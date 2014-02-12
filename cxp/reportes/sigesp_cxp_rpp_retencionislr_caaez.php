<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//    REPORTE: Retencion de ISLR
	//  ORGANISMO: COMPLEJO AGROINDUSTRIAL AZUCARERO EZEQUIEL ZAMORA. CAAEZ
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
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
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
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->addJpegFromFile('../../shared/imagebank/logo_caaez.jpg',90,530,570,50); // Agregar Logo
		$ls_decreto = "Decreto 1.808 Gaceta Oficial 36.203 del 12-05-1997";
		$li_tm=$io_pdf->getTextWidth(12,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,470,12,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(8,$ls_decreto);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,460,8,$ls_decreto);
		$io_pdf->addText(500,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(506,743,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado($as_agente,$as_nombre,$as_rif,$as_nit,$as_telefono,$as_direccion,&$io_pdf)
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
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 05/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_rifemp=$_SESSION["la_empresa"]["rifemp"];
		$ls_diremp=$_SESSION["la_empresa"]["direccion"];
		$ls_telemp=$_SESSION["la_empresa"]["telemp"]." / ".$_SESSION["la_empresa"]["faxemp"];

		$la_data[1]=array('name1'=>'<b>DATOS DEL AGENTE DE RETENCIÓN:</b>','name2'=>'<b>DATOS DEL SUJETO RETENIDO:</b>');
		$la_data[2]=array('name1'=>'<b>Organismo:</b>  '.$as_agente,'name2'=>'<b>Razón:</b>   '.$as_nombre);
		$la_data[3]=array('name1'=>'<b>RIF:</b>  '.$ls_rifemp,'name2'=>'<b>RIF:</b>   '.$as_rif.'          <b>NIT:</b>   '.$as_nit);
		$la_data[4]=array('name1'=>'<b>Dirección:</b>  '.$ls_diremp,'name2'=>'<b>Dirección:</b>   '.$as_direccion);
	
        $la_columna=array('name1'=>'','name2'=>'');
		$la_config= array('showHeadings'=>0, // Mostrar encabezados
						  'fontSize' => 10, // Tamaño de Letras
						  'showLines'=>2, // Mostrar Líneas
						  'shaded'=>0, // Sombra entre líneas
						  'shadeCol'=>array(0.9,0.9,0.9),
						  'shadeCol2'=>array(0.9,0.9,0.9),
						  'xOrientation'=>'center', // Orientación de la tabla
						  'colGap'=>1,
						  'width'=>530,
						  'cols'=>array('name1'=>array('justification'=>'left','width'=>370),
						                'name2'=>array('justification'=>'left','width'=>370))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_encabezado
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($aa_data,&$io_pdf)
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
      	  
		  $io_pdf->ezSetDy(-25);
          $la_datatit = array(array('fecact'=>'<b>Fecha</b>','numrecdoc'=>'<b>Nº Factura/Control</b>','fecemi'=>'<b>Fecha Emisión</b>','montotdoc'=>'<b>Monto</b>','monobjret'=>'<b>Base Imponible</b>','porcentaje'=>'<b>Porcentaje</b>','sustraendo'=>'<b>Sustraendo</b>','retenido'=>'<b>Monto Retenido</b>'));	
	      $la_columna = array('fecact'=>'','numrecdoc'=>'','fecemi'=>'','montotdoc'=>'','monobjret'=>'','porcentaje'=>'','sustraendo'=>'','retenido'=>'');
	      $la_config  = array('showHeadings'=>0,
					          'fontSize' => 10,
					          'showLines'=>2,
					          'shaded'=>2,
					      	  'shadeCol'=>array(0.9,0.9,0.9),
						  	  'shadeCol2'=>array(0.9,0.9,0.9),
						      'xOrientation'=>'center',
					          'colGap'=>1,
						      'width'=>530,
						      'cols'=>array('fecact'=>array('justification'=>'center','width'=>100),
						                    'numrecdoc'=>array('justification'=>'center','width'=>150),
						                    'fecemi'=>array('justification'=>'center','width'=>100),
						                    'montotdoc'=>array('justification'=>'center','width'=>90),
										    'monobjret'=>array('justification'=>'center','width'=>90),
										    'porcentaje'=>array('justification'=>'center','width'=>60),
										    'sustraendo'=>array('justification'=>'center','width'=>60),
										    'retenido'=>array('justification'=>'center','width'=>90)));
	      $io_pdf->ezTable($la_datatit,$la_columna,'',$la_config);		
	
	      $la_columna = array('fecact'=>'','numrecdoc'=>'','fecemi'=>'','montotdoc'=>'','monobjret'=>'','porcentaje'=>'','sustraendo'=>'','retenido'=>'');
	      $la_config  = array('showHeadings'=>0,
					          'fontSize' => 10,
					          'showLines'=>2,
					          'shaded'=>0,
					          'shadeCol'=>array(0.9,0.9,0.9),
						      'shadeCol2'=>array(0.9,0.9,0.9),
						      'xOrientation'=>'center',
					          'colGap'=>1,
						      'width'=>530,
						      'cols'=>array('fecact'=>array('justification'=>'center','width'=>100),
						                    'numrecdoc'=>array('justification'=>'center','width'=>150),
						                    'fecemi'=>array('justification'=>'center','width'=>100),
						                    'montotdoc'=>array('justification'=>'right','width'=>90),
										    'monobjret'=>array('justification'=>'right','width'=>90),
										    'porcentaje'=>array('justification'=>'center','width'=>60),
										    'sustraendo'=>array('justification'=>'right','width'=>60),
										    'retenido'=>array('justification'=>'right','width'=>90)));
	  $io_pdf->ezTable($aa_data,$la_columna,'',$la_config);			
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	 function uf_print_totales($aa_data,&$io_pdf)
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
	    $la_columna = array('totales'=>'','montotdoc'=>'','monobjret'=>'','porcentaje'=>'','sustraendo'=>'','retenido'=>'');
	    $la_config= array('showHeadings'=>0, // Mostrar encabezados
					      'fontSize' => 10, // Tamaño de Letras
					      'showLines'=>2, // Mostrar Líneas
					      'shaded'=>0, // Sombra entre líneas
					      'shadeCol'=>array(0.9,0.9,0.9),
						  'shadeCol2'=>array(0.9,0.9,0.9),
						  'xOrientation'=>'center', // Orientación de la tabla
					      'colGap'=>1,
						  'width'=>530,
						   'cols'=>array('totales'=>array('justification'=>'left','width'=>350),
						                 'montotdoc'=>array('justification'=>'right','width'=>90),
									  	 'monobjret'=>array('justification'=>'right','width'=>90),
										 'porcentaje'=>array('justification'=>'center','width'=>60),
										 'sustraendo'=>array('justification'=>'right','width'=>60),
										 'retenido'=>array('justification'=>'right','width'=>90)));
	    $io_pdf->ezTable($aa_data,$la_columna,'',$la_config);		
	 }//end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------


	function uf_print_sello(&$io_pdf)
	{
	  $io_pdf->line(300,50,540,50);
	  $io_pdf->addText(320,40,10,"FIRMA Y SELLO AGENTE DE RETENCION");  
	}

	require_once("sigesp_cxp_class_report.php");
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../class_folder/class_funciones_cxp.php");
	require_once("../../shared/class_folder/class_funciones.php");

	$io_report	  = new sigesp_cxp_class_report();
	$io_funciones = new class_funciones();				
	$io_fun_cxp	  = new class_funciones_cxp();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>COMPROBANTE DE RETENCION DE IMPUESTO SOBRE LA RENTA</b>";
    $ls_agente=$_SESSION["la_empresa"]["nombre"];
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_comprobantes = $io_fun_cxp->uf_obtenervalor_get("comprobantes","");
	$ls_procedencias = $io_fun_cxp->uf_obtenervalor_get("procedencias","");
	$ls_tiporeporte  = $io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_cxp_class_reportbsf.php");
		$io_report=new sigesp_cxp_class_reportbsf();
	}
	
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$la_procedencias = split('<<<',$ls_procedencias);
		$la_comprobantes = split('<<<',$ls_comprobantes);
		$la_datos        = array_unique($la_comprobantes);
		$li_totrow       = count($la_datos);
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
 		    $io_pdf=new Cezpdf('LETTER','landscape');                       // Instancia de la clase PDF
		    $io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		    $io_pdf->ezSetCmMargins(5.5,2.5,3,3);
			$lb_valido=true;
			$ls_codigoant="";
			
			for ($li_z=0;($li_z<$li_totrow)&&($lb_valido);$li_z++)
			    {
				  $ld_totfaccom = 0;
				  $ld_totbasimp = 0;
				  $ld_totmonret = 0;
				  
				  uf_print_encabezado_pagina($ls_titulo,$io_pdf);
				  $ls_numsol  = $la_datos[$li_z];
				  $ls_procede = $la_procedencias[$li_z];  
				  if ($ls_procede=="SCBBCH")
				     {
					   $lb_valido=$io_report->uf_retencionesislr_scb($ls_numsol);  
				     }
				  else
			 	     {
					   $lb_valido=$io_report->uf_retencionesislr_cxp($ls_numsol);
				     }
				  if ($lb_valido)
				     {
					   $li_total = $io_report->DS->getRowCount("numdoc");
					   for ($li_i=1;($li_i<=$li_total);$li_i++)
				 	       {
						     $ls_tipproben = $io_report->DS->data["tipproben"][$li_i];
						     if ($ls_tipproben=="P")
						        {
							      $ls_codigo    = $io_report->DS->data["cod_pro"][$li_i];
							      $ls_nombre    = $io_report->DS->data["proveedor"][$li_i];
							      $ls_telefono  = $io_report->DS->data["telpro"][$li_i];
							      $ls_direccion = $io_report->DS->data["dirpro"][$li_i];
							      $ls_rif       = $io_report->DS->data["rifpro"][$li_i];
						        }
						     else
						        {
								  $ls_codigo    = $io_report->DS->data["ced_bene"][$li_i];
								  $ls_nombre    = $io_report->DS->data["beneficiario"][$li_i];
								  $ls_telefono  = $io_report->DS->data["telbene"][$li_i];
								  $ls_direccion = $io_report->DS->data["dirbene"][$li_i];
								  $ls_rif       = $io_report->DS->data["rifben"][$li_i];
						        }						 
						     $ls_nit        = $io_report->DS->data["nit"][$li_i];
							 $ld_fecact     = date("d/m/Y");
							 $ls_consol     = $io_report->DS->data["consol"][$li_i];
						     $ls_numdoc     = $io_report->DS->data["numdoc"][$li_i];
						     $ls_numref     = $io_report->DS->data["numref"][$li_i];
						     $ld_fecemidoc  = $io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecemidoc"][$li_i]);
							 $ld_montotdoc  = $io_report->DS->data["montotdoc"][$li_i];  
						     $ld_monobjret  = $io_report->DS->data["monobjret"][$li_i];
						     $ld_monret     = $io_report->DS->data["retenido"][$li_i];  
							 $ld_mondeddoc  = $io_report->DS->data["mondeddoc"][$li_i];
							 $ld_monded     = $io_report->DS->data["monded"][$li_i];
							 $ld_montotdoc  = ($ld_montotdoc+$ld_mondeddoc);
							 $ld_totfaccom  = ($ld_totfaccom+$ld_montotdoc);//Monto Total Facturas del Comprobante.
							 $ld_totbasimp  = ($ld_totbasimp+$ld_monobjret);//Monto Total Bases Imponibles del Comprobante.
							 $ld_totmonret  = ($ld_totmonret+$ld_monret);   //Monto Total Retenido a Facturas del Comprobante. 
							 $ld_montotdoc  = number_format($ld_montotdoc,2,',','.'); 
							 $ld_monobjret  = number_format($ld_monobjret,2,',','.');    
						     $ld_porcentaje = number_format($io_report->DS->data["porcentaje"][$li_i],2,',','.');
							 $ld_monded     = number_format($ld_monded,2,',','.');
							 $ld_monret     = number_format($ld_monret,2,',','.');
							 
							 if ($ls_codigo!=$ls_codigoant)
						        {
							      if ($li_z>=1)
							         {
									   $io_pdf->ezNewPage();  
							         }
							      uf_print_encabezado($ls_agente,$ls_nombre,$ls_rif,$ls_nit,$ls_telefono,$ls_direccion,$io_pdf);
							      $ls_codigoant=$ls_codigo;
						        }	
							 $la_data[$li_i] = array('fecact'=>$ld_fecact,
							                         'numrecdoc'=>$ls_numdoc." / ".$ls_numref,
												  	 'fecemi'=>$ld_fecemidoc,
													 'montotdoc'=>$ld_montotdoc,
													 'monobjret'=>$ld_monobjret,
													 'porcentaje'=>$ld_porcentaje.'%',
													 'sustraendo'=>$ld_monded,
													 'retenido'=>$ld_monret);
					       }
					 }	
		          $ld_totfaccom  = number_format($ld_totfaccom,2,',','.');
				  $ld_totbasimp  = number_format($ld_totbasimp,2,',','.');
				  $ld_totmonret  = number_format($ld_totmonret,2,',','.');
				  $la_datatot[1] = array('totales'=>"<b>TOTALES</b>",'montotdoc'=>'<b>'.$ld_totfaccom.'</b>','concepto'=>"",'monobjret'=>'<b>'.$ld_totbasimp.'</b>','porcentaje'=>"",'sustraendo'=>"",'retenido'=>'<b>'.$ld_totmonret.'</b>');
				  uf_print_detalle($la_data,$io_pdf);
				  uf_print_totales($la_datatot,$io_pdf);
			      uf_print_sello($io_pdf);
				}
			 if ($lb_valido) // Si no ocurrio ningún error
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