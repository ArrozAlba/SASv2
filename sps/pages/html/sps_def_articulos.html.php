<?php
  header("Cache-Control:no-cache");
  header("Pragma:no-cache");

  $ls_permisos = $_POST["permisos"];
  $ls_botonera = $_POST["botonera"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Definici&oacute;n de Articulos de Ley</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<!-- LIBRERIAS Y ESTILOS COMUNES EN TODAS LAS PAGINAS -->
<script type="text/javascript" language="JavaScript1.2" src="../../../shared/js/librerias_comunes.js"></script>
<link href="../../../shared/css/ventanas.css" rel="stylesheet" type="text/css">

<!-- LIBRERIA ESPECIFICA DE ESTA PAGINA -->
<script type="text/javascript" language="javascript1.2" src="../js/sps_def_articulos.js"></script>
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
				  <td width="703" height="27" colspan="4" class="titulo-ventana">Definición de Articulos de Ley			  </td>
				</tr>	
				<tr>
				 <td height="422" colspan="4">
				   <div id="acordion">			   
					  <div>
						<!-- DIV QUE CONTIENE LOS DATOS DEL ARTICULO -->
						<div id="datos_articulos" class="celdas-azules" align="center"><strong>Datos del Artículo</strong></div>
						<div>
						  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="formato-blanco" style="border-color:#FFFFFF">
							<tr>
							  <td width="134" height="22" align="right"><span class="style2">C&oacute;digo</span></td>
							  <td width="334" >  
								<input name="txtid_art" type="text" id="txtid_art" style="text-align:center" value="" size="8" maxlength="4" onBlur="uf_padl(this,4,'0');" ></td>
							</tr>
							<tr>
							  <td>&nbsp;</td>
							  <td>&nbsp;</td>
							</tr>			
							<tr>
							  <td height="22" align="right"><span class="style2">N&uacute;mero Art&iacute;culo&nbsp;</span></td>
							  <td><input name="txtnumart" type="text" id="txtnumart" onKeyPress="return validaCajas( this,'i', event );" value="" size="8" maxlength="4" style="text-align:center">
							   <label>
							    <input name="btnbusart" type="button" id="btnbusart" value="Enviar" onClick="ue_chequear_articulo();" visible="true"; >
						      </label></td>
							</tr>			
							<tr>
							  <td>&nbsp;</td>
							  <td>&nbsp;</td>
							</tr>
							<tr>
							  <td height="22" align="right">Fecha Vigente </td>
							  <td><label>
							  <input name="txtfecvig" type="text" id="txtfecvig" size="15" maxlength="10" datepicker="true" style="text-align:center">
							  </label></td>
							</tr>
							<tr>
							  <td height="16">&nbsp;</td>
							  <td>&nbsp;</td>
							</tr>
							<tr>
							  <td height="16"><div align="right">Concepto</div></td>
							  <td><label>
							    <input name="txtconart" type="text" id="txtconart" size="80" maxlength="80">
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
						<!-- DIV QUE CONTIENE LOS DATOS DE LA TASA DETALLE-->
						<div id="datos_detalle" class="celdas-azules" align="center"><strong>Detalles de Articulos</strong>
						</div>
						<div>
						  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="formato-blanco" style="border-color:#FFFFFF">			
							<tr>
							  <td width="86%" height="16">&nbsp; </td>
							</tr>
							<tr>
							  <td height="22"><table width="684" border="1">
                                <tr>
                                  <td width="46"><div align="center"><span class="Estilo6">Literal</span></div></td>
                                  <td width="69" class="Estilo6"><div align="center"><span class="Estilo5">Operador</span></div></td>
                                  <td width="66" class="Estilo6"><div align="center"><span class="Estilo5">Meses Servicio</span></div></td>
                                  <td width="94" class="Estilo6"><div align="center"><span class="Estilo5">
                                  </span></div>                                    <span class="Estilo5"><label>
                                    <div align="center">D&iacute;as  Salario </div>
                                    </label>
                                  </span></td>
                                  <td width="104" class="Estilo6"><div align="center"><span class="Estilo5">Tiempo</span></div></td>
                                  <td width="80" class="Estilo6"><div align="center"><span class="Estilo5">Condici&oacute;n</span></div></td>
                                  <td width="63" class="Estilo6"><div align="center"><span class="Estilo5">Acum</span></div></td>
                                  <td width="110" class="Estilo6"><div align="center"><span class="Estilo5">D&iacute;as Acum. </span></div></td>
                                </tr>
                                <tr>
                                  <td height="26"><div align="center">
                                    <input name="txtnumlitart" style="text-align:center" type="text" id="txtnumlitart" size="6" maxlength="4">
                                  </div></td>
                                  <td><div align="center">
                                    <select name="cmboperador" id="cmboperador">
                                      <option value="1">&gt;</option>
                                      <option value="2">&lt;</option>
                                      <option value="3">&gt;=</option>
                                      <option value="4">&lt;=</option>
                                      <option value="0">=</option>
                                    </select>
                                  </div></td>
                                  <td><div align="center">
                                    <input name="txtcanmes" type="text" id="txtcanmes" size="9" maxlength="6" onBlur="ue_getformat(this);" style="text-align:center" onKeyPress="return validaCajas(this,'d',event,6);">
                                  </div></td>
                                  <td><div align="center">
                                    <input name="txtdiasal" type="text" id="txtdiasal" size="10" maxlength="6" onBlur="ue_getformat(this);" style="text-align:center" onKeyPress="return validaCajas(this,'d',event,6);">
                                  </div></td>
                                  <td><div align="center">
                                    <select name="cmbtiempo" id="cmbtiempo">
                                      <option value="V">1 Vez</option>
                                      <option value="D">c/D&iacute;a</option>
                                      <option value="S">c/Semana</option>
                                      <option value="M">c/Mes</option>
                                      <option value="A">c/A&ntilde;o</option>
                                    </select>
                                  </div></td>
                                  <td><div align="center">
                                    <select name="cmbcondicion" id="cmbcondicion">
                                      <option value="NONE">NONE</option>
                                      <option value="AND">AND</option>
                                      <option value="OR">OR</option>
                                    </select>
                                  </div></td>
                                  <td><div align="center">
                                    <select name="cmbestacu" id="cmbestacu">
                                      <option value="N">NO</option>
                                      <option value="S">SI</option>
                                    </select>
                                  </div></td>
                                  <td><div align="center"><span class="Estilo3">hasta</span>
                                    <input name="txtdiaacu" type="text" id="txtdiaacu" onBlur="ue_getformat(this);" onKeyPress="return validaCajas(this,'d',event,6);" value="0" size="10" maxlength="6">
                                  </div></td>
                                </tr>
                              </table>							    
							  <label></label></td>
							</tr>			
									
						    <tr>
							  <td height="16" colspan="3">&nbsp;</td>
						    </tr>
						    <tr>
							  <td height="13" colspan="3" align="center"><input name="btnincdet" type="button" class="celdas-grises" id="btnincdet" onClick="ue_agregar_detalle();" value="     Incluir     "></td>
						    </tr>
												
							<td height="61"><table width="680" align="center" id="dt_art" cellspacing="1" cellpadding="0" border="1" class="fondo-tabla">
                              <tr class="titulo-ventana">
                                <td width="60" height="19"><font color=#FFFFFF class="titulo-ventana">Literal</font></td>
                                <td width="56"><font color=#FFFFFF class="titulo-ventana" >Op</font></td>
                                <td width="68"><font color=#FFFFFF  class="titulo-ventana">Mes</font></td>
                                <td width="92"><font color=#FFFFFF class="titulo-ventana">Días S.</font></td>
                                <td width="89"><font color=#FFFFFF class="titulo-ventana">Tiempo</font></td>
                                <td width="68"><font color=#FFFFFF  class="titulo-ventana">Cond.</font></td>
                                <td width="72"><font color=#FFFFFF  class="titulo-ventana">Acum.</font></td>
                                <td width="88"><font color=#FFFFFF class="titulo-ventana">Días Acu.</font></td>
                                <td width="53"><font color=#FFFFFF class="titulo-ventana">&nbsp;X&nbsp;</font></td>
                              </tr>
                            </table>
						    <table width="680"   align="center" cellspacing="1" cellpadding="0" border="0">
								  <tr  id="fila0" class="celdas-blancas">
									<td width="67" height="19"  align="right">&nbsp;</td>
									<td width="57"  align="center">&nbsp;</td>
									<td width="71"  align="center">&nbsp;</td>
									<td width="91"  align="center">&nbsp;</td>
									<td width="91"   align="center">&nbsp;</td>
									<td width="72"   align="center">&nbsp;</td>
									<td width="75"   align="center">&nbsp;</td>
									<td width="87"   align="center">&nbsp;</td>
									<td width="59"   align="center">&nbsp;</td>
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
	<!-- LIBRERIA PARA LA BARRA DE HERRAMIENTAS -->
	<script language="JavaScript" src="../../../shared/js/barra_herramientas.js" type="text/javascript"></script>
</form>
</body>
</html>