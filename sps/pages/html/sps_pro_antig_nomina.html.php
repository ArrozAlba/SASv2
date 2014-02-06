<?php
  header("Cache-Control:no-cache");
  header("Pragma:no-cache");

  $ls_permisos = $_POST["permisos"];
  $ls_botonera = $_POST["botonera"]; 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Antig&uuml;edad por N&oacute;mina</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<!-- LIBRERIAS Y ESTILOS COMUNES EN TODAS LAS PAGINAS -->
<script type="text/javascript" language="JavaScript1.2" src="../../../shared/js/librerias_comunes.js"></script>
<link href="../../../shared/css/ventanas.css" rel="stylesheet" type="text/css">

<!-- LIBRERIA ESPECIFICA DE ESTA PAGINA -->
<script type="text/javascript" language="javascript1.2" src="../js/sps_pro_antig_nomina.js"></script>
<script language="javascript1.2" type="text/javascript" src="../../../shared/js/js_intra/datepickercontrol.js"></script>
<style type="text/css">
<!--
.Estilo3 {font-size: 14px}
.Estilo5 {font-size: 12}
.Estilo6 {font-size: 12px}
-->
</style>
<link href="../../class_folder/css/ventanas_sps.css" rel="stylesheet" type="text/css">
</head>

<body link="#006699" vlink="#006699" alink="#006699">

<script language="JavaScript1.2" type="text/javascript" src="../../../shared/js/cabecera_arriba.js"></script>
<script language="JavaScript1.2" type="text/javascript" src="../js/menu/menu_sps.js"></script>
<script language="JavaScript1.2" type="text/javascript" src="../../../shared/js/cabecera_abajo.js"></script>

<form name="form1" id="form1" method="post" action="">
			<table width="705" height="451" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco" id="tabla_fondo">	
				<tr>
				  <td width="703" height="27" colspan="4" class="titulo-ventana"> Antig&uuml;edad por N&oacute;mina </td>
				</tr>	
				<tr>
				 <td height="422" colspan="4">
				   <div id="acordion">			   
					  <div>
						<!-- DIV QUE CONTIENE LOS DATOS DEL ARTICULO -->
						<div id="datos_articulos" class="celdas-azules" align="center"><strong>Datos del Empleado </strong></div>
						<div>
						  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="formato-blanco" style="border-color:#FFFFFF">
							<tr>
							  <td width="177" height="22" align="right">&nbsp;</td>
							  <td colspan="3" >&nbsp;</td>
						    </tr>
							
							<tr>
							  <td><div align="right">C&oacute;digo</div></td>
							  <td colspan="3"><label>
							    <input name="txtcodper" type="text" id="txtcodper" size="15" style="text-align:center">
							    <a href="javascript: ue_buscarpersonal();"><img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="personal" width="20" height="20" border="0" id="personal"></a></label></td>
							</tr>			
							<tr>
							  <td height="13" align="right">&nbsp;</td>
							  <td colspan="3">&nbsp;</td>
						    </tr>
							<tr>
							  <td height="22" align="right"><span class="style2">Nombres&nbsp;</span></td>
							  <td width="305"><label>
							  <input name="txtnomper" type="text" id="txtnomper" size="55">
							  </label>							  </td>  
							  <td width="66"><div align="right">Apellidos</div></td>
							  <td width="562"><input name="txtapeper" type="text" id="txtapeper" size="50"></td>
							  <!-- <label><a href="javascript: ue_buscarpersonal();"></a></label>-->
							</tr>			
							<tr>
							  <td>&nbsp;</td>
							  <td colspan="3">&nbsp;</td>
							</tr>
							<tr>
							  <td height="22" align="right">N&oacute;mina</td>
							  <td colspan="3"><label>
							  <input name="txtcodnom" type="text" id="txtcodnom" size="15" maxlength="6" style="text-align:center">
							  <input name="txtdennom" type="text" id="txtdennom" size="112">
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
						</div>
					  </div>
					  <div>
						<!-- DIV QUE CONTIENE LOS DATOS DE LA TASA DETALLE-->
						<div id="datos_detalle" class="celdas-azules" align="center"></div>
						<div>
						  <table width="99%" border="0" cellspacing="0" cellpadding="0" class="formato-blanco" style="border-color:#FFFFFF">			
						    <tr>
						      <td height="16" colspan="3"><label></label></td>
					          <td colspan="4">&nbsp;</td>
				            </tr>
						    <tr>
						      <td height="15" colspan="7">&nbsp; </td>
					        </tr>
						    <tr>
						      <td height="16"><div align="right">Fecha Desde </div></td>
					          <td width="9%" height="16"><label>
					          <input name="txtfecdes" type="text" id="txtfecdes" size="16" style="text-align:center" datepicker="true">
					          </label></td>
					          <td width="17%">Fecha Hasta 
				              <input name="txtfechas" type="text" id="txtfechas" size="16" style="text-align:center" datepicker="true"></td>
					          <td width="9%" height="16"><div align="right">Fecha Ingreso </div></td>
						      <td width="11%"><label></label>
						        <label>
						        <input name="txtfecingper" type="text" id="txtfecingper" size="16" style="text-align:center">
					          </label></td>
						      <td width="10%"><div align="right">Fecha Vigente Articulo </div></td>
						      <td width="20%"><label>
						        <input name="txtfecvig" type="text" id="txtfecvig" size="16" style="text-align:center">
						      </label></td>
						    </tr>
						    <tr>
						      <td height="16" colspan="7">&nbsp;</td>
					        </tr>
						    <tr>
						      <td width="24%" height="16"><div align="right">Tiempo de Servicios: A&ntilde;os </div></td>
					          <td colspan="2"><label>
				              <input name="txtanoserant" type="text" id="txtanoserant" size="10" style="text-align:center">
					          Meses 
					          <input name="txtmesserant" type="text" id="txtmesserant" size="10" style="text-align:center">
					          D&iacute;as 
					          <input name="txtdiaserant" type="text" id="txtdiaserant" size="10" style="text-align:center">
					          </label></td>
					          <td height="16">&nbsp;</td>
					          <td height="16">&nbsp;</td>
					          <td height="16">&nbsp;</td>
						      <td height="16"><label></label></td>
						    </tr>
						    <tr>
						      <td height="16" colspan="7">&nbsp;</td>
					        </tr>
						    <tr>
						      <td height="16" colspan="7">&nbsp;</td>
					        </tr>
						    
						    <tr>
							  <td height="13" colspan="7" align="center"><label>
							    <input name="btnantignom" type="button" id="btnantignom" value="Antiguedad N&oacute;mina" onClick="ue_consultar_anticipos();">
							  </label>
							    <input name="btngenint" type="button" class="celdas-grises" id="btngenint" onClick="ue_calcular_interes();" value="     Generar Inter&eacute;s     ">
							    <label></label></td>
						    </tr>
												
							<tr>
							  <td height="17" colspan="7">&nbsp;</td>
						    <tr><td height="61" colspan="7"><table width="1210" align="center" id="dt_antig" cellspacing="1" cellpadding="0" border="1" class="fondo-tabla">
                              <tr class="titulo-ventana">
                                <td width="82" height="35" align="center"><font color=#FFFFFF class="titulo-ventana-grid">Período</font></td>
                                <td width="75" height="35" align="center"><font class="titulo-ventana-grid" >Salario  Base</font></td>
                                <td width="70" height="35" align="center"><font class="titulo-ventana-grid">Inc Vacación</font></td>
								<td width="70" height="35" align="center"><font class="titulo-ventana-grid">Inc Aguinaldo</font></td>
								<td width="70" height="35" align="center"><font class="titulo-ventana-grid">Salario Integral</font></td>
								<td width="45" height="35" align="center"><font class="titulo-ventana-grid">Días Anti g</font></td>
								<td width="45" height="35" align="center"><font class="titulo-ventana-grid">Días Comp</font></td>
								<td width="90" height="35" align="center"><font class="titulo-ventana-grid">Prestación Social</font></td>
								<td width="90" height="35" align="center"><font class="titulo-ventana-grid">Prestac Social Acum.</font></td>
								<td width="80" height="35" align="left"><font class="titulo-ventana-grid">Anticipo</font></td>
								<td width="80" height="35" align="center"><font class="titulo-ventana-grid">Saldo Parcial</font></td>
								<td width="50" height="35" align="center"><font class="titulo-ventana-grid">Tasa (%) </font></td>
								<td width="50" height="35" align="center"><font class="titulo-ventana-grid">Días Interés </font></td>
								<td width="70" height="35" align="center"><font class="titulo-ventana-grid">Interés Período</font></td>
								<td width="75" height="35" align="center"><font class="titulo-ventana-grid">Interés Acum.</font></td>
								<td width="112" height="35" align="center"><font class="titulo-ventana-grid">Saldo Total</font></td>
                              </tr>
                            </table>
						    <table width="1210"   align="center" cellspacing="1" cellpadding="0" border="0">
								  <tr  id="fila0" class="celdas-blancas">
									<td width="82" height="35"  align="center">&nbsp;</td>
									<td width="75" height="35"  align="center">&nbsp;</td>
									<td width="70" height="35"  align="center">&nbsp;</td>
									<td width="70" height="35"  align="center">&nbsp;</td>
									<td width="70" height="35"  align="center">&nbsp;</td>
									<td width="45" height="35"  align="center">&nbsp;</td>
									<td width="45" height="35"  align="center">&nbsp;</td>
									<td width="90" height="35"  align="center">&nbsp;</td>
									<td width="90" height="35"  align="center">&nbsp;</td>
									<td width="80" height="35"  align="center">&nbsp;</td>
									<td width="80" height="35"  align="center">&nbsp;</td>
									<td width="50" height="35"  align="center">&nbsp;</td>
									<td width="50" height="35"  align="center">&nbsp;</td>
									<td width="70" height="35"  align="center">&nbsp;</td>
									<td width="75" height="35"  align="center">&nbsp;</td>
									<td width="112" height="35"  align="center">&nbsp;</td>
								  </tr>
							  </table></td>																		
						 </table>							
						</div>
					  </div>
				   </div>			
				 </td>	  					
				</tr> 
  </table>		
	<input name="hidpermisos" type="hidden" id="hidpermisos" value="<?Php print $ls_permisos?>">
	<input name="hidbotonera" type="hidden" id="hidbotonera" value="<?php print $ls_botonera?>">
	<!-- Antiguedad -->
	<input name="hidestsue"   type="hidden" id="hidestsue" value="">
	<input name="hidsueldo"   type="hidden" id="hidsueldo"  value="">
	<!-- Anticipo -->
	<input name="hidfecant"  type="hidden" id="hidfecant" value="">
	<input name="hidmonant"  type="hidden" id="hidmonant" value="">
	<!-- Tasa de Interes -->
	<input name="hidtasint"  type="hidden" id="hidtasint" value="">

	<!-- LIBRERIA PARA LA BARRA DE HERRAMIENTAS -->
	<script language="JavaScript" src="../../../shared/js/barra_herramientas.js" type="text/javascript"></script>
</form>
</body>
</html>