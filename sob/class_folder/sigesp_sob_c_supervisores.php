<?PHP
class sigesp_sob_c_supervisores
{
	var $io_funcion;
	var $is_msg_error;
	var $io_sql;
	var $la_empresa;
	var $io_msg;
		
	function sigesp_sob_c_supervisores()
	{						
		require_once ("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once ("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->seguridad=   new sigesp_c_seguridad();
		$this->io_function = new class_funciones();		
		$this->io_msg= new class_mensajes();		
		$io_include=new sigesp_include();
		$io_connect=$io_include->uf_conectar();		
		$this->io_sql= new class_sql($io_connect);					
		$this->la_empresa=$_SESSION["la_empresa"];
	}
	

function uf_select_supervisor ($as_cedsup,&$aa_data)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_select_supervisor
	// Access:			public
	//	Returns:		Boolean, Retorna true si existe el registro en bd
	//	Description:	Funcion que se encarga de verificar la existencia de un 
	//					supervisor y retornar su información
	//  Fecha:          12/05/2006
	//	Autor:          Ing. Laura Cabré		
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="SELECT *
			 FROM rpc_supervisores
			 WHERE codemp='".$ls_codemp."' AND cedsup='".$as_cedsup."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		print "Error en select supervisor".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($la_row=$this->io_sql->fetch_row($rs_data))
		{
				$lb_valido=true;
				$aa_data=$this->io_sql->obtener_datos($rs_data);
		}		
		else
		{
		  $lb_valido=0;
		  $aa_data="";
		  
		}
	}
	return $lb_valido;
}

function uf_select_proveedor ($as_codpro,&$aa_data)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_select_supervisor
	// Access:			public
	//	Returns:		Boolean, Retorna true si existe el registro en bd
	//	Description:	Funcion que se encarga de seleccionar un proveedor
	//  Fecha:          12/05/2006
	//	Autor:          Ing. Laura Cabré		
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="SELECT *
			 FROM rpc_proveedor
			 WHERE codemp='".$ls_codemp."' AND cod_pro='".$as_codpro."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		print "Error en select proveedor".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($la_row=$this->io_sql->fetch_row($rs_data))
		{
				$lb_valido=true;
				$aa_data=$this->io_sql->obtener_datos($rs_data);
		}		
		else
		{
		  $lb_valido=0;
		  $aa_data="";
		  
		}
	}
	return $lb_valido;
}	
}
?>