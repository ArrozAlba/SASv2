<?php 
class sigesp_sss_c_derecho_usuarios
{
	var $obj="";
	var $SQL;
	var $siginc;
	var $con;

	function sigesp_sss_c_derecho_usuarios()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->lds_cuentas=new class_datastore();
		$this->lds_detalle_cmp=new class_datastore();
		$this->lds_cmp_cierre=new class_datastore();
		//$this->int_fecha=new class_funciones();
		$io_msg=new class_mensajes();
		$this->seguridad= new sigesp_c_seguridad;
		$this->dat_emp=$_SESSION["la_empresa"];
		//require_once("../shared/class_folder/sigesp_include.php");
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->SQL=new class_sql($this->con);
	
	}


	function  uf_sss_select_derecho_usuario($as_empresa,$as_usuario,$as_sistema,$as_nombrefisico)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_sss_select_grupos
	//	Access:    public
	//	Arguments:
	//  as_empresa       // codigo de empresa
	//  as_usuario       // codigo de usuario
	//  as_sistema       // codigo de sistema
	//  as_nombrefisico  // nombre de de la pantalla
	//	Returns:		$lb_valido-----> true: encontrado false: no encontrado
	//	Description:  Esta funcion busca un derecho de usuario en la tabla de  sss_derechos_usuarios
	//              
	//////////////////////////////////////////////////////////////////////////////		
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_cadena="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
			
		
		$ls_sql = "SELECT * FROM sss_derechos_usuarios ".
					" WHERE codemp = '".$as_empresa."'".
				  	" AND codusu ='".$as_usuario."'".
				  	" AND codsis ='".$as_sistema."'".
				  	" AND nomven ='".$as_nombrefisico."'" ;
		
		$li_exec=$this->SQL->select($ls_sql);

		if($li_exec==false)
		{
			print $this->SQL->message;
			$lb_valido=false;
		}
		else
		{
			if($row=$this->SQL->fetch_row($li_exec))
			{
				$lb_valido=true;
				
			}
			else
			{
				$lb_valido=false;
			}
		}

		$this->SQL->free_result($li_exec);
		return $lb_valido;

	}

	function  uf_sss_insert_derecho_usuario($as_empresa,$as_usuario,$as_sistema,$as_pantalla,$ai_visible,$ai_habilitada,$ai_leer,
											$ai_incluir,$ai_modificar,$ai_eliminar,$ai_imprimir,$ai_administrador,$ai_anular,
											$ai_ejecutar,$aa_seguridad)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_sss_insert_derecho_usuario
	//	Access:    public
	//	Arguments:
	//  as_empresa     // codigo de empresa
	//  as_usuario     // codigo de usuario
	//  as_sistema     // codigo de sistema
	//  as_pantalla    // nombre de la pantalla
	//  ai_visible     // Indica si puede ver o no la pantalla
	//  ai_habilitada  // Indica si tiene permiso o no a la pantalla
	//  ai_leer        // Indica si tiene permiso o no de lectura
	//  ai_incluir     // Indica si tiene permiso o no de incluir
	//  ai_modificar   // Indica si tiene permiso o no demodificar
	//  ai_habilitada  // Indica si tiene permiso o no 
	//  ai_imprimir    // Indica si tiene permiso o no de imprimir
	//  ai_administrador // Indica si tiene permiso o no de administrador
	//  ai_anular     // Indica si tiene permiso o no de anular
	//  ai_ejecutar   // Indica si tiene permiso o no de ejecutar
	//  aa_seguridad  // arreglo de registro de seguridad
	//	Returns:		$lb_valido-----> true: operacion exitosa false: operacion no exitosa
	//	Description:  Esta funcion otorga permiso a un usuario en determinada pantalla 
	//              
	//////////////////////////////////////////////////////////////////////////////		
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_sql="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
			
		
		$ls_sql = "INSERT INTO sss_derechos_usuarios (codemp,codusu,codsis,nomven,visible,enabled,leer,incluir,cambiar,". 
					"eliminar,imprimir,administrativo,anular,ejecutar) ".
					" VALUES('".$as_empresa."','".$as_usuario."','".$as_sistema."','".$as_pantalla."',".$ai_visible.",".
					" ".$ai_habilitada.",".$ai_leer.",".$ai_incluir.",".$ai_modificar.",".$ai_eliminar.",".$ai_imprimir.",".
					" ".$ai_administrador.",".$ai_anular.",".$ai_ejecutar.")" ;
		
		$li_exec=$this->SQL->execute($ls_sql);
		if($li_exec==false)
		{
			print $this->SQL->message;
			$lb_valido=false;
			$this->SQL->rollback();		  

		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Otorgó permiso al usuario ".$as_usuario." en la pantalla ".$as_pantalla." ".
							 "Asociado a la empresa ".$as_empresa;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->SQL->commit();
		}
			
		return $lb_valido;

	}


	function uf_sss_update_derecho_usuario($as_empresa,$as_usuario,$as_sistema,$as_pantalla,$ai_visible,$ai_habilitada,
										   $ai_leer,$ai_incluir,$ai_modificar,$ai_eliminar,$ai_imprimir,$ai_administrador,
										   $ai_anular,$ai_ejecutar,$aa_seguridad) 
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_sss_update_derecho_usuario
	//	Access:    public
	//	Arguments:
	//  as_empresa     // codigo de empresa
	//  as_usuario     // codigo de usuario
	//  as_sistema     // codigo de sistema
	//  as_pantalla    // nombre de la pantalla
	//  ai_visible     // Indica si puede ver o no la pantalla
	//  ai_habilitada  // Indica si tiene permiso o no a la pantalla
	//  ai_leer        // Indica si tiene permiso o no de lectura
	//  ai_incluir     // Indica si tiene permiso o no de incluir
	//  ai_modificar   // Indica si tiene permiso o no demodificar
	//  ai_habilitada  // Indica si tiene permiso o no 
	//  ai_imprimir    // Indica si tiene permiso o no de imprimir
	//  ai_administrador // Indica si tiene permiso o no de administrador
	//  ai_anular     // Indica si tiene permiso o no de anular
	//  ai_ejecutar   // Indica si tiene permiso o no de ejecutar
	//  aa_seguridad  // arreglo de registro de seguridad
	//	Returns:		$lb_valido-----> true: operacion exitosa false: operacion no exitosa
	//	Description:  Esta funcion modifica un  permiso de un usuario en determinada pantalla 
	//              
	//////////////////////////////////////////////////////////////////////////////		
	 $lb_valido=true;
	 $ls_cadena="";
	 $li_exce=-1;
	
	 $ls_sql = "UPDATE sss_derechos_usuarios SET  visible='". $ai_visible ."', enabled='". $ai_habilitada ."', leer='". $ai_leer ."',".
	 			" incluir='". $ai_incluir ."', cambiar='". $ai_modificar ."', eliminar='". $ai_eliminar ."', imprimir='". $ai_imprimir ."',".
				" administrativo='". $ai_administrador ."', anular='". $ai_anular ."', ejecutar='". $ai_ejecutar ."' ".
	 			" WHERE codemp='" .$as_empresa ."'".
				" AND codusu='" .$as_usuario ."'".
				" AND codsis='" .$as_sistema ."'".
				" AND nomven='" .$as_pantalla ."'";
	 
        $this->SQL->begin_transaction();
		$li_exce = $this->SQL->execute($ls_sql);
		//print($ls_sql);	
		//print($this->SQL->message);	
		if ($li_exce===false)
		{
			print $this->SQL->message;
			$lb_valido=false;
			$this->SQL->rollback();		  
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el permiso del usuario ".$as_usuario." en la pantalla ".$as_pantalla." ".
							 "Asociado a la empresa ".$as_empresa;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->SQL->commit();
		}
	  return $lb_valido;

	}

	function  uf_sss_insert_ventana($as_sistema, $as_ventana, $as_titulo, $as_descripcion )
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_sss_insert_derecho_usuario
	//	Access:    public
	//	Arguments:
	//  as_sistema     // codigo de sistema
	//  as_ventana    // nombre de la ventana
	//  as_titulo     // titulo de la ventana
	//  as_descripcion  // descripcion de ventana
	//  aa_seguridad  // arreglo de registro de seguridad
	//	Returns:		$lb_valido-----> true: operacion exitosa false: operacion no exitosa
	//	Description:  Esta funcion otorga permiso a un usuario en determinada pantalla 
	//              
	//////////////////////////////////////////////////////////////////////////////		
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_sql="";
		$li_exec=-1;
		$this->is_msg_error = "";
		
		
		$ls_sql = "INSERT INTO sss_sistemas_ventanas (codsis, nomven, titven, desven) ".
					" VALUES('".$as_sistema."','".$as_ventana."','".$as_titulo."','".$as_descripcion."')" ;
		
			
			$li_exec=$this->SQL->execute($ls_sql);
			
		if($li_exec==false)
		{
			print $this->SQL->message;
			$lb_valido=false;

		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;

	}

	function  uf_sss_select_ventana($as_sistema,$as_ventana)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_sss_select_ventana
	//	Access:    public
	//	Arguments:
	//  as_sistema  // codigo de sistema
	//  as_ventana  // nombre de de la pantalla
	//	Returns:		$lb_valido-----> true: encontrado false: no encontrado
	//	Description:  Esta funcion busca una ventana de sistema en la tabla de  sss_sistemas_ventanas
	//              
	//////////////////////////////////////////////////////////////////////////////		
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_cadena="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
			
		
		$ls_sql = "SELECT * FROM sss_sistemas_ventanas".
					" WHERE codsis = '".$as_sistema."'".
					" AND nomven ='".$as_ventana."'" ;
		
			
		$li_exec=$this->SQL->select($ls_sql);
		if($row=$this->SQL->fetch_row($li_exec))
		{
			$lb_existe=true;
			$this->SQL->free_result($li_exec);
		}
		else
		{
			$lb_existe=false;
			print $this->SQL->message;
		}
								
		return $lb_existe;

	}

}
?>
