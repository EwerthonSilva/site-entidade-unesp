<?php require_once("admin/lib/includes.php"); ?>
<!doctype html>
<html class="no-js" lang="en">
<head>
	<base href="<?= SITE_URL ?>/">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= siteTitulo(); ?></title>

	<link href='https://fonts.googleapis.com/css?family=Lato:400,300,900' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="admin/css/common.css">
	<link rel="stylesheet" href="node_modules/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="node_modules/slick-carousel/slick/slick.css">
	<link rel="stylesheet" href="css/app.css">

	<script src="node_modules/jquery/dist/jquery.js"></script>

	<?= siteHead(); ?>

	<?= dboImportJs(array(
		'smooth-scroll',
	)) ?>

</head>
<body class="<?= siteBodyClass() ?> unresolved">


	<!-- Off-Canvas -->
	<div class="hide-for-large off-canvas position-left hide-for-sr" id="offCanvas" data-off-canvas>
		<ul class="vertical menu drilldown mobile-menu" data-drilldown data-auto-height="true" data-animate-height="true" data-back-button='<li class="js-drilldown-back"><a tabindex="0">Voltar</a></li>'>
			<?= menu::render('menu-principal', array(
				'items_only' => true,
				'$foundation_6' => true,
			)) ?>
		</ul>
	</div>
	<!-- Fim do Off-Canvas -->
	<header>

		<?php
		if(siteConfig()->imagem_com_texto)
		{
			?>
			<img src="<?= siteConfig()->_background_image->url() ?>"/>
			<?php
		}
		else
		{
			?>
			<div id="main-banner" style="background-image: url(<?= siteConfig()->_background_image->url() ?>)">
				<div class="row" id="main-banner-stage">
					<div class="small-12 large-12 columns">
						<div id="main-title" class="stop show-for-large"><?= siteConfig()->site_titulo ?></div>
						<div id="banner-text-left" class="stop"><?= nl2br(siteConfig()->banner_text_left) ?></div>
						<div id="banner-text-right" class="stop"><?= nl2br(siteConfig()->banner_text_right) ?></div>
					</div>
				</div>
			</div>
			<?php
		}
		?>
		<div class="top-bar" data-responsive-toggle="responsive-menu" data-hide-for="large">
			<button class="menu-icon" type="button" id="off-canvas-menu" data-toggle="responsive-menu offCanvas"></button>
			<div class="title-bar-title"><?= siteConfig()->site_titulo ?></div>
			<div class="title-bar-title text-center">

			</div>
		</div>
		<div class="top-bar show-for-large" id="responsive-menu">
			<div class="row">
				<div class="small-12 large-12 columns">
					<div class="top-bar-left">
						<ul class="dropdown menu main-menu" data-dropdown-menu >
							<?= menu::render('menu-principal', array(
								'items_only' => true,
								'$foundation_6' => true,
							)) ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</header>
