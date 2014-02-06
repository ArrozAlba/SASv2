<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Formato de salida  de Solicitud de Pago
//  ORGANISMO: TELEFERICO DE MERIDA.
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
		// Fecha Creación: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_p_solicitudpago.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_numsol,$ad_fecregsol,$as_codproben,$as_nomproben,$as_rifpro,$as_consol,$as_monto,$ad_monsol,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_numsol // numero de la solicitud
		//	    		   ad_fecregsol // fecha de registro de la solicitud
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 11/03/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tiporeporte;
		
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(15,40,585,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],10,735,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->addText(300,735,12,$as_titulo); // Agregar el título
		$io_pdf->addText(120,767,10,"<b>".$_SESSION["la_empresa"]["nombre"]."</b>"); // Agregar la Fecha		
		$io_pdf->addText(120,757,10,"<b>".$_SESSION["la_empresa"]["titulo"]."</b>"); // Agregar la Fecha		

		$io_pdf->addText(432,735,11,"    <b>Nro:</b> ".$as_numsol);
		$io_pdf->addText(432,722,11,"<b>Fecha:</b> ".$ad_fecregsol);

		$la_data=array(array('name'=>"<b>RIF :</b>".$as_rifpro));				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>310, // Orientación de la tabla
						 'width'=>570, // Ancho de la tabla						 						 					 
						 'maxWidth'=>480); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);

		$la_data[1]=array('titulo'=>'<b>Beneficiario</b>');
		$la_data[2]=array('titulo'=>"                            ".$as_nomproben);
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xPos'=>310, // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('titulo'=>'<b> Concepto:   </b>'.$as_consol);
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>9, // Tamaño de Letras
						 'titleFontSize'=>9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xPos'=>310, // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$la_data[1]=array('titulo'=>'<b>Monto en Letras:   </b>'.$as_monto);
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>9, // Tamaño de Letras
						 'titleFontSize'=>9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xPos'=>310, // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
        
		if($ls_tiporeporte==1)
		{
			$ls_titulo=" Bs.F.";
		}
		else
		{
			$ls_titulo=" Bs.";
		}
			
		$la_data[1]=array('titulo'=>'<b>'.$ls_titulo.'</b>','contenido'=>$ad_monsol,);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>9, // Tamaño de Letras
						 'titleFontSize'=>9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xPos'=>310, // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>400), // Justificación y ancho de la columna
						 			   'contenido'=>array('justification'=>'center','width'=>170))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		// cuadro inferior
        $io_pdf->Rectangle(14,40,581,80);
		$io_pdf->line(14,107,594,107);		
		$io_pdf->line(14,94,594,94);		
		$io_pdf->line(150,40,150,94);		
		$io_pdf->line(290,40,290,120);		
		$io_pdf->line(440,40,440,120);				
		$io_pdf->addText(20,96,7,"FECHA :            /               /");
		$io_pdf->addText(295,96,7,"FECHA :            /               /");
		$io_pdf->addText(446,96,7,"FECHA :            /               /");

		$io_pdf->addText(20,85,7,"ELABORADO:");
		$io_pdf->addText(155,85,7,"REVISADO:");
        $io_pdf->addText(295,85,7,"FIRMA:");
        $io_pdf->addText(445,85,7,"FIRMA:");

		$io_pdf->addText(20,77,7,"FIRMA:");
        $io_pdf->addText(155,77,7,"FIRMA:");

		$io_pdf->addText(125,110,7,"CONTABILIDAD");
		$io_pdf->addText(335,110,7,"ADMINISTRACION");
		$io_pdf->addText(475,110,7,"GERENCIA GENERAL");
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		$io_pdf->ezSetCmMargins(6,4.5,3,2); // Configuración de los margenes en centímetros
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_recepcion($la_data,$ai_totsubtot,$ai_tottot,$ai_totcar,$ai_totded,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//				   ai_totsubtot // acumulado del subtotal
		//				   ai_tottot // acumulado del total
		//				   ai_totcar // acumulado de los cargos
		//				   ai_totded // acumulado de las deducciones
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 20/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulo=" Bs.F.";
		}
		else
		{
			$ls_titulo=" Bs.";
		}

		$la_datatit[1]=array('numrecdoc'=>'COMPROMISO</b>',
		                     'fecemisol'=>'FECHA</b>',
							 'subtotdoc'=>'MONTO</b>',
							 'mondeddoc'=>'DEDUCCION</b>',
							 'moncardoc'=>'IMPUESTO</b>',
							 'montotdoc'=>'TOTAL</b>');
		$la_columnas=array('numrecdoc'=>'','fecemisol'=>'','subtotdoc'=>'','mondeddoc'=>'','moncardoc'=>'','montotdoc'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>8, // Tamaño de Letras
						 'titleFontSize'=>8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.8,0.8,0.8),// Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xPos'=>310, // Orientación de la tabla
						 'cols'=>array('numrecdoc'=>array('justification'=>'center','width'=>130), // Justificación y ancho de la columna
						 			   'fecemisol'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'subtotdoc'=>array('justification'=>'center','width'=>92), // Justificación y ancho de la columna
						 			   'mondeddoc'=>array('justification'=>'center','width'=>92), // Justificación y ancho de la columna
						 			   'moncardoc'=>array('justification'=>'center','width'=>92), // Justificación y ancho de la columna
						 			   'montotdoc'=>array('justification'=>'center','width'=>92))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);

		$la_columnas=array('numrecdoc'=>'','fecemisol'=>'', 'subtotdoc'=>'','mondeddoc'=>'','moncardoc'=>'','montotdoc'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>8, // Tamaño de Letras
						 'titleFontSize'=>8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xPos'=>310, // Orientación de la tabla
						 'cols'=>array('numrecdoc'=>array('justification'=>'center','width'=>130),// Justificación y ancho de la columna
						 			   'fecemisol'=>array('justification'=>'left','width'=>70),   // Justificación y ancho de la columna
						 			   'subtotdoc'=>array('justification'=>'right','width'=>92),  // Justificación y ancho de la columna
						 			   'mondeddoc'=>array('justification'=>'right','width'=>92),  // Justificación y ancho de la columna
						 			   'moncardoc'=>array('justification'=>'right','width'=>92),  // Justificación y ancho de la columna
						 			   'montotdoc'=>array('justification'=>'right','width'=>92)));// Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		
        $la_data2=array(array('name'=>'<b>__________________________________________________________________________________________</b>'));
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Lineas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'justification'=>'left',
						 'xPos'=>430, // Orientacion de la tabla
						 'width'=>500, // Ancho de la tabla						 
						 'maxWidth'=>500); // Ancho Minimo de la tabla
		$io_pdf->ezTable($la_data2,$la_columna,'',$la_config);
		
		$la_datatot[1]=array('subtotdoc'=>$ai_totsubtot,'mondeddoc'=>$ai_totded,'moncardoc'=>$ai_totcar,'montotdoc'=>$ai_tottot);
		$la_columnas=array('subtotdoc'=>'','mondeddoc'=>'','moncardoc'=>'','montotdoc'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>9, // Tamaño de Letras
						 'titleFontSize'=>9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>368, // Ancho de la tabla
						 'maxWidth'=>368, // Ancho Máximo de la tabla
						 'xPos'=>406, // Orientacion de la tabla
						 'cols'=>array('subtotdoc'=>array('justification'=>'right','width'=>92),  // Justificación y ancho de la columna
						 			   'mondeddoc'=>array('justification'=>'right','width'=>92),  // Justificación y ancho de la columna
						 			   'moncardoc'=>array('justification'=>'right','width'=>92),  // Justificación y ancho de la columna
						 			   'montotdoc'=>array('justification'=>'right','width'=>92)));// Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatot,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_spg($aa_data,$ad_totpre,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuentas
		//		   Access: private 
		//	    Arguments: aa_data // arreglo de información
		//	    		   ai_totpre // monto total de presupuesto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle presupuestario
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		global $ls_estmodest;
		global $ls_tiporeporte;
		if($ls_estmodest==1)
		{
			$ls_titcuentas="ESTRUCTURA PRESUPUESTARIA";
		}
		else
		{
			$ls_titcuentas="ESTRUCTURA PROGRAMATICA";
		}
		if($ls_tiporeporte==1)
		{
			$ls_titulo=" Bs.F.";
		}
		else
		{
			$ls_titulo=" Bs.";
		}
		
		$la_datatit[1]=array('titulo'=>'CUENTAS PRESUPUESTARIAS');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=> 8, // Tamaño de Letras
						 'titleFontSize'=>8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xPos'=>310, // Orientacion de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->ezSetDy(-2);
		
		$la_datasercon= array(array('estpro'=>"ESTRUCTURA PRESUPUESTARIA",'spg_cuenta'=>"CUENTA PRESUPUESTARIA",'denominacion'=>"DENOMINACION",'monto'=>"MONTO"));
		$la_columna=array('estpro'=>'','spg_cuenta'=>'','denominacion'=>'','monto'=>'');
						  
		$la_config=array('showHeadings'=>0,
						 'fontSize'=>8,
						 'titleFontSize'=>8,
						 'showLines'=>1,
						 'shaded'=>2,
						 'shadeCol2'=>array(0.8,0.8,0.8),
						 'width'=>500,
						 'maxWidth'=>500,
						 'xPos'=>310,
						 'cols'=>array('estpro'=>array('justification'=>'center','width'=>135),
                                       'spg_cuenta'=>array('justification'=>'center','width'=>84),
									   'denominacion'=>array('justification'=>'center','width'=>271),
									   'monto'=>array('justification'=>'center','width'=>80)));
		$io_pdf->ezTable($la_datasercon,$la_columna,'',$la_config);
		
		$la_columna=array('codestpro'=>'','spg_cuenta'=>'','denominacion'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>8, // Tamaño de Letras
						 'titleFontSize'=>8,  // Tamaño de Letras de los titulos
						 'showLines'=>1, // Mostrar Lineas
						 'shaded'=>0, // Sombra entre lineas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Minimo de la tabla
						 'xPos'=>310, // Orientacion de la tabla
						 'cols'=>array('codestpro'=>array('justification'=>'center','width'=>135),
                                       'spg_cuenta'=>array('justification'=>'center','width'=>84),
									   'denominacion'=>array('justification'=>'left','width'=>271),
									   'monto'=>array('justification'=>'right','width'=>80)
									  )
						); // Justificacion y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columna,'',$la_config);
		
		$la_data=array(array('name'=>'<b>                                      _______________</b>'), 		              
   		               array('name'=>'                                         '.$ad_totpre));
		
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Lineas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.8,0.8,0.8), // Sombra entre líneas
						 'justification'=>'right',
						 'xPos'=>940, // Orientacion de la tabla
						 'width'=>1000, // Ancho de la tabla						 
						 'maxWidth'=>1000); // Ancho Minimo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_scg($aa_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuentas
		//		   Access: private 
		//	    Arguments: aa_data // arreglo de información
		//	    		   si_totdeb // total monto debe
		//	    		   si_tothab // total monto haber
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle contable
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 27/05/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulo=" Bs.F.";
		}
		else
		{
			$ls_titulo=" Bs.";
		}
		
		$la_datacon[1]=array('titulo'=>'CUENTAS CONTABLES');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=> 8, // Tamaño de Letras
						 'titleFontSize'=>8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.8,0.8,0.8), // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xPos'=>310, // Orientacion de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datacon,$la_columnas,'',$la_config);
		unset($la_datacon);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->ezSetDy(-2);
		
		$la_datacon[1]=array('sc_cuenta'=>'CUENTA CONTABLE','denominacion'=>'DENOMINACION','mondeb'=>'DEBE','monhab'=>'HABER');
		$la_columnas=array('sc_cuenta'=>'','denominacion'=>'','mondeb'=>'','monhab'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>8, // Tamaño de Letras
						 'titleFontSize'=> 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.8,0.8,0.8), // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xPos'=>310, // Orientación de la tabla
						 'cols'=>array('sc_cuenta'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'center','width'=>340), // Justificación y ancho de la columna
						 			   'mondeb'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'monhab'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datacon,$la_columnas,'',$la_config);
		unset($la_datacon);
		unset($la_columnas);
		unset($la_config);
		
		$la_columnas=array('sc_cuenta'=>'','denominacion'=>'','mondeb'=>'','monhab'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>8, // Tamaño de Letras
						 'titleFontSize'=>8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xPos'=>310, // Orientación de la tabla
						 'cols'=>array('sc_cuenta'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>340), // Justificación y ancho de la columna
						 			   'mondeb'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'monhab'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columnas,'',$la_config);
		unset($la_datacon);
		unset($la_columnas);
		unset($la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_debe,$ad_haber,$as_obssolpag,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación : 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>________________     _______________</b>'), 		              
   		               array('name'=>'          '.$ad_debe.'         '.$ad_haber.'')
					  );				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'justification'=>'left',
						 'xPos'=>940, // Orientación de la tabla
						 'width'=>1000, // Ancho de la tabla						 
						 'maxWidth'=>1000); // Ancho Mínimo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		
		unset($la_data);
		unset($la_columna);
		unset($la_config);	
		
		$la_data=array(array('name'=>''));				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Lineas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'justification'=>'left',
						 'xPos'=>960, // Orientación de la tabla
						 'width'=>1000, // Ancho de la tabla						 
						 'maxWidth'=>1000); // Ancho Minimo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		
		unset($la_data);
		unset($la_columna);
		unset($la_config);	
				
		$la_data=array(array('name'=>'OBSERVACIONES : '.$as_obssolpag));						
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>310, // Orientación de la tabla
						 'width'=>570, // Ancho de la tabla						 						 					 
						 'maxWidth'=>570,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>570))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_total_bsf($ai_monsolaux,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuentas
		//		   Access: private 
		//	    Arguments: ai_monsolaux // Monto Auxiliar en Bs.F.
		//	    		   io_pdf       // Instancia de objeto pdf
		//    Description: Funcion que imprime el monto total de la solicitud en Bs.F.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_datatot[1]=array('titulo'=>'<b>Total Bs.F.</b>','monto'=>$ai_monsolaux);
		$la_columnas=array('titulo'=>'<b>Factura</b>',
						   'monto'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>480), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatot,$la_columnas,'',$la_config);
	}// end function uf_print_total_bsf
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
	//Instancio a la clase de conversión de numeros a letras.
	include("../../shared/class_folder/class_numero_a_letra.php");
	$numalet= new class_numero_a_letra();
	//imprime numero con los valore por defecto
	//cambia a minusculas
	$numalet->setMayusculas(1);
	//cambia a femenino
	$numalet->setGenero(1);
	//cambia moneda
	$numalet->setMoneda("Bolivares");
	//cambia prefijo
	$numalet->setPrefijo("***");
	//cambia sufijo
	$numalet->setSufijo("***");
		
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>ORDEN DE PAGO</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_numsol=$io_fun_cxp->uf_obtenervalor_get("numsol","");
	$ls_tiporeporte=$io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	require_once("../../shared/ezpdf/class.ezpdf.php");
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_cxp_class_reportbsf.php");
		$io_report=new sigesp_cxp_class_reportbsf();
	}
	else
	{
		require_once("sigesp_cxp_class_report.php");
		$io_report=new sigesp_cxp_class_report();
	}	
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_select_solicitud($ls_numsol); // Cargar el DS con los datos del reporte
		if($lb_valido==false) // Existe algún error ó no hay registros
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}
		else  // Imprimimos el reporte
		{
			error_reporting(E_ALL);
			set_time_limit(1800);
			$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		    $io_pdf->ezSetCmMargins(2,11,3,2); // Configuracion de los margenes en centimetros				
			$li_totrow=$io_report->DS->getRowCount("numsol");
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$ls_numsol    = $io_report->DS->data["numsol"][$li_i];
				$ls_codpro    = $io_report->DS->data["cod_pro"][$li_i];
				$ls_rifpro    = $io_report->DS->data["rifpro"][$li_i];
				$ls_cedbene   = $io_report->DS->data["ced_bene"][$li_i];
				$ls_denfuefin = $io_report->DS->data["denfuefin"][$li_i];
				$ls_nomproben = $io_report->DS->data["nombre"][$li_i];
				$ld_fecemisol = $io_report->DS->data["fecemisol"][$li_i];
				$ls_consol    = $io_report->DS->data["consol"][$li_i];
				$ls_obssol    = $io_report->DS->data["obssol"][$li_i];
				$li_monsol    = $io_report->DS->data["monsol"][$li_i];
				$numalet->setNumero($li_monsol);
				$ls_monto     = $numalet->letra();
				$li_monsol    = number_format($li_monsol,2,",",".");
				$ld_fecemisol = $io_funciones->uf_convertirfecmostrar($ld_fecemisol);
				if($ls_codpro!="----------")
				{
					$ls_codproben = $ls_codpro;
				}
				else
				{
					$ls_codproben = $ls_cedbene;
				}						
				if($ls_tiporeporte==0)
				{
					//$li_monsolaux = $io_report->DS->data["monsolaux"][$li_i];
					//$li_monsolaux = number_format($li_monsolaux,2,",",".");
				}
				uf_print_encabezado_pagina($ls_titulo,$ls_numsol,$ld_fecemisol,$ls_codproben,$ls_nomproben,$ls_rifpro,$ls_consol,$ls_monto,$li_monsol,&$io_pdf);
				//////////////////////////  GRID RECEPCIONES DE DOCUMENTOS		//////////////////////////////////////
				$io_report->ds_detalle->reset_ds();
				$lb_valido=$io_report->uf_select_rec_doc_solicitud($ls_numsol); // Cargar el DS con los datos del reporte
				if($lb_valido)
				{
					$li_totrowdet=$io_report->ds_detalle_rec->getRowCount("numrecdoc");
					$la_data="";
					$li_totsubtot=0;
					$li_tottot=0;
					$li_totcar=0;
					$li_totded=0;
					for($li_s=1;$li_s<=$li_totrowdet;$li_s++)
					{
						$ls_numrecdoc=$io_report->ds_detalle_rec->data["numrecdoc"][$li_s];
						$ld_fecemidoc=$io_report->ds_detalle_rec->data["fecemidoc"][$li_s];
						$ls_numdoccomspg=$io_report->ds_detalle_rec->data["numdoccomspg"][$li_s];
						$li_mondeddoc=$io_report->ds_detalle_rec->data["mondeddoc"][$li_s];
						$li_moncardoc=$io_report->ds_detalle_rec->data["moncardoc"][$li_s];
						$li_montotdoc=$io_report->ds_detalle_rec->data["montotdoc"][$li_s];
						$li_subtotdoc=($li_montotdoc-$li_moncardoc+$li_mondeddoc);
						$li_totsubtot=$li_totsubtot + $li_subtotdoc;
						$li_tottot=$li_tottot + $li_montotdoc;
						$li_totcar=$li_totcar + $li_moncardoc;
						$li_totded=$li_totded + $li_mondeddoc;

						$ld_fecemidoc=$io_funciones->uf_convertirfecmostrar($ld_fecemidoc);
						$li_mondeddoc=number_format($li_mondeddoc,2,",",".");
						$li_moncardoc=number_format($li_moncardoc,2,",",".");
						$li_montotdoc=number_format($li_montotdoc,2,",",".");
						$li_subtotdoc=number_format($li_subtotdoc,2,",",".");
						$la_data[$li_s]=array('numrecdoc'=>$ls_numrecdoc,'fecemisol'=>$ld_fecemidoc,'mondeddoc'=>$li_mondeddoc,
											  'moncardoc'=>$li_moncardoc,'montotdoc'=>$li_montotdoc,'subtotdoc'=>$li_subtotdoc);
					}

					$li_totsubtot=number_format($li_totsubtot,2,",",".");
					$li_tottot=number_format($li_tottot,2,",",".");
					$li_totcar=number_format($li_totcar,2,",",".");
					$li_totded=number_format($li_totded,2,",",".");
					uf_print_detalle_recepcion($la_data,$li_totsubtot,$li_tottot,$li_totcar,$li_totded,&$io_pdf);
					unset($la_data);
//////////////////////////  GRID RECEPCIONES DE DOCUMENTOS		//////////////////////////////////////
//////////////////////////   GRID DETALLE PRESUPUESTARIO		//////////////////////////////////////
					$lb_valido=$io_report->uf_select_detalle_spg($ls_numsol); // Cargar el DS con los datos del reporte
					if($lb_valido)
					{
						$li_totrowspg=$io_report->ds_detalle_spg->getRowCount("codestpro");
						$la_data="";
						$li_totpre=0;
						for($li_s=1;$li_s<=$li_totrowspg;$li_s++)
						{
							$ls_codestpro=$io_report->ds_detalle_spg->data["codestpro"][$li_s];
							$ls_spgcuenta=$io_report->ds_detalle_spg->data["spg_cuenta"][$li_s];
							$ls_denominacion=$io_report->ds_detalle_spg->data["denominacion"][$li_s];
							$li_monto=$io_report->ds_detalle_spg->data["monto"][$li_s];
							$li_totpre=$li_totpre+$li_monto;
							$li_monto=number_format($li_monto,2,",",".");
							$io_fun_cxp->uf_formatoprogramatica($ls_codestpro,&$ls_programatica);
							$la_data[$li_s]=array('codestpro'=>$ls_programatica,'spg_cuenta'=>$ls_spgcuenta,
												  'denominacion'=>$ls_denominacion,'monto'=>$li_monto);
						}	
						$li_totpre=number_format($li_totpre,2,",",".");
						uf_print_detalle_spg($la_data,$li_totpre,&$io_pdf);
						unset($la_data);
					}
					//////////////////////////      GRID DETALLE PRESUPUESTARIO		//////////////////////////////////////
					//////////////////////////         GRID DETALLE CONTABLE	    //////////////////////////////////////
					$lb_valido=$io_report->uf_select_detalle_scg($ls_numsol); // Cargar el DS con los datos del reporte
					if($lb_valido)
					{
						$li_totrowscg=$io_report->ds_detalle_scg->getRowCount("sc_cuenta");
						$la_data="";
						$li_totdeb=0;
						$li_tothab=0;
						for($li_s=1;$li_s<=$li_totrowscg;$li_s++)
						{
							$ls_sccuenta=trim($io_report->ds_detalle_scg->data["sc_cuenta"][$li_s]);
							$ls_debhab=trim($io_report->ds_detalle_scg->data["debhab"][$li_s]);
							$ls_denominacion=trim($io_report->ds_detalle_scg->data["denominacion"][$li_s]);
							$li_monto=$io_report->ds_detalle_scg->data["monto"][$li_s];
							if($ls_debhab=="D")
							{
								$li_montodebe=$li_monto;
								$li_montohab="";
								$li_totdeb=$li_totdeb+$li_montodebe;
								$li_montodebe=number_format($li_montodebe,2,",",".");
							}
							else
							{
								$li_montodebe="";
								$li_montohab=$li_monto;
								$li_tothab=$li_tothab+$li_montohab;
								$li_montohab=number_format($li_montohab,2,",",".");
							}
							$la_data[$li_s]=array('sc_cuenta'=>$ls_sccuenta,'denominacion'=>$ls_denominacion,
												  'mondeb'=>$li_montodebe,'monhab'=>$li_montohab);
						}	
						$li_totdeb=number_format($li_totdeb,2,",",".");
						$li_tothab=number_format($li_tothab,2,",",".");
						uf_print_detalle_scg($la_data,&$io_pdf);
						unset($la_data);
					}
                  uf_print_pie_cabecera($li_totdeb,$li_tothab,$ls_obssol,&$io_pdf);
			     //////////////////////////         GRID DETALLE CONTABLE	    //////////////////////////////////////
				}
			}
		}
		if($ls_tiporeporte==0)
		{
			//uf_print_total_bsf($li_monsolaux,&$io_pdf);
		}
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		
	}

?>
