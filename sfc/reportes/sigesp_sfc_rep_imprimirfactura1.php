<?Php
session_start();
require_once("sigesp_sfc_c_factura.php");
$reporte = new sigesp_sfc_c_factura('LETTER','portrait','REPORTE 1');
$io_pdf  = new class_pdf('LETTER','portrait');
$io_pdf->selectFont('../../../shared/ezpdf/fonts/Helvetica.afm');
//$io_pdf->set_margenes(10,10,10,10);
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_fecha.php");
//require_once("class_folder/sigesp_sfc_c_secuencia.php");

$io_fecha=new class_fecha();
$io_datastore= new class_datastore();
$io_datastore2= new class_datastore();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$io_pdf = $reporte->get_pdf();


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
			   $la_facturacion=$io_sql->obtener_datos($rs_datauni);
	        }
		
				$ls_directorio="cheque_configurable";
				$ls_archivo="cheque_configurable/medidas.txt";
				$archartven=file("cheque_configurable/medidas.txt");

						$archivoproductos="";
						$lineas=count($archartven);
						for ($i=0;$i<$lineas;$i++)
						{
							$archivoproductos=$archivoproductos.$archartven[$i];
						}
					$ls_margen_sup=substr($archivoproductos,1,2);
					$ls_ubica_detprod=substr($archivoproductos,5,3);
					$ls_ubica_total=substr($archivoproductos,10,3);
					//print $ls_ubica_total;

				$io_pdf->set_margenes($ls_margen_sup,10,10,10);
				$reporte->add_titulo(17,9,9,"".$la_facturacion["razcli"][1]);
				$ls_fecha="".substr($la_facturacion["fecemi"][1],8,2)."/".substr($la_facturacion["fecemi"][1],5,2)."/".substr($la_facturacion["fecemi"][1],0,4)."";	
				$reporte->add_titulo(155,9,9,"    ".$ls_fecha);		
				$reporte->add_titulo(17,12.5,9,"".$la_facturacion["dircli"][1]);
				$reporte->add_titulo(17,17.5,9,"   ".$la_facturacion["telcli"][1]);		
				$reporte->add_titulo(80,17.5,9,"".$la_facturacion["cedcli"][1]);
				if ($la_facturacion["conpag"][1]==1) 
				 {
				   $ls_conpago='CONTADO';
				 }
				else 
				 {
				 $ls_conpago='CREDITO';
				  }
				
				$reporte->add_titulo(20,21.5,9,"                                          ");  
				$reporte->add_titulo(125,21.5,9,"                              ".$ls_conpago);
				
			   // ----  Detalles de la FACTURA  -------								
			    $li_cuotas=(count($la_facturacion,COUNT_RECURSIVE)/count($la_facturacion)) - 1;
				
			    $subtotal=0;
			    $la_total=0;
			    $la_iva=0;
				
				for($i=0;$i<=$li_cuotas-1;$i++)
 				{
				 $la_datos[$i]["A"]= number_format($la_facturacion["canpro"][$i+1],2, ',', '.');
				 $la_datos[$i]["B"]= substr($la_facturacion["denpro"][$i+1]." ".$la_facturacion["denunimed"][$i+1],0,60);
				 $la_datos[$i]["C"]= number_format($la_facturacion["porimp"][$i+1],2,',','.');
				 $la_datos[$i]["D"]= number_format($la_facturacion["prepro"][$i+1],2, ',', '.');
		  	     $la_datos[$i]["E"]= number_format($la_facturacion["prepro"][$i+1]*$la_facturacion["canpro"][$i+1],2, ',', '.');
				
			    $subtotal= $subtotal+($la_facturacion["prepro"][$i+1]*$la_facturacion["canpro"][$i+1]);
				$la_iva=$la_iva+((($la_facturacion["porimp"][$i+1]/100)*$la_facturacion["prepro"][$i+1])*$la_facturacion["canpro"][$i+1])."\r\n" ;
				//$i++;
				
				}
				
				$la_total= $la_total+($subtotal+$la_iva);
				$la_subtotal=number_format($subtotal,2, ',', '.');
				$la_iva=number_format($la_iva,2, ',', '.');
				$la_total=number_format($la_total,2, ',', '.');
				
				//  -----  CUADRO DE DETALLES  -----
				$io_pdf->ezSetY($ls_ubica_detprod);//Posicion desde la cual se inicia la impresion del detalle de los productos
				
        		$la_anchos_col = array(12,110,10,27,29);
				$la_justificaciones = array('right','left','right','right','right');	
				$la_opciones = array(  "color_fondo" => array(229,229,229), 
									   "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col,
									   "tamano_texto"=> 8.5,
									   "lineas"=>0,
									   "alineacion_col"=>$la_justificaciones,
									   "margen_horizontal"=>1);			   
				$io_pdf->add_tabla4(13,$la_datos,$la_opciones);
				
				
	
	/************************************************************************************************************************/			
				
			   // datos para la primera columna
			  
			   $la_datos2[0]["totales"]= "<b></b>"; //PRIMERA COLUMNA- FILA 0
			   $la_datos2[1]["totales"]= "<b></b>";     //SEGUNDA COLUMNA- FILA 1
			   $la_datos2[2]["totales"]= "<b></b>"; //TERCERA COLUMNA- FILA 2   
			   
			   // datos para la segunda columna
			   $la_datos2[0]["resultados"]="<b>". $la_subtotal."</b>";//PRIMERA COLUMNA- FILA 0
			   $la_datos2[1]["resultados"]= "<b>".$la_iva."</b>";    //SEGUNDA COLUMNA- FILA 1
			   $la_datos2[2]["resultados"]= "<b>".$la_total."</b>";  //TERCERA COLUMNA- FILA 2
			  
			     
				//-- UBICACION EJE "Y" DEL CUADRO;
				$io_pdf->ezSetY($ls_ubica_total);//Posicion desde la cual se inicia la impresion del subtotal,iva y total
				
				$la_anchos_col = array(30,30);
				$la_justificaciones = array('left','right');
				$la_opciones2 = array( "color_fondo" => array(229,229,229), 
									   "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col,
									   "tamano_texto"=> 9,
									   "lineas"=>0,
									   "alineacion_col"=>$la_justificaciones,
									   "margen_horizontal"=>1);			   
				$io_pdf->add_tabla4(140,$la_datos2,$la_opciones2);
				// datos para la segunda columna
			  
$io_pdf->ezStream();

//print $li_cuotas;
				
				 // $la_factura=$io_sql->obtener_datos($rs_datauni);
				 //$io_datastore->data=$la_facturacion;
	             //$totrow=$io_datastore->getRowCount("denpro");
			 //print $totrow;

?>
