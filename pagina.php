<?php require("header.php"); ?>

<div id="main-content">
	<div class="row container">
		<div class="small-12 large-10 large-offset-1 columns">
			<h1><?=paginaTitulo()?></h1>
			<article>
				<?= paginaTexto() ?>
			</article>
		</div>
		<div class="large-4 columns" style="margin-top: 150px;">
			<ul id="second-menu" class="menu vertical">
				<?= menu::render('menu-secundario', array(
					'items_only' => true,
					'$foundation_6' => true,
				)); ?>

			</ul>
		</div>
	</div>

</div>

<?php require("footer.php"); ?>
