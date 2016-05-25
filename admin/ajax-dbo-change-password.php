<? 
	require_once("lib/includes.php"); 
	
	$json_result = array();

	CSRFCheckJson();

	if(!secureUrl())
	{
		$json_result['message'] = '<div class="error">Tentativa de acesso inválida</div>';
	}
	else
	{
		//acesso seguro. Mais checagens
		if($_GET['action'] == 'change-password')
		{
			if(loggedUser() == $_GET['pessoa_id'])
			{
				$pes = new pessoa(dboescape($_GET['pessoa_id']));
				if($pes->size())
				{
					//verificando se a senha atual está correta.
					if($pes->cryptPassword($_POST['pass_atual']) == $pes->pass)
					{
						if($_POST['pass'] == $_POST['pass_check'] && strlen(trim($_POST['pass'])))
						{
							$pes->pass = $pes->cryptPassword($_POST['pass']);
							$pes->update();
							$json_result['message'] = '<div class="success">Senha alterada com sucesso.</div>';
							$json_result['eval'] = '$("#form-dbo-change-password").foundation("reveal", "close");';
						}
						else
						{
							$json_result['message'] = '<div class="error">As senhas novas não conferem.</div>';
						}
					}
					else
					{
						$json_result['message'] = '<div class="error">A senha atual não confere</div>';
					}
				}
				else
				{
					$json_result['message'] = '<div class="error">Pessoa não existe.</div>';
				}
			}
			else
			{
				$json_result['message'] = '<div class="error">Pessoa diferente da seção logada</div>';
			}
		}
	}

	echo json_encode($json_result);

?>