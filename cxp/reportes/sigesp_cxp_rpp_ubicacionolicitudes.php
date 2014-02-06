<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Listado de Documentos
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
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_solicitudesf1.php",$ls_descripcion);
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
			
	function uf_print_detalle($la_data,&$io_pdf)
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
		$io_pdf->ezSetDy(-2);
		$la_datatit[1]=array('numsol'=>'<b>Solicitud</b>','nsestatus'=>'<b>Estatus Solicitud</b>','numche'=>'<b>Cheque</b>',
							 'ctaban'=>'<b>Cuenta</b>','banco'=>'<b>Banco</b>','chestatus'=>'<b>Estatus</b>');
		$la_columnas=array('numsol'=>'',
						   'nsestatus'=>'',
						   'numche'=>'',
						   'ctaban'=>'',
						   'banco'=>'',
						   'chestatus'=>'');
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
						 			   'nsestatus'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
						 			   'numche'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'ctaban'=>array('justification'=>'center','width'=>115), // Justificación y ancho de la columna
						 			   'banco'=>array('justification'=>'center','width'=>90),
									   'chestatus'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);

		$la_columnas=array('numsol'=>'',
						   'nsestatus'=>'',
						   'numche'=>'',
						   'ctaban'=>'',
						   'banco'=>'',
						   'chestatus'=>'');
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
						 			   'nsestatus'=>array('justification'=>'left','width'=>110), // Justificación y ancho de la columna
						 			   'numche'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'ctaban'=>array('justification'=>'center','width'=>115), // Justificación y ancho de la columna
						 			   'banco'=>array('justification'=>'left','width'=>90),
									   'chestatus'=>array('justification'=>'left','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
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
	$ls_titulo="<b>UBICACION DE SOLICITUDES DE PAGO</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_tipproben=$io_fun_cxp->uf_obtenervalor_get("tipproben","");
	$ls_codprobendes=$io_fun_cxp->uf_obtenervalor_get("codprobendes","");
	$ls_codprobenhas=$io_fun_cxp->uf_obtenervalor_get("codprobenhas","");
	$ls_numsoldes=$io_fun_cxp->uf_obtenervalor_get("numsoldes","");
	$ls_numsolhas=$io_fun_cxp->uf_obtenervalor_get("numsolhas","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$rs_data=$io_report->uf_select_solicitudesubicar($ls_tipproben,$ls_codprobendes,$ls_codprobenhas,$ls_numsoldes,$ls_numsolhas,&$lb_valido); // Cargar el DS con los datos del reporte
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
			$io_pdf->ezSetCmMargins(3.6,4.8,3,3); // Configuración de los margenes en centímetros
			$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página
			$li_totrow=$io_report->io_sql->num_rows($rs_data);
			if($li_totrow>0)
			{			
				$li_i=0;
				while((!$rs_data->EOF))
				{
					$ls_numsol=$rs_data->fields["numsol"];
					$ls_nombre=$rs_data->fields["nombre"];
					$ld_fecemisol=$rs_data->fields["fecemisol"];
					$ls_estprosol=$rs_data->fields["estprosol"];
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
					$rs_datadet=$io_report->uf_load_ubicacionsol($ls_numsol,&$lb_valido);
					$li_rowdet=$io_report->io_sql->num_rows($rs_datadet);
					if($li_rowdet>0)
					{
						while((!$rs_datadet->EOF))
						{
							$li_i++;
							$ls_numsol=$rs_datadet->fields["numsol"];
							$ls_numdoc=$rs_datadet->fields["numdoc"];
							$ls_codban=$rs_datadet->fields["codban"];
							$ls_ctaban=$rs_datadet->fields["ctaban"];
							$ls_estmov=$rs_datadet->fields["estmov"];
							$ls_banco=$rs_datadet->fields["banco"];
							switch ($ls_estmov)
							{
								case 'N':
									$ls_estmov='No Contabilizado';
									break;
								case 'C':
									$ls_estmov='Contabilizado';
									break;
								case 'L':
									$ls_estmov='No Contabilizable';
									break;
								case 'A':
									$ls_estmov='Anulado';
									break;
								case 'O':
									$ls_estmov='Original';
									break;
							}
							$la_data[$li_i]=array('numsol'=>$ls_numsol,'nsestatus'=>$ls_denest,'numche'=>$ls_numdoc,'ctaban'=>$ls_ctaban,
												  'banco'=>$ls_banco,'chestatus'=>$ls_estmov);
							
							$rs_datadet->MoveNext();
						}	
					}
					else
					{
						$li_i++;
						$la_data[$li_i]=array('numsol'=>$ls_numsol,'nsestatus'=>$ls_denest,'numche'=>"",'ctaban'=>"",
											  'banco'=>"",'chestatus'=>"");
					}
					$rs_data->MoveNext();
				}
				uf_print_encabezado_pagina($ls_titulo,&$io_pdf);
				uf_print_detalle($la_data,&$io_pdf);
			}
		}
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		
	}

?>
