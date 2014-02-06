<?php
require_once("../shared/class_folder/class_sql.php");
class sigesp_scv_c_solicitudviaticos
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_scv_c_solicitudviaticos()
	{
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_msg=new class_mensajes();
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=      new class_sql($this->con);
		$this->seguridad=   new sigesp_c_seguridad();
		$this->io_funcion = new class_funciones();
		require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
		$this->io_keygen= new sigesp_c_generar_consecutivo();
		
	}
	
	function uf_scv_select_solicitudviaticos($as_codemp,$as_codsolvia)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_select_solicitudviaticos
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codsolvia // codigo de solicitud de viaticos
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de una solicitud de viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/10/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codsolvia FROM scv_solicitudviatico".
				" WHERE codemp='". $as_codemp ."'".
				"   AND codsolvia='". $as_codsolvia ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->solicitud_viaticos MÉTODO->uf_scv_select_solicitudviaticos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_scv_select_solicitudviaticos

	function  uf_scv_insert_solicitudviatico($as_codemp,&$as_codsolvia,$as_codmis,$as_codrut,$as_coduniadm,$as_codestpro1,
	                                         $as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla,
											 $ad_fecsalvia,$ad_fecregvia,$ad_fecsolvia,$ai_numdiavia,$as_obssolvia,
											 $as_estsolvia,$ai_solviaext, $as_codfuefin, $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_insert_solicitudviatico
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa 
		//                 $as_codsolvia // codigo de solicitud de viaticos
		//                 $as_codmis    // codigo de mision
		//                 $as_codrut    // codigo de ruta
		//                 $as_coduniadm // codigo de la unidad ejecutora solicitante
		//        		   $as_codestpro1 //  codigo de estructura programatica nivel 1
		//        		   $as_codestpro2 //  codigo de estructura programatica nivel 2
		//        		   $as_codestpro3 //  codigo de estructura programatica nivel 3
		//        		   $as_codestpro4 //  codigo de estructura programatica nivel 4
		//        		   $as_codestpro5 //  codigo de estructura programatica nivel 5
		//                 $as_estcla     //  estatus de clasificación de la estructura programática
		//                 $ad_fecsalvia // fecha de inicio de los viaticos
		//                 $ad_fecregvia // fecha de cierre de los viaticos
		//                 $ad_fecsolvia // fecha de la solicitud de los viaticos
		//                 $ai_numdiavia // numero de dias de los viaticos
		//                 $as_obssolvia // observaciones de los viaticos
		//                 $as_estsolvia // estarus de la solicitud de viaticos
		//                 $ai_solviaext // indica si la solicitud de viaticos es al exterior(1) o nacional(0)
		//                 $as_codfuefin // código fuente de financioamiento
		//				   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un maestro de solicitud de viaticos en la tabla scv_solicitudviatico
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/10/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_codsolviaaux=$as_codsolvia;
		$lb_valido= $this->io_keygen->uf_verificar_numero_generado("SCV","scv_solicitudviatico","codsolvia","SCV",
																8,"","","",&$as_codsolvia);
		$ls_sql= "INSERT INTO scv_solicitudviatico (codemp,codsolvia,codmis,codrut,coduniadm,fecsalvia,fecregvia,".
				 "                            		fecsolvia,numdiavia,obssolvia,estsolvia,solviaext,codestpro1, ".
				 "                                  codestpro2,codestpro3,codestpro4,codestpro5,estcla,codfuefin) ".
				 "     VALUES('".$as_codemp."','".$as_codsolvia."','".$as_codmis."','".$as_codrut."','".$as_coduniadm."',".
				 "            '".$ad_fecsalvia."','".$ad_fecregvia."','".$ad_fecsolvia."','".$ai_numdiavia."',".
				 "            '".$as_obssolvia."','".$as_estsolvia."','".$ai_solviaext."', '".$as_codestpro1."', ".
				 "            '".$as_codestpro2."','".$as_codestpro3."','".$as_codestpro4."','".$as_codestpro5."', ".
				 "            '".$as_estcla."', '".$as_codfuefin."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			if ($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062')
			{
				$this->uf_scv_insert_solicitudviatico($as_codemp,&$as_codsolvia,$as_codmis,$as_codrut,$as_coduniadm,$ad_fecsalvia,
											 $ad_fecregvia,$ad_fecsolvia,$ai_numdiavia,$as_obssolvia,$as_estsolvia,$ai_solviaext,
											 $aa_seguridad);
			}
			else
			{
				$this->io_msg->message("CLASE->solicitud_viaticos MÉTODO->uf_scv_insert_solicitudviatico ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la Solicitud de Viaticos ".$as_codsolvia." Asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				if($as_codsolviaaux!=$as_codsolvia)
				{
					$this->io_msg->message("Se Asigno el Numero de Solicitud: ".$as_codsolvia);
				}
		}
		return $lb_valido;
	} //end function  uf_scv_insert_solicitudviatico

	function uf_scv_update_solicitudviatico($as_codemp,$as_codsolvia,$as_codmis,$as_codrut,$as_coduniadm,$as_codestpro1,
	                                         $as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla,$ad_fecsalvia,
											$ad_fecregvia,$ad_fecsolvia,$ai_numdiavia,$as_obssolvia,$ai_solviaext,$as_codfuefin,
											$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_update_solicitudviatico
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa 
		//                 $as_codsolvia // codigo de solicitud de viaticos
		//                 $as_codmis    // codigo de mision
		//                 $as_codrut    // codigo de ruta
		//                 $as_coduniadm // codigo de la unidad ejecutora solicitante
		//        		   $as_codestpro1 //  codigo de estructura programatica nivel 1
		//        		   $as_codestpro2 //  codigo de estructura programatica nivel 2
		//        		   $as_codestpro3 //  codigo de estructura programatica nivel 3
		//        		   $as_codestpro4 //  codigo de estructura programatica nivel 4
		//        		   $as_codestpro5 //  codigo de estructura programatica nivel 5
		//                 $as_estcla     //  estatus de clasificación de la estructura programática
		//                 $ad_fecsalvia // fecha de inicio de los viaticos
		//                 $ad_fecregvia // fecha de cierre de los viaticos
		//                 $ad_fecsolvia // fecha de la solicitud de los viaticos
		//                 $ai_numdiavia // numero de dias de los viaticos
		//                 $as_obssolvia // observaciones de los viaticos
		//                 $ai_solviaext // indica si la solicitud de viaticos es al exterior(1) o nacional(0)
		//                 $as_codfuefin // código fuente de financioamiento
		//				   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un maestro de solicitud de viaticos en la tabla scv_solicitudviatico
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 06/11/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 	$lb_valido=true;
		$ls_sql= "UPDATE scv_solicitudviatico".
				 "   SET codmis='". $as_codmis ."',".
				 "       codrut='". $as_codrut ."',".
				 "       coduniadm='". $as_coduniadm ."',". 
				 "       codestpro1='".$as_codestpro1."', ".
				 "       codestpro2='".$as_codestpro2."',".
				 "       codestpro3='".$as_codestpro3."',".
				 "       codestpro4='".$as_codestpro4."',".
				 "       codestpro5='".$as_codestpro5."', ".
				 "       estcla='".$as_estcla."',".
				 "       fecsalvia='". $ad_fecsalvia ."',".
				 "       fecregvia='". $ad_fecregvia ."',".
				 "       fecsolvia='". $ad_fecsolvia ."',".
				 "       numdiavia='". $ai_numdiavia ."',".
				 "       obssolvia='". $as_obssolvia ."',".
				 "       codfuefin='". $as_codfuefin ."',".
				 "       solviaext='". $ai_solviaext ."'".
				 " WHERE codemp='" . $as_codemp ."'".
				 "   AND codsolvia='" . $as_codsolvia ."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->solicitudviaticos MÉTODO->uf_scv_update_solicitudviatico ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la solicitud de viaticos ".$as_codsolvia." Asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	    return $lb_valido;
	} // end  function uf_scv_update_solicitudviatico

	function uf_scv_select_dt_asignaciones($as_codemp,$as_codsolvia,$as_codasi,$as_proasi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_select_dt_asignaciones
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codsolvia // codigo de solicitud de viaticos
		//  			   $as_codasi    // codigo de asignacion
		//  			   $as_proasi    // procedencia de asignaciones
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de una asignacion de una solicitud de viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/11/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codsolvia FROM scv_dt_asignaciones".
				" WHERE codemp='". $as_codemp ."'".
				"   AND codsolvia='". $as_codsolvia ."'".
				"   AND codasi='". $as_codasi ."'".
				"   AND proasi='". $as_proasi ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->solicitud_viaticos MÉTODO->uf_scv_select_dt_asignaciones ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_scv_select_dt_asignaciones

	function uf_scv_select_dt_personal($as_codemp,$as_codsolvia,$as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_select_solicitudviaticos
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codsolvia // codigo de solicitud de viaticos
		//  			   $as_codper    // codigo de personal
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de una solicitud de viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/11/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codsolvia FROM scv_dt_personal".
				" WHERE codemp='". $as_codemp ."'".
				"   AND codsolvia='". $as_codsolvia ."'".				
				"   AND codper='". $as_codper ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->solicitud_viaticos MÉTODO->uf_scv_select_solicitudviaticos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_scv_select_solicitudviaticos

	function  uf_scv_insert_dt_asignaciones($as_codemp,$as_codsolvia,$as_codasi,$as_proasi,$ai_canasi,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_insert_dt_asignaciones
		//         Access: public  
		//      Argumento: $as_codemp     // codigo de empresa 
		//                 $as_codsolvia  // codigo de solicitud de viaticos
		//                 $as_codasi     // codigo de asignacion
		//                 $as_proasi     // procedencia de la asignacion
		//                 $ai_canasi     // cantidad de asignaciones
		//				   $aa_seguridad  // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un detalle de asignaciones de una solicitud de viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/10/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "INSERT INTO scv_dt_asignaciones (codemp, codsolvia, codasi, proasi, canasi) ".
				 "     VALUES('".$as_codemp."','".$as_codsolvia."','".$as_codasi."','".$as_proasi."','".$ai_canasi."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->solicitud_viaticos MÉTODO->uf_scv_insert_dt_asignaciones ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la Asignación ". $as_codasi ." de Procedencia ".$as_proasi.
								 " asociado a la Solicitud de Viaticos ".$as_codsolvia." de la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} //end function  uf_scv_insert_dt_asignaciones

	function  uf_scv_insert_dt_personal($as_codemp,$as_codsolvia,$as_codper,$as_codclavia,$as_codnom,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_insert_dt_personal
		//         Access: public  
		//      Argumento: $as_codemp     // codigo de empresa 
		//                 $as_codsolvia  // codigo de solicitud de viaticos
		//                 $as_codper     // codigo de personal
		//                 $as_codclavia  // codigo de clasificacion de viaticos
		//                 $as_codnom     // codigo de la nomina del personal
		//				   $aa_seguridad  // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un detalle de personal de una solicitud de viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/10/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "INSERT INTO scv_dt_personal (codemp,codsolvia,codper,codclavia,codnom) ".
				  "     VALUES('".$as_codemp."','".$as_codsolvia."','".$as_codper."','".$as_codclavia."','".$as_codnom."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->solicitud_viaticos MÉTODO->uf_scv_insert_dt_personal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Personal ". $as_codper ." de Categoría ".$as_codclavia.
								 " asociado a la Solicitud de Viaticos ".$as_codsolvia." de la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} //end function  uf_scv_insert_dt_personal


	function uf_scv_delete_dt_asignacion($as_codemp,$as_codsolvia,$as_codasi,$as_proasi,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_delete_dt_asignacion
		//         Access: public  
		//      Argumento: $as_codemp    //codigo de empresa 
		//                 $as_codsolvia //codigo de solicitud de viaticos
		//                 $as_codasi    //codigo de asignacion
		//                 $as_proasi    //procedencia de asignacion
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un detalle de asignacion asociado a una solicitud de viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 06/11/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$this->io_sql->begin_transaction();	
		$ls_sql= "DELETE FROM scv_dt_asignaciones".
				 " WHERE codemp= '".$as_codemp. "'".
				 "   AND codsolvia= '".$as_codsolvia. "'";
		if((!empty($as_codasi))&&(!empty($as_proasi)))
		{		 
			$ls_sql=$ls_sql."   AND codasi= '".$as_codasi. "'".
				            "   AND proasi= '".$as_proasi. "'";
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->solicitudviaticos MÉTODO->uf_scv_delete_dt_asignacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion= "Eliminó la asignacion ".$as_codasi." de procedencia <b>".$as_proasi.
							 "</b> de la Solicitud de Viaticos ".$as_codsolvia." Asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////			
			$this->io_sql->commit();
			$lb_valido=true;
		}
		return $lb_valido;
	} //end function uf_scv_delete_dt_asignacion

	function uf_scv_delete_dt_personal($as_codemp,$as_codsolvia,$as_codper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_delete_dt_personal
		//         Access: public  
		//      Argumento: $as_codemp    //codigo de empresa 
		//                 $as_codsolvia //codigo de solicitud de viaticos
		//                 $as_codper    //codigo de personal
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un detalle de personal asociado a una solicitud de viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 06/11/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$this->io_sql->begin_transaction();	
		$ls_sql= "DELETE FROM scv_dt_personal".
				 " WHERE codemp= '".$as_codemp. "'".
				 "   AND codsolvia= '".$as_codsolvia. "'";
		if(!empty($as_codper))
		{		 
			$ls_sql=$ls_sql." AND codper= '".$as_codper. "'";
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->solicitudviaticos MÉTODO->uf_scv_delete_dt_personal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion= "Eliminó al personal ".$as_codper." de la Solicitud de Viaticos ".$as_codsolvia.
							 " Asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////			
			$this->io_sql->commit();
			$lb_valido=true;
		}
		return $lb_valido;
	} //end function uf_scv_delete_dt_personal

	function uf_scv_delete_solicitudviatico($as_codemp,$as_codsolvia,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_delete_solicitudviatico
		//         Access: public  
		//      Argumento: $as_codemp    //codigo de empresa 
		//                 $as_codsolvia //codigo de solicitud de viaticos
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina una solicitud de viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 07/11/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$this->io_sql->begin_transaction();	
		$lb_valido=$this->uf_scv_delete_dt_personal($as_codemp,$as_codsolvia,"",$aa_seguridad);
		if($lb_valido)
		{
			$lb_valido=$this->uf_scv_delete_dt_asignacion($as_codemp,$as_codsolvia,"","",$aa_seguridad);
			if($lb_valido)
			{
				$ls_sql= "DELETE FROM scv_solicitudviatico".
						 " WHERE codemp= '".$as_codemp. "'".
						 "   AND codsolvia= '".$as_codsolvia. "'";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$this->io_msg->message("CLASE->solicitudviaticos MÉTODO->uf_scv_delete_solicitudviatico ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
					$this->io_sql->rollback();
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="DELETE";
					$ls_descripcion= "Eliminó la Solicitud de Viaticos ".$as_codsolvia." Asociado a la Empresa ".$as_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////			
					$this->io_sql->commit();
					$lb_valido=true;
				}
			}
		}
		return $lb_valido;
	} //end function uf_scv_delete_solicitudviatico

	function uf_scv_load_dt_asignacion($as_codemp,$as_codsolvia,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_load_dt_asignacion
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codsolvia // codigo de solicitud de viaticos
		//  			   $ai_totrows   // total de lineas del grid
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que carga el grid con las asignaciones de una solicitud de viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 07/11/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "SELECT scv_dt_asignaciones.*,".
				 "       (CASE scv_dt_asignaciones.proasi".
				 "        WHEN 'TVS' THEN (SELECT scv_tarifas.dentar".
				 "                           FROM scv_tarifas".
				 "                          WHERE scv_dt_asignaciones.codemp=scv_tarifas.codemp".
				 " 							  AND scv_dt_asignaciones.codasi=scv_tarifas.codtar)".
				 "        WHEN 'TRP' THEN (SELECT scv_transportes.dentra".
				 "                           FROM scv_transportes".
				 "                          WHERE scv_dt_asignaciones.codemp=scv_transportes.codemp".
				 "                            AND scv_dt_asignaciones.codasi=scv_transportes.codtra)".
				 "        WHEN 'TOA' THEN (SELECT scv_otrasasignaciones.denotrasi".
				 "                           FROM scv_otrasasignaciones".
				 "                          WHERE scv_dt_asignaciones.codemp=scv_otrasasignaciones.codemp".
				 "                            AND scv_dt_asignaciones.codasi=scv_otrasasignaciones.codotrasi)".
				 "		  ELSE (SELECT scv_tarifakms.dentar".
				 "                FROM scv_tarifakms".
				 "               WHERE scv_dt_asignaciones.codemp=scv_tarifakms.codemp".
				 "                 AND scv_dt_asignaciones.codasi=scv_tarifakms.codtar) END) AS denasi".
				 "  FROM scv_solicitudviatico,scv_dt_asignaciones".
				 " WHERE scv_solicitudviatico.codemp='".$as_codemp."'".
				 "   AND scv_solicitudviatico.codsolvia='".$as_codsolvia."'".
				 "   AND scv_solicitudviatico.codsolvia=scv_dt_asignaciones.codsolvia";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->solicitud_viaticos MÉTODO->uf_scv_load_dt_asignacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codasi=$row["codasi"];
				$ls_proasi=$row["proasi"];
				$ls_denasi=$row["denasi"];
				$li_canasi=$row["canasi"];
				$ai_totrows++;
				
				$ao_object[$ai_totrows][1]="<input name=txtproasig".$ai_totrows."  type=text   id=txtproasig".$ai_totrows."  class=sin-borde size=16 value='". $ls_proasi ."' style='text-align:center' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtcodasig".$ai_totrows."  type=text   id=txtcodasig".$ai_totrows."  class=sin-borde size=11 value='". $ls_codasi ."' readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtdenasig".$ai_totrows."  type=text   id=txtdenasig".$ai_totrows."  class=sin-borde size=55 value='". $ls_denasi ."' readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtcantidad".$ai_totrows." type=text   id=txtcantidad".$ai_totrows." class=sin-borde size=12 value='". $li_canasi ."' style='text-align:right' readonly>";
				$ao_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt_asignaciones(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0></a>";
				
			}
			$lb_valido=true;
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_scv_load_dt_asignacion

	function uf_scv_load_dt_personal($as_codemp,$as_codsolvia,&$ai_totrows,&$ao_objectpersonal)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_load_dt_personal
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codsolvia // codigo de solicitud de viaticos
		//  			   $ai_totrows   // total de lineas del grid
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que carga el grid con el personal de una solicitud de viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 07/11/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$lb_existe=$this->uf_scv_select_categoria_personal($as_codemp,$as_codsolvia);
		if($lb_existe)
		{
			/*$ls_sql="SELECT (CASE sno_nomina.racnom WHEN 1 THEN sno_asignacioncargo.denasicar ELSE sno_cargo.descar END) AS cargo,".
					"       scv_dt_personal.codclavia,sno_personalnomina.codper,".
					"		(SELECT nomper FROM sno_personal".
					"  		  WHERE sno_personal.codper=sno_personalnomina.codper) as nomper,".
					"		(SELECT apeper FROM sno_personal".
					"   	  WHERE sno_personal.codper=sno_personalnomina.codper) as apeper,".
					"		(SELECT cedper FROM sno_personal".
					"		  WHERE sno_personal.codper=sno_personalnomina.codper) as cedper".
					"  FROM sno_personalnomina, sno_nomina, sno_cargo, sno_asignacioncargo,sno_personal,scv_dt_personal".
					" WHERE scv_dt_personal.codemp='".$as_codemp."'".
					"   AND scv_dt_personal.codsolvia='".$as_codsolvia."'".
					"   AND scv_dt_personal.codemp=sno_personal.codemp".
					"   AND scv_dt_personal.codper=sno_personal.codper".
					"   AND scv_dt_personal.codemp=sno_personalnomina.codemp".
					"   AND scv_dt_personal.codnom=sno_personalnomina.codnom".
					"   AND sno_nomina.espnom=0".
					"   AND sno_personalnomina.codemp = sno_nomina.codemp".
					"   AND sno_personalnomina.codnom = sno_nomina.codnom".
					"   AND sno_personalnomina.codper = sno_personal.codper".
					"   AND sno_personalnomina.codemp = sno_cargo.codemp".
					"   AND sno_personalnomina.codnom = sno_cargo.codnom".
					"   AND sno_personalnomina.codcar = sno_cargo.codcar".
					"   AND sno_personalnomina.codemp = sno_asignacioncargo.codemp".
					"   AND sno_personalnomina.codnom = sno_asignacioncargo.codnom".
					"   AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar".
					" ORDER BY sno_personalnomina.codper,codclavia";*/
					$ls_sql=" SELECT sno_personal.*                                       ".
							" FROM  sno_personal,scv_dt_personal                          ".
							" WHERE scv_dt_personal.codemp='".$as_codemp."'               ".
							" AND   scv_dt_personal.codsolvia='".$as_codsolvia."'         ".
							" AND   sno_personal.codemp= scv_dt_personal.codemp           ".
							" AND   scv_dt_personal.codper=sno_personal.codper            ".
							" ORDER BY sno_personal.codper,scv_dt_personal.codclavia      ";
		}
		else
		{
			$ls_sql="SELECT scv_dt_personal.codper,rpc_beneficiario.ced_bene,".
					"       (SELECT nombene ".
					"          FROM rpc_beneficiario".
					"         WHERE scv_dt_personal.codemp=rpc_beneficiario.codemp".
					"           AND scv_dt_personal.codper=rpc_beneficiario.ced_bene) AS nombene,".
					"       (SELECT apebene ".
					"          FROM rpc_beneficiario".
					"         WHERE scv_dt_personal.codemp=rpc_beneficiario.codemp".
					"           AND scv_dt_personal.codper=rpc_beneficiario.ced_bene) AS apebene".
					"  FROM scv_dt_personal,rpc_beneficiario".
					" WHERE scv_dt_personal.codemp='".$as_codemp."'".
					"   AND scv_dt_personal.codsolvia='".$as_codsolvia."'".
					"   AND scv_dt_personal.codper=rpc_beneficiario.ced_bene";
		}
		//print $ls_sql."<br>";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->solicitud_viaticos MÉTODO->uf_scv_load_dt_personal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				if($lb_existe)
				{
					$ls_codper=$row["codper"];
					$ls_cedper=$row["cedper"];
					$ls_nomper=$row["nomper"]." ".$row["apeper"];
					$ls_codcar= $row["cargo"];				
					$ls_codclavia=$row["codclavia"];			
				}
				else
				{
					$ls_codper=$row["codper"];
					$ls_cedper=$row["ced_bene"];
					$ls_nomper=$row["nombene"]." ".$row["apebene"];
					$ls_codcar="";				
					$ls_codclavia="";			
				}
				$ai_totrows++;
				
				$ao_objectpersonal[$ai_totrows][1]="<input name=txtcodper".$ai_totrows."    type=text   id=txtcodper".$ai_totrows."    class=sin-borde size=15 value='". $ls_codper ."'     readonly>";
				$ao_objectpersonal[$ai_totrows][2]="<input name=txtnomper".$ai_totrows."    type=text   id=txtnomper".$ai_totrows."    class=sin-borde size=40 value='". $ls_nomper ."'     readonly>";
				$ao_objectpersonal[$ai_totrows][3]="<input name=txtcedper".$ai_totrows."    type=text   id=txtcedper".$ai_totrows."    class=sin-borde size=11 value='". $ls_cedper ."'     readonly>";
				$ao_objectpersonal[$ai_totrows][4]="<input name=txtcodcar".$ai_totrows."    type=text   id=txtcodcar".$ai_totrows."    class=sin-borde size=30 value='". $ls_codcar ."'     readonly>";
				$ao_objectpersonal[$ai_totrows][5]="<input name=txtcodclavia".$ai_totrows." type=text   id=txtcodclavia".$ai_totrows." class=sin-borde size=10 value='". $ls_codclavia ."'  readonly style='text-align:center'>";
				$ao_objectpersonal[$ai_totrows][6]="<a href=javascript:uf_delete_dt_personal(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0></a>";
				
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_scv_load_dt_personal

	function uf_scv_load_config($as_codemp,$as_codsis,$as_seccion,$as_entry,&$as_spgcuenta) 
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_load_config
		//	          Access:  public
		//	       Arguments:  $as_codemp    // código de la Empresa.
		//        			   $as_codsis    //  código de sistema
		//        			   $as_seccion   //  tipo de dato
		//        			   $as_entry     // 
		//        			   $as_spgcuenta // cuenta presupuestaria
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de cargar la cuenta asociada a los viaticos
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  14/11/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_sql=" SELECT value".
				"   FROM sigesp_config".
				"  WHERE codemp='".$as_codemp."'".
				"    AND codsis='".$as_codsis."'".
				"    AND seccion='".$as_seccion."'".
				"    AND entry='".$as_entry."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigesp_scv_c_solicitudviaticos METODO->uf_scv_load_config ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_spgcuenta=$row["value"];
				$lb_valido=true;
			}
		}
		return $lb_valido;
	} // fin de la function uf_scv_load_config

	function uf_scv_load_estructuraunidad($as_codemp,$as_coduniadm,&$as_codestpro1,&$as_codestpro2,&$as_codestpro3,&$as_codestpro4,
										  &$as_codestpro5,&$as_estcla) 
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_load_estructuraunidad
		//	          Access:  public
		//	       Arguments:  $as_codemp     //  codigo de la Empresa.
		//        			   $as_coduniadm  //  codifo de unidad ejecutora
		//        			   $as_codestpro1 //  codigo de estructura programatica nivel 1
		//        			   $as_codestpro2 //  codigo de estructura programatica nivel 2
		//        			   $as_codestpro3 //  codigo de estructura programatica nivel 3
		//        			   $as_codestpro4 //  codigo de estructura programatica nivel 4
		//        			   $as_codestpro5 //  codigo de estructura programatica nivel 5
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de cargar la estructura presupuestaria de una unidad ejecutora
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  14/11/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_sql=" SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla".
				"   FROM spg_dt_unidadadministrativa".
				"  WHERE codemp='".$as_codemp."'".
				"    AND coduniadm='".$as_coduniadm."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigesp_scv_c_solicitudviaticos METODO->uf_scv_load_estructuraunidad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codestpro1=$row["codestpro1"];
				$as_codestpro2=$row["codestpro2"];
				$as_codestpro3=$row["codestpro3"];
				$as_codestpro4=$row["codestpro4"];
				$as_codestpro5=$row["codestpro5"];
				$as_estcla=$row["estcla"];
				$lb_valido=true;
			}
		}
		return $lb_valido;
	} // fin de la function uf_scv_load_estructuraunidad

	function uf_scv_select_cuentaspg($as_codemp,$as_spgcta,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
									 $as_codestpro5,$as_estcla) 
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_load_estructuraunidad
		//	          Access:  public
		//	       Arguments:  $as_codemp     //  codigo de la Empresa.
		//        			   $as_spgcta     //  cuenta presupuestaria de gasto
		//        			   $as_codestpro1 //  codigo de estructura programatica nivel 1
		//        			   $as_codestpro2 //  codigo de estructura programatica nivel 2
		//        			   $as_codestpro3 //  codigo de estructura programatica nivel 3
		//        			   $as_codestpro4 //  codigo de estructura programatica nivel 4
		//        			   $as_codestpro5 //  codigo de estructura programatica nivel 5
		//                     $as_estcla     //  estatus de clasificación de la estructura programática
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de verificar la existencia de una cuenta presupuestaria en una estructura 
		//                     programatica
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  14/11/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_sql=" SELECT spg_cuenta".
				"   FROM spg_cuentas".
				"  WHERE codemp='".$as_codemp."'".
				"    AND spg_cuenta='".$as_spgcta."'".
				"    AND codestpro1='".$as_codestpro1."'".
				"    AND codestpro2='".$as_codestpro2."'".
				"    AND codestpro3='".$as_codestpro3."'".
				"    AND codestpro4='".$as_codestpro4."'".
				"    AND codestpro5='".$as_codestpro5."'".
				"    AND estcla='".$as_estcla."' ";

		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigesp_scv_c_solicitudviaticos METODO->uf_scv_load_estructuraunidad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
		}
		return $lb_valido;
	} // fin de la function uf_scv_load_estructuraunidad

	function uf_scv_select_categoria_personal($as_codemp,$as_codsolvia)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_select_categoria_personal
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codsolvia // codigo de solicitud de viaticos
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de una solicitud de viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/11/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql=" SELECT sno_personal.*                                       ".
				" FROM  sno_personal,scv_dt_personal                          ".
				" WHERE scv_dt_personal.codemp='".$as_codemp."'               ".
				" AND   scv_dt_personal.codsolvia='".$as_codsolvia."'         ".
				" AND   sno_personal.codemp= scv_dt_personal.codemp           ".
				" AND   scv_dt_personal.codper=sno_personal.codper            ".
				" ORDER BY sno_personal.codper,scv_dt_personal.codclavia      ";
				//print $ls_sql."<br>";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->solicitud_viaticos MÉTODO->uf_scv_select_categoria_personal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codper=$row["codper"];
				if($ls_codper!="")
				{$lb_valido=true;}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_scv_select_categoria_personal

	function uf_scv_validar_fecha_viaticos($as_codemp,$as_codper,$ad_fecsalvia,$ad_fecregvia)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_select_dt_asignaciones
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codper    // codigo de personal / beneficiario
		//  			   $ad_fecsalvia // fecha de salida de viatico
		//  			   $ad_fecregvia // fecha de regreso de viatico
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de otra solicitud de viaticos para la misma persona dentro de la 
		//				   misma fecha
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 25/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT scv_solicitudviatico.codsolvia".
				"  FROM scv_solicitudviatico,scv_dt_personal".
				" WHERE scv_solicitudviatico.codemp='". $as_codemp ."'".
				"   AND ((scv_solicitudviatico.fecsalvia<='".$ad_fecsalvia."'".
				"   AND scv_solicitudviatico.fecregvia>='".$ad_fecsalvia."')".
				"    OR (scv_solicitudviatico.fecsalvia<='".$ad_fecregvia."'".
				"   AND scv_solicitudviatico.fecregvia>='".$ad_fecregvia."'))".
				"   AND scv_dt_personal.codper='". $as_codper ."'".
				"   AND scv_solicitudviatico.estsolvia<>'A' ".
				"   AND scv_solicitudviatico.codemp=scv_dt_personal.codemp".
				"   AND scv_solicitudviatico.codsolvia=scv_dt_personal.codsolvia";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->solicitud_viaticos MÉTODO->uf_scv_validar_fecha_viaticos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_scv_select_dt_asignaciones

	function uf_scv_select_cuentaspg_fuente_financiamiento($as_codemp,$as_spgcta,$as_codestpro1,$as_codestpro2,$as_codestpro3,
	                                                       $as_codestpro4,$as_codestpro5,$as_estcla,$as_codcuefin) 
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_select_cuentaspg_fuente_financiamiento
		//	          Access:  public
		//	       Arguments:  $as_codemp     //  codigo de la Empresa.
		//        			   $as_spgcta     //  cuenta presupuestaria de gasto
		//        			   $as_codestpro1 //  codigo de estructura programatica nivel 1
		//        			   $as_codestpro2 //  codigo de estructura programatica nivel 2
		//        			   $as_codestpro3 //  codigo de estructura programatica nivel 3
		//        			   $as_codestpro4 //  codigo de estructura programatica nivel 4
		//        			   $as_codestpro5 //  codigo de estructura programatica nivel 5
		//                     $as_estcla     //  estatus de clasificación de la estructura programática
		//                     $as_codcuefin  //  código fuente de financiamiento
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de verificar la existencia de una cuenta presupuestaria en una estructura 
		//                     programatica de la fuente de financiamiento
		//     Elaborado Por:  Ing. María Beatriz Unda
		// Fecha de Creación:  05/11/2008
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_sql=" SELECT spg_cuenta".
				"   FROM spg_cuenta_fuentefinanciamiento".
				"  WHERE codemp='".$as_codemp."'".
				"    AND spg_cuenta='".$as_spgcta."'".
				"    AND codestpro1='".$as_codestpro1."'".
				"    AND codestpro2='".$as_codestpro2."'".
				"    AND codestpro3='".$as_codestpro3."'".
				"    AND codestpro4='".$as_codestpro4."'".
				"    AND codestpro5='".$as_codestpro5."'".
				"    AND estcla='".$as_estcla."' ".
				"    AND codfuefin='".$as_codcuefin."' ";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigesp_scv_c_solicitudviaticos METODO->uf_scv_select_cuentaspg_fuente_financiamiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
		}
		return $lb_valido;
	} // fin de la function uf_scv_select_cuentaspg_fuente_financiamiento


} 
?>
