<?php 
class sigesp_sss_c_derechogrupo
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sss_c_derechogrupo()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_msg=new class_mensajes();
		$this->seguridad= new sigesp_c_seguridad;
		$this->dat_emp=$_SESSION["la_empresa"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->io_funcion = new class_funciones();
	}


//---------------------------------------------------------------------------------------------------------------------------------	
	function  uf_sss_select_sistema($as_codemp,$as_codsis)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_sistema
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_codsis    // codigo de sistema
		//    Description: Función que se encarga de verificar el derecho que tiene un usuario a una ventana del sistema
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "SELECT codsis FROM sss_sistemas ".
				 " WHERE codsis ='".$as_codsis."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->derechogrupo MÉTODO->uf_sss_select_sistema ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  // end  function  uf_sss_select_sistema
//---------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------	
	function  uf_sss_select_derechogrupo($as_codemp,$as_nomgru,$as_codsis,$as_nomven,$as_codintper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_derechogrupo
		//         Access: public
		//      Argumento: $as_codemp    // Codigo de Empresa
		//      		   $as_nomgru    // Nombre del Grupo
		//      		   $as_codsis    // Codigo de Sistema
		//      		   $as_nomven    // Nombre de la Ventana (fisico)
		//      		   $as_codintper // Codigo Interno de Permisos
		//    Description: Función que se encarga de verificar el derecho que tiene un grupo a una ventana del sistema
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 02/08/2007									Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "SELECT * FROM sss_derechos_grupos ".
				 " WHERE codemp = '".$as_codemp."'".
				 "   AND nomgru ='".$as_nomgru."'".
				 "   AND codsis ='".$as_codsis."'".
				 "   AND nomven ='".$as_nomven."'".
				 "   AND codintper ='".$as_codintper."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->derechogrupo MÉTODO->uf_sss_select_derechogrupo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  // end  function  uf_sss_select_derechogrupo
//---------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------	
	function  uf_sss_insert_derechogrupo($as_codemp,$as_nomgru,$as_codsis,$as_nomven,$ai_visible,$ai_enabled,$ai_leer,
										 $ai_incluir,$ai_cambiar,$ai_eliminar,$ai_imprimir,$ai_administrador,$ai_anular,
										 $ai_ejecutar,$as_codintper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_insert_derechogrupo
		//         Access: public 
		//      Argumento: $as_codemp        // Codigo de Empresa
		//      		   $as_nomgru        // Nombre de Grupo
		//      		   $as_codsis        // Codigo de Sistema
		//      		   $as_nomven        // Nombre de la Ventana (fisico)
		//      		   $ai_visible       // Indica si puede ver o no la pantalla
		//      		   $ai_enabled       // Indica si tiene permiso o no a la pantalla
		//      		   $ai_leer          // Indica si tiene permiso o no de lectura
		//      		   $ai_incluir       // Indica si tiene permiso o no de incluir
		//      		   $ai_cambiar       // Indica si tiene permiso o no demodificar
		//      		   $ai_habilitada    // Indica si tiene permiso o no 
		//      		   $ai_imprimir      // Indica si tiene permiso o no de imprimir
		//      		   $ai_administrador // Indica si tiene permiso o no de administrador
		//      		   $ai_anular        // Indica si tiene permiso o no de anular
		//      		   $ai_ejecutar      // Indica si tiene permiso o no de ejecutar
		//      		   $as_codintper     // Codigo Interno de Permisos
		//      		   $aa_seguridad     // arreglo de registro de seguridad
		//    Description: Función que se encarga de otorgar permisos a un grupo en determinada  pantalla
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 03/08/2007									Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "INSERT INTO sss_derechos_grupos (codemp,nomgru,codsis,nomven,visible,enabled,leer,incluir,cambiar,". 
				  "									eliminar,imprimir,administrativo,anular,ejecutar,codintper) ".
				  "     VALUES('".$as_codemp."','".$as_nomgru."','".$as_codsis."','".$as_nomven."',".$ai_visible.",".
				  " 	        ".$ai_enabled.",".$ai_leer.",".$ai_incluir.",".$ai_cambiar.",".$ai_eliminar.",".$ai_imprimir.",".
				  "             ".$ai_administrador.",".$ai_anular.",".$ai_ejecutar.",'".$as_codintper."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->derechogrupo MÉTODO->uf_sss_insert_derechogrupo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Otorgó permiso al grupo ".$as_nomgru." en la pantalla ".$as_nomven." ".
							 "Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if ($lb_variable)
			{
				$lb_valido=true;
			}
		}
		return $lb_valido;
	}  // end  function  uf_sss_insert_derechogrupo
//---------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------	
	function uf_sss_update_derechogrupo($as_codemp,$as_nomgru,$as_codsis,$as_nomven,$ai_visible,$ai_enabled,$ai_leer,$ai_incluir,
										$ai_cambiar,$ai_eliminar,$ai_imprimir,$ai_administrador,$ai_anular,$ai_ejecutar,
										$as_codintper,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_update_derechogrupo
		//         Access: public 
		//      Argumento: $as_codemp        // Codigo de Empresa
		//      		   $as_nomgru        // Nombre de Grupo
		//      		   $as_codsis        // Codigo de Sistema
		//      		   $as_nomven        // Nombre de la Ventana (fisico)
		//      		   $ai_visible       // Indica si puede ver o no la pantalla
		//      		   $ai_enabled       // Indica si tiene permiso o no a la pantalla
		//      		   $ai_leer          // Indica si tiene permiso o no de lectura
		//      		   $ai_incluir       // Indica si tiene permiso o no de incluir
		//      		   $ai_cambiar       // Indica si tiene permiso o no demodificar
		//      		   $ai_imprimir      // Indica si tiene permiso o no de imprimir
		//      		   $ai_administrador // Indica si tiene permiso o no de administrador
		//      		   $ai_anular        // Indica si tiene permiso o no de anular
		//      		   $ai_ejecutar      // Indica si tiene permiso o no de ejecutar
		//      		   $as_codintper     // Codigo Interno de Permisos
		//      		   $aa_seguridad     // Arreglo de Registro de Seguridad
		//    Description: Función que se encarga de modificar permisos a un grupo en determinada  pantalla
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 03/08/2007									Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=false;
		 $ls_sql = "UPDATE sss_derechos_grupos".
		 		   "   SET  visible='". $ai_visible ."', enabled='". $ai_enabled ."', leer='". $ai_leer ."',".
				   "        incluir='". $ai_incluir ."', cambiar='". $ai_cambiar ."', eliminar='". $ai_eliminar ."',".
				   "        imprimir='". $ai_imprimir ."', administrativo='". $ai_administrador ."', anular='". $ai_anular ."',".
				   "        ejecutar='". $ai_ejecutar ."' ".
				   " WHERE codemp='" .$as_codemp ."'".
				   "   AND nomgru='" .$as_nomgru ."'".
				   "   AND nomgru='" .$as_nomgru ."'".
				   "   AND nomven='" .$as_nomven ."'".
				   "   AND codintper='" .$as_codintper ."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if ($li_row===false)
		{
			$this->io_msg->message("CLASE->derechogrupo MÉTODO->uf_sss_update_derechogrupo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el permiso del grupo ".$as_nomgru." en la pantalla ".$as_nomven." ".
							 "Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if ($lb_variable)
			{
				$lb_valido=true;
			}
		}
	  return $lb_valido;

	} // end  function  uf_sss_update_derechogrupo
//---------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------	
	function  uf_sss_insert_ventana($as_codsis,$as_nomven,$as_titven,$as_desven)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_insert_ventana
		//         Access: public (sigesp_sss_p_derecho_usuario)
		//      Argumento: $as_codsis    // Codigo de Sistema
		//      		   $as_nomven    // Nombre de la Ventana (fisico)
		//      		   $as_titven    // Titulo de la Ventana
		//      		   $as_desven    // Descripcion de Ventana
		//    Description: Función que se encarga de incluir una ventana nueva en la tabla sss_sistema_ventanas
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 03/08/2007									Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "INSERT INTO sss_sistemas_ventanas (codsis, nomven, titven, desven) ".
				 "VALUES('".$as_codsis."','".$as_nomven."','".$as_titven."','".$as_desven."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row==false)
		{
			$this->io_msg->message("CLASE->derechogrupo MÉTODO->uf_sss_insert_ventana ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function  uf_sss_insert_ventana
//---------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------	
	function  uf_sss_select_ventana($as_codsis,$as_nomven)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_ventana
		//         Access: public 
		//      Argumento: $as_codsis    // Codigo de Sistema
		//      		   $as_nomven    // Nombre de la Ventana (fisico)
		//    Description: Función que se encarga de verificar si existe  una ventana nueva en la tabla sss_sistema_ventanas
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 03/08/2007									Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "SELECT * FROM sss_sistemas_ventanas".
				 " WHERE codsis = '".$as_codsis."'".
				 "   AND nomven ='".$as_nomven."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->derechogrupo MÉTODO->uf_sss_select_ventana ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // end function  uf_sss_select_ventana
//---------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------	
	function  uf_sss_load_sistema($as_codsis,&$as_nomsis)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_sistema
		//         Access: public (sigesp_sss_p_derecho_usuario)
		//      Argumento: $as_codsis    // codigo de sistema
		//      		   $as_nomsis    // nombre del sistema
		//    Description: Función que se encarga de obtener los datos (nombre) de un sistema
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "SELECT * FROM sss_sistemas".
				 " WHERE codsis = '".$as_codsis."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->derechogrupo MÉTODO->uf_sss_load_sistema ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_nomsis=$row["nomsis"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // end function  uf_sss_load_sistema
//---------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------	
	function  uf_sss_load_permisos($as_codemp,$as_nomgru,$as_codsis,$as_nomven,&$ai_enabled,&$ai_leer,&$ai_incluir,&$ai_cambiar,
								   &$ai_eliminar,&$ai_imprimir,&$ai_anular,&$ai_ejecutar,&$ai_administrador)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_permisos
		//         Access: public (sigesp_sss_p_derecho_usuario)
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_nomgru    // nombre del grupo
		//      		   $as_codsis    // codigo del sistema
		//      		   $as_nomven    // nombre de ventana (fisico)
		//      		   $ai_enabled   // indica si tiene permiso o no para accesar a una pantalla 1->si 0->no
		//      		   $ai_leer      // indica si tiene permiso o no para revisar un catalogo 1->si 0->no
		//      		   $ai_incluir   // indica si tiene permiso o no para incluir un registro 1->si 0->no
		//      		   $ai_cambiar   // indica si tiene permiso o no para modificar un registro 1->si 0->no
		//      		   $ai_eliminar  // indica si tiene permiso o no para eliminar un registro 1->si 0->no
		//      		   $ai_imprimir  // indica si tiene permiso o no para imprimir un registro 1->si 0->no
		//      		   $ai_anular    // indica si tiene permiso o no para anular  un registro 1->si 0->no
		//      		   $ai_ejecutar  // indica si tiene permiso o no para procesar una operacion 1->si 0->no
		//      		   $ai_administrador  // indica si tiene permiso o no para modificar la fecha y el numero en SOC y SEP
		//    Description: Función que se encarga de obtener los datos (permiso) de un grupo en una pantalla
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 22/05/2007									Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM sss_derechos_grupos".
		 		" WHERE codemp ='".$as_codemp."'".
				"   AND nomgru ='".$as_nomgru."'".
				"   AND codsis ='".$as_codsis."'".
				"   AND nomven ='".$as_nomven."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->derechogrupo MÉTODO->uf_sss_load_permisos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ai_enabled=  $row["enabled"];
				$ai_leer=     $row["leer"];
				$ai_incluir=  $row["incluir"];
				$ai_cambiar=  $row["cambiar"];
				$ai_eliminar= $row["eliminar"];
				$ai_imprimir= $row["imprimir"];
				$ai_anular=   $row["anular"];
				$ai_ejecutar= $row["ejecutar"];
				$ai_administrador= $row["administrativo"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // end function  uf_sss_load_permisos
//---------------------------------------------------------------------------------------------------------------------------------	

	
//---------------------------------------------------------------------------------------------------------------------------------	
	function  uf_sss_insert_permisos_internos($as_codemp,$as_nomgru,$as_codsis,$as_codintper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_insert_permisos_internos
		//         Access: public (sigesp_sss_p_derecho_usuario)
		//      Argumento: $as_codemp    // Codigo de Empresa
		//      		   $as_nomgru    // Nombre de Grupo
		//      		   $as_codsis    // Codigo de Sistema
		//				   $as_codintper // Codigo Interno de Permisos
		//      		   $aa_seguridad // arreglo de registro de seguridad
		//    Description: Función que se encarga de insertar por defecto los datos en la tabla sss_permisos_internos_grupos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 03/08/2007									Fecha Última Modificación: 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_auxcodintper=$as_codintper;
		$lb_existe=$this->uf_select_permisos_grupales($as_codemp,$as_codsis,$as_nomgru,$ls_auxcodintper);
		if(!$lb_existe)
		{
			$ls_sql = " INSERT INTO sss_permisos_internos_grupos (codemp,codsis,nomgru,codintper) ".
				      " VALUES('".$as_codemp."','".$as_codsis."','".$as_nomgru."','".$as_codintper."')" ;
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->derechogrupo MÉTODO->uf_sss_insert_permisos_internos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			}
			else
			{
				$lb_valido=true;
			}
		}
		return $lb_valido;
	}  // end  function  uf_sss_insert_permisos_internos
//---------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------	
	function  uf_sss_insert_permisos_internos_personal($as_codemp,$as_codusu,$as_codsis,$as_codintper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_insert_permisos_internos
		//         Access: public (sigesp_sss_p_derecho_usuario)
		//      Argumento: $as_codemp    // Codigo de Empresa
		//      		   $as_nomgru    // Nombre de Grupo
		//      		   $as_codsis    // Codigo de Sistema
		//				   $as_codintper // Codigo Interno de Permisos
		//      		   $aa_seguridad // arreglo de registro de seguridad
		//    Description: Función que se encarga de insertar por defecto los datos en la tabla sss_permisos_internos_grupos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 03/08/2007									Fecha Última Modificación: 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_auxcodintper=$as_codintper;
		$lb_existe=$this->uf_select_permisos_internos($as_codemp,$as_codsis,$as_codusu,$aa_codintper);
		if(!$lb_existe)
		{
			$ls_sql = " INSERT INTO sss_permisos_internos (codemp,codusu,codsis,codintper) ".
				      " VALUES('".$as_codemp."','".$as_codusu."','".$as_codsis."','".$as_codintper."')" ;
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->derechogrupo MÉTODO->uf_sss_insert_permisos_internos_personal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  // end  function  uf_sss_insert_permisos_internos
//---------------------------------------------------------------------------------------------------------------------------------	
	
//---------------------------------------------------------------------------------------------------------------------------------	
	function  uf_sss_procesar_usuariosgrupos($as_codemp,$as_nomgru,$as_codsis,$as_nomven,$ai_visible,$ai_enabled,$ai_leer,
											 $ai_incluir,$ai_cambiar,$ai_eliminar,$ai_imprimir,$ai_administrador,$ai_anular,
											 $ai_ejecutar,$as_codintper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_procesar_usuariosgrupos
		//         Access: public 
		//      Argumento: $as_codemp        // Codigo de Empresa
		//      		   $as_nomgru        // Nombre de Grupo
		//      		   $as_codsis        // Codigo de Sistema
		//      		   $as_nomven        // Nombre de la Ventana (fisico)
		//      		   $ai_visible       // Indica si puede ver o no la pantalla
		//      		   $ai_enabled       // Indica si tiene permiso o no a la pantalla
		//      		   $ai_leer          // Indica si tiene permiso o no de lectura
		//      		   $ai_incluir       // Indica si tiene permiso o no de incluir
		//      		   $ai_cambiar       // Indica si tiene permiso o no demodificar
		//      		   $ai_imprimir      // Indica si tiene permiso o no de imprimir
		//      		   $ai_administrador // Indica si tiene permiso o no de administrador
		//      		   $ai_anular        // Indica si tiene permiso o no de anular
		//      		   $ai_ejecutar      // Indica si tiene permiso o no de ejecutar
		//      		   $as_codintper     // Codigo Interno de Permisos
		//      		   $aa_seguridad     // Arreglo de Registro de Seguridad
		//    Description: Función que se encarga de verificar el derecho que tiene un usuario a una ventana del sistema
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 03/08/2007									Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$lb_valido=$this->uf_sss_load_usuariosgrupo($as_codemp,$as_nomgru,&$aa_usuarios);
		if($lb_valido)
		{
			$li_cont=count($aa_usuarios);
			for($li_i=1;$li_i<=$li_cont;$li_i++)
			{
				$ls_codusu=$aa_usuarios[$li_i];
				$lb_exicodint=$this->uf_select_permisos_internos_grupo($as_codemp,$as_codsis,$ls_codusu,$aa_codintper);
				if($lb_exicodint)
				{
					$li_concod=count($aa_usuarios);
					for($li_j=1;$li_j<=$li_concod;$li_j++)
					{
						$lb_valido=$this->uf_sss_insert_permisos_internos_personal($as_codemp,$ls_codusu,$as_codsis,$as_codintper,
																		  $aa_seguridad);
					}
				}
				$lb_existe=$this->uf_sss_select_derecho_usuario($as_codemp,$ls_codusu,$as_codsis,$as_nomven,$as_codintper);
				if($lb_existe)
				{
					$lb_valido=$this->uf_sss_update_derecho_usuario($as_codemp,$ls_codusu,$as_codsis,$as_nomven,$ai_visible,
																	$ai_enabled,$ai_leer,$ai_incluir,$ai_cambiar,$ai_eliminar,
																	$ai_imprimir,$ai_administrador,$ai_anular,$ai_ejecutar,
																	$as_codintper,$aa_seguridad);
				}
				else
				{
					$lb_valido=$this->uf_sss_insert_derecho_usuario($as_codemp,$ls_codusu,$as_codsis,$as_nomven,$ai_visible,
																	$ai_enabled,$ai_leer,$ai_incluir,$ai_cambiar,$ai_eliminar,
																	$ai_imprimir,$ai_administrador,$ai_anular,$ai_ejecutar,
																	$as_codintper,$aa_seguridad);
				}
				
			}
		}
		return $lb_valido;
	}  // end  function  uf_sss_procesar_usuariosgrupos
//---------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------	
	function  uf_sss_load_usuariosgrupo($as_codemp,$as_nomgru,&$aa_usuarios)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_usuariosgrupo
		//         Access: public 
		//      Argumento: $as_codemp    // Codigo de Empresa
		//      		   $as_nomgru    // Codigo de Sistema
		//      		   $aa_usuarios  // Arreglo de Usuarios integrantes de un grupo
		//    Description: Función que se encarga de obtener los usuarios que estan en determinado grupo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 03/08/2007									Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codusu".
				" FROM sss_usuarios_en_grupos ".
				" WHERE codemp ='".$as_codemp."'".
				"   AND nomgru='".$as_nomgru."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->derechogrupo MÉTODO->uf_sss_load_usuariosgrupo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_i=$li_i + 1;
				$aa_usuarios[$li_i]=$row["codusu"];
			}
			$this->io_sql->free_result($rs_data);
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_usuariosgrupo
//---------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------	
	function  uf_sss_select_derecho_usuario($as_codemp,$as_codusu,$as_codsis,$as_nomven,$as_codintper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_derecho_usuario
		//         Access: public (sigesp_sss_p_derecho_usuario)
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_codusu    // codigo de usuario
		//      		   $as_codsis    // codigo de sistema
		//      		   $as_nomven    // nombre de la ventana (fisico)
		//      		   $as_codintper // codigo interno de permisos
		//    Description: Función que se encarga de verificar el derecho que tiene un usuario a una ventana del sistema
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
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
			$this->io_msg->message("CLASE->derecho_usuarios MÉTODO->uf_sss_select_derecho_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
//---------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------	
	function  uf_sss_insert_derecho_usuario($as_codemp,$as_codusu,$as_codsis,$as_nomven,$ai_visible,$ai_enabled,$ai_leer,
											$ai_incluir,$ai_cambiar,$ai_eliminar,$ai_imprimir,$ai_administrador,$ai_anular,
											$ai_ejecutar,$as_codintper,$aa_seguridad)
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
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$lb_existe=$this->uf_sss_select_derecho_usuario($as_codemp,$as_codusu,$as_codsis,$as_nomven,$as_codintper);
		if (!$lb_existe)
		{
			$ls_sql = "INSERT INTO sss_derechos_usuarios (codemp,codusu,codsis,nomven,visible,enabled,leer,incluir,cambiar,". 
					  "									  eliminar,imprimir,administrativo,anular,ejecutar,codintper) ".
					  "     VALUES('".$as_codemp."','".$as_codusu."','".$as_codsis."','".$as_nomven."',".$ai_visible.",".
					  " 	        ".$ai_enabled.",".$ai_leer.",".$ai_incluir.",".$ai_cambiar.",".$ai_eliminar.",".$ai_imprimir.",".
					  "             ".$ai_administrador.",".$ai_anular.",".$ai_ejecutar.",'".$as_codintper."')" ;
			$li_row=$this->io_sql->execute($ls_sql);
		}
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->derecho_usuarios MÉTODO->uf_sss_insert_derecho_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Otorgó permiso al usuario ".$as_codusu." en la pantalla ".$as_nomven." ".
							 "Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if ($lb_variable)
			{
				$lb_valido=true;
			}
		}
		return $lb_valido;
	}  // end  function  uf_sss_insert_derecho_usuario
//---------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------	
	function uf_sss_update_derecho_usuario($as_codemp,$as_codusu,$as_codsis,$as_nomven,$ai_visible,$ai_enabled,
										   $ai_leer,$ai_incluir,$ai_cambiar,$ai_eliminar,$ai_imprimir,$ai_administrador,
										   $ai_anular,$ai_ejecutar,$as_codintper,$aa_seguridad) 
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
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=false;
		 $ls_sql = "UPDATE sss_derechos_usuarios".
		 		   "   SET  visible='". $ai_visible ."', enabled='". $ai_enabled ."', leer='". $ai_leer ."',".
				   "        incluir='". $ai_incluir ."', cambiar='". $ai_cambiar ."', eliminar='". $ai_eliminar ."',".
				   "        imprimir='". $ai_imprimir ."', administrativo='". $ai_administrador ."', anular='". $ai_anular ."',".
				   "        ejecutar='". $ai_ejecutar ."' ".
				   " WHERE codemp='" .$as_codemp ."'".
				   "   AND codusu='" .$as_codusu ."'".
				   "   AND codsis='" .$as_codsis ."'".
				   "   AND nomven='" .$as_nomven ."'".
				   "   AND codintper='" .$as_codintper ."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if ($li_row===false)
		{
			$this->io_msg->message("CLASE->derecho_usuarios MÉTODO->uf_sss_update_derecho_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el permiso del usuario ".$as_codusu." en la pantalla ".$as_nomven." ".
							 "Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if ($lb_variable)
			{
				$lb_valido=true;
			}
		}
	  return $lb_valido;

	} // end  function  uf_sss_update_derecho_usuario
//---------------------------------------------------------------------------------------------------------------------------------	
	function uf_select_permisos_internos($as_codemp,$as_codsis,$as_codusu,&$aa_codintper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_permisos_internos
		//         Access: public (sigesp_c_permisos_globales)
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_codsis    // codigo del sistema
		//      		   $as_codusu    // codigo de usuario
		//      		   $aa_codintper // arreglo de los codigos internos de permisologia
		//    Description: Función que se encarga de verificar si el usuario tiene codigos internos de permisos para los sistemas 
		//				   sno y spg.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/10/2006									Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codintper FROM sss_permisos_internos".
				" WHERE codemp= '". $as_codemp ."'".
				"   AND codsis= '". $as_codsis ."'".
				"   AND codusu= '". $as_codusu ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->permisos_globales MÉTODO->uf_select_permisos_internos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			$li_totrow=$this->io_sql->num_rows($rs_data);
			if($li_totrow>=1)
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$li_pos=$li_pos+1;
					$aa_codintper[$li_pos]=$row["codintper"];   
				}
				if($li_pos>0)
				{
					$lb_valido=true;
				}
			}
			elseif($li_totrow==1)
			{
				$lb_valido=true;
			}
			
		}
		return $lb_valido;
	}  //  end  function uf_select_permisos_internos
//---------------------------------------------------------------------------------------------------------------------------------	
	function uf_select_permisos_internos_grupo($as_codemp,$as_codsis,$as_codusu,&$aa_codintper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_permisos_internos
		//         Access: public (sigesp_c_permisos_globales)
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_codsis    // codigo del sistema
		//      		   $as_codusu    // codigo de usuario
		//      		   $aa_codintper // arreglo de los codigos internos de permisologia
		//    Description: Función que se encarga de verificar si el usuario tiene codigos internos de permisos para los sistemas 
		//				   sno y spg.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/10/2006									Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codintper FROM sss_permisos_internos".
				" WHERE codemp= '". $as_codemp ."'".
				"   AND codsis= '". $as_codsis ."'".
				"   AND codusu= '". $as_codusu ."'";
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->permisos_globales MÉTODO->uf_select_permisos_internos_grupo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_existedefecto =$this->uf_select_permisos_internos_defecto($as_codemp,$as_codsis,$as_codusu);
			if ($lb_existedefecto)
			{
				$li_pos=0;
				$li_totrow=$this->io_sql->num_rows($rs_data);
				if($li_totrow>=1)
				{
					while($row=$this->io_sql->fetch_row($rs_data))
					{
						$li_pos=$li_pos+1;
						$aa_codintper[$li_pos]=$row["codintper"];   
					}
					if($li_pos>0)
					{
						$lb_valido=true;
					}
				}
				elseif($li_totrow==1)
				{
					$lb_valido=true;
				}
			}
		}
		return $lb_valido;
	}  //  end  function uf_select_permisos_internos
//---------------------------------------------------------------------------------------------------------------------------------	
	function uf_select_permisos_internos_defecto($as_codemp,$as_codsis,$as_codusu)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_permisos_internos
		//         Access: public (sigesp_c_permisos_globales)
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_codsis    // codigo del sistema
		//      		   $as_codusu    // codigo de usuario
		//      		   $aa_codintper // arreglo de los codigos internos de permisologia
		//    Description: Función que se encarga de verificar si el usuario tiene codigos internos de permisos para los sistemas 
		//				   sno y spg.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/10/2006									Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM sss_permisos_internos".
				" WHERE codemp= '". $as_codemp ."'".
				"   AND codsis= '". $as_codsis ."'".
				"   AND codusu= '". $as_codusu ."'".
				"   AND codintper= '---------------------------------'";
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->permisos_globales MÉTODO->uf_select_permisos_internos_defecto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_totrow=$this->io_sql->num_rows($rs_data);
			if($li_totrow==1)
			{
				$lb_valido=true;
			}
			elseif($li_totrow==0)
			{
				$lb_insertdefecto = $this->uf_insert_permisos_internos_defecto($as_codemp,$as_codsis,$as_codusu);
				if ($lb_insertdefecto)
				{
					$lb_valido=true;	
				}
			}
			
		}
		return $lb_valido;
	}  //  end  function uf_insert_permisos_internos_defecto
//---------------------------------------------------------------------------------------------------------------------------------	
	function uf_insert_permisos_internos_defecto($as_codemp,$as_codsis,$as_codusu)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_permisos_internos
		//         Access: public (sigesp_c_permisos_globales)
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_codsis    // codigo del sistema
		//      		   $as_codusu    // codigo de usuario
		//      		   $aa_codintper // arreglo de los codigos internos de permisologia
		//    Description: Función que se encarga de verificar si el usuario tiene codigos internos de permisos para los sistemas 
		//				   sno y spg.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/10/2006									Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="INSERT INTO sss_permisos_internos (codemp,codusu,codsis,codintper)".
				" VALUES ('".$as_codemp."','".$as_codusu."','".$as_codsis."','---------------------------------')";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->permisos_globales MÉTODO->uf_insert_permisos_internos_defecto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	}  //  end  function uf_insert_permisos_internos_defecto
//---------------------------------------------------------------------------------------------------------------------------------	
	function uf_select_permisos_grupales($as_codemp,$as_codsis,$as_nomgru,$aa_auxcodintper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_permisos_grupales
		//         Access: public (sigesp_c_permisos_globales)
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_codsis    // codigo del sistema
		//      		   $as_codusu    // codigo de usuario
		//      		   $aa_codintper // arreglo de los codigos internos de permisologia
		//    Description: Función que se encarga de verificar si el usuario tiene codigos internos de permisos para los sistemas 
		//				   sno y spg.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/10/2006									Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codintper FROM sss_permisos_internos_grupos".
				" WHERE codemp= '". $as_codemp ."'".
				"   AND codsis= '". $as_codsis ."'".
				"   AND nomgru= '". $as_nomgru ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->permisos_globales MÉTODO->uf_select_permisos_grupales ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			$li_totrow=$this->io_sql->num_rows($rs_data);
			if($li_totrow>=1)
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$li_pos=$li_pos+1;
					$aa_auxcodintper[$li_pos]=$row["codintper"];   
				}
				if($li_pos>0)
				{
					$lb_valido=true;
				}
			}
			elseif($li_totrow==1)
			{
				$lb_valido=true;
			}
		}
		return $lb_valido;
	}  //  end  function uf_select_permisos_internos
//---------------------------------------------------------------------------------------------------------------------------------	

}  // end  class sigesp_sss_c_derechogrupo
?>
