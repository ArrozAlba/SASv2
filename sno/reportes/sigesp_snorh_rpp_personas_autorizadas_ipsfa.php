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
	function uf_insert_seguridad($as_titulo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 13/06/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_personas_autorizadas.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/08/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(27,40,585,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],40,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		
		$io_pdf->addText(548,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(552,740,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera1(&$io_pdf,&$io_encabezado)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/08/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSety(710);  
		$io_pdf->setColor(0,0,0); 
		
		$li_tm=$io_pdf->getTextWidth(8,'RELACION DE PAGOS A BENEFICIARIOS');
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,8,'<b>RELACION DE PAGOS A BENEFICIARIOS</b>'); // Agregar el título
		    
		$la_data[1]=array('cedper'=>'<b>CEDULA PENSIONADO</b>',						
						  'cedben'=>'<b>CEDULA MILITAR</b>',
						  'cedaut'=>'<b>CEDULA BENEFICIARIO</b>',
						  'nomaut'=>'<b>APELLIDOS Y NOMBRES DEL BENEFICIARIO</b>',						  
						  'monto'=>'<b>MONTO</b>',
						  'banco'=>'<b>BANCO</b>',
						  'forma'=>'<b>FORMA PAGO</b>',
						  'cuenta'=>'<b>CUENTA</b>');
		$la_columna=array('cedper'=>'',						
						  'cedben'=>'',
						  'cedaut'=>'',
						  'nomaut'=>'',						  
						  'monto'=>'',
						  'banco'=>'',
						  'forma'=>'',
						  'cuenta'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas												 
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos' => 312,
						 'cols'=>array('cedper'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'cedben'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'cedaut'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna
						 			   'nomaut'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'center','width'=>55),
									   'banco'=>array('justification'=>'center','width'=>80),
									   'forma'=>array('justification'=>'center','width'=>80),
									   'cuenta'=>array('justification'=>'center','width'=>85))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera2(&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/08/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSety(710);  
		$io_pdf->setColor(0,0,0);  
		
		$li_tm=$io_pdf->getTextWidth(8,'RELACION DE PAGOS A PERSONAL AUTORIZADO');
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,8,'<b>RELACION DE PAGOS A PERSONAL AUTORIZADO</b>'); // Agregar el título
		   
		$la_data[1]=array('cedper'=>'<b>CEDULA PENSIONADO</b>',						
						  'cedben'=>'<b>CEDULA MILITAR</b>',
						  'cedaut'=>'<b>CEDULA AUTORIAZADO</b>',
						  'nomaut'=>'<b>APELLIDOS Y NOMBRES DEL AUTORIZADO</b>',						  
						  'monto'=>'<b>MONTO</b>',
						  'banco'=>'<b>BANCO</b>',
						  'forma'=>'<b>FORMA PAGO</b>',
						  'cuenta'=>'<b>CUENTA</b>');
		$la_columna=array('cedper'=>'',						
						  'cedben'=>'',
						  'cedaut'=>'',
						  'nomaut'=>'',						  
						  'monto'=>'',
						  'banco'=>'',
						  'forma'=>'',
						  'cuenta'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas												 
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos' => 312,
						 'cols'=>array('cedper'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'cedben'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'cedaut'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna
						 			   'nomaut'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'center','width'=>55),
									   'banco'=>array('justification'=>'center','width'=>80),
									   'forma'=>array('justification'=>'center','width'=>80),
									   'cuenta'=>array('justification'=>'center','width'=>85))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por personal
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 13/06/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('cedper'=>'',						
						  'cedben'=>'',
						  'cedaut'=>'',
						  'nomaut'=>'',						  
						  'monto'=>'',
						  'banco'=>'',
						  'forma'=>'',
						  'cuenta'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6.5, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas												 
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos' => 312,
						 'cols'=>array('cedper'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'cedben'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'cedaut'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna
						 			   'nomaut'=>array('justification'=>'left','width'=>110), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>55),
									   'banco'=>array('justification'=>'center','width'=>80),
									   'forma'=>array('justification'=>'center','width'=>80),
									   'cuenta'=>array('justification'=>'center','width'=>85))); // Justificación y ancho de la columna

		$io_pdf->ezTable($la_data,'','',$la_config);
	}// end function uf_print_detalle
	
//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_total($ai_num,$ai_montot,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		   Access: private 
		//	    Arguments: ai_totben // Total de Beneficiarios
		//	   			   ai_montot // Monto total por Beneficiario
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por Beneficiarios
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/11/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
		$la_data=array(array('total'=>'<b>NUMERO TOTAL</b>     '.' '.$ai_num.'','monto'=>'<b>MONTO TOTAL Bs.    </b>'.$ai_montot));
		$la_columna=array('total'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas						
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xPos' => 188,
						 'cols'=>array('total'=>array('justification'=>'right','width'=>200), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>160))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);		
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>Listado Pagos Autorizados</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codbenedes=$io_fun_nomina->uf_obtenervalor_get("codbenedes","");
	$ls_codbenehas=$io_fun_nomina->uf_obtenervalor_get("codbenehas","");
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");	
	$rs_data="";
//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_beneficiario_personal($ls_codperdes, $ls_codperhas, 
		                                                $ls_codbenedes, $ls_codbenehas, $ls_orden,$rs_data); // Obtenemos el detalle del reporte
	}
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		//print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.6,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(580,50,7,'','',1); // Insertar el número de página	
		$li_totrow=$io_report->DS->getRowCount("codper");		
		$li_numrowtot=$io_report->io_sql->num_rows($rs_data);
		$li_numrowtot2=$io_report->io_sql->num_rows($rs_data);		
		$i=0;
		$j=0;
		$ld_neto1=0;
		$ld_neto2=0;
		if ($li_numrowtot>0)
		{
			while ($li_numrowtot2>0)
			{    
			     $li_numrowtot2=$li_numrowtot2-1;
			     $row=$io_report->io_sql->fetch_row($rs_data);				 
			 	 $ls_cedper=$row["cedper"];
				 $ls_cedper=number_format($ls_cedper,0,",",".");
				 $ls_nomper=$row["apeper"].", ".$row["nomper"];
				 $ls_cedbene=$row["cedben"];
				 $ls_cedbene=number_format($ls_cedbene,0,",",".");
				 $ls_nombene=$row["apeben"].", ".$row["nomben"];
				 $ls_tipcueben=$row["tipcueben"];
				 if($ls_tipcueben=='A')
				 {
				 	$ls_tipcueben='/AHO';
				 }
				 elseif($ls_tipcueben=='C')
				 {
				 	$ls_tipcueben='/CTE';
				 }
				 $ls_forpag=$row["forpagben"];
				 switch ($ls_forpag)
				 {
				 	case "":
						$ls_forma="N/A";
					break;
					
					case "0":
						$ls_forma="CHEQUE";
					break;
					
					case "1":
						$ls_forma="DEPOSITO CTA".$ls_tipcueben;
					break;
				 }
				 $ls_monto=$row["monpagben"];
				 $ls_banco=$row["nomban"];
				 $ls_cuenta=$row["ctaban"];
				 
				 if(($row["nomcheben"]=="")&&($row["cedaut"]==""))
				 {
				 	 $i++;					 
					 $ls_nomaut=$ls_nombene;
					 $ls_cedaut=$ls_cedbene;
					 $ls_cedaut=number_format($ls_cedaut,0,",",".");
					 $ls_data[$i]=array('cedben'=>$ls_cedbene,
										'cedper'=>$ls_cedper,																
										'cedaut'=>$ls_cedaut,
										'nomaut'=>$ls_nomaut,						  
										'monto'=>$io_fun_nomina->uf_formatonumerico($ls_monto),
										'banco'=>$ls_banco,
										'forma'=>$ls_forma,
										'cuenta'=>$ls_cuenta);
					$ld_neto1=$ld_neto1 + $ls_monto;
				}	
				else
				{
					$j++;					 
					$ls_nomaut=$row["nomcheben"];
					$ls_cedaut=$row["cedaut"];
					$ls_cedaut=number_format($ls_cedaut,0,",",".");
					$ls_data2[$j]=array('cedben'=>$ls_cedbene,
									    'cedper'=>$ls_cedper,
										'cedaut'=>$ls_cedaut,
										'nomaut'=>$ls_nomaut,						  
										'monto'=>$io_fun_nomina->uf_formatonumerico($ls_monto),
										'banco'=>$ls_banco,
										'forma'=>$ls_forma,
										'cuenta'=>$ls_cuenta);
					$ld_neto2=$ld_neto2 + $ls_monto;
				}			 
				
			}//fin del while
			if($i > 0)
			{
				uf_print_cabecera1($io_pdf,$io_encabezado);	
				uf_print_detalle($ls_data,$io_pdf);
				$io_fun_nomina->uf_formatonumerico($ld_neto1);
				uf_print_total($i,$ld_neto1,$io_pdf);
				if($j > 0)
				{
					$io_pdf->stopObject($io_encabezado);
					$io_pdf->ezNewPage(); // Insertar una nueva página
					$io_pdf->ezSetCmMargins(3.6,2.5,3,3); // Configuración de los margenes en centímetros	
				}
			}
			if($j > 0)
			{			
				uf_print_cabecera2($io_pdf);	
				uf_print_detalle($ls_data2,$io_pdf);
				$io_fun_nomina->uf_formatonumerico($ld_neto2);
				uf_print_total($j,$ld_neto2,$io_pdf);
			}
			unset($ls_data);
			unset($ls_data2);
	    }
		$io_report->io_sql->free_result($rs_data);
		if (($lb_valido)&&($li_numrowtot>0)) // Si no ocurrio ningún error
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