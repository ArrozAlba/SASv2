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

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 29/07/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_sane_centromedico.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_titulo2,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 29/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,740,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo2);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo2); // Agregar el título
		$io_pdf->addText(512,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(518,743,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera(&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 29/07/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSety(713);
        $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(51,698,500,$io_pdf->getFontHeight(11.5));
        $io_pdf->setColor(0,0,0);
		$la_data[1]=array('nro'=>'<b>Nro</b>',
						   'cedula'=>'<b>Cédula</b>',
						   'nombre'=>'<b>Apellidos y Nombres</b>',
						   'centromedico'=>'<b>Centro Médico</b>');
		$la_columnas=array('nro'=>'',
						   'cedula'=>'',
						   'nombre'=>'',
						   'centromedico'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('nro'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>250), // Justificación y ancho de la columna
						 			   'centromedico'=>array('justification'=>'center','width'=>170))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_cabecera
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
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 29/07/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columnas=array('nro'=>'<b>Nro</b>',
						   'cedula'=>'<b>Cédula</b>',
						   'nombre'=>'<b>Apellidos y Nombres</b>',
						   'centromedico'=>'<b>Centro Médico</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('nro'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>250), // Justificación y ancho de la columna
						 			   'centromedico'=>array('justification'=>'left','width'=>170))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>Listado de Cambio de Centro Médico</b>";
	$ls_titulo2="<b>(Forma 14-02)</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_sane_centromedico($ls_codperdes,$ls_codperhas,$ls_orden); // Cargar el DS con los datos del reporte
	}
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
		$io_pdf->ezSetCmMargins(3.25,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_titulo2,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$li_totrow=$io_report->DS->getRowCount("codper");
		uf_print_cabecera($io_pdf);
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
			$ls_cedper=$io_report->DS->data["cedper"][$li_i];
			$ls_nomper=$io_report->DS->data["apeper"][$li_i].", ".$io_report->DS->data["nomper"][$li_i];
			$ls_cenmedper=$io_report->DS->data["cenmedper"][$li_i];
			switch($ls_cenmedper)
			{
				  case "A01": $ls_cenmedper="AV PPAL SAN JOSE MARACAY"; break;
				  case "A02": $ls_cenmedper="C AYACUCHO STA ROSA MARACAY "; break;
				  case "A04": $ls_cenmedper="AV UNIVERSIDAD EL LIMON"; break;
				  case "A10": $ls_cenmedper="URB LAS MERCEDES LA VICTORIA"; break;
				  case "A15": $ls_cenmedper="CTRO CAGUA ULT TRANS CORINSA"; break;
				  case "A20": $ls_cenmedper="HOSP JOSE VARGAS CALLE OVALLES"; break;
				  case "B01": $ls_cenmedper="PASEO MENESES CDAD BOLIVAR"; break;
				  case "B02": $ls_cenmedper="PASEO GASPAN MDO PE CD BOLIVAR"; break;
				  case "B10": $ls_cenmedper="URB GUAIPARO SAN FELIX"; break;
				  case "B11": $ls_cenmedper="VIA RIO CLARO SAN FELIX"; break;
				  case "B12": $ls_cenmedper="SECTOR UD-14S SAN FELIX "; break;
				  case "B20": $ls_cenmedper="URB LOS OLIVOS PTO ORDAZ"; break;
				  case "B22": $ls_cenmedper="UNARE"; break;
				  case "B30": $ls_cenmedper="CALLE CUYUHI UPATA"; break;
				  case "B40": $ls_cenmedper="FRENTE ALTAVISTA SUR PTO ORDAZ"; break;
				  case "B60": $ls_cenmedper="FINAL CALLE GRATEU - EL CALLAO"; break;
				  case "CO1": $ls_cenmedper="AV MONTES DE OCA VALENCIA"; break;
				  case "C03": $ls_cenmedper="AV PRINCIPAL NAGUANAGUA"; break;
				  case "C05": $ls_cenmedper="AV L ALVARADO LA CANDELARIA"; break;
				  case "C10": $ls_cenmedper="CARRETERA YAGUA GUACARA"; break;
				  case "C11": $ls_cenmedper="C PPAL BARRIO GALLARDO S JOAQUIH"; break;
				  case "C12": $ls_cenmedper="C PROCER B MCAL SUCRE MARIARA"; break;
				  case "C13": $ls_cenmedper="AV 6 URB POCATERRA TOCUYITO"; break;
				  case "C14": $ls_cenmedper="UR PARAPARAL LOS GUAYOS VALENCIA"; break;
				  case "C20": $ls_cenmedper="FINAL CALLE PLAZA PTO CABELLO"; break;
				  case "C21": $ls_cenmedper="URB STA CRUZ Z IND PTO CABELLO"; break;
				  case "C22": $ls_cenmedper="AV PPA LA SORPRESA PTO CABELLO"; break;
				  case "C30": $ls_cenmedper="CARRETERA NACIONAL MORON"; break;
				  case "C40": $ls_cenmedper="ALTOS COLOHIA PSIQUI NAGUANAGUA"; break;
				  case "C50": $ls_cenmedper="AV G MOTORS Z I SUR II VALENCIA"; break;
				  case "D02": $ls_cenmedper="AV PRINCIPAL EL CEMENTERIO"; break;
				  case "D03": $ls_cenmedper="2DA TRANSVERSAL GUAICAIPURO"; break;
				  case "D04": $ls_cenmedper="AV SUCRE CATIA"; break;
				  case "D06": $ls_cenmedper="LOS JARDINES DEL VALLE"; break;
				  case "D07": $ls_cenmedper="AV INTERCOMUNAL ANTIMANO"; break;
				  case "D08": $ls_cenmedper="AV LOS SAMANES EL PARAISO"; break;
				  case "D09": $ls_cenmedper="AV PPAL EL CUARTEL CATIA"; break;
				  case "D10": $ls_cenmedper="AV M F TOVAR SAN BERNARDINO"; break;
				  case "D12": $ls_cenmedper="CENTRO AMB UD5 LA HACIENDA"; break;
				  case "D13": $ls_cenmedper="EDF MUNICIPAL MACARAO"; break;
				  case "D50": $ls_cenmedper="AV SOUBLETTE LA GUAIRA"; break;
				  case "D51": $ls_cenmedper="CALLE PRINCIPAL CARABALLEDA"; break;
				  case "D52": $ls_cenmedper="CALLE PRINCIPAL CARAYACA"; break;
				  case "D53": $ls_cenmedper="CALL PPAL LOS MANGOS NAIGUATA"; break;
				  case "D54": $ls_cenmedper="CIUDAD VACACIONAL LOS CARACAS"; break;
				  case "D60": $ls_cenmedper="CALLE LEBRUN PETARE"; break;
				  case "D70": $ls_cenmedper="CALLE JOSE FELIX RIVAS CHACAO"; break;
				  case "D80": $ls_cenmedper="C GONZALES RINCONES-LA TRINIDAD"; break;
				  case "E01": $ls_cenmedper="AV 5 DE JULIO BARCELONA"; break;
				  case "E10": $ls_cenmedper="CAMPO GUARAGUAO PTO LA CRUZ"; break;
				  case "E11": $ls_cenmedper="BARRIO GUANIRE PTO LA CRUZ"; break;
				  case "E20": $ls_cenmedper="CARRETERA VEA EL TIGRE"; break;
				  case "E30": $ls_cenmedper="AV INTER SEC GARZA PTO LA CRUZ"; break;
				  case "E40": $ls_cenmedper="AV VENEZUELA - ANACO"; break;
				  case "F01": $ls_cenmedper="CALLE FEDERACION CORO"; break;
				  case "F10": $ls_cenmedper="C RAFAEL GONZALEZ PTO FIJO"; break;
				  case "F20": $ls_cenmedper="URB JUDIBANA AMUAY"; break;
				  case "F21": $ls_cenmedper="AV TACHIRA AV INTERCOM LAGOVEN"; break;
				  case "F30": $ls_cenmedper="CAMPO SHELL HOSPITAL CARDON"; break;
				  case "GOl": $ls_cenmedper="SECTOR SANTA ISABEL SAN JUAN"; break;
				  case "G03": $ls_cenmedper="URB LA MISION CALABOZO"; break;
				  case "G40": $ls_cenmedper="CALLE ATARRAYA - V DE LA PASCUA"; break;
				  case "HOl": $ls_cenmedper="CARRET A BIRUACA-SAN FERNANDO"; break;
				  case "JOl": $ls_cenmedper="AV CARABOBO SAN CARLOS"; break;
				  case "J30": $ls_cenmedper="CARRETERA NACIONAL-TINAQUILLO"; break;
				  case "K01": $ls_cenmedper="U PROCERES BRNAS-TURINO FE y A"; break;
				  case "LOl": $ls_cenmedper="AV 13 ENTRE CALLS 49 Y 50 BQTO"; break;
				  case "L10": $ls_cenmedper="CARRl C 4Y5 BARRIO UNION BQTO"; break;
				  case "L20": $ls_cenmedper="PROL A L SALLE F SISAL II BQTO"; break;
				  case "L30": $ls_cenmedper="CALLE CURIRAGUA - CARORA"; break;
				  case "M01": $ls_cenmedper="AV BERMUDEZ LOS TEQUES"; break;
				  case "M10": $ls_cenmedper="URB RUIZ PIMEDA GUARENAS"; break;
				  case "M15": $ls_cenmedper="AV PERIMETRAL CUA"; break;
				  case "M20": $ls_cenmedper="U LUIS TOVAR CARR STA TERESA TUY"; break;
				  case "NOl": $ls_cenmedper="AV 4 DE MAYO PORLAMAR"; break;
				  case "NO5": $ls_cenmedper="U VILLA ROSA LADO COL PORLAMAR"; break;
				  case "/01": $ls_cenmedper="CARRET NAC VIA LA CRUZ MATURIN"; break;
				  case "POl": $ls_cenmedper="AVENIDA 21 - GUANARE"; break;
				  case "P10": $ls_cenmedper="URB MAMANICO - ACARIGUA"; break;
				  case "ROl": $ls_cenmedper="FIN AV AMERICAS CERCA TERMINAL"; break;
				  case "SOl": $ls_cenmedper="CALLE SUCRE CUMANA"; break;
				  case "S20": $ls_cenmedper="CALLE CARABOO - CARUPANO"; break;
				  case "TOl": $ls_cenmedper="CALLE 5 ESQ CRR 8 SAN CRISTOBAL"; break;
				  case "T10": $ls_cenmedper="CALLE 4 PALMIRA"; break;
				  case "T20": $ls_cenmedper="ZONA INDUSTRIAL LA FRIA"; break;
				  case "T30": $ls_cenmedper="URB STA TERESA SAN CRISTOBAL"; break;
				  case "UOl": $ls_cenmedper="CALLE NEGRO PRIMERO TUCUPITA"; break;
				  case "WOl": $ls_cenmedper="AV RIO NEGRO PTO AYACUCHO"; break;
				  case "XOl": $ls_cenmedper="AV 19 DE ABRIL TRUJILLO"; break;
				  case "X10": $ls_cenmedper="FINAL CALLE 10 VALERA"; break;
				  case "Xll": $ls_cenmedper="URB LAS BEATRIZ VALERA"; break;
				  case "X20": $ls_cenmedper="EDIF CONTINENTAL C 10 VALERA"; break;
				  case "YOl": $ls_cenmedper="AVDA YARACUY SAN FELIPE"; break;
				  case "Y40": $ls_cenmedper="CARRETERA NACIONAL – CHIVACOA"; break;
				  case "Z0l": $ls_cenmedper="AV GUAJIRA URB SAN JACINTO"; break;
				  case "Z02": $ls_cenmedper="AV 7 ESQ CALLE VARGAS VERITAS"; break;
				  case "Z03": $ls_cenmedper="CALLE 100 SABANETA LARGA "; break;
				  case "Z04": $ls_cenmedper="CAMPO PARAISO LA CONCEPCION"; break;
				  case "ZO5": $ls_cenmedper="AV 4 NRO 71-37 - BELLA VISTA"; break;
				  case "Z07": $ls_cenmedper="ENTRADA DE STA CRUZ DE MARA"; break;
				  case "Z08": $ls_cenmedper="CTRO STA RITA CALLE LA PLANTA"; break;
				  case "Z09": $ls_cenmedper="AMB CABIMAS AV 32 LOS LAURELES"; break;
				  case "ZlO": $ls_cenmedper="AMB CIUDAD OJEDA-C STA MONICA"; break;
				  case "Z20": $ls_cenmedper="CTRO AUX MONS GODOY A 5 D JULI"; break;
				  case "Z21": $ls_cenmedper="AV F ARM CANCHANCHA DELICIAS"; break;
				  case "Z22": $ls_cenmedper="HOSP NORIEGA FRENTE AL LGO MBO"; break;
				  case "Z30": $ls_cenmedper="AV BOLIVAR - STA BARBARA ZULIA"; break;
			}			
			$la_data[$li_i]=array('nro'=>$li_i,'cedula'=>$ls_cedper,'nombre'=>$ls_nomper,'centromedico'=>$ls_cenmedper);
		}
		$io_report->DS->resetds("codper");
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
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
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 
