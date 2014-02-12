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
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_cmpmov,$ad_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_cmpmov // numero de comprobante de movimiento
		//	    		   ad_fecha // Fecha 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Luis Anibal Lang
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
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_estado,$as_municipio,$as_denuniadm,$as_cmpmov,$ad_feccmp,$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: 
		//                 as_estado     //nombre del estado 
		//                 as_municipio  // nombre del municipio
		//                 as_diremp     // direccion de la empresa
		//                 as_codemp     // codigo de empresa
		//	    		   as_nomemp     // nombre de empresa
		//                 as_denunadm   // nombre de la Unidad Administrativa
		//	    		   io_pdf        // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 13/12/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tipoformato;
		if($ls_tipoformato==0)
		{
		  $ls_titulo="Costo Bs.:";
		}
		elseif($ls_tipoformato==1)
		{
		  $ls_titulo="Costo Bs.F.:";
		}
		$la_data=array(array('name'=>'<b> ESTADO:</b>  '.$as_estado.''),
					   array('name'=>'<b> MUNICIPIO:</b> '.$as_municipio.''),
					   array('name'=>'<b> UNIDAD DE TRABAJO:</b> '.$as_denuniadm.''),
					   array('name'=>'<b> UBICACION ADMINISTRATIVA</b>'),
					   array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'lineCol'=>array(0.9,0.9,0.9), // Mostrar Líneas
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>900); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->setStrokeColor(0,0,0);
        $io_pdf->Rectangle(550,439,399,72);
		$io_pdf->addText(555,500,9,"<b>                                                IDENTIFICACION DEL COMPROBANTE         </b>");
        $io_pdf->addText(555,485,9,"<b>CODIGO CONCEPTO DE MOVIMENTO:          060 </b>");
        $io_pdf->addText(555,465,9,"<b>NUMERO DE COMPROBANTE:            ".$as_cmpmov."</b>");
        $io_pdf->addText(555,445,9,"<b>FECHA DE LA OPERACION:                      ".$ad_feccmp."</b>");
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
		// Fecha Creación: 14/12/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_columna=array('codgru'=>'<b>Grupo</b>',
						  'codsubgru'=>'<b>SubGrupo</b>',
						  'codsec'=>'<b>Seccion</b>',
						  'ideact'=>'<b>Nro. de Identificacion</b>',
						  'denact'=>'<b>Descripcion</b>',
						  'maract'=>'<b>Marca</b>',
						  'modact'=>'<b>Modelo</b>',
						  'cantidad'=>'<b>Existencias Fisicas</b>',
						  'regcont'=>'<b>Registros Contables</b>',
						  'costo'=>'<b>Valor Unitario</b>',
						  'cantdif'=>'<b>Diferencia</b>',
						  'costot'=>'<b>Valor Total</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>900, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codgru'=>array('justification'=>'left','width'=>50), // Justificación y ancho de la columna
						 			   'codsubgru'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'codsec'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'ideact'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'denact'=>array('justification'=>'left','width'=>170), // Justificación y ancho de la columna
						 			   'maract'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'modact'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'cantidad'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'regcont'=>array('justification'=>'left','width'=>60), // Justificación y ancho de la columna
						 			   'costo'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'cantdif'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'costot'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera
		//		   Access: private 
		//	    Arguments: ai_montot // Total movimiento
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>900); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$la_data=array(array('total'=>""));
		$la_columna=array('total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 8, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>900, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>900))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera


function uf_print_pie_de_pagina(&$io_pdf)
{
 $io_pdf->setStrokeColor(0,0,0);
 $io_pdf->Rectangle(50,65,450,100);	
 $io_pdf->addText(55,140,9,"<b>OBSERVACIONES:</b>"); // Para Mostrar las Observaciones
 
 $io_pdf->Rectangle(550,65,400,100);	
 $io_pdf->addText(560,140,9,"<b>FALTANTES DETERMINADOS POR:______________________________________________</b>");
 $io_pdf->addText(560,120,9,"<b>CARGO QUE DESEMPEÑA:_____________________________________________________</b>");
 $io_pdf->addText(560,100,9,"<b>DEPENDENCIA A LA CUAL ESTA ADSCRITO:_____________________________________</b>");
 $io_pdf->addText(560,80,9,"                                <b>FIRMA:______________________________________________________</b>");
}
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
	$ls_titulo="<b>RELACION DE BIENES MUEBLES FALTANTES</b>";
	if(($ld_desde!="")&&($ld_hasta!=""))
	{
		$ld_fecha="Desde:".$ld_desde."  Hasta:".$ld_hasta."";
	}
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_estemp=$arre["estemp"];
	$ls_nomemp=$arre["nombre"];
	$ls_ordenact=$io_fun_activos->uf_obtenervalor_get("ordenact","");
	$ls_coddesde=$io_fun_activos->uf_obtenervalor_get("coddesde","");
	$ls_codhasta=$io_fun_activos->uf_obtenervalor_get("codhasta","");
	$ls_cmpmov_desde=$io_fun_activos->uf_obtenervalor_get("cmpmov_desde","");
	$ls_cmpmov_hasta=$io_fun_activos->uf_obtenervalor_get("cmpmov_hasta","");
	$ls_coduniadm=$io_fun_activos->uf_obtenervalor_get("coduniadm","");
	$ls_denuniadm=$io_fun_activos->uf_obtenervalor_get("denuniadm","");
	$ls_grupo=$io_fun_activos->uf_obtenervalor_get("codgru","");
	$ls_subgrupo=$io_fun_activos->uf_obtenervalor_get("codsubgru","");
	$ls_seccion=$io_fun_activos->uf_obtenervalor_get("codsec","");
	$ls_tipoformato=$io_fun_activos->uf_obtenervalor_get("tipoformato",0);
	$li_orden=$io_fun_activos->uf_obtenervalor_get("ordenact",0);
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
	$lb_valido=$io_report->uf_saf_load_relbiemuefal($ls_codemp,$ls_coduniadm,$ls_cmpmov_desde,$ls_cmpmov_hasta,$ld_desde,$ld_hasta,$li_orden); // Cargar el DS con los datos de la cabecera del reporte
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
		$ls_desc_event="Generó un reporte de Relacion de Bienes Muebles Faltantes. Desde el activo   ".$ls_coddesde." hasta   ".$ls_codhasta;
		$io_fun_activos->uf_load_seguridad_reporte("SAF","sigesp_saf_r_activo.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		$io_pdf->ezStartPageNumbers(940,50,10,'','',1); // Insertar el número de página
		uf_print_encabezado_pagina($ls_titulo,"",$ld_fecha,$io_pdf); // Imprimimos el encabezado de la página
		$li_totrow=$io_report->ds->getRowCount("cmpmov");
		$i=0;
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
	        $io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount; // Número de página
			$ls_cmpmov=$io_report->ds->data["cmpmov"][$li_i];
			$ls_coduniadm=$io_report->ds->data["coduniadm"][$li_i];
			$ld_feccmp=$io_report->ds->data["feccmp"][$li_i];
			$ld_feccmp=$io_funciones->uf_convertirfecmostrar($ld_feccmp);
			$lb_valido=$io_report->uf_saf_load_dt_relbiemuefal($ls_codemp,$ls_coduniadm,$ls_cmpmov,$ld_desde,$ld_hasta,$ls_coddesde,$ls_codhasta,$ls_grupo,$ls_subgrupo,$ls_seccion,$li_orden); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_det=$io_report->ds_detalle->getRowCount("ideact");
				$la_data="";
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_grupo= $io_report->ds_detalle->data["grupo"][$li_s];
					$ls_subgrupo= $io_report->ds_detalle->data["subgrupo"][$li_s];
					$ls_seccion= $io_report->ds_detalle->data["seccion"][$li_s];
					$ls_ideact= $io_report->ds_detalle->data["ideact"][$li_s];
					$ls_denact= $io_report->ds_detalle->data["denact"][$li_s];
					$ls_marca= $io_report->ds_detalle->data["marca"][$li_s];
					$ls_modelo= $io_report->ds_detalle->data["modelo"][$li_s];
					$ls_cantidad= $io_report->ds_detalle->data["cantidad"][$li_s];
					$ls_costo= $io_report->ds_detalle->data["costo"][$li_s];
					$ls_costo = $io_fun_activos->uf_formatonumerico($ls_costo);
					$la_data[$li_s]=array('codgru'=>$ls_grupo,'codsubgru'=>$ls_subgrupo,'codsec'=>$ls_seccion,'ideact'=>$ls_ideact,
										  'denact'=>$ls_denact,'maract'=>$ls_marca,'modact'=>$ls_modelo,
										  'cantidad'=>$ls_cantidad,'regcont'=>'','costo'=>$ls_costo,'cantdif'=>'','costot'=>'');
				}
				if($la_data!="")
				{
					$i=$i +1;
					uf_print_cabecera($ls_estemp,'',$ls_denuniadm,$ls_cmpmov,$ld_feccmp,$io_pdf); // Imprimimos la cabecera del registro
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					uf_print_pie_cabecera($io_pdf);
					uf_print_pie_de_pagina(&$io_pdf);
					if ($io_pdf->ezPageCount==$li_numpag)
					{// Hacemos el commit de los registros que se desean imprimir
						$io_pdf->transaction('commit');
					}
					else
					{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
						$io_pdf->transaction('rewind');
						$io_pdf->ezNewPage(); // Insertar una nueva página
						uf_print_cabecera($ls_estemp,'',$ls_denuniadm,$ls_cmpmov,$ld_feccmp,$io_pdf);  // Imprimimos la cabecera del registro
						uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
						uf_print_pie_cabecera($io_pdf);
						uf_print_pie_de_pagina(&$io_pdf);
					}
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