<?

/* ================================================================================================================== */
/* DBO DEFINITION FILE FOR MODULE 'palestra' ==================================== AUTO-CREATED ON 17/05/2016 12:38:53 */
/* ================================================================================================================== */



/* GENERAL MODULE DEFINITIONS ======================================================================================= */

$module = new Obj();
$module->modulo = 'palestra';
$module->tabela = 'palestra';
$module->titulo = 'Atividade';
$module->titulo_plural = 'Atividades';
$module->classes_listagem = 'almost full';
$module->genero = 'a';
$module->paginacao = '20';
$module->update = true;
$module->delete = true;
$module->insert = 'Nova Atividade';
$module->preload_insert_form = true;
$module->auto_view = false;
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
$field->titulo = 'Evento';
$field->coluna = 'evento';
$field->pk = false;
$field->isnull = false;
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
	$join->modulo = 'evento';
	$join->chave = 'id';
	$join->valor = 'nome';
	$join->tipo = 'select';
	$join->order_by = '';
$field->join = $join;
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Título';
$field->coluna = 'titulo';
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
$field->tipo = 'textarea';
$field->rows = 3;
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Descrição';
$field->coluna = 'descricao';
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
$field->classes = 'autosize';
$field->tipo = 'textarea';
$field->rows = 5;
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Data';
$field->coluna = 'data';
$field->default = 'ASC';
$field->pk = false;
$field->isnull = false;
$field->add = true;
$field->valida = true;
$field->edit = true;
$field->view = true;
$field->lista = true;
$field->filter = true;
$field->order = true;
$field->type = 'DATE';
$field->interaction = '';
$field->mask = '99/99/9999';
$field->tipo = 'date';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Horário';
$field->coluna = 'horario';
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
$field->mask = '99h99 - 99h99';
$field->tipo = 'text';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Total de Vagas';
$field->coluna = 'vagas';
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
$field->titulo = 'Inscritos';
$field->coluna = 'inscritos';
$field->pk = false;
$field->isnull = false;
$field->add = false;
$field->valida = false;
$field->edit = false;
$field->view = true;
$field->lista = true;
$field->filter = false;
$field->order = false;
$field->type = 'VARCHAR(255)';
$field->interaction = '';
$field->tipo = 'query';
$field->query = '
	$query = "SELECT COUNT(*) AS val FROM inscricao WHERE palestra = \'".$id."\'";
';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Valor';
$field->coluna = 'valor';
$field->pk = false;
$field->isnull = true;
$field->add = true;
$field->valida = false;
$field->edit = true;
$field->view = true;
$field->lista = true;
$field->filter = true;
$field->order = true;
$field->type = 'DOUBLE';
$field->interaction = '';
$field->tipo = 'price';
$field->formato = 'real';
$module->campo[$field->coluna] = $field;

/*==========================================*/

/* GRID FOR THE FORM LAYOUT ========================================================================================= */

$grid = array();

$grid[] = array('12');
$grid[] = array('12');
$grid[] = array('12');
$grid[] = array('4','4','4');
$grid[] = array('4');

$module->grid = $grid;

/* MODULE LIST BUTTONS ============================================================================================== */

$button = new Obj();
$button->value = 'Inscrições';
$button->modulo = 'inscricao';
$button->modulo_fk = 'palestra';
$button->key = 'id';
$button->view = false;
$button->show = false;
$button->subsection = false;
$button->autoload = false;
$module->button[] = $button;

/* FUNÇÕES AUXILIARES =============================================================================================== */

if(!function_exists('palestra_pre_insert'))
{
	function palestra_pre_insert () // there is nothing to get as a parameter, right?
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('palestra_pos_insert'))
{
	function palestra_pos_insert ($obj) // active just inserted object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('palestra_pre_update'))
{
	function palestra_pre_update ($obj) // active object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('palestra_pos_update'))
{
	function palestra_pos_update ($obj) // active updated object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('palestra_pre_delete'))
{
	function palestra_pre_delete ($obj) // active object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('palestra_pos_delete'))
{
	function palestra_pos_delete ($obj) // active deleted object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('palestra_pre_list'))
{
	function palestra_pre_list () // nothing to be passed here...
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('palestra_pos_list'))
{
	function palestra_pos_list ($ids) // ids of the listed elements
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('palestra_notifications'))
{
	function palestra_notifications ($type = '')
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('palestra_overview'))
{
	function palestra_overview ($foo)
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}


?>