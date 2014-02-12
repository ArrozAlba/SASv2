<?php
  header("Cache-Control:no-cache");
  header("Pragma:no-cache");

  $ls_permisos = $_POST["permisos"];
  $ls_botonera = $_POST["botonera"];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Reporte de detalles de Antig&uuml;edad</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<!-- LIBRERIAS Y ESTILOS COMUNES EN TODAS LAS PAGINAS -->
<script type="text/javascript" language="JavaScript1.2" src="../../../shared/js/librerias_comunes.js"></script>
<link href="../../../shared/css/ventanas.css" rel="stylesheet" type="text/css">

<!-- LIBRERIA ESPECIFICA DE ESTA PAGINA -->
<script type="text/javascript" language="javascript1.2" src="../js/sps_rep_detalle_antiguedad.js"></script>
</head>

<body link="#006699" vlink="#006699" alink="#006699">

<script language="JavaScript1.2" type="text/javascript" src="../../../shared/js/cabecera_arriba.js"></script>
<script language="JavaScript1.2" type="text/javascript" src="../js/menu/menu_sps.js"></script>
<script language="JavaScript1.2" type="text/javascript" src="../../../shared/js/cabecera_abajo.js"></script>
<form name="form1" id="form1" method="post" action="">
    <table width="472" height="162" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco" id="tabla_fondo">
      <tr>
        <td width="483" height="160">
		 <div align="center"> 
          <table width="450" height="154"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
            <tr >
              <td colspan="3" class="titulo-ventana">Reporte de detalles de Antig&uuml;edad </td>
            </tr>
            <tr>
              <td >&nbsp;</td>
              <td colspan="2" >&nbsp;</td>
            </tr>
            <tr>
              <td width="96" height="22" align="right"><span class="style2">Personal</span></td>
              <td colspan="2" ><label>
                <input name="txtcodper" type="text" id="txtcodper">
                <a href="javascript: ue_buscarpersonal();"><img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="personal" width="20" height="20" border="0" id="personal"></a></label></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td height="23"><div align="right">Rango de Fecha</div></td>
              <td colspan="2"><input name="txtfechainicio" id="txtfechainicio" value="" type="text" size="12" datepicker="true">
&nbsp;al&nbsp;
<input name="txtfechafin" id="txtfechafin" value="" type="text" size="12" datepicker="true"></td>
            </tr>
            
            <tr>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="16"><div align="right">Orden</div></td>
              <td width="222"><select name="lstorden" id="lstorden" style="width:200px; height:100px" multiple>
                <option value="fecant">Per&iacute;odo</option>
              </select></td>
              <td width="110"><p>
                <input type="button" id="btnsubir" name="btnsubir" value="&Lambda;" style="width:25px" onClick="subirCampo()"/>
              </p>
              <p>
                <input type="button" id="btnbajar" name="btnbajar" value="V" style="width:25px" onClick="bajarCampo()"/>
</p></td>
            </tr>
            <tr>
              <td height="16">&nbsp;</td>
              <td colspan="2">&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>
  </table>
	<input name="hidpermisos" type="hidden" id="hidpermisos" value="<?Php print $ls_permisos?>">
	<input name="hidbotonera" type="hidden" id="hidbotonera" value="<?php print $ls_botonera?>">
	<!-- LIBRERIA PARA LA BARRA DE HERRAMIENTAS -->
	<script language="JavaScript" src="../../../shared/js/barra_herramientas.js" type="text/javascript"></script>
</form>
</body>
</html>