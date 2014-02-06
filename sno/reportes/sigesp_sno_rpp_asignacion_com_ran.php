<?php
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
	ini_set('memory_limit','256M');
	ini_set('max_execution_time','0');

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo, $as_desnom,$as_periodo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 30/07/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Generó el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_asignacion_comp_ran.php",$ls_descripcion,$ls_codnom);		
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_desnom,$as_periodo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 30/07/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,735,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,723,11,$as_periodo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(10,$as_desnom);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,710,10,$as_desnom); // Agregar el título
		$io_pdf->addText(512,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(518,743,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_titulo($as_tiutlo,&$io_pdf)
	{
		$la_data[1]=array('denominacion'=>'<b>'.$as_tiutlo.'</b>');
		$la_columna=array('denominacion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla				         
						 'cols'=>array('denominacion'=>array('justification'=>'center','width'=>500))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_titulo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($ls_denominacion,$alineacion,$linea,$color,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 18/07/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('denominacion'=>'<b>'.$ls_denominacion.'</b>');
		$la_columna=array('denominacion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>$linea, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla				         
						 'cols'=>array('denominacion'=>array('justification'=>$alineacion,'width'=>500))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle de la nómina
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 18/07/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('total'=>'<b>Nro Personas</b>',
						   'monto'=>'<b>Monto</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('total'=>array('justification'=>'center','width'=>200), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'center','width'=>300))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_print_totales($as_totalper,$as_submonto,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle de la nómina
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 06/08/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-10);
		$la_data[1]=array('total'=>'<b>Total General de Personas:</b>  '.$as_totalper,
						  'monto'=>'<b>Total General de Monto:  </b>   '.$as_submonto);
		$la_columnas=array('total'=>'',
						   'monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla				         
						 'cols'=>array('total'=>array('justification'=>'center','width'=>200), 						 			   
									   'monto'=>array('justification'=>'center','width'=>300))); 
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_totales
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_sno_class_report.php");
	$io_report=new sigesp_sno_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	$ls_titulo="<b>Asignaciones por Componente y Rango</b>";
	$ls_periodo="<b>Período Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper."</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codconcdes=$io_fun_nomina->uf_obtenervalor_get("codcomdes","");
	$ls_codconchas=$io_fun_nomina->uf_obtenervalor_get("codcomhas","");	
	$ls_codrandes=$io_fun_nomina->uf_obtenervalor_get("codrandes","");
	$ls_codranhas=$io_fun_nomina->uf_obtenervalor_get("codranhas","");	
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("subnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("subnomhas","");		//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_componentes_rangos($ls_codconcdes,$ls_codconchas,$ls_codrandes,$ls_codranhas,'RANGO'); // Cargar el DS con los datos de la cabecera del reporte
	}
	if(($lb_valido==false) || ($io_report->rs_data->RecordCount()==0)) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else  // Imprimimos el reporte
	{
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_desnom,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página	
		//PARA LOS REGISTROS ANTES DEL 13/07/1995
		uf_print_titulo('Antes del 13 de Julio de 1995',&$io_pdf);		
		$li_totrow=$io_report->rs_data->RecordCount();
		$ls_codcomaux=""; 
		$ls_codranaux="";
		$ls_codcataux="";
		$sub_total=0;
		$subtotalper=0;
		while(!$io_report->rs_data->EOF)
		{			
			$ls_codcom=$io_report->rs_data->fields["codcom"]; 
			$ls_dencom=$io_report->rs_data->fields["descom"];
			$ls_codran=$io_report->rs_data->fields["codran"]; 
			$ls_denran=$io_report->rs_data->fields["desran"];
			$ls_codcat=$io_report->rs_data->fields["codcat"]; 
			$ls_dencat=$io_report->rs_data->fields["descat"];
			$lb_valido2=$io_report->uf_asignacion_componente_rango($ls_codcom,$ls_codran,$ls_codcat,'1');								
			$li_asignacion=$io_report->rs_data_detalle->RecordCount();			
			$contar=0;
			$sumar=0;			
			if(!$io_report->rs_data_detalle->EOF)
			{
				$sumar=number_format($io_report->rs_data_detalle->fields["monto"],2,".","");
				$contar=number_format($io_report->rs_data_detalle->fields["personas"],2,".","");				
			}//fin del for
			if ($sumar>0)
			{			
			  $ls_data[1]=array('monto'=>number_format($sumar,2,",","."), 'total'=>number_format($contar,0,"","."));
			}
			$sub_total=$sub_total+$sumar;
			$subtotalper=$subtotalper+$contar;												
			if (($ls_codcomaux!=$ls_codcom)&&($li_asignacion>0))
			{
				$io_pdf->ezSetDy(-10);				
				uf_print_cabecera("COMPONENTE  ".$ls_dencom,'center',1,2,&$io_pdf);		
				$ls_codcomaux=$ls_codcom;		
			}			
			if (($ls_codranaux!=$ls_codran)&&($ls_codcat!=""))
			{			
				$ls_codranaux=$ls_codran;
				if ($sumar>0)
				{
					uf_print_cabecera("RANGO:  ".$ls_denran,'left',0,2,&$io_pdf);
					uf_print_cabecera("CATEGORIA:  ".$ls_dencat,'left',0,2,&$io_pdf);				
					uf_print_detalle($ls_data,$io_pdf);				
					unset($ls_data);						
				}
			}		
			$io_report->rs_data->MoveNext();				
		}//fin del for 	
		uf_print_totales(number_format($subtotalper,0,"","."),number_format($sub_total,2,",","."),&$io_pdf);	
		//PARA LOS REGISTROS DESPUES DEL 13/07/1995
		$io_pdf->ezSetDy(-2);
		uf_print_titulo('Después del 13 de Julio de 1995',&$io_pdf);	
		$io_pdf->ezSetDy(-2);	
		$li_totrow=$io_report->DS->getRowCount("codcom"); 
		$ls_codcomaux=""; 
		$ls_codranaux="";
		$ls_codcataux="";
		$sub_total=0;
		$subtotalper=0;
		$io_report->rs_data->MoveFirst();	
		while(!$io_report->rs_data->EOF)
		{
			$ls_codcom=$io_report->rs_data->fields["codcom"]; 
			$ls_dencom=$io_report->rs_data->fields["descom"];
			$ls_codran=$io_report->rs_data->fields["codran"]; 
			$ls_denran=$io_report->rs_data->fields["desran"];
			$ls_codcat=$io_report->rs_data->fields["codcat"]; 
			$ls_dencat=$io_report->rs_data->fields["descat"];
			$lb_valido2=$io_report->uf_asignacion_componente_rango($ls_codcom,$ls_codran,$ls_codcat,'2');								
			$li_asignacion=$io_report->rs_data_detalle->RecordCount();			
			$contar=0;
			$sumar=0;			
			if(!$io_report->rs_data_detalle->EOF)
			{
				$sumar=number_format($io_report->rs_data_detalle->fields["monto"],2,".","");
				$contar=number_format($io_report->rs_data_detalle->fields["personas"],2,".","");				
			}//fin del for
			if ($sumar>0)
			{			
			  $ls_data[1]=array('monto'=>number_format($sumar,2,",","."), 'total'=>number_format($contar,0,"","."));
			}
			$sub_total=$sub_total+$sumar;
			$subtotalper=$subtotalper+$contar;												
			if (($ls_codcomaux!=$ls_codcom)&&($li_asignacion>0))
			{
				$io_pdf->ezSetDy(-10);				
				uf_print_cabecera("COMPONENTE  ".$ls_dencom,'center',1,2,&$io_pdf);		
				$ls_codcomaux=$ls_codcom;		
			}			
			if (($ls_codranaux!=$ls_codran)&&($ls_codcat!=""))
			{			
				$ls_codranaux=$ls_codran;
				if ($sumar>0)
				{
					uf_print_cabecera("RANGO:  ".$ls_denran,'left',0,2,&$io_pdf);
					uf_print_cabecera("CATEGORIA:  ".$ls_dencat,'left',0,2,&$io_pdf);				
					uf_print_detalle($ls_data,$io_pdf);				
					unset($ls_data);						
				}
			}		
			$io_report->rs_data->MoveNext();				
		}//fin del for 	
		uf_print_totales(number_format($subtotalper,0,"","."),number_format($sub_total,2,",","."),&$io_pdf);	

		//PARA TODOS LOS REGISTROS
		$io_pdf->ezSetDy(-2);
		uf_print_titulo('Total de Asignaciones',&$io_pdf);	
		$io_pdf->ezSetDy(-2);	
		$li_totrow=$io_report->DS->getRowCount("codcom"); 
		$ls_codcomaux=""; 
		$ls_codranaux="";
		$ls_codcataux="";
		$sub_total=0;
		$subtotalper=0;
		$io_report->rs_data->MoveFirst();	
		while(!$io_report->rs_data->EOF)
		{
			$ls_codcom=$io_report->rs_data->fields["codcom"]; 
			$ls_dencom=$io_report->rs_data->fields["descom"];
			$ls_codran=$io_report->rs_data->fields["codran"]; 
			$ls_denran=$io_report->rs_data->fields["desran"];
			$ls_codcat=$io_report->rs_data->fields["codcat"]; 
			$ls_dencat=$io_report->rs_data->fields["descat"];
			$lb_valido2=$io_report->uf_asignacion_componente_rango($ls_codcom,$ls_codran,$ls_codcat,'3');								
			$li_asignacion=$io_report->rs_data_detalle->RecordCount();			
			$contar=0;
			$sumar=0;			
			if(!$io_report->rs_data_detalle->EOF)
			{
				$sumar=number_format($io_report->rs_data_detalle->fields["monto"],2,".","");
				$contar=number_format($io_report->rs_data_detalle->fields["personas"],2,".","");				
			}//fin del for
			if ($sumar>0)
			{			
			  $ls_data[1]=array('monto'=>number_format($sumar,2,",","."), 'total'=>number_format($contar,0,"","."));
			}
			$sub_total=$sub_total+$sumar;
			$subtotalper=$subtotalper+$contar;	
			if (($ls_codcomaux!=$ls_codcom)&&($li_asignacion>0))
			{
				$io_pdf->ezSetDy(-10);				
				uf_print_cabecera("COMPONENTE  ".$ls_dencom,'center',1,2,&$io_pdf);		
				$ls_codcomaux=$ls_codcom;		
			}			
			if (($ls_codranaux!=$ls_codran)&&($ls_codcat!=""))
			{			
				$ls_codranaux=$ls_codran;
				if ($sumar>0)
				{
					uf_print_cabecera("RANGO:  ".$ls_denran,'left',0,2,&$io_pdf);
					uf_print_cabecera("CATEGORIA:  ".$ls_dencat,'left',0,2,&$io_pdf);				
					uf_print_detalle($ls_data,$io_pdf);				
					unset($ls_data);						
				}
			}		
			$io_report->rs_data->MoveNext();	
		}//fin del for 	
		uf_print_totales(number_format($subtotalper,0,"","."),number_format($sub_total,2,",","."),&$io_pdf);
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");		
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 
