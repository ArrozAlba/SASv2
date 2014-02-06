<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: LIBRO DE COMPRA
//  ORGANISMO: IPSFA
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if (!array_key_exists("la_logusr",$_SESSION))
	{
		 print "<script language=JavaScript>";
		 print "close();";
		 print "</script>";		
	}
    ini_set('memory_limit','512M');
	ini_set('max_execution_time ','0');
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_periodo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Ing. Nelson Barraez
		// Modificado Por: Ing. Yozelin Barragan 
		// Fecha Creacion: 21/04/2006   Fecha de Modificado: 10/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Generó el Reporte Libro de Compra para el periodo ".$as_periodo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_librocompra.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$ls_mes,$ls_ano,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Ttulo del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funcin que imprime los encabezados por pgina
		//	   Creado Por: Ing.Ing. Nelson Barraez
		// Modificado Por: Ing. Yozelin Barragan 
		// Fecha Creacion: 21/04/2006   Fecha de Modificado: 10/01/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_denominacion="<b> en Bs.F.</b>";
		}
		else
		{
			$ls_denominacion="<b> en Bs.</b>";
		}
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$ls_periodo="<b>MES :</b>".$ls_mes." "."<b>AÑO:</b>".$ls_ano;	
		$io_pdf->line(10,30,1000,30);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],40,525,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		
		$io_pdf->addText(150,550,16,"<b><i>INSTITUTO DE PREVISION SOCIAL DE LA FUERZA ARMADA (IPSFA)</i></b>"); // Agregar el ttulo
		$io_pdf->addText(790,550,16,$as_titulo.$ls_denominacion); // Agregar el ttulo
		$io_pdf->addText(790,530,12,$ls_periodo); // Agregar el ttulo
		
		//$io_pdf->addText(150,550,16,"<b><i>TESORERO/AGENTE DE RETENCION</i></b>"); // Agregar el ttulo
		//$io_pdf->addText(150,550,16,"<b><i>JEFE UNIDAD DE TRIBUTO INTERNOS</i></b>"); // Agregar el ttulo
		//$io_pdf->addText(150,550,16,"<b><i>ELABORADO POR:</i></b>"); // Agregar el ttulo
		
		/*$li_tm=$io_pdf->getTextWidth(16,$as_titulo.$ls_denominacion);
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,550,16,$as_titulo.$ls_denominacion); // Agregar el ttulo
		
		$li_tm=$io_pdf->getTextWidth(14,$ls_periodo);
		$tm=510-($li_tm/2);
		$io_pdf->addText($tm,530,12,$ls_periodo); // Agregar el ttulo*/
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_titulo(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: funcin que imprime la cabecera de cada pgina
		//	   Creado Por: Ing. Nelson Barraez
		// Modificado Por: Ing. Yozelin Barragan 
		// Fecha Creacion: 21/04/2006   Fecha de Modificado: 10/01/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(500);
		$la_data   =array(array('name1'=>'<b>Compras Internas o Importaciones</b>'));
		$la_columna=array('name1'=>'');
		$la_config =array('showHeadings'=>0,     // Mostrar encabezados
						  'fontSize' => 6,       // Tamao de Letras
						  'titleFontSize' => 8, // Tamao de Letras de los ttulos
						  'showLines'=>1,        // Mostrar Lneas
						  'shaded'=>2,           // Sombra entre lneas
						  'xPos'=>880,
						  'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						  'xOrientation'=>'center', // Orientacin de la tabla
						  'width'=>150, // Ancho de la tabla
						  'maxWidth'=>150,
						  'cols'=>array('name1'=>array('justification'=>'center','width'=>150))); // Justificacin y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: funcin que imprime la cabecera de cada pgina
		//	   Creado Por: Ing. Nelson Barraez
		// Modificado Por: Ing. Yozelin Barragan 
		// Fecha Creacion: 21/04/2006   Fecha de Modificado: 10/01/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSetY(500);
		$la_data=array(array('name1'=>'<b>Fecha Factura</b>','name2'=>'<b>Nombre o Razon Social del Beneficiario</b>',
							 'name3'=>'<b>RIF y/o Nro. C.I.</b>','name4'=>'<b>Nro de Factura</b>',
							 'name5'=>'<b>Nro de Control</b>','name6'=>'<b>Compra s/derecho a credito F.</b>',
							 'name7'=>'<b>Base Imponible</b>','name8'=>'<b>Iva</b>',
							 'name9'=>'<b>Total de Compra Incluyendo con IVA</b>',
							 'name10'=>'<b>Nro Comprobante</b>','name11'=>'<b>IVA Retenido 75%</b>',
							 'name12'=>'<b>IVA Percibido 25%</b>','name13'=>'<b>% Aplicado</b>'));
		$la_columna=array('name1'=>'','name2'=>'','name3'=>'','name4'=>'','name5'=>'','name6'=>'','name7'=>'',
		                  'name8'=>'','name9'=>'','name10'=>'','name11'=>'','name12'=>'','name13'=>'');
		$la_config=array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 6,       // Tamao de Letras
						 'titleFontSize' => 6, // Tamao de Letras de los ttulos
						 'showLines'=>1,        // Mostrar Lneas
						 'shaded'=>0,           // Sombra entre lneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientacin de la tabla
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990,
						 'colGap'=>0.5,
						 'cols'=>array('name1'=>array('justification'=>'center','width'=>50), // Justificacin y ancho de la columna Nro de Operacion.
						 			   'name2'=>array('justification'=>'center','width'=>130), // Justificacin y ancho de la columna RIF.
						 			   'name3'=>array('justification'=>'center','width'=>50), // Justificacin y ancho de la columna Nmero de la Fcatura.
						 			   'name4'=>array('justification'=>'center','width'=>70), // Justificacin y ancho de la columna Nombre o Razn Social.
									   'name5'=>array('justification'=>'center','width'=>70), // Justificacin y ancho de la columna Nro de Comprobante.
						 			   'name6'=>array('justification'=>'center','width'=>90), // Justificacin y ancho de la columna Nro de Planilla de Importacin.
						 			   'name7'=>array('justification'=>'center','width'=>90), // Justificacin y ancho de la columna Nro Expediente de Importacin.
						 			   'name8'=>array('justification'=>'center','width'=>80), // Justificacin y ancho de la columna Nro de Factura.   
									   'name9'=>array('justification'=>'center','width'=>80), // Justificacin y ancho de la columna Nro de Control.
						 			   'name10'=>array('justification'=>'center','width'=>80), // Justificacin y ancho de la columna
						 			   'name11'=>array('justification'=>'center','width'=>80), // Justificacin y ancho de la columna
						 			   'name12'=>array('justification'=>'center','width'=>80), // Justificacin y ancho de la columna
									   'name13'=>array('justification'=>'center','width'=>30))); // Justificacin y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informacin
		//	   			   io_pdf // Objeto PDF
		//    Description: funcin que imprime el detalle
		//	   Creado Por: Ing. Nelson Barraez
		// Modificado Por: Ing. Yozelin Barragan 
		// Fecha Creacion: 21/04/2006   Fecha de Modificado: 10/01/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(483);
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 5, // Tamao de Letras
						 'titleFontSize' => 10,  // Tamao de Letras de los ttulos
						 'showLines'=>1, // Mostrar Lneas
						 'shaded'=>0, // Sombra entre lneas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Mximo de la tabla
						 'xOrientation'=>'center', // Orientacin de la tabla
 						 'colGap'=>0.5,
						 'cols'=>array('name1'=>array('justification'=>'center','width'=>50), // Justificacin y ancho de la columna Nro de Operacion.
						 			   'name2'=>array('justification'=>'left','width'=>130), // Justificacin y ancho de la columna RIF.
						 			   'name3'=>array('justification'=>'center','width'=>50), // Justificacin y ancho de la columna Nmero de la Fcatura.
						 			   'name4'=>array('justification'=>'center','width'=>70), // Justificacin y ancho de la columna Nombre o Razn Social.
									   'name5'=>array('justification'=>'center','width'=>70), // Justificacin y ancho de la columna Nro de Comprobante.
						 			   'name6'=>array('justification'=>'right','width'=>90), // Justificacin y ancho de la columna Nro de Planilla de Importacin.
						 			   'name7'=>array('justification'=>'right','width'=>90), // Justificacin y ancho de la columna Nro Expediente de Importacin.
						 			   'name8'=>array('justification'=>'right','width'=>80), // Justificacin y ancho de la columna Nro de Factura.   
									   'name9'=>array('justification'=>'right','width'=>80), // Justificacin y ancho de la columna Nro de Control.
						 			   'name10'=>array('justification'=>'center','width'=>80), // Justificacin y ancho de la columna
						 			   'name11'=>array('justification'=>'right','width'=>80), // Justificacin y ancho de la columna
						 			   'name12'=>array('justification'=>'right','width'=>80), // Justificacin y ancho de la columna
									   'name13'=>array('justification'=>'center','width'=>30))); // Justificacin y ancho de la columna
		$la_columna=array('name1'=>'','name2'=>'','name3'=>'','name4'=>'','name5'=>'','name6'=>'','name7'=>'',
		                  'name8'=>'','name9'=>'','name10'=>'','name11'=>'','name12'=>'','name13'=>'');
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function:  uf_print_totales
		//		   Access:  private 
		//	    Arguments:  la_data // arreglo de informacion
		//	    		    io_pdf // Instancia de objeto pdf
		//    Description:  funcin que imprime el fin de la cabecera de cada pgina
		//	   Creado Por:  Ing. Nelson Barraez
		// Modificado Por:  Ing. Yozelin Barragan 
		// Fecha Creacion:  21/04/2006    Fecha de Modificado: 10/01/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 5, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los ttulos
						 'showLines'=>1, // Mostrar Lneas
						 'shaded'=>0, // Sombra entre lneas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Mximo de la tabla
						 'xPos'=>689,
						 'xOrientation'=>'center', // Orientacin de la tabla
 						 'colGap'=>0.5,
						 'cols'=>array('name1'=>array('justification'=>'right','width'=>90), // Justificacin y ancho de la columna Nro de Planilla de Importacin.
						 			   'name2'=>array('justification'=>'right','width'=>90), // Justificacin y ancho de la columna Nro Expediente de Importacin.
						 			   'name3'=>array('justification'=>'right','width'=>80), // Justificacin y ancho de la columna Nro de Factura.   
									   'name4'=>array('justification'=>'right','width'=>80), // Justificacin y ancho de la columna Nro de Control.
						 			   'name5'=>array('justification'=>'center','width'=>80), // Justificacin y ancho de la columna
						 			   'name6'=>array('justification'=>'right','width'=>80), // Justificacin y ancho de la columna
						 			   'name7'=>array('justification'=>'right','width'=>80), // Justificacin y ancho de la columna
									   'name8'=>array('justification'=>'center','width'=>30))); // Justificacin y ancho de la columna
		$la_columnas=array('name1'=>'',
		                   'name2'=>'',
						   'name3'=>'',
						   'name4'=>'',
						   'name5'=>'',
						   'name6'=>'',
						   'name7'=>'',
						   'name8'=>'');
	  $io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_table_totales
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_table_default($ld_monto,$ad_totbasimp,$ad_totimpuestos,$ld_sumcom,$ad_totcomsiniva,$ad_totimpred,$ad_totbasimp8,$ad_basimpga,$ad_totgenadi,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function:  uf_print_pie_cabecera
		//		   Access:  private 
		//	    Arguments:  ai_totprenom // Total Prenmina
		//	   			    ai_totant // Total Anterior
		//	    		    io_pdf // Instancia de objeto pdf
		//    Description:  funcin que imprime el fin de la cabecera de cada pgina
		//	   Creado Por:  Ing. Nelson Barraez
		// Modificado Por:  Ing. Yozelin Barragan 
		// Fecha Creacion:  21/04/2006    Fecha de Modificado: 10/01/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ld_monto   = number_format($ld_monto,2,',','.');
		$la_data[0] = array('name1'=>'','name2'=>'','name3'=>'<b>Base Imponible</b>','name4'=>'','name5'=>'<b>Credito Fiscal</b>','name6'=>'<b>Iva Retenido a Terceros</b>','name7'=>'<b>Anticipo IVA</b>');
		$la_data[1] = array('name1'=>'Total: Compras Exentas y/o sin derecho a credito fiscal','name2'=>'30','name3'=>$ad_totcomsiniva,'name4'=>'','name5'=>'','name6'=>'','name7'=>'');
	    $la_data[2] = array('name1'=>'E de las: Compras Importacion Afectadas Alicuota General','name2'=>'31','name3'=>'','name4'=>'32','name5'=>'','name6'=>'','name7'=>'');
	    $la_data[3] = array('name1'=>'E de las: Compras Importacion Afectadas en Alicuota General + Adicional','name2'=>'312','name3'=>'','name4'=>'322','name5'=>'','name6'=>'','name7'=>'');
	    $la_data[4] = array('name1'=>'E de las: Compras Importacion Afectadas en Alicuota Reducida','name2'=>'313','name3'=>'','name4'=>'323','name5'=>'','name6'=>'','name7'=>'');
	    $la_data[5] = array('name1'=>'E de las: Compras Internas Afectadas solo en Alicuota General','name2'=>'33','name3'=>$ad_totbasimp,'name4'=>'34','name5'=>$ad_totimpuestos,'name6'=>'','name7'=>'');
	    $la_data[6] = array('name1'=>'E de las: Compras Internas Afectadas en Alicuota General + Adicional','name2'=>'332','name3'=>$ad_basimpga,'name4'=>'342','name5'=>$ad_totgenadi,'name6'=>'','name7'=>'');
	    $la_data[7] = array('name1'=>'E de las: Compras Internas Afectadas en Alicuota Reducida','name2'=>'333','name3'=>$ad_totbasimp8,'name4'=>'343','name5'=>$ad_totimpred,'name6'=>'','name7'=>'');
	    $la_data[8] = array('name1'=>'','name2'=>'35','name3'=>'','name4'=>'36','name5'=>'','name6'=>'','name7'=>'');
		$la_columna = array('name1'=>'','name2'=>'','name3'=>'','name4'=>'','name5'=>'','name6'=>'','name7'=>'');
		$la_config  = array('showHeadings'=>0, // Mostrar encabezados
						    'fontSize' => 8, // Tamao de Letras
						   'titleFontSize' => 10,  // Tamao de Letras de los ttulos
						   'showLines'=>1, // Mostrar Lneas
						   'shaded'=>0, // Sombra entre lneas
						   'width'=>970, // Ancho de la tabla
						   'maxWidth'=>970, // Ancho Mximo de la tabla
						   'xOrientation'=>'center', // Orientacin de la tabla
						   'cols'=>array('name0'=>array('justification'=>'center','width'=>300,'showLines'=>1),
						                 'name1'=>array('justification'=>'left','width'=>300), // Justificacin y ancho de la columna Nro de Operacion.
						 			     'name2'=>array('justification'=>'center','width'=>25), // Justificacin y ancho de la columna RIF.
						 			     'name3'=>array('justification'=>'right','width'=>100), // Justificacin y ancho de la columna Nmero de la Fcatura.
						 			     'name4'=>array('justification'=>'center','width'=>25), // Justificacin y ancho de la columna Nombre o Razn Social.
									     'name5'=>array('justification'=>'right','width'=>110), // Justificacin y ancho de la columna Nro de Comprobante.
						 			     'name6'=>array('justification'=>'right','width'=>110), // Justificacin y ancho de la columna Nro de Planilla de Importacin.
						 			     'name7'=>array('justification'=>'center','width'=>110),
									     'name8'=>array('justification'=>'center','width'=>60))); // Justificacin y ancho de la columna Nro Expediente de Importacin.
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_table_default
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_table_totales($ldec_totcomsiniva,$ldec_totimp8,$ldec_totimp9,$ldec_totimp25,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function:  uf_print_table_totales
		//		   Access:  private 
		//	    Arguments:  ai_totprenom // Total Prenmina
		//	   			    ai_totant // Total Anterior
		//	    		    io_pdf // Instancia de objeto pdf
		//    Description:  funcin que imprime el fin de la cabecera de cada pgina
		//	   Creado Por:  Ing. Nelson Barraez
		// Modificado Por:  Ing. Yozelin Barragan 
		// Fecha Creacion:  21/04/2006    Fecha de Modificado: 10/01/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[0] = array('name1'=>'Compras Exentas:','name2'=>$ldec_totcomsiniva);
		$la_data[1] = array('name1'=>'Total del 8% de IVA Retenido:','name2'=>$ldec_totimp8);
	    $la_data[2] = array('name1'=>'Total del 11% de IVA Retenido','name2'=>$ldec_totimp9);
	    $la_data[3] = array('name1'=>'Total del 25% de IVA Retenido','name2'=>$ldec_totimp25);
		$la_columna = array('name1'=>'','name2'=>'');
		$la_config  = array('showHeadings'=>0, // Mostrar encabezados
						    'fontSize' => 8, // Tamao de Letras
						   'titleFontSize' => 10,  // Tamao de Letras de los ttulos
						   'showLines'=>2, // Mostrar Lneas
						   'shaded'=>0, // Sombra entre lneas
						   'width'=>970, // Ancho de la tabla
						   'maxWidth'=>970, // Ancho Mximo de la tabla
						   'xPos'=>'center', // Orientacin de la tabla
						   'cols'=>array('name1'=>array('justification'=>'left','width'=>200), // Justificacin y ancho de la columna Nro de Operacion.
						 			     'name2'=>array('justification'=>'right','width'=>150))); // Justificacin y ancho de la columna Nro Expediente de Importacin.
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_table_totales
	//--------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_cxp_class_report.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/class_fecha.php");
    $io_fecha = new class_fecha();
	$io_in    = new sigesp_include();
	$con      = $io_in->uf_conectar();
    $io_sql   = new class_sql($con);
	$io_report= new sigesp_cxp_class_report("../../");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();		
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();		
	//----------------------------------------------------  Parmetros del encabezado  -----------------------------------------------
	$ls_mes=$io_fun_cxp->uf_obtenervalor_get("mes","");
	$ls_agno=$io_fun_cxp->uf_obtenervalor_get("agno","");
	$ls_tiporeporte=$io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_cxp_class_reportbsf.php");
		$io_report=new sigesp_cxp_class_reportbsf();
	}
	$ls_titulo     = "<b>Libro de Compras</b>";
	$li_lastday    = $io_fecha->uf_last_day($ls_mes,$ls_agno);
	$li_lastday    = substr($li_lastday,0,2);
	$as_fechadesde = $ls_agno.'-'.$ls_mes.'-01';
	$as_fechahasta = $ls_agno.'-'.$ls_mes.'-'.$li_lastday;
	$ls_mesletras        = $io_fecha->uf_load_nombre_mes($ls_mes);
	$ls_periodo    = "MES: ".$ls_mesletras."    AÑO".$ls_agno."";
	//--------------------------------------------------------------------------------------------------------------------------------
	$ld_monto    = 0;
	$ld_impuesto = 0;
	$ld_sumcom   = 0;
	$ld_baseimp  = 0;
	$arremp      = $_SESSION["la_empresa"];
    $ls_codemp   = $arremp["codemp"];
	error_reporting(E_ALL);
	set_time_limit(1800);
	$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
	$io_pdf->ezSetCmMargins(4.6,3,2.5,2.5); // Configuracin de los margenes en centmetros
    //uf_print_titulo(&$io_pdf);
	$io_pdf->ezStartPageNumbers(970,40,10,'','',1); // Insertar el nmero de pgina
	$lb_valido=$io_report->uf_select_report_libcompra($as_fechadesde,$as_fechahasta,&$rs_resultado);
	uf_print_encabezado_pagina($ls_titulo,$ls_mesletras,$ls_agno,&$io_pdf);
	uf_print_cabecera(&$io_pdf);
	$ldec_totimp8      = 0; //Variable de tipo acumulador que almacenara el monto total de los impuestos a 8%.
	$ldec_totimp9      = 0; //Variable de tipo acumulador que almacenara el monto total de los impuestos a 14%.
	$ldec_totimp25     = 0; //Variable de tipo acumulador que almacenara el monto total de los impuestos a 25%.
	$ldec_totimpret8   = 0; //Variable de tipo acumulador que almacenara el monto total retenido de los impuestos a 8%.
	$ldec_totimpret9   = 0; //Variable de tipo acumulador que almacenara el monto total retenido de los impuestos a 14%.
	$ldec_totimpret25  = 0; //Variable de tipo acumulador que almacenara el monto total retenido de los impuestos a 25%.
	$ldec_totbasimp8   = 0;
	$ldec_totbasimp9   = 0;
	$ldec_totbasimp25  = 0;
	$ldec_totcomsiniva = 0;
	
	$ld_total_sinderiva=0;
	$ld_total_baseimp=0;
	$ld_total_monimp=0;
	$ld_total_montodoc=0;
	$ld_total_montoret=0;
	$ld_total_montoretmen=0;
	if($lb_valido)
	{
		$li=0;
		while($row=$io_report->io_sql->fetch_row($rs_resultado))	
		{
			$li++;
		    $ldec_monret=0;
			$ls_numrecdoc=trim($row["numrecdoc"]);
			$ls_tipproben=$row["tipproben"];
			$ls_codpro=trim($row["cod_pro"]);
			$ls_cedben=trim($row["ced_bene"]);
			$ldec_montoret=$row["monret"];
			$ldec_montodoc=$row["montotdoc"];
			$ldec_mondeddoc=$row["mondeddoc"];
			$ls_codtipdoc =$row["codtipdoc"];
			if($ls_tipproben=='P')
			{
				$la_provben=$io_report->uf_select_rowdata($io_sql,"SELECT * FROM rpc_proveedor WHERE cod_pro='".$ls_codpro."'");
				$ls_rif=$la_provben["rifpro"];
				$ls_nombre=$la_provben["nompro"];
			}	
			else
			{
				$la_provben=$io_report->uf_select_rowdata($io_sql,"SELECT * FROM rpc_beneficiario WHERE ced_bene='".$ls_cedben."'");
				$ls_rif=$la_provben["rifben"];
				$ls_nombre=$la_provben["nombene"]." ".$la_provben["apebene"];
			}
			$la_notas=$io_report->uf_select_rowdata($io_sql,"SELECT * FROM cxp_sol_dc WHERE numrecdoc='".$ls_numrecdoc."' AND codtipdoc='".$ls_codtipdoc."' AND cod_pro='".$ls_codpro."' AND ced_bene='".$ls_cedben."'");
			if(count($la_notas)>0)
			{
				$ls_codope=$la_notas["codope"];
				$ls_numnota=$la_notas["numdc"];
				if($ls_codope=='NC')
				{
					$ls_numnc=$ls_numnota;
					$ls_numnd="";
				}
				else
				{
					$ls_numnd=$ls_numnota;
					$ls_numnc="";
				}
			}
			else
			{
					$ls_numnc="";
					$ls_numnd="";
			}
			$ls_cheque=$io_report->uf_select_data($io_sql,"SELECT distinct cxp_sol_banco.numdoc AS numdoc".
														  "  FROM cxp_dt_solicitudes, cxp_sol_banco".
														  " WHERE cxp_dt_solicitudes.codemp='".$ls_codemp."'".
														  "   AND cxp_dt_solicitudes.numrecdoc='".$ls_numrecdoc."'".
														  "   AND cxp_dt_solicitudes.cod_pro='".$ls_codpro."'".
														  "   AND cxp_dt_solicitudes.ced_bene='".$ls_cedben."'".
														  "   AND cxp_dt_solicitudes.codemp=cxp_sol_banco.codemp".
														  "   AND cxp_dt_solicitudes.numsol=cxp_sol_banco.numsol","numdoc");
	if($ls_tiporeporte==1)
	{
			$la_cmpret=$io_report->uf_select_rowdata($io_sql,"SELECT DISTINCT max(a.numrecdoc) as numrecdoc,max(a.monobjretaux) as monobjret,SUM(a.monretaux) as monret,max(a.porded) as porded,max(b.codret) as codret,max(b.numcom) as numcom,max(b.iva_retaux) as iva_ret,max(tiptrans) as tiptrans".
															  "   FROM cxp_rd_deducciones a,scb_dt_cmp_ret b                                                                         ".
															  "  WHERE a.codemp=b.codemp AND a.numrecdoc=b.numfac AND a.codemp='".$ls_codemp."' AND a.numrecdoc='".$ls_numrecdoc."' AND ".
															  "        a.cod_pro='".$ls_codpro."' AND a.ced_bene='".$ls_cedben."'                                              ".
															  " GROUP BY a.numrecdoc ");
	}
	else
	{
			$la_cmpret=$io_report->uf_select_rowdata($io_sql,"SELECT DISTINCT max(a.numrecdoc) as numrecdoc,max(a.monobjret) as monobjret,SUM(a.monret) as monret,max(a.porded) as porded,max(b.codret) as codret,max(b.numcom) as numcom,max(b.iva_ret) as iva_ret,max(tiptrans) as tiptrans".
															  "   FROM cxp_rd_deducciones a,scb_dt_cmp_ret b                                                                         ".
															  "  WHERE a.codemp=b.codemp AND a.numrecdoc=b.numfac AND a.codemp='".$ls_codemp."' AND a.numrecdoc='".$ls_numrecdoc."' AND ".
															  "        a.cod_pro='".$ls_codpro."' AND a.ced_bene='".$ls_cedben."'                                              ".
															  " GROUP BY a.numrecdoc ");
	}
			if(count($la_cmpret)>0)
			{
				$ls_codret = $la_cmpret["codret"];
				if ($ls_codret=='0000000001')
				{
					 $ldec_monret    = $la_cmpret["monret"];  
					 $ls_cmpret      = $la_cmpret["numcom"];
					 $ldec_monobjret = $la_cmpret["monobjret"];
					 $ldec_porded    = $la_cmpret["porded"];
					 $ldec_ivaret    = $la_cmpret["iva_ret"];
					 $ls_tiptrans    = $la_cmpret["tiptrans"];
				}
				else
				{
					 $ldec_monret    = 0; 
					 $ls_cmpret      = '';
					 $ldec_monobjret = 0;
					 $ldec_porded    = 0;
					 $ldec_ivaret    = 0;
 					 $ls_tiptrans    = "";
				}													  
			}
		    else
			{
				$ldec_monret    = $ldec_monret+$ldec_montoret;  
				$ls_cmpret      = '';
				$ldec_monobjret = 0;
				$ldec_porded    = 0;
				$ls_tiptrans    = "";
				$ldec_ivaret    = 0;
			}	
			if($ls_tiporeporte==1)
			{
				$la_cargos=$io_report->uf_select_rowdata($io_sql,"SELECT monobjretaux as basimp,porcar,monretaux as impiva".
																 "  FROM cxp_rd_cargos ".
																 " WHERE codemp='".$ls_codemp."'".
																 "   AND numrecdoc='".$ls_numrecdoc."'".
																 "   AND cod_pro='".$ls_codpro."'".
																 "   AND ced_bene='".$ls_cedben."'");
			}
			else
			{
				$la_cargos=$io_report->uf_select_rowdata($io_sql,"SELECT monobjret as basimp,porcar,monret as impiva".
																 "  FROM cxp_rd_cargos ".
																 " WHERE codemp='".$ls_codemp."'".
																 "   AND numrecdoc='".$ls_numrecdoc."'".
																 "   AND cod_pro='".$ls_codpro."'".
																 "   AND ced_bene='".$ls_cedben."'");
			}
			if(count($la_cargos)>0)
			{
				$ldec_porcar=$la_cargos["porcar"];
				$ldec_baseimp=$la_cargos["basimp"];
				$ldec_monimp=$la_cargos["impiva"];
			}
			else
			{
				$ldec_porcar="";
				$ldec_baseimp=0;
				$ldec_monimp=0;				
			}	
				
			$ldec_montodoc  = $ldec_montodoc+$ldec_mondeddoc;			 
			$ldec_sinderiva = $ldec_montodoc-$ldec_baseimp-$ldec_monimp;// Total de la Compra sin Derecho a Crédito Iva.
			
		    $ls_fecemidoc=$io_report->io_funciones->uf_convertirfecmostrar($row["fecemidoc"]);
			$ls_numref=$row["numref"];
			
			$ld_total_sinderiva=$ld_total_sinderiva+$ldec_sinderiva;
			$ld_total_baseimp=$ld_total_baseimp+$ldec_baseimp;
			$ld_total_monimp=$ld_total_monimp+$ldec_monimp;
			$ld_total_montodoc=$ld_total_montodoc+$ldec_montodoc;
			$ld_total_montoret=$ld_total_montoret+$ldec_montoret;
			$ld_montoretmen=($ldec_montoret*0.25);
			$ld_total_montoretmen=$ld_total_montoretmen+$ld_montoretmen;
			
			$ld_sinderiva=number_format($ldec_sinderiva,2,",",".");
			$ld_baseimp=number_format($ldec_baseimp,2,",",".");
			$ld_monimp=number_format($ldec_monimp,2,",",".");
			$ld_montodoc=number_format($ldec_montodoc,2,",",".");
			$ld_montoret=number_format($ldec_montoret,2,",",".");
			$ld_montoretmen=number_format($ld_montoretmen,2,",",".");
			
			$la_data[$li] = array('name1'=>$ls_fecemidoc,'name2'=>$ls_nombre,'name3'=>$ls_rif,'name4'=>$ls_numrecdoc,
                                  'name5'=>$ls_numref,'name6'=>$ld_sinderiva,'name7'=>$ld_baseimp,'name8'=>$ld_monimp,
								  'name9'=>$ld_montodoc,'name10'=>$ls_cmpret,'name11'=>$ld_montoret,'name12'=>$ld_montoretmen,
							      'name13'=>$ldec_porcar);
								
			 $li_porcentaje = intval($ldec_porcar);
			 if ($ldec_porcar>0)
			 {
				switch ($li_porcentaje){
				  case '8':
					$ldec_totimp8    = ($ldec_totimp8+$ldec_monimp);
					$ldec_totbasimp8 = ($ldec_totbasimp8+$ldec_baseimp);
					$ldec_totimpret8 = ($ldec_totimpret8+$ldec_montoret);
					break;
				  case '9'||'14'||'11':
					$ldec_totimp9    = ($ldec_totimp9+$ldec_monimp);  
					$ldec_totbasimp9 = ($ldec_totbasimp9+$ldec_baseimp);
					$ldec_totimpret9 = ($ldec_totimpret9+$ldec_montoret);
					break;
				  case '25':
					$ldec_totimp25    = ($ldec_totimp25+$ldec_monimp);  
					$ldec_totbasimp25 = ($ldec_totbasimp28+$ldec_baseimp);
					$ldec_totimpret25 = ($ldec_totimpret25+$ldec_montoret);
					break;
				  }
			 }	
			 /*$ldec_totcomsiniva = ($ldec_totcomsiniva+$ldec_sinderiva);	
			 $ldec_basimpga     = ($ldec_totbasimp9+$ldec_totbasimp25);//Total Base Imponible Compras Internas Afectadas en Alicuota General + Adicional(9% y 25%).
			 $ldec_totgenadi    = ($ldec_totimp9+$ldec_totimp25);//Total Compras Internas Afectadas en Alicuota Reducida. Impuestos al 9% y 25%.*/

		}
		$ld_total_sinderiva=number_format($ld_total_sinderiva,2,",",".");
		$ld_total_baseimp=number_format($ld_total_baseimp,2,",",".");
		$ld_total_monimp=number_format($ld_total_monimp,2,",",".");
		$ld_total_montodoc=number_format($ld_total_montodoc,2,",",".");
		$ld_total_montoret=number_format($ld_total_montoret,2,",",".");
		$ld_total_montoretmen=number_format($ld_total_montoretmen,2,",",".");
		
		$la_data_total[1] = array('name1'=>$ld_total_sinderiva,'name2'=>$ld_total_baseimp,'name3'=>$ld_total_monimp,
		                          'name4'=>$ld_total_montodoc,'name5'=>'','name6'=>$ld_total_montoret,
								  'name7'=>$ld_total_montoretmen,'name8'=>'');
		uf_print_detalle($la_data,&$io_pdf);		
        uf_print_totales($la_data_total,&$io_pdf);
		//uf_print_table_default(0,number_format($ldec_totbasimp9,2,",","."),number_format($ldec_totimp9,2,",","."),0,number_format($ldec_totcomsiniva,2,",","."),number_format($ldec_totimp8,2,",","."),number_format($ldec_totbasimp8,2,",","."),number_format($ldec_basimpga,2,",","."),number_format($ldec_totgenadi,2,",","."),&$io_pdf);
		//uf_print_table_totales(number_format($ldec_totcomsiniva,2,",","."),number_format($ldec_totimpret8,2,",","."),number_format($ldec_totimpret9,2,",","."),number_format($ldec_totimpret25,2,",","."),&$io_pdf);
		uf_insert_seguridad($ls_periodo);
	}
	else
	{
		print("<script language=JavaScript>");
		print("alert('No hay Registros para Mostrar');"); 
		print("close();");
		print("</script>");	
	}
	$io_pdf->stream();
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?>