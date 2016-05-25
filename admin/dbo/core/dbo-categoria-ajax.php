<?php 
	require_once('../../lib/includes.php'); 
	require_once(DBO_PATH.'/core/dbo-categoria-admin.php');
	dboAuth('json');
	CSRFCheckJson();

	$json_result = array();

	if($_GET['action'] == 'quick-cadastrar-nova')
	{
		if(!strlen(trim($_POST['nome'])))
		{
			$json_result['message'] = '<div class="error">Erro: Por favor, preencha um nome para a categoria.</div>';
		}
		else
		{
			$cat = new categoria();
			$cat->nome = $_POST['nome'];
			$cat->mae = strlen(trim($_POST['mae'])) ? $_POST['mae'] : $cat->null();
			$cat->pagina_tipo = $_GET['pagina_tipo'];
			$cat->folha = 1;
			$cat->slug = dboUniqueSlug($_POST['nome'], 'database', array(
				'table' => $cat->getTable(),
				'column' => 'slug',
			));
			$cat->save();

			$checked = (array)$_POST['checados'];

			$tree = categoria::getCategoryStructure($_GET['pagina_tipo']);

			$json_result['message'] = '<div class="success">Categoria cadastrada com sucesso!</div>';
			$json_result['html']['#wrapper-categorias-da-pagina'] = renderCategoryCheckboxes($tree, $checked, array(
				'admin' => (hasPermission('admin', 'pagina-'.$cat->pagina_tipo) ? true : false),
			));
			$json_result['html']['#categoria_mae'] = '<option value="">- nenhuma -</option>'.renderCategoryOptions($tree);
			$json_result['eval'] = '$("#categoria_nome, #categoria_mae").val(""); $(".cancel-pub-categorias").trigger("click"); ';
		}
	}
	elseif($_GET['action'] == 'alterar-categoria')
	{

		secureURLCheck();

		//verificando se a categoria existe
		$cat = new categoria($_GET['categoria_id']);
		if($cat->size())
		{
			//verificando se a pessoa escreveu digitou pelo menos um nome
			if(strlen(trim($_POST['nome'])))
			{
				$cat->mae_antiga = $cat->mae;
				//se tudo ok, salva as alterações na categoria.
				require_once(DBO_PATH.'/core/dbo-ui.php');
				dboUI::smartSet($_POST, $cat);
				$cat->folha = $cat->temFilhos() ? 0 : 1;
				$cat->update();
				$json_result['eval'] = singleLine(' $("#modal-dbo-small").foundation("reveal", "close"); ');
				$json_result['reload'][] = '.wrapper-pagina-field-categorias';
				$json_result['message'] = '<div class="success">Categoria <strong>alterada</strong> com sucesso.</div>';
			}
			else
			{
				$json_result['message'] = '<div class="error">Erro: Preencha um nome para a categoria</div>';
			}
		}
		else
		{
			$json_result['message'] = '<div class="error">Erro: A categoria não existe.</div>';
		}
	}
	elseif($_GET['action'] == 'excluir-categoria')
	{
		secureURLCheck();

		$cat = new categoria($_GET['categoria_id']);
		if($cat->size()) $cat->delete();
		$json_result['eval'] = singleLine(' $("#modal-dbo-small").foundation("reveal", "close"); setTimeout(function(){ initCategorias(); }, 500); ');
		$json_result['reload'][] = '.wrapper-pagina-field-categorias';
		$json_result['message'] = '<div class="success">Categoria <strong>excluída</strong> com sucesso.</div>';
	}
	elseif($_GET['action'] == 'sort-categorias')
	{
		if(sizeof((array)$_POST['new_order']))
		{
			//verificando se tem permissão
			list($lixo, $cat_id) = explode('categoria-id-', $_POST['new_order'][0]);
			$cat = new categoria($cat_id);
			if(hasPermission('admin', 'pagina-'.$cat->pagina_tipo))
			{
				for($i = 0; $i <= sizeof($_POST['new_order'])-1; $i++)
				{
					list($lixo, $cat_id) = explode('categoria-id-', $_POST['new_order'][$i]);
					$cat = new categoria($cat_id);
					$cat->order_by = $i;
					$cat->update();
				}
			}
			else
			{
				$json_result['message'] = '<div class="error">Erro: você não tem permissão para ordenar estas categorias.</div>';
			}
		}
				
	}

	echo json_encode($json_result);

?>