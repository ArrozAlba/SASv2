<?php
/******************************************/
/* FECHA: 27/09/2007                      */
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
$ls_nomtienda=$_SESSION["ls_nomtienda"];
$ls_codest=$_SESSION["ls_codest"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Gestion de Ventas</title>
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
    <td width="450" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1">Sistema de Facturaci&oacute;n</span></td>
    <td width="328" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">&nbsp;</td>
    <td height="20" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="cd-menu">
	<script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script>	</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript: ue_ver();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Buscar" width="20" height="20" border="0"><a href="javascript:ue_cancelar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SFC";
	$ls_ventanas="sigesp_sfc_d_rep_gestion.php";

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
$io_in=new sigesp_include();
$con=$io_in->uf_conectar();
require_once("../shared/class_folder/class_datastore.php");
$io_ds=new class_datastore();
$io_data=new class_datastore();
require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_funciones.php");
$io_funcion=new class_funciones();
require_once("../shared/class_folder/grid_param.php");
$grid=new grid_param();
$la_emp=$_SESSION["la_empresa"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
}
else
{
	$ls_operacion="";
}
$ld_fecdesde=$_POST["txtdesde"];
$ld_fechasta=$_POST["txthasta"];

	$ls_tienda_desde = $_POST["txtcodtienda_desde"];
	$ls_tienda_hasta = $_POST["txtcodtienda_hasta"];

?>

</div>
<p>&nbsp;</p>
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
 <table width="518" height="230" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
		<tr>
			<td width="502"><div align="center">

  <table width="502" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="502" colspan="2" class="titulo-ventana">Reporte de Gesti&oacute;n de Ventas </td>
    </tr>
  </table>
  <input name="hidstatus" type="hidden" id="hidstatus">
  <table width="502" height="168" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="77"></td>
    </tr>
    <tr>
      <td colspan="3" align="center">
        <div align="left"></div></td>
    </tr>
    <tr>
      <td height="33" colspan="3" align="center">      <div align="left">
        <table width="477" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">

                    <?php
					if ($ls_codtie == '0001') {

					?>

                   <input type="hidden" name="hdnagrotienda" value=""/>

					<tr>
		                <td width="110" height="32" align="right">Desde Unidad Operativa de Suministro:</td>
		                <td colspan="3" >

		                <input name="txtdentienda_desde" type="text" id="txtdentienda_desde" size="30">
		                <input name="txtcodtienda_desde" type="hidden" id="txtcodtienda_desde" size="30">
		                <a href="javascript: ue_buscar_tienda('desde');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
					</tr>

					<tr>
		                <td width="110" align="right">Hasta Unidad Operativa de Suministro:</td>
		                <td colspan="3" >
		                <input name="txtdentienda_hasta" type="text" id="txtdentienda_hasta" size="30">
		                <input name="txtcodtienda_hasta" type="hidden" id="txtcodtienda_hasta" size="30">
		                <a href="javascript: ue_buscar_tienda('hasta');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
					</tr>


					<?php
					}
					?>

          <tr>
            <td colspan="2"  ><strong>Intervalo de Fechas </strong></td>

            <td width="172">&nbsp;</td>
            <td width="82">&nbsp;</td>
          </tr>

          <tr>
            <td width="110"><div align="right">Desde</div></td>
            <td width="82"><input name="txtdesde" type="text" id="txtdesde" size="15"  onKeyPress="ue_separadores(this,'/',patron,true);"  datepicker="true"></td>
            <td><div align="right">Hasta</div></td>
            <td><div align="left">
                <input name="txthasta" type="text" id="txthasta" size="15"  onKeyPress="ue_separadores(this,'/',patron,true);"  datepicker="true">
            </div></td>
            <td width="29">&nbsp;</td>
          </tr>
        </table>
      </div></td>
    </tr>

    <tr>
      <td colspan="3" align="center">
        <div align="center">
          <p><span class="Estilo1">
          </span></p>
      </div></td>
    </tr>
  </table>
  </td>
  </tr>
 </table>
  <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
  <div align="left"></div>
  <p align="center">
<input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
</p>
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

	$ld_fecdesde=substr($ld_fecdesde,6,4).'-'.substr($ld_fecdesde,3,2).'-'.substr($ld_fecdesde,0,2);
$ld_fechasta=substr($ld_fechasta,6,4).'-'.substr($ld_fechasta,3,2).'-'.substr($ld_fechasta,0,2);
//print $ld_fechasta;
		//-------------------------------TOTAL CLIENTES QUE COMPRAR�N-----------------------------------
		$ls_sql1="SELECT COUNT(DISTINCT f.codcli) as totcli FROM".
		" sfc_factura f WHERE (substr(cast (f.fecemi as char(30)),0,11) BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."') AND f.estfaccon<>'A' AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'f',$ls_codtie)." ;";
		//print $ls_sql1;
		//----------------------------------------------------------------------------------------------
		//-------------------------------TOTAL CLIENTES QUE NO COMPRAR�N--------------------------------
		$ls_sql2="SELECT COUNT(DISTINCT codcli) as totcli FROM sfc_cotizacion WHERE ".
		"numcot<>'0000000000000000000000000' AND (substr(feccot,0,11) BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."') AND ".
		"estcot<>'F' AND numcot NOT IN (SELECT numcot from sfc_factura) AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'sfc_cotizacion',$ls_codtie)."; ";
		//print $ls_sql2;
		//----------------------------------------------------------------------------------------------
		//-------------------------TOTAL CLIENTES QUE COMPRAR�N EN EFECTIVO-----------------------------
		$ls_sql3="SELECT COUNT(DISTINCT f.codcli) as totcli FROM sfc_factura f".
		" WHERE (f.conpag='1' AND f.estfaccon='C') AND (substr(cast (f.fecemi as char(30)),0,11) BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."') AND ".
		" f.estfaccon<>'A' AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'f',$ls_codtie)." ;";
		//print $ls_sql3;
		//----------------------------------------------------------------------------------------------
		//-------------------------TOTAL CLIENTES QUE COMPRAR�N A CREDITO------------------------------
		$ls_sql4="SELECT COUNT(DISTINCT f.codcli) as totcli FROM sfc_factura f".
		" WHERE (f.conpag BETWEEN '2' AND '4') AND ".
		" (substr(cast (f.fecemi as char(30)),0,11) BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."') AND f.estfaccon<>'A' AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'f',$ls_codtie)." ; ";
		//print $ls_sql4;
		//----------------------------------------------------------------------------------------------
		//-------------------------------------TOTAL MUNICIPIOS-----------------------------------------
		$ls_sql5="SELECT s.codmun,s.denmun FROM sigesp_municipio s WHERE s.codest='".$ls_codest."'";
		//print $ls_sql5;
		//----------------------------------------------------------------------------------------------
		//-------------------------------------TOTAL TENENCIAS------------------------------------------
		$ls_sql6="SELECT DISTINCT(p.codtenencia) as codigo,MAX(p.codcli) as codcli FROM sfc_productor p WHERE ".
		"p.codtenencia<>''and p.codcli IN (select f.codcli from sfc_factura f WHERE (substr(cast (f.fecemi as char(30)),0,11)>='".$ld_fecdesde."') AND ".
		"(substr(cast (f.fecemi as char(30)),0,11)<='".$ld_fechasta."') AND f.estfaccon<>'A'  AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'f',$ls_codtie).") GROUP BY p.codtenencia";
		//print $ls_sql6;
		//----------------------------------------------------------------------------------------------
		//-------------------------------------TOTAL ENTIDADES CREDITICIAS------------------------------
		$ls_sql7="SELECT id_entidad,denominacion FROM sfc_entidadcrediticia";
		//print $ls_sql7;
		//----------------------------------------------------------------------------------------------
		//-------------------------------------TOTAL HAS RUBROS AGRICOLAS-------------------------------
	$ls_sql8="SELECT cla.id_clasificacion,cla.denominacion,sum(rc.hect_prod) as thas,sum(rc.cant_pro) as tprod ".
			"FROM sfc_clasificacionrubro cla,sfc_rubroagri_cliente rc WHERE ".
			"cla.id_clasificacion=rc.id_clasificacion AND rc.codcli IN (SELECT codcli FROM sfc_factura ".
			"WHERE estfaccon<>'A' AND (substr(fecemi,0,11)>='".$ld_fecdesde."' AND substr(fecemi,0,11)<='".$ld_fechasta."' AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'sfc_factura',$ls_codtie)."))".
			" GROUP BY cla.id_clasificacion,cla.denominacion ORDER BY cla.denominacion;";
		//print $ls_sql8;
		//----------------------------------------------------------------------------------------------
		//-------------------------------------TOTAL HAS RUBROS PECUARIOS-------------------------------
		$ls_sql9="SELECT cla.id_clasificacion,cla.denominacion,r.denominacion as rubro,".
		"sum(rc.hect_prod) as hectprorp,sum(rc.cant_pro) as tcantrp,SUM(rc.nro_animales) as tnro_animal".
		" FROM sfc_rubro r,sfc_clasificacionrubro cla,sfc_rubropec_cliente rc WHERE r.id_rubro=cla.id_rubro".
		" AND cla.id_clasificacion=rc.id_clasificacion AND rc.codcli IN (SELECT codcli FROM sfc_factura ".
		" WHERE estfaccon<>'A' AND (substr(fecemi,0,11)>='".$ld_fecdesde."' AND substr(fecemi,0,11)<='".$ld_fechasta."' AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'sfc_factura',$ls_codtie)."))".
		" GROUP BY cla.id_clasificacion,cla.denominacion,r.denominacion ORDER BY cla.denominacion;";
		//print $ls_sql9;
		//-------------------------------------TIPOS DE USOS-----------------------------------------
		$ls_sql10="SELECT t.id_tipouso,t.dentipouso FROM sfc_uso s,sfc_tipouso t,sfc_producto p,sim_articulo a".
		" WHERE t.id_tipouso=s.id_tipouso AND a.id_uso=s.id_uso AND a.codart=p.codart AND p.codart IN (SELECT df.codart FROM ".
		" sfc_detfactura df,sfc_factura f where f.numfac=df.numfac AND f.estfaccon<>'A' AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'f',$ls_codtie).") GROUP BY t.id_tipouso,t.dentipouso ORDER BY t.id_tipouso";
		//print $ls_sql10;
		//----------ACA KEDEEEE------------------------------------------------------------------------------------
?>
     <script language="JavaScript">
   	 	var ls_sql1="<?php print $ls_sql1; ?>";
		var ls_sql2="<?php print $ls_sql2; ?>";
		var ls_sql3="<?php print $ls_sql3; ?>";
		var ls_sql4="<?php print $ls_sql4; ?>";
		var ls_sql5="<?php print $ls_sql5; ?>";
		var ls_sql6="<?php print $ls_sql6; ?>";
		var ls_sql7="<?php print $ls_sql7; ?>";
		var ls_sql8="<?php print $ls_sql8; ?>";
		var ls_sql9="<?php print $ls_sql9; ?>";
		var ls_sql10="<?php print $ls_sql10; ?>";
		var ld_fecdesde="<?php print $ld_fecdesde; ?>";
		var ld_fehasta="<?php print $ld_fechasta; ?>";
		var ls_nomtienda="<?php print $ls_nomtienda;?>";
	   	pagina="reportes/sigesp_sfc_rep_gestion.php?sql1="+ls_sql1+"&desde="+ld_fecdesde+"&hasta="+ld_fehasta+"&nomtie="+ls_nomtienda+"&sql2="+ls_sql2+"&sql3="+ls_sql3+"&sql4="+ls_sql4+"&sql5="+ls_sql5+"&sql6="+ls_sql6+"&sql7="+ls_sql7+"&sql8="+ls_sql8+"&sql9="+ls_sql9+"&sql10="+ls_sql10;
		popupWin(pagina,"catalogo",580,700);
     </script>
<?php
}

elseif($ls_operacion=="VER2")
{
        $ls_operacion="";

	$ld_fecdesde=substr($ld_fecdesde,6,4).'-'.substr($ld_fecdesde,3,2).'-'.substr($ld_fecdesde,0,2);
$ld_fechasta=substr($ld_fechasta,6,4).'-'.substr($ld_fechasta,3,2).'-'.substr($ld_fechasta,0,2);
//print $ld_fechasta;
//-------------------------------TOTAL CLIENTES QUE COMPRAR�N-----------------------------------
		$ls_sql1="SELECT COUNT(DISTINCT f.codcli) as totcli FROM".
		" sfc_factura f WHERE (substr(cast (f.fecemi as char(30)),0,11) BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."') AND f.estfaccon<>'A' AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'f',$ls_codtie)." ;";
		//print $ls_sql1;
		//----------------------------------------------------------------------------------------------
		//-------------------------------TOTAL CLIENTES QUE NO COMPRAR�N--------------------------------
		$ls_sql2="SELECT COUNT(DISTINCT codcli) as totcli FROM sfc_cotizacion WHERE ".
		"numcot<>'0000000000000000000000000' AND (substr(feccot,0,11) BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."') AND ".
		"estcot<>'F' AND numcot NOT IN (SELECT numcot from sfc_factura) AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'sfc_cotizacion',$ls_codtie)."; ";
		//print $ls_sql2;
		//----------------------------------------------------------------------------------------------
		//-------------------------TOTAL CLIENTES QUE COMPRAR�N EN EFECTIVO-----------------------------
		$ls_sql3="SELECT COUNT(DISTINCT f.codcli) as totcli FROM sfc_factura f".
		" WHERE (f.conpag='1' AND f.estfaccon='C') AND (substr(cast (f.fecemi as char(30)),0,11) BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."') AND ".
		" f.estfaccon<>'A' AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'f',$ls_codtie)." ;";
		//print $ls_sql3;
		//----------------------------------------------------------------------------------------------
		//-------------------------TOTAL CLIENTES QUE COMPRAR�N A CREDITO------------------------------
		$ls_sql4="SELECT COUNT(DISTINCT f.codcli) as totcli FROM sfc_factura f".
		" WHERE (f.conpag BETWEEN '2' AND '4') AND ".
		" (substr(cast (f.fecemi as char(30)),0,11) BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."') AND f.estfaccon<>'A' AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'f',$ls_codtie)." ; ";
		//print $ls_sql4;
		//----------------------------------------------------------------------------------------------
		//-------------------------------------TOTAL MUNICIPIOS-----------------------------------------
		$ls_sql5="SELECT s.codmun,s.denmun FROM sigesp_municipio s WHERE s.codest='".$ls_codest."'";
		//print $ls_sql5;
		//----------------------------------------------------------------------------------------------
		//-------------------------------------TOTAL TENENCIAS------------------------------------------
		$ls_sql6="SELECT DISTINCT(p.codtenencia) as codigo,MAX(p.codcli) as codcli FROM sfc_productor p WHERE ".
		"p.codtenencia<>''and p.codcli IN (select codcli from sfc_factura WHERE (substr(fecemi,0,11)>='".$ld_fecdesde."') AND ".
		"(substr(fecemi,0,11)<='".$ld_fechasta."') AND estfaccon<>'A'  AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'sfc_factura',$ls_codtie).") GROUP BY p.codtenencia";
		//print $ls_sql6;
		//----------------------------------------------------------------------------------------------
		//-------------------------------------TOTAL ENTIDADES CREDITICIAS------------------------------
		$ls_sql7="SELECT id_entidad,denominacion FROM sfc_entidadcrediticia";
		//print $ls_sql7;
		//----------------------------------------------------------------------------------------------
		//-------------------------------------TOTAL HAS RUBROS AGRICOLAS-------------------------------
		$ls_sql8="SELECT cla.id_clasificacion,cla.denominacion,sum(rc.hect_prod) as thas,sum(rc.cant_pro) as tprod ".
			"FROM sfc_clasificacionrubro cla,sfc_rubroagri_cliente rc WHERE ".
			"cla.id_clasificacion=rc.id_clasificacion AND rc.codcli IN (SELECT codcli FROM sfc_factura ".
			"WHERE estfaccon<>'A' AND (substr(fecemi,0,11)>='".$ld_fecdesde."' AND substr(fecemi,0,11)<='".$ld_fechasta."' AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'sfc_factura',$ls_codtie)."))".
			" GROUP BY cla.id_clasificacion,cla.denominacion ORDER BY cla.denominacion;";
		//print $ls_sql8;
		//----------------------------------------------------------------------------------------------
		//-------------------------------------TOTAL HAS RUBROS PECUARIOS-------------------------------
		$ls_sql9="SELECT cla.id_clasificacion,cla.denominacion,r.denominacion as rubro,".
		"sum(rc.hect_prod) as hectprorp,sum(rc.cant_pro) as tcantrp,SUM(rc.nro_animales) as tnro_animal".
		" FROM sfc_rubro r,sfc_clasificacionrubro cla,sfc_rubropec_cliente rc WHERE r.id_rubro=cla.id_rubro".
		" AND cla.id_clasificacion=rc.id_clasificacion AND rc.codcli IN (SELECT codcli FROM sfc_factura ".
		" WHERE estfaccon<>'A' AND (substr(fecemi,0,11)>='".$ld_fecdesde."' AND substr(fecemi,0,11)<='".$ld_fechasta."' AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'sfc_factura',$ls_codtie)."))".
		" GROUP BY cla.id_clasificacion,cla.denominacion,r.denominacion ORDER BY cla.denominacion;";
		//print $ls_sql9;
		//-------------------------------------TIPOS DE USOS-----------------------------------------
		$ls_sql10="SELECT t.id_tipouso,t.dentipouso FROM sfc_uso s,sfc_tipouso t,sfc_producto p,sim_articulo a".
		" WHERE t.id_tipouso=s.id_tipouso AND a.id_uso=s.id_uso AND a.codart=p.codart AND p.codart IN (SELECT df.codart FROM ".
		" sfc_detfactura df,sfc_factura f where f.numfac=df.numfac AND f.estfaccon<>'A' AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'f',$ls_codtie).") GROUP BY t.id_tipouso,t.dentipouso ORDER BY t.id_tipouso";
		//print $ls_sql10;
		//----------------------------------------------------------------------------------------------
?>
     <script language="JavaScript">
   	 	var ls_sql1="<?php print $ls_sql1; ?>";
		var ls_sql2="<?php print $ls_sql2; ?>";
		var ls_sql3="<?php print $ls_sql3; ?>";
		var ls_sql4="<?php print $ls_sql4; ?>";
		var ls_sql5="<?php print $ls_sql5; ?>";
		var ls_sql6="<?php print $ls_sql6; ?>";
		var ls_sql7="<?php print $ls_sql7; ?>";
		var ls_sql8="<?php print $ls_sql8; ?>";
		var ls_sql9="<?php print $ls_sql9; ?>";
		var ls_sql10="<?php print $ls_sql10; ?>";
		var ld_fecdesde="<?php print $ld_fecdesde; ?>";
		var ld_fechasta="<?php print $ld_fechasta; ?>";
		var ls_nomtienda="<?php print $ls_nomtienda;?>";
	   	pagina="reportes/sigesp_sfc_rep_gestion_excel.php?sql1="+ls_sql1+"&desde="+ld_fecdesde+"&hasta="+ld_fechasta+"&nomtie="+ls_nomtienda+"&sql2="+ls_sql2+"&sql3="+ls_sql3+"&sql4="+ls_sql4+"&sql5="+ls_sql5+"&sql6="+ls_sql6+"&sql7="+ls_sql7+"&sql8="+ls_sql8+"&sql9="+ls_sql9+"&sql10="+ls_sql10;

		//pagina="reportes/sigesp_sfc_rep_gestion_excel.php?desde="+ld_desde+"&hasta="+ld_hasta;
			window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");

		</script>
	 <?php

}
?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
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
  valido=ue_comparar_intervalo();
  if(valido)
  {
  f.operacion.value="VER";
  f.action="sigesp_sfc_d_rep_gestion.php";
  f.submit();
  }
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

  valido2=ue_comparar_intervalo();
  if(valido2)
  {
  f.operacion.value="VER2";
 f.action="sigesp_sfc_d_rep_gestion.php";
  f.submit();
  }
 }
 else
	{alert("No tiene permiso para realizar esta operaci�n");
}
}


//--------------------------------------------------------
//	Funci�n que da formato a la fecha colocando los separadores (/).
//--------------------------------------------------------
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_separadores(d,sep,pat,nums)
{
	if(d.valant != d.value)
	{
		val = d.value
		largo = val.length
		val = val.split(sep)
		val2 = ''
		for(r=0;r<val.length;r++){
			val2 += val[r]
		}
		if(nums){
			for(z=0;z<val2.length;z++){
				if(isNaN(val2.charAt(z))){
					letra = new RegExp(val2.charAt(z),"g")
					val2 = val2.replace(letra,"")
				}
			}
		}
		val = ''
		val3 = new Array()
		for(s=0; s<pat.length; s++){
			val3[s] = val2.substring(0,pat[s])
			val2 = val2.substr(pat[s])
		}
		for(q=0;q<val3.length; q++){
			if(q ==0){
				val = val3[q]
			}
			else{
				if(val3[q] != ""){
					val += sep + val3[q]
					}
			}
		}
	d.value = val
	d.valant = val
	}
}
//--------------------------------------------------------
//	Funci�n que valida que un intervalo de tiempo sea valido
//--------------------------------------------------------
   function ue_comparar_intervalo()
   {

	f=document.form1;
   	ld_desde="f.txtdesde";
   	ld_hasta="f.txthasta";
	var valido = false;
	var diad = f.txtdesde.value.substr(0, 2);
    var mesd = f.txtdesde.value.substr(3, 2);
    var anod = f.txtdesde.value.substr(6, 4);
    var diah = f.txthasta.value.substr(0, 2);
    var mesh = f.txthasta.value.substr(3, 2);
    var anoh = f.txthasta.value.substr(6, 4);
	if(diad!="" && mesd!="" && anod!="" && diah!="" && mesh!="" && anoh!="")
	{
	if (anod < anoh)
	{
		 valido = true;
	 }
    else
	{
     if (anod == anoh)
	 {
      if (mesd < mesh)
	  {
	   valido = true;
	  }
      else
	  {
       if (mesd == mesh)
	   {
 		if (diad <= diah)
		{
		  valido = true;
		}
	   }
      }
     }
    }
	}
    if (valido==false)
	{
		alert("El rango de fecha es invalido");
		f.txtdesde.value="";
		f.txthasta.value="";
	}
	return valido;
   }





</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
