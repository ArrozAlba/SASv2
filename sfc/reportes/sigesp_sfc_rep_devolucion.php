<?Php
/******************************************/
/* FECHA: 30/01/2008                      */
/* AUTOR: ING. ZULHEYMAR RODR�GUEZ        */
/******************************************/

session_start();
require_once("sigesp_sfc_c_reportesdev.php");
$reporte = new sigesp_sfc_c_reportesdev('LETTER','portrait','REPORTE 1');
$io_pdf  = new class_pdf('LETTER','portrait');
$io_pdf->selectFont('../../../shared/ezpdf/fonts/Helvetica.afm');
//$io_pdf->set_margenes(10,10,10,10);
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_fecha.php");
$io_fecha=new class_fecha();
$io_datastore= new class_datastore();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$io_pdf = $reporte->get_pdf();
//$io_pdf->numerar_paginas(6);




            $ls_sql=$_GET["sql"];
            $ls_sql=str_replace("\\","",$ls_sql);
			$ls_sql=str_replace("/","",$ls_sql);
			//print $ls_sql;
		    $rs_datauni=$io_sql->select($ls_sql);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros");
			}
			else
			{
			   $la_devolucion=$io_sql->obtener_datos($rs_datauni);
	        }

	        	//$reporte->add_titulo(40,10,12,"NOTA DE CREDITO");


				//$reporte->add_titulo(60,7,7,"Agrotienda: ".$la_devolucion["dentie"][1]);
				//$reporte->add_titulo("left",7,7,"RIF: ".$la_devolucion["riftie"][1].", ".$la_devolucion["dirtie"][1].",	Telefonos: ".$la_devolucion["teltie"][1]);

                                $reporte->add_titulo(73,12,12,"<b>SERIE: </b> ".$_SESSION["ls_serdev"]);
				$reporte->add_titulo(103,12,12,"<b>NOTA DE CREDITO:  </b>  ".substr($la_devolucion["coddev"][1],6,21));
				//$reporte->add_titulo(73,18,8,"<b>LUGAR DE EMISION:</b>    ".$la_devolucion["dirtie"][1]);

				//$reporte->add_titulo(100,13,9,"Serie: ".$_SESSION["ls_serdev"]);
				//$reporte->add_titulo("right",13,9,"No. ".substr($la_devolucion["coddev"][1],6,21));
				$ls_fecha="".substr($la_devolucion["fecdev"][1],8,2)."/".substr($la_devolucion["fecdev"][1],5,2)."/".substr($la_devolucion["fecdev"][1],0,4)."";
                                $reporte->add_titulo(157,18,9,"<b>FECHA:   </b>  ".$ls_fecha);
				//$reporte->add_titulo("right",16,9,"Fecha : ".$ls_fecha);

				/*$reporte->add_titulo("left",22,9,"Cliente: ".$la_devolucion["razcli"][1]);
				$reporte->add_titulo("left",25,9,"Direccion: ".$la_devolucion["dircli"][1]);
				$reporte->add_titulo("left",28,9,"Telefonos: ".$la_devolucion["telcli"][1]);
				$reporte->add_titulo("left",31,9,"Rif: ".$la_devolucion["cedcli"][1]);
				$reporte->add_titulo("left",34,8,"OBSERVACION: ".$la_devolucion["obsdev"][1]);*/

                                $io_pdf->ezSetY(695);
				$la_anchos = array(190);
				$la_datosbasicos[0]["contenido"]="<b>NOMBRE Y APELLIDO O RAZON SOCIAL:</b>    ".$la_devolucion["razcli"][1];
				$la_datosbasicos[1]["contenido"]="<b>DOMICILIO FISCAL: </b>  ".$la_devolucion["dircli"][1];
				$la_justificacion = array('left');
				$la_opcion = array(  "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos,
									   "tamano_texto"=> 9,
									   "lineas"=>0,
									   "alineacion_col"=>$la_justificacion,
									   "margen_horizontal"=>1);
				$io_pdf->add_tabla(0,$la_datosbasicos,$la_opcion);
                                $li_alto_usado=$io_pdf->get_alto_usado();

                                $reporte->add_titulo("left",$li_alto_usado+1,9,"</b><b>TELEFONO:</b>".$la_devolucion["telcli"][1]."  ".$la_devolucion["cedcli"][1]);
				$reporte->add_titulo(73,$li_alto_usado+1,9,"</b><b>RIF. o C.I.: </b>    ".$la_devolucion["cedcli"][1]);

				$reporte->add_titulo("right",$li_alto_usado+3,9,"No. Factura Afectada: ".$la_devolucion["numfac"][1]);
				$ls_fechafac="".substr($la_devolucion["fecemi"][1],8,2)."/".substr($la_devolucion["fecemi"][1],5,2)."/".substr($la_devolucion["fecemi"][1],0,4)."";
				$reporte->add_titulo("right",$li_alto_usado+7,9,"Fecha Factura: ".$ls_fechafac);
				$reporte->add_titulo("right",$li_alto_usado+11,9,"Monto Factura: ".number_format($la_devolucion["monto"][1],2, ',', '.'));

                                $io_pdf->ezSetY(615);
				$la_anchos = array(193);
				$la_datosdetalles[0]["contenido"]="<b>DETALLES DE NOTA DE CREDITO </b>  ";
				$la_justificacion = array('center');
				$la_opcion = array(  "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos,
									   "tamano_texto"=> 8,
									   "lineas"=>1,
									   "alineacion_col"=>$la_justificacion,
									   "margen_horizontal"=>1);
				$io_pdf->add_tabla(0,$la_datosdetalles,$la_opcion);
				unset($la_datosdetalle,$la_opcion);

				//$reporte->add_titulo("right",41,7,"Asesor: ".$la_devolucion["nomusu"][1]);
                                $li_cuotas=(count($la_devolucion,COUNT_RECURSIVE)/count($la_devolucion)) -2;
				//$li_cuotas=(count($la_devolucion,COUNT_RECURSIVE)/count($la_devolucion)) -1;
				//$li_cuotas=$io_sql->num_rows($la_devolucion);
				//print "FILA= ".$li_cuotas;
			    $la_total=0;
			    $la_iva8=0;
                            $la_iva12=0;
                            $ls_tot8=0;
                            $ls_tot12=0;
			    $ls_exento=0;

				for($i=0;$i<=$li_cuotas;$i++)
				{
			  	     $la_datos[$i]["<b>Codigo</b>"]= $la_devolucion["codart"][$i+1];
					 $la_datos[$i]["<b>Descripcion</b>"]= $la_devolucion["denart"][$i+1];
					 $la_datos[$i]["<b>Cantidad</b>"]= number_format($la_devolucion["candev"][$i+1],2, ',', '.');
					 $la_datos[$i]["<b>Precio Unit.</b>"]= number_format($la_devolucion["precio"][$i+1],2, ',', '.');

                                         if ($la_devolucion["porimp"][$i+1]==0) {
                                             $imp = 'EXE';
                                         }else if ($la_devolucion["porimp"][$i+1]=='8.0') {
                                             $imp = '8%';
                                         }else if ($la_devolucion["porimp"][$i+1]=='12.0') {
                                             $imp = '12%';
                                         }

                                       $la_datos[$i]["<b>IVA</b>"]= $imp;
                                       $la_datos[$i]["<b>Sub-total</b>"]=number_format($la_devolucion["precio"][$i+1]*$la_devolucion["candev"][$i+1],2, ',', '.');
				       $la_total= $la_total+$la_devolucion["precio"][$i+1]*$la_devolucion["candev"][$i+1];
					
					if ($la_devolucion["porimp"][$i+1]==0)
					{	$ls_exento=$la_devolucion["precio"][$i+1]*$la_devolucion["candev"][$i+1];;
						//print $ls_exento;

					}else if ($la_devolucion["porimp"][$i+1]=='8.0') {
                                            $ls_tot8=$la_devolucion["precio"][$i+1]*$la_devolucion["candev"][$i+1];;
                                            
                                        }else if ($la_devolucion["porimp"][$i+1]=='12.0') {
                                            $ls_tot12=$la_devolucion["precio"][$i+1]*$la_devolucion["candev"][$i+1];;
                                            
                                        }
//print $la_iva;
				}
				$ls_base=$la_total-$ls_exento;
				$la_total=number_format($la_total,2, ',', '.');
				$la_iva=number_format($la_iva,2, ',', '.');
				$ls_exento=number_format($ls_exento,2, ',', '.');

				$io_pdf->ezSetY(600);
				$la_anchos_col = array(42,70,17,20,20,25);
				$la_justificaciones = array('center','left','right','right','center','right');
				$la_opciones = array(                      'showHeadings'=>1, // Mostrar encabezados
                                                                           'width'=>550, // Ancho de la tabla
                                                                           "color_fondo" => array(229,229,229),
									   "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col,
									   "tamano_texto"=> 8,
									   "lineas"=>0,
									   "alineacion_col"=>$la_justificaciones,
									   "margen_horizontal"=>1);
				/*$la_columnas=array('codart'=>utf8_decode('<b>Codigo:</b>'),
						   'denart'=>utf8_decode('<b>Denominación:</b>'),
						   'candev'=>'<b>Cantidad:</b>',
						   '<b>Sub-total</b>'=>'<b>Sub-total</b>');*/

                                //$io_pdf->add_tabla(-5,$la_datos,$la_opciones);
                                $io_pdf->ezTable($la_datos,'','',$la_opciones);

                                $la_iva8=number_format(($ls_tot8*(0.08)),2, ',', '.');
                                $la_iva12=number_format(($ls_tot12*(0.12)),2, ',', '.');

				$io_pdf->ezSetY(328);
                                
 				$la_datos2[0]["totales"]= "<b>EXENTO</b>";
 				$la_datos2[1]["totales"]= "<b>SUB-TOTAL</b>";
			    $la_datos2[2]["totales"]= '<b>I.V.A (8%) Sobre: Bs.'.$ls_tot8.'</b>';
                            $la_datos2[3]["totales"]= '<b>I.V.A (12%) Sobre: Bs.'.$ls_tot12.'</b>';
			    $la_datos2[4]["totales"]= "<b>TOTAL Bs.</b>";
			    $la_datos2[0]["resultados"]= '<b>'.$ls_exento.'</b>';  //IVA
			    $la_datos2[1]["resultados"]= '<b>'.$la_total.'</b>';  //Subtotal
			    $la_datos2[2]["resultados"]= '<b>'.$la_iva8.'</b>';  //IVA
                            $la_datos2[3]["resultados"]= '<b>'.$la_iva12.'</b>';  //IVA
			    $la_datos2[4]["resultados"]= number_format($la_devolucion["mondev"][1],2, ',', '.');
			    $la_datos2[2]["resultados"]='<b>'. $la_datos2[2]["resultados"].'</b>';

                                $la_anchos_col = array(40,15,25);
				$la_justificaciones = array('right','right','right');

                                $la_opciones = array(                      'showHeadings'=>0, // Mostrar encabezados
                                                                           "color_fondo" => array(229,229,229),
									   "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col,
									   "tamano_texto"=> 9,
									   "lineas"=>0,
									   "alineacion_col"=>$la_justificaciones,
									   "margen_horizontal"=>1,
									   "margen_vertical"=>1);
                                //$io_pdf->addTa
                                //$io_pdf->ezTable(23,$la_datos2,'','',$la_opciones);
                                $io_pdf->add_tabla4(135,$la_datos2,$la_opciones);
$io_pdf->ezStream();
?>


