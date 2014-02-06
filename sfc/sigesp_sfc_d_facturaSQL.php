<?php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "location.href='sigesp_conexion.php'";
	print "</script>";		
}
$la_datemp=$_SESSION["la_empresa"];
$ls_codtie=$_SESSION["ls_codtienda"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Facturar</title>
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
.Estilo3 {color: #6699CC}
-->
</style><meta http-equiv="Content-Type" content="text/html; charset="></head>
<body link="#006699" vlink="#006699" alink="#006699" onLoad="ue_subtotalfpago();" onUnload="ue_actualizarestacot();" >
<?Php
//
/****************************************************************/
/* FECHA: 25/03/2006                      						*/ 
/* AUTOR: GERARDO CORDERO 										*/
/*ACTUALIZADO POR: ING. ZULHEYMAR RODRÍGUEZ 	FECHA:28/09/07  */         
/****************************************************************/
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/grid_param.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/evaluate_formula.php");
require_once("../shared/class_folder/class_funciones_db.php");
require_once ("../shared/class_folder/sigesp_include.php");	
require_once("../shared/class_folder/evaluate_formula.php");
require_once("class_folder/sigesp_sob_c_funciones_sob.php");
require_once("class_folder/sigesp_sfc_c_cotizacion.php");
require_once("class_folder/sigesp_sfc_c_factura.php");
require_once("class_folder/sigesp_sfc_c_instpago.php");
require_once("class_folder/sigesp_sfc_c_instpagocob.php");
require_once("class_folder/sigesp_sfc_c_cobranza.php");
require_once("class_folder/sigesp_sfc_c_nota.php");
require_once("class_folder/sigesp_sfc_c_secuencia.php");

$io_evalform=new evaluate_formula();
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
$io_datastore2=new class_datastore();
$io_function=new class_funciones();
$io_cotizacion=new  sigesp_sfc_c_cotizacion();
$io_factura=new  sigesp_sfc_c_factura();
$io_instpago=new sigesp_sfc_c_instrpago();
$io_instpagocob=new sigesp_sfc_c_instpagocob();
$io_cobranza=new  sigesp_sfc_c_cobranza();
$io_nota=new sigesp_sfc_c_nota();
/************CLASE PARA LA INTEGRACION CON INVENTARIO********************/

require_once("class_folder/sigesp_sim_c_articuloxalmacen.php");
$io_art=  new sigesp_sim_c_articuloxalmacen();
require_once("class_folder/sigesp_sim_c_despacho.php");
$io_siv=  new sigesp_sim_c_despacho();
require_once("class_folder/sigesp_sim_c_movimientoinventario.php");
$io_mov=    new sigesp_sim_c_movimientoinventario();
require_once("class_folder/sigesp_sim_c_recepcion.php");
$io_sivRe=  new sigesp_sim_c_recepcion();
$ls_codemp=$la_datemp["codemp"];
$ls_codtie=$_SESSION["ls_codtienda"];
/**************   GRID   DETALLES   FACTURA   *******************/
$ls_tituloconcepto="Detalle Artículos";
$li_anchoconcepto=600;
$ls_nametable="grid2";
$la_columconcepto[1]="Código";
$la_columconcepto[2]="Descripción";
$la_columconcepto[3]="Precio Unitario";
$la_columconcepto[4]="IVA";
$la_columconcepto[5]="Cantidad";
$la_columconcepto[6]="Total";
$la_columconcepto[7]="Edición";
/*******  GRID    DETALLES     FORMA    DE    PAGO  ********************/ 
$ls_titulofpago="Instrumento de pago";
$li_anchofpago=600;
$ls_nametablefpago="grid3";
$la_columfpago[1]="Código";
$la_columfpago[2]="Descripción";
$la_columfpago[3]="Número";
$la_columfpago[4]="Banco";
$la_columfpago[5]="Monto";
$la_columfpago[6]="Edición";
print "<script language=JavaScript>suiche_submit=false;</script>";
/*******************************************************************************************/
/****************    SUBMIT  ***************************************************************/
/*******************************************************************************************/
if(array_key_exists("operacion",$_POST))
{   
	$ls_operacion=$_POST["operacion"];	
	//Datos cabecera factura------------------	
	$ls_cedcli=$_POST["txtcedcli"];
	$ls_codcli=$_POST["txtcodcli"];
	$ls_nomcli=$_POST["txtnomcli"];
	$ls_numfac=$_POST["txtnumfac"];
	$ls_numcot=$_POST["txtnumcot"];
	$ls_fecemi=$_POST["txtfecemi"];
	$ls_conpag=$_POST["combo_conpag"];
	$ls_codusu=$_SESSION["la_logusr"];
	//Datos estatus----------------------------
	$ls_estcot=$_POST["txtestcot"];
	$ls_estfaccon=$_POST["txtestfaccon"];
	//Datos Montos-----------------------------
	$ls_subtot=$_POST["txtsubtot"];
	$ls_moniva=$_POST["txtmoniva"];
	$ls_monto=$_POST["txtmonto"];
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
		
	$ls_codalm=$_POST["hidcodalm"];
	$ls_estcot_ant=$_POST["txtestcot_ant"];
	$ls_numcot_ant=$_POST["txtnumcot_ant"];
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
}
else
{
	//print "NO SUMIT/";
    $ls_operacion="";			
	$ls_numfac="";
	$ls_fecemi=date('d/m/Y');
	/*$ls_estfac="";*/
	$ls_numcot="0000000000000000000000000";
	$ls_cedcli="";
	$ls_codcli="";
	$ls_nomcli="";
	$ls_subtot="0,00";
	$ls_moniva="0,00";
	$ls_monto="0,00";
	$ls_conpag=1;	
	$ls_estcot="";
	$ls_estfaccon="";	
	$ls_codalm="";
	$ls_totalforpag="0,00";	
	$ls_especial="F";	
	$ls_estcot_ant="";
	$ls_numcot_ant="";	
	$ls_resta="0,00";
	$ls_hidbanexi="";						
	//----------------------------------------------------Grid Conceptos----------------------------------------------------//		
	$li_filasconcepto=1;
    $la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodalm".$li_filasconcepto." type=hidden id=txtcodalm".$li_filasconcepto.">";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][3]="<input name=txtprepro".$li_filasconcepto." type=text id=txtprepro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center>";
	$la_objectconcepto[$li_filasconcepto][6]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";	
	$la_objectconcepto[$li_filasconcepto][7]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style=text-align:center size=5 readonly>"; 
	//----------------------------------------------------Grid Forma de Pago----------------------------------------------------//		
	$li_filasfpago=1;	
	$la_objectfpago[$li_filasfpago][1]="<input name=txtcodforpag".$li_filasfpago." type=text id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_filasfpago." type=hidden id=txtcodent".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][2]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][3]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=20 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][4]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][5]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
$la_objectfpago[$li_filasfpago][6]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
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
		$ls_existe=$_POST["txtexiste".$li_i];

		$la_objectconcepto[$li_i][1]="<input name=txtcodpro".$li_i." type=text id=txtcodpro".$li_i." value='".$ls_codpro."' class=sin-borde size=15 style= text-align:center readonly><input name=txtcodalm".$li_i." type=hidden id=txtcodalm".$li_i." value='".$ls_codalm."'><input name=txtexiste".$li_i." type=hidden id=txtexiste".$li_i." value='".$ls_existe."'>";
		$la_objectconcepto[$li_i][2]="<input name=txtdenpro".$li_i." type=text id=txtdenpro".$li_i." value='".$ls_denpro."' class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectconcepto[$li_i][3]="<input name=txtprepro".$li_i." type=text id=txtprepro".$li_i." value='".$ls_prepro."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectconcepto[$li_i][4]="<input name=txtporcar".$li_i." type=text id=txtporcar".$li_i." value='".$ls_porcar."' class=sin-borde size=15 style= text-align:center readonly><input name=txtmoncar".$li_i." type=hidden id=txtmoncar".$li_i." value='".$ls_moncar."' class=sin-borde size=15 style= text-align:center readonly>";
		
		if ($ls_estfaccon=="" && $ls_operacion!="ue_guardar")//Si la factura es nueva se habilita la opcion de eliminar en edicion
		 {
		$la_objectconcepto[$li_i][5]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event))>";
		 }
		else
		 {
		 		$la_objectconcepto[$li_i][5]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=15 style= text-align:center readonly>";	 
		 }
		
		$la_objectconcepto[$li_i][6]="<input name=txttotpro".$li_i." type=text id=txttotpro".$li_i." value='".$ls_totpro."' class=sin-borde size=15 style= text-align:center readonly>";
		
		if ($ls_estfaccon=="" && $ls_operacion!="ue_guardar")//Si la factura es nueva se habilita la opcion de eliminar en edicion
		 {
		$la_objectconcepto[$li_i][7]="<a href=javascript:ue_removerconcepto(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		  }
		else
		  {
		$la_objectconcepto[$li_i][7]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center readonly>";
		  }
	  
		
		}
		
		$la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodalm".$li_filasconcepto." type=hidden id=txtcodalm".$li_filasconcepto."><input name=txtexiste".$li_filasconcepto." type=hidden id=txtexiste".$li_filasconcepto.">";
		$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][3]="<input name=txtprepro".$li_filasconcepto." type=text id=txtprepro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][4]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";		
		$la_objectconcepto[$li_filasconcepto][5]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center>";
		$la_objectconcepto[$li_filasconcepto][6]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][7]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
}

if ($ls_operacion!="ue_cargarfpago" && $ls_operacion!="ue_removerfpago")
{
		
		for($li_i=1;$li_i<$li_filasfpago;$li_i++)
		{
		 $ls_codforpag=$_POST["txtcodforpag".$li_i];
		 $ls_denforpag=$_POST["txtdenforpag".$li_i];
		 $ls_numinst=$_POST["txtnuminst".$li_i];
		 $ls_nombanco=$_POST["txtnombanco".$li_i];
		 $ls_codban=$_POST["txtcodban".$li_i];
		 $ls_montoforpag=$_POST["txtmontoforpag".$li_i];
		 $ls_metforpago=$_POST["txtmetforpag".$li_i];
		 $ls_codent=$_POST["txtcodent".$li_i];

$la_objectfpago[$li_i][1]="<input name=txtcodforpag".$li_i." type=text id=txtcodforpag".$li_i." value='".$ls_codforpag."' class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_i." type=hidden id=txtmetforpag".$li_i." value='".$ls_metforpago."' class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_i." type=hidden id=txtcodent".$li_i." value='".$ls_codent."' class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_i][2]="<input name=txtdenforpag".$li_i." type=text id=txtdenforpag".$li_i." value='".$ls_denforpag."' class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectfpago[$li_i][3]="<input name=txtnuminst".$li_i." type=text id=txtnuminst".$li_i." value='".$ls_numinst."' class=sin-borde size=20 style= text-align:center readonly>";
	$la_objectfpago[$li_i][4]="<input name=txtnombanco".$li_i." type=text id=txtnombanco".$li_i." value='".$ls_nombanco."' class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_i." type=hidden id=txtcodban".$li_i." value='".$ls_codban."' class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_i][5]="<input name=txtmontoforpag".$li_i." type=text id=txtmontoforpag".$li_i." value='".$ls_montoforpag."' class=sin-borde size=15 style= text-align:center readonly>";
	
	//print "ESTFACCON".$ls_estfaccon;
	if ($ls_estfaccon=="" && $ls_operacion!="ue_guardar")// Si la factura es nueva se habilita la opcion de eliminar en edicion
		 {
		$la_objectfpago[$li_i][6]="<a href=javascript:ue_removerfpago(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		  }
		else
		  {
		$la_objectfpago[$li_i][6]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center readonly>";
	     }		
		}		
$la_objectfpago[$li_filasfpago][1]="<input name=txtcodforpag".$li_filasfpago." type=text id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_filasfpago." type=hidden id=txtcodent".$li_filasfpago."class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][2]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][3]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=20 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][4]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][5]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
$la_objectfpago[$li_filasfpago][6]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";		
}


/***********************************************************************************/
/************ PREPARANDO INSERCION DE "NUEVA FACTURA *******************************/
/***********************************************************************************/
if($ls_operacion=="ue_nuevo")
{	 
	 if ($ls_estcot_ant=='P')	
	   {
	    $ls_estcot_ant='E';
   	    $lb_valido=$io_cotizacion->uf_update_cotizacionstatus($ls_numcot_ant,$ls_estcot_ant);
	   } 
	 
	 $li_filasfpago=1;	
	
    $la_empresa=$_SESSION["la_empresa"];
	$ls_codcaj=$_SESSION["ls_codcaj"];
	$ls_prefijo=$_SESSION["ls_prefac"];
	$ls_serie=$_SESSION["ls_serfac"];
	$io_secuencia->uf_ver_secuencia($ls_codcaj."fac",&$ls_secuencia);
	$ls_secuencia=$io_function->uf_cerosizquierda($ls_secuencia,16);
	$ls_numfac=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);	
	$ls_fecemi=date('d/m/Y');
	$ls_numcot="0000000000000000000000000";
	$ls_codcli="";
	$ls_nomcli="";
	$ls_subtot="0,00";
	$ls_moniva="0,00";
	$ls_monto="0,00";
	$ls_conpag=1;	
	$ls_especial="F";	
	$ls_estcot="";
	$ls_estfaccon="";
	//----------------------------------------------------Grid Conceptos----------------------------------------------------//		
	$li_filasconcepto=1;
    $la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodalm".$li_filasconcepto." type=hidden id=txtcodalm".$li_filasconcepto."><input name=txtexiste".$li_filasconcepto." type=hidden id=txtexiste".$li_filasconcepto.">";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][3]="<input name=txtprepro".$li_filasconcepto." type=text id=txtprepro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center>";
	$la_objectconcepto[$li_filasconcepto][6]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][7]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style=text-align:center size=5 readonly>";
	//----------------------------------------------------Grid Forma de Pago----------------------------------------------------//	
	$li_filasfpago=1;
	
	$la_objectfpago[$li_filasfpago][1]="<input name=txtcodforpag".$li_filasfpago." type=text id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_filasfpago." type=hidden id=txtcodent".$li_filasfpago."class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][2]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][3]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=20 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][4]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][5]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
$la_objectfpago[$li_filasfpago][6]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";	


}
/***************************************************************************/
/********************** CARGAR UN PRODUCTO EN GRID *************************/
/***************************************************************************/
elseif($ls_operacion=="ue_cargarconcepto")
{
	//print "UE_CARGARCONCEPTO/";
	$li_filasconcepto=$_POST["filasconcepto"];
	$li_filasconcepto++;
	
	for($li_i=1;$li_i<$li_filasconcepto;$li_i++)
	{
		$ls_codpro=$_POST["txtcodpro".$li_i];
		$ls_denpro=$_POST["txtdenpro".$li_i];
		$ls_prepro=$_POST["txtprepro".$li_i];
		$ls_canpro=$_POST["txtcanpro".$li_i];
		$ls_totpro=$_POST["txttotpro".$li_i];
		$ls_porcar=$_POST["txtporcar".$li_i];
		$ls_moncar=$_POST["txtmoncar".$li_i];
		$ls_codalm=$_POST["txtcodalm".$li_i];
		$ls_existe=$_POST["txtexiste".$li_i];

		$la_objectconcepto[$li_i][1]="<input name=txtcodpro".$li_i." type=text id=txtcodpro".$li_i." value='".$ls_codpro."' class=sin-borde size=15 style= text-align:center readonly><input name=txtcodalm".$li_i." type=hidden id=txtcodalm".$li_i." value='".$ls_codalm."'><input name=txtexiste".$li_i." type=hidden id=txtexiste".$li_i." value='".$ls_existe."'>";
		$la_objectconcepto[$li_i][2]="<input name=txtdenpro".$li_i." type=text id=txtdenpro".$li_i." value='".$ls_denpro."' class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectconcepto[$li_i][3]="<input name=txtprepro".$li_i." type=text id=txtprepro".$li_i." value='".$ls_prepro."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectconcepto[$li_i][4]="<input name=txtporcar".$li_i." type=text id=txtporcar".$li_i." value='".$ls_porcar."' class=sin-borde size=15 style= text-align:center readonly><input name=txtmoncar".$li_i." type=hidden id=txtmoncar".$li_i." value='".$ls_moncar."' class=sin-borde size=15 style= text-align:center readonly>";
		if ($ls_estfaccon=="" && $ls_operacion!="ue_guardar")//Si la factura es nueva se habilita la opcion de eliminar en edicion
		 {
		$la_objectconcepto[$li_i][5]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event))>";
		 }
		else
		 {
		 		$la_objectconcepto[$li_i][5]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=15 style= text-align:center readonly>";	 
		 }		
		$la_objectconcepto[$li_i][6]="<input name=txttotpro".$li_i." type=text id=txttotpro".$li_i." value='".$ls_totpro."' class=sin-borde size=15 style= text-align:center readonly>";
		if ($ls_estfaccon=="")// Si la factura es nueva se habilita la opcion de eliminar en edicion
		 {
		$la_objectconcepto[$li_i][7]="<a href=javascript:ue_removerconcepto(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		  }
		else
		  {
		$la_objectconcepto[$li_i][7]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center readonly>";
		  }
		
	}	
	$la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodalm".$li_filasconcepto." type=hidden id=txtcodalm".$li_filasconcepto."><input name=txtexiste".$li_filasconcepto." type=hidden id=txtexiste".$li_filasconcepto.">";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][3]="<input name=txtprepro".$li_filasconcepto." type=text id=txtprepro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center>";
	$la_objectconcepto[$li_filasconcepto][6]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][7]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
	
}
/****************************************************************************/
/********* CARGAR FORMA DE PAGO EN GRID *************************************/
/****************************************************************************/
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
		 $ls_codban=$_POST["txtcodban".$li_i];
		 $ls_metforpago=$_POST["txtmetforpag".$li_i];
		 $ls_montoforpag=$_POST["txtmontoforpag".$li_i];
		 $ls_codent=$_POST["txtcodent".$li_i];
		$la_objectfpago[$li_i][1]="<input name=txtcodforpag".$li_i." type=text id=txtcodforpag".$li_i." value='".$ls_codforpag."' class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_i." type=hidden id=txtmetforpag".$li_i." value='".$ls_metforpago."' class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_i." type=hidden id=txtcodent".$li_i." value='".$ls_codent."' class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_i][2]="<input name=txtdenforpag".$li_i." type=text id=txtdenforpag".$li_i." value='".$ls_denforpag."' class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectfpago[$li_i][3]="<input name=txtnuminst".$li_i." type=text id=txtnuminst".$li_i." value='".$ls_numinst."' class=sin-borde size=20 style= text-align:center readonly>";
	$la_objectfpago[$li_i][4]="<input name=txtnombanco".$li_i." type=text id=txtnombanco".$li_i." value='".$ls_nombanco."' class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_i." type=hidden id=txtcodban".$li_i." value='".$ls_codban."' class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_i][5]="<input name=txtmontoforpag".$li_i." type=text  id=txtmontoforpag".$li_i." value='".$ls_montoforpag."' class=sin-borde size=15 style= text-align:center readonly>";
	if ($ls_estfaccon=="")// Si la factura es nueva se habilita la opcion de eliminar en edicion
		 {
$la_objectfpago[$li_i][6]="<a href=javascript:ue_removerfpago(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";	
		  }
		else
		  {
$la_objectfpago[$li_i][6]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center readonly>";	
		  }
	}	
	
   $la_objectfpago[$li_filasfpago][1]="<input name=txtcodforpag".$li_filasfpago." type=text id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_filasfpago." type=hidden id=txtcodent".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][2]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][3]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][4]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][5]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
$la_objectfpago[$li_filasfpago][6]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";	

}
/****************************************************************************/
/************ CARGAR CONCEPTOS DESDE CATALOGO DE COTIZACION *************/
/****************************************************************************/
elseif ($ls_operacion=="ue_cargarconcepto_cotizacion")
{
  if ($ls_estcot_ant=='P')	
	   {
	    $ls_estcot_ant='E';
   	    $lb_valido=$io_cotizacion->uf_update_cotizacionstatus($ls_numcot_ant,$ls_estcot_ant);
	   } 

	
/******************************************  LIMPIAR GRID  ******************************************************************/
$li_filasconcepto=1;
     $la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodalm".$li_filasconcepto." type=hidden id=txtcodalm".$li_filasconcepto."><input name=txtexiste".$li_filasconcepto." type=hidden id=txtexiste".$li_filasconcepto.">";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][3]="<input name=txtprepro".$li_filasconcepto." type=text id=txtprepro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center>";
	$la_objectconcepto[$li_filasconcepto][6]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][7]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style=text-align:center size=5 readonly>";
	
	$li_filasfpago=1;
	
	$la_objectfpago[$li_filasfpago][1]="<input name=txtcodforpag".$li_filasfpago." type=text id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_filasfpago." type=hidden id=txtcodent".$li_filasfpago."class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][2]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][3]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=20 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][4]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][5]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
$la_objectfpago[$li_filasfpago][6]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";	


/****************************************************************************************************************************/						
$ls_cadena="SELECT sfc_producto.denpro,sfc_producto.moncar,sfc_detcotizacion.*,sfc_detcotizacion.precot*sfc_detcotizacion.cancot as totpro FROM sfc_producto,sfc_detcotizacion WHERE  sfc_detcotizacion.codpro=sfc_producto.codpro AND sfc_detcotizacion.numcot='".$ls_numcot."' and sfc_producto.codemp=sfc_detcotizacion.codemp ORDER BY sfc_detcotizacion.codpro ASC;";
			
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
					$ls_monacu=0;
					$ls_moncaracu=0;
						
					for($li_i=1;$li_i<=$totrow;$li_i++)
					{

						$ls_codpro=$io_datastore->getValue("codpro",$li_i);
		                $ls_denpro=$io_datastore->getValue("denpro",$li_i);
						$ls_preuni=$io_datastore->getValue("precot",$li_i);
					    $ls_canpro=$io_datastore->getValue("cancot",$li_i);
						$ls_totpro=$io_datastore->getValue("totpro",$li_i);
						$ls_porcar=$io_datastore->getValue("impcot",$li_i);
						$ls_moncar=$io_datastore->getValue("moncar",$li_i);
                        $ls_moncar=((($ls_porcar/100)*$ls_preuni)*$ls_canpro);
						$ls_codalm=$io_datastore->getValue("codalm",$li_i);
						$ls_existe=$io_factura->uf_select_existencia($ls_codpro,$ls_codalm);
						if($ls_canpro>$ls_existe){
						  $ls_hidbanexi="true";
						?>
						 <script language="javascript">
						   alert("La cantidad cotizada supera la existencia!!!");
						 </script>						
						<?php
						}
						
						$ls_monacu=$ls_monacu+$ls_totpro;
						$ls_moncaracu=$ls_moncaracu+$ls_moncar;
						$ls_prepro=number_format($ls_preuni,2, ',', '.');
						$ls_canpro=number_format($ls_canpro,2, ',', '.');
						$ls_totpro=number_format($ls_totpro,2, ',', '.');
						$ls_porcar=number_format($ls_porcar,2, ',', '.');
						
						
		$la_objectconcepto[$li_i][1]="<input name=txtcodpro".$li_i." type=text id=txtcodpro".$li_i." value='".$ls_codpro."' class=sin-borde size=15 style= text-align:center readonly><input name=txtcodalm".$li_i." type=hidden id=txtcodalm".$li_i." value='".$ls_codalm."'><input name=txtexiste".$li_i." type=hidden id=txtexiste".$li_i." value='".$ls_existe."'>";
		$la_objectconcepto[$li_i][2]="<input name=txtdenpro".$li_i." type=text id=txtdenpro".$li_i." value='".$ls_denpro."' class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectconcepto[$li_i][3]="<input name=txtprepro".$li_i." type=text id=txtprepro".$li_i." value='".$ls_prepro."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectconcepto[$li_i][4]="<input name=txtporcar".$li_i." type=text id=txtporcar".$li_i." value='".$ls_porcar."' class=sin-borde size=15 style= text-align:center readonly><input name=txtmoncar".$li_i." type=hidden id=txtmoncar".$li_i." value='".$ls_moncar."' class=sin-borde size=15 style= text-align:center readonly>";
if ($ls_estfaccon=="" && $ls_operacion!="ue_guardar")//Si la factura es nueva se habilita la opcion de eliminar en edicion
		 {
		$la_objectconcepto[$li_i][5]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event))>";
		 }
		else
		 {
		 		$la_objectconcepto[$li_i][5]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=15 style= text-align:center readonly>";	 
		 }		$la_objectconcepto[$li_i][6]="<input name=txttotpro".$li_i." type=text id=txttotpro".$li_i." value='".$ls_totpro."' class=sin-borde size=15 style= text-align:center readonly>";
		if ($ls_estfaccon=="")// Si la factura es nueva se habilita la opcion de eliminar en edicion
		 {
$la_objectconcepto[$li_i][7]="<a href=javascript:ue_removerconcepto(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		  }
		else
		  {
$la_objectconcepto[$li_i][7]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center readonly>";		  }
		   }
			
		}// for			
		$ls_subtot=number_format($ls_monacu,2, ',', '.');
		$ls_moniva=number_format($ls_moncaracu,2, ',', '.');
		$ls_monto=number_format($ls_monacu+$ls_moncaracu,2, ',', '.');
		$li_filasconcepto=$li_i;		
				              
	$la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodalm".$li_filasconcepto." type=hidden id=txtcodalm".$li_filasconcepto."><input name=txtexiste".$li_filasconcepto." type=hidden id=txtexiste".$li_filasconcepto.">";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][3]="<input name=txtprepro".$li_filasconcepto." type=text id=txtprepro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center>";
	$la_objectconcepto[$li_filasconcepto][6]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][7]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
     
	 }	
  
        

 	
/**************************************** ACTUALIZAR COTIZACION A "EN PROCESO"  *************************************************/
		//print "actualizar cotizacion";  
	   if ($ls_numcot=='0000000000000000000000000') 
	     {
	       $ls_estcot='E';
		 }
		else
		 {
		   $ls_estcot='P';
		 }  
	   	 
 	   $lb_valido=$io_cotizacion->uf_update_cotizacionstatus($ls_numcot,$ls_estcot);
/*--------------------------------------------------------------------------------------------------------------------*/	

}
/****************************************************************************************************/
/************* CARGAR PRODUCTO EN GRID DESDE CATALOGO DE FACTURA   **********************************/
/****************************************************************************************************/
elseif ($ls_operacion=="ue_cargarconcepto_factura")// 77
{
/*********************   cargar forma de pago - instrumento de pago  ******************************************/
/********************* LIMPIA EL GRID ***********************/
$li_filasfpago=1;
    $la_objectfpago[$li_filasfpago][1]="<input name=txtcodforpag".$li_filasfpago." type=text id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_filasfpago." type=hidden id=txtcodent".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][2]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][3]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=20 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][4]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][5]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
$la_objectfpago[$li_filasfpago][6]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";	

$ls_cadena="SELECT scb_banco.nomban,scb_banco.codban,sfc_formapago.denforpag,sfc_formapago.metforpag,sfc_instpago.numinst, sfc_instpago.codforpag,sfc_instpago.monto,sfc_instpago.numfac,sfc_instpago.id_entidad FROM scb_banco,sfc_formapago,sfc_instpago WHERE sfc_instpago.numfac='".$ls_numfac."' AND scb_banco.codban=sfc_instpago.codban AND sfc_instpago.codforpag=sfc_formapago.codforpag;";
//print $ls_cadena;
			$arr_formapago=$io_sql->select($ls_cadena);
			 
			if($arr_formapago==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros de formas de pago");
			}
			else
			{						
				if($row=$io_sql->fetch_row($arr_formapago))

 				  {
					$la_formapago=$io_sql->obtener_datos($arr_formapago);
					$io_datastore->data=$la_formapago;
					$totrow=$io_datastore->getRowCount("numfac");
						
					for($li_j=1;$li_j<=$totrow;$li_j++)
					{
					  
						$ls_codforpag=$io_datastore->getValue("codforpag",$li_j);
		                $ls_denforpag=$io_datastore->getValue("denforpag",$li_j);
						$ls_numinst=$io_datastore->getValue("numinst",$li_j);
						$ls_nombanco=$io_datastore->getValue("nomban",$li_j);
						$ls_codban=$io_datastore->getValue("codban",$li_j);
						$ls_montoforpag=$io_datastore->getValue("monto",$li_j);
						$ls_montoforpag=number_format($ls_montoforpag,2, ',', '.');
						$ls_codent=$io_datastore->getValue("id_entidad",$li_j);
						$ls_metforpago=$io_datastore->getValue("metforpag",$li_j);
						
		$la_objectfpago[$li_j][1]="<input name=txtcodforpag".$li_j." type=text id=txtcodforpag".$li_j." value='".$ls_codforpag."' class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_j." type=hidden id=txtmetforpag".$li_j." value='".$ls_metforpago."' class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_j." type=hidden id=txtcodent".$li_j."value='".$ls_codent."'class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_j][2]="<input name=txtdenforpag".$li_j." type=text id=txtdenforpag".$li_j." value='".$ls_denforpag."' class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectfpago[$li_j][3]="<input name=txtnuminst".$li_j." type=text id=txtnuminst".$li_j." value='".$ls_numinst."' class=sin-borde size=20 style= text-align:center readonly>";
	$la_objectfpago[$li_j][4]="<input name=txtnombanco".$li_j." type=text id=txtnombanco".$li_j." value='".$ls_nombanco."' class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_j." type=hidden id=txtcodban".$li_j." value='".$ls_codban."' class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_j][5]="<input name=txtmontoforpag".$li_j." type=text id=txtmontoforpag".$li_j." value='".$ls_montoforpag."' class=sin-borde size=15 style= text-align:center readonly>";
			  
	
		       if ($ls_estfaccon=="")// Si la factura es nueva se habilita la opcion de eliminar en edicion
				 {
		$la_objectfpago[$li_j][6]="<a href=javascript:ue_removerfpago(".$li_j.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";			  
				  }
				else
				  {
		$la_objectfpago[$li_j][6]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center>";			
				   }  	
		   	 	 }//for
					
		 $li_filasfpago=$li_j;		
				              
	$la_objectfpago[$li_filasfpago][1]="<input name=txtcodforpag".$li_filasfpago." type=text id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_filasfpago." type=hidden id=txtcodent".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][2]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][3]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=20 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][4]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][5]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
$la_objectfpago[$li_filasfpago][6]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
             }	//2do if
        }//1er if
/**************************************************************************************************************************/
/*******   limpia el GRID    **************/

	$li_filasconcepto=1;
     $la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodalm".$li_filasconcepto." type=hidden id=txtcodalm".$li_filasconcepto."><input name=txtexiste".$li_filasconcepto." type=hidden id=txtexiste".$li_filasconcepto.">";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][3]="<input name=txtprepro".$li_filasconcepto." type=text id=txtprepro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center>";
	$la_objectconcepto[$li_filasconcepto][6]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][7]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";		

$ls_cadena="SELECT sfc_producto.moncar,sfc_producto.denpro,sfc_detfactura.* FROM  sfc_producto,sfc_detfactura WHERE sfc_detfactura.codpro=sfc_producto.codpro AND sfc_detfactura.numfac='".$ls_numfac."';";	        
			
			$arr_detfactura=$io_sql->select($ls_cadena);
			 
			if($arr_detfactura==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros de productos");
			}
			else
			{						
			  if($row=$io_sql->fetch_row($arr_detfactura))
 			   {
					$la_producto=$io_sql->obtener_datos($arr_detfactura);
					$io_datastore->data=$la_producto;
					$totrow=$io_datastore->getRowCount("codpro");
					$ls_subtotA=0;
					$ls_monivaA=0;
						
					for($li_i=1;$li_i<=$totrow;$li_i++)
					{
						$ls_codpro=$io_datastore->getValue("codpro",$li_i);
		                $ls_denpro=$io_datastore->getValue("denpro",$li_i);
						$ls_prepro=$io_datastore->getValue("prepro",$li_i);
						$ls_canpro=$io_datastore->getValue("canpro",$li_i);
						$ls_porcar=$io_datastore->getValue("porimp",$li_i);
						$ls_moncar=$io_datastore->getValue("moncar",$li_i);
						$ls_totpro=$ls_prepro*$ls_canpro;
						$ls_totcar=((($ls_porcar/100)*$ls_prepro)*$ls_canpro);
						$ls_subtotA=$ls_subtotA+$ls_totpro;
						$ls_monivaA=$ls_monivaA+$ls_totcar;
						$ls_prepro=number_format($ls_prepro,2, ',', '.');
						$ls_canpro=number_format($ls_canpro,2, ',', '.');
						$ls_moncar=number_format($ls_moncar,2, ',', '.');
						$ls_porcar=number_format($ls_porcar,2, ',', '.');
						$ls_totpro=number_format($ls_totpro,2, ',', '.');
						$ls_codalm=$io_datastore->getValue("codalm",$li_i);
						$ls_existe=$io_factura->uf_select_existencia($ls_codpro,$ls_codalm);
						
						
		                $la_objectconcepto[$li_i][1]="<input name=txtcodpro".$li_i." type=text id=txtcodpro".$li_i." value='".$ls_codpro."' class=sin-borde size=15 style= text-align:center readonly><input name=txtcodalm".$li_i." type=hidden id=txtcodalm".$li_i." value='".$ls_codalm."'><input name=txtexiste".$li_i." type=hidden id=txtexiste".$li_i." value='".$ls_existe."'>";
		                $la_objectconcepto[$li_i][2]="<input name=txtdenpro".$li_i." type=text id=txtdenpro".$li_i." value='".$ls_denpro."' class=sin-borde size=45 style= text-align:left readonly>";
		                $la_objectconcepto[$li_i][3]="<input name=txtprepro".$li_i." type=text id=txtprepro".$li_i." value='".$ls_prepro."' class=sin-borde size=15 style= text-align:center readonly>";
		                $la_objectconcepto[$li_i][4]="<input name=txtporcar".$li_i." type=text id=txtporcar".$li_i." value='".$ls_porcar."' class=sin-borde size=15 style= text-align:center readonly><input name=txtmoncar".$li_i." type=hidden id=txtmoncar".$li_i." value='".$ls_moncar."' class=sin-borde size=15 style= text-align:center readonly>";
                        if ($ls_estfaccon=="" && $ls_operacion!="ue_guardar")//Si es nueva, habilita edicion
		                 {
		                    $la_objectconcepto[$li_i][5]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event))>";
		                 }
		                 else
		                 {
		                    $la_objectconcepto[$li_i][5]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=15 style= text-align:center readonly>";	 
		                 }
		                $la_objectconcepto[$li_i][6]="<input name=txttotpro".$li_i." type=text id=txttotpro".$li_i." value='".$ls_totpro."' class=sin-borde size=15 style= text-align:center readonly>";
		 
		                if ($ls_estfaccon=="")//Si es nueva, habilita edicion
		                 {
		                    $la_objectconcepto[$li_i][7]="<a href=javascript:ue_removerconcepto(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
                         }
		                 else
		                 {
		                   $la_objectconcepto[$li_i][7]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center readonly>";
                         }	
 	                 }//fin del for
					$ls_subtot=number_format($ls_subtotA,2, ',', '.');
					$ls_moniva=number_format($ls_monivaA,2, ',', '.');
				    $li_filasconcepto=$li_i;		
				    $la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodalm".$li_filasconcepto." type=hidden id=txtcodalm".$li_filasconcepto."><input name=txtexiste".$li_filasconcepto." type=hidden id=txtexiste".$li_filasconcepto.">";
	                $la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
	                $la_objectconcepto[$li_filasconcepto][3]="<input name=txtprepro".$li_filasconcepto." type=text id=txtprepro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	                $la_objectconcepto[$li_filasconcepto][4]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	                $la_objectconcepto[$li_filasconcepto][5]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center>";
	                $la_objectconcepto[$li_filasconcepto][6]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	                $la_objectconcepto[$li_filasconcepto][7]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
              }	//2do if
         } //1er if
/*******************************************************************************************************/
	
}
/****************************************************************************************************/
/**************  ELIMINAR PRODUCTO EN GRID **********************************************************/
/****************************************************************************************************/
elseif($ls_operacion=="ue_removerconcepto")
{ 
	//print "UE_REMOVERCONCEPTO/";
  	$li_filasconcepto=$_POST["filasconcepto"];
	$li_filasconcepto=$li_filasconcepto - 1;
	
	$li_removerconcepto=$_POST["hidremoverconcepto"]; /*** hid(oculto)+removerconcepto*/
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
		$ls_existe=$_POST["txtexiste".$li_i];

		$la_objectconcepto[$li_temp][1]="<input name=txtcodpro".$li_temp." type=text id=txtcodpro".$li_temp." value='".$ls_codpro."' class=sin-borde size=15 style= text-align:center readonly><input name=txtcodalm".$li_i." type=hidden id=txtcodalm".$li_i." value='".$ls_codalm."'><input name=txtexiste".$li_i." type=hidden id=txtexiste".$li_i." value='".$ls_existe."'>";
		$la_objectconcepto[$li_temp][2]="<input name=txtdenpro".$li_temp." type=text id=txtdenpro".$li_temp." value='".$ls_denpro."' class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectconcepto[$li_temp][3]="<input name=txtprepro".$li_temp." type=text id=txtprepro".$li_temp." value='".$ls_prepro."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectconcepto[$li_temp][4]="<input name=txtporcar".$li_temp." type=text id=txtporcar".$li_temp." value='".$ls_porcar."' class=sin-borde size=15 style= text-align:center readonly><input name=txtmoncar".$li_temp." type=hidden id=txtmoncar".$li_temp." value='".$ls_moncar."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectconcepto[$li_temp][5]="<input name=txtcanpro".$li_temp." type=text id=txtcanpro".$li_temp." value='".$ls_canpro."' class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event))>";
		$la_objectconcepto[$li_temp][6]="<input name=txttotpro".$li_temp." type=text id=txttotpro".$li_temp." value='".$ls_totpro."' class=sin-borde size=15 style= text-align:center readonly>";
		if ($ls_estfaccon=="")// Si la factura es nueva se habilita la opcion de eliminar en edicion
		 {
		$la_objectconcepto[$li_temp][7]="<a href=javascript:ue_removerconcepto(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
          }
		else
		  {
		$la_objectconcepto[$li_temp][7]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center readonly>";
           }	

		}
	}
           $la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto."  class=sin-borde size=15 style= text-align:center readonly><input name=txtcodalm".$li_filasconcepto." type=hidden id=txtcodalm".$li_filasconcepto."><input name=txtexiste".$li_filasconcepto." type=hidden id=txtexiste".$li_filasconcepto.">";
		$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto."  class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][3]="<input name=txtprepro".$li_filasconcepto." type=text id=txtprepro".$li_filasconcepto."  class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][4]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][5]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center>";
		$la_objectconcepto[$li_filasconcepto][6]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto."  class=sin-borde size=15 style= text-align:center readonly>";
	        $la_objectconcepto[$li_filasconcepto][7]="<input name=txtvacio".$li_filasconcepto." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
			
}
/************************************************************************************************/
/********************  ELIMINAR FORMA DE PAGO EN GRID *******************************************/
/************************************************************************************************/
elseif($ls_operacion=="ue_removerfpago")
{
   //	print "UE_REMOVERFPAGO/";
  	$li_filasfpago=$_POST["txtfilasfpago"];
	$li_filasfpago=$li_filasfpago - 1;
	
	$li_removerfpago=$_POST["hidremoverfpago"]; /*** hid(oculto)+removerconcepto*/
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
		 $ls_codban=$_POST["txtcodban".$li_i];
		 $ls_codent=$_POST["txtcodent".$li_i];
		 $ls_montoforpag=$_POST["txtmontoforpag".$li_i];
		 $ls_metforpago=$_POST["txtmetforpag".$li_i];
			
  	$la_objectfpago[$li_temp][1]="<input name=txtcodforpag".$li_temp." type=text id=txtcodforpag".$li_temp." value='".$ls_codforpag."' class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_temp." type=hidden id=txtmetforpag".$li_temp." value='".$ls_metforpago."' class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_temp." type=hidden id=txtcodent".$li_temp." value='".$ls_codent."' class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_temp][2]="<input name=txtdenforpag".$li_temp." type=text id=txtdenforpag".$li_temp." value='".$ls_denforpag."' class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectfpago[$li_temp][3]="<input name=txtnuminst".$li_temp." type=text id=txtnuminst".$li_temp." value='".$ls_numinst."' class=sin-borde size=20 style= text-align:center readonly>";
	$la_objectfpago[$li_temp][4]="<input name=txtnombanco".$li_temp." type=text id=txtnombanco".$li_temp." value='".$ls_nombanco."' class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_temp." type=hidden id=txtcodban".$li_temp." value='".$ls_codban."' class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_temp][5]="<input name=txtmontoforpag".$li_temp."  type=text id=txtmontoforpag".$li_temp." value='".$ls_montoforpag."' class=sin-borde size=15 style= text-align:center readonly>";
			if ($ls_estfaccon=="")// Si la factura es nueva se habilita la opcion de eliminar en edicion
		 {
$la_objectfpago[$li_temp][6]="<a href=javascript:ue_removerfpago(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";			
          }
		else
		  {
$la_objectfpago[$li_temp][6]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center readonly>";			
           }	

		}
	}	
	$la_objectfpago[$li_filasfpago][1]="<input name=txtcodforpag".$li_filasfpago." type=text id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_filasfpago." type=hidden id=txtcodent".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][2]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectfpago[$li_filasfpago][3]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=20 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][4]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][5]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
$la_objectfpago[$li_filasfpago][6]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";		
?>
<script language="javascript">

</script>
<?
}
/***********************************************************************************************************/
/******************** INSERCION O MODIFICACION DE UNA FACTURA EN BD ****************************************/
/***********************************************************************************************************/
elseif($ls_operacion=="ue_guardar")
{
 
 $ls_sql="SELECT * FROM sfc_factura WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."';";
			  
 $rs_datauni=$io_sql->select($ls_sql);
 $la_cotizacion=$io_sql->obtener_datos($rs_datauni);
 $io_datastore->data=$la_cotizacion;
 $ls_estfaccon=$io_datastore->getValue("estfaccon",1);//ñ

   /**************   VERIFICA SI EL USUARIO ES PERSONAL DE LA TIENDA  ***************************************/	
    $ls_codtie=$_SESSION["ls_codtienda"];
	if($ls_codtie!="")
	{ 
	 $ls_codusu=$_SESSION["la_logusr"];
	 $lb_valido_logusr=true;
	}
    else
	{
	 $lb_valido_logusr=false;
	 $io_msg->message ("El usuario no es personal de la tienda no puede generar facturas !!!");	
	}
	/************************************************************************************************************/		

   	   $la_detalles["codpro"][1]="";
	   $la_detalles["prepro"][1]="";
	   $la_detalles["canpro"][1]="";
	   $la_detalles["porimp"][1]="";
	   $cantidad_pro=true;
    for ($li_i=1;$li_i<$li_filasconcepto;$li_i++)
     { 
	   
	   $la_detalles["codpro"][$li_i]=$_POST["txtcodpro".$li_i];
	   $la_detalles["prepro"][$li_i]=$_POST["txtprepro".$li_i];
	   $la_detalles["canpro"][$li_i]=$_POST["txtcanpro".$li_i];
	   $la_detalles["porimp"][$li_i]=$_POST["txtporcar".$li_i];
	   $la_detalles["codalm"][$li_i]=$_POST["txtcodalm".$li_i];
	   if ($la_detalles["canpro"][$li_i]=="") 
	    {
		  $io_msg->message ("Debe colocar la cantidad del producto a vender!!!"); 
		  $cantidad_pro=false;
		} 
	 }
/******************  GUARDAR EN TABLA "SFC_FACTURA"  *****************************************/
	if ($lb_valido_logusr==true && $cantidad_pro==true)
	 {	
	    if ($ls_conpag=='2') 
		  {
		   $ls_estfaccon='N'; // SI conpag=2(crédito) ó conpag=3(pago parcial) entonces ESTAFACCON=N: Factura no cancelada sin registro en cobranza.
		  } 
		 elseif ($ls_conpag=='3')
		  {
		    $ls_estfaccon='N';
		  }
		 else
		  {
		   $ls_estfaccon='C'; // SI conpag=1(contado) entonces ESTAFACCON=C (cancelada): Factura cancelada.
		  }
		  
/*--------------------------  ACTUALIZAR COTIZACION A "FACTURADA"---------------------------------------------*/
		if ($ls_numcot=='0000000000000000000000000') 
	     {
	        $ls_estcot='E';	 
		 }
		 else
		 {
		    $ls_estcot='F';
		 }
	
	   //print "ESTCOT:".$ls_estcot;
 	     $lb_valido=$io_cotizacion->uf_update_cotizacionstatus($ls_numcot,$ls_estcot);
/*------------------------------------------------------------------------------------------------------------*/		  
		 $ls_codusu=$_SESSION["la_logusr"];
		 $ls_codcaj=$_SESSION["ls_codcaj"];
	     $ls_prefijo=$_SESSION["ls_prefac"];
		 $ls_serie=$_SESSION["ls_serfac"];
	     $io_secuencia->uf_obtener_secuencia($ls_codcaj."fac",&$ls_secuencia);
	     $ls_numfac=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);
		  
		$io_msg->message ("La Factura sera guardada con el codigo: ".$ls_numfac); 
		
		$lb_valido1=$io_factura->uf_guardar_factura($ls_codcli,$ls_numfac,$ls_numcot,$ls_codusu,$ls_fecemi,$ls_conpag,$ls_monto,$ls_estfaccon,$ls_moniva,$ls_especial,$ls_resta);
		
		
		$lb_valido2=$io_factura->uf_update_detallesfacturas($ls_codcli,$ls_numfac,$la_detalles,$li_filasconcepto);
		if ($lb_valido2==true) 
		  $io_msg->message ("Registro Incluido"); 
		
/********************   GUARDAR FORMA DE PAGO FACTURA (instrumento de pago)  *********************************/		
	   $la_detapag["codforpag"][1]="";
	   $la_detapag["denforpag"][1]="";
	   $la_detapag["numinst"][1]="";
	   $la_detapag["nombanco"][1]="";
	   $la_detapag["codban"][1]="";
	   $la_detapag["monto"][1]="";
	   $la_detapag["codent"][1]="";
	   
    for ($li_i=1;$li_i<$li_filasfpago;$li_i++)
     { 
	   $la_detapag["codforpag"][$li_i]=$_POST["txtcodforpag".$li_i];
	   $la_detapag["denforpag"][$li_i]=$_POST["txtdenforpag".$li_i];
	   $la_detapag["numinst"][$li_i]=$_POST["txtnuminst".$li_i];
	   $la_detapag["nombanco"][$li_i]=$_POST["txtnombanco".$li_i];
	   $la_detapag["codban"][$li_i]=$_POST["txtcodban".$li_i];
	   $la_detapag["monto"][$li_i]=$_POST["txtmontoforpag".$li_i]; 
	   $la_detapag["codent"][$li_i]=$_POST["txtcodent".$li_i]; 
	 }	  
	  $lb_valido=$io_instpago->uf_update_detalles_instrumentopago($ls_codcli,$ls_numfac,$la_detapag,$li_filasfpago);
	

/*****************************************************/
/*******INTEGRACION CON INVENTARIO********************/
/*****************************************************/
$ls_obsdes="Despacho por concepto de venta bajo la Factura Nº ".$ls_numfac;
$ls_numdoc=substr($ls_numfac,10,strlen($ls_numfac));
$ld_fecemi=$io_function->uf_convertirdatetobd($ls_fecemi);
$lb_valido=$io_siv->uf_sim_insert_despacho($ls_codemp,$ls_numord,$ls_numdoc,"",$ld_fecemi,$ls_obsdes,$ls_codusu,"1","1",""/*,$la_seguridad*/);

if($lb_valido)
 {
	$lb_valido=$io_siv->io_mov->uf_sim_insert_movimiento($ls_nummov,$ld_fecemi,"Despacho",$ls_codusu/*,$la_seguridad*/);
	
	for ($li_i=1;$li_i<$li_filasconcepto;$li_i++)
	  { 
		$ls_codpro=$la_detalles["codpro"][$li_i];
		$ls_codalm=$la_detalles["codalm"][$li_i];
		$ls_cant=$la_detalles["canpro"][$li_i];
		$ls_prepro=$la_detalles["prepro"][$li_i];
		$ld_porimp=$io_funcsob->uf_convertir_cadenanumero($la_detalles["porimp"][$li_i]);
		$li_preuniart=$io_funcsob->uf_convertir_cadenanumero($ls_prepro);
		$ld_canpro=$io_funcsob->uf_convertir_cadenanumero($ls_cant);
		$li_montotart=($li_preuniart*$ld_canpro)+(($li_preuniart*$ld_canpro)*$ld_porimp);
		$ls_unidad="D";
		
		$io_siv->uf_sim_procesar_dt_despacho($ls_codemp,$ls_numord,$ls_codpro,$ls_codalm,$ls_unidad,$ld_canpro,$ld_canpro,$li_preuniart,$li_montotart,$li_montotart,$li_i,$ls_nummov,$ld_fecemi,$ls_numdoc,0/*,$la_seguridad*/);
		   
		$io_art->uf_sim_disminuir_articuloxalmacen($ls_codemp,$ls_codpro,$ls_codalm,$ld_canpro/*,$la_seguridad*/);
		
		$io_art->uf_sim_actualizar_cantidad_articulos($ls_codemp,$ls_codpro/*,$la_seguridad*/);   
      }
 }
/*****************************************************/
/*******FIN INTEGRACION CON INVENTARIO****************/
/*****************************************************/
		if ($lb_valido==true)
		{
				/*$io_msg->message ($ls_mensaje);*/
				$ls_operacion="";
		}
		else
		{
				if($lb_valido==0)
				{
					$ls_operacion="";
				}
				else
				{
					//$io_msg->message ($ls_mensaje);
				}
		}
/******************   ACTUALIZANDO NOTAS PROCESADAS    ****************************************/	
       for ($li_i=1;$li_i<$li_filasfpago;$li_i++)
		 { 
		   $ls_metforpago=$_POST["txtmetforpag".$li_i]; 
		   $ls_numinst=$_POST["txtnuminst".$li_i];
		   		   
		   if ($ls_metforpago=="D") 
		   {
		      $ls_estnot="C";
			  $ls_numnot=$ls_numinst;//número de nota de debito usada para pagar.
			  $lb_valido=$io_nota->uf_update_actualizaestnot($ls_numnot,$ls_estnot);
			}
		 }
/***********************************************************************************************/		
		
/*****************   GENERAR NOTAS       ********************************************************/		
  	    $ld_totalforpag=str_replace('.','',$ls_totalforpag);  //convierte a formato numérico
		$ld_totalforpag=str_replace(',','.',$ld_totalforpag);
			
		$ld_totalmonret=str_replace('.','',$ls_monto);  //convierte a formato numérico
		$ld_totalmonret=str_replace(',','.',$ld_totalmonret);
			
		$ld_monto=$ld_totalforpag-$ld_totalmonret;
		if ($ld_monto>0) // if sobra dinero se genera nota de crédito automática
			{
			$ld_monto=number_format($ld_monto,2,',','.');  //convierte a formato numérico
		
			$ls_codcaj=$_SESSION["ls_codcaj"];
		    $ls_prefijo="ND";
		    $ls_serie=$_SESSION["ls_sernot"];
	        $io_secuencia->uf_obtener_secuencia($ls_codcaj."not",&$ls_secuencia);
	        $ls_numnot=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);  
			$ls_dennot="Nota de Crédito, por sobrante en la factura N° ".$ls_numfac;
			$ls_tipnot="CXP"; // C:nota crédito y D:nota débito
			$ls_fecnot=$ls_fecemi;
			$ls_estnot="P"; //P: pendiente y C: cancelado                                     
			//****  generar nueva nota de crédito automática  *****
			$lb_valido=$io_nota->uf_guardar_nota($ls_codcli,$ls_numnot,$ls_dennot,$ls_tipnot,$ls_fecnot,$ld_monto,$ls_estnot,$ls_numfac);
			$ls_mensaje=$io_nota->io_msgc;
			$io_msg->message ("Se ha generado un Saldo a favor del cliente Nota de Debito N° ".$ls_numnot); 
			}
			 
		if  ($ls_conpag==2)	
		 { 
		    $ld_totalmonret=number_format($ld_totalmonret,2,',','.');
			$ls_dennot="Nota de Debito, por cuenta a cobrar de facturar N° ".$ls_numfac;
			$ls_tipnot="CXC"; // C:nota crédito y D:nota débito
			$ls_fecnot=$ls_fecemi;
			$ls_estnot="P"; //P: pendiente y C: cancelado                                     
			//****  generar nueva nota de crédito automática  *****
			$lb_valido=$io_nota->uf_guardar_nota($ls_codcli,$ls_numfac,$ls_dennot,$ls_tipnot,$ls_fecnot,$ld_totalmonret,$ls_estnot,$ls_numfac);
			$ls_mensaje=$io_nota->io_msgc;
			$io_msg->message ("Se ha generado la Nota de Debito ".$ls_numfac); 
		 
		 }
		 elseif($ls_conpag==4)
		 {
		 	 for ($li_i=1;$li_i<$li_filasfpago;$li_i++)
		      { 
		          $ls_metforpago=$_POST["txtmetforpag".$li_i]; 
		          if ($ls_metforpago=="O") 
		           {
		   		     $ls_numinst=$_POST["txtnuminst".$li_i];
					 $ls_moncarta=$_POST["txtmontoforpag".$li_i];
					 		
					 $ls_dennot="Nota de Debito, por carta orden registrada N° ".$ls_numinst;
			         $ls_tipnot="CXC"; // C:nota crédito y D:nota débito
			         $ls_fecnot=$ls_fecemi;
			         $ls_estnot="P"; //P: pendiente y C: cancelado                                     
			         //****  generar nueva nota de crédito automática  *****
			         $lb_valido=$io_nota->uf_guardar_nota($ls_codcli,$ls_numinst,$ls_dennot,$ls_tipnot,$ls_fecnot,$ls_moncarta,$ls_estnot,$ls_numfac);
			         $ls_mensaje=$io_nota->io_msgc;
			         $io_msg->message ("Se ha generado la Nota de Debito ".$ls_numinst); 
		           }
		  	   }
		 }
		 elseif($ls_conpag==3)
		 {
		 	$ld_monto=$ld_totalmonret-$ld_totalforpag;
			$ld_monto=number_format($ld_monto,2,',','.'); 
			$ls_dennot="Nota de Debito, por pago parcial de la factura N° ".$ls_numfac;
			$ls_tipnot="CXC"; // C:nota crédito y D:nota débito
			$ls_fecnot=$ls_fecemi;
			$ls_estnot="P"; //P: pendiente y C: cancelado                                     
			         //****  generar nueva nota de crédito automática  *****
			$lb_valido=$io_nota->uf_guardar_nota($ls_codcli,$ls_numfac,$ls_dennot,$ls_tipnot,$ls_fecnot,$ld_monto,$ls_estnot,$ls_numfac);
			$ls_mensaje=$io_nota->io_msgc;
			$io_msg->message ("Se ha generado la Nota de Debito ".$ls_numfac); 
		 }				
/****************************************************************************************************************************/			

     } //$lb_valido_logusr	
}
/*********************************************************************************************************/
/*********************** ELIMINAR FACTURA EN BD **********************************************************/
/*********************************************************************************************************/
elseif ($ls_operacion=="ue_eliminar")
{
 //print "eliminar/";
 $ls_sql="SELECT * FROM sfc_factura WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."';";
			  
 $rs_datauni=$io_sql->select($ls_sql);
 $la_cotizacion=$io_sql->obtener_datos($rs_datauni);
 $io_datastore->data=$la_cotizacion;
 $ls_estfaccon=$io_datastore->getValue("estfaccon",1);//ñ
	  
	  
 if ($ls_estfaccon=="A") 
    {
	  $io_msg->message ("¡Factura ANULADA no se puede modificar!");
    }
 elseif ($ls_estfaccon=="P" || $ls_estfaccon=="N")
    {
	  $io_msg->message ("¡Factura por COBRAR no se puede modificar!");
	 }
 elseif ($ls_estfaccon=="C")
    {
	  $io_msg->message ("¡Factura CANCELADA no se puede modificar!");
	}
 else
    {
	  $ls_estfaccon="";
	}	 
	       	 
		
 if ($ls_estfaccon=="")			
   {

  /***********************  verificar si posee "COBRO" ***************************************************************/
	     $ls_sql="SELECT * 
                   FROM sfc_cobro
                  WHERE codemp='".$la_datemp["codemp"]."' AND codcli='".$ls_codcli."'";
				  
		$rs_datauni=$io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido_cob=false;
			$io_msg="Error en uf_select_cobro ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_cob=true; //Registro encontrado
		        $io_msg->message ("El Cliente posee un cobro pendiente no se puede eliminar!!!");				
			}
			else
			{
				$lb_valido_cob=false; //"Registro no encontrado"
			}
		}			    

/*********************************************************************************************************************/
if ($lb_valido_cob==false)
{

	//print "ue_eliminar/";
   $lb_valido=$io_factura->uf_delete_instpago($ls_numfac/*,$aa_seguridad*/);
   $lb_valido=$io_factura->uf_delete_detfactura($ls_numfac/*,$aa_seguridad*/);
   $lb_valido3=$io_factura->uf_delete_facturaretencion($ls_numfac);
   
   if ($lb_valido==true)
		{
				
		 		
				$li_filasconcepto=1;
				$la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodalm".$li_filasconcepto." type=hidden id=txtcodalm".$li_filasconcepto.">";
				$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
				$la_objectconcepto[$li_filasconcepto][3]="<input name=txtprepro".$li_filasconcepto." type=text id=txtprepro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
				$la_objectconcepto[$li_filasconcepto][4]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
				$la_objectconcepto[$li_filasconcepto][5]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center>";
				$la_objectconcepto[$li_filasconcepto][6]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
				$la_objectconcepto[$li_filasconcepto][7]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
				
				$li_filasfpago=1;
	
				$la_objectfpago[$li_filasfpago][1]="<input name=txtcodforpag".$li_filasfpago." type=text id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_filasfpago." type=hidden id=txtcodent".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
				$la_objectfpago[$li_filasfpago][2]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=45 style= text-align:left readonly>";
				$la_objectfpago[$li_filasfpago][3]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=20 style= text-align:center readonly>";
				$la_objectfpago[$li_filasfpago][4]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
				$la_objectfpago[$li_filasfpago][5]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
			$la_objectfpago[$li_filasfpago][6]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";	

				}
   
    $lb_valido=$io_factura->uf_delete_factura($ls_numfac/*,$aa_seguridad*/);
	/*******************INTEGRACION CON INVENTARIO***************************/
		 $ls_docum=substr($ls_numfac,0,5);
	     $ls_docum=$ls_docum.substr($ls_numfac,20,strlen($ls_numfac));
		 for ($li_i=1;$li_i<$li_filasconcepto;$li_i++)
          { 
	                        $ls_codpro=$_POST["txtcodpro".$li_i];  
	                        $ls_prepro=$_POST["txtprepro".$li_i];
	                        $ls_candev=$_POST["txtcanpro".$li_i];
	                        $ls_porimp=$_POST["txtporcar".$li_i];
							$ls_codalm=$_POST["txtcodalm".$li_i];
							if($ls_candev!="0,00"){
							$ls_candev=$io_funcsob->uf_convertir_cadenanumero($ls_candev);
							$ls_prepro=$io_funcsob->uf_convertir_cadenanumero($ls_prepro); /* convierte cadena en numero */
							$ls_porimp=$io_funcsob->uf_convertir_cadenanumero($ls_porimp);
							$ld_iva=($ls_prepro*$ls_porimp)*$ls_candev;
							$li_monsubart=$ls_prepro*$ls_candev;
							$li_montotart=$li_monsubart+$ld_iva;
							
							$ls_fecdev=date("Y-m-d");
					        $lb_valido=$io_sivRe->uf_sim_insert_recepcion($ls_codemp,$ls_docum,"----------",$ls_codalm,$ls_fecdev,
								"Entrada a Almacen por Anulacion de Factura",$ls_codusu,"0","1",&$ls_numconrec/*,$la_seguridad*/);
					
					        if ($lb_valido)
						     {
							    $ls_nummov=0;
							    $ls_nomsol="Recepcion";
							    $lb_valido=$io_mov->uf_sim_insert_movimiento(&$ls_nummov,$ls_fecdev,$ls_docum,$ls_codusu/*,
																		      $la_seguridad*/);
					 	     }
							
							$lb_valido=$io_sivRe->uf_sim_insert_dt_recepcion($ls_codemp,$ls_docum,$ls_codpro,"D",$ls_candev,0,$ls_prepro,$li_monsubart,$li_montotart,$li_i,$ls_candev,$ls_numconrec/*,$la_seguridad*/);
							if ($lb_valido)
							 {
									$lb_valido=$io_art->uf_sim_aumentar_articuloxalmacen($ls_codemp,$ls_codpro,$ls_codalm,
									                                    				 $ls_candev/*,$la_seguridad*/);
									if($lb_valido)
									{
										$ls_opeinv="ENT";
										$ls_promov="RPC";
										$ls_codprodoc="FAC";
										$lb_valido=$io_mov->uf_sim_insert_dt_movimiento($ls_codemp,$ls_nummov,$ls_fecdev,
																						$ls_codpro,$ls_codalm,$ls_opeinv,
																						$ls_codprodoc,$ls_docum,$ls_candev,
																						$ls_prepro,$ls_promov,$ls_numconrec,
																						$ls_candev,$ls_fecdev/*,$la_seguridad*/);
									}
							  }
							  if($lb_valido)
							   {
								$lb_valido=$io_art->uf_sim_actualizar_cantidad_articulos($ls_codemp,$ls_codpro/*,$la_seguridad*/);
								$lb_valido=$io_cotizacion->uf_update_cotizacionstatus($ls_numcot,'E');
								$io_factura->uf_delete_notas($ls_numfac);
							   }
					        }
					    }
					/*******************FIN INTEGRACION CON INVENTARIO***********************/								
  
   	if ($lb_valido==true)
		{
				$io_msg->message($io_factura->io_msgc);
				$ls_operacion="";
				$ls_numfac="";
				$ls_numcot="0000000000000000000000000";
				$ls_fecemi="";
				$ls_codcli="";
				$ls_nomcli="";
				$ls_conpag=1;
				$ls_monto="0,00";
				$ls_totalmonret="0,00";
				$ls_subtotalret="0,00";
				
				
		 } 
		 
  } //$lb_valido_cob==false	
 }//$ls_estfaccon==""	 
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
  $ls_sql="SELECT codcli,razcli,cedcli FROM sfc_cliente WHERE codemp='".$ls_codemp."' AND cedcli='".$ls_cedcli."';";
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
	$ls_cedaux=substr($ls_cedcli,1,9);
	$ls_aux=substr($ls_cedcli,0,1);	
	$ls_aux2=substr($ls_cedcli,1,1);
	$ls_aux3=substr($ls_cedcli,2,1);
	$ls_aux4=substr($ls_cedcli,3,1);
	$ls_aux5=substr($ls_cedcli,4,1);
	$ls_aux6=substr($ls_cedcli,5,1);
	$ls_aux7=substr($ls_cedcli,6,1);
	$ls_aux8=substr($ls_cedcli,7,1);
	$ls_aux9=substr($ls_cedcli,8,1);
	$ls_aux10=substr($ls_cedcli,9,1);
	if ($ls_aux=='V' or $ls_aux=='J' or $ls_aux=='E' or $ls_aux=='G' and ($ls_aux2=='0' or $ls_aux2=='1' or $ls_aux2=='2' or $ls_aux2=='3' or $ls_aux2=='4' or $ls_aux2=='5' or $ls_aux2=='6' or $ls_aux2=='7' or $ls_aux2=='9') and ($ls_aux3=='0' or $ls_aux3=='1' or $ls_aux3=='2' or $ls_aux3=='3' or $ls_aux3=='4' or $ls_aux3=='5' or $ls_aux3=='6' or $ls_aux3=='7' or $ls_aux3=='9')and ($ls_aux4=='0' or $ls_aux4=='1' or $ls_aux4=='2' or $ls_aux4=='3' or $ls_aux4=='4' or $ls_aux4=='5' or $ls_aux4=='6' or $ls_aux4=='7' or $ls_aux4=='9') and ($ls_aux5=='0' or $ls_aux5=='1' or $ls_aux5=='2' or $ls_aux5=='3' or $ls_aux5=='4' or $ls_aux5=='5' or $ls_aux5=='6' or $ls_aux5=='7' or $ls_aux5=='9') and ($ls_aux6=='0' or $ls_aux6=='1' or $ls_aux6=='2' or $ls_aux6=='3' or $ls_aux6=='4' or $ls_aux6=='5' or $ls_aux6=='6' or $ls_aux6=='7' or $ls_aux6=='9') and ($ls_aux7=='0' or $ls_aux7=='1' or $ls_aux7=='2' or $ls_aux7=='3' or $ls_aux7=='4' or $ls_aux7=='5' or $ls_aux7=='6' or $ls_aux7=='7' or $ls_aux7=='9') and ($ls_aux8=='0' or $ls_aux8=='1' or $ls_aux8=='2' or $ls_aux8=='3' or $ls_aux8=='4' or $ls_aux8=='5' or $ls_aux8=='6' or $ls_aux8=='7' or $ls_aux8=='9') and ($ls_aux9=='0' or $ls_aux9=='1' or $ls_aux9=='2' or $ls_aux9=='3' or $ls_aux9=='4' or $ls_aux9=='5' or $ls_aux9=='6' or $ls_aux9=='7' or $ls_aux9=='9') and ($ls_aux10=='0' or $ls_aux10=='1' or $ls_aux10=='2' or $ls_aux10=='3' or $ls_aux10=='4' or $ls_aux10=='5' or $ls_aux10=='6' or $ls_aux10=='7' or $ls_aux10=='9'))
	{
	if ($ls_cedaux!='000000000')
	{
	?>
	 <script language="JavaScript">  
   	 	var cedcli="<?php print $ls_cedcli; ?>";
		pagina="sigesp_cat_clientecotizacion.php?cedcli="+cedcli;
	  	popupWin(pagina,"catalogo",400,350);
     </script>
	<?php
	}
	else{
	?><script>
	//
	alert ('cedula o rif invalido');	
	 </script>
	 <?php
	 $ls_cedcli='';
	
	}
	}else{
	?><script>
	//
	alert ('cedula o rif invalido');	
	 </script>
	 <?php
	 $ls_cedcli='';
		} 
	}
}
/*******************************************************************************************************/
/********************************** ANULAR FACTURA    **************************************************/
/*******************************************************************************************************/
if ($ls_operacion=="ue_anular")
{

 $ls_sql="SELECT * FROM sfc_factura WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."';";
			  
 $rs_datauni=$io_sql->select($ls_sql);
 $la_factura=$io_sql->obtener_datos($rs_datauni);
 $io_datastore->data=$la_factura;
 $ls_estfaccon=$io_datastore->getValue("estfaccon",1);//ñ
 $ls_estfac=$io_datastore->getValue("estfac",1);//ñ
	  
if ($ls_estfac=="N") //Si la factura no esta CERRADA
{	  
	 if ($ls_estfaccon=="A") 
		{
		  $io_msg->message ("¡Factura ya esta ANULADA!");
		}
	 elseif ($ls_estfaccon=="P" || $ls_estfaccon=="N")
		{
		  $io_msg->message ("¡Factura por COBRAR será anulada!");
		  $ls_sql="SELECT * 
                   FROM sfc_cobro
                  WHERE codemp='".$la_datemp["codemp"]."' AND codcli='".$ls_codcli."'";
				  
		  $rs_datauni=$io_sql->select($ls_sql);

		  if($rs_datauni==false)
		   {
			$lb_valido_cob=false;
			$io_msg="Error en uf_select_cobro ".$io_funcion->uf_convertirmsg($io_sql->message);
		   }
		   else
		   {
			  if($row=$io_sql->fetch_row($rs_datauni))
			   {
				 $lb_valido_cob=true; //Registro encontrado
		         $io_msg->message ("El Cliente posee un cobro pendiente no se puede anular!!!");				
			   }
			   else
			   {
				 $lb_valido_cob=false; //"Registro no encontrado"
				 $ls_estfaccon="";
			   }
		   }
		}
	 elseif ($ls_estfaccon=="C")
		{
		  $io_msg->message ("¡Factura CANCELADA será anulada!");
		  $ls_estfaccon="";
		}
	 else
		{
		  $ls_estfaccon="";
		}	 
				 
			
	 if ($ls_estfaccon=="")			
	   {
	     $ls_estfaccon="A";
		 $io_factura->uf_actualizar_facturastatus($ls_numfac,$ls_estfaccon);
		 
		 /*******************INTEGRACION CON INVENTARIO***************************/
		 $ls_docum=substr($ls_numfac,0,5);
	     $ls_docum=$ls_docum.substr($ls_numfac,20,strlen($ls_numfac));
		 for ($li_i=1;$li_i<$li_filasconcepto;$li_i++)
          { 
	                        $ls_codpro=$_POST["txtcodpro".$li_i];  
	                        $ls_prepro=$_POST["txtprepro".$li_i];
	                        $ls_candev=$_POST["txtcanpro".$li_i];
	                        $ls_porimp=$_POST["txtporcar".$li_i];
							$ls_codalm=$_POST["txtcodalm".$li_i];
							if($ls_candev!="0,00"){
							$ls_candev=$io_funcsob->uf_convertir_cadenanumero($ls_candev);
							$ls_prepro=$io_funcsob->uf_convertir_cadenanumero($ls_prepro); /* convierte cadena en numero */
							$ls_porimp=$io_funcsob->uf_convertir_cadenanumero($ls_porimp);
							$ld_iva=($ls_prepro*$ls_porimp)*$ls_candev;
							$li_monsubart=$ls_prepro*$ls_candev;
							$li_montotart=$li_monsubart+$ld_iva;
							
							$ls_fecdev=date("Y-m-d");
					        $lb_valido=$io_sivRe->uf_sim_insert_recepcion($ls_codemp,$ls_docum,"----------",$ls_codalm,$ls_fecdev,
								"Entrada a Almacen por Anulacion de Factura",$ls_codusu,"0","1",&$ls_numconrec/*,$la_seguridad*/);
					
					        if ($lb_valido)
						     {
							    $ls_nummov=0;
							    $ls_nomsol="Recepcion";
							    $lb_valido=$io_mov->uf_sim_insert_movimiento(&$ls_nummov,$ls_fecdev,$ls_docum,$ls_codusu/*,
																		      $la_seguridad*/);
					 	     }
							
							$lb_valido=$io_sivRe->uf_sim_insert_dt_recepcion($ls_codemp,$ls_docum,$ls_codpro,"D",$ls_candev,0,$ls_prepro,$li_monsubart,$li_montotart,$li_i,$ls_candev,$ls_numconrec/*,$la_seguridad*/);
							if ($lb_valido)
							 {
									$lb_valido=$io_art->uf_sim_aumentar_articuloxalmacen($ls_codemp,$ls_codpro,$ls_codalm,
									                                    				 $ls_candev/*,$la_seguridad*/);
									if($lb_valido)
									{
										$ls_opeinv="ENT";
										$ls_promov="RPC";
										$ls_codprodoc="FAC";
										$lb_valido=$io_mov->uf_sim_insert_dt_movimiento($ls_codemp,$ls_nummov,$ls_fecdev,
																						$ls_codpro,$ls_codalm,$ls_opeinv,
																						$ls_codprodoc,$ls_docum,$ls_candev,
																						$ls_prepro,$ls_promov,$ls_numconrec,
																						$ls_candev,$ls_fecdev/*,$la_seguridad*/);
									}
							  }
							  if($lb_valido)
							   {
								$lb_valido=$io_art->uf_sim_actualizar_cantidad_articulos($ls_codemp,$ls_codpro/*,$la_seguridad*/);
								$lb_valido=$io_cotizacion->uf_update_cotizacionstatus($ls_numcot,'E');
								$io_factura->uf_delete_notas($ls_numfac);
							   }
					        }
					    }
					/*******************FIN INTEGRACION CON INVENTARIO***********************/
 	 			    
	   }	
} 
else
{
  $io_msg->message ("La factura esta CERRADA no se puede anular!!!");				
}

}
/*******************************************************************************************************/
/*************************** VER REPORTE DE FACTURA ****************************************************/
/*******************************************************************************************************/
if($ls_operacion=="VER")
{
	   
      $ls_codtie=$_SESSION["ls_codtienda"];
	 
	    $ls_operacion="";	
$ls_sql="SELECT c.codcli,c.cedcli,c.razcli,c.dircli,c.telcli,c.celcli,f.numfac,f.fecemi,f.conpag,f.monto,f.estfaccon,f.estfac,df.codpro,df.canpro,df.prepro,df.porimp,p.denpro,p.moncar FROM sfc_cliente c,sfc_factura f,sfc_detfactura df,sfc_producto p WHERE f.codemp='".$ls_codemp."' AND c.codcli=f.codcli AND f.numfac=df.numfac AND df.codpro=p.codpro AND  f.numfac='".$ls_numfac."' ;";
		

	/**********************************  VERIFICAR QUE EXISTA FACTURA   ****************************************************/	  
		$rs_datauni=$io_sql->select($ls_sql);

				 
		if($rs_datauni==false)
		{
			$lb_valido_repfac=false;
			$io_msg="Error en uf_select_ReporteFacturacion ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
			
				 $lb_valido_repfac=true; //Registro encontrado
							 
			 				
			}
			else
			{
				$lb_valido_repfac=false; //"Registro no encontrado"
				 $io_msg->message ("La Factura no esta registrada, regístrela e intente de nuevo!!!");
			}
		}			     
	/****************************************************************************************************************************/
	if ($lb_valido_repfac==true)
	 {
	
?>
  <script language="JavaScript">
	 
		var ls_sql="<?php print $ls_sql; ?>"; 
	  	pagina="reportes/sigesp_sfc_rep_imprimirfactura.php?sql="+ls_sql;
		
		popupWin(pagina,"catalogo",580,700);
    </script> 
       
<?PHP
    }
} 
if ($ls_operacion=="ue_actualizarestacot")
{
/*---------------------------- ACTUALIZAR COTIZACION A "FACTURADA"   ------------------------------------------*/
		
	   if ($ls_estcot='P')	
	   {
	    $ls_estcot='E';
   	    $lb_valido=$io_cotizacion->uf_update_cotizacionstatus($ls_numcot,$ls_estcot);
	   } 	
}
?>

<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="534" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1 Estilo3">Sistema de Facturaci&oacute;n</span></td>
    <td width="244" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
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
  <form name="form1" method="post"  action="">
    
    <input name="hidestobr" type="hidden" id="hidestobr" value="<? print $ls_estobr ?>">
    <input name="hidmonobr" type="hidden" id="hidstaobr" value="<? print $ls_monobr ?>">
    <input name="hidmonasi" type="hidden" id="hidmonasi" value="<? print $ls_monasi ?>">
  <input name="txtcanpro" type="hidden" id="txtcanpro">
  <input name="txtporcar" type="hidden" id="txtporcar">
  <input name="txtmontoforpag" type="hidden" id="txtmontoforpag">
  <table width="912" height="382" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
    <tr class="titulo-celdanew">
      <th height="19" colspan="8" class="titulo-celdanew" scope="col">Facturar</th>
    </tr>
    
    <tr class="formato-blanco">
      <td height="20" colspan="8"><input name="hidremoverconcepto2" type="hidden" id="hidremoverconcepto2">
        <input name="txtfilasfpago" type="hidden" id="txtfilasfpago" value="<?php print $li_filasfpago ?>">
          <input name="hidremoverfpago" type="hidden" id="hidremoverfpago" value="<? print $li_removerfpago ?>">
          <input name="hidcodalm" type="hidden" id="hidcodalm" value="<?php print $ls_codalm ?>">
      <input name="txtmoncar" type="hidden" id="txtmoncar">
        <div align="left">
          <input name="removerretenciones" type="hidden" id="removerretenciones" value="<? print $li_removerretenciones?>">
          <input name="filasretenciones" type="hidden" id="filasretenciones" value="<? print $li_filasretenciones?>">
          <input name="txtestcot" type="hidden" id="txtestcot" value="<?php print $ls_estcot ?>">
          <input name="txtestfaccon" type="hidden" id="txtestfaccon" value="<?php print $ls_estfaccon ?>">
          <input name="txtnumcot_ant" type="hidden" id="txtnumcot_ant" value="<?php print $ls_numcot_ant ?>">
          <input name="txtestcot_ant" type="hidden" id="txtestcot_ant" value="<?php print $ls_estcot_ant ?>">
          <input name="operacion" type="hidden" id="operacion">
          <input name="opemostrar" type="hidden" id="opemostrar" value="<? print $ls_opemostrar ?>">
          <input type="hidden" name="hidbanexi" value="<? print $ls_hidbanexi ?>">
        </div></td>
    </tr>
    
    <tr class="formato-blanco">
      <?php
	    if ($ls_estfaccon!=""){		
		?>
		   <td width="86" height="22"><div align="right">N&ordm; Factura </div></td>
           <td colspan="2"><div align="left">
          <input name="txtnumfac" type="text" id="txtnumfac" value="<? print $ls_numfac ?>" size="28" maxlength="25" readonly="true">
		 </div></td>
     
	  <?php
	    }
		else{
	  ?>
	     <td width="86" height="22"></td>
         <td colspan="2"><div align="left">
	     <input name="txtnumfac" type="hidden" id="txtnumfac" value="<? print $ls_numfac ?>" size="28" maxlength="25" readonly="true">
		 </div></td>
	  <?php
	    }
	  ?>
      <td colspan="3"><div align="left"><span class="titulo-cat&aacute;logo">
	  <?php
	 	if ($ls_estfaccon=="A")
		{
		?>
        <font color="#3982C6">ANULADA</font>
        <?php
		}
		elseif ($ls_estfaccon=="P")
		{
		?>
        <font color="#3982C6">POR COBRAR</font>
        <?php
		}
		elseif ($ls_estfaccon=="N")
		{
		?>
        <font color="#3982C6">POR COBRAR</font>
        <?php
		}
		elseif ($ls_estfaccon=="C")
		{
		?>
        <font color="#3982C6">CANCELADA</font>
        <?php
		}
		elseif ($ls_estfaccon=="")
		{
		?>
        <font color="#3982C6"></font>
        <?php
		}
		?>
      </span></div></td>
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
		?>	  </td>
    </tr>
    
    <tr class="formato-blanco">
      <td height="22"><div align="right">Cliente</div></td>
      <td colspan="7"><input name="txtcodcli" type="text" id="txtcodcli" style="text-align:center " value="<?php print $ls_codcli ?>" size="15" maxlength="15" readonly>
	  <input name="txtcedcli" type="text" id="txtcedcli" style="text-align:center " value="<?php if ($ls_cedcli!="")
		{ 
		print $ls_cedcli;} ?>" size="15" maxlength="15" onBlur="ue_buscarcli();" onKeyPress="return(validaCajas(this,'z',event,254))" title="El RIF Debe comenzar con J,V,G ó E">
          
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
        <font color="#3982C6">EMITIDA</font>
        <?php
		}
		elseif ($ls_estcot=="P")
		{
		?>
        <font color="#3982C6">EN PROCESO</font>
        <?php
		}
		elseif ($ls_estcot=="F")
		{
		?>
        <font color="#3982C6">FACTURADA</font>
        <?php
		}
		elseif ($ls_estcot=="")
		{
		?>
        <font color="#3982C6"></font>
        <?php
		}
		?>
        </span></td>
    </tr>
    <tr class="formato-blanco">
      <td height="22"><div align="center">Condicion Pago </div></td>
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
			  <select name="combo_conpag" size="1" id="combo_conpag">
			 <?php  
			  }
				  if($ls_conpag==1)
				  {
			      ?>
            <option value="1"  onClick="actualizar_check1();" selected >CONTADO</option>
            <option value="2" onClick="actualizar_check1();">CREDITO</option>
			<option value="3" onClick="actualizar_check1();">PARCIAL</option>
			<option value="4" onClick="actualizar_check1();">CARTA ORDEN</option>
                 <?php
				  }
				 elseif($ls_conpag==2)
				  {	 
				 ?>
            <option value="1"  onClick="actualizar_check1();">CONTADO</option>
            <option value="2" onClick="actualizar_check1();" selected>CREDITO</option>
			<option value="3" onClick="actualizar_check1();">PARCIAL</option>
			<option value="4" onClick="actualizar_check1();">CARTA ORDEN</option>
                <?php
				 }
			     elseif($ls_conpag==3)
				 {	 
				 ?>
            <option value="1" onClick="actualizar_check1();" >CONTADO</option>
            <option value="2" onClick="actualizar_check1();" >CREDITO</option>
			<option value="3" onClick="actualizar_check1();" selected>PARCIAL</option>
			<option value="4" onClick="actualizar_check1();">CARTA ORDEN</option>
                <?php
				 }	 
				 else
				 {
				 ?>
			<option value="1" onClick="actualizar_check1();" >CONTADO</option>
            <option value="2" onClick="actualizar_check1();" >CREDITO</option>
			<option value="3" onClick="actualizar_check1();" >PARCIAL</option>
			<option value="4" onClick="actualizar_check1();" selected>CARTA ORDEN</option>
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
      <td height="21" colspan="7"><div align="right">Sub-Total</div></td>
      <td><input name="txtsubtot" type="text" id="txtsubtot"  style="text-align: right" value="<? print $ls_subtot ?>" size="20" maxlength="20" readonly="true"></td>
    </tr>
      
      <tr class="formato-blanco">
        <td height="21" colspan="7"><div align="right">IVA</div></td>
        <td><input name="txtmoniva" type="text" id="txtmoniva" style="text-align:right"  value="<?php print $ls_moniva ?>" size="20" maxlength="20" readonly="true" onChange="javascript:ue_calretencion();"></td>
      </tr>
      <tr class="formato-blanco">
        <td height="20" colspan="7"><div align="right">Total</div></td>
        <td><input name="txtmonto" type="text" id="txtmonto"  style="text-align: right" value="<? print $ls_monto ?>" size="20" maxlength="20"  readonly="true"></td>
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

/*******************************************************************************************************************************/
/*******************************************************************************************************************************/
/********************************************* RUTINAS JAVASCRIPT **************************************************************/
/*******************************************************************************************************************************/
/*******************************************************************************************************************************/

function actualizar_check1()
{
   f=document.form1;
   f.operacion.value="";
   suiche_submit=true;
   f.submit();
}

function ue_actualizarestacot()
{
  
  
  if (suiche_submit==false)
  {
	  f=document.form1;
	 
	  numcot=f.txtnumcot.value;
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

  f=document.form1;
  f.operacion.value="VER";
  f.action="sigesp_sfc_d_factura.php";
  suiche_submit=true;
  f.submit();
  
  } 
function ue_catcotizacion()
{
 f=document.form1;
 f.operacion.value="";
 codcliente=f.txtcodcli.value;
 pagina="sigesp_cat_cotizacionfactura.php?codcli1="+codcliente;
 popupWin(pagina,"catalogo",850,450);
	
}
function ue_buscar()
{
  f=document.form1;
  /*li_leer=f.leer.value;
  if(li_leer==1)
   {*/
     f.operacion.value="";
     pagina="sigesp_cat_factura.php";
     popupWin(pagina,"catalogo",850,450);
	 //window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=350,resizable=yes,location=no");
   /*}
	else
	{
	  alert("No tiene permiso para realizar esta operacion");
	}*/
} 

/*******************************************************************************************************************************/
/*******************************************************************************************************************************/
/********************************************* COMIENZO DE FUNCIONES MODIFICADAS ***********************************************/
/*******************************************************************************************************************************/
/*******************************************************************************************************************************/
function ue_cargarfpago(numinst,monto,formapago,nombanco,codban,codforpag,metforpago,codent) //x
{
		
	//alert(metforpago);
    f=document.form1;
	f.operacion.value="ue_cargarfpago";	
	lb_existe=false;
	for(li_i=1;li_i<=f.txtfilasfpago.value && !lb_existe;li_i++)
	{
		ls_codigo=eval("f.txtcodforpag"+li_i+".value");
		ls_num=eval("f.txtnuminst"+li_i+".value");
		
		if((ls_codigo==codforpag)&&(numinst==ls_num))
		{
			alert("esta forma de pago ya ha sido cargada!!!");
			lb_existe=true;
		}
	}	
	
	if(!lb_existe)
	{
	    eval("f.txtmontoforpag"+f.txtfilasfpago.value+".value='"+monto+"'");
		suiche=ue_subtotalfpago();
		
		if (suiche==true)
		{	
		
		eval("f.txtcodforpag"+f.txtfilasfpago.value+".value='"+codforpag+"'");
		eval("f.txtmetforpag"+f.txtfilasfpago.value+".value='"+metforpago+"'");
		eval("f.txtcodent"+f.txtfilasfpago.value+".value='"+codent+"'");
		eval("f.txtdenforpag"+f.txtfilasfpago.value+".value='"+formapago+"'");
		eval("f.txtnuminst"+f.txtfilasfpago.value+".value='"+numinst+"'");
		eval("f.txtnombanco"+f.txtfilasfpago.value+".value='"+nombanco+"'");
		eval("f.txtcodban"+f.txtfilasfpago.value+".value='"+codban+"'");
		eval("f.txtmontoforpag"+f.txtfilasfpago.value+".value='"+monto+"'");			
		}
		else
		{
		  alert("No se pudo cargar el documento de pago");
		}
		suiche_submit=true;
    	f.submit();
	}

}
function ue_catinstrpago()//kk
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
	   if (f.combo_conpag.value=="2") alert("La condición de pago es a crédito.");
	   if (f.txtmonto.value=="0,00") alert("Debe agregar productos y la cantidad.");
	 } 	 
 }
 else
 {
  alert("¡Factura no se puede modificar!");
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


                          
function ue_cargarfactura(codigo,numfac,cotizacion,fecemi,conpag,monto,estfac,nombre,estfaccon,esppag,cedcli)
{
			f=document.form1;

		 	f.txtnumfac.value=numfac;			
			f.txtnumcot.value=cotizacion;
			f.txtcodcli.value=codigo;
			f.txtcedcli.value=cedcli;
			f.txtfecemi.value=fecemi;
			f.txtnomcli.value=nombre;
			f.combo_conpag.value=conpag;
			f.txtmonto.value=monto;	
			f.txtestfaccon.value=estfaccon;
			if (esppag=="V")
			{
			 f.check1.checked=true;
			}
			else
			{
			f.check1.checked=false;
			}
			
			f.operacion.value="ue_cargarconcepto_factura";
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

function ue_catclientefac()
		{
            f=document.form1;
			f.operacion.value="";					
			pagina="sigesp_cat_cliente1.php";
	    	popupWin(pagina,"catalogo",520,350);
		}
		
/******************************************************************************************************************************/
function ue_cargarproducto(codpro,denpro,tippro,preven,codcar,dencar,codcla,dencla,codart,denart,ultcosart,spi_cuenta,denspi,sc_cuenta,denscg,moncar,porgan,tipcos,prepro,preven1,preven2,preven3,cosfle,cosproart,porcar,codalm,existe)
{

			
    f=document.form1;
	f.operacion.value="ue_cargarconcepto";	
	lb_existe=false;
	
	for(li_i=1;li_i<=f.filasconcepto.value && !lb_existe;li_i++)
	{
		ls_codigo=eval("f.txtcodpro"+li_i+".value");
		if(ls_codigo==codpro)
		{
			alert("El producto ya ha sido cargada!!!");
			lb_existe=true;
		}
	}	
	
	if(!lb_existe)
	{
		eval("f.txtcodpro"+f.filasconcepto.value+".value='"+codpro+"'");
		eval("f.txtcodalm"+f.filasconcepto.value+".value='"+codalm+"'");
		eval("f.txtexiste"+f.filasconcepto.value+".value='"+existe+"'");
		eval("f.txtdenpro"+f.filasconcepto.value+".value='"+denpro+"'");
		eval("f.txtprepro"+f.filasconcepto.value+".value='"+preven+"'");
		eval("f.txtporcar"+f.filasconcepto.value+".value='"+porcar+"'");
		eval("f.txtmoncar"+f.filasconcepto.value+".value='"+moncar+"'");
		suiche_submit=true;
    	f.submit();
	}

}
/******************************************************************************************************************************/		
function ue_catproducto()
{ 

f=document.form1;
if (f.txtestfaccon.value=="A")
	  {
	    alert("¡Factura ANULADA no se puede modificar!");
	  }
	else if (f.txtestfaccon.value=="P" || f.txtestfaccon.value=="N")   
	  {
	  alert("¡Factura por COBRAR no se puede modificar!");
	  }
	else if (f.txtestfaccon.value=="C")   
	  {
	  alert("¡Factura CANCELADA no se puede modificar!");
	  }  
	else
	  {  	
		validar=ue_validar_cabecera();
		if (validar==true)
		{	
			f.operacion.value="";			
			pagina="sigesp_cat_producto_almacen2.php";
			popupWin(pagina,"catalogo",580,300);
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
			 else if (combo_conpag.value=="1" && txttotalforpag.value!=txtmonto.value && check1.checked==false)
			 {
				alert("El monto de la factura debe coincidir con el de las formas de pago.");
				suiche=true;
			 }
			 else if (combo_conpag.value=="2" && txttotalforpag.value!="0,00")
			 {
				alert("No debe existir forma de pago ya que la factura es a crédito.");
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
				  alert("Debe incluir algún producto y su cantidad.");
				  suiche=true;
			 }	
			 else if (parseFloat(uf_convertir_monto(txttotalforpag.value))<=parseFloat(uf_convertir_monto(txtmonto.value)) && combo_conpag.value=="1" && check1.checked==true ) //Verifica si factura es de CONTADO y CASO ESPECIAL
			 {
			
				  alert("La factura es caso especial seleccione otra condición de pago si el monto es menor al de la factura.");
				  suiche=true;
			 }			
		
			 
			 if (suiche==false)
			    {
					
					if(f.hidbanexi.value=="true"){
					 alert("Esta intentando realizar una venta por una cantidad superior a la existencia!!!");
					 }else{
					 
					  f.operacion.value="ue_guardar";
					  f.action="sigesp_sfc_d_factura.php";
					  suiche_submit=true;
				 	  f.submit();
					 
					 }
					 
					
				}	
			
		} 
 }
 else
 {
  alert("¡Factura no se puede modificar!");
 }
	
}	

function ue_validar_cabecera()
{
    suiche=true;
	f=document.form1;
	with(f)
		{			
		     if (f.filasconcepto.value>10)
			 {
			 	alert("Ha excedido el número maximo de líneas en la cotización!")
				suiche=false;
			 }						
			else if (ue_valida_null(txtcodcli,"Cliente")==false)
			 {
			 	txtcodcli.focus();
				suiche=false;
			 }
		
			else if (ue_valida_null(txtfecemi,"Fecha")==false)
			 {
				  txtfecemi.focus();
				  suiche=false;
			 }
			 else if (ue_valida_null(txtnumfac,"No. de factura")==false)
			 {
				  txtnumfac.focus();
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

/*********************************GENERAR NUEVO******************************************************************/
function ue_nuevo()
		{
		  f=document.form1;
	      /*li_incluir=f.incluir.value;
	      if(li_incluir==1)
	       {*/		
		   
		    f.operacion.value="ue_nuevo";
			
			f.txtnumcot_ant.value=f.txtnumcot.value;
		 	f.txtestcot_ant.value=f.txtestcot.value;
			f.txtnumfac.value="";
			f.txtnumcot.value="0000000000000000000000000";
			f.txtcodcli.value="";
			f.txtcedcli.value="";
			f.txtnomcli.value="";
			f.txtsubtot.value="";
			f.txtmoniva.value="";
			f.txtmonto.value="";
			f.txtresta.value="";
			f.txttotalforpag.value="";
			
			
		 
    	    f.filasconcepto.value=""; 
			f.hidremoverconcepto.value="";
	 
		     f.action="sigesp_sfc_d_factura.php";
			 suiche_submit=true;
		     f.submit();
		   /*}
           else
	       {
		     alert("No tiene permiso para realizar esta operacion");
	       }*/
	   }
/********************************************************************************************************************************/
/********************************************************************************************************************************/
/********************************************************************************************************************************/
/******************************************VALIDACIONES**************************************************************************/

function ue_subtotalfpago() //Z
{
   	f=document.form1;
	li_filasfpago=f.txtfilasfpago.value;
	ld_monto=0;
	ls_cero="0,00";
	suiche=true;
	
	for(li_i=1;li_i<=li_filasfpago;li_i++)
	{
	   if(eval("f.txtmontoforpag"+li_i+".value")=="")
		{
		   ld_montoforpag=0;
		}
	    else
		{
		   ld_montoforpag=parseFloat(uf_convertir_monto(eval("f.txtmontoforpag"+li_i+".value")));   
		   ld_monto=ld_monto+ld_montoforpag; 
		   if (ld_monto>uf_convertir_monto(f.txtmonto.value) && f.check1.checked==false)
			{
				   alert("El monto de la forma de pago no puede ser superior al monto de la factura");
				   suiche=false;
			}    
		}
	}	
	
	if (suiche==true)
	{
	 f.txttotalforpag.value=uf_convertir(ld_monto);
	 resta=parseFloat(uf_convertir_monto(f.txtmonto.value))-parseFloat(uf_convertir_monto(f.txttotalforpag.value));
	 f.txtresta.value=uf_convertir(resta);
	} 
	
	return suiche;
}

function ue_subtotal()
{
	f=document.form1;
	li_filasconcepto=f.filasconcepto.value;
	ld_subtotal=0;
	ld_iva=0;
	ld_monto=0;
	ls_cero="0,00";
	for(li_i=1;li_i<=li_filasconcepto-1;li_i++)
	{
	   if(eval("f.txtcanpro"+li_i+".value")=="")
		  {
		   ld_canpro=0;
		  }
	   else
		  {
		   ld_canpro=parseFloat(uf_convertir_monto(eval("f.txtcanpro"+li_i+".value")));
		   ld_moncar=parseFloat(uf_convertir_monto(eval("f.txtporcar"+li_i+".value")));
		   ld_existe=parseFloat(uf_convertir_monto(eval("f.txtexiste"+li_i+".value")));	 
		   if(eval("f.txtprepro"+li_i+".value")=="")
		      {
		       ld_prepro=0;
		      }
		      else
		      {
		         ld_prepro=parseFloat(uf_convertir_monto(eval("f.txtprepro"+li_i+".value")));
		      }
			  
			  if(ld_canpro>ld_existe)
			   {
			    alert("La cantidad a facturar es mayor que la existencia del almacen seleccionado");
				f.hidbanexi.value="true";
			   }
			   else{
			    f.hidbanexi.value="";
			   }
			   ld_moncar=ld_moncar/100;
		       ld_totpar=ld_prepro*ld_canpro;
			   ld_iva=ld_iva + ((ld_moncar*ld_prepro)*ld_canpro);
			   ld_subtotal=ld_subtotal+ld_totpar;
			   
			   ls_totp=uf_convertir(ld_totpar);
		       eval("f.txttotpro"+li_i+".value='"+ls_totp+"'");
			   ld_monto=ld_subtotal+ld_iva;	
		 }
	}	
	f.txtsubtot.value=uf_convertir(ld_subtotal);
	f.txtmoniva.value=uf_convertir(ld_iva);
	f.txtmonto.value=uf_convertir(roundNumber(ld_monto));
	f.txtresta.value=uf_convertir(roundNumber(ld_monto));
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

function ue_eliminar()  
{
	f=document.form1;
	/*li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{*/	
	  if (f.txtnumfac.value=="")
	  {
		alert("Debe seleccionar la Factura a eliminar!!");
	  }
	  else
	  {
	    si=confirm("Esta seguro?");
		 if(si)
		 {
			f.action="sigesp_sfc_d_factura.php";
			f.operacion.value="ue_eliminar";
			suiche_submit=true;
			f.submit();
		 }
	  }
	/*}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}*/  
}
function ue_anular()  
{
	f=document.form1;
	  if (f.txtnumfac.value=="")
	  {
		alert("Debe seleccionar la Factura a anular!!");
	  }
	  else
	  {
	    si=confirm("Esta seguro?");
		 if(si)
		 {
			f.action="sigesp_sfc_d_factura.php";
			f.operacion.value="ue_anular";
			suiche_submit=true;
			f.submit();
		 }
	  }
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
