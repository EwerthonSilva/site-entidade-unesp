<?

/* ================================================================================================================== */
/* DBO CLASS FILE FOR MODULE 'dbo_slider_slide_layer' =========================== AUTO-CREATED ON 28/09/2016 02:07:11 */
/* ================================================================================================================== */

/* IMPORTANT: This file is generated only in the first DBO sync, what means you should edit only via text editor. */

if(!class_exists('dbo_slider_slide_layer'))
{
	class dbo_slider_slide_layer extends dbo
	{
		/* smart constructor: will perform load() upon numeric argument and loadAll() upon string argument */
		function __construct($foo = '')
		{
			parent::__construct('dbo_slider_slide_layer');
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
		function getSetting($setting)
		{
			$settings = json_decode($this->settings, true);
			return $settings[$setting];
		}

		function setSetting($setting, $value)
		{
			$settings = json_decode($this->settings, true);
			$settings[$setting] = $value;
			$this->settings = json_encode($settings);
		}

		function removeSetting($setting)
		{
			$settings = json_decode($this->settings, true);
			unset($settings[$setting]);
			$this->settings = json_encode($settings);
		}

		function getSettings()
		{
			$settings = json_decode($this->settings, true);
			return $settings;
		}

	} //class declaration
} //if ! class exists

?>