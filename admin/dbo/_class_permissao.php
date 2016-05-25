<?

/* ================================================================================================================== */
/* DBO CLASS FILE FOR MODULE 'permissao' ======================================== AUTO-CREATED ON 16/10/2014 19:26:21 */
/* ================================================================================================================== */

/* IMPORTANT: This file is generated only in the first DBO sync, what means you should edit only via text editor. */

if(!class_exists('permissao'))
{
	class permissao extends dbo
	{
		/* smart constructor: will perform load() upon numeric argument and loadAll() upon string argument */
		function __construct($foo = '')
		{
			parent::__construct('permissao');
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