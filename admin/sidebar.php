<?
	if(file_exists('custom-sidebar.php'))
	{
		include('custom-sidebar.php');
	}
	else
	{
		?>
		<ul class="side-nav" id='cockpit-side-nav' style="padding-top: 0;">
			<? dboSideBarMenu('dbo_admin.php'); ?>
		</ul>		
		<?
	}
?>