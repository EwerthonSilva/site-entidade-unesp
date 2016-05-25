<?

/* ================================================================================================================== */
/* DBO DEFINITION FILE FOR MODULE 'arquivo_morto' =============================== AUTO-CREATED ON 21/05/2015 08:25:30 */
/* ================================================================================================================== */



/* GENERAL MODULE DEFINITIONS ======================================================================================= */

$module = new Obj();
$module->modulo = 'arquivo_morto';
$module->tabela = 'arquivo_morto';
$module->titulo = 'Arquivo morto';
$module->titulo_plural = 'Arquivos mortos';
$module->genero = 'o';
$module->paginacao = '20';
$module->update = true;
$module->delete = true;
$module->insert = 'Novo Arquivo morto';
$module->preload_insert_form = true;
$module->auto_view = true;
$module->order_by = '11';

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
$field->titulo = 'Ano';
$field->coluna = 'ano';
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
$field->titulo = 'Modalidade';
$field->coluna = 'moda';
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
$field->tipo = 'select';
$field->valores = array(
	'Alimentos' => 'Alimentos',
	'Análises Clínicas e Toxicológicas' => 'Análises Clínicas e Toxicológicas',
	'Engenharia de Bioprocessos e Biotecnologia' => 'Engenharia de Bioprocessos e Biotecnologia',
	'Farmácia' => 'Farmácia',
	'Farmácia Bioquímica' => 'Farmácia Bioquímica',
	'Farmacos e Medicamentos' => 'Farmacos e Medicamentos',
	'Generalista' => 'Generalista',
	'Industrial' => 'Industrial',
	'Laboratório Clínico e Saúde Pública' => 'Laboratório Clínico e Saúde Pública',
);
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Caixa';
$field->coluna = 'caixa';
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

/* GRID FOR THE FORM LAYOUT ========================================================================================= */

$grid = array();


$module->grid = $grid;

/* MODULE LIST BUTTONS ============================================================================================== */

/* FUNÇÕES AUXILIARES =============================================================================================== */

if(!function_exists('arquivo_morto_pre_insert'))
{
	function arquivo_morto_pre_insert () // there is nothing to get as a parameter, right?
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('arquivo_morto_pos_insert'))
{
	function arquivo_morto_pos_insert ($obj) // active just inserted object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('arquivo_morto_pre_update'))
{
	function arquivo_morto_pre_update ($obj) // active object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('arquivo_morto_pos_update'))
{
	function arquivo_morto_pos_update ($obj) // active updated object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('arquivo_morto_pre_delete'))
{
	function arquivo_morto_pre_delete ($obj) // active object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('arquivo_morto_pos_delete'))
{
	function arquivo_morto_pos_delete ($obj) // active deleted object
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('arquivo_morto_pre_list'))
{
	function arquivo_morto_pre_list () // nothing to be passed here...
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('arquivo_morto_pos_list'))
{
	function arquivo_morto_pos_list ($ids) // ids of the listed elements
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('arquivo_morto_notifications'))
{
	function arquivo_morto_notifications ($type = '')
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}

if(!function_exists('arquivo_morto_overview'))
{
	function arquivo_morto_overview ($foo)
	{ global $dbo;
	// ----------------------------------------------------------------------------------------------------------



	// ----------------------------------------------------------------------------------------------------------
	}
}


?>