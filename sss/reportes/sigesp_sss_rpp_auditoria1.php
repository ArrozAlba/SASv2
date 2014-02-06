<?php
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
	function uf_print_encabezado_pagina($as_titulo,$ad_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_fecha // Fecha 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(20,40,730,40);
		$io_pdf->setStrokeColor(0,0,0);
//		$io_pdf->rectangle(200,530,525,40);
//		$io_pdf->line(540,530,540,570);
//		$io_pdf->line(540,550,725,550);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],40.5,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,550,11,"<b>".$as_titulo."</b>"); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$ad_fecha);
		$tm=188;
		$io_pdf->addText(693,550,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(699,543,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codusu,$as_nomusu,$as_codsis,$as_nomsis,$as_evento,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codusu // codigo de usuario
		//	    		   as_nomusu // nombre nombre de usuario
		//	    		   as_codsis // codigo de sistema
		//	    		   as_nomsis // nombre de sistema
		//	    		   as_evento // Evento 
		//	    		   io_pdf       // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($as_codusu==""){$as_nomusu="Todos los Usuarios";}
		if($as_codsis==""){$as_nomsis="Todos los Sistemas";}
		if($as_evento==""){$as_evento="Todos los Eventos";}
		
		$la_data=array(array('name'=>'<b>Usuario</b>  '.$as_codusu." - ".$as_nomusu.''),
					   array ('name'=>'<b>Sistema</b>  '.$as_codsis." - ".$as_nomsis.''),
					   array ('name'=>'<b>Evento</b>    '.$as_evento.''),
					   );
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'lineCol'=>array(0.9,0.9,0.9), // Mostrar Líneas
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700); // Ancho Máximo de la tabla
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
		$la_columna=array('codsis'=>'<b>Sistema</b>',
						  'codusu'=>'<b>Usuario</b>',
						  'evento'=>'<b>Evento</b>',
						  'titven'=>'<b>Ventana</b>',
						  'fecevetra'=>'<b>Fecha/Hora</b>',
						  'equevetra'=>'<b>Equipo</b>',
						  'desevetra'=>'<b>Descripción</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codsis'=>array('justification'=>'center','width'=>45), // Justificación y ancho de la columna
						 			   'codusu'=>array('justification'=>'left','width'=>65), // Justificación y ancho de la columna
						 			   'evento'=>array('justification'=>'left','width'=>55), // Justificación y ancho de la columna
						 			   'titven'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'fecevetra'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'equevetra'=>array('justification'=>'left','width'=>65), // Justificación y ancho de la columna
						 			   'desevetra'=>array('justification'=>'left','width'=>250))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_sss_class_report.php");
	$io_report=new sigesp_sss_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_funciones_seguridad.php");
	$io_fun_inventario=new class_funciones_seguridad();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ld_fecdes=$io_fun_inventario->uf_obtenervalor_get("fecdes","");
	$ld_fechas=$io_fun_inventario->uf_obtenervalor_get("fechas","");
	$ls_titulo="<b> Reporte de Auditoría </b>";
	$ls_fecha="";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
	$ls_codusu=$io_fun_inventario->uf_obtenervalor_get("codigo","");
	$ls_evento=$io_fun_inventario->uf_obtenervalor_get("evento","");
	$ls_sistema=$io_fun_inventario->uf_obtenervalor_get("sistema","");
	$ls_nomsis="";
	$ls_nomusu="";
	$lb_valido=true;
	if($ls_evento=="Todos")
	{$ls_evento="";}
	if($ls_codusu!="")
	{
		$lb_valido=$io_report->uf_sss_select_usuario($ls_codemp,$ls_codusu,$ls_nomusu);
	}
	if($ls_sistema=="Todos")
	{$ls_codsis="";}
	else
	{
		$ls_codsis=$ls_sistema;
		$lb_valido=$io_report->uf_sss_select_sistema($ls_codemp,$ls_codsis,$ls_nomsis);
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_sss_select_auditoria($ls_codemp,$ls_codusu,$ls_evento,$ls_codsis,$ld_fecdes,$ld_fechas,$rs_data); // Cargar el DS con los datos de la cabecera del reporte
	}
	$li_total=$io_report->io_sql->num_rows($rs_data);
	if(($lb_valido==false)||($li_total==0)) // Existe algún error ó no hay registros
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
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(690,50,10,'','',1); // Insertar el número de página
		uf_print_cabecera($ls_codusu,$ls_nomusu,$ls_codsis,$ls_nomsis,$ls_evento,$io_pdf); // Imprimimos la cabecera del registro
		$li_pos=0;
		while($row=$io_report->io_sql->fetch_row($rs_data))
		{
			$li_pos=$li_pos+1;
		    $io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount; // Número de página
			$li_totprenom=0;
			$li_totant=0;
			$ls_codsisaux= $row["codsis"];
			$ls_codusu= $row["codusu"];
			$ls_evento= $row["evento"];
			$ls_ventana= $row["titven"];		
			$ld_fecevetra=  date("d/m/Y H:i",strtotime($row["fecevetra"]));
			$ls_equevetra=  $row["equevetra"];
			$ls_desevetra=  $row["desevetra"];
			$la_data[$li_pos]=array('codsis'=>$ls_codsisaux,'codusu'=>$ls_codusu,'evento'=>$ls_evento,'titven'=>$ls_ventana,'fecevetra'=>$ld_fecevetra,
						          'equevetra'=>$ls_equevetra,'desevetra'=>$ls_desevetra);
		}
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 


		if($lb_valido)
		{
			$io_pdf->ezStopPageNumbers(1,1);
			$io_pdf->ezStream();
		}
	}
?>