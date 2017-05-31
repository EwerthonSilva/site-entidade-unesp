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
<style>
	.detalhes-maintenance p { font-size: 14px; }
</style>
<div class="row">
	<div class="large-12 columns">
		<?= dboBreadcrumbs(array(
			'stack' => array(
				array(
					'tipo' => 'url',
					'url' => 'cadastros.php',
					'label' => DBO_TERM_CADASTROS,
				),
				array(
					'tipo' => 'url',
					'url' => 'dbo-maintenance.php',
					'label' => 'Manutenção do sistema',
				),
			)
		)); ?>
	</div>
</div>

<hr class="small">

<div class="row">
	<div class="small-12 large-3 columns">
		<ul class="side-nav">
			<li><label for="">Opções</label></li>
			<li><a href="#">Arquivos</a></li>
			<li><a href="#">Páginas</a></li>
		</ul>
	</div>
	<div class="small-12 large-9 columns detalhes-maintenance">
		<h3>Conversão de páginas para Content Tools</h3>
		<p>Este script pega todas as páginas do sistema e transforma o conteúdo do banco do padrão <strong>TinyMCE</strong> para o padrão <strong>Content Tools</strong>.</p>
		<button type="button" class="button radius peixe-json" data-url="<?= secureUrl('dbo/maintenance/ajax-paginas.php?action=migrar-para-content-tools&'.CSRFVar()) ?>" data-confirm="Você tem certeza que deseja executar este script?" peixe-log>Converter páginas para Content Tools</button>

		<hr>
		<h3>Drop de chaves estrangeiras</h3>
		<p>Este script remove todas as <strong>constraints de chaves estrangeiras (FK)</strong> do banco de dados ativo, para que o DBO Maker possa recriá-las.</p>
		<button type="button" class="button radius peixe-json" data-url="<?= secureUrl('dbo/core/dbo-maintenance-ajax.php?action=drop-fk-constraints&'.CSRFVar()) ?>" data-confirm="Você tem certeza que deseja executar este script?" peixe-log>Remover constraints de chave estrangeira</button>
	</div>
</div>

<?php require_once('footer.php'); ?>