<?Php

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
$io_sql2=new class_sql($io_connect);
$io_data=new class_datastore();
$io_datastore4=new class_datastore();
$io_funcion=new class_funciones();
$io_pdf = $reporte->get_pdf();
/******************************************************************************************************************************/

$ls_codtie=$_SESSION["ls_codtienda"];
 $ls_sqltie="Select dentie from sfc_tienda where codtie='".$ls_codtie."' ";
	$rs_datatie=$io_sql2->select($ls_sqltie);
			if($rs_datatie==false&&($io_sql2->message!=""))
			{
				//$io_msg->message("No hay registros");
			}
			else
			{
				if($row=$io_sql2->fetch_row($rs_datatie))
				{
					$la_agrotienda=$io_sql2->obtener_datos($rs_datatie);
					$io_datastore4->data=$la_agrotienda;
					$totrowt=$io_datastore4->getRowCount("dentie");
					//print_r ($la_agrotienda);

						//print "Paso".$totrowt;
					for($t=0;$t<=$totrowt;$t++)
					{


						$ls_dentie=$io_datastore4->getValue("dentie",$t);
					}
				}
			}



$reporte->add_titulo("center",18,10,"CVA-ECISA ".$ls_dentie);
$reporte->add_titulo("center",22,10,"Resumen General de Ventas por Productos");
$ls_fecha=date('d/m/Y');
$reporte->add_titulo("left",34,7,"Fecha emisi�n: ".$ls_fecha);
//$ruta=$_GET["ruta"];

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
	$io_msg->message("No hay Nada que Reportar");
}
else
{
   $la_producto=$io_sql->obtener_datos($rs_datauni);
   if ($la_producto)
   {

   	$li_cuotas=$io_sql->num_rows($rs_datauni);
	//$li_cuotas=(count($la_producto,COUNT_RECURSIVE)/count($la_producto)) - 1;
	$la_total=0;
	if ($ls_fecemi<>"%/%")
	{
	$ls_fecemi="".substr( $ls_fecemi,8,2)."/".substr( $ls_fecemi,5,2)."/".substr( $ls_fecemi,0,4)."";
	  $ls_fecemi2="".substr( $ls_fecemi2,8,2)."/".substr( $ls_fecemi2,5,2)."/".substr( $ls_fecemi2,0,4)."";
	  $reporte->add_titulo("right",28,9,"Fecha desde: ".$ls_fecemi." Hasta ".$ls_fecemi2);
	}
	for($i=0;$i<$li_cuotas;$i++)
	{
	// $la_datos[$i]["<b>Nº</b>"]= $i;
	 $la_datos[$i]["<b>CÓDIGO</b>"]= strtoupper($la_producto["codart"][$i+1]);
	 $la_datos[$i]["<b>DESCRIPCIÓN</b>"]= strtoupper($la_producto["denart"][$i+1]);
	 $la_datos[$i]["<b>CANTIDAD</b>"]=$la_producto["cantidad"][$i+1];
	 $ls_precioiva=$la_producto["prepro"][$i+1]+($la_producto["prepro"][$i+1]*$la_producto["porimp"][$i+1]/100);
	 $la_datos[$i]["<b>PRECIO Unit.</b>"]=$ls_precioiva;
	 $ls_totalvta=($la_datos[$i]["<b>CANTIDAD</b>"]*$la_datos[$i]["<b>PRECIO Unit.</b>"]);
	 $la_datos[$i]["<b>TOTAL VENTAS</b>"]=($la_datos[$i]["<b>CANTIDAD</b>"]*$la_datos[$i]["<b>PRECIO Unit.</b>"]);
	 $la_datos[$i]["<b>COSTO PROMEDIO</b>"]=$la_producto["cosproart"][$i+1];
	 $ls_costototal=$la_producto["cosproart"][$i+1]*$la_producto["cantidad"][$i+1];
	 $la_datos[$i]["<b>COSTO TOTAL</b>"]=number_format($ls_costototal,2, ',', '.');
	 $la_datos[$i]["<b>UTILIDAD </b>"]=number_format($ls_totalvta-$ls_costototal,2,',','.');
	 $ls_utilidad=$ls_totalvta-$ls_costototal;
	 if ($ls_totalvta==0)
	 $la_datos[$i]["<b>% UTILIDAD </b>"]=0;
	 else
	//print $ls_totalvta.$la_datos[$i]["<b>DESCRIPCI�N</b>"].$ls_precioiva."<br>";
//print $ls_totalvta."<br>";
	 $la_datos[$i]["<b>% UTILIDAD </b>"]=number_format((($ls_utilidad*100)/$ls_totalvta),2,',','.');



	 $la_total= $la_total+($ls_totalvta);
	$li_totacum=$li_totacum+$ls_totalvta;
	$li_acumcosto=$li_acumcosto+$la_datos[$i]["<b>COSTO PROMEDIO</b>"];
	$li_acumcostotot=$li_acumcostotot+$ls_costototal;
	$li_acumuti=$li_acumuti+ $ls_utilidad;
	$li_acumporcutil=$li_acumporcutil+$la_datos[$i]["<b>% UTILIDAD </b>"];


	 $la_datos[$i]["<b>CANTIDAD</b>"]= number_format($la_producto["cantidad"][$i+1],2, ',', '.');
	  $la_datos[$i]["<b>PRECIO Unit.</b>"]= number_format($ls_precioiva,2, ',', '.');
	 $la_datos[$i]["<b>TOTAL VENTAS</b>"]=number_format($la_datos[$i]["<b>TOTAL VENTAS</b>"],2, ',', '.');
	 $la_datos[$i]["<b>COSTO PROMEDIO</b>"]=number_format($la_producto["cosproart"][$i+1],2, ',', '.');
	 $sumatotal=$sumatotal+$la_producto["cantidad"][$i+1];
	  }
	//$io_pdf->add_lineas(5);
	$io_pdf->ezSetY(445);
	$la_anchos_col = array(35,75,18,25,25,20,25,25,18);

    $la_justificaciones = array('center','left','right','right','right','right','right','right','right');
	$la_opciones = array(  "color_fondo" => array(229,229,229),
						   "color_texto" => array(0,0,0),
						   "anchos_col"  => $la_anchos_col,
						   "tamano_texto"=> 7,
						   "lineas"=>1,
						   "alineacion_col"=>$la_justificaciones,
						   "margen_horizontal"=>1);
	$io_pdf->add_tabla(-28,$la_datos,$la_opciones);

 	$la_datos2[0]["totales"]= "<b>Total Ventas Bs.</b>";

 $la_total2=number_format($la_total,2,',','.');
 $li_acumcosto=number_format($li_acumcosto,2,',','.');
 $li_acumcostotot=number_format($li_acumcostotot,2,',','.');
 $li_acumuti=number_format($li_acumuti,2,',','.');
 $li_acumporcutil=number_format($li_acumporcutil,2,',','.');


			   // datos para la segunda columna
			   //$la_titulos2[0]["2"]="";
			  	$la_datos2[0]["resultados"]= '<b>'.$la_total2.'</b>';     // SUBTOTAL
				$la_datos2[0]["resultados1"]= '<b>'.$li_acumcosto.'</b>';
				$la_datos2[0]["resultados2"]= '<b>'.$li_acumcostotot.'</b>';     // SUBTOTAL
				$la_datos2[0]["resultados3"]= '<b>'.$li_acumuti.'</b>';
				$la_datos2[0]["resultados4"]= '<b>'.$li_acumporcutil.'</b>';
			  //print $li_cuotas;
				//$io_pdf->ezSetY(550-($li_cuotas*20));
				$la_anchos_col = array(25,25,20,25,25,18);
				$la_justificaciones = array('left','right','right','right','right','right');
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
				$io_pdf->add_tabla2(100,$la_datos2,$la_opciones2);


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
