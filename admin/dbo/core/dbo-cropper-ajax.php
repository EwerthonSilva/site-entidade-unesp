<?php
require_once('../../lib/includes.php');
require_once('dbo-ui.php');

$json_result = array();

secureURLCheck();

if($_GET['action'] == 'do-crop')
{
	$mod = new $_GET['modulo']();
	$data = $_POST['imgBase64'];
	$nome = $_GET['src'];


	$file_path = $_GET['coluna'] != '' ? DBO_PATH."/upload/files/" : DBO_PATH."/upload/dbo-media-manager/";

	copy($file_path.$nome, $file_path.$nome.'backup');
	list($type, $data) = explode(';', $data);
	list(, $data)      = explode(',', $data);

	$data_decode = base64_decode($data);

	if(file_put_contents($file_path.$nome, $data_decode) !== false){
		if($mod->__module_scheme->campo[$_GET['coluna']]->tipo == 'image'){
			if(dboUi::fieldSQL('image', $nome, $mod, array('image' => $mod->__module_scheme->campo[$_GET['coluna']]->image)))
			{
				$json_result['parent']['eval'] = 'jQuery.colorbox.close(); $("#wrapper-imagem-'.$_GET['coluna'].' img").attr("src", "'.DBO_URL.'/upload/images/'.$nome.'?='.uniqid().'")';
				$json_result['parent']['message'] = '<div class="success">Imagem editada com sucesso!</div>';
			}
		}else{
			resampleThumbs($nome, $file_path, array('aplicar_crop' => $_POST['aplicar_crop']));
			if($_POST['aplicar_crop'] == 'miniatura'){
				rename($file_path.$nome.'backup', $file_path.$nome);
			}
			//$json_result['parent']['html']['.media-item.active'] = 'teste';
			//$json_result['parent']['reload'][] = '.media-item.active';
			//$json_result['parent']['reload'][] = '#main-pic';
			$json_result['parent']['eval'] = singleLine('jQuery.colorbox.close(); /*stopCrop();*/ setTimeout(function(){ reloadAfterCrop(); }, 100)');
			$json_result['parent']['message'] = '<div class="success">Imagem editada com sucesso!</div>';
		}
	}else {
		$json_result['message'] = '<div class="error">Erro ao editar a imagem</div>';
	}
}
@unlink($file_path.$nome.'backup');
echo json_encode($json_result);

?>
