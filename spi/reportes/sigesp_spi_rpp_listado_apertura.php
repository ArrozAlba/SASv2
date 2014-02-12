<?php
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
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],38,730,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		
		$io_pdf->addText(500,730,9,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(500,720,9,date("h:i a")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina2($as_titulo,$as_titulo1,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina2
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,40,578,40); 
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],38,730,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(10,$as_titulo1);
	    $tm=300-($li_tm/2);
	    $io_pdf->addText($tm,715,10,$as_titulo1); // Agregar el título
		
		$io_pdf->addText(500,760,9,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(500,750,9,date("h:i a")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina2

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_detalle($io_encabezado,&$io_pdf)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
        //$io_pdf->ezSetDy(-0.5);
		$la_data=array(array('cuenta'=>'<b>Cuenta</b>','denominacion'=>'<b>Denominación</b>','descripcion'=>'<b>Descripción</b>',
		                     'documento'=>'<b>Documento</b>','monto'=>'<b>Monto</b>'));
		$la_columnas=array('cuenta'=>'','denominacion'=>'','descripcion'=>'','documento'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho Máximo de la tabla
						 'xPos'=>302, // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>70), // Justificación y ancho  
						 			   'denominacion'=>array('justification'=>'center','width'=>140), // Justificación y ancho 
						 			   'descripcion'=>array('justification'=>'center','width'=>115), // Justificación y ancho  
						 			   'documento'=>array('justification'=>'center','width'=>90), // Justificación y ancho 
									   'monto'=>array('justification'=>'center','width'=>115))); // Justificación y ancho  
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_cabecera_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulo="Monto Bs.F.";
		}
		else
		{
			$ls_titulo="Monto Bs.";
		}
		//print_r($la_data);
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho Máximo de la tabla
						 'xPos'=>302, // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la 
						 			   'denominacion'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la 
						 			   'descripcion'=>array('justification'=>'center','width'=>115), // Justificación y ancho de la 
						 			   'documento'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la 
									   'monto'=>array('justification'=>'right','width'=>115))); // Justificación y ancho de la 
		$la_columnas=array('cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Denominación</b>',
						   'descripcion'=>'<b>Descripción</b>',
						   'documento'=>'<b>Documento</b>',
						   'monto'=>'<b>Monto</b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_total,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por : Ing. Yesenia Moreno
		// Fecha Creación : 18/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('total'=>'<b>Total</b>','monto'=>$ad_total));
		$la_columna=array('total'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'fontSize' => 9, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho Máximo de la tabla
						 'xPos'=>302, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>415), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>115))); // Justificación y ancho de la 

		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------------
	 function uf_print_cabecera_estructura( $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,
		                        $ls_denestpro1,$ls_denestpro2,$ls_denestpro3,$ls_denestpro4,$ls_denestpro5,$io_pdf)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_cabecera
	//		   Access: private 
	//	    Arguments: as_programatica // programatica del comprobante
	//	    		   as_denestpro5 // denominacion de la programatica del comprobante
	//	    		   io_pdf // Objeto PDF
	//    Description: función que imprime la cabecera de cada página
	//	   Creado Por: Ing. Jennifer Rivero
	// Fecha Creación: 17/11/2008 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_estmodest  = $_SESSION["la_empresa"]["estmodest"];
		$li_nomestpro1 = $_SESSION["la_empresa"]["nomestpro1"];
		$li_nomestpro2 = $_SESSION["la_empresa"]["nomestpro2"];
		$li_nomestpro3 = $_SESSION["la_empresa"]["nomestpro3"];
		$li_nomestpro4 = $_SESSION["la_empresa"]["nomestpro4"];
		$li_nomestpro5 = $_SESSION["la_empresa"]["nomestpro5"];
		$li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
	    $li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
	    $li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
	    $li_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
	    $li_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
		
		$ls_codestpro1    = trim(substr($ls_codestpro1,-$li_loncodestpro1));
		$ls_codestpro2    = trim(substr($ls_codestpro2,-$li_loncodestpro2));
		$ls_codestpro3    = trim(substr($ls_codestpro3,-$li_loncodestpro3));
		$ls_codestpro4    = trim(substr($ls_codestpro4,-$li_loncodestpro4));
		$ls_codestpro5    = trim(substr($ls_codestpro5,-$li_loncodestpro5));
		
		if ($ls_estmodest==1)
		{
			$ls_datat1[1]=array('nombre'=>'<b>'.$li_nomestpro1.":</b> ",'codestpro'=>$ls_codestpro1,'denom'=>$ls_denestpro1);
			$ls_datat1[2]=array('nombre'=>'<b>'.$li_nomestpro2.":</b> ",'codestpro'=>$ls_codestpro2,'denom'=>$ls_denestpro2);
			$ls_datat1[3]=array('nombre'=>'<b>'.$li_nomestpro3.":</b> ",'codestpro'=>$ls_codestpro3,'denom'=>$ls_denestpro3);			
			
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'fontSize' => 6, // Tamaño de Letras
							 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'colGap'=>1, // separacion entre tablas
							 'width'=>990, // Ancho de la tabla
							 'maxWidth'=>990, // Ancho Máximo de la tabla
							 'xOrientation'=>'center', // Orientación de la tabla
							 'xPos'=>302, // Orientación de la tabla
							 'cols'=>array('nombre'=>array('justification'=>'left','width'=>150),									  
										   'codestpro'=>array('justification'=>'right','width'=>60),
										   'denom'=>array('justification'=>'left','width'=>320)));		
			$io_pdf->ezTable($ls_datat1,'','',$la_config);
		}
		else
		{
			$ls_datat1[1]=array('nombre'=>'<b>'.$li_nomestpro1.":</b> ",'codestpro'=>$ls_codestpro1,'denom'=>$ls_denestpro1);
			$ls_datat1[2]=array('nombre'=>'<b>'.$li_nomestpro2.":</b> ",'codestpro'=>$ls_codestpro2,'denom'=>$ls_denestpro2);
			$ls_datat1[3]=array('nombre'=>'<b>'.$li_nomestpro3.":</b> ",'codestpro'=>$ls_codestpro3,'denom'=>$ls_denestpro3);
			$ls_datat1[4]=array('nombre'=>'<b>'.$li_nomestpro4.":</b> ",'codestpro'=>$ls_codestpro4,'denom'=>$ls_denestpro4);
			$ls_datat1[5]=array('nombre'=>'<b>'.$li_nomestpro5.":</b> ",'codestpro'=>$ls_codestpro5,'denom'=>$ls_denestpro5);			
			
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'fontSize' => 6, // Tamaño de Letras
							 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'colGap'=>1, // separacion entre tablas
							 'width'=>990, // Ancho de la tabla
							 'maxWidth'=>990, // Ancho Máximo de la tabla
							 'xOrientation'=>'center', // Orientación de la tabla
							 'xPos'=>302, // Orientación de la tabla
							 'cols'=>array('nombre'=>array('justification'=>'left','width'=>150),									  
										   'codestpro'=>array('justification'=>'right','width'=>60),
										   'denom'=>array('justification'=>'left','width'=>320)));			
		   $io_pdf->ezTable($ls_datat1,'','',$la_config);	
		}
		unset($ls_datat1);
		unset($la_config);			
	}// end function uf_print_cabecera
//--------------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("sigesp_spi_reporte.php");
		$io_report = new sigesp_spi_reporte();
		require_once("sigesp_spi_funciones_reportes.php");
		$io_function_report = new sigesp_spi_funciones_reportes();
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../class_funciones_ingreso.php");
		$io_fun_ingreso=new class_funciones_ingreso();			
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
//--------------------------------------------------  Parámetros para Filtar el Reporte  -------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		
		$ls_cmbmesdes = "01";
		$ldt_fecini=$li_ano."-".$ls_cmbmesdes."-01";
		
		$ls_cmbmeshas = "12";
		$ls_mes=$ls_cmbmeshas;
		$ls_ano=$li_ano;
		$fecfin=$io_fecha->uf_last_day($ls_mes,$ls_ano);
		$ldt_fecfin=$io_funciones->uf_convertirdatetobd($fecfin);
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
	    $ls_estpreing=$_SESSION["la_empresa"]["estpreing"];
		
		if($ls_estpreing==1)
		{
			$ls_codestpro1_min  = $_GET["codestpro1"];
			$ls_codestpro2_min  = $_GET["codestpro2"];
			$ls_codestpro3_min  = $_GET["codestpro3"];
			$ls_codestpro1h_max = $_GET["codestpro1h"];
			$ls_codestpro2h_max = $_GET["codestpro2h"];
			$ls_codestpro3h_max = $_GET["codestpro3h"];
			$ls_estclades       = $_GET["estclades"];
			$ls_estclahas       = $_GET["estclahas"];
			$ls_loncodestpro1   = $_SESSION["la_empresa"]["loncodestpro1"];
			$ls_loncodestpro2   = $_SESSION["la_empresa"]["loncodestpro2"];
			$ls_loncodestpro3   = $_SESSION["la_empresa"]["loncodestpro3"];
			$ls_loncodestpro4   = $_SESSION["la_empresa"]["loncodestpro4"];
			$ls_loncodestpro5   = $_SESSION["la_empresa"]["loncodestpro5"];
	
			if($ls_modalidad==1)
			{
				$ls_codestpro4_min =  "0000000000000000000000000";
				$ls_codestpro5_min =  "0000000000000000000000000";
				$ls_codestpro4h_max = "0000000000000000000000000";
				$ls_codestpro5h_max = "0000000000000000000000000";
				if(($ls_codestpro1_min=="")&&($ls_codestpro2_min=="")&&($ls_codestpro3_min==""))
				{
				  if($io_function_report->uf_spi_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,
																				 $ls_codestpro3_min,$ls_codestpro4_min,
																				 $ls_codestpro5_min,$ls_estclades))
				  {
						$ls_codestpro1  = $ls_codestpro1_min;
						$ls_codestpro2  = $ls_codestpro2_min;
						$ls_codestpro3  = $ls_codestpro3_min;
						$ls_codestpro4  = $ls_codestpro4_min;
						$ls_codestpro5  = $ls_codestpro5_min;
				  }
				}
				else
				{
						$ls_codestpro1  = $ls_codestpro1_min;
						$ls_codestpro2  = $ls_codestpro2_min;
						$ls_codestpro3  = $ls_codestpro3_min;
						$ls_codestpro4  = $ls_codestpro4_min;
						$ls_codestpro5  = $ls_codestpro5_min;
				}
				if(($ls_codestpro1h_max=="")&&($ls_codestpro2h_max=="")&&($ls_codestpro3h_max==""))
				{
				  if($io_function_report->uf_spi_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
																				 $ls_codestpro3h_max,$ls_codestpro4h_max,
																				 $ls_codestpro5h_max,$ls_estclahas))
				  {
						$ls_codestpro1h  = $ls_codestpro1h_max;
						$ls_codestpro2h  = $ls_codestpro2h_max;
						$ls_codestpro3h  = $ls_codestpro3h_max;
						$ls_codestpro4h  = $ls_codestpro4h_max;
						$ls_codestpro5h  = $ls_codestpro5h_max;
				  }
				}
				else
				{
						$ls_codestpro1h  = $ls_codestpro1h_max;
						$ls_codestpro2h  = $ls_codestpro2h_max;
						$ls_codestpro3h  = $ls_codestpro3h_max;
						$ls_codestpro4h  = $ls_codestpro4h_max;
						$ls_codestpro5h  = $ls_codestpro5h_max;
				}
			}
			elseif($ls_modalidad==2)
			{
				$ls_codestpro4_min  = $_GET["codestpro4"];
				$ls_codestpro5_min  = $_GET["codestpro5"];
				$ls_codestpro4h_max = $_GET["codestpro4h"];
				$ls_codestpro5h_max = $_GET["codestpro5h"];
				
				if(($ls_codestpro1_min=='**') ||($ls_codestpro1_min==''))
				{
					$ls_codestpro1_min='';
				}
				else
				{
					$ls_codestpro1_min  = $io_funciones->uf_cerosizquierda($ls_codestpro1_min,25);
				}
				if(($ls_codestpro2_min=='**') ||($ls_codestpro2_min==''))
				{
					$ls_codestpro2_min='';
				}
				else
				{
					$ls_codestpro2_min  = $io_funciones->uf_cerosizquierda($ls_codestpro2_min,25);
				
				}
				if(($ls_codestpro3_min=='**')||($ls_codestpro3_min==''))
				{
					$ls_codestpro3_min='';
				}
				else
				{
				
					$ls_codestpro3_min  = $io_funciones->uf_cerosizquierda($ls_codestpro3_min,25);
				}
				if(($ls_codestpro4_min=='**') ||($ls_codestpro4_min==''))
				{
					$ls_codestpro4_min='';
				}
				else
				{
					$ls_codestpro4_min  = $io_funciones->uf_cerosizquierda($ls_codestpro4_min,25);
		
				
				}
				if(($ls_codestpro5_min=='**') ||($ls_codestpro5_min==''))
				{
					$ls_codestpro5_min='';
				}
				else
				{
					$ls_codestpro5_min  = $io_funciones->uf_cerosizquierda($ls_codestpro5_min,25);
				}
				
				
				if(($ls_codestpro1h_max=='**')||($ls_codestpro1h_max==''))
				{
					$ls_codestpro1h_max='';
				}
				else
				{
					$ls_codestpro1h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro1h_max,25);
				}
				if(($ls_codestpro2h_max=='**') ||($ls_codestpro2h_max==''))
				{
					$ls_codestpro2h_max='';
				}else
				{
					$ls_codestpro2h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro2h_max,25);
				}
				if(($ls_codestpro3h_max=='**') ||($ls_codestpro3h_max==''))
				{
					$ls_codestpro3h_max='';
				}else
				{
					$ls_codestpro3h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro3h_max,25);
				}
				if(($ls_codestpro4h_max=='**')  ||($ls_codestpro4h_max==''))
				{
					$ls_codestpro4h_max='';
				}else
				{
					$ls_codestpro4h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro4h_max,25);
				}
				if(($ls_codestpro5h_max=='**')  || ($ls_codestpro5h_max==''))
				{
					$ls_codestpro5h_max='';
				}else
				{
					$ls_codestpro5h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro5h_max,25);
				}
				
				if(($ls_codestpro1_min=="")||($ls_codestpro2_min=="")||($ls_codestpro3_min=="")||($ls_codestpro4_min=="")||($ls_codestpro5_min==""))
				{
				  if($io_function_report->uf_spi_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,
																				 $ls_codestpro3_min,$ls_codestpro4_min,
																				 $ls_codestpro5_min,$ls_estclades))
				  {
						$ls_codestpro1  = $ls_codestpro1_min;
						$ls_codestpro2  = $ls_codestpro2_min;
						$ls_codestpro3  = $ls_codestpro3_min;
						$ls_codestpro4  = $ls_codestpro4_min;
						$ls_codestpro5  = $ls_codestpro5_min;
				  }
				}
				else
				{
						$ls_codestpro1  = $ls_codestpro1_min;
						$ls_codestpro2  = $ls_codestpro2_min;
						$ls_codestpro3  = $ls_codestpro3_min;
						$ls_codestpro4  = $ls_codestpro4_min;
						$ls_codestpro5  = $ls_codestpro5_min;
				}
				if(($ls_codestpro1h_max=="")||($ls_codestpro2h_max=="")||($ls_codestpro3h_max=="")||($ls_codestpro4h_max=="")||($ls_codestpro5h_max==""))
				{
				  if($io_function_report->uf_spi_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
																				 $ls_codestpro3h_max,$ls_codestpro4h_max,
																				 $ls_codestpro5h_max,$ls_estclahas))
				  {
					$ls_codestpro1h  = $ls_codestpro1h_max;
					$ls_codestpro2h  = $ls_codestpro2h_max;
					$ls_codestpro3h  = $ls_codestpro3h_max;
					$ls_codestpro4h  = $ls_codestpro4h_max;
					$ls_codestpro5h  = $ls_codestpro5h_max;
				  }
				}
				else
				{
					$ls_codestpro1h  = $ls_codestpro1h_max;
					$ls_codestpro2h  = $ls_codestpro2h_max;
					$ls_codestpro3h  = $ls_codestpro3h_max;
					$ls_codestpro4h  = $ls_codestpro4h_max;
					$ls_codestpro5h  = $ls_codestpro5h_max;
				}
			}
			
			$ls_programatica_desde=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
			$ls_programatica_hasta=$ls_codestpro1h.$ls_codestpro2h.$ls_codestpro3h.$ls_codestpro4h.$ls_codestpro5h;
			if($ls_modalidad==1)
			{
				if (($ls_codestpro1<>"")&&($ls_codestpro2=="")&&($ls_codestpro3==""))
				{
				 $ls_programatica_desde1=substr($ls_codestpro1,-$ls_loncodestpro1);
				 $ls_programatica_hasta1=substr($ls_codestpro1h,-$ls_loncodestpro1);
				}
				elseif(($ls_codestpro1<>"")&&($ls_codestpro2<>"")&&($ls_codestpro3==""))
				{
				 $ls_programatica_desde1=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2);
				 $ls_programatica_hasta1=substr($ls_codestpro1h,-$ls_loncodestpro1)."-".substr($ls_codestpro2h,-$ls_loncodestpro2);
				}
				elseif(($ls_codestpro1<>"")&&($ls_codestpro2<>"")&&($ls_codestpro3<>""))
				{
				 $ls_programatica_desde1=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2)."-".substr($ls_codestpro3,-$ls_loncodestpro3);
				 $ls_programatica_hasta1=substr($ls_codestpro1h,-$ls_loncodestpro1)."-".substr($ls_codestpro2h,-$ls_loncodestpro2)."-".substr($ls_codestpro3h,-$ls_loncodestpro3);
				}
				else
				{
				 $ls_programatica_desde1="";
				 $ls_programatica_hasta1="";
				}
			}
			else
			{
				$ls_programatica_desde1=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2)."-".substr($ls_codestpro3,-$ls_loncodestpro3)."-".substr($ls_codestpro4,-$ls_loncodestpro4)."-".substr($ls_codestpro5,-$ls_loncodestpro5)."-".$ls_estclades;
				$ls_programatica_hasta1=substr($ls_codestpro1h,-$ls_loncodestpro1)."-".substr($ls_codestpro2h,-$ls_loncodestpro2)."-".substr($ls_codestpro3h,-$ls_loncodestpro3)."-".substr($ls_codestpro4h,-$ls_loncodestpro4)."-".substr($ls_codestpro5h,-$ls_loncodestpro5)."-".$ls_estclahas;
			}
         }
		 $ls_cuentades_min=$_GET["txtcuentades"];
		 $ls_cuentahas_max=$_GET["txtcuentahas"];
			if($ls_cuentades_min=="")
			{
			   if($io_function_report->uf_spi_reporte_select_min_cuenta($ls_cuentades_min))
			   {
				 $ls_cuentades=$ls_cuentades_min;
			   } 
			   else
			   {
					print("<script language=JavaScript>");
					print(" alert('No hay cuentas presupuestraias');"); 
					print(" close();");
					print("</script>");
			   }
			}
			else
			{
				$ls_cuentades=$ls_cuentades_min;
			}
			if($ls_cuentahas_max=="")
			{
			   if($io_function_report->uf_spi_reporte_select_max_cuenta($ls_cuentahas_max))
			   {
				 $ls_cuentahas=$ls_cuentahas_max;
			   } 
			   else
			   {
					print("<script language=JavaScript>");
					print(" alert('No hay cuentas presupuestraias');"); 
					print(" close();");
					print("</script>");
			   }
			}
			else
			{
				$ls_cuentahas=$ls_cuentahas_max;
			}
		/////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////
		$ls_desc_event="Solicitud de Reporte Listado de Apertura desde la Cuenta ".$ls_cuentades." hasta ".$ls_cuentahas;
		$io_fun_ingreso->uf_load_seguridad_reporte("SPI","sigesp_spi_r_listado_apertura.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////
//----------------------------------------------------  Parámetros del encabezado  --------------------------------------------
		$ls_titulo=" <b>LISTADO DE APERTURAS</b> ";
		if($ls_estpreing==1)
		{
		  $ls_titulo1="<b>DESDE LA PROGRAMATICA  ".$ls_programatica_desde1."  HASTA  ".$ls_programatica_hasta1." </b>";
		}
		$ls_tiporeporte=$_GET["tiporeporte"];
		global $ls_tiporeporte;
		require_once("../../shared/ezpdf/class.ezpdf.php");
		
		if($ls_tiporeporte==1)
		{
			require_once("sigesp_spi_reportebsf.php");
			$io_report=new sigesp_spi_reportebsf();
		}       
//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
	if($ls_estpreing==1)
	{
			$ls_codestpro1  = $io_funciones->uf_cerosizquierda($ls_codestpro1_min,25);
			$ls_codestpro2  = $io_funciones->uf_cerosizquierda($ls_codestpro2_min,25);
			$ls_codestpro3  = $io_funciones->uf_cerosizquierda($ls_codestpro3_min,25);
			$ls_codestpro4  = $io_funciones->uf_cerosizquierda($ls_codestpro4_min,25);
			$ls_codestpro5  = $io_funciones->uf_cerosizquierda($ls_codestpro5_min,25);
				
			$ls_codestpro1h  = $io_funciones->uf_cerosizquierda($ls_codestpro1h_max,25);
			$ls_codestpro2h  = $io_funciones->uf_cerosizquierda($ls_codestpro2h_max,25);
			$ls_codestpro3h  = $io_funciones->uf_cerosizquierda($ls_codestpro3h_max,25);
			$ls_codestpro4h  = $io_funciones->uf_cerosizquierda($ls_codestpro4h_max,25);
			$ls_codestpro5h  = $io_funciones->uf_cerosizquierda($ls_codestpro5h_max,25);
			$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
			$ls_estpreing=$_SESSION["la_empresa"]["estpreing"];
			$li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
			$li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
			$li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
			$li_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
			$li_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
			$ls_denestpro4="";
			$ls_denestpro5="";
	}
	error_reporting(E_ALL);
	set_time_limit(1800);
	$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
	$io_pdf->ezSetCmMargins(3,3,3,3); // Configuración de los margenes en centímetros
	if ($ls_estpreing==1)
	{
		uf_print_encabezado_pagina2($ls_titulo,$ls_titulo1,$io_pdf); // Imprimimos el encabezado de la página
	}
	else
	{
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
    }
	$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
	
	if ($ls_estpreing==1)
	{	
	      $lb_valido=$io_report->select_estructuras($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
											        $ls_codestpro5,$ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,
											        $ls_codestpro4h,$ls_codestpro5h,$ls_estclades,$ls_estclahas);
		  $li_totfila=$io_report->data_est->getRowCount("programatica");
		  for ($j=1;$j<=$li_totfila;$j++)
		  {
			  $ls_codestpro1=trim($io_report->data_est->data["codestpro1"][$j]); 
			  $ls_codestpro2=trim($io_report->data_est->data["codestpro2"][$j]);
			  $ls_codestpro3=trim($io_report->data_est->data["codestpro3"][$j]);
			  $ls_codestpro4=trim($io_report->data_est->data["codestpro4"][$j]);
			  $ls_codestpro5=trim($io_report->data_est->data["codestpro5"][$j]);
			  $ls_estcla=trim($io_report->data_est->data["estcla"][$j]);
			  $ls_estclades=trim($io_report->data_est->data["estcla"][$j]);
			
			  $ls_codestpro1h=trim($io_report->data_est->data["codestpro1"][$j]);
			  $ls_codestpro2h=trim($io_report->data_est->data["codestpro2"][$j]);
			  $ls_codestpro3h=trim($io_report->data_est->data["codestpro3"][$j]);
			  $ls_codestpro4h=trim($io_report->data_est->data["codestpro4"][$j]);
			  $ls_codestpro5h=trim($io_report->data_est->data["codestpro5"][$j]);
			  $ls_estclahas=trim($io_report->data_est->data["estcla"][$j]); 
			  $lb_valido=$io_report->uf_spi_reporte_apertura2($ldt_fecini,$ldt_fecfin,$ls_cuentades,$ls_cuentahas,
															$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
															$ls_codestpro5,$ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,
															$ls_codestpro4h,$ls_codestpro5h,$ls_estclades,$ls_estclahas);
			  $li_totrow_det=$io_report->dts_reporte->getRowCount("spi_cuenta");
			  $ld_total=0; 
			  for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
			  { 
			   		$ls_spi_cuenta=$io_report->dts_reporte->data["spi_cuenta"][$li_s];
					$ls_denominacion=$io_report->dts_reporte->data["denominacion"][$li_s];
					$ls_descripcion=$io_report->dts_reporte->data["descripcion"][$li_s];  
					$ls_documento=$io_report->dts_reporte->data["documento"][$li_s];  
					$ld_monto=$io_report->dts_reporte->data["monto"][$li_s];  
					$ld_total=$ld_total+$ld_monto;
					$ld_monto=number_format($ld_monto,2,",",".");
					$la_data1[$li_s]=array('cuenta'=>$ls_spi_cuenta,'denominacion'=>$ls_denominacion,
					  					  'descripcion'=>$ls_descripcion,'documento'=>$ls_documento,'monto'=>$ld_monto);
					$ld_monto=str_replace('.','',$ld_monto);
					$ld_monto=str_replace(',','.',$ld_monto);
			  }	//fin del for
			    $lb_valido=$io_report->uf_spg_reporte_select_denestpro1($ls_codestpro1,$ls_denestpro1,$ls_estcla);			
				if($lb_valido)
				{
					$ls_denestpro1=$ls_denestpro1; 
				}			
				if($lb_valido)
				{
					  $ls_denestpro2="";
					  $lb_valido=$io_report->uf_spg_reporte_select_denestpro2($ls_codestpro1,$ls_codestpro2,
					                                                          $ls_denestpro2,$ls_estcla);
					  $ls_denestpro2=$ls_denestpro2;
				}
				if($lb_valido)
				{
					  $ls_denestpro3="";
					  $lb_valido=$io_report->uf_spg_reporte_select_denestpro3($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																			  $ls_denestpro3,$ls_estcla);
					  $ls_denestpro3=$ls_denestpro3;
				}
				if($ls_modalidad==2)
				{
					$ls_codestpro4=substr($ls_programatica,75,25);
					if($lb_valido)
					{
					  $ls_denestpro4="";
					  $lb_valido=$io_report->uf_spg_reporte_select_denestpro4($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																			  $ls_codestpro4,$ls_denestpro4,$ls_estcla);
					  $ls_denestpro4=$ls_denestpro4;
					}
					$ls_codestpro5=substr($ls_programatica,100,25);
					if($lb_valido)
					{
					  $ls_denestpro5="";
					  $lb_valido=$io_report->uf_spg_reporte_select_denestpro5($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																			  $ls_codestpro4,$ls_codestpro5,$ls_denestpro5,
																			  $ls_estcla);
					  $ls_denestpro5=$ls_denestpro5;
					}			
				}
				if ($li_totrow_det>0)
				{			
				   $io_pdf->ezSetDy(-10);
				   uf_print_cabecera_estructura($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,
									       $ls_denestpro1,$ls_denestpro2,$ls_denestpro3,$ls_denestpro4,$ls_denestpro5,$io_pdf);
				   uf_print_detalle($la_data1,$io_pdf); // Imprimimos el detalle
				   $ld_total=number_format($ld_total,2,",",".");
			       uf_print_pie_cabecera($ld_total,$io_pdf); // Imprimimos el total programatica
				}
			   unset($la_data1);		  
			   unset($la_data);	
		  }// fin del for		 
		  $io_pdf->ezStopPageNumbers(1,1);
		  if (isset($d) && $d)
		  {
				$ls_pdfcode = $io_pdf->ezOutput(1);
				$ls_pdfcode = str_replace("\n","\n<br>",htmlspecialchars($ls_pdfcode));
				echo '<html><body>';
				echo trim($ls_pdfcode);
				echo '</body></html>';
		  }
		  else
		  {
				$io_pdf->ezStream();
		  }
		  unset($io_pdf);
	}
	else
	{ 
		$lb_valido=$io_report->uf_spi_reporte_apertura($ldt_fecini,$ldt_fecfin,$ls_cuentades,$ls_cuentahas);
		if($lb_valido==false) // Existe algún error ó no hay registros
		 {
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		 }
		 else // Imprimimos el reporte
		 {
			$li_totrow_det=$io_report->dts_reporte->getRowCount("spi_cuenta");
			$ld_total=0; 
			for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
			{
				  $ls_spi_cuenta=$io_report->dts_reporte->data["spi_cuenta"][$li_s];
				  $ls_denominacion=$io_report->dts_reporte->data["denominacion"][$li_s];
				  $ls_descripcion=$io_report->dts_reporte->data["descripcion"][$li_s];  
				  $ls_documento=$io_report->dts_reporte->data["documento"][$li_s];  
				  $ld_monto=$io_report->dts_reporte->data["monto"][$li_s];  
				
				  $ld_total=$ld_total+$ld_monto;
				  $ld_monto=number_format($ld_monto,2,",",".");
				  $la_data[$li_s]=array('cuenta'=>$ls_spi_cuenta,'denominacion'=>$ls_denominacion,
										'descripcion'=>$ls_descripcion,'documento'=>$ls_documento,'monto'=>$ld_monto);
				  $ld_monto=str_replace('.','',$ld_monto);
				  $ld_monto=str_replace(',','.',$ld_monto);		
			}
			uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
	
			$ld_total=number_format($ld_total,2,",",".");
			uf_print_pie_cabecera($ld_total,$io_pdf); // Imprimimos el total programatica
			unset($la_data);
			$io_pdf->ezStopPageNumbers(1,1);
			if (isset($d) && $d)
			{
				$ls_pdfcode = $io_pdf->ezOutput(1);
				$ls_pdfcode = str_replace("\n","\n<br>",htmlspecialchars($ls_pdfcode));
				echo '<html><body>';
				echo trim($ls_pdfcode);
				echo '</body></html>';
			}
			else
			{
				$io_pdf->ezStream();
			}
			unset($io_pdf);
		}
		unset($io_report);
		unset($io_funciones);
		unset($io_function_report);
	}//fin del eslede no usa estrutcuras 
	 
?> 