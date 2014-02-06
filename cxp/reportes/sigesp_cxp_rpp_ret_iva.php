<?php
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "</script>";		
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�ulo del Reporte
		//	    		   ls_codigo // nmero de an�isis
		//	    		   as_fecana // fecha de an�isis
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci� que imprime los encabezados por p�ina
		//	   Creado Por: Ing. Lucena Selena
		// Fecha Creaci�: 14/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		//$io_pdf->line(50,40,960,40);
		$as_titulo="REPORTE GENERAL DE COMPROBANTES DE RETENCION IVA";
		$io_pdf->addText(300,550,12,'<b>'.$as_titulo.'</b>'); 
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],47,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->addText(910,585,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->setStrokeColor(0,0,0);        					
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}
	//--------------------------------------------------------------------------------------------------------------------------------	
	function uf_print_det_ret_iva($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_analisis
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�
		//	   			   io_pdf // Objeto PDF
		//    Description: funci� que imprime el detalle
		//	   Creado Por: Ing. Lucena Selena
		// Fecha Creaci�: 14/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		$la_columna=array('numcom'=>'<b>Nro. Comprobante</b>',
						  'fecrep'=>'<b>Fecha del Comprobante</b>',
						  'perfiscal'=>'<b>Periodo</b>',
  						  'codsujret'=>'<b>Proveedor</b>',		
						  'nomsujret'=>'<b>Nombre</b>',
						  'rif'=>'<b>Rif.</b>',				
  						  'numfac'=>'<b>Nro. Factura</b>',  
  						  'fecfac'=>'<b>Fecha Factura</b>',
						  'numsop'=>'<b>N Orden de Pago</b>',
						  'numdoc'=>'<b>N Cheque</b>'					  
						  );						  	
								  						 
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tama� de Letras
						 'titleFontSize' => 8,  // Tama� de Letras de los t�ulos
						 'showLines'=>2, // Mostrar L�eas
						 'shaded'=>0, // Sombra entre l�eas
						 'width'=>1000, // Ancho de la tabla
						 'maxWidth'=>1000, // Ancho M�imo de la tabla
						 'xPos'=>505, // Orientaci� de la tabla
						 'cols'=>array('numcom'=>array('justification'=>'center','width'=>90), // Justificaci� y ancho de la columna
						 			   'fecrep'=>array('justification'=>'center','width'=>75), // Justificaci� y ancho de la columna
						 			   'perfiscal'=>array('justification'=>'center','width'=>50), // Justificaci� y ancho de la columna
									   'codsujret'=>array('justification'=>'center','width'=>70), // Justificaci� y ancho de la columna
									   'nomsujret'=>array('justification'=>'center','width'=>250),
  						 			   'rif'=>array('justification'=>'center','width'=>80),
   						 			   'numfac'=>array('justification'=>'center','width'=>100),
									   'fecfac'=>array('justification'=>'center','width'=>70),											   		   									   
									   'numsop'=>array('justification'=>'center','width'=>75),
						 			   'numdoc'=>array('justification'=>'center','width'=>80)								   
									   )
						); 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		unset($la_data1);						
	}
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_sql.php");	
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_cxp_class_report.php");	
	require_once("../../shared/class_folder/class_funciones.php");
	
	$in          =new sigesp_include();
	$con         =$in->uf_conectar();
	$io_sql      =new class_sql($con);	
	$io_report   =new sigesp_cxp_class_report();
	$io_funciones=new class_funciones();			
	//----------------------------------------------------  Inicializacion de variables  -----------------------------------------------
	$lb_valido   =false;
	$lb_validobie=false;
	$lb_validoser=false;
	//----------------------------------------------------  Par�etros del encabezado    -----------------------------------------------
	$ls_titulo="LISTADO DE COMPROBANTES DE RETENCION I.V.A";	
	//--------------------------------------------------  Par�etros para Filtar el Reporte  -----------------------------------------
	$ls_org     = "";
	$ls_codemp  = $_SESSION["la_empresa"]["codemp"];
	$ld_periodo = $_SESSION["la_empresa"]["periodo"];	
	$ld_aoo     = substr($ld_periodo,0,4);
	
	
	$ls_cheque    = $_GET["cheque"];
	$ls_orden     = $_GET["orden"];
	$ls_factura   = $_GET["factura"];
	$ld_fecfac    = $_GET["fecfac"];
	$ls_comprobante= $_GET["comp"];
 	$ls_probenf   = $_GET["probenf"];
	$ls_nomprobenf= $_GET["nomprobenf"];
	$ls_rif       = $_GET["rif"];
	$ls_concepto  = $_GET["concepto"];
	$ld_fecdes    = $_GET["fecdesde"];
 	$ld_fechas    = $_GET["fechasta"];
	$ls_periodo   = $_GET["periodo"];
	$ls_codban    = $_GET["codban"];
	$ls_cuenta    = $_GET["cuenta"];
	//--------------------------------------------------------------------------------------------------------------------------------		
	 error_reporting(E_ALL);
	 set_time_limit(1800);					
	 $io_pdf=new Cezpdf('LEGAL','landscape');
	 $io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); 
	 $io_pdf->ezSetCmMargins(3.2,3,3,3); 
	 $io_pdf->ezStartPageNumbers(950,50,10,'','',1);	
	 uf_print_encabezado_pagina($ls_titulo,&$io_pdf); 	
	             
	 if (empty($ls_feccomdes))
	 {
		$ls_feccomdes="01/01/2008";
	 }
	
	 if(empty($ls_feccomhas))
	 {
		$ls_feccomdes="31/12/2008";
	 }
     
	 if(empty($ls_fecfac))
	 {
		$ls_fecfac="01/01/2008";
	 }
	 
	 $ls_codemp		=trim($ls_codemp);
	 $ls_comprobante=trim($ls_comprobante);
	 $ld_fecdes 	=trim($ld_fecdes);
	 $ld_fechas		=trim($ld_fechas);
	 $ls_periodo	=trim($ls_periodo);
	 $ls_probenf	=trim($ls_probenf);
	 $ls_nomprobenf =trim($ls_nomprobenf);
	 $ls_rif 		=trim($ls_rif);
	 $ls_factura	=trim($ls_factura);	
	 $ls_concepto	=trim($ls_concepto);
	 $ls_orden 		=trim($ls_orden);
	 $ls_cheque		=trim($ls_cheque);
	 $ld_fecfac 	=trim($ld_fecfac);
	 $ls_codban		=trim($ls_codban);
	 $ls_cuenta	    =trim($ls_cuenta);	
	 
	 $lb_valido=$io_report->uf_select_rpp_ret_iva($ls_codemp,$ls_comprobante,$ld_fecdes,$ld_fechas,
	                                              $ls_periodo,$ls_probenf,$ls_nomprobenf,$ls_rif,
									              $ls_factura,$ls_concepto,$ls_orden,$ls_cheque,
												  $ld_fecfac,$ls_codban,$ls_cuenta);				
	 if($lb_valido==false)
	 {
		print("<script language=JavaScript>");
		print("alert('No hay nada que Reportar');"); 
		//print("close();");
		print("</script>");
	 } 
	 else 
	 {									
		   $li_totdet=$io_report->ds_rpp_ret->getRowCount("numcom");			   
		   for ($w=1;$w<=$li_totdet;$w++)
		   {		   
				$ls_numcom    = trim($io_report->ds_rpp_ret->data["numcom"][$w]);					
				$ld_fecrep    = trim($io_report->ds_rpp_ret->data["fecrep"][$w]);	
				$ls_perfiscal = trim($io_report->ds_rpp_ret->data["perfiscal"][$w]);	              
				$ls_codsujret = trim($io_report->ds_rpp_ret->data["codsujret"][$w]);	
				$ls_nomsujret = trim($io_report->ds_rpp_ret->data["nomsujret"][$w]);					
				$ls_rif       = trim($io_report->ds_rpp_ret->data["rif"][$w]);	
				$ld_fecfac    = trim($io_report->ds_rpp_ret->data["fecfac"][$w]);	              
				$ls_numfac    = trim($io_report->ds_rpp_ret->data["numfac"][$w]);
				$ls_desope    = trim($io_report->ds_rpp_ret->data["desope"][$w]);	
				$ls_numsop    = trim($io_report->ds_rpp_ret->data["numsop"][$w]);	              
				
				$ls_cheque    = trim($io_report->uf_buscar_cheque($ls_codemp,$ls_numsop,$ls_codban,$ls_cuenta));	
				
				$ls_fecrep        = substr($ld_fecrep,8,2)."/".substr($ld_fecrep,5,2)."/".substr($ld_fecrep,0,4);							
				$ls_fecfac        = substr($ld_fecfac,8,2)."/".substr($ld_fecfac,5,2)."/".substr($ld_fecfac,0,4);							
				$ls_numsop		  = substr($ls_numsop,5,10);
				$ls_cheque		  = substr($ls_cheque,3,12);				
				$la_data[$w]= array('numcom'=>$ls_numcom,
				                    'fecrep'=>$ls_fecrep,
									'perfiscal'=>$ls_perfiscal,
									'codsujret'=>$ls_codsujret,
									'nomsujret'=>$ls_nomsujret,
									'rif'=>$ls_rif,
									'numfac'=>$ls_numfac,
									'fecfac'=>$ls_fecfac,									
									'numsop'=>$ls_numsop,
									'numdoc'=>$ls_cheque
									);														
		  }																		 																						  
		  uf_print_det_ret_iva($la_data,&$io_pdf); 						 						  
		  unset($la_data);							 
	}
	
	$io_report->ds_rpp_ret->resetds("numcom");	
	
	if($lb_valido) // Si no ocurrio ningún error
	{
		$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
		$io_pdf->ezStream(); // Mostramos el reporte
	}
	else  // Si hubo algún error
	{
		print("<script language=JavaScript>");
		print("alert('No hay nada que Reportar');");
		//print("close();");
		print("</script>");		
	}
	unset($io_pdf);			
	unset($io_report);
	unset($io_funciones);
?> 