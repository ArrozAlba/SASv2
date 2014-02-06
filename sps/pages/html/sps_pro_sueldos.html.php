<?php
  header("Cache-Control:no-cache");
  header("Pragma:no-cache");

  $ls_permisos = $_POST["permisos"];
  $ls_botonera = $_POST["botonera"]; 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Registro de Sueldos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<!-- LIBRERIAS Y ESTILOS COMUNES EN TODAS LAS PAGINAS -->
<script type="text/javascript" language="JavaScript1.2" src="../../../shared/js/librerias_comunes.js"></script>
<link href="../../../shared/css/ventanas.css" rel="stylesheet" type="text/css">

<!-- LIBRERIA ESPECIFICA DE ESTA PAGINA -->
<script type="text/javascript" language="javascript1.2" src="../js/sps_pro_sueldos.js"></script>
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
			<table width="705" height="451" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco" id="tabla_fondo">	
				<tr>
				  <td width="703" height="27" colspan="4" class="titulo-ventana">Registro de Sueldos </td>
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
							  <td height="22" align="right">&nbsp;</td>
							  <td >&nbsp;</td>
						    </tr>
							<tr>
							  <td width="168" height="22" align="right">Personal N&ordm; </td>
							  <td width="534" ><label>
							    <input name="txtcodper" type="text" id="txtcodper" size="15" style="text-align:center">
							    <a href="javascript: ue_buscarpersonal();"><img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="personal" width="20" height="20" border="0" id="personal"></a></label></td>
							</tr>
							<tr>
							  <td>&nbsp;</td>
							  <td>&nbsp;</td>
							</tr>			
							<tr>
							  <td height="22" align="right"><span class="style2">Nombres&nbsp;</span></td>
							  <td><label>
							  <input name="txtnomper" type="text" id="txtnomper" size="55">
							  </label>
							  <!--<label><input name="btnbusart" type="button" id="btnbusart" value="Enviar" onClick="ue_chequear_sueldo();" visible="false";></label></td>-->
							</tr>			
							<tr>
							  <td>&nbsp;</td>
							  <td>&nbsp;</td>
							</tr>
							<tr>
							  <td height="22" align="right">Apellidos</td>
							  <td><label>
							  <input name="txtapeper" type="text" id="txtapeper" size="55">
							  </label></td>
							</tr>
							<tr>
							  <td height="16">&nbsp;</td>
							  <td>&nbsp;</td>
							</tr>
							<tr>
							  <td height="16"><div align="right">N&oacute;mina</div></td>
							  <td><label>
							  <input name="txtcodnom" type="text" id="txtcodnom" size="10" maxlength="6" style="text-align:center">
							  <input name="txtdennom" type="text" id="txtdennom" size="41">
							  </label></td>
							</tr>
							<tr>
							  <td height="16">&nbsp;</td>
							  <td>&nbsp;</td>
							</tr>
							
							
							<tr>
							  <td height="16">&nbsp;</td>
							  <td>&nbsp;</td>
							</tr>
						 </table>	
						</div>
					  </div>
					  <div>
						<!-- DIV QUE CONTIENE LOS DATOS DE LOS SUELDOS DETALLE-->
						<div id="datos_detalle" class="celdas-azules" align="center"><strong>Detalles de Sueldos </strong></div>
						<div>
						  <table width="97%" border="0" cellspacing="0" cellpadding="0" class="formato-blanco" style="border-color:#FFFFFF">			
							<tr>
							  <td height="16" colspan="2">&nbsp; </td>
							</tr>
										
									
						    <tr>
						      <td width="24%" height="16"> <div align="right">Fecha Sueldo  </div>
					          <label></label></td>
					          <td width="74%"><input name="txtfecincsue" type="text" id="txtfecincsue" style="text-align:center" datepicker="true"></td>
					          <td width="1%" height="16">&nbsp;</td>
					          <td width="1%" height="16">&nbsp;</td>
						    </tr>
						    <tr>
						      <td height="16" colspan="4">&nbsp; </td>
					        </tr>
						    <tr>
						      <td height="16"><div align="right">Sueldo Base 
						        </div>
					          <label></label></td>
					          <td height="16"><input name="txtmonsuebas" type="text" id="txtmonsuebas" style="text-align:right" onBlur="calcularSueldoDia();" onKeyPress="return validaCajas(this, 'd',event,18,2);"></td>
					          <td height="16">&nbsp;</td>
					          <td height="16">&nbsp;</td>
						    </tr>
						    <tr>
						      <td height="16" colspan="4">&nbsp;</td>
					        </tr>
						    <tr>
						      <td height="16"><label>
						        <div align="right">Sueldo Integral </div>
						      </label>					          </td>
					          <td height="16"><input name="txtmonsueint" type="text" id="txtmonsueint" style="text-align:right" onKeyPress="return validaCajas(this,'d',event,18,2)"></td>
					          <td height="16">&nbsp;</td>
					          <td height="16">&nbsp;</td>
						    </tr>
						    <tr>
						      <td height="16" colspan="4">&nbsp;</td>
					        </tr>
						    <tr>
						      <td height="16"><div align="right">Sueldo Promedio Diario </div></td>
					          <td height="16"><label>
					            <input name="txtmonsuenordia" type="text" id="txtmonsuenordia" style="text-align:right"  onKeyPress="return validaCajas(this,'d',event,18,2)">
					          </label></td>
					          <td height="16">&nbsp;</td>
					          <td height="16">&nbsp;</td>
						    </tr>
						    <tr>
						      <td height="16" colspan="4">&nbsp;</td>
					        </tr>
						    
						    <tr>
							  <td height="16"><label></label></td>
						      <td height="16"><label></label></td>
						      <td height="16">&nbsp;</td>
						      <td height="16">&nbsp;</td>
						    </tr>
						    <tr>
							  <td height="13" colspan="4" align="center"><label></label>
							  <input name="btnincdet" type="button" class="celdas-grises" id="btnincdet" onClick="ue_agregar_detalle();" value="     Incluir     ">
							  <label>
							    <input name="btnsuenom" type="button" class="celdas-grises" id="btnsuenom"  onClick="ue_sueldos_nomina();" value=" Extraer Sueldos N&oacute;mina ">
							    </label></td>
						    </tr>
												
							<tr><td height="61" colspan="2"><table width="680" align="center" id="dt_sueldo" cellspacing="1" cellpadding="0" border="1" class="fondo-tabla">
                              <tr class="titulo-ventana">
                                <td width="100" height="19"><font color=#FFFFFF class="titulo-ventana"> Fecha </font></td>
                                <td width="130"><font color=#FFFFFF class="titulo-ventana" >Sueldo Base</font></td>
                                <td width="130"><font color=#FFFFFF  class="titulo-ventana">Sueldo Integral</font></td>
                                <td width="130"><font color=#FFFFFF class="titulo-ventana">Promedio Diario</font></td>
                                <td width="60"><font color=#FFFFFF class="titulo-ventana">&nbsp;X&nbsp;</font></td>
                              </tr>
                            </table>
						    <table width="680"   align="center" cellspacing="1" cellpadding="0" border="0">
								  <tr  id="fila0" class="celdas-blancas">
									<td width="100" height="19"  align="center">&nbsp;</td>
									<td width="130"  align="right">&nbsp;</td>
									<td width="130"  align="right">&nbsp;</td>
									<td width="130"  align="right">&nbsp;</td>
									<td width="60"   align="center">&nbsp;</td>
								  </tr>
								</table></td>																		
						 </table>							
						</div>
					  </div>
				   </div>			
				 </td>	  					
				</tr> 
  </table>
	<input name="hidestsue"   type="hidden" id="hidestsue" value="">		
	<input name="hidpermisos" type="hidden" id="hidpermisos" value="<?Php print $ls_permisos?>">
	<input name="hidbotonera" type="hidden" id="hidbotonera" value="<?php print $ls_botonera?>">
	<!-- LIBRERIA PARA LA BARRA DE HERRAMIENTAS -->
	<script language="JavaScript" src="../../../shared/js/barra_herramientas.js" type="text/javascript"></script>
</form>
</body>
</html>