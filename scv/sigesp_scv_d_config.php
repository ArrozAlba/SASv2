<?php
session_start();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_folder/class_funciones_viaticos.php");
$io_fun_viaticos=new class_funciones_viaticos();
$io_fun_viaticos->uf_load_seguridad("SCV","sigesp_scv_d_config.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Configuraci&oacute;n de Vi&aacute;ticos </title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset="><style type="text/css">
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
.Estilo7 {
	font-size: 11px;
	color: #6699CC;
}
-->
</style></head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" colspan="7" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="7" class="cd-menu">
			<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
			
            <td width="432" height="20" bgcolor="#E7E7E7"><span class="Estilo7">Control de Viaticos </span></td>
			  <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
        </table>
</td>
  </tr>
  <tr>
    <td height="20" colspan="7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="7" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td width="21" height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"></a><a href="javascript:ue_guardar();"></a><a href="javascript:ue_buscar();"></a><a href="javascript:ue_eliminar();"></a><a href="javascript: ue_cerrar();"><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0" title="Guardar"></a></td>
    <td width="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_buscar();"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"></a></td>
    <td width="20" bgcolor="#FFFFFF" class="toolbar"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" title="Ayuda"></td>
    <td width="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_eliminar();"></a></td>
    <td width="20" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
    <td width="20" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
    <td width="657" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
</table>
<?php 
require_once("../shared/class_folder/sigesp_include.php");
$io_conect= new sigesp_include();
$conn=      $io_conect->uf_conectar();
require_once("../shared/class_folder/class_sql.php");
$io_sql= new class_sql($conn);
require_once("class_folder/sigesp_scv_c_config.php");
$io_scv= new sigesp_scv_c_config($conn);
require_once("../shared/class_folder/class_datastore.php");
$io_dsclas= new class_datastore();
require_once("../shared/class_folder/class_funciones.php");
$io_funcion= new class_funciones();
require_once("../shared/class_folder/class_funciones_db.php"); 
$io_funciondb= new class_funciones_db($conn);
require_once("../shared/class_folder/class_mensajes.php");
$io_msg= new class_mensajes();
$ls_codemp= $_SESSION["la_empresa"]["codemp"];
$lb_existe= "";

$ls_operacion= $io_fun_viaticos->uf_obteneroperacion();
$ls_spgnac=    $io_fun_viaticos->uf_obtenervalor("txtspgnac","");
$ls_denspgnac= $io_fun_viaticos->uf_obtenervalor("txtdenspgnac","");
$ls_spgint=    $io_fun_viaticos->uf_obtenervalor("txtspgint","");
$ls_denspgint= $io_fun_viaticos->uf_obtenervalor("txtdenspgint","");
$ls_scben=     $io_fun_viaticos->uf_obtenervalor("txtscben","");
$ls_scbenrd=     $io_fun_viaticos->uf_obtenervalor("txtscbenrd","");
$ls_denscben=  $io_fun_viaticos->uf_obtenervalor("txtdenscben","");
$ls_denscbenrd=  $io_fun_viaticos->uf_obtenervalor("txtdenscbenrd","");
$ls_estatus=   $io_fun_viaticos->uf_obtenervalor("hidestatus","");
$ls_type="C";
switch ($ls_operacion) 
{
	case "NUEVO":
		$lb_existe=$io_scv->uf_scv_load_config($ls_codemp,"SCV","CONFIG","NACIONALES",$ls_spgnac,$ls_denspgnac);
		if($lb_existe)
		{
			$lb_existe=$io_scv->uf_scv_load_config($ls_codemp,"SCV","CONFIG","INTERNACIONALES",$ls_spgint,$ls_denspgint);
			if($lb_existe)
			{
				$lb_existe=$io_scv->uf_scv_load_config($ls_codemp,"SCV","CONFIG","BENEFICIARIO",$ls_scben,$ls_denscben);
				$lb_existe=$io_scv->uf_scv_load_config($ls_codemp,"SCV","CONFIG","BENEFICIARIORD",$ls_scbenrd,$ls_denscbenrd);
			}
		}
	break;
	case "GUARDAR":
		$io_sql->begin_transaction();
		$lb_existe=$io_scv->uf_scv_select_config($ls_codemp,"SCV","CONFIG","NACIONALES");
		if($lb_existe)
		{
			$lb_valido=$io_scv->uf_update_scv_config($ls_codemp,"SCV","CONFIG","NACIONALES",$ls_type,$ls_spgnac,$la_seguridad);
		}
		else
		{
			$lb_valido=$io_scv->uf_insert_scv_config($ls_codemp,"SCV","CONFIG","NACIONALES",$ls_type,$ls_spgnac,$la_seguridad);
		}
		if($lb_valido)
		{
			$lb_existe=$io_scv->uf_scv_select_config($ls_codemp,"SCV","CONFIG","INTERNACIONALES");
			if($lb_existe)
			{
				$lb_valido=$io_scv->uf_update_scv_config($ls_codemp,"SCV","CONFIG","INTERNACIONALES",$ls_type,$ls_spgint,$la_seguridad);
			}
			else
			{
				$lb_valido=$io_scv->uf_insert_scv_config($ls_codemp,"SCV","CONFIG","INTERNACIONALES",$ls_type,$ls_spgint,$la_seguridad);
			}
			if($lb_valido)
			{
				$lb_existe=$io_scv->uf_scv_select_config($ls_codemp,"SCV","CONFIG","BENEFICIARIO");
				if($lb_existe)
				{
					$lb_valido=$io_scv->uf_update_scv_config($ls_codemp,"SCV","CONFIG","BENEFICIARIO",$ls_type,$ls_scben,$la_seguridad);
				}
				else
				{
					$lb_valido=$io_scv->uf_insert_scv_config($ls_codemp,"SCV","CONFIG","BENEFICIARIO",$ls_type,$ls_scben,$la_seguridad);
				}
			}
			if($lb_valido)
			{
				$lb_existe=$io_scv->uf_scv_select_config($ls_codemp,"SCV","CONFIG","BENEFICIARIORD");
				if($lb_existe)
				{
					$lb_valido=$io_scv->uf_update_scv_config($ls_codemp,"SCV","CONFIG","BENEFICIARIORD",$ls_type,$ls_scbenrd,$la_seguridad);
				}
				else
				{
					$lb_valido=$io_scv->uf_insert_scv_config($ls_codemp,"SCV","CONFIG","BENEFICIARIORD",$ls_type,$ls_scbenrd,$la_seguridad);
				}
			}
		}
		if($lb_valido)
		{
			$io_sql->commit();
			$io_msg->message("La Configuración de Viáticos ha sido procesada");
		}
		else
		{
			$io_sql->rollback();
			$io_msg->message("No se ha podido procesar la Configuración de Viáticos");
		}
	break;
}
?>
<p align="center"><font size="4" face="Verdana, Arial, Helvetica, sans-serif"></font></p>
<p align="center">&nbsp;</p>
<form name="form1" method="post" action="">
  <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_viaticos->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_viaticos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="683" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="670" height="170"><div align="center">
        <table width="668"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-celdanew">
            <td height="22" colspan="2" class="titulo-ventana">Configuraci&oacute;n de Vi&aacute;ticos </td>
          </tr>
          <tr>
            <td height="22" >&nbsp;</td>
            <td height="22" ><input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_estatus ?>">
              <input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion?>"></td>
          </tr>
          <tr>
            <td width="170" height="22" ><div align="right">Cuenta Vi&aacute;ticos Nacionales </div></td>
            <td width="471" height="22" ><input name="txtspgnac" type="text" id="txtspgnac" value="<?php print  $ls_spgnac; ?>" size="25" style="text-align:center " readonly>
              <a href="javascript: ue_buscarspg('NACIONALES');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
              <input name="txtdenspgnac" type="text" class="sin-borde" id="txtdenspgnac" value="<?php print  $ls_denspgnac; ?>" size="55" readonly></td>
          </tr>
          <tr>
            <td height="22"><div align="right">Cuenta Vi&aacute;ticos Internacionales </div></td>
            <td height="22"><input name="txtspgint" id="txtspgint" value="<?php print $ls_spgint; ?>" type="text" size="25"  style="text-align:center " readonly>
              <a href="javascript: ue_buscarspg('INTERNACIONALES');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
              <input name="txtdenspgint" type="text" class="sin-borde" id="txtdenspgint" value="<?php print $ls_denspgint; ?>" size="55" readonly></td>
          </tr>
          <tr> 
            <td height="22"><div align="right">Cuenta Contable de Beneficiario </div></td>
            <td height="22"><input name="txtscben" type="text" id="txtscben"  style="text-align:center " value="<?php print $ls_scben; ?>" size="25" readonly>
              <a href="javascript: ue_buscarcontable('');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
              <input name="txtdenscben" type="text" class="sin-borde" id="txtdenscben" value="<?php print $ls_denscben; ?>" size="55" readonly></td>
          </tr>
          <tr>
            <td height="22"><div align="right">Cuenta Contable para Registro del Gasto por Pagar </div></td>
            <td height="22"><input name="txtscbenrd" type="text" id="txtscbenrd"  style="text-align:center " value="<?php print $ls_scbenrd; ?>" size="25" readonly>
              <a href="javascript: ue_buscarcontable('RD');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
              <input name="txtdenscbenrd" type="text" class="sin-borde" id="txtdenscbenrd" value="<?php print $ls_denscbenrd; ?>" size="55" readonly></td>
          </tr>
          <tr>
            <td height="22">&nbsp;</td>
            <td height="22">&nbsp;</td>
          </tr>
        </table>
      </div></td>
    </tr>
  </table>
  </div>
    </table>
  </div>
</form>
</body>

<script language="JavaScript">
function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	evento=f.hidestatus.value;
	if(((evento=="")&&(li_incluir==1))||(evento=="C")&&(li_cambiar==1))
	{  	
		ls_spgnac= f.txtspgnac.value;
		ls_spgint= f.txtspgnac.value;
		ls_scben=  f.txtscben.value;
		if((ls_spgnac!="")&&(ls_spgint!="")&&(ls_scben!=""))
		{
		   f=document.form1;
		   f.operacion.value="GUARDAR";
		   f.action="sigesp_scv_d_config.php";
		   f.submit();
		}
		else
		{
			alert("Debe completar los datos");
		}
	}	
	else
	{
		 alert("No tiene permiso para realizar esta operacion");
	}		
}			

function ue_buscarspg(ls_destino)	
{
	 window.open("sigesp_scv_cat_cuentapresupuesto.php?tipo="+ls_destino+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,left=50,top=50,resizable=yes,location=no");
}	

function ue_buscarcontable(ls_destino)	
{
	 window.open("sigesp_scv_cat_cuentacontable.php?tipo="+ls_destino+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,left=50,top=50,resizable=yes,location=no");
}	
					

function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}

</script>
</html>