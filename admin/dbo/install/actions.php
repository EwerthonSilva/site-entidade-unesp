<? include('includes.php') ?>
<?

if($_GET['step'] == 1) { step1(); }
if($_GET['step'] == 2) { step2(); }
if($_GET['step'] == 3) { step3(); }
if($_GET['step'] == 4) { step4(); }

if($_GET['getStatus']) { getStatus($_GET['getStatus']); }

if($_GET['registerDatabase']) { registerDatabase(); }
if($_GET['registerAdminInformation']) { registerAdminInformation(); }
if($_GET['registerSystemInformation']) { registerSystemInformation(); }
if($_GET['registerSystemColors']) { registerSystemColors(); }

if($_GET['showStatusBar']) { showStatusBar(); }
if($_GET['showStatusReport']) { showStatusReport(); }

if($_GET['purgeInstallData']) { purgeInstallData(); }

function getStatus($step)
{
	if($step == 'step1')
	{
		echo "ok";
	}
	if($step == 'step2')
	{
		if(checkDatabase() === true && validateDbFile())
		{
			echo "ok";
		}
		else
		{
			echo "fail";
		}
	}
	if($step == 'step3')
	{
		if(checkDatabase() === true && validateDBFile() && getAdmins())
		{
			echo "ok";
		}
		else
		{
			echo "fail";
		}
	}
	if($step == 'step4')
	{
		if(checkDatabase() === true && validateDBFile() && getAdmins() && getDefines() && validateDefinesFile())
		{
			echo "ok";
		}
		else
		{
			echo "fail";
		}
	}
}

function step1()
{
	?>
		<div class='form'>
			<form id='form-step1' action='actions.php?registerDatabase=1' method='POST' rel='#content'>

			<h2><?= STEP1_INSTRUCTIONS ?></h2>

			<div class='row'>
				<div class='item'>
					<label><?= STEP1_DB_HOST ?></label>
					<div class='input'><input type='text' name='DB_HOST' value="<?= htmlspecialchars($_SESSION['dbo_install']['DB_HOST']) ?>"/></div>
				</div><!-- item -->
			</div><!-- row -->

			<div class='row'>
				<div class='item'>
					<label><?= STEP1_DB_USER ?></label>
					<div class='input'><input type='text' name='DB_USER' value="<?= htmlspecialchars($_SESSION['dbo_install']['DB_USER']) ?>"/></div>
				</div><!-- item -->
			</div><!-- row -->

			<div class='row'>
				<div class='item'>
					<label><?= STEP1_DB_PASS ?></label>
					<div class='input'><input type='password' name='DB_PASS' value="<?= htmlspecialchars($_SESSION['dbo_install']['DB_PASS']) ?>"/></div>
				</div><!-- item -->
			</div><!-- row -->

			<div class='row'>
				<div class='item'>
					<label><?= STEP1_DB_BASE ?></label>
					<div class='input'><input type='text' name='DB_BASE' value="<?= htmlspecialchars($_SESSION['dbo_install']['DB_BASE']) ?>"/></div>
				</div><!-- item -->
			</div><!-- row -->

			<?
				if(checkDatabase() === true)
				{
					if(!validateDBFile())
					{
						if(!@fopen('../../lib/db.php', 'a'))
						{
							?>
							<div class='row'>
								<div class='item-buttons' style='padding-top: 20px;'>
									<div class='dbcheck dbcheck-fail'>
										<div><span class='size1'><?= STEP1_CONNECTION_OK_BUT ?></span><span class='size2'><?= STEP1_CANT_WRITE_DB_FILE ?> <b>(<?= checkCHMOD('../../lib/db.php') ?>)</b></span></div>
										<input type='submit' style='width: 150px;' value='<?= WORD_CHECK ?>' class='dbcheck-button'>
									</div>
								</div><!-- item -->
							</div><!-- row -->
							<?
						}
						else
						{
							makeDbFile();
							?>
							<div class='row'>
								<div class='item-buttons' style='padding-top: 20px;'>
									<div class='dbcheck <?= ((checkDatabase() === true)?('dbcheck-ok'):('dbcheck-fail')) ?>'>
										<div><?= ((checkDatabase() === true)?('<span class="ok">'.STEP1_CONNECTION_SUCCESSFUL.'</span>'):('<span class="size1">'.STEP1_CONNECTION_FAILED.'</span><span class="size2">'.checkDatabase().'</span>')) ?></div>
										<input type='button' style='width: 150px; <?= ((checkDatabase() === true)?(''):("display: none;")) ?>' value='<?= WORD_NEXT ?>' class='go-to-step-2 next' />
										<input type='submit' style='width: 150px; <?= ((checkDatabase() !== true)?(''):("display: none;")) ?>' value='<?= WORD_CHECK ?>' class='dbcheck-button' />
									</div>
								</div><!-- item -->
							</div><!-- row -->
							<?
						}
					}
					else
					{
						?>
						<div class='row'>
							<div class='item-buttons' style='padding-top: 20px;'>
								<div class='dbcheck <?= ((checkDatabase() === true)?('dbcheck-ok'):('dbcheck-fail')) ?>'>
									<div><?= ((checkDatabase() === true)?('<span class="ok">'.STEP1_CONNECTION_SUCCESSFUL.'</span>'):('<span class="size1">'.STEP1_CONNECTION_FAILED.'</span><span class="size2">'.checkDatabase().'</span>')) ?></div>
									<input type='button' style='width: 150px; <?= ((checkDatabase() === true)?(''):("display: none;")) ?>' value='<?= WORD_NEXT ?>' class='go-to-step-2 next' />
									<input type='submit' style='width: 150px; <?= ((checkDatabase() !== true)?(''):("display: none;")) ?>' value='<?= WORD_CHECK ?>' class='dbcheck-button' />
								</div>
							</div><!-- item -->
						</div><!-- row -->
						<?
					}
				}
				else
				{
					?>
					<div class='row'>
						<div class='item-buttons' style='padding-top: 20px;'>
							<div class='dbcheck <?= ((checkDatabase() === true)?('dbcheck-ok'):('dbcheck-fail')) ?>'>
								<div><?= ((checkDatabase() === true)?('<span class="ok">'.STEP1_CONNECTION_SUCCESSFUL.'</span>'):('<span class="size1">'.STEP1_CONNECTION_FAILED.'</span><span class="size2">'.checkDatabase().'</span>')) ?></div>
								<input type='button' style='width: 150px; <?= ((checkDatabase() === true)?(''):("display: none;")) ?>' value='<?= WORD_NEXT ?>' class='go-to-step-2 next' />
								<input type='submit' style='width: 150px; <?= ((checkDatabase() !== true)?(''):("display: none;")) ?>' value='<?= WORD_CHECK ?>' class='dbcheck-button' />
							</div>
						</div><!-- item -->
					</div><!-- row -->
					<?
				}
			?>

			</form>
			<div class='clear'></div>
		</div>
	<?
}

function step2()
{
	if(!checkTables()) //cria as tabelas principais se não existirem...
	{
		$sql = "
			CREATE TABLE IF NOT EXISTS `pessoa` (
			  `id` int(11) NOT NULL auto_increment,
			  `foto` varchar(190) NOT NULL,
			  `nome` varchar(190) NOT NULL,
			  `apelido` varchar(190) NOT NULL,
			  `sexo` varchar(190) NOT NULL,
			  `email` varchar(190) NULL UNIQUE,
			  `user` varchar(190) NULL UNIQUE,
			  `pass` varchar(190) NOT NULL,
			  `descricao` TEXT NOT NULL,
			  `dbo_flag_desenv` INT(11) NOT NULL,
			  `google_id` varchar(190) NULL UNIQUE,
			  `facebook_id` varchar(190) NULL UNIQUE,
			  PRIMARY KEY  (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4;
		";
		dboQuery($sql);

		$sql = "
			CREATE TABLE IF NOT EXISTS `perfil` (
			  `id` int(11) NOT NULL auto_increment,
			  `nome` varchar(190) default NULL,
			  `permissao` text NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4;
		";
		dboQuery($sql);

		$sql = "
			CREATE TABLE IF NOT EXISTS `pessoa_perfil` (
			  `id` int(11) NOT NULL auto_increment,
			  `pessoa` int(11) NULL,
			  `perfil` int(11) NULL,
			  `dbo_flag_desenv` INT(11) NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4;
		";
		dboQuery($sql);

		$sql = "
			CREATE TABLE IF NOT EXISTS `permissao` (
			  `id` int(11) NOT NULL auto_increment,
			  `nome` varchar(190) default NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4;
		";
		dboQuery($sql);
	}

	//checks if the Admin profile exists. If now, inserts it.
	checkDatabase();
	$sql = "SELECT * FROM perfil WHERE nome = 'Desenv'";
	dboQuery($sql);
	if(!dboAffectedRows())
	{
		$sql = "
			INSERT INTO perfil
				(nome, permissao)
			VALUES
				('Desenv', 'pessoa###cockpit|||access|||insert|||update|||delete %%% perfil###sidebar|||access|||insert|||update|||delete|||Permissões %%% permissao###sidebar|||access|||insert|||update|||delete')
		";
		dboQuery($sql);
	}

	//checks if there is at least 1 admin user already on the system.
	if(!checkAdmins())
	{
		?>

			<div class='form'>
				<form id='form-step2' action='actions.php?registerAdminInformation=1' method='POST' rel='#content'>

				<h2><?= STEP2_ADMIN_INFORMATION_H2 ?></h2>

				<div class='row'>
					<div class='item'>
						<label>Nome</label>
						<div class='input'><input type='text' name='admin_name' value="<?= htmlspecialchars($_SESSION['dbo_install']['admin_name']) ?>"/></div>
					</div><!-- item -->
				</div><!-- row -->

				<div class='row'>
					<div class='item'>
						<label>E-mail</label>
						<div class='input'><input type='text' name='admin_email' value="<?= htmlspecialchars($_SESSION['dbo_install']['admin_email']) ?>"/></div>
					</div><!-- item -->
				</div><!-- row -->

				<div class='row'>
					<div class='item'>
						<label>Nome de Usuário</label>
						<div class='input'><input type='text' name='admin_user' value="<?= htmlspecialchars($_SESSION['dbo_install']['admin_user']) ?>"/></div>
					</div><!-- item -->
				</div><!-- row -->

				<div class='row'>
					<div class='item'>
						<label>Senha</label>
						<div class='input'><input type='password' name='admin_pass' value="<?= htmlspecialchars($_SESSION['dbo_install']['admin_pass']) ?>"/></div>
					</div><!-- item -->
				</div><!-- row -->

				<div class='row'>
					<div class='item'>
						<label>Checagem de Senha</label>
						<div class='input'><input type='password' name='admin_pass_check' value="<?= htmlspecialchars($_SESSION['dbo_install']['admin_pass_check']) ?>"/></div>
					</div><!-- item -->
				</div><!-- row -->

				<div class='row'>
					<div class='item-buttons' style='padding-top: 20px;'>
						<div class='dbcheck dbcheck-fail'>
							<span class='size3'><?= getMessage(); ?></span>
							<input type='submit' value='<?= STEP2_CREATE_USER ?>' class='dbcheck-button'/>
						</div>
					</div><!-- item -->
				</div><!-- row -->

				</form>
				<div class='clear'></div>
			</div>
		<?
	}
	else //shows the admin(s) if existant!
	{
		?>
			<div class='form'>
				<form id='form-step2' action='actions.php?registerAdminInformation=1' method='POST' rel='#content'>

				<h2><?= STEP2_ADMIN_INFORMATION_H2 ?></h2>

				<?

					//Copying the module Schemes for the dbo folder (if they're not already there...)
					$handle = opendir("./modules");
					while (false !== ($opendirfiles = readdir($handle)))
					{
						if(strlen($opendirfiles) > 2)
						{
							$files[] = $opendirfiles;
						}
					}
					closedir($handle);

					foreach($files as $key => $value)
					{
						if(!file_exists('../'.$value))
						{
							copy('./modules/'.$value, '../'.$value);
						}
					}

					//creating necessary folders for the CMS
					$oldumask = umask(0);
					$needed_folders = array(
						'../module_backups',
						'../upload',
						'../upload/dbo-media-manager',
						'../upload/dbo-media-manager/thumbs',
						'../upload/files',
						'../upload/images',
						'../plugins/jcrop_dbo/temp',
					);
					foreach($needed_folders as $key => $value)
					{
						if(!is_dir($value))
						{
							mkdir($value, 0755); 
						}
					}
					umask($oldumask); 

					//copying sample files
					if(!file_exists('../../scss/foundation/_variables.scss'))
					{
						copy('./sample-files/_variables.scss', '../../scss/foundation/_variables.scss');
					}
					if(!file_exists('../../scss/_project.scss'))
					{
						copy('./sample-files/_project.scss', '../../scss/_project.scss');
					}

					$admins = getAdmins();
					foreach($admins as $key => $value)
					{
						?>
						<div class='item-user'>
							<div class='user'><?= $value['user'] ?></div>
							<div class='name'><?= $value['nome'] ?></div>
							<div class='email'><?= $value['email'].((!strpos($value['email'], '@'))?('@fcfar.unesp.br'):('')) ?></div>
							<div class='clear'></div>
						</div>
						<?
					}

				?>

				<div class='row'>
					<div class='item-buttons' style='padding-top: 20px;'>
						<div class='dbcheck dbcheck-ok'>
							<div><span class='ok'><?= STEP2_USER_SUCCESS ?></span></div>
							<input type='button' value='<?= WORD_NEXT ?>' class='dbcheck-button go-to-step-3 next'/>
						</div>
					</div><!-- item -->
				</div><!-- row -->

				</form>
			</div>
		<?
	}
}

function step3()
{
	?>

		<div class='form'>
			<form id='form-step3' action='actions.php?registerSystemInformation=1' method='POST' rel='#content'>

			<h2><?= STEP3_BASIC_SETTINGS ?></h2>

			<div class='row'>
				<div class='item'>
					<label><?= STEP3_SYSTEM_NAME ?></label>
					<div class='input'><input type='text' name='SYSTEM_NAME' value="<?= htmlspecialchars($_SESSION['dbo_install']['SYSTEM_NAME']) ?>"/></div>
				</div><!-- item -->
			</div><!-- row -->

			<div class='row'>
				<div class='item'>
					<label><?= STEP3_SYSTEM_DESCRIPTION ?></label>
					<div class='input'><input type='text' name='SYSTEM_DESCRIPTION' value="<?= htmlspecialchars($_SESSION['dbo_install']['SYSTEM_DESCRIPTION']) ?>"/></div>
				</div><!-- item -->
			</div><!-- row -->

			<div class='row'>
				<div class='item'>
					<label><?= STEP3_DBO_URL ?></b></label>
					<div class='input'><input type='text' name='DBO_URL' value="<?= htmlspecialchars($_SESSION['dbo_install']['DBO_URL']) ?>"/></div>
				</div><!-- item -->
			</div><!-- row -->

			<div class='row'>
				<div class='item'>
					<label><?= STEP3_DBO_PERMISSIONS ?></label>
					<div class='input'>
						<select name='DBO_PERMISSIONS'>
							<option value='TRUE' <?= (($_SESSION['dbo_install']['DBO_PERMISSIONS'] === true)?('SELECTED'):('')) ?>>On</option>
							<option value='FALSE' <?= (($_SESSION['dbo_install']['DBO_PERMISSIONS'] === false)?('SELECTED'):('')) ?>>Off</option>
						</select>
					</div>
				</div><!-- item -->
			</div><!-- row -->

			<h2><?= STEP3_ADVANCED_SETTINGS ?></h2>

			<div class='row'>
				<div class='item'>
					<label><?= STEP3_SUPER_ADMINS ?></label>
					<div class='input'>
						<textarea name='SUPER_ADMINS' rows='2'><?
							if(is_array($_SESSION['dbo_install']['SUPER_ADMINS']))
							{
								echo implode("\n", $_SESSION['dbo_install']['SUPER_ADMINS']);
							}
							else
							{
								$get_admins = getAdmins();
								if(is_array($get_admins))
								{
									foreach($get_admins as $key => $value)
									{
										$system_admins[] = $value['user'];
									}
								}
								echo implode("\n", $system_admins);
							}
						?></textarea>
					</div>
				</div><!-- item -->
			</div><!-- row -->

			<div class='row'>
				<div class='item'>
					<label><?= STEP3_FULL_PAGES ?></label>
					<div class='input'>
						<textarea name='FULL_PAGES' rows='4'><?
							if(is_array($_SESSION['dbo_install']['FULL_PAGES']))
							{
								echo implode("\n", $_SESSION['dbo_install']['FULL_PAGES']);
							}
							else
							{
								echo "dbo_admin.php\ndbo_permissions.php";
							}
						?></textarea>
					</div>
				</div><!-- item -->
			</div><!-- row -->

			<?
				$validated = false;
				if(getDefines() && validateDefinesFile())
				{
					$validated = true;
				}
			?>

			<div class='row row-check' style='<?= (($validated)?('display: none;'):('')) ?>'>
				<div class='item-buttons' style='padding-top: 20px;'>
					<div class='dbcheck dbcheck-fail'>
						<span class='size3'><?= getMessage(); ?></span>
						<input type='submit' value='<?= STEP3_SAVE_SETTINGS ?>' class='dbcheck-button'/>
					</div>
				</div><!-- item -->
			</div><!-- row -->

			<div class='row row-next' style='<?= ((!$validated)?('display: none;'):('')) ?>'>
				<div class='item-buttons' style='padding-top: 20px;'>
					<div class='dbcheck dbcheck-ok'>
						<div><span class='ok'><?= STEP3_VALIDATED_AND_READY ?></span></div>
						<input type='button' value='<?= WORD_NEXT ?>' class='go-to-step-4 next'/>
					</div>
				</div><!-- item -->
			</div><!-- row -->

			</form>
			<div class='clear'></div>
		</div>
	<?
}

function step4()
{
	?>

		<div class='form-block'>
			<form id='form-step4' action='actions.php?registerSystemColors=1' method='POST' rel='#content'>

			<h2><?= STEP4_SYSTEM_COLORS ?></h2>

			<div class='wrapper-preview'>

				<div class='bg-header preview-COLOR_HEADER' style='background-color: <?= strtolower($_SESSION['dbo_install']['COLOR_HEADER']) ?>'>
					<div class='wrapper-header2'>
						<div class='bg-menu preview-COLOR_MENU' style='background-color: <?= strtolower($_SESSION['dbo_install']['COLOR_MENU']) ?>;' ></div>
						<div class='wrapper-header'>

							<div id='wrapper-titulo'>
								<h1><?= $_SESSION['dbo_install']['SYSTEM_NAME'] ?></h1>
								<span class='preview-COLOR_DESCRIPTION' style='color: <?= strtolower($_SESSION['dbo_install']['COLOR_DESCRIPTION']) ?>'><?= $_SESSION['dbo_install']['SYSTEM_DESCRIPTION'] ?></span>
							</div>
							<div class='wrapper-menu'>
								<div class='wrapper-user'>
									<?= $_SESSION['user'] ?> | Logout
								</div>
							</div>
						</div>
					</div><!-- wrapper-header2 -->
				</div><!-- bg-header -->

				<div class='example-h1'><?= STEP4_H1 ?></div>

				<div class='example-title preview-COLOR_TITLE' style='color: <?= strtolower($_SESSION['dbo_install']['COLOR_TITLE']) ?>'><?= STEP4_H2 ?></div>

			</div>

			<div class='row'>
				<div class='item item-25'>
					<label><?= STEP4_COLOR_HEADER ?></label>
					<div class='input'><input style='cursor: pointer' type='text' READONLY class='colorpicker-handler' name='COLOR_HEADER' value='<?= strtolower($_SESSION['dbo_install']['COLOR_HEADER']) ?>'/></div>
				</div><!-- item -->
				<div class='item item-25'>
					<label><?= STEP4_COLOR_MENU ?></label>
					<div class='input'><input style='cursor: pointer' type='text' READONLY class='colorpicker-handler' name='COLOR_MENU' value='<?= strtolower($_SESSION['dbo_install']['COLOR_MENU']) ?>'/></div>
				</div><!-- item -->
				<div class='item item-25'>
					<label><?= STEP4_COLOR_DESCRIPTION ?></label>
					<div class='input'><input style='cursor: pointer' type='text' READONLY class='colorpicker-handler' name='COLOR_DESCRIPTION' value='<?= strtolower($_SESSION['dbo_install']['COLOR_DESCRIPTION']) ?>'/></div>
				</div><!-- item -->
				<div class='item item-25'>
					<label><?= STEP4_COLOR_TITLE ?></label>
					<div class='input'><input style='cursor: pointer' type='text' READONLY class='colorpicker-handler' name='COLOR_TITLE' value='<?= strtolower($_SESSION['dbo_install']['COLOR_TITLE']) ?>'/></div>
				</div><!-- item -->
			</div><!-- row -->

			<div class='row row-next row-go-to-status-report'>
				<div class='item-buttons' style='padding-top: 10px;'>
					<div class='dbcheck dbcheck-ok'>
						<div style='width: 300px;'><span class='ok'>Cores Salvas!</span></div>
						<input type='button' value='<?= WORD_NEXT ?>' class='go-to-status-report next'/>
					</div>
				</div><!-- item -->
			</div><!-- row -->

			<div class='row row-check row-save-colors' style='display: none;'>
				<div class='item-buttons' style='padding-top: 10px;'>
					<div class='dbcheck dbcheck-fail'>
						<span class='size3'><?= getMessage(); ?></span>
						<input type='submit' value='<?= STEP4_SAVE_COLORS ?>' class='dbcheck-button'/>
					</div>
				</div><!-- item -->
			</div><!-- row -->

			</form>
			<div class='clear'></div>
		</div>
	<?
}

function registerDatabase()
{
	$_SESSION['dbo_install']['DB_HOST'] = (strlen($_POST['DB_HOST']))?($_POST['DB_HOST']):('localhost');
	$_SESSION['dbo_install']['DB_USER'] = $_POST['DB_USER'];
	$_SESSION['dbo_install']['DB_PASS'] = $_POST['DB_PASS'];
	$_SESSION['dbo_install']['DB_BASE'] = $_POST['DB_BASE'];
	step1();
}

function registerAdminInformation()
{

	$_SESSION['dbo_install']['admin_name'] = $_POST['admin_name'];
	$_SESSION['dbo_install']['admin_user'] = $_POST['admin_user'];
	$_SESSION['dbo_install']['admin_email'] = $_POST['admin_email'];
	$_SESSION['dbo_install']['admin_pass'] = $_POST['admin_pass'];
	$_SESSION['dbo_install']['admin_pass_check'] = $_POST['admin_pass_check'];

	$error = false;

	if(!strlen($_SESSION['dbo_install']['admin_name']))	{ setMessage(STEP2_ERROR_NO_NAME); step2(); exit(); }
	if(!strlen($_SESSION['dbo_install']['admin_email'])) { setMessage(STEP2_ERROR_NO_EMAIL); step2(); exit(); }
	if(!strlen($_SESSION['dbo_install']['admin_user'])) { setMessage(STEP2_ERROR_NO_USERNAME); step2(); exit(); }
	if(!strlen($_SESSION['dbo_install']['admin_pass'])) { setMessage(STEP2_ERROR_NO_PASS); step2(); exit(); }
	if($_SESSION['dbo_install']['admin_pass'] != $_SESSION['dbo_install']['admin_pass_check']) { setMessage(STEP2_ERROR_WRONG_PASS); step2(); exit(); }

	checkDatabase();
	//gets the Admin Profile id
	$sql = "SELECT id FROM perfil WHERE nome = 'Desenv'";
	$res = dboQuery($sql);
	$lin = dboFetchObject($res);
	$perfil = $lin->id;

	//Inserts the user!
	$sql = "INSERT INTO pessoa (nome, email, user, pass)
		VALUES
		(
			'".addslashes($_SESSION['dbo_install']['admin_name'])."',
			'".addslashes($_SESSION['dbo_install']['admin_email'])."',
			'".addslashes($_SESSION['dbo_install']['admin_user'])."',
			'".addslashes(hash('sha512', $_SESSION['dbo_install']['admin_pass']))."'
		)";
	dboQuery($sql);
	$pessoa = dboInsertId();

	$_SESSION['user'] = $_SESSION['dbo_install']['admin_user'];
	$_SESSION['user_id'] = $pessoa;

	//And inserts the ids in the relation table.
	$sql = "INSERT INTO pessoa_perfil (pessoa, perfil) VALUES ('".addslashes($pessoa)."', '".addslashes($perfil)."')";
	dboQuery($sql);

	step2();
}

function registerSystemInformation()
{
	$_SESSION['dbo_install']['SYSTEM_NAME'] = $_POST['SYSTEM_NAME'];
	$_SESSION['dbo_install']['SYSTEM_DESCRIPTION'] = $_POST['SYSTEM_DESCRIPTION'];
	$_SESSION['dbo_install']['DBO_URL'] = $_POST['DBO_URL'];
	$_SESSION['dbo_install']['DBO_PERMISSIONS'] = (($_POST['DBO_PERMISSIONS'] == 'TRUE')?(TRUE):(FALSE));
	$_SESSION['dbo_install']['SUPER_ADMINS'] = explode("\n", $_POST['SUPER_ADMINS']);
	$_SESSION['dbo_install']['FULL_PAGES'] = explode("\n", $_POST['FULL_PAGES']);

	if(!getDefines())
	{
		step3();
		exit();
	}

	makeDefinesFile();

	step3();
}

function registerSystemColors()
{
	$_SESSION['dbo_install']['COLOR_HEADER'] = $_POST['COLOR_HEADER'];
	$_SESSION['dbo_install']['COLOR_MENU'] = $_POST['COLOR_MENU'];
	$_SESSION['dbo_install']['COLOR_DESCRIPTION'] = $_POST['COLOR_DESCRIPTION'];
	$_SESSION['dbo_install']['COLOR_TITLE'] = $_POST['COLOR_TITLE'];

	makeDefinesFile();

	step4();
}

function showStatusBar()
{
	statusBar();
}

function showStatusReport()
{
	?>
	<div class='form'>
		<form>
			<h2><?= STATUS_REPORT_TITLE ?></h2>
			<div class='item-status'>
				<div class='label'><?= STATUS_REPORT_DB_CONNECTION ?></div>
				<div class='status go-to-step-1 <?= ((checkDatabase() === true)?('ok'):('fail')) ?>'><?= ((checkDatabase() === true)?(STATUS_OK):(STATUS_FAIL)) ?></div>
			</div>
			<div class='item-status'>
				<div class='label'><?= STATUS_REPORT_ADMINS ?></div>
				<div class='status go-to-step-2 <?= ((checkAdmins())?('ok'):('fail')) ?>'><?= ((checkAdmins())?(STATUS_OK):(STATUS_FAIL)) ?></div>
			</div>
			<div class='item-status'>
				<div class='label'><?= STATUS_REPORT_DEFINES ?></div>
				<div class='status go-to-step-3 <?= ((checkDefines())?('ok'):('fail')) ?>'><?= ((checkDefines())?(STATUS_OK):(STATUS_FAIL)) ?></div>
			</div>
			<div class='item-status'>
				<div class='label'><?= STATUS_REPORT_COLORS ?></div>
				<div class='status go-to-step-4 <?= ((checkDefines())?('ok'):('fail')) ?>'><?= ((checkDefines())?(STATUS_OK):(STATUS_FAIL)) ?></div>
			</div>

			<?
				if(checkDatabase() === true && checkAdmins() && checkDefines())
				{
					?>
					<div class='row row-next row-go-to-status-report'>
						<div class='item-buttons' style='padding-top: 10px;'>
							<div class='dbcheck dbcheck-ok'>
								<input type='button' value='<?= STATUS_REPORT_FINISH_INSTALL ?>' class='finish-install'/>
							</div>
						</div><!-- item -->
					</div><!-- row -->
					<?
				}
				else
				{
					$next_class='';
					if(checkDatabase() !== true) {
						$next_class = 'go-to-step-1';
					} elseif (!checkAdmins()) {
						$next_class = 'go-to-step-2';
					} elseif (!checkDefines()) {
						$next_class = 'go-to-step-3';
					}
					?>
					<div class='row row-check row-save-colors'>
						<div class='item-buttons' style='padding-top: 10px;'>
							<div class='dbcheck dbcheck-fail'>
								<span class='size3'><?= STAUTS_REPORT_MESSAGE_FINISH_STEPS ?></span>
								<input type='button' value='<?= STATUS_REPORT_CONTINUE_INSTALL ?>' class='next <?= $next_class ?>'/>
							</div>
						</div><!-- item -->
					</div><!-- row -->
					<?
				}
			?>

		</form>
	</div>
	<?
}

function purgeInstallData()
{
	unset($_SESSION['dbo_install']);
	echo "PURGED";
}

?>
