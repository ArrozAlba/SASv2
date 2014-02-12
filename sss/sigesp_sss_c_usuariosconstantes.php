<?php 
class sigesp_sss_c_usuariosconstantes
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sss_c_usuariosconstantes()
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

	//---------------------------------------------------------------------------------------------------------------------------
	function  uf_sss_select_usuario_constantes($as_codemp,$as_codcons,$as_codusu)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_usuario_constantes
		//         Access: public  
		//      Argumento: $as_codemp   // codigo de empresa
		//                 $as_codcons  // codigo de constante
		//                 $as_codusu   // codigo de usuario
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de verificar si una nomina existe para un determinado usuario
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/10/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codusu".
				"  FROM sss_permisos_internos".
				" WHERE codemp = '".$as_codemp."'".
				"   AND codintper ='".$as_codcons."'".
				"   AND codusu ='".$as_codusu."'".
				"   AND codsis ='SNO'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosconstantes MÉTODO->uf_sss_select_usuario_constantes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  // end  function  uf_sss_select_usuario_nomina
	//---------------------------------------------------------------------------------------------------------------------------
	
	//---------------------------------------------------------------------------------------------------------------------------
	function  uf_sss_load_usuarios($as_codemp,&$aa_usuarios)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_usuarios
		//         Access: public  
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $aa_usuarios    //arreglo de usuarios
		//	      Returns: Retorna un Booleano
		//    Description: Función que carga los datos de los usuarios
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005								Fecha Última Modificación : 01/11/2005 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT nomusu,apeusu,codusu".
				"  FROM sss_usuarios".
				" WHERE codemp ='".$as_codemp."' ".
				" ORDER BY nomusu";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosconstantes MÉTODO->uf_sss_load_usuarios ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_usuarios[$li_pos]["nomusu"]=$row["nomusu"];  
				$aa_usuarios[$li_pos]["apeusu"]=$row["apeusu"];  
				$aa_usuarios[$li_pos]["codusu"]=$row["codusu"];  
				$li_pos=$li_pos+1;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_usuarios
	//---------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------
	function  uf_sss_load_constantesxnominasdisponibles($as_codemp,$as_codusu,&$aa_disponibles)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_constantesxnominasdisponibles
		//         Access: public  
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $as_codusu      //codigo de usuario
		//                 $aa_disponibles //arreglo de usuarios disponibles
		//	      Returns: Retorna un Booleano
		//    Description: Función que carga los datos de las nominas que estan disponibles para un determinado usuario
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/10/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false; 
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_concat="CONCAT(codnom,'-',codcons)";
			break;
			case "POSTGRES":
				$ls_concat="(codnom||'-'||codcons)";
			break;
		}
		$ls_sql="SELECT codnom,codcons,nomcon".
				"  FROM sno_constante".
				" WHERE codemp='".$as_codemp."'".
				"   AND conespseg='1'".
				"   AND ".$ls_concat." NOT IN (SELECT codintper".
				"                                            FROM sss_permisos_internos".
				"											WHERE codemp='".$as_codemp."'".
				"											  AND codusu='".$as_codusu."')";
/*		$ls_sql=" SELECT codnom,desnom FROM sno_nomina".
				"  WHERE codemp='".$as_codemp."' ".
				"AND NOT codnom IN".
				" (SELECT codintper FROM sss_permisos_internos".
				"   WHERE codemp ='".$as_codemp."'".
				"     AND codusu ='".$as_codusu."'".
				 "   AND codsis= 'SNO'".
				 "   AND codintper IN (SELECT codnom".
				 "						 FROM sno_nomina".
				 "						WHERE codemp='".$as_codemp."'))";*/
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosconstantes MÉTODO->uf_sss_load_constantesxnominasdisponibles ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_disponibles["codcons"][$li_pos]=$row["codnom"]."-".$row["codcons"];  
				$aa_disponibles["desnom"][$li_pos]=$row["codnom"]." - ".$row["nomcon"];  
				$li_pos++;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_nominasdisponibles
	//---------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------
	function  uf_sss_load_constantesxnominasasignadas($as_codemp,$as_codusu,&$aa_asignados)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_constantesxnominasasignadas
		//         Access: public  
		//      Argumento: $as_codemp     //codigo de empresa
		//                 $as_codusu     //codigo de usuario
		//                 $aa_asignados  //arreglo de usuarios asignados
		//	      Returns: Retorna un Booleano
		//    Description: Función que carga los datos de las nominas que estan asignados para un determinado usuario
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/10/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_concat="CONCAT(codnom,'-',codcons)";
			break;
			case "POSTGRES":
				$ls_concat="(codnom||'-'||codcons)";
			break;
		}
		$ls_sql= "SELECT codintper".
				 " FROM sss_permisos_internos".
				 " WHERE codemp= '".$as_codemp."'".
				 "   AND codusu= '".$as_codusu."'".
				 "   AND codsis= 'SNO'".
				 "   AND codintper IN (SELECT ".$ls_concat."".
				 "						 FROM sno_constante".
				 "						WHERE codemp='".$as_codemp."')";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosconstantes MÉTODO->uf_sss_load_constantesxnominasasignadas ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ls_codintper=$row["codintper"];
				$ls_codnom=substr($ls_codintper,0,4);
				$ls_codcons=substr($ls_codintper,5,10);
				$lb_valido=$this->uf_sss_load_constante($as_codemp,$ls_codnom,$ls_codcons,&$ls_dencon);
				$aa_asignados["codintper"][$li_pos]=$row["codintper"];  
				$aa_asignados["nomcon"][$li_pos]=$ls_codnom." - ".$ls_dencon;  
				$li_pos++;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_nominasasignadas
	//---------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------
	function  uf_sss_load_constante($as_codemp,$as_codnom,$as_codcons,&$as_dencon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_constante
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//                 $as_codnom    // codigo de nomina
		//                 $as_codcons   // codigo de constante
		//                 $aa_seguridad //arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Función que carga la denominacion de una constante
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 06/11/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="  SELECT nomcon".
				"    FROM sno_constante".
				"   WHERE codemp= '".$as_codemp."'".
				"     AND codnom= '".$as_codnom."'".
				"     AND codcons='".$as_codcons."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosconstantes MÉTODO->uf_sss_load_permisos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_dencon=$row["nomcon"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_permisos
	//---------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------
	function  uf_sss_insert_usuario_constante($as_codemp,$as_codcons,$as_codusu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_insert_usuario_constante
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_codcons    // codigo de constante por nomina (codigo interno de permisologia)
		//      		   $as_codusu    // codigo de usuario
		//      		   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: funcion que inserta un usuario en determinado nomina en la tabla sss_permisos_internos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/10/2006									Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "INSERT INTO sss_permisos_internos (codemp, codsis, codusu, codintper) ".
				  "     VALUES('".$as_codemp."','SNO','".$as_codusu."','".$as_codcons."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->usuariosconstantes MÉTODO->uf_sss_insert_usuario_constante ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Relacionó la Constante por Nomina ".$as_codcons." al usuario ".$as_codusu." Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end  function  uf_sss_insert_usuario_constante
	//---------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------
	function uf_sss_delete_usuario_constantes($as_codemp,$as_codcons,$as_codusu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_delete_usuario_constantes
		//         Access: public  
		//      Argumento: $as_codemp     // codigo de empresa
		//      		   $as_codcons     // codigo de constante por nomina
		//      		   $as_codusu     // codigo de usuario
		//      		   $aa_seguridad  // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: funcion que elimina un usuario en determinado nomina en la tabla sss_permisos_internos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 27/10/2006									Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= " DELETE FROM sss_permisos_internos".
				 " WHERE codemp= '".$as_codemp. "'".
				 "   AND codintper= '".$as_codcons. "'".
				 "   AND codusu= '".$as_codusu."'".
				 "   AND codsis='SNO'"; 
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->usuariosconstantes MÉTODO->uf_sss_delete_usuario_constantes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó la Nomina ".$as_codnom." al usuario ".$as_codusu." Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////			
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function uf_sss_delete_usuario_constantes
	//---------------------------------------------------------------------------------------------------------------------------

/*	//---------------------------------------------------------------------------------------------------------------------------
	function  uf_sss_load_permisos($as_codemp,$as_codnom,$as_codusu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_permisos
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//                 $as_codnom    // codigo de nomina
		//                 $as_codusu    // codigo de usuario
		//                 $aa_seguridad //arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Función que verifica si un usuario tiene definido algun perfil de seguridad en las nominas
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 27/10/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="  SELECT nomven,visible,enabled,leer,incluir,cambiar,eliminar,imprimir,administrativo,anular,ejecutar".
				"    FROM sss_derechos_usuarios".
				"   WHERE codemp= '".$as_codemp."'".
				"     AND codusu= '".$as_codusu."'".
				"     AND codsis='SNO' ".
				"GROUP BY nomven,visible,enabled,leer,incluir,cambiar,eliminar,imprimir,administrativo,anular,ejecutar";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosconstantes MÉTODO->uf_sss_load_permisos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ls_nomven=$row["nomven"];  
				$li_visible=$row["visible"];  
				$li_enabled=$row["enabled"];  
				$li_leer=$row["leer"];  
				$li_incluir=$row["incluir"];  
				$li_cambiar=$row["cambiar"];  
				$li_eliminar=$row["eliminar"];  
				$li_imprimir=$row["imprimir"];  
				$li_administrador=$row["administrativo"];  
				$li_anular=$row["anular"];  
				$li_ejecutar=$row["ejecutar"];  
				$lb_valido=$this->uf_sss_insert_derecho_usuario($as_codemp,$as_codusu,'SNO',$ls_nomven,$li_visible,$li_enabled,
									   					 		$li_leer,$li_incluir,$li_cambiar,$li_eliminar,$li_imprimir,
														 		$li_administrador,$li_anular,$li_ejecutar,$as_codnom);
				if(!$lb_valido)
				{break;}
				$li_pos=$li_pos+1;
			}
			if(($li_pos>0)&&($lb_valido))
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion="Actualizó el perfil de seguridad en la  Nomina ".$as_codnom." al usuario ".$as_codusu.
								 " Asociado a la empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_permisos
	//---------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------
	function  uf_sss_insert_derecho_usuario($as_codemp,$as_codusu,$as_codsis,$as_nomven,$ai_visible,$ai_enabled,$ai_leer,
											$ai_incluir,$ai_cambiar,$ai_eliminar,$ai_imprimir,$ai_administrador,$ai_anular,
											$ai_ejecutar,$as_codintper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_insert_derecho_usuario
		//         Access: public  
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
		//    Description: Función que se encarga de otorgar permisos a un usuario en determinada  pantalla
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 27/10/2006									Fecha Última Modificación : 
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
			$this->io_msg->message("CLASE->usuariosconstantes MÉTODO->uf_sss_insert_derecho_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function  uf_sss_insert_derecho_usuario
	//---------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------
	function  uf_sss_delete_permisos($as_codemp,$as_codnom,$as_codusu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_delete_permisos
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//                 $as_codusu    // codigo de usuario
		//                 $as_codnom    // codigo de nomina
		//                 $aa_seguridad //arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Función que elimina los permisos de un usuario a alguna nomina en especifico
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 27/10/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="DELETE FROM sss_derechos_usuarios".
			    " WHERE codemp='" .$as_codemp ."'".
			    "   AND codusu='" .$as_codusu ."'".
			    "   AND codsis='SNO'".
			    "   AND codintper='" .$as_codnom ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosconstantes MÉTODO->uf_sss_delete_permisos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion="Eliminó el perfil de seguridad en la  Nomina ".$as_codnom." al usuario ".$as_codusu.
							 " Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function  uf_sss_delete_permisos
	//---------------------------------------------------------------------------------------------------------------------------
	*/
}//  end  class sigesp_sss_c_usuariosconstantes

?>
