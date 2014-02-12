<?php

  header("Cache-Control:no-cache");
  header("Pragma:no-cache")
  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Catálogo de Sueldos</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<!-- LIBRERIAS Y ESTILOS COMUNES EN TODAS LAS PAGINAS -->
<script type="text/javascript" language="JavaScript1.2" src="../../../shared/js/librerias_comunes.js"></script>

<!-- LIBRERIA ESPECIFICA DE ESTA PAGINA -->
<script type="text/javascript" language="javascript1.2" src="../js/sps_cat_sueldos.js"></script>

<link href="../../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../../../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body onLoad="javascript:ue_inicializar();">
<form name="form1" method="post" action="">
 <table width="600" border="0"  class="formato-blanco" >
	<tr class="titulo-celda">
	  <td width="100%" colspan="2" class="titulo-ventana">
	  Cat&aacute;logo de Sueldos </td>
	</tr>
	<tr><td colspan="2"><td></tr>
	<tr><td colspan="2">
	 <!-- Marcador para mostrar la numeracion de los registros -->
	 <div class="estilo-catalogo" id="marcador" name="marcador">Mostrando Registros</div> 
	<td></tr>
 </table>
 
 <!-- Tabla para la cabecera (titulos) -->
 <table id="data_grid_header" width="600" class="fondo-tabla" cellpadding="1" cellspacing="1">
  <tr class="estilo-celda">
   <td width="90" class="titulo-celda"  > Código </font></td>
   <td width="150" class="titulo-celda"  > Nombre </font></td>
   <td width="148" class="titulo-celda" > Apellido </td>
   <td width="60" class="titulo-celda" > Cód.Nóm </td>
   <td width="152" class="titulo-celda" > Nómina </td>
  </tr>
 </table>
 
 <!-- Div dentro del cual ira la Tabla para mostrar los datos (registros) -->
 <div id="viewPort">
 </div>
 
</form>
</body>
</html>