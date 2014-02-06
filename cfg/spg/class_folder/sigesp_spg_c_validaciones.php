<?php
class sigesp_spg_c_validaciones
{
var $ls_sql;
var $is_msg_error;
	
function sigesp_spg_c_validaciones($conn)
{
  require_once("../../shared/class_folder/sigesp_c_seguridad.php");	      
  require_once("../../shared/class_folder/class_funciones.php");		  
  require_once("../../shared/class_folder/class_mensajes.php");
  $this->io_funcion = new class_funciones();
  $this->seguridad = new sigesp_c_seguridad();		  
  $this->io_sql= new class_sql($conn);
  $this->io_msg= new class_mensajes();		
}
 
function uf_activar_validacion($as_codemp,$ai_chkvalidacion,$as_ctaspgrec,$as_ctaspgced,$aa_seguridad) 
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_activar_validacion
//	          Access:  public
//	       Arguments: 
//       $as_codemp :  Código de la Empresa
// $ai_chkvalidacion:  Valor que indica si está o no activo la validación 
//     $as_ctaspgrec:  Código de las Cuentas Receptoras
//     $as_ctaspgrec:  Código de las Cuentas Cedentes
//     $aa_seguridad:  Arreglo cargado con la información relacionada al
//                     nombre de la ventana,nombre del usuario etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de actualizar en la tabla sigesp_empresa la configuracion de la Validacion 
//                     para los Traspasos Presupuestarios 
//     Elaborado Por:  Ing. Arnaldo Suárez.
// Fecha de Creación:  10/09/2008       Fecha Última Actualización:
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
  $ls_sql = " UPDATE sigesp_empresa SET estvalspg = ".$ai_chkvalidacion.", ctaspgrec = '".$as_ctaspgrec."', ctaspgced = '".$as_ctaspgced."' ".
            "   WHERE codemp = '".$as_codemp."'";						
  $rs_data = $this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->SIGESP_SPG_C_VALIDACIONES; METODO->uf_activar_validacion; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 } 
  else
	 {
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento="UPDATE";
	   if($ai_chkvalidacion ==0)
	   {
	    $ls_explicacion = " Desactivó";
	   }
	   elseif($ai_chkvalidacion ==1)
	   {
	    $ls_explicacion = " Activó";
	   }
	   $ls_descripcion =$ls_explicacion." en SPG la Validacion para las Modificaciones Presupuestarias, colocando como Partidas Cedentes: ".$as_ctaspgced ." y ".
	                    " como Partidas Receptoras: ".$as_ctaspgrec;
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               ////////////////////////////
       $lb_valido=true;
	 }
return $lb_valido;
}

function uf_cambiar_validacion($as_codemp,$ai_chkvalidacion,$aa_seguridad) 
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_cambiar_validacion
//	          Access:  public
//	       Arguments: 
//       $as_codemp :  Código de la Empresa
// $ai_chkvalidacion:  Valor que indica si está o no activo la validación 
//     $aa_seguridad:  Arreglo cargado con la información relacionada al
//                     nombre de la ventana,nombre del usuario etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de cambiar en la tabla sigesp_empresa la configuracion de la Validacion 
//                     para los Traspasos Presupuestarios 
//     Elaborado Por:  Ing. Arnaldo Suárez.
// Fecha de Creación:  10/09/2008       Fecha Última Actualización:
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
  $ls_sql = " UPDATE sigesp_empresa SET estvalspg = ".$ai_chkvalidacion." ".
            "   WHERE codemp = '".$as_ctaspgced."'";
  $rs_data = $this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->SIGESP_SPG_C_VALIDACIONES; METODO->uf_cambiar_validacion; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 } 
  else
	 {
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento="UPDATE";
	   if($ai_chkvalidacion ==0)
	   {
	    $ls_explicacion = " Desactivó";
	   }
	   elseif($ai_chkvalidacion ==1)
	   {
	    $ls_explicacion = " Activó";
	   }
	   $ls_descripcion ="Cambió en SPG la Validacion para las Modificaciones Presupuestarias, ".$ls_explicacion." la validación ";
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               ////////////////////////////
       $lb_valido=true;
	 }
return $lb_valido;
}

function uf_obtener_validacion($as_codemp,&$ai_chkvalidacion,&$as_ctaspgrec,&$as_ctaspgced) 
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_obtener_validacion
//	          Access:  public
//	       Arguments: 
//       $as_codemp :  Código de la Empresa
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de obtener los valores de la validacion
//                     para los Traspasos Presupuestarios 
//     Elaborado Por:  Ing. Arnaldo Suárez.
// Fecha de Creación:  10/09/2008       Fecha Última Actualización:
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
  $ls_sql = " SELECT   estvalspg, ctaspgrec, ctaspgced FROM sigesp_empresa ".
            "   WHERE codemp = '".$as_codemp."'";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->SIGESP_SPG_C_VALIDACIONES; METODO->uf_obtener_validacion; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 } 
  else
	 {
	  	while(!$rs_data->EOF)
		{
		 $ai_chkvalidacion = $rs_data->fields["estvalspg"];
		 $as_ctaspgced     = $rs_data->fields["ctaspgced"];
		 $as_ctaspgrec     = $rs_data->fields["ctaspgrec"];
		 $rs_data->MoveNext();
		}   
       $lb_valido=true;
	 }
return $lb_valido;
}


}//Fin de la Clase.
?> 