<?php
class sigesp_spi_c_transferencia
{
	var $is_msg_error;
	var $io_sql;
	var $io_include;
	var $io_int_scg;
	var $io_int_spg;
	var $io_msg;
	var $io_function;
	var $is_codemp;
	var $is_procedencia;
	var $is_comprobante;
	var $id_fecha;
	var $ii_tipo_comp;
	var $is_descripcion;
	var $is_tipo;
function sigesp_spi_c_transferencia($as_hostname, $as_login, $as_password,$as_database,$as_gestor)
{
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_fecha.php");	
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sigesp_int.php");
	require_once("../shared/class_folder/class_sigesp_int_int.php");	
	require_once("../shared/class_folder/class_sigesp_int_scg.php");
	require_once("../shared/class_folder/class_sigesp_int_spg.php");
	require_once("../shared/class_folder/class_sigesp_int_spi.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$this->bddestino=$as_database;
    $this->io_include=new sigesp_include();
	$this->io_connect_destino=$this->io_include->uf_conectar_otra_bd($as_hostname, $as_login, $as_password,$as_database,$as_gestor);	
	$this->io_function=new class_funciones();	
	$this->sig_int=new class_sigesp_int();
	$this->sig_int->io_sql=new class_sql($this->io_connect_destino);
	
	$this->sig_int_int=new class_sigesp_int_int();
	$this->sig_int_int->io_sql=new class_sql($this->io_connect_destino);
	$this->sig_int_int->int_spg->io_sql=new class_sql($this->io_connect_destino);
	$this->sig_int_int->int_scg->io_sql=new class_sql($this->io_connect_destino);
	$this->sig_int_int->int_spi->io_sql=new class_sql($this->io_connect_destino);
		
    $this->io_fecha=new class_fecha();
	$this->io_connect=$this->io_include->uf_conectar();
	$this->io_sql=new class_sql($this->io_connect);
	$this->io_sql_destino=new class_sql($this->io_connect_destino);
	$this->io_msg = new class_mensajes();
	$this->io_int_spg=new class_sigesp_int_spg();
	$this->io_int_spg->io_sql=new class_sql($this->io_connect_destino);
	$this->io_int_spg->sig_int->io_sql=new class_sql($this->io_connect_destino);
	$this->io_int_spg->io_int_scg->io_sql=new class_sql($this->io_connect_destino);
	$this->io_int_spi=new class_sigesp_int_spi();
	$this->io_int_spi->io_sql=new class_sql($this->io_connect_destino);	
	$this->io_int_spi->sig_int->io_sql=new class_sql($this->io_connect_destino);
	$this->io_int_spi->io_int_scg->io_sql=new class_sql($this->io_connect_destino);	
	$this->io_int_scg=new class_sigesp_int_scg();
	$this->io_int_scg->io_sql=new class_sql($this->io_connect_destino);
	$this->is_msg_error="";
	$this->io_seguridad= new sigesp_c_seguridad;
	$this->dts_empresa=$_SESSION["la_empresa"];
	$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
	$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
	$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
}
/**********************************************************************************************************************************/

//------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_codempresa_bd($as_hostname, $as_login, $as_password,$as_database,$as_gestor)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Funcion       uf_obtener_codempresa_bd
	//	Access        public
	//	Arguments	  $as_hostname  // hostname para conectar con la Base de Datos
	//                $as_login     // login para conectar con la Base de Datos
	//                $as_password  // password o clave para conectac con la Base de Datos
	//                $as_database  // nombre de la Base Datos con la que se quiere conectar
	//                $as_gestor    // nombre del gestor que maneja la Base de Datos
	//                $as_codempdes // Código de la Empresa destino
	//	Returns	      lb_valido. Retorna una variable booleana
	//	Description   Devuelve el Código de Empresa de la Base de Datos referenciada
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql="SELECT codemp ". 
				"  FROM sigesp_empresa ";
		$ls_codemp="";		
		$rs_data   = $this->io_sql_destino->select($ls_sql);
		if ($rs_data===false)
		   {
			  $this->io_msg->message($this->io_function->uf_convertirmsg($io_sql_destino->message));		 
		   }
		else
		   {
			 $li_numrows = $this->io_sql_destino->num_rows($rs_data);
			 if ($li_numrows>0)
				{
				 $lb_valido=true;
				 if ($row=$this->io_sql_destino->fetch_row($rs_data))
				 {
				  $ls_codemp = $row["codemp"];
				 }                  
				 $this->io_sql_destino->free_result($rs_data);	
				}
		   }
	return $ls_codemp;
	}
//------------------------------------------------------------------------------------------------------------------------------------
	function uf_cerrar_presupuesto($as_codempdes,$ai_cerrar)
	{
		  $lb_valido=false;
		  $ls_sql    = " UPDATE sigesp_empresa set estciespi = ".$ai_cerrar.", estciespg = ".$ai_cerrar." where codemp = '".$as_codempdes."'";
		  $li_result = $this->io_sql_destino->execute($ls_sql);
		  if($li_result===false)
		  {
		    $this->is_msg_error="Error en Transferencia->uf_cerrar_presupuesto".
				                       $this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql_destino->rollback();						   
		  }
		  else  
		  {  
		    $this->io_sql_destino->commit();
			$lb_valido = true;
		  }

	 return $lb_valido;
	}//fin de uf_cerrar_presupuesto
//------------------------------------------------------------------------------------------------------------------------------------
    
}
?>