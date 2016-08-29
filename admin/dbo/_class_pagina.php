<?

/* ================================================================================================================== */
/* DBO CLASS FILE FOR MODULE 'pagina' =========================================== AUTO-CREATED ON 11/06/2015 01:53:43 */
/* ================================================================================================================== */

/* IMPORTANT: This file is generated only in the first DBO sync, what means you should edit only via text editor. */

if(!class_exists('pagina'))
{
	class pagina extends dbo
	{

		var $client_object_key = '__client_key';

		/* smart constructor: will perform load() upon numeric argument and loadAll() upon string argument */
		function __construct($foo = '', $params = array())
		{
			parent::__construct('pagina');
			if($foo != '')
			{
				if(is_numeric($foo))
				{
					$this->id = $foo;
					$this->load();
				}
				elseif(is_string($foo))
				{
					$this->loadAll($foo);
				}
			}
		}

		//chamando metodos que não existem.
		//o padrão é pagina_<tipo-da-pagina>_<metodo>
		function __call($name, $args)
		{
			$method_name = 'pagina_'.$this->tipo.'_'.$name;
			return $method_name($this, $args);
		}

		//your methods here
		static function smartLoad($params = array())
		{
			global $_system;
			extract($params);

			$obj = new self();

			//load por id
			if($slug || $id || $tipo)
			{
				$campo = $slug ? 'slug': 'id';
				if(!$tipo)
				{
					$sql = "SELECT tipo FROM ".$obj->getTable()." WHERE ".$campo." = '".dboescape(${$campo})."'";
					$res = dboQuery($sql);
					if(dboAffectedRows())
					{
						$lin = dboFetchObject($res);
						$tipo = $lin->tipo;
					}
				}
				$ext_mod = $_system['pagina_tipo'][$tipo]['extension_module'];
				if($ext_mod)
				{	
					$ext_mod = new $ext_mod();
					$sql_part = "
						LEFT JOIN ".$ext_mod->getTable()." ON
							".$ext_mod->getTable().".".$ext_mod->getPk()." = ".$obj->getTable().".id
					";
				}
				$sql = "
					SELECT * FROM ".$obj->getTable()."
					".$sql_part."
					WHERE ".$obj->getTable().".".$campo." = '".dboescape(${$campo})."';
				";
			}
			elseif($categorias)
			{
				//implementar depois.
			}

			//se de fato existe um modulo cliente...
			if($ext_mod)
			{
				//seta o objeto como host de dados
				$obj->{$obj->client_object_key} = $ext_mod;
				$obj->{$obj->client_object_key}->setHostObject($obj);
			}
		
			$obj->query($sql);

			return $obj;
		}

		function queryPaginas($params = array())
		{
			global $_system;
			extract($params);

			$this->clearData();

			$part_where = array();

			//valores default
			$tipo = $tipo !== null ? $tipo : 'pagina';
			$show_all = $show_all !== null ? $show_all : false;
			$order_by = $order_by !== null ? $order_by : 'titulo';
			$order = $order !== null ? $order : 'ASC';
			$limit = $limit !== null ? $limit : '';
			if($where) $part_where[] = $where;
			if($slug) $part_where[] = "slug = '".$slug."'";
			$custom_page = $tipo == 'pagina' ? false : true;

			//verifica se é um módulo extendido
			if(!is_array($tipo))
			{
				$ext_mod = $_system['pagina_tipo'][$tipo]['extension_module'];
			}

			//se é extendido, seta o objeto host
			if($ext_mod)
			{
				$ext_mod = new $ext_mod();
				$this->{$this->client_object_key} = $ext_mod;
				$this->{$this->client_object_key}->setHostObject($this);
				$part_select_extended = ", ".$ext_mod->getTable().".*";
				$part_join_extended = "
					LEFT JOIN ".$ext_mod->getTable()." ON
						".$ext_mod->getTable().".".$ext_mod->getPk()." = ".$this->getTable().".id					
				";
			}
			
			//pegando todas as paginas por categorias
			if($cat || $custom_page)
			{
				$part_join_categorias = "
					".(!$cat ? "LEFT" : "")." JOIN pagina_categoria ON
						pagina_categoria.pagina = pagina.id 
						".($cat ? " AND pagina_categoria.categoria IN(".$cat.") " : "")."
				";
			}
			
			//fazendoa busca por termo
			if(strlen(trim($term)))
			{
				$parts = array();
				$terms = explode(" ", trim(preg_replace('/\s+/is', ' ', $term)));
				foreach($terms as $key => $value)
				{
					$parts[] = "(
						titulo LIKE '%".$value."%' OR
						subtitulo LIKE '%".$value."%' OR
						resumo LIKE '%".$value."%' OR
						texto LIKE '%".$value."%'
					)";
				}
				if(sizeof($parts))
				{
					$part_where[] = implode(" AND ", $parts);
				}
			}

			//variaveis do banco de dados
			$tabela = $this->getTable();

			//verificando se deve listar um tipo ou todos os tipos de página
			if($tipo !== false)
			{
				$part_where[] = "tipo IN ('".implode("','", (array)$tipo)."')";
			}

			//query parts
			if(!$show_all)
			{
				$part_where[] = "status = 'publicado'";
				$part_where[] = "data <= '".dboNow()."'";
				$part_where[] = "inativo = 0";
			}

			$sql = "
				SELECT 
					".($pagination ? "SQL_CALC_FOUND_ROWS" : "")."
					pagina.*
					".$part_select_extended."
					".($custom_page ? ", GROUP_CONCAT(pagina_categoria.categoria) AS categorias_ids" : "")."
				FROM 
					".$tabela."
					".$part_join_extended."
					".$part_join_categorias."
				WHERE
					".implode(" AND ", $part_where)."
				GROUP BY
					pagina.slug
				ORDER BY 
					".$order_by." 
				".($limit ? "LIMIT ".$limit : "")."
			";

			//se tiver paginação, coloca os limits.
			if($pagination) { $this->forcePagination($pagination); }

			$this->query($sql);
		}

		function mais()
		{
			return is_object($this->getClientObject(0)) ? $this->getClientObject(0) : false;
		}

		function slug()
		{
			return $this->slug;
		}
		
		function data($params = array())
		{
			extract($params);
			$formato = $formato !== null ? $formato : 'd/M/Y H:i';
			if($ago)
			{
				return '<span title="'.dboDate($formato, strtotime($this->data)).'">'.ago($this->data).'</span>';
			}
			return dboDate($formato, strtotime($this->data));
		}

		function titulo()
		{
			return $this->titulo;
		}

		function texto()
		{
			//verifica se é content tools
			if($this->getEditorType() == 'content-tools')
			{
				return $this->_texto->frontEnd(array(
					'template' => $this->getTemplate(),
				));
			}
			return dboContent($this->texto);
		}

		function resumo($size = 440, $params = array())
		{
			extract($params);
			$more = $more ? $more : ' [...]';
			if(strlen(trim($this->resumo)))
			{
				return textOnly(trim($this->resumo));
			}
			else
			{
				return maxString(trim(textOnly(dbo_strip_shortcodes(strip_tags($this->_texto->html())))), $size, array('more' => $more));
			}
		}

		function permalink()
		{
			global $_system;
			$function_name = 'pagina_'.$this->tipo.'_permalink';
			if(!function_exists($function_name))
				return SITE_URL.($this->slugPrefix() ? '/'.$this->slugPrefix() : '').'/'.($_system['pagina_tipo'][$this->tipo]['slug_date'] === true ? date('Y/m/', strtotime($this->data)) : '').$this->slug(); 
			return $function_name($this);
		}

		function slugPrefix()
		{
			global $_system;
			return $_system['pagina_tipo'][$this->tipo]['slug_prefix'];
		}

		static function startPaginaEngine()
		{
			if(thisPage().".php" == PAGINA_ENGINE_FILE)
			{
				pagina::paginaEngine();
			}
			elseif(thisPage().".php" == PAGINA_ADMIN_FILE && $_GET['dbo_mod'] == 'pagina' && $_GET['dbo_new'] == 1)
			{
				pagina::redirecionarParaNovaPagina();
			}
		}

		static function redirecionarParaNovaPagina()
		{
			require_once(DBO_PATH.'/core/dbo-pagina-admin.php');
			$pag = paginaCriarRascunhoAutomatico(array(
				'created_by' => loggedUser(),
				'tipo' => $_GET['dbo_pagina_tipo'],
			));
			//hedirecionando para a página nova
			header("Location: ".PAGINA_ADMIN_FILE.'?dbo_mod=pagina&dbo_pagina_tipo='.$pag->tipo.'&dbo_update='.$pag->id);
			exit();
		}

		static function paginaEngine()
		{
			global $_system;
			global $_pagina;
			global $_pagina_backup;
			global $_category_tree;
			global $_pagina_tipo;
			global $_slug;

			$_slug = $_slug !== null ? $_slug : $_GET['slug'];

			if($_slug)
			{
				//verificamos se a página existe tentando pegar seu tipo no banco
				$_pagina = new pagina();
				$_pagina->query("SELECT tipo FROM pagina WHERE slug = '".dboescape($_slug)."'");

				//a página existe no banco
				if($_pagina->size())
				{
					//fazemos a query completa
					queryPaginas(array(
						'slug' => dboescape($_slug),
						'tipo' => $_pagina->tipo,
					));
					//setando o tipo de página no contexto global

					//depois da pagina carregada, inicia a possibilidade de backup
					$_pagina_backup = array();

					$_pagina_tipo = $_pagina->tipo;
					//primeiro checa a especificidade por tipo e pagina
					if(file_exists($_pagina->tipo.'-'.$_pagina->slug.'.php'))
					{
						include($_pagina->tipo.'-'.$_pagina->slug.'.php');
						exit();
					}
					//fallback para a pagina padrão do tipo especificado
					elseif($_pagina->tipo != 'pagina' && file_exists($_pagina->tipo.'.php'))
					{
						include($_pagina->tipo.'.php');
						exit();
					}
				}
				//se não existe a página requisitada no banco, quer dizer que está tentando acessar algum outro arquivo. Então redirecionamos para ele.
				elseif(file_exists($_slug))
				{
					//isso dá loop infinito... consertar depois
					header("Location: ".SITE_URL."/".$_slug);
					exit();
				}
				elseif(file_exists('404.php'))
				{
					include('404.php');
					exit();
				}
				else
				{
					echo "404";
					exit();
				}
			}
		}			

		function renderStatusSelector($params = array())
		{
			extract($params);
			$active = $active ? $active : 'tudo';
			$total_geral = 0;

			$sql = "
				SELECT 
					COUNT(*) AS total,
					status
				FROM 
					".$this->getTable()."
				WHERE
					tipo = '".$tipo."' AND
					status <> 'rascunho-automatico'
					".((!hasPermission('all', 'pagina-'.$tipo))?(" AND autor = '".loggedUser()."'"):(''))."
				GROUP BY
					status
			";
			$res = dboQuery($sql);
			if(dboAffectedRows())
			{
				while($lin = dboFetchObject($res))
				{
					if($lin->status != 'lixeira')
					{
						$total_geral += $lin->total;
					}
					$status[$lin->status] = $lin->total;
				}
			}

			$status_order = array(
				'publicado' => 'Publicad'.$genero.'s',
				'agendado' => 'Agendad'.$genero.'s',
				'rascunho' => 'Rascunhos',
				'lixeira' => 'Lixeira'
			);

			ob_start();
			?>
			<dl class="sub-nav no-margin" id="list-status-selector">
				<dd class="<?= $active == 'tudo' ? 'active' : '' ?>"><a href="#" class="peixe-reload" peixe-reload="#list-table,.acoes-em-massa,#list-pagination,#list-pagination-bottom,.list-numero-itens,#list-search,#list-data-selector" data-keep-url="!pagina_status&!pag&!s&!m">Tudo <span class="font-12" style="font-weight: normal; opacity: .7;">(<?= $total_geral ?>)</span></a></dd>
				<?
					foreach($status_order as $status_slug => $status_titulo)
					{
						if(in_array($status_slug, array_keys($status_order)) && $status[$status_slug])
						{
							?>
							<dd class="<?= $active == $status_slug ? 'active' : '' ?>"><a href="#" class="peixe-reload" peixe-reload="#list-table,.acoes-em-massa,#list-pagination,#list-pagination-bottom,.list-numero-itens,#list-search,#list-data-selector" data-keep-url="pagina_status=<?= $status_slug ?>&!pag&!s&!m"><?= $status_titulo ?> <span class="font-12" style="font-weight: normal; opacity: .7;">(<?= $status[$status_slug] ?>)</span></a></dd>
							<?
						}
					}
				?>
			</dl>						
			<?
			return ob_get_clean();
		}

		function getUrlOrderBy($coluna)
		{
			return $this->keepUrl('order_by='.$coluna.'&order='.($_GET['order_by'] == $coluna && $_GET['order'] == 'ASC' ? 'DESC' : 'ASC'));
		}

		function getLinkOrderBy($coluna, $titulo)
		{
			if($_GET['order_by'] == $coluna)
			{
				if($_GET['order'] == 'DESC')
				{
					$icon = ' <i class="fa fa-caret-down"></i>';
				}
				else
				{
					$icon = ' <i class="fa fa-caret-up"></i>';
				}
			}
			return '<a href="'.$this->getUrlOrderBy($coluna).'" class="peixe-reload" peixe-reload="#list-table,#list-pagination,.pagination,#list-view-selector,.list-numero-itens">'.$titulo.$icon.'</a>';
		}

		//seta detalhes desta pagina no campo detail. Os detalhes são armazenados como um objeto JSON encodado
		function setDetail($key, $value)
		{
			$detail = json_decode($this->detail, true);
			$detail[$key] = $value;
			$this->detail = json_encode($detail);
		}

		function getDetail($key)
		{
			$detail = json_decode($this->detail, true);
			return $detail[$key];
		}

		function removeDetail($key)
		{
			$detail = json_decode($this->detail, true);
			unset($detail[$key]);
			$this->detail = json_encode($detail);
		}

		static function registerMenus(&$modulos)
		{
			global $_system;
			if(is_array($_system['pagina_tipo']) && sizeof($_system['pagina_tipo']))
			{
				foreach($_system['pagina_tipo'] as $slug => $details)
				{
					$key = safeArrayKey($details['cockpit_order_by'], $modulos);
					
					if($details['icone'])
					{
						$modulos[$key]['icon'] = $details['icone'];
					}
					else
					{
						if(file_exists(DBO_PATH."/../images/module_icons/pagina-".$slug.".png"))
						{
							$modulos[$key]['icon'] = 'pagina-'.$slug.".png";
						} else {
							$modulos[$key]['icon'] = "_icone_generico.png";
						}
					}
					$modulos[$key]['titulo'] = ucfirst($details['titulo_big_button'] ? $details['titulo_big_button'] : $details['titulo_plural']);
					$modulos[$key]['var'] = 'pagina-'.$slug;
					$modulos[$key]['custom_url'] = 'dbo_admin.php?dbo_mod=pagina&dbo_pagina_tipo='.$slug;
				}
			}
		}

		static function mandarParaLixeira($ids, $params = array())
		{
			$ids = (array)$ids;
			extract($params);

			$nro_excluidos = 0;

			if(sizeof($ids))
			{
				foreach($ids as $id)
				{
					$pag = new pagina($id);
					if(hasPermission('delete', 'pagina-'.$pag->tipo) && (hasPermission('all', 'pagina-'.$pag->tipo) || $pag->autor == loggedUser()))
					{
						$pag->setDetail('last_status', $pag->status);
						$pag->status = 'lixeira';
						$pag->deleted_on = dboNow();
						$pag->deleted_by = loggedUser();
						$pag->update();
						if($single)
						{
							return $pag->id;
						}
						$nro_excluidos++;
					}
				}
			}
			return $nro_excluidos;
		}

		static function excluirDefinitivamente($ids, $params = array())
		{
			$ids = (array)$ids;
			extract($params);

			$nro_excluidos = 0;

			if(sizeof($ids))
			{
				foreach($ids as $id)
				{
					$pag = new pagina($id);
					if(hasPermission('delete', 'pagina-'.$pag->tipo) && (hasPermission('all', 'pagina-'.$pag->tipo) || $pag->autor == loggedUser()))
					{
						if($pag->hasExtensionModule())
						{
							$pag->deleteExtensionModule();
						}
						$pag->forceDelete();
						if($single)
						{
							return $pag->id;
						}
						$nro_excluidos++;
					}
				}
			}
			return $nro_excluidos;
		}

		static function restaurarDaLixeira($ids, $params = array())
		{
			$ids = (array)$ids;
			extract($params);

			$nro_restaurados = 0;

			if(sizeof($ids))
			{
				foreach($ids as $id)
				{
					$pag = new pagina($id);
					if(hasPermission('delete', 'pagina-'.$pag->tipo) && (hasPermission('all', 'pagina-'.$pag->tipo) || $pag->autor == loggedUser()))
					{
						$pag->status = $pag->getDetail('last_status');
						$pag->removeDetail('last_status');
						$pag->deleted_on = $pag->null();
						$pag->deleted_by = 0;
						$pag->update();
						if($single)
						{
							return $pag->id;
						}
						$nro_restaurados++;
					}
				}
			}
			return $nro_restaurados;
		}

		static function excluirNaoSalvos($params = array())
		{
			global $_system;
			extract($params);
			$pag = new pagina();
			$sql = "
				SELECT id, tipo FROM ".$pag->getTable()." WHERE status = 'rascunho-automatico' AND created_by = '".$created_by."' AND tipo = '".$tipo."';
			";
			$pag->query($sql);
			if($pag->size())
			{
				do {
					if($pag->hasExtensionModule())
					{
						$pag->deleteExtensionModule();
					}
					$pag->forceDelete();
				}while($pag->fetch());
			}
		}

		function hasExtensionModule()
		{
			global $_system;
			return $_system['pagina_tipo'][$this->tipo]['extension_module'];
		}

		function deleteExtensionModule()
		{
			global $_system;
			$ext = $_system['pagina_tipo'][$this->tipo]['extension_module'];
			$ext = new $ext("WHERE pagina = '".$this->id."'");
			if($ext->size())
			{
				$ext->forceDelete();
			}
		}

		static function criarNova($titulo, $params = array())
		{
			/* Params
				- tipo: tipo de página a ser inserida, default: pagina
				- slug: slug da página, default: dboUniqueSlug() do $titulo
				- subtitulo: subtitulo da página a ser inserida, default: ''
				- resumo: resumo da página a ser inserida, default: ''
				- texto: texto
				- data: data de publicação, default dboNow()
				- autor: id do autor da página, default loggedUser()
				- status: status, default: publicado
				- categorias: ids das categorias separadas por vírgula. Só para paginas customizadas
				- mais: array contendo as colunas e valores do módulo extendido
			*/

			global $_system;

			extract($params);

			$tipo = $tipo === null ? 'pagina' : $tipo;
			$subtitulo = $subtitulo === null ? '' : $subtitulo;
			$resumo = $resumo === null ? '' : $resumo;
			$slug = $slug === null ? $titulo : $slug;
			$data = $data === null ? dboNow() : $data;
			$status = $status === null ? 'publicado' : $status;
			$autor = $autor === null ? loggedUser() : $autor;
			$inativo = $inativo === null ? 0 : $inativo;
			$categorias = explode(',', $categorias);
			
			$pag = new pagina();

			//detectando o tipo de página
			if($tipo != 'pagina')
			{
				$ext_mod = $_system['pagina_tipo'][$tipo]['extension_module'];

				//se é extendido, seta o objeto host
				if($ext_mod)
				{
					$ext_mod = new $ext_mod();
					$pag->{$pag->client_object_key} = $ext_mod;
					$pag->{$pag->client_object_key}->setHostObject($pag);
				}
			}

			//setando todos os parametros no objeto
			foreach($params as $key => $value)
			{
				$pag->{$key} = $value;
				if($pag->mais())
				{
					$pag->mais()->{$key} = $value;
				}
			}

			$pag->titulo = $titulo;
			$pag->tipo = $tipo;
			$pag->slug = dboUniqueSlug($slug, 'database', array(
				'table' => $pag->getTable(),
				'column' => 'slug',
			));
			$pag->data = $data;
			$pag->status = $status;
			$pag->autor = $autor;
			$pag->inativo = $inativo;
			$pag->created_by = loggedUser();
			$pag->created_on = dboNow();

			//salvando a página e o modulo extendido
			$pag->saveOrUpdate();
			if($pag->mais())
			{
				$pag->mais()->saveOrUpdate();
			}

			//tratando a inserção de categorias
			if(sizeof($categorias))
			{
				foreach($categorias as $categoria_id)
				{
					$pag->addCategoriaRecursiva($categoria_id);
				}
			}

		}

		static function renderMenuAdminStructure($pagina_tipo)
		{
			global $_system;
			ob_start();
			$pagina = new pagina("WHERE status = 'publicado' AND deleted_by = 0 AND tipo = '".$pagina_tipo."' ORDER BY titulo");
			if($pagina->size())
			{
				?>
				<li class="accordion-navigation">
					<a href="#acc-<?= $pagina_tipo ?>"><?= ucfirst($_system['pagina_tipo'][$pagina_tipo]['titulo_plural']) ?></a>
					<div id="acc-<?= $pagina_tipo ?>" class="content">
						<ul class="no-bullet font-14 no-margin">
							<?
								do {
									?>
									<li><input type="checkbox" name="item-pagina[<?= $pagina->id ?>]" id="pagina-<?= $pagina->id ?>" data-titulo="<?= htmlSpecialChars($pagina->titulo()) ?>" data-slug="<?= $_system['pagina_tipo'][$pagina_tipo]['slug_prefix'] ? $_system['pagina_tipo'][$pagina_tipo]['slug_prefix']."/" : '' ?><?= $pagina->slug(); ?>" data-pagina_id="<?= $pagina->id ?>" data-tipo="pagina"/> <label for="pagina-<?= $pagina->id ?>"><?= $pagina->titulo(); ?></label></li>
									<?
								}while($pagina->fetch());
							?>
						</ul>
						<hr class="small">
						<div class="row">
							<div class="large-5 columns"><a href="#" class="trigger-selecionar-todas-paginas top-2">Selecionar tod<?= $_system['pagina_tipo'][$pagina_tipo]['genero'] ?>s</a></div>
							<div class="large-7 columns text-right"><span class="button radius small no-margin trigger-adicionar-paginas secondary">Adicionar ao menu <i class="fa-arrow-right fa"></i></span></div>
						</div>
					</div>
				</li>
				<?
			}
			return ob_get_clean();
		}

		function imagemUrl($params = array())
		{
			$params['size'] = $params['size'] ? $params['size'] : 'medium';
			extract($params);

			if($this->imagem_destaque)
			{
				$url = DBO_URL."/upload/dbo-media-manager/";
				$image = $this->imagem_destaque;
				if(file_exists(DBO_PATH."/upload/dbo-media-manager/thumbs/".$size.'-'.$image)) 
				{
					$url .= 'thumbs/';
					$image = $size.'-'.$image;
				} 
				$url .= $image;
			}
			else
			{
				preg_match('/<img.+src=[\'"](?P<src>.+)[\'"].*>/i', $this->texto, $image);
				$image = $image['src'];

				//tratamento do tamanho pedido pelo usuário
				if($size && $image)
				{
					//separa o arquivo da url
					$parts = explode('/', $image);
					$file = array_pop($parts);
					$url = implode('/', $parts).'/';
					
					//separa o tamanho do arquivo
					$parts = explode('-', $file);

					//verifica se era um arquivo composto
					if(sizeof($parts) > 1)
					{
						array_shift($parts);
						$parts = implode('-', $parts);

						//tenta achar o arquivo com tamanho espeficicado pelo usuário
						if(file_exists(filterMediaManagerPath($url.$size.'-'.$parts)))
						{
							$file = $size.'-'.$parts;
						}
					}
					$url = $url.$file;
				}
				else
				{
					$url = $image;
				}
				$url = filterMediaManagerUrl($url);
			}
			if(!$url && $show_placeholder)
			{
				$url = DBO_IMAGE_PLACEHOLDER;
			}
			return $url;
		}

		function imagem($params = array())
		{
			extract($params);
			return $this->_imagem_destaque->imagem($params);
		}

		function imagemAjustada($params = array())
		{
			$image = $this->imagemUrl($params);
			return imagemAjustada($image, $params);
		}

		function getCategoryIds($params = array())
		{
			extract($params);
			$join = $this->getDetails('categoria')->join;

			$result = array();

			$sql = "SELECT ".$join->chave2." FROM ".$join->tabela_ligacao." WHERE ".$join->chave1." = '".$this->id."';";
			$res = dboQuery($sql);
			if(dboAffectedRows())
			{
				while($lin = dboFetchObject($res))
				{
					$result[] = $lin->{$join->chave2};
				}
			}
			return $result;
		}

		function addCategoria($cat_id)
		{
			$join = $this->getDetails('categoria')->join;
			$tabela = $join->tabela_ligacao;
			$sql = "INSERT INTO ".$tabela." (pagina, categoria) VALUES ('".$this->id."', '".$cat_id."')";
			dboQuery($sql);
		}

		function addCategoriaRecursiva($cat_id)
		{
			$join = $this->getDetails('categoria')->join;
			$tabela = $join->tabela_ligacao;
			$sql = "INSERT INTO ".$tabela." (pagina, categoria) VALUES ('".$this->id."', '".$cat_id."')";
			dboQuery($sql);
			
			//verificando se tem mãe
			$cat = new categoria($cat_id);
			if($cat->size() && $cat->mae > 0)
			{
				//primeiro verifica se já não está lá.
				$sql = "SELECT id FROM ".$tabela." WHERE pagina = '".$this->id."' AND categoria = '".$cat->mae."'";
				dboQuery($sql);
				if(!dboAffectedRows())
				{
					$this->addCategoriaRecursiva($cat->mae);
				}
			}
		}

		function removeCategoria($cat_id)
		{
			$join = $this->getDetails('categoria')->join;
			$tabela = $join->tabela_ligacao;
			$sql = "DELETE FROM ".$tabela." WHERE pagina = '".$this->id."' AND categoria = '".$cat_id."'";
			dboQuery($sql);
		}

		function categorias($separator = null, $params = array())
		{
			/*
			* @params
			*  somente_folhas: true | false - retorna somente as categorias mais específicas
			*  classes: ... - classes CSS separadas por espaço
			*/
			global $_category_tree;
			extract($params);
			if($this->categorias_ids != 0)
			{
				$links = array();
				if(!$_category_tree[$this->tipo])
				{
					$_category_tree[$this->tipo] = categoria::getCategoryStructure($this->tipo);
				}
				$categorias_ids = explode(',', $this->categorias_ids);
				foreach($categorias_ids as $cat_id)
				{
					$cat_info = categoria::getCategoriaInfo($cat_id, $_category_tree[$this->tipo], $params);
					if($cat_info)
					{
						$links[$cat_info['slug']] = '<a '.($classes ? 'classes="'.$classes.'"' : '').' href="'.SITE_URL.'/'.$cat_info['full_slug'].'">'.ucfirst($cat_info['nome']).'</a>';
					}
				}
				if($separator)
				{
					return implode($separator, $links);
				}
				return $links;
			}
			return false;
		}

		function hideFormField($field)
		{
			global $_system;
			//primeiro verifica a permissão específica do usuário
			$pref = meta::getPreference('hide_'.$field, 'form_pagina_'.$this->tipo.'_prefs');

			//se não foi setado pelo usuário, tenta ver no array de settings da slug especifica
			if($pref === null) {
				$pref = in_array($field, (array)$_system['settings']['pagina']['slug'][$this->slug]['hidden_fields']);
			}

			//se não tem para a slug específica, testa com o tipo de pagina
			if($pref === false) {
				$pref = in_array($field, (array)$_system['pagina_tipo'][$this->tipo]['hidden_fields']);
			}
			return $pref;
		}

		function destaque()
		{
			return $this->destaque;
		}

		function inativo()
		{
			return $this->inativo;
		}

		function setContentBlock($name, $value = null, $params = array())
		{
			$params['modulo'] = 'pagina';
			$params['modulo_id'] = $this->slug;
			return dbo_content_block::set($name, $value, $params);
		}

		function getContentBlock($name = false, $params = array())
		{
			$params['modulo'] = $params['modulo'] ? $params['modulo'] : 'pagina';
			$params['modulo_id'] = $params['modulo_id'] ? $params['modulo_id'] : $this->slug;
			return dbo_content_block::get($name, $params);
		}

		/*function renderContentBlock($name, $params = array())
		{
			$params['modulo'] = $params['modulo'] ? $params['modulo'] : 'pagina';
			$params['modulo_id'] = $params['modulo_id'] ? $params['modulo_id'] : $this->slug;
			return dbo_content_block::render($name, $params);
		}*/

		function getEditorType()
		{
			return PAGINA_EDITOR_TYPE;
		}

		function getTemplate()
		{
			return file_exists(DBO_TEMPLATE_PATH.'/pagina-'.$this->slug.'.php') ? 'pagina-'.$this->slug : 'pagina-blank';
		}

		function getListIdentifier($column)
		{
			if($column == 'autor')
			{
				return $this->_autor->nome;
			}
			return $this->{$column};
		}

		function getListLabel($column, $pagina_scheme)
		{
			global $_system;
			if($column == 'nome_autor')
			{
				return 'Autor';
			}
			elseif($column == 'categorias')
			{
				return 'Categorias';
			}
			elseif(strpos($column, 'ext_') === 0)
			{
				if(!$pagina_scheme['extension_module_object'])
				{
					$ext = new $pagina_scheme['extension_module'];
					$pagina_scheme['extension_module_object'] = new $ext();
				}
				$column = preg_replace('/^ext_/is', '', $column);
				return $pagina_scheme['extension_module_object']->__module_scheme->campo[$column]->titulo;
			}
			else
			{
				return $this->__module_scheme->campo[$column]->titulo;
			}
		}

		function getListColumnStyles($column)
		{
			if($column == 'nome_autor' || $column == 'categorias')
			{
				return ' width: 15%; ';
			}
			elseif($column == 'data')
			{
				return ' width: 10%; ';
			}
			elseif($column == 'titulo')
			{
				return '';
			}
			else
			{
				return ' width: 10%; ';
			}
		}

	} //class declaration
} //if ! class exists

//definindo o nome padrão para o arquivo de processamento de página
define(PAGINA_ENGINE_FILE, 'pagina.php');
define(PAGINA_ADMIN_FILE, 'dbo_admin.php');
define(PAGINA_EDITOR_TYPE, 'content-tools');

//funções para templating
function pagina()
{
	global $_pagina;
	return $_pagina;
}

function paginaCategorias($separator = null, $params = array())
{
	global $_pagina;
	return $_pagina->categorias($separator, $params);
}

function paginaData($params = array())
{
	global $_pagina;
	return $_pagina->data($params);
}

function paginaSplitter($rest = null, $params = array())
{
	global $_pagina;
	return $_pagina->splitter($rest, $params);
}

function paginaTitulo()
{
	global $_pagina;
	return $_pagina->titulo();
}

function paginaTipo()
{
	global $_pagina_tipo;
	return $_pagina_tipo;
}

function paginaId()
{
	global $_pagina;
	return $_pagina->id;
}

function paginaSlug()
{
	global $_pagina;
	if(is_object($_pagina))
		return $_pagina->slug();
}

function paginaSubtitulo()
{
	global $_pagina;
	return $_pagina->subtitulo;
}

function paginaResumo($size = 440, $params = array())
{
	global $_pagina;
	extract($params);
	return $_pagina->resumo($size, $params);
}

function paginaTexto()
{
	global $_pagina;
	return $_pagina->texto();
}

function paginaPermalink()
{
	global $_pagina;
	return $_pagina->permalink();
}

function paginaImagem($params = array())
{
	global $_pagina;
	return $_pagina->imagem($params);
}

function paginaImagemAjustada($params = array())
{
	global $_pagina;
	return $_pagina->imagemAjustada($params);
}

function paginaImagemUrl($params = array())
{
	global $_pagina;
	return $_pagina->imagemUrl($params);
}

function queryPaginas($params = array())
{
	global $_pagina;
	global $_pagina_backup;
	
	if(is_array($_pagina_backup))
		$_pagina_backup[] = clone $_pagina;

	return $_pagina->queryPaginas($params);
}

function resetQueryPaginas()
{
	global $_pagina;
	global $_pagina_backup;

	if(is_array($_pagina_backup) && sizeof($_pagina_backup))
	{
		$_pagina = array_pop($_pagina_backup);
		$_pagina_backup = false;
	}
}

function listaPaginas()
{
	global $_pagina;
	return $_pagina->fetch();
}

function haPaginas()
{
	global $_pagina;
	return $_pagina->size();
}

function breadcrumbs($params = array())
{
	extract($params);
	/*
		@params
		 prepend: array - adiciona elementos no início do breadcrumb
	*/
	$bca = breadcrumbsArray($params);
	if(sizeof($bca))
	{
		?>
		<ul class="breadcrumbs <?= $classes ?>" style="<?= $styles ?>">
			<?php
				foreach($bca as $data)
				{
					$foo = explode('/', $_GET['slug']);
					if(end($foo) != '' && end($foo) == $data['slug'])
					{
						?>
						<li><span class="show-for-sr">Atual: </span> <?= $data['text'] ?></li>
						<?
					}
					else
					{
						?>
						<li class="<?= end($foo) == $data['slug'] ? 'current' : '' ?>">
							<?php
								if(strlen(trim($data['url'])))
								{
									?>
									<a href="<?= $data['url'] ?>"><?= $data['text'] ?></a>
									<?php
								}
								else
								{
									?>
									<span><?= $data['text'] ?></span>
									<?php
								}
							?>
						</li>
						<?php
					}
					?>
					<?php
				}
				if($extended && is_array($data['children']))
				{
					?>
					<li>
						<?php
							foreach($data['children'] as $ext)
							{
								?><a href="<?= $ext['url'] ? $ext['url'] : $ext['full_slug'] ?>"><?= $ext['text'] ? $ext['text'] : ($ext['nome'] ? $ext['nome'] : $ext['titulo']) ?></a><?php
								if(end($data['children']) != $ext) echo "<br />";
							}
						?>
					</li>
					<?php
				}
			?>
		</ul>		
		<?php
	}
}

function breadcrumbsArray($params = array())
{
	global $_category_tree;
	global $_pagina_tipo;
	extract($params);

	$bca = array();

	if($append)
	{
		$bca = $append;
	}

	if(thisPage() == 'categoria' && $_GET['slug'])
	{
		$parts = explode('/', $_GET['slug']);
		if(sizeof($parts))
		{
			foreach($parts as $slug)
			{
				$info = categoria::getCategoriaInfo($slug, $_category_tree[$_pagina_tipo], array(
					'key' => 'slug',
				));
				$bca[] = array(
					'slug' => $info['slug'],
					'url' => $info['full_slug'],
					'text' => $info['nome'],
					'children' => $info['children'],
				);
			}
		}
	}
	return $bca;
}

function siteDescricao()
{
	global $_conf;
	return $_conf->site_descricao;
}

function siteBody($params = array())
{
	global $hooks;
	extract($params);
	$hooks->do_action('site_body');
}

function siteHead($params = array())
{
	global $_conf;
	global $hooks;
	extract($params);

	$og = array();	
	
	ob_start();

	//locale é sempre a mesma coisa
	$og['og:locale'] = 'pt_BR';

	//checando se estamos em uma página
	if(thisPage().'.php' == PAGINA_ENGINE_FILE)
	{
		//se é home
		if(paginaSlug() == 'home')
		{
			$og['og:type'] = 'website';
			$og['og:title'] = siteTitulo();
			$og['og:description'] = siteDescricao();
			$og['og:url'] = SITE_URL;
			$og['og:image'] = SITE_URL.'/images/og.png';
		}
		else
		{
			$og['og:type'] = 'article';
			$og['og:title'] = siteTitulo();
			$og['og:description'] = paginaResumo();
			$og['og:url'] = paginaPermalink();
			$og['og:image'] = paginaImagemUrl(array('size' => 'medium'));
		}
	}

	$og['og:site_name'] = $_conf->site_titulo;

	foreach($og as $prop => $content)
	{
		?>
		<meta property="<?= $prop ?>" content="<?= $content ?>" />
		<?php
	}

	$hooks->do_action('site_head');

	return ob_get_clean();
}

//hook para criação dinamica das paginas
global $hooks;
$hooks->add_action('dbo_includes_after', 'pagina::startPaginaEngine');

//definindo os tipos de páginas deste sistema.
global $_system;
$aux = array(
	'tipo' => 'pagina',
	'titulo' => 'página',
	'titulo_plural' => 'páginas',
	'icone' => 'file-text',
	'genero' => 'a',
	'cockpit_order_by' => -100,
);

if(!$_system['pagina_tipo']['pagina'])
	$_system['pagina_tipo']['pagina'] = array();

$_system['pagina_tipo']['pagina'] = array_merge($aux, $_system['pagina_tipo']['pagina']);
unset($aux);

function siteTitulo($titulo = false, $params = array())
{
	global $_conf;
	global $_pagina;

	extract($params);

	$separator = (($separator)?($separator):('|'));

	config::init();

	$retorno = '';

	//se existe um define com o titulo do site
	if(defined('SITE_TITULO'))
	{
		$retorno = SITE_TITULO." ".$separator." ";
	}

	//se existe uma página instanciada, colocar o titulo dela antes do titulo do site.
	if(is_object($_pagina) && $_pagina->slug() != 'home')
	{
		$retorno = paginaTitulo()." ".$separator." ";
	}

	//se existe uma titulo setado nas configurações, usa ele.
	if(strlen(trim($_conf->getSiteTitulo())))
	{
		$retorno .= $_conf->getSiteTitulo();
	}
	else
	{
		$retorno .= $titulo;
	}

	return $retorno;
}

function siteConfig()
{
	global $_conf;
	if(class_exists('config'))
	{
		config::init();
	}
	else
	{
		$_conf = new obj();
	}
	return $_conf;
}

function siteBodyClass($params = array())
{
	extract($params);
	return 'pagina-'.paginaSlug();
}

//auto admin das páginas, realiza toda a lógica para as páginas do sistema. Override da função no DBO.
function auto_admin_pagina($params = array())
{
	require_once(DBO_PATH.'/core/dbo-pagina-admin.php');
	return autoAdminPagina($params);
}

?>