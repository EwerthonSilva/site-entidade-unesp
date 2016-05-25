<?

/* ================================================================================================================== */
/* DBO CLASS FILE FOR MODULE 'inscricao' ======================================== AUTO-CREATED ON 06/05/2013 14:22:49 */
/* ================================================================================================================== */

/* IMPORTANT: This file is generated only in the first DBO sync, what means you should edit only via text editor. */

if(!class_exists('inscricao'))
{
	class inscricao extends dbo
	{
		/* smart constructor: will perform load() upon numeric argument and loadAll() upon string argument */
		function __construct($foo = '')
		{
			parent::__construct('inscricao');
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

	} //class declaration
} //if ! class exists

?>