<?

@session_start();
header("Content-Type: text/html; charset=UTF-8",true);

define(DEFAULT_COLOR_MENU, "#9fbdb5");
define(DEFAULT_COLOR_HEADER, "#9fbdb5");
define(DEFAULT_COLOR_DESCRIPTION, "#c1d1c6");
define(DEFAULT_COLOR_TITLE, "#e0b88b");

if($_GET['redirect'] == 1)
{
	header('Location: ../../');
	exit();
}

include('lang/pt-BR.php');

// ----------------------------------------------------------------------------------------------------------------

function populateSession()
{

	@include('../../lib/db.php');
	@include('../../lib/defines.php');

	unset($_SESSION['dbo_install']);

	if(defined('DB_HOST'))                 { $_SESSION['dbo_install']['DB_HOST'] = DB_HOST; }
	if(defined('DB_BASE'))                 { $_SESSION['dbo_install']['DB_BASE'] = DB_BASE; }
	if(defined('DB_USER'))                 { $_SESSION['dbo_install']['DB_USER'] = DB_USER; }
	if(defined('DB_PASS'))                 { $_SESSION['dbo_install']['DB_PASS'] = DB_PASS; }
	if(defined('SYSTEM_NAME'))             { $_SESSION['dbo_install']['SYSTEM_NAME'] = SYSTEM_NAME; }
	if(defined('SYSTEM_DESCRIPTION'))      { $_SESSION['dbo_install']['SYSTEM_DESCRIPTION'] = SYSTEM_DESCRIPTION; }
	if(defined('COLOR_MENU'))              { $_SESSION['dbo_install']['COLOR_MENU'] = COLOR_MENU; }
	if(defined('COLOR_HEADER'))            { $_SESSION['dbo_install']['COLOR_HEADER'] = COLOR_HEADER; }
	if(defined('COLOR_DESCRIPTION'))       { $_SESSION['dbo_install']['COLOR_DESCRIPTION'] = COLOR_DESCRIPTION; }
	if(defined('COLOR_TITLE'))             { $_SESSION['dbo_install']['COLOR_TITLE'] = COLOR_TITLE; }
	if(defined('DBO_URL'))                 { $_SESSION['dbo_install']['DBO_URL'] = DBO_URL; }
	if(defined('DBO_INLINE_LOCAL_STYLES')) { $_SESSION['dbo_install']['DBO_INLINE_LOCAL_STYLES'] = DBO_INLINE_LOCAL_STYLES; }
	if(defined('DBO_PERMISSIONS'))         { $_SESSION['dbo_install']['DBO_PERMISSIONS'] = DBO_PERMISSIONS; }
	if(defined('DEFAULT_CHARSET'))         { $_SESSION['dbo_install']['DEFAULT_CHARSET'] = DEFAULT_CHARSET; }
	if(defined('DBO_CORE_STRUCTURE'))      { $_SESSION['dbo_install']['DBO_CORE_STRUCTURE'] = DBO_CORE_STRUCTURE; }
	if(sizeof($FULL_PAGES))                { $_SESSION['dbo_install']['FULL_PAGES'] = $FULL_PAGES; }
	if(sizeof($SUPER_ADMINS))              { $_SESSION['dbo_install']['SUPER_ADMINS'] = $SUPER_ADMINS; }

	checkPartialInstallation();

	if(!validateAccess())
	{
		//debugAccessValidation();
		@session_destroy();
		@session_unset();
		@session_start();
		$_SESSION['mensagem'][] = "<div class='error'>".ERROR_IRREGULAR_ACCESS."</div>";
		header('Location: ../../login.php');
		exit();
	}
}

// ----------------------------------------------------------------------------------------------------------------

function checkDatabase()
{
	global $_SESSION;
	if($link_connection = @mysql_connect($_SESSION['dbo_install']['DB_HOST'], $_SESSION['dbo_install']['DB_USER'], $_SESSION['dbo_install']['DB_PASS'])) {
		if($db = mysql_select_db($_SESSION['dbo_install']['DB_BASE'], $link_connection)) {
			mysql_query("SET NAMES 'utf8mb4'");
			mysql_query('SET character_set_connection=utf8mb4');
			mysql_query('SET character_set_client=utf8mb4');
			mysql_query('SET character_set_results=utf8mb4');
			return true;
		} else {
			return mysql_error();
		}
	} else {
		return mysql_error();
	}
}

// ----------------------------------------------------------------------------------------------------------------

function setMessage($message)
{
	$_SESSION['dbo_install']['message'][1] = $message;
}

// ----------------------------------------------------------------------------------------------------------------

function hasMessage()
{
	return sizeof($_SESSION['dbo_install']['message']);
}

// ----------------------------------------------------------------------------------------------------------------

function getMessage()
{
	if(sizeof($_SESSION['dbo_install']['message']))
	{
		echo implode('<br>', $_SESSION['dbo_install']['message']);
		unset($_SESSION['dbo_install']['message']);
	}
}

// ----------------------------------------------------------------------------------------------------------------

function checkTables()
{

	checkDatabase();

	$core_tables = array(
		'pessoa',
		'pessoa_perfil',
		'perfil',
		'permissao',
	);

	foreach($core_tables as $key => $value)
	{
		$sql = "SHOW TABLES FROM ".$_SESSION['dbo_install']['DB_BASE']." LIKE '".$value."'";
		$res = mysql_query($sql);
		if(!mysql_affected_rows()) {
			return false;
		}
	}
	return true;
}

// ----------------------------------------------------------------------------------------------------------------

function checkAdmins()
{
	if(checkDatabase() === TRUE)
	{
		if(checkTables())
		{
			$sql = "SELECT id FROM perfil WHERE nome = 'Desenv'";
			$res = mysql_query($sql);
			$lin = mysql_fetch_object($res);
			$id_admin = $lin->id;

			$sql = "SELECT MAX(pessoa) AS pessoa FROM pessoa_perfil WHERE perfil = '".addslashes($id_admin)."'";
			$res = mysql_query($sql);
			if(!mysql_affected_rows())
			{
				return false;
			}
			else
			{
				$lin = mysql_fetch_object($res);
				$id_pessoa = $lin->pessoa;
			}

			$sql = "SELECT * FROM pessoa WHERE id = '".addslashes($id_pessoa)."'";
			$res = mysql_query($sql);
			if(mysql_affected_rows())
			{
				return true;
			}

			return false;
		}
		return false;
	}
	return false;
}

// ----------------------------------------------------------------------------------------------------------------

function makeDefinesFile()
{
	$folder_path = '../../lib/';
	$file = 'defines.php';

	if(!is_writable($folder_path))
	{
		setMessage(STEP3_ERROR_NO_WRITE_PERMISSION_FOLDER);
	}

	if(!$fh = @fopen($folder_path.$file, 'w+'))
	{
		setMessage(STEP3_ERROR_NO_WRITE_PERMISSION_DEFINES." (".checkchmod($folder_path.$file).")");
	}
	else
	{

		fwrite($fh, "<?\n");
		fwrite($fh, "\n");
		fwrite($fh, "@include_once('db.php');\n");
		fwrite($fh, "\n");
		fwrite($fh, "/* ************************************************************************************************ */\n");
		fwrite($fh, "/* CUSTOMIZATIONS ********************************************************************************* */\n");
		fwrite($fh, "/* ************************************************************************************************ */\n");
		fwrite($fh, "\n");
		fwrite($fh, "//system definitions\n");
		fwrite($fh, "define (SYSTEM_NAME, '".mySlasher($_SESSION['dbo_install']['SYSTEM_NAME'])."'); // System Name\n");
		fwrite($fh, "define (SYSTEM_DESCRIPTION, '".mySlasher($_SESSION['dbo_install']['SYSTEM_DESCRIPTION'])."'); // System description (be reasonable...)\n");
		fwrite($fh, "define (DBO_URL, '".mySlasher($_SESSION['dbo_install']['DBO_URL'])."'); // http url to the dbo folder (without the last slash '/')\n");
		fwrite($fh, "\n");
		fwrite($fh, "//experimental import of dbo libs into another systems like wordpress. DO NOT USE! It's alpha!\n");
		fwrite($fh, "define (DBO_INLINE_LOCAL_STYLES, FALSE);\n");
		fwrite($fh, "//system permission module\n");
		fwrite($fh, "define (DBO_PERMISSIONS, ".(($_SESSION['dbo_install']['DBO_PERMISSIONS'] === TRUE)?('TRUE'):('FALSE')).");\n");
		fwrite($fh, "//default system charset (default is UTF-8, will be changeable in the future... Do not touch it for now!)\n");
		fwrite($fh, "define (DEFAULT_CHARSET, 'UTF-8');\n");
		fwrite($fh, "//sets if the the system should or not check for the core database tables (pessoa, perfil, pessoa_perfil, permissao) upon login.\n");
		fwrite($fh, "define (DBO_CORE_STRUCTURE, TRUE);\n");
		fwrite($fh, "\n");
		fwrite($fh, "//the array with the developer names. the developers are super admins who can access the module and system function\n");
		fwrite($fh, "\$SUPER_ADMINS = array(\n");
		if(is_array($_SESSION['dbo_install']['SUPER_ADMINS']))
		{
			foreach($_SESSION['dbo_install']['SUPER_ADMINS'] as $key => $value)
			{
				if(strlen(trim($value)))
				{
					fwrite($fh, "	'".trim($value)."',\n");
				}
			}
		}
		fwrite($fh, ");\n");
		fwrite($fh, "\n");
		fwrite($fh, "//pages that will use only the full column (will discard the sidebar)\n");
		fwrite($fh, "\$FULL_PAGES = array(\n");
		if(is_array($_SESSION['dbo_install']['FULL_PAGES']))
		{
			foreach($_SESSION['dbo_install']['FULL_PAGES'] as $key => $value)
			{
				if(strlen(trim($value)))
				{
					fwrite($fh, "	'".trim($value)."',\n");
				}
			}
		}
		fwrite($fh, ");\n");
		fwrite($fh, "\n");
		fwrite($fh, "?>");

		@fclose($fh);

		populateSession();

	}
}

// ----------------------------------------------------------------------------------------------------------------

function makeDBfile()
{
	$folder_path = '../../lib/';
	$file = 'db.php';

	$fh = @fopen($folder_path.$file, 'w');

	fwrite($fh, "<?\n\n");
	fwrite($fh, "/* ************************************************************************************************ */\n");
	fwrite($fh, "/* DATABASE DEFINITIONS *************************************************************************** */\n");
	fwrite($fh, "/* ************************************************************************************************ */\n");
	fwrite($fh, "\n");
	fwrite($fh, "define (DB_HOST, '".$_SESSION['dbo_install']['DB_HOST']."');\n");
	fwrite($fh, "define (DB_USER, '".$_SESSION['dbo_install']['DB_USER']."');\n");
	fwrite($fh, "define (DB_PASS, '".$_SESSION['dbo_install']['DB_PASS']."');\n");
	fwrite($fh, "define (DB_BASE, '".$_SESSION['dbo_install']['DB_BASE']."');\n\n");
	fwrite($fh, "?>");

	@fclose($fh);
}

// ----------------------------------------------------------------------------------------------------------------

function checkCHMOD($path)
{
	if(file_exists($path))
	{
		return intval(substr(sprintf('%o', @fileperms($path)), -4));
	}
	else
	{
		return 1000;
	}
}

// ----------------------------------------------------------------------------------------------------------------

function bigError($error)
{
	echo "<div class='big-error'>".$error." <a class='button' href='javascript: window.location.reload()'>".WORD_RETRY."?</a></div>";
}

// ----------------------------------------------------------------------------------------------------------------

function validateDBFile()
{
	$file = '../../lib/db.php';
	if(file_exists($file))
	{
		$f = file_get_contents($file);
		if(!strpos($f, 'define (DB_HOST, \''.$_SESSION['dbo_install']['DB_HOST'].'\')')) { return false; }
		if(!strpos($f, 'define (DB_USER, \''.$_SESSION['dbo_install']['DB_USER'].'\')')) { return false; }
		if(!strpos($f, 'define (DB_PASS, \''.$_SESSION['dbo_install']['DB_PASS'].'\')')) { return false; }
		if(!strpos($f, 'define (DB_BASE, \''.$_SESSION['dbo_install']['DB_BASE'].'\')')) { return false; }
		return true;
	}
	return false;
}

// ----------------------------------------------------------------------------------------------------------------

function mySlasher($string)
{
	$string = stripslashes($string);
	$string = str_replace("'", "\'", $string);
	return $string;
}

// ----------------------------------------------------------------------------------------------------------------

function getDefines()
{
	if(!strlen($_SESSION['dbo_install']['SYSTEM_NAME'])) { setMessage(STEP3_ERROR_NO_SYSTEM_NAME); return false; }
	if(!strlen($_SESSION['dbo_install']['SYSTEM_DESCRIPTION'])) { setMessage(STEP3_ERROR_NO_SYSTEM_DESCRIPTION); step3(); return false; }
	if(!strlen($_SESSION['dbo_install']['DBO_URL'])) { setMessage(STEP3_ERROR_NO_DBO_URL); step3(); return false; }
	if(ini_get('allow_url_fopen') == 1)
	{
		if(!@fopen($_SESSION['dbo_install']['DBO_URL']."/core/images/link-order-by-desc.png", 'r'))
		{
			setMessage(STEP3_ERROR_WRONG_DBO_URL); return false;
		}
	}
	if(!strlen(trim(implode('', $_SESSION['dbo_install']['SUPER_ADMINS'])))) { setMessage(STEP3_ERROR_NO_SUPER_ADMINS); return false; }
	return true;
}

// ----------------------------------------------------------------------------------------------------------------

function checkDefines()
{
	if(!strlen($_SESSION['dbo_install']['SYSTEM_NAME'])) { return false; }
	if(!strlen($_SESSION['dbo_install']['SYSTEM_DESCRIPTION'])) { return false; }
	if(!strlen($_SESSION['dbo_install']['DBO_URL'])) { return false; }
	if(ini_get('allow_url_fopen') == 1)
	{
		if(!@fopen($_SESSION['dbo_install']['DBO_URL']."/core/images/link-order-by-desc.png", 'r'))
		{
			return false;
		}
	}
	if(!strlen(trim(implode('', $_SESSION['dbo_install']['SUPER_ADMINS'])))) { return false; }
	return true;
}

// ----------------------------------------------------------------------------------------------------------------

function validateDefinesFile()
{
	return file_exists('../../lib/defines.php');
}

// ----------------------------------------------------------------------------------------------------------------

function debugAccessValidation()
{
	//============= DEBUG ================
	echo "<PRE>";
	var_dump(checkDatabase());
	var_dump(validateDBFile());
	var_dump(getAdmins());
	var_dump(getDefines());
	var_dump(validateDefinesFile());
	echo "</PRE>";
	exit();
	//============= DEBUG ================
}

// ----------------------------------------------------------------------------------------------------------------

function validateAccess()
{
	if(checkDatabase() === true && validateDBFile() && getAdmins() && getDefines() && validateDefinesFile() && !in_array($_SESSION['user'], $_SESSION['dbo_install']['SUPER_ADMINS'])) return false;
	return true;
}

// ----------------------------------------------------------------------------------------------------------------

function statusBar()
{
	?>
	<div class='wrapper-status-bar'>
		<span class='<?= ((checkDatabase() === true)?('ok'):('fail')) ?>'>&bull;</span>
		<span class='<?= ((checkAdmins())?('ok'):('fail')) ?>'>&bull;</span>
		<span class='<?= ((checkDefines())?('ok'):('fail')) ?>'>&bull;</span>
		<span class='<?= ((checkDefines())?('ok'):('fail')) ?>'>&bull;</span>
	</div>
	<?
}

// ----------------------------------------------------------------------------------------------------------------

function checkPartialInstallation()
{
	if(!$_SESSION['user'])
	{
		if(getAdmins())
		{
			foreach(getAdmins() as $key => $value)
			{
				$_SESSION['user'] = $value['user'];
				$_SESSION['user_id'] = $value['id'];
				break;
			}
		}
	}
}

// ----------------------------------------------------------------------------------------------------------------

function getAdmins()
{
	if(checkDatabase() === true)
	{
		$sql = "SELECT id FROM perfil WHERE nome = 'Desenv'";
		$res = mysql_query($sql);
		if(mysql_affected_rows())
		{
			$lin = @mysql_fetch_object($res);
			$id_admin = $lin->id;

			$sql = "SELECT pessoa FROM pessoa_perfil WHERE perfil = '".addslashes($id_admin)."'";
			$res = mysql_query($sql);
			while($lin = @mysql_fetch_object($res))
			{
				$admins[$lin->pessoa]['id'] = $lin->id;
				$sql = "SELECT * FROM pessoa WHERE id ='".addslashes($lin->pessoa)."'";
				$res2 = mysql_query($sql);
				$lin2 = mysql_fetch_object($res2);
				$admins[$lin->pessoa]['nome'] = $lin2->nome;
				$admins[$lin->pessoa]['email'] = $lin2->email;
				$admins[$lin->pessoa]['user'] = $lin2->user;
			}

			if(sizeof($admins))
			{
				return $admins;
			}
			return false;
		}
		else
		{
			return false;
		}
	}
	return false;
}

?>