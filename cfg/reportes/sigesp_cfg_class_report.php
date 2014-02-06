<?php

require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_fecha.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_sigesp_int.php");
require_once("../../shared/class_folder/class_sigesp_int_scg.php");
require_once("../../shared/class_folder/class_sigesp_int_spg.php");

class sigesp_cfg_class_report
{
	//conexion	
	//Instancia de la clase funciones.
    var $is_msg_error;
	var $dts_reporte;
	var $obj="";
	var $SQL;
	var $con;
	var $fun;	
	var $io_msg;
	//var $sigesp_int_spg;
	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_cfg_class_report()
	{
		$this->fun=new class_funciones() ;
		$this->siginc=new sigesp_include();
		$this->con=$this->siginc->uf_conectar();
		$this->SQL=new class_sql($this->con);		
		$this->obj=new class_datastore();
		$this->dts_reporte=new class_datastore();
		$this->io_msg=new class_mensajes();
	}// end function sigesp_cfg_class_report
	//-----------------------------------------------------------------------------------------------------------------------------------
function uf_select_unidad_tributaria()
{	
	
	$lb_valido=true;
	$ls_sql = "SELECT * FROM sigesp_unidad_tributaria ";	
    $li_select =$this->SQL->select($ls_sql);	                                                                                                                                                                                
	if($li_select===false)
	 {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->sigesp_cfg_class_report MÉTODO->uf_select_unidad_tributaria ERROR->".$this->fun->uf_convertirmsg($this->SQL->message));
   	 }
      else
		{
			if($row=$this->SQL->fetch_row($li_select))
			{
				$this->dts_reporte->data=$this->SQL->obtener_datos($li_select);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->SQL->free_result($li_select);
		}		
		return $lb_valido;
	}// end function uf_select_evaluacio_desemp
}
?>