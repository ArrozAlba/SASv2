<?php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_conexion.php'";
	print "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Elaboraci&oacute;n de Acta de Paralizaci&oacute;n</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style6 {color: #000000}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
-->
</style><meta http-equiv="Content-Type" content="text/html; charset="></head>
<body link="#006699" vlink="#006699" alink="#006699">
<?Php
require_once("../shared/class_folder/class_sql.php");
require_once("class_folder/sigesp_sob_c_contrato.php");
$io_contrato=new sigesp_sob_c_contrato();
require_once("class_folder/sigesp_sob_class_obra.php");
$io_obra=new sigesp_sob_class_obra();
require_once("../shared/class_folder/grid_param.php");
$io_grid=new grid_param();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_datastore.php");
$io_datastore=new class_datastore();
require_once("../shared/class_folder/class_funciones.php");
$io_function=new class_funciones();
require_once("../shared/class_folder/class_datastore.php");
$io_datastore=new class_datastore();
require_once ("class_folder/sigesp_sob_c_funciones_sob.php");
$io_funsob= new sigesp_sob_c_funciones_sob(); 

$ls_tituloretenciones="Retenciones Asignadas";
$li_anchoretenciones=600;
$ls_nametable="grid";
$la_columretenciones[1]="Código";
$la_columretenciones[2]="Descripción";
$la_columretenciones[3]="Cuenta";
$la_columretenciones[4]="Deducible";
$la_columretenciones[5]="Edición";

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	
}
else
{
	$ls_datoscontrato="OCULTAR";
	$ls_datosobra="OCULTAR";
	$ls_fecinicon="";
	$ls_placon="";
	$ls_placonuni="";
	$ls_contasi="";
	$ls_contasi="";
	$ls_moncon="";
	$ls_estcon="";
	$ls_codobr="";
	$ls_desobr="";
	$ls_estobr="";
	$ls_estobr="";
	$ls_munobr="";
	$ls_comobr="";
	$ls_parobr="";
	$ls_dirobr="";
	$ls_codcon="";
	$ls_codact="";
	$ls_fecact="";
	$ls_feciniact="";
	$ls_fecfinact="";
	$ls_nominsact="";
	$ls_cedinsact="";
	$ls_nomsupact="";
	$ls_cedsupact="";
	$ls_motact="";
	$ls_resact="";
	$ls_cedresact="";
}

/////////Instrucciones para evitar que las cajitas pierdan la informacion cada vez que se realiza un submit/////////////
if	(array_key_exists("hiddatoscontrato",$_POST)){	$ls_datoscontrato=$_POST["hiddatoscontrato"]; }
else{$ls_datoscontrato="OCULTAR";}

if	(array_key_exists("hiddatosobra",$_POST)){	$ls_datosobra=$_POST["hiddatosobra"]; }
else{$ls_datosobra="OCULTAR";}

if	(array_key_exists("operacion",$_POST)){	$ls_operacion=$_POST["operacion"]; }
else{$ls_operacion="";}

if	(array_key_exists("txtcodcon",$_POST)){	$ls_codcon=$_POST["txtcodcon"]; }
else{$ls_codcon="";}

if	(array_key_exists("txtfecinicon",$_POST)){$ls_fecinicon=$_POST["txtfecinicon"]; }
else{$ls_fecinicon="";}

if	(array_key_exists("txtplacon",$_POST)){$li_placon=$_POST["txtplacon"]; }
else{$li_placon="0";}

if	(array_key_exists("txtplaconuni",$_POST)){$ls_placonuni=$_POST["txtplaconuni"]; }
else{$ls_placonuni="";}

if	(array_key_exists("txtcontasi",$_POST)){$ls_contasi=$_POST["txtcontasi"]; }
else{$ls_contasi="";}

if	(array_key_exists("txtmoncon",$_POST)){$ls_moncon=$_POST["txtmoncon"]; }
else{$ls_moncon="";}	

if	(array_key_exists("txtestcon",$_POST)){$ls_estcon=$_POST["txtestcon"]; }
else{$ls_estcon="";}	

if	(array_key_exists("txtcodobr",$_POST)){$ls_codobr=$_POST["txtcodobr"]; }
else{$ls_codobr="";}

if	(array_key_exists("txtdesobr",$_POST)){$ls_desobr=$_POST["txtdesobr"]; }
else{$ls_desobr="";}

if	(array_key_exists("txtestobr",$_POST)){$ls_estobr=$_POST["txtestobr"]; }
else{$ls_estobr="";}

if	(array_key_exists("txtmunobr",$_POST)){$ls_munobr=$_POST["txtmunobr"]; }
else{$ls_munobr="";}

if	(array_key_exists("txtcomobr",$_POST)){$ls_comobr=$_POST["txtcomobr"]; }
else{$ls_comobr="";}

if	(array_key_exists("txtparobr",$_POST)){$ls_parobr=$_POST["txtparobr"]; }
else{$ls_parobr="";}

if	(array_key_exists("txtdirobr",$_POST)){$ls_dirobr=$_POST["txtdirobr"]; }
else{$ls_dirobr="";}

if	(array_key_exists("txtcodact",$_POST)){$ls_codact=$_POST["txtcodact"]; }
else{$ls_codact="";}

if	(array_key_exists("txtfecact",$_POST)){$ls_fecact=$_POST["txtfecact"]; }
else{$ls_fecact="";}

if	(array_key_exists("txtfeciniact",$_POST)){$ls_feciniact=$_POST["txtfeciniact"]; }
else{$ls_feciniact="";}

if	(array_key_exists("txtfecfinact",$_POST)){$ls_fecfinact=$_POST["txtfecfinact"]; }
else{$ls_fecfinact="";}

if	(array_key_exists("txtnominsact",$_POST)){$ls_nominsact=$_POST["txtnominsact"]; }
else{$ls_nominsact="";}

if	(array_key_exists("txtcedinsact",$_POST)){$ls_cedinsact=$_POST["txtcedinsact"]; }
else{$ls_cedinsact="";}

if	(array_key_exists("txtnomsupact",$_POST)){$ls_nomsupact=$_POST["txtnomsupact"]; }
else{$ls_nomsupact="";}

if	(array_key_exists("txtcedsupact",$_POST)){$ls_cedsupact=$_POST["txtcedsupact"]; }
else{$ls_cedsupact="";}

if	(array_key_exists("txtresact",$_POST)){$ls_resact=$_POST["txtresact"]; }
else{$ls_resact="";}

if	(array_key_exists("txtcedresact",$_POST)){$ls_cedresact=$_POST["txtcedresact"]; }
else{$ls_cedresact="";}	

if	(array_key_exists("txtmotact",$_POST)){$ls_motact=$_POST["txtmotact"]; }
else{$ls_motact="";}	



////////////////////////////////Operaciones de Actualizacion//////////////////////////////////////

if($ls_operacion=="ue_nuevo")//Abre una ficha de obra nueva
{
	require_once("../shared/class_folder/class_funciones_db.php");
	require_once ("../shared/class_folder/sigesp_include.php");		
	$io_include=new sigesp_include();
	$io_connect=$io_include->uf_conectar();
	$io_funcdb=new class_funciones_db($io_connect);
	$la_empresa=$_SESSION["la_empresa"];
	$ls_codact=$io_funsob->uf_generar_codigoacta(1,$ls_codcon);	
	$ls_fecinicon="";	
	$ls_feciniact="";
	$ls_fecfinact="";
	$ls_nominsact="";
	$ls_cedinsact="";
	$ls_nomsupact="";
	$ls_cedsupact="";
	$ls_resact="";
	$ls_motact="";
	$ls_cedresact="";
	$fecha=date("d/m/Y");
	$ls_fecact=$fecha;	
}
elseif($ls_operacion=="ue_cargarcontrato")
{
	$lb_valido=$io_contrato->uf_select_contrato($ls_codcon,$la_data);
	if($lb_valido)
	{
		$ls_fecinicon=$io_function->uf_convertirfecmostrar($la_data["fecinicon"][1]);
		$ls_placon=$io_funsob->uf_convertir_decimalentero($la_data["placon"][1]);
		$ls_placonuni=$la_data["nomuni"][1];
		$ls_contasi=$la_data["nompro"][1];
		$ls_moncon=$io_funsob->uf_convertir_numerocadena($la_data["monto"][1]);
		$ls_estcon=$io_funsob->uf_convertir_numeroestado($la_data["estcon"][1]);
		$ls_codobr=$la_data["codobr"][1];
		$ls_codcon=$la_data["codcon"][1];
		$lb_valido=$io_obra->uf_select_obra($ls_codobr,$la_data);
		if($lb_valido)
		{
			$ls_desobr=$la_data["desobr"][1];
			$ls_estobr=$la_data["desest"][1];
			$ls_munobr=$la_data["denmun"][1];
			$ls_comobr=$la_data["nomcom"][1];
			$ls_parobr=$la_data["denpar"][1];
			$ls_dirobr=$la_data["dirobr"][1];
		}
	}
}
?>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img name="imgnuevo" id="imgnuevo" src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
  <p>&nbsp;
  </p>
  <form name="form1" method="post" action="">
  <table width="638" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
      <tr class="formato-blanco">
        <td>&nbsp;</td>
        <td colspan="8" class="titulo-celdanew">Datos del Contrato </td>
        <td>&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td width="105">&nbsp;</td>
        <td width="126">&nbsp;</td>
        <td colspan="4">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td width="16" height="22"><div align="right"></div></td>
        <td width="37"><div align="right">C&oacute;digo</div></td>
        <td colspan="2"><input name="txtcodcon" type="text" id="txtcodcon" style="text-align:center " value="<?php print $ls_codcon ?>" size="8" maxlength="8" readonly="true">
        <input name="operacion" type="hidden" id="operacion">
        <a href="javascript:ue_catcontrato();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a> </td>
        <td width="152">&nbsp;</td>
        <td colspan="4"><div align="right"><a href="javascript:uf_mostrar_ocultar_contrato();">&nbsp;&nbsp;<img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:uf_mostrar_ocultar_contrato();">Datos del Contrato </a></div></td>
        <td width="5">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="13"><div align="right"></div></td>
        <td height="13" colspan="3"></td>
        <td colspan="5"><div align="right"></div></td>
        <td>&nbsp;</td>
      </tr>
      
		<?Php
			if ($ls_datoscontrato=="MOSTRAR")
			{
			?>		
				<tr class="formato-blanco">
				  <td height="79" class="sin-borde">&nbsp;</td>
				  <td height="79" colspan="8" align="center" valign="top" class="sin-borde">				  <table width="500" height="78" border="0" cellpadding="0"  cellspacing="0" >
                    <tr class="formato-blanco">
                      <td width="82" height="13"><div align="right">Fecha de Inicio</div></td>
                      <td width="140"><input name="txtfecinicon"  stype="text" id="fecinicon"  style="text-align:center "value="<?php print $ls_fecinicon?>" size="11" maxlength="11" readonly="true"></td>
                      <td width="60"><div align="right">Duraci&oacute;n</div></td>
                      <td width="218"><input name="txtplacon"  style="text-align:center "  type="text" id="txtfecasi" value="<?php print $ls_placon?> <?php print $ls_placonuni?>" size="11" maxlength="11" readonly="true">
&nbsp;&nbsp;&nbsp;
                      </td>
                    </tr>
                    <tr class="formato-blanco">
                      <td height="22"> <div align="right">Contratista</div></td>
                      <td height="22" colspan="3">
                      <input name="txtcontasi" type="text" id="txtcontasi" value="<?php print $ls_contasi?>" size="70" maxlength="254" readonly="true"></td>
                    </tr>
                    <tr class="formato-blanco">
                      <td height="19" valign="top" class="navigation"><div align="right">Monto</div></td>
                      <td height="19" colspan="3" valign="top"><input name="txtmoncon" type="text"  readonly="true" id="txtmoncon"  style="text-align: right "value="<?php print $ls_moncon?>" size="21" maxlength="21"></td>
                    </tr>
                    <tr class="formato-blanco">
                      <td height="19" valign="top" class="navigation"><div align="right">Estado Actual</div></td>
                      <td height="19" colspan="3" valign="top"><input name="txtestcon" type="text" id="txtestcon" value="<?php print $ls_estcon?>" size="21" maxlength="30" readonly="true" style="text-align:center "></td>
                    </tr>
                  </table></td>
				  <td height="79" class="sin-borde">&nbsp;</td>
    			</tr>
				<tr class="formato-blanco">
        		<td height="13" class="sin-borde">&nbsp;</td>
       			 <td height="13" colspan="8" align="center" valign="top" class="sin-borde"><div align="right"><a href="javascript:ue_uf_mostrar_ocultar_obra();">&nbsp;&nbsp;<img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:uf_mostrar_ocultar_obra();">Datos de la Obra</a>
				   </div></td>
        		<td height="13" class="sin-borde">&nbsp;</td>
      			</tr>
			<?Php
			}
			else
			{
			?>				
				<tr class="formato-blanco">
        		<td height="13" class="sin-borde">&nbsp;</td>
       			 <td height="13" colspan="8" align="center" valign="top" class="sin-borde"><div align="right"><a href="javascript:ue_uf_mostrar_ocultar_obra();">&nbsp;&nbsp;<img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:uf_mostrar_ocultar_obra();">Datos de la Obra</a>
				   </div></td>
        		<td height="13" class="sin-borde">&nbsp;</td>
      			</tr>
			<?Php
			}
			?>		
      
	  		<?Php
				if ($ls_datosobra == "MOSTRAR")
				{					
			 ?>
  
				  <tr class="formato-blanco">
					<td height="135" class="sin-borde">&nbsp;</td>
					<td height="135" colspan="8" align="center" valign="top" class="sin-borde">					<table width="500" height="110" border="0" cellpadding="0" cellspacing="0" id="detalleasignacion">
                      <tr class="formato-blanco">
                        <td width="82" height="18"><div align="right">C&oacute;digo</div></td>
                        <td width="182"><input name="txtcodobr" id="txtcodobr" value="<?php print $ls_codobr?>" readonly="true"  style="text-align:center "  type="text" size="6" ></td>
                        <td width="66"><div align="right"></div></td>
                        <td width="170">&nbsp;</td>
                      </tr>
                      <tr class="formato-blanco">
                        <td height="18"><div align="right">Descripci&oacute;n</div></td>
                        <td height="18" colspan="3"><input name="txtdesobr" type="text" id="txtdesobr" value="<?php print $ls_desobr?>" size="70" readonly="true"></td>
                      </tr>
                      <tr class="formato-blanco">
                        <td height="18"><div align="right">Estado</div></td>
                        <td height="18"><input name="txtestobr" id="txtestobr3"   value="<?php print $ls_estobr?>" readonly="true" type="text" size="20" maxlength="50" ></td>
                        <td height="18"><div align="right">Municipio</div></td>
                        <td height="18"><input name="txtmunobr" id="txtmunobr" value="<?php print $ls_munobr?>"  readonly="true"  type="text" size="20" ></td>
                      </tr>
                      <tr class="formato-blanco">
                        <td height="18" valign="top"><div align="right">Parroquia</div></td>
                        <td height="18" valign="top"><input name="txtparobr" type="text" id="txtparobr"   value="<?php print $ls_parobr?>" readonly="true"  size="20" maxlength="50" ></td>
                        <td height="18" valign="top"><div align="right">Comunidad</div></td>
                        <td height="18" valign="top"><input name="txtcomobr" id="txtcomobr"  value="<?php print $ls_comobr?>" readonly="true"  type="text" size="20" ></td>
                      </tr>
                      <tr class="formato-blanco">
                        <td height="18" valign="top"><div align="right">Direcci&oacute;n</div></td>
                        <td height="18" colspan="3" valign="top"><input name="txtdirobr" id="txtdirobr"   value="<?php print $ls_dirobr?>" readonly="true" type="text" size="70"></td>
                      </tr>
                    </table></td>
					<td height="135" class="sin-borde">&nbsp;</td>
				  </tr>
				 <?Php
				 }
				 else
				 {
				 ?>
				 	<tr class="formato-blanco">
					<td height="19" class="sin-borde">&nbsp;</td>
					<td height="19" colspan="8" align="center" valign="top" class="sin-borde">
					</td>
					<td height="19" class="sin-borde">&nbsp;</td>
				  	</tr>				 
				 <?Php
				 	}
				 ?> 
				 
		 
		 
	  <tr class="formato-blanco">
        <td height="13" colspan="10" class="titulo-celdanew">Datos del Acta de Paralización </td>
      </tr>	  
      <tr class="formato-blanco">
        <td height="39">&nbsp;</td>
        <td height="39">C&oacute;digo</td>
        <td height="39"><input name="txtcodact" id="txtcodact" style="text-align:center " value="<?php print $ls_codact?>" readonly="true" type="text" size="6" maxlength="6">        </td>
        <td height="39"><div align="right"></div></td>
        <td height="39" colspan="3">&nbsp;</td>
        <td width="108" height="39"><div align="right">Fecha</div></td>
        <td width="85" height="39"><input name="txtfecact" type="text" id="txtfecact"  style="text-align:center" value="<?php print $ls_fecact ?>" size="10" maxlength="10"  readonly="true"></td>
        <td height="39">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="26">&nbsp;</td>
        <td height="26">&nbsp;</td>
        <td height="26"><div align="right">Fecha de Suspensi&oacute;n</div></td>
        <td height="26"><input name="txtfecfinact"   type="text" id="txtfecfinact"  style="text-align:left" value="<?php print $ls_fecfinact ?>" size="11" maxlength="10"   onBlur="javascript:ue_validafechacreacion();" readonly="true" datepicker="true"></td>
        <td height="26" colspan="3"><div align="right">Fecha de Reinicio Estimada</div></td>
        <td height="26"><input name="txtfeciniact"   type="text" id="txtfeciniact"  style="text-align:left" value="<?php print $ls_feciniact ?>" size="11" maxlength="10"   onBlur="javascript:ue_validafechacreacion();" readonly="true" datepicker="true"></td>
        <td height="26">&nbsp;</td>
        <td height="26">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="25">&nbsp;</td>
        <td height="25">&nbsp;</td>
        <td height="25"><div align="right">Ing. Inspector</div></td>
        <td height="25" colspan="6"><input name="txtnominsact" type="text"  style="text-align:right" id="txtmonto2" readonly="true" size="50" value="<?php print $ls_nominsact?>" maxlength="50"> 
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;C&eacute;dula
        <input name="txtcedinsact" type="text" id="txtcedinsact" value="<?php print $ls_cedinsact?>" size="10" maxlength="10">      
          <div align="left"></div>        </td>
        <td height="25">&nbsp;</td>
      </tr>
     
      <tr class="formato-blanco">
        <td height="28">&nbsp;</td>
        <td height="28">&nbsp;</td>
        <td height="28"><div align="right">Ing. Supervisor</div></td>
        <td height="28" colspan="6"><input name="txtnomsupact" type="text" id="txtnomsupact" value="<?php print $ls_nomsupact?> " size="50" maxlength="50">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;C&eacute;dula
        <input name="txtcedsupact" type="text" id="txtcedsupact" value="<?php print $ls_cedsupact?>" size="10" maxlength="10"></td>
        <td height="28">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="24">&nbsp;</td>
        <td height="24">&nbsp;</td>
        <td height="24"><div align="right">Ing. Residente</div></td>
        <td height="24" colspan="6"><input name="txtresact" type="text" id="txtresact" value="<?php print $ls_resact?>" size="50" maxlength="50">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;C&eacute;dula
        <input name="txtcedresact" type="text" id="txtcedresact" value="<?php print $ls_cedresact?>" size="10" maxlength="10"></td>
        <td height="24">&nbsp;</td>
      </tr>	
	  
      
      <tr class="formato-blanco">
        <td height="37">&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right">Motivo Suspensi&oacute;n</div></td>
        <td colspan="6"><textarea name="txtmotact" cols="47" rows="1" wrap="VIRTUAL" id="txtmotact"><?php print $ls_motact;?></textarea></td>
        <td>&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="10">&nbsp;</td>
      </tr>
    </table>
  <!-- Los Hidden son colocados a partir de aca-->
<input name="hiddatoscontrato" type="hidden" id="hiddatoscontrato" value="<?php print $ls_datoscontrato;?>">
<input name="hiddatosobra" type="hidden" id="hiddatosobra" value="<?php print $ls_datosobra;?>">
<!-- Fin de la declaracion de Hidden-->
  </form>
</body>
<script language="javascript">


///////Funciones para llamar catalogos////////////////
function ue_catcontrato()
{
	f=document.form1;
	f.operacion.value="";			
	var estado = 5;
	pagina="sigesp_cat_contrato.php?estado="+estado;
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=500,resizable=yes,location=no");
}

///////Fin de las Funciones para para llamar catalogos/////

//////Funciones para cargar datos provenientes de catalogos///////

function ue_cargarcontrato(ls_codigo,ls_desobr,ls_estado,ls_codest,ld_monto,ls_placon,ls_placonuni,ls_mulcon,ls_tiemulcon,ls_mulreuni,ls_lapgarcon,ls_lapgaruni,
						ls_codtco,ls_monmaxcon,ls_pormaxcon,ls_obscon,ls_porejefiscon,ls_porejefincon,ls_monejefincon,ls_codasi,ls_feccrecon,
						ls_fecinicon,ls_nomtco)
{
	f=document.form1;
	f.txtcodcon.value=ls_codigo;
	f.operacion.value="";	
}

/*

function ue_validafechacreacion()
{
	f=document.form1;
	li_fechacreacion=Date.parse(f.txtfeccon.value);
	li_fechainicio=Date.parse(f.txtfecinicon.value);
	if (li_fechainicio < li_fechacreacion)
	{
		alert ("La fecha de inicio debe ser mayor a la fecha actual!!!");
		f.txtfecinicon.value="";
	}	
}

function validarangofechas()
{
	f=document.form1;
	lb_valido=true;
	li_fechainicio=Date.parse(f.txtfeciniobr.value);
	li_fechafin=Date.parse(f.txtfecfinobr.value);
	if (li_fechainicio>=li_fechafin)
	{
		alert ("La fecha de inicio debe ser menor a la fecha de fin!!!");
		f.txtfecfinobr.value="";
		lb_valido=false;
	}
	return lb_valido;
}

function currencyDate(date)
  { 
	ls_date=date.value;
	li_long=ls_date.length;
	f=document.form1;
			 
		if(li_long==2)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(0,2);
			li_string=parseInt(ls_string,10);

			if((li_string>=1)&&(li_string<=31))
			{
				date.value=ls_date;
			}
			else
			{
				date.value="";
			}
			
		}
		if(li_long==5)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(3,2);
			li_string=parseInt(ls_string,10);
			if((li_string>=1)&&(li_string<=12))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,3);
			}
		}
		if(li_long==10)
		{
			ls_string=ls_date.substr(6,4);
			li_string=parseInt(ls_string,10);
			if((li_string>=1900)&&(li_string<=2090))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,6);
			}
		}

   }*/
function ue_valida_null(field,mensaje)
{
  with (field) 
  {
    if (value==null||value==""||value=="s1")
      {
        alert(mensaje);
        return false;
      }
    else
      {
   	    return true;
      }
  }
}	

/*
function ue_validacaracter(cadena, obj)
{ 
   opc = false; 
   if (cadena == "%d")//toma solo caracteres  
   if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32))  
   opc = true; 

   if (cadena == "%e")//toma el @, el punto y caracteres. Para Email
   if ((event.keyCode > 63 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32)||(event.keyCode ==46)||(event.keyCode ==95)||(event.keyCode > 47 && event.keyCode < 58))  
   opc = true;    

   if (cadena == "%f")//Toma solo numeros
   { 
     if (event.keyCode > 47 && event.keyCode < 58) 
     opc = true; 
     if (obj.value.search("[,*]") == -1 && obj.value.length != 0) 
     if (event.keyCode == 44) 
     opc = true; 
   } 
   
   if (cadena == "%s") // toma numero y letras
   if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32)||(event.keyCode > 47 && event.keyCode < 58)||(event.keyCode ==46)||(event.keyCode ==47)||(event.keyCode ==35)||(event.keyCode ==45)) 
   opc = true; 
   
   if (cadena == "%c") // toma numero, punto y guion. Para telefonos
   if ((event.keyCode > 47 && event.keyCode < 58)|| (event.keyCode > 44 && event.keyCode < 47))
   opc = true; 
   
   if(opc == false) 
   event.returnValue = false;
}*/
//////////////////////////////Fin de las funciones de validacion
function ue_nuevo()
{
  f=document.form1;
  if (ue_valida_null(f.txtcodcon,"Debe seleccionar un Contrato!!!")==true)
  {
	  f.operacion.value="ue_nuevo";
	  f.action="sigesp_sob_d_actaparalizacion.php";
	  f.submit();
  }	
}
/*function ue_buscar()
{
	f=document.form1;
	pagina="sigesp_cat_contrato.php";
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=350,resizable=yes,location=no,status=no");
} 

function ue_eliminar()
		{
		var lb_borrar="";		
		f=document.form1;
		if (f.txtcodobr.value=="")
		   {
		     alert("No ha seleccionado ningún registro para eliminar !!!");
		   }
			else
			{
			    borrar=confirm("¿ Esta seguro de eliminar este registro ?");
				if (borrar==true)
				   { 
					 f=document.form1;
					 f.operacion.value="ue_eliminar";
					 f.action="sigesp_sob_d_obra.php";
					 f.submit();
				   }
			}	   
		}
	
function currencyFormat(fld, milSep, decSep, e) { 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
    if (whichCode == 13) return true; // Enter 
    if (whichCode == 8) return true; // Backspace <-
    if (whichCode == 127) return true; // Suprimir -Del 
    key = String.fromCharCode(whichCode); // Get key value from key code 
    if (strCheck.indexOf(key) == -1) return false; // Not a valid key 
    len = fld.value.length; 
    for(i = 0; i < len; i++) 
     if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break; 
    aux = ''; 
    for(; i < len; i++) 
     if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
    aux += key; 
    len = aux.length; 
    if (len == 0) fld.value = ''; 
    if (len == 1) fld.value = '0'+ decSep + '0' + aux; 
    if (len == 2) fld.value = '0'+ decSep + aux; 
    if (len > 2) { 
     aux2 = ''; 
     for (j = 0, i = len - 3; i >= 0; i--) { 
      if (j == 3) { 
       aux2 += milSep; 
       j = 0; 
      } 
      aux2 += aux.charAt(i); 
      j++; 
     } 
     fld.value = ''; 
     len2 = aux2.length; 
     for (i = len2 - 1; i >= 0; i--) 
      fld.value += aux2.charAt(i); 
     fld.value += decSep + aux.substr(len - 2, len); 
    } 
	//if (fld.id != "txtmonto")
    	//validamonto(fld,500);
    return false; 
   }

  */ 
function uf_mostrar_ocultar_obra()  
{
	f=document.form1;
	if (f.txtcodcon.value=="")
	{
		alert("Debe seleccionar un Contrato!!");
	}
	else
	{
		if (f.hiddatosobra.value == "OCULTAR")
		{
			f.hiddatosobra.value = "MOSTRAR";
			f.operacion.value="ue_cargarcontrato";
			
		}
		else
		{
			f.hiddatosobra.value = "OCULTAR";
			f.operacion.value="";
		}
		f.submit();
	}
}

function uf_mostrar_ocultar_contrato()  
{
	f=document.form1;
	if (f.hiddatoscontrato.value == "OCULTAR")
	{
		f.hiddatoscontrato.value = "MOSTRAR";
		f.operacion.value="ue_cargarcontrato";
	}
	else
	{
		f.hiddatoscontrato.value = "OCULTAR";	
	}
	f.submit();
}
/*
function ue_validarnumero(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if((texto=="0")||(texto=="1")||(texto=="2")||(texto=="3")||(texto=="4")||(texto=="5")||(texto=="6")||(texto=="7")||(texto=="8")||(texto=="9"))
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}

*/
 
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>