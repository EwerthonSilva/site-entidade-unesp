<?
	require_once('../../lib/includes.php');
	if(DBO_ALLOW_UNLOGGED_AJAX_JOINS !== true) {
		require_once('../../auth.php');
	}

	$modulo = dboescape($_GET['module']);
	$field = dboescape($_GET['field']);
	$term = dboescape(trim($_GET['term']));
	$terms = explode(" ", $term);

	$mod = new $modulo();
	$field = $mod->__module_scheme->campo[$field];

	//verifica se o usuário tem permissão de acesso
	if(DBO_ALLOW_UNLOGGED_AJAX_JOINS === true || logadoNoPerfil('Desenv') || hasPermission('access', $modulo))
	{
		//agora verificar se o ajax está habilitado para esta seleção
		if($field->join->ajax)
		{

			$minimo = (($field->join->tamanho_minimo)?($field->join->tamanho_minimo):('3'));

			$mod = $field->join->modulo;
			$mod = new $mod();

			$json_result = array();
			$sql_parts = array();

			//se digitou o minimo de letras, beleza!
			if(strlen($term) >= $minimo)
			{
				//instancia o método de listagem, se existir
				$metodo_listagem = $field->join->metodo_listagem;
				//instancia o método de retorno, se existir
				$metodo_retorno = $field->join->metodo_retorno;

				//caso não exista uma função para fazer o select, usar o padrão do mosulo
				if(!$metodo_listagem)
				{

					if(strlen(trim($field->restricao)))
					{
						eval($field->restricao);
					}

					//implodindo para varios termos
					foreach($terms as $term)
					{
						$sql_parts[] = " ".$field->join->valor." LIKE '%".$term."%' ";
					}
					$sql = "
						SELECT * FROM ".$mod->getTable()."
						WHERE
							( ".implode(" AND ", $sql_parts)." )
							".(strlen(trim($rest)) ? ' AND '.preg_replace('/^WHERE /is', '', $rest) : '')."
							".(!preg_match('/ORDER BY/i', $rest) ? " ORDER BY ".$field->join->order_by : '')."
					";
					$mod->query($sql);
					if($mod->size())
					{
						do {
							//se houver um método de retorno, será chamado no lugar do retorno padrão do módulo.
							$retorno = '';
							$retorno = (($metodo_retorno)?($mod->$metodo_retorno()):($mod->{$field->join->valor}));
							$json_result[] = array('id' => $mod->{$field->join->chave}, 'valor' => $retorno);
						}while($mod->fetch());
					}
				}
				else
				{
					//funcao de listagem especifica do modulo. Esta função deve receber um array de parametros, entre eles os termos que estão sendo buscados.
					$mod->{$metodo_listagem}(array('terms' => $terms));
					if($mod->size())
					{
						do {
							$retorno = '';
							$retorno = (($metodo_retorno)?($mod->$metodo_retorno()):($mod->{$field->join->valor}));
							$json_result[] = array('id' => $mod->{$field->join->chave}, 'valor' => $retorno);
						}while($mod->fetch());
					}
				}
			}
		}
		else
		{
			$json_result[] = "ERRO: Ajax desabilitado para esta operação";
		}
	}
	else
	{
		$json_result[] = "ERRO: Usuário sem permissão de acesso ao módulo '".$modulo."'";
	}

	echo json_encode($json_result);

?>
