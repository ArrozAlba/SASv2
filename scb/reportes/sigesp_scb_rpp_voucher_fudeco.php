<?PHP
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");//FUDECO.
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
	$li_medidas=16;
	
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
	
	//--------------------------------------------------------------------------------------------------------------------------------
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
		$io_pdf->add_texto($valores[0],$valores[1],12,"<b>***".$ldec_monto."***</b>");
		//Beneficiario del Cheque
		$io_pdf->add_texto($valores[2],$valores[3],12,"<b>$ls_nomproben</b>");
		//Monto en letras del Cheque
		//Cortando el monto en caso de que sea muy largo		
		$ls_monto_cortado=wordwrap($ls_monto,70,"?");
		$la_arreglo=array();
		$la_arreglo=explode("?",$ls_monto_cortado);
		if(array_key_exists(0,$la_arreglo))
			$io_pdf->add_texto($valores[4],$valores[5],11,"<b>$la_arreglo[0]</b>");
		if(array_key_exists(1,$la_arreglo))
			$io_pdf->add_texto($valores[6],$valores[7],11,"<b>$la_arreglo[1]</b>");
		$ls_anio=substr($ls_fecha,-4);
		$ls_fecha_corta=substr($ls_fecha,0,(strlen($ls_fecha)-5));	
		//Fecha del Cheque
		$io_pdf->add_texto($valores[8],$valores[9],11,"<b>$ls_fecha_corta</b>");
		$io_pdf->add_texto($valores[10],$valores[11],11,"<b>$ls_anio</b>");	
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_nomproben,$ls_fecmov,$ls_conmov,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: ls_numdoc : Numero de documento
		//	    		   ls_nomban : Nombre del banco
		//				   ls_cbtan  : Cuenta del banco
		//				   ls_chevau : Voucher del cheque
		//				   ls_nomproben: Nombre del proveedor o beneficiario
		//				   ls_solicitudes: Solicitudes canceladas con el cheque					  
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime los datos basicos del cheque
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 24/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$ls_conmov = wordwrap(strtoupper($ls_conmov),75);
		$io_pdf->add_texto(12,100,11,$ls_conmov);
		
		$li_pos=162.5;//167
		$io_pdf->convertir_valor_mm_px($li_pos);		
		$io_pdf->ezSetY($li_pos);
		$la_data=array(array('cheque'=>'','banco'=>''),
					   array('cheque'=>$ls_numdoc,'banco'=>"    ".$ls_nomban));
		$la_columna=array('cheque'=>'','banco'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' =>10, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>350, // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('cheque'=>array('justification'=>'left','width'=>170),
						 'banco'=>array('justification'=>'left','width'=>250))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,$ai_totrowpre,&$io_pdf,$x_pos)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Néstor Falcon.
		// Fecha Creación: 24/04/2006 						Última Modificación: 05/05/2008.
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_pdf->ezSetY(397);
		if ($ai_totrowpre>0)
		   {
		     $la_config=array('showHeadings'=>0, // Mostrar encabezados
						 	  'fontSize'=>10, // Tamaño de Letras
						 	  'titleFontSize'=>0,  // Tamaño de Letras de los títulos
						 	  'showLines'=>0, // Mostrar Líneas
						 	  'shaded'=>0, // Sombra entre líneas
						 	  'width'=>580, // Ancho de la tabla
						 	  'colGap'=>1,
						 	  'maxWidth'=>580, // Ancho Máximo de la tabla
						 	  'xPos'=>221, // Orientación de la tabla
						 	  'cols'=>array('estpro'=>array('justification'=>'center','width'=>200),
			 						   		'spg_cuenta'=>array('justification'=>'center','width'=>55),
									   		'monto_spg'=>array('justification'=>'right','width'=>135))); // Justificación y ancho de la columna
			 $la_columnas=array('estpro'=>'','spg_cuenta'=>'','monto_spg'=>'');
			 $io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		     $io_pdf->ezSetDy(-15);
		   }
		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>10, // Tamaño de Letras
						 'titleFontSize'=>10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580, // Ancho Máximo de la tabla
						 'colGap'=>1, //Separacion de los caracteres entre las barras de las tablas.
						 'xPos'=>266, // Orientación de la tabla
						 'cols'=>array('scg_cuenta'=>array('justification'=>'center','width'=>100),
			 						   'denctascg'=>array('justification'=>'left','width'=>205),
			 						   'debe'=>array('justification'=>'right','width'=>85),									   
									   'haber'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$la_columnas=array('scg_cuenta'=>'','denctascg'=>'','debe'=>'','haber'=>'');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	function uf_print_totales($ad_montotdeb,$ad_montothab,$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		    Acess: private 
		//	    Arguments: $ad_montotdeb : Monto total de los detalles de los Débitos.
		//                 $ad_montothab : Monto total de los detalles de los Créditos.
		//	   			   io_pdf // Objeto PDF
		//    Description: Imprime la totalizacion de los montos de debitos y los creditos al final del voucher.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 29/01/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	     
		 $io_pdf->set_margenes(0,0,0,0);
		 $io_pdf->ezSetY(141);//135
		 $la_data = array(array('mondeb'=>'<b>'.number_format($ad_montotdeb,2,',','.').'</b>',
		                        'monhab'=>'<b>'.number_format($ad_montothab,2,',','.').'</b>'));
		 $la_config=array('showHeadings'=>0, // Mostrar encabezados
					  'fontSize'=>10, // Tamaño de Letras
					  'titleFontSize'=>0,  // Tamaño de Letras de los títulos
					  'showLines'=>0, // Mostrar Líneas
					  'shaded'=>0, // Sombra entre líneas
					  'width'=>580, // Ancho de la tabla
					  'colGap'=>1,
					  'maxWidth'=>580, // Ancho Máximo de la tabla
					  'xPos'=>421, // Orientación de la tabla
					  'cols'=>array('mondeb'=>array('justification'=>'right','width'=>80),
									'monhab'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$la_columnas=array('mondeb'=>'','monhab'=>'');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}
	
	function uf_print_firmas($io_pdf)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_firmas
	//		    Acess: private 
	//	    Arguments: $io_pdf // Objeto PDF
	//    Description: Imprime las Firmas al pie del formato del voucher.
	//	   Creado Por: Ing. Néstor Falcón.
	// Fecha Creación: 09/05/2008
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $io_pdf->set_margenes(0,0,0,0);
		 $io_pdf->ezSetY(127);//120
		 $la_data = array(array('hecho'=>'Ana Maria Peroza','revisado'=>'Ana C. López','aprobado'=>'Rosa Gutierrez'));
		 $la_config=array('showHeadings'=>0, // Mostrar encabezados
					  'fontSize'=>9, // Tamaño de Letras
					  'titleFontSize'=>0,  // Tamaño de Letras de los títulos
					  'showLines'=>0, // Mostrar Líneas
					  'shaded'=>0, // Sombra entre líneas
					  'width'=>580, // Ancho de la tabla
					  'colGap'=>1,
					  'maxWidth'=>580, // Ancho Máximo de la tabla
					  'xPos'=>140, // Orientación de la tabla
					  'cols'=>array('hecho'=>array('justification'=>'center','width'=>80),
									'revisado'=>array('justification'=>'center','width'=>90),
									'aprobado'=>array('justification'=>'center','width'=>95))); // Justificación y ancho de la columna
		$la_columnas=array('hecho'=>'','revisado'=>'','aprobado'=>'');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);	
	}
	
	//--------------------------------------------------------------------------------------------------------------------------------
	
	require_once('../../shared/class_folder/class_pdf.php');
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
	require_once("../../shared/class_folder/cnumero_letra.php");
	$numalet= new cnumero_letra();
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_codban=$_GET["codban"];
	$ls_ctaban=$_GET["ctaban"];
	$ls_numdoc=$_GET["numdoc"];
	$ls_chevau=$_GET["chevau"];
	$ls_codope=$_GET["codope"];	
	if ($ls_codban=='002')//Banco Provincial.
	   {
	     $ls_archivo="cheque_configurable/medidas_provincial.txt";
		 $ls_contenido="144.00-0.00-24.00-20.00-26.00-30.00-27.00-33.00-22.00-38.00-79.00-38.00-120.00-60.00-130.00-65.00-10.00-91.00-8.00-100.00-12.00-117.00";
	 	 $li_medidas=22;
	  	 $lb_concepto=false;
	   }
	else
	   {
	     $ls_archivo="cheque_configurable/medidas.txt";
		 $ls_contenido="167.00-132.00-65.00-148.00-65.00-154.00-65.00-157.00-65.00-168.00-80.00-168.00-1000.00-1000.00-1000.00-1000.00";
		 $li_medidas=16;
	  	 $lb_concepto=false;
	   }			
	uf_inicializar_variables($ls_archivo,$ls_contenido,$li_medidas);
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
	$io_pdf->set_margenes(0,0,$x_pos,0);	
	$li_totrow=$ds_voucher->getRowCount("numdoc");
	$io_pdf->transaction('start'); // Iniciamos la transacción
	$thisPageNum=$io_pdf->ezPageCount;
	for($li_i=1;$li_i<=$li_totrow;$li_i++)
	{
		unset($la_data);
		$li_totprenom = 0;
		$ldec_mondeb  = 0;
		$ldec_monhab  = 0;
		$li_totant    = 0;
		$ls_numdoc		= $ds_voucher->data["numdoc"][$li_i];
		$ls_codban		= $ds_voucher->data["codban"][$li_i];
		$ls_nomban		= $class_report->uf_select_data($io_sql,"SELECT nomban FROM scb_banco WHERE codban ='".$ls_codban."' AND codemp='".$ls_codemp."'","nomban");
		$ls_ctaban		= $ds_voucher->data["ctaban"][$li_i];
		$ls_chevau		= $ds_voucher->data["chevau"][$li_i];
		$ld_fecmov      = $io_funciones->uf_convertirfecmostrar($ds_voucher->data["fecmov"][$li_i]);
		$ls_nomproben   = $ds_voucher->data["nomproben"][$li_i];
		$ls_solicitudes = $class_report->uf_select_solicitudes($ls_numdoc,$ls_codban,$ls_ctaban);
		$ls_conmov		= $ds_voucher->getValue("conmov",$li_i);
		$ldec_monret	= $ds_voucher->getValue("monret",$li_i);
		$ldec_monto		= $ds_voucher->getValue("monto",$li_i);
		$ldec_total		= $ldec_monto-$ldec_monret;
		$ls_monto		= $numalet->uf_convertir_letra($ldec_total,'','');
		$io_encabezado	= $io_pdf->openObject();
		uf_print_encabezado_pagina(number_format($ldec_total,2,",","."),$ls_nomproben,$ls_monto,$_SESSION["la_empresa"]["ciuemp"].", ".$ld_fecmov,$io_pdf,$y_pos); // Imprimimos el encabezado de la página
		uf_print_cabecera($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_nomproben,$ld_fecmov,$ls_conmov,$io_pdf); // Imprimimos la cabecera del registro
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		$ds_dt_scg->data=$class_report->uf_cargar_dt_scg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope); // Obtenemos el detalle del reporte
		$ds_dt_spg->data=$class_report->uf_cargar_dt_spg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope);
		$la_campos=array("scg_cuenta","debhab");
		$la_monto=array("monto");
		$ds_dt_scg->group_by($la_campos,$la_monto,"scg_cuenta");
		$la_campos=array("spg_cuenta","estpro");
		$ds_dt_spg->group_by($la_campos,$la_monto,"spg_cuenta");
		$li_totrow_det=$ds_dt_scg->getRowCount("scg_cuenta");
		$li_totrow_spg=$ds_dt_spg->getRowCount("spg_cuenta");
		$la_contable = array();
		$ld_montotdeb = $ld_montothab = 0;
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Hago un ciclo para unir en una sola matriz los detalles de presupuesto y los contables para proceder luego a pintarlos
		if($li_totrow_det>=$li_totrow_spg)
		{
			for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
			{
				$ls_scg_cuenta = trim($ds_dt_scg->data["scg_cuenta"][$li_s]);
				$ls_denctascg  = $ds_dt_scg->data["denominacion"][$li_s];
				$ls_debhab     = $ds_dt_scg->data["debhab"][$li_s];
				$ldec_monto    = $ds_dt_scg->data["monto"][$li_s];
				if($ls_debhab=='D')
				{
					$ld_montotdeb += $ldec_monto;
					$ldec_mondeb   = number_format($ldec_monto,2,",",".");
					$ldec_monhab   = "";
				}
				else
				{
					$ld_montothab += $ldec_monto;
					$ldec_monhab   = number_format($ldec_monto,2,",",".");
					$ldec_mondeb   = "";
				}
				if (array_key_exists("spg_cuenta",$ds_dt_spg->data))
				   {
					 if (array_key_exists($li_s,$ds_dt_spg->data["spg_cuenta"]))
					    {
						  $ls_cuentaspg   = trim($ds_dt_spg->getValue("spg_cuenta",$li_s));
						  $ls_denctaspg   = $ds_dt_spg->getValue("denominacion",$li_s);	
						  $ls_estpro      = $ds_dt_spg->getValue("estpro",$li_s);	  
						  $ls_codestpro1  = substr($ls_estpro,0,20);
						  $ls_codestpro2  = substr($ls_estpro,21,6);
						  $ls_codestpro3  = substr($ls_estpro,28,3);
						  $ls_estpro      = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
						  $ldec_monto_spg = number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
					    }
					 else
					    {
						  $ls_cuentaspg   = "";
						  $ls_estpro      = "";
						  $ldec_monto_spg = "";
					      $ls_denctaspg   = "";
						}
				   }
				else
				   {
					 $ls_cuentaspg   = "";
					 $ls_estpro      = "";
					 $ldec_monto_spg = "";
					 $ls_denctaspg   = "";
				   }
				$la_data[$li_s]=array('estpro'=>$ls_estpro,'spg_cuenta'=>$ls_cuentaspg,'denctaspg'=>$ls_denctaspg,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'denctascg'=>$ls_denctascg,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);				   
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
							 $ls_denctascg  = $ds_dt_scg->data["denominacion"][$li_s];
							 $ls_debhab     = $ds_dt_scg->data["debhab"][$li_s];
							 $ldec_monto    = $ds_dt_scg->data["monto"][$li_s];
							 if ($ls_debhab=='D')
								{
								  $ld_montotdeb += $ldec_monto;
								  $ldec_mondeb = number_format($ldec_monto,2,",",".");
								  $ldec_monhab = "";
								}
							 else
								{
								  $ld_montothab += $ldec_monto;
								  $ldec_monhab = number_format($ldec_monto,2,",",".");
								  $ldec_mondeb = "";
								}
						   }
					    else
						   {
						     $ls_scg_cuenta = "";
							 $ls_denctascg  = "";
							 $ls_debhab     = "";
							 $ldec_monto	= "";
							 $ldec_mondeb	= "";
							 $ldec_monhab	= "";					
					       }
				      }
				   else
				      {
					    $ls_scg_cuenta="";
					    $ls_denctascg ="";
					    $ls_debhab = "";
					    $ldec_monto= "";
					    $ldec_mondeb="";
					    $ldec_monhab="";					
				      }
				   if (array_key_exists("spg_cuenta",$ds_dt_spg->data))
				      {
					    if (array_key_exists($li_s,$ds_dt_spg->data["spg_cuenta"]))
					       {
							 $ls_cuentaspg   = trim($ds_dt_spg->getValue("spg_cuenta",$li_s));
							 $ls_denctaspg   = $ds_dt_spg->getValue("denominacion",$li_s);	
							 $ls_estpro      = $ds_dt_spg->getValue("estpro",$li_s);	
							 $ls_codestpro1  = substr($ls_estpro,0,20);
							 $ls_codestpro2  = substr($ls_estpro,21,6);
							 $ls_codestpro3  = substr($ls_estpro,28,3);
							 $ls_estpro      = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
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
				   $la_data[$li_s]=array('estpro'=>$ls_estpro,'spg_cuenta'=>$ls_cuentaspg,'denctaspg'=>$ls_denctaspg,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'denctascg'=>$ls_denctascg,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);					  
			     }
		   }
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    if (empty($la_data))
		   {
			 $ls_cuentaspg='';
			 $ls_estpro='';
			 $ldec_monto_spg='';
			 $ls_scg_cuenta='';
			 $ls_denctascg="";
			 $ldec_mondeb='';
			 $ldec_monhab='';
			 $la_data[1]=array('estpro'=>$ls_estpro,'spg_cuenta'=>$ls_cuentaspg,'denctaspg'=>$ls_denctaspg,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'denctascg'=>$ls_denctascg,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
			 $la_data[2]=array('estpro'=>$ls_estpro,'spg_cuenta'=>$ls_cuentaspg,'denctaspg'=>$ls_denctaspg,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'denctascg'=>$ls_denctascg,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
			 $la_data[3]=array('estpro'=>$ls_estpro,'spg_cuenta'=>$ls_cuentaspg,'denctaspg'=>$ls_denctaspg,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'denctascg'=>$ls_denctascg,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
		   }
		
		$io_pdf->y=190;
		$io_pdf->y=440;	
		$io_pdf->set_margenes(138,70,$x_pos,0);
		uf_print_detalle($la_data,$li_totrow_spg,$io_pdf,$x_pos);
	    uf_print_totales($ld_montotdeb,$ld_montothab,$io_pdf);
		uf_print_firmas($io_pdf);
	}
	$io_pdf->ezStopPageNumbers(1,1);
	$io_pdf->ezStream();
	unset($io_pdf);
	unset($class_report);
	unset($io_funciones);
?> 
