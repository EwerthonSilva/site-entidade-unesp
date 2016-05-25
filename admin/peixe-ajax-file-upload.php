<?

require_once('lib/includes.php');

$json_result = dboUpload($_FILES[peixe_ajax_file_upload_file]);

echo json_encode($json_result);

?>