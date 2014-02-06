<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Sistema de Configuraci&oacute;n</title>
<meta http-equiv="imagetoolbar" content="no">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../css/principal.css" rel="stylesheet" type="text/css">
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
.Estilo10 {
	font-size: 14px;
	color: #6699CC;
}
-->
</style></head>

<body>
<?php
require_once("../shared/class_folder/class_funciones.php");
$io_funcion=new class_funciones();
$io_funcion->uf_limpiar_sesion();
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="19" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo10">Sistema de Configuración</td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-negrita"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequeñas"><div align="right" class="letras-negrita"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="19" class="cd-menu"><script language="javascript" src="js/menupro.js"></script></td>
  </tr>
</table>

</body>
<script language="javascript">
function ue_cerrar()
{
	window.open("sigespwindow_blank.php","Blank","_self");
}


function cambiar(item,ventana,sistema)
 {
   obj=document.getElementById(item);
   visible=(obj.style.display!="none")
   key=document.getElementById("x" + item);
  f=document.form1;
   if (visible) 
   {
     obj.style.display="none";
     key.innerHTML="<img src='imagenes/folder.gif' width='16' height='16' hspace='0' vspace='0' border='0'>";
   }
   else 
   {
      obj.style.display="block";
      key.innerHTML="<img src='imagenes/folderopen.gif' width='16' height='16' hspace='0' vspace='0' border='0'>";

   }
}

function uf_cambiar(item,ventana,sistema,nombrefis)
 {
   obj=document.getElementById(item);
   visible=(obj.style.display!="none")
   key=document.getElementById("x" + item);
   if (visible) 
   {
     obj.style.display="none";
     key.innerHTML="<img src='imagenes/folder.gif' width='16' height='16' hspace='0' vspace='0' border='0'>";
   }
   else 
   {
    obj.style.display="block";
    key.innerHTML="<img src='imagenes/folderopen.gif' width='16' height='16' hspace='0' vspace='0' border='0'>";
	window.open(nombrefis);
   }
}
    
</script> 
</html>