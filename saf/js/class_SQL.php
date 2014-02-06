<?
class class_sql
{
	var $gestor;
	var $message;
	var $conn;	
	var $fetch_row;
	var $fetch_array;
	var $datastore;
	function class_sql($con)
	{
		//include("ini.php");
		require_once("class_datastore.php");
		$obj=new class_datastore();
		$this->datastore=$obj;
		$this->conn=$con;
		
		$_SESSION["gestor"]="MYSQL";
		$this->gestor=$_SESSION["gestor"];
	}	
	function select($sql)
	{
		//Para MYSQL
		switch ($_SESSION["gestor"]) {
		   case 'MYSQL':
				$result = mysql_query($sql,$this->conn);
				if(!$result)
				{
				   $this->message  = mysql_error() . "\n";
				   $this->message .= mysql_errno();
				   //die($message);
				}
				else
				{
					//$data=$this->obtener_datos($result);
					return $result;
				}
				break;
			case 'POSTGRE':
				$result = pg_query($this->conn,$sql);
				if(!$result)
				{
				   $message  = 'Invalid query: ' . pg_last_error() . "\n";
				   $message .= 'Whole query: ' . $sql;
				   die($message);
				}
				else
				{
					//$data=$this->obtener_datos($result);
					return $result;
				}
			break;
			}
		
	}	
	function execute($sql)
	{
		//Para MYSQL
		switch ($this->gestor) {
		   case 'MYSQL':
				$result = mysql_query($sql,$this->conn);
				$rows=mysql_affected_rows();
				if($rows<0)
				{
				   $this->message  = 'Invalid query: ' . mysql_error() . "\n";
				   $this->message .= 'Whole query: ' . $sql;
				  // die($message);
				   return false;
				}
				else
				{
					return true ;
				}
				break;
			case 'POSTGRE':
				$result = pg_query($this->conn,$sql);
				$rows   = pg_affected_rows($result) ;

				if($rows<0)
				{
				   $message  = 'Invalid query: ' . pg_last_error() . "\n";
				   $message .= 'Whole query: ' . $sql;
				   die($message);
				   return false;
				}
				else
				{
					return true;
				}
				break;
			}		
	
	}
	
	function fetch_row($sql)
	{
		if(isset($sql)) {
			if(strtoupper($_SESSION["gestor"])==strtoupper("mysql"))$this->fetch_row = mysql_fetch_assoc($sql);
			if(strtoupper($_SESSION["gestor"])==strtoupper("postgre"))$this->fetch_row = pg_fetch_assoc($sql);
			return $this->fetch_row;
		}
	}

	function num_rows($sql) {
		if(isset($sql)) {
			if(strtoupper($_SESSION["gestor"])==strtoupper("mysql")) $this->numrows = mysql_num_rows($sql);
			if(strtoupper($_SESSION["gestor"])==strtoupper("postgre")) $this->numrows = pg_num_rows($sql);
			return $this->numrows;
		}
	}

	function field_name($sql,$f) {
		if(isset($sql) && isset($f)) {
			if(strtoupper($_SESSION["gestor"])==strtoupper("mysql")) $this->fetcharray = mysql_field_name($sql,$f);
			if(strtoupper($_SESSION["gestor"])==strtoupper("postgre")) $this->fetcharray = pg_field_name($sql,$f);
			return $this->fetcharray;
		}
	}

	function field_type ($sql,$f) {
		if(isset($sql) && isset($f)) {
			if(strtoupper($_SESSION["gestor"])==strtoupper("mysql")) $this->fetcharray = mysql_field_type($sql,$f);
			if(strtoupper($_SESSION["gestor"])==strtoupper("postgre")) $this->fetcharray = pg_field_type($sql,$f);
			return $this->fetcharray;
		}
	}

	function free_result ($result)  {
			if(strtoupper($_SESSION["gestor"])==strtoupper("mysql"))  mysql_free_result($result);
			if(strtoupper($_SESSION["gestor"])==strtoupper("postgre"))   pg_free_result($result);
	}
	
	function close () {
		if(strtoupper($_SESSION["gestor"])==strtoupper("mysql")) $this->closeid = mysql_close($this->conn);
		if(strtoupper($_SESSION["gestor"])==strtoupper("postgre")) $this->closeid =  pg_close($this->conn);
		return $this->closeid;
	}
	
	
	function obtener_datos($result)
	{
		//$datastore=new datastore();
		//Para MYSQL
		switch ($_SESSION["gestor"]) {
		   case 'MYSQL':
		   		$numcolumn=mysql_num_fields($result);
				mysql_data_seek ( $result,0);
				while($row=mysql_fetch_array($result))
				{
					for ($i=0;$i<$numcolumn;$i++)
					{
						 $nombre = mysql_field_name($result,$i);
						 $valor  = $row[$nombre];
						 $this->datastore->insertRow($nombre,$valor);
					}				   	 
				}
				break;
			case 'POSTGRE':
				$filas = pg_num_rows($result);
				$numcolumn = pg_num_fields($result);

				for ($j=0; $j < $filas; $j++) 
			   {
				   	for($i=1;$i<=$numcolumn;$i++)
					{
						$nombre = pg_field_name($result, $i);
						$valor  = pg_fetch_result($result, $j, $i);
						$datastore->insertRow($nombre,$valor);
					}				    
				}
			break;
			}
	return $this->datastore->data;	
	}
	
	function begin_transaction()
	{
		//$this->conn=$con;
		//Para MYSQL
		switch ($this->gestor) {
		   case 'MYSQL':
				mysql_query("BEGIN", $this->conn);
				break;
 		   case 'POSTGRE':
		   		pg_query($this->conn, "begin");
		   		break;
		}		
	}
	
	function commit()
	{
		//$this->conn=$con;
		//Para MYSQL
		switch ($this->gestor) {
		   case 'MYSQL':
				mysql_query("COMMIT", $this->conn);
				break;
 		   case 'POSTGRE':
		   		pg_query($this->conn, "COMMIT");
		   		break;
		}		
	}
	function rollback()
	{
		//$this->conn=$con;
		//Para MYSQL
		switch ($this->gestor) {
		   case 'MYSQL':
				mysql_query("ROLLBACK", $this->conn);
				break;
 		   case 'POSTGRE':
		   		pg_query($this->conn, "ROLLBACK");
		   		break;
		}		
	}
	
	
}
?>