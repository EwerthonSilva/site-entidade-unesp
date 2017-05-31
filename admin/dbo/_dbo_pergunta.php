<?

/* ================================================================================================================== */
/* DBO DEFINITION FILE FOR MODULE 'pergunta' ==================================== AUTO-CREATED ON 06/05/2016 08:44:09 */
/* ================================================================================================================== */



/* GENERAL MODULE DEFINITIONS ======================================================================================= */

$module = new Obj();
$module->modulo = 'pergunta';
$module->tabela = 'pergunta';
$module->titulo = 'Pergunta';
$module->titulo_plural = 'Perguntas';
$module->classes_listagem = 'full';
$module->genero = 'a';
$module->paginacao = '20';
$module->update = true;
$module->delete = true;
$module->insert = 'Nova Pergunta';
$module->preload_insert_form = true;
$module->auto_view = true;
$module->ignore_permissions = false;
$module->order_by = '4';

/* FIELDS =========================================================================================================== */

$field = new Obj();
$field->titulo = 'Id';
$field->coluna = 'id';
$field->pk = true;
$field->isnull = false;
$field->add = false;
$field->valida = false;
$field->edit = false;
$field->view = false;
$field->lista = false;
$field->filter = false;
$field->order = false;
$field->type = 'INT NOT NULL auto_increment';
$field->interaction = '';
$field->tipo = 'pk';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Pesquisa';
$field->coluna = 'pesquisa';
$field->pk = false;
$field->isnull = true;
$field->add = true;
$field->valida = false;
$field->edit = true;
$field->view = true;
$field->lista = false;
$field->filter = false;
$field->order = false;
$field->type = 'INT(11)';
$field->interaction = '';
$field->tipo = 'join';
	$join = new Obj();
	$join->modulo = 'pesquisa';
	$join->chave = 'id';
	$join->valor = 'nome';
	$join->tipo = 'select';
	$join->order_by = 'id';
$field->join = $join;
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Pergunta';
$field->coluna = 'pergunta';
$field->pk = false;
$field->isnull = false;
$field->add = true;
$field->valida = true;
$field->edit = true;
$field->view = true;
$field->lista = true;
$field->filter = true;
$field->order = true;
$field->type = 'VARCHAR(255)';
$field->interaction = '';
$field->tipo = 'text';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Tipo de Pergunta';
$field->coluna = 'tipo';
$field->pk = false;
$field->isnull = false;
$field->add = true;
$field->valida = true;
$field->edit = true;
$field->view = true;
$field->lista = true;
$field->filter = true;
$field->order = true;
$field->type = 'VARCHAR(255)';
$field->interaction = '';
$field->tipo = 'select';
$field->valores = array(
	'text' => 'Texto',
	'textarea' => 'Texto Longo',
	'select' => '1 opção de uma caixa de seleção',
	'radio' => '1 opção de uma lista',
	'checkbox' => 'Várias opções de uma lista',
);
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Opções da Pergunta';
$field->coluna = 'opcoes';
$field->dica = '1 por linha';
$field->pk = false;
$field->isnull = false;
$field->add = true;
$field->valida = false;
$field->edit = true;
$field->view = true;
$field->lista = false;
$field->filter = false;
$field->order = false;
$field->type = 'TEXT';
$field->interaction = '';
$field->tipo = 'textarea';
$field->rows = 10;
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Obrigatória';
$field->coluna = 'obrigatoria';
$field->pk = false;
$field->isnull = false;
$field->add = true;
$field->valida = false;
$field->edit = true;
$field->view = true;
$field->lista = true;
$field->filter = false;
$field->order = false;
$field->type = 'VARCHAR(255)';
$field->interaction = '';
$field->default_value = '1';
$field->tipo = 'radio';
$field->valores = array(
	'0' => 'nao',
	'1' => 'sim',
);
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Permitir "Outro"';
$field->coluna = 'permitir_outro';
$field->pk = false;
$field->isnull = false;
$field->add = true;
$field->valida = false;
$field->edit = true;
$field->view = true;
$field->lista = false;
$field->filter = false;
$field->order = false;
$field->type = 'VARCHAR(255)';
$field->interaction = '';
$field->tipo = 'radio';
$field->valores = array(
	'0' => 'não',
	'1' => 'sim',
);
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Auto Ordenação';
$field->coluna = 'order_by';
$field->pk = false;
$field->isnull = false;
$field->add = false;
$field->valida = false;
$field->edit = false;
$field->view = false;
$field->lista = false;
$field->filter = false;
$field->order = false;
$field->type = 'INT(11)';
$field->interaction = '';
$field->tipo = 'text';
$module->campo[$field->coluna] = $field;

/*==========================================*/

/* GRID FOR THE FORM LAYOUT ========================================================================================= */

$grid = array();


$module->grid = $grid;

/* MODULE LIST BUTTONS ============================================================================================== */

$button = new Obj();
$button->value = 'Respostas';
$button->modulo = 'resposta';
$button->modulo_fk = 'pergunta';
$button->key = 'id';
$button->view = false;
$button->show = true;
$button->subsection = false;
$button->autoload = false;
$module->button[] = $button;

/* FUNÇÕES AUXILIARES =============================================================================================== */

if(!function_exists('pergunta_pre_insert'))
{
	function pergunta_pre_insert () // there is nothing to get as a parameter, right?
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pergunta_pos_insert'))
{
	function pergunta_pos_insert ($obj) // active just inserted object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pergunta_pre_update'))
{
	function pergunta_pre_update ($obj) // active object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pergunta_pos_update'))
{
	function pergunta_pos_update ($obj) // active updated object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pergunta_pre_delete'))
{
	function pergunta_pre_delete ($obj) // active object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pergunta_pos_delete'))
{
	function pergunta_pos_delete ($obj) // active deleted object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pergunta_pre_list'))
{
	function pergunta_pre_list () // nothing to be passed here...
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pergunta_pos_list'))
{
	function pergunta_pos_list ($ids) // ids of the listed elements
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pergunta_notifications'))
{
	function pergunta_notifications ($type = '')
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pergunta_overview'))
{
	function pergunta_overview ($foo)
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}


?>
