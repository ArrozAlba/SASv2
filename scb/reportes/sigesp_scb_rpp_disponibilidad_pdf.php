<?php
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
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,730,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
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
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540,
						 'cols'=>array('title'=>array('justification'=>'center','width'=>540))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		
		$la_data=array(array('title'=>'<b>Fecha</b>  ','title2'=>'<b>Operación </b>  ','title3'=>'<b>Nº Documento</b>  ','title4'=>'<b>Beneficiario </b>  ','title5'=>'<b>Debitos </b>  ','title6'=>'<b>Creditos </b>  '));
		$la_columna=array('title'=>'','title2'=>'','title3'=>'','title4'=>'','title5'=>'','title6'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
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
		$io_pdf->ezSetDy(-5);
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
	require_once("sigesp_scb_c_report.php");
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_funciones.php");
    require_once("../../shared/class_folder/class_datastore.php");	
	$sig_inc	  = new sigesp_include();
	$con		  = $sig_inc->uf_conectar();
	$io_report    = new sigesp_scb_c_report($con);
	$io_funciones = new class_funciones();				
	$ds_edocta    = new class_datastore();	
	
	$ls_codemp      = $_SESSION["la_empresa"]["codemp"];
	$ld_fecha       = $_GET["fecha"];
	$ls_bancos      = $_GET["bancos"];
	$ls_tipbol      = 'Bs.';
	$ls_tiporeporte = 0;
	$ls_tiporeporte = $_GET["tiporeporte"];
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_scb_c_reportbsf.php");
		$io_report=new sigesp_scb_c_reportbsf($con);
		$ls_tipbol = 'Bs.F.';
	}
	$arr_bancos   = split("-",$ls_bancos);
	$li_totbancos = count($arr_bancos);
	$io_report->uf_cargar_disponibilidad($arr_bancos,$ld_fecha,"A");	
	
	error_reporting(E_ALL);
	set_time_limit(1800);
	$ldec_totaldebitos=0;
	$ldec_totalcreditos=0;
	$ldec_saldo=0;
	$li_totrow=$io_report->ds_disponibilidad->getRowCount("codban");	
	if($li_totrow<=0)
	{
		?>
		<script language=javascript>
		 alert('No hay datos a reportar');
		 close();
		</script>
		<?php
		exit();
	}
	$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
	$io_pdf->ezSetCmMargins(3.5,2,3.5,3.5); // Configuración de los margenes en centímetros
	uf_print_encabezado_pagina("<b>Disponibilidad Financiera $ls_tipbol </b>"," <b>Acumulada al ".$ld_fecha.'</b>',$io_pdf); // Imprimimos el encabezado de la página
	$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página

	for($i=1;$i<=$li_totrow;$i++)
	{
		$io_pdf->transaction('start'); // Iniciamos la transacción
		$thisPageNum=$io_pdf->ezPageCount;
		$li_totprenom=0;
		$ldec_mondeb=0;
		$ldec_monhab=0;
		$li_totant=0;
		$ls_codban=$io_report->ds_disponibilidad->getValue("codban",$i);
		$ls_nomban=$io_report->ds_disponibilidad->getValue("nomban",$i);	
		if($i>1)
		{
			$ls_nombananterior=$io_report->ds_disponibilidad->getValue("nomban",$i-1);
			if($ls_nomban==$ls_nombananterior)
			{
				$ls_nomban="";
			}				
		}
		$ls_ctaban			= $io_report->ds_disponibilidad->getValue("ctaban",$i);
		$ls_dencta			= $io_report->ds_disponibilidad->getValue("dencta",$i);
		$ls_codtipcta		= $io_report->ds_disponibilidad->getValue("codtipcta",$i);
		$ls_nomtipcta		= $io_report->ds_disponibilidad->getValue("nomtipcta",$i);
		$ldec_creditos		= $io_report->ds_disponibilidad->getValue("creditos",$i);
		$ldec_debitos		= $io_report->ds_disponibilidad->getValue("debitos",$i);
		$ldec_saldo			= $io_report->ds_disponibilidad->getValue("saldo",$i);//Saldo de la cuenta	
		$ldec_totaldebitos  = $ldec_totaldebitos+$ldec_debitos;//Acumulador del total de debitos
		$ldec_totalcreditos = $ldec_totalcreditos+$ldec_creditos;//Acumulador del total de creditos
		$la_data[$i]        = array('nomban'=>$ls_nomban,'ctaban'=>$ls_ctaban,'tipcta'=>$ls_nomtipcta,'debitos'=>$io_funciones->iif_string("$ldec_debitos>=0",number_format($ldec_debitos,2,",","."),"(".number_format($ldec_debitos,2,",",".").")"),'creditos'=>$io_funciones->iif_string("$ldec_creditos>=0",number_format($ldec_creditos,2,",","."),"(".number_format($ldec_creditos,2,",",".").")"),'saldo'=>$io_funciones->iif_string("$ldec_saldo>=0",number_format($ldec_saldo,2,",","."),"(".number_format($ldec_saldo,2,",",".").")"));						
	}
	$la_columna=array('nomban'=>'<b>Banco</b>','ctaban'=>'<b>Cuenta</b>','tipcta'=>'<b>Tipo Cuenta</b>','debitos'=>'<b>Debitos</b>','creditos'=>'<b>Creditos</b>','saldo'=>'<b>Saldo</b>');
	$la_config=array('showHeadings'=>1, // Mostrar encabezados
			 'showLines'=>0, // Mostrar Líneas
			 'shaded'=>0, // Sombra entre líneas
			 'shadeCol'=>array(0.95,0.95,0.95), // Color de la sombra
			 'shadeCol2'=>array(1.5,1.5,1.5), // Color de la sombra
			 'xOrientation'=>'center', // Orientación de la tabla
			 'width'=>540, // Ancho de la tabla
			 'maxWidth'=>540,
			 'fontSize'=>8,
			 'titleFontSize'=>9,
			 'cols'=>array('nomban'=>array('justification'=>'left','width'=>90),'ctaban'=>array('justification'=>'center','width'=>140),
						   'tipcta'=>array('justification'=>'center','width'=>70),'debitos'=>array('justification'=>'right','width'=>80),
						   'creditos'=>array('justification'=>'right','width'=>80),'saldo'=>array('justification'=>'right','width'=>80))); // Ancho Máximo de la tabla
	uf_print_detalle($la_columna,$la_config,$la_data,$io_pdf); // Imprimimos el detalle 
	unset($la_data);
	$ldec_saldo_total=$ldec_totaldebitos-$ldec_totalcreditos;
	uf_print_totales(number_format($ldec_totaldebitos,2,",","."),number_format($ldec_totalcreditos,2,",","."),number_format($ldec_saldo_total,2,",","."),$io_pdf); // Imprimimos el detalle	 
	$io_pdf->transaction('commit');
	$io_pdf->ezStopPageNumbers(1,1);
	$io_pdf->ezStream();
	unset($io_pdf,$io_report,$io_funciones);
?> 