<?PHP
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "opener.document.form1.submit();";
		print "close();";
		print "</script>";		
	}
	ini_set('memory_limit','2048M');
	ini_set('max_execution_time ','0');	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		    Acess: private 
		//	    Arguments: as_titulo: titulo del reporte
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing Laura Cabré basado en reportes del Ing. Nelson Barraez
		// Fecha Creación: 28/09/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(20,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,715,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(12,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,12,$as_titulo); // Agregar el título
		$io_pdf->addText(500,730,10,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($ls_banco,$ls_cuenta,$ls_chequera,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: $ls_banco Nombre del Banco
		//				   $ls_cuenta Numero de Cuenta
		//				   $ls_chuequera Numero de la chequera				  
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime los datos basicos del banco, la cuequera y la cuenta
		//	   Creado Por: Ing Laura Cabré basado en reportes del Ing. Nelson Barraez
		// Fecha Creación: 28/09/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
		$la_data=array(array('title'=>'<b>Chequera:</b>   ','title2'=>$ls_chequera),
					   array('title'=>'<b>Cuenta:</b>','title2'=>$ls_cuenta),
					   array('title'=>'<b>Banco:</b>   ','title2'=>$ls_banco));
		$la_columna=array('title'=>'','title2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>176, // Orientación de la tabla
						 'width'=>280, // Ancho de la tabla
						 'maxWidth'=>280,
						 'fontSize'=>11,
						 'cols'=>array('title'=>array('justification'=>'left','width'=>85),'title2'=>array('justification'=>'left','width'=>195))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
		unset($la_data);
		unset($la_columna);
		unset($la_config);		
		$la_data[1]=array('cheque'=>'<b>Cheque</b>','fecha'=>'<b>Fecha</b>','beneficiario'=>'<b>Proveedor/Beneficiario</b>','monto'=>'<b>Monto</b>','estado'=>'<b>Estado</b>');
		$la_columna=array('cheque'=>'','fecha'=>'','beneficiario'=>'','monto'=>'','estado'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
 						 'shadeCol2'=>array(0.85,0.85,0.85), // Color de la sombra
						 'shadeCol'=>array(1.5,1.5,1.5), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>524, // Ancho de la tabla
						 'maxWidth'=>524,
						 'fontSize'=>9,
						 'cols'=>array('cheque'=>array('justification'=>'center','width'=>90),
						 			   'fecha'=>array('justification'=>'center','width'=>70),'beneficiario'=>array('justification'=>'center','width'=>201),
									   'monto'=>array('justification'=>'center','width'=>94),'estado'=>array('justification'=>'center','width'=>70))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config); 
		
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_columna,$la_config,$la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 24/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_detalle
	
	//--------------------------------------------------------------------------------------------------------------------------------
	require_once("sigesp_scb_report.php");
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/class_datastore.php");
		
	$io_include = new sigesp_include();
	$ls_conect  = $io_include->uf_conectar();
	$io_sql     = new class_sql($ls_conect);	
	$io_report  = new sigesp_scb_report($ls_conect);
	$io_funcion = new class_funciones();				
	$ds_edocta  = new class_datastore();	
	
	$ls_codemp   = $_SESSION["la_empresa"]["codemp"];
	$ls_codban	 = $_GET["codban"];
	$ls_cuenta   = $_GET["cuenta"];
	$ls_chequera = $_GET["chequera"];	
	$ls_nomban   = $_GET["nomban"];
	
	$ls_tipbol      = 'Bs.';
	$ls_tiporeporte = 0;
	$ls_tiporeporte = $_GET["tiporeporte"];
	global $ls_tiporeporte;
	if ($ls_tiporeporte==1)
	   {
		 require_once("sigesp_scb_reportbsf.php");
		 $io_report = new sigesp_scb_reportbsf($ls_conect);
		 $ls_tipbol = 'Bs.F.';
	   }

	$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
	$io_pdf->ezSetCmMargins(3.5,3.5,3.5,3.5); // Configuración de los margenes en centímetros
	uf_print_encabezado_pagina("Listado de Chequeras $ls_tipbol",$io_pdf); // Imprimimos el encabezado de la página
	$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
	uf_print_cabecera($ls_nomban,$ls_cuenta,$ls_chequera,$io_pdf);//imprimiento la cabecera
	$la_datos=$io_report->uf_listado_cheques($ls_codban,$ls_cuenta,$ls_chequera);//Buscando la data a reportar
	$edocta->data=$la_datos;
	$li_filas=(count($la_datos, COUNT_RECURSIVE) / count($la_datos)) - 1;//$ds_edocta->getRowCount("codban");
	if($li_filas>0)
	{
		for($li_i=1;$li_i<=$li_filas;$li_i++)
		{
			$ls_cheque=$la_datos["numche"][$li_i];
			$ls_fecha=$io_funcion->uf_convertirfecmostrar($la_datos["fecmov"][$li_i]);
			$ls_beneficiario=trim($la_datos["nomproben"][$li_i]);
			$ls_monto=number_format(trim($la_datos["monto"][$li_i]),2,",",".");
			if($la_datos["monto"][$li_i]==0)
				$ls_estado="DISPONIBLE";
			else
				$ls_estado="    USADO     ";		
			$la_data[1]=array('cheque'=>$ls_cheque,'fecha'=>$ls_fecha." ",'beneficiario'=>$ls_beneficiario." ",'monto'=>" ".$ls_monto,'estado'=>$ls_estado);
			$la_columna=array('cheque'=>' ','fecha'=>' ','beneficiario'=>' ','monto'=>' ','estado'=>' '); 
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>2, // Sombra entre líneas
							 'shadeCol2'=>array(0.95,0.95,0.95), // Color de la sombra
							 'shadeCol'=>array(0,0,0), // Color de la sombra
							 'xOrientation'=>'center', // Orientación de la tabla
							 'width'=>524, // Ancho de la tabla
							 'maxWidth'=>524,
							 'fontSize'=>8,
							 'cols'=>array('cheque'=>array('justification'=>'center','width'=>90),
										   'fecha'=>array('justification'=>'center','width'=>70),'beneficiario'=>array('justification'=>'center','width'=>200),
										   'monto'=>array('justification'=>'right','width'=>94),'estado'=>array('justification'=>'center','width'=>70))); // Ancho Máximo de la tabla
			uf_print_detalle($la_columna,$la_config,$la_data,$io_pdf); // Imprimimos el detalle 
			
			unset($la_config);
			unset($la_data);
			unset($la_columna);
			
			$la_data_con[1]=array('cheque'=>' ','fecha'=>' ','beneficiario'=>' ','monto'=>' ','estado'=>' ');
			$la_columna_con=array('cheque'=>' ','fecha'=>' ','beneficiario'=>' ','monto'=>' ','estado'=>' ');
			$la_config_con=array('showHeadings'=>0, // Mostrar encabezados
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'xOrientation'=>'center', // Orientación de la tabla
							 'width'=>524, // Ancho de la tabla
							 'maxWidth'=>524,
							 'fontSize'=>6,
							'cols'=>array('cheque'=>array('justification'=>'center','width'=>90),
										   'fecha'=>array('justification'=>'center','width'=>70),'beneficiario'=>array('justification'=>'center','width'=>200),
										   'monto'=>array('justification'=>'right','width'=>94),'estado'=>array('justification'=>'center','width'=>70))); // Ancho Máximo de la tabla
			uf_print_detalle($la_columna_con,$la_config_con,$la_data_con,$io_pdf); // Imprimimos el detalle 
		}	
	}
	else
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print("close();");
		print("</script>");
	}
	$io_pdf->ezStopPageNumbers(1,1);
	$io_pdf->ezStream();
	unset($io_pdf);
	unset($io_report);
	unset($io_funcion);
?> 