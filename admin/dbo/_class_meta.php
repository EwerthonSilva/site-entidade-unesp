<?

/* ================================================================================================================== */
/* DBO CLASS FILE FOR MODULE 'meta' ============================================= AUTO-CREATED ON 24/06/2015 15:47:23 */
/* ================================================================================================================== */

/* IMPORTANT: This file is generated only in the first DBO sync, what means you should edit only via text editor. */

if(!class_exists('meta'))
{
	class meta extends dbo
	{
		/* smart constructor: will perform load() upon numeric argument and loadAll() upon string argument */
		function __construct($foo = '')
		{
			parent::__construct('meta');
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

		//your methods here

		static function get($meta_key = false, $params = array())
		{
			global $dbo;
			extract($params);

			$modulo = $modulo ? $modulo : $dbo->null();
			$modulo_id = $modulo_id ? $modulo_id : $dbo->null();
			$relation_type = $relation_type ? $relation_type : $dbo->null();
			$output = $output ? $output : 'string';

			if(!$meta_key)
			{
				$get_details = true;
			}

			$meta = new meta();
			if($meta_key)
			{
				$meta->meta_key = $meta_key;
			}
			$meta->modulo = $modulo;
			$meta->modulo_id = $modulo_id;
			$meta->relation_type = $relation_type;
			if(!$all_pessoas)
			{
				$meta->created_by = $created_by;
			}
			$meta->loadAll();
			if($meta->size())
			{
				$return = array();
				if($get_details)
				{
					do {
						$return[$meta->meta_key][] = array('meta_value' => $meta->meta_value, 'meta_details' => json_decode($meta->meta_details, true));
					}while($meta->fetch());
					//com get details o outupt é sempre array.
					return $return;
				}
				else
				{
					do {
						$return[] = $meta->meta_value;
					}while($meta->fetch());
					
				}
				if($output == 'string')
				{
					return implode("\n", $return);
				}
				return $return;
			}
			return false;
		}

		static function set($meta_key, $meta_value = null, $params = array())
		{
			extract($params);
			global $dbo;

			//iniciando variaveis
			$modulo = $modulo ? $modulo : $dbo->null();
			$modulo_id = $modulo_id ? $modulo_id : $dbo->null();
			$relation_type = $relation_type ? $relation_type : $dbo->null();
			$meta_details = is_array($meta_details) ? $meta_details : false;
			$order_by = $order_by ? $order_by : 0;

			//transformando em array para o foreach
			$meta_value = (array)$meta_value;

			//se tiver mais de uma meta, apaga as outras antes.
			if(sizeof($meta_value) > 1)
			{
				$meta = new meta();
				$meta->meta_key = $meta_key;
				$meta->modulo = $modulo;
				$meta->modulo_id = $modulo_id;
				$meta->relation_type = $relation_type;
				$meta->created_by = $created_by;
				$meta->loadAll();
				if($meta->size())
				{
					do {
						$meta->delete();
					}while($meta->fetch());
				}

				//e salva as novas
				foreach($meta_value as $value)
				{
					$meta = new meta();
					$meta->meta_key = $meta_key;
					$meta->meta_value = $value;
					$meta->modulo = $modulo;
					$meta->modulo_id = $modulo_id;
					$meta->relation_type = $relation_type;
					if(is_array($meta_details))
					{
						$meta->meta_details = json_encode($meta_details);
					}
					$meta->meta_details = $meta_details;
					$meta->created_by = $created_by;
					$meta->save();
				}
			}
			
			//somente 1 meta
			else
			{
				//load da meta
				$meta = new meta();
				$meta->meta_key = $meta_key;
				$meta->modulo = $modulo;
				$meta->modulo_id = $modulo_id;
				$meta->relation_type = $relation_type;
				$meta->created_by = $created_by;
				$meta->loadAll();

				//se for mais de 1, deleta tudo.
				if($meta->size() > 1)
				{
					do {
						$meta->delete();
					}while($meta->fetch());

					//reinicia o objeto
					$meta = new meta();
					$meta->meta_key = $meta_key;
					$meta->modulo = $modulo;
					$meta->modulo_id = $modulo_id;
					$meta->relation_type = $relation_type;
				}

				//novos dados
				$meta->meta_value = $meta_value[0];
				if(is_array($meta_details))
				{
					$meta->meta_details = json_encode($meta_details);
				}
				$meta->created_by = $created_by;

				//salvando
				$meta->saveOrUpdate();
			}
			return true;
		}

		static function remove($meta_key, $params = array())
		{
			global $dbo;
			extract($params);

			$modulo = $modulo ? $modulo : $dbo->null();
			$modulo_id = $modulo_id ? $modulo_id : $dbo->null();
			$relation_type = $relation_type ? $relation_type : $dbo->null();
			$order_by = $order_by ? $order_by : 0;

			$meta = new meta();
			$meta->meta_key = $meta_key;
			$meta->modulo = $modulo;
			$meta->modulo_id = $modulo_id;
			$meta->relation_type = $relation_type;
			if(!$all_pessoas)
			{
				$meta->created_by = $created_by;
			}
			$meta->loadAll();

			if($meta->size())
			{
				do {
					$meta->delete();
				}while($meta->fetch());
			}
			return true;
		}

		static function setPreference($json_key, $value, $meta_key = null)
		{
			$meta_key = !strlen(trim($meta_key)) ? 'global_preferences' : $meta_key;
			$meta = new meta();
			$meta->meta_key = $meta_key;
			$meta->created_by = loggedUser();
			$meta->loadAll();
			$json = json_decode($meta->meta_value, true);
			$json[$json_key] = $value;
			$meta->meta_value = json_encode($json);
			$meta->saveOrUpdate();
		}

		static function getPreference($json_key, $meta_key = null)
		{
			global $_system;

			$meta_key = $meta_key === null ? 'global_preferences' : $meta_key;

			if(!$_system['preferences'][loggedUser()][$meta_key])
			{
				$meta = new meta();
				$meta->meta_key = $meta_key;
				$meta->created_by = loggedUser();
				$meta->loadAll();
				$_system['preferences'][loggedUser()][$meta_key] = $meta;
			}
			$json = json_decode($_system['preferences'][loggedUser()][$meta_key]->meta_value, true);
			return $json[$json_key];
		}

	} //class declaration
} //if ! class exists

?>