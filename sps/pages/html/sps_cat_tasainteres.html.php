<?php
  header("Cache-Control:no-cache");
  header("Pragma:no-cache")
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Catálogo de Tasas de Interés</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<!-- LIBRERIAS Y ESTILOS COMUNES EN TODAS LAS PAGINAS -->
<script type="text/javascript" language="JavaScript1.2" src="../../../shared/js/librerias_comunes.js"></script>

<!-- LIBRERIA ESPECIFICA DE ESTA PAGINA -->
<script type="text/javascript" language="javascript1.2" src="../js/sps_cat_tasainteres.js"></script>

<link href="../../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../../../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body onLoad="javascript:ue_inicializar();">
<form name="form1" method="post" action="">
 <table width="400" border="0"  class="formato-blanco" >
	<tr class="titulo-celda">
	  <td colspan="2" class="titulo-ventana">
	  Cat&aacute;logo de Tasas de Interés </td>
	</tr>
	<tr><td colspan="2"><td width="1%"></tr>
	<tr>
	  <td colspan="2">    
	  <td>        </tr>
	<tr>
	  <td width="30%">
      
	  <td width="69%"><label></label>      
	  <td>        </tr>
	<tr>
	  <td><div align="right">Años
      </div>
	  <td><label></label>    
	    <label>
	    <select name="cmbano" id="cmbano" onChange="ue_ver_tasainteres();">
        </select>
	    </label>
	  <td>    
    </tr>
	<tr>
	  <td>
      
	  <td><label></label>      
	  <td>        </tr>
	<tr>
	  <td colspan="2">    
	  <td>        </tr>
	<tr><td colspan="2">
	 <!-- Marcador para mostrar la numeracion de los registros -->
	 <div class="estilo-catalogo" id="marcador" name="marcador">Mostrando Registros</div> 
	 <td width="15%"><input type="hidden" name="hidfilas" id="hidfilas"/>
	<td></tr>
 </table>
 
 <!-- Tabla para la cabecera (titulos) -->
 <table id="data_grid_header" width="400" class="fondo-tabla" cellpadding="1" cellspacing="1">
  <tr class="estilo-celda">
   <td width="100" class="titulo-celda"  > Año </font></td>
   <td width="100" class="titulo-celda"  > Mes </font></td>
   <td width="100" class="titulo-celda"  > Valor </font></td>
   <td width="100" class="titulo-celda"  > Gaceta </font></td>
  </tr>
 </table>
 
 <!-- Div dentro del cual ira la Tabla para mostrar los datos (registros) -->
 <div id="viewPort">
 </div>
 
</form>
</body>
</html>