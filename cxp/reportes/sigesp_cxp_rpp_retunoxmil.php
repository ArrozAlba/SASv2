<?php
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if (!array_key_exists("la_logusr",$_SESSION))
	   {
	     print "<script language=JavaScript>";
		 print "close();";
		 print "</script>";		
	   }

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		
		
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$io_pdf->addText(200,630,15,"<b>".$as_titulo."</b>"); // Agregar el título
		$io_pdf->addText(470,735,10,"Fecha: ".date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(476,715,10,"Hora: ".date("h:i a")); // Agregar la hora					
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
    function uf_print_encabezado_pagina2($as_titulo,$as_agente,$as_agenrif,$as_nombre,$as_rif,$as_telefono,$as_direccion,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSetCmMargins(8,4,4,4);
		//$io_pdf->line(20,40,578,40);
		//$io_pdf->rectangle(20,40,558,640);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(12,$as_titulo);
		$io_pdf->addText(140,690,15,"<b>".$as_titulo."</b>"); // Agregar el título
		$io_pdf->addText(470,735,10,"Fecha: ".date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(476,715,10,"Hora: ".date("h:i a")); // Agregar la hora			
		/*
		$io_pdf->addText(47,600,10," Agente de Retención:  ".$as_agente."       - Rif:".$as_agenrif); // Agregar el título
		$io_pdf->addText(47,585,10," Nombre o Razón Social:  ".$as_nombre.""); // Agregar el título
		$io_pdf->addText(47,570,10," RIF:  ".$as_rif."                                  Telefono:  ".$as_telefono.""); // Agregar el título
		$io_pdf->addText(47,555,10," Direccion:  "); // Agregar el título
		$io_pdf->addText(100,555,9,"".$as_direccion." "); // Agregar el título
		*/
		
		$io_pdf->ezSetY(680);
		$la_data[1]=array('columna1'=>'<b>Agente de Retención: </b>  '.$as_agente.'<b>             Rif</b> '.$as_agenrif);
		$la_columna=array('columna1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>500))); // Justificación y ancho de la columna						 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);




		$la_data[1]=array('columna1'=>'<b>Nombre o Razón Social: </b> '.$as_nombre.'');
		$la_columna=array('columna1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>500))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);


		$la_data[1]=array('columna1'=>'<b>RIF: </b> '.$as_rif.'');
		$la_columna=array('columna1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>500))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);



		$la_data[1]=array('columna1'=>'<b>Direccion:</b> '.$as_direccion.'');
		$la_columna=array('columna1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>500))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);

		unset($la_data);
		unset($la_columna);
		unset($la_config);
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($as_agente,$as_nombre,$as_rif,$as_telefono,$as_direccion,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 04/05/2006.
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		$la_data=array(array('name'=>'<b>    Agente de Retención:</b>'."  ".$as_agente),
		               array('name'=>'<b>    Nombre o Razón Social:</b>'."  ".$as_nombre),
					   array('name'=>'<b>    RIF:</b>'."  ".$as_rif),
					   array('name'=>'<b>    Direccion:</b>'."  ".$as_direccion.'<b>    Telefono:   </b>'.$as_telefono)
					   );
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array(1,1,1),
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>530),
						               'name'=>array('justification'=>'left','width'=>530),
									   'name'=>array('justification'=>'left','width'=>530)
									   )
					    ); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);				
		
	}// end function uf_print_detalle.
	//--------------------------------------------------------------------------------------------------------------------------------
     
	 function uf_print_totales($li_filas,$ld_total,&$io_pdf)
	 {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//            Function:  uf_print_totales
		//		        Access:  private 
		//	         Arguments: 
		//           $li_filas:  Número de Registros en el Reporte.
		//           $ld_total:  Monto Total de las Retenciones aplicadas en el Periodo.
		//	  		    io_pdf:  Objeto PDF
		//         Description:  Función que imprime el detalle.
		//	        Creado Por:  Ing. Néstor Falcón.
		//      Fecha Creación:  04/05/2006.
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  /*
	    $la_data=array(array('name'=>'____________________________________________________________________________________________'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>315, // Orientación de la tabla
						 'width'=>530); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		*/
		$la_data  =array(array('cantidad'=>'<b>Total de Objeto Retencion :</b>','filas'=>$li_filas,'totales'=>'<b>Total Retenido:</b>','monto'=>$ld_total));
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 11, // Tamaño de Letras
						 'titleFontSize' =>11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
 						 'colGap'=>1,
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho Máximo de la tabla						
						 'cols'=>array('cantidad'=>array('justification'=>'left','width'=>150),
						               'filas'=>array('justification'=>'left','width'=>100),
									   'totales'=>array('justification'=>'right','width'=>160),
									   'monto'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
	    $la_columna=array('cantidad'=>'','filas'=>'','totales'=>'','monto'=>'');
	    $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	 }
	
	function uf_print_blanco(&$io_pdf)
	{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_blanco.
	//		   Access: private 
	//	    Arguments: 
	//     $as_numsol:
	//   $as_concepto:
	//  $as_fechapago:
	//      $ad_monto:
	//     $ad_monret:
	// $ad_porcentaje:
	//         io_pdf:  Objeto PDF
	//    Description:  Función que imprime una linea de división al final de los detalles.
	//	   Creado Por:  Ing. Néstor Falcón.
	// Fecha Creación:  04/05/2006.
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     
	  $la_data0=array(array('name'=>''),
					  array('name'=>''),
					  array('name'=>''),
					  array('name'=>'')
					  );
	  $la_columna0=array('name'=>'');
	  $la_config0=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array(1,1,1),
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>530),
						               'name'=>array('justification'=>'left','width'=>530),
									   'name'=>array('justification'=>'left','width'=>530)
									   )
					    ); // Ancho Máximo de la tabla
	  $io_pdf->ezTable($la_data0,$la_columna0,'',$la_config0);	
	  }
	
	
	function uf_print_formato($as_numsol,$as_concepto,$as_fechapago,$ad_monto,$ad_monret,$ad_porcentaje,$as_numche,&$io_pdf)
	{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_formato.
	//		   Access: private 
	//	    Arguments: 
	//     $as_numsol:
	//   $as_concepto:
	//  $as_fechapago:
	//      $ad_monto:
	//     $ad_monret:
	// $ad_porcentaje:
	//         io_pdf:  Objeto PDF
	//    Description:  Función que imprime una linea de división al final de los detalles.
	//	   Creado Por:  Ing. Néstor Falcón.
	// Fecha Creación:  04/05/2006.
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     	  
      $la_data    = array(array('solicitud'=>'<b>Factura:</b>'."  ".$as_numsol.'                                             '.'<b>Nro Cheque: </b>'.$as_numche));	
	  $la_columna = array('solicitud'=>'');
	  $la_config  = array('showHeadings'=>1, // Mostrar encabezados
					      'fontSize' => 10,  // Tamaño de Letras
					      'showLines'=>0,    // Mostrar Líneas
					      'shaded'=>0,       // Sombra entre líneas
					      'xPos'=>315,       // Orientación de la tabla
					      'width'=>530);     // Ancho Máximo de la tabla
	  $io_pdf->ezTable($la_data,$la_columna,'',$la_config);		       
	 
	 
	 
      $la_data2    = array(array('concepto'=>'<b>Concepto:</b>'."  ".$as_concepto));	
	  $la_columna2 = array('concepto'=>'');
	  $la_config2  = array('showHeadings'=>1, // Mostrar encabezados
					      'fontSize' => 10,  // Tamaño de Letras
					      'showLines'=>0,    // Mostrar Líneas
					      'shaded'=>0,       // Sombra entre líneas
					      'xPos'=>315,       // Orientación de la tabla
					      'width'=>530);     // Ancho Máximo de la tabla
	  $io_pdf->ezTable($la_data2,$la_columna2,'',$la_config2);		       
	 
	 
	 
      $la_data    = array(array('fecha'=>'<b>Fecha de Pago</b>',
	                            'monto'=>'<b>Monto Objeto de Retención</b>',
								'porcentaje'=>'<b>% Aplicado</b>',
								'retenido'=>'<b>Total Impuesto Retenido</b>'));	
	  $la_columna = array('fecha'=>'','monto'=>'','porcentaje'=>'','retenido'=>'');
	  $la_config  = array('showHeadings'=>0, // Mostrar encabezados
					      'fontSize' => 10, // Tamaño de Letras
					      'showLines'=>2, // Mostrar Líneas
					      'shaded'=>2, // Sombra entre líneas
					      'shadeCol'=>array(0.9,0.9,0.9),
						  'shadeCol2'=>array(0.9,0.9,0.9),
						  'xOrientation'=>'center', // Orientación de la tabla
					      'colGap'=>1,
						  'width'=>530,
						  'cols'=>array('fecha'=>array('justification'=>'center','width'=>100),
						                'monto'=>array('justification'=>'center','width'=>150),
										'porcentaje'=>array('justification'=>'center','width'=>100),
										'retenido'=>array('justification'=>'center','width'=>150)));
	  $io_pdf->ezTable($la_data,$la_columna,'',$la_config);		
	
	  $la_data    = array(array('fecha'=>$as_fechapago,'monto'=>$ad_monto,'porcentaje'=>$ad_porcentaje,'retenido'=>$ad_monret));	
	  $la_columna = array('fecha'=>'','monto'=>'','porcentaje'=>'','retenido'=>'');
	  $la_config  = array('showHeadings'=>0, // Mostrar encabezados
					      'fontSize' => 10, // Tamaño de Letras
					      'showLines'=>2, // Mostrar Líneas
					      'shaded'=>0, // Sombra entre líneas
					      'shadeCol'=>array(0.9,0.9,0.9),
						  'shadeCol2'=>array(0.9,0.9,0.9),
						  'xOrientation'=>'center', // Orientación de la tabla
					      'colGap'=>1,
						  'width'=>530,
						  'cols'=>array('fecha'=>array('justification'=>'center','width'=>100),
						                'monto'=>array('justification'=>'right','width'=>150),
										'porcentaje'=>array('justification'=>'center','width'=>100),
										'retenido'=>array('justification'=>'right','width'=>150)));
	  $io_pdf->ezTable($la_data,$la_columna,'',$la_config);		
	}
	
	function uf_print_sello(&$io_pdf)
	{		
	 $la_data1=array(array('name'=>""), 
	                 array('name'=>""),
					 array('name'=>""), 
	                 array('name'=>""),
	                 array('name'=>'<b> AGENTE DE RETENCION</b>'."                                                                                                                 ".'<b>BENEFICIARIOS</b>'),
                     array('name'=>""),
		             array('name'=>"                                                                                     ".'<b>SELLO</b>')					
				    );
	  $la_columna1=array('name'=>'','name'=>'','name'=>'','name'=>'','name'=>'','name'=>'','name'=>'');
	  $la_config1=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array(1,1,1),
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>530),
 						               'name'=>array('justification'=>'left','width'=>530),
									   'name'=>array('justification'=>'left','width'=>530),
 						               'name'=>array('justification'=>'left','width'=>530),
									   'name'=>array('justification'=>'left','width'=>530),
						               'name'=>array('justification'=>'left','width'=>530),
								       'name'=>array('justification'=>'left','width'=>530)
									   )
					    ); // Ancho Máximo de la tabla
	  $io_pdf->ezTable($la_data1,$la_columna1,'',$la_config1);		
	}	
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_cxp_class_report.php");
	require_once("../../shared/class_folder/sigesp_include.php");
    require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/class_sql.php");
	$io_funcion = new class_funciones();
    $io_in      = new sigesp_include();
	$con        = $io_in->uf_conectar();
    $io_sql     = new class_sql($con);
    $io_report  = new sigesp_cxp_class_report();
    $arr_emp    = $_SESSION["la_empresa"];
    $ls_codemp  = $arr_emp["codemp"];
    $ls_agente  = $arr_emp["nombre"];
	$ls_agenrif = $arr_emp["rifemp"];
   	$ls_titulo  = "<b>Comprobante de Retención Municipal del (1 X 1.000).</b>";
	
    $ldec_summonobjret= 0;
	$ldec_summonret   = 0;
	
	if (array_key_exists("hidprocedencias",$_POST))
       {
	     $ls_procedencias = $_POST["hidprocedencias"];
	   }
    else
       {
	     $ls_procedencias = $_GET["hidprocedencias"];
	   }
    if (array_key_exists("hidcomprobantes",$_POST))
       {
	     $ls_comprobantes=$_POST["hidcomprobantes"];
	   }
    else
       {
	     $ls_comprobantes=$_GET["hidcomprobantes"];
	   }
	$lr_procedencias = split('-',$ls_procedencias);
	$lr_comprobante  = split('-',$ls_comprobantes);
	$lr_datosclean   = array_unique($lr_comprobante);
	$li_total        = count($lr_datosclean);
	sort($lr_datosclean,SORT_STRING);
	if ($li_total<=0)
	   {
	     print("<script language=JavaScript>");
		 print(" alert('No hay nada que Reportar');"); 
		 print(" close();");
	     print("</script>");
       }
	 else // Imprimimos el reporte
	   {
		 error_reporting(E_ALL);
	     set_time_limit(1800);
 		 $io_pdf=new Cezpdf('LETTER','portrait');                       // Instancia de la clase PDF
		 $io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		 $io_pdf->ezSetCmMargins(8,4,4,4);                            // Configuración de los margenes en centímetros
         for ($z=0;$z<$li_total;$z++)
		 {
			   $i = 1;			      
 			   //uf_print_encabezado_pagina($ls_titulo,&$io_pdf);    // Imprimimos el encabezado de la página	
			   $ls_numsol  = $lr_datosclean[$z];
			   $ls_procede = $lr_procedencias[$z];  
			   if ($ls_procede=='CXPRCD')
			   {
				    $lb_valido = $io_report->uf_load_comprobantes_retencion_muni_1xmil($ls_codemp,$ls_numsol);					
			   }
			   /*
			   elseif($ls_procede=='SCBBCH')
			   {
				    $lb_valido = $io_report->uf_load_comprobantes_retencion_scb($ls_codemp,$ls_numsol);  
			   }
			   */
			   if ($lb_valido)
			   {
						$li_totaldt=$io_report->ds_retenciones->getRowCount("numdoc");
						for($i=1;($i<=$li_totaldt);$i++)
						{
								if($i==1)
								{
										$ls_tipproben = $io_report->ds_retenciones->data["tipproben"][$i];
										if ($ls_tipproben=='P')
										{
											 $ls_nombre    = $io_report->ds_retenciones->data["proveedor"][$i];
											 $ls_telefono  = $io_report->ds_retenciones->data["telpro"][$i];
											 $ls_direccion = $io_report->ds_retenciones->data["dirpro"][$i];
											 $ls_rif       = $io_report->ds_retenciones->data["rifpro"][$i];
										}
										else
										{
											 $ls_nombre    = $io_report->ds_retenciones->data["beneficiario"][$i];
											 $ls_telefono  = $io_report->ds_retenciones->data["telbene"][$i];
											 $ls_direccion = $io_report->ds_retenciones->data["dirbene"][$i];
											 $ls_rif       = $io_report->ds_retenciones->data["rifben"][$i];
										}	
   									    uf_print_encabezado_pagina2($ls_titulo,$ls_agente,$ls_agenrif,$ls_nombre,$ls_rif,$ls_telefono,$ls_direccion,&$io_pdf); 
								}
								
								///////////////////////////////////////////////////////////////////
								///////////////////////////////////////////////////////////////////
								///////////////////////////////////////////////////////////////////
								///////////////////////////////////////////////////////////////////
							    if ($ls_procede=='CXPRCD') 
								{
									$ls_numche = $io_report->uf_seek_cheque($ls_codemp,$ls_numsol);					
							    }
							    /*
								if($ls_procede=='SCBBCH')   
								{
									$ls_numche = $io_report->ds_retenciones->data["numdoc"][$i];
							    }
								*/
								//uf_print_encabezado_pagina2($ls_titulo,$ls_agente,$ls_agenrif,$ls_nombre,$ls_rif,$ls_telefono,$ls_direccion,&$io_pdf); 	
								
								$ls_condoc        = $io_report->ds_retenciones->data["consol"][$i];
								$ls_numdoc        = $io_report->ds_retenciones->data["numdoc"][$i];
								$ls_numcon        = $io_report->ds_retenciones->data["numref"][$i];
								$ls_fecha_emision = $io_funcion->uf_convertirfecmostrar($io_report->ds_retenciones->data["fecemisol"][$i]);
							
								$ld_totaldoc      = $io_report->ds_retenciones->data["montotdoc"][$i];  
								$ld_monobjret     = $io_report->ds_retenciones->data["monobjret"][$i];    
								$ld_monret        = $io_report->ds_retenciones->data["retenido"][$i];  
								$ld_porcentaje    = $io_report->ds_retenciones->data["porcentaje"][$i];																									

								$ldec_summonobjret= $ldec_summonobjret+$ld_monobjret;
								$ldec_summonret   = $ldec_summonret+$ld_monret;
								
								$ld_totaldoc      = number_format($io_report->ds_retenciones->data["montotdoc"][$i],2,',','.');  
								$ld_monobjret     = number_format($io_report->ds_retenciones->data["monobjret"][$i],2,',','.');    
								$ld_monret        = number_format($io_report->ds_retenciones->data["retenido"][$i],2,',','.');  
								$ld_porcentaje    = $io_report->ds_retenciones->data["porcentaje"][$i];	
																								
								uf_print_formato($ls_numdoc,$ls_condoc,$ls_fecha_emision,$ld_monobjret,$ld_monret,$ld_porcentaje,$ls_numche,$io_pdf);
						           
						  }
			}		
			if ($z<$li_total-1)
			{
				 $io_pdf->ezNewPage();  
				    	
			}
		  }
		 $ldec_summonobjret  = number_format($ldec_summonobjret,2,',','.');  
		 $ldec_summonret     = number_format($ldec_summonret,2,',','.');    	  
		 uf_print_totales($ldec_summonobjret,$ldec_summonret,&$io_pdf);
   	     uf_print_sello($io_pdf);
		 $io_pdf->ezStream();
		 unset($io_pdf);
		 unset($io_report); 
	   }
?> 