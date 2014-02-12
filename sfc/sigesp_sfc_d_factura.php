<?php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "location.href='sigesp_conexion.php';";
	print "</script>";
}
$la_datemp=$_SESSION["la_empresa"];
$ls_codtie=$_SESSION["ls_codtienda"];
$ls_coduniadm=$_SESSION["ls_coduniad"];
$ls_item=$_SESSION["ls_item"];
$ls_codcaj=$_SESSION["ls_codcaj"];
if($ls_codcaj=="" || $ls_codcaj=="T")
{
	print "<script language=JavaScript>";
	print "alert('Debe seleccionar una caja para poder facturar.');";
	print "location.href='../index_modules.php';";
	print "</script>";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Facturar</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
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
.Estilo2 {color: #006699}
.Estilo3 {
	color: #6699CC;
	font-size: 14px;
}
-->
</style><meta http-equiv="Content-Type" content="text/html; charset="></head>
<body link="#006699" vlink="#006699" alink="#006699" onLoad="ue_subtotal();" onUnload="ue_actualizarestacot();" >
<?Php
/****************************************************************/
/* FECHA: 25/03/2006                      						*/
/* AUTOR: GERARDO CORDERO 										*/
/*ACTUALIZADO POR: ING. ZULHEYMAR RODRIGUEZ    FECHA:06/03/2008 */
/****************************************************************/
//********************************************         SEGURIDAD       ****************************************************
require_once("../shared/class_folder/sigesp_c_seguridad.php");
$io_seguridad= new sigesp_c_seguridad();
$arre=$_SESSION["la_empresa"];//
$ls_formalibre=$_SESSION["ls_formalibre"];
$ls_empresa=$arre["codemp"];
$ls_logusr=$_SESSION["la_logusr"];
$ls_sistema="SFC";
$ls_ventanas="sigesp_sfc_d_factura.php";
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
require_once("../shared/class_folder/grid_param.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/evaluate_formula.php");
require_once("../shared/class_folder/evaluate_formula.php");
require_once("class_folder/sigesp_sob_c_funciones_sob.php");
require_once("class_folder/sigesp_sfc_c_cotizacion.php");
require_once("class_folder/sigesp_sfc_c_factura.php");
require_once("class_folder/sigesp_sfc_c_cobranza.php");
require_once("class_folder/sigesp_sfc_c_nota.php");
require_once("class_folder/sigesp_sfc_c_secuencia.php");
$io_evalform=new evaluate_formula();
$io_secuencia=new sigesp_sfc_c_secuencia();
$io_funcsob=new sigesp_sob_c_funciones_sob();
$io_evalform=new evaluate_formula();
$io_grid=new grid_param();
$io_msg=new class_mensajes();
$io_function=new class_funciones();
$io_cotizacion=new  sigesp_sfc_c_cotizacion();
$io_factura=new  sigesp_sfc_c_factura();
$ls_estcajero=$io_factura->uf_validar_cajero($_SESSION["la_logusr"]);
$io_seccon=new sigesp_sfc_c_secuencia();
$ls_codemp=$la_datemp["codemp"];
/**************   GRID   DETALLES   FACTURA   *******************/
$ls_tituloconcepto="Detalle Productos";
$li_anchoconcepto=600;
$ls_nametable="grid2";
$la_columconcepto[1]="C&oacute;digo";
$la_columconcepto[2]="Descripci&oacute;n";
$la_columconcepto[3]="C&oacute;d Almac&eacute;n";
$la_columconcepto[4]="Almac&eacute;n";
$la_columconcepto[5]="Proveedor";
$la_columconcepto[6]="Precio Unitario";
$la_columconcepto[7]="IVA";
$la_columconcepto[8]="Cantidad";
$la_columconcepto[9]="Total";
$la_columconcepto[10]="Edici&oacute;n";
/*******  GRID    DETALLES     FORMA    DE    PAGO  ********************/
$ls_titulofpago="Instrumento de pago";
$li_anchofpago=600;
$ls_nametablefpago="grid3";
$la_columfpago[1]="C&oacute;digo";
$la_columfpago[2]="Descripci&oacute;n";
$la_columfpago[3]="N&uacute;mero";
$la_columfpago[4]="Banco";
$la_columfpago[5]="Cuenta Banco";
$la_columfpago[6]="Monto";
$la_columfpago[7]="Edici&oacute;n";
print "<script language=JavaScript>suiche_submit=false;</script>";
//**********************SUBMIT*******************************************
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	//Datos cabecera factura------------------
	$ls_cedcli=$_POST["txtcedcli"];
	$ls_codcli=$_POST["txtcodcli"];
	$ls_nomcli=$_POST["txtnomcli"];
	$ls_numfac=$_POST["txtnumfac"];
        $ls_codtie=$_POST["txtcodtienda"];
        $_SESSION["ls_codtienda"] = $ls_codtie;
        //echo "$ls_operacion ".$_SESSION["ls_codtienda"];
        

	$ls_mostrardias=$_POST["txtmostdias"];
	$ls_mostrardias2=$_POST["txtmostdias2"];
	if ($ls_formalibre=='S')
	{
		$ls_numcon=$_POST["txtnumcon"];
	}
	$ls_numcot=$_POST["txtnumcot"];
	$ls_numor=$_POST["txtnumor"];
	$ls_fecemi=$_POST["txtfecemi"];
	$ls_conpag=$_POST["combo_conpag"];
	$ls_codusu=$_SESSION["la_logusr"];
	$ls_hidstatus=$_POST["hidstatus"];
	$ls_nuevo=$_POST["nuevo"];
	$ls_correlativo=$_POST["txtcorrelativo"];
	$ls_correlativocon=$_POST["txtcorrelativocon"];
	$ls_numcontrol=$_POST["txtnumcontrol"];
	$ls_estcot=$_POST["txtestcot"];
	$ls_estfaccon=$_POST["txtestfaccon"];

	//Datos Montos-----------------------------
    //---------agregado 17/08/10---------------------------------------------CristÃ³bal---------------------------
	$ls_subtot1=$_POST["txtsubtot1"];
	$ls_descubonirebajas=$_POST["txtdescubonirebajas"];
	$ls_fletes=$_POST["txtfletes"];	
	$ls_dias=$_POST["txtdias"];	
	$ls_subtot2=$_POST["txtsubtot2"];
	$ls_monexento=$_POST["txtmonexento"];
	$ls_monexonerado=$_POST["txtmonexonerado"];
	$ls_monbaseimponible=$_POST["txtmonbaseimponible"];
	$ls_moniva1=$_POST["txtmoniva1"];
	$ls_moniva2=$_POST["txtmoniva2"];
	//----------------------------------------------------------------------------------------------------------
	
	$ls_subtot=$_POST["txtsubtot"];
	$ls_moniva=$_POST["txtmoniva"];
	$ls_monivas=$_POST["txtmonivas"];
	$ls_monto=$_POST["txtmonto"];
	$ls_observaciones=$_POST["txtobsfac"];
	$ls_moncar=$_POST["txtmoncar"];
	$ls_porcar=$_POST["txtporcar"];
	$ls_montoforpag=$_POST["txtmontoforpag"];
	$ls_totalforpag=$_POST["txttotalforpag"];
	//filas grid concepto-----------------------------
	$li_filasconcepto=$_POST["filasconcepto"];
    $li_removerconcepto=$_POST["hidremoverconcepto"];
	//filas formas de pago----------------------------
	$li_filasfpago=$_POST["txtfilasfpago"];
	$li_removerfpago=$_POST["hidremoverfpago"];
	$ls_estcot_ant=$_POST["txtestcot_ant"];	
	$ls_numcot_ant=$_POST["txtnumcot_ant"];
	$ls_numor_ant=$_POST["txtnumor_ant"];
	$ls_resta=$_POST["txtresta"];
	$ls_hidbanexi=$_POST["hidbanexi"];
    if(array_key_exists("check1",$_POST)) //checkbox pago especial
	 {
	 	 $ls_especial="V";
	 }
	else
	 {
		  $ls_especial="F";
	 }
	$ls_consulta=$_POST["txtconsulta"];
}
else
{
	$ls_operacion="";
	$ls_numfac="";
	if ($ls_formalibre=='S')
	{
		$ls_numcon="";
	}
	$ls_fecemi=date('d/m/Y');
	$ls_numcot="0000000000000000000000000";
	$ls_numor="";
	$ls_cedcli="";
	$ls_codcli="";
	$ls_nomcli="";
	$ls_subtot="0,00";
	$ls_subtot2="0,00";
	$ls_moniva="0,00";
	$ls_monivas="0,00";
	$ls_moniva2="0,00";
	$ls_monto="0,00";
	$ls_conpag=1;
	$ls_estcot="";
	$ls_estfaccon="";
	$ls_totalforpag="0,00";
	$ls_especial="F";
	$ls_estcot_ant="";
	$ls_numcot_ant="";
	$ls_resta="0,00";
	$ls_hidbanexi="";
	$ls_consulta="";
	$ls_hidstatus="";
	$ls_nuevo='';
	$ls_correlativo='';
	//**********************************Grid Concepto*******************************************
	$li_filasconcepto=1;
    $la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=21 style= text-align:center readonly><input name=txtexiste".$li_filasconcepto." type=hidden id=txtexiste".$li_filasconcepto.">";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][3]="<input name=txtcodalm".$li_filasconcepto." type=text id=txtcodalm".$li_filasconcepto." class=sin-borde size=11 maxlength=10 style= text-align:left>";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txtdesalm".$li_filasconcepto." type=text id=txtdesalm".$li_filasconcepto." class=sin-borde size=20 maxlength=255 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txtnompro".$li_filasconcepto." type=text id=txtnompro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly><input name=txtcod_pro".$li_filasconcepto." type=hidden id=txtcod_pro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly>";
	$la_objectconcepto[$li_filasconcepto][6]="<input name=txtprepro".$li_filasconcepto." type=text id=txtprepro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly><input name=txtcosto".$li_filasconcepto." type=hidden id=txtcosto".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][7]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][8]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right>";
	$la_objectconcepto[$li_filasconcepto][9]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly>";
	$la_objectconcepto[$li_filasconcepto][10]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style=text-align:center size=5 readonly>";

	//*****************************Grid Forma de Pago*****************************************
	$li_filasfpago=1;
	$la_objectfpago[$li_filasfpago][1]="<input name=txtcodforpag".$li_filasfpago." type=text id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_filasfpago." type=hidden id=txtcodent".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][2]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=20 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][3]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=20 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][4]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=35 style= text-align:left readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][5]="<input name=txtctabanco".$li_filasfpago." type=text id=txtctabanco".$li_filasfpago." class=sin-borde size=25 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][6]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
$la_objectfpago[$li_filasfpago][7]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
}
if ($ls_operacion!="ue_cargarconcepto" && $ls_operacion!="ue_removerconcepto")
{

		for($li_i=1;$li_i<$li_filasconcepto;$li_i++)
		{
		$ls_codpro=$_POST["txtcodpro".$li_i];
		$ls_denpro=$_POST["txtdenpro".$li_i];
		$ls_prepro=$_POST["txtprepro".$li_i];
		$ls_canpro=$_POST["txtcanpro".$li_i];
		$ls_totpro=$_POST["txttotpro".$li_i];
		$ls_moncar=$_POST["txtmoncar".$li_i];
		$ls_porcar=$_POST["txtporcar".$li_i];
		$ls_codalm=$_POST["txtcodalm".$li_i];
		$ls_desalm=$_POST["txtdesalm".$li_i]; 
		$ls_existe=$_POST["txtexiste".$li_i];
		$ls_cod_pro=$_POST["txtcod_pro".$li_i];
		$ls_nompro=$_POST["txtnompro".$li_i];
		$ls_costo=$_POST["txtcosto".$li_i];
		//print $ls_costo;

		$la_objectconcepto[$li_i][1]="<input name=txtcodpro".$li_i." type=text id=txtcodpro".$li_i." value='".$ls_codpro."' class=sin-borde size=21 style= text-align:center readonly><input name=txtexiste".$li_i." type=hidden id=txtexiste".$li_i." value='".$ls_existe."'>";
		$la_objectconcepto[$li_i][2]="<input name=txtdenpro".$li_i." type=text id=txtdenpro".$li_i." value='".$ls_denpro."' class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectconcepto[$li_i][3]="<input name=txtcodalm".$li_i." type=text id=txtcodalm".$li_i." value='".$ls_codalm."' class=sin-borde size=11 maxlength=10 style= text-align:left>";
		$la_objectconcepto[$li_i][4]="<input name=txtdesalm".$li_i." type=text id=txtdesalm".$li_i." value='".$ls_desalm."' class=sin-borde size=20 maxlength=255 style= text-align:center readonly>";
		$la_objectconcepto[$li_i][5]="<input name=txtnompro".$li_i." type=text id=txtnompro".$li_i." value='".$ls_nompro."' class=sin-borde size=10 style= text-align:right readonly><input name=txtcod_pro".$li_i." type=hidden id=txtcod_pro".$li_i." value='".$ls_cod_pro."' class=sin-borde size=10 style= text-align:right readonly>";
		$la_objectconcepto[$li_i][6]="<input name=txtprepro".$li_i." type=text id=txtprepro".$li_i." value='".$ls_prepro."' class=sin-borde size=10 style= text-align:right onKeyPress=return(currencyFormat(this,'.',',',event))><input name=txtcosto".$li_i." type=hidden id=txtcosto".$li_i." class=sin-borde value='".$ls_costo."' style= text-align:center readonly>";
		$la_objectconcepto[$li_i][7]="<input name=txtporcar".$li_i." type=text id=txtporcar".$li_i." value='".$ls_porcar."' class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_i." type=hidden id=txtmoncar".$li_i." value='".$ls_moncar."' class=sin-borde style= text-align:center readonly>";
		if ($ls_estfaccon=="" && $ls_operacion!="ue_guardar")//Si la factura es nueva se habilita la opcion de eliminar en edicion
		 {
			$la_objectconcepto[$li_i][8]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=10 style= text-align:right onKeyPress=return(currencyFormat(this,'.',',',event))>";
		 }
		else
		 {
		 	$la_objectconcepto[$li_i][8]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=10 style= text-align:right readonly>";
		 }

		$la_objectconcepto[$li_i][9]="<input name=txttotpro".$li_i." type=text id=txttotpro".$li_i." value='".$ls_totpro."' class=sin-borde size=12 style= text-align:right readonly>";

		if ($ls_estfaccon=="" && $ls_operacion!="ue_guardar")//Si la factura es nueva se habilita la opcion de eliminar en edicion
		 {
		$la_objectconcepto[$li_i][10]="<a href=javascript:ue_removerconcepto(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		  }
		else
		  {
		$la_objectconcepto[$li_i][10]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center readonly>";
		  }
		}

	$la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=21 style= text-align:center readonly><input name=txtexiste".$li_filasconcepto." type=hidden id=txtexiste".$li_filasconcepto.">";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][3]="<input name=txtcodalm".$li_filasconcepto." type=text id=txtcodalm".$li_filasconcepto." class=sin-borde size=11 maxlength=10 style= text-align:left>";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txtdesalm".$li_filasconcepto." type=text id=txtdesalm".$li_filasconcepto." class=sin-borde size=20 maxlength=255 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txtnompro".$li_filasconcepto." type=text id=txtnompro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly><input name=txtcod_pro".$li_filasconcepto." type=hidden id=txtcod_pro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly>";
	$la_objectconcepto[$li_filasconcepto][6]="<input name=txtprepro".$li_filasconcepto." type=text id=txtprepro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right onKeyPress=return(currencyFormat(this,'.',',',event))><input name=txtcosto".$li_filasconcepto." type=hidden id=txtcosto".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][7]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][8]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right>";
	$la_objectconcepto[$li_filasconcepto][9]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly>";
	$la_objectconcepto[$li_filasconcepto][10]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style=text-align:center size=5 readonly>";
}

if ($ls_operacion!="ue_cargarfpago" && $ls_operacion!="ue_removerfpago")
{
	for($li_i=1;$li_i<$li_filasfpago;$li_i++)
	{
		 $ls_codforpag=$_POST["txtcodforpag".$li_i];
		 $ls_denforpag=$_POST["txtdenforpag".$li_i];
		 $ls_numinst=$_POST["txtnuminst".$li_i];
		 $ls_nombanco=$_POST["txtnombanco".$li_i];
		 $ls_ctabanco=$_POST["txtctabanco".$li_i];
		 $ls_codban=$_POST["txtcodban".$li_i];
		 $ls_montoforpag=$_POST["txtmontoforpag".$li_i];
		 $ls_metforpago=$_POST["txtmetforpag".$li_i];
		 $ls_codent=$_POST["txtcodent".$li_i];

		 $la_objectfpago[$li_i][1]="<input name=txtcodforpag".$li_i." type=text id=txtcodforpag".$li_i." value='".$ls_codforpag."' class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_i." type=hidden id=txtmetforpag".$li_i." value='".$ls_metforpago."' class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_i." type=hidden id=txtcodent".$li_i." value='".$ls_codent."' class=sin-borde size=15 style= text-align:center readonly>";
		 $la_objectfpago[$li_i][2]="<input name=txtdenforpag".$li_i." type=text id=txtdenforpag".$li_i." value='".$ls_denforpag."' class=sin-borde size=20 style= text-align:left readonly>";
		 $la_objectfpago[$li_i][3]="<input name=txtnuminst".$li_i." type=text id=txtnuminst".$li_i." value='".$ls_numinst."' class=sin-borde size=20 style= text-align:center readonly>";
		 $la_objectfpago[$li_i][4]="<input name=txtnombanco".$li_i." type=text id=txtnombanco".$li_i." value='".$ls_nombanco."' class=sin-borde size=35 style= text-align:center readonly><input name=txtcodban".$li_i." type=hidden id=txtcodban".$li_i." value='".$ls_codban."' class=sin-borde size=15 style= text-align:center readonly>";
		 $la_objectfpago[$li_i][5]="<input name=txtctabanco".$li_i." type=text id=txtctabanco".$li_i." value='".$ls_ctabanco."' class=sin-borde size=25 style= text-align:left readonly>";
		 $la_objectfpago[$li_i][6]="<input name=txtmontoforpag".$li_i." type=text id=txtmontoforpag".$li_i." value='".$ls_montoforpag."' class=sin-borde size=15 style= text-align:center readonly>";
	//**********Si la factura es nueva se habilita la opcion de eliminar en edicion**********
		if ($ls_estfaccon=="" && $ls_operacion!="")
		{
			$la_objectfpago[$li_i][7]="<a href=javascript:ue_removerfpago(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";		}
		else
		{
			$la_objectfpago[$li_i][7]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center readonly>";
	    }
	}//Fin del for $li_filasfpago

	$la_objectfpago[$li_filasfpago][1]="<input name=txtcodforpag".$li_filasfpago." type=text id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_filasfpago." type=hidden id=txtcodent".$li_filasfpago."class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][2]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=20 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][3]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=20 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][4]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=35 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][5]="<input name=txtctabanco".$li_filasfpago." type=text id=txtctabanco".$li_filasfpago." class=sin-borde size=25 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][6]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][7]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
}//fin del if($ls_operacion!="ue_cargarfpago" && $ls_operacion!="ue_removerfpago")

/******************** PREPARANDO INSERCION DE "NUEVA FACTURA ****************************/
if($ls_operacion=="ue_nuevo")
{
	if ($ls_estcot_ant=='P')
	{
		$ls_estcot_ant='E';
		$lb_valido=$io_cotizacion->uf_update_cotizacionstatus($ls_numcot_ant,$ls_estcot_ant,$la_seguridad);
	}
	$li_filasfpago=1;
    $la_empresa=$_SESSION["la_empresa"];
	$ls_codcaj=$_SESSION["ls_codcaj"];
	$ls_prefijo=$_SESSION["ls_prefac"];
	$ls_serie=$_SESSION["ls_serfac"];
	$io_secuencia->uf_ver_secuencia($ls_codcaj.$ls_codtie."fac",&$ls_secuencia);
	$ls_secuencia=$io_function->uf_cerosizquierda($ls_secuencia,16);
	$ls_numfac=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);
	if ($ls_formalibre=='S')
	{
		$ls_sercon=$_SESSION["sercon"];
		$io_seccon->uf_ver_secuencia($ls_codcaj.$ls_codtie."con",&$ls_seccon);
		$ls_seccon=$io_function->uf_cerosizquierda($ls_seccon,16);
		$ls_numcon=$io_secuencia->uf_crear_codigo("",$ls_sercon,$ls_seccon);
	}
	$ls_fecemi=date('d/m/Y');
	$ls_numcot="0000000000000000000000000";
	$ls_numor="";
	$ls_codcli="";
	$ls_nomcli="";
	$ls_subtot="0,00";
	$ls_subtot2="0,00";
	$ls_moniva="0,00";
	$ls_monivas="0,00";
	$ls_moniva2="0,00";
	$ls_monto="0,00";
	$ls_conpag=1;
	$ls_especial="F";
	$ls_estcot="";
	$ls_estfaccon="";
	$ls_nuevo='N';


	//*******************************Grid Conceptos******************************
	$li_filasconcepto=1;
    $la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=21 style= text-align:center readonly><input name=txtexiste".$li_filasconcepto." type=hidden id=txtexiste".$li_filasconcepto.">";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][3]="<input name=txtcodalm".$li_filasconcepto." type=text id=txtcodalm".$li_filasconcepto." class=sin-borde size=11 maxlength=10 style= text-align:left>";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txtdesalm".$li_filasconcepto." type=text id=txtdesalm".$li_filasconcepto." class=sin-borde size=20 maxlength=255 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txtnompro".$li_filasconcepto." type=text id=txtnompro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly><input name=txtcod_pro".$li_filasconcepto." type=hidden id=txtcod_pro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly>";
	$la_objectconcepto[$li_filasconcepto][6]="<input name=txtprepro".$li_filasconcepto." type=text id=txtprepro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right onKeyPress=return(currencyFormat(this,'.',',',event))><input name=txtcosto".$li_filasconcepto." type=hidden id=txtcosto".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][7]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][8]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right>";
	$la_objectconcepto[$li_filasconcepto][9]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly>";
	$la_objectconcepto[$li_filasconcepto][10]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style=text-align:center size=5 readonly>";

	//*******************************Grid Formas de Pago******************************
	$li_filasfpago=1;
	$la_objectfpago[$li_filasfpago][1]="<input name=txtcodforpag".$li_filasfpago." type=text id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_filasfpago." type=hidden id=txtcodent".$li_filasfpago."class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][2]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=20 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][3]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=20 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][4]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=35 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][5]="<input name=txtctabanco".$li_filasfpago." type=text id=txtctabanco".$li_filasfpago." class=sin-borde size=25 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][6]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][7]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
	$io_secuencia->uf_ver_secuencia($ls_codcaj.$ls_codtie."fac",&$ls_secuencia2);
	if ($ls_secuencia2=='')
	{
	print("<script language=JavaScript>");
	print("alert('DEBE ACTUALIZAR LA CAJA, CORRELATIVO NO VALIDO');");
	print("pagina='sigesp_sfc_d_factura.php';");
    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
    print("</script>");
	}
}//fin if($ls_operacion=="ue_nuevo")

/********************** CARGAR UN PRODUCTO EN GRID *************************/
elseif($ls_operacion=="ue_cargarconcepto")
{
	$li_filasconcepto=$_POST["filasconcepto"];
	$li_filasconcepto++;
	for($li_i=1;$li_i<$li_filasconcepto;$li_i++)
	{
		if ($li_i<=$ls_item)
		{
			$ls_codpro=$_POST["txtcodpro".$li_i];
			$ls_denpro=$_POST["txtdenpro".$li_i];
			$ls_prepro=$_POST["txtprepro".$li_i];
			$ls_canpro=$_POST["txtcanpro".$li_i];
			$ls_totpro=$_POST["txttotpro".$li_i];
			$ls_porcar=$_POST["txtporcar".$li_i];
			$ls_moncar=$_POST["txtmoncar".$li_i];
			$ls_codalm=$_POST["txtcodalm".$li_i];
			$ls_desalm=$_POST["txtdesalm".$li_i];
			$ls_existe=$_POST["txtexiste".$li_i];
			$ls_cod_pro=$_POST["txtcod_pro".$li_i];
			$ls_nompro=$_POST["txtnompro".$li_i];
			$ls_costo=$_POST["txtcosto".$li_i];
			//print $ls_costo;

			$la_objectconcepto[$li_i][1]="<input name=txtcodpro".$li_i." type=text id=txtcodpro".$li_i." value='".$ls_codpro."' class=sin-borde size=21 style= text-align:center readonly><input name=txtexiste".$li_i." type=hidden id=txtexiste".$li_i." value='".$ls_existe."'>";
			$la_objectconcepto[$li_i][2]="<input name=txtdenpro".$li_i." type=text id=txtdenpro".$li_i." value='".$ls_denpro."' class=sin-borde size=45 style= text-align:left readonly>";
			$la_objectconcepto[$li_i][3]="<input name=txtcodalm".$li_i." type=text id=txtcodalm".$li_i." value='".$ls_codalm."' class=sin-borde size=11 maxlength=10 style= text-align:left>";
			$la_objectconcepto[$li_i][4]="<input name=txtdesalm".$li_i." type=text id=txtdesalm".$li_i." value='".$ls_desalm."' class=sin-borde size=20 maxlength=255 style= text-align:center readonly>";
			$la_objectconcepto[$li_i][5]="<input name=txtnompro".$li_i." type=text id=txtnompro".$li_i." value='".$ls_nompro."' class=sin-borde size=10 style= text-align:right readonly><input name=txtcod_pro".$li_i." type=hidden id=txtcod_pro".$li_i." value='".$ls_cod_pro."' class=sin-borde size=10 style= text-align:right readonly>";
			$la_objectconcepto[$li_i][6]="<input name=txtprepro".$li_i." type=text id=txtprepro".$li_i." value='".$ls_prepro."' class=sin-borde size=10 style= text-align:right onKeyPress=return(currencyFormat(this,'.',',',event))><input name=txtcosto".$li_i." type=hidden id=txtcosto".$li_i." class=sin-borde value='".$ls_costo."' style= text-align:center readonly>";
			$la_objectconcepto[$li_i][7]="<input name=txtporcar".$li_i." type=text id=txtporcar".$li_i." value='".$ls_porcar."' class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_i." type=hidden id=txtmoncar".$li_i." value='".$ls_moncar."' class=sin-borde style= text-align:center readonly>";

			//Si la factura es nueva se habilita la opcion de eliminar en edicion

				$la_objectconcepto[$li_i][8]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=10 style= text-align:right onKeyPress=return(currencyFormat(this,'.',',',event))>";

			$la_objectconcepto[$li_i][9]="<input name=txttotpro".$li_i." type=text id=txttotpro".$li_i." value='".$ls_totpro."' class=sin-borde size=12 style= text-align:right readonly>";

			// Si la factura es nueva se habilita la opcion de eliminar en edicion
			if ($ls_estfaccon=="")
			{
				$la_objectconcepto[$li_i][10]="<a href=javascript:ue_removerconcepto(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
			}
			else
			{
				$la_objectconcepto[$li_i][10]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center readonly>";
			}
		}
	else
	{
		$io_msg->message("Ha excedido el limite de carga de productos");
		$li_filasconcepto=$li_filasconcepto-1;


	}

	}//fin del for
	//$li_filasconcepto=$li_filasconcepto-1;
	$la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=21 style= text-align:center readonly><input name=txtexiste".$li_filasconcepto." type=hidden id=txtexiste".$li_filasconcepto.">";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][3]="<input name=txtcodalm".$li_filasconcepto." type=text id=txtcodalm".$li_filasconcepto." class=sin-borde size=11 maxlength=10 style= text-align:left>";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txtdesalm".$li_filasconcepto." type=text id=txtdesalm".$li_filasconcepto." class=sin-borde size=20 maxlength=255 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txtnompro".$li_filasconcepto." type=text id=txtnompro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly><input name=txtcod_pro".$li_filasconcepto." type=hidden id=txtcod_pro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly>";
	$la_objectconcepto[$li_filasconcepto][6]="<input name=txtprepro".$li_filasconcepto." type=text id=txtprepro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly><input name=txtcosto".$li_filasconcepto." type=hidden id=txtcosto".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][7]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][8]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right>";
	$la_objectconcepto[$li_filasconcepto][9]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly>";
	$la_objectconcepto[$li_filasconcepto][10]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style=text-align:center size=5 readonly>";

}//fin del if ($ls_operacion=="ue_cargarconcepto")

/************************* CARGAR FORMA DE PAGO EN GRID *********************************/
elseif($ls_operacion=="ue_cargarfpago")
{
	$li_filasfpago=$_POST["txtfilasfpago"];
	$li_filasfpago++;
	for($li_i=1;$li_i<$li_filasfpago;$li_i++)
	{
	     $ls_codforpag=$_POST["txtcodforpag".$li_i];
		 $ls_denforpag=$_POST["txtdenforpag".$li_i];
		 $ls_numinst=$_POST["txtnuminst".$li_i];
		 $ls_nombanco=$_POST["txtnombanco".$li_i];
		 $ls_ctabanco=$_POST["txtctabanco".$li_i];

		 $ls_codban=$_POST["txtcodban".$li_i];
		 $ls_metforpago=$_POST["txtmetforpag".$li_i];
		 $ls_montoforpag=$_POST["txtmontoforpag".$li_i];
		 $ls_codent=$_POST["txtcodent".$li_i];

		 $la_objectfpago[$li_i][1]="<input name=txtcodforpag".$li_i." type=text id=txtcodforpag".$li_i." value='".$ls_codforpag."' class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_i." type=hidden id=txtmetforpag".$li_i." value='".$ls_metforpago."' class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_i." type=hidden id=txtcodent".$li_i." value='".$ls_codent."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectfpago[$li_i][2]="<input name=txtdenforpag".$li_i." type=text id=txtdenforpag".$li_i." value='".$ls_denforpag."' class=sin-borde size=20 style= text-align:left readonly>";
		$la_objectfpago[$li_i][3]="<input name=txtnuminst".$li_i." type=text id=txtnuminst".$li_i." value='".$ls_numinst."' class=sin-borde size=20 style= text-align:center readonly>";
		$la_objectfpago[$li_i][4]="<input name=txtnombanco".$li_i." type=text id=txtnombanco".$li_i." value='".$ls_nombanco."' class=sin-borde size=35 style= text-align:center readonly><input name=txtcodban".$li_i." type=hidden id=txtcodban".$li_i." value='".$ls_codban."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectfpago[$li_i][5]="<input name=txtctabanco".$li_i." type=text id=txtctabanco".$li_i." value='".$ls_ctabanco."' class=sin-borde size=25 style= text-align:left readonly>";
		$la_objectfpago[$li_i][6]="<input name=txtmontoforpag".$li_i." type=text  id=txtmontoforpag".$li_i." value='".$ls_montoforpag."' class=sin-borde size=15 style= text-align:center readonly>";

		// Si la factura es nueva se habilita la opcion de eliminar en edicion
		if ($ls_estfaccon=="")
		{
			$la_objectfpago[$li_i][7]="<a href=javascript:ue_removerfpago(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}
		else
		{
			$la_objectfpago[$li_i][7]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center readonly>";
		}
	}//fin for

    $la_objectfpago[$li_filasfpago][1]="<input name=txtcodforpag".$li_filasfpago." type=text id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_filasfpago." type=hidden id=txtcodent".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][2]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=20 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][3]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][4]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=35 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][5]="<input name=txtctabanco".$li_filasfpago." type=text id=txtctabanco".$li_filasfpago." class=sin-borde size=25 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][6]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][7]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";

}//fin del if ($ls_operacion=="ue_cargarfpago")

/************ CARGAR CONCEPTOS DESDE CATALOGO DE COTIZACION *************/
elseif ($ls_operacion=="ue_cargarconcepto_cotizacion")
{
	if ($ls_estcot_ant=='P')
	{
	    $ls_estcot_ant='E';
   	    $lb_valido=$io_cotizacion->uf_update_cotizacionstatus($ls_numcot_ant,$ls_estcot_ant,$la_seguridad);
	}
	/****************************  LIMPIAR GRID CONCEPTO*********************************/
	$li_filasconcepto=1;
     $la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=21 style= text-align:center readonly><input name=txtexiste".$li_filasconcepto." type=hidden id=txtexiste".$li_filasconcepto.">";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][3]="<input name=txtcodalm".$li_filasconcepto." type=text id=txtcodalm".$li_filasconcepto." class=sin-borde size=11 maxlength=10 style= text-align:left>";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txtdesalm".$li_filasconcepto." type=text id=txtdesalm".$li_filasconcepto." class=sin-borde size=20 maxlength=255 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txtnompro".$li_filasconcepto." type=text id=txtnompro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly><input name=txtcod_pro".$li_filasconcepto." type=hidden id=txtcod_pro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly>";
	$la_objectconcepto[$li_filasconcepto][6]="<input name=txtprepro".$li_filasconcepto." type=text id=txtprepro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly><input name=txtcosto".$li_filasconcepto." type=hidden id=txtcosto".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][7]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][8]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right>";
	$la_objectconcepto[$li_filasconcepto][9]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly>";
	$la_objectconcepto[$li_filasconcepto][10]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style=text-align:center size=5 readonly>";


	/****************************  LIMPIAR GRID FORMA DE PAGO*********************************/
	$li_filasfpago=1;
	$la_objectfpago[$li_filasfpago][1]="<input name=txtcodforpag".$li_filasfpago." type=text id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_filasfpago." type=hidden id=txtcodent".$li_filasfpago."class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][2]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=20 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][3]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=20 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][4]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=35 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][5]="<input name=txtctabanco".$li_filasfpago." type=text id=txtctabanco".$li_filasfpago." class=sin-borde size=25 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][6]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][7]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
	//Consulta de los productos registrados en el detalle de la Cotizaciï¿½n solicitada
	$la_arrdataconfac=$io_factura->uf_select_conceptosfac($ls_numcot);
	list($la_objectconcepto,$ls_subtot, $ls_moniva,$ls_monto,$li_filasconcepto) = $la_arrdataconfac;
	if(is_numeric($li_filasconcepto-1)>ls_item)
	{
		$io_msg->message("La cotizacion excede el numero de items de productos");

		$li_filasconcepto=ls_item;

	}
	elseif ($la_objectconcepto[$li_i][1]!="")
	{
		/**********************ACTUALIZAR COTIZACION A "EN PROCESO" *****************************/
	   if ($ls_numcot=='0000000000000000000000000')
	   {
	       $ls_estcot='E';
	   }
	   else
	   {
		   $ls_estcot='P';
	    }
 	   	$lb_valido=$io_cotizacion->uf_update_cotizacionstatus($ls_numcot,$ls_estcot,$la_seguridad);
	}
	else
	{
		$io_msg->message("No hay registros de productos");
	}
}

/************* CARGAR PRODUCTO EN GRID DESDE CATALOGO DE FACTURA***********************/
elseif ($ls_operacion=="ue_cargarconcepto_factura")
{
	//$_SESSION["ls_codtienda"] = "";
        /*********************   cargar forma de pago - instrumento de pago  *******************/
	/*********************************** LIMPIA EL GRID ************************************/
	$li_filasfpago=1;
	$ls_nuevo='G';
    $la_objectfpago[$li_filasfpago][1]="<input name=txtcodforpag".$li_filasfpago." type=text id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_filasfpago." type=hidden id=txtcodent".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][2]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=20 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][3]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=20 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][4]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=35 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][5]="<input name=txtctabanco".$li_filasfpago." type=text id=txtctabanco".$li_filasfpago." class=sin-borde size=25 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][6]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][7]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
	$la_arrdatainstfac=$io_factura->uf_select_instpagofac($ls_numfac);
	list($la_objectfpago,$li_filasfpago) = $la_arrdatainstfac;
	$la_arrdataest=$io_factura->uf_select_estfaccon($ls_codemp,$ls_numfac);
 	list($ls_estfaccon,$ls_estfac) = $la_arrdataest;
	if ($ls_estfaccon<>"N" && $ls_estfaccon<>"P")
    {
		if($li_filasfpago<=1)
		{
			$io_msg->message("No hay registros de formas de pago");
		}
	}
	/*********************   cargar productos - facturas  *******************/
	/*******   limpia el GRID    **************/
	$li_filasconcepto=1;
    $la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=21 style= text-align:center readonly><input name=txtexiste".$li_filasconcepto." type=hidden id=txtexiste".$li_filasconcepto.">";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][3]="<input name=txtcodalm".$li_filasconcepto." type=text id=txtcodalm".$li_filasconcepto." class=sin-borde size=11 maxlength=10 style= text-align:left>";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txtdesalm".$li_filasconcepto." type=text id=txtdesalm".$li_filasconcepto." class=sin-borde size=20 maxlength=255 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txtnompro".$li_filasconcepto." type=text id=txtnompro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly><input name=txtcod_pro".$li_filasconcepto." type=hidden id=txtcod_pro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly>";
	$la_objectconcepto[$li_filasconcepto][6]="<input name=txtprepro".$li_filasconcepto." type=text id=txtprepro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly><input name=txtcosto".$li_filasconcepto." type=hidden id=txtcosto".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][7]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][8]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right>";
	$la_objectconcepto[$li_filasconcepto][9]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly>";
	$la_objectconcepto[$li_filasconcepto][10]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style=text-align:center size=5 readonly>";

	$la_arrdataprofac=$io_factura->uf_select_profac($ls_numfac);
	list($la_objectconcepto,$ls_subtot,$ls_moniva,$li_filasconcepto) = $la_arrdataprofac;
	if($li_filasconcepto<=1)
	{
		$io_msg->message("No hay registros de productos");
	}
}
/**************  ELIMINAR PRODUCTO EN GRID **********************************************************/
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
			$ls_porcar=$_POST["txtporcar".$li_i];
			$ls_moncar=$_POST["txtmoncar".$li_i];
			$ls_prepro=$_POST["txtprepro".$li_i];
			$ls_canpro=$_POST["txtcanpro".$li_i];
			$ls_totpro=$_POST["txttotpro".$li_i];
			$ls_codalm=$_POST["txtcodalm".$li_i];
			$ls_desalm=$_POST["txtdesalm".$li_i];
			$ls_existe=$_POST["txtexiste".$li_i];
			$ls_cod_pro=$_POST["txtcod_pro".$li_i];
			$ls_nompro=$_POST["txtnompro".$li_i];
			$ls_costo=$_POST["txtcosto".$li_i];


		$la_objectconcepto[$li_temp][1]="<input name=txtcodpro".$li_temp." type=text id=txtcodpro".$li_temp." value='".$ls_codpro."' class=sin-borde size=21 style= text-align:center readonly><input name=txtexiste".$li_temp." type=hidden id=txtexiste".$li_temp." value='".$ls_existe."'>";
		$la_objectconcepto[$li_temp][2]="<input name=txtdenpro".$li_temp." type=text id=txtdenpro".$li_temp." value='".$ls_denpro."' class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectconcepto[$li_temp][3]="<input name=txtcodalm".$li_temp." type=text id=txtcodalm".$li_temp." value='".$ls_codalm."' class=sin-borde size=11 maxlength=10 style= text-align:left>";
		$la_objectconcepto[$li_temp][4]="<input name=txtdesalm".$li_temp." type=text id=txtdesalm".$li_temp." value='".$ls_desalm."' class=sin-borde size=20 maxlength=255 style= text-align:center readonly>";
		$la_objectconcepto[$li_temp][5]="<input name=txtnompro".$li_temp." type=text id=txtnompro".$li_temp." value='".$ls_nompro."' class=sin-borde size=10 style= text-align:right readonly><input name=txtcod_pro".$li_temp." type=hidden id=txtcod_pro".$li_temp." value='".$ls_cod_pro."' class=sin-borde size=10 style= text-align:right readonly>";
		$la_objectconcepto[$li_temp][6]="<input name=txtprepro".$li_temp." type=text id=txtprepro".$li_temp." value='".$ls_prepro."' class=sin-borde size=10 style= text-align:right onKeyPress=return(currencyFormat(this,'.',',',event))><input name=txtcosto".$li_temp." type=hidden id=txtcosto".$li_temp." class=sin-borde value='".$ls_costo."' style= text-align:center readonly>";
		$la_objectconcepto[$li_temp][7]="<input name=txtporcar".$li_temp." type=text id=txtporcar".$li_temp." value='".$ls_porcar."' class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_temp." type=hidden id=txtmoncar".$li_temp." value='".$ls_moncar."' class=sin-borde style= text-align:center readonly>";
			$la_objectconcepto[$li_temp][8]="<input name=txtcanpro".$li_temp." type=text id=txtcanpro".$li_temp." value='".$ls_canpro."' class=sin-borde size=10 style= text-align:right onKeyPress=return(currencyFormat(this,'.',',',event))>";
			$la_objectconcepto[$li_temp][9]="<input name=txttotpro".$li_temp." type=text id=txttotpro".$li_temp." value='".$ls_totpro."' class=sin-borde size=12 style= text-align:right readonly>";
			// Si la factura es nueva se habilita la opcion de eliminar en edicion
			if ($ls_estfaccon=="")
			{
				$la_objectconcepto[$li_temp][10]="<a href=javascript:ue_removerconcepto(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
			}
			else
			{
				$la_objectconcepto[$li_temp][10]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center readonly>";
			 }

		}
	}
    $la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=21 style= text-align:center readonly><input name=txtexiste".$li_filasconcepto." type=hidden id=txtexiste".$li_filasconcepto.">";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][3]="<input name=txtcodalm".$li_filasconcepto." type=text id=txtcodalm".$li_filasconcepto." class=sin-borde size=11 maxlength=10 style= text-align:left>";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txtdesalm".$li_filasconcepto." type=text id=txtdesalm".$li_filasconcepto." class=sin-borde size=20 maxlength=255 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txtnompro".$li_filasconcepto." type=text id=txtnompro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly><input name=txtcod_pro".$li_filasconcepto." type=hidden id=txtcod_pro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly>";
	$la_objectconcepto[$li_filasconcepto][6]="<input name=txtprepro".$li_filasconcepto." type=text id=txtprepro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly><input name=txtcosto".$li_filasconcepto." type=hidden id=txtcosto".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][7]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][8]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right>";
	$la_objectconcepto[$li_filasconcepto][9]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly>";
	$la_objectconcepto[$li_filasconcepto][10]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style=text-align:center size=5 readonly>";


}
/********************  ELIMINAR FORMA DE PAGO EN GRID *******************************************/
elseif($ls_operacion=="ue_removerfpago")
{
   	$li_filasfpago=$_POST["txtfilasfpago"];
	$li_filasfpago=$li_filasfpago - 1;
	$li_removerfpago=$_POST["hidremoverfpago"];
	$li_temp=0;
	for($li_i=1;$li_i<=$li_filasfpago;$li_i++)
	{
		if ($li_i!=$li_removerfpago)
		{
			 $li_temp=$li_temp+1;
			 $ls_codforpag=$_POST["txtcodforpag".$li_i];
			 $ls_denforpag=$_POST["txtdenforpag".$li_i];
			 $ls_numinst=$_POST["txtnuminst".$li_i];
			 $ls_nombanco=$_POST["txtnombanco".$li_i];
			 $ls_ctabanco=$_POST["txtctabanco".$li_i];
			 $ls_codban=$_POST["txtcodban".$li_i];
			 $ls_codent=$_POST["txtcodent".$li_i];
			 $ls_montoforpag=$_POST["txtmontoforpag".$li_i];
			 $ls_metforpago=$_POST["txtmetforpag".$li_i];

  		 	 $la_objectfpago[$li_temp][1]="<input name=txtcodforpag".$li_temp." type=text id=txtcodforpag".$li_temp." value='".$ls_codforpag."' class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_temp." type=hidden id=txtmetforpag".$li_temp." value='".$ls_metforpago."' class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_temp." type=hidden id=txtcodent".$li_temp." value='".$ls_codent."' class=sin-borde size=15 style= text-align:center readonly>";
		 	 $la_objectfpago[$li_temp][2]="<input name=txtdenforpag".$li_temp." type=text id=txtdenforpag".$li_temp." value='".$ls_denforpag."' class=sin-borde size=20 style= text-align:left readonly>";
		 	 $la_objectfpago[$li_temp][3]="<input name=txtnuminst".$li_temp." type=text id=txtnuminst".$li_temp." value='".$ls_numinst."' class=sin-borde size=20 style= text-align:center readonly>";
		 	 $la_objectfpago[$li_temp][4]="<input name=txtnombanco".$li_temp." type=text id=txtnombanco".$li_temp." value='".$ls_nombanco."' class=sin-borde size=35 style= text-align:center readonly><input name=txtcodban".$li_temp." type=hidden id=txtcodban".$li_temp." value='".$ls_codban."' class=sin-borde size=15 style= text-align:center readonly>";
		 	 $la_objectfpago[$li_temp][5]="<input name=txtctabanco".$li_temp." type=text id=txtctabanco".$li_temp." value='".$ls_ctabanco."' class=sin-borde size=25 style= text-align:left readonly>";
		 	 $la_objectfpago[$li_temp][6]="<input name=txtmontoforpag".$li_temp."  type=text id=txtmontoforpag".$li_temp." value='".$ls_montoforpag."' class=sin-borde size=15 style= text-align:center readonly>";
			 // Si la factura es nueva se habilita la opcion de eliminar en edicion
			 if ($ls_estfaccon=="")
		 	 {
				$la_objectfpago[$li_temp][7]="<a href=javascript:ue_removerfpago(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
          	 }
			 else
		  	 {
				$la_objectfpago[$li_temp][7]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center readonly>";
           	 }
		}
	}
	$la_objectfpago[$li_filasfpago][1]="<input name=txtcodforpag".$li_filasfpago." type=text id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_filasfpago." type=hidden id=txtcodent".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][2]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=20 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][3]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=20 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][4]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=35 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][5]="<input name=txtctabanco".$li_filasfpago." type=text id=txtctabanco".$li_filasfpago." class=sin-borde size=25 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][6]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
$la_objectfpago[$li_filasfpago][7]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
}
/******************** INSERCION O MODIFICACION DE UNA FACTURA EN BD ****************************************/
elseif($ls_operacion=="ue_editfactura")
{

	$lb_cajero_valido=$io_factura->uf_validar_cajero($ls_codusu);
	$validinstpago=true;
	if ($ls_conpag=='4')
	{
	$la_detapag["codforpag"][1]="";
	$la_detapag["denforpag"][1]="";
	$la_detapag["numinst"][1]="";
	$la_detapag["nombanco"][1]="";
	$la_detapag["ctabanco"][1]="";
	$la_detapag["codban"][1]="";
	$la_detapag["monto"][1]="";
	$la_detapag["codent"][1]="";
	$validinstpago=false;
	for ($li_i=1;$li_i<$li_filasfpago;$li_i++)
 	{
	   $la_detapag["codforpag"][$li_i]=$_POST["txtcodforpag".$li_i];
	   if ($la_detapag["codforpag"][$li_i]=='04')
	   {
	   	$validinstpago=true;
 	   }
	}
	}
	if ($validinstpago==true)
	{
	if($lb_cajero_valido)
	{
		 $ls_codusu=$_SESSION["la_logusr"];
		 $lb_valido_logusr=true;
	}
    else
	{
		 $lb_valido_logusr=false;
		 $io_msg->message ("El usuario no es personal de la tienda no puede generar facturas !!!");
	}

		$ls_codusu=$_SESSION["la_logusr"];
		$ls_codcaj=$_SESSION["ls_codcaj"];
	    $ls_prefijo=$_SESSION["ls_prefac"];
		$ls_serie=$_SESSION["ls_serfac"];
	    $io_secuencia->uf_ver_secuencia($ls_codcaj.$ls_codtie."fac",&$ls_secuencia);
		$ls_correlativoaux=$io_function->uf_cerosizquierda($ls_secuencia+1,16);
	    $ls_numfac=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_correlativoaux);
		if ($ls_formalibre=='S')
		{

			$ls_sercon=$_SESSION["sercon"];
			$io_secuencia->uf_ver_secuencia($ls_codcaj.$ls_codtie."con",&$ls_seccon);
			$ls_corrcon=$io_function->uf_cerosizquierda($ls_seccon+1,16);
			$ls_numcon=$io_secuencia->uf_crear_codigo("",$ls_sercon,$ls_corrcon);
		}
		//$io_msg->message ("La Factura sera guardada con el codigo: ".$ls_numfac);
		////////////////////////////////////////////////////////////////////////////////////////
		//                          Ediciï¿½n del Nro. de la Factura                            //
		////////////////////////////////////////////////////////////////////////////////////////
		?>
		<script>
		ls_nrofactura='<?php print $ls_numfac ?>';
		ls_nrocontrol='<?php print $ls_numcon ?>';
		ls_formalibre='<?php print $ls_formalibre ?>';
		if (ls_formalibre=='S')
		{
			//open(location.href="sigesp_cat_editfacturacontrol.php?ls_nrofactura="+ls_nrofactura+"&ls_nrocontrol="+ls_nrocontrol);
			pagina="sigesp_cat_editfacturacontrol.php?ls_nrofactura="+ls_nrofactura+"&ls_nrocontrol="+ls_nrocontrol;
		  
		}
		else
		{
			//open(location.href="sigesp_cat_editfactura.php?ls_nrofactura="+ls_nrofactura);
			pagina="sigesp_cat_editfactura.php?ls_nrofactura="+ls_nrofactura;           
		}
		popupWin(pagina,"catalogo",850,450);	
		</script>
		<?php
	}else
	{
		$io_msg->message ("Su condicion de pago es CARTA ORDEN, al menos un instrumento de pago debe ser carta orden, por favor verifique!!!");
	}
		}

elseif($ls_operacion=="ue_guardar")
{
	
	$ls_operacion='';
	$ls_nuevo='G';
	$la_arrdataest=$io_factura->uf_select_estfaccon($ls_codemp,$ls_numfac);
 	list($ls_estfaccon,$ls_estfac) = $la_arrdataest;
   /**************   VERIFICA SI EL USUARIO ES PERSONAL DE LA TIENDA  ***************************************/
        $ls_codtie=$_SESSION["ls_codtienda"];
	$ls_codusu=$_SESSION["la_logusr"];
	$ls_codcaj=$_SESSION["ls_codcaj"];
	$ls_correlativo=$_POST["txtcorrelativo"];
	$ls_corrcon=$_POST["txtcorrelativocon"];
	$ls_numcontrol=$_POST["txtnumcontrol"];
	$lb_cajero_valido=$io_factura->uf_validar_cajero($ls_codusu);
	if($lb_cajero_valido)
	{
		 $ls_codusu=$_SESSION["la_logusr"];
		 $lb_valido_logusr=true;
	}
    else
	{
		 $lb_valido_logusr=false;
		 $io_msg->message ("El usuario no es personal de la tienda no puede generar facturas !!!");
	}
    $la_detalles["codpro"][1]="";
    $la_detalles["prepro"][1]="";
    $la_detalles["canpro"][1]="";
    $la_detalles["porimp"][1]="";
	$la_detalles["codalm"][1]="";
	$la_detalles["desalm"][1]="";
	$la_detalles["cod_pro"][1]="";
	$la_detalles["nompro"][1]="";
	$la_detalles["costo"][1]="";
    $cantidad_pro=true;
    for ($li_i=1;$li_i<$li_filasconcepto;$li_i++)
    {
	   $la_detalles["codpro"][$li_i]=$_POST["txtcodpro".$li_i];
	   $la_detalles["prepro"][$li_i]=$_POST["txtprepro".$li_i];
	   $la_detalles["canpro"][$li_i]=$_POST["txtcanpro".$li_i];
	   $la_detalles["porimp"][$li_i]=$_POST["txtporcar".$li_i];
	   $la_detalles["codalm"][$li_i]=$_POST["txtcodalm".$li_i];
	   $la_detalles["desalm"][$li_i]=$_POST["txtdesalm".$li_i];
	   $la_detalles["cod_pro"][$li_i]=$_POST["txtcod_pro".$li_i];
	   $la_detalles["nompro"][$li_i]=$_POST["txtnompro".$li_i];
	   $la_detalles["costo"][$li_i]=$_POST["txtcosto".$li_i];
	   //print $la_detalles["costo"][$li_i];
	   if ($la_detalles["canpro"][$li_i]=="")
	    {
		  $io_msg->message ("Debe colocar la cantidad del producto a vender!!!");
		  $cantidad_pro=false;
		}
	 }
	  /*******   GUARDAR FORMA DE PAGO FACTURA (instrumento de pago)  ********************/
	    $la_detapag["codforpag"][1]="";
	    $la_detapag["denforpag"][1]="";
	    $la_detapag["numinst"][1]="";
	    $la_detapag["nombanco"][1]="";
	    $la_detapag["ctabanco"][1]="";
	    $la_detapag["codban"][1]="";
	    $la_detapag["monto"][1]="";
	    $la_detapag["codent"][1]="";

    	for ($li_i=1;$li_i<$li_filasfpago;$li_i++)
     	{
		   $la_detapag["codforpag"][$li_i]=$_POST["txtcodforpag".$li_i];
		   $la_detapag["denforpag"][$li_i]=$_POST["txtdenforpag".$li_i];
		   $la_detapag["numinst"][$li_i]=$_POST["txtnuminst".$li_i];
		   $la_detapag["nombanco"][$li_i]=$_POST["txtnombanco".$li_i];
		   $la_detapag["ctabanco"][$li_i]=$_POST["txtctabanco".$li_i];
		   $la_detapag["codban"][$li_i]=$_POST["txtcodban".$li_i];
		   $la_detapag["monto"][$li_i]=$_POST["txtmontoforpag".$li_i];
		   $la_detapag["codent"][$li_i]=$_POST["txtcodent".$li_i];
	 	}
	 /******************  GUARDAR EN TABLA "SFC_FACTURA"  *****************************************/
	 if ($lb_valido_logusr==true && $cantidad_pro==true)
	 {
   		$io_factura->io_sql->begin_transaction();		
		if ($ls_conpag=='2')
		{
		 // SI conpag=2(crï¿½dito) ï¿½ conpag=3(pago parcial) entonces ESTAFACCON=N: Factura no cancelada sin registro en cobranza.
		  	$ls_estfaccon='N';
		 }
		 elseif ($ls_conpag=='3')
		 {
		    $ls_estfaccon='P';
		 }
		 else
		 {
		  // SI conpag=1(contado) entonces ESTAFACCON=C (cancelada): Factura cancelada.
		   $ls_estfaccon='C';
		 }
		/*--------------------  ACTUALIZAR COTIZACION A "FACTURADA"---------------------------*/
		if ($ls_numcot=='0000000000000000000000000')
	    {
	        $ls_estcot='E';
		}
		else
		{
		    $ls_estcot='F';
		}
		if ($ls_correlativo<>'')
		{
			$io_secuencia->uf_actualizar_secuencia($ls_codcaj.$ls_codtie."fac",$ls_correlativo);
			if ($ls_formalibre=='S')
			{
			$io_secuencia->uf_actualizar_secuencia($ls_codcaj.$ls_codtie."con",$ls_correlativo);
			}
			$ls_even="UPDATE";
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_descripcion ="Actualizï¿½ EL CORRELATIVO de la Factura Nro.".$ls_correlativo." Asociado a la Empresa ".$ls_codemp." En el registro de la Factura";
					$lb_variable=$io_seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
													$la_seguridad["sistema"],$ls_even,$la_seguridad["logusr"],
													$la_seguridad["ventanas"],$ls_descripcion);
			////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		else
		{
		 $io_secuencia->uf_obtener_secuencia($ls_codcaj.$ls_codtie."fac",&$ls_correlativo);
		 $io_secuencia->uf_actualizar_secuencia($ls_codcaj.$ls_codtie."fac",$ls_correlativo);
		}
		if ($ls_corrcon<>'')
		{
			if ($ls_formalibre=='S')
				{
					$io_secuencia->uf_actualizar_secuencia($ls_codcaj.$ls_codtie."con",$ls_corrcon);
				}
				$ls_even="UPDATE";
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_descripcion ="Actualizï¿½ EL CORRELATIVO del Control Nro.".$ls_corrcon." Asociado a la Empresa ".$ls_codemp." En el registro de la Factura";
				$lb_variable=$io_seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
														$la_seguridad["sistema"],$ls_even,$la_seguridad["logusr"],
														$la_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		else
		{
		if ($ls_formalibre=='S')
		{
		 $io_secuencia->uf_obtener_secuencia($ls_codcaj.$ls_codtie."con",&$ls_corrcon);
		 $io_secuencia->uf_actualizar_secuencia($ls_codcaj.$ls_codtie."con",$ls_corrcon);
		 }

		}
		$ls_codalm=$la_detalles["codalm"][$li_i];
		if ($ls_codalm=='' or strlen($ls_codalm)<10)
			{
			   if ($ls_codalm=='')
			   {
				 $ls_codalm=$ls_secuencia=$io_function->uf_cerosizquierda($ls_codtie,10);
			   }
			   else
			   {
				$ls_codalm=$ls_secuencia=$io_function->uf_cerosizquierda($ls_codalm,10);
			   }
			}
		$ld_totalforpag=str_replace('.','',$ls_totalforpag);  //convierte a formato numï¿½rico
		$ld_totalforpag=str_replace(',','.',$ld_totalforpag);
		$ld_totalmonret=str_replace('.','',$ls_monto);  //convierte a formato numï¿½rico
		$ld_totalmonret=str_replace(',','.',$ld_totalmonret);
//***********************
                //aca validacion de iva de 8 o 12
                if ($ls_moniva == "0,00" && $ls_monivas != "0,00" ) {
                    $ls_monret = $ls_monivas;
                }else if ($ls_moniva != "0,00" && $ls_monivas == "0,00" ) {
                    $ls_monret = $ls_moniva;
                }else if ($ls_moniva != "0,00" && $ls_monivas != "0,00" ) {
                    $ls_monret = $ls_moniva + $ls_monivas;
                }else if ($ls_moniva == "0,00" && $ls_monivas == "0,00" ) {
                    $ls_monret = "0,00";
                }
                //fin de validacion
		$lb_valido_fac=$io_factura->uf_guardar_factura($ls_codcli,$ls_numfac,$ls_numcot,$ls_numor,$ls_codusu,$ls_fecemi,$ls_conpag,$ls_monto,$ls_estfaccon,$ls_monret,$ls_especial,$ls_resta,$ls_codtie,$ls_numcontrol,$ls_estcot,$la_detalles,$li_filasconcepto,$ls_codtie,$la_detapag,$li_filasfpago,$ls_coduniadm,$ld_totalforpag,$ld_totalmonret,$la_seguridad,$ls_monbaseimponible,$ls_monexento,$ls_observaciones,$ls_dias);
		$ld_monto=$ld_totalforpag-$ld_totalmonret;
		/*******FIN INTEGRACION CON INVENTARIO****************/
		if ($lb_valido_fac==true)
		{
			 $io_msg->message ("Registro Incluido");
			 $io_factura->io_sql->commit();
		 }
		 else
		 {
		  $io_msg->message ("Ocurrio un error en el proceso, intente de nuevo");
		  $io_factura->io_sql->rollback();
		 }
	$ls_fecemi=date('d/m/Y');
	$ls_numcot="0000000000000000000000000";
	$ls_numor="";
	$ls_codcli="";
	$ls_cedcli="";
	$ls_nomcli="";
	$ls_subtot="0,00";
	$ls_subtot2="0,00";
	$ls_moniva="0,00";
	$ls_monivas="0,00";
	$ls_moniva2="0,00";
	$ls_monto="0,00";
	$ls_conpag=1;
	$ls_especial="F";
	$ls_estcot="";
	$ls_estfaccon="";
	$ls_nuevo='N';
	$ls_operacion="";

	//*******************************Grid Conceptos******************************
	$li_filasconcepto=1;
    $la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=21 style= text-align:center readonly><input name=txtexiste".$li_filasconcepto." type=hidden id=txtexiste".$li_filasconcepto.">";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][3]="<input name=txtcodalm".$li_filasconcepto." type=text id=txtcodalm".$li_filasconcepto." class=sin-borde size=11 maxlength=10 style= text-align:left>";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txtdesalm".$li_filasconcepto." type=text id=txtdesalm".$li_filasconcepto." class=sin-borde size=20 maxlength=255 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txtnompro".$li_filasconcepto." type=text id=txtnompro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly><input name=txtcod_pro".$li_filasconcepto." type=hidden id=txtcod_pro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly>";
	$la_objectconcepto[$li_filasconcepto][6]="<input name=txtprepro".$li_filasconcepto." type=text id=txtprepro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly><input name=txtcosto".$li_filasconcepto." type=hidden id=txtcosto".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][7]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][8]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right>";
	$la_objectconcepto[$li_filasconcepto][9]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly>";
	$la_objectconcepto[$li_filasconcepto][10]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style=text-align:center size=5 readonly>";

	//*******************************Grid Formas de Pago******************************
	$li_filasfpago=1;
	$la_objectfpago[$li_filasfpago][1]="<input name=txtcodforpag".$li_filasfpago." type=text id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_filasfpago." type=hidden id=txtcodent".$li_filasfpago."class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][2]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=20 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][3]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=20 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][4]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=35 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][5]="<input name=txtctabanco".$li_filasfpago." type=text id=txtctabanco".$li_filasfpago." class=sin-borde size=25 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][6]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
$la_objectfpago[$li_filasfpago][7]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
$ls_operacion="";

	$lb_valido_repfac=$io_factura->uf_imprimir_factura($ls_numfac,&$ls_sql);
	if ($lb_valido_repfac==true)
	{
	?>
    	 <script language="JavaScript">
            var ls_sql="<?php print $ls_sql; ?>";
            var ls_numcontrol="<?php print $ls_numcontrol; ?>";
            ls_formalibre='<?php print $ls_formalibre ?>';
            moniva = '<?php echo $_POST['txtmoniva'];  ?>';
            monivas = '<?php echo $_POST['txtmonivas'];  ?>';
            baseiva1 = '<?php echo $_POST['txtmoniva1'];  ?>';
            baseiva2 = '<?php echo $_POST['txtmoniva2'];  ?>';
            total = '<?php echo $_POST['txtmonto'];  ?>';
            
            if (ls_formalibre=='S')
            {

                    cadena = '&moniva='+moniva+'&monivas='+monivas+'&baseiva1='+baseiva1+'&baseiva2='+baseiva2+'&total='+total;
                    //alert('Paso 1');
                    pagina="reportes/sigesp_sfc_rep_imprimirfacturalibre.php?sql="+ls_sql+"&ls_numcontrol="+ls_numcontrol+cadena;
            }
            else
            {
                    pagina="reportes/sigesp_sfc_rep_imprimirfactura.php?sql="+ls_sql;
                    
            }

                    popupWin(pagina,"catalogo",580,700);
    	 </script>
    <?php

		}
		else
		{
		$io_msg->message ("Ocurrio un error en el proceso, intente de nuevo");
			if($lb_valido1==0)
			{
				$ls_operacion="";
			}
		}

	} //$lb_valido_logusr
	print("<script language=JavaScript>");
	print("pagina='sigesp_sfc_d_factura.php';");
    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
    print("</script>");
$ls_operacion="";
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
 	$la_arrdatacli=$io_factura->uf_buscarcli($ls_codemp,$ls_cedcli);
	list($ls_codcli,$ls_nomcli,$ls_cedcli) = $la_arrdatacli;
	if($ls_codcli=="")
	{
	$io_msg->message ("ï¿½ï¿½ï¿½ï¿½El Cliente no se encuentra registrado, verifique nro. de Cï¿½dula, sino dirijase al registro de cliente para realizar un nuevo registro!!!!");
	?>
		<script language=JavaScript>
		if(confirm('ï¿½Desea verificar nro. de cï¿½dula nuevamente?'))
		{
			//alert ('paso');
			f=document.form1;
			f.txtcodcli.value='';f.txtcedcli.value='';
		}
		else
		{
			location.href='sigesp_sfc_d_cliente.php';
		}
		</script>
	<?php
	}
}
/********************************* ANULAR FACTURA ********************************************/
if ($ls_operacion=="ue_anular")
{
	 $lb_valido_cob=false;
	 //$_SESSION["ls_codtienda"] = '0001';
         $ls_codtie=$_SESSION["ls_codtienda"];
         
         //$io_msg->message ("Session: ".$ls_codtie);
         //$io_msg->message ("POST: ".$lscodtie2);
         $ls_obsAnul = $_POST[txtobsanul];
	 $la_arrdataest=$io_factura->uf_select_estfaccon($ls_codemp,$ls_numfac);
	 list($ls_estfaccon,$ls_estfac) = $la_arrdataest;
	 if ($ls_estfac=="N") //Si la factura no esta CERRADA
	 {
	 	if ($ls_estfaccon=="A")
		{

		  	$io_msg->message ("!Factura ya esta ANULADA!");
		}
	 	elseif ($ls_estfaccon=="P" || $ls_estfaccon=="N")
		{
		  	$io_msg->message ("!Factura por COBRAR serï¿½ anulada!");
		}
		elseif ($ls_estfaccon=="C")
		{
		   $io_msg->message ("!Factura CANCELADA serï¿½ anulada!");
		   $ls_estfaccon="";
		}
	 	else
		{
		  $ls_estfaccon="";
		}

	     /*****************  verificar si posee "CARTAS ORDENES COBRADAS" ******************************/
		for ($li_i=1;$li_i<$li_filasfpago;$li_i++)
    	{
		   $la_detapag["codforpag"][$li_i]=$_POST["txtcodforpag".$li_i];
		   $la_detapag["numinst"][$li_i]=$_POST["txtnuminst".$li_i];
	   		if ( $la_detapag["codforpag"][$li_i]=='04')
	   		{
	  			$lb_validocobcartord=$io_factura->uf_verificar_cobrosfaccarta($ls_codemp,$la_detapag["numinst"][$li_i],$ls_codcli);
	   		}
		}
		/*****************  verificar si posee "COBROS" ******************************/
		$lb_validocobfac=$io_factura->uf_verificar_cobrosfac($ls_codemp,$ls_numfac,$ls_codcli);
                //$io_msg->message ($lb_validocobfac);
		/*****************  verificar si posee "DEVOLUCIONES" ******************************/
		$lb_validodev=$io_factura->uf_verificar_devoluciones($ls_codemp,$ls_numfac,$ls_codcli);
	  	if($lb_validocobfac || $lb_validocobcartord || $lb_validodev)
	   	{
			$io_msg->message ("El Cliente posee un cobro pendiente o una devoluciï¿½n no se puede anular!!!");
	   	}
	   	else
	   	{
		 	$ls_estfaccon="";
	   	if (($ls_estfaccon=="" or $ls_estfaccon!="A"))
	    {
	 		 $ls_estfaccon="A";
                         //$lb_valido=true;
                         //$io_msg->message ("Paso 1 **$ls_obsAnul**");
			 $lb_valido=$io_factura->uf_actualizar_facturastatus($ls_numfac,$ls_estfaccon,$li_filasconcepto,$ls_numcot,$ls_numor,$li_filasfpago,$la_seguridad,$ls_obsAnul);
                         //$io_msg->message ("Paso 2");
			if ($lb_valido==true)
			{
				$io_msg->message ("Factura Anulada Exitosamente");
                                //aqui imprimir reporte!!!!
                                $fechaanu = date('d/m/Y');
                                ?>
                                     <script language="JavaScript">
                                                    var numfac="<?php print $ls_numfac; ?>";
                                                    //alert(ls_formalibre);
                                                    pagina="reportes/sigesp_sfc_rep_factura_anulada.php?numfac="+numfac;
                                                    popupWin(pagina,"catalogo",580,700);
                                     </script>
                                <?php
                                
				$ls_fecemi=date('d/m/Y');
				$ls_numcot="0000000000000000000000000";
				$ls_codcli="";
				$ls_cedcli="";
				$ls_nomcli="";
				$ls_subtot="0,00";
				$ls_subtot2="0,00";
				$ls_moniva="0,00";
				$ls_monivas="0,00";
				$ls_moniva2="0,00";
				$ls_monto="0,00";
				$ls_conpag=1;
				$ls_especial="F";
				$ls_estcot="";
				$ls_estfaccon="";
				$ls_nuevo='N';
				$ls_operacion="";

	//*******************************Grid Conceptos******************************
	$li_filasconcepto=1;
    $la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=21 style= text-align:center readonly><input name=txtexiste".$li_filasconcepto." type=hidden id=txtexiste".$li_filasconcepto.">";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][3]="<input name=txtcodalm".$li_filasconcepto." type=text id=txtcodalm".$li_filasconcepto." class=sin-borde size=11 maxlength=10 style= text-align:left>";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txtdesalm".$li_filasconcepto." type=text id=txtdesalm".$li_filasconcepto." class=sin-borde size=20 maxlength=255 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txtnompro".$li_filasconcepto." type=text id=txtnompro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly><input name=txtcod_pro".$li_filasconcepto." type=hidden id=txtcod_pro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly>";
	$la_objectconcepto[$li_filasconcepto][6]="<input name=txtprepro".$li_filasconcepto." type=text id=txtprepro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly><input name=txtcosto".$li_filasconcepto." type=hidden id=txtcosto".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][7]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][8]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right>";
	$la_objectconcepto[$li_filasconcepto][9]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly>";
	$la_objectconcepto[$li_filasconcepto][10]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style=text-align:center size=5 readonly>";

	//*******************************Grid Formas de Pago******************************
	$li_filasfpago=1;
	$la_objectfpago[$li_filasfpago][1]="<input name=txtcodforpag".$li_filasfpago." type=text id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_filasfpago." type=hidden id=txtcodent".$li_filasfpago."class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][2]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=20 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][3]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=20 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][4]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=35 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][5]="<input name=txtctabanco".$li_filasfpago." type=text id=txtctabanco".$li_filasfpago." class=sin-borde size=25 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][6]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
$la_objectfpago[$li_filasfpago][7]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
}
	}
	}
}
else
{
  $io_msg->message ("La factura esta CERRADA no se puede anular!!!");
}
	print("<script language=JavaScript>");
	print("pagina='sigesp_sfc_d_factura.php';");
    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
    print("</script>");
}
/*******************************************************************************************************/
/*************************** VER REPORTE DE FACTURA ****************************************************/
/*******************************************************************************************************/
if($ls_operacion=="VER")
{
	$ls_operacion="";
	$lb_valido_repfac=$io_factura->uf_imprimir_factura($ls_numfac,&$ls_sql);
  	if ($lb_valido_repfac==true)
	{
	?>
    	 <script language="JavaScript">
	 		var ls_sql="<?php print $ls_sql; ?>";
	 		var ls_numcontrol="<?php print $ls_numcontrol; ?>";
			ls_formalibre='<?php print $ls_formalibre ?>';
                        moniva = '<?php echo $_POST['txtmoniva'];  ?>';
                        monivas = '<?php echo $_POST['txtmonivas'];  ?>';
                        baseiva1 = '<?php echo $_POST['txtmoniva1'];  ?>';
                        baseiva2 = '<?php echo $_POST['txtmoniva2'];  ?>';
                        total = '<?php echo $_POST['txtmonto'];  ?>';
			//alert(moniva);
			if (ls_formalibre=='S')
			{
                               
				cadena = '&moniva='+moniva+'&monivas='+monivas+'&baseiva1='+baseiva1+'&baseiva2='+baseiva2+'&total='+total;
                                //alert(cadena);
                                pagina="reportes/sigesp_sfc_rep_imprimirfacturalibre.php?sql="+ls_sql+"&ls_numcontrol="+ls_numcontrol+cadena;
			}
			else
			{
				pagina="reportes/sigesp_sfc_rep_imprimirfactura.php?sql="+ls_sql;

			}

				popupWin(pagina,"catalogo",580,700);
    	 </script>
    <?php
    }
}
if ($ls_operacion=="ue_actualizarestacot")
{
/*---------------------------- ACTUALIZAR COTIZACION A "FACTURADA"   ------------------------------------------*/
   if ($ls_estcot='P')
   {
	$ls_estcot='E';
	$lb_valido=$io_cotizacion->uf_update_cotizacionstatus($ls_numcot,$ls_estcot,$la_seguridad);
   }
}
?>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  	<tr>
    	<td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  	</tr>
	<tr>
    <td width="516" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1 Estilo3">Sistema de Facturaci&oacute;n </span></td>
    <td width="262" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20">&nbsp;</td>
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
  <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><div align="left">
  <a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a>
  <a href="javascript:ue_guardar();" ><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a>
<a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a>
<?php
if ($ls_nuevo!='N')
{
?>
<a href="javascript:ue_ver();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a>
<a href="javascript:ue_anular();"><img src="../shared/imagenes/editdelete.png" width="22" height="22" border="0"></a>
<?php
}
?>
<a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
  </tr>
</table>
  <p>&nbsp;</p>
  <form name="form1" method="post"  action="" onunload= "ue_nuevo();">
    <?php
	   /*********************************         SEGURIDAD               *************************************/
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
	   //************************************         SEGURIDAD          ***********************************************/
	?>

  	<input name="txtconsulta" type="hidden" id="txtconsulta" value="<?php print $ls_consulta; ?>">
  	<input name="txtcanpro" type="hidden" id="txtcanpro">
 	 <input name="txtporcar" type="hidden" id="txtporcar">
	 <input name="txtcorrelativo" type="hidden" id="txtcorrelativo">
	  <input name="txtcorrelativocon" type="hidden" id="txtcorrelativocon">
	  <input name="txtnumcontrol" type="hidden" id="txtnumcontrol">
 	 <input name="txtmontoforpag" type="hidden" id="txtmontoforpag">
 	 <input name="histatus" type="hidden" id="histatus" value="<?php print $ls_histatus; ?>">
  	<table width="912" height="382" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
    	<tr class="titulo-celdanew">
      		<th height="19" colspan="8" class="titulo-celdanew" scope="col">Facturar</th>
    	</tr>
    	<tr class="formato-blanco">
      		<td height="20" colspan="8">
				<input name="hidremoverconcepto2" type="hidden" id="hidremoverconcepto2">
       			 <input name="txtfilasfpago" type="hidden" id="txtfilasfpago" value="<?php print $li_filasfpago ?>">
          		<input name="hidremoverfpago" type="hidden" id="hidremoverfpago" value="<? print $li_removerfpago ?>">
          		<!--<input name="hidcodalm" type="hidden" id="hidcodalm" value="">-->
      			<input name="txtmoncar" type="hidden" id="txtmoncar">
			 <input name="nuevo" type="hidden" id="nuevo" value="<? print $ls_nuevo ?>";>
        		<div align="left">
          		<input name="removerretenciones" type="hidden" id="removerretenciones" value="<? print $li_removerretenciones?>">
          		<input name="filasretenciones" type="hidden" id="filasretenciones" value="<? print $li_filasretenciones?>">
          		<input name="txtestcot" type="hidden" id="txtestcot" value="<?php print $ls_estcot ?>">
          		<input name="txtestfaccon" type="hidden" id="txtestfaccon" value="<?php print $ls_estfaccon ?>">
          		<input name="txtnumcot_ant" type="hidden" id="txtnumcot_ant" value="<?php print $ls_numcot_ant ?>">
          		<input name="txtnumor_ant" type="hidden" id="txtnumor_ant" value="<?php print $ls_numor_ant ?>">
          		<input name="txtestcot_ant" type="hidden" id="txtestcot_ant" value="<?php print $ls_estcot_ant ?>">
          		<input name="operacion" type="hidden" id="operacion" value="<? print $ls_operacion ?>">
          		<input name="mostrar" type="hidden" id="mostrar" value="<? print $ls_operacion ?>">
          		<input name="opemostrar" type="hidden" id="opemostrar" value="<? print $ls_opemostrar ?>">
          		<input type="hidden" name="hidbanexi" value="<? print $ls_hidbanexi ?>">
        		</div>
			</td>
    	</tr>
   		 <tr class="formato-blanco">
      		<?php

	    	if ($ls_estfaccon!="")
			{
			?>

		 		<td><div align="right">N&ordm; Control </div></td>
           		<td colspan="2">
			 		<div align="left">
						<input name="txtnumcon" type="text" id="txtnumcon" value="<?
						if ($ls_numcontrol<>"")
						{print $ls_numcontrol;}
						else {print $ls_numcon;} ?>" size="28" maxlength="25" readonly="true">
			 		</div>

		 		</td>

<tr class="formato-blanco">
		   		<td><div align="right">N&ordm; Factura </div></td>
           		<td colspan="2">
				<div align="left">
					<input name="txtnumfac" type="text" id="txtnumfac" value="<? print $ls_numfac ?>" size="28" maxlength="25" readonly="true">
                                        <input name="txtcodtienda" type="hidden" id="txtcodtienda" value="<? print $ls_codtie ?>" size="28" maxlength="25" readonly="true">
                                        <input name="txtobsanul" type="hidden" id="txtobsanul" value="">
		 		</div>

		 		</td>



	  		<?php
	    	}
			else
			{
	  		?>

				<div align="left">
				<input name="txtnumcon" type="hidden" id="txtnumcon" value="<?
						if ($ls_numcontrol<>"")
						{print $ls_numcontrol;}
						else {print $ls_numcon;} ?>" size="28" maxlength="25" readonly="true">
			 	</div>

				<div align="left">
	     		<input name="txtnumfac" type="hidden" id="txtnumfac" value="<? print $ls_numfac ?>" size="28" maxlength="25" readonly="true">
                        <input name="txtcodtienda" type="hidden" id="txtcodtienda" value="<? print $ls_codtie ?>" size="28" maxlength="25" readonly="true">
                        <input name="txtestfac" type="hidden" id="txtestfac" value="<? print $ls_estfaccon; ?>" size="28" maxlength="25" readonly="true">
		 		</div>



	  		<?php
	    	}
	  		?>
      		<td colspan="3"><div align="left">
			<span class="titulo-cat&aacute;logo">
	 		 <?php
	 			if ($ls_estfaccon=="A")
				{
				?>
        			<font color="#006600">ANULADA</font>
       			<?php
				}
				elseif ($ls_estfaccon=="P")
				{
				?>
        			<font color="#006600">POR COBRAR</font>
        		<?php
				}
				elseif ($ls_estfaccon=="N")
				{
				?>
        			<font color="#006600">POR COBRAR</font>
        		<?php
				}
				elseif ($ls_estfaccon=="C")
				{
				?>
        			<font color="#006600">CANCELADA</font>
        		<?php
				}
				elseif ($ls_estfaccon=="")
				{
				?>
        			<font color="#006600"></font>
        		<?php
				}
				?>
			</span></div>
			</td>
      		<td width="111"><div align="right">Fecha</div></td>
     		<td>
	   			<?php
				if ($ls_estfaccon!="")
		 		{
				?>
	    			<input name="txtfecemi" type="text" id="txtfecemi"  style="text-align:left" value="<?php print $ls_fecemi?>" size="11" maxlength="10"    datepicker="false" readonly="true">
	    		<?php
	     		}
				else
				{
	    		?>
	 				 <input name="txtfecemi" type="text" id="txtfecemi"  style="text-align:left" value="<?php print $ls_fecemi?>" size="11" maxlength="10"  datepicker="true"  readonly="true">
      			<?php
	    		}
				?>
			</td>
   	  </tr>
    	<tr class="formato-blanco">
      		<td height="22"><div align="right">Cliente</div></td>
      		<td colspan="7">
			<input name="txtcodcli" type="text" id="txtcodcli" style="text-align:center " value="<?php print $ls_codcli ?>" size="15" maxlength="15" readonly>
	  		<input name="txtcedcli" type="text" id="txtcedcli" style="text-align:center " value="<?php if ($ls_cedcli!="")
		{
		print $ls_cedcli;} ?>" size="15" maxlength="15" onBlur="ue_buscarcli();" onKeyPress="return(validaCajas(this,'z',event,254))" title="El RIF Debe comenzar con J,V,G ï¿½ E">
          	<?php
			 if ($ls_estfaccon!="")
			  {
			 ?>
<img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0">
		   <?php
			  }
			  else
			  {
			  ?>
<a href="javascript:ue_catclientefac();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
			<?php
			  }
		    ?>
          <input name="txtnomcli" type="text" id="txtnomcli"  style="text-align:left" class="sin-borde" value="<?php print $ls_nomcli ?>" size="70" maxlength="100" readonly="true">      </td>
    </tr>
    <tr class="formato-blanco">
      <td height="22"><div align="right">N&ordm; Cotizaci&oacute;n</div></td>
      <td colspan="7"><input name="txtnumcot" type="text" id="txtnumcot" style="text-align:center " value="<? print $ls_numcot ?>" size="28" maxlength="25"  readonly="true">
        <?php
		if ($ls_estfaccon!="")
		 {
		?>
        <img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15"  border="0">
        <?php
		 }
		else
		 {
		 ?>
        <a href="javascript:ue_catcotizacion();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
        <?php
		}
		?>
        <span class="titulo-cat&aacute;logo">
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
        </span></td>
    </tr>
     <tr class="formato-blanco">
      <td height="22"><div align="right">N&ordm; Orden</div></td>
      <td colspan="7"><input name="txtnumor" type="text" id="txtnumor"  style="text-align: left" value="<? print $ls_numor ?>" size="28" maxlength="25"></td>
    </tr>
    <tr class="formato-blanco">
      <td height="22"><div align="right">Condicion Pago </div></td>
      <td width="135">
<!----------------------------------- COMBO CONDICION DE PAGO ----------------------------------------------------------------->
			 <?php
			 if ($ls_estfaccon!="")
			  {
			 ?>
			  <select name="combo_conpag"    size="1" id="combo_conpag"  > <!-- disabled="disabled" -->
			 <?php
			  }
			  else
			  {
			  ?>
			  <select name="combo_conpag"    size="1" id="combo_conpag"  >
			 <?php
			  }
				  if($ls_conpag==1)
				  {
			      ?>
            <option value="1"  onClick="actualizar_check1();" selected >CONTADO</option>
            <option value="2" onClick="actualizar_check1();">CREDITO</option>
			<!--<option value="3" onClick="actualizar_check1();">PARCIAL</option>
			<option value="4" onClick="actualizar_check1();">CARTA ORDEN</option> -->
                 <?php
				  }
				 elseif($ls_conpag==2)
				  {
				 ?>
            <option value="1"  onClick="actualizar_check1();">CONTADO</option>
            <option value="2" onClick="actualizar_check1();" selected>CREDITO</option>
			<!--<option value="3" onClick="actualizar_check1();">PARCIAL</option>
			<option value="4" onClick="actualizar_check1();">CARTA ORDEN</option> -->
                <?php
				 }
			     elseif($ls_conpag==3)
				 {
				 ?>
            <option value="1" onClick="actualizar_check1();" >CONTADO</option>
            <option value="2" onClick="actualizar_check1();" >CREDITO</option>
			<!--<option value="3" onClick="actualizar_check1();" selected>PARCIAL</option>
			<option value="4" onClick="actualizar_check1();">CARTA ORDEN</option> -->
                <?php
				 }
				 else
				 {
				 ?>
			<option value="1" onClick="actualizar_check1();" >CONTADO</option>
            <option value="2" onClick="actualizar_check1();" >CREDITO</option>
		<!--	<option value="3" onClick="actualizar_check1();" >PARCIAL</option>
			<option value="4" onClick="actualizar_check1();" selected>CARTA ORDEN</option> -->
				 <?php
				 }
				 ?>
          </select>
          <label></label></td>
      <td width="72">Especial</td>
      <td colspan="5">
				 <?php
			     if($ls_conpag==1 || $ls_conpag==4)
				  {
						if ($ls_especial=="V")
						{
					    ?>
						<input name="check1" type="checkbox"  checked="checked" id="check1" value="check1">
					    <?php
						}
						else
						{
						?>
					    <input name="check1" type="checkbox"  id="check1" value="check1">
					    <?php
						}
				 }
			     elseif($ls_conpag==2)
				 {
				        if ($ls_especial=="V")
						{
						?>
						<input name="check1" type="checkbox"   disabled="disabled" id="check1" value="check1">
						<?php
						}
						else
						{
						?>
						<input name="check1" type="checkbox"  disabled="disabled" id="check1" value="check1">
						<?php
						}
				 }
			     else
				 {
				        if ($ls_especial=="V")
						{
						?>
			     		<input name="check1" type="checkbox"  disabled="disabled" id="check1" value="check1">
	             		<?php
						}
						else
						{
						?>
			     		<input name="check1" type="checkbox"  disabled="disabled" id="check1" value="check1">
	             		<?php
						}
				 }
				 ?>	  <label></label></td>
    </tr>
    <?php if ($ls_mostrardias=="true" OR $ls_mostrardias2=="true")
    {
    ?>
    
    <tr class="formato-blanco">
     <td height="20" colspan="1"><div align="right"><input name="txtmostdias" id="txtmostdias"  type="text" class="sin-borde" style="text-align: right" value="Cantidad de Dias" size="20" maxlength="20" readonly="true"></div></td>
      <td height="13" colspan="7" align="left"><input name="txtdias" id="txtdias"  type="text" style="text-align: right" value="<? print $ls_dias ?>" size="20" maxlength="20"></td>
    <td ><div align=""><input name="txtmostdias2" id="txtmostdias2"  type="hidden" class="sin-borde" style="text-align: right" value="true" size="20" maxlength="20" readonly="true"></div></td>
    </tr>
   <?php 
    }
    else { ?>
    <tr class="formato-blanco">
     <td height="20" colspan="1"><div align="right"><input name="txtmostdias" id="txtmostdias"  type="hidden" style="text-align: right" value="Cantidad de Dias" size="20" maxlength="20" readonly="true"></div></td>
      <td height="13" colspan="7" align="left"><input name="txtdias" id="txtdias"  type="hidden" style="text-align: right" value="<? print $ls_dias ?>" size="20" maxlength="20"></td>      
      <td ><div align=""><input name="txtmostdias2" id="txtmostdias2"  type="hidden" class="sin-borde" style="text-align: right" value="" size="20" maxlength="20" readonly="true"></div></td>    
    </tr>	
    <?php } ?>	
    <tr class="formato-blanco">
      <td height="13" colspan="8">&nbsp;</td>
    </tr>
    
    <tr class="formato-blanco">
      <td height="37" colspan="8"><table width="615" height="37" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr class="formato-blanco">
          <td width="14" height="19">&nbsp;</td>
          <td width="593"><a href="javascript:ue_catproducto();"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:ue_catproducto();">Agregar Detalle </a></td>
        </tr>
        <tr align="center" class="formato-blanco">
          <td height="11" colspan="2"><?php $io_grid->makegrid($li_filasconcepto,$la_columconcepto,$la_objectconcepto,$li_anchoconcepto,$ls_tituloconcepto,$ls_nametable);?></td>
          <input name="filasconcepto" type="hidden" id="filasconcepto" value="<? print $li_filasconcepto;?>">
          <input name="hidremoverconcepto" type="hidden" id="hidremoverconcepto" value="">
        </tr>
      </table>      </td>
    </tr>
     <tr class="formato-blanco">
      <td height="21" colspan="7"><div align="right">Sub-Total (1)</div></td>
      <td><input name="txtsubtot" type="text" id="txtsubtot"  style="text-align: right" value="<? print $ls_subtot ?>" size="20" maxlength="20" readonly="true"></td>
    </tr> 
    <tr class="formato-blanco">
      <td height="21" colspan="7"><div align="right">Descuentos, bonificaciones y rebajas</div></td>
      <td><input name="txtdescubonirebajas" type="text" id="txtdescubonirebajas"  style="text-align: right" value="0,00" size="20" maxlength="20" readonly="true"></td>
    </tr>
    <tr class="formato-blanco">
      <td height="21" colspan="7"><div align="right">Fletes</div></td>
      <td><input name="txtfletes" type="text" id="txtfletes"  style="text-align: right" value="0,00" size="20" maxlength="20" readonly="true"></td>
    </tr>
    <tr class="formato-blanco">
      <td height="21" colspan="7"><div align="right">Sub-Total (2) </div></td>
      <td><input name="txtsubtot2" type="text" id="txtsubtot2"  style="text-align: right" value="<? print $ls_subtot2 ?>" size="20" maxlength="20" readonly="true"></td>
    </tr>
    <tr class="formato-blanco">
      <td height="21" colspan="7"><div align="right">Monto Exento</div></td>
      <td><input name="txtmonexento" type="text" id="txtmonexento"  style="text-align: right" value="<? print $ls_monexento ?>" size="20" maxlength="20" readonly="true"></td>
    </tr>
    <tr class="formato-blanco">
      <td height="21" colspan="7"><div align="right">Monto Exonerado o No Grabado</div></td>
      <td><input name="txtmonexonerado" type="text" id="txtmonexonerado"  style="text-align: right" value="0,00" size="20" maxlength="20" readonly="true"></td>
    </tr>
    <tr class="formato-blanco">
      <td height="21" colspan="7"><div align="right">Monto Base Imponible</div></td>
      <td><input name="txtmonbaseimponible" type="text" id="txtmonbaseimponible"  style="text-align: right" value="<? print $ls_monbaseimponible ?>" size="20" maxlength="20" readonly="true"></td>
    </tr>  
      <tr class="formato-blanco">
        <td height="21" colspan="6"><div align="right">IVA 12% Sobre</div></td> 
        <td><input name="txtmoniva1" type="text" id="txtmoniva1" style="text-align:right"  value="" size="6" maxlength="6" class="sin-borde" readonly="true" onChange="javascript:ue_calretencion();"></td>
        <td><input name="txtmoniva" type="text" id="txtmoniva" style="text-align:right"  value="<?php print $ls_moniva ?>" size="20" maxlength="20" readonly="true" onChange="javascript:ue_calretencion();"></td>
      </tr>
       <tr class="formato-blanco">
        <td height="21" colspan="6"><div align="right">IVA 8% Sobre</div></td>
        <td><input name="txtmoniva2" type="text" id="txtmoniva2" style="text-align:right"  value="" size="6" maxlength="6" class="sin-borde" readonly="true" onChange="javascript:ue_calretencion();"></td>
      <td><input name="txtmonivas" type="text" id="txtmonivas" style="text-align:right"  value="<?php print $ls_monivas ?>" size="20" maxlength="20" readonly="true" onChange="javascript:ue_calretencion();"></td>
      </tr>
	<!--  <tr class="formato-blanco">
        <td height="21" colspan="7"><div align="right">Total Impuestos (IVA)</div></td>
        <td><input name="txtmoniva" type="text" id="txtmoniva" style="text-align:right"  value="" size="20" maxlength="20" readonly="true" onChange="javascript:ue_calretencion();"></td>
      </tr>-->
      <tr class="formato-blanco">
        <td height="20" colspan="7"><div align="right">Total Bs</div></td>
        <td><input name="txtmonto" type="text" id="txtmonto"  style="text-align: right" value="<? print $ls_monto ?>" size="20" maxlength="20"  readonly="true"></td>
      </tr>
      <tr class="formato-blanco">
        <td height="20" colspan="3"><div align="right">Observaciones</div></td>
        <td><textarea name="txtobsfac" cols="80" rows="2"  id="txtobsfac" onKeyPress="return(textCounter(this,100))" onKeyDown="textCounter(this,100)" ><? print $ls_observaciones ?></textarea></td>       
      </tr>
      
      <tr class="formato-blanco">
        <td height="37" colspan="8"><div align="center">
         
               <table width="609" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr class="formato-blanco">
                  <td width="14" height="11">&nbsp;</td>
                  <td width="593"><a href="javascript:ue_catinstrpago();"></a><a href="javascript:ue_catinstrpago();"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"><span class="Estilo2">Forma de pago</span></a><a href="javascript:ue_catinstrpago();"></a></td>
                </tr>
                <tr align="center" class="formato-blanco">
                  <td height="11" colspan="2"><?php $io_grid->makegrid($li_filasfpago,$la_columfpago,$la_objectfpago,$li_anchofpago,$ls_titulofpago,$ls_nametablefpago);?></td>
                  <input name="filasfpago2" type="hidden" id="filasfpago2" value="<?php print $li_filasfpago;?>">
                  <input name="hidremoverfpago2" type="hidden" id="hidremoverfpago2" value="<?php print $li_removerfpago;?>">
                </tr>
                </table>
         
        </div>  </td>
  </tr>
  <tr class="formato-blanco">
    <td height="20" colspan="7"><div align="right">Total</div></td>
    <td width="217"><input name="txttotalforpag" align="right" type="text" id="txttotalforpag" value="<?php print $ls_totalforpag ?>" size="15" maxlength="15"   readonly="true"></td>
  </tr>
  <tr class="formato-blanco">
    <td height="20" colspan="7"><div align="right">Pendiente</div></td>
    <td><input name="txtresta" align="right" type="text" id="txtresta" value="<?php print $ls_resta ?>" size="15" maxlength="15" readonly="true"></td>
  </tr>
  </table>
<input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_chk ?>">
  <div align="center"></div>
  </form>
</body>
<script language="javascript">

/****************************** RUTINAS JAVASCRIPT ****************************/
function actualizar_check1()
{
   f=document.form1;
   f.operacion.value="";
   suiche_submit=true; 
    if (f.combo_conpag.value=='2')
     {	     	
	 f.txtmostdias2.value="true";
	 		
     }else if (f.combo_conpag.value=='3' ) {
         f.txtmostdias.value="true";
         f.txtmostdias2.value="" ;   
     }
     else
     {
     f.txtdias.value=""	;
	 f.txtmostdias2.value=""    ;
     }
     
   f.submit();
}
function ue_actualizarestacot()
{
  if (suiche_submit==false)
  {
	  f=document.form1;

	  numcot=f.txtnumcot.value;
	  numor=f.txtnumor.value;
	  estcot=f.txtestcot.value;
	  f.operacion.value="";
	  pagina="sigesp_actualizarestacot.php?estcot1="+estcot+"&numcot1="+numcot;
	  popupWin(pagina,"catalogo",1,1);
  }
}
function ue_actualizarpagina()
{
  f=document.form1;
  f.operacion.value="ue_cargarretenciones";
  f.action="sigesp_sfc_d_factura.php";
  suiche_submit=true;
  f.submit();
}
function ue_calretencion()
{
  f=document.form1;
  f.operacion.value="ue_cargarretenciones";
  f.action="sigesp_sfc_d_factura.php";
  suiche_submit=true;
  f.submit();

}
function ue_caliva()
{
  f=document.form1;
  f.operacion.value="ue_calcretencion";
  f.action="sigesp_sfc_d_factura.php";
  suiche_submit=true;
  f.submit();
}
function ue_ver()
{ 
   if (f.txtestfaccon.value=="A")
    {
	var numfac="<?php print $ls_numfac; ?>";
        pagina="reportes/sigesp_sfc_rep_factura_anulada.php?numfac="+numfac;
        popupWin(pagina,"catalogo",580,700);  
    } else { 
  f=document.form1;
  f.operacion.value="VER";
  f.action="sigesp_sfc_d_factura.php";
  suiche_submit=true;
  f.submit();
  }
}
function ue_catcotizacion()
{
 f=document.form1;

 f.operacion.value="";
 codcliente=f.txtcodcli.value;
  if (ue_valida_nuevo(txtnumfac,"!!Debe hacer clic en el boton nuevo!!!")==false)
	 {
		txtnumfac.focus();
		suiche=false;
	 }else{
	 pagina="sigesp_cat_cotizacionfactura.php?codcli1="+codcliente;
	 popupWin(pagina,"catalogo",850,450);
	 }

}

function ue_buscar()
{
  f=document.form1;
  li_leer=f.leer.value;
  if(li_leer==1)
   {
     f.operacion.value="";
     pagina="sigesp_cat_factura.php";
     popupWin(pagina,"catalogo",850,450);
	 //window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=350,resizable=yes,location=no");
   }
	else
	{
	  alert("No tiene permiso para realizar esta operacion");
	}
}
function ue_cargarfpago(numinst,monto,formapago,nombanco,codban,ctabanco,codforpag,metforpago,codent) //x
{
	//alert(metforpago);
    f=document.form1;
	f.operacion.value="ue_cargarfpago";
	lb_existe=false;
	for(li_i=1;li_i<=f.txtfilasfpago.value && !lb_existe;li_i++)
	{
		ls_codigo=eval("f.txtcodforpag"+li_i+".value");
		ls_num=eval("f.txtnuminst"+li_i+".value");
		ld_montoforpag=parseFloat(uf_convertir_monto(monto));
		ld_monto=parseFloat(uf_convertir_monto(f.txtmonto.value));

		if((ls_codigo==codforpag)&&(numinst==ls_num))
		{
			alert("esta forma de pago ya ha sido cargada!!!");
			lb_existe=true;
		}
	}

	/*if((ld_montoforpag>ld_monto)&&(f.check1.checked==false)){
		    alert("el monto del pago no puede ser mayor al monto de la factura!!!");
                    alert("ACA");
			lb_existe=true;
	}*/

	if(!lb_existe)
	{
	   	eval("f.txtcodforpag"+f.txtfilasfpago.value+".value='"+codforpag+"'");
		eval("f.txtmetforpag"+f.txtfilasfpago.value+".value='"+metforpago+"'");
		eval("f.txtcodent"+f.txtfilasfpago.value+".value='"+codent+"'");
		eval("f.txtdenforpag"+f.txtfilasfpago.value+".value='"+formapago+"'");
		eval("f.txtnuminst"+f.txtfilasfpago.value+".value='"+numinst+"'");
		eval("f.txtnombanco"+f.txtfilasfpago.value+".value='"+nombanco+"'");
		eval("f.txtctabanco"+f.txtfilasfpago.value+".value='"+ctabanco+"'");
		eval("f.txtcodban"+f.txtfilasfpago.value+".value='"+codban+"'");
		eval("f.txtmontoforpag"+f.txtfilasfpago.value+".value='"+monto+"'");
		f.submit();
	}

}
function ue_catinstrpago()
{



	f=document.form1;
	codcliente=f.txtcodcli.value;
	ls_monto=f.txtresta.value;

if (f.combo_conpag.value=="4")
  {
   ls_banord="0";
  }
  else
  {
   ls_banord="1";
  }

 if (f.txtestfaccon.value=="")
  {
	if ((f.combo_conpag.value=="1" || f.combo_conpag.value=="3" || f.combo_conpag.value=="4")  && f.txtmonto.value!="0,00")
      {
		f.operacion.value="";
		if(f.check1.checked)
		 {
		   pagina="sigesp_cat_instpago.php?codcli1="+codcliente+"&total="+ls_monto+"&espec=1&cartaord="+ls_banord;
		 }
		 else
		 {
		   pagina="sigesp_cat_instpago.php?codcli1="+codcliente+"&total="+ls_monto+"&espec=0&cartaord="+ls_banord;
		 }
		popupWin(pagina,"catalogo",580,450);
	  }
	else
	 {
	   if (f.combo_conpag.value=="2") alert("La condicion de pago es a credito.");
	   if (f.txtmonto.value=="0,00") alert("Debe agregar productos y la cantidad.");
	 }
 }
 else
 {
  alert("Â¡Factura no se puede modificar!");
 }
}

function ue_cargarcotizacion(codigo,cotizacion,nombre,fecha,monto,obs,estcot,cedcli)
{
	f=document.form1;
	f.txtnumcot_ant.value=f.txtnumcot.value;	
	f.txtestcot_ant.value=f.txtestcot.value;
	f.txtnumcot.value=cotizacion;	
	f.txtcodcli.value=codigo;
	f.txtcedcli.value=cedcli;
	f.txtnomcli.value=nombre;
	f.txtestcot.value=estcot;
	f.operacion.value="ue_cargarconcepto_cotizacion";
	f.action="sigesp_sfc_d_factura.php";
	suiche_submit=true;
	f.submit();
}

function ue_cargarfactura(codigo,numfac,numcon,cotizacion,orden,fecemi,conpag,monto,estfac,nombre,estfaccon,esppag,cedcli,observ,numdia)
{
	f=document.form1;
	f.txtnumfac.value=numfac;
	f.txtnumcot.value=cotizacion;
	f.txtnumor.value=orden;
	f.txtcodcli.value=codigo;
	f.txtcedcli.value=cedcli;
	f.txtfecemi.value=fecemi;
	f.txtnomcli.value=nombre;
	f.combo_conpag.value=conpag;
	f.txtmonto.value=monto;
	f.txtestfaccon.value=estfaccon;
	f.txtnumcon.value=numcon;
	f.txtnumcontrol.value=numcon;
	f.txtobsfac.value=observ;
	f.txtdias.value=numdia;
        f.txtcodtienda.value="<?php echo $_SESSION["ls_codtienda"];?>";
        

	if (f.combo_conpag.value=='2' || f.combo_conpag.value=='3'){
	f.txtmostdias.value="true";
	f.txtmostdias2.value="true";	
        
   }else{
    f.txtmostdias.value="";
	f.txtmostdias2.value="";	
    }
	
	if (esppag=="V")
	{
	 f.check1.checked=true;
	}
	else
	{
	f.check1.checked=false;
	}
	//if f.txtconsulta=='M')
	//alert (f.txtconsulta.value);
	f.operacion.value="ue_cargarconcepto_factura";
	f.histatus.value="C";
	f.action="sigesp_sfc_d_factura.php";
	suiche_submit=true;
	f.submit();
}
function ue_cargarcliente(codcli,cedcli,nomcli,dircli,telcli,celcli,codpai,codest,codmun,codpar) //hh
{
    f=document.form1;
	f.txtcodcli.value=codcli;
	f.txtcedcli.value=cedcli;
	f.txtnomcli.value=nomcli;

}
function ue_cargarnrofactura(nrofactura,correlativo,numcontrol,correlativocon)
{
    f=document.form1;

	f.txtnumfac.value=nrofactura;
	f.txtcorrelativo.value=correlativo;
	f.txtnumcontrol.value=numcontrol;
	f.txtcorrelativocon.value=correlativocon;

//alert(f.txtnumcontrol.value);
	f.operacion.value='ue_guardar';
	//alert(f.operacion.value);
	suiche_submit=true;
	f.submit();
}
function ue_catclientefac()
{

	f=document.form1;
	f.operacion.value="";
    if (ue_valida_nuevo(txtnumfac,"!!Debe hacer clic en el boton nuevo!!!")==false)
	 {
		txtnumfac.focus();
		suiche=false;

	 }else{
	pagina="sigesp_cat_cliente1.php";
	popupWin(pagina,"catalogo",520,350);
	}

}

function ue_cargarproducto(codpro,denpro,preven,moncar,porcar,codalm,nomfisalm,exi,cod_pro,nompro,costo)
{
    f=document.form1;
	//alert (costo);
	f.operacion.value="ue_cargarconcepto";
	ls_item="<?php print $_SESSION["ls_item"]?>";
	lb_existe=false;
	//alert (existe);
	
  
	
	 if (parseFloat(f.filasconcepto.value)>ls_item)
	 {
		alert("Ha excedido el nï¿½mero maximo de lï¿½neas en la Factura!")
		suiche=false;
	 }
	 else
	{
	if(preven!="0,00")
	{
	   for(li_i=1;li_i<=f.filasconcepto.value && !lb_existe;li_i++)
	   {
		 ls_codigo=eval("f.txtcodpro"+li_i+".value");
		 ls_proveedor=eval("f.txtcod_pro"+f.filasconcepto.value+".value");
		 if(ls_codigo==codpro && cod_pro==ls_proveedor)
		  {
			alert("El producto ya fue cargado!!!");
			lb_existe=true;
		  }
	   }

	   if(!lb_existe)
	    {
		//alert (exi);
		eval("f.txtcodpro"+f.filasconcepto.value+".value='"+codpro+"'");
		eval("f.txtexiste"+f.filasconcepto.value+".value='"+exi+"'");
		eval("f.txtcodalm"+f.filasconcepto.value+".value='"+codalm+"'");
		eval("f.txtdesalm"+f.filasconcepto.value+".value='"+nomfisalm+"'");
		eval("f.txtnompro"+f.filasconcepto.value+".value='"+nompro+"'");
		eval("f.txtcod_pro"+f.filasconcepto.value+".value='"+cod_pro+"'")
		eval("f.txtdenpro"+f.filasconcepto.value+".value='"+denpro+"'");
		eval("f.txtprepro"+f.filasconcepto.value+".value='"+preven+"'");
		eval("f.txtcosto"+f.filasconcepto.value+".value='"+costo+"'");
		eval("f.txtporcar"+f.filasconcepto.value+".value='"+porcar+"'");
		eval("f.txtmoncar"+f.filasconcepto.value+".value='"+moncar+"'");
		suiche_submit=true;
    	f.submit();
	    }
	}
	else{
	  alert("por favor actualize el precio del producto!!!");
	}
 }

}
function ue_catproducto()
{
f=document.form1;
ls_cliente=f.txtcodcli.value;
if (f.txtestfaccon.value=="A")
	  {
	    alert("Factura ANULADA no se puede modificar!");
	  }
	else if (f.txtestfaccon.value=="P" || f.txtestfaccon.value=="N")
	  {
	  alert("Factura por COBRAR no se puede modificar!");
	  }
	else if (f.txtestfaccon.value=="C")
	  {
	  alert("Factura CANCELADA no se puede modificar!");
	  }
	else
	  {
		validar=ue_validar_cabecera();
		if (validar==true)
		{
			f.operacion.value="";
			pagina="sigesp_cat_producto_almacen2.php?cliente="+ls_cliente;
			popupWin(pagina,"catalogo",890,350);
		}
     }
}
function ue_removerfpago(li_fila)
{
	f=document.form1;
	f.hidremoverfpago.value=li_fila;
	f.operacion.value="ue_removerfpago";
	f.action="sigesp_sfc_d_factura.php";
	suiche_submit=true;
	f.submit();
}
function ue_removerconcepto(li_fila)
{
	f=document.form1;
	f.hidremoverconcepto.value=li_fila;
	f.operacion.value="ue_removerconcepto";
	f.action="sigesp_sfc_d_factura.php";
	suiche_submit=true;
	f.submit();
}
function ue_guardar()
{
f=document.form1;
suiche=false;
li_incluir=f.incluir.value;
li_cambiar=f.cambiar.value;
lb_status=f.hidstatus.value;
if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
{

	if (lb_status!="C")
	{
		f.hidstatus.value="C";
	}

	if (f.txtestfaccon.value=="")
	 {

		with(f)
			{
				if (ue_valida_null(txtcodcli,"Cliente")==false)
				 {
				 	txtcodcli.focus();
					suiche=true;
				 }

				else if (ue_valida_null(txtfecemi,"Fecha")==false)
				 {
					  txtfecemi.focus();
					  suiche=true;
				 }
				 else if (ue_valida_null(txtnumfac,"No. de factura")==false)
				 {
					  txtnumfac.focus();
					  suiche=true;
				 }
				else if (ue_valida_null(txtmonto,"Monto")==false)
				 {
					  txtmonto.focus();
					  suiche=true;
				 }
				 /*else if (combo_conpag.value=="1" && txttotalforpag.value!=txtmonto.value && check1.checked==false)
				 {
					alert("El monto de la factura debe coincidir con el de las formas de pago.");
					suiche=true;
				 }*/
                                 else if (combo_conpag.value=="1" && parseFloat(uf_convertir_monto(txtresta.value)) < 0)
				 {
					si=confirm("Se Generara una Nota de Credito a favor del cliente, Esta seguro de seguir?");
                                         if(si)
                                         {
                                            suiche=false;
                                         }else{
                                            alert("El monto de la factura debe coincidir con el de las formas de pago.");
                                            suiche=true;
                                         }

				 }
                                 else if (combo_conpag.value=="1" && parseFloat(uf_convertir_monto(txtresta.value)) > 0)
				 {
					alert("El monto de la factura debe coincidir con el de las formas de pago.");
					suiche=true;
				 }
				 else if (combo_conpag.value=="2" && txttotalforpag.value!="0,00")
				 {
					alert("No debe existir forma de pago ya que la factura es a crÃ©dito.");
					suiche=true;
				 }
				 else if (combo_conpag.value=="3" && txttotalforpag.value>=txtmonto.value)
				 {

				   if  (parseFloat(uf_convertir_monto(txttotalforpag.value))>parseFloat(uf_convertir_monto(txtmonto.value)))
				   //if  (txttotalforpag.value<txtmonto.value)
				     {
					       alert("El monto de la forma de pago debe ser menor al Monto de la factura.");
						   suiche=true;
					 }
					 else if (txttotalforpag.value==txtmonto.value)
					 {
					 alert("Esta cancelando la factura de contado debe cambiar a forma de pago de 'CONTADO'! ");
					      suiche=true;
					 }

				 }
				 else if (txtmonto.value=="0,00")
				 {
					  alert("Debe incluir algun producto y su cantidad.");
					  suiche=true;
				 }
				 else if (parseFloat(uf_convertir_monto(txttotalforpag.value))<=parseFloat(uf_convertir_monto(txtmonto.value)) && combo_conpag.value=="1" && check1.checked==true ) //Verifica si factura es de CONTADO y CASO ESPECIAL
				 {

					  alert("La factura es caso especial seleccione otra condiciï¿½n de pago si el monto es menor al de la factura.");
					  suiche=true;
				 }
				 else if (f.filasconcepto.value==1)
				 {

				alert("Introduzca un producto");

				 }
				 else if (f.filasconcepto.value>1)
				 {
				  for (i=1;i<=f.filasconcepto.value;i++)
				     {

				       if (eval("f.txtcanpro"+i+".value")=='0,00')
					   {
					   alert ("Debe colocar una cantidad valida al producto");
					    canpro+i.focus();

						}

					 }
				 }

				 if (suiche==false)
				    {
						 lb_valido=validar_existencia();
						 if(lb_valido=="NO"){
						 alert("Esta intentando realizar una venta por una cantidad invalida!!!");
						 }else{
						  f.operacion.value="ue_editfactura";
						  f.action="sigesp_sfc_d_factura.php";
						  suiche_submit=true;
					 	  f.submit();


						 }


					}

			}
	 }
	 else
	 {
	  alert("ï¿½LA Factura no se puede modificar!");
	 }
}else{
	alert("No tiene permiso para realizar esta operacion");
}

}
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
	with(f)
		{
		     if (parseFloat(f.filasconcepto.value)>ls_item)
			 {
			 	alert("Ha excedido el nï¿½mero maximo de lï¿½neas en la Factura!")
				suiche=false;
			 }
			else if (ue_valida_nuevo(txtnumfac,"!!Debe hacer clic en el boton nuevo!!!")==false)
			 {
			 	txtnumfac.focus();
				suiche=false;
			 }

			else if (ue_valida_null(txtfecemi,"Fecha")==false)
			 {
				  txtfecemi.focus();
				  suiche=false;
			 }
			 else if (ue_valida_null(txtcodcli,"Cliente")==false)
			 {
				  txtcodcli.focus();
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
function ue_nuevo()
{
  f=document.form1;
  li_incluir=f.incluir.value;
  if(li_incluir==1)
   {

    f.operacion.value="ue_nuevo";
	f.txtnumcot_ant.value=f.txtnumcot.value;
	f.txtnumor_ant.value=f.txtnumor.value;
	f.txtnumor_ant.value=f.txtnumor.value;
 	f.txtestcot_ant.value=f.txtestcot.value;
	f.txtnumfac.value="";
	f.txtnumcot.value="0000000000000000000000000";
	f.txtnumor.value="";
	f.txtcodcli.value="";
	f.txtcedcli.value="";
	f.txtnomcli.value="";
	f.txtsubtot.value="";
	f.txtdescubonirebajas="";
	f.txtfletes="";
	f.txtmoniva1="";
	f.txtmoniva2="";
	f.txtmoniva.value="";
	f.txtmonto.value="";
	f.txtresta.value="";
	f.txttotalforpag.value="";
    f.filasconcepto.value="";
	f.hidremoverconcepto.value="";
	f.txtconsulta.value="";
	f.txtobsfac.value="";
    f.txtdias.value="";
    f.txtmoniva1.value="";
    f.txtmoniva2.value="";
    f.txtmoniva.value="";
    f.txtmonivas.value="";
    f.txtmostdias.value="";
	f.txtmostdias2.value="";	
     f.action="sigesp_sfc_d_factura.php";
	 suiche_submit=true;
     f.submit();
   }
   else
   {
     alert("No tiene permiso para realizar esta operacion, o no esta registrado como cajero");
   }
}
/******************************************VALIDACIONES**************************************************/
function ue_subtotalfpago()
{
   	f=document.form1;
	li_filasfpago=f.txtfilasfpago.value;
	ld_monto=0;
	ls_cero="0,00";
	suiche=true;
	ld_monfac=parseFloat(uf_convertir_monto(f.txtmonto.value));//monto de la factura

	for(li_i=1;li_i<=li_filasfpago;li_i++)
	{
	   if(eval("f.txtmontoforpag"+li_i+".value")=="")
		{
		   ld_montoforpag=0;
		}
	    else
		{
		   ld_montoforpag=parseFloat(uf_convertir_monto(eval("f.txtmontoforpag"+li_i+".value")));
		   ld_monto=(roundNumber(ld_monto)+roundNumber(ld_montoforpag));
		   /*if (roundNumber(ld_monto)>roundNumber(ld_monfac) && f.check1.checked==false && f.txtconsulta.value!="M")
			{
				   alert("El monto de la forma de pago no puede ser superior al monto de la factura");
                                   //alert ("Por aca");
				   suiche=false;
			}*/
			if ( f.txtconsulta.value=="M")
			{
			ld_montoforpag=roundNumber(ld_montoforpag)-roundNumber(ld_monto);
			}

		}
	}

	if (suiche==true)
	{


	 f.txttotalforpag.value=uf_convertir(roundNumber(ld_monto));

	 resta=roundNumber(ld_monfac)-roundNumber(ld_monto);
	 f.txtresta.value=uf_convertir(roundNumber(resta));
	}

	return suiche;
}
function ue_subtotal()
{  
	f=document.form1;
	li_filasconcepto=f.filasconcepto.value;
	ld_subtotal=0;
	ld_subtotal2=0;
	ld_fletes=0;
	ld_iva=0;
	ld_ivas=0;
	ld_sumivaexento=0;
	ld_monto=0;
	ld_exento=0;
	ld_iva1=0;
	ld_iva2=0;
	ld_descubonirebajas=0;
	ls_cero="0,00";
	for(li_i=1;li_i<=li_filasconcepto-1;li_i++)
	{
	   if(eval("f.txtcanpro"+li_i+".value")=="")
		  {
		   ld_canpro=0;
		  }
	   else
		  {
		  //alert (eval("f.txtexiste"+li_i+".value"));
		   ld_canpro=parseFloat(uf_convertir_monto(eval("f.txtcanpro"+li_i+".value")));
		   ld_moncar=parseFloat(uf_convertir_monto(eval("f.txtporcar"+li_i+".value")));
		   ld_descubonirebajas=parseFloat(uf_convertir_monto(eval("f.txtdescubonirebajas.value")));
		   ld_fletes=parseFloat(uf_convertir_monto(eval("f.txtfletes.value")));
		   ld_existe=parseFloat(eval("f.txtexiste"+li_i+".value")); //
		   		   		   		   		   
		   if(eval("f.txtprepro"+li_i+".value")=="")
		      {
		       ld_prepro=0;
		      }
		      else
		      {
		         ld_prepro=parseFloat(uf_convertir_monto(eval("f.txtprepro"+li_i+".value")));
		      }
			  /*alert (ld_canpro);
			  alert (ld_existe);*/
			  if(ld_canpro>ld_existe && f.txtconsulta.value!="M")
			   {
			   // alert (uf_convertir(ld_existe));
				 eval("f.txtcanpro"+li_i+".value=uf_convertir(ld_existe)");
				 //eval("f.txttotpro"+li_i+".value='0,00'");
				 alert("La cantidad a facturar del Producto: "+eval("f.txtdenpro"+li_i+".value")+" es mayor que la existencia: "+uf_convertir(ld_existe)+" del almacen seleccionado");
				 if (ld_existe>0)
				 {
				 ld_moncar=roundNumber(ld_moncar/100);
		         ld_totpar=roundNumber(ld_prepro)*roundNumber(ld_existe);
			     //ld_iva=roundNumber(ld_iva) + ((roundNumber(ld_moncar)*roundNumber(ld_prepro))*roundNumber(ld_existe));
			     ld_subtotal=roundNumber(ld_subtotal)+roundNumber(ld_totpar);
				 ld_subtotal2=roundNumber(ld_subtotal)-roundNumber(ld_descubonirebajas)+roundNumber(ld_fletes);
				 ld_monexento=roundNumber(ld_subtotal)+roundNumber(ld_totpar);
				 ls_totp=uf_convertir(roundNumber(ld_totpar));
		         eval("f.txttotpro"+li_i+".value='"+ls_totp+"'");
			     //ld_monto=roundNumber(ld_subtotal)+roundNumber(ld_iva);
				 				   
				  if(eval("f.txtporcar"+li_i+".value")=="12,00")
		           {
		            //alert("pase");
                                 
			         ld_iva=roundNumber(ld_iva) + roundNumber(((roundNumber(ld_moncar)*roundNumber(ld_prepro))*roundNumber(ld_existe)));
			         ld_iva1=ld_iva1+roundNumber(parseFloat(uf_convertir_monto(eval("f.txttotpro"+li_i+".value"))));
			         ld_monto=roundNumber(ld_subtotal)+roundNumber(ld_iva)+roundNumber(ld_ivas);
		           }		  
      
		            if(eval("f.txtporcar"+li_i+".value")=="8,00")
		           {
		            //alert("pase");
                                ld_ivas=roundNumber(ld_ivas) + roundNumber(((roundNumber(ld_moncar)*roundNumber(ld_prepro))*roundNumber(ld_canpro)));
			        //ld_ivas=roundNumber(ld_ivas) + ((roundNumber(ld_moncar)*roundNumber(ld_prepro))*roundNumber(ld_existe));
			        ld_iva2=ld_iva2+parseFloat(uf_convertir_monto(eval("f.txttotpro"+li_i+".value"))); 
		            ld_monto=roundNumber(ld_subtotal)+roundNumber(ld_iva)+roundNumber(ld_ivas);
		           }
				    if(eval("f.txtporcar"+li_i+".value")=="0,00")
		           {
		           	 ld_exento=ld_exento+parseFloat(uf_convertir_monto(eval("f.txttotpro"+li_i+".value"))); 		           
		            ld_monto=roundNumber(ld_subtotal)+roundNumber(ld_iva)+roundNumber(ld_ivas);
		           }
				 
				 }
				}
				else
				{
			     ld_moncar=roundNumber(ld_moncar/100);
		         ld_totpar=roundNumber(ld_prepro)*roundNumber(ld_canpro);
			     //ld_iva=roundNumber(ld_iva) + ((roundNumber(ld_moncar)*roundNumber(ld_prepro))*roundNumber(ld_canpro));
			     ld_subtotal=roundNumber(ld_subtotal)+roundNumber(ld_totpar);
			     ld_subtotal2=roundNumber(ld_subtotal)-roundNumber(ld_descubonirebajas)+roundNumber(ld_fletes);
				 ld_monexento=roundNumber(ld_subtotal)+roundNumber(ld_totpar);
				 ls_totp=uf_convertir(roundNumber(ld_totpar));
		         eval("f.txttotpro"+li_i+".value='"+ls_totp+"'");
			     //ld_monto=roundNumber(ld_subtotal)+roundNumber(ld_iva);
			   			  
				  
				   if(eval("f.txtporcar"+li_i+".value")=="12,00")
		           {
		            //alert("pase");			 roundNumber(((roundNumber(ld_moncar)*roundNumber(ld_prepro))*roundNumber(ld_existe)));	
			        ld_iva=roundNumber(ld_iva) + roundNumber(((roundNumber(ld_moncar)*roundNumber(ld_prepro))*roundNumber(ld_canpro)));	
												//roundNumber(roundNumber(((roundNumber(ld_moncar)*roundNumber(ld_prepro))))*roundNumber(ld_canpro));
					ld_iva1=ld_iva1+parseFloat(uf_convertir_monto(eval("f.txttotpro"+li_i+".value"))); 					
		            ld_monto=roundNumber(ld_subtotal)+roundNumber(ld_iva)+roundNumber(ld_ivas);

		           }
		           
		            if(eval("f.txtporcar"+li_i+".value")=="8,00")
		           {
		            //alert("pase");
                                ld_ivas=roundNumber(ld_ivas) + roundNumber(((roundNumber(ld_moncar)*roundNumber(ld_prepro))*roundNumber(ld_canpro)));	
			        //ld_ivas=roundNumber(ld_ivas) + roundNumber(roundNumber(((roundNumber(ld_moncar)*roundNumber(ld_prepro))))*roundNumber(ld_canpro));
			        ld_iva2=ld_iva2+parseFloat(uf_convertir_monto(eval("f.txttotpro"+li_i+".value")));
                                
		            ld_monto=roundNumber(ld_subtotal)+roundNumber(ld_iva)+roundNumber(ld_ivas);
                            //alert("pase 2: "+ld_ivas+" "+ld_monto);
		           }
		           
		            if(eval("f.txtporcar"+li_i+".value")=="0,00")
		           {
		            ld_exento=ld_exento+parseFloat(uf_convertir_monto(eval("f.txttotpro"+li_i+".value")));	        
		            ld_monto=roundNumber(ld_subtotal)+roundNumber(ld_iva)+roundNumber(ld_ivas);
		           }
			   
			   }

		 }
	}
	f.txtsubtot.value=uf_convertir(roundNumber(ld_subtotal));
	f.txtsubtot2.value=f.txtsubtot.value;
	f.txtmonexento.value=uf_convertir(roundNumber(ld_exento));
	f.txtmoniva1.value=uf_convertir(roundNumber(ld_iva1));
	f.txtmoniva2.value=uf_convertir(roundNumber(ld_iva2));
	f.txtmonbaseimponible.value=uf_convertir(roundNumber(ld_iva1+ld_iva2));
	//f.txtmoniva2.value=uf_convertir(roundNumber(ld_iva2));
	f.txtmoniva.value=uf_convertir(roundNumber(ld_iva));
	f.txtmonivas.value=uf_convertir(roundNumber(ld_ivas));
    f.txtmonto.value=uf_convertir(roundNumber(ld_monto));
	f.txtresta.value=uf_convertir(roundNumber(ld_monto));
	ue_subtotalfpago();
}

function validar_existencia(){
	lb_valido="SI";
	f=document.form1;
	li_filasconcepto=f.filasconcepto.value;
	for(li_i=1;li_i<=li_filasconcepto-1;li_i++)
	{
	   ld_canpro=parseFloat(uf_convertir_monto(eval("f.txtcanpro"+li_i+".value")));
	   ld_existe=parseFloat(eval("f.txtexiste"+li_i+".value"));
	   if((ld_canpro>ld_existe)||(ld_canpro==0)){
	     return lb_valido="NO";
	   }

	}

	return lb_valido;
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
	ue_subtotal()

	return false;
}
function ue_anular()
{
	f=document.form1;
        
        
	ls_anular=f.anular.value;
	if(ls_anular==1){
            
        
	
                
                if (f.txtnumfac.value=="")
		  {
			alert("Debe seleccionar la Factura a anular!!");
		  }
		  else
		  {
		    si=confirm("Esta seguro que desea anular la factura?");
			 if(si)
			 {
				Xpos=((screen.width/2)-(500/2)); 
                                Ypos=((screen.height/2)-(400/2));
                                
                                //document.form1.txtcodtienda.value="<?php echo $_SESSION["ls_codtienda"];?>";
                                //f.txtcodtienda.value="<?php echo $_SESSION["ls_codtienda"];?>";
                                window.open("sigesp_cat_conceptoanula_factura.php?factura="+document.getElementById('txtnumfac').value+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=400,height=180,left="+Xpos+",top="+Ypos+",location=no,resizable=no");
                                
			 }
		  }
                 
	}else{
		alert("No tiene permiso para realizar esta operacion");
	}

}

function verificarAnulacion(srtObservacion){
    document.form1.txtobsanul.value=srtObservacion;
    f.action="sigesp_sfc_d_factura.php";
    
    f.operacion.value="ue_anular";
    suiche_submit=true;
    //document.form1.txtcodtienda.value="<?php echo $_SESSION["ls_codtienda"];?>";
    f.submit();
}
function ue_verificarexistencia()
{
	f=document.form1;
	f.operacion.value="ue_verificarexistencia";
	suiche_submit=true;
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
