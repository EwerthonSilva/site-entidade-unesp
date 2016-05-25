<?
	require_once('lib/includes.php');

	//decodificando os argumentos
	if($_GET['args'])
	{
		echo dboAdminParseUrlCode($_GET['args']);
	}
	
?>