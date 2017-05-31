<?php
	require_once('../../lib/includes.php');

	$json_result = array();
	
	if($_GET['action'] == 'drop-fk-constraints')
	{
		secureUrlCheck();
		CSRFCheckJson();

		$sql = "SELECT concat('ALTER TABLE ', TABLE_NAME, ' DROP FOREIGN KEY ', CONSTRAINT_NAME, ';') AS q FROM information_schema.key_column_usage WHERE CONSTRAINT_SCHEMA = '".DB_BASE."' AND referenced_table_name IS NOT NULL;";
		$res = dboQuery($sql);
		$total = dboAffectedRows();
		if($total)
		{
			while($lin = dboFetchObject($res))
			{
				if(!dboQuery($lin->q))
				{
					$error[] = dboQueryError();
				}
			}
			if(sizeof((array)$error))
			{
				$json_result['message'] = '<div class="error">'.implode("<br />", $error).'</div>';
			}
			else
			{
				$json_result['message'] = '<div class="success">'.$total.' chaves estrangeiras removidas com <strong>sucesso</strong>!</div>';
			}
		}
		else
		{
			$json_result['message'] = '<div class="success">Nenhuma chave estrangeira a ser removida.</div>';
		}

	}
	
	echo json_encode($json_result);

?>