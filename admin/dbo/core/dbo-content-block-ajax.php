<?php
	require_once('../../lib/includes.php');

	$json_result = array();

	if($_GET['action'] == 'update-blocks')
	{
		//checagens de segurança
		secureURLCheck();
		CSRFCheckJson();

		//atualizando conteudo dos blocos
		dbo_content_block::smartSetAndUpdate($_POST);

		//sucesso!
		$json_result['message'] = '<div class="success">Blocos de conteúdo atualizados com <strong>sucesso</strong>!</div>';
	}

	echo json_encode($json_result);
?>