<?php
class sigesp_include
{
	var $msg;
	function sigesp_include()
	{
		require_once("class_mensajes.php");
		require_once("class_sql.php");
		require_once("adodb/adodb.inc.php");
		$this->msg=new class_mensajes();	
	}

	function uf_conectar () 
	{
		$conec=&ADONewConnection($_SESSION["ls_gestor"]);
		//$conec->PConnect($_SESSION["ls_hostname"],$_SESSION["ls_login"],$_SESSION["ls_password"],$_SESSION["ls_database"]); 
		$conec->Connect($_SESSION["ls_hostname"],$_SESSION["ls_login"],$_SESSION["ls_password"],$_SESSION["ls_database"]); 
		
		//$conec->debug = true;
		$conec->SetFetchMode(ADODB_FETCH_ASSOC);
		if($conec===false)
		{
			$this->msg->message("No pudo conectar al servidor de base de datos, contacte al administrador del sistema");				
			exit();
		}
		return $conec;
	}
	
  function uf_conectar_otra_bd ($as_hostname, $as_login, $as_password,$as_database,$as_gestor) 
	{
		$conec=&ADONewConnection($as_gestor);
			 
		$conec->Connect($as_hostname, $as_login, $as_password,$as_database); 
		
		$conec->SetFetchMode(ADODB_FETCH_ASSOC);
		if($conec===false)
		{
			$this->msg->message("No pudo conectar al servidor de base de datos, contacte al administrador del sistema");				
			exit();
		}
		return $conec;
	}
}
?>
