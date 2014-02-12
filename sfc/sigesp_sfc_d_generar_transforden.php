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

<title>Archivo de Transferencia</title>
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
<style type="text/css">
<!--
.Estilo3 {color: #6699CC}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="510" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1 Estilo3">Sistema de Facturaci&oacute;n</span></td>
    <td width="268" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">&nbsp;</td>
    <td height="20" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="cd-menu">
	<script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script>
	<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>	</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>

<?Php
include("class_folder/createzip.php");
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
require_once("../shared/class_folder/sigesp_sfc_c_intarchivo.php");
//$io_archivo= new sigesp_sfc_c_intarchivo("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/ORDEN_COMPRA");
$io_archivo= new sigesp_sfc_c_intarchivo("/var/www/sigesp_fac/sfc/transferencias/ORDEN_COMPRA");
//$io_archivoO= new sigesp_sfc_c_intarchivo("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/ALMACENORIGEN");
//$io_archivoD= new sigesp_sfc_c_intarchivo("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/ALMACENDESTINO");
$io_archivoO= new sigesp_sfc_c_intarchivo("/var/www/sigesp_fac/sfc/transferencias/ALMACENORIGEN");
$io_archivoD= new sigesp_sfc_c_intarchivo("/var/www/sigesp_fac/sfc/transferencias/ALMACENDESTINO");
require_once("class_folder/sigesp_sfc_c_transferencia_almacen.php");
$io_sfc= new sigesp_sfc_c_transferencia_almacen();
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
/**************   GRID   DETALLES   ORDENES DE COMPRA   *******************/

$ls_tituloordenes="Ordenes Por Procesar";
$li_anchoordenes=600;
$ls_nametable="grid";
$la_columordenes[1]="Numero Orden";
$la_columordenes[2]="Fecha de Emision";
$la_columordenes[3]="Forma de Pago";
$la_columordenes[4]="Monto Total";
print "<script language=JavaScript>suiche_submit=false;</script>";

$ls_tituloordenes2="Ordenes Procesadas para Transferencia";
$li_anchoordenes2=600;
$ls_nametable2="grid2";
$la_columordenes2[1]="Numero Orden";
$la_columordenes2[2]="Fecha de Emision";
$la_columordenes2[3]="Forma de Pago";
$la_columordenes2[4]="Monto Total";
$la_columordenes2[5]="Estatus";
print "<script language=JavaScript>suiche_submit=false;</script>";

/**************   GRID   DETALLES TRANSFERENCIA ENTRE ALMACENES  *******************/

$ls_tituloalmacenes3="Transferencia Entre Almacenes Procesadas";
$li_anchoalmacenes3=800;
$ls_nametable3="grid";
$la_columalmacenes3[1]="Numero Transferencia";
$la_columalmacenes3[2]="Fecha de Emision";
$la_columalmacenes3[3]="codigo Almacen Origen";
$la_columalmacenes3[4]="Codigo Almacen Destino";
$la_columalmacenes3[5]="Codigo Articulo";
$la_columalmacenes3[6]="Proceso";

print "<script language=JavaScript>suiche_submit=false;</script>";
	
/**************   GRID   DETALLES TRANSFERENCIA ENTRE ALMACENES  *******************/

$ls_tituloalmacenes4="Transferencia Entre Almacenes";
$li_anchoalmacenes4=800;
$ls_nametable4="grid";
$la_columalmacenes4[1]="Numero Transferencia";
$la_columalmacenes4[2]="Fecha de Emision";
$la_columalmacenes4[3]="codigo Almacen Origen";
$la_columalmacenes4[4]="Codigo Almacen Destino";
$la_columalmacenes4[5]="Codigo Articulo";
print "<script language=JavaScript>suiche_submit=false;</script>";	

/**************************************************************************************************************************/
/**************************    SUBMIT   ***********************************************************************************/
/**************************************************************************************************************************/

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$li_filasordenes=$_POST["filasordenes"];
		$li_filasalmacenes=$_POST["filasalmacenes"];
    }
else
	{
		$ls_operacion="";
		
	}
	
	


///////////////////////////////////////////////////////////Cargar Pantalla al Principio/////////////////////////////////////
	
		
	
	$ls_cadenat="Select sfc_transferenciaorden.* From sfc_transferenciaorden ";
	
	$arr_transf=$io_sql->select($ls_cadenat);

	$row=$io_sql->fetch_row($arr_transf);
	$ls_fechahoy=date('Y-m-d');
	//$ls_fechahoy='2007-07-31';
	
	if($row==0)
	{    
	

	$ls_sql1="Select spg_unidadadministrativa.coduniadm,spg_unidadadministrativa.denuniadm FROM 
						spg_unidadadministrativa ";
						
						 $arr_orduniadm=$io_sql->select($ls_sql1);
						
							if($arr_orduniadm==false&&($io_sql->message!=""))
							{
								$is_msg->message("No hay Ordenes Contabilizadas");
							}
							else
							{						
								if($row=$io_sql->fetch_row($arr_orduniadm))
								  {
									
									$la_orduniadm=$io_sql->obtener_datos($arr_orduniadm);
									$io_datastore1->data=$la_orduniadm;
									$li_p=1;
									$totrow1=$io_datastore1->getRowCount("coduniadm");  
									
									for($li_j=1;$li_j<=$totrow1;$li_j++)
									{	  
		 								
										$ls_denuniad=$io_datastore1->getValue("denuniadm",$li_j);
										$ls_den=(substr($ls_denuniad,1,5));
										$ls_denominacionu=(substr($ls_denuniad,31,20));
									
									
									 if ($ls_den=="02-03")
									{
										$ls_coduniad=$io_datastore1->getValue("coduniadm",$li_j);					
										
									
$ls_cadena3="SELECT soc_ordencompra.* FROM soc_ordencompra WHERE 
soc_ordencompra.coduniadm='".$ls_coduniad."' AND soc_ordencompra.codemp = '".$ls_codemp."' 										AND  soc_ordencompra.numordcom not in (select numordcom from sfc_transferenciaorden ) AND soc_ordencompra.estcondat='B' AND soc_ordencompra.estcom='2' AND   soc_ordencompra.fecordcom='".$ls_fechahoy."' ORDER BY soc_ordencompra.numordcom ASC";
										
							
										$arr_ordcom=$io_sql->select($ls_cadena3);
						
										if($arr_ordcom==false)
										{
											//$is_msg->message("No hay Ordenes Contabilizadas");
											$lb_procesar=0;
										}
										else
										{						
										
											if($row=$io_sql->fetch_row($arr_ordcom))
											  {
													$lb_procesar=1;
												$la_ordenes=$io_sql->obtener_datos($arr_ordcom);
												$io_datastore->data=$la_ordenes;
												$totrow=$io_datastore->getRowCount("codemp");  
											
												for($li_i=1;$li_i<=$totrow;$li_i++)
												{	
											
													$ls_numordcomp=$io_datastore->getValue("numordcom",$li_i);
													$ls_estcondat=$io_datastore->getValue("estcondat",$li_i);
													$ls_codpro=$io_datastore->getValue("cod_pro",$li_i);
													$ls_codmon=$io_datastore->getValue("codmon",$li_i);   
													$ls_codfuefin=$io_datastore->getValue("codfuefin",$li_i);  
													$ls_codtipmod=$io_datastore->getValue("codtipmod",$li_i);    
													$ls_fecordcom=$io_datastore->getValue("fecordcom",$li_i);   
													$ls_estsegcom=$io_datastore->getValue("estsegcom",$li_i);   
													$ls_porsegcom=$io_datastore->getValue("porsegcom",$li_i);   
													$ls_monsegcom=$io_datastore->getValue("monsegcom",$li_i);   
													$ls_forpagcom=$io_datastore->getValue("forpagcom",$li_i);   
													$ls_estcom=$io_datastore->getValue("estcom",$li_i);   
													$ls_diaplacom=$io_datastore->getValue("diaplacom",$li_i);   
													$ls_concom=$io_datastore->getValue("concom",$li_i);   
													$ls_obscom=$io_datastore->getValue("obscom",$li_i);   
													$ls_monsubtotbie=$io_datastore->getValue("monsubtotbie",$li_i);
													$ls_monsubtotser=$io_datastore->getValue("monsubtotser",$li_i);
													$ls_monsubtot=$io_datastore->getValue("monsubtot",$li_i);   
													$ls_monbasimp=$io_datastore->getValue("monbasimp",$li_i);
													$ls_monimp=$io_datastore->getValue("monimp",$li_i);
													$ls_mondes=$io_datastore->getValue("mondes",$li_i);
													$ls_montot=$io_datastore->getValue("montot",$li_i);
													$ls_estpenalm=$io_datastore->getValue("estpenalm",$li_i);
													$ls_codpai=$io_datastore->getValue("codpai",$li_i);
													$ls_codest=$io_datastore->getValue("codest",$li_i);
													$ls_codmun=$io_datastore->getValue("codmun",$li_i);
													$ls_codpar=$io_datastore->getValue("codpar",$li_i);
													$ls_lugentnomdep=$io_datastore->getValue("lugentnomdep",$li_i);
													$ls_lugentdir=$io_datastore->getValue("lugentdir",$li_i);
													$ls_monant=$io_datastore->getValue("monant",$li_i);
													$ls_estlugcom=$io_datastore->getValue("estlugcom",$li_i);
													$ls_tascamordcom=$io_datastore->getValue("tascamordcom",$li_i);
													$ls_montotdiv=$io_datastore->getValue("montotdiv",$li_i);
													$ls_estapro=$io_datastore->getValue("estapro",$li_i);
													$ls_fecaprord=$io_datastore->getValue("fecaprord",$li_i);
													$ls_codusuapr=$io_datastore->getValue("codusuapr",$li_i);
													$ls_numpolcom=$io_datastore->getValue("numpolcom",$li_i);
													$ls_coduniadm=$io_datastore->getValue("coduniadm",$li_i);
		 											$ls_obsordcom=$io_datastore->getValue("obsordcom",$li_i);
													$ls_fecent=$io_datastore->getValue("fecent",$li_i);
													$ls_fechaconta=$io_datastore->getValue("fechaconta",$li_i);
													$ls_fechaanula=$io_datastore->getValue("fechaanula",$li_i);
																
													

													$ls_cadenadt="Select * from soc_dt_bienes where soc_dt_bienes.numordcom='".
													$ls_numordcomp."' AND soc_dt_bienes.codemp='".$ls_codemp."' ";
											
													$arr_orddt=$io_sql->select($ls_cadenadt);
						
													if($arr_orddt==false&&($io_sql->message!=""))
													{
														$is_msg->message("La Orden no tiene Bienes Asociados");
													}
													else
													{						
														if($row=$io_sql->fetch_row($arr_orddt))
														  {
															$la_orddt=$io_sql->obtener_datos($arr_orddt);
															$io_datastore2->data=$la_orddt;
															$totdt=$io_datastore2->getRowCount("numordcom");  
														
															for($li_s=1;$li_s<=$totdt;$li_s++)
															{	
																$ls_numorddt=$io_datastore2->getValue("numordcom",$li_s);
																$ls_estcondatdt=$io_datastore2->getValue("estcondat",$li_s);
																$ls_codartdt=$io_datastore2->getValue("codart",$li_s);
																$ls_unidaddt=$io_datastore2->getValue("unidad",$li_s);   
																$ld_canartdt=$io_datastore2->getValue("canart",$li_s);  
																$ld_penartdt=$io_datastore2->getValue("penart",$li_s);  
																$ld_preuniartdt=$io_datastore2->getValue("preuniart",$li_s);
																$ld_monsubartdt=$io_datastore2->getValue("monsubart",$li_s);
																$ld_montotartdt=$io_datastore2->getValue("montotart",$li_s);
																$li_ordendt=$io_datastore2->getValue("orden",$li_s);   

		 																						
															}//for dt_bienes
															
														}//if row arr_dtbienes
													}//else arrorddt	
															
										//$ls_estatus="No Procesada";			
													$la_objectordenes[$li_p][1]="<input name=txtcodret".$li_p." type=text id=txtcodret".$li_p." class=sin-borde value='".$ls_numordcomp."' style= text-align:center size=15 readonly>";
													
													$la_objectordenes[$li_p][2]="<input name=txtdesret".$li_p." type=text id=txtdesret".$li_p." class=sin-borde value='".$ls_fecordcom."' style= text-align:center size=8 readonly>";
													
													$la_objectordenes[$li_p][3]="<input name=txtmontoret".$li_p." type=text id=txtmontoret".$li_p." class=sin-borde value='".$ls_forpagcom."' style= text-align:center size=10 readonly>"; 
														 
													$la_objectordenes[$li_p][4]="<input name=txttotalret".$li_p." type=text id=txttotalret".$li_p." class=sin-borde value='".$ls_montot."' style= text-align:center size=12 readonly>";  
												
				
							$li_p++;		
							$li_filasordenes++;			
								
										}//for arr_ordcom
										$li_filasordenes=$li_p;
										$la_objectordenes[$li_filasordenes][1]="<input name=txtcodret".$li_filasordenes." type=text id=txtcodret".$li_filasordenes." class=sin-borde style= text-align:center size=15 readonly>";
								$la_objectordenes[$li_filasordenes][2]="<input name=txtdesret".$li_filasordenes." type=text id=txtdesret".$li_filasordenes." class=sin-borde style= text-align:center size=8 readonly>";
								$la_objectordenes[$li_filasordenes][3]="<input name=txtmontoret".$li_filasordenes." type=text id=txtmontoret".$li_filasordenes." class=sin-borde style= text-align:center size=10 ><input name=formula".$li_filasordenes." type=hidden id=formula".$li_filasordenes.">";
	   							$la_objectordenes[$li_filasordenes][4]="<input name=txttotalret".$li_filasordenes." type=text id=txttotalret".$li_filasordenes." class=sin-borde style= text-align:center size=12 readonly>";
								
									 }	//if row arr_ordcom
							
								}//else arr_ordcom
									
							}//if ls_den='02-03'
						}//for arr_ordenes1=spg_unidadadministrativa
					}//if arr_ordenes1=spg_unidadadministrativa
				}//else	arr_ordenes1=spg_unidadadministrativa
	}
	else 
	{
		$ls_sql1="Select spg_unidadadministrativa.coduniadm,spg_unidadadministrativa.denuniadm FROM 
						spg_unidadadministrativa ";
						
						 $arr_orduniadm=$io_sql->select($ls_sql1);
						
							if($arr_orduniadm==false&&($io_sql->message!=""))
							{
								$is_msg->message("No hay Ordenes Contabilizadas, No existen registros en sfc_tranforden");
							}
							else
							{						
								if($row=$io_sql->fetch_row($arr_orduniadm))
								  {
									
									$la_orduniadm=$io_sql->obtener_datos($arr_orduniadm);
									$io_datastore1->data=$la_orduniadm;
									$li_p=1;
									$totrow1=$io_datastore1->getRowCount("coduniadm");  
									
									for($li_j=1;$li_j<=$totrow1;$li_j++)
									{	  
		 								
										$ls_denuniad=$io_datastore1->getValue("denuniadm",$li_j);
										$ls_den=(substr($ls_denuniad,1,5));
										$ls_denominacionu=(substr($ls_denuniad,31,20));
										
										
									 if ($ls_den=="02-03")
									{
										$ls_coduniad=$io_datastore1->getValue("coduniadm",$li_j);					
										
								
$ls_cadena5="Select soc_ordencompra.* from soc_ordencompra where fecordcom='".$ls_fechahoy."' and  estcom<>'2' and numordcom in (Select numordcom from sfc_transferenciaorden where estcom='2' ) and codemp='".$ls_codemp."' and coduniadm ='".$ls_coduniad."' ORDER BY soc_ordencompra.numordcom ASC";										
							
	
										$arr_ordcom=$io_sql->select($ls_cadena5);
						
										if($arr_ordcom==false)
										{
											$lb_procesar=0;
										}
										else
										{						
											
											if($row=$io_sql->fetch_row($arr_ordcom))
											  {
												$la_ordenes=$io_sql->obtener_datos($arr_ordcom);
												$io_datastore->data=$la_ordenes;
												$totrow=$io_datastore->getRowCount("numordcom");  
											$lb_procesar=1;
												for($li_i=1;$li_i<=$totrow;$li_i++)
												{	
											
													$ls_numordcomp=$io_datastore->getValue("numordcom",$li_i);
													$ls_estcondat=$io_datastore->getValue("estcondat",$li_i);
													$ls_codpro=$io_datastore->getValue("cod_pro",$li_i);
													$ls_codmon=$io_datastore->getValue("codmon",$li_i);   
													$ls_codfuefin=$io_datastore->getValue("codfuefin",$li_i);  
													$ls_codtipmod=$io_datastore->getValue("codtipmod",$li_i);    
													$ls_fecordcom=$io_datastore->getValue("fecordcom",$li_i);   
													$ls_estsegcom=$io_datastore->getValue("estsegcom",$li_i);   
													$ls_porsegcom=$io_datastore->getValue("porsegcom",$li_i);   
													$ls_monsegcom=$io_datastore->getValue("monsegcom",$li_i);   
													$ls_forpagcom=$io_datastore->getValue("forpagcom",$li_i);   
													$ls_estcom=$io_datastore->getValue("estcom",$li_i);   
													$ls_diaplacom=$io_datastore->getValue("diaplacom",$li_i);   
													$ls_concom=$io_datastore->getValue("concom",$li_i);   
													$ls_obscom=$io_datastore->getValue("obscom",$li_i);   
													$ls_monsubtotbie=$io_datastore->getValue("monsubtotbie",$li_i);
													$ls_monsubtotser=$io_datastore->getValue("monsubtotser",$li_i);
													$ls_monsubtot=$io_datastore->getValue("monsubtot",$li_i);   
													$ls_monbasimp=$io_datastore->getValue("monbasimp",$li_i);
													$ls_monimp=$io_datastore->getValue("monimp",$li_i);
													$ls_mondes=$io_datastore->getValue("mondes",$li_i);
													$ls_montot=$io_datastore->getValue("montot",$li_i);
													$ls_estpenalm=$io_datastore->getValue("estpenalm",$li_i);
													$ls_codpai=$io_datastore->getValue("codpai",$li_i);
													$ls_codest=$io_datastore->getValue("codest",$li_i);
													$ls_codmun=$io_datastore->getValue("codmun",$li_i);
													$ls_codpar=$io_datastore->getValue("codpar",$li_i);
													$ls_lugentnomdep=$io_datastore->getValue("lugentnomdep",$li_i);
													$ls_lugentdir=$io_datastore->getValue("lugentdir",$li_i);
													$ls_monant=$io_datastore->getValue("monant",$li_i);
													$ls_estlugcom=$io_datastore->getValue("estlugcom",$li_i);
													$ls_tascamordcom=$io_datastore->getValue("tascamordcom",$li_i);
													$ls_montotdiv=$io_datastore->getValue("montotdiv",$li_i);
													$ls_estapro=$io_datastore->getValue("estapro",$li_i);
													$ls_fecaprord=$io_datastore->getValue("fecaprord",$li_i);
													$ls_codusuapr=$io_datastore->getValue("codusuapr",$li_i);
													$ls_numpolcom=$io_datastore->getValue("numpolcom",$li_i);
													$ls_coduniadm=$io_datastore->getValue("coduniadm",$li_i);
		 											$ls_obsordcom=$io_datastore->getValue("obsordcom",$li_i);
													$ls_fecent=$io_datastore->getValue("fecent",$li_i);
													$ls_fechaconta=$io_datastore->getValue("fechaconta",$li_i);
													$ls_fechaanula=$io_datastore->getValue("fechaanula",$li_i);
																
													

													$ls_cadenadt="Select * from soc_dt_bienes where soc_dt_bienes.numordcom='".
													$ls_numordcomp."' AND soc_dt_bienes.codemp='".$ls_codemp."' ";
											
													$arr_orddt=$io_sql->select($ls_cadenadt);
						
													if($arr_orddt==false&&($io_sql->message!=""))
													{
														$is_msg->message("La Orden no tiene Bienes Asociados");
													}
													else
													{						
														if($row=$io_sql->fetch_row($arr_orddt))
														  {
															$la_orddt=$io_sql->obtener_datos($arr_orddt);
															$io_datastore2->data=$la_orddt;
															$totdt=$io_datastore2->getRowCount("numordcom");  
														
															for($li_s=1;$li_s<=$totdt;$li_s++)
															{	
																$ls_numorddt=$io_datastore2->getValue("numordcom",$li_s);
																$ls_estcondatdt=$io_datastore2->getValue("estcondat",$li_s);
																$ls_codartdt=$io_datastore2->getValue("codart",$li_s);
																$ls_unidaddt=$io_datastore2->getValue("unidad",$li_s);   
																$ld_canartdt=$io_datastore2->getValue("canart",$li_s);  
																$ld_penartdt=$io_datastore2->getValue("penart",$li_s);  
																$ld_preuniartdt=$io_datastore2->getValue("preuniart",$li_s);
																$ld_monsubartdt=$io_datastore2->getValue("monsubart",$li_s);
																$ld_montotartdt=$io_datastore2->getValue("montotart",$li_s);
																$li_ordendt=$io_datastore2->getValue("orden",$li_s);   

		 																						
															}//for dt_bienes
															
														}//if row arr_dtbienes
													}//else arrorddt	
															
										//$ls_estatus="No Procesada";			
													$la_objectordenes[$li_p][1]="<input name=txtcodret".$li_p." type=text id=txtcodret".$li_p." class=sin-borde value='".$ls_numordcomp."' style= text-align:center size=15 readonly>";
													
													$la_objectordenes[$li_p][2]="<input name=txtdesret".$li_p." type=text id=txtdesret".$li_p." class=sin-borde value='".$ls_fecordcom."' style= text-align:center size=8 readonly>";
													
													$la_objectordenes[$li_p][3]="<input name=txtmontoret".$li_p." type=text id=txtmontoret".$li_p." class=sin-borde value='".$ls_forpagcom."' style= text-align:center size=10 readonly>"; 
														 
													$la_objectordenes[$li_p][4]="<input name=txttotalret".$li_p." type=text id=txttotalret".$li_p." class=sin-borde value='".$ls_montot."' style= text-align:center size=12 readonly>";  
													 
											
							$li_p++;		
							$li_filasordenes++;			
								
										}//for arr_ordcom
										$li_filasordenes=$li_p;
										$la_objectordenes[$li_filasordenes][1]="<input name=txtcodret".$li_filasordenes." type=text id=txtcodret".$li_filasordenes." class=sin-borde style= text-align:center size=15 readonly>";
								$la_objectordenes[$li_filasordenes][2]="<input name=txtdesret".$li_filasordenes." type=text id=txtdesret".$li_filasordenes." class=sin-borde style= text-align:center size=8 readonly>";
								$la_objectordenes[$li_filasordenes][3]="<input name=txtmontoret".$li_filasordenes." type=text id=txtmontoret".$li_filasordenes." class=sin-borde style= text-align:center size=10 ><input name=formula".$li_filasordenes." type=hidden id=formula".$li_filasordenes.">";
	   							$la_objectordenes[$li_filasordenes][4]="<input name=txttotalret".$li_filasordenes." type=text id=txttotalret".$li_filasordenes." class=sin-borde style= text-align:center size=12 readonly>";
							
									 }	//if row arr_ordcom
							
								}//else arr_ordcom
									
							}//if ls_den='02-03'
						}//for arr_ordenes1=spg_unidadadministrativa
					}//if arr_ordenes1=spg_unidadadministrativa
				}//else	arr_ordenes1=spg_unidadadministrativa
		
	}
	
	
	////////////////////////////////////////  CARGAR TRANSFERENCIA ENTRE ALMACENES   /////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$ls_cadena="SELECT * FROM sim_transferencia WHERE sim_transferencia.codemp = '".$ls_codemp."'";
						$arr_almacenes=$io_sql->select($ls_cadena);						
						
						if($arr_almacenes==false)
						{
							$is_msg->message("No hay Transferencia Entre Almacenes");							
							$lb_procesar2=0;
							//print $lb_procesar2;
						}
						else
						{						
							
							if($row=$io_sql->fetch_row($arr_almacenes))
							  {
								$la_almacenes=$io_sql->obtener_datos($arr_almacenes);
								$io_datastore6->data=$la_almacenes;
								$li_p=1;
								$li_filasalmacenes4=1;
								$ls_proceso="Transferencia";
								$totrow1=$io_datastore6->getRowCount("numtra");  
								
								for($li_i=1;$li_i<=$totrow1;$li_i++)
								{	
							
									$ls_codemp=$io_datastore6->getValue("codemp",$li_i);
									$ls_numtra=$io_datastore6->getValue("numtra",$li_i);
									$ls_fecemi=$io_datastore6->getValue("fecemi",$li_i);
									$ls_codalmori=$io_datastore6->getValue("codalmori",$li_i);
									$ls_codalmdes=$io_datastore6->getValue("codalmdes",$li_i);
									
									
									$ls_cadena2="SELECT * FROM sim_dt_transferencia WHERE numtra = '".$ls_numtra."' ";
									$arr_almacenes2=$io_sql->select($ls_cadena2);
									if($arr_almacenes2==true){
										
										if($row=$io_sql->fetch_row($arr_almacenes2))
										  {
											
											$la_almacenes2=$io_sql->obtener_datos($arr_almacenes2);
											$io_datastore7->data=$la_almacenes2;
											$totrow=$io_datastore7->getRowCount("numtra");  
											
											for($li_z=1;$li_z<=$totrow;$li_z++)
											{	
												
												$ls_codart=$io_datastore7->getValue("codart",$li_z);
												$ls_cantidad=$io_datastore7->getValue("cantidad",$li_z);
												
												$lb_valido9=false;
												$lb_valido9=$io_sfc->uf_sfc_select_transferencia_almacen($ls_codemp,$ls_numtra,$ls_fecemi,$ls_codalmori,$ls_codalmdes);
												if (!$lb_valido9){
											$lb_procesar2=1;
											
										$la_objectoalmacenes4[$li_p][1]="<input name=txtcodret".$li_p." type=text id=txtcodret".$li_p." class=sin-borde value='".$ls_numtra."' style= text-align:center size=15 readonly>";
										
										$la_objectoalmacenes4[$li_p][2]="<input name=txtdesret".$li_p." type=text id=txtdesret".$li_p." class=sin-borde value='".$ls_fecemi."' style= text-align:center size=8 readonly>";
										
										$la_objectoalmacenes4[$li_p][3]="<input name=txtmontoret".$li_p." type=text id=txtmontoret".$li_p." class=sin-borde value='".$ls_codalmori."' style= text-align:center size=8 readonly>"; 
										
										$la_objectoalmacenes4[$li_p][4]="<input name=txttotalret".$li_p." type=text id=txttotalret".$li_p." class=sin-borde value='".$ls_codalmdes."' style= text-align:center size=8 readonly>";
										
										$la_objectoalmacenes4[$li_p][5]="<input name=txttotalre".$li_p." type=text id=txttotalre".$li_p." class=sin-borde value='".$ls_codart."' style= text-align:center size=25 readonly>";
					
			$li_p++;
			$li_filasalmacenes4++;		
													} // if lb_valido9	
												} // for li_z
									
						$li_filasalmacenes4=$li_p;
						$la_objectoalmacenes4[$li_filasalmacenes4][1]="<input name=txtcodret".$li_filasalmacenes4." type=text id=txtcodret".$li_filasalmacenes4." class=sin-borde style= text-align:center size=15 readonly>";
						$la_objectoalmacenes4[$li_filasalmacenes4][2]="<input name=txtdesret".$li_filasalmacenes4." type=text id=txtdesret".$li_filasalmacenes4." class=sin-borde style= text-align:center size=8 readonly>";
				$la_objectoalmacenes4[$li_filasalmacenes4][3]="<input name=txtmontoret".$li_filasalmacenes4." type=text id=txtmontoret".$li_filasalmacenes4." class=sin-borde style= text-align:center size=8 readonly>";
				$la_objectoalmacenes4[$li_filasalmacenes4][4]="<input name=txttotalret".$li_filasalmacenes4." type=text id=txttotalret".$li_filasalmacenes4." class=sin-borde style= text-align:center size=8 readonly>";
				$la_objectoalmacenes4[$li_filasalmacenes4][5]="<input name=txttotalre".$li_filasalmacenes4." type=text id=txttotalre".$li_filasalmacenes4." class=sin-borde style= text-align:center size=25 readonly>";
				
										
										
											} // if almacen2
										}	// if
							
										else
										{
											$lb_procesar2=0;
											
										}
								} // for li_i
						
						 }	// if
			
					} // else


if($ls_operacion=="PROCESAR")
{
	$ls_fechahoy=date('Y-m-d');
      	//$ls_fechahoy='2007-07-31';
		$estatus="NP";
		
				$lb_val=false;
				$lb_val=$io_sfc->uf_sfc_select_transferencia($ls_codemp);
				if($lb_val){
				
						$ls_sql_sfc="SELECT * FROM sfc_transferenciaalmacen WHERE sfc_transferenciaalmacen.codemp = '".$ls_codemp."'";
						$arr_alm_sfc=$io_sql->select($ls_sql_sfc);						
						
						if($arr_alm_sfc==false)
						{
							$is_msg->message("No hay Transferencia Entre Almacenes");							
						}
						else
						{						
							if($row=$io_sql->fetch_row($arr_alm_sfc))
							  {
								$la_alm_sfc=$io_sql->obtener_datos($arr_alm_sfc);
								$io_datastore4->data=$la_alm_sfc;
								$li_x=1;
								$ls_proceso="Reverso";
								$ls_fecha=date('dmY');
								$totrow2=$io_datastore2->getRowCount("numtra");  
								
								for($li_j=1;$li_j<=$totrow2;$li_j++)
								{	
							
									$ls_codemp=$io_datastore4->getValue("codemp",$li_j);
									$ls_numtra=$io_datastore4->getValue("numtra",$li_j);
									$ls_fecemi=$io_datastore4->getValue("fecemi",$li_j);
									$ls_codalmori=$io_datastore4->getValue("codalmori",$li_j);
									$ls_codalmdes=$io_datastore4->getValue("codalmdes",$li_j);
									
									
									$ls_sql_sfc2="SELECT * FROM sfc_dt_transferenciaalmacen WHERE numtra = '".$ls_numtra."' ";
									$arr_alm_sfc2=$io_sql->select($ls_sql_sfc2);
									if($arr_alm_sfc2==true){
										if($row=$io_sql->fetch_row($arr_alm_sfc2))
										  {
											$la_alm_sfc2=$io_sql->obtener_datos($arr_alm_sfc2);
											$io_datastore5->data=$la_alm_sfc2;
											$totrow3=$io_datastore5->getRowCount("numtra");  
											
											for($li_n=1;$li_n<=$totrow3;$li_n++)
											{	
												$ls_codart=$io_datastore5->getValue("codart",$li_n);
												$ls_cantidad=$io_datastore5->getValue("cantidad",$li_n);
												
												$lb_valido9=false;
												$lb_valido9=$io_sfc->uf_sim_select_dt_transferencia_almacen($ls_codemp,$ls_numtra,$ls_codart);
												$lb_valido8=false;
												$lb_valido8=$io_sfc->uf_sim_select_transferencia_almacen($ls_codemp,$ls_numtra,$ls_fecemi,$ls_codalmori,$ls_codalmdes);
												
												if ((!$lb_valido9) and (!$lb_valido8)){
												
												$ls_sql_sfc3="DELETE FROM sfc_transferenciaalmacen WHERE numtra = '".$ls_numtra."' AND codalmori = '".$ls_codalmori."' AND codalmdes = '".$ls_codalmdes."' ";
												$arr_alm_sfc3=$io_sql->execute($ls_sql_sfc3);
												
												$ls_sql_sfc4="DELETE FROM sfc_dt_transferenciaalmacen WHERE numtra = '".$ls_numtra."' AND codart='".$ls_codart."' AND fecemi = '".$ls_fecemi."' ";
												$arr_alm_sfc4=$io_sql->execute($ls_sql_sfc4);
												
										$la_objectoalmacenes3[$li_x][1]="<input name=txtcodret".$li_x." type=text id=txtcodret".$li_x." class=sin-borde value='".$ls_numtra."' style= text-align:center size=15 readonly>";
									
										$la_objectoalmacenes3[$li_x][2]="<input name=txtdesret".$li_x." type=text id=txtdesret".$li_x." class=sin-borde value='".$ls_fecemi."' style= text-align:center size=8 readonly>";
									
										$la_objectoalmacenes3[$li_x][3]="<input name=txtmontoret".$li_x." type=text id=txtmontoret".$li_x." class=sin-borde value='".$ls_codalmori."' style= text-align:center size=8 readonly>"; 
										 
										$la_objectoalmacenes[$li_x][4]="<input name=txttotalret".$li_x." type=text id=txttotalret".$li_x." class=sin-borde value='".$ls_codalmdes."' style= text-align:center size=8 readonly>";
										
										$la_objectoalmacenes3[$li_x][5]="<input name=txttotalre".$li_x." type=text id=txttotalre".$li_x." class=sin-borde value='".$ls_codart."' style= text-align:center size=25 readonly>";    
										
										$la_objectoalmacenes3[$li_x][6]="<input name=txtproceso".$li_x." type=text id=txtproceso".$li_x." value='".$ls_proceso."' class=sin-borde style= text-align:center size=25 readonly>";
												
$ls_sql_resta2 =  "UPDATE sim_articuloalmacen SET existencia= (existencia - '". $ls_cantidad ."') WHERE codemp='" . $ls_codemp ."'".
					"   AND codart='" . $ls_codart ."'".
					"   AND codalm='" . $ls_codalmdes ."';";	
				
				
							
					/***********************************Archivo de Transferencia**********************************/
					$ls_sql_nombre="SELECT * FROM sim_almacen WHERE sim_almacen.codalm = '".$ls_codalmdes."'";
					$nombre_des=$io_sql->select($ls_sql_nombre);
					if($row=$io_sql->fetch_row($nombre_des))
							  {
								$la_nombre_des=$io_sql->obtener_datos($nombre_des);
								$io_datastore8->data=$la_nombre_des;
								$totrow8=$io_datastore8->getRowCount("codalm");
								for($li_d=1;$li_d<=$totrow8;$li_d++)
								{	
									$ls_nombre_des=$io_datastore8->getValue("nomfisalm",$li_d);
									$caracter='(';
									$posicion = strpos($ls_nombre_des,$caracter);
									if($posicion===false){
										$ls_nombre_des=$ls_nombre_des;
									}
									else{
										$ls_nombre_des=(substr($ls_nombre_des,0,$posicion));
									}
								}
							  } 				
					
							$ls_nomarchivo3="trans-".$ls_nombre_des;
							$io_archivoD->crear_archivo($ls_nomarchivo3);
							$io_archivoD->escribir_archivo($ls_sql_resta2);
							$io_archivoD->cerrar_archivo();
				 								
					/**********************************************************************************************/
					
$ls_sql_suma2 = "UPDATE sim_articuloalmacen SET existencia= (existencia + '". $ls_cantidad ."') WHERE codemp='" . $ls_codemp ."'".
				  "   AND codart='" . $ls_codart ."'".
				  "   AND codalm='" . $ls_codalmori ."';";				


					/**************************************Archivo de Transferencia*******************************/
			
					$ls_sql_nombre2="SELECT * FROM sim_almacen WHERE sim_almacen.codalm = '".$ls_codalmori."'";
					$nombre_ori=$io_sql->select($ls_sql_nombre2);
					if($row=$io_sql->fetch_row($nombre_ori))
							  {
								$la_nombre_ori=$io_sql->obtener_datos($nombre_ori);
								$io_datastore9->data=$la_nombre_ori;
								$totrow9=$io_datastore9->getRowCount("codalm");
								for($li_s=1;$li_s<=$totrow9;$li_s++)
								{	
									$ls_nombre_ori=$io_datastore9->getValue("nomfisalm",$li_s);
									$caracter='(';
									$posicion = strpos($ls_nombre_ori,$caracter);
									if($posicion===false){
										$ls_nombre_ori=$ls_nombre_ori;
									}
									else{
										$ls_nombre_ori=(substr($ls_nombre_ori,0,$posicion));
									}
								}
							  } 
					
							$ls_nomarchivo4="trans-".$ls_nombre_ori;
							$io_archivoO->crear_archivo($ls_nomarchivo4);
							$io_archivoO->escribir_archivo($ls_sql_suma2);
							$io_archivoO->cerrar_archivo();
				 		
					/**************************************************************************************************/
					
					
					
					if($li_filasalmacenes4<>""){
					
					//*****************************//  LECTURA ARCHIVO ARTICULOS_PTOSVENTAS  //*****************************//
						$ls_fecha=date('dmY');
	     			//$archartven=file("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/ARTICULOS_PTOVENTAS/transPRODUCTO.txt");
				$archartven=file("/var/www/sigesp_fac/sfc/transferencias/ARTICULOS_PTOVENTAS/transPRODUCTO.txt");

						$archivoproductos="";
						$lineas=count($archartven);
						for ($i=0;$i<$lineas;$i++)
						{
							$archivoproductos=$archivoproductos.$archartven[$i];
						}
						
						
					//****************************//  LECTURA ARCHIVO ARTICULOS_INVENTARIO  //*****************************//
 					//$archartinv=file("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/ARTICULOS_INVENTARIO/transARTICULO.txt");
					$archartinv=file("/var/www/sigesp_fac/sfc/transferencias/ARTICULOS_INVENTARIO/transARTICULO.txt");	

						$archivoinventario="";
						$lineas=count($archartinv);
						for ($i=0;$i<$lineas;$i++)
						{
							$archivoinventario=$archivoinventario.$archartinv[$i];
						}
					
										
					//********************************//  LECTURA ARCHIVO PROVEEDORES  //***************************************//
					//$archprov=file("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/PROVEEDORES/trans-PROVEEDORES.txt");
                      $archprov=file("/var/www/sigesp_fac/sfc/transferencias/PROVEEDORES/trans-PROVEEDORES.txt");

						$archivoproveedor="";
						$lineas=count($archprov);
						for ($i=0;$i<$lineas;$i++)
						{
							$archivoproveedor=$archivoproveedor.$archprov[$i];
						}
				
									
					//***************************//  LECTURA ARCHIVO ALMACEN DESTINO  //******************************//
				//$archalmdest=file("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/ALMACENDESTINO/trans-".$ls_nombre_des.".txt");
				$archalmdest=file("/var/www/sigesp_fac/sfc/transferencias/ALMACENDESTINO/trans-".$ls_nombre_des.".txt");

						$archivoalmacend="";
						$lineas=count($archalmdest);
						for ($i=0;$i<$lineas;$i++)
						{
							$archivoalmacend=$archivoalmacend.$archalmdest[$i];
						}
										
					//*******************************//  LECTURA ARCHIVO ALMACEN ORIGEN  //**************************//
				//$archalmori=file("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/ALMACENORIGEN/trans-".$ls_nombre_ori.".txt");
				$archalmori=file("/var/www/sigesp_fac/sfc/transferencias/ALMACENORIGEN/trans-".$ls_nombre_ori.".txt");

						$archivoalmaceno="";
						$lineas=count($archalmori);
						for ($i=0;$i<$lineas;$i++)
						{
							$archivoalmaceno=$archivoalmaceno.$archalmori[$i];
						}					
					
					/**************************************************************************************************/
					
					$ls_sql_deno="Select spg_unidadadministrativa.coduniadm, spg_unidadadministrativa.denuniadm FROM	spg_unidadadministrativa ";
												$arr_deno=$io_sql->select($ls_sql_deno);
												if($row=$io_sql->fetch_row($arr_deno))
												{
													
													$la_deno=$io_sql->obtener_datos($arr_deno);
													$io_datastore12->data=$la_deno;
													$totrow12=$io_datastore12->getRowCount("coduniadm");  
													
													for($li_d=1;$li_d<=$totrow12;$li_d++)
													{	  
														$ls_denuniad=$io_datastore12->getValue("denuniadm",$li_d);
														$ls_den=(substr($ls_denuniad,1,5));
														if($ls_den=="02-03"){
															$ls_denominacionB=(substr($ls_denuniad,31,20));
														}
						if(($ls_nombre_des<>$ls_denominacionB) and ($ls_nombre_ori<>$ls_denominacionB)){
							
							$cont=array("ALMACENDESTINO/trans-".$ls_nombre_des.".txt"=>$archivoalmacend);
							//createzip($cont,"C:/".$ls_nombre_des."(almacen)-".$ls_fecha.".zip ");
							createzip($cont,"/home/".$ls_nombre_des."(almacen)-".$ls_fecha.".zip ");
							
							$cont=array("ALMACENORIGEN/trans-".$ls_nombre_ori.".txt"=>$archivoalmaceno);
							//createzip($cont,"C:/".$ls_nombre_ori."(almacen)-".$ls_fecha.".zip ");
							createzip($cont,"/home/".$ls_nombre_ori."(almacen)-".$ls_fecha.".zip ");
						
						}
						else{
							//************//  CREACION DE ARCHIVO .ZIP  //************************************// 					
							if($ls_nombre_des==$ls_denominacionB){
								$cont=array(ORDENCOMPRA."/".$ls_nomarchivo.".txt"=>$ls_sql2." ".$ls_sql3."  ".$ls_sql4."  ".$ls_sql5." ",
								"ARTICULOS_PTOVENTAS/transPRODUCTO.txt"=>$archivoproductos,
								"ARTICULOS_INVENTARIO/transARTICULO.txt"=>$archivoinventario,
								"PROVEEDORES/trans-PROVEEDORES.txt"=>$archivoproveedor,
								"ALMACENDESTINO/trans-".$ls_nombre_des.".txt"=>$archivoalmacend);
								//createzip($cont,"C:/".$ls_nomarchivo."-".$ls_fecha.".zip ");
								createzip($cont,"/home/".$ls_nomarchivo."-".$ls_fecha.".zip ");

								$estatus="P";
	  							
							}
							//************//  CREACION DE ARCHIVO .ZIP  //************************************// 
							if($ls_nombre_ori==$ls_denominacionB){
								$cont=array(ORDENCOMPRA."/".$ls_nomarchivo.".txt"=>$ls_sql2." ".$ls_sql3."  ".$ls_sql4."  ".$ls_sql5." ",
								"ARTICULOS_PTOVENTAS/transPRODUCTO.txt"=>$archivoproductos,
								"ARTICULOS_INVENTARIO/transARTICULO.txt"=>$archivoinventario,
								"PROVEEDORES/trans-PROVEEDORES.txt"=>$archivoproveedor,
								"ALMACENORIGEN/trans-".$ls_nombre_ori.".txt"=>$archivoalmaceno);
								//createzip($cont,"C:/".$ls_nomarchivo."-".$ls_fecha.".zip ");
								createzip($cont,"/home/".$ls_nomarchivo."-".$ls_fecha.".zip ");
								$estatus="P";
	  							
							}
						}
													}
												 }
												 
							$cont=array("ARTICULOS_PTOVENTAS/transPRODUCTO.txt"=>$archivoproductos,
							"ARTICULOS_INVENTARIO/transARTICULO.txt"=>$archivoinventario,
							"PROVEEDORES/trans-PROVEEDORES.txt"=>$archivoproveedor);
							//createzip($cont,"C:/".$ls_fecha.".zip ");
							createzip($cont,"/home/".$ls_fecha.".zip ");						   
					}
					/**************************************************************************************************/
			$li_x++;
			$li_filasalmacenes3++;		
												} // if doble
											} // for li_n		
									
						$li_filasalmacenes3=$li_x;
						$la_objectoalmacenes3[$li_filasalmacenes3][1]="<input name=txtcodret".$li_filasalmacenes3." type=text id=txtcodret".$li_filasalmacenes3." class=sin-borde style= text-align:center size=15 readonly>";
				$la_objectoalmacenes3[$li_filasalmacenes3][2]="<input name=txtdesret".$li_filasalmacenes3." type=text id=txtdesret".$li_filasalmacenes3." class=sin-borde style= text-align:center size=8 readonly>";
				$la_objectoalmacenes3[$li_filasalmacenes3][3]="<input name=txtmontoret".$li_filasalmacenes3." type=text id=txtmontoret".$li_filasalmacenes3." class=sin-borde style= text-align:center size=8 readonly>";
				$la_objectoalmacenes3[$li_filasalmacenes3][4]="<input name=txttotalret".$li_filasalmacenes3." type=text id=txttotalret".$li_filasalmacenes3." class=sin-borde style= text-align:center size=8 readonly>";
				$la_objectoalmacenes3[$li_filasalmacenes3][5]="<input name=txttotalre".$li_filasalmacenes3." type=text id=txttotalre".$li_filasalmacenes3." class=sin-borde style= text-align:center size=25 readonly>";
				$la_objectoalmacenes3[$li_filasalmacenes3][6]="<input name=txtproceso".$li_filasalmacenes3." type=text id=txtproceso".$li_filasalmacenes3." class=sin-borde style= text-align:center size=25 readonly>";								
											} // if $row=$io_sql->fetch_row($arr_alm_sfc2)
										}	// if $arr_alm_sfc2==true
							
								} // for li_j
						
						 }	// if $row=$io_sql->fetch_row($arr_alm_sfc)
					 
					} // else
			
				}else{	
					
						$ls_cadena="SELECT * FROM sim_transferencia WHERE sim_transferencia.codemp = '".$ls_codemp."'";
						$arr_almacenes=$io_sql->select($ls_cadena);						
						
						if($arr_almacenes==false)
						{
							$is_msg->message("No hay Transferencia Entre Almacenes");							
						}
						else
						{						
							if($row=$io_sql->fetch_row($arr_almacenes))
							  {
								$la_almacenes=$io_sql->obtener_datos($arr_almacenes);
								$io_datastore6->data=$la_almacenes;
								$li_p=1;
								$li_filasalmacenes3=1;
								$ls_proceso="Transferencia";
								
								$ls_fecha=date('dmY');
								$totrow1=$io_datastore6->getRowCount("numtra");  
								
								for($li_i=1;$li_i<=$totrow1;$li_i++)
								{	
							
									$ls_codemp=$io_datastore6->getValue("codemp",$li_i);
									$ls_numtra=$io_datastore6->getValue("numtra",$li_i);
									$ls_fecemi=$io_datastore6->getValue("fecemi",$li_i);
									$ls_codalmori=$io_datastore6->getValue("codalmori",$li_i);
									$ls_codalmdes=$io_datastore6->getValue("codalmdes",$li_i);
									
									
									$ls_cadena2="SELECT * FROM sim_dt_transferencia WHERE numtra = '".$ls_numtra."' ";
									$arr_almacenes2=$io_sql->select($ls_cadena2);
									if($arr_almacenes2==true){
										if($row=$io_sql->fetch_row($arr_almacenes2))
										  {
											$la_almacenes2=$io_sql->obtener_datos($arr_almacenes2);
											$io_datastore7->data=$la_almacenes2;
											$totrow=$io_datastore7->getRowCount("numtra");  
											
											for($li_z=1;$li_z<=$totrow;$li_z++)
											{	
												$ls_codart=$io_datastore7->getValue("codart",$li_z);
												$ls_cantidad=$io_datastore7->getValue("cantidad",$li_z);
												
												$lb_valido=false;
												$lb_valido=$io_sfc->uf_sfc_select_dt_transferencia_almacen($ls_codemp,$ls_numtra,$ls_codart);
												if (!$lb_valido){	
													$lb_valido_transf=false;
													$lb_valido_transf=$io_sfc->uf_sfc_insert_dt_transferencia_almacen($ls_codemp,$ls_numtra,$ls_fecemi,$ls_codart,$ls_cantidad);
													$lb_valido2=false;
													$lb_valido2=$io_sfc->uf_sfc_select_transferencia_almacen($ls_codemp,$ls_numtra,$ls_fecemi,$ls_codalmori,$ls_codalmdes);
													if ((!$lb_valido2) and (!$lb_valido)){
														$lb_valido_transf2=false;
														$lb_valido_transf2=$io_sfc->uf_sfc_insert_transferencia_almacen($ls_codemp,&$ls_numtra,$ls_fecemi,$ls_codalmori,$ls_codalmdes);
													}	//if lb_valido2
										
										$la_objectoalmacenes3[$li_p][1]="<input name=txtcodret".$li_p." type=text id=txtcodret".$li_p." class=sin-borde value='".$ls_numtra."' style= text-align:center size=15 readonly>";
										
										$la_objectoalmacenes3[$li_p][2]="<input name=txtdesret".$li_p." type=text id=txtdesret".$li_p." class=sin-borde value='".$ls_fecemi."' style= text-align:center size=8 readonly>";
										
										$la_objectoalmacenes3[$li_p][3]="<input name=txtmontoret".$li_p." type=text id=txtmontoret".$li_p." class=sin-borde value='".$ls_codalmori."' style= text-align:center size=8 readonly>"; 
										
										$la_objectoalmacenes3[$li_p][4]="<input name=txttotalret".$li_p." type=text id=txttotalret".$li_p." class=sin-borde value='".$ls_codalmdes."' style= text-align:center size=8 readonly>";
										
										$la_objectoalmacenes3[$li_p][5]="<input name=txttotalre".$li_p." type=text id=txttotalre".$li_p." class=sin-borde value='".$ls_codart."' style= text-align:center size=25 readonly>";
										
										$la_objectoalmacenes3[$li_p][6]="<input name=txtproceso".$li_p." type=text id=txtproceso".$li_p." value='".$ls_proceso."' class=sin-borde style= text-align:center size=25 readonly>";
									
										  
$ls_sql_resta =  "UPDATE sim_articuloalmacen SET existencia= (existencia - '". $ls_cantidad ."') WHERE codemp='" . $ls_codemp ."'".
					"   AND codart='" . $ls_codart ."'".
					"   AND codalm='" . $ls_codalmori ."';";	
							
				  /****************************************Archivo de Transferencia*************************************/
					$ls_sql_nombre3="SELECT * FROM sim_almacen WHERE sim_almacen.codalm = '".$ls_codalmori."'";
					$nombre_ori=$io_sql->select($ls_sql_nombre3);
					if($row=$io_sql->fetch_row($nombre_ori))
							  {
								$la_nombre_ori=$io_sql->obtener_datos($nombre_ori);
								$io_datastore10->data=$la_nombre_ori;
								$totrow10=$io_datastore10->getRowCount("codalm");
								for($li_f=1;$li_f<=$totrow10;$li_f++)
								{	
							
									$ls_nombre_ori=$io_datastore10->getValue("nomfisalm",$li_f);
									$caracter='(';
									$posicion = strpos($ls_nombre_ori,$caracter);
									if($posicion===false){
										$ls_nombre_ori=$ls_nombre_ori;
									}
									else{
										$ls_nombre_ori=(substr($ls_nombre_ori,0,$posicion));
									}
								}
							  } 
					
							$ls_nomarchivo5="trans-".$ls_nombre_ori;
							$io_archivoO->crear_archivo($ls_nomarchivo5);
							$io_archivoO->escribir_archivo($ls_sql_resta);
							$io_archivoO->cerrar_archivo();
												
					/**************************************************************************************************/
					
$ls_sql_suma= "UPDATE sim_articuloalmacen SET existencia= (existencia + '". $ls_cantidad ."') WHERE codemp='" . $ls_codemp ."'".
				  "   AND codart='" . $ls_codart ."'".
				  "   AND codalm='" . $ls_codalmdes ."';";				

				   /****************************************Archivo de Transferencia***********************************/
			
					$ls_sql_nombre4="SELECT * FROM sim_almacen WHERE sim_almacen.codalm = '".$ls_codalmdes."'";
					$nombre_des=$io_sql->select($ls_sql_nombre4);
					if($row=$io_sql->fetch_row($nombre_des))
							  {
								$la_nombre_des=$io_sql->obtener_datos($nombre_des);
								$io_datastore11->data=$la_nombre_des;
								$totrow11=$io_datastore11->getRowCount("codalm");
								for($li_t=1;$li_t<=$totrow11;$li_t++)
								{	
									$ls_nombre_des=$io_datastore11->getValue("nomfisalm",$li_t);
									$caracter='(';
									$posicion = strpos($ls_nombre_des,$caracter);
									if($posicion===false){
										$ls_nombre_des=$ls_nombre_des;
									}
									else{
										$ls_nombre_des=(substr($ls_nombre_des,0,$posicion));
									}	
								}
							  } 	
						
							$ls_nomarchivo2="trans-".$ls_nombre_des;
							$io_archivoD->crear_archivo($ls_nomarchivo2);
							$io_archivoD->escribir_archivo($ls_sql_suma);
							$io_archivoD->cerrar_archivo();
				 		
					/**************************************************************************************************/
					if($li_filasalmacenes4<>""){
					//***********************//  LECTURA ARCHIVO ARTICULOS_PTOSVENTAS  //***********************************//
						$ls_fecha=date('dmY');
				//$archartven=file("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/ARTICULOS_PTOVENTAS/transPRODUCTO.txt");
				$archartven=file("/var/www/sigesp_fac/sfc/transferencias/ARTICULOS_PTOVENTAS/transPRODUCTO.txt");
						$archivoproductos="";
						$lineas=count($archartven);
						for ($i=0;$i<$lineas;$i++)
						{
							$archivoproductos=$archivoproductos.$archartven[$i];
						}
						
						
					//************************//  LECTURA ARCHIVO ARTICULOS_INVENTARIO  //***********************************//
			//$archartinv=file("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/ARTICULOS_INVENTARIO/transARTICULO.txt");
			$archartinv=file("/var/www/sigesp_fac/sfc/transferencias/ARTICULOS_INVENTARIO/transARTICULO.txt");
						$archivoinventario="";
						$lineas=count($archartinv);
						for ($i=0;$i<$lineas;$i++)
						{
							$archivoinventario=$archivoinventario.$archartinv[$i];
						}
					
										
					//********************************//  LECTURA ARCHIVO PROVEEDORES  //***************************************//
				//$archprov=file("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/PROVEEDORES/trans-PROVEEDORES.txt");
				$archprov=file("/var/www/sigesp_fac/sfc/transferencias/PROVEEDORES/trans-PROVEEDORES.txt");
						$archivoproveedor="";
						$lineas=count($archprov);
						for ($i=0;$i<$lineas;$i++)
						{
							$archivoproveedor=$archivoproveedor.$archprov[$i];
						}
				
									
					//***************************//  LECTURA ARCHIVO ALMACEN DESTINO  //******************************//
				//$archalmdest=file("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/ALMACENDESTINO/trans-".$ls_nombre_des.".txt");
				$archalmdest=file("/var/www/sigesp_fac/sfc/transferencias/ALMACENDESTINO/trans-".$ls_nombre_des.".txt");
						$archivoalmacend="";
						$lineas=count($archalmdest);
						for ($i=0;$i<$lineas;$i++)
						{
							$archivoalmacend=$archivoalmacend.$archalmdest[$i];
						}
										
					//*******************************//  LECTURA ARCHIVO ALMACEN ORIGEN  //**************************//
				//$archalmori=file("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/ALMACENORIGEN/trans-".$ls_nombre_ori.".txt");
				$archalmori=file("/var/www/sigesp_fac/sfc/transferencias/ALMACENORIGEN/trans-".$ls_nombre_ori.".txt");
						$archivoalmaceno="";
						$lineas=count($archalmori);
						for ($i=0;$i<$lineas;$i++)
						{
							$archivoalmaceno=$archivoalmaceno.$archalmori[$i];
						}					
					
					/**************************************************************************************************/
					
					$ls_sql_deno="Select spg_unidadadministrativa.coduniadm, spg_unidadadministrativa.denuniadm FROM	spg_unidadadministrativa ";
												$arr_deno=$io_sql->select($ls_sql_deno);
												if($row=$io_sql->fetch_row($arr_deno))
												{
													
													$la_deno=$io_sql->obtener_datos($arr_deno);
													$io_datastore12->data=$la_deno;
													$totrow12=$io_datastore12->getRowCount("coduniadm");  
													
													for($li_d=1;$li_d<=$totrow12;$li_d++)
													{	  
														$ls_denuniad=$io_datastore12->getValue("denuniadm",$li_d);
														$ls_den=(substr($ls_denuniad,1,5));
														if($ls_den=="02-03"){
															$ls_denominacionA=(substr($ls_denuniad,31,20));
														}
												
						if(($ls_nombre_des<>$ls_denominacionA) and ($ls_nombre_ori<>$ls_denominacionA)){
							
							$cont=array("ALMACENDESTINO/trans-".$ls_nombre_des.".txt"=>$archivoalmacend);
							//createzip($cont,"C:/".$ls_nombre_des."(almacen)-".$ls_fecha.".zip ");
							createzip($cont,"/home/".$ls_nombre_des."(almacen)-".$ls_fecha.".zip ");
							
							$cont=array("ALMACENORIGEN/trans-".$ls_nombre_ori.".txt"=>$archivoalmaceno);
							//createzip($cont,"C:/".$ls_nombre_ori."(almacen)-".$ls_fecha.".zip ");
							createzip($cont,"/home/".$ls_nombre_ori."(almacen)-".$ls_fecha.".zip ");
						}
						else{
							//print $ls_nombre_des."-*-*";
							//************//  CREACION DE ARCHIVO .ZIP  //************************************// 					
							if($ls_nombre_des==$ls_denominacionA){
								$cont=array(ORDENCOMPRA."/".$ls_nomarchivo.".txt"=>$ls_sql2." ".$ls_sql3."  ".$ls_sql4."  ".$ls_sql5." ",
								"ARTICULOS_PTOVENTAS/transPRODUCTO.txt"=>$archivoproductos,
								"ARTICULOS_INVENTARIO/transARTICULO.txt"=>$archivoinventario,
								"PROVEEDORES/trans-PROVEEDORES.txt"=>$archivoproveedor,
								"ALMACENDESTINO/trans-".$ls_nombre_des.".txt"=>$archivoalmacend);
							//	createzip($cont,"C:/".$ls_nomarchivo."-".$ls_fecha.".zip ");
								createzip($cont,"/home/".$ls_nomarchivo."-".$ls_fecha.".zip ");
								$estatus="P";
							}
							//************//  CREACION DE ARCHIVO .ZIP  //************************************// 
							if($ls_nombre_ori==$ls_denominacionA){
								$cont=array(ORDENCOMPRA."/".$ls_nomarchivo.".txt"=>$ls_sql2." ".$ls_sql3."  ".$ls_sql4."  ".$ls_sql5." ",
								"ARTICULOS_PTOVENTAS/transPRODUCTO.txt"=>$archivoproductos,
								"ARTICULOS_INVENTARIO/transARTICULO.txt"=>$archivoinventario,
								"PROVEEDORES/trans-PROVEEDORES.txt"=>$archivoproveedor,
								"ALMACENORIGEN/trans-".$ls_nombre_ori.".txt"=>$archivoalmaceno);
								//createzip($cont,"C:/".$ls_nomarchivo."-".$ls_fecha.".zip ");
								createzip($cont,"/home/".$ls_nomarchivo."-".$ls_fecha.".zip ");
								
								$estatus="P";
							}
						}
													}
												 }
							
							$cont=array("ARTICULOS_PTOVENTAS/transPRODUCTO.txt"=>$archivoproductos,
							"ARTICULOS_INVENTARIO/transARTICULO.txt"=>$archivoinventario,
							"PROVEEDORES/trans-PROVEEDORES.txt"=>$archivoproveedor);
							//createzip($cont,"C:/".$ls_fecha.".zip ");
							createzip($cont,"/home/".$ls_fecha.".zip ");							   
					}
					/**************************************************************************************************/

			$li_p++;
			$li_filasalmacenes3++;		
													} // if !$lb_valido
												} // for li_z
									
						$li_filasalmacenes3=$li_p;
						$la_objectoalmacenes3[$li_filasalmacenes3][1]="<input name=txtcodret".$li_filasalmacenes3." type=text id=txtcodret".$li_filasalmacenes3." class=sin-borde style= text-align:center size=15 readonly>";
						$la_objectoalmacenes3[$li_filasalmacenes3][2]="<input name=txtdesret".$li_filasalmacenes3." type=text id=txtdesret".$li_filasalmacenes3." class=sin-borde style= text-align:center size=8 readonly>";
				$la_objectoalmacenes3[$li_filasalmacenes3][3]="<input name=txtmontoret".$li_filasalmacenes3." type=text id=txtmontoret".$li_filasalmacenes3." class=sin-borde style= text-align:center size=8 readonly>";
				$la_objectoalmacenes3[$li_filasalmacenes3][4]="<input name=txttotalret".$li_filasalmacenes3." type=text id=txttotalret".$li_filasalmacenes3." class=sin-borde style= text-align:center size=8 readonly>";
				$la_objectoalmacenes3[$li_filasalmacenes3][5]="<input name=txttotalre".$li_filasalmacenes3." type=text id=txttotalre".$li_filasalmacenes3." class=sin-borde style= text-align:center size=25 readonly>";
				$la_objectoalmacenes3[$li_filasalmacenes3][6]="<input name=txtproceso".$li_filasalmacenes3." type=text id=txtproceso".$li_filasalmacenes3." class=sin-borde style= text-align:center size=25 readonly>";
										
										
											} // if almacen2
										}	// if
							
								} // for li_i
						
						 }	// if
			
					} // else
				}
	
	//////////******************  PROCESAR ORDENES DE COMPRA  ***********************////////////
    //*****************************************************************************************//
	$ls_cadenat="Select sfc_transferenciaorden.* From sfc_transferenciaorden";
	$arr_transf=$io_sql->select($ls_cadenat);

	$row=$io_sql->fetch_row($arr_transf);
	
	
	if($row==0)
	{
		$ls_sql1="Select spg_unidadadministrativa.coduniadm,spg_unidadadministrativa.denuniadm FROM 
						spg_unidadadministrativa ";
						
						 $arr_orduniadm=$io_sql->select($ls_sql1);
						
							if($arr_orduniadm==false&&($io_sql->message!=""))
							{
								$is_msg->message("No hay Ordenes Contabilizadas, No existen registros en sfc_tranforden");
							}
							else
							{	
											
								if($row=$io_sql->fetch_row($arr_orduniadm))
								  {
									
									$la_orduniadm=$io_sql->obtener_datos($arr_orduniadm);
									$io_datastore1->data=$la_orduniadm;
									$li_p=1;
									$totrow1=$io_datastore1->getRowCount("coduniadm");  
									
									for($li_j=1;$li_j<=$totrow1;$li_j++)
									{	  
										
										$ls_denuniad=$io_datastore1->getValue("denuniadm",$li_j);
										$ls_den=(substr($ls_denuniad,1,5));
										$ls_denominacionu=(substr($ls_denuniad,31,20));
									
									 if ($ls_den=="02-03")
									{
										$ls_coduniad=$io_datastore1->getValue("coduniadm",$li_j);					
										
									
										$ls_cadena3="SELECT soc_ordencompra.* FROM soc_ordencompra WHERE 
										soc_ordencompra.coduniadm='".$ls_coduniad."' AND soc_ordencompra.codemp = '".$ls_codemp."' 	
										AND soc_ordencompra.estcom='2' AND soc_ordencompra.estcondat='B' AND soc_ordencompra.fecordcom='".$ls_fechahoy."'"; 
										
							
										$arr_ordcom=$io_sql->select($ls_cadena3);
						
										if($arr_ordcom==false)
										{
											$is_msg->message("No hay Ordenes Contabilizadas");
											
										}
										else
										{						
											
											if($row=$io_sql->fetch_row($arr_ordcom))
											  {
												$la_ordenes=$io_sql->obtener_datos($arr_ordcom);
												$io_datastore->data=$la_ordenes;
												$totrow=$io_datastore->getRowCount("codemp");  
											
												for($li_i=1;$li_i<=$totrow;$li_i++)
												{	
											
													$ls_numordcomp=$io_datastore->getValue("numordcom",$li_i);
													$ls_estcondat=$io_datastore->getValue("estcondat",$li_i);
													$ls_codpro=$io_datastore->getValue("cod_pro",$li_i);
													$ls_codmon=$io_datastore->getValue("codmon",$li_i);   
													$ls_codfuefin=$io_datastore->getValue("codfuefin",$li_i);  
													$ls_codtipmod=$io_datastore->getValue("codtipmod",$li_i);    
													$ls_fecordcom=$io_datastore->getValue("fecordcom",$li_i);   
													$ls_estsegcom=$io_datastore->getValue("estsegcom",$li_i);   
													$ls_porsegcom=$io_datastore->getValue("porsegcom",$li_i);   
													$ls_monsegcom=$io_datastore->getValue("monsegcom",$li_i);   
													$ls_forpagcom=$io_datastore->getValue("forpagcom",$li_i);   
													$ls_estcom=$io_datastore->getValue("estcom",$li_i);   
													$ls_diaplacom=$io_datastore->getValue("diaplacom",$li_i);   
													$ls_concom=$io_datastore->getValue("concom",$li_i);   
													$ls_obscom=$io_datastore->getValue("obscom",$li_i);   
													$ls_monsubtotbie=$io_datastore->getValue("monsubtotbie",$li_i);
													$ls_monsubtotser=$io_datastore->getValue("monsubtotser",$li_i);
													$ls_monsubtot=$io_datastore->getValue("monsubtot",$li_i);   
													$ls_monbasimp=$io_datastore->getValue("monbasimp",$li_i);
													$ls_monimp=$io_datastore->getValue("monimp",$li_i);
	 												$ls_mondes=$io_datastore->getValue("mondes",$li_i);
													$ls_montot=$io_datastore->getValue("montot",$li_i);
													$ls_estpenalm=$io_datastore->getValue("estpenalm",$li_i);
													$ls_codpai=$io_datastore->getValue("codpai",$li_i);
													$ls_codest=$io_datastore->getValue("codest",$li_i);
													$ls_codmun=$io_datastore->getValue("codmun",$li_i);
													$ls_codpar=$io_datastore->getValue("codpar",$li_i);
													$ls_lugentnomdep=$io_datastore->getValue("lugentnomdep",$li_i);
													$ls_lugentdir=$io_datastore->getValue("lugentdir",$li_i);
													$ls_monant=$io_datastore->getValue("monant",$li_i);
													$ls_estlugcom=$io_datastore->getValue("estlugcom",$li_i);
													$ls_tascamordcom=$io_datastore->getValue("tascamordcom",$li_i);
													$ls_montotdiv=$io_datastore->getValue("montotdiv",$li_i);
													$ls_estapro=$io_datastore->getValue("estapro",$li_i);
													$ls_fecaprord=$io_datastore->getValue("fecaprord",$li_i);
													$ls_codusuapr=$io_datastore->getValue("codusuapr",$li_i);
													$ls_numpolcom=$io_datastore->getValue("numpolcom",$li_i);
													$ls_coduniadm=$io_datastore->getValue("coduniadm",$li_i);
													$ls_obsordcom=$io_datastore->getValue("obsordcom",$li_i);
													$ls_fecent=$io_datastore->getValue("fecent",$li_i);
													$ls_fechaconta=$io_datastore->getValue("fechaconta",$li_i);
													$ls_fechaanula=$io_datastore->getValue("fechaanula",$li_i);
													
													
													$ls_sqltransford=" INSERT INTO sfc_transferenciaorden".
													 " (numordcom, estcom,fecordcom)".
													 "  VALUES ".
													 " ('".$ls_numordcomp."','".$ls_estcom."','".$ls_fecordcom."')";                                                                       
											 
													 $rs_data=$io_sql->execute($ls_sqltransford);              
													 if ($rs_data===false)
														{				 
																	
															$is_msg->message("No se pudo Realizar el Registro de la
															 Transferencia ");	
														}			
													
																										
													$ls_sql2= " INSERT INTO soc_ordencompra ".
										   " (codemp, numordcom,estcondat,cod_pro,codmon,codfuefin,codtipmod,fecordcom,estsegcom,porsegcom,monsegcom,forpagcom,".
										   " estcom, diaplacom, concom, obscom, monsubtotbie, monsubtotser, monsubtot,monbasimp,monimp,mondes,montot,estpenalm,".
										   "codpai,codest,codmun,codpar,lugentnomdep,lugentdir,monant,estlugcom,tascamordcom,montotdiv,estapro, fecaprord,".
										   "codusuapr,numpolcom,coduniadm,obsordcom,fecent,fechaconta,fechaanula)".
										   " VALUES                                                                                     ".
										   " ('".$ls_codemp."','".$ls_numordcomp."','".$ls_estcondat."','".$ls_codpro."','".$ls_codmon."',".
										   "  '".$ls_codfuefin."','".$ls_codtipmod."','".$ls_fecordcom."','".$ls_estsegcom."','".$ls_porsegcom."',".
										   "  '".$ls_monsegcom."','".$ls_forpagcom."','".$ls_estcom."','".$ls_diaplacom."','".$ls_concom."',".
										   "  '".$ls_obscom."','".$ls_monsubtotbie."','".$ls_monsubtotser."','".$ls_monsubtot."','".$ls_monbasimp."',".
										   "  '".$ls_monimp."','".$ls_mondes."','".$ls_montot."','".$ls_estpenalm."','".$ls_codpai."','".$ls_codest."',".			
										   "  '".$ls_codmun."','".$ls_codpar."','".$ls_lugentnomdep."','".$ls_lugentdir."','".$ls_monant."','".$ls_estlugcom.",".		
										   "  '".$ls_tascamordcom."','".$ls_montotdiv."', '".$ls_estapro."','".$ls_fecaprord."','".$ls_codusuapr."',".
										   "  '".$ls_numpolcom."',".$ls_coduniadm.",'".$ls_obsordcom."','".$ls_fecent."','".$ls_fechaconta."','".$ls_fechaanula."');"; 
						
				
																
											 /***************************Archivo de Transferencia***************************/
											
												$ls_nomarchivo=$ls_denominacionu;
												$io_archivo->crear_archivo($ls_nomarchivo);
												$io_archivo->escribir_archivo($ls_sql2);
												$io_archivo->cerrar_archivo();
												
																							
											 /*****************************************************************************/
																
													$ls_cadenadt="Select * from soc_dt_bienes where soc_dt_bienes.numordcom='".
													$ls_numordcomp."' AND soc_dt_bienes.codemp='".$ls_codemp."' ";
											
													$arr_orddt=$io_sql->select($ls_cadenadt);
						
													if($arr_orddt==false&&($io_sql->message!=""))
													{
														$is_msg->message("La Orden no tiene Bienes Asociados");
													}
													else
													{						
														if($row=$io_sql->fetch_row($arr_orddt))
														  {
															$la_orddt=$io_sql->obtener_datos($arr_orddt);
															$io_datastore2->data=$la_orddt;
															$totdt=$io_datastore2->getRowCount("numordcom");  
														
															for($li_s=1;$li_s<=$totdt;$li_s++)
															{	
																$ls_numorddt=$io_datastore2->getValue("numordcom",$li_s);
																$ls_estcondatdt=$io_datastore2->getValue("estcondat",$li_s);
																$ls_codartdt=$io_datastore2->getValue("codart",$li_s);
																$ls_unidaddt=$io_datastore2->getValue("unidad",$li_s);   
																$ld_canartdt=$io_datastore2->getValue("canart",$li_s);  
																$ld_penartdt=$io_datastore2->getValue("penart",$li_s);  
																$ld_preuniartdt=$io_datastore2->getValue("preuniart",$li_s);
																$ld_monsubartdt=$io_datastore2->getValue("monsubart",$li_s);
																$ld_montotartdt=$io_datastore2->getValue("montotart",$li_s);
																$li_ordendt=$io_datastore2->getValue("orden",$li_s);   
															
																			
				$ls_sql3="INSERT INTO soc_dt_bienes (codemp, numordcom, estcondat, codart, unidad, canart, penart, ".
				 "preuniart, monsubart, montotart, orden)  VALUES ('".$ls_codemp."','".$ls_numorddt."','".$ls_estcondatdt."','".
				 $ls_codartdt."', '".$ls_unidaddt."',".$ld_canartdt.",".$ld_penartdt.",".$ld_preuniartdt.", ".$ld_monsubartdt.", ".					
				 $ld_montotartdt.",".$li_ordendt.");"; 
															
															/*******************Archivo de Transferencia******************/
							
																$ls_nomarchivo=$ls_denominacionu;
																$io_archivo->crear_archivo($ls_nomarchivo);
																$io_archivo->escribir_archivo($ls_sql3);
																$io_archivo->cerrar_archivo();
															   
															  
															  /*******************************************************/
															
																								
															}//for dt_bienes
															
														}//if row arr_dtbienes
													}//else arrorddt	
										$ls_estatus="Procesada";					
													
													$la_objectordenes2[$li_p][1]="<input name=txtcodret".$li_p." type=text id=txtcodret".$li_p." class=sin-borde value='".$ls_numordcomp."' style= text-align:center size=15 readonly>";
													
													$la_objectordenes2[$li_p][2]="<input name=txtdesret".$li_p." type=text id=txtdesret".$li_p." class=sin-borde value='".$ls_fecordcom."' style= text-align:center size=8 readonly>";
													
													$la_objectordenes2[$li_p][3]="<input name=txtmontoret".$li_p." type=text id=txtmontoret".$li_p." class=sin-borde value='".$ls_forpagcom."' style= text-align:center size=10 readonly>"; 
														 
													$la_objectordenes2[$li_p][4]="<input name=txttotalret".$li_p." type=text id=txttotalret".$li_p." class=sin-borde value='".$ls_montot."' style= text-align:center size=12 readonly>";  
													
													$la_objectordenes2[$li_p][5]="<input name=txtestatus".$li_p." type=text id=txtestatus".$li_p." class=sin-borde value='".$ls_estatus."' style= text-align:center size=12 readonly>"; 
											
				
							$li_p++;		
							$li_filasordenes2++;			
								
										}//for arr_ordcom
										$li_filasordenes2=$li_p;
										$la_objectordenes2[$li_filasordenes2][1]="<input name=txtcodret".$li_filasordenes2." type=text id=txtcodret".$li_filasordenes2." class=sin-borde style= text-align:center size=15 readonly>";
								$la_objectordenes2[$li_filasordenes2][2]="<input name=txtdesret".$li_filasordenes2." type=text id=txtdesret".$li_filasordenes2." class=sin-borde style= text-align:center size=8 readonly>";
								$la_objectordenes2[$li_filasordenes2][3]="<input name=txtmontoret".$li_filasordenes2." type=text id=txtmontoret".$li_filasordenes2." class=sin-borde style= text-align:center size=10 ><input name=formula".$li_filasordenes2." type=hidden id=formula".$li_filasordenes2.">";
								$la_objectordenes2[$li_filasordenes2][4]="<input name=txttotalret".$li_filasordenes2." type=text id=txttotalret".$li_filasordenes2." class=sin-borde style= text-align:center size=12 readonly>";
								$la_objectordenes2[$li_filasordenes2][5]="<input name=txtestatus".$li_filasordenes2." type=text id=txtestatus".$li_filasordenes2." class=sin-borde style= text-align:center size=12 readonly>";
									 }	//if row arr_ordcom
							
								}//else arr_ordcom


					if($li_filasordenes<>""){
					
	//********************************//  LECTURA ARCHIVO ARTICULOS_PTOSVENTAS  //***********************************//
						$ls_fecha=date('dmY');
					//$archartven=file("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/ARTICULOS_PTOVENTAS/trans-PRODUCTO.txt");
					$archartven=file("/var/www/sigesp_fac/sfc/transferencias/ARTICULOS_PTOVENTAS/transPRODUCTO.txt");
					
						$archivoproductos="";
						$lineas=count($archartven);
						for ($i=0;$i<$lineas;$i++)
						{
							$archivoproductos=$archivoproductos.$archartven[$i];
						}
						
		
	//********************************//  LECTURA ARCHIVO ARTICULOS_INVENTARIO  //***********************************//
					//$archartinv=file("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/ARTICULOS_INVENTARIO/transARTICULO.txt");
					$archartinv=file("/var/www/sigesp_fac/sfc/transferencias/ARTICULOS_INVENTARIO/transARTICULO.txt");
						$archivoinventario="";
						$lineas=count($archartinv);
						for ($i=0;$i<$lineas;$i++)
						{
							$archivoinventario=$archivoinventario.$archartinv[$i];
						}
	
						
	//********************************//  LECTURA ARCHIVO PROVEEDORES  //***************************************//
						//$archprov=file("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/PROVEEDORES/trans-PROVEEDORES.txt");
						$archprov=file("/var/www/sigesp_fac/sfc/transferencias/PROVEEDORES/trans-PROVEEDORES.txt");
						$archivoproveedor="";
						$lineas=count($archprov);
						for ($i=0;$i<$lineas;$i++)
						{
							$archivoproveedor=$archivoproveedor.$archprov[$i];
						}
					
					
//**************************//  CREACION DE ARCHIVO .ZIP  //****************************************************// 					
						if(($estatus=="NP") and ($ls_nombre_des<>$ls_nomarchivo) and ($ls_nombre_ori<>$ls_nomarchivo)){
							$cont=array(ORDENCOMPRA."/".$ls_nomarchivo.".txt"=>$ls_sql2." ".$ls_sql3."  ".$ls_sql4."  ".$ls_sql5." ",
							"ARTICULOS_PTOVENTAS/transPRODUCTO.txt"=>$archivoproductos,
							"ARTICULOS_INVENTARIO/transARTICULO.txt"=>$archivoinventario,
							"PROVEEDORES/trans-PROVEEDORES.txt"=>$archivoproveedor);
							//createzip($cont,"C:/".$ls_nomarchivo."-".$ls_fecha.".zip ");
							createzip($cont,"/home/".$ls_nomarchivo."-".$ls_fecha.".zip ");
						}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
						
						$cont=array("ARTICULOS_PTOVENTAS/transPRODUCTO.txt"=>$archivoproductos,
						"ARTICULOS_INVENTARIO/transARTICULO.txt"=>$archivoinventario,
						"PROVEEDORES/trans-PROVEEDORES.txt"=>$archivoproveedor);
						//createzip($cont,"C:/".$ls_fecha.".zip ");
						createzip($cont,"/home/".$ls_fecha.".zip ");
					
					}	
						}//if ls_den='02-03'
						
						
	
						
						}//for arr_ordenes1=spg_unidadadministrativa
					
					
					}//if arr_ordenes1=spg_unidadadministrativa
				}//else	arr_ordenes1=spg_unidadadministrativa
		
		//$is_msg->message("Ya se Realizaron las Transferencias del d�a");
	
		
	}
	else//if no esta vacia sfc_tranforden
	{		 
		
		$ls_sql="Select spg_unidadadministrativa.coduniadm,spg_unidadadministrativa.denuniadm FROM 
		spg_unidadadministrativa ";
						
						 $arr_ordenes1=$io_sql->select($ls_sql);
						
							if($arr_ordenes1==false)
							{
								$is_msg->message("La Unidad Administrativa no tiene ordenes contabilizadas");
							}
							else
							{						
								if($row=$io_sql->fetch_row($arr_ordenes1))
								  {
									
									$la_ordenes1=$io_sql->obtener_datos($arr_ordenes1);
									$io_datastore2->data=$la_ordenes1;
									$li_p=1;
									$totrow1=$io_datastore2->getRowCount("coduniadm");  
									
								
									for($li_j=1;$li_j<=$totrow1;$li_j++)
									{	  
										
										$ls_denuniad=$io_datastore2->getValue("denuniadm",$li_j);
										$ls_den=(substr($ls_denuniad,1,5));
										$ls_denominacionu=(substr($ls_denuniad,31,20));
									
									    if ($ls_den=="02-03")
										{
										$ls_coduniad=$io_datastore2->getValue("coduniadm",$li_j);					
								
										$ls_cadena3="SELECT soc_ordencompra.* FROM soc_ordencompra WHERE 
										soc_ordencompra.coduniadm='".$ls_coduniad."' AND soc_ordencompra.codemp = '".$ls_codemp."' 	AND soc_ordencompra.numordcom not in (select numordcom from sfc_transferenciaorden ) AND soc_ordencompra.estcom='2' AND soc_ordencompra.estcondat='B' AND  soc_ordencompra.fecordcom='".$ls_fechahoy."' ";
									
										$arr_ordcom=$io_sql->select($ls_cadena3);
						
										if($arr_ordcom==false&&($io_sql->message!=""))
										{
											$is_msg->message("La orden de compra ya fue generada para transferencia");
											
										}
										else
										{						
											if($row=$io_sql->fetch_row($arr_ordcom))
											  {
												
												$la_ordcom=$io_sql->obtener_datos($arr_ordcom);
												$io_datastore->data=$la_ordcom;
												$totrow=$io_datastore->getRowCount("numordcom");  

												for($li_i=1;$li_i<=$totrow;$li_i++)
												{	
											
													$ls_numordcomp=$io_datastore->getValue("numordcom",$li_i);
													$ls_estcondat=$io_datastore->getValue("estcondat",$li_i);
													$ls_codpro=$io_datastore->getValue("cod_pro",$li_i);
													$ls_codmon=$io_datastore->getValue("codmon",$li_i);   
													$ls_codfuefin=$io_datastore->getValue("codfuefin",$li_i);  
													$ls_codtipmod=$io_datastore->getValue("codtipmod",$li_i);    
													$ls_fecordcom=$io_datastore->getValue("fecordcom",$li_i);   
													$ls_estsegcom=$io_datastore->getValue("estsegcom",$li_i);   
													$ls_porsegcom=$io_datastore->getValue("porsegcom",$li_i);   
													$ls_monsegcom=$io_datastore->getValue("monsegcom",$li_i);   
													$ls_forpagcom=$io_datastore->getValue("forpagcom",$li_i);   
													$ls_estcom=$io_datastore->getValue("estcom",$li_i);   
													$ls_diaplacom=$io_datastore->getValue("diaplacom",$li_i);   
													$ls_concom=$io_datastore->getValue("concom",$li_i);   
													$ls_obscom=$io_datastore->getValue("obscom",$li_i);   
													$ls_monsubtotbie=$io_datastore->getValue("monsubtotbie",$li_i);
													$ls_monsubtotser=$io_datastore->getValue("monsubtotser",$li_i);
													$ls_monsubtot=$io_datastore->getValue("monsubtot",$li_i);   
													$ls_monbasimp=$io_datastore->getValue("monbasimp",$li_i);
													$ls_monimp=$io_datastore->getValue("monimp",$li_i);
													$ls_mondes=$io_datastore->getValue("mondes",$li_i);
													$ls_montot=$io_datastore->getValue("montot",$li_i);
													$ls_estpenalm=$io_datastore->getValue("estpenalm",$li_i);
													$ls_codpai=$io_datastore->getValue("codpai",$li_i);
													$ls_codest=$io_datastore->getValue("codest",$li_i);
													$ls_codmun=$io_datastore->getValue("codmun",$li_i);
													$ls_codpar=$io_datastore->getValue("codpar",$li_i);
													$ls_lugentnomdep=$io_datastore->getValue("lugentnomdep",$li_i);
													$ls_lugentdir=$io_datastore->getValue("lugentdir",$li_i);
													$ls_monant=$io_datastore->getValue("monant",$li_i);
													$ls_estlugcom=$io_datastore->getValue("estlugcom",$li_i);
													$ls_tascamordcom=$io_datastore->getValue("tascamordcom",$li_i);
													$ls_montotdiv=$io_datastore->getValue("montotdiv",$li_i);
													$ls_estapro=$io_datastore->getValue("estapro",$li_i);
													$ls_fecaprord=$io_datastore->getValue("fecaprord",$li_i);
													$ls_codusuapr=$io_datastore->getValue("codusuapr",$li_i);
													$ls_numpolcom=$io_datastore->getValue("numpolcom",$li_i);
													$ls_coduniadm=$io_datastore->getValue("coduniadm",$li_i);
													$ls_obsordcom=$io_datastore->getValue("obsordcom",$li_i);
													$ls_fecent=$io_datastore->getValue("fecent",$li_i);
													$ls_fechaconta=$io_datastore->getValue("fechaconta",$li_i);
													$ls_fechaanula=$io_datastore->getValue("fechaanula",$li_i);
													
													
													$ls_sqltransford=" INSERT INTO sfc_transferenciaorden".
													 " (numordcom,estcom,fecordcom)".
													 "  VALUES ".
													 " ('".$ls_numordcomp."','".$ls_estcom."','".$ls_fecordcom."')";                                                                       
													 $rs_data=$io_sql->execute($ls_sqltransford);              
													 if ($rs_data===false)
														{				 
															
															$is_msg->message("No se pudo Realizar el Registro de la
															 Transferencia ");	
														}		
													
													
$ls_sql2= " INSERT INTO soc_ordencompra ".
										   " (codemp, numordcom,estcondat,cod_pro,codmon,codfuefin,codtipmod,fecordcom,estsegcom,porsegcom,monsegcom,forpagcom,".
										   " estcom, diaplacom, concom, obscom, monsubtotbie, monsubtotser, monsubtot,monbasimp,monimp,mondes,montot,estpenalm,".
										   "codpai,codest,codmun,codpar,lugentnomdep,lugentdir,monant,estlugcom,tascamordcom,montotdiv,estapro, fecaprord,".
										   "codusuapr,numpolcom,coduniadm,obsordcom,fecent,fechaconta,fechaanula)".
										   " VALUES                                                                                     ".
										   " ('".$ls_codemp."','".$ls_numordcomp."','".$ls_estcondat."','".$ls_codpro."','".$ls_codmon."',".
										   "  '".$ls_codfuefin."','".$ls_codtipmod."','".$ls_fecordcom."','".$ls_estsegcom."','".$ls_porsegcom."',".
										   "  '".$ls_monsegcom."','".$ls_forpagcom."','".$ls_estcom."','".$ls_diaplacom."','".$ls_concom."',".
										   "  '".$ls_obscom."','".$ls_monsubtotbie."','".$ls_monsubtotser."','".$ls_monsubtot."','".$ls_monbasimp."',".
										   "  '".$ls_monimp."','".$ls_mondes."','".$ls_montot."','".$ls_estpenalm."','".$ls_codpai."','".$ls_codest."',".			
										   "  '".$ls_codmun."','".$ls_codpar."','".$ls_lugentnomdep."','".$ls_lugentdir."','".$ls_monant."','".$ls_estlugcom.",".		
										   "  '".$ls_tascamordcom."','".$ls_montotdiv."', '".$ls_estapro."','".$ls_fecaprord."','".$ls_codusuapr."',".
										   "  '".$ls_numpolcom."',".$ls_coduniadm.",'".$ls_obsordcom."','".$ls_fecent."','".$ls_fechaconta."','".$ls_fechaanula."');     
"; 
						
											 /***************************Archivo de Transferencia***************************/
											
												$ls_nomarchivo=$ls_denominacionu;
												$io_archivo->crear_archivo($ls_nomarchivo);
												$io_archivo->escribir_archivo($ls_sql2);
												$io_archivo->cerrar_archivo();
																							
											 /*****************************************************************************/
																

										$ls_cadenab="Select * from soc_dt_bienes where numordcom='".$ls_numordcomp.
											"' AND codemp='".$ls_codemp."' ";
										
													$arr_detalle=$io_sql2->select($ls_cadenab);
															
														if($row=$io_sql2->fetch_row($arr_detalle))
														  {
																
															$la_orddt=$io_sql2->obtener_datos($arr_detalle);
															$io_datastore3->data=$la_orddt;
															$totdt=$io_datastore3->getRowCount("numordcom");  
														
															for($li_s=1;$li_s<=$totdt;$li_s++)
															{	
																$ls_numorddt=$io_datastore3->getValue("numordcom",$li_s);
																$ls_estcondatdt=$io_datastore3->getValue("estcondat",$li_s);
																$ls_codartdt=$io_datastore3->getValue("codart",$li_s);
																$ls_unidaddt=$io_datastore3->getValue("unidad",$li_s);   
																$ld_canartdt=$io_datastore3->getValue("canart",$li_s);  
																$ld_penartdt=$io_datastore3->getValue("penart",$li_s);  
																$ld_preuniartdt=$io_datastore3->getValue("preuniart",$li_s);
																$ld_monsubartdt=$io_datastore3->getValue("monsubart",$li_s);
																$ld_montotartdt=$io_datastore3->getValue("montotart",$li_s);
																$li_ordendt=$io_datastore3->getValue("orden",$li_s);   
															
				$ls_sql3="INSERT INTO soc_dt_bienes (codemp, numordcom, estcondat, codart, unidad, canart, penart, ".
				 "preuniart, monsubart, montotart, orden)  VALUES ('".$ls_codemp."','".$ls_numorddt."','".$ls_estcondatdt."','".
				 $ls_codartdt."', '".$ls_unidaddt."',".$ld_canartdt.",".$ld_penartdt.",".$ld_preuniartdt.", ".$ld_monsubartdt.", "			
				 .$ld_montotartdt.",".$li_ordendt.");"; 
															
															/*******************Archivo de Transferencia******************/
												
							
																$ls_nomarchivo=$ls_denominacionu;
																$io_archivo->crear_archivo($ls_nomarchivo);
																$io_archivo->escribir_archivo($ls_sql3);
																$io_archivo->cerrar_archivo();
															  /*******************************************************/
														
																								
															}//for dt_bienes
															
														}//if row arr_dtbienes
													//}//else arrorddt	
										$ls_estatus="Procesada";					
													
													$la_objectordenes2[$li_p][1]="<input name=txtcodret".$li_p." type=text id=txtcodret".$li_p." class=sin-borde value='".$ls_numordcomp."' style= text-align:center size=15 readonly>";
													
													$la_objectordenes2[$li_p][2]="<input name=txtdesret".$li_p." type=text id=txtdesret".$li_p." class=sin-borde value='".$ls_fecordcom."' style= text-align:center size=8 readonly>";
													
													$la_objectordenes2[$li_p][3]="<input name=txtmontoret".$li_p." type=text id=txtmontoret".$li_p." class=sin-borde value='".$ls_forpagcom."' style= text-align:center size=10 readonly>"; 
														 
													$la_objectordenes2[$li_p][4]="<input name=txttotalret".$li_p." type=text id=txttotalret".$li_p." class=sin-borde value='".$ls_montot."' style= text-align:center size=12 readonly>"; 
													 
												    $la_objectordenes2[$li_p][5]="<input name=txtestatus".$li_p." type=text id=txtestatus".$li_p." class=sin-borde value='".$ls_estatus."' style= text-align:center size=12 readonly>"; 
											
				
							$li_p++;		
							$li_filasordenes2++;			
								
										}//for arr_ordenes
										$li_filasordenes2=$li_p;
										$la_objectordenes2[$li_filasordenes2][1]="<input name=txtcodret".$li_filasordenes2." type=text id=txtcodret".$li_filasordenes2." class=sin-borde style= text-align:center size=15 readonly >";
								$la_objectordenes2[$li_filasordenes2][2]="<input name=txtdesret".$li_filasordenes2." type=text id=txtdesret".$li_filasordenes2." class=sin-borde style= text-align:center size=8 readonly>";
								$la_objectordenes2[$li_filasordenes2][3]="<input name=txtmontoret".$li_filasordenes2." type=text id=txtmontoret".$li_filasordenes2." class=sin-borde style= text-align:center size=10 ><input name=formula".$li_filasordenes2." type=hidden id=formula".$li_filasordenes2.">";
								$la_objectordenes2[$li_filasordenes2][4]="<input name=txttotalret".$li_filasordenes2." type=text id=txttotalret".$li_filasordenes2." class=sin-borde style= text-align:center size=12 readonly>";
								$la_objectordenes2[$li_filasordenes2][5]="<input name=txtestatus".$li_filasordenes2." type=text id=txtestatus".$li_filasordenes2." class=sin-borde style= text-align:center size=12 readonly>";
									 }	//if row arr_ordenes
							
								}//else arr_ordenes
							
							
								$ls_cadenat="Select soc_ordencompra.* from soc_ordencompra where fecordcom='".$ls_fechahoy."' and  estcom<>'2' and numordcom in (Select numordcom from sfc_transferenciaorden where estcom ='2' )  and codemp='".$ls_codemp."' and coduniadm ='".$ls_coduniad."'";
								
							
							$arr_transf=$io_sql->select($ls_cadenat);	
							$li_pr=1;
							if($row=$io_sql->fetch_row($arr_transf))
							{
									
								$la_transford=$io_sql->obtener_datos($arr_transf);
								$io_datastore1->data=$la_transford;
								
								$tot=$io_datastore1->getRowCount("numordcom");  
								
								for($li_r=1;$li_r<=$tot;$li_r++)
								{	  
										
									$ls_nunordtransf=$io_datastore1->getValue("numordcom",$li_r);
								    $ls_estordtransf=$io_datastore1->getValue("estcom",$li_r);
									$ld_fecordtransf=$io_datastore1->getValue("fecordcom",$li_r);
									$ls_forpagcomtransf=$io_datastore1->getValue("forpagcom",$li_r); 
									$ls_montottransf=$io_datastore1->getValue("montot",$li_r);
									 
									$ls_sqltransford=" UPDATE sfc_transferenciaorden set estcom = '".$ls_estordtransf."' where numordcom='".$ls_nunordtransf."' and fecordcom='".$ls_fechahoy."' ";                                                                       
										
												
													 $rs_data=$io_sql->execute($ls_sqltransford);   
													        
													 if ($rs_data===false)
														{				 
															
															$is_msg->message("No se pudo Realizar la Actualizaci�n de la
															 Transferencia ");	
														}		
									
									$ls_sql4=" DELETE FROM soc_dt_bienes WHERE  codemp='".$ls_codemp."' AND numordcom='".$ls_nunordtransf."' ;";  
									
									 /*****************  GENERAR ARCHIVO DE TRANSFERENCIA  **************************/ 
						   
									$ls_nomarchivo=$ls_denominacionu;
									$io_archivo->crear_archivo($ls_nomarchivo);
									$io_archivo->escribir_archivo($ls_sql4);
									$io_archivo->cerrar_archivo();
									
									/*******************************************************************************/
									
									 $ls_sql5=" DELETE  FROM  soc_ordencompra WHERE  codemp='".$ls_codemp."' AND numordcom='".$ls_nunordtransf."' AND 					
									 estcondat='B';";   
						   
									/*****************  GENERAR ARCHIVO DE TRANSFERENCIA  **************************/ 
						   
									$ls_nomarchivo=$ls_denominacionu;
									$io_archivo->crear_archivo($ls_nomarchivo);
									$io_archivo->escribir_archivo($ls_sql5);
									$io_archivo->cerrar_archivo();
										
									$is_msg->message("Se Realiz� un Reverso en la Orden de Compra de la Tienda:".$ls_denominacionu);	
								}//for arr_sfc_transfordenes
							
								$ls_estatus="Procesada";	
								$la_objectordenes2[$li_pr][1]="<input name=txtcodret".$li_pr." type=text id=txtcodret".$li_pr." class=sin-borde value='".$ls_nunordtransf."' style= text-align:center size=15 readonly>";
													
													$la_objectordenes2[$li_pr][2]="<input name=txtdesret".$li_pr." type=text id=txtdesret".$li_pr." class=sin-borde value='".$ld_fecordtransf."' style= text-align:center size=8 readonly>";
													
													$la_objectordenes2[$li_pr][3]="<input name=txtmontoret".$li_pr." type=text id=txtmontoret".$li_pr." class=sin-borde value='".$ls_forpagcomtransf."' style= text-align:center size=10 readonly>"; 
														 
													$la_objectordenes2[$li_pr][4]="<input name=txttotalret".$li_pr." type=text id=txttotalret".$li_pr." class=sin-borde value='".$ls_montottransf."' style= text-align:center size=12 readonly>"; 
													 
												    $la_objectordenes2[$li_pr][5]="<input name=txtestatus".$li_pr." type=text id=txtestatus".$li_pr." class=sin-borde value='".$ls_estatus."' style= text-align:center size=12 readonly>"; 
											
				
							$li_pr++;		
							$li_filasordenes2++;			
								
									
										$li_filasordenes2=$li_pr;
										$la_objectordenes2[$li_filasordenes2][1]="<input name=txtcodret".$li_filasordenes2." type=text id=txtcodret".$li_filasordenes2." class=sin-borde style= text-align:center size=15 readonly >";
								$la_objectordenes2[$li_filasordenes2][2]="<input name=txtdesret".$li_filasordenes2." type=text id=txtdesret".$li_filasordenes2." class=sin-borde style= text-align:center size=8 readonly>";
								$la_objectordenes2[$li_filasordenes2][3]="<input name=txtmontoret".$li_filasordenes2." type=text id=txtmontoret".$li_filasordenes2." class=sin-borde style= text-align:center size=10 ><input name=formula".$li_filasordenes2." type=hidden id=formula".$li_filasordenes2.">";
								$la_objectordenes2[$li_filasordenes2][4]="<input name=txttotalret".$li_filasordenes2." type=text id=txttotalret".$li_filasordenes2." class=sin-borde style= text-align:center size=12 readonly>";
								$la_objectordenes2[$li_filasordenes2][5]="<input name=txtestatus".$li_filasordenes2." type=text id=txtestatus".$li_filasordenes2." class=sin-borde style= text-align:center size=12 readonly>";
								
							
							
							
							 }//if arr_sfc_transfordenes
						 
					if($li_filasordenes2<>""){
					
		//********************************//  LECTURA ARCHIVO ARTICULOS_PTOSVENTAS  //***********************************//
						$ls_fecha=date('dmY');
					//$archartven=file("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/ARTICULOS_PTOVENTAS/transPRODUCTO.txt");
					$archartven=file("/var/www/sigesp_fac/sfc/transferencias/ARTICULOS_PTOVENTAS/transPRODUCTO.txt");					
						$archivoproductos="";
						$lineas=count($archartven);
						for ($i=0;$i<$lineas;$i++)
						{
							$archivoproductos=$archivoproductos.$archartven[$i];
						}
						
		
	//********************************//  LECTURA ARCHIVO ARTICULOS_INVENTARIO  //***********************************//
					//$archartinv=file("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/ARTICULOS_INVENTARIO/transARTICULO.txt");
					$archartinv=file("/var/www/sigesp_fac/sfc/transferencias/ARTICULOS_INVENTARIO/transARTICULO.txt");
						$archivoinventario="";
						$lineas=count($archartinv);
						for ($i=0;$i<$lineas;$i++)
						{
							$archivoinventario=$archivoinventario.$archartinv[$i];
						}
	
						
	//********************************//  LECTURA ARCHIVO PROVEEDORES  //***************************************//
					//$archprov=file("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/PROVEEDORES/trans-PROVEEDORES.txt");
					$archprov=file("/var/www/sigesp_fac/sfc/transferencias/PROVEEDORES/trans-PROVEEDORES.txt");
						$archivoproveedor="";
						$lineas=count($archprov);
						for ($i=0;$i<$lineas;$i++)
						{
							$archivoproveedor=$archivoproveedor.$archprov[$i];
						}
						
	
//***********************//  CREACION DE ARCHIVO .ZIP //***********************************************// 					
						if(($estatus=="NP") and ($ls_nombre_des<>$ls_nomarchivo) and ($ls_nombre_ori<>$ls_nomarchivo))
						{
							$cont=array(ORDENCOMPRA."/".$ls_nomarchivo.".txt"=>$ls_sql2." ".$ls_sql3."  ".$ls_sql4."  ".$ls_sql5." ",
							"ARTICULOS_PTOVENTAS/transPRODUCTO.txt"=>$archivoproductos,
							"ARTICULOS_INVENTARIO/transARTICULO.txt"=>$archivoinventario,
							"PROVEEDORES/trans-PROVEEDORES.txt"=>$archivoproveedor);
							//createzip($cont,"C:/".$ls_nomarchivo."-".$ls_fecha.".zip ");
							createzip($cont,"/home/".$ls_nomarchivo."-".$ls_fecha.".zip ");
						}
						
						$cont=array("ARTICULOS_PTOVENTAS/transPRODUCTO.txt"=>$archivoproductos,
						"ARTICULOS_INVENTARIO/transARTICULO.txt"=>$archivoinventario,
						"PROVEEDORES/trans-PROVEEDORES.txt"=>$archivoproveedor);
						//createzip($cont,"C:/".$ls_fecha.".zip ");
						createzip($cont,"/home/".$ls_fecha.".zip ");	
					
					}
						}//ls_den='02-03'
						}//for arr_ordenes1=spg_unidadadministrativa
					}//if arr_ordenes1=spg_unidadadministrativa
				
		}//else	arr_ordenes1=spg_unidadadministrativa
  	   		//}//if $ls_estordtranf=2
	
}		

$is_msg->message("Archivos de Transferencia Generados Exitosamente!!");	

}
		
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
              <td colspan="2" class="titulo-ventana">Generar Archivo de Transferencia </td>
            </tr>
            <tr>
              <td >
			  <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
			  <input name="hidstatus" type="hidden" id="hidstatus">			  </td>
              <td >&nbsp;</td>
            </tr>
            
			<tr>
              <td width="76" height="22" align="right">&nbsp;</td>
              <td width="392" ><!-- javascript:ue_catusuario(); -->
              <?php
			   if (($lb_procesar==0) && ($lb_procesar2==0))
			   {
			   
			   ?>
			  <img src="Imagenes/ejecutar.gif" width="20" height="20" border="0">Procesar</td>
			   <?php
			   }
			  elseif (($lb_procesar==1) && ($lb_procesar2==0))
			 {
			 
			  ?>
			   <a href="javascript:ue_procesar();"><img src="Imagenes/ejecutar.gif" width="20" height="20" border="0">Procesar</a>		
			 
		    </td>
			  <?php
			 }
			  elseif (($lb_procesar==0) && ($lb_procesar2==1))
			 {
			  
			  ?>
			   <a href="javascript:ue_procesar();"><img src="Imagenes/ejecutar.gif" width="20" height="20" border="0">Procesar</a>		
			   </td>
			  <?php
			 }
			elseif (($lb_procesar==1) && ($lb_procesar2==1))
			 {
			 
			  ?>
			   <a href="javascript:ue_procesar();"><img src="Imagenes/ejecutar.gif" width="20" height="20" border="0">Procesar</a>		
			   </td>
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
              
            </tr>
		
		
<tr align="center" class="formato-blanco">
              <td height="11" colspan="2"><?php $io_grid->makegrid($li_filasalmacenes3,$la_columalmacenes3,$la_objectoalmacenes3,$li_anchoalmacenes3,$ls_tituloalmacenes3,$ls_nametable3);?></td>
           
		    </tr>

<?php

}		  
else{		  
?>
			
	<tr align="center" class="formato-blanco">
              <td height="11" colspan="2"><?php $io_grid->makegrid($li_filasordenes,$la_columordenes,$la_objectordenes,$li_anchoordenes,$ls_tituloordenes,$ls_nametable);?></td>
	  </tr>	  
			  
	<tr align="center" class="formato-blanco">
              <td height="11" colspan="2"><?php $io_grid->makegrid($li_filasalmacenes4,$la_columalmacenes4,$la_objectoalmacenes4,$li_anchoalmacenes4,$ls_tituloalmacenes4,$ls_nametable4);?></td>
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
  f.action="sigesp_sfc_d_generar_transforden.php";
  f.submit();
  
  }
 

</script>
</html>