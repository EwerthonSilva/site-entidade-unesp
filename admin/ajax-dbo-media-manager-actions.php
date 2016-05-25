<?
	require_once('lib/includes.php');

	$_system['media_manager']['image_sizes'] = array_merge($_system['media_manager']['default_image_sizes'], (array)$_system['media_manager']['image_sizes']);

	//função que cria os thumbs da imagem em questão
	function resampleThumbs($file_name, $file_path, $params = array())
	{
		require_once(DBO_PATH."/core/classes/simpleimage.php");
		global $_system;
		extract($params);

		$image_info = getimagesize($file_path.$file_name);

		foreach($_system['media_manager']['image_sizes'] as $slug => $data)
		{
			//pula a miniatura no caso específico
			if($aplicar_crop == 'todos_menos_miniatura' && $slug == 'small') continue;

			//faz somente a minutura no caso específico
			if($aplicar_crop == 'miniatura' && $slug != 'small') continue;

			$image = new SimpleImage();
			$image->load($file_path.$file_name);
			if($image_info[0] >= $image_info[1]) {
				$image->resizeToWidth($data['max_width']);
			} else {
				$image->resizeToHeight($data['max_height']);
			}
			$caminho_arquivo = $file_path.'thumbs/'.$slug."-".preg_replace('/-_-dbomediamanagertempkey-_-[0-9]+$/is', '', $file_name);
			$image->save($caminho_arquivo, $data['quality']); //salvando o arquivo no server
		}
	}

	if(!secureUrl())
	{
		$json_result['error'] = 'Erro: tentativa de acesso insegura';
		echo json_encode($json_result);
		exit();
	}

	CSRFCheckJson();

	if(isset($_GET['file']) && strstr($_GET['file'], '..'))
	{
		$json_result['message'] = '<div class="error">Erro: tentativa de acesso de arquivo insegura.</div>';
		echo json_encode($json_result);
		exit();
	}

	$json_result = array();

	$file_path = DBO_PATH."/upload/dbo-media-manager/";

	if($_GET['action'] == 'upload-file')
	{

		$uploaded_file_data = $_FILES['peixe_ajax_file_upload_file'];

		//checa se ouve erro no upload, antes de mais nada...
		if($uploaded_file_data[error] > 0)
		{
			$uploaded_file_data[error] = 'Erro ao enviar o arquivo. Cod '.$uploaded_file_data[error];
			return $uploaded_file_data;
		}

		//pegando a extensão do arquivo
		$new_file_name = dboFileName($uploaded_file_data[name], array('file_path' => $file_path));

		/*$file_data = exif_read_data($uploaded_file_data[tmp_name]);
		
		//tenta extrair as informações do exif do arquivo.
		//============= DEBUG ================
		echo "<PRE>";
		var_dump($file_data);
		exit();
		echo "</PRE>";
		//============= DEBUG ================*/

		//salvando o arquivo com novo nome e retornando as informações
		if(move_uploaded_file($uploaded_file_data[tmp_name], $file_path.$new_file_name))
		{
			$uploaded_file_data[old_name] = $uploaded_file_data[name];
			$uploaded_file_data[name] = $new_file_name;
			$json_result = $uploaded_file_data;

			//aqui temos que fazer os resamples das imagens, baseado nos tamanhos definidos no sistema.
			resampleThumbs($new_file_name, $file_path);

			//criando uma página do tipo midia, caso exista a classe de páginas.
			if(class_exists('pagina'))
			{
				require_once(DBO_PATH.'/core/dbo-pagina-admin.php');
				paginaCreateMediaPage($new_file_name, array(
					'modulo' => $_POST['modulo'],
					'modulo_id' => $_POST['modulo_id'],
					'update_slug' => true,
				));
			}
		}
		else
		{
			//erro 5: erro ao mudar o arquivo de lugar...
			$json_result = array('error' => 'Erro ao enviar o arquivo. O tamanho não pode exceder '.min(ini_get('post_max_size'), ini_get('upload_max_filesize')));
		}
	}
	//deletando uma imagem
	elseif($_GET['action'] == 'delete-media')
	{
		$pag = new pagina($_GET['pagina_id']);
		if($pag->size())
		{
			if(unlink($file_path.$pag->imagem_destaque))
			{
				//deletando thumbs
				foreach($_system['media_manager']['image_sizes'] as $slug => $data)
				{
					@unlink($file_path.'thumbs/'.$slug.'-'.$pag->imagem_destaque);
				}

				$json_result['message'] = '<div class="success">Mídia removida com sucesso.</div>';
				$json_result['reload'][] = '#block-media-list';
				$json_result['reload'][] = '#block-details';
				$json_result['callback'][] = 'mediaManagerInit';
				$json_result['eval'] = 'setTimeout(function(){ showFormUpload(); }, 500)';
			}
			$pag->forceDelete();
		}
		else
		{
			$json_result['message'] = '<div class="error">Erro: a mídia requisitada não existe.</div>';
		}
		//impedindo espertinhos de apagar o que não devem
	}
	//fazendo o crop da imagem
	elseif($_GET['action'] == 'do-crop')
	{
		
		//gera uma chave temporária caso o crop vá ser aplicado somente na miniatura.
		$temp_key = $_POST['aplicar_crop'] == 'miniatura' ? '-_-dbomediamanagertempkey-_-'.time().rand(1,1000) : '';

		//setando o src
		$src = $file_path.$_GET['file'];

		//arredondando valores
		$x = round($_POST['c-x']);
		$y = round($_POST['c-y']);
		$w = round($_POST['c-w']);
		$h = round($_POST['c-h']);

		//descobrindo o tipo de arquivo
		define(IMAGETYPE_GIF, 1);
		define(IMAGETYPE_JPEG, 2);
		define(IMAGETYPE_JPEG, 3);

		$image_info = getimagesize($src);
		$image_type = $image_info[2];

		if( $image_type == IMAGETYPE_JPEG ) {
			$img_r = imagecreatefromjpeg($src);
		} elseif( $image_type == IMAGETYPE_GIF ) {
			$img_r = imagecreatefromgif($src);
		} elseif( $image_type == IMAGETYPE_PNG ) {
			$img_r = imagecreatefrompng($src);
		}

		$targ_w = $w;
		$targ_h = $h;
		$jpeg_quality = 90;

		$dst_r = ImageCreateTrueColor($targ_w,$targ_h);

		//transparencia do PNG
		if($image_type == IMAGETYPE_PNG)
		{
			imagealphablending($dst_r, false);
			imagesavealpha($dst_r,true);
			$transparent = imagecolorallocatealpha($dst_r, 255, 255, 255, 127);
			imagefilledrectangle($dst_r, 0, 0, $targ_w, $targ_h, $transparent);
		}

		imagecopyresampled($dst_r,$img_r,0,0,$x,$y,$targ_w,$targ_h,$w,$h);

		if( $image_type == IMAGETYPE_JPEG ) {
			imagejpeg($dst_r, $file_path.$_GET['file'].$temp_key, $jpeg_quality);
		} elseif( $image_type == IMAGETYPE_GIF ) {
			imagegif($dst_r, $file_path.$_GET['file'].$temp_key);
		} elseif( $image_type == IMAGETYPE_PNG ) {
			imagepng($dst_r, $file_path.$_GET['file'].$temp_key);
		}

		//criando thumbs apos o crop
		resampleThumbs($_GET['file'].$temp_key, $file_path, array('aplicar_crop' => $_POST['aplicar_crop']));

		//remove a imagem temporária, caso exista.
		if(strlen(trim($temp_key))) @unlink($file_path.$_GET['file'].$temp_key);

		$json_result['eval'] = 'stopCrop(); setTimeout(function(){ reloadAfterCrop(); }, 100)';
		
	}
	elseif($_GET['action'] == 'update-media-image')
	{
		//carrega o arquivo
		$pag = new pagina($_GET['media_id']);
		if($pag->size())
		{
			$pag->titulo = $_POST['titulo'];
			$pag->texto = $_POST['texto'];
			$pag->setDetail('legenda', $_POST['legenda']);
			//comentado porque preciso pensar em uma forma de implementar isso
			//considerando o esquema de inserção com link para a página de anexo.
			/*$pag->slug = dboUniqueSlug($_POST['titulo'], 'database', array(
				'table' => $pag->getTable(),
				'column' => 'slug',
				'exclude_id' => $pag->id,
			));*/
			$pag->update();
		}
	}

	echo json_encode($json_result);

?>