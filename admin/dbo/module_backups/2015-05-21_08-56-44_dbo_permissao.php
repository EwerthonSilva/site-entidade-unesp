<?

/* ================================================================================================================== */
/* DBO DEFINITION FILE FOR MODULE 'permissao' =================================== AUTO-CREATED ON 30/09/2011 11:32:16 */
/* ================================================================================================================== */



/* GENERAL MODULE DEFINITIONS ======================================================================================= */

$module = new Obj();
$module->modulo = 'permissao';
$module->tabela = 'permissao';
$module->titulo = 'Permissão Custom';
$module->titulo_plural = 'Permissões Custom';
$module->genero = 'a';
$module->paginacao = '10';
$module->update = true;
$module->delete = true;
$module->insert = 'Nova Permissão Custom';
$module->preload_insert_form = false;
$module->order_by = '2';

/* FIELDS =========================================================================================================== */

$field = new Obj();
$field->titulo = 'Id';
$field->coluna = 'id';
$field->pk = true;
$field->add = false;
$field->valida = false;
$field->edit = false;
$field->view = false;
$field->lista = false;
$field->filter = false;
$field->order = false;
$field->type = 'INT NOT NULL auto_increment';
$field->tipo = 'pk';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Nome';
$field->coluna = 'nome';
$field->pk = false;
$field->add = true;
$field->valida = false;
$field->edit = true;
$field->view = true;
$field->lista = true;
$field->filter = false;
$field->order = false;
$field->type = 'VARCHAR(255)';
$field->tipo = 'text';
$module->campo[$field->coluna] = $field;

/*==========================================*/

/* GRID FOR THE FORM LAYOUT ========================================================================================= */

/* MODULE LIST BUTTONS ============================================================================================== */

/* FUNÇÕES AUXILIARES =============================================================================================== */

if(!function_exists('permissao_pre_insert'))
{
	function permissao_pre_insert () // there is nothing to get as a parameter, right?
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('permissao_pos_insert'))
{
	function permissao_pos_insert ($id) // id of the just inserted element
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('permissao_pre_update'))
{
	function permissao_pre_update ($id) // id of the active element
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('permissao_pos_update'))
{
	function permissao_pos_update ($id) // id of the active element
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('permissao_pre_delete'))
{
	function permissao_pre_delete ($id) // id of the active element
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('permissao_pos_delete'))
{
	function permissao_pos_delete ($id) // id of the active element
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('permissao_pre_list'))
{
	function permissao_pre_list () // nothing to be passed here...
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('permissao_pos_list'))
{
	function permissao_pos_list ($ids) // ids of the listed elements
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}


?>