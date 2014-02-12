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
	ini_set('memory_limit','2048M');
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
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/11/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Generó el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		if($ai_tipo==1)
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_listadobeneficiario.php",$ls_descripcion,$ls_codnom);
		}
		else
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_listadobeneficiario.php",$ls_descripcion,$ls_codnom);
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
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/11/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,755,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,540,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,530,11,$as_periodo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(10,$as_desnom);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,520,10,$as_desnom); // Agregar el título
		$io_pdf->addText(712,560,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(718,553,7,date("h:i a")); // Agregar la Hora
		
		$io_pdf->ezSetDy(15);
		
		
		$la_data=array(array('name1'=>'<b>DATOS DEL BANCO</b>',
		                     'name2'=>'<b>PAGO POR TAQUILLA</b>',
							 'name3'=>'<b>CUENTAS DE AHORRO</b>',
							 'name4'=>'<b>CUENTAS CORRIENTES</b>',
							 'name5'=>'<b>TOTAL POR BANCO</b>'));	
							 
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>750, // Ancho de la tabla
						 'maxWidth'=>750, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla						 
						 'cols'=>array('name1'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
						 			   'name2'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
									   'name3'=>array('justification'=>'center','width'=>150),
									   'name4'=>array('justification'=>'center','width'=>150),						 			  
									   'name5'=>array('justification'=>'center','width'=>150))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,'','',$la_config);
		unset($la_data);
		unset($la_config);	
		
		
		$la_data=array(array('codban'=>'<b>COD</b>',
		                     'nomban'=>'<b>NOMBRE BANCO</b>',
							 'numche'=>'<b>NUM</b>',
							 'montotche'=>'<b>MONTO Bs.</b>',
							 'nunctaaho'=>'<b>NUM</b>',
							 'montotctaaho'=>'<b>MONTO Bs.</b>',
							 'numctacte'=>'<b>NUM</b>',
							 'montotctacte'=>'<b>MONTO Bs.</b>',
							 'numtot'=>'<b>NUM</b>',
							 'montot'=>'<b>MONTO Bs.</b>'));	
							 
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>750, // Ancho de la tabla
						 'maxWidth'=>750, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla						 
						 'cols'=>array('codban'=>array('justification'=>'left','width'=>30), // Justificación y ancho de la columna
						 			   'nomban'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la columna
									   'numche'=>array('justification'=>'center','width'=>50),
									   'montotche'=>array('justification'=>'right','width'=>100),
						 			  
									   'nunctaaho'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'montotctaaho'=>array('justification'=>'right','width'=>100),
									   'numctacte'=>array('justification'=>'center','width'=>50),
									   'montotctacte'=>array('justification'=>'right','width'=>100),
									   'numtot'=>array('justification'=>'center','width'=>50),
									   'montot'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,'','',$la_config);
		unset($la_data);
		unset($la_config);		
		
		
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/11/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>750, // Ancho de la tabla
						 'maxWidth'=>750, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla						 
						 'cols'=>array('codban'=>array('justification'=>'cleft','width'=>30), // Justificación y ancho de la columna
						 			   'nomban'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la columna
									   'numche'=>array('justification'=>'right','width'=>50),
									   'montotche'=>array('justification'=>'right','width'=>100),
									   'nunctaaho'=>array('justification'=>'right','width'=>50), // Justificación y ancho de la columna
						 			   'montotctaaho'=>array('justification'=>'right','width'=>100),
									   'numctacte'=>array('justification'=>'right','width'=>50),
									   'montotctacte'=>array('justification'=>'right','width'=>100),
									   'numtot'=>array('justification'=>'center','width'=>50),
									   'montot'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,'','',$la_config);
		unset($la_data);
		unset($la_config);		
	}// end function uf_print_detalle
	//---------------------------------------------------------------------------------------------------------------------------------
	
	///--------------------------------------------------------------------------------------------------------------------------------
    function uf_print_nota($ls_monto1,$ls_monto2,$ls_monto3, $ls_monto4, &$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_nota
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la nota 
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 21/01/2009
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                $ls_suma=$ls_monto1+$ls_monto2+$ls_monto3+$ls_monto4;
		$la_data[1]=array('nota'=>'');
		$la_data[2]=array('nota'=>'');
		$la_data[3]=array('nota'=>'NOTA:  1. EL CONCEPTO 411-413-410 PENSIÓN ALIMENTICIA, EN EL REPORTE CUADRE2P ES UNA DEDUCCIÓN DEL INGRESO BRUTO QUE SE HACE A UN PENSIONADO PARA PAGAR A UN                            TERCERO. ESTE PAGO SE RELACIONA COMO INGRESO NETO PUES SE PAGA A TRAVES DE UNA ENTIDAD BANCARIA');
		$la_data[4]=array('nota'=>'');
		$la_data[5]=array('nota'=>'             2. CUADRE DE INGRESOS NETOS Y PAGOS BANCARIOS ASOCIADOS: DEL REPORTE *CUADRE2P TOTAL GENERAL Bs. = '.number_format($ls_monto4,2,",","."));
		$la_data[6]=array('nota'=>'                                                                           										                                                  DEL REPORTE *CUADRE2P CONCEPTO 411 Bs. = '.number_format($ls_monto1,2,",","."));
		$la_data[7]=array('nota'=>'																              						                                                                                                   DEL REPORTE *CUADRE2P CONCEPTO 410 Bs. = '.number_format($ls_monto2,2,",","."));
		$la_data[8]=array('nota'=>'																             						                                                                                                    DEL REPORTE *CUADRE2P CONCEPTO 413 Bs. = '.number_format($ls_monto3,2,",","."));
		$la_data[9]=array('nota'=>'																             						                                                                                                                                                                                            ===========================');
		$la_data[10]=array('nota'=>'																             						                                                                                                    *SUMA*    Bs.=                                                                                          '.number_format($ls_suma,2,",","."));
		$la_data[11]=array('nota'=>'             ** CUADRE VERIFICADO Y CORRECTO, SI SE CUMPLE QUE * SUMA * = TOTAL GENERAL');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras						 
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>750, // Ancho de la tabla
						 'maxWidth'=>750, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla						 
						 'cols'=>array('codban'=>array('justification'=>'right','width'=>750))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,'','',$la_config);
		unset($la_data);
		unset($la_config);		
	}/// fin de uf_print_nota ()
	///--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  -----------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	$ls_tiporeporte="0";
	$ls_bolivares ="Bs.";
	$li_tipo=1;
	require_once("sigesp_sno_class_report.php");
	$io_report=new sigesp_sno_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codbandes=$io_fun_nomina->uf_obtenervalor_get("codbandes","");
	$ls_codbanhas=$io_fun_nomina->uf_obtenervalor_get("codbanhas","");	
	$ls_quincena=$io_fun_nomina->uf_obtenervalor_get("quincena","-");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
//--------------------------------------------------------------------------------------------------------------------------------
	$ls_titulo="<b>Relación de Pagos y Depósitos por Entidad Bancaria </b>";
	$ls_periodo="<b>Período Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper."</b>";
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_pago_por_bancos($ls_codbandes,$ls_codbanhas,$ls_quincena,$ls_orden); // Cargar el DS con los datos de la cabecera del reporte
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
		set_time_limit(3600);
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(4,2,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_desnom,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(750,50,7,'','',1); // Insertar el número de página
		$ld_montot=0;				
		$ld_montotche=0;				
		$ld_montotctaaho=0;				
		$ld_montotctacte=0;
		$li_totche=0;
		$li_totctaaho=0;
		$li_totctacte=0;
		$li_numtot=0;
		$li_s=0;
		$ls_codban_next="";	
		$li_totgenche=0;
		$li_totgenctaaho=0;
		$li_totgenctacte=0;
		$li_totgen=0;		
		$ld_montotgenche=0;
		$ld_montotgenctaaho=0;
		$ld_montotgenctacte=0;
		$ld_montotgen=0;	
 		while ((!$io_report->rs_data->EOF)&&($lb_valido))
		{
			$li_s++;			
			$ls_codban=$io_report->rs_data->fields["codban"];
			$ls_nomban=$io_report->rs_data->fields["nomban"];			
			$ls_pagbanper=$io_report->rs_data->fields["pagbanper"];			
			$ls_tipcuebanper=$io_report->rs_data->fields["tipcuebanper"];			
			$ls_pagtaqper=$io_report->rs_data->fields["pagtaqper"];		
			$li_neto=$io_report->rs_data->fields["monnetres"];
			if ($ls_pagbanper=='1')
			{
				if($ls_tipcuebanper=='A')
				{
					$li_totctaaho++;
					$ld_montotctaaho=$ld_montotctaaho+$li_neto;
				}
				elseif($ls_tipcuebanper=='C')
				{
					$li_totctacte++;
					$ld_montotctacte=$ld_montotctacte+$li_neto;
				}
			}
			$io_report->rs_data->MoveNext();			
			if($ls_codban!=$io_report->rs_data->fields["codban"])
			{
				$li_numtot=$li_totche+$li_totctaaho+$li_totctacte;
				$ld_montot=$ld_montotche+$ld_montotctacte+$ld_montotctaaho;
				
				$li_totgenche=$li_totgenche+$li_totche;
				$li_totgenctaaho=$li_totgenctaaho+$li_totctaaho;
				$li_totgenctacte=$li_totgenctacte+$li_totctacte;
				$li_totgen=$li_totgen+$li_numtot;		
				$ld_montotgenche=$ld_montotgenche+$ld_montotche;
				$ld_montotgenctaaho=$ld_montotgenctaaho+$ld_montotctaaho;
				$ld_montotgenctacte=$ld_montotgenctacte+$ld_montotctacte;
				$ld_montotgen=$ld_montotgen+$ld_montot;
				$li_numtot=number_format($li_numtot,0,"",".");	
				$li_totctaaho=number_format($li_totctaaho,0,"",".");	
				$li_totctacte=number_format($li_totctacte,0,"",".");	
				$ld_montot=number_format($ld_montot,2,",",".");				
				$ld_montotche=number_format($ld_montotche,2,",",".");				
				$ld_montotctaaho=number_format($ld_montotctaaho,2,",",".");				
				$ld_montotctacte=number_format($ld_montotctacte,2,",",".");			
				$la_data[$li_s]=array('codban'=>$ls_codban,'nomban'=>$ls_nomban,'numche'=>$li_totche,'montotche'=>$ld_montotche,
									  'nunctaaho'=>$li_totctaaho,'montotctaaho'=>$ld_montotctaaho,'numctacte'=>$li_totctacte,'montotctacte'=>$ld_montotctacte,
									  'numtot'=>$li_numtot,'montot'=>$ld_montot);
				$ld_montot=0;				
				$ld_montotche=0;				
				$ld_montotctaaho=0;				
				$ld_montotctacte=0;
				$li_totche=0;
				$li_totctaaho=0;
				$li_totctacte=0;
				$li_numtot=0;
			}
		}//fin del while
		if(($lb_valido)&&($li_s>0)) // Si no ocurrio ningún error
		{
			uf_print_detalle($la_data,$io_pdf);
			unset($la_data);			
			$la_data[1]=array('codban'=>'','nomban'=>'<b>T O T A L    G E N E R A L</b>',
				  'numche'=>'<b>'.number_format($li_totgenche,0,"",".").'</b>','montotche'=>'<b>'.number_format($ld_montotgenche,2,",",".").'</b>',
				  'nunctaaho'=>'<b>'.number_format($li_totgenctaaho,0,"",".").'</b>','montotctaaho'=>'<b>'.number_format($ld_montotgenctaaho,2,",",".").'</b>',
				  'numctacte'=>'<b>'.number_format($li_totgenctacte,0,"",".").'</b>','montotctacte'=>'<b>'.number_format($ld_montotgenctacte,2,",",".").'</b>',
				  'numtot'=>'<b>'.number_format($li_totgen,0,"",".").'</b>','montot'=>'<b>'.number_format($ld_montotgen,2,",",".").'</b>');
			uf_print_detalle($la_data,$io_pdf);
			$ls_monto1=$io_report->uf_buscar_deducciones($ls_codnom, $ls_peractnom,'0000000410');
			$ls_monto1=abs($ls_monto1);				
			$ls_monto2=$io_report->uf_buscar_deducciones($ls_codnom, $ls_peractnom,'0000000411');	
			$ls_monto2=abs($ls_monto2);			
			$ls_monto3=$io_report->uf_buscar_deducciones($ls_codnom, $ls_peractnom,'0000000413');	
			$ls_monto3=abs($ls_monto3);	
			$ls_monto4=$io_report->uf_deduccion_categorias($ls_codnom, $ls_peractnom);	
			$ls_monto4=abs($ls_monto4);		
			uf_print_nota($ls_monto1,$ls_monto2,$ls_monto3,$ls_monto4,&$io_pdf);
			unset($la_data);	
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
