<?php
	require('header.php'); 
	require_once(DBO_PATH.'/core/dbo-ui.php');
?>
<style>
	html, body { height: 100%; }
</style>
<?php
	if(!loggedUser())
	{
		?>
		<div class="row absolute-center">
			<div class="small-12 large-12 columns">
				<h1 class="color primary text-center">Login</h1>
				<div style="max-width: 500px; margin: auto;">
					<?= getLoginForm(); ?>
				</div>
			</div>
		</div>
		<?php
			if($_GET['dbo_modal'])
			{
				?>
				<script>
					$(document).ready(function(){
						setTimeout(function(){

							input = $('input[name="pass"]')[0];
							input.focus();
							var event = document.createEvent('TextEvent');
							if ( event.initTextEvent ) {
								event.initTextEvent('textInput', true, true, window, '@@@@@');
								input.dispatchEvent(event);
								input.value = input.value.replace('@@@@@','');
							}
							input.blur();

							input = $('input[name="user"]')[0];
							input.focus();
							var event = document.createEvent('TextEvent');
							if ( event.initTextEvent ) {
								event.initTextEvent('textInput', true, true, window, '@@@@@');
								input.dispatchEvent(event);
								input.value = input.value.replace('@@@@@','');
							}

							//$('input[name="user"]').trigger('change');
							//$('input[name="user"]').focus();
						}, 250);
					}) //doc.ready
				</script>
				<?php
			}
	}
	elseif($_GET['action'] == 'reload-parent')
	{
		?>
		<h2 class="text-center absolute-center">Autenticando...</h2>
		<script>
			setTimeout(function(){
				window.parent.location.reload(false);
			}, 2000);
		</script>
		<?php
	}
	elseif($_GET['action'] == 'logout')
	{
		@session_unset();
		@session_destroy();
		?>
		<h2 class="text-center absolute-center">Realizando o logout...</h2>
		<script>
			setTimeout(function(){
				window.parent.location.reload(false);
			}, 1500);
		</script>
		<?php
	}
	else
	{
		header("Location: ".dboModalMeuPerfilUrl());
	}
?>

<?php require('footer.php'); ?>