<?php
  header("Cache-Control:no-cache");
  header("Pragma:no-cache");

  $ls_permisos = $_POST["permisos"];
  $ls_botonera = $_POST["botonera"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Liquidaci&oacute;n de Prestaciones Sociales.</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<!-- LIBRERIAS Y ESTILOS COMUNES EN TODAS LAS PAGINAS -->
<script type="text/javascript" language="JavaScript1.2" src="../../../shared/js/librerias_comunes.js"></script>
<link href="../../../shared/css/ventanas.css" rel="stylesheet" type="text/css">

<!-- LIBRERIA ESPECIFICA DE ESTA PAGINA -->
<script type="text/javascript" language="javascript1.2" src="../js/sps_pro_liquidacion.js"></script>
<script language="javascript1.2" type="text/javascript" src="../../../shared/js/js_intra/datepickercontrol.js"></script>
<style type="text/css">
<!--
.Estilo3 {font-size: 14px}
.Estilo5 {font-size: 12}
.Estilo6 {font-size: 12px}
-->
</style>
</head>

<body link="#006699" vlink="#006699" alink="#006699">

<script language="JavaScript1.2" type="text/javascript" src="../../../shared/js/cabecera_arriba.js"></script>
<script language="JavaScript1.2" type="text/javascript" src="../js/menu/menu_sps.js"></script>
<script language="JavaScript1.2" type="text/javascript" src="../../../shared/js/cabecera_abajo.js"></script>

<form name="form1" id="form1" method="post" action="">
  <table width="705" height="821" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco" id="tabla_fondo">
    <tr>
      <td height="27" colspan="7" class="titulo-ventana">Liquidaci&oacute;n de Prestaciones Sociales </td>
    </tr>
    <tr>
      <td height="422" colspan="7"><div id="acordion">
          <div>
            <!-- DIV QUE CONTIENE LOS DATOS DEL ARTICULO -->
            <div id="datos_articulos" class="celdas-azules" align="center"><strong>Datos del Empleado </strong></div>
            <div>
              <table width="100%" border="0" cellspacing="0" cellpadding="0" class="formato-blanco" style="border-color:#FFFFFF">
                <tr>
                  <td height="22" align="right">&nbsp;</td>
                  <td colspan="6" >&nbsp;</td>
                </tr>
                <tr>
                  <td height="22" align="right">Fecha Liquidaci&oacute;n </td>
                  <td colspan="4" ><label>
                    <input name="txtfecliq" type="text" id="txtfecliq" size="15" style="text-align:center">
                  </label></td>
                  <td width="142" ><div align="right">N&ordm; Liquidaci&oacute;n </div></td>
                  <td width="324" ><label>
                    <input name="txtnumliq" type="text" id="txtnumliq" style="text-align:center">
                  </label></td>
                </tr>
                <tr>
                  <td height="13" align="right">&nbsp;</td>
                  <td colspan="6" >&nbsp;</td>
                </tr>
                <tr>
                  <td width="188" height="22" align="right">Personal N&ordm; </td>
                  <td colspan="5" ><label>
                    <input name="txtcodper" type="text" id="txtcodper" size="15" style="text-align:center">
                  <a href="javascript: ue_buscarpersonal();"><img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="personal" width="20" height="20" border="0" id="personal"></a></label></td>
                  <td ><label>
                    <input  disabled="disabled" name="txtestliq" type="text" class="sin-borde2" id="txtestliq" style="text-align:center">
                  </label></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td colspan="6">&nbsp;</td>
                </tr>
                <tr>
                  <td height="22" align="right"><span class="style2">Nombres&nbsp;</span></td>
                  <td colspan="6"><label>
                    <input name="txtnomper" type="text" id="txtnomper" size="86">
                    </label>                  </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td colspan="6">&nbsp;</td>
                </tr>
                <tr>
                  <td height="22" align="right">Apellidos</td>
                  <td colspan="6"><label>
                    <input name="txtapeper" type="text" id="txtapeper" size="86">
                  </label></td>
                </tr>
                <tr>
                  <td height="16">&nbsp;</td>
                  <td colspan="6">&nbsp;</td>
                </tr>
                <tr>
                  <td height="16"><div align="right">N&oacute;mina</div></td>
                  <td colspan="6"><label>
                    <input name="txtcodnom" type="text" id="txtcodnom" size="10" maxlength="6" style="text-align:center">
                    <input name="txtdennom" type="text" id="txtdennom" size="72">
                  </label></td>
                </tr>
                <tr>
                  <td height="16">&nbsp;</td>
                  <td colspan="6">&nbsp;</td>
                </tr>
                <tr>
                  <td height="16"><div align="right">Cargo</div></td>
                  <td colspan="6"><label>
                    <input name="txtcargo" type="text" id="txtcargo" size="86">
                  </label></td>
                </tr>
                <tr>
                  <td height="16">&nbsp;</td>
                  <td colspan="6">&nbsp;</td>
                </tr>
                <tr>
                  <td height="16"><div align="right">Dedicaci&oacute;n</div></td>
                  <td colspan="6"><label>
                    <input name="txtdedicacion" type="text" id="txtdedicacion" size="86">
                  </label></td>
                </tr>
                <tr>
                  <td height="16">&nbsp;</td>
                  <td colspan="6">&nbsp;</td>
                </tr>
                <tr>
                  <td height="16"><div align="right">Tipo de Personal </div></td>
                  <td colspan="6"><label>
                    <input name="txttipopersonal" type="text" id="txttipopersonal" size="86">
                  </label></td>
                </tr>
                <tr>
                  <td height="16">&nbsp;</td>
                  <td colspan="6">&nbsp;</td>
                </tr>
                <tr>
                  <td height="29"><div align="right">Fecha Ingreso </div></td>
                  <td colspan="4"><label>
                    <input name="txtfecingper" type="text" id="txtfecingper" style="text-align:center">
                  </label></td>
                  <td><div align="right">Fecha Egreso </div></td>
                  <td><label>
                    <input name="txtfecegrper" type="text" id="txtfecegrper" style="text-align:center" onKeyPress="ue_tiempo_servicio();">
                  </label></td>
                </tr>
                <tr>
                  <td height="16">&nbsp;</td>
                  <td colspan="6">&nbsp;</td>
                </tr>
                <tr>
                  <td height="16"><div align="right">Aplicar Alicuotas </div></td>
                  <td colspan="4"><label>
                    <input name="chkbonofin" type="checkbox" id="chkbonofin" value="1" onChange="ue_calcular_sueldo_diario();">
                  Bono de Fin de Año</label></td>
                  <td><label>
                    <div align="left">
                      <input name="chkbonovac" type="checkbox" id="chkbonovac" value="1" onChange="ue_calcular_sueldo_diario();">
                      Bono Vacacional</div>
                  </label></td>
                  <td><label>
                    <input name="chkcajaaho" type="checkbox" id="chkcajaaho" value="1" onChange="ue_calcular_sueldo_diario();">
                  Caja de Ahorro</label></td>
                </tr>
                <tr>
                  <td height="16">&nbsp;</td>
                  <td colspan="6">&nbsp;</td>
                </tr>
                <tr>
                  <td height="16"><div align="right">Salario Integral </div></td>
                  <td colspan="4"><input name="txtsalintper" type="text" id="txtsalintper" style="text-align:right" readonly="true" onKeyPress="return validaCajas(this, 'd',event,18,3);"></td>
                  <td><div align="right">Salario Diario </div></td>
                  <td><input name="txtsalintdia" type="text" id="txtsalintdia" style="text-align:right" onKeyPress="return validaCajas(this, 'd',event,18,3);"></td>
                </tr>
                <tr>
                  <td height="16">&nbsp;</td>
                  <td colspan="6">&nbsp;</td>
                </tr>
                <tr>
                  <td height="16"><div align="right">Tiempo de Servicio </div></td>
                  <td width="56"><div align="right">A&ntilde;os</div></td>
                  <td width="21"><label>
                    <input name="txtano" type="text" id="txtano" size="2" maxlength="4" style="text-align:center">
                  </label></td>
                  <td width="51"><div align="right">Meses</div></td>
                  <td width="22"><label>
                    <input name="txtmes" type="text" id="txtmes" size="2" maxlength="4" style="text-align:center">
                  </label></td>
                  <td>D&iacute;as
                  <input name="txtdia" type="text" id="txtdia" size="2" maxlength="4" style="text-align:center"></td>
                  <td><label></label></td>
                </tr>
                <tr>
                  <td height="16">&nbsp;</td>
                  <td colspan="6">&nbsp;</td>
                </tr>
                <tr>
                  <td height="16"><div align="right">Causa de Retiro </div></td>
                  <td><select name="cmbcauret" id="cmbcauret">
                  </select></td>
                  <td colspan="3">&nbsp;</td>
                  <td><div align="right">D&iacute;as Abonados </div></td>
                  <td><input name="txtdiaabofid" type="text" id="txtdiaabofid" style="text-align:center" onKeyPress="return validaCajas(this,'d',event, 10,2);" ></td>
                </tr>
                <tr>
                  <td height="16">&nbsp;</td>
                  <td><label></label></td>
                  <td colspan="3">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td><label></label></td>
                </tr>
              </table>
            </div>
          </div>
        <div>
            <!-- DIV QUE CONTIENE LAS ESPECIFICACIONES DE LA LIQUIDACION-->
            <div id="datos_detalle" class="celdas-azules" align="center"><strong>Especificaciones de la Liquidación </strong></div>
          <div>
              <table width="99%" border="0" cellspacing="0" cellpadding="0" class="formato-blanco" style="border-color:#FFFFFF">
                <tr>
                  <td height="16" colspan="4">&nbsp;</td>
                </tr>
                <tr>
                  <td height="16"><div align="right">Antig&uuml;edad Desde: </div></td>
                  <td width="16%" height="16"><input name="txtfecdes" type="text" id="txtfecdes" size="18" datepicker="true" style="text-align:center"></td>
                  <td width="13%" height="16"><div align="right">Hasta:</div></td>
                  <td height="16"><input name="txtfechas" type="text" id="txtfechas" size="20" datepicker="true" style="text-align:center"></td>
                </tr>
                <tr>
                  <td height="16" colspan="4">&nbsp;</td>
                </tr>
                <tr>
                  <td width="25%" height="16"><div align="right">Descripci&oacute;n</div></td>
                  <td height="16" colspan="3"><label>
                    <input name="txtdescripcion" type="text" id="txtdescripcion" size="65">
                  </label></td>
                  <td width="1%" height="16">&nbsp;</td>
                  <td width="1%" height="16">&nbsp;</td>
                </tr>
                <tr>
                  <td height="16" colspan="6">&nbsp;</td>
                </tr>
                <tr>
                  <td height="16"><div align="right">D&iacute;as de Salario </div></td>
                  <td height="16" colspan="3"><label>
                    <input name="txtdiasal" type="text" id="txtdiasal" size="10">
                  </label></td>
                  <td height="16">&nbsp;</td>
                  <td height="16">&nbsp;</td>
                </tr>
                <tr>
                  <td height="16" colspan="6">&nbsp;</td>
                </tr>
                <tr>
                  <td height="16"><div align="right">Monto </div></td>
                  <td height="16" colspan="3"><label>
                    <input name="txtmonto" type="text" id="txtmonto" onBlur="ue_deduccion_liq(this);" >
                  </label></td>
                  <td height="16">&nbsp;</td>
                  <td height="16">&nbsp;</td>
                </tr>
                <tr>
                  <td height="16" colspan="6">&nbsp;</td>
                </tr>
                <tr>
                  <td height="16"><div align="right">Articulo</div></td>
                  <td height="16" colspan="2"><label>
                    <select name="cmbarticulo" id="cmbarticulo">
                    </select>
                  </label></td>
                  <td height="16"><label>
                    <input name="btncalcular" type="button" id="btncalcular" value="Calcular" class="celdas-grises" onClick="ue_calcular();">
                  </label></td>
                  <td height="16">&nbsp;</td>
                  <td height="16">&nbsp;</td>
                </tr>
                <tr>
                  <td height="16" colspan="6">&nbsp;</td>
                </tr>
                <tr>
                  <td height="16"><div align="right">Cuenta Contable Deducci&oacute;n </div></td>
                  <td height="16" colspan="2"><label>
                    <input name="txtsc_cta_ps" type="text" id="txtsc_cta_ps">
                    <a href="javascript: ue_buscar_sc_cuenta();"><img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="sc_cuenta" width="20" height="20" border="0" id="sc_cuenta"></a></label></td>
                  <td height="16">&nbsp;</td>
                  <td height="16">&nbsp;</td>
                  <td height="16">&nbsp;</td>
                </tr>
                <tr>
                  <td height="16" colspan="6">&nbsp;</td>
                </tr>
                <tr>
                  <td height="28"><label> </label>
                      <label></label>
                      <label></label>
                      <label>
                      <div align="center">
                        <input name="btndeudaanterior" type="button" id="btndeudaanterior" value="Deuda Anterior" class="celdas-grises" onClick="ue_deuda_anterior();">
                      </div>
                    </label></td>
                  <td height="28"><label>
                    <input name="btnantiguedad" type="button" id="btnantiguedad" value="Antig&uuml;edad" class="celdas-grises" onClick="ue_extraer_antiguedad();">
                  </label></td>
                  <td><input name="btnvacacion" type="button" id="btnvacacion" value="Vacaciones" class="celdas-grises" onClick="ue_vacaciones();"></td>
                  <td height="28"><input name="btnbonvac" type="button" id="btnbonvac" value="Bono vacacional" class="celdas-grises" onClick="ue_bonovacacional();"> </td>
                  <td height="28">&nbsp;</td>
                  <td height="28">&nbsp;</td>
                </tr>
                <tr>
                  <td height="13" colspan="6" align="center">&nbsp;</td>
                </tr>
                <tr>
                  <td height="13" align="center"><label></label></td>
                  <td height="13" colspan="2" align="center"><label></label>
                      <label> </label>
                    <div align="right">
                        <input name="btnincluir" type="button" id="btnincluir" class="celdas-grises" onClick="ue_agregar_detalle();" value="     Incluir     ">
                    </div></td>
                  <td width="44%" align="center">&nbsp;</td>
                  <td height="13" align="center">&nbsp;</td>
                  <td height="13" align="center">&nbsp;</td>
                </tr>
                <tr>
                  <td height="61" colspan="4"><table width="680" align="center" id="dt_liquidacion" cellspacing="1" cellpadding="0" border="1" class="fondo-tabla" onKeyPress="ue_sumarTotal();">
                      <tr class="titulo-ventana">
                        <td width="30"><font color=#FFFFFF class="titulo-ventana" ></font></td>
                        <td width="260" height="19"><font color=#FFFFFF class="titulo-ventana"> Descripción </font></td>
                        <td width="100"><font color=#FFFFFF class="titulo-ventana" >Días Salario</font></td>
                        <td width="120"><font color=#FFFFFF  class="titulo-ventana"> Monto </font></td>
                        <td width="60"><font color=#FFFFFF class="titulo-ventana">&nbsp;X&nbsp;</font></td>
                      </tr>
                    </table>
                      <table width="680"   align="center" cellspacing="1" cellpadding="0" border="0"  onKeyPress="ue_sumarTotal();">
                        <tr  id="fila0" class="celdas-blancas">
                          <td width="30"   align="center">&nbsp;</td>
                          <td width="260" height="19"  align="center">&nbsp;</td>
                          <td width="100"  align="right">&nbsp;</td>
                          <td width="120"  align="right">&nbsp;</td>
                          <td width="60"   align="center">&nbsp;</td>
                        </tr>
                    </table></td>
              </table>
          </div>
        </div>
      </div></td>
    </tr>
    <tr>
      <td height="42" colspan="2"><label></label></td>
      <td width="240" height="42">&nbsp;</td>
      <td width="241">&nbsp;</td>
      <td width="481">Deducciones
        <label>
        <input name="txtdeduccion" type="text" class="titulo-cat&aacute;logo" id="txtdeduccion" size="20" readonly="true">
      </label></td>
      <td width="481">Asignaciones
        <label>
        <input name="txtasignacion" type="text" class="titulo-cat&aacute;logo" id="txtasignacion" size="20" readonly="true">
      </label></td>
      <td width="229" height="42">Total
      <input name="txttotal" type="text" class="titulo-cat&aacute;logo" id="txttotal" size="20" readonly="true"></td>
    </tr>
  </table>
  <label></label>
  <input name="hidpermisos" type="hidden" id="hidpermisos" value="<?Php print $ls_permisos?>">
            <input name="hidbotonera" type="hidden" id="hidbotonera" value="<?php print $ls_botonera?>">
			<!--Para Antiguedad-->
			<input name="hidfecdes"  type="hidden" id="hidfecdes" value="">
			<input name="hidfechas"  type="hidden" id="hidfechas" value="">
			<!--Para calculos-->
			<input name="hidok"  type="hidden" id="hidok"  value="0">
			<input name="hidand" type="hidden" id="hidand" value="0">
			<input name="hidliteral" type="hidden" id="hidliteral" value="">
			<input name="hiddiasal"  type="hidden" id="hiddiasal"  value="">
			<input name="hidtiempo"  type="hidden" id="hidtiempo"  value="">
			<input name="hiddiaacu"  type="hidden" id="hiddiaacu"  value="">
			<input name="hidtemp"    type="hidden" id="hidtemp"  value="">
			<input name="hiddiaabo"  type="hidden" id="hiddiaabo" value="0">
    <!-- LIBRERIA PARA LA BARRA DE HERRAMIENTAS -->
	<script language="JavaScript" src="../../../shared/js/barra_herramientas.js" type="text/javascript"></script>
</form>
</body>
</html>