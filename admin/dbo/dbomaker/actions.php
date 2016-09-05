<?

require_once('lib/includes.php');
auth();

if($_GET['getDiskModules'])
{
	getDiskModules(array(
		'all_modules' => ($_GET['all_modules'] ? true : false),
	));
	showModules(array(
		'all_modules' => ($_GET['all_modules'] ? true : false),
	));
} 
 
elseif($_GET['showFields'])
{
	showFields($_GET['showFields']);
}

elseif($_GET['showModules'])
{
	showModules();
}

elseif($_GET['showField'])
{
	list($mod, $field) = explode("||", $_GET['showField']);
	showField($mod, $field);
}

elseif($_GET['showModule'])
{
	showModule($_GET['showModule']);
}

elseif($_GET['toggleFieldControl'])
{
	list($mod, $field, $attr) = explode("||", $_GET['toggleFieldControl']);
	toggleFieldControl($mod, $field, $attr);
}

elseif($_GET['toggleControl'])
{
	toggleControl($_GET['toggleControl']);
}

elseif($_GET['getFieldTypeDetail'])
{
	getFieldTypeDetail($_GET['getFieldTypeDetail']);
}

elseif($_GET['getFieldImageDetail'])
{
	getFieldImageDetail($_GET['getFieldImageDetail']);
}

elseif($_GET['getOptionsModuleFields'])
{
	getOptionsModuleFields($_GET['getOptionsModuleFields']);
}

elseif($_GET['getPluginDetail'])
{
	getPluginDetail($_GET['getPluginDetail']);
}

elseif($_GET['getNewFieldForm'])
{
	getNewFieldForm($_GET['getNewFieldForm']);
}

elseif($_POST['runUpdateField'])
{
	runUpdateField($_POST);
}

elseif($_POST['runUpdateModule'])
{
	runUpdateModule($_POST);
}

elseif($_POST['runNewField'])
{
	runNewField($_POST);
}

elseif(strlen($_GET['getModuleButtonForm']))
{
	getModuleButtonForm($_GET['getModuleButtonForm']);
}

elseif($_GET['runNewModule'])
{
	runNewModule();
}

elseif($_GET['syncAll'])
{
	syncAll();
}

elseif($_GET['syncUpdated'])
{
	syncUpdated();
}

elseif($_GET['syncModule'])
{
	syncModule($_GET['syncModule']);
}

elseif($_GET['sortModules'])
{
	sortModules($_GET);
}

elseif($_GET['sortFields'])
{
	sortFields($_GET);
}

elseif($_GET['deleteModule'])
{
	deleteModule($_GET['deleteModule']);
}

elseif($_GET['deleteField'])
{
	deleteField($_GET);
}

elseif($_GET['syncDatabase'])
{
	syncDatabase();
}




/* FUNCTIONS ===================================================================================================== */

if(!function_exists('safeArrayKey'))
{
	function safeArrayKey($key, $array)
	{
		if(@array_key_exists($key, $array))
		{
			return safeArrayKey($key+100, $array);
		}
		return $key;
	}
}

function getDiskModules ($params = array())
{
	extract($params);

	unset($_SESSION['dbomaker_modulos']);
	unset($_SESSION['dbomaker_deleted']);
	unset($_SESSION['dbomaker_updated']);

	//lendo todos os arquivos de definição
	$d = dir('..');
	while (false !== ($entry = $d->read())) {
		if(strpos($entry, "_dbo_") === 0)
		{
			$arq_modulos[] = $entry;
		}
	}
	$d->close();
	
	$module_keys = array();
	$module_keys_read_only = array();

	foreach($arq_modulos as $chave => $valor)
	{
		$arq = file_get_contents('../'.$valor);

		eval("?>".$arq."<?");

		//verificando se o módulo é importado da central de acessos

		$module->imported_module = strstr($arq, 'require(CENTRAL_DE_ACESSOS_PATH') ? true : false;

		$partes = array();
		$partes = explode("FUNÇÕES AUXILIARES", $arq);
		$partes = explode("// ----------------------------------------------------------------------------------------------------------\n", $partes['1']);

		$aux = substr($partes[1], 1, strlen($partes[1])-4);
		$aux = explode("\n", $aux);
		foreach($aux as $chave => $valor) { $aux[$chave] = substr($valor, 2, strlen($valor)-2); }
		$pre_insert = implode("\n", $aux);

		$aux = substr($partes[3], 1, strlen($partes[3])-4);
		$aux = explode("\n", $aux);
		foreach($aux as $chave => $valor) { $aux[$chave] = substr($valor, 2, strlen($valor)-2); }
		$pos_insert = implode("\n", $aux);

		$aux = substr($partes[5], 1, strlen($partes[5])-4);
		$aux = explode("\n", $aux);
		foreach($aux as $chave => $valor) { $aux[$chave] = substr($valor, 2, strlen($valor)-2); }
		$pre_update = implode("\n", $aux);

		$aux = substr($partes[7], 1, strlen($partes[7])-4);
		$aux = explode("\n", $aux);
		foreach($aux as $chave => $valor) { $aux[$chave] = substr($valor, 2, strlen($valor)-2); }
		$pos_update = implode("\n", $aux);

		$aux = substr($partes[9], 1, strlen($partes[9])-4);
		$aux = explode("\n", $aux);
		foreach($aux as $chave => $valor) { $aux[$chave] = substr($valor, 2, strlen($valor)-2); }
		$pre_delete = implode("\n", $aux);

		$aux = substr($partes[11], 1, strlen($partes[11])-4);
		$aux = explode("\n", $aux);
		foreach($aux as $chave => $valor) { $aux[$chave] = substr($valor, 2, strlen($valor)-2); }
		$pos_delete = implode("\n", $aux);

		$aux = substr($partes[13], 1, strlen($partes[13])-4);
		$aux = explode("\n", $aux);
		foreach($aux as $chave => $valor) { $aux[$chave] = substr($valor, 2, strlen($valor)-2); }
		$pre_list = implode("\n", $aux);

		$aux = substr($partes[15], 1, strlen($partes[15])-4);
		$aux = explode("\n", $aux);
		foreach($aux as $chave => $valor) { $aux[$chave] = substr($valor, 2, strlen($valor)-2); }
		$pos_list = implode("\n", $aux);

		$aux = substr($partes[17], 1, strlen($partes[17])-4);
		$aux = explode("\n", $aux);
		foreach($aux as $chave => $valor) { $aux[$chave] = substr($valor, 2, strlen($valor)-2); }
		$notifications = implode("\n", $aux);

		$aux = substr($partes[19], 1, strlen($partes[19])-4);
		$aux = explode("\n", $aux);
		foreach($aux as $chave => $valor) { $aux[$chave] = substr($valor, 2, strlen($valor)-2); }
		$overview = implode("\n", $aux);

		$module->pre_insert    = $pre_insert;
		$module->pos_insert    = $pos_insert;
		$module->pre_update    = $pre_update;
		$module->pos_update    = $pos_update;
		$module->pre_delete    = $pre_delete;
		$module->pos_delete    = $pos_delete;
		$module->pre_list      = $pre_list;
		$module->pos_list      = $pos_list;
		$module->notifications = $notifications;
		$module->overview      = $overview;

		if(!$module->dbo_maker_read_only || $all_modules)
		{
			$module_keys[safeArrayKey($module->order_by, $module_keys)] = $module;
		}
		else
		{
			$module_keys_read_only[safeArrayKey($module->order_by, $module_keys)] = $module;
		}

	}//foreach

	//ordering by menu order
	ksort($module_keys);
	ksort($module_keys_read_only);

	//and putting it in session.
	foreach($module_keys as $module)
	{
		$_SESSION['dbomaker_modulos'][$module->modulo] = $module;
	}
	foreach($module_keys_read_only as $module)
	{
		$_SESSION['dbomaker_modulos_read_only'][$module->modulo] = $module;
	}
}

function showModules ($params = array())
{
	extract($params);
	//mostrando os links para os modulos
	$count = 0;
	foreach($_SESSION['dbomaker_modulos'] as $nome_modulo => $modulo)
	{
		if($modulo->dbo_maker_read_only && !$all_modules) continue;

		$class = '';
		$module_file = '../_dbo_'.$modulo->modulo.'.php';
		if(file_exists($module_file))
		{
			if(!@fopen($module_file, 'r+'))
			{
				$class = 'locked';
			}
		}
	?>
		<a title='Order: <?= $count++ ?> <?= $modulo->imported_module ? '| Módulo integrado com a Central de Acessos, deve ser editado por lá.' : '' ?>' href='<?= $modulo->modulo ?>' class='sortable draggable <?= $class ?> module-<?= $modulo->modulo ?> module <?= $modulo->imported_module ? 'imported' : '' ?>' module='<?= $modulo->modulo ?>' id='module-<?= encNameAjax($modulo->modulo) ?>'><?= $modulo->titulo ?></a>
	<?
	}
	?><div class='new-module button-new' id="button-novo-modulo">Novo <u>M</u>ódulo</div><?
}

function showModule ($mod)
{
	$module = $_SESSION['dbomaker_modulos'][$mod];
	?>
		<div class='wrapper-module module-<?= $mod ?>'>
			<h1><?= $module->titulo ?></h1>

			<? getModuleForm($module) ?>

			<div id='menu-fields'>
				<h1>Campos</h1>
				<div class='anchor sortable sort-fields' module='<?= $mod ?>'>
				<? showFields($mod) ?>
				</div>
				<?php
					if(!$module->imported_module)
					{
						?>
						<div class='new-field button-new' rel='<?= $mod ?>' tabindex="0" id="button-new-field">Novo Campo (<u>d</u>)</div>
						<?php
					}
					else
					{
						?>
						<div class='button-new integrado'>Módulo integrado</div>
						<?php
					}
				?>
			</div>

			<?php
				if(!$module->imported_module)
				{
					?>
					<div class='button-next hidden button-salvar' rel='#form-module' sending='Enviando...' original_value='Salvar' id="button-salvar-modulo" tabindex="0"><span><u>S</u>alvar</span></div>
					<?php
				}
			?>

		</div>
	<?
}

function getModuleForm ($module)
{
	global $_SESSION;
	?>
		<form id='form-module' action='actions.php'>

			<div id='module-main' class='standard fieldset'>
				<h2 id='h2-module-basic-info' tabindex="0">Informações Básicas</h2>

				<div class='anchor' id='anchor-module-basic-info'>
					<div class='row'>
						<div class='item'>
							<label title="Nome amigável ao usuário do sistema.">Nome do Módulo</label>
							<div class='input'><input type='text' name='titulo' value="<?= (($module->titulo != '&nbsp;')?(htmlspecialchars($module->titulo)):('')) ?>"></div>
						</div>
					</div><!-- row -->

					<div class='row'>
						<div class='item'>
							<label>Ident. do Módulo</label>
							<div class='input'><input type='text' name='modulo' value="<?= (($module->modulo != 'temporary_module_key_5658')?(htmlspecialchars($module->modulo)):('')) ?>"></div>
						</div>
					</div><!-- row -->

					<div class='row'>
						<div class='item'>
							<label>Tabela no Banco</label>
							<div class='input'><input type='text' name='tabela' value="<?= htmlspecialchars($module->tabela) ?>"></div>
						</div>
					</div><!-- row -->

					<div class='row'>
						<div class='item'>
							<label>Nome no Plural</label>
							<div class='input'><input type='text' name='titulo_plural' value="<?= htmlspecialchars($module->titulo_plural) ?>"></div>
						</div>
					</div><!-- row -->

					<div class='row'>
						<div class='item'>
							<label>Itens por Página</label>
							<div class='input'><input type='text' name='paginacao' value="<?= htmlspecialchars($module->paginacao) ?>"></div>
						</div>
					</div><!-- row -->

					<div class='row'>
						<div class='item'>
							<label>Gênero</label>
							<div class='input'>
								<input type='radio' name='genero' value='o' <?= ($module->genero == 'o')?('CHECKED'):('') ?> id="genero-masc"> <label for="genero-masc">Masc.</label> &nbsp;&nbsp;&nbsp;
								<input type='radio' name='genero' value='a' <?= ($module->genero == 'a')?('CHECKED'):('') ?> id="genero-fem"> <label for="genero-fem">Fem.</label>
							</div>
						</div>
					</div><!-- row -->

				</div><!-- anchor -->
			</div><!-- module-main -->

			<div id='module-advanced' class='fieldset wide'>
				<h2 tabindex="0">Informações Avançadas</h2>
				<div class='anchor'>
					<div class='row standard'>
						<div class='item'>
							<label>Pode Inserir?</label>
							<div class='input'>
								<input type='radio' name='insert' value='1' <?= ($module->insert)?('CHECKED'):('') ?>> Sim &nbsp;&nbsp;&nbsp;
								<input type='radio' name='insert' value='0' <?= (!$module->insert)?('CHECKED'):('') ?>> Não
							</div>
						</div>
					</div><!-- row -->

					<div class='row standard'>
						<div class='item'>
							<label>Pode Alterar?</label>
							<div class='input'>
								<input type='radio' name='update' value='1' <?= ($module->update)?('CHECKED'):('') ?>> Sim &nbsp;&nbsp;&nbsp;
								<input type='radio' name='update' value='0' <?= (!$module->update)?('CHECKED'):('') ?>> Não
							</div>
						</div>
					</div><!-- row -->

					<div class='row standard'>
						<div class='item'>
							<label>Pode Excluir?</label>
							<div class='input'>
								<input type='radio' name='delete' value='1' <?= ($module->delete)?('CHECKED'):('') ?>> Sim &nbsp;&nbsp;&nbsp;
								<input type='radio' name='delete' value='0' <?= (!$module->delete)?('CHECKED'):('') ?>> Não
							</div>
						</div>
					</div><!-- row -->

					<div class='row standard'>
						<div class='item'>
							<label title="Pré-carregar o formulário de inserção junto com a listagem?">Pré-carregar Insert?</label>
							<div class='input'>
								<input type='radio' name='preload_insert_form' value='1' <?= (!isset($module->preload_insert_form) || $module->preload_insert_form === true)?('CHECKED'):('') ?>> Sim &nbsp;&nbsp;&nbsp;
								<input type='radio' name='preload_insert_form' value='0' <?= ($module->preload_insert_form === false)?('CHECKED'):('') ?>> Não
							</div>
						</div>
					</div><!-- row -->

					<div class='row standard'>
						<div class='item'>
							<label title="Retornar à listagem depois de uma inserção ou edição?">Retornar à listagem?</label>
							<div class='input'>
								<input type='radio' name='auto_view' value='1' <?= ($module->auto_view === true)?('CHECKED'):('') ?>> Sim &nbsp;&nbsp;&nbsp;
								<input type='radio' name='auto_view' value='0' <?= (!isset($module->auto_view) || $module->auto_view === false)?('CHECKED'):('') ?>> Não
							</div>
						</div>
					</div><!-- row -->

					<div class='row standard'>
						<div class='item'>
							<label title="Se marcado como 'sim', este módulo não irá aparecer no gerenciamento de permissões.">Ignorar permissões?</label>
							<div class='input'>
								<input type='radio' name='ignore_permissions' value='1' <?= ($module->ignore_permissions === true)?('CHECKED'):('') ?>> Sim &nbsp;&nbsp;&nbsp;
								<input type='radio' name='ignore_permissions' value='0' <?= (!isset($module->ignore_permissions) || $module->ignore_permissions === false)?('CHECKED'):('') ?>> Não
							</div>
						</div>
					</div><!-- row -->

					<div class='row standard'>
						<div class='item'>
							<label title="Ícone que o módulo utiliza, da FontAwesome. Se não for setado, tenta achar uma imagem PNG na pasta, correspondente ao módulo.">Ícone do módulo</label>
							<div class='input'>
								<input type='text' name='module_icon' value="<?= htmlspecialchars($module->module_icon) ?>"/>
							</div>
						</div>
					</div><!-- row -->

					<div class='row standard'>
						<div class='item'>
							<label title="Texto que será mostrado no botão de inserção do módulo.">Botão de inserção</label>
							<div class='input'>
								<input type='text' name='insert_button_text' value="<?= htmlspecialchars($module->insert_button_text) ?>"/>
							</div>
						</div>
					</div><!-- row -->

					<div class='row standard'>
						<div class='item'>
							<label title="Título que será mostrado no Big Button.">Titulo Big Button</label>
							<div class='input'>
								<input type='text' name='titulo_big_button' value="<?= htmlspecialchars($module->titulo_big_button) ?>"/>
							</div>
						</div>
					</div><!-- row -->

					<div class='row standard'>
						<div class='item'>
							<label title="Título que será mostrado na listagem. Se omitido, gera um título automárico.">Titulo da Listagem</label>
							<div class='input'>
								<input type='text' name='titulo_listagem' value="<?= htmlspecialchars($module->titulo_listagem) ?>"/>
							</div>
						</div>
					</div><!-- row -->

					<div class='row standard'>
						<div class='item'>
							<label title="Classes CSS que serão aplicadas na listagem deste módulo.">Classes CSS da list.</label>
							<div class='input'>
								<input type='text' name='classes_listagem' value="<?= htmlspecialchars($module->classes_listagem) ?>"/>
							</div>
						</div>
					</div><!-- row -->

					<div class='row standard'>
						<div class='item'>
							<label title="Permissões custom que serão criadas para este módulo. Uma por linha separando a descriçao com pipe.">Permissões custom</label>
							<div class='input'>
								<?
									if($module->permissoes_custom)
									{
										$permissoes_custom = trim($module->permissoes_custom);
										$partes = explode("\n", $permissoes_custom);

										$partes_trimmed = array();
										$count = 1;
										foreach($partes as $chave => $valor)
										{
											if($count != 1) {
												$partes_trimmed[] = substr($valor, 1, strlen($valor)-1);
											} else {
												$partes_trimmed[] = $valor;
											}
											$count++;
										}
										$permissoes_custom = implode("\n", $partes_trimmed);
									}
								?>
								<textarea name='permissoes_custom' rows='1' class='code'><?= $permissoes_custom ?></textarea>
							</div>
						</div>
					</div><!-- row -->

					<div class='row standard'>
						<div class='item'>
							<label title="Bibliotecas JS necessárias para este módulo.">Bibliotecas JS</label>
							<div class='input'>
								<?
									if($module->bibliotecas_js)
									{
										$bibliotecas_js = trim($module->bibliotecas_js);
										$partes = explode("\n", $bibliotecas_js);

										$partes_trimmed = array();
										$count = 1;
										foreach($partes as $chave => $valor)
										{
											if($count != 1) {
												$partes_trimmed[] = substr($valor, 1, strlen($valor)-1);
											} else {
												$partes_trimmed[] = $valor;
											}
											$count++;
										}
										$bibliotecas_js = implode("\n", $partes_trimmed);
									}
								?>
								<textarea name='bibliotecas_js' rows='1' class='code'><?= $bibliotecas_js ?></textarea>
							</div>
						</div>
					</div>

					<div class='row standard'>
						<div class='item'>
							<label title="Quando setado, este valor irá sobrepor o valor padrão da ordenação deste módulo no cockpit. Para colocar no final de tudo, usar acima de 2000, antes de tudo, abaixo de 2000.">Force Order By</label>
							<div class='input'>
								<input type='text' name='force_order_by' value="<?= htmlspecialchars($module->force_order_by) ?>" style="max-width: 100px;"/>
							</div>
						</div>
					</div><!-- row -->

					<div class='row standard'>
						<div class='item'>
							<label title="Engine da tabela no MySQL">Engine da tabela</label>
							<div class='input'>
								<select name="table_engine" style="max-width: 100px;">
									<option <?= $module->table_engine == 'InnoDB' || $module->modulo == 'temporary_module_key_5658' ? 'selected' : '' ?>>InnoDB</option>
									<option <?= $module->table_engine == 'MyISAM' || (!isset($module->table_engine) && $module->modulo != 'temporary_module_key_5658') ? 'selected' : '' ?>>MyISAM</option>
								</select>
							</div>
						</div>
					</div>

					<div class='row'>
						<div class='item'>
							<label title="Salve a restrição em uma variável $rest">Restrição do Módulo</label>
							<div class='input'>
								<?
									if($module->restricao)
									{
										$restricao = trim($module->restricao);
										$partes = explode("\n", $restricao);

										$partes_trimmed = array();
										$count = 1;
										foreach($partes as $chave => $valor)
										{
											if($count != 1) {
												$partes_trimmed[] = substr($valor, 1, strlen($valor)-1);
											} else {
												$partes_trimmed[] = $valor;
											}
											$count++;
										}
										$restricao = implode("\n", $partes_trimmed);
									}
								?>
								<textarea name='restricao' rows='1' class='code'><?= $restricao ?></textarea>
							</div><!-- input -->
						</div><!-- item -->
					</div><!-- row -->
				</div><!-- anchor -->
			</div><!-- module-main -->

			<div id='module-triggers' class='wide fieldset monospace'>
				<h2 tabindex="0">Triggers</h2>

				<div class='anchor'>
					<div class='row'>
						<div class='item'>
							<label>Pré-Insert</label>
							<div class='input'><textarea name='pre_insert' rows='1' class='code'><?= htmlspecialchars($module->pre_insert) ?></textarea></div>
						</div>
					</div><!-- row -->

					<div class='row'>
						<div class='item'>
							<label>Pós-Insert</label>
							<div class='input'><textarea name='pos_insert' rows='1' class='code'><?= htmlspecialchars($module->pos_insert) ?></textarea></div>
						</div>
					</div><!-- row -->

					<div class='row'>
						<div class='item'>
							<label>Pré-Update</label>
							<div class='input'><textarea name='pre_update' rows='1' class='code'><?= htmlspecialchars($module->pre_update) ?></textarea></div>
						</div>
					</div><!-- row -->

					<div class='row'>
						<div class='item'>
							<label>Pós-Update</label>
							<div class='input'><textarea name='pos_update' rows='1' class='code'><?= htmlspecialchars($module->pos_update) ?></textarea></div>
						</div>
					</div><!-- row -->

					<div class='row'>
						<div class='item'>
							<label>Pré-Delete</label>
							<div class='input'><textarea name='pre_delete' rows='1' class='code'><?= htmlspecialchars($module->pre_delete) ?></textarea></div>
						</div>
					</div><!-- row -->

					<div class='row'>
						<div class='item'>
							<label>Pós-Delete</label>
							<div class='input'><textarea name='pos_delete' rows='1' class='code'><?= htmlspecialchars($module->pos_delete) ?></textarea></div>
						</div>
					</div><!-- row -->

					<div class='row'>
						<div class='item'>
							<label>Pré-List</label>
							<div class='input'><textarea name='pre_list' rows='1' class='code'><?= htmlspecialchars($module->pre_list) ?></textarea></div>
						</div>
					</div><!-- row -->

					<div class='row'>
						<div class='item'>
							<label>Pós-List</label>
							<div class='input'><textarea name='pos_list' rows='1' class='code'><?= htmlspecialchars($module->pos_list) ?></textarea></div>
						</div>
					</div><!-- row -->
				</div><!-- anchor -->

			</div><!-- module-triggers -->

			<div id='module-functions' class='wide fieldset monospace'>
				<h2 tabindex="0">Funções</h2>

				<div class='anchor'>
					<div class='row'>
						<div class='item'>
							<label>Notificações</label>
							<div class='input'><textarea name='notifications' rows='1' class='code'><?= htmlspecialchars($module->notifications) ?></textarea></div>
						</div>
					</div><!-- row -->

					<div class='row'>
						<div class='item'>
							<label>Visão Geral</label>
							<div class='input'><textarea name='overview' rows='1' class='code'><?= htmlspecialchars($module->overview) ?></textarea></div>
						</div>
					</div><!-- row -->

				</div><!-- anchor -->

			</div><!-- module-functions -->

			<div id='module-buttons' class='wide fieldset'>
				<h2 tabindex="0">Botões</h2>

				<div class='anchor'>
					<div class='row'>
						<div class='item' style='padding-top: 20px;'>
							<div class='wrapper-module-buttons'>
								<a href='#' class='new-module-button button'>Novo Botão</a>
								<?
									if(is_array($module->button))
									{
										foreach($module->button as $key => $button)
										{
											echo "<div id='module-button-".$key."' class='wrapper-module-button' posicao='".$key."'>\n";
											?>
											<div class='standard-type' <?= (($button->custom)?('style="display: none;"'):('')) ?>>

												<div class='row standard'>
													<div class='item'>
														<label>Nome do Botão</label>
														<div class='input'><input type='text' name='button[<?= $key ?>][value]' value="<?= $button->value ?>"/></div>
													</div><!-- item -->
												</div><!-- row -->

												<div class='row standard'>
													<div class='item'>
														<label>Módulo</label>
														<div class='input'><input type='text' name='button[<?= $key ?>][modulo]' value="<?= $button->modulo ?>"/></div>
													</div><!-- item -->
												</div><!-- row -->

												<div class='row standard'>
													<div class='item'>
														<label>Chave Extrangeira</label>
														<div class='input'><input type='text' name='button[<?= $key ?>][modulo_fk]' value="<?= $button->modulo_fk ?>"/></div>
													</div><!-- item -->
												</div><!-- row -->

												<div class='row standard'>
													<div class='item'>
														<label>Chave (módulo atual)</label>
														<div class='input'><input type='text' name='button[<?= $key ?>][key]' value="<?= $button->key ?>"/></div>
													</div><!-- item -->
												</div><!-- row -->

												<div class='row standard' style="display: none;">
													<div class='item'>
														<label>View Recursiva</label>
														<div class='input'>
															<select name='button[<?= $key ?>][view]'>
																<option value='1' <?= (($button->view)?('SELECTED'):('')) ?>>Sim</option>
																<option value='0' <?= ((!$button->view)?('SELECTED'):('')) ?>>Não</option>
															</select>
														</div>
													</div><!-- item -->
												</div><!-- row -->

												<div class='row standard'>
													<div class='item'>
														<label>Exibir botão</label>
														<div class='input'>
															<select name='button[<?= $key ?>][show]'>
																<option value='1' <?= (($button->show)?('SELECTED'):('')) ?>>Sim</option>
																<option value='0' <?= ((!$button->show)?('SELECTED'):('')) ?>>Não</option>
															</select>
														</div>
													</div><!-- item -->
												</div><!-- row -->

												<div class='row standard'>
													<div class='item'>
														<label>Subseção</label>
														<div class='input'>
															<select name='button[<?= $key ?>][subsection]'>
																<option value='1' <?= (($button->subsection)?('SELECTED'):('')) ?>>Sim</option>
																<option value='0' <?= ((!$button->subsection)?('SELECTED'):('')) ?>>Não</option>
															</select>
														</div>
													</div><!-- item -->
												</div><!-- row -->

												<div class='row standard'>
													<div class='item'>
														<label>Auto Load</label>
														<div class='input'>
															<select name='button[<?= $key ?>][autoload]'>
																<option value='1' <?= (($button->autoload)?('SELECTED'):('')) ?>>Sim</option>
																<option value='0' <?= ((!$button->autoload)?('SELECTED'):('')) ?>>Não</option>
															</select>
														</div>
													</div><!-- item -->
												</div><!-- row -->

												<div class='row'>
													<div class='item' style='text-align: right; padding-top: 3px;'>
														<a href='' class='button'>Padrão</a> <a href='' class='button-inactive toggle-module-button-view'>Custom</a> <a href='' class='button-inactive remove-button' title='Remover Botão'>x</a>
													</div><!-- item -->
												</div><!-- row -->
											</div>

											<div class='custom-type' <?= (($button->custom)?(''):('style="display: none;"')) ?>>
												<div class='row standard'>
													<div class='item'>
														<label>Nome do Botão</label>
														<div class='input'><input type='text' name='button[<?= $key ?>][value_custom]' value="<?= $button->value ?>"/></div>
													</div><!-- item -->
												</div><!-- row -->

												<div class='row'>
													<div class='item'>
														<label>Código do Botão ($code)</label>
														<div class='input'>
															<textarea name='button[<?= $key ?>][code]' class='code'><?= unident($button->code) ?></textarea>
														</div>
													</div><!-- item -->
												</div><!-- row -->

												<div class='row'>
													<div class='item' style='text-align: right; padding-top: 3px;'>
														<a href='' class='button-inactive toggle-module-button-view'>Padrão</a> <a href='' class='button'>Custom</a> <a href='' class='button-inactive remove-button' title='Remover Botão'>x</a>
													</div><!-- item -->
												</div><!-- row -->
											</div>

											<input type='hidden' name='button[<?= $key ?>][custom]' class='custom-flag' value="<?= (($button->custom)?(1):(0)) ?>"/>
											<?
											echo "</div><!-- module-button-".$key." -->\n\n";
										} //foreach
									} //if is array
								?>
							</div><!-- wrapper-module-buttons -->
						</div><!-- item -->
					</div><!-- row -->
				</div><!-- anchor -->

			</div><!-- module-buttons -->

			<div id='module-grid' class='wide fieldset'>
				<h2 tabindex="0">Grade de Exibição</h2>

				<div class='anchor'>
					<div class='row'>
						<div class='item'>
							<label>Grade Geral</label>
							<div class='input'>
								<textarea name='grid_general' rows='1' class='code'><?
									$grade = array();
									if(is_array($module->grid))
									{
										foreach($module->grid as $key => $linha)
										{
											if(is_numeric($key))
											{
												$grade[] = implode(",", $linha);
											}
										}
										echo implode("\n", $grade);
									}
								?></textarea>
							</div>
						</div>
					</div><!-- row -->
					<div class='row'>
						<div class='item'>
							<label>Grade de Visualização</label>
							<div class='input'>
								<textarea name='grid_general_view' rows='1' class='code'><?
									$grade = array();
									if(is_array($module->grid))
									{
										foreach($module->grid as $key => $view)
										{
											if(!is_numeric($key) && $key == 'view')
											{
												foreach($view as $linha)
												{
													$grade[] = implode(",", $linha);
												}
											}
										}
									}
									echo implode("\n", $grade);
								?></textarea>
							</div>
						</div>
					</div><!-- row -->
				</div><!-- anchor -->

			</div><!-- module-grid -->

			<input type='hidden' name='runUpdateModule' value='1'/>
			<input type='hidden' name='active_module' value='<?= $module->modulo ?>'/>

		</form>
	<?
}

function showFields ($mod)
{
	global $_SESSION;
	$campos = $_SESSION['dbomaker_modulos'][$mod];

	if(is_array($campos->campo))
	{
		foreach($campos->campo as $chave_campo => $campo)
		{
			showFieldControls($mod, $chave_campo);
		}
	}
}

function showFieldControls ($mod, $field)
{
	global $_SESSION;
	$campo = $_SESSION['dbomaker_modulos'][$mod]->campo[$field];
	?>
		<a href='<?= $mod ?>||<?= $campo->coluna ?>' class='field-<?= $field ?> draggable field' id='field-<?= encNameAjax($field) ?>' module='<?= $mod ?>' field='<?= $field ?>'>
			<?= htmlSpecialChars($campo->titulo) ?>
			<span class='wrapper-controls'>
				<ul class='controls'>
					<? showFieldControl($mod, $field, 'valida') ?>
					<? showFieldControl($mod, $field, 'add') ?>
					<? showFieldControl($mod, $field, 'edit') ?>
					<? showFieldControl($mod, $field, 'view') ?>
					<? showFieldControl($mod, $field, 'lista') ?>
					<? showFieldControl($mod, $field, 'order') ?>
					<? showFieldControl($mod, $field, 'filter') ?>
				</ul>
			</span>
		</a>
	<?
}

function showFieldControl ($mod, $field, $attr)
{
	global $_SESSION;
	$campo = $_SESSION['dbomaker_modulos'][$mod]->campo[$field];
	if($attr == 'valida') {
		?><li rel='valida' title='Campo Obrigatório? (<?= ($campo->valida)?('sim'):('não') ?>)' class='valida <?= ($campo->valida)?('active'):('') ?>'></li><?
	} elseif($attr == 'add') {
		?><li rel='add' title='Visto no Formulário de Inserção? (<?= ($campo->add)?('sim'):('não') ?>)' class='add <?= ($campo->add)?('active'):('') ?>'></li><?
	} elseif($attr == 'edit') {
		?><li rel='edit' title='Visto no Formulário de Edição? (<?= ($campo->edit)?('sim'):('não') ?>)' class='edit <?= ($campo->edit)?('active'):('') ?>'></li><?
	} elseif($attr == 'lista') {
		?><li rel='lista' title='Visto na Listagem? (<?= ($campo->lista)?('sim'):('não') ?>)' class='lista <?= ($campo->lista)?('active'):('') ?>'></li><?
	} elseif($attr == 'view') {
		?><li rel='view' title='Visto na Visualização? (<?= ($campo->view)?('sim'):('não') ?>)' class='view <?= ($campo->view)?('active'):('') ?>'></li><?
	} elseif($attr == 'filter') {
		?><li rel='filter' title='Pode ser Filtrado? (<?= ($campo->filter)?('sim'):('não') ?>)' class='filter <?= ($campo->filter)?('active'):('') ?>'></li><?
	} elseif($attr == 'order') {
		?><li rel='order' title='Pode ser Ordenado? (<?= ($campo->order)?('sim'):('não') ?>)' class='order <?= ($campo->order)?('active'):('') ?>'></li><?
	}
}

function toggleControl ($control)
{
	global $_SESSION;
	if($_SESSION['dbomaker_controls'][$control] == TRUE)
	{
		$_SESSION['dbomaker_controls'][$control] = FALSE;
	} else {
		$_SESSION['dbomaker_controls'][$control] = TRUE;
	}
}

function toggleFieldControl ($mod, $field, $attr)
{
	$campo = &$_SESSION['dbomaker_modulos'][$mod]->campo[$field];
	if($campo->{$attr} == TRUE) { $campo->{$attr} = FALSE; } else { $campo->{$attr} = TRUE; }
	if($campo->lista == FALSE)
	{
		$campo->order = FALSE;
	}
	flagUpdate($mod);
	?>
		<? showFieldControl($mod, $field, 'valida') ?>
		<? showFieldControl($mod, $field, 'add') ?>
		<? showFieldControl($mod, $field, 'edit') ?>
		<? showFieldControl($mod, $field, 'view') ?>
		<? showFieldControl($mod, $field, 'lista') ?>
		<? showFieldControl($mod, $field, 'order') ?>
		<? showFieldControl($mod, $field, 'filter') ?>
	<?
}

function showField ($mod,$field)
{

	global $_SESSION;
	$campo = $_SESSION['dbomaker_modulos'][$mod]->campo[$field];
	?>

	<div class='wrapper-field module-<?= $mod ?> field-<?= $field ?>'>
		<h1><?= htmlSpecialChars($campo->titulo) ?></h1>

		<? getFieldForm($mod,$field) ?>

	</div>
	<?
}

function getFieldForm ($mod,$field)
{
	$campo = $_SESSION['dbomaker_modulos'][$mod]->campo[$field];
	?>
	<form id='form-field' action='actions.php'>

		<div id='field-main' class='standard fieldset'>
			<h2 class="<?= ($_SESSION['dbomaker_controls']['show_field_basic'])?('active'):('') ?> toggle-control" rel='show_field_basic' tabindex="0">Informações Básicas</h2>

			<div class='anchor' style='<?= ($_SESSION['dbomaker_controls']['show_field_basic'])?('display: block;'):('') ?>'>
				<div class='row'>
					<div class='item'>
						<label>Nome do Campo</label>
						<div class='input'><input type='text' name='titulo' value="<?= (($campo->titulo == '&nbsp;')?(''):(htmlspecialchars($campo->titulo))) ?>"></div>
					</div>
				</div><!-- row -->

				<div class='row'>
					<div class='item'>
						<label>Coluna na Tabela</label>
						<div class='input'><input type='text' name='coluna' value="<?= (($campo->coluna == 'temporary_field_key_5658')?(''):(htmlspecialchars($campo->coluna))) ?>"></div>
					</div>
				</div><!-- row -->

				<div class='row'>
					<div class='item'>
						<label>Controles</label>
						<div class='input'>
							<span class='wrapper-controls'>
								<ul class='controls' rel='<?= $mod."||".$field ?>'>
									<? showFieldControl($mod, $field, 'valida') ?>
									<? showFieldControl($mod, $field, 'add') ?>
									<? showFieldControl($mod, $field, 'edit') ?>
									<? showFieldControl($mod, $field, 'view') ?>
									<? showFieldControl($mod, $field, 'lista') ?>
									<? showFieldControl($mod, $field, 'order') ?>
									<? showFieldControl($mod, $field, 'filter') ?>
								</ul>
							</span>
						</div>
					</div>
				</div><!-- row -->
			</div><!-- anchor -->
		</div><!-- field-main -->

		<div id='field-functions' class='standard fieldset'>
			<h2 class='<?= ($_SESSION['dbomaker_controls']['show_field_advanced'])?('active'):('') ?> toggle-control' rel='show_field_advanced' tabindex="0">Informações Avançadas</h2>

			<div class='anchor' style='<?= ($_SESSION['dbomaker_controls']['show_field_advanced'])?('display: block;'):('') ?>'>
				<div class='row'>
					<div class='item'>
						<label title="Como o label deste element é exibido no formulário">Visibilidade do label</label>

						<div class='input'>
							<select name='label_display' style='width: 49%;'>
								<option value='' <?= ($campo->label_display == '')?('SELECTED'):('') ?>>Visível</option>
								<option value='transparent' <?= ($campo->label_display == 'transparent')?('SELECTED'):('') ?>>Transparente</option>
								<option value='hidden' <?= ($campo->label_display == 'hidden')?('SELECTED'):('') ?>>Invisível</option>
							</select>
						</div>
					</div>
				</div><!-- row -->

				<div class='row'>
					<div class='item'>
						<label title="Título mostrado na coluna da listagem. Se omitido, o nome do campo será mostrado.">Título da Listagem</label>
						<div class='input'><input type='text' name='titulo_listagem' value="<?= htmlspecialchars($campo->titulo_listagem) ?>"></div>
					</div>
				</div><!-- row -->

				<div class='row'>
					<div class='item'>
						<label title="Dica que será exibida ao usuário no momento do cadastro.">Dica</label>
						<div class='input'><input type='text' name='dica' value="<?= htmlspecialchars($campo->dica) ?>"></div>
					</div>
				</div><!-- row -->

				<div class='row'>
					<div class='item'>
						<label title="Tipo de dado no MYSQL, para o script que irá gerar a tabela.">Type</label>

						<?

							list($type,$mysql_size) = explode("(", $campo->type);
							$mysql_size = str_replace(')', '', $mysql_size);

						?>

						<div class='input'>
							<select name='type' style='width: 49%;'>
								<option value='VARCHAR' <?= ($type == 'VARCHAR')?('SELECTED'):('') ?>>VARCHAR</option>
								<option value='TINYINT' <?= ($type == 'TINYINT')?('SELECTED'):('') ?>>TINYINT</option>
								<option value='TEXT' <?= ($type == 'TEXT')?('SELECTED'):('') ?>>TEXT</option>
								<option value='DATE' <?= ($type == 'DATE')?('SELECTED'):('') ?>>DATE</option>
								<option value='SMALLINT' <?= ($type == 'SMALLINT')?('SELECTED'):('') ?>>SMALLINT</option>
								<option value='MEDIUMINT' <?= ($type == 'MEDIUMINT')?('SELECTED'):('') ?>>MEDIUMINT</option>
								<option value='INT' <?= ($type == 'INT')?('SELECTED'):('') ?>>INT</option>
								<option value='BIGINT' <?= ($type == 'BIGINT')?('SELECTED'):('') ?>>BIGINT</option>
								<option value='FLOAT' <?= ($type == 'FLOAT')?('SELECTED'):('') ?>>FLOAT</option>
								<option value='DOUBLE' <?= ($type == 'DOUBLE')?('SELECTED'):('') ?>>DOUBLE</option>
								<option value='DECIMAL' <?= ($type == 'DECIMAL')?('SELECTED'):('') ?>>DECIMAL</option>
								<option value='DATETIME' <?= ($type == 'DATETIME')?('SELECTED'):('') ?>>DATETIME</option>
								<option value='TIMESTAMP' <?= ($type == 'TIMESTAMP')?('SELECTED'):('') ?>>TIMESTAMP</option>
								<option value='TIME' <?= ($type == 'TIME')?('SELECTED'):('') ?>>TIME</option>
								<option value='YEAR' <?= ($type == 'YEAR')?('SELECTED'):('') ?>>YEAR</option>
								<option value='CHAR' <?= ($type == 'CHAR')?('SELECTED'):('') ?>>CHAR</option>
								<option value='TINYBLOB' <?= ($type == 'TINYBLOB')?('SELECTED'):('') ?>>TINYBLOB</option>
								<option value='TINYTEXT' <?= ($type == 'TINYTEXT')?('SELECTED'):('') ?>>TINYTEXT</option>
								<option value='BLOB' <?= ($type == 'BLOB')?('SELECTED'):('') ?>>BLOB</option>
								<option value='MEDIUMBLOB' <?= ($type == 'MEDIUMBLOB')?('SELECTED'):('') ?>>MEDIUMBLOB</option>
								<option value='MEDIUMTEXT' <?= ($type == 'MEDIUMTEXT')?('SELECTED'):('') ?>>MEDIUMTEXT</option>
								<option value='LONGBLOB' <?= ($type == 'LONGBLOB')?('SELECTED'):('') ?>>LONGBLOB</option>
								<option value='LONGTEXT' <?= ($type == 'LONGTEXT')?('SELECTED'):('') ?>>LONGTEXT</option>
								<option value='ENUM' <?= ($type == 'ENUM')?('SELECTED'):('') ?>>ENUM</option>
								<option value='SET' <?= ($type == 'SET')?('SELECTED'):('') ?>>SET</option>
								<option value='BOOL' <?= ($type == 'BOOL')?('SELECTED'):('') ?>>BOOL</option>
								<option value='BINARY' <?= ($type == 'BINARY')?('SELECTED'):('') ?>>BINARY</option>
								<option value='VARBINARY' <?= ($type == 'VARBINARY')?('SELECTED'):('') ?>>VARBINARY</option>
							</select>

							<input name='mysql_size' style='width: 50%;' type='text' value='<?= $mysql_size ?>'>
						</div>
					</div>
				</div><!-- row -->

				<div class='row'>
					<div class='item'>
						<label>Null</label>
						<div class='input'>
							<input type='radio' name='isnull' value="1" <?= ($campo->isnull)?('CHECKED'):('') ?>/>Sim &nbsp;&nbsp;&nbsp;
							<input type='radio' name='isnull' value="0" <?= (!$campo->isnull)?('CHECKED'):('') ?>/>Não
						</div>
					</div>
				</div><!-- row -->

				<div class='row'>
					<div class='item'>
						<label title="Selecione para definir esse campo como chave primária do módulo atual.">Chave Primária</label>
						<div class='input'>
							<select name='pk'>
								<option value='1' <?= ($campo->pk)?('SELECTED'):('') ?>>Sim</option>
								<option value='0' <?= (!$campo->pk)?('SELECTED'):('') ?>>Não</option>
							</select>
						</div>
					</div>
				</div><!-- row -->

				<div class='row'>
					<div class='item'>
						<label title="Selecione uma opção para definir esse campo como o padrão de ordenação inicial da listagem. Ascendente, ou Descendente.">Padrão de Ordenação</label>
						<div class='input'>
							<select name='default'>
								<option value='-1'></option>
								<option value='ASC' <?= ($campo->default == 'ASC')?('SELECTED'):('') ?>>Crescente</option>
								<option value='DESC' <?= ($campo->default == 'DESC')?('SELECTED'):('') ?>>Decrescente</option>
							</select>
						</div>
					</div>
				</div><!-- row -->

				<?
				if(HAS_PROFILES)
				{
				?>
				<div class='row'>
					<div class='item'>
						<label title="Perfis pré-existentes no sistema que podem ter acesso ao campo.">Perfis com acesso ao campo</label>
						<div class='input'>
							<div class='perfil_todos' style='float: left;'>
								<input type='checkbox' name='perfil_todos' value='1' <?= (!$campo->perfil)?('CHECKED'):('') ?>> Todos
							</div>
							<div class='perfil' style='float: left; border-left: 1px solid #CCC; padding-left: 10px; margin-left: 10px; '>
							<?
							$sql = "SELECT * FROM perfil ORDER BY nome";
							$res = mysql_query($sql);
							while($lin = mysql_fetch_object($res)) { ?>
								<input type='checkbox' class='campo_perfil' name='perfil[]' value='<?= $lin->nome ?>' <?= (@in_array($lin->nome, $campo->perfil))?("CHECKED"):("") ?>> <?= $lin->nome ?><br>
							<? } ?>
							</div>
						</div>
						<div class='clear'></div>
					</div>
				</div><!-- row -->
				<?
				}
				?>

				<div class='row'>
					<div class='item'>
						<label title="Define o tipo de interação do campo.">Interação do campo</label>
						<div class='input'>
							<input type="radio" name="interaction" value="readonly" <?= (($campo->interaction == 'readonly')?('checked'):('')) ?>/>Read only &nbsp;&nbsp;&nbsp;
							<input type="radio" name="interaction" value="insertonly" <?= (($campo->interaction == 'insertonly')?('checked'):('')) ?>/>Insert only &nbsp;&nbsp;&nbsp;
							<input type="radio" name="interaction" value="updateonly" <?= (($campo->interaction == 'updateonly')?('checked'):('')) ?>/>Update only &nbsp;&nbsp;&nbsp;
							<input type="radio" name="interaction" value="" <?= (($campo->interaction == '')?('checked'):('')) ?>/>Default
						</div>
					</div>
				</div><!-- row -->

				<div class='row'>
					<div class='item'>
						<label title="Função usada para mostrar o dado na listagem. Recebe os parâmetros $obj e $column.">Função de Listagem</label>
						<div class='input'><input type='text' name='list_function' value="<?= htmlspecialchars($campo->list_function) ?>"></div>
					</div>
				</div><!-- row -->

				<div class='row'>
					<div class='item'>
						<label title="Função usada para mostrar o dado no campo de edição. Recebe os parâmetros $obj e $column.">Função de Edição</label>
						<div class='input'><input type='text' name='edit_function' value="<?= htmlspecialchars($campo->edit_function) ?>"></div>
					</div>
				</div><!-- row -->

				<div class='row'>
					<div class='item'>
						<label title="Valor padrão na inserção do dado, caso não seja digitado pelo usuário.">Valor Padrão na Inserção</label>
						<div class='input'><input type='text' name='default_value' value="<?= htmlspecialchars($campo->default_value) ?>"></div>
					</div>
				</div><!-- row -->

				<div class='row'>
					<div class='item'>
						<label title="Caracteres coringa:9 = qualquer número; a = qualquer letra; * = qualquer número ou letra.">Máscara</label>
						<div class='input'><input type='text' name='mask' value="<?= htmlspecialchars($campo->mask) ?>"></div>
					</div>
				</div><!-- row -->

				<div class='row'>
					<div class='item'>
						<label title="Classes que serão exibidas na criação do formulário. Separadas por espaço.">Classes</label>
						<div class='input'><input type='text' name='classes' value="<?= htmlspecialchars($campo->classes) ?>"></div>
					</div>
				</div><!-- row -->

				<div class='row'>
					<div class='item'>
						<label title="Estilos que serão exibidas na criação do formulário. Exatamente como declarado dentro do atributo.">Styles</label>
						<div class='input'><input type='text' name='styles' value="<?= htmlspecialchars($campo->styles) ?>"></div>
					</div>
				</div><!-- row -->

			</div><!-- anchor -->

		</div><!-- field-functions -->

		<div id='field-type' class='standard fieldset'>
			<h2 class='<?= ($_SESSION['dbomaker_controls']['show_field_type'])?('active'):('') ?> toggle-control' rel='show_field_type' tabindex="0">Tipo do Campo</h2>

			<div class='anchor' style='<?= ($_SESSION['dbomaker_controls']['show_field_type'])?('display: block;'):('') ?>'>
				<div class='row'>
					<div class='item'>
						<label title="Tipo do campo, entre os pré-definidos da ferramenta e plugins.">Tipo</label>
						<div class='input'>
							<select name='tipo'>
								<option value='pk' <?= ($campo->tipo == 'pk')?('SELECTED'):('') ?>>Chave Primária A.I.</option>
								<option value='text' <?= ($campo->tipo == 'text')?('SELECTED'):('') ?>>Text</option>
								<option value='password' <?= ($campo->tipo == 'password')?('SELECTED'):('') ?>>Password</option>
								<option value='textarea' <?= ($campo->tipo == 'textarea')?('SELECTED'):('') ?>>Textarea</option>
								<option value='textarea-rich' <?= ($campo->tipo == 'textarea-rich')?('SELECTED'):('') ?>>Textarea Rich</option>
								<option value='content-tools' <?= ($campo->tipo == 'content-tools')?('SELECTED'):('') ?>>Content Tools</option>
								<option value='select' <?= ($campo->tipo == 'select')?('SELECTED'):('') ?>>Select (combo-box)</option>
								<option value='radio' <?= ($campo->tipo == 'radio')?('SELECTED'):('') ?>>Radio Button</option>
								<option value='checkbox' <?= ($campo->tipo == 'checkbox')?('SELECTED'):('') ?>>Checkbox</option>
								<option value='date' <?= ($campo->tipo == 'date')?('SELECTED'):('') ?>>Data</option>
								<option value='datetime' <?= ($campo->tipo == 'datetime')?('SELECTED'):('') ?>>Data e Hora</option>
								<option value='price' <?= ($campo->tipo == 'price')?('SELECTED'):('') ?>>Preço</option>
								<option value='file' <?= ($campo->tipo == 'file')?('SELECTED'):('') ?>>Arquivo</option>
								<option value='image' <?= ($campo->tipo == 'image')?('SELECTED'):('') ?>>Imagem</option>
								<option value='media' <?= ($campo->tipo == 'media')?('SELECTED'):('') ?>>Mídia</option>
								<option value='join' <?= ($campo->tipo == 'join' || $campo->tipo == 'joinNN')?('SELECTED'):('') ?>>Join</option>
								<option value='query' <?= ($campo->tipo == 'query')?('SELECTED'):('') ?>>Query</option>
								<option value='plugin' <?= ($campo->tipo == 'plugin')?('SELECTED'):('') ?>>Plugin</option>
							</select>
						</div>
					</div>
				</div><!-- row -->

				<div class='field-type-details'>
				<?
					getFieldTypeDetail($campo->tipo, $mod, $field);
				?>
				</div>

			</div><!-- anchor -->

		</div><!-- field-type-details -->

		<?php
			if(!$_SESSION['dbomaker_modulos'][$mod]->imported_module)
			{
				?>
				<div class='button-next hidden button-salvar' rel='#form-field' sending='Enviando...' original_value='Salvar' tabindex="0"><span><u>S</u>alvar</span></div>
				<?php
			}
		?>

		<input type='hidden' name='runUpdateField' value='1'/>
		<input type='hidden' name='active_module' value='<?= $mod ?>'/>
		<input type='hidden' name='active_field' value='<?= $field ?>'/>

	</form>
	<?
}

function getFieldTypeDetail ($type = '', $mod = '',$field = '')
{

	if(!$type) { return; }

	if($mod && $field)
	{
		$campo = $_SESSION['dbomaker_modulos'][$mod]->campo[$field];
	}

	// TEXTEAREA / RICH -----------------------------------------------------------------------------------------------------------
	if($type == 'textarea' || $type == 'textarea-rich')
	{
		?>
					<div class='row wide'>
						<div class='item'>
							<label>Número de Linhas</label>
							<div class='input'>
								<div class='wrapper-field-type-detail'>
									<div class='row'>
										<div class='item'>
											<input type='text' name='rows' value='<?= $campo->rows ?>'/>
										</div><!-- item -->
									</div><!-- row -->
								</div><!-- wrapper-field-type-detail -->
							</div><!-- input -->
						</div>
					</div><!-- row -->
		<?
	}
	// TEXTEAREA / RICH -----------------------------------------------------------------------------------------------------------
	if($type == 'content-tools')
	{
		?>
			<div class="wrapper-plugin-detail">				
				<div class="row wide">
					<div class="item">
						<div class="input">
							<div class="wrapper-field-type-detail">
								<div class="row standard">
									<div class="item">
										<label>
											Parâmetros (1 por linha):<br><br>
											template:
											<span class="param-desc">
												nome do template que será utilizado para este campo (dboTemplates)<br /><br />

												Exemplo:<br />
												template: pagina-completa<br />
											</span>
										</label>
										<div class="input">
											<textarea rows="10" name="params"><?php
												if(!in_array('template', array_keys((array)$campo->params)))
												{
													echo "template: content-tools-blank\n";
												}
												foreach((array)$campo->params as $key => $value)
												{
													echo $key.': ';
													if($value === true)
													{
														echo 'true';
													}
													elseif($value === false)
													{
														echo 'false';
													}
													else
													{
														echo $value;
													}
													echo "\n";
												}	
											?></textarea>
										</div><!-- input -->
									</div><!-- item -->
								</div><!-- row -->
								<div class="clear"></div>
							</div><!-- wrapper-field-type-detail -->
						</div><!-- input -->
					</div><!-- item -->
				</div><!-- row -->
			</div>
		<?
	}
	// SELECT ------------------------------------------------------------------------------------------------------------------------
	elseif($type == 'select' || $type == 'radio' || $type == 'checkbox')
	{
		?>
					<div class='row wide'>
						<div class='item'>
							<label title="1 por linha. Se desejar índices não numericos, utilize a forma 'indice => Valor'">Valores</label>
							<div class='input'>
								<div class='wrapper-field-type-detail'>
									<div class='row'>
										<div class='item'>
											<textarea name='valores' rows='10'><?
											$partes = array();
											$todo = '';
											if($campo->valores)
											{
												foreach($campo->valores as $chave => $valor)
												{
													$partes[] = $chave." => ".$valor;
												}
												$todo = implode("\n", $partes);
												echo $todo;
											}
											?></textarea>
										</div><!-- item -->
									</div><!-- row -->
								</div><!-- wrapper-field-type-detail -->
							</div><!-- input -->
						</div>
					</div><!-- row -->
		<?
		// PRICE ------------------------------------------------------------------------------------------------------------------------
		} elseif($type == 'price') {
		?>
					<div class='row wide'>
						<div class='item'>
							<label>Formato</label>
							<select name="formato">
								<option value="real" <?= (($campo->formato == 'real')?('selected'):('')) ?>>Real (R$ 1.000,00)</option>
								<option value="dolar" <?= (($campo->formato == 'dolar')?('selected'):('')) ?>>Dólar (US$ 1,000.00)</option>
								<option value="generico" <?= (($campo->formato == 'generico')?('selected'):('')) ?>>Genérico ($ 1.000,00)</option>
							</select>
						</div>
					</div><!-- row -->
		<?
		// IMAGE ------------------------------------------------------------------------------------------------------------------------
		} elseif($type == 'image') {
		?>
					<div class='row wide'>
						<div class='item'>
							<label title="Permite que o usuário aumente as margens da imagem na edição">Permitir expansão do canvas</label>
							<select name="allow_canvas_expansion">
								<option value="false" <?= (($campo->allow_canvas_expansion == false)?('selected'):('')) ?>>Não</option>
								<option value="true" <?= (($campo->allow_canvas_expansion == true)?('selected'):('')) ?>>Sim</option>
							</select>
							<label title="Não utilize prefixo para o primeiro elemento. As dimensões representam dimensões máximas que a imagem pode ter.">Dimensões</label>
							<div class='input' style='position: relative; padding-bottom: 20px;'>
							<a href='#' class='image-new-size'><span>Novo tamanho</span></a>
							<?
								if($campo->image)
								{
									foreach($campo->image as $chave => $valor)
									{
										getFieldImageDetail($chave, $valor->width, $valor->height, $valor->prefix, $valor->quality);
									}
								}
								else {
									getFieldImageDetail(0, $valor->width, $valor->height, $valor->prefix, $valor->quality);
								}
							?>
							</div>
						</div>
					</div><!-- row -->
		<?
		// MIDIA ------------------------------------------------------------------------------------------------------------------------
		} elseif($type == 'media') {
			?>
						<div class='row wide'>
							<div class='item'>
								<label>Formatos permitidos, separados por vírgula (imagem,video)</label>
								<div class='input'>
									<div class='wrapper-field-type-detail'>
										<div class='row'>
											<div class='item'>
												<input type='text' name='formatos' value='<?= $campo->formatos ?>'/>
											</div><!-- item -->
										</div><!-- row -->
									</div><!-- wrapper-field-type-detail -->
								</div><!-- input -->
							</div>
						</div><!-- row -->
			<?
		// JOIN ------------------------------------------------------------------------------------------------------------------------
		} elseif($type == 'join' || $type == 'joinNN') {

			if($type == 'join') {
				$join_type = 'single';
			} else {
				$join_type = 'multi';
			}
			$single_multi = $join_type."-".$campo->join->tipo;
		?>
					<div class='row wide'>
						<div class='item'>
							<label>Configurações</label>
							<div class='input'>
								<div class='wrapper-field-type-detail'>
									<div class='row'>
										<div class='item'>
											<label>Módulo</label>
											<select name='join[modulo]' class='join-modulo'>
												<option value='-1'>Selecione...</option>
												<? getOptionsModules($campo->join->modulo) ?>
											</select>
										</div><!-- item -->
									</div><!-- row -->
									<div class='row'>
										<div class='item item-33'>
											<label>Chave</label>
											<select name='join[chave]' class='join-chave'>
												<? getOptionsModuleFields($campo->join->modulo, $campo->join->chave) ?>
											</select>
										</div><!-- item -->
										<div class='item item-33'>
											<label>Exibir</label>
											<select name='join[valor]' class='join-valor'>
												<? getOptionsModuleFields($campo->join->modulo, $campo->join->valor) ?>
											</select>
										</div><!-- item -->
										<div class='item item-33'>
											<label>Ordernar Por</label>
											<select name='join[order_by]' class='join-order'>
												<? getOptionsModuleFields($campo->join->modulo, $campo->join->order_by) ?>
											</select>
										</div><!-- item -->
									</div><!-- row -->
									<?php
										if(CREATE_FKS)
										{
											?>
											<div class='row'>
												<div class='item item-33'>
													<label>On update</label>
													<?= renderSelectFkActions('join[on_update]', $campo->join->on_update, 'update') ?>
												</div><!-- item -->
												<div class='item item-33'>
													<label>On delete</label>
													<?= renderSelectFkActions('join[on_delete]', $campo->join->on_delete, 'delete') ?>
												</div><!-- item -->
											</div><!-- row -->
											<?php
										}
									?>
									<div class="row cf">
										<div class='item item-33'>
											<label>AJAX</label>
											<select name='join[ajax]' class='join-order'>
												<option value="0">Não</option>
												<option value="1" <?= (($campo->join->ajax)?('selected'):('')) ?>>Sim</option>
											</select>
										</div><!-- item -->
										<div class='item item-33'>
											<label>Select2</label>
											<select name='join[select2]' class='join-order'>
												<option value="0">Não</option>
												<option value="1" <?= (($campo->join->select2)?('selected'):('')) ?>>Sim</option>
											</select>
										</div><!-- item -->
										<div class="item item-33">
											<label>Tamanho mínimo do texto</label>
											<div class="input">
												<input type="text" name="join[tamanho_minimo]" value="<?= htmlSpecialChars($campo->join->tamanho_minimo) ?>"/>
											</div>
										</div><!-- item -->
									</div><!-- row -->
									<div class="row cf">
										<div class="item item-50">
											<label>Método de listagem (método chamado para selecionar os dados)</label>
											<div class="input">
												<input type="text" name="join[metodo_listagem]" value="<?= htmlSpecialChars($campo->join->metodo_listagem) ?>"/>
											</div>
										</div><!-- item -->
										<div class="item item-50">
											<label>Método de retorno (método chamado na exibição da listagem)</label>
											<div class="input">
												<input type="text" name="join[metodo_retorno]" value="<?= htmlSpecialChars($campo->join->metodo_retorno) ?>"/>
											</div>
										</div><!-- item -->
									</div><!-- row -->
									<div class="row cf">
										<div class="item">
											<label>Parâmetros AJAX (objeto JSON que será passado por REQUEST (pode-se usar $obj))</label>
											<textarea name="join[parametros]" rows="3"><?= htmlSpecialChars($campo->join->parametros) ?></textarea>
										</div><!-- item -->
									</div><!-- row -->
									
									<div class='row'>
										<div class='item'>
											<label>Tipo</label>
											<select name='join[tipo]' class='join-tipo'>
												<option value='single-select' <?= ($single_multi == 'single-select')?('SELECTED'):('') ?>>Select (combo-box)</option>
												<option value='single-radio' <?= ($single_multi == 'single-radio')?('SELECTED'):('') ?>>Radio Button</option>
												<option value='multi-select' <?= ($single_multi == 'multi-select')?('SELECTED'):('') ?>>NxN Select (lista com ferramentas)</option>
												<option value='multi-checkbox' <?= ($single_multi == 'multi-checkbox')?('SELECTED'):('') ?>>NxN Checkboxes</option>
											</select>
										</div><!-- item -->
									</div><!-- row -->
									<div class='wrapper-join-nn' style='display: none; padding: 10px; background: rgba(255,255,255,.8); border-radius: 7px; -moz-border-radius: 7px; margin-top: 5px;'>
										<div class='row'>
											<div class='item'>
												<label>Tabela de Ligação</label>
												<input type='text' name='join[tabela_ligacao]' value='<?= $campo->join->tabela_ligacao ?>'>
											</div><!-- item -->
										</div><!-- row -->
										<div class='row'>
											<div class='item item-25'>
												<label title="Irá conter a chave do campo atual.">Campo 1 (modulo atual)</label>
												<input type='text' name='join[chave1]' value='<?= $campo->join->chave1 ?>'>
											</div><!-- item -->
											<div class='item item-25 border-right'>
												<label>PK 1 (PK no mód. atual (id))</label>
												<input type='text' name='join[chave1_pk]' value='<?= $campo->join->chave1_pk ?>'>
											</div><!-- item -->
											<div class='item item-25'>
												<label title="Irá conter a chave do campo relacionado">Campo 2 (modulo extrangeiro)</label>
												<input type='text' name='join[chave2]' value='<?= $campo->join->chave2 ?>'>
											</div><!-- item -->
											<div class='item item-25'>
												<label>PK 2 (PK no mód. extrangeiro (id))</label>
												<input type='text' name='join[chave2_pk]' value='<?= $campo->join->chave2_pk ?>'>
											</div><!-- item -->
										</div><!-- row -->
										<?php
											if(CREATE_FKS)
											{
												?>
												<div class='row'>
													<div class='item item-25'>
														<label title="Ação da chave primária no campo 1 no update">On update</label>
														<?= renderSelectFkActions('join[chave1_on_update]', $campo->join->chave1_on_update, 'update') ?>
													</div><!-- item -->
													<div class='item item-25 border-right'>
														<label title="Ação da chave primária no campo 1 no delete">On delete</label>
														<?= renderSelectFkActions('join[chave1_on_delete]', $campo->join->chave1_on_delete, 'delete') ?>
													</div><!-- item -->
													<div class='item item-25'>
														<label title="Ação da chave primária no campo 2 no update">On update</label>
														<?= renderSelectFkActions('join[chave2_on_update]', $campo->join->chave2_on_update, 'update') ?>
													</div><!-- item -->
													<div class='item item-25'>
														<label title="Ação da chave primária no campo 2 no delete">On delete</label>
														<?= renderSelectFkActions('join[chave2_on_delete]', $campo->join->chave2_on_delete, 'delete') ?>
													</div><!-- item -->
												</div><!-- row -->
												<?php
											}
										?>
										<div class='row'>
											<div class='item item-50'>
												<label>Relação adicional <span style="cursor: help;" title="Utiliza uma função para criar uma relação adicional neste join. Recebe como parâmetro a variável $obj. Ex: getUnidadeAtiva"><i class="fa fa-question-circle"></i></span></label>
												<input type='text' name='join[relacao_adicional_coluna]' placeholder="coluna" value='<?= $campo->join->relacao_adicional_coluna ?>'>
											</div><!-- item -->
											<div class='item item-50'>
												<label>&nbsp;</label>
												<input type='text' name='join[relacao_adicional_funcao]' placeholder="função" value='<?= $campo->join->relacao_adicional_funcao ?>'>
											</div>
										</div>
										<div class='clear'></div>
									</div><!-- wrapper-join-nn -->

									<?
									if($campo->restricao)
									{
										$restricao = unident($campo->restricao);
									}
									?>

									<div class='row'>
										<div class='item'>
											<label>Restrição (opcional) <i class="fa fa-question-circle help" title="Restrição opcional. Salvar a restrição em uma variável '$rest'."></i></label>
											<textarea name='restricao' class='code' rows='1'><?= $restricao ?></textarea>
										</div><!-- item -->
									</div><!-- row -->

								</div><!-- wrapper-field-type-detail -->
							</div>
						</div>
					</div><!-- row -->
		<?
		// IMAGE ------------------------------------------------------------------------------------------------------------------------
		} elseif($type == 'query') {
		?>
					<div class='row wide'>
						<div class='item'>
							<label title="O resultado da sua query deve ser salva no campo 'val'.">Código da Query</label>
							<textarea name='query' class='code' rows='1'><?= unident($campo->query) ?></textarea>
						</div>
					</div><!-- row -->
		<?
		// PLUGIN ------------------------------------------------------------------------------------------------------------------------
		} elseif($type == 'plugin') {
		?>
					<div class='row standard'>
						<div class='item'>
							<label>Plugin</label>
							<div class='input'>
								<select name='plugin_selector'>
									<option value='-1'>Selecione...</option>
									<?= getOptionsPlugins($campo->plugin->name) ?>
								</select>
							</div>
						</div>
					</div><!-- row -->
					<div class='wrapper-plugin-detail'>
						<? getPluginDetail($campo->plugin->name, $mod, $field) ?>
					</div>
		<?
	}
}

function getPluginDetail ($plugin, $mod = '', $field = '')
{
	if($plugin == '-1') { return; }

	global $_SESSION;
	$campo = $_SESSION['dbomaker_modulos'][$mod]->campo[$field];

	$plugin_name = $plugin;

	if($plugin)
	{
		$plugin = file_get_contents('../plugins/'.$plugin.'/'.$plugin.".php");

		list($lixo, $parte_descricao) = explode("Description:", $plugin);
		list($parte_descricao, $lixo) = explode("\n", $parte_descricao);
		list($parte_descricao, $lixo) = explode("//", $parte_descricao);
		$descricao = trim($parte_descricao);

		list($lixo, $parte_params) = explode("Params:", $plugin);
		list($parte_params, $lixo) = explode("*/", $parte_params);
		$parte_params = explode("\n", $parte_params);
		if(is_array($parte_params))
		{
			foreach($parte_params as $chave => $valor)
			{
				if(!strpos($valor, ':') || strpos($valor, '[user]')) {}
				else {
					$params[] = trim($valor);
				}
			}
		}
		if(sizeof($params))
		{
			?>
				<div class='row wide'>
					<div class='item'>
						<label><?= $descricao ?></label>
						<div class='input'>
							<div class='wrapper-field-type-detail'>
								<div class='row standard'>
									<div class='item'>
										<label>
										<?
											echo "Parâmetros (1 por linha):<br><br>";
											foreach($params as $chave => $valor)
											{
												$partes = explode(":", $valor);
												$def = array_shift($partes);
												echo $def.":<span class='param-desc'>".str_replace('\n', '<br />', implode(':', $partes))."</span><br/>";
											}
										?>
										</label>
										<div class='input'>
											<input type='hidden' name='plugin[name]' value='<?= $plugin_name ?>'>
											<textarea rows='10' name='plugin[params]'><?
												if(is_array($campo->plugin->params))
												{
													$parameters = array();
													foreach($campo->plugin->params as $chave => $valor)
													{
														if($valor === true) { $valor = 'true'; }
														elseif($valor === false) { $valor = 'false'; }
														$parameters[] = $chave.": ".$valor;
													}
													echo implode("\n", $parameters);
												}
											?></textarea>
										</div><!-- input -->
									</div><!-- item -->
								</div><!-- row -->
								<div class='clear'></div>
							</div><!-- wrapper-field-type-detail -->
						</div><!-- input -->
					</div><!-- item -->
				</div><!-- row -->
			<?
		}
	}
}

function getFieldImageDetail ($campo, $w = '', $h = '', $prefix = '', $quality = '')
{
	?>
	<div class='wrapper-field-type-detail dbo-image-array' rel='<?= $campo ?>'>
		<a href='' class='self-delete'><span>x</span></a>
		<div class='row'>
			<div class='item item-50'>
				<label>Largura</label>
				<input type='text' name='dbo_image_array[<?= $campo ?>][width]' value='<?= $w ?>'>
			</div>
			<div class='item item-50'>
				<label>altura</label>
				<input type='text' name='dbo_image_array[<?= $campo ?>][height]' value='<?= $h ?>'>
			</div>
		</div>
		<div class='row'>
			<div class='item item-50'>
				<label>Prefix</label>
				<input type='text' name='dbo_image_array[<?= $campo ?>][prefix]' value='<?= $prefix ?>'>
			</div>
			<div class='item item-50'>
				<label>Quality</label>
				<input type='text' name='dbo_image_array[<?= $campo ?>][quality]' value='<?= $quality ?>'>
			</div>
		</div>
		<div class='clear'></div>
	</div>
	<?
}

function getOptionsModules ($active_module = '')
{
	foreach($_SESSION['dbomaker_modulos'] as $chave => $valor)
	{
		echo "<option value='".$valor->modulo."' ".(($valor->modulo == $active_module)?('SELECTED'):('')).">".$valor->titulo."</option>";
	}
	foreach($_SESSION['dbomaker_modulos_read_only'] as $chave => $valor)
	{
		echo "<option value='".$valor->modulo."' ".(($valor->modulo == $active_module)?('SELECTED'):('')).">".$valor->titulo."</option>";
	}
}

function getOptionsModuleFields ($module, $active_field = '')
{
	$modulo = $_SESSION['dbomaker_modulos'][$module];

	if($modulo)
	{
		if(is_array($modulo->campo))
		{
			foreach($modulo->campo as $chave => $valor)
			{
				echo "<option value='".$valor->coluna."' ".(($valor->coluna == $active_field)?('SELECTED'):('')).">".$valor->titulo."</option>";
			}
		}
	}
	else
	{
		$modulo = $_SESSION['dbomaker_modulos_read_only'][$module];
		if(is_array($modulo->campo))
		{
			foreach($modulo->campo as $chave => $valor)
			{
				echo "<option value='".$valor->coluna."' ".(($valor->coluna == $active_field)?('SELECTED'):('')).">".$valor->titulo."</option>";
			}
		}
	}
}

function getOptionsPlugins ($active_plugin = '')
{
	$pasta_plugs = '../plugins';

	$d = dir($pasta_plugs);
	while (false !== ($entry = $d->read())) {
		if($entry != '.' && $entry != '..' && !strpos($entry, '.'))
		{
			$plugin = file_get_contents($pasta_plugs."/".$entry."/".$entry.".php");

			list($lixo, $parte_nome) = explode("Plugin Name:", $plugin);
			list($parte_nome, $lixo) = explode("\n", $parte_nome);
			list($parte_nome, $lixo) = explode("//", $parte_nome);
			$nome = trim($parte_nome);
			if($nome)
			{
				echo "<option value='".$entry."' ".(($entry == $active_plugin)?("SELECTED"):('')).">".$nome."</option>\n";
			}
		}
	}
	$d->close();
}

function getNewFieldForm($mod)
{
	$module = $_SESSION['dbomaker_modulos'][$mod];
	?>
	<h1>Novo campo | Módulo: <?= htmlSpecialChars($module->titulo) ?></h1>
	<div class='wrapper-new-field-type'>
		<form method='POST' action='actions.php' id='form-new-field'>
			<ul class='field-types field-types-basic'>
				<li class='title'>Pré-definidos</li>
				<li><input type='radio' name='tipo' id="tipo-pk" value='pk'/><label for="tipo-pk">Chave Primária A.I.</label></li>
				<li><input type='radio' name='tipo' id="tipo-text" value='text' checked/><label for="tipo-text">Text</label></li>
				<li><input type='radio' name='tipo' id="tipo-textarea" value='textarea'/><label for="tipo-textarea">Textarea</label></li>
				<li><input type='radio' name='tipo' id="tipo-textarea-rich" value='textarea-rich'/><label for="tipo-textarea-rich">Textarea Rich Text</label></li>
				<li><input type='radio' name='tipo' id="tipo-content-tools" value='content-tools'/><label for="tipo-content-tools">Content Tools</label></li>
				<li><input type='radio' name='tipo' id="tipo-password" value='password'/><label for="tipo-password">Password</label></li>
				<li><input type='radio' name='tipo' id="tipo-image" value='image'/><label for="tipo-image">Upload de Imagem</label></li>
				<li><input type='radio' name='tipo' id="tipo-file" value='file'/><label for="tipo-file">Upload de Arquivo</label></li>
				<li><input type='radio' name='tipo' id="tipo-media" value='media'/><label for="tipo-media">Mídia</label></li>
				<li><input type='radio' name='tipo' id="tipo-select" value='select'/><label for="tipo-select">Select (drop-down múltiplos valores)</label></li>
				<li><input type='radio' name='tipo' id="tipo-radio" value='radio'/><label for="tipo-radio">Radio (múltiplos valores)</label></li>
				<li><input type='radio' name='tipo' id="tipo-checkbox" value='checkbox'/><label for="tipo-checkbox">Checkbox (múltiplos valores)</label></li>
				<li><input type='radio' name='tipo' id="tipo-date" value='date'/><label for="tipo-date">Data com Calendário</label></li>
				<li><input type='radio' name='tipo' id="tipo-datetime" value='datetime'/><label for="tipo-datetime">Data e Hora com Calendário</label></li>
				<li><input type='radio' name='tipo' id="tipo-price" value='price'/><label for="tipo-price">Preço</label></li>
				<li><input type='radio' name='tipo' id="tipo-join" value='join'/><label for="tipo-join">Join com outro Módulo</label></li>
				<li><input type='radio' name='tipo' id="tipo-query" value='query'/><label for="tipo-query">Query (campo virtual)</label></li>
				<li><input type='radio' name='tipo' id="tipo-plugin" value='plugin'/><label for="tipo-plugin">Plugin</label></li>
				<li><input type='radio' name='tipo' id="tipo-custom" value='custom'/><label for="tipo-custom">Personalizado</label></li>
			</ul>

			<ul class='field-types field-types-basic'>
				<li class='title'>Automáticos</li>
				<li><input type='radio' name='tipo' id="tipo-created_by" value='created_by'/><label for="tipo-created_by">Criado Por (created_by)</label></li>
				<li><input type='radio' name='tipo' id="tipo-created_on" value='created_on'/><label for="tipo-created_on">Criado Em (created_on)</label></li>
				<li><input type='radio' name='tipo' id="tipo-updated_by" value='updated_by'/><label for="tipo-updated_by">Modificado Por (updated_by)</label></li>
				<li><input type='radio' name='tipo' id="tipo-updated_on" value='updated_on'/><label for="tipo-updated_on">Modificado Em (updated_on)</label></li>
				<li><input type='radio' name='tipo' id="tipo-deleted_by" value='deleted_by'/><label for="tipo-deleted_by">Deletado Por (deleted_by)</label></li>
				<li><input type='radio' name='tipo' id="tipo-deleted_on" value='deleted_on'/><label for="tipo-deleted_on">Deletado Em (deleted_on)</label></li>
				<li><input type='radio' name='tipo' id="tipo-deleted_because" value='deleted_because'/><label for="tipo-deleted_because">Deletado Porque (deleted_because)</label></li>
				<li><input type='radio' name='tipo' id="tipo-order_by" value='order_by'/><label for="tipo-order_by">Auto Ordenação (order_by)</label></li>
				<li><input type='radio' name='tipo' id="tipo-inativo" value='inativo'/><label for="tipo-inativo">Inativo</label></li>
				<li><input type='radio' name='tipo' id="tipo-permalink" value='permalink'/><label for="tipo-permalink">Permalink (permalink)</label></li>
			</ul>

			<input type='hidden' name='mod' value='<?= $mod ?>'/>
			<input type='hidden' name='runNewField' value='1'/>

			<div class='button-next button-salvar' rel='#form-new-field' sending='Enviando...' original_value='Prosseguir &raquo;' id="trigger-form-new-field"><span>Pro<u>s</u>seguir &raquo;</span></div>

		</form>
	</div>
	<?
}

function shiftPlace($a, $key1, $key2)
{
	if (!array_key_exists($key1,$a) && !array_key_exists($key2,$a))
	return;
	$search = array_flip(array_keys($a));

	$key1_index= $search[$key2];
	$key1_value = $a[$key1];

	$key2_index= $search[$key1];
	$key2_value = $a[$key2];

	$i=0;
	foreach($a as $key => $value){
		if($i==$key1_index) $new[$key1] = $key1_value;
		elseif($i==$key2_index) $new[$key2] = $key2_value;
		else $new[$key] = $value;
		$i++;
	}
	return $new;
}

function replacePlace($a,$key1,$key2)
{
	if (!array_key_exists($key1,$a) && !array_key_exists($key2,$a))
	return;
	$search = array_flip(array_keys($a));

	$key1_index= $search[$key2];
	$key1_value = $a[$key1];

	$key2_index= $search[$key1];
	$key2_value = $a[$key2];

	$i=0;
	foreach($a as $key => $value){
		if($i==$key2_index) $new[$key2] = $key2_value;
		else $new[$key] = $value;
		$i++;
	}
	return $new;
}

function runUpdateField($post_data)
{
	$mod = $post_data['active_module'];

	$field = $post_data['coluna'];

	//main validation
	if(!strlen(trim($post_data['coluna'])))
	{
		echo "ERROR::ERRO: O nome da coluna é obrigatório::#wrapper-details";
		exit();
	}
	//only 1 primary key per module
	if($post_data['tipo'] == 'pk')
	{
		foreach($_SESSION['dbomaker_modulos'][$mod]->campo as $campo_def)
		{
			if($campo_def->tipo == 'pk' && $campo_def->coluna != $field)
			{
				//echo "ERROR::Erro: O campo '".$campo_def->titulo."' já é a Chave Primária::#wrapper-details"; exit();
			}
		}
	}
	//only one default order per module
	if($post_data['default'] != -1)
	{
		foreach($_SESSION['dbomaker_modulos'][$mod]->campo as $campo_def)
		{
			if($campo_def->default && $campo_def->coluna != $field)
			{
				echo "ERROR::Erro: O campo '".$campo_def->titulo."' já é padrão de ordenação::#wrapper-details"; exit();
			}
		}
	}

	if($post_data['active_field'] != $field)
	{
		//first, we see if the field doesnt already exist in the active module...
		if(isset($_SESSION['dbomaker_modulos'][$mod]->campo[$field]))
		{
			echo "ERROR::ERRO: A coluna \"".$post_data['coluna']."\" já está definida em outro campo.::#wrapper-details";
			exit();
		}

		//otherwise... we copy the active field meta-data to the new one.
		$new_field = $_SESSION['dbomaker_modulos'][$mod]->campo[$post_data['active_field']];

		//then we create the new field in the session
		$_SESSION['dbomaker_modulos'][$mod]->campo[$field] = $new_field;

		$_SESSION['dbomaker_modulos'][$mod]->campo = replacePlace($_SESSION['dbomaker_modulos'][$mod]->campo, $post_data['active_field'], $field);

		//and destroy the previous.
		unset($_SESSION['dbomaker_modulos'][$mod]->campo[$post_data['active_field']]);
	}

	//back to business...
	$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->titulo = ((strlen(trim($post_data['titulo'])))?(stripslashes($post_data['titulo'])):('&nbsp;'));
	$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->coluna = $post_data['coluna'];
	$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->label_display = $post_data['label_display'];
	$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->titulo_listagem = stripslashes($post_data['titulo_listagem']);
	$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->dica = stripslashes($post_data['dica']);

	if($post_data['default'] != -1)
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->default = $post_data['default'];
	}
	else
	{
		unset($_SESSION['dbomaker_modulos'][$mod]->campo[$field]->default);
	}

	$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->type = $post_data['type'].(($post_data['mysql_size'])?('('.$post_data['mysql_size'].')'):(''));
	$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->pk = (($post_data['pk'])?(true):(false));
	$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->isnull = (($post_data['isnull'])?(true):(false));
	$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->unique = (($post_data['unique'])?(true):(false));

	//special treatment for the profile types. gotta destroy the session one if it's not set anymore.
	if(is_array($post_data['perfil'])) {
		$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->perfil = $post_data['perfil'];
	} else {
		unset($_SESSION['dbomaker_modulos'][$mod]->campo[$field]->perfil);
	}

	//back to business
	$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->interaction = $post_data['interaction'];
	$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->list_function = $post_data['list_function'];
	$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->edit_function = $post_data['edit_function'];
	$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->default_value = stripslashes($post_data['default_value']);
	$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->mask = stripslashes($post_data['mask']);
	$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->classes = stripslashes($post_data['classes']);
	$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->styles = stripslashes($post_data['styles']);
	$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->tipo = $post_data['tipo'];

	//now checking all the specific data for the types.
	//textarea, textarea-rich ----------------------------------------------------------------------------------------
	if($post_data['tipo'] == 'textarea' || $post_data['tipo'] == 'textarea-rich')
	{
		if(strlen($post_data['rows'])) { $_SESSION['dbomaker_modulos'][$mod]->campo[$field]->rows = $post_data['rows']; }
	}
	//content-tools --------------------------------------------------------------------------------------------------
	if($post_data['tipo'] == 'content-tools')
	{
		$params = array();
		$post_params = explode("\n", trim($post_data['params']));
		foreach($post_params as $key => $param)
		{
			$parts = explode(":", $param);
			$parts = array_reverse($parts);
			$identifier = array_pop($parts);
			$parts = array_reverse($parts);
			$parts = stripslashes(trim(implode(":", $parts)));
			if(strtolower($parts) == 'false') { $parts = false; }
			if(strtolower($parts) == 'true') { $parts = true; }
			$params[$identifier] = $parts;
		}
		$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->params = $params;
	}
	//select, radio, checkbox ----------------------------------------------------------------------------------------
	elseif($post_data['tipo'] == 'select' || $post_data['tipo'] == 'radio' || $post_data['tipo'] == 'checkbox')
	{
		$raw_values = explode("\n", $post_data['valores']);
		foreach($raw_values as $key => $value)
		{
			if(strstr($value, '=>'))
			{
				list($k, $v) = explode('=>', $value);
			}
			else
			{
				$v = $value;
			}
			$k = trim($k);
			$v = trim($v);
			if($v)
			{
				if(strlen($k))
				{
					$values[$k] = stripslashes($v);
				}
				else
				{
					$values[] = stripslashes($v);
				}
			}
		}

		$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->valores = $values;
	}
	//preco ----------------------------------------------------------------------------------------
	elseif($post_data['tipo'] == 'price')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->formato = $post_data['formato'];
	}
	//imagem ----------------------------------------------------------------------------------------
	elseif($post_data['tipo'] == 'image')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->allow_canvas_expansion = $post_data['allow_canvas_expansion'] == 'true' ? true : false;
		$image = array();
		foreach($post_data['dbo_image_array'] as $key => $value)
		{
			$obj = new obj();
			$obj->width = $value[width];
			$obj->height = $value[height];
			$obj->prefix = $value[prefix];
			$obj->quality = $value[quality];
			$image[] = $obj;
		}
		$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->image = $image;
	}
	//file ----------------------------------------------------------------------------------------
	elseif($post_data['tipo'] == 'file')
	{
		$obj = new Obj();
		$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->file = $obj;
	}
	//preco ----------------------------------------------------------------------------------------
	elseif($post_data['tipo'] == 'media')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->formatos = $post_data['formatos'];
	}
	//join ----------------------------------------------------------------------------------------
	elseif($post_data['tipo'] == 'join')
	{
		//salva restrição, se existir.
		if(strlen($post_data['restricao']))
		{
			$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->restricao = ident($post_data['restricao']);
		}
		else
		{
			unset($_SESSION['dbomaker_modulos'][$mod]->campo[$field]->restricao);
		}

		//creates the object to update the session 'join' relation
		$join = new obj();
		$join->modulo = $post_data['join']['modulo'];
		$join->chave = $post_data['join']['chave'];
		$join->valor = $post_data['join']['valor'];
		$join->order_by = $post_data['join']['order_by'];

		//foreign key actions
		if(strlen(trim($post_data['join']['on_update'])))
		{
			$join->on_update = $post_data['join']['on_update'];
		}
		if(strlen(trim($post_data['join']['on_delete'])))
		{
			$join->on_delete = $post_data['join']['on_delete'];
		}

		//verificando metodo de retorno
		if(strlen(trim($post_data['join']['metodo_retorno'])))
		{
			$join->metodo_retorno = $post_data['join']['metodo_retorno'];
		}
		else
		{
			unset($join->metodo_retorno);
		}

		//verificando metodo de listagem
		if(strlen(trim($post_data['join']['metodo_listagem'])))
		{
			$join->metodo_listagem = $post_data['join']['metodo_listagem'];
		}
		else
		{
			unset($join->metodo_listagem);
		}

		//verificando tamanho minimo do texto
		if(strlen(trim($post_data['join']['tamanho_minimo'])))
		{
			$join->tamanho_minimo = $post_data['join']['tamanho_minimo'];
		}
		else
		{
			unset($join->tamanho_minimo);
		}

		//verificando se já metodo de retorno
		if($post_data['join']['ajax'] == 1)
		{
			$join->ajax = $post_data['join']['ajax'];
		}
		else
		{
			unset($join->ajax);
		}

		//verificando se já metodo de retorno
		if($post_data['join']['select2'] == 1)
		{
			$join->select2 = $post_data['join']['select2'];
		}
		else
		{
			unset($join->select2);
		}

		list($tipo_join, $tipo_campo) = explode('-', $post_data['join'][tipo]);

		$join->tipo = $tipo_campo;

		//if it's a NxN join, completes the data required.
		if($tipo_join == 'multi')
		{
			$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->tipo = 'joinNN';
			$join->tabela_ligacao = $post_data['join']['tabela_ligacao'];
			$join->chave1 = $post_data['join']['chave1'];
			$join->chave2 = $post_data['join']['chave2'];

			//chacando chaves custom
			if(strlen(trim($post_data['join']['chave1_pk'])))
			{
				$join->chave1_pk = $post_data['join']['chave1_pk'];
			}
			else
			{
				unset($join->chave1_pk);
			}
			if(strlen(trim($post_data['join']['chave2_pk'])))
			{
				$join->chave2_pk = $post_data['join']['chave2_pk'];
			}
			else
			{
				unset($join->chave2_pk);
			}

			//checando relação adicional
			if(strlen(trim($post_data['join']['relacao_adicional_coluna'])))
			{
				$join->relacao_adicional_coluna = $post_data['join']['relacao_adicional_coluna'];
			}
			else
			{
				unset($join->relacao_adicional_coluna);
			}
			if(strlen(trim($post_data['join']['relacao_adicional_funcao'])))
			{
				$join->relacao_adicional_funcao = $post_data['join']['relacao_adicional_funcao'];
			}
			else
			{
				unset($join->relacao_adicional_funcao);
			}

			//setando ações das constraints
			$join->chave1_on_update = $post_data['join']['chave1_on_update'];
			$join->chave1_on_delete = $post_data['join']['chave1_on_delete'];
			$join->chave2_on_update = $post_data['join']['chave2_on_update'];
			$join->chave2_on_delete = $post_data['join']['chave2_on_delete'];
		}

		//finally, updates the session.
		$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->join = $join;

	}
	//query ----------------------------------------------------------------------------------------
	elseif($post_data['tipo'] == 'query')
	{
		//salva query, se digitada
		if(strlen($post_data['query']))
		{
			$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->query = ident($post_data['query']);
		}
		else
		{
			unset($_SESSION['dbomaker_modulos'][$mod]->campo[$field]->query);
		}
	}
	//plugin ----------------------------------------------------------------------------------------
	elseif($post_data['tipo'] == 'plugin')
	{
		$plugin = new obj();
		$plugin->name = $post_data['plugin']['name'];

		$params = array();
		$post_params = explode("\n", trim($post_data['plugin']['params']));
		foreach($post_params as $key => $param)
		{
			$parts = explode(":", $param);
			$parts = array_reverse($parts);
			$identifier = array_pop($parts);
			$parts = array_reverse($parts);
			$parts = stripslashes(trim(implode(":", $parts)));
			if(strtolower($parts) == 'false') { $parts = false; }
			if(strtolower($parts) == 'true') { $parts = true; }
			$params[$identifier] = $parts;
		}

		$plugin->params = $params;
		$_SESSION['dbomaker_modulos'][$mod]->campo[$field]->plugin = $plugin;
	}

	//everything's done :)
	flagUpdate($mod);
	echo "RUN_UPDATE_FIELD_OK::".$mod."::".$field;
}

function runNewField($post_data)
{

	$mod = $post_data['mod'];

	//main validations
	if($post_data['tipo'] == 'pk')
	{
		if(is_array($_SESSION['dbomaker_modulos'][$mod]->campo))
		{
			foreach($_SESSION['dbomaker_modulos'][$mod]->campo as $value)
			{
				if($value->tipo == 'pk') { echo "ERROR::Erro: Já existe uma chave primária no módulo::#wrapper-details"; exit(); }
			}
		}
	}
	if(
		$post_data['tipo'] == 'created_by' ||
		$post_data['tipo'] == 'created_on' ||
		$post_data['tipo'] == 'updated_by' ||
		$post_data['tipo'] == 'updated_on' ||
		$post_data['tipo'] == 'deleted_by' ||
		$post_data['tipo'] == 'deleted_on' ||
		$post_data['tipo'] == 'deleted_because' ||
		$post_data['tipo'] == 'order_by' ||
		$post_data['tipo'] == 'inativo' ||
		$post_data['tipo'] == 'permalink'
	)
	{
		foreach($_SESSION['dbomaker_modulos'][$mod]->campo as $value)
		{
			if($value->coluna == $post_data['tipo']) { echo "ERROR::Erro: Já existe um campo '".$post_data['tipo']."' no módulo::#wrapper-details"; exit(); }
		}
	}

	unset($_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']); //a funny placeholder :P

	//placeholder data

	$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658'] = new stdClass();

	$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->titulo = "&nbsp;";
	$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->coluna = "temporary_field_key_5658";

	//inicial default definitions (custom type)
	$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "VARCHAR(255)";
	$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->pk = false;
	$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->isnull = false;
	$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'text';
	//inicial default permissions (custom type)
	$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->valida = false;
	$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->add = true;
	$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->edit = true;
	$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->view = true;
	$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->lista = false;
	$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->order = false;
	$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->filter = false;

	$_SESSION['dbomaker_controls']['show_field_advanced'] = FALSE;
	$_SESSION['dbomaker_controls']['show_field_type'] = FALSE;

	//Primary Key
	if($post_data['tipo'] == 'pk')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->titulo = "Id";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->coluna = "id";

		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "INT NOT NULL auto_increment";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'pk';
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->pk = true;

		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->add = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->edit = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->view = false;
	}
	//Input text
	elseif($post_data['tipo'] == 'text')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "VARCHAR(255)";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'text';
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->lista = true;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->order = true;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->filter = true;
	}
	//Textarea
	elseif($post_data['tipo'] == 'textarea')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "TEXT";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'textarea';
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->classes = 'autosize';
		$_SESSION['dbomaker_controls']['show_field_type'] = TRUE;
	}
	//Textarea Rich
	elseif($post_data['tipo'] == 'textarea-rich')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "TEXT";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'textarea-rich';
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->classes = 'tinymce';
		$_SESSION['dbomaker_controls']['show_field_type'] = TRUE;
	}
	//Textarea Rich
	elseif($post_data['tipo'] == 'content-tools')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "TEXT";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'content-tools';
		$_SESSION['dbomaker_controls']['show_field_type'] = TRUE;
	}
	//Password
	elseif($post_data['tipo'] == 'password')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "VARCHAR(255)";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'password';
	}
	//Imagem
	elseif($post_data['tipo'] == 'image')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "TEXT";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'image';
		$_SESSION['dbomaker_controls']['show_field_type'] = TRUE;
	}
	//File
	elseif($post_data['tipo'] == 'file')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "TEXT";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'file';
	}
	//Mídia
	elseif($post_data['tipo'] == 'media')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "VARCHAR(255)";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'media';
	}
	//Select
	elseif($post_data['tipo'] == 'select')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "VARCHAR(255)";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'select';
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->lista = true;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->order = true;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->filter = true;
		$_SESSION['dbomaker_controls']['show_field_type'] = TRUE;
	}
	//Radio
	elseif($post_data['tipo'] == 'radio')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "VARCHAR(255)";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'radio';
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->lista = true;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->order = true;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->filter = true;
		$_SESSION['dbomaker_controls']['show_field_type'] = TRUE;
	}
	//Checkbox
	elseif($post_data['tipo'] == 'checkbox')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "TEXT";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'checkbox';
		$_SESSION['dbomaker_controls']['show_field_type'] = TRUE;
	}
	//Data
	elseif($post_data['tipo'] == 'date')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "DATE";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'date';
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->lista = true;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->order = true;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->filter = true;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->isnull = true;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->classes = 'datepick';
	}
	//Datetime
	elseif($post_data['tipo'] == 'datetime')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "DATETIME";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'datetime';
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->lista = true;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->order = true;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->filter = true;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->isnull = true;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->classes = 'datetimepick';
	}
	//Datetime
	elseif($post_data['tipo'] == 'price')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "DOUBLE";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'price';
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->lista = true;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->order = true;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->filter = true;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->isnull = true;
	}
	//Join
	elseif($post_data['tipo'] == 'join')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "INT(11)";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'join';
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->isnull = true;
		$_SESSION['dbomaker_controls']['show_field_type'] = TRUE;
	}
	//Join
	elseif($post_data['tipo'] == 'query')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "VARCHAR(255)";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'query';
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->add = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->edit = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->lista = true;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->isnull = true;
		$_SESSION['dbomaker_controls']['show_field_type'] = TRUE;
	}
	//Join
	elseif($post_data['tipo'] == 'plugin')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "TEXT";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'plugin';
		$_SESSION['dbomaker_controls']['show_field_type'] = TRUE;
	}
	//Created By
	elseif($post_data['tipo'] == 'created_by')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->titulo = "Criado Por";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->coluna = "created_by";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "INT(11)";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'join';
		$join = new obj();
		$join->modulo = 'pessoa';
		$join->chave = 'id';
		$join->valor = 'nome';
		$join->order_by = 'nome';
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->join = $join;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->add = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->edit = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->view = false;
	}
	//Created On
	elseif($post_data['tipo'] == 'created_on')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->titulo = "Criado Em";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->coluna = "created_on";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "DATETIME";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'datetime';
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->add = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->edit = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->view = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->isnull = true;
	}
	//Updated By
	elseif($post_data['tipo'] == 'updated_by')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->titulo = "Modificado Por";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->coluna = "updated_by";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "INT(11)";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'join';
		$join = new obj();
		$join->modulo = 'pessoa';
		$join->chave = 'id';
		$join->valor = 'nome';
		$join->order_by = 'nome';
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->join = $join;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->add = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->edit = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->view = false;
	}
	//Updated On
	elseif($post_data['tipo'] == 'updated_on')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->titulo = "Modificado Em";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->coluna = "updated_on";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "DATETIME";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'datetime';
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->add = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->edit = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->view = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->isnull = true;
	}
	//Deleted By
	elseif($post_data['tipo'] == 'deleted_by')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->titulo = "Deletado Por";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->coluna = "deleted_by";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "INT(11)";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'join';
		$join = new obj();
		$join->modulo = 'pessoa';
		$join->chave = 'id';
		$join->valor = 'nome';
		$join->order_by = 'nome';
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->join = $join;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->add = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->edit = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->view = false;
	}
	//Deleted On
	elseif($post_data['tipo'] == 'deleted_on')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->titulo = "Deletado Em";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->coluna = "deleted_on";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "DATETIME";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'datetime';
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->add = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->edit = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->view = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->isnull = true;
	}
	//Deleted Because
	elseif($post_data['tipo'] == 'deleted_because')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->titulo = "Deletado Porque";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->coluna = "deleted_because";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "VARCHAR(255)";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'text';
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->add = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->edit = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->view = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->isnull = true;
	}
	//Order By
	elseif($post_data['tipo'] == 'order_by')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->titulo = "Auto Ordenação";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->coluna = "order_by";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "INT(11)";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'text';
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->add = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->edit = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->view = false;
	}
	//Order By
	elseif($post_data['tipo'] == 'inativo')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->titulo = "Inativo";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->coluna = "inativo";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "INT(11)";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'text';
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->add = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->edit = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->view = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->isnull = false;
	}
	//Permalink
	elseif($post_data['tipo'] == 'permalink')
	{
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->titulo = "Permalink";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->coluna = "permalink";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->type = "VARCHAR(255)";
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->tipo = 'text';
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->add = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->edit = false;
		$_SESSION['dbomaker_modulos'][$mod]->campo['temporary_field_key_5658']->view = false;
	}

	//everything done!
	echo "RUN_NEW_FIELD_OK::".$mod."::temporary_field_key_5658";
}

function getModuleButtonForm($key)
{
	echo "<div id='module-button-".$key."' class='wrapper-module-button' posicao='".$key."'>\n";
	?>
	<div class='standard-type'>

		<div class='row standard'>
			<div class='item'>
				<label>Nome do Botão</label>
				<div class='input'><input type='text' name='button[<?= $key ?>][value]' value=""/></div>
			</div><!-- item -->
		</div><!-- row -->

		<div class='row standard'>
			<div class='item'>
				<label>Módulo</label>
				<div class='input'><input type='text' name='button[<?= $key ?>][modulo]' value=""/></div>
			</div><!-- item -->
		</div><!-- row -->

		<div class='row standard'>
			<div class='item'>
				<label>Chave Extrangeira</label>
				<div class='input'><input type='text' name='button[<?= $key ?>][modulo_fk]' value=""/></div>
			</div><!-- item -->
		</div><!-- row -->

		<div class='row standard'>
			<div class='item'>
				<label>Chave (módulo atual)</label>
				<div class='input'><input type='text' name='button[<?= $key ?>][key]' value=""/></div>
			</div><!-- item -->
		</div><!-- row -->

		<div class='row standard' style="display: none;">
			<div class='item'>
				<label>View Recursiva</label>
				<div class='input'>
					<select name='button[<?= $key ?>][view]'>
						<option value='1'>Sim</option>
						<option value='0' SELECTED>Não</option>
					</select>
				</div>
			</div><!-- item -->
		</div><!-- row -->

		<div class='row standard'>
			<div class='item'>
				<label>Exibir botão</label>
				<div class='input'>
					<select name='button[<?= $key ?>][show]'>
						<option value='1' <?= (($button->show)?('SELECTED'):('')) ?>>Sim</option>
						<option value='0' <?= ((!$button->show)?('SELECTED'):('')) ?>>Não</option>
					</select>
				</div>
			</div><!-- item -->
		</div><!-- row -->

		<div class='row standard'>
			<div class='item'>
				<label>Subsessão</label>
				<div class='input'>
					<select name='button[<?= $key ?>][subsection]'>
						<option value='1' <?= (($button->subsection)?('SELECTED'):('')) ?>>Sim</option>
						<option value='0' <?= ((!$button->subsection)?('SELECTED'):('')) ?>>Não</option>
					</select>
				</div>
			</div><!-- item -->
		</div><!-- row -->

		<div class='row standard'>
			<div class='item'>
				<label>Auto Load</label>
				<div class='input'>
					<select name='button[<?= $key ?>][autoload]'>
						<option value='1' <?= (($button->autoload)?('SELECTED'):('')) ?>>Sim</option>
						<option value='0' <?= ((!$button->autoload)?('SELECTED'):('')) ?>>Não</option>
					</select>
				</div>
			</div><!-- item -->
		</div><!-- row -->

		<div class='row'>
			<div class='item' style='text-align: right; padding-top: 3px;'>
				<a href='' class='button'>Padrão</a> <a href='' class='button-inactive toggle-module-button-view'>Custom</a> <a href='' class='button-inactive remove-button' title='Remover Botão'>x</a>
			</div><!-- item -->
		</div><!-- row -->
	</div>

	<div class='custom-type' style='display: none;'>
		<div class='row standard'>
			<div class='item'>
				<label>Nome do Botão</label>
				<div class='input'><input type='text' name='button[<?= $key ?>][value_custom]' value=""/></div>
			</div><!-- item -->
		</div><!-- row -->

		<div class='row'>
			<div class='item'>
				<label>Código do Botão ($code)</label>
				<div class='input'>
					<textarea name='button[<?= $key ?>][code]' class='code'></textarea>
				</div>
			</div><!-- item -->
		</div><!-- row -->

		<div class='row'>
			<div class='item' style='text-align: right; padding-top: 3px;'>
				<a href='' class='button-inactive toggle-module-button-view'>Padrão</a> <a href='' class='button'>Custom</a> <a href='' class='button-inactive remove-button' title='Remover Botão'>x</a>
			</div><!-- item -->
		</div><!-- row -->
	</div>

	<input type='hidden' name='button[<?= $key ?>][custom]' class='custom-flag' value="0"/>
	<?
	echo "</div><!-- module-button-".$key." -->\n\n";
}

function runUpdateModule($post_data)
{
	$mod = $post_data['active_module'];

	//main validation
	if(!strlen($post_data['titulo']))
	{
		echo "ERROR::ERRO: O nome do módulo é obrigatório.::#wrapper-fields";
		exit();
	}
	if(!strlen($post_data['modulo']))
	{
		echo "ERROR::ERRO: O identificador do módulo é obrigatório.::#wrapper-fields";
		exit();
	}
	//check for parse errors on the triggers
	$error_array = array();
	$error_array['Pré-Insert'] = checkSyntax($post_data['pre_insert']);
	$error_array['Pós-Insert'] = checkSyntax($post_data['pos_insert']);
	$error_array['Pré-Update'] = checkSyntax($post_data['pre_update']);
	$error_array['Pós-Update'] = checkSyntax($post_data['pos_update']);
	$error_array['Pré-Delete'] = checkSyntax($post_data['pre_delete']);
	$error_array['Pós-Delete'] = checkSyntax($post_data['pos_delete']);
	$error_array['Pré-List'] = checkSyntax($post_data['pre_list']);
	$error_array['Pós-List'] = checkSyntax($post_data['pos_list']);
	$error_array['Notifications'] = checkSyntax($post_data['notifications']);
	$error_array['Overview'] = checkSyntax($post_data['overview']);
	$parse_errors = array();
	foreach($error_array as $key => $value)
	{
		if($value === false)
		{
			$parse_errors[] = $key;
		}
	}
	if(sizeof($parse_errors))
	{
		echo "ERROR::Erro: Parse error na".((sizeof($parse_erros) > 1)?('s'):(''))." Trigger".((sizeof($parse_erros) > 1)?('s'):('')).": ".implode(", ", $parse_errors)."::#wrapper-fields";
		exit();
	}

	if($mod != $post_data['modulo'])
	{
		//first, we see if the module doesnt exist...
		if(isset($_SESSION['dbomaker_modulos'][$post_data['modulo']]))
		{
			echo "ERROR::ERRO: O módulo \"".$post_data['modulo']."\" já está criado.::#wrapper-fields";
			exit();
		}

		//otherwise... we copy the active module to the new one
		$new_module = $_SESSION['dbomaker_modulos'][$mod];

		//then we create the new module in the session
		$_SESSION['dbomaker_modulos'][$post_data['modulo']] = $new_module;

		$_SESSION['dbomaker_modulos'] = replacePlace($_SESSION['dbomaker_modulos'], $mod, $post_data['modulo']);

		//and destroy the previous.
		unset($_SESSION['dbomaker_modulos'][$mod]);

		$mod = $post_data['modulo'];
	}

	$_SESSION['dbomaker_modulos'][$mod]->titulo = stripslashes($post_data['titulo']);
	$_SESSION['dbomaker_modulos'][$mod]->titulo_plural = stripslashes($post_data['titulo_plural']);
	$_SESSION['dbomaker_modulos'][$mod]->modulo = stripslashes($post_data['modulo']);
	$_SESSION['dbomaker_modulos'][$mod]->tabela = stripslashes($post_data['tabela']);
	$_SESSION['dbomaker_modulos'][$mod]->paginacao = stripslashes($post_data['paginacao']);
	$_SESSION['dbomaker_modulos'][$mod]->genero = $post_data['genero'];

	$_SESSION['dbomaker_modulos'][$mod]->insert = (($post_data['insert'])?(true):(false));
	$_SESSION['dbomaker_modulos'][$mod]->update = (($post_data['update'])?(true):(false));
	$_SESSION['dbomaker_modulos'][$mod]->delete = (($post_data['delete'])?(true):(false));
	$_SESSION['dbomaker_modulos'][$mod]->preload_insert_form = (($post_data['preload_insert_form'])?(true):(false));
	$_SESSION['dbomaker_modulos'][$mod]->auto_view = (($post_data['auto_view'])?(true):(false));
	$_SESSION['dbomaker_modulos'][$mod]->ignore_permissions = (($post_data['ignore_permissions'])?(true):(false));

	$_SESSION['dbomaker_modulos'][$mod]->titulo_big_button = stripslashes($post_data['titulo_big_button']);
	$_SESSION['dbomaker_modulos'][$mod]->titulo_listagem = stripslashes($post_data['titulo_listagem']);
	$_SESSION['dbomaker_modulos'][$mod]->classes_listagem = stripslashes($post_data['classes_listagem']);
	$_SESSION['dbomaker_modulos'][$mod]->force_order_by = stripslashes($post_data['force_order_by']);
	$_SESSION['dbomaker_modulos'][$mod]->table_engine = stripslashes($post_data['table_engine']);
	$_SESSION['dbomaker_modulos'][$mod]->module_icon = stripslashes($post_data['module_icon']);
	$_SESSION['dbomaker_modulos'][$mod]->insert_button_text = stripslashes($post_data['insert_button_text']);

	if(strlen(trim($post_data['permissoes_custom'])))
	{
		$_SESSION['dbomaker_modulos'][$mod]->permissoes_custom = ident($post_data['permissoes_custom']);
	}
	else
	{
		unset($_SESSION['dbomaker_modulos'][$mod]->permissoes_custom);
	}

	if(strlen(trim($post_data['bibliotecas_js'])))
	{
		$_SESSION['dbomaker_modulos'][$mod]->bibliotecas_js = ident($post_data['bibliotecas_js']);
	}
	else
	{
		unset($_SESSION['dbomaker_modulos'][$mod]->bibliotecas_js);
	}

	if(strlen(trim($post_data['restricao'])))
	{
		$_SESSION['dbomaker_modulos'][$mod]->restricao = ident($post_data['restricao']);
	}
	else
	{
		unset($_SESSION['dbomaker_modulos'][$mod]->restricao);
	}

	$_SESSION['dbomaker_modulos'][$mod]->pre_insert    = stripslashes(trim($post_data['pre_insert']));
	$_SESSION['dbomaker_modulos'][$mod]->pos_insert    = stripslashes(trim($post_data['pos_insert']));
	$_SESSION['dbomaker_modulos'][$mod]->pre_update    = stripslashes(trim($post_data['pre_update']));
	$_SESSION['dbomaker_modulos'][$mod]->pos_update    = stripslashes(trim($post_data['pos_update']));
	$_SESSION['dbomaker_modulos'][$mod]->pre_delete    = stripslashes(trim($post_data['pre_delete']));
	$_SESSION['dbomaker_modulos'][$mod]->pos_delete    = stripslashes(trim($post_data['pos_delete']));
	$_SESSION['dbomaker_modulos'][$mod]->pre_list      = stripslashes(trim($post_data['pre_list']));
	$_SESSION['dbomaker_modulos'][$mod]->pos_list      = stripslashes(trim($post_data['pos_list']));
	$_SESSION['dbomaker_modulos'][$mod]->notifications = stripslashes(trim($post_data['notifications']));
	$_SESSION['dbomaker_modulos'][$mod]->overview      = stripslashes(trim($post_data['overview']));

	//buttons

	unset($_SESSION['dbomaker_modulos'][$mod]->button);

	if(is_array($post_data['button']))
	{
		foreach($post_data['button'] as $key => $button)
		{
			$but = new Obj();
			if($button['custom'] == 1)
			{
				$but->value = $button['value_custom'];
				$but->code = ident($button['code']);
				$but->custom = true;
			}
			else
			{
				$but->value = $button['value'];
				$but->modulo = $button['modulo'];
				$but->modulo_fk = $button['modulo_fk'];
				$but->key = $button['key'];
				$but->view = (($button['view'] == 1)?(true):(false));
				$but->show = (($button['show'] == 1)?(true):(false));
				$but->subsection = (($button['subsection'] == 1)?(true):(false));
				$but->autoload = (($button['autoload'] == 1)?(true):(false));
			}
			$_SESSION['dbomaker_modulos'][$mod]->button[] = $but;
		}
	}
	else
	{
		unset($_SESSION['dbomaker_modulos'][$mod]->button);
	}

	//grid
	$grid = array();
	//general
	if(strlen(trim($post_data['grid_general'])))
	{
		$linhas = explode("\n", unixEOL(trim($post_data['grid_general'])));
		foreach($linhas as $key => $linha)
		{
			$elementos = explode(",", $linha);
			$grid[] = $elementos;
		}
	}
	//general-view
	if(strlen(trim($post_data['grid_general_view'])))
	{
		$linhas = explode("\n", unixEOL(trim($post_data['grid_general_view'])));
		foreach($linhas as $key => $linha)
		{
			$elementos = explode(",", $linha);
			$grid['view'][] = $elementos;
		}
	}

	unset($_SESSION['dbomaker_modulos'][$mod]->grid);
	$_SESSION['dbomaker_modulos'][$mod]->grid = $grid;

	flagUpdate($post_data['modulo']);
	echo "RUN_UPDATE_MODULE_OK::".$post_data['modulo'];
}

function runNewModule()
{
	$tk = 'temporary_module_key_5658';

	$module = new Obj();
	$module->titulo = "&nbsp;";
	$module->modulo = $tk;
	$module->paginacao = 20;
	$module->genero = 'a';
	$module->insert = true;
	$module->update = true;
	$module->delete = true;
	$module->order_by = maxModuleOrderBy()+1;

	$_SESSION['dbomaker_modulos'][$tk] = $module;

	showModule($tk);
}

function syncAll()
{
	syncDeleted();
	if(is_array($_SESSION['dbomaker_modulos']))
	{
		foreach($_SESSION['dbomaker_modulos'] as $key => $module)
		{
			if($key != 'temporary_module_key_5658' && !$module->dbo_maker_read_only)
			{
				syncModule($module->modulo);
			}
		}
		echo "SUCESSO";
	}
}

function syncUpdated()
{
	syncDeleted();
	if(is_array($_SESSION['dbomaker_updated']))
	{
		foreach($_SESSION['dbomaker_updated'] as $key => $module)
		{
			syncModule($module);
			unflagUpdate($module);
		}
	}
}

function syncDeleted()
{
	if(is_array($_SESSION['dbomaker_deleted']))
	{
		foreach($_SESSION['dbomaker_deleted'] as $key => $module)
		{
			backupModule($module);
			diskDelete($module);
			unflagDelete($module);
			unset($_SESSION['dbomaker_modulo'][$module]);
		}
	}
}

function backupModule($mod)
{
	$prefix = date('Y-m-d_H-i-s');
	$module_file = "_dbo_".$_SESSION['dbomaker_modulos'][$mod]->modulo.".php";

	if(file_exists('../'.$module_file))
	{
		if(copy('../'.$module_file, '../module_backups/'.$prefix.$module_file))
		{
			return true;
		}
		return false;
	}
	return true;
}

function syncModule($mod)
{
	if(backupModule($mod))
	{
		if(writeModuleFile($mod))
		{
			writeModuleClassFile($mod);
		}
		else
		{
			//echo "ERRO AO CRIAR O ARQUIVO";
		}
	}
	else
	{
		echo "ERROR::Erro ao salvar o arquivo de backup.";
	}
}

function writeModuleClassFile($mod)
{
	$file_prefix = '_class_';
	$file_sufix = '.php';

	$module = $_SESSION['dbomaker_modulos'][$mod];

	if(!file_exists('../'.$file_prefix.$mod.$file_sufix))
	{
		if($fh = fopen('../'.$file_prefix.$mod.$file_sufix, 'w'))
		{
			fwrite($fh, "<?\n");
			fwrite($fh, "\n");
			fwrite($fh, "/* ================================================================================================================== */\n");
			fwrite($fh, "/* DBO CLASS FILE FOR MODULE ".str_pad("'".$module->modulo."' ", 52, "=", STR_PAD_RIGHT)." AUTO-CREATED ON ".date('d/m/Y H:i:s')." */\n");
			fwrite($fh, "/* ================================================================================================================== */\n");
			fwrite($fh, "\n");
			fwrite($fh, "/* IMPORTANT: This file is generated only in the first DBO sync, what means you should edit only via text editor. */\n");
			fwrite($fh, "\n");

			/* WRITES THE CLASS DECLARATION */

			fwrite($fh, "if(!class_exists('".$mod."'))\n");
			fwrite($fh, "{\n");
			fwrite($fh, "\tclass ".$mod." extends dbo\n");
			fwrite($fh, "\t{\n");
			fwrite($fh, "\t\t/* smart constructor: will perform load() upon numeric argument and loadAll() upon string argument */\n");
			fwrite($fh, "\t\tfunction __construct(\$foo = '')\n");
			fwrite($fh, "\t\t{\n");
			fwrite($fh, "\t\t\tparent::__construct('".$mod."');\n");
			fwrite($fh, "\t\t\tif(\$foo != '')\n");
			fwrite($fh, "\t\t\t{\n");
			fwrite($fh, "\t\t\t\tif(is_numeric(\$foo))\n");
			fwrite($fh, "\t\t\t\t{\n");
			fwrite($fh, "\t\t\t\t\t\$this->id = \$foo;\n");
			fwrite($fh, "\t\t\t\t\t\$this->load();\n");
			fwrite($fh, "\t\t\t\t}\n");
			fwrite($fh, "\t\t\t\telseif(is_string(\$foo))\n");
			fwrite($fh, "\t\t\t\t{\n");
			fwrite($fh, "\t\t\t\t\t\$this->loadAll(\$foo);\n");
			fwrite($fh, "\t\t\t\t}\n");
			fwrite($fh, "\t\t\t}\n");
			fwrite($fh, "\t\t}\n");
			fwrite($fh, "\n");
			fwrite($fh, "\t\t//your methods here\n");
			fwrite($fh, "\n");
			fwrite($fh, "\t} //class declaration\n");
			fwrite($fh, "} //if ! class exists\n");

			/* EOF */

			fwrite($fh, "\n");
			fwrite($fh, "?>");

			if(fclose($fh))
			{
				return true;
			}
		} // fopen
	} // file-exists
	return true;
}

function writeModuleFile($mod)
{
	$file_prefix = '_dbo_';
	$file_sufix = '.php';

	$module = $_SESSION['dbomaker_modulos'][$mod];

	if($module->imported_module) return true;

	if($fh = fopen('../'.$file_prefix.$mod.$file_sufix, 'w'))
	{
		fwrite($fh, "<?\n");
		fwrite($fh, "\n");
		fwrite($fh, "/* ================================================================================================================== */\n");
		fwrite($fh, "/* DBO DEFINITION FILE FOR MODULE ".str_pad("'".$module->modulo."' ", 47, "=", STR_PAD_RIGHT)." AUTO-CREATED ON ".date('d/m/Y H:i:s')." */\n");
		fwrite($fh, "/* ================================================================================================================== */\n");

		/* WRITES THE MAIN DEFINITION */

		fwrite($fh, "\n");
		fwrite($fh, "\n");
		fwrite($fh, "\n");
		fwrite($fh, "/* GENERAL MODULE DEFINITIONS ======================================================================================= */\n");
		fwrite($fh, "\n");

		fwrite($fh, "\$module = new Obj();\n");
		fwrite($fh, "\$module->modulo = '".singleScape($module->modulo)."';\n");
		fwrite($fh, "\$module->tabela = '".singleScape($module->tabela)."';\n");
		fwrite($fh, "\$module->titulo = '".singleScape($module->titulo)."';\n");
		fwrite($fh, "\$module->titulo_plural = '".singleScape($module->titulo_plural)."';\n");
		if(strlen($module->titulo_big_button))
		{
			fwrite($fh, "\$module->titulo_big_button = '".singleScape($module->titulo_big_button)."';\n");
		}
		if(strlen($module->titulo_listagem))
		{
			fwrite($fh, "\$module->titulo_listagem = '".singleScape($module->titulo_listagem)."';\n");
		}
		if(strlen($module->classes_listagem))
		{
			fwrite($fh, "\$module->classes_listagem = '".singleScape($module->classes_listagem)."';\n");
		}
		if(strlen($module->module_icon))
		{
			fwrite($fh, "\$module->module_icon = '".singleScape($module->module_icon)."';\n");
		}
		if(strlen($module->insert_button_text))
		{
			fwrite($fh, "\$module->insert_button_text = '".singleScape($module->insert_button_text)."';\n");
		}
		fwrite($fh, "\$module->genero = '".$module->genero."';\n");
		fwrite($fh, "\$module->paginacao = '".singleScape($module->paginacao)."';\n");
		fwrite($fh, "\$module->update = ".(($module->update)?('true'):('false')).";\n");
		fwrite($fh, "\$module->delete = ".(($module->delete)?('true'):('false')).";\n");
		fwrite($fh, "\$module->insert = 'Nov".($module->genero)." ".singleScape($module->titulo)."';\n");
		fwrite($fh, "\$module->preload_insert_form = ".(($module->preload_insert_form)?('true'):('false')).";\n");
		fwrite($fh, "\$module->auto_view = ".(($module->auto_view)?('true'):('false')).";\n");
		fwrite($fh, "\$module->ignore_permissions = ".(($module->ignore_permissions)?('true'):('false')).";\n");
		if(strlen($module->permissoes_custom))
		{
			fwrite($fh, "\$module->permissoes_custom = '\n");
			$permissoes_custom = singleScape("\t".trim(unixEOL($module->permissoes_custom)));
			fwrite($fh, $permissoes_custom."\n");
			fwrite($fh, "';\n");
		}
		if(strlen($module->bibliotecas_js))
		{
			fwrite($fh, "\$module->bibliotecas_js = '\n");
			$bibliotecas_js = singleScape("\t".trim(unixEOL($module->bibliotecas_js)));
			fwrite($fh, $bibliotecas_js."\n");
			fwrite($fh, "';\n");
		}
		if(strlen($module->restricao))
		{
			fwrite($fh, "\$module->restricao = '\n");
			$restricao = singleScape("\t".trim(unixEOL($module->restricao)));
			fwrite($fh, $restricao."\n");
			fwrite($fh, "';\n");
		}
		if(strlen($module->force_order_by))
		{
			fwrite($fh, "\$module->force_order_by = '".singleScape($module->force_order_by)."';\n");
		}
		fwrite($fh, "\$module->order_by = '".($module->force_order_by ? $module->force_order_by : $module->order_by)."';\n");
		if(strlen($module->table_engine))
		{
			fwrite($fh, "\$module->table_engine = '".singleScape($module->table_engine)."';\n");
		}

		/* WRITES THE FIELD DEFINITIONS */

		fwrite($fh, "\n");
		fwrite($fh, "/* FIELDS =========================================================================================================== */\n");
		fwrite($fh, "\n");

		if(is_array($module->campo))
		{
			foreach($module->campo as $field)
			{
				if($field->coluna != 'temporary_field_key_5658')
				{
					fwrite($fh, "\$field = new Obj();\n");
					fwrite($fh, "\$field->titulo = '".((strlen($field->titulo))?(singleScape($field->titulo)):('&nbsp;'))."';\n");
					fwrite($fh, "\$field->coluna = '".$field->coluna."';\n");
					if(strlen($field->label_display))
					{
						fwrite($fh, "\$field->label_display = '".singleScape($field->label_display)."';\n");
					}
					if(strlen($field->titulo_listagem))
					{
						fwrite($fh, "\$field->titulo_listagem = '".singleScape($field->titulo_listagem)."';\n");
					}
					if(strlen($field->dica))
					{
						fwrite($fh, "\$field->dica = '".singleScape($field->dica)."';\n");
					}
					if(strlen($field->default))
					{
						fwrite($fh, "\$field->default = '".singleScape($field->default)."';\n");
					}
					if(sizeof($field->perfil))
					{
						fwrite($fh, "\$field->perfil = array('".implode("','", $field->perfil)."');\n");
					}
					fwrite($fh, "\$field->pk = ".(($field->pk === true)?('true'):('false')).";\n");
					fwrite($fh, "\$field->isnull = ".(($field->isnull === true)?('true'):('false')).";\n");
					fwrite($fh, "\$field->add = ".(($field->add === true)?('true'):('false')).";\n");
					fwrite($fh, "\$field->valida = ".(($field->valida === true)?('true'):('false')).";\n");
					fwrite($fh, "\$field->edit = ".(($field->edit === true)?('true'):('false')).";\n");
					fwrite($fh, "\$field->view = ".(($field->view === true)?('true'):('false')).";\n");
					fwrite($fh, "\$field->lista = ".(($field->lista === true)?('true'):('false')).";\n");
					fwrite($fh, "\$field->filter = ".(($field->filter === true)?('true'):('false')).";\n");
					fwrite($fh, "\$field->order = ".(($field->order === true)?('true'):('false')).";\n");
					fwrite($fh, "\$field->type = '".(($field->tipo == 'pk')?('INT NOT NULL auto_increment'):($field->type))."';\n");
					fwrite($fh, "\$field->interaction = '".$field->interaction."';\n");
					if(strlen($field->list_function))
					{
						fwrite($fh, "\$field->list_function = '".singleScape($field->list_function)."';\n");
					}
					if(strlen($field->edit_function))
					{
						fwrite($fh, "\$field->edit_function = '".singleScape($field->edit_function)."';\n");
					}
					if(strlen($field->default_value))
					{
						fwrite($fh, "\$field->default_value = '".singleScape($field->default_value)."';\n");
					}
					if(strlen($field->mask) || $field->tipo == 'date')
					{
						if($field->tipo == 'date')
						{
							fwrite($fh, "\$field->mask = '99/99/9999';\n");
						}
						else
						{
							fwrite($fh, "\$field->mask = '".singleScape($field->mask)."';\n");
						}
					}
					if(strlen($field->classes))
					{
						fwrite($fh, "\$field->classes = '".singleScape($field->classes)."';\n");
					}
					if(strlen($field->styles))
					{
						fwrite($fh, "\$field->styles = '".singleScape($field->styles)."';\n");
					}
					fwrite($fh, "\$field->tipo = '".$field->tipo."';\n");

					//writes the type specific definitions
					//textareas
					if($field->tipo == 'textarea' || $field->tipo == 'textarea-rich')
					{
						if(strlen($field->rows))
						{
							fwrite($fh, "\$field->rows = ".$field->rows.";\n");
						}
					}
					//content-tools
					elseif($field->tipo == 'content-tools')
					{
						if(is_array($field->params))
						{
							fwrite($fh, "\$field->params = array(\n");
							foreach($field->params as $key => $value)
							{
								fwrite($fh, "\t'".$key."' => ".(($value === true)?("true"):((($value === false)?("false"):("'".singleScape($value)."'")))).",\n");
							}
							fwrite($fh, ");\n");
						}
					}
					//select, radio, checkbox
					elseif($field->tipo == 'select' || $field->tipo == 'radio' || $field->tipo == 'checkbox')
					{
						fwrite($fh, "\$field->valores = array(\n");
						if(is_array($field->valores))
						{
							foreach($field->valores as $key => $value)
							{
								fwrite($fh, "\t'".$key."' => '".$value."',\n");
							}
						}
						fwrite($fh, ");\n");
					}
					//price
					elseif($field->tipo == 'price')
					{
						fwrite($fh, "\$field->formato = '".$field->formato."';\n");
					}
					//image
					elseif($field->tipo == 'image')
					{
						if($field->allow_canvas_expansion)
						{
							fwrite($fh, "\$field->allow_canvas_expansion = true;\n");
						}
						if(is_array($field->image))
						{
							foreach($field->image as $key => $value)
							{
								fwrite($fh, "\t\$image = new Obj();\n");
								if($value->width)
								{
									fwrite($fh, "\t\$image->width = ".$value->width.";\n");
								}
								if($value->height)
								{
									fwrite($fh, "\t\$image->height = ".$value->height.";\n");
								}
								fwrite($fh, "\t\$image->prefix = '".$value->prefix."';\n");
								fwrite($fh, "\t\$image->quality = ".$value->quality.";\n");
								fwrite($fh, "\$field->image[] = \$image;\n");
							}
						}
					}
					//file
					elseif($field->tipo == 'file')
					{
						fwrite($fh, "\t\$file = new Obj();\n");
						fwrite($fh, "\$field->file = \$file;\n");
					}
					//midia
					if($field->tipo == 'media')
					{
						if(strlen($field->formatos))
						{
							fwrite($fh, "\$field->formatos = ".$field->formatos.";\n");
						}
					}
					//join
					elseif($field->tipo == 'join' || $field->tipo == 'joinNN')
					{
						fwrite($fh, "\t\$join = new Obj();\n");
						fwrite($fh, "\t\$join->modulo = '".$field->join->modulo."';\n");
						fwrite($fh, "\t\$join->chave = '".$field->join->chave."';\n");
						fwrite($fh, "\t\$join->valor = '".$field->join->valor."';\n");

						//constraints
						if(strlen(trim($field->join->on_update)))
						{
							fwrite($fh, "\t\$join->on_update = '".$field->join->on_update."';\n");
						}
						if(strlen(trim($field->join->on_delete)))
						{
							fwrite($fh, "\t\$join->on_delete = '".$field->join->on_delete."';\n");
						}
						
						//checando se vai ajax
						if($field->join->ajax == 1)
						{
							fwrite($fh, "\t\$join->ajax = true;\n");
						}

						//checando se vai select2
						if($field->join->select2 == 1)
						{
							fwrite($fh, "\t\$join->select2 = true;\n");
						}

						if($field->tipo == 'joinNN')
						{
							fwrite($fh, "\t\$join->tabela_ligacao = '".$field->join->tabela_ligacao."';\n");
							fwrite($fh, "\t\$join->chave1 = '".$field->join->chave1."';\n");
							fwrite($fh, "\t\$join->chave2 = '".$field->join->chave2."';\n");
							//checando se foi definido chave_pk
							if(strlen(trim($field->join->chave1_pk)))
							{
								fwrite($fh, "\t\$join->chave1_pk = '".$field->join->chave1_pk."';\n");
							}
							if(strlen(trim($field->join->chave2_pk)))
							{
								fwrite($fh, "\t\$join->chave2_pk = '".$field->join->chave2_pk."';\n");
							}
							if(strlen(trim($field->join->relacao_adicional_coluna)))
							{
								fwrite($fh, "\t\$join->relacao_adicional_coluna = '".$field->join->relacao_adicional_coluna."';\n");
							}
							if(strlen(trim($field->join->relacao_adicional_funcao)))
							{
								fwrite($fh, "\t\$join->relacao_adicional_funcao = '".$field->join->relacao_adicional_funcao."';\n");
							}

							//constraints
							if(strlen(trim($field->join->chave1_on_update)))
							{
								fwrite($fh, "\t\$join->chave1_on_update = '".$field->join->chave1_on_update."';\n");
							}
							if(strlen(trim($field->join->chave1_on_delete)))
							{
								fwrite($fh, "\t\$join->chave1_on_delete = '".$field->join->chave1_on_delete."';\n");
							}
							if(strlen(trim($field->join->chave2_on_update)))
							{
								fwrite($fh, "\t\$join->chave2_on_update = '".$field->join->chave2_on_update."';\n");
							}
							if(strlen(trim($field->join->chave2_on_delete)))
							{
								fwrite($fh, "\t\$join->chave2_on_delete = '".$field->join->chave2_on_delete."';\n");
							}
						}
						//checando se foi definido um metodo de retorno
						if(strlen(trim($field->join->metodo_retorno)))
						{
							fwrite($fh, "\t\$join->metodo_retorno = '".$field->join->metodo_retorno."';\n");
						}
						//checando se foi definido um metodo de retorno
						if(strlen(trim($field->join->metodo_listagem)))
						{
							fwrite($fh, "\t\$join->metodo_listagem = '".$field->join->metodo_listagem."';\n");
						}
						//checando se foi definido um metodo de retorno
						if(strlen(trim($field->join->tamanho_minimo)))
						{
							fwrite($fh, "\t\$join->tamanho_minimo = '".$field->join->tamanho_minimo."';\n");
						}
						fwrite($fh, "\t\$join->tipo = '".$field->join->tipo."';\n");
						fwrite($fh, "\t\$join->order_by = '".$field->join->order_by."';\n");
						fwrite($fh, "\$field->join = \$join;\n");
					}
					//plugin
					elseif($field->tipo == 'plugin')
					{
						fwrite($fh, "\t\$plugin = new Obj();\n");
						fwrite($fh, "\t\$plugin->name = '".$field->plugin->name."';\n");
						if(is_array($field->plugin->params))
						{
							fwrite($fh, "\t\$plugin->params = array(\n");
							foreach($field->plugin->params as $key => $value)
							{
								fwrite($fh, "\t\t'".$key."' => ".(($value === true)?("true"):((($value === false)?("false"):("'".singleScape($value)."'")))).",\n");
							}
							fwrite($fh, "\t);\n");
						}
						fwrite($fh, "\$field->plugin = \$plugin;\n");
					}
					//plugin
					elseif($field->tipo == 'query')
					{
						fwrite($fh, "\$field->query = '\n");
						$query = singleScape("\t".trim(unixEOL($field->query)));
						fwrite($fh, $query."\n");
						fwrite($fh, "';\n");
					}

					//writing the field restriction
					if(strlen($field->restricao))
					{
						fwrite($fh, "\$field->restricao = '\n");
						$restricao = singleScape("\t".trim(unixEOL($field->restricao)));
						fwrite($fh, $restricao."\n");
						fwrite($fh, "';\n");
					}

					fwrite($fh, "\$module->campo[\$field->coluna] = \$field;\n");
					fwrite($fh, "\n");
					fwrite($fh, "/*==========================================*/\n");
					fwrite($fh, "\n");


				}//is not a temp key
			}//foreach field
		}//if there is fields

		/* WRITES THE GRID DEFINITION, IF EXISTANT */

		fwrite($fh, "/* GRID FOR THE FORM LAYOUT ========================================================================================= */\n");
		fwrite($fh, "\n");

		$grade = array();
		if(is_array($module->grid))
		{

			fwrite($fh, "\$grid = array();\n");
			fwrite($fh, "\n");

			//basic grid
			foreach($module->grid as $key => $linha)
			{
				if(is_numeric($key))
				{
					fwrite($fh, "\$grid[] = array('".implode("','", unixEOL($linha))."');\n");
				}
			}
			fwrite($fh, "\n");

			$break_view = false;
			foreach($module->grid as $key => $view)
			{
				if(!is_numeric($key) && $key == 'view')
				{
					$break_view = true;
					foreach($view as $linha)
					{
						fwrite($fh, "\$grid['view'][] = array('".implode("','", unixEOL($linha))."');\n");
					}
				}
			}
			if($break_view) { fwrite($fh, "\n"); }

			fwrite($fh, "\$module->grid = \$grid;\n");
			fwrite($fh, "\n");

		} //if there is a grid

		/* WRITES BUTTONS DEFINITIONS, IF EXISTANT */

		fwrite($fh, "/* MODULE LIST BUTTONS ============================================================================================== */\n");
		fwrite($fh, "\n");

		if(is_array($module->button))
		{
			foreach($module->button as $button)
			{
				fwrite($fh, "\$button = new Obj();\n");
				fwrite($fh, "\$button->value = '".$button->value."';\n");
				if($button->custom)
				{
					fwrite($fh, "\$button->custom = true;\n");
					fwrite($fh, "\$button->code = '\n");
					$code = singleScape("\t".trim(unixEOL($button->code)));
					fwrite($fh, $code."\n");
					fwrite($fh, "';\n");
				}
				else
				{
					fwrite($fh, "\$button->modulo = '".$button->modulo."';\n");
					fwrite($fh, "\$button->modulo_fk = '".$button->modulo_fk."';\n");
					fwrite($fh, "\$button->key = '".$button->key."';\n");
					fwrite($fh, "\$button->view = ".(($button->view === true)?('true'):('false')).";\n");
					fwrite($fh, "\$button->show = ".(($button->show === true)?('true'):('false')).";\n");
					fwrite($fh, "\$button->subsection = ".(($button->subsection === true)?('true'):('false')).";\n");
					fwrite($fh, "\$button->autoload = ".(($button->autoload === true)?('true'):('false')).";\n");
				}
				fwrite($fh, "\$module->button[] = \$button;\n");
				fwrite($fh, "\n");
			}
		}

		/* WRITES THE TRIGGERS */

		fwrite($fh, "/* FUNÇÕES AUXILIARES =============================================================================================== */\n");
		fwrite($fh, "\n");

		//pre-insert
		fwrite($fh, "if(!function_exists('".$module->modulo."_pre_insert'))\n");
		fwrite($fh, "{\n");
		fwrite($fh, "	function ".$module->modulo."_pre_insert () // there is nothing to get as a parameter, right?\n");
		fwrite($fh, "	{ global \$dbo;\n");
		fwrite($fh, "	// ----------------------------------------------------------------------------------------------------------\n");
		fwrite($fh, "\n");
		if(strlen($module->pre_insert))
		{
			$code = trim(unixEOL($module->pre_insert));
			$lines = explode("\n", $code);
			foreach($lines as $line)
			{
				fwrite($fh, "\t\t".$line."\n");
			}
		}
		else
		{
			fwrite($fh, "\n");
		}
		fwrite($fh, "\n");
		fwrite($fh, "	// ----------------------------------------------------------------------------------------------------------\n");
		fwrite($fh, "	}\n");
		fwrite($fh, "}\n");
		fwrite($fh, "\n");

		//pos-insert
		fwrite($fh, "if(!function_exists('".$module->modulo."_pos_insert'))\n");
		fwrite($fh, "{\n");
		fwrite($fh, "	function ".$module->modulo."_pos_insert (\$obj) // active just inserted object\n");
		fwrite($fh, "	{ global \$dbo;\n");
		fwrite($fh, "	// ----------------------------------------------------------------------------------------------------------\n");
		fwrite($fh, "\n");
		if(strlen($module->pos_insert))
		{
			$code = trim(unixEOL($module->pos_insert));
			$lines = explode("\n", $code);
			foreach($lines as $line)
			{
				fwrite($fh, "\t\t".$line."\n");
			}
		}
		else
		{
			fwrite($fh, "\n");
		}
		fwrite($fh, "\n");
		fwrite($fh, "	// ----------------------------------------------------------------------------------------------------------\n");
		fwrite($fh, "	}\n");
		fwrite($fh, "}\n");
		fwrite($fh, "\n");

		//pre-update
		fwrite($fh, "if(!function_exists('".$module->modulo."_pre_update'))\n");
		fwrite($fh, "{\n");
		fwrite($fh, "	function ".$module->modulo."_pre_update (\$obj) // active object\n");
		fwrite($fh, "	{ global \$dbo;\n");
		fwrite($fh, "	// ----------------------------------------------------------------------------------------------------------\n");
		fwrite($fh, "\n");
		if(strlen($module->pre_update))
		{
			$code = trim(unixEOL($module->pre_update));
			$lines = explode("\n", $code);
			foreach($lines as $line)
			{
				fwrite($fh, "\t\t".$line."\n");
			}
		}
		else
		{
			fwrite($fh, "\n");
		}
		fwrite($fh, "\n");
		fwrite($fh, "	// ----------------------------------------------------------------------------------------------------------\n");
		fwrite($fh, "	}\n");
		fwrite($fh, "}\n");
		fwrite($fh, "\n");

		//pos-update
		fwrite($fh, "if(!function_exists('".$module->modulo."_pos_update'))\n");
		fwrite($fh, "{\n");
		fwrite($fh, "	function ".$module->modulo."_pos_update (\$obj) // active updated object\n");
		fwrite($fh, "	{ global \$dbo;\n");
		fwrite($fh, "	// ----------------------------------------------------------------------------------------------------------\n");
		fwrite($fh, "\n");
		if(strlen($module->pos_update))
		{
			$code = trim(unixEOL($module->pos_update));
			$lines = explode("\n", $code);
			foreach($lines as $line)
			{
				fwrite($fh, "\t\t".$line."\n");
			}
		}
		else
		{
			fwrite($fh, "\n");
		}
		fwrite($fh, "\n");
		fwrite($fh, "	// ----------------------------------------------------------------------------------------------------------\n");
		fwrite($fh, "	}\n");
		fwrite($fh, "}\n");
		fwrite($fh, "\n");

		//pre-delete
		fwrite($fh, "if(!function_exists('".$module->modulo."_pre_delete'))\n");
		fwrite($fh, "{\n");
		fwrite($fh, "	function ".$module->modulo."_pre_delete (\$obj) // active object\n");
		fwrite($fh, "	{ global \$dbo;\n");
		fwrite($fh, "	// ----------------------------------------------------------------------------------------------------------\n");
		fwrite($fh, "\n");
		if(strlen($module->pre_delete))
		{
			$code = trim(unixEOL($module->pre_delete));
			$lines = explode("\n", $code);
			foreach($lines as $line)
			{
				fwrite($fh, "\t\t".$line."\n");
			}
		}
		else
		{
			fwrite($fh, "\n");
		}
		fwrite($fh, "\n");
		fwrite($fh, "	// ----------------------------------------------------------------------------------------------------------\n");
		fwrite($fh, "	}\n");
		fwrite($fh, "}\n");
		fwrite($fh, "\n");

		//pos-delete
		fwrite($fh, "if(!function_exists('".$module->modulo."_pos_delete'))\n");
		fwrite($fh, "{\n");
		fwrite($fh, "	function ".$module->modulo."_pos_delete (\$obj) // active deleted object\n");
		fwrite($fh, "	{ global \$dbo;\n");
		fwrite($fh, "	// ----------------------------------------------------------------------------------------------------------\n");
		fwrite($fh, "\n");
		if(strlen($module->pos_delete))
		{
			$code = trim(unixEOL($module->pos_delete));
			$lines = explode("\n", $code);
			foreach($lines as $line)
			{
				fwrite($fh, "\t\t".$line."\n");
			}
		}
		else
		{
			fwrite($fh, "\n");
		}
		fwrite($fh, "\n");
		fwrite($fh, "	// ----------------------------------------------------------------------------------------------------------\n");
		fwrite($fh, "	}\n");
		fwrite($fh, "}\n");
		fwrite($fh, "\n");

		//pre-list
		fwrite($fh, "if(!function_exists('".$module->modulo."_pre_list'))\n");
		fwrite($fh, "{\n");
		fwrite($fh, "	function ".$module->modulo."_pre_list () // nothing to be passed here...\n");
		fwrite($fh, "	{ global \$dbo;\n");
		fwrite($fh, "	// ----------------------------------------------------------------------------------------------------------\n");
		fwrite($fh, "\n");
		if(strlen($module->pre_list))
		{
			$code = trim(unixEOL($module->pre_list));
			$lines = explode("\n", $code);
			foreach($lines as $line)
			{
				fwrite($fh, "\t\t".$line."\n");
			}
		}
		else
		{
			fwrite($fh, "\n");
		}
		fwrite($fh, "\n");
		fwrite($fh, "	// ----------------------------------------------------------------------------------------------------------\n");
		fwrite($fh, "	}\n");
		fwrite($fh, "}\n");
		fwrite($fh, "\n");

		//pos-list
		fwrite($fh, "if(!function_exists('".$module->modulo."_pos_list'))\n");
		fwrite($fh, "{\n");
		fwrite($fh, "	function ".$module->modulo."_pos_list (\$ids) // ids of the listed elements\n");
		fwrite($fh, "	{ global \$dbo;\n");
		fwrite($fh, "	// ----------------------------------------------------------------------------------------------------------\n");
		fwrite($fh, "\n");
		if(strlen($module->pos_list))
		{
			$code = trim(unixEOL($module->pos_list));
			$lines = explode("\n", $code);
			foreach($lines as $line)
			{
				fwrite($fh, "\t\t".$line."\n");
			}
		}
		else
		{
			fwrite($fh, "\n");
		}
		fwrite($fh, "\n");
		fwrite($fh, "	// ----------------------------------------------------------------------------------------------------------\n");
		fwrite($fh, "	}\n");
		fwrite($fh, "}\n");
		fwrite($fh, "\n");

		//notifications
		fwrite($fh, "if(!function_exists('".$module->modulo."_notifications'))\n");
		fwrite($fh, "{\n");
		fwrite($fh, "	function ".$module->modulo."_notifications (\$type = '')\n");
		fwrite($fh, "	{ global \$dbo;\n");
		fwrite($fh, "	// ----------------------------------------------------------------------------------------------------------\n");
		fwrite($fh, "\n");
		if(strlen($module->notifications))
		{
			$code = trim(unixEOL($module->notifications));
			$lines = explode("\n", $code);
			foreach($lines as $line)
			{
				fwrite($fh, "\t\t".$line."\n");
			}
		}
		else
		{
			fwrite($fh, "\n");
		}
		fwrite($fh, "\n");
		fwrite($fh, "	// ----------------------------------------------------------------------------------------------------------\n");
		fwrite($fh, "	}\n");
		fwrite($fh, "}\n");
		fwrite($fh, "\n");

		//overview
		fwrite($fh, "if(!function_exists('".$module->modulo."_overview'))\n");
		fwrite($fh, "{\n");
		fwrite($fh, "	function ".$module->modulo."_overview (\$foo)\n");
		fwrite($fh, "	{ global \$dbo;\n");
		fwrite($fh, "	// ----------------------------------------------------------------------------------------------------------\n");
		fwrite($fh, "\n");
		if(strlen($module->overview))
		{
			$code = trim(unixEOL($module->overview));
			$lines = explode("\n", $code);
			foreach($lines as $line)
			{
				fwrite($fh, "\t\t".$line."\n");
			}
		}
		else
		{
			fwrite($fh, "\n");
		}
		fwrite($fh, "\n");
		fwrite($fh, "	// ----------------------------------------------------------------------------------------------------------\n");
		fwrite($fh, "	}\n");
		fwrite($fh, "}\n");
		fwrite($fh, "\n");

		/* EOF */

		fwrite($fh, "\n");
		fwrite($fh, "?>");

		if(fclose($fh))
		{
			return true;
		}
	}
	return false;
}

function sortModules($data)
{
	foreach($data['module'] as $order_by => $module)
	{
		$module = decNameAjax($module);
		$_SESSION['dbomaker_modulos'][$module]->order_by = $order_by;
		flagUpdate($module);
	}
}

function sortFields($data)
{
	$new_order = array();
	foreach($data['field'] as $field_key)
	{
		$field_key = decNameAjax($field_key);
		$new_order[$field_key] = $_SESSION['dbomaker_modulos'][$data['module']]->campo[$field_key];
	}
	$_SESSION['dbomaker_modulos'][$data['module']]->campo = $new_order;
	flagUpdate($data['module']);
}

function deleteModule($module)
{
	flagDelete($module);
}

function deleteField($data)
{
	unset($_SESSION['dbomaker_modulos'][$data['module']]->campo[$data['field']]);
	flagUpdate($data['module']);
}

function syncDatabase()
{
	global $_system;

	//try to create the tables.
	foreach($_SESSION['dbomaker_modulos'] as $module)
	{
		if(!in_array($module->modulo, (array)$_system['module_blacklist']))
		{
			syncTable($module);
		}
	}
}

?>