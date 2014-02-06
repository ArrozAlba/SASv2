<?

/*

	v.3.0.0

	ChangeLog:

	v.1.0.1:
		- Incluye soporte para acces

	v.1.0.2:
		- Solucionados algunos bugs

	v.1.0.3:
		- Bug: reiniciamos a vacio el error al hacer una nueva consulta

	v.2.0.0:
		- Soporte Adodb

*/



class db
{
	var $db;

	var $error=false;
	var $result;
	var $debug;


	function db($type,$debug=false)
	{


	}

	//Conectamos con una base de datos sqlite
	function connect($server,$user,$pass,$database)
	{
		$result=true;
		if (!$this->db=@mysql_connect($server, $user,$pass))
		{
			$this->error=mysql_error();
		}
		else {
			if (!@mysql_select_db($database))
			{
				$error=mysql_error();
			}
		}
		if ($this->error=="") return true;
		else return false;
	}

	function sql($sql)
	{
		mysql_query("SET NAMES 'utf8'");

		if (!$response = @mysql_query($sql))
		{
			$this->error=mysql_error();
			return false;
		}
		else {

			$num_row=@mysql_num_rows($response); // count the rows

			if ($num_row=='')
			{
				return $response;
			}
			else {

				while ($fila = mysql_fetch_assoc($response)) $return[]=$fila;

				return $return;
			}
			mysql_free_result($response);

		}

	}


	function error()
	{
		return $this->error;
	}


	function close()
	{
		@mysql_close($this->db);
	}
}

?>