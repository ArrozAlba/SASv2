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
require_once("../../../class_folder/utilidades/class_funciones_srh.php");
$io_fun_srh=new class_funciones_srh('../../../../');
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_puntuacion_bono_merito.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Definici&oacute;n de Puntuaci&oacute;n Bono M&eacute;rito </title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
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
<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_puntuacion_bono_merito.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../../../sno/js/funcion_nomina.js"></script>

<script language="javascript">
	if(document.all)
	{ 
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
.style1 {color: #EBEBEB}
.Estilo1 {
	color: #6699CC;
	font-size: 12px;
	font-family: Georgia, "Times New Roman", Times, serif;
}
-->
</style>
</head>

<body onLoad="javascript: ue_nuevo();">
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../../../../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Recursos Humanos</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
 <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="../../js/menu/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../../../../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../../../../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_eliminar();"><img src="../../../../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../../../../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../../../../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>


<p>&nbsp;</p>
<div align="center">
  <table width="596" height="159" border="0" class="formato-blanco">
    <tr>
      <td width="588" height="153"><div align="left">
          <form name="form1" method="post" action="">
            <p>
              <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_srh->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_srh);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
</p>
            <p>&nbsp;</p>
            <table width="595" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td colspan="3" class="titulo-ventana">Definici&oacute;n de Puntuaci&oacute;n Bono M&eacute;rito </td>
  </tr>
  <tr class="formato-blanco">
    <td width="83" height="19">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr class="formato-blanco">
    <td height="29"><div align="right">C&oacute;digo</div></td>
    <td width="428" height="29"><input name="txtcodpunt" type="text" id="txtcodpunt"  size="16" maxlength="15"  readonly style="text-align:center ">
        <input name="hidstatus" type="hidden" id="hidstatus">  </td>
    <td width="82" class="sin-borde"><div id="existe" class="letras-pequeñas" style="display:none"> 
		</div></td>
  </tr>
  <tr class="formato-blanco">
    <td height="28"><div align="right">Denominaci&oacute;n</div></td>
    <td height="28" colspan="2"><input name="txtnombpunt" type="text" id="txtnombpunt" maxlength="254" onKeyUp="ue_validarcomillas(this);" size="60" ></td>
  </tr>
  
  <tr class="formato-blanco"> 
 <td height="28"><div align="right">Descripci&oacute;n</div></td>
  <td height="28" valign="middle"><textarea name="txtdespunt" cols="80" id="txtdespunt" maxlength="254" onKeyUp="ue_validarcomillas(this);"></textarea></td>
         <td>&nbsp;</td>
  </tr>
  
  <tr class="formato-blanco">
    <td height="28"><div align="right">Valor Inicial</div></td>
    <td height="28" colspan="2"><input name="txtvalini" type="text" id="txtvalini"   size="8" maxlength="4" onKeyUp="javascript: ue_validarnumero2(this);"></td>
  </tr>
  
   <tr class="formato-blanco">
    <td height="28"><div align="right">Valor Final</div></td>
    <td height="28" colspan="2"><input name="txtvalfin" type="text" id="txtvalfin"   size="8" maxlength="4" onKeyUp="javascript: ue_validarnumero2(this);" onChange="javascript:valida_escala(txtvalini,this);"></td>
  </tr>
  
   <tr class="formato-blanco">
    <td height="28"><div align="right">Tipo Personal</div></td>
    <td height="28" colspan="2"><input name="txtcodtipper" type="text" id="txtcodtipper"    size="5" maxlength="3" style="text-align:center" readonly> <a href="javascript:catalogo_tipo_personal();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Factores de Evaluaci&oacute;n" name="buscartip" width="15" height="15" border="0" id="buscartip"></a>
      <input name="txtdentipper" type="text" class="sin-borde" id="txtdentipper" size="60s" maxlength="80" readonly>
    </td>
          
		</tr>
</table>
          <input name="operacion" type="hidden" id="operacion">
		  <input name="hidcontrol" type="hidden" id="hidcontrol" value="">
          </form>
      </div>
	  <p>&nbsp;</p>
      </td>
    </tr>
  </table>
</div>

<div align="center"></div>
<p align="center" class="style1" id="mostrar" style="font:#EBEBEB" ></p>
</body>
<script language="javascript">

//Funciones de operaciones 

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("../catalogos/sigesp_srh_cat_puntuacion_bono_merito.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}



</script> 
</html>