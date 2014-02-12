<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Reporte de Relacion de Solicitudes
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
		// Fecha Creación: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_relacionsolicitudes.php",$ls_descripcion);
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
		$io_pdf->line(15,40,585,40);
        $io_pdf->Rectangle(15,700,570,60);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$io_pdf->addText(540,770,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(546,764,6,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
			
	function uf_print_detalle($la_data,$ai_i,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//				   as_titcuentas // titulo de estructura presupuestaria
		//				   ai_i // total de registros
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el detalle del reporte
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 10/06/2007 
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
		$la_datatit[1]=array('numsol'=>'<b>Solicitud</b>','nombre'=>'<b>Proveedor / Beneficiario</b>','fecemisol'=>'<b>Fecha Emision</b>',
							 'estprosol'=>'<b>Estatus</b>','monsol'=>'<b>Monto '.$ls_titulo.'</b>');
		$la_columnas=array('numsol'=>'',
						   'nombre'=>'',
						   'fecemisol'=>'',
						   'estprosol'=>'',
						   'monsol'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numsol'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>220), // Justificación y ancho de la columna
						 			   'fecemisol'=>array('justification'=>'center','width'=>75), // Justificación y ancho de la columna
						 			   'estprosol'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
						 			   'monsol'=>array('justification'=>'center','width'=>75))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);

		$la_columnas=array('numsol'=>'',
						   'nombre'=>'',
						   'fecemisol'=>'',
						   'estprosol'=>'',
						   'monsol'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numsol'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>220), // Justificación y ancho de la columna
						 			   'fecemisol'=>array('justification'=>'center','width'=>75), // Justificación y ancho de la columna
						 			   'estprosol'=>array('justification'=>'left','width'=>110), // Justificación y ancho de la columna
						 			   'monsol'=>array('justification'=>'right','width'=>75))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$la_datatot[1]=array('titulo'=>'<b>Total de Registros: </b>','total'=>$ai_i);
		$la_columnas=array('titulo'=>'<b>Factura</b>',
						   'total'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatot,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_cxp_class_report.php");
	$io_report=new sigesp_cxp_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>RELACION CONSECUTIVA DE SOLICITUDES DE PAGO</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_numsoldes=$io_fun_cxp->uf_obtenervalor_get("numsoldes","");
	$ls_numsolhas=$io_fun_cxp->uf_obtenervalor_get("numsolhas","");
	$ls_tiporeporte=$io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_cxp_class_reportbsf.php");
		$io_report=new sigesp_cxp_class_reportbsf();
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_select_relacionsolicitudes($ls_numsoldes,$ls_numsolhas); // Cargar el DS con los datos del reporte
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
			set_time_limit(1800);
			$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(3.6,3,3,3); // Configuración de los margenes en centímetros
			$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página
			$li_totrow=$io_report->DS->getRowCount("numsol");
			$li_totmonsol="";
			$ls_estretiva=$_SESSION["la_empresa"]["estretiva"];
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$ls_numsol=$io_report->DS->data["numsol"][$li_i];
				$ls_nombre=$io_report->DS->data["nombre"][$li_i];
				$ld_fecemisol=$io_report->DS->data["fecemisol"][$li_i];
				$ls_estprosol=$io_report->DS->data["estprosol"][$li_i];
				$li_monsol=$io_report->DS->data["monsol"][$li_i];
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
					case "N":
						$ls_denest="Anulada sin Afectacion";
						break;
				}
				if($ls_estretiva=="B")
				{
					$li_monretiva=$io_report->uf_select_det_deducciones_solpag($ls_numsol);
					$li_monsol=$li_monsol+$li_monretiva;
				}
				$li_totmonsol=$li_totmonsol+$li_monsol;
				$li_monsol=number_format($li_monsol,2,",",".");
				$ld_fecemisol=$io_funciones->uf_convertirfecmostrar($ld_fecemisol);
				$la_data[$li_i]=array('numsol'=>$ls_numsol,'nombre'=>$ls_nombre,'fecemisol'=>$ld_fecemisol,
									  'estprosol'=>$ls_denest,'monsol'=>$li_monsol);
			}
			uf_print_encabezado_pagina($ls_titulo,&$io_pdf);
			uf_print_detalle($la_data,$li_i,&$io_pdf);
		}
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		
	}

?>
