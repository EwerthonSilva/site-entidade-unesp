<?
/* alerts the user if PHP is running on safe mode. The system is not supposed to run like that. */
if( !ini_get('safe_mode') ){
	set_time_limit(0);
} else { echo "ERRO: PHP running in safe mode... "; }

/* instanciating session and headers */
@session_start();
@ob_start();

/* generetating the main path for all the inclues along the system structure */
define(INCLUDE_PATH, dirname(__FILE__));

/* trying to include the defines.php. If it's not there, the validation engine will try to run the system install. */
@include_once(INCLUDE_PATH.'/defines.php');

/* intancing the generic object class */
require_once(INCLUDE_PATH.'/../dbo/core/obj.php');

/* include the local defines, if exists */
if(file_exists(INCLUDE_PATH.'/../local-defines.php'))
{
	include_once(INCLUDE_PATH.'/../local-defines.php');
}

/* validates the installation. */

ini_set('default_charset', DEFAULT_CHARSET);
ini_set("session.cookie_lifetime","36000"); //makes the session last for 10 hours
ini_set("session.gc_maxlifetime","36000"); //makes the session last for 10 hours
date_default_timezone_set(defined('DBO_TIMEZONE') ? DBO_TIMEZONE : 'America/Sao_Paulo');
header("Content-Type: text/html; charset=".DEFAULT_CHARSET,true);

//checando se existe o pacote de multibyte string
if(function_exists(mb_internal_encoding))
{
	mb_internal_encoding('UTF-8');
}

@include_once(INCLUDE_PATH.'/../dbo/core/dbo_install_validation.php');

//utilizing wordpress hooks on the system
@include_once(INCLUDE_PATH.'/../dbo/core/classes/php-hooks.php');

/* at this point the system is sure the installation is valid. Will start instanciating the classes and stuff. */
require_once(INCLUDE_PATH.'/connection.php');

include_once(INCLUDE_PATH.'/../dbo/core/dbo.php');
//include_once(INCLUDE_PATH.'/../dbo/core/dbo_ui.php');
@include_once(INCLUDE_PATH.'/../beta.php');

//start the CSRF engine
CSRFStart();

//includes all the classes that extend DBO
includeClasses();

//inclui as funcoes custom do usuário
@include_once(INCLUDE_PATH.'/../functions.php');

//inclui funcoes especificas para a área administrativa
if(isDboAdminContext()) {
	include_once(DBO_PATH.'/core/dbo_core_functions-admin.php');
	@include_once(DBO_PATH.'/../functions-admin.php');
}
else
{
	@include_once(DBO_PATH.'/../functions-site.php');
}

/* this generates the color for some buttons in the interface. Don't touch it. */
define (HEADER_R, hexdec(substr(COLOR_HEADER, 1, 2)));
define (HEADER_G, hexdec(substr(COLOR_HEADER, 3, 2)));
define (HEADER_B, hexdec(substr(COLOR_HEADER, 5, 2)));

define (TITLE_R, hexdec(substr(COLOR_TITLE, 1, 2)));
define (TITLE_G, hexdec(substr(COLOR_TITLE, 3, 2)));
define (TITLE_B, hexdec(substr(COLOR_TITLE, 5, 2)));

/* creates a define that is the current php file name */
$pagina_atual = explode("/", $_SERVER['PHP_SELF']);
$pagina_atual = $pagina_atual[sizeof($pagina_atual)-1];
define(PAGINA_ATUAL, $pagina_atual);

/* forces the right domain (same as the DBO_URL) */
checkDomain();

//start of the calculations for the script execution... it's always good to know :P
$start_time = (float) array_sum(explode(' ',microtime()));

$hooks->do_action('dbo_includes_after');

?>