<?
	require_once('../../lib/includes.php');

	dboAuth('json');

	$json_result = array();
	
	//criando um item para o editor de menus.
	if($_GET['action'] == 'gerar-dd-item')
	{
		$item = menu::gerarDDItemTemplate($_POST);
		$json_result['eval'] = '$(".dd > .dd-list").append(\''.singleLine($item).'\');';
	}
	elseif($_GET['action'] == 'novo-menu')
	{
		//tem permissão?
		if(!hasPermission('insert', 'menu'))
		{
			$json_result['message'] = '<div class="error">Erro: Permissão negada.</div>';
		}
		//preencheu o nome?
		elseif(!$_POST['nome'])
		{
			$json_result['message'] = '<div class="error">Erro: preencha o nome do menu.</div>';
		}
		else
		{
			$men = new menu("WHERE slug = '".makeSlug($_POST['nome'])."'");
			//já existe um menu com este nome?
			if($men->size())
			{
				$json_result['message'] = '<div class="error">Erro: já existe um menu cadastrado com este nome.</div>';
			}
			//tudo certo!
			else
			{
				$men->nome = $_POST['nome'];
				$men->slug = makeSlug($_POST['nome']);
				$men->estrutura = $men->null();
				$men->profundidade = (($_POST['profundidade'])?($_POST['profundidade']):(1));
				$men->save();
				$json_result['message'] = '<div class="success">Menu criado com sucesso!</div>';
				$json_result['eval'] = 'reloadRightPanel();';
			}
		}
	}
	elseif($_GET['action'] == 'salvar-menu')
	{
		//primeiro, verifica se tem permissão para update de menu
		if(!hasPermission('update', 'menu'))
		{
			$json_result['message'] = '<div class="error">Erro: Permissão negada para alterar menus.</div>';
		}
		else
		{
			$men = new menu($_GET['menu_id']);
			if(!$men->size())
			{
				$json_result['message'] = '<div class="error">Erro: O menu especificado não existe.</div>';
			}
			else
			{
				//tudo ok
				$men->estrutura = json_encode($_POST['menu_data']);
				$men->update();
				$json_result['message'] = '<div class="success">Menu salvo com sucesso!</div>';
			}
		}
	}
	elseif($_GET['action'] == 'delete-menu')
	{
		if(hasPermission('delete', 'menu'))
		{
			$men = new menu($_POST['menu_id']);
			$men->delete();
			$json_result['message'] = '<div class="success">Menu excluído com sucesso.</div>';
			$json_result['eval'] = 'reloadRightPanel();';
		}
	}
	elseif($_GET['action'] == 'load-menu')
	{
		$men = new menu($_POST['menu_id']);
		if(!$men->size())
		{
			$json_result['message'] = '<div class="error">Erro: o menu não existe.</div>';
		}
		else
		{
			$json_result['html']['#menu-canvas'] = $men->getEstruturaAdmin();
			$json_result['eval'] = 'setTimeout(function(){ ddInit(); }, 500)';
		}
	}

	echo json_encode($json_result);

?>