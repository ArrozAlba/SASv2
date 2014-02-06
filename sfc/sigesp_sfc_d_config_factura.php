<?Php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))//Esta es la configuracion general
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Configuraci&oacute;n Formato Factura</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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
.Estilo1 {
	color: #000000;
	font-weight: bold;
}
.Estilo2 {font-size: 9px}
.Estilo3 {font-size: 12px}
.Estilo4 {
	color: #6699CC;
	font-size: 14px;
}
-->
</style>
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
</head>
<body>
<?php

$ls_archivo="reportes/cheque_configurable/medidas.txt";
$li_medidas=16;
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_montox=$_POST["txtmonto_x"];
	$ls_montoy=$_POST["txtmonto_y"];
	$ls_destinox=$_POST["txtdestino_x"];
	$ls_destinoy=$_POST["txtdestino_y"];
	$ls_montoletras1x=$_POST["txtmontoletras1_x"];
	$ls_montoletras1y=$_POST["txtmontoletras1_y"];
	$ls_montoletras2x=$_POST["txtmontoletras2_x"];
	$ls_montoletras2y=$_POST["txtmontoletras2_y"];
	$ls_fechax=$_POST["txtfecha_x"];
	$ls_fechay=$_POST["txtfecha_y"];
	$ls_anox=$_POST["txtano_x"];
	$ls_anoy=$_POST["txtano_y"];
	$ls_noendosablex=$_POST["txtnoendosable_x"];
	$ls_noendosabley=$_POST["txtnoendosable_y"];
	$ls_caducax=$_POST["txtcaduca_x"];
	$ls_caducay=$_POST["txtcaduca_y"];
	//$ls_infoy=$_POST["txtinfo_x"];
	if($ls_operacion=="NUEVO")
	{
		uf_obtener_data_archivo();		
	
		$ls_montoy="";
		$ls_destinoy="";
		$ls_montoletras1y="";
	}
	elseif($ls_operacion=="GUARDAR")
	{
		if(file_exists($ls_archivo))
		{	
			$ls_cadena_guardar=uf_convertir($ls_montox)."-".uf_convertir($ls_montoy)."-".uf_convertir($ls_destinox)."-".uf_convertir($ls_destinoy)."-".uf_convertir($ls_montoletras1x)."-".uf_convertir($ls_montoletras1y)."-".uf_convertir($ls_montoletras2x)."-".uf_convertir($ls_montoletras2y)."-".uf_convertir($ls_fechax)."-".uf_convertir($ls_fechay)."-".uf_convertir($ls_anox)."-".uf_convertir($ls_anoy)."-".uf_convertir($ls_noendosablex)."-".uf_convertir($ls_noendosabley)."-".uf_convertir($ls_caducax)."-".uf_convertir($ls_caducay);
			$archivo = fopen($ls_archivo, "w");
			$lb_exito=fwrite($archivo,$ls_cadena_guardar);
			fclose($archivo);
			if($lb_exito==false)
			{
				print "<script>";
				print "alert('Ocurrio un error, favor intentar de nuevo');";
				//print "location.href='sigespwindow_blank.php';";
				print "</script>";
			}
			else
			{
				print "<script>";
				print "alert('Las medidas fueron actualizadas');";
				//print "location.href='sigespwindow_blank.php';";
				print "</script>";
			}
			uf_obtener_data_archivo();
		}
		else
		{
			print "<script>";
			print "alert('Debe generar una Factura de prueba para inicializar las medidas (Puede ser que esta opción no esté activa aún)');";
			print "location.href='sigespwindow_blank.php';";
			print "</script>";
		}
	}
}
else
{
	$ls_operacion="";
	uf_obtener_data_archivo();
}


function uf_obtener_data_archivo()
{
	global $ls_archivo;
	global $li_medidas;
	if(!file_exists( $ls_archivo) || (filesize($ls_archivo)==0))
	{
		print "<script>";
		print "alert('Error de Lectura y Escritura: Debe generar una Factura de prueba para inicializar las medidas');";
		print "location.href='sigespwindow_blank.php';";
		print "</script>";
	}
	else
	{
		$archivo = fopen($ls_archivo, "r");
		$contenido = fread($archivo, filesize($ls_archivo));		
		fclose($archivo);
		$valores = explode("-",$contenido);
		if(count($valores)==$li_medidas)
		{
			global $ls_montox;
			$ls_montox=number_format($valores[0],2,",",".");
			global $ls_montoy;
			//$ls_montoy=number_format($valores[1],2,",",".");
			global $ls_destinox;
			$ls_destinox=number_format($valores[2],2,",",".");
			global $ls_destinoy;
			//$ls_destinoy=number_format($valores[3],2,",",".");
			global $ls_montoletras1x;
			$ls_montoletras1x=number_format($valores[4],2,",",".");
			global $ls_montoletras1y;
			//$ls_montoletras1y=number_format($valores[5],2,",",".");
			global $ls_montoletras2x;
			$ls_montoletras2x=number_format($valores[6],2,",",".");
			global $ls_montoletras2y;
			$ls_montoletras2y=number_format($valores[7],2,",",".");
			global $ls_fechax;
			$ls_fechax=number_format($valores[8],2,",",".");
			global $ls_fechay;
			$ls_fechay=number_format($valores[9],2,",",".");
			global $ls_anox;
			$ls_anox=number_format($valores[10],2,",",".");
			global $ls_anoy;
			$ls_anoy=number_format($valores[11],2,",",".");
			global $ls_noendosablex;
			$ls_noendosablex=number_format($valores[12],2,",",".");
			global $ls_noendosabley;
			$ls_noendosabley=number_format($valores[13],2,",",".");
			global $ls_caducax;
			$ls_caducax=number_format($valores[14],2,",",".");
			global $ls_caducay;
			$ls_caducay=number_format($valores[15],2,",",".");
			/*global $ls_infoy;
			$ls_infoy=number_format($valores[16],2,",",".");*/
		}
		else
		{
			print "<script>";
			print "alert('Error de Lectura y Escritura: Debe generar una Factura de prueba para inicializar las medidas');";
			print "location.href='sigespwindow_blank.php';";
			print "</script>";
		}		
	}

}
function uf_convertir($ls_numero)
{
	$ls_numero=str_replace(".","",$ls_numero);
	$ls_numero=str_replace(",",".",$ls_numero);
	return $ls_numero;
}	

?>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="540" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1 Estilo4">Sistema de Facturacion</span></td>
    <td width="238" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">&nbsp;</td>
    <td height="20" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 

    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>

</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action=""> 

  <table width="485" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
   
    <tr>
      <td width="483" ></td>
    </tr>
    <tr class="titulo-ventana">
      <td height="3" colspan="6" align="center">Configuraci&oacute;n del Formato de Factura </td>
    </tr>
    <tr class="titulo-ventana">
      <td height="340" colspan="1" align="center" class="celdas-blancas"><table width="306" border="1" align="center" cellpadding="0" cellspacing="1" class="contorno">
          <tr class="formato-azul">
            <td height="13" align="center"><span class="Estilo1">Campo</span></td>
            <td width="91" height="13" align="center">&nbsp;</td>
            <td width="96" height="13" align="center"><span class="Estilo1"><span class="Estilo3">Y</span><br>
              <span class="Estilo2">(mm desde el borde superior)</span></span></td>
          </tr>
          <tr class="titulo-ventana">
            <td height="41" align="center" class="celdas-blancas">Detalle del Cliente </td>
            <td height="41" align="center" class="celdas-blancas"><label></label></td>
            <td height="41" align="center" class="celdas-blancas"><input name="txtmonto_y" type="text" value="<?php print $ls_montoy?>" size="8" maxlength="8" style="text-align:right"  ></td>
          </tr>
          <tr class="titulo-ventana">
            <td height="41" align="center" class="celdas-blancas">Detalle de Articulos </td>
            <td height="41" align="center" class="celdas-blancas">&nbsp;</td>
            <td height="41" align="center" class="celdas-blancas"><input name="txtdestino_y" type="text" value="<?php print $ls_destinoy?>" size="8" maxlength="8" style="text-align:right" ></td>
          </tr>
          <tr class="titulo-ventana">
            <td height="41" align="center" class="celdas-blancas">Detalle Sub-Total/Iva/Total </td>
            <td height="41" align="center" class="celdas-blancas">&nbsp;</td>
            <td height="41" align="center" class="celdas-blancas"><input name="txtmontoletras1_y" type="text" value="<?php print $ls_montoletras1y?>" size="8" maxlength="8" style="text-align:right" ></td>
          </tr>
        </table></td>
    </tr>
  </table>
 
</table>

<input name="operacion" type="hidden" id="operacion">
</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script>
function currencyFormat(fld, milSep, decSep, whichCode) 
{  
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    if (whichCode == 13) return true; // Enter 
    if (whichCode == 8) return true; // Backspace <-
    if (whichCode == 127) return true; // Suprimir -Del 
    key = String.fromCharCode(whichCode); // Get key value from key code 
    if (strCheck.indexOf(key) == -1) return false; // Not a valid key 	
    len = fld.value.length;
    for(i = 0; i < len; i++) 
     if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break; 
    aux = ''; 
    for(; i < len; i++) 
     if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
    aux += key; 
    len = aux.length; 
    if (len == 0) fld.value = ''; 
    if (len == 1) fld.value = '0'+ decSep + '0' + aux; 
    if (len == 2) fld.value = '0'+ decSep + aux; 
    if (len > 2)
	{ 
     aux2 = ''; 
     for (j = 0, i = len - 3; i >= 0; i--)
	 { 
       if (j == 3)
	   { 
         aux2 += milSep; 
         j = 0; 
       } 
       aux2 += aux.charAt(i); 
       j++; 
     } 
     fld.value = ''; 
     len2 = aux2.length; 
     for (i = len2 - 1; i >= 0; i--) 
      fld.value += aux2.charAt(i); 
     fld.value += decSep + aux.substr(len - 2, len); 
    } 
    return false; 
} 

/*****************************************************************
  Funcion que quita a los extremos de un string los espacios
  en blancos
******************************************************************/
function trim(cadena)
{
	return cadena.replace(/^\s*|\s*$/g,"");
}

function ue_valida_null(field,mensaje)
{
    with (field) 
    {
      if (((value==null||trim(value)==""||parseFloat(uf_convertir_monto(value))==0) && 
           (type=="text"||type=="textarea")) ||
	      ((value=="s1") && (type=="select-one")))
      {
		if ((arguments.length > 1) && (mensaje != ""))
		{
		  if ((type=="text") || (type=="textarea"))
		  {alert("Debe Indicar "+mensaje+"!!!");}
		  else if (type=="select-one")
		  {alert("Debe Seleccionar "+mensaje+"!!!")}
		}
        return false;
      }
      else
      {return true;}
    }
}
/*************************************************
  Funcion que coloca el contenido de una caja de
  texto con formato de double (xxx.xxx,xx)
  Ejm: 1..00.0 -> 1.000,00
**************************************************/
function ue_getformat(txt)
{	
	if(ue_valida_null(txt,"") == false)
	{txt.value="0,00";}
	else
	{txt.value=uf_convertir(uf_convertir_monto(txt.value));}
	if ((arguments.length > 1) && (arguments[1] == "i"))
	{txt.value=txt.value.substring(0,txt.value.length-3);}
}
/*-----------------------------------Funcionalidades------------------------------------*/
function  ue_nuevo()
{
	f=document.form1;
	f.operacion.value="NUEVO";
	f.submit();
}

function ue_buscar()
{
	
	f=document.form1;
	f.operacion.value="";		
	
	pagina="sigesp_cat_config_fac.php";
	
	//alert("No tiene permiso para realizar esta operacion");	
	popupWin(pagina,"catalogo",600,250);        
	
	
		

}
	
		 
function ue_cargarconfigfac(ls_margen_sup,ls_ubica_detprod,ls_ubica_total)
		{
		    f=document.form1;
			f.txtmonto_y.value=ls_margen_sup;
			f.txtdestino_y.value=ls_ubica_detprod;			
			f.txtmontoletras1_y.value=ls_ubica_total;
			//f.operacion.value="ue_cargarconfigfac";
			//alert (formalibre);
			f.submit();	
														
       	}
/***********************************************************************************************************************************/

function ue_guardar()
{
	f=document.form1;
	f.operacion.value="GUARDAR";
	f.submit();
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
