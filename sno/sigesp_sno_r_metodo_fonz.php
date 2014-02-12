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
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_r_metodo_fonz.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	$lb_valido=$io_sno->uf_crear_sessionnomina();		
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
	$li_suspendidos=$io_sno->uf_select_config("SNO","CONFIG","EXCLUIR_SUSPENDIDOS","0","I");
	$li_adelanto=$_SESSION["la_nomina"]["adenom"];
	$li_divcon=$_SESSION["la_nomina"]["divcon"];
	$li_tippernom=$_SESSION["la_nomina"]["tippernom"];
	$ls_subnom=$_SESSION["la_nomina"]["subnom"];
	$ls_reporte=$io_sno->uf_select_config("SNO","REPORTE","LISTADO_BANCO","sigesp_sno_rpp_listadobanco.php","C");
	$ls_nomina=$_SESSION["la_nomina"]["codnom"];
	$ls_periodo=$_SESSION["la_nomina"]["peractnom"];
	$li_tipnom=$_SESSION["la_nomina"]["tipnom"];
	$ls_ruta="txt/disco_banco/".$ls_nomina."-".$ls_periodo;
	@mkdir($ls_ruta,0755);
	///////////////// PAGINACION   /////////////////////
	$li_registros = 700;
	$ls_codperdes="";
	$ls_codperhas="";
	$li_pagina=$io_fun_nomina->uf_obtenervalor_get("pagina",0); 
	if (!$li_pagina)
	{ 
		$li_inicio = 0; 
		$li_pagina = 1; 
	} 
	else 
	{ 
		$li_inicio = ($li_pagina - 1) * $li_registros; 
	} 
	$li_totpag=0;	
	$ls_valor=0;
	$li_pag=$li_pagina;
	if (array_key_exists("banco",$_GET))
	{
	  $ls_codban=$_GET["banco"];
	}
	else 
	{
	  	if (array_key_exists("txtcodban",$_POST))
		{
		  $ls_codban=$_POST["txtcodban"];
		}
		else 
		{
		  $ls_codban="";
		}
	}
	$io_sno->uf_buscar_personal($ls_codnom,$ls_peractnom,$ls_valor,$li_inicio,$li_registros,$li_totpag,$ls_codperdes,$ls_codperhas,$ls_codban);	
	if ($ls_valor<$li_registros)
	{
	  $ls_codperhas="";
	  $ls_codperdes="";
	}
	unset($io_sno);
    ///////////////// PAGINACION   /////////////////////
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
<title >Reporte Archivo Fonz03</title>
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
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
	$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
	require_once("sigesp_sno_c_metodo_banco.php");
	$io_metodobanco=new sigesp_sno_c_metodo_banco();
	$ls_codconc="";	
	switch ($ls_operacion) 
	{
		case "GENDISK":
			$ld_monto=0; // ojo monto a pagar			
			$ld_fecdes=$_SESSION["la_nomina"]["fecdesper"];
			$ld_fechas=$_SESSION["la_nomina"]["fechasper"];
			$ls_suspendidos=$_POST["txtsuspendidos"];
			$ls_quincena=$_POST["cmdquincena"];
			$ls_desmet=rtrim("FONZ03");
			$ls_codmet="";
			$ld_fecpro=$_POST["txtfecpro"];
			$ls_ref=$_POST["txtref"];
			$ls_codcue="";
			$ls_pagtaqnom=$io_fun_nomina->uf_obtenervalor("chkpagtaqnom","0");
			$ls_codconc=$_POST["txtcodconc"];
			switch ($ls_codconc)
				{
					case "0000020007":
						$ls_ruta2=$ls_ruta."/fonz03-20007.txt";
					break;
					
					case "0000020014":
						$ls_ruta2=$ls_ruta."/fonz03-20014.txt";
					break;
					
					case "0000020003":
						$ls_ruta2=$ls_ruta."/fonz03-20003.txt";
					break;
					
					case "0000020005":
						$ls_ruta2=$ls_ruta."/fonz03-20014.txt";
					break;
					
					case "0000020008":
						$ls_ruta2=$ls_ruta."/fonz03-20008.txt";
					break;
				}
			switch(substr($ld_fechas,5,2))
			{
				case "01":
					$ls_mes="ENERO";
					break;
				case "02":
					$ls_mes="FEBRERO";
					break;
				case "03":
					$ls_mes="MARZO";
					break;
				case "04":
					$ls_mes="ABRIL";
					break;
				case "05":
					$ls_mes="MAYO";
					break;
				case "06":
					$ls_mes="JUNIO";
					break;
				case "07":
					$ls_mes="JULIO";
					break;
				case "08":
					$ls_mes="AGOSTO";
					break;
				case "09":
					$ls_mes="SEPTIEMBRE";
					break;
				case "10":
					$ls_mes="OCTUBRE";
					break;
				case "11":
					$ls_mes="NOVIEMBRE";
					break;
				case "12":
					$ls_mes="DICIEMBRE";
					break;
			}
			$ls_desope=$ls_mes." DEL ".substr($ld_fechas,0,4);
			$ls_desope=str_pad(substr($ls_desope,0,40),40," ");
			$lb_valido=$io_metodobanco->uf_listadobanco_gendisk2($ls_codban,$ls_suspendidos,$ls_quincena,$ls_pagtaqnom,$ls_codconc,$rs_data);
			if($lb_valido)
			{
				$lb_valido=$io_metodobanco->uf_load_montototal($ls_codban,$ls_suspendidos,$ls_quincena,$ls_pagtaqnom,$ld_monto);
			}
			if($lb_valido)
			{
				//$ds_banco=$io_metodobanco->DS;
				$lb_valido=$io_metodobanco->uf_metodo_banco($ls_ruta2,$ls_desmet,$ls_peractnom,$ld_fecdes,$ld_fechas,$ld_fecpro,
															$ld_monto,$ls_codcue,$rs_data,$ls_codmet,$ls_desope,$ls_quincena,
															$ls_ref,$la_seguridad);
			}
			break;
		default:
			$ls_codmet="";
			$ls_desmet="";
			$ls_codban="";
			$ls_nomban="";
			$ls_codcue="";
			$ld_fecpro="";
			$ls_sc_cuenta="";
			$ls_ctaban="";
			$ls_pagtaqnom="0";
			break;
	}
	unset($io_metodobanco);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="10" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="10" bgcolor="#E7E7E7">
	<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema"><?php print $ls_desnom;?></td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><?php print $ls_desper;?></span></div></td>
			 <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
	  </table></td>
  </tr>
  <tr>
    <td height="20" colspan="10" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_nomina.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="10" class="toolbar"></td>
  </tr>
  <tr>
    <td width="25" height="20" class="toolbar"><div align="center"><a href="javascript: ue_gendisk();"><img src="../shared/imagebank/tools20/gendisk.jpg" title='Generar' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_descargar('<?php print $ls_ruta;?>');"><img src="../shared/imagebank/tools20/download.gif" title='Descargar' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank_nomina.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="579" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td width="605" height="136">
      <p>&nbsp;</p>
      <table width="505" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="3" class="titulo-ventana">Generar Archivo Fonz03 </td>
        </tr>
        <tr >
          <td height="20" colspan="2"><div align="right">Banco</div></td>
          <td width="318" height="20"><input name="txtcodban" type="text" id="txtcodban" value="<?php print $ls_codban;?>" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarbanco();"><img id="banco" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></td>
        </tr>

        <tr>
          <td width="180" height="22"><div align="right">Concepto</div></td>
          <td height="22" colspan="2">        <div align="left">
          <input name="txtcodconc" type="text" id="txtcodconc" size="13" maxlength="10" value="<?php print $ls_codconc;?>" readonly>
          <a href="javascript: ue_buscarconcepto();"><img id="metodobanco" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div>          </tr>
        <tr>
          <td height="22"><div align="right">Quincena</div></td>
          <td colspan="2"><select name="cmdquincena" id="cmdquincena">
            <option value="1">Primera Quincena</option>
            <option value="2">Segunda Quincena</option>
            <option value="3" selected>Mes Completo</option>
          </select></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Fecha de Procesamiento </div></td>
          <td colspan="2"><input name="txtfecpro" type="text" id="txtfecpro" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" value="<?php print $ld_fecpro;?>" size="15" maxlength="10" datepicker="true"></td>
        </tr>
        <tr>
          <td height="22"><div align="right"><a href="sigesp_sno_cat_directorio.php" target="_blank"></a></div></td>
          <td colspan="2"><div align="right">
            <input name="operacion" type="hidden" id="operacion">
			<input name="txttipnom" type="hidden" id="txttipnom" value="<?php print $li_tipnom;?>">
			<input name="txtpag" type="hidden" id="txtpag" value="<?php print $li_pagina;?>">
            <input name="txtsuspendidos" type="hidden" id="txtsuspendidos" value="<?php print $li_suspendidos;?>">
            <input name="reporte" type="hidden" id="reporte" value="<?php print $ls_reporte;?>">
 		   <input name="subnom" type="hidden" id="subnom" value="<?php print $ls_subnom;?>">
			</div></td>
			<input name="txtref" type="hidden" id="txtref">
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
	location.href = "sigespwindow_blank_nomina.php";
}
function ue_gendisk()
{
	f=document.form1;
	li_procesar=f.ejecutar.value;
	if(li_procesar==1)
	{	
		codconc=f.txtcodconc.value;
		codban=f.txtcodban.value;
		fecpro=f.txtfecpro.value;
		if((codban!="")&&(codconc!="")&&(fecpro!=""))
		{
			f.operacion.value="GENDISK";
			f.action="sigesp_sno_r_metodo_fonz.php";
			f.submit();
		}
		else
		{
			alert("Debe colocar todos los datos.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_descargar(ruta)
{
	window.open("sigesp_sno_cat_directorio.php?ruta="+ruta+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarconcepto()
{
		window.open("sigesp_sno_cat_concepto.php?tipo=archtxt","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");

}

function ue_buscarbanco()
{
	
	f=document.form1;
	tipnom=f.txttipnom.value;
	if (tipnom!='12')
	{
		window.open("sigesp_snorh_cat_banco.php?tipo=archtxt","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		window.open("sigesp_snorh_cat_banco.php?tipo=archtxt","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}

}
</script> 
</html>