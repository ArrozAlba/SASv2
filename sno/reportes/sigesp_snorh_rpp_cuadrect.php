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

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo,$as_titulo2,$as_periodo,$ai_tipo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_descripcion="Generó el Reporte Consolidado ".$as_titulo.". Para el Periodo ".$as_periodo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_cuadrect.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_periodo,$as_programatica,$as_programatica2,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_titulo2 // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,740,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,725,11,$as_periodo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_programatica);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,710,11,$as_programatica); // Agregar el título
		$io_pdf->addText($tm,697,11,$as_programatica2); // Agregar el título
		$io_pdf->addText(500,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(506,743,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,$ls_programatica,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-3);
		$la_dato_estruct[1]=array('programatica'=>'<b> ESTRUCTURA </b>       '.$ls_programatica);
		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xPos'=>306, // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('programatica'=>array('programatica'=>'left','width'=>520))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_dato_estruct,'','',$la_config);
		
		$la_dat[1]=array('nom'=>'<b> NOMINA </b>',
						  'monct'=>'<b> MONTO          CESTATICKET   </b>',
						  'mondes'=>'<b> MONTO DESC.    COMEDOR      </b>',
						  'montot'=>'<b> TOTAL POR      NOMINA       </b>',
						  'porc'=>'<b> GASTO ADMIN. 2%            </b>');
		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('nom'=>array('justification'=>'center','width'=>240), // Justificación y ancho de la columna
						 			   'monct'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'mondes'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'montot'=>array('justification'=>'right','width'=>70),
									   'porc'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_dat,'','',$la_config);
		
		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('nom'=>array('justification'=>'left','width'=>240), // Justificación y ancho de la columna
						 			   'monct'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'mondes'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'montot'=>array('justification'=>'right','width'=>70),
									   'porc'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,'','',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_total($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_total
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('nom'=>array('justification'=>'right','width'=>240), // Justificación y ancho de la columna
						 			   'monct'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'mondes'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'montot'=>array('justification'=>'right','width'=>70),
									   'porc'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,'','',$la_config);
		$io_pdf->ezSetDy(-15);
	}// end function uf_print_total
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	$li_tipo=0;
	$ls_bolivares="";
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	$ls_bolivares ="Bs.";
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	
	//DESDE LA ESTRUCTURA..
	$ls_codestpro1=str_pad(trim($io_fun_nomina->uf_obtenervalor_get("codestpro1","")),25,"0",0);
	$ls_codestpro2=str_pad(trim($io_fun_nomina->uf_obtenervalor_get("codestpro2","")),25,"0",0);
	$ls_codestpro3=str_pad(trim($io_fun_nomina->uf_obtenervalor_get("codestpro3","")),25,"0",0);
	$ls_codestpro4=str_pad(trim($io_fun_nomina->uf_obtenervalor_get("codestpro4","")),25,"0",0);
	$ls_codestpro5=str_pad(trim($io_fun_nomina->uf_obtenervalor_get("codestpro5","")),25,"0",0);
	$ls_codestproini=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
	$ls_denestpro1=trim($io_fun_nomina->uf_obtenervalor_get("denestpro1",""));
	$ls_denestpro2=trim($io_fun_nomina->uf_obtenervalor_get("denestpro2",""));
	$ls_denestpro3=trim($io_fun_nomina->uf_obtenervalor_get("denestpro3",""));
	$ls_denestpro4=trim($io_fun_nomina->uf_obtenervalor_get("denestpro4",""));
	$ls_denestpro5=trim($io_fun_nomina->uf_obtenervalor_get("denestpro5",""));
	$ls_estcla=trim($io_fun_nomina->uf_obtenervalor_get("estcla",""));

	//HASTA LA ESTRUCTURA..
	$ls_codestpro6=str_pad(trim($io_fun_nomina->uf_obtenervalor_get("codestpro6","")),25,"0",0);
	$ls_codestpro7=str_pad(trim($io_fun_nomina->uf_obtenervalor_get("codestpro7","")),25,"0",0);
	$ls_codestpro8=str_pad(trim($io_fun_nomina->uf_obtenervalor_get("codestpro8","")),25,"0",0);
	$ls_codestpro9=str_pad(trim($io_fun_nomina->uf_obtenervalor_get("codestpro9","")),25,"0",0);
	$ls_codestpro10=str_pad(trim($io_fun_nomina->uf_obtenervalor_get("codestpro10","")),25,"0",0);
	$ls_codestprofin=$ls_codestpro6.$ls_codestpro7.$ls_codestpro8.$ls_codestpro9.$ls_codestpro10;
	$ls_denestpro6=trim($io_fun_nomina->uf_obtenervalor_get("denestpro6",""));
	$ls_denestpro7=trim($io_fun_nomina->uf_obtenervalor_get("denestpro7",""));
	$ls_denestpro8=trim($io_fun_nomina->uf_obtenervalor_get("denestpro8",""));
	$ls_denestpro9=trim($io_fun_nomina->uf_obtenervalor_get("denestpro9",""));
	$ls_denestpro10=trim($io_fun_nomina->uf_obtenervalor_get("denestpro10",""));
	$ls_estcla2=trim($io_fun_nomina->uf_obtenervalor_get("estcla2",""));
	
	$ls_ano=$io_fun_nomina->uf_obtenervalor_get("ano","");
	$ls_mes=$io_fun_nomina->uf_obtenervalor_get("mes","");
	$ls_titmes=strtoupper($io_report->io_fecha->uf_load_nombre_mes($ls_mes));
	$ls_codperi=$io_fun_nomina->uf_obtenervalor_get("codperi","");
	$ls_periodo=$ls_titmes.' - '.$ls_ano; 
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>Cuadre de Conceptos de Cestaticket</b>";
	$ls_titulo2="";
	$ls_periodo="<b>Período Nro ".$ls_periodo;
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_titulo2,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido) // Buscamos la información que afecta contabilidad por el debe
	{
		$lb_valido=$io_report->uf_cuadrect_estructuras($ls_codperi,$ls_ano,$ls_codestproini,$ls_codestprofin,$ls_estcla,$ls_estcla2,$rs_data2);			
	}
	if(($lb_valido==false)||($rs_data2->RecordCount()==0)) // Existe algún error ó no hay registros
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
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.3,2.5,3,3); // Configuración de los margenes en centímetros
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		
  	    //--------------------------------------------- Imprimir el detalle Presupuestario------------------------------------------------	
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		$ls_programatica=$ls_codestproini;
		$ls_programatica2=$ls_codestprofin;
		$io_fun_nomina->uf_formato_estructura($ls_programatica,$ls_codest1,$ls_codest2,$ls_codest3,$ls_codest4,$ls_codest5);
		$io_fun_nomina->uf_formato_estructura($ls_programatica2,$ls_codest6,$ls_codest7,$ls_codest8,$ls_codest9,$ls_codest10);
		$ls_programatica='Desde Estructura Presupuestaria '.$ls_codest1.'-'.$ls_codest2.'-'.$ls_codest3;
		$ls_programatica2=' Hasta Estructura Presupuestaria '.$ls_codest6.'-'.$ls_codest7.'-'.$ls_codest8;
		switch($ls_modalidad)
		{
			case "2": // Modalidad por Programa
				
				$ls_programatica='Desde Estructura Programatica '.$ls_codest1.'-'.$ls_codest2.'-'.$ls_codest3.'-'.$ls_codest4.'-'.$ls_codest5;
				$ls_programatica2=' Hasta Estructura Programatica '.$ls_codest6.'-'.$ls_codest7.'-'.$ls_codest8.'-'.$ls_codest9.'-'.$ls_codest10;
				break;
		}
		uf_print_encabezado_pagina($ls_titulo,$ls_periodo,$ls_programatica,$ls_programatica2,$io_pdf); // Imprimimos el encabezado de la página
		$li_totalpresupuesto=0;
		$ld_totdesnom=0;
		$ld_totctnom=0;
		$ld_totgennom=0;
		$ld_totct=0;
		$ld_totdes=0;
		$ld_totnom=0;
		$ld_totpor=0;
		$ld_totct2=0;
		$ld_totdes2=0;
		$ld_totnom2=0;
		$ld_totpor2=0;
		$ld_totdesnom2=0;
		$ld_totctnom2=0;
		$ld_totgennom2=0;
		$li_i=0;
        while ((!$rs_data2->EOF)&&($lb_valido))
		{	
			$ls_estruc=$rs_data2->fields["codprouniadm"];
			$rs_data2->MoveNext();
			$lb_valido=$io_report->uf_cuadrect($ls_codperi,$ls_ano,$ls_estruc,$ls_estruc,$ls_estcla,$ls_estcla2,$rs_data);			
			while ((!$rs_data->EOF)&&($lb_valido))
		   	{
					
					$ls_estruc2=$rs_data->fields["codprouniadm"];
					$io_fun_nomina->uf_formatoprogramatica ($ls_estruc2,$ls_programatica);
					$ls_desnom=$rs_data->fields["desnom"];
					$ld_monto=$rs_data->fields["valsal"];			
					$ls_tipsal=trim($rs_data->fields["tipsal"]);
					if ($ls_tipsal=='A')
					{
						$ld_totctnom=$ld_totctnom+abs($ld_monto);
						$ld_totctnom2=$ld_totctnom2+abs($ld_monto);
					}	
					elseif ($ls_tipsal=='D')
					{
						$ld_totdesnom=$ld_totdesnom+abs($ld_monto);
						$ld_totdesnom2=$ld_totdesnom2+abs($ld_monto);
					}		
					$rs_data->MoveNext();
					if (trim($rs_data->fields["desnom"]) != trim($ls_desnom))
					{
						
						$li_i++;
						$ld_totgennom=$ld_totctnom - $ld_totdesnom;
						$ld_totgennom2=$ld_totctnom2 - $ld_totdesnom2;
						$ld_porc=round($ld_totgennom*0.02,2);
						$ld_porc2=round($ld_totgennom2*0.02,2);
						$la_data[$li_i]=array('nom'=>$ls_desnom,'monct'=>number_format($ld_totctnom,2,",","."),
											  'mondes'=>number_format($ld_totdesnom,2,",","."),
											  'montot'=>number_format($ld_totgennom,2,",","."),
											  'porc'=>number_format($ld_porc,2,",","."));
												
						$ld_totct=$ld_totct+$ld_totctnom;
						$ld_totdes=$ld_totdes+$ld_totdesnom;
						$ld_totnom=$ld_totnom+$ld_totgennom;
						$ld_totpor=$ld_totpor+$ld_porc;
						
						$ld_totct2=$ld_totct2+$ld_totctnom2;
						$ld_totdes2=$ld_totdes2+$ld_totdesnom2;
						$ld_totnom2=$ld_totnom2+$ld_totgennom2;
						$ld_totpor2=$ld_totpor2+$ld_porc2;				
						
						$ld_totdesnom=0;
						$ld_totctnom=0;
						$ld_totgennom=0;
						$ld_totdesnom2=0;
						$ld_totctnom2=0;
						$ld_totgennom2=0;
						
					}
					
			}	
				$la_dattot[1]=array('nom'=>'TOTAL GENERAL','monct'=>number_format($ld_totct,2,",","."),
				                      'mondes'=>number_format($ld_totdes,2,",","."),
									  'montot'=>number_format($ld_totnom,2,",","."),
									  'porc'=>number_format($ld_totpor,2,",","."));
				uf_print_detalle($la_data,$ls_programatica,$io_pdf);
				uf_print_total($la_dattot,$io_pdf);
				$ld_totct=0;
				$ld_totdes=0;
				$ld_totnom=0;
				$ld_totpor=0;
				unset($la_data);
		}		
		$la_dattot_gen[1]=array('nom'=>'TOTAL GENERAL ESTRUCTURAS','monct'=>number_format($ld_totct2,2,",","."),
				                      'mondes'=>number_format($ld_totdes2,2,",","."),
									  'montot'=>number_format($ld_totnom2,2,",","."),
									  'porc'=>number_format($ld_totpor2,2,",","."));
		
		if($lb_valido) // Si no ocurrio ningún error
		{
			uf_print_total($la_dattot_gen,$io_pdf);
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