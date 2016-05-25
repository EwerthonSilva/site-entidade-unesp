<?

/* ================================================================================================================== */
/* DBO DEFINITION FILE FOR MODULE 'grupo' ======================================= AUTO-CREATED ON 27/04/2016 11:25:52 */
/* ================================================================================================================== */



/* GENERAL MODULE DEFINITIONS ======================================================================================= */

$module = new Obj();
$module->modulo = 'grupo';
$module->tabela = 'main.grupo';
$module->titulo = 'Grupo de pessoa';
$module->titulo_plural = 'Grupos de pessoas';
$module->genero = 'o';
$module->paginacao = '20';
$module->update = true;
$module->delete = true;
$module->insert = 'Novo Grupo de pessoa';
$module->preload_insert_form = true;
$module->auto_view = true;
$module->ignore_permissions = false;
$module->order_by = '10';

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
$field->type = 'VARCHAR(255)';
$field->interaction = '';
$field->tipo = 'text';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Pessoa';
$field->coluna = 'pessoa';
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
$field->tipo = 'joinNN';
	$join = new Obj();
	$join->modulo = 'pessoa';
	$join->chave = 'id';
	$join->valor = 'nome';
	$join->select2 = true;
	$join->tabela_ligacao = 'main.pessoa_grupo';
	$join->chave1 = 'grupo';
	$join->chave2 = 'pessoa';
	$join->tamanho_minimo = '3';
	$join->tipo = 'select';
	$join->order_by = 'nome';
$field->join = $join;
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Sistemas restritos permitidos';
$field->coluna = 'grupo_sistema_permitido';
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
$field->tipo = 'joinNN';
	$join = new Obj();
	$join->modulo = 'sistema';
	$join->chave = 'id';
	$join->valor = 'sigla';
	$join->select2 = true;
	$join->tabela_ligacao = 'main.grupo_sistema_permitido';
	$join->chave1 = 'grupo';
	$join->chave2 = 'sistema';
	$join->tipo = 'select';
	$join->order_by = 'sigla';
$field->join = $join;
$field->restricao = '
	$rest = "WHERE acessibilidade = \'Restrito\'";
';
$module->campo[$field->coluna] = $field;

/*==========================================*/

/* GRID FOR THE FORM LAYOUT ========================================================================================= */

$grid = array();


$module->grid = $grid;

/* MODULE LIST BUTTONS ============================================================================================== */

/* FUNÇÕES AUXILIARES =============================================================================================== */

if(!function_exists('grupo_pre_insert'))
{
	function grupo_pre_insert () // there is nothing to get as a parameter, right?
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('grupo_pos_insert'))
{
	function grupo_pos_insert ($obj) // active just inserted object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('grupo_pre_update'))
{
	function grupo_pre_update ($obj) // active object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('grupo_pos_update'))
{
	function grupo_pos_update ($obj) // active updated object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('grupo_pre_delete'))
{
	function grupo_pre_delete ($obj) // active object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('grupo_pos_delete'))
{
	function grupo_pos_delete ($obj) // active deleted object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('grupo_pre_list'))
{
	function grupo_pre_list () // nothing to be passed here...
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('grupo_pos_list'))
{
	function grupo_pos_list ($ids) // ids of the listed elements
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('grupo_notifications'))
{
	function grupo_notifications ($type = '')
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('grupo_overview'))
{
	function grupo_overview ($foo)
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}


?>