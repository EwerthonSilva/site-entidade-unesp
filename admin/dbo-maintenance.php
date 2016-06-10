<?php require_once('lib/includes.php'); ?>
<?php
	if(!logadoNoPerfil('Desenv'))
	{
		setMessage('<div class="error">Você <strong>não tem permissão</strong> de acesso à manutenção de dados.</div>');
		header("Location: index.php");
		exit();
	}
?>
<?php require_once('header.php'); ?>

<div class="row">
	<div class="large-12 columns">
		<div class="text-center">
			<p><br /><br /><br />A página de manutenção de dados... está em <strong><u>manutenção</u></strong>!</p>
			<p style="font-size: 100px">xD</p>
		</div>
	</div>
</div>

<?php require_once('footer.php'); ?>