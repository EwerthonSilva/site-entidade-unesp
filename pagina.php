<?php require("header.php"); ?>

<div id="main-content">
	<div class="row container">
		<div class="small-12 large-8 columns">
			<h1><?=paginaTitulo()?></h1>
			<article>
				<?= paginaTexto() ?>
			</article>
		</div>
		<div class="large-4 columns" style="margin-top: 150px;">
			<ul id="second-menu" class="menu vertical">
				<?= menu::render('menu-secundario', array(
					'items_only' => true,
				)); ?>

			</ul>
		</div>
	</div>

</div>

<?php require("footer.php"); ?>
