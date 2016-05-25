<?

/* ================================================================================================================== */
/* DBO DEFINITION FILE FOR MODULE 'disciplina' ================================== AUTO-CREATED ON 22/05/2015 12:31:46 */
/* ================================================================================================================== */



/* GENERAL MODULE DEFINITIONS ======================================================================================= */

$module = new Obj();
$module->modulo = 'disciplina';
$module->tabela = 'disciplina';
$module->titulo = 'Disciplina';
$module->titulo_plural = 'Disciplinas';
$module->genero = 'a';
$module->paginacao = '20';
$module->update = true;
$module->delete = true;
$module->insert = 'Nova Disciplina';
$module->preload_insert_form = true;
$module->auto_view = true;
$module->order_by = '12';

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
$field->titulo = 'Nome';
$field->coluna = 'nome';
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
$field->tipo = 'text';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Abreviatura';
$field->coluna = 'abreviatura';
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
$field->tipo = 'text';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Sigla';
$field->coluna = 'sigla';
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
$field->tipo = 'text';
$module->campo[$field->coluna] = $field;

/*==========================================*/

/* GRID FOR THE FORM LAYOUT ========================================================================================= */

$grid = array();


$module->grid = $grid;

/* MODULE LIST BUTTONS ============================================================================================== */

/* FUNÇÕES AUXILIARES =============================================================================================== */

if(!function_exists('disciplina_pre_insert'))
{
	function disciplina_pre_insert () // there is nothing to get as a parameter, right?
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('disciplina_pos_insert'))
{
	function disciplina_pos_insert ($obj) // active just inserted object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('disciplina_pre_update'))
{
	function disciplina_pre_update ($obj) // active object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('disciplina_pos_update'))
{
	function disciplina_pos_update ($obj) // active updated object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('disciplina_pre_delete'))
{
	function disciplina_pre_delete ($obj) // active object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('disciplina_pos_delete'))
{
	function disciplina_pos_delete ($obj) // active deleted object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('disciplina_pre_list'))
{
	function disciplina_pre_list () // nothing to be passed here...
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('disciplina_pos_list'))
{
	function disciplina_pos_list ($ids) // ids of the listed elements
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('disciplina_notifications'))
{
	function disciplina_notifications ($type = '')
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('disciplina_overview'))
{
	function disciplina_overview ($foo)
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}


?>