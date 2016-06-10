<?php require_once("../lib/includes.php"); ?>
<?php require_once("../header.php"); ?>

<style>
	ul.padding { padding-left: 1.5em; }
</style>

<div class="row almost full">
	<div class="large-3 columns">
		<nav>
			<h6 style="margin-top: 36px;">Navegação</h6>
			<ul class="side-nav">
				<li><a href="#hooks">Hooks</a></li>
			</ul>
		</nav>
	</div>
	<div class="large-9 columns">
		<h1>Documentação do DBO</h1>

		<section id="hooks">
			<h3 id="hooks" class="color primary"><strong>Hooks</strong></h3>

			<p>Esta é a lista completa de Hooks existentes no sistema</p>

			<ul class="padding">
				<li>dbo_header_prepend</li>
				<li>dbo_header_append</li>
				<li>dbo_breadcrumbs_prepend</li>
				<li>dbo_breadcrumbs_append</li>
			</ul>

		</section>

	</div>
</div>

<?php require_once("../footer.php"); ?>