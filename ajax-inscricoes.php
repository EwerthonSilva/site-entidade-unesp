<?
require_once('admin/lib/includes.php');
require_once('admin/dbo/core/dbo-ui.php');

$json_result = array();

if(!secureUrl())
{
	$json_result['message'] = '<div class="error">Tentativa de acesso inválida</div>';
}
else{
	if($_GET['action']== 'insert-inscricao'){

		if(!sizeof($_POST['palestra'])){
			$error = "Erro: Selecione ao menos 1 palestra";
		}

		if(
		!strlen(trim($_POST['nome']))         ||
		!strlen(trim($_POST['email']))        ||
		!strlen(trim($_POST['formacao']))     ||
		!strlen(trim($_POST['logradouro']))		||
		!strlen(trim($_POST['numero']))				||
		!strlen(trim($_POST['bairro']))				||
		!strlen(trim($_POST['cidade']))				||
		!strlen(trim($_POST['estado']))				||
		!strlen(trim($_POST['cpf']))          ||
		!strlen(trim($_POST['faculdade']))
		){
			$error = "Erro: Todos os campos são obrigatórios";
		}

		if($_POST['formacao'] == 'Graduação'){
			if(
			!strlen(trim($_POST['periodo'])) ||
			!strlen(trim($_POST['ano']))
			){
				$error = "Erro: Todos os campos são obrigatórios";
			}
		}

		if($_POST['faculdade'] == 'Outras Instituições' && !strlen(trim($_POST['outra']))){
			$error = "Erro: Preencha o nome da instituição";
		}

		if(!strlen(trim($error))){

			$palestras = array();
			$valor_total = 0;

			foreach($_POST['palestra'] as $key => $value){
				ob_start();
				$pal = new palestra($value);
				$palestras[] = $pal->titulo.' - R$ '.number_format($pal->valor, 2, ',', '.');
				$valor_total += $pal->valor;

				$ins = new inscricao();
				$ins->nome = $_POST['nome'];
				$ins->email = $_POST['email'];
				$ins->faculdade = $_POST['faculdade'];
				$ins->outra = $_POST['outra'];
				$ins->formacao = $_POST['formacao'];
				if($ins->formacao == 'Graduação'){
					$ins->curso = $_POST['curso'];
					$ins->ano = $_POST['ano'];
					$ins->periodo = $_POST['periodo'];
				}
				$ins->palestra = $value;
				$ins->cpf = $_POST['cpf'];
				$ins->endereco = $_POST['logradouro'].', '.$_POST['numero'].', '.$_POST['bairro']."\n".$_POST['cidade'].'/'.$_POST['estado'];
				$ins->forma_pagamento = $_POST['forma_pagamento'];
				$ins->save();
				$ob_result = ob_get_clean();
			}

			$json_result['message'] = "<div class='success'>Cadastro efetuado com sucesso!</div>";
			$json_result['message']='<div class="success">Seu voto foi computado.</div>';
			ob_start();
			?>
			<div class="row">
				<div class="obrigado text-center large-12 columns">
					<br /><br />
					<h1>Sucesso!</h1>
					<p>Sua inscrição foi efetuada com sucesso.</p>
				</div>
			</div>
			<?
			if($valor_total != 0){
				?>
				<div class="obrigado text-center large-12 columns">
					Você receberá o boleto em até 3 dias, com prazo para pagamento de 7 dias após o recebimento.
					<br /><br />
					Obrigado!
				</div>
				<?
			}
			$to = $_POST['email'];
			$subject =  SYSTEM_NAME."- Confirmação de Inscrição";
			$from_name = SYSTEM_NAME;
			$from_email = "no-reply@fcfar.unesp.br";

			$message = "

			<p>Olá, ".$_POST['nome'].".</p>

			<p>Sua inscrição foi efetivada para as seguintes atividades:</p>

			<ul>
			<li>".implode("</li><li>", $palestras)."</li>
			</ul>";

			if($valor_total !=0){

				$message .= "<p>Você receberá o botelo em até 3 dias, com prazo para pagamento de 7 dias após o recebimento.</p>" ;

			}

			$message .= "
			<p>Em caso de dúvidas, entre em contato pelo e-mail ".EMAIL_CONTATO."  </p>

			<p><center><small>Esta mensagem foi gerada automaticamente, favor n&atilde;o responder.</small></center></p>
			";

			mail($to, $subject, $message, "From: ".$from_name." <".$from_email.">\r\nMIME-Version: 1.0\r\nContent-type: text/html; charset=UTF-8\r\n", "-r ".$from_email);
			$editores = getUsersPerfil('Editor');


			foreach((array)$editores as $pessoa_id)
			{
				$pes = new pessoa($pessoa_id);

				$message = "
					<p>Olá, ".$pes->nome.".</p>

					<p>".$_POST['nome']." se inscreveu nas seguintes atividades:</p>

					<ul>
					<li>".implode("</li><li>", $palestras)."</li>
					</ul>

					<p>Para gerenciar as inscrições, acesse: <a href='".SITE_URL."/admin'>Área Administrativa</a>
				";
				mail($pes->email, "Nova inscrição: ".$_POST['nome'], $message, "From: ".$from_name." <".$from_email.">\r\nMIME-Version: 1.0\r\nContent-type: text/html; charset=UTF-8\r\n", "-r ".$from_email);
			}

			$json_result['html']['#form-inscricao'] = ob_get_clean();
		}else{
			$json_result['message'] = "<div class='error'>".$error."</div>";
		}
	}
}

echo json_encode($json_result);
?>
