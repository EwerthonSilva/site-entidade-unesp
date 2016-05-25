<?
/*
-- Este é um plugin modelo para o DBO.
-- Todos os plugins devem ser colocados dentro de uma pasta com o mesmo nome do arquivo principal (.php) do plugin.
-- Este arquivo está na pasta raiz de plugins apenas para servir de guia.
-- Siga os passos:
-- 1) Crie uma pasta com o nome do seu plugin dentro da pasta Plugins. ex: color_picker (os nomes não devem conter caracteres especiais, maiusculas ou hífen )
-- 2) Copie este arquivo e renomei-o para o mesmo nome da pasta criada (.php)
-- 3) Siga o restante das instruções abaixo para finalizar. Você pode apagar esse bloco inicial de comentários

	Variáveis globais úteis

	DBO_PATH       ---> contem o caminho completo da pasta dbo de sua aplicação.
	DBO_URL        ---> caminho HTML da pasta dbo. Útil caso você deseje incluir figuras, CSSs ou scripts no seu plugin.
						ex: <img src="<?= DBO_URL ?>/plugins/plugin_modelo/images/icone.gif"/>

*/

/*
Plugin Name: Locais FCFAR     //Nome que aparecerá no assistente de criação de módulo. ***Obrigatório***
Description: Permite ao usuários pesquisar hierarquicamente no banco de dados de locais da Faculdade.
Params:                                 //Declare seus parametros como mostrado. Se for um parametro digitado usuário colocar [user] antes. ***Obrigatório***
	root: nó inicial da lista de locais (root/id)
*/

/* a classe deve ter o mesmo nome da pasta e do arquivo .php do plugin, mas comecando com 'dbo_' */
class dbo_fcfar_local
{

	/* ========================================== */
	/* ========== MÉTODOS OBRIGATÓRIOS ========== */
	/* ========================================== */

	/* Seta todos os parametros passados para o objeto, faça como achar melhor.
	   Os parametros serão passadas em forma de array.
	   Se não houver nenhum parametro, será um array em branco */
	function __construct ($params = array())
	{
		foreach($params as $chave => $valor)
		{
			$this->{$chave} = $valor;
		}
	}

	/* Mostra o dado na listagem, ele vai ficar dentro da celula de uma tabela.
	   Recebe o nome da coluna do banco de dados como parametro, caso deseje usar. */
	function getList ($coluna = '')
	{
		if($this->local->id)
		{
			return $this->local->getSmartLocal();
		}
	}

	/* Imprime o(s) input(s) no formulário de inserção.
	   Recebe o nome da coluna no banco de dados como parametro, caso voce queira usar no nome de seus inputs para algum fim.  */

	function getInsertForm ($coluna = '')
	{
		ob_start();
		?>
		<div class='row collapse'>
			<div class='small-9 large-10 columns'><input type='text' data-name="<?= $coluna ?>" name='aux_<?= $coluna ?>' value="" class="aux-<?= $coluna ?>" placeholder='Digite algumas letras para procurar...'/></div>
			<div class='small-3 large-2 columns'><input type='button' name='' tabindex='-1' value="Alterar" class="local-clearer button disabled postfix radius"/></div>
		</div>
		<input type='hidden' name='<?= $coluna ?>' value=""/>
		<?
		echo $this->scripts($coluna);
		$ob_result = ob_get_clean();
		return $ob_result;
	}

	/* Imprime o(s) input(s) no formulário de update.
	   Recebe o nome da coluna no banco de dados como parametro, caso voce queira usar no nome de seus inputs para algum fim.  */
	function getUpdateForm ($coluna = '')
	{
		$local = (($this->local->id)?($this->local->id):(false));
		ob_start();
		?>
		<div class='row collapse'>
			<div class='small-9 large-10 columns'><input type='text' data-name="<?= $coluna ?>" name='aux_<?= $coluna ?>' value="<?= (($local)?(htmlSpecialChars($this->local->getSmartLocal())):('')) ?>" class="aux-<?= $coluna ?> <?= (($this->local->id)?("ok"):('')) ?>" placeholder='Digite algumas letras para procurar...' <?= (($this->local->id)?('readonly'):('')) ?> /></div>
			<div class='small-3 large-2 columns'><input type='button' name='' tabindex='-1' value="Alterar" class="local-clearer button <?= (($this->local->id)?(''):('disabled')) ?> postfix radius"/></div>
		</div>
		<input type='hidden' name='<?= $coluna ?>' value="<?= $this->local->id ?>"/>
		<?
		echo $this->scripts($coluna);
		$ob_result = ob_get_clean();
		return $ob_result;
	}

	/* Imprime o seu TAD na visualização do registro pai  */
	function getView ($coluna = '')
	{
		if($this->local->id)
		{
			return $this->local->getSmartLocal();
		}
	}

	/* Seta os dados provenientes do formulário de inserção/update na classe.
	   Você deve processar os dados e retorná-los em uma unica string, na função getData(), para o banco. */
	function setFormData ($coluna = '')
	{
		$this->local = $_POST[$coluna];
	}

	/* Seta os dados na classe, a entrada é no mesmo formato da saida da função getData() */
	function setData ($data)
	{
		if(intval($data) > 0)
		{
			$this->local = new local($data);
		}
	}

	/* Retorna os dados processados em uma unica string para o banco de dados do registro pai. */
	function getData ()
	{
		return $this->local;
	}

	/* Retorna o parametro pedido pelo usuário. */
	function getParam ($param)
	{
		return $this->{$param};
	}

	/* == END =================================== */
	/* == END === MÉTODOS OBRIGATÓRIOS ========== */
	/* == END =================================== */

	/* Daqui para baixo você pode criar qualquer método que julgar necessário para seu plugin.
	   Você também pode usar mais arquivos e pastas se quiser. */
	
	function scripts($coluna)
	{
		ob_start();
		?>
		<script>
			$(document).ready(function(){

				$(document).on('click', '.local-clearer:not(.disabled)', function(){
					$(this).addClass('disabled');
					$(this).closest('.item').find('.aux-<?= $coluna ?>').removeClass('ok').removeAttr('readonly').val('').focus();
					$(this).closest('.item').find('input[name^=<?= $coluna ?>]').val('');
				})

				$(document).on('focus', '.aux-<?= $coluna ?>', function(){
					$(this).autocomplete({
						source: function(request, response){
							$.get("<?= DBO_URL ?>/plugins/fcfar_local/ajax-locais.php", {term:request.term}, function(data){
								response($.map(data, function(item) {
									return {
										label: item.local,
										value: item.id
									}
								}))
							}, "json");
						},
						minLength: 2,
						dataType: "json",
						cache: false,
						focus: function(event, ui) {
							return false;
						},
						change: function (event, ui){
							if(!ui.item){
								$(this).val('');
							}
						},
						delay: 1,
						select: function(event, ui) {
							if(ui.item.value != '-1'){
								this.value = ui.item.label;
								$(this).attr('readonly', 'readonly');
								$(this).removeClass('error');
								$(this).addClass('ok');
								$(this).closest('.item').find('input[name^=<?= $coluna ?>]').val(ui.item.value);
								$(this).closest('.item').find('.local-clearer').removeClass('disabled');
							}
							else {
								this.value = '';
								$(this).closest('.item-local').find('input[name^=<?= $coluna ?>]').val('');
							}
							return false;
						}
					});
				})
			}) //doc.ready
		</script>
		<?
		return ob_get_clean();
	}
}
?>