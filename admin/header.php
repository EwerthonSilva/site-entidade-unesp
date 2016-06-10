<?php require_once('lib/includes.php'); ?>
<?php
	/* se não existir o custom header, usar o header padrão. */
	if(!file_exists('./custom-header.php'))
	{
		?>
<!doctype html>
<html dir="ltr" lang="pt-BR">
<head>
	<!-- <meta name="robots" content="noindex"> -->
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?= SYSTEM_NAME ?> - <?= SYSTEM_DESCRIPTION ?></title>
	<meta name="description" content="">
	<meta name="author" content="Peixe Laranja">
	<base href="<?= preg_replace('#/dbo$#', '', DBO_URL) ?>/">

	<link rel="shortcut icon" href="images/favicon.ico">
	<link rel="stylesheet" href="css/foundation.css" />

	<?= dboHead(); ?>

	<meta name="viewport" content="width=device-width" />
	<link rel="stylesheet" media="screen" href="css/common.css">
	<link rel="stylesheet" media="screen" href="css/style-dbo.css">
	<link rel="stylesheet" media="screen" href="fonts/museo-sans/stylesheet.css">
	<link rel="stylesheet" media="screen" href="fonts/font-awesome/css/font-awesome.css">
	<?= file_exists(DBO_PATH.'/../css/style.css') ? '<link rel="stylesheet" media="screen" href="css/style.css">' : '' ?>

	<?php $hooks->do_action('head') ?>

	<style>
		<?php
			if(!logadoNoPerfil('Desenv')) {
				echo ".dev { display: none !important; }";
			}
		?>
	</style>

</head>
<body class="dbo <?= $_GET['body_class'] ?> <?= (($_GET['dbo_modal'])?('modal'):((($_GET['dbo_modal_no_fixos'])?('modal no-fixos'):('')))) ?>">

	<?= browserWarning(); ?>

	<?= dboBody(); ?>

	<div id="main-header" <?= prettyHeaderAtts() ?> data-stellar-background-ratio="0.5">

		<?php $hooks->do_action('dbo_header_prepend') ?>
		
		<?= prettyHeaderLogo() ?>

		<div class='row first-row hide-for-small'>
			<div class='large-10 columns'>
				<ul class="bread-crumb">
				</ul>
			</div>
			<div class='large-2 columns text-right'></div>
		</div><!-- row -->
		
		<div class="nome-sistema">
			<div class='row'>
				<div class='large-7 columns'>
					<h2><?= SYSTEM_NAME ?></h2>
				</div>
				<div class='large-5 columns hide-for-small tar'>
					<span><?= SYSTEM_DESCRIPTION ?></span>
				</div>
			</div><!-- row -->
		</div>

		<div class="main-tabs cf contain-to-grid">
			<nav class="top-bar">
				<section class="top-bar-section">
					<ul class="title-area">
						<li class="name"></li>
						<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
					</ul>
					<?php
						include(((file_exists('custom-menu.php'))?('custom-menu.php'):('menu.php')));
					?>
				</section>
			</nav>
		</div><!-- main-tabs -->

		<?php $hooks->do_action('dbo_header_append') ?>
	
	</div><!-- main-header -->

	<div id="main-wrap">
		<?php
	}
	else
	{
		include('custom-header.php');
	}
?>