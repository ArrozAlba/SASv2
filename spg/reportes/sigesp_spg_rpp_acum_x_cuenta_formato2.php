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
	function uf_print_encabezado_pagina($as_titulo,$as_titulo1,&$io_pdf)
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
		// Fecha Creación: 10/05/2006 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,30,1000,30);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],10,530,$_SESSION["ls_width"],$_SESSION["ls_height"]);
		
		$li_tm=$io_pdf->getTextWidth(14,$as_titulo);
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,540,14,$as_titulo); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(14,$as_titulo1);
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,520,14,$as_titulo1); // Agregar el título
		
		$io_pdf->addText(900,550,10,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(900,540,10,date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_titulo_reporte($io_encabezado,$as_programatica,$as_denestpro,&$io_pdf)
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
		// Fecha Creación: 07/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		///$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSetY(520);
        //$io_pdf->ezSetDy(-20);
		$la_data=array(array('name'=>'<b>Programatica    </b>'.'<b>'.$as_programatica.'</b>'),
		               array('name'=>''.'<b>'.$as_denestpro.'</b>'));
		$la_columna=array('name'=>'','name'=>'');
		$la_config =array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 8,       // Tamaño de Letras
						 'titleFontSize' => 8, // Tamaño de Letras de los títulos
						 'showLines'=>0,        // Mostrar Líneas
						 'shaded'=>0,           // Sombra entre líneas
						 'xPos'=>260,//65
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>900,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>500),
						               'name'=>array('justification'=>'left','width'=>500)));
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($io_cabecera,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 10/05/2006 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSetY(480);
		$la_data=array(array('cuenta'=>'<b>Cuenta</b>','denominacion'=>'<b>Denominación</b>','asignado'=>'<b>Asignado</b>',
		                     'aumento'=>'<b>Aumento</b>','disminución'=>'<b>Disminución</b>',
							 'montoactualizado'=>'<b>Monto Actualizado</b>','precomprometido'=>'<b>Pre Comprometido</b>',
							 'comprometido'=>'<b>Comprometido</b>','saldoporcomprometer'=>'<b>Saldo por Comprometer</b>',
							 'causado'=>'<b>Causado</b>','pagado'=>'<b>Pagado</b>','porpagar'=>'<b>Por Pagar</b>'));
		$la_columna=array('cuenta'=>'','denominacion'=>'','asignado'=>'','aumento'=>'','disminución'=>'','montoactualizado'=>'',
		                  'precomprometido'=>'','comprometido'=>'','saldoporcomprometer'=>'','causado'=>'','pagado'=>'',
						  'porpagar'=>'');
		$la_config=array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la 
						 			   'denominacion'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la 
						 			   'asignado'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la 
						 			   'aumento'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la 
									   'disminución'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la 
									   'montoactualizado'=>array('justification'=>'center','width'=>80), // Justificación y ancho de
									   'precomprometido'=>array('justification'=>'center','width'=>90), // Justificación y ancho de 
									   'comprometido'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la 
									   'saldoporcomprometer'=>array('justification'=>'center','width'=>80), // Justificación y ancho
									   'causado'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la 
									   'pagado'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la 
									   'porpagar'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la 
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_cabecera,'all');
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 10/05/2006 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la 
						 			   'denominacion'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la 
						 			   'asignado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
						 			   'aumento'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
									   'disminución'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
									   'montoactualizado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de 
									   'precomprometido'=>array('justification'=>'right','width'=>90), // Justificación y ancho de 
									   'comprometido'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
									   'saldoporcomprometer'=>array('justification'=>'right','width'=>80), // Justificación y ancho 
									   'causado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
									   'pagado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'porpagar'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la 
		$la_columnas=array('cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Denominación</b>',
						   'asignado'=>'<b>Asignado</b>',
						   'aumento'=>'<b>Aumento</b>',
						   'disminución'=>'<b>Disminución</b>',
						   'montoactualizado'=>'<b>Monto Actualizado</b>',
						   'precomprometido'=>'<b>Pre Comprometido</b>',
						   'comprometido'=>'<b>Comprometido</b>',
						   'saldoporcomprometer'=>'<b>Saldo por Comprometer</b>',
						   'causado'=>'<b>Causado</b>',
						   'pagado'=>'<b>Pagado</b>',
						   'porpagar'=>'<b>Por Pagar</b>');
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
		// Fecha Creación: 10/05/2006 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>180), // Justificación y ancho de la columna
						               'asignado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
						 			   'aumento'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
									   'disminución'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
									   'montoactualizado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de 
									   'precomprometido'=>array('justification'=>'right','width'=>90), // Justificación y ancho de 
									   'comprometido'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
									   'saldoporcomprometer'=>array('justification'=>'right','width'=>80), // Justificación y ancho 
									   'causado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la 
									   'pagado'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'porpagar'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la 
		$la_columnas=array('total'=>'',
		                   'asignado'=>'',
						   'aumento'=>'',
						   'disminución'=>'',
						   'montoactualizado'=>'',
						   'precomprometido'=>'',
						   'comprometido'=>'',
						   'saldoporcomprometer'=>'',
						   'causado'=>'',
						   'pagado'=>'',
						   'porpagar'=>'');
		$io_pdf->ezTable($la_data_tot,$la_columnas,'',$la_config);
	}// end function uf_print_pie_cabecera
//------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("sigesp_spg_funciones_reportes.php");
		$io_function_report = new sigesp_spg_funciones_reportes();
		require_once("sigesp_spg_reporte_nuevos.php");
		$io_report = new sigesp_spg_reporte_nuevos();
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();			
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
//--------------------------------------------------  Parámetros para Filtar el Reporte  --------------------------------------
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
		$ls_loncodestpro1   = $_SESSION["la_empresa"]["loncodestpro1"];
		$ls_loncodestpro2   = $_SESSION["la_empresa"]["loncodestpro2"];
		$ls_loncodestpro3   = $_SESSION["la_empresa"]["loncodestpro3"];
		$ls_loncodestpro4   = $_SESSION["la_empresa"]["loncodestpro4"];
		$ls_loncodestpro5   = $_SESSION["la_empresa"]["loncodestpro5"];
		if($li_estmodest==1)
		{
			$ls_codestpro4_min =  "0000000000000000000000000";
			$ls_codestpro5_min =  "0000000000000000000000000";
			$ls_codestpro4h_max = "0000000000000000000000000";
			$ls_codestpro5h_max = "0000000000000000000000000";
			if(($ls_codestpro1_min=="")||($ls_codestpro2_min=="")||($ls_codestpro3_min==""))
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
			if(($ls_codestpro1h_max=="")||($ls_codestpro2h_max=="")||($ls_codestpro3h_max==""))
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
		elseif($li_estmodest==2)
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
			    $ls_codestpro1_min  = $io_funciones->uf_cerosizquierda($ls_codestpro1_min,20);
			}
			if(($ls_codestpro2_min=='**') ||($ls_codestpro2_min==''))
			{
				$ls_codestpro2_min='';
			}
			else
			{
				$ls_codestpro2_min  = $io_funciones->uf_cerosizquierda($ls_codestpro2_min,6);
			
			}
			if(($ls_codestpro3_min=='**')||($ls_codestpro3_min==''))
			{
				$ls_codestpro3_min='';
			}
			else
			{
			
				$ls_codestpro3_min  = $io_funciones->uf_cerosizquierda($ls_codestpro3_min,3);
			}
			if(($ls_codestpro4_min=='**') ||($ls_codestpro4_min==''))
			{
				$ls_codestpro4_min='';
			}
			else
			{
				$ls_codestpro4_min  = $io_funciones->uf_cerosizquierda($ls_codestpro4_min,2);
	
			
			}
			if(($ls_codestpro5_min=='**') ||($ls_codestpro5_min==''))
			{
				$ls_codestpro5_min='';
			}
			else
			{
				$ls_codestpro5_min  = $io_funciones->uf_cerosizquierda($ls_codestpro5_min,2);
			}
			
			
			if(($ls_codestpro1h_max=='**')||($ls_codestpro1h_max==''))
			{
				$ls_codestpro1h_max='';
			}
			else
			{
				$ls_codestpro1h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro1h_max,20);
			}
			if(($ls_codestpro2h_max=='**') ||($ls_codestpro2h_max==''))
			{
				$ls_codestpro2h_max='';
			}else
			{
				$ls_codestpro2h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro2h_max,6);
			}
			if(($ls_codestpro3h_max=='**') ||($ls_codestpro3h_max==''))
			{
				$ls_codestpro3h_max='';
			}else
			{
				$ls_codestpro3h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro3h_max,3);
			}
			if(($ls_codestpro4h_max=='**')  ||($ls_codestpro4h_max==''))
			{
				$ls_codestpro4h_max='';
			}else
			{
				$ls_codestpro4h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro4h_max,2);
			}
			if(($ls_codestpro5h_max=='**')  || ($ls_codestpro5h_max==''))
			{
				$ls_codestpro5h_max='';
			}else
			{
				$ls_codestpro5h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro5h_max,2);
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
		
		$ls_cmbmesdes = $_GET["cmbmesdes"];
		$ldt_fecini=$li_ano."-".$ls_cmbmesdes."-01";
		$ldt_fecini_rep="01/".$ls_cmbmesdes."/".$li_ano;
		$ls_cmbmeshas = $_GET["cmbmeshas"];
		$ls_mes=$ls_cmbmeshas;
		$ls_ano=$li_ano;
		$fecfin=$io_fecha->uf_last_day($ls_mes,$ls_ano);
		$ldt_fecfin=$io_funciones->uf_convertirdatetobd($fecfin);
		
		$cmbnivel=$_GET["cmbnivel"];
		if($cmbnivel=="s1")
		{
          $ls_cmbnivel="1";
		}
		else
		{
          $ls_cmbnivel=$cmbnivel;
		}
        $ls_subniv=$_GET["checksubniv"];
		if($ls_subniv==1)
		{
		  $lb_subniv=true;
		}
		else
		{
		  $lb_subniv=false;
		}
		$ls_programatica_desde=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
		$ls_programatica_hasta=$ls_codestpro1h.$ls_codestpro2h.$ls_codestpro3h.$ls_codestpro4h.$ls_codestpro5h;
		if($li_estmodest==1)
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
			$ls_programatica_desde1=substr($ls_codestpro1,-2).substr($ls_codestpro2,-2).substr($ls_codestpro3,-2).substr($ls_codestpro4,-2).substr($ls_codestpro5,-2);
			$ls_programatica_hasta1=substr($ls_codestpro1h,-2).substr($ls_codestpro2h,-2).substr($ls_codestpro3h,-2).substr($ls_codestpro4h,-2).substr($ls_codestpro5h,-2);
		}
		
	    $ls_cuentades_min=$_GET["txtcuentades"];
	    $ls_cuentahas_max=$_GET["txtcuentahas"];
		if($ls_cuentades_min=="")
		{
		   if($io_function_report->uf_spg_reporte_select_min_cuenta($ls_cuentades_min))
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
		   if($io_function_report->uf_spg_reporte_select_max_cuenta($ls_cuentahas_max))
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
		/////////////////////////////////         SEGURIDAD               ///////////////////////////////////
		$ls_desc_event="Solicitud de Reporte Acumulado por Cuentas Formato 2 desde la fecha ".$ldt_fecini_rep." hasta ".$fecfin." ,Desde la programatica ".$ls_programatica_desde."  hasta ".$ls_programatica_hasta;
		$io_function_report->uf_load_seguridad_reporte("SPG","sigesp_spg_r_acum_x_cuentas_formato2.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               ///////////////////////////////////
	//----------------------------------------------------  Parámetros del encabezado  ---------------------------------------------
		$ls_titulo=" <b> ACUMULADO POR CUENTAS FORMATO 2 DESDE FECHA  ".$ldt_fecini_rep."  HASTA  </b>"."<b>".$fecfin."</b>";  
		$ls_titulo1=" <b> DESDE LA PROGRAMATICA  ".$ls_programatica_desde1."  HASTA  </b>"."<b>".$ls_programatica_hasta1."</b>";  
    //------------------------------------------------------------------------------------------------------------------------------
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
	// Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )
	$lb_valido=$io_report->uf_spg_reporte_select_programatica_acumulado_formato2($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
	                                                 $ls_codestpro4,$ls_codestpro5,$ls_codestpro1h,$ls_codestpro2h,
	                                                 $ls_codestpro3h,$ls_codestpro4h,$ls_codestpro5h,$ldt_fecini,
											         $ldt_fecfin,$ls_cmbnivel,$lb_subniv,&$ai_MenorNivel,$ls_cuentades,
											         $ls_cuentahas,$ls_codfuefindes,$ls_codfuefinhas,&$rs_data,
													 $ls_estclades,$ls_estclahas);
	
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
		$io_pdf->ezSetCmMargins(5.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_titulo1,$io_pdf); // Imprimimos el encabezado de la página
        $io_pdf->ezStartPageNumbers(980,40,10,'','',1); // Insertar el número de página
		$li_total=$row=$io_report->SQL->num_rows($rs_data);
		$li=0;
		while($row=$io_report->SQL->fetch_row($rs_data))
		{
			$li=$li+1;
			$ld_total_asignado=0;
			$ld_total_aumento=0;
			$ld_total_disminucion=0;
			$ld_total_monto_actualizado=0;
			$ld_total_compromiso=0;
			$ld_total_precompromiso=0;
			$ld_total_compromiso=0;
			$ld_total_saldo_comprometer=0;
			$ld_total_causado=0;
			$ld_total_pagado=0;
			$ld_total_por_paga=0;
			$thisPageNum=$io_pdf->ezPageCount;
			$ls_programatica=$row["programatica"];
		    $ls_estcla=substr($ls_programatica,-1);
		    $ls_codestpro1=substr($ls_programatica,0,25);
		    $ls_denestpro1="";
		    $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro1($ls_codestpro1,$ls_denestpro1,$ls_estcla);
		    if($lb_valido)
		    {
			  $ls_denestpro1=$ls_denestpro1;
		    }
		    $ls_codestpro2=substr($ls_programatica,25,25);
		    if($lb_valido)
		    {
			  $ls_denestpro2="";
			  $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro2($ls_codestpro1,$ls_codestpro2,$ls_denestpro2,$ls_estcla);
			  $ls_denestpro2=$ls_denestpro2;
		    }
		    $ls_codestpro3=substr($ls_programatica,50,25);
		    if($lb_valido)
		    {
			  $ls_denestpro3="";
			  $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro3($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_denestpro3,$ls_estcla);
			  $ls_denestpro3=$ls_denestpro3;
		    }
			if($li_estmodest==2)
			{
				$ls_codestpro4=substr($ls_programatica,75,25);
				if($lb_valido)
				{
				  $ls_denestpro4="";
				  $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro4($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_denestpro4,$ls_estcla);
				  $ls_denestpro4=$ls_denestpro4;
				}
				$ls_codestpro5=substr($ls_programatica,100,25);
				if($lb_valido)
				{
				  $ls_denestpro5="";
				  $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro5($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_denestpro5,$ls_estcla);
				  $ls_denestpro5=$ls_denestpro5;
				}
			    $ls_denestpro=$ls_denestpro1." , ".$ls_denestpro2." , ".$ls_denestpro3." , ".$ls_denestpro4." , ".$ls_denestpro5;
				$ls_programatica=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2)."-".substr($ls_codestpro3,-$ls_loncodestpro3)."-".substr($ls_codestpro4,-$ls_loncodestpro4)."-".substr($ls_codestpro5,-$ls_loncodestpro5);
			}
			else
			{
			    $ls_denestpro=$ls_denestpro1." , ".$ls_denestpro2." , ".$ls_denestpro3;
				$ls_programatica=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2)."-".substr($ls_codestpro3,-$ls_loncodestpro3);
			}
    		$lb_valido=$io_report->uf_spg_reporte_acumulado_cuentas($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
	                                                                $ls_codestpro5,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
	                                                                $ls_codestpro4,$ls_codestpro5,$ldt_fecini,$ldt_fecfin,$ls_cmbnivel,
								                                    $lb_subniv,$ai_MenorNivel,$ls_cuentades,$ls_cuentahas,
														            $ls_codfuefindes,$ls_codfuefinhas,$ls_estclades,$ls_estclahas);		
		    if($lb_valido)
			{
				$li_tot=$io_report->dts_reporte->getRowCount("spg_cuenta");
				for($z=1;$z<=$li_tot;$z++)
				{
					  $thisPageNum=$io_pdf->ezPageCount;
					  $ls_spg_cuenta=trim($io_report->dts_reporte->data["spg_cuenta"][$z]);
					  $ls_denominacion=trim($io_report->dts_reporte->data["denominacion"][$z]);
					  $ls_nivel=$io_report->dts_reporte->data["nivel"][$z];
					  $ld_asignado=$io_report->dts_reporte->data["asignado"][$z];
					  $ld_aumento=$io_report->dts_reporte->data["aumento"][$z];
					  $ld_disminucion=$io_report->dts_reporte->data["disminucion"][$z];
					  $ld_precompromiso=$io_report->dts_reporte->data["precompromiso"][$z];
					  $ld_compromiso=$io_report->dts_reporte->data["compromiso"][$z];
					  $ld_causado=$io_report->dts_reporte->data["causado"][$z];
					  $ld_pagado=$io_report->dts_reporte->data["pagado"][$z];
					  $ld_monto_actualizado=$io_report->dts_reporte->data["monto_actualizado"][$z];
					  $ld_saldo_comprometer=$io_report->dts_reporte->data["saldo_comprometer"][$z];
					  $ld_por_paga=$io_report->dts_reporte->data["por_pagar"][$z];
					  $ls_status=$io_report->dts_reporte->data["status"][$z];
					  if($ls_nivel==1)
					  {
						  $ld_total_asignado=$ld_total_asignado+$ld_asignado;
						  $ld_total_aumento=$ld_total_aumento+$ld_aumento;
						  $ld_total_disminucion=$ld_total_disminucion+$ld_disminucion;
						  $ld_total_monto_actualizado=$ld_total_monto_actualizado+$ld_monto_actualizado;
						  $ld_total_precompromiso=$ld_total_precompromiso+$ld_precompromiso;
						  $ld_total_compromiso=$ld_total_compromiso+$ld_compromiso;
						  $ld_total_saldo_comprometer=$ld_total_saldo_comprometer+$ld_saldo_comprometer;
						  $ld_total_causado=$ld_total_causado+$ld_causado;
						  $ld_total_pagado=$ld_total_pagado+$ld_pagado;
						  $ld_total_por_paga=$ld_total_por_paga+$ld_por_paga;
					  }
					  $ld_asignado=number_format($ld_asignado,2,",",".");
					  $ld_aumento=number_format($ld_aumento,2,",",".");
					  $ld_disminucion=number_format($ld_disminucion,2,",",".");
					  $ld_monto_actualizado=number_format($ld_monto_actualizado,2,",",".");
					  $ld_precompromiso=number_format($ld_precompromiso,2,",",".");
					  $ld_compromiso=number_format($ld_compromiso,2,",",".");
					  $ld_saldo_comprometer=number_format($ld_saldo_comprometer,2,",",".");
					  $ld_causado=number_format($ld_causado,2,",",".");
					  $ld_pagado=number_format($ld_pagado,2,",",".");
					  $ld_por_paga=number_format($ld_por_paga,2,",",".");
					
					  $la_data[$z]=array('cuenta'=>$ls_spg_cuenta,'denominacion'=>$ls_denominacion,'asignado'=>$ld_asignado,
										  'aumento'=>$ld_aumento,'disminución'=>$ld_disminucion,'montoactualizado'=>$ld_monto_actualizado,
										  'precomprometido'=>$ld_precompromiso,'comprometido'=>$ld_compromiso,
										  'saldoporcomprometer'=>$ld_saldo_comprometer,'causado'=>$ld_causado,
										  'pagado'=>$ld_pagado,'porpagar'=>$ld_por_paga);

					 $ld_asignado=str_replace('.','',$ld_asignado);
					 $ld_asignado=str_replace(',','.',$ld_asignado);		
					 $ld_aumento=str_replace('.','',$ld_aumento);
					 $ld_aumento=str_replace(',','.',$ld_aumento);		
					 $ld_disminucion=str_replace('.','',$ld_disminucion);
					 $ld_disminucion=str_replace(',','.',$ld_disminucion);		
					 $ld_monto_actualizado=str_replace('.','',$ld_monto_actualizado);
					 $ld_monto_actualizado=str_replace(',','.',$ld_monto_actualizado);	
					 $ld_precompromiso=str_replace('.','',$ld_precompromiso);
					 $ld_precompromiso=str_replace(',','.',$ld_precompromiso);		
					 $ld_compromiso=str_replace('.','',$ld_compromiso);
					 $ld_compromiso=str_replace(',','.',$ld_compromiso);		
					 $ld_saldo_comprometer=str_replace('.','',$ld_saldo_comprometer);
					 $ld_saldo_comprometer=str_replace(',','.',$ld_saldo_comprometer);		
					 $ld_causado=str_replace('.','',$ld_causado);
					 $ld_causado=str_replace(',','.',$ld_causado);
					 $ld_pagado=str_replace('.','',$ld_pagado);
					 $ld_pagado=str_replace(',','.',$ld_pagado);
					 $ld_por_paga=str_replace('.','',$ld_por_paga);
					 $ld_por_paga=str_replace(',','.',$ld_por_paga);
							
					if($z==$li_tot)
					{
					  $ld_total_asignado=number_format($ld_total_asignado,2,",",".");
					  $ld_total_aumento=number_format($ld_total_aumento,2,",",".");
					  $ld_total_disminucion=number_format($ld_total_disminucion,2,",",".");
					  $ld_total_monto_actualizado=number_format($ld_total_monto_actualizado,2,",",".");
					  $ld_total_precompromiso=number_format($ld_total_precompromiso,2,",",".");
					  $ld_total_compromiso=number_format($ld_total_compromiso,2,",",".");
					  $ld_total_saldo_comprometer=number_format($ld_total_saldo_comprometer,2,",",".");
					  $ld_total_causado=number_format($ld_total_causado,2,",",".");
					  $ld_total_pagado=number_format($ld_total_pagado,2,",",".");
					  $ld_total_por_paga=number_format($ld_total_por_paga,2,",",".");
			 
					  $la_data_tot[$z]=array('total'=>'<b>TOTAL</b>','asignado'=>$ld_total_asignado,'aumento'=>$ld_total_aumento,
											 'disminución'=>$ld_total_disminucion,'montoactualizado'=>$ld_total_monto_actualizado,
											 'precomprometido'=>$ld_total_precompromiso,'comprometido'=>$ld_total_compromiso,
											 'saldoporcomprometer'=>$ld_total_saldo_comprometer,'causado'=>$ld_total_causado,
											 'pagado'=>$ld_total_pagado,'porpagar'=>$ld_total_por_paga);
					}//if
				}//for
				$io_encabezado=$io_pdf->openObject();
				uf_print_titulo_reporte($io_encabezado,$ls_programatica,$ls_denestpro,$io_pdf);
				$io_cabecera=$io_pdf->openObject();
				uf_print_cabecera($io_cabecera,$io_pdf);
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				uf_print_pie_cabecera($la_data_tot,$io_pdf);
				$io_pdf->stopObject($io_encabezado);
				$io_pdf->stopObject($io_cabecera);
				unset($la_data);
				unset($la_data_tot);
		    }//if
			if($li<$li_total)
			{
			 $io_pdf->ezNewPage(); // Insertar una nueva página
			} 
		}//while			
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
?> 