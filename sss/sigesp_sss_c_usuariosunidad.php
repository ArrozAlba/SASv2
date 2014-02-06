<?php 
class sigesp_sss_c_usuariosunidad
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sss_c_usuariosunidad()
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
		// Fecha Creación: 30/10/2006								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM sss_usuarios".
				" WHERE  codemp ='".$as_codemp."' ".
				" ORDER BY nomusu";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariospresupuesto MÉTODO->uf_sss_load_usuarios ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
    
	function  uf_sss_load_sistemas(&$aa_sistemas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_sistemas
		//         Access: public  
		//      Argumento: $aa_sistemas    // arreglo de sistemas
		//	      Returns: Retorna un Booleano
		//    Description: Función que carga los datos de los sistemas
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 22/05/20008								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql=" SELECT codsis, nomsis FROM sss_sistemas ".
		        " WHERE codsis IN ('SEP','SOC','CXP') ".
				" ORDER BY codsis";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariospresupuesto MÉTODO->uf_sss_load_sistemas ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_sistemas[$li_pos]["codsis"]=trim($row["codsis"]);  
				$aa_sistemas[$li_pos]["nomsis"]=trim($row["nomsis"]);  
				$li_pos=$li_pos+1;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_usuarios
	
	function  uf_sss_load_unidadesdisponibles($as_codemp,$as_codusu,$as_codsis,&$aa_disponibles,$as_usugrup)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_unidadesdisponibles
		//         Access: public  
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $as_codusu      //codigo de usuario
		//                 $aa_disponibles //arreglo de usuarios disponibles
		//	      Returns: Retorna un Booleano
		//    Description: Función que carga los datos de las Unidades Ejecutoras que se encuentran disponibles
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 22/07/2010								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_logusr = $_SESSION["la_logusr"];
		$ls_gestor = $_SESSION["ls_gestor"];
	    $li_estmodest =$_SESSION["la_empresa"]["estmodest"];

	    if($as_usugrup=='U')
		{/// 
			$ls_sql_seguridad = " AND spg_dt_unidadadministrativa.codestpro1||spg_dt_unidadadministrativa.codestpro2||spg_dt_unidadadministrativa.codestpro3||spg_dt_unidadadministrativa.codestpro4||spg_dt_unidadadministrativa.codestpro5||spg_dt_unidadadministrativa.estcla ".
								"  IN (SELECT codintper FROM sss_permisos_internos WHERE codusu = '".$as_codusu."' UNION SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu = '".$as_codusu."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru) ".								
								" AND spg_unidadadministrativa.coduniadm NOT IN (SELECT codintper FROM sss_permisos_internos WHERE codusu = '".$as_codusu."' and codsis = '".$as_codsis."')";
		}
		else
		{
			$ls_sql_seguridad = " AND spg_dt_unidadadministrativa.codestpro1||spg_dt_unidadadministrativa.codestpro2||spg_dt_unidadadministrativa.codestpro3||spg_dt_unidadadministrativa.codestpro4||spg_dt_unidadadministrativa.codestpro5||spg_dt_unidadadministrativa.estcla ".
								"  IN (SELECT codintper FROM sss_permisos_internos UNION SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru AND sss_permisos_internos_grupo.codgru='".$as_codusu."') ".								
								" AND spg_unidadadministrativa.coduniadm NOT IN (SELECT codintper FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE sss_permisos_internos_grupo.codgru = '".$as_codusu."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru )";
		}
			 $ls_sql= "SELECT distinct spg_unidadadministrativa.coduniadm, ".
                "             spg_unidadadministrativa.denuniadm ".
				"  FROM spg_unidadadministrativa, spg_dt_unidadadministrativa,spg_ep5 ".
				" WHERE spg_unidadadministrativa.codemp='".$as_codemp."' ".
				"   AND spg_unidadadministrativa.coduniadm <>'----------' ".
				"   AND spg_unidadadministrativa.codemp=spg_dt_unidadadministrativa.codemp ".
				"   AND spg_unidadadministrativa.coduniadm=spg_dt_unidadadministrativa.coduniadm ".
				"   AND spg_dt_unidadadministrativa.codemp=spg_ep5.codemp ".
				"   AND spg_dt_unidadadministrativa.estcla=spg_ep5.estcla ".
				"   AND spg_dt_unidadadministrativa.codestpro1=spg_ep5.codestpro1 ".
				"   AND spg_dt_unidadadministrativa.codestpro2=spg_ep5.codestpro2 ".
				"   AND spg_dt_unidadadministrativa.codestpro3=spg_ep5.codestpro3 ".
				"   AND spg_dt_unidadadministrativa.codestpro4=spg_ep5.codestpro4 ".
				"   AND spg_dt_unidadadministrativa.codestpro5=spg_ep5.codestpro5 ".
				" ".$ls_sql_seguridad." ".
				"   ORDER BY denuniadm"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosunidad MÉTODO->uf_sss_load_unidadesdisponibles ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ls_codunidad=$row["coduniadm"];
				$ls_denunidad=$row["denuniadm"];
				$ls_unidad= str_pad($ls_codunidad,10,"0",0);
				$lb_existe=$this->uf_sss_select_usuario_unidad($as_codemp,$ls_unidad,$as_codusu,$as_codsis);
				if(!$lb_existe)
				{
					$aa_disponibles["coduniadm"][$li_pos]=$ls_unidad;  
					$aa_disponibles["denuniadm"][$li_pos]=$ls_denunidad;  
					$li_pos=$li_pos+1;
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_unidadesdisponibles

	function  uf_sss_select_usuario_unidad($as_codemp,$as_codunidad,$as_codusu,$as_codsis)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_usuario_unidad
		//         Access: public  
		//      Argumento: $as_codemp   // codigo de empresa
		//                 $as_codest   // codigo de estructura programatica
		//                 $as_codusu   // codigo de usuario
		//                 $as_sistema  // codigo de sistema
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de verificar si una unidad ejecutora existe para un determinado usuario y sistema
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 21/05/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT codusu FROM sss_permisos_internos".
				  " WHERE codemp = '".$as_codemp."'".
				  "   AND codintper ='".$as_codunidad."'".
				  "   AND codusu ='".$as_codusu."'".
				  "   AND codsis ='".$as_codsis."'";	  
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosunidad MÉTODO->uf_sss_select_usuario_unidad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  // end  function  uf_sss_select_usuario_unidad

	function  uf_sss_select_derechos_usuarios($as_codemp,$as_codunidad,$as_codusu,$as_codsis)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_derechos_usuarios
		//         Access: public  
		//      Argumento: $as_codemp      // codigo de empresa
		//                 $as_codunidad   // codigo de la unidad ejecutora
		//                 $as_codusu      // codigo de usuario
		//                 $as_codsis      // codigo de sistema
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de verificar si una unidad ejecutora existe para un determinado usuario
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 21/05/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT codusu FROM sss_derechos_usuarios".
				  " WHERE codemp = '".$as_codemp."'".
				  "   AND codintper ='".$as_codunidad."'".
				  "   AND codusu ='".$as_codusu."'".
				  "   AND codsis ='".$as_codsis."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosunidad MÉTODO->uf_sss_select_derechos_usuarios ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  // end  function  uf_sss_select_derechos_usuarios
	
	function  uf_sss_load_unidadesasignadas($as_codemp,$as_codusu,$as_codsis,&$aa_asignados,$as_usugrup)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_unidadesasignadas
		//         Access: public  
		//      Argumento: $as_codemp     //codigo de empresa
		//                 $as_codusu     //codigo de usuario
		//                 $as_codsis     //codigo de sistema
		//                 $aa_asignados  //arreglo de usuarios asignados
		//	      Returns: Retorna un Booleano
		//    Description: Función que carga los datos de las unidades ejecutoras que estan asignados para un determinado usuario y sistema
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 22/07/2010								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		
		if($as_usugrup=='U')
		{
			$ls_sql= "SELECT codintper ".
					 "  FROM sss_permisos_internos ".
					 " WHERE codemp= '".$as_codemp."' ".
					 "   AND codusu= '".$as_codusu."' ".
					 "   AND codsis= '".$as_codsis."' ".
					 "   AND codintper <> '-----------------------------------------------------------------------------------------------------------------------------' ".
					 "   AND codintper <> '' ".
					 "   AND codintper <> '---------------------------------' ".
					 "   AND LENGTH(codintper)<=10 "; 			
		}
		else
		{
			$ls_sql= "SELECT codintper ".
					 "  FROM sss_permisos_internos_grupo ".
					 " WHERE codemp= '".$as_codemp."' ".
					 "   AND codgru= '".$as_codusu."' ";
		}					 
		$ls_sql=$ls_sql." AND codintper IN (SELECT coduniadm FROM spg_dt_unidadadministrativa)";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosunidad MÉTODO->uf_sss_load_unidadesasignadas ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ls_coduniadm = $row["codintper"];
				$ls_denuniadm = $this->uf_sss_nomunidad($as_codemp,$ls_coduniadm);
				$aa_asignados["codintper"][$li_pos]=$row["codintper"];
				$aa_asignados["denuniadm"][$li_pos]=$ls_denuniadm;  
				$li_pos=$li_pos+1;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_unidadesasignadas
    
	function  uf_sss_nomunidad($as_codemp,$as_coduniadm)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_nomunidad
		//         Access: public  
		//      Argumento: $as_codemp     //codigo de empresa
		//                 $as_coduniadm    //codigo de usuario
		//	      Returns: Retorna un String
		//    Description: Función que busca el nombre asociado a una unidad ejecutora
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 22/05/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "SELECT denuniadm ".
				 " FROM spg_unidadadministrativa".
				 " WHERE codemp    =   '".$as_codemp."'".
				 "   AND coduniadm =   '".$as_coduniadm."'"; //print $ls_sql."<br>";
		$ls_denunidad = "";		 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosunidad MÉTODO->uf_sss_load_nomunidad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
			    $ls_denunidad = $row["denuniadm"];  
			}
			$this->io_sql->free_result($rs_data);
		}
		return $ls_denunidad;
	}  // end  function  uf_sss_load_unidadesasignadas
	
	
	function  uf_sss_insert_usuario_unidad($as_codemp,$as_codunidad,$as_codusu,$as_codsis,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_insert_usuario_unidad
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_codest    // codigo de estructura presupuestaria (codigo interno de permisologia)
		//      		   $as_codusu    // codigo de usuario
		//      		   $as_codsis    // codigo de usuario
		//      		   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: funcion que inserta un usuario en determinado unidad ejecutora en la tabla sss_permisos_internos
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 21/05/2008									Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "INSERT INTO sss_permisos_internos (codemp, codsis, codusu, codintper) ".
				  "     VALUES('".$as_codemp."','".$as_codsis."','".$as_codusu."','".$as_codunidad."')" ; 
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{print ($this->io_sql->message);
			$this->io_msg->message("CLASE->usuariosunidad MÉTODO->uf_sss_insert_usuario_unidad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Relacionó la Unidad Ejecutora ".$as_codunidad." al usuario ".$as_codusu." Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end  function  uf_sss_insert_usuario_unidad

	function uf_sss_delete_usuario_unidad($as_codemp,$as_codunidad,$as_codusu,$as_codsis,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_delete_usuario_unidad
		//         Access: public  
		//      Argumento: $as_codemp     // codigo de empresa
		//      		   $as_codest     // codigo de estructura presupuestaria
		//      		   $as_codusu     // codigo de usuario
		//      		   $as_codsis     // codigo de sistema
		//      		   $aa_seguridad  // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: funcion que elimina un usuario en determinada unidad en la tabla sss_permisos_internos
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 21/05/2008									Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "DELETE FROM sss_permisos_internos".
				 " WHERE codemp= '".$as_codemp. "'".
				 "   AND codintper= '".$as_codunidad. "'".
				 "   AND codusu= '".$as_codusu."'".
				 "   AND codsis='".$as_codsis."'"; 
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{print ($this->io_sql->message);
			$this->io_msg->message("CLASE->usuariospresupuesto MÉTODO->uf_sss_delete_usuario_estructura ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó la Unidad Ejecutora ".$as_codunidad." al usuario ".$as_codusu." Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////			
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function uf_sss_delete_usuario_unidad

	function  uf_sss_load_permisos($as_codemp,$as_codest,$as_codusu,$as_codsis,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_permisos
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//                 $as_codest    // codigo de estructura presupuestaria
		//                 $as_codusu    // codigo de usuario
		//                 $as_codsis    // codigo de sistema
		//                 $aa_seguridad //arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Función que verifica si un usuario tiene definido algun perfil de seguridad en las unidades ejecutoras y sistema.
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 30/10/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="  SELECT nomven,MAX(visible) AS visible,MAX(enabled) AS enabled,MAX(leer) AS leer,MAX(incluir) AS incluir,".
				"         MAX(cambiar)AS cambiar,MAX(eliminar) AS eliminar,MAX(imprimir) AS imprimir,MAX(administrativo) AS administrativo,".
				"         MAX(anular) AS anular,MAX(ejecutar) AS ejecutar".
				"    FROM sss_derechos_usuarios".
				"   WHERE codemp= '".$as_codemp."'".
				"     AND codusu= '".$as_codusu."'".
				"     AND codsis= '".$as_codsis."'".
				"   GROUP BY nomven";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosunidad MÉTODO->uf_sss_load_permisos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
				$lb_valido=$this->uf_sss_insert_derecho_usuario($as_codemp,$as_codusu,$as_codsis,$ls_nomven,$li_visible,$li_enabled,
									   					 		$li_leer,$li_incluir,$li_cambiar,$li_eliminar,$li_imprimir,
														 		$li_administrador,$li_anular,$li_ejecutar,$as_codest);
				if(!$lb_valido)
				{break;}
				$li_pos=$li_pos+1;
			}
			if(($li_pos>0)&&($lb_valido))
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion="Actualizó el perfil de seguridad en la Estructura ".$as_codest." al usuario ".$as_codusu.
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
			$this->io_msg->message("CLASE->usuariosunidad MÉTODO->uf_sss_insert_derecho_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function  uf_sss_insert_derecho_usuario

	function  uf_sss_delete_permisos($as_codemp,$as_codunidad,$as_codusu,$as_codsis,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_delete_permisos
		//         Access: public  
		//      Argumento: $as_codemp     // codigo de empresa
		//                 $as_codusu     // codigo de usuario
		//                 $as_codunidad  // codigo de unidad
		//                 $as_codsis     // codigo de sistema
		//                 $aa_seguridad  // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Función que elimina los permisos de un usuario a alguna nomina en especifico
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 22/05/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="DELETE FROM sss_derechos_usuarios".
			    " WHERE codemp='" .$as_codemp ."'".
			    "   AND codusu='" .$as_codusu ."'".
			    "   AND codsis='" .$as_codsis ."'".
			    "   AND codintper='" .$as_codunidad ."'";		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosunidad MÉTODO->uf_sss_delete_permisos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion="Eliminó el perfil de seguridad en la Unidad ".$as_codunidad." al usuario ".$as_codusu.
							 " Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function  uf_sss_delete_permisos
	
	function uf_llenar_combo_usuarios(&$aa_usuarios)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_llenar_combo_usuarios
		//         Access: public (sigesp_c_permisos_globales)
		//      Argumento: $aa_usuarios // arreglo de valores que puede tomar el combo.
		//    Description: Función que se encarga de llenar el arreglo del combo de Usuarios
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 22/07/2010									Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM sss_usuarios".
				" ORDER BY codusu ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		$li_pos=0;
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->permisos_globales MÉTODO->uf_llenar_combo_usuarios ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_pos=$li_pos+1;
				$aa_usuarios["codusu"][$li_pos]=$row["codusu"];   
				$aa_usuarios["codusu"][$li_pos]=$row["codusu"];   
			}
			$lb_valido=true;
		}
	} // end function uf_llenar_combo_usuarios

	function uf_pintar_combo_usuarios($aa_usuarios,$as_usuario)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_pintar_combo_usuarios
		//         Access: public (sigesp_c_permisos_globales)
		//      Argumento: $aa_usuarios // arreglo de valores que puede tomar el combo.
		//      		   $as_usuario // item seleccionado.
		//    Description: Función que se encarga de cargar el combo de usuarios 
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 22/07/2010									Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		print "<select name='cmbusuarios' id='cmbusuarios' onChange='ue_seleccionar();' style='width:200px'>";
		print "<option value= --- selected>--Seleccione Uno-- </option>";
		$li_total=count($aa_usuarios["codusu"]);
		for($i=1; $i <= $li_total ; $i++)
		{			
			if($aa_usuarios["codusu"][$i]==$as_usuario)
			{
				print "<option value='".$aa_usuarios["codusu"][$i]."' selected>".$aa_usuarios["codusu"][$i]."</option>";
			}
			else
			{
				print "<option value='".$aa_usuarios["codusu"][$i]."'>".$aa_usuarios["codusu"][$i]."</option>";
			}
		}
		print"</select>";
	}  //  end  function uf_pintar_combo_usuarios
	
	function uf_llenar_combo_grupos(&$aa_grupos)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_llenar_combo_grupos
		//         Access: public (sigesp_c_usuariospresupuesto)
		//      Argumento: $aa_grupos // arreglo de valores que puede tomar el combo.
		//    Description: Función que se encarga de llenar el arreglo del combo de grupos
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 22/07/2010									Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codgru,nomgru FROM sss_grupos".
				" ORDER BY codgru ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		$li_pos=0;
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->permisos_globales MÉTODO->uf_llenar_combo_grupos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_pos=$li_pos+1;
				$aa_grupos["codgru"][$li_pos]=$row["codgru"];   
				$aa_grupos["nomgru"][$li_pos]=$row["nomgru"];   
			}
			$lb_valido=true;
		}
	} // end function uf_llenar_combo_usuarios

	function uf_pintar_combo_grupos($aa_grupos,$as_grupo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_pintar_combo_grupos
		//         Access: public (sigesp_c_usuariospresupuesto)
		//      Argumento: $aa_usuarios // arreglo de valores que puede tomar el combo.
		//      		   $as_grupos // item seleccionado.
		//    Description: Función que se encarga de cargar el combo de usuarios 
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 22/07/2010									Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		print "<select name='cmbgrupos' id='cmbgrupos' onChange='ue_seleccionar();' style='width:200px'>";
		print "<option value= --- selected>--Seleccione Uno-- </option>";
		$li_total=count($aa_grupos["codgru"]);
		for($i=1; $i <= $li_total ; $i++)
		{			
			if($aa_grupos["codgru"][$i]==$as_grupo)
			{
				print "<option value='".$aa_grupos["codgru"][$i]."' selected>".$aa_grupos["nomgru"][$i]."</option>";
			}
			else
			{
				print "<option value='".$aa_grupos["codgru"][$i]."'>".$aa_grupos["nomgru"][$i]."</option>";
			}
		}
		print"</select>";
	}  //  end  function uf_pintar_combo_grupo
	
	function  uf_sss_select_grupo_unidad($as_codemp,$as_codunidad,$as_codgru)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_usuario_unidad
		//         Access: public  
		//      Argumento: $as_codemp   // codigo de empresa
		//                 $as_codest   // codigo de estructura programatica
		//                 $as_codgru   // codigo de grupo
		//                 $as_sistema  // codigo de sistema
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de verificar si una unidad ejecutora existe para un determinado usuario y sistema
		//	   Creado Por: Ing. NElson Barraez
		// Fecha Creación: 22/07/2010								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT codgru FROM sss_permisos_internos_grupo".
				  " WHERE codemp = '".$as_codemp."'".
				  "   AND codintper ='".$as_codunidad."'".
				  "   AND codgru ='".$as_codgru."'";	  
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosunidad MÉTODO->uf_sss_select_grupo_unidad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  // end  function  uf_sss_select_usuario_unidad
	
	function  uf_sss_insert_grupo_unidad($as_codemp,$as_codunidad,$as_codgru,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_insert_usuario_unidad
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_codest    // codigo de estructura presupuestaria (codigo interno de permisologia)
		//      		   $as_codgru    // codigo de grupo
		//      		   $as_codsis    // codigo de usuario
		//      		   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: funcion que inserta un usuario en determinado unidad ejecutora en la tabla sss_permisos_internos
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 21/05/2008									Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "INSERT INTO sss_permisos_internos_grupo (codemp, codgru, codintper) ".
				  "     VALUES('".$as_codemp."','".$as_codgru."','".$as_codunidad."')" ; 
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{print ($this->io_sql->message);
			$this->io_msg->message("CLASE->usuariosunidad MÉTODO->uf_sss_insert_usuario_unidad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Relacionó la Unidad Ejecutora ".$as_codunidad." al grupo ".$as_codgru." Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end  function  uf_sss_insert_grupo_unidad

	function uf_sss_delete_grupo_unidad($as_codemp,$as_codunidad,$as_codgru,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_delete_usuario_unidad
		//         Access: public  
		//      Argumento: $as_codemp     // codigo de empresa
		//      		   $as_coduniadm  // codigo de unidad
		//      		   $as_codgru     // codigo de grupo
		//      		   $as_codsis     // codigo de sistema
		//      		   $aa_seguridad  // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: funcion que elimina un usuario en determinada unidad en la tabla sss_permisos_internos
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 22/07/2010									Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "DELETE FROM sss_permisos_internos_grupo".
				 " WHERE codemp= '".$as_codemp. "'".
				 "   AND codintper= '".$as_codunidad. "'".
				 "   AND codgru= '".$as_codgru."'"; 
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{print ($this->io_sql->message);
			$this->io_msg->message("CLASE->usuariosunidad MÉTODO->uf_sss_delete_grupo_unidad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó la Unidad Ejecutora ".$as_codunidad." al grupo ".$as_codgru." Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////			
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function uf_sss_delete_grupo_unidad
	
}//  end  class sigesp_sss_c_usuariosunidad

?>
