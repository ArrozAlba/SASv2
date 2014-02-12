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
<title>Cobranza</title>
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
.Estilo3 {color: #6699CC}
-->
</style><meta http-equiv="Content-Type" content="text/html; charset="></head>
<body link="#006699" vlink="#006699" alink="#006699"  onLoad="ue_subtotal(); ">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
require_once("../shared/class_folder/sigesp_c_seguridad.php");
$io_seguridad= new sigesp_c_seguridad();
$arre=$_SESSION["la_empresa"];
$ls_empresa=$arre["codemp"];
$ls_logusr=$_SESSION["la_logusr"];
$ls_sistema="SFC";
$ls_ventanas="sigesp_sfc_d_cobranzacartas.php";
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
}
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
require_once("class_folder/sigesp_sfc_c_cobranzacarta.php");
require_once("class_folder/sigesp_sfc_c_secuencia.php");
require_once("class_folder/sigesp_sfc_c_nota.php");
require_once("class_folder/sigesp_sfc_class_utilidades.php");
$io_utilidad   = new sigesp_sfc_class_utilidades();
$io_nota       = new sigesp_sfc_c_nota();
$io_include    = new sigesp_include();
$io_connect    = $io_include->uf_conectar();
$io_sql        = new class_sql($io_connect);
$io_funcdb     = new class_funciones_db($io_connect);
$io_funcsob    = new sigesp_sob_c_funciones_sob();
$io_evalform   = new evaluate_formula();
$io_grid       = new grid_param();
$io_msg        = new class_mensajes();
$io_datastore  = new class_datastore();
$io_data       = new class_datastore();
$io_function   = new class_funciones();
$io_secuencia  = new sigesp_sfc_c_secuencia();
$io_cotizacion = new  sigesp_sfc_c_cotizacion();
$io_factura    = new  sigesp_sfc_c_factura();
$io_instpago   = new sigesp_sfc_c_instrpago();
$io_instpagocob= new sigesp_sfc_c_instpagocob();
$io_cobranza   = new  sigesp_sfc_c_cobranzacarta();
/**************   GRID    CARTAS ORDENES   POR   COBRAR   *******************/
$ls_codemp=$la_datemp["codemp"];
$ls_tituloconcepto="Cartas Ordenes por Cobrar";
$li_anchoconcepto=950;
$ls_nametable="grid1";
$la_columconcepto[1]="";
$la_columconcepto[2]="No. Carta Orden";
$la_columconcepto[3]="No. Factura";
$la_columconcepto[4]="Fecha";
$la_columconcepto[5]="Monto";
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion1   =$_POST["operacion1"];
	$ls_operacion    =$_POST["operacion"];
	$ls_codusu       =$_SESSION["la_logusr"];
	$ls_codbanco     =$_POST["txtcodbanco"];
	$ls_ctaban       =$_POST["txtctaban"];
	$ls_nombanco     =$_POST["txtnombanco"];
	$ls_observacion  =$_POST["txtobservacion"];
	$ls_numcob       =$_POST["txtnumcob"];
	$ls_feccob       =$_POST["txtfeccob"];
	$ls_numinst      =$_POST["txtnuminst"];
	$ls_fecinst      =$_POST["txtfecinst"];
	$ls_montoforpag  =$_POST["txtmontoforpag"];
	$ls_totalforpag  =$_POST["txttotalforpag"];
	$li_filasconcepto=$_POST["filasconcepto"];
    $li_removerconcepto=$_POST["hidremoverconcepto"];
	$ls_moncob       =$_POST["txtmoncob"];
	$ls_restaporpagar=$_POST["txtmonresta"];
	$ls_monxcob      =$_POST["txtmoncob1"];
	$ls_codalm       =$_POST["hidcodalm"];
	$ls_estcob       =$_POST["estcob"];
	$ls_modificar    =$_POST["modificar"];
	$ls_hidstatus    =$_POST["hidstatus"];
	$ls_feccar       =$_POST["txtfeccar"];
	$ls_feccar2      =$_POST["txtfeccar2"];
	$la_codcaj       =$_POST["txtcodcaj"];
	$la_nomcaj       =$_POST["txtnomcaj"];

if ($ls_operacion != "ue_cargarfacturas_banco")
   {
 	for($li_i=1;$li_i<$li_filasconcepto;$li_i++)
		{

		$ls_numcarta=$_POST["txtnumcarta".$li_i];
		$ls_numfacasoc=$_POST["txtnumfacasoc".$li_i];
		$ls_codcli=$_POST["txtcodcli".$li_i];
		$ls_fecemi=$_POST["txtfecemi".$li_i];
		$ls_monto=$_POST["txtmonto".$li_i];
		$ls_numcob=$_POST["txtnumcob"];
		if ($_POST["chk".$li_i]=='')
			   {
			    $ls_estacarta='0';
			   }else{
			   $ls_estacarta='1';
			   }
		if ($ls_monto==0 and $ls_estcob!='A')
		{
			if ($ls_estacarta=='1')
			{
					$la_objectconcepto[$li_i][1]= "<input name=chk".$li_i." type=checkbox id=chk".$li_i." checked=checked   class=sin-borde  size=1 onChange=javascript:ue_subtotal()>";
			}else
			{
					$la_objectconcepto[$li_i][1]= "<input name=chk".$li_i." type=checkbox id=chk".$li_i." value=chk  class=sin-borde  size=1 onChange=javascript:ue_subtotal()>";
			}
					$la_objectconcepto[$li_i][2]="<input name=txtnumcarta".$li_i." style=color:#FF0000 type=text id=txtnumcarta".$li_i." value='".$ls_numcarta."' class=sin-borde size=30 style= text-align:center readonly>";
					$la_objectconcepto[$li_i][3]="<input name=txtnumfacasoc".$li_i." style=color:#FF0000 type=text id=txtnumfacasoc".$li_i." value='".$ls_numfacasoc."' class=sin-borde size=30 style= text-align:center readonly><input name=txtcodcli".$li_i." style=color:#FF0000 type=hidden id=txtcodcli".$li_i." value='".$ls_codcli."' class=sin-borde size=30 style= text-align:center readonly>";
					$la_objectconcepto[$li_i][4]="<input name=txtfecemi".$li_i." style=color:#FF0000 type=text id=txtfecemi".$li_i." value='".$ls_fecemi."' class=sin-borde size=10 style= text-align:left readonly>";
					$la_objectconcepto[$li_i][5]="<input name=txtmonto".$li_i."  style=color:#FF0000  style=text-align:right type=text id=txtmonto".$li_i." value='".$ls_monto."' class=sin-borde size=15  readonly>";
				}
			else if ($ls_estcob=='A')
			{
				if ($ls_estacarta=='1')
				{
	    		$la_objectconcepto[$li_i][1]= "<input name=chk".$li_i." type=checkbox id=chk".$li_i." disabled=disabled checked=checked  size=1  class=sin-borde  onChange=javascript:ue_subtotal()>";
				}else
				{
				$la_objectconcepto[$li_i][1]= "<input name=chk".$li_i." type=checkbox id=chk".$li_i." disabled=disabled value=chk  size=1 class=sin-borde  onChange=javascript:ue_subtotal()>";
				}
				$la_objectconcepto[$li_i][2]="<input name=txtnumcarta".$li_i." style=color:#FF0000 type=text id=txtnumcarta".$li_i." value='".$ls_numcarta."' class=sin-borde size=30style= text-align:center readonly>";
				$la_objectconcepto[$li_i][3]="<input name=txtnumfacasoc".$li_i." style=color:#FF0000 type=text id=txtnumfacasoc".$li_i." value='".$ls_numfacasoc."' class=sin-borde size=30 style= text-align:center readonly><input name=txtcodcli".$li_i." style=color:#FF0000 type=hidden id=txtcodcli".$li_i." value='".$ls_codcli."' class=sin-borde size=30 style= text-align:center readonly>";
				$la_objectconcepto[$li_i][4]="<input name=txtfecemi".$li_i." style=color:#FF0000 type=text id=txtfecemi".$li_i." value='".$ls_fecemi."' class=sin-borde size=10 style= text-align:left readonly>";
				$la_objectconcepto[$li_i][5]="<input name=txtmonto".$li_i."  style=color:#FF0000  style=text-align:right type=text id=txtmonto".$li_i." value='".$ls_monto."' class=sin-borde size=15  readonly>";
			}
			else
			{
				if ($ls_estacarta=='1')
				{
	    		$la_objectconcepto[$li_i][1]= "<input name=chk".$li_i." type=checkbox id=chk".$li_i." checked=checked  size=1 class=sin-borde  onChange=javascript:ue_subtotal()>";
				}else
				{
				$la_objectconcepto[$li_i][1]= "<input name=chk".$li_i." type=checkbox id=chk".$li_i." value=chk size=1  class=sin-borde  onChange=javascript:ue_subtotal()>";
				}
				$la_objectconcepto[$li_i][2]="<input name=txtnumcarta".$li_i." type=text id=txtnumcarta".$li_i." value='".$ls_numcarta."' class=sin-borde size=30 style= text-align:center readonly>";
				$la_objectconcepto[$li_i][3]="<input name=txtnumfacasoc".$li_i." type=text id=txtnumfacasoc".$li_i." value='".$ls_numfacasoc."' class=sin-borde size=30 style= text-align:center readonly><input name=txtcodcli".$li_i." style=color:#FF0000 type=hidden id=txtcodcli".$li_i." value='".$ls_codcli."' class=sin-borde size=30 style= text-align:center readonly>";
				$la_objectconcepto[$li_i][4]="<input name=txtfecemi".$li_i." type=text id=txtfecemi".$li_i." value='".$ls_fecemi."' class=sin-borde size=10 style= text-align:left readonly>";
				$la_objectconcepto[$li_i][5]="<input name=txtmonto".$li_i." type=text id=txtmonto".$li_i." value='".$ls_monto."' class=sin-borde size=15 style= text-align:right readonly>";
			}
		}//for
		$la_objectconcepto[$li_filasconcepto][1]="<input name=txtvacio type=text id=txtvacio  class=sin-borde style= text-align:center size=1>";
		$la_objectconcepto[$li_filasconcepto][2]="<input name=txtnumcarta".$li_filasconcepto." type=text id=txtnumcarta".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly>";
		$la_objetoconcepto[$li_filasconcepto][3]="<input name=txtnumfacasoc".$li_filasconcepto." type=text id=txtnumfacasoc".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly><input name=txtcodcli".$li_filasconcepto." style=color:#FF0000 type=hidden id=txtcodcli".$li_filasconcepto." value='".$ls_codcli."' class=sin-borde size=30 style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][4]="<input name=txtfecemi".$li_filasconcepto." type=text id=txtfecemi".$li_filasconcepto." class=sin-borde size=10 style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][5]="<input name=txtmonto".$li_filasconcepto." type=text id=txtmonto".$li_filasconcepto." class=sin-borde size=15 style= text-align:right readonly>";
		}
}
else
{
    $ls_operacion = "";
	$ls_numcarta  = "";
	$ls_nunfacasoc= "";
	$ls_numcob    = "";
	$ls_numinst   = "";
	$ls_codbanco  = "";
	$ls_nombanco  = "";
	$ls_fecemi    = "";
	$ls_feccob    = "";
	$ls_fecinst   = "";
	$ls_subtot    = "0,00";
	$ls_monto     = "0,00";
	$ls_observacion="";
    $ls_moncob    = "0,00";
	$ls_restaporpagar='0,00';
	$ls_tipocancel= "S";
	$ls_codforpag = "";
	$ls_feccob    = date('d/m/Y');
	$li_filasconcepto=1;
	$ls_estcob    = "";
	$ls_modificar = "True";
	$ls_hidstatus =  "";
	$ls_feccar    = "";
	$ls_feccar2   = "";
	$la_codcaj    = "";
	$la_nomcaj    = "";

    $la_objectconcepto[$li_filasconcepto][1]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=1>";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtnumcarta".$li_filasconcepto." type=text id=txtnumcarta".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly>";
	$la_objetoconcepto[$li_filasconcepto][3]="<input name=txtnumfacasoc".$li_filasconcepto." type=text id=txtnumfacasoc".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly><input name=txtcodcli".$li_filasconcepto." style=color:#FF0000 type=hidden id=txtcodcli".$li_filasconcepto." value='".$ls_codcli."' class=sin-borde size=30 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txtfecemi".$li_filasconcepto." type=text id=txtfecemi".$li_filasconcepto." class=sin-borde size=10 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txtmonto".$li_filasconcepto." type=text id=txtmonto".$li_filasconcepto." class=sin-borde size=15 style= text-align:right readonly>";
}
if($ls_operacion=="ue_nuevo")
{
	$la_empresa = $_SESSION["la_empresa"];
	$ls_codcaj  = $_SESSION["ls_codcaj"];
	$ls_codcaj  = "C01";
	$ls_prefijo = $_SESSION["ls_precob"];
	$ls_serie   = $_SESSION["ls_sercob"];
	$io_secuencia->uf_ver_secuencia($ls_codcaj.$ls_codtie."cob",&$ls_secuencia);
	$ls_secuencia  =$io_function->uf_cerosizquierda($ls_secuencia,16);
	$ls_numcob     =$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);
	$ls_numinst    ="";
	$ls_fecemi     ="";
	$ls_fecinst    ="";
	$$ls_codbanco  ="";
	$ls_nomban     ="";
	$ls_feccob     =date('d/m/Y');
	$ls_subtot     ="0,00";
	$ls_monto      ="0,00";
	$ls_conpag     =1;
	$ls_operacion1 ="ue_nuevo";
	$ls_operacion  ="";
	$ls_numcarta   ="";
	$ls_estcob     ="";
	$ls_modificar  ="True";
	$ls_totalforpag="0,00";
	$ls_observacion="";
    $ls_moncob     ="0,00";
	$ls_restaporpagar='0,00';
	$ls_tipocancel ="S";
	$ls_codforpag  ="";
	$ls_feccar     ="";
	$ls_feccar2    ="";
    $la_codcaj     ="";
	$ls_ctaban     ="";
	
	$li_filasconcepto=1;
    $la_objectconcepto[$li_filasconcepto][1]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=1>";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtnumcarta".$li_filasconcepto." type=text id=txtnumcarta".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly>";
	$la_objetoconcepto[$li_filasconcepto][3]="<input name=txtnumfacasoc".$li_filasconcepto." type=text id=txtnumfacasoc".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly><input name=txtcodcli".$li_filasconcepto." type=hidden id=txtcodcli".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txtfecemi".$li_filasconcepto." type=text id=txtfecemi".$li_filasconcepto." class=sin-borde size=10 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txtmonto".$li_filasconcepto." type=text id=txtmonto".$li_filasconcepto." class=sin-borde size=15 style= text-align:right readonly>";
}
elseif ($ls_operacion=="ue_cargarcobros")
{
	$li_filasconcepto=1;
	$ld_summonto     =0;
    $la_objectconcepto[$li_filasconcepto][1]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=1>";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtnumcarta".$li_filasconcepto." type=text id=txtnumcarta".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly>";
	$la_objetoconcepto[$li_filasconcepto][3]="<input name=txtnumfacasoc".$li_filasconcepto." type=text id=txtnumfacasoc".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly><input name=txtcodcli".$li_filasconcepto." type=hidden id=txtcodcli".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txtfecemi".$li_filasconcepto." type=text id=txtfecemi".$li_filasconcepto." class=sin-borde size=10 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txtmonto".$li_filasconcepto." type=text id=txtmonto".$li_filasconcepto." class=sin-borde size=15 style= text-align:right readonly>";
					   
	$ls_cadena="SELECT co.numcob,co.codcli,co.moncancel,n.fecnot,n.monto,co.numcartaorden,n.nro_documento,n.estnota
				FROM   sfc_nota n,sfc_dt_cobrocartaorden co
				WHERE  n.numnot=co.numcartaorden 
				AND    co.numcob = '".$ls_numcob."' 
				AND    (n.estnota='P' OR n.estnota='C') 
				AND    n.tipnot='CXC'";
	$arr_detcobranza=$io_sql->select($ls_cadena);
	if($arr_detcobranza==false&&($io_sql->message!=""))
	{
		$io_msg->message("No hay registros de Cartas Ordenes  por Cobrar!");
	}
	else
	{
		if($row=$io_sql->fetch_row($arr_detcobranza))
		{
			$la_cobros=$io_sql->obtener_datos($arr_detcobranza);
			$io_datastore->data=$la_cobros;
			$totrow=$io_datastore->getRowCount("numcob");
			$ls_modificar="True";
			for($li_i=1;$li_i<=$totrow;$li_i++)
			{
				$ls_estatus="V";
				$ls_numcarta   = $io_datastore->getValue("numcartaorden",$li_i);
				$ls_numfacasoc = $io_datastore->getValue("nro_documento",$li_i);
				$ls_codcli     = $io_datastore->getValue("codcli",$li_i);
				$ls_fecemi     = $io_datastore->getValue("fecnot",$li_i);
				$ls_fecemi     = $io_function->uf_convertirfecmostrar($ls_fecemi);
				$ls_montocancel= $io_datastore->getValue("monto",$li_i);
				$ls_monto      = $io_datastore->getValue("monto",$li_i);
				$ld_summonto   = $ld_summonto+$ls_monto;				
				$ls_monto      = number_format($ls_monto,2, ',', '.');
				$ls_montocancel= number_format($ls_montocancel,2, ',', '.');
				$ls_estacarta  = $io_datastore->getValue("estnota",$li_i);
				$ls_numcob2    = substr($ls_numcob,23,strlen($ls_numcob));
				$ls=0;
				$fecaux=date('d/m/Y');
				$existe_cobro=0;
				$existe_factura=0;
				/*
				$ls_cadena2="SELECT co.numcob,cf.codcli,n.fecnot,co.moncob,cf.moncob,n.nro_documento  
				             FROM   sfc_nota n,sfc_cobrocartaorden co,sfc_cobro_cliente cf 
				             WHERE  cf.numcob=n.numnot AND cf.numcob=co.numcob  
							 AND    co.feccob='".substr ($fecaux,6,4)."-".substr($fecaux,3,2)."-".substr($fecaux,0,2)."' 
							 AND    cf.numcob>'".$ls_numcob."'";
				*/			 
				$ls_cadena2="SELECT co.numcob,co.feccob,co.estcob,co.codtiend,s.nomban,s.codban,o.numinst, o.ctaban, o.fecins
						 	 FROM   sfc_cobrocartaorden co, scb_banco s, sfc_instpagocobcartaorden o
						 	 WHERE  co.codemp='0001' 
							 AND    co.codemp=s.codemp 
							 AND    co.codemp=o.codemp 
							 AND    o.codemp=s.codemp 
							 AND    co.codban=s.codban 
							 AND    co.codban=o.codban 
							 AND    o.codban=s.codban 
							 AND    co.numcob=o.numcob
							 AND    co.codtiend=o.codtiend
							 AND    co.codtiend='".$ls_codtie."' 
							 AND    co.numcob like '".$ls_numcob."' 
							 AND    s.codban  like '".$ls_codbanco."'
							 AND    co.feccob='".substr ($fecaux,6,4)."-".substr($fecaux,3,2)."-".substr($fecaux,0,2)."' 
							 AND    co.numcob>'".$ls_numcob."' ";	
				// print $ls_cadena2."<br>";
				$arr_detcobro=$io_sql->select($ls_cadena2);
				if ($row=$io_sql->fetch_row($arr_detcobro))
				{
				     $ls_modificar="False";
				}
				if ($ls_modificar=="False" or $ls_estcob=='A')
				{
				/*if ($ls_estacarta=='1')
					{*/
					$la_objectconcepto[$li_i][1]= "<input name=chk".$li_i." type=checkbox id=chk".$li_i." checked=checked  size=1 class=sin-borde  onChange=javascript:ue_subtotal() disabled=disabled>";
					/*}else
					{
					$la_objectconcepto[$li_i][1]= "<input name=chk".$li_i." type=checkbox id=chk".$li_i." value=chk size=1  class=sin-borde  onChange=javascript:ue_subtotal() disabled=disabled>";
					}*/
					$la_objectconcepto[$li_i][2]="<input name=txtnumcarta".$li_i." type=text id=txtnumcarta".$li_i." value='".$ls_numcarta."' class=sin-borde size=30 style= text-align:center readonly>";
					$la_objectconcepto[$li_i][3]="<input name=txtnumfacasoc".$li_i." type=text id=txtnumfacasoc".$li_i." value='".$ls_numfacasoc."' class=sin-borde size=30 style= text-align:center readonly><input name=txtcodcli".$li_i."  type=hidden id=txtcodcli".$li_i."  value='".$ls_codcli."' class=sin-borde size=30 style= text-align:center readonly>";
					$la_objectconcepto[$li_i][4]="<input name=txtfecemi".$li_i." type=text id=txtfecemi".$li_i." value='".$ls_fecemi."' class=sin-borde size=10 style= text-align:left readonly>";
					$la_objectconcepto[$li_i][5]="<input name=txtmonto".$li_i." type=text id=txtmonto".$li_i." value='".$ls_monto."' class=sin-borde size=15 style= text-align:center readonly>";
				}
				else
				{
					/*if ($ls_estacarta=='1')
					{*/
					$la_objectconcepto[$li_i][1]= "<input name=chk".$li_i." type=checkbox id=chk".$li_i." checked=checked  size=1 class=sin-borde  onChange=javascript:ue_subtotal() readonly>";
					/*}else
					{
					$la_objectconcepto[$li_i][1]= "<input name=chk".$li_i." type=checkbox id=chk".$li_i." value=chk size=1  class=sin-borde  onChange=javascript:ue_subtotal() readonly>";
					}
					*/
					$la_objectconcepto[$li_i][2]="<input name=txtnumcarta".$li_i." type=text id=txtnumcarta".$li_i." value='".$ls_numcarta."' class=sin-borde size=30 style= text-align:center readonly>";
					$la_objectconcepto[$li_i][3]="<input name=txtnumfacasoc".$li_i." type=text id=txtnumfacasoc".$li_i." value='".$ls_numfacasoc."'  class=sin-borde size=30 style= text-align:center readonly><input name=txtcodcli".$li_i." type=hidden id=txtcodcli".$li_i." value='".$ls_codcli."'  class=sin-borde size=30 style= text-align:center readonly>";
					$la_objectconcepto[$li_i][4]="<input name=txtfecemi".$li_i." type=text id=txtfecemi".$li_i." value='".$ls_fecemi."' class=sin-borde size=10 style= text-align:left readonly>";
					$la_objectconcepto[$li_i][5]="<input name=txtmonto".$li_i." type=text id=txtmonto".$li_i." value='".$ls_monto."' class=sin-borde size=15 style= text-align:center readonly>";
				}
			} //for
			$li_filasconcepto=$li_i;
			$la_objectconcepto[$li_filasconcepto][1]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=1>";
			$la_objectconcepto[$li_filasconcepto][2]="<input name=txtnumcarta".$li_filasconcepto." type=text id=txtnumcarta".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly>";
			$la_objectconcepto[$li_filasconcepto][3]="<input name=txtnumfacasoc".$li_filasconcepto." type=text id=txtnumfacasoc".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly><input name=txtcodcli".$li_filasconcepto." type=hidden id=txtcodcli".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly>";
			$la_objectconcepto[$li_filasconcepto][4]="<input name=txtfecemi".$li_filasconcepto." type=text id=txtfecemi".$li_filasconcepto." class=sin-borde size=10 style= text-align:left readonly>";
			$la_objectconcepto[$li_filasconcepto][5]="<input name=txtmonto".$li_filasconcepto." type=text id=txtmonto".$li_filasconcepto." class=sin-borde size=15 style= text-align:right readonly>";
		    
		}// if($row=$io_sql->fetch_row($arr_detfactura))
	} //if ($arr_detfactura==false&&($io_sql->message!=""))
	}
	elseif ($ls_operacion=="ue_cargarcartas_banco")
	{
	$li_filasconcepto=1;
	$la_objectconcepto[$li_filasconcepto][1]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center  size=1>";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtnumcarta".$li_filasconcepto." type=text id=txtnumcarta".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly>";
	$la_objetoconcepto[$li_filasconcepto][3]="<input name=txtnumfacasoc".$li_filasconcepto." type=text id=txtnumfacasoc".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly><input name=txtcodcli".$li_filasconcepto." type=hidden id=txtcodcli".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txtfecemi".$li_filasconcepto." type=text id=txtfecemi".$li_filasconcepto." class=sin-borde size=10 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txtmonto".$li_filasconcepto." type=text id=txtmonto".$li_filasconcepto." class=sin-borde size=15 style= text-align:right readonly>";
	$existe_carta=0;
	$ls_monto2=0;
	$li_i2=0;

	$ls_feccaraux=substr($ls_feccar,6,4).'-'.substr($ls_feccar,3,2).'-'.substr($ls_feccar,0,2);
	$ls_feccaraux2=substr($ls_feccar2,6,4).'-'.substr($ls_feccar2,3,2).'-'.substr($ls_feccar2,0,2);
	$ls_cadena="SELECT n.codcli,n.numnot,n.nro_documento,n.fecnot,n.monto 
	            FROM   sfc_nota n,sfc_instpago ip,sfc_factura f 
			    WHERE  ip.codban='".$ls_codbanco."' 
				AND    n.estnota='P' 
				AND    n.tipnot='CXC' 
				AND    n.numnot!=n.nro_documento 
				AND    f.numfac=n.nro_documento 
				AND    n.numnot=ip.numinst 
				AND    f.codtiend='".$la_codcaj."' 
			    AND    n.nro_documento=ip.numfac 
			    AND    substr(f.fecemi,0,11) >='".$ls_feccaraux."' 
			    AND    substr(f.fecemi,0,11) <='".$ls_feccaraux2."' 
				ORDER BY n.nro_documento ASC";
	
	$arr_detcarta=$io_sql->select($ls_cadena);
	$w=$io_sql->fetch_row($arr_detcarta);
	if ($w!="")
	{
		$arr_detcarta=$io_sql->select($ls_cadena);
		$w=$io_sql->fetch_row($arr_detcarta);
		$existe_carta=1;
	}
	else
	{
		$existe_carta=0;
	}
	if ($existe_carta==1)
	{
		$arr_detcarta=$io_sql->select($ls_cadena);
		if($row=$io_sql->fetch_row($arr_detcarta))
		{
			$la_carta=$io_sql->obtener_datos($arr_detcarta);
			$io_datastore->data=$la_carta;
			$totrow=$io_datastore->getRowCount("numnot");
			for($li_i=1;$li_i<=$totrow;$li_i++)
			{
				$li_i2=$li_i2+1;
				$ls_numcarta=$io_datastore->getValue("numnot",$li_i);
				$ls_numfacasoc=$io_datastore->getValue("nro_documento",$li_i);
				$ls_codcli=$io_datastore->getValue("codcli",$li_i);
				$ls_fecemi=$io_datastore->getValue("fecnot",$li_i);
				$ls_monto2=$io_datastore->getValue("monto",$li_i);
				$ls_monto=$ls_monto2;
				$ls_fecemi=$io_function->uf_convertirfecmostrar($ls_fecemi);
				$arreglo_cobro[$li_i2]["numnot"]=$ls_numcarta;
				$arreglo_cobro[$li_i2]["nro_factura"]=$ls_numfacasoc;
				$arreglo_cobro[$li_i2]["codcli"]=$ls_codcli;
				$arreglo_cobro[$li_i2]["fecnot"]=$ls_fecemi;
				$arreglo_cobro[$li_i2]["monto"]=$ls_monto2;
			} //for
		}//if($row=$io_sql->fetch_row($arr_detfactura)
	 } //if($existe_carta==1)
	if ($existe_carta==1)
	{
		//$ls_numcob= $io_funcdb->uf_generar_codigo(false,0,"sfc_cobro","numcob",25);
		$ls_nrocartas=0;
		for($li_i=1;$li_i<=$li_i2;$li_i++)
		{

			$ls_numcarta=$arreglo_cobro[$li_i]["numnot"];
			$ls_numfacasoc=$arreglo_cobro[$li_i]["nro_factura"];
			$ls_fecemi=$arreglo_cobro[$li_i]["fecnot"];
			$ls_monto=$arreglo_cobro[$li_i]["monto"];
			$ls_codcli=$arreglo_cobro[$li_i]["codcli"];
			$ls_monto=number_format($ls_monto,2, ',', '.');
			 if ($_POST["chk".$li_i]=='')
			   {
			    $ls_estacarta='0';
			   }else{
			   $ls_estacarta='1';
			   }
			if ($ls_monto=="0,00")
			{
				if ($ls_estacarta=='1')
				{
	    		$la_objectconcepto[$li_i][1]= "<input name=chk".$li_i." type=checkbox id=chk".$li_i." checked=checked  size=1 class=sin-borde  onChange=javascript:ue_subtotal()>";
				}else
				{
				$la_objectconcepto[$li_i][1]= "<input name=chk".$li_i." type=checkbox id=chk".$li_i." value=chk size=1  class=sin-borde  onChange=javascript:ue_subtotal()>";
				}
				$la_objectconcepto[$li_i][2]="<input name=txtnumcarta".$li_i." style=color:#FF0000 type=text id=txtnumcarta".$li_i." value='".$ls_numcarta."' class=sin-borde size=30 style= text-align:center readonly>";
				$la_objectconcepto[$li_i][3]="<input name=txtnumfacasoc".$li_i." style=color:#FF0000 type=text id=txtnumfacasoc".$li_i." value='".$ls_numfacasoc."' class=sin-borde size=30 style= text-align:center readonly><input name=txtcodcli".$li_i." style=color:#FF0000 type=hidden id=txtcodcli".$li_i." value='".$ls_codcli."' class=sin-borde size=30 style= text-align:center readonly>";
				$la_objectconcepto[$li_i][4]="<input name=txtfecemi".$li_i." style=color:#FF0000 type=text id=txtfecemi".$li_i." value='".$ls_fecemi."' class=sin-borde size=10 style= text-align:left readonly>";
				$la_objectconcepto[$li_i][5]="<input name=txtmonto".$li_i."  style=color:#FF0000 type=text id=txtmonto".$li_i." value='                   ".$ls_monto."' class=sin-borde size=15 style= text-align:right readonly>";
			}
			else
			{
			    $la_objectconcepto[$li_i][1]= "<input name=chk".$li_i." type=checkbox id=chk".$li_i." value=chk  size=1 class=sin-borde  onChange=javascript:ue_subtotal()>";
				$la_objectconcepto[$li_i][2]="<input name=txtnumcarta".$li_i." type=text id=txtnumcarta".$li_i." value='".$ls_numcarta."' class=sin-borde size=30 style= text-align:center readonly>";
				$la_objectconcepto[$li_i][3]="<input name=txtnumfacasoc".$li_i." type=text id=txtnumfacasoc".$li_i." value='".$ls_numfacasoc."' class=sin-borde size=30 style= text-align:center readonly><input name=txtcodcli".$li_i." style=color:#FF0000 type=hidden id=txtcodcli".$li_i." value='".$ls_codcli."' class=sin-borde size=30 style= text-align:center readonly>";
				$la_objectconcepto[$li_i][4]="<input name=txtfecemi".$li_i." type=text id=txtfecemi".$li_i." value='".$ls_fecemi."' class=sin-borde size=10 style= text-align:left readonly>";
				$la_objectconcepto[$li_i][5]="<input name=txtmonto".$li_i." type=text id=txtmonto".$li_i." value='".$ls_monto."' class=sin-borde size=15 style= text-align:right readonly>";
				}
		} //for
		$li_filasconcepto=$li_i;
		$la_objectconcepto[$li_filasconcepto][1]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=1>";
		$la_objectconcepto[$li_filasconcepto][2]="<input name=txtnumcarta".$li_filasconcepto." type=text id=txtnumcarta".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][3]="<input name=txtnumfacasoc".$li_filasconcepto." type=text id=txtnumfacasoc".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly><input name=txtcodcli".$li_filasconcepto." type=hidden id=txtcodcli".$li_filasconcepto." class=sin-borde size=30 style= text-align:center readonly>";
		$la_objectconcepto[$li_filasconcepto][4]="<input name=txtfecemi".$li_filasconcepto." type=text id=txtfecemi".$li_filasconcepto." class=sin-borde size=10 style= text-align:left readonly>";
		$la_objectconcepto[$li_filasconcepto][5]="<input name=txtmonto".$li_filasconcepto." type=text id=txtmonto".$li_filasconcepto." class=sin-borde size=15 style= text-align:right readonly>";
	}
	else
	{
		$io_msg->message ("El Banco no tiene Cartas Ordenes por cobrar!!!");
		$ls_numcob     = "";
		$ls_codbanco   = "";
		$ls_nombanco   = "";
		$ls_feccar     = "";
		$ls_feccar2    = "";
		$ls_observacion= "";
		$la_codcaj     = "";
	    $ls_ctaban     = "";
	}
}
elseif($ls_operacion=="ue_guardar")
{
	/********************************   VERIFICA SI USUARIO ES PERSONAL DE LA TIENDA   *****************************************/
	$ls_codtie=$_SESSION["ls_codtienda"];
	if($ls_codtie!="")
	{
	   $ls_codusu=$_SESSION["la_logusr"];
	   $lb_valido_logusr=true;
	}
    else
	{
	   $lb_valido_logusr=false;
	   //mensaje
	   $io_msg->message ("El usuario no es personal de la tienda no puede generar cobros de facturas !!!");
	}
	/******************************************************************************************************************************/
	$ls_codcaj= "C01";
	$ls_estcob= "E";
											  
	$lb_existe=$io_cobranza->uf_select_cobro($ls_numcob,$ls_codtie);
		if ($lb_existe and $ls_operacion1!="ue_nuevo")
		{
		}
		elseif  ($ls_operacion1=="ue_nuevo")
		{
				$ls_estcob  = "E";
				$ls_codcaj  = $_SESSION["ls_codcaj"];
				$ls_prefijo = $_SESSION["ls_precob"];
				$ls_serie   = $_SESSION["ls_sercob"];
				$io_secuencia->uf_obtener_secuencia($ls_codcaj.$ls_codtie."cob",&$ls_secuencia);
				$ls_numcob  = $io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);
				if ($lb_existe and $ls_operacion1!="ue_nuevo")
				{
					$io_msg->message ("El Cobro con el codigo: ".$ls_numcob." fue actualizada satisfactoriamente");
				} elseif ($ls_operacion1=="ue_nuevo")
				{
					$ls_operacion1="";
					$io_msg->message ("El Cobro sera guardado con el codigo: ".$ls_numcob);
				}
		}
	    $lb_valido=$io_cobranza->uf_guardar_cobro($ls_codtie,$ls_codcli,$ls_numcob,$ls_observacion,$ls_feccob,$ls_moncob,$ls_codusu,
	                                              $ls_estcob,$ls_especial,$ls_numcarta,$ls_codcaj,$ls_codbanco,$la_seguridad);
		for ($li_i=1;$li_i<$li_filasconcepto;$li_i++)
		 {
			$ls_numcarta   =$_POST["txtnumcarta".$li_i];
			$ls_numfacasoc =$_POST["txtnumfacasoc".$li_i];
			$ls_codcli     =$_POST["txtcodcli".$li_i];
			$ls_fecemi     =$_POST["txtfecemi".$li_i];
			$ls_monto      =$_POST["txtmonto".$li_i];
			$ls_moncancel  =$_POST["txtmonto".$li_i];
			$ls_tipcancel  ='T';	   
			$ls_obsins     ="";
			$ls_codforpag  ='08';
			$ls_estcob     ='E';
			$ls_especial   ='F';
			 if ($_POST["chk".$li_i]=='')
			 {
					$ls_estacarta='0';
			 }
			 else
			 {
				   $ls_estacarta='1';
			 }
			 if ($ls_estacarta==1)
			 {
					if($lb_valido)
					{
							$lb_valido=$io_cobranza->uf_guardar_detcobro($ls_codtie,$ls_codcli,$ls_numcob,$ls_numcarta,$ls_tipcancel,
																   $ls_moncancel,$ls_monto,$ls_numfacasoc,$ls_estacarta,$ls_codbanco,$la_seguridad);
					}
					if($lb_valido)
					{									   
							$lb_validar=$io_cobranza->uf_guardar_detalles_instpagocob($ls_codtie,$ls_codcli,$ls_numcob,$ls_numinst,
							                                                             $ls_fecinst,$ls_obsins,$ls_codbanco,$ls_ctaban,
											 	                                         $ls_monto,$ls_codforpag,$ls_numcarta);
					}
			 }
			 else
			 {
				  $ls_moncancel=0;
				  $ls_tipcancel='n';
			 }
		 }//for
	 	 $ls_mensaje=$io_cobranza->io_msgc;
		 if ($lb_valido1==true)
		 {
				$io_msg->message ($ls_mensaje);
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
		 $ls_operacion1="ue_guardar";
	 	 print("<script language=JavaScript>");
		 print("pagina='sigesp_sfc_d_cobranzacartas.php';");
		 print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
		 print("</script>");		 
}
if ($ls_operacion=="ue_anular")
{
	 $ls_numcob = $_POST["txtnumcob2"];
	 $ls_codtie = $_SESSION["ls_codtienda"];
	 
	 $ls_sql="SELECT * 
	          FROM   sfc_cobrocartaorden 
	          WHERE  codemp='".$ls_codemp."' 
			  AND    numcob='".$ls_numcob."' 
			  AND    codciecaj <>'';";
	 $rs_datauni=$io_sql->select($ls_sql);
	 $la_cobro=$io_sql->obtener_datos($rs_datauni);
	 $io_datastore->data=$la_cobro;
	 $ls_estcob=$io_datastore->getValue("estcob",1);//�
 	 if ($ls_estcob=="A")
		{
			  $io_msg->message ("El Cobro ya esta ANULADO!");
		}
	 elseif ($ls_estcob=="P")
		{
			  $io_msg->message ("El Cobro PROCESADO sera ANULADO!");
			  $ls_estcob="";
		 }
	 elseif ($ls_estfaccon=="E")
		{
			  $io_msg->message ("El Cobro EMITIDO sera ANULADO!");
			  $ls_estcob="";
		}
	 else
		{
			  $ls_estcob="";
		}


	 if ($ls_estcob=="")
	   {
	    $ls_estcob="A";
		 $io_cobranza->uf_actualizar_cobrostatus($ls_numcob,'A',$ls_codbanco,$ls_codtie,$la_seguridad);
		 $ls_cadena="SELECT co.numcob,n.numnot,co.codcli,co.moncancel,n.fecnot,n.monto,co.numcartaorden,n.nro_documento,n.estnota
				 	 FROM   sfc_nota n,sfc_dt_cobrocartaorden co
				 	 WHERE  n.codemp  = co.codemp 
					 AND    n.numnot  = co.numcartaorden 
				 	 AND    co.numcob = '".$ls_numcob."'";					 
		 $rs_datauni1=$io_sql->select($ls_cadena);
		 $la_factura=$io_sql->obtener_datos($rs_datauni1);
 		 $io_datastore->data=$la_factura;
		 $totrow=$io_datastore->getRowCount("numcob");
		 $li_i2=0;
		 for($li_i=0;$li_i<$totrow;$li_i++)
		 {
			 $li_i2=$li_i2+1;
			 $ls_factura    = $io_datastore->getValue("numnot",$li_i+1);
			 $ls_numfacasoc = $io_datastore->getValue("nro_documento",$li_i+1);
			 $ls_montoanul  = $io_datastore->getValue("moncancel",$li_i+1);
				 if ($ls_factura==$ls_numfacasoc)
				 {
						 $io_cobranza->uf_actualizar_factura($ls_factura,'P',$la_seguridad);
						 $io_cobranza->uf_actualizar_nota($ls_codtie,$ls_factura,'P',$la_seguridad);
				 }
				 else
				 {
						 $io_cobranza->uf_actualizar_factura($ls_numfacasoc,'P',$la_seguridad);
						 $io_cobranza->uf_actualizar_nota($ls_codtie,$ls_factura,'P',$la_seguridad);
				 }
			 $ls_montototal[$li_i2]["moncancel"]=$ls_montoanul;
		 }//FOR
		 $ls_cadena="SELECT sfc_cobrocartaorden.numcob,sfc_dt_cobrocartaorden.moncancel
					 FROM   sfc_dt_cobrocartaorden,sfc_cobrocartaorden
					 WHERE  sfc_dt_cobrocartaorden.numcob=sfc_cobrocartaorden.numcob 
					 AND    sfc_dt_cobrocartaorden.codban=sfc_cobrocartaorden.codban 
					 AND    sfc_cobrocartaorden.numcob   ='".$ls_numcob."' 
					 AND    sfc_cobrocartaorden.estcob!='A'";
				$arr_montos=$io_sql->select($ls_cadena);
				if ($arr_montos==false && ($io_sql->message!=""))
				{
					$io_msg->message("!No hay Registros de Cobros Anteriores");
				}
				else{
					if ($row=$io_sql->fetch_row($arr_montos))
					{
						$la_montos=$io_sql->obtener_datos($arr_montos);
						$io_data->data=$la_montos;
						$ls_montopar2=$io_data->getValue("monto",1);
						$totrow2=$io_data->getRowCount("numcob");
						$ls_monto2=0;
						$ls_monto3=0;
						$li_i3=0;
						for ($li_i2=1;$li_i2<=$totrow2;$li_i2++)
						{
						$li_i3=$li_i3+1;
						$ls_numfactura = $io_data->getValue("numnot",$li_i2);
						$ls_monto3     = $io_data->getValue("moncancel",$li_i2);
						$ls_estatcob   = $io_data->getValue("estcob",$li_i2);
						$ls_tipo       = $io_data->getValue("tipcancel",$li_i2);
						$a_tipo[$li_i3]["tipcancel"]=$ls_tipo;
						$ls_montopar2  = $ls_montopar2-$ls_monto3;
						}
						if ($ls_montopar2==0){
						$ls_montopar2  = $io_data->getValue("monto",$li_i);
						}
						$ls_monto=number_format($ls_montopar2,2,',', '.');
						$ls_tipo=$a_tipo[$li_i3-1]["tipcancel"];
					if ($ls_monto!=0 and $ls_tipo=='T')
					{
						$io_cobranza->uf_actualizar_dfacturatatus($ls_numfactura,'P',$la_seguridad);
					}
					}else
					{
					if ($ls_tipocancel=='T' and $ls_montocancel==0 and $ls_estcob=='A')
					{
					$ls_monto='0,00';
					}else{
					$ls_monto=$ls_montopar;
					}
					}
				}
		  }
		  /*****************    ACTUALIZAR SALDOS PENDIENTES   *************************************************/
        $ls_suichemet=false;
		for ($li_i=1;$li_i<$li_filasfpago;$li_i++)
		 {
		   $ls_codforpag  = $_POST["txtcodforpag".$li_i];
		   $ls_metforpago = $_POST["txtmetforpag".$li_i]; //crear campo oculto "txtmetforpago" en "GRID fpago".
		   $ls_numinst    = $_POST["txtnuminst".$li_i];
		   if ($ls_metforpago=="D" and $ls_codforpag=='03')
		   {
		   		$ls_suichemet=true;
		   		break;
		   }
		 }
		if  ($ls_suichemet==true) //si pago con nota de cr�dito actualizarla a "C" cancelada
			{
				//****  actualizar "estnot" en nota de cr�dito a "C"(cancelada)  *****/
				$ls_estnot="P";
				$ls_numnot=$ls_numinst;//n�mero de nota de credito usada para pagar.
				$lb_valido=$io_cobranza->uf_update_actualizaestnot($ls_numnot,$ls_estnot);
			}
  }//fin procediiento "ue_anular"
?>

<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="533" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1 Estilo3">Sistema de Facturaci&oacute;n</span></td>
    <td width="245" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
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
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><div align="left"><a href="javascript:ue_nuevo();">
    <img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a>
    <a href="javascript:ue_guardar(<?php $li_i?>);"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a>
    <a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a>
    <a href="javascript:ue_eliminar();"></a><a href="javascript:ue_anular();"><img src="../shared/imagenes/editdelete.png" width="22" height="22" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
  </tr>
</table>
  <p>&nbsp;
  </p>
  <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
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
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
    <input name="hidestobr" type="hidden" id="hidestobr" value="<?php print $ls_estobr ?>">
    <input name="hidmonobr" type="hidden" id="hidstaobr" value="<?php print $ls_monobr ?>">
    <input name="hidmonasi" type="hidden" id="hidmonasi" value="<?php print $ls_monasi ?>">
    <input name="txtmontocancel" type="hidden" id="txtmontocancel">
    <input name="txttipocancel" type="hidden" id="txttipocancel">
    <input name="txtmontoforpag" type="hidden" id="txtmontoforpag">
    <table width="771" height="385" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
    <tr class="titulo-celdanew">
      <th colspan="12" scope="col" class="titulo-celdanew">Cobranza</th>
    </tr>

    <tr class="formato-blanco">
      <td  colspan="6"><input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion ?>">
          <input name="opemostrar" type="hidden" id="opemostrar" value="<?php print $ls_opemostrar ?>"></td>
    </tr>
    <tr class="formato-blanco">
      <td height="42"><input name="hidremoverconcepto2" type="hidden" id="hidremoverconcepto2"></td>
      <td><input name="hidcodalm" type="hidden" id="hidcodalm" value="<?php print $ls_codalm ?>">
		  <input name="estcob" type="hidden" id="estcob" value="<?php print $ls_estcob?>">
		  <input name="modificar" type="hidden" id="modificar" value="<?php print $ls_modificar?>">
          <input name="txtmoncar" type="hidden" id="txtmoncar"></td>
      <td width="270"><input name="hiddencodban" type="hidden" id="hiddencodban" value="<?php print $ls_codban ?>">
      <input name="hiddencodforpag" type="hidden" id="hiddencodforpag" value="<?php print $ls_codforpag ?>">	  	 
	  <input name="operacion1" type="hidden" id="operacion1" value="<?php print $ls_operacion1 ?>">	  </td>
    </tr>
    <tr class="formato-blanco">
      <td width="63" height="22"><div align="right"></div></td>
	  <?php
	 if ($ls_operacion1=="ue_guardar" or $ls_estcob=="E" or $ls_estcob=="A" or $ls_estcob=="P" or $ls_estcob=="C")
	 {
	 ?>
      <td width="107"><div align="right">N&ordm; Control </div></td>

	  <td><div align="left">
	    <input name="txtnumcob2" type="text" id="txtnumcob2" value="<?php print $ls_numcob ?>" size="28" maxlength="25" readonly>
	    <span class="titulo-cat&aacute;logo">
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
		}
		else
		{
		?>
		</div></td>
      <td width="35">
          <input name="txtnumcob" type="hidden" id="txtnumcob" value="<?php print $ls_numcob ?>" size="28" maxlength="25" readonly>
		  <span class="titulo-cat&aacute;logo">
       <?php
	   if ($ls_estcob=="")
		{
		?>
        <font color="#006600">&nbsp;</font>
      <?php
		}
		}
		 if ($ls_estcob=='E' or $ls_estcob=='' )
		 {
		 ?>		 </td>
		 <td></td>
      <td colspan="2">Fecha  <input name="txtfeccob" type="text" id="txtfeccob"  style="text-align:left" value="<?php print $ls_feccob ?>" size="11" maxlength="10"   onKeyPress="javascript:ue_validafecha();" readonly="true" datepicker="true"></td>
	  <?php
	  }else{
	  ?>
	  <td width="118" colspan="2">Fecha  
      <input name="txtfeccob" type="text" id="txtfeccob"  style="text-align:left" value="<?php print $ls_feccob ?>" size="11" maxlength="10"   onKeyPress="javascript:ue_validafecha();" readonly="true"></td>
	  <?php
	  }
	  ?>
    </tr>
    <tr class="formato-blanco">
      <td height="22">&nbsp;</td>
	  <td height="22" align="right">Unidad Operativa de Suministro </td>
	  <td colspan="4" ><input name="txtcodcaj" type="text" id="txtcodcaj" style="text-align:center " value="<?php print $la_codcaj?>" size="15" maxlength="15"  readonly="true">
	  <!-- javascript:ue_catusuario(); -->
	  <a href="javascript:ue_catcaja();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de tipos de unidades"></a>
	  <input name="txtnomcaj" type="text" id="txtnomcaj" class="sin-borde" size="40" readonly="true" value="<?php print $la_nomcaj?>" ></td>
	</tr>
	<tr class="formato-blanco">
      	<td height="22">&nbsp;</td>
		<td align="right">Fecha de Factura</td>
		<td colspan="2"><div><input name="txtfeccar" type="text" id="txtfeccar"  style="text-align:left" value="<?php print $ls_feccar ?>" size="11" maxlength="10"   onKeyPress="javascript:ue_validafecha();" readonly="true" datepicker="true">
		&nbsp;&nbsp;&nbsp;Hasta&nbsp;
		    <input name="txtfeccar2" type="text" id="txtfeccar2"  style="text-align:left" value="<?php print $ls_feccar2 ?>" size="11" maxlength="10"   onKeyPress="javascript:ue_validafecha();" readonly="true" datepicker="true">
		</div>		</td>
	</tr>
	 <tr class="formato-blanco">
	   <td height="22">&nbsp;</td>
	   <td height="22"><div align="right">Banco</div></td>
	   <td colspan="4"><input name="txtcodbanco" type="text" id="txtcodbanco" style="text-align:center " value="<?php print $ls_codbanco?>" size="4" maxlength="4" readonly="true">
         <a href="javascript:ue_catbanco();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
         <input name="txtnombanco" type="text" id="txtnombanco"  style="text-align:left" class="sin-borde" value="<?php print $ls_nombanco ?>" size="70" maxlength="100" readonly="true"></td>
      </tr>
	 <tr class="formato-blanco">
      <td height="22">&nbsp;</td>
      <td height="22"><div align="right">Cuenta</div></td>
      <td colspan="4"><p>
        <input name="txtctaban" type="text" id="txtctaban"  style="text-align:left" class="formato-blanco" value="<?php print $ls_ctaban ?>" size="50" maxlength="25" readonly="true">
       </p>
       </td>
	</tr>
	</tr>
	<tr class="formato-blanco">
	 <td width="63" height="26"><div align="right"></div></td>
	<td width="107"><div align="right">N&ordm;  Nota</div></td>
	  <td><div align="left">
          <input name="txtnuminst" type="text" id="txtnuminst" value="<?php print $ls_numinst ?>" size="28" maxlength="25">&nbsp;&nbsp;
      Fecha del Documento  <input name="txtfecinst" type="text" id="txtfecinst"  style="text-align:left" value="<?php print $ls_fecinst ?>" size="11" maxlength="10"   onKeyPress="javascript:ue_validafecha();" readonly="true" datepicker="true"></div></td>
      </tr>
     <tr class="formato-blanco">
      <td colspan="6">
	  <table width="609" border="0" align="center" cellpadding="0" cellspacing="4">
       	<tr align="center" class="formato-blanco">
			  <td><?php $io_grid->makegrid($li_filasconcepto,$la_columconcepto,$la_objectconcepto,$li_anchoconcepto,$ls_tituloconcepto,$ls_nametable);?></td>
			  <input name="filasconcepto" type="hidden" id="filasconcepto" value="<?php print $li_filasconcepto;?>">
			  <input name="hidremoverconcepto" type="hidden" id="hidremoverconcepto" value="">
		  </tr>
		</table>	  </td>
      <tr class="formato-blanco">
      <td><div align="center"></div></td>
     <td height="23">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>

      <td><div align="right">Monto a Cancelar
          <input name="txtmoncob" type="text" id="txtmoncob"  style="text-align: right" value="<?php print $ls_moncob ?>" size="20" maxlength="20"  readonly="true">
      </div></td>
	   <td>&nbsp;</td>
	  </tr>
    </tr>
	 <tr class="formato-blanco">
	 <td><div align="center"></div></td>
     <td height="23">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width="118">&nbsp;</td>
    <td width="57">&nbsp;</td>
  </tr>
  <tr class="formato-blanco">
    <td colspan="6">&nbsp;</td>
  </tr>
  </table>
<input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_chk ?>">
  <div align="center"></div>
  </form>
</body>
<script language="javascript">
function ue_buscar()
{
  	f=document.form1;

	li_leer=f.leer.value;
	if(li_leer==1)
	{
		f.operacion.value="";
	    pagina="sigesp_cat_cobranzacarta.php";
	    popupWin(pagina,"catalogo",850,450);
	}else{
		alert("No tiene permiso para realizar esta operacion");
	}

}
function ue_cargarfpago(numinst,monto,denforpag,nombanco,codban,codforpag,fecins,obsins,metforpag) //x
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
		eval("f.txtnombanco"+f.txtfilasfpago.value+".value='"+nombanco+"'");
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
function ue_catbanco()
{
	f=document.form1;
	if (f.txtfeccar.value=="" || f.txtfeccar2.value=="")
		alert ("Debe indicar la fecha de Emision de la Facturas a Cobrar");
	else
	 {
	 	f.operacion.value="";
		pagina="sigesp_cat_banco.php";
    	popupWin(pagina,"catalogo",850,450);
	 }
}
function ue_cargarcobranza(numcob,feccob,estcob,fecins,codban,nomban,numinst,codtien,ctaban)
{
	f=document.form1;
	f.txtnumcob.value=numcob;
	f.txtcodbanco.value=codban;
	f.txtnuminst.value=numinst;
	f.txtfecinst.value=fecins;
	f.txtfeccob.value=feccob;
	f.estcob.value=estcob;
	f.txtcodcaj.value=codtien;
	f.txtctaban.value=ctaban;
	f.operacion.value="ue_cargarcobros";
	f.operacion1.value="ue_cargarcobros";
	f.action="sigesp_sfc_d_cobranzacartas.php";
	f.submit();
}
function ue_cargarbanco(codbanco,nombanco,ctaban)
{
	f=document.form1;
	f.txtcodbanco.value=codbanco;
	f.txtnombanco.value=nombanco;
	f.txtctaban.value=ctaban;
	f.operacion.value="ue_cargarcartas_banco";
	f.operacion1.value="ue_nuevo";
	f.action="sigesp_sfc_d_cobranzacartas.php";
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
		if (lb_status!="C")
		{
			f.hidstatus.value="C";
		}

		with(f)
		{
		if (f.estcob.value=="A")
		{
		   alert("Cobro ANULADO no puede ser  MODIFICADO!!");
	  		}

		if (ue_valida_null(txtfeccob,"Fecha del Cobro")==false)
			 {
				  txtfeccob.focus();
			 }
			 else if (ue_valida_null(txtnumcob,"No. de Cobro")==false)
			 {
				  txtnumcob.focus();
			 }
			else if (ue_valida_null(txtnuminst,"Nro de Nota")==false)
			 {
				 txtnuminst.focus();
			 }
			 else if (ue_valida_null(txtfecinst,"Fecha del Documento")==false)
			 {
				txtfecinst.focus();
			 }else if (f.txtmoncob.value=='0,00'){
			 alert ('Debe seleccionar al menos una de las Cartas Ordenes a ser Canceladas');
			 }
			 else
			 {
			   if (f.estcob.value=="P")
	 		  {
	  			alert("Cobro PROCESADO no puede ser  MODIFICADO!!");
	  			}
			   else{
					f.operacion.value="ue_guardar";
					f.action="sigesp_sfc_d_cobranzacartas.php";
					f.submit();
				}
			 }
		}
	}
	else
	{
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
		//f.txtnumcob.value="";
		f.txtcodbanco.value="";
		f.txtnombanco.value="";
		f.txtnuminst.value="";
		f.txtfeccob.value="";
		f.txtfecinst.value="";
		f.txtmoncob.value="0,00";
	    f.filasconcepto.value="";
		f.hidremoverconcepto.value="";
		f.action="sigesp_sfc_d_cobranzacartas.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
function ue_subtotal()
{
 	f=document.form1;
	li_filasconcepto=f.filasconcepto.value;
	ld_subtotal=0;
	ld_subtotal2=0;
	ld_porpagar=0;
	ld_porpagar2=0;
	ld_iva=0;
	ld_monto=0;
	ls_cero="0,00";
	estatus=0;
	for(li_i=1;li_i<=li_filasconcepto-1;li_i++)
	{
	  if (eval("f.chk"+li_i+".checked")==true)
		  {
			 ld_monto=parseFloat(uf_convertir_monto(eval("f.txtmonto"+li_i+".value")));
   			 ld_subtotal=ld_subtotal+ld_monto;
			 f.txtmoncob.value=uf_convertir(roundNumber(ld_subtotal));
		 }
		  if (eval("f.chk"+li_i+".checked")!=true)
        {
		 ld_monto=parseFloat(uf_convertir_monto(eval("f.txtmonto"+li_i+".value")));
		 ld_subtotal2=ld_subtotal2+ld_monto;
		 if (ld_subtotal==0)
			{
			  f.txtmoncob.value=uf_convertir(roundNumber(ld_subtotal));
			 }
		 }
	}//for
}
function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
function ue_anular()
{
	f=document.form1;
	ls_anular=f.anular.value;
	if(ls_anular==1){
		if (f.txtnumcob2.value=="")
		{
			alert("Debe seleccionar El Cobro a anular!!");
		}
		else
		{
			if (f.estcob.value=="A")
			{
				alert("El Cobro ya fue Anulado!!");
			}else{
				si=confirm("Esta seguro de Anular este Cobro?");
				if(si)
				{
					f.action="sigesp_sfc_d_cobranzacartas.php";
					f.operacion.value="ue_anular";
					f.submit();
				}
			}
	  	}
	}else{
		alert("No tiene permiso para realizar esta operacion");
	}
}
function ue_cargarcaja(codcaja,desccaja,precot,prefac,predev,preped,sercot,serfac,serdev,serped,sernot,sercon,formalibre,precob,sercob)
{
	f=document.form1;
	f.txtcodcaj.value = codcaja;
	f.txtnomcaj.value = desccaja;

}
function ue_catcaja(){
	pagina="sigesp_cat_tienda_caja.php";
	popupWin(pagina,"catalogo",650,300);
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>