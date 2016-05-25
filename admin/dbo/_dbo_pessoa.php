<?

/* ================================================================================================================== */
/* DBO DEFINITION FILE FOR MODULE 'pessoa' ====================================== AUTO-CREATED ON 27/04/2016 11:25:52 */
/* ================================================================================================================== */



/* GENERAL MODULE DEFINITIONS ======================================================================================= */

$module = new Obj();
$module->modulo = 'pessoa';
$module->tabela = 'main.pessoa';
$module->titulo = 'Pessoa';
$module->titulo_plural = 'Pessoas';
$module->module_icon = 'user';
$module->genero = 'a';
$module->paginacao = '10';
$module->update = true;
$module->delete = true;
$module->insert = 'Nova Pessoa';
$module->preload_insert_form = false;
$module->auto_view = false;
$module->ignore_permissions = false;
$module->order_by = '0';

/* FIELDS =========================================================================================================== */

$field = new Obj();
$field->titulo = 'Senha';
$field->coluna = 'pass';
$field->pk = false;
$field->isnull = false;
$field->add = false;
$field->valida = false;
$field->edit = false;
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
$field->titulo = 'Modificado Por';
$field->coluna = 'perfil_updated_by';
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
$field->tipo = 'join';
	$join = new Obj();
	$join->modulo = 'pessoa';
	$join->chave = 'id';
	$join->valor = 'nome';
	$join->tipo = 'select';
	$join->order_by = 'nome';
$field->join = $join;
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Modificado Em';
$field->coluna = 'perfil_updated_on';
$field->pk = false;
$field->isnull = true;
$field->add = false;
$field->valida = false;
$field->edit = false;
$field->view = false;
$field->lista = false;
$field->filter = false;
$field->order = false;
$field->type = 'DATETIME';
$field->interaction = '';
$field->tipo = 'datetime';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Id';
$field->coluna = 'id';
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
$field->titulo = 'Foto';
$field->coluna = 'foto';
$field->pk = false;
$field->isnull = false;
$field->add = true;
$field->valida = false;
$field->edit = true;
$field->view = true;
$field->lista = true;
$field->filter = false;
$field->order = true;
$field->type = 'TEXT';
$field->interaction = '';
$field->tipo = 'plugin';
	$plugin = new Obj();
	$plugin->name = 'jcrop_dbo';
	$plugin->params = array(
		'cw1' => '400',
		'ch1' => '400',
		'cd1' => 'Selecione uma foto de ROSTO',
		'cw2' => '460',
		'ch2' => '360',
		'cd2' => 'Selecione um recorte de BUSTO',
		'list' => '50',
		'keep_original' => true,
	);
$field->plugin = $plugin;
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Tratamento';
$field->coluna = 'tratamento';
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
$field->classes = 'nome-do-professor';
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
$field->interaction = '';
$field->tipo = 'text';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Tipo.Doc';
$field->coluna = 'tipo_documento';
$field->titulo_listagem = 'Tipo';
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
	'CPF' => 'CPF',
	'CNPJ' => 'CNPJ',
);
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Documento';
$field->coluna = 'cpf';
$field->dica = 'CPF, CNPJ, Passaporte';
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
$field->titulo = 'Alocação';
$field->coluna = 'alocacao';
$field->pk = false;
$field->isnull = true;
$field->add = true;
$field->valida = false;
$field->edit = true;
$field->view = true;
$field->lista = true;
$field->filter = true;
$field->order = true;
$field->type = 'INT(11)';
$field->interaction = '';
$field->tipo = 'join';
	$join = new Obj();
	$join->modulo = 'departamento';
	$join->chave = 'id';
	$join->valor = 'sigla';
	$join->tipo = 'select';
	$join->order_by = 'sigla';
$field->join = $join;
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Vínculo';
$field->coluna = 'vinculo';
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
	'Docente' => 'Docente',
	'Professor colaborador' => 'Professor colaborador',
	'Pós-doutorando' => 'Pós-doutorando',
	'Doutorando' => 'Doutorando',
	'Mestrando' => 'Mestrando',
	'Graduando' => 'Graduando',
	'Discente' => 'Discente',
	'Servidor' => 'Servidor',
	'Estagiário' => 'Estagiário',
	'Prestador de Serviço' => 'Prestador de Serviço',
	'Externo à unidade' => 'Externo à unidade',
);
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Sexo';
$field->coluna = 'sexo';
$field->pk = false;
$field->isnull = false;
$field->add = true;
$field->valida = true;
$field->edit = true;
$field->view = true;
$field->lista = false;
$field->filter = false;
$field->order = false;
$field->type = 'VARCHAR(255)';
$field->interaction = '';
$field->tipo = 'select';
$field->valores = array(
	'm' => 'M',
	'f' => 'F',
);
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'E-mail';
$field->coluna = 'email';
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
$field->titulo = 'Usuário';
$field->coluna = 'user';
$field->dica = 'usar o email';
$field->pk = false;
$field->isnull = false;
$field->add = true;
$field->valida = true;
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
$field->titulo = 'Telefone';
$field->coluna = 'telefone';
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
$field->titulo = 'Curso';
$field->coluna = 'curso';
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
$field->tipo = 'join';
	$join = new Obj();
	$join->modulo = 'curso';
	$join->chave = 'id';
	$join->valor = 'nome';
	$join->tipo = 'select';
	$join->order_by = 'nome';
$field->join = $join;
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Programa de Pós';
$field->coluna = 'programa_pos_graduacao';
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
	$join->modulo = 'programa_pos_graduacao';
	$join->chave = 'id';
	$join->valor = 'nome';
	$join->select2 = true;
	$join->tabela_ligacao = 'pessoa_programa_pos_graduacao';
	$join->chave1 = 'pessoa';
	$join->chave2 = 'programa_pos_graduacao';
	$join->tipo = 'select';
	$join->order_by = 'nome';
$field->join = $join;
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Data inicial';
$field->coluna = 'data_i';
$field->pk = false;
$field->isnull = true;
$field->add = true;
$field->valida = false;
$field->edit = true;
$field->view = true;
$field->lista = false;
$field->filter = false;
$field->order = false;
$field->type = 'DATE';
$field->interaction = '';
$field->mask = '99/99/9999';
$field->tipo = 'date';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Data final';
$field->coluna = 'data_f';
$field->pk = false;
$field->isnull = true;
$field->add = true;
$field->valida = false;
$field->edit = true;
$field->view = true;
$field->lista = false;
$field->filter = false;
$field->order = false;
$field->type = 'DATE';
$field->interaction = '';
$field->mask = '99/99/9999';
$field->tipo = 'date';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Unidade principal';
$field->coluna = 'unidade';
$field->titulo_listagem = 'Unidade';
$field->dica = 'Esta é a unidade onde o servidor trabalha atualmente';
$field->pk = false;
$field->isnull = true;
$field->add = true;
$field->valida = true;
$field->edit = true;
$field->view = true;
$field->lista = true;
$field->filter = true;
$field->order = true;
$field->type = 'INT(11)';
$field->interaction = '';
$field->tipo = 'join';
	$join = new Obj();
	$join->modulo = 'unidade';
	$join->chave = 'id';
	$join->valor = 'sigla';
	$join->tipo = 'select';
	$join->order_by = 'sigla';
$field->join = $join;
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Unidades de contexto';
$field->coluna = 'unidades';
$field->dica = 'Este usuário poderá utilizar os sistemas multi-unidades nas unidades abaixo';
$field->pk = false;
$field->isnull = true;
$field->add = true;
$field->valida = true;
$field->edit = true;
$field->view = true;
$field->lista = false;
$field->filter = false;
$field->order = false;
$field->type = 'INT(11)';
$field->interaction = '';
$field->tipo = 'joinNN';
	$join = new Obj();
	$join->modulo = 'unidade';
	$join->chave = 'id';
	$join->valor = 'sigla';
	$join->select2 = true;
	$join->tabela_ligacao = 'main.pessoa_unidade';
	$join->chave1 = 'pessoa';
	$join->chave2 = 'unidade';
	$join->tipo = 'select';
	$join->order_by = 'sigla';
$field->join = $join;
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Observação';
$field->coluna = 'observacao';
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
$field->titulo = 'Tratamento (inglês)';
$field->coluna = 'tratamento_en';
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
$field->titulo = 'Agrupamento';
$field->coluna = 'atribuicao';
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
$field->titulo = 'Informações, linhas de pesquisa, etc.';
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
$field->classes = 'tinymce-sgcd';
$field->tipo = 'textarea-rich';
$field->rows = 15;
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Informações, linhas de pesquisa, etc. (inglês)';
$field->coluna = 'descricao_en';
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
$field->classes = 'tinymce-sgcd';
$field->tipo = 'textarea-rich';
$field->rows = 15;
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

$field = new Obj();
$field->titulo = 'Grupo';
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
	$join->ajax = true;
	$join->select2 = true;
	$join->tabela_ligacao = 'main.pessoa_grupo';
	$join->chave1 = 'pessoa';
	$join->chave2 = 'grupo';
	$join->tipo = 'select';
	$join->order_by = 'nome';
$field->join = $join;
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Sistemas restritos permitidos';
$field->coluna = 'pessoa_sistema_permitido';
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
	$join->tabela_ligacao = 'main.pessoa_sistema_permitido';
	$join->chave1 = 'pessoa';
	$join->chave2 = 'sistema';
	$join->tipo = 'select';
	$join->order_by = 'sigla';
$field->join = $join;
$field->restricao = '
	$rest = "WHERE acessibilidade = \'Restrito\'";
';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Sistemas favoritos';
$field->coluna = 'sistema_favorito';
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
	$join->tabela_ligacao = 'main.pessoa_sistema_favorito';
	$join->chave1 = 'pessoa';
	$join->chave2 = 'sistema';
	$join->tipo = 'select';
	$join->order_by = 'sigla';
$field->join = $join;
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Token primeiro acesso';
$field->coluna = 'token_primeiro_acesso';
$field->pk = false;
$field->isnull = false;
$field->add = false;
$field->valida = false;
$field->edit = false;
$field->view = false;
$field->lista = false;
$field->filter = false;
$field->order = false;
$field->type = 'VARCHAR(255)';
$field->interaction = '';
$field->tipo = 'text';
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

$field = new Obj();
$field->titulo = 'Deletado Por';
$field->coluna = 'deleted_by';
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
$field->tipo = 'join';
	$join = new Obj();
	$join->modulo = 'pessoa';
	$join->chave = 'id';
	$join->valor = 'nome';
	$join->tipo = 'select';
	$join->order_by = 'nome';
$field->join = $join;
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Deletado Em';
$field->coluna = 'deleted_on';
$field->pk = false;
$field->isnull = true;
$field->add = false;
$field->valida = false;
$field->edit = false;
$field->view = false;
$field->lista = false;
$field->filter = false;
$field->order = false;
$field->type = 'DATETIME';
$field->interaction = '';
$field->tipo = 'datetime';
$module->campo[$field->coluna] = $field;

/*==========================================*/

$field = new Obj();
$field->titulo = 'Deletado Porque';
$field->coluna = 'deleted_because';
$field->pk = false;
$field->isnull = true;
$field->add = false;
$field->valida = false;
$field->edit = false;
$field->view = false;
$field->lista = false;
$field->filter = false;
$field->order = false;
$field->type = 'VARCHAR(255)';
$field->interaction = '';
$field->tipo = 'text';
$module->campo[$field->coluna] = $field;

/*==========================================*/

/* GRID FOR THE FORM LAYOUT ========================================================================================= */

$grid = array();

$grid[] = array('4.end');
$grid[] = array('2','7','3');
$grid[] = array('3','3','2','2','2');
$grid[] = array('4','4','4');
$grid[] = array('4','8');
$grid[] = array('4','4.end');
$grid[] = array('6','6');
$grid[] = array('12');
$grid[] = array('Dados para o SGCD');
$grid[] = array('4','8');
$grid[] = array('12');
$grid[] = array('12');
$grid[] = array('Dados de acesso a sistemas');
$grid[] = array('12');
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