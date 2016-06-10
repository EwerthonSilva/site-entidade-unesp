<?php
require_once("lib/includes.php");
$ev = new evento($_GET['evento']);
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
	<base href="<?= SITE_URL ?>/">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $ev->nome?> - Atividades</title>

	<link href='https://fonts.googleapis.com/css?family=Lato:400,300,900' rel='stylesheet' type='text/css'>
	<!-- <link rel="stylesheet" href="css/app.css"> -->
	<link rel="stylesheet" media="screen" href="style.css">
	<link rel="stylesheet" href="css/common.css">
	<script src="bower_components/jquery/dist/jquery.js"></script>
	<script src="bower_components/foundation-sites/dist/foundation.js"></script>
	<script src="js/peixelaranja.js"></script>

	<style>
	html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, font, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td { margin: 0; padding: 0; border: 0; outline: 0; font-weight: inherit; font-style: inherit; font-size: 100%; font-family: inherit; vertical-align: baseline; }
	:focus { outline: 0; }
	ol, ul { list-style: none; }
	table { border-collapse: collapse; border-spacing: 0; }
	caption, th, td { text-align: left; font-weight: normal; }
	blockquote:before, blockquote:after,
	q:before, q:after { content: ""; }
	blockquote, q {	quotes: "" ""; }
	textarea { resize: none; }
	img { -ms-interpolation-mode: bicubic; }
	big { font-size: 125%; }
	small { font-size: 80%; }
	em { font-style: italic }
	strong { font-weight: bold; }
	.clear { clear: both; }
	svg:not(:root) { overflow: hidden; }

	/* printing */
	@media print {
		img { max-width: 100% !important; }
		p, h2, h3 { orphans: 3; widows: 3; }
		.no-print { display: none; }
	}

	/* selection */
	::-moz-selection { background: #555; color: #fff; text-shadow: none; }
	::selection { background: #555; color: #fff; text-shadow: none; }

	/* new clearfix */
	.clearfix:after,
	.cf:after { visibility: hidden; display: block; font-size: 0; content: " "; clear: both; height: 0; }
	* html .clearfix,
	* html .cf { zoom: 1; } /* IE6 */
	*:first-child+html .clearfix,
	*:first-child+html .cf { zoom: 1; } /* IE7 */

	/* boxsizing (box model alternative) */
	* { -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; *behavior: url(js/boxsizing.htc); }

	/* ---------------------- */
	/* PeixeLaranja Framework */
	/* ---------------------- */

	/* CSS3 Tooltips */
	.tooltip { position: relative; text-decoration: none; }
	.tooltip:after { content: attr(data-tooltip); position: absolute; bottom: 130%; left: 20%; background: #222; color: #FFF; width: 150px; padding: 10px 15px; font-weight: bold; font-size: 12px; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; display: none; -webkit-transition: all 0.4s ease; -moz-transition: all 0.4s ease; text-shadow: 0px 1px 0px rgba(1,1,1,.99); }
	.tooltip:before { content: ""; position: absolute; width: 0; height: 0; border-top: 20px solid #222; border-left: 20px solid transparent; border-right: 20px solid transparent; -webkit-transition: all 0.4s ease; -moz-transition   : all 0.4s ease; display: none; left: 30%; bottom: 90%; }
	.tooltip:hover:after { bottom: 100%; }
	.tooltip:hover:before { bottom: 70%; }
	.tooltip:hover:after, .tooltip:hover:before { display: block; }

	/* Helpers */

	.helper { font-size: 11px !important; color: #fff !important; padding: 10px 15px !important; line-height: 15px !important; background: #333; border-radius: 5px; position: relative; display: inline-block; cursor: help; }

	.arrow-left:after { right: 100%; border: solid transparent; content: " "; height: 0; width: 0; position: absolute; pointer-events: none; }
	.arrow-left:after {	border-color: rgba(51, 51, 51, 0); border-right-color: #333; border-width: 6px; top: 50%; margin-top: -6px; }

	.arrow-bottom:after { top: 100%; border: solid transparent; content: " "; height: 0; width: 0; position: absolute; pointer-events: none; }
	.arrow-bottom:after { border-color: rgba(51, 51, 51, 0); border-top-color: #333; border-width: 6px;	left: 50%; margin-left: -6px; }

	.arrow-right:after { left: 100%; border: solid transparent; content: " "; height: 0; width: 0; position: absolute; pointer-events: none; }
	.arrow-right:after { border-color: rgba(51, 51, 51, 0); border-left-color: #333; border-width: 6px; top: 50%; margin-top: -6px; }

	.arrow-top:after { bottom: 100%; border: solid transparent; content: " "; height: 0; width: 0; position: absolute; pointer-events: none; }
	.arrow-top:after { border-color: rgba(51, 51, 51, 0); border-bottom-color: #333; border-width: 6px; left: 50%; margin-left: -6px; }

	/* grid blocks */
	.s-box { width: 300px; margin: 0 10px; float: left; } /* single */
	.d-box { width: 620px; margin: 0 10px; float: left; } /* double */
	.t-box { width: 940px; margin: 0 10px; float: left; } /* triple */

	.s-column { width: 320px; float: left; }
	.d-column { width: 640px; float: left; }
	.t-column { width: 960px; float: left; }

	/* some support classes */
	.tal { text-align: left; }
	.tac { text-align: center; }
	.tar { text-align: right; }
	.left { float: left; }
	.right { float: right; }

	/* messages */
	.wrapper-message { position: fixed; top: 0px; left: 0px; width: 100%; line-height: 59px; overflow: hidden; font-size: 25px; z-index: 10000; height: 0px; opacity: 0.95; }
	.wrapper-message .success { background: #333; color: #FFF; font-size: 19px; font-weight: bold; padding: 0 20px; text-shadow: 0px -1px 0px #1A360B; border-bottom: 1px solid #1A360B; }
	.wrapper-message .error { background: #CC0000; color: #FFF; font-size: 19px; font-weight: bold; padding: 0 20px; text-shadow: 0px -1px 0px #870000; border-bottom: 1px solid #870000; }

	/* ajax helpers */
	.peixe-screen-freezer { position: fixed; z-index: 10000; width: 100%; height: 100%; background: transparent; display: none; }
	.peixe-ajax-loader { position: fixed; z-index: 10001; background: #333; padding: 5px 10px; color: #FFF; border-radius: 3px; top: 10px; left: 10px; box-shadow: 0px 1px 2px rgba(1,1,1,.2); text-shadow: 0px -1px 0px rgba(1,1,1,.99); display: none; }

	/* General CSS */
	html { /* overflow-y: scroll; */ font-size: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
	body { color: #333; font: 13px Arial, "Helvetica Neue", Helvetica, sans-serif; font-weight: 300; line-height: 1.625; }
	a { color: #999; -webkit-transition: all .15s ease; -moz-transition: all .15s ease; transition: all .15s ease; }
	a:hover { color: #333; }

	div h5{font-size: 25px;}

	.nowrap{white-space: nowrap;}
	/* Your Section Name Here! */

	/*table { width: 80%; margin: 5%}*/
	table
	table td,
	table th { border-collapse: collapse; background: #eee; }
	table td,
	table th { padding: 10px; }
	table th { text-align: left; font-weight: bold; }
	table td { border-bottom: 1px dotted #ccc; }
	table tbody tr:hover td { background: #fafafa; }
	table tbody tr.active td { background: #333; color: #fff; }
	table tbody tr.detail td { border-bottom: 2px solid #333; border-top: 2px solid #000; }
	.text-center{
		display: flex;
		justify-content : center;
	}
	</style>
</head>
<tbody>
	<?

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
	<div class="row">
		<div class=" large-12 columns">
			<h5 class="text-center">Gerenciador de Inscrição</h5>
		</div>
		<div class=" text-center large-12 columns">
			<table class="lista-inscrito">
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
						<th>Endereço</th>
						<th>Forma Pagto</th>
					</tr></div>
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
								<td><?= $lin->cpf ?></td>
								<td><?= $lin->faculdade == 'Outras Instituições' ? 'Outra: '.$lin->outra : $lin->faculdade ?></td>
								<td><?= $lin->curso?></td>
								<td><?= $lin->ano ?></td>
								<td><?= $lin->periodo ?></td>
								<td><?= $lin->formacao ?></td>
								<td><?= nl2br($lin->endereco) ?></td>
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
											<button style="margin-top: 5px;"type='button' class="peixe-json" data-confirm='Tem certeza que deseja excluir esta inscrição?' data-url='ajax-excluir-inscricao.php?palestra=<?= $lin->palestra?>&email=<?= urlencode($lin->email) ?>' peixe-log>
												X
											</button>
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
		</div>
	</div>
	<div class="row">
		<div class="large-12 columns">
			<h4>
				Total de Inscritos: <?= $count ?>
			</h4>
		</div>
	</div>
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
</tbody>
</html>
