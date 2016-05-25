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
Plugin Name: Plugin Teste	       //Nome que aparecerá no assistente de criação de módulo
Description: Permite que usuário crie um quadrilatero colorido na tela
Params:							   //Declare seus parametros como mostrado. Se for um parametro de usuário colocar [user] antes
	x: tamanho x do quadrado
	y: altura y do quadrado
	[user] bgcolor: hexadecimal da cor de fundo do quadrado (utilize #)
	[user] bordercolor: hexadecimal da cor da borda do quadrado (utilize #)
*/

/* a classe deve ter o mesmo nome da pasta e do arquivo .php do plugin, mas comecando com 'dbo_' */
class dbo_plugin_teste
{

	var $x;
	var $y;
	var $bgcolor;
	var $bordercolor;

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
		if($this->bgcolor && $this->bordercolor)
		{
			return "<div style='width: ".$this->x."px; height: ".$this->y."px; background: ".$this->bgcolor."; border: 1px solid ".$this->bordercolor."; '>&nbsp;</div>";
		}
	}

	/* Imprime o(s) input(s) no formulário de inserção.
	   Recebe o nome da coluna no banco de dados como parametro, caso voce queira usar no nome de seus inputs para algum fim.  */
	function getInsertForm ($coluna = '')
	{
		$result  = "<label>Cor do fundo do quadrado</label>";
		$result .= "<input type='text' name='".$coluna."[bgcolor]'>";

		$result .= "<label>Cor da borda do quadrado</label>";
		$result .= "<input type='text' name='".$coluna."[bordercolor]'>";

		return $result;
	}

	/* Imprime o(s) input(s) no formulário de update.
	   Recebe o nome da coluna no banco de dados como parametro, caso voce queira usar no nome de seus inputs para algum fim.  */
	function getUpdateForm ($coluna = '')
	{
		$result  = "<label>Cor do fundo do quadrado</label>";
		$result .= "<input type='text' name='".$coluna."[bgcolor]' value='".$this->bgcolor."'>";

		$result .= "<label>Cor da borda do quadrado</label>";
		$result .= "<input type='text' name='".$coluna."[bordercolor]' value='".$this->bordercolor."'>";

		return $result;
	}

	/* Imprime o seu TAD na visualização do registro pai  */
	function getView ($coluna = '')
	{

	}

	/* Seta os dados provenientes do formulário de inserção/update na classe.
	   Você deve processar os dados e retorná-los em uma unica string, na função getData(), para o banco. */
	function setFormData ($coluna = '')
	{
		global $_POST;
		foreach($_POST[$coluna] as $chave => $valor)
		{
			$this->{$chave} = $valor;
		}
	}

	/* Seta os dados na classe, a entrada é no mesmo formato da saida da função getData() */
	function setData ($data)
	{
		list($bgcolor, $bordercolor) = explode("\n", $data);
		$this->bgcolor = $bgcolor;
		$this->bordercolor = $bordercolor;
	}

	/* Retorna os dados processados em uma unica string para o banco de dados do registro pai. */
	function getData ()
	{
		return $this->bgcolor."\n".$this->bordercolor;
	}

	/* Retorna o parametro pedido pelo usuário. */
	function getParam ($param)
	{
		return $this->{$param};
	}

	/* == END =================================== */
	/* == END === MÉTODOS OBRIGATÓRIOS ========== */
	/* == END =================================== */

	/* Daqui para baixo você pode criar qualquer método que julgar necessário para seu plugin. Você também pode usar mais arquivos e pastas que julgar necessário */

}

?>