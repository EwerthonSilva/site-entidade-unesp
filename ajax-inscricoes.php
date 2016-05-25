<?

/*
To use the ajax interface, just create a custom button with DBOMaker and use the class='ajax-button' on the <a> element.
The response from this file must be in JSON format.
Currently, the implemented functions are:

- message: shows a message on successful request
- html: inserts data in the parent page using jQuery '.html()' function

Example:

----------------------------------------------------------------------------------------
$json_result['message'] = "<div class='success'>This is a successful message!</div>";
$json_result['html'][0]['selector'] = '#wrapper-titulo h1';
$json_result['html'][0]['content'] = 'Total';
$json_result['html'][1]['selector'] = '#wrapper-titulo span';
$json_result['html'][1]['content'] = 'Insanity!';

echo json_encode($json_result);
----------------------------------------------------------------------------------------

The above example will return a Success message on the parent page, and replace the System name and description with the
"Total Insanity!" sentence.

*/

include('admin/lib/includes.php');

if(!sizeof($_POST['palestra']))
{
	$error = "Erro: Selecione ao menos 1 palestra";
}

if(
!strlen(trim($_POST['nome']))         ||
!strlen(trim($_POST['email']))        ||
!strlen(trim($_POST['formacao']))     ||
!strlen(trim($_POST['endereco']))     ||
!strlen(trim($_POST['cpf']))          ||
!strlen(trim($_POST['faculdade']))
)
{
	$error = "Erro: Todos os campos são obrigatórios";
}

if($_POST['formacao'] == 'Graduação')
{
	if(
	!strlen(trim($_POST['periodo'])) ||
	!strlen(trim($_POST['ano']))
	)
	{
		$error = "Erro: Todos os campos são obrigatórios";
	}
}

if($_POST['faculdade'] == 'Outras Instituições' && !strlen(trim($_POST['outra'])))
{
	$error = "Erro: Preencha o nome da instituição";
}

if(!strlen(trim($error)))
{

	$palestras = array();

	foreach($_POST['palestra'] as $key => $value)
	{
		ob_start();
		$pal = new palestra($value);
		$palestras[] = $pal->titulo.' - R$ '.number_format($pal->valor, 2, ',', '.');

		$ins = new inscricao();
		$ins->nome = $_POST['nome'];
		$ins->email = $_POST['email'];
		$ins->faculdade = $_POST['faculdade'];
		$ins->outra = $_POST['outra'];
		$ins->formacao = $_POST['formacao'];
		if($ins->formacao == 'Graduação')
		{
			$ins->ano = $_POST['ano'];
			$ins->periodo = $_POST['periodo'];
		}
		$ins->palestra = $value;
		$ins->cpf = $_POST['cpf'];
		$ins->endereco = $_POST['endereco'];
		$ins->forma_pagamento = $_POST['forma_pagamento'];
		$ins->save();
		$ob_result = ob_get_clean();
	}

	$to = $_POST['email'];
	$subject = "All Pharma Júnior - Confirmação de Inscrição";
	$from_name = "All Pharma Júnior";
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
		mail($pes->email, "Nova inscrição: ".$_POST['nome'], $message, "From: ".$from_name." <".$from_email.">\r\nMIME-Version: 1.0\r\nContent-type: text/html; charset=UTF-8\r\n", "-r ".$from_email);
	}

	$json_result['message'] = "<div class='success'>Cadastro efetuado com sucesso!</div>";
	$json_result['html'][0]['selector'] = 'form';
	if($valor_total != 0){
		$json_result['html'][0]['content'] = '<div class="obrigado">Sua inscrição foi efetuada com sucesso.<br /><br /> Você receberá o boleto em até 3 dias, com prazo para pagamento de 7 dias após o recebimento.<br /><br />Obrigado!</div>';

	}else{
		$json_result['html'][0]['content'] = '<div class="obrigado">Sua inscrição foi efetuada com sucesso.<br /><br />Obrigado!</div>';
	}

}
else
{
	$json_result['message'] = "<div class='error'>".$error."</div>";
}

echo json_encode($json_result);

?>
