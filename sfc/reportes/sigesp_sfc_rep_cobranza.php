<?Php
/******************************************/
/* FECHA: 13/08/2007                      */
/* AUTOR: ING. ZULHEYMAR RODR�GUEZ             */
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
$io_datastore2= new class_datastore();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$io_pdf = $reporte->get_pdf();
$io_pdf->numerar_paginas(6);
            $ls_sql=$_GET["sql"];
			$ls_sql=str_replace("\\","",$ls_sql);
			$ls_sql=str_replace("/","",$ls_sql);
		    $rs_datauni=$io_sql->select($ls_sql);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros");
			}
			else
			{
			   $la_cobranza=$io_sql->obtener_datos($rs_datauni);
	        }
		      	if ($la_cobranza["estcob"][1]=='A')
				{
					$la_cobranza["estcob"][1]='ANULADA';
					$reporte->add_titulo("center",43,11,"RECIBO DE COBRO".'   ('.$la_cobranza["estcob"][1].')');
				}else{
				$reporte->add_titulo('center',10,15,"RECIBO DE COBRO");
				}
				$reporte->add_titulo("left",25,7,"RIF: ".$la_cobranza["riftie"][1].", ".$la_cobranza["dirtie"][1].utf8_decode(", 	Teléfonos: ").$la_cobranza["teltie"][1]);
				$reporte->add_titulo("right",25,7,"No. ".$la_cobranza["numcob"][1]);
				$ls_fecemi="".substr($la_cobranza["feccob"][1],8,2)."/".substr($la_cobranza["feccob"][1],5,2)."/".substr($la_cobranza["feccob"][1],0,4)."";
				$reporte->add_titulo("right",28,7,"Fecha: ".$ls_fecemi);
				$reporte->add_titulo("left",32,7,"Cliente: ".$la_cobranza["razcli"][1]);
				$reporte->add_titulo("left",35,7,utf8_decode("Dirección: ").$la_cobranza["dircli"][1]);
				$reporte->add_titulo("left",41,7,utf8_decode("Teléfonos: ").$la_cobranza["telcli"][1]);
				$reporte->add_titulo("left",38,7,"Rif: ".$la_cobranza["cedcli"][1]);
				$reporte->add_titulo("right",40,7,"Tienda: ".$la_cobranza["dentie"][1]);
				$reporte->add_titulo("right",43,7,"Asesor: ".$la_cobranza["nomusu"][1]);
                $li_cuotas=(count($la_cobranza,COUNT_RECURSIVE)/count($la_cobranza)) - 1;
			    $la_total=0;
				for($i=0;$i<$li_cuotas;$i++)
				{
		  	     $la_datos[$i]["<b>Nro. Factura</b>"]= $la_cobranza["numfac"][$i+1];
				 $ls_fecha="".substr($la_cobranza["fecemi"][$i+1],8,2)."/".substr($la_cobranza["fecemi"][$i+1],5,2)."/".substr($la_cobranza["fecemi"][$i+1],0,4)."";
				 $la_datos[$i]["<b>Fecha de Emisión</b>"]= $ls_fecha;
				 $la_datos[$i]["<b>Tipo de Cancelación</b>"]= $la_cobranza["tipcancel"][$i+1];
				 if ( $la_datos[$i]["<b>Tipo de Cancelación</b>"]=="T"){
				  	$la_datos[$i]["<b>Tipo de Cancelación</b>"]="TOTAL";
				 }elseif( $la_datos[$i]["<b>Tipo de Cancelación</b>"]=="P"){
				  	$la_datos[$i]["<b>Tipo de Cancelación</b>"]="PARCIAL";
				 }
				 $la_datos[$i]["<b>Monto por Cancelar</b>"]= number_format($la_cobranza["montoxcancel"][$i+1],2, ',', '.');
				 $la_datos[$i]["<b>Monto Cancelado</b>"]= number_format($la_cobranza["moncancel"][$i+1],2, ',', '.');

				 $la_total= $la_total+$la_cobranza["moncancel"][$i+1];
				 $la_total2=$la_cobranza["montoxcancel"][$i+1]-	$la_total;
				 $la_total2=number_format($la_total2,2, ',', '.');
				 $la_datos[$i]["<b>Resta por Pagar</b>"]=$la_total2;
				}
				

				$la_total= $la_total+$la_cobranza["moncancel"][$i+1];
				$la_total2=$la_cobranza["montoxcancel"][$i+1]-	$la_total;
				$la_total2=number_format($la_total2,2, ',', '.');
				$la_datos[$i]["<b>Resta por Pagar</b>"]=$la_total2;
				
				
				$la_total=number_format($la_total,2, ',', '.');
				$io_pdf->ezSetY(570);
        		$la_anchos_col = array(185);
				$la_justificaciones = array('center');
				$la_titulo[0]["1"]="<b>Facturas Cobradas</b>";
				$la_opc = array(  "color_fondo" => array(229,229,229),
									   "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col,
									   "tamano_texto"=>9,
									   "lineas"=>1,
									   "alineacion_col"=>$la_justificaciones,
									   "margen_horizontal"=>1);
				$io_pdf->add_tabla2(-11,$la_titulo,$la_opc);
				$la_titulos[0]["<b>Nro. Factura</b>"]="<b>Nro. Factura</b>";
				$la_titulos[0]["<b>Fecha de Emisión</b>"]= utf8_decode("<b>Fecha de Emisión</b>");
				$la_titulos[0]["<b>Tipo de Cancelación</b>"]= utf8_decode("<b>Tipo de Cancelación</b>");				
				$la_titulos[0]["<b>Monto por Cancelar</b>"]="<b>Monto por Cancelar</b>";
				$la_titulos[0]["<b>Monto Cancelado</b>"]= "<b>Monto Cancelado</b>";
				$la_titulos[0]["<b>Resta por Pagar</b>"]= "<b>Restante</b>";
				$la_anchos_col = array(40,30,25,30,30,30);
				$la_justificaciones = array('center','center','center','right','right','right');
				$la_opciones = array(  "color_fondo" => array(255,255,255),
									   "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col,
									   "tamano_texto"=> 7,
									   "lineas"=>1,
									   "alineacion_col"=>$la_justificaciones,
									   "margen_horizontal"=>1);
				$io_pdf->add_tabla(-12.5,$la_titulos,$la_opciones);
				$io_pdf->add_tabla(-12.5,$la_datos,$la_opciones);

				$ls_cadena3="SELECT i.numinst,i.obsins,i.fecins,i.monto ,i.codforpag,i.ctaban,fp.denforpag,fp.metforpag,b.codban,b.nomban ".
						    "FROM   sfc_instpagocob i,sfc_formapago fp,scb_banco b,sfc_cobro_cliente c " .
						    "WHERE i.codban=b.codban AND i.codforpag=fp.codforpag AND i.numcob=c.numcob AND i.numcob='".$la_cobranza["numcob"][1]."'";

				$arr_detints=$io_sql->select($ls_cadena3);
				if($arr_detints==false&&($io_sql->message!=""))
				{
					$io_msg->message("!No hay registros de instrumentos!");
				}
				else
				{
					if($row=$io_sql->fetch_row($arr_detints))
					{
						$la_inst=$io_sql->obtener_datos($arr_detints);
						$io_datastore->data=$la_inst;
						$totrow=$io_datastore->getRowCount("numinst");
						for($li_i=0;$li_i<$totrow;$li_i++)
						{
							$la_datos3[$li_i]["<b>Nro. Instrumento</b>"]=$io_datastore->getValue("numinst",$li_i+1);
							$la_datos3[$li_i]["<b>Banco</b>"]=$io_datastore->getValue("nomban",$li_i+1);							
							$la_datos3[$li_i]["<b>Forma de Pago</b>"]=$io_datastore->getValue("denforpag",$li_i+1);
							$la_datos3[$li_i]["<b>Observación</b>"]=$io_datastore->getValue("obsins",$li_i+1);
							$la_datos3[$li_i]["<b>Fecha de Pago</b>"]=$io_datastore->getValue("fecins",$li_i+1);
							$ls_fecha2="".substr($la_datos3[$li_i]["<b>Fecha de Pago</b>"],8,2)."/".substr($la_datos3[$li_i]["<b>Fecha de Pago</b>"],5,2)."/".substr($la_datos3[$li_i]["<b>Fecha de Pago</b>"],0,4)."";
							$la_datos3[$li_i]["<b>Fecha de Pago</b>"]=$ls_fecha2;
							$la_datos3[$li_i]["<b>Monto Cancelado</b>"]=number_format($io_datastore->getValue("monto",$li_i+1),2,',','.');

						}

						$ls_cad4 = " SELECT comprobante, monret FROM sfc_facturaretencion WHERE  numcob ='".$la_cobranza["numcob"][1]."' ";
						$arr_detints2=$io_sql->select($ls_cad4);
						if($row2=$io_sql->fetch_row($arr_detints2)){
							$la_inst2=$io_sql->obtener_datos($arr_detints2);
							$io_datastore2->data=$la_inst2;
							$totrow2=$io_datastore2->getRowCount("comprobante");
							for($li_i2=0;$li_i2<$totrow2;$li_i2++){

								$la_datos3[$li_i]["<b>Nro. Instrumento</b>"]=$io_datastore2->getValue("comprobante",$li_i2+1);
								$la_datos3[$li_i]["<b>Banco</b>"]="N/A";
								$la_datos3[$li_i]["<b>Forma de Pago</b>"]="Comprobante Retención";
								$la_datos3[$li_i]["<b>Observación</b>"]="";
								$la_datos3[$li_i]["<b>Fecha de Pago</b>"]=$ls_fecha2;
								$la_datos3[$li_i]["<b>Monto Cancelado</b>"]=number_format($io_datastore2->getValue("monret",$li_i2+1),2,',','.');

								$li_i++;
							}
						}

					}
				}
				$io_pdf->add_lineas(2);
        		$la_anchos_col5 = array(185);
				$la_justificaciones5 = array('center');

				$la_titulos5[0]["1"]="<b>Instrumento de Pago</b>";


				$la_opciones5 = array(  "color_fondo" => array(200,200,200),
									   "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col5,
									   "tamano_texto"=> 9,
									   "lineas"=>1,
									   "alineacion_col"=>$la_justificaciones5,
									   "margen_horizontal"=>1);
				$io_pdf->add_tabla2(-10.5,$la_titulos5,$la_opciones5);
				$la_anchos_col = array(40,30,25,30,30,30);
				$la_titulos3[0]["<b>Nro. Instrumento</b>"]="<b>Nro. Instrumento</b>";
				$la_titulos3[0]["<b>Banco</b>"]="<b>Banco</b>";
				$la_titulos3[0]["<b>Forma de Pago</b>"]="<b>Forma de Pago</b>";
				$la_titulos3[0]["<b>Observación</b>"]=utf8_decode("<b>Observación</b>");
				$la_titulos3[0]["<b>Fecha de Pago</b>"]="<b>Fecha de Pago</b>";
				$la_titulos3[0]["<b>Monto Cancelado</b>"]="<b>Monto Cancelado</b>";
				$la_justificaciones = array('center','center','center','center','center','right');
				$la_opciones4 = array(  "color_fondo" => array(255,255,255),
									   "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col,
									   "tamano_texto"=> 7,
									   "lineas"=>1,
									   "alineacion_col"=>$la_justificaciones,
									   "margen_horizontal"=>1);
				$io_pdf->add_tabla(-12,$la_titulos3,$la_opciones4);
				$io_pdf->add_tabla(-12,$la_datos3,$la_opciones4);
				/************************************************************************************************************************/

				// datos para la primera columna
			   //$la_titulos2[0]["1"]="";
			    $la_datos2[0]["totales"]= "<b>Total Cancelado Bs.</b>";

			   // datos para la segunda columna
			   //$la_titulos2[0]["2"]="";
			  	$la_datos2[0]["resultados"]= '<b>'.$la_total.'</b>';     // SUBTOTAL

			  //print $li_cuotas;
				//$io_pdf->ezSetY(400-$li_cuotas*10);
				$la_anchos_col = array(30,30);
				$la_justificaciones = array('left','right');
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
				$io_pdf->add_tabla2(114.5,$la_datos2,$la_opciones2);

  				//$reporte->add_titulo("left",200,8,"Observaci�n: ".$la_cobranza["descob"][1]);

/***********************************************************************************************************************/



/*	if ($ls_fecemi<>"%%%%")
	{
	  $reporte->add_titulo("right",50,7,"Fecha desde: ".$ls_fecemi." Hasta ".$ls_fecemi2);
	}
	else
	{
	$reporte->add_titulo("right",50,7,"Fecha desde: ".$la_datos[1]["fecemi"]." Hasta ".$la_datos[$li_cuotas-1]["fecemi"]." ");
	}*/


/*******************************************************************************************************************************/

/*$ls_contenido="Prueba reporte";
$reporte->cuerpo_reporte($ls_contenido);*/


$io_pdf->ezStream();


?>