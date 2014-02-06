<?php 
class sigesp_c_actualizarventana
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_c_actualizarventana()
	{
		require_once("class_folder/class_sql.php");
		require_once("class_folder/class_datastore.php");
		require_once("sigesp_include.php");
		require_once("class_folder/class_mensajes.php");
		
		$this->lds_cuentas=new class_datastore();
		$this->lds_detalle_cmp=new class_datastore();
		$this->lds_cmp_cierre=new class_datastore();
		//$this->int_fecha=new class_funciones();
		$io_msg=new class_mensajes();
		$this->dat_emp=$_SESSION["la_empresa"];
		require_once("sigesp_include.php");
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
	
	}
	function  uf_sss_insert_ventana($as_sistema, $as_ventana, $as_titulo, $as_descripcion )
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_saf_insert_movimientos
	//	Access:    public
	//	Arguments:
	//  as_sistema     // codigo de sistema
	//  as_ventana     // codigo de ventana
	//  as_titulo      // titulo de la ventana
	//  as_descripcion // descripcion de la ventana
	//	Returns:		$lb_valido-----> true: operacion exitosa false: operacion no exitosa
	//	Description:  Esta funcion inserta una ventana en la tabla de  sss_sistemas_ventanas
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
		
			
			$li_exec=$this->io_sql->execute($ls_sql);
			
		if($li_exec==false)
		{
			print $this->io_sql->message;
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
	//  as_sistema    // codigo de sistema
	//  as_ventana    // codigo de ventana
	//	Returns:		$lb_valido-----> true: encontrado false: no encontrado
	//	Description:  Esta funcion busca una ventana en la tabla de  sss_sistemas_ventanas
	//              
	//////////////////////////////////////////////////////////////////////////////		
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sss_sistemas_ventanas".
		 		  " WHERE codsis = '".$as_sistema."' AND nomven ='".$as_ventana."'" ;
		$li_exec=$this->io_sql->select($ls_sql);
		if($row=$this->io_sql->fetch_row($li_exec))
		{
			$lb_existe=true;
			$this->io_sql->free_result($li_exec);
		}
		else
		{
			$lb_existe=false;
			$this->is_msg_error = "Error en método uf_sss_select_ventana  ";
		}
								
		return $lb_existe;
	}

}//fin de la class sigesp_sss_c_actualizar_ventana

?>
