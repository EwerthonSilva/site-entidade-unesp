<?

/* ================================================================================================================== */
/* DBO CLASS FILE FOR MODULE 'pesquisa' ========================================= AUTO-CREATED ON 19/04/2016 12:17:28 */
/* ================================================================================================================== */

/* IMPORTANT: This file is generated only in the first DBO sync, what means you should edit only via text editor. */

if(!class_exists('pesquisa'))
{
	class pesquisa extends dbo
	{
		/* smart constructor: will perform load() upon numeric argument and loadAll() upon string argument */
		function __construct($foo = '')
		{
			parent::__construct('pesquisa');
			if($foo != '')
			{
				if(is_numeric($foo))
				{
					$this->id = $foo;
					$this->load();
				}
				elseif(is_string($foo))
				{
					$this->loadAll($foo);
				}
			}
		}

		function temAutenticacao()
		{
			$auth = '';
			$auth = explode("\n",$this->autenticacao);
			$retorno = 0;

			foreach ($auth as $value) {
				if($value != 'aberta')
				{
					if($value == 'email')
					{
						$retorno += 1;
					}
					elseif ($value == 'email_pessoal')
					{
						$retorno += 2;
					}
				}
				if($value == "ip")
				{
					$retorno += 4;
				}
			}
			return $retorno;
		}

		function renderAutenticacao()
		{
			$action = 'ajax-insert-resposta.php?action=login&pesq='.$this->id;
			$html = '<form method="post" action="'.secureUrl($action).'" class="no-margin peixe-json" id="form-login" autocomplete="off" peixe-log>'.
			'<div name="autenticacao" class="row">'
			;

			if($this->temAutenticacao() != 0)
			{
				$html .= '<div class="large-9 large-offset-1 columns"><label for="email"> E-mail</label><input type="email" name="email" placeholder="exemplo@exemplo.com" required autofocus></div>';
			}
			if ($this->temAutenticacao() >= 2)
			{
				$html .= '<div class="large-9 large-offset-1 columns"><label for="cpf"> CPF</label><input type="text" name="cpf" onkeypress="MascaraCPF(this);checkCPF(this)" maxlength="14" required></div>';
			}


		$html .= '<div class="large-11 columns"><input type="submit" value="Login" class="button no-margin radios" disabled="true"/></div></div></form>';
		return $html;
		}

		function renderQuestoes($cpf = '', $email = '', $ip = ''){
			$action = 'ajax-insert-resposta.php?action=insert-resposta&pesq='.$this->id.'&cpf='.$cpf.'&email='.$email.'&ip='.$ip;
			$perg = new pergunta();
			$perg->pesquisa = $this->id;
			$perg->loadAll();

			if($perg->size())
			{
				$html = '<h3>'.$this->nome.'</h3><hr>'."<form method='post' action='".secureUrl($action)."' class='no-margin peixe-json' id='form-resposta' autocomplete='off' peixe-log>";
				do{
					$html .= '<div class="row">';
					$html .= '<div class="columns large-12">';
					$html .= $perg->getInput();
					$html .= '<br /><br /></div>';
					$html .= '</div>';
				}while ($perg->fetch());
				submitToken();
				$html .= '<div class="row"><div class="large-12 columns text-center"><input type="submit" value="Enviar minhas respostas" class="button radius"></div></div></form>';
			}
			else
			{
				$html = '<div class="error">Não existem perguntas cadastradas</div>';
			}

			return $html;
		}

		function ifBeBetweenDates(){
			if((dboNow() >= $this->data_inicio) && (dboNow() <= $this->data_termino)){
				return true;
			}else{
				return false;
			}
		}

		function save()
		{
			$this->slug = makeSlug($this->nome);
			print_r($this->slug);
			parent::save();
		}

	} //class declaration
} //if ! class exists

?>
