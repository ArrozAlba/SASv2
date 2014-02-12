<?php
require_once("../shared/class_folder/class_sql.php");
class sigesp_scv_c_transporte
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_scv_c_transporte()
	{
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_funciones_db.php"); 
		$this->io_msg=new class_mensajes();
		$in=new sigesp_include();
		$con=$in->uf_conectar();
		$this->io_sql=      new class_sql($con);
		$this->seguridad=   new sigesp_c_seguridad();
		$this->io_funcion = new class_funciones();
		$this->io_funciondb= new class_funciones_db($con);
		
	}
	
	function uf_scv_select_transporte($as_codemp,$as_codtra,$as_codtiptra)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_transporte
		//         Access: public 
		//      Argumento: $as_codemp    //codigo de empresa 
		//                 $as_codtra    //codigo de transporte
		//                 $as_codtiptra //codigo de tipo de transporte
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe un determinado transporte en la tabla scv_transporte
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/08/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM scv_transportes  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND codtra='".$as_codtra."'" .
				  "   AND codtiptra='".$as_codtiptra."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->transporte MÉTODO->uf_siv_select_transporte ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
		}
		return $lb_valido;
	}  // end uf_siv_select_transporte

	function uf_scv_load_transporte($as_codemp,$as_codtiptra,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_transporte
		//         Access: public 
		//      Argumento: $as_codemp    //codigo de empresa 
		//                 $as_codtiptra //codigo de tipo de transporte
		//                 $ai_totrows   //total de filas del grid
		//                 $ao_object    //arreglo de objetos
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene los datos de un determinado tipo transporte en la tabla scv_transporte
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/08/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM scv_transportes  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND codtiptra='".$as_codtiptra."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->transporte MÉTODO->uf_siv_select_transporte ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codtra= $row["codtra"];
				$ls_dentra= $row["dentra"];
				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<a href=\"javascript: ue_actualizar('$ls_codtra','$ls_dentra');\"><input  name=txtcodtra".$ai_totrows."  type=text id=txtcodtra".$ai_totrows."  class=sin-borde size=10 maxlength=3 value='".$ls_codtra."' readonly></a>";
				$ao_object[$ai_totrows][2]="<a href=\"javascript: ue_actualizar('$ls_codtra','$ls_dentra');\"><input  name=txtdentra".$ai_totrows."  type=text id=txtdentra".$ai_totrows."  class=sin-borde size=60 maxlength=254 value='".$ls_dentra."' readonly></a>";
				$ao_object[$ai_totrows][3]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";			
			
			}
			if($ai_totrows==0)
			{
				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input  name=txtcodtra".$ai_totrows."  type=text id=txtcodtra".$ai_totrows."  class=sin-borde size=10 maxlength=3   readonly>";
				$ao_object[$ai_totrows][2]="<input  name=txtdentra".$ai_totrows."  type=text id=txtdentra".$ai_totrows."  class=sin-borde size=60 maxlength=254 readonly>";
				$ao_object[$ai_totrows][3]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";			
			}
		}
		return $lb_valido;
	}  // end uf_siv_select_transporte
	
	function  uf_scv_insert_transporte($as_codemp,$as_codtra,$as_codtiptra,$as_dentra,$as_tartra,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_insert_transporte
		//         Access: public 
		//      Argumento: $as_codemp    //codigo de empresa 
		//				   $as_codtra    //codigo de transporte
		//				   $as_codtiptra //codigo de tipo de transporte
		//				   $as_dentra    //denominacion de transporte
		//				   $as_tartra    //tarifa de  transporte
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un registro de transporte en la tabla scv_transporte
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/08/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codtraaux = $as_codtra;
		$as_dentra=trim($as_dentra);
		$as_codtra=$this->io_funciondb->uf_generar_codigo(true,$as_codemp,'scv_transportes','codtra');
		$this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO scv_transportes (codemp,codtra,codtiptra,dentra,tartra) ".
				  "     VALUES ('".$as_codemp."','".$as_codtra."','".$as_codtiptra."','".$as_dentra."','".$as_tartra."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();
			if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
			{
				$lb_valido=$this->uf_scv_insert_transporte($as_codemp,$as_codtra,$as_codtiptra,$as_dentra,$as_tartra,$aa_seguridad);
			}
			else
			{
				$this->io_msg->message("CLASE->transporte MÉTODO->uf_siv_insert_transporte ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			}
			
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Transporte ".$as_codtra." Asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				
				if($lb_valido)
				{
					if($ls_codtraaux!=$as_codtra)
					{
						$this->io_msg->message("Se Asigno el Código de Transporte: ".$as_codtra);
					}
					$this->io_sql->commit();
				}
				else
				{
					$this->io_sql->rollback();
				}
		}
		return $lb_valido;
	} //end uf_siv_insert_transporte

	function uf_scv_update_transporte($as_codemp,$as_codtra,$as_codtiptra,$as_dentra,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_update_transporte
		//         Access: public  
		//      Argumento: $as_codemp    //codigo de empresa
		//				   $as_codtra    //codigo de transporte
		//				   $as_codtiptra //codigo de tipo de transporte
		//				   $as_dentra    //denominacion de transporte
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza un registro de transporte en la tabla scv_transportes
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/08/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 	$lb_valido=true;
		$ls_sql = " UPDATE scv_transportes".
				  "    SET   codtiptra='". $as_codtiptra ."', dentra='". $as_dentra ."'".
				  "  WHERE codemp='" . $as_codemp ."'".
				  "    AND codtra='" . $as_codtra ."'";
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->viaticos MÉTODO->uf_scv_update_transporte ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el transporte ".$as_codtra." Asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	    return $lb_valido;
	} // end  function uf_scv_update_transporte

	function uf_scv_delete_transporte($as_codemp,$as_codtra,$as_codtiptra,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_delete_almacen
		//         Access: public (sigesp_siv_d_almacen)
		//      Argumento: $as_codemp    //codigo de empresa 
		//				   $as_codtra    //codigo de transporte
		//				   $as_codtiptra //codigo de tipo de transporte
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un registro de transporte de la tabla scv_transporte
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/08/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe= $this->uf_check_relaciones($as_codemp,$as_codtra);
		if($lb_existe)
		{
			$lb_valido=false;
		}
		else
		{
			$ls_sql = " DELETE FROM scv_transportes".
					  "  WHERE codemp= '".$as_codemp. "'".
				      "    AND codtra= '".$as_codtra. "'".
				      "    AND codtiptra= '".$as_codtiptra. "'"; 
			$this->io_sql->begin_transaction();	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->transporte MÉTODO->uf_scv_delete_transporte ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Transporte ".$as_codtra." Asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}			
		return $lb_valido;
	} //end function uf_siv_delete_almacen

	function uf_scv_select_solicitud($as_codemp,$as_codtra,$as_codtiptra)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_select_solicitud
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa 
		//				   $as_codtra    // codigo de transporte
		//				   $as_codtiptra // codigo de tipo de transporte
		//	      Returns: Retorna un Booleano
		//    Description: Funcion verifica si existen solicitudes asociadas a un registro de transporte en scv_solicitud
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/08/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM scv_solicitud  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND codtra='".$as_codtra."'".
				  "   AND codtiptra='".$as_codtiptra."'";
		$li_row=$this->io_sql->select($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->transporte MÉTODO->uf_scv_select_solicitud ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($li_row))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
			}
		}
		$this->io_sql->free_result($li_row);
		return $lb_valido;
	} // end function uf_scv_select_solicitud
	function uf_check_relaciones($as_codemp,$as_codtar)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	      Metodo:  uf_check_relaciones
		//	      Access:  public
		// 	   Arguments:  $as_codemp // codigo de empresa.
		//     			   $as_codtar // codigo de tarifa
		//	      Returns: Retorna un Booleano
		//	  Description: Función que se encarga de verificar si existen tablas relacionadas al Código de la Dependencia. 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/08/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$ls_sql="SELECT codasi".
				"  FROM scv_dt_asignaciones".
				" WHERE codemp='".$as_codemp."'".
				"   AND proasi='TRP'".
				"   AND codasi='".$as_codtar."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		  {
			$lb_valido=false;
			$this->io_msg->message("CLASE->sigesp_scv_c_tarifas METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  }
		else
		  {
			if($row=$this->io_sql->fetch_row($rs_data))
			  {
				$lb_valido=true;
				$this->is_msg_error="El Transporte no puede ser eliminado, posee registros asociados a otras tablas";
			  }
			else
			  {
				$lb_valido=false;
				$this->is_msg_error="Registro no encontrado";
			  }
		}
		return $lb_valido;	
	}
	
} //end class sigesp_scv_c_transporte
?>
