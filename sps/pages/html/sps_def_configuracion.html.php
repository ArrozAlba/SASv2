<?Php
  header("Cache-Control:no-cache");
  header("Pragma:no-cache");

  $ls_permisos = $_POST["permisos"];
  $ls_botonera = $_POST["botonera"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Configuraci&oacute;n</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- LIBRERIAS Y ESTILOS COMUNES EN TODAS LAS PAGINAS -->
<script type="text/javascript" language="JavaScript1.2" src="../../../shared/js/librerias_comunes.js"></script>

<!-- LIBRERIA ESPECIFICA DE ESTA PAGINA -->
<script type="text/javascript" language="javascript1.2" src="../js/sps_def_configuracion.js"></script>
<link href="../../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {font-size: 12px}
-->
</style>
</head>

<body link="#006699" vlink="#006699" alink="#006699">
<script language="JavaScript1.2" type="text/javascript" src="../../../shared/js/cabecera_arriba.js"></script>
<script language="JavaScript1.2" type="text/javascript" src="../js/menu/menu_sps.js"></script>
<script language="JavaScript1.2" type="text/javascript" src="../../../shared/js/cabecera_abajo.js"></script>
<form name="form1" id="form1" method="post" action="">
    <table width="662" height="174" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco" id="tabla_fondo">
      <tr>
        <td width="660" height="174">
		 <div align="center">
          <table width="650" height="811"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
            <!--DWLayoutTable-->
            <tr>
              <td width="1"></td>
              <td width="161" height="3"></td>
              <td width="449"></td>
            </tr>
            <tr class="titulo-ventana">
              <td height="31" colspan="3"><span class="Estilo1">Configuraci&oacute;n</span></td>
            </tr>
            <tr>
              <td height="13"></td>
              <td><label></label></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="13"></td>
              <td><div align="right"><span class="style2">% Anticipos P.S.</span></div></td>
              <td><input name="txtporant" type="text" id="txtporant" style="text-align:center" value="" size="6" maxlength="5" onBlur="ue_getformat(this);" onKeyPress="return validaCajas(this, 'd',event,6);"></td>
            </tr> 
            <tr>
              <td height="13"></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="22" align="right"><div align="right"><span class="style2">Estatus Sueldo &nbsp;</span></div></td>
              <td><label>
                <select name="cmbestsue" id="cmbestsue">
                  <option selected>Seleccione</option>
                  <option value="B">Sueldo Base</option>
                  <option value="I">Sueldo Integral</option>
                </select>
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">Manejo Incidencias Bonos </div></td>
              <td><label>
                <select name="cmbincbon" id="cmbincbon">
                  <option value="S">SI</option>
                  <option value="N">NO</option>
                </select>
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td><label></label></td>
            </tr>
            <tr>
              <td></td>
              <td height="16" colspan="2" class="titulo-ventana">Configuraci&oacute;n Contable</td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">sc_cuenta</div></td>
              <td><label>
                  <input name="txtsc_cta_ps" type="text" id="txtsc_cta_ps">
                <a href="javascript: ue_buscar_sc_cuenta();"><img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="sc_cuenta" width="20" height="20" border="0" id="sc_cuenta"> </a>
                <input name="txtdensc_cta_ps" type="text" class="sin-borde" id="txtdensc_cta_ps" size="60">
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16" colspan="2" class="titulo-nuevo">Empleado Fijo </td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td><!--DWLayoutEmptyCell-->&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">Cuenta de Prestaciones Sociales</div></td>
              <td><label>
                <input name="txtemp_fijo_ps" type="text" id="txtemp_fijo_ps">
                <a href="javascript: ue_buscar_spg_cuenta('1');"><img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="spg_cuenta" width="20" height="20" border="0" id="spg_cuenta"></a>
                <input name="txtdenemp_fijo_ps" type="text" class="sin-borde" id="txtdenemp_fijo_ps" size="60" >
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">Cuenta de Vacaciones -Bonos</div></td>
              <td><label>
                <input name="txtemp_fijo_vac" type="text" id="txtemp_fijo_vac">
                <a href="javascript: ue_buscar_spg_cuenta('2');"><img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="spg_cuenta" width="20" height="20" border="0" id="spg_cuenta"></a>
                <input name="txtdenemp_fijo_vac" type="text" class="sin-borde" id="txtdenemp_fijo_vac" size="60">
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">Cuenta Aguinaldos </div></td>
              <td><label>
                <input name="txtemp_fijo_agu" type="text" id="txtemp_fijo_agu">
                <a href="javascript: ue_buscar_spg_cuenta('3');"><img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="spg_cuenta" width="20" height="20" border="0" id="spg_cuenta"></a>
                <input name="txtdenemp_fijo_agu" type="text" class="sin-borde" id="txtdenemp_fijo_agu" size="60">
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16" colspan="2" class="titulo-nuevo">Obreros Fijos </td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">Cuenta de Prestaciones Sociales</div></td>
              <td><label>
                <input name="txtobr_fijo_ps" type="text" id="txtobr_fijo_ps">
                <a href="javascript: ue_buscar_spg_cuenta('4');"><img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="spg_cuenta" width="20" height="20" border="0" id="spg_cuenta"></a>
                <input name="txtdenobr_fijo_ps" type="text" class="sin-borde" id="txtdenobr_fijo_ps" size="60">
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">Cuenta de Vacaciones -Bonos</div></td>
              <td><label>
                <input name="txtobr_fijo_vac" type="text" id="txtobr_fijo_vac">
                <a href="javascript: ue_buscar_spg_cuenta('5');"><img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="spg_cuenta" width="20" height="20" border="0" id="spg_cuenta"></a>
                <input name="txtdenobr_fijo_vac" type="text" class="sin-borde" id="txtdenobr_fijo_vac" size="60">
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">Cuenta Aguinaldos</div></td>
              <td><label>
                <input name="txtobr_fijo_agu" type="text" id="txtobr_fijo_agu">
                <a href="javascript: ue_buscar_spg_cuenta('6');"><img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="spg_cuenta" width="20" height="20" border="0" id="spg_cuenta"></a>
                <input name="txtdenobr_fijo_agu" type="text" class="sin-borde" id="txtdenobr_fijo_agu" size="60">
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16" colspan="2" class="titulo-nuevo">Empleados Contratados </td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">Cuenta de Prestaciones Sociales</div></td>
              <td><label>
                <input name="txtemp_cont_ps" type="text" id="txtemp_cont_ps">
                <a href="javascript: ue_buscar_spg_cuenta('7');"><img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="spg_cuenta" width="20" height="20" border="0" id="spg_cuenta"></a>
                <input name="txtdenemp_cont_ps" type="text" class="sin-borde" id="txtdenemp_cont_ps" size="60">
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">Cuenta de Vacaciones -Bonos</div></td>
              <td><label>
                <input name="txtemp_cont_vac" type="text" id="txtemp_cont_vac">
                <a href="javascript: ue_buscar_spg_cuenta('8');"><img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="spg_cuenta" width="20" height="20" border="0" id="spg_cuenta"></a>
                <input name="txtdenemp_cont_vac" type="text" class="sin-borde" id="txtdenemp_cont_vac" size="60">
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">Cuenta Aguinaldos</div></td>
              <td><label>
                <input name="txtemp_cont_agu" type="text" id="txtemp_cont_agu">
                <a href="javascript: ue_buscar_spg_cuenta('9');"><img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="spg_cuenta" width="20" height="20" border="0" id="spg_cuenta"></a>
                <input name="txtdenemp_cont_agu" type="text" class="sin-borde" id="txtdenemp_cont_agu" size="60">
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16" colspan="2" class="titulo-nuevo"> Otros Empleados </td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">Cuenta de Prestaciones Sociales</div></td>
              <td><label>
                <input name="txtemp_esp_ps" type="text" id="txtemp_esp_ps">
                <a href="javascript: ue_buscar_spg_cuenta('10');"><img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="spg_cuenta" width="20" height="20" border="0" id="spg_cuenta"></a>
                <input name="txtdenemp_esp_ps" type="text" class="sin-borde" id="txtdenemp_esp_ps" size="60">
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">Cuenta de Vacaciones -Bonos</div></td>
              <td><label>
                <input name="txtemp_esp_vac" type="text" id="txtemp_esp_vac">
                <a href="javascript: ue_buscar_spg_cuenta('11');"><img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="spg_cuenta" width="20" height="20" border="0" id="spg_cuenta"></a>
                <input name="txtdenemp_esp_vac" type="text" class="sin-borde" id="txtdenemp_esp_vac" size="60">
              </label></td>
            </tr>
            <tr>
              <td></td>
              <td height="16">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td></td>
              <td height="16"><div align="right">Cuenta Aguinaldos</div></td>
              <td><label>
                <input name="txtemp_esp_agu" type="text" id="txtemp_esp_agu">
                <a href="javascript: ue_buscar_spg_cuenta('12');"><img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="spg_cuenta" width="20" height="20" border="0" id="spg_cuenta"></a>
                <input name="txtdenemp_esp_agu" type="text" class="sin-borde" id="txtdenemp_esp_agu" size="60">
              </label></td>
            </tr> 
            <tr>
              <td></td>
              <td height="16"><div align="right"></div></td>
              <td>&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>
  </table>
	<input name="hidpermisos" type="hidden" id="hidpermisos" value="<?Php print $ls_permisos?>">
	<input name="hidbotonera" type="hidden" id="hidbotonera" value="<?Php print $ls_botonera?>">
	<input name="hidctas" type="hidden" id="hidctas" value="">
	<input name="hidid_art" type="hidden" id="hidid_art" value="">
		<!-- LIBRERIA PARA LA BARRA DE HERRAMIENTAS -->
    <script language="JavaScript" src="../../../shared/js/barra_herramientas.js" type="text/javascript"></script>	
</form>
</body>
</html>