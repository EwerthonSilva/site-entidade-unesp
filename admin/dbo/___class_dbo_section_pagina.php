<?

/* ================================================================================================================== */
/* DBO CLASS FILE FOR MODULE 'dbo_section_pagina' =============================== AUTO-CREATED ON 29/03/2018 13:48:22 */
/* ================================================================================================================== */

/* IMPORTANT: This file is generated only in the first DBO sync, what means you should edit only via text editor. */

if(!class_exists('dbo_section_pagina'))
{
	class dbo_section_pagina extends dbo
	{
		/* smart constructor: will perform load() upon numeric argument and loadAll() upon string argument */
		function __construct($foo = '')
		{
			parent::__construct('dbo_section_pagina');
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