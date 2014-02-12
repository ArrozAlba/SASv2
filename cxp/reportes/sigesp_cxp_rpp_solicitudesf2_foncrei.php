<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: SOLICITUDES DE PAGO - FORMATO 2.
//  ORGANISMO: FONCREI
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
		global $io_fun_cxp;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_solicitudesf2.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 11/03/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->line(15,40,585,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$io_pdf->addText(470,735,10,"Fecha: ".date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(476,715,10,"Hora: ".date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codigo,$as_nombre,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_fechadesde // Fecha a partir del cual su buscaran las retenciones.
		//	    		   as_fechahasta // Fecha hasta el cual su buscaran las retenciones.
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 17/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$la_data    = array(array('codigo'=>'<b>Código:</b>','codproben'=>$as_codigo));
			$la_columna = array('codigo'=>'','codproben'=>'');
			$la_config  = array('showHeadings'=>0, // Mostrar encabezados
								'fontSize'=>10,  // Tamaño de Letras
								'showLines'=>0,    // Mostrar Líneas
								'shaded'=>2,       // Sombra entre líneas
							    'shadeCol'=>array(0.9,0.9,0.9),
						 		'shadeCol2'=>array(0.9,0.9,0.9),
								'xPos'=>302, // Orientación de la tabla
								'colGap'=>1,
								'width'=>570,
								'cols'=>array('codigo'=>array('justification'=>'left','width'=>40),
											  'codproben'=>array('justification'=>'left','width'=>530))); // Ancho Máximo de la tabla
			$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
			
			$la_data    = array(array('nombre'=>$as_nombre));
			$la_columna = array('nombre'=>'');
			$la_config  = array('showHeadings'=>0, // Mostrar encabezados
								'fontSize'=>10,  // Tamaño de Letras
								'showLines'=>0,    // Mostrar Líneas
								'shaded'=>2,       // Sombra entre líneas
							    'shadeCol'=>array(0.9,0.9,0.9),
						 		'shadeCol2'=>array(0.9,0.9,0.9),
								'xPos'=>302, // Orientación de la tabla
								'colGap'=>1,
								'width'=>570,
								'cols'=>array('nombre'=>array('justification'=>'left','width'=>570))); // Ancho Máximo de la tabla
			$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,$ai_i,$ai_totmonsol,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data      // arreglo de información
		//				   ai_i         // total de registros
		//				   li_totmonsol // total de solicitudes (Montos)
		//	    		   io_pdf       // Instancia de objeto pdf
		//    Description: Función que imprime el detalle del reporte
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 16/06/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulo=" Bs.F.";
		}
		else
		{
			$ls_titulo=" Bs.";
		}
		$io_pdf->ezSetDy(-2);
		$la_datatit[1]=array('numsol'=>'<b>Solicitud</b>',
		                     'fecemisol'=>'<b>Fecha</b>',
							 'consol'=>'<b>Concepto</b>',
							 'monsol'=>'<b>Monto '.$ls_titulo.'</b>',
							 'denest'=>'<b>Estatus</b>');
		$la_columnas=array('numsol'=>'','fecemisol'=>'','consol'=>'','monsol'=>'','denest'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>9, // Tamaño de Letras
						 'titleFontSize'=>9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2,// Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9),
						 'shadeCol2'=>array(0.9,0.9,0.9),
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness'=>0.5,
						 'cols'=>array('numsol'=>array('justification'=>'center','width'=>95), // Justificación y ancho de la columna
						 			   'fecemisol'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'consol'=>array('justification'=>'center','width'=>245), // Justificación y ancho de la columna
						 			   'monsol'=>array('justification'=>'center','width'=>80),
									   'denest'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);

		$la_columnas=array('numsol'=>'','fecemisol'=>'','consol'=>'','monsol'=>'','denest'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>9, // Tamaño de Letras
						 'titleFontSize'=>12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numsol'=>array('justification'=>'left','width'=>95), // Justificación y ancho de la columna
						 			   'fecemisol'=>array('justification'=>'left','width'=>70), // Justificación y ancho de la columna
						 			   'consol'=>array('justification'=>'left','width'=>245), // Justificación y ancho de la columna
						 			   'monsol'=>array('justification'=>'right','width'=>80),
									   'denest'=>array('justification'=>'left','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

   	 //----------------------------------------------------------------------------------------------------------------------------
	 function uf_print_totales($li_filas,$ld_total,$as_nomproben,&$io_pdf)
	 {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//            Function:  uf_print_totales
		//		        Access:  private 
		//	         Arguments: 
		//           $li_filas:  Número de Registros en el Reporte.
		//           $ld_total:  Monto Total de  los Documentos.
		//       $as_nomproben:  Nombre de Proveedor- Baneficiario
		//       $ld_totcargos:  Monto total de los cargos.
		//	  		    io_pdf:  Objeto PDF
		//         Description:  Función que imprime el detalle.
		//	        Creado Por:  Ing. Néstor Falcón.
		//      Fecha Creación:  04/05/2006.
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data  = array(array('name'=>'__________________________________________________________________________________________'));
		$la_config= array('showHeadings'=>0, // Mostrar encabezados
						  'fontSize' => 10, // Tamaño de Letras
						  'titleFontSize' =>10,  // Tamaño de Letras de los títulos
						  'showLines'=>0, // Mostrar Líneas
						  'shaded'=>0, // Sombra entre líneas
						  'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						  'shadeCol2'=>array(1,1,1), // Color de la sombra
 						  'colGap'=>1,
						  'width'=>520, // Ancho de la tabla
						  'maxWidth'=>520, // Ancho Máximo de la tabla
						  'xOrientation'=>'center', // Orientación de la tabla
						  'cols'=>array('name'=>array('justification'=>'center','width'=>520)));
        $la_columna=array('name'=>'');
	    $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		
		$la_data  =array(array('cantidad'=>'<b>Total de '.$as_nomproben.':</b>'."  ".$li_filas,'totales'=>'<b>Total</b>','monto'=>$ld_total));
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' =>10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
 						 'colGap'=>1,
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>270,
						 'cols'=>array('cantidad'=>array('justification'=>'left','width'=>330),
									   'totales'=>array('justification'=>'right','width'=>70),
									   'monto'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
	    $la_columna=array('cantidad'=>'','totales'=>'','monto'=>'');
	    $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	 }
     //----------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
   	 function uf_print_totaldocumento($ld_total,&$io_pdf)
	 {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//            Function:  uf_print_totaldocumento
		//		        Access:  private 
		//	         Arguments: 
		//           $ld_total:  Monto Total de  los Documentos.
		//	  		    io_pdf:  Objeto PDF
		//         Description:  Función que imprime el total
		//	        Creado Por:  Ing. Luis Anibal Lang
		//      Fecha Creación:  08/03/2007.
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data  = array(array('name'=>'_____________________________________________________________________________________________'));
		$la_config= array('showHeadings'=>0, // Mostrar encabezados
						  'fontSize' => 10, // Tamaño de Letras
						  'titleFontSize' =>10,  // Tamaño de Letras de los títulos
						  'showLines'=>0, // Mostrar Líneas
						  'shaded'=>0, // Sombra entre líneas
						  'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						  'shadeCol2'=>array(1,1,1), // Color de la sombra
 						  'colGap'=>1,
						  'width'=>520, // Ancho de la tabla
						  'maxWidth'=>520, // Ancho Máximo de la tabla
						  'xOrientation'=>'center', // Orientación de la tabla
						  'cols'=>array('name'=>array('justification'=>'center','width'=>520)));
        $la_columna=array('name'=>'');
	    $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data  =array(array('cantidad'=>'<b>Total del Documento </b>','filas'=>'','totales'=>'','monto'=>$ld_total,'blanco'=>''));
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' =>10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
 						 'colGap'=>1,
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Máximo de la tabla
						 'xPos'=>310,
						 'cols'=>array('cantidad'=>array('justification'=>'left','width'=>160),
						               'filas'=>array('justification'=>'left','width'=>20),
									   'totales'=>array('justification'=>'right','width'=>60),
									   'monto'=>array('justification'=>'right','width'=>100),
									   'blanco'=>array('justification'=>'right','width'=>280))); // Justificación y ancho de la columna
	    $la_columna=array('cantidad'=>'','filas'=>'','totales'=>'','monto'=>'');
	    $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	 }
	//------------------------------------------------------------------------------------------------------------------------------

	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_cxp_class_report.php");
	$io_report=new sigesp_cxp_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>SOLICITUDES DE PAGO - BENEFICIARIO</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_solicitudes = $io_fun_cxp->uf_obtenervalor_get("solicitudes","");
    $lr_solicitudes = split('-',$ls_solicitudes);
	$ls_tiporeporte = $io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	if ($ls_tiporeporte==1)
	   {
		 require_once("sigesp_cxp_class_reportbsf.php");
		 $io_report=new sigesp_cxp_class_reportbsf();
	   }
    $lr_datos = array_unique($lr_solicitudes);
    $li_total = count($lr_datos);
	sort($lr_datos,SORT_STRING);
	//--------------------------------------------------------------------------------------------------------------------------------
	$ld_montotrep = 0;
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		if($li_total==0) // Existe algún error ó no hay registros
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
			$io_pdf->ezSetCmMargins(3.6,4.8,3,3); // Configuración de los margenes en centímetros
			$io_pdf->ezStartPageNumbers(550,750,10,'','',1); // Insertar el número de página
			for ($li_i=0;$li_i<$li_total;$li_i++)
			    {
				  $li_totmonsol = 0;
				  $ls_numsol    = $lr_datos[$li_i];
				  $lb_valido    = $io_report->uf_select_solicitudf2($ls_numsol);
				  $li_totrow    = $io_report->ds_detalle->getRowCount("numsol");
				  for ($li_j=1;$li_j<=$li_totrow;$li_j++)
				      {
					    $ls_numsol    = $io_report->ds_detalle->data["numsol"][$li_j];
					    $ls_nombre    = $io_report->ds_detalle->data["nombre"][$li_j];
					    $ld_fecemisol = $io_report->ds_detalle->data["fecemisol"][$li_j];
					    $ls_estprosol = $io_report->ds_detalle->data["estprosol"][$li_j];
					    $li_monsol    = $io_report->ds_detalle->data["monsol"][$li_j];
					    $ls_consol    = $io_report->ds_detalle->data["consol"][$li_j];
					    $ls_tipproben = $io_report->ds_detalle->data["tipproben"][$li_j];
					    if ($ls_tipproben=='P')
					       {
						     $ls_codigo=$io_report->ds_detalle->data["cod_pro"][$li_j];
					       }
					    else
					       { 
						     $ls_codigo=$io_report->ds_detalle->data["ced_bene"][$li_j];
					       }
						switch ($ls_estprosol)
						{
							case 'E':
								$ls_denest='Emitida';
								break;
							case 'C':
								$ls_denest='Contabilizada';
								break;
							case 'A':
								$ls_denest='Anulada';
								break;
							case 'S':
								$ls_denest='Programacion de Pago';
								break;
							case 'P':
								$ls_denest='Pagada';
								break;
						}
						$li_totmonsol   = $li_totmonsol+$li_monsol;
						$li_monsol      = number_format($li_monsol,2,",",".");
						$ld_fecemisol   = $io_funciones->uf_convertirfecmostrar($ld_fecemisol);
						$la_data[$li_j] = array('numsol'=>$ls_numsol, 
						                        'fecemisol'=>$ld_fecemisol,
												'consol'=>$ls_consol,
										        'denest'=>$ls_denest,
												'monsol'=>$li_monsol);
				      }
				  $li_j=$li_j-1;
				  $ld_montotrep += $li_totmonsol;
				  $ld_totmonsol=number_format($li_totmonsol,2,",",".");
				  uf_print_encabezado_pagina($ls_titulo,&$io_pdf);
				  uf_print_cabecera($ls_codigo,$ls_nombre,&$io_pdf);
				  uf_print_detalle($la_data,$li_j,$li_totmonsol,&$io_pdf);
			      uf_print_totales($li_totrow,$ld_totmonsol,$ls_nombre,$io_pdf);
				}
		    $ld_montotrep = number_format($ld_montotrep,2,",",".");
			uf_print_totaldocumento($ld_montotrep,&$io_pdf); 
		}
	 if ($lb_valido) // Si no ocurrio ningún error
		{
		  $io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
		  $io_pdf->ezStream(); // Mostramos el reporte
		}
	}
?>