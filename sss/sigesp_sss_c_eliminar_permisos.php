<?php

class sigesp_sss_c_eliminar_permisos
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sss_c_eliminar_permisos()
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
		$ls_sql="SELECT codusu FROM sss_usuarios".
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
		$ls_sql="SELECT codusu FROM sss_derechos_usuarios".
				" WHERE codemp= '". $as_codemp ."'".
				" AND codusu= '". $as_codusu ."'".
				" AND codsis= '". $as_codsis ."'".
				" AND nomven= '". $as_nomven ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$io_msg->message("CLASE->eliminarpermisos MÉTODO->uf_select_derechos_usuarios ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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


	function uf_sss_delete_usuario_permisos($as_codemp,$as_codusu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_delete_usuario_permisos
		//         Access: public  
		//      Argumento: $as_codemp     // codigo de empresa
		//      		   $as_codusu     // codigo de usuario
		//      		   $aa_seguridad  // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: funcion que elimina los permisos internos de nomina y presupuesto
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2006									Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= " DELETE FROM sss_permisos_internos".
				 " WHERE codemp= '".$as_codemp. "'".
				 "   AND codusu= '".$as_codusu."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->eliminarpermisos MÉTODO->uf_sss_delete_usuario_permisos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion="Eliminó el perfil de seguridad al usuario ".$as_codusu.
							 " Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function uf_sss_delete_usuario_nomina

	function  uf_sss_delete_permisos($as_codemp,$as_codusu)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_delete_permisos
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//                 $as_codusu    // codigo de usuario
		//                 $as_codnom    // codigo de nomina
		//                 $aa_seguridad //arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Función que elimina los perfiles de  permisos de un usuario 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2006								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="UPDATE sss_derechos_usuarios".
				"   SET enabled=0, leer=0, incluir=0, cambiar=0, eliminar=0, imprimir=0, anular=0, ejecutar=0".
			    " WHERE codemp='" .$as_codemp ."'".
			    "   AND codusu='" .$as_codusu ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->eliminarpermisos MÉTODO->uf_sss_delete_permisos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			print ($this->io_sql->message);
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function  uf_sss_delete_permisos

} 
?>
