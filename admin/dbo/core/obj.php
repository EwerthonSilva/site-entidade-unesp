<?
/*
* ===============================================================================================================================================
* ===============================================================================================================================================
* Objeto Genérico ===============================================================================================================================
* ===============================================================================================================================================
* ===============================================================================================================================================
*/
class Obj {

	function __construct($attrs = array())
	{
		if(sizeof($attrs))
		{
			foreach($attrs as $key => $value)
			{
				$this->{$key} = $value;
			}
		}
	}

}
?>