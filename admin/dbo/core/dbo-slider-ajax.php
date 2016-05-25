<?
	require_once('../../lib/includes.php');
	require_once(DBO_PATH.'/core/dbo-ui.php');
	dboAuth('json');

	global $hooks;

	$json_result = array();
	
	CSRFCheckJson();

	if($_GET['action'] == 'update-slider')
	{
		secureURLCheck();
		
		$slider = new dbo_slider($_GET['slider_id']);
		if($slider->size())
		{
			$settings[width] = $_POST['slider_width'];
			$settings[height] = $_POST['slider_height'];
			$settings[autoplaySpeed] = $_POST['autoplaySpeed'];
			$settings[tipo] = $_POST['slider_tipo'];
			$slider->settings = json_encode($settings);
			$slider->update();
			$json_result['eval'] = singleLine('
				slider.updateData(); 
				slider.updatePreview(); 
				setTimeout(function(){ $("#toggle-slider-settings").trigger("click"); }, 500);
			');
			$json_result['message'] = '<div class="success">Configurações do slider salvas com sucesso.</div>';
		}
		else
		{
			$json_result['message'] = '<div class="error">O slider não existe</div>';
		}

	}
	elseif($_GET['action'] == 'adicionar-slide')
	{
		secureUrlCheck();

		$slider = new dbo_slider($_GET['slider_id']);
		if($slider->size())
		{
			$slide = new dbo_slider_slide();

			$sql = "SELECT MAX(order_by) AS max FROM ".$slide->getTable()." WHERE slider = '".$slider->id."'";
			$res = dboQuery($sql);
			$lin = dboFetchObject($res);
			
			$slide->slider = $slider->id;
			$slide->order_by = $lin->max + 1;
			$slide->save();

			$json_result['reload_url'] = $slide->keepUrl('active_slide='.$slide->id, array('url' => dboDecode($_GET['current_url'])));
			$json_result['reload'][] = '#tabs-slides';
			$json_result['reload'][] = '.wrapper-slide-content';
			$json_result['eval'] = singleLine('
				setTimeout(function(){ 
					$(window).scrollTo("#slide-titulo", 500, { interrupt: true, offset: -200, onAfter: function(){
							$("#slide-titulo").focus();
							sliderInit();
						} });
				}, 500)
			');
			
		}
		else
		{
			$json_result['message'] = '<div class="error">O slider não existe.</div>';
		}
	}
	elseif($_GET['action'] == 'update-slide-settings')
	{
		secureUrlCheck();

		$slide = new dbo_slider_slide($_GET['slide_id']);
		if($slide->size())
		{
			if(!strlen(trim($_POST['titulo'])))
			{
				$json_result['message'] = '<div class="error">Erro: Preencha um título para o slide.</div>';
			}
			elseif(!$_POST['status'])
			{
				$json_result['message'] = '<div class="error">Erro: Selecione um status para o slide.</div>';
			}
			elseif(!$_POST['background_type'])
			{
				$json_result['message'] = '<div class="error">Erro: Defina o tipo de background do seu slide.</div>';
			}
			else
			{
				$slide->titulo = $_POST['titulo'];
				$slide->status = $_POST['status'];
				$slide->setSetting('background_type', $_POST['background_type']);
				//verificando qual é a do background do slide.
				if($_POST['background_type'] == 'transparent')
				{
				}
				elseif($_POST['background_type'] == 'solid')
				{
				}
				elseif($_POST['background_type'] == 'image')
				{
				}

				$slide->update();

				$json_result['message'] = '<div class="success">Configurações do slide salvas com sucesso.</div>';
				$json_result['eval'] = singleLine('
					setTimeout(function(){ $("#toggle-slide-settings").trigger("click"); }, 500);
					setTimeout(function(){ $("#wrapper-camadas").fadeIn("fast"); }, 300 );
				');
			}
		}
		else
		{
			$json_result['message'] = '<div class="error">O slide não existe.</div>';
		}
	}
	elseif($_GET['action'] == 'excluir-slide')
	{
		
		secureUrlCheck();
		
		$slide = new dbo_slider_slide($_GET['slide_id']);
		if($slide->size())
		{
			$slide->delete();
			$json_result['message'] = '<div class="success">Slide removido com sucesso.</div>';
			$json_result['reload_url'] = keepUrl('!active_slide', array('url' => dboDecode($_GET['url'])));
			$json_result['reload'][] = '#tabs-slides';
			$json_result['reload'][] = '.wrapper-slide-content';
			$json_result['eval'] = singleLine('setTimeout(function(){ 
				sliderInit();
			}, 500);');
		}
		else
		{
			$json_result['message'] = '<div class="error">O slide não existe.</div>';
		}
		
	}

	echo json_encode($json_result);

?>