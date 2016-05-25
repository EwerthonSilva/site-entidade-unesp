<?
	require_once("lib/includes.php");

	$json_result = array();

	$insc = new inscricao();
  $insc->palestra = $_GET['palestra'];
  $insc->email = $_GET['email'];

  $insc->loadAll();

  if($insc->size())
  {
    if($insc->delete())
    {
			$json_result['eval'] = "alert('Inscrição excluida com sucesso! Recarregue a tela para atualizar.')";
	    $json_result['reload'][] = "#inscricao-".$insc->id;
    }
  }

	echo json_encode($json_result);
?>
