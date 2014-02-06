<?php
/*************************************************************/
/* FECHA: 03/08/2007                      						 */
/* AUTOR: ING. ZULHEYMAR RODR�"GUEZ         */
/************************************************************/
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "location.href='sigesp_conexion.php'";
	print "</script>";
}
	
$la_datemp=$_SESSION["la_empresa"];
//$_SESSION["ls_codtienda"]='0002';
$ls_codcaj=$_SESSION["ls_codcaj"];
$ls_codtie=$_SESSION["ls_codtienda"];
if($ls_codcaj=="" || $ls_codcaj=="T")
{
	print "<script language=JavaScript>";
	print "alert('Debe seleccionar una caja para poder realizar el cobro.');";
	print "location.href='../index_modules.php';";
	print "</script>";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Cobranza</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
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

<!--<style type="text/css">

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

</style>-->
</head>
<body link="#006699" vlink="#006699" alink="#006699"  onLoad="ue_subtotal(); ue_subtotalret(); ue_subtotalfpago();">
<?php

//********************************************         SEGURIDAD       ****************************************************
require_once("../shared/class_folder/sigesp_c_seguridad.php");
$io_seguridad= new sigesp_c_seguridad();

$arre=$_SESSION["la_empresa"];
$ls_empresa=$arre["codemp"];
$ls_logusr=$_SESSION["la_logusr"];
$ls_sistema="SFC";
$ls_ventanas="sigesp_sfc_d_cobranza.php";

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
require_once("class_folder/sigesp_sfc_c_factura.php");
require_once("class_folder/sigesp_sfc_c_instpago.php");
require_once("class_folder/sigesp_sfc_c_instpagocob.php");
require_once("class_folder/sigesp_sfc_c_cobranza.php");
require_once("class_folder/sigesp_sfc_c_secuencia.php");
require_once("class_folder/sigesp_sfc_c_nota.php");
require_once("class_folder/class_funciones_sfc.php");
$io_funciones_sfc=new class_funciones_sfc();
$io_nota=new sigesp_sfc_c_nota();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_sql=new class_sql($io_connect);
$io_funcdb=new class_funciones_db($io_connect);
$io_funcsob=new sigesp_sob_c_funciones_sob();
$io_evalform=new evaluate_formula();
$io_grid=new grid_param();
$io_msg=new class_mensajes();
$io_datastore=new class_datastore();
$io_dataret=new class_datastore();
$io_data=new class_datastore();
$io_function=new class_funciones();
$io_secuencia=new sigesp_sfc_c_secuencia();
$io_cotizacion=new  sigesp_sfc_c_cotizacion();
$io_factura=new  sigesp_sfc_c_factura();
$io_instpago=new sigesp_sfc_c_instrpago();
$io_instpagocob=new sigesp_sfc_c_instpagocob();
$io_cobranza=new  sigesp_sfc_c_cobranza();

/**************   GRID   FACTURAS   POR   COBRAR   *******************/
$ls_codemp=$la_datemp["codemp"];
$ls_tituloconcepto="Facturas por Cobrar";
$li_anchoconcepto=650;
$ls_nametable="grid1";
$la_columconcepto[1]="N&deg; Factura";
$la_columconcepto[2]="Fecha";
$la_columconcepto[3]="Monto IVA"; //Agregada para retencion
$la_columconcepto[4]="Monto";
$la_columconcepto[5]="Tipo Cancelaci&oacute;n";
$la_columconcepto[6]="Monto a cancelar";

/*******  GRID   INSTRUMENTOS   DE   PAGO  ********************/
$ls_titulofpago="Instrumento de pago";
$li_anchofpago=600;
$ls_nametablefpago="grid3";
$la_columfpago[1]="N&uacute;mero";
$la_columfpago[2]="Banco";
$la_columfpago[3]="Factura";
$la_columfpago[4]="Forma de pago";
$la_columfpago[5]="Observaci&oacute;n";
$la_columfpago[6]="Fecha";
$la_columfpago[7]="Monto";
$la_columfpago[8]="Edici&oacute;n";

/******************** GRID DE RETENCIONES ***************************************************/
$ls_tituloretenciones="Retenciones Asignadas";
$li_anchoretenciones=600;
$ls_nametable="grid";
$la_columretenciones[1]="C&oacute;digo";
$la_columretenciones[2]="Descripci&oacute;n";
$la_columretenciones[3]="Monto";
$la_columretenciones[4]="Total";
$la_columretenciones[5]="Nro Factura";
$la_columretenciones[6]="Pagar";
$la_columretenciones[7]="Comprobante";

if(array_key_exists("operacion",$_POST))
{
	//print 'operacion exist';
	$ls_operacion1=$_POST["operacion1"];
	$ls_operacion=$_POST["operacion"];
	$ls_codcierre=$_POST["txtcierre"];
	$ls_codusu=$_SESSION["la_logusr"];
	$ls_codcli=$_POST["txtcodcli"];
	$ls_codcli1=$_POST["txtcodcli1"];
	$ls_nomcli=$_POST["txtnomcli"];
	$ls_observacion=$_POST["txtobservacion"];
	$ls_numcob=$_POST["txtnumcob"];
	$ls_feccob=$_POST["txtfeccob"];
	$ls_codalm=$_POST["hidcodalm"];
	$ls_estcob=$_POST["estcob"];
	$ls_modificar=$_POST["modificar"];

	//Caja
	//$la_codcaj=$_POST["txtcodcaj"];
	//$la_nomcaj=$_POST["txtnomcaj"];

	//FILAS CONCEPTO
	$li_filasconcepto=$_POST["filasconcepto"];
    $li_removerconcepto=$_POST["hidremoverconcepto"];

	//FILAS FORMA DE PAGO
	$li_filasfpago=$_POST["txtfilasfpago"];
	$li_removerfpago=$_POST["hidremoverfpago"];

	//Datos Montos
	$ls_subtotalret=$_POST["txtsubtotalret"];
	$ls_totalmonret=$_POST["txtotalmonret"];
	$ls_montoforpag=$_POST["txtmontoforpag"];
	$ls_totalforpag=$_POST["txttotalforpag"];
	$ls_moncob=$_POST["txtmoncob"];
	$ls_restaporpagar=$_POST["txtmonresta"];
	$ls_monxcob=$_POST["txtmoncob1"];
	$ls_monivat=$_POST["txtmoniva"];

	//FILAS RETENCIONES
	$li_filasretenciones=$_POST["filasretenciones"];
	$li_removerretenciones=$_POST["removerretenciones"];

	if(array_key_exists("check1",$_POST)) //checkbox pago especial
	 {
	  $ls_especial="V";
	 }
	else
	 {
	  $ls_especial="F";
	 }

	 ////////////////////////////////////////////////////////////
	 //     GRID FACTURAS CLIENTES
	 ///////////////////////////////////////////////////////////
	if ($ls_operacion != "ue_cargarfacturas_cliente")
   {
  	for($li_i=1;$li_i<$li_filasconcepto;$li_i++)
		{
		$ls_numfac=$_POST["txtnumfac".$li_i];
		$ls_numfacasoc=$_POST["txtnumfacasoc".$li_i];
		$ls_fecemi=$_POST["txtfecemi".$li_i];
		$ls_monto=$_POST["txtmonto".$li_i];
		$ls_montoiva=$_POST["txtmontoiva".$li_i];
		$ls_montocancel=$_POST["txtmontocancel".$li_i];
		$ls_tipocancel=$_POST["combo_tipocancel".$li_i];
			if ($ls_monto==0 and $ls_estcob!='A')
				{
				$la_objectconcepto[$li_i][1]="<input name=txtnumfac".$li_i." style=color:#FF0000 type=text id=txtnumfac".$li_i." value='".$ls_numfac."' class=sin-borde size=30 style= text-align:center readonly><input name=txtnumfacasoc".$li_i." style=color:#FF0000 type=hidden id=txtnumfacasoc".$li_i." value='".$ls_numfacasoc."' class=sin-borde size=30 style= text-align:center readonly>";
				$la_objectconcepto[$li_i][2]="<input name=txtfecemi".$li_i." style=color:#FF0000 type=text id=txtfecemi".$li_i." value='".$ls_fecemi."' class=sin-borde size=10 style= text-align:left readonly>";
				$la_objectconcepto[$li_i][3]="<input name=txtmontoiva".$li_i."  style=color:#FF0000  style=text-align:center type=text id=txtmontoiva".$li_i." value='".$ls_montoiva."' class=sin-borde size=15  readonly>";
				$la_objectconcepto[$li_i][4]="<input name=txtmonto".$li_i."  style=color:#FF0000  style=text-align:center type=text id=txtmonto".$li_i." value='".$ls_monto."' class=sin-borde size=15  readonly>";
				$la_objectconcepto[$li_i][5]="<select name=combo_tipocancel".$li_i." id=combo_tipocancel".$li_i." size=1 onchange=cargar_monto(".$li_i.");ue_calretencion(".$li_i.");> <option value=T selected >Total</option>
												 </select>";

				$la_objectconcepto[$li_i][6]="<input name=txtmontocancel".$li_i." type=text id=txtmontocancel".$li_i." value='".$ls_montocancel."' class=sin-borde size=15 style=color:#FF0000 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event)) readonly>";
			}
			else if ($ls_estcob=='A')
			{
				$la_objectconcepto[$li_i][1]="<input name=txtnumfac".$li_i." style=color:#FF0000 type=text id=txtnumfac".$li_i." value='".$ls_numfac."' class=sin-borde size=30 style= text-align:center readonly><input name=txtnumfacasoc".$li_i." style=color:#FF0000 type=hidden id=txtnumfacasoc".$li_i." value='".$ls_numfacasoc."' class=sin-borde size=30 style= text-align:center readonly>";
				$la_objectconcepto[$li_i][2]="<input name=txtfecemi".$li_i." style=color:#FF0000 type=text id=txtfecemi".$li_i." value='".$ls_fecemi."' class=sin-borde size=10 style= text-align:left readonly>";
				$la_objectconcepto[$li_i][3]="<input name=txtmontoiva".$li_i."  style=color:#FF0000  style=text-align:center type=text id=txtmontoiva".$li_i." value='".$ls_montoiva."' class=sin-borde size=15  readonly>";
				$la_objectconcepto[$li_i][4]="<input name=txtmonto".$li_i."  style=color:#FF0000  style=text-align:center type=text id=txtmonto".$li_i." value='".$ls_monto."' class=sin-borde size=15  readonly>";

				if ($ls_tipocancel=='T')
				   {
				$la_objectconcepto[$li_i][5]="<select name=combo_tipocancel".$li_i." id=combo_tipocancel".$li_i." size=1 onchange=cargar_monto(".$li_i.");ue_calretencion(".$li_i.");>
									<option value=T selected >Total</option>
											  </select>";					}
				elseif ($ls_tipocancel=='P')
				   {
				   $la_objectconcepto[$li_i][5]="<select name=combo_tipocancel".$li_i." id=combo_tipocancel".$li_i." size=1 onchange=cargar_monto(".$li_i.");ue_calretencion(".$li_i.");>
											<option value=P selected >Parcial</option>
										  </select>";
				   }
				else
				   {
				   $la_objectconcepto[$li_i][5]="<select name=combo_tipocancel".$li_i." id=combo_tipocancel".$li_i." size=1 onchange=cargar_monto(".$li_i.");ue_calretencion(".$li_i.");>
												<option value=n selected >Seleccione...</option>

											  </select>";
				   }
				$la_objectconcepto[$li_i][6]="<input name=txtmontocancel".$li_i." type=text id=txtmontocancel".$li_i." value='".$ls_montocancel."' class=sin-borde size=15 style=color:#FF0000 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event)) readonly>";
			}
			else
			{
				$la_objectconcepto[$li_i][1]="<input name=txtnumfac".$li_i." type=text id=txtnumfac".$li_i." value='".$ls_numfac."' class=sin-borde size=30 style= text-align:center readonly><input name=txtnumfacasoc".$li_i." type=hidden id=txtnumfacasoc".$li_i." value='".$ls_numfacasoc."' class=sin-borde size=30 style= text-align:center readonly>";
				$la_objectconcepto[$li_i][2]="<input name=txtfecemi".$li_i." type=text id=txtfecemi".$li_i." value='".$ls_fecemi."' class=sin-borde size=10 style= text-align:left readonly>";
				$la_objectconcepto[$li_i][3]="<input name=txtmontoiva".$li_i."  style=text-align:center type=text id=txtmontoiva".$li_i." value='".$ls_montoiva."' class=sin-borde size=15  readonly>";
				$la_objectconcepto[$li_i][4]="<input name=txtmonto".$li_i." type=text id=txtmonto".$li_i." value='".$ls_monto."' class=sin-borde size=15 style= text-align:center readonly>";

				if ($ls_tipocancel=='T')
					   {
					$la_objectconcepto[$li_i][5]="<select name=combo_tipocancel".$li_i." id=combo_tipocancel".$li_i." size=1 onchange=cargar_monto(".$li_i.");ue_calretencion(".$li_i.");>
													<option value=n >Seleccione...</option>
													<option value=T selected>Total</option>
													<option value=P >Parcial</option>
												  </select>";
			$la_objectconcepto[$li_i][6]="<input name=txtmontocancel".$li_i." type=text id=txtmontocancel".$li_i." value='".$ls_montocancel."' class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event)) readonly>";
						}
					elseif ($ls_tipocancel=='P')
					   {
					   $la_objectconcepto[$li_i][5]="<select name=combo_tipocancel".$li_i." id=combo_tipocancel".$li_i." size=1 onchange=cargar_monto(".$li_i.");ue_calretencion(".$li_i.");>
													<option value=n >Seleccione...</option>
													<option value=T >Total</option>
													<option value=P selected >Parcial</option>
											  </select>";
											  $la_objectconcepto[$li_i][6]="<input name=txtmontocancel".$li_i." type=text id=txtmontocancel".$li_i." value='".$ls_montocancel."' class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event))  onBlur=ue_calretencion(".$li_i.");>";
					   }
					else
					   {
					   $la_objectconcepto[$li_i][5]="<select name=combo_tipocancel".$li_i." id=combo_tipocancel".$li_i." size=1 onchange=cargar_monto(".$li_i.");ue_calretencion(".$li_i.");>
													<option value=n selected >Seleccione...</option>
													<option value=T >Total</option>
													<option value=P >Parcial</option>
												  </select>";
												  $la_objectconcepto[$li_i][6]="<input name=txtmontocancel".$li_i." type=text id=txtmontocancel".$li_i." value='".$ls_montocancel."' class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=ue_calretencion($li_i);>";
					 }
		}//for
	}
		$la_objectconcepto[$li_filasconcepto][1]="<input name=txtnumfac".$li_filasconcepto." type=text id=txtnumfac".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly><input name=txtnumfacasoc".$li_filasconcepto." type=hidden id=txtnumfacasoc".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][2]="<input name=txtfecemi".$li_filasconcepto." type=text id=txtfecemi".$li_filasconcepto." class=sin-borde size=10 style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][3]="<input name=txtmontoiva".$li_filasconcepto."  style=text-align:center type=text id=txtmontoiva".$li_filasconcepto." class=sin-borde size=15  readonly>";
		$la_objectconcepto[$li_filasconcepto][4]="<input name=txtmonto".$li_filasconcepto." type=text id=txtmonto".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][5]="<input name=txttipocancel".$li_filasconcepto." type=text id=txttipocancel".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][6]="<input name=txtmontocancel".$li_filasconcepto." type=text id=txtmontocancel".$li_filasconcepto." class=sin-borde size=15 style= text-align:center >";
   }
    ////////////////////////////////////////////////////////////
	 //     GRID FORMAS DE PAGO
	 ///////////////////////////////////////////////////////////
if ($ls_operacion != "ue_cargarfpago" && $ls_operacion != "ue_removerfpago")
	{
		for($li_i=1;$li_i<$li_filasfpago;$li_i++)
		{
			$ls_numinst=$_POST["txtnuminst".$li_i];
			$ls_nombanco=$_POST["txtnombanco".$li_i];
			$ls_ctaban=$_POST["txtctaban".$li_i];
			$ls_codban=$_POST["txtcodban".$li_i];
			$ls_facret=$_POST["txtfacret".$li_i];
			$ls_denforpag=$_POST["txtdenforpag".$li_i];
			$ls_obsins=$_POST["txtobsins".$li_i];
			$ls_codforpag=$_POST["txtcodforpag".$li_i];
			$ls_fecins=$_POST["txtfecins".$li_i];
			$ls_montoforpag=$_POST["txtmontoforpag".$li_i];
		 	$ls_metforpago=$_POST["txtmetforpag".$li_i];

			$la_objectfpago[$li_i][1]="<input name=txtnuminst".$li_i." type=text id=txtnuminst".$li_i." value='".$ls_numinst."' class=sin-borde size=25 style= text-align:center readonly><input name=txtmetforpag".$li_i." type=hidden id=txtmetforpag".$li_i." value='".$ls_metforpago."' class=sin-borde size=15 style= text-align:center readonly>";
			$la_objectfpago[$li_i][2]="<input name=txtnombanco".$li_i." type=text id=txtnombanco".$li_i." value='".$ls_nombanco."' class=sin-borde size=20 style= text-align:center readonly><input name=txtcodban".$li_i." type=hidden id=txtcodban".$li_i." value='".$ls_codban."' class=sin-borde size=15 style= text-align:center readonly><input name=txtctaban".$li_i." type=hidden id=txtctaban".$li_i." value='".$ls_ctaban."' class=sin-borde size=15 style= text-align:center readonly>";
			$la_objectfpago[$li_i][3]="<input name=txtfacret".$li_i." type=hidden id=txtfacret".$li_i." value='".$ls_facret."' class=sin-borde size=25 style= text-align:center readonly>";
			$la_objectfpago[$li_i][4]="<input name=txtdenforpag".$li_i." type=text id=txtdenforpag".$li_i." value='".$ls_denforpag."' class=sin-borde size=15 style= text-align:center readonly>";
			$la_objectfpago[$li_i][5]="<input name=txtobsins".$li_i." type=text id=txtobsins".$li_i." value='".$ls_obsins."' class=sin-borde size=45 style= text-align:left readonly><input name=txtcodforpag".$li_i." type=hidden id=txtcodforpag".$li_i." value='".$ls_codforpag."' class=sin-borde size=15 style= text-align:center readonly>";
			$la_objectfpago[$li_i][6]="<input name=txtfecins".$li_i." type=text id=txtfecins".$li_i." value='".$ls_fecins."' class=sin-borde size=10 style= text-align:left readonly>";
			$la_objectfpago[$li_i][7]="<input name=txtmontoforpag".$li_i." type=text id=txtmontoforpag".$li_i." value='".$ls_montoforpag."' class=sin-borde size=15 style= text-align:center readonly>";
			$la_objectfpago[$li_i][8]="<a href=javascript:ue_removerfpago(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}

	$la_objectfpago[$li_filasfpago][1]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=25 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." ttype=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][2]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtctaban".$li_filasfpago." type=hidden id=txtctaban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][3]="<input name=txtfacret".$li_filasfpago." type=hidden id=txtfacret".$li_filasfpago." value='".$ls_facret."' class=sin-borde size=25 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][4]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][5]="<input name=txtobsins".$li_filasfpago." type=text id=txtobsins".$li_filasfpago." class=sin-borde size=45 style= text-align:left readonly><input name=txtcodforpag".$li_filasfpago." type=hidden id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][6]="<input name=txtfecins".$li_filasfpago." type=text id=txtfecins".$li_filasfpago." class=sin-borde size=10 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][7]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][8]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";

	}

	 ////////////////////////////////////////////////////////////
	 //     GRID RETENCIONES POR CLIENTE
	 ///////////////////////////////////////////////////////////
	if ($ls_operacion != "ue_cargarretenciones" && $ls_operacion != "ue_removerretenciones")
	{
	 		 for($li_i=1;$li_i<$li_filasretenciones;$li_i++)
			 {
			 	$ls_codigo=$_POST["txtcodret".$li_i];
				$ls_descripcion=$_POST["txtdesret".$li_i];
				$ls_formula=$_POST["formula".$li_i];
				$ls_montoret=$_POST["txtmontoret".$li_i];
				$ls_totalret=$_POST["txttotalret".$li_i];
				$ls_facasoc=$_POST["txtfacasoc".$li_i];
				$ls_comprobante=$_POST["txtcomprobante".$li_i];
				$ls_chkpago=$_POST["chkpagar".$li_i];
				$la_objectretenciones[$li_i][1]="<input name=txtcodret".$li_i." type=text id=txtcodret".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
				$la_objectretenciones[$li_i][2]="<input name=txtdesret".$li_i." type=text id=txtdesret".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=35 readonly>";
				if ($ls_estcob=="" && $ls_operacion!="ue_guardar")//Si la cobranza es nueva se habilita la opcion de eliminar en edicion
				 {
					$la_objectretenciones[$li_i][3]="<input name=txtmontoret".$li_i." type=text id=txtmontoret".$li_i." class=sin-borde value='".$ls_montoret."' style= text-align:right size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=ue_caliva(); readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";
				 }
				else
				 {
				 	$la_objectretenciones[$li_i][3]="<input name=txtmontoret".$li_i." type=text id=txtmontoret".$li_i." class=sin-borde  onKeyPress=return(currencyFormat(this,'.',',',event)) value='".$ls_montoret."' style= text-align:right size=15 readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";
				 }
				$la_objectretenciones[$li_i][4]="<input name=txttotalret".$li_i." onKeyPress=return(currencyFormat(this,'.',',',event)) type=text id=txttotalret".$li_i." class=sin-borde value='".$ls_totalret."' style= text-align:right size=15 onChange=javascript:ue_subtotalret(); >";
				$la_objectretenciones[$li_i][5]="<input name=txtfacasoc".$li_i." type=text id=txtfacasoc".$li_i." class=sin-borde value='".$ls_facasoc."' style= text-align:center size=26 readonly>";

				if($ls_chkpago == "on"){
					$la_objectretenciones[$li_i][6]="<input name=chkpagar".$li_i." type=checkbox id=chkpagar".$li_i." class=sin-borde onChange=javascript:ue_subtotalret(); checked>";
				}else{
					$la_objectretenciones[$li_i][6]="<input name=chkpagar".$li_i." type=checkbox id=chkpagar".$li_i." class=sin-borde onChange=javascript:ue_subtotalret(); >";
				}
				$la_objectretenciones[$li_i][7]="<input name=txtcomprobante".$li_i." type=text id=txtcomprobante".$li_i." class=sin-borde value='".$ls_comprobante."' style= text-align:center size=26 maxlength=25 >";
			 }

		    $la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
		    $la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=35 readonly>";
			$la_objectretenciones[$li_filasretenciones][3]="<input name=txtmontoret".$li_filasretenciones." type=text id=txtmontoret".$li_filasretenciones." class=sin-borde style= text-align:right size=15><input name=formula".$li_filasretenciones." type=hidden id=formula".$li_filasretenciones." readonly>";
			$la_objectretenciones[$li_filasretenciones][4]="<input name=txttotalret".$li_filasretenciones." type=text id=txttotalret".$li_filasretenciones." onKeyPress=return(currencyFormat(this,'.',',',event)) class=sin-borde style= text-align:right size=15 onChange=javascript:ue_subtotalret(); readonly>";
			$la_objectretenciones[$li_filasretenciones][5]="<input name=txtfacasoc".$li_filasretenciones." type=text id=txtfacasoc".$li_filasretenciones." class=sin-borde style= text-align:right size=26  readonly>";
			//$la_objectretenciones[$li_filasretenciones][6]="<input name=chkpagar".$li_filasretenciones." type=checkbox id=chkpagar".$li_filasretenciones." class=sin-borde style= text-align:right readonly>";
			$la_objectretenciones[$li_filasretenciones][7]="<input name=txtcomprobante".$li_filasretenciones." type=text id=txtcomprobante".$li_filasretenciones." class=sin-borde style= text-align:right size=26  readonly>";
	}
}
else
{
$li_filasfpago=1;
    $la_empresa=$_SESSION["la_empresa"];
	$ls_codcaj=$_SESSION["ls_codcaj"];
	$ls_prefijo=$_SESSION["ls_precob"];
	$ls_serie=$_SESSION["ls_sercob"];
	//$io_secuencia->uf_ver_secuencia($ls_codcaj."cob",&$ls_secuencia);
	//$ls_secuencia=$io_function->uf_cerosizquierda($ls_secuencia,16);
	//$ls_numcob=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);
	$ls_numcob="NUEVO";
	$ls_fecemi="";
	$ls_feccob=date('d/m/Y');
	$ls_codcli="";
	$ls_codcli1="";
	$ls_codcierre="";
	$ls_nomcli="";
	$ls_subtot="0,00";
	$ls_monto="0,00";
	$ls_monivat="0,00";
	$ls_conpag=1;
	$ls_operacion1="ue_nuevo";
	$ls_operacion="ue_nuevo";
	$ls_numfac="";
	$ls_estcob="";
	$ls_modificar="True";
	$ls_totalforpag="0,00";
	$ls_observacion="";
    $ls_moncob="0,00";
	$ls_restaporpagar='0,00';
	$ls_tipocancel="S";
	$ls_codforpag="";
	$ls_codban="";
	$ls_especial="F";
	$ls_subtotalret="0,00";
	$ls_totalmonret="0,00";
	/*$la_codcaj="";
	$la_nomcaj="";*/

	$li_filasconcepto=1;
    $la_objectconcepto[$li_filasconcepto][1]="<input name=txtnumfac".$li_filasconcepto." type=text id=txtnumfac".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly><input name=txtnumfacasoc".$li_filasconcepto." type=hidden id=txtnumfacasoc".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtfecemi".$li_filasconcepto." type=text id=txtfecemi".$li_filasconcepto." class=sin-borde size=10 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][3]="<input name=txtmontoiva".$li_filasconcepto."   style=text-align:center type=text id=txtmontoiva".$li_filasconcepto." value='".$ls_montoiva."' class=sin-borde size=15  readonly>";
	$la_objectconcepto[$li_filasconcepto][3]="<input name=txtmonto".$li_filasconcepto." type=text id=txtmonto".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txttipocancel".$li_filasconcepto." type=text id=txttipocancel".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txtmontocancel".$li_filasconcepto." type=text id=txtmontocancel".$li_filasconcepto." class=sin-borde size=15 style= text-align:center >";

	$li_filasfpago=1;
	$la_objectfpago[$li_filasfpago][1]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=25 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][2]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtctaban".$li_filasfpago." type=hidden id=txtctaban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][3]="<input name=txtfacret".$li_filasfpago." type=text id=txtfacret".$li_filasfpago." class=sin-borde size=25 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][4]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][5]="<input name=txtobsins".$li_filasfpago." type=text id=txtobsins".$li_filasfpago." class=sin-borde size=45 style= text-align:left readonly><input name=txtcodforpag".$li_filasfpago." type=hidden id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][6]="<input name=txtfecins".$li_filasfpago." type=text id=txtfecins".$li_filasfpago." class=sin-borde size=10 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][7]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][8]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
//----------------------------------------------------Grid Retenciones----------------------------------------------------//
	$li_filasretenciones=1;
	$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=35 readonly>";
	$la_objectretenciones[$li_filasretenciones][3]="<input name= ".$li_filasretenciones." type=text id=txtmontoret".$li_filasretenciones." class=sin-borde style= text-align:right size=15 onKeyPress=return(currencyFormat(this,'.',',',event))><input name=formula".$li_filasretenciones." type=hidden id=formula".$li_filasretenciones.">";
	$la_objectretenciones[$li_filasretenciones][4]="<input name=txttotalret".$li_filasretenciones." type=text id=txttotalret".$li_filasretenciones." class=sin-borde style= text-align:right size=15 onChange=javascript:ue_subtotalret(); readonly>";
	$la_objectretenciones[$li_filasretenciones][5]="<input name=txtfacasoc".$li_filasretenciones." type=text id=txtfacasoc".$li_filasretenciones." class=sin-borde style= text-align:right size=26  readonly>";
	$la_objectretenciones[$li_filasretenciones][6]="<input name=chkpagar".$li_filasretenciones." type=checkbox id=chkpagar".$li_filasretenciones." class=sin-borde style= text-align:right >";
	$la_objectretenciones[$li_filasretenciones][7]="<input name=txtcomprobante".$li_filasretenciones." type=text id=txtcomprobante".$li_filasretenciones." class=sin-borde style= text-align:right size=26  readonly>";}
if($ls_operacion=="ue_nuevo")
{
	$li_filasfpago=1;
    $la_empresa=$_SESSION["la_empresa"];
	$ls_codcaj=$_SESSION["ls_codcaj"];
	$ls_prefijo=$_SESSION["ls_precob"];
	$ls_serie=$_SESSION["ls_sercob"];
	//$io_secuencia->uf_ver_secuencia($ls_codcaj."cob",&$ls_secuencia);
	//$ls_secuencia=$io_function->uf_cerosizquierda($ls_secuencia,16);
	//$ls_numcob=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);
	$ls_numcob=$io_funciones_sfc->uf_generer_consecutivo("sfc_cobro_cliente","numcob",$ls_prefijo,$ls_serie,25);
	//$ls_numcob="NUEVO";
	$ls_fecemi="";
	$ls_feccob=date('d/m/Y');
	$ls_codcli="";
	$ls_codcli1="";
	$ls_codcierre="";
	$ls_nomcli="";
	$ls_subtot="0,00";
	$ls_monto="0,00";
	$ls_monivat="0,00";
	$ls_conpag=1;
	$ls_operacion1="ue_nuevo";
	$ls_operacion="";
	$ls_numfac="";
	$ls_estcob="";
	$ls_modificar="True";
	$ls_totalforpag="0,00";
	$ls_observacion="";
    $ls_moncob="0,00";
	$ls_restaporpagar='0,00';
	$ls_tipocancel="S";
	$ls_codforpag="";
	$ls_codban="";
	$ls_especial="F";
	$ls_subtotalret="0,00";
	$ls_totalmonret="0,00";
/*	$la_codcaj="";
	$la_nomcaj="";*/

	$li_filasconcepto=1;
    $la_objectconcepto[$li_filasconcepto][1]="<input name=txtnumfac".$li_filasconcepto." type=text id=txtnumfac".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly><input name=txtnumfacasoc".$li_filasconcepto." type=hidden id=txtnumfacasoc".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtfecemi".$li_filasconcepto." type=text id=txtfecemi".$li_filasconcepto." class=sin-borde size=10 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][3]="<input name=txtmontoiva".$li_filasconcepto."   style=text-align:center type=text id=txtmontoiva".$li_filasconcepto." value='".$ls_montoiva."' class=sin-borde size=15  readonly>";
	$la_objectconcepto[$li_filasconcepto][3]="<input name=txtmonto".$li_filasconcepto." type=text id=txtmonto".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txttipocancel".$li_filasconcepto." type=text id=txttipocancel".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txtmontocancel".$li_filasconcepto." type=text id=txtmontocancel".$li_filasconcepto." class=sin-borde size=15 style= text-align:center >";

	$li_filasfpago=1;
	$la_objectfpago[$li_filasfpago][1]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=25 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][2]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtctaban".$li_filasfpago." type=hidden id=txtctaban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][3]="<input name=txtfacret".$li_filasfpago." type=text id=txtfacret".$li_filasfpago." class=sin-borde size=25 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][4]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][5]="<input name=txtobsins".$li_filasfpago." type=text id=txtobsins".$li_filasfpago." class=sin-borde size=45 style= text-align:left readonly><input name=txtcodforpag".$li_filasfpago." type=hidden id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][6]="<input name=txtfecins".$li_filasfpago." type=text id=txtfecins".$li_filasfpago." class=sin-borde size=10 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][7]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][8]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
//----------------------------------------------------Grid Retenciones----------------------------------------------------//
	$li_filasretenciones=1;
	$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=35 readonly>";
	$la_objectretenciones[$li_filasretenciones][3]="<input name= ".$li_filasretenciones." type=text id=txtmontoret".$li_filasretenciones." class=sin-borde style= text-align:right size=15 onKeyPress=return(currencyFormat(this,'.',',',event))><input name=formula".$li_filasretenciones." type=hidden id=formula".$li_filasretenciones.">";
	$la_objectretenciones[$li_filasretenciones][4]="<input name=txttotalret".$li_filasretenciones." type=text id=txttotalret".$li_filasretenciones." class=sin-borde style= text-align:right size=15 onChange=javascript:ue_subtotalret(); readonly>";
	$la_objectretenciones[$li_filasretenciones][5]="<input name=txtfacasoc".$li_filasretenciones." type=text id=txtfacasoc".$li_filasretenciones." class=sin-borde style= text-align:right size=26  readonly>";
	$la_objectretenciones[$li_filasretenciones][6]="<input name=chkpagar".$li_filasretenciones." type=checkbox id=chkpagar".$li_filasretenciones." class=sin-borde style= text-align:right >";
	$la_objectretenciones[$li_filasretenciones][7]="<input name=txtcomprobante".$li_filasretenciones." type=text id=txtcomprobante".$li_filasretenciones." class=sin-borde style= text-align:right size=26  readonly>";
}
elseif($ls_operacion=="ue_cargarfpago")
{
	$li_filasfpago=$_POST["txtfilasfpago"];
	$li_filasfpago++;
	for($li_i=1;$li_i<$li_filasfpago;$li_i++)
	{
		 $ls_numinst=$_POST["txtnuminst".$li_i];
		 $ls_nombanco=$_POST["txtnombanco".$li_i];
		 $ls_ctaban=$_POST["txtctaban".$li_i];
		 $ls_facret=$_POST["txtfacret".$li_i];
		 $ls_codban=$_POST["txtcodban".$li_i];
		 $ls_denforpag=$_POST["txtdenforpag".$li_i];
		 $ls_obsins=$_POST["txtobsins".$li_i];
		 $ls_codforpag=$_POST["txtcodforpag".$li_i];
		 $ls_fecins=$_POST["txtfecins".$li_i];
		 $ls_montoforpag=$_POST["txtmontoforpag".$li_i];
		 $ls_metforpago=$_POST["txtmetforpag".$li_i];

		$la_objectfpago[$li_i][1]="<input name=txtnuminst".$li_i." type=text id=txtnuminst".$li_i." value='".$ls_numinst."' class=sin-borde size=25 style= text-align:center readonly><input name=txtmetforpag".$li_i." type=hidden id=txtmetforpag".$li_i." value='".$ls_metforpago."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectfpago[$li_i][2]="<input name=txtnombanco".$li_i." type=text id=txtnombanco".$li_i." value='".$ls_nombanco."' class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_i." type=hidden id=txtcodban".$li_i." value='".$ls_codban."' class=sin-borde size=15 style= text-align:center readonly><input name=txtctaban".$li_i." type=hidden id=txtctaban".$li_i." value='".$ls_ctaban."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectfpago[$li_i][3]="<input name=txtfacret".$li_i." type=text id=txtfacret".$li_i." value='".$ls_facret."' class=sin-borde size=25 style= text-align:center readonly>";
		$la_objectfpago[$li_i][4]="<input name=txtdenforpag".$li_i." type=text id=txtdenforpag".$li_i." value='".$ls_denforpag."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectfpago[$li_i][5]="<input name=txtobsins".$li_i." type=text id=txtobsins".$li_i." value='".$ls_obsins."' class=sin-borde size=45 style= text-align:left readonly><input name=txtcodforpag".$li_i." type=hidden id=txtcodforpag".$li_i." value='".$ls_codforpag."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectfpago[$li_i][6]="<input name=txtfecins".$li_i." type=text id=txtfecins".$li_i." value='".$ls_fecins."' class=sin-borde size=10 style= text-align:left readonly>";
		$la_objectfpago[$li_i][7]="<input name=txtmontoforpag".$li_i." type=text id=txtmontoforpag".$li_i." value='".$ls_montoforpag."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectfpago[$li_i][8]="<a href=javascript:ue_removerfpago(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
	}
	$la_objectfpago[$li_filasfpago][1]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=25 style= text-align:center readonly> <input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][2]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtctaban".$li_filasfpago." type=hidden id=txtctaban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][3]="<input name=txtfacret".$li_filasfpago." type=text id=txtfacret".$li_filasfpago." class=sin-borde size=25 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][4]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][5]="<input name=txtobsins".$li_filasfpago." type=text id=txtobsins".$li_filasfpago." class=sin-borde size=45 style= text-align:left readonly><input name=txtcodforpag".$li_filasfpago." type=hidden id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][6]="<input name=txtfecins".$li_filasfpago." type=text id=txtfecins".$li_filasfpago." class=sin-borde size=10 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][7]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][8]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
}
elseif ($ls_operacion=="ue_cargarcobros")
{
   	$li_filasfpago=1;	
	$ls_feccob=substr($ls_feccob,0,2).'-'.substr($ls_feccob,3,2).'-'.substr($ls_feccob,6,4);
	$la_objectfpago[$li_filasfpago][1]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=25 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][2]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtctaban".$li_filasfpago." type=hidden id=txtctaban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][3]="<input name=txtfacret".$li_filasfpago." type=text id=txtfacret".$li_filasfpago." class=sin-borde size=25 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][4]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][5]="<input name=txtobsins".$li_filasfpago." type=text id=txtobsins".$li_filasfpago." class=sin-borde size=45 style= text-align:left readonly><input name=txtcodforpag".$li_filasfpago." type=hidden id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][6]="<input name=txtfecins".$li_filasfpago." type=text id=txtfecins".$li_filasfpago." class=sin-borde size=10 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][7]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][8]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";

	$li_filasconcepto=1;
    $la_objectconcepto[$li_filasconcepto][1]="<input name=txtnumfac".$li_filasconcepto." type=text id=txtnumfac".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly><input name=txtnumfacasoc".$li_filasconcepto." type=hidden id=txtnumfacasoc".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtfecemi".$li_filasconcepto." type=text id=txtfecemi".$li_filasconcepto." class=sin-borde size=10 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][3]="<input name=txtmontoiva".$li_filasconcepto."   style=text-align:center type=text id=txtmontoiva".$li_filasconcepto." value='".$ls_montoiva."' class=sin-borde size=15  readonly>";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txtmonto".$li_filasconcepto." type=text id=txtmonto".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txttipocancel".$li_filasconcepto." type=text id=txttipocancel".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][6]="<input name=txtmontocancel".$li_filasconcepto." type=text id=txtmontocancel".$li_filasconcepto." class=sin-borde size=15 style= text-align:center >";

	$li_filasretenciones=1;
	$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=35 readonly>";
	$la_objectretenciones[$li_filasretenciones][3]="<input name=txtmontoret".$li_filasretenciones." type=text id=txtmontoret".$li_filasretenciones." class=sin-borde style= text-align:right size=15><input name=formula".$li_filasretenciones." type=hidden id=formula".$li_filasretenciones." readonly>";
	$la_objectretenciones[$li_filasretenciones][4]="<input name=txttotalret".$li_filasretenciones." type=text id=txttotalret".$li_filasretenciones." class=sin-borde style= text-align:right size=15 onChange=javascript:ue_subtotalret(); readonly>";
	$la_objectretenciones[$li_filasretenciones][5]="<input name=txtfacasoc".$li_filasretenciones." type=text id=txtfacasoc".$li_filasretenciones." class=sin-borde style= text-align:right size=26  readonly>";
	//$la_objectretenciones[$li_filasretenciones][6]="<input name=chkpagar".$li_filasretenciones." type=checkbox id=chkpagar".$li_filasretenciones." class=sin-borde style= text-align:right readonly>";
	$la_objectretenciones[$li_filasretenciones][7]="<input name=txtcomprobante".$li_filasretenciones." type=text id=txtcomprobante".$li_filasretenciones." class=sin-borde style= text-align:right size=26  readonly>";

	/*$ls_cadena="SELECT c.codcli,c.cedcli,c.razcli,n.numnot,n.monto,n.fecnot,n.nro_factura,f.montoret,co.moncob,co.montoret as monret,co.totalret,cf.moncancel,cf.montoxcancel,cf.tipcancel
	FROM sfc_cliente c,sfc_nota n,sfc_cobro co,sfc_cobrofactura cf,sfc_factura f WHERE cf.numfac=n.numnot AND n.codcli=cf.codcli  AND
	cf.codcli=co.codcli AND cf.numcob=co.numcob AND  c.codcli=co.codcli AND c.codcli='".$ls_codcli1."' AND
	cf.numcob='".$ls_numcob."' AND (n.estnota='P'  OR n.estnota='C') AND n.tipnot='CXC' AND f.codcli=cf.codcli AND co.codcli=f.codcli AND n.codcli=f.codcli
	and f.numfac=cf.numfac;";*/

	$ls_cadena="SELECT c.codcli,c.cedcli,c.razcli,n.numnot,n.monto,n.fecnot,n.nro_documento,f.montoret,co.moncob,co.montoret as monret,co.totalret,cl.moncancel,cl.montoxcancel,cl.tipcancel " .
			"FROM sfc_cliente c,sfc_nota n,sfc_cobro_cliente co,sfc_dt_cobrocliente cl,sfc_factura f " .
			"WHERE cl.numfac=n.numnot AND n.codcli=cl.codcli  AND cl.codcli=co.codcli AND cl.numcob=co.numcob AND  c.codcli=co.codcli " .
			"AND c.codcli=".$ls_codcli1." AND cl.numcob='".$ls_numcob."' AND (n.estnota='P'  OR n.estnota='C') AND n.tipnot='CXC' " .
			"AND f.codcli=cl.codcli AND co.codcli=f.codcli AND n.codcli=f.codcli and f.numfac=cl.numfac;";


	$arr_detcobranza=$io_sql->select($ls_cadena);
	if($arr_detcobranza==false&&($io_sql->message!=""))
	{
		$io_msg->message("No hay registros de Facturas por Cobrar!");
	}
	else
	{
		if($row=$io_sql->fetch_row($arr_detcobranza))
		{
			$la_cobros=$io_sql->obtener_datos($arr_detcobranza);
			$io_datastore->data=$la_cobros;
			$totrow=$io_datastore->getRowCount("numnot");
			$ls_modificar="True";
			$ls_montoretotal=0;
			for($li_i=1;$li_i<=$totrow;$li_i++)
			{
				$ls_estatus="V";
				$ls_numfac=$io_datastore->getValue("numnot",$li_i);
				$ls_numfacasoc=$io_datastore->getValue("nro_documento",$li_i);
				$ls_fecemi=$io_datastore->getValue("fecnot",$li_i);
				$ls_fecemi=substr($ls_fecemi,8,2).'-'.substr($ls_fecemi,5,2).'-'.substr($ls_fecemi,0,4);
				$ls_montocancel=$io_datastore->getValue("moncancel",$li_i);
				//$ls_montocancel=$io_datastore->getValue("moncob",$li_i);
				$ls_tipocancel=$io_datastore->getValue("tipcancel",$li_i);
				$ls_monto=$io_datastore->getValue("montoxcancel",$li_i);
				$ls_montopar=$io_datastore->getValue("moncancel",$li_i);
				$ls_montopar2=$io_datastore->getValue("monto",$li_i);
				$ls_montopar=number_format($ls_montopar,2,',','.');
				$ls_montoiva=$io_datastore->getValue("montoret",$li_i);
				$ls_montoiva=number_format($ls_montoiva,2,',','.');
				$ls_monto=number_format($ls_monto,2, ',', '.');
				$ls_montoret=$io_datastore->getValue("monret",$li_i);
				$ls_montoret=number_format($ls_montoret,2,',','.');
				$ls_totalret=$io_datastore->getValue("totalret",$li_i);
				$ls_totalret=number_format($ls_totalret,2,',','.');
				$ls_montocancel=number_format($ls_montocancel,2, ',', '.');
				//print $ls_montocancel;
				$ls_numcob2=substr($ls_numcob,23,strlen($ls_numcob));
				$ls=0;
				$fecaux=date('d/m/Y');
				$existe_cobro=0;
				$existe_factura=0;

				//Busca Cobros Anteriores al Buscado
				/*$ls_cadena="SELECT n.monto,co.numcob,f.montoret,co.estcob,co.moncob,co.estcob,cf.moncancel,cf.tipcancel
				FROM sfc_cliente c,sfc_nota n, sfc_cobro co, sfc_cobrofactura cf,sfc_factura f WHERE n.numnot=cf.numfac AND
				cf.numcob=co.numcob	AND cf.codcli=co.codcli AND c.codcli=n.codcli AND n.codcli='".$ls_codcli1."'
				AND co.estcob<>'A' AND cf.numfac='".$ls_numfac."' AND cf.numcob<'".$ls_numcob."' AND cf.tipcancel<>'T'
				AND f.codcli=cf.codcli AND co.codcli=f.codcli AND n.codcli=f.codcli and f.numfac=cf.numfac";*/

				$ls_cadena="SELECT n.monto,co.numcob,f.montoret,co.estcob,co.moncob,co.estcob,cl.moncancel,cl.tipcancel
				FROM sfc_cliente c,sfc_nota n, sfc_cobro_cliente co, sfc_dt_cobrocliente cl,sfc_factura f WHERE n.numnot=cl.numfac AND
				cl.numcob=co.numcob	AND cl.codcli=co.codcli AND c.codcli=n.codcli AND n.codcli=".$ls_codcli1."
				AND co.estcob<>'A' AND cl.numfac='".$ls_numfac."' AND cl.numcob<'".$ls_numcob."' AND cl.tipcancel<>'T'
				AND f.codcli=cl.codcli AND co.codcli=f.codcli AND n.codcli=f.codcli and f.numfac=cl.numfac ";
				$arr_montos=$io_sql->select($ls_cadena);
				if ($arr_montos==false && ($io_sql->message!=""))
				{
					$io_msg->message("No hay Registros de Cobros Anteriores!");
				}
				else{
					if ($row=$io_sql->fetch_row($arr_montos))
					{
						$la_montos=$io_sql->obtener_datos($arr_montos);
						$io_data->data=$la_montos;
						$totrow2=$io_data->getRowCount("numcob");
						$ls_monto2=0;
						$ls_monto3=0;
						for ($li_i2=1;$li_i2<=$totrow2;$li_i2++)
						{
						$ls_monto3=$io_data->getValue("moncancel",$li_i2);
						$ls_estatcob=$io_data->getValue("estcob",$li_i2);
						$ls_montopar2=$ls_montopar2-$ls_monto3;
						}
						if ($ls_montopar2==0){
						$ls_montopar2=$io_datastore->getValue("monto",$li_i);
						}
						$ls_monto=number_format($ls_montopar2,2,',', '.');

					}else
					{
						if ($ls_tipocancel=='T' and $ls_montocancel==0 and $ls_estcob=='A')
						{
							$ls_monto='0,00';
						}						
					}
				}

				//$lb_validacion=$io_cobranza->uf_guardar_detcobro($ls_codtie,$ls_codcli1,$ls_numcob,$ls_numfac,$ls_tipocancel,$ls_montocancel,$ls_monto,$ls_numfacasoc,$la_seguridad);
				$fecaux=substr($fecaux,0,2).'-'.substr($fecaux,3,2).'-'.substr($fecaux,6,4);
				//print $ls_estatcob;

				/*$ls_cadena2="SELECT c.codcli,f.montoret,c.cedcli,c.razcli,n.numnot,n.nro_factura,n.monto,n.fecnot,co.moncob,
				cf.moncancel,cf.montoxcancel,cf.tipcancel,cf.numcob FROM sfc_cliente c,sfc_nota n,
				sfc_cobro co,sfc_cobrofactura cf,sfc_factura f WHERE cf.numfac=n.numnot AND n.codcli=cf.codcli AND
				cf.codcli=co.codcli AND cf.numcob=co.numcob AND  c.codcli=n.codcli AND c.codcli='".$ls_codcli1."'
				AND n.estnota='P' AND co.feccob='".$fecaux."' AND cf.numcob>'".	$ls_numcob."'  ";*/

				$ls_cadena2="SELECT c.codcli,f.montoret,c.cedcli,c.razcli,n.numnot,n.nro_documento,n.monto,n.fecnot,co.moncob,
				cl.moncancel,cl.montoxcancel,cl.tipcancel,cl.numcob FROM sfc_cliente c,sfc_nota n,
				sfc_cobro_cliente co,sfc_dt_cobrocliente cl,sfc_factura f WHERE cl.numfac=n.numnot AND n.codcli=cl.codcli AND
				cl.codcli=co.codcli AND cl.numcob=co.numcob AND  c.codcli=n.codcli AND c.codcli=".$ls_codcli1."
				AND n.estnota='P' AND co.feccob='".$fecaux."' AND cf.numcob>'".	$ls_numcob."'  ";

				//print $ls_cadena2;
				$arr_detcobro=$io_sql->select($ls_cadena2);
				if ($row=$io_sql->fetch_row($arr_detcobro))
				{
				$ls_modificar="False";
				}
				if ($ls_estcob=='A')
				{
				$ls_modificar='False';
				}
				if ($ls_modificar=="False")
				{
					$la_objectconcepto[$li_i][1]="<input name=txtnumfac".$li_i." type=text id=txtnumfac".$li_i." value='".$ls_numfac."' class=sin-borde size=30 style= text-align:center readonly><input name=txtnumfacasoc".$li_i." type=hidden id=txtnumfacasoc".$li_i." value='".$ls_numfacasoc."' class=sin-borde size=30 style= text-align:center readonly>";
					$la_objectconcepto[$li_i][2]="<input name=txtfecemi".$li_i." type=text id=txtfecemi".$li_i." value='".$ls_fecemi."' class=sin-borde size=10 style= text-align:left readonly>";
					$la_objectconcepto[$li_i][3]="<input name=txtmontoiva".$li_i." type=text id=txtmontoiva".$li_i." value='".$ls_montoiva."' class=sin-borde size=15 style= text-align:center readonly>";
					$la_objectconcepto[$li_i][4]="<input name=txtmonto".$li_i." type=text id=txtmonto".$li_i." value='".$ls_monto."' class=sin-borde size=15 style= text-align:center readonly>";

					if ($ls_tipocancel=='T')
					   {
					$la_objectconcepto[$li_i][5]="<select name=combo_tipocancel".$li_i." id=combo_tipocancel".$li_i." size=1 onchange=cargar_monto(".$li_i.");>
													 <option value=T selected>Total</option>
													  </select>";
						}
					elseif ($ls_tipocancel=='P')
					   {
					   $la_objectconcepto[$li_i][5]="<select name=combo_tipocancel".$li_i." id=combo_tipocancel".$li_i." size=1 onchange=cargar_monto(".$li_i.");>
													  <option value=P selected>Parcial</option>
											  </select>";
					   }
					else
					   {
					   $la_objectconcepto[$li_i][5]="<select name=combo_tipocancel".$li_i." id=combo_tipocancel".$li_i." size=1 onchange=cargar_monto(".$li_i.");>
													<option value=n selected>Seleccione...</option>
													 </select>";
					   }

					$la_objectconcepto[$li_i][6]="<input name=txtmontocancel".$li_i." type=text id=txtmontocancel".$li_i." value='".$ls_montocancel."' class=sin-borde size=15 style= text-align:center readonly>";
				}
				else
				{

					$la_objectconcepto[$li_i][1]="<input name=txtnumfac".$li_i." type=text id=txtnumfac".$li_i." value='".$ls_numfac."' class=sin-borde size=30 style= text-align:center readonly><input name=txtnumfacasoc".$li_i." type=hidden id=txtnumfacasoc".$li_i." value='".$ls_numfac."' class=sin-borde size=30 style= text-align:center readonly>";
					$la_objectconcepto[$li_i][2]="<input name=txtfecemi".$li_i." type=text id=txtfecemi".$li_i." value='".$ls_fecemi."' class=sin-borde size=10 style= text-align:left readonly>";
					$la_objectconcepto[$li_i][3]="<input name=txtmontoiva".$li_i." type=text id=txtmontoiva".$li_i." value='".$ls_montoiva."' class=sin-borde size=15 style= text-align:center readonly>";
					$la_objectconcepto[$li_i][4]="<input name=txtmonto".$li_i." type=text id=txtmonto".$li_i." value='".$ls_monto."' class=sin-borde size=15 style= text-align:center readonly>";

					if ($ls_feccob==$fecaux)
					{
						  if ($ls_tipocancel=='T')
						   {
								$la_objectconcepto[$li_i][5]="<select name=combo_tipocancel".$li_i." id=combo_tipocancel".$li_i." size=1 onchange=cargar_monto(".$li_i."); readonly>
														<option value=n>Seleccione...</option>
														<option value=T selected>Total</option>
														<option value=P>Parcial</option>
														 </select>";
							}
						  elseif ($ls_tipocancel=='P')
						   {
								$la_objectconcepto[$li_i][5]="<select name=combo_tipocancel".$li_i." id=combo_tipocancel".$li_i." size=1 onchange=cargar_monto(".$li_i."); readonly>
														<option value=n>Seleccione...</option>
														<option value=T>Total</option>
														<option value=P selected>Parcial</option>
												  </select>";
						   }
						  else
						  {
								$la_objectconcepto[$li_i][5]="<select name=combo_tipocancel".$li_i." id=combo_tipocancel".$li_i." size=1 onchange=cargar_monto(".$li_i."); readonly>
														<option value=n selected>Seleccione...</option>
														<option value=T>Total</option>
														<option value=P>Parcial</option>
													  </select>";
						   }
						  if ($ls_tipocancel=='T' and $ls_montocancel=='0,00')
						  {
								$la_objectconcepto[$li_i][6]="<input name=txtmontocancel".$li_i." type=text id=txtmontocancel".$li_i." value='".$ls_montocancel."' class=sin-borde size=15 style= text-align:center readonly>";
						  }
						  else
						  {
								$la_objectconcepto[$li_i][6]="<input name=txtmontocancel".$li_i." type=text id=txtmontocancel".$li_i." value='".$ls_montocancel."' class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event))>";

								$ls_estatus="F";
							}
					}
					else
					{
						if ($ls_tipocancel=='T')
						{
							$la_objectconcepto[$li_i][5]="<select name=combo_tipocancel".$li_i." id=combo_tipocancel".$li_i." size=1 onchange=cargar_monto(".$li_i."); readonly>
														<option value=T selected>Total</option>
														</select>";
							}
						elseif ($ls_tipocancel=='P')
						{
						   $la_objectconcepto[$li_i][5]="<select name=combo_tipocancel".$li_i." id=combo_tipocancel".$li_i." size=1 onchange=cargar_monto(".$li_i."); readonly>
														<option value=P selected>Parcial</option>
												  </select>";
						   }
						else
						{
						   $la_objectconcepto[$li_i][5]="<select name=combo_tipocancel".$li_i." id=combo_tipocancel".$li_i." size=1 onchange=cargar_monto(".$li_i."); readonly>
														<option value=n selected>Seleccione...</option>
													   </select>";
						   }
						$la_objectconcepto[$li_i][6]="<input name=txtmontocancel".$li_i." type=text id=txtmontocancel".$li_i." value='".$ls_montocancel."' class=sin-borde size=15 style= text-align:center readonly>";
					}
				}
			} //for

			$li_filasconcepto=$li_i;
			$la_objectconcepto[$li_filasconcepto][1]="<input name=txtnumfac".$li_filasconcepto." type=text id=txtnumfac".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly><input name=txtnumfacasoc".$li_filasconcepto." type=hidden id=txtnumfacasoc".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly>";
			$la_objectconcepto[$li_filasconcepto][2]="<input name=txtfecemi".$li_filasconcepto." type=text id=txtfecemi".$li_filasconcepto." class=sin-borde size=10 style= text-align:left readonly>";
			$la_objectconcepto[$li_filasconcepto][3]="<input name=txtmontoiva".$li_filasconcepto." type=text id=txtmontoiva".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
			$la_objectconcepto[$li_filasconcepto][4]="<input name=txtmonto".$li_filasconcepto." type=text id=txtmonto".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
			$la_objectconcepto[$li_filasconcepto][5]="<input name=txttipocancel".$li_filasconcepto." type=text id=txttipocancel".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
			$la_objectconcepto[$li_filasconcepto][6]="<input name=txtmontocancel".$li_filasconcepto." type=text id=txtmontocancel".$li_filasconcepto." class=sin-borde size=15 style= text-align:center >";

		}// if($row=$io_sql->fetch_row($arr_detfactura))
	} //if ($arr_detfactura==false&&($io_sql->message!=""))

	/*$ls_cadena3="SELECT i.numinst,i.obsins,i.fecins,i.monto as montoins,i.codforpag,fp.denforpag,fp.metforpag,b.codban,b.nomban FROM sfc_instpagocob i,sfc_formapago fp,
	scb_banco b,sfc_cobro c WHERE i.codban=b.codban AND i.codforpag=fp.codforpag AND i.numcob=c.numcob
	AND i.numcob='".$ls_numcob."'";*/

	$ls_cadena3="SELECT i.numinst,i.obsins,i.fecins,i.monto as montoins,i.codforpag,i.ctaban,fp.denforpag,fp.metforpag,b.codban,b.nomban " .
				"FROM sfc_instpagocob i,sfc_formapago fp,scb_banco b,sfc_cobro_cliente c " .
				"WHERE i.codban=b.codban AND i.codforpag=fp.codforpag AND i.numcob=c.numcob AND i.numcob='".$ls_numcob."'";

	$arr_detints=$io_sql->select($ls_cadena3);
	if($arr_detints==false&&($io_sql->message!=""))
	{
		$io_msg->message("No hay registros de instrumentos!");
	}
	else
	{
		if($row=$io_sql->fetch_row($arr_detints))
		{
			$la_inst=$io_sql->obtener_datos($arr_detints);
			$io_datastore->data=$la_inst;
			$totrow=$io_datastore->getRowCount("numinst");
			for($li_i=1;$li_i<=$totrow;$li_i++)
			{
				$ls_numins=$io_datastore->getValue("numinst",$li_i);
				$ls_codbanco=$io_datastore->getValue("codban",$li_i);
				$ls_nombanco=$io_datastore->getValue("nomban",$li_i);
				$ls_ctaban=$io_datastore->getValue("ctaban",$li_i);
				//$ls_facret=$io_datastore->getValue("facret",$li_i);
				$ls_codforpag=$io_datastore->getValue("codforpag",$li_i);
				$ls_denforpag=$io_datastore->getValue("denforpag",$li_i);
				$ls_observacion=$io_datastore->getValue("obsins",$li_i);
				$ls_fecha=$io_datastore->getValue("fecins",$li_i);				
				$ls_fecha=substr($ls_fecha,8,2).'-'.substr($ls_fecha,5,2).'-'.substr($ls_fecha,0,4);
				$ls_montoins=$io_datastore->getValue("montoins",$li_i);
				$ls_montoinsaux=$ls_montoins;
				$ls_montoins=number_format($ls_montoins,2, ',', '.');
				$fecaux=date('Y-m-d');

				if($ls_codforpag != '09'){
					$ls_facret="N/A";
				}else{
					$ls_sqlfacret = "select ft.numfac From sfc_facturaretencion ft, sfc_dt_cobrocliente dtco " .
								"where dtco.numfac=ft.numfac AND dtco.numcob='".$ls_numcob."' AND ft.monret=".$ls_montoinsaux." " .
								"AND ft.codcli=".$ls_codcli1." ";

					//print $ls_sqlfacret;
					$arr_facret=$io_sql->select($ls_sqlfacret);
					if($arr_facret != false){
						if($rowfac=$io_sql->fetch_row($arr_facret)){
							$la_facret=$io_sql->obtener_datos($arr_facret);
							$io_dataret->data=$la_facret;
							$ls_facret=$io_dataret->getValue("numfac",1);
						}
					}else{
						$ls_facret="N/A";
					}

				}
				$ls_metforpago=$io_datastore->getValue("metforpag",$li_i);
				$la_objectfpago[$li_i][1]="<input name=txtnuminst".$li_i." type=text id=txtnuminst".$li_i." value='".$ls_numins."' class=sin-borde size=25 style= text-align:center readonly><input name=txtmetforpag".$li_i." type=hidden id=txtmetforpag".$li_i." value='".$ls_metforpago."' class=sin-borde size=15 style= text-align:center readonly>";
				$la_objectfpago[$li_i][2]="<input name=txtnombanco".$li_i." type=text id=txtnombanco".$li_i." value='".$ls_nombanco."' class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_i." type=hidden id=txtcodban".$li_i." value='".$ls_codban."' class=sin-borde size=15 style= text-align:center readonly><input name=txtctaban".$li_i." type=hidden id=txtctaban".$li_i." value='".$ls_ctaban."' class=sin-borde size=15 style= text-align:center readonly>";
				$la_objectfpago[$li_i][3]="<input name=txtfacret".$li_i." type=text id=txtfacret".$li_i." value='".$ls_facret."' class=sin-borde size=25 style= text-align:center readonly>";
				$la_objectfpago[$li_i][4]="<input name=txtdenforpag".$li_i." type=text id=txtdenforpag".$li_i." value='".$ls_denforpag."' class=sin-borde size=15 style= text-align:center readonly>";
				$la_objectfpago[$li_i][5]="<input name=txtobsins".$li_i." type=text id=txtobsins".$li_i." value='".$ls_observacion."' class=sin-borde size=45 style= text-align:left readonly><input name=txtcodforpag".$li_i." type=hidden id=txtcodforpag".$li_i." value='".$ls_codforpag."' class=sin-borde size=15 style= text-align:center readonly>";
				$la_objectfpago[$li_i][6]="<input name=txtfecins".$li_i." type=text id=txtfecins".$li_i." value='".$ls_fecha."' class=sin-borde size=10 style= text-align:left readonly>";
				$la_objectfpago[$li_i][7]="<input name=txtmontoforpag".$li_i." type=text id=txtmontoforpag".$li_i." value='".$ls_montoins."' class=sin-borde size=15 style= text-align:center readonly>";

				if ($ls_fecha==$fecaux and $ls_estatus=="F"){
				$la_objectfpago[$li_i][8]="<a href=javascript:ue_removerfpago(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
				}else{
				$la_objectfpago[$li_i][8]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center>";
				}

			}

			$li_filasfpago=$li_i;
			$la_objectfpago[$li_filasfpago][1]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=25 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
			$la_objectfpago[$li_filasfpago][2]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtctaban".$li_filasfpago." type=hidden id=txtctaban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
			$la_objectfpago[$li_filasfpago][3]="<input name=txtfacret".$li_filasfpago." type=text id=txtfacret".$li_filasfpago." class=sin-borde size=25 style= text-align:center readonly>";
			$la_objectfpago[$li_filasfpago][4]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
			$la_objectfpago[$li_filasfpago][5]="<input name=txtobsins".$li_filasfpago." type=text id=txtobsins".$li_filasfpago." class=sin-borde size=45 style= text-align:left readonly><input name=txtcodforpag".$li_filasfpago." type=hidden id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
			$la_objectfpago[$li_filasfpago][6]="<input name=txtfecins".$li_filasfpago." type=text id=txtfecins".$li_filasfpago." class=sin-borde size=10 style= text-align:center readonly>";
			$la_objectfpago[$li_filasfpago][7]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
			$la_objectfpago[$li_filasfpago][8]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";

		}
	}

	//$ls_feccob=substr($ls_feccob,8,2).'-'.substr($ls_feccob,5,2).'-'.substr($ls_feccob,0,4);
	//$ls_feccob=substr($ls_feccob,8,2).'/'.substr($ls_feccob,5,2).'/'.substr($ls_feccob,0,4);

	/********************************************   CARGAR RETENCIONES   *********************************************/
	//$ls_cadena="SELECT cd.*,d.*,d.iva,d.islr,d.otras FROM sfc_cliente c,sfc_clientededuccion cd,sigesp_deducciones d " .
		//	"WHERE d.codemp=cd.codemp AND cd.codcli=c.codcli AND d.codded=cd.codded AND cd.codcli=".$ls_codcli1;

	$ls_cadena="SELECT fr.*, d.dended, d.formula FROM sfc_facturaretencion fr, sigesp_deducciones d " .
			"WHERE d.codemp=fr.codemp AND d.codded=fr.codded AND fr.numcob='".$ls_numcob."'";

	//print $ls_cadena;
		$ls_total=0;
		$arr_retenciones=$io_sql->select($ls_cadena);
		if($arr_retenciones==false&&($io_sql->message!=""))
		{
			$is_msg->message("No hay registros de retenciones");
		}
		else
		{
			if($row=$io_sql->fetch_row($arr_retenciones))
			  {
				$la_retenciones=$io_sql->obtener_datos($arr_retenciones);
				$io_datastore->data=$la_retenciones;
				$totrow2=$io_datastore->getRowCount("codded");  // Ojo puede cambiar coddev por numfac
				//print "LI:".$totrow2;
				for($li_i=1;$li_i<=$totrow2;$li_i++)
				{

					$ls_codigo=$io_datastore->getValue("codded",$li_i);
	                $ls_descripcion=$io_datastore->getValue("dended",$li_i);
	                $ls_totalret = $io_datastore->getValue("monobret",$li_i);
	                $ls_totalret = number_format($ls_totalret,2,',','.');
	                $ls_formula = $io_datastore->getValue("formula",$li_i);
	                $ls_montoret = $io_datastore->getValue("monret",$li_i);
	                $ls_montoret = number_format($ls_montoret,2,',','.');
	                $ls_facasoc = $io_datastore->getValue("numfac",$li_i);
	                $ls_comprobante = $io_datastore->getValue("comprobante",$li_i);

					/**************************************************************************************************/

					$la_objectretenciones[$li_i][1]="<input name=txtcodret".$li_i." type=text id=txtcodret".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
					$la_objectretenciones[$li_i][2]="<input name=txtdesret".$li_i." type=text id=txtdesret".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=35 readonly>";

					$la_objectretenciones[$li_i][3]="<input name=txtmontoret".$li_i." type=text id=txtmontoret".$li_i." class=sin-borde value='".$ls_totalret."' onKeyPress=return(currencyFormat(this,'.',',',event)) style= text-align:right size=15 readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";
					/*if($ls_iva=="1")
	                   {
					    $la_objectretenciones[$li_i][3]="<input name=txtmontoret".$li_i." type=text id=txtmontoret".$li_i." class=sin-borde value='".$ls_totalret."' onKeyPress=return(currencyFormat(this,'.',',',event)) style= text-align:right size=15 readonly><input name=formula".$li_i." type=text id=formula".$li_i." value='".$ls_formula."'>";

	                   }
	                   else
	                   {
					     if ($ls_estfaccon=="" && $ls_operacion!="ue_guardar")
						  {
	                     $la_objectretenciones[$li_i][3]="<input name=txtmontoret".$li_i." type=text id=txtmontoret".$li_i." class=sin-borde value='".$ls_totalret."' onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=ue_caliva(); style= text-align:right size=15 readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";
						   }
						   else
						   {
						  $la_objectretenciones[$li_i][3]="<input name=txtmontoret".$li_i." type=text id=txtmontoret".$li_i." class=sin-borde value='".$ls_totalret."' style= text-align:right size=15 readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";
						   }
	                   }*/

					  $la_objectretenciones[$li_i][4]="<input name=txttotalret".$li_i." type=text id=txttotalret".$li_i." class=sin-borde value='".$ls_montoret."' style= text-align:right size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) onChange=javascript:ue_subtotalret(); readonly>";
					  $la_objectretenciones[$li_i][5]="<input name=txtfacasoc".$li_i." type=text id=txtfacasoc".$li_i." class=sin-borde value='".$ls_facasoc."' style= text-align:center size=26  readonly>";
					  $la_objectretenciones[$li_i][6]="<input name=chkpagar".$li_i." type=checkbox id=chkpagar".$li_i." class=sin-borde checked readonly>";
					  $la_objectretenciones[$li_i][7]="<input name=txtcomprobante".$li_i." type=text id=txtcomprobante".$li_i." class=sin-borde value='".$ls_comprobante."' style= text-align:center size=26  readonly>";

	   	 	   }//for($li_i=1;$li_i<=$totrow;$li_i++)

			$li_filasretenciones=$li_i;

			$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
			$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=35 readonly>";
			$la_objectretenciones[$li_filasretenciones][3]="<input name=txtmontoret".$li_filasretenciones." type=text id=txtmontoret".$li_filasretenciones." class=sin-borde style= text-align:right size=15 ><input name=formula".$li_filasretenciones." type=hidden id=formula".$li_filasretenciones.">";
			$la_objectretenciones[$li_filasretenciones][4]="<input name=txttotalret".$li_filasretenciones." type=text id=txttotalret".$li_filasretenciones." class=sin-borde style= text-align:right size=15 onChange=javascript:ue_subtotalret(); readonly>";
			$la_objectretenciones[$li_filasretenciones][5]="<input name=txtfacasoc".$li_filasretenciones." type=text id=txtfacasoc".$li_filasretenciones." class=sin-borde style= text-align:right size=26  readonly>";
			$la_objectretenciones[$li_filasretenciones][6]="<input name=chkpagar".$li_filasretenciones." type=checkbox id=chkpagar".$li_filasretenciones." class=sin-borde style= text-align:right >";
			$la_objectretenciones[$li_filasretenciones][7]="<input name=txtcomprobante".$li_filasretenciones." type=text id=txtcomprobante".$li_filasretenciones." class=sin-borde style= text-align:right size=26  readonly>";

         }

         //if
	     //$ls_montocadenanumero=$io_funcsob->uf_convertir_cadenanumero($ls_monto);

		 $ls_subtotalret=number_format($ls_total,2,',','.');
		 $ls_total=$io_funcsob->uf_convertir_cadenanumero($ls_total);
		 $ls_montoa=$io_funcsob->uf_convertir_cadenanumero($ls_monto);
		 $ls_totalmonret=number_format($ls_montoa-$ls_total,2,',','.');
		 $ls_resta=number_format($ls_montoa-$ls_total,2,',','.');
		// $ls_totalmonret=$ls_monto;
	}
}
elseif ($ls_operacion=="ue_cargarfacturas_cliente")
{
	$li_filasfpago=1;
	$la_objectfpago[$li_filasfpago][1]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=25 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][2]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtctaban".$li_filasfpago." type=hidden id=txtctaban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][3]="<input name=txtfacret".$li_filasfpago." type=text id=txtfacret".$li_filasfpago." class=sin-borde size=25 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][4]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][5]="<input name=txtobsins".$li_filasfpago." type=text id=txtobsins".$li_filasfpago." class=sin-borde size=45 style= text-align:left readonly><input name=txtcodforpag".$li_filasfpago." type=hidden id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][6]="<input name=txtfecins".$li_filasfpago." type=text id=txtfecins".$li_filasfpago." class=sin-borde size=10 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][7]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][8]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";

	$li_filasconcepto=1;
    $la_objectconcepto[$li_filasconcepto][1]="<input name=txtnumfac".$li_filasconcepto." type=text id=txtnumfac".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly><input name=txtnumfacasoc".$li_filasconcepto." type=hidden id=txtnumfacasoc".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtfecemi".$li_filasconcepto." type=text id=txtfecemi".$li_filasconcepto." class=sin-borde size=10 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][3]="<input name=txtmonto".$li_filasconcepto." type=text id=txtmonto".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txttipocancel".$li_filasconcepto." type=text id=txttipocancel".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly >";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txtmontocancel".$li_filasconcepto." type=text id=txtmontocancel".$li_filasconcepto." class=sin-borde size=15 style= text-align:center >";

	$existe_cobro=0;
	$existe_factura=0;
	$ls_monto2=0;
	$li_i2=0;

	/*$ls_cadena="SELECT c.codcli,c.razcli,n.numnot,n.nro_factura,f.montoret,n.dennot,n.tipnot,n.fecnot,n.estnota,n.monto,co.moncob,co.estcob, " .
			"cf.moncancel,cf.tipcancel FROM sfc_cliente c,sfc_nota n, sfc_cobro co, sfc_cobrofactura cf,sfc_factura f " .
			"WHERE n.numnot=cf.numfac AND cf.numcob=co.numcob AND n.codcli=cf.codcli AND n.estnota='P' AND cf.codcli=co.codcli " .
			"AND c.codcli=n.codcli AND n.codcli='".$ls_codcli1."' AND n.tipnot='CXC' AND f.codcli=cf.codcli AND co.codcli=f.codcli " .
					"AND n.codcli=f.codcli and f.numfac=cf.numfac AND f.codcaj='".$la_codcaj."' ORDER BY numnot ASC";*/

	$ls_cadena="SELECT c.codcli,c.razcli,n.numnot,n.nro_documento,f.monto as montofac,f.montoret,n.dennot,n.tipnot,n.fecnot,n.estnota,n.monto,co.moncob,co.estcob, " .
			"dtco.moncancel,dtco.tipcancel,dtco.estcobdev FROM sfc_cliente c,sfc_nota n, sfc_cobro_cliente co, sfc_dt_cobrocliente dtco,sfc_factura f " .
			"WHERE n.numnot=dtco.numfac AND dtco.numcob=co.numcob AND n.codcli=dtco.codcli AND n.estnota='P' AND dtco.codcli=co.codcli " .
			"AND c.codcli=n.codcli AND n.codcli=".$ls_codcli1." AND n.tipnot='CXC' AND f.codcli=dtco.codcli AND co.codcli=f.codcli " .
			"AND n.codcli=f.codcli and f.numfac=dtco.numfac  ORDER BY numnot ASC";
//			"AND n.codcli=f.codcli and f.numfac=dtco.numfac "./*AND f.cod_caja='".$la_codcaj*/." ORDER BY numnot ASC";


	$arr_detfactura=$io_sql->select($ls_cadena);
	$w=$io_sql->fetch_row($arr_detfactura);
	if ($w!="")
	{
		$arr_detfactura=$io_sql->select($ls_cadena);
		$w=$io_sql->fetch_row($arr_detfactura);
		$existe_cobro=1;
	}
	else
	{
		$existe_cobro=0;
	}

	if ($existe_cobro==1) // Si existe por lo menos un cobro
	{
		$arr_detfactura=$io_sql->select($ls_cadena);
		if($row=$io_sql->fetch_row($arr_detfactura))
		{
			$la_producto=$io_sql->obtener_datos($arr_detfactura);
			$io_datastore->data=$la_producto;
			$totrow=$io_datastore->getRowCount("codcli");
			$ls_suiche=false;
			for($li_i=1;$li_i<=$totrow;$li_i++)
			{
				$li_i2=$li_i2+1;
				$ls_numfac=$io_datastore->getValue("numnot",$li_i);
				$ls_numfacasoc=$io_datastore->getValue("nro_documento",$li_i);
				$ls_fecemi=$io_datastore->getValue("fecnot",$li_i);
				$ls_estatuscob=$io_datastore->getValue("estcob",$li_i);
				$ls_moncancel=$io_datastore->getValue("moncancel",$li_i);
				$ls_montoiva=$io_datastore->getValue("montoret",$li_i);
				$ls_montofac=$io_datastore->getValue("montofac",$li_i);
				$ls_estcobdev=$io_datastore->getValue("estcobdev",$li_i);
				if ($ls_estatuscob=='A')
				{
				$ls_moncancel=0;
				}

				if (($ls_numfac==$io_datastore->getValue("numnot",$li_i-1)) && ($li_i>1))
				{
					$li_i2=$li_i2-1;
					if($ls_estcobdev == 'N'){
						$ls_monto2=$ls_monto2-$ls_moncancel;
					}else{
						$ls_monto2=$ls_monto2;
					}
				}
				else
				{
					$ls_monto2=$io_datastore->getValue("monto",$li_i);
					if($ls_estcobdev == 'N'){
						$ls_monto2=$ls_monto2-$ls_moncancel;
					}else{
						$ls_monto2=$ls_monto2;
					}
				}

				$ls_monto=$ls_monto2;
				$arreglo_cobro[$li_i2]["numnot"]=$ls_numfac;
				$arreglo_cobro[$li_i2]["nro_documento"]=$ls_numfacasoc;
				$arreglo_cobro[$li_i2]["fecnot"]=$ls_fecemi;
				$arreglo_cobro[$li_i2]["monto"]=$ls_monto;
				$arreglo_cobro[$li_i2]["montoret"]=$ls_montoiva;
				$arreglo_cobro[$li_i2]["montofac"]=$ls_montofac;
			} //for
		}//if($row=$io_sql->fetch_row($arr_detfactura)
	 } //if($existe_cobro==1)

	 $ls_cadena="SELECT c.codcli,c.razcli,n.numnot,n.nro_documento,f.monto as montofac,f.montoret,n.fecnot,n.monto".
				" FROM sfc_cliente c,sfc_nota n,sfc_factura f ".
				" WHERE c.codcli=n.codcli AND c.codcli=".$ls_codcli1."  AND n.estnota='P' AND n.numnot NOT IN " .  // AND f.cod_caja='".$la_codcaj."'     
				" (SELECT dtco.numfac FROM sfc_dt_cobrocliente dtco,sfc_nota n WHERE  n.numnot=dtco.numfac)  AND n.tipnot='CXC' " .
				" AND n.numnot=n.nro_documento AND f.numfac=n.nro_documento " .
				"UNION SELECT c.codcli,c.razcli,n.numnot,n.nro_documento,n.monto as montofac,f.montoret,n.fecnot,n.monto " .
				"FROM sfc_cliente c,sfc_nota n,sfc_factura f " .
				"WHERE c.codcli=n.codcli AND c.codcli=".$ls_codcli1."  AND n.estnota='P' AND n.codcli=".$ls_codcli1." " . //AND f.cod_caja='".$la_codcaj."'
				"AND f.numfac='0000000000000000000000000' AND f.numfac=n.nro_documento AND n.tipnot='CXC' ";


	$arr_detfactura=$io_sql->select($ls_cadena);
	$w=$io_sql->fetch_row($arr_detfactura);
	if ($w!="")
	{
		$arr_detfactura=$io_sql->select($ls_cadena);
		$w=$io_sql->fetch_row($arr_detfactura);
		$existe_factura=1;
	}
	else
	{
		$existe_factura=0;
	}

	if ($existe_factura==1)
	{
		$arr_detfactura=$io_sql->select($ls_cadena);
		if($row=$io_sql->fetch_row($arr_detfactura))
		{
			$la_producto=$io_sql->obtener_datos($arr_detfactura);
			$io_datastore->data=$la_producto;
			$totrow=$io_datastore->getRowCount("codcli");
			for($li_i=1;$li_i<=$totrow;$li_i++)
			{
				$li_i2=$li_i2+1;
				$ls_numfac=$io_datastore->getValue("numnot",$li_i);
				$ls_numfacasoc=$io_datastore->getValue("nro_documento",$li_i);
				$ls_fecemi=$io_datastore->getValue("fecnot",$li_i);
				$ls_monto2=$io_datastore->getValue("monto",$li_i);
				$ls_montocancel=$io_datastore->getValue("txtmontocancel",$li_i);
				$ls_montoiva=$io_datastore->getValue("montoret",$li_i);
				$ls_montofac=$io_datastore->getValue("montofac",$li_i);
				$ls_monto=$ls_monto2;

				$arreglo_cobro[$li_i2]["numnot"]=$ls_numfac;
				$arreglo_cobro[$li_i2]["nro_documento"]=$ls_numfacasoc;
				$arreglo_cobro[$li_i2]["fecnot"]=$ls_fecemi;
				$arreglo_cobro[$li_i2]["monto"]=$ls_monto2;
				$arreglo_cobro[$li_i2]["montoret"]=$ls_montoiva;
				$arreglo_cobro[$li_i2]["montofac"]=$ls_montofac;
			} //for
		}//if($row=$io_sql->fetch_row($arr_detfactura)
	 } //if($existe_factura==1)

	if (($existe_factura==1) || ($existe_cobro==1))
	{
		//$ls_numcob= $io_funcdb->uf_generar_codigo(false,0,"sfc_cobro","numcob",25);
		$ls_nrocartas=0;
		for($li_i=1;$li_i<=$li_i2;$li_i++)
		{
			$ls_numfac=$arreglo_cobro[$li_i]["numnot"];
			$ls_numfacasoc=$arreglo_cobro[$li_i]["nro_documento"];
			$ls_fecemi=$arreglo_cobro[$li_i]["fecnot"];
			$ls_monto=$arreglo_cobro[$li_i]["monto"];
			$ls_montoiva=$arreglo_cobro[$li_i]["montoret"];
			$ls_montocancel="0,00";
			$ls_monto=number_format($ls_monto,2, ',', '.');
			$ls_montoiva=number_format($ls_montoiva,2, ',', '.');

			if ($ls_monto=="0,00")
			{
				$la_objectconcepto[$li_i][1]="<input name=txtnumfac".$li_i." style=color:#FF0000 type=text id=txtnumfac".$li_i." value='".$ls_numfac."' class=sin-borde size=30 style= text-align:center readonly><input name=txtnumfacasoc".$li_i." style=color:#FF0000 type=hidden id=txtnumfacasoc".$li_i." value='".$ls_numfacasoc."' class=sin-borde size=30 style= text-align:center readonly>";
				$la_objectconcepto[$li_i][2]="<input name=txtfecemi".$li_i." style=color:#FF0000 type=text id=txtfecemi".$li_i." value='".$ls_fecemi."' class=sin-borde size=10 style= text-align:left readonly>";
				$la_objectconcepto[$li_i][3]="<input name=txtmontoiva".$li_i."  type=text id=txtmontoiva".$li_i." value='".$ls_montoiva."' class=sin-borde size=15 style= text-align:center readonly>";
				$la_objectconcepto[$li_i][4]="<input name=txtmonto".$li_i."  style=color:#FF0000 type=text id=txtmonto".$li_i." value='".$ls_monto."' class=sin-borde size=15 style= text-align:center readonly>";
				$la_objectconcepto[$li_i][5]="<select name=combo_tipocancel".$li_i." id=combo_tipocancel".$li_i." size=1 onchange=cargar_monto(".$li_i.");ue_calretencion($li_i);>  	<option value=T selected >Total</option>
														</select>";
				$la_objectconcepto[$li_i][6]="<input name=txtmontocancel".$li_i." type=text id=txtmontocancel".$li_i." value='".$ls_montocancel."' class=sin-borde size=15 style=color:#FF0000 style= text-align:center readonly onBlur=ue_calretencion($li_i);>";
			}
			else
			{
				$la_objectconcepto[$li_i][1]="<input name=txtnumfac".$li_i." type=text id=txtnumfac".$li_i." value='".$ls_numfac."' class=sin-borde size=30 style= text-align:center readonly><input name=txtnumfacasoc".$li_i." type=hidden id=txtnumfacasoc".$li_i." value='".$ls_numfacasoc."' class=sin-borde size=30 style= text-align:center readonly>";
				$la_objectconcepto[$li_i][2]="<input name=txtfecemi".$li_i." type=text id=txtfecemi".$li_i." value='".$ls_fecemi."' class=sin-borde size=10 style= text-align:left readonly>";
				$la_objectconcepto[$li_i][3]="<input name=txtmontoiva".$li_i."  type=text id=txtmontoiva".$li_i." value='".$ls_montoiva."' class=sin-borde size=15 style= text-align:center readonly>";
				$la_objectconcepto[$li_i][4]="<input name=txtmonto".$li_i." type=text id=txtmonto".$li_i." value='".$ls_monto."' class=sin-borde size=15 style= text-align:center readonly>";
				/*$la_objectconcepto[$li_i][5]="<select name=combo_tipocancel".$li_i." id=combo_tipocancel".$li_i." size=1 onchange=cargar_monto(".$li_i.");ue_calretencion($li_i);>  	<option value=n selected onClick=ue_calretencion($li_i)>Seleccione...</option>
																<option value=T onClick=ue_calretencion($li_i)>Total</option>
																<option value=P onClick=ue_calretencion($li_i)>Parcial</option>
															  </select>";
			  	$la_objectconcepto[$li_i][6]="<input name=txtmontocancel".$li_i." type=text id=txtmontocancel".$li_i." value='".$ls_montocancel."' class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=ue_calretencion($li_i);>";*/
			  	$la_objectconcepto[$li_i][5]="<select name=combo_tipocancel".$li_i." id=combo_tipocancel".$li_i." size=1 onchange=cargar_monto(".$li_i.");ue_calretencion($li_i);>  	<option value=n selected >Seleccione...</option>
																<option value=T >Total</option>
																<option value=P >Parcial</option>
															  </select>";
			  	$la_objectconcepto[$li_i][6]="<input name=txtmontocancel".$li_i." type=text id=txtmontocancel".$li_i." value='".$ls_montocancel."' class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=ue_calretencion($li_i);>";
			}
		} //for

		$li_filasconcepto=$li_i;
		$la_objectconcepto[$li_filasconcepto][1]="<input name=txtnumfac".$li_filasconcepto." type=text id=txtnumfac".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly><input name=txtnumfacasoc".$li_filasconcepto." type=text id=txtnumfacasoc".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][2]="<input name=txtfecemi".$li_filasconcepto." type=text id=txtfecemi".$li_filasconcepto." class=sin-borde size=10 style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][3]="<input name=txtmontoiva".$li_filasconcepto."  type=text id=txtmontoiva".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][4]="<input name=txtmonto".$li_filasconcepto." type=text id=txtmonto".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][5]="<input name=txttipocancel".$li_filasconcepto." type=text id=txttipocancel".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][6]="<input name=txtmontocancel".$li_filasconcepto." type=text id=txtmontocancel".$li_filasconcepto." class=sin-borde size=15 style= text-align:center >";

		/********************************************   CARGAR RETENCIONES   *********************************************/
		$ls_cadena="SELECT cd.*,d.dended,d.formula,d.iva,d.islr,d.estretmun,d.otras FROM sfc_cliente c,sfc_clientededuccion cd,sigesp_deducciones d " .
				"WHERE d.codemp=cd.codemp AND cd.codcli=c.codcli AND d.codded=cd.codded AND cd.codcli='".$ls_codcli1."'";

		/*print "<br>".$ls_cadena;
		print "<pre>";
		var_dump($arreglo_cobro);
		print "</pre>";*/
		$ctafila = count($arreglo_cobro);

			$ls_total=0;
			$arr_retenciones=$io_sql->select($ls_cadena);
			if($arr_retenciones==false&&($io_sql->message!=""))
			{
				$is_msg->message("No hay registros de retenciones");
			}
			else
			{
				if($row=$io_sql->fetch_row($arr_retenciones))
 				  {
					$la_retenciones=$io_sql->obtener_datos($arr_retenciones);
					$io_datastore->data=$la_retenciones;
					$totrow=$io_datastore->getRowCount("codded");  // Ojo puede cambiar coddev por numfac
					//print "LI:".$totrow;
					$aux2 = 0;
					for($li_i=1;$li_i<=$totrow;$li_i++)
					{
						$ls_codigo=$io_datastore->getValue("codded",$li_i);
		                $ls_descripcion=$io_datastore->getValue("dended",$li_i);
						$ls_formula=$io_datastore->getValue("formula",$li_i);
						$ls_iva=$io_datastore->getValue("iva",$li_i);
						$ls_islr=$io_datastore->getValue("islr",$li_i);
						$ls_otras=$io_datastore->getValue("otras",$li_i);
						$ls_estretmun=$io_datastore->getValue("estretmun",$li_i);

						for($li_aux=1; $li_aux<=$ctafila; $li_aux++){
							$aux2++;
							$la_objectretenciones[$aux2][1]="<input name=txtcodret".$aux2." type=text id=txtcodret".$aux2." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
							$la_objectretenciones[$aux2][2]="<input name=txtdesret".$aux2." type=text id=txtdesret".$aux2." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=35 readonly>";

							$ls_montoret = "0,00";
							$ls_totalret = "0,00";
							$ls_facasoc = "";

							if($ls_iva == 1){

								$ls_monobjret=$arreglo_cobro[$li_aux]["montoret"];
								$ls_monobjretcal=$ls_monobjret;
								$ls_montoret=number_format($ls_monobjret,2,',','.');

								$ld_result=$io_evalform->uf_evaluar($ls_formula,$ls_monobjretcal,$lb_valido);
								$ls_totalret=$io_funcsob->uf_convertir_numerocadena($ld_result);
								//$ls_totalret=number_format($ls_totalret,2,',','.');

								$ls_facasoc = $arreglo_cobro[$li_aux]["numnot"];

								$la_objectretenciones[$aux2][3]="<input name=txtmontoret".$aux2." type=text id=txtmontoret".$aux2." class=sin-borde value='".$ls_montoret."' onKeyPress=return(currencyFormat(this,'.',',',event)) style= text-align:right size=15 readonly><input name=formula".$aux2." type=hidden id=formula".$aux2." value='".$ls_formula."'>";
								$la_objectretenciones[$aux2][4]="<input name=txttotalret".$aux2." type=text id=txttotalret".$aux2." class=sin-borde onKeyPress=return(currencyFormat(this,'.',',',event)) value='".$ls_totalret."' style= text-align:right size=15 onChange=javascript:ue_subtotalret(); >";
								$la_objectretenciones[$aux2][5]="<input name=txtfacasoc".$aux2." type=text id=txtfacasoc".$aux2." class=sin-borde value='".$ls_facasoc."' style= text-align:center size=26 readonly>";
								$la_objectretenciones[$aux2][6]="<input name=chkpagar".$aux2." type=checkbox id=chkpagar".$aux2." class=sin-borde onChange=javascript:ue_subtotalret();>";
								$la_objectretenciones[$aux2][7]="<input name=txtcomprobante".$aux2." type=text id=txtcomprobante".$aux2." class=sin-borde value='' style= text-align:center size=26 maxlength=25 >";

							}elseif(($ls_islr == 1) or ($ls_otras == 1)  or ($ls_estretmun == 1) ){

								$ls_monobjret=$arreglo_cobro[$li_aux]["montofac"] - $arreglo_cobro[$li_aux]["montoret"];
								$ls_monobjretcal=$ls_monobjret;
								$ls_montoret=number_format($ls_monobjret,2,',','.');

								$ld_result=$io_evalform->uf_evaluar($ls_formula,$ls_monobjretcal,$lb_valido);
								$ls_totalret=$io_funcsob->uf_convertir_numerocadena($ld_result);
								//$ls_totalret=number_format($ls_totalret,2,',','.');

								$ls_facasoc = $arreglo_cobro[$li_aux]["numnot"];

								$la_objectretenciones[$aux2][3]="<input name=txtmontoret".$aux2." type=text id=txtmontoret".$aux2." class=sin-borde value='".$ls_montoret."' onKeyPress=return(currencyFormat(this,'.',',',event)) style= text-align:right size=15 readonly><input name=formula".$aux2." type=hidden id=formula".$aux2." value='".$ls_formula."'>";
								$la_objectretenciones[$aux2][4]="<input name=txttotalret".$aux2." type=text id=txttotalret".$aux2." class=sin-borde onKeyPress=return(currencyFormat(this,'.',',',event)) value='".$ls_totalret."' style= text-align:right size=15 onChange=javascript:ue_subtotalret(); >";
								$la_objectretenciones[$aux2][5]="<input name=txtfacasoc".$aux2." type=text id=txtfacasoc".$aux2." class=sin-borde value='".$ls_facasoc."' style= text-align:center size=26 readonly>";
								$la_objectretenciones[$aux2][6]="<input name=chkpagar".$aux2." type=checkbox id=chkpagar".$aux2." class=sin-borde onChange=javascript:ue_subtotalret();>";
								$la_objectretenciones[$aux2][7]="<input name=txtcomprobante".$aux2." type=text id=txtcomprobante".$aux2." class=sin-borde value='' style= text-align:center size=26 maxlength=25 >";

							}else{
								$la_objectretenciones[$aux2][3]="<input name=txtmontoret".$aux2." type=text id=txtmontoret".$aux2." class=sin-borde value='".$ls_montoret."' onKeyPress=return(currencyFormat(this,'.',',',event)) style= text-align:right size=15 readonly><input name=formula".$aux2." type=hidden id=formula".$aux2." value='".$ls_formula."'>";
								$la_objectretenciones[$aux2][4]="<input name=txttotalret".$aux2." type=text id=txttotalret".$aux2." class=sin-borde onKeyPress=return(currencyFormat(this,'.',',',event)) value='".$ls_totalret."' style= text-align:right size=15 onChange=javascript:ue_subtotalret(); >";
								$la_objectretenciones[$aux2][5]="<input name=txtfacasoc".$aux2." type=text id=txtfacasoc".$aux2." class=sin-borde value='".$ls_facasoc."' style= text-align:center size=26 readonly>";
								$la_objectretenciones[$aux2][6]="<input name=chkpagar".$aux2." type=checkbox id=chkpagar".$aux2." class=sin-borde onChange=javascript:ue_subtotalret();>";
								$la_objectretenciones[$aux2][7]="<input name=txtcomprobante".$aux2." type=text id=txtcomprobante".$aux2." class=sin-borde value='' style= text-align:center size=26 maxlength=25 >";
							}

						}

						/*$ls_monobjret=$io_datastore->getValue("monobjret",$li_i);
						$ls_montoret=number_format($ls_monobjret,2,',','.');

						$ls_monret=$io_datastore->getValue("monret",$li_i);//
						$ls_totalret=number_format($ls_monret,2,',','.');

						$ls_total=$ls_total+$ls_monret;*/ //acumula montos retenidos
						//print "LS_TOTAL:".$ls_total;
						/**************************************************************************************************/
						/*$la_objectretenciones[$li_i][1]="<input name=txtcodret".$li_i." type=text id=txtcodret".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
						$la_objectretenciones[$li_i][2]="<input name=txtdesret".$li_i." type=text id=txtdesret".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=35 readonly>";
						if($ls_iva=="1")
		                   {

		                     $la_objectretenciones[$li_i][3]="<input name=txtmontoret".$li_i." type=text id=txtmontoret".$li_i." class=sin-borde value='".$ls_montoret."' onKeyPress=return(currencyFormat(this,'.',',',event)) style= text-align:right size=15 readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";

		                   }
		                   else
		                   {
						     if ($ls_estfaccon=="" && $ls_operacion!="ue_guardar")
							  {
		                     $la_objectretenciones[$li_i][3]="<input name=txtmontoret".$li_i." type=text id=txtmontoret".$li_i." class=sin-borde value='".$ls_montoret."' onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=ue_caliva(); style= text-align:right size=15 readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";
							   }
							   else
							   {
							  $la_objectretenciones[$li_i][3]="<input name=txtmontoret".$li_i." type=text id=txtmontoret".$li_i." class=sin-borde value='".$ls_montoret."' style= text-align:right size=15 readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";
							   }
		                   }

						  $la_objectretenciones[$li_i][4]="<input name=txttotalret".$li_i." type=text id=txttotalret".$li_i." class=sin-borde onKeyPress=return(currencyFormat(this,'.',',',event)) value='".$ls_montoret."' style= text-align:right size=15>";
						*/
		   	 	   	}//for($li_i=1;$li_i<=$totrow;$li_i++)

					//$li_filasretenciones=$li_i;
					$li_filasretenciones=$aux2+1;

					$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
					$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=35 readonly>";
					$la_objectretenciones[$li_filasretenciones][3]="<input name=txtmontoret".$li_filasretenciones." type=text id=txtmontoret".$li_filasretenciones." class=sin-borde style= text-align:right size=15 ><input name=formula".$li_filasretenciones." type=hidden id=formula".$li_filasretenciones.">";
					$la_objectretenciones[$li_filasretenciones][4]="<input name=txttotalret".$li_filasretenciones." type=text id=txttotalret".$li_filasretenciones." class=sin-borde style= text-align:right size=15 readonly onChange=javascript:ue_subtotalret(); >";
	             }

            //if
		    //$ls_montocadenanumero=$io_funcsob->uf_convertir_cadenanumero($ls_monto);
			/* $ls_subtotalret=number_format($ls_total,2,',','.');
			 $ls_total=$io_funcsob->uf_convertir_cadenanumero($ls_total);
			 $ls_montoa=$io_funcsob->uf_convertir_cadenanumero($ls_monto);
			 $ls_totalmonret=number_format($ls_montoa-$ls_total,2,',','.');
			 $ls_resta=number_format($ls_montoa-$ls_total,2,',','.');*/
			// $ls_totalmonret=$ls_monto;
			$ls_totalmonret='0,00';
		}
	}
	else
	{
		$io_msg->message ("El cliente no tiene facturas por cobrar!!!");
		$ls_numcob="";
		$ls_codcli="";
		$ls_codcli1="";
		$ls_nomcli="";
		$ls_feccob="";
		$ls_observacion="";
	}
}
elseif($ls_operacion=="ue_removerfpago")
{
  	$li_filasfpago=$_POST["txtfilasfpago"];
	$li_filasfpago=$li_filasfpago - 1;
	$li_removerfpago=$_POST["hidremoverfpago"]; /*** hid(oculto)+removerconcepto*/
	$li_temp=0;
	for($li_i=1;$li_i<=$li_filasfpago;$li_i++)
	{
		if ($li_i!=$li_removerfpago)
		{
		 $li_temp=$li_temp+1;
		 $ls_numinst=$_POST["txtnuminst".$li_i];
		 $ls_nombanco=$_POST["txtnombanco".$li_i];
		 $ls_ctaban=$_POST["txtctaban".$li_i];
		 $ls_facret=$_POST["txtfacret".$li_i];
		 $ls_codban=$_POST["txtcodban".$li_i];
		 $ls_denforpag=$_POST["txtdenforpag".$li_i];
		 $ls_obsins=$_POST["txtobsins".$li_i];
		 $ls_codforpag=$_POST["txtcodforpag".$li_i];
		 $ls_fecins=$_POST["txtfecins".$li_i];
		 $ls_montoforpag=$_POST["txtmontoforpag".$li_i];

		$la_objectfpago[$li_temp][1]="<input name=txtnuminst".$li_temp." type=text id=txtnuminst".$li_temp." value='".$ls_numinst."' class=sin-borde size=25 style= text-align:center readonly><input name=txtmetforpag".$li_temp." type=hidden id=txtmetforpag".$li_temp." value='".$ls_metforpago."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectfpago[$li_temp][2]="<input name=txtnombanco".$li_temp." type=text id=txtnombanco".$li_temp." value='".$ls_nombanco."' class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_temp." type=hidden id=txtcodban".$li_temp." value='".$ls_codban."' class=sin-borde size=15 style= text-align:center readonly><input name=txtctaban".$li_i." type=hidden id=txtctaban".$li_i." value='".$ls_ctaban."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectfpago[$li_temp][3]="<input name=txtfacret".$li_temp." type=text id=txtfacret".$li_temp." value='".$ls_facret."' class=sin-borde size=25 style= text-align:center readonly>";
		$la_objectfpago[$li_temp][4]="<input name=txtdenforpag".$li_temp." type=text id=txtdenforpag".$li_temp." value='".$ls_denforpag."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectfpago[$li_temp][5]="<input name=txtobsins".$li_temp." type=text id=txtobsins".$li_temp." value='".$ls_obsins."' class=sin-borde size=45 style= text-align:left readonly><input name=txtcodforpag".$li_temp." type=hidden id=txtcodforpag".$li_temp." value='".$ls_codforpag."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectfpago[$li_temp][6]="<input name=txtfecins".$li_temp." type=text id=txtfecins".$li_temp." value='".$ls_fecins."' class=sin-borde size=10 style= text-align:left readonly>";
		$la_objectfpago[$li_temp][7]="<input name=txtmontoforpag".$li_temp." type=text id=txtmontoforpag".$li_temp." value='".$ls_montoforpag."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectfpago[$li_temp][8]="<a href=javascript:ue_removerfpago(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
	  }else{
	  	$ls_facret=$_POST["txtfacret".$li_i];
	  	$ls_codforpag=$_POST["txtcodforpag".$li_i];

	  	/*if($ls_codforpag=='09'){
	  		$io_factura->uf_delete_facturaretencion($ls_facret,$la_seguridad);
	  	}*/
	  }
   }

	$la_objectfpago[$li_filasfpago][1]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=25 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][2]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtctaban".$li_filasfpago." type=hidden id=txtctaban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][3]="<input name=txtfacret".$li_filasfpago." type=text id=txtfacret".$li_filasfpago." class=sin-borde size=25 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][4]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][5]="<input name=txtobsins".$li_filasfpago." type=text id=txtobsins".$li_filasfpago." class=sin-borde size=45 style= text-align:left readonly><input name=txtcodforpag".$li_filasfpago." type=hidden id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][6]="<input name=txtfecins".$li_filasfpago." type=text id=txtfecins".$li_filasfpago." class=sin-borde size=10 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][7]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectfpago[$li_filasfpago][8]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
}
elseif($ls_operacion=="ue_guardar")
{
	/********************************   VERIFICA SI USUARIO ES PERSONAL DE LA TIENDA   *****************************************/
	$ls_codtie=$_SESSION["ls_codtienda"];
	//$ls_codtie="0002";
	if($ls_codtie!="")
	{
	 $ls_codusu=$_SESSION["la_logusr"];
	 $lb_valido_logusr=true;
	}
    else
	{
	 $lb_valido_logusr=false;
	 $io_msg->message ("El usuario no es personal de la tienda no puede generar cobros de facturas !!!");
	}
	
	$lb_existe=$io_cobranza->uf_select_cobro($ls_numcob,$ls_codtie);

/*	if ($lb_existe)
	{	
		$ls_numcob=$io_funciones_sfc->uf_generer_consecutivo("sfc_cobro_cliente","numcob",$ls_prefijo,$ls_serie,25);
	}
	else
	{*/
		$ls_estcob="E";

		//$ls_codcaj=$_SESSION["ls_codcaj"];
		$ls_prefijo=$_SESSION["ls_precob"];
		$ls_serie=$_SESSION["ls_sercob"];
		//$ls_codcaj=$la_codcaj;
		//$ls_prefijo="COB";
		//$ls_serie="01";

		/*$io_secuencia->uf_obtener_secuencia($ls_codcaj.$ls_codtie."cob",&$ls_secuencia);
		$ls_numcob=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);*/

		if ($lb_existe and $ls_operacion1!="ue_nuevo")
		{
			$io_msg->message ("El Cobro con el c�digo: ".$ls_numcob." fue actualizada satisfactoriamente");
		} elseif ($lb_existe and $ls_operacion1=="ue_nuevo")
		{
			$ls_operacion1="";
			$ls_numcob=$io_funciones_sfc->uf_generer_consecutivo("sfc_cobro_cliente","numcob",$ls_prefijo,$ls_serie,25);
			$io_msg->message (utf8_encode("El Cobro sera guardado con el c�digo: ".$ls_numcob));
		}
		else
		{
			$ls_operacion1="";
			$ls_numcob=$io_funciones_sfc->uf_generer_consecutivo("sfc_cobro_cliente","numcob",$ls_prefijo,$ls_serie,25);
			$io_msg->message (utf8_encode("El Cobro sera guardado con el c�digo: ".$ls_numcob));

		}
	
	 ///////////////////////////RETENCIONES/////////////////////////////////

	 if ($li_filasretenciones>1)
	 {

		   $lb_valido1=$io_cobranza->uf_guardar_cobrocliente($ls_codcli1,$ls_numcob,$ls_observacion,$ls_feccob,$ls_moncob,$ls_codusu,$ls_estcob,$ls_especial,"0,00",$ls_codtie,"0,00",$ls_codcaj,$la_seguridad);
		   //$lb_valido=$io_factura->uf_guardar_facturaretencion($ls_numcob,$la_detalles["codret"][1],$la_detalles["montoret"][1],$la_detalles["totalret"][1],$ls_codtie,$la_seguridad)
	
		   $lb_validoret = true;
	
		  for ($li_i=1;$li_i<$li_filasretenciones;$li_i++)
		  {
			$la_detalles["codret"][$li_i]=$_POST["txtcodret".$li_i];
			$la_codret = $la_detalles["codret"][$li_i];
			$la_detalles["montoret"][$li_i]=$_POST["txtmontoret".$li_i];
			$la_montoret = $la_detalles["montoret"][$li_i];
			$la_detalles["totalret"][$li_i]=$_POST["txttotalret".$li_i];
			$la_totalret = $la_detalles["totalret"][$li_i];
			$la_detalles["facasoc"][$li_i]=$_POST["txtfacasoc".$li_i];
			$la_facasoc = $la_detalles["facasoc"][$li_i];
			$la_detalles["comprobante"][$li_i]=$_POST["txtcomprobante".$li_i];
			$la_comprobante = $la_detalles["comprobante"][$li_i];
			$la_detalles["chkpagar"][$li_i]=$_POST["chkpagar".$li_i];
	
			if($la_detalles["chkpagar"][$li_i] == "on" and $lb_validoret){
				$lb_valido=$io_factura->uf_guardar_facturaretencion($la_facasoc,$la_codret,$ls_codcli1,$la_montoret,$la_totalret,$ls_codtie,$ls_numcob,$la_comprobante,$la_seguridad);
				if(!$lb_valido){
					//$li_i=$li_filasretenciones;
					$lb_validoret = false;
				}
			}
	
		  }
	
		  if($lb_validoret == false){
			$io_factura->io_sql->rollback();
		  }
	
		  /*$la_codret = $la_detalles["codret"][1];
		  $la_montoret = $la_detalles["montoret"][1];
		  $la_totalret = $la_detalles["totalret"][1];*/

	 }
	 else
	 {
		   $lb_valido1=$io_cobranza->uf_guardar_cobrocliente($ls_codcli1,$ls_numcob,$ls_observacion,$ls_feccob,$ls_moncob,$ls_codusu,$ls_estcob,$ls_especial,"0,00",$ls_codtie,"0,00",$ls_codcaj,$la_seguridad);
		   $la_codret = "";
		   $la_montoret = "";
		   $la_totalret = "";
	 }
	  /////////////////////////////////////////////////////////////////////////
	$lb_valid_dtcobro=true;  
	for ($li_i=1;$li_i<$li_filasconcepto && $lb_valid_dtcobro;$li_i++)
     {
     	$ls_tipcancel=$_POST["combo_tipocancel".$li_i];
	    $ls_numfac=$_POST["txtnumfac".$li_i];
		$ls_numfacasoc=$_POST["txtnumfacasoc".$li_i];
		$ls_fecemi=$_POST["txtfecemi".$li_i];
	    $ls_monto=$_POST["txtmonto".$li_i];
	    $ls_moncancel=$_POST["txtmontocancel".$li_i];
		if($ls_tipcancel<>'n')
		{
			$lb_valid_dtcobro=$io_cobranza->uf_guardar_detcobro($ls_codtie,$ls_codcli1,$ls_numcob,$ls_numfac,$ls_tipcancel,$ls_moncancel,$ls_monto,$ls_numfacasoc,$la_seguridad);   		
	 	}
	 }
		$ls_mensaje=$io_cobranza->io_msgc;
		if ( ($lb_valido1==true) and ($lb_validoret==true) )
		{
			//$io_cobranza->io_sql->commit();
			//$io_msg->message ($ls_mensaje);
		}
		else
		{
			if($lb_valido1==0)
			{
				$ls_operacion="";
			}
			else
			{
				$io_msg->message ($ls_mensaje);
			}
		}
	
		$la_objectfpago[$li_filasfpago][1]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=25 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectfpago[$li_filasfpago][2]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtctaban".$li_filasfpago." type=hidden id=txtctaban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectfpago[$li_filasfpago][3]="<input name=txtfacret".$li_filasfpago." type=text id=txtfacret".$li_filasfpago." class=sin-borde size=25 style= text-align:center readonly>";
		$la_objectfpago[$li_filasfpago][4]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectfpago[$li_filasfpago][5]="<input name=txtobsins".$li_filasfpago." type=text id=txtobsins".$li_filasfpago." class=sin-borde size=45 style= text-align:left readonly><input name=txtcodforpag".$li_filasfpago." type=hidden id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectfpago[$li_filasfpago][6]="<input name=txtfecins".$li_filasfpago." type=text id=txtfecins".$li_filasfpago." class=sin-borde size=10 style= text-align:center readonly>";
		$la_objectfpago[$li_filasfpago][7]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectfpago[$li_filasfpago][8]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
	
		$la_detalles["numinst"][1]="";
		$la_detalles["nomban"][1]="";
		$la_detalles["facret"][1]="";
		$la_detalles["codban"][1]="";
		$la_detalles["ctaban"][1]="";
		$la_detalles["denforpag"][1]="";
		$la_detalles["codforpag"][1]="";
		$la_detalles["obsins"][1]="";
		$la_detalles["fecins"][1]="";
		$la_detalles["monto"][1]="";
	
		for ($li_i=1;$li_i<$li_filasfpago;$li_i++)
		 {
		   $la_detalles["numinst"][$li_i]=$_POST["txtnuminst".$li_i];
		   $la_detalles["nomban"][$li_i]=$_POST["txtnombanco".$li_i];
		   $la_detalles["facret"][$li_i]=$_POST["txtfacret".$li_i];
		   $la_detalles["codban"][$li_i]=$_POST["txtcodban".$li_i];
		   $la_detalles["ctaban"][$li_i]=$_POST["txtctaban".$li_i];
		   $la_detalles["denforpag"][$li_i]=$_POST["txtdenforpag".$li_i];
		   $la_detalles["codforpag"][$li_i]=$_POST["txtcodforpag".$li_i];
		   $la_detalles["obsins"][$li_i]=$_POST["txtobsins".$li_i];
		   $la_detalles["fecins"][$li_i]=$_POST["txtfecins".$li_i];
		   $la_detalles["monto"][$li_i]=$_POST["txtmontoforpag".$li_i];
	
		   /*if($la_detalles["codforpag"][$li_i]=='09'){
			$lb_valido=$io_factura->uf_guardar_facturaretencion($la_detalles["facret"][$li_i],$la_codret,$ls_codcli1,$la_montoret,$la_totalret,$ls_codtie,$la_seguridad);
		   }*/
		 }
	
		 //$io_instpagocob->io_sql->begin_transaction();
		 $lb_valido=$io_instpagocob->uf_update_detalles_instrumentopagocob($ls_codtie,$ls_codcli1,$ls_numcob,$la_detalles,$li_filasfpago);
	
			$ls_mensaje=$io_instpagocob->io_msgc;
			if ($lb_valido==true)
			{
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
					$io_msg->message ($ls_mensaje);
				}
			}
			/*****************    ACTUALIZAR SALDOS PENDIENTES   ***************************************************/
			$ls_suichemet=false;
			for ($li_i=1;$li_i<$li_filasfpago;$li_i++)
			 {
			   $ls_codforpag=$_POST["txtcodforpag".$li_i];
			   $ls_metforpago=$_POST["txtmetforpag".$li_i]; //crear campo oculto "txtmetforpago" en "GRID fpago".
			   $ls_numinst=$_POST["txtnuminst".$li_i];
			   if ($ls_metforpago=="D" and $ls_codforpag=='03')
			   {
					$ls_suichemet=true;
					break;
			   }
			 }
	
	
			$ld_totalforpag=str_replace('.','',$ls_totalforpag);  //convierte a formato num�rico
			$ld_totalforpag=str_replace(',','.',$ld_totalforpag);
			$ld_total1=str_replace('.','',$ls_moncob);  //convierte a formato num�rico
			$ld_total1=str_replace(',','.',$ld_total1);
			$ld_totalmonret=str_replace('.','',$ls_totalmonret);  //convierte a formato num�rico
			$ld_totalmonret=str_replace(',','.',$ld_totalmonret);
			//$ld_monto=-($ld_total1-$ld_totalforpag);
			$ld_monto = $_POST["txtmoncob1"];
			$ld_monto=str_replace('.','',$ld_monto);  //convierte a formato num�rico
			$ld_monto=str_replace(',','.',$ld_monto);
			$ld_monto=-($ld_monto);
	
			//print $ld_monto." Monto <br>";
			$lb_validonota = true;
	
			if ($ld_monto>0) // if sobra dinero se genera nota de cr�dito autom�tica
			{
				//$ld_monto=number_format($ld_monto,2,',','.');  //convierte a formato num�rico
	
				//$ls_codcaj=$_SESSION["ls_codcaj"];
				//$ls_serie='01';
	
				//$ls_codcaj=$la_codcaj;
				$ls_serie=$_SESSION["ls_sernot"];
	
				$ls_prefijo="NC";
				$io_secuencia->uf_obtener_secuencia($ls_codcaj.$ls_codtie."not",&$ls_secuencia);
				$ls_numnot=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);
				$ls_dennot="Nota de credito generada automaticamente al facturar.";
				$ls_tipnot="CXP"; // C:nota cr�dito y D:nota d�bito
				$ls_fecnot=$ls_fecemi;
				$ls_estnot="P"; //P: pendiente y C: cancelado
				//****  generar nueva nota de credito automatica  *****
	
				$ld_montonota = $_POST["txtmoncob1"];
				$ld_montonota = str_replace('-','',$ld_montonota);
				//print $ld_montonota.'<br>';
				$lb_validonota=$io_nota->uf_guardar_nota($ls_codcli1,$ls_numnot,$ls_dennot,$ls_tipnot,$ls_fecnot,$ld_montonota,$ls_estnot,$ls_numcob,$ls_codtie,$la_seguridad);
	
				$ls_mensaje=$io_nota->io_msgc;
				$io_msg->message ("Se ha generado una NOTA a favor del cliente");
			}
			if  ($ls_suichemet==true) //si pago con nota de cr�dito actualizarla a "C" cancelada
			{
			//****  actualizar "estnot" en nota de cr�dito a "C"(cancelada)  *****
			//print "METFORPAGO2:".$ls_metforpago;
			$ls_estnot="C";
			$ls_numnot=$ls_numinst;//n�mero de nota de credito usada para pagar.
			$lb_valido=$io_nota->uf_update_actualizaestnot($ls_numnot,$ls_estnot,$la_seguridad);
			}
		//}
		
		if ( ($lb_valido1==true) and ($lb_validoret==true or $li_filasretenciones==1)
				and ( ($lb_valido==true) or ($lb_valido==0) ) and ( ($lb_validonota==true) or ($lb_validonota==0) ) )
		{
				$io_cobranza->io_sql->commit();
				$ls_operacion1="ue_guardar";
				$ls_operacion="VER";	
		}
		else
		{
			$io_cobranza->io_sql->rollback();
		}
}
elseif ($ls_operacion=="ue_eliminar")
{
	$ls_fecauxiliar=date('d/m/Y');

 if (/*$ls_feccob==$ls_fecauxiliar and */ $ls_codcierre =='')
 {
	/*****************    ACTUALIZAR SALDOS PENDIENTES  ***************************************************/
	$ls_suichemet=false;
	for ($li_i=1;$li_i<$li_filasfpago;$li_i++)
	 {
	   $ls_codforpag=$_POST["txtcodforpag".$li_i];
	   $ls_metforpago=$_POST["txtmetforpag".$li_i]; //crear campo oculto "txtmetforpago" en "GRID fpago".
	   $ls_numinst=$_POST["txtnuminst".$li_i];
	   // print "/METFORPAGO:".$ls_metforpago;
	   if ($ls_metforpago=="D" and $ls_codforpag=='03')
	   {
			$ls_suichemet=true;
			break;
	   }
	 }

	if  ($ls_suichemet==true) //si pago con nota de cr�dito actualizarla a "C" cancelada
	{
		//****  actualizar "estnot" en nota de cr�dito a "C"(cancelada)  *****
		//print "METFORPAGO2:".$ls_metforpago;
		$ls_estnot="P";
		$ls_numnot=$ls_numinst;//n�mero de nota de credito usada para pagar.
		$lb_valido=$io_nota->uf_update_actualizaestnot($ls_numnot,$ls_estnot,$la_seguridad);
	}

		/*****************    ACTUALIZAR ESTATUS FACTURA   ***************************************************/
	$ls_suichemet=false;
	for ($li_i=1;$li_i<$li_filasconcepto;$li_i++)
	 {
	   $ls_tipcancel=$_POST["combo_tipocancel".$li_i];
	   $ls_numfac=$_POST["txtnumfac".$li_i];
	   $ls_numfacasoc=$_POST["txtnumfacasoc".$li_i];
	   // print "/METFORPAGO:".$ls_metforpago;
	   if ($ls_tipcancel=='T')
	   {
			$ls_suichemet=true;
			break;
	   }
	 }

	if  ($ls_suichemet==true) //si pago con nota de cr�dito actualizarla a "C" cancelada
	{
		//****  actualizar "estnot" en nota de cr�dito a "C"(cancelada)  *****
		//print "METFORPAGO2:".$ls_metforpago;
		$ls_estfac="P";
		$ls_numfac=$ls_numfac;//n�mero de nota de credito usada para pagar.
		if ($ls_numfac==$ls_numfacasoc)
		{
		$lb_valido=$io_cobranza->uf_actualizar_factura($ls_numfac,$ls_estfac,$la_seguridad);
		$lb_valido=$io_cobranza->uf_actualizar_nota($ls_numfac,$ls_estfac,$la_seguridad);
		}
		else
		{
		$lb_valido=$io_cobranza->uf_actualizar_factura($ls_numfacasoc,$ls_estfac,$la_seguridad);
		$lb_valido=$io_cobranza->uf_actualizar_nota($ls_numfac,$ls_estfac,$la_seguridad);
		}
	}

	$io_instpagocob->io_sql->begin_transaction();
	$lb_valido=$io_instpagocob->uf_delete_detinstpagocob($ls_numcob);
	if($lb_valido==true or $lb_valido==1 ){

		$io_cobranza->io_sql->begin_transaction();
		$lb_validodelnota = $io_cobranza->uf_delete_nota_cobro($ls_numcob,$ls_codtie,$la_seguridad);

		$lb_valido=$io_cobranza->uf_delete_detcobro($ls_numcob,$la_seguridad);
		if( ($lb_valido==true or $lb_valido==1) and  $lb_validodelnota){
			$lb_valido=$io_cobranza->uf_delete_cobro($ls_numcob,$ls_codtie,$la_seguridad);
			if($lb_valido==true or $lb_valido==1 ){
				$io_instpagocob->io_sql->commit();
				$io_cobranza->io_sql->commit();
			}else{
				$io_cobranza->io_sql->rollback();
			}
		}else{
			$io_cobranza->io_sql->rollback();
		}

	}else{
		$io_instpagocob->io_sql->rollback();
	}


    if ($lb_valido==true)
	{
		$li_filasconcepto=1;
		$la_objectconcepto[$li_filasconcepto][1]="<input name=txtnumfac".$li_filasconcepto." type=text id=txtnumfac".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly><input name=txtnumfacasoc".$li_filasconcepto." type=hidden id=txtnumfacasoc".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][2]="<input name=txtfecemi".$li_filasconcepto." type=text id=txtfecemi".$li_filasconcepto." class=sin-borde size=10 style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][3]="<input name=txtmonto".$li_filasconcepto." type=text id=txtmonto".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][4]="<input name=txttipocancel".$li_filasconcepto." type=text id=txttipocancel".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][5]="<input name=txtmontocancel".$li_filasconcepto." type=text id=txtmontocancel".$li_filasconcepto." class=sin-borde size=15 style= text-align:center >";

		$li_filasfpago=1;
		$la_objectfpago[$li_filasfpago][1]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=25 style= text-align:center readonly>";
		$la_objectfpago[$li_filasfpago][2]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtctaban".$li_filasfpago." type=hidden id=txtctaban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectfpago[$li_filasfpago][3]="<input name=txtfacret".$li_filasfpago." type=text id=txtfacret".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectfpago[$li_filasfpago][4]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectfpago[$li_filasfpago][5]="<input name=txtobsins".$li_filasfpago." type=text id=txtobsins".$li_filasfpago." class=sin-borde size=45 style= text-align:left readonly><input name=txtcodforpag".$li_filasfpago." type=hidden id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectfpago[$li_filasfpago][6]="<input name=txtfecins".$li_filasfpago." type=text id=txtfecins".$li_filasfpago." class=sin-borde size=10 style= text-align:center readonly>";
		$la_objectfpago[$li_filasfpago][7]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectfpago[$li_filasfpago][8]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
		$ls_estcob="";

		$li_filasretenciones=1;
		$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
		$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=35 readonly>";
		$la_objectretenciones[$li_filasretenciones][3]="<input name=txtmontoret".$li_filasretenciones." type=text id=txtmontoret".$li_filasretenciones." class=sin-borde style= text-align:right size=15><input name=formula".$li_filasretenciones." type=hidden id=formula".$li_filasretenciones." readonly>";
		$la_objectretenciones[$li_filasretenciones][4]="<input name=txttotalret".$li_filasretenciones." type=text id=txttotalret".$li_filasretenciones." class=sin-borde style= text-align:right size=15 readonly onChange=javascript:ue_subtotalret(); >";
		$la_objectretenciones[$li_filasretenciones][5]="<input name=txtfacasoc".$li_filasretenciones." type=text id=txtfacasoc".$li_filasretenciones." class=sin-borde style= text-align:right size=26  readonly>";
		$la_objectretenciones[$li_filasretenciones][6]="<input name=chkpagar".$li_filasretenciones." type=checkbox id=chkpagar".$li_filasretenciones." class=sin-borde style= text-align:right >";
		$la_objectretenciones[$li_filasretenciones][7]="<input name=txtcomprobante".$li_filasretenciones." type=text id=txtcomprobante".$li_filasretenciones." class=sin-borde style= text-align:right size=26  readonly>";
	}

   //$lb_valido=$io_cobranza->uf_delete_cobro($ls_numcob,$ls_codtie,$la_seguridad);
   	if ($lb_valido==true)
	{
		$io_msg->message($io_cobranza->io_msgc);
		$ls_operacion="";
		$ls_numcob="";
		$ls_feccob="";
		$ls_codcli="";
		$ls_codcli1="";
		$ls_nomcli="";
		$ls_moncob="0,00";
		$ls_restaporpagar='0,00';
		$ls_totalforpag="0,00";
		$ls_subtot="0,00";
		$ls_monto="0,00";
		$ls_montocancel="";

		$ls_observacion="";
		$ls_monivat="0,00";
		$ls_conpag=1;
		$ls_totalforpag="";
		$ls_observacion="";
	    $ls_moncob="0,00";
		$ls_restaporpagar='0,00';
		$ls_tipocancel="S";
		$ls_codforpag="";
		$ls_codban="";
		$ls_feccob=date('d/m/Y');
		$li_filasconcepto=1;
		$ls_estcob="";
		$ls_modificar="True";
		$ls_especial="F";
		/*$la_codcaj="";
		$la_nomcaj="";*/

		print("<script language=JavaScript>");
		print("pagina='sigesp_sfc_d_cobranza.php';");
	    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
	    print("</script>");

	 }

}else{
?><script>
alert ("El Cobro no puede ser eliminado, ya fue procesado ó en cierre de caja!!!");
</script>
<?php
	}
}
if($ls_operacion=="VER")
{
        $ls_operacion="";
  	    /*$ls_sql="SELECT ".
		"caj.nomusu,cli.codcli,cli.cedcli,cli.razcli,cli.dircli,cli.telcli,cli.celcli,cob.numcob,".
		"cob.codusu,cob.feccob,cob.descob,cob.moncob,cob.estcob,dcob.numfac,dcob.tipcancel,dcob.moncancel,dcob.montoxcancel,".
		"tie.dentie,tie.riftie,tie.dirtie,tie.teltie,fac.fecemi FROM sfc_cliente cli,sfc_cobro cob,sfc_cobrofactura dcob,".
		"sfc_tienda tie,sfc_cajero caj,sfc_factura fac ".
		 "WHERE cob.numcob=dcob.numcob AND ".
		"cob.codcli=cli.codcli AND cob.codusu=caj.codusu AND caj.codtie=tie.codtie AND ".
		"fac.numfac=dcob.numfac AND cob.numcob='".$ls_numcob."'  ORDER BY cob.codcli ASC;";*/

		$ls_sql="SELECT ".
		"caj.nomusu,cli.codcli,cli.cedcli,cli.razcli,cli.dircli,cli.telcli,cli.celcli,cob.numcob,".
		"cob.codusu,cob.feccob,cob.descob,cob.moncob,cob.estcob,dcob.numfac,dcob.tipcancel,dcob.moncancel,dcob.montoxcancel,".
		"tie.dentie,tie.riftie,tie.dirtie,tie.teltie,fac.fecemi " .
		"FROM sfc_cliente cli,sfc_cobro_cliente cob,sfc_dt_cobrocliente dcob,sfc_tienda tie,sfc_cajero caj,sfc_factura fac ".
		"WHERE cob.numcob=dcob.numcob AND ".
		"cob.codcli=cli.codcli AND tie.codtiend = fac.codtiend AND cob.codusu=caj.codusu AND caj.codtiend=tie.codtiend AND ".
		"fac.numfac=dcob.numfac AND cob.numcob='".$ls_numcob."'  ORDER BY cob.codcli ASC;";

		//print $ls_sql;
		$rs_datauni=$io_sql->select($ls_sql);
		if($rs_datauni==false)
		{
			$lb_valido_repcot=false;
			$io_msg="Error en uf_select_ReporteCotizacion ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_repcot=true; //Registro encontrado

			}
			else
			{
				$lb_valido_repcot=false; //"Registro no encontrado"
				 $io_msg->message ("La cobranza no esta registrada, registrela e intente de nuevo!!!");
			}
		}
	if ($lb_valido_repcot==true)
	 {
	  $sub_total="prueba";
	  ?>
	  <script language="javascript">
	    var ls_sql="<?php print $ls_sql; ?>";
	   	pagina="reportes/sigesp_sfc_rep_cobranza.php?sql="+ls_sql;
	  	popupWin(pagina,"catalogo",580,700);
	</script>
<?php

		print("<script language=JavaScript>");
		print("pagina='sigesp_sfc_d_cobranza.php';");
	    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
	    print("</script>");
	}
}
if ($ls_operacion=="ue_anular")
{
	$ls_fecauxiliar=date('d/m/Y');
        $ls_obsAnul = $_POST[txtobsanul];
        //$io_msg->message (" Mensaje: ".$ls_codcierre);
	if(/*$ls_feccob>=$ls_fecauxiliar and */$ls_codcierre ==''){

		$ls_sql="SELECT * FROM sfc_cobro_cliente WHERE codemp='".$ls_codemp."' AND numcob='".$ls_numcob."';";
		$rs_datauni=$io_sql->select($ls_sql);
	 	$la_cobro=$io_sql->obtener_datos($rs_datauni);
		$io_datastore->data=$la_cobro;
		$ls_estcob=$io_datastore->getValue("estcob",1);//

	 	if ($ls_estcob=="A")
			{
			  $io_msg->message ("El Cobro ya esta ANULADO!");
                          
			}
		 elseif ($ls_estcob=="P")
			{
			  $io_msg->message ("El Cobro PROCESADO será ANULADO!");
			  $ls_estcob="";
			 }
		 elseif ($ls_estfaccon=="E")
			{
			  $io_msg->message ("El Cobro EMITIDO será ANULADO!");
			  $ls_estcob="";
			}
		 else
			{
			  $ls_estcob="";
			}
		 if ($ls_estcob=="")
		   {

		    $ls_estcob="A";
		    $io_cobranza->io_sql->begin_transaction();
			 $lb_validocob = $io_cobranza->uf_actualizar_cobrostatus($ls_numcob,'A',$ls_codtie,$la_seguridad,$ls_obsAnul);
			 /*$ls_cadena="SELECT n.numnot,n.nro_factura,cf.numfac,cf.montoxcancel FROM sfc_nota n,sfc_cobrofactura cf,sfc_cobro c WHERE
			 n.numnot=cf.numfac AND n.codcli=cf.codcli AND cf.numcob=c.numcob AND n.codcli=c.codcli AND cf.numcob='".$ls_numcob."' AND c.estcob='A'";*/
			 $ls_cadena="SELECT n.numnot,n.nro_documento,cl.numfac,cl.montoxcancel " .
			 		"FROM sfc_nota n,sfc_dt_cobrocliente cl,sfc_cobro_cliente c " .
			 		"WHERE n.numnot=cl.numfac AND n.codcli=cl.codcli AND cl.numcob=c.numcob AND n.codcli=c.codcli " .
			 		"AND cl.numcob='".$ls_numcob."' AND c.estcob='A'";

			 //print $ls_cadena;
			 $rs_datauni1=$io_sql->select($ls_cadena);
			 $la_factura=$io_sql->obtener_datos($rs_datauni1);
	 		 $io_datastore->data=$la_factura;
			 $totrow=$io_datastore->getRowCount("numnot");
			 //print $totrow;
                         
                         ?>
                                     <script language="JavaScript">
                                                    var numcob="<?php print $ls_numcob; ?>";
                                                    //alert(ls_formalibre);
                                                    pagina="reportes/sigesp_sfc_rep_cobro_anulado.php?numcob="+numcob;
                                                    popupWin(pagina,"catalogo",580,700);
                                     </script>
                                <?php
                         
			 $li_i2=0;
			 for($li_i=0;$li_i<$totrow;$li_i++)
			 {
			 	//print "paso";
				 $li_i2=$li_i2+1;
				 $ls_factura=$io_datastore->getValue("numnot",$li_i+1);
				 $ls_numfacasoc=$io_datastore->getValue("nro_documento",$li_i+1);
				 $ls_montoanul=$io_datastore->getValue("montoxcancel",$li_i+1);
				 if ($ls_factura==$ls_numfacasoc)
				 {
					 $lb_validofac = $io_cobranza->uf_actualizar_factura($ls_factura,'P',$la_seguridad);
					 $lb_validonota = $io_cobranza->uf_actualizar_nota($ls_factura,'P',$la_seguridad);
				 }
				 else
				 {
					 $lb_validofac = $io_cobranza->uf_actualizar_factura($ls_numfacasoc,'P',$la_seguridad);
					 $lb_validonota = $io_cobranza->uf_actualizar_nota($ls_factura,'P',$la_seguridad);
				 }

				 if($lb_validofac != true or $lb_validonota != true){
				 	$io_cobranza->io_sql->rollback();
				 	$io_msg->message ("El Cobro no pudo ser ANULADO !");
				 	break;
				 }

				 $ls_montototal[$li_i2]["montoxcancel"]=$ls_montoanul;
				 /*$ls_cadena="SELECT n.numnot,n.nro_factura,n.monto,co.numcob,co.estcob,co.moncob,co.estcob,cf.numfac,cf.moncancel,cf.tipcancel
					FROM sfc_cliente c,sfc_nota n, sfc_cobro co, sfc_cobrofactura cf WHERE n.numnot=cf.numfac AND
					cf.numcob=co.numcob	AND cf.codcli=co.codcli AND c.codcli=n.codcli
					AND co.estcob<>'A' AND cf.numfac='".$ls_factura."'";*/

				$ls_cadena="SELECT n.numnot,n.nro_documento,n.monto,co.numcob,co.estcob,co.moncob,co.estcob,cl.numfac,cl.moncancel,cl.tipcancel
					FROM sfc_cliente c,sfc_nota n, sfc_cobro_cliente co, sfc_dt_cobrocliente cl WHERE n.numnot=cl.numfac AND
					cl.numcob=co.numcob	AND cl.codcli=co.codcli AND c.codcli=n.codcli
					AND co.estcob<>'A' AND cl.numfac='".$ls_factura."'";

					$arr_montos=$io_sql->select($ls_cadena);
					if ($arr_montos==false && ($io_sql->message!=""))
					{
						$io_cobranza->io_sql->rollback();
						$io_msg->message("!No hay Registros de Cobros Anteriores");
					}
					else
					{
						if ($row=$io_sql->fetch_row($arr_montos))
						{
							$la_montos=$io_sql->obtener_datos($arr_montos);
							$io_data->data=$la_montos;
							$ls_monto=$io_data->getValue("monto",1);
							$totrow2=$io_data->getRowCount("numcob");
							$ls_monto2=0;
							$ls_monto3=0;
							$li_i3=0;
							for ($li_i2=1;$li_i2<=$totrow2;$li_i2++)
							{
								//print 'paso';
								$li_i3=$li_i3+1;
								$ls_numfactura=$io_data->getValue("numnot",$li_i2);
								$ls_montocancel=$io_data->getValue("moncancel",$li_i2);
								$ls_estatcob=$io_data->getValue("estcob",$li_i2);
								$ls_tipo=$io_data->getValue("tipcancel",$li_i2);
								$a_tipo[$li_i3]["tipcancel"]=$ls_tipo;
								$ls_montoresta=$ls_monto-$ls_montocancel;
								if ($ls_tipo=='T')
								{
									$lb_validofac = $io_cobranza->uf_actualizar_dfacturatatus($ls_numfactura,'P',$la_seguridad);
								}

								if($lb_validofac != true){
									$io_cobranza->io_sql->rollback();
								 	$io_msg->message ("El Cobro no pudo ser ANULADO !");
								 	break;
								}
							}

							if($lb_validofac != true){
							 	break;
							}

							if ($ls_montoresta==0){
							$ls_montoresta=$io_data->getValue("monto",$li_i);
							}
							$ls_monto=number_format($ls_montoresta,2,',', '.');
							$ls_tipo=$a_tipo[$li_i3-1]["tipcancel"];

							if ($ls_monto!=0 and $ls_tipo=='T')
							{
								$lb_validofac = $io_cobranza->uf_actualizar_dfacturatatus($ls_numfactura,'P',$la_seguridad);
							}

							if($lb_validofac != true){
								$io_cobranza->io_sql->rollback();
							 	$io_msg->message ("El Cobro no pudo ser ANULADO !");
							 	break;
							}
						}else{
							if ($ls_tipocancel=='T' and $ls_montocancel==0 and $ls_estcob=='A')
							{
							$ls_monto='0,00';
							}else{
							$ls_monto=$ls_montopar;
							}
						}
					}
			  }//FOR

			 }

			  /*****************    ACTUALIZAR SALDOS PENDIENTES   ***************************************************/
	        $ls_suichemet=false;
			for ($li_i=1;$li_i<$li_filasfpago;$li_i++)
			 {
			   $ls_codforpag=$_POST["txtcodforpag".$li_i];
			   $ls_metforpago=$_POST["txtmetforpag".$li_i]; //crear campo oculto "txtmetforpago" en "GRID fpago".
			   $ls_numinst=$_POST["txtnuminst".$li_i];
			   // print "/METFORPAGO:".$ls_metforpago;
			   if ($ls_metforpago=="D" and $ls_codforpag=='03')
			   {
			   		$ls_suichemet=true;
			   		break;
			   }
			 }

			if  ($ls_suichemet==true) //si pago con nota de cr�dito actualizarla a "C" cancelada
			{
				//****  actualizar "estnot" en nota de cr�dito a "C"(cancelada)  *****
				//print "METFORPAGO2:".$ls_metforpago;
				$ls_estnot="P";
				$ls_numnot=$ls_numinst;//número de nota de credito usada para pagar.
				$io_nota->io_sql->begin_transaction();
				$lb_valido=$io_nota->uf_update_actualizaestnot($ls_numnot,$ls_estnot,$la_seguridad);
			}else{
				$lb_valido=true;
			}

			$lb_validodelnota = $io_cobranza->uf_delete_nota_cobro($ls_numcob,$ls_codtie,$la_seguridad);

			if( ($lb_validocob or $lb_validocob==1) and ($lb_validofac or $lb_validofac==1)
					and ($lb_validonota or $lb_validonota==1) and ($lb_valido or $lb_valido==1)
					and ($lb_validodelnota or $lb_validodelnota==1)){

				$io_cobranza->io_sql->commit();
				if  ($ls_suichemet==true){
					$io_nota->io_sql->commit();
				}
				$io_msg->message ("El Cobro fue ANULADO!");

				print("<script language=JavaScript>");
				print("pagina='sigesp_sfc_d_cobranza.php';");
			    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
			    print("</script>");

			}else{
				$io_cobranza->io_sql->rollback();
				if  ($ls_suichemet==true){
					$io_nota->io_sql->rollback();
				}
				$io_msg->message ("El Cobro no pudo ser ANULADO !");
			}

	}else{?>

		<script>
		alert ("El Cobro no puede ser anulado, ya fue procesado ó en cierre de caja!!!");
		</script>
		<?php
	}


}//fin procediiento "ue_anular"
  /*******************************************************************************************************/
/****************************** CARGAR RETENCIONES    **************************************************/
/*******************************************************************************************************/
if($ls_operacion=="ue_cargarretenciones")
{
	//print "UE_CARGARRETENCIONES";
    $ld_acum=0;
    $ls_subtotalret=0;
	$li_filasretenciones=1;

	$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=35 readonly>";
	$la_objectretenciones[$li_filasretenciones][3]="<input name=txtmontoret".$li_filasretenciones." type=text id=txtmontoret".$li_filasretenciones." class=sin-borde style= text-align:right size=15 onKeyPress=return(currencyFormat(this,'.',',',event))><input name=formula".$li_filasretenciones." type=hidden id=formula".$li_filasretenciones.">";
	$la_objectretenciones[$li_filasretenciones][4]="<input name=txttotalret".$li_filasretenciones." type=text id=txttotalret".$li_filasretenciones." class=sin-borde style= text-align:right size=15 readonly onChange=javascript:ue_subtotalret(); >";

	$ls_cadena="SELECT cd.*,d.dended,d.formula,d.iva,d.islr,d.estretmun,d.otras FROM sfc_cliente c,sfc_clientededuccion cd,sigesp_deducciones d " .
			"WHERE d.codemp=cd.codemp AND cd.codcli=c.codcli AND d.codded=cd.codded AND cd.codcli='".$ls_codcli1."'";
	//print $ls_cadena;

	$arr_retenciones=$io_sql->select($ls_cadena);
	$ld_subtotalret=0;
	$ld_result=0;

	//if($arr_retenciones==false&&($io_sql->message!=""))
	if($arr_retenciones==false&&($io_sql->message!=""))
	{
		$io_msg->message("No hay registros de retenciones");
	}
	else
	{
		if($row=$io_sql->fetch_row($arr_retenciones))
		  {

			$la_retenciones=$io_sql->obtener_datos($arr_retenciones);
			$io_datastore->data=$la_retenciones;

			$totrow=$io_datastore->getRowCount("codcli");
			$ls_montoret="0,00";
			$ls_totalret="0,00";

			for($li_i=1;$li_i<=$totrow;$li_i++)
			{
				$ls_codigo=$io_datastore->getValue("codded",$li_i);
                                $ls_descripcion=$io_datastore->getValue("dended",$li_i);
				$ls_iva=$io_datastore->getValue("iva",$li_i);
				$ls_islr=$io_datastore->getValue("islr",$li_i);
				$ls_otras=$io_datastore->getValue("otras",$li_i);
				$ls_estretmun=$io_datastore->getValue("estretmun",$li_i);
				$ls_formula=$io_datastore->getValue("formula",$li_i);

                $la_objectretenciones[$li_i][1]="<input name=txtcodret".$li_i." type=text id=txtcodret".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
                $la_objectretenciones[$li_i][2]="<input name=txtdesret".$li_i." type=text id=txtdesret".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=35 readonly>";
           //------------------------------------------------------------------------------------------------//
				if($ls_iva=="1")
                   {
				    // print $ls_monivat;
					 $ld_montoiva=$ls_monivat;
					
					 $ld_montoiva=$io_funcsob->uf_convertir_cadenanumero($ld_montoiva);

                     $ld_result=$io_evalform->uf_evaluar($ls_formula,$ld_montoiva,$lb_valido);
                     $ls_totalret=$io_funcsob->uf_convertir_numerocadena($ld_result);

                     $la_objectretenciones[$li_i][3]="<input name=txtmontoret".$li_i." type=text id=txtmontoret".$li_i." class=sin-borde value='".number_format($ld_montoiva,'2',',','.')."' onKeyPress=return(currencyFormat(this,'.',',',event)) style= text-align:right size=15 readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";

                     $la_objectretenciones[$li_i][4]="<input name=txttotalret".$li_i." type=text id=txttotalret".$li_i." class=sin-borde onKeyPress=return(currencyFormat(this,'.',',',event)) value='".$ls_totalret."' style= text-align:right size=15 onChange=javascript:ue_subtotalret();>";
                   }
                   else
                   {
				     $ls_totalret='0,00';
					 $ld_result=0;
                     $la_objectretenciones[$li_i][3]="<input name=txtmontoret".$li_i." type=text id=txtmontoret".$li_i." class=sin-borde value='".$ls_montoret."' onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=ue_caliva(); style= text-align:right size=15 readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";

                      $la_objectretenciones[$li_i][4]="<input name=txttotalret".$li_i." type=text id=txttotalret".$li_i." class=sin-borde onKeyPress=return(currencyFormat(this,'.',',',event)) value='".$ls_totalret."' style= text-align:right size=15 onChange=javascript:ue_subtotalret();>";
                   }
			//------------------------------------------------------------------------------------------------//
				  $ld_subtotalret=$ld_subtotalret+$ld_result;
				  $ls_subtotalret=number_format($ld_subtotalret,'2',',','.');
			}

		  $li_filasretenciones=$li_i;

	      $la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
	      $la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=35 readonly>";
	      $la_objectretenciones[$li_filasretenciones][3]="<input name=txtmontoret".$li_filasretenciones." type=text id=txtmontoret".$li_filasretenciones." class=sin-borde style= text-align:right size=15 onKeyPress=return(currencyFormat(this,'.',',',event))><input name=formula".$li_filasretenciones." type=hidden id=formula".$li_filasretenciones.">";
	      $la_objectretenciones[$li_filasretenciones][4]="<input name=txttotalret".$li_filasretenciones." type=text id=txttotalret".$li_filasretenciones." class=sin-borde style= text-align:right size=15 readonly onChange=javascript:ue_subtotalret(); >";
	     }
	}

	$ld_mont1=$io_funcsob->uf_convertir_cadenanumero($ls_moncob);//MONTO DE RETENCION, SE LE APLICARA LA FORMULA
	$ls_mont=$ld_mont1-$ld_subtotalret;
	$ls_totalmonret=number_format($ls_mont,2,',','.');
}
/*******************************************************************************************************/
/******************************   CALCULAR RETENCIONES   ***********************************************/
/*******************************************************************************************************/
if($ls_operacion=="ue_calcretencion")
{
	$ld_acum=0;
	for($li_i=1;$li_i<$li_filasretenciones;$li_i++)
	{
		$ls_codigo=$_POST["txtcodret".$li_i];
		$ls_descripcion=$_POST["txtdesret".$li_i];

		$ls_montoret=$_POST["txtmontoret".$li_i];
		$ls_totalret=$_POST["txttotalret".$li_i];
		$ls_formula=$_POST["formula".$li_i];

		$ls_facasoc=$_POST["txtfacasoc".$li_i];
		$ls_comprobante=$_POST["txtcomprobante".$li_i];
		$ls_chkpagar=$_POST["chkpagar".$li_i];

		$ld_monret=$io_funcsob->uf_convertir_cadenanumero($ls_montoret);//MONTO DE RETENCION, SE LE APLICARA LA FORMULA
		$ld_result=$io_evalform->uf_evaluar($ls_formula,$ld_monret,$lb_valido);// CALCULA RETENCION
		$ls_totalret=number_format($ld_result,'2',',','.');

		if($ls_chkpagar == "on"){
			$chequeado = "checked";
			$ld_acum=$ld_acum+$ld_result; //ACUMULA LOS TOTALES DE LOS MONTOS DE RETENCION
		}else{
			$chequeado = "";
		}

		//$ld_acum=$ld_acum+$ld_result; //ACUMULA LOS TOTALES DE LOS MONTOS DE RETENCION


		/**********************************************************************************************************************/
		$la_objectretenciones[$li_i][1]="<input name=txtcodret".$li_i." type=text id=txtcodret".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
		$la_objectretenciones[$li_i][2]="<input name=txtdesret".$li_i." type=text id=txtdesret".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=30 readonly>";
		$la_objectretenciones[$li_i][3]="<input name=txtmontoret".$li_i." type=text id=txtmontoret".$li_i." class=sin-borde value='".$ls_montoret."' style= text-align:right size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";
		$la_objectretenciones[$li_i][4]="<input name=txttotalret".$li_i." type=text id=txttotalret".$li_i." class=sin-borde value='".$ls_totalret."' style= text-align:right  size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) onChange=javascript:ue_subtotalret(); >";
		$la_objectretenciones[$li_i][5]="<input name=txtfacasoc".$li_i." type=text id=txtfacasoc".$li_i." class=sin-borde value='".$ls_facasoc."' style= text-align:center size=26 >";
		$la_objectretenciones[$li_i][6]="<input name=chkpagar".$li_i." type=checkbox id=chkpagar".$li_i." class=sin-borde ".$chequeado." onChange=javascript:ue_subtotalret(); >";
		$la_objectretenciones[$li_i][7]="<input name=txtcomprobante".$li_i." type=text id=txtcomprobante".$li_i." class=sin-borde value='".$ls_comprobante."' style= text-align:center size=26 maxlength=25 >";
	}

	$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=35 readonly>";
	$la_objectretenciones[$li_filasretenciones][3]="<input name=txtmontoret".$li_filasretenciones." type=text id=txtmontoret".$li_filasretenciones." class=sin-borde style= text-align:right size=15 onKeyPress=return(currencyFormat(this,'.',',',event))><input name=formula".$li_filasretenciones." type=hidden id=formula".$li_filasretenciones.">";
	$la_objectretenciones[$li_filasretenciones][4]="<input name=txttotalret".$li_filasretenciones." type=text id=txttotalret".$li_filasretenciones." class=sin-borde style= text-align:right size=15 readonly onChange=javascript:ue_subtotalret(); >";

	/***********************************************************************************************************************/

	$ld_mont1=$io_funcsob->uf_convertir_cadenanumero($ls_moncob);//MONTO DE RETENCION, SE LE APLICARA LA FORMULA
	$ls_mont=$ld_mont1-$ld_acum;

	//$ls_totalmonret=number_format($ld_acum,2,',','.');
	//$ls_subtotalret=number_format($ls_total,2,',','.');
	$ls_totalmonret=number_format($ls_mont,2,',','.');
	$ls_subtotalret=number_format($ld_acum,2,',','.');

}
?>

<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="519" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1 Estilo3">Sistema de Facturaci&oacute;n</span></td>
    <td width="259" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
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
 < <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><div align="left"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar(<?php $li_i?>);"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><a href="javascript:ue_ver();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a><!--<a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a>--><a href="javascript:ue_anular();"><img src="../shared/imagenes/editdelete.png" width="22" height="22" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
  </tr>
</table>
  <p>&nbsp;
  </p>
  <form name="form1" method="post" action="">
<?php
   /*********************************         SEGURIDAD               *************************************/
 if ($ls_permisos)
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
    <input name="hidestobr" type="hidden" id="hidestobr" value="<?php print $ls_estobr ?>">
    <input name="hidmonobr" type="hidden" id="hidstaobr" value="<?php print $ls_monobr ?>">
    <input name="hidmonasi" type="hidden" id="hidmonasi" value="<?php print $ls_monasi ?>">
    
    <input name="txtmontocancel" type="hidden" id="txtmontocancel">
    <input name="txttipocancel" type="hidden" id="txttipocancel">
    <input name="txtmontoforpag" type="hidden" id="txtmontoforpag">
	 <input name="filasretenciones" type="hidden" id="filasretenciones" value="<?php print $li_filasretenciones;?>">
     <input name="removerretenciones" type="hidden" id="removerretenciones" value="">
    <table width="771" height="399" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
    <tr class="titulo-celdanew">
      <th colspan="9" scope="col" class="titulo-celdanew">Cobranza</th>
    </tr>
    <tr class="formato-blanco">
      <td  colspan="9">
      	<input name="operacion" type="hidden" id="operacion">
      	<input name="opemostrar" type="hidden" id="opemostrar" value="<?php print $ls_opemostrar ?>">
      	<input name="txtciecaja" type="hidden" id="txtciecaja" value="<?php print $ls_codcierre ?>">
     </td>
    </tr>
    <tr class="formato-blanco">
      <td height="20"><input name="hidremoverconcepto2" type="hidden" id="hidremoverconcepto2"></td>
      <td><input name="txtfilasfpago" type="hidden" id="txtfilasfpago" value="<?php print $li_filasfpago ?>">
          <input name="hidremoverfpago" type="hidden" id="hidremoverfpago" value="<?php print $li_removerfpago ?>">
          <input name="hidcodalm" type="hidden" id="hidcodalm" value="<?php print $ls_codalm ?>">
		  <input name="estcob" type="hidden" id="estcob" value="<?php print $ls_estcob?>">
		  <input name="modificar" type="hidden" id="modificar" value="<?php print $ls_modificar?>">
          <input name="txtmoncar" type="hidden" id="txtmoncar">
          <input name="txtsubtotalret" type="hidden" id="txtsubtotalret"  style="text-align: right" value="<?php  print $ls_subtotalret ?>" readonly="true"></td>
      <td width="147"><input name="hiddencodban" type="hidden" id="hiddencodban" value="<?php print $ls_codban ?>">
      <input name="hiddencodforpag" type="hidden" id="hiddencodforpag" value="<?php print $ls_codforpag ?>">
      <input name="operacion1" type="hidden" id="operacion1" value="<?php print $ls_operacion1 ?>">	  </td>
      <td colspan="3">&nbsp;</td>
      <td width="75">&nbsp;</td>
      <td width="128">&nbsp;</td>
      <td width="6">&nbsp;</td>
    </tr>

    <tr class="formato-blanco">

      <td width="22" height="22"><div align="right"></div></td>
	  <?php
	 if ($ls_operacion1=="ue_guardar" or $ls_estcob=="E" or $ls_estcob=="A" or $ls_estcob=="P" or $ls_estcob=="C")
	 {
	 ?>
      <td width="68"><div align="right">N&ordm; Control </div></td>

	  <td><div align="left">
          <input name="txtnumcob" type="text" id="txtnumcob" value="<?php print $ls_numcob ?>" size="28" maxlength="25" readonly>
          <input name="txtobsanul" type="hidden" id="txtobsanul" value="">
         </div></td>
      <td ><div align="left"><span class="titulo-cat&aacute;logo">
        <?php
		if ($ls_estcob=="E")
		{
		?>
        <font color="#006600">EMITIDO</font>
        <?php
		}
		elseif ($ls_estcob=="P")
		{
		?>
        <font color="#006600">PROCESADO</font>
        <?php
		}
		elseif ($ls_estcob=="A")
		{
		?>
        <font color="#006600">ANULADO</font>
        <?php
		}
		elseif ($ls_estcob=="")
		{
		?>
        <font color="#006600">&nbsp;</font>
        <?php
		}
		elseif ($ls_estcob=="C")
		{
		?>
        <font color="#006600">CANCELADO</font>
        <?php
		}
		}else{
		?>
		</div></td>
      </div></td>
     <td width="75"><div align="right"></div></td>
	 <td>
          
          <input name="txtnumcob" type="text" id="txtnumcob" value="<?php print $ls_numcob ?>" size="28" maxlength="25" readonly> </td>
	   <?php
		 }
		 if ($ls_estcob=='E' or $ls_estcob=='' )
		 {
		 ?>
      <td colspan="4"><div align="right">Fecha</div></td>
      <td colspan="4"><input name="txtfeccob" type="text" id="txtfeccob"  style="text-align:left" value="<?php print $ls_feccob ?>" size="11" maxlength="10"   onKeyPress="javascript:ue_validafecha();" readonly="true" datepicker="true"></td>
	  <?php
	  }else{
	  ?>
	  <td><div align="right">Fecha</div></td>
      <td width="62"  colspan="4"><input name="txtfeccob" type="text" id="txtfeccob"  style="text-align:left" value="<?php print $ls_feccob ?>" size="11" maxlength="10"   onKeyPress="javascript:ue_validafecha();" readonly="true"></td>
	  <?php
	  }
	  ?>
    </tr>
    <!--tr class="formato-blanco">
      <td height="22">&nbsp;</td>
      <td height="22" align="right">Caja </td>
      <td colspan="7" ><input name="txtcodcaj" type="text" id="txtcodcaj" style="text-align:center " value="<?php //print $la_codcaj?>" size="15" maxlength="15"  readonly="true">
      <a href="javascript:ue_catcaja();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Caja"></a>
      <input name="txtnomcaj" type="text" id="txtnomcaj" class="sin-borde" size="40" readonly="true" value="<?php //print $la_nomcaj?>" ></td>
    </tr-->
    <tr class="formato-blanco">
      <td height="22">&nbsp;</td>
      <td height="22"><div align="right">C&oacute;digo Cliente</div></td>
      <td colspan="7"><input name="txtcodcli" type="text" id="txtcodcli" style="text-align:center " value="<?php print $ls_codcli ?>" size="15" maxlength="15" readonly="true">
	  <input name="txtcodcli1" type="hidden" id="txtcodcli1" style="text-align:center " value="<?php print $ls_codcli1 ?>" size="15" maxlength="15" readonly="true">
     <a href="javascript:ue_catclientefac();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
      <input name="txtnomcli" type="text" id="txtnomcli"  style="text-align:left" class="sin-borde" value="<?php print $ls_nomcli ?>" size="70" maxlength="100" readonly="true">      </td>
	</tr>
    <tr class="formato-blanco">
      <td height="22">&nbsp;</td>
      <td height="22"><div align="right">Observaci&oacute;n </div></td>
      <td colspan="7">
        <textarea name="txtobservacion" cols="80" rows="2" id="txtobservacion" style="text-align:left"><?php print $ls_observacion ?></textarea> <label></label>      </td>
	  </tr>

    <tr class="formato-blanco">
      <td height="22">&nbsp;</td>
      <td height="22"><div align="right">Especial </div></td>
      <td colspan="7">
      <label>
      <?php
			    	if ($ls_especial=="V")
						{
					    ?>
      <input name="check1" type="checkbox"  checked="checked" id="check1" value="check1" style="display:none;">
      <?php
						}
						else
						{
						?>
      <input name="check1" type="checkbox"  id="check1" value="check1"  checked="checked" style="display:none;">
      <?php
						}
				 ?>
      </label></td>
    </tr>
    <tr class="formato-blanco">
      <td height="82" colspan="9"><table width="609" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr class="formato-blanco">
          <td width="14" height="11">&nbsp;</td>
          <td width="593"><a href="javascript:ue_catproducto();"></a><a href="javascript:ue_catproducto();"></a></td>
        </tr>
        <tr align="center" class="formato-blanco">
          <td height="11" colspan="2"><?php $io_grid->makegrid($li_filasconcepto,$la_columconcepto,$la_objectconcepto,$li_anchoconcepto,$ls_tituloconcepto,$ls_nametable);?>           </td>
          <input name="filasconcepto" type="hidden" id="filasconcepto" value="<?php print $li_filasconcepto;?>">
          <input name="hidremoverconcepto" type="hidden" id="hidremoverconcepto" value="">
        </tr>
      </table>        </td>
    </tr>
	 <tr class="formato-blanco">
	  <td height="23">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width="85">&nbsp;</td>
    <td width="43">&nbsp;</td>
    <td width="47">&nbsp;</td>
    <td>&nbsp;</td>
      <td><div align="right">Resta por Pagar
      <input name="txtmonresta" style="text-align: right" type="text" id="txtmonresta" value="<?php print $ls_restaporpagar ?>" size="15" maxlength="15" readonly="true"></div></td>
	  <td>&nbsp;</td>
    </tr>
	 <tr class="formato-blanco">
	  <td height="23">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width="85">&nbsp;</td>
    <td width="43">&nbsp;</td>
    <td width="47">&nbsp;</td>
    <td>&nbsp;</td>
	   <td><div align="right">Monto IVA
          <input name="txtmoniva" type="text" id="txtmoniva"  style="text-align: right" value="<?php print $ls_monivat?>" size="20" maxlength="20"  readonly="true">
      </div></td>
	   <td>&nbsp;</td>
    </tr>
	 <tr class="formato-blanco">
        <td colspan="9"><div align="center">
          <table width="609" border="0" align="center" cellpadding="0" cellspacing="0">
		  <?php
			if ($ls_modificar=="True"){
		  ?>
            <tr class="formato-blanco">
              <td width="14" height="11">&nbsp;</td>
              <td width="593"><a href="javascript:ue_catinstrpago();"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0" onClick="actualizar_check1()"><span class="Estilo2">Forma de pago</span></a></td>
            </tr>
			<?php
			}else{
			?>
			 <tr class="formato-blanco">
              <td width="14" height="11">&nbsp;</td>
              <td width="593"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"><span class="Estilo2">Forma de pago</span></td>
            </tr>
			<?php
			}
			?>
            <tr align="center" class="formato-blanco">
              <td height="11" colspan="2"><?php $io_grid->makegrid($li_filasfpago,$la_columfpago,$la_objectfpago,$li_anchofpago,$ls_titulofpago,$ls_nametablefpago);?></td>
              <input name="filasfpago2" type="hidden" id="filasfpago2" value="<?php print $li_filasfpago;?>">
              <input name="hidremoverfpago2" type="hidden" id="hidremoverfpago2" value="<?php print $li_removerfpago;?>">
            </tr>
            <tr class="formato-blanco">
              <td colspan="2">&nbsp;</td>
            </tr>
          </table>
        </div>  </td>
  </tr>
  <tr class="formato-blanco">
    <td colspan="11"><table width="415" border="0" align="center" cellpadding="0" cellspacing="0" >

      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td width="111" height="13" align="right">&nbsp;</td>
        <td width="302" >&nbsp;</td>
      </tr>
      <tr>
        <td height="13" colspan="2" align="right"><?php $io_grid->makegrid($li_filasretenciones,$la_columretenciones,$la_objectretenciones,$li_anchoretenciones,$ls_tituloretenciones,$ls_nametable);?></td>
      </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
    </table></td>
  </tr>
    <tr class="formato-blanco">
      <td><div align="center"></div></td>
     <td height="23">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width="43">&nbsp;</td>
    <td width="47">&nbsp;</td>
    <td width="75">&nbsp;</td>
      <td><div align="right">Monto a cancelar
          <input name="txtmoncob" type="text" id="txtmoncob"  style="text-align: right" value="<?php print $ls_moncob ?>" size="20" maxlength="20"  readonly="true">
      </div></td>
	   <td>&nbsp;</td>
	  </tr>
      <tr class="formato-blanco">
        <td colspan="9"></td>
      </tr>
  <tr class="formato-blanco">
  <td height="23">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width="85">&nbsp;</td>
    <td width="43">&nbsp;</td>
    <td width="47">&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="right">Monto a cancelar  con retenci&oacute;n
        <input name="txtotalmonret" type="text" id="txtotalmonret"  style="text-align: right" value="<?php print $ls_totalmonret; ?>" size="20" maxlength="20" ></div></td>
 <td>&nbsp;</td>
    </tr>
	<tr class="formato-blanco">
	  <td height="23">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width="85">&nbsp;</td>
    <td width="43">&nbsp;</td>
    <td width="47">&nbsp;</td>
    <td>&nbsp;</td>
	<td><div align="right">Monto por cancelar

		   <input name="txtmoncob1" type="text" id="txtmoncob1"  style="text-align: right" value="<?php print $ls_totalmonret?>" size="20" maxlength="20"  readonly="true">
      </div></td>
	   <td>&nbsp;</td>
    </tr>
	 <tr class="formato-blanco">
    <td height="23">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width="85">&nbsp;</td>
    <td width="43">&nbsp;</td>
    <td width="47">&nbsp;</td>
    <td>&nbsp;</td>
    <td><div align="right">Total cancelado
      <input name="txttotalforpag" style="text-align: right" type="text" id="txttotalforpag" value="<?php print $ls_totalforpag ?>" size="15" maxlength="15" readonly="true">
    </div></td>
    <td>&nbsp;</td>
  </tr>
  <tr class="formato-blanco">
    <td height="23">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="formato-blanco">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
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

function cargar_monto(li_i)
{
 	f=document.form1;
	f.operacion.value="";

	ls_tipocancel=eval("f.combo_tipocancel"+li_i+".value");

	ttt=eval("f.txtmonto"+li_i+".value");
	if (ls_tipocancel=="T")
	 {
	 eval("f.txtmontocancel"+li_i+".value='"+ttt+"'");
	 }
	 else
	 {
	  eval("f.txtmontocancel"+li_i+".value='0,00'");
	 }


  ue_subtotal();
  ue_subtotalret();
  //ue_calretencion();
}

//////////////////////////////////////////////////////////////////////////////
function actualizar_check1()
{
   f=document.form1;
   f.operacion.value="";
   f.submit();
}

//////////////////////////////////////////////////////////////////////////////
function ue_buscar()
{
  	f=document.form1;
  	li_leer=f.leer.value;
	if(li_leer==1)
	{
		f.operacion.value="";
	    pagina="sigesp_cat_cobranza.php";
	    popupWin(pagina,"catalogo",850,450);
	}else{
		alert("No tiene permiso para realizar esta operacion");
	}

}

//////////////////////////////////////////////////////////////////////////////
function ue_cargarfpago(numinst,numfac,monto,denforpag,nombanco,codban,codforpag,fecins,obsins,metforpag,ctabanco) //x
{
	f=document.form1;
	f.operacion.value="";
	//alert (codban);
    eval("f.txtmontoforpag"+f.txtfilasfpago.value+".value='"+monto+"'");
	suiche=ue_subtotalfpago();
	if (suiche==true)
	{
	eval("f.txtdenforpag"+f.txtfilasfpago.value+".value='"+denforpag+"'");
	eval("f.txtmetforpag"+f.txtfilasfpago.value+".value='"+metforpag+"'");
	eval("f.txtnuminst"+f.txtfilasfpago.value+".value='"+numinst+"'");
	eval("f.txtfacret"+f.txtfilasfpago.value+".value='"+numfac+"'");
	eval("f.txtnombanco"+f.txtfilasfpago.value+".value='"+nombanco+"'");
	eval("f.txtctaban"+f.txtfilasfpago.value+".value='"+ctabanco+"'");
	eval("f.txtmontoforpag"+f.txtfilasfpago.value+".value='"+monto+"'");
	eval("f.txtcodban"+f.txtfilasfpago.value+".value='"+codban+"'");
	eval("f.txtcodforpag"+f.txtfilasfpago.value+".value='"+codforpag+"'");
	eval("f.txtfecins"+f.txtfilasfpago.value+".value='"+fecins+"'");
	eval("f.txtobsins"+f.txtfilasfpago.value+".value='"+obsins+"'");
	f.operacion.value="ue_cargarfpago";
	}
	else
	{
	}

	f.submit();
}

//////////////////////////////////////////////////////////////////////////////
function ue_catinstrpago()
{
 	f=document.form1;
   codcliente=f.txtcodcli1.value;
	ls_estatus='<?php print $ls_estatus; ?>';
	//alert (ls_estatus);
   if (f.txtmoncob1.value!='0,00')
   {
   monto=f.txtmoncob1.value;
   }else
   {
	monto=f.txtmoncob.value;
	}

	if (monto=="0,00")
      {
		alert("Debe incluir un monto a cancelar.");
	  }
	 else
	 {

	      f.operacion.value="";
			pagina="sigesp_cat_instpagcob.php?codcli1="+codcliente+"&total="+monto+"&estatus="+ls_estatus;
			popupWin(pagina,"catalogo",580,450);
	 }
}

//////////////////////////////////////////////////////////////////////////////
function ue_calretencion(li_i)
{
  f=document.form1;
  //f.operacion.value="ue_cargarretenciones";
  ls_tipocancel=	eval("f.combo_tipocancel"+li_i+".value");
  if(ls_tipocancel!='n')
  {
	  f.operacion.value="ue_calcretencion";
	  f.action="sigesp_sfc_d_cobranza.php";
	  f.submit();
  }
  else
  {
	   alert("Debe seleccionar el tipo de cancelaci�n");
  }
}

//////////////////////////////////////////////////////////////////////////////
function ue_cargarcobranza(numcob,razcli,feccob,moncob,codcli,descob,cedcli,estcob,esppag,caja,ciecaja)
{
	f=document.form1;
	f.txtcodcli.value=cedcli;
	f.txtcodcli1.value=codcli;
	f.txtnomcli.value=razcli;
	f.txtfeccob.value=feccob;
	f.txtnumcob.value=numcob;
	f.txtmoncob.value=moncob;
	//f.txtcodcaj.value=caja;
	f.txtciecaja.value=ciecaja;
	f.txtobservacion.value=descob;
	if (esppag=="V")
	{
	 f.check1.checked=true;
	}
	else
	{
	f.check1.checked=false;
	}
	f.operacion.value="ue_cargarcobros";
	f.operacion1.value="ue_cargarcobros";
	f.estcob.value=estcob;
	f.action="sigesp_sfc_d_cobranza.php";
	f.hidstatus.value = "C";

	f.submit();
}

//////////////////////////////////////////////////////////////////////////////
function ue_cargarcliente(codcli,nomcli,dircli,telcli,celcli,codpai,codest,codmun,codpar,producto,cedcli)
{

	f.txtcodcli.value=cedcli;
	f.txtcodcli1.value=codcli;
	f.txtnomcli.value=nomcli;
	f.estcob.value="";
	f.txtmoncob.value="0,00";
	f.operacion.value="ue_cargarfacturas_cliente";
	f.action="sigesp_sfc_d_cobranza.php";
	f.submit();

}

//////////////////////////////////////////////////////////////////////////////
function ue_catclientefac()
{
	f=document.form1;
    /*if(f.txtcodcaj.value==''){
    	alert('Seleccione la caja de la cual desea realizar la Cobranza!');
    }else{*/
    	f=document.form1;
		f.operacion.value="";
		pagina="sigesp_cat_clientecobranza.php";
		popupWin(pagina,"catalogo",520,350);
  // }


}

//////////////////////////////////////////////////////////////////////////////
function ue_removerfpago(li_fila)
{

	f=document.form1;
	f.hidremoverfpago.value=li_fila;
	f.operacion.value="ue_removerfpago";
	f.action="sigesp_sfc_d_cobranza.php";
	f.submit();

}

//////////////////////////////////////////////////////////////////////////////
function ue_removerconcepto(li_fila)
{

	f=document.form1;
	f.hidremoverconcepto.value=li_fila;
	f.operacion.value="ue_removerconcepto";
	f.action="sigesp_sfc_d_cobranza.php";
	f.submit();

}

function ue_guardar()
{
	f=document.form1;

	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidstatus.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{

		if( ue_verificarcheck() ){

			if (lb_status!="C")
			{
				f.hidstatus.value="C";
			}

			with(f)
			{
				if (f.estcob.value=="A"){
			    	alert("Cobro ANULADO no puede ser  MODIFICADO!!");
	  			}else if (f.estcob.value=="C"){
					alert("Cobro CANCELADO no puede ser  MODIFICADO!!");
				}else{
					if (ue_valida_null(txtcodcli,"Cliente")==false)
				 	{
				 		txtcodcli.focus();
				 	}else if (ue_valida_null(txtfeccob,"Fecha")==false){
				 		txtfeccob.focus();
				 	}else if (ue_valida_null(txtnumcob,"No. de factura")==false){
				 		txtnumcob.focus();
				 	}else if (ue_valida_null(txttotalforpag,"Monto")==false){
				 		txttotalforpag.focus();
                                        }else if (parseFloat(uf_convertir_monto(txtmoncob1.value)) < 0)
                                        {
					si=confirm("Se Generara una Nota de Credito a favor del cliente, Esta seguro de seguir?");
                                         if(si)
                                         {
                                            suiche=false;
                                         }else{
                                            alert("El monto de la factura debe coincidir con el de las formas de pago.");
                                            suiche=true;
                                         }

                                        } else if (parseFloat(uf_convertir_monto(txtmoncob1.value)) > 0)
                                        {
					alert("El monto de la factura debe coincidir con el de las formas de pago.");
					suiche=true;

				 	}else if (txttotalforpag.value!=txtotalmonret.value && check1.checked==false){
						alert("El monto a cancelar debe coincidir con el monto total señalado en las formas de pago.");
				 	}else if (txttotalforpag.value=="0,00"){
						alert("Debe incluir una forma de pago.");
				 	}else{
				 		if (f.estcob.value=="P"){
				 			alert("Cobro PROCESADO no puede ser  MODIFICADO!!");
		  				}
						else{
							f.operacion.value="ue_guardar";
							f.action="sigesp_sfc_d_cobranza.php";
							f.submit();
						}
				 	}
				}
			}

		}


	}else{
		alert("No tiene permiso para realizar esta operacion");
	}

}

function ue_nuevo()
{
	f=document.form1;

	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{
		f.operacion.value="ue_nuevo";
		f.txtnumcob.value="";
		f.txtfeccob.value="";
		f.txtcodcli.value="";
		f.txtnomcli.value="";
		f.txtmoncob.value="0,00";
		f.filasconcepto.value="";
		f.hidremoverconcepto.value="";
		f.action="sigesp_sfc_d_cobranza.php";
		f.submit();
	}else{
		alert("No tiene permiso para realizar esta operacion");
	}

}

function ue_subtotal()
{
 	f=document.form1;
	//alert ('paso 2');
	li_filasconcepto=f.filasconcepto.value;
	ld_subtotal=0;
	ld_subtotal2=0;
	ld_subtotal=0;
	ld_totaliva=0;
	ld_retencion=0;
	ld_porpagar=0;
	ld_porpagar2=0;
	ld_retencion=0;
	ld_montoret=0;
	ld_montoret2=0;
	ld_iva=0;
	ld_monto=0;
	ls_cero="0,00";
	ld_retencion=parseFloat(uf_convertir_monto(eval("f.txttotalret"+1+".value")));
	ld_montoret=parseFloat(uf_convertir_monto(eval("f.txtmontoret"+1+".value")));

	if (ld_retencion>ld_montoret)
	{
	eval("f.txttotalret"+1+".value=uf_convertir(roundNumber(ld_montoret))")
	alert("El Total de la Retención no debe ser mayor que el Monto");
	}

	for(li_i=1;li_i<=li_filasconcepto-1;li_i++)
	{

	  if(eval("f.txtmontocancel"+li_i+".value")=="")
	  {
	   ld_montocancel=0;
	  }
	  else
	  {
	   	  ld_montocancel=parseFloat(uf_convertir_monto(eval("f.txtmontocancel"+li_i+".value")));
		  ld_monto=parseFloat(uf_convertir_monto(eval("f.txtmonto"+li_i+".value")));
		  ld_iva=parseFloat(uf_convertir_monto(eval("f.txtmontoiva"+li_i+".value")));

				if (ld_montocancel>ld_monto && check1.checked==false)
				{
					eval("f.txtmontocancel"+li_i+".value='0,00'");
					eval("f.txttotalret"+1+".value='0,00'");
					eval("f.txtmontoret"+1+".value='0,00'");
					alert("El Monto a cancelar no puede ser mayor a la deuda por cobrar!");
				}
				else
				{
					ld_subtotal=ld_subtotal+ld_montocancel;
					  if (ld_montocancel==0)
					  {
					  	ld_subtotal2=ld_subtotal2+ld_monto;
					  }else{
					  	ld_subtotal2=ld_subtotal2+(ld_monto-ld_montocancel);
					   	ld_totaliva=ld_totaliva+ld_iva;
					  }
					  f.txtmoniva.value=uf_convertir(roundNumber(ld_totaliva));
					  f.txtmonresta.value=uf_convertir(roundNumber(ld_subtotal2));
				}
	  }

	}

	if (ld_retencion>='0')
	{
	//ld_subtotal=ld_subtotal-ld_retencion;
	ld_subtotal=ld_subtotal;
	//f.txtmontoret1.value=ld_retencion;
	}
	f.txtmoncob.value=uf_convertir(roundNumber(ld_subtotal));
	f.txtmoncob1.value=uf_convertir(roundNumber(ld_subtotal));
	f.txtotalmonret.value=uf_convertir(roundNumber(ld_subtotal));
}

function ue_subtotalfpago()
{
  	f=document.form1;
	li_filasfpago=f.txtfilasfpago.value;
	ld_monto=0;
	ls_cero="0,00";
	suiche=true;
	for(li_i=1;li_i<=li_filasfpago;li_i++)
	{
		//alert(eval("f.txtmontoforpag"+li_i+".value"));
	   if(eval("f.txtmontoforpag"+li_i+".value")=="")
		  {
		   ld_montoforpag=0;
		   }
	   else
		  {
		   ld_montoforpag=parseFloat(uf_convertir_monto(eval("f.txtmontoforpag"+li_i+".value")));
		   ld_monto=ld_monto+ld_montoforpag;
		   
			   if (roundNumber(ld_monto)>uf_convertir_monto(f.txtmoncob.value) && check1.checked==false)
			     {
				   alert("El monto de la forma de pago no puede ser superior al monto a cancelar 3");
				   suiche=false;
				 }

		 }
	}

	li_filasret = f.filasretenciones.value;
	ld_totalret = 0;
	for(li_i=1;li_i<li_filasret;li_i++){
		ld_check = eval("f.chkpagar"+li_i+".checked");
		if(ld_check){
			ld_totalret=ld_totalret+parseFloat(uf_convertir_monto(eval("f.txttotalret"+li_i+".value")));
		}
	}

	ld_retencion = ld_totalret;

   if (suiche==true)
   {
   	ld_resta1=roundNumber(ld_monto1-(ld_monto+ld_retencion));
	if( ld_resta1 < 0 && check1.checked==false ){
   		alert("El monto de la forma de pago no puede ser superior al monto a cancelar 1");
		suiche=false;
		ld_montoforpag=parseFloat(uf_convertir_monto(eval("f.txtmontoforpag"+li_filasfpago+".value")));
		ld_monto = ld_monto-ld_montoforpag;

		f.txttotalforpag.value=uf_convertir(ld_monto);
		ld_monto1=uf_convertir_monto(f.txtmoncob.value);
		ld_resta=ld_monto1-(ld_monto+ld_retencion);
		f.txtmoncob1.value=uf_convertir(roundNumber(ld_resta));
   	}else{
   		f.txttotalforpag.value=uf_convertir(ld_monto);
		ld_monto1=uf_convertir_monto(f.txtmoncob.value);
		ld_resta=ld_monto1-(ld_monto+ld_retencion);
		f.txtmoncob1.value=uf_convertir(roundNumber(ld_resta));
   	}


	}

	return suiche;
}

function ue_subtotalret(){

	f=document.form1;

	ld_monto1=uf_convertir_monto(f.txtmoncob.value);
	if(ld_monto1 != 0){

		li_filasret = f.filasretenciones.value;
		ld_totalret = 0;
		swche = false;
		for(li_i=1;li_i<li_filasret;li_i++){
			ld_check = eval("f.chkpagar"+li_i+".checked");
			if(ld_check){
				ld_totalret=ld_totalret+parseFloat(uf_convertir_monto(eval("f.txttotalret"+li_i+".value")));
			}
		}

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
				   if (roundNumber(ld_monto)>uf_convertir_monto(f.txtmoncob.value) && check1.checked==false)
				     {
					   alert("El monto de la forma de pago no puede ser superior al monto a cancelar 2");
					   suiche=false;
					 }

			 }
		}

		ld_monto1=uf_convertir_monto(f.txtmoncob.value);
		ld_restaaux=ld_monto1-(ld_monto+ld_totalret);

		if(roundNumber(ld_restaaux) < roundNumber(0) && check1.checked==false ){
			alert("El monto de la forma de pago no puede ser superior al monto a cancelar");
			suiche=false;
			ue_quitarcheck();

			f.txtotalmonret.value=uf_convertir(roundNumber(ld_monto1));
			ld_resta=ld_monto1-ld_monto;
			f.txtmoncob1.value=uf_convertir(roundNumber(ld_resta));
		}else{

			ld_resta=ld_monto1-(ld_totalret);

			f.txtotalmonret.value=uf_convertir(roundNumber(ld_resta));
			ld_resta=ld_monto1-(ld_monto+ld_totalret);

			f.txtmoncob1.value=uf_convertir(roundNumber(ld_resta));
			suiche=true;

		}

	}else{
		//alert("Introduzca el monto a cancelar!")
		ue_quitarcheck();
		suiche=false;
	}

	return suiche;
}

function ue_quitarcheck(){
	li_filasret = f.filasretenciones.value;
	for(li_i=1;li_i<li_filasret;li_i++){
		ld_check = eval("f.chkpagar"+li_i);
		ld_check.checked = false;
	}
}

function ue_verificarcheck(){
	f=document.form1;
	li_filasret = f.filasretenciones.value;
	valido = true;
	for(li_i=1;li_i<li_filasret;li_i++){
		ld_check = eval("f.chkpagar"+li_i+".checked");
		if(ld_check){
			valor = eval("f.txtcomprobante"+li_i+".value");
			if(valor == ""){
				alert("Debe Colocar el nro. de comprobante asociado a la retencion!");
				valido = false;
				li_i = li_filasret;
			}
		}
	}

	return valido;
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
	ue_subtotalfpago();
	return false;
}

function ue_eliminar()
{
	f=document.form1;

	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{

		  if (f.txtnumcob.value=="")
		  {
			alert("Debe seleccionar el No. de cobro a Anular!!");
		  }
		  else
		  {
			  if (f.estcob.value=="P")
			  {
			  	alert("Cobro PROCESADO no puede ser  ELIMINADO!!");
			  }else if (f.estcob.value=="A"){
			  	alert("Cobro ANULADO no puede ser  ELIMINADO!!");
			  }
			  else if (f.estcob.value=="C"){
			  	alert("Cobro CANCELADO no puede ser  ELIMINADO!!");
			  }
			  else{
			  	si=confirm("Esta seguro?");
			  	if(si)
			  	{
			  		f.action="sigesp_sfc_d_cobranza.php";
					f.operacion.value="ue_eliminar";
					f.submit();
				 }
			  }
		  }
	}else{
		alert("No tiene permiso para realizar esta operacion");
	}

}
function ue_anular()
{
    
    f=document.form1;
	ls_anular=f.anular.value;
	if(ls_anular==1){

                if (f.estcob.value=="")
		  {
			alert("Debe seleccionar El Cobro a anular!!");
		  }
		  else
		  {
		  	if (f.estcob.value=="A")
		  	{
		  		//alert("El Cobro ya fue Anulado!!");

                                var numcob="<?php print $ls_numcob; ?>";
                                //alert(ls_formalibre);
                                pagina="reportes/sigesp_sfc_rep_cobro_anulado.php?numcob="+numcob;
                                popupWin(pagina,"catalogo",580,700);
                                
		  	}else{
		  
                          si=confirm("Esta seguro que desea anular el cobro?");
                             if(si)
                             {
                                    Xpos=((screen.width/2)-(500/2)); 
                                    Ypos=((screen.height/2)-(400/2));
                                    window.open("sigesp_cat_conceptoanula_cobro.php?cobro="+document.getElementById('txtnumcob').value+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=400,height=180,left="+Xpos+",top="+Ypos+",location=no,resizable=no");

                             }
                      }
                  }
	}else{
		alert("No tiene permiso para realizar esta operacion");
	}
    
	
}

function verificarAnulacion(srtObservacion){
    document.form1.txtobsanul.value=srtObservacion;
    f=document.form1;
    f.action="sigesp_sfc_d_cobranza.php";
    f.operacion.value="ue_anular";
    //suiche_submit=true;
    f.submit();
}

function ue_ver()
{
  f=document.form1;
  f.operacion.value="VER";
  f.action="sigesp_sfc_d_cobranza.php";
  f.submit();
}

function ue_cargarcaja(codcaja,ls_destienda,codtienda,desccaja,precot,prefac,predev,preped,sercot,serfac,serdev,serped,sernot,sercon,formalibre,precob,sercob)
{
	f=document.form1;
	//f.txtcodcaj.value = codcaja;
	f.txtcodcaj.value = codtienda;
	f.txtnomcaj.value = desccaja;

}
function ue_catcaja(){
	pagina="sigesp_cat_caja.php";
	popupWin(pagina,"catalogo",650,300);
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
