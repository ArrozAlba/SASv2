<?Php
session_start();
require_once("sigesp_sfc_c_factura.php");
$reporte = new sigesp_sfc_c_factura('LETTER','portrait','REPORTE 1');
$io_pdf  = new class_pdf('LETTER','portrait');
$io_pdf->selectFont('../../../shared/ezpdf/fonts/Helvetica.afm');
$io_pdf->set_margenes(10,10,10,10);
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

$la_empresa=$_SESSION["la_empresa"];
$ls_codemp=$la_empresa["codemp"];
$ls_codcaj=$_SESSION["ls_codcaj"];
$ls_prefijo=$_SESSION["ls_prefac"];
$ls_serie=$_SESSION["ls_serfac"];

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
			   $la_facturacion=$io_sql->obtener_datos($rs_datauni);
	        }
		 
			
			
			  	//$reporte->add_titulo(112,2,14,"                      ".substr($la_facturacion["numfac"][1],21,4));
		 		$reporte->add_titulo(8,5.5,9,"".$la_facturacion["razcli"][1]);
								
				$ls_fecha="  ".substr($la_facturacion["fecemi"][1],8,2)."/".substr($la_facturacion["fecemi"][1],5,2)."/".substr($la_facturacion["fecemi"][1],0,4)."";	
				$reporte->add_titulo(142,5.5,9,"    ".$ls_fecha);		
				$reporte->add_titulo(8,9,9,"".$la_facturacion["dircli"][1]);
				$reporte->add_titulo(8,14,9,"   ".$la_facturacion["telcli"][1]);		
				$reporte->add_titulo(58,14,9,"                 ".$la_facturacion["cedcli"][1]);
				if ($la_facturacion["conpag"][1]==1) 
				 {
				   $ls_conpago='CONTADO';
				 }
				else 
				 {
				 $ls_conpago='CREDITO';
				  }
				
				$reporte->add_titulo(20,18,9,"                                          ");  
				$reporte->add_titulo(116,18,9,"                              ".$ls_conpago);
				
			   // ----  Detalles de la FACTURA  -------								
											
                $li_cuotas=(count($la_facturacion,COUNT_RECURSIVE)/count($la_facturacion)) - 1;
				$subtotal=0;
			    $la_total=0;
			    $la_iva=0;
				
				for($i=0;$i<=$li_cuotas-1;$i++)
				{
 				 $la_datos[$i]["."]= number_format($la_facturacion["canpro"][$i+1],2, ',', '.');
				 $la_datos[$i]["`"]= substr("  ".$la_facturacion["denpro"][$i+1]." ".$la_facturacion["denunimed"][$i+1],0,60);
				// $la_datos[$i]["'"]= number_format($la_facturacion["porimp"][$i+1],2,',','.');
				 $la_datos[$i][","]= number_format($la_facturacion["prepro"][$i+1],2, ',', '.');
		  	     $la_datos[$i][":"]= number_format($la_facturacion["prepro"][$i+1]*$la_facturacion["canpro"][$i+1],2, ',', '.');

			    $subtotal= $subtotal+($la_facturacion["prepro"][$i+1]*$la_facturacion["canpro"][$i+1]);
				$la_iva=$la_iva+((($la_facturacion["porimp"][$i+1]/100)*$la_facturacion["prepro"][$i+1])*$la_facturacion["canpro"][$i+1]);
			    					 
				}
				$la_total= $la_total+($subtotal+$la_iva);
				$la_subtotal=number_format($subtotal,2, ',', '.');
				$la_iva=number_format($la_iva,2, ',', '.');
				$la_total=number_format($la_total,2, ',', '.');
				
				//  -----  CUADRO DE DETALLES  -----
				$io_pdf->ezSetY(645);
        
				$la_anchos_col = array(15,125,27,31);
				$la_justificaciones = array('right','left','right','right');	
				$la_opciones = array(  "color_fondo" => array(229,229,229), 
									   "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col,
									   "tamano_texto"=> 8.5,
									   "lineas"=>0,
									   "alineacion_col"=>$la_justificaciones,
									   "margen_horizontal"=>1);			   
				$io_pdf->add_tabla4(-3,$la_datos,$la_opciones);
				
				
	
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
				$io_pdf->ezSetY(600-$li_cuotas*10);
				
				$la_anchos_col = array(30,25);
				$la_justificaciones = array('left','right');
				$la_opciones2 = array( "color_fondo" => array(229,229,229), 
									   "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col,
									   "tamano_texto"=> 10,
									   "lineas"=>0,
									   "alineacion_col"=>$la_justificaciones,
									   "margen_horizontal"=>1);			   
				$io_pdf->add_tabla4(140,$la_datos2,$la_opciones2);
				// datos para la segunda columna
			  
$io_pdf->ezStream();

?>
