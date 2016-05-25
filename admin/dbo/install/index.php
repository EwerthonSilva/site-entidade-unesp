<?
	include('includes.php');
	//se já existir o arquivo de defines, não é possível fazer mais nada aqui.
	if(validateDefinesFile())
	{
		?>
		<h1 style="font-family: Arial; text-align: center;"><br /><br /><br /><br />O sistema já foi instalado.</h3>
		<h3 style="font-family: Arial; text-align: center;">O instalador está inacessível.<br /><br /></h5>
		<p style="font-family: Arial; text-align: center;">Se você chegou até aqui... É porque alguma coisa errada aconteceu com a conexão ao banco de dados.</p>
		<h1 style="font-family: Arial; text-align: center; font-size: 150px; line-height: .2;">:(</h3>
		<?
		exit();
	}
	populateSession();
?>
<!doctype html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>DBO | <?= INSTALL_TITLE ?> | <?= INSTALL_DESCRIPTION ?></title>
	<meta name="description" content="">
	<meta name="author" content="PeixeLaranja">
	<link rel="shortcut icon" href="images/favicon.ico">
	<link rel="apple-touch-icon" href="apple-touch-icon.png">

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

	<link rel="stylesheet" href="js/colorpicker/css/colorpicker.css" type="text/css" />
	<script type="text/javascript" src="js/colorpicker/js/colorpicker.js"></script>
    <script type="text/javascript" src="js/colorpicker/js/eye.js"></script>
    <script type="text/javascript" src="js/colorpicker/js/utils.js"></script>
    <script type="text/javascript" src="js/colorpicker/js/layout.js?ver=1.0.2"></script>

	<link rel="stylesheet" href="style.css">

	<script>

	var reffered = <?= (($_GET['reffered'])?('1'):('0')) ?>

	function ajaxLoad (target, url, callback)
	{
		if(callback === undefined)
		{
			$(target).load(url);
		}
		else {
			$(target).load(url, callback);
		}
	}

	$(document).ready(function(){

		$(document).on('click', '.steps a', function(e){
			var thisone = $(this);
			e.preventDefault();
			$.get('actions.php?getStatus='+$(this).attr('id'), function(data){
				if(data == 'ok')
				{
					$('.steps a').removeClass('active');
					$('.wrapper-status-bar').removeClass('wrapper-status-bar-active');
					thisone.addClass('active');
					if($('#content').is(':hidden'))
					{
						$('#content').show();
					}
					ajaxLoad('#content', 'actions.php?'+thisone.attr('href'), init());
				}
				else
				{
					alert('<?= ERROR_PENDING_STEPS ?>');
				}
			})
		})

		$(document).on('click', '.wrapper-status-bar', function(){
			var thisone = $(this);
			$('.steps a').removeClass('active');
			thisone.addClass('wrapper-status-bar-active');
			$.get('actions.php?showStatusReport=1', function(data){
				if($('#content').is(':hidden')){ $('#content').show(); }
				$('#content').html(data);
			})
		})

		$('#content').hide();

		$(document).on('click', '.go-to-step-1', function(){
			$('#step1').trigger('click');
		})

		$(document).on('click', '.go-to-step-2', function(){
			$('#step2').trigger('click');
		})

		$(document).on('click', '.go-to-step-3', function(){
			$('#step3').trigger('click');
		})

		$(document).on('click', '.go-to-step-4', function(){
			$('#step4').trigger('click');
		})

		$(document).on('click', '.go-to-status-report', function(){
			$('.wrapper-status-bar').trigger('click');
		})

		$(document).on('change', '#form-step1 input', function(){
			$('.dbcheck-button').show();
			$('#form-step1 .next').hide();
		})

		$(document).on('change', '#form-step3 input', function(){
			$('.row-check').show();
			$('.row-next').hide();
		})

		$(document).on('change', '#form-step3 select', function(){
			$('.row-check').show();
			$('.row-next').hide();
		})

		$(document).on('change', '#form-step3 textarea', function(){
			$('.row-check').show();
			$('.row-next').hide();
		})

		$(document).on('click', '.finish-install', function(){
			$.get('actions.php?purgeInstallData=1', function(data){
				if(data == 'PURGED') {
					if(reffered == 1) {
						window.close();
					} else {
						window.location = '?redirect=1';
					}
				} else {
					alert('Error purging installation data...');
				}
			})
		})

	})

	//funcoes ===========================================================================

	$(document).on('submit', 'form[id^="form-"]', function(){
		var target = $(this).attr('rel');
		$.post(
			$(this).attr('action'),
			$(this).serialize(),
			function(data){
				data = $.parseHTML(data);
				$(target).fadeTo('fast', 0, function(){
					$(target).html(data);
					$(target).fadeTo('fast', 1);
				})
				updateStatusBar();
			}
		);
		return false;
	})

	function init()
	{
		$(document).on('focus', '.colorpicker-handler', function(){
			var thisselector = $(this);
			$(this).ColorPicker({
				color: thisselector.val(),
				onSubmit: function (hsb, hex, rgb, el) {
					$(el).val('#'+hex);
					$(el).ColorPickerHide();
					updatePreviews(thisselector.attr('name'), hex);
					triggerStep4Button();
				},
				onChange: function (hsb, hex, rgb) {
					thisselector.val('#'+hex);
					updatePreviews(thisselector.attr('name'), hex);
					triggerStep4Button();
				}
			});
		})
	}

	function updatePreviews(element, color)
	{
		if(element == 'COLOR_MENU') {
			$('.preview-COLOR_MENU').css('background-color', '#'+color);
		} else if (element == 'COLOR_HEADER') {
			$('.preview-COLOR_HEADER').css('background-color', '#'+color);
		} else if (element == 'COLOR_DESCRIPTION') {
			$('.preview-COLOR_DESCRIPTION').css('color', '#'+color);
		} else if (element == 'COLOR_TITLE') {
			$('.preview-COLOR_TITLE').css('color', '#'+color);
		}
	}

	function triggerStep4Button()
	{
		if($('.row-go-to-status-report').is(':visible')){ $('.row-go-to-status-report').hide(); }
		if($('.row-save-colors').is(':hidden')){ $('.row-save-colors').show(); }
	}

	function updateStatusBar() {
		ajaxLoad('#status-bar-handler', 'actions.php?showStatusBar=1');
	}

	</script>


</head>
<body>

<div class='main-wrap'>

	<h1>DBO &bull; <?= INSTALL_TITLE ?></h1>

	<span id='status-bar-handler'><? statusBar(); ?></span>

	<div class='separator'></div>

	<?
		if(!is_writable('../../lib/') || !is_writable('../../dbo/'))
		{
			if(!is_writable('../../lib/') && !is_writable('../../dbo/')) {
				bigError(ERROR_NO_WRITE_PERMISSION_LIB_AND_DBO_FOLDER);
			} elseif (is_writable('../../lib/')) {
				bigError(ERROR_NO_WRITE_PERMISSION_LIB_FOLDER);
			} elseif (is_writable('../../dbo/')) {
				bigError(ERROR_NO_WRITE_PERMISSION_DBO_FOLDER);
			}
		}
		else
		{
			?>
			<ul class='steps'>
				<li><a href='step=1' id='step1'><?= STEP1 ?></a></li>
				<li><a href='step=2' id='step2'><?= STEP2 ?></a></li>
				<li><a href='step=3' id='step3'><?= STEP3 ?></a></li>
				<li><a href='step=4' id='step4'><?= STEP4 ?></a></li>
			</ul>
			<?
		}
	?>

	<div id='content' style='display: none;'>

	</div><!-- content -->

	<div class='clear'></div>

</div>

</body>
</html>