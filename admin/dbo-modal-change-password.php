<? require_once("lib/includes.php"); ?>
<a class="close-reveal-modal">&#215;</a>
<div class="row">
	<div class="large-12 columns">
		<form method="post" action="<?= secureUrl(DBO_URL.'/../ajax-dbo-change-password.php?pessoa_id='.loggedUser().'&action=change-password&'.CSRFVar()) ?>" class="no-margin peixe-json" id="form-dbo-change-password">
			<h3>Alterar senha</h3>
			<div class="row">
				<div class="large-12 columns">
					<label for="">Senha atual</label>
					<input type="password" name="pass_atual" id="" value=""/>
				</div>
				<div class="large-12 columns">
					<label for="">Nova senha</label>
					<input type="password" name="pass" id="" value=""/>
				</div>
				<div class="large-12 columns">
					<label for="">Confirme a nova senha</label>
					<input type="password" name="pass_check" id="" value=""/>
				</div>
				<div class="large-12 columns text-right">
					<input type="submit" name="" id="" value="Alterar senha" class="no-margin button radius"/>
				</div>
			</div>
		</form>
	</div>
</div>