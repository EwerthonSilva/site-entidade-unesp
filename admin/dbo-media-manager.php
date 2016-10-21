<? require_once("header.php"); ?>
<script src="<?= DBO_URL ?>/plugins/jcrop_dbo/js/jquery.Jcrop.min.js"></script>
<link rel="stylesheet" href="<?= DBO_URL ?>/plugins/jcrop_dbo/css/jquery.Jcrop.css" type="text/css" />
<style>
html, body { height: 100%; }
body { overflow-y: scroll !important; }
.processing-time, .dbo-queries-number { display: none; }
#main-wrap { height: 100%; }
.peixe-ajax-loader { width: 60px; height: 60px; font-size: 30px; text-align: center; line-height: 50px; border-radius: 1000px; background-color: rgba(1,1,1,.8); top: 50%; left: 50%; margin-left: -30px; margin-top: -30px; }
.peixe-ajax-loader span { display: none; }
</style>
<?

function calcThumbSize($width, $height, $x)
{
	$y = intval($x*$height/$width);
	return array($x,$y);
}

//setando tamanhos das imagens
$_system['media_manager']['image_sizes'] = array_merge($_system['media_manager']['default_image_sizes'], (array)$_system['media_manager']['image_sizes']);

//primeiro checando se o cidadão pode fazer upload de imagens
if(!hasPermission('media-manager'))
{
	?>
	<h3 class="text-center"><br /><br /><br />Erro: Você não tem permissão para fazer upload de imagens (media-manager)</h3>
	<?
}
else
{
	$img_token = generateToken();
	$media_folder_path = DBO_PATH.'/upload/dbo-media-manager/';
	$media_folder_url = DBO_URL.'/upload/dbo-media-manager/';
	$selected_file = ((file_exists($media_folder_path.$_GET['file']))?($_GET['file']):(false));

	if(!is_writable($media_folder_path))
	{
		?>
		<h3 class="text-center"><br /><br /><br />Erro: a pasta de upload não tem permissão de escrita</h3>
		<?
	}
	else
	{
		?>
		<div id="dbo-media-manager">
			<div class="coluna-1">
				<div class="row full">
					<div class="large-3 columns">
						<h3 class="no-margin">Inserir&nbsp;mídia</h3>
					</div>
					<div class="large-9 columns">
						<div class="row">
							<div class="columns small-12 large-din-right">
								<div class="row collapse">
									<div class="columns small-9 large-din-left">
										<input type="search" name="" id="" value="" class="no-margin font-12" placeholder="Procurar mídias..."/>
									</div>
									<div class="columns small-3 large-din-left">
										<span class="postfix radius font-12 pointer"><i class="fa fa-search fa-fw"></i></span>
									</div>
								</div>
							</div>
							<div class="columns small-12 large-din-right">
								<select name="" class="font-12">
									<option value="">Todas as mídias</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div id="block-media-list">
					<?
					$pag = new pagina();
					$pag->queryPaginas(array(
						'tipo' => 'midia',
						'order_by' => 'id',
						'order' => 'desc',
						'where' => (!hasPermission('media-manager-all') ? " created_by = '".loggedUser()."' " : ""),
					));
					if(!$pag->size())
					{
						?><h3 class="text-center"><br /><br /><br />Ainda não há mídias cadastradas</h3><?
					}
					else
					{
						?>
						<ul class="large-block-grid-8">
							<?
							do {
								?>
								<li class="wrapper-media-item <?= (($selected_file == $pag->imagem_destaque)?('active'):('')) ?>">
									<div class="media-item <?= (($selected_file == $pag->imagem_destaque)?('active'):('')) ?>" style="background-image: url('<?= $media_folder_url.'thumbs/small-'.$pag->imagem_destaque.(($selected_file == $pag->imagem_destaque)?('?='.$img_token):('')) ?>')" data-file="<?= $pag->imagem_destaque ?>">
										<span class="trigger-delete" data-file="<?= $pag->imagem_destaque ?>" data-url="<?= secureUrl('ajax-dbo-media-manager.php?action=delete-media&pagina_id='.$pag->id.'&'.CSRFVar()) ?>"><i class="fa fa-close"></i></span>
										<span class="legenda"><?= $pag->titulo ? $pag->titulo : $pag->imagem_destaque ?></span>
									</div>
								</li>
								<?php
							}while($pag->fetch());
							?>
						</ul>
						<?php
					}
					?>
				</div>
			</div>
			<div class="coluna-2">
				<div id="block-details">
					<?
					if($selected_file)
					{
						$pag->queryPaginas(array(
							'tipo' => 'midia',
							'where' => "imagem_destaque = '".$selected_file."'",
						));
						list($width, $height, $lixo, $lixo) = getimagesize($media_folder_path.$selected_file);
						$thumb_size = calcThumbSize($width, $height, 250);
						?>
						<h6>Detalhes</h6>
						<a href="#" id="title-upload" class="font-13 top-minus-2" style="padding-left: 10px;"><span class="underline">Enviar novo arquivo</span></a>
						<div id="detalhes">
							<div class="inner-wrap">
								<div class="text-center">
									<div id="main-pic">
										<img src="<?= $media_folder_url.$selected_file ?>?=<?= $img_token ?>" id="selected-image" data-width="<?= $width ?>" data-height="<?= $height ?>" data-file="<?= $selected_file ?>" style="background-color: #fff;" data-thumb-width="<?= $thumb_size[0] ?>" data-thumb-height="<?= $thumb_size[1] ?>"/>
									</div>
									<img src="<?= $media_folder_url.'thumbs/medium-'.$selected_file ?>?=<?= $img_token ?>" style="height: 0; width: 0; overflow: hidden;"/>
									<img src="<?= $media_folder_url.'thumbs/large-'.$selected_file ?>?=<?= $img_token ?>" style="height: 0; width: 0; overflow: hidden;"/>
								</div>
								<ul id="drop-crop" class="f-dropdown" data-dropdown-content aria-hidden="true" tabindex="-1">
									<?
									if(is_array($_system['media_manager']['crops']) && sizeof($_system['media_manager']['crops']))
									{
										foreach($_system['media_manager']['crops'] as $slug => $settings)
										{
											?>
											<li><a href="#" data-w="<?= $settings['width'] ?>" data-h="<?= $settings['height'] ?>" data-force_resize="<?= (($settings['force_resize'])?('true'):('false')) ?>"><?= htmlSpecialChars($settings['name']) ?></a></li>
											<?
										}
									}
									?>
								</ul>
								<div id="wrapper-tabela-detalhes">
									<div id="cropper-controls">
										<form method="post" action="<?= secureUrl('ajax-dbo-media-manager.php?action=do-crop&file='.$selected_file.'&'.CSRFVar()) ?>" class="no-margin peixe-json" id="form-crop" peixe-log>
											<div class="font-14 text-left" style="padding-left: 100px; color: #fff;">
												<p>
													<span style="position: relative; top: -5px; color: #999;" class="font-14">Aplicar o recorte:</span><br />
													<input type="radio" name="aplicar_crop" id="aplicar_crop_miniatura" value="miniatura"/><label for="aplicar_crop_miniatura">Somente na miniatura</label><br />
													<input type="radio" name="aplicar_crop" id="aplicar_crop_todos" value="todos" checked/><label for="aplicar_crop_todos">Em todos os tamanhos</label><br />
													<input type="radio" name="aplicar_crop" id="aplicar_crop_todos_menos_miniatura" value="todos_menos_miniatura"/><label for="aplicar_crop_todos_menos_miniatura">Todos, menos a miniatura</label>
												</p>
											</div>
											<input type="hidden" name="c-x" id="c-x" value=""/>
											<input type="hidden" name="c-y" id="c-y" value=""/>
											<input type="hidden" name="c-w" id="c-w" value=""/>
											<input type="hidden" name="c-h" id="c-h" value=""/>
											<input type="hidden" name="force_resize" id="force_resize" value="false"/>
											<span class="button radius large" onClick="doCrop();">Recortar</span>
											<span class="button radius secondary large" onClick="stopCrop();">Cancelar</span>
										</form>
									</div>
									<!-- <span class="button-crop" data-dropdown="drop-crop" title="Recortar" data-tooltip><i class="fa fa-crop"></i></span> -->

									<?php
									$croppeUrl = secureUrl(DBO_URL.'/../dbo-cropper.php?dbo_modal=1&src='.$pag->imagem_destaque.'&modulo='.$pag->modulo_anexado.'&coluna=');
									?>
									<a title="Recortar" rel="modal" href="<?=$croppeUrl?>"><i class="button-crop fa fa-crop"></i></a>
									<form action="<?= secureUrl(DBO_URL.'/../ajax-dbo-media-manager.php?action=update-media-image&media_id='.$pag->id.'&'.CSRFVar()) ?>" method="post" class="no-margin" id="form-media-image">
										<table class="tools" style="margin-bottom: 2px">
											<tbody>
												<tr>
													<td>Alinhamento</td>
													<td style="position: relative;">
														<div id="position-selector" class="selector">
															<span class="active"><i class="fa fa-fw fa-align-justify" title="Nenhum" data-tooltip data-value="text-left"></i></span>
															<span><i class="fa fa-fw fa-align-left" title="Esquerda" data-tooltip data-value="float-left"></i></span>
															<span><i class="fa fa-fw fa-align-center" title="Centro" data-tooltip data-value="text-center"></i></span>
															<span><i class="fa fa-fw fa-align-right" title="Direita" data-tooltip data-value="float-right"></i></span>
														</div>
													</td>
												</tr>
												<tr>
													<td>Título</td>
													<td>
														<input type="text" name="titulo" id="titulo" value="<?= htmlSpecialChars($pag->titulo) ?>" placeholder="Digite o título desta imagem" class="no-margin font-12"/>
													</td>
												</tr>
												<tr>
													<td>Legenda</td>
													<td>
														<input type="text" name="legenda" id="legenda" value="<?= $pag->getDetail('legenda') ?>" placeholder="Digite a legenda para a imagem" class="no-margin font-12"/>
													</td>
												</tr>
												<?php
												if($_GET['destiny'] != 'field')
												{
													?>
													<tr>
														<td>Tamanho</td>
														<td>
															<select id="size-selector" class="font-12 no-margin">
																<?
																foreach($_system['media_manager']['image_sizes'] as $slug => $data)
																{
																	list($w, $h) = getimagesize($media_folder_path.'thumbs/'.$slug.'-'.$selected_file);
																	?>
																	<option data-slug="<?= $slug ?>" data-value="thumbs/<?= $slug ?>-" <?= (($slug == 'medium')?('selected'):('')) ?> value="thumbs/<?= $slug ?>-"><?= $data['name'] ?> - <?= $w ?> &times; <?= $h ?></option>
																	<?
																}
																?>
																<option data-slug="original" data-value="" value="">Original - <?= $width ?> &times <?= $height ?></option>
															</select>
														</td>
													</tr>
													<?php
													if($_GET['destiny'] != 'content-tools')
													{
														?>
														<tr>
															<td>Linkar para</td>
															<td>
																<select class="font-12 no-margin">
																	<option value="nenhum">Nenhum</option>
																	<option value="arquivo">Arquivo original</option>
																	<option value="pagina-anexo">Página de anexo</option>
																	<option value="url">URL Personalizada</option>
																</select>
															</td>
														</tr>
														<?php
													}
												}
												?>
												<tr>
													<td style="vertical-align: top; padding-top: 10px;">Descrição</td>
													<td>
														<textarea name="texto" id="texto" class="no-margin font-12" style="height: 80px; resize: none;"><?= htmlSpecialChars($pag->texto) ?></textarea>
													</td>
												</tr>
											</tbody>
										</table>
										<input type="hidden" name="media_id" id="media_id" value="<?= $pag->id ?>"/>
									</form>
								</div>
								<div class="text-right">
									<?php
									if($_GET['destiny'])
									{
										?>
										<input type="button" name="" id="inserir-midia" value="Adicionar mídia" class="button no-margin radius peixe-save" data-destiny="<?= $_GET['destiny'] ?>"/>
										<?php
									}
									?>
								</div>
							</div>
						</div>
						<?
					}
					?>
				</div>
				<div id="block-upload" style="padding-top: 30px;">
					<form method="post" action="" class="no-margin" id="form-upload" enctype="multipart/form-data" style="<?= (($selected_file)?('display: none;'):('')) ?>">
						<input type="file" name="arquivo" id="arquivo" peixe-ajax-file-upload data-action="<?= secureUrl('ajax-dbo-media-manager.php?action=upload-file&'.CSRFVar()) ?>" data-modulo="<?= $_GET['modulo'] ?>" data-modulo_id="<?= $_GET['modulo_id'] ?>"/><label for="arquivo" id="arquivo-label"><i class="fa fa-cloud-upload" style="font-size: 20px;"></i> Enviar arquivo</label>
					</form>
				</div>
			</div>
		</div>
		<?
	}
}
?>
<script>

var jcrop_api;
var scale;
var timer_update_media;
var destiny = '<?= $_GET['destiny']; ?>';
var wrapper_id = '<?= $_GET['wrapper_id'] ?>';
var external_button = '<?= $_GET['external_button'] ?>';

function startCrop(w, h) {
	$('#drop-crop').css('left', '-99999px').removeClass('open');
	img = $('#selected-image');
	scale = img.data('width')/img.width();
	if(typeof w == 'number' && typeof h == 'number'){
		img.Jcrop({
			setSelect: [ 10, 10, 150, 150 ],
			onSelect: setCoords,
			onChange: setCoords,
			aspectRatio: w / h
		}, function(){
			jcrop_api = this;
		});
	}
	else {
		img.Jcrop({
			setSelect: [ 10, 10, 120, 120 ],
			onSelect: setCoords,
			onChange: setCoords,
			aspectRatio: null
		}, function(){
			jcrop_api = this;
		});
	}
	$('#cropper-controls').addClass('active');
}

function setCoords(c) {
	$('#c-x').val(c.x*scale);
	$('#c-y').val(c.y*scale);
	$('#c-w').val(c.w*scale);
	$('#c-h').val(c.h*scale);
}

function stopCrop() {
	jcrop_api.destroy();
	$('#cropper-controls').removeClass('active');
}

function mediaManagerInit() {
	peixeAjaxFileUploadInit();
}

function doCrop() {
	$('#form-crop').submit();
}

function selectItem(media_item) {
	peixeGet(((media_item)?(document.URL+'&file='+media_item.data('file')):('dbo-media-manager.php?dbo_modal=1')), function(d) {
		var html = $.parseHTML(d);
		/* item 1 */
		handler = '#block-details';
		content = $(html).find(handler).html();
		if(typeof content != 'undefined'){
			$(handler).fadeHtml(content);
		}
		/* item 2 */
		handler = '#block-details';
		content = $(html).find(handler).html();
		if(typeof content != 'undefined'){
			$(handler).fadeHtml(content);
		}
		mediaManagerInit();
	})
	$('#form-upload:visible').hide();
	return false;
}
function deselectItem(media_item) {
	selectItem(false);
	setTimeout(function(){
		$('#form-upload').slideDown();
	}, 500);
}

function reloadAfterCrop() {
	peixeGet(document.URL+'&file='+$('#selected-image').data('file'), function(d) {
		var html = $.parseHTML(d);
		/* item 1 */
		handler = '#block-media-list .wrapper-media-item.active';
		content = $(html).find(handler).html();
		if(typeof content != 'undefined'){
			$(handler).fadeHtml(content);
		}
		/* item 1 */
		handler = '#main-pic';
		content = $(html).find(handler).html();
		if(typeof content != 'undefined'){
			$(handler).fadeHtml(content);
		}
		/* item 1 */
		handler = '#main-pic-size';
		content = $(html).find(handler).html();
		if(typeof content != 'undefined'){
			$(handler).fadeHtml(content);
		}
	})
	return false;
}

function showFormUpload() {
	$('#form-upload').slideDown();
}

function inserirMidiaAtiva(destiny) {

	destiny = typeof destiny == 'undefined' ? 'tinymce' : destiny;

	var file_name = $('#main-pic img').data('file');

	if(destiny == 'field'){
		wrapper = $('#'+wrapper_id, parent.document);
		wrapper.find('.media-controls-insert').hide();
		wrapper.find('.media-controls-update').show();
		wrapper.find('img').attr('src', 'dbo/upload/dbo-media-manager/thumbs/medium-'+file_name+'?='+Math.random());
		wrapper.find('input[type="hidden"]').val(file_name);
		button_update = wrapper.find('.button-media-update');
		button_update.attr('data-url', keepUrl('file='+file_name, button_update.data('url')));
		parent.$.fn.colorbox.close();
	}
	else if(destiny == 'content-tools'){

		var mp = $('#main-pic img');

		var size = $('#size-selector').val();
		var width = mp.data('thumb-width');
		var height = mp.data('thumb-height');
		//var caption = $('#legenda').val();

		src = 'dbo/upload/dbo-media-manager/'+size+file_name;
		window.parent.ctInsertFromDboMediaManager(src, width, height);
		parent.$.fn.colorbox.close();

	}else if(destiny == 'tinymce'){
		//variaveis para montar a tag da imagem
		var align = $('#position-selector .active i').data('value');
		var size = $('#size-selector').val();
		var caption = $('#legenda').val();

		var       img = '<div media-manager-element="image-container" class="'+align+'">';
		var img = img + '<dl><dt>';
		var img = img + '<img media-manager-element="image" src="dbo/upload/dbo-media-manager/'+size+file_name+'">';

		//verificando se tem caption
		if(caption.length){
			img = img + '<dd class="text-left">'+caption+'</dd>';
		}

		var img = img + '</dt></dl>';
		var img = img + '</div>';

		parent.tinyMCE.activeEditor.insertContent(img);
		parent.tinyMCE.activeEditor.nodeChanged();
		parent.tinyMCE.activeEditor.windowManager.close();

		if(external_button){
			parent.$.fn.colorbox.close();
		}
	}
}

function updateMediaData() {
	form = $('#form-media-image');
	$.post(
		form.attr('action'),
		form.serialize(),
		function(data) {}
	)
	return false;
}

$(document).ready(function(){

	mediaManagerInit();

	//event handler para submissão automatica do form de upload.
	//document.querySelector('#arquivo').addEventListener('uploadDone', function(e){
	$(document).on('uploadDone', '#arquivo', function(e, detail){
		peixeGet(document.URL+'&file='+detail.new_file_name, function(d) {
			var html = $.parseHTML(d);
			/* item 1 */
			handler = '#block-media-list';
			content = $(html).find(handler).html();
			if(typeof content != 'undefined'){
				$(handler).fadeHtml(content);
			}
			/* item 2 */
			handler = '#block-details';
			content = $(html).find(handler).html();
			if(typeof content != 'undefined'){
				$(handler).fadeHtml(content);
			}
			/* item 3 */
			handler = '#block-upload';
			content = $(html).find(handler).html();
			if(typeof content != 'undefined'){
				$(handler).fadeHtml(content);
			}
			mediaManagerInit();
		})
		return false;
	});

	//clicks nos itens da listagem
	$(document).on('click', '.media-item', function(){
		clicado = $(this);
		if(clicado.hasClass('active')){
			clicado.removeClass('active').closest('li').removeClass('active');
			deselectItem(clicado);
		}
		else {
			$('.media-item').removeClass('active').closest('li').removeClass('active');
			clicado.addClass('active').closest('li').addClass('active');
			selectItem(clicado);
		}
	});

	//deletendo os itens da listagem
	$(document).on('click', '#dbo-media-manager .trigger-delete', function(e){
		e.stopPropagation();
		clicado = $(this);
		var ans = confirm("Tem certeza que deseja excluir a mídia \""+clicado.data('file')+"\"?");
		if (ans==true) {
			peixeJSON(clicado.data('url'), '', '', true);
		}
	});

	$(document).on('click', '.selector span', function(){
		clicado = $(this);
		clicado.closest('.selector').find('span').removeClass('active');
		clicado.addClass('active');
	});

	$(document).on('click', '#title-upload', function(e){
		e.preventDefault();
		if($('#block-details:visible').length){
			$('#block-details:visible').slideUp(function(){
				$('#form-upload').slideDown();
				$('#block-media-list .active').removeClass('active');
			})
		}
	});

	//dando trigger no selector dos crops
	$(document).on('click', '#drop-crop a', function(e){
		e.preventDefault();
		clicado = $(this);
		startCrop(clicado.data('w'), clicado.data('h'));
	});

	//tratando a inserção das imagens no editor
	$(document).on('click', '#inserir-midia', function(){
		inserirMidiaAtiva($(this).data('destiny'));
	});

	//atualizando os dados da media
	$(document).on('input', '#titulo, #texto, #legenda', function(){
		clearTimeout(timer_update_media);
		timer_update_media = setTimeout(function(){
			updateMediaData();
		}, 1200);
	});

}) //doc.ready
</script>
<? require_once("footer.php"); ?>
