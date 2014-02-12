<?PHP
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if (!array_key_exists("la_logusr",$_SESSION))
	   {
		 print "<script language=JavaScript>";
		 print "opener.document.form1.submit();";
		 print "close();";
		 print "</script>";		
	   }
	ini_set('memory_limit','1024M');
	ini_set('max_execution_time ','0');  
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_periodo,$ls_banco,$ls_nomtipcta,$ls_ctaban,$as_denctaban,$ldec_saldoant,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		    Acess: private 
		//	    Arguments: ldec_monto : Monto del cheque
		//	    		   ls_nomproben:  Nombre del proveedor o beneficiario
		//	    		   ls_monto : Monto en letras
		//	    		   ls_fecha : Fecha del cheque
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 25/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(30,65,750,65);		
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,550,12,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,540,11,$as_periodo); // Agregar el título
		$io_pdf->addText(700,580,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(700,570,8,date("h:i a")); // Agregar la Hora
        $io_pdf->setStrokeColor(0,0,0);
		$io_pdf->Rectangle(180,525,470,45);
		$io_pdf->line(30,65,750,65);
		
		$io_pdf->ezSetY(520);
		$la_data=array(array('title'=>'<b>Banco:</b>   ','title2'=>$ls_banco),
					   array('title'=>'<b>Tipo Cuenta:</b>','title2'=>$ls_nomtipcta),
					   array('title'=>'<b>Cuenta:</b>   ','title2'=>$ls_ctaban.' - '.$as_denctaban),
					   array('title'=>'<b>Saldo Anterior:</b>','title2'=>$ldec_saldoant));
		$la_columna=array('title'=>'','title2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>176, // Orientación de la tabla
						 'width'=>280, // Ancho de la tabla
						 'maxWidth'=>280,
						 'fontSize'=>9,
						 'cols'=>array('title'=>array('justification'=>'left','width'=>85),'title2'=>array('justification'=>'left','width'=>195))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
		unset($la_data);
		unset($la_columna);
		unset($la_config);		
 		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($li_totrow,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: ls_numdoc : Numero de documento
		//	    		   ls_nomban : Nombre del banco
		//				   ls_cbtan  : Cuenta del banco
		//				   ls_chevau : Voucher del cheuqe
		//				   ls_nomproben: Nombre del proveedor o beneficiario
		//				   ls_solicitudes: Solicitudes canceladas con el cheque					  
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime los datos basicos del cheque
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 24/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		if($li_totrow>0)
			{
			$la_data[0]=array('fecha'=>'<b>Fecha</b>','operacion'=>'<b>Operación</b>','numdoc'=>'<b>Documento</b>','beneficiario'=>'<b>Proveedor/Beneficiario</b>','descripcion'=>'<b>                         Descripción</b>','debito'=>'<b>Débitos</b>','credito'=>'<b>Créditos</b>','saldo'=>'<b>Saldo</b>');
			$la_columna=array('fecha'=>'','operacion'=>'','numdoc'=>'','beneficiario'=>'','descripcion'=>'','debito'=>'','credito'=>'','saldo'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>2, // Sombra entre líneas
							 'shadeCol2'=>array(0.85,0.85,0.85), // Color de la sombra
							 'shadeCol'=>array(1.5,1.5,1.5), // Color de la sombra
							 'xOrientation'=>'center', // Orientación de la tabla
							 'width'=>735, // Ancho de la tabla
							 'maxWidth'=>735,
							 'fontSize'=>9,
							 'cols'=>array('fecha'=>array('justification'=>'center','width'=>60),
											'operacion'=>array('justification'=>'center','width'=>55),
										   'numdoc'=>array('justification'=>'center','width'=>85),
										   'beneficiario'=>array('justification'=>'right','width'=>120),
										   'descripción'=>array('justification'=>'center','width'=>180),									   
										   'debito'=>array('justification'=>'right','widht'=>80),
										   'credito'=>array('justification'=>'right','width'=>80),
										   'saldo'=>array('justification'=>'right','width'=>80))); // Ancho Máximo de la tabla
								 
	
			$io_pdf->ezTable($la_data,$la_columna,'',$la_config); 
		}
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
	
	function uf_print_totales($ldec_debe,$ldec_haber,$ldec_saldoactual,&$io_pdf)
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

		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
		$la_data=array(array('fecha'=>'','operacion'=>'','numdoc'=>'','beneficiario'=>'','descripcion'=>'<b>Totales:</b>','debito'=>$ldec_debe,'credito'=>$ldec_haber,'saldo'=>$ldec_saldoactual));							  
		$la_columna=array('fecha'=>'','operacion'=>'','numdoc'=>'','beneficiario'=>'','descripcion'=>'','debito'=>'','credito'=>'','saldo'=>'');
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
 						 'shadeCol'=>array(0.95,0.95,0.95), // Color de la sombra
						 'shadeCol2'=>array(1.5,1.5,1.5), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>735, // Ancho de la tabla
						 'maxWidth'=>735,
						 'fontSize'=>8,
						 'cols'=>array('fecha'=>array('justification'=>'center','width'=>60),'operacion'=>array('justification'=>'center','width'=>55),
						 			   'numdoc'=>array('justification'=>'center','width'=>90),
									   'beneficiario'=>array('justification'=>'right','width'=>120),
									   'descripcion'=>array('justification'=>'right','width'=>175),									   
									   'debito'=>array('justification'=>'right','width'=>80),'credito'=>array('justification'=>'right','width'=>80),
									   'saldo'=>array('justification'=>'right','width'=>80))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
		
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	
	require_once("sigesp_scb_report.php");
	require_once('../../shared/class_folder/class_pdf.php');
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/class_datastore.php");
	
	$io_include   = new sigesp_include();
	$io_conect    = $io_include->uf_conectar();
	$io_sql       = new class_sql($io_conect);	
	$io_report    = new sigesp_scb_report($io_conect);
	$io_funciones = new class_funciones();				
	$ds_edocta    = new class_datastore();	
	
	$ls_codemp      = $_SESSION["la_empresa"]["codemp"];
	$ls_codban      = $_GET["codban"];
	$ls_nomban      = $_GET["nomban"];
	$ls_ctaban      = $_GET["ctaban"];
	$ls_denctaban   = $_GET["denctaban"];
	$ld_fecdesde    = $_GET["fecdes"];		
	$ld_fechasta    = $_GET["fechas"];	
	$ls_orden       = $_GET["orden"];
	$ls_tiprep      = $_GET["tiprep"];
	$ls_codconmov   = $_GET["codconmov"];
	$ls_tipbol      = 'Bs.';
	$ls_tiporeporte = 0;
	$ls_tiporeporte = $_GET["tiporeporte"];
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_scb_reportbsf.php");
		$io_report = new sigesp_scb_reportbsf($io_conect);
		$ls_tipbol = 'Bs.F.';
	}
	$data = $io_report->uf_generar_estado_cuenta($ls_codemp,$ls_codban,$ls_ctaban,$ls_orden,$ld_fecdesde,$ld_fechasta,&$ldec_saldoant,&$ldec_total_debe,&$ldec_total_haber,true,$ls_tiprep,$ls_codconmov);
	$ds_edocta->data=$data;
	$li_totrows = $ds_edocta->getRowCount("numdoc");

	set_time_limit(0);
    $io_pdf=new class_pdf('LETTER','landscape'); // Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
	$io_pdf->ezSetCmMargins(6,3.5,3.5,3.5); // Configuración de los margenes en centímetros
	$ls_nomtipcta     = $io_report->uf_select_data($io_sql,"SELECT nomtipcta FROM scb_tipocuenta t, scb_ctabanco c WHERE c.codemp='".$ls_codemp."' AND c.ctaban='".$ls_ctaban."' AND c.codtipcta=t.codtipcta ","nomtipcta");	
	$ldec_saldoactual = ($ldec_saldoant+$ldec_total_debe-$ldec_total_haber);
	$ld_saldo         = $ldec_saldoant;
	$ldec_saldoant    = number_format($ldec_saldoant,2,",",".");
	uf_print_encabezado_pagina("<b>Libro de Banco $ls_tipbol</b>","<b>Del</b> $ld_fecdesde <b>al</b> $ld_fechasta",$ls_nomban,$ls_nomtipcta,$ls_ctaban,$ls_denctaban,$ldec_saldoant,$io_pdf); // Imprimimos el encabezado de la página
	uf_print_cabecera($li_totrows,$io_pdf); // Imprimimos 
	$io_pdf->ezStartPageNumbers(720,50,10,'','',1); // Insertar el número de página
	if ($li_totrows>0)
	   {	
		 for ($li_i=1;$li_i<=$li_totrows;$li_i++)
		     {
		  	   $io_pdf->transaction('start'); // Iniciamos la transacción
			   $thisPageNum   = $io_pdf->ezPageCount;
			   $ldec_mondeb   = 0;
			   $ldec_monhab   = 0;
			   $li_totant     = 0;
			   $ls_numdoc      = $ds_edocta->getValue("numdoc",$li_i);
			   $ls_nomproben   = $ds_edocta->getValue("beneficiario",$li_i);		
			   $ls_conmov	   = $ds_edocta->getValue("conmov",$li_i);
			   $ls_operacion   = $ds_edocta->getValue("operacion",$li_i);
			   $ld_fecmov      = $io_funciones->uf_formatovalidofecha($ds_edocta->getValue("fecmov",$li_i));
			   $ld_fecmov      = $io_funciones->uf_convertirfecmostrar($ld_fecmov);
			   $ld_montotdeb   = $ds_edocta->getValue("debitos",$li_i);
			   $ld_montotcre   = $ds_edocta->getValue("creditos",$li_i);
			   $ld_saldo       = ($ld_saldo+$ld_montotdeb-$ld_montotcre);
			   $ld_montotdeb   = number_format($ld_montotdeb,2,",",".");
			   $ld_montotcre   = number_format($ld_montotcre,2,",",".");
			   $la_data[$li_i] = array('fecha'=>$ld_fecmov,'operacion'=>$ls_operacion,'numdoc'=>$ls_numdoc,'descripcion'=>$ls_conmov,'beneficiario'=>$ls_nomproben,'debito'=>$ld_montotdeb,'credito'=>$ld_montotcre,'saldo'=>number_format($ld_saldo,2,",","."));
		     }
		 $la_columna=array('fecha'=>'','operacion'=>'','numdoc'=>'','beneficiario'=>'','descripcion'=>'','debito'=>'','credito'=>'','saldo'=>'');
		 $la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.95,0.95,0.95), // Color de la sombra
						 'shadeCol'=>array(1.5,1.5,1.5), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>735, // Ancho de la tabla
						 'maxWidth'=>735,
						 'fontSize'=>8,
						 'cols'=>array('fecha'=>array('justification'=>'center','width'=>60),
									'operacion'=>array('justification'=>'center','width'=>55),
									   'numdoc'=>array('justification'=>'center','width'=>85),								    
									   'beneficiario'=>array('justification'=>'center','width'=>120),
									   'descripción'=>array('justification'=>'center','width'=>175),
									   'debito'=>array('justification'=>'right','widht'=>80),'credito'=>array('justification'=>'right','width'=>80),
									   'saldo'=>array('justification'=>'right','width'=>80))); // Ancho Máximo de la tabla
		uf_print_detalle($la_columna,$la_config,$la_data,$io_pdf); // Imprimimos el detalle 
		unset($la_data);
		uf_print_totales(number_format($ldec_total_debe,2,",","."),number_format($ldec_total_haber,2,",","."),number_format($ldec_saldoactual,2,",","."),$io_pdf); // Imprimimos el detalle
	   	$io_pdf->transaction('commit');
		$io_pdf->ezStopPageNumbers(1,1);
		$io_pdf->ezStream();
		unset($io_pdf);
		unset($io_report);
		unset($io_funciones);
	 }
  else
     {
	   print("<script language=JavaScript>");
	   print(" alert('No hay nada que Reportar');"); 
	   print(" close();");
	   print("</script>");
	 }
?> 