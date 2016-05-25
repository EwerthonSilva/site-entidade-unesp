<?

/* ================================================================================================================== */
/* DBO DEFINITION FILE FOR MODULE 'pessoa_view' ================================= AUTO-CREATED ON 27/04/2016 11:25:52 */
/* ================================================================================================================== */



/* GENERAL MODULE DEFINITIONS ======================================================================================= */

$module = new Obj();
$module->modulo = 'pessoa_view';
$module->tabela = 'main.pessoa';
$module->titulo = 'Pessoa';
$module->titulo_plural = 'Pessoas';
$module->genero = 'a';
$module->paginacao = '20';
$module->update = true;
$module->delete = true;
$module->insert = 'Nova Pessoa';
$module->preload_insert_form = true;
$module->auto_view = false;
$module->ignore_permissions = false;
$module->order_by = '1';

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
$field->interaction = 'readonly';
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
$field->interaction = 'readonly';
$field->tipo = 'text';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Apelido';
$field->coluna = 'apelido';
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
$field->interaction = 'readonly';
$field->tipo = 'text';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Perfil';
$field->coluna = 'perfil';
$field->dica = 'Perfil de acesso ao sistema, pode-se usar mais de 1';
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
$field->tipo = 'joinNN';
	$join = new Obj();
	$join->modulo = 'perfil';
	$join->chave = 'id';
	$join->valor = 'nome';
	$join->select2 = true;
	$join->tabela_ligacao = 'pessoa_perfil';
	$join->chave1 = 'pessoa';
	$join->chave2 = 'perfil';
	$join->tipo = 'select';
	$join->order_by = 'id';
$field->join = $join;
$module->campo[$field->coluna] = $field;

/*==========================================*/

/* GRID FOR THE FORM LAYOUT ========================================================================================= */

$grid = array();

$grid[] = array('8','4');
$grid[] = array('12');

$module->grid = $grid;

/* MODULE LIST BUTTONS ============================================================================================== */

/* FUNÇÕES AUXILIARES =============================================================================================== */

if(!function_exists('pessoa_view_pre_insert'))
{
	function pessoa_view_pre_insert () // there is nothing to get as a parameter, right?
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pessoa_view_pos_insert'))
{
	function pessoa_view_pos_insert ($obj) // active just inserted object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pessoa_view_pre_update'))
{
	function pessoa_view_pre_update ($obj) // active object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pessoa_view_pos_update'))
{
	function pessoa_view_pos_update ($obj) // active updated object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pessoa_view_pre_delete'))
{
	function pessoa_view_pre_delete ($obj) // active object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pessoa_view_pos_delete'))
{
	function pessoa_view_pos_delete ($obj) // active deleted object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pessoa_view_pre_list'))
{
	function pessoa_view_pre_list () // nothing to be passed here...
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pessoa_view_pos_list'))
{
	function pessoa_view_pos_list ($ids) // ids of the listed elements
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pessoa_view_notifications'))
{
	function pessoa_view_notifications ($type = '')
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pessoa_view_overview'))
{
	function pessoa_view_overview ($foo)
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}


?>