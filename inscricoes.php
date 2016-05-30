<?
require_once('admin/lib/includes.php');
$ev = new evento($_GET['evento']);
?>
<!doctype html>
<html dir="ltr" lang="pt-BR">
<head>
	<!-- <meta name="robots" content="noindex"> -->
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?=SYSTEM_NAME?> - Inscrições</title>
	<meta name="description" content="">
	<meta name="author" content="Serviço Técnico de Informática - FCFAR - UNESP - Araraquara">
	<!-- <meta name="viewport" content="width=device-width" /> -->

	<link rel="shortcut icon" href="images/favicon.ico"><!-- 16x16 -->
	<link rel="icon" href="images/icon.png" sizes="32x32"><!-- do other sizes if necessary -->
	<link rel="apple-touch-icon" href="images/apple-touch-icon-iphone.png" /><!-- 57x57 -->
	<link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-ipad.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-iphone4.png" />

	<!-- remeber to update when 2.0 is out -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script src="js/jquery.inputmask.js"></script>

	<!-- Peixe Laranja JSFW -->
	<script src="js/peixelaranja.js"></script>
	<script src="js/jquery.placeholder.js"></script>

	<link rel="stylesheet" media="screen" href="style.css">
	<link rel="stylesheet" media="screen" href="fonts/roboto/stylesheet.css">
	<!-- <link rel="stylesheet" media="screen and (max-width: 970px) and (min-width: 729px) " href="style-medium.css"> -->
	<!-- <link rel="stylesheet" media="screen and (max-width: 730px)" href="style-small.css"> -->
	<style media="screen">
	#forma-pagamento-input{
		display: none;
	}
	body {
		background-image: url('images/fundo-inscricao.jpg');
		background-repeat: no-repeat;
		background-attachment: fixed;
		background-size: cover;
		/*background-position:;*/
	}
	</style>
</head>
<body>


	<div id="main-wrap">

		<article style="opacity: 0.95;">
			<header id="main-header">
				<h1>Inscrições</h1>
			</header>

			<h2><?= $ev->nome ?></h2>

			<p><?= $ev->descricao ?></p>

			<form method="post" action="ajax-inscricoes.php">
				<p><strong>Selecione as atividades desejadas para inscrição:</strong></p>
				<p>
					<table>
						<thead>
							<tr>
								<th></th>
								<th style="width: 100%">Título</th>
							</tr>
						</thead>
						<tbody>
							<?
							$pal = new palestra("WHERE evento = '".$ev->id."' ORDER BY data, horario");
							if($pal->size())
							{
								$data_aux = '';
								$hora_aux = '';
								$valor_total = 0;
								do {
									$vagas = $pal->getVagasDisponiveis();
									if($pal->data != $data_aux)
									{
										?>
										<tr class="data">
											<td colspan='10'><?= date('d/m', strtotime($pal->data)) ?></td>
										</tr>
										<?
										$data_aux = $pal->data;
									}
									if($pal->horario != $hora_aux)
									{
										?>
										<tr class="horario">
											<td colspan='10'><?= $pal->horario ?></td>
										</tr>
										<?
										$hora_aux = $pal->horario;
									}
									?>
									<tr class="<?= (($vagas <= 0)?('esgotada'):('')) ?>">
										<td><input title="<?= (($vagas <= 0)?('Vagas esgotadas'):('')) ?>" type='checkbox' class="<?= $pal->valor > 0 ? 'atividade-paga' : ''?> " name='palestra[<?= $pal->id ?>]' value="<?= $pal->id ?>" <?= (($vagas <= 0)?('disabled'):('')) ?>/></td>
										<?
										list($descricao, $palestrante, $universidade) = explode("\n", $pal->titulo);
										?>
										<td title='<?= $pal->descricao ?>'>
											<span class="descricao"><?= $descricao ?><?= $pal->valor !=0 ? "- R$".number_format($pal->valor, 2, ',', '.') : "" ?></span>
											<span class="palestrante"><?= $palestrante ?></span>
											<span class="universidade"><?= $universidade ?></span>
											<br>
											<span class="palestrante"><?= $pal->descricao ?></span>
										</td>
									</tr>
									<?
									$valor_total += $pal->valor;
								}while($pal->fetch());
							}
							?>
						</tbody>
					</table>
				</p>

				<p><strong>Digite seus dados para inscrição</strong></p>

				<div class="aviso-palestrasss" style="display: none;">
					<strong>Aluno UNESP:</strong> Para que sua presença seja contabilizada você deve assitir o mínimo de duas palestras por dia.
				</div>

				<div class='row cf'>
					<div class='item item-50'>
						<label>Nome completo para o certificado</label>
						<div class='input'><input class="required" type='text' name='nome' value=""/></div>
					</div><!-- item -->
					<div class='item item-50'>
						<label>E-mail</label>
						<div class='input'><input class="required" type='text' name='email' value=""/></div>
					</div><!-- item -->
				</div><!-- row -->

				<div class="row cf">
					<div class="item item-50">
						<label>CPF (somente números)</label>
						<div class="input"><input type="text" name="cpf" id="cpf" value=""/></div>
					</div><!-- item -->
					<div id="forma-pagamento-input" class="item item-50">
						<label>Forma de pagamento</label>
						<div class="input">
							<select name="forma_pagamento">
								<option value="à vista">À vista</option>
								<option value="2x">2x</option>
								<option value="3x">3x</option>
							</select>
						</div>
					</div><!-- item -->

				</div><!-- row -->

				<div class="row cf">
					<div class="item item-100">
						<label>Endereço completo</label>
						<div class="input">
							<textarea name="endereco" id="" rows="4"></textarea>
						</div>
					</div><!-- item -->
				</div><!-- row -->

				<div class='row cf'>
					<div class='item item-20'>
						<label>Categoria</label>
						<div class='input'>
							<select name="formacao" class="required">
								<option value=''>Selecione...</option>
								<option value="Graduação">Graduação</option>
								<option value="Pós-graduação">Pós-graduação</option>
								<option value="Profissional">Profissional</option>
							</select>
						</div>
					</div><!-- item -->
					<div class='item item-30'>
						<label>Instituição</label>
						<div class='input'>
							<select name="faculdade" class="required" id="faculdade">
								<option value=''>Selecione...</option>
								<option value="UNESP">UNESP</option>
								<option value="UNIARA">UNIARA</option>
								<option value="UNIP">UNIP</option>
								<option value="USP">USP</option>
								<option value="UFSCar">UFSCar</option>
								<option value="Outras Instituições">Outras Instituições</option>
							</select>
						</div>
					</div>
					<div id="outra" class='item item-30'style="display: none;">
						<label>Outra</label>
						<div class="input">
							<input type="text" name="outra"  value="" placeholder="Digite o nome da instituição" />
						</div>
					</div><!-- item -->

					<div class="item item-25 check-graduacao" style='display: none'>
						<div class="input">
							<label>Curso</label>
							<input type="text" name="curso" id="curso" value="" placeholder="Digite o curso" class="required"/>
						</div>
					</div><!-- item -->

					<div class='item item-25 check-graduacao' style='display: none'>
						<label>Ano</label>
						<div class='input'>
							<select name="ano">
								<option value=''>Selecione...</option>
								<option value="1">1º</option>
								<option value="2">2º</option>
								<option value="3">3º</option>
								<option value="4">4º</option>
								<option value="5">5º</option>
								<option value="6">6º</option>
							</select>
						</div>
					</div><!-- item -->
					<div class='item item-25 check-graduacao' style='display: none'>
						<label>Período</label>
						<div class='input'>
							<select name="periodo">
								<option value=''>Selecione...</option>
								<option value="Integral">Integral</option>
								<option value="Noturno">Noturno</option>
							</select>
						</div>
					</div><!-- item -->

				</div><!-- row -->

				<div class='row cf'>
					<div class='item'>
						<div class='input tar'><input type='submit' name='' value="Enviar"/></div>
					</div><!-- item -->
				</div><!-- row -->

			</form>
		</article>

	</div>

	<script type="text/javascript" charset="utf-8">

	function checkAvisoUnesp() {

		var formacao = $('select[name=formacao]');
		var faculdade = $('select[name=faculdade]');

		if(formacao.val() == 'Graduação' && faculdade.val() == 'UNESP'){
			$('.aviso-palestras').fadeIn();
		}
		else {
			$('.aviso-palestras').fadeOut();
		}

	}

	function checkFormacao() {

		var formacao = $('select[name=formacao]');

		if(formacao.val() == 'Graduação'){
			$('.check-graduacao').fadeIn();
		}
		else {
			$('.check-graduacao').fadeOut();
		}
	}

	$(document).ready(function(){

		$(document).on('change', '#faculdade', function(){
			c = $(this);
			if(c.val() == 'Outras Instituições'){
				$('#outra').fadeIn('fast', function(){
					$(this).focus();
				});
			}
			else {
				$('#outra').fadeOut('fast');
			}
		});

		$('#cpf').inputmask('999.999.999-99');

		$(document).on('change', 'select[name=faculdade], select[name=formacao]', function(){
			checkAvisoUnesp();
		});

		$(document).on('change', 'select[name=formacao]', function(){
			checkFormacao();
		});

		$("input[type='checkbox']").on('change', function(){
			if($('input.atividade-paga:checked').length){
				$('#forma-pagamento-input').fadeIn();
			}else{
				$('#forma-pagamento-input').fadeOut();
			}
		});

		$('input').placeholder();

		$(document).on('submit', 'form', function(){
			peixePost(
				$(this).attr('action'),
				$(this).serialize(),
				function(data) {
					console.log(data);
					var result = $.parseJSON(data);
					if(result.message){
						setPeixeMessage(result.message);
						showPeixeMessage();
					}
					if(result.reload){
						//nao pode ser o primeiro no depois do <body>
						$.get(document.URL, function(d){
							$(result.reload).fadeHtml($(d).find(result.reload).html());
						})
					}
					if(result.html){
						$(result.html).each(function(){
							$(this.selector).fadeHtml(this.content);
						})
					}
					if(result.append){
						//implementar
					}
					if(result.addClass){
						//implementar
					}
					if(result.removeClass){
						//implementar
					}
				}
			)
			return false;
		});

	}); //doc.ready
	</script>

</body>
</html>
