<?

/* ================================================================================================================== */
/* DBO DEFINITION FILE FOR MODULE 'perfil' ====================================== AUTO-CREATED ON 19/08/2015 11:03:12 */
/* ================================================================================================================== */



/* GENERAL MODULE DEFINITIONS ======================================================================================= */

$module = new Obj();
$module->modulo = 'perfil';
$module->tabela = 'perfil';
$module->titulo = 'Perfil';
$module->titulo_plural = 'Perfis';
$module->genero = 'o';
$module->paginacao = '10';
$module->update = true;
$module->delete = true;
$module->insert = 'Novo Perfil';
$module->preload_insert_form = false;
$module->auto_view = false;
$module->restricao = '
	if(!pessoaHasPerfil($_SESSION[\'user_id\'], \'Desenv\')) $rest = "WHERE dbo_flag_desenv = 0";
';
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
$field->titulo = 'Nome';
$field->coluna = 'nome';
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
$field->tipo = 'text';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Permissão';
$field->coluna = 'permissao';
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
$field->tipo = 'text';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Pessoa';
$field->coluna = 'pessoa';
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
	$join->modulo = 'pessoa';
	$join->chave = 'id';
	$join->valor = 'nome';
	$join->ajax = true;
	$join->select2 = true;
	$join->tabela_ligacao = 'pessoa_perfil';
	$join->chave1 = 'perfil';
	$join->chave2 = 'pessoa';
	$join->tamanho_minimo = '3';
	$join->tipo = 'select';
	$join->order_by = 'id';
$field->join = $join;
$field->restricao = '
	if(!pessoaHasPerfil($_SESSION[\'user_id\'], \'Desenv\')) $rest = "WHERE dbo_flag_desenv = 0";
';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Grupos';
$field->coluna = 'grupo';
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
	$join->modulo = 'grupo';
	$join->chave = 'id';
	$join->valor = 'nome';
	$join->select2 = true;
	$join->tabela_ligacao = 'grupo_perfil';
	$join->chave1 = 'perfil';
	$join->chave2 = 'grupo';
	$join->tipo = 'select';
	$join->order_by = 'nome';
$field->join = $join;
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Restrito ao desenvolvimento';
$field->coluna = 'dbo_flag_desenv';
$field->perfil = array('Desenv');
$field->pk = false;
$field->isnull = false;
$field->add = true;
$field->valida = false;
$field->edit = true;
$field->view = true;
$field->lista = false;
$field->filter = false;
$field->order = false;
$field->type = 'INT';
$field->interaction = '';
$field->tipo = 'radio';
$field->valores = array(
	'0' => 'não',
	'1' => 'sim',
);
$module->campo[$field->coluna] = $field;

/*==========================================*/

/* GRID FOR THE FORM LAYOUT ========================================================================================= */

$grid = array();


$module->grid = $grid;

/* MODULE LIST BUTTONS ============================================================================================== */

$button = new Obj();
$button->value = 'Permissões';
$button->custom = true;
$button->code = '
	$code = "<a class=\'button tiny no-margin radius\' href=\'dbo_permissions.php?perfil=$id\'>[VALUE]</a>";
';
$module->button[] = $button;

/* FUNÇÕES AUXILIARES =============================================================================================== */

if(!function_exists('perfil_pre_insert'))
{
	function perfil_pre_insert () // there is nothing to get as a parameter, right?
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('perfil_pos_insert'))
{
	function perfil_pos_insert ($obj) // active just inserted object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('perfil_pre_update'))
{
	function perfil_pre_update ($obj) // active object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('perfil_pos_update'))
{
	function perfil_pos_update ($obj) // active updated object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('perfil_pre_delete'))
{
	function perfil_pre_delete ($obj) // active object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('perfil_pos_delete'))
{
	function perfil_pos_delete ($obj) // active deleted object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('perfil_pre_list'))
{
	function perfil_pre_list () // nothing to be passed here...
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('perfil_pos_list'))
{
	function perfil_pos_list ($ids) // ids of the listed elements
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('perfil_notifications'))
{
	function perfil_notifications ($type = '')
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('perfil_overview'))
{
	function perfil_overview ($foo)
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}


?>