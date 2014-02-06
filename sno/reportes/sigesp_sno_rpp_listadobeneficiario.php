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
	ini_set('memory_limit','2048M');
	ini_set('max_execution_time','0');

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo,$ai_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/11/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Generó el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		if($ai_tipo==1)
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_listadobeneficiario.php",$ls_descripcion,$ls_codnom);
		}
		else
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_listadobeneficiario.php",$ls_descripcion,$ls_codnom);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_desnom,$as_periodo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/11/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,755,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,540,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,530,11,$as_periodo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(10,$as_desnom);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,520,10,$as_desnom); // Agregar el título
		$io_pdf->addText(712,560,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(718,553,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codper,$as_nomper,$ai_monnet,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // Código del Personal
		//	   			   as_nomper // Nombre del Personal
		//	   			   ai_monnet // Monto neto del personal
		//	    		   io_cabecera // objeto cabecera
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/11/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('nombre'=>'<b>Personal</b>  '.$as_codper.' - '.$as_nomper,'monto'=>'Neto a Cobrar <b>'.$ai_monnet.'</b>'));
		$la_columnas=array('nombre'=>'',
						   'monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>750, // Ancho de la tabla
						 'maxWidth'=>750, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>600), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'left','width'=>150))); // Justificación y ancho de la columna
		$io_pdf->ezSetDy(-10);
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);	
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
		// Fecha Creación: 08/11/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('nro'=>'<b>Nro</b>',
						   'cedula'=>'<b>Cédula</b>',
						   'nombre'=>'<b>                       Apellidos y Nombres</b>',
						   'tipo'=>'<b>	    Tipo Beneficiario</b>',
						   'formapago'=>'<b>       Forma de Pago</b>',
						   'cheque'=>'<b>                Titular Cheque/Cuenta Bancaria</b>',
						   'monto'=>'<b>Monto '.$ls_bolivares.'    </b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>750, // Ancho de la tabla
						 'maxWidth'=>750, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('nro'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>170), // Justificación y ancho de la columna
						 			   'tipo'=>array('justification'=>'left','width'=>100), // Justificación y ancho de la columna
						 			   'formapago'=>array('justification'=>'left','width'=>100), // Justificación y ancho de la columna
						 			   'cheque'=>array('justification'=>'left','width'=>200), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($ai_totben,$ai_montot,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		   Access: private 
		//	    Arguments: ai_totben // Total de Beneficiarios
		//	   			   ai_montot // Monto total por Beneficiario
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por Beneficiarios
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/11/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('total'=>'<b>Total Beneficiarios</b>'.' '.$ai_totben.'','monto'=>$ai_montot));
		$la_columna=array('total'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>750, // Ancho de la tabla
						 'maxWidth'=>750, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>660), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
	$io_monedabsf=new sigesp_c_reconvertir_monedabsf();				
	require_once("../../shared/ezpdf/class.ezpdf.php");
	$ls_tiporeporte="0";
	$ls_bolivares ="Bs.";
	$li_tipo=1;
	require_once("sigesp_sno_class_report.php");
	$io_report=new sigesp_sno_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_quincena=$io_fun_nomina->uf_obtenervalor_get("quincena","-");
	$ls_codban=$io_fun_nomina->uf_obtenervalor_get("codban","");
	$ls_nomban=$io_fun_nomina->uf_obtenervalor_get("nomban","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("subnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("subnomhas","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$ls_titulo="<b>Listado de Beneficiarios ".$ls_nomban."</b>";
	$ls_periodo="<b>Período Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper."</b>";
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_listadobeneficiario_personal($ls_codperdes,$ls_codperhas,$ls_quincena,$ls_codban,$ls_subnomdes,$ls_subnomhas,$ls_orden,&$rs_data); // Cargar el DS con los datos de la cabecera del reporte
	}
	if(($lb_valido==false)||($rs_data->RecordCount()==0)) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else  // Imprimimos el reporte
	{
		error_reporting(E_ALL);
		set_time_limit(3600);
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.6,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_desnom,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(750,50,10,'','',1); // Insertar el número de página
		while($row=$io_report->io_sql->fetch_row($rs_data))
		{
	        $io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount; // Número de página
			$ls_codper=$row["codper"];
			$ls_nomper=$row["apeper"].", ".$row["nomper"];
			$li_neto=$row["monnetres"];
			$li_monnet=$io_fun_nomina->uf_formatonumerico($row["monnetres"]);
			uf_print_cabecera($ls_codper,$ls_nomper,$li_monnet,$io_pdf); // Imprimimos la cabecera del registro
			$ls_tipben="";
			$lb_valido=$io_report->uf_listadobeneficiario_beneficiario($ls_codper,$ls_codban,$ls_tipben,&$rs_data_dt); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_montototalbene=0;
				$li_totalbene=0;
				$li_s=0;
				while($row_dt=$io_report->io_sql->fetch_row($rs_data_dt))
				{
					$li_s=$li_s+1;
					$ls_cedben=$row_dt["cedben"];
					$ls_apenomben=$row_dt["apeben"].", ". $row_dt["nomben"];
					$ls_tipben=$row_dt["tipben"];
					switch($ls_tipben)
					{
						case "0":
							$ls_tipben="Pension sobrevivientes";
							break;
						case "1":
							$ls_tipben="Pension Judicial";
							break;
					}
					$ls_forpagben=$row_dt["forpagben"];
					$ls_pago="";
					switch($ls_forpagben)
					{
						case "0":
							$ls_forpagben="Cheque";
							$ls_pago=$row_dt["nomcheben"];
							break;
						case "1":
							$ls_forpagben="Deposito en Cuenta";
							$ls_pago=$row_dt["nomban"]." - ".$row_dt["ctaban"];
							break;
					}
					$li_porpagben=$row_dt["porpagben"];
					$li_monpagben=$row_dt["monpagben"];
					$li_monto=0;
					if($li_porpagben>0)
					{
						$li_monto=($li_neto*$li_porpagben)/100;
					}
					if($li_monpagben>0)
					{
						$li_monto=$li_monpagben;
						if($ls_tiporeporte==1)
						{
							$li_monto=$io_monedabsf->uf_convertir_monedabsf($li_monpagben,$_SESSION["la_empresa"]["candeccon"],$_SESSION["la_empresa"]["tipconmon"],1000,$_SESSION["la_empresa"]["redconmon"]);
						}
					}
					$li_montototalbene=$li_montototalbene+$li_monto;
					$li_monto=$io_fun_nomina->uf_formatonumerico($li_monto);
					$la_data[$li_s]=array('nro'=>$li_s,'cedula'=>$ls_cedben,'nombre'=>$ls_apenomben,'tipo'=>$ls_tipben,'formapago'=>$ls_forpagben,
					  					  'cheque'=>$ls_pago,'monto'=>$li_monto);
				}
				$io_report->io_sql->free_result($rs_data_dt);
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				$li_montototalbene=$io_fun_nomina->uf_formatonumerico($li_montototalbene);
				uf_print_piecabecera($li_s,$li_montototalbene,$io_pdf); // Imprimimos el pie de la cabecera
				if ($io_pdf->ezPageCount==$li_numpag)
				{// Hacemos el commit de los registros que se desean imprimir
					$io_pdf->transaction('commit');
				}
				else
				{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
					$io_pdf->transaction('rewind');
					$io_pdf->ezNewPage(); // Insertar una nueva página
					uf_print_cabecera($ls_codper,$ls_nomper,$li_monnet,$io_pdf); // Imprimimos la cabecera del registro
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					uf_print_piecabecera($li_s,$li_montototalbene,$io_pdf); // Imprimimos el pie de la cabecera
				}
				unset($la_data);
			}
		}
		$io_report->io_sql->free_result($rs_data);
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