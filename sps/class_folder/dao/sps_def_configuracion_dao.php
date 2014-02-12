<?php
 /* ********************************************************************************************************************************
          Compañia : SOFTBRI C.A.
    Nombre Archivo : sps_def_configuracion_dao.php
   Tipo de Archivo : Archivo tipo DAO ( DATA ACCESS OBJECT )
             Autor : Ing. Ma. Alejandra Roa  
	   Descripción : Esta clase maneja el acceso de datos de la tabla de configuracion del sistema
    *********************************************************************************************************************************/

require_once("../../../sps/class_folder/utilidades/class_dao.php");
require_once("../../../shared/class_folder/sigesp_c_seguridad.php");

class sps_def_configuracion_dao extends class_dao
{	
  public function sps_def_configuracion_dao() // Contructor de la clase
  {
    $this->class_dao("sps_configuracion");
    $this->io_seguridad= new sigesp_c_seguridad();
    
    if(array_key_exists("la_empresa",$_SESSION))
	{
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->ls_logusr=$_SESSION["la_logusr"];
	}
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getData
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de registros del DAO
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getData( $ps_orden="", &$pa_datos="" )  
  {
	$lb_valido = false;
 	$ls_sql    = "SELECT * FROM ".$this->as_tabla." ";
	$rs_data   = $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en getData de la tabla ".$this->as_tabla );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_datos=$this->io_sql->obtener_datos($rs_data); 
	}
	return $lb_valido;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : get_sc_cuenta
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de registros de las cuentas contables del DAO
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function get_sc_cuenta( $as_sc_cuenta="", $as_denominacion="", $ps_orden="", &$pa_datos="" )  
  {
	$lb_valido = false;
	$ps_orden = "ORDER BY sc_cuenta, denominacion ASC";
	if ($as_sc_cuenta==""){$as_sc_cuenta="'%'";}else{$as_sc_cuenta="'$as_sc_cuenta%'";}
	if ($as_denominacion==""){$as_denominacion="'%'";}else{$as_denominacion="'$as_denominacion%'";}
	$ls_sql    = "SELECT sc_cuenta, denominacion FROM scg_cuentas WHERE sc_cuenta like ".$as_sc_cuenta." AND denominacion like ".$as_denominacion." AND status='C' ".$ps_orden;
	$rs_data   = $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en get_sc_cuenta de la tabla " );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_datos=$this->io_sql->obtener_datos($rs_data); 
	}
	else { $this->io_function_sb->message("Registro no encontrado en la tabla ".$this->as_tabla); }	
	return $lb_valido;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : get_spg_cuenta
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de registros de las cuentas presupuestarias del DAO
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function get_spg_cuenta( $as_spg_cuenta="", $as_denominacion="", $ps_orden="", &$pa_datos="" )  
  {
	$lb_valido = false;
	$ps_orden = "ORDER BY spg_cuenta, denominacion ASC";
	if ($as_spg_cuenta==""){$as_spg_cuenta="'%'";}else{$as_spg_cuenta="'$as_spg_cuenta%'";}
	if ($as_denominacion==""){$as_denominacion="'%'";}else{$as_denominacion="'$as_denominacion%'";}
	$ls_sql    = "SELECT spg_cuenta, denominacion FROM spg_cuentas WHERE spg_cuenta like ".$as_spg_cuenta." AND denominacion like ".$as_denominacion." AND status='C' ".$ps_orden;
	$rs_data   = $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en get_spg_cuenta de la tabla " );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_datos=$this->io_sql->obtener_datos($rs_data); 
	}
	else { $this->io_function_sb->message("Registro no encontrado en la tabla ".$this->as_tabla); }	
	return $lb_valido;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : guardarData
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que guarda la información 
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  public function guardarData( $po_object , $ps_operacion="insertar" )
  {	
    $lb_guardo = false;
	$ls_id = "1";
    
	$ld_porant = str_replace(".", "", $po_object->porant );		
	$ld_porant = str_replace(",", ".", $ld_porant );	
	
   	if ($ps_operacion=="modificar")
	{   
		$ls_sql = " UPDATE ".$this->as_tabla." SET porant=".$ld_porant.",estsue='".$po_object->estsue."',estincbon='".$po_object->estincbon."',sc_cuenta_ps='".$po_object->sc_cuenta_ps."',sig_cuenta_emp_fijo_ps='".$po_object->sig_cuenta_emp_fijo_ps."',sig_cuenta_emp_fijo_vac='".$po_object->sig_cuenta_emp_fijo_vac."',sig_cuenta_emp_fijo_agu='".$po_object->sig_cuenta_emp_fijo_agu."',sig_cuenta_obr_fijo_ps ='".$po_object->sig_cuenta_obr_fijo_ps."',sig_cuenta_obr_fijo_vac='".$po_object->sig_cuenta_obr_fijo_vac."',sig_cuenta_obr_fijo_agu='".$po_object->sig_cuenta_obr_fijo_agu."',sig_cuenta_emp_cont_ps='".$po_object->sig_cuenta_emp_cont_ps."',sig_cuenta_emp_cont_vac='".$po_object->sig_cuenta_emp_cont_vac."',sig_cuenta_emp_cont_agu='".$po_object->sig_cuenta_emp_cont_agu."',sig_cuenta_emp_esp_ps='".$po_object->sig_cuenta_emp_esp_ps."',sig_cuenta_emp_esp_vac='".$po_object->sig_cuenta_emp_esp_vac."',sig_cuenta_emp_esp_agu='".$po_object->sig_cuenta_emp_esp_agu."' WHERE id=".$ls_id;
		$li_guardo = $this->io_sql->execute( $ls_sql );
		
	    if ($li_guardo > 0)
	    {
		   $this->io_sql->commit();
		   $lb_guardo=true;
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion =" Actualizó en la tabla ".$this->as_tabla;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_def_configuracion.html.php",$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
 	    }
	    else
	    {
		   $this->io_sql->rollback();
		   $this->io_function_sb->message($this->io_sql->message);
		   $lb_guardo=false;
 	    }				
	}	
	else
	{
		  $ls_sql = "INSERT INTO ".$this->as_tabla."(id, porant,estsue,estincbon,sc_cuenta_ps,sig_cuenta_emp_fijo_ps,sig_cuenta_emp_fijo_vac,sig_cuenta_emp_fijo_agu,sig_cuenta_obr_fijo_ps,sig_cuenta_obr_fijo_vac,sig_cuenta_obr_fijo_agu,sig_cuenta_emp_cont_ps,sig_cuenta_emp_cont_vac,sig_cuenta_emp_cont_agu,sig_cuenta_emp_esp_ps,sig_cuenta_emp_esp_vac,sig_cuenta_emp_esp_agu )
		             VALUES ('".$ls_id."','".$ld_porant."','".$po_object->estsue."','".$po_object->estincbon."','".$po_object->sc_cuenta_ps."','".$po_object->sig_cuenta_emp_fijo_ps."','".$po_object->sig_cuenta_emp_fijo_vac."','".$po_object->sig_cuenta_emp_fijo_agu."','".$po_object->sig_cuenta_obr_fijo_ps."','".$po_object->sig_cuenta_obr_fijo_vac."','".$po_object->sig_cuenta_obr_fijo_agu."','".$po_object->sig_cuenta_emp_cont_ps."','".$po_object->sig_cuenta_emp_cont_vac."','".$po_object->sig_cuenta_emp_cont_agu."','".$po_object->sig_cuenta_emp_esp_ps."','".$po_object->sig_cuenta_emp_esp_vac."','".$po_object->sig_cuenta_emp_esp_agu."' )";	 			 
		  $li_guardo = $this->io_sql->execute( $ls_sql );	
		 
		  if ($li_guardo > 0)
		  {
			$this->io_sql->commit();
			$lb_guardo=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion =" Insertó en la tabla ".$this->as_tabla;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_def_configuracion.html.php",$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		  }
		  else
		  {
			$this->io_sql->rollback();
			$lb_guardo=false;
		  }				
	} 
	if ($lb_guardo) 
	{ 
		$this->io_function_sb->message("Los datos fueron registrados."); }	
	else 
	{ 
		$this->io_function_sb->message("Los datos no fueron registrados."); }	
	return $lb_guardo;
  } 

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getConfiguracion
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna la configuracion del sueldos si es salario base o Integral 
  //    Arguments : 
  //                
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  function getConfiguracion( &$pa_datos )  
  {       
  	$lb_valido = false;
 	$ls_sql    = "SELECT estsue FROM ".$this->as_tabla." WHERE id='1' ORDER BY estsue ASC ";			  
	$rs_data   = $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en getConfiguracion ");
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_datos =$this->io_sql->obtener_datos($rs_data);
		 $pa_datos =$this->io_function_sb->uf_sort_array($pa_datos); 
	}
	else { $this->io_function_sb->message("Registro no encontrado en la tabla ".$this->as_tabla); }	
	return $lb_valido;
  }
} // fin de class sps_def_configuracion_dao
?>
