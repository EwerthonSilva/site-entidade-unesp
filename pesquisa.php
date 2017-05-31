<?
require_once('admin/lib/includes.php');


require_once('header.php');

$pesq = new pesquisa();
$pesq->slug = $_GET['pesq'];
$pesq->loadAll();

if(!$pesq->size()){
  echo('<script>alert("A Pesquisa não existe");</script>');
  exit();
}

if($pesq->ifBeBetweenDates()){

  $perg = new pergunta();
  $perg->pesquisa = $pesq->id;
  $perg->loadAll();
  if(!$perg->size()){
    echo('<script>alert("Não existem perguntas cadastradas");</script>');
    exit();
  }
  ?>

  <div class="row" style="max-width: 550px;">
    <div class="large-12 columns">
      <div id="conteudo">
        <?= $pesq->temAutenticacao() > 0 ? $pesq->renderAutenticacao() : $pesq->renderQuestoes(); ?>
      </div>
    </div>
  </div>
  <?php
}else {
  ?>
  <div class="row">
    <div class="large-12 columns">
      <p>
        Lamentamos, mas essa pesquisa já foi encerrada.
      </p>
    </div>
  </div>
  <?php
}
?>
<script type="text/javascript">
$(document).ready(function(){
  $(document).on('change', 'select, input[type="radio"]', function(){
    c = $(this);
    if(c.val() == 'outro'){
      inp = "inputoutro"+c.context.name.replace(/[^a-zA-Z0-9]/g, "");
      $('#'+inp).removeClass('hide', 'fast', function(){
        $(this).find('input').prop('required', true).focus();
      });
    }
    else {
      inp = "inputoutro"+c.context.name.replace(/[^a-zA-Z0-9]/g, "");
      $('#'+inp).addClass('hide', 'fast', function(){
        $(this).find('input').prop('required', false).val('');
      })
    }
  });

});
function MascaraCPF(cpf){
  if(mascaraInteiro(cpf)==false){
    event.returnValue = false;
  }
  return formataCampo(cpf, '000.000.000-00', event);
}
function mascaraInteiro(){
  if (event.keyCode < 48 || event.keyCode > 57){
    event.returnValue = false;
    return false;
  }
  return true;
}

function formataCampo(campo, Mascara, evento) {
  var boleanoMascara;

  var Digitato = evento.keyCode;
  exp = /\-|\.|\/|\(|\)|/g
  campoSoNumeros = campo.value.toString().replace(exp, "" );

  var posicaoCampo = 0;
  var NovoValorCampo="";
  var TamanhoMascara = campoSoNumeros.length;;

  if (Digitato != 8) { // backspace
    for(i=0; i<= TamanhoMascara; i++) {
      boleanoMascara  = ((Mascara.charAt(i) == "-") || (Mascara.charAt(i) == ".")|| (Mascara.charAt(i) == "/"))
      boleanoMascara  = boleanoMascara || ((Mascara.charAt(i) == "(")|| (Mascara.charAt(i) == ")") || (Mascara.charAt(i) == " "))
      if (boleanoMascara) {
        NovoValorCampo += Mascara.charAt(i);
        TamanhoMascara++;
      }else {
        NovoValorCampo += campoSoNumeros.charAt(posicaoCampo);
        posicaoCampo++;
      }
    }
    campo.value = NovoValorCampo;
    return true;
  }else {
    return true;
  }
}

function checkCPF(obj){

  if(obj.value.length>=13){
    $('input[type="submit"]').prop('disabled', false);
  }else{
    $('input[type="submit"]').prop('disabled', true);
  }
}
</script>
<?
include 'footer.php';
?>
