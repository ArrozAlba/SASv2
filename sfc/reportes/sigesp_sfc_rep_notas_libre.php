<?Php
session_start();
require_once("sigesp_sfc_c_factura_libre.php");
$reporte = new sigesp_sfc_c_factura_libre('LETTER','portrait','REPORTE 1');
$io_pdf  = new class_pdf('LETTER','portrait');
$io_pdf->selectFont('../../../shared/ezpdf/fonts/Helvetica-Bold.afm');
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


$la_empresa=$_SESSION["la_empresa"];
$ls_codemp=$la_empresa["codemp"];
$ls_codcaj=$_SESSION["ls_codcaj"];
$ls_prefijo=$_SESSION["ls_prefac"];
$ls_serie=$_SESSION["ls_serfac"];
$ls_forpago="";

$ls_numnot=$_GET["numnot"];
$ls_codcli=$_GET["codcli"];
$ls_sql="SELECT f.numnot,f.nro_documento,dev.numfac,f.codtiend,f.fecnot as fecemi,'' as conpag,'' as numordent,'' as numcon,dev.obsdev,'' as numdiacre,SUM(df.candev) as canpro,df.codart,df.precio as prepro,df.porimp,'' as costo,c.razcli,t.codtiend,t.dirtie
				,c.dircli,c.telcli,c.cedcli,a.denart as denpro,um.denunimed 
			FROM sfc_nota f,sfc_devolucion dev,sfc_detdevolucion df,sfc_cliente c, sfc_tienda t,
				sim_articulo a,sfc_producto p,sim_unidadmedida um 
			WHERE f.numnot='".$ls_numnot."' AND f.codcli='".$ls_codcli."' AND f.nro_documento=df.coddev AND f.codtiend=t.codtiend AND f.codemp=df.codemp 
			AND f.codtiend=df.codtiend AND f.codcli=c.codcli AND f.codemp=c.codemp AND f.codemp=a.codemp AND f.codemp=p.codemp 
			AND f.codtiend=p.codtiend AND df.codemp=c.codemp AND df.codart=a.codart AND df.codemp=a.codemp AND df.codart=p.codart 
			AND df.codemp=p.codemp AND df.codtiend=p.codtiend AND c.codemp=a.codemp AND c.codemp=p.codemp AND a.codart=p.codart 
			AND a.codemp=p.codemp AND a.codunimed=um.codunimed AND dev.coddev=df.coddev
			GROUP BY f.numnot,f.nro_documento,dev.numfac,f.codtiend,f.fecnot,df.codart,dev.obsdev,df.precio,df.porimp,c.razcli,t.codtiend,t.dirtie,c.dircli,c.telcli,c.cedcli,a.denart,um.denunimed";

//print $ls_sql;
$rs_datauni=$io_sql->select($ls_sql);
if($rs_datauni==false&&($io_sql->message!=""))
{
	$io_msg->message("No hay registros");
}
else
{
   $la_notas=$io_sql->obtener_datos($rs_datauni);
}



			  	//$reporte->add_titulo(112,2,14,"
$ls_numnot=$la_notas["numnot"][1];

/*$ls_cadena="SELECT  p.* ,denforpag, nomban FROM sfc_instpago p,sfc_formapago f, scb_banco b  WHERE  numfac='".$ls_numfac."' AND p.codforpag=f.codforpag AND b.codban=p.codban";
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
			$posx=178;			
			for($f=0;$f<$rows;$f++)
			{										
			    $ls_forpago2=$la_formapago["denforpag"][$f+1];
				
				if(stristr($ls_forpago2,"Efectivo")==TRUE){				 	
				    $ls_montoefec=$la_formapago["monto"][$f+1];
				    $ls_montoefec=number_format($ls_montoefec,2, ',', '.');
					$la_datospago[$f]["tipo"]="<b>Efectivo   Monto:</b>";
					$la_datospago[$f]["contenido"]=$ls_montoefec;
					//$reporte->add_titulo("left",$posx,8,"Efectivo   Monto:  ". );				        
					$posx=$posx+4;
				}
				
				elseif(stristr($ls_forpago2,"Nota Credito")==TRUE){				 	
				    $ls_num=$la_formapago["numinst"][$f+1];
				    $ls_monto=$la_formapago["monto"][$f+1];
				    $ls_monto=number_format($ls_monto,2, ',', '.');
					$la_datospago[$f]["tipo"]="<b>$ls_forpago2:</b>";
					$la_datospago[$f]["contenido"]=$ls_num."       Monto: ".$ls_monto;
				 	//$reporte->add_titulo("left",$posx,8,$ls_forpago2.":  ".$ls_num."       Monto: ".$ls_monto);	        
					$posx=$posx+4;
				} 
				elseif(stristr($ls_forpago2,"Ticket de Alimentación")==TRUE){				 	
				    $ls_num=$la_formapago["numinst"][$f+1];
				    $ls_monto=$la_formapago["monto"][$f+1];
				    $ls_monto=number_format($ls_monto,2, ',', '.');
					$la_datospago[$f]["tipo"]="<b>$ls_forpago2 </b>";
					$la_datospago[$f]["contenido"]=" Monto: ".$ls_monto;
				 	//$reporte->add_titulo("left",$posx,8,$ls_forpago2.":  ".$ls_num."       Monto: ".$ls_monto);	        
					$posx=$posx+4;
				} 
				else
				{
				   $ls_num=$la_formapago["numinst"][$f+1];
				   $ls_nomban=$la_formapago["nomban"][$f+1];
				   $ls_monto=$la_formapago["monto"][$f+1];
				   $ls_monto=number_format($ls_monto,2, ',', '.');
				   if(stristr($ls_forpago2,"Transferencia Bancaria")==TRUE){
	   					$la_datospago[$f]["tipo"]="<b>Transf. Bancaria:</b>";
						$la_datospago[$f]["contenido"]=$ls_num."   Monto: ".$ls_monto."  Banco: ".rtrim($ls_nomban);
				   		//$reporte->add_titulo("left",$posx,8,"Transf. Bancaria:  ".$ls_num."   Monto: ".$ls_monto."  Banco: ".$ls_nomban);
			       } else {
					   	$la_datospago[$f]["tipo"]="<b>Numero de $ls_forpago2:</b>";
						$la_datospago[$f]["contenido"]=$ls_num."   Monto: ".$ls_monto."  Banco: ".rtrim($ls_nomban);
					 //  $reporte->add_titulo("left",$posx,8,"Numero de ".$ls_forpago2.":  ".$ls_num."   Monto: ".$ls_monto."  Banco: ".$ls_nomban);
			       }						  
				   //$reporte->add_titulo(78,$posx,8," Banco:  ".$ls_nomban);										    				 
				   //$ls_forpagoc=$la_formapago["denforpag"][$f+1];				
					$posx=$posx+4;
			    }
							
				 $ls_forpago=$la_formapago["denforpag"][$f+1]." ,".$ls_forpago;
																			
			}
			//print $ls_forpago;
		}
			$io_pdf->ezSetY(285);
				$la_anchos_col_pago = array(27,100);
				$la_justificaciones_pago = array('left','left');
				$la_opciones_pago = array(  "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col_pago,
									   "tamano_texto"=> 7,
									   "lineas"=>0,
									   "alineacion_col"=>$la_justificaciones_pago,
									   "margen_horizontal"=>1);
				$io_pdf->add_tabla(0,$la_datospago,$la_opciones_pago);
				$la_datos_obs[0]["0"]="<b>Observaciones:</b>   ".$la_facturacion["obsfac"][1];
				$la_anchos_col_obs = array(127);
				$la_justificaciones_obs = array('left');
				$la_opciones_obs = array(  "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col_obs,
									   "tamano_texto"=> 7,
									   "lineas"=>0,
									   "alineacion_col"=>$la_justificaciones_obs,
									   "margen_horizontal"=>1);
				$io_pdf->add_tabla(0,$la_datos_obs,$la_opciones_obs);
	*/
// $reporte->add_titulo("left",$posx+5,8,"Observaciones:   ".$la_facturacion["obsfac"][1]);			
				
				$reporte->add_titulo(73,12,12,"<b>SERIE: </b> ".$ls_serie);
				$reporte->add_titulo(103,12,12,"<b>NOTA :  </b>  ".$ls_numnot);
				$reporte->add_titulo(73,18,8,"<b>LUGAR DE EMISION:</b>    ".$la_notas["dirtie"][1]);
		 	//	$reporte->add_titulo("left",22,9,"NOMBRE Y APELLIDO O RAZON SOCIAL:    ".$la_facturacion["razcli"][1]);

				$ls_fecha=substr($la_notas["fecemi"][1],8,2)."/".substr($la_notas["fecemi"][1],5,2)."/".substr($la_notas["fecemi"][1],0,4)."";
				$reporte->add_titulo(157,18,9,"<b>FECHA:   </b>  ".$ls_fecha);
				//$reporte->add_titulo("left",26,9,"DOMICILIO FISCAL:   ".$la_facturacion["dircli"][1]);
				$io_pdf->ezSetY(695);
				$la_anchos = array(190);
				$la_datosbasicos[0]["contenido"]="<b>NOMBRE Y APELLIDO O RAZON SOCIAL:</b>    ".$la_notas["razcli"][1];
				$la_datosbasicos[1]["contenido"]="<b>DOMICILIO FISCAL: </b>  ".$la_notas["dircli"][1];
				$la_justificacion = array('left');
				$la_opcion = array(  "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos,
									   "tamano_texto"=> 9,
									   "lineas"=>0,
									   "alineacion_col"=>$la_justificacion,
									   "margen_horizontal"=>1);
				$io_pdf->add_tabla(0,$la_datosbasicos,$la_opcion);				
				$li_alto_usado=$io_pdf->get_alto_usado();
		 		//$io_pdf->convertir_valor_mm_px($li_alto_usado);
				
				$reporte->add_titulo("left",$li_alto_usado+1,9,"</b><b>TELEFONO:</b>".$la_notas["telcli"][1]."  ".$la_notas["celcli"][1]);
				$reporte->add_titulo(73,$li_alto_usado+1,9,"</b><b>RIF. o C.I.: </b>    ".$la_notas["cedcli"][1]);
				//$reporte->add_titulo("left",151,9,"OBSERVACIONES:     ".$la_facturacion["obsfac"][1]);
				//$li_alto_usado=$io_pdf->get_alto_usado();
				$reporte->add_titulo("left",$li_alto_usado+6,9,"</b><b>DEVOLUCION:</b> ".$la_notas["nro_documento"][1]." , <b>ASOCIADO A LA FACTURA:</b> ".$la_notas["numfac"][1]);
				if ($la_facturacion["conpag"][1]==1)
				 {
				   $ls_conpago='CONTADO';
				 }
				else
				 {
				 $ls_conpago='CREDITO';				 
				  }
				/*$reporte->add_titulo("left",$li_alto_usado+11,9,"</b><b>CONDICION DE PAGO: </b>   ".$ls_conpago);*/
				/*if ($ls_conpago=='CREDITO')
				 {
				   $reporte->add_titulo(73,$li_alto_usado+11,9,"</b><b>CANTIDAD DE DIAS:</b> " .$la_notas["numdiacre"][1]);
				 }  */                       
				 unset($la_datosbasicos,$la_opcion);
				 $io_pdf->ezSetY(600);
				$la_anchos = array(193);
				$la_datosdetalles[0]["contenido"]="<b>DETALLES DE FACTURACIÓN </b>  ";
				$la_justificacion = array('center');
				$la_opcion = array(  "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos,
									   "tamano_texto"=> 8,
									   "lineas"=>1,
									   "alineacion_col"=>$la_justificacion,
									   "margen_horizontal"=>1);
				$io_pdf->add_tabla(0,$la_datosdetalles,$la_opcion);
				unset($la_datosdetalle,$la_opcion);
				
				$reporte->add_titulo("left",166,8,"FORMA DE PAGO");									   
			 /*   $reporte->add_titulo(10,46,7,"CANTIDAD");
			    $reporte->add_titulo(66,46,7,"DESCRIPCION");
			    $reporte->add_titulo(142,46,7,"% IVA");
			    $reporte->add_titulo(160,44,7,"PRECIO");
			     $reporte->add_titulo(160,47,7,"UNITARIO");
			    $reporte->add_titulo(181,46,7,"TOTAL");*/
			   // ----  Detalles de la FACTURA  -------

                $li_cuotas=count($la_notas["canpro"],COUNT_RECURSIVE);
				
				$subtotal=0;
			    $la_total=0;
			    $la_iva=0;
			    $la_iva1=0;
			    $la_iva2=0;
                $ls_base1=0;
                $ls_base2=0;
				$i=0;
				$la_datos[$i]["CANT"]= "<b>CANTIDAD</b>";
				$la_datos[$i]["DESCRIPCION"]= "<b>             DESCRIPCION</b>";
				$la_datos[$i][""]= "<b>% IVA</b>";
				$la_datos[$i]["PRECIO UNITARIO"]= "<b>PREC. UNIT.</b>";
		  	    $la_datos[$i]["TOTAL"]= "<b>TOTAL</b>";
				$io_pdf->ezSetY(588);
				$la_anchos_col = array(20,120,10,20,22);
				$la_justificaciones = array('right','left','right','right','right','right');
				$la_opciones = array(  "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col,
									   "tamano_texto"=> 7,
									   "lineas"=>0,
									   "alineacion_col"=>$la_justificaciones,
									   "margen_horizontal"=>1);
				$io_pdf->add_tabla(0,$la_datos,$la_opciones);
				$la_datos=array();
				for($i=0;$i<$li_cuotas;$i++)
				{
 				 $la_datos[$i]["CANT"]= number_format($la_notas["canpro"][$i+1],2, ',', '.');
				 $ls_porimp= number_format($la_notas["porimp"][$i+1],2,',','.');

				 $la_datos[$i]["DESCRIPCION"]= "   ".$la_notas["denpro"][$i+1]." ".$la_notas["denunimed"][$i+1];
				//$ls_porimp= 0 number_format($la_facturacion["porimp"][$i+1],2,',','.');
				if ($ls_porimp==0)
					 {
				   		$ls_impuesto="(E)";
				   		$ls_exento= $ls_exento+($la_notas["prepro"][$i+1]*$la_notas["canpro"][$i+1]);
				 	    $la_datos[$i][""]=$ls_impuesto;
				 	}
				else
					{
				 	 $la_datos[$i][""]= number_format($la_notas["porimp"][$i+1],2,',','.');
				  	}
				  					
			    $la_datos[$i]["PRECIO UNITARIO"]= number_format(rtrim($la_notas["prepro"][$i+1]),2,',','.');
		  	    $la_datos[$i]["TOTAL"]= number_format($la_notas["prepro"][$i+1]*$la_notas["canpro"][$i+1],2, ',', '.');

			    $subtotal= $subtotal+($la_notas["prepro"][$i+1]*$la_notas["canpro"][$i+1]);
				
				
				
				$la_iva=$la_iva+(round((($la_notas["porimp"][$i+1]/100)*$la_notas["prepro"][$i+1]),2)*$la_notas["canpro"][$i+1]);

                if ($la_facturacion["porimp"][$i+1]=='12')
					 {
					 $la_iva1=$la_iva1+(round((($la_notas["porimp"][$i+1]/100)*$la_notas["prepro"][$i+1]),2)*$la_notas["canpro"][$i+1]);					   		
				 	 $ls_base1=$ls_base1+($la_notas["prepro"][$i+1]*$la_notas["canpro"][$i+1]);
				 	}
				  if ($la_facturacion["porimp"][$i+1]=='8')
					 {
					 $la_iva2=$la_iva2+(round((($la_notas["porimp"][$i+1]/100)*$la_notas["prepro"][$i+1]),2)*$la_notas["canpro"][$i+1]);					   		
				 	 $ls_base2=$ls_base+($la_notas["prepro"][$i+1]*$la_notas["canpro"][$i+1]);
				 	}	
				
				}
				if ($la_total==0){
					$ls_base=$subtotal-$ls_exento;
				}

				$la_total= $la_total+($subtotal+$la_iva1+$la_iva2);
				$ls_imponible=$ls_base1+$ls_base2;
				$la_subtotal=number_format($subtotal,2, ',', '.');
				$la_iva=number_format($la_iva,2, ',', '.');
				$la_iva1=number_format($la_iva1,2, ',', '.');
				$la_iva2=number_format($la_iva2,2, ',', '.');
				$ls_base1=number_format($ls_base1,2, ',', '.');
				$ls_base2=number_format($ls_base2,2, ',', '.');
				$la_total=number_format($la_total,2, ',', '.');

				//  -----  CUADRO DE DETALLES  -----
				
				//$io_pdf->ezSetY(588);
       // $la_anchos_col = array(15,100,36,29);
				$la_anchos_col = array(20,120,10,20,22);
				$la_justificaciones = array('right','left','right','right','right','right');
				$la_opciones = array(  "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col,
									   "tamano_texto"=> 8,
									   "lineas"=>0,
									   "alineacion_col"=>$la_justificaciones,
									   "margen_horizontal"=>1);
				$io_pdf->add_tabla(0,$la_datos,$la_opciones);




	/************************************************************************************************************************/

			   // datos para la primera columna

			$ls_exento=number_format($ls_exento,2, ',', '.');
			$ls_imponible=number_format($ls_imponible,2, ',', '.');
			//$ls_base=number_format($ls_base,2, ',', '.');
			   $la_datos2[0]["total"]= "<b>RECIBI CONFORME</b>"; //PRIMERA COLUMNA- FILA 0
			   $la_datos2[1]["total"]= "<b></b>";     //SEGUNDA COLUMNA- FILA 1
			   $la_datos2[2]["total"]= "<b></b>"; //TERCERA COLUMNA- FILA 2

			   $la_datos2[0]["totales"]= "<b>SUB-TOTAL (1)</b>"; //PRIMERA COLUMNA- FILA 0
			   $la_datos2[1]["totales"]= "<b>DESCUENTOS, BONIFICACIONES Y REBAJAS</b>"; //PRIMERA COLUMNA- FILA 0
			   $la_datos2[2]["totales"]= "<b>FLETES</b>"; //PRIMERA COLUMNA- FILA 0
			   $la_datos2[3]["totales"]= "<b>SUB-TOTAL (2)</b>"; //PRIMERA COLUMNA- FILA 0
			   $la_datos2[4]["totales"]= "<b>MONTO EXENTO</b>"; //PRIMERA COLUMNA- FILA 0	
			   $la_datos2[5]["totales"]= "<b>MONTO EXONERADO</b>"; //PRIMERA COLUMNA- FILA 0	
			   $la_datos2[6]["totales"]= "<b>MONTO BASE IMPONIBLE</b>"; //PRIMERA COLUMNA- FILA 0			  
			   $la_datos2[7]["totales"]= '<b>I.V.A (12%) Sobre: '.$ls_base1.'</b>';     //SEGUNDA COLUMNA- FILA 1
			   $la_datos2[8]["totales"]= '<b>I.V.A (8%) Sobre: '.$ls_base2.'</b>';     //TERCERA COLUMNA- FILA 2
			   $la_datos2[9]["totales"]= "<b>TOTAL Bs</b>"; //TERCERA COLUMNA- FILA 3			   
			  

			   // datos para la segunda columna
			   $la_datos2[0]["resultados"]= "<b>".$la_subtotal."</b>";
			   $la_datos2[1]["resultados"]= "<b>".'0,00'."</b>";
			   $la_datos2[2]["resultados"]= "<b>".'0,00'."</b>";
			   $la_datos2[3]["resultados"]="<b>".$la_subtotal."</b>";//PRIMERA COLUMNA- FILA 0
			   $la_datos2[4]["resultados"]= "<b>".$ls_exento."</b>";
			   $la_datos2[5]["resultados"]= "<b>".'0,00'."</b>";
			   $la_datos2[6]["resultados"]= "<b>".$ls_imponible."</b>";			  
			   $la_datos2[7]["resultados"]= "<b>".$la_iva1."</b>";    //SEGUNDA COLUMNA- FILA 1
			   $la_datos2[8]["resultados"]= "<b>".$la_iva2."</b>";    //SEGUNDA COLUMNA- FILA 1
			   $la_datos2[9]["resultados"]= "<b>".$la_total."</b>";  //TERCERA COLUMNA- FILA 2
               
				//-- UBICACION EJE "Y" DEL CUADRO;
				$io_pdf->ezSetY(328);

				$la_anchos_col = array(56,90,25);
				$la_justificaciones = array('center','right','right');
				$la_opciones2 = array( "color_fondo" => array(229,229,229),
									   "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col,
									   "tamano_texto"=> 9,
									   "lineas"=>0,
									   "alineacion_col"=>$la_justificaciones,
									   "margen_horizontal"=>1,
									   "margen_vertical"=>1);		
				$io_pdf->add_tabla4(23,$la_datos2,$la_opciones2);
				// datos para la segunda columna

$io_pdf->ezStream();

?>
