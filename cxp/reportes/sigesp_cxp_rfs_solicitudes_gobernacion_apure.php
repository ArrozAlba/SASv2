<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Formato de salida  de Solicitud de Pago
//  ORGANISMO: GOBERNACION DE APURE
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
		$io_pdf->Rectangle(138,670,440,40);
        $io_pdf->line(380,670,380,710);
		$io_pdf->line(380,690,578,690);
		
        $io_pdf->addJpegFromFile('../../shared/imagebank/logo.jpg',30,665,80,80); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=240-($li_tm/2);
		$io_pdf->addText($tm,685,13,$as_titulo); // Agregar el título
		$io_pdf->addText(410,695,10,"<b> No.: </b> ".$as_numsol); // Agregar el título
		$io_pdf->addText(400,675,10,"<b>Fecha: </b> ".$ad_fecregsol); // Agregar el título
		$io_pdf->addText(550,760,7,date("d/m/Y")); // Agregar la Fecha
		//Cuadro inferior
			$io_pdf->Rectangle(15,30,575,140);
			$io_pdf->line(15,95,590,95);// Horizontal		
			$io_pdf->line(120,95,120,170);//  Vertical
			$io_pdf->line(140,95,140,135);//  Vertical
			$io_pdf->line(160,95,160,135);//  Vertical
			$io_pdf->line(180,95,180,170);//  Vertical
			$io_pdf->line(320,95,320,170);//  Vertical
			$io_pdf->line(340,95,340,135);//  Vertical
			$io_pdf->line(360,95,360,135);//  Vertical
			$io_pdf->line(380,95,380,170);//  Vertical
			$io_pdf->line(530,95,530,170);//  Vertical
			$io_pdf->line(550,95,550,135);//  Vertical
			$io_pdf->line(570,95,570,135);//  Vertical		
			
			$io_pdf->addText(140,164,6,"FECHA"); // Agregar el título
			$io_pdf->addText(132,135,6,"D       M       A"); // Agregar el título
			$io_pdf->addText(340,164,6,"FECHA"); // Agregar el título
			$io_pdf->addText(332,135,6,"D       M       A"); // Agregar el título
			$io_pdf->addText(550,164,6,"FECHA"); // Agregar el título
			$io_pdf->addText(542,135,6,"D       M       A"); // Agregar el título
			$io_pdf->addText(550,89,6,"FECHA"); // Agregar el título
			$io_pdf->addText(542,69,6,"D       M       A"); // Agregar el título
			$io_pdf->addText(258,89,6,"FECHA"); // Agregar el título
			$io_pdf->addText(250,69,6,"D       M       A"); // Agregar el título
			
			$io_pdf->addText(42,100,6,"ELABORADO POR: "); // Agregar el título
			$io_pdf->addText(80,43,6,""); // Agregar el título
			$io_pdf->addText(210,100,6,"JEFE DE CONTABILIDAD:"); // Agregar el título
			$io_pdf->addText(310,43,6,""); // Agregar el título
			$io_pdf->addText(405,100,6,"JEFE DE ORDENACION DE PAGO:"); // Agregar el título
			$io_pdf->addText(450,43,6,""); // Agregar el título
			$io_pdf->addText(65,32,6,"DIRECTOR DE ADMINISTRACION"); // Agregar el título
			$io_pdf->addText(390,32,6,"GOBERNADOR"); // Agregar el título
			
			$io_pdf->line(238,30,238,95);//  Vertical
			$io_pdf->line(258,30,258,70);//  Vertical
			$io_pdf->line(278,30,278,70);//  Vertical
			$io_pdf->line(298,30,298,95);//  Vertical 
			
			$io_pdf->line(530,30,530,105);//  Vertical
			$io_pdf->line(550,30,550,70);//  Vertical
			$io_pdf->line(570,30,570,70);//  Vertical		

   		/////////////////
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_tipproben,$as_codproben,$as_nompro,$as_dirpro,$as_rifproben,$as_estsolpag,$as_denfuefin,$as_consol,$as_obssol,$ad_monsol,&$io_pdf)
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
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 17/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		if ($as_tipproben=='P')
		   { 
			 $ls_tipproben = "Proveedor";
		   }
		elseif($as_tipproben=='B')
		   {
			 $ls_tipproben = "Beneficiario";
	 	   }
		$ls_rifproben="";
		if ($as_rifproben!='-' && !empty($as_rifproben))
		   {
		     $ls_rifproben = " <b>RIF: </b>".$as_rifproben;
		   }
		$la_data=array(array('name'=>'<b>'.$ls_tipproben.': </b>'.$as_codproben.' - '.$as_nompro.'            '.$ls_rifproben),
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
		
		$la_data2=array(array('name'=>'<b>RECEPCIONES DE DOCUMENTOS</b>'));				
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
		$la_datatit[1]=array('titulo'=>'<b>CUENTAS PRESUPUESTARIAS</b>');
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
		$la_datatit[1]=array('titulo'=>'<b>CUENTAS CONTABLES</b>');
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
		$la_datatot[1]=array('titulo'=>'<b>Totales '.$ls_titulo.'</b>','totdeb'=>$ai_totdeb,
							 'tothab'=>$ai_tothab);
		$la_columnas=array('titulo'=>'<b>Factura</b>',
						   'totdeb'=>'<b>Deducciones</b>',
						   'tothab'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>390), // Justificación y ancho de la columna
						 			   'totdeb'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'tothab'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatot,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

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
	$ls_titulo="<b>ORDEN DE PAGO</b>";
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
		    $io_pdf->ezSetCmMargins(6.5,6.5,3,3); // Configuracion de los margenes en centimetros.		
			$li_totrow=$io_report->DS->getRowCount("numsol");
			for ($li_i=1;$li_i<=$li_totrow;$li_i++)
			    {
			 	  $ls_numsol	= trim($io_report->DS->data["numsol"][$li_i]);
				  $ls_codpro	= trim($io_report->DS->data["cod_pro"][$li_i]);
				  $ls_cedbene	= trim($io_report->DS->data["ced_bene"][$li_i]);
				  $ls_tipproben = $io_report->DS->data["tipproben"][$li_i];
				  $ls_rifproben = $io_report->DS->data["rifpro"][$li_i];
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
				  if ($ls_tipproben=="P")
				     {
					   $ls_codproben=$ls_codpro;
				     }
				  elseif($ls_tipproben=="B")
			  	     {
					   $ls_codproben=$ls_cedbene;
			 	     }						
				  if ($ls_tiporeporte==0)
				     {
					   $li_monsolaux = $io_report->DS->data["monsolaux"][$li_i];
					   $li_monsolaux = number_format($li_monsolaux,2,",",".");
				     }
				  uf_print_encabezado_pagina($ls_titulo,$ls_numsol,$ld_fecemisol,&$io_pdf);
				  uf_print_cabecera($ls_tipproben,$ls_codproben,$ls_nomproben,$ls_dirproben,$ls_rifproben,$ls_estsolpag,$ls_denfuefin,$ls_consol,$ls_obssol,$ld_monsol,&$io_pdf);				
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
								  if ($ls_estmodest==1)
							 		 {
									   $ls_codestpro1  = substr($ls_codestpro,0,20);	
									   $ls_codestpro2  = substr($ls_codestpro,20,6);
									   $ls_codestpro3  = substr($ls_codestpro,26,3);
									   $la_data[$li_s] = array('codestpro'=>$ls_codestpro1."-".$ls_codestpro2."-".$ls_codestpro3,
									                           'spg_cuenta'=>$ls_spgcuenta,
													           'denominacion'=>$ls_denominacion,
															   'monto'=>$li_monto);
							         }
							      else
							         {
								       $ls_codestpro1  = substr($ls_codestpro,18,2);	
								       $ls_codestpro2  = substr($ls_codestpro,24,2);
								       $ls_codestpro3  = substr($ls_codestpro,27,2);
								       $ls_codestpro4  = substr($ls_codestpro,29,2);
								       $ls_codestpro5  = substr($ls_codestpro,31,2);
								       $la_data[$li_s] = array('codestpro'=>$ls_codestpro1."-".$ls_codestpro2."-".$ls_codestpro3."-".$ls_codestpro4."-".$ls_codestpro5,
									                           'spg_cuenta'=>$ls_spgcuenta,
													           'denominacion'=>$ls_denominacion,
															   'monto'=>$li_monto);
							         }
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
			 	}
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