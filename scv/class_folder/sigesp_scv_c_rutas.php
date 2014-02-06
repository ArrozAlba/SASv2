<?php
class sigesp_scv_c_rutas
{

	var $ls_sql;
	
	function sigesp_scv_c_rutas($conn)
	{
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_funciones_db.php");
		$this->io_fundb= new class_funciones_db($conn);
		$this->seguridad = new sigesp_c_seguridad();          
		$this->io_funcion = new class_funciones();
		$this->io_sql= new class_sql($conn);		
		$this->io_msg= new class_mensajes(); 
	}
 
	function uf_scv_load_rutas($as_codemp,$as_codrut,$as_codpaiori,$as_codestori,$as_codciuori,&$ai_totrows,&$ao_object) 
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_load_rutas
		//	          Access:  public
		//	       Arguments:  $as_codemp    // código de empresa
		//        			   $as_codrut    // código de ruta
		//	                   $as_codpaiori // código de pais destino
		//        			   $as_codestori // código de estado destino
		//        			   $as_codciuori // código de ciudad destino
		//  			       $ai_totrows   // total de lineas del grid
		//  			   	   $ao_object    // arreglo de objetos para pintar el grid
		//	         Returns:  $lb_valido
		//	     Description:  Función que se encarga cargar las ciudades destino de una ruta
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  06/10/2006      
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_sql=" SELECT scv_rutas.codpaides,scv_rutas.codestdes,scv_rutas.codciudes,".
				"        (SELECT  despai FROM sigesp_pais".
				" 	       WHERE scv_rutas.codpaides=sigesp_pais.codpai) AS despaides,".
				"        (SELECT  desest FROM sigesp_estados".
				"	       WHERE scv_rutas.codpaides=sigesp_estados.codpai".
				"	         AND   scv_rutas.codestdes=sigesp_estados.codest) AS desestdes,".
				"        (SELECT  desciu FROM scv_ciudades".
				"	       WHERE scv_rutas.codpaides=scv_ciudades.codpai".
				"	         AND   scv_rutas.codestdes=scv_ciudades.codest".
				"	         AND   scv_rutas.codciudes=scv_ciudades.codciu) AS desciudes".
				"   FROM scv_rutas ".
				"  WHERE codemp='".$as_codemp."'".
				"    AND codrut='".$as_codrut."'".
				"    AND codpaiori='".$as_codpaiori."'".
				"    AND codestori='".$as_codestori."'".
				"    AND codciuori='".$as_codciuori."'".
				"  ORDER BY codpaides,codestdes,codciudes";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigecp_scv_c_rutas METODO->uf_scv_load_rutas ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codpaides=$row["codpaides"];
				$ls_despaides=$row["despaides"];
				$ls_codestdes=$row["codestdes"];
				$ls_desestdes=$row["desestdes"];
				$ls_codciudes=$row["codciudes"];
				$ls_desciudes=$row["desciudes"];
				
				$ai_totrows++;
				$ao_object[$ai_totrows][1]="<input name=txtdespaides".$ai_totrows." type=text   id=txtdespaides".$ai_totrows." class=sin-borde size=35 value='". $ls_despaides ."' readonly>".
										   "<input name=txtcodpaides".$ai_totrows." type=hidden id=txtcodpaides".$ai_totrows." class=sin-borde size=17 value='". $ls_codpaides ."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtdesestdes".$ai_totrows." type=text   id=txtdesestdes".$ai_totrows." class=sin-borde size=20 value='". $ls_desestdes ."' readonly>".
										   "<input name=txtcodestdes".$ai_totrows." type=hidden id=txtcodestdes".$ai_totrows." class=sin-borde size=17 value='". $ls_codestdes ."' readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtdesciudes".$ai_totrows." type=text   id=txtdesciudes".$ai_totrows." class=sin-borde size=35 value='". $ls_desciudes ."' readonly>".
										   "<input name=txtcodciudes".$ai_totrows." type=hidden id=txtcodciudes".$ai_totrows." class=sin-borde size=17 value='". $ls_codciudes ."' readonly>";
				$ao_object[$ai_totrows][4]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0></a>";
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // fin function uf_scv_load_rutas
	
	function uf_scv_select_rutas($as_codemp,$as_codrut,$as_codpaiori,$as_codestori,$as_codciuori,$as_codpaides,$as_codestdes,$as_codciudes) 
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_select_rutas
		//	          Access:  public
		//	       Arguments:  $as_codemp    // código de empresa
		//        			   $as_codrut    // código de ruta
		//	                   $as_codpaiori // código de pais de origen
		//        			   $as_codestori // código de estado de origen
		//        			   $as_codciuori // código de ciudad de origen
		//        			   $as_codpaides // código de pais destino
		//        			   $as_codestdes // código de estado destino
		//        			   $as_codciudes // código de ciudad destino
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de verificar la existencia de una ruta
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  05/10/2006      
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_sql=" SELECT scv_rutas.codrut".
				" FROM  scv_rutas".
				" WHERE codemp='".$as_codemp."'".
				" AND   codrut='".$as_codrut."'".
				" AND   codpaiori='".$as_codpaiori."'".
				" AND   codestori='".$as_codestori."'".
				" AND   codciuori='".$as_codciuori."'".
				" AND   codpaides='".$as_codpaides."'".
				" AND   codestdes='".$as_codestdes."'".
				" AND   codciudes='".$as_codciudes."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_sql->rollback();
			$this->io_msg->message("CLASE->sigesp_scv_c_rutas METODO->uf_scv_select_rutas ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // fin function uf_scv_select_rutas

	function uf_scv_insert_rutas($as_codemp,&$as_codrut,$as_codpaiori,$as_codestori,$as_codciuori,$as_codpaides,$as_codestdes,
								 $as_codciudes,$as_desrut,$aa_seguridad,$as_tipo) 
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_insert_rutas
		//	          Access:  public
		//	       Arguments:  $as_codemp    // código de empresa
		//        			   $as_codrut    // código de ruta
		//	                   $as_codpaiori // código de pais de origen
		//        			   $as_codestori // código de estado de origen
		//        			   $as_codciuori // código de ciudad de origen
		//        			   $as_codpaides // código de pais destino
		//        			   $as_codestdes // código de estado destino
		//        			   $as_codciudes // código de ciudad destino
		//        			   $as_desrut    // descripcion de la ruta
		//        			   $aa_seguridad // arreglo de seguridad
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga insertar una ruta de viaticos en la tabla scv_rutas
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  05/10/2006      
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codrutaux = $as_codrut;
		$lb_existe=$this->uf_scv_select_descripcion_rutas($as_codemp,$as_codrut,$as_desrutaux);
		if($lb_existe) 
		{
			if ($as_desrutaux!=$as_desrut)
			{
				$as_codrut=$this->io_fundb->uf_generar_codigo(true,$as_codemp,'scv_rutas','codrut');
			}
		}
		$ls_sql= " INSERT INTO scv_rutas (codemp,codrut,codpaiori,codestori,codciuori,codpaides,codestdes,codciudes,desrut) ".
				 " VALUES ('".$as_codemp."','".$as_codrut."','".$as_codpaiori."','".$as_codestori."','".$as_codciuori."','".$as_codpaides."',".
				 "         '".$as_codestdes."','".$as_codciudes."','".$as_desrut."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if ($li_row===false)
		{
			$this->io_sql->rollback();	
			if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
			{
				
				$as_codrut=$this->io_fundb->uf_generar_codigo(true,$as_codemp,'scv_rutas','codrut');
				$lb_valido=$this->uf_scv_insert_rutas($as_codemp,&$as_codrut,$as_codpaiori,$as_codestori,$as_codciuori,
													  $as_codpaides,$as_codestdes,$as_codciudes,$as_desrut,$aa_seguridad,'2');
			}
			else
			{
				$this->io_msg->message("CLASE->sigecp_scv_c_rutas METODO->uf_scv_insert_rutas ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			}
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion= "Insertó la ruta ".$as_codrut." Asociada a la empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               ///////////////////////////
			if(($ls_codrutaux!=$as_codrut)||($as_tipo=='2'))
			{
				$this->io_msg->message("Se Asigno el Código de Ruta: ".$as_codrut);
			}
			$lb_valido=true;
		}
		return $lb_valido;
	} // fin function uf_scv_insert_rutas

	function uf_scv_update_rutas($as_codemp,$as_codrut,$as_desrut,$aa_seguridad) 
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_update_rutas
		//	          Access:  public
		//	       Arguments:  $as_codemp    // código de empresa
		//        			   $as_codrut    // código de ruta
		//        			   $as_desrut    // descripcion de la ruta
		//        			   $aa_seguridad // arreglo de seguridad
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga actualizar la distancia entre dos ciudades
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  04/10/2006      
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_sql=" UPDATE scv_rutas".
				"    SET desrut='".$as_desrut."' ".
				"  WHERE codemp='".$as_codemp."'".
				"    AND codrut='".$as_codrut."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if ($li_row===false)
		{
			$this->io_msg->message("CLASE->sigesp_scv_c_rutas METODO->uf_scv_update_rutas ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion= "Actualizó la ruta ".$as_codrut." Asociada a la empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               ///////////////////////////
			$lb_valido=true;
		}
		return $lb_valido;
	} // fin function uf_scv_update_rutas

	function uf_scv_delete_destinos($as_codemp,$as_codrut,$as_codpaiori,$as_codestori,$as_codciuori,$as_codpaides,$as_codestdes,$as_codciudes,$aa_seguridad) 
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_delete_destinos
		//	          Access:  public
		//	       Arguments:  $as_codemp    // código de empresa
		//        			   $as_codrut    // código de ruta
		//	                   $as_codpaiori // código de pais de origen
		//        			   $as_codestori // código de estado de origen
		//        			   $as_codciuori // código de ciudad de origen
		//        			   $as_codpaides // código de pais destino
		//        			   $as_codestdes // código de estado destino
		//        			   $as_codciudes // código de ciudad destino
		//        			   $aa_seguridad // arreglo de seguridad
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga eliminar un destino en una ruta
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  05/10/2006      
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$this->io_sql->begin_transaction();
		$ls_sql=" DELETE FROM scv_rutas ".
				"  WHERE codemp='".$as_codemp."'".
				"    AND codrut='".$as_codrut."'".
				"    AND codpaiori='".$as_codpaiori."'".
				"    AND codestori='".$as_codestori."'".
				"    AND codciuori='".$as_codciuori."'".
				"    AND codpaides='".$as_codpaides."'".
				"    AND codestdes='".$as_codestdes."'".
				"    AND codciudes='".$as_codciudes."'";
		$rs_data=$this->io_sql->execute($ls_sql);
		if ($rs_data===false)
		{
			$this->io_sql->rollback();
			$this->io_msg->message("CLASE->sigesp_scv_c_rutas METODO->uf_scv_delete_destinos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion= "Eliminó un destino en la ruta ".$as_codrut." Asociada a la empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               ///////////////////////////
			$this->io_sql->commit();
			$lb_valido= true;
		} 		 
		return $lb_valido;
	} // fin function uf_scv_delete_distancias
	
	function uf_scv_delete_ruta($as_codemp,$as_codrut,$aa_seguridad) 
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_delete_ruta
		//	          Access:  public
		//	       Arguments:  $as_codemp    // código de empresa
		//        			   $as_codrut    // código de ruta
		//        			   $aa_seguridad // arreglo de seguridad
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga eliminar un destino en una ruta
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  05/10/2006      
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$lb_existe=$this->uf_check_relaciones($as_codemp,$as_codrut);
		if(!$lb_existe)
		{
			$this->io_sql->begin_transaction();
			$ls_sql=" DELETE FROM scv_rutas ".
					"  WHERE codemp='".$as_codemp."'".
					"    AND codrut='".$as_codrut."'";
			$rs_data=$this->io_sql->execute($ls_sql);
			if ($rs_data===false)
			{
				$this->io_sql->rollback();
				$this->io_msg->message("CLASE->sigesp_scv_c_rutas METODO->uf_scv_delete_ruta ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion= "Eliminó la ruta ".$as_codrut." Asociada a la empresa ".$as_codemp;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               ///////////////////////////
				$this->io_sql->commit();
				$lb_valido= true;
			} 		 
		}
		return $lb_valido;
	} // fin function uf_scv_delete_distancias

	function uf_check_relaciones($as_codemp,$as_codrut)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_check_relaciones
		//	          Access:  public
		//	       Arguments:  $as_codemp    // codigo de empresa.
		//        			   $as_codrut    // codigo de ruta
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de verificar si existen tablas relacionadas al Código de la Ruta. 
		//    Modificado Por:  Ing. Luis Anibal Lang
		//   Fecha de Modif.:  05/10/2006      
		//////////////////////////////////////////////////////////////////////////////
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

	function uf_check_existe($as_codemp,$as_codrut)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_check_existe
		//	          Access:  public
		//	       Arguments:  $as_codemp  // codigo de empresa.
		//        			   $as_codrut  // codigo de ruta
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de verificar si existen tablas relacionadas al Código de la Ruta. 
		//    Modificado Por:  Ing. Luis Anibal Lang
		//   Fecha de Modif.:  05/10/2006      
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codrut".
				"  FROM scv_rutas".
				" WHERE codemp='".$as_codemp."'".
				"   AND codrut='".$as_codrut."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigesp_scv_c_rutas METODO->uf_check_existe; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
		}
		return $lb_valido;	
	} //Fin de la function uf_check_existe



function uf_scv_select_descripcion_rutas($as_codemp,$as_codrut,&$as_desrutaux)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_select_descripcion_rutas
		//	          Access:  public
		//	       Arguments:  $as_codemp    // código de empresa
		//        			   $as_codrut    // código de ruta
		//	                   $as_desrutaux // descripción de la ruta
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de verificar la existencia de una ruta
		//     Elaborado Por:  Ing. María Beatriz Unda
		// Fecha de Creación:  25/11/2008      
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$as_desrutaux="";
		$lb_valido=false;
		$ls_sql=" SELECT scv_rutas.desrut".
				" FROM  scv_rutas".
				" WHERE codemp='".$as_codemp."'".
				" AND   codrut='".$as_codrut."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_sql->rollback();
			$this->io_msg->message("CLASE->sigesp_scv_c_rutas METODO->uf_scv_select_descripcion_rutas ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_desrutaux=$row["desrut"];
				$this->io_sql->free_result($rs_data);
			}
			
		}
		return $lb_valido;
	} // fin function uf_scv_select_descripcion_rutas


} // fin class sigesp_scv_c_ciudad
?>