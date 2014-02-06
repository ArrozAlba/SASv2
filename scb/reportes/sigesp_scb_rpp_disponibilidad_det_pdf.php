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
	ini_set('memory_limit','1024M');
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
		$io_pdf->line(20,40,730,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,560,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,548,11,$as_periodo); // Agregar el título
		$io_pdf->addText(680,560,10,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');		
	}// end function uf_print_encabezadopagina
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
	
	function uf_print_totales($ldec_saldo_ant,$ldec_debe,$ldec_haber,$ldec_saldoactual,&$io_pdf)
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
		
		$la_data[1]=array('title1'=>'','title2'=>'','title3'=>'','title4'=>'','title5'=>'<b>Saldos Generales:</b>','anterior'=>'<b>'.$ldec_saldo_ant.'</b>',
						   'debitos'=>'<b>'.$ldec_debe.'</b>','creditos'=>'<b>'.$ldec_haber.'</b>','saldo'=>'<b>'.$ldec_saldoactual.'</b>');	
	
		$la_columna=array('title1'=>'','title2'=>'','title3'=>'','title4'=>'','title5'=>'','anterior'=>'',
						   'debitos'=>'','creditos'=>'','saldo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
					 	 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>730, // Ancho de la tabla
						 'maxWidth'=>730,
						 'fontSize'=>8,
						 'cols'=>array('title1'=>array('justification'=>'right','width'=>60),'title2'=>array('justification'=>'right','width'=>50),
									   'title3'=>array('justification'=>'right','width'=>100),'title4'=>array('justification'=>'right','width'=>100),
									   'title5'=>array('justification'=>'right','width'=>100),'anterior'=>array('justification'=>'right','width'=>80),
									   'debitos'=>array('justification'=>'right','width'=>80),'creditos'=>array('justification'=>'right','width'=>80),
									   'saldo'=>array('justification'=>'right','width'=>80)));
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);

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
		
	$ld_fecha       = $_GET["fecha"];
	$ls_bancos      = $_GET["bancos"];
	$arr_bancos     = split("-",$ls_bancos);
	$ls_tipbol      = 'Bs.';
	$ls_tiporeporte = 0;
	$ls_tiporeporte = $_GET["tiporeporte"];
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_scb_c_reportbsf.php");
		$io_report = new sigesp_scb_c_reportbsf($con);
		$ls_tipbol = 'Bs.F.';
	}
	$li_totbancos=count($arr_bancos);
	$io_report->uf_cargar_disponibilidad($arr_bancos,$ld_fecha,"D");
	
	error_reporting(E_ALL);
	set_time_limit(1800);
	$ldec_totaldebitos=0;
	$ldec_totalcreditos=0;
	$ldec_total_saldo_ant=0;
	$ldec_aux=0;
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
	$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
	$io_pdf->ezSetCmMargins(3.5,3.5,3.5,3.5); // Configuración de los margenes en centímetros
	uf_print_encabezado_pagina("<b>Disponibilidad Financiera $ls_tipbol</b>","<b>Detallada al</b> ".$ld_fecha,$io_pdf); // Imprimimos el encabezado de la página
	$io_pdf->ezStartPageNumbers(700,50,10,'','',1); // Insertar el número de página
	$li_aux=0;
	$ldec_mondeb=0;
	$ldec_monhab=0;
	$ldec_monant=0;
	for($i=1;$i<=$li_totrow;$i++)
	{
		$li_aux=$li_aux+1;
		$li_totprenom=0;
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
		$ls_ctaban			  = $io_report->ds_disponibilidad->getValue("ctaban",$i);
		$ls_dencta			  = $io_report->ds_disponibilidad->getValue("dencta",$i);
		$ls_codtipcta		  = $io_report->ds_disponibilidad->getValue("codtipcta",$i);
		$ls_nomtipcta		  = $io_report->ds_disponibilidad->getValue("nomtipcta",$i);
		$ldec_creditos		  = $io_report->ds_disponibilidad->getValue("creditos",$i);
		$ldec_debitos		  = $io_report->ds_disponibilidad->getValue("debitos",$i);
		$ldec_saldo_ant		  = $io_report->ds_disponibilidad->getValue("saldo_anterior",$i);//Saldo de la cuenta
		$ldec_total_saldo_ant = $ldec_total_saldo_ant+$ldec_saldo_ant;
		$ldec_totaldebitos	  = $ldec_totaldebitos+$ldec_debitos;//Acumulador del total de debitos
		$ldec_totalcreditos	  = $ldec_totalcreditos+$ldec_creditos;//Acumulador del total de creditos
		$ls_sc_cuenta		  = $io_report->ds_disponibilidad->getValue("sc_cuenta",$i);//Cuenta contable
		$ls_fecha			  = $io_report->ds_disponibilidad->getValue("fecapr",$i);//Fecha apertura
		$ldec_saldo			  = $ldec_saldo_ant+$ldec_debitos-$ldec_creditos;
		$ldec_aux			  = $ldec_aux+$ldec_saldo;
		 if(!empty($ls_nomban))
		 {
			 if($i>1)
			 {
			  
			  $la_data[$li_aux]=array('nomban'=>'','tipcta'=>'','dencta'=>'','ctaban'=>'','periodo'=>'<b>Total:</b>','anterior'=>number_format($ldec_monant,2,",","."),
						   'debitos'=>number_format($ldec_mondeb,2,",","."),'creditos'=>number_format($ldec_monhab,2,",","."),'saldo'=>number_format($ldec_aux,2,",","."));
		      $li_aux=$li_aux+1;
			  $la_data[$li_aux]=array('nomban'=>'','tipcta'=>'','dencta'=>'','ctaban'=>'','periodo'=>'','anterior'=>'',
						   'debitos'=>'','creditos'=>'','saldo'=>'');		 	
			  $ldec_aux=0;
   	 		  $ldec_mondeb=0;
			  $ldec_monhab=0;
		 	  $ldec_monant=0;
			  $li_aux=$li_aux+1;
			 }				   
		 }
		 $la_data[$li_aux]=array('nomban'=>'<b>'.$ls_nomban.'</b>','tipcta'=>$ls_nomtipcta,'dencta'=>$ls_dencta,'ctaban'=>$ls_ctaban,'periodo'=>$ls_fecha,'anterior'=>$io_funciones->iif_string("$ldec_saldo_ant>=0",number_format($ldec_saldo_ant,2,",","."),"(".number_format($ldec_saldo_ant,2,",",".").")"),
						  'debitos'=>$io_funciones->iif_string("$ldec_debitos>=0",number_format($ldec_debitos,2,",","."),"(".number_format($ldec_debitos,2,",",".").")") ,'creditos'=>$io_funciones->iif_string("$ldec_creditos>=0",number_format($ldec_creditos,2,",","."),"(".number_format($ldec_creditos,2,",",".").")"),'saldo'=>$io_funciones->iif_string("$ldec_saldo>=0",number_format($ldec_saldo,2,",","."),"(".number_format($ldec_saldo,2,",",".").")"));						

		 if($i==$li_totrow)
		 {
			 $ldec_mondeb=$ldec_mondeb+$ldec_debitos;
			 $ldec_monhab=$ldec_monhab+$ldec_creditos;
			 $ldec_monant=$ldec_monant+$ldec_saldo_ant;
			 $li_aux=$li_aux+1;
			 $la_data[$li_aux]=array('nomban'=>'','tipcta'=>'','dencta'=>'','ctaban'=>'','periodo'=>'<b>Total:</b>','anterior'=>number_format($ldec_monant,2,",","."),
						   'debitos'=>number_format($ldec_mondeb,2,",","."),'creditos'=>number_format($ldec_monhab,2,",","."),'saldo'=>number_format($ldec_aux,2,",","."));						  		 	
			 $ldec_aux=0;				   
		 }
		 $ldec_mondeb=$ldec_mondeb+$ldec_debitos;
		 $ldec_monhab=$ldec_monhab+$ldec_creditos;
		 $ldec_monant=$ldec_monant+$ldec_saldo_ant;	
						   
	}
	$la_columna=array('nomban'=>'<b>Banco</b>','tipcta'=>'<b>Tipo Cuenta</b>','dencta'=>'<b>Denominacion Cta.</b>','ctaban'=>'<b>Cuenta</b>','periodo'=>'<b>Año</b>','anterior'=>'<b>Anterior</b>',
					  'debitos'=>'<b>debitos</b>','creditos'=>'<b>Creditos</b>','saldo'=>'<b>Saldo</b>');
	$la_config=array('showHeadings'=>1, // Mostrar encabezados
			 'showLines'=>0, // Mostrar Líneas
			 'shaded'=>0, // Sombra entre líneas
			 'shadeCol'=>array(0.95,0.95,0.95), // Color de la sombra
			 'shadeCol2'=>array(1.5,1.5,1.5), // Color de la sombra
			 'xOrientation'=>'center', // Orientación de la tabla
			 'width'=>730, // Ancho de la tabla
			 'maxWidth'=>730,
			 'fontSize'=>8,
			 'titleFontSize'=>10,
			 'cols'=>array('nomban'=>array('justification'=>'left','width'=>85),'tipcta'=>array('justification'=>'center','width'=>80),
						   'dencta'=>array('justification'=>'center','width'=>80),'ctaban'=>array('justification'=>'center','width'=>130),
						   'periodo'=>array('justification'=>'center','width'=>35),'anterior'=>array('justification'=>'right','width'=>80),
						   'debitos'=>array('justification'=>'right','width'=>80),'creditos'=>array('justification'=>'right','width'=>80),
						   'saldo'=>array('justification'=>'right','width'=>80))); // Ancho Máximo de la tabla
	uf_print_detalle($la_columna,$la_config,$la_data,$io_pdf); // Imprimimos el detalle 
	unset($la_data);
	$ldec_saldo_total=$ldec_total_saldo_ant+$ldec_totaldebitos-$ldec_totalcreditos;
	uf_print_totales(number_format($ldec_total_saldo_ant,2,",","."),number_format($ldec_totaldebitos,2,",","."),number_format($ldec_totalcreditos,2,",","."),number_format($ldec_saldo_total,2,",","."),$io_pdf); // Imprimimos el detalle
	$io_pdf->ezStopPageNumbers(1,1);
	$io_pdf->ezStream();
	unset($io_pdf);
	unset($io_report);
	unset($io_funciones);
?> 