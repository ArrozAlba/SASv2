<?php
class sigesp_scb_c_disponibilidad_financiera{

  function sigesp_scb_c_disponibilidad_financiera($as_path)
  {
    require_once($as_path."shared/class_folder/class_sql.php");
	require_once($as_path."shared/class_folder/class_mensajes.php");
	require_once($as_path."shared/class_folder/sigesp_include.php");
	require_once($as_path."shared/class_folder/class_funciones.php");
	require_once($as_path."shared/class_folder/sigesp_c_seguridad.php");
	
	$io_include = new sigesp_include();
	$ls_conect  = $io_include->uf_conectar();
	$this->io_sql = new class_sql($ls_conect);
	$this->io_msg = new class_mensajes();
	$this->ls_codemp    = $_SESSION["la_empresa"]["codemp"];
	$this->io_funcion   = new class_funciones();
	$this->io_seguridad = new sigesp_c_seguridad();
  }

  function uf_update_validacion_disponibilidad($as_tipvalfin,$aa_seguridad){
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_update_validacion_disponibilidad
  //		   Access: private
  //	    Arguments: $as_tipvalfin = Tipo de Validación Financiera, N= No Verificar; A=Advertir y Permitir; .
  //	  Description: Actualiza la información de la Validacion de la Disponibilidad Financiera.
  //	   Creado Por: Ing. Nestor Falcón.
  //   Fecha Creación: 13/04/2009. 							Fecha Última Modificación : 13/04/2009.
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$lb_valido = true;
	$this->io_sql->begin_transaction();
	$ls_sql = "UPDATE sigesp_empresa 
	              SET estvaldisfin = '".$as_tipvalfin."' 
				WHERE codemp = '".$_SESSION["la_empresa"]["codemp"]."'";
	$rs_data = $this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   {
	     $this->io_sql->rollback();
		 $this->io_msg->message("Error en Actualizacion. Class->sigesp_scb_c_disponibilidad_financiera;Método->uf_update_validacion_disponibilidad. !!!");
	   }
    else
	   {
	     $this->io_sql->commit();
		 $this->io_msg->message("Registro Actualizado !!!");
		 $ls_descripcion = "Actualizó a ".$as_tipvalfin." el Estatus de Validación Financiera, Asociado a la empresa ".$this->ls_codemp;
		 $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],"UPDATE",$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
	   }
  }

  function uf_load_tipo_validacion(){
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_load_tipo_validacion
  //		   Access: private
  //	  Description: Carga el tipo de validacion a utilizar.
  //	   Creado Por: Ing. Nestor Falcón.
  //   Fecha Creación: 13/04/2009. 							Fecha Última Modificación : 13/04/2009.
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$ls_tipvaldis = "N";
	$ls_sql = "SELECT estvaldisfin 
	             FROM sigesp_empresa 
				WHERE codemp = '".$_SESSION["la_empresa"]["codemp"]."'";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		 $this->io_msg->message("Error en Actualizacion. Class->sigesp_scb_c_disponibilidad_financiera;Método->uf_update_validacion_disponibilidad. !!!");
	   }
    else
	   {
	     if ($row=$this->io_sql->fetch_row($rs_data))
		    {
			  $ls_tipvaldis = $row["estvaldisfin"];
			}
	   }
    return $ls_tipvaldis;
  }
}
?>