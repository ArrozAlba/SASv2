<?Php
/************************************************************************************************************************/
/***********************************  Generar Archivo de Transferencia-Ordenes de Compra ********************************/
/************************************************************************************************************************/

session_start();

if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_conexion.php'";
	 print "</script>";
   }
$arre=$_SESSION["la_empresa"];
$ls_codemp=$arre["codemp"];
$ls_controlfac=$_SESSION["ls_controlfac"];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<title>Reprocesar Movimientos</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
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

<Script Language="JavaScript">
function loadImages() {
if (document.getElementById) { // DOM3 = IE5, NS6
document.getElementById('hidepage').style.visibility = 'hidden';
}
else {
if (document.layers) { // Netscape 4
document.hidepage.visibility = 'hidden';
}
else { // IE 4
document.all.hidepage.style.visibility = 'hidden';
}
}
}
</script>
<style type="text/css">
<!--
.Estilo2 {font-size: 9}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699" OnLoad="loadImages()">


<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script>
	<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
	</td>
  </tr>
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>

<?Php
/************************************************************************************************************************/
/********************************   LIBRERIAS   *************************************************************************/
/************************************************************************************************************************/
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/grid_param.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_funciones_db.php");
require_once("class_folder/sigesp_sfc_c_factura.php");
require_once("class_folder/sigesp_sob_c_funciones_sob.php");


$io_funcdb=new class_funciones_db($io_connect);

$io_datastore= new class_datastore();
$io_datastore1= new class_datastore();
$io_datastore2= new class_datastore();
$io_datastore3= new class_datastore();
$io_datastore4= new class_datastore();
$io_datastore5= new class_datastore();
$io_datastore6= new class_datastore();
$io_datastore7= new class_datastore();
$io_datastore8= new class_datastore();
$io_datastore9= new class_datastore();
$io_datastore10= new class_datastore();
$io_datastore11= new class_datastore();
$io_datastore12= new class_datastore();
$io_datastore13= new class_datastore();

$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_sql=new class_sql($io_connect);
$io_sql2=new class_sql($io_connect);
$io_data=new class_datastore();
$io_grid=new grid_param();
$io_function=new class_funciones();
$is_msg=new class_mensajes();
$ls_total_facturado=0;
$io_factura=new  sigesp_sfc_c_factura();
$io_funcsob=new sigesp_sob_c_funciones_sob();

require_once("class_folder/sigesp_sim_c_articuloxalmacen.php");
$io_art=  new sigesp_sim_c_articuloxalmacen();
require_once("class_folder/sigesp_sim_c_despacho.php");
$io_despacho=  new sigesp_sim_c_despacho();
require_once("class_folder/sigesp_sim_c_movimientoinventario.php");
$io_movimiento=    new sigesp_sim_c_movimientoinventario();
require_once("class_folder/sigesp_sim_c_recepcion.php");
$io_recepcion=  new sigesp_sim_c_recepcion();
require_once("class_folder/sigesp_sfc_c_procesarmovimientos.php");
$io_procmovi=  new sigesp_sfc_c_procesarmovimientos();
require_once("class_folder/sigesp_sfc_c_devolucion.php");
$io_devolucion=new  sigesp_sfc_c_devolucion();

/**************   GRID   DETALLES  FACTURAS   *******************/
$lb_procesar=0;
$lb_procesar2=0;
$lb_procesar3=0;
$ls_tituloordenes="Facturas Por Procesar";
$li_anchoordenes=500;
$ls_nametable="grid";
$la_columordenes[1]="Nro. Factura";
$la_columordenes[2]="Fecha de Emision";
$la_columordenes[3]="Estatus";
print "<script language=JavaScript>suiche_submit=false;</script>";

$ls_tituloordenes2="Facturas Procesadas ";
$li_anchoordenes2=600;
$ls_nametable2="grid2";
$la_columordenes2[1]="Nro. Factura";
$la_columordenes2[2]="Fecha de Emision";
$la_columordenes2[3]="Estatus";
print "<script language=JavaScript>suiche_submit=false;</script>";

$ls_tituloordenes3="Devoluciones Por Procesar";
$li_anchoordenes3=500;
$ls_nametable3="grid";
$la_columordenes3[1]="Nro. Devolución";
$la_columordenes3[2]="Fecha de Emision";
$la_columordenes3[3]="Estatus";
print "<script language=JavaScript>suiche_submit=false;</script>";

//$ls_codtie=$_SESSION["ls_codtienda"];


//********************************************         SEGURIDAD       ****************************************************
require_once("../shared/class_folder/sigesp_c_seguridad.php");
$io_seguridad= new sigesp_c_seguridad();

$arre=$_SESSION["la_empresa"];
$ls_empresa=$arre["codemp"];
$ls_logusr=$_SESSION["la_logusr"];
$ls_sistema="SFC";
$ls_ventanas="sigesp_sfc_d_actualizar_facturas.php";

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

							 /**************************************************************************************************************************/
/**************************    SUBMIT   ***********************************************************************************/
/**************************************************************************************************************************/

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		//print $ls_operacion;
		$li_filasordenes=$_POST["filasordenes"];
		$li_filasalmacenes=$_POST["filasalmacenes"];
		//print $ls_operacion;
		$ls_operacion2="";
		$ls_operacion3="";
		$ls_codtie=$_POST["txtcodtie"];
		$ls_destienda=$_POST["txtdestienda"];
		$ls_codalm=$_POST["txtcodalm"];

    }
	else
	{
		$ls_operacion="";
		$ls_operacion2="";
		$ls_operacion3="";
		$ls_codalm="";
		$ls_codtie="";
		$ls_destienda="";
	}



///////////////////////////////////////////////////////////Cargar Pantalla al Principio/////////////////////////////////////

if($ls_operacion=="CARGAR")
{


	if ($ls_controlfac==0)
	{
		$ls_cadenadf="SELECT f.numfac,f.fecemi,f.codemp,df.codalm,df.cod_pro from  sfc_factura f,sfc_detfactura df where f.codtiend='".$ls_codtie."' and df.numfac=f.numfac group by df.numfac,f.numfac,f.fecemi,f.codemp,df.codalm,df.cod_pro order by f.numfac asc limit 100 ";
	}
	else
	{
		$ls_ultimafac=$_SESSION["ls_ultimafac"];
		$ls_cadenadf="SELECT f.numfac,f.fecemi,f.codemp,df.codalm,df.cod_pro from  sfc_factura f,sfc_detfactura df where f.codtiend='".$ls_codtie."' and df.numfac=f.numfac and f.numfac >'".$ls_ultimafac."' group by df.numfac,f.numfac,f.fecemi,f.codemp,df.codalm,df.cod_pro order by f.numfac asc limit 100 ";
	}
//print $ls_cadenadf;
	$arr_transf=$io_sql->select($ls_cadenadf);

	//$row=$io_sql->fetch_row($arr_transf);

	//print substr("FAC-10000",0,4);
	if($row=$io_sql->fetch_row($arr_transf))
	{
		$lb_procesar=1;
		$la_orduniadm=$io_sql->obtener_datos($arr_transf);
		$io_datastore1->data=$la_orduniadm;
		$li_p=1;
		$totrow1=$io_datastore1->getRowCount("codemp");
		//print $totrow1;
		for($li_j=1;$li_j<=$totrow1;$li_j++)
		{

			$ls_numfac=$io_datastore1->getValue("numfac",$li_j);
			$ls_codemp=$io_datastore1->getValue("codemp",$li_j);
			$ls_fecemi=$io_datastore1->getValue("fecemi",$li_j);
			$ls_codalm=$io_datastore1->getValue("codalm",$li_j);
			$ls_codproveedor=$io_datastore1->getValue("cod_pro",$li_j);



			$ls_estatus='NO PROCESADA';
			$la_objectordenes[$li_p][1]="<input name=txtcodret".$li_p." type=text id=txtcodret".$li_p." class=sin-borde value='".$ls_numfac."' style= text-align:center size=30 readonly>";
			$la_objectordenes[$li_p][2]="<input name=txtdesret".$li_p." type=text id=txtdesret".$li_p." class=sin-borde value='".$ls_fecemi."' style= text-align:center size=15 readonly>";
			$la_objectordenes[$li_p][3]="<input name=txtmontoret".$li_p." type=text id=txtmontoret".$li_p." class=sin-borde value='".$ls_estatus."' style= text-align:center size=15 readonly>";

			$li_p++;
			$li_filasordenes++;

		}///for
	}//if
	else
	{
		$is_msg->message("No existen FACTURAS para Procesar!!");
	}

	$li_filasordenes=$li_p;
	$la_objectordenes[$li_filasordenes][1]="<input name=txtcodret".$li_filasordenes." type=text id=txtcodret".$li_filasordenes." class=sin-borde style= text-align:center size=30 readonly>";
	$la_objectordenes[$li_filasordenes][2]="<input name=txtdesret".$li_filasordenes." type=text id=txtdesret".$li_filasordenes." class=sin-borde style= text-align:center size=15 readonly>";
	$la_objectordenes[$li_filasordenes][3]="<input name=txtmontoret".$li_filasordenes." type=text id=txtmontoret".$li_filasordenes." class=sin-borde style= text-align:center size=15 ><input name=formula".$li_filasordenes." type=hidden id=formula".$li_filasordenes.">";

	//print $ls_cadenat;
}

if($ls_operacion=="PROCESAR")
{

?>

<div id="hidepage" style="position: absolute; left:0px; top:0px; background-image:url(/../Imagenes/icon-binoculars.gif); filter:alpha(opacity=80); -moz-opacity:0.8; opacity: 0.8; height: 100%; width: 100%;">
<table border=0 width="100%" height="100%">
<tr>
<td valign="middle" align="center">
<b>Procesando Datos... Por favor espere...</b>
</td>
</tr>
</table>
</div>

<?php

/**************   VERIFICA SI EL USUARIO ES PERSONAL DE LA TIENDA  ***************************************/
   // $ls_codtie=$_SESSION["ls_codtienda"];
	if($ls_codtie!="")
	{
	 $ls_codusu=$_SESSION["la_logusr"];//codigo del Usuario
	 $lb_valido_logusr=true;
	 $ls_codcaj=$_SESSION["ls_codcaj"];
	}
    else
	{
	 $lb_valido_logusr=false;
	 $io_msg->message ("El usuario no es personal de la tienda no puede procesar facturas !!!");
	}
	/************************************************************************************************************/
print "PASE".$ls_codalm;

//print $ls_controlfac;
	if ($ls_controlfac==0)
	{
		$io_procmovi->uf_sfc_movimientos($ls_codemp,$ls_codalm,$ls_codtie);

		$io_procmovi->uf_ultimo_costo($ls_codemp,$ls_codtie,$ls_codproveedor);

		$io_procmovi->uf_costo_prom($ls_codemp,$ls_codalm,$ls_codtie,$ls_codproveedor);

		$_SESSION["ls_controlfac"]=1;

		$ls_maxfactura="SELECT MAX(numfac) as total FROM sfc_factura";

		$rs_total=$io_sql->select($ls_maxfactura);

		if($row=$io_sql->fetch_row($rs_total))
		{
			$_SESSION["ls_finfactura"]=$row["total"];
		}

		$ls_cadenadf="SELECT f.numfac,f.fecemi,f.codemp,f.estfaccon from  sfc_factura f where f.codtiend='".$ls_codtie."' order by f.numfac asc limit 100 ";



	}
	else
	{
		$ls_ultimafac=$_SESSION["ls_ultimafac"];
		$ls_cadenadf="SELECT f.numfac,f.fecemi,f.codemp,f.estfaccon from  sfc_factura f where f.codtiend='".$ls_codtie."' and f.numfac>'".$ls_ultimafac."' order by f.numfac asc limit 100 ";
	}
	//print $ls_cadenadf."<br>";
	$arr_transf=$io_sql->select($ls_cadenadf);

	//$row=$io_sql->fetch_row($arr_transf);

	if($row=$io_sql->fetch_row($arr_transf))
	{
		$lb_procesar=1;
		$la_orduniadm=$io_sql->obtener_datos($arr_transf);
		$io_datastore1->data=$la_orduniadm;

		$totrow1=$io_datastore1->getRowCount("codemp");

		//$is_msg->message("Existen: ".$totrow1." Facturas por Procesar");

		$ls_sqluniadmin="select coduniadm from sfc_tienda where codtiend='".$ls_codtie."'";
		//print $ls_sqluniadmin;
		$arr_uniadmin=$io_sql->select($ls_sqluniadmin);
		if($row=$io_sql->fetch_row($arr_uniadmin))
			{
				$la_uniadmin=$io_sql->obtener_datos($arr_uniadmin);
				$io_datastore->data=$la_uniadmin;
				$ls_coduniadm=$io_datastore->getValue("coduniadm",1);
			}

		for($li_j=1;$li_j<=$totrow1;$li_j++)
		{
			$ls_numfac=$io_datastore1->getValue("numfac",$li_j);
			$ls_codemp=$io_datastore1->getValue("codemp",$li_j);
			$ls_fecemi=$io_datastore1->getValue("fecemi",$li_j);
			$ls_estfaccon=$io_datastore1->getValue("estfaccon",$li_j);
			$ls_obsdes="Despacho por venta bajo la Fact. ".$ls_numfac;
			$ls_numdoc=substr($ls_numfac,10,strlen($ls_numfac));
			$ld_fecemi=$io_function->uf_convertirdatetobd($ls_fecemi);


				//$la_detfactura=$io_sql->obtener_datos($arr_detfac);


			$lb_valido2=$io_despacho->uf_sim_insert_despacho($ls_codemp,$ls_numord,$ls_numdoc,$ls_coduniadm,$ld_fecemi,$ls_obsdes,$ls_codusu,"1","1","",$ls_codtie/*,$la_seguridad*/);

			$lb_valido3=$io_movimiento->uf_sim_insert_movimiento($ls_codemp,$ls_nummov,$ld_fecemi,$ls_obsdes,$ls_codusu,$ls_codtie/*,$la_seguridad*/);


			$ls_sqldf="SELECT df.*  from sfc_detfactura df where df.numfac ilike '".$ls_numfac."' and df.codtiend='".$ls_codtie."';";
			$arr_detfac=$io_sql->select($ls_sqldf);
			if($row=$io_sql->fetch_row($arr_detfac))
			{
				$lb_procesar=1;
				$la_detfactura=$io_sql->obtener_datos($arr_detfac);
				$io_datastore2->data=$la_detfactura;
				$li_p=1;
				$totrow2=$io_datastore2->getRowCount("codemp");
				//print $totrow2;

				for($li_df=1;$li_df<=$totrow2;$li_df++)
				{
					$ls_codalm=$io_datastore2->getValue("codalm",$li_df);
					$ls_codpro=$io_datastore2->getValue("codart",$li_df);
					$ls_canpro=$io_datastore2->getValue("canpro",$li_df);
					$ls_prepro=$io_datastore2->getValue("prepro",$li_df);
					$ls_porimp=$io_datastore2->getValue("porimp",$li_df);
					$ls_codproveedor=$io_datastore2->getValue("cod_pro",$li_df);
					$ls_estatus="PROCESADA";

					/*$ls_codpro=$ls_codpro;
					$ls_codalm=$la_detalles["codalm"][$li_i];
					$ls_cant=$la_detalles["canpro"][$li_i];
					$ls_prepro=$la_detalles["prepro"][$li_i];*/

					/*$ld_porimp=$io_funcsob->uf_convertir_cadenanumero($ls_porimp);
					$li_preuniart=$io_funcsob->uf_convertir_cadenanumero($ls_prepro);
					$ld_canpro=$io_funcsob->uf_convertir_cadenanumero($ls_canpro);*/

					$li_preuniart=$ls_prepro;
					$ld_porimp=$ls_porimp;
					$ld_canpro=$ls_canpro;
					$li_montotart=($li_preuniart*$ld_canpro)+(($li_preuniart*$ld_canpro)*$ld_porimp);

					$ls_unidad="D";
					//print $ls_prepro."FAC";
					$io_despacho->uf_sim_procesar_dt_despacho($ls_codemp,$ls_numord,$ls_codpro,$ls_codalm,$ls_unidad,$ld_canpro,$ld_canpro,$ls_prepro,$li_montotart,$li_montotart,$li_df,$ls_nummov,$ld_fecemi,$ls_numdoc,0,$ls_codproveedor,$ls_codtie/*,$la_seguridad*/);

					if ($ls_estfaccon!='A')
					{
						//print $ls_numfac."<br>";
						$io_art->uf_sim_disminuir_articuloxalmacenmovi($ls_codemp,$ls_codpro,$ls_codalm,$ld_canpro,$ls_codproveedor,$ls_codtie/*,$la_seguridad*/);

						///***** NO se hace porq sta actualizaba la EXISTENCIA en sim_articulo, y sta tabla ya no tiene ese campo!!!!!***********/////
						//$io_art->uf_sim_actualizar_cantidad_articulosmovi($ls_codemp,$ls_codpro,$ls_codalm,$ls_codproveedor,$ls_codtiend/*,$la_seguridad*/);
					}

				}	//for dtfac

			}//if detfactura

		}//for factura

		if($_SESSION["ls_finfactura"]!=$ls_numfac)
		{
			//print $_SESSION["ls_finfactura"];
	        $is_msg->message("Aún quedan Facturas por Procesar!!");
			$_SESSION["ls_ultimafac"]=$ls_numfac;
			print "<script languaje=javascript>";
			print "location.href='sigesp_sfc_d_actualizar_facturas.php'";
			print "</script>";
		}
		else
		{
			//$is_msg->message("Van: ".$li_j." Facturas Procesadas");
			$is_msg->message("FACTURAS Procesadas Exitosamente!!");
		}
	}//if facturas


	$lb_procesar=0;

	$ls_cadenafnul="SELECT f.numfac,f.fecemi,f.codemp from  sfc_factura f where f.codtiend='".$ls_codtie."' AND f.estfaccon='A' order by f.numfac asc ";

	$arr_facnul=$io_sql->select($ls_cadenafnul);

	$row=$io_sql->fetch_row($arr_facnul);

	if($row=="")
	{
		$ls_operacion2="";
		$is_msg->message("No Existen Facturas ANULADAS que Procesar!!");
		$ls_operacion3="DEVOL";

	}
	elseif($row!="")
	{
		$ls_operacion2="NULAS";
		$is_msg->message("Existen Facturas ANULADAS que Procesar!!");
	}


}//$ls_operacion=PROCESAR

if($ls_operacion2=="NULAS")
{

	$ls_cadenadf="SELECT f.numfac,f.fecemi,f.codemp,df.codalm from  sfc_factura f,sfc_detfactura df where f.codtiend='".$ls_codtie."' and df.numfac=f.numfac AND f.estfaccon='A' group by df.numfac,f.numfac,f.fecemi,f.codemp,df.codalm order by f.numfac asc  ";

	$arr_transf=$io_sql->select($ls_cadenadf);

	//$row=$io_sql->fetch_row($arr_transf);

	if($row=$io_sql->fetch_row($arr_transf))
	{
		$lb_procesar2=1;
		$la_orduniadm=$io_sql->obtener_datos($arr_transf);
		$io_datastore1->data=$la_orduniadm;
		$li_p=1;
		$totrow1=$io_datastore1->getRowCount("codemp");
		//print $totrow1;
		for($li_j=1;$li_j<=$totrow1;$li_j++)
		{
			$ls_numfac=$io_datastore1->getValue("numfac",$li_j);
			$ls_codemp=$io_datastore1->getValue("codemp",$li_j);
			$ls_fecemi=$io_datastore1->getValue("fecemi",$li_j);
			$ls_codalm=$io_datastore1->getValue("codalm",$li_j);

			$ls_estatus='NO PROCESADA';

			//KEDE AQUIIIIIII !!! //////////////////
			//$ls_sqlmov="SELECT COUNT(df.numfac) as numreg,f.numfac,df.* from  sfc_factura f,sfc_detfactura df where df.numfac=f.numfac group by df.numfac order by df.numfac asc ";

			$la_objectordenes[$li_p][1]="<input name=txtcodret".$li_p." type=text id=txtcodret".$li_p." class=sin-borde value='".$ls_numfac."' style= text-align:center size=30 readonly>";

			$la_objectordenes[$li_p][2]="<input name=txtdesret".$li_p." type=text id=txtdesret".$li_p." class=sin-borde value='".$ls_fecemi."' style= text-align:center size=15 readonly>";

			$la_objectordenes[$li_p][3]="<input name=txtmontoret".$li_p." type=text id=txtmontoret".$li_p." class=sin-borde value='".$ls_estatus."' style= text-align:center size=15 readonly>";

			$li_p++;
			$li_filasordenes++;

		}///for
	}//if
	else
	{
		$is_msg->message("No existen FACTURAS para Procesar!!");
	}

	$li_filasordenes=$li_p;
	$la_objectordenes[$li_filasordenes][1]="<input name=txtcodret".$li_filasordenes." type=text id=txtcodret".$li_filasordenes." class=sin-borde style= text-align:center size=30 readonly>";
	$la_objectordenes[$li_filasordenes][2]="<input name=txtdesret".$li_filasordenes." type=text id=txtdesret".$li_filasordenes." class=sin-borde style= text-align:center size=15 readonly>";
	$la_objectordenes[$li_filasordenes][3]="<input name=txtmontoret".$li_filasordenes." type=text id=txtmontoret".$li_filasordenes." class=sin-borde style= text-align:center size=15 ><input name=formula".$li_filasordenes." type=hidden id=formula".$li_filasordenes.">";
	//print "Pase y mostre nulas";
	//print $ls_cadenat;

	$ls_procesar="";
	$ls_operacion="";


}


if($ls_operacion=="ANULAR")
{

?>
<div id="hidepage" style="position: absolute; left:0px; top:0px; background-image:url(/../Imagenes/icon-binoculars.gif); filter:alpha(opacity=80); -moz-opacity:0.8; opacity: 0.8; height: 100%; width: 100%;">
<table border=0 width="100%" height="100%">
<tr>
<td valign="middle" align="center">
<b>Procesando Facturas Anuladas... Por favor espere...</b>
</td>
</tr>
</table>
</div>


<?php


$ls_codusu=$_SESSION["la_logusr"];
	//$io_procmovi->uf_sfc_movimientos($ls_codemp,$ls_codalm,$ls_codtie);

	$ls_cadenanula="SELECT f.numfac,f.fecemi,f.codemp,f.estfaccon,df.codalm,df.cod_pro from  sfc_factura f,sfc_detfactura df where f.codtiend='".$ls_codtie."' and df.numfac=f.numfac AND f.estfaccon='A' group by df.numfac,f.numfac,f.fecemi,f.codemp,f.estfaccon,df.codalm order by f.numfac asc ";
	//print $ls_cadenanula;
	$arr_nula=$io_sql->select($ls_cadenanula);

	$la_facnula=$io_sql->obtener_datos($arr_nula);
	//print_r($la_facnula);
	//print "Entre en procesar Anuladas";
	//$row=$io_sql->fetch_row($arr_transf);

	if($row=$io_sql->fetch_row($arr_nula))
				{
	//print $row;
				$lb_procesar=1;
				$la_facnula=$io_sql->obtener_datos($arr_nula);
				$io_datastore4->data=$la_facnula;

				$totrow2=$io_datastore4->getRowCount("codemp");

				for($li_j=1;$li_j<=$totrow2;$li_j++)
					{

						$ls_numfac=$io_datastore4->getValue("numfac",$li_j);
						$ls_codemp=$io_datastore4->getValue("codemp",$li_j);
						$ls_fecemi=$io_datastore4->getValue("fecemi",$li_j);
						$ls_codproveedor=$io_datastore4->getValue("cod_pro",$li_j);

						$ls_docum=substr($ls_numfac,0,5);
	     				$ls_docum=$ls_docum.substr($ls_numfac,20,strlen($ls_numfac));
						$ld_fecemi=$io_function->uf_convertirdatetobd($ls_fecemi);


						 $lb_valido=$io_recepcion->uf_sim_insert_recepcion($ls_codemp,$ls_docum,$ls_codalm,$ls_fecemi,
								"Entrada a Almacen por Anulacion de Factura",$ls_codusu,"0","1",$ls_codproveedor,$ls_codtie,&$ls_numconrec/*,$la_seguridad*/);


					        if ($lb_valido)
						     {
							    $ls_nummov=0;
							    $ls_nomsol="Recepcion";
							    $lb_valido=$io_movimiento->uf_sim_insert_movimiento($ls_codemp,&$ls_nummov,$ls_fecemi,$ls_docum,$ls_codusu,$ls_codtie/*,$la_seguridad*/);
					 	     }

						$ls_sqldf="SELECT df.*  from sfc_detfactura df where df.numfac ilike '".$ls_numfac."' AND df.codtiend='".$ls_codtie."';";
						$arr_detfac=$io_sql->select($ls_sqldf);
						if($row=$io_sql->fetch_row($arr_detfac))
						{
							$lb_procesar=1;
							$la_detfactura=$io_sql->obtener_datos($arr_detfac);
							$io_datastore5->data=$la_detfactura;
							$li_p=1;
							$totrow3=$io_datastore5->getRowCount("codemp");



							for($li_da=1;$li_da<=$totrow3;$li_da++)
							{

								$ls_codalm=$io_datastore5->getValue("codalm",$li_da);
								$ls_codpro=$io_datastore5->getValue("codart",$li_da);
								$ls_canpro=$io_datastore5->getValue("canpro",$li_da);
								$ls_prepro=$io_datastore5->getValue("prepro",$li_da);
								$ls_porimp=$io_datastore5->getValue("porimp",$li_da);
								$ls_codproveedor=$io_datastore5->getValue("cod_pro",$li_da);

								$ls_estatus="PROCESADA";

										/*$ld_porimp=$io_funcsob->uf_convertir_cadenanumero($ls_porimp);
										$li_preuniart=$io_funcsob->uf_convertir_cadenanumero($ls_prepro);
										$ld_canpro=$io_funcsob->uf_convertir_cadenanumero($ls_canpro);*/
										$ld_porimp=$ls_porimp;
										$li_preuniart=$ls_prepro;
										$ld_canpro=$ls_canpro;
										$li_montotart=($li_preuniart*$ld_canpro)+(($li_preuniart*$ld_canpro)*$ld_porimp);
										$ls_unidad="D";

								$lb_valido=$io_recepcion->uf_sim_insert_dt_recepcion($ls_codemp,$ls_docum,$ls_codpro,$ls_unidad,$ld_canpro,0,$li_preuniart,$li_montotart,$li_montotart,$li_da,$ld_canpro,$ls_numconrec,$ls_codproveedor,$ls_codtie/*,$la_seguridad*/);

							if ($lb_valido)
							 {
									//$lb_valido=$io_art->uf_sim_aumentar_articuloxalmacen($ls_codemp,$ls_codpro,$ls_codalm,
//									                                    				 $ld_canpro/*,$la_seguridad*/);
									if($lb_valido)
									{
										$ls_opeinv="ENT";
										$ls_promov="RPC";
										$ls_codprodoc="FAC";
										$lb_valido=$io_movimiento->uf_sim_insert_dt_movimiento($ls_codemp,$ls_nummov,$ls_fecemi,
																						$ls_codpro,$ls_codalm,$ls_opeinv,
																						$ls_codprodoc,$ls_docum,$ld_canpro,
																						$ls_prepro,$ls_promov,$ls_numconrec,
																						$ld_canpro,$ls_fecemi,$ls_codproveedor,$ls_codtie/*,$la_seguridad*/);

								}
							  }

										//$io_art->uf_sim_actualizar_cantidad_articulos($ls_codemp,$ls_codpro/*,$la_seguridad*/);


							}	//for dtfac



						}//if
					}//for



	$is_msg->message("ANULACIÓN DE FACTURAS Procesadas Exitosamente!!");
	$lb_procesar2=0;
	$lb_procesar=0;





}//if


	$lb_procesar2=0;
	$lb_procesar=0;

	$ls_cadenadev="SELECT d.coddev,d.numfac,d.fecdev,d.codemp from  sfc_devolucion d where d.codtiend='".$ls_codtie."' order by d.coddev asc ";

	$arr_dev=$io_sql->select($ls_cadenadev);

	$row=$io_sql->fetch_row($arr_dev);

	if($row=="")
	{
		$ls_operacion3="";
		$is_msg->message("No Existen DEVOLUCIONES que Procesar!!");

	}
	elseif($row!="")
	{
		$ls_operacion3="DEVOL";
		$is_msg->message("Existen DEVOLUCIONES que Procesar!!");

	}



}

if($ls_operacion3=="DEVOL")
{
	$ls_cadenadevo="SELECT d.coddev,d.numfac,d.fecdev,d.codemp from  sfc_devolucion d where d.codtiend='".$ls_codtie."' and d.estdev!='A' order by d.coddev asc";

	$arr_d=$io_sql->select($ls_cadenadevo);

	//$row=$io_sql->fetch_row($arr_transf);

	if($row=$io_sql->fetch_row($arr_d))
	{
		$ls_devolver=0;
		$lb_procesar3=1;
		$la_devo=$io_sql->obtener_datos($arr_d);
		$io_datastore5->data=$la_devo;
		$li_p=1;
		$totrowd=$io_datastore5->getRowCount("coddev");
		//print $totrowd;
		for($li_d=1;$li_d<=$totrowd;$li_d++)
		{

			$ls_coddev=$io_datastore5->getValue("coddev",$li_d);
			$ls_fecdev=$io_datastore5->getValue("fecdev",$li_d);
			$ld_fecdev=$io_function->uf_convertirdatetobd($ls_fecdev);
			$ls_estatus='NO PROCESADA';

			$la_objectordenes3[$li_p][1]="<input name=txtcodret".$li_p." type=text id=txtcodret".$li_p." class=sin-borde value='".$ls_coddev."' style= text-align:center size=30 readonly>";
			$la_objectordenes3[$li_p][2]="<input name=txtdesret".$li_p." type=text id=txtdesret".$li_p." class=sin-borde value='".$ld_fecdev."' style= text-align:center size=15 readonly>";
			$la_objectordenes3[$li_p][3]="<input name=txtmontoret".$li_p." type=text id=txtmontoret".$li_p." class=sin-borde value='".$ls_estatus."' style= text-align:center size=15 readonly>";

			$li_p++;
			$li_filasordenes++;

		}///for
	}//if
	else
	{
		$is_msg->message("No existen DEVOLUCIONES para Procesar!!");
		$ls_devolver = 1;
	}

	$li_filasordenes=$li_p;
	$la_objectordenes3[$li_filasordenes][1]="<input name=txtcodret".$li_filasordenes." type=text id=txtcodret".$li_filasordenes." class=sin-borde style= text-align:center size=30 readonly>";
	$la_objectordenes3[$li_filasordenes][2]="<input name=txtdesret".$li_filasordenes." type=text id=txtdesret".$li_filasordenes." class=sin-borde style= text-align:center size=15 readonly>";
	$la_objectordenes3[$li_filasordenes][3]="<input name=txtmontoret".$li_filasordenes." type=text id=txtmontoret".$li_filasordenes." class=sin-borde style= text-align:center size=15 ><input name=formula".$li_filasordenes." type=hidden id=formula".$li_filasordenes.">";

	//print $ls_cadenat;

	$ls_procesar="";
	$ls_operacion="";

	if($ls_devolver == 1)
	{
		 print "<script language=JavaScript>";
		 print "location.href='../sigesp_conexion.php'";
		 print "</script>";
	}


}


if($ls_operacion=="DEVOLVER")
{
	$ls_codusu=$_SESSION["la_logusr"];


	$ls_cadenadevo="SELECT d.coddev,d.numfac,d.fecdev,d.codemp,dd.cod_pro from  sfc_devolucion d,sfc_detdevolucion dd where d.codtiend='".$ls_codtie."' and d.estdev!='A' and dd.coddev=d.coddev AND dd.codtiend='".$ls_codtie."' AND dd.codtiend=d.codtiend order by d.coddev asc";

	$arr_d=$io_sql->select($ls_cadenadevo);

	//$row=$io_sql->fetch_row($arr_transf);

	if($row=$io_sql->fetch_row($arr_d))
			{
				$lb_procesar3=0;
				$la_devolucion=$io_sql->obtener_datos($arr_d);
				$io_datastore6->data=$la_devolucion;
				$li_p=1;
				$totrow6=$io_datastore6->getRowCount("coddev");
				//print $totrow6;
				for($li_d=1;$li_d<=$totrow6;$li_d++)
					{

						$ls_coddev=$io_datastore6->getValue("coddev",$li_d);
						$ls_fecdevo=$io_datastore6->getValue("fecdev",$li_d);
						$ls_codproveedor=$io_datastore6->getValue("cod_pro",$li_d);
						$ld_fecdevo=$io_function->uf_convertirdatetobd($ls_fecdevo);
						$ls_estatus='NO PROCESADA';


						$ls_docum=substr($ls_coddev,0,5);
					 	$ls_docum=$ls_docum.substr($ls_coddev,20,strlen($ls_coddev));
						//print $ld_fecdev."**";
						$lb_valido=$io_recepcion->uf_sim_insert_recepcion($ls_codemp,$ls_docum,$ls_codalm,$ld_fecdevo,"Entrada a Almacen por Devolucion",$ls_codusu,"0","1",$ls_codproveedor,$ls_codtie,&$ls_numconrec/*,$la_seguridad*/);


					        if ($lb_valido)
						     {
							    $ls_nummov=0;
							    $ls_nomsol="Recepcion";
							    $lb_valido=$io_movimiento->uf_sim_insert_movimiento($ls_codemp,&$ls_nummov,$ld_fecdevo,$ls_docum,$ls_codusu,$ls_codtie/*,$la_seguridad*/);

					 	     }

	$ls_sqldetd="SELECT dd.*  from sfc_detdevolucion dd where dd.coddev ilike '".$ls_coddev."' and dd.codtiend='".$ls_codtie."';";

						$arr_detdev=$io_sql->select($ls_sqldetd);
						if($row=$io_sql->fetch_row($arr_detdev))
						{

							$la_detdevolucion=$io_sql->obtener_datos($arr_detdev);
							$io_datastore7->data=$la_detdevolucion;
							$li_p=1;
							$totrow7=$io_datastore7->getRowCount("codemp");
							//print $totrow2;


							for($li_dd=1;$li_dd<=$totrow7;$li_dd++)
							{

								$ls_coddev=$io_datastore7->getValue("coddev",$li_dd);
								$ls_codpro=$io_datastore7->getValue("codart",$li_dd);
								$ls_candev=$io_datastore7->getValue("candev",$li_dd);
								$ls_precio=$io_datastore7->getValue("precio",$li_dd);
								$ls_porimp=$io_datastore7->getValue("porimp",$li_dd);
								$ls_codproveedor=$io_datastore7->getValue("cod_pro",$li_dd);
								$ls_codalm=$io_datastore7->getValue("codalm",$li_dd);

								/*$ls_candev=$io_funcsob->uf_convertir_cadenanumero($ls_candev);
								$ls_precio=$io_funcsob->uf_convertir_cadenanumero($ls_precio); /* convierte cadena en numero */
								//$ls_porimp=$io_funcsob->uf_convertir_cadenanumero($ls_porimp);
								$ls_candev=$ls_candev;
								$ls_precio=$ls_precio;
								$ls_porimp=$ls_porimp;
								$ld_iva=($ls_precio*$ls_porimp)*$ls_candev;
								$li_monsubart=$ls_precio*$ls_candev;
								$li_montotart=$li_monsubart+$ld_iva;
								$ls_estatus="PROCESADA";


								$lb_valido=$io_recepcion->uf_sim_insert_dt_recepcion($ls_codemp,$ls_docum,$ls_codpro,"D",$ls_candev,0,$ls_precio,$li_monsubart,$li_montotart,$li_dd,$ls_candev,$ls_numconrec,$ls_codproveedor,$ls_codtie/*,$la_seguridad*/);


							if ($lb_valido)
							 {
									$lb_valido=$io_art->uf_sim_aumentar_articuloxalmacen($ls_codemp,$ls_codpro,$ls_codalm,
									                                    				 $ls_candev,$ls_codproveedor,$ls_codtie/*,$la_seguridad*/);
									if($lb_valido)
									{
										$ls_opeinv="ENT";
										$ls_promov="RPC";
										$ls_codprodoc="FAC";
										$lb_valido=$io_movimiento->uf_sim_insert_dt_movimiento($ls_codemp,$ls_nummov,$ld_fecdevo,
																						$ls_codpro,$ls_codalm,$ls_opeinv,
																						$ls_codprodoc,$ls_docum,$ls_candev,
																						$ls_precio,$ls_promov,$ls_numconrec,
																						$ls_candev,$ld_fecdevo,$ls_codproveedor,$ls_codtie/*,$la_seguridad*/);


									}
							  }
							  if($lb_valido)
							   {


								//$lb_valido=$io_art->uf_sim_actualizar_cantidad_articulos($ls_codemp,$ls_codpro,$ls_codproveedor,$ls_codtie/*,$la_seguridad*/);

								$lb_valido=$io_devolucion->uf_actualizar_factura($ls_numfac,$ls_codpro,$ls_candev,"A",$ls_codproveedor,$ls_codtie,$la_seguridad);

							   }


							}	//for dd


						}//if
					}///for


	$is_msg->message("DEVOLUCIONES Procesadas Exitosamente!!");
	$is_msg->message("Ahora Debera Ingresar Nuevamente al Sistema!");

	$lb_procesar3=0;
	$lb_procesar2=0;
	$lb_procesar=0;

	print "<script language=JavaScript>";
	print "location.href='../sigesp_conexion.php'";
	print "</script>";

	}//if
}//$ls_operacion=DEVOLVER






?>

<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
/////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
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
//////////////////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////*/

//print $lb_procesar."--".$lb_procesar2."--".$lb_procesar3;
?>

    <table width="518" height="197" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">

	 <tr>


	<td width="516" height="195"><div align="center">
          <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
            <tr>
              <td colspan="2" class="titulo-ventana">Reprocesar Movimientos </td>
            </tr>
            <tr>
              <td >
			  <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
			  <input name="operacion2" type="hidden" id="operacion2"  value="<? print $ls_operacion2?>">
			   <input name="txtcodalm" type="hidden" id="txtcodalm" value="<? print $ls_codalm?>" size="12" maxlength="12">

			  <input name="hidstatus" type="hidden" id="hidstatus">			  </td>
              <td >&nbsp;</td>
            </tr>

 <tr>
        <input name="txtcodtie" type="text" id="hidden" value="<? print $ls_codtie?>" size="5" maxlength="4">

        <td width="67" height="30"><div align="right">Tienda</div></td>

        <td> <input name="txtdestienda" type="text" id="txtdestienda"  value="<? print $ls_destienda?>" size="50" maxlength="50"><a href="javascript:ue_buscartienda();">
        <img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></td>

      </tr>
			<tr>
              <td width="76" height="22" align="right">&nbsp;</td>
              <td width="392" ><!-- javascript:ue_catusuario(); -->
              <?php


			   if (($lb_procesar==1) and ($lb_procesar2==0) and ($lb_procesar3==0))
			   {

			   ?>
			  <a href="javascript:ue_procesar();"><img src="Imagenes/ejecutar.gif" width="20" height="20" border="0">Procesar</a>

			   <?php
			   }
			  elseif(($lb_procesar==0) and ($lb_procesar2==0) and ($lb_procesar3==0))
			 {

			  ?>
			 <img src="Imagenes/ejecutar.gif" width="20" height="20" border="0">Procesar</td>

			  <?php
			 }

			elseif (($lb_procesar2==1) and ($lb_procesar==0) and ($lb_procesar3==0))
			   {

			   ?>
			  <a href="javascript:ue_procesar2();"><img src="Imagenes/ejecutar.gif" width="20" height="20" border="0">Procesar</a>

			   <?php
			   }
			  elseif (($lb_procesar2==0) and ($lb_procesar==0) and ($lb_procesar3==1))
			   {

			   ?>
			  <a href="javascript:ue_procesar3();"><img src="Imagenes/ejecutar.gif" width="20" height="20" border="0">Procesar</a>

			   <?php
			   }
			  elseif (($lb_procesar==0) and ($lb_procesar2==0) and ($lb_procesar3==0))
			 {

			  ?>
			 <img src="Imagenes/ejecutar.gif" width="20" height="20" border="0">Procesar</td>

			  <?php
			 }


			?>

			</tr>
            <tr>
              <td width="76" height="22" align="right">&nbsp;</td>
              <td width="392" >&nbsp;</td>
            </tr>

            <tr>
              <td height="8" colspan="2"><table width="430" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">

                <tr>
                  <td width="74" align="right">&nbsp;</td>
                  <td colspan="3" >&nbsp;</td>
                </tr>

				<?PHP

/************************************************************************************************************************/
/***************************   PROCESAR ********************************************************************************/
/************************************************************************************************************************/
if($ls_operacion=="CARGAR")
{
?>

	<tr align="center" class="formato-blanco">
              <td height="11" colspan="2"><?php $io_grid->makegrid($li_filasordenes,$la_columordenes,$la_objectordenes,$li_anchoordenes,$ls_tituloordenes,$ls_nametable);?></td>
	  </tr>



<?php
}
elseif ($ls_operacion=="PROCESAR")
{



?>


  <tr align="center" class="formato-blanco">
              <td height="11" colspan="2"><?php $io_grid->makegrid($li_filasordenes2,$la_columordenes2,$la_objectordenes2,$li_anchoordenes2,$ls_tituloordenes2,$ls_nametable2);?>
</td>





<?php
}

elseif(($ls_operacion=="") and ($ls_operacion2=="") and ($ls_operacion3==""))
{
?>

	<tr align="center" class="formato-blanco">
              <td height="11" colspan="2"><?php $io_grid->makegrid($li_filasordenes,$la_columordenes,$la_objectordenes,$li_anchoordenes,$ls_tituloordenes,$ls_nametable);?></td>
	  </tr>



<?php
}


elseif ($ls_operacion2=="NULAS")
{

?>


  <tr align="center" class="formato-blanco">
              <td height="11" colspan="2"><?php $io_grid->makegrid($li_filasordenes,$la_columordenes,$la_objectordenes,$li_anchoordenes,"Facturas Anuladas por Procesar",$ls_nametable);?></td>
	  </tr>





<?php

}


elseif ($ls_operacion3=="DEVOL")
{

?>


  <tr align="center" class="formato-blanco">
              <td height="11" colspan="2"><?php $io_grid->makegrid($li_filasordenes,$la_columordenes3,$la_objectordenes3,$li_anchoordenes3,$ls_tituloordenes3,$ls_nametable3);?></td>
	  </tr>

<?php

}
?>


               </table>
              <p>&nbsp;</p></td>
            </tr>
          </table>
          <p>&nbsp;</p>
        </div></td>
      </tr>
  </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>


<?PHP

/************************************************************************************************************************/
/***************************************   FIN DEL FORMULARIO  **********************************************************/
/************************************************************************************************************************/

?>
</form>
</body>

<script language="JavaScript">


/*******************************************************************************************************************************/
function ue_procesar()
{

  f=document.form1;
  f.operacion.value="PROCESAR";
  f.action="sigesp_sfc_d_actualizar_facturas.php";
  f.submit();

  }

 function ue_procesar2()
{

  f=document.form1;
  f.operacion.value="ANULAR";
  alert(f.operacion.value)
  f.action="sigesp_sfc_d_actualizar_facturas.php";
  f.submit();

  }

 function ue_procesar3()
{

  f=document.form1;
  f.operacion.value="DEVOLVER";
  f.action="sigesp_sfc_d_actualizar_facturas.php";

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
            f.txtdestienda.value=nomtie;
            f.operacion.value="CARGAR";
            f.submit();


		}


</script>
</html>
