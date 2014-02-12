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
	function uf_print_encabezado_pagina($as_titulo,$as_periodo,$ls_banco,$ls_nomtipcta,$ls_ctaban,$as_denctaban,$ldec_saldoant,$as_desconmov,&$io_pdf)
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
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,550,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,540,11,$as_periodo); // Agregar el título
		$io_pdf->addText(700,580,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(700,570,8,date("h:i a")); // Agregar la Hora
        $io_pdf->setStrokeColor(0,0,0);
		$io_pdf->Rectangle(180,525,470,45);
		$io_pdf->line(30,65,750,65);
		if ($as_desconmov!='---seleccione---')
		   {
		     $io_pdf->addText($tm,530,10,'<b>Por Concepto de:</b>'.$as_desconmov); // Agregar el título		   
		   }
		
		$io_pdf->ezSetY(520);
		$la_data=array(array('title'=>'<b>Banco:</b>   ','title2'=>$ls_banco),
					   array('title'=>'<b>Tipo Cuenta:</b>','title2'=>$ls_nomtipcta),
					   array('title'=>'<b>Cuenta:</b>   ','title2'=>$ls_ctaban.' - '.$as_denctaban),
					   array('title'=>'<b>Saldo Anterior:</b>','title2'=>$ldec_saldoant));
		$la_columna=array('title'=>'','title2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>176, // Orientación de la tabla
						 'width'=>280, // Ancho de la tabla
						 'maxWidth'=>280,
						 'fontSize'=>9,
						 'cols'=>array('title'=>array('justification'=>'left','width'=>85),'title2'=>array('justification'=>'left','width'=>195))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
		unset($la_data);
		unset($la_columna);
		unset($la_config);		
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($li_totrow,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: $li_totrow : Número de Filas encontradas.
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime los datos basicos del cheque
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 24/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		if($li_totrow>0)
			{
			$la_data[0]=array('fecha'=>'<b>Fecha</b>','operacion'=>'<b>Operacion</b>','numdoc'=>'<b>Documento</b>','estmov'=>'<b>Estatus</b>','beneficiario'=>'<b>Proveedor/Beneficiario</b>','region'=>'<b>Región</b>','codestpro'=>'<b>Estructura Presupuestaria</b>','spgcta'=>'<b>Cuenta</b>','denctaspg'=>'<b>Denominación</b>','monto'=>'<b>Monto</b>');
			$la_columna=array('fecha'=>'','operacion'=>'','numdoc'=>'','estmov'=>'','beneficiario'=>'','region'=>'','codestpro'=>'','spgcta'=>'','denctaspg'=>'','monto'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'showLines'=>2, // Mostrar Líneas
							 'shaded'=>2, // Sombra entre líneas
							 'shadeCol2'=>array(0.85,0.85,0.85), // Color de la sombra
							 'shadeCol'=>array(1.5,1.5,1.5), // Color de la sombra
							 'xOrientation'=>'center', // Orientación de la tabla
							 'colGap'=>1, // Orientación de la tabla
							 'width'=>735, // Ancho de la tabla
							 'maxWidth'=>735,
							 'fontSize'=>9,
							 'cols'=>array('fecha'=>array('justification'=>'center','width'=>45),
										   'operacion'=>array('justification'=>'center','width'=>50),
										   'numdoc'=>array('justification'=>'center','width'=>70),
										   'estmov'=>array('justification'=>'center','width'=>40),
										   'beneficiario'=>array('justification'=>'center','width'=>130),
										   'region'=>array('justification'=>'center','width'=>50),
										   'codestpro'=>array('justification'=>'center','width'=>140),									   
										   'spgcta'=>array('justification'=>'center','widht'=>45),
										   'denctaspg'=>array('justification'=>'left','width'=>80),
										   'monto'=>array('justification'=>'right','width'=>80))); // Ancho Máximo de la tabla
								 
			$io_pdf->ezTable($la_data,$la_columna,'',$la_config); 
		}
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

		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
		$la_data=array(array('totales'=>'<b>TOTALES:</b>','debitos'=>"Débitos =".$ldec_debe,'creditos'=>"Créditos = ".$ldec_haber,'saldo'=>"Saldo = ".$ldec_saldoactual));							  
		$la_columna=array('totales'=>'','debitos'=>'','creditos'=>'','saldo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.95,0.95,0.95), // Color de la sombra
						 'shadeCol2'=>array(1.5,1.5,1.5), // Color de la sombra
						 'xPos'=>520, // Orientación de la tabla
						 'width'=>735, // Ancho de la tabla
						 'maxWidth'=>735,
						 'fontSize'=>8,
						 'cols'=>array('totales'=>array('justification'=>'center','width'=>80),									   
									   'debitos'=>array('justification'=>'center','width'=>130),
									   'creditos'=>array('justification'=>'right','width'=>130),
									   'saldo'=>array('justification'=>'right','width'=>130))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
	}// end function uf_print_detalle

	//--------------------------------------------------------------------------------------------------------------------------------
	require_once("sigesp_scb_report.php");
    require_once("../../shared/class_folder/class_sql.php");	
	require_once('../../shared/class_folder/class_pdf.php');
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/class_datastore.php");
			
	$io_include   = new sigesp_include();
	$ls_conect    = $io_include->uf_conectar();
	$io_sql		  = new class_sql($ls_conect);	
	$io_report    = new sigesp_scb_report($ls_conect);
	$io_funciones = new class_funciones();
	$ds_edocta    = new class_datastore();	
	
	$ls_codemp      = $_SESSION["la_empresa"]["codemp"];
	$ls_codban      = $_GET["codban"];
	$ls_nomban      = $_GET["nomban"];
	$ls_ctaban      = $_GET["ctaban"];
	$ls_denctaban   = $_GET["denctaban"];
	$ld_fecdesde    = $_GET["fecdes"];		
	$ld_fechasta    = $_GET["fechas"];	
	$ls_orden       = $_GET["orden"];
	$ls_tiprep      = $_GET["tiprep"];
	$ls_codconmov   = $_GET["codconmov"];
	$ls_desconmov   = $_GET["desconmov"];
	$ls_tipbol      = 'Bs.';
	$ls_tiporeporte = 0;
	$ls_tiporeporte = $_GET["tiporeporte"];
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_scb_reportbsf.php");
		$io_report = new sigesp_scb_reportbsf($ls_conect);
		$ls_tipbol = 'Bs.F.';
	}
	$lb_valido 	  = true;
	$data         = $io_report->uf_generar_estado_cuenta($ls_codemp,$ls_codban,$ls_ctaban,$ls_orden,$ld_fecdesde,$ld_fechasta,&$ldec_saldoant,&$ldec_total_debe,&$ldec_total_haber,true,$ls_tiprep,$ls_codconmov);
	$ds_edocta->data=$data;
	$li_totrows = $ds_edocta->getRowCount("numdoc");
	if ($lb_valido)
	   {
	     set_time_limit(0);
		 $io_pdf=new class_pdf('LETTER','landscape'); // Instancia de la clase PDF
		 $io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		 $io_pdf->ezSetCmMargins(6,3.5,3.5,3.5); // Configuración de los margenes en centímetros
	   	 $ls_nomtipcta     = $io_report->uf_select_data($io_sql,"SELECT * FROM scb_tipocuenta t, scb_ctabanco c WHERE c.codemp='".$ls_codemp."' AND c.ctaban='".$ls_ctaban."' AND c.codtipcta=t.codtipcta ","nomtipcta");	
		 $ldec_saldoactual = ($ldec_saldoant+$ldec_total_debe-$ldec_total_haber);
	     $ldec_saldo       = $ldec_saldoant;
		 $ldec_saldoant    = number_format($ldec_saldoant,2,",",".");
		 uf_print_encabezado_pagina("<b>Libro de Banco $ls_tipbol</b>","<b>Del</b> $ld_fecdesde <b>al</b> $ld_fechasta",$ls_nomban,$ls_nomtipcta,$ls_ctaban,$ls_denctaban,$ldec_saldoant,$ls_desconmov,$io_pdf); // Imprimimos el encabezado de la página
	     $io_pdf->ezStartPageNumbers(720,50,10,'','',1); // Insertar el número de página
		 uf_print_cabecera($li_totrows,$io_pdf); // Imprimimos la cabecera del registro
         $li_estmodest = $_SESSION["la_empresa"]["estmodest"];
		 if ($li_totrows>0)
		 {
		 for ($li_i=1;$li_i<=$li_totrows;$li_i++)
		     {
				$io_pdf->transaction('start'); // Iniciamos la transacción
			    $thisPageNum    = $io_pdf->ezPageCount;
			    $ld_fecmov	    = $io_funciones->uf_convertirfecmostrar($ds_edocta->getValue("fecmov",$li_i));
			    $ls_operacion   = $ds_edocta->getValue("operacion",$li_i);
		   	    $ls_numdoc	    = $ds_edocta->getValue("numdoc",$li_i);
			    $ls_nomproben   = $ds_edocta->getValue("beneficiario",$li_i);
			    $ls_region      = $ds_edocta->getValue("region",$li_i);
				if ($ls_region=='---seleccione---')
				   {
				     $ls_region = "";
				   } 
				$ls_codestpro   = $ds_edocta->getValue("codestpro",$li_i);
			    if ($li_estmodest=='1')
				   {
				     $ls_codestpro1 = substr($ls_codestpro,0,20);
					 $ls_codestpro2 = substr($ls_codestpro,20,6);
					 $ls_codestpro3 = substr($ls_codestpro,26,3);
  				     $ls_codestpro  = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
				   }
				else
				   {
				     $ls_codestpro1 = substr($ls_codestpro,18,2);
					 $ls_codestpro2 = substr($ls_codestpro,24,2);
					 $ls_codestpro3 = substr($ls_codestpro,27,2);
					 $ls_codestpro4 = substr($ls_codestpro,29,2);
					 $ls_codestpro5 = substr($ls_codestpro,31,2);
					 $ls_codestpro  = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3.'-'.$ls_codestpro4.'-'.$ls_codestpro5;
				   }
				$ls_spgcuenta   = $ds_edocta->getValue("spg_cuenta",$li_i);
                $ls_denctaspg   = $ds_edocta->getValue("denctaspg",$li_i);
				$ls_estmov      = $ds_edocta->getValue("estmov",$li_i);
			    $ld_monspg      = $ds_edocta->getValue("monto",$li_i);
			    $la_data[$li_i] = array('fecha'=>$ld_fecmov,'operacion'=>$ls_operacion,'numdoc'=>$ls_numdoc,'estmov'=>$ls_estmov,'beneficiario'=>$ls_nomproben,'region'=>$ls_region,'codestpro'=>$ls_codestpro,'spgcta'=>$ls_spgcuenta,'denctaspg'=>$ls_denctaspg,'monto'=>number_format($ld_monspg,2,",","."));
			 } 
	   	 $la_columna=array('fecha'=>'','operacion'=>'','numdoc'=>'','estmov'=>'','beneficiario'=>'','region'=>'','codestpro'=>'','spgcta'=>'','denctaspg'=>'','monto'=>'');
		 $la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol2'=>array(0.95,0.95,0.95), // Color de la sombra
						 'shadeCol'=>array(1.5,1.5,1.5), // Color de la sombra
						 'colGap'=>1,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>735, // Ancho de la tabla
						 'maxWidth'=>735,
						 'fontSize'=>8,
						 'cols'=>array('fecha'=>array('justification'=>'center','width'=>45),
									   'operacion'=>array('justification'=>'center','width'=>50),
									   'numdoc'=>array('justification'=>'center','width'=>70),								    
									   'estmov'=>array('justification'=>'center','width'=>40),
									   'beneficiario'=>array('justification'=>'left','width'=>130),
									   'region'=>array('justification'=>'center','width'=>50),
									   'codestpro'=>array('justification'=>'center','width'=>140),
									   'spgcta'=>array('justification'=>'center','widht'=>45),
									   'denctaspg'=>array('justification'=>'left','width'=>80),
									   'monto'=>array('justification'=>'right','width'=>80))); // Ancho Máximo de la tabla
		 uf_print_detalle($la_columna,$la_config,$la_data,$io_pdf); // Imprimimos el detalle 
		 unset($la_data);
		 uf_print_totales(number_format($ldec_total_debe,2,",","."),number_format($ldec_total_haber,2,",","."),number_format($ldec_saldoactual,2,",","."),$io_pdf); // Imprimimos el detalle
	   	 $io_pdf->transaction('commit');
		 $io_pdf->ezStopPageNumbers(1,1);
		 $io_pdf->ezStream();
		 unset($io_pdf);
		 unset($io_report);
		 unset($io_funciones);
	   }
      else
	   {
		   print("<script language=JavaScript>");
		   print(" alert('No hay nada que Reportar');"); 
		   print(" close();");
		   print("</script>");
	   }
	 }
  else
     {
	   print("<script language=JavaScript>");
	   print(" alert('No hay nada que Reportar');"); 
	   print(" close();");
	   print("</script>");
	 }
?> 