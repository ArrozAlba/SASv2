<?php
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
	ini_set('memory_limit','512M');
	ini_set('max_execution_time','0');
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/08/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_listadopersonalgenerico.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_campo($id,&$as_campo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_validar_campo
		//		   Access: private 
		//	    Arguments: id // Identificador del campo que se desea validar
		//				   as_campo // Contenido del campo que se desea validar
		//    Description: función que valida los campos 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/08/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina, $io_funciones,$io_report;
		
		$ls_tipo=$_SESSION["la_titulos"][$id]["tipo"];
		switch($ls_tipo)
		{
			case "string":
				$as_campo=rtrim($as_campo);
				break;
			case "date":
				$as_campo=$io_funciones->uf_convertirfecmostrar($as_campo);
				break;		
			case "integer":
				$as_campo=number_format($as_campo,0,",",".");
				break;
			case "double":
				$as_campo=number_format($as_campo,2,",",".");
				break;
		}
		$ls_campo=$_SESSION["la_titulos"][$id]["campo"];
		switch($ls_campo)
		{
			case "sno_personal.nacper":
				switch($as_campo)
				{
					case "V":
						$as_campo="Venezolano";
						break;
					case "E":
						$as_campo="Estranjero";
						break;
				}
				break;
			case "sno_personal.sexper":
				switch($as_campo)
				{
					case "M":
						$as_campo="Masculino";
						break;
					case "F":
						$as_campo="Femenino";
						break;
				}
				break;
			case "sno_personal.edocivper":
				switch($as_campo)
				{
					case "S":
						$as_campo="Soltero";
						break;
					case "C":
						$as_campo="Casado";
						break;
					case "D":
						$as_campo="Divorciado";
						break;
					case "V":
						$as_campo="Viudo";
						break;
					case "K":
						$as_campo="Concubino";
						break;
				}
				break;
			case "sno_personal.nivacaper":
				switch($as_campo)
				{
					case "0":
						$as_campo="Ninguno";
						break;
					case "1":
						$as_campo="Primaria";
						break;
					case "2":
						$as_campo="Bachiller";
						break;
					case "3":
						$as_campo="Técnico Superior";
						break;
					case "4":
						$as_campo="Universitario";
						break;
					case "5":
						$as_campo="Maestria";
						break;
					case "6":
						$as_campo="PostGrado";
						break;
					case "7":
						$as_campo="Doctorado";
						break;
				}
				break;
			case "sno_personal.estper":
				switch($as_campo)
				{
					case "0":
						$as_campo="Pre-Ingreso";
						break;
					case "1":
						$as_campo="Activo";
						break;
					case "2":
						$as_campo="N/A";
						break;
					case "3":
						$as_campo="Egresado";
						break;
				}
				break;
			case "sno_personalnomina.staper":
				switch($as_campo)
				{
					case "0":
						$as_campo="N/A";
						break;
					case "1":
						$as_campo="Activo";
						break;
					case "2":
						$as_campo="Vacaciones";
						break;
					case "3":
						$as_campo="Egresado";
						break;
					case "4":
						$as_campo="Suspendido";
						break;
				}
				break;
			case "sno_personal.cauegrper":
				switch($as_campo)
				{
					case "N": // Ninguna
						$as_campo="Ninguna";
						break;
					case "D": // Despido
						$as_campo="Despido";
						break;
					case "R": // Renuncia
						$as_campo="Renuncia";
						break;
					case "J": // Jubilado
						$as_campo="Jubilado";
						break;
					case "P": // Pensionado
						$as_campo="Pensionado";
						break;
					case "T": // Traslado
						$as_campo="Traslado";
						break;
					case "F": // Fallecido
						$as_campo="Fallecido";
						break;
				}
				break;
			case "sno_personal.codorg":
				$as_campo=$io_report->uf_buscar_ubicacion_fisica($as_campo);
			break;
		}
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/08/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		switch($_SESSION["ls_tiporeporte"])
		{
			case "1":
				$io_pdf->line(50,40,555,40);
				$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
				$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
				$tm=306-($li_tm/2);
				$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
				$io_pdf->addText(512,750,8,date("d/m/Y")); // Agregar la Fecha
				$io_pdf->addText(518,743,7,date("h:i a")); // Agregar la Hora
				$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
				break;
		
			case "2":
				$io_pdf->line(50,40,755,40);
				$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
				$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
				$tm=396-($li_tm/2);
				$io_pdf->addText($tm,540,11,$as_titulo); // Agregar el título
				$io_pdf->addText(712,560,8,date("d/m/Y")); // Agregar la Fecha
				$io_pdf->addText(718,553,7,date("h:i a")); // Agregar la Hora
				$io_pdf->ezStartPageNumbers(750,50,10,'','',1); // Insertar el número de página
				break;
		
			case "3":
				$io_pdf->line(50,40,955,40);
				$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],60,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
				$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
				$tm=504-($li_tm/2);
				$io_pdf->addText($tm,570,11,$as_titulo); // Agregar el título
				$io_pdf->addText(912,580,8,date("d/m/Y")); // Agregar la Fecha
				$io_pdf->addText(918,573,7,date("h:i a")); // Agregar la Hora
				$io_pdf->ezStartPageNumbers(950,50,10,'','',1); // Insertar el número de página
				break;
		}

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
		// Fecha Creación: 22/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_cols="";
		$li_ancho=0;
		switch($_SESSION["li_total"])
		{
			case "0":
				$la_columna=array('nro'=>'Nro','campo0'=>$_SESSION["la_titulos"][0]["titulo"]);
				$la_cols=array('nro'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
						 	   'campo0'=>array('justification'=>$_SESSION["la_titulos"][0]["alineacion"],'width'=>470));
				$li_ancho=500;
				break;
			case "1":
				$la_columna=array('nro'=>'Nro','campo0'=>$_SESSION["la_titulos"][0]["titulo"],'campo1'=>$_SESSION["la_titulos"][1]["titulo"]);
				$la_cols=array('nro'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
						 	   'campo0'=>array('justification'=>$_SESSION["la_titulos"][0]["alineacion"],'width'=>235),
							   'campo1'=>array('justification'=>$_SESSION["la_titulos"][1]["alineacion"],'width'=>235));
				$li_ancho=500;
				break;
			case "2":
				$la_columna=array('nro'=>'Nro','campo0'=>$_SESSION["la_titulos"][0]["titulo"],'campo1'=>$_SESSION["la_titulos"][1]["titulo"],
				                  'campo2'=>$_SESSION["la_titulos"][2]["titulo"]);
				$la_cols=array('nro'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
						 	   'campo0'=>array('justification'=>$_SESSION["la_titulos"][0]["alineacion"],'width'=>156),
							   'campo1'=>array('justification'=>$_SESSION["la_titulos"][1]["alineacion"],'width'=>157),
							   'campo2'=>array('justification'=>$_SESSION["la_titulos"][2]["alineacion"],'width'=>157));
				$li_ancho=500;
				break;
			case "3":
				$la_columna=array('nro'=>'Nro','campo0'=>$_SESSION["la_titulos"][0]["titulo"],'campo1'=>$_SESSION["la_titulos"][1]["titulo"],
				                  'campo2'=>$_SESSION["la_titulos"][2]["titulo"],'campo3'=>$_SESSION["la_titulos"][3]["titulo"]);
				$la_cols=array('nro'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
						 	   'campo0'=>array('justification'=>$_SESSION["la_titulos"][0]["alineacion"],'width'=>117.5),
							   'campo1'=>array('justification'=>$_SESSION["la_titulos"][1]["alineacion"],'width'=>117.5),
							   'campo2'=>array('justification'=>$_SESSION["la_titulos"][2]["alineacion"],'width'=>117.5),
							   'campo3'=>array('justification'=>$_SESSION["la_titulos"][3]["alineacion"],'width'=>117.5));
				$li_ancho=500;
				break;
			case "4":
				$la_columna=array('nro'=>'Nro','campo0'=>$_SESSION["la_titulos"][0]["titulo"],'campo1'=>$_SESSION["la_titulos"][1]["titulo"],
				                  'campo2'=>$_SESSION["la_titulos"][2]["titulo"],'campo3'=>$_SESSION["la_titulos"][3]["titulo"],
								  'campo4'=>$_SESSION["la_titulos"][4]["titulo"]);
				$la_cols=array('nro'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
						 	   'campo0'=>array('justification'=>$_SESSION["la_titulos"][0]["alineacion"],'width'=>134),
							   'campo1'=>array('justification'=>$_SESSION["la_titulos"][1]["alineacion"],'width'=>134),
							   'campo2'=>array('justification'=>$_SESSION["la_titulos"][2]["alineacion"],'width'=>134),
							   'campo3'=>array('justification'=>$_SESSION["la_titulos"][3]["alineacion"],'width'=>134),
							   'campo4'=>array('justification'=>$_SESSION["la_titulos"][4]["alineacion"],'width'=>134));
				$li_ancho=700;
				break;
			case "5":
				$la_columna=array('nro'=>'Nro','campo0'=>$_SESSION["la_titulos"][0]["titulo"],'campo1'=>$_SESSION["la_titulos"][1]["titulo"],
								  'campo2'=>$_SESSION["la_titulos"][2]["titulo"],'campo3'=>$_SESSION["la_titulos"][3]["titulo"],
								  'campo4'=>$_SESSION["la_titulos"][4]["titulo"],'campo5'=>$_SESSION["la_titulos"][5]["titulo"]);
				$la_cols=array('nro'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
						 	   'campo0'=>array('justification'=>$_SESSION["la_titulos"][0]["alineacion"],'width'=>111),
							   'campo1'=>array('justification'=>$_SESSION["la_titulos"][1]["alineacion"],'width'=>111),
							   'campo2'=>array('justification'=>$_SESSION["la_titulos"][2]["alineacion"],'width'=>112),
							   'campo3'=>array('justification'=>$_SESSION["la_titulos"][3]["alineacion"],'width'=>112),
							   'campo4'=>array('justification'=>$_SESSION["la_titulos"][4]["alineacion"],'width'=>112),
							   'campo5'=>array('justification'=>$_SESSION["la_titulos"][5]["alineacion"],'width'=>112));
				$li_ancho=700;
				break;
			case "6":
				$la_columna=array('nro'=>'Nro','campo0'=>$_SESSION["la_titulos"][0]["titulo"],'campo1'=>$_SESSION["la_titulos"][1]["titulo"],
								  'campo2'=>$_SESSION["la_titulos"][2]["titulo"],'campo3'=>$_SESSION["la_titulos"][3]["titulo"],
								  'campo4'=>$_SESSION["la_titulos"][4]["titulo"],'campo5'=>$_SESSION["la_titulos"][5]["titulo"],
								  'campo6'=>$_SESSION["la_titulos"][6]["titulo"]);
				$la_cols=array('nro'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
						 	   'campo0'=>array('justification'=>$_SESSION["la_titulos"][0]["alineacion"],'width'=>95),
							   'campo1'=>array('justification'=>$_SESSION["la_titulos"][1]["alineacion"],'width'=>95),
							   'campo2'=>array('justification'=>$_SESSION["la_titulos"][2]["alineacion"],'width'=>96),
							   'campo3'=>array('justification'=>$_SESSION["la_titulos"][3]["alineacion"],'width'=>96),
							   'campo4'=>array('justification'=>$_SESSION["la_titulos"][4]["alineacion"],'width'=>96),
							   'campo5'=>array('justification'=>$_SESSION["la_titulos"][5]["alineacion"],'width'=>96),
							   'campo6'=>array('justification'=>$_SESSION["la_titulos"][6]["alineacion"],'width'=>96));
				$li_ancho=700;
				break;
			case "7":
				$la_columna=array('nro'=>'Nro','campo0'=>$_SESSION["la_titulos"][0]["titulo"],'campo1'=>$_SESSION["la_titulos"][1]["titulo"],
								  'campo2'=>$_SESSION["la_titulos"][2]["titulo"],'campo3'=>$_SESSION["la_titulos"][3]["titulo"],
								  'campo4'=>$_SESSION["la_titulos"][4]["titulo"],'campo5'=>$_SESSION["la_titulos"][5]["titulo"],
								  'campo6'=>$_SESSION["la_titulos"][6]["titulo"],'campo7'=>$_SESSION["la_titulos"][7]["titulo"]);
				$la_cols=array('nro'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
						 	   'campo0'=>array('justification'=>$_SESSION["la_titulos"][0]["alineacion"],'width'=>108.75),
							   'campo1'=>array('justification'=>$_SESSION["la_titulos"][1]["alineacion"],'width'=>108.75),
							   'campo2'=>array('justification'=>$_SESSION["la_titulos"][2]["alineacion"],'width'=>108.75),
							   'campo3'=>array('justification'=>$_SESSION["la_titulos"][3]["alineacion"],'width'=>108.75),
							   'campo4'=>array('justification'=>$_SESSION["la_titulos"][4]["alineacion"],'width'=>108.75),
							   'campo5'=>array('justification'=>$_SESSION["la_titulos"][5]["alineacion"],'width'=>108.75),
							   'campo6'=>array('justification'=>$_SESSION["la_titulos"][6]["alineacion"],'width'=>108.75),
							   'campo7'=>array('justification'=>$_SESSION["la_titulos"][7]["alineacion"],'width'=>108.75));
				$li_ancho=900;
				break;					
			case "8":
				$la_columna=array('nro'=>'Nro','campo0'=>$_SESSION["la_titulos"][0]["titulo"],'campo1'=>$_SESSION["la_titulos"][1]["titulo"],
								  'campo2'=>$_SESSION["la_titulos"][2]["titulo"],'campo3'=>$_SESSION["la_titulos"][3]["titulo"],
								  'campo4'=>$_SESSION["la_titulos"][4]["titulo"],'campo5'=>$_SESSION["la_titulos"][5]["titulo"],
								  'campo6'=>$_SESSION["la_titulos"][6]["titulo"],'campo7'=>$_SESSION["la_titulos"][7]["titulo"],
								  'campo8'=>$_SESSION["la_titulos"][8]["titulo"]);
				$la_cols=array('nro'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
						 	   'campo0'=>array('justification'=>$_SESSION["la_titulos"][0]["alineacion"],'width'=>96),
							   'campo1'=>array('justification'=>$_SESSION["la_titulos"][1]["alineacion"],'width'=>96),
							   'campo2'=>array('justification'=>$_SESSION["la_titulos"][2]["alineacion"],'width'=>96),
							   'campo3'=>array('justification'=>$_SESSION["la_titulos"][3]["alineacion"],'width'=>97),
							   'campo4'=>array('justification'=>$_SESSION["la_titulos"][4]["alineacion"],'width'=>97),
							   'campo5'=>array('justification'=>$_SESSION["la_titulos"][5]["alineacion"],'width'=>97),
							   'campo6'=>array('justification'=>$_SESSION["la_titulos"][6]["alineacion"],'width'=>97),
							   'campo7'=>array('justification'=>$_SESSION["la_titulos"][7]["alineacion"],'width'=>97),
							   'campo8'=>array('justification'=>$_SESSION["la_titulos"][8]["alineacion"],'width'=>97));
				$li_ancho=900;
				break;					
			case "9":
				$la_columna=array('nro'=>'Nro','campo0'=>$_SESSION["la_titulos"][0]["titulo"],'campo1'=>$_SESSION["la_titulos"][1]["titulo"],
								  'campo2'=>$_SESSION["la_titulos"][2]["titulo"],'campo3'=>$_SESSION["la_titulos"][3]["titulo"],
								  'campo4'=>$_SESSION["la_titulos"][4]["titulo"],'campo5'=>$_SESSION["la_titulos"][5]["titulo"],
								  'campo6'=>$_SESSION["la_titulos"][6]["titulo"],'campo7'=>$_SESSION["la_titulos"][7]["titulo"],
								  'campo8'=>$_SESSION["la_titulos"][8]["titulo"],'campo9'=>$_SESSION["la_titulos"][9]["titulo"]);
				$la_cols=array('nro'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
						 	   'campo0'=>array('justification'=>$_SESSION["la_titulos"][0]["alineacion"],'width'=>87),
							   'campo1'=>array('justification'=>$_SESSION["la_titulos"][1]["alineacion"],'width'=>87),
							   'campo2'=>array('justification'=>$_SESSION["la_titulos"][2]["alineacion"],'width'=>87),
							   'campo3'=>array('justification'=>$_SESSION["la_titulos"][3]["alineacion"],'width'=>87),
							   'campo4'=>array('justification'=>$_SESSION["la_titulos"][4]["alineacion"],'width'=>87),
							   'campo5'=>array('justification'=>$_SESSION["la_titulos"][5]["alineacion"],'width'=>87),
							   'campo6'=>array('justification'=>$_SESSION["la_titulos"][6]["alineacion"],'width'=>87),
							   'campo7'=>array('justification'=>$_SESSION["la_titulos"][7]["alineacion"],'width'=>87),
							   'campo8'=>array('justification'=>$_SESSION["la_titulos"][8]["alineacion"],'width'=>87),
							   'campo9'=>array('justification'=>$_SESSION["la_titulos"][9]["alineacion"],'width'=>87));
				$li_ancho=900;
				break;					
		}
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>$li_ancho, // Ancho de la tabla
						 'maxWidth'=>$li_ancho, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>$la_cols); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>".$_SESSION["ls_titulo"]."</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_listadogenerico(); // Obtenemos el detalle del reporte
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
		$io_pdf=new Cezpdf($_SESSION["ls_pagina"],$_SESSION["ls_orientacion"]); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(4,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$li_totrow=$io_report->DS->getRowCount("codper");
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_campo0=$io_report->DS->data["campo0"][$li_i];
			if ($ls_campo0=='A')
				{
					$ls_campo0='AHORRO';
				}
				elseif ($ls_campo0=='C')
				{
					$ls_campo0='CORRIENTE';
				}
			$ls_campo1=$io_report->DS->data["campo1"][$li_i];
			if ($ls_campo1=='A')
				{
					$ls_campo1='AHORRO';
				}
				elseif ($ls_campo1=='C')
				{
					$ls_campo1='CORRIENTE';
				}
			$ls_campo2=$io_report->DS->data["campo2"][$li_i];
			if ($ls_campo2=='A')
				{
					$ls_campo2='AHORRO';
				}
				elseif ($ls_campo2=='C')
				{
					$ls_campo2='CORRIENTE';
				}
			$ls_campo3=$io_report->DS->data["campo3"][$li_i];
			if ($ls_campo3=='A')
				{
					$ls_campo3='AHORRO';
				}
				elseif ($ls_campo3=='C')
				{
					$ls_campo3='CORRIENTE';
				}
			$ls_campo4=$io_report->DS->data["campo4"][$li_i];
			if ($ls_campo4=='A')
				{
					$ls_campo4='AHORRO';
				}
				elseif ($ls_campo4=='C')
				{
					$ls_campo4='CORRIENTE';
				}
			$ls_campo5=$io_report->DS->data["campo5"][$li_i];
			if ($ls_campo5=='A')
				{
					$ls_campo5='AHORRO';
				}
				elseif ($ls_campo5=='C')
				{
					$ls_campo5='CORRIENTE';
				}
			$ls_campo6=$io_report->DS->data["campo6"][$li_i];
			if ($ls_campo6=='A')
				{
					$ls_campo6='AHORRO';
				}
				elseif ($ls_campo6=='C')
				{
					$ls_campo6='CORRIENTE';
				}
			$ls_campo7=$io_report->DS->data["campo7"][$li_i];
			if ($ls_campo7=='A')
				{
					$ls_campo7='AHORRO';
				}
				elseif ($ls_campo7=='C')
				{
					$ls_campo7='CORRIENTE';
				}
			$ls_campo8=$io_report->DS->data["campo8"][$li_i];
			
			if ($ls_campo8=='A')
				{
					$ls_campo8='AHORRO';
				}
				elseif ($ls_campo8=='C')
				{
					$ls_campo8='CORRIENTE';
				}
			$ls_campo9=$io_report->DS->data["campo9"][$li_i];
			if ($ls_campo9=='A')
				{
					$ls_campo9='AHORRO';
				}
				elseif ($ls_campo9=='C')
				{
					$ls_campo9='CORRIENTE';
				}
			
			uf_validar_campo(0,&$ls_campo0);
			uf_validar_campo(1,&$ls_campo1);
			uf_validar_campo(2,&$ls_campo2);
			uf_validar_campo(3,&$ls_campo3);
			uf_validar_campo(4,&$ls_campo4);
			uf_validar_campo(5,&$ls_campo5);
			uf_validar_campo(6,&$ls_campo6);
			uf_validar_campo(7,&$ls_campo7);
			uf_validar_campo(8,&$ls_campo8);
			uf_validar_campo(9,&$ls_campo9);
			switch($_SESSION["li_total"])
			{
				case "0":
					$la_data[$li_i]=array('nro'=>$li_i,'campo0'=>$ls_campo0);
					break;
				case "1":
					$la_data[$li_i]=array('nro'=>$li_i,'campo0'=>$ls_campo0,'campo1'=>$ls_campo1);
					break;
				case "2":
					$la_data[$li_i]=array('nro'=>$li_i,'campo0'=>$ls_campo0,'campo1'=>$ls_campo1,'campo2'=>$ls_campo2);
					break;
				case "3":
					$la_data[$li_i]=array('nro'=>$li_i,'campo0'=>$ls_campo0,'campo1'=>$ls_campo1,'campo2'=>$ls_campo2,'campo3'=>$ls_campo3);
					break;
				case "4":
					$la_data[$li_i]=array('nro'=>$li_i,'campo0'=>$ls_campo0,'campo1'=>$ls_campo1,'campo2'=>$ls_campo2,'campo3'=>$ls_campo3,'campo4'=>$ls_campo4);
					break;
				case "5":
					$la_data[$li_i]=array('nro'=>$li_i,'campo0'=>$ls_campo0,'campo1'=>$ls_campo1,'campo2'=>$ls_campo2,'campo3'=>$ls_campo3,'campo4'=>$ls_campo4,'campo5'=>$ls_campo5);
					break;
				case "6":
					$la_data[$li_i]=array('nro'=>$li_i,'campo0'=>$ls_campo0,'campo1'=>$ls_campo1,'campo2'=>$ls_campo2,'campo3'=>$ls_campo3,'campo4'=>$ls_campo4,'campo5'=>$ls_campo5,'campo6'=>$ls_campo6);
					break;
				case "7":
					$la_data[$li_i]=array('nro'=>$li_i,'campo0'=>$ls_campo0,'campo1'=>$ls_campo1,'campo2'=>$ls_campo2,'campo3'=>$ls_campo3,'campo4'=>$ls_campo4,'campo5'=>$ls_campo5,'campo6'=>$ls_campo6,'campo7'=>$ls_campo7);
					break;					
				case "8":
					$la_data[$li_i]=array('nro'=>$li_i,'campo0'=>$ls_campo0,'campo1'=>$ls_campo1,'campo2'=>$ls_campo2,'campo3'=>$ls_campo3,'campo4'=>$ls_campo4,'campo5'=>$ls_campo5,'campo6'=>$ls_campo6,'campo7'=>$ls_campo7,'campo8'=>$ls_campo8);
					break;					
				case "9":
					$la_data[$li_i]=array('nro'=>$li_i,'campo0'=>$ls_campo0,'campo1'=>$ls_campo1,'campo2'=>$ls_campo2,'campo3'=>$ls_campo3,'campo4'=>$ls_campo4,'campo5'=>$ls_campo5,'campo6'=>$ls_campo6,'campo7'=>$ls_campo7,'campo8'=>$ls_campo8,'campo9'=>$ls_campo9);
					break;					
			}
		}
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		unset($la_data);			
		$io_report->DS->resetds("codper");
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 