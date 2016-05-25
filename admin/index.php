<?
	if(file_exists('custom-index.php'))
	{
		include('custom-index.php');
	}
	else
	{
		require_once('header.php');
		require_once('auth.php');
		header("Location: cadastros.php");
		exit();
		require_once('footer.php');
	}
?>