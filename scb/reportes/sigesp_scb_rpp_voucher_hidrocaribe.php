<?PHP
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");//Ministerio de la Cultura.
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
	$ls_archivo="cheque_configurable/medidas.txt";
	$li_medidas=16;
	//-------------------------------------------------------------------------------------------------
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
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 06/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $valores;
		//Imprimo el monto
		$io_pdf->add_texto($valores[0],$valores[1],9,"<b>***".$ldec_monto."***</b>");
		//Beneficiario del Cheque
		$io_pdf->add_texto($valores[2],$valores[3],9,"<b>$ls_nomproben</b>");
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
        
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_denctaban,$ls_chevau,$ls_nomproben,$ls_solicitudes,$ls_conmov,$ld_fecha,&$io_pdf)
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
		//    Description: Función que imprime los datos basicos del cheque.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 06/06/2007. 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $io_pdf->add_texto(12,116,9,"BANCO:");//Nombre del banco
		$io_pdf->add_texto(30,116,9,$ls_nomban);//Nombre del banco
        $io_pdf->add_texto(120,116,9,"CHEQUE:");//Nombre del banco
		$io_pdf->add_texto(138,116,9,$ls_numdoc);//Numero de documento
        $io_pdf->add_texto(170,116,9,"FECHA:");//Nombre del banco
		$io_pdf->add_texto(186,116,9,$ld_fecha);//fecha de emision
        $io_pdf->add_texto(12,126,9,"CUENTA:");//Nombre del banco
		$io_pdf->add_texto(30,126,9,$ls_ctaban);//Numero de cuenta bancaria
		$io_pdf->add_texto(88,126,9,$ls_denctaban);//Nombre de la cuenta bancaria
        $io_pdf->add_texto(165,126,9,"VOUCHER:");//Nombre del banco		
		$io_pdf->add_texto(184,126,9,substr($ls_chevau,-10));//Numero de voucher
		//$io_pdf->add_texto(24,112,9,$ls_nomproben);//Nombre del beneficiario
		$li_pos=133;
		$io_pdf->convertir_valor_mm_px($li_pos);
		$io_pdf->ezSetY($li_pos);
		$la_data=array(array('lbl'=>'','value'=>' '.$ls_conmov));//Concepto del movimiento
		$la_columna=array('lbl'=>'','value'=>' ');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>250,
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'fontSize' => 9,
						 'cols'=>array('lbl'=>array('justification'=>'left','width'=>60),'value'=>array('justification'=>'left','width'=>410))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->Rectangle(25,416,560,55); 
		$io_pdf->line(25,443,585,443);	//HORIZONTAL	
		$io_pdf->line(330,443,330,470);	//VERTICAL	
		$io_pdf->line(420,418,420,443);	//VERTICAL		
		$io_pdf->line(470,443,470,470);	//VERTICAL		
			

	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,$la_data_spg,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: Función que imprime el detalle
		//	   Creado Por: Ing. Nestor Falcón
		// Fecha Creación: 06/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$li_pos=328;
		$io_pdf->convertir_valor_px_mm($li_pos);

		$io_pdf->set_margenes($li_pos,40,0,0);
		$li_total=1;//Contador total de filas
		$li_max=17;//Maximo de filas aceptado por el formato
		$li_total_data=count($la_data);
		/*$li_total_data++;
		$la_data[$li_total_data]["cuenta"]="";
		$la_data[$li_total_data]["detalle"]="";
		$la_data[$li_total_data]["parciales"]="";
		$la_data[$li_total_data]["debe"]="";
		$la_data[$li_total_data]["haber"]="";
		$li_total_data++;
		$la_data[$li_total_data]["cuenta"]="";
		$la_data[$li_total_data]["detalle"]="";
		$la_data[$li_total_data]["parciales"]="";
		$la_data[$li_total_data]["debe"]="";
		$la_data[$li_total_data]["haber"]="";*/
		/*$li_total_data++;
		$la_data[$li_total_data]["cuenta"]="";
		$la_data[$li_total_data]["detalle"]="Detalle Presupuestario";
		$la_data[$li_total_data]["parciales"]="";
		$la_data[$li_total_data]["debe"]="";
		$la_data[$li_total_data]["haber"]="";*/
		/*$li_total_data++;
		$la_data[$li_total_data]["cuenta"]="";
		$la_data[$li_total_data]["detalle"]="";
		$la_data[$li_total_data]["parciales"]="";
		$la_data[$li_total_data]["debe"]="";
		$la_data[$li_total_data]["haber"]="";*/
		//$la_dataaux=array_merge($la_data,$la_data_spg);
		//$li_total_data=count($la_dataaux);
		///$li_total_data=count($la_data);
		$li_pos=109;
		$io_pdf->convertir_valor_mm_px($li_pos);
		$io_pdf->ezSetY($li_pos);
		$li_pos_x=98;
		$io_pdf->convertir_valor_mm_px($li_pos_x);
		$li_ancho_cta=35;
		$io_pdf->convertir_valor_mm_px($li_ancho_cta);
		$li_ancho_dt=77;
		$io_pdf->convertir_valor_mm_px($li_ancho_dt);
		$li_ancho_monto=26;
		$io_pdf->convertir_valor_mm_px($li_ancho_monto);
		$li_ancho_monto2=26;
		$io_pdf->convertir_valor_mm_px($li_ancho_monto2);
		$li_ancho_monto3=26;
		$io_pdf->convertir_valor_mm_px($li_ancho_monto3);
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>9, // Tamaño de Letras
						 'titleFontSize'=>9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Máximo de la tabla
						 'xPos' =>$li_pos_x,//
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'left','width'=>$li_ancho_cta),
			 						   'detalle'=>array('justification'=>'left','width'=>$li_ancho_dt),
						 			   'parciales'=>array('justification'=>'center','width'=>$li_ancho_monto), // Justificación y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>$li_ancho_monto2), // Justificación y ancho de la columna
						 			   'haber'=>array('justification'=>'right','width'=>$li_ancho_monto3))); // Justificación y ancho de la columna
		$la_columnas=array('cuenta'=>'','detalle'=>'','parciales'=>'','debe'=>'','haber'=>'');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data,$la_columnas,$la_config);			
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

    //------------------------------------------------------------------------------------------------------------------------------
	function uf_print_autorizacion($ldec_totdeb,$ldec_tothab,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_autorizacion
		//		    Acess: private 
		//	    Arguments: io_pdf // Objeto PDF
		//    Description: función el final del voucher 
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 06/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$la_data[0]=array('cuenta'=>'','detalle'=>'','parciales'=>'','debe'=>$ldec_totdeb,'haber'=>$ldec_tothab);
		$li_pos=65;
		$io_pdf->convertir_valor_mm_px($li_pos);
		$io_pdf->ezSetY($li_pos);
		$li_pos_x=98;
		$io_pdf->convertir_valor_mm_px($li_pos_x);
		$li_ancho_cta=35;
		$io_pdf->convertir_valor_mm_px($li_ancho_cta);
		$li_ancho_dt=67;
		$io_pdf->convertir_valor_mm_px($li_ancho_dt);
		$li_ancho_monto=30;
		$io_pdf->convertir_valor_mm_px($li_ancho_monto);
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Máximo de la tabla
						 'xPos' =>$li_pos_x,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>$li_ancho_cta),
			 						   'detalle'=>array('justification'=>'left','width'=>$li_ancho_dt),
						 			   'parciales'=>array('justification'=>'center','width'=>$li_ancho_monto), // Justificación y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>$li_ancho_monto), // Justificación y ancho de la columna
						 			   'haber'=>array('justification'=>'right','width'=>$li_ancho_monto))); // Justificación y ancho de la columna
		$la_columnas=array('cuenta'=>'','detalle'=>'','parciales'=>'','debe'=>'','haber'=>'');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_autorizacion
	//--------------------------------------------------------------------------------------------------------------------------------
uf_inicializar_variables();
require_once("../../shared/class_folder/class_pdf.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_fecha.php");
require_once("sigesp_scb_report.php");
require_once("../../shared/class_folder/class_numero_a_letra.php");

$io_fecha	  = new class_fecha();
$in			  = new sigesp_include();
$con		  = $in->uf_conectar();
$io_sql       = new class_sql($con);	
$class_report = new sigesp_scb_report($con);
$io_funciones = new class_funciones();				
$ds_voucher   = new class_datastore();	
$ds_dt_scg    = new class_datastore();				
$ds_dt_spg    = new class_datastore();
//Instancio a la clase de conversión de numeros a letras.
$numalet= new class_numero_a_letra();
//imprime numero con los valore por defecto
//cambia a minusculas
$numalet->setMayusculas(1);
//cambia a femenino
$numalet->setGenero(1);
//cambia moneda
$numalet->setMoneda(" ");
//cambia prefijo
$numalet->setPrefijo("***");
//cambia sufijo
$numalet->setSufijo("***");
//imprime numero con los cambios

$ls_codemp = $_SESSION["la_empresa"]["codemp"];
$ls_codban = $_GET["codban"];
$ls_ctaban = $_GET["ctaban"];
$ls_numdoc = $_GET["numdoc"];
$ls_chevau = $_GET["chevau"];
$ls_codope = $_GET["codope"];				

	$data = $class_report->uf_cargar_chq_voucher($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
	$class_report->SQL->begin_transaction();
	$lb_valido = $class_report->uf_actualizar_status_impreso($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
	if (!$lb_valido)
	   {
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
	$ldec_totdeb=0;
	$ldec_tothab=0;
	$io_pdf = new class_pdf("letter","portrait");
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica-Bold.afm');
	$io_pdf->set_margenes(0,50,0,0);

	$li_totrow=$ds_voucher->getRowCount("numdoc");
	$io_pdf->transaction('start'); // Iniciamos la transacción
	$thisPageNum=$io_pdf->ezPageCount;
    //print_r ($ds_voucher->data);
	for ($li_i=1;$li_i<=$li_totrow;$li_i++)
	    {
		  unset($la_data);
		  $li_totprenom=0;
		  $ldec_mondeb=0;
		  $ldec_monhab=0;
		  $li_totant=0;
		  $ls_numdoc	  = $ds_voucher->data["numdoc"][$li_i];
		  $ls_codban	  = $ds_voucher->data["codban"][$li_i];
		  $ls_nomban	  = $class_report->uf_select_data($io_sql,"SELECT nomban FROM scb_banco WHERE codban ='".$ls_codban."' AND codemp='".$ls_codemp."'","nomban");
		  $ls_ctaban	  = $ds_voucher->data["ctaban"][$li_i];
		  $ls_denctaban	  = $class_report->uf_select_data($io_sql,"SELECT dencta FROM scb_ctabanco WHERE codban ='".$ls_codban."' AND codemp='".$ls_codemp."' AND ctaban ='".$ls_ctaban."'","dencta");
		  $ls_chevau	  = $ds_voucher->data["chevau"][$li_i];
		  $ld_fecmov	  = $io_funciones->uf_convertirfecmostrar($ds_voucher->data["fecmov"][$li_i]);
		  $ls_mes 		  = $io_fecha->uf_load_nombre_mes(substr($ld_fecmov,3,2));
		  $ls_fecha		  = $_SESSION["la_empresa"]["ciuemp"].", ".substr($ld_fecmov,0,2)." de ".$ls_mes." ".substr($ld_fecmov,-4,4);
		  $ls_nomproben	  = $ds_voucher->data["nomproben"][$li_i];
		  $ls_solicitudes = $class_report->uf_select_solicitudes($ls_numdoc,$ls_codban,$ls_ctaban);
		  $ls_conmov	  = $ds_voucher->getValue("conmov",$li_i);
		  $ldec_monret	  = $ds_voucher->getValue("monret",$li_i);
		  $ldec_monto	  = $ds_voucher->getValue("monto",$li_i);
		  $ldec_total	  = $ldec_monto-$ldec_monret;
		  //Asigno el monto a la clase numero-letras para la conversion.
		  $numalet->setNumero($ldec_total);
		  //Obtengo el texto del monto enviado.
		  $ls_monto= $numalet->letra();
		  uf_print_encabezado_pagina(number_format($ldec_total,2,",","."),strtoupper($ls_nomproben),$ls_monto,$ls_fecha,$io_pdf,$y_pos); // Imprimimos el encabezado de la página
		  uf_print_cabecera($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_denctaban,$ls_chevau,$ls_nomproben,'  ',$ls_conmov,$ld_fecmov,$io_pdf); // Imprimimos la cabecera del registro
		  $ds_dt_scg->data=$class_report->uf_cargar_dt_scg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope); // Obtenemos el detalle del reporte
		  $ds_dt_spg->data=$class_report->uf_cargar_dt_spg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope);
		  $la_campos=array("scg_cuenta");
		  $la_monto=array("monto");
		  $ds_dt_scg->group_by($la_campos,$la_monto,"scg_cuenta");
		  $la_campos=array("spg_cuenta","estpro");
		  $ds_dt_spg->group_by($la_campos,$la_monto,"spg_cuenta");		
		  $li_totrow_det=$ds_dt_scg->getRowCount("scg_cuenta");
		  $li_totrow_spg=$ds_dt_spg->getRowCount("spg_cuenta");
		  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		  if ($li_totrow_det>=0)
		     {
			   for ($li_s=1;$li_s<=$li_totrow_det;$li_s++)
			       {
				     $ls_scg_cuenta   = $ds_dt_scg->data["scg_cuenta"][$li_s];
				     $ls_denominacion = $ds_dt_scg->data["denominacion"][$li_s];
				     $ls_debhab       = $ds_dt_scg->data["debhab"][$li_s];
				     $ldec_monto      = $ds_dt_scg->data["monto"][$li_s];
				     if ($ls_debhab=='D')
				        {
						  $ldec_mondeb=number_format($ldec_monto,2,",",".");
						  $ldec_monhab=" ";
						  $ldec_totdeb=$ldec_totdeb+$ldec_monto;
					    }
					 else
					    {
						  $ldec_monhab=number_format($ldec_monto,2,",",".");
						  $ldec_mondeb=" ";
						  $ldec_tothab=$ldec_tothab+$ldec_monto;
					    }
					 $la_data[$li_s]=array('cuenta'=>$ls_scg_cuenta,'detalle'=>$ls_denominacion,'parciales'=>' ','debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
			       }
			
		     }
		  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		  if (empty($la_data))
		     {
			   $ls_scg_cuenta='';
			   $ls_denominacion='';
			   $ldec_mondeb='';
			   $ldec_monhab='';
			   $la_data[1]=array('cuenta'=>$ls_scg_cuenta,'detalle'=>$ls_denominacion,'parciales'=>' ','debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
		     }
		  if ($li_totrow_spg>=0)
		     {
			   for ($li_s=1;$li_s<=$li_totrow_spg;$li_s++)
		  	       {
				     $ls_spg_cuenta		 = $ds_dt_spg->data["spg_cuenta"][$li_s];
				     $ls_denominacion	 = $ds_dt_spg->data["denominacion"][$li_s];
				     $ldec_monto		 = $ds_dt_spg->data["monto"][$li_s];
				     $ldec_monto         = number_format($ldec_monto,2,",",".");
				     $la_data_spg[$li_s] = array('cuenta'=>$ls_spg_cuenta,'detalle'=>$ls_denominacion,'parciales'=>$ldec_monto,'debe'=>'','haber'=>'');
			       } 
			 }
		  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		  if (empty($la_data_spg))
		     {
			   $ls_spg_cuenta='';
			   $ls_denominacion='';
			   $ldec_monto='';
			   $la_data_spg[1]=array('cuenta'=>$ls_spg_cuenta,'detalle'=>$ls_denominacion,'parciales'=>$ldec_monto,'debe'=>'','haber'=>'');
		     }		
		  uf_print_detalle($la_data,$la_data_spg,$io_pdf); // Imprimimos el detalle		
		  uf_print_autorizacion(number_format($ldec_totdeb,2,",","."),number_format($ldec_tothab,2,",","."),$io_pdf);//Muestra los totales de debe y haber	
	    } 

   	$io_pdf->ezStream();
	unset($io_pdf,$class_report,$io_funciones);
?>
