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
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo,$ai_tipo)
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
		if($ai_tipo==1)
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_cuadre_netos_deduc.php",$ls_descripcion,$ls_codnom);
		}
		else
		{
		 	$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_hcuadre_netos_deduc.php",$ls_descripcion,$ls_codnom);
		}
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
	function uf_print_cabecera($ls_denominacion,&$io_pdf)
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
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('denominacion'=>array('justification'=>'center','width'=>500))); // Justificación y ancho de la columna
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
		$la_columnas=array('codigo'=>'<b>Código</b>',
						  'nombre'=>'<b>Denominación</b>',					      
					      'personal'=>'<b>Nro Personas</b>',
						  'asignacion'=>'<b>Monto</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>200), // Justificación y ancho de la columna				 			   
						 			   'personal'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($ls_denom, $ai_totper,$ai_totamon,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		   Access: private 
		//	    Arguments: ai_totprevio // Total Previo
		//	   			   ai_totactual // Total Actual
		//	   			   ai_totmovimiento // Total Movimiento
		//	   			   ai_totconc // Total Conceptos
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por cuadre
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 02/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('denominacion'=>'<b>'.$ls_denom.'</b>',		                  
						  'totalper'=>'<b>'.$ai_totper.'</b>',
						  'totalmon'=>'<b>'.$ai_totamon.'</b>');
		$la_columna=array('denominacion'=>'',		                
						  'totalper'=>'',
						  'totalmon'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('denominacion'=>array('justification'=>'center','width'=>300), // Justificación y ancho de la columna					 			  			 			   
						 			   'totalper'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'totalmon'=>array('justification'=>'right','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);			
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera2($ls_denom, $ai_totamon,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera2
		//		   Access: private 
		//	    Arguments:		
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por cuadre
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 31/07/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('denominacion'=>'<b>'.$ls_denom.'</b>',						  
						  'totalmon'=>'<b>'.$ai_totamon.'</b>');
		$la_columna=array('denominacion'=>'',					 
						  'totalmon'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 14,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('denominacion'=>array('justification'=>'center','width'=>300), // Justificación y ancho de la columna					 			  
						 			   'totalmon'=>array('justification'=>'right','width'=>200))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);			
	}
	//---------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	$ls_tiporeporte="0";
	$ls_bolivares="";
	if (array_key_exists("tiporeporte",$_GET))
	{
		$ls_tiporeporte=$_GET["tiporeporte"];
	}
	switch($ls_tiporeporte)
	{
		case "0":
			if($_SESSION["la_nomina"]["tiponomina"]=="NORMAL")
			{
				require_once("sigesp_sno_class_report.php");
				$io_report=new sigesp_sno_class_report();
				$li_tipo=1;
			}
			if($_SESSION["la_nomina"]["tiponomina"]=="HISTORICA")
			{
				require_once("sigesp_sno_class_report_historico.php");
				$io_report=new sigesp_sno_class_report_historico();
				$li_tipo=2;
			}	
			$ls_bolivares ="Bs.";
			break;

		case "1":
			if($_SESSION["la_nomina"]["tiponomina"]=="NORMAL")
			{
				require_once("sigesp_sno_class_reportbsf.php");
				$io_report=new sigesp_sno_class_reportbsf();
				$li_tipo=1;
			}
			if($_SESSION["la_nomina"]["tiponomina"]=="HISTORICA")
			{
				require_once("sigesp_sno_class_report_historicobsf.php");
				$io_report=new sigesp_sno_class_report_historicobsf();
				$li_tipo=2;
			}	
			$ls_bolivares ="Bs.F.";
			break;
	}
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	$ls_titulo="<b>Cuadre de Netos y Deducciones</b>";
	$ls_periodo="<b>Período Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper."</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codconcdes=$io_fun_nomina->uf_obtenervalor_get("codconcdes","");
	$ls_codconchas=$io_fun_nomina->uf_obtenervalor_get("codconchas","");
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("subnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("subnomhas","");	
	$ls_codente=$io_fun_nomina->uf_obtenervalor_get("codente","");	
	$ls_monto_total=0;
	$monto_total2=0;
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_buscar_categorias_rango(); // Cargar el DS con los datos de la cabecera del reporte
	}
	if(($lb_valido==false)||($io_report->rs_data->RecordCount()==0)) // Existe algún error ó no hay registros
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
		$io_pdf->ezSetCmMargins(3,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_desnom,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$li_totrow=$io_report->rs_data->RecordCount(); 
		$ls_codcat_aux=" ";
		$ls_totalpersona=0;
		$ls_totalmonto=0;
		$monto_total=0;
		$personal_total=0;
		$personal_total2=0;
		while((!$io_report->rs_data->EOF)&&($lb_valido))		
		{
			$ls_codcat=$io_report->rs_data->fields["codcat"]; 
			$ls_denominacion=$io_report->rs_data->fields["descat"];				
			$io_report->uf_cuadre_concepto_neto_deduc($ls_codconcdes,$ls_codconchas,$ls_conceptocero,$ls_subnomdes,$ls_subnomhas,$ls_codcat,$ls_codente);
			$li_totrow2=$io_report->rs_data_detalle->RecordCount();
			$ls_asig=0;
			$ls_deduc=0;
			$ls_monto_tot1=0;
			$ls_persona_tot1=0; 
			$ls_monto_tot2=0;
			$ls_persona_tot2=0; 
			$ls_totalper1=0;
			$ls_monto1=0;
			while(!$io_report->rs_data_detalle->EOF)
			{				
				$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
				$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
				$ls_totalper=$io_report->rs_data_detalle->fields["total"];	
				$ls_monto=$io_report->rs_data_detalle->fields["monto"];
				$ls_tipo=$io_report->rs_data_detalle->fields["tipsal"];	
				$ls_totalper1=$ls_totalper1+$ls_totalper;	
				$ls_monto1=$ls_monto1+$ls_monto;				 
				if (rtrim($ls_tipo)=="A")
				{
					$ls_monto_tot1=$ls_monto_tot1+$ls_monto;
			        $ls_persona_tot1=$ls_persona_tot1+$ls_totalper; 
				}	
				if (rtrim($ls_tipo)=="D")
				{
					$ls_monto_tot2=$ls_monto_tot2+abs($ls_monto);
			        $ls_persona_tot2=$ls_persona_tot2+$ls_totalper; 
				}	
				$io_report->rs_data_detalle->MoveNext();
				if ((trim($io_report->rs_data_detalle->fields["codconc"])!=trim($ls_codconc)))
				{
					if (rtrim($ls_tipo)=="A")
					{
						$ls_asig++;
						$la_data1[$ls_asig]=array('codigo'=>$ls_codconc,'nombre'=>$ls_nomcon,'personal'=>number_format($ls_totalper1,0,"","."),'asignacion'=>number_format($ls_monto1,2,",","."));
					}	
					if (rtrim($ls_tipo)=="D")
					{
						$ls_deduc++;
						$la_data2[$ls_deduc]=array('codigo'=>$ls_codconc,'nombre'=>$ls_nomcon,'personal'=>number_format($ls_totalper1,0,"","."),'asignacion'=>number_format(abs($ls_monto1),2,",","."));
					}	
					$ls_totalper1=0;
					$ls_monto1=0;
				}
			}//fin del for
			if ($ls_codcat_aux!=$ls_codcat)
			{			   
				if ($ls_codcat_aux!="")
				{
				 	uf_print_cabecera("Categoría ".$ls_denominacion, $io_pdf);				   
				}
				$ls_codcat_aux=$ls_codcat;
				if ($ls_asig>0)
				{	
				    $ls_denominacion="Asignaciones";
					uf_print_cabecera($ls_denominacion,&$io_pdf);
					uf_print_detalle($la_data1,$io_pdf); // Imprimimos el detalle
					$ls_denom="Total Asignaciones";
					uf_print_piecabecera($ls_denom, $ls_persona_tot1,number_format($ls_monto_tot1,2,",","."),&$io_pdf);
					$monto_total=$monto_total+($ls_monto_tot1);
		            $personal_total=$personal_total+($ls_persona_tot1);
					$io_pdf->ezSetDy(-5);
					unset($la_data1);
				}
				if($ls_deduc>0)
				{
					$ls_denominacion="Deducciones";
					uf_print_cabecera($ls_denominacion,&$io_pdf);
					uf_print_detalle($la_data2,$io_pdf); // Imprimimos el detalle
					$ls_denom="Total Deducciones";
					uf_print_piecabecera($ls_denom, $ls_persona_tot2,number_format($ls_monto_tot2,2,",","."),&$io_pdf);
					$monto_total2=$monto_total2+($ls_monto_tot2);
		            $personal_total2=$personal_total2+($ls_persona_tot2);	
					$io_pdf->ezSetDy(-5);			
					unset($la_data2);								
				}
			}	
			$io_report->rs_data->MoveNext();					
		}//fin del for	
		if ($monto_total>0)
		{
			$io_pdf->ezSetDy(-5);
			$ls_denom="TOTAL GENERAL ASIGNACIONES";
			uf_print_piecabecera2($ls_denom,number_format($monto_total,2,",","."),&$io_pdf);
		}
		
		if ($monto_total2>0)
		{
			$io_pdf->ezSetDy(-5);
			$ls_denom="TOTAL GENERAL DEDUCCIONES";
			uf_print_piecabecera2($ls_denom, number_format($monto_total2,2,",","."),&$io_pdf);
		}
		$monto_genral=abs($monto_total-$monto_total2);
		$io_pdf->ezSetDy(-10);
		$ls_denom="CUADRE DE LA NÓMINA";
		uf_print_piecabecera2($ls_denom,number_format($monto_genral,2,",","."),&$io_pdf);
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 
