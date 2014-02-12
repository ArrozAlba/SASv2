<?php
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
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($ad_fechaanula,&$io_pdf)
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
		$ls_titulo="<b>COMPROBANTE DE ANULACIÓN DE CHEQUE</b>";
		$li_tm=$io_pdf->getTextWidth(11,$ls_titulo);
		$tm=296-($li_tm/2);
		$io_pdf->addText($tm,665,11,$ls_titulo); // Agregar el título
		$io_pdf->addText(495,675,9,$ad_fechaanula); // Agregar el título
		$io_pdf->addText(483,695,9,"<b>Fecha Anulación</b>"); // Agregar el título
        $io_pdf->Rectangle(480,665,79,40);
		$io_pdf->line(480,690,559,690);		
		
		//   Cuadro de Firmas	
        $io_pdf->Rectangle(45,60,510,70);
		$io_pdf->line(45,73,555,73);		
		$io_pdf->line(45,117,555,117);		
		$io_pdf->line(165,60,165,130);
            $io_pdf->line(290,60,290,130);		
		$io_pdf->line(430,60,430,130);		
		$io_pdf->addText(70,122,7,"ELABORADO POR"); // Agregar el título
	      $io_pdf->addText(190,122,7,"SOLICITADO POR"); // Agregar el título
		$io_pdf->addText(330,122,7,"VERIFICADO POR"); // Agregar el título
		$io_pdf->addText(460,122,7,"CONFIRMADO POR"); // Agregar el título

	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_numdoc,$ad_fecmov,$ad_fechaanula,$as_nomban,$as_nomproben,$as_conanu,$adec_total,&$io_pdf)
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
		$io_pdf->EzSetY(385);
		$la_data=array(array('numdoc'=>$as_numdoc,'fecmov'=>$ad_fecmov,'nomban'=>$as_nomban,'monto'=>$adec_total));
		$la_columna=array('numdoc'=>'CHEQUE','fecmov'=>'EMISIÓN','nomban'=>'BANCO','monto'=>'MONTO');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('numdoc'=>array('justification'=>'left','width'=>100),
						 			   'fecmov'=>array('justification'=>'left','width'=>80),
						 			   'nomban'=>array('justification'=>'left','width'=>250),
									   'monto'=>array('justification'=>'center','width'=>80))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data=array(array('nomproben'=>'<b>BENEFICIARIO:</b>'),array('nomproben'=>$as_nomproben));
		$la_columna=array('nomproben'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1	, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('nomproben'=>array('justification'=>'left','width'=>510))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data=array(array('conanu'=>'<b>MOTIVO DE LA ANULACIÓN:</b>'),array('conanu'=>$as_conanu));
		$la_columna=array('conanu'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('conanu'=>array('justification'=>'left','width'=>510))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
/*		$la_data=array(array('ordenes'=>'<b>Orden(es) de Pago(s):</b> '.$ls_solicitudes),
					   array('ordenes'=>'<b>Beneficiario:</b> '.$ls_nomproben),
					   array('ordenes'=>'<b>Concepto:</b> '.$ls_conmov));
		$la_columna=array('ordenes'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('ordenes'=>array('justification'=>'left','width'=>580))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);*/
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_title,$la_data,&$io_pdf)
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
		$la_data_title=array($la_title);
		$la_columna=array('title'=>'','title2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('title'=>array('justification'=>'center','width'=>350),'title2'=>array('justification'=>'center','width'=>230))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data_title,$la_columna,'',$la_config);	
		//Imprimo los detalles tanto `de presupuesto como contablwe del movimiento
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('estpro'=>array('justification'=>'center','width'=>195),
			 						   'spg_cuenta'=>array('justification'=>'center','width'=>80),
									   'monto_spg'=>array('justification'=>'right','width'=>75),
						 			   'scg_cuenta'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>75), // Justificación y ancho de la columna
						 			   'haber'=>array('justification'=>'right','width'=>75))); // Justificación y ancho de la columna
		$la_columnas=array('estpro'=>'<b>Programatica</b>',
						   'spg_cuenta'=>'<b>Cuenta</b>',
						   'monto_spg'=>'<b>Monto</b>',
						   'scg_cuenta'=>'<b>Cuenta</b>',
						   'debe'=>'<b>Debe</b>',
						   'haber'=>'<b>Haber</b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_autorizacion(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_autorizacion
		//		    Acess: private 
		//	    Arguments: io_pdf // Objeto PDF
		//    Description: función el final del voucher 
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 25/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$la_data[0]=array('elaborado'=>'<b>Elaborado por:</b>','revisado'=>'<b>Revisado por:</b>','presupuesto'=>'<b>Presupuesto</b>','administracion'=>'<b>Administracion</b>','presidencia'=>'<b>Presidencia</b>');
		$la_data[1]=array('elaborado'=>'','revisado'=>'','presupuesto'=>'','administracion'=>'','presidencia'=>'');
		$la_data[2]=array('elaborado'=>'','revisado'=>'','presupuesto'=>'','administracion'=>'','presidencia'=>'');
		$la_data[3]=array('elaborado'=>'','revisado'=>'','presupuesto'=>'','administracion'=>'','presidencia'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('elaborado'=>array('justification'=>'left','width'=>116),
			 						   'revisado'=>array('justification'=>'left','width'=>116),
									   'presupuesto'=>array('justification'=>'center','width'=>116),
						 			   'administracion'=>array('justification'=>'center','width'=>116), // Justificación y ancho de la columna
						 			   'presidencia'=>array('justification'=>'center','width'=>116))); // Justificación y ancho de la columna
		$la_columnas=array('elaborado'=>'','revisado'=>'','presupuesto'=>'','administracion'=>'','presidencia'=>'');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		
		$la_data=array(array('title'=>'<b>Recibi Conforme</b>  '));
		$la_columna=array('title'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('title'=>array('justification'=>'center','width'=>580))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		
		$la_data=array(array('nombre'=>'<b>Nombre:</b>  ','cedula'=>'<b>Cedula de Identidad:</b>  ','fecha'=>'<b>Fecha:</b>  ','firma'=>'<b>Firma:</b>  '),
						array('nombre'=>'','cedula'=>'','fecha'=>'','firma'=>''));
		$la_columna=array('nombre'=>'','cedula'=>'','fecha'=>'','firma'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>180),'cedula'=>array('justification'=>'left','width'=>120),
						 'fecha'=>array('justification'=>'left','width'=>100),'firma'=>array('justification'=>'left','width'=>180))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->ezText('                     ',10);
	}// end function uf_print_detalle
	function uf_convertir($ls_numero)
	{
		$ls_numero=str_replace(".","",$ls_numero);
		$ls_numero=str_replace(",",".",$ls_numero);
		return $ls_numero;
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_datastore.php");
	require_once("../../shared/class_folder/class_sql.php");
	$in=new sigesp_include();
	$con=$in->uf_conectar();
	$io_sql=new class_sql($con);	
	require_once("sigesp_scb_report.php");
	$class_report=new sigesp_scb_report($con);
	$io_funciones=new class_funciones();				
	$ds_voucher=new class_datastore();	
	$ds_dt_scg=new class_datastore();				
	$ds_dt_spg=new class_datastore();
	//Instancio a la clase de conversión de numeros a letras.
	include("../../shared/class_folder/class_numero_a_letra.php");
	$numalet= new class_numero_a_letra();
	//imprime numero con los valore por defecto
	//cambia a minusculas
	$numalet->setMayusculas(1);
	//cambia a femenino
	$numalet->setGenero(1);
	//cambia moneda
	$numalet->setMoneda("Bolivares");
	//cambia prefijo
	$numalet->setPrefijo("***");
	//cambia sufijo
	$numalet->setSufijo("***");
	//imprime numero con los cambios
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_codban=$_GET["codban"];
	$ls_ctaban=$_GET["ctaban"];
	$ls_numdoc=$_GET["numdoc"];
	$ls_chevau=$_GET["chevau"];
	$ls_codope=$_GET["codope"];				

	$data=$class_report->uf_cargar_chq_voucher_anulado($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
	$class_report->SQL->begin_transaction();
	/*$lb_valido=$class_report->uf_actualizar_status_impreso($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
	if(!$lb_valido)
	{
		print "Error al actualizar";
		$class_report->is_msg_error;	
		$class_report->SQL->rollback();
	}
	else
	{
		$class_report->SQL->commit();
	}*/
	$ds_voucher->data=$data;
	error_reporting(E_ALL);
	set_time_limit(1800);
	$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
	$io_pdf->ezSetCmMargins(0.5,3.5,1,1); // Configuración de los margenes en centímetros
	$li_totrow=$ds_voucher->getRowCount("numdoc");
	$io_pdf->transaction('start'); // Iniciamos la transacción
	$thisPageNum=$io_pdf->ezPageCount;
	for($li_i=1;$li_i<=$li_totrow;$li_i++)
	{
		unset($la_data);
		$li_totprenom=0;
		$ldec_mondeb=0;
		$ldec_monhab=0;
		$li_totant=0;
		$ls_numdoc=$ds_voucher->data["numdoc"][$li_i];
		$ls_codban=$ds_voucher->data["codban"][$li_i];
		$ls_nomban=$class_report->uf_select_data($io_sql,"SELECT * FROM scb_banco WHERE codban ='".$ls_codban."' AND codemp='".$ls_codemp."'","nomban");
		$ls_chevau=$ds_voucher->data["chevau"][$li_i];
		$ld_fecmov=$io_funciones->uf_convertirfecmostrar($ds_voucher->data["fecmov"][$li_i]);
		$ld_fechaanula=$io_funciones->uf_convertirfecmostrar($ds_voucher->data["fechaanula"][$li_i]);
		$ls_nomproben=$ds_voucher->data["nomproben"][$li_i];
		$ls_solicitudes=$class_report->uf_select_solicitudes($ls_numdoc,$ls_codban,$ls_ctaban);
		$ls_conanu=$ds_voucher->getValue("conanu",$li_i);
		$ldec_monret=$ds_voucher->getValue("monret",$li_i);
		$ldec_monto=$ds_voucher->getValue("monto",$li_i);
		$ldec_total=$ldec_monto-$ldec_monret;
		uf_print_encabezado_pagina($ld_fechaanula,&$io_pdf);
		uf_print_cabecera($ls_numdoc,$ld_fecmov,$ld_fechaanula,$ls_nomban,$ls_nomproben,$ls_conanu,$ldec_total,$io_pdf); // Imprimimos la cabecera del registro
		if ($io_pdf->ezPageCount==$thisPageNum)
		{// Hacemos el commit de los registros que se desean imprimir
			$io_pdf->transaction('commit');
		}
		else
		{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
			$io_pdf->transaction('rewind');
			$io_pdf->ezNewPage();
			uf_print_cabecera($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_chevau,$ls_nomproben,$ls_solicitudes,$ls_conmov,$io_pdf); // Imprimimos la cabecera del registro
			uf_print_detalle(array('title'=>'Detalle Presupuestario Pago','title2'=>'Detalle Contable Pago'),$la_data,$io_pdf); // Imprimimos el detalle 
			uf_print_autorizacion($io_pdf);
		}
	}
	$io_pdf->ezStopPageNumbers(1,1);
	$io_pdf->ezStream();
	unset($io_pdf);
	unset($class_report);
	unset($io_funciones);
?> 