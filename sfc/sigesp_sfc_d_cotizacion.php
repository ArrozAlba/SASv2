<?php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "location.href='sigesp_conexion.php'";
	print "</script>";
}
$la_datemp=$_SESSION["la_empresa"];
$ls_item= $_SESSION["ls_item"];
$ls_codtie=$_SESSION["ls_codtienda"];
$ls_codcaj=$_SESSION["ls_codcaj"];
$ls_prefijo=$_SESSION["ls_precot"];
$ls_serie=$_SESSION["ls_sercot"];
$ls_item=$_SESSION["ls_item"];
if($ls_codcaj=="" || $ls_codcaj=="T")
{
	print "<script language=JavaScript>";
	print "alert('Debe seleccionar una caja para poder Cotizar.');";
	print "location.href='../index_modules.php';";
	print "</script>";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Cotizacion</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
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
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: underline;
}
a:active {
	text-decoration: none;
}
.Estilo1 {
	font-family: Tahoma, Verdana, Arial;
	color: #6699CC;
}
-->
</style><meta http-equiv="Content-Type" content="text/html; charset="></head>
<body link="#006699" vlink="#006699" alink="#006699" onLoad="ue_subtotal();">
<?Php
//********************************************         SEGURIDAD       ****************************************************
require_once("../shared/class_folder/sigesp_c_seguridad.php");
$io_seguridad= new sigesp_c_seguridad();

$arre=$_SESSION["la_empresa"];
$ls_empresa=$arre["codemp"];
$ls_logusr=$_SESSION["la_logusr"];
$ls_sistema="SFC";
$ls_ventanas="sigesp_sfc_d_cotizacion.php";

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
//*****************         SEGURIDAD    ****************************************/
require_once("../shared/class_folder/class_sql.php");
require_once("class_folder/sigesp_sob_c_funciones_sob.php");
require_once("../shared/class_folder/grid_param.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/evaluate_formula.php");
require_once("../shared/class_folder/class_funciones_db.php");
require_once ("../shared/class_folder/sigesp_include.php");
require_once("class_folder/sigesp_sfc_c_cotizacion.php");
require_once("class_folder/sigesp_sfc_c_secuencia.php");
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_sql=new class_sql($io_connect);
$io_funcdb=new class_funciones_db($io_connect);
$io_secuencia=new sigesp_sfc_c_secuencia();
$io_funcsob=new sigesp_sob_c_funciones_sob();
$io_evalform=new evaluate_formula();
$io_grid=new grid_param();
$io_msg=new class_mensajes();
$io_datastore=new class_datastore();
$io_function=new class_funciones();
$io_cotizacion=new  sigesp_sfc_c_cotizacion();
/***********************************************************************************************
/                                    GRID DETALLE COTIZACION                                   *
/***********************************************************************************************/
$ls_tituloconcepto="Detalle";
$li_anchoconcepto=800;
$ls_nametable="grid2";
$la_columconcepto[1]="C&oacute;digo";
$la_columconcepto[2]="Descripci&oacute;n";
$la_columconcepto[3]="C&oacute;d. Almac&eacute;n";
$la_columconcepto[4]="Almac&eacute;n";
$la_columconcepto[5]="Precio Unitario";
$la_columconcepto[6]="IVA";
$la_columconcepto[7]="Cantidad";
$la_columconcepto[8]="Total";
$la_columconcepto[9]="Proveedor";
$la_columconcepto[10]="Edici&oacute;n";
$ls_codemp=$la_datemp["codemp"];
$ls_codtie=$_SESSION["ls_codtienda"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion1=$_POST["operacion1"];
	$ls_operacion=$_POST["operacion"];
	$ls_numcot=$_POST["txtnumcot"];
	$ls_feccot=$_POST["txtfeccot"];
	$ls_codcli=$_POST["txtcodcli1"];
	$ls_cedcli=$_POST["txtcodcli"];
	$ls_nomcli=$_POST["txtnomcli"];
	$ls_obscot=$_POST["txtobscot"];
	$ls_subtot=$_POST["txtsubtot"];
	$ls_moniva=$_POST["txtmoniva"];
	$ls_monto=$_POST["txtmonto"];
	$ls_codusu=$_SESSION["la_logusr"];
	$li_filasconcepto=$_POST["filasconcepto"];
    $li_removerconcepto=$_POST["hidremoverconcepto"];
	$ls_porcar=$_POST["txtporcar"];
	$ls_moncar=$_POST["txtmoncar"];
	$ls_estcot=$_POST["txtestcot"];
	$ls_hidstatus=$_POST["hidstatus"];	
	if ($ls_operacion != "ue_cargarconcepto" && $ls_operacion != "ue_removerconcepto")
	{
		for($li_i=1;$li_i<$li_filasconcepto;$li_i++)
		{
		$ls_codpro=$_POST["txtcodpro".$li_i];
		$ls_denpro=$_POST["txtdenpro".$li_i];
		$ls_preuni=$_POST["txtpreuni".$li_i];
		$ls_porcar=$_POST["txtporcar".$li_i];
		$ls_moncar=$_POST["txtmoncar".$li_i];
		$ls_canpro=$_POST["txtcanpro".$li_i];
		$ls_totpro=$_POST["txttotpro".$li_i];
		$ls_codalm=$_POST["txtcodalm".$li_i];
		$ls_desalm=$_POST["txtdesalm".$li_i];
		$ls_codprov=$_POST["txtcodprov".$li_i];
		$ls_prov=$_POST["txtprov".$li_i];

		$la_objectconcepto[$li_i][1]="<input name=txtcodpro".$li_i." type=text id=txtcodpro".$li_i." value='".$ls_codpro."' class=sin-borde size=21 style= text-align:center readonly>";
		$la_objectconcepto[$li_i][2]="<input name=txtdenpro".$li_i." type=text id=txtdenpro".$li_i." value='".$ls_denpro."' class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectconcepto[$li_i][3]="<input name=txtcodalm".$li_i." type=text id=txtcodalm".$li_i." value='".$ls_codalm."' size=11 maxlength=10 class=sin-borde style= text-align:left readonly>";
		$la_objectconcepto[$li_i][4]="<input name=txtdesalm".$li_i." type=text id=txtdesalm".$li_i." value='".$ls_desalm."' size=20 maxlength=255 class=sin-borde style= text-align:left readonly>";
		$la_objectconcepto[$li_i][5]="<input name=txtpreuni".$li_i." type=text id=txtpreuni".$li_i." value='".$ls_preuni."' class=sin-borde size=10 style= text-align:right readonly>";
		$la_objectconcepto[$li_i][6]="<input name=txtporcar".$li_i." type=text id=txtporcar".$li_i." value='".$ls_porcar."' class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_i." type=hidden id=txtmoncar".$li_i." value='".$ls_moncar."' class=sin-borde style= text-align:center readonly>";
		if (($ls_estcot=="" or $ls_estcot=="E"))
		{
		$la_objectconcepto[$li_i][7]="<input name=txtcanpro".$li_i." onChange=javascript:ue_subtotal(); type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=10 style= text-align:right onKeyPress=return(currencyFormat(this,'.',',',event))>";
		}
		else
		{
		$la_objectconcepto[$li_i][7]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=10 style= text-align:right readonly>";
		}

		$la_objectconcepto[$li_i][8]="<input name=txttotpro".$li_i." type=text id=txttotpro".$li_i." value='".$ls_totpro."' class=sin-borde size=12 style= text-align:right readonly>";
		$la_objectconcepto[$li_i][9]="<input name=txtprov".$li_i." type=text id=txtprov".$li_i." value='".$ls_prov."' class=sin-borde size=12 style= text-align:right readonly><input name=txtcodprov".$li_i." type=text id=txtcodprov".$li_i." value='".$ls_codprov."' class=sin-borde size=12 style= text-align:right readonly>";
		if (($ls_estcot==""or $ls_estcot=="E") && $ls_operacion!="ue_guardar" )
		{
		$la_objectconcepto[$li_i][10]="<a href=javascript:ue_removerconcepto(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=5 height=5 border=0 style= text-align:center></a>";
		}
		else
		{
		$la_objectconcepto[$li_i][10]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=5 border=0 style= text-align:center>";
		}

	}
		$la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=21 style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][3]="<input name=txtcodalm".$li_filasconcepto." type=text id=txtcodalm".$li_filasconcepto."  size=11 maxlength=10 class=sin-borde style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][4]="<input name=txtdesalm".$li_filasconcepto." type=text id=txtdesalm".$li_filasconcepto."  size=20 class=sin-borde style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][5]="<input name=txtpreuni".$li_filasconcepto." type=text id=txtpreuni".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly>";
		$la_objectconcepto[$li_filasconcepto][6]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][7]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right>";
		$la_objectconcepto[$li_filasconcepto][8]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly>";
		$la_objectconcepto[$li_filasconcepto][9]="<input name=txtprov".$li_filasconcepto." type=text id=txtprov".$li_filasconcepto." class=sin-borde size=12 style=text-align:right readonly><input name=txtcodprov".$li_filasconcepto." type=hidden id=txtcodprov".$li_filasconcepto." class=sin-borde style=text-align:center readomly>";
		$la_objectconcepto[$li_filasconcepto][10]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
	}
}
else
{
   	$ls_operacion="";
	$ls_numcot="";	
	$ls_feccot="";
	$ls_feccot=date('d/m/Y');
	$ls_codcli="";
	$ls_cedcli="";
	$ls_nomcli="";
	$ls_obscot="CADUCA A LOS TRES DIAS DE EMITIDA";
	$ls_subtot="0,00";
	$ls_moniva="0,00";
	$ls_monto="0,00";
	$ls_porcar="0,00";
	$ls_moncar="0,00";
	$ls_estcot="";
	$ls_hidstatus="";

	$li_filasconcepto=1;
    	$la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=21 style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][3]="<input name=txtcodalm".$li_filasconcepto." type=text id=txtcodalm".$li_filasconcepto."  size=11 maxlength=10 class=sin-borde style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][4]="<input name=txtdesalm".$li_filasconcepto." type=text id=txtdesalm".$li_filasconcepto."  size=20 class=sin-borde style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][5]="<input name=txtpreuni".$li_filasconcepto." type=text id=txtpreuni".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly>";
		$la_objectconcepto[$li_filasconcepto][6]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][7]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right>";
		$la_objectconcepto[$li_filasconcepto][8]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly>";
		$la_objectconcepto[$li_filasconcepto][9]="<input name=txtprov".$li_filasconcepto." type=text id=txtprov".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly><input name=txtcodprov".$li_filasconcepto." type=hidden id=txtcodprov".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][10]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
}

if($ls_operacion=="ue_nuevo")
{
    $la_empresa=$_SESSION["la_empresa"];
	$ls_codtie=$_SESSION["ls_codtienda"];
	$ls_codcaj=$_SESSION["ls_codcaj"];
	$ls_prefijo=$_SESSION["ls_precot"];
	$ls_serie=$_SESSION["ls_sercot"];
	$io_secuencia->uf_ver_secuencia($ls_codcaj.$ls_codtie."cot",&$ls_secuencia);	
	$ls_secuencia=$io_function->uf_cerosizquierda($ls_secuencia,16);
	$ls_numcot=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);
	$ls_operacion1="ue_nuevo";
	$ls_operacion="";
	$ls_feccot="";
	$ls_feccot=date('d/m/Y');
	$ls_codcli="";
	$ls_cedcli="";
	$ls_nomcli="";
	$ls_obscot="CADUCA A LOS TRES DIAS DE EMITIDA";
	$ls_subtot="0,00";
	$ls_moniva="0,00";
	$ls_monto="0,00";
	$ls_estcot="";
	$ls_hidstatus="";

	$li_filasconcepto=1;
    	$la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=21 style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][3]="<input name=txtcodalm".$li_filasconcepto." type=text id=txtcodalm".$li_filasconcepto."  size=11 maxlength=10 class=sin-borde style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][4]="<input name=txtdesalm".$li_filasconcepto." type=text id=txtdesalm".$li_filasconcepto."  size=20 class=sin-borde style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][5]="<input name=txtpreuni".$li_filasconcepto." type=text id=txtpreuni".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly>";
		$la_objectconcepto[$li_filasconcepto][6]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][7]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right>";
		$la_objectconcepto[$li_filasconcepto][8]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly>";
		$la_objectconcepto[$li_filasconcepto][9]="<input name=txtprov".$li_filasconcepto." type=text id=txtprov".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly><input name=txtcodprov".$li_filasconcepto." type=hidden id=txtcodprov".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][10]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
		$io_secuencia->uf_ver_secuencia($ls_codcaj.$ls_codtie."cot",&$ls_secuencia2);
	if ($ls_secuencia2=='')
	{	
	print("<script language=JavaScript>");
	print("alert('DEBE ACTUALIZAR LA CAJA, CORRELATIVO NO VALIDO');");
	print("pagina='sigesp_sfc_d_cotizacion.php';");
    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
    print("</script>");
	}


}
elseif($ls_operacion=="ue_cargarconcepto")
{

	$li_filasconcepto=$_POST["filasconcepto"];
	$li_filasconcepto++;
	for($li_i=1;$li_i<$li_filasconcepto;$li_i++)
	{
		$ls_codpro=$_POST["txtcodpro".$li_i];
		$ls_denpro=$_POST["txtdenpro".$li_i];
		$ls_preuni=$_POST["txtpreuni".$li_i];
		$ls_porcar=$_POST["txtporcar".$li_i];
		$ls_moncar=$_POST["txtmoncar".$li_i];
		$ls_canpro=$_POST["txtcanpro".$li_i];
		$ls_totpro=$_POST["txttotpro".$li_i];
		$ls_codalm=$_POST["txtcodalm".$li_i];
		$ls_desalm=$_POST["txtdesalm".$li_i];
		$ls_codprov=$_POST["txtcodprov".$li_i];
		$ls_prov=$_POST["txtprov".$li_i];
		$la_objectconcepto[$li_i][1]="<input name=txtcodpro".$li_i." type=text id=txtcodpro".$li_i." value='".$ls_codpro."' class=sin-borde size=21 style= text-align:center readonly>";
		$la_objectconcepto[$li_i][2]="<input name=txtdenpro".$li_i." type=text id=txtdenpro".$li_i." value='".$ls_denpro."' class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectconcepto[$li_i][3]="<input name=txtcodalm".$li_i." type=text id=txtcodalm".$li_i." value='".$ls_codalm."' size=11 maxlength=10 class=sin-borde style= text-align:left readonly>";
		$la_objectconcepto[$li_i][4]="<input name=txtdesalm".$li_i." type=text id=txtdesalm".$li_i." value='".$ls_desalm."' size=20 maxlength=255 class=sin-borde style= text-align:left readonly>";
		$la_objectconcepto[$li_i][5]="<input name=txtpreuni".$li_i." type=text id=txtpreuni".$li_i." value='".$ls_preuni."' class=sin-borde size=10 style= text-align:right readonly>";
		$la_objectconcepto[$li_i][6]="<input name=txtporcar".$li_i." type=text id=txtporcar".$li_i." value='".$ls_porcar."' class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_i." type=hidden id=txtmoncar".$li_i." value='".$ls_moncar."' class=sin-borde style= text-align:center readonly>";
		$la_objectconcepto[$li_i][7]="<input name=txtcanpro".$li_i." onChange=javascript:ue_subtotal(); type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=10 style= text-align:right onKeyPress=return(currencyFormat(this,'.',',',event))>";
		$la_objectconcepto[$li_i][8]="<input name=txttotpro".$li_i." type=text id=txttotpro".$li_i." value='".$ls_totpro."' class=sin-borde size=12 style= text-align:right readonly>";
		$la_objectconcepto[$li_i][9]="<input name=txtprov".$li_i." type=text id=txtprov".$li_i." value='".$ls_prov."' class=sin-borde size=12 style= text-align:right readonly><input name=txtcodprov".$li_i." type=text id=txtcodprov".$li_i." value='".$ls_codprov."' class=sin-borde style=text-align:center readonly>";
		$la_objectconcepto[$li_i][10]="<a href=javascript:ue_removerconcepto(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}
		$la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=21 style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][3]="<input name=txtcodalm".$li_filasconcepto." type=text id=txtcodalm".$li_filasconcepto."  size=11 maxlength=10 class=sin-borde style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][4]="<input name=txtdesalm".$li_filasconcepto." type=text id=txtdesalm".$li_filasconcepto."  size=20 class=sin-borde style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][5]="<input name=txtpreuni".$li_filasconcepto." type=text id=txtpreuni".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly>";
		$la_objectconcepto[$li_filasconcepto][6]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][7]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right>";
		$la_objectconcepto[$li_filasconcepto][8]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly>";
		$la_objectconcepto[$li_filasconcepto][9]="<input name=txtprov".$li_filasconcepto." type=text id=txtprov".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly><input name=txtcodprov".$li_filasconcepto." type=hidden id=txtcodprov".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][10]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
}
elseif ($ls_operacion=="ue_cargarconcepto_cotizacion")
{

	$li_filasconcepto=1;
		$la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=21 style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][3]="<input name=txtcodalm".$li_filasconcepto." type=text id=txtcodalm".$li_filasconcepto."  size=11 maxlength=10 class=sin-borde style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][4]="<input name=txtdesalm".$li_filasconcepto." type=text id=txtdesalm".$li_filasconcepto."  size=20 class=sin-borde style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][5]="<input name=txtpreuni".$li_filasconcepto." type=text id=txtpreuni".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly>";
		$la_objectconcepto[$li_filasconcepto][6]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][7]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right>";
		$la_objectconcepto[$li_filasconcepto][8]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly>";
		$la_objectconcepto[$li_filasconcepto][9]="<input name=txtprov".$li_filasconcepto." type=text id=txtprov".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly><input name=txtcodprov".$li_filasconcepto." type=hidden id=txtcodprov".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][10]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";

	$ls_cadena="SELECT dc.numcot,dc.codart,a.denart,um.denunimed,dc.precot,dc.cancot,dc.impcot,dc.codalm,al.desalm,dc.cod_pro,
	rpc.nompro FROM sfc_detcotizacion dc,sim_articulo a,sim_unidadmedida um,sim_almacen al,rpc_proveedor rpc WHERE 
dc.codart=a.codart AND dc.codemp=a.codemp AND dc.codalm=al.codalm AND dc.codemp=al.codemp AND dc.cod_pro=rpc.cod_pro AND dc.codemp=rpc.codemp AND a.codunimed=um.codunimed AND al.codemp=rpc.codemp AND dc.numcot ilike '".$ls_numcot."' AND dc.codtiend ilike '".$ls_codtie."';";
			$arr_detcotizacion=$io_sql->select($ls_cadena);
			if($arr_detcotizacion==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros de productos");
			}
			else
			{
				if($row=$io_sql->fetch_row($arr_detcotizacion))

 				  {
					$la_producto=$io_sql->obtener_datos($arr_detcotizacion);
					$io_datastore->data=$la_producto;
					$totrow=$io_datastore->getRowCount("numcot");					
					for($li_i=1;$li_i<=$totrow;$li_i++)
					{
						$ls_codpro=$io_datastore->getValue("codart",$li_i);				
		                $ls_denpro=$io_datastore->getValue("denart",$li_i)." ".$io_datastore->getValue("denunimed",$li_i);
						$ls_preuni=$io_datastore->getValue("precot",$li_i);
					    $ls_canpro=$io_datastore->getValue("cancot",$li_i);
						$ls_porcar=$io_datastore->getValue("impcot",$li_i);
						$ls_moncar=$io_datastore->getValue("moncar",$li_i);
						$ls_preuni=number_format($ls_preuni,2, ',', '.');
						$ls_canpro=number_format($ls_canpro,2, ',', '.');
						$ls_totpro=$ls_preuni*$ls_canpro;
						$ls_moncar=$ls_totpro*($ls_porcar/100);
						$ls_totpro=number_format($ls_totpro,2, ',', '.');
						$ls_codalm=$io_datastore->getValue("codalm",$li_i);
						$ls_desalm=$io_datastore->getValue("desalm",$li_i);
						$ls_codprov=$io_datastore->getValue("cod_pro",$li_i);
						$ls_prov=$io_datastore->getValue("nompro",$li_i);
		$la_objectconcepto[$li_i][1]="<input name=txtcodpro".$li_i." type=text id=txtcodpro".$li_i." value='".$ls_codpro."' class=sin-borde size=21 style= text-align:center readonly>";
		$la_objectconcepto[$li_i][2]="<input name=txtdenpro".$li_i." type=text id=txtdenpro".$li_i." value='".$ls_denpro."' class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectconcepto[$li_i][3]="<input name=txtcodalm".$li_i." type=text id=txtcodalm".$li_i." value='".$ls_codalm."' size=11 maxlength=10 class=sin-borde style= text-align:left>";
		$la_objectconcepto[$li_i][4]="<input name=txtdesalm".$li_i." type=text id=txtdesdalm".$li_i." value='".$ls_desalm."' size=20 maxlength=255 class=sin-borde style= text-align:left readonly>";
		$la_objectconcepto[$li_i][5]="<input name=txtpreuni".$li_i." type=text id=txtpreuni".$li_i." value='".$ls_preuni."' class=sin-borde size=10 style= text-align:right readonly>";
		$la_objectconcepto[$li_i][6]="<input name=txtporcar".$li_i." type=text id=txtporcar".$li_i." value='".$ls_porcar."' class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_i." type=hidden id=txtmoncar".$li_i." value='".$ls_moncar."' class=sin-borde style= text-align:center readonly>";
		if ($ls_estcot=="" or $ls_estcot=="E")
		{
		$la_objectconcepto[$li_i][7]="<input name=txtcanpro".$li_i." onChange=javascript:ue_subtotal(); type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=10 style= text-align:right onKeyPress=return(currencyFormat(this,'.',',',event))>";
		}
		else
		{
		$la_objectconcepto[$li_i][7]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=10 style= text-align:right readonly>";
		}
		$la_objectconcepto[$li_i][8]="<input name=txttotpro".$li_i." type=text id=txttotpro".$li_i." value='".$ls_totpro."' class=sin-borde size=12 style= text-align:right readonly>";
		$la_objectconcepto[$li_i][9]="<input name=txtprov".$li_i." type=text id=txtprov".$li_i." value='".$ls_prov."' class=sin-borde size=12 style= text-align:right readonly><input name=txtcodprov".$li_i." type=hidden id=txtcodprov".$li_i." value='".$ls_codprov."' class=sin-borde style=text-center readonly>";

if ($ls_estcot=="" or $ls_estcot=="E")
		{
		$la_objectconcepto[$li_i][10]="<a href=javascript:ue_removerconcepto(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}
		else
		{
		$la_objectconcepto[$li_i][10]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center>";
		}
	}
		 $li_filasconcepto=$li_i;
		$la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=21 style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][3]="<input name=txtcodalm".$li_filasconcepto." type=text id=txtcodalm".$li_filasconcepto."  size=11 maxlength=10 class=sin-borde style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][4]="<input name=txtdesalm".$li_filasconcepto." type=text id=txtdesalm".$li_filasconcepto."  size=20 class=sin-borde style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][5]="<input name=txtpreuni".$li_filasconcepto." type=text id=txtpreuni".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly>";
		$la_objectconcepto[$li_filasconcepto][6]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][7]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right>";
		$la_objectconcepto[$li_filasconcepto][8]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly>";
		$la_objectconcepto[$li_filasconcepto][9]="<input name=txtprov".$li_filasconcepto." type=text id=txtprov".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly><input name=txtcodprov".$li_filasconcepto." type=hidden id=txtcodprov".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][10]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
  		 }
	}

}
elseif($ls_operacion=="ue_removerconcepto")
{
  	$li_filasconcepto=$_POST["filasconcepto"];
	$li_filasconcepto=$li_filasconcepto - 1;
	$li_removerconcepto=$_POST["hidremoverconcepto"];
	$li_temp=0;
	for($li_i=1;$li_i<=$li_filasconcepto;$li_i++)
	{
		if ($li_i!=$li_removerconcepto)
		{
		 $li_temp=$li_temp+1;
  		$ls_codpro=$_POST["txtcodpro".$li_i];
		$ls_denpro=$_POST["txtdenpro".$li_i];
		$ls_preuni=$_POST["txtpreuni".$li_i];
		$ls_porcar=$_POST["txtporcar".$li_i];
		$ls_moncar=$_POST["txtmoncar".$li_i];
		$ls_canpro=$_POST["txtcanpro".$li_i];
		$ls_totpro=$_POST["txttotpro".$li_i];
		$ls_codalm=$_POST["txtcodalm".$li_i];
		$ls_desalm=$_POST["txtdesalm".$li_i];
		$ls_codprov=$_POST["txtcodprov".$li_i];
		$ls_prov=$_POST["txtprov".$li_i];
		$la_objectconcepto[$li_temp][1]="<input name=txtcodpro".$li_temp." type=text id=txtcodpro".$li_temp." value='".$ls_codpro."' class=sin-borde size=21 style= text-align:center readonly>";
		$la_objectconcepto[$li_temp][2]="<input name=txtdenpro".$li_temp." type=text id=txtdenpro".$li_temp." value='".$ls_denpro."' class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectconcepto[$li_temp][3]="<input name=txtcodalm".$li_temp." type=text id=txtcodalm".$li_temp." value='".$ls_codalm."' class=sin-borde size=11 maxlength=10 style= text-align:left>";
		$la_objectconcepto[$li_temp][4]="<input name=txtdesalm".$li_temp." type=text id=txtdesalm".$li_temp." value='".$ls_desalm."' class=sin-borde size=20 maxlength=255 style= text-align:left readonly>";
		$la_objectconcepto[$li_temp][5]="<input name=txtpreuni".$li_temp." type=text id=txtpreuni".$li_temp." value='".$ls_preuni."' class=sin-borde size=10 style= text-align:right readonly>";
		$la_objectconcepto[$li_temp][6]="<input name=txtporcar".$li_temp." type=text id=txtporcar".$li_temp." value='".$ls_porcar."' class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_temp." type=hidden id=txtmoncar".$li_temp." value='".$ls_moncar."' class=sin-borde style= text-align:center readonly>";
	    $la_objectconcepto[$li_temp][7]="<input name=txtcanpro".$li_temp." onChange=javascript:ue_subtotal(); type=text id=txtcanpro".$li_temp." value='".$ls_canpro."' class=sin-borde size=10 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event))>";
		$la_objectconcepto[$li_temp][8]="<input name=txttotpro".$li_temp." type=text id=txttotpro".$li_temp." value='".$ls_totpro."' class=sin-borde size=12 style= text-align:center readonly>";
		$la_objectconcepto[$li_temp][9]="<input name=txtprov".$li_temp." type=text id=txtprov".$li_temp." value='".$ls_prov."' class=sin-borde size=12 style= text-align:center readonly><input name=txtcodprov".$li_temp." type=hidden id=txtcodprov".$li_temp." value='".$ls_codprov."' class=sin-borde style=text-align:center readonly>";
		$la_objectconcepto[$li_temp][10]="<a href=javascript:ue_removerconcepto(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}
	}
        $la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=21 style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][3]="<input name=txtcodalm".$li_filasconcepto." type=text id=txtcodalm".$li_filasconcepto."  size=11 maxlength=10 class=sin-borde style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][4]="<input name=txtdesalm".$li_filasconcepto." type=text id=txtdesalm".$li_filasconcepto."  size=20 class=sin-borde style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][5]="<input name=txtpreuni".$li_filasconcepto." type=text id=txtpreuni".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly>";
		$la_objectconcepto[$li_filasconcepto][6]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][7]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right>";
		$la_objectconcepto[$li_filasconcepto][8]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly>";
		$la_objectconcepto[$li_filasconcepto][9]="<input name=txtprov".$li_filasconcepto." type=text id=txtprov".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly><input name=txtcodprov".$li_filasconcepto." type=hidden id=txtcodprov".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][10]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
}
elseif($ls_operacion=="ue_guardar")
{
	$lb_existe=$io_cotizacion->uf_select_cotizacion($ls_numcot);
	if ($lb_existe and $ls_operacion1!="ue_nuevo"){}
	elseif ($ls_operacion1=="ue_nuevo")
	{
		$ls_codcaj=$_SESSION["ls_codcaj"];
		$ls_prefijo=$_SESSION["ls_precot"];
		$ls_serie=$_SESSION["ls_sercot"];
		$io_secuencia->uf_obtener_secuencia($ls_codcaj.$ls_codtie."cot",&$ls_secuencia);		
		$ls_numcot=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);
    }
   $la_detalles["codpro"][1]="";
   $la_detalles["preuni"][1]="";
   $la_detalles["canpro"][1]="";
   $la_detalles["impcot"][1]="";
   $la_detalles["codalm"][1]="";
   $la_detalles["desalm"][1]="";
   $la_detalles["codprov"][1]="";
   $la_detales["nompro"][1]="";
   $cantidad_pro=true;
   for ($li_i=1;$li_i<$li_filasconcepto;$li_i++)
   {
	   $la_detalles["codpro"][$li_i]=$_POST["txtcodpro".$li_i];
	   $la_detalles["preuni"][$li_i]=$_POST["txtpreuni".$li_i];
	   $la_detalles["canpro"][$li_i]=$_POST["txtcanpro".$li_i];
	   $la_detalles["impcot"][$li_i]=$_POST["txtporcar".$li_i];
	   $la_detalles["codalm"][$li_i]=$_POST["txtcodalm".$li_i];
	   $la_detalles["desalm"][$li_i]=$_POST["txtdesalm".$li_i];
	   $la_detalles["codprov"][$li_i]=$_POST["txtcodprov".$li_i];
	   $la_detalles["nompro"][$li_i]=$_POST["txtprov".$li_i];
	   if ($la_detalles["canpro"][$li_i]=="" or $la_detalles["canpro"][$li_i]=="0,00")
	   {
		  $io_msg->message ("Debe colocar la cantidad del producto a vender!!!");
		  $cantidad_pro=false;
		}
	}
	if ($cantidad_pro==true)
 	{

      $ls_sql="SELECT * FROM sfc_cotizacion WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND numcot='".$ls_numcot."';";
	  $rs_datauni=$io_sql->select($ls_sql);
	  $la_cotizacion=$io_sql->obtener_datos($rs_datauni);
	  $io_datastore->data=$la_cotizacion;
      $ls_estcot=$io_datastore->getValue("estcot",1);
      if ($ls_estcot=="E")
	  {
	   $io_msg->message ("¡Cotización EMITIDA!");
	  }
      elseif ($ls_estcot=="P")
	  {
	  $io_msg->message ("¡Cotización EN PROCESO no se puede modificar!");
	  }
	  elseif ($ls_estcot=="F")
	  {
	  $io_msg->message ("¡Cotización FACTURADA no se puede modificar!");
	  }
	  else
	  {
	  $ls_estcot="E";
	  }
	  if ($ls_estcot=="E")
      {
			if (($lb_existe) and ($ls_operacion1!="ue_nuevo"))
			{
		 		$io_msg->message ("La Cotización con el codigo: ".$ls_numcot." fue actualizada satisfactoriamente");
			}
			elseif ($ls_operacion1=="ue_nuevo" )
			{
				$ls_operacion1="";
				$io_msg->message ("La Cotización sera guardada con el codigo: ".$ls_numcot);
			}
		$lb_valido=$io_cotizacion->uf_guardar_cotizacion($ls_codcli,$ls_numcot,$ls_codusu,$ls_feccot,$ls_obscot,$ls_monto,$ls_estcot,$la_detalles,$li_filasconcepto,$sub_total,$la_seguridad);
 	  	}
	} 
}
elseif($ls_operacion=="ue_buscarcli")
{
  $ls_nacionalidad=substr($ls_cedcli,0,1);
  $ls_cedcli=substr($ls_cedcli,1,10);
	if (strlen($ls_cedcli)<10)
	{
		$ls_cedcli=$io_function->uf_cerosizquierda($ls_cedcli,9);
	}
	$ls_cedcli=$ls_nacionalidad.$ls_cedcli;
  $ls_sql="SELECT codcli,razcli,cedcli FROM sfc_cliente WHERE codemp='".$ls_codemp."' " .
  		" AND cedcli='".$ls_cedcli."';";
 // print $ls_sql;
	$rs_datauni=$io_sql->select($ls_sql);
	$la_cliente=$io_sql->obtener_datos($rs_datauni);
	$io_datastore->data=$la_cliente;
	$totrow=$io_datastore->getRowCount("cedcli");
	if($totrow!=0)
	{
	  $ls_codcli=$io_datastore->getValue("codcli",1);
	  $ls_nomcli=$io_datastore->getValue("razcli",1);
	  $ls_cedcli=$io_datastore->getValue("cedcli",1);
	 }
	else
	{


	/*print("<script language=JavaScript>");
	print("pagina='sigesp_sfc_d_cotizacion.php';");
    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
    print("</script>");*/


	$io_msg->message ("¡¡¡¡El Cliente no se encuentra registrado, verifique nro. de Cédula, sino dirijase al registro de cliente para realizar un nuevo registro!!!!");
	$ls_codcli="";
	$ls_nomcli="";
	$ls_cedcli="";
	?>
		<script language=JavaScript>
		if(confirm('¿Desea verificar nro. de cédula nuevamente?'))
		{
			//alert ('paso');
			f=document.form1;
			f.txtcodcli.value='';f.txtcodcli1.value='';
		}
		else
		{
			location.href='sigesp_sfc_d_cliente.php';
		}
		</script>
	<?php
}
}
elseif($ls_operacion=="ue_anular")
{
	$ls_sql="SELECT * FROM sfc_cotizacion WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND numcot='".$ls_numcot."';";

	$rs_datauni=$io_sql->select($ls_sql);
	$la_cotizacion=$io_sql->obtener_datos($rs_datauni);
	$io_datastore->data=$la_cotizacion;
    $ls_estcot=$io_datastore->getValue("estcot",1);//ï¿½

	if ($ls_estcot=="E")
	 {
	      // $io_msg->message ("Â¡CotizaciÃ³n EMITIDA!");
	 }
	elseif ($ls_estcot=="P")
	 {
	  $io_msg->message ("¡Cotización EN PROCESO no se puede modificar!");
	 }
	elseif ($ls_estcot=="F")
	 {
	  $io_msg->message ("¡Cotización FACTURADA no se puede modificar!");
	 }
	else
	 {
	  $ls_estcot="E";
	 }

	if ($ls_estcot=="E")
     {

/************  verificar si cajero generï¿½ "FACTURA"***************************/
	$ls_sql="SELECT * FROM sfc_factura WHERE codemp='".$la_datemp["codemp"]."' AND codtiend='".$ls_codtie."' AND numcot='".$ls_numcot."'";

		$rs_datauni=$io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido_fac=false;
			$io_msg="Error en uf_select_factura ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_fac=true; //Registro encontrado
		        $io_msg->message ("Esta cotización se encuentra enlazada con una factura, no se puede eliminar!!!");
			}
			else
			{
				$lb_valido_fac=false; //"Registro no encontrado"

			}
		}
		$ls_cadena="SELECT * FROM sfc_cotizacion WHERE sfc_cotizacion.numcot >'".$ls_numcot."' AND sfc_cotizacion.feccot='".date("Y-m-d")."'";
		//print $ls_cadena;
		$lb_valido_cot=false;
		$rs_datauni2=$io_sql->select($ls_cadena);
			if($row=$io_sql->fetch_row($rs_datauni2))
			{
			  $lb_valido_cot=true;
			  $io_msg->message ("Esta cotización no puede ser eliminada, tiene cotizaciones posteriores a su correlativo!!!");

			}
   /**************************************************************************************************************/
  			// print $ls_cod;
			if ($lb_valido_fac==false and $lb_valido_cot==true) // si cotizaciï¿½n no esta enlaza con factura ï¿½eliminar!
			{

			//print $ls_secuencia;
			//$ls_secuencia=$io_function->uf_cerosizquierda($ls_secuencia,16);
			//print $ls_secuencia;
			$lb_valido=$io_cotizacion->uf_delete_detcotizacion($ls_numcot,$la_seguridad);

			   if ($lb_valido===true)
					{
							$io_msg->message($io_cotizacion->io_msgc);

		$li_filasconcepto=1;
		$la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=21 style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][3]="<input name=txtcodalm".$li_filasconcepto." type=text id=txtcodalm".$li_filasconcepto."  size=11 maxlength=10 class=sin-borde style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][4]="<input name=txtdesalm".$li_filasconcepto." type=text id=txtdesalm".$li_filasconcepto."  size=20 class=sin-borde style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][5]="<input name=txtpreuni".$li_filasconcepto." type=text id=txtpreuni".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly>";
		$la_objectconcepto[$li_filasconcepto][6]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][7]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right>";
		$la_objectconcepto[$li_filasconcepto][8]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly>";
		$la_objectconcepto[$li_filasconcepto][9]="<input name=txtprov".$li_filasconcepto." type=text id=txtprov".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly><input name=txtcodprov".$li_filasconcepto." type=hidden id=txtcodprov".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][10]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
					}
			   $lb_valido=$io_cotizacion->uf_delete_cotizacion($ls_numcot,$la_seguridad);
			   $io_secuencia->uf_ver_secuencia($ls_codcaj.$ls_codtie."cot",&$ls_secuencia);
			   $ls_secuencia=$ls_secuencia;
			   //print $ls_secuencia;
			   $io_secuencia->uf_eliminar_secuencia($ls_codcaj.$ls_codtie.'cot');
			   $io_secuencia->uf_crear_secuencia($ls_codcaj.$ls_codtie."cot",$ls_secuencia);
			  // $ls_numcot=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);
				if ($lb_valido===true)
					{
							//$io_msg->message($io_cotizacion->io_msgc);
							$ls_operacion="";
							$ls_numcot="";
							$ls_feccot="";
							$ls_codcli="";
							$ls_cedcli="";
							$ls_nomcli="";
							$ls_obscot="";
							/*$ls_subtot="0,00";
							$ls_moniva="0,00";*/
							$ls_monto="0,00";
							$ls_estcot="";
					 }
			 }//cierre del if->$lb_valido_fac
	}//	$ls_estcot=="E"
	print("<script language=JavaScript>");
	print("pagina='sigesp_sfc_d_cotizacion.php';");
    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
    print("</script>");
} // fin "ue_anular"

else if($ls_operacion=="VER")
{
    $ls_operacion="";
	//print 'paso';
  	$lb_valido_repcot=$io_cotizacion->uf_imprimir_cotizacion($ls_numcot,&$ls_sql);
	if ($lb_valido_repcot==true)
	{
	  $sub_total="prueba";
?>

     <script language="JavaScript">
   	 	var ls_sql="<?php print $ls_sql; ?>";
		var sub_total="<?php print $sub_total; ?>";
	   	pagina="reportes/sigesp_sfc_rep_cotizacion.php?sql="+ls_sql+"&total="+sub_total;
	  	popupWin(pagina,"catalogo",580,700);
     </script>

<?php
    }
}

?>

<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="492" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1">Sistema de Facturaci&oacute;n</span></td>
    <td width="286" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">&nbsp;</td>
    <td height="20" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><div align="left"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar(<?php $li_i?>);"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><a href="javascript:ue_ver();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
  </tr>
</table>
  <p>&nbsp;
  </p>
  <form name="form1" method="post" action="">
    <?php
   /*********************************         SEGURIDAD               *************************************/
     if (($ls_permisos)||($ls_logusr=="PSEGIS"))
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
   //************************************         SEGURIDAD          ***********************************************/
?>
    <input name="hidestobr" type="hidden" id="hidestobr" value="<? print $ls_estobr ?>">
    <input name="hidmonobr" type="hidden" id="hidstaobr" value="<? print $ls_monobr ?>">
    <input name="hidmonasi" type="hidden" id="hidmonasi" value="<? print $ls_monasi ?>">
  <input name="txtcanpro" type="hidden" id="txtcanpro">
  <input name="txtporcar" type="hidden" id="txtporcar">
  <input name="txtmoncar" type="hidden" id="txtmoncar">
  <label>
  <input name="txtitem" type="hidden" id="txtitem" value="<?php print $ls_item ?>">
  </label>
  <table width="780" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">


	  <tr class="titulo-celdanew">
        <th colspan="9" scope="col" class="titulo-celdanew">Cotizacion</th>
    </tr>
      <tr class="formato-blanco">
        <td  colspan="9"><input name="operacion" type="hidden" id="operacion">
		<input name="operacion1" type="hidden" id="operacion1" value="<?php print $ls_operacion1 ?>">
		<input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_hidstauts ?>">
        <input name="opemostrar" type="hidden" id="opemostrar" value="<? print $ls_opemostrar ?>">
        <input name="txtestcot" type="hidden" id="txtestcot" value="<?php print $ls_estcot ?>"></td>
      </tr>
      <tr class="formato-blanco">
        <td height="20"><input name="hidremoverconcepto2" type="hidden" id="hidremoverconcepto2"></td>
        <td>&nbsp;</td>
        <td width="153">&nbsp;</td>
        <td colspan="3">&nbsp;</td>
        <td width="91">&nbsp;</td>
        <td width="84">&nbsp;</td>
        <td width="135">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
	  <?php
		if ($ls_operacion=="ue_guardar" or $ls_estcot=="E" or $ls_estcot=="F"){
		?>
        <td width="40" height="22"><div align="right"></div></td>
        <td width="77"><div align="right">N&ordm; Cotizacion </div></td>
        <td>
		<input name="txtnumcot" type="text" id="txtnumcot" style="text-align:center " value="<? print $ls_numcot ?>" size="28" maxlength="25"  >
		<?php
		}else{
		?>
		<td width="40" height="22"><div align="right"></div></td>
        <td>
		<input name="txtnumcot" type="hidden" id="txtnumcot" style="text-align:center " value="<? print $ls_numcot ?>" size="28" maxlength="25"  >
		<?php
		}
		?>

        <div align="right"></div></td>
        <td colspan="2"><label class="titulo-cat&aacute;logo">
		<?php
		if ($ls_estcot=="E")
		{
		?>
		<font color="#006600">EMITIDA</font>
		<?php
		}
		elseif ($ls_estcot=="P")
		{
		?>
		<font color="#006600">EN PROCESO</font>
		<?php
		}
		elseif ($ls_estcot=="F")
		{
		?>
		<font color="#006600">FACTURADA</font>
		<?php
		}
		elseif ($ls_estcot=="")
		{
		?>
		<font color="#006600"></font>
		<?php
		}
		?>
		</label></td>
        <td><div align="right">Fecha</div></td>
        <td colspan="3">
			<?php
		if ($ls_estcot=="")
		{
		?>
		<input name="txtfeccot" type="text" id="txtfeccot"  style="text-align:left" value="<? print $ls_feccot?>" size="11" maxlength="10"   onKeyPress="javascript:ue_validafecha();" datepicker="true" readonly="true">
		 <?php
		 }
		 else
		 {
		 ?>
		 <input name="txtfeccot" type="text" id="txtfeccot"  style="text-align:left" value="<? print $ls_feccot?>" size="11" maxlength="10"   readonly="true">
		 <?php
		 }
		 ?>

		</td>
      </tr>
       <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right">Cliente</div></td>
		<?php
		if ($ls_cedcli!="")
		{
			$ls_nacionalidad=substr($ls_cedcli,0,1);
			$ls_cedcli=substr($ls_cedcli,1,10);
			if (strlen($ls_cedcli)<10)
				{
				$ls_cedcli=$io_function->uf_cerosizquierda($ls_cedcli,9);
				}
			$ls_cedcli=$ls_nacionalidad.$ls_cedcli;
		}
		?>
        <td colspan="7"><input  name="txtcodcli" type="text" id="txtcodcli" style="text-align:center " value="<?
		if ($ls_cedcli!="")
		{
		print $ls_cedcli;}
		 ?>" size="15" maxlength="10" onBlur="ue_buscarcli();" onKeyPress="return(validaCajas(this,'z',event,254))" title="El RIF Debe comenzar con J,V,G ó E">
		<input name="txtcodcli1" type="hidden" id="txtcodcli1" style="text-align:center " value="<? print $ls_codcli ?>" size="15" maxlength="10">
		<?php
		if ($ls_estcot=="")
		{
		?>
          <a href="javascript:ue_catclientecot();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
		 <?php
		 }
		 ?>

        <input name="txtnomcli" type="text" id="txtnomcli"  style="text-align:left" class="sin-borde" value="<? print $ls_nomcli ?>" size="70" maxlength="100" readonly="true">        </td></tr>

      <tr class="formato-blanco">
        <td height="22"><div align="right"></div></td>
        <td height="22"><div align="right">Observacion</div></td>
        <td colspan="7">
		<?php
		if ($ls_estcot=="")
		{
		?>
		<textarea name="txtobscot" cols="80" rows="2" id="txtobscot" onKeyPress="return(validaCajas(this,'x',event))"  onKeyDown="textCounter(this,254)" ><? print $ls_obscot ?></textarea>
		 <?php
		 }
		 else
		 {
		 ?>
		<textarea name="txtobscot" cols="80" rows="2" id="txtobscot" onKeyPress="return(validaCajas(this,'x',event))"  onKeyDown="textCounter(this,254)" readonly="readonly" ><? print $ls_obscot ?></textarea>
        <?php
		}
		?>

		</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="9">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="9"></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="9"><div align="center">
          <table width="609" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr class="formato-blanco">
              <td width="14" height="11">&nbsp;</td>
              <td width="593">
				<?php
				if ($ls_estcot=="" or ($ls_estcot=="E" and $ls_feccot==date('d/m/Y')))
				{
				?>
			  <a href="javascript:ue_catproducto();"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:ue_catproducto();">Agregar Detalle </a>
			   <?php
			   }
			   else
			   {
			   ?>
			<img src="../shared/imagebank/mas.gif" width="9" height="17" border="0">Agregar Detalle
 			  <?php
			  }
			  ?>


			  </td>
            </tr>
            <tr align="center" class="formato-blanco">
              <td height="11" colspan="2"><?php $io_grid->makegrid($li_filasconcepto,$la_columconcepto,$la_objectconcepto,$li_anchoconcepto,$ls_tituloconcepto,$ls_nametable);?>              </td>
              <input name="filasconcepto" type="hidden" id="filasconcepto" value="<? print $li_filasconcepto;?>">
			  <input name="hidremoverconcepto" type="hidden" id="hidremoverconcepto" value="">
            </tr>
            <tr class="formato-blanco">
              <td colspan="2">&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>
      <tr class="formato-blanco">
        <td height="23">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td width="25">&nbsp;</td>
        <td width="115">&nbsp;</td>
        <td width="58">&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right">Sub-Total</div></td>
        <td><input name="txtsubtot" type="text" id="txtsubtot"  style="text-align: right" value="<? print $ls_subtot ?>" size="20" maxlength="20" readonly="true"></td>
      </tr>
      <tr class="formato-blanco">
        <td height="23">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right">IVA</div></td>
        <td><input name="txtmoniva" type="text" id="txtmoniva"  style="text-align: right" value="<? print $ls_moniva ?>" size="20" maxlength="20" readonly="true"></td>
      </tr>

      <tr class="formato-blanco">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right">Total</div></td>
        <td><input name="txtmonto" type="text" id="txtmonto"  style="text-align: right" value="<? print number_format($ls_monto,2,',','.'); ?>" size="20" maxlength="20"  readonly="true"></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="9"><div align="center"></div></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="9">&nbsp;</td>
      </tr>
    </table>
  <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_chk ?>">
  <div align="center"></div>
  </form>
</body>

<script language="javascript">
/*function ue_valida_nuevo(field,campo)
{
	with (field) 
  {
    if (value==null||value==""||value=="s1" || value=="---")
      {
        alert(campo+" para poder realizar el proceso!!!");
        return false;
      }
    else
      {
   	    return true;
      }
  }
}*/
function ue_validar_cabecera()
{
    suiche=true;
	f=document.form1;
	ls_item="<?php print $_SESSION["ls_item"]?>";
	filas_concepto="<? print $li_filasconcepto;?>";
	//alert (ls_item);
	with(f)
		{
		  if (parseFloat(filas_concepto)>ls_item)
			 {
			 	alert("Ha excedido el número maximo de líneas en la cotización!")
				suiche=false;
			 }
		   else if (ue_valida_nuevo(txtnumcot,"!!Debe hacer clic en el boton nuevo!!!")==false)
			 {
			 	txtnumcot.focus();
				suiche=false;
				
			 }
			else if (ue_valida_null(txtcodcli,"Cliente")==false)
			 {
				txtcodcli.focus();
				suiche=false;
			 }
			else if (ue_valida_null(txtfeccot,"Fecha")==false)
			 {
				  txtfeccot.focus();
				  suiche=false;
			 }
			 else if (ue_valida_null(txtobscot,"Observaciones")==false)
			 {
				  txtobscot.focus();
				  suiche=false;
			 }
			else if (ue_valida_null(txtmonto,"Monto")==false)
			 {
				  txtmonto.focus();
				  suiche=false;
			 }


		}
	return suiche;

}

function ue_ver()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
  	if(li_imprimir==1){
  		f.operacion.value="VER";
		f.action="sigesp_sfc_d_cotizacion.php";
		f.submit();
  	}else{
  		alert("No tiene permiso para realizar esta operacion");
  	}

}

function ue_buscar()
{
  f=document.form1;
  li_leer=f.leer.value;
  if(li_leer==1)
   {
     f.operacion.value="";
     pagina="sigesp_cat_cotizacion.php";
     popupWin(pagina,"catalogo",850,450);
	 //window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=350,resizable=yes,location=no");
   }
	else
	{
	  alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_cargarcotizacion(codigo,cedula,cotizacion,nombre,fecha,monto,obs,estcot)
{
	f=document.form1;

	f.txtnumcot.value=cotizacion;
	f.txtcodcli.value=cedula;
	f.txtcodcli1.value=codigo;
	f.txtfeccot.value=fecha;
	f.txtnomcli.value=nombre;
 	f.txtobscot.value=obs;
	f.txtmonto.value=monto;
	f.txtestcot.value=estcot;
	f.operacion.value="ue_cargarconcepto_cotizacion";
	f.action="sigesp_sfc_d_cotizacion.php";
	f.submit();
}

function ue_cargarcliente(codcli,cedcli,nomcli,dircli,telcli,celcli,codpai,codest,codmun,codpar,producto,precioestandar,tentierra)
{
    f=document.form1;
	f.txtcodcli1.value=codcli;
	f.txtcodcli.value=cedcli;
	f.txtnomcli.value=nomcli;
}

function ue_catclientecot()
{
    f=document.form1;
	f.operacion.value="";	
	if (ue_valida_nuevo(txtnumcot,"!!Debe hacer clic en el boton nuevo!!!")==false)
	{
	 	txtnumcot.focus();
		suiche=false;
				
	}else
	{
	pagina="sigesp_cat_cliente.php";
	popupWin(pagina,"catalogo",520,350);
	}
	
}

function ue_cargarproducto(codpro,denpro,tippro,preven,codcar,dencar,codcla,dencla,codart,denart,ultcosart,spi_cuenta,denspi,sc_cuenta,denscg,moncar,porgan,tipcos,prepro,preven1,preven2,preven3,cosfle,cosproart,porcar,codalm,desalm,exi,cod_pro,nompro)
{
    f=document.form1;
	ls_item="<?php print $_SESSION["ls_item"]?>";
	f.operacion.value="ue_cargarconcepto";
	lb_existe=false;
/*	alert (ls_item);
	alert(f.filasconcepto.value);*/
	 if (parseFloat(f.filasconcepto.value)>ls_item)
	 {
		alert("Ha excedido el número maximo de líneas en la cotización!")
		suiche=false;
	 }
	 else
	 {
	for(li_i=1;li_i<=f.filasconcepto.value && !lb_existe;li_i++)
	{
		ls_codigo=eval("f.txtcodpro"+li_i+".value");
		ls_almacen=eval("f.txtcodalm"+li_i+".value");
		if(ls_codigo==codpro)
		{
			alert("La Cuenta ya ha sido cargada!!!");
			lb_existe=true;
		}
	}

	if(!lb_existe)
	{
		eval("f.txtcodpro"+f.filasconcepto.value+".value='"+codpro+"'");
		eval("f.txtcodalm"+f.filasconcepto.value+".value='"+codalm+"'");
		eval("f.txtdesalm"+f.filasconcepto.value+".value='"+desalm+"'");
		eval("f.txtdenpro"+f.filasconcepto.value+".value='"+denpro+"'");
		eval("f.txtpreuni"+f.filasconcepto.value+".value='"+preven1+"'");
		eval("f.txtporcar"+f.filasconcepto.value+".value='"+porcar+"'");
		eval("f.txtmoncar"+f.filasconcepto.value+".value='"+moncar+"'");
		eval("f.txtprov"+f.filasconcepto.value+".value='"+nompro+"'");
		eval("f.txtcodprov"+f.filasconcepto.value+".value='"+cod_pro+"'");
		suiche_submit=true;
    	f.submit();
	}
}
}

function ue_catproducto()
{

	if (f.txtestcot.value=="F")
	  {
	    alert("Cotización esta FACTURADA no se puede modificar!!!");
	  }
	else if (f.txtestcot.value=="P")
	  {
	  alert("Cotización esta EN PROCESO no se puede modificar!!!");
	  }
	else
	  {

			validar=ue_validar_cabecera();
			if (validar==true)
			{
				f=document.form1;
				f.operacion.value="";
				pagina="sigesp_cat_producto_almacen.php";
				popupWin(pagina,"catalogo",900,300);
			}
	  }

}

function ue_removerconcepto(li_fila)
{


	f=document.form1;
	f.hidremoverconcepto.value=li_fila;
	f.operacion.value="ue_removerconcepto";
	f.action="sigesp_sfc_d_cotizacion.php";
	f.submit();

}

function ue_guardar(li_fila)
{

	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidstatus.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{

		if (lb_status!="C")
		{
			f.hidstatus.value="C";
		}

		with(f)
			{
				if (ue_valida_null(txtcodcli,"Cliente")==false)
				 {
				 	txtcodcli.focus();
				 }
				else if (ue_valida_null(txtnumcot,"No de CotizaciÃ³n")==false)
				 {
					txtnumcot.focus();
				 }
				else if (ue_valida_null(txtfeccot,"Fecha")==false)
				 {
					  txtfeccot.focus();
				 }
				 else if (ue_valida_null(txtobscot,"Observaciones")==false)
				 {
					  txtobscot.focus();
				 }
				else if (ue_valida_null(txtmonto,"Monto")==false)
				 {
					  txtmonto.focus();
				 }
				 else if (f.filasconcepto.value==1)
				 {

				alert("Introduzca un producto");				

				 }
				 
				 else if (f.txtestcot.value=="F")
		 		 {
		  			alert("La Cotización ya está Facturada no puede ser  MODIFICADA!!");
		  			}

				 else
				 {
				 if (f.filasconcepto.value>1)
				 {	
				 	lb_cantval=true;	
				  for (i=1;i<=f.filasconcepto.value;i++)
				     {

				       if (eval("f.txtcanpro"+i+".value")=='0,00')
					   {
					   lb_cantval=false;
					   alert ("Debe colocar una cantidad valida al producto");
					   canpro+i.focus();
						
						}

					 }
				 }
					if (lb_cantval==true)
					{
					f.operacion.value="ue_guardar";
					f.action="sigesp_sfc_d_cotizacion.php";
					f.submit();
					}
				 }

			}
	}else{
		alert("No tiene permiso para realizar esta operacion");
	}

}

function limpiar()
{
	f=document.form1;
	f.txtnumcot.value="";
	f.txtfeccot.value="";
	f.txtcodcli.value="";
	f.txtcodcli1.value="";
	f.txtnomcli.value="";
	f.txtobscot.value="";
	f.txtsubtot.value="";
	f.txtmoniva.value="";
	f.txtmonto.value="";
	f.filasconcepto.value="";
	f.hidremoverconcepto.value="";
}
/*********************************GENERAR NUEVO******************************************************************/
function ue_nuevo()
{
  f=document.form1;
  li_incluir=f.incluir.value;
  if(li_incluir==1)
   {

    f.operacion.value="ue_nuevo";
    f.txtnumcot.value="";
	f.txtfeccot.value="";
	f.txtcodcli.value="";
	f.txtcodcli1.value="";
	f.txtnomcli.value="";
	f.txtobscot.value="";
	f.txtsubtot.value="";
	f.txtmoniva.value="";
	f.txtmonto.value="";
	f.filasconcepto.value="";
	f.hidremoverconcepto.value="";

     f.action="sigesp_sfc_d_cotizacion.php";
     f.submit();
   }
   else
   {
     alert("No tiene permiso para realizar esta operacion");
   }
}

function ue_removerproducto(li_fila)
{
	f=document.form1;
	f.hidremovercargo.value=li_fila;
	f.operacion.value="ue_removercargo"
	f.action="sigesp_sob_d_asignacion.php";
	f.submit();
}
function uf_validacaracter(cadena, obj)
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
     if (obj.value.search("[.*]") == -1 && obj.value.length != 0)
     if (event.keyCode == 46)
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
 }

function esDigito(sChr)
{
    var sCod = sChr.charCodeAt(0);
    return ((sCod > 47) && (sCod < 58));
}

function ue_subtotal()
{
 // alert("sub_in");
	f=document.form1;
	li_filasconcepto=f.filasconcepto.value;
	ld_subtotal=0;
	ld_iva=0;
	ld_monto=0;
	ls_cero="0,00"
	
	for(li_i=1;li_i<=li_filasconcepto-1;li_i++)
	{
	//alert (eval("f.txtcanpro"+li_i+".value"));
	   if(eval("f.txtcanpro"+li_i+".value")=="")
		  {
		   ld_canpro=0;
		   //alert("No le coloco cantidad a una de las partidas seleccionadas");
		  }
	  else
		  {
		   ld_canpro=parseFloat(uf_convertir_monto(eval("f.txtcanpro"+li_i+".value")));
		   ld_moncar=parseFloat(uf_convertir_monto(eval("f.txtmoncar"+li_i+".value")));
		   ld_porcar= parseFloat(uf_convertir_monto(eval("f.txtporcar"+li_i+".value")));
			 //tomando el precio unitario de la partida
		   if(eval("f.txtpreuni"+li_i+".value")=="")
		      {
		       ld_preuni=0;
		      }
		      else
		      {
		         ld_preuni=parseFloat(uf_convertir_monto(eval("f.txtpreuni"+li_i+".value")));
		      }
		       //tomando la cantidad asignada de la partida

		       ld_totpar=roundNumber(ld_preuni)*roundNumber(ld_canpro);

			   ld_iva=roundNumber(ld_iva) + (roundNumber(ld_totpar)*roundNumber(ld_porcar/100));

			   ld_subtotal=roundNumber(ld_subtotal)+roundNumber(ld_totpar);

			   ls_totp=uf_convertir(roundNumber(ld_totpar));
		       eval("f.txttotpro"+li_i+".value='"+ls_totp+"'");
			   ld_monto=roundNumber(ld_subtotal)+roundNumber(ld_iva);
				//alert (ld_monto);
		 }
	}

	f.txtsubtot.value=uf_convertir(roundNumber(ld_subtotal));
	// alert (ld_iva);
	//f.txtmoniva.value=roundNumber(ld_iva);
	f.txtmoniva.value=uf_convertir(roundNumber(ld_iva));
	//f.txtmonto.value=roundNumber(ld_monto);
	f.txtmonto.value=uf_convertir(roundNumber(ld_monto));

}
function currencyFormat(fld, milSep, decSep, e)
 {
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
    if (len > 2)
	{
     aux2 = '';
     for (j = 0, i = len - 3; i >= 0; i--)
	 {
      if (j == 3)
	  {
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
	ue_subtotal();
	return false;
}
function uf_validarbasimp()
 {
   f=document.form1;
   if((f.txtmonparasi.value=="")||(f.txtmonparasi.value=="0,00"))
   {
     alert("Debe calcular el Monto a Asignar!!");
   }
   else
   {
     ld_montoasi=parseFloat(uf_convertir_monto(f.txtmonparasi.value));
     if(f.txtbasimpasi.value=="")
      {
        ld_basimp=0;
      }
      else
      {
        ld_basimp=parseFloat(uf_convertir_monto(f.txtbasimpasi.value));
      }

      if(ld_basimp>ld_montoasi)
      {
       alert("la Base Imponible supera al Sub Total de la Asignacion!!");
	   f.txtbasimpasi.value="0,00";
	  }
   }
 }

function ue_validardispo()
{
	f=document.form1;
	ld_montotasi=uf_convertir_monto(f.txtmontotasi.value);
	ld_montotcue=0;
	for(li_i=1;li_i<=f.filascuentas.value;li_i++)
	{

		if(eval("f.txtmoncue"+li_i+".value")=="")
		{
		  ld_monto=0;
		}
		else
		{
		  ld_monto=parseFloat(uf_convertir_monto(eval("f.txtmoncue"+li_i+".value")));
		}
		if(eval("f.disponible"+li_i+".value")=="")
		{
		   ld_dispo=0;
		}
		else
		{
		   ld_dispo=parseFloat(eval("f.disponible"+li_i+".value"));
		}

		//alert("monto->"+ld_monto+" disponible->"+ld_dispo);
		if(ld_monto>ld_dispo)
		{
			alert("El monto asignado a la cuenta es mayor que su Diponibilidad!!");
			eval("f.txtmoncue"+li_i+".value=''")
		}
		ld_montotcue=ld_montotcue+ld_monto;
	}
    //alert("monto total asignado->"+ld_montotasi+" Totales cuentas->"+ld_montotcue);
	if(ld_montotasi<ld_montotcue)
	{
	 alert("El monto asignado a las cuentas sobre pasa el total Asignado");
	}
	f.submit();
}

function ue_validarmontocuentas()
{
	f=document.form1;
	ld_monparasi=uf_convertir_monto(f.txtmonparasi.value);
	ld_montotcue=0;
	lb_flag=false;
	for(li_i=1;li_i<=f.filascuentas.value;li_i++)
	{
		if(eval("f.txtmoncue"+li_i+".value")=="")
		{
		  ld_monto=0;
		}
		else
		{
		  ld_monto=parseFloat(uf_convertir_monto(eval("f.txtmoncue"+li_i+".value")));
		}
		ld_montotcue=ld_montotcue+ld_monto;
    }
    if(ld_montotcue==0)
	{
	 alert("Debe asignar al menos una cuenta de gastos a la Asigancion!!");
	}
	else
	{

	if(ld_monparasi==ld_montotcue)
	{
		lb_flag=true;
	}
	else
	{
	  	alert("El monto asignado a las Cuentas debe coincidir con el sub total Asignado!!");
	}
	}
	return lb_flag;
}
function uf_mostrar_ocultar_obra()
{
	f=document.form1;
	if (f.txtcodobrasi.value=="")
	{
		alert("Debe seleccionar la Obra a Asignar!!");
	}
	else
	{

		if (f.opemostrar.value == "")
		{
			f.opemostrar.value = "MOSTRAR";
	    }
		else
		{
		  if (f.opemostrar.value == "MOSTRAR")
		   {
		     f.opemostrar.value = "";
		   }
		}
		f.submit();
	}
}

function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{
	  if (f.txtnumcot.value=="")
	  {
		alert("Debe seleccionar la CotizaciÃ³n a Anular!!");
	  }
	  else
	  {
	    si=confirm("Esta seguro?");
		 if(si)
		 {
			f.action="sigesp_sfc_d_cotizacion.php";
			f.operacion.value="ue_anular";
			f.submit();
		 }
	  }
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_verificarexistencia()
{
	f=document.form1;
	f.operacion.value="ue_verificarexistencia";
	f.submit();
}

function ue_buscarcli()
{
    f=document.form1;
	f.operacion.value="ue_buscarcli";
	f.submit();

}

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
