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
		print "</script>";		
	}

	//--------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_estcondat,$hidnumero,$ls_fecord,&$io_pdf)										
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   hidnumero // Número de Orden de compra
		//	    		   ls_fecord // fecha de Orden de compra
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
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],40,945,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
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
		$io_pdf->addText(370,866,8,$ls_fecord); // Agregar Fecha
		$io_pdf->addText(457,876,8,"No."); // Agregar texto
		$io_pdf->addText(457,866,8,$hidnumero); // Agregar Numero de la solicitud
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
		$la_data[3]=array('c1'=>'MT1 (AV) JOSE ALBERTO TREJO CASTILLO','c2'=>'REVISADO:');
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
		$la_data[3]=array('c1'=>'CAP.(Av.) EDGAR NUÑEZ','c2'=>'');
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
		$la_data[3]=array('c1'=>'LIC. JOSE LUIS MARTINEZ BRAVO','c2'=>'');
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
/*		$la_data[1]=array('c1'=>'VIA','c11'=>'TIPO DE FONDO','c2'=>'PLAZO DE ENTREGA');	
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
		unset($la_config);*/
	}
	//--------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuentas_spg($aa_data,&$io_pdf)
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
	
	//--------------------------------------------------------------------------------------------------------------------------
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
		//	   Creado Por: Ing. Selena Lucena 
		// Fecha Creación: 17/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ad_total = number_format($ad_total,2,',','.');		
		$la_data[1]=array('titulo'=>'<b>Sub-Total</b>','valor'=>$ad_subtotal);
		$la_data[2]=array('titulo'=>'<b>Cargos</b>','valor'=>$ad_cargos);
   		$la_data[3]=array('titulo'=>'<b>Total</b>','valor'=>$ad_total);				
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
	}
	//--------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pie($adec_monto,$ls_nomproben,$as_monto,$as_lugcom,$as_codmoneda,$as_moneda,$ad_tasa,$ad_mondiv,
									 $as_estcondat,$as_pais,$as_estado,$as_municipio,$as_parroquia,$as_estcondat,$as_codmoneda,&$io_pdf)
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
		//	   Creado Por: Ing. Selena Lucena 
		// Fecha Creación: 25/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($as_codmoneda=="001")
		{
			$ls_moneda="($)";
		}
		else
		{
			$ls_moneda="(Bs.)";
		}
		$la_data[1]=array('monlet'=>'<b>MONTO TOTAL EN LETRAS '.$ls_moneda.'</b>','monnum'=>'<b>MONTO TOTAL '.$ls_moneda.'</b>');
		$la_data[2]=array('monlet'=>$as_monto,'monnum'=>$adec_monto);				
		$la_columna=array('monlet'=>'','monnum'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla						 									 
						 'maxWidth'=>580,
                         'cols'=>array('monlet'=>array('justification'=>'left','width'=>400), // Justificación y ancho de la columna
									   'monnum'=>array('justification'=>'right','width'=>150))); 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);				
	}
	//--------------------------------------------------------------------------------------------------------------------------
	
   
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_sql.php");	
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_soc_class_report.php");	
	require_once("../../shared/class_folder/class_funciones.php");
	
	$in= new sigesp_include();
	$con= $in->uf_conectar();
	$io_sql= new class_sql($con);	
	$io_report= new sigesp_soc_class_report($con);
	$io_funciones= new class_funciones();		
	
	//----------------------------------------------------  Inicializacion de variables  -----------------------------------------------
	$lb_valido= false;
	$lb_validobie= false;
	$lb_validoser= false;	
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codemp= $_SESSION["la_empresa"]["codemp"];
	$hidnumero= $_GET["hidnumero"];
    $hidtipcom= $_GET["hidtipcom"];
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_select_orden_imprimir($ls_codemp,$hidnumero,$hidtipcom); 
	if($lb_valido==false) 
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
		$io_pdf=new Cezpdf('LEGAL','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(8,6,3,3); // Configuración de los margenes en centímetros
		$li_totrow=$io_report->ds_orden->getRowCount("numordcom");
		for($i=1;$i<=$li_totrow;$i++)
		{
				$li_totprenom=0;
				$li_totant=0;
				$ls_pais= "";
				$ls_estado= "";
				$ls_parroquia= "";
				$ls_municipio= "";
				$ls_denfuefin= "";
                $ls_moneda= "";				 
				$ls_numord= $io_report->ds_orden->data["numordcom"][$i];	
                $ls_estcondat= $io_report->ds_orden->data["estcondat"][$i];										
				$ls_codprov= $io_report->ds_orden->data["cod_pro"][$i];				   		                    
				$ls_proveedor= $io_report->uf_select_nombre_pro($ls_codemp,$ls_codprov);
				$ls_dirpro= $io_report->uf_select_dirpro($ls_codemp,$ls_codprov);		
				$ls_rifpro= $io_report->uf_select_rifpro($ls_codemp,$ls_codprov);		
				$ls_nitpro= $io_report->uf_select_rifpro($ls_codemp,$ls_codprov);		
				$ls_telpro= $io_report->uf_select_tel_pro($ls_codemp,$ls_codprov);          								
				$ls_fecha= $io_report->ds_orden->data["fecordcom"][$i];		
				$ls_fecord= substr($ls_fecha,8,2)."/".substr($ls_fecha,5,2)."/".substr($ls_fecha,0,4);			
				$li_seguro= $io_report->ds_orden->data["estsegcom"][$i];		
				$ld_porcentaje= $io_report->ds_orden->data["porsegcom"][$i];	
				$ld_montoseg= $io_report->ds_orden->data["monsegcom"][$i];			   
				$li_diaplacom= $io_report->ds_orden->data["diaplacom"][$i];	
				$ls_condicion= $io_report->ds_orden->data["concom"][$i];	
				$ls_observacion= $io_report->ds_orden->data["obscom"][$i];	
				$ls_obsordcom= $io_report->ds_orden->data["obsordcom"][$i];	
				$ls_formapago= $io_report->ds_orden->data["forpagcom"][$i];	
				$ls_estus= $io_report->ds_orden->data["estcom"][$i];	               
				$ld_monbas= $io_report->ds_orden->data["monsubtot"][$i];		             
				$ld_moncar= $io_report->ds_orden->data["monimp"][$i];		
				$ld_monto= $io_report->ds_orden->data["montot"][$i];			
				$ls_codmodcla= $io_report->ds_orden->data["codtipmod"][$i];			
				$ls_codpais= $io_report->ds_orden->data["codpai"][$i];							                    
				$ls_pais= $io_report->uf_select_pais($ls_codpais);               
				$ls_codestado= $io_report->ds_orden->data["codest"][$i];	
				$ls_estado= $io_report->uf_select_estado($ls_codpais,$ls_codestado);                   				
				$ls_codmunicipio= $io_report->ds_orden->data["codmun"][$i];		
				$ls_municipio= $io_report->uf_select_municipio($ls_codpais,$ls_codestado,$ls_codmunicipio);                   				
				$ls_codparroquia= $io_report->ds_orden->data["codpar"][$i];
				$ls_parroquia= $io_report->uf_select_parroquia($ls_codpais,$ls_codestado,$ls_codmunicipio,$ls_codparroquia);                										
				$ld_monant= $io_report->ds_orden->data["monant"][$i];			
				$ls_lugcom= $io_report->ds_orden->data["estlugcom"][$i];	
				$ls_codfuefin= $io_report->ds_orden->data["codfuefin"][$i];				 								  				
				$ls_dependencia = $io_report->ds_orden->data["lugentnomdep"][$i];		
				$ls_codmoneda= $io_report->ds_orden->data["codmon"][$i];	
				$ls_moneda= $io_report->uf_select_denmoneda($ls_codmoneda);           
			    $ld_tasa= $io_report->ds_orden->data["tascamordcom"][$i];			
				$ld_mondiv= $io_report->ds_orden->data["montotdiv"][$i];			
				$ls_codmodcla= $io_report->ds_orden->data["codtipmod"][$i];		
				$ls_dirdependencia=$io_report->ds_orden->data["lugentdir"][$i];	
				$ls_coduniadm= $io_report->ds_orden->data["coduniadm"][$i];	
				$ld_fecent= $io_report->ds_orden->data["fecent"][$i];
				$li_totconv=($ld_monto*$ld_tasa);
				$ld_fecentrega= substr($ld_fecent,8,2)."/".substr($ld_fecent,5,2)."/".substr($ld_fecent,0,4);	
				$ls_denuniadm= "";
				
				if(empty($ls_coduniadm) )
				{
				    $ls_coduniadm= $io_report->uf_select_unid_adm($ls_codemp,$ls_numord,$ls_estcondat);
				}
				
				if(!(empty($ls_coduniadm)))
				{
  				    $ls_denuniadm= $io_report->uf_select_den_unidadm($ls_codemp,$ls_coduniadm);
				}
				
			    if($ls_codfuefin!="--")
				{
				   $ls_denfuefin= $io_report->uf_select_denfuefin($ls_codfuefin);   
				}  
				else
				{
				   $ls_denfuefin="";
				   $ls_codfuefin="";
				}
				
				if($ls_codmoneda!="---")
				{
				   $ls_moneda = $io_report->uf_select_denmoneda($ls_codmoneda);  
				}  
				else
				{
				   $ls_moneda = "";
				   $ls_codmoneda="";
				}
			
				if($ls_lugcom==0)
				 {
					$ls_lugcom="Nacional";				
				 }
				else
				 {
					$ls_lugcom="Extranjero";				
				 }
				 
				if($ls_pais=="---seleccione---")
				{
				  $ls_pais="";
				}
				if($ls_estado=="---seleccione---")
                {
				   $ls_estado="";
				}				
				if($ls_municipio=="---seleccione---")
                {
				  $ls_municipio="";
				}				
				if($ls_parroquia=="---seleccione---")  
				{
				  $ls_parroquia="";
				}
				if($ls_codmoneda=="001")
				{
					//Instancio a la clase de conversión de numeros a letras.
					include("../../shared/class_folder/class_numero_a_letra.php");
					$numalet= new class_numero_a_letra();
					//imprime numero con los valore por defecto
					//cambia a minusculas
					$numalet->setMayusculas(1);
					//cambia a femenino
					$numalet->setGenero(1);
					//cambia moneda
					$numalet->setMoneda("Dolares");
					//cambia prefijo
					$numalet->setPrefijo("***");
					//cambia sufijo
					$numalet->setSufijo("***");
				}
				else
				{
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
				}
					
				$ld_monbas    = number_format($ld_monbas,2,',','.');			             
				$ld_moncar    = number_format($ld_moncar,2,',','.');	
				$ld_monto     = number_format($ld_monto,2,',','.');	
				$ld_tasa      = number_format($ld_tasa,2,',','.');	     
				$ld_mondiv    = number_format($ld_mondiv,2,',','.');	     
				$ld_porcentaje= number_format($ld_porcentaje,0,'','');	     
				$ld_monant    = number_format($ld_monant,2,',','.');	     
				$ld_montoseg  = number_format($ld_montoseg,2,',','.');	     			
				$li_totconv  = number_format($li_totconv,2,',','.');	     			
				if($ls_formapago=="s1")
				 {
					$ls_formapago="";
				 }				 
				if($ls_condicion=="s1")
				 {
					$ls_condicion="";
				 }				 
				uf_print_encabezado_pagina($ls_estcondat,$hidnumero,$ls_fecord,&$io_pdf); // Imprimimos el encabezado de la página
				uf_print_cuadros_superiores($ls_lugcom,$ls_condicion,$ls_proveedor,$ls_dirpro,$ls_telpro,$li_diaplacom,
											$ls_formapago,$ls_dirdependencia,$io_pdf);
		}				  
		//-------------------------------SPG-------------------------------------- 												
		$li_totrowspg=0;
		$lb_validosqg   =$io_report->uf_select_cuenta_spg($ls_codemp,$hidnumero,$hidtipcom);
		$li_totrowspg=$io_report->ds_spg_dt->getRowCount("numordcom");
		$li_totrowcount=$li_totrowspg;
		if($li_totrowspg<4)
		{
			$li_totrowcount=4;
		}
		for($li_i=1;$li_i<=$li_totrowcount;$li_i++)
		{
			if($li_i<=$li_totrowspg)
			{
				$denominacion ="";
				$ls_numsol= $io_report->ds_spg_dt->data["numordcom"][$li_i];
				$ls_codestpro1= $io_report->ds_spg_dt->data["codestpro1"][$li_i];
				$ls_codestpro2= $io_report->ds_spg_dt->data["codestpro2"][$li_i];
				$ls_codestpro3= $io_report->ds_spg_dt->data["codestpro3"][$li_i];
				$ls_codestpro4= $io_report->ds_spg_dt->data["codestpro4"][$li_i];
				$ls_codestpro5= $io_report->ds_spg_dt->data["codestpro5"][$li_i];
				$ls_spgcuenta= $io_report->ds_spg_dt->data["spg_cuenta"][$li_i];
				$li_monto= $io_report->ds_spg_dt->data["monto"][$li_i];				
				$li_monto= number_format($li_monto,2,',','.');
				$lb_valden=$io_report->uf_select_existe_cuenta($ls_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
															   $ls_codestpro4,$ls_codestpro5,$ls_spgcuenta);							    
				if($lb_valden)
				{
					$ls_denominacion =$io_report->uf_select_denominacionspg($ls_codemp,$ls_spgcuenta);
				}
				$ls_codestpro=$ls_codestpro1." - ".$ls_codestpro2." - ".$ls_codestpro3;
			}
			else
			{
				$ls_spgcuenta= "";
				$ls_denominacion="";
				$li_monto="";
				$ls_codestpro="";
			}
			$ls_dato="";
			switch ($li_i)
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
			
			$la_data1[$li_i]= array('c1'=>'','c2'=>$ls_codestpro,'c3'=>$ls_spgcuenta,'c4'=>$ls_denominacion,'c5'=>$li_monto,'c6'=>$ls_dato);
		}
	   if(!empty($la_data1))
	   {			        
		   uf_print_cuentas_spg($la_data1,$io_pdf); 														   
	   }
		//-----------------------------BIENES-----------------------------------				
		if($lb_valido)
		{
		
          if( ($ls_estcondat=="B") || ($ls_estcondat=="-") || ($ls_estcondat=="") )
          {
				$li_totrowdet=0;
				$lb_validobie=$io_report->uf_select_articulos($ls_codemp,$hidnumero,$hidtipcom);	
				if($lb_validobie)
				{													
							$li_totrowdet=$io_report->ds_art_dt->getRowCount("numordcom");
							for($li_s=1;$li_s<=$li_totrowdet;$li_s++)
							{
								  $ls_codart       = $io_report->ds_art_dt->data["codart"][$li_s];		
								  $ls_codigo       = substr($ls_codart,10,20);   									  				 
								  $ls_denominacion = $io_report->ds_art_dt->data["denart"][$li_s];				 
								  $ld_cantidad     = $io_report->ds_art_dt->data["canart"][$li_s];
								  $ld_precio       = $io_report->ds_art_dt->data["preuniart"][$li_s];
								  $ld_subtotal     = $ld_cantidad*$ld_precio;				
								  $ls_unidad       = $io_report->ds_art_dt->data["unidad"][$li_s];
								  $ld_total        = $io_report->ds_art_dt->data["montotart"][$li_s];										  
								  $ld_cargo        = $ld_total-$ld_subtotal;										 
								  
								  if($ls_unidad=="D")
								  {
									 $ls_unidad="Detal";
								  }
								  else
								  {
									 $ls_unidad="Mayor";
								  }										  
								  $ld_precio     = number_format($ld_precio,2,",",".");	
								  $ld_cantidad   = number_format($ld_cantidad,2,",",".");	
								  $ld_subtotal   = number_format($ld_subtotal,2,",",".");	
								  $ld_cargo      = number_format($ld_cargo,2,",",".");	
								  $ld_total      = number_format($ld_total,2,",",".");
																					
								  $la_data[$li_s]= array('codigo'=>$ls_codart,'denominacion'=>$ls_denominacion,'cantidad'=>$ld_cantidad,
								  'precio'=>$ld_precio,'unidad'=>$ls_unidad,'subtotal'=>$ld_subtotal,'cargos'=>$ld_cargo,'total'=>$ld_total);
							}														
							uf_print_detalle_bienes($la_data,$io_pdf); 
				      }						
                }
				//-------------------------------SERVICIOS-------------------------------------- 												
                if( ($ls_estcondat=="S") || ($ls_estcondat=="-") || ($ls_estcondat==""))
                {
							$li_totrowdetser=0;
							$lb_validoser=$io_report->uf_select_servicios($ls_codemp,$hidnumero,$hidtipcom);									
							if($lb_validoser)
							{										
										$li_totrowdetser=$io_report->ds_ser_dt->getRowCount("orden");	
										for($li_ser=1;$li_ser<=$li_totrowdetser;$li_ser++)
										{
											  $ls_codigo       = $io_report->ds_ser_dt->data["codser"][$li_ser];				  
											  $ls_denominacion = $io_report->ds_ser_dt->data["denser"][$li_ser];				 				  
											  $ld_cantidad     = $io_report->ds_ser_dt->data["canser"][$li_ser];				  
											  $ld_precio       = $io_report->ds_ser_dt->data["monuniser"][$li_ser];				  
											  $ld_subtotal     = $ld_cantidad*$ld_precio;				  				  				                 
											  $ld_total        = $io_report->ds_ser_dt->data["montotser"][$li_ser];
											  $ld_cargo        = $ld_total-$ld_subtotal;
											  
											  $ld_cantidad   = number_format($ld_cantidad,2,",",".");	
											  $ld_precio     = number_format($ld_precio,2,",",".");	 
											  $ld_subtotal   = number_format($ld_subtotal,2,",",".");	
											  $ld_cargo      = number_format($ld_cargo,2,",",".");	
											  $ld_total      = number_format($ld_total,2,",",".");								
											  
											  $la_dataser[$li_ser]= array('codigo'=>$ls_codigo,'denominacion'=>$ls_denominacion,'cantidad'=>$ld_cantidad,
											  'precio'=>$ld_precio,'subtotal'=>$ld_subtotal,'cargos'=>$ld_cargo,'total'=>$ld_total);
										}														
										uf_print_detalle_servicios($la_dataser,$io_pdf); 
							}
                }
				
			   $ld_monto = str_replace(".","",$ld_monto);
			   $ld_monto = str_replace(",",".",$ld_monto);	
			   uf_print_pie_cabecera($ld_monbas,$ld_moncar,$ld_monto,&$io_pdf);		
               $ls_tipoencabenzadosol = "Detalle Presupuestario";
			   $numalet->setNumero($ld_monto);						
			   $ls_monto= $numalet->letra();			  
			 
			  // $io_pdf->ezStartPageNumbers(588,710,7,'','',1); 
			   			       			  								
			   uf_print_encabezado_pie(number_format($ld_monto,2,",","."),$ls_proveedor,$ls_monto,
                                       $ls_lugcom,$ls_codmoneda,$ls_moneda,$ld_tasa,$ld_mondiv,$ls_estcondat,
									   $ls_pais,$ls_estado,$ls_municipio,$ls_parroquia,$ls_estcondat,$ls_codmoneda,
                                       &$io_pdf);
			    uf_print_cuadros_inferiores($ls_obsordcom,$ls_observacion,$ld_tasa,$li_totconv,&$io_pdf);
		}               										
		if($lb_valido) 
		{
			$io_pdf->ezStream(); 
		}
		else  
		{
			print("<script language=JavaScript>");
			print("alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print("close();");			  
			print("</script>");		
		}	
	}	
	unset($io_pdf);
	unset($io_report);
	unset($io_funciones);
?> 