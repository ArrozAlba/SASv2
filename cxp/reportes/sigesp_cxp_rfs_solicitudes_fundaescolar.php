<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Formato de salida  de Solicitud de Pago
//  ORGANISMO: FUNDAESCOLAR
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
	function uf_print_encabezado_pagina($as_titulo,$as_numsol,$ad_fecregsol,&$io_pdf)
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
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->rectangle(150,705,375,40);
        $io_pdf->line(380,705,380,745);
		$io_pdf->line(380,725,525,725);
		
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,711,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo Fundaescolar.
		$io_pdf->addJpegFromFile('../../shared/imagebank/logo_gel.jpg',530,705,60,50); // Agregar Logo Gobernación del Estado Lara.
		
		$io_pdf->addText(220,725,13,"<b>$as_titulo</b>");// Agregar el título
		$io_pdf->addText(385,732,11,"<b> Nro.: </b> ".$as_numsol);   
		$io_pdf->addText(385,712,11,"<b>Fecha: </b> ".$ad_fecregsol);
		
		//Recuadro Inferior.
   		$io_pdf->rectangle(26,55,557,70);
		$io_pdf->line(26,68,160,68);		
		$io_pdf->line(26,108,582,108);		
		$io_pdf->addText(60,113,7,"CONTABILIZADO POR");
		$io_pdf->addText(31,60,7,"PRESUPUESTO");
		$io_pdf->addText(98,60,7,"CONTABILIDAD");
		$io_pdf->line(160,55,160,125);		
		$io_pdf->line(93,55,93,107);		
		$io_pdf->addText(195,113,7,"REVISADO POR");
		$io_pdf->addText(330,113,7,"AUTORIZADO POR");
		$io_pdf->line(290,55,290,125);		
		$io_pdf->addText(480,113,7,"APROBADO POR");
		$io_pdf->line(435,55,435,125);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_nompro,$as_dirpro,$as_estsolpag,$as_denfuefin,$as_consol,$as_obssol,$ad_monsol,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_numsol    // Numero de la Solicitud de Pago
		//	   			   as_codigo    // Codigo del Proveedor / Beneficiario
		//	   			   as_nombre    // Nombre del Proveedor / Beneficiario
		//	   			   as_denfuefin // Denominacion de la fuente de financiamiento
		//	   			   ad_fecemisol // Fecha de Emision de la Solicitud
		//	   			   as_consol    // Concepto de la Solicitud
		//	   			   as_obssol    // Observaciones de la Solicitud
		//	   			   ai_monsol    // Monto de la Solicitud
		//	   			   as_monto     // Monto de la Solicitud en letras
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera 
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 17/05/2007
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

		$la_data=array(array('name'=>'<b>Proveedor: </b>'.$as_nompro),
					   array('name'=>'<b>Dirección: </b>'.$as_dirpro),
					   array('name'=>'<b>Estatus: </b>'.$as_estsolpag),
		               array('name'=>'<b>Fuente de Financiamiento: </b>'.$as_denfuefin),	   		               
					   array('name'=>'<b>Concepto: </b>'.$as_consol),					   
					   array('name'=>'<b>Observación: </b>'.$as_obssol));			
					
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Lineas
						 'shaded'=>0, // Sombra entre lineas
						 'xPos'=>310, // Orientación de la tabla
						 'width'=>570, // Ancho de la tabla						 
						 'xOrientation'=>'center', // Ancho de la tabla						 
						 'maxWidth'=>500); // Ancho Mínimo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_cabecera
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
		$io_pdf->ezSetDy(-2);
		
		$la_data2=array(array('name'=>'RECEPCIONES DE DOCUMENTOS'));				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>310, // Orientacion de la tabla
						 'width'=>500, // Ancho de la tabla						 
						 'maxWidth'=>500,
						 'cols'=>array('name'=>array('justification'=>'center','width'=>560))); // Ancho Mínimo de la tabla
		$io_pdf->ezTable($la_data2,$la_columna,'',$la_config);
		unset($la_data2);
		unset($la_columna);
		unset($la_config);
		
		$la_datatit[1]=array('numrecdoc'=>'<b>Nro del DOCUMENTO</b>',
							 'fecemisol'=>'<b>FECHA DEL DOCUMENTO</b>',
							 'fecdoccom'=>'<b>FECHA DEL COMPROMISO</b>',
							 'subtotdoc'=>'<b>MONTO</b>',
							 'mondeddoc'=>'<b>DEDUCCION</b>',
							 'moncardoc'=>'<b>IMPUESTO</b>',
							 'montotdoc'=>'<b>MONTO</b>');
		$la_columnas=array('numrecdoc'=>'','fecemisol'=>'','fecdoccom'=>'','subtotdoc'=>'','mondeddoc'=>'','moncardoc'=>'','montotdoc'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('numrecdoc'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'fecemisol'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'fecdoccom'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'subtotdoc'=>array('justification'=>'center','width'=>85), // Justificación y ancho de la columna
						 			   'mondeddoc'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'moncardoc'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'montotdoc'=>array('justification'=>'center','width'=>85))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
        unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		
		$la_columnas=array('numrecdoc'=>'','fecemisol'=>'','fecdoccom'=>'','subtotdoc'=>'','moncardoc'=>'','mondeddoc'=>'','montotdoc'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('numrecdoc'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'fecemisol'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'fecdoccom'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'subtotdoc'=>array('justification'=>'right','width'=>85), // Justificación y ancho de la columna
						 			   'mondeddoc'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'moncardoc'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'montotdoc'=>array('justification'=>'right','width'=>85))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_spg($aa_data,$ai_totpre,&$io_pdf)
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
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		$io_pdf->ezSetDy(-2);
		
		$la_datasercon= array(array('estpro'=>"<b>ESTRUCTURA PRESUPUESTARIA</b>",
		                            'spg_cuenta'=>"<b>CUENTA PRESUPUESTARIA</b>",
									'denominacion'=>"<b>DENOMINACION</b>",
									'monto'=>"<b>MONTO </b>"));
		$la_columna=array('estpro'=>'','spg_cuenta'=>'','denominacion'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los titulos
						 'showLines'=>1, // Mostrar Lineas
						 'shaded'=>2, // Sombra entre lineas
						 'shadeCol2'=>array(0.8,0.8,0.8), // Sombra entre lineas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Minimo de la tabla
						 'xOrientation'=>'center', // Orientacion de la tabla
						 'cols'=>array('estpro'=>array('justification'=>'center','width'=>165),
                                       'spg_cuenta'=>array('justification'=>'center','width'=>84),
									   'denominacion'=>array('justification'=>'center','width'=>236),
									   'monto'=>array('justification'=>'center','width'=>85))); // Justificacion y ancho de la columna
		$io_pdf->ezTable($la_datasercon,$la_columna,'',$la_config);
		unset($la_datasercon);
		unset($la_columna);
		unset($la_config);
		
		$la_columnas=array('codestpro'=>'','spg_cuenta'=>'','denominacion'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>8, // Tamaño de Letras
						 'titleFontSize'=>8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codestpro'=>array('justification'=>'center','width'=>165), // Justificación y ancho de la columna
						 			   'spg_cuenta'=>array('justification'=>'center','width'=>84), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>236), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>85))); // Justificación y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_scg($aa_data,$ai_totdeb,$ai_tothab,&$io_pdf)
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
		$la_datatit[1]=array('titulo'=>'CUENTAS CONTABLES');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>8, // Tamaño de Letras
						 'titleFontSize'=>8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		$io_pdf->ezSetDy(-2);
		$la_data[1]=array('sc_cuenta'=>'<b>CUENTA CONTABLE</b>',
		                  'denominacion'=>'<b>DENOMINACION</b>',
						  'mondeb'=>'<b>DEBE</b>',
						  'monhab'=>'<b>HABER</b>');
		$la_columnas=array('sc_cuenta'=>'','denominacion'=>'','mondeb'=>'','monhab'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>8, // Tamaño de Letras
						 'titleFontSize'=>9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('sc_cuenta'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'center','width'=>310), // Justificación y ancho de la columna
						 			   'mondeb'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'monhab'=>array('justification'=>'center','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		
		$la_columnas=array('sc_cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Denominacion</b>',
						   'mondeb'=>'<b>Debe</b>',
						   'monhab'=>'<b>Haber</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>8, // Tamaño de Letras
						 'titleFontSize'=>12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('sc_cuenta'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>310), // Justificación y ancho de la columna
						 			   'mondeb'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'monhab'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($aa_data,$la_columnas,'',$la_config);
		
	    $la_data=array(array('name'=>'<b>________________   _________________</b>'), 		              
   		               array('name'=>'       '.$ai_totdeb.'               '.$ai_tothab.''));				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Lineas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'justification'=>'left',
						 'xPos'=>930, // Orientacion de la tabla
						 'width'=>1000, // Ancho de la tabla						 
						 'maxWidth'=>1000); // Ancho Minimo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

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
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>480), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatot,$la_columnas,'',$la_config);
	}// end function uf_print_total_bsf
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_carded($la_datacar,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_carded
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf  // Objeto PDF
		//    Description: Función que imprime el detalle.
		//	   Creado Por: Ing. Yesenia Moreno.                   Modificado Por: Ing. Néstor Falcón.
		// Fecha Creación: 21/04/2006                         Fecha Modificación: 18/10/2007. 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////						
        $la_data1=array(array('name'=>''));				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, 			  // Mostrar encabezados
						 'fontSize' => 9,  				  // Tamaño de Letras
						 'showLines'=>0,    			  // Mostrar Líneas
						 'shaded'=>0,       			  // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', 		  // Orientación de la tabla
						 'width'=>900,      			  // Ancho de la tabla						 
						 'maxWidth'=>900);  			  // Ancho Mínimo de la tabla
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);	
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		//-----------------------------------------------------------------------------------------------------------------																												
		$la_columnacar=array('denominacion'=>'<b>Denominación</b>',						  
						     'monobjret'=>'<b>Monto Objeto Retención y/o Base Imponible</b>',
							 'objret'=>'<b>Retención y/o Impuesto</b>');
		$la_configcar=array('showHeadings'=>1,   // Mostrar encabezados
						    'fontSize' =>8,      // Tamaño de Letras
						    'titleFontSize' =>9, // Tamaño de Letras de los títulos
						    'showLines'=>1, 	 // Mostrar Líneas
						    'shaded'=>0,         // Sombra entre líneas
						    'width'=>500,        // Ancho de la tabla
						    'maxWidth'=>500,     // Ancho Mínimo de la tabla
						    'xPos'=>395,         // Orientación de la tabla
						    'cols'=>array('denominacion'=>array('justification'=>'center','width'=>180), // Justificación y ancho de la columna
						 			      'monobjret'=>array('justification'=>'center','width'=>100),    // Justificación y ancho de la columna
						 			      'objret'=>array('justification'=>'right','width'=>100)));      // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datacar,$la_columnacar,'<b>CARGOS Y DEDUCCIONES</b>',$la_configcar);			
	}
	//--------------------------------------------------------------------------------------------------------------------------------	
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_monto_letras($as_monto,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_monto_letras
		//		    Acess: private 
		//	    Arguments: ldec_monto : Monto del cheque
		//	    		   ls_nomproben:  Nombre del proveedor o beneficiario
		//	    		   ls_monto : Monto en letras
		//	    		   ls_fecha : Fecha del cheque
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Nelson Barraez                    Modificado Por: Ing. Néstor Falcón.
		// Fecha Creación: 25/04/2006                         Fecha Modificación: 18/10/2007.
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$la_data=array(array('data'=>"<b>".$as_monto."</b>"));
		$la_columna=array('data'=>'');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' =>8, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>310, // Orientación de la tabla
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570,
						 'cols'=>array('data'=>array('justification'=>'center','width'=>570))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);			
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../class_folder/class_funciones_cxp.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/class_numero_a_letra.php");
	
	$io_funciones = new class_funciones();				
	$io_fun_cxp   = new class_funciones_cxp();
	$ls_estmodest = $_SESSION["la_empresa"]["estmodest"];
	$numalet      = new class_numero_a_letra();

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
		
	if($ls_estmodest==1)
	{
		$ls_titcuentas="Estructura Presupuestaria";
	}
	else
	{
		$ls_titcuentas="Estructura Programatica";
	}
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="ORDEN DE PAGO";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_numsol      = $io_fun_cxp->uf_obtenervalor_get("numsol","");
	$ls_tiporeporte = $io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	$ls_codemp      = $_SESSION["la_empresa"]["codemp"];
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
		    $io_pdf->ezSetCmMargins(3.5,4,3,3); // Configuracion de los margenes en centimetros		
			$io_pdf->ezStartPageNumbers(570,43,8,'','',1); // Insertar el número de página
			$li_totrow=$io_report->DS->getRowCount("numsol");
			for ($li_i=1;$li_i<=$li_totrow;$li_i++)
			    {
			 	  $ls_numsol	= trim($io_report->DS->data["numsol"][$li_i]);
				  $ls_codpro	= trim($io_report->DS->data["cod_pro"][$li_i]);
				  $ls_cedbene	= trim($io_report->DS->data["ced_bene"][$li_i]);
				  $ls_codfuefin = $io_report->DS->data["codfuefin"][$li_i];
				  $ls_denfuefin = "";
				  if ($ls_codfuefin!="--")
				     {
					   $ls_denfuefin = $io_report->DS->data["denfuefin"][$li_i]; 
					 }
				  $ls_nomproben = $io_report->DS->data["nombre"][$li_i];
				  $ls_dirproben = $io_report->DS->data["dirproben"][$li_i];
				  $ld_fecemisol = $io_report->DS->data["fecemisol"][$li_i];
				  $ls_consol    = $io_report->DS->data["consol"][$li_i];
				  $ls_obssol    = $io_report->DS->data["obssol"][$li_i];
				  $ls_estsolpag = $io_report->DS->data["estprosol"][$li_i];
				  switch ($ls_estsolpag){
				    case 'E':
				      $ls_estsolpag = "Emitida";
					break;
				    case 'C':
				      $ls_estsolpag = "Contabilizada";
					break;
				    case 'A':
				      $ls_estsolpag = "Anulada";
					break;
				    case 'S':
				      $ls_estsolpag = "Programación de Pago";
					break;
				    case 'P':
				      $ls_estsolpag = "Pagada";
					break;
				  }
				  
				  $ld_monsol    = $io_report->DS->data["monsol"][$li_i];
				  $numalet->setNumero($ld_monsol);
				  $ls_monto     = $numalet->letra();
				  $ld_monsol    = number_format($ld_monsol,2,",",".");
				  $ld_fecemisol = $io_funciones->uf_convertirfecmostrar($ld_fecemisol);
				  if ($ls_codpro!="----------")
				     {
					   $ls_codigo=$ls_codpro;
				     }
				  else
			  	     {
					   $ls_codigo=$ls_cedbene;
			 	     }						
				  if ($ls_tiporeporte==0)
				     {
					   $li_monsolaux = $io_report->DS->data["monsolaux"][$li_i];
					   $li_monsolaux = number_format($li_monsolaux,2,",",".");
				     }
				  uf_print_encabezado_pagina($ls_titulo,$ls_numsol,$ld_fecemisol,&$io_pdf);
				  uf_print_cabecera($ls_nomproben,$ls_dirproben,$ls_estsolpag,$ls_denfuefin,$ls_consol,$ls_obssol,$ld_monsol,&$io_pdf);				
				  //////////////////////////  GRID RECEPCIONES DE DOCUMENTOS		//////////////////////////////////////
				  $io_report->ds_detalle->reset_ds();
				  $lb_valido=$io_report->uf_select_rec_doc_solicitud($ls_numsol); // Cargar el DS con los datos del reporte
				  if ($lb_valido)
				     {
					   $li_totrowdet = $io_report->ds_detalle_rec->getRowCount("numrecdoc");
					   $la_data      = "";
					   $li_totsubtot = 0;
					   $li_tottot    = 0;
					   $li_totcar    = 0;
				 	   $li_totded    = 0;
					   for ($li_s=1;$li_s<=$li_totrowdet;$li_s++)
					       {
						     $ls_numrecdoc    = $io_report->ds_detalle_rec->data["numrecdoc"][$li_s];
							 $ld_fecemidoc    = $io_report->ds_detalle_rec->data["fecemidoc"][$li_s];
							 $ls_numdoccomspg = $io_report->ds_detalle_rec->data["numdoccomspg"][$li_s];
							 $li_mondeddoc    = $io_report->ds_detalle_rec->data["mondeddoc"][$li_s];
							 $li_moncardoc    = $io_report->ds_detalle_rec->data["moncardoc"][$li_s];
							 $li_montotdoc	  = $io_report->ds_detalle_rec->data["montotdoc"][$li_s];
							 $li_subtotdoc	  = ($li_montotdoc-$li_moncardoc+$li_mondeddoc);
							 $li_totsubtot	  = $li_totsubtot + $li_subtotdoc;
							 $li_tottot		  = $li_tottot + $li_montotdoc;
							 $li_totcar		  = $li_totcar + $li_moncardoc;
							 $li_totded		  = $li_totded + $li_mondeddoc;

						     $ld_fecemidoc   = $io_funciones->uf_convertirfecmostrar($ld_fecemidoc);
							 $li_mondeddoc   = number_format($li_mondeddoc,2,",",".");
							 $li_moncardoc   = number_format($li_moncardoc,2,",",".");
							 $li_montotdoc   = number_format($li_montotdoc,2,",",".");
							 $li_subtotdoc   = number_format($li_subtotdoc,2,",",".");
							 $la_data[$li_s] = array('numrecdoc'=>$ls_numrecdoc,
						                        	 'fecemisol'=>$ld_fecemidoc,
													 'fecdoccom'=>$ld_fecemidoc,
													 'mondeddoc'=>$li_mondeddoc,
											    	 'moncardoc'=>$li_moncardoc,
											    	 'montotdoc'=>$li_montotdoc,
											    	 'subtotdoc'=>$li_subtotdoc);
					       }
					   $li_totsubtot = number_format($li_totsubtot,2,",",".");
					   $li_tottot	  = number_format($li_tottot,2,",",".");
					   $li_totcar	  = number_format($li_totcar,2,",",".");
					   $li_totded	  = number_format($li_totded,2,",",".");
					   uf_print_detalle_recepcion($la_data,$li_totsubtot,$li_tottot,$li_totcar,$li_totded,&$io_pdf);
					   unset($la_data);
					   //////////////////////////  GRID RECEPCIONES DE DOCUMENTOS		//////////////////////////////////////
					   //////////////////////////   GRID DETALLE PRESUPUESTARIO		//////////////////////////////////////
					   $lb_valido=$io_report->uf_select_detalle_spg($ls_numsol); // Cargar el DS con los datos del reporte
					   if ($lb_valido)
					      { 
						    $li_totrowspg=$io_report->ds_detalle_spg->getRowCount("codestpro");
						    $la_data="";
						    $li_totpre=0;
						    for ($li_s=1;$li_s<=$li_totrowspg;$li_s++)
						        {
							      $ls_codestpro    = trim($io_report->ds_detalle_spg->data["codestpro"][$li_s]);
							      $ls_spgcuenta    = trim($io_report->ds_detalle_spg->data["spg_cuenta"][$li_s]);
						 	 	  $ls_denominacion = $io_report->ds_detalle_spg->data["denominacion"][$li_s];
								  $li_monto        = $io_report->ds_detalle_spg->data["monto"][$li_s];
								  $li_totpre       = $li_totpre+$li_monto;
								  $li_monto        = number_format($li_monto,2,",",".");
								  $io_fun_cxp->uf_formatoprogramatica($ls_codestpro,&$ls_programatica);
								  $la_data[$li_s]=array('codestpro'=>$ls_programatica,'spg_cuenta'=>$ls_spgcuenta,
									   				    'denominacion'=>$ls_denominacion,'monto'=>$li_monto);
						        }	
						    $li_totpre=number_format($li_totpre,2,",",".");
						    uf_print_detalle_spg($la_data,$li_totpre,&$io_pdf);
						    unset($la_data);
					      }
					   //////////////////////////      GRID DETALLE PRESUPUESTARIO		//////////////////////////////////////
					   /////////////////////////         GRID DETALLE CONTABLE	    //////////////////////////////////////
					   $lb_valido=$io_report->uf_select_detalle_scg($ls_numsol); // Cargar el DS con los datos del reporte
					   if ($lb_valido)
					      {
						    $li_totrowscg =$io_report->ds_detalle_scg->getRowCount("sc_cuenta");
						    $la_data   = "";
						    $li_totdeb = 0;
						    $li_tothab = 0;
						    for ($li_s=1;$li_s<=$li_totrowscg;$li_s++)
						        {
							      $ls_sccuenta	   = trim($io_report->ds_detalle_scg->data["sc_cuenta"][$li_s]);
							      $ls_debhab	   = trim($io_report->ds_detalle_scg->data["debhab"][$li_s]);
							      $ls_denominacion = trim($io_report->ds_detalle_scg->data["denominacion"][$li_s]);
							      $li_monto		   = $io_report->ds_detalle_scg->data["monto"][$li_s];
							      if ($ls_debhab=="D")
							         { 
								       $li_montodebe = $li_monto;
							 	       $li_montohab  = "";
							 	       $li_totdeb    = $li_totdeb+$li_montodebe;
								       $li_montodebe = number_format($li_montodebe,2,",",".");
							         }
							      else
							         {
									   $li_montodebe = "";
									   $li_montohab  = $li_monto;
									   $li_tothab    = $li_tothab+$li_montohab;
									   $li_montohab  = number_format($li_montohab,2,",",".");
							         }
							      $la_data[$li_s]=array('sc_cuenta'=>$ls_sccuenta,
								                        'denominacion'=>$ls_denominacion,
												        'mondeb'=>$li_montodebe,
														'monhab'=>$li_montohab);
						        }	
						    $li_totdeb = number_format($li_totdeb,2,",",".");
						    $li_tothab = number_format($li_tothab,2,",",".");
						    uf_print_detalle_scg($la_data,$li_totdeb,$li_tothab,&$io_pdf);
						    unset($la_data);
					      }
				     }
			       $b=0;
				   $lb_validocar = $io_report->uf_select_sol_cargos($ls_codemp,$ls_numsol);
			       if ($lb_validocar)
			          { 
			            $li_totdet = $io_report->ds_car_dt->getRowCount("numsol");								     
			            for ($b=1;$b<=$li_totdet;$b++)
					        {										  													  
					          $ls_codcar      = $io_report->ds_car_dt->data["codcar"][$b]; 
					          $ls_dencar      = $io_report->ds_car_dt->data["dencar"][$b];
					          $ld_monobjret   = $io_report->ds_car_dt->data["monobjretcar"][$b];
					          $ld_objret      = $io_report->ds_car_dt->data["objretcar"][$b];
					     	  $ld_monobjret   = number_format($ld_monobjret,2,",",".");	
					     	  $ld_objret      = number_format($ld_objret,2,",",".");	
				 	     	  $la_datacar[$b] = array('codigo'=>$ls_codcar,
					                                  'denominacion'=>$ls_dencar,
									                  'monobjret'=>$ld_monobjret,
											          'objret'=>$ld_objret);
					        }							  							   
			          }
			       
				   $li_totdet=0;
			       $lb_validoded = $io_report->uf_select_sol_deducciones($ls_codemp,$ls_numsol);
			       if ($lb_validoded)
			          {
			            $li_totdet = $io_report->ds_ded_dt->getRowCount("numsol");								     
				        for ($c=1;$c<=$li_totdet;$c++)
					        {										
                              $b++;
					          $ls_codded      = $io_report->ds_ded_dt->data["codded"][$c];
					          $ls_dended      = $io_report->ds_ded_dt->data["dended"][$c];
					   		  $ld_monobjded   = $io_report->ds_ded_dt->data["monobjretded"][$c];
					   		  $ld_objretded   = $io_report->ds_ded_dt->data["objretded"][$c];
					  	   	  $ld_monobjded   = number_format($ld_monobjded,2,",",".");	
					          $ld_objretded   = number_format($ld_objretded,2,",",".");	
					          $la_datacar[$b] = array('codigo'=>$ls_codded,
					                                  'denominacion'=>$ls_dended,
									                  'monobjret'=>$ld_monobjded,
											          'objret'=>$ld_objretded);
					        }							  							   
			          }
				   if (!empty($la_datacar))
				      {                  								
 		                uf_print_carded($la_datacar,&$io_pdf); 
 			          }
			 	}
		}
	 if ($ls_tiporeporte==0)
		{
		  uf_print_total_bsf($li_monsolaux,&$io_pdf);
		}
	 uf_print_monto_letras($ls_monto,&$io_pdf);
	 if ($lb_valido) // Si no ocurrio ningún error
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
