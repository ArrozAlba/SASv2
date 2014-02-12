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

$_SESSION["ls_codtienda"]='0001';
$la_datemp=$_SESSION["la_empresa"];
$ls_codtie=$_SESSION["ls_codtienda"];
$ls_nomtienda=$_SESSION["ls_nomtienda"];
$ls_codest=$_SESSION["ls_codest"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Listado de Productos por Clasificaci&oacute;n </title>
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
.Estilo2 {color: #6699CC}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno" >
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="527" height="20" class="descripcion_sistema  Estilo2">Sistema de Facturaci&oacute;n</td>
    <td width="251" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
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
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript: ue_ver();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
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
	$ls_ventanas="sigesp_sfc_d_rep_clasificacion.php";

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

$ls_dencla=$_POST["txtdencla"];
$ls_densub=$_POST["txtdensub"];

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
    <table width="518" height="260" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
	   <input name="hidstatus" type="hidden" id="hidstatus">
        <td width="516" height="258"><div align="center">
            <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
              <tr>
                <td colspan="3" class="titulo-ventana">Reporte de Productos Por Clasificacion  (Filtrar) </td>
              </tr>
              <tr>
                <td colspan="3" class="sin-borde">&nbsp;</td>
              </tr>
              <tr>
                <td width="400"  height="175"><div align="right">
                  <table width="448" height="158" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">


					<?php
					if ($ls_codtie == '0001') {

					?>

                   <input type="hidden" name="hdnagrotienda" value=""/>

					<tr>
		                <td width="133" height="22" align="right">Desde Unidad Operativa de Suministro </td>
		                <td colspan="3" >

		                <input name="txtdentienda_desde" type="text" id="txtdentienda_desde" size="30">
		                <input name="txtcodtienda_desde" type="hidden" id="txtcodtienda_desde" size="30"><a href="javascript: ue_buscar_tienda('desde');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
					</tr>

					<tr>
		                <td height="22" align="right">Hasta Unidad Operativa de Suministro </td>
		                <td colspan="3" >
		                <input name="txtdentienda_hasta" type="text" id="txtdentienda_hasta" size="30">
		                <input name="txtcodtienda_hasta" type="hidden" id="txtcodtienda_hasta" size="30">
		                <a href="javascript: ue_buscar_tienda('hasta');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
					</tr>


					<?php
					}
					?>
<td>&nbsp;</td>

          <tr>

            <td  height="22" align="right">Clasificacion </td>
            <td height="25" colspan="2">
              <input name="txtdencla" type="text" id="txtdencla" size="30">
            </td>
            <td width="147"><a href="javascript: ue_buscar_clasificacion();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
            <td width="43">&nbsp;</td>
          </tr>
          <tr>
            <td align="right">Subclasificacion </td>
            <td colspan="2">
              <input name="txtdensub" type="text" id="txtdensub" size="30">
            </td>
            <td><a href="javascript:ue_catsubclasificacion();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
            <td>&nbsp;</td>
          </tr>
        </table>

                </div></td>

            </table>
        </div></td>
      </tr>
    </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
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

	if (($ls_dencla=="") and ($ls_densub=="")){
		//-------------------------------TOTAL CLIENTES QUE COMPRAR�N-----------------------------------
		$ls_sql1="SELECT COUNT(DISTINCT f.codcli) as totcli FROM".
		" sfc_factura f WHERE ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie).";";

		//----------------------------------------------------------------------------------------------
		//-------------------------------TOTAL CLIENTES QUE NO COMPRAR�N--------------------------------
		$ls_sql2="SELECT COUNT(DISTINCT codcli) as totcli FROM sfc_cotizacion WHERE ".
		"numcot<>'0000000000000000000000000' AND codtiend = '$ls_codtie' AND numcot NOT IN (SELECT numcot from sfc_factura f WHERE " .
		"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie).");";

		//----------------------------------------------------------------------------------------------
		//-------------------------TOTAL CLIENTES QUE COMPRAR�N EN EFECTIVO-----------------------------
		$ls_sql3="SELECT COUNT(DISTINCT f.codcli) as totcli FROM sfc_factura f".
		" WHERE f.estfaccon='C' AND  ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie)." ;";

		//----------------------------------------------------------------------------------------------
		//-------------------------TOTAL CLIENTES QUE COMPRAR�N A CREDITO------------------------------
		$ls_sql4="SELECT COUNT(f.codcli) as totcli FROM sfc_factura f".
		" WHERE f.estfaccon<>'C' AND  ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie)." ;";

		//----------------------------------------------------------------------------------------------
		//-------------------------------------TOTAL MUNICIPIOS-----------------------------------------
		$ls_sql5="SELECT s.codmun,s.denmun FROM sigesp_municipio s WHERE s.codest='".$ls_codest."'";
		//----------------------------------------------------------------------------------------------


		//-------------------------------------TOTAL TENENCIAS------------------------------------------
		$ls_sql6="SELECT DISTINCT(p.codtenencia) as codigo,MAX(p.codcli) as codcli FROM sfc_productor p WHERE " .
				"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie)." GROUP BY p.codtenencia";

		//----------------------------------------------------------------------------------------------
		//-------------------------------------TOTAL ENTIDADES CREDITICIAS------------------------------
		$ls_sql7="SELECT ec.id_entidad,ec.denominacion FROM sfc_entidadcrediticia ec WHERE " .
				"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'ec',$ls_codtie)."";

		//----------------------------------------------------------------------------------------------
		//-------------------------------------TOTAL HAS RUBROS AGRICOLAS-------------------------------
		$ls_sql8="SELECT cla.id_clasificacion,cla.denominacion,SUM(rc.hect_prod) as thas,SUM(rc.cant_pro) as tprod ".
"FROM sfc_clasificacionrubro cla,sfc_rubro ru,sfc_renglon re,sfc_rubroagri_cliente rc,sfc_factura f WHERE ".
" ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie)." AND cla.id_clasificacion=rc.id_clasificacion " .
		" AND cla.cod_clasificacion=rc.cod_clasificacion AND cla.codemp=re.codemp AND cla.codemp=ru.codemp AND cla.codemp=rc.codemp " .
		"AND cla.codemp=f.codemp AND ru.codemp=re.codemp AND ru.codemp=rc.codemp AND ru.codemp=f.codemp AND re.codemp=rc.codemp " .
		"AND re.codemp=f.codemp AND rc.codemp=f.codemp AND cla.id_rubro=ru.id_rubro AND ru.id_renglon=re.id_renglon " .
		"AND re.id_tipoexplotacion='1' AND rc.id_clasificacion=cla.id_clasificacion AND f.codcli=rc.codcli ".
" GROUP BY cla.denominacion,cla.id_clasificacion ORDER BY cla.denominacion ASC";
       /*  echo '<br>Consulta 8a<br> ';
         echo $ls_sql8;*/
		//----------------------------------------------------------------------------------------------
		//-------------------------------------TOTAL HAS RUBROS PECUARIOS-------------------------------
		$ls_sql9="SELECT cla.id_clasificacion,cla.denominacion,SUM(rc.hect_prod) as thas,SUM(rc.cant_pro) as tprod,SUM(rc.nro_animales) as tanimal ".
"FROM sfc_clasificacionrubro cla,sfc_rubro ru,sfc_renglon re,sfc_rubropec_cliente rc,sfc_factura f WHERE ".
"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie)." AND cla.id_clasificacion=rc.id_clasificacion AND cla.cod_clasificacion=rc.cod_clasificacion " .
		" AND cla.codemp=re.codemp AND cla.codemp=ru.codemp AND cla.codemp=rc.codemp AND cla.codemp=f.codemp AND ru.codemp=re.codemp AND ru.codemp=rc.codemp" .
		" AND ru.codemp=f.codemp AND re.codemp=rc.codemp AND re.codemp=f.codemp AND rc.codemp=f.codemp" .
		" AND cla.id_rubro=ru.id_rubro AND ru.id_renglon=re.id_renglon AND re.id_tipoexplotacion='2' AND".
" rc.id_clasificacion=cla.id_clasificacion AND f.codcli=rc.codcli ".
" GROUP BY cla.denominacion,cla.id_clasificacion ORDER BY cla.denominacion ASC";
       /*  echo ' <br>Consulta 9a<br> ';
         echo $ls_sql9;*/
		//-------------------------------------TIPOS DE USOS-----------------------------------------
		$ls_sql10="SELECT t.id_tipouso,t.dentipouso FROM sfc_factura f,sfc_uso u,sfc_tipouso t,sfc_detfactura df,sfc_producto p,sim_articulo a".
		" WHERE ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie)." AND f.codemp=u.codemp AND f.codemp=t.codemp AND f.codemp=df.codemp" .
		" AND f.codemp=p.codemp AND f.codemp=a.codemp AND f.numfac=df.numfac AND f.codtiend=df.codtiend AND f.codtiend=p.codtiend  " .
		" AND u.codemp=t.codemp AND u.codemp=df.codemp AND u.codemp=p.codemp AND u.codemp=a.codemp AND u.id_tipouso=t.id_tipouso " .
		" AND u.id_uso=a.id_uso AND t.codemp=df.codemp AND t.codemp=p.codemp AND df.codemp=p.codemp AND df.codemp=a.codemp AND df.codart=p.codart" .
		" AND df.codart=a.codart AND df.codtiend=p.codtiend AND p.codemp=a.codemp AND p.codart=a.codart ".
		" GROUP BY t.id_tipouso,t.dentipouso ORDER BY t.id_tipouso ASC";
      /*  echo '<br>Consulta 10a<br> ';
        echo $ls_sql10;*/
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
		var ls_dencla="<?php print $ls_dencla; ?>";
		var ls_densub="<?php print $ls_densub; ?>";
		var ls_nomtienda="<?php print $ls_nomtienda;?>";
	   	pagina="reportes/sigesp_sfc_rep_clasificacion.php?sql1="+ls_sql1+"&clasificacion="+ls_dencla+"&subclasificacion="+ls_densub+"&nomtie="+ls_nomtienda+"&sql2="+ls_sql2+"&sql3="+ls_sql3+"&sql4="+ls_sql4+"&sql5="+ls_sql5+"&sql6="+ls_sql6+"&sql7="+ls_sql7+"&sql8="+ls_sql8+"&sql9="+ls_sql9+"&sql10="+ls_sql10;
		popupWin(pagina,"catalogo",580,700);
     </script>
<?php
	}
	else
	{
		if (($ls_dencla<>"") and ($ls_densub<>"")){
		//-------------------------------TOTAL CLIENTES QUE COMPRAR�N-----------------------------------
		$ls_sql1="SELECT COUNT(DISTINCT f.codcli) as totcli FROM".
		" sfc_factura f  WHERE ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie)." ;";

		//----------------------------------------------------------------------------------------------
		//-------------------------------TOTAL CLIENTES QUE NO COMPRAR�N--------------------------------
		$ls_sql2="SELECT COUNT(DISTINCT codcli) as totcli FROM sfc_cotizacion WHERE ".
		"numcot<>'0000000000000000000000000' AND codtiend = '$ls_codtie' AND numcot NOT IN (SELECT numcot from sfc_factura f WHERE " .
		"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie).");";

		//----------------------------------------------------------------------------------------------
		//-------------------------TOTAL CLIENTES QUE COMPRAR�N EN EFECTIVO-----------------------------
		$ls_sql3="SELECT COUNT(DISTINCT f.codcli) as totcli FROM sfc_factura f".
		" WHERE f.estfaccon='C' AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie).";";

		//----------------------------------------------------------------------------------------------
		//-------------------------TOTAL CLIENTES QUE COMPRAR�N A CREDITO------------------------------
		$ls_sql4="SELECT COUNT(f.codcli) as totcli FROM sfc_factura f".
		" WHERE f.estfaccon<>'C' AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie).";";

		//----------------------------------------------------------------------------------------------
		//-------------------------------------TOTAL MUNICIPIOS-----------------------------------------
		$ls_sql5="SELECT s.codmun,s.denmun FROM sigesp_municipio s WHERE s.codest='".$ls_codest."'";
		//----------------------------------------------------------------------------------------------
		//-------------------------------------TOTAL TENENCIAS------------------------------------------
		$ls_sql6="SELECT DISTINCT(p.codtenenencia) as codigo,MAX(p.codcli) as codcli FROM sfc_productor p WHERE" .
				" ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie)." GROUP BY p.codtenencia";

		//----------------------------------------------------------------------------------------------
		//-------------------------------------TOTAL ENTIDADES CREDITICIAS------------------------------
		$ls_sql7="SELECT id_entidad,denominacion FROM sfc_entidadcrediticia ec WHERE " .
				" ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'ec',$ls_codtie)." ";

		//----------------------------------------------------------------------------------------------
		//-------------------------------------TOTAL HAS RUBROS AGRICOLAS-------------------------------
		$ls_sql8="SELECT cla.id_clasificacion,cla.denominacion,SUM(rc.hect_prod) as thas,SUM(rc.cant_pro) as tprod ".
"FROM sfc_clasificacionrubro cla,sfc_rubro ru,sfc_renglon re,sfc_rubroagri_cliente rc,sfc_factura f WHERE ".
"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie)." AND cla.id_clasificacion=rc.id_clasificacion " .
		" AND cla.cod_clasificacion=rc.cod_clasificacion AND cla.codemp=re.codemp AND cla.codemp=ru.codemp AND cla.codemp=rc.codemp " .
		" AND cla.codemp=f.codemp AND ru.codemp=re.codemp AND ru.codemp=rc.codemp AND ru.codemp=f.codemp AND re.codemp=rc.codemp " .
		" AND re.codemp=f.codemp AND rc.codemp=f.codemp AND cla.id_rubro=ru.id_rubro AND ru.id_renglon=re.id_renglon " .
		" AND re.id_tipoexplotacion='1' AND rc.id_clasificacion=cla.id_clasificacion AND f.codcli=rc.codcli ".
" GROUP BY cla.denominacion,cla.id_clasificacion ORDER BY cla.denominacion ASC";
      /* echo '<br>Consulta 8b<br> ';
         echo $ls_sql8;*/
		//----------------------------------------------------------------------------------------------
		//-------------------------------------TOTAL HAS RUBROS PECUARIOS-------------------------------
		$ls_sql9="SELECT cla.id_clasificacion,cla.denominacion,SUM(rc.hect_prod) as thas,SUM(rc.cant_pro) as tprod,SUM(rc.nro_animales) as tanimal ".
"FROM sfc_clasificacionrubro cla,sfc_rubro ru,sfc_renglon re,sfc_rubropec_cliente rc,sfc_factura f WHERE  ".
" ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie)." AND cla.id_clasificacion=rc.id_clasificacion " .
		" AND cla.cod_clasificacion=rc.cod_clasificacion AND cla.codemp=re.codemp AND cla.codemp=ru.codemp AND cla.codemp=rc.codemp " .
		" AND cla.codemp=f.codemp AND ru.codemp=re.codemp AND ru.codemp=rc.codemp AND ru.codemp=f.codemp AND re.codemp=rc.codemp " .
		" AND re.codemp=f.codemp AND rc.codemp=f.codemp AND cla.id_rubro=ru.id_rubro AND ru.id_renglon=re.id_renglon " .
		" AND re.id_tipoexplotacion='2' AND rc.id_clasificacion=cla.id_clasificacion AND f.codcli=rc.codcli" .
" GROUP BY cla.denominacion,cla.id_clasificacion ORDER BY cla.denominacion ASC";
      /*  echo '<br>Consulta 9b<br> ';
         echo $ls_sql9;*/
		//-------------------------------------TIPOS DE USOS-----------------------------------------
		$ls_sql10="SELECT t.id_tipouso,t.dentipouso FROM sfc_factura f,sfc_tipouso t,sfc_detfactura df,sfc_producto p, sim_articulo a".
		" WHERE ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie)." AND f.codemp=t.codemp AND f.codemp=df.codemp" .
		" AND f.codemp=p.codemp AND f.codemp=a.codemp AND f.numfac=df.numfac AND f.codtiend=df.codtiend AND f.codtiend=p.codtiend  " .
		" AND t.codemp=df.codemp AND t.codemp=p.codemp AND df.codemp=p.codemp AND df.codemp=a.codemp AND df.codart=p.codart" .
		" AND df.codart=a.codart AND df.codtiend=p.codtiend AND p.codemp=a.codemp AND p.codart=a.codart ".
		" GROUP BY t.id_tipouso,t.dentipouso ORDER BY t.id_tipouso";
		/* echo '<br>Consulta 10b<br> ';
         echo $ls_sql10;*/
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
		var ls_dencla="<?php print $ls_dencla; ?>";
		var ls_densub="<?php print $ls_densub; ?>";
		var ls_nomtienda="<?php print $ls_nomtienda;?>";
	   	pagina="reportes/sigesp_sfc_rep_clasificacion2.php?sql1="+ls_sql1+"&clasificacion="+ls_dencla+"&subclasificacion="+ls_densub+"&nomtie="+ls_nomtienda+"&sql2="+ls_sql2+"&sql3="+ls_sql3+"&sql4="+ls_sql4+"&sql5="+ls_sql5+"&sql6="+ls_sql6+"&sql7="+ls_sql7+"&sql8="+ls_sql8+"&sql9="+ls_sql9+"&sql10="+ls_sql10;
		popupWin(pagina,"catalogo",580,700);
     </script>

<?php
	}
	else
	{
		if ($ls_dencla<>"")
		{
		//-------------------------------TOTAL CLIENTES QUE COMPRAR�N-----------------------------------
		$ls_sql1="SELECT COUNT(DISTINCT f.codcli) as totcli FROM".
		" sfc_factura f WHERE ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie)." ;";

		//----------------------------------------------------------------------------------------------
		//-------------------------------TOTAL CLIENTES QUE NO COMPRAR�N--------------------------------
		$ls_sql2="SELECT COUNT(DISTINCT codcli) as totcli FROM sfc_cotizacion WHERE ".
		"numcot<>'0000000000000000000000000' AND codtiend = '$ls_codtie' AND numcot NOT IN (SELECT numcot from sfc_factura f WHERE " .
		"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie).");";

		//----------------------------------------------------------------------------------------------
		//-------------------------TOTAL CLIENTES QUE COMPRAR�N EN EFECTIVO-----------------------------
		$ls_sql3="SELECT COUNT(DISTINCT f.codcli) as totcli FROM sfc_factura f".
		" WHERE f.estfaccon='C' AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie).";";

		//----------------------------------------------------------------------------------------------
		//-------------------------TOTAL CLIENTES QUE COMPRAR�N A CREDITO------------------------------
		$ls_sql4="SELECT COUNT(f.codcli) as totcli FROM sfc_factura f".
		" WHERE f.estfaccon<>'C'AND ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie).";";

		//----------------------------------------------------------------------------------------------
		//-------------------------------------TOTAL MUNICIPIOS-----------------------------------------
		$ls_sql5="SELECT s.codmun,s.denmun FROM sigesp_municipio s WHERE s.codest='".$ls_codest."'";
		//----------------------------------------------------------------------------------------------
		//-------------------------------------TOTAL TENENCIAS------------------------------------------
		$ls_sql6="SELECT DISTINCT(p.codtenencia) as codigo,MAX(p.codcli) as codcli FROM sfc_productor p WHERE " .
				" ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'p',$ls_codtie)." GROUP BY p.codtenencia";

		//----------------------------------------------------------------------------------------------
		//-------------------------------------TOTAL ENTIDADES CREDITICIAS------------------------------
		$ls_sql7="SELECT id_entidad,denominacion FROM sfc_entidadcrediticia ec WHERE " .
				"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'ec',$ls_codtie)."";

		//----------------------------------------------------------------------------------------------
		//-------------------------------------TOTAL HAS RUBROS AGRICOLAS-------------------------------
		$ls_sql8="SELECT cla.id_clasificacion,cla.denominacion,SUM(rc.hect_prod) as thas,SUM(rc.cant_pro) as tprod ".
"FROM sfc_clasificacionrubro cla,sfc_rubro ru,sfc_renglon re,sfc_rubroagri_cliente rc,sfc_factura f WHERE ".
"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie)." AND cla.id_clasificacion=rc.id_clasificacion " .
		" AND cla.cod_clasificacion=rc.cod_clasificacion AND cla.codemp=re.codemp AND cla.codemp=ru.codemp AND cla.codemp=rc.codemp " .
		" AND cla.codemp=f.codemp AND ru.codemp=re.codemp AND ru.codemp=rc.codemp AND ru.codemp=f.codemp AND re.codemp=rc.codemp " .
		" AND re.codemp=f.codemp AND rc.codemp=f.codemp AND cla.id_rubro=ru.id_rubro AND ru.id_renglon=re.id_renglon " .
		" AND re.id_tipoexplotacion='1' AND rc.id_clasificacion=cla.id_clasificacion AND f.codcli=rc.codcli ".
	    " GROUP BY cla.denominacion,cla.id_clasificacion ORDER BY cla.denominacion";
/*echo '<br>Consulta 8c<br> ';
         echo $ls_sql8;*/
		//----------------------------------------------------------------------------------------------
		//-------------------------------------TOTAL HAS RUBROS PECUARIOS-------------------------------
		$ls_sql9="SELECT cla.id_clasificacion,cla.denominacion,SUM(rc.hect_prod) as thas,SUM(rc.cant_pro) as tprod,SUM(rc.nro_animales) as tanimal ".
"FROM sfc_clasificacionrubro cla,sfc_rubro ru,sfc_renglon re,sfc_rubropec_cliente rc,sfc_factura f WHERE ".
"".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie)." AND cla.id_clasificacion=rc.id_clasificacion " .
		" AND cla.cod_clasificacion=rc.cod_clasificacion AND cla.codemp=re.codemp AND cla.codemp=ru.codemp AND cla.codemp=rc.codemp " .
		" AND cla.codemp=f.codemp AND ru.codemp=re.codemp AND ru.codemp=rc.codemp AND ru.codemp=f.codemp AND re.codemp=rc.codemp " .
		" AND re.codemp=f.codemp AND rc.codemp=f.codemp AND cla.id_rubro=ru.id_rubro AND ru.id_renglon=re.id_renglon " .
		" AND re.id_tipoexplotacion='2' AND rc.id_clasificacion=cla.id_clasificacion AND f.codcli=rc.codcli" .
		" GROUP BY cla.denominacion,cla.id_clasificacion ORDER BY cla.denominacion";
/*echo '<br>Consulta 9c<br> ';
         echo $ls_sql9;*/
		//-------------------------------------TIPOS DE USOS-----------------------------------------
		$ls_sql10="SELECT t.id_tipouso,t.dentipouso FROM sfc_factura f,sfc_tipouso t,sfc_detfactura df,sfc_producto p,sim_articulo a".
		" WHERE ".sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie)."AND f.codemp=t.codemp AND f.codemp=df.codemp" .
		" AND f.codemp=p.codemp AND f.codemp=a.codemp AND f.numfac=df.numfac AND f.codtiend=df.codtiend AND f.codtiend=p.codtiend  " .
		" AND t.codemp=df.codemp AND t.codemp=p.codemp AND df.codemp=p.codemp AND df.codemp=a.codemp AND df.codart=p.codart" .
		" AND df.codart=a.codart AND df.codtiend=p.codtiend AND p.codemp=a.codemp AND p.codart=a.codart ".
		" GROUP BY t.id_tipouso,t.dentipouso ORDER BY t.id_tipouso";
		/*echo '<br>Consulta 10c<br> ';
         echo $ls_sql10;*/
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
		var ls_dencla="<?php print $ls_dencla; ?>";
		var ls_densub="<?php print $ls_densub; ?>";
		var ls_nomtienda="<?php print $ls_nomtienda;?>";
	   	pagina="reportes/sigesp_sfc_rep_clasificacion3.php?sql1="+ls_sql1+"&clasificacion="+ls_dencla+"&subclasificacion="+ls_densub+"&nomtie="+ls_nomtienda+"&sql2="+ls_sql2+"&sql3="+ls_sql3+"&sql4="+ls_sql4+"&sql5="+ls_sql5+"&sql6="+ls_sql6+"&sql7="+ls_sql7+"&sql8="+ls_sql8+"&sql9="+ls_sql9+"&sql10="+ls_sql10;
		popupWin(pagina,"catalogo",580,700);
     </script>

<?php
		}
	 }
  }
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

  f.operacion.value="VER";
  f.action="sigesp_sfc_d_rep_clasificacion.php";
  f.submit();
 }
 else
	{alert("No tiene permiso para realizar esta operaci�n");}

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
function ue_cargarsubclasificacion(codsub,nomsub,codcla,nomcla1)
{
    f=document.form1;
	f.operacion.value="";
	f.txtdensub.value=nomsub;
}
  function ue_catsubclasificacion()
{
	f=document.form1;
	f.operacion.value="";
	pagina="sigesp_cat_sub_clasificacion.php";
	popupWin(pagina,"catalogo",580,300);
}

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
