<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
$li_diasem = date('w');
switch ($li_diasem){
  case '0': $ls_diasem='Domingo';
  break; 
  case '1': $ls_diasem='Lunes';
  break;
  case '2': $ls_diasem='Martes';
  break;
  case '3': $ls_diasem='Mi&eacute;rcoles';
  break;
  case '4': $ls_diasem='Jueves';
  break;
  case '5': $ls_diasem='Viernes';
  break;
  case '6': $ls_diasem='S&aacute;bado';
  break;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Registro de Proveedores</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_rpc.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="css/rpc.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=">
<!-- Copyright 2000,2001 Macromedia, Inc. All rights reserved. -->
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
.Estilo2 {
	font-size: 12px;
	font-weight: bold;
}
-->
</style></head>
<body>
<span class="toolbar"><a name="top"></a></span>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
			
          <td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Proveedores y Beneficiarios</td>
			<td width="349" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequeñas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  	  <tr>
	  	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	    <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>

      </table></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" title="Buscar" width="20" height="20" border="0"></a><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" title="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></td>
  </tr>
</table>
<?php
require_once("class_folder/sigesp_rpc_c_proveedor.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_funciones_db.php"); 
require_once("../shared/class_folder/class_fecha.php");

$io_proveedor = new sigesp_rpc_c_proveedor();
$io_conect    = new sigesp_include();
$conn         = $io_conect->uf_conectar ();
$la_emp       = $_SESSION["la_empresa"];
$io_msg       = new class_mensajes(); //Instanciando la clase mensajes 
$io_sql       = new class_sql($conn); //Instanciando  la clase sql
$io_dspro     = new class_datastore(); //Instanciando la clase datastore
$io_funcion   = new class_funciones(); //Instanciando la clase datastore
$io_funciondb = new class_funciones_db($conn);
$io_fecha    = new class_fecha();


//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre        = $_SESSION["la_empresa"];
	$ls_empresa  = $arre["codemp"];
	$ls_logusr   = $_SESSION["la_logusr"];
	$ls_sistema  = "RPC";
	$ls_ventanas = "sigesp_rpc_d_proveedor.php";
    $ls_conrecdoc=$_SESSION["la_empresa"]["conrecdoc"];

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
		}
		else
		{
			$ls_permisos             =$_POST["permisos"];
			$la_accesos["leer"]     =$_POST["leer"];			
			$la_accesos["incluir"]  =$_POST["incluir"];			
			$la_accesos["cambiar"]  =$_POST["cambiar"];
			$la_accesos["eliminar"] =$_POST["eliminar"];
			$la_accesos["imprimir"] =$_POST["imprimir"];
			$la_accesos["anular"]   =$_POST["anular"];
			$la_accesos["ejecutar"] =$_POST["ejecutar"];
		}
	}
	else
	{
	    $la_accesos["leer"]="";		
		$la_accesos["incluir"]="";
		$la_accesos["cambiar"]="";
		$la_accesos["eliminar"]="";
		$la_accesos["imprimir"]="";
		$la_accesos["anular"]="";
		$la_accesos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
$ls_codemp   = $ls_empresa;
$ls_disabled = 'disabled';
if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion       = $_POST["operacion"];
     $ls_codigo          = $_POST["txtcodigo"];
	 $ls_nombre          = $_POST["txtnombre"]; 
	 $ls_direccion       = $_POST["txtdireccion"];
	 $ls_tiporg          = $_POST["cmbtiporg"];
	 $ls_telefono        = $_POST["txttelefono"];
	 $ls_fax             = $_POST["txtfax"];
	 $ls_nacionalidad    = $_POST["cmbnacionalidad"];
	 $ls_especialidad    = $_POST["cmbespecialidad"];
	 $ls_tipconpro       = $_POST["cmbcontribuyente"];
	 $ls_tipperpro       = $_POST["cmbtipopersona"];
	 $lr_datos["codpro"] = $ls_codigo;
     $lr_datos["nompro"] = $ls_nombre;
     $lr_datos["dirpro"] = $ls_direccion;
	 $lr_datos["tiporg"] = $ls_tiporg;
	 $lr_datos["telpro"] = $ls_telefono;
     $lr_datos["faxpro"] = $ls_fax;
     $lr_datos["nacpro"] = $ls_nacionalidad; 
     $lr_datos["esppro"] = $ls_especialidad;
	 $lr_datos["tipconpro"] = $ls_tipconpro;
	 $lr_datos["tipperpro"] = $ls_tipperpro;
   }
else
   {
      $ls_operacion = "NUEVO";
	  $ls_codigo    = "";
	  $ls_nombre="";
      $ls_direccion="";
      $ls_tiporg="00";
      $ls_telefono="";
	  $ls_fax="";
	  $ls_nacionalidad="";
	  $ls_especialidad="---";
	  $lr_datos["especialidad"]=$ls_especialidad; 
	  $ls_tipconpro = '';
	  $ls_tipperpro = '-';
	  $lr_datos["tipconpro"] = $ls_tipconpro;
	  $lr_datos["tipperpro"] = $ls_tipperpro;
	}	
if  (array_key_exists("cmbtipperrif",$_POST))
	{
	  $ls_tipperrif          = $_POST["cmbtipperrif"];
      $lr_datos["tipperrif"] = $ls_tipperrif;
    }
else
	{
	  $ls_tipperrif = "J";	  
	}
$ls_seljur = $ls_selgub = $ls_selven = $ls_selext = "";
if ($ls_tipperrif=='J')
   {
     $ls_seljur = "selected";
   }	
elseif($ls_tipperrif=='G')
   {
     $ls_selgub = "selected";
   }
elseif($ls_tipperrif=='V')
   {
     $ls_selven = "selected";
   }
else
   {
     $ls_selext = "selected";
   }

if  (array_key_exists("txtnumpririf",$_POST))
	{
	  $ls_numpririf    = $_POST["txtnumpririf"];
	  $lr_datos["numpririf"] = $ls_numpririf;
    }
else
	{
	  $ls_numpririf = ""; 
	}
if  (array_key_exists("txtnumterrif",$_POST))
	{
	  $ls_numterrif    = $_POST["txtnumterrif"];
	  $lr_datos["numterrif"] = $ls_numterrif;
    }
else
	{
	  $ls_numterrif = ""; 
	}
	
if  (array_key_exists("txtnit",$_POST))
	{
 	  $ls_nit=$_POST["txtnit"];
	  $lr_datos["nit"]=$ls_nit;
    }
else
	{
	  $ls_nit="";
	}	
if  (array_key_exists("txtcapital",$_POST))
	{
	  $ld_capital=$_POST["txtcapital"];
	  $lr_datos["capital"]=$ld_capital;
    }
else
	{
	  $ld_capital="";
	}
if  (array_key_exists("txtmonmax",$_POST))
	{
      $ld_monmax=$_POST["txtmonmax"];
      $lr_datos["monmax"]=$ld_monmax;
    }
else
    {
	  $ld_monmax=""; 
	}
if  (array_key_exists("cmbbanco",$_POST))
	{
	  $ls_banco=$_POST["cmbbanco"];
      $lr_datos["banco"]=$ls_banco;
    }
else
	{
	  $ls_banco="---";
	  $lr_datos["banco"]=$ls_banco;
	}	
if  (array_key_exists("txtcuenta",$_POST))
	{
	  $ls_cuenta=$_POST["txtcuenta"]; 
      $lr_datos["cuenta"]=$ls_cuenta;
	}
else
    {
	  $ls_cuenta="";
    }
if  (array_key_exists("txtdencuenta",$_POST))
	{
  	  $ls_denocuenta=$_POST["txtdencuenta"];
    }
else
    {
	  $ls_denocuenta="";
    }	  
if  (array_key_exists("txtdenocuentarecdoc",$_POST))
	{
  	  $ls_denocuentarecdoc=$_POST["txtdenocuentarecdoc"];
    }
else
    {
	  $ls_denocuentarecdoc="";
    }	  
if  (array_key_exists("cmbmoneda",$_POST))
	{
	  $ls_moneda=$_POST["cmbmoneda"];
	  $lr_datos["moneda"]=$ls_moneda;
    }
else
	{
	  $ls_moneda="000";
  	  $lr_datos["moneda"]=$ls_moneda;
	}	
//Clase de Ubicación Geográfica

if	(array_key_exists("cmbpais",$_POST))
	{
	$ls_pais=$_POST["cmbpais"];
	$lr_datos["pais"]=$ls_pais;
	}
else
	{
	  $ls_pais="---";
	  $lr_datos["pais"]=$ls_pais;
	}
	
if 	(array_key_exists("cmbestado",$_POST))
	{
	  $ls_estado=$_POST["cmbestado"];
	  $lr_datos["estado"]=$ls_estado;
	}
else
	{
	  $ls_estado="---";	
	  $lr_datos["estado"]=$ls_estado;
	}
	
if	(array_key_exists("cmbmunicipio",$_POST))
	{
	$ls_municipio=$_POST["cmbmunicipio"];
	$lr_datos["municipio"]=$ls_municipio;
	}
else
	{
	  $ls_municipio="---";
   	  $lr_datos["municipio"]=$ls_municipio;
	}
	
if	(array_key_exists("cmbparroquia",$_POST))
	{
	  $ls_parroquia=$_POST["cmbparroquia"];
	  $lr_datos["parroquia"]=$ls_parroquia;
	}
else
	{
	  $ls_parroquia="---";	
  	  $lr_datos["parroquia"]=$ls_parroquia;
	}
//Fin de Ubicación Geográfica
if  (array_key_exists("txtcontable",$_POST))
	{
  	  $ls_contable=$_POST["txtcontable"];
	  $lr_datos["contable"]=$ls_contable;
    }
else
	{
	  $ls_contable="";
	}	
if  (array_key_exists("txtcontablerecdoc",$_POST))
	{
  	  $ls_contablerecdoc=$_POST["txtcontablerecdoc"];
	  $lr_datos["contablerecdoc"]=$ls_contablerecdoc;
    }
else
	{
	  $ls_contablerecdoc="";
	}	

if  (array_key_exists("txtctaant",$_POST))
	{
  	  $ls_ctaant=$_POST["txtctaant"];
	  $lr_datos["ctaant"]=$ls_ctaant;
    }
else
	{
	  $ls_ctaant="";
	}	

if  (array_key_exists("txtdenctaant",$_POST))
	{
  	  $ls_denctaant=$_POST["txtdenctaant"];
    }
else
	{
	  $ls_denctaant="";
	}	



if  (array_key_exists("txtobservacion",$_POST))
	{
	  $ls_observacion=$_POST["txtobservacion"];
	  $lr_datos["observacion"]=$ls_observacion;
    }
else
	{
	  $ls_observacion="";
	}	

/*Datos del Registro*/
if  (array_key_exists("txtcedula",$_POST))
	{
	  $ls_cedula=$_POST["txtcedula"];
	  $lr_datos["cedula"]=$ls_cedula;
    }
else
	{
	  $ls_cedula="";
	}	

if  (array_key_exists("txtnomrep",$_POST))
	{
	 $ls_nomrep=$_POST["txtnomrep"];
	 $lr_datos["nomrep"]=$ls_nomrep;
    }
else
	{
	  $ls_nomrep="";
	}	
if  (array_key_exists("txtcargo",$_POST))
	{
	 $ls_cargo=$_POST["txtcargo"];
	 $lr_datos["cargo"]=$ls_cargo;
    }
else
	{
	  $ls_cargo="";
	}	

if  (array_key_exists("txtnumregRNC",$_POST))
	{
	  $ls_numregRNC=$_POST["txtnumregRNC"];
	  $lr_datos["numregrnc"]=$ls_numregRNC;
    }
else
	{
	  $ls_numregRNC="";
	}	

if  (array_key_exists("txtregistro",$_POST))
	{
	  $ls_registro=$_POST["txtregistro"];
	  $lr_datos["registro"]=$ls_registro;
    }
else
	{
	  $ls_registro="";
	}	

if  (array_key_exists("txtfecreg",$_POST))
	{
	  $ls_fecreg=$_POST["txtfecreg"];
	  $ls_fecregistro=$io_funcion->uf_convertirdatetobd($ls_fecreg);
      $lr_datos["fecreg"]=$ls_fecregistro;
    }
else
	{
	  $ls_fecreg="";
	}	
if  (array_key_exists("txtnumero",$_POST))
	{
	  $ls_numero=$_POST["txtnumero"];
	  $lr_datos["numero"]=$ls_numero;
    }
else
	{
	  $ls_numero="";
	}	
if  (array_key_exists("txttomo",$_POST))
	{
	  $ls_tomo=$_POST["txttomo"];
	  $lr_datos["tomo"]=$ls_tomo;
    }
else
	{
	  $ls_tomo="";
	}	
if  (array_key_exists("txtfecregRNC",$_POST))
	{
	  $ls_fecregRNC=$_POST["txtfecregRNC"];
	  $ls_fecregRNContrataciones=$io_funcion->uf_convertirdatetobd($ls_fecregRNC);
 	  $lr_datos["fecregrnc"]=$ls_fecregRNContrataciones;
    }
else
	{
	  $ls_fecregRNC="";
	}	

if  (array_key_exists("txtfecregmod",$_POST))
	{
	  $ls_fecregmod=$_POST["txtfecregmod"];
	  $ls_fecregmodificado=$io_funcion->uf_convertirdatetobd($ls_fecregmod);
	  $lr_datos["fecregmod"]=$ls_fecregmodificado;
    }
else
	{
	  $ls_fecregmod="";
	}	
if  (array_key_exists("txtregmod",$_POST))
	{
	  $ls_regmod=$_POST["txtregmod"];
	  $lr_datos["regmod"]=$ls_regmod;
    }
else
	{
	  $ls_regmod="";
	}	
if  (array_key_exists("txtnummod",$_POST))
	{
	  $ls_nummod=$_POST["txtnummod"];
	  $lr_datos["nummod"]=$ls_nummod;
    }
else
	{
	  $ls_nummod="";
	}	
if  (array_key_exists("txttommod",$_POST))
	{
	  $ls_tommod=$_POST["txttommod"];
	  $lr_datos["tommod"]=$ls_tommod;
    }
else
	{
	  $ls_tommod="";
	}	
if  (array_key_exists("txtnumfol",$_POST))
	{
	  $ls_numfol=$_POST["txtnumfol"];
	  $lr_datos["numfol"]=$ls_numfol;
    }
else
	{
	  $ls_numfol="";
	}	
if  (array_key_exists("txtnumfolmod",$_POST))
	{
	  $ls_numfolmod=$_POST["txtnumfolmod"];
	  $lr_datos["numfolmod"]=$ls_numfolmod;
    }
else
	{
	  $ls_numfolmod="";
	}	
if  (array_key_exists("txtnumlic",$_POST))
	{
	  $ls_numlic=$_POST["txtnumlic"];
	  $lr_datos["numlic"]=$ls_numlic;
    }
else
	{
	  $ls_numlic="";
	}	
//Check
if  (array_key_exists("cbinspector",$_POST))
	{
 	  $ls_inspector=$_POST["cbinspector"];
	  $lr_datos["inspector"]=$ls_inspector;
    }
else
	{
	  $ls_inspector=0;
	  $lr_datos["inspector"]=$ls_inspector;
	}	
//Checked
if  (array_key_exists("chkestpro",$_POST))
	{
	  $ls_proveedor=$_POST["chkestpro"];
	  $lr_datos["estpro"]=$ls_proveedor;
    }
else
	{
	  $ls_proveedor="0";
	  $lr_datos["estpro"]=$ls_proveedor;
	}	
//Checked
if  (array_key_exists("chkestcon",$_POST))
	{
	  $ls_contratista=$_POST["chkestcon"];
	  $lr_datos["estcon"]=$ls_contratista;
    }
else
	{
	  $ls_contratista="0";
	  $lr_datos["estcon"]=$ls_contratista;
	}	

//CAMPOS NUEVOS
if  (array_key_exists("txtpagweb",$_POST))
	{
	  $ls_pagweb=$_POST["txtpagweb"];
	  $lr_datos["pagweb"]=$ls_pagweb;
    }
else   
	{
	  $ls_pagweb="";
	}	
if  (array_key_exists("txtemail",$_POST))
	{
	  $ls_email=$_POST["txtemail"];
	  $lr_datos["email"]=$ls_email;
    }
else
	{
	  $ls_email="";
	}	
if  (array_key_exists("estprov",$_POST))
	{
	  $ls_estprov=$_POST["estprov"];
	  $lr_datos["estatus"]=$ls_estprov;
    }
else
	{
	  $ls_estprov="";
	}	
if  (array_key_exists("txtfecvenRNC",$_POST))
	{
	  $ls_fecvenRNC=$_POST["txtfecvenRNC"];
	  $ls_fecvencimiento=$io_funcion->uf_convertirdatetobd($ls_fecvenRNC);
		$ld_hoy=date('Y')."-".date('m')."-".date('d');
		if($io_fecha->uf_comparar_fecha($ld_hoy,$ls_fecvencimiento))
		{
			$ls_registronacional="VIGENTE";
		}
		else
		{
			$ls_registronacional="VENCIDO";
		}
	  
	  $lr_datos["fecvenrnc"]=$ls_fecvencimiento;
    }
else
	{
	  $ls_fecvenRNC="";
	  $ls_registronacional="";
	}	
if  (array_key_exists("txtregSSO",$_POST))
	{

	  $ls_regSSO=$_POST["txtregSSO"];
	  $lr_datos["regsso"]=$ls_regSSO;
    }
else
	{
	  $ls_regSSO="";
	}	
if  (array_key_exists("txtfecvenSSO",$_POST))
	{
	  $ls_fecvenSSO=$_POST["txtfecvenSSO"];
	  $ls_fecvenSeguro=$io_funcion->uf_convertirdatetobd($ls_fecvenSSO);
	  $lr_datos["fecvensso"]=$ls_fecvenSeguro;
    }
else
	{
	  $ls_fecvenSSO="";
	}	
if  (array_key_exists("txtregINCE",$_POST))
	{
 	  $ls_regINCE=$_POST["txtregINCE"];
	  $lr_datos["regince"]=$ls_regINCE;
    }
else
	{
	  $ls_regINCE="";
	}	
if  (array_key_exists("txtfecvenINCE",$_POST))
	{
	  $ls_fecvenINCE=$_POST["txtfecvenINCE"];
	  $ls_fecINCE=$io_funcion->uf_convertirdatetobd($ls_fecvenINCE);
	  $lr_datos["fecvenince"]=$ls_fecINCE;
    }
else
	{
	  $ls_fecvenINCE="";
	}
//****************************************
if  (array_key_exists("cmbgraemp",$_POST))
	{
	  $ls_graemp=$_POST["cmbgraemp"];
	  $lr_datos["graemp"]=$ls_graemp;
    } 
else
    {
     $ls_graemp="";
    }	

if  (array_key_exists("txtemailrep",$_POST))
	{
	  $ls_emailrep=$_POST["txtemailrep"];	  
	  $lr_datos["txtemailrep"]=$ls_emailrep;
    }
else
	{
	  $ls_emailrep="";
	}
	
	if  (array_key_exists("cmbgraemp",$_POST))
	{
	  $ls_select=$_POST["cmbgraemp"];	  
	  $lr_datos["cmbgraemp"]=$ls_select;
    }
else
	{
	  $ls_select="";
	}
	
if  (array_key_exists("cmbgraemp",$_POST))
	{
	  $ls_selectuno=$_POST["cmbgraemp"];	  
	  $lr_datos["cmbgraemp"]=$ls_selectuno;
    }
else
	{
	  $ls_selectuno="";
	}
	
	if  (array_key_exists("cmbgraemp",$_POST))
	{
	  $ls_selectdos=$_POST["cmbgraemp"];	  
	  $lr_datos["cmbgraemp"]=$ls_selectdos;
    }
else
	{
	  $ls_selectdos="";
	}
if  (array_key_exists("cmbnacionalidad",$_POST))
	{
	  $ls_selectven=$_POST["cmbnacionalidad"];	  
	  $lr_datos["cmbnacionalidad"]=$ls_selectven;
    }
else
	{
	  $ls_selectven="";
	}	
if  (array_key_exists("cmbnacionalidad",$_POST))
	{
	  $ls_selectext=$_POST["cmbnacionalidad"];	  
	  $lr_datos["cmbnacionalidad"]=$ls_selectext;
    }
else
	{
	  $ls_selectext="";
	}
if  (array_key_exists("hidestatus",$_POST))
	{
  	  $ls_estatus=$_POST["hidestatus"];
	}
else
	{
	  $ls_estatus="NUEVO";	  
	}	
if	(array_key_exists("txtcodbancof",$_POST))
	{
	  $ls_codbancof = $_POST["txtcodbancof"];
	  $lr_datos["codbancof"] = $ls_codbancof;
	}
else
	{
	  $ls_codbancof="";	
	}
if	(array_key_exists("txtnombancof",$_POST))
	{
	  $ls_nombancof = $_POST["txtnombancof"];
	}
else
	{
	  $ls_nombancof="";	
	}			
$ls_readonly="";

function uf_load_tipo($as_codemp)
{
  global $io_sql;
  $ls_tipo = "";
  $ls_sql  = "SELECT value FROM sigesp_config WHERE codemp='".$as_codemp."' AND codsis='RPC' AND seccion='RPC'";
  $rs_data = $io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido = false;
	 }
  else
     {
	   if ($row=$io_sql->fetch_row($rs_data))
	      {
		    $lb_valido = true; 
		    $ls_tipo   = $row["value"];
		  }
	    else
		  {
		    $lb_valido = false;
		  }
	 }	 
  return $ls_tipo;
}


function uf_limpiar_variables()
{
global $ls_nombre;
global $ls_direccion;
global $ls_tipoorg;
global $ls_telefono;
global $ls_fax;
global $ls_nacionalidad;
global $ls_especialidad;
global $ls_rif;
global $ls_nit;
global $ld_capital;
global $ld_monmax;
global $ls_banco;
global $ls_cuenta;
global $ls_moneda;
global $ls_pais;
global $ls_estado;
global $ls_municipio;
global $ls_parroquia;
global $ls_proveedor;
global $ls_contratista;
global $ls_contable;
global $ls_contablerecdoc;
global $ls_observacion;
global $ls_denocuenta;		 
global $ls_denocuentarecdoc;
global $ls_cedula;		
global $ls_nomrep;		
global $ls_cargo;		
global $ls_numregRNC;		
global $ls_registro;		
global $ls_fecreg;		
global $ls_numero;		
global $ls_tomo;		
global $ls_fecregRNC;		
global $ls_fecregmod;
global $ls_regmod;		
global $ls_nummod;		
global $ls_tommod;
global $ls_numfol;
global $ls_numfolmod;
global $ls_numlic;
global $ls_inspector;
global $ls_pagweb;
global $ls_email;
global $ls_estprov;
global $ls_fecvenRNC;		
global $ls_regSSO;
global $ls_fecvenSSO;		
global $ls_regINCE;
global $ls_fecINCE;
global $ls_fecvenINCE;
global $ls_estatus;
global $ls_operacion;
global $ls_tipconpro;
global $ls_ctaant;
global $ls_denctaant;
global $ls_tipperpro;

$ls_nombre="";
$ls_direccion="";
$ls_tipoorg="";
$ls_telefono="";
$ls_fax="";
$ls_nacionalidad="V";
$ls_especialidad="";
$ls_rif="";
$ls_nit="";
$ld_capital="";
$ld_monmax="";
$ls_banco="";
$ls_cuenta="";
$ls_moneda="";
$ls_pais="";
$ls_estado="";
$ls_municipio="";
$ls_parroquia="";
$ls_proveedor="";
$ls_contratista="";
$ls_contable="";
$ls_contablerecdoc="";
$ls_observacion="";
$ls_denocuenta="";	
$ls_denocuentarecdoc="";	 
$ls_cedula="";		
$ls_nomrep="";		
$ls_cargo="";		
$ls_numregRNC="";		
$ls_registro="";		
$ls_fecreg="";		
$ls_numero="";		
$ls_tomo="";		
$ls_fecregRNC="";		
$ls_fecregmod="";
$ls_regmod="";		
$ls_nummod="";		
$ls_tommod="";
$ls_numfol="";
$ls_numfolmod="";
$ls_numlic="";
$ls_inspector="";
$ls_pagweb="";
$ls_email="";
$ls_estprov="";
$ls_fecvenRNC="";		
$ls_regSSO="";
$ls_fecvenSSO="";		
$ls_regINCE="";
$ls_fecINCE    = "";
$ls_fecvenINCE = "";
$ls_estatus    = 'NUEVO';
$ls_operacion  = 'NUEVO';
$ls_tipconpro  = '-';
$ls_tipperpro ='-';
$ls_ctaant="";
$ls_denctaant="";
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="NUEVO")
   {
	 $lb_empresa = true;
	 $ls_type    = uf_load_tipo($ls_codemp); // Variable que indica si el Código es 
	                                         // Númerico o Alfanumerico para la 
			                                 // Generacion del consecutivo.A=Alfanumerico,N=Numerico
	 if ($ls_type!='A')
	    {
		  $ls_string = "'0123456789'";
		  $ls_codigo = $io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'rpc_proveedor','cod_pro');
		  if (empty($ls_codigo))
		     {
			   $io_msg->message($io_funciondb->is_msg_error);
		     }
		}
     else
	    {
		  $ls_codigo = "";
		  $ls_string = "'0123456789'+'abcdefghijklmnopqrstuvwxyz'";
		}
	 uf_limpiar_variables();
   }
if (($ls_operacion=="pais")||($ls_operacion=="estado")||($ls_operacion=="municipio")||($ls_operacion=="parroquia"))
{
	 $lb_empresa = true;
	 $ls_type    = uf_load_tipo($ls_codemp); // Variable que indica si el Código es 
	                                         // Númerico o Alfanumerico para la 
			                                 // Generacion del consecutivo.A=Alfanumerico,N=Numerico
	 if ($ls_type!='A')
	    {
		  $ls_string = "'0123456789'";
		}
     else
	    {
		  $ls_string = "'0123456789'+'abcdefghijklmnopqrstuvwxyz'";
		}
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="GUARDAR")
   { 
 	 $lb_existe = $io_proveedor->uf_select_proveedor($ls_codemp,$ls_codigo);
	 if ($lb_existe)
        { 
	      if ($ls_estatus=="NUEVO")
		     {
			   $io_msg->message("Este Código de Proveedor ya existe !!!");  
			   $lb_valido=false;
			 }
		  elseif($ls_estatus=="GRABADO")
		     {
		       $lb_valido=$io_proveedor->uf_update_proveedor($ls_codemp,$lr_datos,$la_seguridad);
	           if ($lb_valido)
		          {
				    $io_sql->commit();
				    $io_msg->message("Registro Actualizado !!!");
				    $lb_empresa    = false;
				    $ls_codigo     = $io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'rpc_proveedor','cod_pro');
				    $ls_estatus    ="NUEVO";
					uf_limpiar_variables();
			      }
		       else
		          {
		            $io_sql->rollback();
			        $io_msg->message("Error en Actualización !!!");
			      }
	         }
	   } 
	 else
	   {
          $lb_valido=$io_proveedor->uf_insert_proveedor($ls_codemp,$lr_datos,$la_seguridad);
	      if($lb_valido)
		    {
		      $io_sql->commit();
			  $io_msg->message("Registro Incluido !!!");
		      $lb_empresa=false;
	          $ls_codigo=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'rpc_proveedor','cod_pro');
		      $ls_estatus="NUEVO";
              uf_limpiar_variables();
		    }
		 else
		    {
   		      $io_sql->rollback();
			  $io_msg->message($io_proveedor->is_msg_error);
		    }
	   } 
	   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   /// PARA LA CONVERSIÓN MONETARIA
	   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=$io_proveedor->uf_convertir_rpcproveedor($lr_datos,$la_seguridad);
		if($lb_valido===false)
		{
			$io_msg->message("Error al Actualizar los montos auxiliares");
		}
	   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
} 

if ($ls_operacion=="ELIMINAR")
   {
 	  $lb_existe = $io_proveedor->uf_select_proveedor($ls_codemp,$ls_codigo);
	  if ($lb_existe)
	     {
	       $lb_valido = $io_proveedor->uf_delete_proveedor($ls_codemp,$ls_codigo,$la_seguridad);
	       if ($lb_valido)
	          {
			    $io_sql->commit();
			    $io_msg->message("Registro Eliminado !!!");
			    $lb_empresa=false;
			    $ls_codigo=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'rpc_proveedor','cod_pro');
				uf_limpiar_variables();
			  }
		   else
			  {
			    $io_sql->rollback();
			    $io_msg->message("Error en Eliminación !!!");
			  }	 
          }
	   else
		  {
		    $io_msg->message("Este Registro No Existe !!!");
		  }  
  }

if ($ls_operacion=="buscar")
   {
	 $ls_readonly = "readonly";
	 $ls_disabled = '';
	 $ls_estado=$_POST["hidestado"];
	 $ls_parroquia=$_POST["hidparroquia"];
	 $ls_municipio=$_POST["hidmunicipio"];
   }
   
if ($ls_operacion=="VERIFICAR")
{
  $ls_rif=$_POST["txtrif"];
  $ls_nif=$_POST["txtnit"];
  $lb_existe = $io_proveedor->uf_select_validar_rif($ls_codemp,$ls_rif);
   if ($lb_existe)
	{ 
	   $io_msg->message("Este Rif ya existe !!!");  
	}
	
}
?>
<form name="form1" method="post" action="">
  <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=permisos value='$la_accesos[leer]'>");
	print("<input type=hidden name=incluir  id=permisos value='$la_accesos[incluir]'>");
	print("<input type=hidden name=cambiar  id=permisos value='$la_accesos[cambiar]'>");
	print("<input type=hidden name=eliminar id=permisos value='$la_accesos[eliminar]'>");
	print("<input type=hidden name=imprimir id=permisos value='$la_accesos[imprimir]'>");
	print("<input type=hidden name=anular   id=permisos value='$la_accesos[anular]'>");
	print("<input type=hidden name=ejecutar id=permisos value='$la_accesos[ejecutar]'>");
}
else
{
	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>  
 <p>&nbsp;  </p>
  
   <table width="770"  border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
    <tr class="titulo-celdanew">
      <td height="22" colspan="5" class="titulo-celdanew">Datos B&aacute;sicos del Proveedor </strong></font></td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td height="22" colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" align="right"><div align="left">
        <input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_estatus ?>">
        <input id="conrecdoc" name="conrecdoc" type="hidden" value="<?php print $ls_conrecdoc; ?>">
      </div></td>
      <td height="22" colspan="4">Los Campos en <span class="sin-borde"><strong>(*) </strong></span>son necesarios para la Incluir el Proveedor </td>
    </tr>
    <tr>
      <td height="22" align="right">&nbsp;</td>
      <td height="22" colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td width="162" height="22" align="right"> <span class="sin-borde"><strong>(*)</strong></span> C&oacute;digo&nbsp;</td>
      <td height="22" colspan="4"><input name="txtcodigo" type="text" id="txtcodigo" onKeyPress="return keyRestrict(event,<?php print $ls_string; ?>); " value="<?php print $ls_codigo; ?>" size="15" maxlength="10"  style="text-align:center" <?php print $ls_readonly ?> onBlur="javascript:rellenar_cadena(this.value,10,this.name);" tabindex="0">
      <input name="operacion" type="hidden" id="operacion">      </td>
    </tr>
    <tr>
      <td height="22" align="right"> <span class="sin-borde"><strong>(*) </strong></span>Nombre&nbsp;</td>
      <td height="22" colspan="4"><input name="txtnombre" type="text" id="txtnombre" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+',-.;*&?¿!¡+()[]{}%@/'+'´áéíóú');" value="<?php print $ls_nombre ?>" size="100" maxlength="100" tabindex="1"></td>
    </tr>
    <tr>
      <td height="22" align="right"><span class="sin-borde" body="bold"><strong>(*)</strong></span> Direcci&oacute;n&nbsp;</td>
      <td height="22" colspan="4"><input name="txtdireccion" type="text" id="txtdireccion" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+',-.;*&?¿!¡+()[]{}%@#/'+'´áéíóú');" value="<?php print $ls_direccion ?>" size="100" maxlength="254" tabindex="2"></td>
    </tr>
    <tr>
      <td height="22" align="right"> <span class="sin-borde"></span> Tipo de Empresa </td>
      <td height="22" colspan="4"><p>
      <?php
		//Llenar Combo Banco
		$ls_codemp=$la_emp["codemp"];
		$rs_pro=$io_proveedor->uf_select_llenarcombo_tipoorganizacion($ls_codemp);
	  ?>
          <select name="cmbtiporg" id="cmbtiporg"  style="width:167px" tabindex="3">
	  <?php
			while($row=$io_sql->fetch_row($rs_pro))
		 	{
		   	  $ls_codtiporg=$row["codtipoorg"];
		  	  $ls_dentiporg=$row["dentipoorg"];
		  	  if ($ls_codtiporg==$ls_tiporg)
		 	  {
		  	    print "<option value='$ls_codtiporg' selected>$ls_dentiporg</option>";
		  	  }
		  	  else
		   	  {
		   	   print "<option value='$ls_codtiporg'>$ls_dentiporg</option>";
		  	  }
			} 
	  ?>
          </select>
      </p>      </td>
    </tr>
    <tr>
      <td height="22" align="right"><span class="sin-borde"><strong>(*)</strong></span>Tel&eacute;fono&nbsp;</td>
      <td height="22" colspan="4"><input name="txttelefono" type="text" id="txttelefono" onKeyPress="return keyRestrict(event,'1234567890'+'()- /');" value="<?php print $ls_telefono ?>" size="60" maxlength="50" tabindex="4"></td>
    </tr>
    <tr>
      <td height="22" align="right"><span class="sin-borde"></span>&nbsp;Fax&nbsp;</td>
      <td height="22" colspan="4"><input name="txtfax" type="text" id="txtfax" onKeyPress="return keyRestrict(event,'1234567890'+'()- /');" value="<?php print $ls_fax ?>" size="30" maxlength="30" tabindex="5"></td>
    </tr>
    <tr>
      <td height="22" align="right">Nacionalidad&nbsp;</td>
      <td height="22" colspan="4"><select name="cmbnacionalidad" id="cmbnacionalidad" tabindex="6">
        <?php 
         if($ls_nacionalidad=="V")    
         {
           $ls_selectven="selected";
         }
         else
         {
           $ls_selectext="selected";
         }
        ?>
        <option value="V" <?php print $ls_selectven ?> >Venezolano</option>
        <option value="E" <?php print $ls_selectext ?> >Extranjero</option>
      </select>
        <label></label></td>
    </tr>
    <tr>
      <td height="22" align="right"> <strong>(*)</strong> R.I.F</td>
      <td height="22" colspan="4"><select name="cmbtipperrif" id="cmbtipperrif" tabindex="7" onChange="document.form1.txtnumpririf.focus();">
        <option value="J" <?php echo $ls_seljur ?>>J </option>
        <option value="G" <?php echo $ls_selgub ?>>G </option>
        <option value="V" <?php echo $ls_selven ?>>V </option>
        <option value="E" <?php echo $ls_selext ?>>E </option>
      </select> 
        <span class="Estilo2">-</span> 
        <input name="txtnumpririf" type="text" id="txtnumpririf" style="text-align:center" tabindex="8" onBlur="javascript:rellenar_cadena(this.value,8,this.name);" onKeyPress="return keyRestrict(event,'1234567890');" onKeyUp="javascript:uf_set_focus();" value="<?php echo $ls_numpririf ?>" size="10" maxlength="8"> 
        <strong>-</strong>
        <label>
        <input name="txtnumterrif" type="text" id="txtnumterrif" style="text-align:center" tabindex="9" onKeyPress="return keyRestrict(event,'1234567890');" value="<?php echo $ls_numterrif ?>" size="2" maxlength="1">
        </label></td>
    </tr>
    <tr>
      <td height="22" align="right">&nbsp;N.I.T&nbsp;</td>
      <td height="22" colspan="4"><input name="txtnit" type="text" id="txtnit" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'.-');" value="<?php print $ls_nit ?>" size="15" maxlength="15" style="text-align:right" tabindex="10"></td>
    </tr>
    <tr>
      <td height="22" align="right"><span class="sin-borde"><strong>(*)</strong></span>&nbsp;Capital Social Suscrito&nbsp;</td>
      <td height="22" colspan="4"><input name="txtcapital" type="text" id="txtcapital" onKeyPress="return(ue_formatonumero(this,'.',',',event))" value="<?php print $ld_capital ?>"  style="text-align:right" tabindex="11"></td>
    </tr>
    <tr>
      <td height="22" align="right"><span class="sin-borde"><strong>(*)</strong></span>&nbsp;Capital Social Pagado &nbsp;</td>
      <td height="22" colspan="4"><input name="txtmonmax" type="text" id="txtmonmax" onKeyPress="return(ue_formatonumero(this,'.',',',event))" value="<?php print $ld_monmax ?>"  style="text-align:right" tabindex="12"></td>
    </tr>
    <tr>
      <td height="22" align="right">Banco&nbsp;</td>
      <td height="22" colspan="4"><p>
	     <?php
		    //Llenar Combo Banco
		    $ls_codemp=$la_emp["codemp"];
		    $rs_pro=$io_proveedor->uf_select_llenarcombo_banco($ls_codemp);
		 ?>
         <select name="cmbbanco" id="cmbbanco"  style="width:167px" tabindex="13">
		 <option value="---">---seleccione---</option>
		 <?php
			 while($row=$io_sql->fetch_row($rs_pro))
			 {
			   $ls_codban=$row["codban"];
			   $ls_denban=$row["nomban"];
			   if ($ls_codban==$ls_banco)
			   {
		    	 print "<option value='$ls_codban' selected>$ls_denban</option>";
		   	   }
			   else
			   {
		    	 print "<option value='$ls_codban'>$ls_denban</option>";
		   	   }
		     } 
		 ?>
          </select></p></td>
    </tr>
    <tr>
      <td height="22" align="right">Cuenta Bancaria N&ordm;&nbsp;</td>
      <td height="22" colspan="4"><input name="txtcuenta" type="text" id="txtcuenta" onKeyPress="return keyRestrict(event,'1234567890');" value="<?php print $ls_cuenta ?>" size="29" maxlength="25"  style="text-align:right" tabindex="14"></td>
    </tr>
    <tr>
      <td height="22" align="right">Moneda&nbsp;</td>
      <td height="22" colspan="4"><?php
		//Llenar Combo Banco
		$ls_codemp=$la_emp["codemp"];
		$rs_pro=$io_proveedor->uf_select_llenarcombo_moneda($ls_codemp);
	  ?>
        <select name="cmbmoneda" id="cmbmoneda" style="width:120px" tabindex="15">
          <?php
		 while($row=$io_sql->fetch_row($rs_pro))
		 {
		   $ls_codmon=$row["codmon"];
		   $ls_denmon=$row["denmon"];
		   if ($ls_codmon==$ls_moneda)
		   {
		     print "<option value='$ls_codmon' selected>$ls_denmon</option>";
		   }
		   else
		   {
		    print "<option value='$ls_codmon'>$ls_denmon</option>";
		   }
		 } 
		 ?>
      </select></td>
    </tr>
    <tr>
      <td height="22" align="right">&nbsp;
      Banco SIGECOF&nbsp;</td>
      <td height="22" colspan="4"><input name="txtcodbancof" type="text" id="txtcodbancof" value="<?php print $ls_codbancof ?>" maxlength="3" style="text-align:center" onBlur="javascript:rellenar_cadena(this.value,3,this.name)"  onKeyPress="return keyRestrict(event,'1234567890'); ">
      <a tabindex="16" href="javascript:catalogo_BCOSIGECOF();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
      <label>
      <input name="txtnombancof" type="text" class="sin-borde" id="txtnombancof" value="<?php print $ls_nombancof ?>" size="65" readonly>
      </label>
      <div align="left"></div></td>
    </tr>
    <tr>
      <td height="22" align="right">Grado de la Empresa</td>
      <td height="22" colspan="4"><select name="cmbgraemp" id="cmbgraemp" style="width:120px" tabindex="17">
        <?php 
         if ($ls_graemp=="0000")    
            {
              $ls_select="selected";
            }

         if ($ls_graemp=="0001")    
            {
              $ls_selectuno="selected";
            }
         
         if($ls_graemp=="0002")    
         {
           $ls_selectdos="selected";
         }
        ?>
        <option value="0001" <?php print $ls_selectuno ?> >Grado Uno</option>
        <option value="0002" <?php print $ls_selectdos ?> >Grado Dos</option>
      </select></td>
    </tr>
    <tr>
      <td height="22" align="right">Contribuyente&nbsp;</td>
	   <?php
	     $ls_formal    = "";
         $ls_especial  = "";
		 $ls_ordinario = "";
		 if ($ls_tipconpro=="F")    
            {
              $ls_formal    = "selected";
			}
         elseif($ls_tipconpro=="E")
            {
              $ls_especial  = "selected";
			}
         else
		    {
			  if ($ls_tipconpro=="O")
			     {
				   $ls_ordinario = "selected";
				 } 
			}
	     $ls_juridica    = "";
         $ls_natural  = "";
		 if ($ls_tipperpro=="J")    
            {
              $ls_juridica    = "selected";
			}
         elseif($ls_tipperpro=="N")
            {
              $ls_natural  = "selected";
			}
	  ?>
	  <td height="22" colspan="4"><select name="cmbcontribuyente" class="contorno" id="cmbcontribuyente" style="width:120px" tabindex="18">
        <option value="-">---seleccione---</option>
        <option value="F" <?php print $ls_formal    ?>>Formal</option>
        <option value="E" <?php print $ls_especial  ?>>Especial</option>
        <option value="O" <?php print $ls_ordinario ?>>Ordinario</option>
      </select></td>
    </tr>
    <tr>
      <td height="22" align="right">Tipo de Persona </td>
      <td height="22" colspan="4"><select name="cmbtipopersona" class="contorno" id="cmbtipopersona" style="width:120px" tabindex="18">
        <option value="-" selected="selected">---seleccione---</option>
        <option value="J" <?php print $ls_juridica ?>>Juridica</option>
        <option value="N" <?php print $ls_natural  ?>>Natural</option>
      </select></td>
    </tr>
    <tr>
      <td height="141" colspan="5" style="text-align:center"><div align="center">
        <table width="691" border="0" cellpadding="0" class="formato-blanco">
          <tr>
            <td height="22" colspan="2" align="left" class="titulo-celdanew">Ubicaci&oacute;n Geogr&aacute;fica</td>
          </tr>
          <tr>
            <td width="123" height="22" style="text-align:right">Pa&iacute;s</td>
            <td width="556" style="text-align:left">
             <?php
             //Llenar Combo Pais
             $rs_pro=$io_proveedor->uf_load_paises();
             ?>
             <select name="cmbpais" id="cmbpais" onChange="javascript:uf_cambiopais();"  style="width:150px" tabindex="20">
             <?php
		     while($row=$io_sql->fetch_row($rs_pro))
		          {
				    $ls_codpai = $row["codpai"];
				    $ls_denpai = $row["despai"];
				    if ($ls_codpai==$ls_pais)
					   {
						 print "<option value='".$ls_codpai."' selected>".$ls_denpai."</option>";
					   }
				    elseif($ls_codpai=='058' && empty($ls_pais))
					   {
					     print "<option value='".$ls_codpai."' selected>".$ls_denpai."</option>";					   
					     $ls_pais = "058";
					   }
					else
					   {
						 print "<option value='".$ls_codpai."'>".$ls_denpai."</option>";
					   }
				  }
	        ?>
            </select></td>
          </tr>
          <tr>
            <td style="text-align:right">Estado</td>
            <td style="text-align:left">
              <?php
          //Llenar Combo Estado
		  $rs_pro=$io_proveedor->uf_load_estados($ls_pais);
		 ?>
              <select name="cmbestado" id="cmbestado" onChange="javascript:uf_cambioestado();" style="width:150px" tabindex="21">
                <option value="---">---seleccione---</option>
                <?php
		 while($row=$io_sql->fetch_row($rs_pro))
		 {
		   $ls_codest=$row["codest"];
		   $ls_denest=$row["desest"];
		   if ($ls_codest==$ls_estado)
			   {
				 print "<option value='".$ls_codest."' selected>".$ls_denest."</option>";
			   }
		   else
			   {
				 print "<option value='".$ls_codest."'>".$ls_denest."</option>";
			   }
		 } 
	     ?>
              </select>
              <input name="hidestado" type="hidden" id="hidestado"></td>
          </tr>
          <tr>
            <td align="right">Municipio</td>
            <td style="text-align:left">
              <?php
          //Llenar Combo Municipio
		  $rs_pro=$io_proveedor->uf_load_municipios($ls_pais,$ls_estado);
         ?>
              <select name="cmbmunicipio" id="cmbmunicipio"  onChange="javascript:uf_cambiomunicipio();"  style="width:150px " tabindex="22">
                <option value="---">---seleccione---</option>
                <?php
		 while($row=$io_sql->fetch_row($rs_pro))
		 {
		   $ls_codmun=$row["codmun"];
		   $ls_denmun=$row["denmun"];
		   if ($ls_codmun==$ls_municipio)
		   {
		     print "<option value='".$ls_codmun."' selected>".$ls_denmun."</option>";
		   }
		   else
		   {
		     print "<option value='".$ls_codmun."'>".$ls_denmun."</option>";
		   }
		 } 
	    ?>
              </select>
              <input name="hidmunicipio" type="hidden" id="hidmunicipio"></td>
          </tr>
          <tr>
            <td align="right">Parroquia</td>
            <td style="text-align:left">
              <?php
          //Llenar Combo Parroquia
		  $rs_pro=$io_proveedor->uf_load_parroquias($ls_pais,$ls_estado,$ls_municipio);
        ?>
              <select name="cmbparroquia" id="cmbparroquia"  style="width:150px " tabindex="23">
                <option value="---">---seleccione---</option>
                <?php
		while($row=$io_sql->fetch_row($rs_pro))
		{
		   $ls_codpar=$row["codpar"];
		   $ls_denpar=$row["denpar"];
		   if ($ls_codpar==$ls_parroquia)
		   {
		     print "<option value='".$ls_codpar."' selected>".$ls_denpar."</option>";
		   }
		   else
		   {
		     print "<option value='".$ls_codpar."'>".$ls_denpar."</option>";
		   }
		 } 
	    ?>
              </select>
              <input name="hidparroquia" type="hidden" id="hidparroquia"></td>
          </tr>
        </table>
      </div></td>
    </tr>
    <tr>
      <td align="right"></td>
      <td colspan="4">&nbsp;        </td>
    </tr>
    <tr>
      <td height="22" align="right">P&aacute;gina Web&nbsp;</td>
      <td colspan="4"><input name="txtpagweb" type="text" id="txtpagweb" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'._-');" value="<?php print $ls_pagweb ?>" size="70" maxlength="200"></td>
    </tr>
    <tr>
      <td height="22" align="right">Email&nbsp;</td>
      <td colspan="4"><input name="txtemail" type="text" id="txtemail" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'._-@'); " value="<?php print $ls_email ?>" size="70" maxlength="200"></td>
    </tr>
    <tr>
      <td height="22" align="right"><strong>(*)</strong>Tipo de Proveedor&nbsp;</td>
      <td colspan="2">
      <input name="chkestpro" type="checkbox" class="sin-borde" value="1" <?php if($ls_proveedor=="1"){ print "checked"; } ?> >
      Proveedor</td>
      <td width="88">
	  <input name="chkestcon" type="checkbox" class="sin-borde" value="1" <?php if($ls_contratista=="1"){ print "checked"; } ?> >
      Contratista</td>
      <td width="321">&nbsp;        </td>
    </tr>
    <tr>
      <td height="30" align="right"> <span class="sin-borde"><strong>(*)</strong></span> Cuenta Contable para el registro de las solicitudes por pagar &nbsp;</td>
      <td colspan="4"><input name="txtcontable" type="text" id="txtcontable" onKeyPress="return keyRestrict(event,'1234567890');" value="<?php print $ls_contable ?>"  style="text-align:center" readonly> <a href="javascript:catalogo_cuentas('');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a> <input name="txtdencuenta" type="text" class="sin-borde" id="txtdencuenta" value="<?php print $ls_denocuenta ?>" size="80" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn&ntilde;opqrstuvwxyz '+'.,-');"></td>
    </tr>
      <?php 	
	  if($ls_conrecdoc=="1")
	  {
	  ?>
    <tr>
      <td height="30" align="right"><span class="sin-borde"><strong>(*)</strong></span> Cuenta Contable para el registro del Gasto por pagar </td>
      <td colspan="4"><input name="txtcontablerecdoc" type="text" id="txtcontablerecdoc" onKeyPress="return keyRestrict(event,'1234567890');" value="<?php print $ls_contablerecdoc ?>"  style="text-align:center" readonly> <a href="javascript:catalogo_cuentas('recdoc');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a> <input name="txtdencuentarecdoc" type="text" class="sin-borde" id="txtdencuentarecdoc" value="<?php print $ls_denocuentarecdoc; ?>" size="80" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn&ntilde;opqrstuvwxyz '+'.,-');"></td>
    </tr>
      <?php 
	  }
	  else
	  {
	?>
	  <input name="txtcontablerecdoc" type="hidden" id="txtcontablerecdoc">
      <?php 
	  }	
	?>
      <tr>
        <td height="22" align="right">Cuenta Contable de Anticipo a Contratistas </td>
        <td colspan="4"><input name="txtctaant" type="text" id="txtctaant" onKeyPress="return keyRestrict(event,'1234567890');" value="<?php print $ls_ctaant ?>"  style="text-align:center" readonly>
          <a href="javascript:catalogo_cuentas('ctaant');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
          <input name="txtdenctaant" type="text" class="sin-borde" id="txtdenctaant" value="<?php print $ls_denctaant; ?>" size="80" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn&ntilde;opqrstuvwxyz '+'.,-');" readonly></td>
      </tr>
     <tr>
      <td height="22" align="right">Observaci&oacute;n&nbsp;</td>
      <td colspan="4"><input name="txtobservacion" type="text" id="txtobservacion" value="<?php print $ls_observacion ?>" size="70" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'.,-');"></td>
    </tr>
    <tr>
      <td height="22" align="right">Estatus del Proveedor&nbsp;</td>
      <td width="87">
      <?php 	
	  if(($ls_estprov=="A")||($ls_estprov==""))
	  {
		$ls_estprovactivo="checked";
	    $ls_estprovinactivo="";
	    $ls_estprovbloqueado="";
	    $ls_estprovsuspendido="";
      }
      elseif($ls_estprov=="I")
	  {
		  $ls_estprovactivo="";
	      $ls_estprovinactivo="checked";
	      $ls_estprovbloqueado="";
	      $ls_estprovsuspendido="";
	  }
  	  elseif($ls_estprov=="B")
	  {
		    $ls_estprovactivo="";
	        $ls_estprovinactivo="";
	        $ls_estprovbloqueado="checked";
	        $ls_estprovsuspendido="";
	  }
 	  else
	  {
		    $ls_estprovactivo="";
	        $ls_estprovinactivo="";
	        $ls_estprovbloqueado="";
	        $ls_estprovsuspendido="checked";
	   }
	  ?>
        <input name="estprov" type="radio" class="sin-borde" value="A" <?php print $ls_estprovactivo ?>>      
      Activo</td>
      <td width="80"><input name="estprov" type="radio" class="sin-borde" value="I" <?php print $ls_estprovinactivo ?>>
      Inactivo</td>
      <td width="88"><input name="estprov" type="radio" class="sin-borde" value="B" <?php print $ls_estprovbloqueado ?>>
      Bloqueado</td>
      <td><input name="estprov" type="radio" class="sin-borde" value="S" <?php print $ls_estprovsuspendido ?>>
      Suspendido</td>
    </tr>
    <tr>
      <td height="28" colspan="5"><div align="center">
        <table width="200" border="0" align="center" class="formato-blanco">
          <tr>
            <td><div align="center"><a href="#top">Volver Arriba</a> </div></td>
          </tr>
        </table>
      </div></td>
     </tr>
  </table>
<br>
  <table width="770"  border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr class="titulo-celdanew">
      <td height="22" colspan="2" align="center" class="titulo-celdanew"><strong>Datos del Representante<a name="representante"></a></strong></td>
    </tr>
    <tr>
      <td width="172" height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" align="right"><div align="right">C&eacute;dula&nbsp;</div></td>
      <td width="596" height="22"><input name="txtcedula" type="text" id="txtcedula" onKeyPress="return keyRestrict(event,'1234567890');" value="<?php print $ls_cedula ?>" size="15" maxlength="10"></td>
    </tr>
    <tr>
      <td height="22" align="right">Nombre&nbsp;</td>
      <td height="22"><input name="txtnomrep" type="text" id="txtnomrep" onKeyPress="return keyRestrict(event,'abcdefghijklmnñopqrstuvwxyz ');" value="<?php print $ls_nomrep ?>" size="50" maxlength="50"></td>
    </tr>
    <tr>
      <td height="22" align="right">Cargo&nbsp;</td>
      <td height="22"><input name="txtcargo" type="text" id="txtcargo" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'.,-');" value="<?php print $ls_cargo ?>" size="35" maxlength="35"></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Email&nbsp;</div></td>
      <td height="22"><input name="txtemailrep" type="text" id="txtemailrep" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnoñpqrstuvwxyz '+'._-@');" value="<?php print $ls_emailrep ?>" size="60" maxlength="60"></td>
    </tr>
    <tr>
      <td height="13" colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="2"><div align="center">
        <table width="200" border="0" class="formato-blanco">
          <tr>
            <td><div align="center"><a href="#top">Datos B&aacute;sicos del Proveedor</a> </div></td>
          </tr>
        </table>
      </div></td>
    </tr>
  </table>
<br>
  <table width="770" border="0" cellpadding="0" cellspacing="0" align="center" class="contorno">
    <tr class="titulo-celdanew">
		<td height="22" colspan="4" class="titulo-celdanew">Datos del Registro</strong></font><a name="registro"></a></td>
	</tr>
	<tr>
	  <td height="22"><div align="right">Registro Nacional de Contratistas</div></td>
	  <td height="22"><label>
	    <input name="txtestatusRNC" type="text" class="sin-borde2" id="txtestatusRNC" value="<?php print $ls_registronacional; ?>" readonly>
	  </label></td>
    </tr>
	<tr>
      <td width="230" height="22" align="right">N&ordm; Registro RNC</td>
      <td width="411" height="22"><input name="txtnumregRNC" type="text" id="txtnumregRNC" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz ')"; value="<?php print $ls_numregRNC ?>" size="40" maxlength="35">      </td>
    </tr>
    <tr>
      <td height="22" align="right" >Fecha de Registro RNC&nbsp;</td>
      <td height="22"><input name="txtfecregRNC" type="text" id="txtfecregRNC" value="<?php print $ls_fecregRNC ?>" onBlur="valFecha(document.form1.txtfecregRNC)" datepicker="true" onKeyPress="currencyDate(this);"></td>
    </tr>
    <tr>
      <td height="22" align="right">Fecha de Vencimiento Registro RNC&nbsp;</td>
      <td height="22"><input name="txtfecvenRNC" type="text" id="txtfecvenRNC" value="<?php print $ls_fecvenRNC ?>" onBlur="valFecha(document.form1.txtfecregRNC)" datepicker="true" onKeyPress="currencyDate(this);"></td>
    </tr>
    <tr>
      <td height="22" align="right">N&ordm; Registro SSO&nbsp;</td>
      <td height="22"><input name="txtregSSO" type="text" id="txtregSSO" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz ')"; value="<?php print $ls_regSSO ?>" size="15" maxlength="15"></td>
    </tr>
    <tr>
      <td height="22" align="right">Fecha de Vencimiento SSO&nbsp;</td>
      <td height="22"><input name="txtfecvenSSO" type="text" id="txtfecvenSSO" value="<?php print $ls_fecvenSSO ?>" onBlur="valFecha(document.form1.txtfecregRNC)" datepicker="true" onKeyPress="currencyDate(this);"></td>
    </tr>
    <tr>
      <td height="22" align="right">N&ordm; Registro INCE&nbsp;</td>
      <td height="22"><input name="txtregINCE" type="text" id="txtregINCE" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz ')"; value="<?php print $ls_regINCE ?>" size="15" maxlength="15"></td>
    </tr>
    <tr>
      <td height="22" align="right">Fecha Vencimiento INCE&nbsp;</td>
      <td height="22"><input name="txtfecvenINCE" type="text" id="txtfecvenINCE" value="<?php print $ls_fecvenINCE ?>" onBlur="valFecha(document.form1.txtfecregRNC)" datepicker="true" onKeyPress="currencyDate(this);"></td>
    </tr>
    <tr>
      <td height="22" align="right">Registro Subalterno&nbsp;</td>
      <td height="22"><input name="txtregistro" type="text" id="txtregistro" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz ')"; value="<?php print $ls_registro ?>"></td>
    </tr>
    <tr>
      <td height="22" align="right">Fecha del Registro Subalterno&nbsp;</td>
      <td height="22"><input name="txtfecreg" type="text" id="txtfecreg" value="<?php print $ls_fecreg ?>" onBlur="valFecha(document.form1.txtfecreg)" datepicker="true" onKeyPress="currencyDate(this);"></td>
    </tr>
    <tr>
      <td height="22" align="right">N&ordm; del Registro&nbsp;</td>
      <td height="22"><input name="txtnumero" type="text" id="txtnumero" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz ')"; value="<?php print $ls_numero?>" size="15" maxlength="15"></td>
    </tr>
    <tr>
      <td height="22" align="right">Tomo del Registro&nbsp;</td>
      <td height="22"><input name="txttomo" type="text" id="txttomo" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz ')"; value="<?php print $ls_tomo ?>" size="5" maxlength="5"></td>
    </tr>
    <tr>
      <td height="22" align="right">Registro Modificado&nbsp;</td>
      <td height="22"><input name="txtregmod" type="text" id="txtregmod" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz ')"; value="<?php print $ls_regmod ?>" size="40" maxlength="35"></td>
    </tr>
    <tr>
      <td height="22" align="right">Fecha de Registro Modificado&nbsp;</td>
      <td height="22"><input name="txtfecregmod" type="text" id="txtfecregmod" value="<?php print $ls_fecregmod ?>" onBlur="valFecha(document.form1.txtfecregmod)" datepicker="true" onKeyPress="currencyDate(this);"></td>
    </tr>
    <tr>
      <td height="22" align="right">Tomo Modificado&nbsp;</td>
      <td height="22"><input name="txttommod" type="text" id="txttommod" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz ')"; value="<?php print $ls_tommod ?>" size="5" maxlength="5"></td>
    </tr>
    <tr>
      <td height="22" align="right">N&ordm; Modificado&nbsp;</td>
      <td height="22"><input name="txtnummod" type="text" id="txtnummod" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz ')"; value="<?php print $ls_nummod ?>" size="15" maxlength="15"></td>
    </tr>
    <tr>
      <td height="22" align="right" >N&ordm; Folio&nbsp;</td>
    <td height="22"><input name="txtnumfol" type="text" id="txtnumfol" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz ')"; value="<?php print $ls_numfol ?>" size="5" maxlength="5"></tr>
    <tr>
      <td height="22" align="right">N&ordm; Folio Modificado&nbsp;</td>
    <td height="22"><input name="txtnumfolmod" type="text" id="txtnumfolmod" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz ')"; value="<?php print $ls_numfolmod ?>" size="5" maxlength="5"></tr>
    <tr>
      <td height="22" align="right">N&ordm; Licencia&nbsp;</td>
    <td height="22"><input name="txtnumlic" type="text" id="txtnumlic" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz ')"; value="<?php print $ls_numlic ?>" size="27" maxlength="25"></tr>
    <tr>
      <td height="22" align="right">Inspector&nbsp;</td>
      <?php
		if($ls_inspector==1)
		{
	  ?>
      <td height="22"><input name="cbinspector" type="checkbox" class="sin-borde" id="cbinspector" value="1" checked>
      <?php
	    }
	    else
	    {
	  ?>	
      <td width="57" height="22"><input name="cbinspector" type="checkbox" id="cbinspector" value="1">
      <?php
		}
	  ?>    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td height="22"><span class="style13"></span>    
      <td height="22"><span class="style13"></span>        </tr>
    <tr>
      <td height="22" colspan="2"><div align="center">
        <input name="botsocios" type="button" class="boton" id="botsocios" onClick="javascript: uf_socio(document.form1.txtcodigo.value)" value="Socios" <?php print $ls_disabled ?>> 
        <input name="botdocumentos" type="button" class="boton" id="botdocumentos" onClick="javascript: uf_documento(document.form1.txtcodigo.value)" value="Documentos"  <?php print $ls_disabled ?>> 
        <input name="botclasificacion" type="button" class="boton" id="botclasificacion" onClick="javascript: uf_clasificacion(document.form1.txtcodigo.value)" value="Calificaci&oacute;n" <?php print $ls_disabled ?>>
        <input name="btnespecialidad" type="button" class="boton" id="btnespecialidad" onClick="javascript: uf_especialidad(document.form1.txtcodigo.value)" value="Especialidad" <?php print $ls_disabled ?>>
        <input name="btndeducciones" type="button" class="boton" id="btndeducciones" onClick="javascript: uf_deducciones(document.form1.txtcodigo.value,document.form1.cmbtipopersona.value)" value="Deducciones" <?php print $ls_disabled ?>>
        <input name="cmbespecialidad" id="cmbespecialidad" type="hidden" <?php print $ls_especialidad; ?>>      
      </div></td>
    <td height="22">    </tr>
    <tr>
      <td height="22"></td>
      <td height="22"></td>
      <td height="22"></td>
    </tr>
    <tr>
      <td height="22" colspan="3"><div align="center">
        <table width="434" border="0" align="center" class="formato-blanco">
          <tr>
            <td width="241"><div align="center"><a href="#top">Datos B&aacute;sicos del Proveedor</a> </div></td>
            <td width="273"><div align="center"><a href="#representante">Datos del Representante</a></div>              </td>
            </tr>
        </table>
      </div></td>
    </tr>
    <tr>
      <td height="22"></td>
      <td height="22"></td>
      <td height="22"></td>    
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</form>
</body>

<script language="javascript">
function uf_socio(prov)
{
  f=document.form1;
  if (prov=="")
      {
	    alert("Debe seleccionar previamente un Proveedor Válido !!!");
      }
   else
      {
	    ls_nomprov=f.txtnombre.value;
		pagina="sigesp_rpc_d_socio.php?txtprov="+prov +"&txtnombre="+ls_nomprov;
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=500,resizable=yes,location=no");
      }
}

function uf_documento(prov)
{
   f=document.form1; 
   if (prov=="")
      {
	    alert("Debe seleccionar previamente un Proveedor Válido !!!");
      }
   else
      {
        ls_nomprov=f.txtnombre.value;
		pagina="sigesp_rpc_w_proxdoc.php?txtprov="+prov +"&txtnombre="+ls_nomprov;
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=500,resizable=yes,location=no");
	  }	
}

function uf_clasificacion(prov)
{
 f=document.form1;
 if (prov=="")
      
	  {
	    alert("Debe seleccionar previamente un Proveedor Válido !!!");
      }
   else
      {
 	    ls_nomprov=f.txtnombre.value;
		pagina="sigesp_rpc_w_proxcla.php?txtprov="+prov +"&txtnombre="+ls_nomprov;
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
 	  }
}

function uf_especialidad(prov)
{
 f=document.form1;
 if (prov=="")
      
	  {
	    alert("Debe seleccionar previamente un Proveedor Válido !!!");
      }
   else
      {
 	    ls_nomprov=f.txtnombre.value;
		pagina="sigesp_rpc_w_proxesp.php?codprov="+prov +"&nomprov="+ls_nomprov;
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,resizable=yes,location=no");
 	  }
}

function uf_deducciones(prov,tipperpro)
{
	f=document.form1;
	if (prov=="")
	{
		alert("Debe seleccionar previamente un Proveedor Válido !!!");
	}
	else
	{
		if(tipperpro=='-')
		{
			alert("Debe Seleccionar un tipo de Persona guardar el proveedor y aplicar la deduccion correspondiente. ");
		}
		else
		{
			ls_nomprov=f.txtnombre.value;
			pagina="sigesp_rpc_w_proxded.php?codprov="+prov+"&tipperpro="+tipperpro+"&nomprov="+ls_nomprov;
			window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,resizable=yes,location=no");
		}
	}
}

/*Function:  ue_guardar()
	 *
	 *Descripción: Función que se llama al presionar la opcion de "Grabar" en el toolbar o en el menu la cual realiza primero
	               la verificacion de que las cajas de textos no esten vacias. En caso de que exista un campo vacio se enviara
				   un mensaje Javascript al usuario indicandole que campo(s) debe rellenar apoyandose en la funcion valida_null y en caso de que todos los campos estén llenos
				   se procede al llamado del codigo PHP respectivo a si la opcion es "GUARDAR"  .*/
function ue_guardar()
{//1
  var resul="";
  
  f          = document.form1;
  li_incluir = f.incluir.value;
  li_cambiar = f.cambiar.value;
  evento     = f.hidestatus.value;
		
  if (((evento=="NUEVO")&&(li_incluir==1))||(evento=="GRABADO")&&(li_cambiar==1))
     {//2  	
	   with (document.form1)
		    {//3
		      if (valida_null(txtcodigo,"El Código del Proveedor esta vacio!!")==false)
		         {//4
			       txtcodigo.focus();
		         }//4
		      else
		         {//5
				   if (valida_null(txtnombre,"El Nombre del Proveedor esta vacio!!")==false)
			          {//6
			            txtnombre.focus();
			          }//6
			       else
			          {//7
			            if (valida_null(txtdireccion,"La Dirección del Proveedor esta vacia!!")==false)
			               {//8
				             txtdireccion.focus();
			               }//8
			            else
			               {//9
			                 if (valida_null(txttelefono,"El Telefono del Proveedor esta vacio!!")==false)
			                    {//10
				                  txttelefono.focus();
			                    }//10
			                 else
			                    {//11		 	
				                  if (valida_null(txtnumpririf,"Complete RIF del Proveedor !!!")==false) 
				                     {//12
				                       txtnumpririf.focus();
				                     }//12
				                  else
				                     {//13
				                       if (valida_null(txtnumterrif,"Complete RIF del Proveedor !!!")==false) 
				                          {//14
				                            txtnumterrif.focus();
				                          }//14
				                       else
				                          {//15
				                            if (valida_null(txtcapital,"El Capital Social Suscrito está vacío !!!")==false) 
				                               {//16
					                             txtcapital.focus();
				                               }//16
				                            else
				                               {//17
					                             if (valida_null(txtmonmax,"El monto esta está vacío !!!")==false) 
					                                {//18
					                                  txtcapital.focus();
					                                }//18
					                             else
					                                {//19
													       if (valida_null(txtcontable,"El Código Contable Asociado al Proveedor está vacío !!!")==false) 
					                                          {//22
						                                        txtcontable.focus();
					                                          }//22
					                                       else
					                                          {//23
																if (f.cmbpais.value!='---' && (f.cmbestado.value=='---' || f.cmbmunicipio.value=='---' || f.cmbparroquia.value=='---'))
							                                       {//24
																	 alert("Debe completar la Ubicación Geográfica !!!");
							                                       }//24
							                                    else
							                                       {//25
							                                         if (f.cmbestado.value!='---' && (f.cmbmunicipio.value=='---' || f.cmbparroquia.value=='---'))
								                                        {//26
																		  alert("Debe completar la Ubicación Geográfica !!!");
									                                    }//26
							                                         else
								                                        {//27
									                                      if (f.cmbmunicipio.value!='---' && f.cmbparroquia.value=='---') 
									                                         {//28
																			   alert("Debe completar la Ubicación Geográfica !!!");
										                                     }//28
									                                      else
									                                         {//29
																				if((f.chkestpro.checked==false)&&(f.chkestcon.checked==false))
																				{
																				   alert("Debe completar el Tipo de Proveedor !!!");
																				 }//28
																			  else
																				 {//29
																				   f=document.form1;
																				   f.operacion.value="GUARDAR";
																				   f.action="sigesp_rpc_d_proveedor.php";
																				   f.submit();
																				  }		  
										                                     }//29
									                                    }//27
							                                       }//25
					                                          }//23
					                                }//19
					                           }//17
				                          }//15
				                     }//13
				                }//11
				           }//9
			          }//7
			     }//5 
			 }//3
      }//2	
	else
	  {
	    alert("No tiene permiso para realizar esta operacion");
	  }
}//1


/*Function:  valida_null(field , mensaje)
	 *
	 *Descripción:   Función que se encarga de evaluar al objeto "field" para verificar si esta o no en blanco, en caso de que el objeto 
	                 este vacio se imprime el mensaje y se devuelve false,en caso contrario se devuelve true.
	  *Argumentos:   field: Objeto el cual va a ser chequeado su condicion de vacio. Ejempo: txtcedula.  
	                 mensaje: Cadena de caracteres que se mostrara al usuario en caso de que el contenido del objeto sea igual a null o
					 igual a vacio(blanco).*/
function valida_null(field,mensaje)
{
  with (field) 
  {
    if (value==null||value=="")
      {
        alert(mensaje);
        return false;
      }
    else
      {
   	    return true;
      }
  }
}	
/*Fin de la Funcion valida_null*/


/*Function:  ue_eliminar()
	 *
	 *Descripción: Función que se llama al presionar la opcion de "Eliminar" en el toolbar o en el menu. En caso de que el usuario 
	               presione la opcion de eliminar sin tener marcado ningun registro se enviara un mensaje Javascript indicandole 
				   que no ha seleccionado ningun registro para su eliminación. Una vez seleccionado el registro a eliminar y 
				   presionada la opción de eliminar se envia al usuario un mensaje Javascript de verificación que presenta al 
				   usuario las opciones de aceptar o cancelar. Si el usuario presiona la opción aceptar se procede a eliminar dicho 
				   registro,en caso contrario se detiene el proceso de eliminación y se limpia los campos de la pagina.*/
function ue_eliminar()
{
    var borrar="";
    f=document.form1;
    li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	  {		
			if (f.txtcodigo.value=="")
			{
			   alert("No ha seleccionado ningún registro para eliminar !!!");
			}
			else
			{
				borrar=confirm("¿ Esta seguro de eliminar este registro ?");
				if (borrar==true)
				   { 
					  f.operacion.value="ELIMINAR";
					  f.action="sigesp_rpc_d_proveedor.php";
					  f.submit();
				   }
				else
				   { 
					 alert("Eliminación Cancelada !!!");
				   }
			}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
/*Fin de la Funcion ue_eliminar()*/



//UBICACIÓN GEOGRÁFICA
/*Function:  uf_cambiopais()
*
 *Descripción: Función que se encarga de llamar al respectivo codigo PHP una vez el usuario haya seleccionado un pais y cargar todos
               los estados pertenecientes a ese pais en el combo de Estado (cmbestado) */
		function uf_cambiopais()
		{
			f=document.form1;
			f.action="sigesp_rpc_d_proveedor.php";
			f.operacion.value="pais";
			f.submit();
		}
/*Fin de la función uf_cambiopais()*/


/*Function:  uf_cambioestado()
*
 *Descripción: Función que se encarga de llamar al respectivo codigo PHP una vez el usuario haya seleccionado un estado y cargar todos
               los municipios pertenecientes a ese pais en el combo de Municipio (cmbmunicipio) */
		function uf_cambioestado()
		{
			f=document.form1;
			f.action="sigesp_rpc_d_proveedor.php";
			f.operacion.value="estado";
			f.submit();
		}
/*Fin de la función uf_cambioestado()*/
		

/*Function:  uf_cambiomunicipio()
*
 *Descripción: Función que se encarga de llamar al respectivo codigo PHP una vez el usuario haya seleccionado un municipio y cargar 
               todas las parroquias pertenecientes a ese municipio en el combo de Parroquia (cmbparroquia) */
		function uf_cambiomunicipio()
		{
			f=document.form1;
			f.action="sigesp_rpc_d_proveedor.php";
			f.operacion.value="municipio";
			f.submit();
		}
/*Fin de la función uf_cambiomunicipio()*/
//FIN DE UBICACIÓN GEOGRÁFICA


		function valSep(oTxt)
		{ 
			var bOk = false; 
			var sep1 = oTxt.value.charAt(2); 
			var sep2 = oTxt.value.charAt(5); 
			bOk = bOk || ((sep1 == "-") && (sep2 == "-")); 
			bOk = bOk || ((sep1 == "/") && (sep2 == "/")); 
			return bOk; 
		} 
		
		function finMes(oTxt)
		{ 
			var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
			var nAno = parseInt(oTxt.value.substr(6), 10); 
			var nRes = 0; 
			switch (nMes)
			{ 
			 case 1: nRes = 31; break; 
			 case 2: nRes = 28; break; 
			 case 3: nRes = 31; break; 
			 case 4: nRes = 30; break; 
			 case 5: nRes = 31; break; 
			 case 6: nRes = 30; break; 
			 case 7: nRes = 31; break; 
			 case 8: nRes = 31; break; 
			 case 9: nRes = 30; break; 
			 case 10: nRes = 31; break; 
			 case 11: nRes = 30; break; 
			 case 12: nRes = 31; break; 
			} 
		 return nRes + (((nMes == 2) && (nAno % 4) == 0)? 1: 0); 
		} 
		
		function valDia(oTxt)
		{ 
		   var bOk = false; 
		   var nDia = parseInt(oTxt.value.substr(0, 2), 10); 
		   bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt))); 
		   return bOk; 
		} 
		
		function valMes(oTxt)
		{ 
			var bOk = false; 
			var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
			bOk = bOk || ((nMes >= 1) && (nMes <= 12)); 
			return bOk; 
		} 
		
		function valAno(oTxt)
		{ 
			var bOk = true; 
			var nAno = oTxt.value.substr(6); 
			bOk = bOk && ((nAno.length == 2) || (nAno.length == 4)); 
			if (bOk)
			{ 
			 for (var i = 0; i < nAno.length; i++)
			 { 
			   bOk = bOk && esDigito(nAno.charAt(i)); 
			 } 
			} 
		 return bOk; 
		 } 
		
		 function valFecha(oTxt)
		 { 
			var bOk = true; 
				if (oTxt.value != "")
				{ 
				 bOk = bOk && (valAno(oTxt)); 
				 bOk = bOk && (valMes(oTxt)); 
				 bOk = bOk && (valDia(oTxt)); 
				 bOk = bOk && (valSep(oTxt)); 
				 if (!bOk)
				 { 
				  alert("Fecha inválida ,verifique el formato(Ejemplo: 10/10/2005) \n o introduzca una fecha correcta."); 
				  oTxt.value = "01/01/1900"; 
				  oTxt.focus(); 
				 } 
				}
		}

function esDigito(sChr)
{ 
    var sCod = sChr.charCodeAt(0); 
    return ((sCod > 47) && (sCod < 58)); 
}


/*Function:  ue_buscar()
	 *
	 *Descripción: Función que se encarga de hacer el llamado al catalogo de los proveedores*/   
    function ue_buscar()
    {
		f=document.form1;
		li_leer=f.leer.value;
		if(li_leer==1)
		{
			f.operacion.value="";			
			pagina="sigesp_rpc_cat_proveedores.php";
			window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=770,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}
	} 
/*Fin de la Función ue_buscar()*/


/*Function:  catalogo_cuentas()
	 *
	 *Descripción: Función que se encarga de hacer el llamado al catalogo de las cuentas contables*/   
   function catalogo_cuentas(tipo)
		{
            f=document.form1;
			f.operacion.value="";			
			pagina="sigesp_catdinamic_ctas.php?tipo="+tipo;
  			window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no");
		} 		
/*Fin de la Función catalogo_cuentas()*/


/*Function:  ue_nuevo()
	 *
	 *Descripción: Función que se encarga de colocar la página en su estado original y poner la página a disposición para incluir
	               un nuevo registro*/   
function ue_nuevo()
		{
		  f=document.form1;
          li_incluir=f.incluir.value;	
 	      if(li_incluir==1)
	      {			 
				  f.txtnombre.value="";
				  f.txtdireccion.value="";
				  f.txttelefono.value="";
				  f.txtfax.value="";
				  f.txtnumpririf.value="";
				  f.txtnumterrif.value="";
				  f.txtnit.value="";
				  f.cmbespecialidad.value="";
				  f.cmbtipperrif[0].selected=true;
				  f.cmbpais[0].selected=true;
				  f.cmbtiporg[0].selected=true;		 
				  f.cmbbanco[0].selected=true;
				  f.txtmonmax.value="";
				  f.txtcapital.value="";
				  f.txtcuenta.value="";
				  f.txtcontable.value="";
				  f.txtdencuenta.value="";
				  f.txtcedula.value="";
				  f.txtnomrep.value="";
				  f.txtcargo.value="";
				  f.txtnumregRNC.value="";
				  f.txtfecregRNC.value="";
				  f.txtregistro.value="";
				  f.txtfecreg.value="";
				  f.txtregmod.value="";
				  f.txtfecregmod.value="";
				  f.txtnumero.value="";
				  f.txtnummod.value="";
				  f.txttomo.value="";
				  f.txttommod.value="";
				  f.txtnumfol.value="";
				  f.txtnumfolmod.value="";
				  f.txtobservacion.value="";
				  f.txtnumlic.value="";
				  f.cbinspector.value="";
				  f.chkestpro.checked=true;
				  f.chkestcon.checked=false;
				  f.estprov[0].checked=true;
				  f.txtfecvenRNC.value="";
				  f.txtregSSO.value="";
				  f.txtfecvenSSO.value="";
				  f.txtregINCE.value="";
				  f.txtfecvenINCE.value="";
				  f.txtpagweb.value="";
				  f.txtemail.value="";
				  f.operacion.value="NUEVO";
				  f.cmbcontribuyente[0].selected = true;
				  f.cmbtipopersona[0].selected = true;
				  f.action="sigesp_rpc_d_proveedor.php";
				  f.submit();
		    }
	        else
	        {
		        alert("No tiene permiso para realizar esta operacion");
	        }
		}
/*Fin de la Función ue_nuevo()*/

function currencyDate(date)
  { 
	ls_date=date.value;
	li_long=ls_date.length;
	f=document.form1;
			 
		if(li_long==2)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(0,2);
			li_string=parseInt(ls_string);
			if((li_string>=1)&&(li_string<=31))
			{
				date.value=ls_date;
			}
			else
			{
				date.value="";
			}
			
		}
		if(li_long==5)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(3,2);
			li_string=parseInt(ls_string);
			if((li_string>=1)&&(li_string<=12))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,3);
			}
		}
		if(li_long==10)
		{
			ls_string=ls_date.substr(6,4);
			li_string=parseInt(ls_string);
			if((li_string>=1900)&&(li_string<=2090))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,6);
			}
		}
 
   }

function currencyFormat(fld, milSep, decSep, e) { 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
    if (whichCode == 13) return true; // Enter 
    key = String.fromCharCode(whichCode); // Get key value from key code 
    if (strCheck.indexOf(key) == -1) return false; // Not a valid key 
    len = fld.value.length; 
    for(i = 0; i < len; i++) 
     if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break; 
    aux = ''; 
    for(; i < len; i++) 
     if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
    aux += key; 
    len = aux.length; 
    if (len == 0) fld.value = ''; 
    if (len == 1) fld.value = '0'+ decSep + '0' + aux; 
    if (len == 2) fld.value = '0'+ decSep + aux; 
    if (len > 2) { 
     aux2 = ''; 
     for (j = 0, i = len - 3; i >= 0; i--) { 
      if (j == 3) { 
       aux2 += milSep; 
       j = 0; 
      } 
      aux2 += aux.charAt(i); 
      j++; 
     } 
     fld.value = ''; 
     len2 = aux2.length; 
     for (i = len2 - 1; i >= 0; i--) 
      fld.value += aux2.charAt(i); 
     fld.value += decSep + aux.substr(len - 2, len); 
    } 
    return false; 
   }
   
function rellenar_cadena(cadena,longitud,objeto)
{
var mystring = new String(cadena);
cadena_ceros = "";
lencad       = mystring.length;
total        = longitud-lencad;
if (cadena!='')
   {
	 for (i=1;i<=total;i++)
		 {
		  cadena_ceros=cadena_ceros+"0";
		 }
	 cadena=cadena_ceros+cadena;   
	 if (objeto=='txtcodigo')
	    {
		  document.form1.txtcodigo.value=cadena;
		}
	 else
	    {
		  if (objeto=='txtnumpririf')
	         {
		       document.form1.txtnumpririf.value=cadena;
		     }
		  else
			 {
			   document.form1.txtcodbancof.value=cadena;
			 }
		}
   }
}

function verificar_rif()
{
	f=document.form1;
    f.operacion.value="VERIFICAR";
    f.action="sigesp_rpc_d_proveedor.php";
    f.submit();
}

/*Function:  catalogo_BCOSIGECOF()
	 *
	 *Descripción: Función que se encarga de hacer el llamado al catalogo de las cuentas contables*/   
function catalogo_BCOSIGECOF()
{
	f=document.form1;
	f.operacion.value="";			
	pagina="sigesp_rpc_cat_bancos_sigecof.php";
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
} 
function uf_set_focus()
{
  f = document.form1;
  ls_numrif = f.txtnumpririf.value;
  li_len = ls_numrif.length;
  if (li_len=='8')
     {
	   f.txtnumterrif.focus();
	 }
}
/*Fin de la Función catalogo_cuentas()*/
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>