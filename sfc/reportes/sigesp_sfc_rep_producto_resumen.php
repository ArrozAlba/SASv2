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
$io_pdf->set_margenes(10,10,5,5);
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
/******************************************************************************************************************************/
$reporte->add_titulo("center",9,11,"LISTADO DE PRODUCTOS VENDIDOS(RESUMEN)");
$ls_fecha=date('d/m/Y');
$reporte->add_titulo("left",17,7,"Fecha emision: ".$ls_fecha);
//$ruta=$_GET["ruta"];

$ls_sql=$_GET["sql"];
$ls_fecemi=$_GET["fecemi"];
$ls_fecemi2=$_GET["fecemi2"];
$io_pdf->numerar_paginas(6);
$ls_sql=str_replace("\\","",$ls_sql);
$ls_sql=str_replace("/","",$ls_sql);
$rs_datauni=$io_sql->select($ls_sql);

if($rs_datauni==false&&($io_sql->message!=""))
{
	$io_msg->message("No hay Nada que Reportar");
}
else
{
   $la_producto=$io_sql->obtener_datos($rs_datauni);
   if ($la_producto)
   {
	$li_cuotas=(count($la_producto,COUNT_RECURSIVE)/count($la_producto)) - 1;
	$la_total=0;
	if ($ls_fecemi<>"%/%")
	{
	$ls_fecemi="".substr( $ls_fecemi,8,2)."/".substr( $ls_fecemi,5,2)."/".substr( $ls_fecemi,0,4)."";
	  $ls_fecemi2="".substr( $ls_fecemi2,8,2)."/".substr( $ls_fecemi2,5,2)."/".substr( $ls_fecemi2,0,4)."";
	  $reporte->add_titulo("right",34,7,"Fecha desde: ".$ls_fecemi." Hasta ".$ls_fecemi2);
	}
	for($i=0;$i<$li_cuotas;$i++)
	{
	 //print $la_producto["porimp"][$i+1];
	 $la_datos[$i]["<b>CÓDIGO</b>"]=strtoupper($la_producto["codpro"][$i+1]);
	 $la_datos[$i]["<b>DESCRIPCIÓN</b>"]=strtoupper($la_producto["denpro"][$i+1]);
	 $la_datos[$i]["<b>PROVEEDOR</b>"]=strtoupper($la_producto["nompro"][$i+1]);
	 $la_datos[$i]["<b>CANTIDAD</b>"]= number_format($la_producto["canpro"][$i+1],2, ',', '.');
	 $la_datos[$i]["<b>PRECIO</b>"]= number_format($la_producto["prepro"][$i+1],2, ',', '.');
	 $la_datos[$i]["<b>SUB-TOTAL Bs.</b>"]=(round(round(round($la_producto["canpro"][$i+1]*$la_producto["prepro"][$i+1],2)*$la_producto["porimp"][$i+1],2)/100,2)+round($la_producto["canpro"][$i+1]*$la_producto["prepro"][$i+1],2));
	 $la_datos[$i]["<b>SUB-TOTAL Bs.</b>"]=number_format($la_datos[$i]["<b>SUB-TOTAL Bs.</b>"],2, ',', '.');


	 $la_total= $la_total+(round(round(round($la_producto["canpro"][$i+1]*$la_producto["prepro"][$i+1],2)*$la_producto["porimp"][$i+1],2)/100,2)+round($la_producto["canpro"][$i+1]*$la_producto["prepro"][$i+1],2));
	 $la_total2=number_format($la_total,2,',','.');
	 $sumatotal=$sumatotal+$la_producto["canpro"][$i+1];
	  }
	//$io_pdf->add_lineas(5);
	$io_pdf->ezSetY(485);
	$la_anchos_col = array(35,75,75,20,25,25);
	$la_titulos[0]["<b>CÓDIGO</b>"]= "<b>CODIGO</b>";
	$la_titulos[0]["<b>DESCRIPCION</b>"]= "<b>DESCRIPCION</b>";
	$la_titulos[0]["<b>PROVEEDOR</b>"]= "<b>PROVEEDOR</b>";
	$la_titulos[0]["<b>CANTIDAD</b>"]="<b>CANTIDAD</b>";
	$la_titulos[0]["<b>PRECIO</b>"]="<b>PRECIO</b>";
	$la_titulos[0]["<b>SUB-TOTAL Bs.</b>"]="<b>SUB-TOTAL Bs.</b>";
	$la_justificaciones = array('center','left','left','right','right','right','center');
	$la_opciones = array(  "color_fondo" => array(229,229,229),
						   "color_texto" => array(0,0,0),
						   "anchos_col"  => $la_anchos_col,
						   "tamano_texto"=> 7,
						   "lineas"=>1,
						   "alineacion_col"=>$la_justificaciones,
						   "margen_horizontal"=>2);
	$io_pdf->add_tabla(-25,$la_titulos,$la_opciones);
    $la_justificaciones = array('center','left','left','right','right','right','center');
	$la_opciones = array(  "color_fondo" => array(255,255,255),
						   "color_texto" => array(0,0,0),
						   "anchos_col"  => $la_anchos_col,
						   "tamano_texto"=> 7,
						   "lineas"=>1,
						   "alineacion_col"=>$la_justificaciones,
						   "margen_horizontal"=>2);
	$io_pdf->add_tabla(-25,$la_datos,$la_opciones);

 	$la_datos2[0]["totales"]= "<b>Totales:</b>";

   // datos para la segunda columna
   //$la_titulos2[0]["2"]="";
  	$la_datos2[0]["cantidades"]= '<b>'.number_format($sumatotal,2,",",".").'</b>';     // SUBTOTAL
  	$la_datos2[0][""]= "";     // SUBTOTAL
  	$la_datos2[0]["resultados"]= '<b>'.$la_total2.'</b>';     // SUBTOTAL

			  //print $li_cuotas;
				//$io_pdf->ezSetY(550-($li_cuotas*20));
				$la_anchos_col = array(75,20,25,25);
				$la_justificaciones = array('right','right','right','right');
				// titulos de la primera y segunda columna respectivamente
				$la_titulos2[0]["1"]="";
				$la_titulos2[0]["2"]="";
				$la_titulos2[0]["3"]="";
				$la_titulos2[0]["4"]="";


				$la_opciones2 = array(  "color_fondo" => array(229,229,229),
									   "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col,
									   "tamano_texto"=> 7,
									   "lineas"=>1,
									   "alineacion_col"=>$la_justificaciones,
									   "margen_horizontal"=>1);
				$io_pdf->add_tabla2(86,$la_datos2,$la_opciones2);


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