<?php
	require_once('lib/includes.php');

	function autoAdminDboContentBlock($params = array())
	{
		global $_system;
		extract($params);
		ob_start();
		?>
		<div class="wrapper-dbo-auto-admin" id="modulo-dbo_content_block" style="<?= (($_GET['hide_admin_header'])?('display: none;'):('')) ?>">
			<div id="auto-admin-header">
				<div class="row">
					<div class="large-9 columns">
						<div class="breadcrumb" style="">
							<ul class="no-margin">
								<li><a href="cadastros.php"><?= DBO_TERM_CADASTROS ?></a></li>
								<li><a href="dbo_admin.php?dbo_mod=dbo_content_block">Blocos de conteúdo</a></li>
							</ul>																			
						</div>
					</div>
				</div>
				<hr>
			</div>
			<?php
				if(sizeof($_system['content_block']['global']))
				{
					?>
					<form method="post" action="<?= secureUrl(DBO_URL.'/core/dbo-content-block-ajax.php?action=update-blocks&'.CSRFVar()) ?>" class="no-margin peixe-json" id="form-content-blocks" peixe-log>
						<div class="row">
							<?php
								foreach($_system['content_block']['global'] as $name => $data)
								{
									$data['name'] = $name;
									?>
									<div class="large-<?= $data['grid'] ?> end columns">
										<?= dbo_content_block::renderField($data) ?>
									</div>
									<?php
								}
							?>
						</div>
						<div class="row">
							<div class="large-12 columns text-right">
								<button type="submit" class="button radius" accesskey="s">Atualizar blocos de conteúdo</button>
							</div>
						</div>
					</form>
					<?php
				}
				else
				{
					?>
					<h3 class="text-center"><br /><br /><br />&#8212; Não existem blocos de conteúdo cadastrados &#8212;</h3>
					<?php
				}
			?>
		</div>
		<script>
			$(document).ready(function(){
				dboInit();
			}) //doc.ready
		</script>
		<?php
		return ob_get_clean();
	}

?>