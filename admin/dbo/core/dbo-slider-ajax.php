<?
	require_once('../../lib/includes.php');
	require_once(DBO_PATH.'/core/dbo-ui.php');
	require_once(DBO_PATH.'/core/dbo-slider-admin.php');
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
			//varias checagens para poder atualizar o site...
			//checando se o usuário selecionou uma unidade de medida para o slider
			if(!$_POST['slider_width_unit'])
			{
				$json_result['message'] = '<div class="error">Erro: selecione uma unidade de medida para a largura do slider <strong>px</strong> ou <strong>%</strong>.</div>';
			}
			//checando se os valores de largura e altura são validos
			elseif(!is_numeric(trim($_POST['slider_width'])))
			{
				$json_result['message'] = '<div class="error">Erro: preencha um número valido para a <strong>largura</strong> do slider.</div>';
			}
			elseif(!is_numeric(trim($_POST['slider_height'])))
			{
				$json_result['message'] = '<div class="error">Erro: preencha um número valido para a <strong>altura</strong> do slider.</div>';
			}
			//se o slider for porcentagem a largura não pode ser maior que 100%
			elseif($_POST['slider_width_unit'] == '%' && $_POST['slider_width'] > 100)
			{
				$json_result['message'] = '<div class="error">Erro: A largura do slider não pode ser <strong>superior a 100%</strong>.</div>';
			}
			//verifica se o valor de transição foi setado. Se foi, verifica se é um numero válido
			elseif(strlen(trim($_POST['transition_time'])) && !is_numeric(trim($_POST['transition_time'])))
			{
				$json_result['message'] = '<div class="error">Erro: O tempo de transição precisa ser um <strong>número válido</strong>, ou precisar ser <strong>deixado em branco</strong>.</div>';
			}
			//verifica se o slider é de largura fixa e se o usuário escolheu um tamanho padrão para a fonte
			elseif($_POST['slider_width_unit'] == 'px' && intval($_POST['font-size']) < 6)
			{
				$json_result['message'] = '<div class="error">Erro: Para sliders de tamanho fixo você precisa de um tamanho de fonte padrão <strong>maior que 5</strong>.</div>';
			}
			//verifica se a pessoa escolheu um tipo de slider
			elseif(!$_POST['slider_tipo'])
			{
				$json_result['message'] = '<div class="error">Erro: selecione um tipo de slider: <strong>Sangrado</strong> ou <strong>Contido</strong>.</div>';
			}
			//tudo certo
			else
			{
				$settings['width'] = $_POST['slider_width'];
				$settings['slider_width_unit'] = $_POST['slider_width_unit'];
				$settings['height'] = $_POST['slider_height'];
				$settings['transition_time'] = $_POST['transition_time'];
				$settings['font-size'] = $_POST['font-size'];
				$settings['tipo'] = $_POST['slider_tipo'];
				$slider->settings = json_encode($settings);
				$slider->update();
				$json_result['eval'] = singleLine('
					slider.updateData(); 
					slider.updatePreview(); 
					setTimeout(function(){ $("#toggle-slider-settings").trigger("click"); }, 500);
				');
				$json_result['message'] = '<div class="success">Configurações do slider salvas com sucesso.</div>';
			}
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
							slide.clearLayers();
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
			elseif(!$_POST['bg_type'])
			{
				$json_result['message'] = '<div class="error">Erro: Defina o tipo de background do seu slide.</div>';
			}
			else
			{
				$slide->titulo = $_POST['titulo'];
				$slide->status = $_POST['status'];
				$slide->setSetting('bg_type', $_POST['bg_type']);

				//verificando qual é a do background do slide.
				if($_POST['bg_type'] == 'transparent')
				{
					$slide->setSetting('bg_color', '');
					$slide->setSetting('bg_image', '');
				}
				elseif($_POST['bg_type'] == 'solid')
				{
					$slide->setSetting('bg_color', $_POST['bg_color']);
					$slide->setSetting('bg_image', '');
				}
				elseif($_POST['bg_type'] == 'image')
				{
					$slide->setSetting('bg_color', '');
					$slide->setSetting('bg_image', $_POST['bg_image']);
				}

				//atualiza o slide
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
				slide.clearLayers();
				sliderInit();
			}, 500);');
		}
		else
		{
			$json_result['message'] = '<div class="error">O slide não existe.</div>';
		}
	}
	elseif($_GET['action'] == 'new-layer')
	{
		secureUrlCheck();

		$slide = new dbo_slider_slide($_GET['slide_id']);
		if($slide->size())
		{
			$layers = new dbo_slider_slide_layer("WHERE slide = '".$slide->id."' ORDER BY order_by DESC");

			$lay = new dbo_slider_slide_layer();
			$lay->slide = $slide->id;
			$lay->tipo = $_GET['type'];
			$lay->titulo = 'Camada '.($layers->size() + 1);
			$lay->setSetting('top', $slide->_slider->getDefaultProp('top'));
			$lay->setSetting('left', $slide->_slider->getDefaultProp('left'));
			$lay->setSetting('width', $slide->_slider->getDefaultProp('width'));
			$lay->setSetting('height', $slide->_slider->getDefaultProp('height'));
			if($lay->tipo == 'text')
			{
				$lay->setSetting('text', $slide->_slider->getDefaultProp('text'));
				$lay->setSetting('font-size', $slide->_slider->getDefaultProp('font-size'));
				$lay->setSetting('padding', $slide->_slider->getDefaultProp('padding'));
				$lay->setSetting('line-height', $slide->_slider->getDefaultProp('line-height'));
				$lay->setSetting('letter-spacing', $slide->_slider->getDefaultProp('letter-spacing'));
			}
			elseif($lay->tipo == 'image')
			{
				$lay->setSetting('width', '15%');
				$lay->setSetting('height', '26%');
			}
			elseif($lay->tipo == 'video')
			{
				$lay->setSetting('width', '22%');
				$lay->setSetting('height', '26%');
			}
			$lay->setSetting('peixe-animation-delay', $slide->_slider->getDefaultProp('peixe-animation-delay'));
			$lay->setSetting('peixe-animation-duration', $slide->_slider->getDefaultProp('peixe-animation-duration'));
			$lay->order_by = $layers->order_by + 1;
			$lay->save();

			//$json_result['reload'][] = '#wrapper-lista-camadas';
			$json_result['prepend']['#lista-camadas'][] = dboSliderRenderLayerTab($lay);
			$json_result['eval'] = singleLine('setTimeout(function(){ slide.renderLayers(); slide.wrapperCamadasInit(); slide.selectLayer(\'layer-'.$lay->id.'\'); }, 500); $("#helper-camadas-placeholder").remove(); ');
		}
		else
		{
			$json_result['message'] = '<div class="error">O slide não existe.</div>';
		}
	}
	elseif($_GET['action'] == 'remove-layer')
	{
		secureUrlCheck();

		$lay = new dbo_slider_slide_layer($_GET['layer_id']);
		if($lay->size())
		{
			$lay->delete();
			$json_result['message'] = '<div class="success">Camada removida com sucesso.</div>';
			$json_result['reload'][] = '#wrapper-lista-camadas';
			$json_result['reload_eval'] = singleLine('setTimeout(function(){ slide.renderLayers(); slide.deleteLayer(\'layer-'.$lay->id.'\'); slide.wrapperCamadasInit(); }, 500);');
		}
		else
		{
			$json_result['message'] = '<div class="error">O layer não existe</div>';
		}
	}
	elseif($_GET['action'] == 'save-layers')
	{
		secureUrlCheck();

		foreach((array)$_POST['layer'] as $layer_id => $data)
		{
			$lay = new dbo_slider_slide_layer($layer_id);
			$lay->titulo = $data['titulo'];
			//primeiro verifica se tem alguma propriedade que existia antes, e agora não existe mais. Se for o caso, remove
			foreach(array_keys($lay->getSettings()) as $key)
			{
				if(!in_array($key, $data['settings']))
				{
					$lay->removeSetting($key);
				}
			}
			//agora carrega as informações que vieram do post
			foreach($data['settings'] as $prop => $value)
			{
				$lay->setSetting($prop, $value);
			}
			$lay->update();
		}

		$json_result['message'] = '<div class="success">Camadas salvas com <strong>sucesso</strong>!</div>';
	}
	elseif($_GET['action'] == 'sort-layers')
	{
		parse_str($_POST['sorted'], $sorted);
		
		$init_z_index = 50;

		foreach($sorted['layer'] as $key => $layer_id)
		{
			$lay = new dbo_slider_slide_layer($layer_id);
			if($lay->size())
			{
				$lay->order_by = $init_z_index - $key;
				$lay->update();
			}
		}
	}
	elseif($_GET['action'] == 'sort-slides')
	{
		parse_str($_POST['sorted'], $sorted);
		
		foreach($sorted['slide'] as $key => $slide_id)
		{
			$slide = new dbo_slider_slide($slide_id);
			if($slide->size())
			{
				$slide->order_by = $key;
				$slide->update();
			}
		}
	}

	echo json_encode($json_result);

?>