<?php 
	require('header.php'); 
	require_once(DBO_PATH.'/core/dbo-ui.php');
?>
<style>
	html, body { height: 100%; }
</style>
<?php
	if(loggedUser())
	{
		?>
		<div class="row">
			<div class="small-12 large-12 columns">
				<h1 class="color primary inline-block">Meu perfil <span class="color light">&nbsp;|&nbsp;</span></h1>
				<div class="inline-block">
					<label for="" style="margin-bottom: -11px;">usuário de acesso</label>
					<h3><?= loggedUserObj()->user ?></h3>
				</div>
			</div>
		</div>
		<form method="post" action="<?= secureUrl(ADMIN_URL.'/ajax-dbo-meu-perfil.php?action=atualizar-perfil&pessoa_id='.loggedUser().'&'.CSRFVar()); ?>" class="no-margin peixe-json" id="form-meu-perfil" peixe-log>
			<?= loggedUserObj()->getUpdateForm(array(
				'fields_only' => true,
				'field_whitelist' => $_system['meu_perfil']['campos'],
			)); ?>
			<?php
				if(defined('FACEBOOK_AUTH_CONFIG_JSON') || defined('GOOGLE_AUTH_CONFIG_JSON'))
				{
					?>
					<div class="row">
						<div class="small-12 large-12 columns">
							<h5 class="section subheader text-center">
								<span>Vincule seu perfil às redes sociais para um login mais rápido e seguro!</span>
							</h5>
							<hr style="margin-bottom: 30px;">
						</div>
					</div>
					<div class="row">
						<div class="small-12 large-6 columns text-center">
							<?php
								if(defined('GOOGLE_AUTH_CONFIG_JSON'))
								{
									if(!strlen(trim(loggedUserObj()->google_id)))
									{
										?>
										<div class="margin-bottom">
											<div class="g-signin2" data-onsuccess="googleSignIn"></div>
										</div>
										<?php
									}
									else
									{
										?>
										<div class="panel text-center">
											<p><i class="fa fa-check color ok"></i> Seu perfil já está vinculado ao <strong>Google</strong></p>
										</div>
										<?php
									}
								}
							?>
						</div>
						<div class="small-12 large-6 columns text-center">
							<?php
								if(defined('FACEBOOK_AUTH_CONFIG_JSON'))
								{
									if(!strlen(trim(loggedUserObj()->facebook_id)))
									{
										?>
										<div class="margin-bottom">
											<div class="facebook-signin pointer abcRioButton" onClick="facebookSignin()">
												<div class="abcRioButtonIcon"><i class="fa fa-facebook"></i></div>
												<span class="abcRioButtonContents">Logar com Facebook</span>
											</div>
										</div>
										<?php
									}
									else
									{
										?>
										<div class="panel text-center">
											<p><i class="fa fa-check color ok"></i> Seu perfil já está vinculado ao <strong>Facebook</strong></p>
										</div>
										<?php
									}
								}
							?>
						</div>
					</div>
					<?php
				}
			?>
			<div class="row">
				<div class="small-12 large-12 columns text-right">
					<button type="submit" class="button radius peixe-save"><i class="fa fa-check"></i> Atualizar meu perfil</button>
				</div>
			</div>
		</form>
		<?= dboGetRegisteredJS() ?>
		<script>
			$(document).ready(function(){
				dboInit();
			}) //doc.ready
		</script>
		<?php
	}
	else
	{
		?>
		<h3 class="absolute-center">&#8212; Você precisa estar logado &#8212;</h3>
		<?php
	}
?>

<?php require('footer.php'); ?>