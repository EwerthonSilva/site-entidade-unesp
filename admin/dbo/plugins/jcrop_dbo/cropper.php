<?
require_once('../../../lib/includes.php');
require_once(DBO_PATH."/core/classes/simpleimage.php");
$data = unserialize(dboDecode($_GET['data']));

//instanciando as variaveis do objeto pai no contexto global
foreach($data as $key => $value)
{
	if($value) { $$key = $value; }
}

if($_FILES['image']['error'] === 0)
{
	if($_FILES['image']['type'] != 'image/jpeg')
	{
		$error = "A imagem precisa ser obritoriamente formato JPEG";
	}
	else
	{
		$file_name = time().rand(1,100).".jpg"; //criando um nome randomico para o arquivo

		$original_file = $_FILES['image']['tmp_name'];

		$file_path = "temp/orig_".$file_name;

		move_uploaded_file($original_file, $file_path); //ou então se não precisou...

		$step2 = TRUE;
	}

}

if($_POST['time2crop'])
{

	list($folder, $filename) = explode('/', $_POST['original_file']);

	$img_info = getImageSize($_POST['original_file']);
	$original_file_width  = $img_info[0];
	$original_file_height = $img_info[1];

	/* ====================================================================================================== */
	/* tratamento de crops ================================================================================== */
	/* ====================================================================================================== */

	//trata o crop 1
	if($_POST['cw1'])
	{

		$crop_filename = "c1_".str_replace('orig_', '', $filename);

		$targ_w = $_POST['cw1'];
		$targ_h = $_POST['ch1'];
		$jpeg_quality = 95;

		$src = $_POST['original_file'];
		$img_r = imagecreatefromjpeg($src);
		$dst_r = ImageCreateTrueColor($targ_w,$targ_h);

		imagecopyresampled($dst_r,$img_r,0,0,$_POST['c1-x'],$_POST['c1-y'],$targ_w,$targ_h,$_POST['c1-w'],$_POST['c1-h']);

		imagejpeg($dst_r, $folder."/".$crop_filename, $jpeg_quality);

	}

	//trata o crop 2
	if($_POST['cw2'])
	{

		$crop_filename = "c2_".str_replace('orig_', '', $filename);

		$targ_w = $_POST['cw2'];
		$targ_h = $_POST['ch2'];
		$jpeg_quality = 95;

		$src = $_POST['original_file'];
		$img_r = imagecreatefromjpeg($src);
		$dst_r = ImageCreateTrueColor($targ_w,$targ_h);

		imagecopyresampled($dst_r,$img_r,0,0,$_POST['c2-x'],$_POST['c2-y'],$targ_w,$targ_h,$_POST['c2-w'],$_POST['c2-h']);

		imagejpeg($dst_r, $folder."/".$crop_filename, $jpeg_quality);

	}

	//trata o crop 3
	if($_POST['cw3'])
	{

		$crop_filename = "c3_".str_replace('orig_', '', $filename);

		$targ_w = $_POST['cw3'];
		$targ_h = $_POST['ch3'];
		$jpeg_quality = 95;

		$src = $_POST['original_file'];
		$img_r = imagecreatefromjpeg($src);
		$dst_r = ImageCreateTrueColor($targ_w,$targ_h);

		imagecopyresampled($dst_r,$img_r,0,0,$_POST['c3-x'],$_POST['c3-y'],$targ_w,$targ_h,$_POST['c3-w'],$_POST['c3-h']);

		imagejpeg($dst_r, $folder."/".$crop_filename, $jpeg_quality);

	}

	//trata o crop 4
	if($_POST['cw4'])
	{

		$crop_filename = "c4_".str_replace('orig_', '', $filename);

		$targ_w = $_POST['cw4'];
		$targ_h = $_POST['ch4'];
		$jpeg_quality = 95;

		$src = $_POST['original_file'];
		$img_r = imagecreatefromjpeg($src);
		$dst_r = ImageCreateTrueColor($targ_w,$targ_h);

		imagecopyresampled($dst_r,$img_r,0,0,$_POST['c4-x'],$_POST['c4-y'],$targ_w,$targ_h,$_POST['c4-w'],$_POST['c4-h']);

		imagejpeg($dst_r, $folder."/".$crop_filename, $jpeg_quality);

	}

	//trata o crop 5
	if($_POST['cw5'])
	{

		$crop_filename = "c5_".str_replace('orig_', '', $filename);

		$targ_w = $_POST['cw5'];
		$targ_h = $_POST['ch5'];
		$jpeg_quality = 95;

		$src = $_POST['original_file'];
		$img_r = imagecreatefromjpeg($src);
		$dst_r = ImageCreateTrueColor($targ_w,$targ_h);

		imagecopyresampled($dst_r,$img_r,0,0,$_POST['c5-x'],$_POST['c5-y'],$targ_w,$targ_h,$_POST['c5-w'],$_POST['c5-h']);

		imagejpeg($dst_r, $folder."/".$crop_filename, $jpeg_quality);

	}

	/* ====================================================================================================== */
	/* tratamento de fullsizes ============================================================================== */
	/* ====================================================================================================== */

	$original_file = $_POST['original_file'];
	$img_info = getImageSize($original_file);
	$original_file_width  = $img_info[0];
	$original_file_height = $img_info[1];


	//trata fullsize 1
	if($fullw1 || $fullh1)
	{

		$image = new SimpleImage();
		$image->load($original_file);

		$full_filename = "f1_".str_replace('orig_', '', $filename);

		if(!$fullh1) //if max height wasn't set
		{
			if($fullw1 < $original_file_width)
			{
				$image->resizeToWidth($fullw1);
			}
		}
		elseif(!$fullw1) //if max width wasn't set
		{
			if($fullh1 < $original_file_height)
			{
				$image->resizeToHeight($fullh1);
			}
		}
		else //if both height and width were set
		{
			if($fullw1 >= $fullh1 && $original_file_width >= $original_file_height) {
				if($fullw1 < $original_file_width)
				{
					$image->resizeToWidth($fullw1);
				}
			} else {
				if($fullh1 < $original_file_height)
				{
					$image->resizeToHeight($fullh1);
				}
			}
		}

		$image->save($folder."/".$full_filename);

	}

	//trata fullsize 2
	if($fullw2 || $fullh2)
	{

		$image = new SimpleImage();
		$image->load($original_file);

		$full_filename = "f2_".str_replace('orig_', '', $filename);

		if(!$fullh2) //if max height wasn't set
		{
			if($fullw2 < $original_file_width)
			{
				$image->resizeToWidth($fullw2);
			}
		}
		elseif(!$fullw2) //if max width wasn't set
		{
			if($fullh2 < $original_file_height)
			{
				$image->resizeToHeight($fullh2);
			}
		}
		else //if both height and width were set
		{
			if($fullw2 >= $fullh2 && $original_file_width >= $original_file_height) {
				if($fullw2 < $original_file_width)
				{
					$image->resizeToWidth($fullw2);
				}
			} else {
				if($fullh2 < $original_file_height)
				{
					$image->resizeToHeight($fullh2);
				}
			}
		}

		$image->save($folder."/".$full_filename);

	}

	//trata fullsize 3
	if($fullw3 || $fullh3)
	{

		$image = new SimpleImage();
		$image->load($original_file);

		$full_filename = "f3_".str_replace('orig_', '', $filename);

		if(!$fullh3) //if max height wasn't set
		{
			if($fullw3 < $original_file_width)
			{
				$image->resizeToWidth($fullw3);
			}
		}
		elseif(!$fullw3) //if max width wasn't set
		{
			if($fullh3 < $original_file_height)
			{
				$image->resizeToHeight($fullh3);
			}
		}
		else //if both height and width were set
		{
			if($fullw3 >= $fullh3 && $original_file_width >= $original_file_height) {
				if($fullw3 < $original_file_width)
				{
					$image->resizeToWidth($fullw3);
				}
			} else {
				if($fullh3 < $original_file_height)
				{
					$image->resizeToHeight($fullh3);
				}
			}
		}

		$image->save($folder."/".$full_filename);

	}

	//trata fullsize 4
	if($fullw4 || $fullh4)
	{

		$image = new SimpleImage();
		$image->load($original_file);

		$full_filename = "f4_".str_replace('orig_', '', $filename);

		if(!$fullh4) //if max height wasn't set
		{
			if($fullw4 < $original_file_width)
			{
				$image->resizeToWidth($fullw4);
			}
		}
		elseif(!$fullw4) //if max width wasn't set
		{
			if($fullh4 < $original_file_height)
			{
				$image->resizeToHeight($fullh4);
			}
		}
		else //if both height and width were set
		{
			if($fullw4 >= $fullh4 && $original_file_width >= $original_file_height) {
				if($fullw4 < $original_file_width)
				{
					$image->resizeToWidth($fullw4);
				}
			} else {
				if($fullh4 < $original_file_height)
				{
					$image->resizeToHeight($fullh4);
				}
			}
		}

		$image->save($folder."/".$full_filename);

	}

	//trata fullsize 5
	if($fullw5 || $fullh5)
	{

		$image = new SimpleImage();
		$image->load($original_file);

		$full_filename = "f5_".str_replace('orig_', '', $filename);

		if(!$fullh5) //if max height wasn't set
		{
			if($fullw5 < $original_file_width)
			{
				$image->resizeToWidth($fullw5);
			}
		}
		elseif(!$fullw5) //if max width wasn't set
		{
			if($fullh5 < $original_file_height)
			{
				$image->resizeToHeight($fullh5);
			}
		}
		else //if both height and width were set
		{
			if($fullw5 >= $fullh5 && $original_file_width >= $original_file_height) {
				if($fullw5 < $original_file_width)
				{
					$image->resizeToWidth($fullw5);
				}
			} else {
				if($fullh5 < $original_file_height)
				{
					$image->resizeToHeight($fullh5);
				}
			}
		}

		$image->save($folder."/".$full_filename);

	}

	//trata a imagem de listagem
	if($list)
	{

		$image = new SimpleImage();
		$image->load($original_file);

		$full_filename = "list_".str_replace('orig_', '', $filename);

		if($original_file_width >= $original_file_height)
		{
			$image->resizeToWidth($list);
		}
		else
		{
			$image->resizeToHeight($list);
		}

		$image->save($folder."/".$full_filename);

	}

	$coluna = addslashes($_GET['coluna']);

	?>
	<script>
		window.parent.setPeixeMessage('<div class="success">Atenção: Salve as alterações para finalizar a alteração da foto!</div>');
		window.parent.showPeixeMessage();
		window.parent.closeBox('<?= $coluna ?>', '<?= str_replace("orig_", "", $filename) ?>');
	</script>
	<?
	exit();
}

?>
<!doctype html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>DBO Jcrop</title>
	<meta name="description" content="">
	<meta name="author" content="PeixeLaranja">
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="apple-touch-icon" href="apple-touch-icon.png">

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>

	<link rel="stylesheet" href="cropper.css">

	<script src="js/jquery.Jcrop.min.js"></script>
	<link rel="stylesheet" href="css/jquery.Jcrop.css" type="text/css" />

	<script>

		$(document).ready(function(){

			<? if($cw1) { ?>
				$('#cropbox-1').Jcrop({
					boxWidth: 940,
					boxHeight: 560,
					aspectRatio: <?= $cw1/$ch1 ?>,
					//minSize: [<?= $cw1 ?>, <?= $ch1 ?>]
					setSelect: [0, 0, <?= $cw1 ?>, <?= $ch1 ?>],
					onChange: showCoords1,
					onSelect: showCoords1
				});
			<? } ?>

			<? if($cw2) { ?>
				$('.crop-1 h2 input.continuar').click(function(){
					$('.crop-1').fadeOut(function(){
						$('.crop-2').fadeIn(function(){
							$('#cropbox-2').Jcrop({
								boxWidth: 940,
								boxHeight: 560,
								aspectRatio: <?= $cw2/$ch2 ?>,
								setSelect: [0, 0, <?= $cw2 ?>, <?= $ch2 ?>],
								onChange: showCoords2,
								onSelect: showCoords2
							});
						});
					});
				})
			<? } ?>

			<? if($cw3) { ?>
				$('.crop-2 h2 input.continuar').click(function(){
					$('.crop-2').fadeOut(function(){
						$('.crop-3').fadeIn(function(){
							$('#cropbox-3').Jcrop({
								boxWidth: 940,
								boxHeight: 560,
								aspectRatio: <?= $cw3/$ch3 ?>,
								setSelect: [0, 0, <?= $cw3 ?>, <?= $ch3 ?>],
								onChange: showCoords3,
								onSelect: showCoords3
							});
						});
					});
				})
			<? } ?>

			<? if($cw4) { ?>
				$('.crop-3 h2 input.continuar').click(function(){
					$('.crop-3').fadeOut(function(){
						$('.crop-4').fadeIn(function(){
							$('#cropbox-4').Jcrop({
								boxWidth: 940,
								boxHeight: 560,
								aspectRatio: <?= $cw4/$ch4 ?>,
								setSelect: [0, 0, <?= $cw4 ?>, <?= $ch4 ?>],
								onChange: showCoords4,
								onSelect: showCoords4
							});
						});
					});
				})
			<? } ?>

			<? if($cw5) { ?>
				$('.crop-4 h2 input.continuar').click(function(){
					$('.crop-4').fadeOut(function(){
						$('.crop-5').fadeIn(function(){
							$('#cropbox-5').Jcrop({
								boxWidth: 940,
								boxHeight: 560,
								aspectRatio: <?= $cw5/$ch5 ?>,
								setSelect: [0, 0, <?= $cw5 ?>, <?= $ch5 ?>],
								onChange: showCoords5,
								onSelect: showCoords5
							});
						});
					});
				})
			<? } ?>


		})

		function showCoords1(c)
		{
			$('#c1-x').val(c.x);
			$('#c1-y').val(c.y);
			$('#c1-w').val(c.w);
			$('#c1-h').val(c.h);
		};

		function showCoords2(c)
		{
			$('#c2-x').val(c.x);
			$('#c2-y').val(c.y);
			$('#c2-w').val(c.w);
			$('#c2-h').val(c.h);
		};

		function showCoords3(c)
		{
			$('#c3-x').val(c.x);
			$('#c3-y').val(c.y);
			$('#c3-w').val(c.w);
			$('#c3-h').val(c.h);
		};

		function showCoords4(c)
		{
			$('#c4-x').val(c.x);
			$('#c4-y').val(c.y);
			$('#c4-w').val(c.w);
			$('#c4-h').val(c.h);
		};

		function showCoords5(c)
		{
			$('#c5-x').val(c.x);
			$('#c5-y').val(c.y);
			$('#c5-w').val(c.w);
			$('#c5-h').val(c.h);
		};

	</script>

</head>
<body>

<div class='cropper-header'></div>
<div class='wrapper-cropper'>

	<h1>Image Cropper</h1>

	<? if($error) { ?>
		<div class='error'><?= $error ?></div>
	<? } ?>

	<?
		if(!$step2)
		{
			?>
				<form enctype="multipart/form-data" method='POST' action='<?= $dbo->keepUrl(); ?>' id="form-cropper">
				<div class='row'>
					<div class='item'>
						<label>Selecione a Imagem para tratamento (somente formato JPEG)</label>
						<div style='padding: 20px 0;'><input type='file' name='image' onChange="document.getElementById('form-cropper').submit();"></div>
						<input type='submit' value='enviar' class='button'>
					</div><!-- item -->
				</div><!-- row -->
				</form>
			<?
		}
		else
		{
			?>
			<form enctype="multipart/form-data" method='POST' action='<?= $dbo->keepUrl(); ?>'>
			<input type='hidden' name='original_file' value='<?= $file_path ?>'/>
			<input type='hidden' value='1' name='time2crop'/>
			<?
			if($cw1)
			{
			?>
				<div class='wrapper-crop crop-1'>
					<h2><span class='description'><?= (($cd1)?($cd1." - "):('')) ?>(<?= $cw1 ?> x <?= $ch1 ?> px)</span> <?= (($cw2)?("<input type='button' class='button continuar' value='Continuar...'>"):("<input type='submit' class='button' value='Finalizar'>")) ?><div class='clear'></div></h2>
					<img src='<?= $file_path ?>' id='cropbox-1' class='cropbox'/>
					<input type='hidden' name='c1-x' id='c1-x'/>
					<input type='hidden' name='c1-y' id='c1-y'/>
					<input type='hidden' name='c1-w' id='c1-w'/>
					<input type='hidden' name='c1-h' id='c1-h'/>
					<input type='hidden' name='cw1' value='<?= $cw1 ?>'/>
					<input type='hidden' name='ch1' value='<?= $ch1 ?>'/>
				</div>
			<?
			}
			if($cw2)
			{
			?>
				<div class='wrapper-crop crop-2 <?= (($cw1)?('hidden'):('')) ?>'>
					<h2><span class='description'><?= (($cd2)?($cd2." - "):('')) ?>(<?= $cw2 ?> x <?= $ch2 ?> px)</span> <?= (($cw3)?("<input type='button' class='button continuar' value='Continuar...'>"):("<input type='submit' class='button' value='Finalizar'>")) ?><div class='clear'></div></h2>
					<img src='<?= $file_path ?>' id='cropbox-2' class='cropbox'/>
					<input type='hidden' name='c2-x' id='c2-x'/>
					<input type='hidden' name='c2-y' id='c2-y'/>
					<input type='hidden' name='c2-w' id='c2-w'/>
					<input type='hidden' name='c2-h' id='c2-h'/>
					<input type='hidden' name='cw2' value='<?= $cw2 ?>'/>
					<input type='hidden' name='ch2' value='<?= $ch2 ?>'/>
				</div>
			<?
			}
			if($cw3)
			{
			?>
				<div class='wrapper-crop crop-3 <?= (($cw2)?('hidden'):('')) ?>'>
					<h2><span class='description'><?= (($cd3)?($cd3." - "):('')) ?>(<?= $cw3 ?> x <?= $ch3 ?> px)</span> <?= (($cw4)?("<input type='button' class='button continuar' value='Continuar...'>"):("<input type='submit' class='button' value='Finalizar'>")) ?><div class='clear'></div></h2>
					<img src='<?= $file_path ?>' id='cropbox-3' class='cropbox'/>
					<input type='hidden' name='c3-x' id='c3-x'/>
					<input type='hidden' name='c3-y' id='c3-y'/>
					<input type='hidden' name='c3-w' id='c3-w'/>
					<input type='hidden' name='c3-h' id='c3-h'/>
					<input type='hidden' name='cw3' value='<?= $cw3 ?>'/>
					<input type='hidden' name='ch3' value='<?= $ch3 ?>'/>
				</div>
			<?
			}
			if($cw4)
			{
			?>
				<div class='wrapper-crop crop-4 <?= (($cw2)?('hidden'):('')) ?>'>
					<h2><span class='description'><?= (($cd4)?($cd4." - "):('')) ?>(<?= $cw4 ?> x <?= $ch4 ?> px)</span> <?= (($cw5)?("<input type='button' class='button continuar' value='Continuar...'>"):("<input type='submit' class='button' value='Finalizar'>")) ?><div class='clear'></div></h2>
					<img src='<?= $file_path ?>' id='cropbox-4' class='cropbox'/>
					<input type='hidden' name='c4-x' id='c4-x'/>
					<input type='hidden' name='c4-y' id='c4-y'/>
					<input type='hidden' name='c4-w' id='c4-w'/>
					<input type='hidden' name='c4-h' id='c4-h'/>
					<input type='hidden' name='cw4' value='<?= $cw4 ?>'/>
					<input type='hidden' name='ch4' value='<?= $ch4 ?>'/>
				</div>
			<?
			}
			if($cw5)
			{
			?>
				<div class='wrapper-crop crop-5 <?= (($cw2)?('hidden'):('')) ?>'>
					<h2><span class='description'><?= (($cd5)?($cd5." - "):('')) ?>(<?= $cw5 ?> x <?= $ch5 ?> px)</span> <input type='submit' class='button' value='Finalizar'><div class='clear'></div></h2>
					<img src='<?= $file_path ?>' id='cropbox-5' class='cropbox'/>
					<input type='hidden' name='c5-x' id='c5-x'/>
					<input type='hidden' name='c5-y' id='c5-y'/>
					<input type='hidden' name='c5-w' id='c5-w'/>
					<input type='hidden' name='c5-h' id='c5-h'/>
					<input type='hidden' name='cw5' value='<?= $cw5 ?>'/>
					<input type='hidden' name='ch5' value='<?= $ch5 ?>'/>
				</div>
			<?
			}
			?></form><?
		}
	?>

</div>

</body>
</html>