<?Php
/******************************************/
/* FECHA: 13/08/2007                      */
/* AUTOR: ING. ZULHEYMAR RODR�GUEZ        */
/******************************************/

session_start();
require_once("sigesp_sfc_c_reportes.php");
$reporte = new sigesp_sfc_c_reportes('LETTER','portrait','REPORTE 1');
$io_pdf  = new class_pdf('LETTER','portrait');
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
$io_pdf->numerar_paginas(6);
$reporte->add_titulo("center",10,15,"CUENTAS POR COBRAR...");
$ls_fecha=date('d/m/Y');
$ls_fecemi=$_GET["fecemi"];
$ls_fecemi2=$_GET["fecemi2"];
$ls_ordenarpor=$_GET["ordenapor"];
$ls_orden=$_GET["orden"];
$ls_cadena=$_GET["cadena"];
$ls_cadena=str_replace("\\","",$ls_cadena);
$ls_cadena=str_replace("/","",$ls_cadena);
$ls_cadena2=$_GET["cadena2"];
$ls_cadena2=str_replace("\\","",$ls_cadena2);
$ls_cadena2=str_replace("/","",$ls_cadena2);
//print $ls_cadena;

$reporte->add_titulo("left",25,7,"Fecha emisi�n: ".$ls_fecha);
$existe_cobro=0;
$existe_factura=0;
$ls_monto2=0;
$li_i2=1;
$li_i3=1;

/*print $ls_cadena."<br>";
print $ls_cadena2."<br>";*/

if ($ls_fecemi<>"")
{
	$ls_fecemi="".substr($ls_fecemi,8,2)."/".substr($ls_fecemi,5,2)."/".substr($ls_fecemi,0,4)."";
	$ls_fecemi2="".substr($ls_fecemi2,8,2)."/".substr($ls_fecemi2,5,2)."/".substr($ls_fecemi2,0,4)."";
	$reporte->add_titulo("right",25,7,"Fecha desde: ".$ls_fecemi."  Hasta ".$ls_fecemi2);
}

$arr_detfactura=$io_sql->select($ls_cadena);
$f=$io_sql->fetch_row($arr_detfactura);
if ($f!="")
{
	$arr_detfactura=$io_sql->select($ls_cadena);
	//$w=$io_sql->fetch_row($arr_detfactura);
	$existe_cobro=1;
	//$arr_detfactura=$io_sql->select($ls_cadena);
	if($row=$io_sql->fetch_row($arr_detfactura))
	  {
		$la_producto=$io_sql->obtener_datos($arr_detfactura);
		$io_datastore->data=$la_producto;
		//print_r($la_producto);
		$totrow=$io_datastore->getRowCount("codcli");
        $ls_suiche=false;
		for($li_i=1;$li_i<=$totrow;$li_i++)
		{
			$ls_numfac=$io_datastore->getValue("numfac",$li_i);
			$ls_razcli=$io_datastore->getValue("razcli",$li_i);
			$ls_codcli=$io_datastore->getValue("cedcli",$li_i);
            $ls_fecemi=$io_datastore->getValue("fecemi",$li_i);
			$ls_fecemi="".substr($ls_fecemi,8,2)."/".substr($ls_fecemi,5,2)."/".substr($ls_fecemi,0,4)."";
			$ls_montos_cobrado=$io_datastore->getValue("montos_cobrado",$li_i);
			$ls_montopar=$io_datastore->getValue("montopar",$li_i);
			$arreglo_cobro[$li_i2]["numfac"]=$ls_numfac;
			$arreglo_cobro[$li_i2]["razcli"]=$ls_razcli;
			$arreglo_cobro[$li_i2]["codcli"]=$ls_codcli;
			$arreglo_cobro[$li_i2]["fecemi"]=$ls_fecemi;
			$arreglo_cobro[$li_i2]["debitos"]=$ls_montopar; //debitos
			$arreglo_cobro[$li_i2]["creditos"]=$ls_montos_cobrado; //creditos
			$arreglo_cobro[$li_i2]["montopar"]=$ls_montopar-$ls_montos_cobrado; //saldo
			$li_i2=$li_i2+1;
		} //for
	}//if($row=$io_sql->fetch_row($arr_detfactura)
}
$arr_detfactura=$io_sql->select($ls_cadena2);
$w=$io_sql->fetch_row($arr_detfactura);
if ($w!="")
{
  	$arr_detfactura=$io_sql->select($ls_cadena2);
	$w=$io_sql->fetch_row($arr_detfactura);
	$existe_factura=1;
   	$arr_detfactura=$io_sql->select($ls_cadena2);
	if($row=$io_sql->fetch_row($arr_detfactura))
	  {
		$la_producto2=$io_sql->obtener_datos($arr_detfactura);
		$io_datastore->data=$la_producto2;
		$totrow=$io_datastore->getRowCount("codcli");
		for($li_i=1;$li_i<=$totrow;$li_i++)
		{
			$ls_numfac=$io_datastore->getValue("numfac",$li_i);
			$ls_razcli=$io_datastore->getValue("razcli",$li_i);
			$ls_codcli=$io_datastore->getValue("cedcli",$li_i);
            $ls_fecemi=$io_datastore->getValue("fecemi",$li_i);
			$ls_fecemi="".substr($ls_fecemi,8,2)."/".substr($ls_fecemi,5,2)."/".substr($ls_fecemi,0,4)."";
			$ls_montos_cobrado=0;
			$ls_montopar=$io_datastore->getValue("montopar",$li_i);
			$arreglo_cobro2[$li_i3]["numfac"]=$ls_numfac;
			$arreglo_cobro2[$li_i3]["razcli"]=$ls_razcli;
			$arreglo_cobro2[$li_i3]["codcli"]=$ls_codcli;
			$arreglo_cobro2[$li_i3]["fecemi"]=$ls_fecemi;
			$arreglo_cobro2[$li_i3]["debitos"]=$ls_montopar; //debitos
			$arreglo_cobro2[$li_i3]["creditos"]=$ls_montos_cobrado; //creditos
			$arreglo_cobro2[$li_i3]["montopar"]=$ls_montopar-$ls_montos_cobrado; //saldo
			$li_i3=$li_i3+1;
		} //for
	 }//if($row=$io_sql->fetch_row($arr_detfactura)
}

//print "PUN".$existe_factura."----".$existe_cobro;
if ($f!="")
{
	if (($existe_factura==1) || ($existe_cobro==1))
	{
		$total=0;
	   	for($i=0;$i<$li_i2;$i++)
		{
		 $la_datos[$i]["<b>No. FACTURA</b>"]= $arreglo_cobro[$i+1]["numfac"];
		 $la_datos[$i]["<b>R.I.F.</b>"]= $arreglo_cobro[$i+1]["codcli"];
		 $la_datos[$i]["<b>RAZ�N SOCIAL</b>"]= $arreglo_cobro[$i+1]["razcli"];
		 $la_datos[$i]["<b>FECHA DE EMISI�N</b>"]= $arreglo_cobro[$i+1]["fecemi"];
		 $la_datos[$i]["<b>DEBITO</b>"]= $arreglo_cobro[$i+1]["debitos"]; //suma de montos cobrados
		 $la_datos[$i]["<b>CREDITO</b>"]= $arreglo_cobro[$i+1]["creditos"]; //suma de montos cobrados
		 $la_datos[$i]["<b>MONTO*COBRAR</b>"]= $arreglo_cobro[$i+1]["montopar"]; //suma de montos cobrados
		 $la_datos[$i]["<b>DEBITO</b>"]= number_format($arreglo_cobro[$i+1]["debitos"],2, ',', '.');
		 $la_datos[$i]["<b>CREDITO</b>"]= number_format($arreglo_cobro[$i+1]["creditos"],2, ',', '.');
		 $la_datos[$i]["<b>MONTO*COBRAR</b>"]= number_format($arreglo_cobro[$i+1]["montopar"],2, ',', '.');
		 $total=$total+$arreglo_cobro[$i+1]["montopar"];
		}

		$total=number_format($total,2, ',', '.');
		$io_pdf->ezSetY(600);
		$la_anchos_col = array(206);
		$la_justificaciones = array('center');
		$la_titulos[0]="<b>Facturas por Cobrar con Credito</b>";
		$la_opciones = array(  "color_fondo" => array(200,200,200),
							   "color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=>9,
							   "lineas"=>1,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>1);
		$io_pdf->add_tabla2(-23.5,$la_titulos,$la_opciones);
		
		$la_titulos[0]["<b>No. FACTURA</b>"]= "<b>No. FACTURA</b>";
		$la_titulos[0]["<b>R.I.F.</b>"]= "<b>R.I.F.</b>";
		$la_titulos[0]["<b>RAZ�N SOCIAL</b>"]= "<b>RAZON SOCIAL</b>";
		$la_titulos[0]["<b>FECHA DE EMISI�N</b>"]= "<b>FECHA DE EMISION</b>";
		$la_titulos[0]["<b>DEBITO</b>"]="<b>DEBITO</b>"; //suma de montos cobrados
		$la_titulos[0]["<b>CREDITO</b>"]= "<b>CREDITO</b>"; //suma de montos cobrados
		$la_titulos[0]["<b>MONTO*COBRAR</b>"]= "<b>MONTO*COBRAR</b>"; //suma de montos cobrados
		
		$la_anchos_col = array(40,20,60,20,20,20,26);
		$la_justificaciones = array('center','center','left','center','right','right','right');
		$la_opciones = array(  "color_fondo" => array(255,255,255),
							   "color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 7,
							   "lineas"=>1,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>1);
		$io_pdf->add_tabla(-25,$la_titulos,$la_opciones);
		$io_pdf->add_tabla(-25,$la_datos,$la_opciones);

		$la_datos2[0]["totales"]= "<b>TOTAL POR COBRAR Bs.</b>";
	   	$la_datos2[0]["resultados"]= '<b>'.$total.'</b>';     // SUBTOTAL
	  	$la_anchos_col = array(20,26);
		$la_justificaciones = array('left','right');
		$la_opciones2 = array(  "color_fondo" => array(229,229,229),
							   "color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 7,
							   "lineas"=>1,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>1);
		$io_pdf->add_tabla2(137,$la_datos2,$la_opciones2);
		$io_pdf->add_lineas(1);
		if (($existe_factura==1))
		{
			$total=0;
	        for($i=0;$i<$li_i3;$i++)
			{
			 $la_datos3[$i]["<b>No. FACTURA</b>"]= $arreglo_cobro2[$i+1]["numfac"];
			 $la_datos3[$i]["<b>R.I.F.</b>"]= $arreglo_cobro2[$i+1]["codcli"];
			 $la_datos3[$i]["<b>RAZ�N SOCIAL</b>"]= $arreglo_cobro2[$i+1]["razcli"];
			 $la_datos3[$i]["<b>FECHA DE EMISION</b>"]= $arreglo_cobro2[$i+1]["fecemi"];
			 $la_datos3[$i]["<b>DEBITO</b>"]= $arreglo_cobro2[$i+1]["debitos"]; //suma de montos cobrados
			 $la_datos3[$i]["<b>CREDITO</b>"]= $arreglo_cobro2[$i+1]["creditos"]; //suma de montos cobrados
			 $la_datos3[$i]["<b>MONTO*COBRAR</b>"]= $arreglo_cobro2[$i+1]["montopar"]; //suma de montos cobrados
			 $la_datos3[$i]["<b>DEBITO</b>"]= number_format($arreglo_cobro2[$i+1]["debitos"],2, ',', '.');
			 $la_datos3[$i]["<b>CREDITO</b>"]= number_format($arreglo_cobro2[$i+1]["creditos"],2, ',', '.');
			 $la_datos3[$i]["<b>MONTO*COBRAR</b>"]= number_format($arreglo_cobro2[$i+1]["montopar"],2, ',', '.');
 			 $total=$total+$arreglo_cobro2[$i+1]["montopar"];
			}

			$total=number_format($total,2, ',', '.');

			$io_pdf->ezSetY(490);
	     	$la_anchos_col = array(206);
			$la_justificaciones = array('center');
			$la_titulos3[0]="<b>Facturas por Cobrar sin Credito</b>";
			$la_opciones = array(  "color_fondo" => array(200,200,200),
								   "color_texto" => array(0,0,0),
								   "anchos_col"  => $la_anchos_col,
								   "tamano_texto"=>9,
								   "lineas"=>1,
								   "alineacion_col"=>$la_justificaciones,
								   "margen_horizontal"=>1);
			$io_pdf->add_tabla2(-23.5,$la_titulos3,$la_opciones);
			
			$la_titulos[0]["<b>No. FACTURA</b>"]= "<b>No. FACTURA</b>";
			$la_titulos[0]["<b>R.I.F.</b>"]= "<b>R.I.F.</b>";
			$la_titulos[0]["<b>RAZ�N SOCIAL</b>"]= "<b>RAZON SOCIAL</b>";
			$la_titulos[0]["<b>FECHA DE EMISI�N</b>"]= "<b>FECHA DE EMISION</b>";
			$la_titulos[0]["<b>DEBITO</b>"]="<b>DEBITO</b>"; //suma de montos cobrados
			$la_titulos[0]["<b>CREDITO</b>"]= "<b>CREDITO</b>"; //suma de montos cobrados
			$la_titulos[0]["<b>MONTO*COBRAR</b>"]= "<b>MONTO*COBRAR</b>"; //suma de montos cobrados
			
			
			$la_anchos_col = array(40,20,60,20,20,20,26);
			$la_justificaciones = array('center','center','left','center','right','right','right');
			$la_opciones = array(  "color_fondo" => array(255,255,255),
								   "color_texto" => array(0,0,0),
								   "anchos_col"  => $la_anchos_col,
								   "tamano_texto"=> 7,
								   "lineas"=>1,
								   "alineacion_col"=>$la_justificaciones,
								   "margen_horizontal"=>1);
			$io_pdf->add_tabla(-25,$la_titulos,$la_opciones);								   
			$io_pdf->add_tabla(-25,$la_datos3,$la_opciones);

			$la_datos2[0]["totales"]= "<b>TOTAL POR COBRAR Bs.</b>";
		   	$la_datos2[0]["resultados"]= '<b>'.$total.'</b>';     // SUBTOTAL
			$la_anchos_col = array(20,26);
			$la_justificaciones = array('left','right');
			$la_opciones2 = array(  "color_fondo" => array(255,255,255),
								   "color_texto" => array(0,0,0),
								   "anchos_col"  => $la_anchos_col,
								   "tamano_texto"=> 7,
								   "lineas"=>1,
								   "alineacion_col"=>$la_justificaciones,
								   "margen_horizontal"=>1);
			$io_pdf->add_tabla2(137,$la_datos2,$la_opciones2);
		}
	}
}
else
{
	if (($existe_factura==1))
 		{
			$total=0;
			for($i=0;$i<$li_i3;$i++)
			{
			 $la_datos3[$i]["<b>No. FACTURA</b>"]= $arreglo_cobro2[$i+1]["numfac"];
			 $la_datos3[$i]["<b>R.I.F.</b>"]= $arreglo_cobro2[$i+1]["codcli"];
			 $la_datos3[$i]["<b>RAZ�N SOCIAL</b>"]= $arreglo_cobro2[$i+1]["razcli"];
			 $la_datos3[$i]["<b>FECHA DE EMISI�N</b>"]= $arreglo_cobro2[$i+1]["fecemi"];
			 $la_datos3[$i]["<b>DEBITO</b>"]= $arreglo_cobro2[$i+1]["debitos"]; //suma de montos cobrados
			 $la_datos3[$i]["<b>CREDITO</b>"]= $arreglo_cobro2[$i+1]["creditos"]; //suma de montos cobrados
			 $la_datos3[$i]["<b>MONTO*COBRAR</b>"]= $arreglo_cobro2[$i+1]["montopar"]; //suma de montos cobrados
			 $la_datos3[$i]["<b>DEBITO</b>"]= number_format($arreglo_cobro2[$i+1]["debitos"],2, ',', '.');
			 $la_datos3[$i]["<b>CREDITO</b>"]= number_format($arreglo_cobro2[$i+1]["creditos"],2, ',', '.');
			 $la_datos3[$i]["<b>MONTO*COBRAR</b>"]= number_format($arreglo_cobro2[$i+1]["montopar"],2, ',', '.');
 			 $total=$total+$arreglo_cobro2[$i+1]["montopar"];
			}

			$total=number_format($total,2, ',', '.');
			$io_pdf->ezSetY(600);
	     	$la_anchos_col = array(206);
			$la_justificaciones = array('center');
			$la_titulos3[0]="<b>Facturas por Cobrar sin Credito</b>";
			$la_opciones = array(  "color_fondo" => array(200,200,200),
								   "color_texto" => array(0,0,0),
								   "anchos_col"  => $la_anchos_col,
								   "tamano_texto"=>9,
								   "lineas"=>1,
								   "alineacion_col"=>$la_justificaciones,
								   "margen_horizontal"=>1);
			$io_pdf->add_tabla2(-23.5,$la_titulos3,$la_opciones);

			$la_titulos[0]["<b>No. FACTURA</b>"]= "<b>No. FACTURA</b>";
			$la_titulos[0]["<b>R.I.F.</b>"]= "<b>R.I.F.</b>";
			$la_titulos[0]["<b>RAZ�N SOCIAL</b>"]= "<b>RAZON SOCIAL</b>";
			$la_titulos[0]["<b>FECHA DE EMISI�N</b>"]= "<b>FECHA DE EMISION</b>";
			$la_titulos[0]["<b>DEBITO</b>"]="<b>DEBITO</b>"; //suma de montos cobrados
			$la_titulos[0]["<b>CREDITO</b>"]= "<b>CREDITO</b>"; //suma de montos cobrados
			$la_titulos[0]["<b>MONTO*COBRAR</b>"]= "<b>MONTO*COBRAR</b>"; //suma de montos cobrados
			$io_pdf->ezSetY(575);
			$la_anchos_col = array(40,20,60,20,20,20,26);
			$la_justificaciones = array('center','center','left','center','right','right','right');
			$la_opciones = array(  "color_fondo" => array(255,255,255),
								   "color_texto" => array(0,0,0),
								   "anchos_col"  => $la_anchos_col,
								   "tamano_texto"=> 7,
								   "lineas"=>1,
								   "alineacion_col"=>$la_justificaciones,
								   "margen_horizontal"=>1);
			$io_pdf->add_tabla(-25,$la_titulos,$la_opciones);								   
			$io_pdf->add_tabla(-25,$la_datos3,$la_opciones);

			$la_datos2[0]["totales"]= "<b>TOTAL POR COBRAR Bs.</b>";
		   	$la_datos2[0]["resultados"]= '<b>'.$total.'</b>';     // SUBTOTAL
			$la_anchos_col = array(20,20);
			$la_justificaciones = array('left','right');
			$la_opciones2 = array(  "color_fondo" => array(255,255,255),
								   "color_texto" => array(0,0,0),
								   "anchos_col"  => $la_anchos_col,
								   "tamano_texto"=> 7,
								   "lineas"=>1,
								   "alineacion_col"=>$la_justificaciones,
								   "margen_horizontal"=>1);
			$io_pdf->add_tabla2(135,$la_datos2,$la_opciones2);
		}
}

if ($existe_factura==1 || $existe_cobro==1)
{
$io_pdf->ezStream();
}else{
$io_msg->message("No hay Nada que Reportar");
}


?>
