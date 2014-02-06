<?PHP
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");//SATA.
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
	$valores=array();
	$ls_directorio="cheque_configurable";
	$ls_archivo="cheque_configurable/medidas.txt";
	$li_medidas=16;
	//--------------------------------------------------------------------------------------------------------------------------------
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
	}

	function uf_print_encabezado_pagina($ldec_monto,$ls_nomproben,$ls_monto,$ls_fecha,&$io_pdf,$x)
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
		global $valores;
		//Imprimo el monto
		$io_pdf->add_texto($valores[0],$valores[1],12,"<b>$ldec_monto</b>");
		//Beneficiario del Cheque
		$io_pdf->add_texto($valores[2],$valores[3],12,"<b>$ls_nomproben</b>");
		//Monto en letras del Cheque
		//Cortando el monto en caso de que sea muy largo		
		$ls_monto_cortado=wordwrap($ls_monto,85,"?");
		$ls_anio=substr($ls_fecha,-4);
		$ls_fecha_corta=substr($ls_fecha,0,(strlen($ls_fecha)-5));	
		//Fecha del Cheque
		$io_pdf->add_texto($valores[8],$valores[9],12,"<b>$ls_fecha_corta</b>");
		$io_pdf->add_texto($valores[10],$valores[11],12,"<b>$ls_anio</b>");
		//Informacion de Caducidad
		$io_pdf->add_texto($valores[14],$valores[13],12,"Caduca a los 60 Días");
		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($la_title,$ls_numdoc,$ls_nomban,$ls_ctaban,$ls_denctaban,$ls_chevau,$ls_nomproben,$ls_solicitudes,$ls_conmov,$ld_fecmov,$ldec_total,$ls_monto,&$io_pdf)
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
		
		$li_pos=190;//197
		$io_pdf->convertir_valor_mm_px($li_pos);		
		$io_pdf->ezSetY($li_pos);
		
		$la_data=array(array('voucher'=>'<b>Voucher Número:</b>  '.$ls_chevau.'                                                                                                    <b>Fecha:</b> '.$ld_fecmov));
		$la_columna=array('voucher'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('voucher'=>array('justification'=>'left','width'=>580))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
				
		$la_data=array(array('banco'=>'<b>Banco</b>  ','cheque'=>'<b>Cheque Nº</b>  ','cuenta'=>'<b>Cuenta Nº:</b>  '.$ls_ctaban),
					   array('banco'=>$ls_nomban,'cheque'=>$ls_numdoc,'cuenta'=>$ls_denctaban));
		$la_columna=array('banco'=>'','cheque'=>'','cuenta'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('banco'=>array('justification'=>'left','width'=>193),
						               'cheque'=>array('justification'=>'left','width'=>140),
						 			   'cuenta'=>array('justification'=>'left','width'=>247))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		
		$la_data=array(array('ordenes'=>'<b>Orden(es) de Pago(s):</b> '.$ls_solicitudes),
					   array('ordenes'=>'<b>Beneficiario:</b> '.$ls_nomproben));
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
		
		$la_data=array(array('concepto'=>'<b>He recibido DE LA DIRECCIÓN DE ADMINISTRACIÓN DEL SATA, la cantidad de :</b>   '.$ldec_total),
					   array('concepto'=>$ls_monto),
					   array('concepto'=>'<b>por concepto de :</b> '.rtrim($ls_conmov.' ')),
					   array('concepto'=>''));
		$la_columna=array('concepto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('concepto'=>array('justification'=>'left','width'=>580))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		
		$la_data_title=array($la_title);
		$la_columna=array('title'=>'','title2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('title'=>array('justification'=>'center','width'=>345),
						               'title2'=>array('justification'=>'center','width'=>235))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data_title,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf,$x_pos)
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
		
		//Imprimo los detalles tanto `de presupuesto como contable del movimiento
		$io_pdf->ezSetY(345);
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>580, // Ancho de la tabla
						 'colGap'=>1,
						 'maxWidth'=>580, // Ancho Máximo de la tabla
						 'xPos'=>302.10, // Orientación de la tabla
                         'cols'=>array('estpro'=>array('justification'=>'center','width'=>200),
			 						   'spg_cuenta'=>array('justification'=>'center','width'=>60),
									   'monto_spg'=>array('justification'=>'right','width'=>85),
						 			   'scg_cuenta'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>85), // Justificación y ancho de la columna
						 			   'haber'=>array('justification'=>'right','width'=>85))); // Justificación y ancho de la columna
		$la_columnas=array('estpro'=>'<b>ESTRUCTURA PRESUPUESTARIA</b>',
						   'spg_cuenta'=>'<b>CUENTA</b>',
						   'monto_spg'=>'<b>MONTO</b>',
						   'scg_cuenta'=>'<b>CUENTA</b>',
						   'debe'=>'<b>DEBE</b>',
						   'haber'=>'<b>HABER</b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$io_pdf->ezText('          ',10);//Inserto una linea en blanco
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
		$io_pie = $io_pdf->openObject();
		
	    $ls_preparado  = 'ALBURY MORENO';
		$ls_revisado   = 'CAP.(AV) EDGAR JOSE NUÑEZ';
		$ls_autorizado = 'LIC. JOSE LUIS MARTINEZ B.';

		$io_pdf->add_texto(6,241,9,"<b>$ls_preparado</b>");
		$io_pdf->add_texto(52,241,9,"<b>$ls_revisado</b>");
		$io_pdf->add_texto(114,241,9,"<b>$ls_autorizado</b>");
		
		$li_pos=55;
		$io_pdf->convertir_valor_mm_px($li_pos);		
		$io_pdf->ezSetY($li_pos);	
			
		$la_data[0]=array('preparado'=>'<b>Preparado Por:</b>','revisado'=>'<b>Revisado Por:</b>','autorizado'=>'<b>Autorizado Por:</b>','contabilizado'=>'<b>Contabilizado Por:</b>');
		$la_data[1]=array('preparado'=>'','revisado'=>'','autorizado'=>'','contabilizado'=>'');
		$la_data[2]=array('preparado'=>'','revisado'=>'','autorizado'=>'','contabilizado'=>'');
		$la_data[3]=array('preparado'=>'','revisado'=>'','autorizado'=>'','contabilizado'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('preparado'=>array('justification'=>'left','width'=>120),
			 						   'revisado'=>array('justification'=>'left','width'=>170),
						 			   'autorizado'=>array('justification'=>'left','width'=>170), // Justificación y ancho de la columna
						 			   'contabilizado'=>array('justification'=>'left','width'=>120))); // Justificación y ancho de la columna
		$la_columnas=array('preparado'=>'','revisado'=>'','autorizado'=>'','contabilizado'=>'');
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
		
		$la_data=array(array('nombre'=>'<b>Nombre:</b>  ','cedula'=>'<b>Cédula de Identidad:</b>  ','fecha'=>'<b>Fecha:</b>  ','firma'=>'<b>Firma:</b>  '),
						array('nombre'=>'','cedula'=>'','fecha'=>'','firma'=>''));
		$la_columna=array('nombre'=>'','cedula'=>'','fecha'=>'','firma'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>180),
						               'cedula'=>array('justification'=>'left','width'=>120),
						               'fecha'=>array('justification'=>'left','width'=>100),
									   'firma'=>array('justification'=>'left','width'=>180))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->ezText('                     ',10);
		$io_pdf->closeObject();
		$io_pdf->addObject($io_pie,'all');
	}// end function uf_print_detalle
	function uf_convertir($ls_numero)
	{
		$ls_numero=str_replace(".","",$ls_numero);
		$ls_numero=str_replace(",",".",$ls_numero);
		return $ls_numero;
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	uf_inicializar_variables();
	require_once("sigesp_scb_report.php");
	require_once('../../shared/class_folder/class_pdf.php');
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_datastore.php");
	require_once("../../shared/class_folder/cnumero_letra.php");
	$in			  = new sigesp_include();
	$con		  = $in->uf_conectar();
	$io_sql		  = new class_sql($con);	
	$class_report = new sigesp_scb_report($con);
	$io_funciones = new class_funciones();				
	$ds_voucher	  = new class_datastore();	
	$ds_dt_scg	  = new class_datastore();				
	$ds_dt_spg	  = new class_datastore();
	$numalet	  = new cnumero_letra();
	
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
			$io_pdf->set_margenes(0,0.5,$x_pos,0);	
		
			for ($i=1;$i<=$li_total;$i++)
			{
				$ls_numdoc=$class_report->ds_voucher1->getValue("numdoc",$i);
				
				$ls_chevau=$class_report->ds_voucher1->getValue("chevau",$i);
				$ls_codope='CH';
				
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
					$ls_numdoc		= $ds_voucher->data["numdoc"][$li_i];
					$ls_codban		= $ds_voucher->data["codban"][$li_i];
					$ls_nomban		= $class_report->uf_select_data($io_sql,"SELECT * FROM scb_banco WHERE codban ='".$ls_codban."' AND codemp='".$ls_codemp."'","nomban");
					$ls_ctaban		= $ds_voucher->data["ctaban"][$li_i];
					$ls_denctaban	= $class_report->uf_select_data($io_sql,"SELECT dencta FROM scb_ctabanco WHERE codemp='".$ls_codemp."' AND codban ='".$ls_codban."' AND ctaban ='".$ls_ctaban."'","dencta");
					$ls_chevau		= $ds_voucher->data["chevau"][$li_i];
					$ld_fecmov		= $io_funciones->uf_convertirfecmostrar($ds_voucher->data["fecmov"][$li_i]);
					$ls_nomproben	= $ds_voucher->data["nomproben"][$li_i];
					$ls_nomproben = str_replace(",","",$ls_nomproben);
					$ls_solicitudes = $class_report->uf_select_solicitudes($ls_numdoc,$ls_codban,$ls_ctaban);
					$ls_conmov		= $ds_voucher->getValue("conmov",$li_i);
					$ldec_monret    = $ds_voucher->getValue("monret",$li_i);
					$ldec_monto	    = $ds_voucher->getValue("monto",$li_i);
					$ldec_total	    = $ldec_monto-$ldec_monret;
					$ls_monto	    = $numalet->uf_convertir_letra($ldec_total,'','');
					$io_encabezado  = $io_pdf->openObject();
					uf_print_encabezado_pagina(number_format($ldec_total,2,",","."),$ls_nomproben,$ls_monto,$_SESSION["la_empresa"]["ciuemp"].", ".$ld_fecmov,$io_pdf,$y_pos); // Imprimimos el encabezado de la página
					uf_print_cabecera(array('title'=>'<b>Registro Presupuestario Pago</b>','title2'=>'<b>Registro Contable Pago</b>'),$ls_numdoc,$ls_nomban,$ls_ctaban,$ls_denctaban,$ls_chevau,$ls_nomproben,$ls_solicitudes,$ls_conmov,$ld_fecmov,number_format($ldec_total,2,",","."),$ls_monto,$io_pdf); // Imprimimos la cabecera del registro
					$li_pos_cabecera=$io_pdf->get_alto_disponible();
					$io_pdf->convertir_valor_mm_px($li_pos_cabecera);
					$io_pdf->closeObject();
					$io_pdf->addObject($io_encabezado,'all');
					
					$ds_dt_scg->data=$class_report->uf_cargar_dt_scg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope); // Obtenemos el detalle del reporte
					$la_items = array('0'=>'scg_cuenta');
					$la_suma  = array('0'=>'monto');
					$ds_dt_scg->group_by($la_items,$la_suma,'scg_cuenta');
					
					$ds_dt_spg->data=$class_report->uf_cargar_dt_spg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope);
					$la_items = array('0'=>'estpro','1'=>'spg_cuenta');
					$la_suma = array('0'=>'monto');
					$ds_dt_spg->group_by($la_items,$la_suma,'spg_cuenta');
			
					$li_totrow_det = $ds_dt_scg->getRowCount("scg_cuenta");
					$li_totrow_spg = $ds_dt_spg->getRowCount("spg_cuenta");
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					// Hago un ciclo para unir en una sola matriz los detalles de presupuesto y los contables para proceder luego a pintarlos
					if($li_totrow_det>=$li_totrow_spg)
					{
						for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
						{
							$ls_scg_cuenta = trim($ds_dt_scg->data["scg_cuenta"][$li_s]);
							$ls_debhab     = $ds_dt_scg->data["debhab"][$li_s];
							$ldec_monto    = $ds_dt_scg->data["monto"][$li_s];
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
									$ls_cuentaspg = trim($ds_dt_spg->getValue("spg_cuenta",$li_s));	
									$ls_denctaspg = $ds_dt_spg->getValue("denominacion",$li_s);	
									$ls_estpro    = trim($ds_dt_spg->getValue("estpro",$li_s));
									$ldec_monto_spg=number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
								}
								else
								{
									$ls_cuentaspg=" ";
									$ls_denctaspg = "";
									$ls_estpro=" ";	  
									$ldec_monto_spg=" ";
								}
							}
							else
							{
								$ls_cuentaspg=" ";	
								$ls_estpro=" ";	  
								$ls_denctaspg = "";
								$ldec_monto_spg=" ";
							}
							$la_data[$li_s]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'denctaspg'=>$ls_denctaspg,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
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
									$ls_scg_cuenta = trim($ds_dt_scg->data["scg_cuenta"][$li_s]);
									$ls_debhab     = $ds_dt_scg->data["debhab"][$li_s];
									$ldec_monto    = $ds_dt_scg->data["monto"][$li_s];
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
									$ls_cuentaspg = trim($ds_dt_spg->getValue("spg_cuenta",$li_s));	
									$ls_denctaspg = $ds_dt_spg->getValue("denominacion",$li_s);
									$ls_estpro    = trim($ds_dt_spg->getValue("estpro",$li_s));	  
									$ldec_monto_spg=number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
								}
								else
								{
									$ls_cuentaspg=" ";	
									$ls_estpro=" ";	  
									$ls_denctaspg = "";
									$ldec_monto_spg=" ";
								}
							}
							else
							{
								$ls_cuentaspg=" ";	
								$ls_estpro=" ";	 
								$ls_denctaspg = ""; 
								$ldec_monto_spg=" ";
							}
							$la_data[$li_s]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'denctaspg'=>$ls_denctaspg,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
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
						$la_data[1]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'denctaspg'=>$ls_denctaspg,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
						$la_data[2]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'denctaspg'=>$ls_denctaspg,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
						$la_data[3]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'denctaspg'=>$ls_denctaspg,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
					}
					
					uf_print_autorizacion($io_pdf);		
					$io_pdf->ezSetY($li_pos_cabecera);
					$li_margen=140;
					if($li_pos_cabecera>=390)
					{
						$li_margen=148;
					}
					if($li_pos_cabecera<=371)
					{
						$li_margen=158;
					}
					$io_pdf->set_margenes($li_margen,60,$x_pos,0);		//
					uf_print_detalle($la_data,$io_pdf,$x_pos); // Imprimimos el detalle 	
				}
			
				if ($i<$li_total)
				{			
					$io_pdf->ezNewPage(); // Insertar una nueva página
					$io_pdf->set_margenes(0,0.5,$x_pos,0);	
				}
		
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
		$io_pdf=new class_pdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra	
		$io_pdf->set_margenes(0,0.5,$x_pos,0);	
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
			$ls_numdoc		= $ds_voucher->data["numdoc"][$li_i];
			$ls_codban		= $ds_voucher->data["codban"][$li_i];
			$ls_nomban		= $class_report->uf_select_data($io_sql,"SELECT * FROM scb_banco WHERE codban ='".$ls_codban."' AND codemp='".$ls_codemp."'","nomban");
			$ls_ctaban		= $ds_voucher->data["ctaban"][$li_i];
			$ls_denctaban	= $class_report->uf_select_data($io_sql,"SELECT dencta FROM scb_ctabanco WHERE codemp='".$ls_codemp."' AND codban ='".$ls_codban."' AND ctaban ='".$ls_ctaban."'","dencta");
			$ls_chevau		= $ds_voucher->data["chevau"][$li_i];
			$ld_fecmov		= $io_funciones->uf_convertirfecmostrar($ds_voucher->data["fecmov"][$li_i]);
			$ls_nomproben	= $ds_voucher->data["nomproben"][$li_i];
			$ls_nomproben = str_replace(",","",$ls_nomproben);
			$ls_solicitudes = $class_report->uf_select_solicitudes($ls_numdoc,$ls_codban,$ls_ctaban);
			$ls_conmov		= $ds_voucher->getValue("conmov",$li_i);
			$ldec_monret    = $ds_voucher->getValue("monret",$li_i);
			$ldec_monto	    = $ds_voucher->getValue("monto",$li_i);
			$ldec_total	    = $ldec_monto-$ldec_monret;
			$ls_monto	    = $numalet->uf_convertir_letra($ldec_total,'','');
			$io_encabezado  = $io_pdf->openObject();
			uf_print_encabezado_pagina(number_format($ldec_total,2,",","."),$ls_nomproben,$ls_monto,$_SESSION["la_empresa"]["ciuemp"].", ".$ld_fecmov,$io_pdf,$y_pos); // Imprimimos el encabezado de la página
			uf_print_cabecera(array('title'=>'<b>Registro Presupuestario Pago</b>','title2'=>'<b>Registro Contable Pago</b>'),$ls_numdoc,$ls_nomban,$ls_ctaban,$ls_denctaban,$ls_chevau,$ls_nomproben,$ls_solicitudes,$ls_conmov,$ld_fecmov,number_format($ldec_total,2,",","."),$ls_monto,$io_pdf); // Imprimimos la cabecera del registro
			$li_pos_cabecera=$io_pdf->get_alto_disponible();
			$io_pdf->convertir_valor_mm_px($li_pos_cabecera);
			$io_pdf->closeObject();
			$io_pdf->addObject($io_encabezado,'all');
			
			$ds_dt_scg->data=$class_report->uf_cargar_dt_scg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope); // Obtenemos el detalle del reporte
			$la_items = array('0'=>'scg_cuenta');
			$la_suma  = array('0'=>'monto');
			$ds_dt_scg->group_by($la_items,$la_suma,'scg_cuenta');
			
			$ds_dt_spg->data=$class_report->uf_cargar_dt_spg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope);
			$la_items = array('0'=>'estpro','1'=>'spg_cuenta');
			$la_suma = array('0'=>'monto');
			$ds_dt_spg->group_by($la_items,$la_suma,'spg_cuenta');
	
			$li_totrow_det = $ds_dt_scg->getRowCount("scg_cuenta");
			$li_totrow_spg = $ds_dt_spg->getRowCount("spg_cuenta");
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// Hago un ciclo para unir en una sola matriz los detalles de presupuesto y los contables para proceder luego a pintarlos
			if($li_totrow_det>=$li_totrow_spg)
			{
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_scg_cuenta = trim($ds_dt_scg->data["scg_cuenta"][$li_s]);
					$ls_debhab     = $ds_dt_scg->data["debhab"][$li_s];
					$ldec_monto    = $ds_dt_scg->data["monto"][$li_s];
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
							$ls_cuentaspg = trim($ds_dt_spg->getValue("spg_cuenta",$li_s));	
							$ls_denctaspg = $ds_dt_spg->getValue("denominacion",$li_s);	
							$ls_estpro    = trim($ds_dt_spg->getValue("estpro",$li_s));
							$ldec_monto_spg=number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
						}
						else
						{
							$ls_cuentaspg=" ";
							$ls_denctaspg = "";
							$ls_estpro=" ";	  
							$ldec_monto_spg=" ";
						}
					}
					else
					{
						$ls_cuentaspg=" ";	
						$ls_estpro=" ";	  
						$ls_denctaspg = "";
						$ldec_monto_spg=" ";
					}
					$la_data[$li_s]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'denctaspg'=>$ls_denctaspg,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
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
							$ls_scg_cuenta = trim($ds_dt_scg->data["scg_cuenta"][$li_s]);
							$ls_debhab     = $ds_dt_scg->data["debhab"][$li_s];
							$ldec_monto    = $ds_dt_scg->data["monto"][$li_s];
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
							$ls_cuentaspg = trim($ds_dt_spg->getValue("spg_cuenta",$li_s));	
							$ls_denctaspg = $ds_dt_spg->getValue("denominacion",$li_s);
							$ls_estpro    = trim($ds_dt_spg->getValue("estpro",$li_s));	  
							$ldec_monto_spg=number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
						}
						else
						{
							$ls_cuentaspg=" ";	
							$ls_estpro=" ";	  
							$ls_denctaspg = "";
							$ldec_monto_spg=" ";
						}
					}
					else
					{
						$ls_cuentaspg=" ";	
						$ls_estpro=" ";	 
						$ls_denctaspg = ""; 
						$ldec_monto_spg=" ";
					}
					$la_data[$li_s]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'denctaspg'=>$ls_denctaspg,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
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
				$la_data[1]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'denctaspg'=>$ls_denctaspg,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
				$la_data[2]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'denctaspg'=>$ls_denctaspg,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
				$la_data[3]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'denctaspg'=>$ls_denctaspg,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
			}
			
			uf_print_autorizacion($io_pdf);		
			$io_pdf->ezSetY($li_pos_cabecera);
			$li_margen=140;
			if($li_pos_cabecera>=390)
			{
				$li_margen=148;
			}
			if($li_pos_cabecera<=371)
			{
				$li_margen=158;
			}
			$io_pdf->set_margenes($li_margen,60,$x_pos,0);		//
			uf_print_detalle($la_data,$io_pdf,$x_pos); // Imprimimos el detalle 	
		}
		$io_pdf->ezStopPageNumbers(1,1);
		$io_pdf->ezStream();
		unset($io_pdf);
		unset($class_report);
		unset($io_funciones);
		
	}
?> 
