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
<title>Elaboraci&oacute;n de Acta de Inicio</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
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
require_once("class_folder/sigesp_sob_c_acta.php");
$io_acta=new sigesp_sob_c_acta();
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
require_once("class_folder/sigesp_sob_class_mensajes.php");
$io_mensaje=new sigesp_sob_class_mensajes();

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
	$ls_control="";
	$ls_estobr="";
	$ls_munobr="";
	$ls_comobr="";
	$ls_parobr="";
	$ls_dirobr="";
	$ls_codcon="";
	$ls_codact="";
	$ls_fecact="";
	$ls_obsact="";
	$ls_feciniact="";
	$ls_fecfinact="";
	$ls_nominsact="";
	$ls_cedinsact="";
	$ls_nomsupact="";
	$ls_estact="";
	$ls_cedsupact="";
	$ls_nomresact="";
	$ls_codproins="";
	$ls_codpro="";
	$ls_cedresact="";
	$ls_civresact="";
	$ls_civsup="";
	$ls_civinsact="";
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

if	(array_key_exists("txtobsact",$_POST)){	$ls_obsact=$_POST["txtobsact"]; }
else{$ls_obsact="";}

if	(array_key_exists("txtfecinicon",$_POST)){$ls_fecinicon=$_POST["txtfecinicon"]; }
else{$ls_fecinicon="";}

if	(array_key_exists("hidplacon",$_POST)){$ls_placon=$_POST["hidplacon"]; }
else{$ls_placon="0";}

if	(array_key_exists("hidplaconuni",$_POST)){$ls_placonuni=$_POST["hidplaconuni"]; }
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

if	(array_key_exists("txtnomresact",$_POST)){$ls_nomresact=$_POST["txtnomresact"]; }
else{$ls_nomresact="";}

if	(array_key_exists("txtcedresact",$_POST)){$ls_cedresact=$_POST["txtcedresact"]; }
else{$ls_cedresact="";}

if	(array_key_exists("txtcivresact",$_POST)){$ls_civresact=$_POST["txtcivresact"]; }
else{$ls_civresact="";}

if	(array_key_exists("txtcivinsact",$_POST)){$ls_civinsact=$_POST["txtcivinsact"]; }
else{$ls_civinsact="";}	

if	(array_key_exists("hidcodproins",$_POST)){$ls_codproins=$_POST["hidcodproins"]; }
else{$ls_codproins="";}	

if	(array_key_exists("hidcodpro",$_POST)){$ls_codpro=$_POST["hidcodpro"]; }
else{$ls_codpro="";}	

if	(array_key_exists("txtestact",$_POST)){$ls_estact=$_POST["txtestact"]; }
else{$ls_estact="";}

if	(array_key_exists("hidcontrol",$_POST)){$ls_control=$_POST["hidcontrol"]; }
else{$ls_control="";}	
////////////////////////////////Operaciones de Actualizacion//////////////////////////////////////

if($ls_operacion=="ue_nuevo")//Abre una ficha de obra nueva
{
	require_once("../shared/class_folder/class_funciones_db.php");
	require_once ("../shared/class_folder/sigesp_include.php");		
	$io_include=new sigesp_include();
	$io_connect=$io_include->uf_conectar();
	$io_funcdb=new class_funciones_db($io_connect);
	$la_empresa=$_SESSION["la_empresa"];
	$lb_tieneacta=$io_acta->uf_revisar_contrato_acta($ls_codcon,1);
	if(!$lb_tieneacta)
	{
		$ls_codact=$io_funsob->uf_generar_codigoacta(1,$ls_codcon);	
		$ls_fecinicon="";	
		$ls_feciniact="";
		$ls_fecfinact="";
		$ls_nominsact="";
		$ls_cedinsact="";
		$ls_nomsupact="";
		$ls_cedsupact="";
		$ls_nomresact="";
		$ls_cedresact="";
		$ls_civinsact="";
		$ls_obsact="";
		$ls_civsup="";
		$ls_control="";
		$ls_civresact="";
		$ls_estact="EMITIDO";
		$fecha=date("d/m/Y");
		$ls_fecact=$fecha;	
	}
	else
	{
		$io_msg->message("Este Contrato ya tiene un Acta de Inicio!!!");
		$ls_datoscontrato="OCULTAR";
		$ls_datosobra="OCULTAR";
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
		$ls_obsact="";
		$ls_estact="";
		$ls_parobr="";
		$ls_dirobr="";
		$ls_codcon="";
		$ls_codact="";
		$ls_fecact="";
		$ls_feciniact="";
		$ls_fecfinact="";
		$ls_nominsact="";
		$ls_cedinsact="";
		$ls_control="";
		$ls_nomsupact="";
		$ls_cedsupact="";
		$ls_nomresact="";
		$ls_codproins="";
		$ls_codpro="";
		$ls_cedresact="";
		$ls_civresact="";
		$ls_civsup="";
		$ls_civinsact="";
	}
	
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
elseif($ls_operacion=="ue_guardar")
{
	
	$ls_fecact=$io_function->uf_convertirdatetobd($ls_fecact);
	$ls_feciniact=$io_function->uf_convertirdatetobd($ls_feciniact);
	$ls_fecfinact=$io_function->uf_convertirdatetobd($ls_fecfinact);
	$li_numero=0;
	$lb_existe=$io_acta->uf_select_acta($ls_codcon,$ls_codact,1,&$aa_data);
	if(!$lb_existe)
	{
		$lb_valido=$io_acta->uf_guardar_acta($ls_codcon,$ls_codact,1,$ls_fecact,$ls_feciniact,$ls_fecfinact,"",$li_numero,"001","","",$ls_cedinsact,$ls_cedresact,"","",$ls_obsact);
			if ($lb_valido)																																				
			{
				$lb_valido=$io_contrato->uf_update_ultimoacta($ls_codcon,1);
				if($lb_valido)
				{
					$io_mensaje->incluir();
					$ls_datoscontrato="OCULTAR";
					$ls_datosobra="OCULTAR";
					$ls_placon="";
					$ls_placonuni="";
					$ls_contasi="";
					$ls_contasi="";
					$ls_moncon="";
					$ls_estcon="";
					$ls_codobr="";
					$ls_desobr="";
					$ls_estobr="";
					$ls_obsact="";
					$ls_estobr="";
					$ls_munobr="";
					$ls_comobr="";
					$ls_estact="";
					$ls_parobr="";
					$ls_dirobr="";
					$ls_control="";
					$ls_codcon="";
					$ls_codact="";
					$ls_fecact="";
					$ls_feciniact="";
					$ls_fecfinact="";
					$ls_nominsact="";
					$ls_cedinsact="";
					$ls_nomsupact="";
					$ls_cedsupact="";
					$ls_nomresact="";
					$ls_codproins="";
					$ls_codpro="";
					$ls_cedresact="";
					$ls_civresact="";
					$ls_civsup="";
					$ls_civinsact="";
				}
				else
				{
					$io_msg->message("Error actualizando ultimo acta del contrato");
				}					
			}
			else
			{
				$io_mensaje->error_incluir();
			}
		}/*************************************End del if si no existe (Guardar)*************************/
		else
		{
			$lb_valido=$io_acta->uf_select_estado($ls_codcon,$ls_codact,1,$li_estado);
			if($li_estado==1)
			{	
				$lb_valido=$io_acta->uf_update_acta($ls_codcon,$ls_codact,1,$ls_fecact,$ls_feciniact,$ls_fecfinact,"",$li_numero,"001","","",$ls_cedinsact,$ls_cedresact,"","",$ls_obsact);
				if($lb_valido)
				{					
					$ls_datoscontrato="OCULTAR";
					$ls_datosobra="OCULTAR";
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
					$ls_estact="";
					$ls_obsact="";
					$ls_parobr="";
					$ls_dirobr="";
					$ls_codcon="";
					$ls_codact="";
					$ls_fecact="";
					$ls_feciniact="";
					$ls_fecfinact="";
					$ls_nominsact="";
					$ls_control="";
					$ls_cedinsact="";
					$ls_nomsupact="";
					$ls_cedsupact="";
					$ls_nomresact="";
					$ls_codproins="";
					$ls_codpro="";
					$ls_cedresact="";
					$ls_civresact="";
					$ls_civsup="";
					$ls_civinsact="";
					if($lb_valido===true)
						$io_mensaje->modificar();
				}
				else
				{
					$io_mensaje->error_modificar();
				}
				
			}
			else
			{
				$ls_estado=$io_funsob->uf_convertir_numeroestado($li_estado);
				$io_msg->message("El Acta no puede ser modificada, su estado es $ls_estado");
			}
		
		}
}
elseif($ls_operacion=="ue_eliminar")
{
	$lb_valido=$io_acta->uf_select_estado($ls_codcon,$ls_codact,1,$li_estado);
	if($lb_valido)
	{
		if($li_estado==1)
		{
			$lb_valido=$io_acta->uf_update_estado($ls_codcon,$ls_codact,1,3);
			if($lb_valido)
				$io_mensaje->anular();
			else
				$io_mensaje->error_anular();
			$ls_datoscontrato="OCULTAR";
					$ls_datosobra="OCULTAR";
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
					$ls_estact="";
					$ls_parobr="";
					$ls_dirobr="";
					$ls_codcon="";
					$ls_codact="";
					$ls_fecact="";
					$ls_feciniact="";
					$ls_fecfinact="";
					$ls_control="";
					$ls_nominsact="";
					$ls_cedinsact="";
					$ls_obsact="";					
					$ls_nomsupact="";
					$ls_cedsupact="";
					$ls_nomresact="";
					$ls_codproins="";
					$ls_codpro="";
					$ls_cedresact="";
					$ls_civresact="";
					$ls_civsup="";
					$ls_civinsact="";	
		}
		else
		{
			$ls_estado=$io_funsob->uf_convertir_numeroestado($li_estado);
			$io_msg->message("El Acta no puede ser anulada, su estado es $ls_estado");
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
  <table width="685" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
      <tr class="formato-blanco">
        <td>&nbsp;</td>
        <td colspan="8" class="titulo-celdanew">Datos del Contrato </td>
        <td>&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td width="82">&nbsp;</td>
        <td width="187">&nbsp;</td>
        <td colspan="4">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td width="16" height="22"><div align="right"></div></td>
        <td width="37"><div align="right">C&oacute;digo</div></td>
        <td colspan="2"><input name="txtcodcon" type="text" id="txtcodcon" style="text-align:center " value="<?php print $ls_codcon ?>" size="8" maxlength="8" readonly="true">
        <input name="operacion" type="hidden" id="operacion">
        <?Php
		if($ls_control=="")
		{
		?>
		<a href="javascript:ue_catcontrato();" >
			<img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" >
		</a>
		<?
		}
		else
		{
		?>
			<img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" onClick="javascript:mensaje();">		
		<?
		}
		?>
		
		 </td>
        <td width="114">&nbsp;</td>
        <td colspan="4"><div align="right"><a href="javascript:uf_mostrar_ocultar_contrato();">&nbsp;&nbsp;<img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:uf_mostrar_ocultar_contrato();">Datos del Contrato </a></div></td>
        <td width="15">&nbsp;</td>
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
				  <td height="79" colspan="8" align="center" valign="top" class="sin-borde">				  <table width="480" height="111" border="0" cellpadding="0"  cellspacing="0" >
                    <tr class="letras-pequeñas">
                      <td width="126" height="13"><div align="right">Fecha de Inicio</div></td>
                      <td width="96"><input name="txtfecinicon"  stype="text" id="fecinicon"  style="text-align:center "value="<?php print $ls_fecinicon?>" size="11" maxlength="11" readonly="true"></td>
                      <td width="60"><div align="right">Duraci&oacute;n</div></td>
                      <td width="198"><input name="txtplacon"  style="text-align:center "  type="text" id="txtplacon" value="<?php print $ls_placon?> <?php print $ls_placonuni?>" size="11" maxlength="11" readonly="true">
&nbsp;&nbsp;&nbsp;
                      </td>
                    </tr>
                    <tr class="letras-pequeñas">
                      <td height="22"> <div align="right">Contratista</div></td>
                      <td height="22" colspan="3">
                      <input name="txtcontasi" type="text" id="txtcontasi" value="<?php print $ls_contasi?>" size="70" maxlength="254" readonly="true"></td>
                    </tr>
                    <tr class="letras-pequeñas">
                      <td height="19" valign="top" class="navigation"><div align="right">Monto</div></td>
                      <td height="19" colspan="3" valign="top"><input name="txtmoncon" type="text"  readonly="true" id="txtmoncon"  style="text-align: right "value="<?php print $ls_moncon?>" size="21" maxlength="21"></td>
                    </tr>
                    <tr class="letras-pequeñas">
                      <td height="19" valign="top" class="navigation"><div align="right">Estado Actual</div></td>
                      <td height="19" colspan="3" valign="top"><input name="txtestcon" type="text" id="txtestcon" value="<?php print $ls_estcon?>" size="21" maxlength="30" readonly="true" style="text-align:center "></td>
                    </tr>
                    <tr class="letras-pequeñas">
                      <td height="19" valign="top" class="navigation"><div align="right">C&oacute;digo de la Obra</div></td>
                      <td height="19" colspan="3" valign="top"><input name="txtcodobr" id="txtcodobr2" value="<?php print $ls_codobr?>" readonly="true"  style="text-align:center "  type="text" size="6" ></td>
                    </tr>
                    <tr class="letras-pequeñas">
                      <td height="19" valign="top" class="navigation"><div align="right">Descripci&oacute;n</div></td>
                      <td height="19" colspan="3" valign="top"><input name="txtdesobr" type="text" id="txtdesobr2" value="<?php print $ls_desobr?>" size="70" readonly="true"></td>
                    </tr>
                  </table></td>
				  <td height="79" class="sin-borde">&nbsp;</td>
    			</tr>
			<?Php
			}
			else
			{
			?>
			<?Php
			}
			?>		
      
	  		<?Php
				if ($ls_datosobra == "MOSTRAR")
				{					
			 ?>
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
        <td height="13" colspan="10" class="titulo-celdanew">Datos del Acta de Inicio </td>
      </tr>	  
      <tr class="formato-blanco">
        <td height="39">&nbsp;</td>
        <td height="39"><div align="right">C&oacute;digo</div></td>
        <td height="39"><input name="txtcodact" id="txtcodact" style="text-align:center " value="<?php print $ls_codact?>" readonly="true" type="text" size="6" maxlength="6">        </td>
        <td height="39"><div align="left">Estado
          <input name="txtestact" type="text" class="celdas-grises" id="txtestact" value="<?php print $ls_estact;?>" size="20" maxlength="20" style="text-align:center ">
        </div></td>
        <td height="39" colspan="3">&nbsp;</td>
        <td width="106" height="39"><div align="right">Fecha:</div></td>
        <td width="122" height="39"><input name="txtfecact" type="text" id="txtfecact"  style="text-align:center" value="<?php print $ls_fecact ?>" size="10" maxlength="10"  readonly="true"></td>
        <td height="39">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="26">&nbsp;</td>
        <td height="26">&nbsp;</td>
        <td height="26"><div align="right">Fecha de Inicio</div></td>
        <td height="26"><input name="txtfeciniact"   type="text" id="txtfeciniact" onBlur="javascript:ue_comparar_intervalo('txtfecact','txtfeciniact','La fecha de inicio del Acta debe ser mayor o igual a la fecha actual');"    style="text-align:left" value="<?php print $ls_feciniact ?>" size="11" maxlength="10"    readonly="true" datepicker="true"></td>
        <td height="26" colspan="3"><div align="right">Fecha de Finalizaci&oacute;n</div></td>
        <td height="26"><input name="txtfecfinact"   type="text" id="txtfecfinact"  style="text-align:left" value="<?php print $ls_fecfinact ?>" size="11" maxlength="10"   readonly="true" datepicker="true"></td>
        <td height="26">&nbsp;</td>
        <td height="26">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="25">&nbsp;</td>
        <td height="25">&nbsp;</td>
        <td height="25"><div align="right">Ing. Inspector</div></td>
        <td height="25" colspan="6"><input name="txtnominsact" type="text"  style="text-align: left" id="txtnominsact" readonly="true" size="50" value="<?php print $ls_nominsact?>" maxlength="50">          &nbsp;&nbsp;&nbsp;&nbsp;C.I.
        <input name="txtcedinsact" type="text" id="txtcedinsact" onKeyPress="return acceptNum(event)" value="<?php print $ls_cedinsact?>" size="10" readonly="true" maxlength="10" style="text-align:center ">
        &nbsp;
        C.I.V.        
        <input name="txtcivinsact" type="text" id="txtcivinsact" onKeyPress="return acceptNum(event)" value="<?php print $ls_civinsact;?>" size="10" style="text-align:center " readonly="true" maxlength="10"> 
        <a href="javascript:ue_catinspectores();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"  ></a>          <div align="left"></div>        </td><td height="25">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="24">&nbsp;</td>
        <td height="24">&nbsp;</td>
        <td height="24"><div align="right">Ing. Residente</div></td>
        <td height="24" colspan="6"><input name="txtnomresact" type="text" id="txtnomresact" value="<?php print $ls_nomresact?>" size="50" readonly="true" maxlength="50" style="text-align:left ">
        &nbsp;&nbsp;&nbsp;&nbsp;C.I.
        <input name="txtcedresact" type="text" id="txtcedresact" onKeyPress="return acceptNum(event)" value="<?php print $ls_cedresact?>" size="10" maxlength="10" style="text-align:center " readonly="true">
        &nbsp;&nbsp;C.I.V.
        <input name="txtcivresact" type="text" onKeyPress="return acceptNum(event)"  style="text-align:center " value="<?php print $ls_civresact;?>" readonly="true " size="10" maxlength="10">
        <a href="javascript:ue_catresidentes();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a></td>
        <td height="24">&nbsp;</td>
      </tr>	
	  
      
      <tr class="formato-blanco">
        <td height="39">&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right">Observaci&oacute;n</div></td>
        <td colspan="6"><textarea name="txtobsact" cols="80" rows="2" id="txtobsact" onKeyDown="textCounter(this,254)" onKeyUp="textCounter(this,254)"><?php print $ls_obsact;?></textarea></td>
        <td>&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="10">&nbsp;</td>
      </tr>
    </table>
  <!-- Los Hidden son colocados a partir de aca-->
<input name="hiddatoscontrato" type="hidden" id="hiddatoscontrato" value="<?php print $ls_datoscontrato;?>">
<input name="hiddatosobra" type="hidden" id="hiddatosobra" value="<?php print $ls_datosobra;?>">
<input name="hidcodproins" type="hidden" id="hidcodproins" value="<?php print $ls_codproins;?>">
<input name="hidcodpro" type="hidden" id="hidcodpro" value="<?php print $ls_codpro;?>">
<input name="hidplacon" type="hidden" id="hidplacon" value="<?php print $ls_placon;?>">
<input name="hidplaconuni" type="hidden" id="hidplaconuni" value="<?php print $ls_placonuni;?>">
<input name="hidcontrol" type="hidden" id="hidcontrol" value="<?php print $ls_control?>">


<!-- Fin de la declaracion de Hidden-->
  </form>
</body>
<script language="javascript">


///////Funciones para llamar catalogos////////////////
function ue_catcontrato()
{
	f=document.form1;
	f.operacion.value="";			
	var tipoacta = "INICIO";
	pagina="sigesp_cat_contratoactas.php?tipoacta="+tipoacta;
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=500,resizable=yes,location=no");
}

function ue_catinspectores()
{
	f=document.form1;
	if(f.txtfeciniact.value=="" || f.txtfecfinact.value=="")
	{
		f.operacion.value="";			
		var codpro = f.hidcodproins.value;
		var tipocatalogo="INSPECTOR";
		pagina="sigesp_cat_inspectores.php?codpro="+codpro+"&tipocatalogo="+tipocatalogo;
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=200,resizable=yes,location=no");
	}
	else
	{
		if(ue_comparar_intervalo('txtfeciniact','txtfecfinact','La fecha de inicio del Acta debe ser mayor o igual a la fecha de finalización'))
		{
			
			f.operacion.value="";			
			var codpro = f.hidcodproins.value;
			var tipocatalogo="INSPECTOR";
			pagina="sigesp_cat_inspectores.php?codpro="+codpro+"&tipocatalogo="+tipocatalogo;
			window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=200,resizable=yes,location=no");
		}
	}
	
}

function ue_catresidentes()
{
	f=document.form1;
	f=document.form1;
	if(f.txtfeciniact.value=="" || f.txtfecfinact.value=="")
	{
		f.operacion.value="";			
		var codpro = f.hidcodpro.value;
		var tipocatalogo="RESIDENTE";
		pagina="sigesp_cat_inspectores.php?codpro="+codpro+"&tipocatalogo="+tipocatalogo;
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=200,resizable=yes,location=no");
	}
	else
	{
		if(ue_comparar_intervalo('txtfeciniact','txtfecfinact','La fecha de inicio del Acta debe ser mayor o igual a la fecha de finalización'))
		{
			
			f.operacion.value="";			
			var codpro = f.hidcodpro.value;
			var tipocatalogo="RESIDENTE";
			pagina="sigesp_cat_inspectores.php?codpro="+codpro+"&tipocatalogo="+tipocatalogo;
			window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=200,resizable=yes,location=no");
		}
	}	
}

///////Fin de las Funciones para para llamar catalogos/////

//////Funciones para cargar datos provenientes de catalogos///////

function ue_cargarcontrato(ls_codigo,ls_desobr,ls_estado,ls_codest,ls_codasi,ls_feccrecon,ls_fecinicon,ls_codobr,ls_codpro,ls_codproins)
{
	f=document.form1;
	f.txtcodcon.value=ls_codigo;
	f.hidcodproins.value=ls_codproins;
	f.hidcodpro.value=ls_codpro;
	f.operacion.value="";	
}

function ue_cargarinspector(ls_codpro,ls_nomsup,ls_cedsup,ls_civ)
{
	f=document.form1;
	f.txtnominsact.value=ls_nomsup;
	f.txtcedinsact.value=ls_cedsup;
	f.txtcivinsact.value=ls_civ;	
}

function ue_cargarresidente(ls_codpro,ls_nomsup,ls_cedsup,ls_civ)
{
	f=document.form1;
	f.txtnomresact.value=ls_nomsup;
	f.txtcedresact.value=ls_cedsup;
	f.txtcivresact.value=ls_civ;	
}

function ue_cargaracta(ls_codact,ls_codcon,ls_desobr,ls_estact,ls_fecact,ls_feciniact,ls_fecfinact,ls_cedinsact,ls_cedresact,ls_nominsact,ls_civinsact,ls_nomresact,ls_civresact,ls_codpro,ls_codproins,ls_obsact)
{
	f=document.form1;
	f.txtcodact.value=ls_codact;
	f.txtcodcon.value=ls_codcon;
	f.txtfecact.value=ls_fecact;
	f.txtfeciniact.value=ls_feciniact;
	f.txtfecfinact.value=ls_fecfinact;
	f.txtcedinsact.value=ls_cedinsact;
	f.txtcedresact.value=ls_cedresact;
	f.txtnominsact.value=ls_nominsact;
	f.txtcivinsact.value=ls_civinsact;
	f.txtnomresact.value=ls_nomresact;
	f.txtcivresact.value=ls_civresact;
	f.operacion.value="";
	f.txtestact.value=ls_estact;
	f.hidcodpro.value=ls_codpro;
	f.hidcodproins.value=ls_codproins;
	f.txtobsact.value=ls_obsact;
	f.hidcontrol.value="x";
	f.submit();
	
}

//////////////////////////////Fin de las funciones de validacion//////////////
function ue_nuevo()
{
  f=document.form1;
  if(f.txtcodcon.value=="")
  	alert("Debe seleccionar un Contrato!!!")
 else
 {
	  f.operacion.value="ue_nuevo";
	  f.action="sigesp_sob_d_actainicio.php";
	  f.submit();
 } 
}

function ue_guardar()
{
	f=document.form1;	
	if(ue_comparar_intervalo('txtfeciniact','txtfecfinact','La fecha de inicio del Acta debe ser mayor o igual a la fecha de finalización'))
	{
		lb_valido=true;
		var la_objetos=new Array ("txtcodcon","txtcodact","txtfeciniact","txtfecfinact","txtnominsact","txtnomresact");
		var la_mensajes=new Array ("Código del Contrato","Código del Acta","Fecha de Inicio","Fecha de Finalización","Ing. Inspector","Ing. Residente");
		for (li_i=0;li_i<6;li_i++)
		{
			if(ue_valida_null(eval("f."+la_objetos[li_i]),la_mensajes[li_i])==false)
			{
				eval("f."+la_objetos[li_i]+".focus();");
				lb_valido=false;
				break;				
			}
		}
		if(lb_valido)
		{
			f.operacion.value="ue_guardar";
			f.action="sigesp_sob_d_actainicio.php";
			f.submit();
		}	
	}
}
function ue_buscar()
{
	f=document.form1;
	var tipoacta=1;
	pagina="sigesp_cat_acta.php?tipoacta="+tipoacta;
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=350,resizable=yes,location=no,status=no,left=0,top=0");
} 

function ue_eliminar()
{
	f=document.form1;
	if (f.txtcodact.value=="")
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
		 f.action="sigesp_sob_d_actainicio.php";
		 f.submit();
	   }
	}	   
}
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
	if(f.txtcodcon.value=="")
	{
		alert("Debe seleccionar un Contrato!!!");
	}
	else
	{
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
		h=2;
	}
}

function mensaje()
{
	alert("No puede cambiar el Contrato!!!");
}

 
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>