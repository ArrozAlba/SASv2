<?php 
class sigesp_sss_c_usuariosgrupos
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sss_c_usuariosgrupos()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad;
		$this->io_msg=new class_mensajes();
		$this->io_funcion = new class_funciones();
	}

	function  uf_sss_select_usuario_grupo($as_temporal,$as_codemp,$as_codgru)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_usuario_grupo
		//         Access: public  
		//      Argumento: $as_temporal   //codigo de usuario seleccionado
		//                 $as_codemp     //codigo de empresa
		//                 $as_codgru     //Codigo del grupo
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de verificar si un usuario existe en determinado grupo
		//	   Modificado Por: Ing. NElson Barraez
		// Fecha Creación: 27/05/2010								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT codusu FROM sss_usuarios_en_grupos".
				  " WHERE codusu = '".$as_temporal."'".
				  " AND codemp ='".$as_codemp."'".
				  " AND codgru ='".$as_codgru."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosgrupos MÉTODO->uf_sss_select_usuario_grupo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  // end  function  uf_sss_select_usuario_grupo
	
	function  uf_sss_load_grupos($as_codemp,&$aa_grupos)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_grupos
		//         Access: public  
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $aa_grupos      //arreglo de grupos
		//	      Returns: Retorna un Booleano
		//    Description: Función que carga los datos de los grupos
		//	   Modificado Por: Ing. Nelson Barraez
		// Fecha Creación: 27/05/2010								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codgru,nomgru FROM sss_grupos".
				" WHERE  codemp ='".$as_codemp."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosgrupos MÉTODO->uf_sss_load_grupos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_grupos[$li_pos]["codgru"]=$row["codgru"];  
				$aa_grupos[$li_pos]["nomgru"]=$row["nomgru"];  
				$li_pos=$li_pos+1;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_grupos


	function  uf_sss_load_usuariosdisponibles($as_codemp,$as_codgru,&$aa_disponibles)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_usuariosdisponibles
		//         Access: public  
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $as_codgru      //Codigo del grupo
		//                 $aa_disponibles //arreglo de usuarios disponibles
		//	      Returns: Retorna un Booleano
		//    Description: Función que carga los datos de los usuarios que estan disponibles para un determinado grupo
		//	   Modificado Por: Ing. Nelson Barraez
		// Fecha Creación: 27/05/2010								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM sss_usuarios".
				" WHERE NOT codusu IN".
				" (SELECT codusu FROM sss_usuarios_en_grupos".
					" WHERE codemp ='".$as_codemp."'".
					" AND codgru ='".$as_codgru."')";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosgrupos MÉTODO->uf_sss_load_usuariosdisponibles ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_disponibles["codusu"][$li_pos]=$row["codusu"];  
				$li_pos=$li_pos+1;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_usuariosdisponibles

	function  uf_sss_load_usuariosasignados($as_codemp,$as_codgru,&$aa_asignados)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_usuariosasignados
		//         Access: public  
		//      Argumento: $as_codemp     //codigo de empresa
		//                 $as_codmgru    //Codigo del grupo
		//                 $aa_asignados  //arreglo de usuarios asignados
		//	      Returns: Retorna un Booleano
		//    Description: Función que carga los datos de los usuarios que estan asignados para un determinado grupo
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 26/05/2010								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codusu FROM sss_usuarios_en_grupos".
				 " WHERE codemp ='".$as_codemp."'".
				 " AND codgru ='".$as_codgru."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosgrupos MÉTODO->uf_sss_load_usuariosasignados ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_asignados["codusu"][$li_pos]=$row["codusu"];  
				$li_pos=$li_pos+1;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_usuariosasignados


	function  uf_sss_insert_usuario_grupo($as_codemp,$as_codgru,$as_codusu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_insert_usuario_grupo
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_codgru    // Codigo del grupo
		//      		   $as_codusu    // codigo de usuario
		//      		   $aa_seguridad  // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: funcion que inserta un usuario en determinado grupo en la tabla sss_usuarios_en_grupos
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 27/05/2010									Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "INSERT INTO sss_usuarios_en_grupos (codemp, codgru, codusu ) ".
					" VALUES('".$as_codemp."','".$as_codgru."','".$as_codusu."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->usuariosgrupos MÉTODO->uf_sss_insert_usuario_grupo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Usuario ".$as_codusu." al grupo ".$as_codgru." Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if ($lb_variable)
			{
				$lb_valido=true;
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
			}	

		}
		return $lb_valido;
	} // end  function  uf_sss_insert_usuario_grupo

	function uf_sss_delete_usuario_grupo($as_codemp,$as_codgru,$as_codusu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_delete_usuario_grupo
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_codgru    // Codigo del grupo
		//      		   $as_codusu    // codigo de usuario
		//      		   $aa_seguridad  // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: funcion que elimina un usuario en determinado grupo en la tabla sss_usuarios_en_grupos
		//	  Modificado Por: Ing. Nelson Barraez
		// Fecha Creación: 27/05/2010									Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = " DELETE FROM sss_usuarios_en_grupos".
					 " WHERE codemp= '".$as_codemp. "'".
					 " AND codgru= '".$as_codgru. "'".
					 " AND codusu= '".$as_codusu."'"; 
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->usuariosgrupos MÉTODO->uf_sss_delete_usuario_grupo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el Usuario ".$as_codusu." del grupo ".$as_codgru." Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////			
			if ($lb_variable)
			{
				$lb_valido=true;
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
			}	
		}
		return $lb_valido;
	}  // end  function uf_sss_delete_usuario_grupo
	
	//---------------------------------------------------------------------------------------------------------------------------
	function uf_sss_procesar_permisos($as_codemp,$as_nomgru,$as_codusu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_procesar_permisos
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_nomgru    // nombre del grupo
		//      		   $as_codusu    // codigo de usuario
		//	      Returns: Retorna un Booleano
		//    Description: funcion que procesa los permisos de un grupo a los usuarios asignados al mismo.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 06/09/2007									Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$rs_data=$this->uf_sss_load_permisos_grupos($as_codemp,$as_nomgru);
		while($row=$this->io_sql->fetch_row($rs_data))
		{
			$ls_codsis=$row["codsis"];
			$ls_nomven=$row["nomven"];
			$li_visible=$row["visible"];
			$li_enabled=$row["enabled"];
			$li_leer=$row["leer"];
			$li_incluir=$row["incluir"];
			$li_cambiar=$row["cambiar"];
			$li_eliminar=$row["eliminar"];
			$li_imprimir=$row["imprimir"];
			$li_administrativo=$row["administrativo"];
			$li_anular=$row["anular"];
			$li_ejecutar=$row["ejecutar"];
			$ls_codintper="---------------------------------";
			$lb_valido=$this->uf_sss_load_permisos_internos($as_codemp,$as_codusu,$ls_codsis,&$ls_codintper);
			if($lb_valido)
			{
				$lb_existe=$this->uf_sss_select_derecho_usuario($as_codemp,$as_codusu,$ls_codsis,$ls_nomven,$ls_codintper);
				if($lb_existe)
				{
					$lb_valido=$this->uf_sss_update_derecho_usuario($as_codemp,$as_codusu,$ls_codsis,$ls_nomven,$li_visible,
																	$li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																	$li_imprimir,$li_administrativo,$li_anular,$li_ejecutar,
																	$ls_codintper);
				}
				else
				{
					$lb_valido=$this->uf_sss_insert_derecho_usuario($as_codemp,$as_codusu,$ls_codsis,$ls_nomven,$li_visible,
																	$li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																	$li_imprimir,$li_administrativo,$li_anular,$li_ejecutar,
																	$ls_codintper);
				}
			}
			if(!$lb_valido)
			{
				break;
			}
		} // end while
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion="Se insertó el Perfil de Seguridad del Grupo ".$as_nomgru." al usuario ".$as_codusu.
							" Asociado a la empresa ".$as_codemp;
			$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	
	} //end function uf_sss_procesar_permisos
	//---------------------------------------------------------------------------------------------------------------------------
	function uf_sss_anular_permisos($as_codemp,$as_nomgru,$as_codusu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_anular_permisos
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_nomgru    // nombre del grupo
		//      		   $as_codusu    // codigo de usuario
		//	      Returns: Retorna un Booleano
		//    Description: funcion que procesa los permisos de un grupo a los usuarios asignados al mismo.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 06/09/2007									Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$rs_data=$this->uf_sss_load_permisos_grupos($as_codemp,$as_nomgru);
		while($row=$this->io_sql->fetch_row($rs_data))
		{
			$ls_codsis=$row["codsis"];
			$ls_nomven=$row["nomven"];
			$li_visible=0;
			$li_enabled=0;
			$li_leer=0;
			$li_incluir=0;
			$li_cambiar=0;
			$li_eliminar=0;
			$li_imprimir=0;
			$li_administrativo=0;
			$li_anular=0;
			$li_ejecutar=0;
			$ls_codintper="----------------------";
			$lb_valido=$this->uf_sss_load_permisos_internos($as_codemp,$as_codusu,$ls_codsis,&$ls_codintper);
			if($lb_valido)
			{
				$lb_valido=$this->uf_sss_update_derecho_usuario($as_codemp,$as_codusu,$ls_codsis,$ls_nomven,$li_visible,
																$li_enabled,$li_leer,$li_incluir,$li_cambiar,$li_eliminar,
																$li_imprimir,$li_administrativo,$li_anular,$li_ejecutar,
																$ls_codintper);
			}
			if(!$lb_valido)
			{
				break;
			}
		} // end while
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion="Se eliminó el Perfil de Seguridad del Grupo ".$as_nomgru." al usuario ".$as_codusu.
							" Asociado a la empresa ".$as_codemp;
			$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	
	} //end function uf_sss_anular_permisos
	//---------------------------------------------------------------------------------------------------------------------------
	function uf_sss_load_permisos_grupos($as_codemp,$as_nomgru)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_permisos_grupos
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_nomgru    // nombre del grupo
		//	      Returns: Retorna el resultado del select
		//    Description: Funcion que obtiene el perfil de seguridad para el grupo indicado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 07/09/2007									Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql="SELECT codsis,nomven,visible,enabled,leer,incluir,cambiar,eliminar,imprimir,administrativo,anular,ejecutar".
				"  FROM sss_derechos_grupos".
				" WHERE codemp='".$as_codemp."'".
				"   AND nomgru='".$as_nomgru."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosgrupos MÉTODO->uf_sss_load_permisos_grupos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{
			return $rs_data;		
		}
	
	} //end function uf_sss_load_permisos_grupos
	//---------------------------------------------------------------------------------------------------------------------------
	function uf_sss_load_permisos_internos($as_codemp,$as_codusu,$as_codsis,&$as_codintper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_permisos_internos
		//         Access: public  
		//      Argumento: $as_codemp    // Codigo de Empresa
		//      		   $as_codusu    // Codigo de Usuario
		//      		   $as_codsis    // Codigo de Sistema
		//      		   $as_codintper // Codigo Interno de Permisologia
		//	      Returns: Retorna el resultado del select
		//    Description: Funcion que obtiene los permisos internos de un usuario 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 07/09/2007									Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codintper".
				"  FROM sss_permisos_internos".
				" WHERE codemp='".$as_codemp."'".
				"   AND codusu='".$as_codusu."'".
				"   AND codsis='".$as_codsis."'";		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosgrupos MÉTODO->uf_sss_load_permisos_internos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codintper=$row["codintper"];
				$lb_valido=true;
			}
			else
			{
			 if ($as_codsis == 'SNO')
			 {
			   $this->io_msg->message("El Grupo posee permiso para Nomina, pero el usuario ".trim($as_codusu)." no tiene ninguna Nomina asociada");
			 }
			 elseif ($as_codsis == 'SPG')
			 {
			  $this->io_msg->message("El Grupo posee permiso para Presupuesto de Gasto, pero el usuario ".trim($as_codusu)." no tiene ninguna Estructura Presupuestaria asociada");
			 }
			 else
			 {	
			  $lb_valido=$this->uf_sss_insert_permisos_internos($as_codemp,$as_codusu,$as_codsis,$as_codintper);
			 }
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	
	} //end function uf_sss_load_permisos_internos
	//---------------------------------------------------------------------------------------------------------------------------
	function  uf_sss_select_derecho_usuario($as_codemp,$as_codusu,$as_codsis,$as_nomven,$as_codintper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_derecho_usuario
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_codusu    // codigo de usuario
		//      		   $as_codsis    // codigo de sistema
		//      		   $as_nomven    // nombre de la ventana (fisico)
		//      		   $as_codintper // codigo interno de permisos
		//    Description: Función que se encarga de verificar el derecho que tiene un usuario a una ventana del sistema
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/09/2007									Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "SELECT * FROM sss_derechos_usuarios ".
				 " WHERE codemp = '".$as_codemp."'".
				 "   AND codusu ='".$as_codusu."'".
				 "   AND codsis ='".$as_codsis."'".
				 "   AND nomven ='".$as_nomven."'".
				 "   AND codintper ='".$as_codintper."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosgrupos MÉTODO->uf_sss_select_derecho_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  // end  function  uf_sss_select_derecho_usuario
	//---------------------------------------------------------------------------------------------------------------------------
	function uf_sss_update_derecho_usuario($as_codemp,$as_codusu,$as_codsis,$as_nomven,$ai_visible,$ai_enabled,
										   $ai_leer,$ai_incluir,$ai_cambiar,$ai_eliminar,$ai_imprimir,$ai_administrador,
										   $ai_anular,$ai_ejecutar,$as_codintper) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_update_derecho_usuario
		//         Access: public (sigesp_sss_p_derecho_usuario)
		//      Argumento: $as_codemp        // codigo de empresa
		//      		   $as_codusu        // codigo de usuario
		//      		   $as_codsis        // codigo de sistema
		//      		   $as_nomven        // nombre de la ventana (fisico)
		//      		   $ai_visible       // indica si puede ver o no la pantalla
		//      		   $ai_enabled       // indica si tiene permiso o no a la pantalla
		//      		   $ai_leer          // indica si tiene permiso o no de lectura
		//      		   $ai_incluir       // indica si tiene permiso o no de incluir
		//      		   $ai_cambiar       // indica si tiene permiso o no demodificar
		//      		   $ai_imprimir      // indica si tiene permiso o no de imprimir
		//      		   $ai_administrador // indica si tiene permiso o no de administrador
		//      		   $ai_anular        // indica si tiene permiso o no de anular
		//      		   $ai_ejecutar      // indica si tiene permiso o no de ejecutar
		//      		   $as_codintper     // codigo interno de permisos
		//      		   $aa_seguridad     // arreglo de registro de seguridad
		//    Description: Función que se encarga de modificar permisos a un usuario en determinada  pantalla
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 11/09/2007									Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=false;
		 $ls_sql = "UPDATE sss_derechos_usuarios".
		 		   "   SET visible='". $ai_visible ."', enabled='". $ai_enabled ."', leer='". $ai_leer ."',".
				   "       incluir='". $ai_incluir ."', cambiar='". $ai_cambiar ."', eliminar='". $ai_eliminar ."',".
				   "       imprimir='". $ai_imprimir ."', administrativo='". $ai_administrador ."', anular='". $ai_anular ."',".
				   "       ejecutar='". $ai_ejecutar ."' ".
				   " WHERE codemp='" .$as_codemp ."'".
				   "   AND codusu='" .$as_codusu ."'".
				   "   AND codsis='" .$as_codsis ."'".
				   "   AND nomven='" .$as_nomven ."'".
				   "   AND codintper='" .$as_codintper ."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if ($li_row===false)
		{
			$this->io_msg->message("CLASE->usuariosgrupos MÉTODO->uf_sss_update_derecho_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
		}
	  return $lb_valido;

	} // end  function  uf_sss_update_derecho_usuario

	//---------------------------------------------------------------------------------------------------------------------------	
	function  uf_sss_insert_derecho_usuario($as_codemp,$as_codusu,$as_codsis,$as_nomven,$ai_visible,$ai_enabled,$ai_leer,
											$ai_incluir,$ai_cambiar,$ai_eliminar,$ai_imprimir,$ai_administrador,$ai_anular,
											$ai_ejecutar,$as_codintper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_insert_derecho_usuario
		//         Access: public (sigesp_sss_p_derecho_usuario)
		//      Argumento: $as_codemp        // codigo de empresa
		//      		   $as_codusu        // codigo de usuario
		//      		   $as_codsis        // codigo de sistema
		//      		   $as_nomven        // nombre de la ventana (fisico)
		//      		   $ai_visible       // indica si puede ver o no la pantalla
		//      		   $ai_enabled       // indica si tiene permiso o no a la pantalla
		//      		   $ai_leer          // indica si tiene permiso o no de lectura
		//      		   $ai_incluir       // indica si tiene permiso o no de incluir
		//      		   $ai_cambiar       // indica si tiene permiso o no demodificar
		//      		   $ai_habilitada    // indica si tiene permiso o no 
		//      		   $ai_imprimir      // indica si tiene permiso o no de imprimir
		//      		   $ai_administrador // indica si tiene permiso o no de administrador
		//      		   $ai_anular        // indica si tiene permiso o no de anular
		//      		   $ai_ejecutar      // indica si tiene permiso o no de ejecutar
		//      		   $as_codintper     // codigo interno de permisos
		//      		   $aa_seguridad     // arreglo de registro de seguridad
		//    Description: Función que se encarga de otorgar permisos a un usuario en determinada  pantalla
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 11/09/2007									Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "INSERT INTO sss_derechos_usuarios (codemp,codusu,codsis,nomven,visible,enabled,leer,incluir,cambiar,". 
				  "									  eliminar,imprimir,administrativo,anular,ejecutar,codintper) ".
				  "     VALUES('".$as_codemp."','".$as_codusu."','".$as_codsis."','".$as_nomven."',".$ai_visible.",".
				  " 	        ".$ai_enabled.",".$ai_leer.",".$ai_incluir.",".$ai_cambiar.",".$ai_eliminar.",".$ai_imprimir.",".
				  "             ".$ai_administrador.",".$ai_anular.",".$ai_ejecutar.",'".$as_codintper."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->usuariosgrupos MÉTODO->uf_sss_insert_derecho_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function  uf_sss_insert_derecho_usuario
	//---------------------------------------------------------------------------------------------------------------------------
	function  uf_sss_insert_permisos_internos($as_codemp,$as_codusu,$as_codsis,$as_codintper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_insert_permisos_internos
		//         Access: public (sigesp_sss_p_derecho_usuario)
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_codusu    // codigo de usuario
		//      		   $as_codsis    // codigo de sistema
		//				   $as_codintper // codigo interno de permisos
		//      		   $aa_seguridad // arreglo de registro de seguridad
		//    Description: Función que se encarga de insertar por defecto los datos en la tabla sss_permisos_internos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = " INSERT INTO sss_permisos_internos (codemp,codusu,codsis,codintper) ".
				  " VALUES('".$as_codemp."','".$as_codusu."','".$as_codsis."','".$as_codintper."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->derecho_usuarios MÉTODO->uf_sss_insert_permisos_internos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function  uf_sss_insert_permisos_internos
	//---------------------------------------------------------------------------------------------------------------------------



}//  end  class sigesp_sss_c_usuarios_grupos

?>
