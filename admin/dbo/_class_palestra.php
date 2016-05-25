<?

/* ================================================================================================================== */
/* DBO CLASS FILE FOR MODULE 'palestra' ========================================= AUTO-CREATED ON 06/05/2013 14:20:01 */
/* ================================================================================================================== */

/* IMPORTANT: This file is generated only in the first DBO sync, what means you should edit only via text editor. */

if(!class_exists('palestra'))
{
	class palestra extends dbo
	{
		/* smart constructor: will perform load() upon numeric argument and loadAll() upon string argument */
		function __construct($foo = '')
		{
			parent::__construct('palestra');
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
		
		function getVagasDisponiveis()
		{
			$total = $this->vagas;
			$sql = "SELECT COUNT(*) AS total FROM inscricao WHERE palestra = '".$this->id."'";
			$res = mysql_query($sql);
			$lin = mysql_fetch_object($res);
			return $total - $lin->total;
		}

		function getBreadcrumbIdentifier()
		{
			$parts = explode("\n", $this->titulo);
			return trim($parts[0]);
		}

	} //class declaration
} //if ! class exists

?>