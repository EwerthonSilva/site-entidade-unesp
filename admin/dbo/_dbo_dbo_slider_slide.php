<?

/* ================================================================================================================== */
/* DBO DEFINITION FILE FOR MODULE 'dbo_slider_slide' ============================ AUTO-CREATED ON 01/04/2016 15:49:50 */
/* ================================================================================================================== */



/* GENERAL MODULE DEFINITIONS ======================================================================================= */

$module = new Obj();
$module->modulo = 'dbo_slider_slide';
$module->tabela = 'dbo_slider_slide';
$module->titulo = 'Slide';
$module->titulo_plural = 'Slides';
$module->module_icon = 'picture-o';
$module->genero = 'o';
$module->paginacao = '20';
$module->update = true;
$module->delete = true;
$module->insert = 'Novo Slide';
$module->preload_insert_form = true;
$module->auto_view = true;
$module->ignore_permissions = true;
$module->order_by = '-970';
$module->dbo_maker_read_only = true;
$module->table_engine = 'InnoDB';

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
$field->titulo = 'Slider';
$field->coluna = 'slider';
$field->pk = false;
$field->isnull = true;
$field->add = false;
$field->valida = false;
$field->edit = false;
$field->view = false;
$field->lista = false;
$field->filter = false;
$field->order = false;
$field->type = 'INT(11)';
$field->interaction = '';
$field->tipo = 'join';
	$join = new Obj();
	$join->modulo = 'dbo_slider';
	$join->chave = 'id';
	$join->valor = 'nome';
	$join->tipo = 'select';
	$join->order_by = 'nome';
$field->join = $join;
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Título';
$field->coluna = 'titulo';
$field->pk = false;
$field->isnull = false;
$field->add = false;
$field->valida = false;
$field->edit = false;
$field->view = false;
$field->lista = false;
$field->filter = false;
$field->order = false;
$field->type = 'VARCHAR(255)';
$field->interaction = '';
$field->tipo = 'text';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Status';
$field->coluna = 'status';
$field->pk = false;
$field->isnull = false;
$field->add = true;
$field->valida = false;
$field->edit = true;
$field->view = true;
$field->lista = true;
$field->filter = true;
$field->order = true;
$field->type = 'VARCHAR(255)';
$field->interaction = '';
$field->tipo = 'select';
$field->valores = array(
	'publicado' => 'Publicado',
	'rascunho' => 'Rascunho',
);
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Settings';
$field->coluna = 'settings';
$field->pk = false;
$field->isnull = false;
$field->add = false;
$field->valida = false;
$field->edit = false;
$field->view = false;
$field->lista = false;
$field->filter = false;
$field->order = false;
$field->type = 'TEXT';
$field->interaction = '';
$field->tipo = 'textarea';
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

/* FUNÇÕES AUXILIARES =============================================================================================== */

if(!function_exists('dbo_slider_slide_pre_insert'))
{
	function dbo_slider_slide_pre_insert () // there is nothing to get as a parameter, right?
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('dbo_slider_slide_pos_insert'))
{
	function dbo_slider_slide_pos_insert ($obj) // active just inserted object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('dbo_slider_slide_pre_update'))
{
	function dbo_slider_slide_pre_update ($obj) // active object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('dbo_slider_slide_pos_update'))
{
	function dbo_slider_slide_pos_update ($obj) // active updated object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('dbo_slider_slide_pre_delete'))
{
	function dbo_slider_slide_pre_delete ($obj) // active object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('dbo_slider_slide_pos_delete'))
{
	function dbo_slider_slide_pos_delete ($obj) // active deleted object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('dbo_slider_slide_pre_list'))
{
	function dbo_slider_slide_pre_list () // nothing to be passed here...
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('dbo_slider_slide_pos_list'))
{
	function dbo_slider_slide_pos_list ($ids) // ids of the listed elements
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('dbo_slider_slide_notifications'))
{
	function dbo_slider_slide_notifications ($type = '')
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('dbo_slider_slide_overview'))
{
	function dbo_slider_slide_overview ($foo)
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}


?>