<?php
class sigesp_scv_c_regiones
{
	var $ls_sql;
	var $ds_dtregion;
	
	function sigesp_scv_c_regiones($conn)
	{
		require_once("../shared/class_folder/sigesp_c_seguridad.php");	  
		require_once("../shared/class_folder/class_funciones.php");		  
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_funciones_db.php"); 
		$this->seguridad= new sigesp_c_seguridad();		
		$this->io_funcion= new class_funciones();
		$this->io_sql= new class_sql($conn);
		$this->io_msg= new class_mensajes();
        $this->io_database  = $_SESSION["ls_database"];
		$this->io_gestor    = $_SESSION["ls_gestor"];
		$this->io_funciondb= new class_funciones_db($conn);
	} // fin de la function sigesp_scv_c_regiones

	function uf_insert_region($as_codemp,$as_codreg,$as_codpai,$as_denreg,$ar_grid,$ai_total,$aa_seguridad) 
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_insert_region
		//	          Access:  public
		// 	       Arguments:  $as_codemp    // Código de la Empresa.  
		//        			   $as_codreg    // Código de la Región.
		//        			   $as_codpai    // Código del País al cual pertenece la Región.
		//        			   $as_denreg    // Denominación de la Región.
		//   				   $ar_grid      // Objeto grid de donde insertaremos los detalles.
		//         			   $ai_total     // Total de filas del grid de Detalles de Estados.
		//     				   $aa_seguridad // Arreglo de Seguridad cargado con la información de usuario,ventana,etc.
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de insertar una nueva modalidad en la tabla scv_regiones. 
		//     Elaborado Por:  Ing. Néstor Falcón.
		// Fecha de Creación:  23/06/2006
		//    Modificado Por:  Ing. Luis Anibal Lang
		//    Fecha de Modif:  19/09/2006      
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codregaux = $as_codreg;
		$as_codreg=$this->io_funciondb->uf_generar_codigo(true,$as_codemp,'scv_regiones','codreg',$as_codpai);
		$this->io_sql->begin_transaction();
		$ls_sql=" INSERT INTO scv_regiones (codemp, codreg, codpai, denreg)".
				"      VALUES ('".$as_codemp."','".$as_codreg."','".$as_codpai."','".$as_denreg."')";
		$rs_data = $this->io_sql->execute($ls_sql);
		if ($rs_data===false)
		{
			$this->io_sql->rollback();
			if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
			{
				$lb_valido=$this->uf_insert_region($as_codemp,$as_codreg,$as_codpai,$as_denreg,$ar_grid,$ai_total,$aa_seguridad);
			}
			else
			{
				$this->io_msg->message("CLASE->SIGESP_SCV_C_REGIONES; METODO->uf_insert_region; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			}
		}
		else
		{
			if ($this->uf_insert_dt_region($as_codemp,$as_codpai,$as_codreg,$ar_grid,$ai_total,$aa_seguridad))
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               ////////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion =" Insertó  la Región ".$as_codreg." del Pais ".$as_codpai." Asociada a la empresa ".$as_codemp;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               ////////////////////////////////	
				$this->io_sql->commit();
				if($ls_codregaux!=$as_codreg)
				{
					$this->io_msg->message("Se Asigno el Código de Región: ".$as_codreg);
				}
			}
		}
		return $lb_valido;
	} // fin de la function sigesp_scv_c_regiones
	
	function uf_insert_dt_region($as_codemp,$as_codpai,$as_codreg,$ar_grid,$ai_total,$aa_seguridad)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Funcion:  uf_insert_dt_modalidad
		//	          Access:  public
		// 	       Arguments:  $as_codemp    //  Código de la Empresa.
		//        			   $as_codpai    //  Código del Pais.
		//                     $ar_grid      //  Arreglo cargado con los estados que serán insertados para una Región.
		//                     $ai_total     //  Variable que contiene la cantidad de estados que van a ser insertados a la Región.
		//                     $aa_seguridad //  Arreglo de Seguridad cargado con la información de usuario,ventana,etc.
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de insertar detalles para una modalidad en la tabla soc_dtm_clausulas. 
		//     Elaborado Por:  Ing. Néstor Falcón.
		// Fecha de Creación:  20/02/2006  
		//    Modificado Por:  Ing. Luis Anibal Lang
		//   Fecha de Modif.:  19/09/2006      
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for ($li_i=1;$li_i<=$ai_total;$li_i++)
		{
			if ($lb_valido)
			{
				$ls_codest = $ar_grid["estado"][$li_i];    
				if (!empty($ls_codest))			            
				{
					$ls_sql=" INSERT INTO scv_dt_regiones (codemp, codreg, codpai, codest) ".
							"      VALUES ('".$as_codemp."','".$as_codreg."','".$as_codpai."','".$ls_codest."')"; 
					$rs_data = $this->io_sql->execute($ls_sql);              
					if ($rs_data===false)
					{				 
						$this->io_sql->rollback();
						$this->io_msg->message("CLASE->SIGESP_SCV_C_REGIONES; METODO->uf_insert_dt_region; ERROR->".$this->io_funcion->                     uf_convertirmsg($this->io_sql->message));
					}
					else
					{				 
						$lb_valido=true;  		                    
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento      ="INSERT";
						$ls_descripcion =" Insertó el estado ".$ls_codest." del la Región ".$as_codreg." asociado a la Empresa ".$as_codemp;
						$ls_variable    = $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
						$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
						$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               ///////////////////////////// 
					}  				
				}
			}
		} 
		return $lb_valido;
	} // fin de la function uf_insert_dt_region
	
	function uf_update_region($as_codemp,$as_codreg,$as_codpai,$as_denreg,$ar_grid,$ai_total,$aa_seguridad) 
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_update_region
		//	          Access:  public
		// 	       Arguments:  $as_codemp    //  Código de la Empresa.
		//       			   $as_codpai    //  Código del Pais.
		//                     $ar_grid      //  Arreglo cargado con los estados que serán insertados para una Región.
		//                     $ai_total     //  Variable que contiene la cantidad de estados que van a ser insertados a la Región.
		//                     $aa_seguridad // Arreglo de Seguridad cargado con la información de usuario,ventana,etc.
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de actualizar los datos de una modalidad en la tabla scv_regiones.  
		//     Elaborado Por:  Ing. Néstor Falcón.
		// Fecha de Creación:  20/02/2006     
		//    Modificado Por:  Ing. Luis Anibal Lang
		//   Fecha de Modif.:  19/09/2006      
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
		$lb_valido= false;
		$this->io_sql->begin_transaction();
		$ls_sql= "UPDATE scv_regiones".
				 "   SET denreg='".$as_denreg."'".
				 " WHERE codemp='".$as_codemp."'".
				 "   AND codreg='".$as_codreg."'";
		$rs_data = $this->io_sql->execute($ls_sql);
		if ($rs_data===false)
		{
			$this->io_sql->rollback();
			$this->io_msg->message("CLASE->SIGESP_SCV_C_REGIONES; METODO->uf_update_region; ERROR->".$this->io_funcion->uf_convertirmsg(       $this->io_sql->message));
		}
		else
		{
			if ($this->uf_delete_estados_region($as_codemp,$as_codpai,$as_codreg,$aa_seguridad))//Eliminar todos los estados asociados a una                                                                                   región.
			{                  
				if ($this->uf_insert_dt_region($as_codemp,$as_codpai,$as_codreg,$ar_grid,$ai_total,$aa_seguridad))
				{                        
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizó la Región ".$as_codreg." del Pais ".$as_codpai." Asociada a la empresa ".$as_codemp;
					$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$this->io_sql->commit();
				}
			}
		}
		return $lb_valido;
	} // fin de la function uf_update_region
	
	function uf_delete_region($as_codemp,$as_codpai,$as_codreg,$aa_seguridad)
	{          		 
		//////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_delete_region
		//	          Access:  public
		// 	       Arguments:  $as_codemp    // Código de la Empresa.
		//       			   $as_codpai    // Código del Pais.
		//       			   $as_codreg    // Código de la Región. 
		//     				   $aa_seguridad // Arreglo de Seguridad cargado con la información de usuario,ventana,etc.
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de eliminar una modalidad en la tabla scv_regiones.  
		//     Elaborado Por:  Ing. Néstor Falcón.
		// Fecha de Creación:  20/02/2006 
		//    Modificado Por:  Ing. Luis Anibal Lang
		//   Fecha de Modif.:  19/09/2006      
		//////////////////////////////////////////////////////////////////////////////  
		$lb_valido=false;
		$lb_relacion= $this->uf_validar_delete($as_codemp,$as_codreg);
		if (!$lb_relacion)
		{
			if ($this->uf_delete_estados_region($as_codemp,$as_codpai,$as_codreg,$aa_seguridad))  
			{
				$ls_sql= " DELETE FROM scv_regiones".
						 "  WHERE codemp='".$as_codemp."'".
						 "    AND codpai='".$as_codpai."'".
						 "    AND codreg='".$as_codreg."'";	 
				$rs_data= $this->io_sql->execute($ls_sql);
				if ($rs_data===false)
				{
					$this->io_sql->rollback();
					$this->io_msg->message("CLASE->SIGESP_SCV_C_REGIONES; METODO->uf_delete_region; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				}
				else
				{
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="DELETE";
					$ls_descripcion ="Eliminó la Región ".$as_codreg." del Pais ".$as_codpai." Asociada a la empresa ".$as_codemp;
					$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$this->io_sql->commit();
				}
			}
		}
		else
		{
			$this->io_msg->message('La Región no puede ser eliminada, posee registros asociados a otras tablas'); 
		}
		return $lb_valido;
	} // fin de la function uf_delete_region
	
	function uf_delete_estados_region($as_codemp,$as_codpai,$as_codreg)
	{          		 
		//////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_delete_estados_region
		//	          Access:  public
		// 	       Arguments:  $as_codemp    // Código de la Empresa.
		//       			   $as_codpai    // Código del Pais.
		//       			   $as_codreg    // Código de la Región. 
		//     				   $aa_seguridad // Arreglo de Seguridad cargado con la información de usuario,ventana,etc.
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de eliminar las modalidades por clausulas en la tabla soc_dtm_clausulas.  
		//     Elaborado Por:  Ing. Néstor Falcón.
		// Fecha de Creación:  20/02/2006
		//    Modificado Por:  Ing. Luis Anibal Lang
		//   Fecha de Modif.:  19/09/2006      
		//////////////////////////////////////////////////////////////////////////////  
		$lb_valido= false;        
		$ls_sql= "DELETE FROM scv_dt_regiones".
				 " WHERE codemp='".$as_codemp."'".
				 "   AND codpai='".$as_codpai."'".
				 "   AND codreg='".$as_codreg."'";	 
		$rs_data=$this->io_sql->execute($ls_sql);
		if ($rs_data===false)
		{
			$this->io_sql->rollback();
			$this->io_msg->message("CLASE->SIGESP_SCV_C_REGIONES; METODO->uf_delete_estados_region; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
		} 		 
		return $lb_valido;
	} // fin de la function  uf_delete_estados_region
	
	function uf_load_region($as_codemp,$as_codpai,$as_codreg) 
	{
		//////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_load_region
		// 	          Access:  public
		// 	       Arguments:  $as_codemp // Código de la Empresa.
		//       			   $as_codpai // Código del Pais.
		//       			   $as_codreg // Código de la Región. 
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de verificar si existe o no una region, la funcion devuelve true si el
		//                     registro es encontrado caso contrario devuelve false. 
		//     Elaborado Por:  Ing. Néstor Falcón.
		// Fecha de Creación:  26/06/2006 
		//    Modificado Por:  Ing. Luis Anibal Lang
		//   Fecha de Modif.:  19/09/2006      
		//////////////////////////////////////////////////////////////////////////////  
		$lb_valido= false;
		$ls_sql= " SELECT codreg FROM scv_regiones".
				 "  WHERE codemp='".$as_codemp."'".
				 "    AND codpai='".$as_codpai."' ".
				 "    AND codreg='".$as_codreg."'";
		$rs_data = $this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_msg->message("CLASE->SIGESP_SCV_C_REGIONES; METODO->uf_load_region; ERROR->".$this->io_funcion->uf_convertirmsg(       $this->io_sql->message));
		}
		else
		{
			$li_numrows= $this->io_sql->num_rows($rs_data);
			if ($li_numrows>0)
			{
				$lb_valido= true;
				$this->io_sql->free_result($rs_data);
			}
		} 
		return $lb_valido;
	} // fin de la function uf_load_region
	
	function uf_validar_delete($as_codemp,$as_codreg) 
	{
		//////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_validar_delete
		//	          Access:  public
		// 	       Arguments:  $as_codemp // Código de la Empresa.
		//       			   $as_codreg // Código de la Región. 
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de verificar si existe o no una modalidad dentro de la tabla soc_ordencompra, 
		//                     la funcion devuelve true si el registro es encontrado, caso contrario devuelve false. 
		//     Elaborado Por:  Ing. Néstor Falcón.
		// Fecha de Creación:  20/02/2006 
		//    Modificado Por:  Ing. Luis Anibal Lang
		//   Fecha de Modif.:  19/09/2006      
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido= false;
		$ls_sql= " SELECT * FROM scv_tarifas".
				 "  WHERE codemp='".$as_codemp."'".
				 "    AND codreg='".$as_codreg."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false;
		}
		else
		{
			$li_numrows = $this->io_sql->num_rows($rs_data);
			if ($li_numrows>0)
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
		}
		return $lb_valido;
	} // fin de la function uf_validar_delete
	
	function uf_load_dt_region($as_codemp,$as_codreg,$as_codpai)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_load_dt_region
		//	          Access:  public
		// 	       Arguments:  $as_codemp // Código de la Empresa.
		//       			   $as_codreg // Código de la Región. 
		//       			   $as_codpai // Código del Pais.
		//           Returns:  $lb_valido.
		//	     Description:  Función que se encarga de extraer todos los detalles(Estados) asociados a un Región. 
		//     Elaborado Por:  Ing. Néstor Falcón.
		// Fecha de Creación:  26/06/2006 
		//    Modificado Por:  Ing. Luis Anibal Lang
		//   Fecha de Modif.:  19/09/2006      
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido= false;
		$ls_sql=" SELECT scv_dt_regiones.codest,sigesp_estados.desest".
				"   FROM scv_dt_regiones,sigesp_estados ".
				"  WHERE scv_dt_regiones.codemp='".$as_codemp."'".
				"    AND scv_dt_regiones.codreg='".$as_codreg."'".
				"    AND scv_dt_regiones.codpai='".$as_codpai."'".
				"    AND scv_dt_regiones.codpai=sigesp_estados.codpai".
				"    AND scv_dt_regiones.codest=sigesp_estados.codest";
		$rs_data = $this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_msg->message("CLASE->SIGESP_SCV_C_REGIONES; METODO->uf_load_dt_region; ERROR->".$this->io_funcion->uf_convertirmsg(       $this->io_sql->message));
		}
		else
		{
			$li_numrows=$this->io_sql->num_rows($rs_data);
			if ($li_numrows>0)
			{
				$datos=$this->io_sql->obtener_datos($rs_data);
				$this->ds_dtregion = new class_datastore();
				$this->ds_dtregion->data=$datos;
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
		}		
		return $lb_valido;
	} // fin de la function uf_load_dt_region
	
	function uf_load_paises()
	{
		//////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_load_paises
		//	          Access:  public
		// 	       Arguments:  
		//           Returns:  $rs_data.
		//		 Description:  Devuelve un resulset con todos los paises de la tabla sigesp_pais.
		//     Elaborado Por:  Ing. Néstor Falcón.
		// Fecha de Creación:  26/06/2006 
		//    Modificado Por:  Ing. Luis Anibal Lang
		//   Fecha de Modif.:  19/09/2006      
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "SELECT codpai,despai FROM sigesp_pais".
				 " ORDER BY codpai ASC";
		$rs_data = $this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_msg->message("CLASE->SIGESP_SCV_C_REGIONES; METODO->uf_load_paises; ERROR->".$this->io_funcion->uf_convertirmsg(       $this->io_sql->message));
		}
		else
		{
			$li_numrows = $this->io_sql->num_rows($rs_data);	    
			if ($li_numrows>0)
			{
				$lb_valido=true;
			}
		}	
		return $rs_data;
	}  // fin de la function uf_load_paises
	
	function uf_check_relaciones($as_codemp,$as_codrut)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_check_relaciones
		//	          Access:  public
		//	       Arguments:  $as_codemp    // codigo de empresa.
		//        			   $as_codrut    // codigo de ruta
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de verificar si existen tablas relacionadas al Código de la Ruta. 
		//    Modificado Por:  Ing. Luis Anibal Lang
		//   Fecha de Modif.:  05/10/2006      
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codrut".
				"  FROM scv_solicitudviatico".
				" WHERE codemp='".$as_codemp."'".
				"   AND codrut='".$as_codrut."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigesp_scv_c_rutas METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->is_msg_error="La Ruta no puede ser eliminada, posee registros asociados a otras tablas";
			}
		}
		return $lb_valido;	
	} //Fin de la function uf_check_relaciones
	function uf_generar_codigo($ab_empresa,$as_codemp,$as_tabla,$as_columna,$as_columna2)
	{ 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_generar_codigo
		//	          Access:  public
		//	       Arguments:  $as_codpais // codigo de pais
		//					   $ab_empresa // Si usara el campo empresa como filtro    
		//					   $as_codemp    // codigo de la empresa
		//					   $as_tabla     // Nombre de la tabla 
		//					   $ai_length    // longitud del campo
		//	         Returns:  $lb_valido.
		//		 Description:   Este método genera el numero consecutivo del código de cualquier tabla deseada
		//     Elaborado Por:  Ing. Nestor Falcón
		// Fecha de Creación:  02/08/2006      
		//	  Modificado Por:  Ing. Luis Anibal Lang
		// 		Fecha Modif.:  02/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_existe=$this->existe_tabla($as_tabla);
		if ($lb_existe)
		{
			$lb_existe=$this->existe_columna($as_tabla,$as_columna);
			if ($lb_existe)
			{
				$li_longitud=$this->longitud_campo($as_tabla,$as_columna) ;
				if ($ab_empresa)
				{	
					$ls_sql=" SELECT ".$as_columna."".
							"   FROM ".$as_tabla."".
							"  WHERE codemp='".$as_codemp."'".
							"    AND codpai='".$as_columna2."'".
							"  ORDER BY ".$as_columna." DESC";		
					$rs_funciondb=$this->io_sql->select($ls_sql);
					if ($row=$this->io_sql->fetch_row($rs_funciondb))
					{ 
						$codigo=$row[$as_columna];
						settype($codigo,'int');                             // Asigna el tipo a la variable.
						$codigo = $codigo + 1;                              // Le sumo uno al entero.
						settype($codigo,'string');                          // Lo convierto a varchar nuevamente.
						$ls_codigo=$this->io_funcion->uf_cerosizquierda($codigo,$li_longitud);
					}
					else
					{
						$codigo="1";
						$ls_codigo=$this->io_funcion->uf_cerosizquierda($codigo,$li_longitud);
					}
				}	
				else
				{
					$ls_sql=" SELECT ".$as_columna."".
							"   FROM ".$as_tabla."".
							"  WHERE codpai='".$as_columna2."'".
							"  ORDER BY ".$as_columna." DESC";	
					$rs_funciondb=$this->io_sql->select($ls_sql);
					if ($row=$this->io_sql->fetch_row($rs_funciondb))
					{ 
						$codigo=$row[$as_columna];
						settype($codigo,'int');                                          // Asigna el tipo a la variable.
						$codigo = $codigo + 1;                                           // Le sumo uno al entero.
						settype($codigo,'string');                                       // Lo convierto a varchar nuevamente.
						$ls_codigo=$this->io_funcion->uf_cerosizquierda($codigo,$li_longitud); 
					}   
					else
					{
						$codigo="1";
						$ls_codigo=$this->io_funcion->uf_cerosizquierda($codigo,$li_longitud);
					}
				}// SI NO TIENE CODIGO DE EMPRESA
			}
			else
			{
			$ls_codigo="";
			$this->is_msg_error="No existe el campo" ;
			}
		}
		else
		{
			$ls_codigo="";
			$this->is_msg_error="No existe la tabla	" ;
		}
		return $ls_codigo;
	} // fin function uf_generar_codigo

	function longitud_campo($as_tabla,$as_columna)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_generar_codigo
		//	          Access:  public
		//	       Arguments:  $as_tabla   // nombre de la tabla
		//					   $as_columna // nombre de la columna
		//	         Returns:  $lb_valido.
		//		 Description:  Este método verifica la longitud de un campo
		//     Elaborado Por:  Ing. Nestor Falcón
		// Fecha de Creación:  02/08/2006      
		//	  Modificado Por:  Ing. Luis Anibal Lang
		// 		Fecha Modif.:  02/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	   $li_length = 0;
	   switch ($this->io_gestor)
	   {
	   		case "MYSQLT":
			   $ls_sql=" SELECT character_maximum_length AS width ".
					   " FROM information_schema.columns ".
					   " WHERE TABLE_SCHEMA='".$this->io_database."' AND UPPER(table_name)=UPPER('".$as_tabla."') AND ".
					   "       UPPER(column_name)=UPPER('".$as_columna."')";
			break;
	   		case "POSTGRES":
			  $ls_sql = " SELECT character_maximum_length AS width ".
						"   FROM INFORMATION_SCHEMA.COLUMNS ".
						"  WHERE table_catalog='".$this->io_database."'".
						"    AND UPPER(table_name)=UPPER('".$as_tabla."')".
						"    AND UPPER(column_name)=UPPER('".$as_columna."')";
			break;
	   }
	   $rs_data=$this->io_sql->select($ls_sql);
	   if ($row=$this->io_sql->fetch_row($rs_data))   {  $li_length=$row["width"];  } 
	   return $li_length; 
	} // fin function longitud_campo

	function existe_tabla($as_tabla)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  existe_tabla
		//	          Access:  public
		//	       Arguments:  $as_tabla   // nombre de la tabla
		//	         Returns:  $lb_valido.
		//		 Description:  Este método verifica la existencia de una tabla
		//     Elaborado Por:  Ing. Nestor Falcón
		// Fecha de Creación:  02/08/2006      
		//	  Modificado Por:  Ing. Luis Anibal Lang
		// 		Fecha Modif.:  02/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
       $lb_existe = false;
	   switch ($this->io_gestor)
	   {
	   		case "MYSQLT":
			   $ls_sql= " SELECT * FROM ".
						" INFORMATION_SCHEMA.TABLES ".
						" WHERE TABLE_SCHEMA='".$this->io_database."' AND (UPPER(TABLE_NAME)=UPPER('".$as_tabla."'))";				
			break;
	   		case "POSTGRES":
			   $ls_sql= " SELECT * FROM ".
						" INFORMATION_SCHEMA.TABLES ".
						" WHERE table_catalog='".$this->io_database."' AND (UPPER(table_name)=UPPER('".$as_tabla."'))";	
			break;
	   }
	   $rs_data=$this->io_sql->select($ls_sql);
	   if($rs_data===false)
	   {   
          $this->io_msg->message("ERROR en uf_select_table()".$this->io_funcion->uf_convertirmsg($this->io_sql->message));			
 		 return false; 
	   }
	   else
	   {
	 	  if ($row=$this->io_sql->fetch_row($rs_data)) { $lb_existe=true; } 
   		  $this->io_sql->free_result($rs_data);	 
   	   }	  
	   return $lb_existe;
	} // fin function existe_tabla

	function existe_columna($as_tabla,$as_columna)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  existe_columna
		//	          Access:  public
		//	       Arguments:  $as_tabla   // nombre de la tabla
		//					   $as_columna // nombre de la columna
		//	         Returns:  $lb_valido.
		//		 Description:  Este método verifica la existencia de una tabla
		//     Elaborado Por:  Ing. Nestor Falcón
		// Fecha de Creación:  02/08/2006      
		//	  Modificado Por:  Ing. Luis Anibal Lang
		// 		Fecha Modif.:  02/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
       $lb_existe = false;
	   switch ($this->io_gestor)
	   {
	   		case "MYSQLT":
			  $ls_sql = " SELECT COLUMN_NAME ".
						" FROM INFORMATION_SCHEMA.COLUMNS ".
						" WHERE TABLE_SCHEMA='".$this->io_database."' AND UPPER(TABLE_NAME)=UPPER('".$as_tabla."') AND UPPER(COLUMN_NAME)=UPPER('".$as_columna."')";
			break;
	   		case "POSTGRES":
			  $ls_sql = " SELECT COLUMN_NAME ".
						" FROM INFORMATION_SCHEMA.COLUMNS ".
						" WHERE table_catalog='".$this->io_database."' AND UPPER(table_name)=UPPER('".$as_tabla."') AND UPPER(column_name)=UPPER('".$as_columna."')";
			break;
	   }
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   
         $this->io_msg->message("ERROR en uf_select_column()".$this->io_funcion->uf_convertirmsg($this->io_sql->message));			
		 return false;
	  }
	  else
	  {
		  if ($row=$this->io_sql->fetch_row($rs_data)) { $lb_existe=true; } 
  		  $this->io_sql->free_result($rs_data);	 
	  }	  
	  return $lb_existe;
	} // fin function existe_columna
	
}   // fin de la class sigesp_scv_c_regiones
?> 