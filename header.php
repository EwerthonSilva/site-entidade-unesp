<?php require_once("admin/lib/includes.php"); ?>
<!doctype html>
<html class="no-js" lang="en">
<head>
	<base href="<?= SITE_URL ?>/">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= siteTitulo(); ?></title>

	<link href='https://fonts.googleapis.com/css?family=Lato:400,300,900' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/app.css">
	<link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="bower_components/slick-carousel/slick/slick.css">

	<script src="bower_components/jquery/dist/jquery.js"></script>
	<script src="bower_components/foundation-sites/dist/foundation.js"></script>
	<script src="bower_components/slick-carousel/slick/slick.js"></script>
	<script src="admin/js/peixelaranja.js"></script>
	<script src="js/app.js"></script>

	<?= siteHead(); ?>

	<?= dboImportJs(array(
		'smooth-scroll',
	)) ?>

</head>
<body class="<?= siteBodyClass() ?> unresolved">

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
							<div id="main-title" class="stop"><?= siteConfig()->site_titulo ?></div>
							<div id="banner-text-left" class="stop"><?= nl2br(siteConfig()->banner_text_left) ?></div>
							<div id="banner-text-right" class="stop"><?= nl2br(siteConfig()->banner_text_right) ?></div>
						</div>
					</div>
				</div>
				<?php
			}
		?>
		<div class="top-bar">
			<div class="row">
				<div class="small-12 large-12 columns">
					<div class="top-bar-left">
						<ul class="dropdown menu" data-dropdown-menu>
							<?= menu::render('menu-principal', array(
								'items_only' => true,
							)) ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</header>
