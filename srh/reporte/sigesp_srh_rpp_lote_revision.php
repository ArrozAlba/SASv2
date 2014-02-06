<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//  REPORTE: Formato de salida  de Solicitud de Ejecucion Presupuestaria
	//  ORGANISMO: Ninguno en particular
	//  ESTE FORMATO SE IMPRIME EN Bs Y EN BsF. SEGUN LO SELECCIONADO POR EL USUARIO
	//  MODIFICADO POR: ING.YOZELIN BARRAGAN         FECHA DE MODIFICACION : 14/08/2007
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
		// Fecha Creación: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_srh;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("SRH","sigesp_srh_r_lote_revision.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo='Titulo',$as_numsol='0001',$ad_fecregsol='20/09/2007',&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_numsol // numero de la solicitud
		//	    		   ad_fecregsol // fecha de registro de la solicitud
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		// Fecha Creación: 11/03/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();

		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(14,$as_titulo);
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_nombre,$as_cedula,$as_cargo,$as_departamento,$as_gerencia,$as_extension,$as_objetivo,
	                           $adt_revini1,$adt_revfin1,$adt_revini2,$adt_revfin2,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_numsol    // numero de la solicitud de ejecucion presupuestaria
		//	   			   as_dentipsol // Denominacion del tipo de solicitud
		//	   			   as_denuniadm // Denominacion de la Unidad Ejecutora solicitante
		//	   			   as_denfuefin // Denominacion de la fuente de financiamiento
		//	   			   as_codigo    // Codigo del Proveedor / Beneficiario
		//	   			   as_nombre    // Nombre del Proveedor / Beneficiario
		//	   			   as_consol    // Concepto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por concepto
		// Fecha Creación: 17/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(102);
		$la_data[1]=array('titulo'=>'<b> ESTABLECIMIENTO Y SEGUIMIENTO DE LOS OBJETIVOS DE DESEMPEÑO INDIVIDUAL </b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 16, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre línea
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xPos'=>400, // OrientaciÃ³n de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				        //'outerLineThickness'=>0.5,
						// 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>540))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		
		
		$io_pdf->ezSetDy(-15);
		$la_data[1]=array('nombre'=>$as_nombre,'cedula'=>$as_cedula,'cargo'=>$as_cargo,'departamento'=>$as_departamento,'gerencia'=>$as_gerencia, 
		                  'extension'=>$as_extension);
		$la_columnas=array('nombre'=>'APELLIDOS Y NOMBRES',
		                  'cedula'=>'CEDULA DE IDENTIDAD',
		                  'cargo'=>'CARGO',
		                  'departamento'=>'DEPARTAMENTO',
		                  'gerencia'=>'GERENCIA',
		                  'extension'=>'EXTENSION');
		                  
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' =>10, // TamaÃ±o de Letras
						 'titleFontSize' => 12,  // TamaÃ±o de Letras de los tÃ­tulos
						 'showLines'=>1, // Mostrar LÃ­neas
						 'shaded'=>0, // Sombra entre lÃ­neas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho MÃ¡ximo de la tabla
						 'xPos'=>400, // OrientaciÃ³n de la tabla
						 'xOrientation'=>'center', // OrientaciÃ³n de la tablA
						 'cols'=>array('nombre'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
						               'cedula'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
						               'cargo'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
						               'departamento'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
						               'gerencia'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
						               'extension'=>array('justification'=>'center','width'=>110))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		
		
		
		
		//$io_pdf->addLink("http://www.ros.co.nz/pdf/",50,100,500,120);
		//$io_pdf->rectangle(63,340,660,42);
		$io_pdf->ezSetDy(-12);
		$la_data[1]=array('nombre'=>'    '.trim($as_objetivo));
		$la_columnas=array('nombre'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // TamaÃ±o de Letras
						 'titleFontSize' => 12,  // TamaÃ±o de Letras de los tÃ­tulos
						 'showLines'=>1, // Mostrar LÃ­neas
						 'shaded'=>1, // Sombra entre lÃ­neas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho MÃ¡ximo de la tabla
						 'xPos'=>400, // OrientaciÃ³n de la tabla
						 'xOrientation'=>'center', // OrientaciÃ³n de la tablA
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>660))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'OBJETIVO FUNCIONAL DE LA UNIDAD',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		
		
		$io_pdf->ezSetDy(-15);
		$la_data[1]=array('odi'=>'OBJETIVOS DE DESEMPEÑO INDIVIDUAL','peso'=>'PESO',
		                 'rev1'=>"PRIMERA REVISION ".$adt_revini1." AL ".$adt_revfin1." ",'rev2'=>"SEGUNDA REVISION ".$adt_revini2." AL ".$adt_revfin2."");
		$la_columnas=array('odi'=>'',
		                  'peso'=>'',
		                  'rev1'=>'',
		                  'rev2'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>8, // TamaÃ±o de Letras
						 'titleFontSize' => 12,  // TamaÃ±o de Letras de los tÃ­tulos
						 'showLines'=>1, // Mostrar LÃ­neas
						 'shaded'=>0, // Sombra entre lÃ­neas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho MÃ¡ximo de la tabla
						 'xPos'=>400, // OrientaciÃ³n de la tabla
						 'xOrientation'=>'center', // OrientaciÃ³n de la tablA
						 'cols'=>array('odi'=>array('justification'=>'center','width'=>300), // Justificación y ancho de la columna
						               'peso'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
						               'rev1'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
						               'rev2'=>array('justification'=>'center','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
	  
		

	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($as_odi,$as_observacion,$adt_fecrev,$ai_peso,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-1);
		$la_data[1]=array('odi'=>$as_odi,'peso'=>$ai_peso,'rev1'=>$as_observacion,'rev2'=>'');
		$la_columnas=array('odi'=>'',
		                  'peso'=>'',
		                  'rev1'=>'',
		                  'rev2'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // TamaÃ±o de Letras
						 'titleFontSize' => 12,  // TamaÃ±o de Letras de los tÃ­tulos
						 'showLines'=>1, // Mostrar LÃ­neas
						 'shaded'=>0, // Sombra entre lÃ­neas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho MÃ¡ximo de la tabla
						 'xPos'=>400, // OrientaciÃ³n de la tabla
						 'xOrientation'=>'center', // OrientaciÃ³n de la tablA
						 'cols'=>array('odi'=>array('justification'=>'left','width'=>300), // Justificación y ancho de la columna
						               'peso'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
						               'rev1'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la columna
						               'rev2'=>array('justification'=>'left','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		

	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle2($as_evaluador,$as_evaluado,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$tm=250-(200/2);
		$io_pdf->addText(77,80,12,"EVALUADOR:    ".$as_evaluador); // Agregar el título
	    $tm=310-(300/2);
		$io_pdf->addText(400,80,12," EVALUADO:    ".$as_evaluado); // Agregar el título
		 $tm=250-(350/2);
		
		

	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha = new class_fecha();	
	require_once("class_folder/sigesp_srh_class_report.php");
	$io_report  = new sigesp_srh_class_report('../../');
	require_once("../class_folder/utilidades/class_funciones_srh.php");
	$io_fun_srh=new class_funciones_srh("../../");	
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];	
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	 $ls_titulo='<b>REVISION DE ODIS </b>';
	 //--------------variable que se toman de sigesp_srh_r_listado_evaluacioneficiencia.php------------------------------------------
	 $ls_fechades=$_GET["fechades"]; 
	 $ls_fechahas=$_GET["fechahas"];
	 $ls_codperdes=$_GET["codperdes"];
	 $ls_codperhas=$_GET["codperhas"];
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{       
		
		$lb_valido=$io_report->uf_select_lote_revision_odi($ls_fechades,$ls_fechahas,$ls_codperdes,$ls_codperhas);	
		
	}
	if($lb_valido==false) // Existe algún error ó no hay registros
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
			$io_pdf->ezSetCmMargins(7,4,3,3);
			$io_pdf->ezStartPageNumbers(406,30,10,'','',1);//Insertar el número de página.
		    $li_total=$io_report->DS->getRowCount("nroreg");
		    $li_aux=0;
		    uf_print_encabezado_pagina('','','',&$io_pdf);
		    for($li_i=1;$li_i<=$li_total;$li_i++)
			{	  
				  $li_aux++;
			  //  $io_pdf->transaction('start'); // Iniciamos la transacción
				$thisPageNum=$io_pdf->ezPageCount;
				$ls_nroeval=$io_report->DS->getValue("nroreg",$li_i);
				$ls_codper=$io_report->DS->getValue("codper",$li_i);			
				$ls_cedper=$io_report->DS->getValue("cedper",$li_i);			
				$ls_nomper=$io_report->DS->getValue("nomper",$li_i);			
				$ldt_revini1=$io_report->DS->getValue("fecinirev1",$li_i);	
				$ldt_revini1=$io_funciones->uf_formatovalidofecha($ldt_revini1);
				$ldt_revini1=$io_funciones->uf_convertirfecmostrar($ldt_revini1);	
				$ldt_revfin1=$io_report->DS->getValue("fecfinrev1",$li_i);			
				$ldt_revfin1=$io_funciones->uf_formatovalidofecha($ldt_revfin1);
				$ldt_revfin1=$io_funciones->uf_convertirfecmostrar($ldt_revfin1);	
				$ldt_revini2=$io_report->DS->getValue("fecinirev2",$li_i);
				$ldt_revini2=$io_funciones->uf_formatovalidofecha($ldt_revini2);
				$ldt_revini2=$io_funciones->uf_convertirfecmostrar($ldt_revini2);	
				$ldt_revfin2=$io_report->DS->getValue("fecfinrev2",$li_i);	
				$ldt_revfin2=$io_funciones->uf_formatovalidofecha($ldt_revfin2);
				$ldt_revfin2=$io_funciones->uf_convertirfecmostrar($ldt_revfin2);	
				$ls_objetivo=$io_report->DS->getValue("objetivo",$li_i);	
				$lb_valido=$io_report->uf_select_odi_persona($ls_nroeval,'E');
				$ls_evaluador=$io_report->ds_detalle2->getValue("evaluador",$li_i);
				
				
				uf_print_cabecera($ls_nomper,$ls_cedper,$ls_cargo='',$ls_departamento=$ls_nroeval,$ls_gerencia='',$ls_extension='',
				                  $ls_objetivo,$ldt_revini1,$ldt_revfin1,$ldt_revini2,$ldt_revfin2,&$io_pdf);
				
				$lb_valido=$io_report->uf_select_lote_dt_revision_odi($ls_nroeval);
				$li_total2=$io_report->ds_detalle->getRowCount("nroreg");
				
				
				uf_print_detalle2($ls_evaluador,$ls_nomper,&$io_pdf);
				if($lb_valido)
			    {
			    	
			    }
		
				
				for($li_d=1;$li_d<=$li_total2;$li_d++)
				{
					$ls_odi=$io_report->ds_detalle->getValue("odi",$li_d);			
					$ls_observacion=$io_report->ds_detalle->getValue("observacion",$li_d);			
					$ldt_fecrev=$io_report->ds_detalle->getValue("fecrev",$li_d);	
					$li_peso=$io_report->ds_detalle->getValue("valor",$li_d);
					uf_print_detalle($ls_odi,$ls_observacion,$ldt_fecrev,$li_peso,&$io_pdf);	
				}
				
				if($li_aux<$li_total)
				{
					$io_pdf->ezNewPage(); // Insertar una nueva página
				}
	    	}//fo
	    	
	    	
	    		$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
	 	    	$io_pdf->ezStream(); // Mo
		    
		    
		    
			
			//uf_print_cabecera(&$io_pdf);
		    
		
		
	 }

?>
