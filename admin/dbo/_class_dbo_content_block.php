<?

/* ================================================================================================================== */
/* DBO CLASS FILE FOR MODULE 'dbo_content_block' ================================ AUTO-CREATED ON 29/03/2016 13:35:23 */
/* ================================================================================================================== */

/* IMPORTANT: This file is generated only in the first DBO sync, what means you should edit only via text editor. */

if(!class_exists('dbo_content_block'))
{
	class dbo_content_block extends dbo
	{
		/* smart constructor: will perform load() upon numeric argument and loadAll() upon string argument */
		function __construct($foo = '')
		{
			parent::__construct('dbo_content_block');
			if($foo != '')
			{
				if(is_numeric($foo))
				{
					$this->id = $foo;
					$this->load();
				}
				elseif(is_string($foo))
				{
					$this->loadAll($foo);
				}
			}
		}

		function __toString()
		{
			return (string)$this->value;
		}

		function __call($method, $args)
		{
			$params = (array)$args[0];

			//montando um objeto na mesma estrutura das definições do DboFieldType
			$det = new Obj();
			$det->tipo = $this->params['field_type'];
			
			//instancioando o field type e retornando o metodo
			$f = new DboFieldType($this->value, $det);
			return $f->$method($params);
		}

		static function set($name, $value = null, $params = array())
		{
			$params['relation_type'] = 'content_block';
			return meta::set($name, $value, $params);
		}

		static function get($name = false, $params = array())
		{
			global $_system;

			extract($params);

			$params['relation_type'] = 'content_block';
			$params['name'] = $name;
			$params['field_type'] = dbo_content_block::getFieldType($name, $params);

			//content blcoks de paginas e outros módulos
			if($modulo && $modulo_id)
			{
				if(!is_object($_system['objects']['content_block']['modules'][$modulo][$id][$name]))
				{
					$cb = new dbo_content_block();
					$cb->value = meta::get($name, $params);
					$cb->params = $params;
					$_system['objects']['content_block']['modules'][$modulo][$id][$name] = $cb;
				}
				return $_system['objects']['content_block']['modules'][$modulo][$id][$name];
			}
			//globais
			else
			{
				if(!is_object($_system['objects']['content_block']['global'][$name]))
				{
					$cb = new dbo_content_block();
					$cb->value = meta::get($name, $params);
					$cb->params = $params;
					$_system['objects']['content_block']['global'][$name] = $cb;
				}
				return $_system['objects']['content_block']['global'][$name];
			}
		}

		static function renderField($params = array())
		{
			//carrega as informações da tabela para a campo do formulário
			$params['value'] = dbo_content_block::get($params['name'], $params);

			//alterando o nome dos content_blocks para não ter perido de se misturarem com os outros dados do POST.
			$params['name'] = 'dbo_content_block_'.$params['name'];

			$params['autosize'] = $params['autosize'] === null ? true : $params['autosize'];
			$params['markdown'] = $params['markdown'] === null ? true : $params['markdown'];

			ob_start();
			?>
			<div class="row">
				<div class="large-<?= $params['markdown'] ? 9 : 12 ?> columns"><label><?= $params['label'] ?><?= $params['dica'] ? ' <i data-tooltip class="fa fa-question-circle has-tip tip-top" title="'.htmlSpecialChars($params['dica']).'"></i>' : '' ?></label></div>
				<?php
					if($params['markdown'])
					{
						?>
						<div class="large-3 columns text-right"><a href="https://guides.github.com/features/mastering-markdown/" target="_blank" class="has-tip tip-top font-14 top-minus-3" data-tooltip title="O Markdown está ativo. Clique para saber mais."><i class="fa fa-arrow-circle-down"></i></a></div>
						<?php
					}
				?>
			</div>
			<?= dboUI::field($params['field_type'], 'update', false, $params); ?>
			<?php
			return ob_get_clean();
		}

		static function smartSetAndUpdate($data_array = array(), $params = array())
		{
			global $_system;
			extract($params);
			//vericicamos se o nome da variavel começa com o idenfiticador
			foreach($data_array as $key => $value)
			{
				if(strpos($key, 'dbo_content_block_') === 0)
				{
					//achou... instanciamos a dboUI
					require_once(DBO_PATH.'/core/dbo-ui.php');

					//atualizando o nome do content block para o correto
					$key = preg_replace('/^dbo_content_block_/is', '', $key);

					//pegando o field_type
					$field_type = dbo_content_block::getFieldType($key, array(
						'modulo' => $modulo,
						'modulo_id' => $modulo_id,
					));

					dbo_content_block::set($key, dboUI::fieldSQL($field_type, $value), $params);
				}
			}
		}

		static function getFieldType($key, $params = array())
		{
			global $_system;
			extract($params);
			if($modulo == 'pagina')
			{
				return $_system['content_block']['pagina']['slug'][$modulo_id][$key]['field_type'];
			}
			else
			{
				return $_system['content_block']['global'][$key]['field_type'];
			}
		}

	} //class declaration
} //if ! class exists

function auto_admin_dbo_content_block($params = array())
{
	require_once(DBO_PATH.'/core/dbo-content-block-admin.php');
	return autoAdminDboContentBlock($params);
}

function getContentBlock($name, $params = array())
{
	extract($params);
	return dbo_content_block::get($name, $params);
}

?>