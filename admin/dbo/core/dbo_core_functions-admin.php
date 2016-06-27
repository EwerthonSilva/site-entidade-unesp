<?php

	function getPrettyHeaderSetting($setting)
	{
		global $_system;
		return $_system['pretty_header'][$setting];
	}
	
	// ----------------------------------------------------------------------------------------------------------------

	function hasPrettyHeader()
	{
		global $_system;
		return is_array($_system['pretty_header']);
	}
	
	// ----------------------------------------------------------------------------------------------------------------

	function prettyHeaderAtts()
	{
		global $_system;
		if(hasPrettyHeader())
		{
			extract($_system['pretty_header']);
			$classes .= ' pretty-header';
			if($theme) $classes .= ' '.$theme.'-theme';
			if($hide_menu) $classes .= ' hide-menu';
			if($height) $styles .= ' height: '.$height.'px;';
		}
		return ' class="'.trim($classes).'" style="'.trim($styles).'"';
	}
	
	// ----------------------------------------------------------------------------------------------------------------

	function prettyHeaderLogo()
	{
		ob_start();
		if(hasPrettyHeader())
		{
			?>
			<div class="row">
				<div class="large-12 columns">
					<a href="<?= SITE_URL ?>" id="pretty-logo" style="position: absolute; top: <?= getPrettyHeaderSetting('logo_offset') ?>px; left: 15px; z-index: 50;" target="_blank"><img src="images/admin-logo.png" alt="" style="max-height: <?= getPrettyHeaderSetting('logo_height') ?>px;"></a>
				</div>
			</div>
			<?php
		}
		return ob_get_clean();
	}
	
	// ----------------------------------------------------------------------------------------------------------------

	function makeDboButtons ($url = '')
	{

		global $dbo;

		dumpMid();

		$d = dir(DBO_PATH);
		while (false !== ($entry = $d->read())) {
			if(strpos($entry, "_dbo_") === 0)
			{
				$arq_modulos[] = $entry;
			}
		}
		$d->close();

		// incluindo cada modulo para gerar os botoes
		foreach($arq_modulos as $valor)
		{
			$file_code = file_get_contents(DBO_PATH."/".$valor);
			ob_start();
			eval("?>".$file_code."<?php ");
			$output = ob_get_clean();

			$key = intval(safeArrayKey($module->order_by, $modulos));

			if($module->module_icon)
			{
				$modulos[$key]['icon'] = $module->module_icon;
			}
			else
			{
				//testando icones do modulo... tem que ser png e minúsculo, e ter o mesmo nome do modulo, evidentemente.
				if(file_exists(DBO_PATH."/../images/module_icons/".$module->modulo.".png"))
				{
					$modulos[$key]['icon'] = $module->modulo.".png";
				} else {
					$modulos[$key]['icon'] = "cube";
				}
			}
			$modulos[$key]['titulo'] = (($module->titulo_big_button)?($module->titulo_big_button):($module->titulo_plural));
			$modulos[$key]['var'] = $module->modulo;
		}

		//incluindo as páginas
		if(class_exists('pagina'))
		{
			pagina::registerMenus($modulos);
		}

		ksort($modulos);

		$count = 1;
		foreach($modulos as $chave => $valor)
		{
			$count = insertCustomMenu($count, 'cockpit');

			//imprimindo botao de modulo de cadastro
			if(!DBO_PERMISSIONS || hasPermission('cockpit', $valor['var']))
			{
				$notification_function = $valor['var']."_notifications";
				$notifications = 0;
				if(function_exists($notification_function))
				{
					$notifications = $notification_function();
				}
				?>
				<li>
					<a class="radius" href="<?= (($valor['custom_url'])?($valor['custom_url']):(($url)?($url.'?dbo_mod='. $valor['var']):($dbo->keepUrl(array('!dbo_new&!dbo_update&!dbo_delete&!dbo_view&!pag!','dbo_mod='.$valor['var']))))) ?>">
					<?php
						if(strstr($valor['icon'], '.png'))
						{
							?>
							<span class='icon' style='background-image: url(<?= DBO_URL ?>/../images/module_icons/<?= $valor['icon'] ?>);'></span>
							<?php
						}
						else
						{
							?>
							<i class="fa fa-fw fa-<?= $valor['icon'] ?>"></i>
							<?php
						}
					?>
					<div class='name'><?= $valor['titulo'] ?></div>
					<?= (($notifications)?("<span class='notifications'><?= $notifications ?></span>"):('')) ?></a>
				</li>
				<?php
			}
			$count++;
		}
	}

	// ----------------------------------------------------------------------------------------------------------------

	function dboSideBarMenu ($url = '')
	{

		global $dbo;

		dumpMid();

		$d = dir(DBO_PATH);
		while (false !== ($entry = $d->read())) {
			if(strpos($entry, "_dbo_") === 0)
			{
				$arq_modulos[] = $entry;
			}
		}
		$d->close();

		// incluindo cada modulo para gerar os botoes
		foreach($arq_modulos as $valor)
		{
			$file_code = file_get_contents(DBO_PATH."/".$valor);
			ob_start();
			eval("?>".$file_code."<?");
			$output = ob_get_clean();

			$key = safeArrayKey($module->order_by, $modulos);

			if($module->module_icon)
			{
				$modulos[$key]['icon'] = $module->module_icon;
			}
			else
			{
				//testando icones do modulo... tem que ser png e minúsculo, e ter o mesmo nome do modulo, evidentemente.
				if(file_exists(DBO_PATH."/../images/module_icons/".$module->modulo.".png"))
				{
					$modulos[$key]['icon'] = $module->modulo.".png";
				} else {
					$modulos[$key]['icon'] = "cube";
				}
			}
			$modulos[$key]['titulo'] = (($module->titulo_big_button)?($module->titulo_big_button):($module->titulo_plural));
			$modulos[$key]['var'] = $module->modulo;
		}

		//incluindo as páginas
		if(class_exists('pagina'))
		{
			pagina::registerMenus($modulos);
		}

		ksort($modulos);

		$count = 1;
		foreach($modulos as $chave => $valor)
		{

			$count = insertCustomMenu($count, 'sidebar');

			//imprimindo botao de modulo de cadastro
			if(!DBO_PERMISSIONS || hasPermission('sidebar', $valor['var']))
			{
				$notification_function = $valor['var']."_notifications";
				$notifications = 0;
				if(function_exists($notification_function))
				{
					$notifications = $notification_function();
				}
				?>
				<li>
					<a href="<?= (($valor['custom_url'])?($valor['custom_url']):(($url)?($url.'?dbo_mod='.$valor['var']):($dbo->keepUrl(array('!dbo_new&!dbo_update&!dbo_delete&!dbo_view&!pag!','dbo_mod='.$valor['var']))))) ?>" class="sidebar-button">
						<?php
							if(strstr($valor['icon'], '.png'))
							{
								?><i style="background-image: url(<?= DBO_URL."/../images/module_icons/".$valor['icon'] ?>)"></i><?php
							}
							else
							{
								?><i class="fa fa-fw fa-<?= $valor['icon'] ?>"></i><?php		
							}
						?><span class="name"><?= $valor['titulo'].(($notifications)?('<span class="notifications">'.$notifications.'</span>'):('')) ?></span>
					</a>
				</li>
				<?php
			}
			$count++;
		}
	}

	// ----------------------------------------------------------------------------------------------------------------

	function insertCustomMenu($count, $permission = 'cockpit')
	{
		//checando se há um custom na posição
		$custom_button = dboCustomMenus($count);
		if($custom_button)
		{
			if(!DBO_PERMISSIONS || hasPermission($permission, $custom_button->slug))
			{
				if($permission == 'cockpit')
				{
					$notification_function = $custom_button->notification_function;
					$notifications = 0;
					if(function_exists($notification_function))
					{
						$notifications = $notification_function();
					}
					echo "<li><a class='radius' href='".$custom_button->url."' class='big-button' ".(($custom_button->target)?("target='".$custom_button->target."'"):(''))." ><span class='icon' style='background-image: url(".DBO_URL."/../images/module_icons/".$custom_button->image.");'></span><div class='name'>".$custom_button->name."</div>".(($notifications)?("<span class='notifications'>".$notifications."</span>"):(''))."</a></li>\n";
					$count++;
					insertCustomMenu($count, $permission);
				}
				elseif($permission == 'sidebar')
				{
					$notification_function = $custom_button->notification_function;
					$notifications = 0;
					if(function_exists($notification_function))
					{
						$notifications = $notification_function();
					}
					echo "<li><a href='".$custom_button->url."' class='sidebar-button' ".(($custom_button->target)?("target='".$custom_button->target."'"):(''))." ><i style='background-image: url(".DBO_URL."/../images/module_icons/".$custom_button->image.")'></i><span class='name'>".$custom_button->name."".(($notifications)?("<span class='notifications'>".$notifications."</span>"):(''))."</span></a></li><li class='divider'></li>\n";
					$count++;
					insertCustomMenu($count, $permission);
				}
			}
		}
		return $count++;
	}

	// ----------------------------------------------------------------------------------------------------------------

	function getItemsSidebar()
	{
		global $_sys;
		//checando se os perfis estão carregados
		if(!is_array($_sys[sysId()]['perfis_permissoes']))
		{
			loadAllPerfis();
		}
		
		$perfis_pessoa = getPerfisPessoa(loggedUser());

		if(is_array($_sys[sysId()]['perfis_permissoes']))
		{
			$total = 0;
			foreach($_sys[sysId()]['perfis_permissoes'] as $perfil_id => $perfil)
			{
				if(in_array($perfil_id, $perfis_pessoa))
				{
					foreach($perfil as $modulo)
					{
						if(is_array($modulo))
						{
							if(array_key_exists('sidebar', $modulo))
							{
								$total++;
							}
						}
					}
				}
			}
			return $total;
		}
		return false;
	}

	// ----------------------------------------------------------------------------------------------------------------

	function renderBreadcrumbItem($data, $params = array())
	{
		ob_start();
		if($data['module'])
		{
			$mod = $data['module'];
			$obj = new $mod();
			if($data['id'])
			{
				$obj->id = $data['id'];
				$obj->load();
				return '<li '.($params['id'] ? 'id="'.$params['id'].'"' : '').'><a href="dbo_admin.php?dbo_mod='.$data['module'].'&dbo_update='.$data['id'].'">'.$obj->getBreadcrumbIdentifier().'</a></li>';
			}
			else
			{
				return '<li '.($params['id'] ? 'id="'.$params['id'].'"' : '').'><a href="dbo_admin.php?dbo_mod='.$data['module'].'">'.$obj->getBreadcrumbIdentifier().'</a></li>';
			}
		}
		elseif($data['url'])
		{
			return '<li '.($params['id'] ? 'id="'.$params['id'].'"' : '').'><a href="'.$data['url'].'">'.$data['label'].'</a></li>';
		}
		return ob_get_clean();
	}
	
	// ----------------------------------------------------------------------------------------------------------------
	
	function dboBreadcrumbs($params = array())
	{
		/*
			ignore_hooks
		*/
		global $hooks;
		extract($params);
		ob_start();
		?>
		<div class="breadcrumb">
			<ul style="margin-bottom: 6px;">
				<?php 
					if(!$ignore_hooks)
					{
						$hooks->do_action('dbo_breadcrumbs_prepend');
					}
					foreach((array)$stack as $data)
					{
						echo renderBreadcrumbItem($data, $data['params']);
					}
					if(!$ignore_hooks)
					{
						$hooks->do_action('dbo_breadcrumbs_append');
					}
				?>
			</ul>
		</div>
		<?php
		return ob_get_clean();
	}

?>