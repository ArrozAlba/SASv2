<?Php
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
$la_datemp=$_SESSION["la_empresa"];
$ls_codtie=$_SESSION["ls_codtienda"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>FORMATOS CVA</title>
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
    <td width="523" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1">Sistema de Facturaci&oacute;n</span></td>
    <td width="255" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
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
		<td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript: ue_ver();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
	</tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?Php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	/*require_once("../shared/class_folder/sigesp_c_seguridad.php");
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

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_permisos=$io_seguridad->uf_sss_load_permisossigesp();
		}
		else
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
	}*/

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("class_folder/sigesp_sfc_class_utilidades.php");
require_once("../shared/class_folder/class_datastore.php");
$io_datastore= new class_datastore();
$io_datastore_ent= new class_datastore();
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

	$ls_opcion=$_POST["opcion"];

	}
else
{
	$ls_operacion="";
	$ls_opcion="FORMATO_PRODUCTOR";
	}
if($ls_operacion=="ue_actualizar_option")
	{
		  if ($ls_opcion=="FORMATO_PRODUCTOR"){
		  $ls_opcion="FORMATO_PRODUCTOR";
		  }else if ($ls_opcion=="FORMATO_EXPLOT_AGROP.")
		  {
		  $ls_opcion="FORMATO_EXPLOT_AGROP";
		  }else if ($ls_opcion=="FORMATO_DISTRIBUIDOR_PROVEEDOR"){
		  $ls_opcion="FORMATO_DISTRIBUIDOR_PROVEEDOR";
		  }else if ($ls_opcion=="FORMATO_PRODUCTO"){
		  $ls_opcion="FORMATO_PRODUCTO";
		  }	else if ($ls_opcion=="FORMATO_PRODUCTO_PROVEEDOR"){
		  $ls_opcion="FORMATO_PRODUCTO_PROVEEDOR";
		  }else if ($ls_opcion=="FORMATO_PRODUCTO_UPS"){
		  $ls_opcion="FORMATO_PRODUCTO_UPS";
		  }else if ($ls_opcion=="FORMATO_CONTACTO_PROVEEDOR"){
		  $ls_opcion="FORMATO_CONTACTO_PROVEEDOR";
		  }
		//print $ls_opcion;
	}
?>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
/*if (($ls_permisos)||($ls_logusr=="PSEGIS"))
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
}*/
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
    <table width="518" height="260" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
		<tr>
			<td width="600" height="258"><div align="center">
			  <table width="616"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
					<tr>
						<td colspan="3" class="titulo-ventana">FORMATOS CVA </td>
					</tr>
					<tr>
						<td height="17" colspan="3" class="sin-borde">&nbsp;</td>
					</tr>
					 <tr>
              <td height="8" colspan="2"><table width="483" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                  <tr>
                    <td height="13" colspan="2" align="right" class="titulo-ventana">Tipo de FORMATO</td>
                  </tr>
                  <tr>
						<td height="8">&nbsp;</td>
						<td ><p>
							<label>
							<?php
							 if ($ls_opcion=='FORMATO_PRODUCTOR')
							   {
							   ?>
			                   <input name="opcion" type="radio" value="FORMATO_PRODUCTOR"  checked="checked" onClick="actualizar_option()">FORMATO PRODUCTOR</label>
							   <label>
			                   <input name="opcion" type="radio" value="FORMATO_EXPLOT_AGROP"  onClick="actualizar_option()" >FORMATO EXPLOT. AGROP.</label>
							   <input name="opcion" type="radio" value="FORMATO_DISTRIBUIDOR_PROVEEDOR" onClick="actualizar_option()">FORMATO PROVEEDOR</label>
							   <input name="opcion" type="radio" value="FORMATO_PRODUCTO" onClick=
							   "actualizar_option()" >
			                   FORMATO PRODUCTO</label>
							   <input name="opcion" type="radio" value="FORMATO_PRODUCTO_PROVEEDOR" onClick=
							   "actualizar_option()" >
			                   FORMATO PRODUCTO PROV</label>
							     <input name="opcion" type="radio" value="FORMATO_PRODUCTO_UPS" onClick=
							   "actualizar_option()" >
			                  FORMATO PROD UPS</label>
							  <input name="opcion" type="radio" value="FORMATO_CONTACTO_PROVEEDOR" onClick=
							   "actualizar_option()" >
			                  FORMATO CONTACTO PROVEEDOR</label>

								<?php
								}
								else if ($ls_opcion=='FORMATO_EXPLOT_AGROP')
								{
								// print 'paso';
								?>
								<input name="opcion" type="radio" value="FORMATO_PRODUCTOR"   onClick=
							   "actualizar_option()">FORMATO PRODUCTOR</label>
							   <label>
			                   <input name="opcion" type="radio" value="FORMATO_EXPLOT_AGROP"  checked="checked"  onClick="actualizar_option()" >FORMATO EXPLOT. AGROP.</label>
							   <input name="opcion" type="radio" value="FORMATO_DISTRIBUIDOR_PROVEEDOR" onClick="actualizar_option()">FORMATO PROVEEDOR</label>
							   <input name="opcion" type="radio" value="FORMATO_PRODUCTO" onClick=
							   "actualizar_option()" >
			                   FORMATO PRODUCTO</label>
							   <input name="opcion" type="radio" value="FORMATO_PRODUCTO_PROVEEDOR" onClick=
							   "actualizar_option()" >
			                   FORMATO PRODUCTO PROV</label>
							     <input name="opcion" type="radio" value="FORMATO_PRODUCTO_UPS" onClick=
							   "actualizar_option()" >
			                  FORMATO PROD UPS</label>
							  <input name="opcion" type="radio" value="FORMATO_CONTACTO_PROVEEDOR" onClick=
							   "actualizar_option()" >
			                  FORMATO CONTACTO PROVEEDOR</label>

								<?php
								}else if ($ls_opcion=='FORMATO_DISTRIBUIDOR_PROVEEDOR')
								{
								?>
							<input name="opcion" type="radio" value="FORMATO_PRODUCTOR"   onClick=
							   "actualizar_option()">FORMATO PRODUCTOR</label>
							   <label>
			                   <input name="opcion" type="radio" value="FORMATO_EXPLOT_AGROP"  onClick="actualizar_option()" >FORMATO EXPLOT. AGROP.</label>
							   <input name="opcion" type="radio" value="FORMATO_DISTRIBUIDOR_PROVEEDOR" checked="checked" onClick="actualizar_option()">FORMATO PROVEEDOR</label>
							   <input name="opcion" type="radio" value="FORMATO_PRODUCTO" onClick=
							   "actualizar_option()" >
			                   FORMATO PRODUCTO</label>
							   <input name="opcion" type="radio" value="FORMATO_PRODUCTO_PROVEEDOR" onClick=
							   "actualizar_option()" >
			                   FORMATO PRODUCTO PROV</label>
							     <input name="opcion" type="radio" value="FORMATO_PRODUCTO_UPS" onClick=
							   "actualizar_option()" >
			                  FORMATO PROD UPS</label>
							  <input name="opcion" type="radio" value="FORMATO_CONTACTO_PROVEEDOR" onClick=
							   "actualizar_option()" >
			                  FORMATO CONTACTO PROVEEDOR</label>

								<?php
								}else if ($ls_opcion=='FORMATO_PRODUCTO')
								{
								?>
								<input name="opcion" type="radio" value="FORMATO_PRODUCTOR"   onClick=
							   "actualizar_option()">FORMATO PRODUCTOR</label>
							   <label>
			                   <input name="opcion" type="radio" value="FORMATO_EXPLOT_AGROP"  onClick="actualizar_option()" >FORMATO EXPLOT. AGROP.</label>
							   <input name="opcion" type="radio" value="FORMATO_DISTRIBUIDOR_PROVEEDOR" onClick="actualizar_option()">FORMATO PROVEEDOR</label>
							   <input name="opcion" type="radio" value="FORMATO_PRODUCTO" checked="checked" onClick=
							   "actualizar_option()" >
			                   FORMATO PRODUCTO</label>
							   <input name="opcion" type="radio" value="FORMATO_PRODUCTO_PROVEEDOR" onClick=
							   "actualizar_option()" >
			                   FORMATO PRODUCTO PROV</label>
							     <input name="opcion" type="radio" value="FORMATO_PRODUCTO_UPS" onClick=
							   "actualizar_option()" >
			                  FORMATO PROD UPS</label>
							  <input name="opcion" type="radio" value="FORMATO_CONTACTO_PROVEEDOR" onClick=
							   "actualizar_option()" >
			                  FORMATO CONTACTO PROVEEDOR</label>

								<?php
								}else if ($ls_opcion=='FORMATO_PRODUCTO_PROVEEDOR')
								{
								?>
								<input name="opcion" type="radio" value="FORMATO_PRODUCTOR"   onClick=
							   "actualizar_option()">FORMATO PRODUCTOR</label>
							   <label>
			                   <input name="opcion" type="radio" value="FORMATO_EXPLOT_AGROP"  onClick="actualizar_option()" >FORMATO EXPLOT. AGROP.</label>
							   <input name="opcion" type="radio" value="FORMATO_DISTRIBUIDOR_PROVEEDOR" onClick="actualizar_option()">FORMATO PROVEEDOR</label>
							   <input name="opcion" type="radio" value="FORMATO_PRODUCTO"  onClick=
							   "actualizar_option()" >
			                   FORMATO PRODUCTO</label>
							   <input name="opcion" type="radio" value="FORMATO_PRODUCTO_PROVEEDOR" checked="checked" onClick=
							   "actualizar_option()" >
			                   FORMATO PRODUCTO PROV</label>
							     <input name="opcion" type="radio" value="FORMATO_PRODUCTO_UPS" onClick=
							   "actualizar_option()" >
			                  FORMATO PROD UPS</label>
							  <input name="opcion" type="radio" value="FORMATO_CONTACTO_PROVEEDOR" onClick=
							   "actualizar_option()" >
			                  FORMATO CONTACTO PROVEEDOR</label>

								<?php
								}else if ($ls_opcion=='FORMATO_PRODUCTO_UPS')
								{
								?>
										<input name="opcion" type="radio" value="FORMATO_PRODUCTOR"   onClick=
							   "actualizar_option()">FORMATO PRODUCTOR</label>
							   <label>
			                   <input name="opcion" type="radio" value="FORMATO_EXPLOT_AGROP"  onClick="actualizar_option()" >FORMATO EXPLOT. AGROP.</label>
							   <input name="opcion" type="radio" value="FORMATO_DISTRIBUIDOR_PROVEEDOR" onClick="actualizar_option()">FORMATO PROVEEDOR</label>
							   <input name="opcion" type="radio" value="FORMATO_PRODUCTO"  onClick=
							   "actualizar_option()" >
			                   FORMATO PRODUCTO</label>
							   <input name="opcion" type="radio" value="FORMATO_PRODUCTO_PROVEEDOR" onClick=
							   "actualizar_option()" >
			                   FORMATO PRODUCTO PROV</label>
							     <input name="opcion" type="radio" value="FORMATO_PRODUCTO_UPS" checked="checked" onClick="actualizar_option()" >
			                  FORMATO PROD UPS</label>
							  <input name="opcion" type="radio" value="FORMATO_CONTACTO_PROVEEDOR" onClick=
							   "actualizar_option()" >
			                  FORMATO CONTACTO PROVEEDOR</label>

								<?php
								}else if ($ls_opcion=='FORMATO_CONTACTO_PROVEEDOR')
								{
								?>
										<input name="opcion" type="radio" value="FORMATO_PRODUCTOR"   onClick=
							   "actualizar_option()">FORMATO PRODUCTOR</label>
							   <label>
			                   <input name="opcion" type="radio" value="FORMATO_EXPLOT_AGROP"  onClick="actualizar_option()" >FORMATO EXPLOT. AGROP.</label>
							   <input name="opcion" type="radio" value="FORMATO_DISTRIBUIDOR_PROVEEDOR" onClick="actualizar_option()">FORMATO PROVEEDOR</label>
							   <input name="opcion" type="radio" value="FORMATO_PRODUCTO"  onClick=
							   "actualizar_option()" >
			                   FORMATO PRODUCTO</label>
							   <input name="opcion" type="radio" value="FORMATO_PRODUCTO_PROVEEDOR" onClick=
							   "actualizar_option()" >
			                   FORMATO PRODUCTO PROV</label>
							     <input name="opcion" type="radio" value="FORMATO_PRODUCTO_UPS"  onClick="actualizar_option()" >
			                  FORMATO PROD UPS</label>
							  <input name="opcion" type="radio" value="FORMATO_CONTACTO_PROVEEDOR" checked="checked" onClick="actualizar_option()" >
			                  FORMATO CONTACTO PROVEEDOR</label>
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

						</p></td>
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
if($ls_operacion=="VER")
{

	$ls_operacion="";
	$ls_suiche=false;
	 if ($ls_opcion=='FORMATO_PRODUCTOR')
	 {

		$io_sql_bd=new class_sql($io_connect);

					require_once("../shared/class_folder/class_mensajes.php");
					$msg=new class_mensajes();

					require_once("class_folder/sigesp_sfc_c_formatoscva.php");
					$formatos= new sigesp_sfc_c_formatoscva($io_connect);

   					$ls_sql_entidad="SELECT id_entidad, denominacion from sfc_entidadcrediticia where denominacion not ilike 'Fondas'" .
   									" and denominacion not ilike 'Banco agricola%' order by id_entidad ASC ";
					$result_entidad=$io_sql_bd->select($ls_sql_entidad);
						if($result_entidad===false)
						{
							$msg->message("NO HAY ENTIDADES CREDITICIAS".$io_funciones->uf_convertirmsg($io_sql_bd->message));

						}
						else
						{
							$la_resultado=$io_sql_bd->obtener_datos($result_entidad);
							$io_datastore->data=$la_resultado;
							$total=$io_datastore->getRowCount("id_entidad");
							$ls_campos="";

							for($e=1;$e<=$total;$e++)
							{
									$ls_id=$io_datastore->getValue("id_entidad",$e);
									$ls_entidades[$ls_id]=$io_datastore->getValue("denominacion",$e);
									//print $ls_id."--ENT<br>";
									$ls_campos.="cant".$ls_id." double precision Default 0, prepro".$ls_id." double precision Default 0," .
											   " venta".$ls_id." double precision Default 0,";
									$ls_suma.="SUM(cant".$ls_id.") as cant".$ls_id.",SUM(venta".$ls_id.") as venta".$ls_id.",";
									//$ls_denominacion.=$ls_entidades."--";
									$ls_numcero.="0,0,0,";
									$ls_cant[$e]="cant".$ls_id;
									$ls_venta[$e]="venta".$ls_id;
							}
						}


					$lb_inserto=false;
					$lb_creotabla=$formatos->crear_tabla($ls_campos);
/*print_r($ls_entidades);
exit;*/
				if ($lb_creotabla)
					{

						/********************* Ventas Productor Fondas ****************/
						$ls_sql_prfondas="INSERT into temporalcoloca(dencla, den_sun, codpro, denpro, deunimed, cantf, preprof, ventaf) (" .
								"select dencla,den_sub,codpro,denpro,denunimed,SUM(cantidad) as cantidad,prepro," .
								"(SUM(cantidad)*prepro + (SUM(cantidad)*prepro*porimp/100)) as venta  from " .
								"( SELECT cl.dencla,sf.den_sub,p.codpro,p.denpro, d.canpro as cantidad,d.prepro,d.porimp," .
								"u.denunimed FROM sfc_detfactura d,sfc_producto p,sfc_clasificacion cl,sfc_factura f," .
								"sfc_subclasificacion sf,sim_articulo a,sfc_productor pr, sfc_cliente c, sfc_instpago ip," .
								"sim_unidadmedida u,sfc_entidadcrediticia e " .
								" WHERE p.codcla=cl.codcla AND p.cod_sub=sf.cod_sub AND p.codpro=a.codart AND p.codemp=a.codemp" .
								" AND p.codpro=d.codpro	AND p.codemp=d.codemp AND sf.codcla=cl.codcla AND a.codart=d.codpro" .
								" AND f.codemp=d.codemp AND f.numfac=d.numfac AND f.codcli=pr.codcli AND f.codcli=c.codcli" .
								" AND f.numfac=ip.numfac AND d.numfac=ip.numfac AND pr.codcli=c.codcli " .
								" AND pr.codemp=c.codemp AND pr.codcli=ip.codcli AND c.codcli=ip.codcli" .
								" AND f.estfaccon<>'A' " .
								" AND (f.fecemi>='2009-05-01' AND f.fecemi<='2009-07-06') AND a.codunimed=u.codunimed" .
								" AND ip.codforpag='04' and ip.id_entidad=e.id_entidad AND c.cedcli not like 'G%' AND e.denominacion ilike 'Fondas' " .
								") productor_fon " .
								"GROUP BY codpro,denpro,prepro,porimp,dencla,den_sub,denunimed ORDER BY dencla,den_sub,denpro ASC)";

								//print $ls_sql_prfondas;


	 					$result_prfondas=$io_sql_bd->execute($ls_sql_prfondas);
						if($result_prfondas===false)
						{
							$msg->message("Error FONDAS".$io_funciones->uf_convertirmsg($io_sql_bd->message));

						}
						else
						{
							$msg->message("Inserto FONDAS");
							$lb_inserto=true;
						}

						/*******************************************************************************/


						/********************* Ventas Productor BAV ****************/

						$ls_sql_prbav="INSERT into temporalcoloca(dencla, den_sun, codpro, denpro, deunimed, cantb, preprob, ventab) (" .
								"select dencla,den_sub,codpro,denpro,denunimed,SUM(cantidad) as cantidad,prepro," .
								"(SUM(cantidad)*prepro + (SUM(cantidad)*prepro*porimp/100)) as venta  from" .
								"( SELECT cl.dencla,sf.den_sub,p.codpro,p.denpro, d.canpro as cantidad,d.prepro,d.porimp," .
								"u.denunimed FROM sfc_detfactura d,sfc_producto p,sfc_clasificacion cl,sfc_factura f," .
								"sfc_subclasificacion sf,sim_articulo a,sfc_productor pr, sfc_cliente c, sfc_instpago ip," .
								"sim_unidadmedida u,scb_banco b,sfc_formapago fp" .
								" WHERE p.codcla=cl.codcla AND p.cod_sub=sf.cod_sub AND p.codpro=a.codart AND p.codemp=a.codemp" .
								" AND p.codpro=d.codpro	AND p.codemp=d.codemp AND sf.codcla=cl.codcla AND a.codart=d.codpro" .
								" AND f.codemp=d.codemp AND f.numfac=d.numfac AND f.codcli=pr.codcli AND f.codcli=c.codcli" .
								" AND f.numfac=ip.numfac AND d.numfac=ip.numfac AND pr.codcli=c.codcli AND pr.codemp=c.codemp" .
								" AND pr.codcli=ip.codcli AND c.codcli=ip.codcli " .
								" AND f.estfaccon<>'A' AND (f.fecemi>='2009-05-01' AND f.fecemi<='2009-07-06')" .
								" AND a.codunimed=u.codunimed AND b.nomban ilike 'BANCO AGRICOLA DE VENEZUELA'" .
								" AND b.codban=ip.codban  and fp.denforpag ilike 'cheque' and ip.codforpag=fp.codforpag" .
								" AND c.cedcli not like 'G%' " .
								" )	 productor_BAV " .
								" GROUP BY codpro,denpro,prepro,porimp,dencla,den_sub,denunimed ORDER BY dencla,den_sub,denpro ASC)";


						//	print $ls_sql_prbav;

	 					$result_prbav=$io_sql_bd->execute($ls_sql_prbav);
						if($result_prbav===false)
						{
							$msg->message("Error BAV".$io_funciones->uf_convertirmsg($io_sql_bd->message));

						}
						else
						{
							$msg->message("Inserto BAV");
							$lb_inserto=true;
						}
						/*******************************************************************************/

						/********************* PRODUCTORES  OTRAS ENTIDADES CREDITICIAS  **************************/

						for($e=1;$e<=$total;$e++)
							{
								$ls_id=$io_datastore->getValue("id_entidad",$e);
								$ls_denominacion=$io_datastore->getValue("denominacion",$e);

print $ls_id."--".$ls_denominacion."---ENTIDAD<br>";
								$ls_sql_ent="INSERT into temporalcoloca (dencla, den_sun, codpro, denpro, deunimed,cant".$ls_id.", prepro".$ls_id."," .
								" venta".$ls_id.") (" .
								"select dencla,den_sub,codpro,denpro,denunimed,SUM(cantidad) as cantidad,prepro," .
								"(SUM(cantidad)*prepro + (SUM(cantidad)*prepro*porimp/100)) as venta from " .
								"( SELECT cl.dencla,sf.den_sub,p.codpro,p.denpro, d.canpro as cantidad,d.prepro,d.porimp," .
								"u.denunimed FROM sfc_detfactura d,sfc_producto p,sfc_clasificacion cl,sfc_factura f," .
								"sfc_subclasificacion sf,sim_articulo a,sfc_productor pr, sfc_cliente c, sfc_instpago ip," .
								"sim_unidadmedida u " .
								" WHERE p.codcla=cl.codcla AND p.cod_sub=sf.cod_sub AND p.codpro=a.codart AND p.codemp=a.codemp" .
								" AND p.codpro=d.codpro	AND p.codemp=d.codemp AND sf.codcla=cl.codcla AND a.codart=d.codpro" .
								" AND f.codemp=d.codemp AND f.numfac=d.numfac AND f.codcli=pr.codcli AND f.codcli=c.codcli" .
								" AND f.numfac=ip.numfac AND d.numfac=ip.numfac AND pr.codcli=c.codcli " .
								" AND pr.codemp=c.codemp AND pr.codcli=ip.codcli AND c.codcli=ip.codcli" .
								" AND f.estfaccon<>'A' " .
								" AND (f.fecemi>='2009-05-01' AND f.fecemi<='2009-07-06') AND a.codunimed=u.codunimed" .
								" AND ip.codforpag='04' and ip.id_entidad=".$ls_id." AND c.cedcli not like 'G%'" .
								") productor_fon " .
								"GROUP BY codpro,denpro,prepro,porimp,dencla,den_sub,denunimed ORDER BY dencla,den_sub,denpro ASC)";
print $ls_sql_ent."<br>";
								$result_ent=$io_sql_bd->execute($ls_sql_ent);
								if($result_ent===false)
								{
									$msg->message("Error ENTIDAD CREDITICIA".$io_funciones->uf_convertirmsg($io_sql_bd->message));

								}
								else
								{
									$msg->message("Inserto ENTIDAD CREDITICIA".$ls_denominacion);
									$lb_inserto=true;
								}



							}
//exit;

					/*************************** Ventas PRODUCTORES Y NO PRODUCTORES CON Convenios  ************************************/


				$ls_sql_prconc="INSERT into temporalcoloca (dencla, den_sun, codpro, denpro, deunimed,cantc, preproc,ventac)(" .
							"select dencla,den_sub,codpro,denpro,denunimed,SUM(cantidad) as cantidad,prepro," .
							"(SUM(cantidad)*prepro + (SUM(cantidad)*prepro*porimp/100)) as venta from " .
							"(( SELECT cl.dencla,sf.den_sub,p.codpro,p.denpro, d.canpro as cantidad,d.prepro,d.porimp,u.denunimed" .
							"	FROM sfc_detfactura d,sfc_producto p,sfc_clasificacion cl,sfc_factura f,sfc_subclasificacion sf,sim_articulo a," .
							"	sfc_productor pr, sfc_cliente c, sfc_instpago ip,sim_unidadmedida u" .
							"	WHERE p.codcla=cl.codcla AND p.cod_sub=sf.cod_sub AND p.codpro=a.codart AND p.codemp=a.codemp AND p.codpro=d.codpro" .
							"	AND p.codemp=d.codemp AND sf.codcla=cl.codcla AND a.codart=d.codpro AND f.codemp=d.codemp AND f.numfac=d.numfac" .
							"	AND f.codcli=pr.codcli AND f.codcli=c.codcli AND f.numfac=ip.numfac AND d.numfac=ip.numfac AND pr.codcli=c.codcli" .
							"	AND pr.codemp=c.codemp AND pr.codcli=ip.codcli AND c.codcli=ip.codcli AND " .
							"   f.estfaccon<>'A' AND (f.fecemi>='2009-05-01' AND f.fecemi<='2009-05-14')" .
							"   AND a.codunimed=u.codunimed	 AND c.cedcli like 'G%' AND ip.codforpag<>'04'" .
							")" .
							" UNION ALL" .
							" (SELECT cl.dencla,sf.den_sub,p.codpro,p.denpro,d.canpro as cantidad,d.prepro,d.porimp,u.denunimed" .
							"	FROM sfc_detfactura d,sfc_producto p,sfc_clasificacion cl,sfc_factura f,sfc_subclasificacion sf,sim_articulo a," .
							"	sfc_cliente c, sfc_instpago ip,sim_unidadmedida u" .
							"	WHERE p.codcla=cl.codcla AND p.cod_sub=sf.cod_sub AND p.codpro=a.codart AND p.codemp=a.codemp AND p.codpro=d.codpro" .
							"	AND p.codemp=d.codemp AND sf.codcla=cl.codcla AND a.codart=d.codpro AND f.codemp=d.codemp AND f.numfac=d.numfac" .
							"	AND f.codcli=c.codcli AND f.numfac=ip.numfac AND d.numfac=ip.numfac AND c.codcli=ip.codcli" .
							"   AND c.codcli NOT in (select codcli from sfc_productor)" .
							"   AND f.estfaccon<>'A'" .
							"   AND (f.fecemi>='2009-05-01' AND f.fecemi<='2009-07-06') AND a.codunimed=u.codunimed	 AND c.cedcli like 'G%'" .
							")) re" .
							"	GROUP BY codpro,denpro,prepro,porimp,dencla,den_sub,denunimed ORDER BY dencla,den_sub,denpro ASC" .
							")";

						$result_prconc=$io_sql_bd->execute($ls_sql_prconc);
						if($result_prconc===false)
						{
							$msg->message("Error CONVENIOS".$io_funciones->uf_convertirmsg($io_sql_bd->message));

						}
						else
						{
							$msg->message("Inserto CONVENIOS");
							$lb_inserto=true;
						}


					/***************************************************************************************/


				/******************************** Ventas COOPERATIVAS ***********************************************/

				$ls_sql_coo="INSERT into temporalcoloca (dencla, den_sun, codpro, denpro, deunimed,cantcop, preprocop,ventacop) (" .
								"select dencla,den_sub,codpro,denpro,denunimed,SUM(cantidad) as cantidad,prepro," .
								"(SUM(cantidad)*prepro + (SUM(cantidad)*prepro*porimp/100)) as venta from" .
								" (SELECT cl.dencla,sf.den_sub,p.codpro,p.denpro, d.canpro as cantidad,d.prepro,d.porimp," .
								"u.denunimed" .
								" FROM sfc_detfactura d,sfc_producto p,sfc_clasificacion cl,sfc_factura f,sfc_subclasificacion sf,sim_articulo a," .
								" sfc_cliente c, sfc_instpago ip,sim_unidadmedida u,scb_banco b" .
								" WHERE p.codcla=cl.codcla AND p.cod_sub=sf.cod_sub AND p.codpro=a.codart AND p.codemp=a.codemp" .
								" AND p.codpro=d.codpro	AND p.codemp=d.codemp AND sf.codcla=cl.codcla AND a.codart=d.codpro AND f.codemp=d.codemp" .
								" AND f.numfac=d.numfac	AND f.codcli=c.codcli AND f.numfac=ip.numfac AND d.numfac=ip.numfac" .
								" AND c.codcli=ip.codcli AND (c.razcli ilike 'COOP%' or c.razcli ilike 'ASOC%' )" .
								" AND f.estfaccon<>'A' AND" .
								" (f.fecemi>='2009-05-01' AND f.fecemi<='2009-07-06') AND a.codunimed=u.codunimed " .
								" AND ip.codforpag<>'04' AND b.nomban not ilike 'BANCO AGRICOLA DE VENEZUELA'" .
								") re GROUP BY codpro,denpro,prepro,porimp,dencla,den_sub,denunimed ORDER BY dencla,den_sub,denpro ASC)";
//print $ls_sql_coo;
						$result_coo=$io_sql_bd->execute($ls_sql_coo);
						if($result_coo===false)
						{
							$msg->message("Error COOPERATIVAS".$io_funciones->uf_convertirmsg($io_sql_bd->message));

						}
						else
						{
							$msg->message("Inserto COOPERATIVAS");
							$lb_inserto=true;
						}

					/**************************************************************************************/


					/******************************** Ventas PRODUCTORES Particulares ***********************************************/

				$ls_sql_prsinc="INSERT into temporalcoloca (dencla, den_sun, codpro, denpro, deunimed,cantp, preprop,ventap) (" .
								"select dencla,den_sub,codpro,denpro,denunimed,SUM(cantidad) as cantidad,prepro," .
								"(SUM(cantidad)*prepro + (SUM(cantidad)*prepro*porimp/100)) as venta from" .
								"( SELECT cl.dencla,sf.den_sub,p.codpro,p.denpro, d.canpro as cantidad,d.prepro,d.porimp," .
								"u.denunimed" .
								" FROM sfc_detfactura d,sfc_producto p,sfc_clasificacion cl,sfc_factura f,sfc_subclasificacion sf,sim_articulo a," .
								" sfc_productor pr, sfc_cliente c, sfc_instpago ip,sim_unidadmedida u,scb_banco b" .
								" WHERE p.codcla=cl.codcla AND p.cod_sub=sf.cod_sub AND p.codpro=a.codart AND p.codemp=a.codemp" .
								" AND p.codpro=d.codpro	AND p.codemp=d.codemp AND sf.codcla=cl.codcla AND a.codart=d.codpro AND f.codemp=d.codemp" .
								" AND f.numfac=d.numfac	AND f.codcli=pr.codcli AND f.codcli=c.codcli AND f.numfac=ip.numfac AND d.numfac=ip.numfac" .
								" AND pr.codcli=c.codcli AND pr.codemp=c.codemp AND pr.codcli=ip.codcli AND c.codcli=ip.codcli" .
								" AND f.estfaccon<>'A' AND" .
								" (f.fecemi>='2009-05-01' AND f.fecemi<='2009-07-06') AND a.codunimed=u.codunimed AND c.cedcli not like 'G%'" .
								" AND ip.codforpag<>'04' AND b.nomban not ilike 'BANCO AGRICOLA DE VENEZUELA'" .
								" AND b.codban=ip.codban AND (c.razcli not ilike 'COOP%' or c.razcli not ilike 'ASOC%' )" .
								") re GROUP BY codpro,denpro,prepro,porimp,dencla,den_sub,denunimed ORDER BY dencla,den_sub,denpro ASC)";

						$result_prsinc=$io_sql_bd->execute($ls_sql_prsinc);
						if($result_prsinc===false)
						{
							$msg->message("Error SIN CONVENIOS".$io_funciones->uf_convertirmsg($io_sql_bd->message));

						}
						else
						{
							$msg->message("Inserto SIN CONVENIOS");
							$lb_inserto=true;
						}

				/**************************************************************************************/


				/******************************** Ventas Otros***********************************************/

				$ls_sql_part="INSERT into temporalcoloca (dencla, den_sun, codpro, denpro, deunimed,canto, preproo,ventao)(" .
								"select dencla,den_sub,codpro,denpro,denunimed,SUM(cantidad) as cantidad,prepro," .
								"(SUM(cantidad)*prepro + (SUM(cantidad)*prepro*porimp/100)) as venta from" .
								"((SELECT cl.dencla,sf.den_sub,p.codpro,p.denpro,d.canpro as cantidad,d.prepro,d.porimp,u.denunimed" .
								" FROM sfc_detfactura d,sfc_producto p,sfc_clasificacion cl,sfc_factura f,sfc_subclasificacion sf,sim_articulo a," .
								" sfc_cliente c, sfc_instpago ip,sim_unidadmedida u" .
								" WHERE p.codcla=cl.codcla AND p.cod_sub=sf.cod_sub AND p.codpro=a.codart AND p.codemp=a.codemp AND p.codpro=d.codpro" .
								" AND p.codemp=d.codemp AND sf.codcla=cl.codcla AND a.codart=d.codpro AND f.codemp=d.codemp AND f.numfac=d.numfac " .
								" AND f.codcli=c.codcli AND f.numfac=ip.numfac AND d.numfac=ip.numfac AND c.codcli=ip.codcli" .
								" AND c.codcli NOT in (select codcli from sfc_productor) AND" .
								" f.estfaccon<>'A' AND" .
								" (f.fecemi>='2009-05-01' AND f.fecemi<='2009-07-06') AND a.codunimed=u.codunimed AND c.cedcli not like 'G%'" .
								" AND ip.codforpag<>'04' AND (c.razcli ilike 'COOP%' or c.razcli ilike 'ASOC%' )" .
								")) re" .
								" GROUP BY codpro,denpro,prepro,porimp,dencla,den_sub,denunimed	ORDER BY dencla,den_sub,denpro ASC)";
//print $ls_sql_part;
						$result_part=$io_sql_bd->execute($ls_sql_part);
						if($result_par===false)
						{
							$msg->message("Error Particulares".$io_funciones->uf_convertirmsg($io_sql_bd->message));

						}
						else
						{
							$msg->message("Inserto particulares");
							$lb_inserto=true;
						}

					/**************************************************************************************/



				}
				else
					{
						$msg->message("No pudo crear la Tabla, contacte al administrador del sistema");
					}


//print "PASOOO";
	 ?>
	<script language="JavaScript">
		var lb_inserto="<?php print $lb_inserto; ?>";
		var li_suma="<?php print $ls_suma; ?>";
		var ls_entidades="<?php print implode("-",$ls_entidades); ?>";

		var ls_cant="<?php print implode("-",$ls_cant); ?>";
		var ls_venta="<?php print implode("-",$ls_venta); ?>";

		var total="<?php print $total; ?>";

		pagina="reportes/sigesp_sfc_rep_colocacionventasproductor_excel.php?lb_inserto="+lb_inserto+"&li_suma="+li_suma+"&ls_cant="+ls_cant+"&ls_venta="+ls_venta+"&total="+total+"&ls_entidades="+ls_entidades;


		popupWin(pagina,"catalogo",580,700);
		</script>
	<?PHP
	 }
	elseif ($ls_opcion=='FORMATO_EXPLOT_AGROP')
	{

?>
     <script language="JavaScript">


		pagina="reportes/sigesp_sfc_rep_colocacionlineas_excel.php?";
	  	popupWin(pagina,"catalogo",580,700);
     </script>

	<?PHP
	}
	elseif ($ls_opcion=='FORMATO_DISTRIBUIDOR_PROVEEDOR')
	{
	?>
		<script language="JavaScript">
		pagina="reportes/sigesp_sfc_rep_ventas_x_lineas_excel.php";
		popupWin(pagina,"catalogo",580,700);
		</script>
	<?PHP
	}
	elseif ($ls_opcion=='FORMATO_PRODUCTO')
	{
	$ls_sql="SELECT p.denpro as nombre_producto,c.dencla as tipo_producto,um.denunimed as ".
		" tipo_presentacion_producto,p.preven1 as precio_unitario_producto,'No Aplica' as ".
		" observacion_producto FROM sfc_producto p,sim_articulo a,sim_unidadmedida um,sfc_clasificacion c ".
		" WHERE p.codart=a.codart and p.codart=a.codart and a.codunimed=um.codunimed and c.codcla=p.codcla ORDER BY nombre_producto;";
print $ls_sql;
	?>
	<script language="JavaScript">
		var ls_sql="<?php print $ls_sql; ?>";
		pagina="reportes/sigesp_sfc_rep_formato_producto.php?sql="+ls_sql;
		popupWin(pagina,"catalogo",580,700);
		</script>
	<?PHP
	}
	elseif ($ls_opcion=='FORMATO_PRODUCTO_PROVEEDOR')
	{
	$ls_sql="SELECT rpc.nompro as nombre,a.denart as articulo FROM soc_dtcot_bienes dc,rpc_proveedor rpc,".
	 " sim_articulo a WHERE dc.cod_pro=rpc.cod_pro AND dc.codart=a.codart GROUP BY rpc.nompro,a.denart ".
	" ORDER BY a.denart;";
	?>
	<script language="JavaScript">
		var ls_sql="<?php print $ls_sql; ?>";
		pagina="reportes/sigesp_sfc_rep_formato_producto_proveedor.php?sql="+ls_sql;
		popupWin(pagina,"catalogo",580,700);
		</script>
	<?PHP
	}
	elseif ($ls_opcion=='FORMATO_PRODUCTO_UPS')
	{
	?>
	<script language="JavaScript">
		var ls_sql="<?php print $ls_sql; ?>";
		pagina="reportes/sigesp_sfc_rep_formato_producto_ups.php?sql="+ls_sql;
		popupWin(pagina,"catalogo",580,700);
		</script>
	<?PHP
	}
	elseif ($ls_opcion=='FORMATO_CONTACTO_PROVEEDOR')
	{
	$ls_sql="SELECT substr(rpc.cedrep,0,10) as cedula_contacto,rpc.nomreppro as nombre_contacto, ".
" 'No Aplica' as nacionalidad,'No Aplica' as sexo,rpc.carrep as cargo_contacto,p.despai as pais,".
" e.desest as estado,m.denmun as municipio,pa.denpar as parroquia,rpc.dirpro as direccion_contacto,".
" CASE WHEN (substr(rpc.telpro,0,4))='025' THEN rpc.telpro ELSE 'NO APLICA'".
" END AS telefono_fijo,CASE WHEN (substr(rpc.telpro,0,4))='041' THEN rpc.telpro ELSE 'NO APLICA'".
" END AS telefono_movil,rpc.faxpro as telefono_fax,rpc.emailrep as correo_contacto ".
" FROM rpc_proveedor rpc,sigesp_pais p,sigesp_municipio m,sigesp_estados e,sigesp_parroquia pa WHERE".
" p.codpai=e.codpai and p.codpai=m.codpai and p.codpai=pa.codpai and e.codest=m.codest and e.codest=pa.codest ".
" and m.codmun=pa.codmun and p.codpai=rpc.codpai and e.codest=rpc.codest and m.codmun=rpc.codmun and ".
" pa.codpar=rpc.codpar AND rpc.cedrep<>'' and rpc.nomreppro <>'';";
	?>
	<script language="JavaScript">
		var ls_sql="<?php print $ls_sql; ?>";
		pagina="reportes/sigesp_sfc_rep_formato_contacto_proveedor.php?sql="+ls_sql;
		popupWin(pagina,"catalogo",580,700);
		</script>
	<?PHP


	}
	}
?>
</body>
<script language="JavaScript">

function ue_ver()
{
	f=document.form1;

	f.operacion.value="VER";
	f.action="sigesp_sfc_d_rep_formatos_cva.php";
	f.submit();

}
function actualizar_combo()
{
	f=document.form1;
	f.combo_ordenarpor.value="VER";
	f.action="sigesp_sfc_d_rep_formatos_cva.php";
	f.submit();
}
function actualizar_option()
{
	f=document.form1;
	f.operacion.value="ue_actualizar_option";
	f.action="sigesp_sfc_d_rep_formatos_cva.php";
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
