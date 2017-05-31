<?
require_once("admin/lib/includes.php");

$json_result = array();


// CSRFCheckJson();

if(!secureUrl())
{
	$json_result['message'] = '<div class="error">Tentativa de acesso inválida</div>';
}
else{

	if($_GET['action'] == 'login'){

		$pesq = new pesquisa($_GET['pesq']);
		$resp = new resposta();
		$resp->pesquisa = $pesq->id;
		$resp->loadAll();

		$flag = 0;

		$email = "";
		$email = $_POST['email'];
		$cpf = $_POST['cpf'];
		$ip = "";

		if($pesq->size())
		{

			switch ($pesq->temAutenticacao()) {
				case 1:
				if(!dboValidaEmail($email)){
					$error .= " email";
				}
				break;

				case 2:
				if(!dboValidaEmail($email)){
					$error .= " email";
				}
				if(!dboValidaCPF($cpf)){
					$error .= " cpf";
				}
				break;

				case 3:
				if(!dboValidaEmail($email)){
					$error .= " email";
				}
				if(!dboValidaCPF($cpf)){
					$error .= " cpf";
				}
				break;

				case 4:
				$ip = $_SERVER["REMOTE_ADDR"];
				if(!dboValidaEmail($email)){
					$error .= " email";
				}
				if(!dboValidaCPF($cpf)){
					$error .= " cpf";
				}
				do{
					if($ip == $resp->ip_pesquisado){
						$error = "ip";
					}
				}while($resp->fetch());

				break;

				default:
				//SE FOR ABERTO
				break;
			}
			if($error == ""){
				do{
					$ins = new inscricao();
					$ins->cpf = $cpf;
					$ins->loadAll();
					if($ins->size()){
						if($email != $ins->email){
							$flag = 1;
						}
						do{
							if($cpf == $resp->cpf_pesquisado && $resp->_inscricao->_palestra->evento == $pesq->evento){
								$flag = 2;
							}
						}while($resp->fetch());
					}else{
						$flag = 3;
					}
				}while($resp->fetch());
				if(!$flag){
					$ins = new inscricao();

					$ins->cpf = $cpf;
					$ins->loadAll();

					if(($ins->size()) && ($ins->email != $_POST['email'])){
						$json_result['eval'] = 'alert("Favor utilizar o seu email utlizado para cadastrar no Evento.")';
					}else{
						$json_result['html']['#conteudo'] = $pesq->renderQuestoes($cpf, $email, $ip);
					}
				}else{
					if($flag == 1){
						$json_result['eval'] = 'alert("O Email informado é diferente da sua incrição!")';
					}elseif($flag == 2){
						$json_result['eval'] = 'alert("Você já respondeu esta pesquisa!")';
					}else{
						$json_result['eval'] = 'alert("Inscrição não localizada /n Favor utilizar o seu email e CPF utlizado no cadastro do Evento.")';
					}

				}

			}else{
				if($error==" ip"){
					$json_result['eval'] = 'alert("Essa pesquisa já foi respondida por esse IP")';
				}else{
					if($error==" cpf"){
						$json_result['eval'] = 'alert("CPF inválido!. Favor verificar e corrigir!")';

					}else{
						$json_result['eval'] = 'alert("Email inválido!. Favor verificar e corrigir!")';
					}
				}
			}

		}
	}

	//acesso seguro. Mais checagens
	if($_GET['action'] == 'insert-resposta')
	{
		$pesq = new pesquisa($_GET['pesq']);
		$resp = new resposta();

		if($pesq->size())
		{
			$insc = new inscricao();
			if($_GET['cpf'] != ''){
				$insc->cpf = $_GET['cpf'];
			}
			if($_GET['email'] != ''){
				$insc->email = $_GET['email'];
			}

			$insc->loadAll();

			$perg = new pergunta();
			$perg->pesquisa = $pesq->id;
			$perg->loadAll();

			do
			{
				$resp->created_on = dboNow();
				$resp->pesquisa = $pesq->id;
				$resp->pergunta = $perg->id;
				if($insc->size())
				{
					$resp->inscricao = $insc->id;
					$resp->cpf_pesquisado = $insc->cpf;
				}else{
					$resp->cpf_pesquisado = $cpf;
				}
				if ($_GET['ip'] != "") {
					$resp->ip_pesquisado = $_GET['ip'];
				}

				$input = "outro".makeSlug($perg->pergunta);


				if($_POST[$input]){
					$resp->eh_outro = 'true';
					$resp->resposta = $_POST[$input];
				}else{
					$resp->resposta = implode("\n", (array)$_POST[makeSlug($perg->pergunta)]);
				}

				if(!$resp->save())
				{
					$json_result['message'] = '<div class="error">Erro ao salvar as respostas</div>';
				}
			}while ($perg->fetch());

			$json_result['message'] = '<div class="success">Obrigado por contribuir com a pesquisa</div>';
			$json_result['reload'][] = '#conteudo';

		}

	}
}

echo json_encode($json_result);

?>
