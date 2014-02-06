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
	require_once("class_folder/class_funciones_scf.php");
	$io_fun_scf=new class_funciones_scf("../");
	$io_fun_scf->uf_load_seguridad("SCF","sigesp_scf_r_comprobantes.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
    $ld_fecdes="01/01/".substr($_SESSION["la_empresa"]["periodo"],0,4);
    $ld_fechas=date("d/m/Y");
	//$lb_valido=$io_fun_scf->uf_convertir_scgsaldos($la_seguridad);
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
<title>Reporte de Comprobantes</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_scf.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../spg/js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
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
</style>
<link href="css/scf.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {font-weight: bold}
.Estilo2 {font-size: 14px}
-->
</style>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu">
	<table width="777" border="0" bgcolor="#E7E7E7" align="center" cellpadding="0" cellspacing="0">
		
	  <td width="405" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema
		de Contabilidad Fiscal</td>
		<td width="366" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequeñas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript:ue_openexcel();"><img src="../shared/imagebank/tools20/excel.jpg" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<form name="formulario" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_scf->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_scf);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
  <table width="650" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td height="136"><p>&nbsp;</p>
          <table width="600" height="22" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
            <tr>
              <td width="98"></td>
            </tr>
            <tr class="titulo-ventana">
              <td height="22" colspan="4" align="center">Reporte de Comprobantes </td>
            </tr>
            <tr>
              <td height="15" colspan="4" align="center">&nbsp;</td>
            </tr>
            <tr style="display:none">
              <td height="13" align="center"><div align="right">Reporte en </div></td>
              <td height="22" colspan="3" align="center"><div align="left">
                  <select name="cmbbsf" id="cmbbsf">
                    <option value="0" selected>Bs.</option>
                    <option value="1">Bs.F.</option>
                  </select>
              </div></td>
            </tr>
            <tr class="titulo-celdanew">
              <td height="22" colspan="4" align="center">Rango de Comprobantes</td>
            </tr>
            <tr>
              <td height="22" align="center"><div align="right">Desde</div></td>
              <td align="center"><div align="left">
                  <input name="txtcomprobantedesde" type="text" id="txtcomprobantedesde" size="22">
              <a href="javascript:ue_buscarcomprobante('REPDES')"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Cuentas" width="15" height="15" border="0"></a></div></td>
              <td align="center"><div align="right">Hasta</div></td>
              <td align="center"><div align="left">
                  <input name="txtcomprobantehasta" type="text" id="txtcomprobantehasta" size="22">
              <a href="javascript:ue_buscarcomprobante('REPHAS')"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Cuentas" width="15" height="15" border="0"></a></div></td>
            </tr>
            <tr>
              <td height="22" colspan="4" align="center" class="titulo-celdanew">Rango de Procedes</td>
            </tr>
            <tr>
              <td height="22" align="center"><div align="right">Desde</div></td>
              <td align="center"><div align="left">
                <input name="txtprocededesde" type="text" id="txtprocededesde" size="22" maxlength="20">
                <a href="javascript:ue_buscarprocede('REPDES')"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Cuentas" width="15" height="15" border="0"></a></div></td>
              <td align="center"><div align="right">Hasta</div></td>
              <td align="center"><div align="left">
                <input name="txtprocedehasta" type="text" id="txtprocedehasta" size="22" maxlength="6">
                <a href="javascript:ue_buscarprocede('REPHAS')"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Cuentas" width="15" height="15" border="0"></a></div></td>
            </tr>
            <tr class="titulo-celdanew">
              <td height="22" colspan="4" align="center">Rango de Fechas </td>
            </tr>
            <tr>
              <td height="22" align="center"><div align="right">Desde</div></td>
              <td height="22" align="center"><div align="left">
                  <input name="txtfecdes" type="text" id="txtfecdes"  onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print  $ld_fecdes; ?>" size="22" maxlength="10"  datepicker="true">
              </div></td>
              <td height="22" align="center"><div align="right">Hasta</div></td>
              <td height="22" align="center"><div align="left">
                  <input name="txtfechas" type="text" id="txtfechas"  onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print  $ld_fechas; ?>" size="22" maxlength="10"  datepicker="true">
              </div></td>
            </tr>
            <tr class="titulo-celdanew">
              <td height="22" colspan="4" align="center">Ordenado Por </td>
            </tr>
            <tr>
              <td height="22" align="center"><div align="right">
                <input name="rdborden" type="radio" class="sin-borde" value="1" checked >
              </div></td>
              <td align="center"><div align="left">Procede-Comprobante-Fecha</div></td>
              <td align="center">&nbsp;</td>
              <td align="center">&nbsp;</td>
            </tr>
            <tr>
              <td height="22" align="center"><div align="right">
                <input name="rdborden" type="radio" class="sin-borde" value="2">
              </div></td>
              <td align="center"><div align="left">Comprobante-Fecha-Procede</div></td>
              <td align="center">&nbsp;</td>
              <td align="center">&nbsp;</td>
            </tr>
            <tr>
              <td height="22" align="center"><div align="right">
                <input name="rdborden" type="radio" class="sin-borde" value="3">
              </div>
                  <div align="left"></div></td>
              <td width="152" align="center"><div align="left">Fecha-Procede-Comprobante</div></td>
              <td width="85" align="center">&nbsp;</td>
              <td width="198" align="center">&nbsp;</td>
            </tr>
            <tr>
              <td height="22" colspan="4" align="center"></td>
            </tr>
          </table>
        <p>&nbsp;</p></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>      
</body>
<script language="JavaScript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
function ue_search()
{
	f=document.formulario;
	li_imprimir=f.imprimir.value;
	valido=true;
	if(li_imprimir==1)
	{	
		fecdes=f.txtfecdes.value;
		fechas=f.txtfechas.value;
		comprobantedesde=f.txtcomprobantedesde.value;
		comprobantehasta=f.txtcomprobantehasta.value;
		procededesde=f.txtprocededesde.value;
		procedehasta=f.txtprocedehasta.value;
		tiporeporte=f.cmbbsf.value;
		if(f.rdborden[0].checked)
		{
			orden="1";
		}
		if(f.rdborden[1].checked)
		{
			orden="2";
		}
		if(f.rdborden[2].checked)
		{
			orden="3";
		}
		if((fecdes=="")||(fechas==""))
		{
			alert("Debe colocar un rango de fechas.");
			valido=false;
		}
		if(comprobantedesde>comprobantehasta)
		{
			alert("Intervalo de Comprobantes incorrecto.");
			valido=false;
		}
		if(procededesde>procedehasta)
		{
			alert("Intervalo de Procedes incorrecto.");
			valido=false;
		}
		if(valido)
		{
			pagina="reportes/sigesp_scf_rpp_comprobantes.php?fecdes="+fecdes+"&fechas="+fechas+"&orden="+orden;
			pagina=pagina+"&comprobantedesde="+comprobantedesde+"&comprobantehasta="+comprobantehasta;
			pagina=pagina+"&procededesde="+procededesde+"&procedehasta="+procedehasta+"&tiporeporte="+tiporeporte;
			window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación");
   	}		
}

function ue_openexcel()
{
	f=document.formulario;
	li_imprimir=f.imprimir.value;
	valido=true;
	if(li_imprimir==1)
	{	
		fecdes=f.txtfecdes.value;
		fechas=f.txtfechas.value;
		comprobantedesde=f.txtcomprobantedesde.value;
		comprobantehasta=f.txtcomprobantehasta.value;
		procededesde=f.txtprocededesde.value;
		procedehasta=f.txtprocedehasta.value;
		tiporeporte=f.cmbbsf.value;
		if(f.rdborden[0].checked)
		{
			orden="1";
		}
		if(f.rdborden[1].checked)
		{
			orden="2";
		}
		if(f.rdborden[2].checked)
		{
			orden="3";
		}
		if((fecdes=="")||(fechas==""))
		{
			alert("Debe colocar un rango de fechas.");
			valido=false;
		}
		if(comprobantedesde>comprobantehasta)
		{
			alert("Intervalo de Comprobantes incorrecto.");
			valido=false;
		}
		if(procededesde>procedehasta)
		{
			alert("Intervalo de Procedes incorrecto.");
			valido=false;
		}
		if(valido)
		{
			pagina="reportes/sigesp_scf_rpp_comprobantes_excel.php?fecdes="+fecdes+"&fechas="+fechas+"&orden="+orden;
			pagina=pagina+"&comprobantedesde="+comprobantedesde+"&comprobantehasta="+comprobantehasta;
			pagina=pagina+"&procededesde="+procededesde+"&procedehasta="+procedehasta+"&tiporeporte="+tiporeporte;
			window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación");
   	}		
}
   
function ue_cerrar()
{
	location.href = "sigespwindow_blank.php";
}

function ue_buscarcomprobante(tipo)
{
	window.open("sigesp_scf_cat_comprobante.php?tipo="+tipo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=620,height=400,resizable=yes,location=no");
}

function ue_buscarprocede(tipo)
{
	window.open("sigesp_scf_cat_procede.php?tipo="+tipo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js" ></script>
</html>