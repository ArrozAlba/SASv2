<?php 
class sigesp_sss_c_derechousuarios
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sss_c_derechousuarios()
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


	function  uf_sss_select_sistema($as_codemp,$as_codsis)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_sistema
		//         Access: public (sigesp_sss_p_derecho_usuario)
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
			$this->io_msg->message("CLASE->derecho_usuarios MÉTODO->uf_sss_select_sistema ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO sss_derechos_usuarios (codemp,codusu,codsis,nomven,visible,enabled,leer,incluir,cambiar,". 
				  "									  eliminar,imprimir,administrativo,anular,ejecutar,codintper) ".
				  "     VALUES('".$as_codemp."','".$as_codusu."','".$as_codsis."','".$as_nomven."',".$ai_visible.",".
				  " 	        ".$ai_enabled.",".$ai_leer.",".$ai_incluir.",".$ai_cambiar.",".$ai_eliminar.",".$ai_imprimir.",".
				  "             ".$ai_administrador.",".$ai_anular.",".$ai_ejecutar.",'".$as_codintper."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->derecho_usuarios MÉTODO->uf_sss_insert_derecho_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();		  
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
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
			}	
		}
		return $lb_valido;
	}  // end  function  uf_sss_insert_derecho_usuario

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
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if ($li_row===false)
		{
			$this->io_msg->message("CLASE->derecho_usuarios MÉTODO->uf_sss_update_derecho_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();		  
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
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
			}	
		}
	  return $lb_valido;

	} // end  function  uf_sss_update_derecho_usuario

	function  uf_sss_insert_ventana($as_codsis, $as_nomven, $as_titven, $as_desven)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_insert_ventana
		//         Access: public (sigesp_sss_p_derecho_usuario)
		//      Argumento: $as_codsis    // codigo de sistema
		//      		   $as_nomven    // nombre de la ventana (fisico)
		//      		   $as_titven    // titulo de la ventana
		//      		   $as_desven    // descripcion de ventana
		//    Description: Función que se encarga de incluir una ventana nueva en la tabla sss_sistema_ventanas
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "INSERT INTO sss_sistemas_ventanas (codsis, nomven, titven, desven) ".
				 "VALUES('".$as_codsis."','".$as_nomven."','".$as_titven."','".$as_desven."')" ;
        $this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row==false)
		{
			$this->io_msg->message("CLASE->derecho_usuarios MÉTODO->uf_sss_insert_ventana ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();		  
		}
		else
		{
			$this->io_sql->commit();
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function  uf_sss_insert_ventana

	function  uf_sss_select_ventana($as_codsis,$as_nomven)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_ventana
		//         Access: public (sigesp_sss_p_derecho_usuario)
		//      Argumento: $as_codsis    // codigo de sistema
		//      		   $as_nomven    // nombre de la ventana (fisico)
		//    Description: Función que se encarga de verificar si existe  una ventana nueva en la tabla sss_sistema_ventanas
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "SELECT * FROM sss_sistemas_ventanas".
				 " WHERE codsis = '".$as_codsis."'".
				 "   AND nomven ='".$as_nomven."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->derecho_usuarios MÉTODO->uf_sss_select_ventana ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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

	function  uf_sss_load_usuario($as_codusu,&$as_nomusu,&$as_apeusu)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_usuario
		//         Access: public (sigesp_sss_p_derecho_usuario)
		//      Argumento: $as_codusu    // codigo de usuario
		//      		   $as_nomusu    // nombre usuario
		//      		   $as_apeusu    // apellido de usuario
		//    Description: Función que se encarga de obtener los datos (nombre y apellido) de un usuario por su login
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "SELECT * FROM sss_usuarios".
				 " WHERE codusu = '".$as_codusu."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->derecho_usuarios MÉTODO->uf_sss_select_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_nomusu=$row["nomusu"];
				$as_apeusu=$row["apeusu"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // end function  uf_sss_load_usuario

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
			$this->io_msg->message("CLASE->derecho_usuarios MÉTODO->uf_sss_load_sistema ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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

	function  uf_sss_load_permisos($as_codemp,$as_codusu,$as_codsis,$as_nomven,&$ai_enabled,&$ai_leer,&$ai_incluir,&$ai_cambiar,
								   &$ai_eliminar,&$ai_imprimir,&$ai_anular,&$ai_ejecutar,&$ai_administrador)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_permisos
		//         Access: public (sigesp_sss_p_derecho_usuario)
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_codusu    // codigo de usuario
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
		//    Description: Función que se encarga de obtener los datos (permiso) de un usuario en una pantalla
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 19/03/2007	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM sss_derechos_usuarios".
		 		" WHERE codemp ='".$as_codemp."'".
				"   AND codusu ='".$as_codusu."'".
				"   AND codsis ='".$as_codsis."'".
				"   AND nomven ='".$as_nomven."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->derecho_usuarios MÉTODO->uf_sss_load_permisos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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

	function uf_select_permisos_internos($as_codemp,$as_codsis,$as_codusu,&$aa_codintper,$as_usugrup)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_permisos_internos
		//         Access: public (sigesp_c_permisos_globales)
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_codsis    // codigo del sistema
		//      		   $as_codusu    // codigo de usuario
		//      		   $aa_codintper // arreglo de los codigos internos de permisologia
		//				   $as_usugrup   // Variable que indica si es un usuario o grupo 	
		//    Description: Función que se encarga de verificar si el usuario tiene codigos internos de permisos para los sistemas 
		//				   sno y spg.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/10/2006									Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		if($as_usugrup=='U')
		{
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
		}
		else
		{
			$ls_sql="SELECT codintper FROM sss_permisos_internos_grupo".
					" WHERE codemp= '". $as_codemp ."'".
					"   AND codgru= '". $as_codusu ."'";
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
		}
		return $lb_valido;
	}  //  end  function uf_select_permisos_internos
	
	function  uf_sss_insert_permisos_internos($as_codemp,$as_codusu,$as_codsis,$as_codintper,$aa_seguridad,$as_usugrup)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_insert_permisos_internos
		//         Access: public (sigesp_sss_p_derecho_usuario)
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_codusu    // codigo de usuario
		//      		   $as_codsis    // codigo de sistema
		//				   $as_codintper // codigo interno de permisos
		//      		   $aa_seguridad // arreglo de registro de seguridad
		//				   $as_usugrup   // Indica si es usuario o grupo
		//    Description: Función que se encarga de insertar por defecto los datos en la tabla sss_permisos_internos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_auxcodintper=$as_codintper;
		$lb_existe=$this->uf_select_permisos_internos($as_codemp,$as_codsis,$as_codusu,$ls_auxcodintper,$as_usugrup);
		if(!$lb_existe)
		{
			$this->io_sql->begin_transaction();
			$ls_sql = " INSERT INTO sss_permisos_internos (codemp,codusu,codsis,codintper) ".
				      " VALUES('".$as_codemp."','".$as_codusu."','".$as_codsis."','".$as_codintper."')" ;
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->derecho_usuarios MÉTODO->uf_sss_insert_permisos_internos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();		  
			}
			else
			{
				$lb_valido=true;
				$this->io_sql->commit();
			}
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function  uf_sss_insert_permisos_internos

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	  										AGREGADO PARA EL MANEJO DE PERMISOS POR GRUPOS														    //	
//											Desarrollado por Ing. Nelson Barraez															        //
//											Fecha de Creación : 01/06/2010			Ultima Modificación:	01/06/2010							    //
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	function  uf_sss_load_grupo($as_codgru,&$as_nomgru,&$as_apegru)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_grupo
		//         Access: public (sigesp_sss_p_derecho_usuario)
		//      Argumento: $as_codgru    // codigo de grupo
		//      		   $as_nomusu    // nombre usuario
		//      		   $as_apeusu    // apellido de usuario
		//    Description: Función que se encarga de obtener los datos (nombre y apellido) de un grupo
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 27/05/2010									Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "SELECT * FROM sss_grupos".
				 " WHERE codgru = '".$as_codgru."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->derecho_usuarios MÉTODO->uf_sss_load_grupo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_nomgru=$row["nomgru"];
				$as_apegru="";
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // end function  uf_sss_load_grupo
	
	
	function  uf_sss_load_permisos_grupo($as_codemp,$as_codgru,$as_codsis,$as_nomven,&$ai_enabled,&$ai_leer,&$ai_incluir,&$ai_cambiar,
								   &$ai_eliminar,&$ai_imprimir,&$ai_anular,&$ai_ejecutar,&$ai_administrador)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_permisos
		//         Access: public (sigesp_sss_p_derecho_usuario)
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_codgru    // codigo de grupo
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
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 01/06/2010									Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM sss_derechos_grupos".
		 		" WHERE codemp ='".$as_codemp."'".
				"   AND codgru ='".$as_codgru."'".
				"   AND codsis ='".$as_codsis."'".
				"   AND nomven ='".$as_nomven."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->derecho_usuarios MÉTODO->uf_sss_load_permisos_grupo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // end function  uf_sss_load_permisos_grupo
	
	
	function  uf_sss_select_derecho_grupo($as_codemp,$as_codgru,$as_codsis,$as_nomven)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_derecho_grupo
		//         Access: public (sigesp_sss_p_derecho_usuario)
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_codgru    // codigo de grupo
		//      		   $as_codsis    // codigo de sistema
		//      		   $as_nomven    // nombre de la ventana (fisico)
		//    Description: Función que se encarga de verificar el derecho que tiene un grupo a una ventana del sistema
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 01/06/2010									Fecha Última Modificación : 	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "SELECT * FROM sss_derechos_grupos ".
				 " WHERE codemp = '".$as_codemp."'".
				 "   AND codgru ='".$as_codgru."'".
				 "   AND codsis ='".$as_codsis."'".
				 "   AND nomven ='".$as_nomven."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->derecho_usuarios MÉTODO->uf_sss_select_derecho_grupo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  // end  function  uf_sss_select_derecho_grupo

	function  uf_sss_insert_derecho_grupo($as_codemp,$as_codgru,$as_codsis,$as_nomven,$ai_visible,$ai_enabled,$ai_leer,
											$ai_incluir,$ai_cambiar,$ai_eliminar,$ai_imprimir,$ai_administrador,$ai_anular,
											$ai_ejecutar,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_insert_derecho_usuario
		//         Access: public (sigesp_sss_p_derecho_usuario)
		//      Argumento: $as_codemp        // codigo de empresa
		//      		   $as_codgru        // codigo de grupo
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
		//    Description: Función que se encarga de otorgar permisos a un grupo en determinada  pantalla
		//	   Creado Por: Ing. NElson Barraez
		// Fecha Creación: 01/06/2010									Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO sss_derechos_grupos (codemp,codgru,codsis,nomven,visible,enabled,leer,incluir,cambiar,". 
				  "									  eliminar,imprimir,administrativo,anular,ejecutar) ".
				  "     VALUES('".$as_codemp."','".$as_codgru."','".$as_codsis."','".$as_nomven."',".$ai_visible.",".
				  " 	        ".$ai_enabled.",".$ai_leer.",".$ai_incluir.",".$ai_cambiar.",".$ai_eliminar.",".$ai_imprimir.",".
				  "             ".$ai_administrador.",".$ai_anular.",".$ai_ejecutar.")" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->derecho_usuarios MÉTODO->uf_sss_insert_derecho_grupo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();		  
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Otorgó permiso al grupo  ".$as_codgru." en la pantalla ".$as_nomven." ".
							 "Asociado a la empresa ".$as_codemp;
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
	}  // end  function  uf_sss_insert_derecho_grupo

	function uf_sss_update_derecho_grupo($as_codemp,$as_codgru,$as_codsis,$as_nomven,$ai_visible,$ai_enabled,
										   $ai_leer,$ai_incluir,$ai_cambiar,$ai_eliminar,$ai_imprimir,$ai_administrador,
										   $ai_anular,$ai_ejecutar,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_update_derecho_grupo
		//         Access: public (sigesp_sss_p_derecho_usuario)
		//      Argumento: $as_codemp        // codigo de empresa
		//      		   $as_codgru        // codigo de grupo
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
		//      		   $aa_seguridad     // arreglo de registro de seguridad
		//    Description: Función que se encarga de modificar permisos a un grupo en determinada  pantalla
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 01/06/2010									Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=false;
		 $ls_sql = "UPDATE sss_derechos_grupos".
		 		   "   SET  visible='". $ai_visible ."', enabled='". $ai_enabled ."', leer='". $ai_leer ."',".
				   "        incluir='". $ai_incluir ."', cambiar='". $ai_cambiar ."', eliminar='". $ai_eliminar ."',".
				   "        imprimir='". $ai_imprimir ."', administrativo='". $ai_administrador ."', anular='". $ai_anular ."',".
				   "        ejecutar='". $ai_ejecutar ."' ".
				   " WHERE codemp='" .$as_codemp ."'".
				   "   AND codgru='" .$as_codgru ."'".
				   "   AND codsis='" .$as_codsis ."'".
				   "   AND nomven='" .$as_nomven ."'";
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if ($li_row===false)
		{
			$this->io_msg->message("CLASE->derecho_usuarios MÉTODO->uf_sss_update_derecho_grupo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();		  
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el permiso del grupo ".$as_codgru." en la pantalla ".$as_nomven." ".
							 "Asociado a la empresa ".$as_codemp;
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

	} // end  function  uf_sss_update_derecho_grupo
	
	
	
}  // end  class sigesp_sss_c_derecho_usuarios
?>
