<?

/* ================================================================================================================== */
/* DBO CLASS FILE FOR MODULE 'dbo_slider' ======================================= AUTO-CREATED ON 20/08/2015 12:06:01 */
/* ================================================================================================================== */

/* IMPORTANT: This file is generated only in the first DBO sync, what means you should edit only via text editor. */

if(!class_exists('dbo_slider'))
{
	class dbo_slider extends dbo
	{
		/* smart constructor: will perform load() upon numeric argument and loadAll() upon string argument */
		function __construct($foo = '')
		{
			parent::__construct('dbo_slider');
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

		function getFirstSlide()
		{
			$slide = new dbo_slider_slide("WHERE slider = '".$this->id."' ORDER BY order_by LIMIT 1");
			return $slide;
		}

		function delete()
		{
			$slide = new dbo_slider_slide("WHERE slider = '".$this->id."'");
			if($slide->size())
			{
				do {
					$slide->delete();
				}while($slide->fetch());
			}
			return parent::delete();
		}

	} //class declaration
} //if ! class exists

function form_dbo_slider_append($operation, $obj)
{
	ob_start();
	?>
	<div class="row">
		<div class="large-12 columns">
			<div class="helper arrow-top">
				<p class="no-margin">Digite um nome para o seu slider.<br />No próximo passo você irá configurá-lo e cadastrar os slides individualmente.</p> 
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

function form_dbo_slider_update($obj)
{
	require_once(DBO_PATH.'/core/dbo-slider-admin.php');
	return formDboSliderUpdate($obj);
}

?>