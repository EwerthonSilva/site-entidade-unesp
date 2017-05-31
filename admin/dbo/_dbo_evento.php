<?

/* ================================================================================================================== */
/* DBO DEFINITION FILE FOR MODULE 'evento' ====================================== AUTO-CREATED ON 30/11/2016 09:55:29 */
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
$field->titulo = 'Imagem de Fundo';
$field->coluna = 'background_image';
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
$field->tipo = 'image';
	$image = new Obj();
	$image->width = 1900;
	$image->height = 1080;
	$image->prefix = '';
	$image->quality = 75;
$field->image[] = $image;
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
$field->tipo = 'content-tools';
$field->params = array(
	'template' => 'content-tools-blank',
);
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Instruções de Pagamento';
$field->coluna = 'instrucao_pagamento';
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
$field->titulo = 'Inicio das inscrições';
$field->coluna = 'data_insc_i';
$field->pk = false;
$field->isnull = true;
$field->add = true;
$field->valida = false;
$field->edit = true;
$field->view = true;
$field->lista = true;
$field->filter = true;
$field->order = true;
$field->type = 'DATETIME';
$field->interaction = '';
$field->classes = 'datetimepick';
$field->tipo = 'datetime';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Fim das inscrições';
$field->coluna = 'data_insc_f';
$field->pk = false;
$field->isnull = true;
$field->add = true;
$field->valida = false;
$field->edit = true;
$field->view = true;
$field->lista = true;
$field->filter = true;
$field->order = true;
$field->type = 'DATETIME';
$field->interaction = '';
$field->classes = 'datetimepick';
$field->tipo = 'datetime';
$module->campo[$field->coluna] = $field;

/*==========================================*/

/* GRID FOR THE FORM LAYOUT ========================================================================================= */

$grid = array();

$grid[] = array('6.no-margin', '6');
$grid[] = array('12');
$grid[] = array('12');
$grid[] = array('4','4.end');

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
	$code = "<a target=\'_blank\' class=\'button tiny no-margin radius\' href=\'../inscricoes.php?evento=".$id."\'>Formulário</a>";
';
$module->button[] = $button;

$button = new Obj();
$button->value = 'Total de Inscritos';
$button->custom = true;
$button->code = '
	$code = "<a rel=\'modal\' data-width=\'100%\' target=\'_blank\' class=\'button tiny no-margin radius nowrap\' href=\'total-inscritos.php?evento=".$id."&dbo_modal=1\'>Total de Inscritos</a>";
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
