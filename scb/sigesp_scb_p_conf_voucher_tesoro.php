<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
$li_diasem = date('w');
switch ($li_diasem){
  case '0': $ls_diasem='Domingo';
  break; 
  case '1': $ls_diasem='Lunes';
  break;
  case '2': $ls_diasem='Martes';
  break;
  case '3': $ls_diasem='Mi&eacute;rcoles';
  break;
  case '4': $ls_diasem='Jueves';
  break;
  case '5': $ls_diasem='Viernes';
  break;
  case '6': $ls_diasem='S&aacute;bado';
  break;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Configuraci&oacute;n Formato Cheque Voucher</title>
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
.Estilo4 {color: #6699CC}
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
/////////////////////////////////////////////  SEGURIDAD   /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
	
	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	if(array_key_exists("la_logusr",$_SESSION))
	{
		$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
		$ls_logusr="";
	}
	$ls_sistema="SCB";
	$ls_ventanas="sigesp_scb_p_conf_voucher.php";
	$la_security[1]=$ls_empresa;
	$la_security[2]=$ls_sistema;
	$la_security[3]=$ls_logusr;
	$la_security[4]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_accesos=$io_seguridad->uf_sss_load_permisossigesp();
		}
		else
		{
			$ls_permisos           = $_POST["permisos"];
			$la_accesos["leer"]    = $_POST["leer"];
			$la_accesos["incluir"] = $_POST["incluir"];
			$la_accesos["cambiar"] = $_POST["cambiar"];
			$la_accesos["eliminar"]= $_POST["eliminar"];
			$la_accesos["imprimir"]= $_POST["imprimir"];
			$la_accesos["anular"]  = $_POST["anular"];
			$la_accesos["ejecutar"]= $_POST["ejecutar"];
		}
	}
	else
	{
		$la_accesos["leer"]="";
		$la_accesos["incluir"]="";
		$la_accesos["cambiar"]="";
		$la_accesos["eliminar"]="";
		$la_accesos["imprimir"]="";
		$la_accesos["anular"]="";
		$la_accesos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
	//Inclusión de la clase de seguridad.
	
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
$ls_archivo="reportes/cheque_configurable/medidas_tesoro.txt";
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
			print "alert('Debe generar un Cheque Voucher de prueba para inicializar las medidas (Puede ser que esta opción no esté activa aún)');";
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
		print "alert('Error de Lectura y Escritura: Debe generar un Cheque Voucher de prueba para inicializar las medidas');";
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
			$ls_montoy=number_format($valores[1],2,",",".");
			global $ls_destinox;
			$ls_destinox=number_format($valores[2],2,",",".");
			global $ls_destinoy;
			$ls_destinoy=number_format($valores[3],2,",",".");
			global $ls_montoletras1x;
			$ls_montoletras1x=number_format($valores[4],2,",",".");
			global $ls_montoletras1y;
			$ls_montoletras1y=number_format($valores[5],2,",",".");
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
			print "alert('Error de Lectura y Escritura: Debe generar un Cheque Voucher de prueba para inicializar las medidas');";
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
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
  <td width="778" height="20" colspan="11" bgcolor="#E7E7E7">
    <table width="778" border="0" align="center" cellpadding="0" cellspacing="0">			
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo4">Caja y Banco</td>
	  <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  <tr>
	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	<td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table>
  </td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 

    <td height="20" bgcolor="#FFFFFF" class="toolbar"> <a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_nuevo();"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"></a><a href="javascript:ue_eliminar();"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></td>
  </tr>
</table>

</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action=""> 
 <?php 
		//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=leer     value='$la_accesos[leer]'>");
	print("<input type=hidden name=incluir  id=incluir  value='$la_accesos[incluir]'>");
	print("<input type=hidden name=cambiar  id=cambiar  value='$la_accesos[cambiar]'>");
	print("<input type=hidden name=eliminar id=eliminar value='$la_accesos[eliminar]'>");
	print("<input type=hidden name=imprimir id=imprimir value='$la_accesos[imprimir]'>");
	print("<input type=hidden name=anular   id=anular   value='$la_accesos[anular]'>");
	print("<input type=hidden name=ejecutar id=ejecutar value='$la_accesos[ejecutar]'>");
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
		//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?> 
  <table width="395" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
   
    <tr>
      <td width="393"></td>
    </tr>
    <tr class="titulo-ventana">
      <td height="13" colspan="6" align="center">Configuraci&oacute;n del Formato del Cheque Voucher </td>
    </tr>
    <tr class="titulo-ventana">
      <td height="440" colspan="6" align="center" class="celdas-blancas"><div align="left"></div>        
	  <table width="306" border="1" align="center" cellpadding="0" cellspacing="1" class="contorno">
          
		  
         
		 
          <tr class="formato-azul">
            <td height="13" align="center"><span class="Estilo1">Campo</span></td>
            <td width="91" height="13" align="center"><span class="Estilo1"><span class="Estilo3">X</span><br>
            <span class="Estilo2">(mm desde el borde izquierdo)</span></span></td>
            <td width="96" height="13" align="center"><span class="Estilo1"><span class="Estilo3">Y</span><br>
              <span class="Estilo2">(mm desde el borde superior)</span></span></td>
          </tr>
          <tr class="titulo-ventana">
            <td height="41" align="center" class="celdas-blancas">Monto</td>
            <td height="41" align="center" class="celdas-blancas"><label>
              <input name="txtmonto_x" type="text" value="<?php print $ls_montox?>" size="8" maxlength="8" style="text-align:right" onKeyPress="return(validaCajas(this,'d',event))" onBlur="javascript:ue_getformat(this)">
            </label></td>
            <td height="41" align="center" class="celdas-blancas"><input name="txtmonto_y" type="text" value="<?php print $ls_montoy?>" size="8" maxlength="8" style="text-align:right" onKeyPress="return(validaCajas(this,'d',event))" onBlur="javascript:ue_getformat(this)"></td>
          </tr>
          <tr class="titulo-ventana">
            <td height="41" align="center" class="celdas-blancas">Proveedor/Beneficiario</td>
            <td height="41" align="center" class="celdas-blancas"><input name="txtdestino_x" type="text" value="<?php print $ls_destinox?>" size="8" maxlength="8" style="text-align:right" onKeyPress="return(validaCajas(this,'d',event))" onBlur="javascript:ue_getformat(this)"></td>
            <td height="41" align="center" class="celdas-blancas"><input name="txtdestino_y" type="text" value="<?php print $ls_destinoy?>" size="8" maxlength="8" style="text-align:right" onKeyPress="return(validaCajas(this,'d',event))" onBlur="javascript:ue_getformat(this)"></td>
          </tr>
          <tr class="titulo-ventana">
            <td height="41" align="center" class="celdas-blancas">Monto en Letras (L&iacute;nea 1) </td>
            <td height="41" align="center" class="celdas-blancas"><input name="txtmontoletras1_x" type="text" value="<?php print $ls_montoletras1x?>" size="8" maxlength="8" style="text-align:right" onKeyPress="return(validaCajas(this,'d',event))" onBlur="javascript:ue_getformat(this)"></td>
            <td height="41" align="center" class="celdas-blancas"><input name="txtmontoletras1_y" type="text" value="<?php print $ls_montoletras1y?>" size="8" maxlength="8" style="text-align:right" onKeyPress="return(validaCajas(this,'d',event))" onBlur="javascript:ue_getformat(this)"></td>
          </tr>
          <tr class="titulo-ventana">
            <td height="41" align="center" class="celdas-blancas">Monto en Letras (L&iacute;nea 2) </td>
            <td height="41" align="center" class="celdas-blancas"><input name="txtmontoletras2_x" type="text" value="<?php print $ls_montoletras2x?>" size="8" maxlength="8" style="text-align:right" onKeyPress="return(validaCajas(this,'d',event))" onBlur="javascript:ue_getformat(this)"></td>
            <td height="41" align="center" class="celdas-blancas"><input name="txtmontoletras2_y" type="text" value="<?php print $ls_montoletras2y?>" size="8" maxlength="8" style="text-align:right" onKeyPress="return(validaCajas(this,'d',event))" onBlur="javascript:ue_getformat(this)"></td>
          </tr>
          <tr class="titulo-ventana">
            <td height="41" align="center" class="celdas-blancas">Ciudad, D&iacute;a y Mes </td>
            <td height="41" align="center" class="celdas-blancas"><input name="txtfecha_x" type="text" value="<?php print $ls_fechax?>" size="8" maxlength="8" style="text-align:right" onKeyPress="return(validaCajas(this,'d',event))" onBlur="javascript:ue_getformat(this)"></td>
            <td height="41" align="center" class="celdas-blancas"><input name="txtfecha_y" type="text" value="<?php print $ls_fechay?>" size="8" maxlength="8" style="text-align:right" onKeyPress="return(validaCajas(this,'d',event))" onBlur="javascript:ue_getformat(this)"></td>
          </tr>
          <tr class="titulo-ventana">
            <td height="41" align="center" class="celdas-blancas">A&ntilde;o</td>
            <td height="41" align="center" class="celdas-blancas"><input name="txtano_x" type="text" value="<?php print $ls_anox?>" size="8" maxlength="8" style="text-align:right" onKeyPress="return(validaCajas(this,'d',event))" onBlur="javascript:ue_getformat(this)"></td>
            <td height="41" align="center" class="celdas-blancas"><input name="txtano_y" type="text" value="<?php print $ls_anoy?>" size="8" maxlength="8" style="text-align:right" onKeyPress="return(validaCajas(this,'d',event))" onBlur="javascript:ue_getformat(this)"></td>
          </tr>
          <tr class="titulo-ventana">
            <td height="41" align="center" class="celdas-blancas">NO ENDOSABLE </td>
            <td height="41" align="center" class="celdas-blancas"><input name="txtnoendosable_x" type="text" value="<?php print $ls_noendosablex?>" size="8" maxlength="8" style="text-align:right" onKeyPress="return(validaCajas(this,'d',event))" onBlur="javascript:ue_getformat(this)"></td>
            <td height="41" align="center" class="celdas-blancas"><input name="txtnoendosable_y" type="text" value="<?php print $ls_noendosabley?>" size="8" maxlength="8" style="text-align:right" onKeyPress="return(validaCajas(this,'d',event))" onBlur="javascript:ue_getformat(this)"></td>
          </tr>
          <tr class="titulo-ventana">
            <td height="41" align="center" class="celdas-blancas">CADUCA A LOS __ DIAS </td>
            <td height="41" align="center" class="celdas-blancas"><input name="txtcaduca_x" type="text" value="<?php print $ls_caducax?>" size="8" maxlength="8" style="text-align:right" onKeyPress="return(validaCajas(this,'d',event))" onBlur="javascript:ue_getformat(this)"></td>
            <td height="41" align="center" class="celdas-blancas"><input name="txtcaduca_y" type="text" value="<?php print $ls_caducay?>" size="8" maxlength="8" style="text-align:right" onKeyPress="return(validaCajas(this,'d',event))" onBlur="javascript:ue_getformat(this)"></td>
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
/*************************************************
  Funcion que valida el texto de un caja de texto
  segun el tipo de dto que se quiere validar
  "x" -> Cualquier caracter menos comillas simples(') y comillas dobles(")
  "i" -> Numericos (Ejm: Codigos)
  "c" -> Numericos con guiones (Ejm: Cuentas Bancarias)
  "s" -> Alfabeticos (Ejm: Nombres)
  "a" -> Alfanumericos (Ejm: Direcciones)
  "e" -> email
  "t" -> telefono (Ejm: 0251-2555555)
  "g" -> Codigos alfanumericos y guiones
  "d" -> double (Ejm: 2.000.000,00)
  "m" -> enteros con puntos de miles (Ejm: 123.456.789)
  NOTA: Algunos caracteres para guiarse 
   Backspace=8, Enter=13, Barra Espaciadora= 32, '0'=48, '9'=57, 'A'=65, 'Z'=90, 'a'=97, 'z'=122		
**************************************************/
//var nav4 = window.Event ? true : false;
function validaCajas(cajaTexto,tipo_dato,evt)
{
	key = evt.which || evt.keyCode;
	if (key <= 13)
	{return true;}
	if ((tipo_dato == "x")||(tipo_dato == "i")||(tipo_dato == "c")||(tipo_dato == "s")||
	    (tipo_dato == "a")||(tipo_dato == "e")||(tipo_dato == "t")||(tipo_dato == "g")||
		(tipo_dato == "r")||
		(tipo_dato == 0)||(tipo_dato == 1)||(tipo_dato == 2)||(tipo_dato == 3)||
	    (tipo_dato == 4)||(tipo_dato == 5)||(tipo_dato == 6)||(tipo_dato == 7)||
		(tipo_dato == 10))
	{
		if (((arguments.length > 3) && (cajaTexto.value.length < arguments[3])) ||
		    (arguments.length <= 3))
		{
			switch(tipo_dato)
			{
				case "x": case 0: return ((key != 34) && (key != 39));break;
				case "i": case 1: return ((key >= 48 && key <= 57)); break;
				case "c": case 2: return ((key >= 48 && key <= 57) || (key == 45)); break;
				case "s": case 3: return ((key == 32) || 
								  (key >= 65 && key <= 90) || (key >= 97 && key <= 122) || 
								  (key == 225) || (key == 233) || (key == 237) || (key == 243) || (key == 250) || //VOCALES MINUSCULAS ACENTUADAS
								  (key == 193) || (key == 201) || (key == 205) || (key == 211) || (key == 218) || //VOCALES MAYUSCULAS ACENTUADAS
								  (key == 241) || (key == 209) || (key == 44) || (key == 46) // Ñ, ñ, "," y "."
								  ); break;
				case "a": case 4: return ((key == 32) || (key >= 48 && key <= 57) || 
								  (key >= 65 && key <= 90) || (key >= 97 && key <= 122) || 
								  (key == 225) || (key == 233) || (key == 237) || (key == 243) || (key == 250) || //VOCALES MINUSCULAS ACENTUADAS
								  (key == 193) || (key == 201) || (key == 205) || (key == 211) || (key == 218) || //VOCALES MAYUSCULAS ACENTUADAS
								  (key == 241) || (key == 209) || (key == 44)// Ñ, ñ y ","
								  ); break;
				case "e": case 5: return ((key >= 45 && key <= 57) || (key >= 65 && key <= 122) || (key == 64 && cajaTexto.value.indexOf('@', 0) == -1));break;
				case "t": case 6: if (cajaTexto.value.length == 4 && cajaTexto.value.indexOf('-', 0) == -1 && key != 8)
						          {cajaTexto.value = cajaTexto.value + "-";}
						          return ((key > 48 && key <= 57 && cajaTexto.value != "") || (key == 48 ));break;
				case "g": case 7: return ((key == 32) || (key >= 48 && key <= 57) ||
								  (key >= 65 && key <= 90) || (key >= 97 && key <= 122) ||
								  (key == 241) || (key == 209) || (key == 45)// Ñ, ñ y "-"
								  ); break;
				case "r": case 10: return ((key >= 48 && key <= 57 && cajaTexto.value != "") || (cajaTexto.value == "" && (key == 74 || key == 106)));break;
			}
		}
		else
		{return false;}
	}
	else
	{
		switch(tipo_dato)
		{
			case "d":
			case 8  : if (arguments.length > 3)			
					  {
						if (parseFloat(uf_convertir_monto(cajaTexto.value)) == 0)
						{cajaTexto.value = "";}
						if (document.selection)//IE
						  selecciono = (document.selection.createRange().text.length > 0);
						else//NS ó MFF
						  selecciono = (cajaTexto.selectionStart < cajaTexto.value.length);
						if ((cajaTexto.value.length < arguments[3]) || (key <= 13) || (selecciono))
						{
						  if (selecciono)
						  {
							if (document.selection)//IE
							{
							  seleccion = document.selection.createRange();
							  seleccion.text="";
							  cajaTexto.createTextRange().moveStart('character',-1);
							  cajaTexto.createTextRange().moveEnd('character',0);
							  cajaTexto.createTextRange().select();
							}
							else//Otro NS ó MFF
							{
							  cajaTexto.value = (cajaTexto.value).substring(0,cajaTexto.selectionStart);
							}
						  };
						  return (currencyFormat(cajaTexto,'.',',',key));
						}
						else
						{return false;}
					  }
					  else
					  {return (currencyFormat(cajaTexto,'.',',',key));}
					  break;
			case "m": 
			case 9  : if (arguments.length > 3)
					  {
						if (document.selection)//IE
						  selecciono = (document.selection.createRange().text.length > 0);
						else//NS ó MFF
						  selecciono = (cajaTexto.selectionStart < cajaTexto.value.length);
						if ((cajaTexto.value.length < arguments[3]) || (key <= 13) || (selecciono))
						{
						   if (parseFloat(uf_convertir_monto(cajaTexto.value)) == 0)
						   {cajaTexto.value = "";}
						   return (FormatoMiles(cajaTexto,'.',key));
						}
						else
						{return false;}
					  }
					  else
					  {return (FormatoMiles(cajaTexto,'.',key));} 
					  break;			
		}
	}
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

function ue_guardar()
{
	f=document.form1;
	f.operacion.value="GUARDAR";
	f.submit();
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
