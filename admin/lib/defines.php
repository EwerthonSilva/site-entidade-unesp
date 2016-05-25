<?

@include_once('db.php');

/* ************************************************************************************************ */
/* CUSTOMIZATIONS ********************************************************************************* */
/* ************************************************************************************************ */

//system definitions
define (SYSTEM_NAME, 'AllPharmaJr'); // System Name
define (SYSTEM_DESCRIPTION, 'Administração'); // System description (be reasonable...)
define (DBO_URL, 'https://www.fcfar.unesp.br/eventos/allpharmajr/admin/dbo'); // http url to the dbo folder (without the last slash '/')

//system colors
define (COLOR_MENU, '#9fbdb5'); // color of the menu background (use hexadecimal)
define (COLOR_HEADER, '#9fbdb5'); // color of the header background (use hexadecimal)
define (COLOR_DESCRIPTION, '#c1d1c6'); // color of the system description in the header (use hexadecimal)
define (COLOR_TITLE, '#e0b88b'); // color for the module and form titles throughout the system (use hexadecimal)

//experimental import of dbo libs into another systems like wordpress. DO NOT USE! It's alpha!
define (DBO_INLINE_LOCAL_STYLES, FALSE);
//system permission module
define (DBO_PERMISSIONS, TRUE);
//default system charset (default is UTF-8, will be changeable in the future... Do not touch it for now!)
define (DEFAULT_CHARSET, 'UTF-8');
//sets if the the system should or not check for the core database tables (pessoa, perfil, pessoa_perfil, permissao) upon login.
define (DBO_CORE_STRUCTURE, TRUE);

//the array with the developer names. the developers are super admins who can access the module and system function
$SUPER_ADMINS = array(
	'jose@fcfar.unesp.br',
	'ewerthon@fcfar.unesp.br',
);

//pages that will use only the full column (will discard the sidebar)
$FULL_PAGES = array(
	'dbo_admin.php',
	'dbo_permissions.php',
);

?>
