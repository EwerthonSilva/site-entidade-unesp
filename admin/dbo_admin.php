<? require('header.php') ?>
<? require('auth.php') ?>
<? require_once(DBO_PATH.'/core/dbo-ui.php'); ?>
<?
	$params = $_GET['dbo_params'] ? json_decode(base64_decode($_GET['dbo_params']), true) : array();
	if($_GET['dbo_pagina_tipo'])
	{
		$params['tipo'] = dboescape($_GET['dbo_pagina_tipo']);
	}
	$class_name = dboescape($_GET['dbo_mod']);
	$obj = new $class_name();
	$obj->autoAdmin($params);
?>

<script>
	$(document).ready(function(){
		activeMainNav('cadastros');
		activeMainNav('sistema');
	}) //doc.ready
</script>

<? require('footer.php') ?>