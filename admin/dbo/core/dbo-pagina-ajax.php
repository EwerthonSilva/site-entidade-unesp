<?
	require_once('../../lib/includes.php');
	require_once(DBO_PATH.'/core/dbo-ui.php');
	dboAuth('json');

	global $hooks;

	$json_result = array();
	
	CSRFCheckJson();

	if($_GET['action'] == 'salvar-pagina')
	{
		if(!secureUrl())
		{
			$json_result['message'] = '<div class="error">Erro: Tentativa de acesso insegura</div>';
		}
		else
		{
			$full_url = base64_decode($_GET['full_url']);

			$pag = pagina::smartLoad(array(
				'id' => $_GET['pagina_id'],
				'tipo' => $_GET['tipo'],
			));

			//marcando o antigo status
			$old_status = $pag->status;

			//setando a operação de acordo com o obj pag
			$operation = $pag->id ? 'update' : 'insert';

			//pegando o valor antigo da slug antes de setar o novo
			$old_slug = $pag->slug;

			//setando todos os dados da página por POST
			dboUI::smartSet($_POST, $pag);

			//verificando se o tipo de campo é content-tools, para salvar do jeito certo!
			if($pag->getEditorType() == 'content-tools')
			{
				$pag->texto = dboUI::fieldSQL('content-tools', $_POST['texto'], $pag, array(), $_POST);
			}

			$pag->setAutoFields();

			if($pag->mais())
			{
				dboUI::smartSet($_POST, $pag->{$pag->client_object_key});
			}


			//setando o tipo de página pela url
			$pag->tipo = $_GET['tipo'];

			extract($_system['pagina_tipo'][$_GET['tipo']]);

			//------------------------------------------
			//tratamento de casos especiais ------------
			//------------------------------------------

			//se a pessoa não digitou um título, colocar "sem titulo"
			$pag->titulo = strlen(trim($_POST['titulo'])) ? $_POST['titulo'] : '(sem título)';

			//setando o valor da slug da página
			if(strlen(trim($_POST['slug'])) && $old_slug != trim($_POST['slug']))
			{
				$pag->slug = dboUniqueSlug($_POST['slug'], 'database', array(
					'table' => $pag->getTable(),
					'column' => 'slug',
				));
				//não é ativado quando a página é salva pela primeira vez
				if(class_exists('menu') && strlen(trim($old_slug)))
				{
					menu::updateSlug($old_slug, $pag->slug);
				}
			}
			//a pessoa não esperou a slug ser criada antes de salvar, mas digitou um título
			elseif($pag->titulo != '(sem título)' && !strlen(trim($pag->slug)))
			{
				$pag->slug = dboUniqueSlug($_POST['titulo'], 'database', array(
					'table' => $pag->getTable(),
					'column' => 'slug',
				));
			}

			//se não colocou data, usar a data atual.
			if($pag->status != 'rascunho' && $pag->status != 'pendente')
			{
				$pag->data = !strlen(trim($_POST['data'])) ? $pag->now() : $pag->data;
			}

			//se o status for "publicado" mas a data for maior do que agora, setar o status para "agendado"
			if($pag->status == 'publicado' && strlen(trim($_POST['data'])) && dataHoraSQL($_POST['data']).":00" > dboNow())
			{
				$pag->status = 'agendado';
			}

			//Autor somente pode ser alterado por quem tem permissão administrativa no tipo de página.
			$pag->autor = hasPermission('admin', 'pagina-'.$tipo) && $_POST['autor'] ? $_POST['autor'] : loggedUser();

			//Fazendo o unautop do conteudo da página
			//$pag->texto = dboUnautop($pag->texto);

			//hook que faz alterações no objeto logo antes do save/update
			$pag = $hooks->apply_filters('dbo_pagina_pre_save', $pag);
			$pag = $hooks->apply_filters('dbo_pagina_'.$tipo.'_pre_save', $pag);

			//salvando a pagina principal
			$pag->saveOrUpdate();

			//se for extendidda, salva tambem o objeto cliente
			if($pag->mais())
			{
				$pag->mais()->saveOrUpdate();
			}

			//tratando os content blocks. Usamos o smartUpdate da classe.
			dbo_content_block::smartSetAndUpdate($_POST, array(
				'modulo' => 'pagina',
				'modulo_id' => $pag->slug(),
			));

			//tratando as categorias
			//pegamos todas as categorias do banco, e comparamos com o que foi
			//enviado pelo post. vamos acrescentando as que já não estiverem no banco.
			if($pag->tipo != 'pagina')
			{
				$categorias_cadastradas = $pag->getCategoryIds();
				foreach((array)$_POST['categoria'] as $cat);
				{
					if($cat != 0 && !in_array($cat, $categorias_cadastradas)) 
					{
						$pag->addCategoria($cat);
					}
				}
				//depois, fazemos um merge do que foi submetido com o que já existia no banco
				$todas_categorias = array_merge($categorias_cadastradas, (array)$_POST['categoria']);
				//e finalmente fazemos um diff para saber quais devem ser removidas.
				$categorias_a_remover = array_diff($todas_categorias, (array)$_POST['categoria']);
				//se tiver alguma coisa para remover, remove.
				if(sizeof($categorias_a_remover))
				{
					foreach($categorias_a_remover as $cat)
					{
						$pag->removeCategoria($cat);
					}
				}
				//dando uma limpada...
				unset($todas_categorias);
				unset($categorias_cadastradas);
				unset($categorias_a_remover);
			}

			//fazendo reload dos elementos críticos
			$json_result['reload'][] = '#breadcrumb-item-atual';
			$json_result['reload'][] = '#wrapper-publicacao';
			$json_result['reload'][] = '#wrapper-imagem-destacada';
			if(hasPermission('admin', 'pagina-'.$tipo)) { $json_result['reload'][] = '#wrapper-autor'; }
			$json_result['reload_url'] = $pag->keepUrl(array('dbo_update='.$pag->id, '!dbo_new'), array('url' => $full_url));

			//se for um insert, atualiza o action do formulário para update.
			if($operation == 'insert')
			{
				$form_action = secureUrl('dbo/core/dbo-pagina-ajax.php?action=salvar-pagina&tipo='.$_GET['tipo'].'&pagina_id='.$pag->id."&full_url=".base64_encode($dbo->keepUrl(array(
					'dbo_update='.$pag->id,
					'!dbo_new'
				), array(
					'url' => $pag->keepUrl('!dbo_new&!dbo_update', array('url' => $full_url))
				))));
				$json_result['eval'] = '$("#form-pagina").attr("action", "'.$form_action.'"); ';
			}
			$json_result['eval'] .= 'setTimeout(function(){ paginaInit(); }, 500); ';

			//setando a mensagem de sucesso
			if($pag->status == $old_status)
			{
				$msg_part = 'atualizad'.$genero.'';
			}
			else
			{
				if($pag->status == 'publicado')
				{
					$msg_part = 'publicad'.$genero.'';
				}
				elseif($pag->status == 'agendado')
				{
					$msg_part = 'agendad'.$genero.'';
				}
				elseif($pag->status == 'rascunho')
				{
					$msg_part = 'salv'.$genero.' como rascunho';
				}
				elseif($pag->status == 'pendente')
				{
					$msg_part = 'marcad'.$genero.' como pendente';
				}
				elseif($pag->status == 'lixeira')
				{
					$msg_part = 'enviad'.$genero.' para lixeira';
				}
			}
			$json_result['message'] = '<div class="success">'.ucfirst($titulo).' <strong>'.$msg_part.'</strong> com sucesso.</div>';
		}
	}
	//mandando uma página para a lixeira
	elseif($_GET['action'] == 'lixeira')
	{
		if(!secureUrl())
		{
			$json_result['message'] = '<div class="error">Tentativa de acesso insegura</div>';
		}
		else
		{
			//primeiro descobrimos se a pessoa que está tentando mandar esta página para a lixeira tem permissão para fazer isso
			$excluido = pagina::mandarParaLixeira($_GET['pagina_id'], array('single' => true));
			if($excluido > 0)
			{
				$json_result['reload'][] = '#list-item-'.$excluido;
				$json_result['reload'][] = '.list-numero-itens';
				$json_result['reload'][] = '#list-status-selector';
				$json_result['message'] = '<div class="success">Item enviado com sucesso para a <strong>lixeira</strong>.</div>';
			}
			else
			{
				$json_result['message'] = '<div class="error">Erro: Nenhum item pôde ser mandado para a lixeira. Você não tem permissão para isso.</div>';
			}
		}
	}
	//mandando uma página para a lixeira a partir do form
	elseif($_GET['action'] == 'lixeira-from-form')
	{
		if(!secureUrl())
		{
			$json_result['message'] = '<div class="error">Tentativa de acesso insegura</div>';
		}
		else
		{
			//primeiro descobrimos se a pessoa que está tentando mandar esta página para a lixeira tem permissão para fazer isso
			$excluido = pagina::mandarParaLixeira($_GET['pagina_id'], array('single' => true));
			if($excluido > 0)
			{
				$pag = new pagina($_GET['pagina_id']);
				setMessage('<div class="success">Item enviado com sucesso para a <strong>lixeira</strong>.</div>');
				$json_result['redirect'] = DBO_URL.'/../dbo_admin.php?dbo_mod=pagina&dbo_pagina_tipo='.$pag->tipo;
			}
			else
			{
				$json_result['message'] = '<div class="error">Erro: Nenhum item pôde ser mandado para a lixeira. Você não tem permissão para isso.</div>';
			}
		}
	}
	//mandando uma página para a lixeira
	elseif($_GET['action'] == 'excluir')
	{
		if(!secureUrl())
		{
			$json_result['message'] = '<div class="error">Tentativa de acesso insegura</div>';
		}
		else
		{
			//primeiro descobrimos se a pessoa que está tentando mandar esta página para a lixeira tem permissão para fazer isso
			$excluido = pagina::excluirDefinitivamente($_GET['pagina_id'], array('single' => true));
			if($excluido > 0)
			{
				$json_result['reload'][] = '#list-item-'.$excluido;
				$json_result['reload'][] = '.list-numero-itens';
				$json_result['reload'][] = '#list-status-selector';
				$json_result['message'] = '<div class="success">Item <strong>excluído</strong> com sucesso.</div>';
			}
			else
			{
				$json_result['message'] = '<div class="error">Erro: Nenhum item pôde ser excluído. Você não tem permissão para isso.</div>';
			}
		}
	}
	//restaurando uma página da lixeira
	elseif($_GET['action'] == 'restaurar')
	{
		if(!secureUrl())
		{
			$json_result['message'] = '<div class="error">Tentativa de acesso insegura</div>';
		}
		else
		{
			//primeiro descobrimos se a pessoa que está tentando mandar esta página para a lixeira tem permissão para fazer isso
			$restaurado = pagina::restaurarDaLixeira($_GET['pagina_id'], array('single' => true));
			if($restaurado > 0)
			{
				$json_result['reload'][] = '#list-item-'.$restaurado;
				$json_result['reload'][] = '.list-numero-itens';
				$json_result['reload'][] = '#list-status-selector';
				$json_result['message'] = '<div class="success">Item restaurado com sucesso.</div>';
			}
			else
			{
				$json_result['message'] = '<div class="error">Erro: Nenhum item pôde ser restaurado. Você não tem permissão para isso.</div>';
			}
		}
	}
	//mandando uma página para a lixeira
	elseif($_GET['action'] == 'lixeira-multi')
	{
		//primeiro descobrimos se a pessoa que está tentando mandar esta página para a lixeira tem permissão para fazer isso
		$excluido = pagina::mandarParaLixeira($_POST['pagina_ids']);
		if($excluido > 0)
		{
			$json_result['reload'][] = '#list-table-rows';
			$json_result['reload'][] = '.list-numero-itens';
			$json_result['reload'][] = '#list-status-selector';
			$json_result['message'] = '<div class="success"><strong>'.$excluido.' ite'.($excluido > 1 ? 'ns' : 'm').'</strong> enviado'.($excluido > 1 ? 's' : '').' com sucesso para a <strong>lixeira</strong>.</div>';
		}
		else
		{
			$json_result['message'] = '<div class="error">Erro: Nenhum item pôde ser mandado para a lixeira. Você não tem permissão para isso.</div>';
		}
	}
	//excluindo varias paginas definitivamente
	elseif($_GET['action'] == 'excluir-multi')
	{
		//primeiro descobrimos se a pessoa que está tentando mandar esta página para a lixeira tem permissão para fazer isso
		$excluido = pagina::excluirDefinitivamente($_POST['pagina_ids']);
		if($excluido > 0)
		{
			$json_result['reload'][] = '#list-table-rows';
			$json_result['reload'][] = '.list-numero-itens';
			$json_result['reload'][] = '#list-status-selector';
			$json_result['message'] = '<div class="success"><strong>'.$excluido.' ite'.($excluido > 1 ? 'ns' : 'm').' excluiído'.($excluido > 1 ? 's' : '').'</strong> com sucesso.</div>';
		}
		else
		{
			$json_result['message'] = '<div class="error">Erro: Nenhum item pôde ser excluído. Você não tem permissão para isso.</div>';
		}
	}
	//restaurando uma página da lixeira
	elseif($_GET['action'] == 'restaurar-multi')
	{
		//primeiro descobrimos se a pessoa que está tentando mandar esta página para a lixeira tem permissão para fazer isso
		$restaurado = pagina::restaurarDaLixeira($_POST['pagina_ids']);
		if($restaurado > 0)
		{
			$json_result['reload'][] = '#list-table-rows';
			$json_result['reload'][] = '.list-numero-itens';
			$json_result['reload'][] = '#list-status-selector';
			$json_result['message'] = '<div class="success"><strong>'.$restaurado.' ite'.($restaurado > 1 ? 'ns' : 'm').'</strong> restaurado'.($restaurado > 1 ? 's' : '').' com sucesso.</div>';
		}
		else
		{
			$json_result['message'] = '<div class="error">Erro: Nenhum item pôde ser restaurado. Você não tem permissão para isso.</div>';
		}
	}
	elseif($_GET['action'] == 'get-new-slug')
	{
		$pag = new pagina();
		$slug = dboUniqueSlug($_POST['slug'], 'database', array(
			'table' => $pag->getTable(),
			'column' => 'slug',
		));
		$json_result['eval'] = '$("#pagina-slug").val("'.$slug.'"); $("#slug-label").text("'.$slug.'"); $("#wrapper-pagina-slug").animate({ opacity: 1 }, "fast", function(){ $("#wrapper-slug-edit").hide(); $("#wrapper-slug-view").show(); });';
	}
	elseif($_GET['action'] == 'destacar')
	{
		secureURLCheck();
		$pag = new pagina($_GET['pagina_id']);
		$pag->destaque = 1;
		$pag->update();
	}
	elseif($_GET['action'] == 'remover-destaque')
	{
		secureURLCheck();
		$pag = new pagina($_GET['pagina_id']);
		$pag->destaque = 0;
		$pag->update();
	}
	elseif($_GET['action'] == 'ativar')
	{
		secureURLCheck();
		$pag = new pagina($_GET['pagina_id']);
		$pag->inativo = 0;
		$pag->update();
	}
	elseif($_GET['action'] == 'desativar')
	{
		secureURLCheck();
		$pag = new pagina($_GET['pagina_id']);
		$pag->inativo = 1;
		$pag->update();
	}

	echo json_encode($json_result);

?>