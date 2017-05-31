<?

/* ================================================================================================================== */
/* DBO CLASS FILE FOR MODULE 'dbo_slider' ======================================= AUTO-CREATED ON 20/08/2015 12:06:01 */
/* ================================================================================================================== */

/* IMPORTANT: This file is generated only in the first DBO sync, what means you should edit only via text editor. */

if(!class_exists('dbo_slider'))
{
	class dbo_slider extends dbo
	{
		/* smart constructor: will perform load() upon numeric argument and loadAll() upon string argument */
		function __construct($foo = '')
		{
			parent::__construct('dbo_slider');
			if($foo != '')
			{
				if(is_numeric($foo))
				{
					$this->id = $foo;
					$this->load();
				}
				elseif(is_string($foo))
				{
					$this->loadAll($foo);
				}
			}
		}

		//your methods here
		function getSetting($setting)
		{
			$settings = json_decode($this->settings, true);
			return $settings[$setting];
		}

		function setSetting($setting, $value)
		{
			$settings = json_decode($this->settings, true);
			$settings[$setting] = $value;
			$this->settings = json_encode($settings);
		}

		function getFirstSlide()
		{
			$slide = new dbo_slider_slide("WHERE slider = '".$this->id."' ORDER BY order_by LIMIT 1");
			return $slide;
		}

		function delete()
		{
			$slide = new dbo_slider_slide("WHERE slider = '".$this->id."'");
			if($slide->size())
			{
				do {
					$slide->delete();
				}while($slide->fetch());
			}
			return parent::delete();
		}

		function getDefaultProp($prop)
		{
			global $default_props;
			return $default_props[$prop];
		}

		function estaConfigurado()
		{
			if(
				$this->getSetting('width') &&
				$this->getSetting('height')
			)
				return true;
			return false;
		}

		static function render($slider_id, $params = array())
		{
			extract($params);
			$slider = new dbo_slider($slider_id);
			if($slider->size())
			{
				ob_start();
				?>
				<?= dboImportJs(array(
					'flowtype',
				)) ?>
				<script>
					$(document).ready(function(){

						$('.dbo-slider-<?= $slider_id ?> .dbo-slide').flowtype({ fontRatio: 100 });

						$('.dbo-slider-<?= $slider_id ?> .dbo-slider-canvas').on('afterChange', function(event, slick, currentSlide){
							var current_slide = slick.$slides.removeClass('show').filter('.slick-current');
							current_slide.addClass('show').find('.dbo-slide-layer[data-peixe-animation]').peixeAnimate();
							dboSlidePlayVideos(current_slide);
						});
						
						$('.dbo-slider-<?= $slider_id ?> .dbo-slider-canvas').on('init', function(event, slick){
							setTimeout(function(){
								var current_slide = slick.$slides.filter('.slick-current');
								current_slide.addClass('show').find('.dbo-slide-layer[data-peixe-animation]').peixeAnimate();
								dboSlidePlayVideos(current_slide);
							}, 1000);
						});
						
						$('.dbo-slider-<?= $slider_id ?> .dbo-slider-canvas').slick({ 
							autoplay: true,
							pauseOnHover: false,
							dots: true,
							autoplaySpeed: <?= $slider->getSetting('transition_time')*1000 ?>
						});
					}) 

					function dboSlidePlayVideos(slide) {
						slide.find('video').each(function(){
							this.pause();
							this.currentTime = 0;
							this.play();
						})
					}

				</script>
				<style>
					.dbo-slider [data-peixe-animation] { visibility: hidden; }
					.dbo-slider .dbo-slide.show [data-peixe-animation] { visibility: visible; }
					.dbo-slider-canvas { margin-left: auto; margin-right: auto; }
				</style>
				<div class="dbo-slider dbo-slider-<?= $slider->id ?>">
					<div class="dbo-slider-canvas" style="max-width: <?= $slider->getSetting('width').$slider->getSetting('slider_width_unit') ?>;">
						<?php
							$slide = new dbo_slider_slide("WHERE slider = '".$slider->id."' AND status = 'publicado' ORDER BY order_by");
							if($slide->size())
							{
								do {
									//verificando o que colocar de background
									//se for imagem
									if($slide->getSetting('bg_type') == 'image')
									{
										$css_bg  = ' background-image: url(\''.filterMediaManagerUrl($slide->getSetting('bg_image')).'\'); ';
										$css_bg .= ' background-size: cover; ';
										$css_bg .= ' background-repeat: no-repeat; ';
										$css_bg .= ' background-position: center center; ';
									}
									elseif($slide->getSetting('bg_type') == 'solid')
									{
										$css_bg = ' background-color: '.$slide->getSetting('bg_color').'; ';
									}
									?>
									<div class="dbo-slide slide-<?= $slide->getIterator()-1 ?> dbo-slide-<?= $slide->id ?>" style="<?= $css_bg ?>">
										<div class="dbo-slide-content" style="padding-top: <?= $slider->getSetting('height') ?>%; position: relative;">
											<?php
												$lay = new dbo_slider_slide_layer("WHERE slide = '".$slide->id."'");
												if($lay->size())
												{
													do {

														$settings = $lay->getSettings();

														$css  = '';
														$classes = array();
														$data_attrs = array();
														$on_click = '';

														$classes = array_merge($classes, (array)$settings['classes']);


														//posicionamento e tamanho
														$css .= strlen(trim($settings['top'])) ? ' top: '.$settings['top'].'; ' : '';
														$css .= strlen(trim($settings['left'])) ? ' left: '.$settings['left'].'; ' : '';
														$css .= strlen(trim($settings['width'])) ? ' width: '.$settings['width'].'; ' : '';
														$css .= strlen(trim($settings['height'])) ? ' height: '.$settings['height'].'; ' : '';
														$css .= strlen(trim($settings['z-index'])) ? ' z-index: '.$settings['z-index'].'; ' : '';

														//configurações de texto
														$css .= strlen(trim($settings['font-weight'])) ? ' font-weight: '.$settings['font-weight'].'; ' : '';
														$css .= strlen(trim($settings['font-style'])) ? ' font-style: '.$settings['font-style'].'; ' : '';
														$css .= strlen(trim($settings['font-size'])) ? ' font-size: '.$settings['font-size'].'em; ' : '';
														$css .= strlen(trim($settings['font-family'])) ? ' font-family: '.$settings['font-family'].'; ' : '';
														$css .= strlen(trim($settings['padding'])) ? ' padding: '.$settings['padding'].'em; ' : '';
														$css .= strlen(trim($settings['letter-spacing'])) ? ' letter-spacing: '.$settings['letter-spacing'].'em; ' : '';
														$css .= strlen(trim($settings['line-height'])) ? ' line-height: '.$settings['line-height'].'; ' : '';

														//verificando se deve abrir link
														if(strlen(trim($settings['url'])))
														{
															$classes = array_merge($classes, array('pointer'));
															$on_click = ' onClick="'.(strlen(trim($settings['external-link'])) ? 'window.open(\''.$settings['url'].'\', \'_blank\');' : 'document.location = \''.$settings['url'].'\'').'" ';
														}

														//cores
														$css .= strlen(trim($settings['color'])) ? ' color: '.$settings['color'].'; ' : '';
														if(strlen(trim($settings['background-color'])))
														{
															extract(hex2rgb($settings['background-color']));
															$css .= ' background-color: rgba('.$r.', '.$g.', '.$b.', '.(min(100, $settings['background-alpha'])/100).'); ';
														}

														//animacao
														if(strlen(trim($settings['peixe-animation']))) { $data_attrs['peixe-animation'] = $settings['peixe-animation']; }
														if(strlen(trim($settings['peixe-animation-delay']))) { $data_attrs['peixe-animation-delay'] = $settings['peixe-animation-delay'].'s'; }
														if(strlen(trim($settings['peixe-animation-duration']))) { $data_attrs['peixe-animation-duration'] = $settings['peixe-animation-duration'].'s'; }
														
														//do tipo imagem
														$css .= strlen(trim($settings['background-image'])) ? ' background-image: url(\''.filterMediaManagerUrl($settings['background-image']).'\'); ' : '';

														?>
														<div class="dbo-slide-layer dbo-slide-layer-<?= $lay->id ?> <?= implode(' ', (array)$classes) ?>" style="position: absolute; <?= $css ?>" <?= $on_click ?> <?= dboParseDataAttributes($data_attrs) ?>>
															<div class="dbo-layer-content">
																<?= $lay->tipo == 'text' ? dboMarkdown($settings['text']) : '' ?>
																<?php
																	if($lay->tipo == 'video')
																	{
																		?>
																		<video autoplay loop style="opacity: 1; width: 100%; height: 100%; position: absolute; top: 0; left: 0;">
																			<source src="<?= filterMediaManagerUrl($settings['video-url']) ?>" type="video/mp4">
																		</video>
																		<?php
																	}
																?>
															</div>
														</div>
														<?php
													}while($lay->fetch());
												}
											?>
										</div>
									</div>
									<?php
								}while($slide->fetch());
							}
						?>
					</div>
				</div>
				<?php
				return ob_get_clean();
			}
		}

	} //class declaration
} //if ! class exists

function form_dbo_slider_append($operation, $obj)
{
	ob_start();
	?>
	<div class="row">
		<div class="large-12 columns">
			<div class="helper arrow-top">
				<p class="no-margin">Digite um nome para o seu slider.<br />No próximo passo você irá configurá-lo e cadastrar os slides individualmente.</p> 
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

function form_dbo_slider_update($obj)
{
	require_once(DBO_PATH.'/core/dbo-slider-admin.php');
	return formDboSliderUpdate($obj);
}

function scDboSlider($atts, $content = null) {
	extract(dboShortcodeAtts(array(
		'id' => null, //default atts values
	), $atts));
	echo dbo_slider::render($slider_id);
}
dboAddShortcode('dbo-slider','scDboSlider');

?>