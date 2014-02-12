<?php
class sigesp_scv_c_tarifasxdistancias
{
	var $ls_sql;
	var $is_msg_error;
		
	function sigesp_scv_c_tarifasxdistancias($con)
	{
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_funciones_db.php");
		$this->io_fundb= new class_funciones_db($con);
		$this->seguridad = new sigesp_c_seguridad();		  
		$this->io_funcion = new class_funciones();
		$this->io_sql= new class_sql($con);
		$this->io_msg= new class_mensajes();		
		
	}
	
	function uf_scv_select_tarifa($as_codemp,$as_codtar) 
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_select_tarifa
		//	          Access:  public
		//	       Arguments:  $as_codemp    // código de empresa.
		//        			   $as_codtar    // código de tarifa
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de verificar la existencia de una tarifa por kilometraje
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  09/10/2006      
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_sql=" SELECT * FROM scv_tarifakms".
				" WHERE codemp='".$as_codemp."'".
				" AND   codtar='".$as_codtar."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->sigesp_scv_c_tarifasxdistancias METODO->uf_scv_select_tarifa ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_numrows=$this->io_sql->num_rows($rs_data);
			if($li_numrows>0)
			{
				$lb_valido=true;
			}
		}
		return $lb_valido;
	} // fin de la function uf_scv_select_tarifa

	function uf_scv_insert_tarifa($as_codemp,$as_codtar,$as_dentar,$ai_kmsdes,$ai_kmshas,$ai_montar,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_insert_tarifa
		//	          Access:  public
		//	        Arguments  $as_codemp    // código de empresa.
		//        			   $as_codtar    // código de tarifa
		//    			       $as_dentar    // denominación de la tarifa
		//    			       $ai_kmsdes    // kilometraje de inicio del intervalo
		//    			       $ai_kmshas    // kilomatraje de cierre del intervalo
		//    			       $ai_montar    // monto de la tarifa
		//     				   $aa_seguridad // arreglo de seguridad
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de insertar una nueva tarifa de distancias en la tabla scv_tarifakms
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  09/10/2006      
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codtaraux = $as_codtar;
		$as_codtar=$this->io_fundb->uf_generar_codigo(true,$as_codemp,'scv_tarifakms','codtar');
		$this->io_sql->begin_transaction();
		$ls_sql=" INSERT INTO scv_tarifakms (codemp,codtar,dentar,kmsdes,kmshas,montar)".
				" VALUES ('".$as_codemp."','".$as_codtar."','".$as_dentar."','".$ai_kmsdes."','".$ai_kmshas."','".$ai_montar."')";
		$rs_data=$this->io_sql->execute($ls_sql);
		if ($rs_data===false)		     
		{
			$this->io_sql->rollback();
			if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
			{
				$lb_valido=$this->uf_insert_tarifa($as_codemp,$as_codtar,$as_dentar,$as_codpai,$as_codreg,$ai_monbol,$ai_mondol,
				                                   $ai_monpas,$ai_monhos,$ai_monali,$ai_monmov,$ai_exterior,$as_codcat,$as_codnom,
												   $aa_seguridad);
			}
			else
			{
				$this->io_msg->message("CLASE->sigesp_scv_c_tarifasxdistancias METODO->uf_scv_insert_tarifa ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			}
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Tarifa de Distancias ".$as_codtar." Asociada a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////// 		     
			$lb_valido=true;
			
			if($lb_valido)
			{
				$this->io_sql->commit();
				if($ls_codtaraux!=$as_codtar)
				{
					$this->io_msg->message("Se Asigno el Código de Tarifa por Distancia: ".$as_codtar);
				}
			}
			else
			{
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	} // fin de la function uf_scv_insert_tarifa
	
	function uf_scv_update_tarifa($as_codemp,$as_codtar,$as_dentar,$ai_kmsdes,$ai_kmshas,$ai_montar,$aa_seguridad) 
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_update_tarifa
		//	          Access:  public
		//	        Arguments  $as_codemp    // código de empresa.
		//        			   $as_codtar    // código de tarifa
		//    			       $as_dentar    // denominación de la tarifa
		//    			       $ai_kmsdes    // kilometraje de inicio del intervalo
		//    			       $ai_kmshas    // kilomatraje de cierre del intervalo
		//    			       $ai_montar    // monto de la tarifa
		//     				   $aa_seguridad // arreglo de seguridad
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de modificar una tarifa de distancias 
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  09/10/2006      
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$this->io_sql->begin_transaction();
		$ls_sql=" UPDATE scv_tarifakms".
				" SET   dentar='".$as_dentar."',".
				"       kmsdes='".$ai_kmsdes."',".
				"       kmshas='".$ai_kmshas."',".
				"       montar='".$ai_montar."'".
				" WHERE codemp='".$as_codemp."'".
				" AND   codtar='".$as_codtar."'";
		$rs_data = $this->io_sql->execute($ls_sql);
		if ($rs_data===false)
		{
			$this->io_sql->rollback();
			$this->io_msg->message("CLASE->sigesp_scv_c_tarifasxdistancias METODO->uf_scv_update_tarifa ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Tarifa de Distancias ".$as_codtar." Asociada a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										    $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										    $aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		     
			
			if($lb_valido)
			{
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
			}
		}  		      
		return $lb_valido;
	} // fin de la function uf_scv_update_tarifa
			
	function uf_scv_delete_tarifa($as_codemp,$as_codtar,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_delete_tarifa
		//	          Access:  public
		//	       Arguments:  $as_codemp    // código de empresa.
		//        			   $as_codtar    // código de tarifa
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de eliminar una tarifa de distancias en la tabla scv_tarifakms
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  09/10/2006      
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido= false;
		$lb_relacion= $this->uf_scv_check_relaciones($as_codemp,$as_codtar);
		if (!$lb_relacion)
		{
			$this->io_sql->begin_transaction();
			$ls_sql= " DELETE FROM scv_tarifakms".
					 " WHERE codemp='".$as_codemp."'".
					 " AND   codtar='".$as_codtar."'";	    
			$rs_data=$this->io_sql->execute($ls_sql);
			if ($rs_data===false)
			{
				$this->io_sql->rollback();
				$this->io_msg->message("CLASE->sigesp_scv_c_categoria METODO->uf_scv_delete_tarifa ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la Tarifa de Distancias ".$as_codtar." Asociada a la Empresa ".$as_codemp;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               ///////////////////////////// 		     
				$this->io_sql->commit();
			}
		}	  		 
		return $lb_valido;
	}// fin de la function uf_scv_delete_tarifa
	
	
	function uf_scv_check_relaciones($as_codemp,$as_codtar)
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
		$lb_valido=false;
		$ls_sql="SELECT codasi".
				"  FROM scv_dt_asignaciones".
				" WHERE codemp='".$as_codemp."'".
				"   AND proasi='TDS'".
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
				$this->is_msg_error="La Tarifa no puede ser eliminada, posee registros asociados a otras tablas";
			}
		}
		return $lb_valido;	
	}
	
} //Fin de la class sigesp_scv_c_misiones
?> 