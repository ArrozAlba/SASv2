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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_r_aportepatronal.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$ls_ruta="txt/general";
	@mkdir($ls_ruta,0755);
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	$ls_reporte=$io_sno->uf_select_config("SNR","REPORTE","CONSOLIDADO_APORTEPATRONAL","sigesp_snorh_rpp_aportepatronal.php","C");
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
<title >Reporte de Aporte Patronal</title>
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
<?php 
	$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
	require_once("sigesp_snorh_c_metodo_aporte.php");
	$io_metodo=new sigesp_snorh_c_metodo_aporte();
	switch ($ls_operacion) 
	{
		case "GENDISK":
			$ls_concepto_lph=trim($io_metodo->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO LPH","LPHLPHLPHL","C"));
			$ls_concepto_fpj=trim($io_metodo->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO FPJ","XXXXXXXX","C"));
			$ls_concepto_fpa=trim($io_metodo->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO FPA","XXXXXXXXXX","C"));
			$ls_codconc=$_POST["txtcodconc"];
			$ls_nomcon=$_POST["txtnomcon"];
			$ls_codnomdes=$_POST["txtcodnomdes"];
			$ls_codnomhas=$_POST["txtcodnomhas"];
			$ls_anocur=$_POST["txtanocurperdes"];
			$ls_mes=$_POST["txtmescurperdes"];
			$ls_perdes=$_POST["txtperdes"];
			$ls_perhas=$_POST["txtperhas"];
			$ld_fecpro='01/'.$ls_mes.'/'.$ls_anocur;
			$la_fpa=split(",",$ls_concepto_fpa);
			$la_fpj=split(",",$ls_concepto_fpj);
			$la_lph=split(",",$ls_concepto_lph);
			$li_total_fpa=count($la_fpa);
			$li_total_fpj=count($la_fpj);
			$li_total_lph=count($la_lph);
			// Para el Fondo de Plan de Ahorro
			for($li_i=0;$li_i<$li_total_fpa;$li_i++)
			{
				$ls_concepto_fpa=$la_fpa[$li_i];
				if($ls_codconc==$ls_concepto_fpa)
				{
					$ls_metodo_fpa=trim($io_metodo->io_sno->uf_select_config("SNO","NOMINA","METODO FPA","SIN METODO","C"));
					$lb_valido=$io_metodo->uf_listado_gendisk($la_fpa,$ls_codnomdes,$ls_codnomhas,$ls_anocur,$ls_perdes,$ls_perhas);
					if($lb_valido)
					{
						$ds_banco=$io_metodo->DS;
						$lb_valido=$io_metodo->uf_metodo_fpa($ls_ruta,$ls_metodo_fpa,$ds_banco,$ld_fecpro,
														     $ls_codconc,$ls_codnomdes,$ls_codnomhas,$ls_anocur,$ls_perdes,
															 $ls_perhas,$la_seguridad);
					}
					else
					{
						$io_metodo->io_mensajes->message("No hay nada que Reportar. No se encontraron datos para generar el archivo de texto.");
					}
					break;
				}
			}
			// Para el Fondo de Pensión de Jubilación
			for($li_i=0;$li_i<$li_total_fpj;$li_i++)
			{
				$ls_concepto_fpj=$la_fpj[$li_i];
				if($ls_codconc==$ls_concepto_fpj)
				{
					$ls_metodo_fpj=trim($io_metodo->io_sno->uf_select_config("SNO","CONFIG","METODO FPJ","SUELDO NORMAL","C"));
					$ls_organismo=trim($io_metodo->io_sno->uf_select_config("SNO","NOMINA","COD ORGANISMO FPJ","XXXXXXXX","C"));
					$lb_valido=$io_metodo->uf_listado_gendisk($la_fpj,$ls_codnomdes,$ls_codnomhas,$ls_anocur,$ls_perdes,$ls_perhas);
					if($lb_valido)
					{
						$ds_banco=$io_metodo->DS;
						$lb_valido=$io_metodo->uf_metodo_fpj($ls_ruta,$ls_metodo_fpj,$ls_organismo,$ds_banco,$ld_fecpro,
														     $ls_codconc,$ls_codnomdes,$ls_codnomhas,$ls_anocur,$ls_perdes,
															 $ls_perhas,$la_seguridad);
					}
					else
					{
						$io_metodo->io_mensajes->message("No hay nada que Reportar. No se encontraron datos para generar el archivo de texto.");
					}
					break;
				}
			}
			// Para el Ley de Política Habitacional
			for($li_i=0;$li_i<$li_total_lph;$li_i++)
			{
				$ls_concepto_lph=$la_lph[$li_i];
				if($ls_codconc==$ls_concepto_lph)
				{
					$ls_metodo_lph=rtrim($io_metodo->io_sno->uf_select_config("SNO","NOMINA","METODO LPH","SIN METODO","C"));
					$lb_valido=$io_metodo->uf_listado_gendisk($la_lph,$ls_codnomdes,$ls_codnomhas,$ls_anocur,$ls_perdes,$ls_perhas);
					if($lb_valido)
					{
						$ds_banco=$io_metodo->DS;
						$lb_valido=$io_metodo->uf_metodo_lph($ls_ruta,$ls_metodo_lph,$ds_banco,$ld_fecpro,
														     $ls_codconc,$ls_codnomdes,$ls_codnomhas,$ls_anocur,$ls_perdes,
															 $ls_perhas,$la_seguridad);
					}
					else
					{
						$io_metodo->io_mensajes->message("No hay nada que Reportar. No se encontraron datos para generar el archivo de texto.");
					}
					break;
				}
			}
			break;
			
		default:
			$ls_codconc="";
			$ls_nomcon="";
			break;
	}
	unset($io_metodobanco);
?>
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
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_gendisk();"><img src="../shared/imagebank/tools20/gendisk.jpg"  title="Generar" alt="Salir" width="21" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_descargar('<?php print $ls_ruta;?>');"><img src="../shared/imagebank/tools20/download.gif" title="Descargar" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></div></td>
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
          <td height="20" colspan="4" class="titulo-ventana">Reporte de Aporte Patronal </td>
        </tr>
        <tr class="formato-blanco">
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
        </tr>
        <tr class="formato-blanco" style="display:none">
          <td height="20"> <div align="right">Reporte en </div></td>
          <td height="20"><select name="cmbbsf" id="cmbbsf">
            <option value="0" selected>Bs.</option>
            <option value="1">Bs.F.</option>
          </select></td>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
        </tr>
        <tr>
          <td width="155" height="22"><div align="right">N&oacute;mina Desde </div></td>
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
          <td height="22"><div align="right"> Concepto </div></td>
          <td colspan="3"><div align="left">
            <input name="txtcodconc" type="text" id="txtcodconc" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarconcepto();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtnomcon" type="text" class="sin-borde" id="txtnomcon" size="40" readonly>
          </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Mes Desde </div></td>
          <td><div align="left">
            <input name="txtanocurperdes" type="text" id="txtanocurperdes" size="7" maxlength="4" readonly>
            <input name="txtmescurperdes" type="text" id="txtmescurperdes" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarmesdesde();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="meses" width="15" height="15" border="0" id="meses"></a></div></td>
          <td><div align="right">Mes Hasta </div></td>
          <td><input name="txtanocurperhas" type="text" id="txtanocurperhas" size="7" maxlength="4" readonly>
            <input name="txtmescurperhas" type="text" id="txtmescurperhas" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarmeshasta();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="meses" width="15" height="15" border="0" id="meses"></a></td>
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
          <td width="138"><div align="left">
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
			codconc=f.txtcodconc.value;
			nomcon=f.txtnomcon.value;
			anodes=f.txtanocurperdes.value;
			mesdes=f.txtmescurperdes.value;
			anohas=f.txtanocurperhas.value;
			meshas=f.txtmescurperhas.value;
			perdes=f.txtperdes.value;
			perhas=f.txtperhas.value;
			if((codconc!="")&&(mesdes!="")&&(meshas!="")&&(perdes!="")&&(perhas!=""))
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
				pagina="reportes/"+reporte+"?codnomdes="+codnomdes+"&codnomhas="+codnomhas+"&nomcon="+nomcon;
				pagina=pagina+"&codconc="+codconc+"&mesdes="+mesdes+"&anodes="+anodes+"&meshas="+meshas+"&anohas="+anohas+"&perdes="+perdes+"&perhas="+perhas;
				pagina=pagina+"&conceptocero="+conceptocero+"&orden="+orden+"&tiporeporte="+tiporeporte;
				pagina=pagina+"&codsubnomdes="+codsubnomdes+"&codsubnomhas="+codsubnomhas;
				window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			}
			else
			{
				alert("Debe seleccionar un concepto, mes y Periodo.");
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
		codconc=f.txtcodconc.value;
		if(codconc!="")
		{
			f.operacion.value="GENDISK";
			f.action="sigesp_snorh_r_aportepatronal.php";
			f.submit();
		}
		else
		{
			alert("Debe seleccionar un concepto.");
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
	window.open("sigesp_snorh_cat_nomina.php?tipo=repapopatdes","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarnominahasta()
{
	f=document.form1;
	if(f.txtcodnomdes.value!="")
	{
		window.open("sigesp_snorh_cat_nomina.php?tipo=repapopathas","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar una nómina desde.");
	}
}

function ue_buscarconcepto()
{
	f=document.form1;
	codnomdes=f.txtcodnomdes.value;
	codnomhas=f.txtcodnomhas.value;
	if((f.txtcodnomdes.value!="")&&(f.txtcodnomhas.value!=""))
	{
		window.open("sigesp_sno_cat_concepto.php?tipo=repapopatcon&codnomdes="+codnomdes+"&codnomhas="+codnomhas+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar las nómina.");
	}
}

function ue_buscarmesdesde()
{
	f=document.form1;
	codnomdes=f.txtcodnomdes.value;
	codnomhas=f.txtcodnomhas.value;
	if((f.txtcodnomdes.value!="")&&(f.txtcodnomhas.value!=""))
	{
		window.open("sigesp_sno_cat_hmes.php?tipo=repapopatdes&codnom="+codnomdes+"&codnomhas="+codnomhas+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=200,top=200,location=no,resizable=no");
   	}
	else
   	{
		alert("Debe seleccionar una nómina desde.");
   	}
}

function ue_buscarmeshasta()
{
	f=document.form1;
	codnomdes=f.txtcodnomdes.value;
	codnomhas=f.txtcodnomhas.value;
	mes=f.txtmescurperdes.value;
	if(mes!="")
	{
		window.open("sigesp_sno_cat_hmes.php?tipo=repapopathas&codnom="+codnomdes+"&codnomhas="+codnomhas+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=200,top=200,location=no,resizable=no");
   	}
	else
   	{
		alert("Debe seleccionar un Mes desde.");
   	}
}

function ue_buscarperiododesde()
{
	f=document.form1;
	codnomdes=f.txtcodnomdes.value;
	codnomhas=f.txtcodnomhas.value;
	mesdesde=f.txtmescurperdes.value;
	meshasta=f.txtmescurperhas.value;
	if((mesdesde!="")&&(meshasta!=""))
	{
		window.open("sigesp_sno_cat_hperiodo.php?tipo=repapopatdes&codnom="+codnomdes+"&codnomhas="+codnomhas+"&mesdesde="+mesdesde+"&meshasta="+meshasta+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=200,top=200,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un mes.");
	}
}

function ue_buscarperiodohasta()
{
	f=document.form1;
	codnomdes=f.txtcodnomdes.value;
	codnomhas=f.txtcodnomhas.value;
	mesdesde=f.txtmescurperdes.value;
	meshasta=f.txtmescurperhas.value;
	if((mesdesde!="")&&(meshasta!="")&&(f.txtperdes.value!=""))
	{
		window.open("sigesp_sno_cat_hperiodo.php?tipo=repapopathas&codnom="+codnomdes+"&codnomhas="+codnomhas+"&mesdesde="+mesdesde+"&meshasta="+meshasta+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=200,top=200,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un período desde.");
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