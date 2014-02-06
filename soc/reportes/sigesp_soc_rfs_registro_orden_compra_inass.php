<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Formato de salida  de la Orden de Compra
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
	function uf_print_encabezado_pagina($as_estcondat,$as_numordcom,$ad_fecordcom,$as_coduniadm,$as_denuniadm, $as_codfuefin,
	                                   $as_denfuefin,$as_codigo,$as_nombre,$as_rifpro,$as_diaplacom,$as_dirpro,
									   $ls_forpagcom,$as_telfpro,$as_lugcom,$as_fechent,$as_estlugcom,$as_obscom,$as_numcoti,&$io_pdf)
	{                              
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_estcondat  ---> tipo de la orden de compra
		//	    		   as_numordcom ---> numero de la orden de compra
		//	    		   ad_fecordcom ---> fecha de registro de la orden de compra
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 21/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		/*$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],20,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo*/

		//$io_pdf->addJpegFromFile('../../shared/imagebank/logo.jpg',20,719,400,50); // Agregar Logo
		$io_pdf->Rectangle(485,682,101,89);   // Rectangulo del número y fecha
		$io_pdf->line(485,745,585,745);  // linea horizontal
		$io_pdf->addText(487,760,9,"<b>NÚMERO</b>");  
		$io_pdf->addText(487,750,9,$as_numordcom);
		$io_pdf->line(485,735,585,735); // linea horizontal ABAJO FECHA
		$io_pdf->addText(520,737,9,"<b>FECHA</b>");
		$io_pdf->line(485,720,585,720); // linea horizontal ABAJO DIAS MES AÑO
		$io_pdf->line(517,681,517,735);  // linea vertical DIA
		$io_pdf->addText(495,724,9,"<b>D</b>");
		$io_pdf->line(550,681,550,735);  // linea vertical MES
		$io_pdf->addText(530,724,9,"<b>M</b>");
		$io_pdf->addText(565,724,9,"<b>A</b>");
		if($as_estcondat=="B") 
        {
             $ls_titulo="ORDEN DE COMPRA";	
			 $ls_titulo_grid="Bienes";
        }
        else
        {
             $ls_titulo="ORDEN DE SERVICIO";
			 $ls_titulo_grid="Servicios";
        }
		
		$li_tm=$io_pdf->getTextWidth(4,$ls_titulo);
		$tm=200-($li_tm/2);
		$io_pdf->addText($tm,685,14,$ls_titulo); // Agregar el título
		$io_pdf->addText(493,698,9,date("d"));
		$io_pdf->addText(530,698,9,date("m"));  // Agregar la Fecha
		$io_pdf->addText(560,698,9,date("Y")); 
		$io_pdf->Rectangle(16,681,570,90); // rectangulo superior
		//$io_pdf->line(15,235,585,235); 
		$io_pdf->setStrokeColor(0,0,0);		
	
	    $io_pdf->Rectangle(16,585,570,96);  // recatngulo datos del proveeedor
		$io_pdf->addText(90,672,9,"<b>DATOS DEL PROVEEDOR</b>"); 
		$io_pdf->ezSetY(670);
		$la_data[2]=array('columna1'=>'<b>NOMBRE:</b> ',
		                 'columna2'=>$as_nombre);
		$la_columna=array('columna1'=>'','columna2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>280, // Ancho de la tabla
						 'maxWidth'=>280, // Ancho Máximo de la tabla
						 'xOrientation'=>'left', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>50), // Justificación y ancho de la columna
						 			   'columna2'=>array('justification'=>'left','width'=>230))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[2]=array('columna1'=>'<b>DIRECCIÓN:</b> ',
		                 'columna2'=>$as_dirpro);
		$la_columna=array('columna1'=>'','columna2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>280, // Ancho de la tabla
						 'maxWidth'=>280, // Ancho Máximo de la tabla
						 'xOrientation'=>'left', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'columna2'=>array('justification'=>'left','width'=>220))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		/*
		
	    $io_pdf->addText(18,655,9,"<b>NOMBRE: </b>".$as_nombre);
		$io_pdf->addText(18,640,9,"<b>DIRECCION FISCAL: </b>");  
        $ls_textdirpro=$io_pdf->addTextWrap(110,640,210,9,$as_dirpro);
		$io_pdf->addText(20,630,9,$ls_textdirpro); */
		$io_pdf->setStrokeColor(0,0,0);
		//$io_pdf->line(16,670,320,670); //linea horizontal
		$io_pdf->line(320,586,320,681);  // LINEA VERTICAL
		$io_pdf->addText(25,605,8,"<b>RIF: </b>".$as_rifpro); 
		$io_pdf->addText(25,590,8,"<b>TELÉFONO: </b>".$as_telfpro); 
		////////////////////// cuadro de la derecha////////////////// 
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->addText(330,672,9,"<b>SOLICITUD DE COTIZACIÓN Nº: </b>"); 
		$io_pdf->addText(330,660,9,$as_numcoti);
		$io_pdf->addText(500,672,9,"<b>FECHA: </b>");  
		$io_pdf->addText(500,660,9,$ad_fecordcom); 
		$io_pdf->line(320,655,585,655);  //linea horizontal      
		$io_pdf->line(320,635,585,635);  //linea horizontal                  
		$io_pdf->line(320,615,585,615);  //linea horizontal   
		$io_pdf->line(480,654,480,681);  // linea vertical 
	    $io_pdf->addText(330,643,9,"<b>ANALISTA: </b>");  
		$io_pdf->addText(330,620,9,"<b>CÓDIGO PROVEEDOR INTERNO: </b>");  
		$io_pdf->line(455,586,455,615);  // linea vertical
		$io_pdf->addText(350,603,9,"<b>CÓDIGO OEEI: </b>"); 
		$io_pdf->addText(490,603,9,"<b>CÓDIGO NIC: </b>"); 
		
				
		// cuadro inferior
 /*   	$io_pdf->Rectangle(15,192,571,65);   // Cuadro de las observaciones
	    $io_pdf->addText(19,245,9,"<b>OBSERVACIONES: </b>");
		$ls_textoobscom=$io_pdf->addTextWrap(19,285,550,9,$as_obscom);
		$io_pdf->addText(19,235,9,$ls_textoobscom);	*/
		$io_pdf->Rectangle(15,175,570,75); // primer rectángulo inferior
		$io_pdf->line(15,235,585,235);   //linea horizontal debajo del primer cuadro
		$io_pdf->addText(40,240,9,"<b>FIANZA DE FIEL CUMPLIMIENTO</b>"); 
		$io_pdf->line(201,176,201,250);  // LINEA VERTICAL
		$io_pdf->addText(17,225,8,"Al aprobarse esta Orden de Compra se exigirá al"); 
		$io_pdf->addText(17,215,8,"beneficiario fianza del fiel cumplimiento equivalente"); 
		$io_pdf->addText(17,205,8,"al ______% del monto de la Orden otorgada por el"); 
		$io_pdf->addText(17,195,8,"banco o compañia de seguros, notariada y vigente");
		$io_pdf->addText(17,185,8,"hasta la total recepción de la mercancía."); 
		$io_pdf->addText(255,240,9,"<b>CLÁUSULA PENAL</b>"); 
		$io_pdf->line(401,176,401,250);  // LINEA VERTICAL
		$io_pdf->addText(203,225,8,"Queda establecida la Cláusula Penal según la cual el"); 
		$io_pdf->addText(203,215,8,"proveedor pagará el _________% sobre el monto de la");
		$io_pdf->addText(203,205,8,"mercancía respectiva por cada día hábil del retardo en");
		$io_pdf->addText(203,195,8,"la entrega.");
		$io_pdf->addText(445,240,9,"<b>CLÁUSULA ESPECIAL</b>"); 
		$io_pdf->addText(402,225,8,"El INASS se reserva el derecho de anular unilateral"); 
		$io_pdf->addText(403,215,8,"mente la presente Orden de Compra sin"); 
		$io_pdf->addText(403,205,8,"indemnización alguna."); 
		$io_pdf->addText(260,165,9,"<b>FIRMA RESPONSABLE</b>"); 
        $io_pdf->Rectangle(15,95,570,80); 
		$io_pdf->line(15,160,585,160);  //linea horizontal debajo firma
		$io_pdf->line(15,145,585,145);	//HORIZONTAL	
		$io_pdf->addText(45,150,7,"<b>JEFE DE COMPRAS</b>"); // Agregar el título
		$io_pdf->line(145,95,145,160);	//VERTICAL	
		$io_pdf->addText(160,150,7,"<b>GERENTE DE PRESUPUESTO</b>"); // Agregar el título
		$io_pdf->line(285,95,285,160);	//VERTICAL		
		$io_pdf->addText(305,150,7,"<b>GERENTE DE ADMINISTRACIÓN</b>"); // Agregar el título
		$io_pdf->line(430,95,430,160);	//VERTICAL	
		$io_pdf->addText(477,150,7,"<b>PRESIDENTE(A)</b>"); 	
		$io_pdf->addText(20,85,7,"CALLE LA IGLESIA CON CALLE LAS FLORES, URB. SABANA GRANDE. EDF.INAGER."); 	
		$io_pdf->addText(20,75,7,"ZONA POSTAL 1050."); 
        $io_pdf->addText(20,65,7,"TELEFONOS: 762.89.31 AL 34.");
		//$io_pdf->ezSetDy(-18);
		
		///////////////////////////////////////////////tabla4//////////////////////////////////////////////////////////////////////////////
		if ($as_estlugcom==0)
		 {
		  $as_lugar= "Nacional"; 
		 }
		else
		 {
		   $as_lugar= "Extranjero"; 
		 }
		 $io_pdf->Rectangle(16,565,570,20); // rectangulo del lugar
		 $io_pdf->addText(18,570,9,"<b>LUGAR DE ENTREGA: </b>".$as_lugar); 
		 $io_pdf->Rectangle(16,540,570,25); // rectangulo del lugar
		 $io_pdf->addText(18,550,9,"<b>TIEMPO DE ENTREGA: </b>".$as_fechent); 
		 $io_pdf->line(300,540,300,565);  // linea vertical
		 $io_pdf->addText(303,550,9,"<b>CONDICIONES DE PAGO: </b>".$ls_forpagcom);
		
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		// cuadro inferior
	


		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------
	 function uf_print_requision ($as_numsol,$as_denuniadm_req, &$io_pdf)
	 {
	    $la_datatit[1]=array('columna1'=>'<b>REQUISICION</b>',
		                     'columna2'=>'<b>DEPARTAMENTO</b>');
		$la_columnas=array('columna1'=>'', 'columna2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'center','width'=>120),
						               'columna2'=>array('justification'=>'center','width'=>450))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('columna1'=>$as_numsol,
		                  'columna2'=>$as_denuniadm_req);
		$la_columna=array('columna1'=>'','columna2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la columna
						 			   'columna2'=>array('justification'=>'left','width'=>450))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	 }
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
		//$io_pdf->ezSetCmMargins(8.8,10,3,3); // Configuración de los margenes en centímetros
		global $ls_estmodest, $ls_bolivares;
		if($ls_estmodest==1)
		{
			$ls_titulo_grid="Bienes";
		}
		else
		{
			$ls_titulo_grid="Servicios";
		}
		//$io_pdf->ezSetDy(17);
		$io_pdf->ezSetY(542);
		$la_datatit[1]=array('columna1'=>'<b>REGLÓN</b>',
		                       'columna2'=>'<b>ARTICULOS</b>',
							   'columna3'=>'<b>UNIDAD DE MEDIDA</b>',
						   	   'columna4'=>'<b>CANT.</b>',						   
						  	   'columna5'=>'<b>PRECIO UNITARIO </b>',
						   	   'columna6'=>'<b>SUB TOTAL </b>',
							   'columna7'=>'<b>CARGO</b>',
							   'columna8'=>'<b>TOTAL</b>');
		$la_columnas=array('columna1'=>'', 'columna2'=>'','columna3'=>'','columna4'=>'','columna5'=>'',
		                   'columna6'=>'','columna7'=>'','columna8'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'center','width'=>50),
						               'columna2'=>array('justification'=>'center','width'=>152),
									   'columna3'=>array('justification'=>'center','width'=>66),
									   'columna4'=>array('justification'=>'center','width'=>50),
									   'columna5'=>array('justification'=>'center','width'=>67),
									   'columna6'=>array('justification'=>'center','width'=>63),
									   'columna7'=>array('justification'=>'center','width'=>60),
									   'columna8'=>array('justification'=>'center','width'=>63))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columnas=array('numero'=>'',
		                   'codigo'=>'',
						   'presentacion'=>'',
						   'cantidad'=>'',						   
						   'cosuni'=>'',
						   'baseimp'=>'',
						   'cargo'=>'',
						   'montot'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('numero'=>array('justification'=>'center','width'=>50),
						               'codigo'=>array('justification'=>'left','width'=>152), // Justificación y ancho de la columna	 			   
									   'presentacion'=>array('justification'=>'center','width'=>66),
						 			   'cantidad'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'cosuni'=>array('justification'=>'center','width'=>67), // Justificación y ancho de la columna
						 			   'baseimp'=>array('justification'=>'right','width'=>63),
									   'cargo'=>array('justification'=>'right','width'=>60),
									   'montot'=>array('justification'=>'right','width'=>63))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
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
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera_total($li_montot,&$io_pdf)
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
		$io_pdf->ezSetDy(-2);	
		$la_data[1]=array('titulo'=>'<b>Total</b>','contenido'=>'<b>'.$li_montot.'</b>');
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
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
	}
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	/*function uf_print_piecabeceramonto_bsf($li_montotaux,&$io_pdf)
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
*/
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_print_observaciones($as_obscom,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		    Acess: private 
		//	    Arguments: li_montotaux ---> Total de la Orden Bs.F.
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: Función que imprime la observación
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 06/05/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->setStrokeColor(0,0,0);
		//$io_pdf->Rectangle(15,250,570,60); 
		$io_pdf->ezSetDy(-15);
	    $la_data[3]=array('obs'=>'<b>OBSERVACIONES:</b> '.$as_obscom);
		$la_columna=array('obs'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>305,
						 'cols'=>array('obs'=>array('justification'=>'left','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
/*		unset($la_data);
		unset($la_columna);
		unset($la_config);*/
	}
	
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
			$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(8.8,8,3,3); // Configuración de los margenes en centímetros
			$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página
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
				$ls_numcoti=$row["numanacot"];
				$ls_codpro=$row["cod_pro"];
				$ls_nompro=$row["nompro"];
				$ls_rifpro=$row["rifpro"];
				$ls_dirpro=$row["dirpro"];
				$ls_telfpro=$row["telpro"];
				$ls_lugcom=$row["lugentdir"];
				$ls_estlugcom=$row["estlugcom"];
				$ls_fecent=$row["fecent"];
				$ld_fecordcom=$row["fecordcom"];
				$ls_obscom=$row["obscom"];
				$ld_monsubtot=$row["monsubtot"];
				$ld_monimp=$row["monimp"];
				$ld_montot=$row["montot"];
				if($ls_tiporeporte==0)
				{
					$ld_montotaux=$row["montotaux"];
					$ld_montotaux=number_format($ld_montotaux,2,",",".");
				}
				$numalet->setNumero($ld_montot);
				$ls_monto= $numalet->letra();
				//$ld_montot=number_format($ld_montot,2,",",".");
				$ld_monsubtot=number_format($ld_monsubtot,2,",",".");
				$ld_monimp=number_format($ld_monimp,2,",",".");
				$ld_fecordcom=$io_funciones->uf_convertirfecmostrar($ld_fecordcom);				
		        $ls_fecent=$io_funciones->uf_convertirfecmostrar($ls_fecent);
				uf_print_encabezado_pagina($ls_estcondat,$ls_numordcom,$ld_fecordcom,$ls_coduniadm,$ls_denuniadm,
				                           $ls_codfuefin,$ls_denfuefin,$ls_codpro,$ls_nompro,$ls_rifpro,
										   $ls_diaplacom,$ls_dirpro,$ls_forpagcom,$ls_telfpro,$ls_lugcom,$ls_fecent,
										   $ls_estlugcom,$ls_obscom,$ls_numcoti,&$io_pdf);
				/////////////////////////////datos de la requisiciòn////////////////////////////////////////////
				$ls_codemp = $_SESSION["la_empresa"];
			   $lb_validosep = $io_report->uf_select_soc_sep($ls_codemp,$ls_numordcom,$ls_estcondat);	
			   if ($lb_validosep)
				  {										
					$li_totrow = $io_report->ds_soc_sep->getRowCount("numordcom");							
					if ($li_totrow>0)
						{
						  $ls_numsep   		   = $io_report->ds_soc_sep->data["numsol"][1];  											  
						  $ls_denunadm 		   = $io_report->ds_soc_sep->data["denuniadm"][1];  											  
						}														
					uf_print_requision ($ls_numsol,$ls_denuniadm_req, &$io_pdf);
				  } 
				/*$ls_req=$io_report->uf_select_soc_sep($ls_empresa,$ls_numordcom,$ls_estcondat);
				
				 if ($row_1=$io_sql->fetch_row($ls_req))
				  {
				    $ls_numsol=$row_1["numsol"];
					$ls_denuniadm_req=$row_1["denuniadm"];
					uf_print_requision ($ls_numsol,$ls_denuniadm_req, &$io_pdf);
				  }
				  else
				   {
				     $ls_numsol="";
					 $ls_denuniadm_req="";
					 //uf_print_requision ($ls_numsol,$ls_denuniadm_req, &$io_pdf);
				   }*/
				/////////////////////////////////////////////////////////////////////////////////////////////////
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
						
						if ($ls_estcondat=="B") 
						{
							$ls_unidad=$row["unidad"];
							$ls_denunidadmed=$row["denunimed"];	
						}
						else
						{
							$ls_unidad="";
							$ls_denunidadmed="";
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
						
						$ls_descuento=$io_report->uf_select_descuentos($ls_numordcom,$ls_estcondat);
						$descuento=0;	
						if($row_1=$io_sql->fetch_row($ls_descuento))
						{
						  $descuento=$row["monto"];						 
						}
						else
						{
						  $descuento;					  
						}	
						$articulo= $ls_codartser. $ls_denartser;
						$la_data[$li_i]=array('codigo'=>$articulo,'cantidad'=>$li_cantartser,
											  'unidad'=>$ls_unidad,'cosuni'=>$ld_preartser,'baseimp'=>$ld_subtotartser,
											  'cargo'=>$ld_carartser,'montot'=>$ld_totartser, 'numero'=>$li_i, 
											  'presentacion'=>$ls_denunidadmed,'descuento'=>$descuento);
					}

					uf_print_detalle($la_data,&$io_pdf);
					unset($la_data);
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
								$ls_codestpro1 = substr($ls_codestpro1,-$_SESSION["la_empresa"]["loncodestpro1"]);
								$ls_codestpro2 = substr($ls_codestpro2,-$_SESSION["la_empresa"]["loncodestpro2"]);
								$ls_codestpro3 = substr($ls_codestpro3,-$_SESSION["la_empresa"]["loncodestpro3"]);
								$ls_codestpro4 = substr($ls_codestpro3,-$_SESSION["la_empresa"]["loncodestpro4"]);
								$ls_codestpro5 = substr($ls_codestpro3,-$_SESSION["la_empresa"]["loncodestpro5"]);
								if($ls_estmodest==1)
								{
									$ls_codestpro=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3;
								}
								else
								{
									$ls_codestpro=substr($ls_codestpro1,-2)."-".substr($ls_codestpro2,-2)."-".substr($ls_codestpro3,-2)."-".substr($ls_codestpro4,-2)."-".substr($ls_codestpro5,-2);
								}
								$ls_spg_cuenta=substr($ls_spg_cuenta,0,3)."-".substr($ls_spg_cuenta,3,2)."-".substr($ls_spg_cuenta,5,2)."-".substr($ls_spg_cuenta,7,2);
								$la_data[$li_s]=array('codestpro'=>$ls_codestpro,'denominacion'=>$ls_dencuenta,
													  'cuenta'=>$ls_spg_cuenta,'monto'=>$ld_monto);
							}
							//////////////////////////para calcular el monto total si tiene o no descuentos/////////////////
							$ls_monto_menos_desc=$ld_montot-$descuento;
							$ld_montot=number_format($ld_montot,2,",",".");
							$ls_monto_menos_desc=number_format($ls_monto_menos_desc,2,",",".");	
							/////////////////////////////////////////////////////////////////////////////////////////////////
							//uf_print_piecabecera($ld_monsubtot,$ld_monimp,$ls_monto_menos_desc,$descuento,&$io_pdf);	
							uf_print_detalle_cuentas($la_data,&$io_pdf);
							uf_print_piecabecera_total($ld_montot,&$io_pdf);
							unset($la_data);
						    
						}
				     }
			      }
		       }
	     	}
		}
		uf_print_observaciones($ls_obscom,&$io_pdf);
		//uf_monto_en_letras ($ls_monto,&$io_pdf);
			
		 
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