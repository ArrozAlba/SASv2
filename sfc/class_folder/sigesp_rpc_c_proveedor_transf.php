<?php
class sigesp_rpc_c_proveedor_transf
 {
    var $ls_sql="";
	var $la_emp;
	var $io_msg_error;
	
	function sigesp_rpc_c_proveedor_transf()
	{
   		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
        require_once("../shared/class_folder/sigesp_c_seguridad.php");
 	    require_once("../shared/class_folder/class_funciones.php");
 	   	require_once("../shared/class_folder/sigesp_sfc_c_intarchivo.php");
	    $this->$archivo= new sigesp_sfc_c_intarchivo("/var/www/sigesp_fac/sfc/transferencias/PROVEEDORES");
		//$this->archivo= new sigesp_sfc_c_intarchivo("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/PROVEEDORES");
		
		$this->io_funcion = new class_funciones();
		$this->seguridad = new sigesp_c_seguridad();	 		
        $io_conect=new sigesp_include();
		$conn=$io_conect->uf_conectar();
		$this->la_emp=$_SESSION["la_empresa"];
		$this->io_sql=new class_sql($conn); //Instanciando  la clase sql
		$_SESSION["gestor"]="MYSQL";
		$this->gestor=$_SESSION["gestor"];
		$this->io_msg= new class_mensajes();
	}




function uf_insert_proveedor_transf($as_codemp,$ar_datos,$aa_seguridad)
{  	   
//////////////////////////////////////////////////////////////////////////////
//	Metodo        uf_insert_proveedor
//	Access        public
//	Arguments     $as_codemp,$ar_datos,$aa_seguridad
//	Returns		
//	Description   Funcion que carga los valores traidos en la carga de datos desde el $ar_datos 
//               y asigna el valor respectivo a cada variable y a realiza una busqueda para 
//               decidir si el registro ya existe para actualizarlo "UPDATE"o si 
//			      el registro no existe realizar un "INSERT". 
//////////////////////////////////////////////////////////////////////////////

  $ls_codigo       = $ar_datos["codpro"];
  $ls_nombre       = $ar_datos["nompro"];
  $ls_direccion    = $ar_datos["dirpro"];
  $ls_tiporg       = $ar_datos["tiporg"];
  $ls_telefono     = $ar_datos["telpro"];
  $ls_fax          = $ar_datos["faxpro"];
  $ls_nacionalidad = $ar_datos["nacpro"];
  $ls_especialidad = $ar_datos["esppro"];
  $ls_rif          = $ar_datos["rif"];
  $ls_nit          = $ar_datos["nit"];
  $ls_banco        = $ar_datos["banco"];
  $ls_cuenta       = $ar_datos["cuenta"];
  $ls_moneda       = $ar_datos["moneda"];
  $ls_graemp       = $ar_datos["graemp"];
  $ls_emailrep     = $ar_datos["txtemailrep"];
  $ls_pais         = $ar_datos["pais"];
  $ls_estado       = $ar_datos["estado"];
  $ls_municipio    = $ar_datos["municipio"];
  $ls_parroquia    = $ar_datos["parroquia"];
  $ls_contable     = $ar_datos["contable"];
  $ls_observacion  = $ar_datos["observacion"];
  $ls_cedula       = $ar_datos["cedula"];
  $ls_nomrep       = $ar_datos["nomrep"];
  $ls_cargo        = $ar_datos["cargo"];
  $ls_numregRNC    = $ar_datos["numregrnc"];
  $ls_registro     = $ar_datos["registro"];
  $ls_fecreg       = $ar_datos["fecreg"];
  $ls_numero       = $ar_datos["numero"];
  $ls_tomo         = $ar_datos["tomo"];
  $ls_fecregRNC    = $ar_datos["fecregrnc"];
  $ls_fecregmod    = $ar_datos["fecregmod"];
  $ls_regmod       = $ar_datos["regmod"];
  $ls_nummod       = $ar_datos["nummod"];
  $ls_tommod       = $ar_datos["tommod"];
  $ls_numfol       = $ar_datos["numfol"];
  $ls_numfolmod    = $ar_datos["numfolmod"];
  $ls_numlic       = $ar_datos["numlic"];
  $ls_fecvenRNC    = $ar_datos["fecvenrnc"];
  $ls_regSSO       = $ar_datos["regsso"];
  $ls_fecvenSSO    = $ar_datos["fecvensso"];
  $ls_regINCE      = $ar_datos["regince"];
  $ls_fecvenINCE   = $ar_datos["fecvenince"];
  $ls_estprovedor  = $ar_datos["estatus"];
  $ls_pagweb       = $ar_datos["pagweb"];
  $ls_email        = $ar_datos["email"];
  $ls_inspector    = $ar_datos["inspector"];
  $ld_contratista=$ar_datos["estcon"];
  $ld_proveedor=$ar_datos["estpro"];
  $ld_capital      = $ar_datos["capital"];
  $ld_capital      = str_replace('.','',$ld_capital);
  $ld_capital      = str_replace(',','.',$ld_capital);      
  $ld_monmax       = $ar_datos["monmax"];      
  $ld_monmax       = str_replace('.','',$ld_monmax);
  $ld_monmax       = str_replace(',','.',$ld_monmax);
  $ls_codbansig    = $ar_datos["codbancof"];
  $ls_tipconpro    = $ar_datos["tipconpro"];
  if ($ls_tipconpro=='-'){$ls_tipconpro='O';}
  if ($ls_tiporg=='00')
	 {
	   $ls_tiporg = '--';
	 }
    if ($ls_moneda=="000")
	 {
	   $ls_moneda='---';
	 }
  if (empty($ls_fecreg) || ($ls_fecreg=="--"))
     {
	   $ls_fecreg="1900-01-01 00:00:00";
     }
  if (empty($ls_fecregRNC) || ($ls_fecregRNC=="--"))
     {
	   $ls_fecregRNC="1900-01-01 00:00:00";
     }
  if (empty($ls_fecregmod) || ($ls_fecregmod=="--"))
     {
	   $ls_fecregmod="1900-01-01 00:00:00";
     }
  if (empty($ls_fecvenRNC) || ($ls_fecvenRNC=="--"))
     {
	   $ls_fecvenRNC="1900-01-01 00:00:00";
     }
  if (empty($ls_fecvenSSO) || ($ls_fecvenSSO=="--"))
     {
	   $ls_fecvenSSO="1900-01-01 00:00:00";
     }
  if (empty($ls_fecvenINCE) || ($ls_fecvenINCE=="--"))
     {
	   $ls_fecvenINCE="1900-01-01 00:00:00";
     }
  if ($ls_estprovedor=="A")
     {
	   $ls_estprov=0;
     } 	  
  if ($ls_estprovedor=="I")
     {
	   $ls_estprov=1;
     } 
  if ($ls_estprovedor=="B")
     {
	   $ls_estprov=2;
     }
  if ($ls_estprovedor=="S")
     {
	   $ls_estprov=3;
     } 	  
   if ($ls_inspector==1)
	 {
	   $ld_inspector=1;
	 }
  else
	 {
	   $ld_inspector=0;
	 }
 
	  $ls_sql=" INSERT INTO rpc_proveedor(codemp,cod_pro,nompro,dirpro,telpro,faxpro,nacpro,codtipoorg,                      ".
			  " codesp,rifpro,nitpro,capital,monmax,codban,ctaban,codmon,sc_cuenta,obspro,codpai,codest,                     ".
			  " codmun,codpar,cedrep,nomreppro,carrep,ocei_no_reg,registro,fecreg,nro_reg,tomo_reg,                          ".
			  " ocei_fec_reg,regmod,fecregmod,nummod,tommod,inspector,estpro,estcon,folreg,folmod,numlic,                    ".
			  " estprov,pagweb,email,fecvenrnc,numregsso,fecvensso,numregince,fecvenince,graemp,emailrep,codbansig,tipconpro)".
			  " VALUES                                                                                                       ".
			  " ('".$as_codemp."','".$ls_codigo."','".$ls_nombre."','".$ls_direccion."',                                     ".
			  " '".$ls_telefono."','".$ls_fax."','".$ls_nacionalidad."','".$ls_tiporg."',                                    ".
			  " '".$ls_especialidad."','".$ls_rif."','".$ls_nit."',".$ld_capital.",".$ld_monmax.",                           ".
			  " '".$ls_banco."','".$ls_cuenta."','".$ls_moneda."','".$ls_contable."','".$ls_observacion."',                  ".
			  " '".$ls_pais."','".$ls_estado."','".$ls_municipio."','".$ls_parroquia."','".$ls_cedula."',                    ".
			  " '".$ls_nomrep."','".$ls_cargo."','".$ls_numregRNC."','".$ls_registro."','".$ls_fecreg."',                    ".
			  " '".$ls_numero."','".$ls_tomo."','".$ls_fecregRNC."','".$ls_regmod."','".$ls_fecregmod."',                    ".
			  " '".$ls_nummod."','".$ls_tommod."','".$ld_inspector."','".$ld_proveedor."','".$ld_contratista."',             ".
			  " '".$ls_numfol."','".$ls_numfolmod."','".$ls_numlic."','".$ls_estprov."','".$ls_pagweb."','".$ls_email."',    ".
			  " '".$ls_fecvenRNC."','".$ls_regSSO."','".$ls_fecvenSSO."','".$ls_regINCE."','".$ls_fecvenINCE."',             ".
			  " '".$ls_graemp."','".$ls_emailrep."','".$ls_codbansig."','".$ls_tipconpro."')                                ;";  
	
	
	 /**************************************** GENERAR ARCHIVO DE TRANSFERENCIA  *****************************************/
		
		$ls_nomarchivo="trans".PROVEEDORES;
		$this->archivo->crear_archivo($ls_nomarchivo);
		$this->archivo->escribir_archivo($ls_sql);
		$this->archivo->cerrar_archivo();
		
	  /*******************************************************************************************************************/ 
	   
  
 } 

function uf_update_proveedor_transf($as_codemp,$ar_datos,$aa_seguridad)
{  	   
//////////////////////////////////////////////////////////////////////////////
//	Metodo        uf_update_proveedor
//	Access        public
//	Arguments     $as_codemp,$ar_datos,$aa_seguridad
//	Returns		
//	Description   Funcion que carga los valores traidos en la carga de datos desde el $ar_datos 
//               y asigna el valor respectivo a cada variable y a realiza una busqueda para 
//               decidir si el registro ya existe para actualizarlo "UPDATE"o si 
//			      el registro no existe realizar un "INSERT". 
//////////////////////////////////////////////////////////////////////////////

  $ls_codigo=$ar_datos["codpro"];
  $ls_nombre=$ar_datos["nompro"];
  $ls_direccion=$ar_datos["dirpro"];
  $ls_tiporg=$ar_datos["tiporg"];
  $ls_telefono=$ar_datos["telpro"];
  $ls_fax=$ar_datos["faxpro"];
  $ls_nacionalidad=$ar_datos["nacpro"];
  $ls_especialidad=$ar_datos["esppro"];
  $ls_rif=$ar_datos["rif"];
  $ls_nit=$ar_datos["nit"];
  $ld_capital=$ar_datos["capital"];
  $ld_monmax=$ar_datos["monmax"];
  $ls_banco=$ar_datos["banco"];
  $ls_cuenta=$ar_datos["cuenta"];
  $ls_moneda    = $ar_datos["moneda"];
  $ls_graemp    = $ar_datos["graemp"];
  $ls_emailrep  = $ar_datos["txtemailrep"];
  $ls_codbansig = $ar_datos["codbancof"]; 
  $ls_pais      = $ar_datos["pais"];
  $ls_estado    = $ar_datos["estado"];
  $ls_municipio = $ar_datos["municipio"];
  $ls_parroquia = $ar_datos["parroquia"];
  $ls_tipconpro = $ar_datos["tipconpro"];
  if ($ls_tipconpro=='-'){$ls_tipconpro='O';}
 //FIN UBICACI�N GEOGR�FICA
  $ls_contable=$ar_datos["contable"];
  $ls_observacion=$ar_datos["observacion"];

  /*Datos del Registro*/
  $ls_cedula=$ar_datos["cedula"];
  $ls_nomrep=$ar_datos["nomrep"];
  $ls_cargo=$ar_datos["cargo"];
  $ls_numregRNC=$ar_datos["numregrnc"];
  $ls_registro=$ar_datos["registro"];
  $ls_fecreg=$ar_datos["fecreg"];
  if(empty($ls_fecreg) || ($ls_fecreg=="--"))
  {
	$ls_fecreg="1900-01-01 00:00:00";
  }
  $ls_numero=$ar_datos["numero"];
  $ls_tomo=$ar_datos["tomo"];
  $ls_fecregRNC=$ar_datos["fecregrnc"];
  if(empty($ls_fecregRNC) || ($ls_fecregRNC=="--"))
  {
	$ls_fecregRNC="1900-01-01 00:00:00";
  }
  $ls_fecregmod=$ar_datos["fecregmod"];
  if(empty($ls_fecregmod) || ($ls_fecregmod=="--"))
  {
	$ls_fecregmod="1900-01-01 00:00:00";
  }
  $ls_regmod=$ar_datos["regmod"];
  $ls_nummod=$ar_datos["nummod"];
  $ls_tommod=$ar_datos["tommod"];
  $ls_numfol=$ar_datos["numfol"];
  $ls_numfolmod=$ar_datos["numfolmod"];
  $ls_numlic=$ar_datos["numlic"];
  $ls_fecvenRNC=$ar_datos["fecvenrnc"];
  if(empty($ls_fecvenRNC) || ($ls_fecvenRNC=="--"))
  {
	$ls_fecvenRNC="1900-01-01 00:00:00";
  }
  $ls_regSSO=$ar_datos["regsso"];
  $ls_fecvenSSO=$ar_datos["fecvensso"];
  if(empty($ls_fecvenSSO) || ($ls_fecvenSSO=="--"))
  {
	$ls_fecvenSSO="1900-01-01 00:00:00";
  }
  $ls_regINCE=$ar_datos["regince"];
  $ls_fecvenINCE=$ar_datos["fecvenince"];
  if(empty($ls_fecvenINCE) || ($ls_fecvenINCE=="--"))
  {
	$ls_fecvenINCE="1900-01-01 00:00:00";
  }
  $ls_estprovedor=$ar_datos["estatus"];
  $ls_pagweb=$ar_datos["pagweb"];
  $ls_email=$ar_datos["email"];
  if ($ls_estprovedor=="A")
     {
	   $ls_estprov=0;
     }	  
  if ($ls_estprovedor=="I")
  {
	 $ls_estprov=1;
  }
  if ($ls_estprovedor=="B")
  {
	 $ls_estprov=2;
  }
  if ($ls_estprovedor=="S")
  {
	 $ls_estprov=3;
  }	  
  
  $ls_inspector=$ar_datos["inspector"];
  
  if ($ls_inspector==1)
	 {
	   $ld_inspector=1;
	 }
  else
	 {
	   $ld_inspector=0;
	 }
  $ld_contratista=$ar_datos["estcon"];
  $ld_proveedor=$ar_datos["estpro"];

  $ld_capital=$ar_datos["capital"];

  $ld_capital=str_replace('.','',$ld_capital);
  $ld_capital=str_replace(',','.',$ld_capital);      
  $ld_monmax=$ar_datos["monmax"];      
 
  $ld_monmax=str_replace('.','',$ld_monmax);
  $ld_monmax=str_replace(',','.',$ld_monmax);

  $ls_sql=" UPDATE rpc_proveedor ".
		  " SET  nompro='".$ls_nombre."',dirpro='".$ls_direccion."',telpro='".$ls_telefono."',".
		  " faxpro='".$ls_fax."',nacpro='".$ls_nacionalidad."',codtipoorg='".$ls_tiporg."',".
		  " codesp='".$ls_especialidad."',rifpro='".$ls_rif."',nitpro='".$ls_nit."',".
		  " capital='".$ld_capital."',monmax='".$ld_monmax."',codban='".$ls_banco."',".
		  " ctaban='".$ls_cuenta."',codmon='".$ls_moneda."',sc_cuenta='".$ls_contable."',             ".
		  " obspro='".$ls_observacion."',codpai='".$ls_pais."',codest='".$ls_estado."',               ".
		  " codmun='".$ls_municipio."',codpar='".$ls_parroquia."',cedrep='".$ls_cedula."',            ".
		  " nomreppro='".$ls_nomrep."',carrep='".$ls_cargo."',ocei_no_reg='".$ls_numregRNC."',        ".
		  " registro='".$ls_registro."',fecreg='".$ls_fecreg."',nro_reg='".$ls_numero."',             ".
		  " tomo_reg='".$ls_tomo."',ocei_fec_reg='".$ls_fecregRNC."',regmod='".$ls_regmod."',         ".
		  " fecregmod='".$ls_fecregmod."',nummod='".$ls_nummod."',tommod='".$ls_tommod."',            ".
		  " inspector=".$ld_inspector.",estpro=".$ld_proveedor.",estcon=".$ld_contratista.",          ".
		  " folreg='".$ls_numfol."', folmod='".$ls_numfolmod."', numlic='".$ls_numlic."',             ".
		  " pagweb='".$ls_pagweb."',email='".$ls_email."',fecvenrnc='".$ls_fecvenRNC."',              ".
		  " numregsso='".$ls_regSSO."',fecvensso='".$ls_fecvenSSO."',numregince='".$ls_regINCE."',    ".
		  " fecvenince='".$ls_fecvenINCE."',estprov=".$ls_estprov.", graemp='".$ls_graemp."',         ".
		  " emailrep='".$ls_emailrep."', codbansig='".$ls_codbansig."', tipconpro='".$ls_tipconpro."' ".
		  " WHERE codemp='".$as_codemp."' AND cod_pro='".$ls_codigo."' ;";


	
	 /**************************************** GENERAR ARCHIVO DE TRANSFERENCIA  *****************************************/
		
		$ls_nomarchivo="trans".PROVEEDORES;
		$this->archivo->crear_archivo($ls_nomarchivo);
		$this->archivo->escribir_archivo($ls_sql);
		$this->archivo->cerrar_archivo();
		
	  /*******************************************************************************************************************/ 
	   
	   	
//return $lb_valido;
}


function uf_delete_proveedor_transf($as_codemp,$as_codpro,$aa_seguridad)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Metodo        uf_delete_proveedor
//	Access        public
//	Arguments     $as_codemp,$as_codpro,$aa_seguridad
//	Returns	      lb_valido. Retorna una variable booleana
//	Description   Metodo que se encarga de eliminar un Proveedor
//               en la base de datos y retorna una variable booleana de que fue eliminada 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   $lb_valido=true;
   
   
			if ($this->uf_delete_documentos_transf($as_codemp,$as_codpro,$aa_seguridad))
			{ 
				 $ls_sql  = "DELETE FROM rpc_proveedor WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codpro."'";
	  /**************************************** GENERAR ARCHIVO DE TRANSFERENCIA  *****************************************/
		
		$ls_nomarchivo="trans".PROVEEDORES;
		$this->archivo->crear_archivo($ls_nomarchivo);
		$this->archivo->escribir_archivo($ls_sql);
		$this->archivo->cerrar_archivo();
		
	  /*******************************************************************************************************************/ 
	 
				
				 
			}
			
}	                         
    
function uf_delete_documentos_transf($as_codemp,$as_codpro,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	Funcion      uf_delete_documentos
//	Access       public
//	Arguments    $as_codemp,$as_codpro
//	Returns	     lb_valido. Retorna una variable booleana
//	Description  Funcion que se encarga validar si se puede eliminar un Proveedor 
//               en la base de datos y retorna una variable booleana de que es valido 
//////////////////////////////////////////////////////////////////////////////

      $ls_sql  = " DELETE FROM rpc_docxprov WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codpro."' ;";
     
	  /**************************************** GENERAR ARCHIVO DE TRANSFERENCIA  *****************************************/
		
		$ls_nomarchivo="trans".PROVEEDORES;
		$this->archivo->crear_archivo($ls_nomarchivo);
		$this->archivo->escribir_archivo($ls_sql);
		$this->archivo->cerrar_archivo();
		
	  /*******************************************************************************************************************/ 
	 
        $ls_sql2 = "DELETE FROM rpc_clasifxprov WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codpro."';";
         /**************************************** GENERAR ARCHIVO DE TRANSFERENCIA  *****************************************/
		
		$ls_nomarchivo="trans".PROVEEDORES;
		$this->archivo->crear_archivo($ls_nomarchivo);
		$this->archivo->escribir_archivo($ls_sql2);
		$this->archivo->cerrar_archivo();
		
	  /*******************************************************************************************************************/ 
	 
		   
		$ls_sql3="DELETE ".
					"  FROM rpc_espexprov ".
					" WHERE codemp='".$as_codemp."'".
					"   AND cod_pro='".$as_codpro."';";
			
		 
	 /**************************************** GENERAR ARCHIVO DE TRANSFERENCIA  *****************************************/
		
		$ls_nomarchivo="trans".PROVEEDORES;
		$this->archivo->crear_archivo($ls_nomarchivo);
		$this->archivo->escribir_archivo($ls_sql3);
		$this->archivo->cerrar_archivo();
		
	  /*******************************************************************************************************************/ 
	$lb_valido=true; 	
return $lb_valido;
}


}//Fin de la Clase.
?>