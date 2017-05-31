<?

/* ================================================================================================================== */
/* DBO CLASS FILE FOR MODULE 'pessoa' =========================================== AUTO-CREATED ON 04/05/2012 16:21:58 */
/* ================================================================================================================== */

/* IMPORTANT: This file is generated only in the first DBO sync, what means you should edit only via text editor. */

if(!class_exists('pessoa'))
{
	class pessoa extends dbo
	{
		/* smart constructor: will perform load() upon numeric argument and loadAll() upon string argument */
		function __construct($foo = '')
		{
			parent::__construct('pessoa');
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

		//your methods here
		//encriptando o password
		function save()
		{
			if(strlen(trim($this->pass)) != '128' && strlen(trim($this->pass)) > 0)
			{
				$this->pass = dbo::cryptPassword($this->pass);
			}
			if(!strlen(trim($this->token)))
			{
				$this->token = uniqid();
			}
			return parent::save();
		}

		//encriptando o password
		function update($rest = '')
		{
			if(strlen(trim($this->pass)) != '128' && strlen(trim($this->pass)) > 0)
			{
				$this->pass = dbo::cryptPassword($this->pass);
			}
			if(!strlen(trim($this->token)))
			{
				$this->token = uniqid();
			}
			return parent::update();
		}

		//mostrando a foto do usuário
		function foto($params = array())
		{
			extract($params);
			//params
			//size - small, medium, large
			//gravatar_size - px value, default 200
			if(strlen(trim($this->foto)))
			{
				return $this->_foto->url($params);
			}
			else
			{
				return getGravatar($this->email, ($gravatar_size ? $gravatar_size : 200));
			}
		}

		function getShortName()
		{
			global $_system;
			if(!$_system['pessoa'][$this->id]['short_name'])
			{
				if(strlen(trim($this->apelido)))
				{
					$_system['pessoa'][$this->id]['short_name'] = $this->apelido;
				}
				else
				{
					$partes = explode(" ", $this->nome);
					$_system['pessoa'][$this->id]['short_name'] = ((strlen($partes[0])>=4)?($partes[0]):($partes[0]." ".$partes[1]));
				}
			}
			return $_system['pessoa'][$this->id]['short_name'];
		}

		function getGenero()
		{
			return $this->sexo == 'f' ? 'a' : 'o';
		}

	} //class declaration
} //if ! class exists

?>