<?php
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");//Corpocentro.
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "opener.document.form1.submit();"	;	
		print "close();";
		print "</script>";		
	}	
	$x_pos=0;//mientras mas grande el numero, mas a la derecha.
	$y_pos=-1;//Mientras mas pequeño el numero, mas alto.
	$ls_directorio="cheque_configurable";
	
	//-------------------------------------------------------------------------------------------------
	function uf_inicializar_variables($as_archivo,$as_contenido,$ai_medidas)
	{
		global $valores;
		global $ls_directorio;
		
		if(!file_exists ($ls_directorio))
		{
			$lb_exito=mkdir($ls_directorio,0777);
			if(!$lb_exito)
			{
				print "<script>";
				print "alert('Error al crear directorio cheque_configurable');";
				print "close();";
				print "</script>";
			}
		}
		
		if((!file_exists ($as_archivo)) || (filesize($as_archivo)==0))
		{	
			if(file_exists ($ls_directorio))
			{
				$archivo = fopen($as_archivo, "w");			
				fwrite($archivo,$as_contenido);
				fclose($archivo);
			}
		}
			
		if((file_exists($as_archivo)) && (filesize($as_archivo)>0))
		{
			$archivo = fopen($as_archivo, "r");
			$contenido = fread($archivo, filesize($as_archivo));			
			fclose($archivo);			
			$valores = explode("-",$contenido);	
			if(count($valores)<>$ai_medidas)
			{
				$archivo = fopen($as_archivo, "w");
				fclose($archivo);			
				print "<script>";
				print "alert('Ocurrio un error, por favor cargue de nuevo el cheque (Las medidas seran inicializadas por fallo de lectura y escritura)');";
				print "close();";
				print "</script>";
			}
		}
		else
		{
			print "<script>";
			print "alert('Ocurrio un error, por favor cargue de nuevo el cheque (Las medidas seran inicializadas por fallo de lectura y escritura)');";
			print "close();";
			print "</script>";
		}
	}	
	
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
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 25/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $valores;
		//Imprimo el monto
		$io_pdf->add_texto($valores[0],$valores[1],10,"<b>***".$ldec_monto."***</b>");
		//Beneficiario del Cheque
		$io_pdf->add_texto($valores[2],$valores[3],11,"<b>$ls_nomproben</b>");
		//Monto en letras del Cheque
		//Cortando el monto en caso de que sea muy largo		
		$ls_monto_cortado=wordwrap($ls_monto,70,"?");
		$la_arreglo=array();
		$la_arreglo=explode("?",$ls_monto_cortado);
		if(array_key_exists(0,$la_arreglo))
			$io_pdf->add_texto($valores[4],$valores[5],9,"<b>$la_arreglo[0]</b>");
		if(array_key_exists(1,$la_arreglo))
			$io_pdf->add_texto($valores[6],$valores[7],9,"<b>$la_arreglo[1]</b>");
		$ls_anio=substr($ls_fecha,-4);
		$ls_fecha_corta=substr($ls_fecha,0,(strlen($ls_fecha)-5));	
		//Fecha del Cheque
		$io_pdf->add_texto($valores[8],$valores[9],9,"<b>$ls_fecha_corta</b>");
		$io_pdf->add_texto($valores[10],$valores[11],9,"<b>$ls_anio</b>");	
		$io_pdf->add_texto($valores[12],$valores[13],9,"<b>NO ENDOSABLE</b>");
		$io_pdf->add_texto($valores[14],$valores[15],9,"<b>CADUCA A LOS ".$_SESSION["la_empresa"]["diacadche"]." DIAS</b>");		
	}// end function uf_print_encabezadopagina.

	function uf_print_cabecera($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_chevau,$ls_nomproben,$ls_solicitudes,$ls_conmov,$ls_codban,$ls_fecha,$la_data,$ls_cedbene,&$io_pdf)
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
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 02/04/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_pdf->setStrokeColor(0,0,0);
		if ($ls_codban=='001')
		{
			$li_pos=235;
			$io_pdf->convertir_valor_mm_px($li_pos);
			$io_pdf->ezSetY($li_pos);
			$li_totrow = count($la_data);
            $ls_forpre= $_SESSION["la_empresa"]["formpre"];
			$ld_montotdeb=$ld_montothab=0;
			for ($li_i=1;$li_i<=$li_totrow;$li_i++)
			    {
				  $ls_spgcuenta = $la_data[$li_i]["spg_cuenta"];
				  if (!empty($ls_spgcuenta))
				     {
					   $ls_anio=substr($ls_fecha,-4);
					   $ls_codestpro = $la_data[$li_i]["estpro"];
					   $ls_codestpro1 = substr($ls_codestpro,0,2);
					   $ls_codestpro2 = substr($ls_codestpro,3,2);
					   $ls_codestpro3 = substr($ls_codestpro,6,2);
					   $ls_codestpro4 = substr($ls_codestpro,9,2);
					   $ls_codestpro5 = substr($ls_codestpro,-2);
					  
					   $ls_spgcuenta1 = substr($ls_spgcuenta,0,3);
					   $ls_spgcuenta2 = substr($ls_spgcuenta,3,2);
					   $ls_spgcuenta3 = substr($ls_spgcuenta,5,2);
					   $ls_spgcuenta4 = substr($ls_spgcuenta,7,2);
					   $ls_montospg = $la_data[$li_i]["monto_spg"];
					  
					  
					   $la_datapre[$li_i] = array('0'=>$ls_anio,'1'=>$ls_solicitudes,'2'=>$ls_codestpro1,
											      '3'=>$ls_codestpro2,'4'=>$ls_codestpro3,'5'=>$ls_codestpro4,
											      '6'=>$ls_codestpro5,'7'=>$ls_spgcuenta1,'8'=>$ls_spgcuenta2,
											      '9'=>$ls_spgcuenta3,'10'=>$ls_spgcuenta4,'11'=>$ls_montospg);
					 
					 }				  
				  $ls_scgcuenta = $la_data[$li_i]["scg_cuenta"];
				  if (!empty($ls_scgcuenta))
				     {
					   $ls_denominacion = $la_data[$li_i]["denominacion"];
					   $ls_debe = $la_data[$li_i]["debe"];
					   $ld_mondeb = str_replace(',','.',$ls_debe);
					   $ld_montotdeb+=$ld_mondeb;
					   $ls_haber = $la_data[$li_i]["haber"];
					   $ld_monhab = str_replace(',','.',$ls_haber);
					   $ld_montothab+=$ld_monhab;
					   $ls_scgcuenta1 = substr($ls_scgcuenta,0,3);
					   $ls_scgcuenta2 = substr($ls_scgcuenta,3,2);
					   $ls_scgcuenta3 = substr($ls_scgcuenta,5,2);
					   $ls_scgcuenta4 = substr($ls_scgcuenta,7,2);
					   $ls_scgcuenta5 = substr($ls_scgcuenta,9,3);
					   $la_datacon[$li_i] = array('0'=>'','1'=>$ls_scgcuenta1,'2'=>$ls_scgcuenta2,
											  	  '3'=>$ls_scgcuenta3,'4'=>$ls_scgcuenta4,'5'=>$ls_scgcuenta5,
											  	  '6'=>$ls_denominacion,'7'=>$ls_debe,'8'=>$ls_haber);
					 }
				}
				 
				  $la_columna=array('0'=>'','1'=>'','2'=>'','3'=>'','4'=>'','5'=>'','6'=>'','7'=>'','8'=>'',
				  				    '9'=>'','10'=>'','11'=>'');
				  $la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
							 'xPos'=>270, // Orientación de la tabla
							 'width'=>580, // Ancho de la tabla
							 'maxWidth'=>580,
							 'cols'=>array('0'=>array('justification'=>'left','width'=>55),'1'=>array('justification'=>'left','width'=>100),'2'=>array('justification'=>'left','width'=>22),
							 '3'=>array('justification'=>'left','width'=>25),'4'=>array('justification'=>'left','width'=>22),
							 '5'=>array('justification'=>'left','width'=>27),'6'=>array('justification'=>'left','width'=>42),
							 '7'=>array('justification'=>'left','width'=>27),'8'=>array('justification'=>'left','width'=>22),
							 '9'=>array('justification'=>'left','width'=>22),'10'=>array('justification'=>'left','width'=>22),
							 '11'=>array('justification'=>'right','width'=>107))); // Ancho Máximo de la tabla
				  $io_pdf->ezTable($la_datapre,$la_columna,'',$la_config);
				 unset($la_datapre,$la_columna,$la_config); 
				  
				  $io_pdf->ezSetDy(-60); 
				  $la_columna=array('0'=>'','1'=>'','2'=>'','3'=>'','4'=>'','5'=>'','6'=>'','7'=>'','8'=>'');
				  $la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
							 'xPos'=>226, // Orientación de la tabla
							 'width'=>580, // Ancho de la tabla
							 'maxWidth'=>580,
							 'cols'=>array('0'=>array('justification'=>'left','width'=>55),'1'=>array('justification'=>'left','width'=>27),'2'=>array('justification'=>'left','width'=>22),
							 '3'=>array('justification'=>'left','width'=>22),'4'=>array('justification'=>'left','width'=>22),
							 '5'=>array('justification'=>'left','width'=>27),'6'=>array('justification'=>'left','width'=>200),
							 '7'=>array('justification'=>'right','width'=>57),'8'=>array('justification'=>'right','width'=>57))); // Ancho Máximo de la tabla
				  $io_pdf->ezTable($la_datacon,$la_columna,'',$la_config);			 				
			
				  
				
				  $io_pdf->ezSetDy(-110); 
				  $la_data=array(array('ordenes'=>''.$ls_conmov));
				  $la_columna=array('ordenes'=>'');
				  $la_config=array('showHeadings'=>0, // Mostrar encabezados
								   'showLines'=>0, // Mostrar Líneas
								   'shaded'=>0, // Sombra entre líneas
								   'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
								   'xPos'=>254, // Orientación de la tabla
								   'width'=>580, // Ancho de la tabla
								   'maxWidth'=>580,
								   'cols'=>array('ordenes'=>array('justification'=>'left','width'=>480))); // Ancho Máximo de la tabla
				  $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
				  
				  $io_pdf->ezSetDy(-130); 
				  $la_data2=array(array('ordenes'=>$ls_nomproben));            				      
				  $la_columna=array('ordenes'=>'');
				  $la_config=array('showHeadings'=>0, // Mostrar encabezados
								   'showLines'=>0, // Mostrar Líneas
								   'shaded'=>0, // Sombra entre líneas
								   'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
								   'xPos'=>210, // Orientación de la tabla
								   'width'=>580, // Ancho de la tabla
								   'maxWidth'=>580,
								   'cols'=>array('ordenes'=>array('justification'=>'left','width'=>410))); // Ancho Máximo de la tabla
				  $io_pdf->ezTable($la_data2,$la_columna,'',$la_config);
				  $io_pdf->addText(445,274.6,9,$ls_cedbene);
				  $io_pdf->addText(445,700,9,$ls_numdoc);
				 
		}
		else
		{
			$li_pos=195;		
			$io_pdf->convertir_valor_mm_px($li_pos);
			$io_pdf->ezSetY($li_pos);
			$la_data=array(array('banco'=>'<b>Banco</b>  ','cheque'=>'<b>Cheque Nº</b>  ','cuenta'=>'<b>Cuenta Nº:</b>  ','voucher'=>'<b>Voucher Nº:</b>  '),
							array('banco'=>$ls_nomban,'cheque'=>$ls_numdoc,'cuenta'=>$ls_ctaban,'voucher'=>$ls_chevau));
			$la_columna=array('banco'=>'','cheque'=>'','cuenta'=>'','voucher'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'showLines'=>1, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
							 'xOrientation'=>'center', // Orientación de la tabla
							 'width'=>580, // Ancho de la tabla
							 'maxWidth'=>580,
							 'cols'=>array('banco'=>array('justification'=>'left','width'=>160),'cheque'=>array('justification'=>'left','width'=>100),
							 'cuenta'=>array('justification'=>'left','width'=>160),'voucher'=>array('justification'=>'left','width'=>160))); // Ancho Máximo de la tabla
			$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
			$la_data=array(array('ordenes'=>'<b>Orden(es) de Pago(s):</b> '.$ls_solicitudes),
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
			$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		
		}
	}// end function uf_print_cabecera.

	function uf_print_detalle($la_title,$la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 02/04/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_pdf->setStrokeColor(0,0,0);
		$la_data_title=array($la_title);
		$io_pdf->set_margenes(115,55,0,0);
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
	}// end function uf_print_detalle.
	
	function uf_print_autorizacion($ls_codban,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_autorizacion
		//		    Acess: private 
		//	    Arguments: io_pdf // Objeto PDF
		//    Description: función el final del voucher 
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 25/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		if ($ls_codban!='001')
		{
			$io_pdf->saveState();
			$io_pdf->setStrokeColor(0,0,0);		
			$io_pdf->Rectangle(11,43,580,105);
			$io_pdf->line(11,90,590,90);
			$io_pdf->line(11,74.6,590,74.6);		
			$io_pdf->line(127,90,127,148);
			$io_pdf->line(243,90,243,148);
			$io_pdf->line(359,90,359,148);
			$io_pdf->line(475,90,475,148);		
			$io_pdf->line(191,43,191,75);
			$io_pdf->line(310.5,43,310.5,75);
			$io_pdf->line(411,43,411,75);	
			
			$io_pdf->addText(16,137.6,9,'<b>Elaborado por:</b>');
			$io_pdf->addText(132,137.6,9,'<b>Revisado por:</b>');
			$io_pdf->addText(273,137.6,9,'<b>Presupuesto</b>');		
			$io_pdf->addText(384,137.6,9,'<b>Administración</b>');		
			$io_pdf->addText(508,137.6,9,'<b>Presidencia</b>');
			$io_pdf->addText(258,78.85,10,'<b>Recibí Conforme</b>');
			
			$io_pdf->addText(16,63.27,10,'<b>Nombre:</b>');		
			$io_pdf->addText(196,63.27,10,'<b>Cédula de Identidad:</b>');		
			$io_pdf->addText(316,63.27,10,'<b>Fecha:</b>');
			$io_pdf->addText(416,63.27,10,'<b>Firma:</b>');
		}	
	}// end function uf_print_autorizacion.	

require_once("sigesp_scb_report.php");
require_once('../../shared/class_folder/class_pdf.php');
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_numero_a_letra.php");

$io_include   = new sigesp_include();
$ls_conect    = $io_include->uf_conectar();
$io_sql		  = new class_sql($ls_conect);	
$class_report = new sigesp_scb_report($ls_conect);
$io_funciones = new class_funciones();				
$ds_voucher	  = new class_datastore();	
$ds_dt_scg	  = new class_datastore();				
$ds_dt_spg	  = new class_datastore();
$numalet	  = new class_numero_a_letra();

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


$ls_tipimp = "";
	if (array_key_exists("tipimp",$_GET))
	{
		$ls_tipimp = $_GET["tipimp"];
	}	

	if ($ls_tipimp=='lote')
	{
		$ls_codemp		=$_SESSION["la_empresa"]["codemp"];
		$ls_codban      = $_GET["codban"];
		$ls_ctaban      = $_GET["ctaban"];
		$ls_documentos  = $_GET["documentos"];
		$ls_fechas      = $_GET["fechas"];
		$ld_fecdes      = $_GET["fecdesde"];
		$ld_fechas      = $_GET["fechasta"];
		$ls_operaciones = $_GET["operaciones"];
			
		//Descompongo la cadena de documentos en un arreglo tomando como separación el ','
		$arr_documentos = split(",",$ls_documentos);
		$li_totdoc		= count($arr_documentos);
		//Descompongo la cadena de fechas en un arreglo tomando como separación el '-'
		$arr_fecmov = split("-",$ls_fechas);
		$li_totfec  = count($arr_fecmov);
	   //Descompongo la cadena de operaciones en un arreglo tomando como separación el '-'
		$arr_operaciones = split("-",$ls_operaciones);
		$li_totdoc	= count($arr_operaciones);		
		$class_report->uf_buscar_cheques_vouchers($arr_documentos,$arr_fecmov,$arr_operaciones,$ls_codban,$ls_ctaban);
		
		$li_total   = $class_report->ds_voucher1->getRowCount("numdoc");

		if ($li_total>0)
		{	
			error_reporting(E_ALL);
			set_time_limit(1800);
			$io_pdf=new class_pdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->set_margenes(0,55,0,0);
			if (trim($ls_codban)=="001")//Banco Industrial.
			   {
				 $ls_archivo="cheque_configurable/medidas_industrial.txt";
				 $ls_contenido="144.00-0.00-24.00-20.00-26.00-30.00-27.00-33.00-22.00-38.00-79.00-38.00-120.00-60.00-130.00-65.00-10.00-91.00-8.00-100.00-12.00-117.00";
				 $li_medidas=22;
			   }
			elseif(trim($ls_codban)=="002")//Banco de Venezuela.
			   {
				 $ls_archivo   = "cheque_configurable/medidas_venezuela.txt";
				 $ls_contenido = "144.00-0.00-24.00-20.00-26.00-30.00-27.00-33.00-22.00-38.00-79.00-38.00-120.00-60.00-130.00-65.00-10.00-91.00-8.00-100.00-12.00-117.00";
				 $li_medidas   = 22;
			   }
			elseif(trim($ls_codban)=="003")//Banco Occidental de Descuento.
			   {
				 $ls_archivo   = "cheque_configurable/medidas_bod.txt";
				 $ls_contenido = "144.00-0.00-24.00-20.00-26.00-30.00-27.00-33.00-22.00-38.00-79.00-38.00-120.00-60.00-130.00-65.00-10.00-91.00-8.00-100.00-12.00-117.00";
				 $li_medidas   = 22;
			   }
			else
			   {
				 $ls_archivo="cheque_configurable/medidas.txt";
				 $ls_contenido="144.00-0.00-24.00-20.00-26.00-30.00-27.00-33.00-22.00-38.00-79.00-38.00-120.00-60.00-130.00-65.00";
				 $li_medidas=16;	  	 
			   }
			uf_inicializar_variables($ls_archivo,$ls_contenido,$li_medidas);
		    $io_pdf->ezStartPageNumbers(570,30,10,'','',1); // Insertar el número de página
			for ($i=1;$i<=$li_total;$i++)
			{
				$ls_numdoc=$class_report->ds_voucher1->getValue("numdoc",$i);
				
				$ls_chevau=$class_report->ds_voucher1->getValue("chevau",$i);
				$ls_codope='CH';
				
				$data 	   = $class_report->uf_cargar_chq_voucher($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
				$lb_valido = $class_report->uf_actualizar_status_impreso($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
				$class_report->SQL->begin_transaction();
				if (!$lb_valido)
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
				
				$li_totrow=$ds_voucher->getRowCount("numdoc");
				
				$io_pdf->transaction('start'); // Iniciamos la transacción
				$thisPageNum=$io_pdf->ezPageCount;
				for($li_i=1;$li_i<=$li_totrow;$li_i++)
				{
					$ls_tipodest = $ds_voucher->data["tipo_destino"][$li_i];
					$li_totprenom = 0;
					$ldec_mondeb  = 0;
					$ldec_monhab  = 0;
					$li_totant	  = 0;
					$ls_numdoc		= $ds_voucher->data["numdoc"][$li_i];
					$ls_codban		= $ds_voucher->data["codban"][$li_i];
					$ls_nomban		= $class_report->uf_select_data($io_sql,"SELECT nomban FROM scb_banco WHERE codban ='".$ls_codban."' AND codemp='".$ls_codemp."'","nomban");
					$ls_ctaban		= $ds_voucher->data["ctaban"][$li_i];
					$ls_chevau		= $ds_voucher->data["chevau"][$li_i];
					$ld_fecmov	  	= $io_funciones->uf_convertirfecmostrar($ds_voucher->data["fecmov"][$li_i]);
					$ls_nomproben 	= $ds_voucher->data["nomproben"][$li_i];
					$ls_cedbene 	= $ds_voucher->data["ced_bene"][$li_i];
					$ls_rifpro  	= $ds_voucher->data["rifpro"][$li_i];
					if ($ls_tipodest =='P')
					{
						$ls_cedbene  	= $ds_voucher->data["rifpro"][$li_i];	
					}
					else
					{
						$ls_cedbene 	= $ds_voucher->data["ced_bene"][$li_i];
					}
					
					$ls_solicitudes = $class_report->uf_select_solicitudes($ls_numdoc,$ls_codban,$ls_ctaban);
					$ls_conmov		= $ds_voucher->getValue("conmov",$li_i);
					$ldec_monret	= $ds_voucher->getValue("monret",$li_i);
					$ldec_monto		= $ds_voucher->getValue("monto",$li_i);
					$ldec_total		= $ldec_monto-$ldec_monret;
					//Asigno el monto a la clase numero-letras para la conversion.
					$numalet->setNumero($ldec_total);
					//Obtengo el texto del monto enviado.
					$ls_monto= $numalet->letra();
					uf_print_encabezado_pagina(number_format($ldec_total,2,",","."),$ls_nomproben,$ls_monto,$_SESSION["la_empresa"]["ciuemp"].", ".$ld_fecmov,$io_pdf); // Imprimimos el encabezado de la página
					
					
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
					// Ciclo para unir en una sola matriz los detalles de presupuesto y los contables para proceder luego a imprimirlos.
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					if ($li_totrow_det>=$li_totrow_spg)
					   {
						 for ($li_s=1;$li_s<=$li_totrow_det;$li_s++)
							 {
							   $ls_scg_cuenta = trim($ds_dt_scg->data["scg_cuenta"][$li_s]);
							   $ls_debhab     = $ds_dt_scg->data["debhab"][$li_s];
							   $ldec_monto    = $ds_dt_scg->data["monto"][$li_s];
							   if ($ls_debhab=='D')
								  {
									$ldec_mondeb = number_format($ldec_monto,2,",",".");
									$ldec_monhab = "";
								  }
							   else
								  {
									$ldec_monhab = number_format($ldec_monto,2,",",".");
									$ldec_mondeb = "";
								  }
							   if (array_key_exists("spg_cuenta",$ds_dt_spg->data))
								  {
									if (array_key_exists($li_s,$ds_dt_spg->data["spg_cuenta"]))
									   {
										 $ls_cuentaspg   = trim($ds_dt_spg->getValue("spg_cuenta",$li_s));
										 $ls_estpro      = $ds_dt_spg->getValue("estpro",$li_s);	  
										 $ldec_monto_spg = number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
										 $ls_denominacion= $ds_dt_scg->data["denominacion"][$li_s];
									   }
									else
									   {
										 $ls_cuentaspg   = "";	
										 $ls_estpro      = "";	  
										 $ldec_monto_spg = "";
									   }
								  }
							   else
								  {
									$ls_cuentaspg   = "";	
									$ls_estpro      = "";	  
									$ldec_monto_spg = "";
								  }
					   			$la_data[$li_s]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab,'denominacion'=>$ls_denominacion);
							 }
					   }
					if ($li_totrow_spg>$li_totrow_det)
					   {
						 for ($li_s=1;$li_s<=$li_totrow_spg;$li_s++)
							 {
							   if (array_key_exists("scg_cuenta",$ds_dt_scg->data))
								  {
									if (array_key_exists($li_s,$ds_dt_scg->data["scg_cuenta"]))
									   {
										 $ls_scg_cuenta = trim($ds_dt_scg->data["scg_cuenta"][$li_s]);
										 $ls_debhab 	= $ds_dt_scg->data["debhab"][$li_s];
										 $ldec_monto	= $ds_dt_scg->data["monto"][$li_s];
										 if ($ls_debhab=='D')
											{
											  $ldec_mondeb = number_format($ldec_monto,2,",",".");
											  $ldec_monhab = "";
											}
										 else
											{
											  $ldec_monhab = number_format($ldec_monto,2,",",".");
											  $ldec_mondeb = "";
											}
									   }
									else
									   {
										 $ls_scg_cuenta = "";
										 $ls_debhab 	= "";
										 $ldec_monto	= "";
										 $ldec_mondeb	= "";
										 $ldec_monhab   = "";					
									   }
								  }
							   else
								  {
									$ls_scg_cuenta = "";
									$ls_debhab 	   = "";
									$ldec_monto	   = "";
									$ldec_mondeb   = "";
									$ldec_monhab   = "";					
								  }
							   if (array_key_exists("spg_cuenta",$ds_dt_spg->data))
								  {
									if (array_key_exists($li_s,$ds_dt_spg->data["spg_cuenta"]))
									   {
										 $ls_cuentaspg   = trim($ds_dt_spg->getValue("spg_cuenta",$li_s));
										 $ls_estpro      = $ds_dt_spg->getValue("estpro",$li_s);	  
										 $ldec_monto_spg = number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
										 $ls_denominacion= $ds_dt_scg->data["denominacion"][$li_s];
									   }
									else
									   {
										 $ls_cuentaspg   = "";	
										 $ls_estpro      = "";	  
										 $ldec_monto_spg = "";
									   }
								  }
							   else
								  {
									$ls_cuentaspg   = "";	
									$ls_estpro      = "";	  
									$ldec_monto_spg = "";
								  }
					   			$la_data[$li_s]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab,'denominacion'=>$ls_denominacion);
							 }
					   }
					if ($ls_codban !='001')
					{
					uf_print_detalle(array('title'=>'Detalle Presupuestario Pago','title2'=>'Detalle Contable Pago'),$la_data,$io_pdf);
					}
				} // Fin del for 2
		    	uf_print_cabecera($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_chevau,$ls_nomproben,$ls_solicitudes,$ls_conmov,$ls_codban,$ld_fecmov,$la_data,$ls_cedbene,$io_pdf); // Imprimimos la cabecera del registro

			    uf_print_autorizacion($ls_codban,$io_pdf);	
				if ($i<$li_total)
				{			
					$io_pdf->ezNewPage(); // Insertar una nueva página
					$io_pdf->set_margenes(0,55,0,0);
				}
				unset($la_data);
			}// Fin del for 1
			$io_pdf->ezStopPageNumbers(1,1);
			$io_pdf->ezStream();
			unset($io_pdf,$class_report,$io_funciones,$ds_dt_spg,$ds_dt_scg,$ds_voucher,$la_data);
		}//Fin del if ($li_total>0)
		else
		{
			 print("<script language=JavaScript>");
			 print(" alert('No hay nada que Reportar');"); 
			 print(" close();");
			 print("</script>");	  
		} 
		
	} // Fin de si es lote
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// SI NO ES POR LOTE ENTONCES
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	else 
	{
		$ls_codemp = $_SESSION["la_empresa"]["codemp"];
		$ls_codban = $_GET["codban"];
		$ls_ctaban = $_GET["ctaban"];
		$ls_numdoc = $_GET["numdoc"];
		$ls_chevau = $_GET["chevau"];
		$ls_codope = $_GET["codope"];				
	
		$data 	   = $class_report->uf_cargar_chq_voucher($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
		$lb_valido = $class_report->uf_actualizar_status_impreso($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
		$class_report->SQL->begin_transaction();
		if (!$lb_valido)
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
		$io_pdf=new class_pdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->set_margenes(0,55,0,0);
		if (trim($ls_codban)=="001")//Banco Industrial.
		   {
			 $ls_archivo="cheque_configurable/medidas_industrial.txt";
			 $ls_contenido="144.00-0.00-24.00-20.00-26.00-30.00-27.00-33.00-22.00-38.00-79.00-38.00-120.00-60.00-130.00-65.00-10.00-91.00-8.00-100.00-12.00-117.00";
			 $li_medidas=22;
		   }
	    elseif(trim($ls_codban)=="002")//Banco de Venezuela.
		   {
		     $ls_archivo   = "cheque_configurable/medidas_venezuela.txt";
		     $ls_contenido = "144.00-0.00-24.00-20.00-26.00-30.00-27.00-33.00-22.00-38.00-79.00-38.00-120.00-60.00-130.00-65.00-10.00-91.00-8.00-100.00-12.00-117.00";
		     $li_medidas   = 22;
		   }
	    elseif(trim($ls_codban)=="003")//Banco Occidental de Descuento.
		   {
		     $ls_archivo   = "cheque_configurable/medidas_bod.txt";
		     $ls_contenido = "144.00-0.00-24.00-20.00-26.00-30.00-27.00-33.00-22.00-38.00-79.00-38.00-120.00-60.00-130.00-65.00-10.00-91.00-8.00-100.00-12.00-117.00";
		     $li_medidas   = 22;
		   }
		else
		   {
			 $ls_archivo="cheque_configurable/medidas.txt";
			 $ls_contenido="144.00-0.00-24.00-20.00-26.00-30.00-27.00-33.00-22.00-38.00-79.00-38.00-120.00-60.00-130.00-65.00";
			 $li_medidas=16;	  	 
		   }
		uf_inicializar_variables($ls_archivo,$ls_contenido,$li_medidas);
		$li_totrow=$ds_voucher->getRowCount("numdoc");
		$io_pdf->transaction('start'); // Iniciamos la transacción
		$thisPageNum=$io_pdf->ezPageCount;
		$io_pdf->ezStartPageNumbers(570,30,10,'','',1); // Insertar el número de página
		uf_print_autorizacion($ls_codban,$io_pdf);
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
			unset($la_data);
			$ls_tipodest = $ds_voucher->data["tipo_destino"][$li_i];
			$li_totprenom = 0;
			$ldec_mondeb  = 0;
			$ldec_monhab  = 0;
			$li_totant	  = 0;
			$ls_numdoc		= $ds_voucher->data["numdoc"][$li_i];
			$ls_codban		= $ds_voucher->data["codban"][$li_i];
			$ls_nomban		= $class_report->uf_select_data($io_sql,"SELECT nomban FROM scb_banco WHERE codban ='".$ls_codban."' AND codemp='".$ls_codemp."'","nomban");
			$ls_ctaban		= $ds_voucher->data["ctaban"][$li_i];
			$ls_chevau		= $ds_voucher->data["chevau"][$li_i];
			$ld_fecmov	  	= $io_funciones->uf_convertirfecmostrar($ds_voucher->data["fecmov"][$li_i]);
			$ls_nomproben 	= $ds_voucher->data["nomproben"][$li_i];
			$ls_cedbene 	= $ds_voucher->data["ced_bene"][$li_i];
			$ls_rifpro  	= $ds_voucher->data["rifpro"][$li_i];
			if ($ls_tipodest =='P')
			{
				$ls_cedbene  	= $ds_voucher->data["rifpro"][$li_i];	
			}
			else
			{
				$ls_cedbene 	= $ds_voucher->data["ced_bene"][$li_i];
			}
			$ls_solicitudes = $class_report->uf_select_solicitudes($ls_numdoc,$ls_codban,$ls_ctaban);
			$ls_conmov		= $ds_voucher->getValue("conmov",$li_i);
			$ldec_monret	= $ds_voucher->getValue("monret",$li_i);
			$ldec_monto		= $ds_voucher->getValue("monto",$li_i);
			$ldec_total		= $ldec_monto-$ldec_monret;
			//Asigno el monto a la clase numero-letras para la conversion.
			$numalet->setNumero($ldec_total);
			//Obtengo el texto del monto enviado.
			$ls_monto= $numalet->letra();
			uf_print_encabezado_pagina(number_format($ldec_total,2,",","."),$ls_nomproben,$ls_monto,$_SESSION["la_empresa"]["ciuemp"].", ".$ld_fecmov,$io_pdf); // Imprimimos el encabezado de la página
			
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
			// Ciclo para unir en una sola matriz los detalles de presupuesto y los contables para proceder luego a imprimirlos.
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			if ($li_totrow_det>=$li_totrow_spg)
			   {
				 for ($li_s=1;$li_s<=$li_totrow_det;$li_s++)
					 {
					   $ls_scg_cuenta = trim($ds_dt_scg->data["scg_cuenta"][$li_s]);
					   $ls_debhab     = $ds_dt_scg->data["debhab"][$li_s];
					   $ldec_monto    = $ds_dt_scg->data["monto"][$li_s];
					   $ls_denominacion= $ds_dt_scg->data["denominacion"][$li_s];
					   if ($ls_debhab=='D')
						  {
							$ldec_mondeb = number_format($ldec_monto,2,",",".");
							$ldec_monhab = "";
						  }
					   else
						  {
							$ldec_monhab = number_format($ldec_monto,2,",",".");
							$ldec_mondeb = "";
						  }
					   if (array_key_exists("spg_cuenta",$ds_dt_spg->data))
						  {
							if (array_key_exists($li_s,$ds_dt_spg->data["spg_cuenta"]))
							   {
								 $ls_cuentaspg   = trim($ds_dt_spg->getValue("spg_cuenta",$li_s));
								 $ls_estpro      = $ds_dt_spg->getValue("estpro",$li_s);	  
								 $ldec_monto_spg = number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
							   }
							else
							   {
								 $ls_cuentaspg   = "";	
								 $ls_estpro      = "";	  
								 $ldec_monto_spg = "";
							   }
						  }
					   else
						  {
							$ls_cuentaspg   = "";	
							$ls_estpro      = "";	  
							$ldec_monto_spg = "";
						  }
					   $la_data[$li_s]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab,'denominacion'=>$ls_denominacion);
					 }
			   }
			if ($li_totrow_spg>$li_totrow_det)
			   {
				 for ($li_s=1;$li_s<=$li_totrow_spg;$li_s++)
					 {
					   if (array_key_exists("scg_cuenta",$ds_dt_scg->data))
						  {
							if (array_key_exists($li_s,$ds_dt_scg->data["scg_cuenta"]))
							   {
								 $ls_scg_cuenta = trim($ds_dt_scg->data["scg_cuenta"][$li_s]);
								 $ls_debhab 	= $ds_dt_scg->data["debhab"][$li_s];
								 $ldec_monto	= $ds_dt_scg->data["monto"][$li_s];
								 $ls_denominacion= $ds_dt_scg->data["denominacion"][$li_s];
								 if ($ls_debhab=='D')
									{
									  $ldec_mondeb = number_format($ldec_monto,2,",",".");
									  $ldec_monhab = "";
									}
								 else
									{
									  $ldec_monhab = number_format($ldec_monto,2,",",".");
									  $ldec_mondeb = "";
									}
							   }
							else
							   {
								 $ls_scg_cuenta = "";
								 $ls_debhab 	= "";
								 $ldec_monto	= "";
								 $ldec_mondeb	= "";
								 $ldec_monhab   = "";					
								 $ls_denominacion = "";
							   }
						  }
					   else
						  {
							$ls_scg_cuenta = "";
							$ls_debhab 	   = "";
							$ldec_monto	   = "";
							$ldec_mondeb   = "";
							$ldec_monhab   = "";
							$ls_denominacion = "";					
						  }
					   if (array_key_exists("spg_cuenta",$ds_dt_spg->data))
						  {
							if (array_key_exists($li_s,$ds_dt_spg->data["spg_cuenta"]))
							   {
								 $ls_cuentaspg   = trim($ds_dt_spg->getValue("spg_cuenta",$li_s));
								 $ls_estpro      = $ds_dt_spg->getValue("estpro",$li_s);	  
								 $ldec_monto_spg = number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
							   }
							else
							   {
								 $ls_cuentaspg   = "";	
								 $ls_estpro      = "";	  
								 $ldec_monto_spg = "";
							   }
						  }
					   else
						  {
							$ls_cuentaspg   = "";	
							$ls_estpro      = "";	  
							$ldec_monto_spg = "";
						  }
					   $la_data[$li_s]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab,'denominacion'=>$ls_denominacion);
					 }
			   }
			uf_print_cabecera($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_chevau,$ls_nomproben,$ls_solicitudes,$ls_conmov,$ls_codban,$ld_fecmov,$la_data,$ls_cedbene,$io_pdf);
			if ($ls_codban !='001')
			{
			uf_print_detalle(array('title'=>'Detalle Presupuestario Pago','title2'=>'Detalle Contable Pago'),$la_data,$io_pdf);
			}
		}
		$io_pdf->ezStopPageNumbers(1,1);
		$io_pdf->ezStream();
		unset($io_pdf,$class_report,$io_funciones);
	}
?> 