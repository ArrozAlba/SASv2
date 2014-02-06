<?php
  header("Cache-Control:no-cache");
  header("Pragma:no-cache");

  $ls_permisos = $_POST["permisos"];
  $ls_botonera = $_POST["botonera"];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Tasas de Inter&eacute;s</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<!-- LIBRERIAS Y ESTILOS COMUNES EN TODAS LAS PAGINAS -->
<script type="text/javascript" language="JavaScript1.2" src="../../../shared/js/librerias_comunes.js"></script>
<link href="../../../shared/css/ventanas.css" rel="stylesheet" type="text/css">

<!-- LIBRERIA ESPECIFICA DE ESTA PAGINA -->
<script type="text/javascript" language="javascript1.2" src="../js/sps_def_tasainteres.js"></script>
</head>

<body link="#006699" vlink="#006699" alink="#006699">

<script language="JavaScript1.2" type="text/javascript" src="../../../shared/js/cabecera_arriba.js"></script>
<script language="JavaScript1.2" type="text/javascript" src="../js/menu/menu_sps.js"></script>
<script language="JavaScript1.2" type="text/javascript" src="../../../shared/js/cabecera_abajo.js"></script>
<form name="form1" id="form1" method="post" action="">
    <table width="440" height="162" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco" id="tabla_fondo">
      <tr>
        <td width="438" height="160">
		 <div align="center"> 
          <table width="424" height="154"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
            <tr >
              <td colspan="4" class="titulo-ventana">
			    Tasa de Inter&eacute;s </td>
            </tr>
            <tr>
              <td >&nbsp;</td>
              <td colspan="3" >&nbsp;</td>
            </tr>
            <tr>
              <td width="99" height="22" align="right"><span class="style2">A&ntilde;o</span></td>
              <td width="115" ><select name="cmbano" id="cmbano">
              </select>              </td>
              <td width="67" ><div align="right">Mes</div></td>
              <td width="141" ><select name="cmbmes" id="cmbmes">
              </select></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
              <td height="23"><div align="right">% Tasa BCV </div></td>
              <td><input name="txtbcv" type="text" id="txtbcv" size="7" maxlength="5" onBlur="ue_getformat(this);"></td>
              <td><div align="right">Gaceta N&ordm; </div></td>
              <td><label>
                <input name="txtnumgac" type="text" id="txtnumgac" size="10" maxlength="6" onKeyPress="return validaCajas(this,'i',event,6)">
              </label></td>
            </tr>
            
            <tr>
              <td height="16">&nbsp;</td>
              <td colspan="3">&nbsp;</td>
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
	<!-- LIBRERIA PARA LA BARRA DE HERRAMIENTAS -->
	<script language="JavaScript" src="../../../shared/js/barra_herramientas.js" type="text/javascript"></script>
</form>
</body>
</html>