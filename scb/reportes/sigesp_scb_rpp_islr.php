<?php
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if (!array_key_exists("la_logusr",$_SESSION))
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
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(20,40,578,40);
		$io_pdf->rectangle(20,40,558,640);
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$io_pdf->addText(200,630,15,"<b>".$as_titulo."</b>"); // Agregar el título
		$io_pdf->addText(470,735,10,"Fecha: ".date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(476,715,10,"Hora: ".date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codigo,$as_denominacion,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_fechadesde // Fecha a partir del cual su buscaran las retenciones.
		//	    		   as_fechahasta // Fecha hasta el cual su buscaran las retenciones.
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 09/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data   =array(array('retencion'=>'<b><i>Retención<i></b>','codigo'=>$as_codigo,'denominacion'=>$as_denominacion));
		$la_columna=array('retencion'=>'','codigo'=>'','denominacion'=>'');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'titleFontSize' =>10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0,
						 'shadeCol2'=>array(1,1,1),
						 'colGap'=>1,
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho Máximo de la tabla
						 'xPos'=>155, // Orientación de la tabla
						 'cols'=>array('retencion'=>array('justification'=>'left','width'=>60),
						               'codigo'=>array('justification'=>'left','width'=>50), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);			
		
		$la_data   =array(array('beneficiario'=>'<b>Beneficiario</b>','solicitud'=>'<b>Factura</b>','fecha'=>'<b>Fecha</b>','monto'=>'<b>Monto</b>','retencion'=>'<b>Retención</b>'));
		$la_columna=array('beneficiario'=>'','solicitud'=>'','fecha'=>'','monto'=>'','retencion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'titleFontSize' =>10,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2,
						 'shadeCol2'=>array(0.86,0.86,0.86),
						 'colGap'=>1,
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('beneficiario'=>array('justification'=>'left','width'=>220),
						               'solicitud'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'retencion'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($as_agente,$as_nombre,$as_rif,$as_nit,$as_telefono,$as_direccion,$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 04/05/2006.
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_titulo = "<b><i>NIT:</i></b>";
		$la_data=array(array('name'=>'<b><i>    Agente de Retención:</i></b>'."  ".$as_agente),
		               array('name'=>'<b><i>    Nombre o Razón Social:</i></b>'."  ".$as_nombre),
					   array('name'=>'<b><i>    RIF:</i></b>'."  ".$as_rif."                                                        ".$ls_titulo."  ".$as_nit),
					   array('name'=>'<b><i>    Direccion: </i></b>'."  ".$as_direccion.'<b><i>    Telefono:   </i></b>'.$as_telefono)
					   );
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array(1,1,1),
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>530),
						               'name'=>array('justification'=>'left','width'=>530),
									   'name'=>array('justification'=>'left','width'=>530)
									   )
					    ); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle.
	//--------------------------------------------------------------------------------------------------------------------------------
     
	 function uf_print_totales($li_filas,$ld_total,&$io_pdf)
	 {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//            Function:  uf_print_totales
		//		        Access:  private 
		//	         Arguments: 
		//           $li_filas:  Número de Registros en el Reporte.
		//           $ld_total:  Monto Total de las Retenciones aplicadas en el Periodo.
		//	  		    io_pdf:  Objeto PDF
		//         Description:  Función que imprime el detalle.
		//	        Creado Por:  Ing. Néstor Falcón.
		//      Fecha Creación:  04/05/2006.
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $la_data=array(array('name'=>'____________________________________________________________________________________________'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>315, // Orientación de la tabla
						 'width'=>530); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		
		$la_data  =array(array('cantidad'=>'<b>Total de Retenciones :</b>','filas'=>$li_filas,'totales'=>'<b>Total Retenido</b>','monto'=>$ld_total));
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' =>8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
 						 'colGap'=>1,
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho Máximo de la tabla
						 'xPos'=>305,
						 'cols'=>array('cantidad'=>array('justification'=>'right','width'=>90),
						               'filas'=>array('justification'=>'left','width'=>20),
									   'totales'=>array('justification'=>'right','width'=>335),
									   'monto'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
	    $la_columna=array('cantidad'=>'','filas'=>'','totales'=>'','monto'=>'');
	    $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	 }
	
	
	function uf_print_formato($as_numsol,$as_concepto,$as_fechapago,$ad_monto,$ad_monret,$ad_porcentaje,$ls_numcon,&$io_pdf)
	{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_formato.
	//		   Access: private 
	//	    Arguments: 
	//     $as_numsol:
	//   $as_concepto:
	//  $as_fechapago:
	//      $ad_monto:
	//     $ad_monret:
	// $ad_porcentaje:
	//         io_pdf:  Objeto PDF
	//    Description:  Función que imprime una linea de división al final de los detalles.
	//	   Creado Por:  Ing. Néstor Falcón.
	// Fecha Creación:  04/05/2006.
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      $la_data    = array(array('solicitud'=>'<b><i>Factura:</i></b>'."  ".$as_numsol.'                                             '.'<b><i>Nro Control: </i></b>'.$ls_numcon));	
	  $la_columna = array('solicitud'=>'');
	  $la_config  = array('showHeadings'=>1, // Mostrar encabezados
					      'fontSize' => 10,  // Tamaño de Letras
					      'showLines'=>0,    // Mostrar Líneas
					      'shaded'=>0,       // Sombra entre líneas
					      'xPos'=>315,       // Orientación de la tabla
					      'width'=>530);     // Ancho Máximo de la tabla
	  $io_pdf->ezTable($la_data,$la_columna,'',$la_config);		       
	 
	  $la_data=array(array('name'=>''));
	  $la_columna=array('name'=>'');
	  $la_config=array('showHeadings'=>0, // Mostrar encabezados
					   'fontSize' => 10, // Tamaño de Letras
					   'showLines'=>0, // Mostrar Líneas
					   'shaded'=>0, // Sombra entre líneas
					   'xOrientation'=>'center', // Orientación de la tabla
					   'width'=>500); // Ancho Máximo de la tabla						 
	  $io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	
      $la_data    = array(array('fecha'=>'<b>Fecha de Pago</b>','monto'=>'<b>Monto Objeto de Retención</b>','porcentaje'=>'<b>% Aplicado</b>','retenido'=>'<b>Total Impuesto Retenido</b>'));	
	  $la_columna = array('fecha'=>'','monto'=>'','porcentaje'=>'','retenido'=>'');
	  $la_config  = array('showHeadings'=>0, // Mostrar encabezados
					      'fontSize' => 10, // Tamaño de Letras
					      'showLines'=>2, // Mostrar Líneas
					      'shaded'=>2, // Sombra entre líneas
					      'shadeCol'=>array(0.9,0.9,0.9),
						  'shadeCol2'=>array(0.9,0.9,0.9),
						  'xOrientation'=>'center', // Orientación de la tabla
					      'colGap'=>1,
						  'width'=>530,
						  'cols'=>array('fecha'=>array('justification'=>'center','width'=>100),
						                'monto'=>array('justification'=>'center','width'=>150),
										'porcentaje'=>array('justification'=>'center','width'=>100),
										'retenido'=>array('justification'=>'center','width'=>150)));
	  $io_pdf->ezTable($la_data,$la_columna,'',$la_config);		
	
	  $la_data    = array(array('fecha'=>$as_fechapago,'monto'=>$ad_monto,'porcentaje'=>$ad_porcentaje,'retenido'=>$ad_monret));	
	  $la_columna = array('fecha'=>'','monto'=>'','porcentaje'=>'','retenido'=>'');
	  $la_config  = array('showHeadings'=>0, // Mostrar encabezados
					      'fontSize' => 10, // Tamaño de Letras
					      'showLines'=>2, // Mostrar Líneas
					      'shaded'=>0, // Sombra entre líneas
					      'shadeCol'=>array(0.9,0.9,0.9),
						  'shadeCol2'=>array(0.9,0.9,0.9),
						  'xOrientation'=>'center', // Orientación de la tabla
					      'colGap'=>1,
						  'width'=>530,
						  'cols'=>array('fecha'=>array('justification'=>'center','width'=>100),
						                'monto'=>array('justification'=>'right','width'=>150),
										'porcentaje'=>array('justification'=>'center','width'=>100),
										'retenido'=>array('justification'=>'right','width'=>150)));
	  $io_pdf->ezTable($la_data,$la_columna,'',$la_config);		
	}
	
	function uf_print_sello(&$io_pdf)
	{
	  $io_pdf->line(350,250,540,250);
	  $io_pdf->addText(70,240,10,"AGENTE DE RETENCION");  
	  
	  $io_pdf->line(50,250,230,250);
	  $io_pdf->addText(410,240,10,"BENEFICIARIOS");  
	  
      $io_pdf->rectangle(450,60,110,90); 
      $io_pdf->line(450,80,560,80);
      $io_pdf->addText(485,66,10,'<b>SELLO</b>');
	}	
	
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_scb_c_cmpret_op.php");
	require_once("../../shared/class_folder/sigesp_include.php");
    require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/class_sql.php");
	$io_funcion = new class_funciones();
    $io_in      = new sigesp_include();
	$con        = $io_in->uf_conectar();
    $io_sql     = new class_sql($con);
    $io_report  = new sigesp_scb_c_cmpret_op($con);
    $arr_emp    = $_SESSION["la_empresa"];
    $ls_codemp  = $arr_emp["codemp"];
    $ls_agente  = $arr_emp["nombre"];
   	$ls_titulo  = "<b>Comprobante de Retención de I.S.L.R.</b>";
    
	$ls_codban=$_GET["codban"];
	$ls_ctaban=$_GET["ctaban"];
	$ls_numdoc=$_GET["numdoc"];
	$ls_codope=$_GET["codope"];
	$ls_numdocres=$_GET["numdocres"];
	$ld_fecdocres=$_GET["fecdocres"];
	$ls_nrocontrol=$_GET["nrocontrol"];
	$ls_desope=$_GET["desope"];
	$ldec_monto=$_GET["monto"];
	$ls_tipodestino=$_GET["tipodestino"];
	$ls_codpro=$_GET["codpro"];
	$ls_cedbene=$_GET["cedbene"];
	$ld_fecmov=$_GET["fecmov"];
	$lb_valido=$io_report->uf_generar_islr($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codpro,$ls_cedbene,$ls_tipodestino,$ls_numdocres,$ls_nrocontrol,$ld_fecdocres);
	$li_total=$io_report->ds_retenciones->getRowCount("cod_pro");
	if (!$lb_valido)
	   {
	     print("<script language=JavaScript>");
		 print(" alert('No hay nada que Reportar');"); 
		// print(" close();");
	     print("</script>");
       }
	 else // Imprimimos el reporte
	   {
		 error_reporting(E_ALL);
	     set_time_limit(1800);
 		 $io_pdf=new Cezpdf('LETTER','portrait');                       // Instancia de la clase PDF
		 $io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		 $io_pdf->ezSetCmMargins(7,3,3,3);                            // Configuración de los margenes en centímetros
         for ($z=0;$z<$li_total;$z++)
		     {
			   $i = 1;
			   uf_print_encabezado_pagina($ls_titulo,$io_pdf);    // Imprimimos el encabezado de la página
			   if ($lb_valido)
			      {
				    $ls_tipodestino = $io_report->ds_retenciones->data["tipo_destino"][$i];
					if ($ls_tipodestino=='P')
					   {
					     $ls_nombre    = $io_report->ds_retenciones->data["nompro"][$i];
					     $ls_telefono  = $io_report->ds_retenciones->data["telpro"][$i];
					     $ls_direccion = $io_report->ds_retenciones->data["dirpro"][$i];
				         $ls_rif       = $io_report->ds_retenciones->data["rifpro"][$i];
   	 			         $ls_nit       = $io_report->ds_retenciones->data["nitpro"][$i];
					   }
				    else
					   {
					     $ls_nombre    = $io_report->ds_retenciones->data["nombene"][$i].$io_report->ds_retenciones->data["apebene"][$i];
					     $ls_telefono  = $io_report->ds_retenciones->data["telbene"][$i];
					     $ls_direccion = $io_report->ds_retenciones->data["dirbene"][$i];
				         $ls_rif       = $io_report->ds_retenciones->data["rifben"][$i];
				         $ls_nit       = $io_report->ds_retenciones->data["ced_bene"][$i];						 
					   }						 

				    uf_print_detalle($ls_agente,$ls_nombre,$ls_rif,$ls_nit,$ls_telefono,$ls_direccion,$io_pdf);
					$ls_condoc        = $ls_desope;
					$ls_numcon        = $ls_nrocontrol;
					$ls_fecha_emision = $ld_fecdocres;
					$ld_monobjret     = number_format($io_report->ds_retenciones->data["monobjret"][$i],2,',','.');    
					$ld_monret        = number_format($io_report->ds_retenciones->data["monto"][$i],2,',','.');  
				    $ld_porcentaje    = $io_report->ds_retenciones->data["porded"][$i];
					uf_print_formato($ls_numdoc,$ls_condoc,$ls_fecha_emision,$ld_monobjret,$ld_monret,$ld_porcentaje,$ls_numcon,$io_pdf);
				    uf_print_sello($io_pdf);
				  }
			        if ($z<$li_total-1)
			           {
				         $io_pdf->ezNewPage();  
				       }
				  }
		 $io_pdf->ezStream();
		 unset($io_pdf);
		 unset($io_report); 
	   }
?> 