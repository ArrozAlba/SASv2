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
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Arreglo de las variables de seguridad
		//	    		   as_desnom // Arreglo de las variables de seguridad
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/09/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_descripcion="Generó el Reporte Consolidado ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_recibopago_beneficiario.php",$ls_descripcion);
		return $lb_valido;
	}
	
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina1($as_desnom,$as_periodo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina1
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$as_titulo1="<b>INSTITUCION DE PREVISION SOCIAL</b>";
		$li_tm=$io_pdf->getTextWidth(8,$as_titulo1);		
		$io_pdf->addText(150,750,8,$as_titulo1); // Agregar el título
		$as_titulo2="<b>DE LAS</b>";
		$li_tm=$io_pdf->getTextWidth(8,$as_titulo2);		
		$io_pdf->addText(200,740,8,$as_titulo2); // Agregar el título
		$as_titulo3="<b>FUERZAS ARMADAS</b>";
		$li_tm=$io_pdf->getTextWidth(8,$as_titulo3);		
		$io_pdf->addText(170,730,8,$as_titulo3); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(9,$as_periodo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,710,9,$as_periodo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(9,$as_desnom);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,695,9,"<b>".$as_desnom."</b>"); // Agregar el título
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina1
	//--------------------------------------------------------------------------------------------------------------------------------

	function uf_print_cabecera1(&$io_cabecera,$as_cuenta,$as_banco,$as_ano,$as_categoria,$as_forma,
	                            $as_cedben, $as_nomben, $as_apeben,$ls_parentesco,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera1
		//		   Access: private 
		//	    Arguments: as_cedper // Cédula del personal
		//	    		   as_nomper // Nombre del personal
		//	    		   as_descar // Decripción del cargo
		//	    		   io_cabecera // objeto cabecera
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();		
		$io_pdf->ezSetDy(-35);		
		$as_cedben=number_format($as_cedben,0,",",".");		
		$la_data[1]=array('nombre'=>'<b> PENSIONADO: </b>'.$as_apeben." ".$as_nomben,'cedula'=>'<b>CI. </b>'.$as_cedben,
		                  'parentesco'=>'<b>PARENTESCO CON EL CAUSANTE: </b>'.$ls_parentesco);					
		$la_columna=array('nombre'=>'','cedula'=>'','parentesco'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'xPos'=>315,
						 'fontSize' => 7.5, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>260),
						 			   'cedula'=>array('justification'=>'left','width'=>120),
									   'parentesco'=>array('justification'=>'left','width'=>180))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	   
		///--------------------------------------------------------------------------------------------------------------
		$la_data_banco[1]=array('banco1'=>'<b>FORMA DE PAGO: </b>','banco2'=>$as_forma);
		$la_data_banco[2]=array('banco1'=>'<b>BANCO: </b>','banco2'=>$as_banco);
		$la_data_banco[3]=array('banco1'=>'<b>CUENTA NRO.</b>','banco2'=>$as_cuenta);
		$la_columna=array('banco1'=>'','banco2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'xPos'=>315,
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('banco1'=>array('justification'=>'right','width'=>350),
						 			   'banco2'=>array('justification'=>'left','width'=>210))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_banco,$la_columna,'',$la_config);		
		//----------------------------------------------------------------------------------------------------------------
		$io_pdf->setColor(0,0,0);
		$io_pdf->ezSetDy(-15);
		$la_data_titulo[1]=array('titulo'=>'<b><c:uline>NOMINA DE '.strtoupper($as_categoria).': </c:uline></b>');
		$la_columna=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'left','width'=>560))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_titulo,$la_columna,'',$la_config);
		//--------------------------------------------------------------------------------------------------------------------		
		
		$io_pdf->addText(40,481,'10','______________________________________________________________________________________________');
		$io_pdf->ezSetY(480);
		$la_data=array(array('codcon'=>'<b>CODIGO</b>','denomasig'=>'<b>DESCRIPCION DEL CONCEPTO</b>','cuota'=>'<b>CUOTA / PLAZO</b>', 'valorasig'=>'<b>ASIGNACIONES</b>','valordedu'=>'<b>DEDUCCIONES</b>'));
		$la_columna=array('codcon'=>'<b>CODIGO</b>',
						  'denomasig'=>'<b>DESCRIPCION DEL CONCEPTO</b>',
						  'cuota'=>'<b>CUOTA / PLAZO</b>',
						  'valorasig'=>'<b>ASIGNACIÓN</b>',						
						  'valordedu'=>'<b>DEDUCCIÓN</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codcon'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'denomasig'=>array('justification'=>'center','width'=>140), // Justificación y ancho de la columna
									   'cuota'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'valorasig'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna						 			   
						 			   'valordedu'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->addText(40,465,'10','______________________________________________________________________________________________');
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');
	}// end function uf_print_cabecera

   //--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data_a,$la_data_d,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSety(465);
		$la_columna=array('codcon'=>'',
						  'denomasig'=>'',
						  'cuota'=>'',
						  'valorasig'=>'',						  
						  'valor'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codcon'=>array('justification'=>'left','width'=>70), // Justificación y ancho de la columna
						               'denomasig'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
									   'cuota'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'valorasig'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'valor'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_a,$la_columna,'',$la_config);		
		$la_columna=array('codcon'=>'',
						  'denomdedu'=>'',
						  'cuota'=>'',						  						  
						  'valor'=>'',
						  'valordedu'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codcon'=>array('justification'=>'left','width'=>70),
						               'denomdedu'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
									   'cuota'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						  			   'valor'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'valordedu'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_d,$la_columna,'',$la_config);
	}// end function uf_print_detalle

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera1($ai_toting,$ai_totded,$ai_totnet,$as_codcueban,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera1
		//		   Access: private 
		//	    Arguments: ai_toting // Total Ingresos
		//	   			   ai_totded // Total Deducciones
		//	   			   ai_totnet // Total Neto
		//	   			   as_codcueban // Codigo cuenta bancaria
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares, $io_monedabsf, $ls_tiporeporte;
		
		$io_piepagina=$io_pdf->openObject(); // Creamos el objeto pie de página
		$io_pdf->saveState();
		
		$io_pdf->ezSety(300);
		$la_data=array(array('valor'=>'<b>TOTALES:</b>    ','valorasig'=>$ai_toting,'valordedu'=>$ai_totded));
		$la_columna=array('valor'=>'<b>Columna</b>',
		                  'valorasig'=>'<b>ASIGNACIÓN</b>',						 
						  'valordedu'=>'<b>DEDUCCIÓN</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('valor'=>array('justification'=>'right','width'=>290),
						               'valorasig'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la columna
						 			   'valordedu'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		
		$la_data=array(array('cuenta'=>'', 'neto'=>'<b>Neto a Cobrar '.$ls_bolivares.'</b>  '.$ai_totnet));
		$la_columna=array('cuenta'=>'',
						  'neto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'left','width'=>220), // Justificación y ancho de la columna
						 			   'neto'=>array('justification'=>'right','width'=>250))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->addText(40,300,'10','------------------------------------------------------------------------------------------------------------------------------------------------------------');
		$io_pdf->addText(40,302,'10','------------------------------------------------------------------------------------------------------------------------------------------------------------');	  
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_piepagina,'all');
		$io_pdf->stopObject($io_piepagina); // Detener el objeto pie de página
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
 
	function uf_detalle_nomina_oficial($sueldo,$prima1,$prima2,$prima3,$prima4,$prima5,$prima6,$porcentaje,$subtotal,
	                                   $as_porcentajeben,$as_feleypen, $l_nomcau, $as_cedper, $as_ano, $io_pdf)
	{
		$io_pdf->ezSety(585);
		$ls_subtotal2=0;
		$ls_subtotal3=0;
		$ls_totalbene=0;
		$ls_subtotal2= $sueldo + $prima1 + $prima2 + $prima3 + $prima4 + $prima5 + $prima6;
		$ls_subtotal3= $ls_subtotal2*($porcentaje/100);
		$ls_totalbene= $ls_subtotal3*($as_porcentajeben/100);
	    $la_data1[1]=array('sueldo'=>'<b>S. BASICO: </b>'.number_format($sueldo,2,",","."),
		                   'prima1'=>'<b>P. CHOF/T: </b>'.number_format($prima1,2,",","."));
		$la_columna=array('sueldo'=>'','prima1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array(  'sueldo'=>array('justification'=>'left','width'=>300.3),
										 'prima1'=>array('justification'=>'left','width'=>180.3))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);	
		
		$la_data2[1]=array('prima2'=>'<b>    P. AÑOS SVC </b>:'.number_format($prima2,2,",","."),
		                   'prima3'=>'<b>    P. DESCEND.</b>: '.number_format($prima3,2,",","."));
		$la_columna=array('prima2'=>'','prima3'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>'281',
						 'cols'=>array(  'prima2'=>array('justification'=>'left','width'=>300.3),
										 'prima3'=>array('justification'=>'left','width'=>180.3))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data2,$la_columna,'',$la_config);
			
		$la_data3[1]=array('prima4'=>'<b>P. NO ASCENSO: </b>'.number_format($prima4,2,",","."),
						   'prima5'=>'<b>P. ESPECIAL :</b>'.number_format($prima5,2,",","."));
		$la_columna=array('prima4'=>'','prima5'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array(  'prima4'=>array('justification'=>'left','width'=>300.3),
						 			     'prima5'=>array('justification'=>'left','width'=>180.3))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data3,$la_columna,'',$la_config);	
		$la_data6[1]=array('prima6'=>'<b>P. PROFESION.:</b> '.number_format($prima6,2,",","."),
		                   'prima7'=>'<b>PENS. RET: </b>'.number_format($ls_subtotal2,2,",",".").'<b> X (</b>'.number_format($porcentaje,2,",",".").' % + 0%)');
		$la_columna=array('prima6'=>'','prima7'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('prima6'=>array('justification'=>'left','width'=>300.3),
									   'prima7'=>array('justification'=>'left','width'=>180.3))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data6,$la_columna,'',$la_config);	
		
		$la_data5[1]=array('subtotal'=>'<b>PENS. SOB: </b>'.number_format($ls_subtotal3,2,",",".").'<b> X (</b>'.$as_porcentajeben.' %)',
		                   'total'=>'<b>TOTAL: </b>'.number_format($ls_subtotal3,2,",","."));
		$la_columna=array('subtotal'=>'','total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>'300',
						 'cols'=>array('subtotal'=>array('justification'=>'left','width'=>300.3)),
						 			   'total'=>array('justification'=>'left','width'=>180.3)); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data5,$la_columna,'',$la_config);
		$ls_porbene=$subtotal*(($as_porcentajeben)/100);
		$ls_porbene=number_format($ls_porbene,2,",",".");	
		$la_data6[1]=array('bene'=>'','totalbene'=>'<b>TOTAL PENSIONADO. </b>'.number_format($ls_totalbene,2,",","."));
		$la_columna=array('bene'=>'', 'totalbene'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>'290',
						 'cols'=>array('bene'=>array('justification'=>'left','width'=>300.3),
						               'totalbene'=>array('justification'=>'left','width'=>180.3))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data6,$la_columna,'',$la_config);	
		
		$io_pdf->ezSety(660);
		$la_data[1]=array('nombre'=>'<b>CAUSANTE: </b>'.$l_nomcau,'cedula'=>'<b>CI. </b>'.$as_cedper);				
		$la_columna=array('nombre'=>'','cedula'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'xPos'=>315,
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>220),
						 			   'cedula'=>array('justification'=>'left','width'=>340))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		
	    $la_data_c[1]=array('servicio'=>'<b>TIEMPO DE SERVICIO: </b>'.$as_ano.'<b> AÑOS</b>');		
		$la_columna=array('servicio'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'xPos'=>315,
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('servicio'=>array('justification'=>'left','width'=>560))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_c,$la_columna,'',$la_config);	
			
		$la_data4[1]=array('porcentaje'=>'<b>PORCEN. PENS </b>:'.$porcentaje.' %');
		$la_columna=array('porcentaje'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array(  'porcentaje'=>array('justification'=>'left','width'=>514))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data4,$la_columna,'',$la_config);	
		
		$la_data5[1]=array('ley'=>'<b>PENSION. LSSFAN ('.$as_feleypen.')</b>');
		$la_columna=array('ley'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('ley'=>array('justification'=>'left','width'=>514))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data5,$la_columna,'',$la_config);		
	}
	
//--------------------------------------------------------------------------------------------------------------------------

      function calcular_anos_servicioas($fecha_ingreso,$fecha_egreso)
	  {  
		  $c = date("Y",$fecha_ingreso);	   
		  $b = date("m",$fecha_ingreso);	  
		  $a = date("d",$fecha_ingreso); 	  
		  $anos = date("Y",$fecha_egreso)-$c; 
	   
			  if(date("m",$fecha_egreso)-$b > 0){
		  
			  }elseif(date("m",$fecha_egreso)-$b == 0){
		 
			  if(date("d",$fecha_egreso)-$a <= 0)
			  {		  
			     $anos = $anos-1;	  
			  }
		  
			  }else{		  
			         $anos = $anos-1;		  
			       }  
		  return $anos;	 
      }
//-------------------------------------------------------------------------------------------------------------------------
	function uf_print_autorizado($la_cedaut,$la_nomaut,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_autorizado
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSety(270);
		$la_data[1]=array('cedaut'=>'<b>CEDULA DEL AUTORIZADO: </b>'.$la_cedaut,
		                  'nomaut'=>'<b>NOMBRE DEL AUTORIZADO: </b>'.$la_nomaut);
		$la_columna=array('cedaut'=>'',
						  'nomaut'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('cedaut'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
						               'nomaut'=>array('justification'=>'left','width'=>250))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);			
	}// end uf_print_autorizado
//--------------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	$ls_tiporeporte="0";
	$ls_bolivares ="Bs.";
	switch($ls_tiporeporte)
	{
		case "0":
			require_once("sigesp_snorh_class_report.php");
			$io_report=new sigesp_snorh_class_report();
			$ls_bolivares ="Bs.";
			break;

		case "1":
			require_once("sigesp_snorh_class_reportbsf.php");
			$io_report=new sigesp_snorh_class_reportbsf();
			$ls_bolivares ="Bs.F.";
			break;
	}
	require_once("../../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
	$io_monedabsf=new sigesp_c_reconvertir_monedabsf();				
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();	
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_codnom=$io_fun_nomina->uf_obtenervalor_get("codnom",""); 
	$ls_desnom="<b>".$io_fun_nomina->uf_obtenervalor_get("desnom","")."</b>";
	$ls_codbendes=$io_fun_nomina->uf_obtenervalor_get("codbendes",""); 
	$ls_codbenhas=$io_fun_nomina->uf_obtenervalor_get("codbenhas","");
	$ls_codperides=$io_fun_nomina->uf_obtenervalor_get("codperides","");
	$ls_codperihas=$io_fun_nomina->uf_obtenervalor_get("codperihas","");
	$ld_fecdesper=$io_fun_nomina->uf_obtenervalor_get("fecdesper","");
	$ld_fechasper=$io_fun_nomina->uf_obtenervalor_get("fechasper","");
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_coduniadm=$io_fun_nomina->uf_obtenervalor_get("coduniadm","");
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_conceptop2=$io_fun_nomina->uf_obtenervalor_get("conceptop2","");
	$ls_conceptoreporte=$io_fun_nomina->uf_obtenervalor_get("conceptoreporte","");
	$ls_tituloconcepto=$io_fun_nomina->uf_obtenervalor_get("tituloconcepto","");	
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("codsubnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("codsubnomhas","");
	$ls_titulo="<b>COMPROBANTE DE PAGO BENEFICIARIO</b>";
	$ls_periodo="Periodos: <b>".$ls_codperides." - ".$ls_codperihas."</b> del <b>".$ld_fecdesper."</b> al <b>".$ld_fechasper."</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo); // Seguridad de Reporte

	if($lb_valido)
	{
		$lb_valido=$io_report->uf_buscar_beneficiarios($ls_codbendes, $ls_codbenhas, $ls_codperdes, $ls_codperhas);
	}
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
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3,1,1,2); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina1($ls_desnom,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la página
		$li_bene=$io_report->DS_pension->getRowCount("codben");  
		for($li_j=1;(($li_j<=$li_bene)&&($lb_valido));$li_j++)
		{
			$ls_codperdes2=$io_report->DS_pension->data["codper"][$li_j];
			$ls_codperhas2=$io_report->DS_pension->data["codper"][$li_j];
			$ls_codperdes2=str_pad($io_report->DS_pension->data["cedben"][$li_j],10,"0",0);
			$ls_codperhas2=str_pad($io_report->DS_pension->data["cedben"][$li_j],10,"0",0);			
			$ls_codpe_cau=$io_report->DS_pension->data["codper"][$li_j];
			$ls_cedben=$io_report->DS_pension->data["cedben"][$li_j];
			$ls_cedcaut=$io_report->DS_pension->data["cedaut"][$li_j];		
			$ls_nomaut=$io_report->DS_pension->data["nomcheben"][$li_j];
			$ls_nexben=$io_report->DS_pension->data["nexben"][$li_j];
			switch ($ls_nexben)
			{
				 case "-":
					$ls_parentesco="Sin parentesco";
				break;
				case "C":
					$ls_parentesco="Conyuge";
				break;
				case "H":
					$ls_parentesco="Hijo";
				break;
				case "P":
					$ls_parentesco="Progenitor";
				break;
				case "E":
					$ls_parentesco="Hermano";
				break;
			} 
		 	$lb_valido=$io_report->uf_recibopago_personal($ls_codnom,$ld_fecdesper,$ld_fechasper,$ls_codperdes2,$ls_codperhas2,
									$ls_coduniadm,$ls_conceptocero,$ls_conceptop2,$ls_conceptoreporte,$ls_subnomdes,$ls_subnomhas,$ls_orden);
									
			$ls_cedben=$io_report->DS_pension->data["cedben"][$li_j];
			$ls_nomben=$io_report->DS_pension->data["nomben"][$li_j];	
			$ls_apeben=$io_report->DS_pension->data["apeben"][$li_j];	
			$ls_porcentajeben=$io_report->DS_pension->data["porpagben"][$li_j];										   
			$li_totrow=$io_report->DS->getRowCount("codper");	
			
			for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
			{   
				$li_toting=0;
				$li_totded=0;
				$ls_codper=$io_report->DS->data["codper"][$li_i];
				$ls_cedper=$io_report->DS->data["cedper"][$li_i];
				$ls_nomper=$io_report->DS->data["apeper"][$li_i].", ".$io_report->DS->data["nomper"][$li_i];
				$ls_descar=$io_report->DS->data["descar"][$li_i];
				$ls_codcueban=$io_report->DS->data["codcueban"][$li_i];
				$li_total=$io_report->DS->data["total"][$li_i];
				$li_adelanto=$io_report->DS->data["adenom"][$li_i];
				$ls_unidad=$io_report->DS->data["desuniadm"][$li_i];
				$ls_banco=$io_report->DS->data["banco"][$li_i];
				$ls_codnom=$io_report->DS->data["codnom"][$li_i];
				$io_cabecera=$io_pdf->openObject(); // Creamos el objeto cabecera
				$ls_fecha_ingreso=$io_report->DS->data["fecingper"][$li_i];	
				$ls_fecha_egreso=$io_report->DS->data["fecegrper"][$li_i];			
				$ls_ano=calcular_anos_servicioas(strtotime($ls_fecha_ingreso),strtotime($ls_fecha_egreso));	
				$ls_categoria=$io_report->DS->data["descat"][$li_i];
				$ls_tipopago=$io_report->DS->data["tipcuebanper"][$li_i];	
				$ls_fecleypen=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecleypen"][$li_i]);
				switch($ls_tipopago)
				{
					case "A":
						$ls_forma="CUENTA DE AHORRO";
					break;
					case "C":
						$ls_forma="CUENTA CORRIENTE";
					break;
					case " ":
					break;					
				}	 					
				
				uf_print_cabecera1($io_cabecera,$ls_codcueban,$ls_banco,$ls_ano,
				                   $ls_categoria,$ls_forma,$ls_cedben, $ls_nomben, $ls_apeben, $ls_parentesco, $io_pdf); // Imprimimos la cabecera del registro	
				if ($ls_cedben!=$ls_cedcaut)
				{
					uf_print_autorizado($ls_cedcaut,$ls_nomaut,&$io_pdf);	
				}				
				$lb_valido=$io_report->uf_recibopago_conceptopersonal($ls_codnom,$ld_fecdesper,$ld_fechasper,$ls_codper,
																  $ls_conceptocero,$ls_conceptop2,$ls_conceptoreporte,
																  $ls_tituloconcepto); // Obtenemos el detalle del reportedel Obtenemos el detalle del reporte
				if($lb_valido)
				{
					$li_totrow_det=$io_report->DS_detalle->getRowCount("codconc");
					$li_asig=0;
					$li_dedu=0;
					if($li_adelanto==1)// Utiliza el adelanto de quincena
					{  						
						for($li_s=1;$li_s<=$li_totrow_det;$li_s++) 
						{
							$ls_tipsal=rtrim($io_report->DS_detalle->data["tipsal"][$li_s]);
							if(($ls_tipsal=="A") || ($ls_tipsal=="V1") || ($ls_tipsal=="V2") || ($ls_tipsal=="R")) // Buscamos las asignaciones
							{
							 	$li_asig=$li_asig+1;
								$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_s];
								$ls_nomcon=$io_report->DS_detalle->data["nomcon"][$li_s];
								$li_toting=$li_toting+abs($io_report->DS_detalle->data["valsal"][$li_s]);
								$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["valsal"][$li_s]));
								$ls_repconsunicon=$io_report->DS_detalle->data["repconsunicon"][$li_s];
								$ls_consunicon=$io_report->DS_detalle->data["consunicon"][$li_s];
								$ls_cuota="";
								if (($ls_repconsunicon=='1')&&($ls_consunicon!=""))
								{
									$io_report->uf_buscar_cuotas($ls_consunicon,$ls_codper,$ls_codnom,$ls_cuota);
								}
								$la_data_a[$li_asig]=array('codcon'=>$ls_codconc,'denominacion'=>$ls_nomcon,'valor'=>$li_valsal,'cuota'=>$ls_cuota);
							}
							else // Buscamos las deducciones y aportes
							{
								$li_dedu=$li_dedu+1;
								$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_s];
								$ls_nomcon=$io_report->DS_detalle->data["nomcon"][$li_s];
								$li_totded=$li_totded+abs($io_report->DS_detalle->data["valsal"][$li_s]);
								$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["valsal"][$li_s]));
								$ls_repconsunicon=$io_report->DS_detalle->data["repconsunicon"][$li_s];
								$ls_consunicon=$io_report->DS_detalle->data["consunicon"][$li_s];
								$ls_cuota="";
								if (($ls_repconsunicon=='1')&&($ls_consunicon!=""))
								{
									$io_report->uf_buscar_cuotas($ls_consunicon,$ls_codper,$ls_codnom,$ls_cuota);
								}
								$la_data_d[$li_dedu]=array('codcon'=>$ls_codconc,'denominacion'=>$ls_nomcon,'valor'=>$li_valsal,'cuota'=>$ls_cuota);
							}
						}
						
				    }
					else
					{   
						for($li_s=1;$li_s<=$li_totrow_det;$li_s++) 
						{
							$ls_tipsal=rtrim($io_report->DS_detalle->data["tipsal"][$li_s]);						
							if(($ls_tipsal=="A") || ($ls_tipsal=="V1") || ($ls_tipsal=="V2") || ($ls_tipsal=="R")) // Buscamos las asignaciones
							{
								$li_asig=$li_asig+1;
								$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_s];
								$ls_nomcon=$io_report->DS_detalle->data["nomcon"][$li_s];
								$li_toting=$li_toting+abs($io_report->DS_detalle->data["valsal"][$li_s]);
								$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["valsal"][$li_s]));
								$ls_repconsunicon=$io_report->DS_detalle->data["repconsunicon"][$li_s];
								$ls_consunicon=$io_report->DS_detalle->data["consunicon"][$li_s];
								$ls_cuota="";
								if (($ls_repconsunicon=='1')&&($ls_consunicon!=""))
								{
									$io_report->uf_buscar_cuotas($ls_consunicon,$ls_codper,$ls_codnom,$ls_cuota);
								}
								$la_data_a[$li_asig]=array('codcon'=>$ls_codconc,'denominacion'=>$ls_nomcon,'valor'=>$li_valsal,'cuota'=>$ls_cuota);
							}
							else // Buscamos las deducciones y aportes
							{
								$li_dedu=$li_dedu+1;
								$ls_codconc=$io_report->DS_detalle->data["codconc"][$li_s];
								$ls_nomcon=$io_report->DS_detalle->data["nomcon"][$li_s];
								$li_totded=$li_totded+abs($io_report->DS_detalle->data["valsal"][$li_s]*($ls_porcentajeben/100));
								$li_valsal=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS_detalle->data["valsal"][$li_s]));
								$ls_repconsunicon=$io_report->DS_detalle->data["repconsunicon"][$li_s];
								$ls_consunicon=$io_report->DS_detalle->data["consunicon"][$li_s];
								$ls_cuota="";
								if (($ls_repconsunicon=='1')&&($ls_consunicon!=""))
								{
									$io_report->uf_buscar_cuotas($ls_consunicon,$ls_codper,$ls_codnom,$ls_cuota);
								}
								$la_data_d[$li_dedu]=array('codcon'=>$ls_codconc,'denominacion'=>$ls_nomcon,'valor'=>$li_valsal,'cuota'=>$ls_cuota);
							}
						}
				  } 					
				
				  if($li_asig<=$li_dedu)
				  {
						$li_total=$li_dedu;
				  }
				  else
				  {
				   		$li_total=$li_asig;
				  }
					
					for($li_s=1;$li_s<=$li_total;$li_s++) 
					{
						$la_valores_a["codcon"]="";
						$la_valores_a["denomasig"]="";
						$la_valores_a["valorasig"]="";
						$la_valores_a["valor"]="";
						$la_valores_a["cuota"]="";
						$la_valores_d["codcon"]="";
						$la_valores_d["denomdedu"]="";
						$la_valores_d["valordedu"]="";
						$la_valores_d["valor"]="";
						$la_valores_d["cuota"]="";					
						
						if($li_s<=$li_asig)
						{  
							$la_valores_a["codcon"]=$la_data_a[$li_s]["codcon"];
							$la_valores_a["denomasig"]=$la_data_a[$li_s]["denominacion"];
							$la_valores_a["valorasig"]=$la_data_a[$li_s]["valor"];
							$la_valores_a["valor"]="";
							$la_valores_a["cuota"]=$la_data_a[$li_s]["cuota"];
							$la_data_a[$li_s]=$la_valores_a;
						}
						if($li_s<=$li_dedu)
						{
							$la_valores_d["codcon"]=$la_data_d[$li_s]["codcon"];
							$la_valores_d["denomdedu"]=$la_data_d[$li_s]["denominacion"];
							$la_valores_d["valordedu"]=$la_data_d[$li_s]["valor"];
							$la_valores_d["valor"]="";
							$la_valores_d["cuota"]=$la_data_a[$li_s]["cuota"];
							$la_data_d[$li_s]=$la_valores_d;
						}
						else
						{
							 $la_valores_a["codcon"]="";
							 $la_valores_a["denomasig"]="";
							 $la_valores_a["valorasig"]="";
							 $la_valores_a["valor"]="";
							 $la_valores_a["cuota"]="";
							 $la_valores_d["codcon"]="";
							 $la_valores_d["denomdedu"]="";
							 $la_valores_d["valordedu"]="";
							 $la_valores_d["valor"]="";
							 $la_valores_d["cuota"]="";	
							 $la_data_d[$li_s]=$la_valores_d;
						}
						
											
					}
					
					$lb_valido1=$io_report->uf_recibo_nomina_oficiales($ls_codpe_cau,$ls_codnom);	
					if ($lb_valido1)
					{
					  $li_nom=$io_report->DS_pension->getRowCount("codper");
					  for($li_j=1;(($li_j<=$li_nom)&&($lb_valido1));$li_j++)
					  {
						$ls_sueldob=$io_report->DS_pension->data["suebasper"][$li_j];
						$ls_prichof=$io_report->DS_pension->data["pritraper"][$li_j];
						$ls_prianoserv=$io_report->DS_pension->data["prianoserper"][$li_j];
						$ls_prides=$io_report->DS_pension->data["pridesper"][$li_j];
						$ls_noasc=$io_report->DS_pension->data["prinoascper"][$li_j];
						$ls_priesp=$io_report->DS_pension->data["priespper"][$li_j];
						$ls_priprof=$io_report->DS_pension->data["priproper"][$li_j];
						$ls_subtotal=$io_report->DS_pension->data["subtotper"][$li_j];					
						$ls_porcentaje=$io_report->DS_pension->data["porpenper"][$li_j];
						$ls_nomper=$io_report->DS_pension->data["nomper"][$li_j];
						$ls_aperper=$io_report->DS_pension->data["apeper"][$li_j];
						$ls_nompercau=$ls_aperper.", ".$ls_aperper;
						$ls_cedpercau=$io_report->DS_pension->data["cedper"][$li_j];
						$ls_fecingcau=$io_report->DS_pension->data["fecingper"][$li_j];
						$ls_fecingnom=$io_report->DS_pension->data["fecingnom"][$li_j];
						$ls_com=$io_report->DS_pension->data["descom"][$li_j];
						$ls_rango=$io_report->DS_pension->data["desran"][$li_j];
						$ls_ano=calcular_anos_servicioas(strtotime($ls_fecingcau),strtotime($ls_fecingnom));							
						if ($ls_ano<0)
						{
							$ls_ano=0;
						}							
					  }					
						uf_detalle_nomina_oficial($ls_sueldob,
												$ls_prichof,
												$ls_prianoserv,
												$ls_prides,
												$ls_noasc,
												$ls_priesp,
												$ls_priprof,
												$ls_porcentaje,
												$ls_subtotal,
												$ls_porcentajeben,	
												$ls_fecleypen,
												$ls_nompercau,
												$ls_cedpercau,
												$ls_ano,											
												$io_pdf);
					}
					else
					{
					  uf_detalle_nomina_oficial(0,0,0,0,0,0,0,0,0,0,0,'','',0,$io_pdf);
					}
							
					uf_print_detalle($la_data_a,$la_data_d,$io_pdf); // Imprimimos el detalle 
					$li_totnet=$li_toting-$li_totded;
					$li_toting=$io_fun_nomina->uf_formatonumerico($li_toting);
					$li_totded=$io_fun_nomina->uf_formatonumerico($li_totded);
					$li_totnet=$io_fun_nomina->uf_formatonumerico($li_totnet);						
					uf_print_pie_cabecera1($li_toting,$li_totded,$li_totnet,$ls_codcueban,$io_pdf); // Imprimimos pie de la cabecera									
					$io_report->DS_detalle->resetds("codconc");
					unset($la_data_a);
					unset($la_data_d);
					unset($la_data);
									
				}
				if(($li_j<$li_bene))
				{
					$io_pdf->ezNewPage(); // Insertar una nueva página						
				}
			}// fin del for (pensinado)
		}// fin del For (beneficiario)
		
		$io_report->DS->resetds("codper");
		$io_report->DS_pension->resetds("codben");
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