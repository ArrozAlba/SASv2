<?php
	class sigesp_c_seguridad
	{
		function sigesp_c_seguridad()
		{
			require_once("class_sql.php");
			require_once("sigesp_include.php");
			require_once("class_mensajes.php");
			require_once("class_funciones_db.php");
			require_once("class_funciones.php");
			$in=new sigesp_include();
			$this->con=$in->uf_conectar();
			$this->io_msg=     new class_mensajes();
			$this->io_funcion= new class_funciones();
			$this->io_sql=        new class_sql($this->con);
			$this->io_fun=     new class_funciones_db($this->con);
		}	
		
		function uf_sss_select_eventos($as_evento,$ls_descripcion)
		{
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//	     Function: uf_sss_select_eventos
			//         Access: public 
			//      Argumento: $as_evento        // codigo de evento
			//                 $ls_descripcion   // descripcion  de evento
			//	      Returns: Retorna un Booleano
			//    Description: Funcion que verifica la existencia de un evento en la tabla sss_eventos
			//	   Creado Por: Ing. Luis Anibal Lang
			// Fecha Creación: 01/11/2005 								Fecha Última Modificación : 
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$lb_valido=true;
			$ls_sql="";
			$ls_enabled= 1;
			$ls_sql="SELECT * FROM sss_eventos".
					" WHERE evento='".$as_evento."' ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("CLASE->seguridad MÉTODO->uf_sss_select_eventos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$ls_descripcion=$row["deseve"];
					$lb_valido=true;
				}
				else
				{
					$ls_descripcion="";
					$lb_valido=false;
				}
				$this->io_sql->free_result($rs_data);
			}
			return $lb_valido;
		} // end function uf_sss_select_eventos
		
		function uf_sss_insert_eventos_ventana($as_empresa,$as_sistema,$as_evento,$as_usuario,$as_ventana,$as_descripcion)
		{
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//	     Function: uf_sss_insert_eventos_ventana
			//         Access: public 
			//      Argumento: $as_empresa      // codigo de empresa
			//                 $as_sistema      // codigo de sistema
			//                 $as_evento       // codigo de evento
			//                 $as_usuario      // codigo de usuario
			//                 $as_ventana      // codigo de ventana
			//                 $as_descripcion  // descripcion  de evento
			//	      Returns: Retorna un Booleano
			//    Description: Funcion que inserta un evento que se origina en alguna operación  de INSERT, UPDATE ó DELETE 
			//				   dentro del Sistema y lo inserta en la tabla sss_registro_eventos
			//	   Creado Por: Ing. Luis Anibal Lang
			// Fecha Creación: 01/11/2005 								Fecha Última Modificación : 
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			if($as_usuario!="PSEGIS")
			{
				$lb_valido=true;
				$ls_sql="";
			
				$ld_fecha = date("Y-m-d H:i");
				$ls_ip=$this->getip();
				$ls_descripcion="";
				$ls_tabla="sss_registro_eventos";
				$ls_columna="numeve";
				$li_numeve=$this->io_fun->uf_generar_codigo("","",$ls_tabla,$ls_columna);
				$ls_sisope="N/D";
				$ls_sql= "INSERT INTO sss_registro_eventos (codemp, numeve, codusu, codsis, evento, nomven, fecevetra, equevetra,".
						 " desevetra, ususisoper)". 
						 " VALUES ('".$as_empresa."','".$li_numeve."','".$as_usuario."','".$as_sistema."','".$as_evento."',".
						 " '".$as_ventana."','".$ld_fecha."','".$ls_ip."','".$as_descripcion."','".$ls_sisope."')" ;
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$this->io_msg->message("CLASE->seguridad MÉTODO->uf_sss_insert_eventos_ventana ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;
				}
				else
				{
					$lb_valido=true;
				}
			}
			else
			{
				$lb_valido=true;
			}
		  	return $lb_valido;
		} // end  function uf_sss_insert_eventos_ventana		
		
		function uf_sss_select_permisos($as_empresa,$as_usuario,$as_sistema,$as_ventana)
		{
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//	     Function: uf_sss_select_permisos
			//         Access: public 
			//      Argumento: $as_empresa      // codigo de empresa
			//                 $as_usuario      // codigo de usuario
			//                 $as_sistema      // codigo de sistema
			//                 $as_ventana      // codigo de ventana
			//	      Returns: Retorna un Booleano
			//    Description: Funcion que verifica si un usuario tiene permiso en determinada pantalla o no comparando que el campo
			//				   "enabled" sea igual a 1 para "permiso otorgado". En la tabla sss_derechos_usuarios
			//	   Creado Por: Ing. Luis Anibal Lang
			// Fecha Creación: 01/11/2005 								Fecha Última Modificación : 
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$lb_valido=true;
			$ls_sql="";
			$ls_enabled= 1;
			$ls_sql="SELECT * FROM sss_derechos_usuarios".
					" WHERE codemp='".$as_empresa."'".
					" AND codusu='".$as_usuario."'".
					" AND codsis='".$as_sistema."'".
					" AND nomven='".$as_ventana."'".
					" AND enabled=".$ls_enabled." ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("CLASE->seguridad MÉTODO->uf_sss_select_permisos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$lb_valido=true;
				}
				else
				{
					$this->io_msg->message("NO TIENE PERMISO");
					$lb_valido=false;
				}
				$this->io_sql->free_result($rs_data);
			}
			return $lb_valido;
		} // end  function uf_sss_select_permisos


		function uf_sss_load_permisos($as_empresa,$as_usuario,$as_sistema,$as_ventana,&$aa_permisos)
		{
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//	     Function: uf_sss_select_permisos
			//         Access: public 
			//      Argumento: $as_empresa       // codigo de empresa
			//                 $as_usuario       // codigo de usuario
			//                 $as_sistema       // codigo de sistema
			//                 $as_ventana       // codigo de ventana
			//                 $aa_permisos      // arreglo que contiene los permisos de la barra de herramienta
			//	      Returns: Retorna un Booleano
			//    Description: Funcion que verifica si un usuario tiene permiso en determinada pantalla o no comparando que el campo
			//				   "enabled" sea igual a 1 para "permiso otorgado" y carga en un arreglo todos los permisos de la barra
			//				   de herramientas En la tabla sss_derechos_usuarios
			//	   Creado Por: Ing. Luis Anibal Lang
			// Fecha Creación: 01/11/2005 								Fecha Última Modificación : 
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$lb_valido=true;
			$ls_sql="";
			$ls_enabled= 1;
			$ls_sql="SELECT * FROM sss_derechos_usuarios".
					" WHERE codemp='".$as_empresa."'".
					" AND codusu='".$as_usuario."'".
					" AND codsis='".$as_sistema."'".
					" AND nomven='".$as_ventana."'".
					" AND enabled=".$ls_enabled." ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("CLASE->seguridad MÉTODO->uf_sss_select_permisos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$lb_valido=true;
					$aa_permisos["leer"]=     $row["leer"];
					$aa_permisos["incluir"]=  $row["incluir"];
					$aa_permisos["cambiar"]=  $row["cambiar"];
					$aa_permisos["eliminar"]= $row["eliminar"];
					$aa_permisos["imprimir"]= $row["imprimir"];
					$aa_permisos["anular"]=   $row["anular"];
					$aa_permisos["ejecutar"]= $row["ejecutar"];
				}
				else
				{
					$this->io_msg->message("NO TIENE PERMISO");
					$lb_valido=false;
				}
				$this->io_sql->free_result($rs_data);
			}
			return $lb_valido;
		} // end  function uf_sss_select_permisos

		function uf_sss_load_permisossigesp()
		{
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//	     Function: uf_sss_load_permisossigesp
			//         Access: public 
			//      Argumento: $aa_permisos      // arreglo que contiene los permisos de la barra de herramienta
			//	      Returns: Retorna un Booleano
			//    Description: Funcion que otorga todos los permisos al usuario SIGESP
			//	   Creado Por: Ing. Luis Anibal Lang
			// Fecha Creación: 01/11/2005 								Fecha Última Modificación : 
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$aa_permisos["leer"]=1;
			$aa_permisos["incluir"]=1;
			$aa_permisos["cambiar"]=1;
			$aa_permisos["eliminar"]=1;
			$aa_permisos["imprimir"]=1;
			$aa_permisos["anular"]=1;
			$aa_permisos["ejecutar"]=1;
			return $aa_permisos;
		} // end  function uf_sss_select_permisossigesp

		function getip()
		{
		   if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"),"unknown"))
				   $ip = getenv("HTTP_CLIENT_IP");
		   else if (getenv("HTTP_X_FORWARDED_FOR ") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR "), "unknown"))
				   $ip = getenv("HTTP_X_FORWARDED_FOR ");
		   else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
				   $ip = getenv("REMOTE_ADDR");
		   else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
				   $ip = $_SERVER['REMOTE_ADDR'];
		   else
				   $ip = "unknown";
		   
		   return($ip);
		}

	}//  end class sigesp_c_seguridad
?>