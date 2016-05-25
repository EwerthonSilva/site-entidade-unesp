<?

/* ================================================================================================================== */
/* DBO CLASS FILE FOR MODULE 'dbo_slider_slide' ================================= AUTO-CREATED ON 21/08/2015 01:34:12 */
/* ================================================================================================================== */

/* IMPORTANT: This file is generated only in the first DBO sync, what means you should edit only via text editor. */

if(!class_exists('dbo_slider_slide'))
{
	class dbo_slider_slide extends dbo
	{
		/* smart constructor: will perform load() upon numeric argument and loadAll() upon string argument */
		function __construct($foo = '')
		{
			parent::__construct('dbo_slider_slide');
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

	} //class declaration
} //if ! class exists

?>