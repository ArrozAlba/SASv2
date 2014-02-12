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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_r_cestaticket.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$ls_ruta="txt/cestaticket";
	@mkdir($ls_ruta,0755);
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	$ls_reporte=$io_sno->uf_select_config("SNR","REPORTE","CONSOLIDADO_CESTATICKET","sigesp_snorh_rpp_cestaticket.php","C");
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
<title >Reporte de Cesta Ticket</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
<?php 
	$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
	require_once("sigesp_snorh_c_metodo_cestaticket.php");
	$io_metodo=new sigesp_snorh_c_metodo_cestaticket();
	switch ($ls_operacion) 
	{
		case "GENDISK":
			$ls_anocurper=$_POST["txtanocurper"];
			$ls_mescurper=$_POST["txtmescurper"];
			$ls_codnomdes=$_POST["txtcodnomdes"];
			$ls_codnomhas=$_POST["txtcodnomhas"];
			$ls_codconcdes=$_POST["txtcodconcdes"];
			$ls_codconchas=$_POST["txtcodconchas"];
			$ls_codperi=$_POST["txtcodperi"];
			$ls_metodo=trim($_POST["txtmetodo"]);
			$ls_codcestic=$_POST["txtctmetnom"];
			$ls_codcli=$_POST["txtcodcli"];
			$ls_codprod=$_POST["txtcodprod"];
			$ls_punent=$_POST["txtpunent"];
			$ld_fecha=$_POST["txtfecpro"];
			$ls_orden=$_POST["rdborden"];
			$lb_valido=$io_metodo->uf_listado_gendisk($ls_codnomdes,$ls_codnomhas,$ls_anocurper,$ls_mescurper,$ls_codperi,
													  $ls_codconcdes,$ls_codconchas,$ls_codcestic,"1",$ls_orden);
			if($lb_valido)
			{
				$ds_cestaticket=$io_metodo->DS;
				$lb_valido=$io_metodo->uf_metodo_cestaticket($ls_ruta,$ls_metodo,$ds_cestaticket,$ls_anocurper,$ls_mescurper,
				                                             $ls_codcli,$ls_codprod,$ls_punent,$ld_fecha,$la_seguridad);
			}
			break;
			
		case "GENDISK2":
			$ls_anocurper=$_POST["txtanocurper"];
			$ls_mescurper=$_POST["txtmescurper"];
			$ls_codnomdes=$_POST["txtcodnomdes"];
			$ls_codnomhas=$_POST["txtcodnomhas"];
			$ls_codconcdes=$_POST["txtcodconcdes"];
			$ls_codconchas=$_POST["txtcodconchas"];
			$ls_codperi=$_POST["txtcodperi"];
			$ls_metodo=trim($_POST["txtmetodo"]);
			$ls_codcestic=$_POST["txtctmetnom"];
			$ls_codcli=$_POST["txtcodcli"];
			$ls_codprod=$_POST["txtcodprod"];
			$ls_punent=$_POST["txtpunent"];
			$ld_fecha=$_POST["txtfecpro"];
			$ls_orden=$_POST["rdborden"];
			$lb_valido=$io_metodo->uf_listado_gendisk2($ls_codnomdes,$ls_codnomhas,$ls_anocurper,$ls_mescurper,$ls_codperi,
													   $ls_codcestic,"1",$ls_orden);
			if($lb_valido)
			{
				$ds_cestaticket=$io_metodo->DS;
				$lb_valido=$io_metodo->uf_metodo_cestaticket($ls_ruta,$ls_metodo,$ds_cestaticket,$ls_anocurper,$ls_mescurper,
				                                             $ls_codcli,$ls_codprod,$ls_punent,$ld_fecha,$la_seguridad);
			}
			break;
			
		default:
			$ls_codconc="";
			$ls_nomcon="";
			break;
	}
	unset($io_metodo);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7">
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
    <td height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="24" class="toolbar"><div align="center"><a href="javascript:ue_print();"><img src="../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
	<td class="toolbar" width="26"><div align="center"><a href="javascript: ue_openexcel();"><img src="../shared/imagebank/tools20/excel.jpg" title="Excel" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="29"><div align="center"><a href="javascript: ue_gendisk();"><img src="../shared/imagebank/tools20/gendisk.jpg" title="Generar" alt="Salir" width="21" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_descargar('<?php print $ls_ruta;?>');"><img src="../shared/imagebank/tools20/download.gif" title="Descargar" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="20"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif"  title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="23"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif"  title="Ayuda" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="351"><div align="center"></div></td>
    <td class="toolbar" width="71"><div align="center"></div></td>
    <td class="toolbar" width="71"><div align="center"></div></td>
    <td class="toolbar" width="71"><div align="center"></div></td>
    <td class="toolbar" width="68"><div align="center"></div></td>
    <td class="toolbar" width="3">&nbsp;</td>
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
          <td height="20" colspan="4" class="titulo-ventana">Reporte de Cesta Ticket </td>
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
          <td height="22"><div align="right">M&eacute;todo</div></td>
          <td colspan="3"><input name="txtctmetnom" type="text" id="txtctmetnom" size="5" maxlength="2" >
            <a href="javascript: ue_buscarmetodoct();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdencestic" type="text" class="sin-borde" id="txtdencestic" size="50">
            <input name="txtmetodo" type="hidden" id="txtmetodo">
            <input name="txtcodcli" type="hidden" id="txtcodcli">
            <input name="txtcodprod" type="hidden" id="txtcodprod">
            <input name="txtpunent" type="hidden" id="txtpunent"></td>
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
          <td height="22" colspan="4"><div align="center">Este filtro no sera tomado en cuenta para generar el txt </div></td>
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
          <td height="22" colspan="4"><div align="center">El filtro concepto desde - hasta, no ser&aacute; tomado en cuenta para generar el reporte en excel </div></td>
        </tr>
		<tr>
          <td height="22"><div align="right"> Concepto Desde </div></td>
          <td><div align="left"><a href="javascript: ue_buscarconcepto();"></a>
            <input name="txtcodconcdes" type="text" id="txtcodconcdes" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarconceptodesde();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="personal" width="15" height="15" border="0" id="personal"></a></div></td>
          <td><div align="right">Concepto Hasta </div></td>
          <td><div align="left">
            <input name="txtcodconchas" type="text" id="txtcodconchas" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarconceptohasta();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="personal" width="15" height="15" border="0" id="personal"></a></div></td>
        </tr>		
        <tr>
          <td height="22"><div align="right">Mes</div></td>
          <td colspan="3"><div align="left">
            <input name="txtanocurper" type="text" id="txtanocurper" size="7" maxlength="4" readonly>
            <input name="txtmescurper" type="text" id="txtmescurper" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarmeses();"><img id="meses" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdesmesper" type="text" class="sin-borde" id="txtdesmesper" value="" size="30" maxlength="20" readonly>
            <input name="txtcodperi" type="hidden" id="txtcodperi">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Fecha de Procesamiento </div></td>
          <td colspan="3"><div align="left">
            <input name="txtfecpro" type="text" id="txtfecpro" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" size="15" maxlength="10" datepicker="true">
          </div></td>
        </tr>
        <tr class="titulo-celdanew">
          <td height="20" colspan="4">&nbsp;</td>
        </tr>
		 <tr class="formato-blanco">
          <td height="20" ><div align="right">Tomar en cuenta Monto Neto a Cobrar para Generar el TXT</div></td>
          <td height="20"><div align="left">
            <input name="chkmoncob" type="checkbox" class="sin-borde" id="chkmoncob" value="1">
          </div></td>
          <td height="20" >&nbsp;</td>
          <td height="20" >&nbsp;</td>
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
          <td width="173"><div align="left"><input name="rdborden" type="radio" class="sin-borde" value="2">
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
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);

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
		if(codnomdes<=codnomhas)
		{
			codconcdes=f.txtcodconcdes.value;
			codconchas=f.txtcodconchas.value;
			ano=f.txtanocurper.value;
			mes=f.txtmescurper.value;
			codperi=f.txtcodperi.value;
			tiporeporte=f.cmbbsf.value;
			if((codconcdes!="")&&(codconchas!="")&&(mes!=""))
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
				pagina="reportes/"+reporte+"?codnomdes="+codnomdes+"&codnomhas="+codnomhas;
				pagina=pagina+"&codconcdes="+codconcdes+"&codconchas="+codconchas+"&mes="+mes+"&ano="+ano+"&conceptocero="+conceptocero;
				pagina=pagina+"&codperi="+codperi+"&orden="+orden+"&tiporeporte="+tiporeporte;
				pagina=pagina+"&codsubnomdes="+codsubnomdes+"&codsubnomhas="+codsubnomhas;
				window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			}
			else
			{
				alert("Debe seleccionar un Concepto y Mes.");
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

function ue_gendisk()
{
	f=document.form1;
	li_procesar=f.ejecutar.value;
	if(li_procesar==1)
	{	
		codconcdes=f.txtcodconcdes.value;
		codconcdhas=f.txtcodconchas.value;
		ctmetnom=f.txtctmetnom.value;		
		if(ctmetnom!="")
		{
			if(f.chkmoncob.checked)
			{
				f.operacion.value="GENDISK2";
				f.action="sigesp_snorh_r_cestaticket.php";
				f.submit();
			}			
			else if ((codconcdes!="")&&(codconcdhas!=""))
			{
				f.operacion.value="GENDISK";
				f.action="sigesp_snorh_r_cestaticket.php";
				f.submit();
			}
			else
			{
				alert("Debe seleccionar un Rango de Conceptos");
			}
		}
		else
		{
			alert("Debe seleccionar un Método de Cesta Ticket");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación");
   	}
}

function ue_descargar(ruta)
{
	window.open("sigesp_sno_cat_directorio.php?ruta="+ruta+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarnominadesde()
{
	window.open("sigesp_snorh_cat_nomina.php?tipo=repcesticdes","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarnominahasta()
{
	f=document.form1;
	if(f.txtcodnomdes.value!="")
	{
		window.open("sigesp_snorh_cat_nomina.php?tipo=repcestichas","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
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
		window.open("sigesp_sno_cat_concepto.php?tipo=repcesticdes&codnomdes="+codnomdes+"&codnomhas="+codnomhas+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
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
		window.open("sigesp_sno_cat_concepto.php?tipo=repcestichas&codnomdes="+codnomdes+"&codnomhas="+codnomhas+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un concepto Desde.");
	}
}

function ue_buscarmeses()
{
	f=document.form1;
	codnomdes=f.txtcodnomdes.value;
	codnomhas=f.txtcodnomhas.value;
	if((f.txtcodnomdes.value!="")&&(f.txtcodnomhas.value!=""))
	{
		window.open("sigesp_sno_cat_hmes.php?tipo=repcestic&codnom="+codnomdes+"&codnomhas="+codnomhas+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=200,top=200,location=no,resizable=no");
   	}
	else
   	{
		alert("Debe seleccionar una nómina desde.");
   	}
}

function ue_buscarmetodoct()
{
	window.open("sigesp_snorh_cat_ct.php?tipo=gendisk","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
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

function ue_openexcel()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		codnomdes=f.txtcodnomdes.value;
		codnomhas=f.txtcodnomhas.value;
		codsubnomdes=f.txtcodsubnomdes.value;
		codsubnomhas=f.txtcodsubnomhas.value;		
		if(codnomdes<=codnomhas)
		{
			codconcdes=f.txtcodconcdes.value;
			codconchas=f.txtcodconchas.value;
			ano=f.txtanocurper.value;
			mes=f.txtmescurper.value;
			codperi=f.txtcodperi.value;
			tiporeporte=f.cmbbsf.value;
			if(mes!="")
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
				pagina="reportes/sigesp_snorh_rpp_cestaticket_excel.php?codnomdes="+codnomdes+"&codnomhas="+codnomhas;
				pagina=pagina+"&codconcdes="+codconcdes+"&codconchas="+codconchas+"&mes="+mes+"&ano="+ano+"&conceptocero="+conceptocero;
				pagina=pagina+"&codperi="+codperi+"&orden="+orden+"&tiporeporte="+tiporeporte;
				pagina=pagina+"&codsubnomdes="+codsubnomdes+"&codsubnomhas="+codsubnomhas;
				window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			}
			else
			{
				alert("Debe seleccionar un Mes.");
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
</script> 
</html>