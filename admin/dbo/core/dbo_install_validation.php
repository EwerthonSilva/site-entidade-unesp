<?

include_once(INCLUDE_PATH."/connection.php");

function checkStructure()
{
	if(file_exists(INCLUDE_PATH."/defines.php") && file_exists(INCLUDE_PATH."/db.php")) {
		return true;
	}
	return false;
}

// ----------------------------------------------------------------------------------------------------------------

function checkInstall()
{
	if(file_exists(INCLUDE_PATH."/../dbo/install/")) {
		return true;
	}
	return false;
}

// ----------------------------------------------------------------------------------------------------------------

function badNews($message, $call = '')
{
	?>
		<!doctype html>
		<html lang="pt-BR">
		<head>
			<meta charset="UTF-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

			<title>DBO - Bad news...</title>
			<meta name="description" content="">
			<meta name="author" content="PeixeLaranja">

			<style>

			html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, font, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td { margin: 0; padding: 0; border: 0; outline: 0; font-weight: inherit; font-style: inherit; font-size: 100%; font-family: inherit; vertical-align: baseline; }
			:focus { outline: 0; }
			ol, ul { list-style: none; }
			table { border-collapse: collapse; border-spacing: 0; }
			caption, th, td { text-align: left; font-weight: normal; }
			blockquote:before, blockquote:after,
			q:before, q:after { content: ""; }
			blockquote, q {	quotes: "" ""; }
			textarea { resize: none; }
			img { -ms-interpolation-mode: bicubic; max-width: 100%; box-sizing: border-box; -ms-box-sizing: border-box; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; }

			.clear { clear: both; }

			html, body { line-height: 1; color: #555; background: #EEE; font-family: Trebuchet MS, Arial, Sans-serif; font-size: 15px; }

			h1 { padding: 20px 100px; font-size: 23px; font-weight: bold; letter-spacing: -1px; width: 70%; margin: 100px auto 0; text-align: center; background: #B00; color: #FFF; -webkit-border-radius: 100px; -moz-border-radius: 100px; border-radius: 100px; -webkit-box-shadow: 0px 3px 10px rgba(1,1,1,.3); -moz-box-shadow: 0px 3px 10px rgba(1,1,1,.3); box-shadow: 0px 3px 10px rgba(1,1,1,.3); text-shadow: 0px -1px 0px rgba(1,1,1,.4); box-sizing: border-box; -ms-box-sizing: border-box; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; }
			h1 span { display: block; font-size: 17px; text-transform: small-caps; margin-bottom: 7px; color: #600; text-shadow: 0px 1px 0px rgba(255,255,255,.3); }

			</style>

		</head>
		<body>

			<h1><?= (($call)?('<span>'.$call.'</span>'):('')) ?> <?= $message ?></h1>

		</body>
		</html>
	<?
}

// ----------------------------------------------------------------------------------------------------------------

if(!checkStructure())
{
	if(checkInstall())
	{
		header("Location: dbo/install");
		exit();
	}
	else
	{
		badNews('Você não tem um arquivo defines.php e nem um script de instalação. <br>Como você conseguiu chegar nessa situação? <br>Contate o administrador.', 'Más notícias...');
	}
	exit();
}
elseif (!checkDatabase())
{
	header("Location: dbo/install");
	exit();
}

// ----------------------------------------------------------------------------------------------------------------

function checkDatabase($check = '')
{
	global $link_connection;
	global $db;

	$core_tables = array(
		'pessoa',
		'pessoa_perfil',
		'perfil',
		'permissao',
	);

	if($check == '' || $check == 'connection')
	{
		if(!mysql_ping($link_connection))
		{
			return false;
		}
	}
	if($check == '' || $check == 'structure')
	{
		foreach($core_tables as $key => $value)
		{
			$sql = "SHOW TABLES FROM ".DB_BASE." LIKE '".$value."'";
			$res = mysql_query($sql);
			if(!mysql_affected_rows()) {
				return false;
			}
		}
	}
	return true;
}

// ----------------------------------------------------------------------------------------------------------------

?>