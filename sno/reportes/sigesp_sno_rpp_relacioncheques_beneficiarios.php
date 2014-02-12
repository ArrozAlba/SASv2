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
		
		$io_pdf->ezSetDy(20);
		
		$la_data=array(array('cedpen'=>'<b>CED. PENSIONADO</b>',
							 'nompen'=>'<b>NOMBRE PENSIONADO</b>',
							 'cedmil'=>'<b>CED. CAUSANTE</b>',
							 'nommil'=>'<b>NOMBRE CAUSANTE</b>',
							 'cedaut'=>'<b>CED. AUTORIZADO</b>',
							 'nomaut'=>'<b>NOMBRE AUTORIZADO</b>',
							 'monto'=>'<b>NETO A PAGAR</b>',
							 'recib'=>'<b>RECIBI CONF</b>'));	
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>750, // Ancho de la tabla
						 'maxWidth'=>750, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla						 
						 'cols'=>array('cedpen'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'nompen'=>array('justification'=>'left','width'=>130), // Justificación y ancho de la columna
									   'cedmil'=>array('justification'=>'left','width'=>60),
									   'nommil'=>array('justification'=>'left','width'=>130),
						 			  
									   'cedaut'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'nomaut'=>array('justification'=>'left','width'=>130),
									   'monto'=>array('justification'=>'right','width'=>100),
									   'recib'=>array('justification'=>'center','width'=>95))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,'','',$la_config);
		unset($la_data);
		unset($la_config);		
		
		$la_data1[1]=array('name'=>'=====================================================================================================================================================================================================================================================================================================================================================================================================================================');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 3, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>750); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data1,'','',$la_config);			
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
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>750, // Ancho de la tabla
						 'maxWidth'=>750, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla						 
						 'cols'=>array('cedpen'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'nompen'=>array('justification'=>'left','width'=>130), // Justificación y ancho de la columna
									   'cedmil'=>array('justification'=>'left','width'=>60),
									   'nommil'=>array('justification'=>'left','width'=>130),
						 			  
									   'cedaut'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'nomaut'=>array('justification'=>'left','width'=>130),
									   'monto'=>array('justification'=>'right','width'=>100),
									   'recib'=>array('justification'=>'right','width'=>95))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,'','',$la_config);
		unset($la_data);
		unset($la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($ai_totben,$ai_montot,&$io_pdf)
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
		
		$la_data1[1]=array('name'=>'============================================================================================================================================================================================================================================================================================================================');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 4, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>750); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data1,'','',$la_config);			
		unset($la_data);
		unset($la_config);
		
		$la_data=array(array('total'=>'<b>NUMERO TOTAL</b>  '.' '.$ai_totben.'','monto'=>'<b>MONTO TOTAL Bs. </b>'.$ai_montot));
		$la_columna=array('total'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas						
						 'width'=>750, // Ancho de la tabla
						 'maxWidth'=>750, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>200), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>450))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);		
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

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
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_quincena=$io_fun_nomina->uf_obtenervalor_get("quincena","-");
	$ls_codban=$io_fun_nomina->uf_obtenervalor_get("codban","");
	$ls_nomban=$io_fun_nomina->uf_obtenervalor_get("nomban","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("subnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("subnomhas","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$ls_titulo="<b>Relación de Cheques Emitidos ".$ls_nomban."</b>";
	$ls_periodo="<b>Período Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper."</b>";
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_listadobeneficiario_banco($ls_codperdes,$ls_codperhas,$ls_quincena,$ls_codban,$ls_subnomdes,$ls_subnomhas,$ls_orden,&$rs_data); // Cargar el DS con los datos de la cabecera del reporte
	}
	if($lb_valido==false) // Existe algún error ó no hay registros
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
		$io_pdf->ezSetCmMargins(4.2,2,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_desnom,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(750,50,7,'','',1); // Insertar el número de página
		$li_numrowtot=$io_report->io_sql->num_rows($rs_data);
		$li_montototalbene=0;	
		$li_totalbene=0;
		$li_s=0;		
 		while((!$rs_data->EOF)&&($lb_valido))
		{
		    $li_numrowtot=$li_numrowtot-1;
			$row=$io_report->io_sql->fetch_row($rs_data);	        
			$ls_codper=$rs_data->fields["codper"];
			$ls_cedper=$rs_data->fields["cedper"];
			$ls_nomper=$rs_data->fields["apeper"]." ".$rs_data->fields["nomper"];
			$li_neto=$io_fun_nomina->uf_formatonumerico($rs_data->fields["sueper"]);
			$lb_valido=$io_report->uf_listadobeneficiario_beneficiario_cheque($ls_codper,$ls_codban,&$rs_data_dt); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				
				while((!$rs_data_dt->EOF)&&($lb_valido)&&($rs_data_dt->RecordCount()>0))
				{
					$li_s=$li_s+1;
					$ls_cedben=$rs_data_dt->fields["cedben"];
					$ls_apenomben=$rs_data_dt->fields["apeben"].", ". $rs_data_dt->fields["nomben"];					
					$ls_tipben=$rs_data_dt->fields["tipben"];
					$ls_cta=$rs_data_dt->fields["ctaban"];
					
					 if(($rs_data_dt->fields["nomcheben"]=="")&&($rs_data_dt->fields["cedaut"]==""))
					 {
						 $ls_nomaut=$ls_apenomben;
						 $ls_cedaut=$ls_cedben;
						 
					}	
					else
					{
						$ls_nomaut=$rs_data_dt->fields["nomcheben"];
						$ls_cedaut=$rs_data_dt->fields["cedaut"];
						
					}	
					$li_porpagben=$rs_data_dt->fields["porpagben"];
					$li_monpagben=$rs_data_dt->fields["monpagben"];
					$li_monto=0;
					if($li_porpagben>0)
					{
						$li_monto=($li_neto*$li_porpagben)/100;
					}
					if($li_monpagben>0)
					{
						$li_monto=$li_monpagben;
						
					}
					$li_montototalbene=$li_montototalbene+$li_monto;
					$li_monto=$io_fun_nomina->uf_formatonumerico($li_monto);
					$la_data[$li_s]=array('cedpen'=>$ls_cedben,'nompen'=>$ls_apenomben,'cedmil'=>$ls_cedper,'nomil'=>$ls_nomper,
					                      'cedaut'=>$ls_cedaut,'nomaut'=>$ls_nomaut,'monto'=>$li_monto,'recib'=>'_________________________');
					$rs_data_dt->MoveNext();
				}
				unset($row);
				unset($rs_data_dt->fields);
				$io_report->io_sql->free_result($rs_data_dt);
			}
			
			$rs_data->MoveNext();
		}
		
		if(($lb_valido)&&($li_s>0)) // Si no ocurrio ningún error
		{
			uf_print_detalle($la_data,$io_pdf);
			$li_montototalbene=$io_fun_nomina->uf_formatonumerico($li_montototalbene);						
			unset($la_data);
			uf_print_piecabecera($li_s,$li_montototalbene,$io_pdf); // Imprimimos el pie de la cabecera	
			$io_report->io_sql->free_result($rs_data);			
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo algún error
		{
			if($li_s==0)
			{
				print("<script language=JavaScript>");
				print(" alert('No hay nada que Reportar');"); 
				print(" close();");
				print("</script>");
			}
			else
			{
				print("<script language=JavaScript>");
				print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
				print(" close();");
				print("</script>");		
			}
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 
