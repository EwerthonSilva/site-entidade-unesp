<?php require_once('header.php') ?>
<link rel="stylesheet" href="js/cropper/main.css">
<link rel="stylesheet" href="js/cropper/cropper.min.css">
<script src="js/cropper/cropper.js"></script>
<script src="js/cropper/main.js"></script>
<style>
	#close-backdrop { position: absolute; top: 0; right:0; width: 60px;	height: 40px; background-color: #fff; z-index: 100; }
	.pretty-label { margin-bottom: 5px; }
	.pretty-label.vertical label { display: block; margin-bottom: 5px; }
	.pretty-label input[type="radio"] { display: none; }
	.pretty-label input[type="radio"]:checked + label { background: #333; color: #fff; border-color: #111; }
	.pretty-label label { margin-left: 0 !important; margin-right: 0 !important; }
	.modal-body { margin-bottom: 1rem; }
	.modal-body canvas { max-height: 400px; }
</style>
<?php
if(!secureUrl()){
	echo "Tentativa de acesso insegura";
	exit();
}

if($_GET['coluna'] != ''){
	$tipo = 'image';
	$imageUrl = DBO_URL.'/upload/files/'.$_GET['src'];
}else {
	$tipo = 'media';
	$imageUrl = DBO_URL.'/upload/dbo-media-manager/'.$_GET['src'];
}

//verificando definições de tamanhos de crop e tamanhos de imagem
if(!isset($_system['media_manager']['crops']))
{
	$_system['media_manager']['crops'] = array(
		'quadradro' => array(
			'name' => 'Recorte quadrado',
			'width' => 1,
			'height' => 1,
			'force_resize' => false
		),
		'livre' => array(
			'name' => 'Recorte livre',
			'width' => '',
			'height' => '', //NaN
			'force_resize' => false
		)
	);
}

?>
<script type="text/javascript">
var $url = "<?=secureUrl('dbo/core/dbo-cropper-ajax.php?action=do-crop&src='.$_GET['src'].'&modulo='.$_GET['modulo'].'&coluna='.$_GET['coluna'])?>";
var $oldName = "<?=$_GET['src']?>";
</script>

<div id="close-backdrop"></div>

<div class="container">
	<!-- Show the cropped image in modal -->
	<div class="reveal-modal fade docs-cropped" id="getCroppedCanvasModal" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle" role="dialog" tabindex="-1">
		<div class="modal-body"></div>
		<div class="modal-footer text-center">
			<a class="button large radius no-margin" id="download" href="javascript:void(0);"><i class="fa fa-check"></i> Salvar</a>
			<a href="javascript:void(0)" class="button large radius no-margin secondary" onClick="$('#getCroppedCanvasModal').foundation('reveal', 'close');"><i class="fa fa-times"></i> Cancelar</a>
		</div>
	</div>
	<!-- /.modal -->
	<!-- <h3 class="page-header">Demo:</h3> -->
	<div class="img-container">
		<img id="image" src="<?=$imageUrl?>?=<?= uniqid() ?>" alt="Picture" style="opacity: 0;">
	</div>

	<div class="row">
		<div class="large-12 columns docs-buttons">
			<!-- <h3 class="page-header">Toolbar:</h3> -->
			<div class="row">
				<div class="small-12 large-9 columns">
					<ul class="button-group radius inline-block" style="display: none !important;">
						<li>
							<button type="button" class="button small secondary" data-method="setDragMode" data-option="move" title="Arrastar a imagem">
								<span class="docs-tooltip"><i class="fa fa-arrows font-14"></i></span>
							</button>
						</li>
						<li>
							<button type="button" class="button small secondary" data-method="setDragMode" data-option="crop" title="Redimensionar a seleção">
								<i class="fa fa-crop font-14"></i>
							</button>
						</li>
					</ul>

					<?php
						if($_GET['allow_canvas_expansion'] != 'false')
						{
							?>
							<ul class="button-group radius inline-block docs-toggles pretty-label">
								<li>
									<input type="radio" class="sr-only" id="viewMode1" name="viewMode" value="1" checked>
									<label for="viewMode1" class="button small secondary margin-bottom" title="Manter o recorte dentro da imagem"><i class="fa fa-compress font-14"></i></label>
								</li>
								<li>
									<input type="radio" class="sr-only" id="viewMode0" name="viewMode" value="0">
									<label for="viewMode0" class="button small secondary margin-bottom" title="Permitir recorte fora da imagem"><i class="fa fa-expand font-14"></i></label>
								</li>
							</ul>
							<?php
						}
					?>


					<ul class="button-group radius inline-block">
						<li>
							<button type="button" class="button small secondary" data-method="reset" title="Reset">
								<i class="fa fa-undo font-14"></i>
							</button>
						</li>
					</ul>
					<ul class="button-group radius inline-block">
						<li>
							<button type="button" class="button small secondary" data-method="zoom" data-option="0.1" title="Mais zoom">
								<i class="fa fa-search-plus font-14"></i>
							</button>
						</li>
						<li>
							<button type="button" class="button small secondary" data-method="zoom" data-option="-0.1" title="Menos zoom">
								<i class="fa fa-search-minus font-14"></i>
							</button>
						</li>
					</ul>
					<ul class="button-group radius inline-block">
						<li>
							<button type="button" class="button small secondary" data-method="move" data-option="-10" data-second-option="0" title="Mover a image para a esquerda">
								<i class="fa fa-arrow-left font-14"></i>
							</button>
						</li>
						<li>
							<button type="button" class="button small secondary" data-method="move" data-option="10" data-second-option="0" title="Mover a image para a direita">
								<i class="fa fa-arrow-right font-14"></i>
							</button>
						</li>
						<li>
							<button type="button" class="button small secondary" data-method="move" data-option="0" data-second-option="-10" title="Mover a image para cima">
								<i class="fa fa-arrow-up font-14"></i>
							</button>
						</li>
						<li>
							<button type="button" class="button small secondary" data-method="move" data-option="0" data-second-option="10" title="Mover a image para baixo">
								<i class="fa fa-arrow-down font-14"></i>
							</button>
						</li>
					</ul>
					<ul class="button-group radius inline-block">
						<li>
							<button type="button" class="button small secondary" data-method="rotate" data-option="-45" title="Rotacionar para a esquerda">
								<i class="fa fa-rotate-left font-14"></i>
							</button>
						</li>
						<li>
							<button type="button" class="button small secondary" data-method="rotate" data-option="45" title="Rotacionar para a direita">
								<i class="fa fa-rotate-right font-14"></i>
							</button>
						</li>
						<li>
							<button type="button" class="button small secondary" data-method="scaleX" data-option="-1" title="Inverter na horizontal">
								<i class="fa fa-arrows-h font-14"></i>
							</button>
						</li>
						<li>
							<button type="button" class="button small secondary" data-method="scaleY" data-option="-1" title="Inverter na vertical">
								<i class="fa fa-arrows-v font-14"></i>
							</button>
						</li>
					</ul>

					<div class="row">
						<div class="large-4 columns docs-toggles">
							<label for="">Formato do recorte</label>
							<div class="pretty-label vertical">
								<?php
									foreach($_system['media_manager']['crops'] as $key => $value)
									{
										?>
										<input type="radio" class="sr-only" id="crop-size-<?= $key ?>" name="aspectRatio" value="<?= !is_numeric($value['width']) ? 'NaN' : $value['width'] / $value['height'] ?>" <?= !is_numeric($value['width']) ? 'checked' : '' ?>>
										<label for="crop-size-<?= $key ?>" class="button small secondary radius"><?= $value['name'] ?></label>
										<?php
									}
								?>
							</div>
						</div>
						<div class="small-12 large-6 columns end">
							<?php
							if ($tipo == 'media') {
								?>
								<label for="">Aplicar o recorte</label>
								<div class="pretty-label vertical">
									<input type="radio" name="aplicar_crop" id="aplicar_crop_miniatura" value="miniatura"/><label for="aplicar_crop_miniatura" class="button small secondary radius">Somente na miniatura</label>
									<input type="radio" name="aplicar_crop" id="aplicar_crop_todos" value="todos" checked/><label for="aplicar_crop_todos" class="button small secondary radius">Em todos os tamanhos</label>
									<input type="radio" name="aplicar_crop" id="aplicar_crop_todos_menos_miniatura" value="todos_menos_miniatura"/><label for="aplicar_crop_todos_menos_miniatura" class="button small secondary radius">Todos, menos a miniatura</label>
								</div>
								<?php
							} 
							?>
						</div>
					</div>
					
				</div>
				<div class="small-12 large-3 columns text-right">
					<button type="button" class="button large radius" data-method="getCroppedCanvas" title="Visualizar a imagem recortada">
						<i class="fa fa-eye" style="font-size: 40px;"></i>
						Visualizar
					</button>
				</div>
			</div>


		</div><!-- /.docs-buttons -->
	</div>

	<script>
		$(function () {

			'use strict';

			var console = window.console || { log: function () {} };
			var $image = $('#image');
			var $download = $('#download');
			var $dataX = $('#dataX');
			var $dataY = $('#dataY');
			var $dataHeight = $('#dataHeight');
			var $dataWidth = $('#dataWidth');
			var $dataRotate = $('#dataRotate');
			var $dataScaleX = $('#dataScaleX');
			var $dataScaleY = $('#dataScaleY');
			var options = {
				aspectRatio: NaN,
				preview: '.img-preview',
				viewMode: 1,
				crop: function (e) {
					$dataX.val(Math.round(e.x));
					$dataY.val(Math.round(e.y));
					$dataHeight.val(Math.round(e.height));
					$dataWidth.val(Math.round(e.width));
					$dataRotate.val(e.rotate);
					$dataScaleX.val(e.scaleX);
					$dataScaleY.val(e.scaleY);
				}
			};


			// Tooltip
			$('[data-toggle="tooltip"]').tooltip();


			// Cropper
			$image.on({
				'build.cropper': function (e) {},
				'built.cropper': function (e) {},
				'cropstart.cropper': function (e) {},
				'cropmove.cropper': function (e) {},
				'cropend.cropper': function (e) {},
				'crop.cropper': function (e) {},
				'zoom.cropper': function (e){}
			}).cropper(options);


			// Buttons
			if (!$.isFunction(document.createElement('canvas').getContext)) {
				$('button[data-method="getCroppedCanvas"]').prop('disabled', true);
			}

			if (typeof document.createElement('cropper').style.transition === 'undefined') {
				$('button[data-method="rotate"]').prop('disabled', true);
				$('button[data-method="scale"]').prop('disabled', true);
			}


			// Download
			if (typeof $download[0].download === 'undefined') {
				$download.addClass('disabled');
			}


			// Options
			$('.docs-toggles').on('change', 'input', function () {
				var $this = $(this);
				var name = $this.attr('name');
				var type = $this.prop('type');
				var cropBoxData;
				var canvasData;

				if (!$image.data('cropper')) {
					return;
				}

				if (type === 'checkbox') {
					options[name] = $this.prop('checked');
					cropBoxData = $image.cropper('getCropBoxData');
					canvasData = $image.cropper('getCanvasData');

					options.built = function () {
						$image.cropper('setCropBoxData', cropBoxData);
						$image.cropper('setCanvasData', canvasData);
					};
				} else if (type === 'radio') {
					options[name] = $this.val();
				}

				$image.cropper('destroy').cropper(options);
			});


			// Methods
			$('.docs-buttons').on('click', '[data-method]', function () {
				var $this = $(this);
				var data = $this.data();
				var $target;
				var result;

				if ($this.prop('disabled') || $this.hasClass('disabled')) {
					return;
				}

				if ($image.data('cropper') && data.method) {
					data = $.extend({}, data); // Clone a new one

					if (typeof data.target !== 'undefined') {
						$target = $(data.target);

						if (typeof data.option === 'undefined') {
							try {
								data.option = JSON.parse($target.val());
							} catch (e) {
								console.log(e.message);
							}
						}
					}

					result = $image.cropper(data.method, data.option, data.secondOption);

					switch (data.method) {
						case 'scaleX':
						case 'scaleY':
						$(this).data('option', -data.option);
						break;

						case 'getCroppedCanvas':
						if (result) {

							// Bootstrap's Modal
							$('#getCroppedCanvasModal').foundation('reveal', 'open').find('.modal-body').html(result);

							if (!$download.hasClass('disabled')) {
								var $img_encript;
								var suffix = $oldName.split('.').pop();
								if((suffix == 'jpg')||(suffix == 'jpeg')){
									$img_encript = result.toDataURL('image/jpeg');
								}else if (suffix == 'gif') {
									$img_encript = result.toDataURL('image/gif');
								}else if (suffix == 'png') {
									$img_encript = result.toDataURL('image/png');
								}

								$download.on('click', function send2Server(){
									peixeJSON($url, { imgBase64: $img_encript, aplicar_crop: $('input[name="aplicar_crop"]:checked').val() }, null, true);
									return false;
								});

							}
						}

						break;
					}

					if ($.isPlainObject(result) && $target) {
						try {
							$target.val(JSON.stringify(result));
						} catch (e) {
							console.log(e.message);
						}
					}

				}
			});


			// Keyboard
			$(document.body).on('keydown', function (e) {

				if (!$image.data('cropper') || this.scrollTop > 300) {
					return;
				}

				switch (e.which) {
					case 37:
					e.preventDefault();
					$image.cropper('move', -1, 0);
					break;

					case 38:
					e.preventDefault();
					$image.cropper('move', 0, -1);
					break;

					case 39:
					e.preventDefault();
					$image.cropper('move', 1, 0);
					break;

					case 40:
					e.preventDefault();
					$image.cropper('move', 0, 1);
					break;
				}

			});


			// Import image
			var $inputImage = $('#inputImage');
			var URL = window.URL || window.webkitURL;
			var blobURL;

			if (URL) {
				$inputImage.change(function () {
					var files = this.files;
					var file;

					if (!$image.data('cropper')) {
						return;
					}

					if (files && files.length) {
						file = files[0];

						if (/^image\/\w+$/.test(file.type)) {
							blobURL = URL.createObjectURL(file);
							$image.one('built.cropper', function () {

								// Revoke when load complete
								URL.revokeObjectURL(blobURL);
							}).cropper('reset').cropper('replace', blobURL);
							$inputImage.val('');
						} else {
							window.alert('Please choose an image file.');
						}
					}
				});
			} else {
				$inputImage.prop('disabled', true).parent().addClass('disabled');
			}

		});
	</script>

	<?php require_once('footer.php') ?>