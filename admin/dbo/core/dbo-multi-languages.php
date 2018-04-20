<?php

	global $hooks;
	global $_system;

	//se existir uma linguagem na seção, usa ela como a ativa.
	//para o admin
	if(isDboAdminContext())
	{
		if($_SESSION[sysId()]['admin']['dbo_active_language'])
		{
			if(in_array($_SESSION[sysId()]['admin']['dbo_active_language'], array_keys((array)$_system['dbo_languages'])))
			{
				dboSetActiveLanguage($_SESSION[sysId()]['admin']['dbo_active_language']);
			}
		}
	}
	//ou para o site
	else
	{
		if($_SESSION[sysId()]['site']['dbo_active_language'])
		{
			if(in_array($_SESSION[sysId()]['site']['dbo_active_language'], array_keys((array)$_system['dbo_languages'])))
			{
				dboSetActiveLanguage($_SESSION[sysId()]['site']['dbo_active_language']);
			}
		}
	}
	
	//se existe uma linguagem na url, usa ela como a ativa
	if($_GET['dbo_language'])
	{
		if(in_array($_GET['dbo_language'], array_keys((array)$_system['dbo_languages'])))
		{
			dboSetActiveLanguage($_GET['dbo_language']);
		}
	}

	//definindo o hook para limpar o cache do sistema
	function button_switch_dbo_languages()
	{
		global $_system;
		?>
		<li><a href="" class="color light pointer" title="Trocar o idioma do site" data-dropdown="drop-dbo-admin-language-switcher"><i class="fa fa-fw fa-globe"></i> <?= $_system['dbo_languages'][$_system['dbo_active_language']] ?></a></li>
		<ul id="drop-dbo-admin-language-switcher" class="f-dropdown" data-dropdown-content>
			<?php
				foreach($_system['dbo_languages'] as $key => $value)
				{
					?>
					<li style="display: block;"><a href="<?= keepUrl('dbo_language='.$key) ?>"><?= $value ?></a></li>
					<?php
				}
			?>
		</ul>
		<?php
	}
	if(is_object($hooks))
	{
		$hooks->add_action('dbo_top_dock', 'button_switch_dbo_languages');
	}

	//funcao que ste a linguagem ativa
	function dboSetActiveLanguage($lang)
	{
		global $_system;
		$_SESSION[sysId()][(isDboAdminContext() ? 'admin' : 'site')]['dbo_active_language'] = $lang;
		$_system['dbo_active_language'] = $lang;
	}

	//retorna a lingua ativa
	function dboGetActiveLanguage()
	{
		global $_system;
		return $_system['dbo_active_language'];
	}

?>