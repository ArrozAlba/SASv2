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
	require_once("class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$io_fun_cxp->uf_load_seguridad("CXP","sigesp_cxp_p_solicituddesembolso.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title >Generar Recepciones de Documentos de Cr&eacute;ditos Aprobados </title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
}

a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_cxp.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>

<link href="css/cxp.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>
<?php 
	$ld_fecha=date("d/m/Y");
	require_once("class_folder/sigesp_cxp_c_recepcion.php");
	$io_cxprd=new sigesp_cxp_c_recepcion("../");
	require_once("class_folder/sigesp_cxp_c_solicituddesembolso.php");
	$io_cxp= new sigesp_cxp_c_solicituddesembolso("../");
	$ls_operacion=$io_fun_cxp->uf_obteneroperacion();
	if($ls_operacion=="PROCESAR")
	{
		$ls_codtipdoc=$_POST["cmbcodtipdoc"];
		$lb_valido=$io_cxp->uf_procesar_creditos("../scc/III/pendientes/",$ls_codtipdoc,$la_seguridad);
	}
	$io_cxp->uf_destructor();
?>
<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="1535" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="812" height="40"></td>
  </tr>
  <tr>
    <td width="780" height="20" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			
            <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Cuentas por Pagar </td>
			  <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
        </table>    </td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" class="toolbar"></td>
  </tr>
</table>
<p>&nbsp;</p>
<form name="formulario" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_cxp->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_cxp);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
  <table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
    <td width="760" height="136">
      <p>&nbsp;</p>
        <table width="741" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr> 
            <td colspan="4" class="titulo-ventana">Generar Recepciones de Documentos de Cr&eacute;ditos Aprobados </td>
          </tr>
          <tr> 
            <td width="22%" height="22"><div align="right"></div></td>
            <td width="60%" colspan="2"><div align="right">Fecha</div></td>
            <td width="18%"><input name="txtfecaprord" type="text" id="txtfecaprord" style="text-align:center" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" value="<?php print $ld_fecha; ?>" size="15"  datepicker="true"></td>
          </tr>
          <tr>
            <td height="22"><div align="right">Tipo de Documento</div></td>
            <td colspan="2"><?php $io_cxprd->uf_load_tipodocumento("","D");?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="22">&nbsp;</td>
            <td colspan="2">
                <div align="center">
                  <input name="Submit" type="button" class="boton" value="Generar Recepciones" onClick="javascript: ue_procesar();">
                </div></td>
            <td>&nbsp;</td>
          </tr>
        </table>
        <table width="740" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="748"><input name="operacion" type="hidden" id="operacion"></td>
          </tr>
          <tr>
            <td></td>
          </tr>
        </table>        </td>
  </tr>
</table>
</form>   
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_procesar()
{
	f=document.formulario;
	li_procesar=f.ejecutar.value;
	if (li_procesar==1)
   	{
		tipdoc=f.cmbcodtipdoc.value;
		if(tipdoc!="-")
		{
			f.operacion.value="PROCESAR";
			f.action="sigesp_cxp_p_solicituddesembolso.php";
			f.submit();		
		}
		else
		{
 			alert("Debe de seleccionar un tipo de documento");
		}
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}
</script> 
</html>