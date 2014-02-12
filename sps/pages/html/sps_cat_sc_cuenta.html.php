<?php
  header("Cache-Control:no-cache");
  header("Pragma:no-cache")
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Catálogo de Cuentas Contables</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<!-- LIBRERIAS Y ESTILOS COMUNES EN TODAS LAS PAGINAS -->
<script type="text/javascript" language="JavaScript1.2" src="../../../shared/js/librerias_comunes.js"></script>

<!-- LIBRERIA ESPECIFICA DE ESTA PAGINA -->
<script type="text/javascript" language="javascript1.2" src="../js/sps_cat_sc_cuenta.js"></script>

<link href="../../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../../../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body onLoad="javascript:ue_ver_sc_cuentas();">
<form name="form1" method="post" action="">
 <table width="500" border="0"  class="formato-blanco" >
	<tr class="titulo-celda">
	  <td colspan="2" class="titulo-ventana">
	  Cat&aacute;logo de Cuentas Contables </td>
	</tr>
	<tr><td colspan="2"><td width="1%"></tr>
	<tr>
	  <td colspan="2">    
	  <td>        </tr>
	<tr>
	  <td width="30%"><div align="right">Código</div>
	  <td width="69%"><label>
	  <input name="txtcodigo" type="text" id="txtcodigo" onKeyPress="return validaCajas(this,'i',event,50);" onKeyUp="ue_ver_sc_cuentas(this);">
	  </label>      
	  <td>        </tr>
	<tr>
	  <td><div align="right">Denominación    </div>
	  <td><label>
	    <input name="txtdenominacion" type="text" id="txtdenominacion" onKeyPress="return validaCajas(this,'s',event,50);" onKeyUp="ue_ver_sc_cuentas(this);">
	  </label>      
	  <td>        </tr>
	<tr>
	  <td colspan="2">    
	  <td>        </tr>
	<tr><td colspan="2">
	 <!-- Marcador para mostrar la numeracion de los registros -->
	 <div class="estilo-catalogo" id="marcador" name="marcador">Mostrando Registros</div> 
	<td></tr>
 </table>
 
 <!-- Tabla para la cabecera (titulos) -->
 <table id="data_grid_header" width="500" class="fondo-tabla" cellpadding="1" cellspacing="1">
  <tr class="estilo-celda">
   <td width="150" class="titulo-celda"  > Código </font></td>
   <td width="350" class="titulo-celda"  > Denominación </font></td>
  </tr>
 </table>
 
 <!-- Div dentro del cual ira la Tabla para mostrar los datos (registros) -->
 <div id="viewPort">
 </div>
 
</form>
</body>
</html>