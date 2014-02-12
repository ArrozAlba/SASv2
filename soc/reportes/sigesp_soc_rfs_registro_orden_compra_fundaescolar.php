<?php 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Formato de salida  de la Orden de Compra
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
	function uf_print_encabezado_pagina($as_estcondat,$as_numordcom,$ad_fecordcom,$as_coduniadm,$as_denuniadm, $as_codfuefin,
	                                   $as_denfuefin,$as_codigo,$as_nombre,$as_conordcom,$as_rifpro,$as_diaplacom,$as_dirpro,
									   $ls_forpagcom,$as_telpro,&$io_pdf)
	{		
	    $io_encabezado=$io_pdf->openObject();		
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		if (($as_estcondat=="B") || ($as_estcondat=="-") || ($as_estcondat==""))
           {
             $ls_titulo="Orden de Compra";	
           }
        else
        {
             $ls_titulo="Orden de Servicio";	
        }
			$io_pdf->rectangle(150,705,375,40);
			$io_pdf->line(380,705,380,745);
			$io_pdf->line(380,725,525,725);
			$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],45,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
			$io_pdf->addJpegFromFile('../../shared/imagebank/logo_gel.jpg',530,705,60,50); // Agregar Logo
			$li_tm=$io_pdf->getTextWidth(12,$ls_titulo);
			$tm=210;
			
			$io_pdf->addText($tm,720,14,"<b>".$ls_titulo."</b>"); // Agregar el título
			$io_pdf->addText(390,730,10,"   No.:");      // Agregar texto
			$io_pdf->addText(430,730,10,$as_numordcom); // Agregar Numero de la solicitud
			$io_pdf->addText(390,709,10,"  Fecha:"); // Agregar texto
			$io_pdf->addText(430,709,10,$ad_fecordcom); // Agregar la Fecha			
			
			$la_data=array(array('name'=>'<b>Proveedor: </b>'.$as_codigo.' - '.$as_nombre.'<b> - Rif: </b>'.$as_rifpro),
						   array('name'=>'<b>Direccion: </b>'.$as_dirpro.'<b> - Telefono:</b> '.$as_telpro),
						   array('name'=>'<b>Fuente de Financiamiento: </b>'.$as_denfuefin),
						   array('name'=>'<b>Unidad Ejecutora: </b>'.$as_coduniadm.' - '.'<b>'.$as_denuniadm.'</b>'),
						   array('name'=>'<b>Concepto: </b> '.$as_conordcom));				
			$la_columna=array('name'=>'');		
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'fontSize' =>9, // Tamaño de Letras
							 'showLines'=>1, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'xPos'=>320, // Orientación de la tabla
							 'width'=>548, // Ancho de la tabla						 						 					 
							 'maxWidth'=>550); // Ancho Máximo de la tabla
			$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
			unset($la_data);
			unset($la_columna);
			unset($la_config);		
			
			$io_pdf->Rectangle(40,30,550,130);
			$io_pdf->line(40,100,590,100);		
			$io_pdf->line(40,145,590,145);		
			$io_pdf->line(160,100,160,160);		
			$io_pdf->line(290,100,290,160);		
			$io_pdf->line(430,100,430,160);		
			
			$io_pdf->addText(70,150,6,"ELABORADO POR "); // Agregar el título
			$io_pdf->addText(80,43,6,""); // Agregar el título
			$io_pdf->addText(200,150,6,"REVISADO POR"); // Agregar el título
			$io_pdf->line(160,110,290,110);		
			$io_pdf->addText(200,102,6,"PRESUPUESTO"); // Agregar el título
			$io_pdf->addText(330,150,6,"AUTORIZADO POR "); // Agregar el título
			$io_pdf->addText(310,43,6,""); // Agregar el título
			$io_pdf->addText(485,150,6,"APROBADO POR"); // Agregar el título
			$io_pdf->addText(450,43,6,""); // Agregar el título
				  
			$io_pdf->line(40,88,590,88);		
			$io_pdf->addText(250,92,6,"RECEPCION DE LA ORDEN POR EL PROVEEDOR"); // Agregar el título				
			$io_pdf->line(160,30,160,87);		
			$io_pdf->line(290,30,290,87);	
			$io_pdf->addText(65,80,6,"APELLIDOS Y NOMBRES"); // Agregar el título	
			$io_pdf->addText(185,80,6,"CEDULA DE IDENTIDAD NRO"); // Agregar el título
			$io_pdf->addText(390,80,6,"FIRMA RECIBIDO CONFORME"); // Agregar el título
					
			$io_pdf->restoreState();
			$io_pdf->closeObject();
			$io_pdf->addObject($io_encabezado,'all');
			$io_pdf->ezSetCmMargins(6.5,6.5,3,3); // Configuración de los margenes en centímetros
		}
   
    //-----------------------------------------------------------------------------------------------------------------------------	
	function uf_print_cabecera($ai_seguro,$ad_porcentaje,$ad_montoseg,$ai_plazo,$as_condicion,
                               $as_formapago,$ad_monant,$as_lugcom,
                               $as_codmoneda,$as_moneda,$ad_tasa,$ad_mondiv,$as_dirdependencia,
							   $as_dependencia,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		//------------------------------------------------  Datos de la Entrega  ----------------------------------------------------					
		
		$la_data[1]=array('dependencia'=>'<b>Dependencia</b>','lugar'=>'<b>Direccion</b>','plazo'=>'<b>Plazo de Entrega</b>');	
		$la_data[2]=array('dependencia'=>$as_dependencia,'lugar'=>$as_dirdependencia,'plazo'=>$ai_plazo);				
		$la_columna=array('dependencia'=>'','lugar'=>'','plazo'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>548, // Ancho de la tabla						 									 
						 'maxWidth'=>548,
						 'cols'=>array('dependencia'=>array('justification'=>'left','width'=>183), // Justificación y ancho de la columna
									   'lugar'=>array('justification'=>'left','width'=>183), // Justificación y ancho de la columna
									   'plazo'=>array('justification'=>'left','width'=>182))); // Ancho Máximo de la tabla
						 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);				
		
		//------------------------------------------------  Datos del Seguro  ----------------------------------------------------					
		$la_data[1]=array('seguro'=>'<b>Seguro</b>','porcentaje'=>'<b>Porcentaje</b>','monto'=>'<b>Monto del Seguro</b>');	
		$la_data[2]=array('seguro'=>$ai_seguro,'porcentaje'=>$ad_porcentaje,'monto'=>$ad_montoseg);				
		$la_columna=array('seguro'=>'','porcentaje'=>'','monto'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>548, // Ancho de la tabla						 										 
						 'maxWidth'=>548,
                         'cols'=>array('seguro'=>array('justification'=>'left','width'=>183), // Justificación y ancho de la columna
									   'porcentaje'=>array('justification'=>'left','width'=>183), // Justificación y ancho de la columna
									   'monto'=>array('justification'=>'left','width'=>182))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);		
		
		//------------------------------------------------  Datos del Pago  ----------------------------------------------------------		
		$la_data[1]=array('condicion'=>'<b>Condicion de Entrega</b>','forma'=>'<b>Forma de Pago</b>','anticipo'=>'<b>Anticipo de Pago</b>');
		$la_data[2]=array('condicion'=>$as_condicion,'forma'=>$as_formapago,'anticipo'=>$ad_monant);				
		$la_columna=array('condicion'=>'','forma'=>'','anticipo'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>548, // Ancho de la tabla						 									 
						 'maxWidth'=>548,
                         'cols'=>array('condicion'=>array('justification'=>'left','width'=>183), // Justificación y ancho de la columna
									   'forma'=>array('justification'=>'left','width'=>183), // Justificación y ancho de la columna
									   'anticipo'=>array('justification'=>'left','width'=>182)
                                       ) 
                         ); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);					
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	function uf_print_detalle_sep($la_datasep,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_sep
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Selena Lucena
		// Fecha Creación: 17/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		$la_data1=array(array('name'=>''));				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 11, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>548, // Ancho de la tabla						 
						 'maxWidth'=>548); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);	
		unset($la_data1);		
		unset($la_columna);		
		unset($la_config);		

		$la_columna=array('codigo'=>utf8_decode('N° Ejecución Presupuestaria'));
		$la_config =array('showHeadings'=>1, // Mostrar encabezados
						  'fontSize' => 8, // Tamaño de Letras
						  'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						  'showLines'=>1, // Mostrar Líneas
						  'shaded'=>1, // Sombra entre líneas
						  'xPos'=>320, // Orientación de la tabla
						  'width'=>548, // Ancho de la tabla						 										 
						  'maxWidth'=>548,
						  'cols'=>array('codigo'=>array('justification'=>'left','width'=>548))
						); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datasep,$la_columna,'',$la_config);
	}
	//------------------------------------------------------------------------------------------------------------------------------
	
	function uf_print_detallespg($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
	    
		$la_data1=array(array('name'=>''));				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 11, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>1, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>548, // Ancho de la tabla						 
						 'maxWidth'=>548); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);	
		unset($la_data1);		
		unset($la_columna);		
		unset($la_config);		
		
		$la_datasercon= array(array('codigo'=>"<b>Estructura Presupuestaria</b>",'spg_cuenta'=>"<b>Cuenta Presupuestaria</b>",'denominacion'=>"<b>Denominacion</b>",'monto'=>"<b>Monto </b>"));
		$la_columna=array('codigo'=>'<b>Estructura Programatica</b>',
						  'spg_cuenta'=>'<b>Cuenta Presupuestaria</b>',
  						  'denominacion'=>'<b>Denominacion</b>',
						  'monto'=>'<b>Monto</b>',
						  );
						  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>1, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Sombra entre líneas
						 'width'=>548, // Ancho de la tabla
						 'maxWidth'=>548, // Ancho Máximo de la tabla
						 'xPos'=>320, // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>160), // Justificación y ancho de la columna
									   'spg_cuenta'=>array('justification'=>'center','width'=>90),
									   'denominacion'=>array('justification'=>'center','width'=>195),
									   'monto'=>array('justification'=>'right','width'=>100)
									  )
						); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datasercon,$la_columna,'',$la_config);

		$la_columna=array('codigo'=>'<b>Codigo</b>',
						  'spg_cuenta'=>'<b>Cuenta</b>',
						  'denominacion'=>'<b>Denominacion</b>',
						  'monto'=>'<b>Monto</b>',
						  );
						  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>548, // Ancho de la tabla
						 'maxWidth'=>548, // Ancho Máximo de la tabla
						 'xPos'=>320, // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>160), // Justificación y ancho de la columna
									   'spg_cuenta'=>array('justification'=>'center','width'=>90),
									   'denominacion'=>array('justification'=>'left','width'=>195),
									   'monto'=>array('justification'=>'right','width'=>100)
									  )
						); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}	
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	function uf_print_detalle($as_estcondat,$la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data ---> arreglo de información
		//	    		   io_pdf ---> Instancia de objeto pdf
		//    Description: función que imprime el detalle 
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 21/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;

		if ($as_estcondat=='B')
		{
		
		$io_pdf->ezSetDy(-5);
		$la_datatitulo[1]=array('columna1'=>'<b>Detalle de Bienes</b>');
		$la_columnas=array('columna1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xPos'=>320, // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatitulo,$la_columnas,'',$la_config);

		$la_columnas=array('codigo'=>'<b>Codigo</b>',
						   'denominacion'=>'<b>Denominacion</b>',
						   'cantidad'=>'<b>Cant.</b>',
						   'precio'=>'<b>Precio </b>',
						   'unidad'=>'<b>Unid</b>',
						   'subtotal'=>'<b>Sub-Total </b>',
						   'cargo'=>'<b>Cargo </b>',
						   'montot'=>'<b>Total </b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xPos'=>320, // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>160), // Justificación y ancho de la columna
						 			   'cantidad'=>array('justification'=>'left','width'=>34), // Justificación y ancho de la columna
						 			   'precio'=>array('justification'=>'right','width'=>65), // Justificación y ancho de la columna
						 			   'unidad'=>array('justification'=>'center','width'=>33), // Justificación y ancho de la columna
						 			   'subtotal'=>array('justification'=>'right','width'=>65), // Justificación y ancho de la columna
						 			   'cargo'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
						 			   'montot'=>array('justification'=>'right','width'=>65))); // Justificación y ancho de la columna
	     }
		elseif($as_estcondat=='S')
		{
		$io_pdf->ezSetDy(-5);
		$la_datatitulo[1]=array('columna1'=>'<b> Detalle de Servicios</b>');
		$la_columnas=array('columna1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xPos'=>320, // Orientación de la tabla
						// 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatitulo,$la_columnas,'',$la_config);

		$io_pdf->ezSetDy(-2);
		$la_columnas=array('codigo'=>'<b>Codigo</b>',
						   'denominacion'=>'<b>Denominacion</b>',
						   'cantidad'=>'<b>Cant.</b>',
						   'precio'=>'<b>Precio </b>',
						   'unidad'=>'<b>Unid</b>',
						   'subtotal'=>'<b>Sub-Total </b>',
						   'cargo'=>'<b>Cargo </b>',
						   'montot'=>'<b>Total </b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xPos'=>320, // Orientación de la tabla
						 //'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>160), // Justificación y ancho de la columna
						 			   'cantidad'=>array('justification'=>'left','width'=>34), // Justificación y ancho de la columna
						 			   'precio'=>array('justification'=>'right','width'=>65), // Justificación y ancho de la columna
						 			   'unidad'=>array('justification'=>'center','width'=>33), // Justificación y ancho de la columna
						 			   'subtotal'=>array('justification'=>'right','width'=>65), // Justificación y ancho de la columna
						 			   'cargo'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
						 			   'montot'=>array('justification'=>'right','width'=>65))); // Justificación y ancho de la columna
		
		
		}
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_cuentas($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuentas
		//		   Access: private 
		//	    Arguments: la_data ---> arreglo de información
		//	    		   io_pdf ---> Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 21/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		global $ls_estmodest, $ls_bolivares;
		if($ls_estmodest==1)
		{
			$ls_titulo="Estructura Presupuestaria";
		}
		else
		{
			$ls_titulo="Estructura Programatica";
		}
		$la_datatit[1]=array('titulo'=>'<b> Detalle de Presupuesto </b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('codestpro'=>'<b>'.$ls_titulo.'</b>',
						   'cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Denominacion</b>',
						   'monto'=>'<b>Total '.$ls_bolivares.'</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codestpro'=>array('justification'=>'center','width'=>170), // Justificación y ancho de la columna
						 			   'cuenta'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'denominacio'=>array('justification'=>'center','width'=>200), // Justificación y ancho de la columna
									   'monto'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($li_subtot,$li_totcar,$li_montot,$ls_monlet,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		    Acess: private 
		//	    Arguments: li_subtot ---> Subtotal del articulo
		//	    		   li_totcar -->  Total cargos
		//	    		   li_montot  --> Monto total
		//	    		   ls_monlet   //Monto en letras
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los totales
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 21/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$la_data[1]=array('titulo'=>'<b>Sub Total '.$ls_bolivares.'</b>','contenido'=>$li_subtot,);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificación y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('titulo'=>'<b>Cargos '.$ls_bolivares.'</b>','contenido'=>$li_totcar,);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificación y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('titulo'=>'<b>Total '.$ls_bolivares.'</b>','contenido'=>$li_montot,);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificación y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->ezSetDy(-5);
		$la_data[1]=array('titulo'=>'<b> Son: '.$ls_monlet.'</b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>1, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}
	//--------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pie($ldec_monto,$ls_monto,$as_lugcom,$as_codmoneda,$as_moneda,
	                                 $ad_tasa,$ad_mondiv,$as_estcondat,$as_pais,$as_estado,$as_municipio,
									 $as_parroquia,$as_estcondat,$as_bolivares,$as_tiporeporte,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pie
		//		    Acess: private 
		//	    Arguments: ldec_monto : Monto del cheque
		//	    		   ls_nomproben:  Nombre del proveedor o beneficiario
		//	    		   ls_monto : Monto en letras
		//	    		   ls_fecha : Fecha del cheque
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 25/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->setStrokeColor(0,0,0);

        $la_data=array(array('titulo'=>''));				
		$la_columna=array('titulo'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>548, // Ancho de la tabla						 										 
						 'maxWidth'=>548,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>500))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);		
		
		$la_data[1]=array('monlet'=>'<b>MONTO TOTAL EN LETRAS '.$as_bolivares.'</b>','monnum'=>'<b>MONTO TOTAL '.$as_bolivares.'</b>');
		$la_data[2]=array('monlet'=>$ls_monto,'monnum'=>$ldec_monto);				
		$la_columna=array('monlet'=>'','monnum'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla						 									 
						 'maxWidth'=>580,
                         'cols'=>array('monlet'=>array('justification'=>'left','width'=>400), // Justificación y ancho de la columna
									   'monnum'=>array('justification'=>'right','width'=>148))); 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);		
		
		if($as_tiporeporte==0)
	    {
			$la_data=array(array('titulo'=>''));				
			$la_columna=array('titulo'=>'');		
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'fontSize' => 7, // Tamaño de Letras
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'xPos'=>320, // Orientación de la tabla
							 'width'=>548, // Ancho de la tabla						 										 
							 'maxWidth'=>548,
							 'cols'=>array('titulo'=>array('justification'=>'center','width'=>500))); // Ancho Máximo de la tabla
			$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
			unset($la_data);
			unset($la_columna);
			unset($la_config);		
		}
				
        //------------------------------------------------  Datos   ----------------------------------------------------------					
		if( ($as_estcondat=="B")  || ($as_estcondat=="-") || ($as_estcondat=="") )
        {			
				$la_data[1]=array('lugar'=>'<b>Lugar de Compra:   </b>'.$as_lugcom,'moneda'=>'<b>Moneda: </b>'.$as_codmoneda,'tasa'=>'<b>Tasa de Cambio: </b>'.$ad_tasa,'monto'=>'<b>Monto en Divisas: </b>'.$ad_mondiv);				
				$la_columna=array('lugar'=>'','moneda'=>'','tasa'=>'','monto'=>'');		
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'fontSize' => 8,   // Tamaño de Letras
								 'showLines'=>1,    // Mostrar Líneas
								 'shaded'=>0,       // Sombra entre líneas
								 'xPos'=>320, // Orientación de la tabla
								 'width'=>548,      // Ancho de la tabla						 						 
								 'maxWidth'=>548,
								 'cols'=>array('lugar'=>array('justification'=>'left','width'=>125), // Justificación y ancho de la columna
											   'moneda'=>array('justification'=>'left','width'=>130), // Justificación y ancho de la columna
											   'tasa'=>array('justification'=>'left','width'=>145), // Justificación y ancho de la columna
											   'monto'=>array('justification'=>'left','width'=>148)));  // Ancho Máximo de la tabla
				$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
				unset($la_data);
				unset($la_columna);
				unset($la_config);	
				
				$la_data[1]=array('pais'=>'<b>Pais: </b>'.$as_pais,'estado'=>'<b>Estado: </b>'.$as_estado,'municipio'=>'<b>Municipio: </b>'.$as_municipio,'parroquia'=>'<b>Parroquia: </b>'.$as_parroquia);				
				$la_columna=array('pais'=>'','estado'=>'','municipio'=>'','parroquia'=>'');		
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'fontSize'=>8,     // Tamaño de Letras
								 'showLines'=>1,    // Mostrar Líneas
								 'shaded'=>0,       // Sombra entre líneas
								 'xPos'=>320, // Orientación de la tabla
								 'width'=>548,      // Ancho de la tabla						 						 			 
								 'maxWidth'=>548,
								 'cols'=>array('pais'=>array('justification'=>'left','width'=>125), // Justificación y ancho de la columna
											   'estado'=>array('justification'=>'left','width'=>130), // Justificación y ancho de la columna
											   'municipio'=>array('justification'=>'left','width'=>145), // Justificación y ancho de la columna
											   'parroquia'=>array('justification'=>'left','width'=>148)));  // Ancho Máximo de la tabla
				$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
				unset($la_data);
				unset($la_columna);
				unset($la_config);						
		}	
		//------------------------------------------------  Datos   ----------------------------------------------------------
        if( ($as_estcondat=="B")  || ($as_estcondat=="-") )
        {
				$ls_clafiecum="FIANZA DE FIEL CUMPLIMIENTO: AL APROBARSE ESTA ORDEN SE EXIGIRA AL BENEFICIARIO, FIANZA DE FIEL CUMPLIENTO EQUIVALENTE AL ______ %";
				$ls_clafiecum2= "DEL MONTO DE ESTA ORDEN, OTORGADA POR UN BANCO O COMPAÑIA DE SEGUROS, VIGENTE HASTA LA TOTAL ENTREGA DE MERCANCIA";
		
				$ls_clapenal="CLAUSULA PENAL: QUEDA ESTABLECIDA LA CLAUSULA PENAL, SEGUN LA CUAL EL PROVEEDOR PAGARA AL FISCO EL  _______% SOBRE EL MONTO DE LA"; 
				$ls_clapenal2="MERCANCIA RESPECTIVA, POR CADA DIA HABIL DE RETARDO EN LA ENTREGA";
		
				$ls_claesp="CLAUSULA ESPECIAL: EL ORGANISMO SE RESERVA EL DERECHO DE ANULAR UNILATERALMENTE LA PRESENTE ORDEN DE COMPRA SIN INDEMNIZACION,"; 
				$ls_claesp2="DE CONFORMIDAD CON LAS PREVISIONES LEGALES QUE RIGEN LA MATERIA";
				
				$la_data=array(array('titulo'=>''));				
				$la_columna=array('titulo'=>'');		
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'fontSize' => 5, // Tamaño de Letras
								 'showLines'=>0, // Mostrar Líneas
								 'shaded'=>0, // Sombra entre líneas
								 'xPos'=>320, // Orientación de la tabla
								 'width'=>548, // Ancho de la tabla						 										 
								 'maxWidth'=>548,
								 'cols'=>array('titulo'=>array('justification'=>'center','width'=>500))); // Ancho Máximo de la tabla
				$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
				unset($la_data);
				unset($la_columna);
				unset($la_config);		
				
				$la_data[1]=array('clafie'=>$ls_clafiecum." ".$ls_clafiecum2,'clapen'=>$ls_clapenal." ".$ls_clapenal2,'claesp'=>$ls_claesp." ".$ls_claesp2);				
				$la_columna=array('clafie'=>'','clapen'=>'','claesp'=>'');		
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'fontSize' => 5, // Tamaño de Letras
								 'showLines'=>1, // Mostrar Líneas
								 'shaded'=>0, // Sombra entre líneas
								 'xPos'=>320, // Orientación de la tabla
								 'width'=>580, // Ancho de la tabla						 									 
								 'maxWidth'=>580,
								 'cols'=>array('clafie'=>array('justification'=>'left','width'=>185), // Justificación y ancho de la columna
											   'clapen'=>array('justification'=>'left','width'=>185),
											   'claesp'=>array('justification'=>'left','width'=>180))); 
				$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
				unset($la_data);
				unset($la_columna);
				unset($la_config);						
        }				
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_print_pie_cabecera($ad_subtotal,$ad_cargos,$ad_total,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: ad_subtotal // Monto del Subtotal
		//	    		   ad_cargos // Monto de los Cargos
		//	    		   ad_total // Monto total
		//	    		   io_pdf // Instancia de Objeto PDF
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$la_data[1]=array('titulo'=>'<b>Sub-Total</b>','valor'=>$ad_subtotal);
		$la_data[2]=array('titulo'=>'<b>Cargos</b>','valor'=>$ad_cargos);
   		$la_data[3]=array('titulo'=>'<b>Total</b>','valor'=>$ad_total);				
		$la_columna=array('titulo'=>'','valor'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>  8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla						 
						 'maxWidth'=>580, // Ancho Máximo de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificación y ancho de la columna
						 			   'valor'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------

	function uf_print_piecabeceramonto_bsf($li_montotaux,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		    Acess: private 
		//	    Arguments: li_montotaux ---> Total de la Orden Bs.F.
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: Función que imprime el total de la Orden de Compra en Bolivares Fuertes.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 25/09/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('titulo'=>'<b>Monto Bs.F.</b>','contenido'=>$li_montotaux,);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificación y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_sql.php");	
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("sigesp_soc_class_report.php");	
	require_once("../class_folder/class_funciones_soc.php");
	
	$in           = new sigesp_include();
	$con          = $in->uf_conectar();
	$io_sql       = new class_sql($con);	
	$io_funciones = new class_funciones();	
	$io_fun_soc   = new class_funciones_soc();
	$io_report    = new sigesp_soc_class_report();
	$ls_codemp    = $_SESSION["la_empresa"]["codemp"];
	$ls_estmodest = $_SESSION["la_empresa"]["estmodest"];
    $li_candeccon = $_SESSION["la_empresa"]["candeccon"];
	$li_tipconmon = $_SESSION["la_empresa"]["tipconmon"];
	$li_redconmon = $_SESSION["la_empresa"]["redconmon"];
	global $ld_monto;

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
	$ls_tiporeporte=$io_fun_soc->uf_obtenervalor_get("tiporeporte",1);
	$ls_bolivares="Bs.";
	
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_soc_class_reportbsf.php");
		$io_report=new sigesp_soc_class_reportbsf();
		$ls_bolivares="Bs.F.";
		$numalet->setMoneda("Bolivares Fuerte");
	}
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	 global $ls_estcondat;
	 $ls_numordcom = $io_fun_soc->uf_obtenervalor_get("numordcom","");
	 $ls_estcondat = $io_fun_soc->uf_obtenervalor_get("tipord","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$rs_data= $io_report->uf_select_orden_imprimir($ls_numordcom,$ls_estcondat,&$lb_valido); // Cargar los datos del reporte
	if ($lb_valido==false)
	   {
	     print("<script language=JavaScript>");
		 print(" alert('No hay nada que Reportar');"); 
		 print(" close();");
		 print("</script>");
	   }
	else
	   {
		 $ls_descripcion="Generó el Reporte de Orden de Compra";
		 $lb_valido=$io_fun_soc->uf_load_seguridad_reporte("SOC","sigesp_soc_p_registro_orden_compra.php",$ls_descripcion);
		 if ($lb_valido)	
		    {
			  error_reporting(E_ALL);
			  set_time_limit(1800);
			  $io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			  $io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			  $io_pdf->ezSetCmMargins(3.3,2,3,3); // Configuración de los margenes en centímetros
		 	  while ($row=$io_sql->fetch_row($rs_data))
			        {
				      $ls_numordcom = $row["numordcom"];
				      $ls_estcondat = $row["estcondat"];
					  $ls_coduniadm = $row["coduniadm"];
					  $ls_denuniadm = $row["denuniadm"];
					  $ls_codfuefin = $row["codfuefin"];
					  $ls_denfuefin = $row["denfuefin"];
					  $ls_diaplacom = $row["diaplacom"];
					  $ls_forpagcom = $row["forpagcom"];
					  $ls_codpro    = $row["cod_pro"];
					  $ls_nompro    = $row["nompro"];
					  $ls_rifpro    = $row["rifpro"];
					  $ls_dirpro    = $row["dirpro"];
					  $ls_telpro    = $row["telpro"];
					  $ld_fecordcom = $row["fecordcom"];
					  $ls_obscom    = $row["obscom"];
					  $ld_monsubtot = $row["monsubtot"];
					  $ld_monimp    = $row["monimp"];
					  $ld_montot    = $row["montot"];
					  $ld_monbas    = $row["monsubtot"];
					  $ld_moncar    = $row["monimp"];
					  $ld_monbas     = number_format($ld_monbas,2,',','.');
					  $ld_moncar     = number_format($ld_moncar,2,',','.');
					  $li_seguro     = $row["estsegcom"];
					  $ld_porcentaje = $row["porsegcom"];
					  $ld_montoseg   = $row["monsegcom"];
					  $ls_condicion  = $row["concom"];
					  $ld_monant     = $row["monant"];
					  $ls_lugcom     = $row["estlugcom"];
					  $ls_codmoneda  = $row["codmon"];
					  $ld_tasa           = $row["tascamordcom"];
					  $ld_mondiv         = $row["montotdiv"];
					  $ls_dirdependencia = $row["lugentdir"];
				   	  $ls_dependencia    = $row["lugentnomdep"];
			 		  $ls_codpais        = $row["codpai"];	               				
				      $ls_pais           = $io_report->uf_select_denominacion('sigesp_pais','despai',"WHERE codpai='".$ls_codpais."'");
					  if ($ls_pais=="---seleccione---")
				         {
				           $ls_pais = "";
				         }
					  $ls_codestado = $row["codest"];
					  $ls_estado = $io_report->uf_select_denominacion('sigesp_estados','desest',"WHERE codpai='".$ls_codpais."' AND codest='".$ls_codestado."'");   
					  if ($ls_estado=="---seleccione---")
					     {
						   $ls_estado = "";   
					     }
				      $ls_codmunicipio = $row["codmun"];
				      $ls_municipio = $io_report->uf_select_denominacion('sigesp_municipio','denmun',"WHERE codpai='".$ls_codpais."' AND codest='".$ls_codestado."' AND codmun='".$ls_codmunicipio."'");				   
				      if ($ls_municipio)
				         {
				           $ls_municipio = "";
				         }
				      $ls_codparroquia = $row["codpar"];
				      $ls_parroquia = $io_report->uf_select_denominacion('sigesp_parroquia','denpar',"WHERE codpai='".$ls_codpais."' AND codest='".$ls_codestado."' AND codmun='".$ls_codmunicipio."' AND codpar='".$ls_codparroquia."'");				   
				      if ($ls_parroquia)
				         {
				           $ls_parroquia ="";
				         }
			    	  $ld_monto = number_format($ld_monto,2,',','.');
					  if (!empty($ls_coduniadm))
				         { 
  				           $ls_denuniadm = $io_report->uf_select_denominacion('spg_unidadadministrativa','denuniadm',"WHERE coduniadm='".$ls_coduniadm."'");				   
						 }
					  else
				         {
					       $ls_denuniadm="";
				         }
				      if ($ls_codfuefin!="--")
				         {
				           $ls_denfuefin = $io_report->uf_select_denominacion('sigesp_fuentefinanciamiento','denfuefin',"WHERE codfuefin='".$ls_codfuefin."'");				   
				         }  
				      else
				         {
				           $ls_denfuefin="";
				         }
					  if ($ls_codmoneda!="---")
				         {
				           $ls_moneda = $io_report->uf_select_denominacion('sigesp_moneda','denmon',"WHERE codmon='".$ls_codmoneda."'");				   
				         }  
				      else
				         {
				           $ls_moneda = "";
				           $ls_codmoneda="";
				         }
				      if ($ls_lugcom==0)
				         {
					       $ls_lugcom="Nacional";				
				         }
					  else
						 {
						   $ls_lugcom="Extranjero";				
						 }
				      if ($ls_forpagcom=="s1")
				         {
					       $ls_forpagcom="";
				         }				 			 
				      if ($li_seguro==0)
				         {
				           $li_seguro="No";
				         }
				      else
				         {
				  		   $li_seguro="Si";
				         } 
					  if ($ls_tiporeporte==0)
				         {
						   $ld_montotaux=$row["montotaux"];
						   $ld_montotaux=number_format($ld_montotaux,2,",",".");
				         }
				      $numalet->setNumero($ld_montot);
				      $ls_monto     = $numalet->letra();
				      $ld_montot    = number_format($ld_montot,2,",",".");
				      $ld_monsubtot = number_format($ld_monsubtot,2,",",".");
				      $ld_monimp    = number_format($ld_monimp,2,",",".");
					  $ld_tasa      = number_format($ld_tasa,2,',','.');	     
					  $ld_mondiv    = number_format($ld_mondiv,2,',','.');	     
					  $ld_porcentaje= number_format($ld_porcentaje,0,'','');	     
					  $ld_monant    = number_format($ld_monant,2,',','.');	     
					  $ld_montoseg  = number_format($ld_montoseg,2,',','.');
					  $ld_fecordcom = $io_funciones->uf_convertirfecmostrar($ld_fecordcom);
	 	
		 	 	      uf_print_encabezado_pagina($ls_estcondat,$ls_numordcom,$ld_fecordcom,$ls_coduniadm,$ls_denuniadm,
				                                 $ls_codfuefin,$ls_denfuefin,$ls_codpro,$ls_nompro,$ls_obscom,$ls_rifpro,
										         $ls_diaplacom,$ls_dirpro,$ls_forpagcom,$ls_telpro,&$io_pdf);
				
				      uf_print_cabecera($li_seguro,$ld_porcentaje,$ld_montoseg,$ls_diaplacom,$ls_condicion,
                                        $ls_forpagcom,$ld_monant,$ls_lugcom,
                                        $ls_codmoneda,$ls_moneda,$ld_tasa,$ld_mondiv,$ls_dirdependencia,
								        $ls_dependencia,$io_pdf);
			          $lb_validosep = true;
				      $li_totrow	 = 0;
				      $lb_validosep = $io_report->uf_select_soc_sep($ls_codemp,$ls_numordcom,$ls_estcondat);	
				      if ($lb_validosep)
					     {										
					       $li_totrow = $io_report->ds_soc_sep->getRowCount("numordcom");							
						   for ($li_row=1;$li_row<=$li_totrow;$li_row++)
							   { 
							     $ls_numsep   		  = $io_report->ds_soc_sep->data["numsol"][$li_row];  											  
							     $ls_denunadm 		  = $io_report->ds_soc_sep->data["denuniadm"][$li_row];  											  
							     $la_datasep[$li_row] = array('codigo'=>$ls_numsep,'denuniadm'=>$ls_denunadm);
							   }														
						   uf_print_detalle_sep($la_datasep,$io_pdf); 
					     } 
	                  $rs_datos = $io_report->uf_select_detalle_orden_imprimir($ls_numordcom,$ls_estcondat,&$lb_valido);
	                  if ($lb_valido)
		                 {
			               $li_totrows = $io_sql->num_rows($rs_datos);
			               if ($li_totrows>0)
			                  {
			 	                $li_i = 0;
				                while ($row=$io_sql->fetch_row($rs_datos))
				                      {
						                $li_i++;
						                $ls_codartser = $row["codartser"];
										$ls_denartser=$row["denartser"];
										if ($ls_estcondat=="B")
										   {
										     $ls_unidad    = $row["unidad"];
											 $ls_codartser = substr($ls_codartser,10,20);   
										   }
										else
										   {
										     $ls_unidad="";
										   }
										if ($ls_unidad=="D")
						                   {
						                     $ls_unidad = "Detal";
						                   }
					 	                elseif($ls_unidad=="M")
										   {
										     $ls_unidad="Mayor";
										   }
						                $li_cantartser   = $row["cantartser"];
										$ld_preartser    = $row["preartser"];
										$ld_subtotartser = $ld_preartser*$li_cantartser;
										$ld_totartser    = $row["monttotartser"];
										$ld_carartser    = $ld_totartser-$ld_subtotartser;
										$ld_preartser    = number_format($ld_preartser,2,",",".");
										$ld_subtotartser = number_format($ld_subtotartser,2,",",".");
										$ld_totartser	 = number_format($ld_totartser,2,",",".");
										$ld_carartser	 = number_format($ld_carartser,2,",",".");
										$la_data[$li_i]  = array('codigo'=>$ls_codartser,
																 'denominacion'=>$ls_denartser,
																 'cantidad'=>number_format($li_cantartser,2,',','.'),
																 'precio'=>$ld_preartser,
																 'unidad'=>$ls_unidad,
																 'subtotal'=>$ld_subtotartser,
																 'cargo'=>$ld_carartser,
																 'montot'=>$ld_totartser);
					                  }
								uf_print_detalle($ls_estcondat,$la_data,&$io_pdf);
								uf_print_pie_cabecera($ld_monsubtot,$ld_monimp,$ld_montot,&$io_pdf);
								unset($la_data);
			                    $rs_datos_cuenta = $io_report->uf_select_cuenta_gasto($ls_numordcom,$ls_estcondat,&$lb_valido); 
					            if ($lb_valido)
					               {
						             $li_totrows = $io_sql->num_rows($rs_datos);
						             if ($li_totrows>0)
						                {
							              $li_s = 0;
							              while($row=$io_sql->fetch_row($rs_datos_cuenta))
							                   {
											     $li_s++;
											     $ls_codestpro1 = trim($row["codestpro1"]);
											     $ls_codestpro2 = trim($row["codestpro2"]);
											     $ls_codestpro3 = trim($row["codestpro3"]);
											     $ls_codestpro4 = trim($row["codestpro4"]);
											     $ls_codestpro5 = trim($row["codestpro5"]);
											     $ls_spg_cuenta = trim($row["spg_cuenta"]);
											     $ld_monto      = $row["monto"];
											     $ld_monto      = number_format($ld_monto,2,",",".");
											     $ls_dencuenta  = "";
											     $lb_valido     = $io_report->uf_select_denominacionspg($ls_spg_cuenta,$ls_dencuenta);																																						
											     if ($ls_estmodest==1)
											        {
												      $ls_codestpro=$ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
											        }
												 else
												    {
													  $ls_codestpro=substr($ls_codestpro1,-2)."-".substr($ls_codestpro2,-2)."-".substr($ls_codestpro3,-2)."-".substr($ls_codestpro4,-2)."-".substr($ls_codestpro5,-2);
												    }
								                 $la_data[$li_s] = array('codigo'=>$ls_codestpro,
												                         'spg_cuenta'=>$ls_spg_cuenta,
																		 'denominacion'=>$ls_dencuenta,
													                     'monto'=>$ld_monto);
							                   }	
							              uf_print_detallespg($la_data,$io_pdf);
							              unset($la_data);
						                }
				                   }
 			                  }
		                 }
		            }
		    } 
	    uf_print_encabezado_pie($ld_montot,$ls_monto,$ls_lugcom,$ls_moneda,$ls_moneda,$ld_tasa,$ld_mondiv,$ls_estcondat,
		 	 			        $ls_pais,$ls_estado,$ls_municipio,$ls_parroquia,$ls_estcondat,$ls_bolivares,
                                $ls_tiporeporte,&$io_pdf);
   		if ($ls_tiporeporte==0)
		   {
			 uf_print_piecabeceramonto_bsf($ld_montotaux,&$io_pdf);
		   }
	   }
   
    if ($lb_valido)
	   {
		 $io_pdf->ezStopPageNumbers(1,1);
		 $io_pdf->ezStream();
	   }
	else
	   {
		 print("<script language=JavaScript>");
		 print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
		 print(" close();");
		 print("</script>");		
	   }
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_soc);
?>