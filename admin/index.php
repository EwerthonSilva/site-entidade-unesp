<?
	if(file_exists('custom-index.php'))
	{
		include('custom-index.php');
	}
	else
	{
		require_once('header.php');
		require_once('auth.php');
		header("Location: ".(defined('DBO_ADMIN_INDEX') ? DBO_ADMIN_INDEX : 'cadastros.php'));
		exit();
		require_once('footer.php');
	}
?>