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
Plugin Name: Image Cropper     //Nome que aparecerá no assistente de criação de módulo. ***Obrigatório***
Description: Permite ao usuário cortar uma imagem em um tamanho pré-definido.
Params:                                 //Declare seus parametros como mostrado. Se for um parametro digitado usuário colocar [user] antes. ***Obrigatório***
	cw1: largura do crop 1
	ch1: altura do crop 1
	cd1: descrição do crop 1
	cw2: largura do crop 2 (opt)
	ch2: altura do crop 2 (opt)
	cd2: descrição do crop 2 (opt)
	cw3: largura do crop 3 (opt)
	ch3: altura do crop 3 (opt)
	cd3: descrição do crop 3 (opt)
	cw4: largura do crop 4 (opt)
	ch4: altura do crop 4 (opt)
	cd4: descrição do crop 4 (opt)
	cw5: largura do crop 5 (opt)
	ch5: altura do crop 5 (opt)
	cd5: descrição do crop 5 (opt)
	fullw1: largura da imagem 
	fullh1: altura da imagem
	fullw2: largura da imagem 
	fullh2: altura da imagem
	fullw3: largura da imagem 
	fullh3: altura da imagem
	fullw4: largura da imagem 
	fullh4: altura da imagem
	fullw5: largura da imagem 
	fullh5: altura da imagem
	list: largura da imagem na listagem
	keep_original: true/false
*/

/* a classe deve ter o mesmo nome da pasta e do arquivo .php do plugin, mas comecando com 'dbo_' */
class dbo_jcrop_dbo
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
		if($this->image)
		{
			return "<img src='".DBO_URL."/upload/images/c1_".$this->image."' style='padding: 1px; border: 1px solid #CCC; max-height: 60px; max-width: 150px;'/>";
		}
	}

	/* Imprime o(s) input(s) no formulário de inserção.
	   Recebe o nome da coluna no banco de dados como parametro, caso voce queira usar no nome de seus inputs para algum fim.  */

	function getInsertForm ($coluna = '')
	{
		echo dboImportJs('colorbox');
		$result .= "<div class='jcrop-dbo-result-canvas' id='jcrop-dbo-preview-".$coluna."'><img src='images/spacer.gif' class='thumb-lista' style='display: none; width: 100%; max-width: 100%;'></div>";
		$result .= "<a href='".DBO_URL."/plugins/jcrop_dbo/cropper.php?data=".dboEncode(serialize($this))."&coluna=".$coluna."' data-width='1050' data-height='98%' rel='modal' class='button secondary radius small'>Selecione a foto para tratamento</a>";
		$result .= "<input type='hidden' name='".$coluna."[image]' id='jcrop-dbo-field-".$coluna."'>";
		$result .= '<script>function closeBox(coluna, filename) { $(\'#jcrop-dbo-field-\'+coluna).val(filename); $(\'#jcrop-dbo-preview-\'+coluna+\' img\').attr(\'src\', \''.DBO_URL.'/plugins/jcrop_dbo/temp/c1_\'+filename).fadeIn(); $.colorbox.close(); }</script>';

		return $result;
	}

	/* Imprime o(s) input(s) no formulário de update.
	   Recebe o nome da coluna no banco de dados como parametro, caso voce queira usar no nome de seus inputs para algum fim.  */
	function getUpdateForm ($coluna = '')
	{
		echo dboImportJs('colorbox');
		$result .= "<input type='hidden' name='".$coluna."[image]' id='jcrop-dbo-field-".$coluna."' value='".$this->image."' rel='".$this->image."'>";
		if(strlen($this->image)) //if there is a picture in the db
		{
			$result .= "<div class='jcrop-dbo-result-canvas' id='jcrop-dbo-preview-".$coluna."'><img src='".DBO_URL."/upload/images/c1_".$this->image."' class='thumb-lista' style=\"width: 100%; max-width: 100%;\"></div>";
			$result .=
			'<script>
				$(document).on(\'click\', \'#jcrop-dbo-keep-'.$coluna.'\', function(){
					if ($(this).is(\':checked\')) {
						$(\'#jcrop-dbo-field-'.$coluna.'\').val($(\'#jcrop-dbo-field-'.$coluna.'\').attr(\'rel\'));
						$(\'#jcrop-dbo-preview-'.$coluna.' img\').attr(\'src\', \''.DBO_URL.'/upload/images/c1_\'+$(\'#jcrop-dbo-field-'.$coluna.'\').attr(\'rel\')).fadeIn();
					} else {
						$(\'#jcrop-dbo-field-'.$coluna.'\').val(\'\');
						$(\'#jcrop-dbo-preview-'.$coluna.' img\').fadeOut();
					}
				});
			</script>';
		}
		else
		{
			$result .= "<div class='jcrop-dbo-result-canvas' id='jcrop-dbo-preview-".$coluna."' style=\"min-height: 60px;\"><img src='images/spacer.gif' class='thumb-lista' style='display: none; width: 100%; max-width: 100%;'></div>";
		}
		$result .= "<a href='".DBO_URL."/plugins/jcrop_dbo/cropper.php?data=".dboEncode(serialize($this))."&coluna=".$coluna."' data-width='1050' data-height='98%' rel='modal' class='jcrop-alterar-foto' id='jcrop-dbo-trigger-cropper-".$coluna."'>Alterar foto</a> ".((strlen($this->image))?("<span style='white-space: nowrap; display: none;'><input style='display: inline' type='checkbox' CHECKED name='".$coluna."[keep]' value='1' id='jcrop-dbo-keep-".$coluna."'> Manter a foto atual</span>"):(''));
		$result .= '<script>function closeBox(coluna, filename) { $(\'#jcrop-dbo-field-\'+coluna).val(filename); $(\'#jcrop-dbo-preview-\'+coluna+\' img\').attr(\'src\', \''.DBO_URL.'/plugins/jcrop_dbo/temp/c1_\'+filename).fadeIn(); $.colorbox.close(); $(\'#jcrop-dbo-keep-\'+coluna).removeAttr(\'checked\'); }</script>';
		ob_start();
		?>
		<style>
			.jcrop-alterar-foto {
				display: block; padding: 1em; background: rgba(1,1,1,.5); z-index: 10; position: relative; color: #fff; font-size: 12px; text-transform: uppercase; text-align: center; margin: -56px auto 20px auto; width: calc(100% - 8px);
			}
			.jcrop-alterar-foto:hover { color: #fff; background: rgba(1,1,1,.7); }
		</style>
		<?
		$result .= ob_get_clean();
		return $result;
	}

	/* Imprime o seu TAD na visualização do registro pai  */
	function getView ($coluna = '')
	{
		if($this->image)
		{
			return "<img src='".DBO_URL."/upload/images/c1_".$this->image."' style='padding: 3px; border: 1px solid #CCC; max-width: 90%;'/>";
		}
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
		//moving the files between places on the server
		if(!$this->keep && strlen($this->image))
		{
			//crops
			@rename(DBO_PATH."/plugins/jcrop_dbo/temp/c1_".$this->image, DBO_PATH."/upload/images/c1_".$this->image);
			@rename(DBO_PATH."/plugins/jcrop_dbo/temp/c2_".$this->image, DBO_PATH."/upload/images/c2_".$this->image);
			@rename(DBO_PATH."/plugins/jcrop_dbo/temp/c3_".$this->image, DBO_PATH."/upload/images/c3_".$this->image);
			@rename(DBO_PATH."/plugins/jcrop_dbo/temp/c4_".$this->image, DBO_PATH."/upload/images/c4_".$this->image);
			@rename(DBO_PATH."/plugins/jcrop_dbo/temp/c5_".$this->image, DBO_PATH."/upload/images/c5_".$this->image);
			//fulls
			@rename(DBO_PATH."/plugins/jcrop_dbo/temp/f1_".$this->image, DBO_PATH."/upload/images/f1_".$this->image);
			@rename(DBO_PATH."/plugins/jcrop_dbo/temp/f2_".$this->image, DBO_PATH."/upload/images/f2_".$this->image);
			@rename(DBO_PATH."/plugins/jcrop_dbo/temp/f3_".$this->image, DBO_PATH."/upload/images/f3_".$this->image);
			@rename(DBO_PATH."/plugins/jcrop_dbo/temp/f4_".$this->image, DBO_PATH."/upload/images/f4_".$this->image);
			@rename(DBO_PATH."/plugins/jcrop_dbo/temp/f5_".$this->image, DBO_PATH."/upload/images/f5_".$this->image);
			//list
			@rename(DBO_PATH."/plugins/jcrop_dbo/temp/list_".$this->image, DBO_PATH."/upload/images/list_".$this->image);
			//original
			if($this->keep_original) {
				@rename(DBO_PATH."/plugins/jcrop_dbo/temp/orig_".$this->image, DBO_PATH."/upload/images/orig_".$this->image);
			} else {
				@unlink(DBO_PATH."/plugins/jcrop_dbo/temp/orig_".$this->image);
			}
		}
	}

	/* Seta os dados na classe, a entrada é no mesmo formato da saida da função getData() */
	function setData ($data)
	{
		$this->image = $data;
	}

	/* Retorna os dados processados em uma unica string para o banco de dados do registro pai. */
	function getData ()
	{
		return $this->image;
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

}

?>