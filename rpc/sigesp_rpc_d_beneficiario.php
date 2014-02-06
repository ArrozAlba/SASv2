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
<html>
<head>
<title>Registro de Beneficiarios</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="css/rpc.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<!-- Copyright 2000,2001 Macromedia, Inc. All rights reserved. -->
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<a name="top"></a>
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
require_once("class_folder/sigesp_rpc_c_beneficiario.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_funciones.php");
$io_beneficiario = new sigesp_rpc_c_beneficiario();
$io_conect       = new sigesp_include();
$con             = $io_conect-> uf_conectar ();
$la_emp          = $_SESSION["la_empresa"];
$io_msg          = new class_mensajes(); //Instanciando la clase mensajes 
$io_sql          = new class_sql($con); //Instanciando  la clase sql
$io_dsest        = new class_datastore(); //Instanciando la clase datastore
$lb_valido       = "";
$io_funcion      = new class_funciones();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre        = $_SESSION["la_empresa"];
	$ls_empresa  = $arre["codemp"];
	$ls_logusr   = $_SESSION["la_logusr"];
	$ls_sistema  = "RPC";
	$ls_ventanas = "sigesp_rpc_d_beneficiario.php";
    $ls_conrecdoc=$_SESSION["la_empresa"]["conrecdoc"];

	$la_seguridad["empresa"]  = $ls_empresa;
	$la_seguridad["logusr"]   = $ls_logusr;
	$la_seguridad["sistema"]  = $ls_sistema;
	$la_seguridad["ventanas"] = $ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
		}
		else
		{
			$ls_permisos            = $_POST["permisos"];
			$la_accesos["leer"]     = $_POST["leer"];			
			$la_accesos["incluir"]  = $_POST["incluir"];			
			$la_accesos["cambiar"]  = $_POST["cambiar"];
			$la_accesos["eliminar"] = $_POST["eliminar"];
			$la_accesos["imprimir"] = $_POST["imprimir"];
			$la_accesos["anular"]   = $_POST["anular"];
			$la_accesos["ejecutar"] = $_POST["ejecutar"];
		}
	}
	else
	{
	    $la_accesos["leer"]     = "";		
		$la_accesos["incluir"]  = "";
		$la_accesos["cambiar"]  = "";
		$la_accesos["eliminar"] = "";
		$la_accesos["imprimir"] = "";
		$la_accesos["anular"]   = "";
		$la_accesos["ejecutar"] = "";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if  (array_key_exists("operacion",$_POST))
	{
	  $ls_operacion = $_POST["operacion"];
	  $ls_tipconben = $_POST["cmbcontribuyente"]; 
	}
else
	{
	  $ls_operacion = "";
	  $ls_tipconben = "-";
	}
$lr_datos["tipconben"] = $ls_tipconben;

if (array_key_exists("txtcedula",$_POST))
   {
	 $ls_cedula          = $_POST["txtcedula"];
     $lr_datos["cedula"] = $ls_cedula;
   }
else
   {
     $ls_cedula = "";
   }
if (array_key_exists("txtnumpasben",$_POST))
   {
     $ls_numpasben          = $_POST["txtnumpasben"];
 	 $lr_datos["numpasben"] = $ls_numpasben;
   }
else
   {
	 $ls_numpasben = "";
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
	
if (array_key_exists("txtnombre",$_POST))
   {
	 $ls_nombre=$_POST["txtnombre"];
	 $lr_datos["nombre"]=$ls_nombre;
   }
else
   {
	 $ls_nombre="";
   }
if  (array_key_exists("txtapellido",$_POST))
	{
	$ls_apellido=$_POST["txtapellido"];
	$lr_datos["apellido"]=$ls_apellido;
	}
else
	{
	$ls_apellido="";
	}
if  (array_key_exists("txtdireccion",$_POST))
	{
	$ls_direccion=$_POST["txtdireccion"];
	$lr_datos["direccion"]=$ls_direccion;
	}
else
	{
	$ls_direccion="";
	}
if  (array_key_exists("txttelefono",$_POST))
	{
	$ls_telefono=$_POST["txttelefono"];
	$lr_datos["telefono"]=$ls_telefono;
	}
else
	{
	$ls_telefono="";
	}
if  (array_key_exists("txtcelular",$_POST))
	{
	$ls_celular=$_POST["txtcelular"];
	$lr_datos["celular"]=$ls_celular;
	}
else
	{
	$ls_celular="";
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
if	(array_key_exists("txtcontable",$_POST))
	{
	$ls_contable=$_POST["txtcontable"];
	$lr_datos["contable"]=$ls_contable;
	}
else
	{
	$ls_contable="";
	}

if	(array_key_exists("txtcontablerecdoc",$_POST))
	{
	$ls_contablerecdoc=$_POST["txtcontablerecdoc"];
	$lr_datos["contablerecdoc"]=$ls_contablerecdoc;
	}
else
	{
	$ls_contablerecdoc="";
	}	
if	(array_key_exists("txtcuenta",$_POST))
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
	  $lr_datos["denominacion"]=$ls_denocuenta;
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

	
if	(array_key_exists("cmbtipcue",$_POST))
	{
	  $ls_tipocuenta=$_POST["cmbtipcue"];
	  $lr_datos["cmbtipcue"]=$ls_tipocuenta;	
    }
else
	{
	  $ls_tipocuenta="000";
	}
if	(array_key_exists("cmbbanco",$_POST))
	{
	$ls_banco=$_POST["cmbbanco"];
	$lr_datos["banco"]=$ls_banco;
	}
else
	{
	$ls_banco="000";
	}
	
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
if  (array_key_exists("hidestatus",$_POST))
	{
  	  $ls_estatus=$_POST["hidestatus"];
	}
else
	{
	  $ls_estatus="NUEVO";	  
	}
if  (array_key_exists("radionacionalidad",$_POST))
	{
  	  $ls_nacionalidad          = $_POST["radionacionalidad"];
 	  $lr_datos["nacionalidad"] = $ls_nacionalidad;
	}
else
	{
	  $ls_nacionalidad = "V";	  
	}
if ($ls_nacionalidad == 'V')
   {
     $ls_venezolano  = "checked";
     $ls_extranjero  = "";
   }	
else
   {
     $ls_venezolano  = "";
     $ls_extranjero  = "checked";
   }
if  (array_key_exists("txtfecregben",$_POST))
	{
  	  $ls_fecregben          = $_POST["txtfecregben"];
	  $lr_datos["fecregben"] = $ls_fecregben;
	}
else
	{
	  $arr_date     = getdate();
      $ls_dia       = $arr_date['mday'];
	  $ls_mes       = $arr_date['mon'];
	  $ls_ano       = $arr_date['year'];
	  $ls_fecregben = $io_funcion->uf_cerosizquierda($ls_dia,2)."/".$io_funcion->uf_cerosizquierda($ls_mes,2)."/".$ls_ano;
	}		
$ls_readonly="";	
	

 if ($ls_operacion=="GUARDAR")
	{ 
	  $ls_codemp=$la_emp["codemp"];
      $lb_existe=$io_beneficiario->uf_select_beneficiario($ls_codemp,$ls_cedula);
	  if ($lb_existe)
         {           
	       if ($ls_estatus=="NUEVO")
		      {
			    $io_msg->message("Este Beneficiario ya existe !!!");  
			    $lb_valido=false;
			  }
		   elseif($ls_estatus=="GRABADO")
		      {
			    $arre=$_SESSION["la_empresa"];
	            $ls_empresa=$arre["codemp"];
		        $lb_valido=$io_beneficiario->uf_update_beneficiario($ls_codemp,$lr_datos,$la_seguridad);
		        if ($lb_valido)
		           {
					 $io_msg->message("Registro Actualizado !!!");
					 $ls_estatus="NUEVO";
					 $ls_cedula="";$ls_tipperrif='J';$ls_seljur = "selected";$ls_numpririf="";$ls_numterrif="";$ls_nombre="";$ls_apellido="";$ls_direccion="";$ls_telefono="";
					 $ls_celular="";$ls_email="";$ls_contable="";$ls_denocuenta="";$ls_banco="";
					 $ls_tipocuenta="";$ls_cuenta="";$ls_pais="";$ls_estado="";$ls_municipio="";
					 $ls_parroquia="";$ls_codbancof="";$ls_numpasben="";$ls_tipconben="";$ls_denocuentarecdoc="";
					 $ls_contablerecdoc	="";$ls_selext = "";$ls_selgub = "";$ls_selven = "";
			       }
		         else
		           {
			         $io_msg->message($io_beneficiario->is_msg_error);
			       }
		      }
	     }
	  else
	     {
		   $lb_valido=$io_beneficiario->uf_insert_beneficiario($ls_codemp,$lr_datos,$la_seguridad);
		   if ($lb_valido)
		      {
			    $io_msg->message("Registro Incluido !!!");
				$ls_estatus="NUEVO";$ls_cedula="";$ls_seljur = "selected";$ls_tipperrif='J';$ls_numpririf="";$ls_numterrif="";$ls_nombre="";$ls_apellido="";
				$ls_direccion="";$ls_telefono="";$ls_celular="";$ls_email="";$ls_contable="";$ls_denocuenta="";
			    $ls_banco="";$ls_tipocuenta="";$ls_cuenta="";$ls_pais="";$ls_estado="";$ls_municipio="";
				$ls_parroquia="";$ls_codbancof="";$ls_numpasben="";$ls_tipconben="";$ls_contablerecdoc	="";
				$ls_denocuentarecdoc="";$ls_selext = "";$ls_selgub = "";$ls_selven = "";
			  }
		    else
		      {
			    $io_msg->message($io_beneficiario->is_msg_error);
			  }
	     }
}

if ($ls_operacion=="ELIMINAR")
   { 
     $ls_codemp=$la_emp["codemp"];
     $lb_existe=$io_beneficiario->uf_select_beneficiario($ls_codemp,$ls_cedula);
     if ($lb_existe)
	    {
		  $lb_valido=$io_beneficiario->uf_delete_beneficiario($ls_codemp,$ls_cedula,$la_seguridad);
		  if ($lb_valido)
			 { 
			   $io_msg->message("Registro Eliminado !!!");
			   $ls_cedula="";$ls_nombre="";$ls_apellido="";$ls_direccion="";$ls_telefono="";$ls_celular="";$ls_email="";
			   $ls_contable="";$ls_denocuenta="";$ls_banco="";$ls_tipocuenta="";$ls_cuenta="";$ls_pais="";$ls_estado="";
			   $ls_municipio="";$ls_parroquia="";$ls_estatus="NUEVO";$ls_rif="";$ls_codbancof="";$ls_numpasben="";$ls_tipconben="";
			   $ls_contablerecdoc	="";$ls_denocuentarecdoc="";$ls_tipperrif='J';$ls_seljur = "selected";$ls_numpririf="";
			   $ls_numterrif="";$ls_selext = "";$ls_selgub = "";$ls_selven = "";
			 }
		   else
			 {
			   $io_msg->message($io_beneficiario->is_msg_error);
			 }
         }
	  else
		 {
		   $io_msg->message("Este Registro No Existe !!!");
		 }
   }
		
		
if ($ls_operacion=="buscar")
   {
	 $ls_readonly ="readonly";	
	 $ls_estado   =$_POST["hidestado"];
	 $ls_municipio=$_POST["hidmunicipio"];
	 $ls_parroquia=$_POST["hidparroquia"];
   }
?>

<p>&nbsp;</p>
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
<input id="conrecdoc" name="conrecdoc" type="hidden" value="<?php print $ls_conrecdoc; ?>">
  <table width="800" height="615" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="774" height="613"><div align="center">
        <table width="750"  border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
          <tr class="titulo-celdanew">
            <td height="22" colspan="3" class="titulo-celdanew">Registro de Beneficiario</td>
          </tr>
          <tr class="formato-blanco">
            <td height="22">&nbsp;</td>
            <td width="542" height="22"><div align="right"><strong>Fecha de Registro</strong>
              <label>
              <input name="txtfecregben" type="text" class="sin-borde" id="txtfecregben" style="text-align:center" value="<?php print $ls_fecregben ?>" size="12" maxlength="12" readonly>
              </label>
</div></td>
            <td width="19">&nbsp;</td>
          <tr class="formato-blanco">
            <td height="30"><input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_estatus ?>"></td>
            <td height="30" colspan="2">Los Campos en <span class="sin-borde"><strong>(*) </strong></span>son necesarios para la Incluir el Beneficiario              </td>
          <tr class="formato-blanco">
            <td height="22" align="right">Nacionalidad</td>
            <td height="22" colspan="2"><label>
              <input name="radionacionalidad" type="radio" class="sin-borde" value="V" <?php print $ls_venezolano ?> onClick="javascript:cambio_nacionalidad();" tabindex="0">Venezolano</label>
              <label>
              <input name="radionacionalidad" type="radio" class="sin-borde" value="E" <?php print $ls_extranjero ?> onClick="javascript:cambio_nacionalidad();" tabindex="1">
              Extranjero</label></td>
          <tr class="formato-blanco">
            <td width="187" height="22" align="right"><span class="sin-borde"><strong>(*)</strong></span> C&eacute;dula&nbsp;</td>
            <td height="22" colspan="2"><input name="txtcedula" type="text" id="txtcedula" size="15" maxlength="10" onKeyPress="return keyRestrict(event,'1234567890');" value="<?php print $ls_cedula ?>" style="text-align:center " tabindex="2">
              <input name="operacion" type="hidden" id="operacion">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
              Pasaporte 
              <label>
              <input name="txtnumpasben" type="text" id="txtnumpasben" value="<?php print $ls_numpasben ?>" maxlength="10" style="text-align:right" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz ');" tabindex="3">
              </label></td>
          <tr class="formato-blanco">
            <td height="22" align="right">R.I.F&nbsp;</td>
            <td height="22" colspan="3"><select name="cmbtipperrif" id="cmbtipperrif" tabindex="4" onChange="document.form1.txtnumpririf.focus();">
              <option value="J" <?php echo $ls_seljur ?>>J </option>
              <option value="G" <?php echo $ls_selgub ?>>G </option>
              <option value="V" <?php echo $ls_selven ?>>V </option>
              <option value="E" <?php echo $ls_selext ?>>E </option>
            </select>
              <span class="Estilo2">-</span>
              <input name="txtnumpririf" type="text" id="txtnumpririf" style="text-align:center" tabindex="5" onBlur="javascript:rellenar_cad(this.value,8,this.name);" onKeyPress="return keyRestrict(event,'1234567890');" onKeyUp="javascript:uf_set_focus();" value="<?php echo $ls_numpririf ?>" size="10" maxlength="8">
              <strong>-</strong>
              <label>
              <input name="txtnumterrif" type="text" id="txtnumterrif" style="text-align:center" tabindex="6" onKeyPress="return keyRestrict(event,'1234567890');" value="<?php echo $ls_numterrif ?>" size="2" maxlength="1">
              </label></td>
          </tr>
          <tr class="formato-blanco">
            <td height="22" align="right"><span class="sin-borde"><strong>(*)</strong></span>&nbsp;Nombre&nbsp;</td>
            <td height="22" colspan="3"><input name="txtnombre" type="text" id="txtnombre" size="70" maxlength="50" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+',-.;*&?¿!¡+()[]{}%@/'+'´áéíóú');" value="<?php print $ls_nombre ?>" tabindex="7"></td>
          </tr>
          <tr class="formato-blanco">
            <td height="22" align="right"><span class="sin-borde"><strong>(*)</strong></span>&nbsp;Apellido&nbsp;</td>
            <td height="22" colspan="3"><input name="txtapellido" type="text" id="txtapellido" size="70" maxlength="50" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+',-.;*&?¿!¡+()[]{}%@/'+'´áéíóú');" value="<?php print $ls_apellido ?>" tabindex="8"></td>
          </tr>
          <tr class="formato-blanco">
            <td height="22" align="right"><span class="sin-borde"><strong>(*)</strong></span>&nbsp;Direcci&oacute;n&nbsp;</td>
            <td height="22" colspan="3"><input name="txtdireccion" type="text" id="txtdireccion" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+',-.;*&?¿!¡+()[]{}%@/#'+'´áéíóú');" value="<?php print $ls_direccion ?>" size="95" maxlength="254" tabindex="9"></td>
          </tr>
          <tr class="formato-blanco">
            <td height="22" align="right">N&ordm; Tel&eacute;fono Fijo&nbsp;</td>
            <td height="22" colspan="3"><input name="txttelefono" type="text" id="txttelefono" size="20" maxlength="20" onKeyPress="return keyRestrict(event,'1234567890'+'()- /');" value="<?php print $ls_telefono ?>" tabindex="10"></td>
          </tr>
          <tr class="formato-blanco">
            <td height="22" align="right">N&ordm; de Celular&nbsp;</td>
            <td height="22" colspan="3"><input name="txtcelular" type="text" id="txtcelular" size="20" maxlength="20" onKeyPress="return keyRestrict(event,'1234567890'+'()- /');" value="<?php print $ls_celular ?>" tabindex="11"></td>
          </tr>
          <tr class="formato-blanco">
            <td height="22" align="right">E-Mail&nbsp;</td>
            <td height="22" colspan="3"><input name="txtemail" type="text" id="txtemail" size="60" maxlength="100" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'._-@'); " value="<?php print $ls_email ?>" tabindex="12"></td>
          </tr>
          <tr class="formato-blanco">
            <td height="22" align="right"><span class="sin-borde"><strong>(*)</strong></span> Cuenta Contable para el registro de las solicitudes por pagar&nbsp;</td>
            <td height="22" colspan="3"><input name="txtcontable" type="text" id="txtcontable" onKeyPress="return keyRestrict(event,'1234567890');" value="<?php print $ls_contable; ?>" size="25" maxlength="25" readonly  style="text-align:center " tabindex="13">
              <a href="javascript:catalogo_cuentas('');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a> <input name="txtdencuenta" type="text" class="sin-borde" id="txtdencuenta" value="<?php print $ls_denocuenta ?>" size="70" maxlength="100" readonly  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn&ntilde;opqrstuvwxyz '+'.,-');"></td>
          </tr>
      <?php 	
	  if($ls_conrecdoc=="1")
	  {
	  ?>
          <tr class="formato-blanco">
            <td height="22" align="right"><span class="sin-borde"><strong>(*)</strong></span> Cuenta Contable para el registro del Gasto por pagar&nbsp;</td>
            <td height="22" colspan="3"><input name="txtcontablerecdoc" type="text" id="txtcontablerecdoc" onKeyPress="return keyRestrict(event,'1234567890');" value="<?php print $ls_contablerecdoc; ?>"  style="text-align:center">
              <a href="javascript:catalogo_cuentas('recdoc');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
            <input name="txtdencuentarecdoc" type="text" class="sin-borde" id="txtdencuentarecdoc" value="<?php print $ls_denocuentarecdoc; ?>" size="80" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn&ntilde;opqrstuvwxyz '+'.,-');">    </tr>
      <?php 
	  }
	  else
	  {
	?>
	  <input name="txtcontablerecdoc" type="hidden" id="txtcontablerecdoc">
      <?php 
	  }	
	?>
          <tr class="formato-blanco">
            <td height="22" align="right"><span class="sin-borde"></span>Banco&nbsp;</td>
            <td height="22" colspan="3">
              <?php
			//Llenar Combo Banco
			$ls_codemp=$la_emp["codemp"];
			$rs_ben=$io_beneficiario->uf_select_banco($ls_codemp);
        ?>
              <select name="cmbbanco" id="cmbbanco" style="width:150px " tabindex="14">
                <option value="---">---seleccione---</option>
                <?php
          while ($row=$io_sql->fetch_row($rs_ben))
		  {
		   $ls_codban=$row["codban"];
		   $ls_nomban=$row["nomban"];
		   if ($ls_codban==$ls_banco)
			   {
				 print "<option value='$ls_codban' selected>$ls_nomban</option>";
			   }
		   else
			   {
				 print "<option value='$ls_codban'>$ls_nomban</option>";
			   }
		 } 
	  ?>
              </select>
             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</tr>
          <tr class="formato-blanco">
            <td height="22" align="right"><span class="sin-borde"></span>&nbsp;Tipo de Cuenta Bancaria&nbsp;</td>
            <td height="22" colspan="3">
              <?php
			//Llenar Combo Tipo de Cuenta
			$ls_codemp=$la_emp["codemp"];
			$rs_ben=$io_beneficiario->uf_select_tipo_cuenta();
        ?>
              <select name="cmbtipcue" id="cmbtipcue" style="width:150px " tabindex="15">
                <option value="s1">---seleccione---</option>
                <?php
      	 while($row=$io_sql->fetch_row($rs_ben))
		 {
		   $ls_codtipcue=$row["codtipcta"];
		   $ls_nomtipcue=$row["nomtipcta"];
		   if ($ls_codtipcue==$ls_tipocuenta)
		   {
		     print "<option value='$ls_codtipcue' selected>$ls_nomtipcue</option>";
		   }
		   else
		   {
		     print "<option value='$ls_codtipcue'>$ls_nomtipcue</option>";
		   }
		 } 
	?>
              </select></td>
          </tr>
          <tr class="formato-blanco">
            <td height="22" style="text-align:right">&nbsp;Cuenta Bancaria&nbsp;</td>
            <td height="22" colspan="3"><input name="txtcuenta" type="text" id="txtcuenta" onKeyPress="return keyRestrict(event,'1234567890');" value="<?php print $ls_cuenta ?>" size="30" maxlength="25" style="text-align:center" tabindex="16"></td>
          </tr>
          <tr class="formato-blanco">
            <td height="22" align="right"> Banco SIGECOF</td>
            <td height="22" colspan="3"><input name="txtcodbancof" type="text" id="txtcodbancof" value="<?php print $ls_codbancof ?>" maxlength="3" style="text-align:center" onBlur="javascript:rellenar_cad(this.value,3,this.name)" tabindex="17"> 
              <a href="javascript:catalogo_BCOSIGECOF();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> 
              <input name="txtnombancof" type="text" class="sin-borde" id="txtnombancof" value="<?php print $ls_nombancof ?>" size="62"></td>
          <tr class="formato-blanco">
		     <td height="22" style="text-align:right">Contribuyente</td>
		     <td height="22">
			 <?php 
			   $ls_formal    = "";
			   $ls_ordinario = "";
			   if ($ls_tipconben=='F')
			      {
			        $ls_formal = "selected";
				  }
			   elseif($ls_tipconben=='O')
			      {
				    $ls_ordinario = "selected";
				  }	  
			 ?>
			 <select name="cmbcontribuyente" id="cmbcontribuyente" style="width:120px" tabindex="18">
		       <option value="-">---seleccione---</option>
		       <option value="F" <?php print $ls_formal ?>>Formal</option>
		       <option value="O" <?php print $ls_ordinario ?> >Ordinario</option>
	          </select></td>
		   </tr>
          <tr>
            <td height="22" colspan="4" align="center">
              <table width="449" border="0" class="contorno">
                <tr class="titulo-celdanew">
                  <td height="22" colspan="2" align="left"><div align="left">Ubicaci&oacute;n Geogr&aacute;fica</div></td>
                </tr>
                <tr class="formato-blanco">
                  <td width="117" align="right">Pa&iacute;s</td>
                  <td width="320" align="left" scope="col">
                    <?php
            //Llenar Combo Pais
            $rs_ben=$io_beneficiario->uf_select_pais();
          ?>
                    <select name="cmbpais" id="cmbpais" onChange="javascript:uf_cambiopais();"  style="width:150px " tabindex="19">
                      <?php
		  while($row=$io_sql->fetch_row($rs_ben))
		  {
		   $ls_codpai=$row["codpai"];
		   $ls_denpai=$row["despai"];
		   if ($ls_codpai==$ls_pais)
			   {
				 print "<option value='$ls_codpai' selected>$ls_denpai</option>";
			   }
		   else
			   {
				 print "<option value='$ls_codpai'>$ls_denpai</option>";
			   }
		 } 
	     ?>
                    </select></td>
                </tr>
                <tr class="formato-blanco">
                  <td><div align="right">Estado</div></td>
                  <td>
                    <?php
          //Llenar Combo Estado
		  $rs_ben=$io_beneficiario->uf_select_estado($ls_pais);
		 ?>
                    <select name="cmbestado" id="cmbestado" onChange="javascript:uf_cambioestado();"  style="width:150px " tabindex="20">
                      <option value="---">---seleccione---</option>
                      <?php
		 while($row=$io_sql->fetch_row($rs_ben))
		 {
		   $ls_codest=$row["codest"];
		   $ls_denest=$row["desest"];
		   if ($ls_codest==$ls_estado)
			   {
				 print "<option value='$ls_codest' selected>$ls_denest</option>";
			   }
		   else
			   {
				 print "<option value='$ls_codest'>$ls_denest</option>";
			   }
		 } 
	     ?>
                    </select>
                    <input name="hidestado" type="hidden" id="hidestado"></td>
                </tr>
                <tr class="formato-blanco">
                  <td align="right">Municipio</td>
                  <td>
                    <?php
          //Llenar Combo Municipio
		  $rs_ben=$io_beneficiario->uf_select_municipio($ls_pais,$ls_estado);
         ?>
                    <select name="cmbmunicipio" id="cmbmunicipio"  onChange="javascript:uf_cambiomunicipio();"  style="width:150px " tabindex="21">
                      <option value="---">---seleccione---</option>
                      <?php
		 while($row=$io_sql->fetch_row($rs_ben))
		 {
		   $ls_codmun=$row["codmun"];
		   $ls_denmun=$row["denmun"];
		   if ($ls_codmun==$ls_municipio)
		   {
		     print "<option value='$ls_codmun' selected>$ls_denmun</option>";
		   }
		   else
		   {
		     print "<option value='$ls_codmun'>$ls_denmun</option>";
		   }
		 } 
	    ?>
                    </select>
                    <input name="hidmunicipio" type="hidden" id="hidmunicipio"></td>
                </tr>
                <tr class="formato-blanco">
                  <td align="right">Parroquia</td>
                  <td>
                    <?php
          //Llenar Combo Parroquia
		  $rs_ben=$io_beneficiario->uf_select_parroquia($ls_pais,$ls_estado,$ls_municipio);
        ?>
                    <select name="cmbparroquia" id="cmbparroquia"  style="width:150px " tabindex="22">
                      <option value="---">---seleccione---</option>
                      <?php
		while($row=$io_sql->fetch_row($rs_ben))
		{
		   $ls_codpar=$row["codpar"];
		   $ls_denpar=$row["denpar"];
		   if ($ls_codpar==$ls_parroquia)
		   {
		     print "<option value='$ls_codpar' selected>$ls_denpar</option>";
		   }
		   else
		   {
		     print "<option value='$ls_codpar'>$ls_denpar</option>";
		   }
		 } 
	    ?>
                    </select>
                    <input name="hidparroquia" type="hidden" id="hidparroquia"></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td height="22" colspan="4" align="center"><input name="btndeducciones" type="button" class="boton" id="btndeducciones" onClick="javascript: uf_deducciones(document.form1.txtcedula.value,'N')" value="Deducciones" <?php print $ls_disabled ?>></td>
          </tr>
          <tr class="formato-blanco">
            <td height="22" colspan="4" align="center"><a href="#top">Volver Arriba</a> </td>
          </tr>
        </table>
      </div></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</form>
</body>

<script language="javascript">
f = document.form1;
function ue_nuevo()
{
    li_incluir=f.incluir.value;	
    if(li_incluir==1)
    {
		f.txtcedula.value="";
		f.txtcedula.readOnly=false;
		f.txtnombre.value="";
		f.cmbtipperrif[0].selected = true;
		f.txtnumpririf.value = "";
		f.txtnumterrif.value = "";
		f.txtnumpasben.value = "";
		f.txtapellido.value  = "";
		f.txtdireccion.value = "";
		f.txttelefono.value  = "";
		f.txtcelular.value   = "";
		f.txtemail.value="";
		f.txtcontable.value="";
		f.txtcontablerecdoc.value="";
		f.txtcuenta.value="";
		f.txtdencuenta.value="";
		f.txtdencuentarecdoc.value="";
		f.hidestatus.value="NUEVO";
		f.cmbbanco[0].selected=true;
		f.cmbpais[0].selected=true;
		f.cmbestado[0].selected=true; 
		f.cmbmunicipio[0].selected=true;
		f.cmbparroquia[0].selected=true;
		f.cmbtipcue[0].selected=true;
		f.cmbcontribuyente[0].selected=true;
		f.txtcedula.focus(); 
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_guardar()
{
  var resul="";
  
  li_incluir=f.incluir.value;
  li_cambiar=f.cambiar.value;
  evento    =f.hidestatus.value;
  if (((evento=="NUEVO")&&(li_incluir==1))||(evento=="GRABADO")&&(li_cambiar==1))
     {  	
       with (document.form1)
            {
              if (valida_null(txtcedula,"La Cédula esta vacia!!")==false)
                 {
                   txtcedula.focus();
                 }
              else
                 {
	               if (valida_null(txtnombre,"El Nombre esta vacio!!")==false)
	                  {
	                    txtnombre.focus();
	                  }
	               else
	                  {
                        if (valida_null(txtapellido,"El Apellido esta vacio!!")==false)
  		                   {
 	                         txtapellido.focus();
	                       }
		                else
		                   {
		                     if (valida_null(txtdireccion,"La Dirección esta vacia!!")==false)
		                        {
    		                      txtdireccion.focus();
	 	                        }
		                     else
		                        {
		                          if (valida_null(txtcontable,"La Código Contable esta vacia!!")==false) 
	   		                         {
	   	                               txtcontable.focus();
		                             }
			                      else
									 {	 	
									   if (f.cmbpais.value!='---' && (f.cmbestado.value=='---' || f.cmbmunicipio.value=='---' || f.cmbparroquia.value=='---'))
										  {
										    alert("Debe completar la Ubicación Geográfica !!!");
										  }
									   else
										  {
										    if (f.cmbestado.value!='---' && (f.cmbmunicipio.value=='---' || f.cmbparroquia.value=='---'))
											   {
												 alert("Debe completar la Ubicación Geográfica !!!");
											   }
										    else
											   {
												 if (f.cmbmunicipio.value!='---' && f.cmbparroquia.value=='---') 
												    {
													  alert("Debe completar la Ubicación Geográfica !!!");
												    }
												 else
												    {
													  f.operacion.value="GUARDAR";
													  f.action="sigesp_rpc_d_beneficiario.php";
													  f.submit();
												    }
											  } 
										  }
									 }
                                }
                           }
                      }
                 }
		    } 
     }
   else
     {
	   alert("No tiene permiso para realizar esta operación !!!");
	 }
}

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

function ue_eliminar()
{
   var borrar="";

   li_eliminar=f.eliminar.value;
   if(li_eliminar==1)
   {		
		if (f.txtcedula.value=="")
		{
		   alert("No ha seleccionado ningún registro para eliminar !!!");
		}
		else
		{
			borrar=confirm("¿ Esta seguro de eliminar este registro ?");
			if (borrar==true)
			   { 
				  f.operacion.value="ELIMINAR";
				  f.action="sigesp_rpc_d_beneficiario.php";
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

//UBICACIÓN GEOGRÁFICA
function uf_cambiopais()
{
	f.action="sigesp_rpc_d_beneficiario.php";
	f.operacion.value="pais";
	f.submit();
}

function uf_cambioestado()
{
	f.action="sigesp_rpc_d_beneficiario.php";
	f.operacion.value="estado";
	f.submit();
}

function uf_cambiomunicipio()
{
	f.action="sigesp_rpc_d_beneficiario.php";
	f.operacion.value="municipio";
	f.submit();
}
//FIN DE UBICACIÓN GEOGRÁFICA

function uf_cambiobanco()
{
  f.action="sigesp_rpc_d_beneficiario.php";
  f.operacion.value="$ls_nomban";
  activar();
}

function catalogo_cuentas(tipo)
{
	f.operacion.value="";			
	pagina="sigesp_catdinamic_ctas.php?tipo="+tipo;
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no");
} 	

function ue_buscar()
{
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		f.operacion.value="";			
		pagina="sigesp_catdinamic_bene.php";
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function rellenar_cad(cadena,longitud,as_objeto)
{
	var mystring = new String(cadena);
	cadena_ceros = "";
	lencad       = mystring.length;
    total        = longitud-lencad;
	if (cadena!="")
	   {
	     for (i=1;i<=total;i++)
		     {
			   cadena_ceros=cadena_ceros+"0";
		     }
		 cadena=cadena_ceros+cadena;
		 if (as_objeto=='txtnumpririf')
	        {
		      document.form1.txtnumpririf.value=cadena;
		    }
		 else
		    {
			  document.form1.txtcodbancof.value=cadena;
			}
	   }
}

/*Function:  catalogo_BCOSIGECOF()
	 *
	 *Descripción: Función que se encarga de hacer el llamado al catalogo de las cuentas contables*/   
function catalogo_BCOSIGECOF()
{
	f.operacion.value="";			
	pagina="sigesp_rpc_cat_bancos_sigecof.php";
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
} 		
/*Fin de la Función catalogo_BCOSIGECOF()*/

function cambio_nacionalidad()
{
  if (f.radionacionalidad[0].checked==true)
     {
	   f.txtcedula.value = "";
	 }
   else
     {
	   f.txtcedula.value = "";
	   f.txtcedula.value = "E";
	 }
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

function uf_deducciones(beneficiario,tipperpro)
{
	f=document.form1;
	if (beneficiario=="")
	{
		alert("Debe seleccionar previamente un Beneficiario Válido !!!");
	}
	else
	{
		ls_nombre=f.txtnombre.value;
		pagina="sigesp_rpc_w_benexded.php?cedbene="+beneficiario+"&tipperpro="+tipperpro+"&nombre="+ls_nombre;
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,resizable=yes,location=no");
	}
}

</script>
</html>