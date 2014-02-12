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
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 18/10/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->rectangle(10,450,990,150);
		
		$li_tm=$io_pdf->getTextWidth(16,$as_titulo);
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,550,16,$as_titulo); // Agregar el título
		
		$io_pdf->addText(900,550,10,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(900,540,10,date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_titulo_reporte($io_encabezado,$as_programatica,$ai_ano,$as_mes,$as_denestpro,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 18/10/2006 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->ezSetY(525);
		$ls_codorgsig=$_SESSION["la_empresa"]["codorgsig"];
		$ls_nombre=$_SESSION["la_empresa"]["nombre"];
		$ls_nomorgads=$_SESSION["la_empresa"]["nomorgads"];
		$la_data=array(array('name'=>'<b>CODIGO    </b>'.'<b>'.$ls_codorgsig.'</b>'),
		  			   array('name'=>'<b>DENOMINACIÓN    </b>'.'<b>'.$ls_nombre.'</b>'),
		               array('name'=>'<b>PROYECTO O ACCIÓN CENTRALIZADA    </b>'.'<b>'.$as_programatica.'</b>'),
		               array('name'=>''.'<b>'.$as_denestpro.'</b>'),       
		               array('name'=>'<b>ORGANO DE ADSCRIPCIÓN   </b>'.'<b>'.$ls_nomorgads.'</b>'));
		$la_columna=array('name'=>'','name'=>'','name'=>'','name'=>'','name'=>'');
		$la_config =array('showHeadings'=>0,     // Mostrar encabezados
						  'fontSize' =>8,       // Tamaño de Letras
						  'titleFontSize' =>8, // Tamaño de Letras de los títulos
						  'showLines'=>0,        // Mostrar Líneas
						  'shaded'=>0,           // Sombra entre líneas
						  'xPos'=>265,//65
						  'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						  'xOrientation'=>'center', // Orientación de la tabla
						  'width'=>900, // Ancho de la tabla
						  'maxWidth'=>900,
						  'cols'=>array('name'=>array('justification'=>'left','width'=>500),
						                'name'=>array('justification'=>'left','width'=>500),
						  			    'name'=>array('justification'=>'left','width'=>500)));
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_titulo($io_titulo,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_titulo
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 18/10/2006 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->ezSetY(451);
		$la_data=array(array('name1'=>'<b>EJECUTADO</b>','name2'=>'<b> </b>',
		                     'name3'=>'<b>DISPONIBILIDAD  PRESUPUESTARIA</b>'));
		$la_columna=array('name1'=>'','name2'=>'','name3'=>'');
		$la_config =array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 7,       // Tamaño de Letras
						 'titleFontSize' => 7, // Tamaño de Letras de los títulos
						 'showLines'=>1,        // Mostrar Líneas
						 'shaded'=>0,           // Sombra entre líneas
						 'xPos'=>684,
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>900,
						 'cols'=>array('name1'=>array('justification'=>'center','width'=>240),// Justificación y ancho de la columna
						               'name2'=>array('justification'=>'center','width'=>240),// Justificación y ancho de la columna
									   'name3'=>array('justification'=>'center','width'=>160))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_titulo,'all');

	}// end function uf_print_titulo
	//--------------------------------------------------------------------------------------------------------------------------------		

	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($io_cabecera,&$io_pdf)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 18/10/2006 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->ezSetY(438);
		$la_data=array(array('cuenta'=>'<b>Cuenta</b>','denominacion'=>'<b>Denominación</b>','pres_anual'=>'<b>Presupuesto Anual</b>',
		                     'programado'=>'<b>Programado</b>','compromiso'=>'<b>Compromiso</b>','causado'=>'<b>Causado</b>',
							 'pagado'=>'<b>Pagado</b>','porc_comprometer'=>'<b>% Compromiso</b>','porc_causado'=>'<b>% Causado</b>',
							 'porc_pagado'=>'<b>% Pagado</b>','disp_trim_ant'=>'<b>Mes Anterior</b>',
							 'disp_fecha'=>'<b>A la Fecha</b>'));
		$la_columna=array('cuenta'=>'','denominacion'=>'','pres_anual'=>'','programado'=>'','compromiso'=>'','causado'=>'',
		                  'pagado'=>'','porc_comprometer'=>'','porc_causado'=>'','porc_pagado'=>'','disp_trim_ant'=>'','disp_fecha'=>'');
		$la_config=array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 7,       // Tamaño de Letras
						 'titleFontSize' => 7, // Tamaño de Letras de los títulos
						 'showLines'=>2,        // Mostrar Líneas
						 'shaded'=>0,           // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990,
						 'colGap'=>0,
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'center','width'=>130), // Justificación y ancho de la columna
						 			   'pres_anual'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'programado'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'compromiso'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'causado'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'pagado'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'porc_comprometer'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'porc_causado'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'porc_pagado'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'disp_trim_ant'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'disp_fecha'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_cabecera,'all');

	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 18/10/2006 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>130), // Justificación y ancho de la columna
						 			   'pres_anual'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'programado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'compromiso'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'causado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'pagado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'porc_comprometer'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'porc_causado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'porc_pagado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'disp_trim_ant'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'disp_fecha'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		$la_columnas=array('cuenta'=>'<b></b>',
						   'denominacion'=>'<b></b>',
						   'pres_anual'=>'<b></b>',
						   'programado'=>'<b></b>',
						   'compromiso'=>'<b></b>',
						   'causado'=>'<b></b>',
						   'pagado'=>'<b></b>',
						   'porc_comprometer'=>'<b></b>',
						   'porc_causado'=>'<b></b>',
						   'porc_pagado'=>'<b></b>',
						   'disp_trim_ant'=>'<b></b>',
						   'disp_fecha'=>'<b></b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($la_data_tot,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private 
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 18/10/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>190), // Justificación y ancho de la columna
									   'pres_anual'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'programado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'compromiso'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'causado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'pagado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'porc_comprometer'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'porc_causado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'porc_pagado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'disp_trim_ant'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'disp_fecha'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		
		$la_columnas=array('total'=>'',
		                   'pres_anual'=>'',
						   'programado'=>'',
						   'compromiso'=>'',
						   'causado'=>'',
						   'pagado'=>'',
						   'porc_comprometer'=>'',
						   'porc_causado'=>'',
						   'porc_pagado'=>'',
						   'disp_trim_ant'=>'',
						   'disp_fecha'=>'');
		$io_pdf->ezTable($la_data_tot,$la_columnas,'',$la_config);
	}// end function uf_print_pie_cabecera
//--------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();	
		require_once("sigesp_spg_funciones_reportes.php");
		$io_function_report = new sigesp_spg_funciones_reportes();
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
		$ls_tipoformato=$_GET["tipoformato"];
//-----------------------------------------------------------------------------------------------------------------------------
		global $ls_tipoformato;
		global $ld_total_porc_comprometer;
		global $ld_total_porc_causado;	
		global $ld_total_porc_pagado;
		if($ls_tipoformato==1)
		{
			require_once("sigesp_spg_reporte_comparados_forma05_bsf.php");
			$io_report = new sigesp_spg_reporte_comparados_forma05_bsf();
		}
		else
		{
			require_once("sigesp_spg_reporte_comparados_forma05.php");
			$io_report = new sigesp_spg_reporte_comparados_forma05();
		}	
		 require_once("../../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
		 $io_rcbsf= new sigesp_c_reconvertir_monedabsf();
		 $li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		 $li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		 $li_redconmon=$_SESSION["la_empresa"]["redconmon"];
//--------------------------------------------------  Parámetros para Filtar el Reporte  ----------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		$ls_codestpro1_min  = $_GET["codestpro1"];
		$ls_codestpro2_min  = $_GET["codestpro2"];
		$ls_codestpro3_min  = $_GET["codestpro3"];
		$ls_codestpro1h_max = $_GET["codestpro1h"];
		$ls_codestpro2h_max = $_GET["codestpro2h"];
		$ls_codestpro3h_max = $_GET["codestpro3h"];
	    $ls_estclades       = $_GET["estclades"];
	    $ls_estclahas       = $_GET["estclahas"];
		if($li_estmodest==1)
		{
			$ls_codestpro4_min  = "0000000000000000000000000";
			$ls_codestpro5_min  = "0000000000000000000000000";
			$ls_codestpro4h_max = "0000000000000000000000000";
			$ls_codestpro5h_max = "0000000000000000000000000";
			if(($ls_codestpro1_min=="")&&($ls_codestpro2_min=="")&&($ls_codestpro3_min==""))
			{
			  if($io_function_report->uf_spg_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,
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
			  if($io_function_report->uf_spg_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
																			 $ls_codestpro3h_max,$ls_codestpro4h_max,
																			 $ls_codestpro4h_max,$ls_estclahas))
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
		elseif($li_estmodest==2)
		{
			$ls_codestpro4_min = $_GET["codestpro4"];
			$ls_codestpro5_min = $_GET["codestpro5"];
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
			}else
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
			  if($io_function_report->uf_spg_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,
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
			  if($io_function_report->uf_spg_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
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
		$cmbnivel=$_GET["cmbnivel"];
		if($cmbnivel=="s1")
		{
          $li_cmbnivel="1";
		}
		else
		{
          $li_cmbnivel=$cmbnivel;
		}
		$ls_cmbmes=$_GET["cmbmes"];
		$li_mes=substr($ls_cmbmes,0,2);
		$ldt_fecdes=$li_ano."-".$li_mes."-01";
		$ldt_ult_dia=$io_fecha->uf_last_day($li_mes,$li_ano);
		$fechas=$ldt_ult_dia;
		$ldt_fechas=$io_funciones->uf_convertirdatetobd($fechas);
		$ls_mes=$io_fecha->uf_load_nombre_mes($li_mes);
	    $ls_codfuefindes=$_GET["txtcodfuefindes"];
	    $ls_codfuefinhas=$_GET["txtcodfuefinhas"];
		if (($ls_codfuefindes=='')&&($ls_codfuefindes==''))
		{
		   if($io_function_report->uf_spg_select_fuentefinanciamiento(&$ls_minfuefin,&$ls_maxfuefin))
		   {
		     $ls_codfuefindes=$ls_minfuefin;
		     $ls_codfuefinhas=$ls_maxfuefin;
		   } 
		}
//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
		$ls_titulo=" <b>EJECUCION FINANCIERA MENSUAL DEL PRESUPUESTO DE GASTOS (FORMA 0503)</b>";       
//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
     $lb_valido=$io_report->uf_spg_reportes_select_ejecucion_financiera_forma0503($ls_codestpro1,$ls_codestpro2,
	                                                                              $ls_codestpro3,$ls_codestpro4,
																				  $ls_codestpro5,$ls_codestpro1h,
																	  			  $ls_codestpro2h,$ls_codestpro3h,
																	              $ls_codestpro4h,$ls_codestpro5h,
																				  $ls_codfuefindes,$ls_codfuefinhas,
																				  $ls_estclades,$ls_estclahas,&$rs_data);
	 if($lb_valido==false) // Existe algún error ó no hay registros
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	 }
	 else // Imprimimos el reporte
	 {
	    error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(6.6,3,3,3); // Configuración de los margenes en centímetros(para el rectangulo)
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
 	    $io_pdf->ezStartPageNumbers(980,40,10,'','',1); // Insertar el número de página
		$li_tot=$io_report->dts_cab->getRowCount("programatica");
		//for($li_i=1;$li_i<=$li_tot;$li_i++)
		$li_i=0;
		$ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
		$ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
		$ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
		$ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
		$ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
		while($row=$io_report->SQL->fetch_row($rs_data))
		{		
			$li_i=$li_i+1;
	        $io_pdf->transaction('start'); // Iniciamos la transacción
			$thisPageNum=$io_pdf->ezPageCount;
			//$ls_programatica=$io_report->dts_cab->data["programatica"][$li_i];
			$ls_programatica=$row["programatica"];
		    $ls_estcla=substr($ls_programatica,-1);
		    $ls_codestpro1=substr($ls_programatica,0,25);
		    $ls_denestpro1="";
		    $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro1($ls_codestpro1,$ls_denestpro1,$ls_estcla);
		    if($lb_valido)
		    {
			  $ls_denestpro1=trim($ls_denestpro1);
		    }
		    $ls_codestpro2=substr($ls_programatica,25,25);
		    if($lb_valido)
		    {
			  $ls_denestpro2="";
			  $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro2($ls_codestpro1,$ls_codestpro2,$ls_denestpro2,$ls_estcla);
			  $ls_denestpro2=trim($ls_denestpro2);
		    }
		    $ls_codestpro3=substr($ls_programatica,50,25);
		    if($lb_valido)
		    {
			  $ls_denestpro3="";
			  $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro3($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_denestpro3,$ls_estcla);
			  $ls_denestpro3=trim($ls_denestpro3);
		    }
			if($li_estmodest==2)
			{
				$ls_codestpro4=substr($ls_programatica,75,25);
				if($lb_valido)
				{
				  $ls_denestpro4="";
				  $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro4($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_denestpro4,$ls_estcla);
				  $ls_denestpro4=trim($ls_denestpro4);
				}
				$ls_codestpro5=substr($ls_programatica,100,25);
				if($lb_valido)
				{
				  $ls_denestpro5="";
				  $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro5($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_denestpro5,$ls_estcla);
				  $ls_denestpro5=trim($ls_denestpro5);
				}
			    $ls_denestpro=trim($ls_denestpro1)." , ".trim($ls_denestpro2)." , ".trim($ls_denestpro3)." , ".trim($ls_denestpro4)." , ".trim($ls_denestpro5);
			    $ls_programatica=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2)."-".substr($ls_codestpro3,-$ls_loncodestpro3)."-".substr($ls_codestpro4,-$ls_loncodestpro4)."-".substr($ls_codestpro5,-$ls_loncodestpro5);
			}
			else
			{
			    $ls_denestpro=trim($ls_denestpro1)." , ".trim($ls_denestpro2)." , ".trim($ls_denestpro3);
				$ls_programatica=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2)."-".substr($ls_codestpro3,-$ls_loncodestpro3);
			}
		    $lb_ok=$io_report->uf_spg_reportes_comparados_ejecucion_financiera_forma0503($ls_codestpro1,$ls_codestpro2,
	                                                                                     $ls_codestpro3,$ls_codestpro4,
																					     $ls_codestpro5,$ls_codestpro1,
																	  				     $ls_codestpro2,$ls_codestpro3,
																	                     $ls_codestpro4,$ls_codestpro5,
																	                     $ldt_fecdes,$ldt_fechas,$li_cmbnivel,
																						 $ls_codfuefindes,$ls_codfuefinhas,
																				         $ls_estclades,$ls_estclahas);
		  if($lb_ok==false) // Existe algún error ó no hay registros
		  {
				print("<script language=JavaScript>");
				print(" alert('No hay nada que Reportar');"); 
				print(" close();");
				print("</script>");
		  }
		  else																					
		  {
			$ld_pres_anual=0;
			$ld_programado=0;
			$ld_porc_comprometer=0;
			$ld_porc_causado=0;
			$ld_porc_pagado=0;
			$ld_total_pres_anual=0;
			$ld_total_compromiso=0;
			$ld_total_causado=0;
			$ld_total_pagado=0;
			$ld_total_programado=0;
			$ld_total_prog_t_ant=0;
			$ld_total_disp_fecha=0;
			$li_total=$io_report->dts_reporte->getRowCount("spg_cuenta");
			for($z=1;$z<=$li_total;$z++)
			{
				  $thisPageNum=$io_pdf->ezPageCount;
				  $ls_spg_cuenta=trim($io_report->dts_reporte->data["spg_cuenta"][$z]);
				  $ls_denominacion=trim($io_report->dts_reporte->data["denominacion"][$z]);
				  $li_nivel=$io_report->dts_reporte->data["nivel"][$z];
				  $ld_asignado=$io_report->dts_reporte->data["asignado"][$z];
				  $ld_monto_programado=$io_report->dts_reporte->data["monto_programado"][$z];
				  $ld_aumdismes=$io_report->dts_reporte->data["aumdis_mes"][$z];
				  $ld_aumdisacum=$io_report->dts_reporte->data["aumdis_acumulado"][$z];
				  $ld_monto_ejecutado=$io_report->dts_reporte->data["ejecutado_mes"][$z];
				  $ld_ejecutado_acumulado=$io_report->dts_reporte->data["ejecutado_acum"][$z];
				  $ld_comprometer=$io_report->dts_reporte->data["compromiso"][$z];
				  $ld_causado=$io_report->dts_reporte->data["causado"][$z];
				  $ld_pagado=$io_report->dts_reporte->data["pagado"][$z];
				  if($li_mes=="01")
				  {
				     $ld_ejec_t_ant=0;
				  }
				  else
				  {
				   	 $ld_ejec_t_ant=$io_report->dts_reporte->data["ejec_t_ant"][$z];
				  }
				  $ld_compr_t_ant=$io_report->dts_reporte->data["compr_t_ant"][$z];
				  $ld_dispon_fecha=$io_report->dts_reporte->data["disponible_fecha"][$z];
				  
				  $ld_pres_anual=$ld_asignado+$ld_aumdismes/*+$ld_aumdisacum*/;
				  $ld_programado=$ld_monto_programado+$ld_aumdismes;	
				  $ld_comprometer=$ld_comprometer;
				  $ld_causado=$ld_causado;	  
				  $ld_pagado=$ld_pagado;
				  if($ld_monto_programado<>0)
				  {
					 $ld_porc_comprometer=($ld_comprometer*100)/$ld_monto_programado;
				  }
				  else
				  {
					 $ld_porc_comprometer=0;
				  }
				  if($ld_monto_programado<>0)
				  {
					 $ld_porc_causado=($ld_causado*100)/$ld_monto_programado;
				  }
				  else
				  {
					 $ld_porc_causado=0;
				  }
				  if($ld_monto_programado<>0)
				  {
					 $ld_porc_pagado=($ld_pagado*100)/$ld_monto_programado;
				  }
				  else
				  {
					 $ld_porc_pagado=0;
				  }
				  $ld_ejec_t_ant=$ld_ejec_t_ant;
				  $ld_dispon_fecha=$ld_dispon_fecha;
				  //totales
				  if($li_nivel==1)
				  {
					 $ld_total_pres_anual=$ld_total_pres_anual+$ld_pres_anual/*($ld_asignado+$ld_aumdisacum)*/;
					 $ld_total_compromiso=$ld_total_compromiso+$ld_comprometer;
					 $ld_total_causado=$ld_total_causado+$ld_causado;
					 $ld_total_pagado=$ld_total_pagado+$ld_pagado;
					 $ld_total_programado=$ld_total_programado+$ld_programado;
					 $ld_total_prog_t_ant=$ld_total_prog_t_ant+$ld_ejec_t_ant/*($ld_ejec_t_ant-$ld_compr_t_ant)*/;
					 $ld_total_disp_fecha=$ld_total_disp_fecha+$ld_dispon_fecha;
				  }
		
				  $ld_pres_anual=number_format($ld_pres_anual,2,",",".");
				  $ld_programado=number_format($ld_programado,2,",",".");
				  $ld_comprometer=number_format($ld_comprometer,2,",",".");
				  $ld_causado=number_format($ld_causado,2,",",".");
				  $ld_pagado=number_format($ld_pagado,2,",",".");
				  $ld_porc_comprometer=number_format($ld_porc_comprometer,2,",",".");
				  $ld_porc_causado=number_format($ld_porc_causado,2,",",".");
				  $ld_porc_pagado=number_format($ld_porc_pagado,2,",",".");
				  $ld_ejec_t_ant=number_format($ld_ejec_t_ant,2,",",".");
				  $ld_dispon_fecha=number_format($ld_dispon_fecha,2,",",".");
				
				  $la_data[$z]=array('cuenta'=>$ls_spg_cuenta,'denominacion'=>$ls_denominacion,'pres_anual'=>$ld_pres_anual,
									 'programado'=>$ld_programado,'compromiso'=>$ld_comprometer,'causado'=>$ld_causado,
									 'pagado'=>$ld_pagado,'porc_comprometer'=>$ld_porc_comprometer,'porc_causado'=>$ld_porc_causado,
									 'porc_pagado'=>$ld_porc_pagado,'disp_trim_ant'=>$ld_ejec_t_ant,'disp_fecha'=>$ld_dispon_fecha);
									  
				 $ld_pres_anual=str_replace('.','',$ld_pres_anual);
				 $ld_pres_anual=str_replace(',','.',$ld_pres_anual);		
				 $ld_programado=str_replace('.','',$ld_programado);
				 $ld_programado=str_replace(',','.',$ld_programado);		
				 $ld_comprometer=str_replace('.','',$ld_comprometer);
				 $ld_comprometer=str_replace(',','.',$ld_comprometer);		
				 $ld_causado=str_replace('.','',$ld_causado);
				 $ld_causado=str_replace(',','.',$ld_causado);
				 $ld_pagado=str_replace('.','',$ld_pagado);
				 $ld_pagado=str_replace(',','.',$ld_pagado);
				 $ld_porc_comprometer=str_replace('.','',$ld_porc_comprometer);
				 $ld_porc_comprometer=str_replace(',','.',$ld_porc_comprometer);	
				 $ld_porc_causado=str_replace('.','',$ld_porc_causado);
				 $ld_porc_causado=str_replace(',','.',$ld_porc_causado);		
				 $ld_porc_pagado=str_replace('.','',$ld_porc_pagado);
				 $ld_porc_pagado=str_replace(',','.',$ld_porc_pagado);		
				 $ld_ejec_t_ant=str_replace('.','',$ld_ejec_t_ant);
				 $ld_ejec_t_ant=str_replace(',','.',$ld_ejec_t_ant);		
				 $ld_dispon_fecha=str_replace('.','',$ld_dispon_fecha);
				 $ld_dispon_fecha=str_replace(',','.',$ld_dispon_fecha);
				 if($z==$li_total)
				 {
					  
					 if($ls_tipoformato==1)
					 {
						  $ld_total_pres_anual=number_format($ld_total_pres_anual,2,",",".");
						  $ld_total_programado=number_format($ld_total_programado,2,",",".");
						  $ld_total_compromiso=number_format($ld_total_compromiso,2,",",".");
						  $ld_total_causado=number_format($ld_total_causado,2,",",".");
						  $ld_total_pagado=number_format($ld_total_pagado,2,",",".");
						  $ld_total_prog_t_ant=number_format($ld_total_prog_t_ant,2,",",".");
						  $ld_total_disp_fecha=number_format($ld_total_disp_fecha,2,",",".");
						  $ld_total_porc_comprometer="";
						  $ld_total_porc_causado="";	
						  $ld_total_porc_pagado="";
							   
						  $la_data_tot[$z]=array('total'=>'<b>TOTALES BsF.</b>','pres_anual'=>$ld_total_pres_anual,'programado'=>$ld_total_programado,
												 'compromiso'=>$ld_total_compromiso,'causado'=>$ld_total_causado,'pagado'=>$ld_total_pagado,
												 'porc_comprometer'=>$ld_total_porc_comprometer,'porc_causado'=>$ld_total_porc_causado,
												 'porc_pagado'=>$ld_total_porc_pagado,'disp_trim_ant'=>$ld_total_prog_t_ant,
												 'disp_fecha'=>$ld_total_disp_fecha);
					 }
					 else
					 {
					 	  
						  
						  
						  $ld_total_pres_anual_bsf   = $io_rcbsf->uf_convertir_monedabsf($ld_total_pres_anual, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
						  $ld_total_programado_bsf   = $io_rcbsf->uf_convertir_monedabsf($ld_total_programado, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
						  $ld_total_compromiso_bsf   = $io_rcbsf->uf_convertir_monedabsf($ld_total_compromiso, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
						  $ld_total_causado_bsf   = $io_rcbsf->uf_convertir_monedabsf($ld_total_causado, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
						  $ld_total_pagado_bsf   = $io_rcbsf->uf_convertir_monedabsf($ld_total_pagado, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
						  $ld_total_prog_t_ant_bsf   = $io_rcbsf->uf_convertir_monedabsf($ld_total_prog_t_ant, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
						  $ld_total_disp_fecha_bsf   = $io_rcbsf->uf_convertir_monedabsf($ld_total_disp_fecha, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
						  $ld_total_porc_comprometer_bsf   = $io_rcbsf->uf_convertir_monedabsf($ld_total_porc_comprometer, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
						  $ld_total_porc_causado_bsf   = $io_rcbsf->uf_convertir_monedabsf($ld_total_porc_causado, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
						  $ld_total_porc_pagado_bsf   = $io_rcbsf->uf_convertir_monedabsf($ld_total_porc_pagado, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
						  
						  
						  
						  
						  $ld_total_pres_anual=number_format($ld_total_pres_anual,2,",",".");
						  $ld_total_programado=number_format($ld_total_programado,2,",",".");
						  $ld_total_compromiso=number_format($ld_total_compromiso,2,",",".");
						  $ld_total_causado=number_format($ld_total_causado,2,",",".");
						  $ld_total_pagado=number_format($ld_total_pagado,2,",",".");
						  $ld_total_prog_t_ant=number_format($ld_total_prog_t_ant,2,",",".");
						  $ld_total_disp_fecha=number_format($ld_total_disp_fecha,2,",",".");
						  $ld_total_porc_comprometer="";
						  $ld_total_porc_causado="";	
						  $ld_total_porc_pagado="";
							   
						  $la_data_tot[$z]=array('total'=>'<b>TOTALES Bs.</b>','pres_anual'=>$ld_total_pres_anual,'programado'=>$ld_total_programado,
												 'compromiso'=>$ld_total_compromiso,'causado'=>$ld_total_causado,'pagado'=>$ld_total_pagado,
												 'porc_comprometer'=>$ld_total_porc_comprometer,'porc_causado'=>$ld_total_porc_causado,
												 'porc_pagado'=>$ld_total_porc_pagado,'disp_trim_ant'=>$ld_total_prog_t_ant,
												 'disp_fecha'=>$ld_total_disp_fecha);
												 
						  $ld_total_pres_anual_bsf=number_format($ld_total_pres_anual_bsf,2,",",".");
						  $ld_total_programado_bsf=number_format($ld_total_programado_bsf,2,",",".");
						  $ld_total_compromiso_bsf=number_format($ld_total_compromiso_bsf,2,",",".");
						  $ld_total_causado_bsf=number_format($ld_total_causado_bsf,2,",",".");
						  $ld_total_pagado_bsf=number_format($ld_total_pagado_bsf,2,",",".");
						  $ld_total_prog_t_ant_bsf=number_format($ld_total_prog_t_ant_bsf,2,",",".");
						  $ld_total_disp_fecha_bsf=number_format($ld_total_disp_fecha_bsf,2,",",".");
						  $ld_total_porc_comprometer_bsf="";
						  $ld_total_porc_causado_bsf="";	
						  $ld_total_porc_pagado_bsf="";
						  
						  $la_data_tot_bsf[$z]=array('total'=>'<b>TOTALES BsF.</b>','pres_anual'=>$ld_total_pres_anual_bsf,'programado'=>$ld_total_programado_bsf,
												 'compromiso'=>$ld_total_compromiso_bsf,'causado'=>$ld_total_causado_bsf,'pagado'=>$ld_total_pagado_bsf,
												 'porc_comprometer'=>$ld_total_porc_comprometer_bsf,'porc_causado'=>$ld_total_porc_causado_bsf,
												 'porc_pagado'=>$ld_total_porc_pagado_bsf,'disp_trim_ant'=>$ld_total_prog_t_ant_bsf,
												 'disp_fecha'=>$ld_total_disp_fecha_bsf);
						
					 }
					  
					 
				}//if
			  }//for
            $io_encabezado=$io_pdf->openObject();
			uf_print_titulo_reporte($io_encabezado,$ls_programatica,$li_ano,$ls_mes,$ls_denestpro,$io_pdf);
            $io_titulo=$io_pdf->openObject();
			uf_print_titulo($io_titulo,$io_pdf);
		    $io_cabecera=$io_pdf->openObject();
			uf_print_cabecera($io_cabecera,$io_pdf);
			uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
			if($ls_tipoformato==1)
			{
		    	uf_print_pie_cabecera($la_data_tot,$io_pdf);
				
			}
			else
			{
				uf_print_pie_cabecera($la_data_tot,$io_pdf);
				uf_print_pie_cabecera($la_data_tot_bsf,$io_pdf);
			}
			$io_pdf->stopObject($io_encabezado);
			$io_pdf->stopObject($io_titulo);
			$io_pdf->stopObject($io_cabecera);
			}
			unset($la_data);
			unset($la_data_tot);
			if($li_i<$li_tot)
			{
			 $io_pdf->ezNewPage(); // Insertar una nueva página
			} 
		}//for				
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
	}//else
	unset($io_report);
	unset($io_funciones);
?> 