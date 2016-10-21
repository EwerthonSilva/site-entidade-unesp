<?php
	require_once('lib/includes.php');
	require_once(DBO_PATH.'/core/dbo-ui.php');

	$json_result = array();
	
	if($_GET['action'] == 'atualizar-perfil')
	{
		secureUrlCheck();
		CSRFCheckJson();
		dboAuth('json');

		if(loggedUser() == $_GET['pessoa_id'])
		{
			//email e nome são sempre obrigatórios
			if(!strlen(trim($_POST['nome'])))
			{
				$json_result['message'] = '<div class="error">Erro: Preencha seu <strong>nome</strong>.</div>';
			}
			elseif(!strlen(trim($_POST['email'])))
			{
				$json_result['message'] = '<div class="error">Erro: Preencha seu <strong>e-mail</strong>.</div>';
			}
			else
			{
				//agrao verifica se a pessoa não está tentando usar e-mail de outra...
				$pes = new pessoa("WHERE email = '".$_POST['email']."' AND id <> '".$_GET['pessoa_id']."'");
				if(!$pes->size())
				{
					$pes = new pessoa(loggedUser());
					//certificando-se que o usuário não vai sacanear e colocar campos que não estão na lista de campos permitidos no perfil
					foreach($_POST as $key => $value)
					{
						if(in_array($key, $_system['meu_perfil']['campos']))
						{
							$update[$key] = $value;
						}
					}
					if(sizeof((array)$update))
					{
						dboUI::smartSet($update, $pes);
						$pes->update();
						$json_result['message'] = '<div class="success">Os dados do seu perfil foram atualizados com <strong>sucesso</strong>!</div>';
						$json_result['reload'][] = '#item-foto';
					}
				}
				else
				{
					$json_result['message'] = '<div class="error">Erro: O e-mail digitado já pertence a <strong>outro usuário</strong>.</div>';
				}
			}

		}
		else
		{
			$json_result['message'] = '<div class="error">Erro: Tentativa de alteração de outro perfil.</div>';
		}
	}
	
	echo json_encode($json_result);
?>