<?php
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if (!array_key_exists("la_logusr",$_SESSION))
	   {
		 print "<script language=JavaScript>";
		 print "opener.document.form1.submit();"	;	
		 print "close();";
		 print "</script>";		
	   }
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($ldec_monto,$ls_nomproben,$ls_monto,$ls_fecha,&$io_pdf)
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
		
		//Imprimo el monto
		$io_pdf->ezSetY(789);
		$la_data=array(array('1'=>' ','2'=>' ','monto'=>"    ***".$ldec_monto."***"),array('1'=>' ','2'=>' ','monto'=>''));
		$la_columna=array('1'=>' ','2'=>' ','monto'=>'     ');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('1'=>array('justification'=>'left','width'=>190),'2'=>array('justification'=>'left','width'=>190),
						 'monto'=>array('justification'=>'right','width'=>150))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		//Imprimo los datos del cheque
		
		$io_pdf->ezSetY(742);
		$la_data=array(array('space'=>'','data'=>' '),
					   array('space'=>'','data'=>$ls_nomproben),
					   array('space'=>'','data'=>$ls_monto),
					   array('space'=>'','data'=>' '),
					   array('space'=>'','data'=>' '),
					   array('space'=>'','data'=>$ls_fecha));
		$la_columna=array('space'=>'','data'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'fontSize' => 9, // Tamaño de Letras
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>398, // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('space'=>array('justification'=>'left','width'=>60),'data'=>array('justification'=>'left','width'=>520))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		//Imprimo los datos de endoso
		$la_data=array(array('1'=>' ','caduca'=>' ','3'=>' '),array('1'=>' ','caduca'=>'No Endosable','3'=>' '),array('1'=>' ','caduca'=>'Caduca a los 60 dias','3'=>' '),array('1'=>' ','caduca'=>' ','3'=>' '),array('1'=>' ','caduca'=>' ','3'=>' '));
		$la_columna=array('1'=>' ','caduca'=>' ','3'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 9, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>350, // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('1'=>array('justification'=>'left','width'=>160),'caduca'=>array('justification'=>'center','width'=>200),
						 '3'=>array('justification'=>'left','width'=>220))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_chevau,$ls_nomproben,$ls_solicitudes,$ls_conmov,&$io_pdf)
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
		
		$io_pdf->ezSetY(525);
		$la_data=array(array('banco'=>$ls_nomban,'cheque'=>$ls_numdoc,'cuenta'=>$ls_ctaban,'voucher'=>''));
		$la_columna=array('banco'=>'','cheque'=>'','cuenta'=>'','voucher'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('banco'=>array('justification'=>'center','width'=>160),'cheque'=>array('justification'=>'left','width'=>100),
						 'cuenta'=>array('justification'=>'left','width'=>160),'voucher'=>array('justification'=>'left','width'=>160))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
        
		$io_pdf->ezSetY(505);
		$la_data=array(array('ordenes'=>'                                        '.$ls_solicitudes),
					   array('ordenes'=>'                        '),
					   array('ordenes'=>'                        '.$ls_nomproben),
					   array('ordenes'=>'                    '.$ls_conmov));
		$la_columna=array('ordenes'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('ordenes'=>array('justification'=>'left','width'=>580))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
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
		
	    $io_pdf->ezSetY(400);
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize'=>9, // Tamaño de Letras
						 'titleFontSize'=>9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('estpro'=>array('justification'=>'center','width'=>125),
			 						   'spg_cuenta'=>array('justification'=>'center','width'=>70),
									   'monto_spg'=>array('justification'=>'right','width'=>100),
						 			   'scg_cuenta'=>array('justification'=>'center','width'=>85), // Justificación y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'haber'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$la_columnas=array('estpro'=>'','spg_cuenta'=>'','monto_spg'=>'','scg_cuenta'=>'','debe'=>'','haber'=>'');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	
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

	$data=$class_report->uf_cargar_chq_voucher($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
	$class_report->SQL->begin_transaction();
	$lb_valido=$class_report->uf_actualizar_status_impreso($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
	if(!$lb_valido)
	{
		print "Error al actualizar";
		$class_report->is_msg_error;	
		$class_report->SQL->rollback();
	}
	else
	{
		$class_report->SQL->commit();
	}
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
		$ls_ctaban=$ds_voucher->data["ctaban"][$li_i];
		$ls_chevau=$ds_voucher->data["chevau"][$li_i];
		$ld_fecmov=$io_funciones->uf_convertirfecmostrar($ds_voucher->data["fecmov"][$li_i]);
		$ls_nomproben=$ds_voucher->data["nomproben"][$li_i];
		$ls_solicitudes=$class_report->uf_select_solicitudes($ls_numdoc,$ls_codban,$ls_ctaban);
		$ls_conmov=$ds_voucher->getValue("conmov",$li_i);
		$ldec_monret=$ds_voucher->getValue("monret",$li_i);
		$ldec_monto=$ds_voucher->getValue("monto",$li_i);
		$ldec_total=$ldec_monto-$ldec_monret;
		//Asigno el monto a la clase numero-letras para la conversion.
		$numalet->setNumero($ldec_total);
		//Obtengo el texto del monto enviado.
		$ls_monto= $numalet->letra();
		uf_print_encabezado_pagina(number_format($ldec_total,2,",","."),$ls_nomproben,$ls_monto,$_SESSION["la_empresa"]["ciuemp"].", ".$ld_fecmov,$io_pdf); // Imprimimos el encabezado de la página
		uf_print_cabecera($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_chevau,$ls_nomproben,$ls_solicitudes,$ls_conmov,$io_pdf); // Imprimimos la cabecera del registro
		
		$ds_dt_scg->data=$class_report->uf_cargar_dt_scg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope); // Obtenemos el detalle del reporte
		$la_items = array('0'=>'scg_cuenta');
		$la_suma  = array('0'=>'monto');
		$ds_dt_scg->group_by($la_items,$la_suma,'scg_cuenta');
		$li_totrow_det=$ds_dt_scg->getRowCount("scg_cuenta");
		
		$ds_dt_spg->data=$class_report->uf_cargar_dt_spg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope);
		$la_items = array('0'=>'estpro','1'=>'spg_cuenta');
		$la_suma  = array('0'=>'monto');
		$ds_dt_spg->group_by($la_items,$la_suma,'spg_cuenta');
		$li_totrow_spg=$ds_dt_spg->getRowCount("spg_cuenta");
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Hago un ciclo para unir en una sola matriz los detalles de presupuesto y los contables para proceder luego a pintarlos
		if($li_totrow_det>=$li_totrow_spg)
		{
			for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
			{
				$ls_scg_cuenta=$ds_dt_scg->data["scg_cuenta"][$li_s];
				$ls_debhab = $ds_dt_scg->data["debhab"][$li_s];
				$ldec_monto= $ds_dt_scg->data["monto"][$li_s];
				if($ls_debhab=='D')
				{
					$ldec_mondeb=number_format($ldec_monto,2,",",".");
					$ldec_monhab=" ";
				}
				else
				{
					$ldec_monhab=number_format($ldec_monto,2,",",".");
					$ldec_mondeb=" ";
				}
				if(array_key_exists("spg_cuenta",$ds_dt_spg->data))
				{
					if(array_key_exists($li_s,$ds_dt_spg->data["spg_cuenta"]))
					{
						$ls_cuentaspg=$ds_dt_spg->getValue("spg_cuenta",$li_s);	
						$ls_estpro=$ds_dt_spg->getValue("estpro",$li_s);	  
						$ldec_monto_spg=number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
					}
					else
					{
						$ls_cuentaspg=" ";	
						$ls_estpro=" ";	  
						$ldec_monto_spg=" ";
					}
				}
				else
				{
					$ls_cuentaspg=" ";	
					$ls_estpro=" ";	  
					$ldec_monto_spg=" ";
				}
				$la_data[$li_s]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
			}
		}
		if($li_totrow_spg>$li_totrow_det)
		{
			for($li_s=1;$li_s<=$li_totrow_spg;$li_s++)
			{
				if(array_key_exists("scg_cuenta",$ds_dt_scg->data))
				{
					if(array_key_exists($li_s,$ds_dt_scg->data["scg_cuenta"]))
					{
						$ls_scg_cuenta=$ds_dt_scg->data["scg_cuenta"][$li_s];
						$ls_debhab = $ds_dt_scg->data["debhab"][$li_s];
						$ldec_monto= $ds_dt_scg->data["monto"][$li_s];
						if($ls_debhab=='D')
						{
							$ldec_mondeb=number_format($ldec_monto,2,",",".");
							$ldec_monhab=" ";
						}
						else
						{
							$ldec_monhab=number_format($ldec_monto,2,",",".");
							$ldec_mondeb=" ";
						}
					}
					else
					{
						$ls_scg_cuenta="";
						$ls_debhab = "";
						$ldec_monto= "";
						$ldec_mondeb="";
						$ldec_monhab="";					
					}
				}
				else
				{
					$ls_scg_cuenta="";
					$ls_debhab = "";
					$ldec_monto= "";
					$ldec_mondeb="";
					$ldec_monhab="";					
				}
				if(array_key_exists("spg_cuenta",$ds_dt_spg->data))
				{
					if(array_key_exists($li_s,$ds_dt_spg->data["spg_cuenta"]))
					{
						$ls_cuentaspg=$ds_dt_spg->getValue("spg_cuenta",$li_s);	
						$ls_estpro=$ds_dt_spg->getValue("estpro",$li_s);	  
						$ldec_monto_spg=number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
					}
					else
					{
						$ls_cuentaspg=" ";	
						$ls_estpro=" ";	  
						$ldec_monto_spg=" ";
					}
				}
				else
				{
					$ls_cuentaspg=" ";	
					$ls_estpro=" ";	  
					$ldec_monto_spg=" ";
				}
				$la_data[$li_s]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
			}
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(empty($la_data))
		{
			$ls_cuentaspg='';
			$ls_estpro='';
			$ldec_monto_spg='';
			$ls_scg_cuenta='';
			$ldec_mondeb='';
			$ldec_monhab='';
			$la_data[1]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
			$la_data[2]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
			$la_data[3]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
		}
	
		uf_print_detalle(array('title'=>'Detalle Presupuestario Pago','title2'=>'Detalle Contable Pago'),$la_data,$io_pdf);
		
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
