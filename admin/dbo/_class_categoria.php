<?

/* ================================================================================================================== */
/* DBO CLASS FILE FOR MODULE 'categoria' ======================================== AUTO-CREATED ON 20/07/2015 13:52:00 */
/* ================================================================================================================== */

/* IMPORTANT: This file is generated only in the first DBO sync, what means you should edit only via text editor. */

if(!class_exists('categoria'))
{
	class categoria extends dbo
	{
		/* smart constructor: will perform load() upon numeric argument and loadAll() upon string argument */
		function __construct($foo = '')
		{
			parent::__construct('categoria');
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

		//sobrecarga
		function save()
		{
			$id = parent::save();
			if(is_numeric($this->mae))
			{
				//tira a flag de folha do pai, se for o caso.
				$cat = new categoria($this->mae);
				if($cat->folha == 1)
				{
					$cat->folha = 0;
					$cat->update();
				}
			}
			return $id;
		}

		function update($rest = null)
		{	
			$id = parent::update();
			if(is_numeric($this->mae_antiga))
			{
				$this->updateStatusMae($this->mae_antiga);
			}
			if(is_numeric($this->mae))
			{
				$this->updateStatusMae($this->mae);
			}
			return $id;
		}

		function delete()
		{
			//no delete, tem que remover antes todos os filhos e remover a relação de paginas com os filhos
			$filhos = new categoria("WHERE mae = '".$this->id."'");
			if($filhos->size())
			{
				do {
					$filhos->delete();
				}while($filhos->fetch());
			}

			//desassociando todas as paginas desta categoria
			$sql = "DELETE FROM ".$this->getTabelaLigacaoPagina()." WHERE categoria = ".$this->id.";";
			dboQuery($sql);

			//deletando efetivamente.
			$mae = $this->mae;
			$id = parent::delete();
			if($mae)
			{
				$this->updateStatusMae($mae);
			}
			return $id;
		}

		function updateStatusMae($mae_id)
		{
			$mae = new categoria($mae_id);
			$mae->folha = $mae->temFilhos() ? 0 : 1;
			$mae->update();
		}

		function getTabelaLigacaoPagina()
		{
			$pag = new pagina();
			$det = $pag->getDetails('categoria');
			return $det->join->tabela_ligacao;
		}

		function temFilhos()
		{
			$filhos = new categoria("WHERE mae = '".$this->id."'");
			if($filhos->size()) return true;
			return false;
		}

		static function getCategoryStructure($pagina_tipo = null, $params = array())
		{
			global $_system;
			extract($params);

			$tree = array();

			$cat = new categoria();
			$sql = "
				SELECT * FROM ".$cat->getTable()." 
				WHERE 
					mae ".($mae ? " = '".$mae."' " : " IS NULL AND 
					pagina_tipo = '".$pagina_tipo."' ")." 
				ORDER BY order_by, nome
			";
			$cat->query($sql);
			if($cat->size())
			{
				do {
					$dados = array(
						'id' => $cat->id,
						'slug' => $cat->slug,
						'nome' => $cat->nome,
						'full_slug' => $_system['pagina_tipo'][$pagina_tipo]['slug_prefix'].'/categorias/'.$cat->slug,
						'descricao' => $cat->descricao,
						'imagem' => $cat->imagem,
					);
					if($cat->folha == 0)
					{
						$params['full_slug_prefix'] = $_system['pagina_tipo'][$pagina_tipo]['slug_prefix'].'/categorias/'.$cat->slug.'/';
						$dados['children'] = categoria::getCategoryChildren($cat, $params);
					}
					$tree[] = $dados;
				}while($cat->fetch());
			}
			return $tree;
		}

		static function getCategoryChildren($cat, $params = array())
		{
			extract($params);

			$tree = array();

			$filha = new categoria();
			$sql = "
				SELECT * FROM ".$cat->getTable()." 
				WHERE 
					mae = '".$cat->id."'
				ORDER BY order_by, nome;
			";
			$filha = new categoria();
			$filha->query($sql);
			if($filha->size())
			{
				do {
					$dados = array(
						'id' => $filha->id,
						'slug' => $filha->slug,
						'nome' => $filha->nome,
						'full_slug' => $full_slug_prefix.$filha->slug,
					);
					if($filha->folha == 0)
					{
						$params['full_slug_prefix'] = $full_slug_prefix.$filha->slug.'/';
						$dados['children'] = categoria::getCategoryChildren($filha, $params);
					}
					$tree[] = $dados;
				}while($filha->fetch());
			}
			return $tree;
		}

		static function startCategoriaEngine()
		{
			if(thisPage().".php" == CATEGORIA_ENGINE_FILE)
			{
				categoria::categoriaEngine();
			}
		}
		
		static function categoriaEngine()
		{
			if(thisPage().".php" == CATEGORIA_ENGINE_FILE)
			{
				global $_system;
				global $_pagina;
				global $_pagina_backup;
				global $_category_tree;
				global $_pagina_tipo;
				global $_categoria;
				global $_slug;

				$_slug = $_slug !== null ? $_slug : $_GET['slug'];

				//situação para categorias especificas
				if($_GET['slug'])
				{
					$slug = preg_replace('/\/$/is', '', $_GET['slug']);
					$slug = explode("/", $slug);
					$slug = array_pop($slug);
					$_categoria = new categoria("WHERE slug = '".dboescape($slug)."'");
					if($_categoria->size())
					{
						$_pagina = new pagina();
						$_pagina_tipo = $_categoria->pagina_tipo;
						$_category_tree[$_categoria->pagina_tipo] = categoria::getCategoryStructure($_pagina_tipo);
						queryPaginas(array(
							'cat' => $_categoria->id,
							'tipo' => $_pagina_tipo,
						));

						$_pagina_backup = array();
						
						//testa especificidade pelas cutomizações do tempalte
						if(file_exists($_pagina_tipo.'-categoria-'.$_categoria->slug.'.php'))
						{
							include($_pagina_tipo.'-categoria-'.$_categoria->slug.'.php');
							exit();
						}
						elseif(file_exists($_pagina_tipo.'-categoria.php'))
						{
							include($_pagina_tipo.'-categoria.php');
							exit();
						}
					}
					//se não existe a página requisitada no banco, quer dizer que está tentando acessar algum outro arquivo. Então redirecionamos para ele.
					elseif(file_exists($_GET['slug']))
					{
						//isso dá loop infinito... consertar depois
						header("Location: ".SITE_URL."/".$_GET['slug']);
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
				elseif($_GET['slug_prefix'])
				{
					foreach($_system['pagina_tipo'] as $tipo => $data)
					{
						if($data['slug_prefix'] == $_GET['slug_prefix'])
						{
							$_pagina = new pagina();
							$_pagina_tipo = $tipo;
							queryPaginas(array(
								'tipo' => $tipo,
							));
							$_category_tree[$tipo] = categoria::getCategoryStructure($tipo);

							//testa especificidade pelas cutomizações do tempalte
							if(file_exists($_pagina_tipo.'-categorias.php'))
							{
								include($_pagina_tipo.'-categorias.php');
								exit();
							}
							/*elseif(file_exists($_pagina_tipo.'-categoria.php'))
							{
								include($_pagina_tipo.'-categoria.php');
								exit();
							}*/
							else
							{
								include('categorias.php');
								exit();								
							}
						}
					}
				}
			}
		}			

		static function getCategoriaInfo($cat_id, $tree, $params = array())
		{
			extract($params);
			/*
				@params
				 key: id | slug
			*/

			//valores default
			$key = $key !== null ? $key : 'id';

			//busca recursiva pelas informações deste id
			if(sizeof($tree))
			{
				foreach($tree as $node)
				{
					if($node[$key] == $cat_id)
					{
						//se for para retornar somente folhas, mas este tiver filhos, não retorna.
						if($somente_folhas && is_array($node['children']))
						{
							return false;
						}
						return $node;
					}
					elseif(is_array($node['children']))
					{
						$return = categoria::getCategoriaInfo($cat_id, $node['children'], $params);
					}

					if($return)
					{
						return $return;
					}
				}
			}
		}

		static function renderTreeMenu($tree, $params = array())
		{
			extract($params);
			if(sizeof($tree))
			{
				ob_start();
				?>
				<ul class="<?= $classes ?>">
					<?php
						foreach($tree as $node)
						{
							?>
							<li><a href="<?= SITE_URL ?>/<?= $node['full_slug'] ?>"><?= $node['nome'] ?></a>
							<?php
							if(is_array($node['children']))
							{
								$params['classes'] .= ' children ';
								echo categoria::renderTreeMenu($node['children'], $params);
							}
							?>
							</li>
							<?php
						}
					?>
				</ul>
				<?php
				return ob_get_clean();
			}
		}

		function imagemURL($params)
		{
			$params['size'] = $params['size'] ? $params['size'] : 'medium';
			extract($params);

			return $this->_imagem->url(array(
				'size' => $size,
			));
		}

		function imagemAjustada($params = array())
		{
			return imagemAjustada($this->imagemURL($params), $params);
		}

		function imagem($params = array())
		{
			/* Params:
			   - classes
			   - styles
			*/
			return '<img src="'.$this->imagemURL($params).'" alt="" class="'.$classes.'" style="'.$styles.'">';
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
	
	} //class declaration
} //if ! class exists

//definindo o nome padrão para o arquivo de processamento de página
define(CATEGORIA_ENGINE_FILE, 'categoria.php');

//hook para criação categorias dinâmicas
global $hooks;
$hooks->add_action('dbo_includes_after', 'categoria::startCategoriaEngine');

//funções para templating

function carregaArvoreDeCategorias($pagina_tipo = null, $params = array())
{
	global $_category_tree;
	global $_pagina_tipo;
	$_category_tree[$pagina_tipo] = categoria::getCategoryStructure($pagina_tipo, $params);
	$_pagina_tipo = $pagina_tipo;
}

function arvoreDeCategorias($pagina_tipo = null)
{
	global $_category_tree;
	if($pagina_tipo)
	{
		return $_category_tree[$pagina_tipo];
	}
	return $_category_tree;
}

function menuDeCategorias($params = array())
{
	extract($params);
	global $_category_tree;

	if($pagina_tipo)
	{
		if(!$_category_tree[$pagina_tipo])
		{
			carregaArvoreDeCategorias($pagina_tipo, $params);
		}
		return categoria::renderTreeMenu($_category_tree[$pagina_tipo], $params);
	}
	$keys = array_keys($_category_tree);
	ob_start();
	foreach((array)$keys as $key => $value)
	{
		?>
		<h4><?= $value ?></h4>
		<?php
		echo categoria::renderTreeMenu($_category_tree[$value], $params);
	}
	return ob_get_clean();
}

function categoriaId()
{
	global $_categoria;
	return $_categoria->id;
}

function categoriaNome()
{
	global $_categoria;
	return $_categoria->nome;
}

function categoriaSlug()
{
	global $_categoria;
	return $_categoria->slug;
}

function categoriaDescricao()
{
	global $_categoria;
	return dboContent($_categoria->descricao);
}

function categoriaImagem($params = array())
{
	global $_categoria;
	return $_categoria->imagem($params);
}

function categoriaImagemAjustada($params = array())
{
	global $_categoria;
	return $_categoria->imagemAjustada($params);
}

function categoriaImagemURL($params = array())
{
	global $_categoria;
	return $_categoria->imagemURL($params);
}

function categoriaPermalink($params = array())
{
	global $_categoria;
	global $_system;
	return ($_system['dbo_active_language'] ? $_system['dbo_active_language'].'/' : '').$_categoria->full_slug ? $_categoria->full_slug : $_categoria->slug;
}

function haCategorias()
{
	global $_category_tree;
	global $_pagina_tipo;
	if(sizeof($_category_tree[$_pagina_tipo]))
		return true;
	return false;
}

function listaCategorias()
{
	global $_categoria;
	global $_category_tree;
	global $_pagina_tipo;

	if(!$_categoria)
	{
		$_categoria = new categoria();
	}

	if(sizeof($_category_tree[$_pagina_tipo]))
	{
		$cat = array_shift($_category_tree[$_pagina_tipo]);

		foreach($cat as $key => $value)
		{
			$_categoria->{$key} = $value;
		}
		return true;
	}
	return false;
}

function haSubcategorias()
{
	global $_categoria;
	global $_category_tree;
	global $_pagina_tipo;
	$foo = categoria::getCategoriaInfo($_categoria->id, $_category_tree[$_pagina_tipo]);
	return is_array($foo['children']);
}

function subcategorias($separator, $params = array())
{
	global $_categoria;
	global $_category_tree;
	global $_pagina_tipo;
	$cats = categoria::getCategoriaInfo($_categoria->id, $_category_tree[$_pagina_tipo]);
	$cats = $cats['children'];
	foreach($cats as $cat)
	{
		$return .= '<a href="'.$cat['full_slug'].'">'.$cat['nome'].'</a>'.(end($cats) != $cat ? $separator : '');
	}
	return $return;
}

?>