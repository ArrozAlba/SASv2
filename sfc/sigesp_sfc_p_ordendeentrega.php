<?php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "location.href='../index.php'";
	print "</script>";
}
$la_datemp=$_SESSION["la_empresa"];
$ls_codtie=$_SESSION["ls_codtienda"];
$ls_coduniadm=$_SESSION["ls_coduniad"];
$ls_item=$_SESSION["ls_item"];
$ls_codcaja=$_SESSION["ls_codcaj"];
$ls_codusu=$_SESSION["la_logusr"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Generar Orden de Entrega</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<script type="text/javascript" language="javascript"    src="../shared/js/js_intra/datepickercontrol.js"></script>
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
.Estilo3 {color: #6699CC}
-->
</style><meta http-equiv="Content-Type" content="text/html; charset="></head>
<body link="#006699" vlink="#006699" alink="#006699" >
<?Php
/****************************************************************/
/* FECHA: 13/10/2010                      				        */
/* AUTOR: ING. NELSON BARRAEZ 	  								*/
/*ACTUALIZADO POR:     FECHA:13/10/2010                         */
/****************************************************************/
//********************************************         SEGURIDAD       ****************************************************
require_once("../shared/class_folder/sigesp_c_seguridad.php");
$io_seguridad= new sigesp_c_seguridad();
$arre=$_SESSION["la_empresa"];//
$ls_formalibre=$_SESSION["ls_formalibre"];
$ls_empresa=$arre["codemp"];
$ls_logusr=$_SESSION["la_logusr"];
$ls_sistema="SFC";
$ls_ventanas="sigesp_sfc_p_ordendeentrega.php";
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
require_once("class_folder/sigesp_sfc_c_ordenentrega.php");
require_once("class_folder/sigesp_sfc_c_factura.php");
require_once("class_folder/sigesp_sfc_c_cobranza.php");
require_once("class_folder/sigesp_sfc_c_nota.php");
require_once("class_folder/sigesp_sfc_c_secuencia.php");
require_once("class_folder/class_funciones_sfc.php");
$io_funciones_sfc=new class_funciones_sfc();
$io_evalform=new evaluate_formula();
$io_secuencia=new sigesp_sfc_c_secuencia();
$io_funcsob=new sigesp_sob_c_funciones_sob();
$io_evalform=new evaluate_formula();
$io_grid=new grid_param();
$io_msg=new class_mensajes();
$io_function=new class_funciones();
$io_ordenentrega=new  sigesp_sfc_c_ordenentrega();
$io_factura=new  sigesp_sfc_c_factura();
$ls_estcajero=$io_factura->uf_validar_cajero($ls_codusu);
$io_seccon=new sigesp_sfc_c_secuencia();
$ls_ubicacion=$io_ordenentrega->uf_select_ubicacion($ls_codtie);
$ls_codemp=$la_datemp["codemp"];
/**************   GRID   DETALLES   FACTURA   *******************/
$ls_tituloconcepto="Detalle Productos";
$li_anchoconcepto=600;
$ls_nametable="grid2";
$la_columconcepto[1]="C&oacute;digo";
$la_columconcepto[2]="Descripci&oacute;n";
$la_columconcepto[3]="Almac&eacute;n";
$la_columconcepto[4]="Proveedor";
$la_columconcepto[5]="Precio Unitario";
$la_columconcepto[6]="IVA";
$la_columconcepto[7]="Cantidad";
$la_columconcepto[8]="Total";
$la_columconcepto[9]="Edici&oacute;n";
/*******  GRID    DETALLES     Insumos  ********************/
$ls_tituloinsumos="Insumos de Traslado";
$li_anchoinsumos=970;
$la_columinsumos[1]="C&oacute;digo";
$la_columinsumos[2]="Descripci&oacute;n";
$la_columinsumos[3]="Almac&eacute;n";
$la_columinsumos[4]="Precio Unit.";
$la_columinsumos[5]="IVA";
$la_columinsumos[6]="Cantidad";
$la_columinsumos[7]="Total";
$la_columinsumos[8]="Edici&oacute;n";
//**********************SUBMIT*******************************************
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion     =$_POST["operacion"];	
	$ls_codordent     =$_POST["hidcodordent"];
	$ls_numordent     =$_POST["txtnumord"];
	$ldt_fecemiord    =$_POST["txtfecha"];
	$ls_numordcom     =$_POST["txtnumordcom"];
	$ls_numorddes     =$_POST["txtnumdespacho"];
	$ls_numguiasada   =$_POST["txtnumguiasada"];
	$ls_codcli        =$_POST["txtcodcliente"];
	$ls_nomcli        =$_POST["txtnomcliente"];
	$ls_codptocol     =$_POST["txtcodptocol"];
	$ls_denptocol     =$_POST["txtdenptocol"];
	$ls_percontptocol =$_POST["txtpersonacontacto"];
	$ls_telcontptocol =$_POST["txttelefono"];
	$ls_dirptocol     =$_POST["txtdirptocol"];
	$ls_observacion   =$_POST["txtobservacion"];
	$ldec_subtotalpro =$_POST["txtsubtotalpro"];
	$ldec_totalpro    =$_POST["txttotalpro"];
	$ldec_descuentos  =$_POST["txtdescuentos"];
	$ldec_monfle      =$_POST["txtfletes"];
	$ldec_subtotal2   =$_POST["txtsubtotal2"];
	$ldec_monexonerado=$_POST["txtexonerado"];
	$ldec_monexento   =$_POST["txtexento"];
	$ldec_monbaseimp  =$_POST["txtbaseimp"];
	$ldec_totalgeneral=$_POST["txttotalgeneral"];
	$ldec_iva1        =$_POST["txtiva1"];
	$ldec_iva2        =$_POST["txtiva2"];
	$li_filasinsumos  =$_POST["filasinsumos"];
	$ls_pagotransp    =$_POST["rbpagoflete"];
	$ldt_fecdevinsumos=$_POST["txtfecdevinsumos"];
	$ls_codprovtrans  =$_POST["txtcodpro"];
	$ls_nomprotrans   =$_POST["txtnompro"];;
	$ls_riftransporte =$_POST["txtrifpro"];
	$ls_cedconductor  =$_POST["txtcedconductor"];
	$ls_nomconductor  =$_POST["txtnombreconductor"];
	$ldt_fecsalida    =$_POST["txtfecsalida"];
	$ldt_horasalida   =$_POST["txthorasalida"];
	$ls_desvehiculo   =$_POST["txtdesvehiculo"];
	$ls_placaveh      =$_POST["txtplacavehiculo"];
	$ls_placabatea    =$_POST["txtplacabatea"];
	$ls_codestordent  =$_POST["hidcodestordent"];
	$ls_codmotordent   =$_POST["cmbmotivoentrega"];
	if($ls_pagotransp=='S')
	{
		$lb_selfleteno = "";
		$lb_selfletesi = "checked";
		$lb_disabled   = "";
		$ls_emppagtransp="t";
	}
	else
	{
		$lb_selfleteno = "checked";
		$lb_selfletesi = "";
		$lb_disabled   = "";
		$ls_emppagtransp="f";
	}
	$ls_estatus	=$_POST["hidstatus"];
}
else
{
	$ls_operacion  ="ue_nuevo";
}
if($ls_operacion=="ue_guardar")//Operacion de registrar o actualizar la orden de entrega
{
   /* $la_empresa=$_SESSION["la_empresa"];
	$ls_codcaj=$_SESSION["ls_codcaj"];
	$ls_prefijo=$_SESSION["ls_preordent"];
	$ls_serie=$_SESSION["ls_serordent"];
	$io_secuencia->uf_ver_secuencia($ls_codcaj."ord",&$ls_secuencia);
	$ls_secuencia=$io_function->uf_cerosizquierda($ls_secuencia,16);
	$ls_codordent=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);
	$io_secuencia->uf_actualizar_secuencia($ls_codcaj."ord",$ls_correlativo);*/
	/*if ($ls_formalibre=='S')
	{
		$ls_sercon=$_SESSION["sercon"];
		$io_seccon->uf_ver_secuencia($ls_codcaj."con",&$ls_seccon);
		$ls_seccon=$io_function->uf_cerosizquierda($ls_seccon,25);
		$ls_numcon=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_seccon);
	}*/
	$io_ordenentrega->io_sql->begin_transaction();
	$lb_valido=$io_ordenentrega->uf_guardar_orden_entrega($ls_codtie,$ls_codcaja,$ls_codordent,$ls_numordent,$ldt_fecemiord,$ls_numordcom,$ls_numorddes,$ls_numguiasada,$ls_codcli,
									 $ls_codptocol,$ls_percontptocol,$ls_telcontptocol,$ls_dirptocol,$ls_observacion,$ldt_fecsalida,$ls_codtransp,
									 $ldec_descuentos,$ls_emppagtransp,$ls_placaveh,$ls_placabatea,$ldt_fecdevinsumos,$ls_codusu,$ls_codestordent,$ls_codmotordent,$ldec_monfle,
									 $ldec_monexento,$ldec_monexonerado,$ldec_monbaseimp,$ldec_totalgeneral);	
	if($lb_valido)								 
	{
		$li_totalproductos=$_POST["filasconcepto"];
		print $li_totalproductos;
		for($li_i=1;$li_i<$li_totalproductos;$li_i++)
		{
			$ls_codpro=$_POST["txtcodart".$li_i];//COdigo producto
			$ls_denpro=$_POST["txtdenart".$li_i];
			$ldec_prepro=$_POST["txtprepro".$li_i];
			$ldec_prepro=str_replace(",",".",str_replace(".","",$ldec_prepro));
			$ldec_canpro=$_POST["txtcanpro".$li_i];
			$ldec_canpro=str_replace(",",".",str_replace(".","",$ldec_canpro));			
			$ls_totpro=$_POST["txttotpro".$li_i];
			$ldec_porcar=$_POST["txtporcar".$li_i];
			$ldec_porcar=str_replace(",",".",str_replace(".","",$ldec_porcar));
			$ls_moncar=$_POST["txtmoncar".$li_i];
			$ls_codalm=$_POST["txtcodalm".$li_i];
			$ls_desalm=$_POST["txtdesalm".$li_i];
			$ls_existe=$_POST["txtexiste".$li_i];
			$ls_cod_pro=$_POST["txtcod_pro".$li_i];//COdigo proveedor
			$ls_nompro=$_POST["txtnompro".$li_i];
			$ldec_cospro=$_POST["txtcosto".$li_i];
			$ldec_cospro=str_replace(",",".",str_replace(".","",$ldec_cospro));
			$lb_valido=$io_ordenentrega->uf_insert_dt_ordenentrega($ls_codordent,$ls_codtie,'P',$ls_cod_pro,$ls_codpro,$ldec_porcar,$ldec_canpro,$ldec_prepro,'',
								 					   '1900-01-01',$ls_codalm,$ldec_cospro,'',0.00);
		}
	}
	if($lb_valido)								 
	{
		$li_totalinsumos=$_POST["filasinsumos"];
		for($li_i=1;$li_i<$li_totalinsumos;$li_i++)
		{
			$ls_codinsumo=$_POST["txtcodinsumo".$li_i];
			$ls_deninsumo=$_POST["txtdeninsumo".$li_i];
			$ls_codalminsumo=$_POST["txtcodalminsumo".$li_i];
			$ls_denalminsumo=$_POST["txtdesalminsumo".$li_i];
			$ldec_preinsumo=$_POST["txtpreinsumo".$li_i];
			$ldec_preinsumo=str_replace(",",".",str_replace(".","",$ldec_preinsumo));
			$ldec_caninsumo=$_POST["txtcaninsumo".$li_i];
			$ldec_caninsumo=str_replace(",",".",str_replace(".","",$ldec_caninsumo));
			$ldec_totinsumo=$_POST["txttotinsumo".$li_i];
			$ldec_existeinsumo=$_POST["txtexisteinsumo".$li_i];
			$ldec_costoinsumo=$_POST["txtcostoinsumo".$li_i];
			$ldec_costoinsumo=str_replace(",",".",str_replace(".","",$ldec_costoinsumo));
			$ldec_porcarinsumo=$_POST["txtporcarinsumo".$li_i];
			$ldec_porcarinsumo=str_replace(",",".",str_replace(".","",$ldec_porcarinsumo));
			$ldec_moncarinsumo=$_POST["txtmoncarinsumo".$li_i];
			$ls_codproinsumo=$_POST["txtcod_proinmsumo".$li_i];
			$lb_valido=$io_ordenentrega->uf_insert_dt_ordenentrega($ls_codordent,$ls_codtie,'I',$ls_cod_proinsumo,$ls_codinsumo,$ldec_porcarinsumo,$ldec_caninsumo,$ldec_preinsumo,'',
								 					   '1900-01-01',$ls_codalminsumo,$ldec_costoinsumo,'',0.00);
		}
	}
	uf_cargar_insumos(&$li_filasinsumos,&$la_objectinsumos,$io_msg,$ls_item,false,false,$ls_estfaccon,$ls_estatus,$ls_codordent,$ls_codtie);
	uf_cargar_conceptos(&$li_filasconcepto,&$la_objectconcepto,$io_msg,$ls_item,false,false,$ls_estfaccon,$ls_estatus,$ls_codordent,$ls_codtie);

	if($lb_valido)
	{
		$ls_estatus="R";//R=registrado;
		$io_ordenentrega->io_sql->commit();
		$io_msg->message("La orden fue registrada satisfactoriamente");
	}
	else
	{
		$io_msg->message($io_ordenentrega->io_mgsc);
		$io_ordenentrega->io_sql->rollback();
	}
}
if($ls_operacion=="ue_anular")
{
	
}
if($ls_operacion=="ue_nuevo")
{
	$ls_operacion  ="";
	$ls_estatus    ="N";
	$ls_codmotordent="";
	$ls_numordent  ="";
	$ldt_fecemiord =date("d-m-Y");
	$ls_numordcom  ="";
	$ls_numorddes  ="";
	$ls_numguiasada="";
	$ls_codcli     ="";
	$ls_nomcli     ="";
	$ls_codptocol  ="";
	$ls_denptocol  ="";
	$ls_percontptocol ="";
	$ls_telcontptocol ="";
	$ls_dirptocol  ="";
	$ls_observacion="";
	$ldec_subtotalpro ="0,00";
	$ldec_totalpro ="0,00";
	$ldec_descuentos="0,00";
	$ldec_monfle   ="0,00";
	$ldec_subtotal2="0,00";
	$ldec_monexonerado="0,00";
	$ldec_monexento="0,00";
	$ldec_monbaseimp="0,00";
	$ldec_iva1     ="0,00";
	$ldec_iva2     ="0,00";
	$ldec_totinsumos="0,00";
	$lb_selfleteno   ="checked";
	$lb_selfletesi   ="";
	$lb_disabled     ="readonly";
	$ldt_fecdevinsumos=date("d-m-Y");
	$ls_codprovtrans="";
	$ls_nomprotrans="";
	$ls_riftransporte="";
	$ls_cedconductor="";
	$ls_nomconductor="";
	$ldt_fecsalida=date("d-m-Y");
	$ldt_horasalida="";
	$ls_desvehiculo="";
	$ls_placaveh="";
	$ls_placabatea="";
	$ls_codestordent='0001';
	//**********************************Grid Concepto*******************************************
	$li_filasconcepto=1;
    $la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodart".$li_filasconcepto." type=text id=txtcodart".$li_filasconcepto." class=sin-borde size=21 style= text-align:center readonly><input name=txtexiste".$li_filasconcepto." type=hidden id=txtexiste".$li_filasconcepto.">";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenart".$li_filasconcepto." type=text id=txtdenart".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][3]="<input name=txtdesalm".$li_filasconcepto." type=text id=txtdesalm".$li_filasconcepto." class=sin-borde size=20 maxlength=255 style= text-align:center readonly><input name=txtcodalm".$li_filasconcepto." type=hidden id=txtcodalm".$li_filasconcepto." >";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txtnompro".$li_filasconcepto." type=text id=txtnompro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly><input name=txtcod_pro".$li_filasconcepto." type=hidden id=txtcod_pro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly>";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txtprepro".$li_filasconcepto." type=text id=txtprepro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly><input name=txtcosto".$li_filasconcepto." type=hidden id=txtcosto".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][6]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][7]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right>";
	$la_objectconcepto[$li_filasconcepto][8]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly>";
	$la_objectconcepto[$li_filasconcepto][9]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style=text-align:center size=5 readonly>";


	//**********************************Grid Insumos de traslado**************************************
	$li_filasinsumos=1;
    $la_objectinsumos[$li_filasinsumos][1]="<input name=txtcodinsumo".$li_filasinsumos." type=text id=txtcodinsumo".$li_filasinsumos." class=sin-borde size=21 style= text-align:center readonly><input name=txtexisteinsumo".$li_filasinsumos." type=hidden id=txtexisteinsumo".$li_filasinsumos.">";
	$la_objectinsumos[$li_filasinsumos][2]="<input name=txtdeninsumo".$li_filasinsumos." type=text id=txtdeninsumo".$li_filasinsumos." class=sin-borde size=45 style= text-align:left readonly><input name=txtcod_proinsumo".$li_filasinsumos." type=hidden id=txtcod_proinsumo".$li_filasinsumos.">";
	$la_objectinsumos[$li_filasinsumos][3]="<input name=txtdesalminsumo".$li_filasinsumos." type=text id=txtdesalminsumo".$li_filasinsumos." class=sin-borde size=20 style= text-align:right readonly><input name=txtcodalminsumo".$li_filasinsumos." type=hidden id=txtcodalminsumo".$li_filasinsumos.">";
	$la_objectinsumos[$li_filasinsumos][4]="<input name=txtpreinsumo".$li_filasinsumos." type=text id=txtpreinsumo".$li_filasinsumos." class=sin-borde size=10 style= text-align:right readonly><input name=txtcostoinsumo".$li_filasinsumos." type=hidden id=txtcostoinsumo".$li_filasinsumos." class=sin-borde style= text-align:center readonly>";
	$la_objectinsumos[$li_filasinsumos][5]="<input name=txtporcarinsumo".$li_filasinsumos." type=text id=txtporcarinsumo".$li_filasinsumos." class=sin-borde size=2 style= text-align:right><input name=txtmoncarinsumo".$li_filasinsumos." type=hidden id=txtmoncarinsumo".$li_filasinsumos." class=sin-borde style= text-align:center readonly>";
	$la_objectinsumos[$li_filasinsumos][6]="<input name=txtcaninsumo".$li_filasinsumos." type=text id=txtcaninsumo".$li_filasinsumos." class=sin-borde size=10 style= text-align:right>";
	$la_objectinsumos[$li_filasinsumos][7]="<input name=txttotinsumo".$li_filasinsumos." type=text id=txttotinsumo".$li_filasinsumos." class=sin-borde size=12 style= text-align:right readonly>";
	$la_objectinsumos[$li_filasinsumos][8]="";
	$ls_prefijo=$_SESSION["ls_preordent"];
	$ls_serie=$_SESSION["ls_serordent"];
	$ls_codordent=$io_funciones_sfc->uf_generer_consecutivo("siv_orden_entrega","codordent",$ls_prefijo,$ls_serie,25);
}
function uf_cargar_conceptos(&$ai_filasconcepto,&$aa_objectconcepto,$ao_msg,$ai_item,$ab_agregar,$ab_remover,$as_estfaccon,$as_estatus,$as_codordent,$as_codtienda)
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//  Function   : uf_cargar_conceptos
	// Description : Metodo que se encarga de realizar el llenado del arreglo de objetos a mostrar en el grid, el mismo 
	//               es utilizado para adicion de registros y para eliminacion de registros asi como para el llenado 
	//				 sin cambios del arreglo con el manejo de suiches  y condicionales segun el caso.
	//  Parametros : $ai_filasconcepto  - Numero de filas totales para el grid (Siempre habra una adicional que va en blanco)
	//				 $aa_objectconcepto	- Arreglo contentivo de los objetos que formaran parte del grid de detalle de Conceptos
	//				 $ao_msg           	- Objeto de la clase mensajes.
	//				 $ai_item           - Numero maximo de items que pueden ser agregados al detalle.
	//				 $ab_agregar        - Variable booleana que indica si la accion a realizar es de agregar un item nuevo.
	//				 $ab_remover        - Variable booleana que indica si la accion a realizar es de remover un determinado item.
	//				 $as_estfaccon      - Estatus del registro actual.
	//				 $as_estatus        - Estatus que indica si la orden ya esta almacenada
	//				 $as_codordent      - Codigo de la orden de entrega
	//				 $as_codtienda      - Codigo de la tienda
	//	Desarrollado por : Ing. Nelson Barráez                         Fecha de Creacion: 19-10-2010
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////						
	$ai_filasconcepto=$_POST["filasconcepto"];
	if($ab_agregar)
	{	$ai_filasconcepto++;	}
	if($ab_remover)
	{	
		$li_removerconcepto=$_POST["hidremoverconcepto"];	
		if($as_estatus=="R")	
		{
			$lb_valido=$io_ordenentrega->uf_eliminar_dt_orden_entrega($as_codordent,$as_codtienda,'P');
		}
	}	
	$li_temp=0;
	for($li_i=1;$li_i<$ai_filasconcepto;$li_i++)
	{
		if ($li_i<=$ai_item)
		{
			$ls_codpro=$_POST["txtcodart".$li_i];
			$ls_denpro=$_POST["txtdenart".$li_i];
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
			if(!$ab_remover)
			{
				$aa_objectconcepto[$li_i][1]="<input name=txtcodart".$li_i." type=text id=txtcodart".$li_i." value='".$ls_codpro."' class=sin-borde size=21 style= text-align:center readonly><input name=txtexiste".$li_i." type=hidden id=txtexiste".$li_i." value='".$ls_existe."'>";
				$aa_objectconcepto[$li_i][2]="<input name=txtdenart".$li_i." type=text id=txtdenart".$li_i." value='".$ls_denpro."' class=sin-borde size=45 style= text-align:left readonly>";
				$aa_objectconcepto[$li_i][3]="<input name=txtdesalm".$li_i." type=text id=txtdesalm".$li_i." value='".$ls_desalm."' class=sin-borde size=20 maxlength=255 style= text-align:center readonly><input name=txtcodalm".$li_i." type=hidden id=txtcodalm".$li_i." value='".$ls_codalm."' >";
				$aa_objectconcepto[$li_i][4]="<input name=txtnompro".$li_i." type=text id=txtnompro".$li_i." value='".$ls_nompro."' class=sin-borde size=10 style= text-align:right readonly><input name=txtcod_pro".$li_i." type=hidden id=txtcod_pro".$li_i." value='".$ls_cod_pro."' class=sin-borde size=10 style= text-align:right readonly>";
				$aa_objectconcepto[$li_i][5]="<input name=txtprepro".$li_i." type=text id=txtprepro".$li_i." value='".$ls_prepro."' class=sin-borde size=10 style= text-align:right readonly><input name=txtcosto".$li_i." type=hidden id=txtcosto".$li_i." class=sin-borde value='".$ls_costo."' style= text-align:center readonly>";
				$aa_objectconcepto[$li_i][6]="<input name=txtporcar".$li_i." type=text id=txtporcar".$li_i." value='".$ls_porcar."' class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_i." type=hidden id=txtmoncar".$li_i." value='".$ls_moncar."' class=sin-borde style= text-align:center readonly>";
				$aa_objectconcepto[$li_i][7]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=10 style= text-align:right onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=javascript:ue_calcular_total_fila('$li_i');>";
				$aa_objectconcepto[$li_i][8]="<input name=txttotpro".$li_i." type=text id=txttotpro".$li_i." value='".$ls_totpro."' class=sin-borde size=12 style= text-align:right readonly>";
				// Si la factura es nueva se habilita la opcion de eliminar en edicion
				if ($as_estfaccon=="")
				{
					$aa_objectconcepto[$li_i][9]="<a href=javascript:ue_removerconcepto(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
				}
				else
				{
					$aa_objectconcepto[$li_i][9]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center readonly>";
				}
				if($as_estatus=="R")	
				{
					$lb_valido=$io_ordenentrega->uf_insert_dt_ordenentrega($as_codordent,$as_codtienda,'P',$ls_cod_pro,$ls_codpro,str_replace(",",".",str_replace(".","",$ldec_porcar)),str_replace(",",".",str_replace(".","",$ldec_canpro)),str_replace(",",".",str_replace(".","",$ldec_prepro)),'',
								 					   '1900-01-01',$ls_codalm,str_replace(",",".",str_replace(".","",$ldec_cospro)),'',0.00);
				}	
			}
			else
			{
				if ($li_i!=$li_removerconcepto)//Si la actual es distinta a la fila a remover la agrego al grid
				{
					$li_temp=$li_temp+1;
					$aa_objectconcepto[$li_temp][1]="<input name=txtcodart".$li_temp." type=text id=txtcodart".$li_temp." value='".$ls_codpro."' class=sin-borde size=21 style= text-align:center readonly><input name=txtexiste".$li_temp." type=hidden id=txtexiste".$li_temp." value='".$ls_existe."'>";
					$aa_objectconcepto[$li_temp][2]="<input name=txtdenart".$li_temp." type=text id=txtdenart".$li_temp." value='".$ls_denpro."' class=sin-borde size=45 style= text-align:left readonly>";
					$aa_objectconcepto[$li_temp][3]="<input name=txtdesalm".$li_temp." type=text id=txtdesalm".$li_temp." value='".$ls_desalm."' class=sin-borde size=20 maxlength=255 style= text-align:center readonly><input name=txtcodalm".$li_temp." type=hidden id=txtcodalm".$li_temp." value='".$ls_codalm."'>";
					$aa_objectconcepto[$li_temp][4]="<input name=txtnompro".$li_temp." type=text id=txtnompro".$li_temp." value='".$ls_nompro."' class=sin-borde size=10 style= text-align:right readonly><input name=txtcod_pro".$li_temp." type=hidden id=txtcod_pro".$li_temp." value='".$ls_cod_pro."' class=sin-borde size=10 style= text-align:right readonly>";
					$aa_objectconcepto[$li_temp][5]="<input name=txtprepro".$li_temp." type=text id=txtprepro".$li_temp." value='".$ls_prepro."' class=sin-borde size=10 style= text-align:right readonly><input name=txtcosto".$li_temp." type=hidden id=txtcosto".$li_temp." class=sin-borde value='".$ls_costo."' style= text-align:center readonly>";
					$aa_objectconcepto[$li_temp][6]="<input name=txtporcar".$li_temp." type=text id=txtporcar".$li_temp." value='".$ls_porcar."' class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_temp." type=hidden id=txtmoncar".$li_temp." value='".$ls_moncar."' class=sin-borde style= text-align:center readonly>";
					$aa_objectconcepto[$li_temp][7]="<input name=txtcanpro".$li_temp." type=text id=txtcanpro".$li_temp." value='".$ls_canpro."' class=sin-borde size=10 style= text-align:right onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=javascript:ue_calcular_total_fila('$li_temp');>";
					$aa_objectconcepto[$li_temp][8]="<input name=txttotpro".$li_temp." type=text id=txttotpro".$li_temp." value='".$ls_totpro."' class=sin-borde size=12 style= text-align:right readonly>";
					// Si la factura es nueva se habilita la opcion de eliminar en edicion
					if ($as_estfaccon=="")
					{
						$aa_objectconcepto[$li_temp][9]="<a href=javascript:ue_removerconcepto(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
					}
					else
					{
						$aa_objectconcepto[$li_temp][9]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center readonly>";
					}
 	 				if($as_estatus=="R")	
					{
						$lb_valido=$io_ordenentrega->uf_insert_dt_ordenentrega($as_codordent,$as_codtienda,'P',$ls_cod_pro,$ls_codpro,str_replace(",",".",str_replace(".","",$ldec_porcar)),str_replace(",",".",str_replace(".","",$ldec_canpro)),str_replace(",",".",str_replace(".","",$ldec_prepro)),'',
								 					   '1900-01-01',$ls_codalm,str_replace(",",".",str_replace(".","",$ldec_cospro)),'',0.00);
					}
				}
			}			
		}
		else
		{
			$io_msg->message("Ha excedido el limite de carga de productos");
			$ai_filasconcepto=$ai_filasconcepto-1;
		}

	}//fin del for
	if($ab_remover){$ai_filasconcepto--;}
	$aa_objectconcepto[$ai_filasconcepto][1] = "<input name=txtcodart".$ai_filasconcepto." type=text id=txtcodart".$ai_filasconcepto." class=sin-borde size=21 style= text-align:center readonly><input name=txtexiste".$ai_filasconcepto." type=hidden id=txtexiste".$ai_filasconcepto.">";
	$aa_objectconcepto[$ai_filasconcepto][2] = "<input name=txtdenart".$ai_filasconcepto." type=text id=txtdenart".$ai_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
	$aa_objectconcepto[$ai_filasconcepto][3] = "<input name=txtdesalm".$ai_filasconcepto." type=text id=txtdesalm".$ai_filasconcepto." class=sin-borde size=20 maxlength=255 style= text-align:center readonly><input name=txtcodalm".$ai_filasconcepto." type=hidden id=txtcodalm".$ai_filasconcepto." >";
	$aa_objectconcepto[$ai_filasconcepto][4] = "<input name=txtnompro".$ai_filasconcepto." type=text id=txtnompro".$ai_filasconcepto." class=sin-borde size=10 style= text-align:right readonly><input name=txtcod_pro".$ai_filasconcepto." type=hidden id=txtcod_pro".$ai_filasconcepto." class=sin-borde size=10 style= text-align:right readonly>";
	$aa_objectconcepto[$ai_filasconcepto][5] = "<input name=txtprepro".$ai_filasconcepto." type=text id=txtprepro".$ai_filasconcepto." class=sin-borde size=10 style= text-align:right readonly><input name=txtcosto".$ai_filasconcepto." type=hidden id=txtcosto".$ai_filasconcepto." class=sin-borde style= text-align:center readonly>";
	$aa_objectconcepto[$ai_filasconcepto][6] = "<input name=txtporcar".$ai_filasconcepto." type=text id=txtporcar".$ai_filasconcepto." class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$ai_filasconcepto." type=hidden id=txtmoncar".$ai_filasconcepto." class=sin-borde style= text-align:center readonly>";
	$aa_objectconcepto[$ai_filasconcepto][7] = "<input name=txtcanpro".$ai_filasconcepto." type=text id=txtcanpro".$ai_filasconcepto." class=sin-borde size=10 style= text-align:right>";
	$aa_objectconcepto[$ai_filasconcepto][8] = "<input name=txttotpro".$ai_filasconcepto." type=text id=txttotpro".$ai_filasconcepto." class=sin-borde size=12 style= text-align:right readonly>";
	$aa_objectconcepto[$ai_filasconcepto][9] = "";
}
function uf_cargar_insumos(&$ai_filasinsumos,&$aa_objectinsumos,$ao_msg,$ai_item,$ab_agregar,$ab_remover,$as_estfaccon,$as_estatus,$as_codordent,$as_codtienda)
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//  Function   : uf_cargar_insumos
	// Description : Metodo que se encarga de realizar el llenado del arreglo de objetos a mostrar en el grid, el mismo 
	//               es utilizado para adicion de registros y para eliminacion de registros, asi como para el llenado sin cambios del 
	//               arreglo con el manejo de suiches  y condicionales segun el caso.
	//  Parametros : $ai_filasinsumos   - Numero de filas totales para el grid (Siempre habra una adicional que va con objetos en blanco)
	//				 $aa_objectinsumos	- Arreglo contentivo de los objetos que formaran parte del grid de detalle de insumos de traslado
	//				 $ao_msg           	- Objeto de la clase mensajes.
	//				 $ai_item           - Numero maximo de items que pueden ser agregados al detalle.
	//				 $ab_agregar        - Variable booleana que indica si la accion a realizar es de agregar un item nuevo.
	//				 $ab_remover        - Variable booleana que indica si la accion a realizar es de remover un determinado item.
	//				 $as_estfaccon      - Estatus del registro actual.
	//				 $as_estatus        - Estatus que indica si la orden ya esta almacenada
	//				 $as_codordent      - Codigo de la orden de entrega
	//				 $as_codtienda      - Codigo de la tienda
	//	Desarrollado por : Ing. Nelson Barráez                         Fecha de Creacion: 19-10-2010
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////						

	$ai_filasinsumos=$_POST["filasinsumos"];
	if($ab_agregar)
	{	$ai_filasinsumos++;  }
	if($ab_remover)
	{	
		$li_removerinsumos=$_POST["hidremoverinsumos"];  
		if($as_estatus=="R")	
		{
			$lb_valido=$io_ordenentrega->uf_eliminar_dt_orden_entrega($as_codordent,$as_codtienda,'I');
		}
	}	
	$li_temp=0;
	for($li_i=1;$li_i<$ai_filasinsumos;$li_i++)
	{
		$ls_codinsumo=$_POST["txtcodinsumo".$li_i];
		$ls_deninsumo=$_POST["txtdeninsumo".$li_i];
		$ls_codalminsumo=$_POST["txtcodalminsumo".$li_i];
		$ls_denalminsumo=$_POST["txtdesalminsumo".$li_i];
		$ldec_preinsumo=$_POST["txtpreinsumo".$li_i];
		$ldec_caninsumo=$_POST["txtcaninsumo".$li_i];
		$ldec_totinsumo=$_POST["txttotinsumo".$li_i];
		$ldec_existeinsumo=$_POST["txtexisteinsumo".$li_i];
		$ldec_costoinsumo=$_POST["txtcostoinsumo".$li_i];
		$ldec_porcarinsumo=$_POST["txtporcarinsumo".$li_i];
		$ldec_moncarinsumo=$_POST["txtmoncarinsumo".$li_i];
		$ls_codproinsumo=$_POST["txtcod_proinmsumo".$li_i];
		if(!$ab_remover)
		{
			$aa_objectinsumos[$li_i][1]="<input name=txtcodinsumo".$li_i." type=text id=txtcodinsumo".$li_i." value='".$ls_codinsumo."' class=sin-borde size=21 style= text-align:center readonly><input name=txtexisteinsumo".$li_i." type=hidden id=txtexisteinsumo".$li_i." value='".$ldec_existeinsumo."'>";
			$aa_objectinsumos[$li_i][2]="<input name=txtdeninsumo".$li_i." type=text id=txtdeninsumo".$li_i." value='".$ls_deninsumo."' class=sin-borde size=45 style= text-align:left readonly><input name=txtcod_proinsumo".$li_i." type=hidden id=txtcod_proinsumo".$li_i." value='".$ls_codproinsumo."'>";
			$aa_objectinsumos[$li_i][3]="<input name=txtdesalminsumo".$li_i." type=text id=txtdesalminsumo".$li_i." value='".$ls_denalminsumo."' class=sin-borde size=20 maxlength=255 style= text-align:left><input name=txtcodalminsumo".$li_i." type=hidden id=txtcodalminsumo".$li_i." value='".$ls_codalminsumo."'>";
			$aa_objectinsumos[$li_i][4]="<input name=txtpreinsumo".$li_i." type=text id=txtpreinsumo".$li_i." value='".$ldec_preinsumo."' class=sin-borde size=10 style= text-align:right readonly><input name=txtcostoinsumo".$li_i." type=hidden id=txtcostoinsumo".$li_i." value='".$ldec_costoinsumo."'>";
			$aa_objectinsumos[$li_i][5]="<input name=txtporcarinsumo".$li_i." type=text id=txtporcarinsumo".$li_i." value='".$ldec_porcarinsumo."' class=sin-borde size=2 style= text-align:right><input name=txtmoncarinsumo".$li_i." type=hidden id=txtmoncarinsumo".$li_i." value='".$ldec_moncarinsumo."'>";
			$aa_objectinsumos[$li_i][6]="<input name=txtcaninsumo".$li_i." type=text id=txtcaninsumo".$li_i." value='".$ldec_caninsumo."' class=sin-borde size=10 style= text-align:right onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=javascript:ue_calcular_total_insumo('$li_i');>";
			$aa_objectinsumos[$li_i][7]="<input name=txttotinsumo".$li_i." type=text id=txttotinsumo".$li_i." value='".$ldec_totinsumo."' class=sin-borde size=12 style= text-align:right readonly>";
			// Si la factura es nueva se habilita la opcion de eliminar en edicion
			if ($as_estfaccon=="")
			{
				$aa_objectinsumos[$li_i][8]="<a href=javascript:ue_removerinsumos(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
			}
			else
			{
				$aa_objectinsumos[$li_i][8]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center readonly>";
			}
			if($as_estatus=="R")	
			{
				$lb_valido=$io_ordenentrega->uf_insert_dt_ordenentrega($as_codordent,$as_codtienda,'I',$ls_cod_pro,$ls_codpro,str_replace(",",".",str_replace(".","",$ldec_porcar)),str_replace(",",".",str_replace(".","",$ldec_canpro)),str_replace(",",".",str_replace(".","",$ldec_prepro)),'',
											   '1900-01-01',$ls_codalm,str_replace(",",".",str_replace(".","",$ldec_cospro)),'',0.00);
			}
		}
		else
		{
			if ($li_i!=$li_removerinsumos)
			{
				$li_temp=$li_temp+1;
				$aa_objectinsumos[$li_temp][1]="<input name=txtcodinsumo".$li_temp." type=text id=txtcodinsumo".$li_temp." value='".$ls_codinsumo."' class=sin-borde size=21 style= text-align:center readonly><input name=txtexisteinsumo".$li_temp." type=hidden id=txtexisteinsumo".$li_temp." value='".$ldec_existeinsumo."'>";
				$aa_objectinsumos[$li_temp][2]="<input name=txtdeninsumo".$li_temp." type=text id=txtdeninsumo".$li_temp." value='".$ls_deninsumo."' class=sin-borde size=45 style= text-align:left readonly><input name=txtcod_proinsumo".$li_temp." type=hidden id=txtcod_proinsumo".$li_temp." value='".$ls_codproinsumo."'>";
				$aa_objectinsumos[$li_temp][3]="<input name=txtdesalminsumo".$li_temp." type=text id=txtdesalminsumo".$li_temp." value='".$ls_denalminsumo."' class=sin-borde size=20 maxlength=255 style= text-align:left><input name=txtcodalminsumo".$li_temp." type=hidden id=txtcodalminsumo".$li_temp." value='".$ls_codalminsumo."'>";
				$aa_objectinsumos[$li_temp][4]="<input name=txtpreinsumo".$li_temp." type=text id=txtpreinsumo".$li_temp." value='".$ldec_preinsumo."' class=sin-borde size=10 style= text-align:right readonly><input name=txtcostoinsumo".$li_temp." type=hidden id=txtcostoinsumo".$li_temp." value='".$ldec_costoinsumo."'>";
				$aa_objectinsumos[$li_temp][5]="<input name=txtporcarinsumo".$li_temp." type=text id=txtporcarinsumo".$li_temp." value='".$ldec_porcarinsumo."' class=sin-borde size=2 style= text-align:right><input name=txtmoncarinsumo".$li_temp." type=hidden id=txtmoncarinsumo".$li_temp." value='".$ldec_moncarinsumo."'>";
				$aa_objectinsumos[$li_temp][6]="<input name=txtcaninsumo".$li_temp." type=text id=txtcaninsumo".$li_temp." value='".$ldec_caninsumo."' class=sin-borde size=10 style= text-align:right onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=javascript:ue_calcular_total_insumo('$li_temp');>";
				$aa_objectinsumos[$li_temp][7]="<input name=txttotinsumo".$li_temp." type=text id=txttotinsumo".$li_temp." value='".$ldec_totinsumo."' class=sin-borde size=12 style= text-align:right readonly>";
				// Si la factura es nueva se habilita la opcion de eliminar en edicion
				if ($as_estfaccon=="")
				{
					$aa_objectinsumos[$li_temp][8]="<a href=javascript:ue_removerinsumos(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
				}
				else
				{
					$aa_objectinsumos[$li_temp][8]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center readonly>";
				}
				if($as_estatus=="R")	
				{
					$lb_valido=$io_ordenentrega->uf_insert_dt_ordenentrega($as_codordent,$as_codtienda,'I',$ls_cod_pro,$ls_codpro,str_replace(",",".",str_replace(".","",$ldec_porcar)),str_replace(",",".",str_replace(".","",$ldec_canpro)),str_replace(",",".",str_replace(".","",$ldec_prepro)),'',
												   '1900-01-01',$ls_codalm,str_replace(",",".",str_replace(".","",$ldec_cospro)),'',0.00);
				}
			}	
		}		
	}//fin del for
	if($ab_remover){$ai_filasinsumos--;}
	$aa_objectinsumos[$ai_filasinsumos][1]="<input name=txtcodinsumo".$ai_filasinsumos." type=text id=txtcodinsumo".$ai_filasinsumos." value='' class=sin-borde size=21 style= text-align:center readonly><input name=txtexisteinsumo".$ai_filasinsumos." type=hidden id=txtexisteinsumo".$ai_filasinsumos." value=''>";
	$aa_objectinsumos[$ai_filasinsumos][2]="<input name=txtdeninsumo".$ai_filasinsumos." type=text id=txtdeninsumo".$ai_filasinsumos." value='' class=sin-borde size=45 style= text-align:left readonly><input name=txtcod_proinsumo".$ai_filasinsumos." type=hidden id=txtcod_proinsumo".$ai_filasinsumos.">";
	$aa_objectinsumos[$ai_filasinsumos][3]="<input name=txtdesalminsumo".$ai_filasinsumos." type=text id=txtdesalminsumo".$ai_filasinsumos." value='' class=sin-borde size=20 maxlength=255 style= text-align:left><input name=txtcodalminsumo".$ai_filasinsumos." type=hidden id=txtcodalminsumo".$ai_filasinsumos." value=''>";
	$aa_objectinsumos[$ai_filasinsumos][4]="<input name=txtpreinsumo".$ai_filasinsumos." type=text id=txtpreinsumo".$ai_filasinsumos." value='' class=sin-borde size=10 style= text-align:right readonly><input name=txtcostoinsumo".$ai_filasinsumos." type=hidden id=txtcostoinsumo".$ai_filasinsumos." value=''>";
	$aa_objectinsumos[$ai_filasinsumos][5]="<input name=txtporcarinsumo".$ai_filasinsumos." type=text id=txtporcarinsumo".$ai_filasinsumos." value='' class=sin-borde size=2 style= text-align:right><input name=txtmoncarinsumo".$ai_filasinsumos." type=hidden id=txtmoncarinsumo".$ai_filasinsumos." class=sin-borde style= text-align:center readonly>";
	$aa_objectinsumos[$ai_filasinsumos][6]="<input name=txtcaninsumo".$ai_filasinsumos." type=text id=txtcaninsumo".$ai_filasinsumos." value='' class=sin-borde size=10 style= text-align:right>";
	$aa_objectinsumos[$ai_filasinsumos][7]="<input name=txttotinsumo".$ai_filasinsumos." type=text id=txttotinsumo".$ai_filasinsumos." value='' class=sin-borde size=12 style= text-align:right readonly>";
	$aa_objectinsumos[$ai_filasinsumos][8]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center readonly>";
}

if($ls_operacion=="ue_cargarconcepto")//Cargo los conceptos de facturacion
{
	uf_cargar_conceptos(&$li_filasconcepto,&$la_objectconcepto,$io_msg,$ls_item,true,false,$ls_estfaccon,$ls_estatus,$ls_codordent,$ls_codtie);
	uf_cargar_insumos(&$li_filasinsumos,&$la_objectinsumos,$io_msg,$ls_item,false,false,$ls_estfaccon,$ls_estatus,$ls_codordent,$ls_codtie);
}

if($ls_operacion=="ue_removerconcepto")//Remuevo los conceptos de facturacion
{
	uf_cargar_conceptos(&$li_filasconcepto,&$la_objectconcepto,$io_msg,$ls_item,false,true,$ls_estfaccon,$ls_estatus,$ls_codordent,$ls_codtie);
	uf_cargar_insumos(&$li_filasinsumos,&$la_objectinsumos,$io_msg,$ls_item,false,false,$ls_estfaccon,$ls_estatus,$ls_codordent,$ls_codtie);
}

if($ls_operacion=="ue_cargarinsumos")//Cargo los insumos de traslado
{
	 uf_cargar_insumos(&$li_filasinsumos,&$la_objectinsumos,$io_msg,$ls_item,true,false,$ls_estfaccon,$ls_estatus,$ls_codordent,$ls_codtie);
	 uf_cargar_conceptos(&$li_filasconcepto,&$la_objectconcepto,$io_msg,$ls_item,false,false,$ls_estfaccon,$ls_estatus,$ls_codordent,$ls_codtie);
}

if($ls_operacion=="ue_removerinsumos")//Remuevo los insumos de facturacion
{
	uf_cargar_insumos(&$li_filasinsumos,&$la_objectinsumos,$io_msg,$ls_item,false,true,$ls_estfaccon,$ls_estatus,$ls_codordent,$ls_codtie);
	uf_cargar_conceptos(&$li_filasconcepto,&$la_objectconcepto,$io_msg,$ls_item,false,false,$ls_estfaccon,$ls_estatus,$ls_codordent,$ls_codtie);
}
if($ls_operacion=="ue_cargar_dt")
{
	$io_ordenentrega->uf_cargar_dt_insumos(&$li_filasinsumos,&$la_objectinsumos,$ls_codordent,$ls_codtie);
	$io_ordenentrega->uf_cargar_dt_conceptos(&$li_filasconcepto,&$la_objectconcepto,$ls_codordent,$ls_codtie);
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
  <p>&nbsp;</p>
  <form name="form1" method="post"  action="" >
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
    <input name="operacion" type="hidden" id="operacion" >
    <table width="813" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
       <tr class="titulo-celdanew">
         <td colspan="4">Generar Orden de Entrega </td>
       </tr>
       <tr class="formato-blanco">
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
       </tr>
       <tr class="formato-blanco">
         <td height="22"><div align="right">Lugar de Emisi&oacute;n: </div></td>
         <td colspan="3" class="letras-negrita"><label>
           <?php print $ls_ubicacion;?>
           <input type="text" name="hidcodordent" id="hidcodordent" value="<?php print $ls_codordent;?>" size="50">
           <input name="hidcodestordent" type="hidden" id="hidcodestordent" value="<?php print $ls_codestordent;?>">
         </label></td>
       </tr>
       <tr class="formato-blanco">
         <td width="140" height="22"><div align="right">Nro. Orden de Entrega </div></td>
         <td width="303"><label>
           <input name="txtnumord" type="text" id="txtnumord" style="text-align:center" value="<?php print $ls_numordent;?>" maxlength="15">
         </label></td>
         <td width="144"><div align="right">Fecha</div></td>
         <td width="224"><label>
           <input name="txtfecha" type="text" id="txtfecha" style="text-align:center" value="<?php print $ldt_fecemiord;?>" maxlength="10" datepicker="true">
         </label></td>
       </tr>
       <tr class="formato-blanco">
         <td height="22"><div align="right">Nro. Orden de Compra </div></td>
         <td height="22"><label>
           <input name="txtnumordcom" type="text" id="txtnumordcom" style="text-align:center" value="<?php print $ls_numordcom;?>">
         </label></td>
         <td height="22"><div align="right">Nro. Orden de Despacho </div></td>
         <td height="22"><label>
           <input name="txtnumdespacho" type="text" id="txtnumdespacho" style="text-align:center" value="<?php print $ls_numorddes;?>">
         </label></td>
       </tr>
       <tr class="formato-blanco">
         <td height="22"><div align="right">Nro. Guia SADA </div></td>
         <td height="22"><label>
           <input name="txtnumguiasada" type="text" id="txtnumguiasada" style="text-align:center" value="<?php print $ls_numguiasada;?>">
         </label></td>
         <td height="22">&nbsp;</td>
         <td height="22">&nbsp;</td>
       </tr>
       <tr class="formato-blanco">
         <td height="22"><div align="right">Cliente</div></td>
         <td height="22" colspan="3"><label>
           <input name="txtcodcliente" type="text" id="txtcodcliente" style="text-align:center" value="<?php print $ls_codcli;?>" readonly="true">
           <a href="javascript:ue_cat_cliente();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar Cliente" width="15" height="15" border="0" longdesc="Buscar Cliente"></a>
           <input name="txtnomcliente" type="text" class="sin-borde" id="txtnomcliente" value="<?php print $ls_nomcli;?>" size="65" readonly="true">
         </label></td>
       </tr>
       <tr class="formato-blanco">
         <td height="22"><div align="right">Punto de Colocacion </div></td>
         <td height="22" colspan="3"><input name="txtcodptocol" type="text" id="txtcodptocol" style="text-align:center" value="<?php print $ls_codptocol;?>" readonly="true">
           <a href="javascript:ue_cat_puntos();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar Cliente" width="15" height="15" border="0" longdesc="Buscar Cliente"></a>
           <input name="txtdenptocol" type="text" class="sin-borde" id="txtdenptocol" value="<?php print $ls_denptocol;?>" size="65" readonly="true"></td>
       </tr>
       <tr class="formato-blanco">
         <td height="22"><div align="right">Persona Contacto </div></td>
         <td height="22"><label>
           <input name="txtpersonacontacto" type="text" id="txtpersonacontacto" value="<?php print $ls_percontptocol;?>" size="50" readonly>
         </label></td>
         <td height="22"><div align="right">Telefono</div></td>
         <td height="22"><label>
           <input name="txttelefono" type="text" id="txttelefono" style="text-align:center" value="<?php print $ls_telcontptocol;?>" readonly>
         </label></td>
       </tr>
       <tr class="formato-blanco">
         <td height="22"><div align="right">Direcci&oacute;n</div></td>
         <td height="22" colspan="3"><label>
           <input name="txtdirptocol" type="text" id="txtdirptocol" value="<?php print $ls_dirptocol;?>" size="138" readonly>
         </label></td>
       </tr>
       <tr class="formato-blanco">
         <td height="22"><div align="right">Observaciones</div></td>
         <td height="22" colspan="3"><textarea name="txtobservacion" cols="135" id="txtobservacion"><?php print $ls_observacion;?></textarea></td>
       </tr>
       <tr class="formato-blanco">
         <td height="22"><div align="right">Motivo de Traslado</div></td>
         <td height="22" colspan="3"><label>
           <select name="cmbmotivoentrega">
            <option value="">Seleccione...</option>
            <?Php
		     $ls_sql="SELECT codmotordent ,denmotordent
                        FROM siv_motivo_ordenentrega ";

			$rs_data=$io_ordenentrega->io_sql->select($ls_sql);
			if($rs_data==false)
			 {
			  
			 }
			 else
			 { 
			 		while($row=$io_ordenentrega->io_sql->fetch_row($rs_data))		 
					{
						 $ls_codigo=$row["codmotordent"];
						 $ls_denmot=$row["denmotordent"];
						 if ($ls_codmotordent==$ls_codigo)
						 {
							  print "<option value='$ls_codigo' selected>$ls_denmot</option>";
						 }
						 else
						 {
							  print "<option value='$ls_codigo'>$ls_denmot</option>";
						 }
					} 
			}
	        ?>
           </select>           
         </label></td>
       </tr>
       <tr class="formato-blanco">
         <td height="22" colspan="4"><table width="615" height="250" border="0" align="center" cellpadding="0" cellspacing="0">
           <tr class="formato-blanco">
             <td width="14" height="19">&nbsp;</td>
             <td width="593"><a href="javascript:ue_catproducto();"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:ue_catproducto('');">Agregar Detalle </a></td>
           </tr>
           <tr align="center" class="formato-blanco">
             <td height="11" colspan="2"><?php $io_grid->makegrid($li_filasconcepto,$la_columconcepto,$la_objectconcepto,$li_anchoconcepto,$ls_tituloconcepto,$ls_nametable);?></td>
             <input name="filasconcepto" type="hidden" id="filasconcepto" value="<? print $li_filasconcepto;?>">
             <input name="hidremoverconcepto" type="hidden" id="hidremoverconcepto" value="">
           </tr>
           <tr align="center" class="formato-blanco">
             <td height="22" colspan="2"><label>
               <div align="right">
                 Sub-Total(1)
                   <input name="txtsubtotalpro" type="text" id="txtsubtotalpro" value="<?php print $ldec_subtotalpro;?>" style="text-align:right" readonly>
               </div>
             </label></td>
           </tr>
           <tr align="center" class="formato-blanco">
             <td height="22" colspan="2"><label>
               <div align="right">Descuentos,Rebajas y Bonificaciones
                 <input name="txtdescuentos" type="text" id="txtdescuentos" value="<?php print $ldec_descuentos;?>" style="text-align:right" readonly>
               </div>
             </label></td>
           </tr>
           <tr align="center" class="formato-blanco">
             <td height="22" colspan="2"><label>
               <div align="right">Fletes
                 <input name="txtfletes" type="text" id="txtfletes" value="<?php print $ldec_monfle;?>" style="text-align:right" readonly>
               </div>
             </label></td>
           </tr>
           <tr align="center" class="formato-blanco">
             <td height="22" colspan="2"><label>
               <div align="right">Sub-Total(2)
                 <input name="txtsubtotal2" type="text" id="txtsubtotal2" value="<?php print $ldec_subtotal2;?>" style="text-align:right" readonly>
               </div>
             </label></td>
           </tr>
           <tr align="center" class="formato-blanco">
             <td height="22" colspan="2"><label>
               <div align="right">Monto Exento
                 <input name="txtexento" type="text" id="txtexento" value="<?php print $ldec_monexento;?>" style="text-align:right" readonly>
               </div>
             </label></td>
           </tr>
           <tr align="center" class="formato-blanco">
             <td height="22" colspan="2"><label>
               <div align="right">Monto Exonerado o no Grabado
                 <input name="txtexonerado" type="text" id="txtexonerado" value="<?php print $ldec_monexonerado;?>" style="text-align:right" readonly>
               </div>
             </label></td>
           </tr>
           <tr align="center" class="formato-blanco">
             <td height="22" colspan="2"><label>
               <div align="right">Monto Base Imponible
                 <input name="txtbaseimp" type="text" id="txtbaseimp" value="<?php print $ldec_monbaseimp;?>" style="text-align:right" readonly>
               </div>
             </label></td>
           </tr>
           <tr align="center" class="formato-blanco">
             <td height="22" colspan="2"><label>
               <div align="right"> IVA 12%
                 <input name="txtiva1" type="text" id="txtiva1" value="<?php print $ldec_iva1;?>" style="text-align:right" readonly>
               </div>
             </label></td>
           </tr>
           <tr align="center" class="formato-blanco">
             <td height="22" colspan="2"><label>
               <div align="right"> IVA 8%
                 <input name="txtiva2" type="text" id="txtiva2" value="<?php print $ldec_iva2;?>" style="text-align:right" readonly>
               </div>
             </label></td>
           </tr>
           <tr align="center" class="formato-blanco">
             <td height="22" colspan="2"><label>
               <div align="right"> Total
                 <input name="txttotalpro" type="text" id="txttotalpro" value="<?php print $ldec_totalpro;?>" style="text-align:right" readonly>
               </div>
             </label></td>
           </tr>
         </table></td>
       </tr>
       <tr class="formato-blanco">
         <td height="22" colspan="4"><table width="615" height="85" border="0" align="center" cellpadding="0" cellspacing="0">
           <tr class="formato-blanco">
             <td width="14" height="19">&nbsp;</td>
             <td width="593"><a href="javascript:ue_catproducto();"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:ue_catproducto('T');">Agregar Insumos de Traslado </a></td>
           </tr>
           <tr align="center" class="formato-blanco">
             <td height="11" colspan="2"><?php $io_grid->makegrid($li_filasinsumos,$la_columinsumos,$la_objectinsumos,$li_anchoinsumos,$ls_tituloinsumos,"grid3");?></td>
             <input name="filasinsumos" type="hidden" id="filasinsumos" value="<? print $li_filasinsumos;?>">
             <input name="hidremoverinsumos" type="hidden" id="hidremoverinsumos" value="">
           </tr>
           <tr align="center" class="formato-blanco">
             <td height="22" colspan="2"><label>
                 <div align="right"> Sub-Total
                   <input name="txtsubtotalinsumo" type="text" id="txtsubtotalinsumo" value="<?php print $ldec_subtotalinsumo;?>" style="text-align:right" readonly>
                 </div>
             </label></td>
           </tr>
           <tr align="center" class="formato-blanco">
             <td height="22" colspan="2"><label>
                 <div align="right"> IVA 12%
                   <input name="txtiva1insumo" type="text" id="txtiva1insumo" value="<?php print $ldec_iva1insumo;?>" style="text-align:right" readonly>
                 </div>
             </label></td>
           </tr>
           <tr align="center" class="formato-blanco">
             <td height="22" colspan="2"><label>
                 <div align="right"> IVA 8%
                   <input name="txtiva2insumo" type="text" id="txtiva2insumo" value="<?php print $ldec_iva2insumo;?>" style="text-align:right" readonly>
                 </div>
             </label></td>
           </tr>
           <tr align="center" class="formato-blanco">
             <td height="22" colspan="2"><label>
                 <div align="right"> Total
                   <input name="txttotalinsumo" type="text" id="txttotalinsumo" value="<?php print $ldec_totalinsumo;?>" style="text-align:right" readonly>
                 </div>
             </label></td>
           </tr>
           <tr align="center" class="formato-blanco">
             <td height="11" colspan="2"><label>
             </label></td>
           </tr>
         </table></td>
       </tr>
       <tr class="formato-blanco">
         <td height="64" colspan="4"><table width="864" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
           <tr>
             <td colspan="6" class="titulo-celdanew">Totales Generales </td>
           </tr>
           <tr class="celdas-blancas">
             <td width="90"><div align="right">Iva 12% </div></td>
             <td width="161" height="25"><input name="txtiva12" type="text" id="txtiva12" value="<?php print $ldec_totgeniva12;?>"  style="text-align:right" readonly></td>
             <td width="152"><div align="right">Iva 8% </div></td>
             <td width="152"><label>
               <input name="txtiva8" type="text" id="txtiva8" value="<?php print $ldec_totgeniva8;?>"  style="text-align:right" readonly>
             </label></td>
             <td width="105"><div align="right">Total</div></td>
             <td width="202"><label>
               <input name="txttotalgeneral" type="text" id="txttotalgeneral" value="<?php print $ldec_totalgeneral;?>"  style="text-align:right" readonly>
             </label></td>
           </tr>
         </table>
         <label></label></td>
       </tr>
       <tr class="formato-blanco">
         <td height="22" colspan="4">
		 <table width="784" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
           <tr class="titulo-celdanew">
             <td colspan="4">Transporte</td>
           </tr>
           <tr>
             <td width="146" height="22"><div align="right">Fecha de Devoluci&oacute;n de Insumos de Traslado </div></td>
             <td height="22" colspan="3"><label>
               <input name="txtfecdevinsumos" type="text" id="txtfecdevinsumos" value="<?php print $ldt_fecdevinsumos;?>" datepicker="true"  style="text-align:center">
             </label></td>
           </tr>
           <tr>
             <td height="22"><div align="right">CVAL paga flete?</div></td>
             <td height="22" colspan="3"><p>
               <label>
                 <input type="radio" name="rbpagoflete" value="S" <?php print $lb_selfletesi;?> onSelect="javascript:ue_habilitar();">
                 Si</label>              
               <label>
                 <input type="radio" name="rbpagoflete" value="N" <?php print $lb_selfleteno;?>>
                 No</label>
               <br>
             </p></td>
           </tr>
           <tr>
             <td height="22"><div align="right">Empresa Transportista 
               <label></label>
</div></td>
             <td height="22" colspan="3"><input name="txtcodpro" type="text" id="txtcodpro" value="<?php print $ls_codprovtrans;?>" size="12" readonly>
               <a href="javascript:ue_cat_proveedor_transporte();" ><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar Transporte" name="lupa" width="15" height="15" border="0" longdesc="Buscar Transporte"></a>
             <label>
             <input name="txtnompro" type="text" class="sin-borde" id="txtnompro" value="<?php print $ls_nomprotrans;?>" size="85" readonly>
             </label></td>
           </tr>
           <tr>
             <td height="22"><div align="right">RIF Transporte </div></td>
             <td height="22" colspan="3"><label>
               <input name="txtrifpro" type="text" id="txtrifpro" value="<?php print $ls_riftransporte;?>" readonly>
             </label></td>
           </tr>
           <tr>
             <td height="22"><div align="right">Cedula del Conductor </div></td>
             <td width="192" height="22"><label>
               <input name="txtcedconductor" type="text" id="txtcedconductor" value="<?php print $ls_cedconductor;?>">
             </label></td>
             <td width="150" height="22"><div align="right">Nombre del Conductor </div></td>
             <td width="294" height="22"><label>
               <input name="txtnombreconductor" type="text" id="txtnombreconductor" value="<?php print $ls_nomconductor;?>" size="40">
             </label></td>
           </tr>
           <tr>
             <td height="22"><div align="right">Fecha de Salida </div></td>
             <td height="22"><label>
               <input name="txtfecsalida" type="text" id="txtfecsalida"  style="text-align:center" value="<?php print $ldt_fecsalida;?>" datepicker="true">
             </label></td>
             <td height="22"><div align="right">Hora de Salida </div></td>
             <td height="22"><label>
               <input name="txthorasalida" type="text" id="txthorasalida"  style="text-align:center" value="<?php print $ldt_horasalida;?>" size="8">
             </label></td>
           </tr>
           <tr>
             <td height="22"><div align="right">Descripci&oacute;n del Vehiculo </div></td>
             <td height="22" colspan="3"><label>
               <input name="txtdesvehiculo" type="text" id="txtdesvehiculo" value="<?php print $ls_desvehiculo;?>" size="108">
               <a href="javascript:ue_cat_tipotransporte();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo de Tipos de Transporte de Carga" width="15" height="15" border="0" longdesc="Catalogo de Tipos de Transporte de Carga">
               <input name="codtiptracar" type="hidden" id="codtiptracar">
              </a></label></td>
           </tr>
           <tr>
             <td height="22"><div align="right">Placa del Vehiculo (O Chuto) </div></td>
             <td height="22"><label>
               <input name="txtplacavehiculo" type="text" id="txtplacavehiculo" onChange="javascript:ue_mayusculas(this);" value="<?php print $ls_placaveh;?>" size="8" maxlength="7">
             </label></td>
             <td height="22"><div align="right">Placa Batea </div></td>
             <td height="22"><label>
               <input name="txtplacabatea" type="text" id="txtplacabatea" onBlur="javascript:ue_mayusculas(this);" value="<?php print $ls_placabatea;?>" size="8" maxlength="7">
             </label></td>
           </tr>
         </table></td>
       </tr>
       <tr class="formato-blanco">
         <td height="22" colspan="4">&nbsp;</td>
       </tr>
    </table>
     <p>&nbsp;</p>
     <p>&nbsp;</p>
     <p>&nbsp;</p>
     <p>&nbsp;	</p>
     <label></label>
     <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_estatus;?>">
  <div align="center"></div>
  </form>
</body>
<script language="javascript">

/****************************** RUTINAS JAVASCRIPT ****************************/
function ue_cargarordenentrega(codordent,numconordent,codcli,rif,razcli,fecemi,obsordent,numorddes,nunordcom,numguisad,codestordent,codmotordent,codptocol,denptocol,dirptocol,nomconptocol,telmovconptocol)
{
	f=document.form1;
	f.hidcodordent.value=codordent;
	f.txtnumord.value=numconordent;
	f.hidstatus.value="C";
	f.txtfecha.value=fecemi;
	f.txtnumordcom.value=nunordcom;
	f.txtnumdespacho.value=numorddes;
	f.txtnumguiasada.value=numguisad;
	f.txtcodcliente.value=codcli;
	f.txtnomcliente.value=razcli;
	f.txtcodptocol.value=codptocol;
	f.txtdenptocol.value=denptocol;
	f.cmbmotivoentrega.value=codmotordent;
	f.txtpersonacontacto.value=nomconptocol;
	f.txttelefono.value=telmovconptocol;
	f.txtdirptocol.value=dirptocol;
	f.hidcodestordent.value=codestordent;
	f.operacion.value="CARGAR_DT";
	f.action="sigesp_sfc_p_ordendeentrega.php";
	f.submit();
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		f.operacion.value="";
		pagina="sigesp_cat_ordendeentrega.php";
		popupWin(pagina,"catalogo",850,450);
	}
	else
	{	
		alert("No tiene permiso para realizar esta operacion.");
	}
}

function ue_nuevo()
{
	f=document.form1;
	f.action="sigesp_sfc_p_ordendeentrega.php";
	f.operacion.value="ue_nuevo";
	f.submit();		
}

function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_modificar=f.cambiar.value;
	suiche=true;
	li_totalconceptos=f.filasconcepto.value;
	li_totalinsumos=f.filasinsumos.value;
	if(li_incluir==1 || li_modificar==1)
	{
		if (ue_valida_null(f.txtnumord,"Numero de Orden de Entrega")==false)
		{
			f.txtnumord.focus();
			suiche=false;	
		}
		if (suiche==true && ue_valida_null(f.txtfecha,"Fecha de Emisión")==false)
		{
			f.txtfecha.focus();
			suiche=false;	
		}
		if (suiche==true &&  ue_valida_null(f.txtnumordcom,"Numero de Orden de Compra")==false)
		{
			f.txtfecha.focus();
			suiche=false;	
		}
		if (suiche==true &&  ue_valida_null(f.txtnumdespacho,"Numero de Despacho")==false)
		{
			f.txtfecha.focus();
			suiche=false;	
		}
		if (suiche==true &&  ue_valida_null(f.cmbmotivoentrega,"Motivo de Traslado")==false)
		{
			f.txtfecha.focus();
			suiche=false;	
		}
		if (suiche==true && ue_valida_null(f.txtnumguiasada,"Numero Guia SADA")==false)
		{
			f.txtnumguiasada.focus();
			suiche=false;	
		}
		if (suiche==true &&  ue_valida_null(f.txtcodcliente,"Codigo del Cliente")==false)
		{
			f.txtcodcliente.focus();
			suiche=false;	
		}
		if (suiche==true && ue_valida_null(f.txtcodptocol,"Punto de Colocacion")==false)
		{
			f.txtcodptocol.focus();
			suiche=false;	
		}		
		if(li_totalconceptos > 2)
		{
			if(suiche)
			{
				f.action="sigesp_sfc_p_ordendeentrega.php";
				f.operacion.value="ue_guardar";
				f.submit();		
			}	
		}
		else
		{
			alert("Debe registrar al menos 1 producto para procesar la factura.");	
		}
	}
	else
	{	
		alert("No tiene permiso para realizar esta operacion.");
	}
}


function ue_cat_puntos()
{
     f=document.form1;
	 ls_codcli=f.txtcodcliente.value;
	 if(ls_codcli!="")
	 {
		 pagina="sigesp_cat_puntos_col.php?codcli="+ls_codcli+"&opener='sigesp_sfc_p_ordendeentrega.php'";
		 popupWin(pagina,"catalogo",850,450);
	 }
	 else
	 {
	 	alert("Debe Seleccionar el Cliente.");
	 }
}
function ue_cat_cliente()
{
	pagina="sigesp_cat_cliente1.php";
	popupWin(pagina,"catalogo",520,550);
}

function ue_cat_proveedor_transporte()
{
	f=document.form1;
	if(f.rbpagoflete[0].checked)
	{
		pagina="sigesp_catdinamic_prov.php";
		popupWin(pagina,"catalogo",520,550);
	}
}

function ue_cat_tipotransporte()
{
	pagina="sigesp_cat_tipotransporte.php";
	popupWin(pagina,"catalogo",520,550);
}

function ue_cargarcliente(codcli,cedcli,nomcli,dircli,telcli,celcli,codpai,codest,codmun,codpar,producto,precioestandar,tentierra,estatus)
{
	f=document.form1;
	f.txtcodcliente.value=codcli;
	f.txtnomcliente.value=nomcli;	
}

function ue_cargarproducto(codpro,denpro,preven,moncar,porcar,codalm,nomfisalm,exi,cod_pro,nompro,costo)
{

	f=document.form1;
	f.operacion.value="ue_cargarconcepto";
	
	lb_existe=false;

	/*if (parseFloat(f.filasconcepto.value)>ls_item)
	{
		alert("Ha excedido el nï¿½mero maximo de lï¿½neas en la Factura!")
		suiche=false;
	}
	else
	{*/
		if(preven!="0,00")
		{
		   for(li_i=1;li_i<=f.filasconcepto.value && !lb_existe;li_i++)
		   {
			 ls_codigo=eval("f.txtcodart"+li_i+".value");
			 if(ls_codigo==codpro)
			  {
				alert("El producto ya fue cargado!!!");
				lb_existe=true;
			  }
		   }
	
		   if(!lb_existe)
			{
			//alert (exi);
			eval("f.txtcodart"+f.filasconcepto.value+".value='"+codpro+"'");
			eval("f.txtexiste"+f.filasconcepto.value+".value='"+exi+"'");
			eval("f.txtcodalm"+f.filasconcepto.value+".value='"+codalm+"'");
			eval("f.txtdesalm"+f.filasconcepto.value+".value='"+nomfisalm+"'");
			eval("f.txtnompro"+f.filasconcepto.value+".value='"+nompro+"'");
			eval("f.txtcod_pro"+f.filasconcepto.value+".value='"+cod_pro+"'");
			eval("f.txtdenart"+f.filasconcepto.value+".value='"+denpro+"'");
			eval("f.txtprepro"+f.filasconcepto.value+".value='"+preven+"'");
			eval("f.txtcosto"+f.filasconcepto.value+".value='"+costo+"'");
			eval("f.txtporcar"+f.filasconcepto.value+".value='"+porcar+"'");
			eval("f.txtmoncar"+f.filasconcepto.value+".value='"+moncar+"'");
			//suiche_submit=true;
			f.submit();
			}
		}
		else
		{
		  alert("por favor actualize el precio del producto!!!");
		}
	//}
}

function ue_cargarinsumo(codinsumo,deninsumo,preven,moncar,porcar,codalm,nomfisalm,exi,cod_pro,nompro,costo)
{
	f=document.form1;
	f.operacion.value="ue_cargarinsumos";
	
	lb_existe=false;
	/*if (parseFloat(f.filasconcepto.value)>ls_item)
	{
		alert("Ha excedido el nï¿½mero maximo de lï¿½neas en la Factura!")
		suiche=false;
	}
	else
	{*/
		if(preven!="0,00")
		{
		   for(li_i=1;li_i<=f.filasinsumos.value && !lb_existe;li_i++)
		   {
			  ls_codigo=eval("f.txtcodinsumo"+li_i+".value");
			  if(ls_codigo==codinsumo)
			  {
				alert("El producto ya fue cargado!!!");
				lb_existe=true;
			  }
		   }
	
		   if(!lb_existe)
			{
				eval("f.txtcodinsumo"+f.filasinsumos.value+".value='"+codinsumo+"'");
				eval("f.txtexisteinsumo"+f.filasinsumos.value+".value='"+exi+"'");
				eval("f.txtcodalminsumo"+f.filasinsumos.value+".value='"+codalm+"'");
				eval("f.txtdesalminsumo"+f.filasinsumos.value+".value='"+nomfisalm+"'");
				//eval("f.txtnompro"+f.filasinsumos.value+".value='"+nompro+"'");
				eval("f.txtcod_proinsumo"+f.filasinsumos.value+".value='"+cod_pro+"'");
				eval("f.txtdeninsumo"+f.filasinsumos.value+".value='"+deninsumo+"'");
				eval("f.txtpreinsumo"+f.filasinsumos.value+".value='"+preven+"'");
				eval("f.txtcostoinsumo"+f.filasinsumos.value+".value='"+costo+"'");
				eval("f.txtporcarinsumo"+f.filasinsumos.value+".value='"+porcar+"'");
				eval("f.txtmoncarinsumo"+f.filasinsumos.value+".value='"+moncar+"'");
				//suiche_submit=true;
				f.submit();
			}
		}
		else
		{
		  alert("por favor actualize el precio del producto!!!");
		}
	//}
}


function ue_catproducto(ls_tipo)
{
	pagina="sigesp_cat_producto_almacen2.php?tipo="+ls_tipo;
	popupWin(pagina,"catalogo",890,550);
}

function ue_calcular_total_fila(li_i)
{
	f=document.form1;
	ldec_existencia=roundNumber(parseFloat(eval("f.txtexiste"+li_i+".value"))); 
	ldec_prepro=eval("f.txtprepro"+li_i+".value");
	ldec_prepro=roundNumber(parseFloat(uf_convertir_monto(ldec_prepro)));
	ldec_canpro=eval("f.txtcanpro"+li_i+".value");
	if(ldec_canpro=="")
	{	ldec_canpro=0;	}
	ldec_canpro=roundNumber(parseFloat(uf_convertir_monto(ldec_canpro)));
	if(ldec_existencia<ldec_canpro)
	{
		alert("La cantidad introducida es mayor que la existencia del producto");
		eval("f.txtcanpro"+li_i+".value='"+uf_convertir(ldec_existencia)+"'");
		ldec_canpro=ldec_existencia;
	}
	ldec_porcar=eval("f.txtporcar"+li_i+".value");	
	ldec_porcar=roundNumber(parseFloat(uf_convertir_monto(ldec_porcar)));
	ldec_totpro=uf_convertir(roundNumber(ldec_prepro*ldec_canpro));
	eval("f.txttotpro"+li_i+".value='"+ldec_totpro+"'");	
	uf_total_productos();
	uf_total_general();			
}

function ue_calcular_total_insumo(li_i)
{
	f=document.form1;
	ldec_existencia=roundNumber(parseFloat(eval("f.txtexisteinsumo"+li_i+".value"))); 
	ldec_preinsumo=eval("f.txtpreinsumo"+li_i+".value");
	ldec_preinsumo=roundNumber(parseFloat(uf_convertir_monto(ldec_preinsumo)));
	ldec_caninsumo=eval("f.txtcaninsumo"+li_i+".value");
	if(ldec_caninsumo=="")
	{	ldec_caninsumo=0;	}
	ldec_caninsumo=roundNumber(parseFloat(uf_convertir_monto(ldec_caninsumo)));
	if(ldec_existencia<ldec_caninsumo)
	{
		alert("La cantidad introducida es mayor que la existencia del insumo");
		eval("f.txtcaninsumo"+li_i+".value='"+uf_convertir(ldec_existencia)+"'");
		ldec_caninsumo=ldec_existencia;
	}
	ldec_porcar=eval("f.txtporcarinsumo"+li_i+".value");	
	ldec_porcar=roundNumber(parseFloat(uf_convertir_monto(ldec_porcar)));
	ldec_totinsumo=uf_convertir(roundNumber(ldec_preinsumo*ldec_caninsumo));
	eval("f.txttotinsumo"+li_i+".value='"+ldec_totinsumo+"'");	
	uf_total_insumos();		
	uf_total_general();	
}

function uf_total_productos()
{
	f=document.form1;
	ldec_total=0;
	ldec_subtotal=0;
	ldec_totcar1=0;
	ldec_totcar2=0;
	for(li_i=1;li_i<=f.filasconcepto.value;li_i++)
	{
		 ldec_porcar=eval("f.txtporcar"+li_i+".value");
		 ldec_porcar=roundNumber(parseFloat(uf_convertir_monto(ldec_porcar)));
		 ldec_totpro=eval("f.txttotpro"+li_i+".value");
		 if(ldec_totpro=="")
		 {	ldec_totpro=0;	}
		 ldec_totpro=roundNumber(parseFloat(uf_convertir_monto(ldec_totpro)));
		 if(ldec_porcar==12)
		 {
		 	ldec_moncar=ldec_totpro*(ldec_porcar/100);
			ldec_totcar1=roundNumber(ldec_totcar1+ldec_moncar);
		    eval("f.txtmoncar"+li_i+".value="+ldec_totcar1);
		 }
		 if(ldec_porcar==8)
		 {
		 	ldec_moncar=ldec_totpro*(ldec_porcar/100);
			ldec_totcar2=roundNumber(ldec_totcar2+ldec_moncar);
		 	eval("f.txtmoncar"+li_i+".value="+ldec_totcar2);
		 }
		 ldec_subtotal=roundNumber(ldec_subtotal+ldec_totpro);
	}
	ldec_total=roundNumber(ldec_totcar1+ldec_totcar2+ldec_subtotal);
	f.txtsubtotalpro.value=uf_convertir(ldec_subtotal);
	f.txtiva1.value=uf_convertir(ldec_totcar1);
	f.txtiva2.value=uf_convertir(ldec_totcar2);
	f.txttotalpro.value=uf_convertir(ldec_total);	
}

function uf_total_insumos()
{
	f=document.form1;
	ldec_total=0;
	ldec_subtotal=0;
	ldec_totcar1=0;
	ldec_totcar2=0;
	for(li_i=1;li_i<=f.filasinsumos.value;li_i++)
	{
		 ldec_porcar=eval("f.txtporcarinsumo"+li_i+".value");
		 ldec_porcar=roundNumber(parseFloat(uf_convertir_monto(ldec_porcar)));
		 ldec_totinsumo=eval("f.txttotinsumo"+li_i+".value");
		 if(ldec_totinsumo=="")
		 {	ldec_totinsumo=0;	}
		 ldec_totinsumo=roundNumber(parseFloat(uf_convertir_monto(ldec_totinsumo)));
		 if(ldec_porcar==12)
		 {
		 	ldec_moncar=ldec_totinsumo*(ldec_porcar/100);
			ldec_totcar1=roundNumber(ldec_totcar1+ldec_moncar);
		    eval("f.txtmoncarinsumo"+li_i+".value="+ldec_totcar1);
		 }
		 if(ldec_porcar==8)
		 {
		 	ldec_moncar=ldec_totinsumo*(ldec_porcar/100);
			ldec_totcar2=roundNumber(ldec_totcar2+ldec_moncar);
		    eval("f.txtmoncarinsumo"+li_i+".value="+ldec_totcar2);			
		 }
		 ldec_subtotal=roundNumber(ldec_subtotal+ldec_totinsumo);
	}
	ldec_total=roundNumber(ldec_totcar1+ldec_totcar2+ldec_subtotal);
	f.txtsubtotalinsumo.value=uf_convertir(ldec_subtotal);
	f.txtiva1insumo.value=uf_convertir(ldec_totcar1);
	f.txtiva2insumo.value=uf_convertir(ldec_totcar2);
	f.txttotalinsumo.value=uf_convertir(ldec_total);	
}


function ue_removerconcepto(li_fila)
{
	f=document.form1;
	f.hidremoverconcepto.value=li_fila;
	f.operacion.value="ue_removerconcepto";
	f.action="sigesp_sfc_p_ordendeentrega.php";
	f.submit();
}

function ue_removerinsumos(li_fila)
{
	f=document.form1;
	f.hidremoverinsumos.value=li_fila;
	f.operacion.value="ue_removerinsumos";
	f.action="sigesp_sfc_p_ordendeentrega.php";
	f.submit();
}

function ue_mayusculas(obj)
{
	ls_cadena=obj.value;
	ls_cadena=ls_cadena.toUpperCase(); 
	obj.value=ls_cadena;
}

function uf_total_general()
{
	f=document.form1;
	ldec_iva1=roundNumber(parseFloat(uf_convertir_monto(f.txtiva1.value)));
	ldec_iva2=roundNumber(parseFloat(uf_convertir_monto(f.txtiva2.value)));
	ldec_iva1insumo=roundNumber(parseFloat(uf_convertir_monto(f.txtiva1insumo.value)));
	ldec_iva2insumo=roundNumber(parseFloat(uf_convertir_monto(f.txtiva2insumo.value)));
	ldec_impuesto12=ldec_iva1+ldec_iva1insumo;	
	ldec_impuesto8 =ldec_iva2+ldec_iva2insumo;
	ldec_totalproductos=roundNumber(parseFloat(uf_convertir_monto(f.txttotalpro.value)));
	ldec_totalinsumos=roundNumber(parseFloat(uf_convertir_monto(f.txttotalinsumo.value)));
	ldec_totalgeneral=roundNumber(ldec_totalproductos+ldec_totalinsumos+ldec_impuesto12+ldec_impuesto8);
	f.txttotalgeneral.value=uf_convertir(ldec_totalgeneral);
	f.txtiva12.value=uf_convertir(ldec_impuesto12);
	f.txtiva8.value=uf_convertir(ldec_impuesto8);
	
}

uf_total_productos();
uf_total_insumos();
uf_total_general();
</script>
</html>
