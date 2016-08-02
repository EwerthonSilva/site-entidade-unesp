<?php
	require_once('../../lib/includes.php');

	$json_result = array();

	if(logadoNoPerfil('Desenv'))
	{
		//migra todas as páginas do sistema para o padrão content-tools
		if($_GET['action'] == 'migrar-para-content-tools')
		{
			secureURLCheck();
			CSRFCheckJson();
			$pag = new pagina("ORDER BY id");
			if($pag->size())
			{
				$count = 0;
				do {
					$json = null;
					$json = json_decode($pag->texto, true);

					if($json === null && strlen(trim($pag->texto)))
					{
						$texto = json_encode(array(
							'content' => dboAutop(dboUnautop($pag->texto)),
						));
						$pag->texto = $texto;
						$pag->update();
						$count++;
					}
				}while($pag->fetch());
				$json_result['message'] = '<div class="success">O conteúdo de <strong>'.$count.' páginas</strong> foi atualizado.</div>';
			}
		}
	}
	else
	{
		$json_result['message'] = '<div class="error">Erro: Você não tem permissão de <strong>Desenvolvedor</strong>.</div>';
	}


	echo json_encode($json_result);

?>