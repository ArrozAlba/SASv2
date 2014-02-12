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
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_titulo2,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 26/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,30,1000,30);
		$io_pdf->rectangle(10,480,990,110);
		$li_tm=$io_pdf->getTextWidth(16,$as_titulo);
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,550,16,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(16,$as_titulo2);
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,530,16,$as_titulo2); // Agregar el título
		$io_pdf->addText(10,40,10,'<b>Forma: 0405<b>'); // Agregar la Fecha
		$io_pdf->addText(920,550,10,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(920,540,10,''); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_titulo_reporte($io_encabezado,$as_programatica,$ai_ano,$as_mes,$as_denestpro,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 26/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->ezSetY(525);
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_nombre=$_SESSION["la_empresa"]["nombre"];
		$ls_nomorgads=$_SESSION["la_empresa"]["nomorgads"];
		$la_data=array(array('name'=>'<b>CODIGO DEL ENTE:     </b>'.'<b>'.$ls_codemp.'</b>'),
		               array('name'=>'<b>DENOMINACION:    </b>'.'<b>'.$ls_nombre.'</b>'));
		$la_columna=array('name'=>'','name'=>'','name'=>'');
		$la_config =array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 8,       // Tamaño de Letras
						 'titleFontSize' => 8, // Tamaño de Letras de los títulos
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
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_titulo
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 26/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->ezSetDy(-26); // para  el rectangulo 
		$io_pdf->addText(260,462,7,'<b>PROGRAMADO</b>'); // Agregar la hora
		$la_data=array(array('name1'=>'','name2'=>'',  
		                     'name3'=>'<b>EJECUTADO</b>','name4'=>''));
		$la_columna=array('name1'=>'','name2'=>'','name3'=>'','name4'=>'');
		$la_config =array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 7,       // Tamaño de Letras
						 'titleFontSize' => 7, // Tamaño de Letras de los títulos
						 'showLines'=>1,        // Mostrar Líneas
						 'shaded'=>0,           // Sombra entre líneas
						 'xPos'=>504,
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990,
						 'colGap'=>0,
						 'cols'=>array('name1'=>array('justification'=>'center','width'=>190),// Justificación y ancho de la columna
						               'name2'=>array('justification'=>'center','width'=>160),// Justificación y ancho de la columna
									   'name3'=>array('justification'=>'center','width'=>480),
									   'name4'=>array('justification'=>'center','width'=>160))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data=array(array('name1'=>'',
		                     'name2'=>'',
		                     'name3'=>'<b>MES</b>',  
		                     'name4'=>'<b>ACUMULADO</b>',
							 'name5'=>''));
		$la_columna=array('name1'=>'','name2'=>'','name3'=>'','name4'=>'','name5'=>'');
		$la_config =array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 7,       // Tamaño de Letras
						 'titleFontSize' => 7, // Tamaño de Letras de los títulos
						 'showLines'=>1,        // Mostrar Líneas
						 'shaded'=>0,           // Sombra entre líneas
						 'xPos'=>504,
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990,
						 'colGap'=>0,
						 'cols'=>array('name1'=>array('justification'=>'center','width'=>190),// Justificación y ancho de la columna
						               'name2'=>array('justification'=>'center','width'=>160),// Justificación y ancho de la columna
						               'name3'=>array('justification'=>'center','width'=>240),// Justificación y ancho de la columna
									   'name4'=>array('justification'=>'center','width'=>240),
									   'name5'=>array('justification'=>'center','width'=>160))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_titulo,'all');

	}// end function uf_print_titulo
	//--------------------------------------------------------------------------------------------------------------------------------	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($io_cabecera,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 26/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$la_data=array(array('cuenta'=>'<b>Código</b>','denominacion'=>'<b>Denominación</b>',
		                     'programado'=>'<b>Mes</b>','programado_acum'=>'<b>Acumulado</b>','compromiso'=>'<b>Compromiso</b>','causado'=>'<b>Causado</b>',
							 'pagado'=>'<b>Pagado</b>','porc_comprometer'=>'<b> Compromiso</b>','porc_causado'=>'<b>Causado</b>',
							 'porc_pagado'=>'<b>Pagado</b>','disp_trim_ant'=>'<b>Responsable</b>',
							 'disp_fecha'=>'<b>Revision Actualizada Proximo Mes</b>'));
		$la_columna=array('cuenta'=>'','denominacion'=>'','programado'=>'','programado_acum'=>'','compromiso'=>'','causado'=>'',
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
						 			   'programado'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'programado_acum'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
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
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 26/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
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
						 			   'programado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'programado_acum'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'compromiso'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'causado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'pagado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'porc_comprometer'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'porc_causado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'porc_pagado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'disp_trim_ant'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'disp_fecha'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		$la_columnas=array('cuenta'=>'<b></b>',
						   'denominacion'=>'<b></b>',
						   'programado'=>'<b></b>',
						   'programado_acum'=>'<b></b>',
						   'compromiso'=>'<b></b>',
						   'causado'=>'<b></b>',
						   'pagado'=>'<b></b>',
						   'porc_comprometer'=>'<b></b>',
						   'porc_causado'=>'<b></b>',
						   'porc_pagado'=>'<b></b>',
						   'disp_trim_ant'=>'<b></b>',
						   'disp_fecha'=>'<b></b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($la_data_tot,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private 
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 26/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>190),// Justificación y ancho de la columna
						 			   'programado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'programado_acum'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'compromiso'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'causado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'pagado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'porc_comprometer'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'porc_causado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'porc_pagado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'disp_trim_ant'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'disp_fecha'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		
		$la_columnas=array('total'=>'',
						   'programado'=>'',
						   'programado_acum'=>'',
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
		$io_function_report=new sigesp_spg_funciones_reportes();	
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
		$ls_tipoformato=$_GET["tipoformato"];
//-----------------------------------------------------------------------------------------------------------------------------
		global $ls_tipoformato;
		global $la_data_tot_bsf;
		global $la_data_tot;
		if($ls_tipoformato==1)
		{
			require_once("sigesp_spg_reporte_comparados_forma04_bsf.php");
		    $io_report = new sigesp_spg_reporte_comparados_forma04_bsf();
		}
		else
		{
			require_once("sigesp_spg_reporte_comparados_forma04.php");
		    $io_report = new sigesp_spg_reporte_comparados_forma04();
		}	
		require_once("../../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
		$io_rcbsf= new sigesp_c_reconvertir_monedabsf();
		$li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$li_redconmon=$_SESSION["la_empresa"]["redconmon"];
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		$ls_codestpro1_min  = $_GET["codestpro1"];
		$ls_codestpro2_min  = $_GET["codestpro2"];
		$ls_codestpro3_min  = $_GET["codestpro3"];
		$ls_codestpro1h_max = $_GET["codestpro1h"];
		$ls_codestpro2h_max = $_GET["codestpro2h"];
		$ls_codestpro3h_max = $_GET["codestpro3h"];
	    $ls_estclades       = "";
	    $ls_estclahas       = "";
		if($li_estmodest==1)
		{
			$ls_codestpro4_min = "00";
			$ls_codestpro5_min = "00";
			$ls_codestpro4h_max = "00";
			$ls_codestpro5h_max = "00";
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
					$ls_codestpro1  = trim($ls_codestpro1_min);
					$ls_codestpro2  = trim($ls_codestpro2_min);
					$ls_codestpro3  = trim($ls_codestpro3_min);
					$ls_codestpro4  = trim($ls_codestpro4_min);
					$ls_codestpro5  = trim($ls_codestpro5_min);
			  }
			}
			else
			{
					$ls_codestpro1  = trim($ls_codestpro1_min);
					$ls_codestpro2  = trim($ls_codestpro2_min);
					$ls_codestpro3  = trim($ls_codestpro3_min);
					$ls_codestpro4  = trim($ls_codestpro4_min);
					$ls_codestpro5  = trim($ls_codestpro5_min);
			}
			if(($ls_codestpro1h_max=="")||($ls_codestpro2h_max=="")||($ls_codestpro3h_max=="")||($ls_codestpro4h_max=="")||($ls_codestpro5h_max==""))
			{
			  if($io_function_report->uf_spg_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
																			 $ls_codestpro3h_max,$ls_codestpro4h_max,
																			 $ls_codestpro4h_max,$ls_estclahas))
			  {
					$ls_codestpro1h  = trim($ls_codestpro1h_max);
					$ls_codestpro2h  = trim($ls_codestpro2h_max);
					$ls_codestpro3h  = trim($ls_codestpro3h_max);
					$ls_codestpro4h  = trim($ls_codestpro4h_max);
					$ls_codestpro5h  = trim($ls_codestpro5h_max);
			  }
			}
			else
			{
					$ls_codestpro1h  = trim($ls_codestpro1h_max);
					$ls_codestpro2h  = trim($ls_codestpro2h_max);
					$ls_codestpro3h  = trim($ls_codestpro3h_max);
					$ls_codestpro4h  = trim($ls_codestpro4h_max);
					$ls_codestpro5h  = trim($ls_codestpro5h_max);
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
		
		$ls_cmbmes=$_GET["cmbmes"];
		$li_mes=substr($ls_cmbmes,0,2);
		$ldt_fecdes=$li_ano."-".$li_mes."-01";
		$ldt_ult_dia=$io_fecha->uf_last_day($li_mes,$li_ano);
		$fechas=$ldt_ult_dia;
		$ldt_fechas=$io_funciones->uf_convertirdatetobd($fechas);
		$ls_mes=$io_fecha->uf_load_nombre_mes($li_mes);
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
		$ls_titulo="<b>EJECUCIÓN FINANCIERA DE LOS PROYECTOS / ACCIONES CENTRALIZADAS DEL ENTE(FORMA 0405)</b>"; 
		$ls_titulo2 = "<b>DESDE "."01-".$li_mes."-".$li_ano."   HASTA ".$fechas;
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
     $lb_valido_proyectos=$io_report->uf_spg_reportes_comparados_forma0413_proyectos($ls_codestpro1,$ls_codestpro2,
	                                                                                 $ls_codestpro3,$ls_codestpro4,
																				     $ls_codestpro5,$ls_codestpro1h,
																	  			     $ls_codestpro2h,$ls_codestpro3h,
																	                 $ls_codestpro4h,$ls_codestpro5h,
																				     $ldt_fecdes,$ldt_fechas);
	 if($lb_valido_proyectos==false) // Existe algún error ó no hay registros
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
		$io_pdf->ezSetCmMargins(6.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_titulo2 ,$io_pdf); // Imprimimos el encabezado de la página
 	    $io_pdf->ezStartPageNumbers(980,40,10,'','',1); // Insertar el número de página
		$li_total=$io_report->dts_reporte->getRowCount("spg_cuenta");
		$ld_total_programada=0;
		$ld_total_programada_acumulado=0;
		$ld_total_compromiso=0;
		$ld_total_causado=0;
		$ld_total_pagado=0;
		$ld_total_compromiso_acumulado=0;
		$ld_total_causado_acumulado=0;
		$ld_total_pagado_acumulado=0;
		$ld_total_reprog_prox_mes=0;
		$ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
		$ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
		$ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
		$ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
		$ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
		for($z=1;$z<=$li_total;$z++)
		{
			$thisPageNum=$io_pdf->ezPageCount;
			$ls_programatica=$io_report->dts_reporte->data["programatica"][$z];
			$ls_estcla=substr($ls_programatica,-1);
		    $ls_codestpro1=substr($ls_programatica,0,25);
		    $ls_denestpro1="";
		    $lb_valido=$io_report->uf_spg_reporte_select_denestpro1($ls_codestpro1,$ls_denestpro1,$ls_estcla);
		    if($lb_valido)
		    {
			  $ls_denestpro1=$ls_denestpro1;
		    }
		    $ls_codestpro2=substr($ls_programatica,25,25);
		    if($lb_valido)
		    {
			  $ls_denestpro2="";
			  $lb_valido=$io_report->uf_spg_reporte_select_denestpro2($ls_codestpro1,$ls_codestpro2,$ls_denestpro2,$ls_estcla);
			  $ls_denestpro2=$ls_denestpro2;
		    }
		    $ls_codestpro3=substr($ls_programatica,50,25);
		    if($lb_valido)
		    {
			  $ls_denestpro3="";
			  $lb_valido=$io_report->uf_spg_reporte_select_denestpro3($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_denestpro3,$ls_estcla);
			  $ls_denestpro3=$ls_denestpro3;
		    }
			if($li_estmodest==2)
			{
				$ls_codestpro4=substr($ls_programatica,75,25);
				if($lb_valido)
				{
				  $ls_denestpro4="";
				  $lb_valido=$io_report->uf_spg_reporte_select_denestpro4($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_denestpro4,$ls_estcla);
				  $ls_denestpro4=trim($ls_denestpro4);
				}
				$ls_codestpro5=substr($ls_programatica,100,25);
				if($lb_valido)
				{
				  $ls_denestpro5="";
				  $lb_valido=$io_report->uf_spg_reporte_select_denestpro5($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_denestpro5,$ls_estcla);
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
			$ls_spg_cuenta=$io_report->dts_reporte->data["spg_cuenta"][$z];
			$ls_denominacion=$ls_denestpro;
			$ld_asignado=$io_report->dts_reporte->data["asignado"][$z];
			$ld_monto_programado=$io_report->dts_reporte->data["monto_programado"][$z];
			$ld_monto_acumulado=$io_report->dts_reporte->data["monto_acumulado"][$z];
			$ld_aumdismes=$io_report->dts_reporte->data["aumdis_mes"][$z];
			$ld_aumdisacum=$io_report->dts_reporte->data["aumdis_acumulado"][$z];
			$ld_monto_ejecutado=$io_report->dts_reporte->data["ejecutado_mes"][$z];
			$ld_ejecutado_acumulado=$io_report->dts_reporte->data["ejecutado_acum"][$z];
			$ld_reprog_prox_mes=$io_report->dts_reporte->data["reprog_prox_mes"][$z];
			$ld_comprometer=$io_report->dts_reporte->data["compromiso"][$z];
			$ld_causado=$io_report->dts_reporte->data["causado"][$z];
			$ld_pagado=$io_report->dts_reporte->data["pagado"][$z];
			$ld_comprometer_acumulado=$io_report->dts_reporte->data["compromiso_acumulado"][$z];
			$ld_causado_acumulado=$io_report->dts_reporte->data["causado_acumulado"][$z];
			$ld_pagado_acumulado=$io_report->dts_reporte->data["pagado_acumulado"][$z];
			
			$ld_pres_anual=$ld_monto_programado+$ld_aumdismes;  //programado de los meses
			$ld_programada_acumulado=$ld_monto_acumulado+$ld_aumdisacum;	//acumulado programado de los meses  
			$ld_monto_ejecutado=$ld_monto_ejecutado;   // monto ejecutado de los meses 
			$ld_ejecutado_acumulado=$ld_ejecutado_acumulado; // monto ejecutado acumulado de los meses 
			
			$ld_total_programada=$ld_total_programada+$ld_monto_programado;
			$ld_total_programada_acumulado=$ld_total_programada_acumulado+$ld_programada_acumulado;
			$ld_total_compromiso=$ld_total_compromiso+$ld_comprometer;
			$ld_total_causado=$ld_total_causado+$ld_causado;
			$ld_total_pagado=$ld_total_pagado+$ld_pagado;
			$ld_total_compromiso_acumulado=$ld_total_compromiso_acumulado+$ld_comprometer_acumulado;
			$ld_total_causado_acumulado=$ld_total_causado_acumulado+$ld_causado_acumulado;
			$ld_total_pagado_acumulado=$ld_total_pagado_acumulado+$ld_pagado_acumulado;
			$ld_total_reprog_prox_mes=$ld_total_reprog_prox_mes+$ld_reprog_prox_mes;
			
			$ld_pres_anual=number_format($ld_pres_anual,2,",",".");
			$ld_monto_programado=number_format($ld_monto_programado,2,",",".");
			$ld_monto_acumulado=number_format($ld_monto_acumulado,2,",",".");
			$ld_monto_ejecutado=number_format($ld_monto_ejecutado,2,",",".");
			$ld_ejecutado_acumulado=number_format($ld_ejecutado_acumulado,2,",",".");
		    $ld_comprometer=number_format($ld_comprometer,2,",",".");
		    $ld_causado=number_format($ld_causado,2,",",".");
		    $ld_pagado=number_format($ld_pagado,2,",",".");
		    $ld_comprometer_acumulado=number_format($ld_comprometer_acumulado,2,",",".");
		    $ld_causado_acumulado=number_format($ld_causado_acumulado,2,",",".");
		    $ld_pagado_acumulado=number_format($ld_pagado_acumulado,2,",",".");
			$ld_reprog_prox_mes=number_format($ld_reprog_prox_mes,2,",",".");
			

		    $la_data[$z]=array('cuenta'=>$ls_programatica,'denominacion'=>$ls_denominacion,
						 	   'programado'=>$ld_monto_programado,'programado_acum'=>$ld_monto_acumulado,
							   'compromiso'=>$ld_comprometer,'causado'=>$ld_causado,
							   'pagado'=>$ld_pagado,'porc_comprometer'=>$ld_comprometer_acumulado,
							   'porc_causado'=>$ld_causado_acumulado,'porc_pagado'=>$ld_pagado_acumulado,
							   'disp_trim_ant'=>'','disp_fecha'=>$ld_reprog_prox_mes);			

				 $ld_monto_programado=str_replace('.','',$ld_monto_programado);
				 $ld_monto_programado=str_replace(',','.',$ld_monto_programado);	
				 $ld_monto_acumulado=str_replace('.','',$ld_monto_acumulado);
				 $ld_monto_acumulado=str_replace(',','.',$ld_monto_acumulado);		
				 $ld_comprometer=str_replace('.','',$ld_comprometer);
				 $ld_comprometer=str_replace(',','.',$ld_comprometer);		
				 $ld_causado=str_replace('.','',$ld_causado);
				 $ld_causado=str_replace(',','.',$ld_causado);
				 $ld_pagado=str_replace('.','',$ld_pagado);
				 $ld_pagado=str_replace(',','.',$ld_pagado);
				 $ld_comprometer_acumulado=str_replace('.','',$ld_comprometer_acumulado);
				 $ld_comprometer_acumulado=str_replace(',','.',$ld_comprometer_acumulado);	
				 $ld_causado_acumulado=str_replace('.','',$ld_causado_acumulado);
				 $ld_causado_acumulado=str_replace(',','.',$ld_causado_acumulado);		
				 $ld_pagado_acumulado=str_replace('.','',$ld_pagado_acumulado);
				 $ld_pagado_acumulado=str_replace(',','.',$ld_pagado_acumulado);	
				 $ld_reprog_prox_mes=str_replace('.','',$ld_reprog_prox_mes);
				 $ld_reprog_prox_mes=str_replace(',','.',$ld_reprog_prox_mes);
			
			if($z==$li_total)
			{
				 if($ls_tipoformato==1)
				 {
				 
				  $ld_total_programada=number_format($ld_total_programada,2,",",".");
				  $ld_total_programada_acumulado=number_format($ld_total_programada_acumulado,2,",",".");
				  $ld_total_compromiso=number_format($ld_total_compromiso,2,",",".");
				  $ld_total_causado=number_format($ld_total_causado,2,",",".");
				  $ld_total_pagado=number_format($ld_total_pagado,2,",",".");
				  $ld_total_compromiso_acumulado=number_format($ld_total_compromiso_acumulado,2,",",".");
				  $ld_total_causado_acumulado=number_format($ld_total_causado_acumulado,2,",",".");
				  $ld_total_pagado_acumulado=number_format($ld_total_pagado_acumulado,2,",",".");
				  $ld_total_reprog_prox_mes=number_format($ld_total_reprog_prox_mes,2,",",".");
				 
				  $la_data_tot[$z]=array('total'=>'<b>TOTALES BsF.</b>','programado'=>$ld_total_programada,
										 'programado_acum'=>$ld_total_programada_acumulado,'compromiso'=>$ld_total_compromiso,
										 'causado'=>$ld_total_causado,'pagado'=>$ld_total_pagado,'porc_comprometer'=>$ld_total_compromiso_acumulado,
										 'porc_causado'=>$ld_total_causado_acumulado,'porc_pagado'=>$ld_total_pagado_acumulado,
										 'disp_trim_ant'=>'','disp_fecha'=>$ld_total_reprog_prox_mes);
				 }
				 else
				 {
				 
				  $ld_total_programada_bsf   = $io_rcbsf->uf_convertir_monedabsf($ld_total_programada, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
				  $ld_total_programada_acumulado_bsf    = $io_rcbsf->uf_convertir_monedabsf($ld_total_programada_acumulado, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
				  $ld_total_compromiso_bsf= $io_rcbsf->uf_convertir_monedabsf($ld_total_compromiso, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
  				  $ld_total_causado_bsf= $io_rcbsf->uf_convertir_monedabsf($ld_total_causado, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
				  $ld_total_pagado_bsf= $io_rcbsf->uf_convertir_monedabsf($ld_total_pagado, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
				  $ld_total_compromiso_acumulado_bsf= $io_rcbsf->uf_convertir_monedabsf($ld_total_compromiso_acumulado, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
				  $ld_total_causado_acumulado_bsf= $io_rcbsf->uf_convertir_monedabsf($ld_total_causado_acumulado, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
				  $ld_total_pagado_acumulado_bsf= $io_rcbsf->uf_convertir_monedabsf($ld_total_pagado_acumulado, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
				  $ld_total_reprog_prox_mes_bsf= $io_rcbsf->uf_convertir_monedabsf($ld_total_reprog_prox_mes, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
				 
				  $ld_total_programada_bsf=number_format($ld_total_programada_bsf,2,",",".");
				  $ld_total_programada_acumulado_bsf=number_format($ld_total_programada_acumulado_bsf,2,",",".");
				  $ld_total_compromiso_bsf=number_format($ld_total_compromiso_bsf,2,",",".");
				  $ld_total_causado_bsf=number_format($ld_total_causado_bsf,2,",",".");
				  $ld_total_pagado_bsf=number_format($ld_total_pagado_bsf,2,",",".");
				  $ld_total_causado_acumulado_bsf=number_format($ld_total_causado_acumulado_bsf,2,",",".");
				  $ld_total_compromiso_acumulado_bsf=number_format($ld_total_compromiso_acumulado_bsf,2,",",".");
				  $ld_total_pagado_acumulado_bsf=number_format($ld_total_pagado_acumulado_bsf,2,",",".");
				  $ld_total_reprog_prox_mes_bsf=number_format($ld_total_reprog_prox_mes_bsf,2,",",".");
				 
				 $la_data_tot_bsf[$z]=array('total'=>'<b>TOTALES BsF.</b>','programado'=>$ld_total_programada_bsf,
										 'programado_acum'=>$ld_total_programada_acumulado_bsf,'compromiso'=>$ld_total_compromiso_bsf,
										 'causado'=>$ld_total_causado_bsf,'pagado'=>$ld_total_pagado_bsf,'porc_comprometer'=>$ld_total_compromiso_acumulado_bsf,
										 'porc_causado'=>$ld_total_causado_acumulado_bsf,'porc_pagado'=>$ld_total_pagado_acumulado_bsf,
										 'disp_trim_ant'=>'','disp_fecha'=>$ld_total_reprog_prox_mes_bsf);
				 
				  $ld_total_programada=number_format($ld_total_programada,2,",",".");
				  $ld_total_programada_acumulado=number_format($ld_total_programada_acumulado,2,",",".");
				  $ld_total_compromiso=number_format($ld_total_compromiso,2,",",".");
				  $ld_total_causado=number_format($ld_total_causado,2,",",".");
				  $ld_total_pagado=number_format($ld_total_pagado,2,",",".");
				  $ld_total_compromiso_acumulado=number_format($ld_total_compromiso_acumulado,2,",",".");
				  $ld_total_causado_acumulado=number_format($ld_total_causado_acumulado,2,",",".");
				  $ld_total_pagado_acumulado=number_format($ld_total_pagado_acumulado,2,",",".");
				  $ld_total_reprog_prox_mes=number_format($ld_total_reprog_prox_mes,2,",",".");
						   
				 
				  $la_data_tot[$z]=array('total'=>'<b>TOTALES Bs.</b>','programado'=>$ld_total_programada,
										 'programado_acum'=>$ld_total_programada_acumulado,'compromiso'=>$ld_total_compromiso,
										 'causado'=>$ld_total_causado,'pagado'=>$ld_total_pagado,'porc_comprometer'=>$ld_total_compromiso_acumulado,
										 'porc_causado'=>$ld_total_causado_acumulado,'porc_pagado'=>$ld_total_pagado_acumulado,
										 'disp_trim_ant'=>'','disp_fecha'=>$ld_total_reprog_prox_mes);
		         }
		   }//if
		 }//for
		$io_encabezado=$io_pdf->openObject();
		uf_print_titulo_reporte($io_encabezado,$ls_programatica,$li_ano,'',$ls_denestpro,$io_pdf);
		$io_titulo=$io_pdf->openObject();
		uf_print_titulo($io_titulo,$io_pdf);
		$io_cabecera=$io_pdf->openObject();
		uf_print_cabecera($io_cabecera,$io_pdf);
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		if($ls_tipoformato==1)
		{
			uf_print_pie_cabecera($la_data_tot,$io_pdf);//Bs.
		}
		else
		{
			uf_print_pie_cabecera($la_data_tot,$io_pdf);//Bs.
			//uf_print_pie_cabecera($la_data_tot_bsf,$io_pdf);//BsF.
		}
		$io_pdf->stopObject($io_encabezado);
		$io_pdf->stopObject($io_titulo);
		$io_pdf->stopObject($io_cabecera);
		unset($la_data);
		unset($la_data_tot);
		if($z<$li_total)
		{
		 $io_pdf->ezNewPage(); // Insertar una nueva página
		} 
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