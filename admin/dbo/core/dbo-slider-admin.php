<?php

	require_once(DBO_PATH.'/core/dbo-ui.php');

	$default_props = array(
		'top' => '40%',
		'left' => '40%',
		'width' => '20%',
		'height' => '',
		'text' => 'Lorem ipsum',
		'font-size' => '1',
		'padding' => '0',
		'line-height' => '1',
		'letter-spacing' => '0',
		'peixe-animation-delay' => '0',
		'peixe-animation-duration' => '0.5',
	);

	function dboSliderRenderLayerTab($lay)
	{
		ob_start();

		global $_system; 
		$slider_settings = array_merge_recursive($_system['dbo_slider']['default_settings'], (array)$_system['dbo_slider']['settings']);
		if(sizeof((array)$slider_settings['colors']))
		{
			asort($slider_settings['colors']);
		}

		?>
		<li class="dbo-slide-layer-tab" data-id="layer-<?= $lay->id ?>">
			<div class="row">
				<div class="small-12 large-12 columns">
					<span class="layer-name">
						<input type="text" name="layer[<?= $lay->id ?>][titulo]" id="" value="<?= htmlSpecialChars($lay->titulo) ?>" required/>
					</span>
					<div class="open-close class color medium font-14 pointer">
						<i class="fa fa-minus-square-o fa-fw open trigger-deselect-layers"></i>
						<i class="fa fa-plus-square-o fa-fw close"></i>
					</div>
					<div class="handle class color medium font-14"><i class="fa fa-ellipsis-h fa-fw"></i></div>
				</div>
			</div>
			<div class="details font-14">
				<?php
					if($lay->tipo == 'text')
					{
						?>
						<div class="row">
							<div class="small-12 large-12 columns" style="margin-top: 3px;">
								<ul class="button-group left" style="margin-bottom: 10px;">
									<?php $id = 'layer-'.$lay->id.'-text-align-left'; ?>
									<li>
										<input type="radio" name="layer[<?= $lay->id ?>][settings][classes][]" id="<?= $id ?>" value="text-left" <?= in_array('text-left', (array)$lay->getSetting('classes')) ? 'checked' : '' ?> data-layer-prop data-layer-prop-type="class" data-layer-prop-options="text-left,text-center,text-right" class="hidden"/>
										<label for="<?= $id ?>" class="button secondary tiny" title="Alinhar à esquerda"><i class="fa fa-align-left font-14"></i></label>
									</li>
									<?php $id = 'layer-'.$lay->id.'-text-align-center'; ?>
									<li>
										<input type="radio" name="layer[<?= $lay->id ?>][settings][classes][]" id="<?= $id ?>" value="text-center" <?= in_array('text-center', (array)$lay->getSetting('classes')) ? 'checked' : '' ?> data-layer-prop data-layer-prop-type="class" data-layer-prop-options="text-left,text-center,text-right" class="hidden"/>
										<label for="<?= $id ?>" class="button secondary tiny" title="Alinhar ao centro"><i class="fa fa-align-center font-14"></i></label>
									</li>
									<?php $id = 'layer-'.$lay->id.'-text-align-right'; ?>
									<li>
										<input type="radio" name="layer[<?= $lay->id ?>][settings][classes][]" id="<?= $id ?>" value="text-right" <?= in_array('text-right', (array)$lay->getSetting('classes')) ? 'checked' : '' ?> data-layer-prop data-layer-prop-type="class" data-layer-prop-options="text-left,text-center,text-right" class="hidden"/>
										<label for="<?= $id ?>" class="button secondary tiny" title="Alinhar à direita"><i class="fa fa-align-right font-14"></i></label>
									</li>
								</ul>															
								<ul class="button-group left">
									<?php $id = 'layer-'.$lay->id.'-font-weight'; ?>
									<li><input type="checkbox" name="layer[<?= $lay->id ?>][settings][font-weight]" id="<?= $id ?>" value="bold" <?= $lay->getSetting('font-weight') == 'bold' ? 'checked' : '' ?> class="hidden" data-layer-prop-type="css" data-layer-prop="font-weight" data-layer-prop-toggle/><label for="<?= $id ?>" title="Negrito" class="tiny button secondary"><i class="fa fa-bold font-14"></i></label></li>
									<?php $id = 'layer-'.$lay->id.'-font-style'; ?>
									<li><input type="checkbox" name="layer[<?= $lay->id ?>][settings][font-style]" id="<?= $id ?>" value="italic" <?= $lay->getSetting('font-style') == 'italic' ? 'checked' : '' ?> class="hidden" data-layer-prop-type="css" data-layer-prop="font-style" data-layer-prop-toggle/><label for="<?= $id ?>" title="Itálico" class="tiny button secondary"><i class="fa fa-italic font-14"></i></label></li>
								</ul>
							</div>
						</div>
						<div class="row">
							<div class="small-12 large-4 columns">
								<i class="fa fa-font fa-fw help" title="Tamanho da fonte (em)"></i>
								<?= dboValueStepper($lay->getSetting('font-size'), array(
									'name' => 'layer['.$lay->id.'][settings][font-size]',
									'min_value' => '0.5',
									'step' => '0.1',
									'data_attrs' => array(
										'layer-prop' => 'font-size',
										'layer-prop-type' => 'css',
										'layer-prop-unit' => 'em',
									),
									'input_classes' => 'icon-label',
								)) ?>
							</div>
							<div class="small-12 large-8 columns">
								<select name="layer[<?= $lay->id ?>][settings][font-family]" data-layer-prop="font-family" data-layer-prop-type="css" class="icon-label no-icon">
									<?php
										if(sizeof((array)$slider_settings['fonts']))
										{
											foreach($slider_settings['fonts'] as $key => $dados)
											{
												?>
												<option value="<?= $key ?>" <?= $lay->getSetting('font-family') == $key ? 'selected' : '' ?>><?= $dados['label'] ?></option>
												<?php
											}
										}
										else
										{
											?>
											<option value="inherit">Fonte padrão</option>
											<?php
										}
									?>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="small-12 large-4 columns">
								<i class="fa fa-arrows-alt fa-fw help" title="Espaço interno"></i>
								<?= dboValueStepper($lay->getSetting('padding'), array(
									'name' => 'layer['.$lay->id.'][settings][padding]',
									'min_value' => '0',
									'step' => '0.1',
									'data_attrs' => array(
										'layer-prop' => 'padding',
										'layer-prop-type' => 'css',
										'layer-prop-unit' => 'em',
									),
									'input_classes' => 'icon-label',
								)) ?>
							</div>
							<div class="small-12 large-4 columns">
								<i class="fa fa-text-height fa-fw help" title="Altura da linha"></i>
								<?= dboValueStepper($lay->getSetting('line-height'), array(
									'name' => 'layer['.$lay->id.'][settings][line-height]',
									'min_value' => '0',
									'step' => '0.1',
									'data_attrs' => array(
										'layer-prop' => 'line-height',
										'layer-prop-type' => 'css',
										//'layer-prop-unit' => 'em',
									),
									'input_classes' => 'icon-label',
								)) ?>
							</div>
							<div class="small-12 large-4 columns">
								<i class="fa fa-text-width fa-fw help" title="Espaçamento das letras"></i>
								<?= dboValueStepper($lay->getSetting('letter-spacing'), array(
									'name' => 'layer['.$lay->id.'][settings][letter-spacing]',
									'min_value' => '-1',
									'step' => '0.025',
									'data_attrs' => array(
										'layer-prop' => 'letter-spacing',
										'layer-prop-type' => 'css',
										'layer-prop-unit' => 'em',
									),
									'input_classes' => 'icon-label',
								)) ?>
							</div>
						</div>
						<div class="row">
							<div class="small-12 large-5 columns">
								<i class="fa fa-pencil fa-fw"></i>
								<select name="layer[<?= $lay->id ?>][settings][color]" class="icon-label" data-layer-prop="color" data-layer-prop-type="css">
									<option style="background-color: #ddd;" value="">Padrão</option>
									<?php
										foreach($slider_settings['colors'] as $key => $value)
										{
											?>
											<option style="background-color: #ddd; color: <?= $key ?>;" value="<?= $key ?>" <?= $key == $lay->getSetting('color') ? 'selected' : '' ?>><?= $value ?></option>
											<?php
										}
									?>
								</select>
							</div>
							<div class="small-12 large-5 columns">
								<i class="fa fa-pencil-square fa-fw"></i>
								<select name="layer[<?= $lay->id ?>][settings][background-color]" class="icon-label" data-layer-prop="background-color" data-layer-prop-type="css">
									<option value="" style="color: #666;">Transparente</option>
									<?php
										foreach($slider_settings['colors'] as $key => $value)
										{
											?>
											<option style="color: #666; background-color: <?= $key ?>;" value="<?= $key ?>" <?= $key == $lay->getSetting('background-color') ? 'selected' : '' ?>><?= $value ?></option>
											<?php
										}
									?>
								</select>
							</div>
							<div class="small-12 large-2 columns">
								<?= dboValueStepper(($lay->getSetting('background-alpha') ? $lay->getSetting('background-alpha') : 100), array(
									'name' => 'layer['.$lay->id.'][settings][background-alpha]',
									'min_value' => '1',
									'step' => '1',
									'data_attrs' => array(
										'layer-prop' => 'background-alpha',
										'layer-prop-type' => 'css',
									),
									'input_classes' => 'icon-label no-icon',
									'classes' => 'no-icon',
								)) ?>
							</div>
						</div>
						<div class="row">
							<div class="small-12 large-12 columns">
								<textarea data-layer-prop="text" data-layer-prop-type="text" id="" name="layer[<?= $lay->id ?>][settings][text]" rows="4" style="display: block; width: calc(100% - 22px); margin-left: 22px;"  class="icon-label no-icon"><?= htmlSpecialChars($lay->getSetting('text')) ?></textarea>
							</div>
						</div>
						<?php
					}
					elseif($lay->tipo == 'image')
					{
						?>
						<div class="row">
							<div class="small-12 large-12 columns">
								<i class="fa fa-image fa-fw help" title="Imagem"></i>
								<input type="text" name="layer[<?= $lay->id ?>][settings][background-image]" data-layer-prop="background-image" data-layer-prop-type="css" id="layer-<?= $lay->id ?>-background-image" value="<?= htmlSpecialChars($lay->getSetting('background-image')) ?>" class="icon-label"/>
								<div class="text-right" style="padding-right: 7px;">
									<button data-url="dbo-media-manager.php?dbo_modal=1&destiny=background&wrapper_id=layer-<?= $lay->id ?>&input_id=layer-<?= $lay->id ?>-background-image&default_size=large" rel="modal" data-modal-width="100%" data-modal-height="100%" class="button radius small pointer trigger-media-manager" data-target="#<?= $slide_id ?>">Selecionar imagem...</button>
								</div>
							</div>
						</div>
						<?php
					}
					elseif($lay->tipo == 'video')
					{
						?>
						<div class="row">
							<div class="small-12 large-12 columns">
								<i class="fa fa-image fa-fw help" title="Poster: Esta imagem normalmente é o primeiro frame do vídeo. Ela é mostrada enquanto o vídeo não termina de carregar."></i>
								<input type="text" name="layer[<?= $lay->id ?>][settings][background-image]" data-layer-prop="background-image" data-layer-prop-type="css" id="layer-<?= $lay->id ?>-background-image" value="<?= htmlSpecialChars($lay->getSetting('background-image')) ?>" class="icon-label"/>
								<div class="text-right" style="padding-right: 7px;">
									<button data-url="dbo-media-manager.php?dbo_modal=1&destiny=background&wrapper_id=layer-<?= $lay->id ?>&input_id=layer-<?= $lay->id ?>-background-image&default_size=large" rel="modal" data-modal-width="100%" data-modal-height="100%" class="button radius small pointer trigger-media-manager" data-target="#<?= $slide_id ?>">Selecionar imagem...</button>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="small-12 large-12 columns">
								<i class="fa fa-play fa-fw help" title="Url do vídeo. Pode ser um link direto a um vídeo externo ao site."></i>
								<input type="text" name="layer[<?= $lay->id ?>][settings][video-url]" data-layer-prop="" data-layer-prop-type="" id="layer-<?= $lay->id ?>-video-url" value="<?= htmlSpecialChars($lay->getSetting('video-url')) ?>" class="icon-label"/>
								<div class="text-right" style="padding-right: 7px;">
									<button data-url="dbo-media-manager.php?dbo_modal=1&destiny=input&wrapper_id=layer-<?= $lay->id ?>&input_id=layer-<?= $lay->id ?>-video-url&default_size=large" rel="modal" data-modal-width="100%" data-modal-height="100%" class="button radius small pointer trigger-media-manager" data-target="#<?= $slide_id ?>">Selecionar vídeo...</button>
								</div>
							</div>
						</div>
						<?php
					}
				?>
				<!-- parte comum a todos os layers -->
				<div class="row">
					<div class="small-12 large-10 columns">
						<i class="fa fa-chain fa-fw help" title="Linkar para..."></i>
						<input type="text" name="layer[<?= $lay->id ?>][settings][url]" data-layer-prop="url" data-layer-prop-type="" id="" placeholder="http://..." value="<?= htmlSpecialChars($lay->getSetting('url')) ?>" class="icon-label"/>
					</div>
					<div class="small-12 large-2 columns">
						<ul class="button-group left">
							<?php $id = 'layer-'.$lay->id.'-external-link'; ?>
							<li><input type="checkbox" name="layer[<?= $lay->id ?>][settings][external-link]" id="<?= $id ?>" value="bold" <?= $lay->getSetting('external-link') == 'bold' ? 'checked' : '' ?> class="hidden" data-layer-prop-type="" data-layer-prop="external-link" data-layer-prop-toggle/><label for="<?= $id ?>" title="Abrir em nova aba" class="tiny button secondary"><i class="fa fa-external-link-square font-14"></i></label></li>
						</ul>				
					</div>
				</div>
				<div class="row">
					<div class="small-12 large-6 columns">
						<i class="fa fa-play-circle-o fa-fw help" title="Animação a ser aplicada nesta camada"></i>
						<select name="layer[<?= $lay->id ?>][settings][peixe-animation]" data-layer-prop="peixe-animation" data-layer-prop-type="data-attr" class="icon-label">
							<option value="">Nenhuma</option>

							<optgroup label="Bouncing Entrances">
								<option value="bounceIn" <?= $lay->getSetting('peixe-animation') == 'bounceIn' ? 'selected' : '' ?>>Bounce In</option>
								<option value="bounceInDown" <?= $lay->getSetting('peixe-animation') == 'bounceInDown' ? 'selected' : '' ?>>Bounce In Down</option>
								<option value="bounceInLeft" <?= $lay->getSetting('peixe-animation') == 'bounceInLeft' ? 'selected' : '' ?>>Bounce In Left</option>
								<option value="bounceInRight" <?= $lay->getSetting('peixe-animation') == 'bounceInRight' ? 'selected' : '' ?>>Bounce In Right</option>
								<option value="bounceInUp" <?= $lay->getSetting('peixe-animation') == 'bounceInUp' ? 'selected' : '' ?>>Bounce In Up</option>
							</optgroup>

							<optgroup label="Fading Entrances">
								<option value="fadeIn" <?= $lay->getSetting('peixe-animation') == 'fadeIn' ? 'selected' : '' ?>>Fade In</option>
								<option value="fadeInDown" <?= $lay->getSetting('peixe-animation') == 'fadeInDown' ? 'selected' : '' ?>>Fade In Down</option>
								<option value="fadeInDownBig" <?= $lay->getSetting('peixe-animation') == 'fadeInDownBig' ? 'selected' : '' ?>>Fade In DownBig</option>
								<option value="fadeInLeft" <?= $lay->getSetting('peixe-animation') == 'fadeInLeft' ? 'selected' : '' ?>>Fade In Left</option>
								<option value="fadeInLeftBig" <?= $lay->getSetting('peixe-animation') == 'fadeInLeftBig' ? 'selected' : '' ?>>Fade In Left Big</option>
								<option value="fadeInRight" <?= $lay->getSetting('peixe-animation') == 'fadeInRight' ? 'selected' : '' ?>>Fade In Right</option>
								<option value="fadeInRightBig" <?= $lay->getSetting('peixe-animation') == 'fadeInRightBig' ? 'selected' : '' ?>>Fade In Right Big</option>
								<option value="fadeInUp" <?= $lay->getSetting('peixe-animation') == 'fadeInUp' ? 'selected' : '' ?>>Fade In Up</option>
								<option value="fadeInUpBig" <?= $lay->getSetting('peixe-animation') == 'fadeInUpBig' ? 'selected' : '' ?>>Fade In Up Big</option>
							</optgroup>

							<optgroup label="Flippers">
								<option value="flipInX" <?= $lay->getSetting('peixe-animation') == 'flipInX' ? 'selected' : '' ?>>Flip In X</option>
								<option value="flipInY" <?= $lay->getSetting('peixe-animation') == 'flipInY' ? 'selected' : '' ?>>Flip In Y</option>
							</optgroup>

							<optgroup label="Lightspeed">
								<option value="lightSpeedIn" <?= $lay->getSetting('peixe-animation') == 'lightSpeedIn' ? 'selected' : '' ?>>Light Speed In</option>
							</optgroup>

							<optgroup label="Rotating Entrances">
								<option value="rotateIn" <?= $lay->getSetting('peixe-animation') == 'rotateIn' ? 'selected' : '' ?>>Rotate In</option>
								<option value="rotateInDownLeft" <?= $lay->getSetting('peixe-animation') == 'rotateInDownLeft' ? 'selected' : '' ?>>Rotate In Down Left</option>
								<option value="rotateInDownRight" <?= $lay->getSetting('peixe-animation') == 'rotateInDownRight' ? 'selected' : '' ?>>Rotate In Down Right</option>
								<option value="rotateInUpLeft" <?= $lay->getSetting('peixe-animation') == 'rotateInUpLeft' ? 'selected' : '' ?>>Rotate In Up Left</option>
								<option value="rotateInUpRight" <?= $lay->getSetting('peixe-animation') == 'rotateInUpRight' ? 'selected' : '' ?>>Rotate In Up Right</option>
							</optgroup>

							<optgroup label="Sliding Entrances">
								<option value="slideInUp" <?= $lay->getSetting('peixe-animation') == 'slideInUp' ? 'selected' : '' ?>>Slide In Up</option>
								<option value="slideInDown" <?= $lay->getSetting('peixe-animation') == 'slideInDown' ? 'selected' : '' ?>>Slide In Down</option>
								<option value="slideInLeft" <?= $lay->getSetting('peixe-animation') == 'slideInLeft' ? 'selected' : '' ?>>Slide In Left</option>
								<option value="slideInRight" <?= $lay->getSetting('peixe-animation') == 'slideInRight' ? 'selected' : '' ?>>Slide In Right</option>

							</optgroup>
							
							<optgroup label="Zoom Entrances">
								<option value="zoomIn" <?= $lay->getSetting('peixe-animation') == 'zoomIn' ? 'selected' : '' ?>>Zoom In</option>
								<option value="zoomInDown" <?= $lay->getSetting('peixe-animation') == 'zoomInDown' ? 'selected' : '' ?>>Zoom In Down</option>
								<option value="zoomInLeft" <?= $lay->getSetting('peixe-animation') == 'zoomInLeft' ? 'selected' : '' ?>>Zoom In Left</option>
								<option value="zoomInRight" <?= $lay->getSetting('peixe-animation') == 'zoomInRight' ? 'selected' : '' ?>>Zoom In Right</option>
								<option value="zoomInUp" <?= $lay->getSetting('peixe-animation') == 'zoomInUp' ? 'selected' : '' ?>>Zoom In Up</option>
							</optgroup>
							
							<optgroup label="Specials">
								<option value="rollIn" <?= $lay->getSetting('peixe-animation') == 'rollIn' ? 'selected' : '' ?>>Roll In</option>
							</optgroup>
						</select>
					</div>
					<div class="small-12 large-3 columns">
						<i class="fa fa-fw fa-hourglass-start help" title="Tempo de espera para o início da animação, em segundos (s)"></i>
						<?= dboValueStepper($lay->getSetting('peixe-animation-delay'), array(
							'name' => 'layer['.$lay->id.'][settings][peixe-animation-delay]',
							'min_value' => '0.0',
							'step' => '0.1',
							'data_attrs' => array(
								'layer-prop' => 'peixe-animation-delay',
								'layer-prop-type' => 'data-attr',
								'layer-prop-unit' => 's',
							),
							'input_classes' => 'icon-label',
						)) ?>
					</div>
					<div class="small-12 large-3 columns">
						<i class="fa fa-fw fa-clock-o help" title="Tempo de duração da animação, em segundos (s)"></i>
						<?= dboValueStepper($lay->getSetting('peixe-animation-duration'), array(
							'name' => 'layer['.$lay->id.'][settings][peixe-animation-duration]',
							'min_value' => '0.0',
							'step' => '0.1',
							'data_attrs' => array(
								'layer-prop' => 'peixe-animation-duration',
								'layer-prop-type' => 'data-attr',
								'layer-prop-unit' => 's',
							),
							'input_classes' => 'icon-label',
						)) ?>
					</div>
				</div>

				<p class="text-right no-margin font-12"><a href="<?= secureUrl('dbo/core/dbo-slider-ajax.php?action=remove-layer&layer_id='.$lay->id.'&'.CSRFVar()); ?>" class="peixe-json" data-confirm="Tem certeza que deseja remover esta camada?"><i class="fa fa-times"></i> Remover camada</a></p>
				<input type="hidden" name="layer[<?= $lay->id ?>][settings][top]" data-layer-prop="top" data-layer-prop-type="css" value="<?= $lay->getSetting('top') ?>"/>
				<input type="hidden" name="layer[<?= $lay->id ?>][settings][left]" data-layer-prop="left" data-layer-prop-type="css" value="<?= $lay->getSetting('left') ?>"/>
				<input type="hidden" name="layer[<?= $lay->id ?>][settings][width]" data-layer-prop="width" data-layer-prop-type="css" value="<?= $lay->getSetting('width') ?>"/>
				<input type="hidden" name="layer[<?= $lay->id ?>][settings][height]" data-layer-prop="height" data-layer-prop-type="css" value="<?= $lay->getSetting('height') ?>"/>
				<input type="hidden" name="layer[<?= $lay->id ?>][settings][z-index]" data-layer-prop="z-index" data-layer-prop-type="css" value="<?= $lay->order_by ?>"/>
			</div>
		</li>		
		<?php
		return ob_get_clean();
	}

	function formDboSliderUpdate($slider)
	{
		ob_start();

		//selecionando o slide ativo
		$active_slide = $_GET['active_slide'] ? $_GET['active_slide'] : $slider->getFirstSlide()->id;

		?>
		<link rel="stylesheet" href="css/style-dbo-slider.css">
		<style>
			#dbo-slider { font-size: 15px; }
		</style>
		<?= dboImportJs(array(
			'marked',
			'dbo-value-stepper',
			'flowtype',
		)) ?>
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
					<div class="settings-box" id="settings-box-slider" style="<?= !$slider->estaConfigurado() ? 'display: block;' : '' ?>">
						<div class="row">
							<div class="large-12 columns">
								<?php
									if(!$slider->estaConfigurado())
									{
										?>
										<script>
											$(document).ready(function(){
												$('#settings-box-slider').peixeAutoFocus();
											}) //doc.ready
										</script>
										<?php
									}
								?>
								<h3>Configurações</h3>
								<div class="font-14">
									<form action="<?= secureUrl('dbo/core/dbo-slider-ajax.php?action=update-slider&slider_id='.$slider->id) ?>" id="form-slider" class="peixe-json no-margin" peixe-log>
										<div class="row">
											<div class="large-7 columns">
												<div>
													<label for="" class="inline-block fixed-width">Largura do slider <i class="fa fa-question-circle font-14 help" title="A lagura do slider pode ser um número fixo de pixels ou uma porcentagem da largura da tela do usuário"></i></label> 
													<input type="number" name="slider_width" id="slider_width" value="<?= $slider->getSetting('width') ?>" class="slider-setting inline-block w-auto font-12 text-center" size="8"/>
													<input type="radio" name="slider_width_unit" id="slider_width_unit_px" value="px" <?= $slider->getSetting('slider_width_unit') == 'px' ? 'checked' : '' ?>/><label for="slider_width_unit_px" >px</label>
													<input type="radio" name="slider_width_unit" id="slider_width_unit_percent" value="%" <?= $slider->getSetting('slider_width_unit') == '%' ? 'checked' : '' ?>/><label for="slider_width_unit_percent" >%</label>
												</div>
												<div>
													<label for="" class="inline-block fixed-width">Altura do slider <i class="fa fa-question-circle font-14 help" title="A altura do slider deve obrigatoriamente ser uma porcentagem de sua largura"></i></label> 
													<input type="number" name="slider_height" id="slider_height" value="<?= $slider->getSetting('height') ?>" class="slider-setting inline-block w-auto font-12 text-center" size="8"/> % 
												</div>
												<div>
													<label for="" class="inline-block fixed-width">Tempo de transição <i class="fa fa-question-circle font-14 help" title="O tempo padrão que cada slide fica na tela antes de passar para o próximo"></i></label> 
													<input type="number" name="transition_time" id="transition_time" value="<?= $slider->getSetting('transition_time') ?>" class="slider-setting inline-block w-auto font-12 text-center" size="8"/> segundos
												</div>
												<div>
													<label for="" class="inline-block fixed-width">Tamanho da fonte <i class="fa fa-question-circle font-14 help" title="Se você estiver utilizando um slider com largura em %, deixe este campo em branco"></i></label> 
													<input type="number" name="font-size" id="font-size" value="<?= $slider->getSetting('font-size') ?>" class="slider-setting inline-block w-auto font-12 text-center" size="8"/> px
												</div>
												<div>
													<label for="" class="inline-block fixed-width">Tipo do slider</label>
													<span class="inline-block">
														<input type="radio" name="slider_tipo" id="slider-sangrado" value="bleed" <?= $slider->getSetting('tipo') == 'bleed' ? 'checked' : '' ?>/><label for="slider-sangrado" title="As imagens dos slides ocupam toda a largura da tela" class="help">Sangrado</label>
														<input type="radio" name="slider_tipo" id="slider-contido" value="contain" <?= $slider->getSetting('tipo') == 'contain' ? 'checked' : '' ?>/><label title="As imagens dos slides tem a largura máxima igual à largura do conteúdo do site (grid)" for="slider-contido" class="help">Contido</label>
													</span>
												</div>
											</div>
											<div class="large-5 columns" style="padding-top: 60px;">
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
					<div id="dbo-wrapper-slider" class="animated" style="max-width: <?= $slider->getSetting('width').$slider->getSetting('slider_width_unit') ?>; margin: 0 auto;">
						<div id="dbo-slider" style="padding-top: <?= $slider->getSetting('height'); ?>%;" class="animated" data-slider_width="<?= $slider->getSetting('width') ?>" data-slider_width_unit="<?= $slider->getSetting('slider_width_unit') ?>" data-slider_height="<?= $slider->getSetting('height') ?>" data-transition_time="<?= $slider->getSetting('transition_time') ?>" data-font-size="<?= $slider->getSetting('font-size') ?>" data-tipo="<?= $slider->getSetting('tipo') ?>"></div>
					</div>
				</div>
			</div>
			
			<?= sliderRenderSlidesTabBar($slider->id, array('active_slide' => $active_slide)) ?>
			
			<?= sliderRenderFormSlideContent($active_slide) ?>
			
			<script>
			
				slider = {
			
					input_timer: null,
					fix_height_timer: null,
					data: {},

					wrapper_canvas: $('#dbo-wrapper-slider'),
					canvas: $('#dbo-slider'),
			
					updateData: function() {
						slider.canvas.removeData('slider_width').attr('data-slider_width', $('#slider_width').val());
						slider.canvas.removeData('slider_width_unit').attr('data-slider_width_unit', $('[name="slider_width_unit"]:checked').val());
						slider.canvas.removeData('slider_height').attr('data-slider_height', $('#slider_height').val());
						slider.canvas.removeData('transition_time').attr('data-transition_time', $('#transition_time').val());
						slider.canvas.removeData('font-size').attr('data-font-size', $('#font-size').val());
						slider.canvas.removeData('tipo').attr('data-tipo', $('[name="font-size"]:checked').val());
						//console.log('slider data updated');
					},
			
					updatePreview: function(){
						//cerifica se é pixel ou percent
						slider.wrapper_canvas.css('max-width', slider.canvas.data('slider_width')+slider.canvas.data('slider_width_unit'));
						slider.canvas.css('padding-top', slider.canvas.data('slider_height')+'%');
						/*if(slider.data.height.indexOf('%') > -1){
							console.log('percent based');
							slider.canvas.css('height', '');
							slider.canvas.css('padding-top', slider.data.height);
							slide.canvas.attr('data-padding-top', slider.data.height);
						}
						else {
							slider.canvas.css('height', slider.data.height);
							slider.canvas.css('padding-top', 0);
						}*/
						//console.log('slider canvas updated');
						setTimeout(function(){
							$(window).trigger('resize');
						}, 400);
					},

					fixHeight: function(){
						//remove as animações
						slider.canvas.removeClass('animated');
						slider.wrapper_canvas.removeClass('animated');

						//remove a algura fixa do canvas e aplica o paddint-top
						slider.clearHeight();
						
						//salva a altura atual em uma veriável para reaplicação
						var height = slider.canvas.outerHeight();
						slider.canvas.css('height', height + 'px');
						slider.canvas.css('padding-top', 0);
						slider.canvas.addClass('animated');
						slider.wrapper_canvas.addClass('animated');
						//console.log('height fixed');
					},
					
					clearHeight: function(){
						slider.canvas.css('height', '');
						slider.canvas.css('padding-top', slider.canvas.data('slider_height')+'%');
					},

					hideBorders: function(){
						slider.canvas.addClass('hide-borders');
						$('#wrapper-camadas').addClass('hide-borders');
					},

					showBorders: function(){
						slider.canvas.removeClass('hide-borders');
						$('#wrapper-camadas').removeClass('hide-borders');
					}
				};
			
				slide = {
					input_timer: null,
					data: {},
					layers: [],

					canvas: $('#dbo-slider'),
					canvas_selector: '#dbo-slider',

					play: function(){
						slide.canvas.find('[data-peixe-animation]').peixeAnimate();
					},

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
						tipo_background = $('input[name="bg_type"]:checked').val();

						//resetando o background
						this.resetBackground(wrapper);

						//se for solido
						if(tipo_background == 'solid'){
							wrapper.css('background-color', $('#slide-bg-color').val());
						}
						//se for imagem
						else if(tipo_background == 'image'){
							wrapper.css('background-image', 'url("'+$('#slide-bg-image').val()+'")');
							//wrapper.css('background-size', 'auto 100%');
							wrapper.css('background-size', 'cover');
							wrapper.css('background-position', 'center center');
							wrapper.css('background-repeat', 'no-repeat');
						}
						//se não está setado ou é transparente

						//console.log('slide preview updated');
					},

					wrapperCamadasInit: function(){

						//remove a seleção de qq layer
						this.deselectLayers();

						//inicia o controle das camadas
						$('#wrapper-camadas').draggable({
							handle: '.ct-grip',
							stop: function(event, ui){
								var finalOffset = $(this).offset();
								var finalxPos = finalOffset.left;
								var finalyPos = finalOffset.top - $(window).scrollTop();
								//console.log(finalxPos);
								//console.log(finalyPos);
								dboSetPreference(null, 'position_x', finalxPos);
								dboSetPreference(null, 'position_y', finalyPos);
							}
						});
						$('#lista-camadas').sortable({
							handle: '.handle',
							axis: 'y',
							distance: 10,
							stop: function(event, ui){
								var sorted = $(this).sortable('serialize', {
									attribute: 'data-id'
								})
								peixeJSONSilent(DBO_URL + '/core/dbo-slider-ajax.php?action=sort-layers', {
									DBO_CSRF_token: '<?= CSRFGetToken() ?>',
									sorted: sorted
								}, null, true);
								slide.sortLayers();
							}
						});
					},

					resetBackground: function(wrapper){
						wrapper.css('background-color', '');
						wrapper.css('background-image', '');
					},

					createLayer: function(tipo){
						this.canvas.append(layer.new(tipo))
					},

					selectLayer: function(id){
						//ativa o layer e o layer-tab
						if(layer.active.data('id') != id){
							layer.active.removeClass('active');
							layer.active_tab.removeClass('active');

							layer.active = $('.dbo-slide-layer[data-id="'+id+'"]');
							layer.active_tab = $('.dbo-slide-layer-tab[data-id="'+id+'"]');

							layer.active.addClass('active');
							layer.active_tab.addClass('active');

							console.log(id + ' selecionado');
						}
					},

					deselectLayers: function(){

						layer.active.removeClass('active');
						layer.active_tab.removeClass('active');

						layer.active = $('.juca-bala-superstar');
						layer.active_tab = $('.juca-bala-superstar');
					
					},

					deleteLayer: function(id){
						$('.dbo-slide-layer[data-id="'+id+'"]').remove();
						$('.dbo-slide-layer-tab[data-id="'+id+'"]').remove();
					},

					clearLayers: function(){
						slide.canvas.find('.dbo-slide-layer').remove();
					},

					renderLayers: function(){
						$('#lista-camadas .dbo-slide-layer-tab').each(function(){
							//primeiro atualiza html de todos layers no canvas
							layer.render($(this).data('id'), $(this).find('[data-layer-prop]'));
						})
						//depois, ativa o draggable e resize
						slide.canvas.find('.dbo-slide-layer').draggable({
							/*containment: slide.canvas_selector,*/
							delay: 150,
							stop: function (){
								var c = $(this);
								var l = ( 100 * parseFloat(c.css("left")) / parseFloat(c.parent().css("width")) )+ "%" ;
								var t = ( 100 * parseFloat(c.css("top")) / parseFloat(c.parent().css("height")) )+ "%" ;
								c.css("left" , l);
								c.css("top" , t);
								layer.updateProp(c.data('id'), 'left', l);
								layer.updateProp(c.data('id'), 'top', t);
							}
						}).resizable({
							/*handles: 'all',*/
							stop: function (){
								var c = $(this);

								//atualiza o tamanho
								var w = ( 100 * parseFloat(c.css("width")) / parseFloat(c.parent().css("width")) )+ "%" ;
								var h = ( 100 * parseFloat(c.css("height")) / parseFloat(c.parent().css("height")) )+ "%" ;
								c.css("width" , w);
								c.css("height" , h);
								layer.updateProp(c.data('id'), 'width', w);
								layer.updateProp(c.data('id'), 'height', h);
							}
						}).on('resize', function(e){
							e.stopPropagation();
						});
					}, 

					salvarCamadas: function(){
						console.log('salvando camadas');
					},

					sortLayers: function(){
						var zi = 50;
						$('.dbo-slide-layer-tab').each(function(){
							var c = $(this);
							layer.updateProp(c.data('id'), 'z-index', zi--);
						})
						slide.renderLayers();
						console.log('layers sorted');
					}
				}; //slide

				layer = {
				
					lista: $('#wrapper-lista-camadas'),
					helper: $('#helper-camadas-placeholder'),

					active: $('.juca-bala-superstar'),
					active_tab: $('.juca-bala-superstar'),

					render: function(id, data){
						//console.log('layer: -------->' + id);
						//verifica se o slide já existe no canvas
						var lay = slide.canvas.find('.dbo-slide-layer[data-id="'+id+'"]');
						if(lay.length){
							lay.remove();
						}
						lay = '<div class="dbo-slide-layer" data-id="'+id+'" id="'+id+'"><div class="dbo-layer-content" id="'+id+'-content"></div></div>';
						slide.canvas.append(lay);

						//aplicando todas as propriedades
						data.each(function(){
							layer.applyProp($(this), id);
						})
					},

					playActive: function(){
						layer.active.peixeAnimate();
					},

					applyProp: function(c, layer_id){
						//aplicando o texto no elemento

						var target = layer_id ? slide.canvas.find('.dbo-slide-layer[data-id="'+layer_id+'"]') : layer.active;
						var id = target.attr('data-id').split('-').slice(1,2);

						//inicializando as variaveis
						var prop = c.data('layer-prop');
						var prop_type = c.data('layer-prop-type');
						var prop_value = c.val();
						var prop_unit = c.data('layer-prop-unit');
						var prop_toggle = c.data('layer-prop-toggle');
						var prop_options = c.data('layer-prop-options');
						var input_type = c.attr('type');
						var checked = c.is(':checked');

						//tratando as variaveis
						prop_value = String(prop_value) + (prop_unit !== undefined ? prop_unit : '');
						prop_options = prop_options !== undefined ? prop_options.split(',') : [];

						if(prop_type == 'text'){
							target.find('.dbo-layer-content').html(marked(prop_value));
						}
						else if(prop_type == 'css'){
							//se for do tipo toggle
							if(prop_toggle !== undefined){
								//se for radio
								if(input_type == 'radio' && checked){}
								//se for checkbox
								else if(input_type == 'checkbox'){
									if(checked){
										target.css(prop, prop_value);
									}
									else {
										target.css(prop, '');
									}
								}
							}
							else {
								//caso especial da cor de fundo
								if(prop == 'background-color' || prop == 'background-alpha'){
									var color = $('[name="layer['+id+'][settings][background-color]"]').val();
									var alpha = $('[name="layer['+id+'][settings][background-alpha]"]').val();
									if(color != ''){
										prop = 'background-color';
										prop_value = hex2rgba(color, alpha);
									}
								}
								//caso especial para imagens de fundo
								if(prop == 'background-image'){
									prop_value = 'url("'+prop_value+'")';
								}
								target.css(prop, prop_value);
							}
						}
						else if(prop_type == 'class'){
							if(prop_toggle !== undefined){
								
							}
							else {
								//se for radio
								if(input_type == 'radio' && checked){
									prop_options.forEach(function(value) {
										target.removeClass(value);
									});
									target.addClass(prop_value);
								}
								//se for checkbox
								else if(input_type == 'checkbox'){
									if(checked){
										target.addClass(prop_value);
									}
									else {
										target.removeClass(prop_value);
									}
								}
							}
						}
						else if(prop_type == 'data-attr'){
							//console.log('data-' + prop);
							//console.log(prop_value);
							target.removeData(prop).attr('data-' + prop, prop_value);
						}
					},

					updateProp: function(layer_id, prop, value){
						layer_id = layer_id.replace('layer-', '');
						$('input[name="layer['+layer_id+'][settings]['+prop+']"]').val(value);
					}

				};

				$(document).on('click', '#tabs-slides .dbo-tab', function(){
					c = $(this);
					$('#tabs-slides .dbo-tab.active').removeClass('active');
					c.addClass('active');
				});

				function sliderInit() {
					$('#tabs-slides .tabs').sortable({
						//axis: 'x',
						distance: 10,
						stop: function(event, ui){
							var sorted = $(this).sortable('serialize', {
								attribute: 'data-id'
							})
							peixeJSONSilent(DBO_URL + '/core/dbo-slider-ajax.php?action=sort-slides', {
								DBO_CSRF_token: '<?= CSRFGetToken() ?>',
								sorted: sorted
							}, null, true);
						}
					});
					slider.fixHeight();
					slide.updatePreview();
					slide.renderLayers();
					slide.wrapperCamadasInit();
					$('#dbo-slider').flowtype({ fontRatio: 100 });
				}

				function hex2rgba(hex,opacity){
					hex = hex.replace('#','');
					r = parseInt(hex.substring(0,2), 16);
					g = parseInt(hex.substring(2,4), 16);
					b = parseInt(hex.substring(4,6), 16);

					result = 'rgba('+r+','+g+','+b+','+opacity/100+')';
					return result;
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
					$(document).on('change', 'input[name="bg_type"]:checked', function(){
						c = $(this);
						$('.wrapper-background-options').hide();
						$('#wrapper-background-'+c.val()).fadeIn('fast');
					});

					//colocando o handler de ativar layers nos layers e tabs
					$(document).on('click', '.dbo-slide-layer, .dbo-slide-layer-tab', function(){
						var c = $(this);
						slide.selectLayer(c.data('id'));
					});

					//atualizando propriedados dos layers
					$(document).on('input', 'textarea[data-layer-prop], input[type="text"][data-layer-prop]', function(){
						var c = $(this);
						layer.applyProp(c);
					});

					$(document).on('change', 'input[type="checkbox"][data-layer-prop], input[type="radio"][data-layer-prop], select[data-layer-prop]', function(){
						var c = $(this);
						layer.applyProp(c);
					});

					/*$(window).scroll(function () {
						var position = $("#wrapper-camadas").offset();
						$("#wrapper-camadas").html(position.top);
					});*/

					$(document).on('click', '.trigger-deselect-layers', function(e){
						e.stopPropagation();
						slide.deselectLayers();
					});

					//ativando a animação do layer na mudança do select
					$(document).on('change', 'select[data-layer-prop="peixe-animation"]', function(){
						layer.playActive();
					});

				}) //doc.ready

				$(window).resize(function(){
					clearTimeout(slider.fix_height_timer);
					slider.fix_height_timer = setTimeout(function(){
						slider.fixHeight();
						//slide.renderLayers();
						//console.log('fix');
					}, 100);
				})

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
								<span class="dbo-tab <?= $active_slide == $slide->id ? 'active' : '' ?>" data-slide_id="<?= $slide->id ?>" data-id="slide-<?= $slide->id ?>" peixe-reload=".wrapper-slide-content" data-url="<?= keepUrl('active_slide='.$slide->id) ?>" peixe-done="setTimeout(function(){ slide.clearLayers(); slide.updatePreview(); slide.wrapperCamadasInit(); slide.renderLayers(); }, 700)"><br /><?= strlen(trim($slide->titulo)) ? htmlSpecialChars($slide->titulo) : 'Slide sem título' ?><br />&nbsp;</span>
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
												<input type="radio" name="bg_type" id="slide_background-transparent" value="transparent" class="" <?= $slide->getSetting('bg_type') == 'transparent' ? 'checked' : '' ?>/><label for="slide_background-transparent">Transparente</label>
												<input type="radio" name="bg_type" id="slide_background-solid" value="solid" class="" <?= $slide->getSetting('bg_type') == 'solid' ? 'checked' : '' ?>/><label for="slide_background-solid">Cor sólida</label>
												<input type="radio" name="bg_type" id="slide_background-image" value="image" class="" <?= $slide->getSetting('bg_type') == 'image' ? 'checked' : '' ?>/><label for="slide_background-image">Imagem</label>
											</div>
											<div class="wrapper-background-options" id="wrapper-background-solid" style="<?= $slide->getSetting('bg_type') == 'solid' ? '' : 'display: none;' ?>">
												<label for="" class="inline-block fixed-width">HEX</label>
												<input type="text" name="bg_color" id="slide-bg-color" value="<?= $slide->getSetting('bg_color'); ?>" class="inline-block w-auto font-12 slide-setting" size="10"/>
											</div>
											<div class="wrapper-background-options" id="wrapper-background-image" style="<?= $slide->getSetting('bg_type') == 'image' ? '' : 'display: none;' ?>">
												<label for="" class="inline-block fixed-width">Imagem</label>
												<input type="hidden" name="bg_image" id="slide-bg-image" value="<?= $slide->getSetting('bg_image') ?>"/>
												<button data-url="dbo-media-manager.php?dbo_modal=1&destiny=background&wrapper_id=dbo-slider-<?= $slide->_slider->getSetting('tipo') == 'bleed' ? 'bleed' : '' ?>&input_id=slide-bg-image&default_size=hd" rel="modal" data-modal-width="100%" data-modal-height="100%" class="button radius small pointer trigger-media-manager" data-target="#<?= $slide_id ?>">Selecionar imagem...</button>
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

				<?php
					$wcx = meta::getPreference('position_x');
					$wcy = meta::getPreference('position_y');
				?>
				<div id="wrapper-camadas" style="<?= !strlen(trim($slide->titulo)) ? 'display: none;' : '' ?> padding-top: 20px; <?= $wcx && $wcy ? ' top: '.$wcy.'px; left: '.$wcx.'px; ' : '' ?>" class="ct-widget ct-toolbox ct-widget--active">
					<div class="ct-toolbox__grip ct-grip">
						<div class="ct-grip__bump"></div>
						<div class="ct-grip__bump"></div>
						<div class="ct-grip__bump"></div>
					</div>
					<div class="ct-tool-groups">
						<div class="row" style="margin-bottom: 10px;">
							<div class="small-12 large-6 columns">
								<span>
									<label for="" data-dropdown="drop-nova-camada" data-options="is_hover:true" class="inline-block">Camadas <i class="fa fa-plus-circle medium pointer"></i></label>
								</span>
							</div>
							<div class="small-12 large-6 columns text-right font-18">
								<span class="show-hide-borders">
									<i class="fa fa-eye-slash color medium pointer trigger-hide-borders" title="Ocultar bordas de seleção" onClick="slider.hideBorders()"></i>
									<i class="fa fa-eye color medium pointer trigger-show-borders" title="Mostrar bordas de seleção" onClick="slider.showBorders()"></i>
								</span>
								<i class="fa fa-play-circle color medium pointer" onClick="slide.play();"></i>
							</div>
						</div>
					</div>
					<ul id="drop-nova-camada" class="f-dropdown content" style="padding: 10px;" data-dropdown-content>
						<li><a href="<?= secureUrl('dbo/core/dbo-slider-ajax.php?action=new-layer&type=text&slide_id='.$slide->id.'&'.CSRFVar()); ?>" class="no-margin peixe-json" peixe-log><i class="fa fa-font fa-fw"></i> Adicionar texto</a></li>
						<li><a href="<?= secureUrl('dbo/core/dbo-slider-ajax.php?action=new-layer&type=image&slide_id='.$slide->id.'&'.CSRFVar()); ?>" class="no-margin peixe-json" peixe-log><i class="fa fa-image fa-fw"></i> Adicionar imagem</a></li>
						<li><a href="<?= secureUrl('dbo/core/dbo-slider-ajax.php?action=new-layer&type=video&slide_id='.$slide->id.'&'.CSRFVar()); ?>" class="no-margin peixe-json"><i class="fa fa-youtube-play fa-fw"></i> Adicionar vídeo</a></li>
					</ul>
					<div id="wrapper-lista-camadas">
						<form method="post" action="<?= secureUrl('dbo/core/dbo-slider-ajax.php?action=save-layers&'.CSRFVar()); ?>" class="no-margin peixe-json" id="" peixe-log>
							<?php
								$lay = new dbo_slider_slide_layer("WHERE slide = '".$slide->id."' ORDER BY order_by DESC");
								if($lay->size())
								{
									?>
									<ul id="lista-camadas" class="no-bullet">
										<?php
											do {
												echo dboSliderRenderLayerTab($lay);
											}while($lay->fetch());
										?>
									</ul>
									<?php
								}
								else
								{
									?>
									<ul id="lista-camadas" class="no-bullet">
										<div class="text-center" id="helper-camadas-placeholder">
											<div class="helper arrow-top margin-bottom">
												<p class="no-margin font-12">Utilize os botões acima para adicionar conteúdo a seu slide.</p>
											</div>
										</div>
									</ul>
									<?php
								}
							?>
							<div class="row">
								<div class="small-12 large-12 columns text-center">
									<button type="submit" class="button small radius no-margin peixe-save" onClick="slide.salvarCamadas()"><span class="underline">S</span>alvar camadas</button>
								</div>
							</div>
						</form>
					</div>
				</div>
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
		return ob_get_clean();
	}

?>