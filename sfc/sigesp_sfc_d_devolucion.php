<?php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "location.href='sigesp_conexion.php'";
	print "</script>";
}
$la_datemp=$_SESSION["la_empresa"];
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
<script language="javascript">
/*document.onkeydown = function(){
if(window.event && window.event.keyCode == 116)
{
window.event.keyCode = 505;
}
if(window.event && window.event.keyCode == 505)
{
return false;
}
}*/


</script>



<title>Devoluci&oacute;n</title>
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
.Estilo3 {
	color: #6699CC;
	font-size: 14px;
}
-->
</style><meta http-equiv="Content-Type" content="text/html; charset="></head>
<body link="#006699" vlink="#006699" alink="#006699"  >
<?Php
//********************************************         SEGURIDAD       ****************************************************
require_once("../shared/class_folder/sigesp_c_seguridad.php");


$io_seguridad= new sigesp_c_seguridad();

$arre=$_SESSION["la_empresa"];
$ls_empresa=$arre["codemp"];
$ls_logusr=$_SESSION["la_logusr"];
$ls_codtienda=$_SESSION["ls_codtienda"];
//print $ls_codtienda;

$ls_sistema="SFC";
$ls_ventanas="sigesp_sfc_d_devolucion.php";

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

/************************************************************************************************************************/
/********************************   LIBRERIAS   *************************************************************************/
/************************************************************************************************************************/
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
require_once("class_folder/sigesp_sfc_c_devolucion.php");
require_once("../shared/class_folder/evaluate_formula.php");
require_once("class_folder/sigesp_sfc_c_secuencia.php");
require_once("class_folder/sigesp_sfc_c_nota.php");

$io_evalform=new evaluate_formula();
$io_secuencia=new sigesp_sfc_c_secuencia();
$io_secuencia2=new sigesp_sfc_c_secuencia();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_sql=new class_sql($io_connect);
$io_funcdb=new class_funciones_db($io_connect);

$io_funcsob=new sigesp_sob_c_funciones_sob();
$io_evalform=new evaluate_formula();
$io_grid=new grid_param();
$io_msg=new class_mensajes();
$io_datastore=new class_datastore();
$io_datastoreest=new class_datastore();
$io_function=new class_funciones();

$io_cotizacion=new  sigesp_sfc_c_cotizacion();
$io_factura=new  sigesp_sfc_c_factura();
$io_instpago=new sigesp_sfc_c_instrpago();
$io_instpagocob=new sigesp_sfc_c_instpagocob();
$io_cobranza=new  sigesp_sfc_c_cobranza();
$io_devolucion=new  sigesp_sfc_c_devolucion();
$io_nota=new sigesp_sfc_c_nota();

$ls_codemp=$la_datemp["codemp"];


require_once("class_folder/sigesp_sim_c_articuloxalmacen.php");
$io_art=  new sigesp_sim_c_articuloxalmacen();
require_once("class_folder/sigesp_sim_c_movimientoinventario.php");
$io_mov=    new sigesp_sim_c_movimientoinventario();
require_once("class_folder/sigesp_sim_c_recepcion.php");
$io_siv=  new sigesp_sim_c_recepcion();
require_once("class_folder/sigesp_sim_c_despacho.php");
$io_sivDes=  new sigesp_sim_c_despacho();



/**************   GRID   DETALLES   FACTURA   *******************/
$ls_titulodevolucion="Detalle Art&iacute;culos";
$li_anchodevolucion=600;
$ls_nametable="grid2";
$la_columdevolucion[1]="C&oacute;d. Producto";
$la_columdevolucion[2]="Descripci&oacute;n";
$la_columdevolucion[3]="Precio";
$la_columdevolucion[4]="IVA";
$la_columdevolucion[5]="Cant anterior";
$la_columdevolucion[6]="Cant a devolver";
$la_columdevolucion[7]="Cod. Almacen";
$la_columdevolucion[8]="Proveedor";
$la_columdevolucion[9]="Cond. Art&iacute;culo";


/**************************************************************************************************************************/
/**************************    SUBMIT   ***********************************************************************************/
/**************************************************************************************************************************/
if(array_key_exists("operacion",$_POST))
{

	$ls_operacion=$_POST["operacion"];
	$ls_codtie=$_POST["txtcodtienda"];
	$ls_tienda=$_POST["txttienda"];
	$ls_codcli=$_POST["txtcodcli"];
	$ls_estdev=$_POST["txtestdev"];
	$ls_nomcli=$_POST["txtnomcli"];
	$ls_numfac=$_POST["txtnumfac"];
	$ls_fecemi=$_POST["txtfecemi"];
	$ls_mondev=$_POST["txtsubtot"];
	$ls_observacion=$_POST["txtobservacion"];
	$ls_codusu=$_SESSION["la_logusr"];
	$ls_moniva=$_POST["txtmoniva"];
	$ls_monto=$_POST["txtmonto"];
	$li_filasdevolucion=$_POST["filasdevolucion"];
    $li_removerdevolucion=$_POST["hidremoverdevolucion"];
	$ls_coddev=$_POST["txtcoddev"];
	$ls_numcon=$_POST["txtnumcont"];
	$ls_hidval=$_POST["hidval"];
	$ls_hidstatus=$_POST["hidstatus"];

}
/************************************************************************************************************************/
/********************************************** NO SUBMIT ***************************************************************/
/************************************************************************************************************************/
else
{
  // print "NOSUMIT/";
    $ls_operacion="";
    $ls_codtie="";
    $ls_tienda="";
	$ls_numfac="";
	$ls_fecemi="";
	$ls_fecemi=date('d/m/Y');
	$ls_codcli="";
	$ls_estdev="";
	$ls_nomcli="";
	$ls_observacion="";
	$ls_coddev="";
	$ls_mondev="0,00";
	$ls_moniva="0,00";
	$ls_monto="0,00";
	$ls_moncosdev="0,00";
	$ls_moncosiva="0,00";
	$ls_montocos="0,00";
	$ls_hidval="";
	$ls_numcon="";


	$li_filasdevolucion=1;
    $la_objectdevolucion[$li_filasdevolucion][1]="<input name=txtcodpro".$li_filasdevolucion." type=text id=txtcodpro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectdevolucion[$li_filasdevolucion][2]="<input name=txtdenpro".$li_filasdevolucion." type=text id=txtdenpro".$li_filasdevolucion." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectdevolucion[$li_filasdevolucion][3]="<input name=txtprepro".$li_filasdevolucion." type=text id=txtprepro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left ><input name=txtcospro".$li_filasdevolucion." type=hidden id=txtcospro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left readonly>";
	$la_objectdevolucion[$li_filasdevolucion][4]="<input name=txtporcar".$li_filasdevolucion." type=text id=txtporcar".$li_filasdevolucion." class=sin-borde size=8 style= text-align:center readonly>";
	$la_objectdevolucion[$li_filasdevolucion][5]="<input name=txtcanpro".$li_filasdevolucion." type=text id=txtcanpro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left readonly>";
	$la_objectdevolucion[$li_filasdevolucion][6]="<input name=txtcandev".$li_filasdevolucion." type=text id=txtcandev".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center>";

	$la_objectdevolucion[$li_filasdevolucion][7]="<input name=txtcodalm".$li_filasdevolucion." id=txtcodalm".$li_filasdevolucion." type=text class=sin-borde size=15 style= text-align:left readonly>";
	$la_objectdevolucion[$li_filasdevolucion][8]="<input name=txtproveedor".$li_filasdevolucion." type=text id=txtproveedor".$li_filasdevolucion." class=sin-borde size=25 style= text-align:center readonly>";
	$la_objectdevolucion[$li_filasdevolucion][9]="<input type='checkbox' name=chkreversar".$li_filasdevolucion." class= sin-borde value=1>";


}


/**************************************************************************************************************************/
/***************** Inicalizaci�n para devolucion(cargar productos en grid) **************************************************/
/**************************************************************************************************************************/
	if ($ls_operacion != "ue_cargardevolucion" && $ls_operacion != "ue_removerdevolucion")
	{

		for($li_i=1;$li_i<$li_filasdevolucion;$li_i++)
		{
		$ls_codpro=$_POST["txtcodpro".$li_i];
		$ls_denpro=$_POST["txtdenpro".$li_i];
		$ls_prepro=$_POST["txtprepro".$li_i];
		$ls_cospro=$_POST["txtcospro".$li_i];
		$ls_canpro=$_POST["txtcanpro".$li_i];
		$ls_candev=$_POST["txtcandev".$li_i];
		$ls_porcar=$_POST["txtporcar".$li_i];

		$ls_codalm=$_POST["txtcodalm".$li_i];
		$ls_codproveedor=$_POST["txtcodproveedor".$li_i];
		$ls_proveedor=$_POST["txtproveedor".$li_i];
		$ls_condmerc=$_POST["chkreversar".$li_i];


		$la_objectdevolucion[$li_i][1]="<input name=txtcodpro".$li_i." type=text id=txtcodpro".$li_i." value='".$ls_codpro."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectdevolucion[$li_i][2]="<input name=txtdenpro".$li_i." type=text id=txtdenpro".$li_i." value='".$ls_denpro."' class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectdevolucion[$li_i][3]="<input name=txtprepro".$li_i." type=text id=txtprepro".$li_i." value='".$ls_prepro."' class=sin-borde size=15 style= text-align:left ><input name=txtcospro".$li_i." type=hidden id=txtcospro".$li_i." value='".$ls_cospro."' class=sin-borde size=15 style= text-align:left readonly>";
		$la_objectdevolucion[$li_i][4]="<input name=txtporcar".$li_i." type=text id=txtporcar".$li_i." value='".$ls_porcar."' class=sin-borde size=8 style= text-align:center readonly>";
		$la_objectdevolucion[$li_i][5]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectdevolucion[$li_i][6]="<input name=txtcandev".$li_i." type=text id=txtcandev".$li_i." value='".$ls_candev."' class=sin-borde size=15 style= text-align:center onblur=ue_subtotal(); onKeyPress=return(currencyFormat(this,'.',',',event,".$li_i."))>";

		$la_objectdevolucion[$li_i][7]="<input name=txtcodalm".$li_i." type=text id=txtcodalm".$li_i." value='".$ls_codalm."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectdevolucion[$li_i][8]="<input name=txtproveedor".$li_i." type=text id=txtproveedor".$li_i." value='".$ls_proveedor."' class=sin-borde size=15 style= text-align:center >  <input name=txtcodproveedor".$li_i." type=text id=txtcodproveedor".$li_i." value='".$ls_codproveedor."' class=sin-borde size=15 style= text-align:center >";
		$la_objectdevolucion[$li_i][9]="<input type='checkbox' name=chkreversar".$li_i." class= sin-borde value=1>";

		}

	$la_objectdevolucion[$li_filasdevolucion][1]="<input name=txtcodpro".$li_filasdevolucion." type=text id=txtcodpro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectdevolucion[$li_filasdevolucion][2]="<input name=txtdenpro".$li_filasdevolucion." type=text id=txtdenpro".$li_filasdevolucion." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectdevolucion[$li_filasdevolucion][3]="<input name=txtprepro".$li_filasdevolucion." type=text id=txtprepro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left ><input name=txtcospro".$li_filasdevolucion." type=hidden id=txtcospro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left readonly>";
	$la_objectdevolucion[$li_filasdevolucion][4]="<input name=txtporcar".$li_filasdevolucion." type=text id=txtporcar".$li_filasdevolucion." class=sin-borde size=8 style= text-align:center readonly>";
	$la_objectdevolucion[$li_filasdevolucion][5]="<input name=txtcanpro".$li_filasdevolucion." type=text id=txtcanpro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left readonly>";
	$la_objectdevolucion[$li_filasdevolucion][6]="<input name=txtcandev".$li_filasdevolucion." type=text id=txtcandev".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center>";

	$la_objectdevolucion[$li_filasdevolucion][7]="<input name=txtcodalm".$li_filasdevolucion." id=txtcodalm".$li_filasdevolucion." type=text class=sin-borde size=15 style= text-align:left readonly>";
	$la_objectdevolucion[$li_filasdevolucion][8]="<input name=txtproveedor".$li_filasdevolucion." type=text id=txtproveedor".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center readonly> <input name=txtcodproveedor".$li_filasdevolucion." type=text id=txtcodproveedor".$li_filasdevolucion." value='".$ls_codproveedor."' class=sin-borde size=15 style= text-align:center >";
	$la_objectdevolucion[$li_filasdevolucion][9]="<input type='checkbox' name=chkreversar".$li_filasdevolucion." class= sin-borde value=1>";
	}

/**************************************************************************************************************************/
/*********************** PREPARANDO INSERCION DE "NUEVA FACTURA" **********************************************************/
/**************************************************************************************************************************/
if($ls_operacion=="ue_nuevo")
{
    $la_empresa=$_SESSION["la_empresa"];
	$ls_codcaj=strtolower($_SESSION["ls_codcaj"]);
	$ls_prefijo=$_SESSION["ls_predev"];
	$ls_serie=$_SESSION["ls_serdev"];
	$ls_serie=$_SESSION["ls_serdev"];
    $ls_codtie =$_SESSION["ls_codtienda"];

	$io_secuencia->uf_ver_secuencia($ls_codcaj.$ls_codtie."dev",&$ls_secuencia);

	$ls_secuencia++;

	$ls_secuencia=$io_function->uf_cerosizquierda($ls_secuencia,16);
	$ls_coddev=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);

	$ls_fecemi="";


	if ($_SESSION["ls_formalibre"]=='S'){

	$ls_sercon=$_SESSION["ls_sercon"];

	$io_secuencia2->uf_ver_secuencia($ls_codcaj.$ls_codtie."con",&$ls_seccon);
	$ls_seccon++;

	$ls_seccon=$io_function->uf_cerosizquierda($ls_seccon,16);
	$ls_numcon=$io_secuencia2->uf_crear_codigo("",$ls_sercon,$ls_seccon);

	$estado="";
	$editar="";

	$_SESSION["ls_numcon"]=$ls_numcon;
	}
	else{
		$ls_numcon="";

		$estado="disabled";
	}
	$ls_codtie="";
	$ls_tienda="";
	$ls_numcot="0000000000000000000000000";
	$ls_codcli="";
	$ls_estdev="";
	$ls_nomcli="";
	$ls_mondev="0,00";
	$ls_moniva="0,00";
	$ls_monto="0,00";
	$ls_moncosdev="0,00";
	$ls_moncosiva="0,00";
	$ls_montocos="0,00";
	$ls_fecemi=date('d/m/Y');

	$li_filasdevolucion=1;
   	$la_objectdevolucion[$li_filasdevolucion][1]="<input name=txtcodpro".$li_filasdevolucion." type=text id=txtcodpro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectdevolucion[$li_filasdevolucion][2]="<input name=txtdenpro".$li_filasdevolucion." type=text id=txtdenpro".$li_filasdevolucion." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectdevolucion[$li_filasdevolucion][3]="<input name=txtprepro".$li_filasdevolucion." type=text id=txtprepro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left><input name=txtcospro".$li_filasdevolucion." type=hidden id=txtcospro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left readonly>";
	$la_objectdevolucion[$li_filasdevolucion][4]="<input name=txtporcar".$li_filasdevolucion." type=text id=txtporcar".$li_filasdevolucion." class=sin-borde size=8 style= text-align:center readonly>";
	$la_objectdevolucion[$li_filasdevolucion][5]="<input name=txtcanpro".$li_filasdevolucion." type=text id=txtcanpro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left readonly>";
	$la_objectdevolucion[$li_filasdevolucion][6]="<input name=txtcandev".$li_filasdevolucion." type=text id=txtcandev".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center>";

	$la_objectdevolucion[$li_filasdevolucion][7]="<input name=txtcodalm".$li_filasdevolucion." id=txtcodalm".$li_filasdevolucion." type=text class=sin-borde size=15 style= text-align:left readonly>";
	$la_objectdevolucion[$li_filasdevolucion][8]="<input name=txtproveedor".$li_filasdevolucion." type=text id=txtproveedor".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center readonly> <input name=txtcodproveedor".$li_filasdevolucion." type=text id=txtcodproveedor".$li_filasdevolucion." value='".$ls_codproveedor."' class=sin-borde size=15 style= text-align:center >";
	$la_objectdevolucion[$li_filasdevolucion][9]="";

}
/**************************************************************************************************************************/
/******************************************* CARGAR UN PRODUCTO EN GRID ***************************************************/
/**************************************************************************************************************************/
elseif($ls_operacion=="ue_cargardevolucion")
{
  // print "ue_cargardevolucion/";
	$li_filasdevolucion=1;
   	$la_objectdevolucion[$li_filasdevolucion][1]="<input name=txtcodpro".$li_filasdevolucion." type=text id=txtcodpro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectdevolucion[$li_filasdevolucion][2]="<input name=txtdenpro".$li_filasdevolucion." type=text id=txtdenpro".$li_filasdevolucion." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectdevolucion[$li_filasdevolucion][3]="<input name=txtprepro".$li_filasdevolucion." type=text id=txtprepro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left><input name=txtcospro".$li_filasdevolucion." type=hidden id=txtcospro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left readonly>";
	$la_objectdevolucion[$li_filasdevolucion][4]="<input name=txtporcar".$li_filasdevolucion." type=text id=txtporcar".$li_filasdevolucion." class=sin-borde size=8 style= text-align:center readonly>";
	$la_objectdevolucion[$li_filasdevolucion][5]="<input name=txtcanpro".$li_filasdevolucion." type=text id=txtcanpro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left readonly>";
	$la_objectdevolucion[$li_filasdevolucion][6]="<input name=txtcandev".$li_filasdevolucion." type=text id=txtcandev".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center>";

	$la_objectdevolucion[$li_filasdevolucion][7]="<input name=txtcodalm".$li_filasdevolucion." id=txtcodalm".$li_filasdevolucion." type=text class=sin-borde size=15 style= text-align:left readonly>";
	$la_objectdevolucion[$li_filasdevolucion][8]="<input name=txtproveedor".$li_filasdevolucion." type=text id=txtproveedor".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center readonly> <input name=txtcodproveedor".$li_filasdevolucion." type=text id=txtcodproveedor".$li_filasdevolucion." value='".$ls_codproveedor."' class=sin-borde size=15 style= text-align:center >";
	$la_objectdevolucion[$li_filasdevolucion][9]="<input type='checkbox' name=chkreversar".$li_filasdevolucion." class= sin-borde value=1>";

/********************************************************************************************************************************/

/*$ls_cadena="SELECT dd.*,a.denart,df.canpro,df.codalm, rpc_proveedor.cod_pro,rpc_proveedor.nompro as proveedor ".
            "FROM  sfc_detfactura df,sfc_detdevolucion dd, sfc_devolucion d,sfc_producto p, rpc_proveedor, sim_articulo a ".
			"WHERE dd.coddev=d.coddev AND dd.codart=p.codart AND d.numfac=df.numfac AND df.codart=dd.codart AND dd.coddev='".$ls_coddev."' AND p.codart=a.codart AND df.cod_pro=rpc_proveedor.cod_pro;";
*/
$ls_cadena="SELECT dd.*,df.canpro,df.costo,rpc_proveedor.nompro as proveedor,a.denart
from sfc_detdevolucion dd,sfc_detfactura df,rpc_proveedor, sim_articulo a
where dd.coddev='".$ls_coddev."' AND dd.codemp=df.codemp and dd.codart=df.codart and dd.codalm=df.codalm
and dd.codtiend=df.codtiend AND dd.codart=a.codart AND dd.codemp=a.codemp and df.codemp=a.codemp and df.codart=a.codart
AND df.numfac='".$ls_numfac."' AND df.cod_pro=rpc_proveedor.cod_pro and df.codemp=rpc_proveedor.codemp ";



//print $ls_cadena;

			$arr_detdevolucion=$io_sql->select($ls_cadena);

			if($arr_detdevolucion==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros de devoluciones");
			}
			else
			{
				if($row=$io_sql->fetch_row($arr_detdevolucion))

 				  {
					$la_devolucion=$io_sql->obtener_datos($arr_detdevolucion);
					$io_datastore->data=$la_devolucion;
					$totrow=$io_datastore->getRowCount("coddev");
$ls_mondev=0;
$ls_moniva=0;
$ls_moncosdev=0;
$ls_moncosiva=0;
$ls_montocos=0;
/********************************************************************************************************************************/


					for($li_i=1;$li_i<=$totrow;$li_i++)
					{
						$ls_codpro=$io_datastore->getValue("codart",$li_i);
		                $ls_denpro=$io_datastore->getValue("denart",$li_i);
						$ls_prepro=$io_datastore->getValue("precio",$li_i);
						$ls_cospro=$io_datastore->getValue("costo",$li_i);
						$ls_canpro=$io_datastore->getValue("canpro",$li_i);
						$ls_candev=$io_datastore->getValue("candev",$li_i);
						$ls_porcar=$io_datastore->getValue("porimp",$li_i);

						$ls_codalm=$io_datastore->getValue("codalm",$li_i);
						$ls_proveedor=$io_datastore->getValue("proveedor",$li_i);
						$ls_codproveedor=$io_datastore->getValue("cod_pro",$li_i);
						$ls_condmerc=$io_datastore->getValue("condmerc",$li_i);


						$ls_mondev=$ls_mondev + ($ls_prepro*$ls_candev);
						$ls_moniva=$ls_moniva+ ($ls_prepro*$ls_porcar/100);

						$ls_moncosdev=$ls_moncosdev + ($ls_cospro*$ls_candev);
						$ls_moncosiva=$ls_moncosiva+ ($ls_cospro*$ls_porcar/100);

						$ls_prepro=number_format($ls_prepro,2, ',', '.');
						$ls_cospro=number_format($ls_cospro,2, ',', '.');

						$ls_porcar=number_format($ls_porcar,2, ',', '.');
						$ls_canpro=number_format($ls_canpro,2, ',', '.');
						$ls_candev=number_format($ls_candev,2, ',', '.');

		                $la_objectdevolucion[$li_i][1]="<input name=txtcodpro".$li_i." type=text id=txtcodpro".$li_i." value='".$ls_codpro."' class=sin-borde size=15 style= text-align:center readonly>";
		                $la_objectdevolucion[$li_i][2]="<input name=txtdenpro".$li_i." type=text id=txtdenpro".$li_i." value='".$ls_denpro."' class=sin-borde size=45 style= text-align:left readonly>";
		                $la_objectdevolucion[$li_i][3]="<input name=txtprepro".$li_i." type=text id=txtprepro".$li_i." value='".$ls_prepro."' class=sin-borde size=15 style= text-align:left ><input name=txtcospro".$li_i." type=hidden id=txtcospro".$li_i." value='".$ls_cospro."' class=sin-borde size=15 style= text-align:left readonly>";
		                $la_objectdevolucion[$li_i][4]="<input name=txtporcar".$li_i." type=text id=txtporcar".$li_i." value='".$ls_porcar."' class=sin-borde size=8 style= text-align:center readonly>";
		                $la_objectdevolucion[$li_i][5]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=15 style= text-align:center readonly>";
		                $la_objectdevolucion[$li_i][6]="<input name=txtcandev".$li_i." type=text id=txtcandev".$li_i." value='".$ls_candev."' class=sin-borde size=15 style= text-align:center onchange=ue_subtotal(); onKeyPress=return(currencyFormat(this,'.',',',event,".$li_i."))>";

						$la_objectdevolucion[$li_i][7]="<input name=txtcodalm".$li_i." type=text id=txtcodalm".$li_i." value='".$ls_codalm."' class=sin-borde size=15 style= text-align:center readonly>";
						$la_objectdevolucion[$li_i][8]="<input name=txtproveedor".$li_i." type=text id=txtproveedor".$li_i." value='".$ls_proveedor."' class=sin-borde size=15 style= text-align:center > <input name=txtcodproveedor".$li_i." type=text id=txtcodproveedor".$li_i." value='".$ls_codproveedor."' class=sin-borde size=15 style= text-align:center >";
						if($ls_condmerc=='V')
						{	$la_objectdevolucion[$li_i][9]="<input type='checkbox' name=chkreversar".$li_i." checked=checked class= sin-borde value=0>";

						}else
							$la_objectdevolucion[$li_i][9]="<input type='checkbox' name=chkreversar".$li_i." class= sin-borde value=1>";
					}

					$ls_mondev=number_format($ls_mondev,2, ',', '.');

					$ls_moniva=number_format($ls_moniva,2, ',', '.');
					$ls_moncosdev=number_format($ls_moncosdev,2, ',', '.');

					$ls_moncosiva=number_format($ls_moncosiva,2, ',', '.');

					$li_filasdevolucion=$li_i;
					$la_objectdevolucion[$li_filasdevolucion][1]="<input name=txtcodpro".$li_filasdevolucion." type=text id=txtcodpro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center readonly>";
	                $la_objectdevolucion[$li_filasdevolucion][2]="<input name=txtdenpro".$li_filasdevolucion." type=text id=txtdenpro".$li_filasdevolucion." class=sin-borde size=45 style= text-align:left readonly>";
	                $la_objectdevolucion[$li_filasdevolucion][3]="<input name=txtprepro".$li_filasdevolucion." type=text id=txtprepro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left ><input name=txtcospro".$li_filasdevolucion." type=hidden id=txtcospro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left readonly>";
	                $la_objectdevolucion[$li_filasdevolucion][4]="<input name=txtporcar".$li_filasdevolucion." type=text id=txtporcar".$li_filasdevolucion." class=sin-borde size=8 style= text-align:center readonly>";
	                $la_objectdevolucion[$li_filasdevolucion][5]="<input name=txtcanpro".$li_filasdevolucion." type=text id=txtcanpro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left readonly>";
	                $la_objectdevolucion[$li_filasdevolucion][6]="<input name=txtcandev".$li_filasdevolucion." type=text id=txtcandev".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center >";

					$la_objectdevolucion[$li_filasdevolucion][7]="<input name=txtcodalm".$li_filasdevolucion." id=txtcodalm".$li_filasdevolucion." type=text class=sin-borde size=15 style= text-align:left readonly>";
					$la_objectdevolucion[$li_filasdevolucion][8]="<input name=txtproveedor".$li_filasdevolucion." type=text id=txtproveedor".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center readonly> <input name=txtcodproveedor".$li_filasdevolucion." type=text id=txtcodproveedor".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center readonly>";
					//$la_objectdevolucion[$li_filasdevolucion][9]="<input type='checkbox' name=chkreversar".$li_filasdevolucion." class= sin-borde value=1>";

			}
       }

}

/*****************************************************************************************************************************/
/**************************** CARGAR PRODUCTO EN GRID DESDE CATALOGO DE FACTURA *********************************************/
/*****************************************************************************************************************************/

elseif ($ls_operacion=="ue_cargarfacturas")// 77
{
      //print $ls_codtienda;
       /*******   limpia el GRID    **************/
	    $li_filasdevolucion=1;
   		$la_objectdevolucion[$li_filasdevolucion][1]="<input name=txtcodpro".$li_filasdevolucion." type=text id=txtcodpro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center readonly>";
	    $la_objectdevolucion[$li_filasdevolucion][2]="<input name=txtdenpro".$li_filasdevolucion." type=text id=txtdenpro".$li_filasdevolucion." class=sin-borde size=45 style= text-align:left readonly>";
	    $la_objectdevolucion[$li_filasdevolucion][3]="<input name=txtprepro".$li_filasdevolucion." type=text id=txtprepro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left ><input name=txtcospro".$li_filasdevolucion." type=hidden id=txtcospro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left readonly>";
	    $la_objectdevolucion[$li_filasdevolucion][4]="<input name=txtporcar".$li_filasdevolucion." type=text id=txtporcar".$li_filasdevolucion." class=sin-borde size=8 style= text-align:center readonly>";
	    $la_objectdevolucion[$li_filasdevolucion][5]="<input name=txtcanpro".$li_filasdevolucion." type=text id=txtcanpro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left readonly>";
	    $la_objectdevolucion[$li_filasdevolucion][6]="<input name=txtcandev".$li_filasdevolucion." type=text id=txtcandev".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center>";

		$la_objectdevolucion[$li_filasdevolucion][7]="<input name=txtcodalm".$li_filasdevolucion." id=txtcodalm".$li_filasdevolucion." type=text class=sin-borde size=15 style= text-align:left readonly>";
		$la_objectdevolucion[$li_filasdevolucion][8]="<input name=txtproveedor".$li_filasdevolucion." type=text id=txtproveedor".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center readonly> <input name=txtcodproveedor".$li_filasdevolucion." type=text id=txtcodproveedor".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectdevolucion[$li_filasdevolucion][9]="";

	//////OJO MODIFICAR PARA ACTUALIZAR LA CANTIDAD DEVUELTA ///////////
         $ls_cadena="SELECT sfc_tienda.codtiend, sfc_tienda.dentie,a.denart,sfc_detfactura.codart as codarticulo,sfc_detfactura.*,sfc_producto.codart,rpc_proveedor.nompro as proveedor".
                    " FROM  sfc_producto,sfc_detfactura, rpc_proveedor,sim_articulo a , sfc_tienda".
			        " WHERE sfc_detfactura.codart=sfc_producto.codart AND sfc_detfactura.numfac='".$ls_numfac."' AND sfc_detfactura.cod_pro=rpc_proveedor.cod_pro AND sfc_producto.codart=a.codart AND sfc_detfactura.codtiend=sfc_tienda.codtiend AND sfc_producto.codtiend=sfc_detfactura.codtiend;";
//print $ls_codtie;
//print $ls_cadena;
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
					$totrow=$io_datastore->getRowCount("numfac");


					for($li_i=1;$li_i<=$totrow;$li_i++)
					{


		                $ls_denpro=$io_datastore->getValue("denart",$li_i);
		                $ls_codpro=$io_datastore->getValue("codarticulo",$li_i);
						$ls_prepro=$io_datastore->getValue("prepro",$li_i);
						$ls_cospro=$io_datastore->getValue("costo",$li_i);
						$ls_canpro=$io_datastore->getValue("canpro",$li_i);
						$ls_porcar=$io_datastore->getValue("porimp",$li_i);

						$ls_candvp=$io_datastore->getValue("candev",$li_i);
						$ls_codalm=$io_datastore->getValue("codalm",$li_i);
						$ls_proveedor=$io_datastore->getValue("proveedor",$li_i);
						$ls_codproveedor=$io_datastore->getValue("cod_pro",$li_i);
						$ls_codtie=$io_datastore->getValue("codtiend",$li_i);
						$ls_tienda=$io_datastore->getValue("dentie",$li_i);
//print $ls_codtie;

						$ls_canpro=$ls_canpro-$ls_candvp;

						$ls_candev="0,00";
						$ls_prepro=number_format($ls_prepro,2, ',', '.');
						$ls_cospro=number_format($ls_cospro,2, ',', '.');
						$ls_canpro=number_format($ls_canpro,2, ',', '.');

						$la_objectdevolucion[$li_i][1]="<input name=txtcodpro".$li_i." type=text id=txtcodpro".$li_i." value='".$ls_codpro."' class=sin-borde size=15 style= text-align:center readonly>";
		                $la_objectdevolucion[$li_i][2]="<input name=txtdenpro".$li_i." type=text id=txtdenpro".$li_i." value='".$ls_denpro."' class=sin-borde size=45 style= text-align:left readonly>";
		                $la_objectdevolucion[$li_i][3]="<input name=txtprepro".$li_i." type=text id=txtprepro".$li_i." value='".$ls_prepro."' class=sin-borde size=15 style= text-align:left ><input name=txtcospro".$li_i." type=hidden id=txtcospro".$li_i." value='".$ls_cospro."' class=sin-borde size=15 style= text-align:left readonly>";
		                $la_objectdevolucion[$li_i][4]="<input name=txtporcar".$li_i." type=text id=txtporcar".$li_i." value='".$ls_porcar."' class=sin-borde size=8 style= text-align:center readonly>";
		                $la_objectdevolucion[$li_i][5]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=15 style= text-align:center readonly>";
		                $la_objectdevolucion[$li_i][6]="<input name=txtcandev".$li_i." type=text id=txtcandev".$li_i." value='".$ls_candev."' class=sin-borde size=15 style= text-align:center onblur=ue_subtotal(); onKeyPress=return(currencyFormat(this,'.',',',event,".$li_i."))>";

		          		$la_objectdevolucion[$li_i][7]="<input name=txtcodalm".$li_i." type=text id=txtcodalm".$li_i." value='".$ls_codalm."' class=sin-borde size=15 style= text-align:center readonly>";
						$la_objectdevolucion[$li_i][8]="<input name=txtproveedor".$li_i." type=text id=txtproveedor".$li_i." value='".$ls_proveedor."' class=sin-borde size=15 style= text-align:center > <input name=txtcodproveedor".$li_i." type=text id=txtcodproveedor".$li_i." value='".$ls_codproveedor."' class=sin-borde size=15 style= text-align:center >";
						$la_objectdevolucion[$li_i][9]="<input type='checkbox' name=chkreversar".$li_i." class= sin-borde value=1>";


		          }
  	   	 	   $li_filasdevolucion=$li_i;
		$la_objectdevolucion[$li_filasdevolucion][1]="<input name=txtcodpro".$li_filasdevolucion." type=text id=txtcodpro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center readonly>";
	    $la_objectdevolucion[$li_filasdevolucion][2]="<input name=txtdenpro".$li_filasdevolucion." type=text id=txtdenpro".$li_filasdevolucion." class=sin-borde size=45 style= text-align:left readonly>";
	    $la_objectdevolucion[$li_filasdevolucion][3]="<input name=txtprepro".$li_filasdevolucion." type=text id=txtprepro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left ><input name=txtcospro".$li_filasdevolucion." type=hidden id=txtcospro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left readonly>";
	    $la_objectdevolucion[$li_filasdevolucion][4]="<input name=txtporcar".$li_filasdevolucion." type=text id=txtporcar".$li_filasdevolucion." class=sin-borde size=8 style= text-align:center readonly>";
	    $la_objectdevolucion[$li_filasdevolucion][5]="<input name=txtcanpro".$li_filasdevolucion." type=text id=txtcanpro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left readonly>";
	    $la_objectdevolucion[$li_filasdevolucion][6]="<input name=txtcandev".$li_filasdevolucion." type=text id=txtcandev".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center>";

        $la_objectdevolucion[$li_filasdevolucion][7]="<input name=txtcodalm".$li_filasdevolucion." id=txtcodalm".$li_filasdevolucion." type=text class=sin-borde size=15 style= text-align:left readonly>";
		$la_objectdevolucion[$li_filasdevolucion][8]="<input name=txtproveedor".$li_filasdevolucion." type=text id=txtproveedor".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center readonly> <input name=txtcodproveedor".$li_filasdevolucion." type=text id=txtcodproveedor".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center readonly>";
		//$la_objectdevolucion[$li_filasdevolucion][9]="<input type='checkbox' name=chkreversar".$li_filasdevolucion." class= sin-borde value=1>";
            }

        }
}
/**************************************************************************************************************************/
/**************************** INSERCION O MODIFICACION DE UNA DEVOLUCION EN BD ***********************************************/
/**************************************************************************************************************************/
elseif($ls_operacion=="ue_guardar")
{
        $cantidad_pro=true;

		$la_objectdevolucion[$li_filasdevolucion][1]="<input name=txtcodpro".$li_filasdevolucion." type=text id=txtcodpro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center readonly>";
	    $la_objectdevolucion[$li_filasdevolucion][2]="<input name=txtdenpro".$li_filasdevolucion." type=text id=txtdenpro".$li_filasdevolucion." class=sin-borde size=45 style= text-align:left readonly>";
	    $la_objectdevolucion[$li_filasdevolucion][3]="<input name=txtprepro".$li_filasdevolucion." type=text id=txtprepro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left  ><input name=txtcospro".$li_filasdevolucion." type=hidden id=txtcospro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left readonly>";
	    $la_objectdevolucion[$li_filasdevolucion][4]="<input name=txtporcar".$li_filasdevolucion." type=text id=txtporcar".$li_filasdevolucion." class=sin-borde size=8 style= text-align:center readonly>";
	    $la_objectdevolucion[$li_filasdevolucion][5]="<input name=txtcanpro".$li_filasdevolucion." type=text id=txtcanpro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left readonly>";
	    $la_objectdevolucion[$li_filasdevolucion][6]="<input name=txtcandev".$li_filasdevolucion." type=text id=txtcandev".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center>";

		$la_objectdevolucion[$li_filasdevolucion][7]="<input name=txtcodalm".$li_filasdevolucion." id=txtcodalm".$li_filasdevolucion." type=text class=sin-borde size=15 style= text-align:left readonly>";
		$la_objectdevolucion[$li_filasdevolucion][8]="<input name=txtproveedor".$li_filasdevolucion." type=text id=txtproveedor".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center readonly> <input name=txtcodproveedor".$li_filasdevolucion." type=text id=txtcodproveedor".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectdevolucion[$li_filasdevolucion][9]="<input type='checkbox' name=chkreversar".$li_filasdevolucion." class= sin-borde value=1>";

   $la_detalles["codart"][1]="";
   $la_detalles["denart"][1]="";
   $la_detalles["prepro"][1]="";
   $la_detalles["cospro"][1]="";
   $la_detalles["canpro"][1]="";
   $la_detalles["candev"][1]="";
   $la_detalles["porimp"][1]="";
   $la_detalles["codalm"][1]="";
   $la_detalles["cod_pro"][1]="";
   $la_detalles["proveedor"][1]="";
   $la_detalles["condmerc"][1]="";

   for ($li_i=1;$li_i<$li_filasdevolucion;$li_i++)
    {
	   $ls_txtcandev=$_POST["txtcandev".$li_i];
	   $la_detalles["codart"][$li_i]=$_POST["txtcodpro".$li_i];
	   $la_detalles["denart"][$li_i]=$_POST["txtdenpro".$li_i];
	   $la_detalles["prepro"][$li_i]=$_POST["txtprepro".$li_i];
	   $la_detalles["cospro"][$li_i]=$_POST["txtcospro".$li_i];
	   $la_detalles["porimp"][$li_i]=$_POST["txtporcar".$li_i];
	   $la_detalles["canpro"][$li_i]=$_POST["txtcanpro".$li_i];
	   $la_detalles["candev"][$li_i]=$ls_txtcandev;

	   $la_detalles["codalm"][$li_i]=$_POST["txtcodalm".$li_i];
	   $la_detalles["proveedor"][$li_i]=$_POST["txtproveedor".$li_i];
	   $la_detalles["cod_pro"][$li_i]=$_POST["txtcodproveedor".$li_i];
	    if ($_POST["chkreversar".$li_i]=='')
		{
			$la_detalles["condmerc"][$li_i]='B';
		}
		else
		{
			$la_detalles["condmerc"][$li_i]='V';
		}




	  // $ls_canprod= $_POST["txtcanpro".$li_i];

	   if($ls_txtcandev!="0,00")
	      $cantidad_pro=false;
		//$ls_cantexis= $_POST["txtcanpro".$li_i] - $ls_txtcandev;
		//if($ls_cantexis>$ls_canprod)
		 //$cantidad_dev=false;

	}


  if($cantidad_pro)
	{
	        $io_msg->message ("Debe colocar la cantidad del producto a devolver!!!");
	}
	else
	{

		  $ls_codusu=$_SESSION["la_logusr"];
		  $ls_codcaj=strtolower($_SESSION["ls_codcaj"]);


	      $ls_prefijo=$_SESSION["ls_predev"];
		  $ls_serie=$_SESSION["ls_serdev"];
		  $ls_sercon=$_SESSION["ls_sercon"];
			if (($_POST["txtcoddev"]=="") or ($_POST["txtnumcont"]=="")){
		    $io_secuencia->uf_obtener_secuencia($ls_codcaj.$ls_codtie."dev",&$ls_secuencia);

	        $ls_coddev=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);


			$io_secuencia2->uf_obtener_secuencia($ls_codcaj.$ls_codtie."con",&$ls_seccon);
			$ls_seccon=$io_function->uf_cerosizquierda($ls_seccon,16);
			$ls_numcon=$io_secuencia->uf_crear_codigo("",$ls_sercon,$ls_seccon);

			}else{
			$ls_coddev=$_POST["txtcoddev"];
			 $io_secuencia->uf_obtener_secuencia($ls_codcaj.$ls_codtie."dev",&$ls_secuencia);

		    $ls_numcon=$_POST["txtnumcont"];
			$io_secuencia->uf_actualizar_secuencia($ls_codcaj.$ls_codtie."dev",substr($ls_coddev,6,strlen($ls_coddev)));

			$io_secuencia2->uf_actualizar_secuencia($ls_codcaj.$ls_codtie."con",substr($ls_numcon,6,strlen($ls_numcon)));


			}
	  	 /* echo "session";
		  var_dump($_SESSION);
		  echo "post";
		  var_dump($_POST);
		  echo "get";

	      $io_secuencia->uf_obtener_secuencia($ls_codcaj."dev",&$ls_secuencia);
	      $ls_coddev=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);

		$io_secuencia2->uf_obtener_secuencia($ls_codcaj."con",&$ls_seccon);
		$ls_seccon=$io_function->uf_cerosizquierda($ls_seccon,16);
		$ls_numcon=$io_secuencia->uf_crear_codigo("",$ls_sercon,$ls_seccon);
*/
		  $io_msg->message ("La Devolucion sera guardada con el codigo: ".$ls_coddev." y el numero de control :".$ls_numcon);
		  $lb_valido=$io_devolucion->uf_guardar_devolucion($ls_coddev,$ls_numfac,$ls_observacion,$ls_fecemi,$ls_monto,$ls_codusu,$ls_codtie,$la_seguridad,$ls_numcon);
            if($lb_valido)
	         {

	           $lb_valido=$io_devolucion->uf_update_detallesdevoluciones($ls_coddev,$ls_codpro,$la_detalles,$li_filasdevolucion,$ls_codtie,$la_seguridad);


		       /////NUEVOOOOOOO SI LA FACTURA ES A CREDITO NO SE DEBE GENERAR LA NOTA DE CREDITO
		        $ls_estfac="SELECT sfc_factura.estfaccon ".
                    "FROM  sfc_factura ".
			        "WHERE sfc_factura.numfac='".$ls_numfac."' and codtiend='".$ls_codtie."';";
			       $arr_estfactura=$io_sql->select($ls_estfac);



			if($arr_estfactura==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros de factura");
			}
			else
			{
				if($row=$io_sql->fetch_row($arr_estfactura))

 				  {
					$la_estfactura=$io_sql->obtener_datos($arr_estfactura);
					$io_datastoreest->data=$la_estfactura;
					$ls_estatufac=$io_datastoreest->getValue("estfaccon",1);

 				  }
			}
		         if($lb_valido)
	              {

				//	print 'paso2';
					if (($ls_estatufac <> 'N') and ($ls_estatufac <> 'P'))
					{
//print 'paso CANCELADA';
					    $ls_codcaj=$_SESSION["ls_codcaj"];
			            $ls_prefijo="NC";
			            $ls_serie=$_SESSION["ls_sernot"];
		                $io_secuencia->uf_obtener_secuencia($ls_codcaj.$ls_codtie."not",&$ls_secuencia);
		                $ls_numnot=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);
				        $ls_dennot="Nota de Cr�dito, por la Devoluci�n No. ".$ls_coddev;
				        $ls_tipnot="CXP";
				        $ls_fecnot=$ls_fecemi;
				        $ls_estnot="P"; //P: pendiente y C: cancelado


			  		    $lb_valido=$io_nota->uf_guardar_nota_factura($ls_codcli,$ls_numnot,$ls_dennot,$ls_tipnot,$ls_fecnot,$ls_monto,$ls_estnot,$ls_coddev,$ls_codtie,$la_seguridad);


					    $io_msg->message("Recuerde procesar la Nota de Cr�dito No. ".$ls_numnot." generada por la devolucion");

                   }
                   elseif(($ls_estatufac=='N') or ($ls_estatufac=='P'))
                   {


						$lb_valido=$io_cobranza->uf_calcular_cobrocliente_devolucion($ls_numfac,$ls_codcli,$ls_codtie,&$li_montocobrado);
						if($li_montocobrado<>0)
						{
							//print 'pasooooooooo';
							$lb_valido=$io_nota->uf_select_nota_debito($ls_codcli,$ls_numfac,$ls_codtie,&$ls_numnotdeb,&$li_montonotdeb);
							$li_montopendiente=$li_montonotdeb-$li_montocobrado;
							if ($li_montopendiente>$ls_monto)
							{
								//print 'paso2';
								$ls_monto=$io_funcsob->uf_convertir_cadenanumero($ls_monto);
								//print "MONTO DEV".$ls_monto."<br>";
								$li_montonota=$li_montopendiente-$ls_monto;
/*print "MONTO DEV".$ls_monto."<br>";
print "MONTO PEN".$li_montopendiente."<br>";
print "MONTO COB".$li_montocobrado."<br>";
print "MONTO NDEB".$li_montonota."<br>";*/
								$lb_valido=$io_nota->uf_actualizar_nota_debito($ls_codcli,$ls_numfac,$li_montonota,$ls_numnotdeb,$ls_codtie,$la_seguridad);

							}
							elseif($li_montopendiente==$ls_monto)
							{
								//print 'paso3';
								if ($li_montocobrado>0)
								{
								$li_montonota=$li_montocobrado;
								}
								else
								{
								$li_montonota=$li_montopendiente-$ls_monto;
								}
								$lb_valido=$io_nota->uf_actualizar_nota_debito($ls_codcli,$ls_numfac,$li_montonota,$ls_numnotdeb,$ls_codtie,$la_seguridad);
								$io_factura->uf_actualizar_estatusfactura_devolucion($ls_codcli,$ls_numfac,$ls_codtie,$la_seguridad);
								$ls_estatus="C";
								$io_nota->uf_actualizar_estatusnota_debito($ls_codcli,$ls_numfac,$ls_numnotdeb,$ls_estatus,$ls_codtie,$la_seguridad);

							}
							else
							{
								//print 'paso4';
								$ls_codcaj=$_SESSION["ls_codcaj"];
					            $ls_prefijo="NC";
					            $ls_serie=$_SESSION["ls_sernot"];
				                $io_secuencia->uf_obtener_secuencia($ls_codcaj.$ls_codtie."not",&$ls_secuencia);
				                $ls_numnot=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);
						        $ls_dennot="Nota de Cr�dito, por la Devoluci�n No. ".$ls_coddev;
						        $ls_tipnot="CXP";
						        $ls_fecnot=$ls_fecemi;
						        $ls_estnot="P"; //P: pendiente y C: cancelado

								//$ls_montocredito=$ls_monto-$li_montocobrado;
/*print "MONTO DEV".$ls_monto."<br>";
print "MONTO PEN".$li_montopendiente."<br>";
print "MONTO COB".$li_montocobrado."<br>";*/

								$ls_montocredito=$ls_monto-$li_montopendiente;
//print "MONTO NCRE".$ls_montocredito."<br>";
								$li_montonota=0;

					  		    $lb_valido=$io_nota->uf_guardar_nota_factura($ls_codcli,$ls_numnot,$ls_dennot,$ls_tipnot,$ls_fecnot,$ls_montocredito,$ls_estnot,$ls_coddev,$ls_codtie,$la_seguridad);
							    $io_factura->uf_actualizar_estatusfactura_devolucion($ls_codcli,$ls_numfac,$ls_codtie,$la_seguridad);
								$lb_valido=$io_nota->uf_actualizar_nota_debito($ls_codcli,$ls_numfac,$li_montonota,$ls_numnotdeb,$ls_codtie,$la_seguridad);
								$ls_estatus="C";
								$io_nota->uf_actualizar_estatusnota_debito($ls_codcli,$ls_numfac,$ls_numnotdeb,$ls_estatus,$ls_codtie,$la_seguridad);

							    $io_msg->message("Recuerde procesar la Nota de Cr�dito No. ".$ls_numnot." generada por la devolucion");

							}

							$io_cobranza->uf_actualizar_estcobcli_devolucion($ls_numfac,$ls_codcli,$ls_codtie);

						}
						else
						{
								//print 'pasooooooooo2222';
							$lb_valido=$io_nota->uf_select_nota_debito($ls_codcli,$ls_numfac,$ls_codtie,&$ls_numnotdeb,&$li_montonotdeb);

//print $li_montonotdeb."<br>".$ls_monto."MONOTS<br>";
 							$ls_monto=$io_funcsob->uf_convertir_cadenanumero($ls_monto);
// print $li_montonotdeb."<br>".$ls_monto."MONOTS<br>";


							if ($li_montonotdeb>$ls_monto)
							{
								/*print 'paso5'.$ls_monto."<br>";

								print 'paso5'.$ls_monto;*/
								$li_montonota=$li_montonotdeb-$ls_monto;
								//print $li_montonota;
								$lb_valido=$io_nota->uf_actualizar_nota_debito($ls_codcli,$ls_numfac,$li_montonota,$ls_numnotdeb,$ls_codtie,$la_seguridad);
							}
							elseif($li_montonotdeb==$ls_monto)
							{
								//print 'paso6';
								$li_montonota=$li_montonotdeb-$ls_monto;
								$lb_valido=$io_nota->uf_actualizar_nota_debito($ls_codcli,$ls_numfac,$li_montonota,$ls_numnotdeb,$ls_codtie,$la_seguridad);
								$io_factura->uf_actualizar_estatusfactura_devolucion($ls_codcli,$ls_numfac,$ls_codtie,$la_seguridad);
								$ls_estatus="C";
								$io_nota->uf_actualizar_estatusnota_debito($ls_codcli,$ls_numfac,$ls_numnotdeb,$ls_estatus,$ls_codtie,$la_seguridad);
							}
							else
							{
								//print 'paso7';
								$ls_codcaj=$_SESSION["ls_codcaj"];
					            $ls_prefijo="NC";
					            $ls_serie=$_SESSION["ls_sernot"];
				                $io_secuencia->uf_obtener_secuencia($ls_codcaj.$ls_codtie."not",&$ls_secuencia);
				                $ls_numnot=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);
						        $ls_dennot="Nota de Cr�dito, por la Devoluci�n No. ".$ls_coddev;
						        $ls_tipnot="CXP";
						        $ls_fecnot=$ls_fecemi;
						        $ls_estnot="P"; //P: pendiente y C: cancelado

								$ls_montocredito=$ls_monto-$li_montonotdeb;
								$ls_montocredito=str_replace('.',',',$ls_montocredito);
								//print "CRE ".$ls_montocredito."<br>";

 								$li_montonota=0;

					  		    $lb_valido=$io_nota->uf_guardar_nota_factura($ls_codcli,$ls_numnot,$ls_dennot,$ls_tipnot,$ls_fecnot,$ls_montocredito,$ls_estnot,$ls_coddev,$ls_codtie,$la_seguridad);
							    $io_factura->uf_actualizar_estatusfactura_devolucion($ls_codcli,$ls_numfac,$ls_codtie,$la_seguridad);
								$lb_valido=$io_nota->uf_actualizar_nota_debito($ls_codcli,$ls_numfac,$li_montonota,$ls_numnotdeb,$ls_codtie,$la_seguridad);
								$ls_estatus="C";
								$io_nota->uf_actualizar_estatusnota_debito($ls_codcli,$ls_numfac,$ls_numnotdeb,$ls_estatus,$ls_codtie,$la_seguridad);
							    $io_msg->message("Recuerde procesar la Nota de Cr�dito No. ".$ls_numnot." generada por la devolucion");

							}
						}
                   }
                   /*******************INTEGRACION CON INVENTARIO***************************/
				    if ($lb_valido)
					{
					 $ls_docum=substr($ls_coddev,0,5);
					 $ls_docum=$ls_docum.substr($ls_coddev,20,strlen($ls_coddev));
					 for ($li_i=1;$li_i<$li_filasdevolucion;$li_i++)
                        {
	                        $ls_codpro=$_POST["txtcodpro".$li_i];
							$ls_prepro=$_POST["txtprepro".$li_i];
							$ls_cospro=$_POST["txtcospro".$li_i];
	                        $ls_candev=$_POST["txtcandev".$li_i];
	                        $ls_porimp=$_POST["txtporcar".$li_i];
							$ls_codalm=$_POST["txtcodalm".$li_i];
						    $ls_proveedor=$_POST["txtproveedor".$li_i];
	   						$ls_codproveedor=$_POST["txtcodproveedor".$li_i];
	   						if ($_POST["chkreversar".$li_i]=='')
							{
								$ls_condmerc='B';
							}
							else
							{
								$ls_condmerc='V';
							}

							if($ls_candev!="0,00"){
							$ls_candev=$io_funcsob->uf_convertir_cadenanumero($ls_candev);
							$ls_prepro=$io_funcsob->uf_convertir_cadenanumero($ls_prepro); /* convierte cadena en numero */
							$ls_cospro=$io_funcsob->uf_convertir_cadenanumero($ls_cospro); /* convierte cadena en numero */
							$ls_porimp=$io_funcsob->uf_convertir_cadenanumero($ls_porimp);
							$ld_iva=($ls_prepro*$ls_porimp/100)*$ls_candev;


							$li_monsubart=$ls_prepro*$ls_candev;
							$li_montotart=$li_monsubart+$ld_iva;

							$li_monsubcosart=$ls_cospro*$ls_candev;
							$li_montotcosart=$li_monsubcosart+$ld_iva;

							$ls_fecdev=$io_function->uf_convertirdatetobd($ls_fecemi);

							//print 'paso4';
					        $lb_valido=$io_siv->uf_sim_insert_recepcion($ls_codemp,$ls_docum,$ls_codalm,$ls_fecdev,
								"Entrada a Almacen por Devolucion",$ls_codusu,"0","1",$ls_codproveedor,$ls_codtie,&$ls_numconrec,$la_seguridad);
					        if ($lb_valido)
						     {
							 	//print 'paso5';
							    $ls_nummov=0;
							    $ls_nomsol="Recepcion";
							    $lb_valido=$io_mov->uf_sim_insert_movimiento($ls_codemp,&$ls_nummov,$ls_fecdev,$ls_docum,$ls_codusu,$ls_codtie,
																		      $la_seguridad);
					 	     }
							$lb_valido=$io_siv->uf_sim_insert_dt_recepcion($ls_codemp,$ls_docum,$ls_codpro,"D",$ls_candev,0,$ls_cospro,$li_monsubcosart,$li_montotcosart,$li_i,$ls_candev,$ls_numconrec,$ls_codproveedor,$ls_codtie,$la_seguridad);
							if ($lb_valido)
							 {
									$lb_valido=$io_art->uf_sim_aumentar_articuloxalmacen($ls_codemp,$ls_codpro,$ls_codalm,
									                                    				 $ls_candev,$ls_codproveedor,$ls_codtie,$la_seguridad);
									if($lb_valido)
									{
										$ls_opeinv="ENT";
										$ls_promov="RPC";
										$ls_codprodoc="FAC";
										$lb_valido=$io_mov->uf_sim_insert_dt_movimiento($ls_codemp,$ls_nummov,$ls_fecdev,
																						$ls_codpro,$ls_codalm,$ls_opeinv,
																						$ls_codprodoc,$ls_docum,$ls_candev,
																						$ls_cospro,$ls_promov,$ls_numconrec,
																						$ls_candev,$ls_fecdev,$ls_codproveedor,$ls_codtie,$la_seguridad);
									}
							  }

							 if($ls_condmerc=='V')
							 {
								$ls_coduniadm=$_SESSION["ls_coduniad"];
								$ls_numdoc=substr($ls_coddev,10,strlen($ls_coddev));
							 	 $lb_valido=$io_sivDes->uf_sim_insert_despacho($ls_codemp,$ls_numord,$ls_numdoc,$ls_coduniadm,$ls_fecdev,"Despacho por Devolucion, articulos en mal estado",$ls_codusu,"1","1","",$ls_codtie,$la_seguridad);

						         if($lb_valido)
						         {

							         $lb_valido=$io_mov->uf_sim_insert_movimiento($ls_codemp,$ls_nummov,$ls_fecdev,"Despacho por Devolucion, articulos en mal estado",$ls_codusu,$ls_codtie,$la_seguridad);

								     $ls_unidad="D";

								     $lb_valido=$io_sivDes->uf_sim_procesar_dt_despacho($ls_codemp,$ls_numord,$ls_codpro,$ls_codalm,$ls_unidad,$ls_candev,$ls_candev,$ls_prepro,$li_montotart,$li_montotart,$li_i,$ls_nummov,$ls_fecdev,$ls_numdoc,0,$ls_codproveedor,$ls_codtie,$la_seguridad,"DEV");

								     $lb_validoart=$io_art->uf_sim_disminuir_articuloxalmacen($ls_codemp,$ls_codpro,$ls_codalm,$ls_candev,$ls_codproveedor,$ls_codtie,$la_seguridad);

									 $io_msg->message (" El Artículo: ".$ls_codpro." ha sido desincorporado del Inventario por estar en mal estado");
								         //$io_art->uf_sim_actualizar_cantidad_articulos($ls_codemp,$ls_codpro,$ls_codproveedor,$ls_codtie/*,$la_seguridad*/);
						         	}
								 }




							  if($lb_valido)
							   {

								$lb_valido=$io_devolucion->uf_actualizar_factura($ls_numfac,$ls_codpro,$ls_candev,"A",$ls_codproveedor,$ls_codtie,$la_seguridad);

							   }
					        }
					    }
						}
						if($lb_valido){
						$io_devolucion->io_sql->commit();
						$io_siv->io_sql->commit();
						$io_mov->io_sql->commit();
						$io_art->io_sql->commit();
						$io_sivDes->io_sql->commit();
						$io_msg->message ("Devolucion Procesada Exitosamente!!!");
						}else
						{
						$io_devolucion->io_sql->rollback();
						$io_siv->io_sql->rollback();
						$io_mov->io_sql->rollback();
						$io_art->io_sql->rollback();
						$io_sivDes->io_sql->rollback();
						$io_nota->io_sql->rollback();
						$io_cobranza->io_sql->rollback();
						$io_msg->message ("Ocurrio un error en el Proceso!!!");

						}
					/*******************FIN INTEGRACION CON INVENTARIO***********************/



			      }



		      }



	}




$ls_operacion="VER";




}
/******************************************************************************************/
/*************** ELIMINAR FACTURA EN BD ***************************************************/
/******************************************************************************************/
elseif ($ls_operacion=="ue_anular")
{

   $ls_coduniadm=$_SESSION["ls_coduniad"];

   $io_devolucion->uf_select_devolucion($ls_coddev,$ls_codtie,$ls_estdev);
   print $ls_estdev;
   if($ls_estdev!="A")
   {
	if($ls_estdev!=P)
	{

	$lb_validoA=$io_devolucion->uf_actualizar_estdevolucion($ls_coddev,$ls_codtie,$la_seguridad);

	 if($lb_validoA)
	 {

	 	 $ld_fecmov=date('Y-m-d');
	 	 $ld_fecemi=$_POST["txtfecemi"];

	 	 $ld_fecemi=$io_function->uf_convertirdatetobd($ld_fecemi);

		 $ls_coddev=$_POST["txtcoddev"];
		 $ls_docum=substr($ls_coddev,0,5);
		 $ls_docum=$ls_docum.substr($ls_coddev,20,strlen($ls_coddev));
		 for ($li_i=1;$li_i<$li_filasdevolucion;$li_i++)
	     {
		  	$ls_codpro=$_POST["txtcodpro".$li_i];
		    $ls_candev=$_POST["txtcandev".$li_i];
		    $ls_codart = $_POST["txtcodpro".$li_i];
			$ls_codalm = $_POST["txtcodalm".$li_i];
			$ls_canpro=$_POST["txtcanpro".$li_i];
			$ls_prepro = $_POST["txtprepro".$li_i];
			$ls_cospro = $_POST["txtcospro".$li_i];

			$ls_denpro   = $_POST["txtproveedor".$li_i];
			$ls_codproveedor = $_POST["txtcodproveedor".$li_i];
			$ls_condmerc=$_POST["chkreversar".$li_i];



			$ld_porimp=$io_funcsob->uf_convertir_cadenanumero($_POST["txtporcar".$li_i]);
			$li_preuniart=$io_funcsob->uf_convertir_cadenanumero($ls_prepro);
			$ls_cospro=$io_funcsob->uf_convertir_cadenanumero($ls_cospro);
			$ld_canpro=$io_funcsob->uf_convertir_cadenanumero($ls_candev);
			$li_monsubart=($li_preuniart*$ld_canpro);
			$li_montotart=($li_preuniart*$ld_canpro)+(($li_preuniart*$ld_canpro)*($ld_porimp/100));

			$li_monsubcosart=($ls_cospro*$ld_canpro);
			$li_montotcosart=($ls_cospro*$ld_canpro)+(($ls_cospro*$ld_canpro)*($ld_porimp/100));
			$ls_unidad="D";
		    if($ls_candev!="0,00")
		    {
				$ls_candev=$io_funcsob->uf_convertir_cadenanumero($ls_candev);
				$lb_validoC=$io_devolucion->uf_actualizar_factura($ls_numfac,$ls_codpro,$ls_candev,"D",$ls_codproveedor,$ls_codtie,$la_seguridad);
			}

			if ($_POST["chkreversar".$li_i]=='')
			{
				$ls_condmerc='B';
			}
			else
			{
				$ls_condmerc='V';
			}

			 /*****************************************************/
         	/*******INTEGRACION CON INVENTARIO********************/
        	 /*****************************************************/

 			if($ls_condmerc=='B')
 			{

		         $ls_obsdes="Despacho por concepto de Anulacion de la devolucion No. ".$ls_coddev;
		         $ls_numdoc=substr($ls_coddev,0,5);
		 		 $ls_numdoc=$ls_numdoc.substr($ls_coddev,20,strlen($ls_coddev));


		         $lb_valido=$io_sivDes->uf_sim_insert_despacho($ls_codemp,$ls_numord,$ls_numdoc,$ls_coduniadm,$ld_fecmov,$ls_obsdes,$ls_codusu,"1","1","",$ls_codtie,$la_seguridad);

		         if($lb_valido)
		         {
			         $lb_valido=$io_mov->uf_sim_insert_movimiento($ls_codemp,$ls_nummov,$ld_fecmov,"Despacho por Anulacion Dev",$ls_codusu,$ls_codtie,$la_seguridad);
			         for ($li_j=1;$li_j<$li_filasdevolucion;$li_j++)
			         {


				        $lb_valido=$io_sivDes->uf_sim_procesar_dt_despacho($ls_codemp,$ls_numord,$ls_codart,$ls_codalm,$ls_unidad,$ld_canpro,$ld_canpro,$li_preuniart,$li_montotart,$li_montotart,$li_i,$ls_nummov,$ld_fecmov,$ls_numdoc,0,$ls_codproveedor,$ls_codtie,$la_seguridad,"DEV");

				         $lb_valido=$io_art->uf_sim_disminuir_articuloxalmacen($ls_codemp,$ls_codart,$ls_codalm,$ld_canpro,$ls_codproveedor,$ls_codtie,$la_seguridad);

				         //$io_art->uf_sim_actualizar_cantidad_articulos($ls_codemp,$ls_codpro,$ls_codproveedor,$ls_codtie/*,$la_seguridad*/);
		           }

		    /*****************************************************/
		    /*******FIN INTEGRACION CON INVENTARIO****************/
		    /*****************************************************/
		         }

				 $ls_operacion="";
				 $ls_codtie="";
			     $ls_tienda="";
				 $ls_coddev="";
			     $ls_numfac="";
			     $ls_fecemi="";
			     $ls_codcli="";
				 $ls_estdev="";
			     $ls_nomcli="";
			     $ls_monto="0,00";
			     $ls_mondev="0,00";
			     $ls_montocos="0,00";
			     $ls_moncosdev="0,00";

				 $li_filasdevolucion=1;
			     $la_objectdevolucion[$li_filasdevolucion][1]="<input name=txtcodpro".$li_filasdevolucion." type=text id=txtcodpro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center readonly>";
			     $la_objectdevolucion[$li_filasdevolucion][2]="<input name=txtdenpro".$li_filasdevolucion." type=text id=txtdenpro".$li_filasdevolucion." class=sin-borde size=45 style= text-align:left readonly>";
			     $la_objectdevolucion[$li_filasdevolucion][3]="<input name=txtprepro".$li_filasdevolucion." type=text id=txtprepro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left><input name=txtcospro".$li_filasdevolucion." type=hidden id=txtcospro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left readonly>";
			     $la_objectdevolucion[$li_filasdevolucion][4]="<input name=txtporcar".$li_filasdevolucion." type=text id=txtporcar".$li_filasdevolucion." class=sin-borde size=8 style= text-align:center readonly>";
			     $la_objectdevolucion[$li_filasdevolucion][5]="<input name=txtcanpro".$li_filasdevolucion." type=text id=txtcanpro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left readonly>";
			     $la_objectdevolucion[$li_filasdevolucion][6]="<input name=txtcandev".$li_filasdevolucion." type=text id=txtcandev".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center>";

				 $la_objectdevolucion[$li_filasdevolucion][7]="<input name=txtcodalm".$li_filasdevolucion." id=txtcodalm".$li_filasdevolucion." type=text class=sin-borde size=15 style= text-align:left readonly>";
				 $la_objectdevolucion[$li_filasdevolucion][8]="<input name=txtproveedor".$li_filasdevolucion." type=text id=txtproveedor".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center readonly>";
				 $la_objectdevolucion[$li_filasdevolucion][9]="<input type='checkbox' name=chkreversar".$li_filasdevolucion." class= sin-borde value=1>";

			}
			elseif($ls_condmerc=='V')
			{
				 $lb_valido=$io_siv->uf_sim_insert_recepcion($ls_codemp,$ls_docum,$ls_codalm,$ld_fecmov,
								"Entrada a Almacen por Anulacion en Devolucion",$ls_codusu,"0","1",$ls_codproveedor,$ls_codtie,&$ls_numconrec,$la_seguridad);



				 if ($lb_valido)
				 {

					$ls_nummov=0;
					$ls_nomsol="Recepcion Anulacion en Devolucion";
					$lb_valido=$io_mov->uf_sim_insert_movimiento($ls_codemp,&$ls_nummov,$ld_fecmov,$ls_docum,$ls_codusu,$ls_codtie,
																		      $la_seguridad);


					$lb_valido=$io_siv->uf_sim_insert_dt_recepcion($ls_codemp,$ls_docum,$ls_codpro,"D",$ls_candev,0,$ls_cospro,$li_monsubcosart,$li_montotcosart,$li_i,$ls_candev,$ls_numconrec,$ls_codproveedor,$ls_codtie,$la_seguridad);
					if ($lb_valido)
					{
						$lb_valido=$io_art->uf_sim_aumentar_articuloxalmacen($ls_codemp,$ls_codpro,$ls_codalm,
										                                    				 $ls_candev,$ls_codproveedor,$ls_codtie,$la_seguridad);
						if($lb_valido)
						{
							$ls_opeinv="ENT";
							$ls_promov="RPC";
							$ls_codprodoc="FAC";
							$lb_valido=$io_mov->uf_sim_insert_dt_movimiento($ls_codemp,$ls_nummov,$ld_fecmov,
																							$ls_codpro,$ls_codalm,$ls_opeinv,
																							$ls_codprodoc,$ls_docum,$ls_candev,
																							$ls_cospro,$ls_promov,$ls_numconrec,
																							$ls_candev,$ld_fecemi,$ls_codproveedor,$ls_codtie,$la_seguridad);
						}
					}
				 }
			}
	 }
     if ($lb_validoC)
	 {
	     $io_devolucion->uf_delete_nota($ls_coddev,$ls_codtie,$la_seguridad);
	     $lb_valido=$io_nota->uf_select_nota_debito($ls_codcli,$ls_numfac,$ls_codtie,&$ls_numnotdeb,&$li_montonotdeb);
	     if($lb_valido)
	     {
	     	$li_montoactual=$li_montonotdeb+$li_montotart;
	     	$lb_valido=$io_nota->uf_actualizar_nota_debito($ls_codcli,$ls_numfac,$li_montoactual,$ls_numnotdeb,$ls_codtie,$la_seguridad);
			$ls_estatus="P";
			$io_nota->uf_actualizar_estatusnota_debito($ls_codcli,$ls_numfac,$ls_numnotdeb,$ls_estatus,$ls_codtie,$la_seguridad);

	     }

		if($lb_valido)
		{
			$io_devolucion->io_sql->commit();
			$io_siv->io_sql->commit();
			$io_mov->io_sql->commit();
			$io_art->io_sql->commit();
			$io_sivDes->io_sql->commit();
			$io_msg->message ("La Devolucion fue anulada exitosamente!!!");


		}
		else
		{
			$io_devolucion->io_sql->rollback();
			$io_siv->io_sql->rollback();
			$io_mov->io_sql->rollback();
			$io_art->io_sql->rollback();
			$io_sivDes->io_sql->rollback();
			$io_nota->io_sql->rollback();
			$io_cobranza->io_sql->rollback();
			$io_msg->message ("La Devolucion no pudo ser Procesada!");
		}
	  }

	}
	}
	 else
	 {
	 	$io_msg->message ("La Devolucion ha sido cerrada no puede ser eliminada!!");

	 }

  }
  else
  {

     $io_msg->message ("La Devolucion ya fue Anulada!!");
  }

  print("<script language=JavaScript>");
	print("pagina='sigesp_sfc_d_devolucion.php';");
    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
    print("</script>");



}
/******************************************************************************************/
/***************** VER REPORTE DE FACTURA *************************************************/
/******************************************************************************************/
if($ls_operacion=="VER")
{

/*$ls_sql="SELECT f.fecemi,f.monto,d.coddev,d.numfac,d.obsdev,d.fecdev,d.mondev,d.codtiend,dd.codart,dd.candev," .
		"dd.precio,dd.porimp,a.denart,c.cedcli,c.razcli,c.dircli,c.telcli,t.dentie,t.teltie,t.riftie,ca.nomusu FROM " .
		"sfc_devolucion d,sfc_detdevolucion dd,sfc_producto p,sim_articulo a,sfc_cliente c,sfc_tienda t,sfc_cajero ca,sfc_factura f ".
		 "WHERE d.coddev=dd.coddev AND d.codemp=dd.codemp AND d.codtiend=dd.codtiend AND d.codemp=p.codemp AND a.codart=p.codart AND d.codemp=a.codemp AND " .
		 "d.codtiend=p.codtiend AND d.codemp=c.codemp AND d.codtiend=t.codtiend AND ".
   "d.codemp=ca.codemp AND d.codtiend=ca.codtiend AND d.codusu=ca.codusu AND d.codemp=f.codemp AND ".
"d.codtiend=f.codtiend AND d.numfac=f.numfac AND dd.codemp=p.codemp AND dd.codart=p.codart AND dd.codart=a.codart AND ".
"dd.codtiend=p.codtiend AND dd.codemp=c.codemp AND dd.codtiend=t.codtiend AND ".
"dd.codemp=ca.codemp AND dd.codtiend=ca.codtiend AND dd.codemp=f.codemp AND dd.codtiend=f.codtiend AND ".
"p.codemp=c.codemp AND p.codtiend=t.codtiend AND p.codemp=ca.codemp AND ".
"p.codtiend=ca.codtiend AND p.codemp=f.codemp AND p.codtiend=f.codtiend AND  ".
"c.codemp=ca.codemp AND c.codcli=f.codcli AND c.codemp=f.codemp AND ".
" t.codtie=ca.codtie AND t.codtiend=f.codtiend AND ca.codemp=f.codemp AND ".
" ca.codtiend=f.codtiend AND d.coddev like '".$ls_coddev."' AND d.numfac like '".$ls_numfac."';";*/

/*$ls_sql=" SELECT f.fecemi,f.monto,d.coddev,d.numfac,d.obsdev,d.fecdev,d.mondev,d.codtiend,dd.codart,dd.candev,dd.precio,dd.porimp,a.denart,c.cedcli," .
		"c.razcli,c.dircli,c.telcli,t.dentie,t.teltie,t.riftie,ca.nomusu,pr.nompro from sfc_factura f, sfc_devolucion d, sfc_detdevolucion dd, " .
		"sim_articulo a, sfc_producto p, sfc_cliente c,sfc_cajero ca,sfc_tienda t, rpc_proveedor pr where f.numfac=d.numfac " .
		"and f.codtiend=d.codtiend and d.coddev=dd.coddev and d.codtiend=dd.codtiend and a.codart=dd.codart and a.codart=p.codart " .
		"and p.codart=dd.codart and p.codtiend=dd.codtiend and c.codcli=f.codcli and d.codusu=ca.codusu and t.codtiend=d.codtiend " .
		"and t.codtiend=dd.codtiend and pr.cod_pro=dd.cod_pro and d.coddev ilike '".$ls_coddev."' AND d.numfac ilike '".$ls_numfac."' ; ";*/


$ls_sql="SELECT f.fecemi,f.monto,d.coddev,d.numfac,d.obsdev,d.fecdev,d.mondev,d.codtiend,dd.codart,dd.candev,dd.precio,dd.porimp," .
		" a.denart,c.cedcli,c.razcli,c.dircli,c.telcli,t.dentie,t.teltie,t.riftie,pr.nompro" .
		" from sfc_factura f, sfc_devolucion d, sfc_detdevolucion dd, sim_articulo a,  sfc_cliente c," .
		" sfc_tienda t, rpc_proveedor pr where f.codemp=d.codemp and f.codemp=c.codemp and f.codemp=t.codemp  " .
		" and f.codemp=dd.codemp and f.codemp=a.codemp and f.codemp=pr.codemp and f.numfac ilike '".$ls_numfac."' and f.codtiend=d.codtiend" .
		" and f.codtiend=t.codtiend  and f.codtiend=dd.codtiend and d.codemp=c.codemp and d.codemp=t.codemp " .
		" and  d.codemp=dd.codemp and d.codemp=a.codemp and d.codemp=pr.codemp and d.coddev=dd.coddev and " .
		" d.codtiend=t.codtiend and  d.codtiend=dd.codtiend and d.coddev ilike '".$ls_coddev."' and" .
		" d.numfac ilike '".$ls_numfac."' and c.codemp=t.codemp and  c.codemp=dd.codemp and c.codemp=a.codemp and " .
		" c.codemp=pr.codemp and t.codemp=dd.codemp and t.codemp=a.codemp and t.codemp=pr.codemp " .
		" and t.codtiend=dd.codtiend and " .
		" dd.codemp=a.codemp and dd.codemp=pr.codemp and dd.codart=a.codart and a.codemp=pr.codemp and pr.cod_pro=dd.cod_pro and " .
		" f.codcli=c.codcli";




//print $ls_sql;
/**************  VERIFICAR QUE EXISTA FACTURA   ************************************/
		$rs_datauni=$io_sql->select($ls_sql);

		if($rs_datauni==false)
		{

			$lb_valido_repdev=false;
			$io_msg="Error en uf_select_ReporteFacturacion ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_repdev=true; //Registro encontrado

			}
			else
			{
				$lb_valido_repdev=false; //"Registro no encontrado"
				 $io_msg->message ("La Devoluci�n no esta registrada, reg�strela e intente de nuevo!!!");
			}
		}
	/****************************************************************************************************************************/
	if ($lb_valido_repdev==true)
	 {
	 // $sub_total="prueba";


	 ?>

     <script languaje='JavaScrip'>
		ls_sql="<?php print $ls_sql;?>"

		pagina="reportes/sigesp_sfc_rep_devolucion.php?sql="+ls_sql;

	  	popupWin(pagina,"catalogo",580,700);


     </script>


     <?Php
print("<script language=JavaScript>");
	print("pagina='sigesp_sfc_d_devolucion.php';");
    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
    print("</script>");

	    $ls_operacion="ue_nuevo";
	    $ls_codtie="";
	    $ls_tienda="";
		$ls_numfac="";
		$ls_fecemi="";
		//$ls_fecemi=date('d/m/Y');
		$ls_codcli="";
		$ls_estdev="";
		$ls_nomcli="";
		$ls_observacion="";
		$ls_coddev="";
		$ls_mondev="0,00";
		$ls_moniva="0,00";
		$ls_monto="0,00";
		$ls_moncosdev="0,00";
		$ls_moncosiva="0,00";
		$ls_montocos="0,00";
		$ls_hidval="";
		$ls_numcon="";
 		$ls_candev ="0";

		 $li_filasdevolucion=1;
	     $la_objectdevolucion[$li_filasdevolucion][1]="<input name=txtcodpro".$li_filasdevolucion." type=text id=txtcodpro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center readonly>";
	     $la_objectdevolucion[$li_filasdevolucion][2]="<input name=txtdenpro".$li_filasdevolucion." type=text id=txtdenpro".$li_filasdevolucion." class=sin-borde size=45 style= text-align:left readonly>";
	     $la_objectdevolucion[$li_filasdevolucion][3]="<input name=txtprepro".$li_filasdevolucion." type=text id=txtprepro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left ><input name=txtcospro".$li_filasdevolucion." type=hidden id=txtcospro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left readonly>";
	     $la_objectdevolucion[$li_filasdevolucion][4]="<input name=txtporcar".$li_filasdevolucion." type=text id=txtporcar".$li_filasdevolucion." class=sin-borde size=8 style= text-align:center readonly>";
	     $la_objectdevolucion[$li_filasdevolucion][5]="<input name=txtcanpro".$li_filasdevolucion." type=text id=txtcanpro".$li_filasdevolucion." class=sin-borde size=15 style= text-align:left readonly>";
	     $la_objectdevolucion[$li_filasdevolucion][6]="<input name=txtcandev".$li_filasdevolucion." type=text id=txtcandev".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center>";

		 $la_objectdevolucion[$li_filasdevolucion][7]="<input name=txtcodalm".$li_filasdevolucion." id=txtcodalm".$li_filasdevolucion." type=text class=sin-borde size=15 style= text-align:left readonly>";
		 $la_objectdevolucion[$li_filasdevolucion][8]="<input name=txtproveedor".$li_filasdevolucion." type=text id=txtproveedor".$li_filasdevolucion." class=sin-borde size=15 style= text-align:center readonly>";
		 $la_objectdevolucion[$li_filasdevolucion][9]="<input type='checkbox' name=chkreversar".$li_filasdevolucion." class= sin-borde value=1>";




         }
}

?>

<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="517" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1 Estilo3">Sistema de Facturaci&oacute;n</span></td>
    <td width="261" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
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
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><div align="left"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><a href="javascript:ue_ver();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a><a href="javascript:ue_eliminar();"><img src="../shared/imagenes/editdelete.png" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
  </tr>
</table>
  <p>&nbsp;
  </p>
  <form name="form1" method="post" action=""  meta http-equiv="refresh" content="0" URL="sigespwindow_blank.php"  >
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

    <table width="780" height="369" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
    <tr class="titulo-celdanew">
      <th height="19" colspan="7" class="titulo-celdanew" scope="col">Devoluciones</th>
    </tr>
    <tr class="formato-blanco">
      <td  colspan="7"><input name="operacion" type="hidden" id="operacion">
        <input name="txtfilasfpago" type="hidden" id="txtfilasfpago" value="<?php $ls_filaspago ?>">
        <input name="hidstatus" type="hidden" id="hidstatus" value="<?php $ls_hidstatus ?>">
      <input name="hidval" type="hidden" id="hidval" value="<?php $ls_hidval?>">	  <input name="txtestdev" type="hidden" id="txtestdev" style="text-align:center " value="<? print $ls_estdev ?>" size="15" maxlength="10" readonly="true"></td>
    </tr>

    <tr class="formato-blanco">
      <td width="100" height="26"><div align="right">No</div></td>
      <td width="332"><input name="txtcoddev" type="text" id="txtcoddev" value="<? print $ls_coddev ?>" size="28" maxlength="25" readonly="true"><a href="javascript:editar_codigo();">  editar</a></td>
      <td colspan="3"><div align="right">Numero de Control:</div></td>
      <td colspan="2"><input name="txtnumcont" type="text" id="txtnumcont" value="<? print $ls_numcon ?>" size="30" maxlength="30" readonly="true" ><a href="javascript:editar_codigo();">  editar</a></td>
    </tr>
    <tr class="formato-blanco">
      <td height="26"><div align="right">N&ordm; Factura </div></td>
      <td><div align="left">
          <input name="txtnumfac" type="text" id="txtnumfac" value="<? print $ls_numfac ?>" size="28" maxlength="25" readonly="true">
          <a href="javascript:ue_buscar_factura();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a></div></td>
      <td colspan="3"><div align="right">Fecha</div></td>
      <td colspan="2"><input name="txtfecemi" type="text" id="txtfecemi"  style="text-align:left" value="<? print $ls_fecemi?>" size="11" maxlength="10"   onKeyPress="javascript:ue_validafecha();" datepicker="true" readonly="true"></td>
    </tr>

    <tr class="formato-blanco">
      <td height="26" ><div align="right">Unidad Operativa de Suministro </div></td>
      <td><div align="left">
          <input name="txtcodtienda" type="text" id="txtcodtienda" value="<? print $ls_codtie ?>" size="6" maxlength="8" readonly="true">
          <input name="txttienda" type="text" id="txttienda"  style="text-align:left" class="sin-borde" value="<? print $ls_tienda?>" size="41" maxlength="40" readonly="true">
      </td>
    </tr>

    <tr class="formato-blanco">
      <td height="22"><div align="right">Cliente</div></td>
      <td colspan="6"><input name="txtcodcli" type="hidden" id="txtcodcli" style="text-align:center " value="<? print $ls_codcli ?>" size="15" maxlength="10" readonly="true">

	   <a href="javascript:ue_catclientefac();"></a>
          <input name="txtnomcli" type="text" id="txtnomcli"  style="text-align:left" class="sin-borde" value="<? print $ls_nomcli ?>" size="70" maxlength="100" readonly="true">      </td></tr>
    <tr class="formato-blanco">
      <td height="27"><div align="right">Observaci&oacute;n</div></td>
      <td colspan="6">
	  <!----------------------------------- COMBO CONDICION DE PAGO -----------------------------------------------------------------><!------------------------------------------------------------------------------------------------------------------------------>
          <label>
          <textarea name="txtobservacion" cols="80" rows="2" id="txtobservacion" onKeyPress="return(validaCajas(this,'x',event))"  onKeyDown="textCounter(this,254)" ><? print $ls_observacion ?></textarea>
          </label></td>
    </tr>


      <tr class="formato-blanco">
        <td colspan="7"></td>
      </tr>
      <tr class="formato-blanco">
        <td height="76" colspan="7"><div align="center">
          <table width="611" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr class="formato-blanco">
              <td width="14" height="11">&nbsp;</td>
              <td width="593"><a href="javascript:ue_catproducto();"></a><a href="javascript:ue_catproducto();"></a></td>
            </tr>
            <tr align="center" class="formato-blanco">
              <td height="11" colspan="2"><?php $io_grid->makegrid($li_filasdevolucion,$la_columdevolucion,$la_objectdevolucion,$li_anchodevolucion,$ls_titulodevolucion,$ls_nametable);?>              <p>&nbsp;</p></td>
              <input name="filasdevolucion" type="hidden" id="filasdevolucion" value="<? print $li_filasdevolucion;?>">
          <input name="hidremoverdevolucion" type="hidden" id="hidremoverdevolucion" value="">
    </tr>
  </table>
  </div>  </td>
  </tr>
  <tr class="formato-blanco">
    <td height="23" colspan="6"><div align="right">Sub-Total</div></td>
    <td width="269"><input name="txtsubtot" type="text" id="txtsubtot"  style="text-align: right" value="<? print $ls_mondev ?>" size="20" maxlength="20" readonly="true"></td>
  </tr>
  <tr class="formato-blanco">
    <td height="23" colspan="6"><div align="right">IVA</div></td>
    <td><input name="txtmoniva" type="text" id="txtmoniva" style="text-align:right"  value="<?php print $ls_moniva ?>" size="20" maxlength="20" readonly="true" onChange="javascript:ue_calretencion();"></td>
  </tr>
  <tr class="formato-blanco">
    <td height="23" colspan="6"><div align="right">Total</div></td>
    <td><input name="txtmonto" type="text" id="txtmonto"  style="text-align: right" value="<? print $ls_monto ?>" size="20" maxlength="20"  readonly="true"></td>
  </tr>



  <tr class="formato-blanco">
    <td colspan="7">&nbsp;</td>
  </tr>
  </table>
    <div align="center"></div>
  </form>
</body>
<script language="javascript">

function ue_cargardevolucion(coddev,razcli,fecdev,mondev,codcli,numfac,obsdev,estdev,numcon,dentie,codtiend)
{
	f=document.form1;

	f.txtcodtienda.value=codtiend;
	f.txttienda.value=dentie;
	f.txtcodcli.value=codcli;
	f.txtnomcli.value=razcli;
	f.txtfecemi.value=fecdev;
	f.txtnumfac.value=numfac;
	f.txtcoddev.value=coddev;
	f.txtnumcont.value=numcon;
	f.txtestdev.value=estdev;
	f.txtobservacion.value=obsdev;
	f.txtmonto.value=mondev;
	f.operacion.value="ue_cargardevolucion";
	f.hidstatus.value="C";
	f.action="sigesp_sfc_d_devolucion.php";

	f.submit();
}

function ue_ver()
{

  f=document.form1;
  f.operacion.value="VER";
  f.action="sigesp_sfc_d_devolucion.php";
  f.submit();

}

function ue_buscar_factura()
{
   f=document.form1;
   f.operacion.value="";
   pagina="sigesp_cat_facturadev.php";
   popupWin(pagina,"catalogo",550,350);
}

function ue_buscar()
{
  f=document.form1;
  li_leer=f.leer.value;
  if(li_leer==1)
   {
     f.operacion.value="";
     pagina="sigesp_cat_devolucion.php";
     popupWin(pagina,"catalogo",650,400);

   }
	else
	{
	  alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_cargarfactura(codigo,numfac,cotizacion,fecemi,conpag,monto,estfac,nombre,ls_codtie,ls_tienda)
{
			f=document.form1;
			f.txtcodtienda.value=ls_codtie;
			f.txttienda.value=ls_tienda;
            f.txtnumfac.value=numfac;
			f.txtcodcli.value=codigo;
			f.txtnomcli.value=nombre;
			f.txtsubtot.value="0,00";
			f.operacion.value="ue_cargarfacturas";
			f.action="sigesp_sfc_d_devolucion.php";
			f.submit();
}

function ue_cargarcliente(codcli,nomcli,dircli,telcli,celcli,codpai,codest,codmun,codpar) //hh
{
            f=document.form1;
			f.txtcodcli.value=codcli;
 		    f.txtnomcli.value=nomcli;
}

function ue_catclientefac()
{
            f=document.form1;
			f.operacion.value="";
			pagina="sigesp_cat_cliente.php";
	    	popupWin(pagina,"catalogo",520,350);
}


function ue_guardar()
{
	f=document.form1;

	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidstatus.value;
 	if (ue_valida_null(txtcoddev,"C�digo devoluci�n")==false)
	{
		txtcoddev.focus();
	}
	else
	{

		var codtienda = "<?php echo $ls_codtienda; ?>";
		var codtie = "<?php echo $ls_codtie; ?>";


		if (codtienda!=codtie)
		 {
		 	alert("No puede realizar esta Operaci�n, Solo puede Consultar");

		 }
		else
		{
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

						else if (ue_valida_null(txtfecemi,"Fecha")==false)
						 {
							  txtfecemi.focus();
						 }
						 else if (ue_valida_null(txtnumfac,"No. de factura")==false)
						 {
							  txtnumfac.focus();
						 }
						else if (ue_valida_null(txtsubtot,"Monto")==false)
						 {
							  alert("Debe incluir cantidad a devolver.");
						 }
						 else if (ue_valida_null(txtcoddev,"C�digo devoluci�n")==false)
						 {
							  txtcoddev.focus();
						 }
						  else if (f.txtestdev.value=="E")
						 {
							   alert("Devoluci�n Emitida no puede ser Modificada!!");
						 }
						 else
						 {
						    band=ue_validar_cantidades();
							if(band){
							f.operacion.value="ue_guardar";
							f.action="sigesp_sfc_d_devolucion.php";
							f.submit();
							}
							else{
							  alert("verifique las cantidades introducidas!!!");
							}
						 }

					}
			}else
			{
				alert("No tiene permiso para realizar esta operacion");
			}

		}
	}
}

function ue_validar_cabecera()
{
    suiche=true;
	f=document.form1;
	with(f)
		{
			if (ue_valida_null(txtcodcli,"Cliente")==false)
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
      li_incluir=f.incluir.value;
      if(li_incluir==1)
       {

	    f.operacion.value="ue_nuevo";
		f.txtnumfac.value="";
		f.txtfecemi.value="";
		f.txtcodcli.value="";
		f.txtestdev.value="";
		f.txtnomcli.value="";
		f.txtobservacion.value="";
	    f.filasdevolucion.value="";
		f.hidremoverdevolucion.value="";

	     f.action="sigesp_sfc_d_devolucion.php";
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
	ld_subtotal=0;
	f.txtsubtot.value=0;
	ld_iva=0;
	ld_monto=0;
	ls_cero="0,00";
	li_filasdevolucion=f.filasdevolucion.value;

	suiche=true;
	for(li_i=1;li_i<=li_filasdevolucion;li_i++)
	{

	   if(eval("f.txtcandev"+li_i+".value")=="")
		  {
		   ld_candev=0;
		  }
	   else
		  {
		   ld_candev=parseFloat(uf_convertir_monto(eval("f.txtcandev"+li_i+".value")));
		   ld_canpro=parseFloat(uf_convertir_monto(eval("f.txtcanpro"+li_i+".value")));
		   ld_moncar=parseFloat(eval("f.txtporcar"+li_i+".value"));
		    if (ld_candev>ld_canpro)
			  {
			    alert("La cantidad a devolver no puede ser mayor a la cantidad comprada!");
			    eval("f.txtcandev"+li_i+".value='0,00'");
			    f.txtsubtot.value="0,00";
			  }
			  else
			  {
				 //tomando el precio unitario de la partida
			     if(eval("f.txtprepro"+li_i+".value")=="")
				  {
				   ld_prepro=0;
				  }
				  else
				  {
					 ld_prepro=parseFloat(uf_convertir_monto(eval("f.txtprepro"+li_i+".value")));
					 ld_txtsubtotal=parseFloat(uf_convertir_monto(f.txtsubtot.value));
				  }
				  ld_moncar=ld_moncar/100;
                              //if (ld_candev > 0)    {
                                  ld_totpar=ld_prepro*ld_candev;
                                  ld_iva=ld_iva + ((ld_moncar*ld_prepro)*ld_candev);
                                  ld_subtotal=ld_subtotal+ld_totpar;
                                  ld_monto=ld_subtotal+ld_iva;
                              /*}else {
                                  ld_totpar=ld_prepro*ld_canpro;
                                  ld_iva=ld_iva + ((ld_moncar*ld_prepro)*ld_canpro);
                                  ld_subtotal=ld_subtotal+ld_totpar;
                                  ld_monto=ld_subtotal+ld_iva;
                              }*/


		     }
	    }
	}
	f.txtsubtot.value=uf_convertir(roundNumber(ld_subtotal));
	f.txtmoniva.value=uf_convertir(ld_iva);
	f.txtmonto.value=uf_convertir(roundNumber(ld_monto));

}

function ue_validar_cantidades()
{

    f=document.form1;
	li_filasdevolucion=f.filasdevolucion.value;
	suiche=true;
	for(li_i=1;li_i<=li_filasdevolucion;li_i++)
	{

	   if(eval("f.txtcandev"+li_i+".value")=="")
		  {
		   ld_candev=0;
		  }
	   else
		  {
		   ld_candev=parseFloat(uf_convertir_monto(eval("f.txtcandev"+li_i+".value")));
		   ld_canpro=parseFloat(uf_convertir_monto(eval("f.txtcanpro"+li_i+".value")));
		   if (ld_candev>ld_canpro)
			  {
			    suiche=false;
			  }
		  }
	}

	return suiche;
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

	return false;
}

function editar_codigo(){

	f=document.form1;
	ls_nrofactura=f.txtcoddev.value;
	ls_nrocontrol=f.txtnumcont.value;
	f.operacion.value="";
	pagina="sigesp_cat_editar.php?ls_nrofactura="+ls_nrofactura+"&ls_nrocontrol="+ls_nrocontrol;
	popupWin(pagina,"catalogo",600,150);

}

function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;

	if(li_eliminar==1)
	{
	  if (f.txtnumfac.value=="")
	  {
		alert("Debe seleccionar la Devoluci�n a Anular!!");
	  }
	  else
	  {
		   var codtienda = "<?php echo $ls_codtienda; ?>";
		   var codtie = "<?php echo $ls_codtie; ?>";

			if (codtienda!=codtie)
			 {
			 	alert("No puede realizar esta Operaci�n, Solo puede Consultar");

			 }
			else
			{

			    si=confirm("�Esta seguro que desea Anular este registro?");
				 if(si)
				 {
					f.action="sigesp_sfc_d_devolucion.php";
					f.operacion.value="ue_anular";
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
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>