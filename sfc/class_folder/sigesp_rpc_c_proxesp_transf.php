<?PHP
class sigesp_rpc_c_proxesp_transf
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_fun_rpc;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_rpc_c_proxesp_transf()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_rpc_c_proxesp
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 24/05/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
		require_once("class_funciones_rpc.php");
		$this->io_fun_rpc=new class_funciones_rpc();
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		require_once("../shared/class_folder/sigesp_sfc_c_intarchivo.php");
	    $this->$archivo= new sigesp_sfc_c_intarchivo("/var/www/sigesp_fac/sfc/transferencias/PROVEEDORES");
		//$this->archivo= new sigesp_sfc_c_intarchivo("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/PROVEEDORES");
			
	
	}// end function sigesp_rpc_c_proxesp
	
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_eliminar_especialidad_transf($as_codpro,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_eliminar_especialidad
		//		   Access: public
		//	    Arguments: as_codpro  // c�digo de Proveedor
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete � False si hubo error en el delete
		//	  Description: Funcion que elimina las especialidades asociadas a un proveedor
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 24/05/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql="DELETE ".
				"  FROM rpc_espexprov ".
				" WHERE codemp='".$this->ls_codemp."'".
			"   AND cod_pro='".$as_codpro."';";
		
	/**************************************** GENERAR ARCHIVO DE TRANSFERENCIA  *****************************************/
		
		$ls_nomarchivo="trans".PROVEEDORES;
		$this->archivo->crear_archivo($ls_nomarchivo);
		$this->archivo->escribir_archivo($ls_sql);
		$this->archivo->cerrar_archivo();
		
	/*******************************************************************************************************************/ 
	   
		
	}
	
function uf_insert_especialidad_transf($as_codpro,$as_codesp,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_especialidad
		//		   Access: private
		//	    Arguments: as_codpro  // c�digo de Proveedor
		//				   as_codesp  // C�digo de Especialidad
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert � False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla de rpc_espexprov
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 24/05/2007 								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql="INSERT INTO rpc_espexprov (codemp,cod_pro,codesp) VALUES ('".$this->ls_codemp."','".$as_codpro."','".$as_codesp."');";
		
	/**************************************** GENERAR ARCHIVO DE TRANSFERENCIA  *****************************************/
		
		$ls_nomarchivo="trans".PROVEEDORES;
		$this->archivo->crear_archivo($ls_nomarchivo);
		$this->archivo->escribir_archivo($ls_sql);
		$this->archivo->cerrar_archivo();
		
	  /*******************************************************************************************************************/ 
	   
	
	
	}// end function uf_insert_especialidad
	
	
}//fin de la clase
?>