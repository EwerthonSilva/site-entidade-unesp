<?
	if(!file_exists('custom-footer.php'))
	{
		?>
		</div><!-- main-wrap -->
		<script>$(document).foundation();</script>
		<?= getMessage(); ?>
		<div id="modal-change-password" class="reveal-modal smart small" data-reveal></div>
		<div id="modal-dbo-medium" class="reveal-modal smart medium" data-reveal></div>
		<div id="modal-dbo-small" class="reveal-modal smart small" data-reveal></div>
		<? dboFooter(); ?>
		<? $hooks->do_action('footer'); ?>
		</body>
		</html>
		<?
	}
	else
	{
		include('custom-footer.php');
	}
?>
