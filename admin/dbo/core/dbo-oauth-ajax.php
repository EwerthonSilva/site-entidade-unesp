<?
	require_once('../../lib/includes.php');
	require_once(DBO_PATH.'/core/classes/vendor/autoload.php');

	//função para pegar o usuário a partir do Token do Google
	function getGoogleUserFromToken($token) {
		global $client;
		$ticket = $client->verifyIdToken($token);
		if ($ticket) {
			$data = $ticket->getAttributes();
			return $data['payload']; // user data
		}
		return false;
	}

	$json_result = array();

	//se estiver setada esta variavel, tenta fazer a autenticação com o Google.
	if($_POST['google_id_token'] )
	{

		$oauth_link_id = $_GET['oauth_link_id'] ? $_GET['oauth_link_id'] : loggedUser();

		//verifica se a url é segura e o CSRF
		secureUrlCheck();
		CSRFCheckJson();

		//instanciando o cliente do Google
		$client = new Google_Client();
		//$client->setApplicationName("Peixe Laranja");
		//$client->setDeveloperKey("AIzaSyCp6FlrJdXY3AZSP90h1cBYGoFxgfeYGjI");
		$client->setAuthConfig(GOOGLE_AUTH_CONFIG_JSON);

		//primeiro de tudo, tenta pegar o usuário do google do token
		try {

			$google_user = getGoogleUserFromToken($_POST['google_id_token']);
			
			if(strlen(trim($google_user['sub'])))
			{
				//antes de mais nada, verifica se existe uma pessoa no bando de dados com este id do google. Se for este o caso, vai então realizar um login.
				$pes = new pessoa("WHERE google_id = '".dboescape($google_user['sub'])."'");
				//achou a pessoa. Faz então o login, instancioando na sessao.
				if($pes->size())
				{
					$_SESSION['user_id'] = $pes->id;
					$_SESSION['user'] = $pes->user;
					setMessage('<div class="success">Login realizado com sucesso utilizando sua conta do <strong>Google</strong>.</div>');
					if($_GET['dbo_redirect']) { $json_result['redirect'] = dboDecode($_GET['dbo_redirect']); } else { $json_result['eval'] = singleLine('location.reload();'); }
				}
				//se não achou a pessoa, verifica se é para vincular uma pessoa que está no GET ou se é possível criar novos usuários
				else
				{
					//tenta instanciar a pessoa com o ID da URL ou o e-mail de cadastro
					$pes = new pessoa("WHERE id = '".$oauth_link_id."' OR email = '".$google_user['email']."'");

					//se conseguiu instanciar o usuário ou pode criar novo, atribui todas as informações.
					if($pes->size() || OAUTH_ALLOW_NEW_USERS === true)
					{
						$pes->nome = strlen(trim($pes->nome)) ? $pes->nome : $google_user['name'];
						$pes->apelido = strlen(trim($pes->apelido)) ? $pes->apelido : $google_user['given_name'];
						$pes->user = strlen(trim($pes->user)) ? $pes->user : $google_user['email'];
						$pes->email = strlen(trim($pes->email)) ? $pes->email : $google_user['email'];
						$pes->foto = strlen(trim($pes->foto)) ? $pes->foto : (strlen(trim($google_user['picture'])) ? $pes->_foto->createFromUrl($google_user['picture']) : '');
						$pes->google_id = $google_user['sub'];
					}

					if($pes->size())
					{
						//atualiza o usuário, vinculando à conta do google.
						$pes->update();
						setMessage('<div class="success">Seu usário foi vinculado com sucesso à sua conta do <strong>Google</strong>.</div>');
						if($_GET['dbo_redirect']) { $json_result['redirect'] = dboDecode($_GET['dbo_redirect']); } else { $json_result['eval'] = singleLine('location.reload();'); }

						//instancia
						$_SESSION['user'] = $pes->user;
						$_SESSION['user_id'] = $pes->id;
					}
					elseif(OAUTH_ALLOW_NEW_USERS === true)
					{
						//atualiza o usuário, vinculando à conta do google.
						$pes->save();
						setMessage('<div class="success">Login realizado com sucesso utilizando sua conta do <strong>Google</strong>.</div>');
						if($_GET['dbo_redirect']) { $json_result['redirect'] = dboDecode($_GET['dbo_redirect']); } else { $json_result['eval'] = singleLine('location.reload();'); }

						//instancia
						$_SESSION['user'] = $pes->user;
						$_SESSION['user_id'] = $pes->id;

						//se houver perfis padrão, atribui.
						if(defined('OAUTH_NEW_USER_PERFIL'))
						{
							$perfis = explode(',', OAUTH_NEW_USER_PERFIL);
							$perfis = array_map(trim, $perfis);
							foreach($perfis as $perfil)
							{
								atribuiPerfilPessoa($perfil, $pes->id);
							}
						}
					}
					else
					{
						$json_result['message'] = '<div class="success">O login com a conta do <strong>Google</strong> só está disponível para <strong>usuários já cadastrados</strong>.</div>';
					}
				}
			}

		} catch (Exception $e) {
			$json_result['message'] = '<div class="error">Caught exception: '.$e->getMessage().'</div>';
		}

	}
	elseif($_POST['facebook'])
	{

		$oauth_link_id = $_GET['oauth_link_id'] ? $_GET['oauth_link_id'] : loggedUser();

		//verifica se a url é segura e o CSRF
		secureUrlCheck();
		CSRFCheckJson();

		//tenta instanciar o objeto do facebook e realizar todo o processo
		try {

			//instancia o objeto do facebook
			$fb = new Facebook\Facebook([
				'app_id' => '157364511377524',
				'app_secret' => 'b70bd22d782120c2731ff0179d3b0a08',
				'default_graph_version' => 'v2.2',
			]);
			
			//intancia o helper
			$helper = $fb->getJavaScriptHelper();
			//recupera o accessToken do cookie
			$access_token = $helper->getAccessToken();
			//recupera os dados do usuário da API de Graph

			if($access_token)
			{
				$facebook_data = json_decode(file_get_contents('https://graph.facebook.com/me?fields=id,email,name,first_name,picture.height(500),gender&access_token='.$access_token));
			}

			if(isset($facebook_data))
			{
				//antes de mais nada, verifica se existe uma pessoa no bando de dados com este id do facebook. Se for este o caso, vai então realizar um login.
				$pes = new pessoa("WHERE facebook_id = '".dboescape($facebook_data->id)."'");
				//achou a pessoa. Faz então o login, instancioando na sessao.
				if($pes->size())
				{
					$_SESSION['user_id'] = $pes->id;
					$_SESSION['user'] = $pes->user;
					setMessage('<div class="success">Login realizado com sucesso utilizando sua conta do <strong>Facebook</strong>.</div>');
					if($_GET['dbo_redirect']) { $json_result['redirect'] = dboDecode($_GET['dbo_redirect']); } else { $json_result['eval'] = singleLine('location.reload();'); }
				}
				//se não achou a pessoa, verifica se é para vincular uma pessoa que está no GET ou se é possível criar novos usuários
				else
				{
					//tenta instanciar a pessoa com o ID da URL ou o e-mail de cadastro
					$pes = new pessoa("WHERE id = '".$oauth_link_id."' OR email = '".$facebook_data->email."'");

					//se conseguiu instanciar o usuário ou pode criar novo, atribui todas as informações.
					if($pes->size() || OAUTH_ALLOW_NEW_USERS === true)
					{
						$pes->nome = strlen(trim($pes->nome)) ? $pes->nome : $facebook_data->name;
						$pes->apelido = strlen(trim($pes->apelido)) ? $pes->apelido : $facebook_data->first_name;
						$pes->user = strlen(trim($pes->user)) ? $pes->user : $facebook_data->email;
						$pes->email = strlen(trim($pes->email)) ? $pes->email : $facebook_data->email;
						$pes->foto = strlen(trim($pes->foto)) ? $pes->foto : ($facebook_data->picture->data->url ? $pes->_foto->createFromUrl($facebook_data->picture->data->url) : '');
						$pes->sexo = strlen(trim($pes->sexo)) ? $pes->sexo : ($facebook_data->gender ? ($facebook_data->gender == 'male' ? 'm' : ($facebook_data->gender == 'female' ? 'f' : '')) : '');
						$pes->facebook_id = $facebook_data->id;
					}

					if($pes->size())
					{
						//atualiza o usuário, vinculando à conta do facebook.
						$pes->update();
						setMessage('<div class="success">Seu usário foi vinculado com sucesso à sua conta do <strong>Facebook</strong>.</div>');
						if($_GET['dbo_redirect']) { $json_result['redirect'] = dboDecode($_GET['dbo_redirect']); } else { $json_result['eval'] = singleLine('location.reload();'); }

						//instancia
						$_SESSION['user'] = $pes->user;
						$_SESSION['user_id'] = $pes->id;
					}
					elseif(OAUTH_ALLOW_NEW_USERS === true)
					{
						//atualiza o usuário, vinculando à conta do facebook.
						$pes->save();
						setMessage('<div class="success">Login realizado com sucesso utilizando sua conta do <strong>Facebook</strong>.</div>');
						if($_GET['dbo_redirect']) { $json_result['redirect'] = dboDecode($_GET['dbo_redirect']); } else { $json_result['eval'] = singleLine('location.reload();'); }

						//instancia
						$_SESSION['user'] = $pes->user;
						$_SESSION['user_id'] = $pes->id;

						//se houver perfis padrão, atribui.
						if(defined('OAUTH_NEW_USER_PERFIL'))
						{
							$perfis = explode(',', OAUTH_NEW_USER_PERFIL);
							$perfis = array_map(trim, $perfis);
							foreach($perfis as $perfil)
							{
								atribuiPerfilPessoa($perfil, $pes->id);
							}
						}
					}
					else
					{
						$json_result['message'] = '<div class="success">O login com a conta do <strong>Facebook</strong> só está disponível para <strong>usuários já cadastrados</strong>.</div>';
					}
				}
			}

		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			$json_result['message'] = '<div class="error">Graph returned an error: '.$e->getMessage().'</div>';
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			$json_result['message'] = '<div class="error">Facebook SDK returned an error: '.$e->getMessage().'</div>';
		}

	}

	echo json_encode($json_result);

?>