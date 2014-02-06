<?php 
class sigesp_sss_c_usuarios
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sss_c_usuarios()
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

	function  uf_sss_select_usuarios($as_codemp,$as_codusu)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_grupos
		//         Access: public (sigesp_sss_d_usuarios)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codusu    // codigo de usuario
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de verificar la existencia de un usuario en la tabla sss_usuarios
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM sss_usuarios  ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND codusu='".$as_codusu."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuarios MÉTODO->uf_sss_select_usuarios ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  // end  function  uf_sss_select_usuarios

	function  uf_sss_insert_usuario($ad_ultingusu,$as_codemp,$as_codusu,$as_nomusu,$as_apeusu,$as_cedusu,
									$as_pwdusu,$as_telusu,$as_nota,$as_fotousu,$aa_seguridad,$as_estusu,$as_coduniadm )
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_insert_usuario
		//         Access: public (sigesp_sss_d_usuarios)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codusu    // codigo de usuario
		//  			   $as_nomusu    // nombre de usuario
		//  			   $as_apeusu    // apellido de usuario
		//  			   $as_cedusu    // cedula de usuario
		//  			   $as_pwdusu    // password encriptado de usuario
		//  			   $as_telusu    // telefono de usuario
		//  			   $as_nota      // observaciones de usuario
		//  			   $as_fotousu   // foto de usuario
		//  			   $ad_ultingusu // fecha de ultimo ingreso del usuario
		//  			   $aa_seguridad // arreglo de registro de seguridad
		//				   $as_estusu	 // Estatus del usuario en el sistema
		//				   $as_coduniadm // Ubicacion administrativa del usuario	
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de insertar un usuario en la tabla sss_usuarios
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/06/2010 Por: Ing. Nelson Barraez	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "INSERT INTO sss_usuarios (codemp, codusu, cedusu, nomusu, apeusu, pwdusu, telusu, nota, ultingusu, fotousu,estusu,coduniadm ) ".
				  " VALUES('".$as_codemp."','".$as_codusu."','".$as_cedusu."','".$as_nomusu."','".$as_apeusu."','".$as_pwdusu."',".
				  "        '".$as_telusu."','".$as_nota."','".$ad_ultingusu."','".$as_fotousu."',".$as_estusu.",'".$as_coduniadm."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->usuarios MÉTODO->uf_sss_insert_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Usuario ".$as_codusu." Asociado a la empresa ".$as_codemp;
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
	}  // end  function  uf_sss_insert_usuario
	
	function uf_sss_delete_usuario($as_codemp,$as_codusu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_delete_usuario
		//         Access: public (sigesp_sss_d_usuarios)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codusu    // codigo de usuario
		//  			   $aa_seguridad // codigo de usuario
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de eliminar un usuario en la tabla sss_usuarios verificando su integridad referencial
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$lb_existe=$this->uf_sss_select_eventos($as_codemp,$as_codusu);
		if($lb_existe)
		{
			$this->io_msg->message("El usuario tiene registros de eventos asociados");
			$lb_valido=false;
		}
		else
		{
			$lb_existe=$this->uf_sss_select_permisos($as_codemp,$as_codusu);
			if($lb_existe)
			{
				$this->io_msg->message("El usuario tiene registros de permisos");
				$lb_valido=false;
			}
		}
		if(!$lb_existe)
		{
			$ls_sql = " DELETE FROM sss_usuarios".
					  " WHERE codemp= '".$as_codemp. "'".
					  " AND codusu= '".$as_codusu."'"; 
			$this->io_sql->begin_transaction();	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->usuarios MÉTODO->uf_sss_delete_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Usuario ".$as_codusu." Asociado a la empresa ".$as_codemp;
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
		}
		return $lb_valido;
	} // end  function uf_sss_delete_usuario
	
	function uf_sss_update_usuario($as_codemp,$as_codusu,$as_cedusu,$as_nomusu,$as_apeusu,$as_telusu,$as_nota,$as_nomarch,$aa_seguridad,$as_estusu,$as_coduniadm) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_update_usuario
		//         Access: public (sigesp_sss_d_usuarios)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codusu    // codigo de usuario
		//  			   $as_nomusu    // nombre de usuario
		//  			   $as_apeusu    // apellido de usuario
		//  			   $as_cedusu    // cedula de usuario
		//  			   $as_telusu    // telefono de usuario
		//  			   $as_nota      // observaciones de usuario
		//  			   $as_nomarch   // nombre del archivo de la foto
		//  			   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de modificar un usuario en la tabla sss_usuarios
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/06/2010 Por: Ing. Nelson Barraez	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=false;
		 $ls_sqlfoto="";
		 if($as_nomarch!="")
		 {
		 	$ls_sqlfoto=", fotousu='". $as_nomarch ."'";
		 }
		 $ls_sql = "UPDATE sss_usuarios SET  cedusu='". $as_cedusu ."',nomusu='". $as_nomusu ."',apeusu='". $as_apeusu ."',".
				   "telusu='". $as_telusu ."', nota='". $as_nota ."'". $ls_sqlfoto .",estusu=".$as_estusu.",coduniadm='".$as_coduniadm."' ".
				   " WHERE codemp='" .$as_codemp ."'".
				   " AND codusu='" .$as_codusu ."'";
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->usuarios MÉTODO->uf_sss_update_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Usuario ".$as_codusu." Asociado a la empresa ".$as_codemp;
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
	}  // end  function uf_sss_update_usuario
	
	function  uf_sss_select_eventos($as_codemp,$as_codusu)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_eventos
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codusu    // codigo de usuario
		//	      Returns: Retorna un Booleano
		//    Description: Función que verifica si un usuario tiene registrado algun evento en la tabla sss_registro_eventos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM sss_registro_eventos  ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND codusu='".$as_codusu."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuarios MÉTODO->uf_sss_select_eventos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  // end  function  uf_sss_select_eventos

	function  uf_sss_select_permisos($as_codemp,$as_codusu)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_permisos
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codusu    // codigo de usuario
		//	      Returns: Retorna un Booleano
		//    Description: Función que verifica si un usuario tiene registrado algun permiso en la tabla sss_derechos_usuarios
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM sss_derechos_usuarios  ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND codusu='".$as_codusu."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuarios MÉTODO->uf_sss_select_permisos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  // end   function  uf_sss_select_permisos
	
}//end  class sigesp_sss_c_usuarios

?>
