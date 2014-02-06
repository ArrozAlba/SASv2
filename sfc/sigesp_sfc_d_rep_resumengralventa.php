<?Php
/******************************************/
/* FECHA: 13/08/2007                      */
/*                                        */
/******************************************/
session_start();

if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_conexion.php'";
	 print "</script>";
   }

//$_SESSION["ls_codtienda"] = '0002';
$la_datemp=$_SESSION["la_empresa"];
$ls_codtie= $_SESSION["ls_codtienda"];
//print $ls_codtie;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Resumen General de Ventas por Producto</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie
		document.onkeydown = function(){
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505;
		}
		if(window.event.keyCode == 505){ return false;}
		}
	}
</script>
<style type="text/css">
<!--
.style6 {color: #000000}
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno" >
	<tr>
		<td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
	</tr>
	<tr>
    <td width="527" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1">Sistema de Facturaci&oacute;n</span></td>
    <td width="251" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">&nbsp;</td>
    <td height="20" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
	<tr>
		<td height="20" colspan="2" class="cd-menu">
			<script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script>		</td>
	</tr>
	<tr>
		<td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
	</tr>
	<tr>
		<td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript: ue_ver();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Buscar" width="20" height="20" border="0"></a>
		<a href="javascript:ue_openexcel();"><img src="../shared/imagebank/tools20/excel.jpg" alt="Excel" width="20" height="20" border="0"></a></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
	</tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?Php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SFC";
	$ls_ventanas="sigesp_sfc_d_rep_producto.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST))
	{

			$ls_permisos=             $_POST["permisos"];
			$la_permisos["leer"]=     $_POST["leer"];
			$la_permisos["incluir"]=  $_POST["incluir"];
			$la_permisos["cambiar"]=  $_POST["cambiar"];
			$la_permisos["eliminar"]= $_POST["eliminar"];
			$la_permisos["imprimir"]= $_POST["imprimir"];
			$la_permisos["anular"]=   $_POST["anular"];
			$la_permisos["ejecutar"]= $_POST["ejecutar"];

	}
	else
	{
		$la_permisos["leer"]="";
		$la_permisos["incluir"]="";
		$la_permisos["cambiar"]="";
		$la_permisos["eliminar"]="";
		$la_permisos["imprimir"]="";
		$la_permisos["anular"]="";
		$la_permisos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_permisos);
	}

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("class_folder/sigesp_sfc_class_utilidades.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");
$io_datastore= new class_datastore();
$io_utilidad = new sigesp_sfc_class_utilidades();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$ls_codemp=$la_datemp["codemp"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codcli="%/".$_POST["txtcodcli"]."%";
	$ls_denpro="%/".$_POST["txtdenpro"]."%";
	$ls_fecemi=$_POST["txtfecemi"];
	$ls_fecemi2=$_POST["txtfecemi2"];
	$ls_dencla="%/".$_POST["txtdencla"]."%";
	$ls_opcion=$_POST["opcion"];
	$ls_orden=$_POST["combo_orden"];

    $ls_tienda_desde = $_POST["txtcodtienda_desde"];
	$ls_tienda_hasta = $_POST["txtcodtienda_hasta"];
	$ls_dentienda_desde = $_POST["txtdentienda_desde"];
	$ls_dentienda_hasta = $_POST["txtdentienda_hasta"];

	$ls_ordenarpor=$_POST["combo_ordenarpor"];
	$ls_fecemi=$io_funcion->uf_convertirdatetobd($ls_fecemi);
	$ls_fecemi2=$io_funcion->uf_convertirdatetobd($ls_fecemi2);
	if ($ls_fecemi=="")
	{
		$ls_fecemi="%/".$ls_fecemi."%";
		}

	if ($ls_fecemi2=="")
	{
		$ls_fecemi2="%/".$ls_fecemi2."%";
		}
	}
else
{
	$ls_operacion="";
	$ls_codcli="";
	$ls_denpro="";
	$ls_fecemi="%%";
	$ls_fecemi2="%%";
	$ls_orden="";
	$ls_ordenarpor="Null";
	$ls_dencla="";
	$ls_opcion="detalles";
	}
if($ls_operacion=="ue_actualizar_option")
	{
		  if ( $ls_opcion=="detalles"){
		  $ls_opcion="detalles";
		  $ls_ordenarpor="Null";
		  }else{
		  $ls_opcion="resumen";
		  $ls_ordenarpor="Null";
		  }


	}
?>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=leer value='$la_permisos[leer]'>");
	print("<input type=hidden name=incluir  id=incluir value='$la_permisos[incluir]'>");
	print("<input type=hidden name=cambiar  id=cambiar value='$la_permisos[cambiar]'>");
	print("<input type=hidden name=eliminar id=eliminar value='$la_permisos[eliminar]'>");
	print("<input type=hidden name=imprimir id=imprimir value='$la_permisos[imprimir]'>");
	print("<input type=hidden name=anular   id=anular value='$la_permisos[anular]'>");
	print("<input type=hidden name=ejecutar id=ejecutar value='$la_permisos[ejecutar]'>");
}
else
{

	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
    <table width="584" height="260" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
		<tr>
			<td width="510" height="258"><div align="center">
				<table width="571"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
					<tr>
						<td colspan="4" class="titulo-ventana">Resumen General de Ventas por Productos </td>
					</tr>
					<tr>
						<td colspan="4" class="sin-borde">&nbsp;</td>
					</tr>
					<tr>
						<td width="146" ><div align="right">
							<input name="txtcodcli" type="hidden" id="txtcodcli">
							<input name="txtcodemp" type="hidden" id="txtcodemp" value="<?php print $ls_codemp?>">
							<input name="operacion1" type="hidden" id="operacion1" value="<? print $ls_opcion?>">
							<input name="operacion" type="hidden" id="operacion" value="<? print $ls_operacion?>">
							Ordenar por
							</div>						</td>
								<td width="150" ><p align="left">
								<select name="combo_ordenarpor" size="1" >
							  <?php
							  if ($ls_ordenarpor=="Null")
							   {
							   ?>
								<option value="Null" selected>Seleccione...</option>
								<option value="d.codart">Codigo del Producto</option>
								<option value="a.denart">Definici&oacute;n del Producto</option>
								<option value="d.prepro">Precio del Producto</option>
								<?php
							   }
							  elseif ($ls_ordenarpor=="d.codart")
							   {
								?>
								<option value="Null" >Seleccione...</option>
								<option value="d.codart" selected>Codigo del Producto</option>
								<option value="a.denart">Definici&oacute;n del Producto</option>
								<option value="d.prepro">Precio del Producto</option>
								<?php
							   }
							   elseif ($ls_ordenarpor=="a.denart")
							   {
								?>
								<option value="Null" >Seleccione...</option>
								<option value="d.codart">Codigo del Producto</option>
								<option value="a.denart" selected>Definicion del Producto</option>
								<option value="d.prepro">Precio del Producto</option>
								<?php
							   }
							   elseif ($ls_ordenarpor=="d.prepro")
							   {
								?>
								<option value="Null" >Seleccione...</option>
								<option value="d.codart">Codigo del Producto</option>
								<option value="a.denart">Definicion del Producto</option>
								<option value="d.prepro">Precio del Producto</option>
								<?php
							    }
							   ?>
							</select>
							</p>						</td>
						<td width="249" colspan="2" >
							Orden
							<select name="combo_orden" size="1">
							<?php
							  if ($ls_orden=="ASC")
							   {
							   ?>
			                  <option value="ASC" selected>ASC</option>
			                  <option value="DESC">DESC</option>
							  <?php
							   }
							  else
							   {
							   ?>
			                  <option value="ASC" >ASC</option>
			                  <option value="DESC" selected>DESC</option>
							  <?php
							  }
							  ?>
			                </select>						</td>
					</tr>

					<?php
					if ($ls_codtie == '0001') {

					?>

                   <input type="hidden" name="hdnagrotienda" value=""/>

					<tr>
		                <td height="22" align="right">Desde Unidad Operativa de Suministro:</td>
		                <td colspan="3" >

		                <input name="txtdentienda_desde" type="text" id="txtdentienda_desde" size="30">
		                <input name="txtcodtienda_desde" type="hidden" id="txtcodtienda_desde" size="30">
		                <a href="javascript: ue_buscar_tienda('desde');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
					</tr>

					<tr>
		                <td height="22" align="right">Hasta Unidad Operativa de Suministro:</td>
		                <td colspan="3" >
		                <input name="txtdentienda_hasta" type="text" id="txtdentienda_hasta" size="30">
		                <input name="txtcodtienda_hasta" type="hidden" id="txtcodtienda_hasta" size="30">
		                <a href="javascript: ue_buscar_tienda('hasta');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
					</tr>


					<?php
					}
					?>


					<tr>
		                <td height="22" align="right">Clasificaci&oacute;n</td>
		                <td colspan="3" ><input name="txtdencla" type="text" id="txtdencla" size="30">
		                <a href="javascript: ue_buscar_clasificacion();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
					</tr>
					<tr>
		                <td height="27" align="right">Producto</td>
		                <td colspan="3" ><input name="txtdenpro" type="text" id="txtdenpro" size="30">
		                <a href="javascript:ue_catproducto();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
					</tr>

					<tr>
		                <td height="22" align="right">Fecha desde </td>
		                <td colspan="3"><input name="txtfecemi" type="text" id="txtfecemi"  style="text-align:left" size="11" maxlength="10" onKeyPress="javascript:ue_validafecha();"  datepicker="true" readonly="true"></td>
					</tr>
					<tr align="left">
		                <td height="23" align="right">Fecha hasta </td>
		                <td colspan="3"><input name="txtfecemi2" type="text" id="txtfecemi2"  style="text-align:left" size="11" maxlength="10" onKeyPress="javascript:ue_validafecha();"  datepicker="true" readonly="true">
		                <a href="javascript: ue_ver();"></a></td>
					</tr>
					<tr>
						<td width="72" height="8">&nbsp;</td>
						<td colspan="3"><p>
							<label>

			                  <br>
			                
						
					
	            </table>
				</div>
		  </td>
		</tr>
  </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</form>
<?php


function sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,$alias_tabla,$ls_codtie) {

$add_sql = '';
if ($ls_tienda_desde=='') {

$add_sql = "$alias_tabla.codtiend='$ls_codtie'";

}else {

$add_sql = "$alias_tabla.codtiend  BETWEEN '$ls_tienda_desde' AND '$ls_tienda_hasta'";

}

return $add_sql;
}


if($ls_operacion=="VER")
{
	$ls_operacion="";
	$ls_suiche=false;
	if ($ls_ordenarpor!="Null")
	{
	if ($ls_fecemi=="%/%" AND $ls_fecemi2=="%/%") //si el usuario no coloca fecha
	{
		$ls_suiche=true;
		$ls_sql="SELECT d.codart,a.denart,SUM(d.canpro) as cantidad,d.prepro,SUM(d.canpro)*d.prepro as subtotal,d.porimp,p.cosproart FROM " .
				"sfc_detfactura d,sfc_producto p,sfc_clasificacion cl,sim_articulo a WHERE " .
				"d.codemp=p.codemp AND d.codemp=a.codemp AND d.codart=p.codart AND a.codart=p.codart AND " .
				" d.codtiend=p.codtiend AND p.codemp=a.codemp AND cl.codcla=a.codcla AND a.codart=d.codart AND" .
				" ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'d',$ls_codtie)." AND" .
				" a.denart like '".$ls_denpro."' AND cl.dencla ilike '".$ls_dencla."' AND " .
				"d.numfac IN (SELECT numfac FROM sfc_factura where estfaccon<>'A') GROUP BY d.codart,a.denart,d.prepro,d.porimp,p.cosproart " .
				"ORDER BY ".$ls_ordenarpor." ".$ls_orden.";";
			/*echo "Consulta 1 <br>";
			print $ls_sql;*/


	}
	elseif ($ls_fecemi<>"%/%" AND $ls_fecemi2<>"%/%") //si el usuario coloca fecha desde - hasta
	{
		$ls_suiche=true;

		$ls_sql="SELECT d.codart,a.denart,SUM(d.canpro) as cantidad,d.prepro,d.porimp,SUM(d.canpro)*d.prepro as subtotal,d.porimp,p.cosproart" .
				" FROM sfc_detfactura d,sfc_producto p,sfc_clasificacion cl,sfc_factura f,sim_articulo a WHERE f.codemp=d.codemp AND " .
					" f.codemp=p.codemp AND f.codemp=a.codemp AND f.numfac=d.numfac AND f.codtiend=d.codtiend AND " .
					" f.codtiend=p.codtiend AND d.codemp=p.codemp AND d.codemp=a.codemp AND d.codart=p.codart AND a.codart=p.codart AND " .
					" d.codtiend=p.codtiend AND p.codemp=a.codemp AND cl.codcla=a.codcla AND a.codart=d.codart AND " .
					"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie)." AND a.denart like '".$ls_denpro."' AND " .
					"(substr(cast (f.fecemi as char(30)),0,11)>='".$ls_fecemi."' AND substr(cast (f.fecemi as char(30)),0,11)<='".$ls_fecemi2."') AND cl.dencla ilike '".$ls_dencla."' AND " .
					"d.numfac IN (SELECT numfac FROM sfc_factura where estfaccon<>'A') " .
					"GROUP BY d.codart,a.denart,d.prepro,d.porimp,p.cosproart ORDER BY ".$ls_ordenarpor." ".$ls_orden.";";
			 /*echo "Consulta 2 <br>";
			 print $ls_sql;*/

		}
	else // si falta colocar una de las dos fechas
	{
		$io_msg->message("Debe introducir el rango completo de la fecha!");
		}
	}else{
	if ($ls_fecemi=="%/%" AND $ls_fecemi2=="%/%") //si el usuario no coloca fecha
	{

			$ls_sql="SELECT d.porimp,d.codart,a.denart,SUM(d.canpro) as cantidad,d.prepro,SUM(d.canpro)*d.prepro as subtotal,p.cosproart FROM " .
					"sfc_detfactura d,sfc_producto p,sfc_clasificacion cl,sim_articulo a WHERE " .
					"d.codemp=p.codemp AND d.codemp=a.codemp AND d.codart=p.codart AND a.codart=p.codart AND " .
					" d.codtiend=p.codtiend AND p.codemp=a.codemp AND cl.codcla=a.codcla AND a.codart=d.codart AND " .
					"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'d',$ls_codtie)." AND a.denart like '".$ls_denpro."' AND " .
					"cl.dencla ilike '".$ls_dencla."' AND d.numfac IN (SELECT numfac FROM sfc_factura where estfaccon<>'A') " .
					"GROUP BY d.codart,a.denart,d.prepro,d.porimp,p.cosproart;";
			/*echo "Consulta 3 <br>";
			print $ls_sql;*/

		}
	elseif ($ls_fecemi<>"%/%" AND $ls_fecemi2<>"%/%") //si el usuario coloca fecha desde - hasta
	{
		$ls_suiche=true;

			$ls_sql="SELECT d.codart,d.porimp,a.denart,SUM(d.canpro) as cantidad,d.prepro,SUM(d.canpro)*d.prepro as subtotal,p.cosproart FROM " .
					"sfc_detfactura d,sfc_producto p,sfc_clasificacion cl,sfc_factura f,sim_articulo a WHERE f.codemp=d.codemp AND " .
					" f.codemp=p.codemp AND f.codemp=a.codemp AND f.numfac=d.numfac AND f.codtiend=d.codtiend AND " .
					" f.codtiend=p.codtiend AND d.codemp=p.codemp AND d.codemp=a.codemp AND d.codart=p.codart AND a.codart=p.codart AND " .
					" d.codtiend=p.codtiend AND p.codemp=a.codemp AND cl.codcla=a.codcla AND a.codart=d.codart AND " .
					" ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie)." AND " .
					" a.denart like '".$ls_denpro."' AND (substr(cast (f.fecemi as char(30)),0,11)>='".$ls_fecemi."' AND substr(cast (f.fecemi as char(30)),0,11)<='".$ls_fecemi2."') " .
					"AND cl.dencla ilike '".$ls_dencla."' AND d.numfac IN (SELECT numfac FROM sfc_factura where estfaccon<>'A') " .
					"GROUP BY d.codart,a.denart,d.prepro,d.porimp,p.cosproart;";
			 /*echo "Consulta 4 <br>";
			 print $ls_sql;*/

		}
	else // si falta colocar una de las dos fechas
	{
		$io_msg->message("Debe introducir el rango completo de la fecha!");
		}
	}
//print $ls_sql;

	?>
		<script language="JavaScript">
		var ls_sql="<?php print $ls_sql; ?>";
		var ls_fecemi="<?php print $ls_fecemi; ?>";
		var ls_fecemi2="<?php print $ls_fecemi2; ?>";

		pagina="reportes/sigesp_sfc_rep_resumengralventa.php?sql="+ls_sql+"&fecemi="+ls_fecemi+"&fecemi2="+ls_fecemi2;
		popupWin(pagina,"catalogo",580,700);
		</script>
	<?php

	}

//////////////////////////////////REPORTES EXCEL  ////////////////////////////////////////////////////////

if($ls_operacion=="EXCEL")
{
	$ls_operacion="";
	$ls_suiche=false;
	if ($ls_ordenarpor!="Null")
	{
	if ($ls_fecemi=="%/%" AND $ls_fecemi2=="%/%") //si el usuario no coloca fecha
	{
		$ls_suiche=true;
		$ls_sql="SELECT d.codart,a.denart,SUM(d.canpro) as cantidad,d.prepro,SUM(d.canpro)*d.prepro as subtotal,d.porimp,p.cosproart FROM " .
				"sfc_detfactura d,sfc_producto p,sfc_clasificacion cl,sim_articulo a WHERE " .
				"d.codemp=p.codemp AND d.codemp=a.codemp AND d.codart=p.codart AND a.codart=p.codart AND " .
				" d.codtiend=p.codtiend AND p.codemp=a.codemp AND cl.codcla=a.codcla AND a.codart=d.codart AND " .
				"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'d',$ls_codtie)." AND a.denart like '".$ls_denpro."' AND " .
				"cl.dencla ilike '".$ls_dencla."' AND d.numfac IN (SELECT numfac FROM sfc_factura where estfaccon<>'A') " .
				"GROUP BY d.codart,a.denart,d.prepro,d.porimp,p.cosproart ORDER BY ".$ls_ordenarpor." ".$ls_orden.";";
			/*echo "Consulta excel 1 <br>";
			print $ls_sql;*/

	}
	elseif ($ls_fecemi<>"%/%" AND $ls_fecemi2<>"%/%") //si el usuario coloca fecha desde - hasta
	{
		$ls_suiche=true;

		$ls_sql="SELECT d.codart,a.denart,SUM(d.canpro) as cantidad,d.prepro,d.porimp,SUM(d.canpro)*d.prepro as subtotal,d.porimp,p.cosproart" .
				" FROM sfc_detfactura d,sfc_producto p,sfc_clasificacion cl,sfc_factura f,sim_articulo a WHERE f.codemp=d.codemp AND " .
				" f.codemp=p.codemp AND f.codemp=a.codemp AND f.numfac=d.numfac AND f.codtiend=d.codtiend AND " .
				" f.codtiend=p.codtiend AND d.codemp=p.codemp AND d.codemp=a.codemp AND d.codart=p.codart AND a.codart=p.codart AND " .
				" d.codtiend=p.codtiend AND p.codemp=a.codemp AND cl.codcla=a.codcla AND a.codart=d.codart AND " .
				"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie)." AND " .
				" a.denart like '".$ls_denpro."' AND (substr(cast (f.fecemi as char(30)),0,11)>='".$ls_fecemi."' AND substr(cast (f.fecemi as char(30)),0,11)<='".$ls_fecemi2."') AND " .
				" cl.dencla ilike '".$ls_dencla."' AND d.numfac IN (SELECT numfac FROM sfc_factura where estfaccon<>'A') " .
				"GROUP BY d.codart,a.denart,d.prepro,d.porimp,p.cosproart ORDER BY ".$ls_ordenarpor." ".$ls_orden.";";
			 /*echo "Consulta excel 2 <br>";
			 print $ls_sql;*/

		}
	else // si falta colocar una de las dos fechas
	{
		$io_msg->message("Debe introducir el rango completo de la fecha!");
		}
	}else{
	if ($ls_fecemi=="%/%" AND $ls_fecemi2=="%/%") //si el usuario no coloca fecha
	{

			$ls_sql="SELECT d.porimp,d.codart,a.denart,SUM(d.canpro) as cantidad,d.prepro,SUM(d.canpro)*d.prepro as subtotal,p.cosproart FROM " .
					"sfc_detfactura d,sfc_producto p,sfc_clasificacion cl,sim_articulo a WHERE " .
					" d.codemp=p.codemp AND d.codemp=a.codemp AND d.codart=p.codart AND a.codart=p.codart AND " .
					" d.codtiend=p.codtiend AND p.codemp=a.codemp AND cl.codcla=a.codcla AND a.codart=d.codart AND " .
					"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'d',$ls_codtie)." AND a.denart like '".$ls_denpro."' AND " .
					"cl.dencla ilike '".$ls_dencla."' AND d.numfac IN (SELECT numfac FROM sfc_factura where estfaccon<>'A') " .
					"GROUP BY d.codart,a.denart,d.prepro,d.porimp,p.cosproart;";
			/*echo "Consulta excel 3 <br>";
			print $ls_sql;*/

		}
	elseif ($ls_fecemi<>"%/%" AND $ls_fecemi2<>"%/%") //si el usuario coloca fecha desde - hasta
	{
		$ls_suiche=true;

			$ls_sql="SELECT d.codart,d.porimp,a.denart,SUM(d.canpro) as cantidad,d.prepro,SUM(d.canpro)*d.prepro as subtotal,p.cosproart FROM " .
					"sfc_detfactura d,sfc_producto p,sfc_clasificacion cl,sfc_factura f,sim_articulo a WHERE f.codemp=d.codemp AND " .
					" f.codemp=p.codemp AND f.codemp=a.codemp AND f.numfac=d.numfac AND f.codtiend=d.codtiend AND " .
					" f.codtiend=p.codtiend AND d.codemp=p.codemp AND d.codemp=a.codemp AND d.codart=p.codart AND a.codart=p.codart AND " .
					" d.codtiend=p.codtiend AND p.codemp=a.codemp AND cl.codcla=a.codcla AND a.codart=d.codart AND " .
					"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie)." AND a.denart like '".$ls_denpro."' AND " .
					"(substr(cast (f.fecemi as char(30)),0,11)>='".$ls_fecemi."' AND substr(cast (f.fecemi as char(30)),0,11)<='".$ls_fecemi2."') AND cl.dencla ilike '".$ls_dencla."' AND " .
					"d.numfac IN (SELECT numfac FROM sfc_factura where estfaccon<>'A') " .
					"GROUP BY d.codart,a.denart,d.prepro,d.porimp,p.cosproart;";
			 /*echo "Consulta excel 4 <br>";
			 print $ls_sql;*/

		}
	else // si falta colocar una de las dos fechas
	{
		$io_msg->message("Debe introducir el rango completo de la fecha!");
		}
	}
//print $ls_sql;

	?>
		<script language="JavaScript">
		var ls_sql="<?php print $ls_sql; ?>";
		//alert(ls_sql);
		var ls_fecemi="<?php print $ls_fecemi; ?>";
		var ls_fecemi2="<?php print $ls_fecemi2; ?>";

		pagina="reportes/sigesp_sfc_rep_resumengralventa_excel.php?sql="+encodeURIComponent(ls_sql)+"&fecemi="+ls_fecemi+"&fecemi2="+ls_fecemi2;
		popupWin(pagina,"catalogo",580,700);
		</script>
	<?php

	}



/*
if($ls_operacion=="VERXML")
{
	$ls_operacion="";
	$ls_suiche=false;
	if ($ls_ordenarpor!="Null")
	{
	if ($ls_fecemi=="%/%" AND $ls_fecemi2=="%/%") //si el usuario no coloca fecha
	{
		$ls_suiche=true;
			$ls_sql=" SELECT cl.codcla, cl.dencla,c.razcli,c.cedcli, f.fecemi,f.numfac,f.codcli,d.canpro,d.prepro,p.denpro FROM sfc_factura f,sfc_detfactura d, sfc_producto p, sfc_cliente c,sfc_clasificacion cl WHERE cl.codcla=p.codcla AND f.codemp='".$ls_codemp."' AND c.codcli=f.codcli AND p.codpro=d.codpro AND d.numfac=f.numfac AND f.codcli ilike '".$ls_codcli."' AND f.fecemi ilike '".$ls_fecemi."' AND p.denpro ilike '".$ls_denpro."' AND cl.dencla ilike '".$ls_dencla."' ORDER BY ".$ls_ordenarpor." ".$ls_orden.";";

		}
	elseif ($ls_fecemi<>"%/%" AND $ls_fecemi2<>"%/%") //si el usuario coloca fecha desde - hasta
	{
		$ls_suiche=true;

			$ls_sql="SELECT cl.codcla, cl.dencla,c.razcli,c.cedcli,f.fecemi,f.numfac,d.canpro,f.codcli,d.prepro,p.denpro FROM sfc_factura f,sfc_detfactura d, sfc_producto p, sfc_cliente c,sfc_clasificacion cl WHERE cl.codcla=p.codcla AND f.codemp='".$ls_codemp."' AND c.codcli=f.codcli AND p.codpro=d.codpro AND d.numfac=f.numfac AND f.codcli ilike '".$ls_codcli."' AND (f.fecemi>='".$ls_fecemi."' AND f.fecemi<='".$ls_fecemi2."') AND p.denpro ilike '".$ls_denpro."' AND cl.dencla ilike '".$ls_dencla."' ORDER BY ".$ls_ordenarpor." ".$ls_orden.";";
			//print $ls_sql;
		}
	else // si falta colocar una de las dos fechas
	{
		$io_msg->message("Debe introducir el rango completo de la fecha!");
		}
	}else{
	if ($ls_fecemi=="%/%" AND $ls_fecemi2=="%/%") //si el usuario no coloca fecha
	{
		$ls_suiche=true;
		$ls_sql=" SELECT cl.codcla, cl.dencla,c.razcli,c.cedcli, f.fecemi,f.numfac,f.codcli,d.canpro,d.prepro,p.denpro FROM sfc_factura f,sfc_detfactura d, sfc_producto p, sfc_cliente c,sfc_clasificacion cl WHERE cl.codcla=p.codcla AND f.codemp='".$ls_codemp."' AND c.codcli=f.codcli AND p.codpro=d.codpro AND d.numfac=f.numfac AND f.codcli ilike '".$ls_codcli."' AND f.fecemi ilike '".$ls_fecemi."' AND p.denpro ilike '".$ls_denpro."' AND cl.dencla ilike '".$ls_dencla."';";
		}
	elseif ($ls_fecemi<>"%/%" AND $ls_fecemi2<>"%/%") //si el usuario coloca fecha desde - hasta
	{
		$ls_suiche=true;
			$ls_sql="SELECT cl.codcla, cl.dencla,c.razcli,c.cedcli,f.fecemi,f.numfac,d.canpro,f.codcli,d.prepro,p.denpro FROM sfc_factura f,sfc_detfactura d, sfc_producto p, sfc_cliente c,sfc_clasificacion cl WHERE cl.codcla=p.codcla AND f.codemp='".$ls_codemp."' AND c.codcli=f.codcli AND p.codpro=d.codpro AND d.numfac=f.numfac AND f.codcli ilike '".$ls_codcli."' AND (f.fecemi>='".$ls_fecemi."' AND f.fecemi<='".$ls_fecemi2."') AND p.denpro ilike '".$ls_denpro."' AND cl.dencla ilike '".$ls_dencla."';";
			}
	else // si falta colocar una de las dos fechas
	{
		$io_msg->message("Debe introducir el rango completo de la fecha!");
		}
	}

	if ($ls_suiche==true and $ls_opcion=='detalles') //envia datos al reporte detalles
	{
	?>
		<script language="JavaScript">
		var ls_sql="<?php print $ls_sql; ?>";
		var ls_fecemi="<?php print $ls_fecemi; ?>";
		var ls_fecemi2="<?php print $ls_fecemi2; ?>";
		pagina="reportes/sigesp_sfc_rep_productoxml.php?sql="+ls_sql+"&fecemi2="+ls_fecemi2+"&fecemi="+ls_fecemi;
		popupWin(pagina,"catalogo",580,700);
		</script>
	<?php
		}else{
		?>
		<script>alert('No hay nada que Reportar!!!');</script>
		<?php
		}

	}*/
?>
</body>
<script language="JavaScript">


/************************* TIENDA***************************************/
function ue_buscar_tienda(intervalo)
{
	f=document.form1;
	if (intervalo == 'desde') {
	  f.hdnagrotienda.value='desde';
	  f.txtcodtienda_desde.value="";
	}else {
	  f.hdnagrotienda.value='hasta';
	  f.txtcodtienda_hasta.value="";
	}
	pagina="sigesp_cat_tienda.php";
	popupWin(pagina,"catalogo",600,250);
}


function ue_cargartienda (codtie,nomtie,dirtie,teltie,riftie,codpai,codest,codmun,codpar,items,spi_cuenta,denominacion,codunidad,denunidad,cuentapre,denpre,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5)
{

	f=document.form1;
	if (f.hdnagrotienda.value == 'desde') {
	 f.txtcodtienda_desde.value=codtie;
	 f.txtdentienda_desde.value=nomtie;
	}else {
     f.txtcodtienda_hasta.value=codtie;
     f.txtdentienda_hasta.value=nomtie;
	}


}

/************************* TIENDA***************************************/


function ue_cargarproducto(codpro,denpro,tippro,preven,codcar,dencar,codcla,dencla,codart,denart,ultcosart,spi_cuenta,denspi,sc_cuenta,denscg,codalm)
{
    f=document.form1;
	f.operacion.value="";
	f.txtdenpro.value=denpro;
}

function ue_catproducto()
{
	f=document.form1;
	f.operacion.value="";
	pagina="sigesp_cat_producto.php";
	popupWin(pagina,"catalogo",580,300);
}

function ue_ver()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{
	f.operacion.value="VER";
	f.action="sigesp_sfc_d_rep_resumengralventa.php";
	f.submit();
	}
	else
	{alert("No tiene permiso para realizar esta operaci�n");}
}

function ue_ver2()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{
	f.operacion.value="VER2";
	f.action="sigesp_sfc_d_rep_resumengralventa.php";
	f.submit();
	}
	else
	{alert("No tiene permiso para realizar esta operaci�n");}
}

function ue_openexcel()
{
	f=document.form1;
	 li_imprimir=f.imprimir.value;

if(li_imprimir==1)
{

	f.operacion.value="EXCEL";

	f.action="sigesp_sfc_d_rep_resumengralventa.php";
	f.submit();
	}
else
	{alert("No tiene permiso para realizar esta operaci�n");}
		/*pagina="reportes/sigesp_sfc_rep_existeclasificacion_excel.php?sql="+encodeURIComponent(ls_sql);
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");*/
}


function ue_nuevo()
{
	f=document.form1;
	f.operacion.value="";
	f.txtdencla.value="";
	f.txtdenpro.value="";
	f.txtrazcli.value="";
	f.txtfecemi.value="";
	f.txtfecemi2.value="";
}

function actualizar_combo()
{
	f=document.form1;
	f.combo_ordenarpor.value="VER";
	f.action="sigesp_sfc_d_rep_factura.php";
	f.submit();
}

function ue_buscar_clasificacion()
{
	f=document.form1;
	f.operacion.value="";
	pagina="sigesp_cat_clasificacion.php";
	popupWin(pagina,"catalogo",600,250);
}

function ue_cargarclasificacion(codcla,nomcla)
{
	f=document.form1;
	f.txtdencla.value=nomcla;
}

function ue_buscar_cliente()
{
	f=document.form1;
	f.operacion.value="";
	pagina="sigesp_cat_cliente.php";
	popupWin(pagina,"catalogo",600,250);
}

function ue_cargarcliente(codcli,nomcli,dircli,telcli,celcli,codpai,codest,codmun,codpar)
{
	f=document.form1;
	f.txtcodcli.value=codcli;
	f.txtrazcli.value=nomcli;
	}

function actualizar_option()
{
			f=document.form1;
			f.operacion.value="ue_actualizar_option";
			f.action="sigesp_sfc_d_rep_resumengralventa.php";
			 f.submit();

}

function ue_imprimir()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{
	ls_codemp=f.txtcodemp.value;
	ls_codcli=f.txtcodcli.value;
	ls_fecemi=f.txtfecemi.value;
	ls_fecemi2=f.txtfecemi2.value;
	ls_denpro=f.txtdenpro.value;
	ls_dencla=f.txtdencla.value;
	ls_ordenapor=f.combo_ordenapor.value;
	ls_orden=f.combo_orden.value;
	ls_opcion=f.operacion1.value;
	if((ld_fecdes!="")&&(ld_fechas!=""))
	{
	window.open("reportes/sigesp_sss_rpp_auditoria1.php?codigo="+ls_codigo+"&evento="+ls_evento+"&sistema="+ls_sistema+"&fecdes="+ld_fecdes+"&fechas="+ld_fechas+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=50,top=50,location=no,resizable=yes");
		}
	else
	{
			alert("Seleccione solo un (1) dia a imprimir");
		}
	}
	else
	{alert("No tiene permiso para realizar esta operaci�n");}
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
