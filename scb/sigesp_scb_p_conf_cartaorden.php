<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
$ls_logusr = $_SESSION["la_logusr"];
require_once("class_funciones_banco.php");
$io_fun_banco= new class_funciones_banco();
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_conf_cartaorden.php",$ls_permisos,$la_seguridad,$la_permisos);
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
<title>Selecci&oacute;n de Formato de Impresi&oacute;n de Carta Orden</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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

<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>

<body>

<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
  <td width="778" height="20" colspan="11" bgcolor="#E7E7E7">
    <table width="778" border="0" align="center" cellpadding="0" cellspacing="0">			
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Caja y Banco</td>
	  <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  <tr>
	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	<td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table>
  </td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" class="toolbar"><div align="left"><a href="javascript: ue_nuevo();"><img  src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" title="Buscar" width="20" height="20" border="0"></a>
	<a href="javascript:ue_imprimir()"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" title="Imprimir" width="20" height="20" border="0"></a>
	<a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" title="Eliminar" width="20" height="20" border="0"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></div>      
    <div align="center"></div>      <div align="center"></div>      <div align="center"></div>      <div align="center"></div></td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/class_mensajes.php");
	$msg=new class_mensajes();

	require_once("sigesp_scb_c_config.php");
	$in_classconfig=new sigesp_scb_c_config($la_seguridad);

	if( array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_codigo=$_POST["txtcodigo"];
		$ls_nombre=$_POST["txtnombre"];
		$ls_nomrtf=$_POST["txtnomrtf"];
		$ls_encabezado=$_POST["txtencabezado"];
		$ls_cuerpo=$_POST["txtcuerpo"];
		$ls_pie=$_POST["txtpie"];
		$ls_status=$_POST["hidstatus"];		
	}
	else
	{
		$ls_operacion="";
		$ls_codigo=$in_classconfig->uf_generar_codigo();
		$ls_nombre="";
		$ls_nomrtf="";
		$ls_encabezado="";
		$ls_cuerpo="";
		$ls_pie="";		
		$ls_status="";		
	}

	if($ls_operacion == "GUARDAR")
	{
		/////////////////////////////////////////////////////////////////
		// Código Nuevo para plantear la plantilla en rtf de cata orden
		/////////////////////////////////////////////////////////////////
		$ls_archrtf=$HTTP_POST_FILES['txtarchrtf']['name'];
		if(strlen($ls_archrtf)>50)
		{
			$in_classconfig->io_msg->message("La Longitud del Nombre del Archivo es mayor a 50 caracteres."); 
			$lb_valido=false;
		} 
		if($ls_archrtf!="")
		{
			$ls_tiparc=$HTTP_POST_FILES['txtarchrtf']['type']; 
			$ls_tamarc=$HTTP_POST_FILES['txtarchrtf']['size']; 
			$ls_nomtemarc=$HTTP_POST_FILES['txtarchrtf']['tmp_name'];
			$ls_archrtf=$in_classconfig->uf_upload($ls_archrtf,$ls_tiparc,$ls_tamarc,$ls_nomtemarc);
		}
		/////////////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////////////////
		$lb_valido=$in_classconfig->uf_guardar_cartaorden($ls_codigo,$ls_nombre,$ls_encabezado,$ls_cuerpo,$ls_pie,$ls_archrtf);
		$msg->message($in_classconfig->is_msg_error);
		if($lb_valido)
		{
			$in_classconfig->io_sql->commit();
					
		}
		else
		{
			$in_classconfig->io_sql->rollback();
		}			
	}	
	elseif($ls_operacion == "ELIMINAR")
	{
		$lb_valido=$in_classconfig->uf_eliminar_cartaorden($ls_codigo);
		$msg->message($in_classconfig->is_msg_error);
		if($lb_valido)
		{
			$in_classconfig->io_sql->commit();	
			$ls_operacion="";
			$ls_codigo=$in_classconfig->uf_generar_codigo();
			$ls_nombre="";
			$ls_nomrtf="";
			$ls_encabezado="";
			$ls_cuerpo="";
			$ls_pie="";		
			$ls_status="";					
		}
		else
		{
			$in_classconfig->io_sql->rollback();
		}
	}

?>
<p>&nbsp;</p>
<div align="center">
  <table width="677" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="675" height="410" valign="top">
<form name="form1" method="post" enctype="multipart/form-data" action="">
<p>
<?php 
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
</p><br>
		<table width="647" height="335" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td colspan="3">Configuraci&oacute;n de Formato de Impresi&oacute;n de Carta Orden</td>
              </tr>
              <tr class="formato-blanco">
                <td width="184" height="23"><div align="right">C&oacute;digo</div></td>
                <td width="461" colspan="2"><label>
                  <div align="left">
                    <input name="txtcodigo" type="text" id="txtcodigo" value="<?php print $ls_codigo?>" size="3" maxlength="3" style="text-align:center" readonly="true">
                  </div>
                </label></td>
              </tr>
              
              <tr class="formato-blanco">
                <td height="22"><div align="right">Nombre</div></td>
                <td colspan="2"><label>
                  <div align="left">
                    <input name="txtnombre" type="text" id="txtnombre" value="<?php print $ls_nombre;?>" size="50" maxlength="50">
                  </div>
                </label></td>
              </tr>
			  
            
              <tr class="formato-blanco">
                <td height="20"><div align="right">Platilla RTF </div></td>
                <td colspan="2">
                  <div align="left">
                    <input name="txtnomrtf" type="text" id="txtnomrtf" size="50" maxlength="60" value="<?php print $ls_nomrtf;?>" readonly>
                  </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="20"><div align="right">Actualizar Plantilla RTF </div></td>
                <td colspan="2"><div align="left">
                  <input name="txtarchrtf" type="file" id="txtarchrtf" size="50" maxlength="200">
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="255" colspan="3"><div align="center"></div>
                  <table width="615" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">

                  <tr class="formato-blanco">
                    <td width="142" height="153" rowspan="4"><label>
                      <select name="lista" size="11" >
                        <option value="@banco@" onDblClick="javascript:uf_pasar('','selStart','selEnd');">Banco</option>
                        <option value="@ciudad@" onDblClick="javascript:uf_pasar('','selStart','selEnd');">Ciudad</option>
						<option value="@empresa@" onDblClick="javascript:uf_pasar('','selStart','selEnd');">Nombre de la Empresa</option>
                        <option value="@fecha@" onDblClick="javascript:uf_pasar('','selStart','selEnd');">Fecha</option>
                        <option value="@gerente@" onDblClick="javascript:uf_pasar('','selStart','selEnd');">Gerente del Banco</option>
                        <option value="@cartaorden@" onDblClick="javascript:uf_pasar('','selStart','selEnd');">N&ordm; de Carta Orden</option>
                        <option value="@documento@" onDblClick="javascript:uf_pasar('','selStart','selEnd');">N&ordm; de Documento</option>
                        <option value="@cuentabancaria@" onDblClick="javascript:uf_pasar('','selStart','selEnd');">N&ordm; de Cuenta Movimiento</option>						
                        <option value="@monto@" onDblClick="javascript:uf_pasar('','selStart','selEnd');">Monto</option>
                        <option value="@montoletras@" onDblClick="javascript:uf_pasar('','selStart','selEnd');">Monto en Letras</option>
                        <option value="@tipocuenta@" onDblClick="javascript:uf_pasar('','selStart','selEnd');">Tipo de Cuenta</option>
                      </select>
                    </label></td>
                    <td width="39"><label></label></td>
                    <td width="80" height="34"><label></label></td>
                    <td width="128"><div align="right"><a href="javascript:uf_formato('b');"><img src="imagenes/bold.gif" width="23" height="22"  border="0"></a></div></td>
                    <td width="114"><a href="javascript:uf_formato('u')"><img src="imagenes/underline.gif" width="23" height="22" border="0"></a></td>
                    <td width="110">&nbsp;</td>
                  </tr>
                  <tr class="formato-blanco">
                    <td width="39"><input name="button3" type="button" onClick="javascript:uf_pasar(document.form1.txtencabezado,'selStart','selEnd');" value=">>"></td>
                    <td height="91" colspan="4">
                      <div align="center">
                        <textarea name="txtencabezado" cols="77" rows="5" wrap="physical" id="txtencabezado" onMouseUp="inputKey(this, event,'2850','selStart','selEnd')" onKeyUp="inputKey(this, event,'2850','selStart','selEnd')" onFocus="javascript:uf_setfocus('txtencabezado');"><?php print $ls_encabezado;?></textarea>
                      </div></td></tr>
                  <tr class="formato-blanco">
                    <td width="39"><label>
                      <input name="button" type="button" value=">>" onClick="javascript:uf_pasar(document.form1.txtcuerpo,'selStart2','selEnd2');">
                    </label></td>
                    <td height="100" colspan="4" align="center" valign="top"><label>

                      <div align="center">
                            <p>
                              <textarea name="txtcuerpo" cols="77" rows="5" id="txtcuerpo" onmouseup="inputKey(this, event,'2868','selStart2','selEnd2')" onkeyup="inputKey(this, event,'2868','selStart2','selEnd2')" onFocus="javascript:uf_setfocus('txtcuerpo');"><?php print $ls_cuerpo;?></textarea>
                            </p>
                            <p>Detalle de Carta Orden </p>
                      </div>
                    </label></td></tr>
                  <tr class="formato-blanco">
                    <td width="39"><label>
                      <input name="button2" type="button" value=">>" onClick="javascript:uf_pasar(document.form1.txtpie,'selStart3','selEnd3');">
                    </label></td>
                    <td height="99" colspan="4"><label>

                      <div align="center">
                            <textarea name="txtpie" cols="77" rows="5" id="txtpie" onmouseup="inputKey(this, event,'2886','selStart3','selEnd3')" onkeyup="inputKey(this, event,'2886','selStart3','selEnd3')" onFocus="javascript:uf_setfocus('txtpie');"><?php print $ls_pie;?></textarea>
                      </div>
                        </label></td></tr>
                </table></td>
              </tr>
          </table>
            <p><input name="operacion" type="hidden" id="operacion">
               <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_status;?>">
			   <input name="selStart" type="hidden" id="selStart">
			   <input name="selEnd" type="hidden" id="selEnd">
			   <input name="selStart2" type="hidden" id="selStart2">
			   <input name="selEnd2" type="hidden" id="selEnd2">
			   <input name="selStart3" type="hidden" id="selStart3">
			   <input name="selEnd3" type="hidden" id="selEnd3" >
			   <input type="hidden" name="hidfocus" id="hidfocus"></p>
			  
        </form></td>
      </tr>
  </table>
</div>
</body>
<script language="javascript">

function ue_guardar()
{
	f=document.form1;
    f.operacion.value ="GUARDAR";
    f.submit();
}

function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}

function uf_pasar(txt,start,end)
{
	f=document.form1;
	ls_seleccionado=f.lista.value;
	if(txt=="")
	{
		cajita=f.hidfocus.value;
		txt=document.getElementById(cajita);
		if(cajita=="txtencabezado")
			start="selStart";
		else if(cajita=="txtcuerpo")
			start="selStart2";
		else if(cajita=="txtpie")
			start="selStart3";
	}
	//alert(txt.value);		
	if(navigator.appName=="Netscape")
	{
		ls_cadena1=txt.value.slice(0,document.getElementById(start).value);
		ls_cadena2=txt.value.slice(document.getElementById(start).value,txt.value.length);
		ls_cadena=ls_cadena1+ls_seleccionado+ls_cadena2;
		txt.value=ls_cadena;
	}
	else
	{
		txt.value=txt.value+" "+ls_seleccionado;
	}
}

function ue_nuevo()
{
	location.href="sigesp_scb_p_conf_cartaorden.php";
}

function ue_buscar()
{
	window.open("sigesp_cat_cartaorden.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=300,left=30,top=30,location=no,resizable=yes");
}

function ue_eliminar()
{
	f=document.form1;
	if(confirm("Esta seguro de eliminar el registro? \n Esta operación no puede ser reversada"))
	{	
		f.operacion.value="ELIMINAR";
		f.submit();
	}
}
function uf_setfocus(cajita)
{
	document.form1.hidfocus.value=cajita;
}

function uf_formato(tipo)
{
	f=document.form1;
	if(tipo=='b')
	{
		ls_cadena_inicio="<b>";
		ls_cadena_fin="</b>";
	}
	else
	{
		ls_cadena_inicio="<u>";
		ls_cadena_fin="</u>";
	}	
	if(navigator.appName=="Netscape")
	{
			
		cajita=f.hidfocus.value;
		txt=document.getElementById(cajita);
		if(cajita=="txtencabezado")
		{
			start="selStart";
			end="selEnd";
		}
		else if(cajita=="txtcuerpo")
		{
			start="selStart2";
			end="selEnd2";
		}
		else if(cajita=="txtpie")
		{
			start="selStart3";
			end="selEnd3";
		}	
		
		ls_cadena1=txt.value.slice(0,document.getElementById(start).value);
		ls_cadena2=txt.value.slice(document.getElementById(start).value,document.getElementById(end).value);
		ls_cadena3=txt.value.slice(document.getElementById(end).value,txt.value.length);
		ls_cadena=ls_cadena1+ls_cadena_inicio+ls_cadena2+ls_cadena_fin+ls_cadena3;
		txt.value=ls_cadena;
	}
	else
	{
		txt.value=txt.value+ls_cadena_inicio+ls_cadena_fin;
	}
}

function ue_imprimir()
{
	f=document.form1;
	ls_codigo=f.txtcodigo.value;
	ls_opener="conf";
	window.open("reportes/sigesp_scb_rpp_cartaorden_pdf.php?codigo="+ls_codigo+"&opener="+ls_opener,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=600,left=0,top=0,location=no,resizable=yes");
}

//----------------------------------Funciones para obtener la posicion del cursor--------------------//
var is_gecko = /gecko/i.test(navigator.userAgent);
var is_ie    = /MSIE/.test(navigator.userAgent);

function setSelectionRange(input, start, end) {
	input.focus();
	if (is_gecko) {
		input.setSelectionRange(start, end);
	} else {
		// assumed IE
		var range = input.createTextRange();
		range.collapse(true);
		range.moveStart("character", start);
		range.moveEnd("character", end - start);
		range.select();
	}
};

function getSelectionStart(input,valor) {
	input.focus();
	if (is_gecko)
		return input.selectionStart;
	var range = document.selection.createRange();
	//alert(range.text);
	var isCollapsed = range.compareEndPoints("StartToEnd", range) == 0;
	if (!isCollapsed)
		range.collapse(true);
	var b = range.getBookmark();
	//alert((b.charCodeAt(2) - parseInt(valor)));
	return b.charCodeAt(2) - parseInt(valor);
};

function getSelectionEnd(input,valor) {
	input.focus();
	if (is_gecko)
		return input.selectionEnd;
	var range = document.selection.createRange();
	var isCollapsed = range.compareEndPoints("StartToEnd", range) == 0;
	if (!isCollapsed)
		range.collapse(false);
	var b = range.getBookmark();
	return b.charCodeAt(2) - parseInt(valor);
};

function inputKey(input,ev,valor,start,end) {
//setTimeout(function() {
if(navigator.appName=="Netscape")
{
  document.getElementById(start).value = getSelectionStart(input,valor);
  document.getElementById(end).value = getSelectionEnd(input,valor);
}
else
{
  document.getElementById(start).value = 0;
  document.getElementById(end).value = 0;
}

//}, 20);
}
function doSelect() {
var start = document.getElementById("selStart").value;
var end = document.getElementById("selEnd").value;
var input = document.getElementById("testfield");
input.focus();
setSelectionRange(input, start, end);
}

</script>
</html>