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

$ls_codtie=$_SESSION["ls_codtienda"];
                             /**************************************************************************************************************************/
/**************************    SUBMIT   ***********************************************************************************/
/**************************************************************************************************************************/

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$li_filasordenes=$_POST["filasordenes"];
		$li_filasalmacenes=$_POST["filasalmacenes"];
		//print $ls_operacion;
		$ls_operacion2="";
		$ls_operacion3="";
    }
else
	{
		$ls_operacion="";
		$ls_operacion2="";
		$ls_operacion3="";
		
	}
	
	


///////////////////////////////////////////////////////////Cargar Pantalla al Principio/////////////////////////////////////
	
		
	
	$ls_cadenadf="SELECT f.numfac,f.fecemi,f.codemp,df.codalm from  sfc_factura f,sfc_detfactura df where f.codtiend='".$ls_codtie."' and df.numfac=f.numfac group by df.numfac,f.numfac,f.fecemi,f.codemp,df.codalm order by f.numfac asc  ";
	
	$arr_transf=$io_sql->select($ls_cadenadf);

	//$row=$io_sql->fetch_row($arr_transf);
	
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
	   							
	//print $ls_cadenat;
	
	
	
	
	
	
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
    $ls_codtie=$_SESSION["ls_codtienda"];
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

	$io_procmovi->uf_sfc_movimientos($ls_codemp,$ls_codalm);
	
	$ls_cadenadf="SELECT f.numfac,f.fecemi,f.codemp,f.estfaccon from  sfc_factura f where f.codtiend='".$ls_codtie."' 
	order by f.numfac asc ";
	
	$arr_transf=$io_sql->select($ls_cadenadf);

	//$row=$io_sql->fetch_row($arr_transf);
	
	if($row=$io_sql->fetch_row($arr_transf))
			{
				$lb_procesar=1;								
				$la_orduniadm=$io_sql->obtener_datos($arr_transf);
				$io_datastore1->data=$la_orduniadm;
				
				$totrow1=$io_datastore1->getRowCount("codemp");  
				
														
				for($li_j=1;$li_j<=$totrow1;$li_j++)
					{	  
													
						$ls_numfac=$io_datastore1->getValue("numfac",$li_j);
						$ls_codemp=$io_datastore1->getValue("codemp",$li_j);
						$ls_fecemi=$io_datastore1->getValue("fecemi",$li_j);
						$ls_estfaccon=$io_datastore1->getValue("estfaccon",$li_j);
						$ls_obsdes="Despacho por concepto de venta bajo la Factura Nº ".$ls_numfac;
						$ls_numdoc=substr($ls_numfac,10,strlen($ls_numfac));
						$ld_fecemi=$io_function->uf_convertirdatetobd($ls_fecemi);
					
						$lb_valido2=$io_despacho->uf_sim_insert_despacho($ls_codemp,$ls_numord,$ls_numdoc,"",$ld_fecemi,$ls_obsdes,$ls_codusu,"1","1",""/*,$la_seguridad*/);
$lb_valido3=$io_movimiento->uf_sim_insert_movimiento($ls_nummov,$ld_fecemi,"Despacho",$ls_codusu/*,$la_seguridad*/);
						
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
								$ls_codpro=$io_datastore2->getValue("codpro",$li_df);
								$ls_canpro=$io_datastore2->getValue("canpro",$li_df);
								$ls_prepro=$io_datastore2->getValue("prepro",$li_df);
								$ls_porimp=$io_datastore2->getValue("porimp",$li_df);
								$ls_estatus="PROCESADA";
								
							
										/*$ls_codpro=$ls_codpro;
										$ls_codalm=$la_detalles["codalm"][$li_i];
										$ls_cant=$la_detalles["canpro"][$li_i];
										$ls_prepro=$la_detalles["prepro"][$li_i];*/
										$ld_porimp=$io_funcsob->uf_convertir_cadenanumero($ls_porimp);
										$li_preuniart=$io_funcsob->uf_convertir_cadenanumero($ls_prepro);
										$ld_canpro=$io_funcsob->uf_convertir_cadenanumero($ls_canpro);
										$li_montotart=($li_preuniart*$ld_canpro)+(($li_preuniart*$ld_canpro)*$ld_porimp);
										
										$ls_unidad="D";
								//print $ls_prepro."FAC";		
								$io_despacho->uf_sim_procesar_dt_despacho($ls_codemp,$ls_numord,$ls_codpro,$ls_codalm,$ls_unidad,$ld_canpro,$ld_canpro,$ls_prepro,$li_montotart,$li_montotart,$li_df,$ls_nummov,$ld_fecemi,$ls_numdoc,0/*,$la_seguridad*/);
								
  
										if ($ls_estfaccon<>'A')
										{
										$io_art->uf_sim_disminuir_articuloxalmacen($ls_codemp,$ls_codpro,$ls_codalm,$ld_canpro/*,$la_seguridad*/);
										
										$io_art->uf_sim_actualizar_cantidad_articulos($ls_codemp,$ls_codpro/*,$la_seguridad*/);
										}   
									
 							
							}	//for dtfac	
							
						
							 
						}
					}
			

$is_msg->message("FACTURAS Procesadas Exitosamente!!");	

		
			}//facturas 
	$lb_procesar=0;	


$ls_cadenafnul="SELECT f.numfac,f.fecemi,f.codemp from  sfc_factura f where f.codtiend='".$ls_codtie."' AND f.estfaccon='A' order by f.numfac asc ";
	
	$arr_facnul=$io_sql->select($ls_cadenafnul);

	$row=$io_sql->fetch_row($arr_facnul);
	
	if($row=="")
	{	
		$ls_operacion2="";
		$is_msg->message("No Existen Facturas ANULADAS que Procesar!!");	

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
	   							
	//print $ls_cadenat;
	
	$ls_procesar="";
	$ls_operacion="";


}







if($ls_operacion=="ANULAR")
{
	

/**************   VERIFICA SI EL USUARIO ES PERSONAL DE LA TIENDA  ***************************************/	
    $ls_codtie=$_SESSION["ls_codtienda"];
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

	//$io_procmovi->uf_sfc_movimientos($ls_codemp,$ls_codalm);
	
	$ls_cadenanulf="SELECT f.numfac,f.fecemi,f.codemp,f.estfaccon,df.codalm from  sfc_factura f,sfc_detfactura df where f.codtiend='".$ls_codtie."' and df.numfac=f.numfac AND f.estfaccon='A' AND f.estfac='N' group by df.numfac,f.numfac,f.fecemi,f.codemp,f.estfaccon,df.codalm order by f.numfac asc ";
	
	$arr_nulf=$io_sql->select($ls_cadenanulf);

	//$row=$io_sql->fetch_row($arr_transf);
	
	if($row=$io_sql->fetch_row($arr_nulf))
			{
				$lb_procesar=1;								
				$la_orduniadm=$io_sql->obtener_datos($arr_nulf);
				$io_datastore4->data=$la_orduniadm;
				
				$totrow1=$io_datastore4->getRowCount("codemp");  
				
														
				for($li_j=1;$li_j<=$totrow1;$li_j++)
					{	  
													
						$ls_numfac=$io_datastore4->getValue("numfac",$li_j);
						$ls_codemp=$io_datastore4->getValue("codemp",$li_j);
						$ls_fecemi=$io_datastore4->getValue("fecemi",$li_j);
						
						$ls_docum=substr($ls_numfac,0,5);
	     				$ls_docum=$ls_docum.substr($ls_numfac,20,strlen($ls_numfac));
						$ld_fecemi=$io_function->uf_convertirdatetobd($ls_fecemi);
					
						
						 $lb_valido=$io_recepcion->uf_sim_insert_recepcion($ls_codemp,$ls_docum,"----------",$ls_codalm,$ls_fecemi,
								"Entrada a Almacen por Anulacion de Factura",$ls_codusu,"0","1",&$ls_numconrec/*,$la_seguridad*/);
					
					        if ($lb_valido)
						     {
							    $ls_nummov=0;
							    $ls_nomsol="Recepcion";
							    $lb_valido=$io_movimiento->uf_sim_insert_movimiento(&$ls_nummov,$ls_fecemi,$ls_docum,$ls_codusu/*,
																		      $la_seguridad*/);
					 	     }
						
						$ls_sqldf="SELECT df.*  from sfc_detfactura df where df.numfac ilike '".$ls_numfac."' AND df.codtiend='".$ls_codtie."';";
						$arr_detfac=$io_sql->select($ls_sqldf);
						if($row=$io_sql->fetch_row($arr_detfac))
						{
							$lb_procesar=1;								
							$la_detfactura=$io_sql->obtener_datos($arr_detfac);
							$io_datastore5->data=$la_detfactura;
							$li_p=1;
							$totrow2=$io_datastore5->getRowCount("codemp");  
							//print $totrow2;							
							
							
							for($li_da=1;$li_da<=$totrow2;$li_da++)
							{	  
								
								$ls_codalm=$io_datastore5->getValue("codalm",$li_da);
								$ls_codpro=$io_datastore5->getValue("codpro",$li_da);
								$ls_canpro=$io_datastore5->getValue("canpro",$li_da);
								$ls_prepro=$io_datastore5->getValue("prepro",$li_da);
								$ls_porimp=$io_datastore5->getValue("porimp",$li_da);
								
								$ls_estatus="PROCESADA";
								
							
										/*$ls_codpro=$ls_codpro;
										$ls_codalm=$la_detalles["codalm"][$li_i];
										$ls_cant=$la_detalles["canpro"][$li_i];
										$ls_prepro=$la_detalles["prepro"][$li_i];*/
										$ld_porimp=$io_funcsob->uf_convertir_cadenanumero($ls_porimp);
										$li_preuniart=$io_funcsob->uf_convertir_cadenanumero($ls_prepro);
										$ld_canpro=$io_funcsob->uf_convertir_cadenanumero($ls_canpro);
										$li_montotart=($li_preuniart*$ld_canpro)+(($li_preuniart*$ld_canpro)*$ld_porimp);
								//print "NUL".$ls_prepro;		
										//print $li_montotart."MON-*-";
										//$li_montotart=($li_preuniart*$ld_canpro)+(($li_preuniart*$ld_canpro)*$ld_porimp);
										$ls_unidad="D";
										
								$lb_valido=$io_recepcion->uf_sim_insert_dt_recepcion($ls_codemp,$ls_docum,$ls_codpro,$ls_unidad,$ld_canpro,0,$li_preuniart,$li_montotart,$li_montotart,$li_da,$ld_canpro,$ls_numconrec/*,$la_seguridad*/);
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
																						$ld_canpro,$ls_fecemi/*,$la_seguridad*/);
									}
							  }
										
										//$io_art->uf_sim_actualizar_cantidad_articulos($ls_codemp,$ls_codpro/*,$la_seguridad*/);   
									
 							
							}	//for dtfac	
							
						
							 
						}//if
					}//for
			
	$is_msg->message("ANULACIÓN DE FACTURAS Procesadas Exitosamente!!");	
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

	
	
}//if
		
		
		
if($ls_operacion3=="DEVOL")
{
$ls_cadenadevo="SELECT d.coddev,d.numfac,d.fecdev,d.codemp from  sfc_devolucion d where d.codtiend='".$ls_codtie."' order by d.coddev asc";
	
	$arr_d=$io_sql->select($ls_cadenadevo);

	//$row=$io_sql->fetch_row($arr_transf);
	
	if($row=$io_sql->fetch_row($arr_d))
			{
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
		}
					$li_filasordenes=$li_p;
					$la_objectordenes3[$li_filasordenes][1]="<input name=txtcodret".$li_filasordenes." type=text id=txtcodret".$li_filasordenes." class=sin-borde style= text-align:center size=30 readonly>";
					$la_objectordenes3[$li_filasordenes][2]="<input name=txtdesret".$li_filasordenes." type=text id=txtdesret".$li_filasordenes." class=sin-borde style= text-align:center size=15 readonly>";
					$la_objectordenes3[$li_filasordenes][3]="<input name=txtmontoret".$li_filasordenes." type=text id=txtmontoret".$li_filasordenes." class=sin-borde style= text-align:center size=15 ><input name=formula".$li_filasordenes." type=hidden id=formula".$li_filasordenes.">";
	   							
	//print $ls_cadenat;
	
	$ls_procesar="";
	$ls_operacion="";
			
	
}
}


if($ls_operacion=="DEVOLVER")
{
	$ls_codusu=$_SESSION["la_logusr"];
		

$ls_cadenadevo="SELECT d.coddev,d.numfac,d.fecdev,d.codemp from  sfc_devolucion d where d.codtiend='".$ls_codtie."' order by d.coddev asc";
	
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
						$ld_fecdevo=$io_function->uf_convertirdatetobd($ls_fecdevo);
						$ls_estatus='NO PROCESADA';
						
						//KEDE AQUIIIIIII !!! //////////////////						
						
						$ls_docum=substr($ls_coddev,0,5);
					 	$ls_docum=$ls_docum.substr($ls_coddev,20,strlen($ls_coddev));
						//print $ld_fecdev."**";
						$lb_valido=$io_recepcion->uf_sim_insert_recepcion($ls_codemp,$ls_docum,"----------",$ls_codalm,$ld_fecdevo,"Entrada a Almacen por Devolucion",$ls_codusu,"0","1",&$ls_numconrec/*,$la_seguridad*/);
					
					        if ($lb_valido)
						     {
							    $ls_nummov=0;
							    $ls_nomsol="Recepcion";
							    $lb_valido=$io_movimiento->uf_sim_insert_movimiento(&$ls_nummov,$ld_fecdevo,$ls_docum,$ls_codusu/*,
																		      $la_seguridad*/);
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
								$ls_codpro=$io_datastore7->getValue("codpro",$li_dd);
								$ls_candev=$io_datastore7->getValue("candev",$li_dd);
								$ls_precio=$io_datastore7->getValue("precio",$li_dd);
								$ls_porimp=$io_datastore7->getValue("porimp",$li_dd);
							
								
								
								$ls_candev=$io_funcsob->uf_convertir_cadenanumero($ls_candev);
								$ls_precio=$io_funcsob->uf_convertir_cadenanumero($ls_precio); /* convierte cadena en numero */
								$ls_porimp=$io_funcsob->uf_convertir_cadenanumero($ls_porimp);
								$ld_iva=($ls_precio*$ls_porimp)*$ls_candev;
								$li_monsubart=$ls_precio*$ls_candev;
								$li_montotart=$li_monsubart+$ld_iva;
								$ls_estatus="PROCESADA";
								
								
								$lb_valido=$io_recepcion->uf_sim_insert_dt_recepcion($ls_codemp,$ls_docum,$ls_codpro,"D",$ls_candev,0,$ls_precio,$li_monsubart,$li_montotart,$li_dd,$ls_candev,$ls_numconrec/*,$la_seguridad*/);
							if ($lb_valido)
							 {
									$lb_valido=$io_art->uf_sim_aumentar_articuloxalmacen($ls_codemp,$ls_codpro,$ls_codalm,
									                                    				 $ls_candev/*,$la_seguridad*/);
									if($lb_valido)
									{
										$ls_opeinv="ENT";
										$ls_promov="RPC";
										$ls_codprodoc="FAC";
										$lb_valido=$io_movimiento->uf_sim_insert_dt_movimiento($ls_codemp,$ls_nummov,$ld_fecdevo,
																						$ls_codpro,$ls_codalm,$ls_opeinv,
																						$ls_codprodoc,$ls_docum,$ls_candev,
																						$ls_precio,$ls_promov,$ls_numconrec,
																						$ls_candev,$ld_fecdevo/*,$la_seguridad*/);
									}
							  }
							  if($lb_valido)
							   {
								$lb_valido=$io_art->uf_sim_actualizar_cantidad_articulos($ls_codemp,$ls_codpro/*,$la_seguridad*/);
								
								$lb_valido=$io_devolucion->uf_actualizar_factura($ls_numfac,$ls_codpro,$ls_candev,"A");
								
							   }
					        
 							
							}	//for dd
							
							 
						}//if
					}///for
				
					
	$is_msg->message("DEVOLUCIONES Procesadas Exitosamente!!");	
	$lb_procesar3=0;	
	$lb_procesar2=0;	
	$lb_procesar=0;	
	
	}//if
}//$ls_operacion=DEVOLVER

			



		
?>
	
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
/*/////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
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
//////////////////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////*/
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

			  <input name="hidstatus" type="hidden" id="hidstatus">			  </td>
              <td >&nbsp;</td>
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
if ($ls_operacion=="PROCESAR")
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

</script>
</html>