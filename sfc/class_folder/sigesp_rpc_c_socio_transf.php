<?php
class sigesp_rpc_c_socio_transf
{

var $ls_sql;
	
		function sigesp_rpc_c_socio_transf($conn)
		{  
		  require_once("../shared/class_folder/sigesp_c_seguridad.php");
	      $this->seguridad = new sigesp_c_seguridad();
		  require_once("../shared/class_folder/class_mensajes.php");
		  require_once("../shared/class_folder/class_funciones.php");
		  require_once("../shared/class_folder/sigesp_sfc_c_intarchivo.php");
	      $this->$archivo= new sigesp_sfc_c_intarchivo("/var/www/sigesp_fac/sfc/transferencias/PROVEEDORES");
		  //$this->archivo= new sigesp_sfc_c_intarchivo("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/PROVEEDORES");
		  $this->io_funcion = new class_funciones();
		  $this->io_sql= new class_sql($conn);
		  $this->io_msg= new class_mensajes();		
		}
 
function uf_insert_socio_transf($as_codemp,$as_prov,$as_cedula,$as_nombre,
                        $as_apellido,$as_direccion,$as_cargo,
                        $as_telefono,$as_email,$aa_seguridad) 
{
 //////////////////////////////////////////////////////////////////////////////
 //
 //	Metodo       uf_insert_socio
 //	Access       public
 //	Arguments    $as_codemp,$as_prov,$as_cedula,$as_nombre,$as_apellido,
 //              $as_direccion,$as_cargo,$as_telefono,$as_email,$aa_seguridad
 //	Returns		 $lb_valido. Retorna una variable booleana.
 //	Description  Funcion que toma los valores traidos y a realiza una inserci�n 
 //              en la tabla de rpc_proveedorsocios
 //
 //////////////////////////////////////////////////////////////////////////////

   

	$ls_sql = " INSERT INTO rpc_proveedorsocios ".
			  " (codemp,cod_pro,cedsocio,nomsocio,apesocio,carsocio,telsocio,dirsocio,email) ". 
			  " VALUES ". 
			  " ('".$as_codemp."','".$as_prov."','".$as_cedula."','".$as_nombre."','".$as_apellido."',".
			  " '".$as_cargo."','".$as_telefono."','".$as_direccion."','".$as_email."');";

	 /**************************************** GENERAR ARCHIVO DE TRANSFERENCIA  *****************************************/
		
		$ls_nomarchivo="trans".PROVEEDORES;
		$this->archivo->crear_archivo($ls_nomarchivo);
		$this->archivo->escribir_archivo($ls_sql);
		$this->archivo->cerrar_archivo();
		
	  /*******************************************************************************************************************/ 

	
}

function uf_update_socio_transf($as_codemp,$as_prov,$as_cedula,$as_nombre,
                         $as_apellido,$as_direccion,$as_cargo,
                         $as_telefono,$as_email,$aa_seguridad) 
{
 //////////////////////////////////////////////////////////////////////////////
 //
 //	Metodo       uf_update_socio
 //	Access       public
 //	Arguments    $as_codemp,$as_prov,$as_cedula,$as_nombre,$as_apellido,
 //              $as_direccion,$as_cargo,$as_telefono,$as_email,$aa_seguridad
 //	Returns		 $lb_valido. Retorna una variable booleana.
 //	Description  Funcion que toma los valores traidos y a realiza una actualizacion 
 //              en la tabla de rpc_proveedorsocios.
 //
 //////////////////////////////////////////////////////////////////////////////

  

	$ls_sql=" UPDATE rpc_proveedorsocios ".
			" SET    nomsocio='".$as_nombre."',    apesocio='".$as_apellido."', ".
            "        dirsocio='".$as_direccion."', carsocio='".$as_cargo."',". 
			"        telsocio='".$as_telefono."',  email='".$as_email."' ". 
			" WHERE  codemp   ='".$as_codemp."'   AND ".
            "        cedsocio = '".$as_cedula."'  AND ".
            "        cod_pro  ='".$as_prov."';";
	 /**************************************** GENERAR ARCHIVO DE TRANSFERENCIA  *****************************************/
		
		$ls_nomarchivo="trans".PROVEEDORES;
		$this->archivo->crear_archivo($ls_nomarchivo);
		$this->archivo->escribir_archivo($ls_sql);
		$this->archivo->cerrar_archivo();
		
	  /*******************************************************************************************************************/ 
	
	
} 

function uf_delete_socio_transf($as_codemp,$as_prov,$as_cedula,$aa_seguridad)
{
 //////////////////////////////////////////////////////////////////////////////
 //
 //	Metodo       uf_delete_socio
 //	Access       public
 //	Arguments    $as_codemp,$as_prov,$as_cedula,$aa_seguridad
 //	Returns 	 $lb_valido. Retorna una variable booleana.	
 //	Description  Funcion de eliminar un socio de la tabla rpc_proveedorsocios
 //
 //////////////////////////////////////////////////////////////////////////////

  

  $ls_sql = " DELETE ".
			" FROM rpc_proveedorsocios ".
			" WHERE  codemp='".$as_codemp."'   AND ".
            "        cedsocio='".$as_cedula."' AND ".
			"        cod_pro='".$as_prov."'; ";	    
  
   /**************************************** GENERAR ARCHIVO DE TRANSFERENCIA  *****************************************/
		
		$ls_nomarchivo="trans".PROVEEDORES;
		$this->archivo->crear_archivo($ls_nomarchivo);
		$this->archivo->escribir_archivo($ls_sql);
		$this->archivo->cerrar_archivo();
		
  /*******************************************************************************************************************/ 
  
}

}//Fin de la Clase...
?>