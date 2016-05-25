<?

/* ================================================================================================================== */
/* DBO DEFINITION FILE FOR MODULE 'evento' ====================================== AUTO-CREATED ON 04/04/2016 15:06:40 */
/* ================================================================================================================== */



/* GENERAL MODULE DEFINITIONS ======================================================================================= */

$module = new Obj();
$module->modulo = 'evento';
$module->tabela = 'evento';
$module->titulo = 'Evento';
$module->titulo_plural = 'Eventos';
$module->classes_listagem = 'almost full';
$module->genero = 'o';
$module->paginacao = '20';
$module->update = true;
$module->delete = true;
$module->insert = 'Novo Evento';
$module->preload_insert_form = true;
$module->auto_view = false;
$module->ignore_permissions = false;
$module->order_by = '3';

/* FIELDS =========================================================================================================== */

$field = new Obj();
$field->titulo = 'Id';
$field->coluna = 'id';
$field->default = 'ASC';
$field->pk = true;
$field->isnull = false;
$field->add = false;
$field->valida = false;
$field->edit = false;
$field->view = false;
$field->lista = true;
$field->filter = true;
$field->order = true;
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
$field->rows = 5;
$module->campo[$field->coluna] = $field;

/*==========================================*/

/* GRID FOR THE FORM LAYOUT ========================================================================================= */

$grid = array();


$module->grid = $grid;

/* MODULE LIST BUTTONS ============================================================================================== */

$button = new Obj();
$button->value = 'Atividades';
$button->modulo = 'palestra';
$button->modulo_fk = 'evento';
$button->key = 'id';
$button->view = false;
$button->show = true;
$button->subsection = false;
$button->autoload = false;
$module->button[] = $button;

$button = new Obj();
$button->value = 'Formulário';
$button->custom = true;
$button->code = '
	$code = "<a target=\'_blank\' class=\'button-dbo-fixo\' href=\'http://www.fcfar.unesp.br/allpharmajr/inscricoes.php?evento=".$id."\'>Formulário</a>";
';
$module->button[] = $button;

$button = new Obj();
$button->value = 'Total de Inscritos';
$button->custom = true;
$button->code = '
	$code = "<a target=\'_blank\' class=\'button-dbo-fixo\' href=\'http://www.fcfar.unesp.br/allpharmajr/admin/total-inscritos.php?evento=".$id."\'>Total de Inscritos</a>";
';
$module->button[] = $button;

/* FUNÇÕES AUXILIARES =============================================================================================== */

if(!function_exists('evento_pre_insert'))
{
	function evento_pre_insert () // there is nothing to get as a parameter, right?
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('evento_pos_insert'))
{
	function evento_pos_insert ($obj) // active just inserted object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('evento_pre_update'))
{
	function evento_pre_update ($obj) // active object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('evento_pos_update'))
{
	function evento_pos_update ($obj) // active updated object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('evento_pre_delete'))
{
	function evento_pre_delete ($obj) // active object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('evento_pos_delete'))
{
	function evento_pos_delete ($obj) // active deleted object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('evento_pre_list'))
{
	function evento_pre_list () // nothing to be passed here...
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('evento_pos_list'))
{
	function evento_pos_list ($ids) // ids of the listed elements
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('evento_notifications'))
{
	function evento_notifications ($type = '')
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('evento_overview'))
{
	function evento_overview ($foo)
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}


?>