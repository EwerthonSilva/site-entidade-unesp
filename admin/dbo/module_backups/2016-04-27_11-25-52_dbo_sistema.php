<?

/* ================================================================================================================== */
/* DBO DEFINITION FILE FOR MODULE 'sistema' ===================================== AUTO-CREATED ON 27/04/2016 11:25:50 */
/* ================================================================================================================== */



/* GENERAL MODULE DEFINITIONS ======================================================================================= */

$module = new Obj();
$module->modulo = 'sistema';
$module->tabela = 'main.sistema';
$module->titulo = 'Sistema';
$module->titulo_plural = 'Sistemas';
$module->module_icon = 'desktop';
$module->genero = 'o';
$module->paginacao = '20';
$module->update = true;
$module->delete = true;
$module->insert = 'Novo Sistema';
$module->preload_insert_form = false;
$module->auto_view = false;
$module->ignore_permissions = false;
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
$field->titulo = 'Cor do sistema';
$field->coluna = 'cor';
$field->pk = false;
$field->isnull = false;
$field->add = true;
$field->valida = false;
$field->edit = true;
$field->view = true;
$field->lista = true;
$field->filter = false;
$field->order = true;
$field->type = 'VARCHAR(255)';
$field->interaction = '';
$field->list_function = 'listSistema';
$field->tipo = 'text';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Slug do sistema';
$field->coluna = 'slug';
$field->dica = 'Utilizada para ícones e notificações';
$field->pk = false;
$field->isnull = false;
$field->add = true;
$field->valida = false;
$field->edit = true;
$field->view = true;
$field->lista = false;
$field->filter = true;
$field->order = false;
$field->type = 'VARCHAR(150)';
$field->interaction = '';
$field->tipo = 'text';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Sigla do Sistema';
$field->coluna = 'sigla';
$field->titulo_listagem = 'Sigla';
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
$field->titulo = 'Acessibilidade';
$field->coluna = 'acessibilidade';
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
$field->list_function = 'listSistema';
$field->tipo = 'select';
$field->valores = array(
	'Público' => 'Público',
	'Privado' => 'Privado',
	'Restrito' => 'Restrito',
);
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Integrado?';
$field->coluna = 'integrado';
$field->pk = false;
$field->isnull = false;
$field->add = true;
$field->valida = true;
$field->edit = true;
$field->view = true;
$field->lista = true;
$field->filter = true;
$field->order = true;
$field->type = 'INT';
$field->interaction = '';
$field->list_function = 'listSistema';
$field->tipo = 'radio';
$field->valores = array(
	'0' => 'não',
	'1' => 'sim',
);
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Notification engine?';
$field->coluna = 'notification_engine';
$field->pk = false;
$field->isnull = false;
$field->add = true;
$field->valida = true;
$field->edit = true;
$field->view = true;
$field->lista = true;
$field->filter = true;
$field->order = true;
$field->type = 'INT';
$field->interaction = '';
$field->list_function = 'listSistema';
$field->tipo = 'radio';
$field->valores = array(
	'0' => 'não',
	'1' => 'sim',
);
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Nome do Sistema';
$field->coluna = 'nome';
$field->pk = false;
$field->isnull = false;
$field->add = true;
$field->valida = true;
$field->edit = true;
$field->view = true;
$field->lista = false;
$field->filter = true;
$field->order = false;
$field->type = 'VARCHAR(255)';
$field->interaction = '';
$field->tipo = 'text';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Texto do ícone';
$field->coluna = 'texto_icone';
$field->pk = false;
$field->isnull = false;
$field->add = true;
$field->valida = true;
$field->edit = true;
$field->view = true;
$field->lista = false;
$field->filter = true;
$field->order = false;
$field->type = 'VARCHAR(255)';
$field->interaction = '';
$field->tipo = 'text';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Path para a pasta DBO';
$field->coluna = 'dbo_path';
$field->dica = 'sem a "/" no final';
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
$field->tipo = 'text';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'URL do sistema';
$field->coluna = 'url';
$field->pk = false;
$field->isnull = false;
$field->add = true;
$field->valida = true;
$field->edit = true;
$field->view = true;
$field->lista = false;
$field->filter = true;
$field->order = false;
$field->type = 'VARCHAR(255)';
$field->interaction = '';
$field->tipo = 'text';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Código SVG do ícone (beta)';
$field->coluna = 'svg_code';
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
$field->tipo = 'text';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Ícone (FontAwesome)';
$field->coluna = 'icone_fa';
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
$field->tipo = 'text';
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

$field = new Obj();
$field->titulo = 'Categorizado nos grupos de acessos:';
$field->coluna = 'gruposistema';
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
	$join->modulo = 'gruposistema';
	$join->chave = 'id';
	$join->valor = 'nome';
	$join->select2 = true;
	$join->tabela_ligacao = 'main.sistema_gruposistema';
	$join->chave1 = 'sistema';
	$join->chave2 = 'gruposistema';
	$join->tipo = 'select';
	$join->order_by = 'nome';
$field->join = $join;
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Acessível para os grupos de pessoas:';
$field->coluna = 'grupo_sistema_permitido';
$field->dica = 'Somente para sistemas de acessibilidade restrita';
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
	$join->tabela_ligacao = 'main.grupo_sistema_permitido';
	$join->chave1 = 'sistema';
	$join->chave2 = 'grupo';
	$join->tamanho_minimo = '1';
	$join->tipo = 'select';
	$join->order_by = 'nome';
$field->join = $join;
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Acessível para as pessoas:';
$field->coluna = 'pessoa_sistema_permitido';
$field->dica = 'Somente para sistemas de acessibilidade restrita';
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
	$join->tabela_ligacao = 'main.pessoa_sistema_permitido';
	$join->chave1 = 'sistema';
	$join->chave2 = 'pessoa';
	$join->tamanho_minimo = '3';
	$join->tipo = 'select';
	$join->order_by = 'nome';
$field->join = $join;
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Favorito para as pessoas:';
$field->coluna = 'pessoa_sistema_favorito';
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
	$join->tabela_ligacao = 'main.pessoa_sistema_favorito';
	$join->chave1 = 'sistema';
	$join->chave2 = 'pessoa';
	$join->tamanho_minimo = '3';
	$join->tipo = 'select';
	$join->order_by = 'nome';
$field->join = $join;
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Inativo';
$field->coluna = 'inativo';
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

$grid[] = array('4','4','4');
$grid[] = array('6','3','3');
$grid[] = array('12');
$grid[] = array('6','6');
$grid[] = array('12');
$grid[] = array('12');
$grid[] = array('4');
$grid[] = array('Configurações de acessibilidade');
$grid[] = array('6','6');
$grid[] = array('6','6');

$module->grid = $grid;

/* MODULE LIST BUTTONS ============================================================================================== */

/* FUNÇÕES AUXILIARES =============================================================================================== */

if(!function_exists('sistema_pre_insert'))
{
	function sistema_pre_insert () // there is nothing to get as a parameter, right?
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('sistema_pos_insert'))
{
	function sistema_pos_insert ($obj) // active just inserted object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('sistema_pre_update'))
{
	function sistema_pre_update ($obj) // active object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('sistema_pos_update'))
{
	function sistema_pos_update ($obj) // active updated object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('sistema_pre_delete'))
{
	function sistema_pre_delete ($obj) // active object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('sistema_pos_delete'))
{
	function sistema_pos_delete ($obj) // active deleted object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('sistema_pre_list'))
{
	function sistema_pre_list () // nothing to be passed here...
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('sistema_pos_list'))
{
	function sistema_pos_list ($ids) // ids of the listed elements
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('sistema_notifications'))
{
	function sistema_notifications ($type = '')
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('sistema_overview'))
{
	function sistema_overview ($foo)
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}


?>