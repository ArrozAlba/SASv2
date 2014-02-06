<?php

class sigesp_sss_c_permisos_administrador
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sss_c_permisos_administrador()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_msg=new class_mensajes();
		$this->dat_emp=$_SESSION["la_empresa"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->io_funcion = new class_funciones();

	}
	
	function uf_llenar_combo_usuarios(&$aa_usuarios)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_llenar_combo_usuarios
		//         Access: public (sigesp_c_permisos_globales)
		//      Argumento: $aa_usuarios // arreglo de valores que puede tomar el combo.
		//    Description: Función que se encarga de llenar el arreglo del combo de Usuarios
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 22/02/2006									Fecha Última Modificación : 22/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM sss_usuarios".
				" ORDER BY codusu ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		$li_pos=0;
		if($rs_data===false)
		{
			$io_msg->message("CLASE->permisos_globales MÉTODO->uf_llenar_combo_usuarios ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 22/02/2006									Fecha Última Modificación : 22/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		print "<select name='cmbusuarios' id='cmbusuarios' style='width:200px'>";
		print "<option value= --- selected>--Seleccione Uno-- </option>";
		$li_total=count($aa_usuarios["codusu"]);
		for($i=1; $i <= $li_total ; $i++)
		{			
			print "<option value='".$aa_usuarios["codusu"][$i]."'>".$aa_usuarios["codusu"][$i]."</option>";
		}
		print"</select>";
	}  //  end  function uf_pintar_combo_usuarios

	function uf_select_sistemasventanas(&$aa_ventanas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_sistemasventanas
		//         Access: public  
		//      Argumento: $as_codsis   // codigo del sistema
		//      		   $aa_ventanas // arreglo de los nombre de las ventanas por el sistema seleccionado
		//    Description: Función que se encarga de verificar si las paginas de un sistema estan en la tabla de sss_sistemas_ventanas
		//				   y los guarda en un arreglo.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 17/10/2006									Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codsis,nomven FROM sss_sistemas_ventanas";
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$io_msg->message("CLASE->permisos_administrador MÉTODO->uf_select_sistemasventanas ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_pos=$li_pos+1;
				$aa_ventanas["codsis"][$li_pos]=$row["codsis"];   
				$aa_ventanas["nomven"][$li_pos]=$row["nomven"]; 
			}
			if($li_pos!=0)
			{
				$lb_valido=true;
			}
		}
		return $lb_valido;
	}  //  end  function uf_select_sistemasventanas

	function uf_select_derechos_usuarios($as_codemp,$as_codusu,$as_codsis,$as_nomven)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_derechos_usuarios
		//         Access: public (sigesp_c_permisos_globales)
		//      Argumento: $as_codemp // codigo de empresa 
		//      		   $as_codusu // codigo de usuario
		//      		   $as_codsis // codigo del sistema
		//      		   $as_nomven // nombre de la ventana
		//    Description: Función que se encarga de verificar si las paginas de un sistema estan en la tabla de sss_derechos_usuarios
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 22/02/2006									Fecha Última Modificación : 22/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM sss_derechos_usuarios".
				" WHERE codemp= '". $as_codemp ."'".
				" AND codusu= '". $as_codusu ."'".
				" AND codsis= '". $as_codsis ."'".
				" AND nomven= '". $as_nomven ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$io_msg->message("CLASE->permisos_globales MÉTODO->uf_select_derechos_usuarios ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  // end  function uf_select_derechos_usuarios

	function  uf_sss_insert_derecho_usuario($as_codemp,$as_codusu,$as_codsis,$as_nomven,$ai_visible,$ai_enabled,$ai_leer,
											$ai_incluir,$ai_cambiar,$ai_eliminar,$ai_imprimir,$ai_administrador,$ai_anular,
											$ai_ejecutar,$aa_seguridad)
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
		//      		   $aa_seguridad     // arreglo de registro de seguridad
		//    Description: Función que se encarga de otorgar permisos a un usuario en determinada  pantalla
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO sss_derechos_usuarios (codemp,codusu,codsis,nomven,visible,enabled,leer,incluir,cambiar,". 
					"eliminar,imprimir,administrativo,anular,ejecutar) ".
					" VALUES('".$as_codemp."','".$as_codusu."','".$as_codsis."','".$as_nomven."',".$ai_visible.",".
					" ".$ai_enabled.",".$ai_leer.",".$ai_incluir.",".$ai_cambiar.",".$ai_eliminar.",".$ai_imprimir.",".
					" ".$ai_administrador.",".$ai_anular.",".$ai_ejecutar.")" ;
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
										   $ai_anular,$ai_ejecutar,$aa_seguridad) 
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
		//      		   $aa_seguridad     // arreglo de registro de seguridad
		//    Description: Función que se encarga de modificar permisos a un usuario en determinada  pantalla
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005									Fecha Última Modificación : 01/11/2005	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=false;
		 $ls_sql = "UPDATE sss_derechos_usuarios SET  visible='". $ai_visible ."', enabled='". $ai_enabled ."', leer='". $ai_leer ."',".
					" incluir='". $ai_incluir ."', cambiar='". $ai_cambiar ."', eliminar='". $ai_eliminar ."', imprimir='". $ai_imprimir ."',".
					" administrativo='". $ai_administrador ."', anular='". $ai_anular ."', ejecutar='". $ai_ejecutar ."' ".
					" WHERE codemp='" .$as_codemp ."'".
					" AND codusu='" .$as_codusu ."'".
					" AND codsis='" .$as_codsis ."'".
					" AND nomven='" .$as_nomven ."'";
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

	}
} 
?>
