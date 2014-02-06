<?PHP
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

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo." Forma 0711";
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_instructivo_07_cargos.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$rango,&$io_pdf)
	{		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo // Período
		//	    		   as_rango // Rango de Meses
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		//     Modificado Por: Ing. Jennifer Rivero
		// Fecha Creación: 27/06/2006 
		// Fecha de modificaciòn: 06/06/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		
		$io_pdf->addText(50,730,7,"<b>CÓDIGO PRESUPUESTARIO DEL ENTE: </b>");		
		$io_pdf->addText(50,720,7,"<b>DENOMINACIÓN DEL ENTE:  ".$_SESSION["la_empresa"]["nombre"]."</b>");
		$io_pdf->addText(50,710,7,"<b>ORGANO DE ADSCRIPCIÓN:  ".$_SESSION["la_empresa"]["nomorgads"]."</b>");
		$io_pdf->addText(50,700,7,"<b>PERIODO PRESUPUESTARIO:</b> ".substr($_SESSION["la_empresa"]["periodo"],0,4));
		$li_tm=$io_pdf->getTextWidth(8,"RECURSOS HUMANOS CLASIFICADOS POR TIPO DE CARGOS");		
		$tm=280-($li_tm/2);
		$io_pdf->addText($tm,680,10,"<b>RECURSOS HUMANOS CLASIFICADOS POR TIPO DE CARGOS</b>"); // Agregar el título
		$io_pdf->Rectangle(50,570,510,100);
		$io_pdf->line(77,570,77,670);//linea vertical
		$io_pdf->addText(52,580,7,"<b>CÓD.</b>");
		$io_pdf->line(200,570,200,670);//linea vertical
		$io_pdf->addText(110,580,7,"<b>TIPO DE CARGO</b>");	
		$io_pdf->line(200,652,560,652);//Horizontal	
		$io_pdf->line(200,620,560,620);//Horizontal	
		$io_pdf->line(225,570,225,620);//linea vertical
		$io_pdf->line(250,570,250,620);//linea vertical
		$io_pdf->line(275,570,275,620);//linea vertical
		$io_pdf->line(310,570,310,620);//linea vertical
		$io_pdf->line(350,570,350,670);//linea vertical		
		$io_pdf->line(375,570,375,620);//linea vertical
		$io_pdf->line(400,570,400,620);//linea vertical
		$io_pdf->line(425,570,425,620);//linea vertical
		$io_pdf->line(460,570,460,620);//linea vertical
		$io_pdf->line(500,570,500,670);//linea vertical
		$io_pdf->addText(225,657,6,"<b>PRESUPUESTO APROBADO</b>");	
		$io_pdf->addText(370,657,6,"<b>EJECUTADO EN EL TRIMESTRE NRO. ".$rango."</b>");	
		$io_pdf->addText(250,630,7,"<b>NRO. DE CARGOS</b>");
		$io_pdf->addText(400,630,7,"<b>NRO. DE CARGOS</b>");
		$io_pdf->addText(503,638,5,"<b>ACUMULADO AL</b>");
		$io_pdf->addText(503,631,5,"<b>TRIMESTRE NRO. ".$rango."</b>");
		$io_pdf->addText(210,580,7,"<b>F</b>");	
		$io_pdf->addText(235,580,7,"<b>M</b>");	
		$io_pdf->addText(260,580,7,"<b>V</b>");	
		$io_pdf->addText(282,600,6,"<b>TOTAL</b>");
		$io_pdf->addText(280,590,6,"<b>NRO. DE</b>");
		$io_pdf->addText(280,580,6,"<b>CARGOS</b>");
		$io_pdf->addText(318,590,6,"<b>MONTO</b>");
		$io_pdf->addText(312,580,6,"<b>ASIGNADO</b>");
		$io_pdf->addText(360,580,7,"<b>F</b>");	
		$io_pdf->addText(385,580,7,"<b>M</b>");	
		$io_pdf->addText(410,580,7,"<b>V</b>");	
		$io_pdf->addText(430,600,6,"<b>TOTAL</b>");
		$io_pdf->addText(428,590,6,"<b>NRO. DE</b>");
		$io_pdf->addText(428,580,6,"<b>CARGOS</b>");
		$io_pdf->addText(470,590,6,"<b>MONTO</b>");
		$io_pdf->addText(464,580,6,"<b>ASIGNADO</b>");
		$io_pdf->addText(515,590,6,"<b>MONTO</b>");
		$io_pdf->addText(510,580,6,"<b>ASIGNADO</b>");
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('codigo'=>'',
						  'descripcion'=>'',
						  'cargof'=>'',
						  'cargom'=>'',
						  'cargov'=>'',
						  'cargo'=>'',
						  'monto'=>'',
						  'cargorealf'=>'',
						  'cargorealm'=>'',
						  'cargorealv'=>'',
						  'cargoreal'=>'',
						  'montoreal'=>'',
						  'montoacum'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 5.8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>505, // Ancho de la tabla
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'xOrientation'=>'left', // Orientación de la tabla
						 'xPos'=>565,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>27), 
						 			   'descripcion'=>array('justification'=>'left','width'=>123),
									   'cargof'=>array('justification'=>'center','width'=>25),
									   'cargom'=>array('justification'=>'center','width'=>25),
									   'cargov'=>array('justification'=>'center','width'=>25),
									   'cargo'=>array('justification'=>'center','width'=>35),
									   'monto'=>array('justification'=>'right','width'=>40),
									   'cargorealf'=>array('justification'=>'center','width'=>25),
									   'cargorealm'=>array('justification'=>'center','width'=>25),
									   'cargorealv'=>array('justification'=>'center','width'=>25),
									   'cargoreal'=>array('justification'=>'center','width'=>35),
									   'montoreal'=>array('justification'=>'right','width'=>40),
									   'montoacum'=>array('justification'=>'right','width'=>60))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales($ls_totalf,$ls_totalm, $ls_totalv,$ls_totalcargo,$ls_totalmonto,
	                          $ls_totalrealf,$ls_totalrealm,$ls_totalrealv,$ls_totalreal,
							  $ls_totalmontoreal,$ls_totalmontoacum,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los totales
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('descripcion'=>'<b>TOTALES</b>',
						  'cargof'=>'<b>'.$ls_totalf.'</b>',
						  'cargom'=>'<b>'.$ls_totalm.'</b>',
						  'cargov'=>'<b>'.$ls_totalv.'</b>',
						  'cargo'=>'<b>'.$ls_totalcargo.'</b>',
						  'monto'=>'<b>'.number_format($ls_totalmonto,2,",",".").'</b>',
						  'cargorealf'=>'<b>'.$ls_totalrealf.'</b>',
						  'cargorealm'=>'<b>'.$ls_totalrealm.'</b>',
						  'cargorealv'=>'<b>'.$ls_totalrealv.'</b>',
						  'cargoreal'=>'<b>'.$ls_totalreal.'</b>',
						  'montoreal'=>'<b>'.number_format($ls_totalmontoreal,2,",",".").'</b>',
						  'montoacum'=>'<b>'.number_format($ls_totalmontoacum,2,",",".").'</b>');
		$la_columna=array('descripcion'=>'',
						  'cargof'=>'',
						  'cargom'=>'',
						  'cargov'=>'',
						  'cargo'=>'',
						  'monto'=>'',
						  'cargorealf'=>'',
						  'cargorealm'=>'',
						  'cargorealv'=>'',
						  'cargoreal'=>'',
						  'montoreal'=>'',
						  'montoacum'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>505, // Ancho de la tabla
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'xOrientation'=>'left', // Orientación de la tabla
						 'xPos'=>565,
						 'cols'=>array('descripcion'=>array('justification'=>'left','width'=>150),
									   'cargof'=>array('justification'=>'center','width'=>25),
									   'cargom'=>array('justification'=>'center','width'=>25),
									   'cargov'=>array('justification'=>'center','width'=>25),
									   'cargo'=>array('justification'=>'center','width'=>35),
									   'monto'=>array('justification'=>'right','width'=>40),
									   'cargorealf'=>array('justification'=>'center','width'=>25),
									   'cargorealm'=>array('justification'=>'center','width'=>25),
									   'cargorealv'=>array('justification'=>'center','width'=>25),
									   'cargoreal'=>array('justification'=>'center','width'=>35),
									   'montoreal'=>array('justification'=>'right','width'=>40),
									   'montoacum'=>array('justification'=>'right','width'=>60))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="RECURSOS HUMANOS CLASIFICADOS POR TIPO DE CARGOS";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_rango=$io_fun_nomina->uf_obtenervalor_get("rango",""); 
	$ls_periodo=$io_fun_nomina->uf_obtenervalor_get("periodo","");
	$ls_tiporeporte=$io_fun_nomina->uf_obtenervalor_get("tiporeporte",0);
	$ls_bolivares="Bolívares";
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_snorh_class_reportbsf.php");
		$io_report=new sigesp_snorh_class_reportbsf();
		$ls_bolivares="Bolívares Fuertes";
	}	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_intstructivo_cargos($ls_rango); // Obtenemos el detalle del reporte
	}
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		error_reporting(E_ALL);
		set_time_limit(1800);
        $io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(7.79,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_rango,$io_pdf); // Imprimimos el encabezado de la página		
		$li_totalcargoprog=0;
		$li_totalcargoreal=0;		
		$li_totalmontoprog=0;
		$li_totalmontoreal=0;		
		$total_cagorprogf=0;
		$total_cagorprogm=0;
		$total_cagorprogv=0;
		$total_cagorrealf=0;
		$total_cagorrealm=0;
		$total_cagorrealv=0;
		$total_montoacum=0;
		$li_totrow=$io_report->DS_cargos->getRowCount("codigo"); 
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
		   $ls_codigo=$io_report->DS_cargos->getValue("codigo",$li_i);
		   $ls_denomiancion=$io_report->DS_cargos->getValue("denominacion",$li_i);
		   $ls_cargo=$io_report->DS_cargos->getValue("cargos",$li_i);
		   $ls_cargof=$io_report->DS_cargos->getValue("cargosf",$li_i);
		   $ls_cargom=$io_report->DS_cargos->getValue("cargosm",$li_i);
		   $ls_cargov=$io_report->DS_cargos->getValue("cargosv",$li_i);
		   $ls_montosc=$io_report->DS_cargos->getValue("montosc",$li_i);
		   $ls_cargoreal=$io_report->DS_cargos->getValue("cargoreal",$li_i);
		   $ls_cargorealf=$io_report->DS_cargos->getValue("cargorealf",$li_i);
		   $ls_cargorealm=$io_report->DS_cargos->getValue("cargorealm",$li_i);
		   $ls_cargorealv=$io_report->DS_cargos->getValue("cargorealv",$li_i);
		   $ls_montoreal=$io_report->DS_cargos->getValue("montoreal",$li_i);
		   $ls_montoacum=$io_report->DS_cargos->getValue("montoacum",$li_i); 
		   $ls_tipo_cargo=$io_report->DS_cargos->getValue("tipo",$li_i);
		   $ls_tipo= gettype($ls_montoacum);  
		   if ($ls_tipo!="string")
		   {
		   		$la_data[$li_i]=array('codigo'=>$ls_codigo,
									  'descripcion'=> $ls_denomiancion,
									  'cargof'=>$ls_cargof,
									  'cargom'=>$ls_cargom,
									  'cargov'=>$ls_cargov,
									  'cargo'=>$ls_cargo,
									  'monto'=>number_format($ls_montosc,2,",","."),
									  'cargorealf'=>$ls_cargorealf,
									  'cargorealm'=>$ls_cargorealm,
									  'cargorealv'=>$ls_cargorealv,
									  'cargoreal'=>$ls_cargoreal,
									  'montoreal'=>number_format($ls_montoreal,2,",","."),
									  'montoacum'=>number_format($ls_montoacum,2,",","."));
		   }
		   else
		   {
		   		$la_data[$li_i]=array('codigo'=>$ls_codigo,
									  'descripcion'=> $ls_denomiancion,
									  'cargof'=>$ls_cargof,
									  'cargom'=>$ls_cargom,
									  'cargov'=>$ls_cargov,
									  'cargo'=>$ls_cargo,
									  'monto'=>$ls_montosc,
									  'cargorealf'=>$ls_cargorealf,
									  'cargorealm'=>$ls_cargorealm,
									  'cargorealv'=>$ls_cargorealv,
									  'cargoreal'=>$ls_cargoreal,
									  'montoreal'=>$ls_montoreal,
									  'montoacum'=>$ls_montoacum);	   	
		   }
		   
		   if ($ls_tipo_cargo==1)
		   {				  
			   $li_totalcargoprog=$li_totalcargoprog+$ls_cargo;
			   $li_totalcargoreal=$li_totalcargoreal+$ls_cargoreal;
					
			   $li_totalmontoprog=$li_totalmontoprog+$ls_montosc;
			   $li_totalmontoreal=$li_totalmontoreal+$ls_montoreal;	
				
			   $total_cagorprogf=$total_cagorprogf+$ls_cargof;
			   $total_cagorprogm=$total_cagorprogm+$ls_cargom;
			   $total_cagorprogv=$total_cagorprogv+$ls_cargov;
			   
			   $total_cagorrealf=$total_cagorrealf+$ls_cargorealf;
			   $total_cagorrealm=$total_cagorrealm+$ls_cargorealm;
			   $total_cagorrealv=$total_cagorrealv+$ls_cargorealv;
			   
			   $total_montoacum=$total_montoacum+$ls_montoacum;
			}
		   			
		}//fin del for
		if ($li_totrow>0)
		{
			uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
			uf_print_totales($total_cagorprogf,$total_cagorprogm, $total_cagorprogv,$li_totalcargoprog,$li_totalmontoprog,
							 $total_cagorrealf,$total_cagorrealm,$total_cagorrealv,$li_totalcargoreal,$li_totalmontoreal,
							 $total_montoacum,&$io_pdf);
			unset($la_data);
		}
		if(($lb_valido)&&($li_totrow>0)) // Si no ocurrio ningún error
		{
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo algún error
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