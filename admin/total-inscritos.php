<?
require_once('lib/includes.php');
require_once('auth.php');

require_once('header.php');

$ev = new Evento($_GET['evento']);

$palestra = new palestra();
$palestra->evento = $_GET['evento'];
$palestra->loadAll();

do
{
	$ids[] = $palestra->id;
}while($palestra->fetch());

$sql = "SELECT id, palestra, nome, email, faculdade, outra, curso, ano, periodo, formacao, endereco, forma_pagamento, cpf FROM inscricao WHERE palestra IN (".implode(",", $ids).") ORDER BY nome";

$res = dboQuery($sql);

$nome = '';

?>

<h3 class="text-center"><span class="color primary">Gerenciamento de inscrições:</span> <strong><?= $ev->nome ?></strong></h3>

<table class="list" align="center">
	<thead>
		<tr>
			<th>Nome</th>
			<th>E-mail</th>
			<th>CPF</th>
			<th>Faculdade</th>
			<th>Curso</th>
			<th>Ano</th>
			<th>Período</th>
			<th>Formação</th>
			<th>Forma Pagto</th>
		</tr>
	</thead>
	<tbody>
	<?
		$count = 0;
		while($lin = dboFetchObject($res))
		{
			if($nome != $lin->nome)
			{
				$count++;
				$palestras = array();
				$nome = $lin->nome;
				$email = $lin->email;
				?>
				<tr class="handler">
					<td><?= $lin->nome ?></td>
					<td><?= $lin->email ?></td>
					<td class="nowrap"><?= $lin->cpf ?></td>
					<td><?= $lin->faculdade == 'Outras Instituições' ? 'Outra: '.$lin->outra : $lin->faculdade ?></td>
					<td><?= $lin->curso?></td>
					<td><?= $lin->ano ?></td>
					<td><?= $lin->periodo ?></td>
					<td><?= $lin->formacao ?></td>
					<td><?= $lin->forma_pagamento ?></td>
				</tr>
				<tr style="display: none;" class="detail" id="">
					<td colspan='20'>
						<?
						$sql = "SELECT group_concat(palestra) as ids FROM `inscricao` WHERE cpf = '".$lin->cpf."'";
						$res2 = dboQuery($sql);

						$palestraIds = dboFetchObject($res2);

						$sql = "SELECT titulo from palestra where id in (".$palestraIds->ids.")";
						$res2 = dboQuery($sql);

						while($pale = dboFetchObject($res2))
						{
						?><div class="" id="inscricao-<?=$lin->id?>">
							<button style="margin-top: 5px;" type='button' class="peixe-json button small radius no-margin" data-confirm='Tem certeza que deseja excluir esta inscrição?' data-url='ajax-excluir-inscricao.php?palestra=<?= $lin->palestra?>&email=<?= urlencode($lin->email) ?>' peixe-log><i class="fa fa-times"></i></button>
							<?= $pale->titulo ?>
						</div>
						<?
						}
					}

				}
				?>
					</td>
			</tr>
	</tbody>
</table>
<h3 class="text-center"><strong>Total de Inscritos: <?= $count ?></strong></h3>
<script type="text/javascript" charset="utf-8">
$(document).ready(function(){
	$('.handler').click(function(){
		if(!$(this).hasClass('active')){
			$('tr.active').removeClass('active');
			$(this).addClass('active');
			$('tr.detail').hide();
			$(this).next('tr').fadeIn();
			console.log('clicou');
		}else {
			$('tr.detail').hide();
			$('tr.active').removeClass('active');
		}
	})
}) //doc.ready
</script>

<?php require_once("footer.php"); ?>
