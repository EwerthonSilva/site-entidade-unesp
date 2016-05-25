<? require('header.php') ?>
<? require('auth.php') ?>
<?

	//checa para ver se esse usuário pode estar aqui....
	if(DBO_PERMISSIONS)
	{
		if(!hasPermission('Permissões', 'perfil'))
		{
			setMessage("<div class='error'>Seu usuário não tem permissão de acesso à essa página.</div>");
			$dbo->myHeader("Location: index.php");
		}
	}

	if($_POST['flag_update'])
	{
		CSRFCheckRequest();

		if(is_array($_POST['permission']))
		{
			foreach($_POST['permission'] as $mod => $perm_array)
			{
				if(is_array($perm_array))
				{
					$permission_array[] = $mod."###".@implode("|||", $perm_array);
				} else {
					$permission_array[] = $mod;
				}
			}
			$permission_string = @implode(" %%% ", $permission_array);
		}

		$obj = new dbo('perfil');
		$obj->id = $_POST['flag_update'];
		$obj->load();
		$obj->permissao = $permission_string;
		$obj->update();
		setMessage("<div class='success'>Permissões atualizadas com sucesso.</div>");
		$dbo->myHeader("Location: ".$dbo->keepURL());
	}
?>

<?
	if($_GET['perfil'])
	{
		$obj = new dbo('perfil');
		$obj->id = $_GET['perfil'];
		$obj->load();
	}

	$d = dir(DBO_PATH);
	while (false !== ($entry = $d->read())) {
		if(strpos($entry, "_dbo_") === 0)
		{
			$arq_modulos[] = $entry;
		}
	}
	$d->close();

	function getButtons ($arq)
	{
		global $modulos_array;
		global $modulos_array_nomes;
		global $_system;

		include(DBO_PATH."/".$arq);
		if($module->ignore_permissions !== true)
		{
			$modulos_array[$module->order_by][$module->modulo]['Cockpit'] = 'cockpit';
			$modulos_array[$module->order_by][$module->modulo]['Sidebar'] = 'sidebar';
			$modulos_array[$module->order_by][$module->modulo]['Acesso'] = 'access';
			$modulos_array[$module->order_by][$module->modulo]['Inserir'] = 'insert';
			$modulos_array[$module->order_by][$module->modulo]['Editar'] = 'update';
			$modulos_array[$module->order_by][$module->modulo]['Excluir'] = 'delete';
			$modulos_array[$module->order_by][$module->modulo]['Visualizar'] = 'view';
			$modulos_array_nomes[$module->modulo]['nome'] = $module->titulo;

			//instanciando botoes
			if(is_array($module->button))
			{
				foreach($module->button as $chave => $valor)
				{
					$modulos_array[$module->order_by][$module->modulo][$valor->value] = $valor->value;
				}
			}

			//instanciando permissoes custom
			if(strlen(trim($module->permissoes_custom)))
			{
				$permissoes_custom = trim($module->permissoes_custom);
				$permissoes_custom = explode("\n", $permissoes_custom);
				foreach($permissoes_custom as $permissao_custom)
				{
					$_system['module_permissoes_custom'][trim($permissao_custom)] = trim($permissao_custom);
				}
			}
		}
	}

	$modulos_array = array();
	$modulos_array_nomes = array();

	foreach($arq_modulos as $arq)
	{
		getButtons($arq);
	}

	if(sizeof($modulos_array))
	{
		ksort($modulos_array);
		?>
			<div class="wrapper-permissions">

				<div class="row" style="position: relative;">
				<div class="large-7 columns">
					<div class="breadcrumb">
						<ul class="no-margin">
							<li><a href="cadastros.php"><?= DBO_TERM_CADASTROS ?></a></li>
							<li><a href="dbo_admin.php?dbo_mod=perfil">Perfis</a></li>
							<li><a href="dbo_admin.php?dbo_mod=perfil&dbo_update=<?= $obj->id ?>"><?= $obj->nome ?></a></li>
							<li><a href="#">Permissões</a></li>
						</ul>
					</div>
				</div>
				<div class="large-5 columns text-right">
					<div class="top-less-10">
						<i class="fa fa-sign-out fa-fw pointer color medium tip-top font-14 trigger-exportar-permissoes" data-tooltip title="Exportar permissões"></i>
						<i class="fa fa-sign-in fa-fw pointer color medium tip-top font-14 trigger-importar-permissoes" data-tooltip title="Importar permissões" style="margin-right: 5px;"></i>
						<select id="copiar" class="font-12" style="width: auto; display: inline-block; margin-bottom: 4px;">
							<option value="">Copiar permissões de ...</option>
							<?
								$perf = new perfil('ORDER BY nome');
								do {
									if(!logadoNoPerfil('Desenv') && $perf->dbo_flag_desenv == '1') continue;
									?>
									<option value="<?= $perf->id ?>"><?= $perf->nome ?></option>								
									<?
								}while($perf->fetch());
							?>
						</select>
					</div>
				</div>
			</div>
			<hr class="small">

			<div class="row full">
				<div class="large-12 columns">
					<h3>Módulos</h3>
					<p class="color medium font-14">As permissões desta seção dão acesso aos formulários de cadastro automáticos dos módulos sistema.<br />Elas também valem para módulos que são extensões de páginas.</p>
				</div><!-- col -->
			</div><!-- row -->
			
			<form method="POST" action="dbo_permissions.php?perfil=<?= $_GET['perfil'] ?>" id="form-permissions">
				<div id="wrapper-all-permissions">
					<div class="row full">
						<div class="large-12 columns">
							<table>
								<thead>
									<th colspan="2"></th>
								</thead>
								<tbody>
								<?
									foreach($modulos_array as $modulo)
									{
										foreach($modulo as $chave => $valor)
										{
										?>
											<tr>
												<td style="width: 20%;"><span class="no-margin inline header" id="<?= $chave ?>" title="<?= $chave ?>"><?= $modulos_array_nomes[$chave]['nome'] ?></span></td>
												<td>
												<?
													foreach($valor as $item_chave => $item_permissao)
													{
														$perm = false;
														$perm = perfilHasPermission($_GET["perfil"], $item_permissao, $chave);
													?>
														<span class="item <?= (($perm)?(''):('off')) ?>"><input rel="<?= $chave ?>" type="checkbox" <?= $perm ? 'CHECKED' : '' ?> value="<?= $item_permissao ?>" name="permission[<?= $chave ?>][<?= $item_permissao ?>]"> <span><?= $item_chave ?></span></span>
													<?
													}
												?>
												</td>
											</tr>
										<?
										}
									}
								?>
								</tbody>
							</table>
						</div><!-- col -->
					</div><!-- row -->
					<?
						//paginas
						if(class_exists('pagina') && sizeof($_system['pagina_tipo']))
						{
							
							$items = array(
								'cockpit' => 'Cockpit',
								'sidebar' => 'Sidebar',
								'insert' => 'Inserir',
								'update' => 'Editar',
								'delete' => 'Excluir',
								'publicar' => 'Publicar',
								'admin' => 'Gerenciar',
								'all' => 'Todas'
							);

							?>
							<div class="row full">
								<div class="large-12 columns">
									<h3>Tipos de página</h3>
									<p class="color medium font-14">As permissões desta seção dão acesso aos tipos específicos de página. Por padrão somente o tipo "Página" está definido.<br />Os demais tipos devem ser configurados no arquivo de defines.</p>
								</div>
							</div>
							<div class="row full">
								<div class="large-12 columns">
									<table>
										<thead>
											<th colspan="2"></th>
										</thead>
										<tbody>
											<?
												foreach($_system['pagina_tipo'] as $pagina_tipo)
												{
													?>
													<tr>
														<td style="width: 20%;"><span class="header no-margin inline" id="pagina-<?= $pagina_tipo['tipo'] ?>"><?= ucfirst($pagina_tipo['titulo']) ?></span></td>
														<td>
															<?
																foreach($items as $key => $value)
																{
																	$perm = perfilHasPermission($_GET['perfil'], $key, 'pagina-'.$pagina_tipo['tipo']);
																	?>
																	<span class="item <?= (($perm)?(""):("off")) ?>"><input type="checkbox" rel="pagina-<?= $pagina_tipo['tipo'] ?>" <?= $perm ? "checked" : "" ?> value="<?= $key ?>" name="permission[pagina-<?= $pagina_tipo['tipo'] ?>][<?= $key ?>]"> <span><?= $value ?></span></span>
																	<?
																}
															?>
														</td>
													</tr>
													<?
												}
											?>
										</tbody>
									</table>
								</div>
							</div>
							<?
						}

						//custom menus
						$cm = dboCustomMenus();
						if(sizeof($cm) && 1==2)
						{
							?>
							<div class="row full">
								<div class="large-12 columns">
									<h3>Menus custom</h3>
								</div><!-- col -->
							</div><!-- row -->
							<div class="row full">
								<div class="large-12 columns">
									<table>
										<thead>
											<th colspan="2"></th>
										</thead>
										<tbody>
											<tr>
											<?
												foreach($cm as $key => $item_cm)
												{
													$perm_cockpit = false;
													$perm_sidebar = false;
													$perm_cockpit = perfilHasPermission($_GET["perfil"], "cockpit", $item_cm->slug);
													$perm_sidebar = perfilHasPermission($_GET["perfil"], "sidebar", $item_cm->slug);
												?>
												<tr>
													<td style="width: 20%;"><span class="header no-margin inline" id="<?= $item_cm->slug ?>"><?= $item_cm->slug ?></span></td>
													<td>
														<span class="item <?= (($perm_cockpit)?(""):("off")) ?>"><input type="checkbox" rel="<?= $item_cm->slug ?>" <?= $perm_cockpit ? "CHECKED" : "" ?> value="cockpit" name="permission[<?= $item_cm->slug ?>][Cockpit]"> <span>Cockpit</span></span>
														<span class="item <?= (($perm_sidebar)?(""):("off")) ?>"><input type="checkbox" rel="<?= $item_cm->slug ?>" <?= $perm_sidebar ? "CHECKED" : "" ?> value="sidebar" name="permission[<?= $item_cm->slug ?>][Sidebar]"> <span>Sidebar</span></span>
													</td>
												</tr>
												<?
												}
											?>
											</tr>
										</tbody>
									</table>
								</div><!-- col -->
							</div><!-- row -->
							<?
						}

						$perms_raw = (array)($_system['module_permissoes_custom']);
						foreach($perms_raw as $value)
						{
							list($permissao, $ajuda) = explode(" | ", $value);
							$perms[$permissao] = $permissao;
							$perms_ajuda[$permissao] = $ajuda;
						}

						$obj = new dbo('permissao');
						$obj->loadAll('ORDER BY nome');
						if($obj->size())
						{
							do {
								$perms[$obj->nome] = $obj->nome;
								$perms_ajuda[$obj->nome] = $obj->ajuda;
							} while($obj->fetch());
						}
						?>
						<div class="row full">
							<div class="large-12 columns">
								<h3>Permissões custom</h3>
								<p class="color medium font-14">Permissões customizadas podem ser usadas cadastradas no sistema ou definidas por módulos específicos.<br />São usadas para diversos fins na lógica do sistema.</p>
							</div><!-- col -->
						</div><!-- row -->
						<div class="row full">
							<div class="large-12 columns">
								<table>
									<thead>
										<th colspan="2"></th>
									</thead>
									<tbody>
										<?
											if(is_array($perms))
											{
												sort($perms);
												foreach($perms as $key => $value)
												{
													$perm = false;
													$perm = perfilHasPermission($_GET["perfil"], $value);
													?>
													<tr>
														<td style="width: 20%"><span class="header inline no-margin" id="permissao-custom-<?= $value ?>"><?= $value ?></span></td>
														<td>
															<span class="item <?= ((perfilHasPermission($_GET['perfil'], $value))?(''):('off')) ?>"><input type="checkbox" rel="permissao-custom-<?= $value ?>" <?= $perm ? "CHECKED" : "" ?> value="<?= $value ?>" name="permission[<?= $value ?>]"></span>
															<span class="color medium font-12"><?= $perms_ajuda[$value] ?></span>
														</td>
													</tr>
													<?
												}
											}
										?>
									</tbody>
								</table>
							</div>
						</div><!-- row -->
				</div>
				<div class="row full">
					<div class="large-12 columns">
						<input type="submit" class="button radius" value="Atualizar Permissões">
					</div>
				</div><!-- row -->
				<input type="hidden" name="flag_update" value="<?= $_GET['perfil'] ?>">
				<?= CSRFInput() ?>
			</form>
		</div><!-- wrapper-permissions -->
		<?
	}

?>

<script>

	//serializejson
	!function(e){"use strict";e.fn.serializeJSON=function(n){var r,t,s,a,i,u,o;return u=e.serializeJSON,o=u.setupOpts(n),t=this.serializeArray(),u.readCheckboxUncheckedValues(t,this,o),r={},e.each(t,function(e,n){s=u.splitInputNameIntoKeysArray(n.name,o),a=s.pop(),"skip"!==a&&(i=u.parseValue(n.value,a,o),o.parseWithFunction&&"_"===a&&(i=o.parseWithFunction(i,n.name)),u.deepSet(r,s,i,o))}),r},e.serializeJSON={defaultOptions:{checkboxUncheckedValue:void 0,parseNumbers:!1,parseBooleans:!1,parseNulls:!1,parseAll:!1,parseWithFunction:null,customTypes:{},defaultTypes:{string:function(e){return String(e)},number:function(e){return Number(e)},"boolean":function(e){var n=["false","null","undefined","","0"];return-1===n.indexOf(e)},"null":function(e){var n=["false","null","undefined","","0"];return-1===n.indexOf(e)?e:null},array:function(e){return JSON.parse(e)},object:function(e){return JSON.parse(e)},auto:function(n){return e.serializeJSON.parseValue(n,null,{parseNumbers:!0,parseBooleans:!0,parseNulls:!0})}},useIntKeysAsArrayIndex:!1},setupOpts:function(n){var r,t,s,a,i,u;u=e.serializeJSON,null==n&&(n={}),s=u.defaultOptions||{},t=["checkboxUncheckedValue","parseNumbers","parseBooleans","parseNulls","parseAll","parseWithFunction","customTypes","defaultTypes","useIntKeysAsArrayIndex"];for(r in n)if(-1===t.indexOf(r))throw new Error("serializeJSON ERROR: invalid option '"+r+"'. Please use one of "+t.join(", "));return a=function(e){return n[e]!==!1&&""!==n[e]&&(n[e]||s[e])},i=a("parseAll"),{checkboxUncheckedValue:a("checkboxUncheckedValue"),parseNumbers:i||a("parseNumbers"),parseBooleans:i||a("parseBooleans"),parseNulls:i||a("parseNulls"),parseWithFunction:a("parseWithFunction"),typeFunctions:e.extend({},a("defaultTypes"),a("customTypes")),useIntKeysAsArrayIndex:a("useIntKeysAsArrayIndex")}},parseValue:function(n,r,t){var s,a;return a=e.serializeJSON,s=t.typeFunctions&&t.typeFunctions[r],s?s(n):t.parseNumbers&&a.isNumeric(n)?Number(n):!t.parseBooleans||"true"!==n&&"false"!==n?t.parseNulls&&"null"==n?null:n:"true"===n},isObject:function(e){return e===Object(e)},isUndefined:function(e){return void 0===e},isValidArrayIndex:function(e){return/^[0-9]+$/.test(String(e))},isNumeric:function(e){return e-parseFloat(e)>=0},optionKeys:function(e){if(Object.keys)return Object.keys(e);var n,r=[];for(n in e)r.push(n);return r},splitInputNameIntoKeysArray:function(n,r){var t,s,a,i,u;return u=e.serializeJSON,i=u.extractTypeFromInputName(n,r),s=i[0],a=i[1],t=s.split("["),t=e.map(t,function(e){return e.replace(/\]/g,"")}),""===t[0]&&t.shift(),t.push(a),t},extractTypeFromInputName:function(n,r){var t,s,a;if(t=n.match(/(.*):([^:]+)$/)){if(a=e.serializeJSON,s=a.optionKeys(r?r.typeFunctions:a.defaultOptions.defaultTypes),s.push("skip"),-1!==s.indexOf(t[2]))return[t[1],t[2]];throw new Error("serializeJSON ERROR: Invalid type "+t[2]+" found in input name '"+n+"', please use one of "+s.join(", "))}return[n,"_"]},deepSet:function(n,r,t,s){var a,i,u,o,l,c;if(null==s&&(s={}),c=e.serializeJSON,c.isUndefined(n))throw new Error("ArgumentError: param 'o' expected to be an object or array, found undefined");if(!r||0===r.length)throw new Error("ArgumentError: param 'keys' expected to be an array with least one element");a=r[0],1===r.length?""===a?n.push(t):n[a]=t:(i=r[1],""===a&&(o=n.length-1,l=n[o],a=c.isObject(l)&&(c.isUndefined(l[i])||r.length>2)?o:o+1),""===i?(c.isUndefined(n[a])||!e.isArray(n[a]))&&(n[a]=[]):s.useIntKeysAsArrayIndex&&c.isValidArrayIndex(i)?(c.isUndefined(n[a])||!e.isArray(n[a]))&&(n[a]=[]):(c.isUndefined(n[a])||!c.isObject(n[a]))&&(n[a]={}),u=r.slice(1),c.deepSet(n[a],u,t,s))},readCheckboxUncheckedValues:function(n,r,t){var s,a,i,u,o;null==t&&(t={}),o=e.serializeJSON,s="input[type=checkbox][name]:not(:checked):not([disabled])",a=r.find(s).add(r.filter(s)),a.each(function(r,s){i=e(s),u=i.attr("data-unchecked-value"),u?n.push({name:s.name,value:u}):o.isUndefined(t.checkboxUncheckedValue)||n.push({name:s.name,value:t.checkboxUncheckedValue})})}}}(window.jQuery||window.Zepto||window.$);

	$('.header').click(function(){
		var id = $(this).attr('id');
		$('input[rel='+id+']').each(function(){
			$(this).trigger('click');
		})
	})

	$(document).on('change', 'input[type="checkbox"]', function(){
		if($(this).is(':checked')){
			$(this).closest('span').removeClass('off');
		}
		else {
			$(this).closest('span').addClass('off');
		}
	});

	$(document).on('click', '.item span', function(){
		$(this).closest('.item').find('input[type="checkbox"]').trigger('click');
	});

	$(document).on('submit', 'form', function(){
		//usar peixePost() com o peixelaranja JSFW
		peixePost(
			$(this).attr('action'),
			$(this).serialize(),
			function(data) {
				setPeixeMessage("<div class='success'>Permissões atualizadas com sucesso!</div>");
				showPeixeMessage();
			}
		)
		return false;
	});

	$(document).on('change', '#copiar', function(){
		var mudado = $(this);
		if(mudado.val() > 0){
			peixeGet('dbo_permissions.php?perfil='+mudado.val(), function(d) {
				var html = $.parseHTML(d);
				/* item 1 */
				handler = '#wrapper-all-permissions';
				content = $(html).find(handler).html();
				if(typeof content != 'undefined'){
					$(handler).fadeHtml(content);
				}
			})
			return false;
		}
	});

	$(document).on('click', '.trigger-exportar-permissoes', function(){
		string = JSON.stringify($('#form-permissions').serializeJSON());
		console.log(string);
		var ans = prompt("Pressione CTRL + C para copiar as permissões", string);
	});

	$(document).on('click', '.trigger-importar-permissoes', function(){
		string = $('#form-permissions').serialize();
		var ans = prompt("Pressione CTRL + V para colar as permissões, em seguida pressione \"OK\"");
		if (ans!=null)
		{
			permissions = JSON.parse(ans);
			for(var prop in permissions) { 
				if (permissions.hasOwnProperty(prop)) {
					if(prop == 'permission'){
						first_level = permissions[prop];
						for(var macro in first_level) { 
							if (first_level.hasOwnProperty(macro)) {
								//prop, obj[prop]
								second_level = first_level[macro];
								if(typeof second_level == 'object'){
									for(var micro in second_level) {
										if (second_level.hasOwnProperty(micro)) {
											$('input[name="permission\\['+macro+'\\]\\['+second_level[micro]+'\\]"]').prop('checked', true).trigger('change');
										}
									}
								}
								else {
									$('input[name="permission\\['+second_level+'\\]"]').prop('checked', true).trigger('change');
								}
							}
						}
					}
				}
			}
		}
	});

</script>

<? require('footer.php') ?>