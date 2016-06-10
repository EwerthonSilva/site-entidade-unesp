<?php 
	require_once('../../lib/includes.php'); 
	dboAuth('json');
	//CSRFCheckJson();

	$json_result = array();

	if($_GET['action'] == 'set-pref')
	{
		//se for true ou false
		if($_POST['json_value'] === 'false' || $_POST['json_value'] === 'true')
		{
			$json_value = $_POST['json_value'] === 'true' ? true : false;
		}
		else
		{
			$json_value = $_POST['json_value'];
		}
		meta::setPreference($_POST['json_key'], $json_value, $_POST['meta_key']);
	}

	echo json_encode($json_result);

?>