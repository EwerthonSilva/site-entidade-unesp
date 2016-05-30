<?
	define(BREAD_CRUMB_NAME, 'Site Entidade Unesp');
	define(HEADER_NAME, '<span class="hide-for-small inline">Eventos Site Entidade Unesp</span><span class="show-for-small inline">Site Entidade Unesp</span>');
	define(SYSTEM_SLUG, 'siteentidadeunesp');

	$_system['module_blacklist'] = array(
		'pagina',
		'menu',
		'meta',
		'categoria',
		'dbo_slider',
		'dbo_slider_slide',
	);

	define(CENTRAL_DE_ACESSOS_PATH, '/www/portal2/central');
	require_once(CENTRAL_DE_ACESSOS_PATH."/global-defines.php");

?>
