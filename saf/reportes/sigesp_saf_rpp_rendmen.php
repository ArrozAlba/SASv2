<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//  ESTE FORMATO SE IMPRIME EN Bs Y EN BsF. SEGUN LO SELECCIONADO POR EL USUARIO
	//  MODIFICADO POR: ING.YOZELIN BARRAGAN         FECHA DE MODIFICACION : 27/08/2007
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "</script>";		
	}//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_estado,$as_municipio,$as_codemp,$as_nomemp,$as_diremp,$ad_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   ad_fecha // Fecha 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Luis Anibal Lang
		//     Modificado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->saveState();
		$io_pdf->line(50,40,950,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],22,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=504-($li_tm/2);
		$io_pdf->addText($tm,550,11,"<b>".$as_titulo."</b>"); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$ad_fecha);
		$tm=504-($li_tm/2);
		$io_pdf->addText(750,535,11,""); // Agregar la fecha
		$io_pdf->addText($tm,535,11,$ad_fecha); // Agregar la fecha
		$io_pdf->addText(750,555,11,""); // Agregar la fecha
		$io_pdf->addText(800,555,11,""); // Agregar la fecha
		$io_pdf->addText(928,570,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(934,563,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
		$la_data=array(array('name'=>'<b>1. ESTADO:</b>  '.$as_estado.''),
					   array('name'=>'<b>2. MUNICIPIO:</b> '.$as_municipio.''),
					   array('name'=>'<b>3. DIRECCION O LUGAR:</b>  '.$as_diremp.''),
					   array('name'=>'<b>4. DEPENDENCIA O UNIDAD PRIMARIA:</b>  '.$as_codemp."-".$as_nomemp.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'lineCol'=>array(0.9,0.9,0.9), // Mostrar Líneas
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>954, // Ancho de la tabla
						 'maxWidth'=>954); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codemp   // codigo de empresa
		//	    		   as_nomemp   // nombre de empresa
		//	    		   io_pdf      // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 17/12/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $la_datatit[0]=array('coduniadm'=>'<b>Codigo</b>','denuniadm'=>'<b>Denominacion</b>','saldo_anterior'=>'<b>Saldo Anterior</b>','tot_inc'=>'<b>Incorporacion</b>',
				             'tot_desinc'=>'<b>Desincorporacion</b>','tot_desinc_060'=>'<b>Faltante Cod. 060</b>','saldo_actual'=>'<b>Saldo Actual</b>');
		$la_columna=array('coduniadm'=>'',
						  'denuniadm'=>'',
						  'saldo_anterior'=>'',
						  'tot_inc'=>'',
						  'tot_desinc'=>'',
						  'tot_desinc_060'=>'',
						  'saldo_actual'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>954, // Ancho de la tabla
						 'maxWidth'=>954, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('coduniadm'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'denuniadm'=>array('justification'=>'center','width'=>254), // Justificación y ancho de la columna
									   'saldo_anterior'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
									   'tot_inc'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
									   'tot_desinc'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
									   'tot_desinc_060'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
									   'saldo_actual'=>array('justification'=>'center','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
	
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 02/01/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_columna=array('coduniadm'=>'<b>Codigo</b>',
						  'denuniadm'=>'<b>Cuenta</b>',
						  'saldo_anterior'=>'<b>Saldo Anterior</b>',
						  'tot_inc'=>'<b>Incorporacion</b>',
						  'tot_desinc'=>'<b>Desincorporacion</b>',
						  'tot_desinc_060'=>'<b>Faltante Cod. 060</b>',
						  'saldo_actual'=>'<b>Saldo Actual</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>900, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('coduniadm'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'denuniadm'=>array('justification'=>'center','width'=>254), // Justificación y ancho de la columna
									   'saldo_anterior'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
									   'tot_inc'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
									   'tot_desinc'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
									   'tot_desinc_060'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
									   'saldo_actual'=>array('justification'=>'center','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_funciones_activos.php");
	$io_fun_activos=new class_funciones_activos();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ld_desde=$io_fun_activos->uf_obtenervalor_get("desde","");
	$ld_hasta=$io_fun_activos->uf_obtenervalor_get("hasta","");
	$ld_fecha="";
	global $ls_tipoformato;
	$ls_titulo="<b>RENDICION MENSUAL DE LA CUENTA DE BIENES MUEBLES ";
	if($ls_tipoformato==0)
	{
	 $ls_titulo=$ls_titulo."CON COSTO EXPRESADO EN BS.</b>";
	}
	elseif($ls_tipoformato==1)
	{
	 $ls_titulo="CON COSTO EXPRESADO EN BS.F.</b>";
	}
	if(($ld_desde!="")&&($ld_hasta!=""))
	{
		if (substr($ld_desde,3,2) == '01')
		{
		 $ld_mes = 12;
		 $ld_anno = intval(substr($ld_desde,6,4)) - 1;
		}
		else
		{
		 $ld_mes  = intval(substr($ld_desde,3,2))-1;
		 $ld_anno = intval(substr($ld_desde,6,4));
		}
		$ld_fecha="Desde:".$ld_desde."  Hasta:".$ld_hasta."";
	}
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_nomemp=$arre["nombre"];
	$ls_estemp=$arre["estemp"];
	$ls_diremp=$arre["direccion"];
	$ls_ordenact=$io_fun_activos->uf_obtenervalor_get("ordenact","");
	$ls_coduniadm_desde=$io_fun_activos->uf_obtenervalor_get("coduniadm_desde","");
	$ls_coduniadm_hasta=$io_fun_activos->uf_obtenervalor_get("coduniadm_hasta","");
	$ls_tipoformato=$io_fun_activos->uf_obtenervalor_get("tipoformato",0);
	$ls_orden=$io_fun_activos->uf_obtenervalor_get("orden",0);
	global $ls_tipoformato;
	if($ls_tipoformato==1)
	{
		require_once("sigesp_saf_class_reportbsf.php");
		$io_report=new sigesp_saf_class_reportbsf();
	}
	else
	{
		require_once("sigesp_saf_class_report.php");
		$io_report=new sigesp_saf_class_report();
	}	
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_saf_load_rendmen($ls_codemp,$ls_coduniadm_desde,$ls_coduniadm_hasta,$ld_mes,$ld_anno,$ld_desde,$ld_hasta,$ai_orden); // Cargar el DS con los datos de la cabecera del reporte
	
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		/////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////
		$ls_desc_event="Generó un reporte de Rendicion Mensual de la Cuenta de Bienes Muebles. Desde el activo   ".$ls_coddesde." hasta   ".$ls_codhasta;
		//$io_fun_activos->uf_load_seguridad_reporte("SAF","sigesp_saf_r_relmovbm2.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		$io_pdf->ezStartPageNumbers(940,50,10,'','',1); // Insertar el número de página
		uf_print_encabezado_pagina($ls_titulo,$ls_estemp,'',$ls_codemp,$ls_nomemp,$ls_diremp,$ld_fecha,$io_pdf); // Imprimimos el encabezado de la página
		$li_totrow=$io_report->ds->getRowCount("coduniadm");
		$i=0;
	        $io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount;
			if($lb_valido)
			{
				$la_data="";
				for($li_s=1;$li_s<=$li_totrow;$li_s++)
				{
				    $ls_coduniadm=$io_report->ds->data["coduniadm"][$li_s];
					$ls_denuniadm=$io_report->ds->data["denuniadm"][$li_s];
					$li_saldo_anterior=$io_report->ds->data["saldo_anterior"][$li_s];
					$li_tot_inc=$io_report->ds->data["tot_inc"][$li_s];
					$li_tot_desinc=$io_report->ds->data["tot_desinc"][$li_s];
					$li_tot_desinc_060=$io_report->ds->data["tot_desinc_060"][$li_s];
					$li_saldo_actual = $li_saldo_anterior + $li_tot_inc - $li_tot_desinc - $li_tot_desinc_060;
					if ($li_saldo_anterior >0)
					{
					 $li_saldo_anterior = $io_fun_activos->uf_formatonumerico($li_saldo_anterior);
					}
					else
					{
					 $li_saldo_anterior = '0,00';
					}
					
					if ($li_tot_inc >0)
					{
					 $li_tot_inc = $io_fun_activos->uf_formatonumerico($li_tot_inc);
					}
					else
					{
					$li_tot_inc = '0,00';
					}
					
					if ($li_tot_desinc >0)
					{
					 $li_tot_desinc = $io_fun_activos->uf_formatonumerico($li_tot_desinc);
					}
					else
					{
					$li_tot_desinc = '0,00';
					} 
					
					if ($li_tot_desinc_060 >0)
					{
					 $li_tot_desinc_060 = $io_fun_activos->uf_formatonumerico($li_tot_desinc_060);
					}
					else
					{
					$li_tot_desinc_060 = '0,00';
					}
					
					if ($li_saldo_actual <> 0)
					{
					 $li_saldo_actual = $io_fun_activos->uf_formatonumerico($li_saldo_actual);
					}
					else
					{
					$$li_saldo_actual = '0,00';
					}
					
					$la_data[$li_s]=array('coduniadm'=>$ls_coduniadm,'denuniadm'=>$ls_denuniadm,'saldo_anterior'=>$li_saldo_anterior,'tot_inc'=>$li_tot_inc,'tot_desinc'=>$li_tot_desinc,'tot_desinc_060'=>$li_tot_desinc_060,'saldo_actual'=>$li_saldo_actual);
				}
				
				if($la_data!="")
				{
					$i=$i +1;
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					if ($io_pdf->ezPageCount==$li_numpag)
					{// Hacemos el commit de los registros que se desean imprimir
						$io_pdf->transaction('commit');
					}
					else
					{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
						$io_pdf->transaction('rewind');
						$io_pdf->ezNewPage(); // Insertar una nueva página
					    uf_print_cabecera($io_pdf);  // Imprimimos la cabecera del registro
						uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					}
				}
			  unset($la_data);
			}
		if(($lb_valido)&&($i>0))
		{
			$io_pdf->ezStopPageNumbers(1,1);
			$io_pdf->ezStream();
		}
		else
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