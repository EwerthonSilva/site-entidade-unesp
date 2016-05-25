<?php 
	require_once('../../lib/includes.php'); 
	dboAuth('json');
	CSRFCheckJson();

	$json_result = array();

	if($_GET['action'] == 'clean-cache')
	{
		$files = glob(DBO_CACHE_PATH.'/*'); // get all file names
		foreach($files as $file){ // iterate files
			if(is_file($file))
				unlink($file); // delete file
		}
		$json_result['message'] = '<div class="success">Cache das páginas limpo com sucesso.</div>';
	}

	echo json_encode($json_result);

?>