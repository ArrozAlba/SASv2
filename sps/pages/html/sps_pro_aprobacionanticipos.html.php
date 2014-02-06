<?Php
  header("Cache-Control:no-cache");
  header("Pragma:no-cache");

  $ls_permisos = $_POST["permisos"];
  $ls_botonera = $_POST["botonera"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Aprobaci&oacute;n de Anticipos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<!-- LIBRERIAS Y ESTILOS COMUNES EN TODAS LAS PAGINAS -->
<script type="text/javascript" language="JavaScript1.2" src="../../../shared/js/librerias_comunes.js"></script>
<script language="javascript1.2" type="text/javascript" src="../../../shared/js/js_intra/datepickercontrol.js"></script>

<!-- LIBRERIA ESPECIFICA DE ESTA PAGINA -->
<script type="text/javascript" language="javascript1.2" src="../js/sps_pro_aprobacionanticipos.js"></script>
<link href="../../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>

<body link="#006699" vlink="#006699" alink="#006699">
<script language="JavaScript1.2" type="text/javascript" src="../../../shared/js/cabecera_arriba.js"></script>
<script language="JavaScript1.2" type="text/javascript" src="../js/menu/menu_sps.js"></script>
<script language="JavaScript1.2" type="text/javascript" src="../../../shared/js/cabecera_abajo.js"></script>
<form name="form1" id="form1" method="post" action="">
    <table width="692" height="174" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco" id="tabla_fondo">
      <tr>
        <td width="690" height="174">
		 <div align="center">
          <table width="655"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
            <!--DWLayoutTable-->
            <tr>
              <td width="1"></td>
              <td width="99" height="3"></td>
              <td colspan="4"></td>
            </tr>
            <tr >
              <td></td>
              <td colspan="5" class="titulo-ventana">
			    Aprobaci&oacute;n de Anticipos </td>
            </tr>
            <tr>
              <td></td>
              <td >&nbsp;</td>
              <td colspan="4" >&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td colspan="5" class="titulo-nuevo" >Informaci&oacute;n de Personal </td>
            </tr>
            <tr>
              <td></td>
              <td >&nbsp;</td>
              <td colspan="4" >&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="22" align="right"><div align="right"><span class="style2">Personal N&ordm; &nbsp;</span></div></td>
              <td colspan="4" ><input name="txtcodper" type="text" id="txtcodper" style="text-align:center" value="" size="15" maxlength="10">
              <label><a></a></label></td>
            </tr>
            <tr>
              <td></td>
              <td>&nbsp;</td>
              <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="22" align="right"><div align="right"><span class="style2">Nombres&nbsp;</span></div></td>
              <td colspan="4"><input name="txtnomper" id="txtnomper" value="" type="text" size="50" onKeyPress="return validaCajas(this,'s',event,45);" onKeyUp="changeCase(this)"></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">Apellidos</div></td>
              <td colspan="4"><label>
                <input name="txtapeper" type="text" id="txtapeper" size="50">
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">N&oacute;mina</div></td>
              <td colspan="4"><label>
                <input name="txtcodnom" type="text" id="txtcodnom" style="text-align:center" size="10" maxlength="6">
                <input name="txtdennom" type="text" id="txtdennom" size="40">
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16" colspan="5" class="titulo-nuevo">Informaci&oacute;n de Anticipo </td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td colspan="4">&nbsp;</td>
            </tr>
            
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">Monto Deuda </div></td>
              <td colspan="2"><label>
                <input name="txtmondeulab" type="text" id="txtmondeulab" style="text-align:right" onKeyPress="return validaCajas(this, 'd', event, 18, 2);" onBlur="ue_getformat(this);">
              </label></td>
              <td><div align="right">Monto Porcentual </div></td>
              <td width="299"><input name="txtmonporant" type="text" id="txtmonporant" style="text-align:right" onKeyPress="return validaCajas(this, 'd', event, 18, 2);" onBlur="ue_getformat(this);"></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">Fecha Anticipo</div></td>
              <td colspan="2"><label>
                <input name="txtfecantper" type="text" id="txtfecantper" size="15" maxlength="10" style="text-align:center">
              </label></td>
              <td width="165"> <div align="right">Status</div></td>
              <td><select name="cmbestant" id="cmbestant">
                <option value="0" selected>Solicitado</option>
                  <option value="1">Aprobado</option>
                  <option value="2">Rechazado</option>
                                            </select></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">Monto Anticipo</div></td>
              <td colspan="4"><label>
                <input name="txtmonant" type="text" id="txtmonant"  style="text-align:right" onKeyPress="return validaCajas(this, 'd', event, 18, 2);" onBlur="ue_getformat(this);">
              </label></td>
            </tr>
            

            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">Obsevaci&oacute;n
                <label></label>
              </div></td>
              <td colspan="4"><textarea name="txtobsant" cols="74" id="txtobsant"></textarea></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td colspan="4">&nbsp;</td>
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