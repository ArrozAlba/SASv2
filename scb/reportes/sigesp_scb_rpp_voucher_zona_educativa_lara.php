<?php
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");//Zona Educativa del Estado Lara.
	header("Cache-Control: private",false);
	if (!array_key_exists("la_logusr",$_SESSION))
	   {
		 echo "<script language=JavaScript>";
		 echo "opener.document.form1.submit();"	;	
		 echo "close();";
		 echo "</script>";		
	   }	
	$x_pos		   = 0;//mientras mas grande el numero, mas a la derecha.
	$y_pos		   = -1;//Mientras mas pequeño el numero, mas alto.
	$ls_directorio = "cheque_configurable";
	$ls_archivo	   = "cheque_configurable/medidas.txt";
	$li_medidas    = 16;

	function uf_inicializar_variables()
	{
		global $valores;
		global $ls_directorio;
		global $ls_archivo;	
		global $li_medidas;	
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
		
		if((!file_exists ($ls_archivo)) || (filesize($ls_archivo)==0))
		{	
			if(file_exists ($ls_directorio))
			{
				$archivo = fopen($ls_archivo, "w");			
				$ls_contenido="138.00-6.00-32.00-24.00-32.00-28.00-32.00-34.00-32.00-43.00-77.00-43.00-137.00-65.00-131.00-70.00";
				fwrite($archivo,$ls_contenido);
				fclose($archivo);
			}
		}
			
		if((file_exists($ls_archivo)) && (filesize($ls_archivo)>0))
		{
			$archivo = fopen($ls_archivo, "r");
			$contenido = fread($archivo, filesize($ls_archivo));			
			fclose($archivo);			
			$valores = explode("-",$contenido);	
			if(count($valores)<>$li_medidas)
			{
				$archivo = fopen($ls_archivo, "w");
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
	}// end function uf_inicializar_variables.
	
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
	}// end function uf_print_encabezadopagina.

	function uf_print_cabecera($aa_datsolpag,$as_conmov,&$io_pdf)
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
		$li_pos=165;
		$io_pdf->convertir_valor_mm_px($li_pos);
		$io_pdf->ezSetY($li_pos);
		$la_data=array(array('conmov'=>$as_conmov));
		$la_columna=array('conmov'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('conmov'=>array('justification'=>'left','width'=>580))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data,$la_columna,$la_config);

		$io_pdf->setStrokeColor(0,0,0);
		$li_pos=120;
		$io_pdf->convertir_valor_mm_px($li_pos);
		$io_pdf->ezSetY($li_pos);
		$la_columna=array('anoordpag'=>'','numordpag'=>'','fecordpag'=>'');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize'=>8, // Tamaño de Letras
						 'titleFontSize'=>8,  // Tamaño de Letras de los títulos
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>97, // Orientación de la tabla
						 'width'=>175, // Ancho de la tabla
						 'maxWidth'=>175,
						 'cols'=>array('anoordpag'=>array('justification'=>'left','width'=>58),
									   'numordpag'=>array('justification'=>'left','width'=>59),
									   'fecordpag'=>array('justification'=>'left','width'=>58) )); // Ancho Máximo de la tabla
		$io_pdf->ezTable($aa_datsolpag,$la_columna,'',$la_config);
		unset($aa_datsolpag,$la_columna,$la_config);
	}// end function uf_print_cabecera.

	function uf_print_detalle($la_data,&$io_pdf)
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
		$li_pos=120;
		$io_pdf->convertir_valor_mm_px($li_pos);
		$io_pdf->ezSetY($li_pos);
		//Imprimo los detalles tanto `de presupuesto como contablwe del movimiento
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize'=>8, // Tamaño de Letras
						 'titleFontSize'=>8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>463, // Ancho de la tabla
						 'maxWidth'=>463, // Ancho Máximo de la tabla
						 'xPos'=>400, // Orientación de la tabla
						 'cols'=>array('codestpro1'=>array('justification'=>'center','width'=>70),
						 			   'codestpro2'=>array('justification'=>'center','width'=>69),
						 			   'codestpro3'=>array('justification'=>'center','width'=>65),
			 						   'spg_cuenta'=>array('justification'=>'center','width'=>115),
									   'monto_spg'=>array('justification'=>'right','width'=>112))); // Justificación y ancho de la columna
		$la_columnas=array('codestpro1'=>'','codestpro2'=>'','codestpro3'=>'','spg_cuenta'=>'','monto_spg'=>'');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
	}// end function uf_print_detalle.
	
	function uf_print_retenciones($aa_dataret,$io_pdf)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_retenciones
	//		    Acess: private 
	//	    Arguments: $aa_dataret = Arreglo cargado con la información relacionada a las retencion de iva y/o islr.
	//    Description: Funcíon que imprime en un recuadro el monto de las retenciones aplicadas al cheque.
	//	   Creado Por: Ing. Néstor Falcón
	// Fecha Creación: 11/06/2008.
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	  $io_pdf->setStrokeColor(0,0,0);
	  $li_pos=90;
	  $io_pdf->convertir_valor_mm_px($li_pos);
	  $io_pdf->ezSetY($li_pos);
	  $la_columna = array('retiva'=>'','retislrnat'=>'','retislrjur'=>'');
	  $la_config  = array('showHeadings'=>0, // Mostrar encabezados
					      'showLines'=>0, // Mostrar Líneas
					      'fontSize'=>8, // Tamaño de Letras
					      'titleFontSize'=>8,// Tamaño de Letras de los títulos
					      'shaded'=>0,// Sombra entre líneas
					 	  'xPos'=>310,// Orientación de la tabla
					 	  'width'=>600,// Ancho de la tabla
					 	  'maxWidth'=>600,
					 	  'cols'=>array('retiva'=>array('justification'=>'right','width'=>195),
								   		'retislrnat'=>array('justification'=>'right','width'=>200),
								   		'retislrjur'=>array('justification'=>'right','width'=>210))); // Ancho Máximo de la tabla
	  $io_pdf->ezTable($aa_dataret,$la_columna,'',$la_config);
	  unset($aa_dataret,$la_columna,$la_config);
	}
	
uf_inicializar_variables();
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
$ds_dt_spg	  = new class_datastore();
$ds_dt_scg	  = new class_datastore();
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
	$li_totrow=$ds_voucher->getRowCount("numdoc");
	$io_pdf->transaction('start'); // Iniciamos la transacción
	$thisPageNum=$io_pdf->ezPageCount;
	for($li_i=1;$li_i<=$li_totrow;$li_i++)
	{
		unset($la_data);
		$li_totprenom = $li_totrowsol = $ld_montotislr = 0;
		$ldec_mondeb  = 0;
		$ldec_monhab  = 0;
		$li_totant	  = 0;
		$ls_numdoc		= $ds_voucher->data["numdoc"][$li_i];
		$ls_estmov      = $ds_voucher->data["estmov"][$li_i];
		$ld_fecmov	  	= $io_funciones->uf_convertirfecmostrar($ds_voucher->data["fecmov"][$li_i]);
		$ls_codban      = $ds_voucher->data["codban"][$li_i];
		$ls_ctaban      = $ds_voucher->data["ctaban"][$li_i]; 
		$ls_nomproben 	= $ds_voucher->data["nomproben"][$li_i];
		$ldec_monret	= $ds_voucher->getValue("monret",$li_i);
		$ls_solicitudes = $class_report->uf_select_solicitudes($ls_numdoc,$ls_codban,$ls_ctaban);
		$la_numsolpag   = $la_datsolpag = array();
		if (!empty($ls_solicitudes))
		   {
			 $la_numsolpag   = explode("-",$ls_solicitudes);
			 $li_totrowsol   = count($la_numsolpag);
		   }
	    $ld_monislrnat = $ld_monislrjur = $ld_monretiva = $ls_tipretislr = "";
		if ($li_totrowsol>0)
		   {
		     $ld_montotiva = $class_report->uf_load_retenciones_cxp($ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_estmov,$ls_tipretislr,"IVA");
			 if ($ld_montotiva>0)
			    {
				  $ld_monretiva = number_format($ld_montotiva,2,',','.');
				}
			 $ld_montotislr = $class_report->uf_load_retenciones_cxp($ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_estmov,$ls_tipretislr,"ISLR");
			 if ($ld_montotislr>0)
				{
				  if (strtoupper($ls_tipretislr)=="J")
					 {
					   $ld_monislrjur = number_format($ld_montotislr,2,',','.');//Impuesto aplicado sobre persona Jurídica.
					 }
				  elseif(strtoupper($ls_tipretislr)=="N")
					 {
					   $ld_monislrnat = number_format($ld_montotislr,2,',','.');//Impuesto aplicado sobre persona Natural.
					 }						  
				}
			 $li_totfil = 0;
			 for ($i=0;$i<$li_totrowsol;$i++)
			     {
				   $ls_numsolpag = $la_numsolpag[$i];
				   if (!empty($ls_numsolpag))
				      {
					    $li_totfil++;
						$ls_fecemisol = $class_report->uf_select_data($io_sql,"SELECT fecemisol FROM cxp_solicitudes WHERE codemp='".$ls_codemp."' AND numsol ='".$ls_numsolpag."'","fecemisol");
				        $la_datsolpag[$li_totfil] = array('anoordpag'=>substr($ls_fecemisol,0,4),'numordpag'=>substr($ls_numsolpag,-9),'fecordpag'=>substr($ls_fecemisol,-2)."-".substr($ls_fecemisol,5,2));					  
					  }					  
				 }
		   }
		else
		   {
		     if ($ldec_monret>0)
			    {
		          $ld_montotiva = $class_report->uf_load_retenciones_scb($ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_estmov,$ls_tipretislr,"IVA");
				  if ($ld_montotiva>0)
					 {
					   $ld_monretiva = number_format($ld_montotiva,2,',','.');
					 }
				  if ($ld_montotiva<$ldec_monret)//Realizamos esta verificacion para ver si hace falta buscar retenciones de islr.
				     {
					   $ld_montotislr = $class_report->uf_load_retenciones_scb($ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_estmov,$ls_tipretislr,"ISLR");
					   if ($ld_montotislr>0)
					      {
						    if (strtoupper($ls_tipretislr)=="J")
							   {
								 $ld_monislrjur = number_format($ld_montotislr,2,',','.');//Impuesto aplicado sobre persona Jurídica.
							   }
						    elseif(strtoupper($ls_tipretislr)=="N")
							   {
								 $ld_monislrnat = number_format($ld_montotislr,2,',','.');//Impuesto aplicado sobre persona Natural.
							   }						  
						  }
					 }
				}
		   }		
		$la_dataret[0]  = array('retiva'=>$ld_monretiva,'retislrnat'=>$ld_monislrnat,'retislrjur'=>$ld_monislrjur);
		$ls_conmov		= $ds_voucher->getValue("conmov",$li_i);		
		$ldec_monto		= $ds_voucher->getValue("monto",$li_i);
		$ldec_total		= $ldec_monto-$ldec_monret;
		//Asigno el monto a la clase numero-letras para la conversion.
		$numalet->setNumero($ldec_total);
		//Obtengo el texto del monto enviado.
		$ls_monto= $numalet->letra();
		uf_print_encabezado_pagina(number_format($ldec_total,2,",","."),$ls_nomproben,$ls_monto,$_SESSION["la_empresa"]["ciuemp"].", ".$ld_fecmov,$io_pdf); // Imprimimos el encabezado de la página
		uf_print_cabecera($la_datsolpag,$ls_conmov,$io_pdf); // Imprimimos la cabecera del registro
		
		$ds_dt_scg->data=$class_report->uf_cargar_dt_scg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope); // Obtenemos el detalle del reporte
		$la_items = array('0'=>'scg_cuenta','1'=>'debhab');
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
		$li_i = 0;
		if ($li_totrow_det>=$li_totrow_spg)
		   {
			 for ($li_s=1;$li_s<=$li_totrow_det;$li_s++)
			     {
				   $ls_cuentaspg  = $ls_codestpro = $ls_codestpro1 = $ls_codestpro2 = $ls_codestpro3 = $ldec_monto_spg = "";
				   $ls_codestpro3 = trim($ds_dt_scg->data["scg_cuenta"][$li_s]);
				   $ls_debhab     = $ds_dt_scg->data["debhab"][$li_s];
				   $ldec_monto    = $ds_dt_scg->data["monto"][$li_s];
				   if ($ls_debhab=='D')
				      {
					    $ls_cuentaspg   = number_format($ldec_monto,2,",",".");
					    $ldec_monto_spg = "";
				      }
				   else
				      {
					    $ldec_monto_spg = number_format($ldec_monto,2,",",".");
					    $ls_cuentaspg   = "";
				      }
				   $li_i++;
				   $la_data[$li_i] = array('codestpro1'=>$ls_codestpro1,
										   'codestpro2'=>$ls_codestpro2,
										   'codestpro3'=>$ls_codestpro3,
										   'spg_cuenta'=>$ls_cuentaspg,
										   'monto_spg'=>$ldec_monto_spg);
						   
				   if (array_key_exists("estpro",$ds_dt_spg->data))
				      {
					    if (array_key_exists($li_s,$ds_dt_spg->data["estpro"]))
					       {
						     $ls_cuentaspg   = trim($ds_dt_spg->getValue("spg_cuenta",$li_s));
							 $ls_codestpro   = $ds_dt_spg->getValue("estpro",$li_s);
							 $ls_codestpro1  = substr(substr($ls_codestpro,0,20),-9);
							 $ls_codestpro2  = substr($ls_codestpro,21,6);
							 $ls_codestpro3  = substr($ls_codestpro,28,3);						
							 $ldec_monto_spg = number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
						     $li_i++;
							 $la_data[$li_i] = array('codestpro1'=>$ls_codestpro1,
													 'codestpro2'=>$ls_codestpro2,
													 'codestpro3'=>$ls_codestpro3,
													 'spg_cuenta'=>$ls_cuentaspg,
													 'monto_spg'=>$ldec_monto_spg);
						   }
					  }
			     }
		   }
		if ($li_totrow_spg>$li_totrow_det)
		   {
			 for ($li_s=1;$li_s<=$li_totrow_spg;$li_s++)
			     {
				   $ls_scg_cuenta = $ls_debhab = $ldec_monto = $ldec_mondeb	= $ldec_monhab = "";
				   if (array_key_exists("scg_cuenta",$ds_dt_scg->data))
				      {
					    if (array_key_exists($li_s,$ds_dt_scg->data["scg_cuenta"]))
					       {
							 $ls_codestpro3 = trim($ds_dt_scg->data["scg_cuenta"][$li_s]);
							 $ls_debhab 	= $ds_dt_scg->data["debhab"][$li_s];
							 $ldec_monto	= $ds_dt_scg->data["monto"][$li_s];
							 if ($ls_debhab=='D')
								{
								  $ls_cuentaspg   = number_format($ldec_monto,2,",",".");
								  $ldec_monto_spg = "";
								}
							 else
							    {
								  $ldec_monto_spg = number_format($ldec_monto,2,",",".");
								  $ls_cuentaspg   = "";
							    }
						     $li_i++;
							 $la_data [$li_i] = array('codestpro1'=>'',
											          'codestpro2'=>'',
											          'codestpro3'=>$ls_codestpro3,
											          'spg_cuenta'=>$ls_cuentaspg,
											          'monto_spg'=>$ldec_monto_spg);

						   }
				      }
				   $ls_cuentaspg = $ls_codestpro = $ls_codestpro1 = $ls_codestpro2 = $ls_codestpro3 = $ldec_monto_spg = "";
				   if (array_key_exists("spg_cuenta",$ds_dt_spg->data))
				      {
					    if (array_key_exists($li_s,$ds_dt_spg->data["spg_cuenta"]))
						   {
							 $ls_cuentaspg   = trim($ds_dt_spg->getValue("spg_cuenta",$li_s));
							 $ls_codestpro   = $ds_dt_spg->getValue("estpro",$li_s);
							 $ls_codestpro1  = substr(substr($ls_codestpro,0,20),-9);
							 $ls_codestpro2  = substr($ls_codestpro,21,6);
							 $ls_codestpro3  = substr($ls_codestpro,28,3);						
							 $ldec_monto_spg = number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
						   }
				      }
				   $li_i++;
				   $la_data[$li_i] = array('codestpro1'=>$ls_codestpro1,
										   'codestpro2'=>$ls_codestpro2,
										   'codestpro3'=>$ls_codestpro3,
										   'spg_cuenta'=>$ls_cuentaspg,
										   'monto_spg'=>$ldec_monto_spg);
			     }
		     $li_y = 0;
			 $li_totrows = count($la_data);
			 for ($li_x=1;$li_x<=$li_totrows;$li_x++)
			     {
				   $ls_codestpro1 = $la_data[$li_x]["codestpro1"];
				   if (!empty($ls_codestpro1))
				      {
					    $ls_codestpro2  = $la_data[$li_x]["codestpro2"]; 
						$ls_codestpro3  = $la_data[$li_x]["codestpro3"];
						$ls_cuentaspg   = $la_data[$li_x]["spg_cuenta"];
						$ldec_monto_spg = $la_data[$li_x]["monto_spg"];
					    $li_y++;
						$la_dataux[$li_y] = array('codestpro1'=>$ls_codestpro1,
											      'codestpro2'=>$ls_codestpro2,
											      'codestpro3'=>$ls_codestpro3,
											      'spg_cuenta'=>$ls_cuentaspg,
											      'monto_spg'=>$ldec_monto_spg);
					  }
				 }
			 for ($li_x=1;$li_x<=$li_totrows;$li_x++)
			     {
				   $ls_codestpro1 = $la_data[$li_x]["codestpro1"];
				   $ls_codestpro2 = $la_data[$li_x]["codestpro2"];
				   if (empty($ls_codestpro1) && empty($ls_codestpro2))
				      {
						$ls_codestpro3  = $la_data[$li_x]["codestpro3"];
						$ls_cuentaspg   = $la_data[$li_x]["spg_cuenta"];
						$ldec_monto_spg = $la_data[$li_x]["monto_spg"];
					    $li_y++;
						$la_dataux[$li_y] = array('codestpro1'=>'',
											      'codestpro2'=>'',
											      'codestpro3'=>$ls_codestpro3,
											      'spg_cuenta'=>$ls_cuentaspg,
											      'monto_spg'=>$ldec_monto_spg);
					  }
				 }
			 unset($la_data);
			 $la_data = $la_dataux;
		   }
		uf_print_detalle($la_data,$io_pdf);
		uf_print_retenciones($la_dataret,$io_pdf);
	}
	$io_pdf->ezStream();
	unset($io_pdf,$class_report,$io_funciones);
?> 