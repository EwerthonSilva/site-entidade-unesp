<?

@include_once(__DIR__.'/defines.php');
require_once(__DIR__.'/../dbo/core/dbo-database-functions.php');

if($dbo_default_link_connection = dboDatabaseConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE))
{
	define(HAS_CONNECTION, TRUE);
	dboQuery("SET NAMES 'utf8mb4'");
	dboQuery("SET character_set_connection=utf8mb4");
	dboQuery("SET character_set_client=utf8mb4");
	dboQuery("SET character_set_results=utf8mb4");
}
else
{
	define(HAS_CONNECTION, FALSE);
}

?>