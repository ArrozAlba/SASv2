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
	require_once("class_folder/class_funciones_ins.php");
	$oi_fun_instala=new class_funciones_ins("../");
	$oi_fun_instala->uf_load_seguridad("INS","sigesp_ins_r_solicitudpago.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
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
<title>Solicitudes de Pago sin Detalle Asociado</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="../ins/js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
-->
</style></head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="../ins/js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></td>
  </tr>
</table>
  <?php
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	
	require_once("../shared/class_folder/sigesp_include.php");
	$sig_inc=new sigesp_include();
	$con=$sig_inc->uf_conectar();

$la_emp=$_SESSION["la_empresa"];
?>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$oi_fun_instala->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($oi_fun_instala);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="350" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
   
    <tr>
      <td width="435"></td>
    </tr>
    <tr class="titulo-ventana">
      <td width="435" height="13" colspan="4" align="center"><div align="center">Solicitudes de Pago sin Detalle Asociado </div></td>
    </tr>
    <tr>
      <td height="13" colspan="4" align="center">&nbsp;</td>
    </tr>
    <tr>
      	<td colspan="4" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="4" align="center"><label>
      	<input name="botimprimir" type="button" class="boton" id="botimprimir" value="Imprimir" onClick="javascript:ue_imprimir();">
      </label></td>
    </tr>
    <tr>
      <td height="22" colspan="4" align="center"><div align="right">     <span class="Estilo1">
        <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
      </span></div></td>
    </tr>
  </table>
 
</table>
</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function ue_imprimir()
{
  f=document.form1;
  pagina="reportes/sigesp_ins_rpp_solicitudespago.php";
  window.open(pagina,"comprobantes","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
