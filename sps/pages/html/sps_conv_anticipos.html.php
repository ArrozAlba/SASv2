<?Php
  header("Cache-Control:no-cache");
  header("Pragma:no-cache");

  $ls_permisos = $_POST["permisos"];
  $ls_botonera = $_POST["botonera"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Convertidor Anticipos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- LIBRERIAS Y ESTILOS COMUNES EN TODAS LAS PAGINAS -->
<script type="text/javascript" language="JavaScript1.2" src="../../../shared/js/librerias_comunes.js"></script>
<!-- LIBRERIA ESPECIFICA DE ESTA PAGINA -->
<script type="text/javascript" language="javascript1.2" src="../js/sps_conv_anticipos.js"></script>
</head>

<body link="#006699" vlink="#006699" alink="#006699">
<script language="JavaScript1.2" type="text/javascript" src="../../../shared/js/cabecera_arriba.js"></script>
<script language="JavaScript1.2" type="text/javascript" src="../js/menu/menu_sps.js"></script>
<script language="JavaScript1.2" type="text/javascript" src="../../../shared/js/cabecera_abajo.js"></script>
<form name="form1" id="form1" method="post" action="">
    <table width="518" height="174" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco" id="tabla_fondo">
      <tr>
        <td width="516" height="174">
		 <div align="center">
          <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
            <!--DWLayoutTable-->
            <tr>
              <td width="6"></td>
              <td height="3"></td>
              <td></td>
            </tr>
            <tr >
              <td></td>
              <td colspan="2" class="titulo-ventana">Convertidor Anticipos </td>
            </tr>
            <tr>
              <td></td>
              <td >&nbsp;</td>
              <td >&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td width="134" height="22" align="right"><span class="style2">Archivo .txt &nbsp;</span></td>
              <td width="334" ><label>
              <input name="txtarchivo" type="text" id="txtarchivo">
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="22" align="right"><span class="style2">&nbsp;</span></td>
              <td><label>
                <input name="btnejecutar" type="button" id="btnejecutar" value="Ejecutar" onClick="ue_leer_archivo();">
              <div id="mensaje">&nbsp;</div></td></label></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>
    </table>
	<input name="hidpermisos" type="hidden" id="hidpermisos" value="<?Php print $ls_permisos?>">	
	<input name="hidbotonera" type="hidden" id="hidbotonera" value="<?Php print $ls_botonera?>">
	<!-- LIBRERIA PARA LA BARRA DE HERRAMIENTAS -->
    <script language="JavaScript" src="../../../shared/js/barra_herramientas.js" type="text/javascript"></script>	
</form>
</body>
</html>