<?php

require_once('../../lib/includes.php');

$json_result = array();

if($_GET['action'] == 'importar-dados')
{
	secureUrlCheck();
	CSRFCheckJson();

	//checando se foi alguma coisa selecionada
	if(!sizeof((array)$_POST['importar_dados_outra_lingua']))
	{
		$json_result['message'] = '<div class="error">Erro: Você precisa selecionar <strong>pelo menos 1 campo</strong> para importação.</div>';
	}
	else
	{
		//alterando os idiomas para realizar a importação
		$aux = $_system['dbo_active_language'];
		$_system['dbo_active_language'] = $_POST['source_lang'];

		$pag = new pagina($_GET['pagina_id']);
		foreach($_POST['importar_dados_outra_lingua'] as $key => $value)
		{
			$traduzidos[$value] = $pag->{$value};
		}

		//pegou os dados na linguagem source, volta para a target
		$_system['dbo_active_language'] = $aux;

		foreach((array)$traduzidos as $key => $value)
		{
			$pag->{$key} = $value;
		}
		$pag->update();

		setMessage('<div class="success">Campos importados com <strong>sucesso</strong>!</div>');
		$json_result['eval'] = singleLine('location.reload();');
	}
}

echo json_encode($json_result);

?>