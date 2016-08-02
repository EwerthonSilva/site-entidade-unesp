<?php
require_once("admin/lib/includes.php");
$ev = new evento($_GET['evento']);
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
	<base href="<?= SITE_URL ?>/">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= SYSTEM_NAME?> - Inscrições</title>

	<link href='https://fonts.googleapis.com/css?family=Lato:400,300,900' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/app.css">
	<script src="bower_components/jquery/dist/jquery.js"></script>
	<script src="bower_components/foundation-sites/dist/foundation.js"></script>
	<script src="admin/js/peixelaranja.js"></script>
	<script src="js/mascaras.js"></script>

	<?= dboImportJs(array(
		'hotkeys',
		'maskedinput',
	)) ?>

	<style media="screen">
	body {
		background-repeat: no-repeat;
		background-attachment: fixed;
		background-size: 100% 100%;
		width: 80%;
		margin-left: 10%;

	}

	.row{

	}
	</style>
</head>
<?php
if($ev->background_image)
{
	$bg = $ev->_background_image->url();
}
elseif(siteConfig()->background_image)
{
	$bg = siteConfig()->_background_image->url();
}
?>
<body style="<?= $bg ? 'background-image: url('.$bg.')' : '' ?>; ">
	<div class="row">
		<div class="container large-12 columns" style="padding: 15px 40px;">
			<header>
				<div class="row">
					<div class="text-center large-12 columns">
						<h2><?= $ev->nome ?></h2>
					</div>
				</div>
				<div class="row">
					<div class="text-center large-12 columns">
						<h3>Inscrições</h3>
						<hr>
					</div>
				</div>
			</header>
			<?php
			$pal= new palestra("WHERE evento = '".$ev->id."' ORDER BY data, horario");
			if($pal->size())
			{
				?>
			<div class="row">
				<div class="large-12 columns">
					<p style="text-align: justify"><?= $ev->descricao ?></p>
					<p>
						Selecione as atividades desejadas para inscrição:
					</p>
				</div>
			</div>
			<div class="row">
				<div class="large-12 columns">
					<form id="form-inscricao" class="no-margin peixe-json" action="<?=SecureUrl('ajax-inscricoes.php?action=insert-inscricao&evento='.$ev->id)?>" method="post" peixe-log>
						<div class="row">
							<div class="large-12 columns">
								<?
									do {

										$array_atividades[$pal->data][$pal->horario][$pal->id] = array(
											'titulo' => $pal->titulo,
											'valor' => $pal->valor,
											'descricao' => $pal->descricao,
											'vagas' => $pal->getVagasDisponiveis()
										);

									}while ($pal->fetch());

								foreach($array_atividades as $data => $horarios)
								{
									echo '<h4>'.date('d/m', strtotime($data)).'</h4>';
									foreach($horarios as $horario => $atividades)
									{
										echo '<h5>'.$horario.'</h5>';
										?>
										<table class="lista-atividades">
											<tbody>
												<?php
												foreach($atividades as $id_atividade => $dados_atividade)
												{
													list($descricao, $palestrante, $universidade) = explode("\n", $dados_atividade[titulo]);
													?>
													<tr>
														<td class="checkbox"><input title="<?= (($dados_atividade[vagas] <= 0)?('Vagas esgotadas'):('')) ?>" type='checkbox' class="no-margin <?= $dados_atividade[valor] > 0 ? 'atividade-paga' : ''?> " name='palestra[<?= $id_atividade ?>]' value="<?= $id_atividade ?>"
															<?= (($dados_atividade[vagas] <= 0)?('disabled'):('')) ?>/></td>
															<td>
																<blockquote class="no-margin">
																	<span class="titulo"><?= $descricao?><?= $dados_atividade[valor] !=0 ? "- R$".number_format($dados_atividade[valor], 2, ',', '.') : "" ?></span>
																	<cite>
																		<?= $palestrante ?> - <?= $universidade ?>
																	</cite>
																	<span class="descricao"><?=$dados_atividade[descricao]?></span>
																</blockquote>
															</td>
														</tr>
														<?php
													}
													?>
												</tbody>
											</table>
											<?php
										}
									}
									?>
									<h3>Preencha os campos abaixo</h3>
									<div class="row">
										<div class="large-8 columns">
											<label for="nome">Nome Completo para o certificado</label>
											<input type="text" name="nome" required="">
										</div>
										<div class="large-4 end columns">
											<label for="cpf">CPF</label>
											<input class="required cpf" type="text" name="cpf" maxlength="14" onKeyPress="MascaraCPF(this);" onblur="ValidarCPF(this);">
										</div>
									</div>
									<div class="row">
										<div class="large-6 columns">
											<label for="email">E-mail</label>
											<input type="email" name="email" required="">
										</div>
										<!-- <div id="forma-pagamento-input" class="large-4 columns">
											<label for="forma_pagamento">Forma de pagamento</label>
											<select name="forma_pagamento">
												<option value="à vista">À vista</option>
												<option value="2x">2x</option>
												<option value="3x">3x</option>
											</select>
										</div> -->
									</div>
									<div class="row">
										<div class="large-6 columns">
											<label for="logradouro">Logradouro</label>
											<input type="text" name="logradouro" required="">
										</div>
										<div class="large-2 columns">
											<label for="numero">Nº</label>
											<input maxlength="5" type="text" name="numero" onkeypress="soNumeros(this);" required="">
										</div>
										<div class="large-4 columns">
											<label for="bairro">Bairro</label>
											<input type="text" name="bairro" required="">
										</div>
									</div>
									<div class="row">
										<div class="large-6 columns">
											<label for="cidade">Cidade</label>
											<input type="text" name="cidade" required="">
										</div>
										<div class="large-2 end columns">
											<label for="estado">Estado</label>
											<select class="required" name="uf" title="Este campo é obrigatório" required >
												<option value="">--</option>
												<option value="AC">AC</option>
												<option value="AL">AL</option>
												<option value="AM">AM</option>
												<option value="AP">AP</option>
												<option value="BA">BA</option>
												<option value="CE">CE</option>
												<option value="DF">DF</option>
												<option value="ES">ES</option>
												<option value="GO">GO</option>
												<option value="MA">MA</option>
												<option value="MG">MG</option>
												<option value="MS">MS</option>
												<option value="MT">MT</option>
												<option value="PA">PA</option>
												<option value="PB">PB</option>
												<option value="PE">PE</option>
												<option value="PI">PI</option>
												<option value="PR">PR</option>
												<option value="RJ">RJ</option>
												<option value="RN">RN</option>
												<option value="RO">RO</option>
												<option value="RR">RR</option>
												<option value="RS">RS</option>
												<option value="SC">SC</option>
												<option value="SE">SE</option>
												<option value="SP">SP</option>
												<option value="TO">TO</option>
											</select>
										</div>
									</div>
									<div class="row">
										<div class="large-4 columns">
											<label for="categoria">Categoria</label>
											<select name="formacao" class="required">
												<option value=''>Selecione...</option>
												<option value="Graduação">Graduação</option>
												<option value="Pós-graduação">Pós-graduação</option>
												<option value="Profissional">Profissional</option>
											</select>
										</div>
										<div class="large-4 columns">
											<label for="faculdade">Instituição</label>
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
										<div id="outra-instituicao" class="large-4 columns outra-instituicao">
											<label for="outra">Outra Instituição</label>
											<input type="text" name="outra">
										</div>
									</div>
									<div id="input-graduacao" class="row input-graduacao">
										<div class="large-4 columns">
											<label for="curso">Curso</label>
											<input type="text" name="curso">
										</div>
										<div class="large-4 columns">
											<label for="ano">Ano</label>
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
										<div class="large-4 columns">
											<label for="periodo">Periodo</label>
											<select name="periodo">
												<option value=''>Selecione...</option>
												<option value="Integral">Integral</option>
												<option value="Noturno">Noturno</option>
											</select>
										</div>
									</div>
									<div class="row">
										<div class="large-12 columns text-right">
											<button class="button radius" type="submit" name="button">Enviar</button>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
				<?
			}else{
				?>

				<div class="row">
				  <div class="large-12 columns text-center">
				  		<h4>
								O Evento selecionado não existe ou foi removido.
							</h4>
							<p>
								Favor contactar os Adminsitradores.

							</p>
				  </div>
				</div>
				<?
			}
			?>
			</div>
		</div>
		<script src="js/app.js"></script>
		<script src="js/jquery.inputmask.js"></script>
	</body>
	</html>
