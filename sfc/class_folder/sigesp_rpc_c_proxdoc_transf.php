<?php
class sigesp_rpc_c_proxdoc_transf
 {
    var $ls_sql="";
	var $la_emp;

	function sigesp_rpc_c_proxdoc_transf()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
	    $this->seguridad = new sigesp_c_seguridad();
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funcion = new class_funciones();
		require_once("../shared/class_folder/sigesp_sfc_c_intarchivo.php");
 		$this->$archivo= new sigesp_sfc_c_intarchivo("/var/www/sigesp_fac/sfc/transferencias/PROVEEDORES");
		//$this->archivo= new sigesp_sfc_c_intarchivo("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/PROVEEDORES");
		$io_conect=new sigesp_include();
		$conn=$io_conect->uf_conectar();
		$this->la_emp=$_SESSION["la_empresa"];
		$this->io_sql=new class_sql($conn);
		$_SESSION["gestor"]="MYSQL";
		$this->gestor=$_SESSION["gestor"];
		$this->io_msg= new class_mensajes();
	}

  
function ue_guardar_transf($as_codemp,$as_codprov,$ar_datos,$aa_seguridad)
{  	   
//////////////////////////////////////////////////////////////////////////////
 //	Funcion : ue_guardar
 //	Access:  public
 //	Arguments:  $as_codemp,$as_codprov,$ar_datos,$aa_seguridad
 //	Returns:	lb_valido
 //				retorna una variable booleana
 //	Description:  Busca un registro dentro de la tabla rpc_proveedor en 
 //               la base de datos y retorna una variable booleana de que  
 //               existe 
 ////////////////////////////////////////////////////////////////////////////// 
  
  $ls_coddoc=$ar_datos["codigo"];
  $ls_fecrec=$ar_datos["fecrec"];
  $ls_fecven=$ar_datos["fecven"];
  $ls_estdoc=$ar_datos["documento"];
  $ls_estori=$ar_datos["original"];
  if($this->uf_existe_proxdoc($as_codemp,$ls_coddoc,$as_codprov))
  {
	$ls_sql=" UPDATE rpc_docxprov ".
			" SET fecrecdoc='".$ls_fecrec."',fecvendoc='".$ls_fecven."',estdoc='".$ls_estdoc."',".
			" estorig='".$ls_estori."' ".
			" WHERE codemp='".$as_codemp."' AND  coddoc='".$ls_coddoc."' AND  cod_pro='".$as_codprov."';";
	
	/**************************************** GENERAR ARCHIVO DE TRANSFERENCIA  *****************************************/
		
		$ls_nomarchivo="trans".PROVEEDORES;
		$this->archivo->crear_archivo($ls_nomarchivo);
		$this->archivo->escribir_archivo($ls_sql);
		$this->archivo->cerrar_archivo();
		
	 /*******************************************************************************************************************/ 

  }
  else
  {
	$ls_sql=" INSERT INTO rpc_docxprov ".
			" (codemp,coddoc,cod_pro,fecrecdoc,fecvendoc,estdoc,estorig)".
			" VALUES".
			" ('".$as_codemp."','".$ls_coddoc."','".$as_codprov."','".$ls_fecrec."','".$ls_fecven."','".$ls_estdoc."','".$ls_estori."');";
	
	/**************************************** GENERAR ARCHIVO DE TRANSFERENCIA  *****************************************/
		
		$ls_nomarchivo="trans".PROVEEDORES;
		$this->archivo->crear_archivo($ls_nomarchivo);
		$this->archivo->escribir_archivo($ls_sql);
		$this->archivo->cerrar_archivo();
		
	  /*******************************************************************************************************************/ 
	
   }	

}


function uf_existe_proxdoc($as_codemp,$ls_coddoc,$as_codprov)
{
 //////////////////////////////////////////////////////////////////////////////
 //	Funcion : uf_existe_proxdoc
 //	Access:  public
 //	Arguments:  $as_codemp,$ls_coddoc,$as_codprov
 //	Returns:	lb_valido
 //				retorna una variable booleana
 //	Description:  Busca un registro dentro de la tabla rpc_proveedor en 
 //               la base de datos y retorna una variable booleana de que  
 //               existe 
 ////////////////////////////////////////////////////////////////////////////// 

	$ls_sql=" SELECT * ".
			" FROM rpc_docxprov ".
			" WHERE codemp='".$as_codemp."' AND coddoc='" .$ls_coddoc."' AND cod_pro='" .$as_codprov."'";
	$rs_proxdoc=$this->io_sql->select($ls_sql);
    if ($rs_proxdoc===false)
		{
		   $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   $lb_valido=false;
		}
		else
		{
		   $li_numrows=$this->io_sql->num_rows($rs_proxdoc);
		   if($li_numrows>0)
			 {
			   $lb_valido=true;
			   $this->io_sql->free_result($rs_proxdoc);			
			 }
			 else
			 {
			   $lb_valido=false;
			 }
		}
return $lb_valido;
}


function ue_eliminar_transf($as_codemp,$ls_coddoc,$as_codprov,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
 //
 //	Funcion : ue_eliminar
 //
 //	Access:  public
 //
 //	Arguments:  $as_codemp,$ls_coddoc,$as_codprov,$aa_seguridad
 //
 //	Returns:	lb_valido
 //				retorna una variable booleana
 //           
 //
 //	Description:  Busca un registro dentro de la tabla rpc_proveedor en 
 //               la base de datos y retorna una variable booleana de que  
 //               existe 
 //
 ////////////////////////////////////////////////////////////////////////////// 

	$ls_sql="";
	$ls_sql=" DELETE ".
			" FROM rpc_docxprov ".
			" WHERE codemp='".$as_codemp."' AND coddoc='".$ls_coddoc."' AND cod_pro='".$as_codprov."';";

	/**************************************** GENERAR ARCHIVO DE TRANSFERENCIA  *****************************************/
		
		$ls_nomarchivo="trans".PROVEEDORES;
		$this->archivo->crear_archivo($ls_nomarchivo);
		$this->archivo->escribir_archivo($ls_sql);
		$this->archivo->cerrar_archivo();
		
	  /*******************************************************************************************************************/ 
	
}	 

}//Fin de la Clase...
?>