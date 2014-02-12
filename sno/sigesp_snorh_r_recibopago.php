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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_r_recibopago.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	$ls_recibo=$io_sno->uf_select_config("SNR","REPORTE","RECIBO_PAGO","sigesp_snorh_rpp_recibopago.php","C");
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
<title >Reporte Recibo de Pago</title>
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
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7"><table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Nómina</td>
			<td width="346" bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
        </table></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript:ue_print();"><img src="../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_enviarcorreo();"><img src="../shared/imagebank/tools20/email.png" title="Enviar por Correo" alt="Enviar por Correo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></div></td>
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
<table width="600" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="550" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Reporte Recibo de Pago </td>
        </tr>
        <tr style="display:none">
          <td height="20"><div align="right">Reporte en</div></td>
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
          <td height="20" colspan="4" class="titulo-celdanew">N&oacute;mina</td>
          </tr>
        <tr>
          <td height="20"><div align="right">N&oacute;mina</div></td>
          <td height="20" colspan="3"><div align="left">
            <input name="txtcodnom" type="text" id="txtcodnom" size="8" maxlength="4" readonly>
            <a href="javascript: ue_buscarnomina();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <label>
            <input name="txtdesnom" type="text" class="sin-borde" id="txtdesnom" size="50" readonly>
            </label>
            <input name="txttipnom" type="hidden" id="txttipnom">
          </div>            <div align="left"><a href="javascript: ue_buscarnominahasta();"></a></div></td>
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
          <td height="20"><div align="right">Periodo Desde </div></td>
          <td height="20"><div align="left">
            <input name="txtperdes" type="text" id="txtperdes" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarperiododesde();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="periodo" width="15" height="15" border="0" id="periodo"></a>
            <input name="txtfecdesper" type="hidden" id="txtfecdesper">
</div></td>
          <td height="20"><div align="right">Periodo Hasta </div></td>
          <td height="20"><div align="left">
            <input name="txtperhas" type="text" id="txtperhas" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarperiodohasta();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="periodo" width="15" height="15" border="0" id="periodo"></a>
            <input name="txtfechasper" type="hidden" id="txtfechasper">
</div></td>
        </tr>
        <tr>
          <td height="20" colspan="4" class="titulo-celdanew">Intervalo de Personal </td>
          </tr>
        <tr>
          <td width="133" height="22"><div align="right"> Desde </div></td>
          <td width="112"><div align="left">
            <input name="txtcodperdes" type="text" id="txtcodperdes" size="13" maxlength="10" value="" readonly>
            <a href="javascript: ue_buscarpersonaldesde();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td width="119"><div align="right">Hasta </div></td>
          <td width="121"><div align="left">
            <input name="txtcodperhas" type="text" id="txtcodperhas" value="" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarpersonalhasta();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
        <tr>
          <td height="20" colspan="4" class="titulo-celdanew">&nbsp;</td>
          </tr>
        <tr>
          <td height="22"><div align="right">Quitar conceptos en cero </div></td>
          <td><div align="left">
            <input name="chkconceptocero" type="checkbox" class="sin-borde" id="chkconceptocero" value="1" checked>
          </div></td>
          <td><div align="right">Mostrar Concepto P2</div></td>
          <td><div align="left">
            <input name="chkconceptop2" type="checkbox" class="sin-borde" id="chkconceptop2" value="1">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Incluir conceptos reporte</div></td>
          <td><div align="left">
            <input name="chkconceptoreporte" type="checkbox" class="sin-borde" id="chkconceptoreporte" value="1">
          </div></td>
          <td><div align="right">Usar t&iacute;tulo del concepto </div></td>
          <td><div align="left">
            <input name="chktituloconcepto" type="checkbox" class="sin-borde" id="chktituloconcepto" value="1" checked>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Unidad Administrativa </div></td>
          <td colspan="3"><div align="left">
            <input name="txtcoduniadm" type="text" id="txtcoduniadm" size="19" maxlength="16" readonly>
            <a href="javascript: ue_buscaruniadm();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm" size="40" maxlength="30" readonly>
          </div></td>
          </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td>        </tr>
        <tr>
          <td height="20" colspan="4" class="titulo-celdanew"><div align="right" class="titulo-celdanew">Ordenado por </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo del Personal </div></td>
          <td colspan="3"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="1" checked>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Apellido del Personal</div></td>
          <td colspan="3"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="2">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nombre del Personal</div></td>
          <td colspan="3"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="3">
          </div></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="3"> <div align="right">
            <input name="recibo" type="hidden" id="recibo" value="<?php print $ls_recibo;?>">
			<input name="pagina" type="hidden" id="pagina">
			<input name="operacion" type="hidden" id="operacion">
          </div></td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
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
		codperdes=f.txtcodperdes.value;
		codperhas=f.txtcodperhas.value;
		codsubnomdes=f.txtcodsubnomdes.value;
		codsubnomhas=f.txtcodsubnomhas.value;
		tiporeporte=f.cmbbsf.value;
		codnom=f.txtcodnom.value;
		desnom=f.txtdesnom.value;
		codperides=f.txtperdes.value;
		codperihas=f.txtperhas.value;
		fecdesper=f.txtfecdesper.value;
		fechasper=f.txtfechasper.value;
		tipnom=f.txttipnom.value;
		if((codnom!="")&&(codperides!="")&&(codperihas!=""))
		{
			if(codperdes<=codperhas)
			{
				recibo=f.recibo.value;
				conceptocero="";
				conceptop2="";
				tituloconcepto="";
				conceptoreporte="";
				coduniadm=f.txtcoduniadm.value;
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
				if(f.chkconceptocero.checked)
				{
					conceptocero=1;
				}
				if(f.chkconceptop2.checked)
				{
					conceptop2=1;
				}
				if(f.chktituloconcepto.checked)
				{
					tituloconcepto=1;
				}
				if(f.chkconceptoreporte.checked)
				{
					conceptoreporte=1;
				}
				pagina="reportes/"+recibo+"?codperdes="+codperdes+"&codperhas="+codperhas+"&conceptocero="+conceptocero+"";
				pagina=pagina+"&conceptop2="+conceptop2+"&tituloconcepto="+tituloconcepto+"&conceptoreporte="+conceptoreporte;
				pagina=pagina+"&coduniadm="+coduniadm+"&orden="+orden+"&tiporeporte="+tiporeporte+"&codnom="+codnom+"&desnom="+desnom;
				pagina=pagina+"&codperides="+codperides+"&codperihas="+codperihas+"&fecdesper="+fecdesper+"&fechasper="+fechasper;
				pagina=pagina+"&tipnom="+tipnom;
				pagina=pagina+"&codsubnomdes="+codsubnomdes+"&codsubnomhas="+codsubnomhas;
				window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			}
			else
			{
				alert("El rango del personal está erroneo");
			}
		}
		else
		{
			alert("Debe seleccionar un rango de nóminas y períodos.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_buscarnomina()
{
	window.open("sigesp_snorh_cat_nomina.php?tipo=reprecpagcon","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarperiododesde()
{
	f=document.form1;
	codnom=f.txtcodnom.value;
	if(codnom!="")
	{
		window.open("sigesp_sno_cat_hperiodo.php?tipo=reprecpagcondes&codnom="+codnom+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=200,top=200,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar una nómina.");
	}
}

function ue_buscarperiodohasta()
{
	f=document.form1;
	codnom=f.txtcodnom.value;
	if((codnom!="")&&(f.txtperdes.value!=""))
	{
		window.open("sigesp_sno_cat_hperiodo.php?tipo=reprecpagconhas&codnom="+codnom+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=200,top=200,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un rango de nóminas y aun período desde.");
	}
}

function ue_buscarpersonaldesde()
{
	window.open("sigesp_snorh_cat_personal.php?tipo=recpagcondes","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarpersonalhasta()
{
	f=document.form1;
	if(f.txtcodperdes.value!="")
	{
		window.open("sigesp_snorh_cat_personal.php?tipo=recpagconhas","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un personal desde.");
	}
}

function ue_buscaruniadm()
{
	window.open("sigesp_snorh_cat_uni_ad.php?tipo=reprecpag","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarsubnominadesde()
{
	f=document.form1;
	codnomdes=f.txtcodnom.value;
	if(codnomdes!="")
	{
		window.open("sigesp_snorh_cat_subnomina.php?tipo=reportedesde&codnom="+codnomdes,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Para filtrar por Subnóminas La nómina debe estar seleccionada.");
	}
}

function ue_buscarsubnominahasta()
{
	f=document.form1;
	codsubnomdes=f.txtcodsubnomdes.value;
	codnomdes=f.txtcodnom.value;
	if(codsubnomdes!="")
	{
		window.open("sigesp_snorh_cat_subnomina.php?tipo=reportehasta&codnom="+codnomdes,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar una subnómina desde.");
	}
}

function ue_enviarcorreo()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		codperdes=f.txtcodperdes.value;
		codperhas=f.txtcodperhas.value;
		codsubnomdes=f.txtcodsubnomdes.value;
		codsubnomhas=f.txtcodsubnomhas.value;
		tiporeporte=f.cmbbsf.value;
		codnom=f.txtcodnom.value;
		desnom=f.txtdesnom.value;
		codperides=f.txtperdes.value;
		codperihas=f.txtperhas.value;
		fecdesper=f.txtfecdesper.value;
		fechasper=f.txtfechasper.value;
		tipnom=f.txttipnom.value;
		if((codnom!="")&&(codperides!="")&&(codperihas!=""))
		{
			if(codperdes<=codperhas)
			{
				recibo=f.recibo.value;
				conceptocero="";
				conceptop2="";
				tituloconcepto="";
				conceptoreporte="";
				coduniadm=f.txtcoduniadm.value;
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
				if(f.chkconceptocero.checked)
				{
					conceptocero=1;
				}
				if(f.chkconceptop2.checked)
				{
					conceptop2=1;
				}
				if(f.chktituloconcepto.checked)
				{
					tituloconcepto=1;
				}
				if(f.chkconceptoreporte.checked)
				{
					conceptoreporte=1;
				}
				pagina="reportes/sigesp_snorh_rpp_recibopagocorreo.php?codperdes="+codperdes+"&codperhas="+codperhas+"&conceptocero="+conceptocero+"";
				pagina=pagina+"&conceptop2="+conceptop2+"&tituloconcepto="+tituloconcepto+"&conceptoreporte="+conceptoreporte;
				pagina=pagina+"&coduniadm="+coduniadm+"&orden="+orden+"&tiporeporte="+tiporeporte+"&codnom="+codnom+"&desnom="+desnom;
				pagina=pagina+"&codperides="+codperides+"&codperihas="+codperihas+"&fecdesper="+fecdesper+"&fechasper="+fechasper;
				pagina=pagina+"&tipnom="+tipnom;
				pagina=pagina+"&codsubnomdes="+codsubnomdes+"&codsubnomhas="+codsubnomhas;
				window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			}
			else
			{
				alert("El rango del personal está erroneo.");
			}
		}
		else
		{
			alert("Debe seleccionar un rango de nóminas y períodos.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion.");
   	}		
}

</script> 
</html>