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
		$servidor=$_SESSION["ls_hostname"].':'.$_SESSION["ls_port"];
		$conec->Connect($servidor,$_SESSION["ls_login"],$_SESSION["ls_password"],$_SESSION["ls_database"]); 

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
	
	function uf_obtener_parametros_conexion($as_path,$as_database,&$as_hostname,&$as_login,&$as_password,&$as_gestor)
	{
		require_once($as_path."sigesp_config.php");
		$as_hostname="";
		$as_login="";
		$as_password="";
		$as_gestor="";
		for($li_i=1;$li_i<=$i;$li_i++)
		{
			if($empresa["database"][$li_i]==$as_database)
			{
				$as_hostname=$empresa["hostname"][$li_i];
				$as_login=$empresa["login"][$li_i];
				$as_password=$empresa["password"][$li_i];
				$as_gestor=$empresa["gestor"][$li_i];
			}	
		}
	}
}
?>
