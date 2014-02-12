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
	$io_fun_cxp->uf_load_seguridad("CXP","sigesp_cxp_r_retencionesespecifico.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_reporte=$io_fun_cxp->uf_select_config("CXP","REPORTE","FORMATO_RETENCION_ESP","sigesp_cxp_rpp_retencionespecifico.php","C");
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Reporte de Retenciones Especifico</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
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
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="804" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="786" border="0" align="center" cellpadding="0" cellspacing="0">
			
          <td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Cuentas por Pagar </td>
			<td width="363" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	  <tr>
	  	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	    <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </table>    </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_print();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0" title="Imprimir"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_openexcel();"><img src="../shared/imagebank/tools20/excel.jpg" title="Excel" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" border="0" title="Ayuda"></a></div></td>
    <td class="toolbar" width="25">&nbsp;</td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
</div> 
<p>&nbsp;	</p>
<form name="formulario" method="post" action="">
<input name="formato"    type="hidden" id="formato"    value="<?php print $ls_reporte; ?>">

<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_cxp->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_cxp);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="83"></td>
    </tr>
    <tr class="titulo-ventana">
      <td height="22" colspan="2" align="center">Reporte de Retenciones Especifico </td>
    </tr>
    <tr style="visibility:hidden">
      <td height="19" colspan="2" align="center"><div align="left">Reporte en
          <select name="cmbbsf" id="cmbbsf">
            <option value="0" selected>Bs.</option>
            <option value="1">Bs.F.</option>
          </select>
</div></td>
    </tr>
    <tr>
      <td height="19" align="center"><div align="right"><strong>Deducciones</strong></div></td>
      <td width="515" height="19" align="center"><div align="left"></div></td>
    </tr>
    <tr>
      <td height="19" align="center"><div align="right">Desde</div></td>
      <td height="19" align="center"><div align="left">
        <input name="txtcodded" type="text" id="txtcodded" size="10"  style="text-align:center" readonly>
        <a href="javascript: ue_catalogo_deduccion('');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
        <label>
        <input name="txtdended" type="text" class="sin-borde" id="txtdended" size="60" readonly>
        </label>
</div></td>
    </tr>
    <tr>
      <td height="19" align="center"><div align="right">Hasta
      </div></td>
      <td height="19" align="center"><div align="left">
        <input name="txtcoddedhas" type="text" id="txtcoddedhas"  size="10" style="text-align:center" readonly>
        <a href="javascript: ue_catalogo_deduccion('rephas');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
        <input name="txtdendedhas" type="text" class="sin-borde" id="txtdendedhas" size="60" readonly>
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="2" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="2" align="center"><input name="rdtipded" type="radio" class="sin-borde" value="T" checked>
TODAS<input name="rdtipded" type="radio" class="sin-borde" value="S">
I.S.L.R.
  <input name="rdtipded" type="radio" class="sin-borde" value="I">
Ret. IVA
<input name="rdtipded" type="radio" class="sin-borde" value="M">
Ret. Municipal
<input name="rdtipded" type="radio" class="sin-borde" value="A" >
Ret. Aporte Social 
<input name="rdtipded" type="radio" class="sin-borde" value="O">
Otras </td>
    </tr>
    <tr>
      <td height="22" colspan="2" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="2" align="center"><table width="511" border="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td width="199" height="22"><div align="right">
            <input name="rdproben" type="radio" class="sin-borde" value="radiobutton" onClick="javascript: ue_limpiarproben();" checked>
            Todos</div></td>
          <td width="89" height="22"><div align="center">
                <input name="rdproben" type="radio" class="sin-borde" value="radiobutton" onClick="javascript: ue_limpiarproben();">
            Proveedor</div></td>
          <td width="215" height="22"><div align="left">
            <input name="rdproben" type="radio" class="sin-borde" value="radiobutton" onClick="javascript: ue_limpiarproben();">
          Beneficiario</div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Desde
            <input name="txtcoddes" type="text" id="txtcoddes" size="20" readonly>
            <a href="javascript: ue_catalogo_proben('REPDES');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a></div></td>
          <td><div align="right">Hasta</div></td>
          <td><input name="txtcodhas" type="text" id="txtcodhas" size="20" readonly>
            <a href="javascript: ue_catalogo_proben('REPHAS');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a></td>
        </tr>

      </table></td>
    </tr>
    <tr>
      <td height="33" colspan="2" align="center">Tipo de Persona:      
        <input name="rdtipper" type="radio" class="sin-borde" value="T" checked>
      Todas 
      <input name="rdtipper" type="radio" class="sin-borde" value="N" onClick="javascript: ue_validartipopersona();">
      Natural 
      <input name="rdtipper" type="radio" class="sin-borde" value="J" onClick="javascript: ue_validartipopersona();">
      Jurídica</td>
    </tr>
    <tr>
      <td height="33" colspan="2" align="center">      <div align="left">
        <table width="511" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td height="22" colspan="5"><strong>Rango de Fecha </strong></td>
            </tr>
          <tr>
            <td width="136"><div align="right">Desde</div></td>
            <td width="101"><input name="txtfecdes" type="text" id="txtfecdes"  onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" size="15" maxlength="10"  datepicker="true"></td>
            <td width="42"><div align="right">Hasta</div></td>
            <td width="129"><div align="left">
                <input name="txtfechas" type="text" id="txtfechas" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);"size="15" maxlength="10"  datepicker="true">
            </div></td>
            <td width="101">&nbsp;</td>
          </tr>
        </table>
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="2" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" align="center"></td>
    </tr>
  </table>
</form>      
</body>
<script language="JavaScript">
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_limpiarproben()
{
	f=document.formulario;
	f.txtcoddes.value="";
	f.txtcodhas.value="";
	f.rdtipper[0].checked = true 
}

function ue_catalogo_proben(ls_tipo)
{
	f=document.formulario;
	valido=true;		
	if(f.rdproben[0].checked)
	{
		valido=false;
	}
	if(f.rdproben[1].checked)
	{
		ls_catalogo="sigesp_cxp_cat_proveedor.php?tipo="+ls_tipo+"";
	}
	if(f.rdproben[2].checked)
	{
		ls_catalogo="sigesp_cxp_cat_beneficiario.php?tipo="+ls_tipo+"";
	}
	if(valido)
	{		
		window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("Debe indicar si es Proveedor ó Beneficiario");
	}
}

function ue_catalogo_deduccion(ls_destino)
{
	window.open("sigesp_cxp_cat_deduccion.php?tipo="+ls_destino+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}
	
function ue_print()
{
	f=document.formulario;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{
		if(f.rdproben[0].checked)
		{
			tipproben="";
		}
		else
		{
			if(f.rdproben[1].checked)
			{
				tipproben="P";
			}
			else
			{
				tipproben="B";
			}
		}
		if(f.rdtipper[0].checked)
		{
			tipper="T";
		}
		else
		{
			if(f.rdtipper[1].checked)
			{
				tipper="N";
			}
			else
			{
				tipper="J";
			}
		}
		if(f.rdtipded[0].checked)
		{
			tipded="T";
		}
		else
		{
			if(f.rdtipded[1].checked)
			{
				tipded="S";
			}
			else
			{
				if(f.rdtipded[2].checked)
				{
					tipded="I";
				}
				else
				{
					if(f.rdtipded[3].checked)
					{
						tipded="M";
					}
					else
					{
						if(f.rdtipded[4].checked)
						{
							tipded="A";
						}
						else
						{
							tipded="O";
						}
					}
				}
			}
		}
		codded=f.txtcodded.value;
		coddedhas=f.txtcoddedhas.value;
		dended=f.txtdended.value;
		codprobendes=f.txtcoddes.value;
		codprobenhas=f.txtcodhas.value;
		fecdes=f.txtfecdes.value;
		fechas=f.txtfechas.value;
		if((fecdes!="")&&(fechas!="")&&(codded!="")&&(coddedhas!=""))
		{
			formato=f.formato.value;
			tiporeporte=f.cmbbsf.value;
			pagina="reportes/"+formato+"?tipproben="+tipproben+"&codprobendes="+codprobendes+"&tipper="+tipper+"&tipded="+tipded;
			pagina=pagina+"&codprobenhas="+codprobenhas+"&fecdes="+fecdes+"&fechas="+fechas+"&codded="+codded+"&dended="+dended+"&tiporeporte="+tiporeporte+"&coddedhas="+coddedhas;
			window.open(pagina,"reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no,left=0,top=0");
		}
		else
		{
			alert("Debe indicar intervalo de fechas y deduccion");
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operación");
	}
}
function ue_openexcel()
{
	f = document.formulario;
	li_imprimir = f.imprimir.value;
	if(li_imprimir==1)
	{
		if(f.rdproben[0].checked)
		{
			tipproben="";
		}
		else
		{
			if(f.rdproben[1].checked)
			{
				tipproben="P";
			}
			else
			{
				tipproben="B";
			}
		}
		if(f.rdtipper[0].checked)
		{
			tipper="T";
		}
		else
		{
			if(f.rdtipper[1].checked)
			{
				tipper="N";
			}
			else
			{
				tipper="J";
			}
		}
		if(f.rdtipded[0].checked)
		{
			tipded="T";
		}
		else
		{
			if(f.rdtipded[1].checked)
			{
				tipded="S";
			}
			else
			{
				if(f.rdtipded[2].checked)
				{
					tipded="I";
				}
				else
				{
					if(f.rdtipded[3].checked)
					{
						tipded="M";
					}
					else
					{
						if(f.rdtipded[4].checked)
						{
							tipded="A";
						}
						else
						{
							tipded="O";
						}
					}
				}
			}
		}
		codded=f.txtcodded.value;
		coddedhas=f.txtcoddedhas.value;
		dended=f.txtdended.value;
		codprobendes=f.txtcoddes.value;
		codprobenhas=f.txtcodhas.value;
		fecdes=f.txtfecdes.value;
		fechas=f.txtfechas.value;
		if((fecdes!="")&&(fechas!="")&&(codded!="")&&(coddedhas!=""))
		{
			pagina="reportes/sigesp_cxp_rpp_retencionespecifico_excel.php?tipproben="+tipproben+"&codprobendes="+codprobendes+"&tipper="+tipper+"&tipded="+tipded;
			pagina=pagina+"&codprobenhas="+codprobenhas+"&fecdes="+fecdes+"&fechas="+fechas+"&codded="+codded+"&dended="+dended+"&coddedhas="+coddedhas;
			window.open(pagina,"reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no,left=0,top=0");
		}
		else
		{
			alert("Debe indicar intervalo de fechas y deduccion");
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operación");
	}
}

function ue_validartipopersona()
{
	f=document.formulario;
	valor=f.rdproben.value;
	if(f.rdproben[0].checked)
	{
		tipproben="";
	}
	else
	{
		if(f.rdproben[1].checked)
		{
			tipproben="P";
		}
		else
		{
			tipproben="B";
		}
	}
	if(tipproben!="P")
	{
		alert("Esta opcion solo es valida si selecciona proveedores");
		f.rdtipper[0].checked = true 
	}
}
function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}
</script>
</html>