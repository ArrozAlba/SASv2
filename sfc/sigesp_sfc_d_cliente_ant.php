<?php
/************************************************************************************************************************/
/***********************************  INICIO DE LA PAGINA E INICIO DE SESION   ******************************************/
/************************************************************************************************************************/
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_conexion.php'";
	 print "</script>";
   }
$la_datemp=$_SESSION["la_empresa"];
$ls_codtie=$_SESSION["ls_codtienda"];
/************************************************************************************************************************/

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Definici&oacute;n de Clientes</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
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
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699" onLoad="ue_subtotal();"> <!--****************Agregado Subtotal***************-->
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno" >
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="485" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1">Sistema de Facturaci&oacute;n</span></td>
    <td width="293" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
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
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SFC";
	$ls_ventanas="sigesp_sfc_d_cliente.php";

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
/************************************************************************************************************************/
/**************************************************  LIBRERIAS  **********************************************************/
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
	require_once("class_folder/sigesp_sfc_c_secuencia.php");
	require_once("class_folder/sigesp_sfc_c_cliente.php");
	require_once("class_folder/sigesp_sfc_c_productor.php");
	require_once("class_folder/sigesp_sfc_class_utilidades.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	require_once("class_folder/sigesp_sfc_c_secuencia.php");
/************************************************************************************************************************/
	$io_include  = new sigesp_include();
	$io_connect  = $io_include->uf_conectar();
	$io_sql      = new class_sql($io_connect);
	$io_funcdb   = new class_funciones_db($io_connect);
	$io_funcsob  = new sigesp_sob_c_funciones_sob();
	$io_evalform = new evaluate_formula();
	$io_grid     = new grid_param();
	$is_msg      = new class_mensajes();
	$io_datastore= new class_datastore();
    $io_function = new class_funciones();
	$io_cliente  = new sigesp_sfc_c_cliente();
	$io_productor= new sigesp_sfc_c_productor();
	$io_utilidad = new sigesp_sfc_class_utilidades();
	$io_secuencia= new sigesp_sfc_c_secuencia();
	/************************************************************************************************************************
	* ***************************************Datos definidos para la estructura del grid         ****************************
	*************************************************************************************************************************/
	/********************* GRID RETENCIONES**********************/
	$ls_tituloretenciones="Retenciones Asignadas";
    $li_anchoretenciones=900;
    $ls_nametable="grid";
    $la_columretenciones[1]="Codigo";
    $la_columretenciones[2]="Descripcion";
    $la_columretenciones[3]="Edicion";
	/***************************************************************/
	/****************   GRID   RUBROS AGRoCOLAS   *******************/
	$ls_titulorubrosagri="Rubros Agrocolas";
    $li_anchorubrosagri=900;
    $ls_nametableagri="grid1";

    $la_columrubrosagri[1]="Clasificacion";
    $la_columrubrosagri[2]="Has. Prodvas.";
	$la_columrubrosagri[3]="Rendimiento Estimado*Has.";
	$la_columrubrosagri[4]="Prod.";
	$la_columrubrosagri[5]="Rubro";
	$la_columrubrosagri[6]="Renglon";
    $la_columrubrosagri[7]="Ciclo";
	$la_columrubrosagri[8]="A";
	$la_columrubrosagri[9]="Edicion";
	/***************************************************************/
	/****************   GRID   RUBROS PECUARIOS   *******************/
	$ls_titulorubrospec="Rubros Pecuarios";
    $li_anchorubrospec=900;
    $ls_nametablepec="grid2";

    $la_columrubrospec[1]="Clasificacion";
    $la_columrubrospec[2]="No de Animales";
	$la_columrubrospec[3]="Has. Prodvas.";
	$la_columrubrospec[4]="Rendimiento Estimado*Animal";
	$la_columrubrospec[5]="Prod.";
	$la_columrubrospec[6]="Rubro";
	$la_columrubrospec[7]="Renglon";
	$la_columrubrospec[8]="Edicion";
	/**************************************************************************************************************************/
	/****************************************************   SUBMIT   ***********************************************************/
	/**************************************************************************************************************************/
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_cedcli   =$_POST["txtcedcli"];
		$ls_codcli   =$_POST["hidcodcli"];
		$ls_codcla   =$_POST["hidcodcli"];
		$ls_codcla=$io_funcdb->uf_generar_codigo(false,"","sfc_cliente","codcli",100); // correlativo incrementa automaticamente
		//print 'codcla->'.$ls_codcla.'<br>';
		if ($ls_codcla=="")
		{
		  $ls_codcla=1;
		}
		$ls_nomcli        = $_POST["txtnomcli"];
		$ls_dircli        = $_POST["txtdircli"];
		$ls_telcli        = $_POST["txttelcli"];
		$ls_celcli        = $_POST["txtcelcli"];
		$ls_codest        = $_POST["cmbestado"];
		$ls_codmun        = $_POST["cmbmunicipio"];
		$ls_codpar        = $_POST["cmbparroquia"];
		$ls_precioestandar= $_POST["cmbprecioestandar"];
		$ls_tentierra     = $_POST["cmbtentierra"];
		$ls_codpai        = $_POST["hidcodpai"];
		$ls_nrohect       = $_POST["txtnrohect"];
		$hectprod         = $_POST["hectprod"];
		$ls_nrohectsinprod= $_POST["txthectsinprod"];
		$ls_hidsta        = $_POST["hidsta"];
		$ls_hidstatus     = $_POST["hidstatus"];
		$ls_cedcliaux     = $_POST["hidcedcli"];
		$ls_tipcli        = $_POST["cmbtipcli"];
        $ls_tentierra     = $_POST["cmbtentierra"];
		$ls_readonly      = $_POST["hidreadonly"];
		$ls_readonlyced   = $_POST["hidreadonlyced"];
		if($_POST["txtnrocartagr"]=="")
		{
		$ls_nrocartagr=$_POST["txtnrocartagr"];
		}else
		{
		$ls_nrocartagr=$io_function->uf_cerosizquierda($_POST["txtnrocartagr"],'25');
		}
		$ls_productor=$_POST["txtproductor"];

		/**************** GRID RETENCIONES**********************/
		$li_filasretenciones=$_POST["filasretenciones"];
		$li_removerretenciones=$_POST["removerretenciones"];
		if ($ls_operacion != "ue_cargarretenciones" && $ls_operacion != "ue_removerretenciones")
	     {
		 //recorrido del grid
		  for($li_i=1;$li_i<$li_filasretenciones;$li_i++)
		   {
			$ls_codigo=$_POST["txtcodret".$li_i];
			$ls_descripcion=$_POST["txtdesret".$li_i];
			$la_objectretenciones[$li_i][1]="<input name=txtcodret".$li_i." type=text id=txtcodret".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
			$la_objectretenciones[$li_i][2]="<input name=txtdesret".$li_i." type=text id=txtdesret".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=35 readonly>";
			$la_objectretenciones[$li_i][3]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerretenciones(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";		}

		$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
		$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=25 readonly>";
		$la_objectretenciones[$li_filasretenciones][3]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
		}
		/**************** GRID RUBROS AGRoCOLAS**********************/
		$li_filasrubrosagri=$_POST["filasrubrosagri"];
		$li_removerrubrosagri=$_POST["removerrubrosagri"];
		if ($ls_operacion != "ue_cargarrubrosagri" && $ls_operacion != "ue_removerrubrosagri")
	     {
		 //recorrido del grid
		  for($li_i=1;$li_i<$li_filasrubrosagri;$li_i++)
		   {
			$ls_codrubrosagri = $_POST["txtcodrubrosagri".$li_i];
			$ls_codclaagri    = $_POST["txtcodclaagri".$li_i];
			$ls_codclaagri    = $_POST["txtcodclaagri".$li_i];
			$ls_descrubrosagri= $_POST["txtdesrubrosagri".$li_i];
			$ls_tipo          = $_POST["txttipo".$li_i];
			$ls_nro           = $_POST["txtnro".$li_i];
			$ls_hectsembradas = $_POST["txthectsembradas".$li_i];
			$ls_tipoprod      = $_POST["txttipoprod".$li_i];
			$ls_cantprod      = $_POST["txtcantprod".$li_i];
			$ls_ciclo         = $_POST["txtciclo".$li_i];
			 if ($_POST["chk".$li_i]=='')
			   {
			    $ls_estarub='0';
			   }else{
			   $ls_estarub='1';
			   }
			$la_objectrubrosagri[$li_i][1]="<input name=txtcodrubrosagri".$li_i." type=hidden id=txtcodrubrosagri".$li_i." class=sin-borde value='".$ls_codrubrosagri."' style= text-align:center size=20 readonly><input name=txtcodclaagri".$li_i." type=hidden id=txtcodclaagri".$li_i." class=sin-borde value='".$ls_codclaagri."' style= text-align:center size=20 readonly><input name=txtdesrubrosagri".$li_i." type=text id=txtdesrubrosagri".$li_i." class=sin-borde value='".$ls_descrubrosagri."' style= text-align:left size=35 readonly>";
			if ($ls_operacion!="ue_guardar")
			{
				$la_objectrubrosagri[$li_i][2]="<input name=txthectsembradas".$li_i." type=text id=txthectsembradas".$li_i." value='".$ls_hectsembradas."' class=sin-borde size=8 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event))>";
			}
			else
			{
				$la_objectrubrosagri[$li_i][2]="<input name=txthectsembradas".$li_i." type=text id=txthectsembradas".$li_i." value='".$ls_hectsembradas."' class=sin-borde size=8 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event))>";
			}
			$la_objectrubrosagri[$li_i][3]="<input name=txtnro".$li_i." type=text id=txtnro".$li_i." class=sin-borde value='".$ls_nro."' style= text-align:left size=4 readonly>";
			$la_objectrubrosagri[$li_i][4]="<input name=txtcantprod".$li_i." type=text id=txtcantprod".$li_i." value='".$ls_cantprod."' class=sin-borde style= text-align:left size=12 readonly>";
			$la_objectrubrosagri[$li_i][5]="<input name=txttipoprod".$li_i." type=text id=txttipoprod".$li_i." value='".$ls_tipoprod."' class=sin-borde style= text-align:left size=35 readonly >";
			$la_objectrubrosagri[$li_i][6]="<input name=txttipo".$li_i." type=text id=txttipo".$li_i." class=sin-borde value='".$ls_tipo."' style= text-align:left size=35 readonly>";
			$la_objectrubrosagri[$li_i][7]="<input name=txtciclo".$li_i." type=text id=txtciclo".$li_i." class=sin-borde value='".$ls_ciclo."' style= text-align:left size=5 readonly>";

			if ($ls_estarub=='1')
			{
				$la_objectrubrosagri[$li_i][8]= "<input name=chk".$li_i." type=checkbox id=chk".$li_i." checked=checked   class=sin-borde  onChange=javascript:ue_subtotal()>";
            }
			else
			{
				$la_objectrubrosagri[$li_i][8]= "<input name=chk".$li_i." type=checkbox id=chk".$li_i." value=chk  class=sin-borde  onChange=javascript:ue_subtotal()>";
			}
				$la_objectrubrosagri[$li_i][9]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerrubroagri(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
			}
			$la_objectrubrosagri[$li_filasrubrosagri][1]="<input name=txtcodrubrosagri".$li_filasrubrosagri." type=hidden id=txtcodrubrosagri".$li_filasrubrosagri." class=sin-borde style= text-align:center size=20 readonly><input name=txtcodclaagri".$li_filasrubrosagri." type=hidden id=txtcodclaagri".$li_filasrubrosagri." class=sin-borde style= text-align:center size=20 readonly><input name=txtdesrubrosagri".$li_filasrubrosagri." type=text id=txtdesrubrosagri".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
			$la_objectrubrosagri[$li_filasrubrosagri][2]="<input name=txthectsembradas".$li_filasrubrosagri." type=text id=txthectsembradas".$li_filasrubrosagri." class=sin-borde style= text-align:left size=8 readonly>";
			$la_objectrubrosagri[$li_filasrubrosagri][3]="<input name=txtnro".$li_filasrubrosagri." type=text id=txtnro".$li_filasrubrosagri." class=sin-borde style= text-align:left size=4 readonly>";
			$la_objectrubrosagri[$li_filasrubrosagri][4]="<input name=txtcantprod".$li_filasrubrosagri." type=text id=txtcantprod".$li_filasrubrosagri." class=sin-borde style= text-align:left size=12 readonly>";
			$la_objectrubrosagri[$li_filasrubrosagri][5]="<input name=txttipoprod".$li_filasrubrosagri." type=text id=txttipoprod".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
			$la_objectrubrosagri[$li_filasrubrosagri][6]="<input name=txttipo".$li_filasrubrosagri." type=text id=txttipo".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
			$la_objectrubrosagri[$li_filasrubrosagri][7]="<input name=txtciclo".$li_filasrubrosagri." type=text id=txtciclo".$li_filasrubrosagri." class=sin-borde style= text-align:left size=5 readonly>";
			$la_objectrubrosagri[$li_filasrubrosagri][8]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=3>";
			$la_objectrubrosagri[$li_filasrubrosagri][9]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=4 readonly>";
	}
	/**************** GRID RUBROS PECUARIOS**********************/
		$li_filasrubrospec=$_POST["filasrubrospec"];
		$li_removerrubrospec=$_POST["removerrubrospec"];
		if ($ls_operacion != "ue_cargarrubrospec" && $ls_operacion != "ue_removerrubrospec")
	     {
		 //recorrido del grid
		  for($li_i=1;$li_i<$li_filasrubrospec;$li_i++)
		   {
			$ls_codrubrospec = $_POST["txtcodrubrospec".$li_i];
			$ls_codclapec    = $_POST["txtcodclapec".$li_i];
			$ls_descrubrospec= $_POST["txtdesrubrospec".$li_i];
			$ls_tipopec      = $_POST["txttipopec".$li_i];
			$ls_nropec       = $_POST["txtnropec".$li_i];
			$ls_hectpec      = $_POST["txthectpec".$li_i];
			$ls_tipoprodpec  = $_POST["txttipoprodpec".$li_i];
			$ls_cantprodpec  = $_POST["txtcantprodpec".$li_i];
			$ls_nroanimal    = $_POST["txtnroanimal".$li_i];
			$ls_has          = $_POST["txthas".$li_i];

			$la_objectrubrospec[$li_i][1]="<input name=txtcodrubrospec".$li_i." type=hidden id=txtcodrubrospec".$li_i." class=sin-borde value='".$ls_codrubrospec."' style= text-align:center size=20 readonly><input name=txtcodclapec".$li_i." type=hidden id=txtcodclapec".$li_i." class=sin-borde value='".$ls_codclapec."' style= text-align:center size=20 readonly><input name=txtdesrubrospec".$li_i." type=text id=txtdesrubrospec".$li_i." class=sin-borde value='".$ls_descrubrospec."' style= text-align:left size=20 readonly>";
			$la_objectrubrospec[$li_i][2]="<input name=txtnropec".$li_i." type=text id=txtnropec".$li_i." class=sin-borde value='".$ls_nropec."' style= text-align:left onKeyPress=return(currencyFormat(this,'.',',',event)) size=8><input name=txthas".$li_i." type=hidden id=txthas".$li_i." class=sin-borde value='".$ls_has."' style= text-align:left size=8 onKeyPress=return(currencyFormat(this,'.',',',event))>";
			if ($ls_operacion!="ue_guardar")
			{
				$la_objectrubrospec[$li_i][3]="<input name=txthectpec".$li_i." onChange=javascript:ue_subtotal2(); type=text id=txthectpec".$li_i." value='".$ls_hectpec."' class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event)) readonly>";
			}
			else
			{
				$la_objectrubrospec[$li_i][3]="<input name=txthectpec".$li_i." type=text id=txthectpec".$li_i." value='".$ls_hectpec."' class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event)) readonly>";
			}
			$la_objectrubrospec[$li_i][4]="<input name=txtnroanimal".$li_i." type=text id=txtnroanimal".$li_i." class=sin-borde value='".$ls_nroanimal."' style= text-align:left size=4 readonly onKeyPress=return(currencyFormat(this,'.',',',event))>";
			$la_objectrubrospec[$li_i][5]="<input name=txtcantprodpec".$li_i." type=text id=txtcantprodpec".$li_i." value='".$ls_cantprodpec."' class=sin-borde style= text-align:left size=20 readonly onKeyPress=return(currencyFormat(this,'.',',',event))>";
			$la_objectrubrospec[$li_i][6]="<input name=txttipoprodpec".$li_i." type=text id=txttipoprodpec".$li_i." value='".$ls_tipoprodpec."' class=sin-borde style= text-align:left size=35 readonly >";
			$la_objectrubrospec[$li_i][7]="<input name=txttipopec".$li_i." type=text id=txttipopec".$li_i." class=sin-borde value='".$ls_tipopec."' style= text-align:left size=50 readonly>";
			$la_objectrubrospec[$li_i][8]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerrubropec(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}
		$la_objectrubrospec[$li_filasrubrospec][1]="<input name=txtcodrubrospec".$li_filasrubrospec." type=hidden id=txtcodrubrospec".$li_filasrubrospec." class=sin-borde style= text-align:center size=20 readonly><input name=txtcodclapec".$li_filasrubrospec." type=hidden id=txtcodclapec".$li_filasrubrospec." class=sin-borde style= text-align:center size=20 readonly><input name=txtdesrubrospec".$li_filasrubrospec." type=text id=txtdesrubrospec".$li_filasrubrospec." class=sin-borde style= text-align:left size=20 readonly>";
	    $la_objectrubrospec[$li_filasrubrospec][2]="<input name=txtnropec".$li_filasrubrospec." type=text id=txtnropec".$li_filasrubrospec." class=sin-borde style= text-align:left size=8 readonly><input name=txthas".$li_filasrubrospec." type=hidden id=txthas".$li_filasrubrospec." class=sin-borde style= text-align:left size=8>";
		$la_objectrubrospec[$li_filasrubrospec][3]="<input name=txthectpec".$li_filasrubrospec." type=text id=txthectpec".$li_filasrubrospec." class=sin-borde style= text-align:left size=6 readonly>";
		$la_objectrubrospec[$li_filasrubrospec][4]="<input name=txtnroanimal".$li_filasrubrospec." type=text id=txtnroanimal".$li_filasrubrospec." class=sin-borde style= text-align:left size=4 readonly>";
		$la_objectrubrospec[$li_filasrubrospec][5]="<input name=txtcantprodpec".$li_filasrubrospec." type=text id=txtcantprodpec".$li_filasrubrospec." class=sin-borde style= text-align:left size=20 readonly>";
		$la_objectrubrospec[$li_filasrubrospec][6]="<input name=txttipoprodpec".$li_filasrubrospec." type=text id=txttipoprodpec".$li_filasrubrospec." class=sin-borde style= text-align:left size=35 readonly>";
		$la_objectrubrospec[$li_filasrubrospec][7]="<input name=txttipopec".$li_filasrubrospec." type=text id=txttipopec".$li_filasrubrospec." class=sin-borde style= text-align:left size=50 readonly>";
		$la_objectrubrospec[$li_filasrubrospec][8]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=4 readonly>";
	}
	}
/************************************************************************************************************************/
/***************************   NO SUBMIT ********************************************************************************/
/************************************************************************************************************************/
	else
	{
		$ls_operacion="";
		$ls_codcli="0";
		$ls_codcla=1;
		$ls_cedcli="";
		$ls_nomcli="";
		$ls_dircli="";
		$ls_telcli="";
		$ls_celcli="";
		$ls_codest="";
		$ls_codmun="";
		$ls_codpar="";
		$ls_codpai="";
		$ls_precioestandar="";
		$ls_tentierra="";
		$ls_tipcli="";
	    $ls_productor="NO";
		$ls_nrohect="0,00";
		$hectprod="0,00";
		$ls_nrohectsinprod="0,00";
		$ls_nrocartagr="";
		$ls_hidsta="";
		$ls_hidstatus="";
		$ls_cedcliaux="";
		$ls_clicot=$_GET["codcli"];
		$ls_readonly="readonly";

        if($ls_clicot!="")
         {
           $ls_codcli=$ls_clicot;
		   $ls_operacion="ue_nuevo";
         }

	     //Pinta el grid sin datos

		/********************* GRID RETENCIONES**********************/
		$li_filasretenciones=1; //Nro de filas que mostraro el grid al cargar el formulario
	    $la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
	    $la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=35 readonly>";
	    $la_objectretenciones[$li_filasretenciones][3]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
		/**************   GRID   RUBROS AGRoCOLAS   *******************/
		$li_filasrubrosagri=1; //Nro de filas que mostraro el grid al cargar el formulario
	    $la_objectrubrosagri[$li_filasrubrosagri][1]="<input name=txtcodrubrosagri".$li_filasrubrosagri." type=hidden id=txtcodrubrosagri".$li_filasrubrosagri." class=sin-borde style= text-align:center size=20 readonly><input name=txtcodclaagri".$li_filasrubrosagri." type=hidden id=txtcodclaagri".$li_filasrubrosagri." class=sin-borde style= text-align:center size=20 readonly><input name=txtdesrubrosagri".$li_filasrubrosagri." type=text id=txtdesrubrosagri".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
		$la_objectrubrosagri[$li_filasrubrosagri][2]="<input name=txthectsembradas".$li_filasrubrosagri." type=text id=txthectsembradas".$li_filasrubrosagri." class=sin-borde style= text-align:left size=8>";
		$la_objectrubrosagri[$li_filasrubrosagri][3]="<input name=txtnro".$li_filasrubrosagri." type=text id=txtnro".$li_filasrubrosagri." class=sin-borde style= text-align:left size=4 readonly>";
		$la_objectrubrosagri[$li_filasrubrosagri][4]="<input name=txtcantprod".$li_filasrubrosagri." type=text id=txtcantprod".$li_filasrubrosagri." class=sin-borde style= text-align:left size=12 readonly>";
		$la_objectrubrosagri[$li_filasrubrosagri][5]="<input name=txttipoprod".$li_filasrubrosagri." type=text id=txttipoprod".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
		$la_objectrubrosagri[$li_filasrubrosagri][6]="<input name=txttipo".$li_filasrubrosagri." type=text id=txttipo".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
		$la_objectrubrosagri[$li_filasrubrosagri][7]="<input name=txtciclo".$li_filasrubrosagri." type=text id=txtciclo".$li_filasrubrosagri." class=sin-borde style= text-align:center size=5 readonly>";
		$la_objectrubrosagri[$li_filasrubrosagri][8]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=3>";
		$la_objectrubrosagri[$li_filasrubrosagri][9]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=4 readonly>";
		/**************   GRID   RUBROS PECUARIOS   *******************/
		$li_filasrubrospec=1; //Nro de filas que mostraro el grid al cargar el formulario
	    $la_objectrubrospec[$li_filasrubrospec][1]="<input name=txtcodrubrospec".$li_filasrubrospec." type=hidden id=txtcodrubrospec".$li_filasrubrospec." class=sin-borde style= text-align:center size=20 readonly><input name=txtcodclapec".$li_filasrubrospec." type=hidden id=txtcodclapec".$li_filasrubrospec." class=sin-borde style= text-align:center size=20 readonly><input name=txtdesrubrospec".$li_filasrubrospec." type=text id=txtdesrubrospec".$li_filasrubrospec." class=sin-borde style= text-align:left size=20 readonly>";
		$la_objectrubrospec[$li_filasrubrospec][2]="<input name=txtnropec".$li_filasrubrospec." type=text id=txtnropec".$li_filasrubrospec." class=sin-borde style= text-align:left size=8 readonly><input name=txthas".$li_filasrubrospec." type=hidden id=txthas".$li_filasrubrospec." class=sin-borde style= text-align:left size=8>";
		$la_objectrubrospec[$li_filasrubrospec][3]="<input name=txthectpec".$li_filasrubrospec." type=text id=txthectpec".$li_filasrubrospec." class=sin-borde style= text-align:left size=6>";
		$la_objectrubrospec[$li_filasrubrospec][4]="<input name=txtnroanimal".$li_filasrubrospec." type=text id=txtnroanimal".$li_filasrubrospec." class=sin-borde style= text-align:left size=4 readonly>";
		$la_objectrubrospec[$li_filasrubrospec][5]="<input name=txtcantprodpec".$li_filasrubrospec." type=text id=txtcantprodpec".$li_filasrubrospec." class=sin-borde style= text-align:left size=20 readonly>";
		$la_objectrubrospec[$li_filasrubrospec][6]="<input name=txttipoprodpec".$li_filasrubrospec." type=text id=txttipoprodpec".$li_filasrubrospec." class=sin-borde style= text-align:left size=35 readonly>";
		$la_objectrubrospec[$li_filasrubrospec][7]="<input name=txttipopec".$li_filasrubrospec." type=text id=txttipopec".$li_filasrubrospec." class=sin-borde style= text-align:left size=50 readonly>";
		$la_objectrubrospec[$li_filasrubrospec][8]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=4 readonly>";
	}
/************************************************************************************************************************/
/***************************   NUEVO-> Limpia cajas de textos para nuevo cliente ****************************************/
/************************************************************************************************************************/

	/*Cuando la operacion es UE_NUEVO*/
	if($ls_operacion=="ue_nuevo")
	{
		//$ls_codcla=$io_funcdb->uf_generar_codigo(false,"","sfc_cliente","codcli",100); // correlativo incrementa automaticamente
		//$io_secuencia->uf_obtener_secuencia_cliente(sfc_cliente_codcli_seq,$ls_codcla); // correlativo incrementa automaticamente

		if ($ls_codcla=="")
		{
			$ls_codcla=1;
		}
		$ls_codcli=$ls_codcla;
		//print 'codcli_nuevo->'.$ls_codcli.'<br>';
	    $ls_clicot=$_GET["codcli"];
	    if($ls_clicot!="")
         {
           $ls_codpai="058";
		   $ls_codcli=$ls_clicot;
		   $ls_hidsta="V";
		 }
		 else
		 {
		   //$ls_codcli="0";
		 }
		$ls_nomcli="";
		$ls_dircli="";
		$ls_telcli="";
		$ls_celcli="";
		$ls_codest="";
		$ls_codmun="";
		$ls_codpar="";
		$ls_hidstatus="";
		$ls_precioestandar="PV";
		$ls_tentierra="";
		$ls_tipcli="";
		$ls_productor="NO";
		$ls_nrohect="0,00";
		$ls_nrocartagr="";
		$hectprod="0,00";
		$ls_nrohectsinprod="0,00";
        $ls_readonly="readonly";
		$ls_readonlyced="";

		/*$ls_codpai="058";*/

		/********************* GRID RETENCIONES**********************/
		$li_filasretenciones=1; //Nro de filas que mostraro el grid al cargar el formulario
	    $la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
	    $la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=35 readonly>";
	    $la_objectretenciones[$li_filasretenciones][3]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";

		/**************   GRID   RUBROS AGRoCOLAS   *******************/

		$li_filasrubrosagri=1; //Nro de filas que mostraro el grid al cargar el formulario
	    $la_objectrubrosagri[$li_filasrubrosagri][1]="<input name=txtcodrubrosagri".$li_filasrubrosagri." type=hidden id=txtcodrubrosagri".$li_filasrubrosagri." class=sin-borde style= text-align:center size=20 readonly><input name=txtcodclaagri".$li_filasrubrosagri." type=hidden id=txtcodclaagri".$li_filasrubrosagri." class=sin-borde style= text-align:center size=20 readonly><input name=txtdesrubrosagri".$li_filasrubrosagri." type=text id=txtdesrubrosagri".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
	    $la_objectrubrosagri[$li_filasrubrosagri][2]="<input name=txthectsembradas".$li_filasrubrosagri." type=text id=txthectsembradas".$li_filasrubrosagri." class=sin-borde style= text-align:left size=8>";
		$la_objectrubrosagri[$li_filasrubrosagri][3]="<input name=txtnro".$li_filasrubrosagri." type=text id=txtnro".$li_filasrubrosagri." class=sin-borde style= text-align:left size=4 readonly>";
		$la_objectrubrosagri[$li_filasrubrosagri][4]="<input name=txtcantprod".$li_filasrubrosagri." type=text id=txtcantprod".$li_filasrubrosagri." class=sin-borde style= text-align:left size=12 readonly>";
		$la_objectrubrosagri[$li_filasrubrosagri][5]="<input name=txttipoprod".$li_filasrubrosagri." type=text id=txttipoprod".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
		$la_objectrubrosagri[$li_filasrubrosagri][6]="<input name=txttipo".$li_filasrubrosagri." type=text id=txttipo".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
		$la_objectrubrosagri[$li_filasrubrosagri][7]="<input name=txtciclo".$li_filasrubrosagri." type=text id=txtciclo".$li_filasrubrosagri." class=sin-borde style= text-align:left size=5 readonly>";
		$la_objectrubrosagri[$li_filasrubrosagri][8]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=3 >";
		$la_objectrubrosagri[$li_filasrubrosagri][9]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=4 readonly>";

		/**************   GRID   RUBROS PECUARIOS   *******************/

		$li_filasrubrospec=1; //Nro de filas que mostraro el grid al cargar el formulario
	    $la_objectrubrospec[$li_filasrubrospec][1]="<input name=txtcodrubrospec".$li_filasrubrospec." type=hidden id=txtcodrubrospec".$li_filasrubrospec." class=sin-borde style= text-align:center size=20 readonly><input name=txtcodclapec".$li_filasrubrospec." type=hidden id=txtcodclapec".$li_filasrubrospec." class=sin-borde style= text-align:center size=20 readonly><input name=txtdesrubrospec".$li_filasrubrospec." type=text id=txtdesrubrospec".$li_filasrubrospec." class=sin-borde style= text-align:left size=20 readonly>";
		$la_objectrubrospec[$li_filasrubrospec][2]="<input name=txtnropec".$li_filasrubrospec." type=text id=txtnropec".$li_filasrubrospec." class=sin-borde style= text-align:left size=8 readonly><input name=txthas".$li_filasrubrospec." type=hidden id=txthas".$li_filasrubrospec." class=sin-borde style= text-align:left size=8>";
		$la_objectrubrospec[$li_filasrubrospec][3]="<input name=txthectpec".$li_filasrubrospec." type=text id=txthectpec".$li_filasrubrospec." class=sin-borde style= text-align:left size=6>";
		$la_objectrubrospec[$li_filasrubrospec][4]="<input name=txtnroanimal".$li_filasrubrospec." type=text id=txtnroanimal".$li_filasrubrospec." class=sin-borde style= text-align:left size=4 readonly>";
		$la_objectrubrospec[$li_filasrubrospec][5]="<input name=txtcantprodpec".$li_filasrubrospec." type=text id=txtcantprodpec".$li_filasrubrospec." class=sin-borde style= text-align:left size=20 readonly>";
		$la_objectrubrospec[$li_filasrubrospec][6]="<input name=txttipoprodpec".$li_filasrubrospec." type=text id=txttipoprodpec".$li_filasrubrospec." class=sin-borde style= text-align:left size=35 readonly>";
		$la_objectrubrospec[$li_filasrubrospec][7]="<input name=txttipopec".$li_filasrubrospec." type=text id=txttipopec".$li_filasrubrospec." class=sin-borde style= text-align:left size=50 readonly>";
		$la_objectrubrospec[$li_filasrubrospec][8]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=4 readonly>";

	}
/************************************************************************************************************************/
/***************************   GUARDAR   ********************************************************************************/
/************************************************************************************************************************/
	elseif($ls_operacion=="ue_guardar")
	{
		$ls_evento='INSERT';
		$ls_cadena= " SELECT * ".
						" FROM  sfc_cliente ".
						" WHERE codcli ilike '".$ls_codcli."'";
						$rs_datauni=$io_sql->select($ls_cadena);
						if($rs_datauni==false&&($io_sql->message!=""))
						{
							$is_msg->message("No hay registros");
						}
						else
						{
							if($row=$io_sql->fetch_row($rs_datauni))
							{
								$la_tienda=$io_sql->obtener_datos($rs_datauni);
								$io_datastore->data=$la_tienda;
								$totrow=$io_datastore->getRowCount("codcli");

								for($z=1;$z<=$totrow;$z++)
								{
									$ls_codcli=$io_datastore->getValue("codcli",$z);
									$ls_evento='UPDATE';
								}
							}
						}
					
					
					if($ls_evento=='INSERT')
					{
					$io_secuencia->uf_obtener_secuencia_cliente(sfc_cliente_codcli_seq,$ls_codcli); // correlativo incrementa automaticamente
					}
					
					
					if ($ls_productor=="NO")
					{
							$lb_valido=$io_cliente->uf_guardar_cliente($ls_codcli,$ls_tipcli.$ls_cedcli,$ls_nomcli,$ls_dircli,$ls_telcli,$ls_celcli,
														$ls_codpai,$ls_codest,$ls_codmun,$ls_codpar,$ls_precioestandar,$ls_productor,$la_seguridad);
							
							 $la_detalles["codded"][1]="";
							 for ($li_i=1;$li_i<$li_filasretenciones;$li_i++)
							 {
								 $la_detalles["codded"][$li_i]=$_POST["txtcodret".$li_i];
							 }
							 if($li_filasretenciones>=2)
							 {
									 $io_cliente->uf_update_clientededucciones($ls_codcli, $la_detalles,$li_filasretenciones,$la_seguridad);
									 $io_sql->commit();
									 
							 }
							 else
							 {
							  $io_sql->commit();
							 }
							 $ls_mensaje=$io_cliente->io_msgc;
							 if($lb_valido==true)
							 {
								$is_msg->message ($ls_mensaje);
							 }
							 else
							 {
									if($lb_valido==0){}
									else
									{
										$is_msg->message ($ls_mensaje);
									}
							  }

						}
						elseif ($ls_productor=="SI")
						{
								$cantidad_pro=true;
								$cantidad_pro1=true;
								//$io_productor->uf_guardar_productor($ls_codcli,$ls_nrocartagr,$ls_nrohect,$hectprod,$ls_nrohectsinprod);
								$la_detalles["cod_rubro"][1]="";
								$la_detalles["desc_rubro"][1]="";
								$la_detalles["hect_prod"][1]="";
								$la_detalles["nro_prod"][1]="";
								$la_detalles["desc_tipo"][1]="";
								$la_detalles["cant_prod"][1]="";
								$la_detalles["descripcion"][1]="";
								$la_detalles["estarub"][1]="";
								$la_detalles["deno_rubro"][1]="";
								$la_detalles["cod_claagri"][1]="";
							/*******************************************************************************************************************
							**********************************  GRID RUBROS AGRoCOLAS ********************************************************
							********************************************************************************************************************/
								for ($li_i=1;$li_i<$li_filasrubrosagri;$li_i++)
								{
								   $la_detalles["cod_rubro"][$li_i]   = $_POST["txtcodrubrosagri".$li_i];
								   $la_detalles["cod_claagri"][$li_i] = $_POST["txtcodclaagri".$li_i];
								   $la_detalles["nro_cartagr"][$li_i] = $ls_nrocartagr;
								   $la_detalles["hect_prod"][$li_i]   = $_POST["txthectsembradas".$li_i];

								   if ($la_detalles["estarub"][$li_i].checked==true)
								   {
										 if ($la_detalles["hect_prod"][$li_i]=="")
										   {
												  echo("<script>alert('Debe indicar el nro. de Has. Productivas para el Rubro Agricola!!!');</script>");
												  $cantidad_pro=false;
											}
								   }
								   $la_detalles["cant_prod"][$li_i]=$_POST["txtcantprod".$li_i];
								   $la_detalles["desc_tipo"][$li_i]=$_POST["txttipoprod".$li_i];
								   $la_detalles["deno_rubro"][$li_i]=$_POST["txtciclo".$li_i];
								   if($_POST["txtciclo".$li_i]=="PERMANENTE")
								   {
										$_POST["chk".$li_i]=='1';
								   }
								   if($_POST["chk".$li_i]=='')
								   {
										$la_detalles["estarub"][$li_i]=0;
								   }
								   else
								   {
										$la_detalles["estarub"][$li_i]='1';
								   }
								}//for
								//$io_productor->uf_update_rubrosproductor($ls_codcli,$la_detalles,$li_filasrubrosagri/*,$aa_seguridad*/);
								/*******************************************************************************************************************
								**********************************  GRID RUBROS PECUARIOS ********************************************************
								********************************************************************************************************************/
								for ($li_i=1;$li_i<$li_filasrubrospec;$li_i++)
								 {
									$la_detalles1["cod_rubro"][$li_i]    = $_POST["txtcodrubrospec".$li_i];
									$la_detalles1["cla_rubro"][$li_i]    = $_POST["txtcodclapec".$li_i];
									$la_detalles1["nro_cartagr"][$li_i]  = $ls_nrocartagr;
									$la_detalles1["hect_prod"][$li_i]    = $_POST["txthectpec".$li_i];
									$la_detalles1["cant_prod"][$li_i]    = $_POST["txtcantprodpec".$li_i];
									$la_detalles1["nro_animales"][$li_i] = $_POST["txtnropec".$li_i];
									if ($la_detalles1["nro_animales"][$li_i]=="")
									{
										  echo("<script>alert('Debe indicar el nro. de Animales para el Rubro Pecuario!!!');</script>");
										  $cantidad_pro1=false;
									}
									$la_detalles1["desc_tipo"][$li_i]=$_POST["txttipoprodpec".$li_i];
								}
								if ($cantidad_pro1==true and $cantidad_pro==true)
								{
									$lb_valido=$io_cliente->uf_guardar_cliente($ls_codcli,$ls_tipcli.$ls_cedcli,$ls_nomcli,$ls_dircli,$ls_telcli,$ls_celcli,
													$ls_codpai,$ls_codest,$ls_codmun,$ls_codpar,$ls_precioestandar,$ls_productor,$la_seguridad);

									$ls_cadena= " SELECT * ".
												" FROM sfc_cliente ".
												" WHERE razcli ilike '".$ls_nomcli."'";

									$rs_datauni=$io_sql->select($ls_cadena);
									if($rs_datauni==false&&($io_sql->message!=""))
									{
										$is_msg->message("No hay registros");
									}
									else
									{
										if($row=$io_sql->fetch_row($rs_datauni))
										{
											$la_tienda=$io_sql->obtener_datos($rs_datauni);
											$io_datastore->data=$la_tienda;
											$totrow=$io_datastore->getRowCount("codcli");

											for($z=1;$z<=$totrow;$z++)
											{
												$ls_codcli=$io_datastore->getValue("codcli",$z);
											}
										}
									}
									$la_detalles2["codded"][1]="";
									//Carga en una matriz la informacion registrada en el grid para luego guardarla
									for ($li_i=1;$li_i<$li_filasretenciones;$li_i++)
									 {
									   $la_detalles2["codded"][$li_i]=$_POST["txtcodret".$li_i];
									 }
									 if($li_filasretenciones>=2)
							 		{
									$io_cliente->uf_update_clientededucciones($ls_codcli,$la_detalles2,$li_filasretenciones,$la_seguridad);
									}
									$ls_tentierra = $_POST["cmbtentierra"];
									$io_productor->uf_guardar_productor($ls_codcli,$ls_nrocartagr,$ls_nrohect,$hectprod,
																		$ls_nrohectsinprod,$ls_tentierra,$la_seguridad);

									if($li_filasrubrosagri>=2)
									{
									   $io_productor->uf_update_rubrosproductor($ls_codcli,$la_detalles,$ls_tentierra,$li_filasrubrosagri,$la_seguridad);
									}
									if($li_filasrubrospec>=2)
									{
									   $io_productor->uf_update_rubrospecproductor($ls_codcli, $la_detalles1,$li_filasrubrospec,$la_seguridad);
									   $io_sql->commit();
									}
									else
									{
									$io_sql->commit();
									
									}

									$ls_mensaje=$io_cliente->io_msgc;
									if($lb_valido==true)
									{
										$is_msg->message ($ls_mensaje);

									}
									else
									{
										if($lb_valido==0)
										{

										}
										else
										{
											$is_msg->message ($ls_mensaje);
											$ls_operacion=="ue_nuevo";
										}
									}
								}
						 }
	}//elseif($ls_operacion=="ue_guardar")

/************************************************************************************************************************/
/***************************   ELIMINAR  ********************************************************************************/
/************************************************************************************************************************/
	elseif($ls_operacion=="ue_eliminar")
	{

	/***********************  verificar si posee "notas" ***************************************************************/
	     $ls_sql="SELECT *
                   FROM sfc_nota
                  WHERE codemp='".$la_datemp["codemp"]."' AND codtiend='".$ls_codtie."' AND codcli=".$ls_codcli;

		$rs_datauni=$io_sql->select($ls_sql);
		if($rs_datauni==false)
		{
			$lb_valido_nota=false;
			$is_msg="Error en uf_select_nota ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_nota=true; //Registro encontrado
		        $is_msg->message ("El Cliente posee una nota de crodito no se puede eliminar!!!");

			}
			else
			{
				$lb_valido_nota=false; //"Registro no encontrado"
			}
		}

	/**********************  verificar si posee "cotizacion" ***************************************************************/
	     $ls_sql="SELECT *
                  FROM sfc_cotizacion
                  WHERE codemp='".$la_datemp["codemp"]."' AND codtiend='".$ls_codtie."' AND codcli=".$ls_codcli;

		$rs_datauni=$io_sql->select($ls_sql);
		if($rs_datauni==false)
		{
			$lb_valido_cot=false;
			$is_msg="Error en uf_select_cotizacion ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_cot=true; //Registro encontrado
		        $is_msg->message ("El Cliente posee una cotizacion no se puede eliminar!!!");

			}
			else
			{
				$lb_valido_cot=false; //"Registro no encontrado"
			}
		}
	/***********************  verificar si posee "factura" ***************************************************************/
	     $ls_sql="SELECT *
                  FROM sfc_factura
                  WHERE codemp='".$la_datemp["codemp"]."' AND codtiend='".$ls_codtie."' AND codcli=".$ls_codcli;

		$rs_datauni=$io_sql->select($ls_sql);
		if($rs_datauni==false)
		{
			$lb_valido_fac=false;
			$is_msg="Error en uf_select_factura ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_fac=true; //Registro encontrado
		        $is_msg->message ("El Cliente posee una factura no se puede eliminar!!!");

			}
			else
			{
				$lb_valido_fac=false; //"Registro no encontrado"
			}
		}

	/***********************  verificar si posee "COBRO" ***************************************************************/
	     $ls_sql="SELECT *
                  FROM sfc_cobro_cliente
                  WHERE codemp='".$la_datemp["codemp"]."' AND codtiend='".$ls_codtie."'  AND codcli=".$ls_codcli;

		$rs_datauni=$io_sql->select($ls_sql);
		if($rs_datauni==false)
		{
			$lb_valido_cob=false;
			$is_msg="Error en uf_select_cobro ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_cob=true; //Registro encontrado
		        $is_msg->message ("El Cliente posee un cobro pendiente no se puede eliminar!!!");

			}
			else
			{
				$lb_valido_cob=false; //"Registro no encontrado"
			}
		}
	/***********************************************************************************************************************/
	/***********************  verificar si posee "RUBROS" ***************************************************************/
	     $ls_sql="SELECT *
                  FROM sfc_rubroagri_cliente
                  WHERE codemp='".$la_datemp["codemp"]."' AND codcli=".$ls_codcli;

		$rs_datauni=$io_sql->select($ls_sql);
		if($rs_datauni==false)
		{
			$lb_valido_rub=false;
			$is_msg="Error en uf_select_rubrocliente ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_rub=true; //Registro encontrado
		        $is_msg->message ("El Cliente tiene Rubros Agrocolas asociados, no se puede eliminar!!!");
			}
			else
			{
				$lb_valido_rub=false; //"Registro no encontrado"
			}
		}
	/***********************************************************************************************************************/
	/***********************************************************************************************************************/
	/***********************  verificar si posee "RUBROS" ***************************************************************/
	     $ls_sql="SELECT *
                  FROM sfc_rubropec_cliente
                  WHERE codemp='".$la_datemp["codemp"]."' AND codcli=".$ls_codcli;
		$rs_datauni=$io_sql->select($ls_sql);
		if($rs_datauni==false)
		{
			$lb_valido_rubp=false;
			$is_msg="Error en uf_select_rubrocliente ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_rubp=true; //Registro encontrado
		        $is_msg->message ("El Cliente tiene Rubros Pecuarios asociados, no se puede eliminar!!!");

			}
			else
			{
				$lb_valido_rubp=false; //"Registro no encontrado"
			}
		}
	/***********************************************************************************************************************/
	if ($lb_valido_rub==false && $lb_valido_rubp==false && $lb_valido_nota==false && $lb_valido_cot==false && $lb_valido_fac==false && $lb_valido_cob==false) // si cliente no posee nota de credito ni cotizacion ni factura pendiente ni cobro oeliminar!
	 {
	    $io_cliente->uf_delete_deducciones($ls_codcli,$la_seguridad); //yy
		$io_productor->uf_delete_rubroproductor($ls_codcli,$la_seguridad);
		$io_productor->uf_delete_productor($ls_codcli,$ls_seguridad);
		$lb_valido=$io_cliente->uf_delete_cliente($ls_codcli,$la_seguridad);
		$ls_mensaje=$io_cliente->io_msgc;
		if ($lb_valido==true)
		{
		    $is_msg->message ($ls_mensaje);
			$ls_codcli="";
			$ls_cedcli="";
		    $ls_nomcli="";
		    $ls_dircli="";
		    $ls_telcli="";
		    $ls_celcli="";
			$ls_codpai="";
		    $ls_codest="";
		    $ls_codmun="";
		    $ls_codpar="";
			$ls_precioestandar="PV";
			$ls_tentierra="";
			$ls_tipcli="V";
			$ls_nrohect="0,00";
			$ls_nrocartagr="";
			/********************* GRID RETENCIONES**********************/
			$li_filasretenciones=1; //Nro de filas que mostraro el grid al cargar el formulario
			$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
			$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=35 readonly>";
			$la_objectretenciones[$li_filasretenciones][3]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
		}
	}
}
/************************************************************************************************************************/
/***************************   VERIFICA SI EL CLIENTE EXISTE   **********************************************************/
/************************************************************************************************************************/
	elseif($ls_operacion=="ue_validar")
	{
		$ls_cedcli=$io_function->uf_cerosizquierda($ls_cedcli,9);
		$ls_sql="SELECT *
                 FROM  sfc_cliente
                 WHERE codemp='".$la_datemp["codemp"]."' AND cedcli='".$ls_tipcli.$ls_cedcli."';";

	    $lb_valido=$io_utilidad->uf_datacombo($ls_sql,&$la_cliente);
		if ($lb_valido==true)
		{
		  $is_msg->message ("El numero de cedula fue registrado para otro cliente!!");
		  $io_datastore->data=$la_cliente;
		  $ls_cedcli=$io_datastore->getValue("cedcli",1);
		  $ls_tipcli=substr($ls_cedcli,0,1);
		  $ls_cedcliaux=$ls_cedcli;
		  $ls_cedcli=substr($ls_cedcli,1,strlen($ls_cedcli));
		  $ls_codcli=$io_datastore->getValue("codcli",1);
		  $ls_nomcli=$io_datastore->getValue("razcli",1);
		  $ls_dircli=$io_datastore->getValue("dircli",1);
		  $ls_telcli=$io_datastore->getValue("telcli",1);
		  $ls_celcli=$io_datastore->getValue("celcli",1);
		  $ls_codpai=$io_datastore->getValue("codpai",1);
		  $ls_codest=$io_datastore->getValue("codest",1);
		  $ls_codmun=$io_datastore->getValue("codmun",1);
		  $ls_codpar=$io_datastore->getValue("codpar",1);
		  $ls_hidstatus="C";
		  $ls_precioestandar=$io_datastore->getValue("precio_estandar",1);
		  $ls_productor=$io_datastore->getValue("productor",1);
		  $ls_tentierra=$io_datastore->getValue("tentierra",1);
		  if ($ls_productor=="SI")
		  {
				$ls_codemp=$la_datemp["codemp"];
				$ls_cadena2="SELECT * FROM sfc_productor
							 WHERE codcli='".$ls_codcli."'";
				$rs_datauni2=$io_sql->select($ls_cadena2);
				if($rs_datauni2==false&&($io_sql->message!=""))
				{
					$is_msg->message("No hay registros");
				}
				else
				{
					if($row2=$io_sql->fetch_row($rs_datauni2))
					{
						$la_prod=$io_sql->obtener_datos($rs_datauni2);
						$io_datastore->data=$la_prod;
						$totrow2=$io_datastore->getRowCount("codcli");
						for($z2=1;$z2<=$totrow2;$z2++)
						{
							$ls_nrocartagr=$io_datastore->getValue("nro_cartagr",$z2);
							$ls_nrohect=$io_datastore->getValue("hect_tot",$z2);
							$hectprod=$io_datastore->getValue("hect_prod",$z2);
							$ls_nrohectsinprod=$io_datastore->getValue("hect_sinprod",$z2);
							$ls_nrohect=number_format($ls_nrohect,2, ',', '.');
							$ls_nrohectsinprod=number_format($ls_nrohectsinprod,2, ',', '.');
							$hectprod=number_format($hectprod,2, ',', '.');

						}
					}
				}
			}
			$li_filasretenciones=1; //Nro de filas que mostraro el grid al cargar el formulario
			$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
			$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=35 readonly>";
			$la_objectretenciones[$li_filasretenciones][3]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
			/**************   GRID   RUBROS AGRoCOLAS   *******************/
			$li_filasrubrosagri=1; //Nro de filas que mostraro el grid al cargar el formulario
			$la_objectrubrosagri[$li_filasrubrosagri][1]="<input name=txtcodrubrosagri".$li_filasrubrosagri." type=hidden id=txtcodrubrosagri".$li_filasrubrosagri." class=sin-borde style= text-align:center size=20 readonly><input name=txtcodclaagri".$li_filasrubrosagri." type=hidden id=txtcodclaagri".$li_filasrubrosagri." class=sin-borde style= text-align:center size=20 readonly><input name=txtdesrubrosagri".$li_filasrubrosagri." type=text id=txtdesrubrosagri".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
			$la_objectrubrosagri[$li_filasrubrosagri][2]="<input name=txthectsembradas".$li_filasrubrosagri." type=text id=txthectsembradas".$li_filasrubrosagri." class=sin-borde style= text-align:left size=8>";
			$la_objectrubrosagri[$li_filasrubrosagri][3]="<input name=txtnro".$li_filasrubrosagri." type=text id=txtnro".$li_filasrubrosagri." class=sin-borde style= text-align:left size=4 readonly>";
			$la_objectrubrosagri[$li_filasrubrosagri][4]="<input name=txtcantprod".$li_filasrubrosagri." type=text id=txtcantprod".$li_filasrubrosagri." class=sin-borde style= text-align:left size=12 readonly>";
			$la_objectrubrosagri[$li_filasrubrosagri][5]="<input name=txttipoprod".$li_filasrubrosagri." type=text id=txttipoprod".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
			$la_objectrubrosagri[$li_filasrubrosagri][6]="<input name=txttipo".$li_filasrubrosagri." type=text id=txttipo".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
			$la_objectrubrosagri[$li_filasrubrosagri][7]="<input name=txtciclo".$li_filasrubrosagri." type=text id=txtciclo".$li_filasrubrosagri." class=sin-borde style= text-align:left size=5 readonly>";
			$la_objectrubrosagri[$li_filasrubrosagri][8]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=3 >";
			$la_objectrubrosagri[$li_filasrubrosagri][9]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=4 readonly>";
			/**************   GRID   RUBROS PECUARIOS   *******************/
			$li_filasrubrospec=1; //Nro de filas que mostraro el grid al cargar el formulario
			$la_objectrubrospec[$li_filasrubrospec][1]="<input name=txtcodrubrospec".$li_filasrubrospec." type=hidden id=txtcodrubrospec".$li_filasrubrospec." class=sin-borde style= text-align:center size=20 readonly><input name=txtcodclapec".$li_filasrubrospec." type=hidden id=txtcodclapec".$li_filasrubrospec." class=sin-borde style= text-align:center size=20 readonly><input name=txtdesrubrospec".$li_filasrubrospec." type=text id=txtdesrubrospec".$li_filasrubrospec." class=sin-borde style= text-align:left size=20 readonly>";
			$la_objectrubrospec[$li_filasrubrospec][2]="<input name=txtnropec".$li_filasrubrospec." type=text id=txtnropec".$li_filasrubrospec." class=sin-borde style= text-align:left size=8 readonly><input name=txthas".$li_filasrubrospec." type=hidden id=txthas".$li_filasrubrospec." class=sin-borde style= text-align:left size=8>";
			$la_objectrubrospec[$li_filasrubrospec][3]="<input name=txthectpec".$li_filasrubrospec." type=text id=txthectpec".$li_filasrubrospec." class=sin-borde style= text-align:left size=6>";
			$la_objectrubrospec[$li_filasrubrospec][4]="<input name=txtnroanimal".$li_filasrubrospec." type=text id=txtnroanimal".$li_filasrubrospec." class=sin-borde style= text-align:left size=4 readonly>";
			$la_objectrubrospec[$li_filasrubrospec][5]="<input name=txtcantprodpec".$li_filasrubrospec." type=text id=txtcantprod".$li_filasrubrospec." class=sin-borde style= text-align:left size=20 readonly>";
			$la_objectrubrospec[$li_filasrubrospec][6]="<input name=txttipoprodpec".$li_filasrubrospec." type=text id=txttipoprodpec".$li_filasrubrospec." class=sin-borde style= text-align:left size=35 readonly>";
			$la_objectrubrospec[$li_filasrubrospec][7]="<input name=txttipopec".$li_filasrubrospec." type=text id=txttipopec".$li_filasrubrospec." class=sin-borde style= text-align:left size=50 readonly>";
			$la_objectrubrospec[$li_filasrubrospec][8]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=4 readonly>";
			/********************************************************************************************************************************/
			$ls_cadena="SELECT cd.*,d.dended FROM sfc_cliente c,sfc_clientededuccion cd,sigesp_deducciones d WHERE d.codemp=cd.codemp AND cd.codcli=c.codcli AND d.codded=cd.codded AND cd.codcli='".$ls_codcli."'";
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
					$totrow=$io_datastore->getRowCount("codcli");
					for($li_i=1;$li_i<=$totrow;$li_i++)
					{
						$ls_codigo=$io_datastore->getValue("codded",$li_i);
		                $ls_descripcion=$io_datastore->getValue("dended",$li_i);

						$la_objectretenciones[$li_i][1]="<input name=txtcodret".$li_i." type=text id=txtcodret".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
						$la_objectretenciones[$li_i][2]="<input name=txtdesret".$li_i." type=text id=txtdesret".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=35 readonly>";
						$la_objectretenciones[$li_i][3]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerretenciones(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
   	 			    }
					$li_filasretenciones=$li_i;
					$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
					$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=35 readonly>";
					$la_objectretenciones[$li_filasretenciones][3]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
            }
		}
/*********************************************************      RUBROS AGRoCOLAS           ********************************************************************************/
		$ls_cadena1="SELECT ra.*,cla.denominacion as dencla,cla.prod_estimada,ru.denominacion as denrubro,re.denominacion as denrenglon,ci.id_ciclo,ci.denominacion as deno_rubro,ru.id_ciclo
					 FROM sfc_ciclo ci,sfc_rubroagri_cliente ra,sfc_clasificacionrubro cla,sfc_rubro ru,sfc_renglon re
					 WHERE ra.id_rubro=cla.id_clasificacion AND cla.id_rubro=ru.id_rubro AND ru.id_renglon=re.id_renglon AND ci.id_ciclo=ru.id_ciclo AND
					 re.id_tipoexplotacion like '1' AND ra.codcli like '".$ls_codcli."'";
			$arr_rubrosagri=$io_sql->select($ls_cadena1);
			if($arr_rubrosagri==false&&($io_sql->message!=""))
			{
				$is_msg->message("No hay registros de rubros agrocolas");
			}
			else
			{
				if($row1=$io_sql->fetch_row($arr_rubrosagri))
 				  {
					$la_rubrosagri=$io_sql->obtener_datos($arr_rubrosagri);
					$io_datastore->data=$la_rubrosagri;
					$totrow1=$io_datastore->getRowCount("codcli");
					for($li_i=1;$li_i<=$totrow1;$li_i++)
					{
						$ls_codigo       = $io_datastore->getValue("id_clasificacion",$li_i);
						$ls_codclaagri   = $io_datastore->getValue("cod_clasificacion",$li_i);
						$ls_descripcion  = $io_datastore->getValue("dencla",$li_i);
						$ls_hectsembradas= $io_datastore->getValue("hect_prod",$li_i);
						$ls_tiporub      = $io_datastore->getValue("denrubro",$li_i);
						$ls_tipoprod     = $io_datastore->getValue("denrenglon",$li_i);
						$ls_cantprod     = $io_datastore->getValue("cant_prod",$li_i);
						$ls_prodest      = $io_datastore->getValue("prod_estimada",$li_i);
						$ls_ciclo        = $io_datastore->getValue("deno_rubro",$li_i);
						$ls_estarub      = $io_datastore->getValue("estarub",$li_i);
						$ls_hectsembradas= number_format($ls_hectsembradas,2, ',', '.');
						$ls_cantprod     = number_format($ls_cantprod,2, ',', '.');
						$ls_prodest      = number_format($ls_prodest,2, ',', '.');

						$la_objectrubrosagri[$li_i][1]="<input name=txtcodrubrosagri".$li_i." type=hidden id=txtcodrubrosagri".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=20 readonly><input name=txtcodclaagri".$li_i." type=hidden id=txtcodclaagri".$li_i." class=sin-borde value='".$ls_codclaagri."' style= text-align:center size=20 readonly><input name=txtdesrubrosagri".$li_i." type=text id=txtdesrubrosagri".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=35 readonly>";
						$la_objectrubrosagri[$li_i][2]="<input name=txthectsembradas".$li_i."  type=text id=txthectsembradas".$li_i." class=sin-borde value='".$ls_hectsembradas."' style= text-align:left size=8 onKeyPress=return(currencyFormat(this,'.',',',event))>";
						$la_objectrubrosagri[$li_i][3]="<input name=txtnro".$li_i." type=text id=txtnro".$li_i." value='".$ls_prodest."' class=sin-borde style= text-align:left size=4 readonly>";
						$la_objectrubrosagri[$li_i][4]="<input name=txtcantprod".$li_i." type=text id=txtcantprod".$li_i." value='".$ls_cantprod."' class=sin-borde style= text-align:left size=12 readonly>";
						$la_objectrubrosagri[$li_i][5]="<input name=txttipoprod".$li_i." type=text id=txttipoprod".$li_i." value='".$ls_tipoprod."' class=sin-borde style= text-align:left size=35 readonly>";
						$la_objectrubrosagri[$li_i][6]="<input name=txttipo".$li_i." type=text id=txttipo".$li_i." class=sin-borde value='".$ls_tiporub."' style= text-align:left size=35 readonly>";
						$la_objectrubrosagri[$li_i][7]="<input name=txtciclo".$li_i." type=text id=txtciclo".$li_i." class=sin-borde value='".$ls_ciclo."' style= text-align:left size=15 readonly>";
						if ($ls_estarub=='1')
						{
							$la_objectrubrosagri[$li_i][8]= "<input name=chk".$li_i." type=checkbox id=chk".$li_i." checked=checked   class=sin-borde  onChange=javascript:ue_subtotal()>";
						}
						else
						{
							$la_objectrubrosagri[$li_i][8]= "<input name=chk".$li_i." type=checkbox id=chk".$li_i." value=chk  class=sin-borde  onChange=javascript:ue_subtotal()>";
						}
						$la_objectrubrosagri[$li_i][9]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerrubroagri(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
					}
					$li_filasrubrosagri=$li_i;
					$la_objectrubrosagri[$li_filasrubrosagri][1]="<input name=txtcodrubrosagri".$li_filasrubrosagri." type=hidden id=txtcodrubrosagri".$li_filasrubrosagri." class=sin-borde style= text-align:center size=20 readonly><input name=txtcodclaagri".$li_filasrubrosagri." type=hidden id=txtcodclaagri".$li_filasrubrosagri." class=sin-borde style= text-align:center size=20 readonly><input name=txtdesrubrosagri".$li_filasrubrosagri." type=text id=txtdesrubrosagri".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
					$la_objectrubrosagri[$li_filasrubrosagri][2]="<input name=txthectsembradas".$li_filasrubrosagri." type=text id=txthectsembradas".$li_filasrubrosagri." class=sin-borde style= text-align:left size=8 readonly>";
					$la_objectrubrosagri[$li_filasrubrosagri][3]="<input name=txtnro".$li_filasrubrosagri." type=text id=txtnro".$li_filasrubrosagri." class=sin-borde style= text-align:left size=4 readonly>";
					$la_objectrubrosagri[$li_filasrubrosagri][4]="<input name=txtcantprod".$li_filasrubrosagri." type=text id=txtcantprod".$li_filasrubrosagri." class=sin-borde style= text-align:left size=12 readonly>";
					$la_objectrubrosagri[$li_filasrubrosagri][5]="<input name=txttipoprod".$li_filasrubrosagri." type=text id=txttipoprod".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
					$la_objectrubrosagri[$li_filasrubrosagri][6]="<input name=txttipo".$li_filasrubrosagri." type=text id=txttipo".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
					$la_objectrubrosagri[$li_filasrubrosagri][7]="<input name=txtciclo".$li_filasrubrosagri." type=text id=txtciclo".$li_filasrubrosagri." class=sin-borde style= text-align:left size=5 readonly>";
					$la_objectrubrosagri[$li_filasrubrosagri][8]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=3 >";
					$la_objectrubrosagri[$li_filasrubrosagri][9]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
				}
			}
/*********************************************************      RUBROS PECUARIOS           ********************************************************************************/
			$ls_cadena2="SELECT  rp.*,cla.denominacion as dencla,cla.prod_estimada,cla.animal_has,ru.denominacion as denrubro,re.denominacion as denrenglon
			             FROM    sfc_rubropec_cliente rp,sfc_clasificacionrubro cla,sfc_rubro as ru,sfc_renglon re
						 WHERE   rp.id_rubro  = cla.id_clasificacion
						 AND     cla.id_rubro = ru.id_rubro
						 AND     ru.id_renglon= re.id_renglon
						 AND     re.id_tipoexplotacion like '2'
 			             AND     rp.codcli like '".$ls_codcliente."'";
			$arr_rubrospec=$io_sql->select($ls_cadena2);
			if($arr_rubrospec==false&&($io_sql->message!=""))
			{
				$is_msg->message("No hay registros de rubros pecuarios");
			}
			else
			{
				if($row2=$io_sql->fetch_row($arr_rubrospec))
 				  {
					$la_rubrospec=$io_sql->obtener_datos($arr_rubrospec);
					$io_datastore->data=$la_rubrospec;
					$totrow2=$io_datastore->getRowCount("codcli");
					for($li_i=1;$li_i<=$totrow2;$li_i++)
					{
						$ls_codigopec      = $io_datastore->getValue("id_clasificacion",$li_i);
						$ls_descripcionpec = $io_datastore->getValue("dencla",$li_i);
						$ls_hectpec        = $io_datastore->getValue("hect_prod",$li_i);
						$ls_tiporubpec     = $io_datastore->getValue("denrubro",$li_i);
						$ls_tipoprodpec    = $io_datastore->getValue("denrenglon",$li_i);
						$ls_cantprodpec    = $io_datastore->getValue("cant_pro",$li_i);
						$ls_prodestpec     = $io_datastore->getValue("prod_estimada",$li_i);
						$ls_hectpec        = number_format($ls_hectpec,2, ',', '.');
						$ls_cantprodpec    = number_format($ls_cantprodpec,2, ',', '.');
						$ls_nroanimal      = number_format($io_datastore->getValue("nro_animales",$li_i),2, ',', '.');
						$ls_prodestpec     = number_format($ls_prodestpec,2, ',', '.');
						$ls_has            = number_format($io_datastore->getValue("animal_has",$li_i),2, ',', '.');

						$la_objectrubrospec[$li_i][1]="<input name=txtcodrubrospec".$li_i." type=hidden id=txtcodrubrospec".$li_i." class=sin-borde value='".$ls_codigopec."' style= text-align:center size=20 readonly><input name=txtcodclapec".$li_i." type=hidden id=txtcodclapec".$li_i." class=sin-borde value='".$ls_codclapec."' style= text-align:center size=20 readonly><input name=txtdesrubrospec".$li_i." type=text id=txtdesrubrospec".$li_i." class=sin-borde value='".$ls_descripcionpec."' style= text-align:left size=20 readonly>";
						$la_objectrubrospec[$li_i][2]="<input name=txtnropec".$li_i." type=text id=txtnropec".$li_i." value='".$ls_nroanimal."' class=sin-borde style= text-align:left size=8 onKeyPress=return(currencyFormat(this,'.',',',event))><input name=txthas".$li_i." type=hidden id=txthas".$li_i." class=sin-borde value='".$ls_has."' style= text-align:left size=8>";
						$la_objectrubrospec[$li_i][3]="<input name=txthectpec".$li_i." onChange=javascript:ue_subtotal2(); type=text id=txthectpec".$li_i." class=sin-borde value='".$ls_hectpec."' style= text-align:left size=6 onKeyPress=return(currencyFormat(this,'.',',',event)) readonly>";
						$la_objectrubrospec[$li_i][4]="<input name=txtnroanimal".$li_i." type=text id=txtnroanimal".$li_i." value='".$ls_prodestpec."' class=sin-borde style= text-align:left size=4 onKeyPress=return(currencyFormat(this,'.',',',event)) readonly>";
						$la_objectrubrospec[$li_i][5]="<input name=txtcantprodpec".$li_i." type=text id=txtcantprodpec".$li_i." value='".$ls_cantprodpec."' class=sin-borde style= text-align:left size=20 onKeyPress=return(currencyFormat(this,'.',',',event)) readonly>";
						$la_objectrubrospec[$li_i][6]="<input name=txttipoprodpec".$li_i." type=text id=txttipoprodpec".$li_i." value='".$ls_tipoprodpec."' class=sin-borde style= text-align:left size=35 readonly>";
						$la_objectrubrospec[$li_i][7]="<input name=txttipopec".$li_i." type=text id=txttipopec".$li_i." class=sin-borde value='".$ls_tiporubpec."' style= text-align:left size=50 readonly>";
						$la_objectrubrospec[$li_i][8]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerrubropec(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
					}
					$li_filasrubrospec=$li_i;
					$la_objectrubrospec[$li_filasrubrospec][1]="<input name=txtcodrubrospec".$li_filasrubrospec." type=hidden id=txtcodrubrospec".$li_filasrubrospec." class=sin-borde style= text-align:center size=20 readonly><input name=ls_codclapec".$li_filasrubrospec." type=hidden id=ls_codclapec".$li_filasrubrospec." class=sin-borde style= text-align:center size=20 readonly><input name=txtdesrubrospec".$li_filasrubrospec." type=text id=txtdesrubrospec".$li_filasrubrospec." class=sin-borde style= text-align:left size=20 readonly>";
					$la_objectrubrospec[$li_filasrubrospec][2]="<input name=txtnropec".$li_filasrubrospec." type=text id=txtnropec".$li_filasrubrospec." class=sin-borde style= text-align:left size=8 readonly><input name=txthas".$li_filasrubrospec." type=hidden id=txthas class=sin-borde style= text-align:left size=8>";
					$la_objectrubrospec[$li_filasrubrospec][3]="<input name=txthectpec".$li_filasrubrospec." type=text id=txthectpec".$li_filasrubrospec." class=sin-borde style= text-align:left size=20 readonly>";
					$la_objectrubrospec[$li_filasrubrospec][4]="<input name=txtnroanimal".$li_filasrubrospec." type=text id=txtnroanimal".$li_filasrubrospec." class=sin-borde style= text-align:left size=4 readonly>";
					$la_objectrubrospec[$li_filasrubrospec][5]="<input name=txtcantprodpec".$li_filasrubrospec." type=text id=txtcantprodpec".$li_filasrubrospec." class=sin-borde style= text-align:left size=20 readonly>";
					$la_objectrubrospec[$li_filasrubrospec][6]="<input name=txttipoprodpec".$li_filasrubrospec." type=text id=txttipoprodpec".$li_filasrubrospec." class=sin-borde style= text-align:left size=35 readonly>";
					$la_objectrubrospec[$li_filasrubrospec][7]="<input name=txttipopec".$li_filasrubrospec." type=text id=txttipopec".$li_filasrubrospec." class=sin-borde style= text-align:left size=50 readonly>";
					$la_objectrubrospec[$li_filasrubrospec][8]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
				}
			}
			$ls_readonly   ="readonly";
			$ls_readonlyced="readonly";
	   }
	   else
	   {
			/*  $lb_valido=$io_cliente->uf_guardar_cliente($ls_codcli,$ls_tipcli.$ls_cedcli,$ls_nomcli,$ls_dircli,$ls_telcli,$ls_celcli,
														   $ls_codpai,$ls_codest,$ls_codmun,$ls_codpar,$ls_precioestandar,$ls_productor,$la_seguridad);
			*/
            $lb_valido=$io_cliente->uf_validar_cliente_onidex($ls_tipcli,$ls_cedcli,$ls_nombre);
			if($lb_valido)
			{
				$ls_nomcli = $ls_nombre;
				$ls_readonly   ="readonly";
				$ls_readonlyced="readonly";
			}
			else
			{
				$ls_readonly   ="readonly";
				$ls_readonlyced="readonly";
			}

   	   }
	}
/************************************************************************************************************************/
/***************************   VERIFICA SI EL CLIENTE EXISTE   **********************************************************/
/************************************************************************************************************************/
	elseif($ls_operacion=="ue_validar_carta")
	{
	    $ls_sql="SELECT *
                 FROM sfc_productor
                 WHERE codemp='".$la_datemp["codemp"]."' AND nro_cartagr='".$ls_nrocartagr."'";
	    $lb_valido=$io_utilidad->uf_datacombo($ls_sql,&$la_productor);

		if ($lb_valido==true)
		{
		    $is_msg->message ("El Nomero de Documento ya existe, esta registrada para otro Productor!!");
			$ls_nrocartagr="";
	    }
	}
/*****************************************************************************************************************************/
/***************************   ACTUALIZA EL    VALOR  DEL  CHECK ***************************************************************/
/*****************************************************************************************************************************/
	elseif($ls_operacion=="ue_actualizar_check")
	{
	   $ls_sql="SELECT *
                FROM sfc_cliente
                WHERE codemp='".$la_datemp["codemp"]."' AND codcli=".$ls_codcli;
	    $lb_valido=$io_utilidad->uf_datacombo($ls_sql,&$la_cliente);
		if ($lb_valido==true)
		{
		 // $is_msg->message ("El Cliente esta registrado!!");
		  $io_datastore->data=$la_cliente;
		  $ls_cedcli=$io_datastore->getValue("cedcli",1);
		  $ls_tipcli=substr($ls_cedcli,0,1);
		  $ls_cedcliaux=$ls_cedcli;
		  $ls_cedcli=substr($ls_cedcli,1,strlen($ls_cedcli));
		  $ls_codcli=$io_datastore->getValue("codcli",1);
		  $ls_nomcli=$io_datastore->getValue("razcli",1);
		  $ls_dircli=$io_datastore->getValue("dircli",1);
		  $ls_telcli=$io_datastore->getValue("telcli",1);
		  $ls_celcli=$io_datastore->getValue("celcli",1);
		  $ls_codpai=$io_datastore->getValue("codpai",1);
		  $ls_codest=$io_datastore->getValue("codest",1);
		  $ls_codmun=$io_datastore->getValue("codmun",1);
		  $ls_codpar=$io_datastore->getValue("codpar",1);
		  $ls_precioestandar=$io_datastore->getValue("precio_estandar",1);
		 $ls_tentierra=$io_datastore->getValue("tentierra",1);
		// $ls_productor=$io_datastore->getValue("productor",1);
		}
		if ($ls_productor=="NO")
	    {
			$ls_productor="SI";
			$ls_tentierra="";
		}
		else
		{
		$ls_productor="NO";
		if ($ls_nrocartagr!="")
		{
			$io_productor->uf_delete_rubroproductor($ls_codcli,$la_seguridad);
			$io_productor->uf_delete_rubropecproductor($ls_codcli,$la_seguridad);
			$io_productor->uf_delete_productor($ls_codcli,$la_seguridad);
			$lb_valido=$io_cliente->uf_guardar_cliente($ls_codcli,$ls_tipcli.$ls_cedcli,$ls_nomcli,$ls_dircli,$ls_telcli,$ls_celcli,
	                                                   $ls_codpai,$ls_codest,$ls_codmun,$ls_codpar,$ls_precioestandar,$ls_productor,$la_seguridad);
		}
	}
}
/*****************************************************************************************************************************/
/***************************   CARGA LOS DATOS DEL CLIENTE ************************* ****************************************/
/*****************************************************************************************************************************/
elseif($ls_operacion=="ue_cargarcliente")
	{
	  $ls_sql="SELECT *
               FROM sfc_cliente
               WHERE codemp='".$la_datemp["codemp"]."' AND codcli='".$ls_codcli."'";
	    $lb_valido=$io_utilidad->uf_datacombo($ls_sql,&$la_cliente);
		if ($lb_valido==true)
		{
		  $io_datastore->data=$la_cliente;
		  $ls_cedcli=$io_datastore->getValue("cedcli",1);
		  $ls_tipcli=substr($ls_cedcli,0,1);
		  $ls_cedcliaux=$ls_cedcli;
		  $ls_cedcli=substr($ls_cedcli,1,strlen($ls_cedcli));
		  $ls_codcli=$io_datastore->getValue("codcli",1);
		  $ls_nomcli=$io_datastore->getValue("razcli",1);
		  $ls_dircli=$io_datastore->getValue("dircli",1);
		  $ls_telcli=$io_datastore->getValue("telcli",1);
		  $ls_celcli=$io_datastore->getValue("celcli",1);
		  $ls_codpai=$io_datastore->getValue("codpai",1);
		  $ls_codest=$io_datastore->getValue("codest",1);
		  $ls_codmun=$io_datastore->getValue("codmun",1);
		  $ls_codpar=$io_datastore->getValue("codpar",1);
		  $ls_precioestandar=$io_datastore->getValue("precio_venta",1);
		  $ls_productor=$io_datastore->getValue("productor",1);
		  if ($ls_productor=="SI")
		  {
			$ls_codemp=$la_datemp["codemp"];
			$ls_cadena2="SELECT *
			             FROM sfc_productor
						 WHERE codcli='".$ls_codcli."'";
			$rs_datauni2=$io_sql->select($ls_cadena2);
			if($rs_datauni2==false&&($io_sql->message!=""))
			{
				$is_msg->message("No hay registros");
			}
			else
			{
				if($row2=$io_sql->fetch_row($rs_datauni2))
				{
					$la_prod=$io_sql->obtener_datos($rs_datauni2);
					$io_datastore->data=$la_prod;
					$totrow2=$io_datastore->getRowCount("codcli");
					for($z2=1;$z2<=$totrow2;$z2++)
					{
						$ls_nrocartagr     = $io_datastore->getValue("nro_cartagr",$z2);
						$ls_nrohect        = $io_datastore->getValue("hect_tot",$z2);
						$hectprod          = $io_datastore->getValue("hect_prod",$z2);
						$ls_tentierra      = $io_datastore->getValue("codtenencia",$z2);
						$ls_nrohectsinprod = $io_datastore->getValue("hect_sinprod",$z2);
						$ls_nrohect        = number_format($ls_nrohect,2, ',', '.');
						$ls_nrohectsinprod = number_format($ls_nrohectsinprod,2, ',', '.');
						$hectprod=number_format($hectprod,2, ',', '.');
					}
				}
			}
		}
		$ls_codpai=$_POST["hidcodpai"];
		$ls_codest=$_POST["hidcodest"];
		$ls_codmun=$_POST["hidcodmun"];
		$ls_codpar=$_POST["hidcodpar"];
		$ls_precioestandar=$_POST["hidprecioestandar"];
		$ls_tentierra=$_POST["hidtentierra"];
		$li_filasretenciones=1; //Nro de filas que mostraro el grid al cargar el formulario
	    $la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
	    $la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=35 readonly>";
	    $la_objectretenciones[$li_filasretenciones][3]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
		/**************   GRID   RUBROS AGRoCOLAS   *******************/
		$li_filasrubrosagri=1; //Nro de filas que mostraro el grid al cargar el formulario
	    $la_objectrubrosagri[$li_filasrubrosagri][1]="<input name=txtcodrubrosagri".$li_filasrubrosagri." type=hidden id=txtcodrubrosagri".$li_filasrubrosagri." class=sin-borde style= text-align:center size=20 readonly><input name=txtcodclaagri".$li_filasrubrosagri." type=hidden id=txtcodclaagri".$li_filasrubrosagri." class=sin-borde style= text-align:center size=20 readonly><input name=txtdesrubrosagri".$li_filasrubrosagri." type=text id=txtdesrubrosagri".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
	    $la_objectrubrosagri[$li_filasrubrosagri][2]="<input name=txthectsembradas".$li_filasrubrosagri." type=text id=txthectsembradas".$li_filasrubrosagri." class=sin-borde style= text-align:left size=8>";
		$la_objectrubrosagri[$li_filasrubrosagri][3]="<input name=txtnro".$li_filasrubrosagri." type=text id=txtnro".$li_filasrubrosagri." class=sin-borde style= text-align:left size=4 readonly>";
		$la_objectrubrosagri[$li_filasrubrosagri][4]="<input name=txtcantprod".$li_filasrubrosagri." type=text id=txtcantprod".$li_filasrubrosagri." class=sin-borde style= text-align:left size=12 readonly>";
		$la_objectrubrosagri[$li_filasrubrosagri][5]="<input name=txttipoprod".$li_filasrubrosagri." type=text id=txttipoprod".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
		$la_objectrubrosagri[$li_filasrubrosagri][6]="<input name=txttipo".$li_filasrubrosagri." type=text id=txttipo".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
		$la_objectrubrosagri[$li_filasrubrosagri][7]="<input name=txtciclo".$li_filasrubrosagri." type=text id=txtciclo".$li_filasrubrosagri." class=sin-borde style= text-align:left size=5 readonly>";
		$la_objectrubrosagri[$li_filasrubrosagri][8]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=3 >";
		$la_objectrubrosagri[$li_filasrubrosagri][9]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=4 readonly>";
		/**************   GRID   RUBROS PECUARIOS   *******************/
		$li_filasrubrospec=1; //Nro de filas que mostraro el grid al cargar el formulario
	    $la_objectrubrospec[$li_filasrubrospec][1]="<input name=txtcodrubrospec".$li_filasrubrospec." type=hidden id=txtcodrubrospec".$li_filasrubrospec." class=sin-borde style= text-align:center size=20 readonly><input name=txtcodclapec".$li_filasrubrospec." type=hidden id=txtcodclapec".$li_filasrubrospec." class=sin-borde style= text-align:center size=20 readonly><input name=txtdesrubrospec".$li_filasrubrospec." type=text id=txtdesrubrospec".$li_filasrubrospec." class=sin-borde style= text-align:left size=20 readonly>";
	    $la_objectrubrospec[$li_filasrubrospec][2]="<input name=txtnropec".$li_filasrubrospec." type=text id=txtnropec".$li_filasrubrospec." class=sin-borde style= text-align:left size=8 readonly><input name=txthas".$li_filasrubrospec." type=hidden id=txthas".$li_filasrubrospec." class=sin-borde style= text-align:left size=8>";
		$la_objectrubrospec[$li_filasrubrospec][3]="<input name=txthectpec".$li_filasrubrospec." type=text id=txthectpec".$li_filasrubrospec." class=sin-borde style= text-align:left size=6>";
		$la_objectrubrospec[$li_filasrubrospec][4]="<input name=txtnroanimal".$li_filasrubrospec." type=text id=txtnroanimal".$li_filasrubrospec." class=sin-borde style= text-align:left size=4 readonly>";
		$la_objectrubrospec[$li_filasrubrospec][5]="<input name=txtcantprodpec".$li_filasrubrospec." type=text id=txtcantprod".$li_filasrubrospec." class=sin-borde style= text-align:left size=20 readonly>";
		$la_objectrubrospec[$li_filasrubrospec][6]="<input name=txttipoprodpec".$li_filasrubrospec." type=text id=txttipoprodpec".$li_filasrubrospec." class=sin-borde style= text-align:left size=35 readonly>";
		$la_objectrubrospec[$li_filasrubrospec][7]="<input name=txttipopec".$li_filasrubrospec." type=text id=txttipopec".$li_filasrubrospec." class=sin-borde style= text-align:left size=50 readonly>";
		$la_objectrubrospec[$li_filasrubrospec][8]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=4 readonly>";
		/********************************************************************************************************************************/
		$ls_cadena="SELECT cd.*,d.dended
		            FROM sfc_cliente c,sfc_clientededuccion cd,sigesp_deducciones d
					WHERE d.codemp=cd.codemp AND cd.codcli=c.codcli
					AND d.codded=cd.codded AND cd.codcli='".$ls_codcli."'";

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
					$totrow=$io_datastore->getRowCount("codcli");

					for($li_i=1;$li_i<=$totrow;$li_i++)
					{
						$ls_codigo=$io_datastore->getValue("codded",$li_i);
		                $ls_descripcion=$io_datastore->getValue("dended",$li_i);

				$la_objectretenciones[$li_i][1]="<input name=txtcodret".$li_i." type=text id=txtcodret".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
				$la_objectretenciones[$li_i][2]="<input name=txtdesret".$li_i." type=text id=txtdesret".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=35 readonly>";
				$la_objectretenciones[$li_i][3]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerretenciones(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";

		   	 	   }

	$li_filasretenciones=$li_i;

	$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=35 readonly>";
	$la_objectretenciones[$li_filasretenciones][3]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
  }
}
/*********************************************************      RUBROS AGRoCOLAS           ********************************************************************************/
			 $ls_cadena1="SELECT ra.*,cla.denominacion as dencla,cla.prod_estimada,ru.denominacion as denrubro,
								 re.denominacion as denrenglon,ci.id_ciclo,substr(ci.denominacion,0,4) as deno_rubro,ru.id_ciclo
						  FROM   sfc_rubroagri_cliente ra,sfc_clasificacionrubro cla,sfc_rubro ru,sfc_ciclo ci,sfc_renglon re
						  WHERE  ra.id_clasificacion=cla.id_clasificacion AND cla.id_rubro=ru.id_rubro AND
								 ru.id_renglon=re.id_renglon AND ci.id_ciclo=ru.id_ciclo AND
								 ra.cod_clasificacion=cla.cod_clasificacion AND ra.codemp=cla.codemp AND 
								 re.id_tipoexplotacion like '1' AND ra.codcli like '".$ls_codcli."'";
			//print $ls_cadena1;
			$arr_rubrosagri=$io_sql->select($ls_cadena1);
			if($arr_rubrosagri==false&&($io_sql->message!=""))
			{
				$is_msg->message("No hay registros de rubros agricolas");
			}
			else
			{
				if($row1=$io_sql->fetch_row($arr_rubrosagri))
 				  {
					$la_rubrosagri=$io_sql->obtener_datos($arr_rubrosagri);
					$io_datastore->data=$la_rubrosagri;
					$totrow1=$io_datastore->getRowCount("codcli");
					for($li_i=1;$li_i<=$totrow1;$li_i++)
					{
						$ls_codigo       = $io_datastore->getValue("id_clasificacion",$li_i);
						$ls_codclaagri   = $io_datastore->getValue("cod_clasificacion",$li_i);
						$ls_descripcion  = $io_datastore->getValue("dencla",$li_i);
						$ls_hectsembradas= $io_datastore->getValue("hect_prod",$li_i);
						$ls_tiporub      = $io_datastore->getValue("denrubro",$li_i);
						$ls_tipoprod     = $io_datastore->getValue("denrenglon",$li_i);
						$ls_cantprod     = $io_datastore->getValue("cant_pro",$li_i);
						$ls_prodest      = $io_datastore->getValue("prod_estimada",$li_i);
						$ls_ciclo        = $io_datastore->getValue("deno_rubro",$li_i);
						$ls_estarub      = $io_datastore->getValue("estarub",$li_i);
						$ls_hectsembradas= number_format($ls_hectsembradas,2, ',', '.');
						$ls_cantprod     = number_format($ls_cantprod,2, ',', '.');
						$ls_prodest      = number_format($ls_prodest,2, ',', '.');

						$la_objectrubrosagri[$li_i][1]="<input name=txtcodrubrosagri".$li_i." type=hidden id=txtcodrubrosagri".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=20 readonly><input name=txtcodclaagri".$li_i." type=hidden id=txtcodclaagri".$li_i." class=sin-borde value='".$ls_codclaagri."' style= text-align:center size=20 readonly><input name=txtdesrubrosagri".$li_i." type=text id=txtdesrubrosagri".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=35 readonly>";
						$la_objectrubrosagri[$li_i][2]="<input name=txthectsembradas".$li_i." type=text id=txthectsembradas".$li_i." class=sin-borde value='".$ls_hectsembradas."' style= text-align:left size=8 onKeyPress=return(currencyFormat(this,'.',',',event))>";
						$la_objectrubrosagri[$li_i][3]="<input name=txtnro".$li_i." type=text id=txtnro".$li_i." value='".$ls_prodest."' class=sin-borde style= text-align:left size=4 readonly>";
						$la_objectrubrosagri[$li_i][4]="<input name=txtcantprod".$li_i." type=text id=txtcantprod".$li_i." value='".$ls_cantprod."' class=sin-borde style= text-align:left size=12 readonly>";
						$la_objectrubrosagri[$li_i][5]="<input name=txttipoprod".$li_i." type=text id=txttipoprod".$li_i." value='".$ls_tipoprod."' class=sin-borde style= text-align:left size=35 readonly>";
						$la_objectrubrosagri[$li_i][6]="<input name=txttipo".$li_i." type=text id=txttipo".$li_i." class=sin-borde value='".$ls_tiporub."' style= text-align:left size=35 readonly>";
						$la_objectrubrosagri[$li_i][7]="<input name=txtciclo".$li_i." type=text id=txtciclo".$li_i." class=sin-borde value='".$ls_ciclo."' style= text-align:left size=5 readonly>";
						if ($ls_estarub=='1')
						{
							$la_objectrubrosagri[$li_i][8]= "<input name=chk".$li_i." type=checkbox id=chk".$li_i." checked=checked   class=sin-borde  onChange=javascript:ue_subtotal()>";
						}
						else
						{
							$la_objectrubrosagri[$li_i][8]= "<input name=chk".$li_i." type=checkbox id=chk".$li_i." value=chk  class=sin-borde  onChange=javascript:ue_subtotal()>";
						}
						$la_objectrubrosagri[$li_i][9]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerrubroagri(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
					}
					$li_filasrubrosagri=$li_i;
						$la_objectrubrosagri[$li_filasrubrosagri][1]="<input name=txtcodrubrosagri".$li_filasrubrosagri." type=hidden id=txtcodrubrosagri".$li_filasrubrosagri." class=sin-borde style= text-align:center size=20 readonly><input name=txtcodclaagri".$li_filasrubrosagri." type=hidden id=txtcodclaagri".$li_filasrubrosagri." class=sin-borde style= text-align:center size=20 readonly><input name=txtdesrubrosagri".$li_filasrubrosagri." type=text id=txtdesrubrosagri".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
						$la_objectrubrosagri[$li_filasrubrosagri][2]="<input name=txthectsembradas".$li_filasrubrosagri." type=text id=txthectsembradas".$li_filasrubrosagri." class=sin-borde style= text-align:left size=8 readonly>";
						$la_objectrubrosagri[$li_filasrubrosagri][3]="<input name=txtnro".$li_filasrubrosagri." type=text id=txtnro".$li_filasrubrosagri." class=sin-borde style= text-align:left size=4 readonly>";
						$la_objectrubrosagri[$li_filasrubrosagri][4]="<input name=txtcantprod".$li_filasrubrosagri." type=text id=txtcantprod".$li_filasrubrosagri." class=sin-borde style= text-align:left size=12 readonly>";
						$la_objectrubrosagri[$li_filasrubrosagri][5]="<input name=txttipoprod".$li_filasrubrosagri." type=text id=txttipoprod".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
						$la_objectrubrosagri[$li_filasrubrosagri][6]="<input name=txttipo".$li_filasrubrosagri." type=text id=txttipo".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
						$la_objectrubrosagri[$li_filasrubrosagri][7]="<input name=txtciclo".$li_filasrubrosagri." type=text id=txtciclo".$li_filasrubrosagri." class=sin-borde style= text-align:left size=5 readonly>";
						$la_objectrubrosagri[$li_filasrubrosagri][8]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=3 >";
						$la_objectrubrosagri[$li_filasrubrosagri][9]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
				}
			}
/*********************************************************      RUBROS PECUARIOS           ********************************************************************************/
			$ls_cadena2="SELECT rp.*,cla.denominacion as dencla,cla.prod_estimada,cla.animal_has,
								ru.denominacion as denrubro,re.denominacion as denrenglon
						 FROM   sfc_rubropec_cliente rp,sfc_clasificacionrubro cla,sfc_rubro as ru,sfc_renglon re
						 WHERE  rp.id_clasificacion=cla.id_clasificacion
						 AND rp.cod_clasificacion=cla.cod_clasificacion AND
						 rp.codemp=cla.codemp
						 AND 	cla.id_rubro=ru.id_rubro
						 AND    ru.id_renglon=re.id_renglon
						 AND    re.id_tipoexplotacion like '2'
						 AND    rp.codcli like '".$ls_codcli."'";
			$arr_rubrospec=$io_sql->select($ls_cadena2);
			if($arr_rubrospec==false&&($io_sql->message!=""))
			{
				$is_msg->message("No hay registros de rubros pecuarios");
			}
			else
			{
				if($row2=$io_sql->fetch_row($arr_rubrospec))
 				  {
					$la_rubrospec=$io_sql->obtener_datos($arr_rubrospec);
					$io_datastore->data=$la_rubrospec;
					$totrow2=$io_datastore->getRowCount("codcli");
					for($li_i=1;$li_i<=$totrow2;$li_i++)
					{
						$ls_codigopec     = $io_datastore->getValue("id_clasificacion",$li_i);
						$ls_codclapec     = $io_datastore->getValue("cod_clasificacion",$li_i);
						$ls_descripcionpec= $io_datastore->getValue("dencla",$li_i);
						$ls_hectpec       = $io_datastore->getValue("hect_prod",$li_i);
						$ls_tiporubpec    = $io_datastore->getValue("denrubro",$li_i);
						$ls_tipoprodpec   = $io_datastore->getValue("denrenglon",$li_i);
						$ls_cantprodpec   = $io_datastore->getValue("cant_pro",$li_i);
						$ls_prodestpec    = $io_datastore->getValue("prod_estimada",$li_i);
						$ls_hectpec       = number_format($ls_hectpec,2, ',', '.');
						$ls_cantprodpec   = number_format($ls_cantprodpec,2, ',', '.');
						$ls_prodestpec    = number_format($ls_prodestpec,2, ',', '.');
						$ls_has           = number_format($io_datastore->getValue("animal_has",$li_i),2, ',', '.');
						$ls_nroanimal     = number_format($io_datastore->getValue("nro_animales",$li_i),2, ',', '.');

						$la_objectrubrospec[$li_i][1]="<input name=txtcodrubrospec".$li_i." type=hidden id=txtcodrubrospec".$li_i." class=sin-borde value='".$ls_codigopec."' style= text-align:center size=20 readonly><input name=txtcodclapec".$li_i." type=hidden id=txtcodclapec".$li_i." class=sin-borde value='".$ls_codclapec."' style= text-align:center size=20 readonly><input name=txtdesrubrospec".$li_i." type=text id=txtdesrubrospec".$li_i." class=sin-borde value='".$ls_descripcionpec."' style= text-align:left size=20 readonly>";
						$la_objectrubrospec[$li_i][2]="<input name=txtnropec".$li_i." type=text id=txtnropec".$li_i." value='".$ls_nroanimal."' class=sin-borde style= text-align:left size=8 onKeyPress=return(currencyFormat(this,'.',',',event))><input name=txthas".$li_i." type=hidden id=txthas".$li_i." class=sin-borde value='".$ls_has."' style= text-align:left size=8 onKeyPress=return(currencyFormat(this,'.',',',event))>";
						$la_objectrubrospec[$li_i][3]="<input name=txthectpec".$li_i." onChange=javascript:ue_subtotal2(); type=text id=txthectpec".$li_i." class=sin-borde value='".$ls_hectpec."' style= text-align:left size=6 onKeyPress=return(currencyFormat(this,'.',',',event))>";
						$la_objectrubrospec[$li_i][4]="<input name=txtnroanimal".$li_i." type=text id=txtnroanimal".$li_i." value='".$ls_prodestpec."' class=sin-borde style= text-align:left size=4 readonly onKeyPress=return(currencyFormat(this,'.',',',event))>";
						$la_objectrubrospec[$li_i][5]="<input name=txtcantprodpec".$li_i." type=text id=txtcantprodpec".$li_i." value='".$ls_cantprodpec."' class=sin-borde style= text-align:left size=20 readonly onKeyPress=return(currencyFormat(this,'.',',',event))>";
						$la_objectrubrospec[$li_i][6]="<input name=txttipoprodpec".$li_i." type=text id=txttipoprodpec".$li_i." value='".$ls_tipoprodpec."' class=sin-borde style= text-align:left size=35 readonly>";
						$la_objectrubrospec[$li_i][7]="<input name=txttipopec".$li_i." type=text id=txttipopec".$li_i." class=sin-borde value='".$ls_tiporubpec."' style= text-align:left size=50 readonly>";
						$la_objectrubrospec[$li_i][8]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerrubropec(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
					}
					$li_filasrubrospec=$li_i;
						$la_objectrubrospec[$li_filasrubrospec][1]="<input name=txtcodrubrospec".$li_filasrubrospec." type=hidden id=txtcodrubrospec".$li_filasrubrospec." class=sin-borde style= text-align:center size=20 readonly><input name=txtcodclapec".$li_filasrubrospec." type=hidden id=txtcodclapec".$li_filasrubrospec." class=sin-borde style= text-align:center size=20 readonly><input name=txtdesrubrospec".$li_filasrubrospec." type=text id=txtdesrubrospec".$li_filasrubrospec." class=sin-borde style= text-align:left size=20 readonly>";
						$la_objectrubrospec[$li_filasrubrospec][2]="<input name=txtnropec".$li_filasrubrospec." type=text id=txtnropec".$li_filasrubrospec." class=sin-borde style= text-align:left size=8 readonly><input name=txthas".$li_filasrubrospec." type=hidden id=txthas".$li_filasrubrospec." class=sin-borde style= text-align:left size=8>";
						$la_objectrubrospec[$li_filasrubrospec][3]="<input name=txthectpec".$li_filasrubrospec." type=text id=txthectpec".$li_filasrubrospec." class=sin-borde style= text-align:left size=20 readonly>";
						$la_objectrubrospec[$li_filasrubrospec][4]="<input name=txtnroanimal".$li_filasrubrospec." type=text id=txtnroanimal".$li_filasrubrospec." class=sin-borde style= text-align:left size=4 readonly>";
						$la_objectrubrospec[$li_filasrubrospec][5]="<input name=txtcantprodpec".$li_filasrubrospec." type=text id=txtcantprodpec".$li_filasrubrospec." class=sin-borde style= text-align:left size=20 readonly>";
						$la_objectrubrospec[$li_filasrubrospec][6]="<input name=txttipoprodpec".$li_filasrubrospec." type=text id=txttipoprodpec".$li_filasrubrospec." class=sin-borde style= text-align:left size=35 readonly>";
						$la_objectrubrospec[$li_filasrubrospec][7]="<input name=txttipopec".$li_filasrubrospec." type=text id=txttipopec".$li_filasrubrospec." class=sin-borde style= text-align:left size=50 readonly>";
						$la_objectrubrospec[$li_filasrubrospec][8]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
				}
			}
		}
/*****************************************************************************************************************************/
/***************************   CARGA LOS DATOS DE LAS RETENCIONES DESDE CATALOGO****************************************/
/*****************************************************************************************************************************/
	}
	elseif($ls_operacion=="ue_cargarretenciones")
    {
	$li_filasretenciones=$li_filasretenciones+1;

	for($li_i=1;$li_i<$li_filasretenciones;$li_i++)
	{
		$ls_codigo=$_POST["txtcodret".$li_i];
		$ls_descripcion=$_POST["txtdesret".$li_i];
		$la_objectretenciones[$li_i][1]="<input name=txtcodret".$li_i." type=text id=txtcodret".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
		$la_objectretenciones[$li_i][2]="<input name=txtdesret".$li_i." type=text id=txtdesret".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=35 readonly>";
		$la_objectretenciones[$li_i][3]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerretenciones(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
	}
	$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=35 readonly>";
	$la_objectretenciones[$li_filasretenciones][3]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
}
/*****************************************************************************************************************************/
/***************************   REMUEVE LOS DATOS DE LA RETENCION SELECCIONADA *******************************************/
/*****************************************************************************************************************************/
elseif($ls_operacion=="ue_removerretenciones")
{
	$li_filasretenciones=$li_filasretenciones-1;
	$li_temp=0;

	for($li_i=1;$li_i<=$li_filasretenciones;$li_i++)
	{
		if($li_i!=$li_removerretenciones)
		{
			$li_temp=$li_temp+1;
			$ls_codigo=$_POST["txtcodret".$li_i];
			$ls_descripcion=$_POST["txtdesret".$li_i];
			$la_objectretenciones[$li_temp][1]="<input name=txtcodret".$li_temp." type=text id=txtcodret".$li_temp." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
			$la_objectretenciones[$li_temp][2]="<input name=txtdesret".$li_temp." type=text id=txtdesret".$li_temp." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=35 readonly>";
	   	    $la_objectretenciones[$li_temp][3]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerretenciones(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}
	}
	$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=35 readonly>";
	$la_objectretenciones[$li_filasretenciones][3]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
}
/*****************************************************************************************************************************/
/***************************   CARGA LOS DATOS DE LOS RUBROS AGRoCOLAS ***************************************************/
/*****************************************************************************************************************************/
elseif($ls_operacion=="ue_cargar_rubroagr")
    {
	$li_filasrubrosagri=$li_filasrubrosagri+1;
	$ls_total="0,00";
	for($li_i=1;$li_i<$li_filasrubrosagri;$li_i++)
	{
		$ls_codigo       = $_POST["txtcodrubrosagri".$li_i];
		$ls_codclaagri   = $_POST["txtcodclaagri".$li_i];
		$ls_descripcion  = $_POST["txtdesrubrosagri".$li_i];
		$ls_hectsembradas= $_POST["txthectsembradas".$li_i];
		$ls_tiporub      = $_POST["txttipo".$li_i];
		$ls_tipoprod     = $_POST["txttipoprod".$li_i];
		$ls_nro1         = $_POST["txtnro".$li_i];
		$ls_cantprod     = $_POST["txtcantprod".$li_i];
		$ls_ciclo        = $_POST["txtciclo".$li_i];

		 if ($_POST["chk".$li_i]=='')
			   {
			    $ls_estarub='0';
			   }else{
			   $ls_estarub='1';
			   }
		$la_objectrubrosagri[$li_i][1]="<input name=txtcodrubrosagri".$li_i." type=hidden id=txtcodrubrosagri".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=20 readonly><input name=txtcodclaagri".$li_i." type=hidden id=txtcodclaagri".$li_i." class=sin-borde value='".$ls_codclaagri."' style= text-align:center size=20 readonly><input name=txtdesrubrosagri".$li_i." type=text id=txtdesrubrosagri".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=35 readonly>";
		$la_objectrubrosagri[$li_i][2]="<input name=txthectsembradas".$li_i."  type=text id=txthectsembradas".$li_i." class=sin-borde value='".$ls_hectsembradas."' style= text-align:left size=8 onKeyPress=return(currencyFormat(this,'.',',',event))>";
		$la_objectrubrosagri[$li_i][3]="<input name=txtnro".$li_i." type=text id=txtnro".$li_i." value='".$ls_nro1."' class=sin-borde style= text-align:left size=4 readonly>";
		$la_objectrubrosagri[$li_i][4]="<input name=txtcantprod".$li_i." type=text id=txtcantprod".$li_i." value='".$ls_cantprod."' class=sin-borde style= text-align:left size=12 readonly>";
		$la_objectrubrosagri[$li_i][5]="<input name=txttipoprod".$li_i." type=text id=txttipoprod".$li_i." value='".$ls_tipoprod."' class=sin-borde style= text-align:left size=35 readonly>";
		$la_objectrubrosagri[$li_i][6]="<input name=txttipo".$li_i." type=text id=txttipo".$li_i." class=sin-borde value='".$ls_tiporub."' style= text-align:left size=35 readonly>";
		$la_objectrubrosagri[$li_i][7]="<input name=txtciclo".$li_i." type=text id=txtciclo".$li_i." class=sin-borde value='".$ls_ciclo."' style= text-align:left size=5 readonly>";
		if (($ls_estarub=='1') OR ($ls_ciclo=="PER"))
		{
			$la_objectrubrosagri[$li_i][8]= "<input name=chk".$li_i." type=checkbox id=chk".$li_i." checked=checked   class=sin-borde  onChange=javascript:ue_subtotal()>";
		}
		else
		{
	       $la_objectrubrosagri[$li_i][8]= "<input name=chk".$li_i." type=checkbox id=chk".$li_i." value=chk  class=sin-borde  onChange=javascript:ue_subtotal()>";
		}
		$la_objectrubrosagri[$li_i][9]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerrubroagri(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
	}
	$li_filasrubrosagri=$li_i;
	$la_objectrubrosagri[$li_filasrubrosagri][1]="<input name=txtcodrubrosagri".$li_filasrubrosagri." type=hidden id=txtcodrubrosagri".$li_filasrubrosagri." class=sin-borde style= text-align:center size=20 readonly><input name=txtcodclaagri".$li_filasrubrosagri." type=hidden id=txtcodclaagri".$li_filasrubrosagri." class=sin-borde style= text-align:center size=20 readonly><input name=txtdesrubrosagri".$li_filasrubrosagri." type=text id=txtdesrubrosagri".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
	$la_objectrubrosagri[$li_filasrubrosagri][2]="<input name=txthectsembradas".$li_filasrubrosagri." type=text id=txthectsembradas".$li_filasrubrosagri." class=sin-borde style= text-align:left size=8>";
	$la_objectrubrosagri[$li_filasrubrosagri][3]="<input name=txtnro".$li_filasrubrosagri." type=text id=txtnro".$li_filasrubrosagri." class=sin-borde style= text-align:left size=4 readonly>";
	$la_objectrubrosagri[$li_filasrubrosagri][4]="<input name=txtcantprod".$li_filasrubrosagri." type=text id=txtcantprod".$li_filasrubrosagri." class=sin-borde style= text-align:left size=12 readonly>";
	$la_objectrubrosagri[$li_filasrubrosagri][5]="<input name=txttipoprod".$li_filasrubrosagri." type=text id=txttipoprod".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
	$la_objectrubrosagri[$li_filasrubrosagri][6]="<input name=txttipo".$li_filasrubrosagri." type=text id=txttipo".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
	$la_objectrubrosagri[$li_filasrubrosagri][7]="<input name=txtciclo".$li_filasrubrosagri." type=text id=txtciclo".$li_filasrubrosagri." class=sin-borde style= text-align:left size=5 readonly>";
	$la_objectrubrosagri[$li_filasrubrosagri][8]= "<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=3>";
	$la_objectrubrosagri[$li_filasrubrosagri][9]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
}
/*****************************************************************************************************************************/
/***************************   REMUEVE LOS DATOS DEL RUBRO AGRoCOLASELECCIONADO****************************************/
/*****************************************************************************************************************************/
elseif($ls_operacion=="ue_removerrubroagr")
{
	$li_filasrubrosagri=$li_filasrubrosagri-1;
	$li_temp=0;

	for($li_i=1;$li_i<=$li_filasrubrosagri;$li_i++)
	{
		if($li_i!=$li_removerrubrosagri)
		{
			$li_temp=$li_temp+1;
			$ls_codigo       = $_POST["txtcodrubrosagri".$li_i];
			$ls_codclaagri   = $_POST["txtcodclaagri".$li_i];
			$ls_descripcion  = $_POST["txtdesrubrosagri".$li_i];
			$ls_hectsembradas= number_format($_POST["txthectsembradas".$li_i],2, ',', '.');
			$ls_tiporub      = $_POST["txttipo".$li_i];
			$ls_tipoprod     = $_POST["txttipoprod".$li_i];
			$ls_ciclo        = $_POST["txtciclo".$li_i];
			$ls_nro          = $_POST["txtnro".$li_i];
			$ls_cantprod     = number_format($_POST["txtcantprod".$li_i],2, ',', '.');
			 if ($_POST["chk".$li_i]=='')
			   {
			    $ls_estarub='0';
			   }else{
			   $ls_estarub='1';
			   }

			$la_objectrubrosagri[$li_temp][1]="<input name=txtcodrubrosagri".$li_temp." type=hidden id=txtcodrubrosagri".$li_temp." class=sin-borde value='".$ls_codigo."' style= text-align:center size=20 readonly><input name=txtcodclaagri".$li_temp." type=hidden id=txtcodclaagri".$li_temp." class=sin-borde value='".$ls_codclaagri."' style= text-align:center size=20 readonly><input name=txtdesrubrosagri".$li_temp." type=text id=txtdesrubrosagri".$li_temp." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=35 readonly>";
			$la_objectrubrosagri[$li_temp][2]="<input name=txthectsembradas".$li_temp."  type=text id=txthectsembradas".$li_temp." class=sin-borde value='".$ls_hectsembradas."' style= text-align:left size=8 onKeyPress=return(currencyFormat(this,'.',',',event))>";
			$la_objectrubrosagri[$li_temp][3]="<input name=txtnro".$li_temp." type=text id=txtnro".$li_temp." value='".$ls_nro."' class=sin-borde style= text-align:left size=4 readonly readonly>";
			$la_objectrubrosagri[$li_temp][4]="<input name=txtcantprod".$li_temp." type=text id=txtcantprod".$li_temp." value='".$ls_cantprod."' class=sin-borde style= text-align:left size=12 readonly>";
			$la_objectrubrosagri[$li_temp][5]="<input name=txttipoprod".$li_temp." type=text id=txttipoprod".$li_temp." value='".$ls_tipoprod."' class=sin-borde style= text-align:left size=35 readonly>";
			$la_objectrubrosagri[$li_temp][6]="<input name=txttipo".$li_temp." type=text id=txttipo".$li_temp." class=sin-borde value='".$ls_tiporub."' style= text-align:left size=35 readonly>";
			$la_objectrubrosagri[$li_temp][7]="<input name=txtciclo".$li_temp." type=text id=txtciclo".$li_temp." class=sin-borde value='".$ls_ciclo."' style= text-align:left size=5 readonly>";
			if ($ls_estarub=='1')
			{
			$la_objectrubrosagri[$li_temp][8]= "<input name=chk".$li_temp." type=checkbox id=chk".$li_temp." checked=checked   class=sin-borde  onChange=javascript:ue_subtotal()>";
			}
			else
			{
			$la_objectrubrosagri[$li_temp][8]= "<input name=chk".$li_temp." type=checkbox id=chk".$li_temp." value=chk  class=sin-borde  onChange=javascript:ue_subtotal()>";
			}
	   	    $la_objectrubrosagri[$li_temp][9]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerrubroagri(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}
	}
	$la_objectrubrosagri[$li_filasrubrosagri][1]="<input name=txtcodrubrosagri".$li_filasrubrosagri." type=hidden id=txtcodrubrosagri".$li_filasrubrosagri." class=sin-borde style= text-align:center size=20 readonly><input name=txtcodclaagri".$li_filasrubrosagri." type=hidden id=txtcodclaagri".$li_filasrubrosagri." class=sin-borde style= text-align:center size=20 readonly><input name=txtdesrubrosagri".$li_filasrubrosagri." type=text id=txtdesrubrosagri".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
	$la_objectrubrosagri[$li_filasrubrosagri][2]="<input name=txthectsembradas".$li_filasrubrosagri." type=text id=txthectsembradas".$li_filasrubrosagri." class=sin-borde style= text-align:left size=8>";
	$la_objectrubrosagri[$li_filasrubrosagri][3]="<input name=txtnro".$li_filasrubrosagri." type=text id=txtnro".$li_filasrubrosagri." class=sin-borde style= text-align:left size=4 readonly>";
	$la_objectrubrosagri[$li_filasrubrosagri][4]="<input name=txtcantprod".$li_filasrubrosagri." type=text id=txtcantprod".$li_filasrubrosagri." class=sin-borde style= text-align:left size=12 readonly>";
	$la_objectrubrosagri[$li_filasrubrosagri][5]="<input name=txttipoprod".$li_filasrubrosagri." type=text id=txttipoprod".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
	$la_objectrubrosagri[$li_filasrubrosagri][6]="<input name=txttipo".$li_filasrubrosagri." type=text id=txttipo".$li_filasrubrosagri." class=sin-borde style= text-align:left size=35 readonly>";
	$la_objectrubrosagri[$li_filasrubrosagri][7]="<input name=txtciclo".$li_filasrubrosagri." type=text id=txtciclo".$li_filasrubrosagri." class=sin-borde style= text-align:left size=5 readonly>";
	$la_objectrubrosagri[$li_filasrubrosagri][8]= "<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=3>";
	$la_objectrubrosagri[$li_filasrubrosagri][9]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
}
/*****************************************************************************************************************************/
/***************************   CARGA LOS DATOS DE LOS RUBROS PECUARIOS ***************************************************/
/*****************************************************************************************************************************/

	elseif($ls_operacion=="ue_cargar_rubropec")
    {
	$li_filasrubrospec=$li_filasrubrospec+1;
	$ls_total="0,00";
	for($li_i=1;$li_i<$li_filasrubrospec;$li_i++)
	{
		$ls_codigo      = $_POST["txtcodrubrospec".$li_i];
		$ls_codclapec   = $_POST["txtcodclapec".$li_i];
		$ls_descripcion = $_POST["txtdesrubrospec".$li_i];
		$ls_hectpec     = $_POST["txthectpec".$li_i];
		$ls_tiporub     = $_POST["txttipopec".$li_i];
		$ls_tipoprod    = $_POST["txttipoprodpec".$li_i];
		$ls_nro1        = $_POST["txtnropec".$li_i];
		$ls_nroanimal   = $_POST["txtnroanimal".$li_i];
		$ls_cantprod    = $_POST["txtcantprodpec".$li_i];
		$ls_has         = $_POST["txthas".$li_i];

		$la_objectrubrospec[$li_i][1]="<input name=txtcodrubrospec".$li_i." type=hidden id=txtcodrubrospec".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=20 readonly><input name=txtcodclapec".$li_i." type=hidden id=txtcodclapec".$li_i." class=sin-borde value='".$ls_codclapec."' style= text-align:center size=20 readonly><input name=txtdesrubrospec".$li_i." type=text id=txtdesrubrospec".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=20 readonly>";
		$la_objectrubrospec[$li_i][2]="<input name=txtnropec".$li_i." type=text id=txtnropec".$li_i." value='".$ls_nro1."' class=sin-borde style= text-align:left size=8 onKeyPress=return(currencyFormat(this,'.',',',event))><input name=txthas".$li_i." type=hidden id=txthas".$li_i." class=sin-borde value='".$ls_has."' style= text-align:left size=8>";
		$la_objectrubrospec[$li_i][3]="<input name=txthectpec".$li_i." onChange=javascript:ue_subtotal2(); type=text id=txthectpec".$li_i." class=sin-borde value='".$ls_hectpec."' style= text-align:left size=6 onKeyPress=return(currencyFormat(this,'.',',',event)) readonly>";
		$la_objectrubrospec[$li_i][4]="<input name=txtnroanimal".$li_i." type=text id=txtnroanimal".$li_i." value='".$ls_nroanimal."' class=sin-borde style= text-align:left size=4 readonly>";
		$la_objectrubrospec[$li_i][5]="<input name=txtcantprodpec".$li_i." type=text id=txtcantprodpec".$li_i." value='".$ls_cantprod."' class=sin-borde style= text-align:left size=20 readonly>";
		$la_objectrubrospec[$li_i][6]="<input name=txttipoprodpec".$li_i." type=text id=txttipoprodpec".$li_i." value='".$ls_tipoprod."' class=sin-borde style= text-align:left size=35 readonly>";
		$la_objectrubrospec[$li_i][7]="<input name=txttipopec".$li_i." type=text id=txttipopec".$li_i." class=sin-borde value='".$ls_tiporub."' style= text-align:left size=50 readonly>";
		$la_objectrubrospec[$li_i][8]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerrubropec(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
	}
	$li_filasrubrospec=$li_i;
	$la_objectrubrospec[$li_filasrubrospec][1]="<input name=txtcodrubrospec".$li_filasrubrospec." type=hidden id=txtcodrubrospec".$li_filasrubrospec." class=sin-borde style= text-align:center size=20 readonly><input name=txtcodclapec".$li_filasrubrospec." type=hidden id=txtcodclapec".$li_filasrubrospec." class=sin-borde style= text-align:center size=20 readonly><input name=txtdesrubrospec".$li_filasrubrospec." type=text id=txtdesrubrospec".$li_filasrubrospec." class=sin-borde style= text-align:left size=20 readonly>";
	$la_objectrubrospec[$li_filasrubrospec][2]="<input name=txtnropec".$li_filasrubrospec." type=text id=txtnropec".$li_filasrubrospec." class=sin-borde style= text-align:left size=8><input name=txthas".$li_filasrubrospec." type=hidden id=txthas".$li_filasrubrospec." class=sin-borde style= text-align:left size=8>";
	$la_objectrubrospec[$li_filasrubrospec][3]="<input name=txthectpec".$li_filasrubrospec." type=text id=txthectpec".$li_filasrubrospec." class=sin-borde style= text-align:left size=6 readonly>";
	$la_objectrubrospec[$li_filasrubrospec][4]="<input name=txtnroanimal".$li_filasrubrospec." type=text id=txtnroanimal".$li_filasrubrospec." class=sin-borde style= text-align:left size=4 readonly>";
	$la_objectrubrospec[$li_filasrubrospec][5]="<input name=txtcantprodpec".$li_filasrubrospec." type=text id=txtcantprodpec".$li_filasrubrospec." class=sin-borde style= text-align:left size=20 readonly>";
	$la_objectrubrospec[$li_filasrubrospec][6]="<input name=txttipoprodpec".$li_filasrubrospec." type=text id=txttipoprodpec".$li_filasrubrospec." class=sin-borde style= text-align:left size=35 readonly>";
	$la_objectrubrospec[$li_filasrubrospec][7]="<input name=txttipopec".$li_filasrubrospec." type=text id=txttipopec".$li_filasrubrospec." class=sin-borde style= text-align:left size=50 readonly>";
	$la_objectrubrospec[$li_filasrubrospec][8]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";

}

/*****************************************************************************************************************************/
/***************************   REMUEVE LOS DATOS DEL RUBRO PECUARIO SELECCIONADO****************************************/
/*****************************************************************************************************************************/
elseif($ls_operacion=="ue_removerrubropec")
{
	$li_filasrubrospec=$li_filasrubrospec-1;
	$li_temp=0;

	for($li_i=1;$li_i<=$li_filasrubrospec;$li_i++)
	{
		if($li_i!=$li_removerrubrospec)
		{
			$li_temp=$li_temp+1;
			$ls_codigo      = $_POST["txtcodrubrospec".$li_i];
			$ls_codclapec   = $_POST["txtcodclapec".$li_i];
			$ls_descripcion = $_POST["txtdesrubrospec".$li_i];
			$ls_hectpec     = number_format($_POST["txthectpec".$li_i],2, ',', '.');
			$ls_tiporub     = $_POST["txttipopec".$li_i];
			$ls_tipoprod    = $_POST["txttipoprodpec".$li_i];
			$ls_nro         = $_POST["txtnropec".$li_i];
			$ls_cantprod    = number_format($_POST["txtcantprodpec".$li_i],2, ',', '.');
			$ls_has         = number_format($_POST["txthas".$li_i],2, ',', '.');

			$la_objectrubrospec[$li_temp][1]="<input name=txtcodrubrospec".$li_temp." type=hidden id=txtcodrubrospec".$li_temp." class=sin-borde value='".$ls_codigo."' style= text-align:center size=20 readonly><input name=txtcodclapec".$li_temp." type=hidden id=txtcodclapec".$li_temp." class=sin-borde value='".$ls_codclapec."' style= text-align:center size=20 readonly><input name=txtdesrubrospec".$li_temp." type=text id=txtdesrubrospec".$li_temp." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=20 readonly>";
			$la_objectrubrospec[$li_temp][2]="<input name=txtnropec".$li_temp." type=text id=txtnropec".$li_temp." value='".$ls_nro."' class=sin-borde style= text-align:left size=8 onKeyPress=return(currencyFormat(this,'.',',',event))><input name=txthas".$li_temp." type=hidden id=txthas".$li_temp." class=sin-borde value='".$ls_has."' style= text-align:left size=8>";
			$la_objectrubrospec[$li_temp][3]="<input name=txthectpec".$li_temp." onChange=javascript:ue_subtotal2(); type=text id=txthectpec".$li_temp." class=sin-borde value='".$ls_hectpec."' style= text-align:left size=6 onKeyPress=return(currencyFormat(this,'.',',',event)) readonly>";
			$la_objectrubrospec[$li_temp][4]="<input name=txtnroanimal".$li_temp." type=text id=txtnroanimal".$li_temp." value='".$ls_nro."' class=sin-borde style= text-align:left size=4 readonly readonly>";
			$la_objectrubrospec[$li_temp][5]="<input name=txtcantprodpec".$li_temp." type=text id=txtcantprodpec".$li_temp." value='".$ls_cantprod."' class=sin-borde style= text-align:left size=20 readonly>";
			$la_objectrubrospec[$li_temp][6]="<input name=txttipoprodpec".$li_temp." type=text id=txttipoprodpec".$li_temp." value='".$ls_tipoprod."' class=sin-borde style= text-align:left size=35 readonly>";
			$la_objectrubrospec[$li_temp][7]="<input name=txttipopec".$li_temp." type=text id=txttipopec".$li_temp." class=sin-borde value='".$ls_tiporub."' style= text-align:left size=50 readonly>";
	   	    $la_objectrubrospec[$li_temp][8]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerrubropec(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}
	}
	$la_objectrubrospec[$li_filasrubrospec][1]="<input name=txtcodrubrospec".$li_filasrubrospec." type=hidden id=txtcodrubrospec".$li_filasrubrospec." class=sin-borde style= text-align:center size=20 readonly><input name=txtcodclapec".$li_filasrubrospec." type=hidden id=txtcodclapec".$li_filasrubrospec." class=sin-borde style= text-align:center size=20 readonly><input name=txtdesrubrospec".$li_filasrubrospec." type=text id=txtdesrubrospec".$li_filasrubrospec." class=sin-borde style= text-align:left size=20 readonly>";
	$la_objectrubrospec[$li_filasrubrospec][2]="<input name=txtnropec".$li_filasrubrospec." type=text id=txtnropec".$li_filasrubrospec." class=sin-borde style= text-align:left size=8><input name=txthas".$li_filasrubrospec." type=hidden id=txthas".$li_filasrubrospec." class=sin-borde style= text-align:left size=8>";
	$la_objectrubrospec[$li_filasrubrospec][3]="<input name=txthectpec".$li_filasrubrospec." type=text id=txthectpec".$li_filasrubrospec." class=sin-borde style= text-align:left size=6 readonly>";
	$la_objectrubrospec[$li_filasrubrospec][4]="<input name=txtnroanimal".$li_filasrubrospec." type=text id=txtnroanimal".$li_filasrubrospec." class=sin-borde style= text-align:left size=4 readonly>";
	$la_objectrubrospec[$li_filasrubrospec][5]="<input name=txtcantprodpec".$li_filasrubrospec." type=text id=txtcantprodpec".$li_filasrubrospec." class=sin-borde style= text-align:left size=20 readonly>";
	$la_objectrubrospec[$li_filasrubrospec][6]="<input name=txttipoprodpec".$li_filasrubrospec." type=text id=txttipoprodpec".$li_filasrubrospec." class=sin-borde style= text-align:left size=35 readonly>";
	$la_objectrubrospec[$li_filasrubrospec][7]="<input name=txttipopec".$li_filasrubrospec." type=text id=txttipopec".$li_filasrubrospec." class=sin-borde style= text-align:left size=50 readonly>";
	$la_objectrubrospec[$li_filasrubrospec][8]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
}
?>
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
        <td width="516" height="258"><div align="center">
            <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
              <tr>
                <td colspan="2" class="titulo-ventana">Datos del Cliente </td>
              </tr>
              <tr>
                <td >
				<input name="operacion" 		 type="hidden" id="operacion"          value="<? print $ls_operacion?>">
				<input name="hidstatus"		 	 type="hidden" id="hidstatus"          value="<? print $ls_hidstatus?>">
				<input name="hidsta"    		 type="hidden" id="hidsta"             value="<? print $ls_hidsta?>">
				<input name="hidcodpai" 	 	 type="hidden" id="hidcodpai"          value="<? print $ls_codpai?>">
				<input name="hidcodest"	 		 type="hidden" id="hidcodest"          value="<? print $ls_codest?>">
				<input name="hidcodmun" 		 type="hidden" id="hidcodmun"          value="<? print $ls_codmun?>">
				<input name="hidcodpar" 	     type="hidden" id="hidcodpar"          value="<? print $ls_codpar?>">
				<input name="hidprecioestandar"  type="hidden" id="hidprecioestandar"  value="<?php print $ls_precioestandar ?>">
				<input name="hidtentierra" 		 type="hidden" id="hidtentierra"       value="<?php print $ls_tentierra ?>">
				<input name="filasretenciones"	 type="hidden" id="filasretenciones"   value="<? print $li_filasretenciones?>">
				<input name="removerretenciones" type="hidden" id="removerretenciones" value="<? print $li_removerretenciones?>">
				<input name="filasrubrosagri" 	 type="hidden" id="filasrubrosagri"    value="<? print $li_filasrubrosagri?>">
				<input name="removerrubrosagri"  type="hidden" id="removerrubrosagri"  value="<? print $li_removerrubrosagri?>">
				<input name="filasrubrospec"     type="hidden" id="filasrubrospec"     value="<? print $li_filasrubrospec?>">
				<input name="removerrubrospec"   type="hidden" id="removerrubrospec"   value="<? print $li_removerrubrospec?>">
				<input name="hidcodcli"          type="hidden" id="hidcodcli"          value="<? print $ls_codcli?>">
				<input name="hidcedcli"          type="hidden" id="hidcedcli"          value="<? print $ls_tipcli?>">
			    <input name="hidreadonly"        type="hidden" id="hidreadonly"        value="<? print $ls_readonly?>">
                <input name="hidreadonlyced"     type="hidden" id="hidreadonlyced"     value="<? print $ls_readonlyced?>">
              <tr>
                <td width="151" height="22" align="right"><span class="style2">Cedula o RIF </span></td>
                <td width="629" ><label>
                  <select name="cmbtipcli" size="1" id="cmbtipcli">
				     <?php
					   $ls_aux=substr($ls_cedcliaux,0,1);
					   $ls_aux=$ls_tipcli;
					   if($ls_aux=="" || $ls_aux=="V")
					   {
					 ?>
                    <option value="V" selected>V</option>
                    <option value="E">E</option>
                    <option value="J">J</option>
                    <option value="G">G</option>
					<?php
					    }
						elseif($ls_aux=="E")
						{
					?>
					<option value="V">V</option>
                    <option value="E" selected>E</option>
                    <option value="J">J</option>
                    <option value="G">G</option>
					<?php
					    }
						elseif($ls_aux=="J")
						{
					?>
					<option value="V">V</option>
                    <option value="E">E</option>
                    <option value="J" selected>J</option>
                    <option value="G">G</option>
					<?php
					    }
						elseif($ls_aux=="G")
						{
					?>
					<option value="V" >V</option>
                    <option value="E">E</option>
                    <option value="J">J</option>
                    <option value="G" selected>G</option>
					<?php
					    }
					?>
                </select>
                </label>
                <input name="txtcedcli" type="text" id="txtcedcli" onKeyPress="return validaCajas(this,'i',event)" value="<? if ($ls_cedcli!='')
				{print $io_function->uf_cerosizquierda($ls_cedcli,9);}?>" size="15" maxlength="10" onBlur="ue_validar()" <?php print $ls_readonlyced ?> ></td>
              </tr>
              <tr>
                <td width="151" height="22" align="right">Razon Social </td>
                <td width="629" >
				<input name="txtnomcli" type="text" id="txtnomcli"  onKeyPress="return(validaCajas(this,'x',event,50))"  value="<? print  $ls_nomcli?>" size="50" maxlength="225">
                  <a href="javascript:ue_abrir('sigesp_sss_p_repassword_admin.php');"><img src="../shared/imagebank/tools20/download.gif" width="20" height="20" border="0"></a></td>
              </tr>

              <tr>
                <td height="4" align="right">Direcci&oacute;n</td>
                <td><textarea name="txtdircli" onKeyDown=" textCounter(this,254)" onKeyUp="textCounter(this,254)"  onKeyPress="return(validaCajas(this,'x',event,254))" cols="47" rows="2" id="txtdircli" ><? print $ls_dircli?></textarea></td>
              </tr>
              <tr align="left">
                <td height="22" align="right"><span class="style2">Telefono Fijo </span></td>
                <td><input name="txttelcli" onKeyPress="return(validaCajas(this,'t',event,254))" id="txttelcli"    value="<? print $ls_telcli?>" type="text" size="20" maxlength="20" ></td>
              </tr>
              <tr align="left">
                <td height="22" align="right"><span class="style2">Telefono</span> Movil </td>
                <td><input name="txtcelcli" id="txtcelcli"   value="<? print $ls_celcli?>" type="text" size="20" maxlength="20"></td>
              </tr>
                           <tr>
                <td height="24" align="right">Estado</td>
                <td><span class="style6">
                  <?php
                   $ls_codpai="058";
				   if($ls_codpai=="")
				    {
						$lb_valest=false;
					}
					else
					{
				       $ls_sql="SELECT codest ,desest FROM sigesp_estados
					   WHERE codpai='".$ls_codpai."' ORDER BY codest ASC";
				       $lb_valest=$io_utilidad->uf_datacombo($ls_sql,$la_estado);
					}

					if($lb_valest)
				     {
					   $io_datastore->data=$la_estado;
					   $li_totalfilas=$io_datastore->getRowCount("codest");
				     }
					 else
					 	$li_totalfilas=0;
				    ?>
                  <select name="cmbestado" size="1" id="cmbestado" onChange="javascript:ue_llenarcmb();">
                    <option value="">Seleccione...</option>
                    <?php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
					 $ls_codigo=$io_datastore->getValue("codest",$li_i);
					 $ls_desest=$io_datastore->getValue("desest",$li_i);
					 if ($ls_codigo==$ls_codest)
					 {
						  print "<option value='$ls_codigo' selected>$ls_desest</option>";
					 }
					 else
					 {
						  print "<option value='$ls_codigo'>$ls_desest</option>";
					 }
					}
	                ?>
                  </select>
                </span></td>
              </tr>
              <tr>
                <td height="24" align="right">Municipio</td>
                <td><span class="style6">
				<?php
					$lb_valmun=false;
					if($ls_codest=="")
					{
						$lb_valmun=false;
					}
					else
					{
						 $ls_sql="SELECT codmun ,denmun
                                  FROM sigesp_municipio
                                  WHERE codpai='$ls_codpai' AND codest='".$ls_codest."' ORDER BY codmun ASC";
				         $lb_valmun=$io_utilidad->uf_datacombo($ls_sql,&$la_municipio);

					}
					if($lb_valmun)
					{
						$io_datastore->data=$la_municipio;
						$li_totalfilas=$io_datastore->getRowCount("codmun");
					}
					else{$li_totalfilas=0;}
			      ?>
                  <select name="cmbmunicipio" size="1" id="cmbmunicipio" onChange="javascript:ue_llenarcmb();">
                  <option value="">Seleccione...</option>
                  <?php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
						 $ls_codigo=$io_datastore->getValue("codmun",$li_i);
						 $ls_denmun=$io_datastore->getValue("denmun",$li_i);
						 if ($ls_codigo==$ls_codmun)
						 {
							  print "<option value='$ls_codigo' selected>$ls_denmun</option>";
						 }
						 else
						 {
							  print "<option value='$ls_codigo'>$ls_denmun</option>";
						 }
					}
	            ?>
                  </select>
				  </span>				  </td>
              </tr>
              <tr>
                <td height="24" align="right">Parroquia</td>
                <td>
				<span class="style6">
				<?php
				$lb_valpar=false;
			    if($ls_codmun=="")
					{
						$lb_valpar=false;
					}
					else
					 {
						 $ls_sql="SELECT codpar,denpar
                                  FROM sigesp_parroquia
                                  WHERE codpai='$ls_codpai' AND codest='".$ls_codest."' AND codmun='".$ls_codmun."' ORDER BY codpar ASC";
				         $lb_valpar=$io_utilidad->uf_datacombo($ls_sql,&$la_parroquia);
					 }

					if($lb_valpar)
					{
						$io_datastore->data=$la_parroquia;
						$li_totalfilas=$io_datastore->getRowCount("codpar");
					}
					else{$li_totalfilas=0;}
			    ?>
                  <select name="cmbparroquia" size="1" id="cmbparroquia" onChange="javascript:ue_llenarcmb();">
                    <option value="">Seleccione...</option>
                    <?php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
					 $ls_codigo=$io_datastore->getValue("codpar",$li_i);
					 $ls_denpar=$io_datastore->getValue("denpar",$li_i);
					 if ($ls_codigo==$ls_codpar)
					 {
						  print "<option value='$ls_codigo' selected>$ls_denpar</option>";
					 }
					 else
					 {
						  print "<option value='$ls_codigo'>$ls_denpar</option>";
					 }
					}
	            ?>
                  </select>
				  </span>				  </td>
              </tr>
             <tr>
               <td height="24" align="right">Precio Estandar</td>
                <td colspan="3" ><span class="style6">
                  <select name="cmbprecioestandar" size="1" id="cmbprecioestandar">
                    <?php
				    if($ls_precioestandar=="")
					 {
				   ?>
                                        <option value="PV">Precio de Venta</option>
                                        <option value="PU"selected>Precio de Venta 1</option>
					<option value="PD">Precio de Venta 2</option>
					<option value="PT">Precio de Venta 3</option>
                    <?php
					 }
					 elseif($ls_precioestandar=="PV")
					 {
					?>
                                        <option value="PV" selected>Precio de Venta</option>
                                        <option value="PU">Precio de Venta 1</option>
					<option value="PD">Precio de Venta 2</option>
					<option value="PT">Precio de Venta 3</option>
                    <?php
					 }
					 elseif($ls_precioestandar=="PU")
					 {
					?>
                    <option value="PV">Precio de Venta</option>
                    <option value="PU" selected>Precio de Venta 1</option>
					<option value="PD">Precio de Venta 2</option>
					<option value="PT">Precio de Venta 3</option>
                    <?php
					}
					elseif ($ls_precioestandar=="PD")
					{
					 ?>
                    <option value="PV">Precio de Venta</option>
                    <option value="PU">Precio de Venta 1</option>
					<option value="PD" selected>Precio de Venta 2</option>
					<option value="PT">Precio de Venta 3</option>
					 <?php
					 }
					 elseif ($ls_precioestandar=="PT")
					{
					?>
                    <option value="PV">Precio de Venta</option>
                    <option value="PU">Precio de Venta 1</option>
					<option value="PD">Precio de Venta 2</option>
					<option value="PT" selected>Precio de Venta 3</option>
					<?php
					}
					?>
                  </select>
                </span></td>
              </tr>

			   <tr>
                <td height="24" align="right">productor</td>
                <td><label>
				<?php
				if ($ls_productor=="SI" and $li_filasrubrosagri!="1")
				  {
				?>
				<!--<input name="check1" type="checkbox" id="check1" value="V" checked>	-->
				<input name="txtproductor" type="text" id="txtproductor" value="SI" readonly>
				<img src="../shared/imagebank/tools20/aprobado.gif" width="22" height="22" border="0">Datos del Productor
				<?php
				  }
				 else if ($ls_productor=="SI" and $li_filasrubrosagri=="1")
				  {
				  ?>
				<input name="txtproductor" type="text" id="txtproductor" value="SI" readonly>
				<a href="javascript:actualizar_check();"><!--<input name="check1" type="checkbox" id="check1" value="V" checked>-->
				<img src="../shared/imagebank/tools20/aprobado.gif" width="22" height="22" border="0">Datos del Productor</a>
				<?php
				  }
				  else if ($ls_productor=="NO")
				  {
				  ?>
				  <input name="txtproductor" type="text" id="txtproductor" value="NO" readonly>
				 <a href="javascript:actualizar_check();"><!--<input name="check1" type="checkbox" id="check1" value="F" >-->
				<img src="../shared/imagebank/tools20/aprobado.gif" width="22" height="22" border="0">Datos del Productor</a>
				<?php
				  } else if ($ls_cedcli=="")
				  {
				  ?>
				   <input name="txtproductor" type="text" id="txtproductor" value="NO" readonly>
				 <!--<input name="check1" type="checkbox" id="check1" value="F" >-->
				<img src="../shared/imagebank/tools20/aprobado.gif" width="22" height="22" border="0">Datos del Productor
				<?php
				}
				?>
                </label></td>
              </tr>
				<?php
				if ($ls_productor=="SI")
				  {

				?>
			     <tr>
                    <td height="13" align="right">&nbsp;</td>
                    <td >&nbsp;</td>
              </tr>
				<tr>
                <td colspan="2" class="titulo-ventana"> Datos del Productor </td>
              </tr>
                <tr>
               <td height="24" align="right">Tenencia de Tierra</td>
                <td colspan="3" ><span class="style6">
                  <?php
				    $ls_sql="SELECT * FROM sfc_tenenciatierra ORDER BY codtenencia ASC";
				    $lb_valten=$io_utilidad->uf_datacombo($ls_sql,&$la_tenencia);

					if($lb_valten)
				    {
					   $io_datastore->data=$la_tenencia;
					   $li_totalfilas=$io_datastore->getRowCount("codtenencia");
				    }
					else
						$li_totalfilas=0;
				    ?>
                  <select name="cmbtentierra" size="1" id="cmbtentierra" onChange="javascript:ue_llenarcmb();" >
                    <option value="">Seleccione...</option>
                    <?php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
						 $ls_codtenencia=$io_datastore->getValue("codtenencia",$li_i);
						 $ls_tenenciatierra=$io_datastore->getValue("denominacion",$li_i);
						 if ($ls_codtenencia==$ls_tentierra)
						 {
							  print "<option value='".$ls_codtenencia."' selected>".$ls_tenenciatierra."</option>";
						 }
						 else
						 {
							 print "<option value='".$ls_codtenencia."'>".$ls_tenenciatierra."</option>";
						 }
					}
	                ?>
                  </select>
                </span></td>
              </tr>

			   <tr>

			  <td width="151" height="22" align="right">
					<span class="style2">No de Documento </span>				</td>
			  	<td width="629" >

				 <input name="txtnrocartagr" type="text" id="txtnrocartagr" onKeyPress="return validaCajas(this,'i',event)"
					size="27" maxlength="25" value="<?php print $ls_nrocartagr;?>" onChange="ue_validar_carta();">					</td>
			  </tr>

              <tr align="left">
                <td height="22" align="right">N&deg; Hect&aacute;reas</td>
                <td>
					<input name="txtnrohect" id="txtnrohect"  onChange="javascript:ue_subtotal();" onKeyPress="return currencyFormat(this,'.',',',event)"  value="<?php print $ls_nrohect; ?>" type="text"	size="20" maxlength="20">				</td>
              </tr>


				 <tr>
                    <td width="151" height="13" align="right"><div align="left"><a href="javascript:ue_catrubrosagri();"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:ue_catrubrosagri();">Agregar Detalle</a></div></td>
                    <td width="629" >&nbsp;</td>
              </tr>
                  <tr>
				  <!--Aqui se llama al metodo que dibuja la grid, y se la pasan los parametros correpondientes-->
                    <td height="10" colspan="2" align="left">
                    <?php $io_grid->makegrid($li_filasrubrosagri,$la_columrubrosagri,$la_objectrubrosagri,$li_anchorubrosagri,$ls_titulorubrosagri,$ls_nametableagri);?></td>
                  </tr>

				  <tr>
                    <td width="151" height="13" align="right"><div align="left"><a href="javascript:ue_catrubrospec();"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:ue_catrubrospec();">Agregar Detalle</a></div></td>
                    <td width="629" >&nbsp;</td>
                  </tr>
                  <tr>
				  <!--Aqui se llama al metodo que dibuja la grid, y se la pasan los parametros correpondientes-->
                    <td height="13" colspan="2" align="center"><?php $io_grid->makegrid($li_filasrubrospec,$la_columrubrospec,$la_objectrubrospec,$li_anchorubrospec,$ls_titulorubrospec,$ls_nametablepec);?></td>
                  </tr>
				  <tr align="right">
                <td height="22"><span class="style2"></span> </td>
               <td>
					<span class="style2">N&deg; Hect&aacute;reas Productivas </span><input name="hectprod" id="hectprod"  onkeypress="return validaCajas(this,'i',event)"  value="<?php print $hectprod;?>" type="text" size="20" maxlength="20" readonly>					</td>
              </tr>
             <tr align="right">
                 <td height="22"><span class="style2"></span> </td>

                <td>
					<span class="style2">N&deg; Hect&aacute;reas sin Producir </span><input name="txthectsinprod" id="txthectsinprod"  onkeypress="return validaCajas(this,'i',event)"  value="<?php print $ls_nrohectsinprod;?>" type="text" size="20" maxlength="20" readonly>					</td>
              </tr>
                  <tr>
                    <td height="13" align="right">&nbsp;</td>
                    <td >&nbsp;</td>
                  </tr>
			<?php
			}
			?>
			  <tr>
                    <td height="13" colspan="2" align="right"><table width="415" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                     <tr>
                    	<td height="13" colspan="2" align="right" class="titulo-ventana">Agentes Especiales de Retencion </td>
                    </tr>
                  	<tr>
                    	<td height="13" align="right">&nbsp;</td>
                   		<td >&nbsp;</td>
                  	</tr>
                  	<tr>
                    <td width="111" height="13" align="right"><div align="left"><a href="javascript:ue_catretenciones();"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:ue_catretenciones();">Agregar Detalle</a></div>					</td>
                    <td width="302" >&nbsp;</td>
                  	</tr>
                  	<tr>
				  <!--Aqui se llama al metodo que dibuja la grid, y se la pasan los parametros correpondientes-->
                    <td height="13" colspan="2" align="center"><?php $io_grid->makegrid($li_filasretenciones,$la_columretenciones,$la_objectretenciones,$li_anchoretenciones,$ls_tituloretenciones,$ls_nametable);?>					</td>
                 	 </tr>
			       </table></td>
              </tr>
             <tr>
               <td height="19" align="right">&nbsp;</td>
                <td colspan="3" >&nbsp;</td>
              </tr>
    </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</form>
</body>

<script language="JavaScript">

/***********************************************************************************************************************************/
function actualizar_check()
{
			f=document.form1;
			if ((f.txtproductor.value=="SI") && (f.txtnrocartagr.value!=""))
			{
			 if (confirm("o Esta seguro de eliminar este registro ?"))
					   {
						 f=document.form1;
						 f.hidcodpai.value="058";
						 f.operacion.value="ue_actualizar_check";
						 f.action="sigesp_sfc_d_cliente.php";
						 f.submit();
					   }
					else
					   {
						 f=document.form1;
						 f.action="sigesp_sfc_d_cliente.php";
						 alert("Eliminacion Cancelada !!!");
						 f.operacion.value="ue_validar";
						 f.hidcodpai.value="058";
						 f.submit();
					   }
			}
			else
			{
			f=document.form1;
			f.hidcodpai.value="058";
			f.operacion.value="ue_actualizar_check";
			f.action="sigesp_sfc_d_cliente.php";
			 f.submit();
			}

}

function ue_nuevo()
{
f=document.form1;
//li_incluir=f.incluir.value;
li_incluir=1;
if(li_incluir==1)
{

			f.hidcodcli.value="0";
		    f.operacion.value="ue_nuevo";
			f.hidcodpai.value="058";
			f.hidprecioestandar.value="PV";
			f.txtcedcli.value="";
			f.txtnomcli.value="";
			f.txttelcli.value="";
			f.txtcelcli.value="";
			f.txtdircli.value="";
			f.txtproductor.value="NO";
			f.hidreadonly.value="readonly";
			f.action="sigesp_sfc_d_cliente.php";
			f.submit();
}
else
{
	alert("No tiene permiso para realizar esta operacion");
}

}
function ue_guardar()
{

	f=document.form1;
	//li_incluir=f.incluir.value;
	//li_cambiar=f.cambiar.value;
	ls_codcli=f.hidcodcli.value;
	if(  (ls_codcli!='0')  &&  (ls_codcli!=0)  )
	{
			li_incluir=1;
			li_cambiar=1;

			lb_status=f.hidstatus.value;
			if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
			{
				if (lb_status!="C")
				{
					f.hidstatus.value="C";
				}

				 with(f)
				 {
				  if (ue_valida_null(txtcedcli,"Cedula o RIF")==false)
				   {
					 txtcodcli.focus();
				   }
				   else
				   {
					if (ue_valida_null(txtnomcli,"Razon Social")==false)
					 {
					  txtnomcli.focus();
					 }
					 else
					 {
					  if (ue_valida_null(txtdircli,"Direccion")==false)
					   {
						txtdircli.focus();
					   }
					  else
					  {
						if (ue_valida_null(cmbestado,"Estado")==false)
						{
						  cmbestado.focus();
						}
						else
						{
						   if (ue_valida_null(cmbmunicipio,"Municipio")==false)
						   {
							 cmbmunicipio.focus();
						   }
						 else
						  {
							if (ue_valida_null(cmbparroquia,"Parroquia")==false)
							{
							  cmbparroquia.focus();
							}

					  else
					  {
						if (f.txtproductor.value=="SI")
						{
						if (ue_valida_null(txtnrocartagr,"Nro de Carta Agraria")==false)
						{
						alert("Debe indicar un nro de carta agraria");
						}
						else{
						if (ue_valida_null(txtnrohect,"Nro de Hectareas")==false)
						{
						alert("Debe indicar un nro de hectareas");
						}
						else{
						if (f.filasrubrosagri.value=="1" && f.filasrubrospec.value=="1")
						{
						alert("Debe registrar al menos un rubro");
						}
						else{
						if (f.hectprod.value=="0,00")
						{
						alert("Debe seleccionar al menos un rubro o la cantidad del rubro debe ser distinta a cero");
						}
						else
						{
								if (f.cmbtentierra.value=="")
								{
								alert("Debe seleccionar la tenencia de la tierra o crearla");
								alert("No se debe guardara el cliente");
								}
								else
								{
										 f.operacion.value="ue_guardar";
										 f.action="sigesp_sfc_d_cliente.php";
										 f.submit();
								}
						}
						}
						}
						}
						}
					   else
					   {

								 f.operacion.value="ue_guardar";
								 f.action="sigesp_sfc_d_cliente.php";
								 f.submit();
					   }
					  }
					  }
					  }
					   }
					 }
				   }
				 }

			}
			else
			{
				alert("No tiene permiso para realizar esta operacion");
			}
	}
    else
    {
         alert("Por favor Clickee el boton de nuevo para comenzar el proceso");
    }
}
function ue_eliminar()
{
		f=document.form1;
        //li_eliminar=f.eliminar.value;
		li_eliminar=1;
		if(li_eliminar==1)
		{
			if (f.txtcedcli.value=="")
			   {
				 alert("No ha seleccionado ningon registro para eliminar !!!");
			   }
				else
				{
				 if (confirm("o Esta seguro de eliminar este registro ?"))
					   {
						 f=document.form1;
						 f.operacion.value="ue_eliminar";
						 f.action="sigesp_sfc_d_cliente.php";
						 f.submit();
					   }
					else
					   {
						 f=document.form1;
						 f.action="sigesp_sfc_d_cliente.php";
						 alert("Eliminacion Cancelada !!!");
						 f.txtcodcli.value="";
						 f.txtnomcli.value="";
						 f.txtdircli.value="";
						 f.txttelcli.value="";
						 f.txtcelcli.value="";
						 f.submit();
					   }
				}

			}
			else
			{
				alert("No tiene permiso para realizar esta operacion");
			}
}


function ue_buscar()
{
    f=document.form1;
	f.operacion.value="";
	//li_leer=f.leer.value;
	li_leer=1;
    if(li_leer==1)
	{
	pagina="sigesp_cat_cliente.php";
	popupWin(pagina,"catalogo",600,250);
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

/*******************************************************************************************************************************/

function ue_cargarcliente(codcli,cedcli,nomcli,dircli,telcli,celcli,codpai,codest,codmun,codpar,productor,precioestandar,tentierra)
{
	f=document.form1;
	f.hidstatus.value="C";
	f.hidcodcli.value=codcli;
	f.txtcedcli.value=cedcli;
    f.txtnomcli.value=nomcli;
    f.txttelcli.value=telcli;
    f.txtdircli.value=dircli;
    f.txtcelcli.value=celcli;
    f.hidcodpai.value=codpai;
    f.hidcodest.value=codest;
	f.hidcodmun.value=codmun;
	f.hidcodpar.value=codpar;
	f.hidprecioestandar.value=precioestandar;
	f.hidtentierra.value=tentierra;
	if (productor=="V")
	 {
	  f.txtproductor.value=productor;
	  /*f.check1.checked=true;
	  f.operacion.value="";	*/
	 }
	 else
	 {
	  f.txtproductor.value=productor;
	/*  f.check1.checked=false;*/
	  }

	f.operacion.value="ue_cargarcliente";
	f.submit();

}

/***********************************************************************************************************************************/

function EvaluateText(cadena, obj)
{
opc = false;

	if (cadena == "%d")
	  if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32))
	  opc = true;
	if (cadena == "%f"){
	 if (event.keyCode > 47 && event.keyCode < 58)
	  opc = true;
	 if (obj.value.search("[.*]") == -1 && obj.value.length != 0)
	  if (event.keyCode == 46)
	   opc = true;
	}
	 if (cadena == "%s") // toma numero y letras
	 if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32)||(event.keyCode > 47 && event.keyCode < 58)||(event.keyCode ==46))
	  opc = true;
	 if (cadena == "%c") // toma numero y punto
	 if ((event.keyCode > 47 && event.keyCode < 58)|| (event.keyCode ==46))
	  opc = true;
	if(opc == false)
	 event.returnValue = false;
   }



function ue_llenarcmb()
{
	f=document.form1;
	f.action="sigesp_sfc_d_cliente.php";
	f.operacion.value="";
	f.hidcodpai.value="058";
	f.submit();
}

function ue_validar()
{
	f=document.form1;
	if(f.txtcedcli.value != ""){
		f.action="sigesp_sfc_d_cliente.php";
		f.operacion.value="ue_validar";
		f.submit();
	}

}

function ue_validar_carta()
{
	f=document.form1;
	f.action="sigesp_sfc_d_cliente.php";
	f.operacion.value="ue_validar_carta";
	f.submit();
}

/********************************************************************************************************************************
*************************************************  MANEJO DEL GRID RETENCIONES  ********************************************
*********************************************************************************************************************************/
function ue_cargarretenciones(codigo,descripcion,cuenta,deducible,formula)
{
	f=document.form1;
	f.operacion.value="ue_cargarretenciones";
	lb_existe=false;

	for(li_i=1;li_i<=f.filasretenciones.value && !lb_existe;li_i++)
	{
		ls_codigo=eval("f.txtcodret"+li_i+".value");
		if(ls_codigo==codigo)
		{
			alert("Detalle ya existe!!!");
			lb_existe=true;
		}
	}

	if(!lb_existe)
	{
		eval("f.txtcodret"+f.filasretenciones.value+".value='"+codigo+"'");
		eval("f.txtdesret"+f.filasretenciones.value+".value='"+descripcion+"'");
		f.submit();
	}
}

function ue_removerretenciones(li_fila)
{
	f=document.form1;
	f.removerretenciones.value=li_fila;
	f.operacion.value="ue_removerretenciones"
	f.action="sigesp_sfc_d_cliente.php";
	f.submit();
}

function ue_catretenciones()
{
	f=document.form1;
	if(f.txtcedcli.value!="")
	{
		f.operacion.value="";
		pagina="sigesp_cat_retenciones.php";
		popupWin(pagina,"catalogo",550,200);
	}
	else
	{
		alert("Debe indicar un Cliente!!");
	}
}

/********************************************************************************************************************************
*************************************************  MANEJO DEL GRID RUBROS AGRICOLAS  ********************************************
*********************************************************************************************************************************/
function ue_catrubrosagri()
{
	f=document.form1;
	if(f.txtcedcli.value!="" && f.txtnomcli.value!="" && f.txtdircli.value!="" && f.txttelcli.value!="" && f.cmbestado.value!="" && f.cmbmunicipio.value!="" && f.cmbparroquia.value!="" &&
	f.cmbprecioestandar.value!="" && f.txtnrocartagr.value!="" && f.txtnrohect.value!="")
	{
		f.operacion.value="";
		pagina="sigesp_cat_rubroagri.php";
		popupWin(pagina,"catalogo",550,200);
	}
	else
	{
		if (f.txtcedcli.value=="")
		{
		alert("Debe indicar un cliente!!");
		txtcodcli1.focus();
		}else if (f.txtnomcli.value=="")
		{
		alert("Debe indicar una razon social");
		txtnomcli.focus();
		}else if (f.txtdircli.value=="")
		{
		alert("Debe indicar una direccion de referencia");
		txtdircli.focus();
		}
		else if (f.txttelcli.value=="")
		{
		alert("Debe indicar un nomero de telefono");
		txttelcli.focus();
		}else if (f.cmbestado.value=="")
		{
		alert("Debe indicar un Estado");
		cmbestado.focus();
		}else if (f.cmbmunicipio.value=="")
		{
		alert("Debe indicar un Municipio");
		cmbmunicipio.focus();
		}else if (f.cmbparroquia.value=="")
		{
		alert("Debe indicar una Parroquia");
		cmbparroquia.focus();
		}else if (f.txtnrocartagr.value=="")
		{
		alert("Debe especificar un No de Carta Agraria");
		txtnrocartagr.focus();
		}else if (f.txtnrohect.value==""){
		alert("Debe indicar el Nomero de Hectareas que posee el Productor");
		txtnrohect.focus();
		}
	}
}

function ue_cargar_rubroagr(codrubro,nomrubro,cod_tipo,cod_tipoprod,tipo_prod,descripcion,prod_est,deno_rubro,codclarubro)
{
	f=document.form1;
	f.operacion.value="ue_cargar_rubroagr";
	lb_existe=false;
	for(li_i=1;li_i<=f.filasrubrosagri.value && !lb_existe;li_i++)
	{
		ls_codigo=eval("f.txtcodrubrosagri"+li_i+".value");
		ls_cod_tipo=eval("f.txttipoprod"+li_i+".value");
		if(ls_codigo==codrubro && ls_cod_tipo==tipo_prod)
		{
			alert("Detalle ya existe!!!");
			lb_existe=true;
		}

	}

	if(!lb_existe)
	{

		eval("f.txtcodrubrosagri"+f.filasrubrosagri.value+".value='"+codrubro+"'");
		eval("f.txtdesrubrosagri"+f.filasrubrosagri.value+".value='"+nomrubro+"'");
		eval("f.txtnro"+f.filasrubrosagri.value+".value='"+prod_est+"'");
		eval("f.txttipoprod"+f.filasrubrosagri.value+".value='"+tipo_prod+"'");
		eval("f.txttipo"+f.filasrubrosagri.value+".value='"+descripcion+"'");
		eval("f.txtciclo"+f.filasrubrosagri.value+".value='"+deno_rubro+"'");
		eval("f.txtcodclaagri"+f.filasrubrosagri.value+".value='"+codclarubro+"'");
		f.submit();

	}
}

function ue_removerrubroagri(li_fila)
{
	f=document.form1;
	f.removerrubrosagri.value=li_fila;
	f.operacion.value="ue_removerrubroagr"
	f.action="sigesp_sfc_d_cliente.php";
	f.submit();
}
/********************************************************************************************************************************
*************************************************  MANEJO DEL GRID RUBROS PECUARIOS ******************************************
*********************************************************************************************************************************/
function ue_catrubrospec()
{
	f=document.form1;
	if(f.txtcedcli.value!="" && f.txtnomcli.value!="" && f.txtdircli.value!="" && f.txttelcli.value!="" && f.cmbestado.value!="" && f.cmbmunicipio.value!="" && f.cmbparroquia.value!="" && f.cmbprecioestandar.value!="" && f.txtnrocartagr.value!="" && f.txtnrohect.value!="")
	{
		f.operacion.value="";
		pagina="sigesp_cat_rubropec.php";
		popupWin(pagina,"catalogo",550,200);
	}
	else
	{
		if (f.txtcedcli.value=="")
		{
		alert("Debe indicar un cliente!!");
		txtcodcli1.focus();
		}else if (f.txtnomcli.value=="")
		{
		alert("Debe indicar una razon social");
		txtnomcli.focus();
		}else if (f.txtdircli.value=="")
		{
		alert("Debe indicar una direccion de referencia");
		txtdircli.focus();
		}
		else if (f.txttelcli.value=="")
		{
		alert("Debe indicar un nomero de telefono");
		txttelcli.focus();
		}else if (f.cmbestado.value=="")
		{
		alert("Debe indicar un Estado");
		cmbestado.focus();
		}else if (f.cmbmunicipio.value=="")
		{
		alert("Debe indicar un Municipio");
		cmbmunicipio.focus();
		}else if (f.cmbparroquia.value=="")
		{
		alert("Debe indicar una Parroquia");
		cmbparroquia.focus();
		}else if (f.txtnrocartagr.value=="")
		{
		alert("Debe especificar un No de Carta Agraria");
		txtnrocartagr.focus();
		}else if (f.txtnrohect.value==""){
		alert("Debe indicar el Nomero de Hectareas que posee el Productor");
		txtnrohect.focus();
		}
	}
}

function ue_cargar_rubropec(codrubro,nomrubro,cod_tipo,cod_tipoprod,tipo_prod,descripcion,prod_est,has,codclapec)
{
	f=document.form1;
	f.operacion.value="ue_cargar_rubropec";
	lb_existe=false;
	for(li_i=1;li_i<=f.filasrubrospec.value && !lb_existe;li_i++)
	{
		ls_codigo=eval("f.txtcodrubrospec"+li_i+".value");
		ls_cod_tipo=eval("f.txttipoprodpec"+li_i+".value");
		if(ls_codigo==codrubro && ls_cod_tipo==tipo_prod)
		{
			alert("Detalle ya existe!!!");
			lb_existe=true;
		}
	}

	if(!lb_existe)
	{
		eval("f.txtcodrubrospec"+f.filasrubrospec.value+".value='"+codrubro+"'");
		eval("f.txtcodclapec"+f.filasrubrospec.value+".value='"+codclapec+"'");
		eval("f.txtdesrubrospec"+f.filasrubrospec.value+".value='"+nomrubro+"'");
		eval("f.txttipoprodpec"+f.filasrubrospec.value+".value='"+tipo_prod+"'");
		eval("f.txtnroanimal"+f.filasrubrospec.value+".value='"+prod_est+"'");
		eval("f.txthas"+f.filasrubrospec.value+".value='"+has+"'");
		eval("f.txttipopec"+f.filasrubrospec.value+".value='"+descripcion+"'");
		f.submit();
	}
}

function ue_removerrubropec(li_fila)
{
	f=document.form1;
	f.removerrubrospec.value=li_fila;
	f.operacion.value="ue_removerrubropec"
	f.action="sigesp_sfc_d_cliente.php";
	f.submit();
}
/********************************************************************************************************************************
*************************************************  VALIDACIONES DE CAJAS DE TEXTO  ********************************************
*********************************************************************************************************************************/
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
	//ue_subtotal3();
	//ue_subtotal2();
	ue_subtotal();
	return false;
}

function ue_subtotal()
{
// alert("sub_in");
	f=document.form1;
	li_filasrubrosagri=f.filasrubrosagri.value;
	ld_hectotal=0;
	ld_hect=0;
	ld_hectsin=0;
	ls_cero="0,00";
	ld_prodtotal=0;
	suiche=true;
	lb_convertir=true;

	for(li_i=1;li_i<=li_filasrubrosagri-1;li_i++)
	{
	    if(eval("f.txthectsembradas"+li_i+".value")=="")
		  {
		   ld_canpro=0;
		    }
	   else
		  {
		   if (eval("f.chk"+li_i+".checked==1")){
		   ld_canpro=parseFloat(uf_convertir_monto(eval("f.txthectsembradas"+li_i+".value")));
		   ld_hect=parseFloat(uf_convertir_monto(eval("f.txtnrohect.value")));
		   ld_hectotal=ld_hectotal+ld_canpro;

		   if (ld_hectotal>uf_convertir_monto(f.txtnrohect.value))
			 {
					for(li_j=1;li_j<=li_filasrubrosagri-1;li_j++)
					{
					   eval("f.chk"+li_j+".checked=''");
					   eval("f.txthectsembradas"+li_j+".value='0,00'");
					}
					alert("El nro. de hectareas productivas no puede ser mayor al de hectareas totales");
					lb_convertir=false;
					ls_cero="0,00";
				    f.hectprod.value=ls_cero;
                    f.txthectsinprod.value=ls_cero;
				    suiche=false;
				 }
			 } // if que pusimos
			 else {
			  	eval("f.chk"+li_i+".checked=''");
				 f.hectprod.value=0;
                 f.txthectsinprod.value=0;
			 }
		 }
		if(eval("f.txthectsembradas"+li_i+".value")=="")
		  {
		   ld_canpro1=0;
		   ld_prodtotal=0;
		  }
		  else{
		     ld_canpro1=parseFloat(uf_convertir_monto(eval("f.txthectsembradas"+li_i+".value")));
		  }
		 ld_total=parseFloat(uf_convertir_monto(eval("f.txtnro"+li_i+".value")));

		 ld_prodtotal=ld_canpro1*ld_total;
		 ls_totp=uf_convertir(ld_prodtotal);
		 eval("f.txtcantprod"+li_i+".value='"+ls_totp+"'");
	}
	li_filasrubrospec=f.filasrubrospec.value;
	suiche=true;
	for(li_i=1;li_i<=li_filasrubrospec-1;li_i++)
	{
	   if(eval("f.txtnropec"+li_i+".value")=="")
	   {
		   ld_haspro=0;
	   }
	   else
		  {
		  ld_has     = parseFloat(uf_convertir_monto(eval("f.txthas"+li_i+".value")));
		  ld_animal  = parseFloat(uf_convertir_monto(eval("f.txtnropec"+li_i+".value")));
		  ld_haspro  = ld_animal/ld_has;
		  ld_hasprod = uf_convertir(ld_haspro);
		  eval("f.txthectpec"+li_i+".value='"+ld_hasprod+"'");
		  ld_hect=parseFloat(uf_convertir_monto(eval("f.txtnrohect.value")));
		  ld_hectotal=ld_hectotal+ld_haspro;

		  if (ld_hectotal>uf_convertir_monto(f.txtnrohect.value))
			     {
				   eval("f.txtnropec"+li_i+".value=''");
				   eval("f.txthectpec"+li_i+".value=''");
				   alert("El nro. de hectareas productivas no puede ser mayor al de hectareas totales");
				   ls_cero="0,00";
				   f.hectprod.value=0;
                   f.txthectsinprod.value=0;
				   suiche=false;
				 }

		 if(eval("f.txtnropec"+li_i+".value")=="")
		  {
		   ld_canpro1=0;
		   ld_prodtotal=0;
		  }
		  else{
		     ld_canpro1=parseFloat(uf_convertir_monto(eval("f.txtnropec"+li_i+".value")));
		  }
		 ld_total=parseFloat(uf_convertir_monto(eval("f.txtnroanimal"+li_i+".value")));

		 ld_prodtotal=ld_canpro1*ld_total;
		 ls_totp=uf_convertir(ld_prodtotal);
		 eval("f.txtcantprodpec"+li_i+".value='"+ls_totp+"'");
		 }
		}
		if (suiche==true)
		{
				if(lb_convertir==true)
				{
						ld_hectsin=ld_hect-ld_hectotal;
						f.hectprod.value=uf_convertir(ld_hectotal);
						f.txthectsinprod.value=uf_convertir(ld_hectsin);
				}
				else
				{
				    ls_cero="0,00";
				    f.hectprod.value=ls_cero;
                    f.txthectsinprod.value=ls_cero;
				}
		}
}

function ue_subtotal3()
{
// alert("sub_in");
	f=document.form1;
	li_filasrubrosagri=f.filasrubrosagri.value;
	ld_hectotal=0;
	ld_hect=0;
	ld_hectsin=0;
	ls_cero="0,00";
	ld_prodtotal=0;
	suiche=true;
	lb_convertir=true;
	for(li_i=1;li_i<=li_filasrubrosagri-1;li_i++)
	{
	    if(eval("f.txthectsembradas"+li_i+".value")=="")
		  {
		   ld_canpro=0;
		    }
	   else
		  {
		   if (eval("f.chk"+li_i+".checked==1")){
		   ld_canpro=parseFloat(uf_convertir_monto(eval("f.txthectsembradas"+li_i+".value")));
		   ld_hect=parseFloat(uf_convertir_monto(eval("f.txtnrohect.value")));
		   ld_hectotal=ld_hectotal+ld_canpro;

		   if (ld_hectotal>uf_convertir_monto(f.txtnrohect.value))
		     {
			    alert("El nro. de hectareas productivas no puede ser mayor al de hectareas totales ");
			    ls_cero="0,00";
			    lb_convertir=false;
			    f.hectprod.value=0;
                f.txthectsinprod.value=0;
			   //eval("f.txthectsembradas"+li_i+".value=''");
			   suiche=false;
			 }
			 }
			 else {
			  	eval("f.chk"+li_i+".checked=''");
			 }
		 }
		if(eval("f.txthectsembradas"+li_i+".value")=="")
		  {
		   ld_canpro1=0;
		   ld_prodtotal=0;
		  }
		  else{
		     ld_canpro1=parseFloat(uf_convertir_monto(eval("f.txthectsembradas"+li_i+".value")));
		  }
		 ld_total=parseFloat(uf_convertir_monto(eval("f.txtnro"+li_i+".value")));

		 ld_prodtotal=ld_canpro1*ld_total;
		 ls_totp=uf_convertir(ld_prodtotal);
		 eval("f.txtcantprod"+li_i+".value='"+ls_totp+"'");
	}
	if (suiche==true)
	{
				if(lb_convertir==true)
				{
					ld_hectsin=ld_hect-ld_hectotal;
					f.hectprod.value=uf_convertir(ld_hectotal);
					f.txthectsinprod.value=uf_convertir(ld_hectsin);
				}
				else
				{
				    ls_cero="0,00";
				    f.hectprod.value=ls_cero;
                    f.txthectsinprod.value=ls_cero;
				}

	}
}

function ue_abrir(pagina)
{
	if( (f.txtcedcli.value != "") && (f.txtnomcli.value == "") && (f.operacion.value == "ue_validar") ){
		popupWin(pagina,"catalogo",450,250);
	}

}

function ue_valcajastext(pagina)
{
	 f=document.form1;
	 f.hidreadonly.value="readonly";
     f.hidreadonlyced.value="readonly";
}

function ue_subtotal2()
{
    f=document.form1;
	li_filasrubrospec=f.filasrubrospec.value;
	suiche=true;
	for(li_i=1;li_i<=li_filasrubrospec-1;li_i++)
	{
	   if(eval("f.txtnropec"+li_i+".value")=="")
		  {

		   ld_haspro=0;
		    }
	   else
		  {
		  ld_has=parseFloat(uf_convertir_monto(eval("f.txthas"+li_i+".value")));
		  ld_animal=parseFloat(uf_convertir_monto(eval("f.txtnropec"+li_i+".value")));
		  ld_haspro=ld_animal/ld_has;
		  ld_hasprod=uf_convertir(ld_haspro);
		  eval("f.txthectpec"+li_i+".value='"+ld_hasprod+"'");
		  ld_hect=parseFloat(uf_convertir_monto(eval("f.txtnrohect.value")));
		  ld_hectotal=ld_hectotal+ld_haspro;
		  if (ld_hectotal>uf_convertir_monto(f.txtnrohect.value))
			     {
				   eval("f.txtnropec"+li_i+".value=''");
				   alert("El nro. de hectareas productivas no puede ser mayor al de hectareas totales");
				   ls_cero="0,00";
				   f.hectprod.value=0;
                   f.txthectsinprod.value=0;

				   suiche=false;
				 }

		 if(eval("f.txtnropec"+li_i+".value")=="")
		  {
		   ld_canpro1=0;
		   ld_prodtotal=0;
		  }
		  else{
		     ld_canpro1=parseFloat(uf_convertir_monto(eval("f.txtnropec"+li_i+".value")));
		  }
		 ld_total=parseFloat(uf_convertir_monto(eval("f.txtnroanimal"+li_i+".value")));

		 ld_prodtotal=ld_canpro1*ld_total;
		 ls_totp=uf_convertir(ld_prodtotal);
		 eval("f.txtcantprodpec"+li_i+".value='"+ls_totp+"'");

		}
    }
	if (suiche==true)
	{
	ld_hectsin=ld_hect-ld_hectotal;
	f.hectprod.value=uf_convertir(ld_hectotal);
	f.txthectsinprod.value=uf_convertir(ld_hectsin);

	}

}
</script>
</html>
