<?php

	global $dbo_cache_file;
	global $hooks;

	//define o path para os arquivos de path, se ja não definido no defines.php
	if(!defined(DBO_CACHE_PATH))
	{
		define(DBO_CACHE_PATH, dirname(dirname(__FILE__))."/cache");
	}

	//definindo o hook para limpar o cache do sistema
	function button_clear_dbo_cache()
	{
		?>
		<li><a href="<?= DBO_URL ?>/core/dbo-cache-ajax.php?action=clean-cache&<?= CSRFVar(); ?>" class="color light pointer peixe-json" title="Limpar o cache das páginas do site" data-tooltip><i class="fa fa-fw fa-refresh"></i></a></li>
		<?php
	}
	if(is_object($hooks))
	{
		$hooks->add_action('dbo_top_dock', 'button_clear_dbo_cache');
	}

	//função que retorna uma url com a flag de no-cache, para que seja gerada e cacheada. Esta url é gerada pela função de cache
	function noCacheUrl() 
	{
		parse_str($_SERVER['QUERY_STRING'], $query_string);
		$query_string['dbo_no_cache'] = 1;
		
		$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
		$sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
		$protocol = substr($sp, 0, strpos($sp, "/")) . $s;
		$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
		return $protocol . "://" . $_SERVER['HTTP_HOST'] . $port . $_SERVER['PHP_SELF'].'?'.http_build_query($query_string);
	}

	//funcao que verifica se o arquivo de cache está expirado
	function dboCacheFileOlderThan($file, $time)
	{
		return time() - dboCacheHuman2Seconds($time) > filemtime($file);
	}

	//verifica se o caminho do cache está com permissão de escrita
	function checkDboCachePath()
	{
		if(!is_writable(DBO_CACHE_PATH))
		{
			trigger_error('The cache folder <strong>"'.DBO_CACHE_PATH.'"</strong> is not writable.', E_USER_ERROR);
		}
	}

	//-----------------------------------------------------------------------------------------------
	//tratando cache para blocos de html ------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------

	//começa a capturar o output para o cache
	function dboCacheStart()
	{
		ob_start();
	}

	//verifica se o arquivo de cache existe
	function dboCached($params = array())
	{
		checkDboCachePath();

		global $_system;
		global $dbo_cache_file;
		extract($params);

		//verificando qual parametro de expiração deve ser seguido
		//primeiro, verifica se foi especificado um expire direto na chamada. Se sim, já ignora o resto da lógica.
		if(!$expire)
		{
			//em seguida tentanmos atribuir o valor da slug, se ela existe. Se ela não foi setada ou não existe, $expire vai ser null de qualquer forma.
			$expire = $_system['cache_settings']['expire']['block'][$dbo_cache_file];
			//em seguida, caso não tenha slug, tentamos o valor global. Se não estiver setado, o cache é perpetudo e soh pode ser apagado via backend.
			$expire = $expire ? $expire : $_system['cache_settings']['expire']['global'];
		}
		if($expire && file_exists(DBO_CACHE_PATH."/".$dbo_cache_file) && dboCacheFileOlderThan(DBO_CACHE_PATH."/".$dbo_cache_file, $expire))
		{
			unlink(DBO_CACHE_PATH."/".$dbo_cache_file);
		}
		return file_exists(DBO_CACHE_PATH."/".$dbo_cache_file);
	}

	//inclui o arquivo cacheado
	function dboCacheLoad()
	{
		global $dbo_cache_file;
		include(DBO_CACHE_PATH.'/'.$dbo_cache_file);
	}

	//captura o output e salva no arquivo. da proxima vez, estará cacheado.
	function dboCacheEnd()
	{
		global $dbo_cache_file;
		$f = fopen(DBO_CACHE_PATH.'/'.$dbo_cache_file, 'w');
		$content = ob_get_contents();
		fwrite($f, $content);
		fclose($f);
	}

	function dboCacheHuman2Seconds($string)
	{
		preg_match('#([0-9]+)([smhdw])#is', $string, $match);
		if(is_array($match))
		{
			switch($match[2])
			{
				case 's': //segundos
					$multi = 1;
					break;
				case 'm': //minutos 1*60
					$multi = 60;
					break;
				case 'h': //horas 1*60*60
					$multi = 3600;
					break;
				case 'd': //dias 1*60*60*24
					$multi = 86400;
					break;
				case 'w': //semanas 1*60*60*24*7
					$multi = 604800;
					break;
			}
		}
		return $match[1]*$multi;
	}

	//-----------------------------------------------------------------------------------------------
	//tratando cache para paginas inteiras ----------------------------------------------------------
	//-----------------------------------------------------------------------------------------------

	function dboCachePage($params = array())
	{
		global $_system;
		extract($params);
	
		//verifica se o cache de paginas está ativo, e se não foi desativado especificamente para esta slug.
		if(DBO_CACHE_PAGES === true && $_system['cache_settings']['expire']['slug'][$slug] !== false)
		{
			checkDboCachePath();

			//verificando qual parametro de expiração deve ser seguido
			//primeiro, verifica se foi especificado um expire direto na chamada. Se sim, já ignora o resto da lógica.
			if(!$expire)
			{
				//em seguida tentanmos atribuir o valor da slug, se ela existe. Se ela não foi setada ou não existe, $expire vai ser null de qualquer forma.
				$expire = $_system['cache_settings']['expire']['slug'][$slug];
				//em seguida, caso não tenha slug, tentamos o valor global. Se não estiver setado, o cache é perpetudo e soh pode ser apagado via backend.
				$expire = $expire ? $expire : $_system['cache_settings']['expire']['global'];
			}

			//soh faz a logica de cache se a flag de no-cache não estiver na url.
			if(!$_GET['dbo_no_cache'])
			{
				parse_str($_SERVER['QUERY_STRING'], $query_string);

				//construindo o nome do arquivo de cache
				unset($query_string['dbo_no_cache']);
				$cache_file = DBO_CACHE_PATH."/".($_GET['slug'] ? $_GET['slug'].'-' : '').str_replace("/", "", base64_encode($_SERVER['PHP_SELF'].'?'.http_build_query($query_string)));

				//verificando a validade do cache, se venceu, apaga o arquivo para a proxima logica gerar novamente.
				if($expire && file_exists($cache_file) && dboCacheFileOlderThan($cache_file, $expire))
				{
					unlink($cache_file);
				}

				//se o arquivo de cache existe, inclui ele e encerra
				if(file_exists($cache_file))
				{
					include($cache_file);
					exit();
				}
				//senão, gera novamente e inclui.
				else
				{
					$content = file_get_contents(noCacheUrl());
					$f = fopen($cache_file, 'w');
					fwrite($f, $content);
					fclose($f);
				}
			}
		}
	}


?>