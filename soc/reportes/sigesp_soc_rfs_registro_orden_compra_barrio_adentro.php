<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Formato de salida  de la Orden de Compra
//  ORGANISMO: BARRIO ADENTRO
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
	function uf_print_encabezado_pagina($as_estcondat,$as_numordcom,$ad_fecordcom,$as_codpro,$as_nompro,$as_rifpro,$as_dirpro,
	                                    $as_telpro,$as_denfuefin,$as_coduniadm,$as_denuniadm,$as_conordcom,$as_obsordcom,
										$as_nomdep,$as_dirdep,$as_diaplacom,$ls_forpagcom,$as_conent,$as_seguro,$ad_porseg,$ad_monseg,
										$ad_monantpag,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: 
		//   $as_estcondat : Tipo de la Orden de Compra (B) = Bienes, (S) = Servicios.
		//   $as_numordcom : Número que identifica a la Orden de Compra.
		//	 $ad_fecordcom : Fecha de Creación de la Orden de Compra.
		//	    $as_codpro : Código del Proveedor asociado a la Orden de Compra.
		//	 	$as_nompro : Nombre del Proveedor.
		//	 	$as_rifpro : Número del Registro de Información Fiscal.
		//	 	$as_dirpro : Dirección del Proveedor.
		//	 	$as_telpro : Teléfono de Ubicación del Proveedor.
		//	 $as_denfuefin : Nombre de la Fuente de Financiamiento.
		//	 $as_coduniadm : Códificación de la Unidad Administrativa.
		//	 $as_denuniadm : Nombre de la Unidad Administrativa.
		//	 $as_conordcom : Concepto de la Orden de Compras.
		//	 $as_obsordcom : Observación de la Orden de Compras.
		//	 	$as_nomdep : Nombre de la Dependencia.
		//	 	$as_dirdep : Dirección de la Dependencia.
		//	 $as_diaplacom : Días de Plazo para la entrega de los Bienes o Prestación del Servicio.
		//	 $as_forpagcom : Forma de Cancelación de la Orden de Compras. 
		//	 	$as_conent : Condiciones de la Entrega.
		//	 	   $io_pdf : Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 10/10/2007.
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->rectangle(95,660,440,40);
		$io_pdf->line(380,660,380,700);
		$io_pdf->line(380,680,535,680);
        $io_pdf->addJpegFromFile('../../shared/imagebank/logo_fmba.jpg',45,705,300,50); // Agregar Logo
		if(($as_estcondat=="B") || ($as_estcondat=="-") || ($as_estcondat=="")) 
        {
             $ls_titulo="Orden de Compra";	
        }
        else
        {
             $ls_titulo="Orden de Servicio";
        }
		$li_tm=$io_pdf->getTextWidth(14,$ls_titulo);
		$tm=240-($li_tm/2);
		$io_pdf->addText($tm,675,14,"<b>".$ls_titulo."</b>"); // Agregar el título
		$io_pdf->addText(410,685,10," <b>   No.: </b> ".$as_numordcom); // Agregar el título
		$io_pdf->addText(400,665,10,"<b>   Fecha: </b>".$ad_fecordcom); // Agregar el título
		$io_pdf->addText(554,750,7,date("d/m/Y")); // Agregar la Fecha	
        $io_pdf->ezSetY(660);
		$la_data = array(array('fila'=>'<b>Proveedor  :</b> '.$as_codpro.' - '.$as_nompro.' <b> - RIF: </b>'.$as_rifpro),
		            	 array('fila'=>'<b>Dirección     : </b> '.$as_dirpro.'<b> - Teléfono:</b> '.$as_telpro),
						 array('fila'=>'<b>Fuente de Financiamiento:</b> '.$as_denfuefin),
						 array('fila'=>'<b>Unidad Ejecutora:</b> '.$as_coduniadm."  -  <b>".$as_denuniadm.'</b>'),
						 array('fila'=>'<b>Concepto:</b> '.$as_conordcom),
					     array('fila'=>'<b>Observación:</b> '.$as_obsordcom));

		$la_columna=array('fila'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		
		$la_data[1]  = array('columna1'=>'<b>Dependencia</b>','columna2'=>'<b>Dirección</b>','columna3'=>'<b>Plazo de Entrega</b>');
		$la_data[2]  = array('columna1'=>strtoupper($as_nomdep),'columna2'=>$as_dirdep,'columna3'=>''.$as_diaplacom);
		$la_columnas = array('columna1'=>'','columna2'=>'','columna3'=>'');
		$la_config   = array('showHeadings'=>0, // Mostrar encabezados
						     'fontSize' => 8, // Tamaño de Letras
						     'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						     'showLines'=>1, // Mostrar Líneas
						     'shaded'=>0, // Sombra entre líneas
						     'width'=>570, // Ancho de la tabla
						 	 'maxWidth'=>570, // Ancho Máximo de la tabla
						 	 'xOrientation'=>'center', // Orientación de la tabla
						 	 'cols'=>array('columna1'=>array('justification'=>'left','width'=>190), // Justificación y ancho de la columna
						 			       'columna2'=>array('justification'=>'left','width'=>190),
									       'columna3'=>array('justification'=>'left','width'=>190))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

        $la_data[1]  = array('columna1'=>'<b>Seguro</b>','columna2'=>'<b>Porcentaje</b>','columna3'=>'<b>Monto del Seguro</b>');
		$la_data[2]  = array('columna1'=>$as_seguro,'columna2'=>$ad_porseg,'columna3'=>$ad_monseg);
		$la_columnas = array('columna1'=>'','columna2'=>'','columna3'=>'');
		$la_config   = array('showHeadings'=>0, // Mostrar encabezados
						     'fontSize'=>8, // Tamaño de Letras
						     'titleFontSize'=>8,  // Tamaño de Letras de los títulos
						     'showLines'=>1, // Mostrar Líneas
						     'shaded'=>0, // Sombra entre líneas
						     'width'=>570, // Ancho de la tabla
						     'maxWidth'=>570, // Ancho Máximo de la tabla
						     'xOrientation'=>'center', // Orientación de la tabla
						     'cols'=>array('columna1'=>array('justification'=>'left','width'=>190), // Justificación y ancho de la columna
						 			       'columna2'=>array('justification'=>'left','width'=>190),
										   'columna3'=>array('justification'=>'left','width'=>190))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$la_data[1]  = array('columna1'=>'<b>Condición de Entrega</b>','columna2'=>'<b>Forma de Pago</b>','columna3'=>'<b>Anticipo de Pago</b>');
		$la_data[2]  = array('columna1'=>$as_conent,'columna2'=>$ls_forpagcom,'columna3'=>$ad_monantpag);
		$la_columnas = array('columna1'=>'','columna2'=>'','columna3'=>'');
		$la_config   = array('showHeadings'=>0, // Mostrar encabezados
						     'fontSize'=>8, // Tamaño de Letras
						     'titleFontSize'=>8,  // Tamaño de Letras de los títulos
						     'showLines'=>1, // Mostrar Líneas
						     'shaded'=>0, // Sombra entre líneas
						     'width'=>570, // Ancho de la tabla
						     'maxWidth'=>570, // Ancho Máximo de la tabla
						     'xOrientation'=>'center', // Orientación de la tabla
						     'cols'=>array('columna1'=>array('justification'=>'left','width'=>190), // Justificación y ancho de la columna
						 			       'columna2'=>array('justification'=>'left','width'=>190),
										   'columna2'=>array('justification'=>'left','width'=>190)
										   )); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->Rectangle(18,63,565,127);
		$io_pdf->line(18,170,582,170);		
		$io_pdf->line(18,130,582,130);
		$io_pdf->line(18,115,582,115);		
		
		$io_pdf->line(130,130,130,190);		
		$io_pdf->line(260,130,260,190);		
		$io_pdf->line(390,130,390,190);		
		$io_pdf->line(500,130,500,190);		
		
		$io_pdf->addText(45,178,6,"Gerencia de Compras");
		$io_pdf->addText(165,183,6,"Gerencia Control y");
		$io_pdf->addText(160,175,6,"Gestión de Presupuesto");
		$io_pdf->addText(290,183,6,"Gerencia Administración");
		$io_pdf->addText(307,175,6,"y Servicios");
		$io_pdf->addText(415,183,6,"Gerencia General de");
		$io_pdf->addText(408,175,6,"Gestión Administrativa");
		$io_pdf->addText(525,178,6,"Presidencia");

		$io_pdf->addText(250,120,6,"RECEPCION DE LA ORDEN POR EL PROVEEDOR"); // Agregar el título				
		$io_pdf->line(160,63,160,115);		
		$io_pdf->line(290,63,290,115);	
		$io_pdf->line(450,63,450,115);	
		$io_pdf->addText(50,105,6,"APELLIDOS Y NOMBRES"); // Agregar el título	
		$io_pdf->addText(185,105,6,"CEDULA DE IDENTIDAD N°"); // Agregar el título
		$io_pdf->addText(325,105,6,"FIRMA RECIBIDO CONFORME"); // Agregar el título
		$io_pdf->addText(480,105,6,"SELLO DE LA EMPRESA"); // Agregar el título
		
		$ls_nomemp = rtrim($_SESSION["la_empresa"]["nombre"]);				
		$ls_rifemp = rtrim($_SESSION["la_empresa"]["rifemp"]);
		
		$li_tm=$io_pdf->getTextWidth(6,'<b>'.$ls_nomemp."</b> R.I.F.:".$ls_rifemp);
		$tm=296-($li_tm/2);
		$io_pdf->addText($tm,54,6,'<b>'.$ls_nomemp."</b> R.I.F.:".$ls_rifemp); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(6,"Av. Sur 4, Centro Simón Bolívar, Torre Sur, piso 3, Oficina 325. El Silencio. Caracas.");
		$tm=296-($li_tm/2);
		$io_pdf->addText($tm,47,6,"Av. Sur 4, Centro Simón Bolívar, Torre Sur, piso 3, Oficina 325. El Silencio. Caracas."); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(6,"Teléfonos: 0212/408 06 63, 408 06 60 - Fax: 0212/ 408 06 64");
		$tm=296-($li_tm/2);
		$io_pdf->addText($tm,40,6,"Teléfonos: 0212/408 06 63, 408 06 60 - Fax: 0212/ 408 06 64"); // Agregar el título
				
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($as_estcondat,$la_data,$ld_subtot,$ld_totcar,$ld_montot,&$io_pdf)
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
		$io_pdf->ezSetDy(-5);
		if ($as_estcondat=='B')
		{
			$ls_titulo_grid = "Bienes";
		    
			$la_columnas=array('codigo'=>'<b>Código</b>',
				   'denominacion'=>'<b>Denominación</b>',
				   'cantidad'=>'<b>Cant</b>',
				   'precio'=>'<b>Precio ('.$ls_bolivares.')</b>',
				   'unidad'=>'<b>Unidad</b>',
				   'subtotal'=>'<b>SubTotal ('.$ls_bolivares.')</b>',
				   'cargo'=>'<b>Cargos ('.$ls_bolivares.')</b>',
				   'montot'=>'<b>Total ('.$ls_bolivares.')</b>');

			$la_config      = array('showHeadings'=>1, // Mostrar encabezados
						 		    'fontSize' => 7.5, // Tamaño de Letras
						 			'titleFontSize'=>8,  // Tamaño de Letras de los títulos
						 			'showLines'=>1, // Mostrar Líneas
						 			'shaded'=>0, // Sombra entre líneas
						 			'width'=>570, // Ancho de la tabla
						 			'maxWidth'=>570, // Ancho Máximo de la tabla
						 			'colGap'=>1,
									'xPos'=>302, // Orientación de la tabla
						 			'cols'=>array('codigo'=>array('justification'=>'center','width'=>90),      // Justificación y ancho de la columna
						 			   			  'denominacion'=>array('justification'=>'left','width'=>145), // Justificación y ancho de la columna
						 			   			  'cantidad'=>array('justification'=>'right','width'=>43),     // Justificación y ancho de la columna
						 			   			  'precio'=>array('justification'=>'right','width'=>65),       // Justificación y ancho de la columna
						 			   			  'unidad'=>array('justification'=>'right','width'=>33),       // Justificación y ancho de la columna
												  'subtotal'=>array('justification'=>'right','width'=>65),     // Justificación y ancho de la columna
									  			  'cargo'=>array('justification'=>'right','width'=>65),        // Justificación y ancho de la columna
						 			   			  'montot'=>array('justification'=>'right','width'=>65)));     // Justificación y ancho de la columna
		}
		elseif($as_estcondat=='S')
		{
			$ls_titulo_grid="Servicios";
			
			$la_columnas=array('codigo'=>'<b>Código</b>',
				               'denominacion'=>'<b>Denominación</b>',
				               'cantidad'=>'<b>Cant</b>',
				               'precio'=>'<b>Precio ('.$ls_bolivares.')</b>',
				   			   'subtotal'=>'<b>SubTotal ('.$ls_bolivares.')</b>',
				   			   'cargo'=>'<b>Cargos ('.$ls_bolivares.')</b>',
				   			   'montot'=>'<b>Total ('.$ls_bolivares.')</b>');

		    $la_config      = array('showHeadings'=>1, // Mostrar encabezados
						 		    'fontSize' => 8, // Tamaño de Letras
						 			'titleFontSize'=>9,  // Tamaño de Letras de los títulos
						 			'showLines'=>1, // Mostrar Líneas
						 			'shaded'=>0, // Sombra entre líneas
						 			'width'=>570, // Ancho de la tabla
						 			'maxWidth'=>570, // Ancho Máximo de la tabla
						 			'xPos'=>302.5, // Orientación de la tabla
						 			'colGap'=>1, // Separacion en las lineas de la  tabla
						 			'cols'=>array('codigo'=>array('justification'=>'center','width'=>60),      // Justificación y ancho de la columna
						 			   			  'denominacion'=>array('justification'=>'left','width'=>175), // Justificación y ancho de la columna
						 			   			  'cantidad'=>array('justification'=>'right','width'=>40),     // Justificación y ancho de la columna
						 			   			  'precio'=>array('justification'=>'right','width'=>75),       // Justificación y ancho de la columna
												  'subtotal'=>array('justification'=>'right','width'=>75),     // Justificación y ancho de la columna
									  			  'cargo'=>array('justification'=>'right','width'=>70),        // Justificación y ancho de la columna
						 			   			  'montot'=>array('justification'=>'right','width'=>75)));     // Justificación y ancho de la columna
		} 
		$io_pdf->ezTable($la_data,$la_columnas,'Detalles de los '.$ls_titulo_grid,$la_config);
	    unset($la_data);
		unset($la_columnas);
		unset($la_config);
		 
		$la_data[1]  = array('titulo'=>'<b>Sub-Total '.$ls_bolivares.'</b>','contenido'=>$ld_subtot);
		$la_columnas = array('titulo'=>'','contenido'=>'');
		$la_config	 = array('showHeadings'=>0, // Mostrar encabezados
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
		
		$la_data[1]=array('titulo'=>'<b>Cargos '.$ls_bolivares.'</b>','contenido'=>$ld_totcar);
		$la_columnas=array('titulo'=>'','contenido'=>'');
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
		
		$la_data[1]  = array('titulo'=>'<b>Total '.$ls_bolivares.'</b>','contenido'=>$ld_montot);
		$la_columnas = array('titulo'=>'','contenido'=>'');
		$la_config   = array('showHeadings'=>0, // Mostrar encabezados
						     'fontSize' => 9, // Tamaño de Letras
						     'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						     'showLines'=>0, // Mostrar Líneas
						     'shaded'=>0, // Sombra entre líneas
						     'width'=>570, // Ancho de la tabla
						     'maxWidth'=>570, // Ancho Máximo de la tabla
						     'xOrientation'=>'center', // Orientación de la tabla
						     'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificación y ancho de la columna
						 			       'contenido'=>array('justification'=>'right','width'=>120))); // Justificación y ancho de la columna
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
			$ls_titulo="Estructura Programática";
		}
		
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('codestpro'=>'<b>'.$ls_titulo.'</b>',
						   'cuenta'=>'<b>Cuenta Presupuestaria</b>',
						   'denominacion'=>'<b>Denominación</b>',
						   'monto'=>'<b>Monto ('.$ls_bolivares.')</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'colGap'=>1, // Ancho Máximo de la tabla
						 'xPos'=>302.5, // Orientación de la tabla
						 'cols'=>array('codestpro'=>array('justification'=>'center','width'=>145), // Justificación y ancho de la columna
						 			   'cuenta'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>225), // Justificación y ancho de la columna
									   'monto'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($as_estcondat,$as_lugcom,$as_codmoneda,$ad_tasa,$ad_mondiv,$as_pais,$as_estado,$as_municipio,$as_parroquia,$ld_montot,$ls_monlet,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		    Acess: private 
		//	    Arguments: ld_montot  --> Monto total
		//	    		   ls_monlet   //Monto en letras
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los totales
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 21/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		$io_pdf->ezSetDy(-5);
		$la_data[1] = array('monlet'=>'<b>MONTO TOTAL EN LETRAS ('.$ls_bolivares.')</b>','monnum'=>'<b>MONTO TOTAL ('.$ls_bolivares.')</b>');
		$la_data[2] = array('monlet'=>$ls_monlet,'monnum'=>$ld_montot);
		$la_columnas=array('monlet'=>'','monnum'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>7, // Tamaño de Letras
						 'titleFontSize'=>12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('monlet'=>array('justification'=>'left','width'=>400),
						               'monnum'=>array('justification'=>'right','width'=>170))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	
	    //------------------------------------------------  Datos   ----------------------------------------------------------					
		if( ($as_estcondat=="B")  || ($as_estcondat=="-") || ($as_estcondat=="") )
        {			
				$la_data[1]=array('lugar'=>'<b>Lugar de Compra:   </b>'.$as_lugcom,'moneda'=>'<b>Moneda: </b>'.$as_codmoneda,'tasa'=>'<b>Tasa de Cambio: </b>'.$ad_tasa,'monto'=>'<b>Monto en Divisas: </b>'.$ad_mondiv);				
				$la_columna=array('lugar'=>'','moneda'=>'','tasa'=>'','monto'=>'');		
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'fontSize' => 8,   // Tamaño de Letras
								 'showLines'=>1,    // Mostrar Líneas
								 'shaded'=>0,       // Sombra entre líneas
								 'xOrientation'=>'center', // Orientación de la tabla
								 'width'=>570,      // Ancho de la tabla						 						 
								 'maxWidth'=>570,
								 'cols'=>array('lugar'=>array('justification'=>'left','width'=>142), // Justificación y ancho de la columna
											   'moneda'=>array('justification'=>'left','width'=>142), // Justificación y ancho de la columna
											   'tasa'=>array('justification'=>'left','width'=>144), // Justificación y ancho de la columna
											   'monto'=>array('justification'=>'left','width'=>142)));
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
								 'xOrientation'=>'center', // Orientación de la tabla
								 'width'=>570,      // Ancho de la tabla						 						 			 
								 'maxWidth'=>570,
								 'cols'=>array('pais'=>array('justification'=>'left','width'=>142), // Justificación y ancho de la columna
											   'estado'=>array('justification'=>'left','width'=>142), // Justificación y ancho de la columna
											   'municipio'=>array('justification'=>'left','width'=>144), // Justificación y ancho de la columna
											   'parroquia'=>array('justification'=>'left','width'=>142)));  
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
								 'xOrientation'=>'center', // Orientación de la tabla
								 'width'=>570, // Ancho de la tabla						 										 
								 'maxWidth'=>570,
								 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Ancho Máximo de la tabla
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
								 'xOrientation'=>'center', // Orientación de la tabla
								 'width'=>570, // Ancho de la tabla						 									 
								 'maxWidth'=>570,
								 'cols'=>array('clafie'=>array('justification'=>'left','width'=>190), // Justificación y ancho de la columna
											   'clapen'=>array('justification'=>'left','width'=>190),
											   'claesp'=>array('justification'=>'left','width'=>190))); 
				$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
				unset($la_data);
				unset($la_columna);
				unset($la_config);						
        }
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
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 19/10/2007. 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		$io_pdf->ezSetDy(-5);
		$la_columna=array('codigo'=>'<b>N° Ejecucion Presupuestaria</b>','denuniadm'=>'<b>Gerencia /Oficina Solicitante</b>');
		$la_config =array('showHeadings'=>1, // Mostrar encabezados
						  'fontSize'=> 8, // Tamaño de Letras
						  'titleFontSize'=>8,  // Tamaño de Letras de los títulos
						  'showLines'=>1, // Mostrar Líneas
						  'shaded'=>0, // Sombra entre líneas
						  'xOrientation'=>'center', // Orientación de la tabla
						  'width'=>570, // Ancho de la tabla						 										 
						  'maxWidth'=>570,
						  'cols'=>array('codigo'=>array('justification'=>'center','width'=>120),
						  				'denuniadm'=>array('justification'=>'left','width'=>450))
						); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datasep,$la_columna,'',$la_config);
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
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
		$la_columnas=array('titulo'=>'','contenido'=>'');
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

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("sigesp_soc_class_report.php");	
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../class_folder/class_funciones_soc.php");
	require_once("../../shared/class_folder/class_sql.php");	
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_funciones.php");

	$in           = new sigesp_include();
	$con          = $in->uf_conectar();
	$io_sql       = new class_sql($con);	
	$io_funciones = new class_funciones();	
	$io_fun_soc   = new class_funciones_soc();
	$io_report    = new sigesp_soc_class_report($con);
	$ls_estmodest = $_SESSION["la_empresa"]["estmodest"];
	$ls_orgrif	  = $_SESSION["la_empresa"]["rifemp"];
	$ls_orgnom    = $_SESSION["la_empresa"]["nombre"];
	$ls_orgtit	  = $_SESSION["la_empresa"]["titulo"];
	$ls_codemp	  = $_SESSION["la_empresa"]["codemp"];

	//Instancio a la clase de conversión de numeros a letras.
	require_once("../../shared/class_folder/class_numero_a_letra.php");
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
	$ls_numordcom=$io_fun_soc->uf_obtenervalor_get("numordcom","");
	$ls_estcondat=$io_fun_soc->uf_obtenervalor_get("tipord","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$rs_data   = $io_report->uf_select_orden_imprimir($ls_numordcom,$ls_estcondat,&$lb_valido); // Cargar los datos del reporte
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else  // Imprimimos el reporte
	{
		$ls_descripcion="Generó el Reporte de Orden de Compra";
		$lb_valido=$io_fun_soc->uf_load_seguridad_reporte("SOC","sigesp_soc_p_registro_orden_compra.php",$ls_descripcion);
		if($lb_valido)	
		{
			error_reporting(E_ALL);
			set_time_limit(1800);
			$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		    $io_pdf->ezSetCmMargins(11.5,7.5,3,3); // Configuración de los margenes en centímetros
			if ($row=$io_sql->fetch_row($rs_data))
			{
				$ls_numordcom = $row["numordcom"];
				$ls_estcondat = $row["estcondat"];
				$ls_coduniadm = $row["coduniadm"];
				$ls_denuniadm = $row["denuniadm"];
				$ls_codfuefin = $row["codfuefin"];
				$ls_denfuefin = $row["denfuefin"];
				$ls_diaplacom = $row["diaplacom"];
				$ls_forpagcom = $row["forpagcom"];
				$ls_codpro	  = $row["cod_pro"];
				$ls_nompro	  = $row["nompro"];
				$ls_rifpro	  = $row["rifpro"];
				$ls_dirpro	  = $row["dirpro"];
				$ls_telpro	  = $row["telpro"];
				$ld_fecordcom = $row["fecordcom"];
				$ls_conordcom = $row["obscom"];
				$ls_obsordcom = $row["obsordcom"];
				$ld_monsubtot = $row["monsubtot"];
				$ld_monimp	  = $row["monimp"];
				$ld_montot	  = $row["montot"];
				$li_seguro    = $row["estsegcom"];
				$ld_porseg    = $row["porsegcom"];
				$ld_monseg    = number_format($row["monsegcom"],2,',','.');
				$ld_monantpag = number_format($row["monant"],2,',','.');
				if ($li_seguro==0)
				   {
				     $ls_seguro="No";
				   }
				else
				   {
				     $ls_seguro="Si";
				   }
				if ($ls_tiporeporte==0)
				   {
					 $ld_montotaux = $row["montotaux"];
					 $ld_montotaux = number_format($ld_montotaux,2,",",".");
				   }
				$ls_nomdep    = $row["lugentnomdep"];
				$ls_dirdep    = $row["lugentdir"];
				$ls_conentord = $row["concom"];
				$numalet->setNumero($ld_montot);
				$ls_monto     = $numalet->letra();
				$ld_montot    = number_format($ld_montot,2,",",".");
				$ld_monsubtot = number_format($ld_monsubtot,2,",",".");
				$ld_monimp    = number_format($ld_monimp,2,",",".");
				$ld_fecordcom = $io_funciones->uf_convertirfecmostrar($ld_fecordcom);
				$ld_tasa      = $row["tascamordcom"];
				$ld_mondiv    = $row["montotdiv"];			
				$ls_lugcom    = $row["estlugcom"];
				$ls_denpai    = "";
				$ls_denest    = "";
				$ls_denmun    = "";
				$ls_denpar    = "";
				$ls_moneda    = "";
				$ls_codpai    = $row["codpai"];
				if ($ls_codpai!='---')
				   {
				     $ls_denpai = $io_report->uf_select_denominacion('sigesp_pais','despai',"WHERE codpai='".$ls_codpai."'");
				   }
				$ls_codest = $row["codest"];
				if ($ls_codest!="---")
				   {
				     $ls_denest = $io_report->uf_select_denominacion('sigesp_estados','desest',"WHERE codpai='".$ls_codpai."' AND codest='".$ls_codest."'");   
				   }
				$ls_codmun = $row["codmun"];
				if ($ls_codmun!="---")
				   {
				     $ls_denmun = $io_report->uf_select_denominacion('sigesp_municipio','denmun',"WHERE codpai='".$ls_codpai."' AND codest='".$ls_codest."' AND codmun='".$ls_codmun."'");				   
				   }
				$ls_codpar = $row["codpar"];
				if ($ls_codpar!="---")
				   {
				     $ls_denpar = $io_report->uf_select_denominacion('sigesp_parroquia','denpar',"WHERE codpai='".$ls_codpai."' AND codest='".$ls_codest."' AND codmun='".$ls_codmun."' AND codpar='".$ls_codpar."'");				   
				   }
				$ls_codmon = $row["codmon"];
				if ($ls_codmon!="---")
				   {
				     $ls_moneda = $io_report->uf_select_denominacion('sigesp_moneda','denmon',"WHERE codmon='".$ls_codmon."'");   
				   }
					    
				uf_print_encabezado_pagina($ls_estcondat,$ls_numordcom,$ld_fecordcom,$ls_codpro,$ls_nompro,$ls_rifpro,$ls_dirpro,
	                                       $ls_telpro,$ls_denfuefin,$ls_coduniadm,$ls_denuniadm,$ls_conordcom,$ls_obsordcom,
										   $ls_nomdep,$ls_dirdep,$ls_diaplacom,$ls_forpagcom,$ls_conentord,$ls_seguro,$ld_porseg,
										   $ld_monseg,$ld_monantpag,&$io_pdf);
			   
			   /////DETALLE  DE  LA ORDEN DE COMPRA
			   $rs_datos = $io_report->uf_select_detalle_orden_imprimir($ls_numordcom,$ls_estcondat,&$lb_valido);
			   if ($lb_valido)
			   {
		     	 $li_totrows = $io_sql->num_rows($rs_datos);
				 if ($li_totrows>0)
				 {
				    $li_i = 0;
				    while($row=$io_sql->fetch_row($rs_datos))
					{
						$li_i++;
						$ls_codartser=$row["codartser"];
						$ls_denartser=$row["denartser"];
						if($ls_estcondat=="B")
						{
							$ls_unidad=$row["unidad"];
						}
						else
						{
							$ls_unidad="";
						}
						if($ls_unidad=="D")
						{
						   $ls_unidad="Detal";
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
					uf_print_detalle($ls_estcondat,$la_data,$ld_monsubtot,$ld_monimp,$ld_montot,&$io_pdf);
					unset($la_data);
				    /////DETALLE  DE  LAS  CUENTAS DE GASTOS DE LA ORDEN DE COMPRA
					$rs_datos_cuenta=$io_report->uf_select_cuenta_gasto($ls_numordcom,$ls_estcondat,&$lb_valido); 
					if($lb_valido)
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
								if($ls_estmodest==1)
								{
									$ls_codestpro=$ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
								}
								else
								{
									$ls_codestpro=substr($ls_codestpro1,-2)."-".substr($ls_codestpro2,-2)."-".substr($ls_codestpro3,-2)."-".substr($ls_codestpro4,-2)."-".substr($ls_codestpro5,-2);
								}
								$la_data[$li_s]=array('codestpro'=>$ls_codestpro,'denominacion'=>$ls_dencuenta,
													  'cuenta'=>$ls_spg_cuenta,'monto'=>$ld_monto);
							}	
							uf_print_detalle_cuentas($la_data,&$io_pdf);
							unset($la_data);
						}
				     }
			       $lb_validosep = true;
				   $li_totrow	 = 0;
				   $lb_validosep = $io_report->uf_select_soc_sep($ls_codemp,$ls_numordcom,$ls_estcondat);	
				   if ($lb_validosep)
					  {										
					    $li_totrow = $io_report->ds_soc_sep->getRowCount("numordcom");							
						for ($li_row=1;$li_row<=$li_totrow;$li_row++)
							{
							  $ls_numsep   		   = $io_report->ds_soc_sep->data["numsol"][$li_row];  											  
							  $ls_denunadm 		   = $io_report->ds_soc_sep->data["denuniadm"][$li_row];  											  
							  $la_datasep[$li_row] = array('codigo'=>$ls_numsep,'denuniadm'=>$ls_denunadm);
							}														
						uf_print_detalle_sep($la_datasep,$io_pdf); 
					  } 
				  }
		       }
	     	}
		}
		uf_print_piecabecera($ls_estcondat,$ls_lugcom,$ls_moneda,$ld_tasa,$ld_mondiv,$ls_denpai,$ls_denest,$ls_denmun,$ls_denpar,$ld_montot,$ls_monto,&$io_pdf);
	    if ($ls_tiporeporte==0)
		   {
			 uf_print_piecabeceramonto_bsf($ld_montotaux,&$io_pdf);
		   }
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
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_soc);
?>