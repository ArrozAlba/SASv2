<?php
 /////////////////////////////////////////////////////////////////////////////////////////////
 // Vista:       - sigesp_sfc_d_pedido
 // Autor:       - Ing. Zulheymar Rodrï¿½guez
 // Fecha:       - 28/08/2007
 //////////////////////////////////////////////////////////////////////////////////////////
/************************************************************************************************************************/
/***********************************  INICIO DE LA PAGINA E INICIO DE SESION   ******************************************/
/************************************************************************************************************************/
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "location.href='sigesp_conexion.php'";
	print "</script>";
}
$la_item1=$_SESSION["ls_item"];
$la_datemp=$_SESSION["la_empresa"];
$ls_codcaj=$_SESSION["ls_codcaj"];
$ls_codtie = $_SESSION["ls_codtienda"];
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
<title>Pedido</title>
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
.Estilo1 {
	font-family: Tahoma, Verdana, Arial;
	color: #6699CC;
}
-->
</style><meta http-equiv="Content-Type" content="text/html; charset="></head>
<body link="#006699" vlink="#006699" alink="#006699" >
<?Php
//********************************************         SEGURIDAD       ****************************************************
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SFC";
	$ls_ventanas="sigesp_sfc_d_pedido.php";

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
require_once("class_folder/sigesp_sfc_c_pedido.php");
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
$io_pedido=new  sigesp_sfc_c_pedido();
$ls_codusu=$_SESSION["la_logusr"];


/***********************************************************************************************
/                                    GRID DETALLE PEDIDO                                       *
/***********************************************************************************************/
$ls_titulopedido="Detalle";
$li_anchopedido=600;
$ls_nametable="grid2";
$la_columpedido[1]="Código";
$la_columpedido[2]="Descripción";
$la_columpedido[3]="Proveedor";
$la_columpedido[4]="Cantidad";
$la_columpedido[5]="Edicion";
$ls_item=$la_item1["item"];
$ls_codemp=$la_datemp["codemp"];
/***************************************************************************************
/  										  SUBMIT  									   *
/***************************************************************************************/
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion1=$_POST["operacion1"];
	$ls_operacion=$_POST["operacion"];
	$ls_numped=$_POST["txtnumped"];
	$ls_fecped=$_POST["txtfecped"];
	$ls_codtie=$_POST["txtcodtie"];
	$ls_dentie=$_POST["txtdentie"];
	$ls_codunidad=$_POST["txtcodunidad"];
	$ls_denunidad=$_POST["txtdenunidad"];
	$ls_obsped=$_POST["txtobsped"];
	$ls_codusu=$_SESSION["la_logusr"];
	$li_filaspedido=$_POST["filaspedido"];
    $li_removerpedido=$_POST["hidremoverpedido"];
	$ls_estped=$_POST["txtestped"];
	$ls_hidstatus=$_POST["hidstatus"];

	$ls_codtienda=$_SESSION["ls_codtienda"];


	if ($ls_operacion != "ue_cargarpedido" && $ls_operacion != "ue_removerconcepto")
	{
		for($li_i=1;$li_i<$li_filaspedido;$li_i++)
		{
		$ls_codpro=$_POST["txtcodpro".$li_i];
		$ls_denpro=$_POST["txtdenpro".$li_i];
		$ls_denproveedor=$_POST["txtdenproveedor".$li_i];
		$ls_codproveedor=$_POST["txtcodproveedor".$li_i];
		$ls_canpro=$_POST["txtcanpro".$li_i];
		$ls_codalm=$_POST["txtcodalm".$li_i];

		$la_objectpedido[$li_i][1]="<input name=txtcodpro".$li_i." type=text id=txtcodpro".$li_i." value='".$ls_codpro."' class=sin-borde size=25 style= text-align:center readonly><input name=txtcodalm".$li_i." type=hidden id=txtcodalm".$li_i." value='".$ls_codalm."'>";
		$la_objectpedido[$li_i][2]="<input name=txtdenpro".$li_i." type=text id=txtdenpro".$li_i." value='".$ls_denpro."' class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectpedido[$li_i][3]="<input name=txtdenproveedor".$li_i." type=text id=txtdenproveedor".$li_i." value='".$ls_denproveedor."' class=sin-borde size=45 style= text-align:left readonly> <input name=txtcodproveedor".$li_i." type=text id=txtcodproveedor".$li_i." value='".$ls_codproveedor."' class=sin-borde size=45 style= text-align:left readonly>";
		if (($ls_estped=="" or $ls_estped=="E") && $ls_operacion1!="ue_guardar")
		{
		$la_objectpedido[$li_i][4]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event))>";
		}
		else
		{
		$la_objectpedido[$li_i][4]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=15 style= text-align:center readonly>";
		}
		if (($ls_estped==""or $ls_estped=="E"))
		{
		$la_objectpedido[$li_i][5]="<a href=javascript:ue_removerconcepto(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}
		else
		{
		$la_objectpedido[$li_i][5]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center>";
		}
	}
		$la_objectpedido[$li_filaspedido][1]="<input name=txtcodpro".$li_filaspedido." type=text id=txtcodpro".$li_filaspedido." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodalm".$li_filaspedido." type=hidden id=txtcodalm".$li_filaspedido.">";
		$la_objectpedido[$li_filaspedido][2]="<input name=txtdenpro".$li_filaspedido." type=text id=txtdenpro".$li_filaspedido." class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectpedido[$li_filaspedido][3]="<input name=txtdenproveedor".$li_filaspedido." type=text id=txtdenproveedor".$li_filaspedido." class=sin-borde size=45 style= text-align:left readonly> <input name=txtcodproveedor".$li_filaspedido." type=text id=txtcodproveedor".$li_filaspedido." class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectpedido[$li_filaspedido][4]="<input name=txtcanpro".$li_filaspedido." type=text id=txtcanpro".$li_filaspedido." class=sin-borde size=15 style= text-align:center >";
		$la_objectpedido[$li_filaspedido][5]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
	}
}
/************************************************************************************
/							    NO SUBMIT                                           *     /************************************************************************************/
else
{
   	$ls_operacion="";
	$ls_numped="";
	$ls_fecped="";
	$ls_fecped=date('d/m/Y');
	$ls_obsped="";
	$ls_estped="";
	$ls_hidstatus="";
	$li_filaspedido=1;
    $la_objectpedido[$li_filaspedido][1]="<input name=txtcodpro".$li_filaspedido." type=text id=txtcodpro".$li_filaspedido." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodalm".$li_filaspedido." type=hidden id=txtcodalm".$li_filaspedido.">";
	$la_objectpedido[$li_filaspedido][2]="<input name=txtdenpro".$li_filaspedido." type=text id=txtdenpro".$li_filaspedido." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectpedido[$li_filaspedido][3]="<input name=txtdenproveedor".$li_filaspedido." type=text id=txtdenproveedor".$li_filaspedido." class=sin-borde size=45 style= text-align:left readonly> <input name=txtcodproveedor".$li_filaspedido." type=text id=txtcodproveedor".$li_filaspedido." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectpedido[$li_filaspedido][4]="<input name=txtcanpro".$li_filaspedido." type=text id=txtcanpro".$li_filaspedido." class=sin-borde size=15 style= text-align:center >";
	$la_objectpedido[$li_filaspedido][5]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
}

/************************************************************************************************************************
/*								  	NUEVO --> PREPARANDO NUEVA INSERCION DE PEDIDO 										* /************************************************************************************************************************/
if($ls_operacion=="ue_nuevo")
{


    require_once("../shared/class_folder/class_funciones_db.php");
	require_once ("../shared/class_folder/sigesp_include.php");
	$io_include=new sigesp_include();
	$io_connect=$io_include->uf_conectar();
	$io_funcdb=new class_funciones_db($io_connect);
	$la_empresa=$_SESSION["la_empresa"];
	$ls_codcaj=$_SESSION["ls_codcaj"];
	$ls_prefijo="PED";
	$io_secuencia->uf_ver_secuencia($ls_codcaj.$ls_codtie."ped",&$ls_secuencia);
	$ls_secuencia=$io_function->uf_cerosizquierda($ls_secuencia,16);
	$ls_numped=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);
	$ls_operacion1="ue_nuevo";
	$ls_operacion="";
	$ls_fecped="";
	$ls_fecped=date('d/m/Y');
	$ls_obsped="";
	$ls_estped="";


if ($ls_codtienda=='')
{	$ls_sql="SELECT caj.codtiend,caj.nomtie,tie.coduniadm,ue.denuniadm FROM sfc_cajero caj,sfc_tienda tie,spg_unidadadministrativa ue " .
		"WHERE caj.codusu='".$ls_codusu."' AND tie.codtiend=caj.codtiend AND ue.coduniadm=tie.coduniadm;";
}
else
 {

 	$ls_sql="SELECT caj.codtiend,caj.nomtie,tie.coduniadm,ue.denuniadm FROM sfc_cajero caj,sfc_tienda tie,spg_unidadadministrativa ue " .
		"WHERE caj.codusu='".$ls_codusu."' AND tie.codtiend=caj.codtiend AND ue.coduniadm=tie.coduniadm AND tie.codtiend='".$ls_codtienda."' AND caj.codtiend='".$ls_codtienda."';";


 }
//print $ls_sql;

	$rs_datauni=$io_sql->select($ls_sql);
	$la_pedido=$io_sql->obtener_datos($rs_datauni);
	$io_datastore->data=$la_pedido;
    $ls_codtie=$io_datastore->getValue("codtiend",1);//
	$ls_dentie=$io_datastore->getValue("nomtie",1);//
	$ls_codunidad=$io_datastore->getValue("coduniadm",1);
	$ls_denunidad=$io_datastore->getValue("denuniadm",1);


	$li_filaspedido=1;
    $la_objectpedido[$li_filaspedido][1]="<input name=txtcodpro".$li_filaspedido." type=text id=txtcodpro".$li_filaspedido." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodalm".$li_filaspedido." type=hidden id=txtcodalm".$li_filaspedido.">";
	$la_objectpedido[$li_filaspedido][2]="<input name=txtdenpro".$li_filaspedido." type=text id=txtdenpro".$li_filaspedido." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectpedido[$li_filaspedido][3]="<input name=txtdenproveedor".$li_filaspedido." type=text id=txtdenproveedor".$li_filaspedido." class=sin-borde size=45 style= text-align:left readonly> <input name=txtcodproveedor".$li_filaspedido." type=text id=txtcodproveedor".$li_filaspedido." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectpedido[$li_filaspedido][4]="<input name=txtcanpro".$li_filaspedido." type=text id=txtcanpro".$li_filaspedido." class=sin-borde size=15 style= text-align:center >";
	$la_objectpedido[$li_filaspedido][5]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
}
/************************************************************************************************************************/
/*							CARGAR UN CONCEPTO(PRODUCTO) EN GRID DESDE CATALOGO PRODUCTO 								*
/************************************************************************************************************************/
elseif($ls_operacion=="ue_cargarconcepto")
{


	$li_filaspedido=$_POST["filaspedido"];
	$li_filaspedido++;

	for($li_i=1;$li_i<$li_filaspedido;$li_i++)
	{
		$ls_codpro=$_POST["txtcodpro".$li_i];
		$ls_denpro=$_POST["txtdenpro".$li_i];
		$ls_denproveedor=$_POST["txtdenproveedor".$li_i];
		$ls_codproveedor=$_POST["txtcodproveedor".$li_i];
		$ls_canpro=$_POST["txtcanpro".$li_i];
		$ls_codalm=$_POST["txtcodalm".$li_i];

		$la_objectpedido[$li_i][1]="<input name=txtcodpro".$li_i." type=text id=txtcodpro".$li_i." value='".$ls_codpro."' class=sin-borde size=25 style= text-align:center readonly><input name=txtcodalm".$li_i." type=hidden id=txtcodalm".$li_i." value='".$ls_codalm."'>";
		$la_objectpedido[$li_i][2]="<input name=txtdenpro".$li_i." type=text id=txtdenpro".$li_i." value='".$ls_denpro."' class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectpedido[$li_i][3]="<input name=txtdenproveedor".$li_i." type=text id=txtdenproveedor".$li_i." value='".$ls_denproveedor."' class=sin-borde size=45 style= text-align:left readonly> <input name=txtcodproveedor".$li_i." type=text id=txtcodproveedor".$li_i." value='".$ls_codproveedor."' class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectpedido[$li_i][4]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event))>";
		$la_objectpedido[$li_i][5]="<a href=javascript:ue_removerconcepto(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
	}
	$la_objectpedido[$li_filaspedido][1]="<input name=txtcodpro".$li_filaspedido." type=text id=txtcodpro".$li_filaspedido." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodalm".$li_filaspedido." type=hidden id=txtcodalm".$li_filaspedido.">";
	$la_objectpedido[$li_filaspedido][2]="<input name=txtdenpro".$li_filaspedido." type=text id=txtdenpro".$li_filaspedido." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectpedido[$li_filaspedido][3]="<input name=txtdenproveedor".$li_filaspedido." type=text id=txtdenproveedor".$li_filaspedido." class=sin-borde size=45 style= text-align:left readonly> <input name=txtcodproveedor".$li_filaspedido." type=text id=txtcodproveedor".$li_filaspedido." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectpedido[$li_filaspedido][4]="<input name=txtcanpro".$li_filaspedido." type=text id=txtcanpro".$li_filaspedido." class=sin-borde size=15 style= text-align:center >";
	$la_objectpedido[$li_filaspedido][5]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
}

/************************************************************************************************************************
/*							 CARGAR CONCEPTO(PRODUCTO) EN GRID DESDE CATALOGO PEDIDO	 								*
/************************************************************************************************************************/

elseif ($ls_operacion=="ue_cargarconcepto_pedido")
{

	$li_filaspedido=1;
		$la_objectpedido[$li_filaspedido][1]="<input name=txtcodpro".$li_filaspedido." type=text id=txtcodpro".$li_filaspedido." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodalm".$li_filaspedido." type=hidden id=txtcodalm".$li_filaspedido.">";
		$la_objectpedido[$li_filaspedido][2]="<input name=txtdenpro".$li_filaspedido." type=text id=txtdenpro".$li_filaspedido." class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectpedido[$li_filaspedido][3]="<input name=txtdenproveedor".$li_filaspedido." type=text id=txtdenproveedor".$li_filaspedido." class=sin-borde size=45 style= text-align:left readonly><input name=txtcodproveedor".$li_filaspedido." type=text id=txtcodproveedor".$li_filaspedido." class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectpedido[$li_filaspedido][4]="<input name=txtcanpro".$li_filaspedido." type=text id=txtcanpro".$li_filaspedido." class=sin-borde size=15 style= text-align:center >";
		$la_objectpedido[$li_filaspedido][5]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
$ls_cadena="SELECT sfc_detpedido.cod_pro,sfc_detpedido.codart,sfc_detpedido.cantped,sfc_detpedido.codalm,sim_articulo.denart,rpc_proveedor.cod_pro as codproveedor,rpc_proveedor.nompro as denproveedor FROM
sfc_detpedido,sim_articulo,rpc_proveedor WHERE sfc_detpedido.codart=sim_articulo.codart AND sfc_detpedido.numpedido='".$ls_numped."' AND rpc_proveedor.cod_pro=sfc_detpedido.cod_pro AND sfc_detpedido.codtiend='".$ls_codtie."' ORDER BY sim_articulo.denart ASC;";/*".$ls_numcot."*/
//print $ls_cadena;
			$arr_detpedido=$io_sql->select($ls_cadena);
			if($arr_detpedido==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros de productos");
			}
			else
			{
				if($row=$io_sql->fetch_row($arr_detpedido))
 				  {
					$la_producto=$io_sql->obtener_datos($arr_detpedido);
					$io_datastore->data=$la_producto;
					$totrow=$io_datastore->getRowCount("codart");

					for($li_i=1;$li_i<=$totrow;$li_i++)
					{
						$ls_codpro=$io_datastore->getValue("codart",$li_i);
						$ls_denpro=$io_datastore->getValue("denart",$li_i);
						$ls_denproveedor=$io_datastore->getValue("denproveedor",$li_i);
						$ls_codproveedor=$io_datastore->getValue("codproveedor",$li_i);
						$ls_canpro=$io_datastore->getValue("cantped",$li_i);
						$ls_codalm=$io_datastore->getValue("codalm",$li_i);
						$ls_canpro=number_format($ls_canpro,2, ',', '.');
		$la_objectpedido[$li_i][1]="<input name=txtcodpro".$li_i." type=text id=txtcodpro".$li_i." value='".$ls_codpro."' class=sin-borde size=25 style= text-align:center readonly><input name=txtcodalm".$li_i." type=hidden id=txtcodalm".$li_i." value='".$ls_codalm."'>";
		$la_objectpedido[$li_i][2]="<input name=txtdenpro".$li_i." type=text id=txtdenpro".$li_i." value='".$ls_denpro."' class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectpedido[$li_i][3]="<input name=txtdenproveedor".$li_i." type=text id=txtdenproveedor".$li_i." value='".$ls_denproveedor."' class=sin-borde size=45 style= text-align:left readonly> <input name=txtcodproveedor".$li_i." type=text id=txtcodproveedor".$li_i." value='".$ls_codproveedor."' class=sin-borde size=45 style= text-align:left readonly>";
		if ($ls_estcot=="" or $ls_estcot=="E")
		{
		$la_objectpedido[$li_i][4]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event))>";
		}
		else
		{
		$la_objectpedido[$li_i][4]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=15 style= text-align:center readonly>";
		}
if ($ls_estcot=="" or $ls_estcot=="E")
		{
		$la_objectpedido[$li_i][5]="<a href=javascript:ue_removerconcepto(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}
		else
		{
		$la_objectpedido[$li_i][5]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center>";
		}
	}
	$li_filaspedido=$li_i;
	$la_objectpedido[$li_filaspedido][1]="<input name=txtcodpro".$li_filaspedido." type=text id=txtcodpro".$li_filaspedido." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodalm".$li_filaspedido." type=hidden id=txtcodalm".$li_filaspedido.">";
	$la_objectpedido[$li_filaspedido][2]="<input name=txtdenpro".$li_filaspedido." type=text id=txtdenpro".$li_filaspedido." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectpedido[$li_filaspedido][3]="<input name=txtdenproveedor".$li_filaspedido." type=text id=txtdenproveedor".$li_filaspedido." class=sin-borde size=45 style= text-align:left readonly> <input name=txtcodproveedor".$li_filaspedido." type=text id=txtcodproveedor".$li_filaspedido." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectpedido[$li_filaspedido][4]="<input name=txtcanpro".$li_filaspedido." type=text id=txtcanpro".$li_filaspedido." class=sin-borde size=15 style= text-align:center >";
	$la_objectpedido[$li_filaspedido][5]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
   		}
	}
}
/************************************************************************************************************************
/*										REMOVER --> ELIMINAR UN PRODUCTO EN GRID 										*
/************************************************************************************************************************/
elseif($ls_operacion=="ue_removerconcepto")
{
  	$li_filaspedido=$_POST["filaspedido"];
	$li_filaspedido=$li_filaspedido - 1;
	$li_removerpedido=$_POST["hidremoverpedido"]; /*** hid(oculto)+removerconcepto*/
	$li_temp=0;
	for($li_i=1;$li_i<=$li_filaspedido;$li_i++)
	{
		if ($li_i!=$li_removerpedido)
		{
		 $li_temp=$li_temp+1;
  		$ls_codpro=$_POST["txtcodpro".$li_i];
		$ls_denpro=$_POST["txtdenpro".$li_i];
		$ls_denproveedor=$_POST["txtdenproveedor".$li_i];
		$ls_codproveedor=$_POST["txtcodproveedor".$li_i];
		$ls_canpro=$_POST["txtcanpro".$li_i];
		$ls_codalm=$_POST["txtcodalm".$li_i];
		$la_objectpedido[$li_temp][1]="<input name=txtcodpro".$li_temp." type=text id=txtcodpro".$li_temp." value='".$ls_codpro."' class=sin-borde size=15 style= text-align:center readonly><input name=txtcodalm".$li_i." type=hidden id=txtcodalm".$li_i." value='".$ls_codalm."'>";
		$la_objectpedido[$li_temp][2]="<input name=txtdenpro".$li_temp." type=text id=txtdenpro".$li_temp." value='".$ls_denpro."' class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectpedido[$li_temp][3]="<input name=txtdenproveedor".$li_temp." type=text id=txtdenproveedor".$li_temp." value='".$ls_denproveedor."' class=sin-borde size=45 style= text-align:left readonly><input name=txtcodproveedor".$li_temp." type=text id=txtcodproveedor".$li_temp." value='".$ls_codproveedor."' class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectpedido[$li_temp][4]="<input name=txtcanpro".$li_temp." type=text id=txtcanpro".$li_temp." value='".$ls_canpro."' class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event))>";
		$la_objectpedido[$li_temp][5]="<a href=javascript:ue_removerconcepto(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}
	}
        $la_objectpedido[$li_filaspedido][1]="<input name=txtcodpro".$li_filaspedido." type=text id=txtcodpro".$li_filaspedido."  class=sin-borde size=15 style= text-align:center readonly><input name=txtcodalm".$li_filaspedido." type=hidden id=txtcodalm".$li_filaspedido.">";
		$la_objectpedido[$li_filaspedido][2]="<input name=txtdenpro".$li_filaspedido." type=text id=txtdenpro".$li_filaspedido."  class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectpedido[$li_filaspedido][3]="<input name=txtdenproveedor".$li_filaspedido." type=text id=txtdenproveedor".$li_filaspedido." class=sin-borde size=45 style= text-align:left readonly><input name=txtcodproveedor".$li_filaspedido." type=text id=txtcodproveedor".$li_filaspedido." class=sin-borde size=45 style= text-align:left readonly> ";
		$la_objectpedido[$li_filaspedido][4]="<input name=txtcanpro".$li_filaspedido." type=text id=txtcanpro".$li_filaspedido."  class=sin-borde size=15 style= text-align:center >";
		$la_objectpedido[$li_filaspedido][5]="<input name=txtvacio".$li_filaspedido." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly >";
}
/************************************************************************************************************************
/*										GUARDAR -->   GUARDA NUEVA COTIZACION 											*
/************************************************************************************************************************/
elseif($ls_operacion=="ue_guardar")
{
    $lb_existe=$io_pedido->uf_select_pedido($ls_numped,$ls_codtie);
	if ($lb_existe and $ls_operacion1!="ue_nuevo"){}
	elseif ($ls_operacion1=="ue_nuevo"){
	$ls_codcaj=$_SESSION["ls_codcaj"];
	$ls_prefijo="PED";
	$io_secuencia->uf_obtener_secuencia($ls_codcaj.$ls_codtie."ped",&$ls_secuencia);
	$ls_secuencia=$io_function->uf_cerosizquierda($ls_secuencia,16);
	$ls_numped=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);
	 }
   	   $la_detalles["codart"][1]="";
   	   $la_detalles["canpro"][1]="";
   	   $la_detalles["codproveedor"][1]="";
	   $la_detalles["codalm"][1]="";
	   $cantidad_pro=true;
    for ($li_i=1;$li_i<$li_filaspedido;$li_i++)
     {
	   $la_detalles["codart"][$li_i]=$_POST["txtcodpro".$li_i];
	   $la_detalles["canpro"][$li_i]=$_POST["txtcanpro".$li_i];
	   $la_detalles["codproveedor"][$li_i]=$_POST["txtcodproveedor".$li_i];
	   $la_detalles["codalm"][$li_i]=$_POST["txtcodalm".$li_i];
	   $la_detalles["codtiend"][$li_i]=$_POST["txtcodtie"];
	   if ($la_detalles["canpro"][$li_i]=="")
	    {
		  $io_msg->message ("Debe colocar la cantidad del producto solicitado!!!");
		  $cantidad_pro=false;
		}
	 }
if ($cantidad_pro==true)
 {
      $ls_sql="SELECT * FROM sfc_pedido WHERE codemp='".$ls_codemp."' AND numpedido='".$ls_numped."' AND codtiend='".$ls_codtie."';";
	  $rs_datauni=$io_sql->select($ls_sql);
	  $la_pedido=$io_sql->obtener_datos($rs_datauni);
	  $io_datastore->data=$la_pedido;
      $ls_estped=$io_datastore->getValue("estped",1);
	   if ($ls_estped=="E")
	     {
	      // $io_msg->message ("ï¿½Cotizaciï¿½n EMITIDA!");
	     }
	   elseif ($ls_estped=="P")
	     {
		  $io_msg->message ("¡PEDIDO EN PROCESO no se puede modificar!");
		 }
		 elseif ($ls_estped=="F")
		 {
		  $io_msg->message ("¡PEDIDO ORDENADO no se puede modificar!");
		 }
		 else
		 {
		  $ls_estped="E";
		 }
	   if ($ls_estped=="E")
	   {

			if ($lb_existe and $ls_operacion1!="ue_nuevo")
			{
			 $io_msg->message ("El PEDIDO con el codigo: ".$ls_numped." fue actualizado satisfactoriamente");
			}
			elseif ($ls_operacion1=="ue_nuevo")
			{
				$ls_operacion1="";
			  	$io_msg->message ("El PEDIDO sera guardado con el codigo: ".$ls_numped);
		  	}
		  	//print "ENTRE";
	 	   $lb_valido=$io_pedido->uf_guardar_pedido($ls_codtie,$ls_numped,$ls_codusu,$ls_fecped,$ls_obsped,$ls_estped,$la_seguridad,$ls_hidstatus);
	 	   $lb_valido=$io_pedido->uf_update_detallespedidos($ls_numped,$ls_codtie,$la_detalles,$li_filaspedido,$la_seguridad,$ls_hidstatus);
	       $ls_mensaje=$io_pedido->io_msgc;
	       if($lb_valido==true)
			 {
				$io_msg->message ($ls_mensaje);
			 }
			else
			 {
				if($lb_valido==0)
				 {
				 }
				else
				 {
					$io_msg->message ($ls_mensaje);
				 }
			 }
		}
	 }
	}
/************************************************************************************************************************
/*										REMOVER --> ELIMINAR UN PEDIDO EN BD 											*
/************************************************************************************************************************/
elseif($ls_operacion=="ue_anular")
{
$ls_sql="SELECT * FROM sfc_pedido WHERE codemp='".$ls_codemp."' AND numpedido='".$ls_numped."' AND codtiend='".$ls_codtie."';";

	$rs_datauni=$io_sql->select($ls_sql);
	$la_pedido=$io_sql->obtener_datos($rs_datauni);
	$io_datastore->data=$la_pedido;
    $ls_estped=$io_datastore->getValue("estped",1);//

	if ($ls_estped=="E")
	 {
	      // $io_msg->message ("ï¿½Cotizaciï¿½n EMITIDA!");
	 }
	elseif ($ls_estped=="P")
	 {
	  $io_msg->message ("¡PEDIDO EN PROCESO no se puede modificar!");
	 }
	elseif ($ls_estped=="F")
	 {
	  $io_msg->message ("¡PEDIDO ORDENADO no se puede modificar!");
	 }
	else
	 {
	  $ls_estped="E";
	 }

	if ($ls_estped=="E")
     {

 			   $lb_valido=$io_pedido->uf_delete_detpedido($ls_numped,$ls_codtie,$la_seguridad);

			   if ($lb_valido===true)
					{
							$io_msg->message($io_pedido->io_msgc);

	$li_filaspedido=1;
	$la_objectpedido[$li_filaspedido][1]="<input name=txtcodpro".$li_filaspedido." type=text id=txtcodpro".$li_filaspedido." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodalm".$li_filaspedido." type=hidden id=txtcodalm".$li_filaspedido.">";
	$la_objectpedido[$li_filaspedido][2]="<input name=txtdenpro".$li_filaspedido." type=text id=txtdenpro".$li_filaspedido." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectpedido[$li_filaspedido][3]="<input name=txtdenproveedor".$li_filaspedido." type=text id=txtdenproveedor".$li_filaspedido." class=sin-borde size=45 style= text-align:left readonly> <input name=txtcodproveedor".$li_filaspedido." type=text id=txtcodproveedor".$li_filaspedido." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectpedido[$li_filaspedido][4]="<input name=txtcanpro".$li_filaspedido." type=text id=txtcanpro".$li_filaspedido." class=sin-borde size=15 style= text-align:center >";

	$la_objectpedido[$li_filaspedido][5]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
					}
			   $lb_valido=$io_pedido->uf_delete_pedido($ls_numped,$ls_codtie,$la_seguridad);
				if ($lb_valido===true)
					{
							//$io_msg->message($io_cotizacion->io_msgc);
							$ls_operacion="";
							$ls_numped="";
							$ls_fecped="";
							//$ls_codtie="";
							//$ls_dentie="";
							$ls_obsped="";
							$ls_estped="";
					 }
			 }

}
/******************************************************************************************************************************
/*												 VER REPORTE DE PEDIDO														  *
/******************************************************************************************************************************/
if($ls_operacion=="VER")
{
        $ls_operacion="";
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		///////////////////////////////////CAMBIAR SELECT PARA VER REPORTE PEDIDO/////////////////////////////////////////////
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	    $ls_sql=" SELECT".
		        " caj.nomusu,a.denart,ped.numpedido,ped.codusu,       ".
			    " ped.fecpedido,ped.obspedido,dped.codart,dped.cantped,tie.dentie,tie.riftie,    ".
				" tie.dirtie,tie.teltie,tie.coduniadm,ue.denuniadm".
				" FROM".
				" sfc_producto pro,sim_articulo a,sfc_pedido ped,sfc_detpedido dped,sfc_tienda tie,sfc_cajero caj,spg_unidadadministrativa ue".
				" WHERE".
				" pro.codart=dped.codart AND pro.codart=a.codart AND ped.numpedido=dped.numpedido AND ped.codusu=caj.codusu AND pro.codtiend='".$ls_codtie."' ".
				" AND caj.codtiend=tie.codtiend AND ped.codtiend='".$ls_codtie."' AND dped.codtiend='".$ls_codtie."' AND dped.codtiend=ped.codtiend AND ped.numpedido='".$ls_numped."' AND ue.coduniadm=tie.coduniadm AND tie.codtiend='".$ls_codtie."'".
				" ORDER BY dped.codart ASC;";

//print $ls_sql;
	/**********************************  VERIFICAR QUE EXISTA PEDIDO  ****************************************************/
		$rs_datauni=$io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido_repped=false;
			$io_msg="Error en uf_select_ReportePedido ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_repped=true; //Registro encontrado

			}
			else
			{
				$lb_valido_repped=false; //"Registro no encontrado"
				 $io_msg->message ("El Pedido no esta registrado, regístrelo e intente de nuevo!!!");
			}
		}
	/****************************************************************************************************************************/
	if ($lb_valido_repped==true)
	 {
	  ?>

     <script language="JavaScript">
   	 	var ls_sql="<?php print $ls_sql; ?>";
	   	pagina="reportes/sigesp_sfc_rep_pedido.php?sql="+ls_sql;
	  	popupWin(pagina,"catalogo",580,700);
     </script>

<?PHP
    }
}

?>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="557" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1">Sistema de Facturaci&oacute;n</span></td>
    <td width="221" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
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
   /************************************         SEGURIDAD          ***********************************************/
?>
  <input name="txtcanpro" type="hidden" id="txtcanpro">
  <label>
  <input name="txtitem" type="hidden" id="txtitem" value="<?php print $ls_item ?>">
  </label>
  <table width="780" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">


	  <tr class="titulo-celdanew">
        <th colspan="9" scope="col" class="titulo-celdanew">Pedido</th>
    </tr>
      <tr class="formato-blanco">
        <td  colspan="9">
		<input name="operacion" type="hidden" id="operacion">
		<input name="operacion1" type="hidden" id="operacion1" value="<?php print $ls_operacion1 ?>">
        <input name="opemostrar" type="hidden" id="opemostrar" value="<? print $ls_opemostrar ?>">
        <input name="txtestped" type="hidden" id="txtestped" value="<?php print $ls_estped ?>"></td>
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
		if ($ls_operacion=="ue_guardar" or $ls_estped=="E" or $ls_estped=="F"){
		?>
        <td width="40" height="22"><div align="right"></div></td>
        <td width="77"><div align="right">N&ordm; Pedido </div></td>
        <td>
		<input name="txtnumped" type="text" id="txtnumped" style="text-align:center " value="<? print $ls_numped ?>" size="28" maxlength="25" readonly>
		<?php
		}else{
		?>
		<td width="40" height="22"><div align="right"></div></td>
        <td>
		<input name="txtnumped" type="hidden" id="txtnumped" style="text-align:center " value="<? print $ls_numped ?>" size="28" maxlength="25" readonly>
		<?php
		}
		?>
		<div align="right"></div></td>
        <td colspan="2"><label class="titulo-cat&aacute;logo">
		<?php
		if ($ls_estped=="E")
		{
		?>
		<font color="#3982C6">EMITIDO</font>
		<?php
		}
		elseif ($ls_estped=="P")
		{
		?>
		<font color="#3982C6">EN PROCESO</font>
		<?php
		}
		elseif ($ls_estped=="F")
		{
		?>
		<font color="#3982C6">FACTURADO</font>
		<?php
		}
		elseif ($ls_estped=="")
		{
		?>
		<font color="#3982C6"></font>
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
		<input name="txtfecped" type="text" id="txtfecped"  style="text-align:left" value="<? print $ls_fecped?>" size="11" maxlength="10"   onKeyPress="javascript:ue_validafecha();" datepicker="true" readonly="true">
		 <?php
		 }
		 else
		 {
		 ?>
		 <input name="txtfecped" type="text" id="txtfecped"  style="text-align:left" value="<? print $ls_fecped?>" size="11" maxlength="10"   readonly="true">
		 <?php
		 }
		 ?>

		</td>


      </tr>
       <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right">Unidad Operativa de Suministro</div></td>
        <td colspan="7"><input name="txtcodtie" type="text" id="txtcodtie" style="text-align:center " value="<? print $ls_codtie ?>" size="15" maxlength="10" readonly>
		<input name="txtdentie" type="text" id="txtdentie"  style="text-align:left" class="sin-borde" value="<? print $ls_dentie ?>" size="70" maxlength="100" readonly="true">
		</td>


	</tr>


  <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right">Unidad Ejecutora</div></td>
        <td colspan="7"><input name="txtcodunidad" type="text" id="txtcodunidad" style="text-align:center " value="<? print $ls_codunidad ?>" size="15" maxlength="10" readonly>
		<input name="txtdenunidad" type="text" id="txtdenunidad"  style="text-align:left" class="sin-borde" value="<? print $ls_denunidad ?>" size="70" maxlength="100" readonly="true">        </td></tr>

      <tr class="formato-blanco">
        <td height="22"><div align="right"></div></td>
        <td height="22"><div align="right">Observacion</div></td>
        <td colspan="7">
		<?php
		if ($ls_estcot=="")
		{
		?>
		<textarea name="txtobsped" cols="80" rows="2" id="txtobsped" onKeyPress="return(validaCajas(this,'x',event))"  onKeyDown="textCounter(this,254)" ><? print $ls_obsped ?></textarea>
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
				if ($ls_estped=="" or ($ls_estped=="E"/* and $ls_fecped==date('d/m/Y')*/))
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
              <td height="11" colspan="2"><?php $io_grid->makegrid($li_filaspedido,$la_columpedido,$la_objectpedido,$li_anchopedido,$ls_titulopedido,$ls_nametable);?>
			  </td>
              <input name="filaspedido" type="hidden" id="filaspedido" value="<? print $li_filaspedido;?>">
			  <input name="hidremoverpedido" type="hidden" id="hidremoverpedido" value="">
            </tr>
            <tr class="formato-blanco">
              <td colspan="2">&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>

    </table>
  <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_hidstatus ?>">
  <div align="center"></div>
  </form>
</body>

<script language="javascript">
   /***************************************************************************************************
    ***********************************FUNCIONES JAVASCRIPT********************************************
	***************************************************************************************************/
function ue_validar_cabecera()
{
    suiche=true;
	f=document.form1;
	with(f)
		{
		   if (filaspedido.value>5)
			 {
			 	alert("Ha excedido el nï¿½mero maximo de lï¿½neas en el pedido!")
				suiche=false;
			 }
		   else if (ue_valida_null(txtnumped,"Nï¿½ de Pedido")==false)
			 {
			 	txtnumped.focus();
				suiche=false;
			 }
			else if (ue_valida_null(txtcodtie,"Codigo de Tienda")==false)
			 {
				txtcodtie.focus();
				suiche=false;
			 }
			else if (ue_valida_null(txtfecped,"Fecha")==false)
			 {
				  txtfecped.focus();
				  suiche=false;
			 }
			 else if (ue_valida_null(txtobsped,"Observaciones")==false)
			 {
				  txtobsped.focus();
				  suiche=false;
			 }
		}
	return suiche;
}

function ue_ver()
  {
  f=document.form1;
  f.operacion.value="VER";
  f.action="sigesp_sfc_d_pedido.php";
  f.submit();
  }

function ue_buscar()
{
  f=document.form1;
  li_leer=f.leer.value;
  if(li_leer==1)
   {
     f.operacion.value="";
     pagina="sigesp_cat_pedido.php";
     popupWin(pagina,"catalogo",850,450);
	 window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=350,resizable=yes,location=no");
   }
	else
	{
	  alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_cargarpedido(codtie,numpedido,dentie,fecha,obs,estped,coduniadm,denuniadm)
{
	f=document.form1;
	f.txtnumped.value=numpedido;
	f.txtcodtie.value=codtie;
	f.txtfecped.value=fecha;
	f.txtdentie.value=dentie;
 	f.txtobsped.value=obs;
	f.txtestped.value=estped;
	f.txtcodunidad.value=coduniadm;
	f.txtdenunidad.value=denuniadm;
	f.operacion.value="ue_cargarconcepto_pedido";
	f.hidstatus.value="C";
	f.action="sigesp_sfc_d_pedido.php";
	f.submit();
}

function ue_cargarproducto(codpro,denpro,codalm,codproveedor,denproveedor)
{
    f=document.form1;
	f.operacion.value="ue_cargarconcepto";
	lb_existe=false;
	for(li_i=1;li_i<=f.filaspedido.value && !lb_existe;li_i++)
	{
		ls_codigo=eval("f.txtcodpro"+li_i+".value");
		if(ls_codigo==codpro)
		{
			alert("La Cuenta ya ha sido cargada!!!");
			lb_existe=true;
		}
	}
	if(!lb_existe)
	{
		eval("f.txtcodpro"+f.filaspedido.value+".value='"+codpro+"'");
		eval("f.txtcodalm"+f.filaspedido.value+".value='"+codalm+"'");
		eval("f.txtdenpro"+f.filaspedido.value+".value='"+denpro+"'");
		eval("f.txtdenproveedor"+f.filaspedido.value+".value='"+denproveedor+"'");
		eval("f.txtcodproveedor"+f.filaspedido.value+".value='"+codproveedor+"'");

		suiche_submit=true;
    	f.submit();
	}
}

function ue_catproducto()
{
 f=document.form1;
	if (f.txtestped.value=="F")
	  {
	    alert("PEDIDO ORDENADO no se puede modificar!!!");
	  }
	else if (f.txtestped.value=="P")
	  {
	  alert("PEDIDO esta EN PROCESO no se puede modificar!!!");
	  }
	else
	  {
	  		validar=ue_validar_cabecera();
			if (validar==true)
			{
				f=document.form1;
				f.operacion.value="";
				pagina="sigesp_cat_producto_sinexistencia.php";
				popupWin(pagina,"catalogo",700,500);
			}
	  }
}

function ue_removerconcepto(li_fila)
{
	f=document.form1;
	f.hidremoverpedido.value=li_fila;
	f.operacion.value="ue_removerconcepto";
	f.action="sigesp_sfc_d_pedido.php";
	f.submit();
}

function ue_guardar(li_fila)
{
	f=document.form1;

	var codtienda_busca = "<?php echo $ls_codtie; ?>";
	var codtienda_session = "<?php echo $ls_codtienda; ?>";

	if (codtienda_busca!=codtienda_session)
	{
		 alert("No puede realizar esta Operación, Solo puede Consultar");

	}
	else
	{

		li_incluir=f.incluir.value;
		li_cambiar=f.cambiar.value;
		lb_status=f.hidstatus.value;
		if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
			{
			/*	if (lb_status!="C")
				{
					f.hidstatus.value="C";
				}*/

				with(f)
				{
					if (ue_valida_null(txtnumped,"Número de Pedido")==false)
					 {
					 	txtnumped.focus();
					 }
					else if (ue_valida_null(txtcodtie,"Código de la Tienda")==false)
					 {
						txtcodtie.focus();
					 }
					else if (ue_valida_null(txtfecped,"Fecha")==false)
					 {
						  txtfecped.focus();
					 }
					 else if (ue_valida_null(txtobsped,"Observaciones")==false)
					 {
						  txtobsped.focus();
					 }
					 else if (f.filaspedido.value==1)
					 {
					 	alert("Introduzca un producto");
					 /* for (i=1;i<=cantidad;i++)
					     {

					       if (ue_valida_null(txtcanpro+i,"Cantidad")==false) canpro+i.focus();

						 }

					 /*eval("f.txtcodpro"+li_i+".value")*/
					/* alert("fila="+li_i);

						  /*canpro.focus();*/
					 /*}*/
					/*else
					 {	*/
					 }
					 else
					 {
							f.operacion.value="ue_guardar";
							f.action="sigesp_sfc_d_pedido.php";
							f.submit();
					 }
				}
			}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}
	}
}

function ue_nuevo()
{
  f=document.form1;
  li_incluir=f.incluir.value;
  if(li_incluir==1)
   {
 	f.operacion.value="ue_nuevo";
	f.txtnumped.value="";
	f.txtfecped.value="";
	//f.txtcodtie.value="";
	//f.txtdentie.value="";
	f.txtobsped.value="";
	f.filaspedido.value="";
	f.hidremoverpedido.value="";
    f.action="sigesp_sfc_d_pedido.php";
	f.submit();
   }
   else
   {
	 alert("No tiene permiso para realizar esta operacion");
   }
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

function ue_eliminar()
{
	f=document.form1;

	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{
	  if (f.txtnumped.value=="")
	  {
		alert("Debe seleccionar el Pedido a Eliminar!!");
	  }
	  else
	  {

	    var codtienda_busca = "<?php echo $ls_codtie; ?>";
		var codtienda_session = "<?php echo $ls_codtienda; ?>";

		if (codtienda_busca!=codtienda_session)
		{
			 alert("No puede realizar esta Operación, Solo puede Consultar");

		}
		else
		{
		    si=confirm("Esta seguro?");
			 if(si)
			 {
				f.action="sigesp_sfc_d_pedido.php";
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

function ue_verificarexistencia()
{
	f=document.form1;
	f.operacion.value="ue_verificarexistencia";
	f.submit();
}


function ue_buscartienda()
		{
            f=document.form1;

			f.operacion.value="";
			pagina="sigesp_cat_tienda.php";
			popupWin(pagina,"catalogo_tiendas",600,250);

		}

/***********************************************************************************************************************************/

		function ue_cargartienda(codtie,nomtie,dirtie,teltie,riftie,codpai,codest,codmun,codpar,items,spi_cuenta,denominacion,codunidad,denunidad,cuentaiva,deniva)
		{
			f=document.form1;

			f.txtcodtie.value=codtie;
            f.txtdentie.value=nomtie;


		}



</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>