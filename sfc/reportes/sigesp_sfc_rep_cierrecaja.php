<?Php
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
$io_sql2=new class_sql($io_connect);
$io_sql3=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$io_pdf = $reporte->get_pdf();
$io_pdf->numerar_paginas(6);
/******************************************************************************************************************************/
$reporte->add_titulo("center",10,11,"              CIERRE DE CAJA ".$_SESSION["ls_nomtienda"]);
$reporte->add_titulo("center",14,11,"Por Cajero ".$_SESSION["la_logusr"]);
$ls_fecha=date('d/m/Y');
$reporte->add_titulo("left",25,7,"Fecha emisi�n: ".$ls_fecha);
$ls_feccie=substr($_GET['ls_feccie'],8,2)."-".substr($_GET['ls_feccie'],5,2)."-".substr($_GET['ls_feccie'],0,4)."  ".substr($_GET['ls_feccie'],10,9);
//print $ls_feccie;
$reporte->add_titulo("right",25,7,"Fecha de Cierre: ".$ls_feccie);
$ls_totalefectivo=0;
$ls_totalcheque=0;
$ls_totalcredito=0;
$ls_totaldeposito=0;
$ls_totaldebito=0;
$ls_sqlf=$_GET['sql_f'];
$ls_sqlf=str_replace("\\","",$ls_sqlf);
//$ls_sqlf=str_replace("/","",$ls_sqlf);
//print $ls_sqlf;
$rs_dataunifac=$io_sql->select($ls_sqlf);
if($rs_dataunifac==false&&($io_sql->message!=""))
	{
		$io_msg->message("No hay registros");
	}
else
	{
	   $la_factura=$io_sql->obtener_datos($rs_dataunifac);
	}
	if ($la_factura)
	 {
		$li_cuotasfac=(count($la_factura,COUNT_RECURSIVE)/count($la_factura)) - 1;
		$la_total=0;
		$la_totnot=0;
		for($i=0;$i<$li_cuotasfac;$i++)
			{
				 $la_datosfac[$i]["<b>No. Factura</b>"]= $la_factura["numfac"][$i+1];
				 $la_datosfac[$i]["<b>R.I.F.</b>"]= $la_factura["cedcli"][$i+1];
				 $la_datosfac[$i]["<b>Nombre Cliente</b>"]= $la_factura["razcli"][$i+1];
				 $la_datosfac[$i]["<b>Forma de pago</b>"]= $la_factura["denforpag"][$i+1];
				 $ls_codforpagfac= $la_factura["codforpag"][$i+1];
				 if ($ls_codforpagfac=='01')
				 {
				 	$ls_totalefectivo=$ls_totalefectivo+ $la_factura["montofac"][$i+1];
				 }
				 else if ($ls_codforpagfac=='02')
				 {
				 	$ls_totalcheque=$ls_totalcheque+ $la_factura["montofac"][$i+1];
				 }
				 else if ($ls_codforpagfac=='03')
				 {
				 if (-$la_factura["montopar"][$i+1]<$la_factura["montofac"][$i+1])
				 {
				 	$ls_totalcredito=$ls_totalcredito+ ($la_factura["montofac"][$i+1]+$la_factura["montopar"][$i+1]);
				 }
				 else
				 {
				 	$ls_totalcredito=$ls_totalcredito+ ($la_factura["montofac"][$i+1]);
				 }
				 }
				 else if ($ls_codforpagfac=='05')
				 {
				 	$ls_totaldeposito=$ls_totaldeposito+ $la_factura["montofac"][$i+1];
				 }
				 else if ($ls_codforpagfac=='07' or $ls_codforpagfac=='08')
				 {
				 	$ls_totaldebito=$ls_totaldebito+ $la_factura["montofac"][$i+1];
				 }
				 else
				 {}
				 if ($ls_codforpagfac=='03' and -$la_factura["montopar"][$i+1]<$la_factura["montofac"][$i+1])
				 {
					 $la_datosfac[$i]["<b>Monto</b>"]= number_format($la_factura["montofac"][$i+1]+$la_factura["montopar"][$i+1],2, ',', '.');
					 $la_total= $la_total+$la_factura["montofac"][$i+1]+$la_factura["montopar"][$i+1];
				 }
				 else
				 {
					 $la_datosfac[$i]["<b>Monto</b>"]= number_format($la_factura["montofac"][$i+1],2, ',', '.');
					 $la_total= $la_total+$la_factura["montofac"][$i+1];
				 }

				 $la_totalfac=number_format($la_total,2,',','.');
			}
		$io_pdf->ezSetY(615);
		$la_datostitfac['0']["<b>PRODUCTOS VENDIDOS</b>"]='<b>FACTURAS CERRADAS</b>';
						$la_anchos_col = array(175);
						$la_justificaciones = array('center');
						$la_opciones = array(  "color_fondo" => array(229,229,229),
											   "color_texto" => array(0,0,0),
											   "anchos_col"  => $la_anchos_col,
											   "tamano_texto"=> 8,
											   "lineas"=>1,
											   "alineacion_col"=>$la_justificaciones,
											   "margen_horizontal"=>1);
						$io_pdf->add_tabla3(-10,$la_datostitfac,$la_opciones);
						$io_pdf->add_lineas(0.1);
		$la_anchos_col = array(40,20,70,25,20);
		//$io_pdf->add_lineas(1);
		$la_anchos_col = array(40,20,70,25,20);
		$la_justificaciones = array('center','center','left','center','right');
		$la_opciones = array(  "color_fondo" => array(229,229,229),
							   "color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 7,
							   "lineas"=>1,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>1);
		$io_pdf->add_tabla(-10,$la_datosfac,$la_opciones);
		$la_datostotfac[0]["totales"]= "<b>Total Facturas Bs.</b>";
		$la_datostotfac[0]["resultados"]= '<b>'.$la_totalfac.'</b>';     // SUBTOTAL
		$la_anchos_col = array(35,30);
		$la_justificaciones = array('left','right');
		$la_opciones2 = array(  "color_fondo" => array(229,229,229),
							   "color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 7,
							   "lineas"=>0,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>1);
		$io_pdf->add_tabla2(100,$la_datostotfac,$la_opciones2);
	}//FIN IF $LA_FACTURA
	unset($la_datostitfac);
	unset($la_datostotfac);
	unset($la_datosfac);
//////////////////////////////////////////////////////////////////
$ls_sqld=$_GET['ls_sql_dev'];
$ls_sqld=str_replace("\\","",$ls_sqld);
$rs_dataunidev=$io_sql2->select($ls_sqld);
if ($rs_dataunidev==false &&($io_sql->message!=""))
	{
		//$io_msg->message("No hay registros");
	}
else
	{

	   $la_devolucion=$io_sql2->obtener_datos($rs_dataunidev);
	}

if ($la_devolucion)
	 {
		$li_cuotasdev=(count($la_devolucion,COUNT_RECURSIVE)/count($la_devolucion)) - 1;
		$la_totaldev=0;
		$la_totnotdev=0;
		for($i=0;$i<$li_cuotasdev;$i++)
			{
				 $la_datosdev[$i]["<b>No. Factura</b>"]=$la_devolucion["numnota"][$i+1];
				 $la_datosdev[$i]["<b>R.I.F.</b>"]= $la_devolucion["cedula"][$i+1];
				 $la_datosdev[$i]["<b>Nombre Cliente</b>"]= $la_devolucion["nombre"][$i+1];
				 $la_datosdev[$i]["<b>Forma de pago</b>"]= $la_devolucion["dennot"][$i+1];
				 $la_datosdev[$i]["<b>Monto Devoluci�n</b>"]= number_format($la_devolucion["montodev"][$i+1],2, ',', '.');
				 $la_totaldev= $la_totaldev+$la_devolucion["montodev"][$i+1];
				 $la_totaldev2=number_format($la_totaldev,2,',','.');
			}
		if ($la_totaldev!=0)
		{
		$io_pdf->add_lineas(1);
		$la_datostitdev['0']["<b>PRODUCTOS VENDIDOS</b>"]='<b>FACTURAS CON DEVOLUCIONES ( CANCELADAS POR CAJA )</b>';
						$la_anchos_col = array(175);
						$la_justificaciones = array('center');
						$la_opciones = array(  "color_fondo" => array(229,229,229),
											   "color_texto" => array(0,0,0),
											   "anchos_col"  => $la_anchos_col,
											   "tamano_texto"=> 8,
											   "lineas"=>1,
											   "alineacion_col"=>$la_justificaciones,
											   "margen_horizontal"=>1);
						$io_pdf->add_tabla3(-10,$la_datostitdev,$la_opciones);
						$io_pdf->add_lineas(0.1);
		$la_anchos_col = array(40,20,50,35,30);
		$la_justificaciones = array('center','center','left','center','right');
		$la_opciones = array(  "color_fondo" => array(229,229,229),
							   "color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 7,
							   "lineas"=>1,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>1);
		$io_pdf->add_tabla(-10,$la_datosdev,$la_opciones);
		$la_datostotdev[0]["totales"]= "<b>Total Devoluciones Bs.</b>";
		$la_datostotdev[0]["resultados"]= '<b>'.$la_totaldev2.'</b>';     // SUBTOTAL
		$la_anchos_col = array(35,30);
		$la_justificaciones = array('left','right');
		$la_opciones2 = array(  "color_fondo" => array(229,229,229),
							   "color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 7,
							   "lineas"=>1,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>1);
		$io_pdf->add_tabla2(100,$la_datostotdev,$la_opciones2);
	}
	}//FIN IF $LA_DEVOLUCION
	unset($la_datostitdev);
	unset($la_datostotdev);
	unset($la_datosdev);
	$ls_sqlc=$_GET['sql_c'];
	$ls_sqlc=str_replace("\\","",$ls_sqlc);
	$rs_dataunicob=$io_sql3->select($ls_sqlc);
	if($rs_dataunicob==false&&($io_sql->message!=""))
		{
			$io_msg->message("No hay registros");
		}
	else
		{
		   $la_cobro=$io_sql3->obtener_datos($rs_dataunicob);
		}
	if ($la_cobro and !$la_factura and !la_devolucion)
	{
		$io_pdf->ezSetY(550);
		$li_cuotascob=(count($la_cobro,COUNT_RECURSIVE)/count($la_cobro)) - 1;
		$la_totalcob=0;
		$la_totnotcob=0;
  		$li_totrows = $io_sql3->num_rows($rs_dataunicob);
		for ($i=0; $i<$li_totrows;$i++)
		{
			 $la_datoscob[$i]["<b>No. Cobro*Factura</b>"]= $la_cobro["numcob"][$i+1];
			 $la_datoscob[$i]["<b>R.I.F.</b>"]= $la_cobro["cedcli"][$i+1];
			 $la_datoscob[$i]["<b>Nombre Cliente</b>"]= $la_cobro["razcli"][$i+1];
			 $la_datoscob[$i]["<b>Forma de pago</b>"]= $la_cobro["denforpag"][$i+1]." Nro. ".$la_cobro["numinst"][$i+1];
			 $ls_codforpagcob= $la_cobro["codforpagcob"][$i+1];
			 if ($ls_codforpagcob=='01')
			 {
			     $ls_totalefectivo=$ls_totalefectivo+$la_cobro["monto"][$i+1];
			 }
			 else if ($ls_codforpagcob=='02')
			 {
			     $ls_totalcheque=$ls_totalcheque+$la_cobro["monto"][$i+1];
			 }
			 else if ($ls_codforpagcob=='03')
			 {
			     $ls_totalcredito=$ls_totalcredito+$la_cobro["monto"][$i+1];
			 }
			 else if ($ls_codforpagcob=='05')
			 {
			     $ls_totaldeposito=$ls_totaldeposito+$la_cobro["monto"][$i+1];
			 }
			 else if ($ls_codforpagcob=='07' or $ls_codforpagcob=='08')
			 {
			     $ls_totaldebito=$ls_totaldebito+$la_cobro["monto"][$i+1];
			 }
			 else
			 {}
			 $la_datoscob[$i]["<b>Monto</b>"]=number_format($la_cobro["monto"][$i+1],2, ',', '.');
			 $la_totalcob= $la_totalcob+$la_cobro["monto"][$i+1];
			 $la_totalcob2=number_format($la_totalcob,2,',','.');
		}
		if ($la_totalcob!=0)
		{
			$io_pdf->add_lineas(1);
			$la_datostitcob['0']["<b>PRODUCTOS VENDIDOS</b>"]='<b>FACTURAS COBRADAS</b>';
							$la_anchos_col = array(175);
							$la_justificaciones = array('center');
							$la_opciones = array(  "color_fondo" => array(229,229,229),
												   "color_texto" => array(0,0,0),
												   "anchos_col"  => $la_anchos_col,
												   "tamano_texto"=> 8,
												   "lineas"=>1,
												   "alineacion_col"=>$la_justificaciones,
												   "margen_horizontal"=>1);
							$io_pdf->add_tabla3(-10,$la_datostitcob,$la_opciones);
							$io_pdf->add_lineas(0.1);
			$la_anchos_col = array(40,20,50,35,30);
			$la_justificaciones = array('center','center','left','center','right');
			$la_opciones = array(  "color_fondo" => array(229,229,229),
								   "color_texto" => array(0,0,0),
								   "anchos_col"  => $la_anchos_col,
								   "tamano_texto"=> 7,
								   "lineas"=>1,
								   "alineacion_col"=>$la_justificaciones,
								   "margen_horizontal"=>1);
			$io_pdf->add_tabla(-5,$la_datoscob,$la_opciones);
			if ($la_totalcob2!="")
			{
				$la_datostotcob[0]["totales"]= "<b>Total Cobros Bs.</b>";
				$la_datostotcob[0]["resultados"]= '<b>'.$la_totalcob2.'</b>';     // SUBTOTAL
				$la_anchos_col = array(35,30);
				$la_justificaciones = array('left','right');
				$la_opciones2 = array(  "color_fondo" => array(229,229,229),
									   "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col,
									   "tamano_texto"=> 7,
									   "lineas"=>1,
									   "alineacion_col"=>$la_justificaciones,
									   "margen_horizontal"=>1);
				$io_pdf->add_tabla2(100,$la_datostotcob,$la_opciones2);
			}
	}
	unset($la_datostitcob);
	unset($la_datostotcob);
	unset($la_datoscob);
	}//fin del existe cobro y no existe factura
	elseif ($la_cobro and $la_devolucion)
	{
	   $li_cuotascob=count($la_cobro,COUNT_RECURSIVE);
	   $la_totalcob=0;
	   $la_totnotcob=0;
	   $la_totalcob2=0;
	   $li_totrows = $io_sql3->num_rows($rs_dataunicob);
		for ($i=0; $i<$li_totrows;$i++)
		{

			 $la_datoscob[$i]["<b>No. Cobro*Factura</b>"]= $la_cobro["numcob"][$i+1];
			 $la_datoscob[$i]["<b>R.I.F.</b>"]= $la_cobro["cedcli"][$i+1];
			 $la_datoscob[$i]["<b>Nombre Cliente</b>"]= $la_cobro["razcli"][$i+1];
			 $la_datoscob[$i]["<b>Forma de pago</b>"]= $la_cobro["denforpag"][$i+1]." Nro. ".$la_cobro["numinst"][$i+1];
			 $ls_codforpagcob=$la_cobro["codforpagcob"][$i+1];
			 if ($ls_codforpagcob=='01')
			 {
			    $ls_totalefectivo=$ls_totalefectivo+ $la_cobro["monto"][$i+1];
			 }
			 else if ($ls_codforpagcob=='02')
			 {
			    $ls_totalcheque=$ls_totalcheque+ $la_cobro["monto"][$i+1];
			 }
			 else if ($ls_codforpagcob=='03')
			 {
			    $ls_totalcredito=$ls_totalcredito+ $la_cobro["monto"][$i+1];
			 }
			 else if ($ls_codforpagcob=='05')
			 {
			    $ls_totaldeposito=$ls_totaldeposito+ $la_cobro["monto"][$i+1];
			 }
			 else if ($ls_codforpagcob=='07' or $ls_codforpagcob=='08')
			 {
			    $ls_totaldebito=$ls_totaldebito+ $la_cobro["monto"][$i+1];
			 }
			 else
			 {}
			 $la_datoscob[$i]["<b>Monto</b>"]= number_format($la_cobro["monto"][$i+1],2, ',', '.');
			 $la_totalcob= $la_totalcob+$la_cobro["monto"][$i+1];
			 $la_totalcob2=number_format($la_totalcob,2,',','.');

		}
		if ($la_totalcob!=0)
		{
		$io_pdf->add_lineas(1);
		$la_datostitcob['0']["<b>PRODUCTOS VENDIDOS</b>"]='<b>FACTURAS COBRADAS</b>';
						$la_anchos_col = array(175);
						$la_justificaciones = array('center');
						$la_opciones = array(  "color_fondo" => array(229,229,229),
											   "color_texto" => array(0,0,0),
											   "anchos_col"  => $la_anchos_col,
											   "tamano_texto"=> 8,
											   "lineas"=>1,
											   "alineacion_col"=>$la_justificaciones,
											   "margen_horizontal"=>1);
						$io_pdf->add_tabla3(-10,$la_datostitcob,$la_opciones);
						$io_pdf->add_lineas(0.1);
		$la_anchos_col = array(40,20,50,35,30);
		$la_justificaciones = array('center','center','left','center','right');
		$la_opciones = array(  "color_fondo" => array(229,229,229),
							   "color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 7,
							   "lineas"=>1,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>1);
		$io_pdf->add_tabla(-10,$la_datoscob,$la_opciones);
		if ($la_totalcob2!="")
		{
			$la_datostotcob[0]["totales"]= "<b>Total Cobros Bs.</b>";
			$la_datostotcob[0]["resultados"]= '<b>'.$la_totalcob2.'</b>';     // SUBTOTAL
			$la_anchos_col = array(35,30);
			$la_justificaciones = array('left','right');
			$la_opciones2 = array(  "color_fondo" => array(229,229,229),
								   "color_texto" => array(0,0,0),
								   "anchos_col"  => $la_anchos_col,
								   "tamano_texto"=> 7,
								   "lineas"=>1,
								   "alineacion_col"=>$la_justificaciones,
								   "margen_horizontal"=>1);
			$io_pdf->add_tabla2(100,$la_datostotcob,$la_opciones2);
			}
		$la_anchos_col = array(40,20,50,35,30);
		}
	}//fin del if existecobro
	unset($la_datostitcob);
	unset($la_datostotcob);
	unset($la_datoscob);
	//Informaci�n de la Factura//
	if($la_factura or $la_cobro or $la_devolucion)
	{
				//TOTALES GENERALES//
				//print 'total: '.$la_total.' totalcob: '.$la_total3.'la_totalnotdev: '.$la_totnotdev.'$la_totnotcob: '.$la_totnotcob.'$la_totnot: '.$la_totnot.'$la_totaldev2: '.$la_totaldev;
				$total_caja=$ls_totalefectivo-$la_totaldev;
				$total_general=($la_total+$la_totalcob)-$la_totaldev;

				$total_caja=number_format($total_caja,2,',','.');
				$ls_total_deposito=$ls_totalefectivo+$ls_totalcheque;
				//$ls_total_deposito=number_format($total_caja,2,',','.');
				$la_datostot[0]["totales"]= "<b>Total Efectivo Bs.</b>";
				$la_datostot[1]["totales"]= "<b>Total Cheques Bs.</b>";
				$la_datostot[2]["totales"]= "<b>Total Notas de Cr�dito Bs.</b>";
				$la_datostot[3]["totales"]= "<b>Total Depositos Bs.</b>";
				$la_datostot[4]["totales"]= "<b>Total Notas de Debito Bs.</b>";
				$la_datostot[5]["totales"]= "<b>Total Devol. Efectivo Bs.</b>";
				$la_datostot[6]["totales"]= "<b>Total General Bs.</b>";
				$la_datostot[7]["totales"]= "<b>Total Efectivo en Caja Bs.</b>";
				$la_datostot[8]["totales"]= "<b> MONTO TOTAL A DEPOSITAR Bs. </b>";

			   	$la_datostot[0]["resultados"]= '<b>'.number_format($ls_totalefectivo,2,',','.').'</b>';     // SUBTOTAL
				$la_datostot[1]["resultados"]= '<b>'.number_format($ls_totalcheque,2,',','.').'</b>';     // SUBTOTAL
			   	$la_datostot[2]["resultados"]= '<b>'.number_format($ls_totalcredito,2,',','.').'</b>';     // SUBTOTAL
				$la_datostot[3]["resultados"]= '<b>'.number_format($ls_totaldeposito,2,',','.').'</b>';     // SUBTOTAL
			   	$la_datostot[4]["resultados"]= '<b>'.number_format($ls_totaldebito,2,',','.').'</b>';     // SUBTOTAL
				$la_datostot[5]["resultados"]= '<b>- '.number_format($la_totaldev,2,',','.').'</b>';     // SUBTOTAL
				$la_datostot[6]["resultados"]= '<b>'.number_format($total_general,2,',','.').'</b>';     // SUBTOTAL
				$la_datostot[7]["resultados"]= '<b>'.$total_caja.'</b>';     // SUBTOTAL
				$la_datostot[8]["resultados"]= '<b>'.number_format($ls_total_deposito,2,',','.').'</b>';     // TOTAL DEPOSITAR



				$io_pdf->add_lineas(1);
				$la_anchos_col = array(45,30);
				$la_justificaciones = array('left','right');
				$la_opciones2 = array(  "color_fondo" => array(229,229,229),
									   "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col,
									   "tamano_texto"=> 7,
									   "lineas"=>2,
									   "alineacion_col"=>$la_justificaciones,
									   "margen_horizontal"=>1);
				$io_pdf->add_tabla3(90,$la_datostot,$la_opciones2);
	unset($la_datostot);
	}
	else
	{
		?>
			<script>alert ("No hay Nada que Reportar");
			close();
			</script>
		<?php
	}
	$io_pdf->ezStream();
    ?>
