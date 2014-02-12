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
	ini_set('memory_limit','512M');
	ini_set('max_execution_time','0');

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_scf;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_scf->uf_load_seguridad_reporte("SCF","sigesp_scf_r_balance_general.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_titulo1,$as_titulo2,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 28/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(20,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,715,11,$as_titulo); // Agregar el título		
		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo1);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,700,11,$as_titulo1); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo2);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,685,11,$as_titulo2); // Agregar el título	

		$io_pdf->addText(510,730,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(510,720,8,date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,$la_data_p,$la_data_t,$total_activo_t,$ls_total_pasivo, $total_pasivo_result,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 28/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$la_data_T=array(array('activo'=>'<b>ACTIVO</b>','pasivo'=>'<b>PASIVO</b>'));
		$la_columna=array('activo'=>'','pasivo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 9, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>560, // Ancho Máximo de la tabla
						 'colGap'=>1, // separacion entre tablas
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('activo'=>array('justification'=>'center','width'=>300), // Justificación y ancho de la columna
						 			   'pasivo'=>array('justification'=>'center','width'=>260))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_T,$la_columna,'',$la_config);	
			
		$io_pdf->ezSetY(640);		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>560, // Ancho de la tabla
						 'maxWidth'=>560, // Ancho Máximo de la tabla
						 'xOrientation'=>'left', // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'left','width'=>20), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'saldo'=>array('justification'=>'right','width'=>100),
						 			   'vacio'=>array('justification'=>'right','width'=>10))); // Justificación y ancho de la columna
		$la_columnas=array('cuenta'=>'',
						   'denominacion'=>'',
						   'saldo'=>'',
						   'vacio'=>'');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);	
		//--------------------------------------------------------------------------------------------------------------------
		$io_pdf->ezSetY(640);
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>560, // Ancho de la tabla		
						 'maxWidth'=>560, // Ancho Máximo de la tabla				
						 'xOrientation'=>'right', // Orientación de la tabla
						 'cols'=>array('cuenta_p'=>array('justification'=>'left','width'=>20), // Justificación y ancho de la columna
						 			   'denom_p'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'saldo_p'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$la_columnas=array('cuenta_p'=>'',
						   'denom_p'=>'',
						   'saldo_p'=>'');
		$io_pdf->ezTable($la_data_p,$la_columnas,'',$la_config);
		//-----------------------------------------------------------------------------------------------------------------------
		
		$io_pdf->ezSetY(575);
		$la_data_total_p[1]=array('total'=>'','vacio'=>'<b>----------------------</b>');
		$la_data_total_p[2]=array('total'=>'<b>Sub Total</b>','vacio'=>'<b>'.$ls_total_pasivo.'</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>560, // Ancho de la tabla		
						 'maxWidth'=>560, // Ancho Máximo de la tabla				
						 'xOrientation'=>'right', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>150),
						               'vacio'=>array('justification'=>'right','width'=>110))); // Justificación y ancho de la columna
		$la_columnas=array('total'=>'','vacio'=>'');
		$io_pdf->ezTable($la_data_total_p,$la_columnas,'',$la_config);
		
		//------------------------------------------------------------------------------------------------------------------------
		
		$io_pdf->ezSetY(535);
		$la_data_total_p[1]=array('total'=>'','vacio'=>'<b>----------------------</b>');
		$la_data_total_p[2]=array('total'=>'','vacio'=>'<b>'.$total_pasivo_result.'</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>560, // Ancho de la tabla		
						 'maxWidth'=>560, // Ancho Máximo de la tabla				
						 'xOrientation'=>'right', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>150),
						               'vacio'=>array('justification'=>'right','width'=>110))); // Justificación y ancho de la columna
		$la_columnas=array('total'=>'','vacio'=>'');
		$io_pdf->ezTable($la_data_total_p,$la_columnas,'',$la_config);
		
		//----------------------------------------------------------------------------------------------------------------------
		
		$io_pdf->ezSetY(540);
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>560, // Ancho de la tabla		
						 'maxWidth'=>560, // Ancho Máximo de la tabla				
						 'xOrientation'=>'right', // Orientación de la tabla
						 'cols'=>array('cuenta_t'=>array('justification'=>'left','width'=>20), // Justificación y ancho de la columna
						 			   'denom_t'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'saldo_t'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$la_columnas=array('cuenta_t'=>'',
						   'denom_t'=>'',
						   'saldo_t'=>'');
		$io_pdf->ezTable($la_data_t,$la_columnas,'',$la_config);
		
		
		$io_pdf->ezSetY(540);
		$la_data_total[1]=array('total'=>'<b>----------------------</b>','vacio'=>'');
		$la_data_total[2]=array('total'=>'<b>'.$total_activo_t.'</b>','vacio'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>560, // Ancho de la tabla		
						 'maxWidth'=>560, // Ancho Máximo de la tabla				
						 'xOrientation'=>'left', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>240),
						               'vacio'=>array('justification'=>'right','width'=>10))); // Justificación y ancho de la columna
		$la_columnas=array('total'=>'','vacio'=>'');
		$io_pdf->ezTable($la_data_total,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	function uf_print_detalle_2($la_data,$la_data_p_h,$ls_total_activo_H,$ls_total_pasivo_H,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 28/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(470);
		$la_data_T=array(array('titulo'=>'<b>CUENTAS DE LA HACIENDA</b>'));
		$la_columna=array('titulo'=>'',);
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 9, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>560, // Ancho Máximo de la tabla
						 'colGap'=>1, // separacion entre tablas
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('titulo'=>array('justification'=>'center','width'=>560))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_T,$la_columna,'',$la_config);	
					
		$io_pdf->ezSetY(450);		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>560, // Ancho de la tabla
						 'maxWidth'=>560, // Ancho Máximo de la tabla
						 'xOrientation'=>'left', // Orientación de la tabla
						 'cols'=>array('cuenta_h'=>array('justification'=>'left','width'=>20), // Justificación y ancho de la columna
						 			   'denom_h'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'saldo_h'=>array('justification'=>'right','width'=>100),
						 			   'vacio'=>array('justification'=>'right','width'=>10))); // Justificación y ancho de la columna
		$la_columnas=array('cuenta_h'=>'',
						   'denom_h'=>'',
						   'saldo_h'=>'',
						   'vacio'=>'');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);	
		//--------------------------------------------------------------------------------------------------------------------
		$io_pdf->ezSetY(450);
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>560, // Ancho de la tabla		
						 'maxWidth'=>560, // Ancho Máximo de la tabla				
						 'xOrientation'=>'right', // Orientación de la tabla
						 'cols'=>array('cuenta_p_h'=>array('justification'=>'left','width'=>20), // Justificación y ancho de la columna
						 			   'denom_p_h'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'saldo_p_h'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$la_columnas=array('cuenta_p_h'=>'',
						   'denom_p_h'=>'',
						   'saldo_p_h'=>'');
		$io_pdf->ezTable($la_data_p_h,$la_columnas,'',$la_config);
		//-----------------------------------------------------------------------------------------------------------------------
		$io_pdf->ezSetY(360);
		$la_data_total_H[1]=array('total'=>'<b>----------------------</b>','vacio'=>'');
		$la_data_total_H[2]=array('total'=>'<b>'.$ls_total_activo_H.'</b>','vacio'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>560, // Ancho de la tabla		
						 'maxWidth'=>560, // Ancho Máximo de la tabla				
						 'xOrientation'=>'left', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>240),
						               'vacio'=>array('justification'=>'right','width'=>10))); // Justificación y ancho de la columna
		$la_columnas=array('total'=>'','vacio'=>'');
		$io_pdf->ezTable($la_data_total_H,$la_columnas,'',$la_config);
		//---------------------------------------------------------------------------------------------------------------------------
		//-----------------------------------------------------------------------------------------------------------------------
		$io_pdf->ezSetY(360);
		$la_data_total_P_H[1]=array('total'=>'','vacio'=>'<b>----------------------</b>');
		$la_data_total_P_H[2]=array('total'=>'','vacio'=>'<b>'.$ls_total_pasivo_H.'</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>560, // Ancho de la tabla		
						 'maxWidth'=>560, // Ancho Máximo de la tabla				
						 'xOrientation'=>'right', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>10),
						               'vacio'=>array('justification'=>'right','width'=>250))); // Justificación y ancho de la columna
		$la_columnas=array('total'=>'','vacio'=>'');
		$io_pdf->ezTable($la_data_total_P_H,$la_columnas,'',$la_config);
		//---------------------------------------------------------------------------------------------------------------------------
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	
	function uf_print_detalle_3($la_data,$la_data_g,$ls_total_ingreso,$ls_total_gastos,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 28/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(330);
		$la_data_T=array(array('titulo'=>'<b>CUENTAS DE PRESUPUESTO</b>'));
		$la_columna=array('titulo'=>'',);
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 9, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>560, // Ancho Máximo de la tabla
						 'colGap'=>1, // separacion entre tablas
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('titulo'=>array('justification'=>'center','width'=>560))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_T,$la_columna,'',$la_config);				
		
		//--------------------------------------------------------------------------------------------------------------------
		$io_pdf->ezSetY(300);
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>560, // Ancho de la tabla		
						 'maxWidth'=>560, // Ancho Máximo de la tabla				
						 'xOrientation'=>'left', // Orientación de la tabla
						 'cols'=>array('cuenta_g'=>array('justification'=>'left','width'=>20), // Justificación y ancho de la columna
						 			   'denom_g'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'saldo_g'=>array('justification'=>'right','width'=>100),
						 			   'vacio'=>array('justification'=>'right','width'=>10))); // Justificación y ancho de la columna
		$la_columnas=array('cuenta_g'=>'',
						   'denom_g'=>'',
						   'saldo_g'=>'',
						   'vacio'=>'');
		$io_pdf->ezTable($la_data_g,$la_columnas,'',$la_config);
		
		$io_pdf->ezSetY(300);	
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>560, // Ancho de la tabla
						 'maxWidth'=>560, // Ancho Máximo de la tabla
						 'xOrientation'=>'right', // Orientación de la tabla
						 'cols'=>array('cuenta_i'=>array('justification'=>'left','width'=>20), // Justificación y ancho de la columna
						 			   'denom_i'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'saldo_i'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$la_columnas=array('cuenta_i'=>'',
						   'denom_i'=>'',
						   'saldo_i'=>'');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		
		//------------------------------------------------------------------------------------------------------------------------
		$io_pdf->ezSetY(280);
		$la_data_total_i[1]=array('total'=>'','vacio'=>'<b>----------------------</b>');
		$la_data_total_i[2]=array('total'=>'','vacio'=>'<b>'.$ls_total_ingreso.'</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>560, // Ancho de la tabla		
						 'maxWidth'=>560, // Ancho Máximo de la tabla				
						 'xOrientation'=>'right', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>20),
						               'vacio'=>array('justification'=>'right','width'=>240))); // Justificación y ancho de la columna
		$la_columnas=array('total'=>'','vacio'=>'');
		$io_pdf->ezTable($la_data_total_i,$la_columnas,'',$la_config);
		//------------------------------------------------------------------------------------------------------------------	
		//------------------------------------------------------------------------------------------------------------------------
		$io_pdf->ezSetY(280);
		$la_data_total_g[1]=array('total'=>'<b>----------------------</b>','vacio'=>'');
		$la_data_total_g[2]=array('total'=>'<b>'.$ls_total_gastos.'</b>','vacio'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>560, // Ancho de la tabla		
						 'maxWidth'=>560, // Ancho Máximo de la tabla				
						 'xOrientation'=>'left', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>240),
						               'vacio'=>array('justification'=>'right','width'=>10))); // Justificación y ancho de la columna
		$la_columnas=array('total'=>'','vacio'=>'');
		$io_pdf->ezTable($la_data_total_g,$la_columnas,'',$la_config);
		//------------------------------------------------------------------------------------------------------------------	
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ld_total,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	    Arguments : ad_totaldebe // Total debe
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por : Ing. Yozelin Barragan
		// Fecha Creación : 18/02/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$la_data=array(array('total'=>'<b>Total Pasivo + Capital + Resultado del Ejercicio '.$ls_bolivares.'</b>','totalgen'=>$ld_total));
		$la_columna=array('total'=>'','totalgen'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 9, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>560, // Ancho Máximo de la tabla
						 'colGap'=>1, // separacion entre tablas
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>300), // Justificación y ancho de la columna
						 			   'totalgen'=>array('justification'=>'right','width'=>260))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_init_niveles()
	{	///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_init_niveles
		//	     Access: public
		//	    Returns: vacio	 
		//	Description: Este método realiza una consulta a los formatos de las cuentas
		//               para conocer los niveles de la escalera de las cuentas contables  
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones,$ia_niveles_scg;
		
		$ls_formato=""; $li_posicion=0; $li_indice=0;
		$dat_emp=$_SESSION["la_empresa"];
		//contable
		$ls_formato = trim($dat_emp["formcont"])."-";
		$li_posicion = 1 ;
		$li_indice   = 1 ;
		$li_posicion = $io_funciones->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		do
		{
			$ia_niveles_scg[$li_indice] = $li_posicion;
			$li_indice   = $li_indice+1;
			$li_posicion = $io_funciones->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		} while ($li_posicion>=0);
	}// end function uf_init_niveles
	//-----------------------------------------------------------------------------------------------------------------------------------

	 require_once("../../shared/ezpdf/class.ezpdf.php");
	 require_once("../../shared/class_folder/class_funciones.php");
	 $io_funciones=new class_funciones();
	 require_once("../../shared/class_folder/class_fecha.php");
	 require_once("../../shared/class_folder/class_sql.php");
	 require_once("../../shared/class_folder/sigesp_include.php");
	 require_once("../../shared/class_folder/class_sigesp_int.php");
	 require_once("../../shared/class_folder/class_sigesp_int_scg.php");
	$ls_tiporeporte="0";
	$ls_bolivares="";
	if (array_key_exists("tiporeporte",$_GET))
	{
		$ls_tiporeporte=$_GET["tiporeporte"];
	}
	switch($ls_tiporeporte)
	{
		case "0":
			require_once("sigesp_scf_class_bal_general.php");
			$io_report  = new sigesp_scf_class_bal_general();
			$ls_bolivares ="Bs.";
			break;
	
		case "1":
			require_once("sigesp_scg_class_bal_generalbsf.php");
			$io_report  = new sigesp_scg_class_bal_generalbsf();
			$ls_bolivares ="Bs.F.";
			break;
	}	 
	 require_once("../../shared/class_folder/class_fecha.php");
	 $io_fecha=new class_fecha();
	 require_once("../class_folder/class_funciones_scf.php");
	 $io_fun_scf=new class_funciones_scf("../../");
	 $ia_niveles_scg[0]="";			
	 uf_init_niveles();
	 $li_total=count($ia_niveles_scg)-1;
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	   $ls_cmbmes=$_GET["cmbmes"];
	   $ls_cmbagno=$_GET["cmbagno"];
	   $ls_last_day=$io_fecha->uf_last_day($ls_cmbmes,$ls_cmbagno);
	   $fechas=$ls_last_day;
	   $ldt_fechas=$io_funciones->uf_convertirdatetobd($ls_last_day)." 00:00:00";
  	   $li_nivel=$_GET["cmbnivel"];
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$ls_nombre=$_SESSION["la_empresa"]["nombre"];
		$li_ano=substr($ldt_periodo,0,4);

		$ld_fechas=$io_funciones->uf_convertirfecmostrar($fechas);
		$ls_titulo="<b>BALANCE GENERAL ANUAL</b>";
		$ls_titulo1="<b> ".$ls_nombre." </b>"; 
		$ls_titulo2="<b> al ".$ld_fechas."</b>";		  
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar datastore con los datos del reporte
	$lb_valido=uf_insert_seguridad("<b>Balance General en PDF</b>"); // Seguridad de Reporte
	if($lb_valido)
	{
		$li_nivel=2;
		$lb_valido=$io_report->uf_balance_general($ldt_fechas,$li_nivel); 
	}
		if($lb_valido==false) // Existe algún error ó no hay registros
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}	
		else// Imprimimos el reporte
		{
			error_reporting(E_ALL);
			//set_time_limit(1800);
			$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(4.8,3,3,3); // Configuración de los margenes en centímetros
			uf_print_encabezado_pagina($ls_titulo,$ls_titulo1,$ls_titulo2,$io_pdf); // Imprimimos el encabezado de la página
			$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
			
			//------------------activo tesoro-----------------------------------------------------------
			$total_activo_t=0;
			$li_tot=$io_report->ds_Prebalance->getRowCount("sc_cuenta");	    		
			for($li_i=1;$li_i<=$li_tot;$li_i++)
			{
				$io_pdf->transaction('start'); // Iniciamos la transacción
				$thisPageNum=$io_pdf->ezPageCount;		
				
				$ls_sc_cuenta=trim($io_report->ds_Prebalance->data["sc_cuenta"][$li_i]);				

					$li_totfil=0;
					$as_cuenta="";
					for($li=$li_total;$li>1;$li--)
					{
						$li_ant=$ia_niveles_scg[$li-1];
						$li_act=$ia_niveles_scg[$li];
						$li_fila=$li_act-$li_ant;
						$li_len=strlen($ls_sc_cuenta);
						$li_totfil=$li_totfil+$li_fila;
						$li_inicio=$li_len-$li_totfil;
						if($li==$li_total)
						{
							$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila);
						}
						else
						{
							$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila)."-".$as_cuenta;
						}
					}
					$li_fila=$ia_niveles_scg[1]+1;
					$as_cuenta=substr($ls_sc_cuenta,0,$li_fila)."-".$as_cuenta;
					$ver=substr($as_cuenta,3,3);				

				////-----------activo del tesoro--------------------------------------------------------------
				$ls_denominacion=$io_report->ds_Prebalance->data["denominacion"][$li_i];
				$ls_nivel=$io_report->ds_Prebalance->data["nivel"][$li_i];				
				$ld_saldo=$io_report->ds_Prebalance->data["saldo"][$li_i];							
				///--------------------------------------------------------------------------------------------
				$total_activo_t=$total_activo_t+$ld_saldo; 
				$ld_saldo=number_format($ld_saldo,2,",",".");				
				$la_data[$li_i]=array('cuenta'=>$ver,'denominacion'=>$ls_denominacion,'saldo'=>$ld_saldo,'vacio'=>'');
			}//for
			//------------------------fin de activo tesoro-------------------------------------------------------------
			$ls_total_pasivo=0;
			//------------------pasivo tesoro-----------------------------------------------------------
			$li_tot_p=$io_report->ds_pasivo_t->getRowCount("cuenta_p");  
					
			for($li_j=1;$li_j<=$li_tot_p;$li_j++)
			{ 
				$io_pdf->transaction('start'); // Iniciamos la transacción
				$thisPageNum=$io_pdf->ezPageCount;		
				
				$ls_sc_cuenta=trim($io_report->ds_pasivo_t->data["cuenta_p"][$li_j]);				

					$li_totfil=0;
					$as_cuenta="";
					for($li=$li_total;$li>1;$li--)
					{
						$li_ant=$ia_niveles_scg[$li-1];
						$li_act=$ia_niveles_scg[$li];
						$li_fila=$li_act-$li_ant;
						$li_len=strlen($ls_sc_cuenta);
						$li_totfil=$li_totfil+$li_fila;
						$li_inicio=$li_len-$li_totfil;
						if($li==$li_total)
						{
							$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila);
						}
						else
						{
							$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila)."-".$as_cuenta;
						}
					}
					$li_fila=$ia_niveles_scg[1]+1;
					$as_cuenta=substr($ls_sc_cuenta,0,$li_fila)."-".$as_cuenta;
					$ver=substr($as_cuenta,3,3);
				
				$ls_denom_p=$io_report->ds_pasivo_t->data["denom_p"][$li_j];
				$ls_nivel_p=$io_report->ds_pasivo_t->data["nivel_p"][$li_j];				
				$ld_saldo_p=$io_report->ds_pasivo_t->data["saldo_p"][$li_j];						
				$ls_total_pasivo=$ls_total_pasivo+$ld_saldo_p;
				$ld_saldo_p=number_format($ld_saldo_p,2,",",".");				
				$la_data_p[$li_j]=array('cuenta_p'=>$ver,'denom_p'=>$ls_denom_p,'saldo_p'=>$ld_saldo_p);
			   
			}//for
			//------------------------fin de activo tesoro-------------------------------------------------------------
			$ls_total_activo_H=0;
			//------------------activo hacienda-----------------------------------------------------------
			$li_tot_p=$io_report->ds_activo_h->getRowCount("cuenta_h");  				
			for($li_k=1;$li_k<=$li_tot_p;$li_k++)
			{ 
				$io_pdf->transaction('start'); // Iniciamos la transacción
				$thisPageNum=$io_pdf->ezPageCount;		
				
				$ls_sc_cuenta=trim($io_report->ds_activo_h->data["cuenta_h"][$li_k]);				

					$li_totfil=0;
					$as_cuenta="";
					for($li=$li_total;$li>1;$li--)
					{
						$li_ant=$ia_niveles_scg[$li-1];
						$li_act=$ia_niveles_scg[$li];
						$li_fila=$li_act-$li_ant;
						$li_len=strlen($ls_sc_cuenta);
						$li_totfil=$li_totfil+$li_fila;
						$li_inicio=$li_len-$li_totfil;
						if($li==$li_total)
						{
							$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila);
						}
						else
						{
							$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila)."-".$as_cuenta;
						}
					}
					$li_fila=$ia_niveles_scg[1]+1;
					$as_cuenta=substr($ls_sc_cuenta,0,$li_fila)."-".$as_cuenta;
					$ver=substr($as_cuenta,3,3);
				
				$ls_denom_h=$io_report->ds_activo_h->data["denom_h"][$li_k];
				$ls_nivel_h=$io_report->ds_activo_h->data["nivel_h"][$li_k];				
				$ld_saldo_h=$io_report->ds_activo_h->data["saldo_h"][$li_k];						
				$ls_total_activo_H=$ls_total_activo_H+$ld_saldo_h;
				$ld_saldo_h=number_format($ld_saldo_h,2,",",".");				
				$la_data_h[$li_k]=array('cuenta_h'=>$ver,'denom_h'=>$ls_denom_h,'saldo_h'=>$ld_saldo_h,'vacio'=>'');
			   
			}//for
			//------------------------fin de activo hacienda-------------------------------------------------------------
			$ls_total_pasivo_H=0;
			//------------------pasivo hacienda-----------------------------------------------------------
			$li_tot_h=$io_report->ds_pasivo_h->getRowCount("cuenta_p_h");
			  				
			for($li_l=1;$li_l<=$li_tot_h;$li_l++)
			{ 
				$io_pdf->transaction('start'); // Iniciamos la transacción
				$thisPageNum=$io_pdf->ezPageCount;		
				
				$ls_sc_cuenta=trim($io_report->ds_pasivo_h->data["cuenta_p_h"][$li_l]);				

					$li_totfil=0;
					$as_cuenta="";
					for($li=$li_total;$li>1;$li--)
					{
						$li_ant=$ia_niveles_scg[$li-1];
						$li_act=$ia_niveles_scg[$li];
						$li_fila=$li_act-$li_ant;
						$li_len=strlen($ls_sc_cuenta);
						$li_totfil=$li_totfil+$li_fila;
						$li_inicio=$li_len-$li_totfil;
						if($li==$li_total)
						{
							$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila);
						}
						else
						{
							$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila)."-".$as_cuenta;
						}
					}
					$li_fila=$ia_niveles_scg[1]+1;
					$as_cuenta=substr($ls_sc_cuenta,0,$li_fila)."-".$as_cuenta;
					$ver=substr($as_cuenta,3,3);
				
				$ls_denom_p_h=$io_report->ds_pasivo_h->data["denom_p_h"][$li_l];
				$ls_nivel_p_h=$io_report->ds_pasivo_h->data["nivel_p_h"][$li_l];				
				$ld_saldo_p_h=$io_report->ds_pasivo_h->data["saldo_p_h"][$li_l];						
				$ls_total_pasivo_H=$ls_total_pasivo_H+$ld_saldo_p_h;
				$ld_saldo_p_h=number_format($ld_saldo_p_h,2,",",".");				
				$la_data_p_h[$li_l]=array('cuenta_p_h'=>$ver,'denom_p_h'=>$ls_denom_p_h,'saldo_p_h'=>$ld_saldo_p_h);
			   
			}//for
			//------------------------fin de activo tesoro-------------------------------------------------------------
			$ls_total_ingreso=0;
			//------------------ingresos-----------------------------------------------------------
			$li_tot_i=$io_report->ds_ingreso->getRowCount("cuenta_i");
			  				
			for($li_m=1;$li_m<=$li_tot_i;$li_m++)
			{ 
				$io_pdf->transaction('start'); // Iniciamos la transacción
				$thisPageNum=$io_pdf->ezPageCount;		
				
				$ls_sc_cuenta=trim($io_report->ds_ingreso->data["cuenta_i"][$li_m]);				

					$li_totfil=0;
					$as_cuenta="";
					for($li=$li_total;$li>1;$li--)
					{
						$li_ant=$ia_niveles_scg[$li-1];
						$li_act=$ia_niveles_scg[$li];
						$li_fila=$li_act-$li_ant;
						$li_len=strlen($ls_sc_cuenta);
						$li_totfil=$li_totfil+$li_fila;
						$li_inicio=$li_len-$li_totfil;
						if($li==$li_total)
						{
							$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila);
						}
						else
						{
							$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila)."-".$as_cuenta;
						}
					}
					$li_fila=$ia_niveles_scg[1]+1;
					$as_cuenta=substr($ls_sc_cuenta,0,$li_fila)."-".$as_cuenta;
					$ver=substr($as_cuenta,3,3);
				
				$ls_denom_i=$io_report->ds_ingreso->data["denom_i"][$li_m];
				$ls_nivel_i=$io_report->ds_ingreso->data["nivel_i"][$li_m];				
				$ld_saldo_i=$io_report->ds_ingreso->data["saldo_i"][$li_m];						
				$ls_total_ingreso=$ls_total_ingreso+$ld_saldo_i;
				$ld_saldo_i=number_format($ld_saldo_i,2,",",".");				
				$la_data_i[$li_m]=array('cuenta_i'=>$ver,'denom_i'=>$ls_denom_i,'saldo_i'=>$ld_saldo_i,'vacio'=>'');
			   
			}//for
			//------------------------fin de ingresos-------------------------------------------------------------
			
			$ls_total_gastos=0;
			//------------------gastos-----------------------------------------------------------
			$li_tot_g=$io_report->ds_gasto->getRowCount("cuenta_g");
			  				
			for($li_n=1;$li_n<=$li_tot_g;$li_n++)
			{ 
				$io_pdf->transaction('start'); // Iniciamos la transacción
				$thisPageNum=$io_pdf->ezPageCount;		
				
				$ls_sc_cuenta=trim($io_report->ds_gasto->data["cuenta_g"][$li_n]);				

					$li_totfil=0;
					$as_cuenta="";
					for($li=$li_total;$li>1;$li--)
					{
						$li_ant=$ia_niveles_scg[$li-1];
						$li_act=$ia_niveles_scg[$li];
						$li_fila=$li_act-$li_ant;
						$li_len=strlen($ls_sc_cuenta);
						$li_totfil=$li_totfil+$li_fila;
						$li_inicio=$li_len-$li_totfil;
						if($li==$li_total)
						{
							$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila);
						}
						else
						{
							$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila)."-".$as_cuenta;
						}
					}
					$li_fila=$ia_niveles_scg[1]+1;
					$as_cuenta=substr($ls_sc_cuenta,0,$li_fila)."-".$as_cuenta;
					$ver=substr($as_cuenta,3,3);
				
				$ls_denom_g=$io_report->ds_gasto->data["denom_g"][$li_n];
				$ls_nivel_g=$io_report->ds_gasto->data["nivel_g"][$li_n];				
				$ld_saldo_g=$io_report->ds_gasto->data["saldo_g"][$li_n];						
				$ls_total_gastos=$ls_total_gastos+$ld_saldo_g;
				$ld_saldo_g=number_format($ld_saldo_g,2,",",".");				
				$la_data_g[$li_n]=array('cuenta_g'=>$ver,'denom_g'=>$ls_denom_g,'saldo_g'=>$ld_saldo_g,'vacio'=>'');
			   
			}//for
			
			$ls_total_resultado=0;
			//------------------------fin de resultado del tesoro-------------------------------------------------------------
			
			$li_tot_t=$io_report->ds_resultado->getRowCount("cuenta_t");
			  				
			for($li_o=1;$li_o<=$li_tot_t;$li_o++)
			{ 
				$io_pdf->transaction('start'); // Iniciamos la transacción
				$thisPageNum=$io_pdf->ezPageCount;		
				
				$ls_sc_cuenta=trim($io_report->ds_resultado->data["cuenta_t"][$li_o]);				

					$li_totfil=0;
					$as_cuenta="";
					for($li=$li_total;$li>1;$li--)
					{
						$li_ant=$ia_niveles_scg[$li-1];
						$li_act=$ia_niveles_scg[$li];
						$li_fila=$li_act-$li_ant;
						$li_len=strlen($ls_sc_cuenta);
						$li_totfil=$li_totfil+$li_fila;
						$li_inicio=$li_len-$li_totfil;
						if($li==$li_total)
						{
							$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila);
						}
						else
						{
							$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila)."-".$as_cuenta;
						}
					}
					$li_fila=$ia_niveles_scg[1]+1;
					$as_cuenta=substr($ls_sc_cuenta,0,$li_fila)."-".$as_cuenta;
					$ver=substr($as_cuenta,3,3);
				
				$ls_denom_t=$io_report->ds_resultado->data["denom_t"][$li_o];
				$ls_nivel_t=$io_report->ds_resultado->data["nivel_t"][$li_o];				
				$ld_saldo_t=$io_report->ds_resultado->data["saldo_t"][$li_o];						
				$ls_total_resultado=$ls_total_resultado+$ld_saldo_t;
				$ld_saldo_t=number_format($ld_saldo_t,2,",",".");				
				$la_data_t[$li_o]=array('cuenta_t'=>$ver,'denom_t'=>$ls_denom_t,'saldo_t'=>$ld_saldo_t);
			   
			}//for
			//------------------------fin de resultado del tesoro-------------------------------------------------------------
				
		    $total_activo_t=number_format($total_activo_t,2,",",".");		    
		    $total_pasivo_result= $ls_total_resultado+$ls_total_pasivo;
		    $total_pasivo_result=number_format( $total_pasivo_result,2,",",".");		
		    $ls_total_pasivo=number_format($ls_total_pasivo,2,",",".");	
		    $ls_total_ingreso=number_format($ls_total_ingreso,2,",",".");
		    $ls_total_gastos=number_format($ls_total_gastos,2,",",".");	
		    $ls_total_activo_H=number_format($ls_total_activo_H,2,",",".");	
		    $ls_total_pasivo_H=number_format($ls_total_pasivo_H,2,",",".");
			uf_print_detalle($la_data,$la_data_p,$la_data_t,$total_activo_t,$ls_total_pasivo, $total_pasivo_result,$io_pdf); // Imprimimos el detalle 
			uf_print_detalle_2($la_data_h,$la_data_p_h,$ls_total_activo_H,$ls_total_pasivo_H,$io_pdf); // Imprimimos el detalle 
			//uf_print_detalle_3($la_data_i,$la_data_g,$ls_total_ingreso,$ls_total_gastos,$io_pdf); // Imprimimos el detalle
			
			unset($la_data);
			unset($la_data_p);
			unset($la_data_t);
			unset($la_data_p_h);
			unset($la_data_i);
			unset($la_data_g);		
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