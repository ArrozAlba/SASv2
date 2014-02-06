<?Php
  header("Cache-Control:no-cache");
  header("Pragma:no-cache");

  $ls_permisos = $_POST["permisos"];
  $ls_botonera = $_POST["botonera"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Registro de Deudas Anteriores</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<!-- LIBRERIAS Y ESTILOS COMUNES EN TODAS LAS PAGINAS -->
<script type="text/javascript" language="JavaScript1.2" src="../../../shared/js/librerias_comunes.js"></script>
<script language="javascript1.2" type="text/javascript" src="../../../shared/js/js_intra/datepickercontrol.js"></script>

<!-- LIBRERIA ESPECIFICA DE ESTA PAGINA -->
<script type="text/javascript" language="javascript1.2" src="../js/sps_pro_deudaanterior.js"></script>
<link href="../../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
              <td width="1"></td>
              <td height="3"></td>
              <td></td>
            </tr>
            <tr >
              <td></td>
              <td colspan="2" class="titulo-ventana">
			    Registro de Deudas Anteriores  </td>
            </tr>
            <tr>
              <td></td>
              <td >&nbsp;</td>
              <td >&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td colspan="2" class="titulo-nuevo" >Informaci&oacute;n de Personal </td>
            </tr>
            <tr>
              <td></td>
              <td >&nbsp;</td>
              <td >&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td width="145" height="22" align="right"><div align="right"><span class="style2">Personal N&ordm; &nbsp;</span></div></td>
              <td width="351" ><input name="txtcodper" type="text" id="txtcodper" style="text-align:center" value="" size="15" maxlength="10">
                <label><a href="javascript: ue_buscarpersonal();"><img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="personal" width="20" height="20" border="0" id="personal"></a></label></td>
            </tr>
            <tr>
              <td></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="22" align="right"><div align="right"><span class="style2">Nombres&nbsp;</span></div></td>
              <td>
			    <input name="txtnomper" id="txtnomper" value="" type="text" size="50" onKeyPress="return validaCajas(this,'s',event,45);" onKeyUp="changeCase(this)">			  </td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">Apellidos</div></td>
              <td><label>
                <input name="txtapeper" type="text" id="txtapeper" size="50">
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">N&oacute;mina</div></td>
              <td><label>
                <input name="txtcodnom" type="text" id="txtcodnom" style="text-align:center" size="10" maxlength="6">
                <input name="txtdennom" type="text" id="txtdennom" size="40">
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16" colspan="2" class="titulo-nuevo">Informaci&oacute;n de Deuda Anterior </td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">Fecha Corte Deuda Anterior </div></td>
              <td><label>
              <input name="txtfeccordeuant" type="text" id="txtfeccordeuant" style="text-align:center" size="15" maxlength="10" datepicker="true">
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">Deuda Antig&uuml;edad</div></td>
              <td><label>
                <input name="txtdeuantant" type="text" id="txtdeuantant" style="text-align:right" onKeyPress="return validaCajas(this,'d',event,18,2);"  onBlur="ue_getformat(this);">
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">Deuda Intereses </div></td>
              <td><label>
                <input name="txtdeuantint" type="text" id="txtdeuantint" style="text-align:right" onKeyPress="return validaCajas(this, 'd', event, 18, 2);" onBlur="ue_getformat(this);">
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">Anticipos Pagados </div></td>
              <td><label>
                <input name="txtantpag" type="text" id="txtantpag" style="text-align:right" onKeyPress="return validaCajas(this,'d',event,18,2);"  onBlur="ue_getformat(this);">
              </label></td>
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