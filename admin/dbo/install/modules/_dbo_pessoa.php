<?

/* ================================================================================================================== */
/* DBO DEFINITION FILE FOR MODULE 'pessoa' ====================================== AUTO-CREATED ON 16/08/2015 03:02:01 */
/* ================================================================================================================== */



/* GENERAL MODULE DEFINITIONS ======================================================================================= */

$module = new Obj();
$module->modulo = 'pessoa';
$module->tabela = 'pessoa';
$module->titulo = 'Pessoa';
$module->titulo_plural = 'Pessoas';
$module->module_icon = 'user';
$module->genero = 'a';
$module->paginacao = '10';
$module->update = true;
$module->delete = true;
$module->insert = 'Nova Pessoa';
$module->preload_insert_form = true;
$module->auto_view = false;
$module->permissoes_custom = '
	painel-cadastros | Permissão que dá acesso ao painel principal de cadastros do site
';
$module->restricao = '
	if(!pessoaHasPerfil($_SESSION[\'user_id\'], \'Desenv\')) $rest = "WHERE dbo_flag_desenv = 0";
';
$module->order_by = '2';

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
$field->titulo = 'Foto';
$field->coluna = 'foto';
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
$field->tipo = 'image';
	$image = new Obj();
	$image->width = 1200;
	$image->height = 1200;
	$image->prefix = '';
	$image->quality = 80;
$field->image[] = $image;
	$image = new Obj();
	$image->width = 400;
	$image->height = 400;
	$image->prefix = 'small-';
	$image->quality = 80;
$field->image[] = $image;
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
$field->titulo = 'E-mail';
$field->coluna = 'email';
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
$field->titulo = 'Usuário';
$field->coluna = 'user';
$field->dica = 'Usuário de acesso ao sistema';
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
$field->titulo = 'Senha';
$field->coluna = 'pass';
$field->pk = false;
$field->isnull = false;
$field->add = true;
$field->valida = false;
$field->edit = true;
$field->view = false;
$field->lista = false;
$field->filter = false;
$field->order = false;
$field->type = 'VARCHAR(255)';
$field->interaction = '';
$field->tipo = 'password';
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
	$join->ajax = true;
	$join->select2 = true;
	$join->tabela_ligacao = 'pessoa_perfil';
	$join->chave1 = 'pessoa';
	$join->chave2 = 'perfil';
	$join->tipo = 'select';
	$join->order_by = 'id';
$field->join = $join;
$field->restricao = '
	if(!pessoaHasPerfil($_SESSION[\'user_id\'], \'Desenv\')) $rest = "WHERE dbo_flag_desenv = 0";
';
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
$field->tipo = 'textarea';
$field->rows = 6;
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

$grid[] = array('12');
$grid[] = array('12');
$grid[] = array('4','4','4');
$grid[] = array('12');
$grid[] = array('12');
$grid[] = array('12');

$module->grid = $grid;

/* MODULE LIST BUTTONS ============================================================================================== */

/* FUNÇÕES AUXILIARES =============================================================================================== */

if(!function_exists('pessoa_pre_insert'))
{
	function pessoa_pre_insert () // there is nothing to get as a parameter, right?
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pessoa_pos_insert'))
{
	function pessoa_pos_insert ($obj) // active just inserted object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pessoa_pre_update'))
{
	function pessoa_pre_update ($obj) // active object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pessoa_pos_update'))
{
	function pessoa_pos_update ($obj) // active updated object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pessoa_pre_delete'))
{
	function pessoa_pre_delete ($obj) // active object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pessoa_pos_delete'))
{
	function pessoa_pos_delete ($obj) // active deleted object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pessoa_pre_list'))
{
	function pessoa_pre_list () // nothing to be passed here...
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pessoa_pos_list'))
{
	function pessoa_pos_list ($ids) // ids of the listed elements
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pessoa_notifications'))
{
	function pessoa_notifications ($type = '')
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('pessoa_overview'))
{
	function pessoa_overview ($foo)
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}


?>