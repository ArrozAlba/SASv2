<?php
/******************************************/
/* FECHA: 03/09/2007                      */
/* AUTOR: ING. ZULHEYMAR RODR�GUEZ        */
/******************************************/
session_start();

if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_conexion.php'";
	 print "</script>";
   }
//$_SESSION["ls_codtienda"] = '0001';
$la_datemp=$_SESSION["la_empresa"];
$ls_codtie=$_SESSION["ls_codtienda"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Listado de Ventas de Productos por Linea</title>
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
    <td width="537" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1">Sistema de Facturaci&oacute;n</span></td>
    <td width="241" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
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
		<td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript: ue_ver();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
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
	$ls_ventanas="sigesp_sfc_d_rep_productoest.php";

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
	$ls_fecemi=$_POST["txtfecemi"];
	$ls_fecemi2=$_POST["txtfecemi2"];
	$ls_dencla="%/".$_POST["txtdencla"]."%";
	$ls_opcion=$_POST["opcion"];
	$ls_orden=$_POST["combo_orden"];
	$ls_ordenarpor=$_POST["combo_ordenarpor"];

	$ls_tienda_desde = $_POST["txtcodtienda_desde"];
	$ls_tienda_hasta = $_POST["txtcodtienda_hasta"];

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
	$ls_fecemi="%%";
	$ls_fecemi2="%%";
	$ls_orden="";
	$ls_ordenarpor="Null";
	$ls_dencla="";
	$ls_opcion="Barras_Verticales";
	}
if($ls_operacion=="ue_actualizar_option")
	{
		  if ($ls_opcion=="Barras_Verticales"){
		  $ls_opcion="Barras_Verticales";
		  $ls_ordenarpor="Null";
		  }else if ($ls_opcion=="Barras_Horizontales")
		  {
		  $ls_opcion="Barras_Horizontales";
		  $ls_ordenarpor="Null";
		  }else if ($ls_opcion=="Torta"){
		  $ls_opcion="Torta";
		  $ls_ordenarpor="Null";
		  }else if ($ls_opcion=="Multiples_Lineas"){
		  $ls_opcion="Multiples_Lineas";
		  $ls_ordenarpor="Null";
		  }	else if ($ls_opcion=="Lineas"){
		  $ls_opcion="Lineas";
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
    <table width="518" height="260" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
		<tr>
			<td width="600" height="258"><div align="center">
			  <table width="616"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
					<tr>
						<td colspan="3" class="titulo-ventana">Listado de Ventas de Productos por Linea </td>
					</tr>
					<tr>
						<td height="17" colspan="3" class="sin-borde">&nbsp;</td>
					</tr>
					 <tr>
              <td height="8" colspan="2"><table width="483" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                  <tr>
                    <td height="13" colspan="4" align="center" class="titulo-ventana">Tipo de Gr&aacute;fico </td>
                  </tr>



                  <tr>
						<td height="8">&nbsp;</td>
						<td colspan="3"><p>
							<label>
							<?php
							 if ($ls_opcion=='Barras_Verticales')
							   {
							   ?>
			                   <input name="opcion" type="radio" value="Barras_Verticales"  checked="checked" onClick=
							   "actualizar_option()">
			                   Barras Verticales</label>
							   <label>
			                   <input name="opcion" type="radio" value="Barras_Horizontales"  onClick="actualizar_option()" >
			                   Barras Horizontales</label>
							   <input name="opcion" type="radio" value="Torta" onClick="actualizar_option()">                    		   							   Torta</label>
							   <input name="opcion" type="radio" value="Lineas" onClick=
							   "actualizar_option()" >
			                   Lineas</label>

								<?php
								}
								else if ($ls_opcion=='Barras_Horizontales')
								{
								?>
								<input name="opcion" type="radio" value="Barras_Verticales" onClick="actualizar_option()">
			                   Barras Verticales</label>
							   <label>
			                   <input name="opcion" type="radio" value="Barras_Horizontales"  checked="checked" onClick=
							   "actualizar_option()" >
			                   Barras Horizontales</label>
							   <input name="opcion" type="radio" value="Torta" onClick="actualizar_option()">                    		   							   Torta</label>
							   <input name="opcion" type="radio" value="Lineas" onClick=
							   "actualizar_option()" >
			                   Lineas</label>

								<?php
								}else if ($ls_opcion=='Torta')
								{
								?>
							   <input name="opcion" type="radio" value="Barras_Verticales" onClick="actualizar_option()">
			                   Barras Verticales</label>
							   <label>
			                   <input name="opcion" type="radio" value="Barras_Horizontales" onClick="actualizar_option()" >
			                   Barras Horizontales</label>
							   <input name="opcion" type="radio" value="Torta"  checked="checked" onClick="actualizar_option()">                    		   Torta</label>
							   <input name="opcion" type="radio" value="Lineas" onClick=
							   "actualizar_option()" >
			                  Lineas</label>

								<?php
								}else if ($ls_opcion=='Lineas')
								{
								?>
								<input name="opcion" type="radio" value="Barras_Verticales" onClick="actualizar_option()">
			                   Barras Verticales</label>
							   <label>
			                   <input name="opcion" type="radio" value="Barras_Horizontales" onClick="actualizar_option()" >
			                   Barras Horizontales</label>
							   <input name="opcion" type="radio" value="Torta"  onClick="actualizar_option()">                    		   							   Torta</label>
							   <input name="opcion" type="radio" value="Lineas" checked="checked" onClick=
							   "actualizar_option()" >
			                  Lineas</label>

								<?php
								}
								?>


			                  <br>
			                </p>
						</td>
				  </tr>

              <tr>
              </tr>
                </table>				</td>
				</tr>
				<tr>
				<td height='10'></td></tr>
				<tr>

						<td width="215" ><div align="right">
							<input name="txtcodemp" type="hidden" id="txtcodemp" value="<?php print $ls_codemp?>">
							<input name="operacion1" type="hidden" id="operacion1" value="<? print $ls_opcion?>">
							<input name="operacion" type="hidden" id="operacion" value="<? print $ls_operacion?>">
							Ordenar por
							</div>					  </td>
							<td width="399"  ><p align="left">
							<select name="combo_ordenarpor" size="1" >
							  <?php
							  if ($ls_ordenarpor=="Null")
							   {
							   ?>
								<option value="Null" selected="selected">Seleccione...</option>
								<option value="cl.codcla" >C&oacute;digo de L&iacute;nea</option>
								<option value="cl.dencla" >Denominaci&oacute;n de L&iacute;nea</option>
								<option value="cantidad" >Cantidad Vendida</option>
							  <?php
							   }
							  elseif ($ls_ordenarpor=="cl.codcla")
							   {
								?>
								<option value="Null">Seleccione...</option>
								<option value="cl.codcla" selected="selected">C&oacute;digo de L&iacute;nea</option>
								<option value="cl.dencla" >Denominaci&oacute;n de L&iacute;nea</option>
								<option value="cantidad" >Cantidad Vendida</option>
							  <?php
							   }
							   elseif ($ls_ordenarpor=="cl.dencla")
							   {
								?>
								<option value="Null">Seleccione...</option>
								<option value="cl.codcla">C&oacute;digo de L&iacute;nea</option>
								<option value="cl.dencla" selected="selected">Denominaci&oacute;n de L&iacute;nea</option>
								<option value="cantidad" >Cantidad Vendida</option>
							  <?php
								}
							   elseif ($ls_ordenarpor=="cantidad")
							   {
								?>
								<option value="Null">Seleccione...</option>
								<option value="cl.codcla">C&oacute;digo de L&iacute;nea</option>
								<option value="cl.dencla">Denominaci&oacute;n de L&iacute;nea</option>
								<option value="cantidad" selected="selected">Cantidad Vendida</option>
							  <?php
							   }
							  ?>
							</select>
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
                            </select>
						</p></td>
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
					<td height="8" colspan="3"><p>
							<label></label>
							<br>
		                </p></td>
				</tr>
						<tr>
		                <td height="22" align="right">Fecha desde </td>
	                  <td colspan="2"><input name="txtfecemi" type="text" id="txtfecemi"  style="text-align:left" size="11" maxlength="10" onKeyPress="javascript:ue_validafecha();"  datepicker="true" readonly="true"> &nbsp;&nbsp;
		              Fecha hasta
                      <input name="txtfecemi2" type="text" id="txtfecemi2"  style="text-align:left" size="11" maxlength="10" onKeyPress="javascript:ue_validafecha();"  datepicker="true" readonly="true"></td>
			        </tr>
					<tr>
						<td height="8" colspan="3"><p>
							<label></label>
							<br>
		                </p></td>
					</tr>

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
	if ($ls_opcion=="Multiples_Lineas")
	{
	?>
	<script>alert("Debe colocar un Rango de Fechas para este tipo de Grafico");</script>
	<?php
	}else
	{
		$ls_suiche=true;
		$ls_sql="SELECT cl.codcla,cl.dencla,SUM(d.canpro) as cantidad,SUM(d.canpro*d.prepro) as subtotal FROM " .
				"sfc_detfactura d,sfc_producto p,sfc_clasificacion cl,sim_articulo a WHERE d.codemp=p.codemp AND d.codemp=a.codemp AND " .
				"d.codart=p.codart AND d.codart=a.codart AND d.codtiend=p.codtiend AND p.codemp=a.codemp AND p.codart=a.codart AND " .
				"a.codcla=cl.codcla AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'d',$ls_codtie)." " .
				"GROUP BY cl.codcla,cl.dencla ORDER BY ".$ls_ordenarpor." ".$ls_orden.";";
			//print $ls_sql;
	}
	}
	elseif ($ls_fecemi<>"%/%" AND $ls_fecemi2<>"%/%") //si el usuario coloca fecha desde - hasta
	{
	if ($ls_opcion=="Multiples_Lineas"){
		if ($ls_fecemi>=$ls_fecemi2)
		{
		?>
		<script>alert("Las Fecha desde no puede ser mayor o igual que la fecha hasta");</script>
		<?php
		}else{
		$ls_suiche=true;
		$ls_sql="SELECT cl.codcla,cl.dencla,SUM(d.canpro) as cantidad,SUM(d.canpro*d.prepro) as subtotal,substr(cast (f.fecemi as char(30)),0,11) as fecemi," .
				"SUBSTR(cast (f.fecemi as char(30)),6,2) as mes FROM sfc_detfactura d,sfc_producto p,sfc_clasificacion cl,sfc_factura f,sim_articulo a" .
				" WHERE f.codemp=d.codemp AND f.codemp=p.codemp AND f.numfac=d.numfac AND " .
				"f.codtiend=d.codtiend AND f.codtiend=p.codtiend d.codemp=p.codemp AND d.codart=p.codart AND " .
				" d.codtiend=p.codtiend AND p.codemp=a.codemp AND cl.codcla=a.codcla AND " .
				"".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'f',$ls_codtie)." AND " .
				"(substr(cast (f.fecemi as char(30)),0,11)>='".$ls_fecemi."' AND substr(cast (f.fecemi as char(30)),0,11)<='".$ls_fecemi2."') " .
				"GROUP BY cl.codcla,cl.dencla,fecemi ORDER BY cl.codcla,fecemi ASC;";
		//print $ls_sql;

	}
	}else{
	$ls_suiche=true;
	$ls_sql="SELECT cl.codcla,cl.dencla,SUM(d.canpro) as cantidad,SUM(d.canpro*d.prepro) as subtotal FROM " .
			"sfc_detfactura d,sfc_producto p,sfc_clasificacion cl,sfc_factura f,sim_articulo a WHERE " .
			"f.codemp=d.codemp AND f.codemp=p.codemp AND f.numfac=d.numfac AND f.codtiend=d.codtiend AND " .
			"f.codtiend=p.codtiend AND d.codemp=p.codemp AND d.codart=p.codart AND d.codtiend=p.codtiend AND " .
			"p.codemp=a.codemp AND cl.codcla=a.codcla AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'f',$ls_codtie)." AND " .
			"(substr(cast (f.fecemi as char(30)),0,11)>='".$ls_fecemi."' AND substr(cast (f.fecemi as char(30)),0,11)<='".$ls_fecemi2."') " .
			"GROUP BY cl.codcla,cl.dencla ORDER BY ".$ls_ordenarpor." ".$ls_orden.";";
	//print $ls_sql;
	}

	}
	else // si falta colocar una de las dos fechas
	{
		$io_msg->message("Debe introducir el rango completo de la fecha!");
		}
	}else{

	if ($ls_fecemi=="%/%" AND $ls_fecemi2=="%/%") //si el usuario no coloca fecha
	{
	if ($ls_opcion=="Multiples_Lineas")
	{
	?>
	<script>alert("Debe colocar un Rango de Fechas para este tipo de Grafico");</script>
	<?php
	}else{
		$ls_suiche=true;
		$ls_sql="SELECT cl.codcla,cl.dencla,SUM(d.canpro) as cantidad,SUM(d.canpro*d.prepro) as subtotal FROM " .
				"sfc_detfactura d,sfc_producto p,sfc_clasificacion cl,sim_articulo a WHERE  " .
				"d.codemp=p.codemp AND d.codemp=a.codemp AND d.codart=p.codart AND d.codart=a.codart AND " .
				"d.codtiend=p.codtiend AND p.codemp=a.codemp AND p.codart=a.codart AND a.codcla=cl.codcla AND " .
				"".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'d',$ls_codtie)." " .
				"GROUP BY cl.codcla,cl.dencla;";
		//print $ls_sql;
		$ls_sql2="SELECT cl.dencla FROM sfc_detfactura d,sfc_producto p,sfc_clasificacion cl,sim_articulo a WHERE " .
				"d.codemp=p.codemp AND d.codemp=a.codemp AND d.codart=p.codart AND d.codart=a.codart AND " .
				"d.codtiend=p.codtiend AND p.codemp=a.codemp AND p.codart=a.codart AND a.codcla=cl.codcla AND " .
				"".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'d',$ls_codtie)." GROUP by cl.dencla;";
		}
	}
	elseif ($ls_fecemi<>"%/%" AND $ls_fecemi2<>"%/%") //si el usuario coloca fecha desde - hasta
	{
	if ($ls_opcion=="Multiples_Lineas"){
		if ($ls_fecemi>=$ls_fecemi2)
		{
		?>
		<script>alert("Las Fecha desde no puede ser mayor o igual que la fecha hasta");</script>
		<?php
		}else{
		$ls_suiche=true;
		$ls_sql="SELECT cl.codcla,cl.dencla,SUM(d.canpro) as cantidad,SUM(d.canpro*d.prepro) as subtotal," .
				"substr(cast (f.fecemi as char(30)),0,11) as fecemi,SUBSTR(cast (f.fecemi as char(30)),6,2) as mes FROM sfc_detfactura d,sfc_producto p,sfc_clasificacion cl,sfc_factura f,sim_articulo a WHERE " .
				"f.codemp=d.codemp AND f.codemp=p.codemp AND f.numfac=d.numfac AND f.codtiend=d.codtiend AND " .
				"f.codtiend=p.codtiend d.codemp=p.codemp AND d.codart=p.codart AND d.codtiend=p.codtiend AND p.codemp=a.codemp AND " .
				"cl.codcla=a.codcla AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'f',$ls_codtie)." AND " .
				"(substr(cast (f.fecemi as char(30)),0,11)>='".$ls_fecemi."' AND substr(cast (f.fecemi as char(30)),0,11)<='".$ls_fecemi2."') GROUP BY cl.codcla,cl.dencla,fecemi " .
				"ORDER BY cl.codcla,fecemi ASC;";


		$ls_sql2="SELECT cl.dencla FROM sfc_detfactura d,sfc_producto p,sfc_clasificacion cl,sim_articulo a WHERE " .
				"d.codemp=p.codemp AND d.codemp=a.codemp AND d.codart=p.codart AND d.codart=a.codart AND " .
				"d.codtiend=p.codtiend AND p.codemp=a.codemp AND p.codart=a.codart AND a.codcla=cl.codcla GROUP by cl.dencla;";

		}
	}else{
	$ls_suiche=true;
	$ls_sql="SELECT cl.codcla,cl.dencla,SUM(d.canpro) as cantidad,SUM(d.canpro*d.prepro) as subtotal FROM " .
			"sfc_detfactura d,sfc_producto p,sfc_clasificacion cl,sfc_factura f,sim_articulo a WHERE f.codemp=d.codemp AND " .
			"f.codemp=p.codemp AND f.numfac=d.numfac AND f.codtiend=d.codtiend AND f.codtiend=p.codtiend AND " .
			"d.codemp=p.codemp AND d.codart=p.codart AND d.codtiend=p.codtiend AND p.codemp=a.codemp AND " .
			"cl.codcla=a.codcla AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'f',$ls_codtie)." AND " .
			"(substr(cast (f.fecemi as char(30)),0,11)>='".$ls_fecemi."' AND substr(cast (f.fecemi as char(30)),0,11)<='".$ls_fecemi2."') GROUP BY cl.codcla,cl.dencla;";
	//print $ls_sql;
	}

	}
	else // si falta colocar una de las dos fechas
	{
		$io_msg->message("Debe introducir el rango completo de la fecha!");
		}
	}

	//print $ls_sql."<br>";
	//print $ls_sql2."<br>";

	if ($ls_suiche==true) //envia datos al reporte detalles
	{

	if ($ls_opcion!="Multiples_Lineas")
	{

	?>
		<script language="JavaScript">
		//alert ('iuouio');
		var ls_sql="<?php print $ls_sql; ?>";

		var ls_fecemi="<?php print $ls_fecemi; ?>";

		var ls_fecemi2="<?php print $ls_fecemi2; ?>";
		var ls_grafico="<?php print $ls_opcion; ?>";

		//alert (ls_grafico);
		pagina="reportes/sigesp_sfc_rep_producto_estadistico.php?sql="+ls_sql+"&fecemi2="+ls_fecemi2+"&fecemi="+ls_fecemi+"&grafico="+ls_grafico;
		popupWin(pagina,"catalogo",580,700);
		</script>
	<?php
		}else{

		?>
		<script language="JavaScript">
		var ls_sql="<?php print $ls_sql; ?>";
		var ls_fecemi="<?php print $ls_fecemi; ?>";
		var ls_fecemi2="<?php print $ls_fecemi2; ?>";
		var ls_grafico="<?php print $ls_opcion; ?>";
		var totcla="<?php print $totrow; ?>";
		//alert (ls_grafico);
		pagina="reportes/sigesp_sfc_rep_producto_estadisticomultiple.php?sql="+ls_sql+"&fecemi2="+ls_fecemi2+"&fecemi="+ls_fecemi+"&grafico="+ls_grafico+"&totcla="+totcla;
		popupWin(pagina,"catalogo",580,700);
		</script>
		<?php
		}
	}
	}
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


function ue_ver()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{
	f.operacion.value="VER";
	f.action="sigesp_sfc_d_rep_productoest.php";
	f.submit();
	}
	else
	{alert("No tiene permiso para realizar esta operaci�n");}
}
function actualizar_combo()
{
	f=document.form1;
	f.combo_ordenarpor.value="VER";
	f.action="sigesp_sfc_d_rep_productoest.php";
	f.submit();
}
function actualizar_option()
{
	f=document.form1;
	f.operacion.value="ue_actualizar_option";
	f.action="sigesp_sfc_d_rep_productoest.php";
	f.submit();

}
function ue_imprimir()
{
	/*f=document.form1;
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
		}*/
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
