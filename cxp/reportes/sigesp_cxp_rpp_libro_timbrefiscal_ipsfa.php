<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//    REPORTE: Retencion Municipales
	//  ORGANISMO: Ninguno en particular
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 15/07/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_libro_islr_timbrefiscal.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 04/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(12,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,540,12,$as_titulo); // Agregar el título
		$io_pdf->addText(712,560,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(718,553,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_agenteret,$as_rifagenteret,$as_diragenteret,$as_periodo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_agenteret // agente de Retención
		//	    		   as_rifagenteret // Rif del Agente de Retención
		//       		   as_diragenteret // Dirección del agente de retención
		//	    		   as_periodo // Periodo del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 17/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>NOMBRE DE LA INSTTUCION:</b>'."  ".$as_agenteret),
					   array('name'=>'<b>RIF:</b>'."  ".$as_rifagenteret),
					   array('name'=>'<b>DIRECCION:</b>'."  ".$as_diragenteret),
					   array('name'=>'<b>PERIODO:</b>'."  ".$as_periodo),
					   array('name'=>'<b>Nº PLANILLA (BANCO):</b>'));
		
		 
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras						 
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas						 
						 'xPos'=>405, // Orientación de la tabla
						 'width'=>740, // Ancho de la tabla
						 'maxWidth'=>740, // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'lef','width'=>740))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		
		
		unset($la_data);
		unset($la_columnas);
		unset($la_config);							 
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------			
			
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabeceradetalle
		//		   Access: private 
		//	    Arguments: la_data // Arreglo de datos a imprimir		
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 14/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(400);
		$la_data1[1]=array('fecha'=>'<b>Fecha Operación</b>',
						  'nombre'=>'<b>Nombre Contribuyente</b>',
						  'rif'=>'<b>CI / RIF</b>',
						  'monto'=>'<b>Monto Operación</b>',
  						  'monimp'=>'<b>     Monto Impuesto         1 x 1000</b>',		
						  'municipio'=>'<b>Municipio</b>',
						  'comp'=>'<b>Nº Comprobante</b>',
						  'obs'=>'<b>Observaciones</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>740, // Ancho de la tabla
						 'maxWidth'=>740, // Ancho Mínimo de la tabla
						 'xPos'=>405, // Orientación de la tabla
						 'cols'=>array('fecha'=>array('justification'=>'center','width'=>65), // Justificacion y ancho de la columna
									   'nombre'=>array('justification'=>'center','width'=>135),
						 			   'rif'=>array('justification'=>'center','width'=>70), // Justificacion y ancho de la columna
						 			   'monto'=>array('justification'=>'center','width'=>95), // Justificacion y ancho de la columna
									   'monimp'=>array('justification'=>'center','width'=>95), // Justificacion y ancho de la columna
						 			   'municipio'=>array('justification'=>'center','width'=>100),
						 			   'comp'=>array('justification'=>'center','width'=>80),
   						 			   'obs'=>array('justification'=>'center','width'=>100))); 
		$io_pdf->ezTable($la_data1,'','',$la_config);
		unset($la_data1);
		unset($la_config);
		
		
		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>740, // Ancho de la tabla
						 'maxWidth'=>740, // Ancho Mínimo de la tabla
						 'xPos'=>405, // Orientación de la tabla
						 'cols'=>array('fecha'=>array('justification'=>'center','width'=>65), // Justificacion y ancho de la columna
									   'nombre'=>array('justification'=>'left','width'=>135),
						 			   'rif'=>array('justification'=>'center','width'=>70), // Justificacion y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>95), // Justificacion y ancho de la columna
									   'monimp'=>array('justification'=>'right','width'=>95), // Justificacion y ancho de la columna
						 			   'municipio'=>array('justification'=>'center','width'=>100),
						 			   'comp'=>array('justification'=>'center','width'=>80),
   						 			   'obs'=>array('justification'=>'center','width'=>100))); 
		$io_pdf->ezTable($la_data,'','',$la_config);
		unset($la_data);
		unset($la_config);
		
	}// end function uf_print_detalle
	
//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_total($ai_totbasimp,$ai_totmonimp,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_total
		//		   Access: private 
		//	    Arguments: 
		//	    		   ai_totbasimp // Total de la base imponible
		//	    		   ai_totmonimp // Total monto imponible		
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 14/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data1[1]=array('total'=>'<b>TOTAL</b>',
						  'total1'=>'<b>'.$ai_totbasimp.'</b>',
						  'total2'=>'<b>'.$ai_totmonimp.'</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>740, // Ancho de la tabla
						 'maxWidth'=>740, // Ancho Mínimo de la tabla
						 'xPos'=>265, // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>270), // Justificacion y ancho de la columna
									   'total1'=>array('justification'=>'right','width'=>95),
						 			   'total2'=>array('justification'=>'right','width'=>95))); 
		$io_pdf->ezTable($la_data1,'','',$la_config);
		unset($la_data1);
		unset($la_config);
		
	}// end function uf_print_total
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_firmas(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_firmas
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por recepción
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 05/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[0]=array('firma1'=>'','firma2'=>'');
		$la_data[1]=array('firma1'=>'','firma2'=>'');
		$la_data[2]=array('firma1'=>'','firma2'=>'');
		$la_data[3]=array('firma1'=>'','firma2'=>'');
		$la_data[4]=array('firma1'=>'_________________________________','firma2'=>'_________________________________');
		$la_data[5]=array('firma1'=>'<b>CNEL. (EJ) MARCO A. ROJAS TORRES</b>','firma2'=>'<b>LIC. ROSA YELITZA ARVELO</b>');
		$la_data[6]=array('firma1'=>'<b>TESORERO / AGENTE DE RETENCION</b>','firma2'=>'<b>JEFE UNIDAD DE TRIBUTOS INTERNOS</b>');
		$la_data[7]=array('firma1'=>'','firma2'=>'');
		$la_columna=array('firma1'=>'','firma2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('firma1'=>array('justification'=>'center','width'=>250), // Justificación y ancho de la columna
						 			   'firma2'=>array('justification'=>'center','width'=>250))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		
	}// end function uf_print_firmas
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------

	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_cxp_class_report.php");
	$io_report=new sigesp_cxp_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$ls_tiporeporte=$io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_cxp_class_reportbsf.php");
		$io_report=new sigesp_cxp_class_reportbsf();
	}
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	   $ls_titulo="<b>DECLARACION DE TIMBRE FISCAL 1 X 1000</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_mes=$io_fun_cxp->uf_obtenervalor_get("mes","");
	$ls_anio=$io_fun_cxp->uf_obtenervalor_get("anio","");
	$ls_agenteret=$_SESSION["la_empresa"]["nombre"];
	$ls_rifagenteret=$_SESSION["la_empresa"]["rifemp"];
	$ls_diragenteret=$_SESSION["la_empresa"]["direccion"];
	
	$mes="";
	switch ($ls_mes)
	{
		case '01':
			$mes='ENERO';
		break;
		case '02':
			$mes='FEBRERO';
		break;
		case '03':
			$mes='MARZO';
		break;
		case '04':
			$mes='ABRIL';
		break;
		case '05':
			$mes='MAYO';
		break;
		case '06':
			$mes='JUNIO';
		break;
		case '07':
			$mes='JULIO';
		break;
		case '08':
			$mes='AGOSTO';
		break;
		case '09':
			$mes='SEPTIEMBRE';
		break;
		case '10':
			$mes='OCTUBRE';
		break;
		case '11':
			$mes='NOVIEMBRE';
		break;
		case '12':
			$mes='DICIEMBRE';
		break;
	
	}
	$ls_periodo= $mes.' - '.$ls_anio;	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_select_contribuyentes_libro_timbrefiscal($ls_mes,$ls_anio,$rs_data);
		if(!$lb_valido)
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}
		else
		{
			error_reporting(E_ALL);
			set_time_limit(1800);
			$io_pdf = new Cezpdf("LETTER","landscape");
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');
			$io_pdf->ezSetCmMargins(4,2,3,3);
			$lb_valido=true;
			$li_totalbaseimp=0;
			$li_totalmontoimp=0;
			$li_i=0;
			uf_print_encabezado_pagina($ls_titulo,$io_pdf);
			while (!$rs_data->EOF)
			{
				$ls_numcon=$rs_data->fields["numcom"];
				$ls_fecrep=$io_funciones->uf_convertirfecmostrar($rs_data->fields["fecfac"]);
				$ls_nomsujret=$rs_data->fields["nomsujret"];	
				$ls_rif=$rs_data->fields["rif"];	
				$li_baseimp=$rs_data->fields["basimp"];
				$li_totimp=$rs_data->fields["iva_ret"];
				$ls_denmun='LIBERTADOR';
				$li_totalbaseimp=$li_totalbaseimp + $li_baseimp ;	
				$li_totalmontoimp=$li_totalmontoimp + $li_totimp;					
				$li_i++;
				$la_data[$li_i]=array('fecha'=>$ls_fecrep,'nombre'=>$ls_nomsujret, 'rif'=>$ls_rif,
				                      'monto'=>number_format($li_baseimp,2,",","."),
									  'monimp'=>number_format($li_totimp,2,",","."),'municipio'=>$ls_denmun,'comp'=>$ls_numcon,'obs'=>'');
				$rs_data->MoveNext();	

			}
			if($lb_valido) // Si no ocurrio ningún error
			{
				
				uf_print_cabecera($ls_agenteret,$ls_rifagenteret,$ls_diragenteret,$ls_periodo,&$io_pdf);
				uf_print_detalle($la_data,&$io_pdf);
				uf_print_total(number_format($li_totalbaseimp,2,",","."),number_format($li_totalmontoimp,2,",","."),&$io_pdf);
				uf_print_firmas(&$io_pdf);
				$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
				$io_pdf->ezStream(); // Mostramos el reporte
				unset($la_data);
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
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_cxp);
?> 