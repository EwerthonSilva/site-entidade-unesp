<?php require_once("../../lib/includes.php"); ?>
<?php require_once(DBO_PATH.'/core/dbo-ui.php'); ?>
<?php require_once(DBO_PATH.'/core/dbo-categoria-admin.php'); ?>
<a class="close-reveal-modal">&#215;</a>
<div class="row">
	<div class="large-12 columns">
		<h3>Editar categoria</h3>
	</div>
</div>

<?php
	$cat = new categoria($_GET['categoria_id']);
	if($cat->size())
	{
		$tree = categoria::getCategoryStructure($cat->pagina_tipo);
		?>
		<form method="post" action="<?= secureUrl('dbo/core/dbo-categoria-ajax.php?action=alterar-categoria&categoria_id='.$cat->id) ?>" class="no-margin peixe-json" id="form-alterar-categoria">
			<div class="row">
				<div class="large-12 columns">
					<label for="">Imagem</label>
					<?= $cat->getFormElement('update', 'imagem') ?>
				</div>
			</div>
			<div class="row">
				<div class="large-6 columns">
					<label for="">Nome</label>
					<?= $cat->getFormElement('update', 'nome') ?>
				</div>
				<div class="large-6 columns">
					<label for="">Categoria mãe</label>
					<select name="mae" id="categoria_mae">
						<option value="">- nenhuma -</option>
						<?php
							if(sizeof($tree))
							{
								echo renderCategoryOptions($tree, null, array(
									'id' => $cat->id,
									'selected' => $cat->mae,
									'skip_self' => true,
								));
							}
						?>
					</select>
				</div>
			</div>
			<div class="large-12 columns">
				<label for="">Descrição</label>
				<?= $cat->getFormElement('update', 'descricao') ?>
			</div>
			<div class="row">
				<div class="large-12 columns text-right">
					<a class="underline font-14 peixe-json" href="<?= secureUrl('dbo/core/dbo-categoria-ajax.php?action=excluir-categoria&categoria_id='.$cat->id.'&'.CSRFVar()) ?>" data-confirm="Tem certeza que deseja excluir esta categoria?\n\nTodos as publicações relacionadas a ela serão desassociadas.">Excluir</a> &nbsp;&nbsp;&nbsp;
					<input type="submit" name="" id="" value="Alterar" class="button radius no-margin"/>
				</div>
			</div>
			<?= CSRFInput() ?>
		</form>
		<?php
	}
	else
	{
		?>
		<h2 class="text-center"><br /><br />Erro: a categoria não existe<br /><br /><br /></h2>
		<?php
	}
?>
