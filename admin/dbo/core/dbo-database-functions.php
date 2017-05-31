<?php
	if(!defined('DBO_DATABASE_LIB') || DBO_DATABASE_LIB === 'mysqli')
	{

		function dboDatabaseConnect($host, $user, $pass, $db)
		{
			return mysqli_connect($host, $user, $pass, $db);
		}

		// ----------------------------------------------------------------------------------------------------------------

		function dboQuery($sql)
		{
			global $dbo_query_counter;
			global $dbo_default_link_connection;

			$dbo_query_counter++;
			//dboLog('query', $sql);
			$ret = mysqli_query($dbo_default_link_connection, $sql);
			if($ret)
			{
				return $ret;
			}
			else
			{
				echo '[[[ '.$sql.' ]]] ---> '.dboQueryError();
			}
		}

		// ----------------------------------------------------------------------------------------------------------------

		function dboDatabasePing()
		{
			global $dbo_default_link_connection;

			return mysqli_ping($dbo_default_link_connection);
		}
		
		// ----------------------------------------------------------------------------------------------------------------

		function dboAffectedRows()
		{
			global $dbo_default_link_connection;

			return mysqli_affected_rows($dbo_default_link_connection);
		}

		// ----------------------------------------------------------------------------------------------------------------

		function dboQueryError()
		{
			global $dbo_default_link_connection;

			return mysqli_error($dbo_default_link_connection);
		}

		// ----------------------------------------------------------------------------------------------------------------

		function dboFetchAssoc($res)
		{
			return mysqli_fetch_assoc($res);
		}

		// ----------------------------------------------------------------------------------------------------------------

		function dboFetchObject($res)
		{
			return mysqli_fetch_object($res);
		}

		// ----------------------------------------------------------------------------------------------------------------

		/* não existe uma correspondente em mysqli, então implementamos. */
		function mysqli_result($res, $row, $field=0) { 
			$res->data_seek($row); 
			$datarow = $res->fetch_array(); 
			return $datarow[$field]; 
		} 

		function dboQueryResult($res, $pos)
		{
			return mysqli_result($res, $pos);
		}

		// ----------------------------------------------------------------------------------------------------------------

		function dboInsertId()
		{
			global $dbo_default_link_connection;
			
			return mysqli_insert_id($dbo_default_link_connection);
		}

		// ----------------------------------------------------------------------------------------------------------------

		function dboEscape($var)
		{
			global $dbo_default_link_connection;

			return mysqli_real_escape_string($dbo_default_link_connection, $var);
		}

	}
	else
	{

		function dboDatabaseConnect($host, $user, $pass, $db)
		{
			$link = mysql_connect($host, $user, $pass);
			if($link)
			{
				$db = mysql_select_db($db, $link);
			}
			return $link;
		}

		// ----------------------------------------------------------------------------------------------------------------

		function dboQuery($sql)
		{
			global $dbo_query_counter;
			$dbo_query_counter++;
			//dboLog('query', $sql);
			$ret = mysql_query($sql);
			if($ret)
			{
				return $ret;
			}
			else
			{
				echo dboQueryError();
			}
		}

		// ----------------------------------------------------------------------------------------------------------------

		function dboDatabasePing()
		{
			return mysql_ping();
		}
		
		// ----------------------------------------------------------------------------------------------------------------

		function dboAffectedRows()
		{
			return mysql_affected_rows();
		}

		// ----------------------------------------------------------------------------------------------------------------

		function dboQueryError()
		{
			return mysql_error();
		}

		// ----------------------------------------------------------------------------------------------------------------

		function dboFetchAssoc($res)
		{
			return mysql_fetch_assoc($res);
		}

		// ----------------------------------------------------------------------------------------------------------------

		function dboFetchObject($res)
		{
			return mysql_fetch_object($res);
		}

		// ----------------------------------------------------------------------------------------------------------------

		function dboQueryResult($res, $pos)
		{
			return mysql_result($res, $pos);
		}

		// ----------------------------------------------------------------------------------------------------------------

		function dboInsertId()
		{
			return mysql_insert_id();
		}

		// ----------------------------------------------------------------------------------------------------------------
		
		function dboEscape($var)
		{
			return mysql_real_escape_string($var);
		}

	}
?>