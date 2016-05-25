<?php
	require_once('../../lib/includes.php');

	$json_result = array();

	if(!logadoNoPerfil('Desenv'))
	{
		$json_result['message'] = '<div class="error">Erro: Permissão negada.</div>';
	}
	else
	{
		//fazendo a sincronização dos campos no banco de dados... primeiro precisa carregar os módulos na sessão
		require_once(DBO_PATH.'/dbomaker/actions.php');
		getDiskModules(array('all_modules' => true));

		//salvando as mensagens de sucesso da sincronização
		ob_start();
		syncDatabase();
		$ob_result = ob_get_clean();

		//removendo os módulos da memoria.
		unset($_SESSION['dbomaker_modulos']);

		if(strlen(trim($ob_result)))
		{
			$json_result['eval'] = singleLine('alert("'.addslashes($ob_result).'")');
		}

		//futuramente colocar hooks aqui.

		$json_result['message'] = '<div class="success">Sincronização bem sucedida.</div>';
	}

	echo json_encode($json_result);

?>