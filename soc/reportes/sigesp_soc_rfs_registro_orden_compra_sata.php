<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Formato de salida  de la Orden de Compra
//  ORGANISMO: Sata
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
	//--------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_estcondat,$as_numordcom,$ad_fecordcom,&$io_pdf)										
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_estcondat // Estatus de Orden Compra/Servicios
		//	    		   as_numordcom // Número de Orden de compra
		//	    		   ad_fecordcom // fecha de Orden de compra
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 04/10/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
		$ls_diremp=$_SESSION["la_empresa"]["direccion"];
		$ls_rifemp=$_SESSION["la_empresa"]["rifemp"];
		$io_encabezado=$io_pdf->openObject();		
		$io_pdf->saveState();
		if( ($as_estcondat=="B") || ($as_estcondat=="-") || ($as_estcondat==""))
        {
             $ls_titulo="ORDEN DE COMPRA";	
        }
        else
        {
             $ls_titulo="ORDEN DE SERVICIO";	
        }
		$io_pdf->setStrokeColor(0,0,0);
        ////////////////////// ENCABEZADO  ////////////////////////////		
		$io_pdf->addJpegFromFile('../../shared/imagebank/logo_sata2.jpg',40,945,550,50); // Agregar Logo
		$io_pdf->addText(40,935,9,"<b>REPUBLICA BOLIVARIANA DE VENEZUELA</b>"); // Agregar el título
		$io_pdf->addText(40,925,9,"<b>MINISTERIO POPULAR PARA LA INFRAESTRUCTURA</b>"); // Agregar el título
		$io_pdf->addText(40,915,9,"<b>".$ls_nomemp."</b>"); // Agregar el título
		$io_pdf->addText(40,895,9,"<b>".$ls_rifemp."</b>"); // Agregar el título
		$io_pdf->addText(40,905,9,"<b>".$ls_diremp."</b>"); // Agregar el título
		////////////////////// ENCABEZADO  ////////////////////////////		
		///////////////////// CUADRO ORDEN ////////////////////////////		
		$io_pdf->rectangle(360,864,230,30);
		$io_pdf->line(360,884,590,884);
		$io_pdf->line(455,864,455,884);
		$io_pdf->addText(455,886,8,"ORDEN"); // Agregar texto
		$io_pdf->addText(370,876,8,"FECHA"); // Agregar texto
		$io_pdf->addText(370,866,8,$ad_fecordcom); // Agregar Fecha
		$io_pdf->addText(457,876,8,"No."); // Agregar texto
		$io_pdf->addText(457,866,8,$as_numordcom); // Agregar Numero de la solicitud
		///////////////////// CUADRO ORDEN ////////////////////////////		
		$io_pdf->addText(220,866,9,"<b>".$ls_titulo."</b>"); // Agregar el título

		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}
	//--------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuadros_superiores($as_lugcom,$as_condicion,$as_denpro,$as_dirpro,$as_telpro,$ai_diaplacom,$as_formapago,
										 $as_dirdependencia,&$io_pdf)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cuadros_superiores
		//		   Access: private 
		//	    Arguments: ls_lugcom    // Lugar de Compra
		//	    		   as_condicion // Condicion de la Compra
		//	    		   as_denpro    // Nombre del Proveedor
		//	    		   as_dirpro    // Direccion del Proveedor
		//	    		   as_telpro    // Telefono del Proveedor
		//	    		   ai_diaplacom // Dias de Plazo de Entrega
		//	    		   as_formapago // Forma de Pago
		//	    		   as_dirdependencia // Direccion de Entrega
		//	    		   io_pdf       // Instancia de objeto pdf
		//    Description: Funcion que imprime la parte fija de la orden de compra
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 04/10/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(865);
		$la_data[1]=array('c1'=>'LUGAR DE COMPRA','c2'=>'SOLICITUD DE COTIZACION','c3'=>'OFICIO DE LA C.G.R.','c4'=>'CONDICIONES DE LA COMPRA');	
		$la_columna=array('c1'=>'','c2'=>'','c3'=>'','c4'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>548, // Ancho de la tabla						 									 
						 'maxWidth'=>548,
						 'cols'=>array('c1'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la columna
									   'c2'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
									   'c3'=>array('justification'=>'left','width'=>100),
									   'c4'=>array('justification'=>'left','width'=>230))); 
						 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		if($as_lugcom==0)
		{
			$ls_lugcom1="(X)Nacional";				
			$ls_lugcom2="( )Extranjero";				
		}
		else
		{
			$ls_lugcom1="( )Nacional";				
			$ls_lugcom2="(X)Extranjero";				
		}
		$ls_concom="( )CIF   ( )FOB   ( )FAS   ( )OTROS";
		switch($as_condicion)
		{
			case ("CIF"):
				$ls_concom="(X)CIF   ( )FOB   ( )FAS   ( )OTROS";
			break;
			case ("FOB"):
				$ls_concom="( )CIF   (X)FOB   ( )FAS   ( )OTROS";
			break;
			case ("FAS"):
				$ls_concom="( )CIF   ( )FOB   (X)FAS   ( )OTROS";
			break;
			case ("Otros"):
				$ls_concom="( )CIF   ( )FOB   ( )FAS   (X)OTROS";
			break;
		}
		$la_data[1]=array('c11'=>$ls_lugcom1,'c21'=>'','c22'=>'FECHA','c31'=>'No.','c32'=>'FECHA','c41'=>$ls_concom);	
		$la_data[2]=array('c11'=>$ls_lugcom2,'c21'=>'','c22'=>'','c31'=>'','c32'=>'','c41'=>'');	
		$la_columna=array('c11'=>'','c21'=>'','c22'=>'','c31'=>'','c32'=>'','c41'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>548, // Ancho de la tabla						 									 
						 'maxWidth'=>548,
						 'cols'=>array('c11'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la columna
									   'c21'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la columna
									   'c22'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
									   'c31'=>array('justification'=>'left','width'=>50),
									   'c32'=>array('justification'=>'left','width'=>50),
									   'c41'=>array('justification'=>'left','width'=>230))); 
						 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		
		$la_data[1]=array('c1'=>'PROVEEDOR:','c2'=>'REFERENCIA');	
		$la_data[2]=array('c1'=>'          <b>'.$as_denpro.'</b>','c2'=>'LICITACION GENERAL ( )');	
		$la_columna=array('c1'=>'','c2'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>548, // Ancho de la tabla						 									 
						 'maxWidth'=>548,
						 'cols'=>array('c1'=>array('justification'=>'left','width'=>320), // Justificación y ancho de la columna
									   'c2'=>array('justification'=>'left','width'=>230))); 
						 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('c1'=>'          ','c2'=>'LICITACION SELECTIVA ( )');	
		$la_columna=array('c1'=>'','c2'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>548, // Ancho de la tabla						 									 
						 'maxWidth'=>548,
						 'cols'=>array('c1'=>array('justification'=>'left','width'=>320), // Justificación y ancho de la columna
									   'c2'=>array('justification'=>'left','width'=>230))); 
						 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('c1'=>'DIRECCION: '.$as_dirpro,'c2'=>'ADJUDICACION');	
		$la_data[2]=array('c1'=>'TELEFONO: '.$as_telpro,'c2'=>'DIRECTA ( )');	
		$la_columna=array('c1'=>'','c2'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla						 									 
						 'maxWidth'=>550,
						 'cols'=>array('c1'=>array('justification'=>'left','width'=>320), // Justificación y ancho de la columna
									   'c2'=>array('justification'=>'left','width'=>230))); 
						 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('c1'=>'VIA','c11'=>'TIPO DE FONDO','c2'=>'PLAZO DE ENTREGA');	
		$la_columna=array('c1'=>'','c11'=>'','c2'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla						 									 
						 'maxWidth'=>550,
						 'cols'=>array('c1'=>array('justification'=>'left','width'=>220), // Justificación y ancho de la columna
						 			   'c11'=>array('justification'=>'left','width'=>100),
									   'c2'=>array('justification'=>'left','width'=>230))); 
						 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('c1'=>'( ) TERRESTRE       ( ) MARITIMA       ( ) AEREA','c11'=>'NIVEL              GENERAL','c2'=>'DIAS:'.$ai_diaplacom);	
		$la_data[2]=array('c1'=>'','c11'=>'','c2'=>'                                      HABILES  ( )           CONTINUOS  ( )');	
		$la_columna=array('c1'=>'','c11'=>'','c2'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla						 									 
						 'maxWidth'=>550,
						 'cols'=>array('c1'=>array('justification'=>'left','width'=>220), // Justificación y ancho de la columna
						 			   'c11'=>array('justification'=>'left','width'=>100),
									   'c2'=>array('justification'=>'left','width'=>230))); 
						 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$as_formapago=strtoupper($as_formapago);
		$la_data[1]=array('c1'=>'LUGAR DE ENTREGA: ','c2'=>'FORMA DE PAGO');	
		$la_data[2]=array('c1'=>$as_dirdependencia,'c2'=>'                                     '.$as_formapago);	
		$la_columna=array('c1'=>'','c2'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla						 									 
						 'maxWidth'=>550,
						 'cols'=>array('c1'=>array('justification'=>'left','width'=>320), // Justificación y ancho de la columna
									   'c2'=>array('justification'=>'left','width'=>230))); 
						 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}
	//--------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuadros_inferiores($as_obsordcom,$as_conordcom,$ad_tasa,$li_totconv,&$io_pdf)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cuadros_superiores
		//		   Access: private 
		//	    Arguments: as_obsordcom // Observaciones de la Orden de Compra
		//	    		   as_conordcom // Concepto de la Orden de Compra
		//	    		   ad_tasa      // Tasa cambiaria en Dolares
		//	    		   li_totconv   // Total de Monto de Conversion a Bs.
		//	    		   io_pdf       // Instancia de objeto pdf
		//    Description: Funcion que imprime la parte fija de la orden de compra
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 04/10/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_report;
		$ls_nombre=$io_report->uf_load_nombre_usuario();
		$la_data[1]=array('c1'=>'MONEDA EXTRANJERA','c2'=>'FINANCIAMIENTO');
		$la_columna=array('c1'=>'','c2'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>548, // Ancho de la tabla						 									 
						 'maxWidth'=>548,
						 'cols'=>array('c1'=>array('justification'=>'left','width'=>275), // Justificación y ancho de la columna
									   'c2'=>array('justification'=>'left','width'=>275))); 
						 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('c1'=>'DENOMINACION','c2'=>'TIPO DE CAMBIO             ','c3'=>'MONTO (Bs.)                 ','c4'=>'PRESUPUESTO DE LA VIGENCIA','c5'=>'AÑOS FUTUROS');
		$la_data[2]=array('c1'=>'','c2'=>$ad_tasa,'c3'=>$li_totconv,'c4'=>'','c5'=>'');
		$la_columna=array('c1'=>'','c2'=>'','c3'=>'','c4'=>'','c5'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>548, // Ancho de la tabla						 									 
						 'maxWidth'=>548,
						 'cols'=>array('c1'=>array('justification'=>'left','width'=>90), // Justificación y ancho de la columna
									   'c2'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
									   'c3'=>array('justification'=>'right','width'=>85), // Justificación y ancho de la columna
									   'c4'=>array('justification'=>'left','width'=>137),
									   'c5'=>array('justification'=>'left','width'=>138))); 
						 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		
		$la_data[1]=array('c1'=>'CONCEPTO: '.$as_conordcom);
		$la_columna=array('c1'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>548, // Ancho de la tabla						 									 
						 'maxWidth'=>548,
						 'cols'=>array('c1'=>array('justification'=>'left','width'=>550))); 
						 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);

		$la_data[1]=array('c1'=>'OBSERVACIONES: '.$as_obsordcom);
		$la_columna=array('c1'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>548, // Ancho de la tabla						 									 
						 'maxWidth'=>548,
						 'cols'=>array('c1'=>array('justification'=>'left','width'=>550))); 
						 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$ls_clausula1="Al aprobarse esta orden, se exigira al beneficiario fianza de fiel cumplimiento equivalente al      % del".
					  " monto de la orden, otorgada por un Banco o Compañia de Seguros, notariada y vigente hasta la fecha de".
					  " recepcion de la mercancia.";
		$ls_clausula2="Queda establecida la clausula penal segun lo cual el proveedor pagara al Fisco el 2% sobre el monto de la".
					  " mercancia respectiva por cada dia habil de retardo en la entrega, maximo hasta un diez (10%) por ciento.";
		$ls_clausula3=" El organismno se reserva el derecho de anular unilateralmente la presente orden de compra sin".
					  " indemnizacion de conformidad con lo dispuesto en el reglamento que rige la materia.";
		$la_data[1]=array('c1'=>'FIANZA DE FIEL CUMPLIMIENTO','c2'=>'CLAUSULA PENAL','c3'=>'CLAUSULA ESPECIAL');	
		$la_data[2]=array('c1'=>$ls_clausula1,'c2'=>$ls_clausula2,'c3'=>$ls_clausula3);	
		$la_columna=array('c1'=>'','c2'=>'','c3'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>548, // Ancho de la tabla						 									 
						 'maxWidth'=>548,
						 'cols'=>array('c1'=>array('justification'=>'left','width'=>170), // Justificación y ancho de la columna
									   'c2'=>array('justification'=>'left','width'=>210),
									   'c3'=>array('justification'=>'left','width'=>170))); 
						 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('c1'=>'FIRMAS','c2'=>'PARA USO DE LA CONTRALORIA');	
		$la_columna=array('c1'=>'','c2'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla						 									 
						 'maxWidth'=>550,
						 'cols'=>array('c1'=>array('justification'=>'left','width'=>275), // Justificación y ancho de la columna
									   'c2'=>array('justification'=>'left','width'=>275))); 
						 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('c1'=>'CONTADOR S.A.T.A.','c2'=>'');
		$la_data[2]=array('c1'=>'','c2'=>'');
		$la_data[3]=array('c1'=>'','c2'=>'');
		$la_columna=array('c1'=>'','c2'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla						 									 
						 'maxWidth'=>550,
						 'cols'=>array('c1'=>array('justification'=>'left','width'=>275), // Justificación y ancho de la columna
									   'c2'=>array('justification'=>'left','width'=>275))); 
						 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('c1'=>'PREPARADO POR DPTO. DE COMPRAS','c2'=>'');
		$la_data[2]=array('c1'=>'','c2'=>'');
		$la_data[3]=array('c1'=>$ls_nombre,'c2'=>'REVISADO:');
		$la_columna=array('c1'=>'','c2'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla						 									 
						 'maxWidth'=>550,
						 'cols'=>array('c1'=>array('justification'=>'left','width'=>275), // Justificación y ancho de la columna
									   'c2'=>array('justification'=>'left','width'=>275))); 
						 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('c1'=>'DIRECTOR DE ADMINISTRACION','c2'=>'');
		$la_data[2]=array('c1'=>'','c2'=>'');
		$la_data[3]=array('c1'=>'CAP.(Av.) EDGAR JOSÉ NUÑEZ','c2'=>'');
		$la_columna=array('c1'=>'','c2'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla						 									 
						 'maxWidth'=>550,
						 'cols'=>array('c1'=>array('justification'=>'left','width'=>275), // Justificación y ancho de la columna
									   'c2'=>array('justification'=>'left','width'=>275))); 
						 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('c1'=>'DIRECTOR GENERAL SECTORIAL','c2'=>'DECISION:');
		$la_data[2]=array('c1'=>'','c2'=>'');
		$la_data[3]=array('c1'=>'LIC. JOSÉ LUIS MARTÍNEZ BRAVO','c2'=>'');
		$la_columna=array('c1'=>'','c2'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla						 									 
						 'maxWidth'=>550,
						 'cols'=>array('c1'=>array('justification'=>'left','width'=>275), // Justificación y ancho de la columna
									   'c2'=>array('justification'=>'left','width'=>275))); 
						 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('c1'=>'RECEPCION PARA EL PROVEEDOR','c2'=>'');
		$la_columna=array('c1'=>'','c2'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla						 									 
						 'maxWidth'=>550,
						 'cols'=>array('c1'=>array('justification'=>'left','width'=>275), // Justificación y ancho de la columna
									   'c2'=>array('justification'=>'left','width'=>275))); 
						 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('c1'=>'FIRMA Y CEDULA DE IDENTIDAD','c2'=>'FECHA','c3'=>'');
		$la_data[2]=array('c1'=>'','c2'=>'','c3'=>'');
		$la_data[3]=array('c1'=>'','c2'=>'','c3'=>'');
		$la_columna=array('c1'=>'','c2'=>'','c3'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla						 									 
						 'maxWidth'=>550,
						 'cols'=>array('c1'=>array('justification'=>'left','width'=>190), // Justificación y ancho de la columna
									   'c2'=>array('justification'=>'left','width'=>85),
									   'c3'=>array('justification'=>'left','width'=>275))); 
						 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}
	//--------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_bienes($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Selena Lucena 
		// Fecha Creación: 17/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////						
		$io_pdf->ezSetDy(-2);
		$la_columna=array('codigo'=>'<b>CODIGO</b>',
						  'denominacion'=>'<b>DENOMINACION</b>',
						  'cantidad'=>'<b>CANTIDAD</b>',
						  'precio'=>'<b>PRECIO</b>',
						  'unidad'=>'<b>UNIDAD</b>',
						  'subtotal'=>'<b>SUBTOTAL</b>',
						  'cargos'=>'<b>CARGOS</b>',
						  'total'=>'<b>TOTAL</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'xPos'=>320, // Orientación de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>160), // Justificación y ancho de la columna
						 			   'cantidad'=>array('justification'=>'right','width'=>45), // Justificación y ancho de la columna
						 			   'precio'=>array('justification'=>'right','width'=>55),
   						 			   'unidad'=>array('justification'=>'center','width'=>40),
									   'subtotal'=>array('justification'=>'right','width'=>55),
									   'cargos'=>array('justification'=>'right','width'=>55),
									   'total'=>array('justification'=>'right','width'=>60))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}
	//--------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_servicios($la_dataser,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Selena Lucena 
		// Fecha Creación: 17/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////						
		$io_pdf->ezSetDy(-2);
		$la_columna=array('codigo'=>'<b>Codigo</b>',
						  'denominacion'=>'<b>Denominacion</b>',
						  'cantidad'=>'<b>Cant</b>',
						  'precio'=>'<b>Precio</b>',						 
						  'subtotal'=>'<b>SubTotal</b>',
						  'cargos'=>'<b>Cargos</b>',
						  'total'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 6,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>548, // Ancho de la tabla
						 'maxWidth'=>548, // Ancho Máximo de la tabla
						 'xPos'=>320, // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
						 			   'cantidad'=>array('justification'=>'right','width'=>45), // Justificación y ancho de la columna
						 			   'precio'=>array('justification'=>'right','width'=>55),   						 			  
									   'subtotal'=>array('justification'=>'right','width'=>55),
									   'cargos'=>array('justification'=>'right','width'=>55),
									   'total'=>array('justification'=>'right','width'=>60))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_dataser,$la_columna,'',$la_config);
	}
	//--------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
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
		global $ls_estmodest, $ls_bolivares;
		if($ls_estmodest==1)
		{
			$ls_titulo_grid="Bienes";
		}
		else
		{
			$ls_titulo_grid="Servicios";
		}
		$io_pdf->ezSetY(650);
		$la_datatitulo[1]=array('columna1'=>'<b> Detalle de '.$ls_titulo_grid.'</b>');
		$la_columnas=array('columna1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatitulo,$la_columnas,'',$la_config);
		unset($la_datatitulo);
		unset($la_columnas);
		unset($la_config);
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('codigo'=>'<b>Código</b>',
						   'denominacion'=>'<b>Denominacion</b>',
						   'cantidad'=>'<b>Cant.</b>',
						   'unidad'=>'<b>Unidad</b>',
						   'cosuni'=>'<b>Costo '.$ls_bolivares.'</b>',
						   'baseimp'=>'<b>Sub-Total '.$ls_bolivares.'</b>',
						   'cargo'=>'<b>Cargo '.$ls_bolivares.'</b>',
						   'montot'=>'<b>Total '.$ls_bolivares.'</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>115), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>115), // Justificación y ancho de la columna
						 			   'cantidad'=>array('justification'=>'left','width'=>40), // Justificación y ancho de la columna
						 			   'unidad'=>array('justification'=>'center','width'=>45), // Justificación y ancho de la columna
						 			   'cosuni'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
						 			   'baseimp'=>array('justification'=>'right','width'=>65), // Justificación y ancho de la columna
						 			   'cargo'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
						 			   'montot'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_cuentas($aa_data,&$io_pdf)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cuentas_spg
		//		   Access: private 
		//	    Arguments: aa_data // Arreglo de datos
		//	    		   io_pdf  // Instancia de objeto pdf
		//    Description: Funcion que imprime las cuentas presupuestarias
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 04/10/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('c1'=>'CODIGO DE TIEMPO','c2'=>'ESTRUCTURA PRESUPUESTARIA','c3'=>'CUENTA','c4'=>'DENOMINACION','c5'=>'MONTO','c6'=>'');
		$la_columna=array('c1'=>'','c2'=>'','c3'=>'','c4'=>'','c5'=>'','c6'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>548, // Ancho de la tabla						 									 
						 'maxWidth'=>548,
						 'cols'=>array('c1'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'c2'=>array('justification'=>'center','width'=>130), // Justificación y ancho de la columna
									   'c3'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
									   'c4'=>array('justification'=>'center','width'=>140), // Justificación y ancho de la columna
									   'c5'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
									   'c6'=>array('justification'=>'center','width'=>80))); 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_columna=array('c1'=>'','c2'=>'','c3'=>'','c4'=>'','c5'=>'','c6'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>548, // Ancho de la tabla						 									 
						 'maxWidth'=>548,
						 'cols'=>array('c1'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'c2'=>array('justification'=>'center','width'=>130), // Justificación y ancho de la columna
									   'c3'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
									   'c4'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
									   'c5'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
									   'c6'=>array('justification'=>'left','width'=>80))); 
		$io_pdf->ezTable($aa_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($li_subtot,$li_totcar,$li_montot,$ls_monlet,$li_montotaux,$ls_codmoneda,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: ad_subtotal // Monto del Subtotal
		//	    		   ad_cargos // Monto de los Cargos
		//	    		   ad_total // Monto total
		//	    		   io_pdf // Instancia de Objeto PDF
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Selena Lucena 
		// Fecha Creación: 17/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tiporeporte;
		
		$la_data[1]=array('titulo'=>'<b>Sub-Total</b>','valor'=>$li_subtot);
		$la_data[2]=array('titulo'=>'<b>IVA 9% / Otros Impuestos</b>','valor'=>$li_totcar);
   		if ($ls_codmoneda=="001")
		   {
		     $la_data[3]=array('titulo'=>'<b>Total $</b>','valor'=>$li_montot);   
		   }
		else
		   {
		     $la_data[3]=array('titulo'=>'<b>Total Bs.</b>','valor'=>$li_montot);
		   }
		
		/*if($ls_codmoneda!="001")
		{
			if($ls_tiporeporte==0)
			{
				$la_data[4]=array('titulo'=>'<b>Total Bs.F.</b>','valor'=>$li_montotaux);
			}
		}*/

		$la_columna=array('titulo'=>'','valor'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>  6, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla						 
						 'maxWidth'=>580, // Ancho Máximo de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificación y ancho de la columna
						 			   'valor'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('titulo'=>'<b> MONTO TOTAL EN LETRA: '.$ls_monlet.'</b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>  6, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla						 
						 'maxWidth'=>580, // Ancho Máximo de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>550))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
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
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
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
	$io_report    = new sigesp_soc_class_report($con);
	$ls_estmodest = $_SESSION["la_empresa"]["estmodest"];
	$ls_codemp    = $_SESSION["la_empresa"]["codemp"];
	global $io_report;
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
	$ls_numordcom=$io_fun_soc->uf_obtenervalor_get("numordcom","");
	$ls_estcondat=$io_fun_soc->uf_obtenervalor_get("tipord","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$rs_data= $io_report->uf_select_orden_imprimir($ls_numordcom,$ls_estcondat,&$lb_valido); // Cargar los datos del reporte
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
			$io_pdf=new Cezpdf('LEGAL','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(6,2,3,3); // Configuración de los margenes en centímetros
			if ($row=$io_sql->fetch_row($rs_data))
			{
				$ls_numordcom=$row["numordcom"];
				$ls_estcondat=$row["estcondat"];
				$ls_coduniadm=$row["coduniadm"];
				$ls_denuniadm=$row["denuniadm"];
				$ls_codfuefin=$row["codfuefin"];
				$ls_denfuefin=$row["denfuefin"];
				$ls_diaplacom=$row["diaplacom"];
				$ls_forpagcom=$row["forpagcom"];
				$ls_codpro=$row["cod_pro"];
				$ls_nompro=$row["nompro"];
				$ls_rifpro=$row["rifpro"];
				$ls_dirpro=$row["dirpro"];
				$ld_fecordcom=$row["fecordcom"];
				$ls_obscom=$row["obscom"];
				$ld_monsubtot=$row["monsubtot"];
				$ld_monimp=$row["monimp"];
				$ld_montot=$row["montot"];
				$ls_estlugcom=$row["estlugcom"];
				$ls_concom=$row["concom"];
				$ls_telpro=$row["telpro"];
				$li_diaplacom=$row["diaplacom"];
				$ls_lugentdir=$row["lugentdir"];
				$ls_obsordcom=$row["obsordcom"];
			    $li_tasa= $row["tascamordcom"];			
				$li_totconv=($ld_montot*$li_tasa);
				$ls_codmoneda=$row["codmon"];	
				if($ls_tiporeporte==0)
				{
					$ld_montotaux=$row["montotaux"];
					$ld_montotaux=number_format($ld_montotaux,2,",",".");
				}
				else
				{
					$ld_montotaux="";
				}
				if($ls_codmoneda=="001")
				{
				   	$numalet->setMoneda("Dolares");
				    $ls_simmon = "$";
			    }
				$numalet->setNumero($ld_montot);
				$ls_monto= $numalet->letra();
				$ld_montot=number_format($ld_montot,2,",",".");
				$ld_monsubtot=number_format($ld_monsubtot,2,",",".");
				$li_tasa=number_format($li_tasa,2,",",".");
				$ld_monimp=number_format($ld_monimp,2,",",".");
				$li_totconv=number_format($li_totconv,2,",",".");
				$ld_fecordcom=$io_funciones->uf_convertirfecmostrar($ld_fecordcom);
				if($li_tasa==0)
				{
					$li_tasa="";
					$li_totconv="";
				}
				uf_print_encabezado_pagina($ls_estcondat,$ls_numordcom,$ld_fecordcom,&$io_pdf);		 
				uf_print_cuadros_superiores($ls_estlugcom,$ls_concom,$ls_nompro,$ls_dirpro,$ls_telpro,$li_diaplacom,$ls_forpagcom,
										    $ls_lugentdir,&$io_pdf);
				    /////DETALLE  DE  LAS  CUENTAS DE GASTOS DE LA ORDEN DE COMPRA
					$rs_datos_cuenta=$io_report->uf_select_cuenta_gasto($ls_numordcom,$ls_estcondat,&$lb_valido); 
					if($lb_valido)
					{
						 $li_totrows = $io_sql->num_rows($rs_datos_cuenta);
						 if ($li_totrows>0)
						 {
							$li_s = 0;
							while($row=$io_sql->fetch_row($rs_datos_cuenta))
							{
								$li_s=$li_s+1;
								$ls_codestpro1=trim($row["codestpro1"]);
								$ls_codestpro2=trim($row["codestpro2"]);
								$ls_codestpro3=trim($row["codestpro3"]);
								$ls_codestpro4=trim($row["codestpro4"]);
								$ls_codestpro5=trim($row["codestpro5"]);
								$ls_spg_cuenta=$row["spg_cuenta"];
								$ld_monto=$row["monto"];
								$ld_monto=number_format($ld_monto,2,",",".");
								$ls_dencuenta="";
								$lb_valido = $io_report->uf_select_denominacionspg($ls_spg_cuenta,$ls_dencuenta);																																						
								if($ls_estmodest==1)
								{
									$ls_codestpro=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3;
								}
								else
								{
									$ls_codestpro=substr($ls_codestpro1,-2)."-".substr($ls_codestpro2,-2)."-".substr($ls_codestpro3,-2)."-".substr($ls_codestpro4,-2)."-".substr($ls_codestpro5,-2);
								}
								$ls_dato="";
								switch ($li_s)
								{
									case "1":
										$ls_dato="ORDEN PAGO ( )";
									break;
									case "2":
										$ls_dato="CARTA CRED ( )";
									break;
									case "3":
										$ls_dato="TRANSFERENCIAL ( )";
									break;
									case "4":
										$ls_dato="OTROS ( )";
									break;
								}
								$la_data[$li_s]= array('c1'=>'','c2'=>$ls_codestpro,'c3'=>$ls_spg_cuenta,'c4'=>$ls_dencuenta,'c5'=>$ld_monto,'c6'=>$ls_dato);
							}
							if($li_s<4)
							{
								for($li_i=1;$li_i<=4;$li_i++)
								{
									$li_s++;
									$ls_codestpro="";
									$ls_spg_cuenta="";
									$ls_dencuenta="";
									$ld_monto="";
									$ls_dato="";
									switch ($li_s)
									{
										case "1":
											$ls_dato="ORDEN PAGO ( )";
										break;
										case "2":
											$ls_dato="CARTA CRED ( )";
										break;
										case "3":
											$ls_dato="TRANSFERENCIAL ( )";
										break;
										case "4":
											$ls_dato="OTROS ( )";
										break;
									}
									$la_data[$li_s]= array('c1'=>'','c2'=>$ls_codestpro,'c3'=>$ls_spg_cuenta,'c4'=>$ls_dencuenta,'c5'=>$ld_monto,'c6'=>$ls_dato);
								}
							}
							uf_print_detalle_cuentas($la_data,&$io_pdf);
							unset($la_data);
						}
				     }
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
						$li_i=$li_i+1;
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
						$li_cantartser=$row["cantartser"];
						$ld_preartser=$row["preartser"];
						$ld_subtotartser=$ld_preartser*$li_cantartser;
						$ld_totartser=$row["monttotartser"];
						$ld_carartser=$ld_totartser-$ld_subtotartser;
						
						
						$ld_preartser=number_format($ld_preartser,2,",",".");
						$ld_subtotartser=number_format($ld_subtotartser,2,",",".");
						$ld_totartser=number_format($ld_totartser,2,",",".");
						$ld_carartser=number_format($ld_carartser,2,",",".");
					if($ls_estcondat=="B")
					{
					  $la_data[$li_i]= array('codigo'=>$ls_codartser,'denominacion'=>$ls_denartser,'cantidad'=>$li_cantartser,
					  						 'precio'=>$ld_preartser,'unidad'=>$ls_unidad,'subtotal'=>$ld_subtotartser,'cargos'=>$ld_carartser,'total'=>$ld_totartser);
					}
					else
					{
					  $la_dataser[$li_i]= array('codigo'=>$ls_codartser,'denominacion'=>$ls_denartser,'cantidad'=>$li_cantartser,
					  							  'precio'=>$ld_preartser,'subtotal'=>$ld_subtotartser,'cargos'=>$ld_carartser,'total'=>$ld_totartser);
					}
					}
					if($ls_estcondat=="B")
					{
						uf_print_detalle_bienes($la_data,$io_pdf); 
					}
					else
					{
						uf_print_detalle_servicios($la_dataser,$io_pdf); 
					}
					unset($la_data);
			      }
		       }
	     	}
		}
		uf_print_piecabecera($ld_monsubtot,$ld_monimp,$ld_montot,$ls_monto,$ld_montotaux,$ls_codmoneda,&$io_pdf);
		uf_print_cuadros_inferiores($ls_obsordcom,$ls_obscom,$li_tasa,$li_totconv,&$io_pdf);
		 
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