<?

/* ================================================================================================================== */
/* DBO CLASS FILE FOR MODULE 'evento' =========================================== AUTO-CREATED ON 06/05/2013 14:20:01 */
/* ================================================================================================================== */

/* IMPORTANT: This file is generated only in the first DBO sync, what means you should edit only via text editor. */

if(!class_exists('evento'))
{
	class evento extends dbo
	{
		/* smart constructor: will perform load() upon numeric argument and loadAll() upon string argument */
		function __construct($foo = '')
		{
			parent::__construct('evento');
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
		function getBreadcrumbIdentifier()
		{
			return $this->nome;
		}

	} //class declaration
} //if ! class exists

?>