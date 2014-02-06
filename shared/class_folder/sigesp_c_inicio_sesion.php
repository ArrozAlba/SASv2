<?php
require_once("class_sql.php");
require_once("class_datastore.php");
require_once("class_funciones.php");
require_once("sigesp_include.php");
require_once("class_mensajes.php");

class sigesp_c_inicio_sesion
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_c_inicio_sesion()
	{
		$this->io_msg=new class_mensajes();
		$this->dat_emp=$_SESSION["la_empresa"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->io_funcion = new class_funciones();
	}

	function  uf_sss_select_login($as_login,$as_password)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_login
		//         Access: public (sigesp_inicio_sesion)
		//      Argumento: $as_login    //login de usuario
		//                 $as_password //password encriptado de usuario
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica que el login y el password de un usuario sean correctos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005 								Fecha Última Modificación: 07/03/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sss_usuarios  ".
					" WHERE codusu='".$as_login."'".
					" AND pwdusu='".$as_password."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->inicio_sesion MÉTODO->uf_sss_select_login ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ls_estusu=$row["estusu"];
				if($ls_estusu=='t')
				{				
					$_SESSION["la_cedusu"]=$row['cedusu'];
					$_SESSION["la_nomusu"]=$row['nomusu'];
					$_SESSION["la_apeusu"]=$row['apeusu'];
					$_SESSION["la_codusu"]=$row['codusu'];
					$_SESSION["la_pasusu"]=$row['pwdusu'];
				}
				else
				{	$lb_valido=false;	}
			}
			else
			{	$lb_valido=false;	}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;

	} //end function  uf_sss_select_login

	function uf_sss_update_acceso($as_login,$as_fecha) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_update_acceso
		//         Access: public (sigesp_inicio_sesion)
		//      Argumento: $as_login    //login de usuario
		//                 $as_password //password encriptado de usuario
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el registro del ultimo acceso del usuario en el sistema en la tabla sss_usuarios
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005 								Fecha Última Modificación: 07/03/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE sss_usuarios SET ultingusu='". $as_fecha ."'".
					" WHERE codusu='" .$as_login ."' ";
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->inicio_sesion MÉTODO->uf_sss_update_acceso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} //end  function uf_sss_update_acceso

	function  uf_sss_select_usuario()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_login
		//         Access: public (sigesp_inicio_sesion)
		//      Argumento: $as_login    //login de usuario
		//                 $as_password //password encriptado de usuario
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existen usuarios registrados en el sistema
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005 								Fecha Última Modificación: 07/03/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sss_usuarios  ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->inicio_sesion MÉTODO->uf_sss_select_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;

	} //end function  uf_sss_select_usuario

} //end class sigesp_c_inicio_sesion
?>