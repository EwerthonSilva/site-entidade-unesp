<?
@include_once('defines.php');
$mysql_error = false;

if($link_connection = @mysql_connect(DB_HOST, DB_USER, DB_PASS)) {
	if($db = mysql_select_db(DB_BASE, $link_connection)) {
	} else {
		$mysql_error = 'database';
	}
} else {
	$mysql_error = 'connection';
}
if($mysql_error)
{
	define(HAS_CONNECTION, FALSE);
} else {
	define(HAS_CONNECTION, TRUE);
	mysql_query("SET NAMES 'utf8'");
	mysql_query('SET character_set_connection=utf8');
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_results=utf8');
}
?>