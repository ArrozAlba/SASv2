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
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_r_conceptos.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	$ls_reporte=$io_sno->uf_select_config("SNR","REPORTE","CONSOLIDADO_CONCEPTOS","sigesp_snorh_rpp_conceptos.php","C");
	unset($io_sno);		
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
<title >Reporte Consolidado de Conceptos</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Nómina</td>
			<td width="346" bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
        </table>	 </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript:ue_print();"><img src="../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_gendisk();"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_descargar('<?php print $ls_ruta;?>');"></a><img src="../shared/imagebank/tools20/ayuda.gif"  title="Ayuda" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
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
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="650" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="600" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Reporte Consolidado de Conceptos </td>
        </tr>
        <tr class="formato-blanco">
          <td height="20" colspan="4">&nbsp;</td>
        </tr>
        <tr class="formato-blanco" style="display:none">
          <td height="20"> <div align="right">Reporte en
            
          </div></td>
          <td height="20"><div align="left">
            <select name="cmbbsf" id="cmbbsf">
              <option value="0" selected>Bs.</option>
              <option value="1">Bs.F.</option>
            </select>
          </div></td>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
        </tr>
        <tr>
          <td width="168" height="22"><div align="right">N&oacute;mina Desde </div></td>
          <td><div align="left">
            <input name="txtcodnomdes" type="text" id="txtcodnomdes" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarnominadesde();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td><div align="right">N&oacute;mina Hasta </div></td>
          <td><div align="left">
            <input name="txtcodnomhas" type="text" id="txtcodnomhas" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarnominahasta();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
        <tr>
          <td height="20"><div align="right"> Subn&oacute;mina Desde </div></td>
          <td height="20"><input name="txtcodsubnomdes" type="text" id="txtcodsubnomdes" size="13" maxlength="10" value="" readonly>
            <a href="javascript: ue_buscarsubnominadesde();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></td>
          <td height="20"><div align="right">Subn&oacute;mina Hasta </div></td>
          <td height="20"><input name="txtcodsubnomhas" type="text" id="txtcodsubnomhas" value="" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarsubnominahasta();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Personal Desde </div></td>
          <td><div align="left">
            <input name="txtcodperdes" type="text" id="txtcodperdes" size="13" maxlength="10" value="" readonly>
            <a href="javascript: ue_buscarpersonaldesde();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td><div align="right">Personal Hasta </div></td>
          <td><div align="left">
            <input name="txtcodperhas" type="text" id="txtcodperhas" value="" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarpersonalhasta();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right"> Concepto Desde </div></td>
          <td><div align="left">
            <input name="txtcodconcdes" type="text" id="txtcodconcdes" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarconceptodesde();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="personal" width="15" height="15" border="0" id="personal"></a></div></td>
          <td><div align="right">Concepto Hasta </div></td>
          <td><div align="left">
            <input name="txtcodconchas" type="text" id="txtcodconchas" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarconceptohasta();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="personal" width="15" height="15" border="0" id="personal"></a></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tipo de Concepto </div></td>
          <td colspan="3"><div align="left"><a href="javascript: ue_buscarmeses();"></a>
            <label>
            <select name="cmbtipconc" id="cmbtipconc">
              <option value="" selected>--Seleccione--</option>
              <option value="A">Asignaci&oacute;n</option>
              <option value="D">Deducci&oacute;n</option>
              <option value="P">Aporte Patronal</option>
              <option value="B">Reintegro Deducci&oacute;n</option>
              <option value="E">Reintegro Asignaci&oacute;n</option>
			  <option value="R">Reporte</option>
            </select>
            </label>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Periodo Desde </div></td>
          <td><div align="left">
            <input name="txtperdes" type="text" id="txtperdes" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarperiododesde();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="periodo" width="15" height="15" border="0" id="periodo"></a></div></td>
          <td><div align="right">Periodo Hasta </div></td>
          <td><div align="left">
            <input name="txtperhas" type="text" id="txtperhas" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarperiodohasta();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="periodo" width="15" height="15" border="0" id="periodo"></a>
            <input name="txtfecpro" type="hidden" id="txtfecpro">
          </div></td>
        </tr>
        <tr class="titulo-celdanew">
          <td height="20" colspan="4">&nbsp;</td>
        </tr>
        <tr class="formato-blanco">
          <td height="20" ><div align="right">Quitar conceptos en cero</div></td>
          <td height="20"><div align="left">
            <input name="chkconceptocero" type="checkbox" class="sin-borde" id="chkconceptocero" value="1" checked>
          </div></td>
          <td height="20" >&nbsp;</td>
          <td height="20" >&nbsp;</td>
        </tr>
        <tr class="titulo-celdanew">
          <td height="20" colspan="4"><div align="right" class="titulo-celdanew">Ordenado por </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo del Personal </div></td>
          <td width="125"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="1" checked>
          </div></td>
          <td width="124"><div align="right">Apellido del Personal</div></td>
          <td width="173"><div align="left">            <input name="rdborden" type="radio" class="sin-borde" value="2">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nombre del Personal</div></td>
          <td>            <div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="3">
          </div></td>
          <td><div align="right">C&eacute;dula del Personal</div></td>
          <td><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="4">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right"></div></td>
          <td colspan="3"><div align="right">
            <input name="operacion" type="hidden" id="operacion">
            <input name="reporte" type="hidden" id="reporte" value="<?php print $ls_reporte;?>">			
          </div></td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_cerrar()
{
	location.href = "sigespwindow_blank.php";
}

function ue_print()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		codnomdes=f.txtcodnomdes.value;
		codnomhas=f.txtcodnomhas.value;
		codsubnomdes=f.txtcodsubnomdes.value;
		codsubnomhas=f.txtcodsubnomhas.value;
		reporte=f.reporte.value;
		tiporeporte=f.cmbbsf.value;
		if(codnomdes<=codnomhas)
		{
			personaldes=f.txtcodperdes.value;
			personalhas=f.txtcodperhas.value;
			if(personaldes<=personalhas)
			{
				codconcdes=f.txtcodconcdes.value;
				codconchas=f.txtcodconchas.value;
				if(codconcdes<=codconchas)
				{
					perdes=f.txtperdes.value;
					perhas=f.txtperhas.value;
					if(perdes<=perhas)
					{
						tipconc=f.cmbtipconc.value;
						if((codnomdes!="")&&(codconcdes!="")&&(perdes!=""))
						{
							conceptocero="";
							if(f.chkconceptocero.checked)
							{
								conceptocero=1;
							}
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
							if(f.rdborden[3].checked)
							{
								orden="4";
							}
							pagina="reportes/"+reporte+"?codnomdes="+codnomdes+"&codnomhas="+codnomhas+"&codconcdes="+codconcdes;
							pagina=pagina+"&codconchas="+codconchas+"&tipconc="+tipconc+"&codperdes="+perdes+"&codperhas="+perhas;
							pagina=pagina+"&conceptocero="+conceptocero+"&personaldes="+personaldes+"&personalhas="+personalhas+"&orden="+orden+"&tiporeporte="+tiporeporte;
							pagina=pagina+"&codsubnomdes="+codsubnomdes+"&codsubnomhas="+codsubnomhas;
							window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
						}
						else
						{
							alert("Debe seleccionar un Nómina, Concepto y Periodo.");
						}
					}
					else
					{
						alert("El Rango de Períodos es inválido.");
					}
				}
				else
				{
					alert("El Rango de conceptos es inválido.");
				}
			}
			else
			{
				alert("El Rango de personas es inválido.");
			}
		}
		else
		{
			alert("El Rango de Nóminas es inválido.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_buscarnominadesde()
{
	window.open("sigesp_snorh_cat_nomina.php?tipo=repconcdes","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarnominahasta()
{
	f=document.form1;
	if(f.txtcodnomdes.value!="")
	{
		window.open("sigesp_snorh_cat_nomina.php?tipo=repconchas","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar una nómina desde.");
	}
}

function ue_buscarconceptodesde()
{
	f=document.form1;
	codnomdes=f.txtcodnomdes.value;
	codnomhas=f.txtcodnomhas.value;
	if((f.txtcodnomdes.value!="")&&(f.txtcodnomhas.value!=""))
	{
		window.open("sigesp_sno_cat_concepto.php?tipo=repcondes&codnomdes="+codnomdes+"&codnomhas="+codnomhas+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar las nómina.");
	}
}

function ue_buscarconceptohasta()
{
	f=document.form1;
	codnomdes=f.txtcodnomdes.value;
	codnomhas=f.txtcodnomhas.value;
	codconcdes=f.txtcodconcdes.value;
	if((codconcdes!=""))
	{
		window.open("sigesp_sno_cat_concepto.php?tipo=repconhas&codnomdes="+codnomdes+"&codnomhas="+codnomhas+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un concepto Desde.");
	}
}

function ue_buscarperiododesde()
{
	f=document.form1;
	codnomdes=f.txtcodnomdes.value;
	codnomhas=f.txtcodnomhas.value;
	codconchas=f.txtcodconchas.value;
	if(codconchas!="")
	{
		window.open("sigesp_sno_cat_hperiodo.php?tipo=repcondes&codnom="+codnomdes+"&codnomhas="+codnomhas+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=200,top=200,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un rango de Conceptos.");
	}
}

function ue_buscarperiodohasta()
{
	f=document.form1;
	codnomdes=f.txtcodnomdes.value;
	codnomhas=f.txtcodnomhas.value;
	if(f.txtperdes.value!="")
	{
		window.open("sigesp_sno_cat_hperiodo.php?tipo=repconhas&codnom="+codnomdes+"&codnomhas="+codnomhas+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=200,top=200,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un período desde.");
	}
}

function ue_buscarpersonaldesde()
{
	window.open("sigesp_snorh_cat_personal.php?tipo=repconcdes","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarpersonalhasta()
{
	f=document.form1;
	if(f.txtcodperdes.value!="")
	{
		window.open("sigesp_snorh_cat_personal.php?tipo=repconchas","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un personal desde.");
	}
}
function ue_buscarsubnominadesde()
{
	f=document.form1;
	codnomdes=f.txtcodnomdes.value;
	codnomhas=f.txtcodnomhas.value;
	if((codnomdes==codnomhas)&&(codnomdes!=""))
	{
		window.open("sigesp_snorh_cat_subnomina.php?tipo=reportedesde&codnom="+codnomdes,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Para filtrar por Subnóminas La nómina desde y hasta debe ser la misma.");
	}
}

function ue_buscarsubnominahasta()
{
	f=document.form1;
	codsubnomdes=f.txtcodsubnomdes.value;
	codnomdes=f.txtcodnomdes.value;
	if(codsubnomdes!="")
	{
		window.open("sigesp_snorh_cat_subnomina.php?tipo=reportehasta&codnom="+codnomdes,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar una subnómina desde.");
	}
}

</script> 
</html>