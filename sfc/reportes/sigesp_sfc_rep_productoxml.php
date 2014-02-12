<?Php
/******************************************/
/* FECHA: 13/08/2007                      */ 
/* AUTOR: ING. ZULHEYMAR RODRÍGUEZ             */         
/******************************************/
session_start();
header("Content-type: text/xml");
require_once("sigesp_sfc_c_reportes.php");
$reporte = new sigesp_sfc_c_reportes('LETTER','landscape','REPORTE 1');
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_datastore.php");
//require_once('xml.php');
 //   $xml = new xml();
$io_datastore= new class_datastore();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$reporte->add_titulo("center",22,11,"LISTADO DE PRODUCTOS VENDIDOS(DETALLES)");
$ls_fecha=date('d/m/Y');
$reporte->add_titulo("left",34,7,"Fecha de emisión: ".$ls_fecha);
$ls_sql=$_GET["sql"];
$ls_sql=str_replace("\\","",$ls_sql);
$ls_sql=str_replace("/","",$ls_sql);
$ls_longitud=strlen($ls_sql);
$ls_posicion=strpos($ls_sql,";");
$ls_fecemi=$_GET["fecemi"];
$ls_fecemi2=$_GET["fecemi2"];

$ls_sql=substr($ls_sql,0,$ls_posicion+1);
$rs_datauni=$io_sql->select($ls_sql);
if($rs_datauni==false&&($io_sql->message!=""))
{  
	$io_msg->message("No hay Nada que Reportar");
}
else
{ 
   $la_producto=$io_sql->obtener_datos($rs_datauni);
	if ($la_producto){
	$li_cuotas=(count($la_producto,COUNT_RECURSIVE)/count($la_producto)) - 1;
	$la_total=0;
	if ($ls_fecemi<>"%/%")
	{	
/*	  $ls_fecemi="".substr( $ls_fecemi,8,2)."/".substr( $ls_fecemi,5,2)."/".substr( $ls_fecemi,0,4)."";
	  $ls_fecemi2="".substr( $ls_fecemi2,8,2)."/".substr( $ls_fecemi2,5,2)."/".substr( $ls_fecemi2,0,4)."";
	  $reporte->add_titulo("right",34,7,"Fecha desde: ".$ls_fecemi." Hasta ".$ls_fecemi2);*/
	}
	echo '<?xml version="1.0"?>';
	for($i=0;$i<$li_cuotas;$i++)
	{
	// $la_datos[$i]["<b>FECHA</b>"]=$ls_fecha2;
	echo '<factura'.$i.'>';
	echo $la_producto["numfac"][$i+1];
/*	 $la_datos[$i]["<b>R.I.F</b>"]= $la_producto["cedcli"][$i+1];
	 $la_datos[$i]["<b>RAZÓN SOCIAL</b>"]= strtoupper($la_producto["razcli"][$i+1]);
	 $la_datos[$i]["<b>PRODUCTO</b>"]= strtoupper($la_producto["denpro"][$i+1]);
	 $la_datos[$i]["<b>PRECIO*UNIDAD</b>"]=$la_producto["prepro"][$i+1];
	 $la_datos[$i]["<b>CANTIDAD</b>"]=$la_producto["canpro"][$i+1];	 
	 $la_datos[$i]["<b>SUB-TOTAL Bs.</b>"]=$la_datos[$i]["<b>CANTIDAD</b>"]*$la_datos[$i]["<b>PRECIO*UNIDAD</b>"];
	 $la_total= $la_total+$la_datos[$i]["<b>SUB-TOTAL Bs.</b>"];
	 $la_total2=number_format($la_total,2,',','.');
	 $la_datos[$i]["<b>PRECIO*UNIDAD</b>"]= number_format($la_producto["prepro"][$i+1],2, ',', '.');
	 $la_datos[$i]["<b>CANTIDAD</b>"]= number_format($la_producto["canpro"][$i+1],2, ',', '.');	 
	 $la_datos[$i]["<b>SUB-TOTAL Bs.</b>"]=number_format($la_datos[$i]["<b>SUB-TOTAL Bs.</b>"],2, ',', '.');*/
	//$exampleData[$i]= array('factura'=>$la_factura);
		echo '</factura'.$i.'>';
										 
	}	
	/*$la_datos2[0]["totales"]= "<b>TOTAL Bs.</b>";
	$la_datos2[0]["resultados"]= '<b>'.$la_total2.'</b>';     // SUBTOTAL	
	$la_data=array('root'=>array('reporte'=>$exampleData),);
	$xml->setXMLHead();
	$xml->setArray($la_data);
	$xml->outputXML('echo');*/
}else{
?>
<script>
alert ("No hay Nada que Reportar");
</script>
<?php
}
}
?>