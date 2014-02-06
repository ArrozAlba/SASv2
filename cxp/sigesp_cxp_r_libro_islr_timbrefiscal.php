<?php
	session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_ano=date('Y');
	$ls_mes=date('m');
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$io_fun_cxp->uf_load_seguridad("CXP","sigesp_cxp_r_libro_islr_timbrefiscal.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_reporte1=$io_fun_cxp->uf_select_config("CXP","REPORTE","LIBRO_ISLR","sigesp_cxp_rpp_libro_islr.php","C");
	$ls_reporte2=$io_fun_cxp->uf_select_config("CXP","REPORTE","LIBRO_TIMBRE_FISCAL","sigesp_cxp_rpp_libro_timbrefiscal.php","C");
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$ls_estempcon=$_SESSION["la_empresa"]["estempcon"];
	if($ls_estempcon!=1)
	{
		print("<script language=JavaScript>");
		print(" alert('Este reporte esta desactivado para bases de datos que no sean consolidadoras.');");
		print(" location.href='sigespwindow_blank.php'");
		print("</script>");
	}	
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Libro I.S.L.R. / Timbre Fiscal</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_cxp.js"></script>

<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="css/cxp.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<script language="javascript" src="js/funcion_cxp.js"></script>
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
-->
</style></head>
<body>
<table width="793" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="805" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			
          <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Cuentas por Pagar </td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	  <tr>
	  	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	    <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
    </table>    </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="26" class="toolbar"><div align="center"><a href="javascript: ue_print();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0" title="Imprimir"></a></div></td>
    <td class="toolbar" width="26"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"></a></div></td>
    <td class="toolbar" width="26"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" border="0" title="Ayuda"></a></div></td>
    <td class="toolbar" width="26"><div align="center"></div></td>
    <td class="toolbar" width="26">&nbsp;</td>
    <td class="toolbar" width="26"><div align="center"></div></td>
    <td class="toolbar" width="26"><div align="center"></div></td>
    <td class="toolbar" width="26"><div align="center"></div></td>
    <td class="toolbar" width="26"><div align="center"></div></td>
    <td class="toolbar" width="26"><div align="center"></div></td>
    <td class="toolbar" width="531">&nbsp;</td>
  </tr>
</table>
</div> 
<p>&nbsp;	</p>
<form name="formulario" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_cxp->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_cxp);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="228"></td>
    </tr>
    <tr class="titulo-ventana">
      <td height="22" colspan="4" align="center">Libro I.S.L.R. / Timbre Fiscal</td>
    </tr>
    <tr style="visibility:hidden">
      <td height="22" colspan="4" align="center" ></div></td>
    </tr>
    <tr>
      <td height="22" align="center"><div align="right">Mes</div></td>
      <td width="102" height="22" align="center"><div align="left">
        <select name="cmbmes" id="cmbmes">
          <option value="01">ENERO</option>
          <option value="02">FEBRERO</option>
          <option value="03">MARZO</option>
          <option value="04">ABRIL</option>
          <option value="05">MAYO</option>
          <option value="06">JUNIO</option>
          <option value="07">JULIO</option>
          <option value="08">AGOSTO</option>
          <option value="09">SEPTIEMBRE</option>
          <option value="10">OCTUBRE</option>
          <option value="11">NOVIEMBRE</option>
          <option value="12">DICIEMBRE</option>
        </select>
</div></td>
      <td width="63" height="22" align="center"><div align="right">A&ntilde;o</div></td>
      <td width="355" align="center"><div align="left">
        <input name="txtano" type="text" id="txtano" value="<?php print $ls_ano;?>" size="6" maxlength="4" readonly>
</div></td>
    </tr>
   <tr>
   	<td height="22" align="center"><div align="right">Tipo Reporte</div></td>
      <td width="102" height="22" align="center"><div align="left">
        <select name="cmbtiprep" id="cmbtiprep">
		  <option value="" selected="selected">-- Seleccione Uno --</option>
	      <option value="1">LIBRO I.S.L.R.</option>
          <option value="2">LIBRO TIMBRE FISCAL</option>
        </select>
	</div></td>
    </tr>
    <tr>
      <td colspan="4" align="center">
  		<div id="resultados" align="center"></div>	</td>
    </tr>
  </table>
	<input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
    <input name="formato1"    type="hidden" id="formato1"    value="<?php print $ls_reporte1; ?>">
	<input name="formato2"    type="hidden" id="formato2"    value="<?php print $ls_reporte2; ?>">
</form>      
</body>
<script language="JavaScript">

	
function ue_print()
{
	f=document.formulario;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{
		
		ls_mes=f.cmbmes.value;
		ls_anio=f.txtano.value;
		ls_tiprep=f.cmbtiprep.value;
		if (ls_tiprep=='1')
		{			
			formato=f.formato1.value;
			pagina="reportes/"+formato+"?mes="+ls_mes+"&anio="+ls_anio;
			window.open(pagina,"reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no,left=0,top=0");
		}
		else if (ls_tiprep=='2')
		{
			formato=f.formato2.value;
			pagina="reportes/"+formato+"?mes="+ls_mes+"&anio="+ls_anio;
			window.open(pagina,"reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no,left=0,top=0");  
		}
		else
		{
			alert("Debe seleccionar una Opcion de Reporte");
		}	  
	}
	else
	{
		alert("No tiene permiso para realizar esta operación");
	}
}


function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}
</script>
</html>