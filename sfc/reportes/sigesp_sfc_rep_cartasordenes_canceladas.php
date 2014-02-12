<?Php
/******************************************/
/* FECHA: 13/08/2007                      */
/* AUTOR: ING. ZULHEYMAR RODR�GUEZ             */
/******************************************/
session_start();
require_once("sigesp_sfc_c_reportes.php");
$reporte = new sigesp_sfc_c_reportes('LETTER','landscape','REPORTE 1');
$io_pdf  = new class_pdf('LETTER','landscape');
$io_pdf->selectFont('../../../shared/ezpdf/fonts/Helvetica.afm');
$io_pdf->set_margenes(10,10,10,10);
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_datastore.php");
$io_datastore= new class_datastore();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$io_pdf = $reporte->get_pdf();
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$reporte->add_titulo("center",22,11,"LISTADO DE CARTAS ORDENES CANCELADAS");
$ls_fecha=date('d/m/Y,  g:i:s a');
$reporte->add_titulo("left",34,7,"Fecha de emisi�n: ".$ls_fecha);
$io_pdf->numerar_paginas(6);

$ls_sql=$_GET["sql"];
$ls_fecemi=$_GET["fecemi"];
$ls_fecemi2=$_GET["fecemi2"];
$io_pdf->numerar_paginas(6);
$ls_sql=str_replace("\\","",$ls_sql);
$ls_sql=str_replace("/","",$ls_sql);
//print $ls_sql;
$rs_datauni=$io_sql->select($ls_sql);
if($rs_datauni==false&&($io_sql->message!=""))
{
	$io_msg->message("No hay Nada que Reportar!!!");
}
else
{
   $la_cartaorden=$io_sql->obtener_datos($rs_datauni);
   if ($la_cartaorden)
   {
	$li_cuotas=(count($la_cartaorden,COUNT_RECURSIVE)/count($la_cartaorden)) - 1;

	$la_total=0;
	if ($ls_fecemi<>"%/%")
	{
	$ls_fecemi="".substr( $ls_fecemi,8,2)."/".substr( $ls_fecemi,5,2)."/".substr( $ls_fecemi,0,4)."";
	  $ls_fecemi2="".substr( $ls_fecemi2,8,2)."/".substr( $ls_fecemi2,5,2)."/".substr( $ls_fecemi2,0,4)."";
	  $reporte->add_titulo("right",34,7,"Fecha desde: ".$ls_fecemi." Hasta ".$ls_fecemi2);
	}

	for($i=0;$i<$li_cuotas;$i++)
	{
	 $ls_fecha2="".substr( $la_cartaorden["fecnot"][$i+1],8,2)."/".substr($la_cartaorden["fecnot"][$i+1],5,2)."/".substr( $la_cartaorden["fecnot"][$i+1],0,4)."";

	 $la_datos[$i]["<b>FECHA</b>"]=$ls_fecha2;
	 $la_datos[$i]["<b>R.I.F</b>"]= $la_cartaorden["cedcli"][$i+1];
	 $la_datos[$i]["<b>NOMBRE � RAZ�N SOCIAL</b>"]= strtoupper($la_cartaorden["razcli"][$i+1]);
	 $la_datos[$i]["<b>No. FACTURA</b>"]= strtoupper($la_cartaorden["nro_documento"][$i+1]);
	 $la_datos[$i]["<b>No. CARTA ORDEN</b>"]= strtoupper($la_cartaorden["numnot"][$i+1]);
	 $la_datos[$i]["<b>BANCO</b>"]= strtoupper($la_cartaorden["nomban"][$i+1]);
	 $la_datos[$i]["<b>ENTIDAD CREDITICIA</b>"]= strtoupper($la_cartaorden["denominacion"][$i+1]);
	 $la_datos[$i]["<b>MONTO Bs.</b>"]=$la_cartaorden["monto"][$i+1];
	 $la_total= $la_total+$la_datos[$i]["<b>MONTO Bs.</b>"];
	 $la_total2=number_format($la_total,2,',','.');
	 $la_datos[$i]["<b>MONTO Bs.</b>"]=number_format($la_datos[$i]["<b>MONTO Bs.</b>"],2,',', '.');

	}

	$io_pdf->ezSetY(445);
	$la_anchos_col = array(17,20,50,39,28,45,45,30);
	$la_justificaciones = array('center','left','left','left','left','left','left','right');
	$la_opciones = array(  "color_fondo" => array(229,229,229),
						   "color_texto" => array(0,0,0),
						   "anchos_col"  => $la_anchos_col,
						   "tamano_texto"=> 7,
						   "lineas"=>1,
						   "shaded"=>2,
						   "alineacion_col"=>$la_justificaciones,
						   "margen_horizontal"=>1);
	$io_pdf->add_tabla(-31,$la_datos,$la_opciones);
	$la_datos2[0]["totales"]= "<b>TOTAL Bs.</b>";

			   // datos para la segunda columna
			   //$la_titulos2[0]["2"]="";
			  	$la_datos2[0]["resultados"]= '<b>'.$la_total2.'</b>';     // SUBTOTAL

			  //print $li_cuotas;
				//$io_pdf->ezSetY(550-$li_cuotas*20);
				$la_anchos_col = array(45,30);
				$la_justificaciones = array('center','right');
				// titulos de la primera y segunda columna respectivamente
				$la_titulos2[0]["1"]="";
				$la_titulos2[0]["2"]="";


				$la_opciones2 = array(  "color_fondo" => array(229,229,229),
									   "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col,
									   "tamano_texto"=> 7,
									   "lineas"=>1,
									   "alineacion_col"=>$la_justificaciones,
									   "margen_horizontal"=>1);
				$io_pdf->add_tabla2(168,$la_datos2,$la_opciones2);




$io_pdf->ezStream();
}else{
?>
<script>
alert ("No hay Nada que Reportar");
</script>
<?php
}
}
?>
