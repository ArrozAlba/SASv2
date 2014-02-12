<?Php
session_start();
require_once("sigesp_sfc_c_factura.php");
$reporte = new sigesp_sfc_c_factura('LETTER','portrait','REPORTE 1');
$io_pdf  = new class_pdf('LETTER','portrait');
$io_pdf->selectFont('../../../shared/ezpdf/fonts/Helvetica-Bold.afm');
$io_pdf->set_margenes(10,10,10,10);
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_fecha.php");

//require_once("class_folder/sigesp_sfc_c_secuencia.php");

$io_datastore= new class_datastore();
$io_fecha=new class_fecha();
$io_datastore2= new class_datastore();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$io_pdf = $reporte->get_pdf();
//$io_factura=new  sigesp_sfc_c_factura();

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
			
			  	//$reporte->add_titulo(112,2,14,"                      
$ls_numfac=$la_facturacion["numfac"][1];
$ls_cadena="SELECT  p.* ,denforpag, nomban FROM sfc_instpago p,sfc_formapago f, scb_banco b  WHERE  numfac='".$ls_numfac."' AND p.codforpag=f.codforpag AND b.codban=p.codban";
$rs_datapago=$io_sql->select($ls_cadena);
  // print $ls_cadena;
	
		if($rs_datapago==false)
		{
			
			//$io_msg->message("No hay registros");
		}
		else
		{
		  $rows =$io_sql->num_rows($rs_datapago);
	      $la_formapago=$io_sql->obtener_datos($rs_datapago);
			
			//print $rows;
			$c=0;
			$g=0;
			$posx=169;			
			for($f=0;$f<$rows;$f++)
				{
			   $ls_forpago2=$la_formapago["denforpag"][$f+1];
				
				if(stristr($ls_forpago2,"Efectivo")==TRUE){				 	
				    $ls_montoefec=$la_formapago["monto"][$f+1];
				    $ls_montoefec=number_format($ls_montoefec,2, ',', '.');
				 	$reporte->add_titulo(-22,$posx,8,"Efectivo   Monto:  ". $ls_montoefec);				        
				$posx=$posx+4;
				}
				
				elseif(stristr($ls_forpago2,"Nota Credito")==TRUE){				 	
				    $ls_num=$la_formapago["numinst"][$f+1];
				    $ls_monto=$la_formapago["monto"][$f+1];
				    $ls_monto=number_format($ls_monto,2, ',', '.');
				 	$reporte->add_titulo(-22,$posx,8,$ls_forpago2.":  ".$ls_num."       Monto: ".$ls_monto);	        
				$posx=$posx+4;
				} 
				else
				{
				   $ls_num=$la_formapago["numinst"][$f+1];
				   $ls_nomban=$la_formapago["nomban"][$f+1];
				   $ls_monto=$la_formapago["monto"][$f+1];
				   $ls_monto=number_format($ls_monto,2, ',', '.');
				   				  
				   if(stristr($ls_forpago2,"Transferencia Bancaria")==TRUE){
				   $reporte->add_titulo(-22,$posx,8,$ls_forpago2.":  ".$ls_num."   Monto: ".$ls_monto."  Banco: ".$ls_nomban);
			       } else {
				   $reporte->add_titulo(-22,$posx,8,"Numero de ".$ls_forpago2.":  ".$ls_num."   Monto: ".$ls_monto."  Banco: ".$ls_nomban);
			       }						  
				   //$reporte->add_titulo(65,$posx,8,"Banco: ".$ls_nomban);									    				 
				   //$ls_forpagoc=$la_formapago["denforpag"][$f+1];				
				$posx=$posx+4;
			    }
												
				 $ls_forpago=$la_formapago["denforpag"][$f+1]." ,".$ls_forpago;
																			
				}
				$reporte->add_titulo(-22,$posx,8,"Observaciones:   ".$la_facturacion["obsfac"][1]);			
			//print $ls_forpago;
		}
		        $ls_fecha=substr($la_facturacion["fecemi"][1],8,2)."       ".substr($la_facturacion["fecemi"][1],5,2)."        ".substr($la_facturacion["fecemi"][1],0,4)."";	
				$reporte->add_titulo(147,15,10,$ls_fecha);		
		 	    $reporte->add_titulo(111,15,10,$la_facturacion["dirtie"][1]);
		 	    //$reporte->add_titulo(127,25,10,$la_facturacion["numcon"][1]);
		 		$reporte->add_titulo(45,43,10,$la_facturacion["razcli"][1]);											
				$reporte->add_titulo(15,51,10,$la_facturacion["dircli"][1]);
				$reporte->add_titulo("left",72,10,$la_facturacion["telcli"][1]." ".$la_facturacion["celcli"][1]);		
				$reporte->add_titulo(141,59,10,$la_facturacion["cedcli"][1]);
				$reporte->add_titulo(47,72,10,$la_facturacion["numordent"][1]);
				if ($la_facturacion["conpag"][1]==1) 
				 {
				   $ls_conpago='CONTADO';
				 }
				else 
				 {
				   $ls_conpago='CREDITO';
				 }
				if ($ls_conpago=='CREDITO')
				 {
				   $reporte->add_titulo(163,71,10,$la_facturacion["numdiacre"][1]);
				 }
				 $reporte->add_titulo(-22,164,9,"FORMA DE PAGO");														    						
				
				$reporte->add_titulo("left",20,9,"                                          ");  
				$reporte->add_titulo(141,71,9,$ls_conpago);
				//$reporte->add_titulo("left",178,11,$ls_forpago);
				
				
			   // ----  Detalles de la FACTURA  -------								
											
                $li_cuotas=(count($la_facturacion,COUNT_RECURSIVE)/count($la_facturacion)) - 1;
				$subtotal=0;
			    $la_total=0;
			    $la_iva=0;
				
				for($i=0;$i<=$li_cuotas-1;$i++)
				{
 				
				 $ls_porimp= number_format($la_facturacion["porimp"][$i+1],2,',','.');
                 //$la_datos[$i]["`"]= '<b>'.$la_facturacion["denpro"][$i+1]." ".$la_facturacion["denunimed"][$i+1].'</b>';
				//$ls_porimp= 0 number_format($la_facturacion["porimp"][$i+1],2,',','.');
				
				if ($ls_porimp==0) 
					 {
				   		$ls_impuesto="(E)";
				   		$ls_exento= $ls_exento+($la_facturacion["prepro"][$i+1]*$la_facturacion["canpro"][$i+1]);
				   		$la_datos[$i]["`"]= '<b>'.$la_facturacion["denpro"][$i+1]." ".$la_facturacion["denunimed"][$i+1]." ".$ls_impuesto.'</b>';
				   		//$la_datos[$i][""]=$ls_impuesto;
				 	}
				else 
					 {
				 	//$la_datos[$i][""]= "(IVA ".number_format($la_facturacion["porimp"][$i+1],2,',','.').")";
				 	$la_datos[$i]["`"]= '<b>'.$la_facturacion["denpro"][$i+1]." ".$la_facturacion["denunimed"][$i+1]." "."(IVA ".number_format($la_facturacion["porimp"][$i+1],2,',','.').")".'</b>';
				  	}
				//$la_datos[$i][""]='<b>'.$ls_impuesto.'</b>';
				$la_datos[$i]["."]= '<b>'.number_format($la_facturacion["canpro"][$i+1],2, ',', '.').'</b>';
			    $la_datos[$i][","]='<b>'. number_format(rtrim($la_facturacion["prepro"][$i+1]),2,',','.').'</b>';
		  	    $la_datos[$i][":"]= '<b>'.number_format($la_facturacion["prepro"][$i+1]*$la_facturacion["canpro"][$i+1],2, ',', '.').'</b>';

			    $subtotal= $subtotal+($la_facturacion["prepro"][$i+1]*$la_facturacion["canpro"][$i+1]);
				
			    					 
				//$la_iva=$la_iva+((($la_facturacion["porimp"][$i+1]/100)*$la_facturacion["prepro"][$i+1])*$la_facturacion["canpro"][$i+1]);

               
                if ($la_facturacion["porimp"][$i+1]=='12')
					 {
					 $la_iva1=$la_iva1+((($la_facturacion["porimp"][$i+1]/100)*$la_facturacion["prepro"][$i+1])*$la_facturacion["canpro"][$i+1]);					   		
				 	 $ls_base1=$ls_base1+($la_facturacion["prepro"][$i+1]*$la_facturacion["canpro"][$i+1]);
				 	}
				  if ($la_facturacion["porimp"][$i+1]=='8')
					 {
					 $la_iva2=$la_iva2+((($la_facturacion["porimp"][$i+1]/100)*$la_facturacion["prepro"][$i+1])*$la_facturacion["canpro"][$i+1]);					   		
				 	 $ls_base2=$ls_base+($la_facturacion["prepro"][$i+1]*$la_facturacion["canpro"][$i+1]);
				 	}	
				
				
				}
				if ($la_total==0){
					$ls_base=$subtotal-$ls_exento;
				}
				
				$la_total= $la_total+($subtotal+$la_iva1+$la_iva2);
				$ls_imponible=$ls_base1+$ls_base2;
				$la_subtotal=number_format($subtotal,2, ',', '.');
				$la_iva=($la_iva1+$la_iva2);
				$la_iva=number_format($la_iva,2, ',', '.');
				$la_iva1=number_format($la_iva1,2, ',', '.');
				$la_iva2=number_format($la_iva2,2, ',', '.');
				$ls_base1=number_format($ls_base1,2, ',', '.');
				$ls_base2=number_format($ls_base2,2, ',', '.');
				$la_total=number_format($la_total,2, ',', '.');
				
				//  -----  CUADRO DE DETALLES  -----
				$io_pdf->ezSetY(549);
       // $la_anchos_col = array(15,100,36,29);
				$la_anchos_col = array(125,20,23,34);
				$la_justificaciones = array('left','right','right','right');	
				$la_opciones = array(  "color_fondo" => array(229,229,229), 
									   "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col,
									   "tamano_texto"=> 9,
									   "lineas"=>0,
									   "alineacion_col"=>$la_justificaciones,
									   "margen_horizontal"=>1,
									    "margen_vertical"=>4);				   
				$io_pdf->add_tabla4(-22,$la_datos,$la_opciones);
													
	/************************************************************************************************************************/			
			
		/*	   $reporte->add_titulo(156,131,9,$la_subtotal);
			   $reporte->add_titulo(156,136,9,"0,00");
			   $reporte->add_titulo(156,141,9,$la_subtotal);
			   $reporte->add_titulo(156,146,9,"0,00");
			   $reporte->add_titulo(156,151,9,"0,00");
			   $reporte->add_titulo(156,156,9,"0,00");
			   $reporte->add_titulo(156,161,9,"0,00");
			   $reporte->add_titulo(156,166,9,$la_iva);
			   $reporte->add_titulo(156,171,9,$la_total);
		*/		
			   // datos para la primera columna
		       $ls_exento=number_format($ls_exento,2, ',', '.');
		       $ls_imponible=number_format($ls_imponible,2, ',', '.');
			   $la_datos2[0]["totales"]= "<b></b>"; //PRIMERA COLUMNA- FILA 0
			   $la_datos2[1]["totales"]= "<b></b>"; //SEGUNDA COLUMNA- FILA 1
			   $la_datos2[2]["totales"]= "<b></b>"; //TERCERA COLUMNA- FILA 2   
			   $la_datos2[3]["totales"]= "<b></b>"; //TERCERA COLUMNA- FILA 2   
			   $la_datos2[4]["totales"]= "<b></b>"; //TERCERA COLUMNA- FILA 2   
			   $la_datos2[5]["totales"]= "<b></b>"; //TERCERA COLUMNA- FILA 2   
			   $la_datos2[6]["totales"]= "<b></b>"; //TERCERA COLUMNA- FILA 2   
			   $la_datos2[7]["totales"]= "<b>".$ls_imponible."</b>"; //TERCERA COLUMNA- FILA 2   
			   $la_datos2[8]["totales"]= "<b></b>"; //TERCERA COLUMNA- FILA 2   			  
			   
			   // datos para la segunda columna
			   $la_datos2[0]["resultados"]="<b>".$la_subtotal."</b>";//PRIMERA COLUMNA- FILA 0
			   $la_datos2[1]["resultados"]="<b>".'0,00'."</b>";//PRIMERA COLUMNA- FILA 0
			   $la_datos2[2]["resultados"]="<b>".$la_subtotal."</b>";//PRIMERA COLUMNA- FILA 0
			   $la_datos2[3]["resultados"]="<b>".'0,00'."</b>";//PRIMERA COLUMNA- FILA 0			  
			   $la_datos2[4]["resultados"]="<b>".$ls_exento."</b>";//PRIMERA COLUMNA- FILA 0
			   $la_datos2[5]["resultados"]="<b>".'0,00'."</b>";//PRIMERA COLUMNA- FILA 0
			   $la_datos2[6]["resultados"]="<b>".$ls_imponible."</b>";//PRIMERA COLUMNA- FILA 0			  
			   $la_datos2[7]["resultados"]= "<b>".$la_iva."</b>";    //SEGUNDA COLUMNA- FILA 1
			   $la_datos2[8]["resultados"]= "<b>".$la_total."</b>";  //TERCERA COLUMNA- FILA 2
			  
			     
				//-- UBICACION EJE "Y" DEL CUADRO;
				$io_pdf->ezSetY(399);
				
				$la_anchos_col = array(55,30);
				$la_justificaciones = array('right','right');
				$la_opciones2 = array("color_fondo" => array(229,229,229), 
									  "color_texto" => array(0,0,0),
									  "anchos_col"  => $la_anchos_col,
									  "tamano_texto"=> 10,
									  "lineas"=>0,
									  "alineacion_col"=>$la_justificaciones,
									  "margen_horizontal"=>1,
									  "margen_vertical"=>3);			   
			 $io_pdf->add_tabla4(95,$la_datos2,$la_opciones2);
			 // datos para la segunda columna
						 
$io_pdf->ezStream();

?>
