<? require('header.php') ?>
<? require_once('auth.php') ?>
<?
	if(!logadoNoPerfil('Desenv') && !hasPermission('painel-cadastros'))
	{
		?>
		<div class="row">
			<div class="large-12 columns">
				<div class="panel">
					<h3 class="text-center"><br />Seu usuário <strong>não tem</strong> permissão de acesso ao painel de <strong><?= DBO_TERM_CADASTROS ?></strong>.<br /><br /></h3>
				</div>
			</div>
		</div>
		<?php
	}
	else
	{
		$nro_itens_sidebar = getItemsSidebar();
		?>

		<div class="row">
			<div class="large-12 columns">
				<?= dboBreadcrumbs(array(
					'stack' => array(
						array(
							'tipo' => 'url',
							'url' => 'cadastros.php',
							'label' => DBO_TERM_CADASTROS,
						),
					)
				)); ?>
			</div>
		</div>

		<hr class="small">

		<div class='row'>
			<div class='large-12 columns'>
			<?
				//mensagem para usar em desenv.
				//setWarning('<b>O sistema está em manutenção no momento. Consulte o desenvolvedor para se informar sobre limitações de uso.</b>');
				checkPermissions();
				getWarning();
			?>
			</div>
		</div><!-- row -->

		<div class='row'>
			<div class='large-<?= (($nro_itens_sidebar)?(9):(12)) ?> columns'>
				<ul class="large-block-grid-<?= (($nro_itens_sidebar)?(4):(5)) ?> small-block-grid-2" id='cockpit-big-buttons'>
					<? makeDboButtons('dbo_admin.php'); ?>
				</ul>
			</div>
			<?
				if($nro_itens_sidebar)
				{
					?>
					<hr class="show-for-small">
					<div class='large-3 columns'>
						<? include('sidebar.php'); ?>
					</div><!-- col -->
					<?
				}
			?>
		</div><!-- row -->
		<?php
	}
?>
<script>
	$(document).ready(function(){
		activeMainNav('cadastros');
		activeMainNav('sistema');
	}) //doc.ready
</script>
<? require('footer.php') ?>