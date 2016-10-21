<?
if(!class_exists('Obj'))
{
	class Obj {}
}

ob_start();
session_start();
@ini_set('default_charset', 'UTF-8');
header('Content-Type: text/html; charset=UTF-8');

/*
try to make the default connection to the bank, from the framework.
If it doesn't work you should try to include your own connection...
Or use the maker without connection.
It also works, but with some few limitations.
*/
@include('../../lib/connection.php');
@include('../../lib/defines.php');
@include('../../local-defines.php');  

/* definindo o tipo de tabela do mysql */
define(MYSQL_TABLE_TYPE, $_GET['table_engine'] == 'MyISAM' ? 'MyISAM' : 'InnoDB');
define(CREATE_FKS, MYSQL_TABLE_TYPE == 'InnoDB' ? true : false);

$sql = "SHOW TABLES";
if(@mysql_query($sql))
{
	define (MYSQL_CONNECTION, TRUE);
	$sql = "SELECT * FROM perfil"; /* checks if the perfil table exists. */
	if(mysql_query($sql))
	{
		define(HAS_PROFILES, TRUE);
	} else {
		define(HAS_PROFILES, FALSE);
	}
} else {
	define (MYSQL_CONNECTION, FALSE);
	define(HAS_PROFILES, FALSE);
}

if(!function_exists('safeArrayKey'))
{
	function safeArrayKey($key, $array)
	{
		if(@array_key_exists($key, $array))
		{
			return safeArrayKey($key+100, $array);
		}
		return $key;
	}
}


/* ---------------------------------------------------------------------------------------------------------- */

function renderSelectFkActions($input_name, $selected, $operation)
{
	$selected = $selected ? $selected : ($operation == 'update' ? 'CASCADE' : ($operation == 'delete' ? 'SET NULL' : ''));
	ob_start();
	?>
	<select name="<?= $input_name ?>">
		<option <?= $selected == 'RESTRICT' ? 'selected' : '' ?>>RESTRICT</option>
		<option <?= $selected == 'NO ACTION' ? 'selected' : '' ?>>NO ACTION</option>
		<option <?= $selected == 'CASCADE' ? 'selected' : '' ?>>CASCADE</option>
		<option <?= $selected == 'SET NULL' ? 'selected' : '' ?>>SET NULL</option>
		<option <?= $selected == 'SET DEFAULT' ? 'selected' : '' ?>>SET DEFAULT</option>
	</select>
	<?php
	return ob_get_clean();
}

/* ---------------------------------------------------------------------------------------------------------- */

function createFksIfNotExists($foo = array())
{
	/* Foo:
	   - table
	   - column
	   - referenced_table
	   - referenced_column
	*/
	//verificando se AS FKs estão ativas e o array de data tem conteudo
	//criando as chaves estrangeiras
	if(CREATE_FKS && sizeof((array)$foo))
	{

		foreach($foo as $data)
		{
			//extraindo as variaveis do array para melhor semântica
			extract($data);

			$update_action = $on_update ? $on_update : 'CASCADE';
			$delete_action = $on_delete ? $on_delete : 'SET NULL';

			//não criar para campos automaticos do dbo
			if(in_array($column, array('created_by', 'updated_by', 'deleted_by'))) continue;

			//verifica se a tabela em que a chave vai ser criada é do tipo InnoDB
			$sql = "SHOW TABLE STATUS WHERE Name = '".$table."'";
			$res = mysql_query($sql);
			$lin = mysql_fetch_object($res);
			//se a tabela é InnoDB, declaramos o nome da constraint
			if($lin->Engine == 'InnoDB')
			{
				//definindo o nome da constraint
				$const_name = "constr_t_".str_replace('.', '_', $table)."_c_".$column."_fk";

				//verifica se a constraint já existe
				$sql = "
					SELECT *
					FROM information_schema.REFERENTIAL_CONSTRAINTS
					WHERE CONSTRAINT_SCHEMA = '".DB_BASE."'
					AND REFERENCED_TABLE_NAME = '".$referenced_table."'
					AND CONSTRAINT_NAME = '".$const_name."'				
				";
				$res = mysql_query($sql);
				$lin = mysql_fetch_object($res);
				
				//agora, verificamos se as regras de updade ou delete são diferentes do desejado
				//se for, dropamos a FK e recriamos (ou criamos pela primeira vez, se for o caso)
				if($lin->UPDATE_RULE != $update_action || $lin->DELETE_RULE != $delete_action)
				{
					//dropa a constraint, se existe
					if(mysql_affected_rows())
					{
						$sql = "ALTER TABLE ".$table." DROP FOREIGN KEY ".$const_name;
						mysql_query($sql);
					}

					//cria a constraint (ou recria, dependendo do caso)
					$sql = "ALTER TABLE ".$table." ADD CONSTRAINT ".$const_name." FOREIGN KEY ".$column."_fk (".$column.") REFERENCES ".$referenced_table." (".$referenced_column.") ON DELETE ".$delete_action." ON UPDATE ".$update_action;
					//echo "FK criada: ".$const_name.', ';
					if(mysql_query($sql))
					{
						echo "FK criada: ".$const_name.', ';
					};
				}
			}
		}
	}
}

/* ---------------------------------------------------------------------------------------------------------- */

function unident($text)
{
	$text = trim($text);
	$partes = explode("\n", $text);

	$partes_trimmed = array();
	$count = 1;
	foreach($partes as $chave => $valor)
	{
		if($count != 1) {
			$partes_trimmed[] = substr($valor, 1, strlen($valor)-1);
		} else {
			$partes_trimmed[] = $valor;
		}
		$count++;
	}
	return implode("\n", $partes_trimmed);
}

/* ---------------------------------------------------------------------------------------------------------- */

function ident($code)
{
	$partes_final = array();
	$code = stripslashes($code);
	$partes = explode("\n", $code);
	foreach($partes as $key => $value)
	{
		$partes_final[] = "\t".$value;
	}
	return implode("\n", $partes_final);
}

/* ---------------------------------------------------------------------------------------------------------- */

function maxModuleOrderBy()
{
	$max = 0;
	if(is_array($_SESSION['dbomaker_modulos']))
	{
		foreach($_SESSION['dbomaker_modulos'] as $modulo)
		{
			if($modulo->order_by > $max && $modulo->modulo != 'temporary_module_key_5658')
			{
				$max = $modulo->order_by;
			}
		}
	}
	return $max;
}

/* ---------------------------------------------------------------------------------------------------------- */

function checkBackupDir()
{
	//first we'll see if the backup folder exists and is writable. If not, we'll try to create it.
	$backup_folder = '../module_backups';
	if(file_exists($backup_folder))
	{
		if(is_writable($backup_folder))
		{
			return true;
		}
		return false;
	}
	else
	{
		if(@mkdir($backup_folder))
		{
			return true;
		}
		return false;
	}
}

/* ---------------------------------------------------------------------------------------------------------- */

function unixEOL($string)
{
	//fix line breaks bugs
	$search = array("\r\n", "\r");
	$replace = array("\n", "\n");
	return str_replace($search, $replace, $string);
}

/* ---------------------------------------------------------------------------------------------------------- */

function singleScape($string)
{
	return str_replace("'", "\\'", $string);
}

/* ---------------------------------------------------------------------------------------------------------- */

function encNameAjax($name)
{
	$name = str_replace("_", "~", $name);
	$name = str_replace("-", "^", $name);
	$name = str_replace("=", "§", $name);
	return $name;
}

/* ---------------------------------------------------------------------------------------------------------- */

function decNameAjax($name)
{
	$name = str_replace("~", "_", $name);
	$name = str_replace("^", "-", $name);
	$name = str_replace("§", "=", $name);
	return $name;
}

/* ---------------------------------------------------------------------------------------------------------- */

function checkSyntax($code)
{
	if(strlen(trim($code)))
	{
		return true;
//		return @eval($code);
	}
	return true;
}

/* ---------------------------------------------------------------------------------------------------------- */

function flagUpdate($module)
{
	$_SESSION['dbomaker_updated'][$module] = $module;
	unflagDelete($module);
}

/* ---------------------------------------------------------------------------------------------------------- */

function flagDelete($module)
{
	$_SESSION['dbomaker_deleted'][$module] = $module;
}

/* ---------------------------------------------------------------------------------------------------------- */

function unflagUpdate($module)
{
	unset($_SESSION['dbomaker_updated'][$module]);
}

/* ---------------------------------------------------------------------------------------------------------- */

function unflagDelete($module)
{
	unset($_SESSION['dbomaker_deleted'][$module]);
}

/* ---------------------------------------------------------------------------------------------------------- */

function diskDelete($mod)
{
	@unlink('../_dbo_'.$mod.'.php');
}

/* ---------------------------------------------------------------------------------------------------------- */

function getModuleTable($module)
{
	return $_SESSION['dbomaker_modulos'][$module]->tabela;
}

/* ---------------------------------------------------------------------------------------------------------- */

function dropModuleFks($module)
{
	foreach((array)$module->campo as $key => $campo)
	{
		if($campo->tipo == 'join')
		{
			//nao criar para campos automaticos do sistema.
			if(in_array($campo->coluna, array('created_by', 'updated_by', 'deleted_by'))) continue;

			$const_name = "constr_t_".str_replace('.', '_', $module->tabela)."_c_".$campo->coluna."_fk";			
			$sql = "ALTER TABLE ".$module->tabela." DROP FOREIGN KEY ".$const_name.";";
			if(!mysql_query($sql))
			{
				echo mysql_error()."<br />";
			}
		}
	}
}

/* ---------------------------------------------------------------------------------------------------------- */

function syncTable($module)
{
	//trying to create the tables.
	$sql = "CREATE TABLE IF NOT EXISTS ".$module->tabela." (\n";
	if(is_array($module->campo))
	{
		$sql_parts = array();
		$joinNN = array();
	
		foreach($module->campo as $field)
		{
			if($field->tipo == 'pk')
			{
				$pk = $field->coluna;
				$sql_parts[] = "\t".$field->coluna." int(11) NOT NULL auto_increment";
			}
			elseif($field->tipo == 'join')
			{
				//se o tipo da tabela for InnoDB, tenta criar as chaves
				if($module->table_engine == 'InnoDB')
				{
					$fks[] = array(
						'table' => $module->tabela,
						'column' => $field->coluna,
						'referenced_table' => getModuleTable($field->join->modulo),
						'referenced_column' => $field->join->chave,
						'on_update' => $field->join->on_update,
						'on_delete' => $field->join->on_delete,
					);
				}
				$sql_parts[] = "\t".$field->coluna." ".$field->type." ".(($field->isnull)?("NULL"):("NOT NULL"));
			}
			//para o caso de PKs não A.I.
			elseif($field->tipo == 'joinNN')
			{
				//montando as configurações de chave estrangeira
				$fks[] = array(
					'table' => $field->join->tabela_ligacao,
					'column' => $field->join->chave1,
					'referenced_table' => $module->tabela,
					'referenced_column' => $field->join->chave1_pk ? $field->join->chave1_pk : 'id',
					'on_update' => $field->join->chave1_on_update,
					'on_delete' => $field->join->chave1_on_delete,
				);
				$fks[] = array(
					'table' => $field->join->tabela_ligacao,
					'column' => $field->join->chave2,
					'referenced_table' => getModuleTable($field->join->modulo),
					'referenced_column' => $field->join->chave2_pk ? $field->join->chave2_pk : 'id',
					'on_update' => $field->join->chave2_on_update,
					'on_delete' => $field->join->chave2_on_delete,
				);
				
				$sql_join  = "CREATE TABLE IF NOT EXISTS ".$field->join->tabela_ligacao." (\n";
				$sql_join .= "\tid int(11) NOT NULL auto_increment,\n";
				$sql_join .= "\t".$field->join->chave1." int(11) NULL,\n";
				$sql_join .= "\t".$field->join->chave2." int(11) NULL,\n";
				$sql_join .= "UNIQUE (".$field->join->chave1.", ".$field->join->chave2."),\n";
				$sql_join .= "PRIMARY KEY (id)\n";
				$sql_join .= ") ENGINE = InnoDB DEFAULT CHARSET=utf8mb4; ";
				mysql_query($sql_join);

				//salvando a definição dos joinNN para o alter table
				$joinNN[] = $field->join;
			}
			elseif($field->coluna == 'inativo')
			{
				$sql_parts[] = "\t".$field->coluna." int(11) NOT NULL";
			}
			elseif($field->tipo == 'query') {}
			else
			{
				if($field->pk == true) {
					$pk = $field->coluna;
				}
				$sql_parts[] = "\t".$field->coluna." ".$field->type." ".(($field->isnull)?("NULL"):("NOT NULL")).($field->unique ? ' UNIQUE' : '');
			}
		}
	}
	if($pk)
	{
		$sql_parts[] .= "PRIMARY KEY ( ".$pk." )";
	}
	$sql .= @implode(",\n", $sql_parts);
	$sql .= ") ENGINE = ".($module->table_engine ? $module->table_engine : MYSQL_TABLE_TYPE)." DEFAULT CHARSET=utf8mb4; ";

	mysql_query($sql);

	//agora, tenta verificar se a tabela em questão é do mesmo engine que está no modulo
	$sql = "SHOW TABLE STATUS WHERE Name = '".$module->tabela."'";
	$res = mysql_query($sql);
	$lin = mysql_fetch_object($res);
	if($module->table_engine && $module->table_engine != $lin->Engine)
	{
		//se o módulo for MyISAM, remove todas as constraints da tabela antes de fazer a alteração
		if($module->table_engine == 'MyISAM') dropModuleFks($module);

		//finalmente, altera o engine da tabela
		$sql = "ALTER TABLE ".$module->tabela." ENGINE = ".$module->table_engine.";";
		if(!mysql_query($sql))
		{
			echo mysql_error();
		};
	}

	//and now checking for the fields in the table. alter tables to create extra-fields.
	$sql = "SHOW COLUMNS FROM ".$module->tabela;
	$res = mysql_query($sql);

	//saving all fields in the temp array
	if(mysql_affected_rows())
	{
		while($lin = @mysql_fetch_object($res))
		{
			$fields[] = $lin->Field;
		}
	}

	$virtual_field_types = array('joinNN', 'query');

	//check if the module field exists in the table
	if(is_array($module->campo) && is_array($fields))
	{
		foreach($module->campo as $field)
		{
			//if not exists, create.
			if(!in_array($field->coluna, $fields) && !in_array($field->tipo, $virtual_field_types) && $field->coluna != 'temporary_field_key_5658')
			{

				if($field->tipo == 'pk')
				{
					$sql = "ALTER TABLE ".$module->tabela." ADD ".$field->coluna." INT NOT NULL AUTO_INCREMENT PRIMARY KEY";
				}
				else
				{
					$sql = "ALTER TABLE ".$module->tabela." ADD ".$field->coluna." ".fieldType($field->type)." ".((checkTypeForCollate($field->type))?("CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci"):(""))." ".(($field->isnull)?("NULL"):("NOT NULL"));
				}
				echo $sql;
				mysql_query($sql);
			}
		}
	}

	//fazendo alter table nas tabelas de ligação
	if(sizeof($joinNN))
	{
		$colunas = array(
			'chave1',
			'chave2',
			'relacao_adicional_coluna',
		);
		foreach($joinNN as $join)
		{
			$sql = "SHOW COLUMNS FROM ".$join->tabela_ligacao;
			$res = mysql_query($sql);

			//saving all fields in the temp array
			if(mysql_affected_rows())
			{
				$fields = array();
				while($lin = @mysql_fetch_object($res))
				{
					$fields[] = $lin->Field;
				}
				foreach($colunas as $coluna)
				{
					if(isset($join->$coluna) && !in_array($join->{$coluna}, $fields))
					{
						$sql = "ALTER TABLE ".$join->tabela_ligacao." ADD ".$join->{$coluna}." INT(11);";
						echo $sql;
						mysql_query($sql);
					}
				}
			}
		}		
	}

	//cria as chaves estrangeiras no banco de dados, se for o caso.
	createFksIfNotExists($fks);
}

/* ---------------------------------------------------------------------------------------------------------- */

function fieldType($type)
{
	$no_size = array(
		'text',
		'tinytext',
		'mediumtext',
		'longtext',
	);
	if(in_array($type, $no_size))
	{
		list($type, $trash) = explode("(", $type);
	}
	return $type;
}

/* ---------------------------------------------------------------------------------------------------------- */

function checkTypeForCollate($string)
{
	$need_collate = array(
		'varchar',
		'char',
		'text',
		'tinytext',
		'mediumtext',
		'longtext',
	);
	list($tipo, $lixo) = explode("(", $string);
	return in_array(strtolower($tipo), $need_collate);
}

/* ---------------------------------------------------------------------------------------------------------- */

function auth()
{
	include('../../lib/defines.php');
	if(!in_array($_SESSION['user'], $SUPER_ADMINS))
	{
		?><h1>Access Denied.</h1><p>You either is not a super-admin developer or is not logged in.<br>Shame on you.</p><?
		exit();
	}
}

/* ---------------------------------------------------------------------------------------------------------- */

function checkCHMOD($path)
{
	if(file_exists($path))
	{
		return intval(substr(sprintf('%o', @fileperms($path)), -4));
	}
	else
	{
		return 1000;
	}
}


?>