<?Php
  header("Cache-Control:no-cache");
  header("Pragma:no-cache");

  $ls_permisos = $_POST["permisos"];
  $ls_botonera = $_POST["botonera"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Carta de Anticipo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<!-- LIBRERIAS Y ESTILOS COMUNES EN TODAS LAS PAGINAS -->
<script type="text/javascript" language="JavaScript1.2" src="../../../shared/js/librerias_comunes.js"></script>

<!-- LIBRERIA ESPECIFICA DE ESTA PAGINA -->
<script type="text/javascript" language="javascript1.2" src="../js/sps_def_cartaanticipo.js"></script>
<link href="../../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>

<body link="#006699" vlink="#006699" alink="#006699">
<script language="JavaScript1.2" type="text/javascript" src="../../../shared/js/cabecera_arriba.js"></script>
<script language="JavaScript1.2" type="text/javascript" src="../js/menu/menu_sps.js"></script>
<script language="JavaScript1.2" type="text/javascript" src="../../../shared/js/cabecera_abajo.js"></script>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
    <table width="518" height="174" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco" id="tabla_fondo">
      <tr>
        <td width="516" height="174">
		 <div align="center">
          <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
            <!--DWLayoutTable-->
            <tr>
              <td width="1"></td>
              <td width="115" height="3"></td>
              <td colspan="3"></td>
            </tr>
            <tr >
              <td></td>
              <td colspan="4" class="titulo-ventana">Configuraci&oacute;n de Carta de Anticipo </td>
            </tr>
            <tr>
              <td></td>
              <td >&nbsp;</td>
              <td colspan="3" >&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td ><div align="right">C&oacute;digo</div></td>
              <td colspan="3" ><label>
              <input name="txtcodcarant" type="text" id="txtcodcarant" size="10" maxlength="3" style="text-align:center">
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td >&nbsp;</td>
              <td colspan="3" >&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td ><div align="right">Descripci&oacute;n</div></td>
              <td colspan="3" ><label>
                <input name="txtdescarant" type="text" id="txtdescarant" size="40" maxlength="150">
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td >&nbsp;</td>
              <td colspan="3" >&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td ><div align="right">Tama&ntilde;o de Letra </div></td>
              <td width="87" ><label>
                <input name="txttamletcarant" type="text" id="txttamletcarant" size="5" maxlength="2" onKeyPress="return validaCajas(this,'i',event,2)">
              </label></td>
              <td width="155" ><div align="right">Tama&ntilde;o de Letra Pie de P&aacute;gina</div></td>
              <td width="146" ><label>
                <input name="txttamletpiepag" type="text" id="txttamletpiepag" size="5" maxlength="2" onKeyPress="return validaCajas(this,'i',event,2)">
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td >&nbsp;</td>
              <td colspan="3" >&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td ><div align="right">Interlineado</div></td>
              <td colspan="3" ><label>
                <select name="cmbintlincarant" id="cmbintlincarant">
                  <option value="1">1</option>
                  <option value="2">1.5</option>
                  <option value="3">2</option>
                </select>
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td >&nbsp;</td>
              <td colspan="3" >&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td ><div align="right">Margen Superior </div></td>
              <td ><label>
                <input name="txtmarsupcarant" type="text" id="txtmarsupcarant" size="8" maxlength="5" onBlur="ue_getformat(this);">
              cm</label></td>
              <td ><div align="right">Margen Inferior </div></td>
              <td ><input name="txtmarinfcarant" type="text" id="txtmarinfcarant" size="8" maxlength="5" onBlur="ue_getformat(this);">
                cm</td>
            </tr>
            <tr>
              <td></td>
              <td >&nbsp;</td>
              <td colspan="3" >&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td ><div align="right">T&iacute;tulo</div></td>
              <td colspan="3" ><label>
                <input name="txttitcarant" type="text" id="txttitcarant" size="60" maxlength="240">
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td >&nbsp;</td>
              <td colspan="3" >&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td ><div align="right">Plantilla rtf </div></td>
              <td colspan="3" ><label>
                <input name="txtnomrtf" type="text" id="txtnomrtf" size="50" maxlength="50">
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td >&nbsp;</td>
              <td colspan="3" >&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td ><div align="right">Actualizar Plantilla rtf </div></td>
              <td colspan="3" ><label>
                <input name="txtarcrtfcarant" type="file" id="txtarcrtfcarant" value="">
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td >&nbsp;</td>
              <td colspan="3" >&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td ><div align="right">Campos</div></td>
              <td colspan="3" ><label>
                <select name="cmbcampos" id="cmbcampos">
                  <option value="$ld_monant">Monto Anticipo</option>
                  <option value="$ls_nomper">Nombres</option>
                  <option value="$ls_apeper">Apellidos</option>
                  <option value="$ls_cedper">C&eacute;dula</option>
                  <option value="$ls_carper">Cargo</option>
                  <option value="$ldt_fecingper">Fecha de Ingreso</option>
                  <option value="$ls_undadm">Departamento</option>
                  <option value="$ls_dennom">N&oacute;mina</option>
                  <option value="$ldt_fecantper">Fecha Anticipo</option>
                  <option value="$ls_motant">Motivo del Anticipo</option>
                  <option value="$ld_mondeulab">Deuda Laboral</option>
                  <option value="$ld_monporant">Monto Porcentual Anticipo</option>
                </select>
			    <a href="javascript: ue_ingresarcampo();"><img src="../../../shared/imagebank/arrow.gif" alt="Ingresar" width="13" height="13" border="0"></a></label></td>
            </tr>
            <tr>
              <td></td>
              <td >&nbsp;</td>
              <td colspan="3" >&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td colspan="4" class="titulo-nuevo" >Contenido</td>
            </tr>
            <tr>
              <td></td>
              <td >&nbsp;</td>
              <td colspan="3" >&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="22" colspan="4" align="right"><label>
                <textarea name="txtconcarant" cols="100" rows="20" id="txtconcarant"></textarea>
              <a href="javascript: ue_buscarpersonal();"></a></label></td>
            </tr>
            
            <tr>
              <td></td>
              <td height="22" align="right"><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td colspan="3"><!--DWLayoutEmptyCell-->&nbsp;</td>
            </tr>
            
            
            <tr>
              <td></td>
              <td height="16" colspan="4" class="titulo-nuevo">Pie de P&aacute;gina </td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td colspan="3">&nbsp;</td>
            </tr>
            
            <tr>
              <td></td>
              <td height="16" colspan="4"><label>
                <textarea name="txtpiepagcarant" cols="100" rows="5" id="txtpiepagcarant"></textarea>
              </label></td>
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