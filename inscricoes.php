<?php
require_once('admin/lib/includes.php');
$ev = new evento($_GET['evento']);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title><?=SYSTEM_NAME?> - Inscrições</title>
	<link rel="stylesheet" href="css/app.css">
</head>

<body>

	<header>
		<div class="row">
			<div class="text-center large-12 columns">
				<h1>Inscrições</h1>
			</div>
		</div>
	</header>

	<div class="row">
		<div class="text-center large-12 columns">
			<h2 style="white-space:nowrap"><?= $ev->nome ?></h2>
		</div>
	</div>

	<div class="row">
		<div class="large-12 columns">
			<p style="text-align: justify"><?= $ev->descricao ?></p>
			<p>
				Selecione as atividades desejadas para inscrição:
			</p>
		</div>
	</div>

	<form class="no-margin peixe-json" action="ajax-incricao-action.php" method="post">
		<div class="row">
			<div class="large-12 columns">

				<?php
				$pal= new palestra("WHERE evento = '".$ev->id."' ORDER BY data, horario");
				if($pal->size())
				{
					do {
						$array_atividades[$pal->data][$pal->horario][$pal->id] = array(
							'titulo' => $pal->titulo,
							'valor' => $pal->valor,
							'descricao' => $pal->descricao,
							'vagas' => $pal->getVagasDisponiveis()
						);
					}while ($pal->fetch());
				}
				foreach($array_atividades as $data => $horarios)
				{
					echo '<h4>'.date('d/m', strtotime($pal->data)).'</h4>';
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
										<td class="checkbox"><input title="<?= (($dados_atividade[vagas] <= 0)?('Vagas esgotadas'):('')) ?>" type='checkbox' class="<?= $dados_atividade[valor] > 0 ? 'atividade-paga' : ''?> " name='palestra[<?= $id_atividade ?>]' value="<?= $id_atividade ?>" <?= (($dados_atividade[vagas] <= 0)?('disabled'):('')) ?>/></td>
										<td>
											<span class="titulo"><?= $descricao?><?= $dados_atividade[valor] !=0 ? "- R$".number_format($dados_atividade[valor], 2, ',', '.') : "" ?></span>
											<span class="palestrante"><?= $palestrante ?></span>
											<span class="universidade"><?= $universidade ?></span>
											<span class="descricao"><?=$dados_atividade[descricao]?></span>
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
			</div>
			<div class="row">
				<div class="large-12 columns">
					<div class="row">
						<div class="large-8 columns">
							<label for="nome">Nome Completo para o certificado</label>
							<input type="text" name="nome" required="">
						</div>
						<div class="large-4 end columns">
							<label for="cpf">CPF</label>
							<input class="required cpf" type="text" name="cpf" >
						</div>
					</div>
					<div class="row">
						<div class="large-6 columns">
							<label for="email">E-mail</label>
							<input type="email" name="email" required="">
						</div>
					</div>
					<div class="row">
						<div class="large-6 columns">
							<label for="email">Logradouro</label>
							<input type="email" name="email" required="">
						</div>
						<div class="large-2 columns">
							<label for="email">Nº</label>
							<input type="email" name="email" required="">
						</div>
						<div class="large-4 columns">
							<label for="email">Bairro</label>
							<input type="email" name="email" required="">
						</div>
					</div>
					<div class="row">
						<div class="large-6 columns">
							<label for="email">Cidade</label>
							<input type="email" name="email" required="">
						</div>
						<div class="large-4 columns">
							<label for="email">Estado</label>
							<input type="email" name="email" required="">
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
							<button class="button radius" type="button" name="button">Enviar</button>
						</div>
					</div>

				</div>

			</div>
		</div>
	</form>
	<script src="bower_components/jquery/dist/jquery.js"></script>
	<script src="bower_components/what-input/what-input.js"></script>
	<script src="bower_components/foundation-sites/dist/foundation.js"></script>
	<script src="js/app.js"></script>
	<script src="js/jquery.inputmask.js"></script>

</body>
</html>
