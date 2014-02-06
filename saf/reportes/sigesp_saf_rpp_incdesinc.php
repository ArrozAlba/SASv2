<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//  ESTE FORMATO SE IMPRIME EN Bs Y EN BsF. SEGUN LO SELECCIONADO POR EL USUARIO
	//  MODIFICADO POR: ING.YOZELIN BARRAGAN         FECHA DE MODIFICACION : 28/08/2007
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
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_titulo2,$ad_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   ad_fecha // Fecha 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 17/12/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(15,40,585,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo2);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,710,11,$as_titulo2);
		$io_pdf->addText(540,770,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(546,764,6,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codemp,$as_nomemp,$as_coduniadm,$as_denuniadm,$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codemp   // codigo de empresa
		//	    		   as_nomemp   // nombre de empresa
		//	    		   as_codact   // codigo de activo
		//	    		   as_denact   // denominacion de activo
		//	    		   as_maract   // marca del activo
		//	    		   as_modact   // modelo del activo
		//	    		   ai_costo    // costo del activo
		//	    		   io_pdf      // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 17/12/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>ORGANISMO: '.$as_codemp." - ".$as_nomemp.'</b>'),
		               array('name'=>'<b>UNIDAD ADMINISTRATIVA: '.$as_coduniadm.' '.$as_denuniadm.'</b>'),
					   array('name'=>'<b>UNIDAD DE BIENES</b>'),
					   array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'lineCol'=>array(0.9,0.9,0.9), // Mostrar Líneas
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
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
		// Fecha Creación: 17/12/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		global $ls_tipoformato;
		if($ls_tipoformato==0)
		{
		  $ls_titulo=" Bs.";
		}
		elseif($ls_tipoformato==1)
		{
		  $ls_titulo=" Bs.F.";
		}
		$la_datatit[0]=array('codact'=>'<b>Codigo del Bien</b>','tot_inc'=>'<b>Incorporaciones</b>','tot_desinc'=>'<b>Desincorporaciones</b>');
		$la_columna=array('codact'=>'',
						  'tot_inc'=>'',
						  'tot_desinc'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codact'=>array('justification'=>'center','width'=>200), // Justificación y ancho de la columna
						 			   'tot_inc'=>array('justification'=>'center','width'=>170), // Justificación y ancho de la columna
									   'tot_desinc'=>array('justification'=>'center','width'=>170) // Justificación y ancho de la columna
									    )); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columna,'',$la_config);
		$la_columna=array('codact'=>'',
						  'tot_inc'=>'',
						  'tot_desinc'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codact'=>array('justification'=>'center','width'=>200), // Justificación y ancho de la columna
						 			   'tot_inc'=>array('justification'=>'right','width'=>170), // Justificación y ancho de la columna
									   'tot_desinc'=>array('justification'=>'right','width'=>170) // Justificación y ancho de la columna
									    )); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ai_montotinc,$ai_montotdesinc,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera
		//		   Access: private 
		//	    Arguments: ai_montot // Total movimiento
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing.Arnaldo Suárez
		// Fecha Creación: 17/12/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('total'=>'TOTAL','monto_inc'=>$ai_montotinc,'monto_desinc'=>$ai_montotdesinc));
		$la_columna=array('total'=>'','monto_inc'=>'','monto_desinc'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>2, // Mostrar Líneas
						 'fontSize' => 8, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>200),  // Justificación y ancho de la columna
						               'monto_inc'=>array('justification'=>'right','width'=>170),// Justificación y ancho de la columna
						               'monto_desinc'=>array('justification'=>'right','width'=>170))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_funciones_activos.php");
	$io_fun_activos=new class_funciones_activos();
	$ls_tipoformato=$io_fun_activos->uf_obtenervalor_get("tipoformato",0);
	global $ls_tipoformato;
	if($ls_tipoformato==1)
	{
		require_once("sigesp_saf_class_reportbsf.php");
		$io_report=new sigesp_saf_class_reportbsf();
		$ls_titulo_report="Bs.F.";
	}
	else
	{
		require_once("sigesp_saf_class_report.php");
		$io_report=new sigesp_saf_class_report();
		$ls_titulo_report="Bs.";
	}	
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ld_desde=$io_fun_activos->uf_obtenervalor_get("desde","");
	$ld_hasta=$io_fun_activos->uf_obtenervalor_get("hasta","");

	$ls_titulo= "REPORTE DE INCORPORACIONES Y DESINCORPORACIONES ";
	$ls_titulo2 ="DE LOS BIENES DE UN DEPARTAMENTO EN ".$ls_titulo_report."";
	$ld_fecha="";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_nomemp=$arre["nombre"];
	$li_ordenact=$io_fun_activos->uf_obtenervalor_get("ordenact","");
	$ls_coddesde=$io_fun_activos->uf_obtenervalor_get("coddesde","");
	$ls_codhasta=$io_fun_activos->uf_obtenervalor_get("codhasta","");
	$ls_grupo=$io_fun_activos->uf_obtenervalor_get("grupo","");
	$ls_subgrupo=$io_fun_activos->uf_obtenervalor_get("subgrupo","");
	$ls_seccion=$io_fun_activos->uf_obtenervalor_get("seccion","");
	$ls_coduniadm=$io_fun_activos->uf_obtenervalor_get("coduniadm","");
	$ls_denuniadm=$io_fun_activos->uf_obtenervalor_get("denuniadm","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_saf_load_incdesinc($ls_codemp,$li_ordenact,$ld_desde,$ld_hasta,$ls_coddesde,$ls_codhasta,$ls_grupo,$ls_subgrupo,$ls_seccion,$ls_coduniadm); // Cargar el DS con los datos de la cabecera del reporte
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print("alert('No hay nada que Reportar');"); 
		print("close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		/////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////
		$ls_desc_event="Generó un reporte de Incorporaciones y Desincorporaciones de Bienes Muebles por Departamento";
		$io_fun_activos->uf_load_seguridad_reporte("SAF","sigesp_saf_r_activo.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.6,4.8,3,3); // Configuración de los margenes en centímetros
		$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página
		uf_print_encabezado_pagina($ls_titulo,$ls_titulo2,$ld_fecha,$io_pdf); // Imprimimos el encabezado de la página
		$li_totrow=$io_report->ds->getRowCount("codact");
		$i=0;
		$li_tot_gen_inc = 0.00;
		$li_tot_gen_desinc= 0.00;
		$la_data="";
		if($lb_valido)
		{
		  for($li_i=1;$li_i<=$li_totrow;$li_i++)
		  {
			$io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount; // Número de página
			$ls_codact=$io_report->ds->data["codact"][$li_i];
			$li_totinc=$io_report->ds->data["tot_inc"][$li_i];
			$li_totdesinc=$io_report->ds->data["tot_desinc"][$li_i];
			$li_tot_gen_inc = $li_tot_gen_inc + $li_totinc;
			$li_tot_gen_desinc= $li_tot_gen_desinc + $li_totdesinc;
			if ($li_totinc <> 0) 
			{
			$li_totinc = $io_fun_activos->uf_formatonumerico($li_totinc);
			}
			else
			{
			 $li_totinc = "";
			}
			if ($li_totdesinc <> 0) 
			{
			$li_totdesinc = $io_fun_activos->uf_formatonumerico($li_totdesinc);
			}
			else
			{
			 $li_totdesinc = "";
			}
			
			$la_data[$li_i]=array('codact'=>$ls_codact,'tot_inc'=>$li_totinc,'tot_desinc'=>$li_totdesinc);
		   }	
				if($la_data!="")
				{
					$i=$i +1;
					uf_print_cabecera($ls_codemp,$ls_nomemp,$ls_coduniadm,$ls_denuniadm,$io_pdf); // Imprimimos la cabecera del registro
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					//uf_print_pie_de_pagina(&$io_pdf);
					if ($io_pdf->ezPageCount==$li_numpag)
					{// Hacemos el commit de los registros que se desean imprimir
					    $li_tot_gen_inc  = $io_fun_activos->uf_formatonumerico($li_tot_gen_inc);
						$li_tot_gen_desinc  = $io_fun_activos->uf_formatonumerico($li_tot_gen_desinc);
						uf_print_pie_cabecera($li_tot_gen_inc,$li_tot_gen_desinc,$io_pdf);
						$io_pdf->transaction('commit');
					}
					else
					{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
						$io_pdf->transaction('rewind');
						$io_pdf->ezNewPage(); // Insertar una nueva página
						uf_print_cabecera($ls_codemp,$ls_nomemp,$ls_codgru,$ls_dengru,$io_pdf); // Imprimimos la cabecera del registro
						uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
						//uf_print_pie_cabecera($li_tot_gen_inc,$li_tot_gen_desinc,$io_pdf);
						//uf_print_pie_de_pagina(&$io_pdf);
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
?> 