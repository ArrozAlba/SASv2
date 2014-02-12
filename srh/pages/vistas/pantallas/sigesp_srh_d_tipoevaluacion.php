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
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_tipoevaluacion.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Definici&oacute;n de Tipo de Evaluaci&oacute;n</title>
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
.Estilo1 {
	color: #6699CC;
	font-size: 12px;
	font-family: Georgia, "Times New Roman", Times, serif;
}

-->
</style>

<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_tipoevaluacion.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../../../sno/js/funcion_nomina.js"></script>


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
  <table width="646" height="178" border="0" class="formato-blanco">
    <tr>
      <td width="647" height="174"><div align="left">
          <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_srh->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_srh);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="566" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="20" colspan="3" class="titulo-celdanew">Definici&oacute;n de Tipo de Evaluaci&oacute;n</td>
  </tr>
  <tr class="formato-blanco">
    <td width="97" height="19">&nbsp;</td>
    <td colspan="2"><div id="resultado" ></div></td>
  </tr>
  <tr class="formato-blanco">
    <td height="29"><div align="right">C&oacute;digo</div></td>
    <td width="155" height="29"><input name="txtcodeval" type="text" id="txtcodeval"  size="16" maxlength="15"  onKeyUp=" " onBlur="javascript: ue_validaexiste();   "  readonly style="text-align:center ">
        <input name="hidstatus" type="hidden" id="hidstatus">    </td>
    <td width="314" class="sin-borde"><div id="existe" class="letras-pequeñas" style="display:none"></div></td>
  </tr>
  <tr class="formato-blanco">
    <td height="28"><div align="right">Denominaci&oacute;n</div></td>
    <td height="28" colspan="2"><input name="txtdeneval" type="text" id="txtdeneval"  onKeyUp="ue_validarcomillas(this);" size="60" maxlength="254"></td>
  </tr>
   <tr class="formato-blanco"> 
 <td height="28"><div align="right">Escala de Evaluaci&oacute;n</div></td>
  <td height="28" valign="middle"><input name="txtcodesc" type="text" id="txtcodesc"   size="16" maxlength="15"  style="text-align:center" readonly>
          <a href="javascript:catalogo_escala();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Escala de Evaluaci&oacute;n" name="buscartip" width="15" height="15" border="0" id="buscartip"></a></td>
         <td> <input name="txtdenesc" type="text" class="sin-borde" id="txtdenesc" v size="50
          " maxlength="80" readonly></td>
    </tr>
  
</table>
<input name="operacion" type="hidden" id="operacion">
<input name="hidguardar" type="hidden" id="hidguardar">
<input name="hidcontrol" type="hidden" id="hidcontrol" value="">
<input name="hidcontrol2" type="hidden" id="hidcontrol2" value="">
<input name="hidcontrol3" type="hidden" id="hidcontrol3" value="1">
          </form>
      </div></td>
    </tr>
  </table>
</div>
<div align="center"></div>
<p align="center" class="oculto1" id="mostrar" style="font:#EBEBEB"  ></p>
</body>
 
</html>