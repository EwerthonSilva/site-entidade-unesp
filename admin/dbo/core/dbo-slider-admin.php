<?php

	require_once(DBO_PATH.'/core/dbo-ui.php');

	function formDboSliderUpdate($slider)
	{

		//selecionando o slide ativo
		$active_slide = $_GET['active_slide'] ? $_GET['active_slide'] : $slider->getFirstSlide()->id;


		ob_start();
		?>
		<style>
			#wrapper-dbo-slider { background-image: url(images/transparent-placeholder.png); box-shadow: 0px 0px 10px rgba(1,1,1,.3); padding: 32px 0; }
			#dbo-slider { border: 1px solid rgba(1,1,1,.3); border-top: 0; border-bottom: 0; margin: 0 auto; transition: all .3s ease-out; }
			#dbo-slider-bleed { outline: 1px solid #999; }
			.tabs .ui-sortable-placeholder { padding-top: 0 !important; padding-bottom: 0; margin: 0 !important; }
			.tabs .ui-sortable-helper { border-color: transparent !important; }
			.fixed-width { width: 130px; }
		</style>
		<div id="wrapper-form-dbo-slider-update" style="padding-bottom: 250px;">
			<?php
				if(hasPermission('slider-configuracoes'))
				{
					?>
					<div class="row relative">
						<div class="settings-toolbar">
							<span class="color light pointer toggle-settings-box" data-settings-box="settings-box-slider" id="toggle-slider-settings"><i class="fa fa-cog"></i> Configurações do slider</span>
						</div>
					</div>
					<div class="settings-box" id="settings-box-slider">
						<div class="row">
							<div class="large-12 columns">
								<h3>Configurações</h3>
								<div class="font-14">
									<form action="<?= secureUrl('dbo/core/dbo-slider-ajax.php?action=update-slider&slider_id='.$slider->id) ?>" id="form-slider" class="peixe-json no-margin" peixe-log>
										<div class="row">
											<div class="large-6 columns">
												<div>
													<label for="" class="inline-block fixed-width">Tamanho do slider</label> 
													<input type="text" name="slider_width" id="slider_width" value="<?= $slider->getSetting('width') ?>" class="slider-setting inline-block w-auto font-12 text-center" size="2"/> &times;
													<input type="text" name="slider_height" id="slider_height" value="<?= $slider->getSetting('height') ?>" class="slider-setting inline-block w-auto font-12 text-center" size="2"/> px
												</div>
												<div>
													<label for="" class="inline-block fixed-width">Tempo de transição</label> 
													<input type="text" name="autoplaySpeed" id="autoplaySpeed" value="<?= $slider->getSetting('autoplaySpeed') ?>" class="slider-setting inline-block w-auto font-12 text-center" size="2"/> ms
												</div>
												<div>
													<label for="" class="inline-block fixed-width">Tipo do slider</label>
													<span class="inline-block">
														<input type="radio" name="slider_tipo" id="slider-sangrado" value="bleed" <?= $slider->getSetting('tipo') == 'bleed' ? 'checked' : '' ?>/><label for="slider-sangrado">Sangrado</label>
														<input type="radio" name="slider_tipo" id="slider-contido" value="contain" <?= $slider->getSetting('tipo') == 'contain' ? 'checked' : '' ?>/><label for="slider-contido">Contido</label>
													</span>
												</div>
											</div>
											<div class="large-6 columns" style="padding-top: 60px;">
												<div class="text-right">
													<input type="submit" name="" id="" value="Salvar configurações" class="button radius no-margin"/>
												</div>
											</div>
										</div>
										<?= CSRFInput() ?>
									</form>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
			?>
			<div class="row">
				<div class="large-12 columns">
					<h3>Preview</h3>
				</div>
			</div>
			<div id="wrapper-dbo-slider" class="margin-bottom">
				<div id="dbo-slider-bleed">
					<div id="dbo-slider" style="width: <?= $slider->getSetting('width') ?>px; height: <?= $slider->getSetting('height') ?>px;">
			
					</div>
				</div>
			</div>
			
			<?= sliderRenderSlidesTabBar($slider->id, array('active_slide' => $active_slide)) ?>
			
			<?= sliderRenderFormSlideContent($active_slide) ?>
			
			<script>
			
				slider = {
			
					input_timer: null,
					data: {},
			
					updateData: function() {
						this.data.width = $('#slider_width').val();
						this.data.height = $('#slider_height').val();
						this.data.autoplaySpeed = $('#autoplaySpeed').val();
						console.log('slider data updated');
					},
			
					updatePreview: function(){
						preview = $('#dbo-slider');
						preview.css('width', this.data.width+'px');
						preview.css('height', this.data.height+'px');
						console.log('slider preview updated');
					}
			
				};
			
				slide = {
					input_timer: null,
					data: {},
			
					updateData: function() {
					},
			
					updateTitulo: function() {
						$('#tabs-slides .dbo-tab.active').html('<br />'+(document.querySelector('#slide-titulo').value.trim().length == 0 ? 'Slide sem título' : document.querySelector('#slide-titulo').value)+'<br />&nbsp;');
					},
			
					updatePreview: function(){
						//pegar o tipo de slider, sangrado ou não, para saber onde aplicar o efeito desejado.
						tipo_slider = $('input[name="slider_tipo"]:checked').val();
						if(tipo_slider == 'bleed'){
							wrapper = $('#dbo-slider-bleed');
							other_wrapper = $('#dbo-slider');
						} else {
							wrapper = $('#dbo-slider');
							other_wrapper = $('#dbo-slider-bleed');
						}

						//pegando o tipo de background
						tipo_background = $('input[name="background_type"]:checked').val();
						if(tipo_background == 'solid'){
							wrapper.css('background-color', $('#slide-bg-color').val());
						}

						console.log('slide preview updated');
					},

					resetBackground: function(){
						
					},

				};
			
				$(document).on('click', '#tabs-slides .dbo-tab', function(){
					c = $(this);
					$('#tabs-slides .dbo-tab.active').removeClass('active');
					c.addClass('active');
				});

				function sliderInit() {
					$('#tabs-slides .tabs').sortable({
						//axis: 'x',
						distance: 10
					});
				}
			
				$(document).ready(function(){
			
					sliderInit();
			
					//atualizando as configurações do slide
					$(document).on('input', '.slide-setting', function(){
						clearTimeout(slide.input_timer);
						slide.input_timer = setTimeout(function(){
							slide.updateData();
							slide.updatePreview();
						}, 350);
					});
			
					$(document).on('change', '.slide-setting', function(){
						clearTimeout(slide.input_timer);
						slide.input_timer = setTimeout(function(){
							slide.updateData();
							slide.updatePreview();
						}, 350);
					});
			
					//atualizando o tituo do site
					$(document).on('input', 'input[name^="titulo"]', function(){
						slide.updateTitulo();
					});

					//mostrando caixas de seleção do background
					$(document).on('change', 'input[name="background_type"]:checked', function(){
						c = $(this);
						$('.wrapper-background-options').hide();
						$('#wrapper-background-'+c.val()).fadeIn('fast');
					});
			
				}) //doc.ready
			</script>
		</div>

		<?php
		return ob_get_clean();
	}

	function sliderRenderSlidesTabBar($slider_id, $params = array())
	{
		/* Params
			active_slide -> id do slide ativo
		*/

		extract($params);

		$slide = new dbo_slider_slide("WHERE slider = '".dboescape($slider_id)."' ORDER BY order_by");

		ob_start();
		?>
		<div class="dbo-tab-bar margin-bottom" id="tabs-slides">
			<div class="row">
				<span class="tabs inline-block" style="max-width: calc(100% - 120px)">
					<?php
						if($slide->size())
						{
							do {
								?>
								<span class="dbo-tab <?= $active_slide == $slide->id ? 'active' : '' ?>" data-slide_id="<?= $slide->id ?>" peixe-reload=".wrapper-slide-content" data-url="<?= keepUrl('active_slide='.$slide->id) ?>"><br /><?= strlen(trim($slide->titulo)) ? htmlSpecialChars($slide->titulo) : 'Slide sem título' ?><br />&nbsp;</span>
								<?php
							}while($slide->fetch());
						}
						else
						{
							?>
							<span class="dbo-tab" style="cursor: default;"><br /><br />&nbsp;</span>
							<?php
						}
					?>
				</span>
				<span class="right relative top-20" style="right: 15px;">
					<a href="<?= secureUrl('dbo/core/dbo-slider-ajax.php?action=adicionar-slide&slider_id='.$slider_id.'&current_url='.dboEncode(fullUrl()).'&'.CSRFVar()) ?>" class="no-margin peixe-json font-13" peixe-log><i class="fa fa-plus-circle font-14"></i> <span class="underline">Adicionar slide</span></a>
				</span>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	function sliderRenderFormSlideContent($slide_id)
	{
		ob_start();
		$slide = new dbo_slider_slide($slide_id);
		if($slide->size())
		{
			?>
			<div class="dbo-tab-content active wrapper-slide-content" id="dbo-tab-slide-1">
				<div class="row relative">
					<div class="settings-toolbar">
						<span class="color light pointer toggle-settings-box" data-settings-box="settings-slide-<?= $slide->id ?>" id="toggle-slide-settings"><i class="fa fa-cog"></i> Configurações do slide</span>
					</div>
				</div>
				
				<div class="settings-box <?= !strlen(trim($slide->titulo)) ? 'open' : '' ?>" id="settings-slide-<?= $slide->id ?>">
					<div class="row">
						<div class="large-12 columns">
							<form method="post" action="<?= secureUrl('dbo/core/dbo-slider-ajax.php?action=update-slide-settings&slide_id='.$slide->id.'&'.CSRFVar()) ?>" class="no-margin peixe-json" id="form-dbo-slide" peixe-log peixe-silent>
								<h3>Configurações gerais do slide</h3>
								<div class="font-14">
									<div class="row realative">
										<div class="large-12 columns">
											<div>
												<label class="inline-block fixed-width">Título do slide</label>
												<input type="text" name="titulo" id="slide-titulo" value="<?= htmlSpecialChars($slide->titulo) ?>" class="inline-block w-auto font-12 " size="60"/>
											</div>
											<div>
												<label class="inline-block fixed-width margin-bottom-20">Status</label>
												<input type="radio" name="status" id="status-publicado" value="publicado" class="" <?= $slide->status == 'publicado' ? 'checked' : '' ?>/><label for="status-publicado">Publicado</label>
												<input type="radio" name="status" id="status-rascunho" value="rascunho" class="" <?= $slide->status == 'rascunho' ? 'checked' : '' ?>/><label for="status-rascunho">Rascunho</label>
											</div>
											<div>
												<label for="" class="inline-block fixed-width margin-bottom-20">Background</label>
												<input type="radio" name="background_type" id="slide_background-transparent" value="transparent" class="" <?= $slide->getSetting('background_type') == 'transparent' ? 'checked' : '' ?>/><label for="slide_background-transparent">Transparente</label>
												<input type="radio" name="background_type" id="slide_background-solid" value="solid" class="" <?= $slide->getSetting('background_type') == 'solid' ? 'checked' : '' ?>/><label for="slide_background-solid">Cor sólida</label>
												<input type="radio" name="background_type" id="slide_background-image" value="image" class="" <?= $slide->getSetting('background_type') == 'image' ? 'checked' : '' ?>/><label for="slide_background-image">Imagem</label>
											</div>
											<div class="wrapper-background-options" id="wrapper-background-solid" style="display: none;">
												<label for="" class="inline-block fixed-width">HEX</label>
												<input type="text" name="bg_color" id="slide-bg-color" value="<?= $slide->bg_color; ?>" class="inline-block w-auto font-12 slide-setting" size="10"/>
											</div>
											<div class="wrapper-background-options" id="wrapper-background-image" style="display: none;">
												imagem
											</div>
										</div>
										<div class="text-center" style="position: absolute; bottom: 0; right: 15px;">
											<a href="<?= secureUrl('dbo/core/dbo-slider-ajax.php?action=excluir-slide&slide_id='.$slide->id.'&url='.dboEncode(keepUrl()).'&'.CSRFVar()) ?>" class="top-minus-15 peixe-json" data-confirm="Tem certeza que deseja remover este slide?" peixe-log><i class="fa fa-times"></i> <span class="underline">remover slide</span></a><br />
											<input type="submit" class="button radius no-margin" name="" id="" value="Salvar configurações"/>
										</div>
									</div>
								</div>
								<?= CSRFInput() ?>
							</form>
						</div>
					</div>
				</div>
				
				<section id="wrapper-camadas" style="<?= !strlen(trim($slide->titulo)) ? 'display: none;' : '' ?>" class="top-minus-7">
					<div class="row">
						<div class="large-12 columns flex-container flex-align-baseline flex-space-between margin-bottom">
							<h4 class="no-margin flex-grow-1">Camadas</h4>
							<span class="font-13 text-right" style="flex-basis: 250px;">
								<a href="" class="no-margin"><i class="fa fa-plus-circle font-14"></i> <span class="underline">Adicionar texto</span></a> &nbsp;
								<a href="" class="no-margin"><i class="fa fa-plus-circle font-14"></i> <span class="underline">Adicionar imagem</span></a> 
							</span>
						</div>
						<div class="row">
							<div class="large-12 columns">
								<div id="wrapper-lista-camadas">
									<?php
										$camadas = $slide->getSetting('layers');
										if(is_array($camadas) && sizeof($camadas))
										{
											
										}
										else
										{
											?>
											<div class="text-right" id="helper-camadas-placeholder">
												<div class="helper arrow-top">
													<p class="no-margin">Utilize os botões acima para adicionar conteúdo a seu slide.</p>
												</div>
											</div>
											<?php
										}
									?>
								</div>
							</div>
						</div>
						<div class="large-5 columns hidden">
							<h4>Linha do tempo</h4>
						</div>
					</div>
				</section>
			</div>		
			<?php
		}
		else
		{
			?>
			<div class="dbo-tab-content active wrapper-slide-content">
				<div class="row">
					<div class="large-12 columns text-right">
						<div class="helper arrow-top">
							<p class="no-margin text-left">
								Ainda não há slides cadastrados.<br />
								Utilize o botão acima para adicionar.
							</p>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
		?>
		<?php
		return ob_get_clean();
	}

?>