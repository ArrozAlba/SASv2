<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Listado de Solicitudes de Ejecucion Presupuestaria
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
		global $io_fun_sep;
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_sep->uf_load_seguridad_reporte("SEP","sigesp_sep_r_solicitudes.php",$ls_descripcion);
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
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$io_pdf->addText(500,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(506,743,7,date("h:i a")); // Agregar la Hora
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
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 13/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('codigo'=>'<b>Solicitud</b>',
						   'nombre'=>'<b>Proveedor / Beneficiario</b>',
						   'status'=>'<b>Estatus</b>',
						   'documento'=>'<b>Documento</b>',
						   'origen'=>'<b>Origen</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>170), // Justificación y ancho de la columna
						 			   'status'=>array('justification'=>'left','width'=>90), // Justificación y ancho de la columna
						 			   'documento'=>array('justification'=>'left','width'=>100), // Justificación y ancho de la columna
						 			   'origen'=>array('justification'=>'left','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($ai_total,$ai_totrows,$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		   Access: private 
		//	    Arguments: ai_total // Total por personal
		//	   			   ai_totrows // Total por patrón
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por concepto
		//	   Creado Por: Ing. Yesenia Moreno /Ing. Luis Lang
		// Fecha Creación: 13/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>Total Solicitudes</b>','totrows'=>$ai_totrows,'total'=>$ai_total));
		$la_columna=array('name'=>'','totrows'=>'','total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('name'=>array('justification'=>'right','width'=>390), // Justificación y ancho de la columna
						 			   'totrows'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_sep.php");
	$io_fun_sep=new class_funciones_sep();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>UBICACION DE SOLICITUDES DE EJECUCIÓN PRESUPUESTARIA</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_numsoldes=$io_fun_sep->uf_obtenervalor_get("numsoldes","");
	$ls_numsolhas=$io_fun_sep->uf_obtenervalor_get("numsolhas","");
	$ls_tipproben=$io_fun_sep->uf_obtenervalor_get("tipproben","");
	$ls_codprobendes=$io_fun_sep->uf_obtenervalor_get("codprobendes","");
	$ls_codprobenhas=$io_fun_sep->uf_obtenervalor_get("codprobenhas","");
	//--------------------------------------------------------------------------------------------------------------------------------
	require_once("sigesp_sep_class_report.php");
	$io_report=new sigesp_sep_class_report();
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$rs_data=$io_report->uf_select_ubicacionsolicitudes($ls_numsoldes,$ls_numsolhas,$ls_tipproben,$ls_codprobendes,
															  $ls_codprobenhas,$lb_valido); // Cargar el DS con los datos del reporte
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
			$io_pdf->ezSetCmMargins(3.6,2.5,3,3); // Configuración de los margenes en centímetros
			uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
			$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
			$li_totrow=$io_report->io_sql->num_rows($rs_data);
			$li_s=0;
			if($li_totrow>0)
			{			
				while((!$rs_data->EOF))
				{
					$li_ok=0;
					$ls_numsol=$rs_data->fields["numsol"]; 
					$ls_estsol=$rs_data->fields["estsol"];
					$ls_estope=$rs_data->fields["estope"];
					$ls_modsep=$rs_data->fields["modsep"];
					$ls_codpro=$rs_data->fields["cod_pro"];
					$ls_cedbene=$rs_data->fields["ced_bene"];
					$ls_nombre=$rs_data->fields["nombre"];
					$ls_estapro=$rs_data->fields["estapro"];
					switch ($ls_estsol)
					{
						case "R":
							$ls_estsol="Registro";					
							break;
						case "E":
							if ($ls_estapro==0)
							{
							  $ls_estsol="Emitida";					
							}
							else
							{
							  $ls_estsol="Aprobada";
							}					
							break;
						case "C":
							$ls_estsol="Contabilizada";					
							break;
						case "A":
							$ls_estsol="Anulada";					
							break;
						case "P":
							$ls_estsol="Procesada";					
							break;
						case "D":
							$ls_estsol="Despachada";
							break;
					}
					if($ls_estope=="O")
					{
						$rs_datacomp=$io_report->uf_load_sep_ubicacioncompromiso($ls_numsol,$ls_codpro,$ls_cedbene,"SEPSPC",&$lb_valido);
						$li_rowdet=$io_report->io_sql->num_rows($rs_datacomp);
						if($li_rowdet>0)
						{
							while((!$rs_datacomp->EOF))
							{	
								$ls_documento  = $rs_datacomp->fields["documento"];
								$ls_estatus  = $rs_datacomp->fields["estatus"];
								$ls_origen  = $rs_datacomp->fields["origen"];
								if($ls_origen=="RD")
								{						
									$ls_origen="Recepcion de Documentos";
									switch($ls_estatus)
									{
										case "R": 
											$ls_estatus="Recibida";
											break;
										case "E": 
											$ls_estatus="Emitida";
											break;
										case "C": 
											$ls_estatus="Contabilizada";
											break;
										case "A": 
											$ls_estatus="Anulada";
											break;
									}
								}
								$li_s=$li_s+1;
								$li_ok=1;
								$la_data[$li_s]= array('codigo'=>$ls_numsol,'nombre'=>$ls_nombre,'status'=>$ls_estsol,
												       'documento'=>$ls_documento,'origen'=>$ls_origen);
								$rs_datacomp->MoveNext();
							}
						}
					}
					else
					{
						$rs_datadet=$io_report->uf_load_sep_ubicacionprecompromiso($ls_numsol,&$lb_valido);
						$li_rowdet=$io_report->io_sql->num_rows($rs_datadet);
						if($li_rowdet>0)
						{
							while((!$rs_datadet->EOF))
							{	
								$ls_documento  = $rs_datadet->fields["numdocdes"];
								$ls_estatus  ="EN ESPERA";// $rs_data1->fields["estatus"];
								$ls_origen  = $rs_datadet->fields["estincite"];
								if($ls_origen=="SC")
								{						
									$ls_origen="Solicitud de Cotizacion";
									switch($ls_estatus)
									{
										case "R": 
											$ls_estatus="Recibida";
											break;
										case "E": 
											$ls_estatus="Emitida";
											break;
										case "C": 
											$ls_estatus="Contabilizada";
											break;
										case "A": 
											$ls_estatus="Anulada";
											break;
									}
								}
								if($ls_origen=="OC")
								{
									$ls_origen="Orden de Compra";
								}
								$li_s=$li_s+1;
								$li_ok=1;
								$la_data[$li_s]= array('codigo'=>$ls_numsol,'nombre'=>$ls_nombre,'status'=>$ls_estsol,
												 	   'documento'=>$ls_documento,'origen'=>$ls_origen);
								$rs_datadet->MoveNext();
							}
						}
					}
					if($li_ok==0)
					{
						$li_s=$li_s+1;
						$li_ok=0;
						$la_data[$li_s]= array('codigo'=>$ls_numsol,'nombre'=>$ls_nombre,'status'=>$ls_estsol,
											   'documento'=>"",'origen'=>"");
					}
					$rs_data->MoveNext();
				}
				uf_print_detalle($la_data,&$io_pdf);
				unset($la_data);
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
		}
	}

?>
