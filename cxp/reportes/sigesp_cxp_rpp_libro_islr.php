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
					   array('name'=>'<b>PERIODO:</b>'."  ".$as_periodo));
		
		 
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
		$io_pdf->ezSetY(420);
		$la_data1[1]=array('titulo1'=>'<b>BENEFICIARIO DE LAS REMUNERACIONES</b>',
						  'titulo2'=>'<b>Nº RIF</b>',
						  'titulo3'=>'<b>Nº COMP</b>',
						  'titulo4'=>'<b>MONTO OBJETO DE RETENCIÓN</b>',
  						  'titulo5'=>'<b>ALICUOTA 2%</b>',		
						  'titulo6'=>'<b>MONTO OBJETO DE RETENCIÓN</b>',
						  'titulo7'=>'<b>ALICUOTA 3%</b>',
						  'titulo8'=>'<b>MONTO OBJETO DE RETENCIÓN</b>',
						  'titulo9'=>'<b>ALICUOTA 5%</b>',
						  'titulo10'=>'<b>DEPENDENCIA</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>740, // Ancho de la tabla
						 'maxWidth'=>740, // Ancho Mínimo de la tabla
						 'xPos'=>405, // Orientación de la tabla
						 'cols'=>array('titulo1'=>array('justification'=>'center','width'=>130), // Justificacion y ancho de la columna
									   'titulo2'=>array('justification'=>'center','width'=>63),
						 			   'titulo3'=>array('justification'=>'center','width'=>78), // Justificacion y ancho de la columna
						 			   'titulo4'=>array('justification'=>'center','width'=>69), // Justificacion y ancho de la columna
									   'titulo5'=>array('justification'=>'center','width'=>69), // Justificacion y ancho de la columna
						 			   'titulo6'=>array('justification'=>'center','width'=>69),
						 			   'titulo7'=>array('justification'=>'center','width'=>69),
   						 			   'titulo8'=>array('justification'=>'center','width'=>69),
									   'titulo9'=>array('justification'=>'center','width'=>69),
									   'titulo10'=>array('justification'=>'center','width'=>70))); 
		$io_pdf->ezTable($la_data1,'','',$la_config);
		unset($la_data1);
		unset($la_config);
		
		
		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>740, // Ancho de la tabla
						 'maxWidth'=>740, // Ancho Mínimo de la tabla
						 'xPos'=>405, // Orientación de la tabla
						 'cols'=>array('islr1'=>array('justification'=>'left','width'=>130), // Justificacion y ancho de la columna
									   'islr2'=>array('justification'=>'center','width'=>63),
						 			   'islr3'=>array('justification'=>'center','width'=>78), // Justificacion y ancho de la columna
						 			   'islr4'=>array('justification'=>'right','width'=>69), // Justificacion y ancho de la columna
									   'islr5'=>array('justification'=>'right','width'=>69), // Justificacion y ancho de la columna
						 			   'islr6'=>array('justification'=>'right','width'=>69),
						 			   'islr7'=>array('justification'=>'right','width'=>69),
   						 			   'islr8'=>array('justification'=>'right','width'=>69),
									   'islr9'=>array('justification'=>'right','width'=>69),
									   'islr10'=>array('justification'=>'center','width'=>70)));  
		$io_pdf->ezTable($la_data,'','',$la_config);
		unset($la_data);
		unset($la_config);
		
	}// end function uf_print_detalle
	
//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_total($ai_totalbaseimp2porc,$ai_totalmontret2porc,$ai_totalbaseimp3porc,
				            $ai_totalmontret3porc,$ai_totalbaseimp5porc,$ai_totalmontret5porc,$ai_totalbaseimp,$ai_totalret,
							&$io_pdf)
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
						  'total1'=>'<b>'.$ai_totalbaseimp2porc.'</b>',
						  'total2'=>'<b>'.$ai_totalmontret2porc.'</b>',
						  'total3'=>'<b>'.$ai_totalbaseimp3porc.'</b>',
						  'total4'=>'<b>'.$ai_totalmontret3porc.'</b>',
						  'total5'=>'<b>'.$ai_totalbaseimp5porc.'</b>',
						  'total6'=>'<b>'.$ai_totalmontret5porc.'</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>740, // Ancho de la tabla
						 'maxWidth'=>740, // Ancho Mínimo de la tabla
						 'xPos'=>370, // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>271), // Justificacion y ancho de la columna
									   'total1'=>array('justification'=>'right','width'=>69),
						 			   'total2'=>array('justification'=>'right','width'=>69),
									   'total3'=>array('justification'=>'right','width'=>69),
									   'total4'=>array('justification'=>'right','width'=>69),
									   'total5'=>array('justification'=>'right','width'=>69),
									   'total6'=>array('justification'=>'right','width'=>69))); 
		$io_pdf->ezTable($la_data1,'','',$la_config);
		unset($la_data1);
		unset($la_config);
		
		
		$la_data[0]=array('firma1'=>'','firma2'=>'');
		$la_data[1]=array('firma1'=>'','firma2'=>'');
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
		unset($la_data);
		unset($la_config);
		
		
		$la_data[0]=array('total1'=>'<b>MONTO OBJETO DE RETENCIÓN (2%) Bs.  </b>','total2'=>$ai_totalbaseimp2porc,
		                  'total3'=>$ai_totalmontret2porc);
		$la_data[1]=array('total1'=>'<b>MONTO OBJETO DE RETENCIÓN (3%) Bs.   </b>','total2'=>$ai_totalbaseimp3porc,
		                  'total3'=>$ai_totalmontret3porc);
		$la_data[2]=array('total1'=>'<b>MONTO OBJETO DE RETENCIÓN (5%) Bs.   </b>','total2'=>$ai_totalbaseimp5porc,
		                  'total3'=>$ai_totalmontret5porc);
		$la_data[3]=array('total1'=>'<b>TOTAL MONTO OBJETO DE RETENCIÓN Bs.  </b>','total2'=>'<b>'.$ai_totalbaseimp.'</b>',
		                  'total3'=>'<b>'.$ai_totalret.'</b>');
		$la_columna=array('total1'=>'','total2'=>'','total3'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>400, // Ancho Máximo de la tabla
						 'xPos'=>230, // Orientación de la tabla
				 		 'cols'=>array('total1'=>array('justification'=>'right','width'=>220), // Justificación y ancho de la columna
						 			   'total2'=>array('justification'=>'right','width'=>90),
									   'total3'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
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
		$la_data[5]=array('firma1'=>'JEFE DEPARTAMENTO TRIBUTOS INTERNOS','firma2'=>'TESORERO / AGENTE DE RETENCION');
		$la_data[6]=array('firma1'=>'','firma2'=>'');
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
		unset($la_data);
		unset($la_config);
		
		
		
		
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
	   $ls_titulo="<b>DECLARACION DE IMPUESTO SOBRE LA RENTA</b>";
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
		$lb_valido=$io_report->uf_select_beneficiarios_libro_islr($ls_mes,$ls_anio,$rs_data);
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
			$li_totalbaseimp2porc=0;
			$li_totalmontret2porc=0;
			$li_totalbaseimp3porc=0;
			$li_totalmontret3porc=0;
			$li_totalbaseimp5porc=0;
			$li_totalmontret5porc=0;
			$li_s=0;
			uf_print_encabezado_pagina($ls_titulo,$io_pdf);
			while ((!$rs_data->EOF)&&($lb_valido))
			{
				$li_s=$li_s+1;
				switch (trim($rs_data->fields["procede"]))
				{
					case "SCBBCH":
						$lb_valido= $io_report->uf_retencionesislr_scb($rs_data->fields["numero"]);  
					break;
					case "INT":
						$lb_valido= $io_report->uf_retencionesislr_int($rs_data->fields["numero"]);
					break;
					default:
						$lb_valido= $io_report->uf_retencionesislr_cxp($rs_data->fields["numero"]);
					break;
				}
					$li_total=$io_report->DS->getRowCount("numdoc");
					for($li_i=1;($li_i<=$li_total);$li_i++)
					{
						$ls_codpro=$io_report->DS->data["cod_pro"][$li_i];
						$ls_cedbene=$io_report->DS->data["ced_bene"][$li_i];
						if($ls_codpro!="----------")
						{
							$ls_tipproben="P";
						}
						else
						{
							$ls_tipproben="B";
						}
						if($ls_tipproben=="P")
						{
							$ls_codigo=$io_report->DS->data["cod_pro"][$li_i];
							$ls_nombre=$io_report->DS->data["proveedor"][$li_i];
							$ls_rif=$io_report->DS->data["rifpro"][$li_i];
						}
						else
						{
							$ls_codigo=$io_report->DS->data["ced_bene"][$li_i];
							$ls_nombre=$io_report->DS->data["beneficiario"][$li_i];
							$ls_rif=$io_report->DS->data["rifben"][$li_i];
						}						 
						
						$ls_dependencia='TESORERIA';
						$li_monobjret=$io_report->DS->data["monobjret"][$li_i];    
						$li_retenido=$io_report->DS->data["retenido"][$li_i];  
						$li_porcentaje=$io_report->DS->data["porcentaje"][$li_i];
						$ls_correlativo=$io_report->DS->data["numcmpislr"][$li_i];	
						switch(trim($li_porcentaje))
						{
							case 0.02:
								$li_totalbaseimp2porc=$li_totalbaseimp2porc+$li_monobjret;
								$li_totalmontret2porc=$li_totalmontret2porc+$li_retenido;
								$la_data[$li_s]=array('islr1'=>$ls_nombre,'islr2'=>$ls_rif,
								                      'islr3'=>$ls_correlativo,
													  'islr4'=>number_format($li_monobjret,2,",","."),
				                                      'islr5'=>number_format($li_retenido,2,",","."),
													  'islr6'=>'0,00','islr7'=>'0,00','islr8'=>'0,00','islr9'=>'0,00',
													  'islr10'=>$ls_dependencia);
							break;
							case 0.03:
								$li_totalbaseimp3porc=$li_totalbaseimp3porc+$li_monobjret;
								$li_totalmontret3porc=$li_totalmontret3porc+$li_retenido;
								$la_data[$li_s]=array('islr1'=>$ls_nombre,'islr2'=>$ls_rif,
								                      'islr3'=>$ls_correlativo,
				                                      'islr4'=>'0,00',
													  'islr5'=>'0,00',
													  'islr6'=>number_format($li_monobjret,2,",","."),
													  'islr7'=>number_format($li_retenido,2,",","."),
													  'islr8'=>'0,00','islr9'=>'0,00',
													  'islr10'=>$ls_dependencia);
							break;
							case 0.05:
								$li_totalbaseimp5porc=$li_totalbaseimp5porc+$li_monobjret;
								$li_totalmontret5porc=$li_totalmontret5porc+$li_retenido;
								$la_data[$li_s]=array('islr1'=>$ls_nombre,'islr2'=>$ls_rif,
								                      'islr3'=>$ls_correlativo,
				                                      'islr4'=>'0,00',
													  'islr5'=>'0,00',
													  'islr6'=>'0,00',
													  'islr7'=>'0,00',
													  'islr8'=>number_format($li_monobjret,2,",","."),
													  'islr9'=>number_format($li_retenido,2,",","."),
													  'islr10'=>$ls_dependencia);
							break;
						
							
						
						}
					}//fin del For
				
				$rs_data->MoveNext();	

			}
			if($lb_valido) // Si no ocurrio ningún error
			{
				
				uf_print_cabecera($ls_agenteret,$ls_rifagenteret,$ls_diragenteret,$ls_periodo,&$io_pdf);
				uf_print_detalle($la_data,&$io_pdf);
				$li_totalbaseimp=$li_totalbaseimp2porc+$li_totalbaseimp3porc+$li_totalbaseimp5porc;
				$li_totalret=$li_totalmontret2porc+$li_totalmontret3porc+$li_totalmontret5porc;
				$li_totalbaseimp2porc=number_format($li_totalbaseimp2porc,2,",",".");
				$li_totalmontret2porc=number_format($li_totalmontret2porc,2,",",".");
				$li_totalbaseimp3porc=number_format($li_totalbaseimp3porc,2,",",".");
				$li_totalmontret3porc=number_format($li_totalmontret3porc,2,",",".");
				$li_totalbaseimp5porc=number_format($li_totalbaseimp5porc,2,",",".");
				$li_totalmontret5porc=number_format($li_totalmontret5porc,2,",",".");
				$li_totalbaseimp=number_format($li_totalbaseimp,2,",",".");
				$li_totalret=number_format($li_totalret,2,",",".");
				uf_print_total($li_totalbaseimp2porc,$li_totalmontret2porc,$li_totalbaseimp3porc,
				               $li_totalmontret3porc,$li_totalbaseimp5porc,$li_totalmontret5porc,$li_totalbaseimp,$li_totalret,
							   &$io_pdf);
				uf_print_firmas(&$io_pdf);
				$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
				$io_pdf->ezStream(); // Mostramos el reporte
				unset($la_data);
			}
			else  // Si hubo algún error
			{
				print("<script language=JavaScript>");
				print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
				//print(" close();");
				print("</script>");		
			}
			unset($io_pdf);
		}
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_cxp);
?> 