<?php
  header("Cache-Control:no-cache");
  header("Pragma:no-cache");

  $ls_permisos = $_POST["permisos"];
  $ls_botonera = $_POST["botonera"];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Reporte de detalles de Sueldos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<!-- LIBRERIAS Y ESTILOS COMUNES EN TODAS LAS PAGINAS -->
<script type="text/javascript" language="JavaScript1.2" src="../../../shared/js/librerias_comunes.js"></script>
<link href="../../../shared/css/ventanas.css" rel="stylesheet" type="text/css">

<!-- LIBRERIA ESPECIFICA DE ESTA PAGINA -->
<script type="text/javascript" language="javascript1.2" src="../js/sps_rep_detalle_sueldos.js"></script>
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
          <table width="481" height="205"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
            <tr >
              <td colspan="4" class="titulo-ventana">Reporte de detalles de Sueldos </td>
            </tr>
            <tr>
              <td >&nbsp;</td>
              <td colspan="3" >&nbsp;</td>
            </tr>
            <tr>
              <td width="108" height="28" align="right"><span class="style2">Rango de Personal</span></td>
              <td width="261" ><label>

                <div align="left">
                  <input name="txtcodper1" type="text" id="txtcodper1" size="16" maxlength="12">
                  <a href="javascript: ue_buscarpersonal('1');"><img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="personal" width="20" height="20" border="0" id="personal"> </a>
                  <input name="txtcodper2" type="text" id="txtcodper2" size="16" maxlength="12">
                  <a href="javascript: ue_buscarpersonal('2');"><img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="personal" width="20" height="20" border="0" id="personal"></a></div>
              </label></td>
              <td width="6" >&nbsp;</td>
              <td ><a href="javascript: ue_buscarpersonal();"></a></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="3">&nbsp;</td>
            </tr>
            
            
            <tr>
              <td height="16">&nbsp;</td>
              <td colspan="2">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="16"><div align="right">Orden</div></td>
              <td colspan="2">
                <div align="left">
                  <select name="lstorden" id="lstorden" style="width:200px; height:100px" multiple>
                    <option value="s.codper">C&oacute;digo</option>
                    <option value="p.nomper">Nombre</option>
                    <option value="p.apeper">Apellido</option>
                  </select>
                </div></td>
              <td width="104"><p>
                <input type="button" id="btnsubir" name="btnsubir" value="&Lambda;" style="width:25px" onClick="subirCampo()"/>
              </p>
              <p>
                <input type="button" id="btnbajar" name="btnbajar" value="V" style="width:25px" onClick="bajarCampo()"/>
</p></td>
            </tr>
            <tr>
              <td height="16">&nbsp;</td>
              <td colspan="3">&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>
  </table>
	<input name="hidpermisos" type="hidden" id="hidpermisos" value="<?Php print $ls_permisos?>">
	<input name="hidbotonera" type="hidden" id="hidbotonera" value="<?php print $ls_botonera?>">
	<input name="hidcatalogo" type="hidden" id="hidcatalogo" value="">
	<!-- LIBRERIA PARA LA BARRA DE HERRAMIENTAS -->
	<script language="JavaScript" src="../../../shared/js/barra_herramientas.js" type="text/javascript"></script>
</form>
</body>
</html>