<?php
class sigesp_scg_c_casamientopresupuesto
{
	 var $int_scg;
	 var $io_sql;
	 var $dat;
	 var $msg;
	 var $fun;
	 var $int_spg;
	 var $is_msg_error;
	 var $io_seguridad;
	 
	function sigesp_scg_c_casamientopresupuesto()
	{
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_sigesp_int_scg.php");
		require_once("../../shared/class_folder/class_sigesp_int_spg.php");
	    require_once("../../shared/class_folder/class_funciones.php");
	    require_once("../../shared/class_folder/sigesp_c_seguridad.php");
 	    require_once("../../shared/class_folder/sigesp_c_check_relaciones.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_conect          = new sigesp_include();
        $conn               = $io_conect->uf_conectar ();
		$this->msg          = new class_mensajes();
		$this->fun          = new class_funciones();
		$this->int_scg      = new class_sigesp_int_scg();	
		$this->int_spg      = new class_sigesp_int_spg();
		$this->io_sql       = new class_sql($conn );
		$this->io_chkrel    = new sigesp_c_check_relaciones($conn);
		$this->dat          = $_SESSION["la_empresa"];
		$this->io_seguridad = new sigesp_c_seguridad();
	}


  function uf_procesar_cuentas($ls_sig_cuenta,$ls_scg_cuenta,$la_security)
  {
		$lb_valido=true;							
		$lb_valido=$this->uf_buscar_cuenta($this->dat["codemp"],$ls_sig_cuenta);
		if ($lb_valido)
		   {
			 $lb_valido=$this->uf_scg_update_cuenta($this->dat["codemp"],$ls_sig_cuenta,$ls_scg_cuenta);
			 if ($lb_valido)
			    {
				  $this->is_msg_error="Denominación Actualizada !!!";
				  $this->int_scg->io_sql->commit();
				  /////////////////////////////////         SEGURIDAD               /////////////////////////////
				  $ls_evento="UPDATE";
				  $ls_desc_event="Actualizo la cuenta del catalogo de recursos y egresos de la onapre".$ls_sig_cuenta.", asociada a la cuenta contable ".$ls_scg_cuenta; 
				  $ls_variable= $this->io_seguridad->uf_sss_insert_eventos_ventana($la_security[1],$la_security[2],$ls_evento,$la_security[3],$la_security[4],$ls_desc_event);
				  ////////////////////////////////         SEGURIDAD               //////////////////////////////
			 	
			    }
			 else
				{
				  $this->int_scg->io_sql->rollback();
				  $this->is_msg_error="Error al guardar cuenta ";
				}
		   }
		else
		   {
			 $lb_valido=$this->uf_scg_insert_cuenta($this->dat["codemp"],$ls_sig_cuenta,$ls_scg_cuenta);
			 if ($lb_valido)
			    {
			      $this->int_scg->io_sql->commit();
				  $this->is_msg_error="Registro guardado !!!";
				  /////////////////////////////////         SEGURIDAD               /////////////////////////////
			      $ls_evento="INSERT";
			      $ls_desc_event="Inserto la cuenta ".$ls_sig_cuenta.", asociada a la cuenta contable ".$ls_scg_cuenta; 
			      //////////////////////////////         SEGURIDAD               /////////////////////////////
			    }
			 else
			    {
				  $this->int_scg->io_sql->rollback();
				  $this->is_msg_error="Error al guardar cuenta ";
			    }
		   }
		return $lb_valido;
	}

	function uf_procesar_delete_cuenta($ls_sig_cuenta,$ls_scg_cuenta,$aa_security)
	{
		$ls_codemp    = $this->dat["codemp"];
		$lb_valido    = false;
		$lb_valido=$this->uf_scg_eliminar_cuenta($this->dat["codemp"],$ls_sig_cuenta,$ls_scg_cuenta);
		if($lb_valido)
		{
		   $this->int_spg->io_sql->commit();
		   $this->is_msg_error="Registro Eliminado";
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_desc_event="Elimino la cuenta ".$ls_sig_cuenta." asociada a la cuenta contable ".$ls_scg_cuenta;
			$ls_variable= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_security[1],$aa_security[2],$ls_evento,$aa_security[3],$aa_security[4],$ls_desc_event);
			////////////////////////////////         SEGURIDAD               //////////////////////////////
		}
		else
		{
		   $this->int_spg->io_sql->rollback();
		   $this->is_msg_error=$this->int_spg->is_msg_error;
		}
		return $lb_valido;
	}
	
		
	function uf_buscar_cuenta($as_codemp,$ls_sig_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_cuenta
		//         Access: public 
		//      Argumento:  
		//                
		//	      Returns: Retorna un Booleano
		//    Description: 
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 08/08/08 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT sig_cuenta 
		             FROM scg_casa_presu
				    WHERE codemp='".$as_codemp."'
				      AND trim(sig_cuenta)='".trim($ls_sig_cuenta)."'";
 		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->msg->message("CLASE->sigesp_scg_c_casamientopresupuesto MÉTODO->uf_buscar_cuenta ERROR->".$this->fun->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{ 
		  if ($row=$this->io_sql->fetch_row($rs_data))
			 {
			   $lb_valido=true;
			 }
		}
		return $lb_valido;
	}  // end function uf_buscar_cuenta
	
	function uf_scg_insert_cuenta($ls_codemp,$ls_sig_cuenta,$ls_scg_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_insert_cuenta
		//         Access: public
		//      Argumento: $ls_codemp    //codigo de empresa 					
		//                 $ls_sig_cuenta    // partida del catalogo de recursos y egresos de la ONAPRE					
		//                 $ls_scg_cuenta    // cuenta contable			
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta el casamiento de las cuentas del catalogo de recursos y egresos de la ONAPRE
		//	   Creado Por: Ing. GLoriely Fréitez
		// Fecha Creación: 08/08/08							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sig_cuenta=trim($ls_sig_cuenta);
		$ls_sql="INSERT INTO scg_casa_presu (codemp,sig_cuenta,sc_cuenta) ".
				"     VALUES ('".$ls_codemp."','".$ls_sig_cuenta."','".$ls_scg_cuenta."'); ";  
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->msg->message("CLASE->sigesp_scg_c_casamientopresupuesto MÉTODO->uf_scg_insert_cuenta ERROR->".$this->fun->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
		   $lb_valido=true;
		}
		return $lb_valido;
	} // end  function uf_scg_insert_cuenta
	
	function uf_scg_update_cuenta($ls_codemp,$ls_sig_cuenta,$ls_scg_cuenta) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_update_cuenta
		//         Access: private
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_numsol // numero de la solicitud de ejecución presupuestaria
		//                 $as_estsep // estatus en que se va a colocar la SEP
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza la cuenta contable de la partida seleccionada
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 16/02/2006	 								Fecha Última Modificación:	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql= "UPDATE scg_casa_presu SET sc_cuenta='".$ls_scg_cuenta."'".
				  " WHERE codemp='".$ls_codemp."'".
				  "   AND sig_cuenta='".$ls_sig_cuenta."'"; 
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->msg->message("CLASE->sigesp_scg_c_casamientopresupuesto MÉTODO->uf_scg_update_cuenta ERROR->".$this->fun->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
	  return $lb_valido;
	} // end function uf_scg_update_cuenta
	
	function uf_scg_eliminar_cuenta($ls_codemp,$ls_sig_cuenta,$ls_scg_cuenta) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_eliminar_cuenta
		//         Access: private
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_numsol // numero de la solicitud de ejecución presupuestaria
		//                 $as_estsep // estatus en que se va a colocar la SEP
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina la cuenta contable de la cuenta elegida del catalogo de la ONAPRE seleccionada
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 16/02/2006	 								Fecha Última Modificación:	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql= "DELETE FROM scg_casa_presu ".
				  " WHERE codemp='".$ls_codemp."'".
				  "   AND sig_cuenta='".$ls_sig_cuenta."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->msg->message("CLASE->uf_scg_eliminar_cuenta MÉTODO->uf_scg_update_cuenta ERROR->".$this->fun->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
	  return $lb_valido;
	} // end function uf_scg_eliminar_cuenta


}
?>
