<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//  REPORTE: Formato de salida  de Evluaci�n de Eficienia
	//  ORGANISMO: IPSFA
	//  MODIFICADO POR: MAR�A BEATRIZ UNDA         FECHA DE MODIFICACION : 15/04/2008
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
		//	    Arguments: as_titulo // T�tulo del reporte
		//    Description: funci�n que guarda la seguridad de quien gener� el reporte
		//	   Creado Por: Mar�a Beatriz Unda
		// Fecha Creaci�n: 15/04/08
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_srh;
		
		$ls_descripcion="Gener� el Reporte ".$as_titulo;
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("SRH","sigesp_srh_rpp_evaluacion_eficiencia.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   as_numsol // numero de la solicitud
		//	    		   ad_fecregsol // fecha de registro de la solicitud
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Funci�n que imprime los encabezados por p�gina
		// Fecha Creaci�n: 11/03/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();        
		$io_pdf->setStrokeColor(0,0,0);		
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],80,500,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(14,$as_titulo);
			
		$io_pdf->addText(650,550,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(650,560,8,date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	
	function print_cabecera_factor(&$io_pdf)
	{
		$io_pdf->ezSetDy(-1);
		$la_data[1]=array('seccion'=>'<b>F A C T O R E S    A    E V A L U A R</b>');
		$la_columnas=array('seccion'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>16, // Tamaño de Letras
						 'titleFontSize' => 18,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('seccion'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		
	}
	
	//------------------------------------------------------------------------------------------------------------------------------------
	function uf_print_aspecto ($aspecto,&$io_pdf)
	{
	  $io_pdf->ezSetDy(-20);
	    $la_data_titulo[1]=array('name1'=>$aspecto);
		$la_columnas=array('name1'=>$aspecto);
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('name1'=>array('justification'=>'center','width'=>660))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_titulo,$la_columnas,'',$la_config);
		
	
	}
	
	
	function uf_print_items($as_data,&$io_pdf)
	{   
	    		
		$la_columnas=array('name1'=>'',
		                   'name2'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('name1'=>array('justification'=>'left','width'=>560),
						               'name2'=>array('justification'=>'center','width'=>100))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($as_data,$la_columnas,'',$la_config);
		
	}
	
	function uf_print_total ($total,&$io_pdf)
	{
		//--------------------------------------------------------------------------------------------------
		//-----------------------totales---------------------------------------------------------------------
		$la_data_totales[1]=array('total1'=>'TOTAL','total2'=>$total);
		$la_columnas=array('total1'=>'','total2'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>600, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('total1'=>array('justification'=>'right','width'=>560),
						               'total2'=>array('justification'=>'center','width'=>100))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_totales,$la_columnas,'',$la_config);
		//-----------------------------------------------------------------------------------------------------------
	}
	
		//------------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($as_titulo,$adt_fecini,$adt_fecfin,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por concepto
		//	   Creado Por: Mar�a Beatriz Unda
		// Fecha Creaci�n: 15/04/08
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-1);
		$la_data[1]=array('periodo'=>'<b>PRERIODO A EVALUAR:              DESDE     </b>'.$adt_fecini.'<b>               HASTA    </b>'.$adt_fecfin);
		$la_columnas=array('periodo'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('periodo'=>array('justification'=>'left','width'=>660))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$li_tm=$io_pdf->getTextWidth(14,$as_titulo);
		$tm=400-($li_tm/2);
		$io_pdf->addText($tm,425,14,'<b>'.$as_titulo.'</b>'); // Agregar el t�tulo
		$io_pdf->ezSetDy(-50);

	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_comentarios($comen, $obs, $accion, &$io_pdf)
	      {
		 

		 //------------------------------------------------------------------------------------------
		$io_pdf->ezSetDy(-5);
		$la_data_6[1]=array('seccion_6'=>'<b>COMENTARIOS DEL SUPERVISOR</b>');
		$la_columnas=array('seccion_6'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>650, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('seccion_6'=>array('justification'=>'center','width'=>650))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_6,$la_columnas,'',$la_config);
		//----------------------------------------------------------------------------
		
		$la_data_7[1]=array('obs_sup'=>$comen);
		$la_columnas=array('obs_sup'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>650, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('obs_sup'=>array('justification'=>'left','width'=>650))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_7,$la_columnas,'',$la_config);				
	
   //------------------------------------------------------------------------------------------------------------------------
		//------------------------------------------------------------------------------------------------
		
		$io_pdf->ezSetDy(-30);
		$la_data_8[1]=array('aspecto1'=>'<b>ASPECTOS A MEJORAR</b>','aspecto2'=>'<b>ACCIONES</b>');
		$la_columnas=array('aspecto1'=>'','aspecto2'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>650, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('aspecto1'=>array('justification'=>'center','width'=>330),
						               'aspecto2'=>array('justification'=>'center','width'=>330))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_8,$la_columnas,'',$la_config);
		
				
		$la_data[1]=array('aspecto1'=>$obs,'aspecto2'=>$accion);
		$la_data[2]=array('aspecto1'=>'','aspecto2'=>'');
		
		
		$la_columnas=array('obs'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'left', // Orientación de la tablA
						 'cols'=>array('obs'=>array('justification'=>'left','width'=>330))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($obs,$la_columnas,'',$la_config);
		
		$io_pdf->ezSetY(389);
		$la_columnas=array('acc'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'yPos'=>200, // Orientación de la tabla
						 'xOrientation'=>'right', // Orientación de la tablA
						 'cols'=>array('acc'=>array('justification'=>'left','width'=>330))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($accion,$la_columnas,'',$la_config);
		  
		  
		  
		 $io_pdf->ezSetDy(-30);
		$la_data_E[1]=array('evaluado'=>'A SER LLENADO POR EL EVALUADO:');
		$la_columnas=array('evaluado'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>12, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('evaluado'=>array('justification'=>'left','width'=>660))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_E,$la_columnas,'',$la_config);
		//-------------------------------------------------------------------------
		$io_pdf->ezSetDy(-2);
		$la_data_Preg[1]=array('pregunta'=>'�Est� de acuerdo?','respuesta1'=>'Si:________','respuesta2'=>'No:________');
		$la_columnas=array('pregunta'=>'','respuesta1'=>'','respuesta2'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>12, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('pregunta'=>array('justification'=>'left','width'=>330),
						               'respuesta1'=>array('justification'=>'left','width'=>115),
						               'respuesta2'=>array('justification'=>'left','width'=>115))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_Preg,$la_columnas,'',$la_config);
		//--------------------------------------------------------------
		$io_pdf->ezSetDy(-15);
		$la_data_Comentario[1]=array('comentario'=>'Explique');
		$la_data_Comentario[2]=array('comentario'=>'');
		$la_data_Comentario[3]=array('comentario'=>'');
		$la_data_Comentario[4]=array('comentario'=>'');
		$la_data_Comentario[5]=array('comentario'=>'');
		$la_data_Comentario[6]=array('comentario'=>'');
	
		$la_columnas=array('comentario'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('comentario'=>array('justification'=>'center','width'=>660))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_Comentario,$la_columnas,'',$la_config);
			
	}

	//------------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle2($adt_fecini,$adt_fecfin,$as_nombre,$as_cedper,$as_deasicar,$as_codasicar,$as_desuniadm,$ls_titulo,$as_codnom,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por concepto
		//	   Creado Por: Mar�a Beatriz Unda
		// Fecha Creaci�n: 15/04/08 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_pdf->ezSetDy(-2);
		$la_data[1]=array('nombre'=>'APELLIDOS  Y NOMBRE :'.$as_nombre);
		$la_columnas=array('nombre'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>660))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'DATOS DEL '.$ls_titulo,$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->ezSetDy(-1);
		$la_data[1]=array('cedula'=>' CEDULA DE IDENTIDAD :'.$as_cedper,'codnom'=>' C�DIGO N�MINA: '.$as_codnom);
		$la_columnas=array('cedula'=>'','codnom'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('cedula'=>array('justification'=>'left','width'=>460),
						 			   'codnom'=>array('justification'=>'left','width'=>200))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->ezSetDy(-1);
		$la_data[1]=array('titulo'=>' TITULO DEL CARGO :'.$as_deasicar,'nomina'=>' CODIGO DEL CARGO :'.$as_codasicar);
		$la_columnas=array('titulo'=>'','nomina'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>460), // Justificaci�n y ancho de la columna
						               'nomina'=>array('justification'=>'left','width'=>200))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->ezSetDy(-1);
		$la_data[1]=array('unidad'=>' UBICACION ADMINISTRATIVA:  '.$as_desuniadm);
		$la_columnas=array('unidad'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('unidad'=>array('justification'=>'left','width'=>660))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}// end function uf_print_detalle
   //------------------------------------------------------------------------------------------------------------------------
   function uf_print_firmas($total, $rango, &$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por concepto
		//	   Creado Por: Mar�a Beatriz Unda
		// Fecha Creaci�n: 15/04/08
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_data_F[1]=array('firma'=>' NOMBRES APELLIDOS Y FIRMAS:');
		$la_columnas=array('firma'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>12, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('firma'=>array('justification'=>'left','width'=>660))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_F,$la_columnas,'',$la_config);
		//------------------------------------------------------------------------------------------------
		$la_data[1]=array('firma1'=>'EVALUADOR','firma2'=>'SUPERVISOR DEL EVALUADOR', 'firma3'=>'EVALUADO');
		$la_data[2]=array('firma1'=>'','firma2'=>'','firma3'=>'');
		$la_data[3]=array('firma1'=>'','firma2'=>'','firma3'=>'');
		$la_data[4]=array('firma1'=>'','firma2'=>'','firma3'=>'');
		
		$la_columnas=array('firma1'=>'','firma2'=>'','firma3'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('firma1'=>array('justification'=>'center','width'=>220),
						 			   'firma2'=>array('justification'=>'center','width'=>220),
						               'firma3'=>array('justification'=>'center','width'=>220))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		//----------------------------------------------------------------------------------------------------------
		 $io_pdf->ezSetDy(-30);
		$la_data_E[1]=array('uso'=>'<b>PARA USO DE LA OFICINA DE PERSONAL</b>');
		$la_columnas=array('uso'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>12, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('uso'=>array('justification'=>'left','width'=>660))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data_E,$la_columnas,'',$la_config);
		
				
		$io_pdf->ezSetDy(-5);
		$la_data[1]=array('resultado1'=>'RESULTADO DE LA EVALUACI�N');
		
		
		$la_columnas=array('resultado1'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('resultado1'=>array('justification'=>'center','width'=>660))); // Justificaci�n y ancho de la columna
						
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		
		$la_data_1[1]=array('puntaje1'=>'PUNTAJE OBTENIDO','puntaje2'=>'RANGO ACTUACI�N','puntaje3'=>'DECISI�N');
		$la_data_1[2]=array('puntaje1'=>$total, 'puntaje2'=>$rango,'puntaje3'=>'' );
		$la_data_1[3]=array('puntaje1'=>'', 'puntaje2'=>'','puntaje3'=>'' );
	
		
		$la_columnas=array('puntaje1'=>'','puntaje2'=>'','puntaje3'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('puntaje1'=>array('justification'=>'center','width'=>140),
						 			   'puntaje2'=>array('justification'=>'center','width'=>260),
						               'puntaje3'=>array('justification'=>'center','width'=>260))); // Justificaci�n y ancho de la columna
		
		$io_pdf->ezTable($la_data_1,$la_columnas,'',$la_config);
		
		
		$io_pdf->ezSetDy(-40);
		$la_data_2[1]=array('firma1'=>'FIRMA DE RECURSOS HUMANOS','firma2'=>'FECHA');
		$la_data_2[2]=array('firma1'=>'', 'firma2'=>'');
		$la_data_2[3]=array('firma1'=>'', 'firma2'=>'');
		$la_data_2[4]=array('firma1'=>'', 'firma2'=>'');
	
		
		$la_columnas=array('firma1'=>'','firma2'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tablA
						 'cols'=>array('firma1'=>array('justification'=>'center','width'=>330),
						 			   'firma2'=>array('justification'=>'center','width'=>330))); // Justificaci�n y ancho de la columna
		
		$io_pdf->ezTable($la_data_2,$la_columnas,'',$la_config);
	}
	
	//------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha = new class_fecha();	
	require_once("class_folder/sigesp_srh_class_report.php");
	$io_report  = new sigesp_srh_class_report('../../');
	require_once("../class_folder/utilidades/class_funciones_srh.php");
	$io_fun_srh=new class_funciones_srh("../../");	
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];	
	require_once("../../shared/class_folder/sigesp_include.php");
	$io_include=new sigesp_include();
	$io_conexion=$io_include->uf_conectar();
	require_once("../../shared/class_folder/class_sql.php");
	$io_sql=new class_sql($io_conexion);	
	//----------------------------------------------------  Par�metros del encabezado  --
	 
	 
	 $ls_nroeval=$_GET["nroeval"];
	 $ldt_fecini=$_GET["fecini"];
	 $ldt_fecfin=$_GET["fecfin"];
	 $ls_titulo=$_GET["titulo"];
	 //--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{       
		$lb_valido=$io_report->uf_select_personas_evaluacion_eficiencia($ls_nroeval);		
	}
	if($lb_valido==false) // Existe alg�n error � no hay registros
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
			ini_set('display_errors','off');
			$io_pdf = new Cezpdf("LETTER","landscape");
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');
			$io_pdf->ezSetCmMargins(5,4,3,3);
			$io_pdf->ezStartPageNumbers(406,30,10,'','',1);//Insertar el n�mero de p�gina.
		    $li_total=$io_report->DS->getRowCount("nroeval");
		    $li_aux=0;
			uf_print_encabezado_pagina($ls_titulo,&$io_pdf);
		    uf_print_detalle($ls_titulo,$ldt_fecini,$ldt_fecfin,$io_pdf);	   	
            
			for ($li_o=1;$li_o<=$li_total;$li_o++)
			{			 
			  $thisPageNum=$io_pdf->ezPageCount;			
			  $ls_tipo=$io_report->DS->getValue("tipo",$li_o);			
			  $ls_codper=$io_report->DS->getValue("codper",$li_o); 				  	 				
			  $lb_valido=$io_report->uf_select_registro_persona_eval_eficiencia($ls_nroeval,$ls_codper,$ls_tipo);
			  $li_total2=$io_report->ds_detalle2->getRowCount("cedper");
			
			  for($li_d=1;$li_d<=$li_total2;$li_d++)
			  {
			        $ls_codnom=$io_report->ds_detalle2->getValue("codnom",$li_d);
					$ls_codper2=$io_report->ds_detalle2->getValue("codper",$li_d);
					$ls_cedula=$io_report->ds_detalle2->getValue("cedper",$li_d); 	
			        $ls_cedula=number_format($ls_cedula,0,",",".");	
					$ls_codasicar=$io_report->ds_detalle2->getValue("codasicar",$li_d);
					$ls_deasicar=$io_report->ds_detalle2->getValue("denasicar",$li_d);
					$ls_codcar=$io_report->ds_detalle2->getValue("codcargo",$li_d);
					$ls_descar=$io_report->ds_detalle2->getValue("descargo",$li_d);	
					
					
				
					if ($ls_deasicar=="Sin Asignaci�n de Cargo")
				    {
					  	$ls_codcargo=$ls_codcar;
					  	$ls_descargo = trim ($ls_descar);
				    }
				   if ($ls_descar=="Sin Cargo")
				    {
						$ls_codcargo=$ls_codasicar;
					  	$ls_descargo = trim ($ls_deasicar);
				    }
							
					$ls_desuniadm=$io_report->ds_detalle2->getValue("desuniadm",$li_d);
					$ls_nomper=$io_report->ds_detalle2->getValue("nombre",$li_d);
					switch ($ls_tipo){
						case 'E':
							$ls_titulo='EVALUADOR';
						break;
						case 'P':
							$ls_titulo='EVALUADO';
						break;				
					}					
				
			    uf_print_detalle2($ldt_fecini, $ldt_fecfin, $ls_nomper, $ls_cedula, $ls_descargo, $ls_codcargo, $ls_desuniadm,$ls_titulo,$ls_codnom,$io_pdf);					
			   }				  	
			 }			  
		   $io_pdf->ezNewPage(); // Insertar una nueva p�gina
		   $rs_data="";
		   $lb_valido=$io_report->uf_select_factor_evaluacion_eficiencia($ls_nroeval,$rs_data);   
		   print_cabecera_factor(&$io_pdf);
		   
		       $val = false;
		   	   $aux_aspecto1=0;
			   $aux_aspecto = 0;	 
		   while ($row=$io_sql->fetch_row($rs_data))
		   {
		   
		   if (($aux_aspecto1 != $row["codasp"]) && ($val))
		       { 
			      uf_print_total ($total,&$io_pdf);
				  $total= 0;
				}	
		   
		     if ($aux_aspecto != $row["codasp"])
		       {  
			    $denasp=$row["denasp"];
				$codasp=$row["codasp"];
				$aux_aspecto1 =$row["codasp"];
				uf_print_aspecto ($denasp,&$io_pdf);
				}
					
				$aux_aspecto = $row["codasp"];
				if ($aux_aspecto == $row["codasp"])
				{ 
				   
				   $denite=$row["denite"];	
  				   $punobt=$row["puntos"];
				   if ($punobt!=0)
				   {
				   		$marca='X';
				   } 
				   else
				   {
				   		$marca='';
				   }
				   				
				   $total= $total + $punobt;			
				   $val = true;	
			       $ls_data[$li_t]=array('name1'=>$denite,'name2'=>$marca);			
				   uf_print_items($ls_data,&$io_pdf);	
 		        }			  
		 }
		 if (($aux_aspecto1 != $row["codasp"]) && ($val))
		   {  
		 		uf_print_total ($total,&$io_pdf);
			}	
			
			$io_pdf->ezNewPage(); // Insertar una nueva p�gina
			
			 $lb_valido=$io_report->uf_select_dt_evaluacion_eficiencia($ls_nroeval,$rs_data); 
			
			
			while ($row=$io_sql->fetch_row($rs_data))
		   {
		     $comensup=$row["comen_sup"];	
			 $obs=$row["observacion"];	
			 $accion=$row["acciones"];	
			 $ELEMENTOS=explode("\\n",$obs);
				
				
                   for($i=0;$i<count($ELEMENTOS);$i++) 
                   {
                   	 $valor=$ELEMENTOS[$i];
                     $ls_data_o[$i]=array('obs'=>$valor);
                   }
				   
			$ELEMENTOS2=explode("\\n",$accion);
				
				
                   for($j=0;$j<count($ELEMENTOS2);$j++) 
                   {
                   	 $valor2=$ELEMENTOS2[$j];
                     $ls_data_a[$j]=array('acc'=>$valor2);
                   }
			 		 	
			 uf_print_comentarios ($comensup, $ls_data_o, $ls_data_a,&$io_pdf);
			 $total=$row["total"];
			 $rango_act = $row["actuacion"]; 
			 
			  
			}
			  
				
               //--------------------------------------------------------------- 
			$io_pdf->ezNewPage(); // Insertar una nueva p�gina
			uf_print_firmas ($total,$rango_act,&$io_pdf);
			//---------------------------------------------------------------
			  
				
               //--------------------------------------------------------------- 
			
			for($li_i=1;$li_i<=$li_total;$li_i++)
			{	
			  $io_pdf->ezStopPageNumbers(1,1);		
	 	      $io_pdf->ezStream(); // Mo
	        }  
		       
     }

?>
