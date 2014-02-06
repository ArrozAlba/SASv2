<?PHP
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	//header('Content-type: application/pdf');
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "</script>";		
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_periodo,&$io_pdf)
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
		$io_pdf->line(20,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/logo.jpg',30,700,130); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,718,11,$as_periodo); // Agregar el título
		$io_pdf->addText(500,730,10,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($ls_banco,$ls_ctaban,$ls_nomtipcta,$ldec_saldoant,&$io_pdf)
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
				
		$la_data=array(array('title'=>'<b>Banco:</b>   ','title2'=>$ls_banco),
					   array('title'=>'<b>Tipo Cuenta:</b>','title2'=>$ls_nomtipcta),
					   array('title'=>'<b>Cuenta:</b>   ','title2'=>$ls_ctaban),
					   array('title'=>'<b>Saldo Anterior:</b>','title2'=>$ldec_saldoant));
		$la_columna=array('title'=>'','title2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>176, // Orientación de la tabla
						 'width'=>280, // Ancho de la tabla
						 'maxWidth'=>280,
						 'cols'=>array('title'=>array('justification'=>'left','width'=>85),'title2'=>array('justification'=>'left','width'=>195))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
		
		$la_data=array(array('title'=>'<b>Detalles del Estado de Cuenta</b>  '));
		$la_columna=array('title'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>1, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('title'=>array('justification'=>'center','width'=>540))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		
		$la_data=array(array('title'=>'<b>Fecha</b>  ','title2'=>'<b>Operación </b>  ','title3'=>'<b>Nº Documento</b>  ','title4'=>'<b>Beneficiario </b>  ','title5'=>'<b>Debitos </b>  ','title6'=>'<b>Creditos </b>  '));
		$la_columna=array('title'=>'','title2'=>'','title3'=>'','title4'=>'','title5'=>'','title6'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('title'=>array('justification'=>'center','width'=>70),'title2'=>array('justification'=>'center','width'=>70),
										'title3'=>array('justification'=>'center','width'=>100),'title4'=>array('justification'=>'left','width'=>140),
										'title5'=>array('justification'=>'center','width'=>80),'title6'=>array('justification'=>'center','width'=>80))); // Ancho Máximo de la tabla
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
		global $io_fun_nomina;

		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
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
		$io_pdf->ezSetY(170);
		$la_data=array(array('item'=>'<b>Total Débitos:</b>','saldo'=>$ldec_debe),
							  array('item'=>'<b>Total Créditos:</b>','saldo'=>$ldec_haber),
							  array('item'=>'<b>Saldo Actual:</b>','saldo'=>$ldec_saldoactual));
		$la_columna=array('item'=>'','saldo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
					 	'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>'470', // Orientación de la tabla
						 'width'=>210, // Ancho de la tabla
						 'maxWidth'=>210,
						 'cols'=>array('item'=>array('justification'=>'right','width'=>100),
									   'saldo'=>array('justification'=>'right','width'=>110)));
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
		
	}// end function uf_print_detalle

	//--------------------------------------------------------------------------------------------------------------------------------
	require_once("../shared/ezpdf/class.ezpdf.php");
	require_once("../shared/class_folder/sigesp_include.php");
	$in=new sigesp_include();
	$con=$in->uf_conectar();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql=new class_sql($con);	
	require_once("sigesp_scb_report.php");
	$class_report=new sigesp_scb_report($con);
	require_once("../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../shared/class_folder/class_datastore.php");
	$ds_edocta=new class_datastore();	
	
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_codban=$_GET["codban"];
	$ls_ctaban=$_GET["ctaban"];
	$ld_fecdesde=$_GET["fecdes"];		
	$ld_fechasta=$_GET["fechas"];	
		
	$data=$class_report->uf_generar_estado_cuenta($ls_codban,$ls_ctaban,$ld_fecdesde,$ld_fechasta,&$ldec_saldoant,&$ldec_total_debe,&$ldec_total_haber);
	$ds_edocta->data=$data;
	error_reporting(E_ALL);
	set_time_limit(1800);

	$li_totrow=$ds_edocta->getRowCount("numdoc");

	if($li_totrow<=0)
	{
		?>
		<script language=javascript>
		 alert('No hay datos a reportar');
		 close();
		</script>
		<?
		exit();
	}
	$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
	$io_pdf->ezSetCmMargins(3.5,3.5,3.5,3.5); // Configuración de los margenes en centímetros
	uf_print_encabezado_pagina("Estado de Cuenta","Del $ld_fecdesde al $ld_fechasta",$io_pdf); // Imprimimos el encabezado de la página
	$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
	$ldec_saldoactual=$ldec_saldoant+$ldec_total_debe-$ldec_total_haber;
	$ldec_saldoant=number_format($ldec_saldoant,2,",",".");
	$ls_nomban=$ds_edocta->getValue("nomban",1);
	$ls_nomtipcta=$ds_edocta->getValue("nomtipcta",1);
	uf_print_cabecera($ls_nomban,$ls_ctaban,$ls_nomtipcta,$ldec_saldoant,$io_pdf); // Imprimimos la cabecera del registro
	for($li_i=1;$li_i<=$li_totrow;$li_i++)
	{
		$io_pdf->transaction('start'); // Iniciamos la transacción
		$thisPageNum=$io_pdf->ezPageCount;
		$li_totprenom=0;
		$ldec_mondeb=0;
		$ldec_monhab=0;
		$li_totant=0;
		//unset($la_data);
		$ls_numdoc=$ds_edocta->getValue("numdoc",$li_i);
		$ls_codban=$ds_edocta->getValue("codban",$li_i);
		$ls_nomban=$ds_edocta->getValue("nomban",$li_i);
		$ls_ctaban=$ds_edocta->getValue("ctaban",$li_i);
		$ls_nomproben=$ds_edocta->getValue("beneficiario",$li_i);		
		$ls_conmov=$ds_edocta->getValue("conmov",$li_i);
		$ldec_monret=$ds_edocta->getValue("monret",$li_i);
		$ldec_monto=$ds_edocta->getValue("monto",$li_i);
		$ls_nomtipcta=$ds_edocta->getValue("nomtipcta",$li_i);
		$ls_operacion=$ds_edocta->getValue("operacion",$li_i);
		$ld_fecmov=$io_funciones->uf_convertirfecmostrar($ds_edocta->getValue("fecmov",$li_i));
		$ldec_total=$ldec_monto-$ldec_monret;
		$ldec_mondeb=number_format($ds_edocta->getValue("debitos",$li_i),2,",",".");
		$ldec_monhab=number_format($ds_edocta->getValue("creditos",$li_i),2,",",".");
		$la_data[$li_i]=array('fecha'=>$ld_fecmov,'operacion'=>$ls_operacion,'numdoc'=>$ls_numdoc,'beneficiario'=>$ls_nomproben,'debito'=>$ldec_mondeb,'credito'=>$ldec_monhab);
		$la_columna=array('fecha'=>'','operacion'=>'','numdoc'=>'','beneficiario'=>'','debito'=>'','credito'=>'');
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
 						 'shadeCol'=>array(0.95,0.95,0.95), // Color de la sombra
						 'shadeCol2'=>array(1.5,1.5,1.5), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('fecha'=>array('justification'=>'center','width'=>70),'operacion'=>array('justification'=>'center','width'=>70),
						 			   'numdoc'=>array('justification'=>'center','width'=>100),'beneficiario'=>array('justification'=>'left','width'=>140),
									   'debito'=>array('justification'=>'right','width'=>80),'credito'=>array('justification'=>'right','width'=>80))); // Ancho Máximo de la tabla
		//uf_print_autorizacion($io_pdf);	
		
		
	}

	uf_print_detalle($la_columna,$la_config,$la_data,$io_pdf); // Imprimimos el detalle 
	unset($la_data);
	uf_print_totales(number_format($ldec_total_debe,2,",","."),number_format($ldec_total_haber,2,",","."),number_format($ldec_saldoactual,2,",","."),$io_pdf); // Imprimimos el detalle
	 
	/*if ($io_pdf->ezPageCount==$thisPageNum)
	{// Hacemos el commit de los registros que se desean imprimir*/
		$io_pdf->transaction('commit');
	/*}
	else
	{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
		$io_pdf->transaction('rewind');
		$io_pdf->ezNewPage();
		uf_print_cabecera($ls_numdoc,$ls_codban,$ls_ctaban,$ls_chevau,$io_pdf); // Imprimimos la cabecera del registro
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		uf_print_autorizacion($io_pdf);
	}*/
	$io_pdf->ezStopPageNumbers(1,1);
	$io_pdf->ezStream();
	unset($io_pdf);
	unset($class_report);
	unset($io_funciones);
?> 