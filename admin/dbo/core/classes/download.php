<?

$_GET['file']=str_replace("..", "||", $_GET['file']);
$file="../../upload/files/".$_GET['file'];
$name=$_GET['name'];

header("Pragma: public"); // required
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false); // required for certain browsers
header("Content-Disposition: attachment; filename=\"".basename($name)."\";" );
header("Content-Transfer-Encoding: binary");
header("Content-Type: application/force-download");
header("Content-Length: ".filesize($file));
readfile("$file");

exit();

?>