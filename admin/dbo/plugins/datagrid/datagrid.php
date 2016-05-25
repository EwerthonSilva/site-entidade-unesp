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
Plugin Name: Datagrid //Nome que aparecerá no assistente de criação de módulo. ***Obrigatório***
Description: Permite a inserção de dados que serão salvos como uma planilha json no banco de dados.
Params: //Declare seus parametros como mostrado. Se for um parametro digitado usuário colocar [user] antes. ***Obrigatório***
	linhas: quantidade de linhas do datagrid, é um número ou array de dados\n\nExemplo:\n3\ngoogle_plus:Google +,facebook:Facebook\n
	colunas: definição das colunas, separadas por '---'.\n\nA definição do campo é:\ncampo:Título do campo|tipo do campo|valores separados por vírgula|classes|tamanho da coluna\n\nExemplos:\nnome:Nome|text||.nowrap|6---\ntelefone:Telefone Celular|text|.mask-telefone|3---\nsexo:Sexo|select|m:Masculino,f:Feminino||3
*/

/* a classe deve ter o mesmo nome da pasta e do arquivo .php do plugin, mas comecando com 'dbo_' */
class dbo_datagrid
{

	/* ========================================== */
	/* ========== MÉTODOS OBRIGATÓRIOS ========== */
	/* ========================================== */

	/* Seta todos os parametros passados para o objeto, faça como achar melhor.
	   Os parametros serão passadas em forma de array.
	   Se não houver nenhum parametro, será um array em branco */
	function __construct ($params = array())
	{
		$this->datagrid_id = 'json_datagrid_'.uniqid();
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
		$def = $this->decodeDatagridDefinition($this->colunas);
		return $this->renderDatagrid($def, 'insert', $coluna, $this->data);
	}

	/* Imprime o(s) input(s) no formulário de update.
	   Recebe o nome da coluna no banco de dados como parametro, caso voce queira usar no nome de seus inputs para algum fim.  */
	function getUpdateForm ($coluna = '')
	{
		$def = $this->decodeDatagridDefinition($this->colunas);
		return $this->renderDatagrid($def, 'update', $coluna, $this->data);
	}

	/* Imprime o seu TAD na visualização do registro pai  */
	function getView ($coluna = '')
	{
		return 'implementar view...';
	}

	/* Seta os dados provenientes do formulário de inserção/update na classe.
	   Você deve processar os dados e retorná-los em uma unica string, na função getData(), para o banco. */
	function setFormData ($coluna = '')
	{
		require_once(DBO_PATH.'/core/dbo-ui.php');

		//recebe os dados do post
		$data = $_POST[$_POST[$coluna]];

		$ft = $this->getArrayFieldTypes();
		
		//agora iteramos os dados do post derificando o tipo de dado na definição, e aplicando o tratamento do dboUI.
		foreach((array)$data as $linha => $dados)
		{
			foreach($dados as $input_name => $input_value)
			{
				$data[$linha][$input_name] = dboUI::fieldSQL($ft[$input_name], $input_value);
			}
		}
		$this->data = $data;
	}

	/* Seta os dados na classe, a entrada é no mesmo formato da saida da função getData() */
	function setData ($data)
	{
		$this->data = json_decode($data, true);
	}

	/* Retorna os dados processados em uma unica string para o banco de dados do registro pai. */
	function getData ()
	{
		return json_encode($this->data);
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

	function decodeDatagridDefinition($def)
	{
		$colunas = explode('---', $def);
		$colunas = array_map(trim, $colunas);
		foreach($colunas as $col_def)
		{
			//explode inicial
			list($campo, $tipo, $valores, $classes, $tamanho) = explode('|', $col_def);

			//montando o array do input
			list($name, $label) = explode(':', $campo);

			//setando o tipo de campo, padrão text
			$tipo = strlen(trim($tipo)) ? $tipo : 'text';

			//montando o array de possíveis valores
			$valores_aux = explode(',', $valores);
			$valores = array();
			if(sizeof($valores_aux) > 1)
			{
				foreach($valores_aux as $valor)
				{
					list($key, $value) = explode(':', $valor);
					$valores[] = array(
						'key' => $key,
						'value' => $value,
					);
				}
			}

			//montando o array de classes
			$classes_aux = explode('.', $classes);
			$classes = array();
			array_shift($classes_aux);
			if(sizeof($classes_aux))
			{
				$classes = $classes_aux;
			}

			//juntando tudo
			$return[] = array(
				'campo' => array(
					'name' => $name,
					'label' => $label,
				),
				'tipo' => $tipo,
				'valores' => $valores,
				'classes' => $classes,
				'tamanho' => $tamanho
			);
		}
		return $return;
	} 
	
	function getColumnNames($def)
	{
		$names = array();
		foreach($def as $value)
		{
			$names[] = $value['campo']['label'];
		}
		return $names;
	}

	function getColumnSizes($def)
	{
		$sizes = array();
		foreach($def as $value)
		{
			$sizes[] = $value['tamanho'];
		}
		return $sizes;
	}

	function renderDatagrid($def, $operation, $coluna, $data = array())
	{
		//primeiro decodificamos as informações das colunas do datagrid
		$col_names = $this->getColumnNames($def);
		$col_sizes = $this->getColumnSizes($def);
		ob_start();
		?>
		<table class="json-datagrid">
			<thead>
				<tr>
					<?php
						if(!$this->hasNumericLines())
						{
							?>
							<th></th>
							<?php
						}
						foreach($col_names as $key => $value)
						{
							?>
							<th width="<?= $this->percentConv($col_sizes[$key]) ?>"><?= $value ?></th>
							<?php
						}
					?>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach($this->getArrayLinhas() as $i => $title)
					{
						echo '<tr>';
						echo $this->hasNumericLines() ? '' : '<td style="padding: 0 .6rem;">'.$title.'</td>';
						foreach($def as $key => $value)
						{
							echo '<td>'.$this->renderInput($value, $operation, $i, $data[$i]).'</td>';
						}
						echo '</tr>';
					}
				?>
			</tbody>
		</table>
		<input type="hidden" name="<?= $coluna ?>" id="" value="<?= $this->datagrid_id ?>"/>
		<?php
		return ob_get_clean();
	}

	function renderInput($def, $operation, $line_number, $data = array())
	{
		require_once(DBO_PATH.'/core/dbo-ui.php');

		ob_start();

		//preparando alguns dados para o dbo-ui
		foreach($def['valores'] as $i => $aux)
		{
			if($aux['key'] == '') continue;
			$valores[$aux['key']] = $aux['value'];
		}

		//montando o array de dados para o dbo-ui
		$params = array(
			'field_type' => $def['tipo'],
			'name' => $this->datagrid_id.'['.$line_number.']['.$def['campo']['name'].']',
			'value' => $data[$def['campo']['name']],
			'valores' => $valores,
			'input_only' => true,
			'classes' => implode(' ', $def['classes']),
		);

		//tratando especificidades
		if($def['tipo'] == 'select')
		{
			$params['allow_empty'] = $def['valores'][0]['key'] == '' ? true : false;
		}
		elseif($def['tipo'] == 'price')
		{
			$params['formato'] = 'generico';
		}
		echo dboUI::field($def['tipo'], $operation, false, $params);
		return ob_get_clean();
	}

	function getArrayLinhas()
	{
		$linhas = array();
		if(is_numeric($this->linhas))
		{
			for($i=0; $i<$this->linhas; $i++)
			{
				$linhas[$i] = $i;
			}
		}
		else
		{
			$partes = explode(',', $this->linhas);
			foreach($partes as $val)
			{
				list($key, $value) = explode(':', $val);
				$linhas[$key] = $value;
			}
		}
		return $linhas;
	}

	function percentConv($size)
	{
		return ($size/12*100).'%';
	}

	function hasNumericLines()
	{
		return is_numeric($this->linhas);
	}

	function getArrayFieldTypes()
	{
		//pega a definição do datagrid para o tratamento de valores no save.
		$def = $this->decodeDatagridDefinition($this->colunas);

		$field_types = array();
		foreach($def as $key => $value)
		{
			$field_types[$value['campo']['name']] = $value['tipo'];
		}
		return $field_types;
	}

}
?>