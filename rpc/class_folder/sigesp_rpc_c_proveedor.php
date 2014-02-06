<?php
class sigesp_rpc_c_proveedor
 {
    var $ls_sql="";
	var $la_emp;
	var $io_msg_error;
	
	function sigesp_rpc_c_proveedor()
	{
   		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
        require_once("../shared/class_folder/sigesp_c_seguridad.php");
 	    require_once("../shared/class_folder/class_funciones.php");
		require_once("class_validacion.php");
		$this->io_validacion = new class_validacion();
 	    $this->io_funcion    = new class_funciones();
		$this->seguridad     = new sigesp_c_seguridad();	 		
        $io_conect           = new sigesp_include();
		$conn				 = $io_conect->uf_conectar();
		$this->la_emp=$_SESSION["la_empresa"];
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->io_sql=new class_sql($conn); //Instanciando  la clase sql
		$this->io_msg= new class_mensajes();
	}


function uf_select_validar_rif($as_codemp,$as_rifpro,$as_codpro)
{
//////////////////////////////////////////////////////////////////////////////
//	Metodo       uf_validar_rif
//	Access       public
//	Arguments    $as_codemp,$as_rif
//	Returns      una variable booleana ($lb_valido)		
//	Description  Funcion que encarga de verificar si un rif ya se encuentra 
//               Registrado asignado a otro proveedor 
//////////////////////////////////////////////////////////////////////////////
	$lb_valido = true;
	$ls_sql    = "SELECT rifpro 
	                FROM rpc_proveedor
	               WHERE codemp = '".$as_codemp."'
				     AND cod_pro <> '".$as_codpro."'
				     AND trim(rifpro)='".trim($as_rifpro)."'";
	$rs_data   = $this->io_sql->select($ls_sql);//echo $ls_sql.'<br>';
	if ($rs_data===false)
	   {
		  $lb_valido=false;
	      $this->is_msg_error="CLASE->SIGESP_RPC_C_PROVEEDOR; METODO->uf_select_validar_rif; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	   }
	else
	   {
		 if ($row=$this->io_sql->fetch_row($rs_data))
			{ 
			  $lb_valido = false;
			  $this->io_sql->free_result($rs_data);
			}
	   }
return $lb_valido;
}

function uf_insert_proveedor($as_codemp,$ar_datos,$aa_seguridad)
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
  $ls_rifpro       = trim($ar_datos["tipperrif"].'-'.$ar_datos["numpririf"].'-'.$ar_datos["numterrif"]);
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
  $ls_contablerecdoc    = $ar_datos["contablerecdoc"];
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
  $ls_tipperpro    = $ar_datos["tipperpro"];
  $ls_ctaant    = $ar_datos["ctaant"];
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
	   $ls_fecreg="1900-01-01";
     }
  if (empty($ls_fecregRNC) || ($ls_fecregRNC=="--"))
     {
	   $ls_fecregRNC="1900-01-01";
     }
  if (empty($ls_fecregmod) || ($ls_fecregmod=="--"))
     {
	   $ls_fecregmod="1900-01-01";
     }
  if (empty($ls_fecvenRNC) || ($ls_fecvenRNC=="--"))
     {
	   $ls_fecvenRNC="1900-01-01";
     }
  if (empty($ls_fecvenSSO) || ($ls_fecvenSSO=="--"))
     {
	   $ls_fecvenSSO="1900-01-01";
     }
  if (empty($ls_fecvenINCE) || ($ls_fecvenINCE=="--"))
     {
	   $ls_fecvenINCE="1900-01-01";
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
  if (!$this->uf_select_validar_rif($as_codemp,$ls_rifpro,$ls_codigo))
     {				 
	   $this->io_msg->message('El RIF Ya Existe en un Proveedor !!!');           
	   $lb_valido=false;
	 }
  else
	 {
	  $ls_sql=" INSERT INTO rpc_proveedor(codemp,cod_pro,nompro,dirpro,telpro,faxpro,nacpro,codtipoorg,                      ".
			  " codesp,rifpro,nitpro,capital,monmax,codban,ctaban,codmon,sc_cuenta,obspro,codpai,codest,                     ".
			  " codmun,codpar,cedrep,nomreppro,carrep,ocei_no_reg,registro,fecreg,nro_reg,tomo_reg,                          ".
			  " ocei_fec_reg,regmod,fecregmod,nummod,tommod,inspector,estpro,estcon,folreg,folmod,numlic,                    ".
			  " estprov,pagweb,email,fecvenrnc,numregsso,fecvensso,numregince,fecvenince,graemp,emailrep,codbansig,          ".
			  " tipconpro,sc_cuentarecdoc,sc_ctaant,tipperpro)".
			  " VALUES                                                                                                       ".
			  " ('".$as_codemp."','".$ls_codigo."','".$ls_nombre."','".$ls_direccion."',                                     ".
			  " '".$ls_telefono."','".$ls_fax."','".$ls_nacionalidad."','".$ls_tiporg."',                                    ".
			  " '".$ls_especialidad."','".$ls_rifpro."','".$ls_nit."',".$ld_capital.",".$ld_monmax.",                        ".
			  " '".$ls_banco."','".$ls_cuenta."','".$ls_moneda."','".$ls_contable."','".$ls_observacion."',                  ".
			  " '".$ls_pais."','".$ls_estado."','".$ls_municipio."','".$ls_parroquia."','".$ls_cedula."',                    ".
			  " '".$ls_nomrep."','".$ls_cargo."','".$ls_numregRNC."','".$ls_registro."','".$ls_fecreg."',                    ".
			  " '".$ls_numero."','".$ls_tomo."','".$ls_fecregRNC."','".$ls_regmod."','".$ls_fecregmod."',                    ".
			  " '".$ls_nummod."','".$ls_tommod."','".$ld_inspector."','".$ld_proveedor."','".$ld_contratista."',             ".
			  " '".$ls_numfol."','".$ls_numfolmod."','".$ls_numlic."','".$ls_estprov."','".$ls_pagweb."','".$ls_email."',    ".
			  " '".$ls_fecvenRNC."','".$ls_regSSO."','".$ls_fecvenSSO."','".$ls_regINCE."','".$ls_fecvenINCE."',             ".
			  " '".$ls_graemp."','".$ls_emailrep."','".$ls_codbansig."','".$ls_tipconpro."','".$ls_contablerecdoc."','".$ls_ctaant."','".$ls_tipperpro."')";  
	  $this->io_sql->begin_transaction(); 
	  $rs_data=$this->io_sql->execute($ls_sql);
	  if ($rs_data===false)
	     { 
		   $lb_valido=false;
		   $this->is_msg_error="CLASE->SIGESP_RPC_C_PROVEEDOR; METODO->uf_insert_proveedor; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		 }
	  else
	     { 
		   $lb_valido=true;
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		   $ls_evento="INSERT";
		   $ls_sql = str_replace("'",'`',$ls_sql);
		   $ls_descripcion ="Insertó en RPC al Proveedor ".$ls_codigo." con ".$ls_sql;
		   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		   $aa_seguridad["ventanas"],$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               ///////////////////////////  		   			 
	       if ($lb_valido)
			  {//**********************   Envio del Correo ***********************
			   if (!empty($ls_email))
			      {
				    $ls_asunto="Registro de Proveedores";                 
				    $ls_cuerpo="Su Registro fue Exitoso y su Código es :  ".$ls_codigo;					
				    if (@mail($ls_email,$ls_asunto,$ls_cuerpo))
					   {
					     $this->io_msg->message('Correo Enviado !!!'); 
					   }
				    else
				       {
					     $this->io_msg->message('Falló el Envio del Correo al Proveedor !!!'); 
				       } 	
			      }
		     }
	     }	  	
     }
 return $lb_valido;
 } 

function uf_update_proveedor($as_codemp,$ar_datos,$aa_seguridad)
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
  $ls_especialidad = $ar_datos["esppro"];
  $ls_rifpro       = trim($ar_datos["tipperrif"].'-'.$ar_datos["numpririf"].'-'.$ar_datos["numterrif"]);
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
  $ls_tipperpro = $ar_datos["tipperpro"];
  $ls_ctaant    = $ar_datos["ctaant"];
  if ($ls_tipconpro=='-'){$ls_tipconpro='O';}
 //FIN UBICACIÓN GEOGRÁFICA
  $ls_contable=$ar_datos["contable"];
  $ls_contablerecdoc=$ar_datos["contablerecdoc"];
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
	$ls_fecreg="1900-01-01";
  }
  $ls_numero=$ar_datos["numero"];
  $ls_tomo=$ar_datos["tomo"];
  $ls_fecregRNC=$ar_datos["fecregrnc"];
  if(empty($ls_fecregRNC) || ($ls_fecregRNC=="--"))
  {
	$ls_fecregRNC="1900-01-01";
  }
  $ls_fecregmod=$ar_datos["fecregmod"];
  if(empty($ls_fecregmod) || ($ls_fecregmod=="--"))
  {
	$ls_fecregmod="1900-01-01";
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
	$ls_fecvenRNC="1900-01-01";
  }
  $ls_regSSO=$ar_datos["regsso"];
  $ls_fecvenSSO=$ar_datos["fecvensso"];
  if(empty($ls_fecvenSSO) || ($ls_fecvenSSO=="--"))
  {
	$ls_fecvenSSO="1900-01-01";
  }
  $ls_regINCE=$ar_datos["regince"];
  $ls_fecvenINCE=$ar_datos["fecvenince"];
  if(empty($ls_fecvenINCE) || ($ls_fecvenINCE=="--"))
  {
	$ls_fecvenINCE="1900-01-01";
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
  if (!$this->uf_select_validar_rif($as_codemp,$ls_rifpro,$ls_codigo))
     {				 
       $this->io_msg->message('El RIF Ya Existe en un Proveedor !!!');           
       $lb_valido = false;
     }
 else
 {
  $ls_sql=" UPDATE rpc_proveedor ".
		  " SET  nompro='".$ls_nombre."',dirpro='".$ls_direccion."',telpro='".$ls_telefono."',".
		  " faxpro='".$ls_fax."',nacpro='".$ls_nacionalidad."',codtipoorg='".$ls_tiporg."',".
		  " codesp='".$ls_especialidad."',rifpro='".$ls_rifpro."',nitpro='".$ls_nit."',".
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
		  " emailrep='".$ls_emailrep."', codbansig='".$ls_codbansig."', tipconpro='".$ls_tipconpro."', ".
		  " tipperpro = '".$ls_tipperpro."', sc_cuentarecdoc = '".$ls_contablerecdoc."', sc_ctaant='".$ls_ctaant."' ".
		  " WHERE codemp='".$as_codemp."' AND cod_pro='".$ls_codigo."' ";
		$this->io_sql->begin_transaction(); 
		$rs_data=$this->io_sql->execute($ls_sql);
		if ($rs_data===false)
		{  
		 $lb_valido=false;
		 $this->is_msg_error="CLASE->SIGESP_RPC_C_PROVEEDOR; METODO->uf_update_proveedor; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{  
			$ls_sql="DELETE ".
					"  FROM rpc_deduxprov ".
					" WHERE codded NOT IN (SELECT codded ".
					"					     FROM sigesp_deducciones, rpc_proveedor ".
					"						WHERE rpc_proveedor.codemp = '".$as_codemp."' ".
					"						  AND rpc_proveedor.cod_pro = '".$ls_codigo."' ".
					"                         AND sigesp_deducciones.codemp = rpc_proveedor.codemp  ".
					"   					  AND (sigesp_deducciones.tipopers = rpc_proveedor.tipperpro OR sigesp_deducciones.tipopers ='') ) ";
			$rs_data=$this->io_sql->execute($ls_sql);
			if ($rs_data===false)
			{  
				$lb_valido=false;
				$this->is_msg_error="CLASE->SIGESP_RPC_C_PROVEEDOR; METODO->uf_update_proveedor; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			}
			else
			{		   
			   $lb_valido=true;
			   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			   $ls_evento="UPDATE";
			   $ls_sql = str_replace("'",'`',$ls_sql);
			   $ls_descripcion ="Actualizó en RPC al Proveedor".$ls_codigo." con ".$ls_sql;
			   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
			   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
			   $aa_seguridad["ventanas"],$ls_descripcion);
			   /////////////////////////////////         SEGURIDAD               ///////////////////////////  
			}		   
		}     
		return $lb_valido;
  }
}

function uf_select_proveedor($as_codemp,$as_codigo)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Funcion       uf_select_proveedor
//	Access        public
//	Arguments     $as_codemp,$as_codpro
//	Returns	      lb_valido. Retorna una variable booleana
//	Description   Busca un registro dentro de la tabla rpc_proveedor en la base de datos y retorna una variable booleana de que existe 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido = false;
	$ls_sql    = " SELECT * FROM rpc_proveedor WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codigo."'";
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		  $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));		 
		  $lb_valido=false;
	   }
	else
	   {
		 $li_numrows = $this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
			{
			 $lb_valido=true;                  
			 $this->io_sql->free_result($rs_data);	
			}
	   }
return $lb_valido;
}

function uf_select_proveedor_sep($as_codemp,$as_codigo)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Funcion       uf_select_proveedor_sep
//	Access        public
//	Arguments     $as_codemp,$as_codpro
//	Returns	      lb_valido. Retorna una variable booleana
//	Description   Busca un registro dentro de la tabla rpc_proveedor en la base de datos y retorna una variable booleana de que existe 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido = false;
	$ls_sql    = " SELECT codemp FROM sep_solicitud WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codigo."'";
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {		 
		  $this->is_msg_error="CLASE->SIGESP_RPC_C_PROVEEDOR; METODO->uf_select_proveedor_sep; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);	   
		  $lb_valido=false;
	   }
	else
	   {
		 $li_numrows = $this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
			{
			 $lb_valido=true;                  
			 $this->io_sql->free_result($rs_data);	
			}
	   }
return $lb_valido;
}

function uf_select_proveedor_soc($as_codemp,$as_codigo)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Funcion       uf_select_proveedor_soc
//	Access        public
//	Arguments     $as_codemp,$as_codpro
//	Returns	      lb_valido. Retorna una variable booleana
//	Description   Busca un registro dentro de la tabla rpc_proveedor en la base de datos y retorna una variable booleana de que existe 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido = false;
	$ls_sql    = " SELECT codemp FROM soc_ordencompra WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codigo."'";
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		  $this->is_msg_error="CLASE->SIGESP_RPC_C_PROVEEDOR; METODO->uf_select_proveedor_soc; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);	   		 
		  $lb_valido=false;
	   }
	else
	   {
		 $li_numrows = $this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
			{
			 $lb_valido=true;                  
			 $this->io_sql->free_result($rs_data);	
			}
	   }
return $lb_valido;
}

function uf_select_proveedor_cxp($as_codemp,$as_codigo)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Funcion       uf_select_proveedor_cxp
//	Access        public
//	Arguments     $as_codemp,$as_codpro
//	Returns	      lb_valido. Retorna una variable booleana
//	Description   Busca un registro dentro de la tabla rpc_proveedor en la base de datos y retorna una variable booleana de que existe 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido = false;
	$ls_sql    = " SELECT codemp FROM cxp_rd WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codigo."'";
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		 $this->is_msg_error="CLASE->SIGESP_RPC_C_PROVEEDOR; METODO->uf_select_proveedor_cxp; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);	   
		 $lb_valido=false;
	   }
	else
	   {
		 $li_numrows = $this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
			{
			 $lb_valido=true;                  
			 $this->io_sql->free_result($rs_data);	
			}
	   }
return $lb_valido;
}

function uf_select_proveedor_scb($as_codemp,$as_codigo)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Funcion       uf_select_proveedor_scb
//	Access        public
//	Arguments     $as_codemp,$as_codpro
//	Returns	      lb_valido. Retorna una variable booleana
//	Description   Busca un registro dentro de la tabla rpc_proveedor en la base de datos y retorna una variable booleana de que existe 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido = false;
	$ls_sql    = "SELECT codemp FROM scb_movbco WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codigo."'";	
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		  $this->is_msg_error="CLASE->SIGESP_RPC_C_PROVEEDOR; METODO->uf_select_proveedor_scb; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);	   		 
		  $lb_valido=false;
	   }
	else
	   {
		 $li_numrows = $this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
			{
			 $lb_valido=true;                  
			 $this->io_sql->free_result($rs_data);	
			}
	   }
return $lb_valido;
}

function uf_select_proveedor_sno($as_codemp,$as_codigo)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Funcion       uf_select_proveedor_sno
//	Access        public
//	Arguments     $as_codemp,$as_codpro
//	Returns	      lb_valido. Retorna una variable booleana
//	Description   Busca un registro dentro de la tabla rpc_proveedor en la base de datos y retorna una variable booleana de que existe 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido = false;
	$ls_sql    = "SELECT codemp FROM sno_dt_spg WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codigo."'";	
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		  $this->is_msg_error="CLASE->SIGESP_RPC_C_PROVEEDOR; METODO->uf_select_proveedor_sno; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);	   		 
		  $lb_valido=false;
	   }
	else
	   {
		 $li_numrows = $this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
			{
			 $lb_valido=true;                  
			 $this->io_sql->free_result($rs_data);	
			}
	   }
	if($lb_valido===false)
	{
		$ls_sql    = "SELECT codemp FROM sno_dt_scg WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codigo."'";	
		$rs_data   = $this->io_sql->select($ls_sql);
		if ($rs_data===false)
		   {
			  $this->is_msg_error="CLASE->SIGESP_RPC_C_PROVEEDOR; METODO->uf_select_proveedor_sno; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);	   		 
			  $lb_valido=false;
		   }
		else
		   {
			 $li_numrows = $this->io_sql->num_rows($rs_data);
			 if ($li_numrows>0)
				{
				 $lb_valido=true;                  
				 $this->io_sql->free_result($rs_data);	
				}
		   }
	}
return $lb_valido;
}

function uf_delete_proveedor($as_codemp,$as_codpro,$aa_seguridad)
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
   if ($this->uf_validardelete($as_codemp,$as_codpro))
      { 
	    if ($this->uf_delete_detalles($as_codemp,$as_codpro,$aa_seguridad))
		   { 
		     $ls_sql  = "DELETE FROM rpc_proveedor WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codpro."'";
			 $rs_data = $this->io_sql->execute($ls_sql);
			 if ($rs_data===false)
				{
				  $lb_valido = false;
				  $this->io_msg->message("CLASE->SIGESP_RPC_C_PROVEEDOR; METODO->uf_delete_proveedor; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				  echo $this->io_sql->message;
				}
		   }
		else
		   {
		     /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			 $ls_evento="DELETE";
			 $ls_descripcion ="Eliminó en RPC al Proveedor ".$as_codpro;
			 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
			 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
			 $aa_seguridad["ventanas"],$ls_descripcion);
			 /////////////////////////////////         SEGURIDAD               ///////////////////////////  		   
			 $lb_valido=true;
		   }
	  }
   else
	  {
	    $this->io_msg->message("El Proveedor no puede ser eliminado, posee registros asociados a otras tablas !!!");
        $lb_valido = false;
	  }		   			   
   return $lb_valido;
}	                         
    
function uf_delete_detalles($as_codemp,$as_codpro,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	Funcion      uf_delete_detalles
//	Access       public
//	Arguments    $as_codemp,$as_codpro
//	Returns	     lb_valido. Retorna una variable booleana
//	Description  Funcion que se encarga validar si se puede eliminar un Proveedor 
//               en la base de datos y retorna una variable booleana de que es valido 
//////////////////////////////////////////////////////////////////////////////

 $lb_valido = false;
 if ($this->uf_select_proveedor($as_codemp,$as_codpro))
    {
      $ls_sql  = " DELETE FROM rpc_docxprov WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codpro."'";
      $rs_data = $this->io_sql->execute($ls_sql);
      if ($rs_data===false)
	     { 
           $lb_valido = false;
		   $this->io_msg->message("CLASE->SIGESP_RPC_C_PROVEEDOR; METODO->validar_delete(rpc_docxprov); ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 }
 	  else
	     {
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
 		   $ls_evento="DELETE";
		   $ls_descripcion ="Eliminó Documentos del Proveedor en RPC"." ".$as_codpro;
		   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		   $aa_seguridad["ventanas"],$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               ///////////////////////////
		   $lb_valido = true;
		 }
      if ($lb_valido)
	     {
           $ls_sql = "DELETE FROM rpc_clasifxprov WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codpro."'";
           $rs_data= $this->io_sql->execute($ls_sql);
		   if ($rs_data===false)
	      	  {  
		        $lb_valido = false;
		        $this->io_msg->message("CLASE->SIGESP_RPC_C_PROVEEDOR; METODO->validar_delete(rpc_clasixprov); ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		      }
 		   else
		      {
		        /////////////////////////////////         SEGURIDAD               /////////////////////////////		
 		        $ls_evento="DELETE";
 		        $ls_descripcion ="Eliminó Calificaciones por Proveedor de la Tabla rpc_clasifxprov en RPC del Proveedor "." ".$as_codpro;
		        $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
				$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
				$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               ///////////////////////////
			    $lb_valido = true;
			  }
		 }	  	       
      if ($lb_valido)
	     {
			$ls_sql="DELETE ".
					"  FROM rpc_espexprov ".
					" WHERE codemp='".$as_codemp."'".
					"   AND cod_pro='".$as_codpro."'";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_msg->message("CLASE->SIGESP_RPC_C_PROVEEDOR MÉTODO->validar_delete ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó las Especialidades asociadas al provedor ".$as_codpro;
				$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		 
		 }
      if ($lb_valido)
	     {
			$ls_sql="DELETE FROM rpc_proveedorsocios WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codpro."'";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_msg->message("CLASE->SIGESP_RPC_C_PROVEEDOR MÉTODO->validar_delete ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó los Socios asociados al proveedor ".$as_codpro;
				$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		 
		 }
    }
return $lb_valido;
}

function uf_validardelete($as_codemp,$as_codpro)
{
//////////////////////////////////////////////////////////////////////////////
//	Funcion      uf_validardelete
//	Access       public
//	Arguments    $as_codemp,$as_codpro
//	Returns	     lb_valido. Retorna una variable booleana
//	Description  Funcion que se encarga validar si se puede eliminar un Proveedor 
//               en la base de datos y retorna una variable booleana de que es valido 
//////////////////////////////////////////////////////////////////////////////

 $lb_valido = false;
 if ($this->uf_select_proveedor($as_codemp,$as_codpro))
    {
        if (!$this->uf_select_proveedor_sep($as_codemp,$as_codpro))
		{
		       if (!$this->uf_select_proveedor_soc($as_codemp,$as_codpro))
			   {
					if (!$this->uf_select_proveedor_cxp($as_codemp,$as_codpro))
					{
						 if (!$this->uf_select_proveedor_scb($as_codemp,$as_codpro))
						 {
							 if (!$this->uf_select_proveedor_sno($as_codemp,$as_codpro))
							 {
									$lb_valido = true;
							 }
						 } 							 
					} 	
			   } 	
		 } 	
    }
return $lb_valido;
}
	
function uf_select_llenarcombo_banco($ls_codemp)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Funcion      uf_select_llenarcombo_banco
//	Access       public
//	Arguments    $ls_codemp
//	Returns	     rs_data. Retorna un resulset cargado con los bancos creados en la tabla scb_banco. 
//	Description  Devuelve un resulset con todos los bancos registrados para dicho 
//               codigo de empresa.
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$lb_valido = false;
	$ls_sql    = "SELECT * FROM scb_banco WHERE codemp='".$ls_codemp."' ORDER BY nomban ASC";
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		 $lb_valido = false;
		 $this->io_msg->message("CLASE->SIGESP_RPC_C_PROVEEDOR; METODO->uf_select_llenarcombo_banco; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	  {
	   	$li_numrows = $this->io_sql->num_rows($rs_data);	   
        if ($li_numrows>0)
		   {
		     $lb_valido = true;
		   }
	  }
return $rs_data;         
}

function uf_select_llenarcombo_tipoorganizacion($ls_codemp)
{
//////////////////////////////////////////////////////////////////////////////
//	Funcion       uf_select_llenarcombo_tipoorganizacion
//	Access        public
//	Arguments     $ls_codemp
//	Returns	      rs. Retorna una resulset
//	Description   Devuelve un resulset con todos los tipos de organización de la tabla rpc_tipo_organizacion.
//////////////////////////////////////////////////////////////////////////////

	$lb_valido = false;
	$ls_sql  = "SELECT * FROM rpc_tipo_organizacion ORDER BY dentipoorg ASC";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		 $lb_valido = false;
		 $this->io_msg->message("CLASE->SIGESP_RPC_C_PROVEEDOR; METODO->uf_select_llenarcombo_tipoorganizacion; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	  {
	   	$li_numrows = $this->io_sql->num_rows($rs_data);	   
        if ($li_numrows>0)
		   {
		     $lb_valido = true;
		   }
	  }
return $rs_data;         
}

function uf_select_llenarcombo_especialidad($ls_codemp)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Funcion       uf_select_llenarcombo_especialidad
//	Access        public
//	Arguments     $ls_codemp: Código de la Empresa.
//	Returns	      rs. Retorna una resulset
//	Description   Devuelve un resulset con todas las especialidades de la tabla rpc_especialidad.
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$lb_valido = false;
	$ls_sql  = "SELECT * FROM rpc_especialidad ORDER BY denesp ASC";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		 $lb_valido = false;
		 $this->io_msg->message("CLASE->SIGESP_RPC_C_PROVEEDOR; METODO->uf_select_llenarcombo_especialidad; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	  {
	   	$li_numrows = $this->io_sql->num_rows($rs_data);	   
        if ($li_numrows>0)
		   {
		     $lb_valido = true;
		   }
	  }
return $rs_data;         
}

function uf_select_llenarcombo_moneda($ls_codemp)
{
//////////////////////////////////////////////////////////////////////////////
//	Funcion      uf_select_llenarcombo_moneda
//	Access       public
//	Arguments    
//   $ls_codemp  Código de la empresa.
//	    Returns	 rs. Retorna una resulset con los tipos de moneda creadas.
//	Description  Devuelve un resulset con todas las monedas de la tabla sigesp_moneda.
//////////////////////////////////////////////////////////////////////////////

	$lb_valido = false;
	$ls_sql    = " SELECT * FROM sigesp_moneda ORDER BY denmon ASC";
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		 $lb_valido = false;
		 $this->io_msg->message("CLASE->SIGESP_RPC_C_PROVEEDOR; METODO->uf_select_llenarcombo_moneda; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	  {
	   	$li_numrows = $this->io_sql->num_rows($rs_data);	   
        if ($li_numrows>0)
		   {
		     $lb_valido = true;
		   }
	  }
return $rs_data;         
}

function uf_load_paises()
{
//////////////////////////////////////////////////////////////////////////////
//	Funcion      uf_load_paises
//	Access       public
//	Arguments  
//	Returns	     rs. Retorna una resulset
//	Description  Devuelve un resulset con todos los paises de la tabla sigesp_pais.*/	
//////////////////////////////////////////////////////////////////////////////

	$lb_valido = false;
	$ls_sql    = " SELECT codpai,despai FROM sigesp_pais WHERE codpai<>'---' ORDER BY despai ASC";
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido = false;
	     $this->io_msg->message("CLASE->SIGESP_RPC_C_PROVEEDOR; METODO->uf_load_paises; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	   {
	     $li_numrows = $this->io_sql->num_rows($rs_data);	   
	     if ($li_numrows>0)
	        {
	          $lb_valido=true;
	        }	
       }
return $rs_data;
}

function uf_load_estados($as_codpai)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	    Funcion  uf_load_estados
//	     Access  public
//	  Arguments    
//  $as_codpai:  Código del Pais.
//	    Returns	 rs_data. Retorna una resulset cargado con los estados creados para el pais que viene como parametro.
//	Description  Devuelve un resulset con todos los estados asociados a un pais en específico de la tabla sigesp_estados.	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$lb_valido = false;
	$ls_sql  = "SELECT * FROM sigesp_estados WHERE codpai='".$as_codpai."' ORDER BY desest ASC";
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido = false;
	     $this->io_msg->message("CLASE->SIGESP_RPC_C_PROVEEDOR; METODO->uf_load_estados; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	   {
	     $li_numrows = $this->io_sql->num_rows($rs_data);	   
	     if ($li_numrows>0)
	        {
	          $lb_valido=true;
	        }	
       }
return $rs_data;
}

function uf_load_municipios($as_codpai,$as_codest)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Funcion      uf_load_municipios
//	Access       public
//	Arguments    $as_codpai,$as_codest
//	Returns	     rs. Retorna una resulset
//	Description  Devuelve un resulset con todos los municipios asociados a 
//              un pais y un estado en específico de la tabla sigesp_municipio.	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$lb_valido = false;
	$ls_sql    = "SELECT * FROM sigesp_municipio WHERE codpai='".$as_codpai."' AND codest='".$as_codest."' ORDER BY denmun";
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido = false;
	     $this->io_msg->message("CLASE->SIGESP_RPC_C_PROVEEDOR; METODO->uf_load_municipios; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	   {
	     $li_numrows = $this->io_sql->num_rows($rs_data);	   
	     if ($li_numrows>0)
	        {
	          $lb_valido=true;
	        }	
       }
return $rs_data;
}

function uf_load_parroquias($as_codpai,$as_codest,$as_codmun)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Funcion       uf_load_parroquias
//	Access        public
//	Arguments     $as_codpai,$as_codest,$as_codmun
//	Returns	      rs.  Retorna una resulset
//	Description   Devuelve un resulset con todas las parroquias asociadas a un pais,
//                estado,municipio en específico de la tabla sigesp_parroquia.
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $lb_valido = false;
	$ls_sql    = "SELECT * FROM sigesp_parroquia ".
                 " WHERE codpai='".$as_codpai."' AND codest='".$as_codest."' AND codmun='".$as_codmun."'".
                 " ORDER BY denpar ASC";
	$rs_data   = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido = false;
	     $this->io_msg->message("CLASE->SIGESP_RPC_C_PROVEEDOR; METODO->uf_load_parroquias; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	   {
	     $li_numrows = $this->io_sql->num_rows($rs_data);	   
	     if ($li_numrows>0)
	        {
	          $lb_valido=true;
	        }	
       }
return $rs_data;
}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/// PARA LA CONVERSIÓN MONETARIA
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_rpcproveedor($ar_datos,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_rpcproveedor
		//		   Access: private
		//	    Arguments: ar_datos  // arreglo de datos
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_cod_pro= $ar_datos["codpro"];
		$li_capital=$ar_datos["capital"];
		$li_capital=str_replace('.','',$li_capital);
		$li_capital=str_replace(',','.',$li_capital);      
		$li_monmax=$ar_datos["monmax"];      
		$li_monmax=str_replace('.','',$li_monmax);
		$li_monmax=str_replace(',','.',$li_monmax);
		
		return $lb_valido;
	}// end function uf_convertir_rpcproveedor
	//-----------------------------------------------------------------------------------------------------------------------------
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_proveedor_cambioestatus($as_codprovdesde,$as_codprovhasta,$as_estprov,&$ao_object,&$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_proveedor_cambioestatus
		//		   Access: 
		//	    Arguments: as_codprov1  // Número de Solicitud
		//	   			   as_codprov2  // Fecha de Registro
		//				   ao_object  // Arreglo de objetos
		//				   ai_totrows  // Total de Filas
		//	      Returns: lb_valido True si se ejecuto la busqueda correctamente
		//	  Description: Método que obtiene los proveedores para cambiarles el estatus
		//	   Creado Por: Ing. Yesenia Moreno	
		// Modificado Por:                                        						Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totrows=0;
        $lb_valido=true;
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_criterio="";
		if(!empty($as_codprovdesde))
		{
			$ls_criterio=$ls_criterio." AND cod_pro >='".$as_codprovdesde."'";
		}
		if(!empty($as_codprovhasta))
		{
			$ls_criterio=$ls_criterio." AND cod_pro <='".$as_codprovhasta."'";
		}
		if($as_estprov!="")
		{
			$ls_criterio=$ls_criterio." AND estprov =".$as_estprov."";
		}
		$ls_sql="SELECT cod_pro, nompro ".
                "  FROM rpc_proveedor  ".
				" WHERE codemp = '".$ls_codemp."' ".
				"   AND cod_pro <> '----------' ".
				$ls_criterio.
				" ORDER BY cod_pro ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->proveedores MÉTODO->uf_load_proveedor_cambioestatus ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_cod_pro=rtrim($row["cod_pro"]);
				$ls_nompro=rtrim($row["nompro"]);
				
				$ao_object[$ai_totrows][1]="<input type=checkbox name=chksel".$ai_totrows." id=chksel".$ai_totrows." value=1 checked style=width:15px;height:15px >";		
				$ao_object[$ai_totrows][2]="<input type=text name=txtcodprov".$ai_totrows." id=txtcodprov".$ai_totrows." value='".$ls_cod_pro."' class=sin-borde readonly style=text-align:center size=11 maxlength=10>";
				$ao_object[$ai_totrows][3]="<input type=text name=txtnomprov".$ai_totrows." id=txtnomprov".$ai_totrows." value='".$ls_nompro."' class=sin-borde readonly style=text-align:left size=50 maxlength=100>";
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_proveedor_cambioestatus	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_proveedor_cambioestatus($as_codprov,$as_estprovnew,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_proveedor_cambioestatus
		//		   Access: 
		//	    Arguments: as_codprov  // Código de PRoveedor
		//	   			   as_estprovnew  // Estatus nuevo
		//				   aa_seguridad  // Arreglo de seguridad
		//	      Returns: lb_valido True si se ejecuto la busqueda correctamente
		//	  Description: Método que obtiene los proveedores para cambiarles el estatus
		//	   Creado Por: Ing. Yesenia Moreno	
		// Modificado Por:                                        						Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
  	    $ls_sql="UPDATE rpc_proveedor ".
				"   SET estprov=".$as_estprovnew." ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND cod_pro='".$as_codprov."' ";
		$li_rows=$this->io_sql->execute($ls_sql);
		if($li_rows===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->proveedores MÉTODO->uf_update_proveedor_cambioestatus ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
		        /////////////////////////////////         SEGURIDAD               /////////////////////////////		
 		        $ls_evento="UPDATE";
 		        $ls_descripcion ="Actualizo el estatus del proveedor ".$as_codprov." A ".$as_estprovnew;
		        $lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
																			$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               ///////////////////////////
		}
		return $lb_valido;
	}// end function uf_update_proveedor_cambioestatus	
	//-----------------------------------------------------------------------------------------------------------------------------------
  
	function uf_select_proveedor_ipsfa($as_codemp)
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Funcion       uf_select_proveedor
	//	Access        public
	//	Arguments     $as_codemp
	//	Returns	      lb_valido. Retorna una variable booleana
	//	Description   Busca un registro dentro de la tabla rpc_proveedor en la base de datos y retorna una variable booleana de que existe 
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sql    = " SELECT * FROM rpc_proveedor WHERE codemp='".$as_codemp."' AND cod_pro='".$as_codigo."'";
		$rs_data   = $this->io_sql->select($ls_sql);
		if ($rs_data===false)
		   {
			  $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));		 
			  $lb_valido=false;
		   }
		else
		   {
			 $li_numrows = $this->io_sql->num_rows($rs_data);
			 if ($li_numrows>0)
				{
				 $lb_valido=true;                  
				 $this->io_sql->free_result($rs_data);	
				}
		   }
	return $lb_valido;
	}
	
//------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------


	function uf_consultar_proveedores($as_codprov1,$as_codprov2,$as_nomprov,$as_rifprov,&$ai_totrows,&$ao_object)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Funcion       uf_consultar_proveedores
	//	Access        public
	//	Arguments	  ai_totrows  // total de filas del detalle
	//				  ao_object  // objetos del detalle
	//	Returns	      lb_valido. Retorna una variable booleana
	//	Description   Busca registros de proveedores dada una base de datos. 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_cadena="";
		
		if (($as_codprov1!="") && ($as_codprov2!=""))
		{
		  $ls_cadena=$ls_cadena."AND cod_pro BETWEEN '".$as_codprov1."' AND '".$as_codprov2."' ";
		}
		
		if ($as_nomprov!="")
		{
		  $ls_cadena=$ls_cadena."AND nompro LIKE '".'%'.$as_nomprov.'%'."' ";
		}
		
		if ($as_rifprov!="")
		{
		  $ls_cadena=$ls_cadena."AND rifpro LIKE '".'%'.$as_rifprov.'%'."' ";
		}
		
		$lb_valido=true;
		$ls_sql="SELECT rpc_proveedor.cod_pro, rpc_proveedor.nompro, rpc_proveedor.rifpro  ". 
				"  FROM rpc_proveedor ".	
				" WHERE cod_pro <> '----------' ".$ls_cadena.			
				" ORDER BY rpc_proveedor.cod_pro ";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->proveedores MÉTODO->uf_consultar_proveedores ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
		 $num=$this->io_sql->num_rows($rs_data);
           
		  if ($num!=0) {
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				
				$ls_codpro= trim ($row["cod_pro"]);
				$ls_nompro= trim (htmlentities($row["nompro"]));
				$ls_rifpro= trim ($row["rifpro"]);
				$ai_totrows++;
				$ao_object[$ai_totrows][1]="<input name=txtcodprov".$ai_totrows." type=text id=txtcodprov".$ai_totrows." class=sin-borde size=12 maxlength=10 value='".$ls_codpro."' readonly >";
				$ao_object[$ai_totrows][2]="<input name=txtdenprov".$ai_totrows." type=text id=txtdenprov".$ai_totrows." maxlength=100 class=sin-borde size=60 value='".$ls_nompro."' readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtrifprov".$ai_totrows." type=text id=txtrifprov".$ai_totrows." class=sin-borde size=17 maxlength=15 value='".$ls_rifpro."'  readonly>";
				$ao_object[$ai_totrows][4]="<input type=checkbox name=selprov".$ai_totrows." id=selprov".$ai_totrows." onChange='javascript: cambiar_valor(".$ai_totrows.");'><input name=txtselprov".$ai_totrows." type=hidden id=txtselprov".$ai_totrows." readonly>";			
			   	
							
			}
			$this->io_sql->free_result($rs_data);
			}
		else 
		 {
		    $this->io_msg->message("No hay proveedores en la base de datos seleccionada.");
	 		$ai_totrows=1;
			$ao_object[$ai_totrows][1]="<input name=txtcodprov".$ai_totrows." type=text id=txtcodprov".$ai_totrows." class=sin-borde size=12 maxlength=10  readonly >";
			$ao_object[$ai_totrows][2]="<input name=txtdenprov".$ai_totrows." type=text id=txtdenprov".$ai_totrows." maxlength=100 class=sin-borde size=60  readonly>";
			$ao_object[$ai_totrows][3]="<input name=txtrifprov".$ai_totrows." type=text id=txtrifprov".$ai_totrows." class=sin-borde size=17 maxlength=15   readonly>";
			$ao_object[$ai_totrows][4]="<input type=checkbox name=selprov".$ai_totrows." id=selprov".$ai_totrows." onChange='javascript: cambiar_valor(".$ai_totrows.");'><input name=txtselprov".$ai_totrows." type=hidden id=txtselprov".$ai_totrows." readonly>";	
			
		
		  }
		  return $lb_valido;
		}
		
	}
	
//------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------

	function uf_select_proveedores_bd ($as_codprov, $as_hostname, $as_login, $as_password,$as_database,$as_gestor)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Funcion       uf_select_proveedores_bd
	//	Access        public
	//	Arguments	  $as_codprov   // código del proveedor
	//                $as_hostname  // hostname para conectar con la Base de Datos
	//                $as_login     // login para conectar con la Base de Datos
	//                $as_password  // password o clave para conectac con la Base de Datos
	///               $as_database  // nombre de la Base Datos con la que se quiere conectar
	//                $as_gestor    // nombre del gestor que maneja la Base de Datos
	//	Returns	      lb_valido. Retorna una variable booleana
	//	Description   Busca registros de proveedores dada una base de datos. 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_conect=new sigesp_include();
		$io_conexion_destino   = $io_conect->uf_conectar_otra_bd ($as_hostname, $as_login, $as_password,$as_database,$as_gestor);
		$io_sql_destino        = new class_sql($io_conexion_destino);
		
		$lb_valido=true;
		$ls_sql="SELECT rpc_proveedor.cod_pro ". 
				"  FROM rpc_proveedor ".	
				" WHERE cod_pro = '$as_codprov' ".			
				" ORDER BY rpc_proveedor.cod_pro ";
			
		$rs_data   = $io_sql_destino->select($ls_sql);
		if ($rs_data===false)
		   {
			  $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));		 
			  $lb_valido=false;
		   }
		else
		   {
			 $li_numrows = $this->io_sql->num_rows($rs_data);
			 if ($li_numrows>0)
				{
				 $lb_valido=true;                  
				 $this->io_sql->free_result($rs_data);	
				}
			else 
				{
					$lb_valido=false;
				}
		   }
	return $lb_valido;
	}

//------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------

	function uf_transferir_proveedores($codpro,$as_hostname, $as_login, $as_password,$as_database,$as_gestor)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Funcion        uf_transferir_proveedores
	//	Access        public
	//	Arguments	  ai_totrows  // total de filas del detalle
	//				  ao_object  // objetos del detalle
	//	Returns	      lb_valido. Retorna una variable booleana
	//	Description   Busca registros de proveedores dada una base de datos. 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe=false;
		require_once("../shared/class_folder/sigesp_include.php");
		$io_conect=new sigesp_include();
		$io_conexion_destino   = $io_conect->uf_conectar_otra_bd ($as_hostname, $as_login, $as_password,$as_database,$as_gestor);
		$io_sql_destino = new class_sql($io_conexion_destino);
		$this->io_sql->begin_transaction(); 
		$lb_existe=$this->uf_select_proveedores_bd ($codpro,$as_hostname, $as_login, $as_password,$as_database,$as_gestor);
		
		if (!$lb_existe)
		{
			$lb_valido= true;
			$li_total_select= 0;
			$li_total_insert= 0;
			$ls_sql="SELECT codemp, cod_pro, nompro, dirpro, telpro, faxpro, nacpro, rifpro, nitpro, fecreg, capital, sc_cuenta,".
				"       obspro, estpro, estcon, estaso, ocei_fec_reg, ocei_no_reg, monmax, cedrep, nomreppro, emailrep, carrep,".
				"       registro, nro_reg, tomo_reg, folreg, fecregmod, regmod, nummod, tommod, folmod, inspector, foto,".
				"       codbansig, codban, codmon, codtipoorg, codesp, ctaban, numlic, fecvenrnc, numregsso, fecvensso,".
				"       numregince, fecvenince, estprov, pagweb, email, codpai, codest, codmun, codpar, graemp, tipconpro, tipperpro ".
				"  FROM rpc_proveedor ".
				"  WHERE cod_pro = '$codpro' ";
				
			
			$io_recordset= $this->io_sql->select($ls_sql);
			if($io_recordset===false)
			{ 
				$lb_valido=false;
				$this->io_msg->message("Error al seleccionar Proveedor de la Base de Datos.");
			}
			else
			{   $li_total_select = $this->io_sql->num_rows($io_recordset);
			while($row=$this->io_sql->fetch_row($io_recordset))
			{
				$ls_codemp= $this->io_validacion->uf_valida_texto($row["codemp"],0,4,"");
				$ls_cod_pro= $this->io_validacion->uf_valida_texto($row["cod_pro"],0,10,"");
				$ls_nompro= $this->io_validacion->uf_valida_texto($row["nompro"],0,100,"");
				$ls_dirpro= $this->io_validacion->uf_valida_texto($row["dirpro"],0,254,"");
				$ls_telpro= $this->io_validacion->uf_valida_texto($row["telpro"],0,50,"");
				$ls_faxpro= $this->io_validacion->uf_valida_texto($row["faxpro"],0,30,"");
				$ls_nacpro= $this->io_validacion->uf_valida_texto($row["nacpro"],0,1,"");
				$ls_rifpro= $this->io_validacion->uf_valida_texto($row["rifpro"],0,15,"");
				$ls_nitpro= $this->io_validacion->uf_valida_texto($row["nitpro"],0,15,"");
				$ld_fecreg= $this->io_validacion->uf_valida_fecha($row["fecreg"],"1900-01-01");
				$li_capital= $row["capital"];
				$ls_sc_cuenta= $this->io_validacion->uf_valida_texto($row["sc_cuenta"],0,25,"");
				$ls_obspro= $this->io_validacion->uf_valida_texto($row["obspro"],0,8000,"");
				$ls_estpro= $this->io_validacion->uf_valida_monto($row["estpro"],0);
				$ls_estcon= $this->io_validacion->uf_valida_monto($row["estcon"],0); 
				$ls_estaso= $this->io_validacion->uf_valida_monto($row["estaso"],0);
				$ld_ocei_fec_reg= $this->io_validacion->uf_valida_fecha($row["ocei_fec_reg"],"1900-01-01");
				$ls_ocei_no_reg= $this->io_validacion->uf_valida_texto($row["ocei_no_reg"],0,17,"");
				$li_monmax= $row["monmax"];
				$ls_cedrep= $this->io_validacion->uf_valida_texto($row["cedrep"],0,10,"");
				$ls_nomreppro= $this->io_validacion->uf_valida_texto($row["nomreppro"],0,50,"");
				$ls_emailrep= $this->io_validacion->uf_valida_texto($row["emailrep"],0,100,"");
				$ls_carrep= $this->io_validacion->uf_valida_texto($row["carrep"],0,35,"");
				$ls_registro= $this->io_validacion->uf_valida_texto($row["registro"],0,35,"");
				$ls_nro_reg= $this->io_validacion->uf_valida_texto($row["nro_reg"],0,15,"");
				$ls_tomo_reg= $this->io_validacion->uf_valida_texto($row["tomo_reg"],0,5,"");
				$ls_folreg= $this->io_validacion->uf_valida_texto($row["folreg"],0,5,"");
				$ld_fecregmod= $this->io_validacion->uf_valida_fecha($row["fecregmod"],"1900-01-01");
				$ls_regmod= $this->io_validacion->uf_valida_texto($row["regmod"],0,35,"");
				$ls_nummod= $this->io_validacion->uf_valida_texto($row["nummod"],0,15,"");
				$ls_tommod= $this->io_validacion->uf_valida_texto($row["tommod"],0,5,"");
				$ls_folmod= $this->io_validacion->uf_valida_texto($row["folmod"],0,5,"");
				$ls_inspector= $this->io_validacion->uf_valida_monto($row["estprov"],0);
				$ls_foto= $row["foto"];
				$ls_codbansig= $this->io_validacion->uf_valida_texto($row["codbansig"],0,3,"");
				$ls_codban= $this->io_validacion->uf_valida_texto($row["codban"],0,3,"");
				$ls_codmon= $this->io_validacion->uf_valida_texto($row["codmon"],0,3,"");
				$ls_codtipoorg= $this->io_validacion->uf_valida_texto($row["codtipoorg"],0,2,"--");
				$ls_codesp= $this->io_validacion->uf_valida_texto($row["codesp"],0,3,"");
				$ls_ctaban= $this->io_validacion->uf_valida_texto($row["ctaban"],0,25,"");
				$ls_numlic= $this->io_validacion->uf_valida_texto($row["numlic"],0,25,"");
				$ld_fecvenrnc= $this->io_validacion->uf_valida_fecha($row["fecvenrnc"],"1900-01-01");
				$ls_numregsso= $this->io_validacion->uf_valida_texto($row["numregsso"],0,15,"");
				$ld_fecvensso= $this->io_validacion->uf_valida_fecha($row["fecvensso"],"1900-01-01");
				$ls_numregince= $this->io_validacion->uf_valida_texto($row["numregince"],0,15,"");
				$ld_fecvenince= $this->io_validacion->uf_valida_fecha($row["fecvenince"],"1900-01-01");
				$ls_estprov= $this->io_validacion->uf_valida_monto($row["estprov"],0);
				$ls_pagweb= $this->io_validacion->uf_valida_texto($row["pagweb"],0,200,"");
				$ls_email= $this->io_validacion->uf_valida_texto($row["email"],0,200,"");
				$ls_codpai= $this->io_validacion->uf_valida_texto($row["codpai"],0,3,"---");
				$ls_codest= $this->io_validacion->uf_valida_texto($row["codest"],0,3,"---");
				$ls_codmun= $this->io_validacion->uf_valida_texto($row["codmun"],0,3,"---");
				$ls_codpar= $this->io_validacion->uf_valida_texto($row["codpar"],0,3,"---");
				if($ls_codpai=="---")
				{
					$ls_codest= "---";
					$ls_codmun= "---";
					$ls_codpar= "---";
				}
				if($ls_codest=="---")
				{
					$ls_codmun= "---";
					$ls_codpar= "---";
				}
				if($ls_codmun=="---")
				{
					$ls_codpar= "---";
				}
				
				$ls_graemp= $this->io_validacion->uf_valida_texto($row["graemp"],0,4,"");
				$ls_tipconpro= $this->io_validacion->uf_valida_texto($row["tipconpro"],0,1,"");
				$ls_tipperpro= $this->io_validacion->uf_valida_texto($row["tipperpro"],0,1,"");
				
				if($ls_codtipoorg!="")
				{
								
					$ls_sql="INSERT INTO rpc_proveedor(codemp, cod_pro, nompro, dirpro, telpro, faxpro, nacpro, rifpro, nitpro,".
							"                          fecreg, capital, sc_cuenta, obspro, estpro, estcon, estaso, ocei_fec_reg,".
							"                          ocei_no_reg, monmax, cedrep, nomreppro, emailrep, carrep, registro,".
							"                          nro_reg, tomo_reg, folreg, fecregmod, regmod, nummod, tommod, folmod,".
							"                          inspector, foto, codbansig, codban, codmon, codtipoorg, codesp, ctaban,".
							"                          numlic, fecvenrnc, numregsso, fecvensso, numregince, fecvenince, estprov,".
							"                          pagweb, email, codpai, codest, codmun, codpar, graemp, tipconpro,tipperpro)".
							"	  VALUES ('".$ls_codemp."','".$ls_cod_pro."','".$ls_nompro."','".$ls_dirpro."','".$ls_telpro."',".
							"             '".$ls_faxpro."','".$ls_nacpro."','".$ls_rifpro."','".$ls_nitpro."','".$ld_fecreg."',".
							"             ".$li_capital.",'".$ls_sc_cuenta."','".$ls_obspro."','".$ls_estpro."',".
							"             '".$ls_estcon."','".$ls_estaso."','".$ld_ocei_fec_reg."','".$ls_ocei_no_reg."',".
							"             ".$li_monmax.",'".$ls_cedrep."','".$ls_nomreppro."','".$ls_emailrep."','".$ls_carrep."',".
							"             '".$ls_registro."','".$ls_nro_reg."','".$ls_tomo_reg."','".$ls_folreg."',".
							"             '".$ld_fecregmod."','".$ls_regmod."','".$ls_nummod."','".$ls_tommod."','".$ls_folmod."',".
							"             '".$ls_inspector."','".$ls_foto."','".$ls_codbansig."','".$ls_codban."','".$ls_codmon."',".
							"             '".$ls_codtipoorg."','".$ls_codesp."','".$ls_ctaban."','".$ls_numlic."','".$ld_fecvenrnc."',".
							"             '".$ls_numregsso."','".$ld_fecvensso."','".$ls_numregince."','".$ld_fecvenince."',".
							"             '".$ls_estprov."','".$ls_pagweb."','".$ls_email."','".$ls_codpai."','".$ls_codest."',".
							"             '".$ls_codmun."','".$ls_codpar."','".$ls_graemp."','".$ls_tipconpro."','".$ls_tipperpro."')";
							
					$li_row=$io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$io_sql_destino->rollback();
						$this->io_msg->message("Error al insertar Proveedor  ".$codpro);
					}
					else
					{ 
						$io_sql_destino->commit();
						$li_total_insert++;
					}
				}
				else
				{
					   $io_sql_destino->rollback();
					   $this->io_msg->message("Hay data inconsistente en los Proveedores");
				}
			}
			
		}	
	}	
	return $lb_valido;
}

//------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------



}//Fin de la Clase.
?>