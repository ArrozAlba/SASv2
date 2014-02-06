<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//          FECHA DE MODIFICACION : 30/11/2007
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
	function uf_print_cabecera($as_codemp,$as_nomemp,$as_depen,$as_distrito,$as_direccion,$as_servicio,&$io_pdf)
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
		//	    		   ad_fecmpact // fecha de compra del activo
		//	    		   ai_costo    // costo del activo
		//	    		   io_pdf      // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
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
		$la_data=array(array('name'=>'<b>Organismo:</b>  '.$as_codemp." - ".$as_nomemp.''),
		               array ('name'=>'<b>Servicio:</b>  '.$as_servicio.''),
					   array ('name'=>'<b>Unidad Administrativa:</b>  '.$as_depen.''),
					   array ('name'=>'<b>Distrito:</b>  '.$as_distrito.''),
					   array ('name'=>'<b>Dirección o Lugar:</b>  '.$as_direccion.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'lineCol'=>array(0.9,0.9,0.9), // Mostrar Líneas
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>900); // Ancho Máximo de la tabla
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
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_columna=array('codgru'=>'<b>Grupo</b>',
		                  'codsubgru'=>'<b>Subgrupo</b>',
						  'codsec'=>'<b>Sección</b>',
						  'cantidad'=>'<b>Cantidad</b>',
						  'ideact'=>'<b>Identificación</b>',
		                  'denact'=>'<b>Denominación</b>',					  
						  'costo1'=>'<b>Valor Unitario</b>',
						  'costo'=>'<b>Valor Total</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>900, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codgru'=>array('justification'=>'center','width'=>40), // Justificación y ancho de la columna	
						               'codsubgru'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna	
									   'codsec'=>array('justification'=>'center','width'=>40), // Justificación y ancho de la columna	
									   'cantidad'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna	
									   'ideact'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna	
									   'denact'=>array('justification'=>'left','width'=>480), // Justificación y ancho de la columna						 			  
						 			   'costo1'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'costo'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
									   
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ai_montot,&$io_pdf)
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
	
	function uf_print_firmas(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_firmas
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime las firmas
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 06/12/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-170);
		$la_data[1]=array('name1'=>'JEFE DE LA UNIDAD DE TRABAJO','name2'=>'REPONSABLE DE BIENES DE LA UNIDAD DE TRABAJO','name3'=>'UNIDAD DE TRABAJO');
		$la_data[2]=array('name1'=>'','name2'=>'','name3'=>'');
		$la_data[3]=array('name1'=>'Nombre y Apellido:___________________________________','name2'=>'Nombre y Apellido:___________________________________','name3'=>'Nombre y Apellido:___________________________________');
		$la_data[4]=array('name1'=>'Firma:                    ___________________________________','name2'=>'Firma:                    ___________________________________','name3'=>'Firma:                    ___________________________________');
		$la_data[5]=array('name1'=>'C.I:                         ___________________________________','name2'=>'C.I:                         ___________________________________','name3'=>'C.I:                         ___________________________________');
		$la_columna=array('name1'=>'',
						  'name2'=>'',
						  'name3'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>930, // Ancho Mínimo de la tabla
						 'xPos'=>500, // Orientación de la tabla
						 'cols'=>array('name1'=>array('justification'=>'left','width'=>300), // Justificacion y ancho de la columna
  						 			   'name2'=>array('justification'=>'left','width'=>300),
									   'name3'=>array('justification'=>'left','width'=>300))); 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_firmas		//--------------------------------------------------------------------------------------------------------------------------------

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
	$ls_titulo="<b>INVENTARIO DE BIENES MUEBLES</b>";
	if(($ld_desde!="")&&($ld_hasta!=""))
	{
		$ld_fecha="Desde:".$ld_desde."  Hasta:".$ld_hasta."";
	}
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_nomemp=$arre["nombre"];
	$ls_distrito=$arre["estemp"];
	$ls_direccion=$arre["direccion"];
	$ls_coddesde=$_GET["coddesde"];
	$ls_codhasta=$_GET["codhasta"];
	$ls_ordenact=$_GET["ordenact"];
	$ls_status=$_GET["status"];
	$ls_coduniadm=$_GET["coduni"]; 
	$ls_grupo=$_GET["grupo"];
	$ls_subgrupo=$_GET["subgrupo"];
	$ls_seccion=$_GET["seccion"];
	$ls_tipoformato=$io_fun_activos->uf_obtenervalor_get("tipoformato",0);
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
	$lb_valido=$io_report->uf_select_inventario_unidad($ls_coduniadm,$ld_desde,$ld_hasta,$ls_status,$ls_ordenact,$ls_coddesde,$ls_codhasta,$ls_grupo,$ls_subgrupo,$ls_seccion); // Cargar el DS con los datos de la cabecera del reporte
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
		$ls_desc_event="Generó un reporte de Activo. Desde el activo   ".$ls_coddesde." hasta   ".$ls_codhasta;
		$io_fun_activos->uf_load_seguridad_reporte("SAF","sigesp_saf_r_activo_bien.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		$io_pdf->ezStartPageNumbers(940,50,10,'','',1); // Insertar el número de página
		uf_print_encabezado_pagina($ls_titulo,"",$ld_fecha,$io_pdf); // Imprimimos el encabezado de la página	
			$li_numpag=$io_pdf->ezPageCount; // Número de página	
					
			if($lb_valido)
			{			
				$li_totrow_det=$io_report->ds->getRowCount("codact");
				$la_data="";
				$i=0;
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{  				
					$ls_codact=    $io_report->ds->data["codact"][$li_s];
					$ls_codgru=    $io_report->ds->data["codgru"][$li_s];
					$ls_codsubgru=    $io_report->ds->data["codsubgru"][$li_s];
					$ls_codsec=    $io_report->ds->data["codsec"][$li_s];
					$ls_seract=    $io_report->ds->data["seract"][$li_s];
					$ls_denact=    $io_report->ds->data["denact"][$li_s];
					$ls_maract=    $io_report->ds->data["maract"][$li_s];
			        $ls_modact=    $io_report->ds->data["modact"][$li_s];					
					$ls_denuniadm= $io_report->ds->data["denuniadm"][$li_s];									
					$ls_estact=    $io_report->ds->data["estact"][$li_s];					
					$li_costo=$io_report->ds->data["costo"][$li_s];
			        $li_costo=$io_fun_activos->uf_formatonumerico($li_costo);
					$ls_ideact=	    $io_report->ds->data["ideact"][$li_s];
					$ls_cantidad=	    $io_report->ds->data["cantidad"][$li_s];	
					$ls_servicio=	    $io_report->ds->data["denuniadm"][$li_s];						
					if($ls_estact=="R"){$ls_estact="Reasignado";}
					if($ls_estact=="I"){$ls_estact="Incorporado";}					
					$la_data[$li_s]=array('codact'=>$ls_codact,'codgru'=>$ls_codgru,'codsubgru'=>$ls_codsubgru,'codsec'=>$ls_codsec,'denact'=>$ls_denact,'seract'=>$ls_seract,'denuniadm'=>$ls_denuniadm,'estact'=>$ls_estact,'maract'=>$ls_maract,'modact'=>$ls_modact,'costo1'=>$li_costo,'costo'=>$li_costo,'ideact'=>$ls_ideact,'cantidad'=>$ls_cantidad,'servicio'=>$ls_servicio);
				}
				if($la_data!="")
				{
					$i=$i +1;
					uf_print_cabecera($ls_codemp,$ls_nomemp,$ls_denuniadm,$ls_distrito,$ls_direccion,$ls_servicio,$io_pdf); // Imprimimos la cabecera del registro
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					if ($io_pdf->ezPageCount==$li_numpag)
					{// Hacemos el commit de los registros que se desean imprimir
					    uf_print_firmas($io_pdf);
						$io_pdf->transaction('commit');
					}
					else
					{// Hacemos un rollback de los registros y volvemos a imprimir
					    $io_pdf->transaction('rewind');
					}
				}
			 uf_print_firmas($io_pdf);				
			unset($la_data);			
		}
		if(($lb_valido)&&($i>0))
		{
			$io_pdf->ezStopPageNumbers(1,1);
			$io_pdf->ezStream();
		}
		else
		{
		   	print "numero de filas ".$li_totrow_det."<br>";
		   	print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar, entro por aqui tambien');"); 
			print(" close();");
			print("</script>");
		}		
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
?> 