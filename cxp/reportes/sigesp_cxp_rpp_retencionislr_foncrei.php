<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//    REPORTE: Retencion de ISLR
	//  ORGANISMO: Ninguno en particular
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";		
		print "</script>";		
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 03/07/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_retencionesislr.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_numdoc,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 04/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		
		//$io_pdf->line(20,40,578,40);
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(10,$as_titulo);
		$io_pdf->addText(130,710,15,"<b>".$as_titulo."</b>");// Agregar el título		
				
		$io_pdf->rectangle(460,655,90,30);
		$io_pdf->addText(465,710,9,"Fecha: ");
		$io_pdf->addText(500,695,9,date("d/m/Y"));
		$io_pdf->addText(470,660,9,$as_numdoc);
		
		$io_pdf->rectangle(460,690,90,30);
		$io_pdf->addText(465,675,9,"No. Comprobante ");
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado($as_agente,$as_nomproben,$as_rifproben,$as_nitproben,$as_condoc,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado
		//		   Access: private 
		//	    Arguments: as_agente // Nombre del agente de retención
		//	    		   as_nombre // Nombre del proveedor ó beneficiario
		//	    		   as_rif // Rif del proveedor ó beneficiario
		//	    		   as_nit // nit del proveedor ó beneficiario
		//	    		   as_telefono // Telefono del proveedor ó beneficiario
		//	    		   as_direccion // Dirección del proveedor ó beneficiario
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por recepción
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 05/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		  
		  $ls_rifageret = $_SESSION["la_empresa"]["rifemp"];
          $ls_dirageret = $_SESSION["la_empresa"]["direccion"];
		  
		  $la_data    = array(array('ageret'=>$as_agente,'rifageret'=>$ls_rifageret));	
	      $la_columna = array('ageret'=>' NOMBRE O RAZON SOCIAL DEL AGENTE DE RETENCION','rifageret'=>'R.I.F. DEL AGENTE DE RETENCION');
		  $la_config  = array('showHeadings'=>1, // Mostrar encabezados
						      'showLines'=>1, // Mostrar Líneas
						      'fontSize' => 9, // Tamaño de Letras
						      'titleFontSize' =>9,  // Tamaño de Letras de los títulos
						      'shaded'=>2, // Sombra entre líneas
						      'shadeCol'=>array(1,1,1),
						 	  'shadeCol2'=>array(1,1,1), // Color de la sombra
						 	  'xOrientation'=>'center', // Orientación de la tabla
						      'width'=>530, // Ancho de la tabla
						      'maxWidth'=>530,
						      'cols'=>array('ageret'=>array('justification'=>'left','width'=>300),
									        'rifageret'=>array('justification'=>'left','width'=>200))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna); 
		unset($la_config); 

        $io_pdf->ezSetDy(-5);
	    $la_data    = array(array('dirageret'=>$ls_dirageret));	
	    $la_columna = array('dirageret'=>'DIRECCION FISCAL DEL AGENTE DE RETENCION');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' =>9,  // Tamaño de Letras de los títulos
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array(1,1,1),
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530,
						 'cols'=>array('dirageret'=>array('justification'=>'left','width'=>500))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);		
		unset($la_data);
		unset($la_columna); 
		unset($la_config); 

		$io_pdf->ezSetDy(-5);
	    $la_data    = array(array('nompro'=>$as_nomproben,'rifpro'=>$as_rifproben."      ".$as_nitproben));	
	    $la_columna = array('nompro'=>' NOMBRE O RAZON SOCIAL DEL SUJETO RETENIDO','rifpro'=>'R.I.F./N.I.T. DEL CONTRIBUYENTE');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' =>9,  // Tamaño de Letras de los títulos
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array(1,1,1),
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530,
						 'cols'=>array('nompro'=>array('justification'=>'left','width'=>300),
									   'rifpro'=>array('justification'=>'left','width'=>200))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna); 
		unset($la_config); 
	
	    $io_pdf->ezSetDy(-5);
	    $la_data    = array(array('titcon'=>'CONCEPTO','concepto'=>$as_condoc));	
	    $la_columna = array('titcon'=>'','concepto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' =>9,  // Tamaño de Letras de los títulos
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array(1,1,1),
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530,
						 'cols'=>array('titcon'=>array('justification'=>'center','width'=>80),
									   'concepto'=>array('justification'=>'left','width'=>420))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna); 
		unset($la_config); 
	}// end function uf_print_encabezado
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($as_numsolpag,$as_fechapago,$as_numrecdoc,$ad_monrecdoc,$ad_porcentaje,$ad_monret,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: as_numsol // Número de recepción
		//	    		   as_concepto // Concepto de la solicitud
		//	    		   as_fechapago // Fecha de la recepción
		//	    		   ad_monto // monto de la recepción
		//	    		   ad_monret // monto retenido
		//	    		   ad_porcentaje // porcentaje de retención
		//	    		   as_numcon // numero de referencia
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por recepción
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 05/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      	global $ls_tiporeporte;
		if ($ls_tiporeporte==1)
		   {
		     $ls_tipbol = "Bs.F.";
		   }
		else
		   {
		     $ls_tipbol = "Bs.";
		   }
   	    $la_data    = array(array('name'=>''));
	    $la_columna = array('name'=>'');
	    $la_config  = array('showHeadings'=>0, // Mostrar encabezados
					        'fontSize' => 10, // Tamaño de Letras
					        'showLines'=>0, // Mostrar Líneas
					        'shaded'=>0, // Sombra entre líneas
					        'xOrientation'=>'center', // Orientación de la tabla
					        'width'=>500); // Ancho Máximo de la tabla						 
	    $io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
        unset($la_data);
		unset($la_columna);
		unset($la_config);   
		
		$la_data    = array(array('ordenpago'=>'<b>Orden de Pago</b>','fecha'=>'<b>Fecha de Factura</b>','numfac'=>'<b>Número de Factura</b>','monto'=>'<b>Base Imponible</b>','porcentaje'=>'<b>% Alicuota</b>','retenido'=>'<b>Impuesto Retenido ('.$ls_tipbol.')</b>'));	
	    $la_columna = array('ordenpago'=>'','fecha'=>'','numfac'=>'','monto'=>'','porcentaje'=>'','retenido'=>'');
	    $la_config  = array('showHeadings'=>0, // Mostrar encabezados
					        'fontSize' => 9.5, // Tamaño de Letras
					        'showLines'=>2, // Mostrar Líneas
					        'shaded'=>2, // Sombra entre líneas
					        'shadeCol'=>array(0.9,0.9,0.9),
						    'shadeCol2'=>array(0.9,0.9,0.9),
						    'xOrientation'=>'center', // Orientación de la tabla
					      //  'colGap'=>1,
						    'width'=>500,
						    'cols'=>array('ordenpago'=>array('justification'=>'center','width'=>90),
						                  'fecha'=>array('justification'=>'center','width'=>70),
						                  'numfac'=>array('justification'=>'center','width'=>90),
						                  'monto'=>array('justification'=>'center','width'=>90),
										  'porcentaje'=>array('justification'=>'center','width'=>80),
										  'retenido'=>array('justification'=>'center','width'=>80)));
	    $io_pdf->ezTable($la_data,$la_columna,'',$la_config);		
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		
		$la_data    = array(array('ordenpago'=>$as_numsolpag,'fecha'=>$as_fechapago,'numfac'=>$as_numrecdoc,'monto'=>$ad_monrecdoc,'porcentaje'=>$ad_porcentaje,'retenido'=>$ad_monret));	
	    $la_columna = array('ordenpago'=>'','fecha'=>'','numfac'=>'','monto'=>'','porcentaje'=>'','retenido'=>'');
	    $la_config  = array('showHeadings'=>0, // Mostrar encabezados
					        'fontSize' => 9.5, // Tamaño de Letras
					        'showLines'=>2, // Mostrar Líneas
					        'shaded'=>0, // Sombra entre líneas
					        'shadeCol'=>array(0.9,0.9,0.9),
						    'shadeCol2'=>array(0.9,0.9,0.9),
						    'xOrientation'=>'center', // Orientación de la tabla
					       // 'colGap'=>1,
						    'width'=>500,
						    'cols'=>array('ordenpago'=>array('justification'=>'center','width'=>90),
						                  'fecha'=>array('justification'=>'right','width'=>70),
						                  'numfac'=>array('justification'=>'right','width'=>90),
						                  'monto'=>array('justification'=>'right','width'=>90),
										  'porcentaje'=>array('justification'=>'center','width'=>80),
										  'retenido'=>array('justification'=>'right','width'=>80)));
  	    $io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_firma(&$io_pdf)
	{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_firmas
	//		   Access: private 
	//	    Arguments: io_pdf // Instancia de objeto pdf
	//    Description: función que imprime el detalle por recepción
	//	   Creado Por: Ing. Néstor Falcón.
	// Fecha Creación: 02/11/2007. 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$ls_rifageret = $_SESSION["la_empresa"]["rifemp"];
		$io_pdf->line(200,70,420,70);
		$io_pdf->addText(210,60,9,"FIRMA Y SELLO DEL AGENTE DE RETENCION");  
		$io_pdf->addText(260,50,9,"R.I.F.: ".$ls_rifageret);  
	
	}// end function uf_print_firmas
	//--------------------------------------------------------------------------------------------------------------------------------

	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_cxp_class_report.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../class_folder/class_funciones_cxp.php");
	
	$io_report    = new sigesp_cxp_class_report();
	$io_funciones = new class_funciones();				
	$io_fun_cxp   = new class_funciones_cxp();

	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>COMPROBANTE DE RETENCION DE I.S.L.R.</b>";
    $ls_agente=$_SESSION["la_empresa"]["nombre"];
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_comprobantes = $io_fun_cxp->uf_obtenervalor_get("comprobantes","");
	$ls_procedencias = $io_fun_cxp->uf_obtenervalor_get("procedencias","");
	$ls_tiporeporte  = $io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	
	global $ls_tiporeporte;
	if ($ls_tiporeporte==1)
	   {
		 require_once("sigesp_cxp_class_reportbsf.php");
		 $io_report=new sigesp_cxp_class_reportbsf();
	   }

	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$la_procedencias=split('<<<',$ls_procedencias);
		$la_comprobantes=split('<<<',$ls_comprobantes);
		$la_datos=array_unique($la_comprobantes);
		$li_totrow=count($la_datos);
		sort($la_datos,SORT_STRING);
		if($li_totrow<=0)
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}
		else
		{
			error_reporting(E_ALL);
			set_time_limit(1800);
			$io_pdf=new Cezpdf('LETTER','portrait');
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');
		     $io_pdf->ezSetCmMargins(5,3,3,3);
			$lb_valido=true;
			$ls_codigoant="";
			for ($li_z=0;($li_z<$li_totrow)&&($lb_valido);$li_z++)
			{
				$ls_numsol=$la_datos[$li_z];
				$ls_procede=$la_procedencias[$li_z];  
				if($ls_procede=="SCBBCH")
				{
					$lb_valido=$io_report->uf_retencionesislr_scb($ls_numsol);  
				}
				else
				{
					$lb_valido=$io_report->uf_retencionesislr_cxp($ls_numsol);
				}
				if($lb_valido)
				{
					$li_total=$io_report->DS->getRowCount("numdoc");
					for($li_i=1;($li_i<=$li_total);$li_i++)
					{
						$ls_tipproben=$io_report->DS->data["tipproben"][$li_i];
						if($ls_tipproben=="P")
						{
							$ls_codigo    = $io_report->DS->data["cod_pro"][$li_i];
							$ls_nombre    = $io_report->DS->data["proveedor"][$li_i];
							$ls_telefono  = $io_report->DS->data["telpro"][$li_i];
							$ls_direccion = $io_report->DS->data["dirpro"][$li_i];
							$ls_rif		  = $io_report->DS->data["rifpro"][$li_i];
						}
						else
						{
							$ls_codigo	  = $io_report->DS->data["ced_bene"][$li_i];
							$ls_nombre	  = $io_report->DS->data["beneficiario"][$li_i];
							$ls_telefono  = $io_report->DS->data["telbene"][$li_i];
							$ls_direccion = $io_report->DS->data["dirbene"][$li_i];
							$ls_rif		  = $io_report->DS->data["rifben"][$li_i];
						}						 
						$ls_nit		   = $io_report->DS->data["nit"][$li_i];
						$ls_consol	   = $io_report->DS->data["consol"][$li_i];
						$ls_numdoc	   = $io_report->DS->data["numdoc"][$li_i];
						$ls_numref	   = $io_report->DS->data["numref"][$li_i];
						$ld_fecemidoc  = $io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecemidoc"][$li_i]);
						$li_montotdoc  = number_format($io_report->DS->data["montotdoc"][$li_i],2,',','.');  
						$li_monobjret  = number_format($io_report->DS->data["monobjret"][$li_i],2,',','.');    
						$li_retenido   = number_format($io_report->DS->data["retenido"][$li_i],2,',','.');  
						$li_porcentaje = number_format($io_report->DS->data["porcentaje"][$li_i],2,',','.');
						if($ls_codigo!=$ls_codigoant)
						{
							if($li_z>=1)
							{
								uf_print_firma($io_pdf);
								$io_pdf->ezNewPage();  
							}
							$ls_codigoant=$ls_codigo;
						}
						uf_print_encabezado_pagina($ls_titulo,$ls_numsol,$io_pdf);
						uf_print_encabezado($ls_agente,$ls_nombre,$ls_rif,$ls_nit,$ls_consol,$io_pdf);
						uf_print_detalle($ls_numsol,$ld_fecemidoc,$ls_numdoc,$li_monobjret,$li_porcentaje,$li_retenido,$io_pdf);
		    			uf_print_firma($io_pdf);			  
						if ($li_i<$li_total)
					 	   {
						     $io_pdf->ezNewPage();  
					  	   }
					}
				}	
			}
			if($lb_valido) // Si no ocurrio ningún error
			{
				$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
				$io_pdf->ezStream(); // Mostramos el reporte
			}
			else  // Si hubo algún error
			{
				print("<script language=JavaScript>");
				print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
				print(" close();");
				print("</script>");		
			}
			unset($io_pdf);
		}
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_cxp);
?> 