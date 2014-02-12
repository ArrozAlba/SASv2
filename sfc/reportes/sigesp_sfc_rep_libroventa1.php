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
	if (array_key_exists("hidmes",$_POST))
	   {
	     $li_mes=$_POST["hidmes"];
	   }
    else
	   {
	     $li_mes=$_GET["hidmes"];
	   }	
    if (array_key_exists("hidano",$_POST))
	   {
	     $ls_ano=$_POST["hidano"];
	   }
    else
	   {
	     $ls_ano=$_GET["hidano"];
	   }	
	

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$ls_mes,$ls_ano,$as_dentie,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_periodo="<b>MES :</b>".$ls_mes." "."<b>AÑO :</b>".$ls_ano;	
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,30,1000,30);
		$ancho_img_izq = 15;
   
		$io_pdf->addJpegFromFile('imagenes/logo.jpeg',30,520,920,85); // Agregar Logo
		
		
		$li_tm=$io_pdf->getTextWidth(12,"CVAL CORPORACION VENEZOLANA DE ALIMENTOS, S.A");
		$tm=548-($li_tm/2);
		$io_pdf->addText($tm,548,12,"CVAL CORPORACION VENEZOLANA DE ALIMENTOS, S.A"); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(11,$as_dentie);
		$tm=513-($li_tm/2);
		$io_pdf->addText($tm,538,11,$as_dentie); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=527-($li_tm/2);
		$io_pdf->addText($tm,527,11,$as_titulo); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(8,$ls_periodo);
		$tm=522-($li_tm/2);
		$io_pdf->addText($tm,515,8,$ls_periodo); // Agregar el título
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	
	function uf_print_titulo(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(500);
		$la_data   =array(array('name1'=>'<b>Compras Internas o Importaciones</b>'));
		$la_columna=array('name1'=>'');
		$la_config =array('showHeadings'=>0,     // Mostrar encabezados
						  'fontSize' => 6,       // Tamaño de Letras
						  'titleFontSize' => 8, // Tamaño de Letras de los títulos
						  'showLines'=>1,        // Mostrar Líneas
						  'shaded'=>2,           // Sombra entre líneas
						  'xPos'=>817.5,
						  'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						  'xOrientation'=>'center', // Orientación de la tabla
						  'width'=>130, // Ancho de la tabla
						  'maxWidth'=>130,
						  'cols'=>array('name1'=>array('justification'=>'center','width'=>160))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$la_data=array(array('name1'=>'<b>Nro Oper</b>','name2'=>'<b>Fecha Fact.</b>','name3'=>'<b>RIF</b>','name4'=>'<b>Nombre o Razón Social del Comprador</b>','name8'=>'<b>Nro de Factura</b>','name9'=>'<b>Nro de Control</b>','name10'=>'<b>Nro Nota Débito</b>','name11'=>'<b>Nro Nota Crédito</b>','name12'=>'<b>Tipo de Transacc.</b>','name13'=>'<b>Nro de Factura Afectada</b>','name14'=>'<b>Total de Ventas Incluyendo IVA</b>','name15'=>'<b>Ventas Internas no Grabadas</b>','name16'=>'<b>Base Imponible 8%</b>','name17'=>'<b>8% </b>','name24'=>'<b> Impuesto  8% </b>','name22'=>'<b>Base Imponible 9%</b>','name23'=>'<b> 9% </b>','name25'=>'<b> Impuesto 9% </b>','name18'=>'<b>Impuesto IVA </b>','name19'=>'<b>IVA Retenido</b>'));
		$la_columna=array('name1'=>'','name2'=>'','name3'=>'','name4'=>'','name8'=>'','name9'=>'','name10'=>'','name11'=>'','name12'=>'','name13'=>'','name14'=>'','name15'=>'','name16'=>'','name17'=>'','name24'=>'','name22'=>'','name23'=>'','name25'=>'','name18'=>'','name19'=>'');
		$la_config=array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 5,       // Tamaño de Letras
						 'titleFontSize' => 5, // Tamaño de Letras de los títulos
						 'showLines'=>1,        // Mostrar Líneas
						 'shaded'=>2,           // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990,
						 'colGap'=>0.5,
						 'cols'=>array('name1'=>array('justification'=>'center','width'=>15), // Justificación y ancho de la columna Nro de Operacion.
						 			   'name2'=>array('justification'=>'left','width'=>28), // Justificación y ancho de la columna RIF.
						 			   'name3'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna Número de la Fcatura.
						 			   'name4'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna Nombre o Razón Social.
									   'name8'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna Nro de Factura.   
									   'name9'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna Nro de Control.
						 			   'name10'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'name11'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'name12'=>array('justification'=>'center','width'=>25), // Justificación y ancho de la columna
									   'name13'=>array('justification'=>'center','width'=>45), // Justificación y ancho de la columna
						 			   'name14'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'name15'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'name16'=>array('justification'=>'center','width'=>40), // Justificación y ancho de la columna									
									   'name17'=>array('justification'=>'center','width'=>20), // Justificación y ancho de la columna
									   'name24'=>array('justification'=>'center','width'=>40), 
									   									   
									   'name22'=>array('justification'=>'center','width'=>40), // Justificación y ancho de la columna
									   'name23'=>array('justification'=>'center','width'=>20), // Justificación y ancho de la columna
									   'name25'=>array('justification'=>'center','width'=>40),
									   
									   'name18'=>array('justification'=>'right','width'=>40),
									   
									   'name19'=>array('justification'=>'center','width'=>35))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

function uf_print_totales($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		

		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$la_data=array(array('name1'=>'','name2'=>'','name3'=>'','name4'=>'','name6'=>'','name8'=>'','name9'=>'','name10'=>''));
		$la_columna=array('name1'=>'','name2'=>'','name3'=>'','name4'=>'','name5'=>'','name6'=>'','name7'=>'','name8'=>'','name11'=>'', 'name10'=>'','name9'=>'');
		$la_config=array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 5,       // Tamaño de Letras
						 'showLines'=>1,        // Mostrar Líneas
						 'shaded'=>2,           // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'right', // Orientación de la tabla
						 'xPos'=>498,
						 'width'=>890, // Ancho de la tabla
						 'maxWidth'=>890,
						 'colGap'=>0.5,
						 'cols'=>array('name1'=>array('justification'=>'center','width'=>45), // Justificación y ancho de la columna Nro de Operacion.
						 			   'name2'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
						 			   'name3'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'name4'=>array('justification'=>'right','width'=>40), // Justificación y ancho de la columna
						 			   'name5'=>array('justification'=>'right','width'=>20),
									   
									   'name6'=>array('justification'=>'right','width'=>40), // Justificación y ancho de la columna 
									   'name7'=>array('justification'=>'right','width'=>40), // Justificación y ancho de la columna 
									   'name8'=>array('justification'=>'right','width'=>20), 
									   
									   'name11'=>array('justification'=>'right','width'=>40),//Justificación y ancho de la columna
						 			   									  
										'name10'=>array('justification'=>'right','width'=>40),								  
																		  
									   'name9'=>array('justification'=>'right','width'=>35))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_TotalesGenerales






	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 5, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
 						 'colGap'=>0.5,
						 'cols'=>array('name1'=>array('justification'=>'center','width'=>15), // Justificación y ancho de la columna Nro de Operacion.
						 			   'name2'=>array('justification'=>'left','width'=>28), // Justificación y ancho de la columna RIF.
						 			   'name3'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna Número de la Fcatura.
						 			   'name4'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la columna Nombre o Razón Social.
									  
						 			   'name8'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna Nro de Factura.   
									   'name9'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna Nro de Control.
						 			   'name10'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'name11'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'name12'=>array('justification'=>'center','width'=>25), // Justificación y ancho de la columna
									   'name13'=>array('justification'=>'center','width'=>45), // Justificación y ancho de la columna
						 			   'name14'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
						 			   'name15'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'name16'=>array('justification'=>'right','width'=>40), // Justificación y ancho de la columna
		     						   'name17'=>array('justification'=>'right','width'=>20), // Justificación y ancho de la columna
									   'name24'=>array('justification'=>'right','width'=>40), 
									   									  
									   'name22'=>array('justification'=>'right','width'=>40), // Justificación y ancho de la columna
									   'name23'=>array('justification'=>'center','width'=>20), // Justificación y ancho de la columna
									   'name25'=>array('justification'=>'right','width'=>40), 
									   
						 			   'name18'=>array('justification'=>'right','width'=>40),
									   
									   'name19'=>array('justification'=>'right','width'=>35))); // Justificación y ancho de la columna
		$la_columna=array('name1'=>'','name2'=>'','name3'=>'','name4'=>'',
						  'name8'=>'','name9'=>'','name10'=>'','name11'=>'','name12'=>'',
						  'name13'=>'','name14'=>'','name15'=>'','name16'=>'','name17'=>'','name24'=>'','name22'=>'','name23'=>'','name25'=>'', 'name18'=>'','name19'=>'');
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_table_default($ld_monto,$ad_totaldedu9,$totalImpiva,$ad_totbasimp8,$ad_totvenconiva,$ad_totvensiniva,$ad_totbasimp9,$ad_totaldedu8,$ad_basimpga,$ad_totgenadi,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function:  uf_print_pie_cabecera
		//		   Access:  private 
		//	    Arguments:  ai_totprenom // Total Prenómina
		//	   			    ai_totant // Total Anterior
		//	    		    io_pdf // Instancia de objeto pdf
		//    Description:  función que imprime el fin de la cabecera de cada página
		//	   Creado Por:  Ing. Yesenia Moreno
		// Fecha Creación:  27/04/2006    Fecha Última actualización:27/04/2006. 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		

		$ld_monto   = number_format($ld_monto,2,',','.');
		$la_data[0] = array('name1'=>'RESUMEN','name2'=>'','name3'=>'','name5'=>'');
		$la_data[1] = array('name1'=>'Total de Ventas: Incluyendo IVA','name2'=>'','name3'=>$ad_totvenconiva,'name5'=>'');
	    $la_data[2] = array('name1'=>'Ventas Exentas','name2'=>'','name3'=>$ad_totvensiniva,'name5'=>'');
	    $la_data[3] = array('name1'=>'Ventas Gravables al 8,00%','name2'=>'','name3'=>$ad_totbasimp8,'name5'=>'');
		$la_data[4] = array('name1'=>'IVA cobrado al 8,00%','name2'=>'','name3'=>$ad_totaldedu8,'name5'=>'');
		
		$la_data[5] = array('name1'=>'Ventas Gravables al 9,00%','name2'=>'','name3'=>$ad_totbasimp9,'name5'=>'');
		$la_data[6] = array('name1'=>'IVA cobrado al 9,00%','name2'=>'','name3'=>$ad_totaldedu9,'name5'=>'');
		$la_data[7] = array('name1'=>'Total IVA Cobrado','name2'=>'','name3'=>$totalImpiva,'name5'=>'');
	    $la_data[8] = array('name1'=>'','name2'=>'','name3'=>'','name5'=>'');
		$la_columna = array('name1'=>'','name2'=>'','name3'=>'','name5'=>'');
		$la_config  = array('showHeadings'=>0, // Mostrar encabezados
						    'fontSize' => 8, // Tamaño de Letras
						   'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						   'showLines'=>1, // Mostrar Líneas
						   'shaded'=>0, // Sombra entre líneas
						   'width'=>970, // Ancho de la tabla
						   'maxWidth'=>970, // Ancho Máximo de la tabla
						   'xOrientation'=>'center', // Orientación de la tabla
						   'cols'=>array('name0'=>array('justification'=>'center','width'=>256,'showLines'=>1),
						                 'name1'=>array('justification'=>'left','width'=>256), // Justificación y ancho de la columna Nro de Operacion.
						 			     'name2'=>array('justification'=>'center','width'=>15), // Justificación y ancho de la columna RIF.
						 			     'name3'=>array('justification'=>'right','width'=>120), // Justificación y ancho de la columna Número de la Fcatura.
										 'name4'=>array('justification'=>'right','width'=>15), // Justificación y ancho de la columna RIF.
						 			     'name5'=>array('justification'=>'right','width'=>120), // Justificación y ancho de la columna Número de la Fcatura.
						 			     'name6'=>array('justification'=>'right','width'=>110))); // Justificación y ancho de la columna Nro de Comprobante.
										 
		$io_pdf->ezTable($la_data,$la_columna,'   ',$la_config);
	}// end function uf_print_table_default
	//--------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_sfc_c_libroventa.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/class_fecha.php");
	require_once("../../shared/class_folder/class_datastore.php");

	
       $io_fecha = new class_fecha();
	$io_in    = new sigesp_include();
	$con      = $io_in->uf_conectar();
       $io_sql   = new class_sql($con);
	$io_sql2   = new class_sql($con);
	$io_sql3   = new class_sql($con);
	$io_sql5   = new class_sql($con);
	$io_datastore5= new class_datastore();
	$io_datastore4= new class_datastore();
	$io_datastore= new class_datastore();
	$io_datastore2= new class_datastore();
	$io_datastore3= new class_datastore();
	$io_report= new sigesp_sfc_c_libroventa($con);
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	
	//---------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo     = "<b>Libro de Ventas</b>";
	$ls_mes        = $io_fecha->uf_load_nombre_mes($li_mes);
	$li_lastday    = $io_fecha->uf_last_day($li_mes,$ls_ano);
	$li_lastday    = substr($li_lastday,0,2);
	$as_fechadesde = $ls_ano.'-'.$li_mes.'-01';
	$as_fechahasta = $ls_ano.'-'.$li_mes.'-'.$li_lastday;
	$ls_periodo    = "MES: ".$ls_mes."    "."AÑO:".$ls_ano."";
	
	//---------------------------------------------------------------------------------------------------------------------------
	$ld_monto    = 0;
	$ld_impuesto = 0;
	$ld_sumcom   = 0;
	$ld_baseimp  = 0;
	$arremp      = $_SESSION["la_empresa"];
       $ls_codemp   = $arremp["codemp"];
	$ls_codtie=$_SESSION["ls_codtienda"];
	
	$lb_valido   = $io_report->uf_load_libro_ventas($as_fechadesde,$as_fechahasta); // Cargar el DS con los datos de la cabecera del reporte
	
	if ($lb_valido==false) // Existe algún error ó no hay registros
	   {
		 print("<script language=JavaScript>");
		 print(" alert('No hay nada que Reportar ');"); 
		 //print(" close();");
		 print("</script>");
	   }
	else // Imprimimos el reporte
	   {
		 error_reporting(E_ALL);
		 set_time_limit(1800);
		// $io_pdf2  = new class_pdf('LETTER','landscape');
		 $io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
		
		 $io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		 $io_pdf->ezSetCmMargins(3.5,3,2.5,2.5); // Configuración de los margenes en centímetros
		 $ls_sql="Select dentie from sfc_tienda where codtie='".$ls_codtie."' ";
	$rs_data=$io_sql->select($ls_sql);
			if($rs_data==false&&($io_sql->message!=""))
			{
				//$io_msg->message("No hay registros");
			}
			else
			{
				if($row=$io_sql->fetch_row($rs_data))
				{
					$la_agrotienda=$io_sql->obtener_datos($rs_data);
					$io_datastore4->data=$la_agrotienda;
					$totrowt=$io_datastore4->getRowCount("dentie");
					//print_r ($la_agrotienda);
					
						//print "Paso".$totrowt; 			
					for($t=0;$t<=$totrowt;$t++)
					{
						
						
						$ls_dentie=$io_datastore4->getValue("dentie",$t);
					}
				}
			}
			//print $ls_dentie;
		 
		 uf_print_encabezado_pagina($ls_titulo,$ls_mes,$ls_ano,$ls_dentie,$io_pdf); // Imprimimos el encabezado de la página
		 
		 $io_pdf->ezStartPageNumbers(970,40,10,'','',1); // Insertar el número de página
		 $aa_items = array('0'=>'numfac');
	     $aa_sumar = array('0'=>'monto');
		 
		 $io_report->ds_libroventa->group_by($aa_items,$aa_sumar,"numfac");
		 $li_totrow       = $io_report->ds_libroventa->getRowCount("numfac");
		 $ld_totbasimp    = 0;
		 $ld_totcomsiniva = 0;
		 $ld_totvenconiva = 0;
		 $ld_totimpuestos = 0;
		 $ld_totimp8      = 0; //Variable de tipo acumulador que almacenara el monto total de los impuestos a 8%.
		 $ld_totimp9     = 0; //Variable de tipo acumulador que almacenara el monto total de los impuestos a 14%.
		 $ld_totimp25     = 0; //Variable de tipo acumulador que almacenara el monto total de los impuestos a 25%.
		
		 $ld_totbasimp8   = 0;
		 $ld_totbasimp9  = 0;
		
		 $totalVentasiva = 0;
   	     $totalVentasint = 0;         
		 $totalBasimp    = 0;
		 $totalImpiva    = 0;
		 $totalIvaper    = 0;
		 $ld_montodev    =0;	
		 $ld_totaldedu8  =0;
		 $ld_totaldedu9  =0;
		 $ls_notacre="";
		 $ld_totalmontoret=0;
		 $ld_totbasimp9f=0;
		 $ld_totbasimp9d=0;
		 $ld_totbasimp8f=0; 
		 $ld_totbasimp8d=0;
		
		   
		 uf_print_cabecera($io_pdf); // Imprimimos la cabecera del registro
		 $ld_baseimp9    =0;
		  $ld_baseimp9s    =0;
		   $ld_baseimp8s    =0;
		 $ld_deduccion9  =0;
		 $ls_porimp9     =0;
		 $ld_deduccion9s=0;
		 $ld_deduccion8s=0;
		 $ld_baseimp8    =0;
		 $ld_deduccion8  =0;
		 $ls_porimp8     =0;
		$ld_montoret=0;		
		
		for ($li_i=1;$li_i<=$li_totrow;$li_i++)
		     {
			   	$ld_sinderiva=0;
					$ld_sinderivaA=0;
			 
			   
			   $ld_monconiva=0;
			   $ld_monret=0;
			   $li_totant     = 0;
			  // $ls_numrecdoc  = substr($io_report->ds_libroventa->data["numfac"][$li_i],4,1)."-".substr($io_report->ds_libroventa->data["numfac"][$li_i],19,25);
			   
			   $lb_existe     = false;
			   $ls_numfac     = $io_report->ds_libroventa->data["numfac"][$li_i];
			   $ls_fecemidoc  = $io_report->ds_libroventa->data["fecemi"][$li_i];
			   $ls_fecemidoc  = substr($ls_fecemidoc,8,2).'/'.substr($ls_fecemidoc,5,2).'/'.substr($ls_fecemidoc,0,4);
			   $ls_tipproben  = $io_report->ds_libroventa->data["numcot"][$li_i];
			   $ls_codtie     = $io_report->ds_libroventa->data["codtiend"][$li_i];
			   $ls_cedbene    = $io_report->ds_libroventa->data["codcli"][$li_i];
			   $ls_estfac     = $io_report->ds_libroventa->data["estfaccon"][$li_i];
			   $ls_rif        = $io_report->ds_libroventa->data["cedcli"][$li_i];
			   $ls_apebene    = $io_report->ds_libroventa->data["razcli"][$li_i];
			   $ls_nombre     = $ls_apebene;	
			   $ld_montotdoc = $io_report->ds_libroventa->data["monto"][$li_i];
			
				 if($ls_estfac=='A') 
			   {
			   		$ls_tiptran="03-Anul";
					$ls_numrecdoc  = substr($io_report->ds_libroventa->data["numfac"][$li_i],4,1)."-".substr($io_report->ds_libroventa->data["numfac"][$li_i],19,25);
					$ls_numcontrol = $ls_numrecdoc;
					$ls_numfacafec="";
					$ld_montotdoc=0;
					$ls_esta="N";
							
					
			   }
			   elseif(($ls_estfac=='N') or ($ls_estfac=='P') or ($ls_estfac=='C') )
			   {
			   		$ls_tiptran="01-Reg";
					$ls_numrecdoc  = substr($io_report->ds_libroventa->data["numfac"][$li_i],4,1)."-".substr($io_report->ds_libroventa->data["numfac"][$li_i],19,25);
					$ls_numcontrol = $ls_numrecdoc;
					$ls_numfacafec="";
					$ls_esta="R";
					
			   }
			   
			
	 
			// DETALLE FACTURA !!!!!!!!!!!
			
$ls_cadena="Select dt.porimp,f.numfac,SUM(dt.canpro*dt.prepro) as baseimponible,SUM((dt.canpro*dt.prepro)*dt.porimp/100) as deduccion from sfc_factura f,sfc_detfactura dt where f.codemp='".$ls_codemp."' and f.codtiend='".$ls_codtie."' and dt.numfac='".$ls_numfac."' and f.numfac=dt.numfac and f.fecemi BETWEEN '".$as_fechadesde."' AND '".$as_fechahasta."' GROUP by f.numfac,dt.porimp,f.fecemi  ORDER BY f.fecemi ASC";			
				
				
		$ls_notacre="";
			$rs_datauni=$io_sql->select($ls_cadena);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				//$io_msg->message("No hay registros");
			}
			else
			{
				if($row=$io_sql->fetch_row($rs_datauni))
				{
					$la_tienda=$io_sql->obtener_datos($rs_datauni);
					$io_datastore3->data=$la_tienda;
					$totrow=$io_datastore3->getRowCount("numfac");
					
					//print_r ($la_tienda);
					
					 $ld_baseimp9    =0;
		 $ld_deduccion9  =0;
		 $ls_porimp9     =0;
		 $ld_baseimp9s   = 0;
		 $ld_baseimp8s   = 0;
		 $ld_baseimp8    =0;
		 $ld_deduccion8  =0;
		 $ld_deduccion8s  =0;
		 $ld_deduccion9s  =0;
		 $ls_porimp8     =0;
		$ld_montoret=0;	
					
		
					for($z=1;$z<=$totrow;$z++)
					{
					
						
						$ls_codcli=$io_datastore3->getValue("numfac",$z);
			  			$ld_porimp = $io_datastore3->getValue("porimp",$z); 
						$ld_baseimp    =$io_datastore3->getValue("baseimponible",$z);
						$ld_deduccion = $io_datastore3->getValue("deduccion",$z);
						
						
						//print "-*-".$ls_codcli."*P*".$ld_porimp."B".$ld_baseimp."D".$ld_deduccion."*--";
					//print $ld_baseimp."---";
					if(($ld_porimp==9) and ($ld_baseimp8!=0))
						{
							$ld_baseimp9   = $ld_baseimp;
							$ld_baseimp9s   = $ld_baseimp;
							$ld_deduccion9 = $ld_deduccion;
							$ld_deduccion9s = $ld_deduccion;
							
							$ls_porimp9=$ld_porimp;
						 
						 	
						}
					
					elseif(($ld_porimp==9) and ($ld_baseimp8==0))
					{
							$ld_baseimp9   =$ld_baseimp;
							$ld_baseimp9s=$ld_baseimp;
							$ld_deduccion9 =$ld_deduccion;	
							$ld_deduccion9s =$ld_deduccion;	
							$ls_porimp9=$ld_porimp;
							
							$ls_porimp8=0;
							$ld_baseimp8=0;
							$ld_baseimp8s=0;
							$ld_deduccion8=0;
							$ld_deduccion8s=0;
					
					}
					
					if(($ld_porimp==8) and ($ld_baseimp9!=0))
					{
							$ld_baseimp8   =$ld_baseimp;
							$ld_baseimp8s=$ld_baseimp;
							$ld_deduccion8 =$ld_deduccion;
							$ld_deduccion8s =$ld_deduccion;		
							$ls_porimp8=$ld_porimp;
							
						
					
					}
					elseif(($ld_porimp==8) and ($ld_baseimp9==0))
					{
							$ld_baseimp8   =$ld_baseimp;
							$ld_baseimp8s=$ld_baseimp;
							$ld_deduccion8 =$ld_deduccion;	
							$ld_deduccion8s =$ld_deduccion;	
							$ls_porimp8=$ld_porimp;
							
							$ls_porimp9=0;
							$ld_baseimp9=0;
							$ld_baseimp9s=0;
							$ld_deduccion9=0;
							$ld_deduccion9s=0;
					}
					
					
					
					
					
					if($ld_porimp==0) //and ($ld_basimp9==0))
					{
							
							
							if ($ld_baseimp9==0)	
							{
								$ls_porimp9=0;
								$ld_baseimp9=0;
								$ld_baseimp9s=0;
								$ld_deduccion9=0;							
								$ld_deduccion9s=0;
							}
							if($ld_baseimp8==0)	
							{
								$ls_porimp8=0;
								$ld_baseimp8=0;
								$ld_baseimp8s=0;
								$ld_deduccion8=0;							
								$ld_deduccion8s=0;
							}
							
					}
					
				
					
				if($ls_esta=="N") 
			   {
			   		
					$ld_porimp =0; 
						$ld_baseimp    =0;
						$ld_deduccion = 0;
						
					
					$ld_monconiva=0;
					$ld_sinderiva=0;
					$ld_sinderivaA=0;
					$ld_deduccion8=0;
					$ld_deduccion9=0;
					
					$ld_baseimp9=0;
					$ld_baseimp8=0;
					$ls_porimp8=0;
					$ls_porimp9=0;
					$ld_deduccion8=0;
					$ld_deduccion9=0;
					$ld_deduccion8s=0;
					$ld_deduccion9s=0;
					
					$ld_baseimp9s=0;
					$ld_baseimp8s=0;
				
			   }
			   elseif($ls_esta=="R")
			   {
			   		 $ld_monconiva = $ld_montotdoc;
					//$ld_sinderiva = $ld_montotdoc;
				
				
				}  
				   
	
		
			   
		//print "--".$totalVentasiva."--";
		//print "--".$totalVentasint."--";
			 
			$ls_sql3="Select cf.numcob as cod,c.montoret from sfc_cobro c, sfc_cobrofactura cf where cf.codemp='".$ls_codemp."' and cf.codtiend='".$ls_codtie."' and cf.codcli='".$ls_cedbene."' and cf.numfac='".$ls_numfac."'  and c.numcob=cf.numcob ORDER BY c.feccob ASC";
			
		//print $ls_sql3;
			$arr_cobro=$io_sql3->select($ls_sql3);
			if($arr_cobro==false&&($io_sql2->message!=""))
			{
				$ld_montoret=0;
			}
			else
			{					
			
				if($row=$io_sql3->fetch_row($arr_cobro))
				{
					
					
					$la_cobro=$io_sql3->obtener_datos($arr_cobro);
					
					//print_r($la_cobro);
					$io_datastore4->data=$la_cobro;
					$totrow1=$io_datastore4->getRowCount("cod");  
				//print "**".$totrow1."**";
			
					for($j=1;$j<=$totrow1;$j++)
					{
					//		print "-".$i."-";
								$ld_montoret=$io_datastore4->getValue("montoret",$j);
					
				   }
			   }
			 }
			 
			//print "-*-".$ld_baseimp8."--";
		//}	
			if($ld_porimp==0) 
			{
					 $ld_sinderiva=  $ld_baseimp;
					 $ld_sinderivaA= $ld_sinderiva;
					 	
					 $ld_sinderiva= number_format($ld_sinderiva,2,',','.');
			}
					else{
				
				
				if($ld_sinderiva=="0,00") 
						{
								 $ld_sinderiva= number_format($ld_sinderiva,2,',','.');
						}
						else 
					{ $ld_sinderiva= $ld_sinderiva;}
				
				
				/*$ld_sinderiva= number_format($ld_sinderiva,2,',','.');/*	//$ld_sinderiva=0;
					// $ld_sinderiva0;
					 $ld_sinderiva =  $ld_montotdoc - $ld_baseimp - $ld_deduccion;
					
				   //print "*".$ld_sinderiva;
					 $ld_sinderivaA= $ld_sinderiva;
					
					 //print "**".$ld_sinderiva."*";
					 if($ld_sinderiva=="-0,00")
					{$ld_sinderiva="0,00";
					 }*/
					 
					} 
			
	
		//print $ld_sinderiva;
			
			
			 
			  $ld_totalmontoret=$ld_totalmontoret+$ld_montoret;
			  
			   
			  /////////  TOTALES GENERALES   //////////////
			   $ld_totmonconiva=$ld_monconiva;
			   
			   $ld_totalimpuesto = $ld_deduccion8s+$ld_deduccion9s;
			   
			
			 
			   
			 //print $ld_deduccion8."--";
			  
			   
			  
			  
			
			 
			  // print $ld_baseimp8s."-B-";
			  }
			   $totalImpiva= $totalImpiva + $ld_totalimpuesto;
			 $ld_baseimp8       = number_format($ld_baseimp8,2,',','.');
			   $ld_baseimp9       = number_format($ld_baseimp9,2,',','.');
			   $ld_montoret       = number_format($ld_montoret,2,',','.');
			   $ld_deduccion8    = number_format($ld_deduccion8,2,',','.');
			//  print $ld_deduccion8s."*--*";
			 //print $ld_deduccion8."**";
			   $ld_deduccion9    = number_format($ld_deduccion9,2,',','.');
			   $ld_monconiva    = number_format($ld_monconiva,2,',','.'); 
			  
			   $ld_total        = 0;
			  // $ld_sinderiva    = number_format($ld_sinderiva,2,',','.');
			   $ls_porimp9 = number_format($ls_porimp9,2,',','.');
			   $ls_porimp8 = number_format($ls_porimp8,2,',','.');
			   $ld_totalimpuesto = number_format($ld_totalimpuesto,2,',','.'); 
			
			 // print "F-".$ld_baseimp8s."-BF-";
			  $ld_totbasimp9f = $ld_totbasimp9f + $ld_baseimp9s;	
			 $ld_totbasimp8f = $ld_totbasimp8f + $ld_baseimp8s;	
			//print "DF".$ld_deduccion8s."DF";
			  $ld_totaldedu8    = $ld_totaldedu8 + $ld_deduccion8s ;
			  $ld_totaldedu9    = $ld_totaldedu9 + $ld_deduccion9s; 
			//print "TF-".$ld_totbasimp8f."-TB-";
			  
			  $la_data[$li_i]=array('name1'=>$li_i,'name2'=>$ls_fecemidoc,'name3'=>$ls_rif,'name4'=>$ls_nombre,
		         	   				'name8'=>$ls_numrecdoc,'name9'=>$ls_numcontrol,'name10'=>'',
									'name11'=>$ls_notacre,'name12'=>$ls_tiptran,'name13'=>$ls_numfacafec,'name14'=>$ld_monconiva,
									'name15'=>$ld_sinderiva,'name16'=>$ld_baseimp8,'name17'=>$ls_porimp8,'name24'=>$ld_deduccion8,
									'name22'=>$ld_baseimp9,'name23'=>$ls_porimp9,'name25'=>$ld_deduccion9,
									'name18'=>$ld_totalimpuesto,'name19'=>$ld_montoret);	
			
			
			//}//else devo==""
			//$ld_baseimp8    =0;
			
			//}
			
			//print "**".$ld_totmonconiva;//."*A".$ld_sinderivaA."**";
			
//			$ld_totmonconiva=$ld_totmonconiva + $ld_monconiva;
			$totalVentasiva = $totalVentasiva +  $ld_totmonconiva;
			$totalVentasint = $totalVentasint + $ld_sinderivaA;	
			
}}
}


	
	/*******************///  CHEQUEAR DEVOLUCIONES DE LAS FACTURAS ///*********************/
 $t=$li_i-1;
$ls_sql= "Select DISTINCT(d.numfac),d.coddev,d.codemp,d.codtiend,d.fecdev,sfc_cliente.cedcli,sfc_cliente.razcli  from sfc_devolucion d,sfc_cliente,sfc_factura f where d.codemp='".$ls_codemp."' and d.codtiend='".$ls_codtie."' and d.numfac=f.numfac and sfc_cliente.codcli=f.codcli  and d.fecdev BETWEEN '".$as_fechadesde."' AND '".$as_fechahasta."'  ORDER BY d.fecdev ASC";
//print $ls_sql;
$arr_devol=$io_sql5->select($ls_sql);
			if($arr_devol==false&&($io_sql5->message!=""))
			{
				
			}
			else
			{					
			
				if($row=$io_sql5->fetch_row($arr_devol))
				{
					
					$la_devol=$io_sql5->obtener_datos($arr_devol);
					
					$io_datastore5->data=$la_devol;
					$totrow3=$io_datastore5->getRowCount("coddev");
			
				for($d=1;$d<=$totrow3;$d++)
					{
				
				
						$ls_coddevol=$io_datastore5->getValue("coddev",$d);
						$ls_rifd=$io_datastore5->getValue("cedcli",$d);
						$ls_nombred=$io_datastore5->getValue("razcli",$d);
						$ls_numfacd=$io_datastore5->getValue("numfac",$d);
						
						$ls_tiptran="02-Dev";
					$ls_numrecdoc="";
					$ls_numfacafec=substr($io_datastore5->getValue("numfac",$d),4,1)."-".substr($io_datastore5->getValue("numfac",$d),19,25);
				

					
$ls_sql2="Select dt.coddev as cod,dt.porimp,SUM(dt.candev*dt.precio) + ((dt.candev*dt.precio)*porimp/100) as montodev,(dt.candev*dt.precio) as baseimponible,SUM((dt.candev*dt.precio)*dt.porimp/100) as deduccion from sfc_detdevolucion dt where dt.codemp='".$ls_codemp."' and dt.codtiend='".$ls_codtie."' and dt.coddev='".$ls_coddevol."'  GROUP by dt.porimp,dt.coddev,dt.candev,dt.precio ORDER BY dt.coddev ASC";							


//$ls_cadena="Select dt.porimp,f.numfac,SUM(dt.canpro*dt.prepro) as baseimponible,SUM((dt.canpro*dt.prepro)*dt.porimp/100) as deduccion from sfc_factura f,sfc_detfactura dt where f.codemp='".$ls_codemp."' and f.codtiend='".$ls_codtie."' and dt.numfac='".$ls_numfac."' and f.numfac=dt.numfac and f.fecemi BETWEEN '".$as_fechadesde."' AND '".$as_fechahasta."' GROUP by f.numfac,dt.porimp,f.fecemi  ORDER BY f.fecemi ASC";

//$ls_sql2="Select dt.coddev as cod,dt.porimp,f.numfac,(dt.candev*dt.precio)+ ((dt.candev*dt.precio)*porimp/100) as montodev,(dt.candev*dt.precio) as baseimponible,(dt.candev*dt.precio)*dt.porimp/100 as deduccion from sfc_devolucion f,sfc_detdevolucion dt where f.codemp='".$ls_codemp."' and f.codtiend='".$ls_codtie."' and dt.coddev=f.coddev and f.fecdev BETWEEN '".$as_fechadesde."' AND '".$as_fechahasta."'  ORDER BY f.fecdev ASC";
			
		//print $ls_sql2;
			$arr_devo=$io_sql2->select($ls_sql2);
			if($arr_devo==false&&($io_sql2->message!=""))
			{
				
			}
			else
			{					
			
				if($row=$io_sql2->fetch_row($arr_devo))
				{
					
					$la_devo=$io_sql2->obtener_datos($arr_devo);
				//print_r($la_devo);
					$io_datastore2->data=$la_devo;
					$totrow2=$io_datastore2->getRowCount("cod");  
				//print "**".$totrow2."**";
			
					//print_r($la_devo);
		 $ld_baseimp9    =0;
		 $ld_deduccion9  =0;
		 $ld_deduccion9s  =0;
		 $ls_porimp9     =0;
		 
		 $ld_baseimp8    =0;
		 $ld_deduccion8  =0;
		 $ld_deduccion8s  =0;
		 $ls_porimp8     =0;
		$ld_baseimp9s=0;
		$ld_baseimp8s=0;	
		$ld_montotdoc =0;
		$ld_sinderiva=0;
		$ld_sinderivaA=0;
	
		 
					for($i=1;$i<=$totrow2;$i++)
					{
					//		print "-".$i."-";
						
					$ls_numcontrol=$ls_numfacafec;	
								$ls_coddev=$io_datastore2->getValue("cod",$i);
								
								$ld_montodev  = $io_datastore2->getValue("montodev",$i); 
								$ld_porimp = $io_datastore2->getValue("porimp",$i); 
			   					
								$ld_baseimp    =$io_datastore2->getValue("baseimponible",$i);
								$ld_deduccion = $io_datastore2->getValue("deduccion",$i);
								
				
						$ls_notacre=substr($ls_coddev,4,1)."-".substr($ls_coddev,19,25);
						
					
		
						$ld_montotdoc = $ld_montotdoc + $ld_montodev;
						
					
					if(($ld_porimp==9) and ($ld_baseimp8!=0))
						{
							$ld_baseimp9   = "-".$ld_baseimp;
							$ld_deduccion9 = "-".$ld_deduccion;
							$ld_deduccion9s=$ld_deduccion;
							$ld_baseimp9s  =$ld_baseimp;
							$ls_porimp9=$ld_porimp;
						 
						 
						}
					
					elseif(($ld_porimp==9) and ($ld_baseimp8==0))
					{
							$ld_baseimp9   ="-".$ld_baseimp;
							$ld_deduccion9 ="-".$ld_deduccion;	
							$ld_deduccion9s=$ld_deduccion;
							$ld_baseimp9s  =$ld_baseimp;
							$ls_porimp9=$ld_porimp;
							
							$ls_porimp8=0;
							$ld_baseimp8=0;
							$ld_baseimp8s =0;
							$ld_deduccion8=0;
							$ld_deduccion8s=0;
							//print "9-8=0";
					
					}
					
					if(($ld_porimp==8) and ($ld_baseimp9!=0))
					{
							$ld_baseimp8   ="-".$ld_baseimp;
							$ld_baseimp8s  =$ld_baseimp;
							$ld_deduccion8 ="-".$ld_deduccion;	
							$ld_deduccion8s=$ld_deduccion;
							$ls_porimp8=$ld_porimp;
							
						
							
					
					}
					elseif(($ld_porimp==8) and ($ld_baseimp9==0))
					{
							$ld_baseimp8   ="-".$ld_baseimp;
							$ld_baseimp8s  =$ld_baseimp;
							$ld_deduccion8 ="-".$ld_deduccion;	
							$ld_deduccion8s=$ld_deduccion;
							$ls_porimp8=$ld_porimp;
							
							$ls_porimp9=0;
							$ld_baseimp9=0;
							$ld_baseimp9s  =0;
					 		$ld_deduccion9=0;
							$ld_deduccion9s=0;
					//print "9-8";
					}
					
					
					if($ld_porimp==0) //and ($ld_basimp9==0))
					{
							
							
							if($ld_baseimp9==0)	
							{
								$ls_porimp9=0;
								$ld_baseimp9=0;
								$ld_baseimp9s  =0;
								$ld_deduccion9=0;
								$ld_deduccion9s=0;							
							//print "b9";
							}
							if($ld_baseimp8==0)	
							{
								$ls_porimp8=0;
								$ld_baseimp8=0;
								$ld_beseimp8s  =0;
								$ld_deduccion8=0;
								$ld_deduccion8s=0;
							//	print "b8";							
							}
							
													
							
					
					}
			
		
			//print "**".$ld_sinderiva."**";	
			//print "--".$ld_baseimp."--";	
			if($ld_porimp==0)
					 {
					 $ld_sinderiva=$ld_baseimp;
					
					 $ld_sinderivaA= $ld_baseimp;
					 
					 $ld_sinderiva="-".number_format($ld_sinderiva,2,',','.');
					 }
					else
					{
					
							
				if($ld_sinderiva=="0,00") 
						{
								 $ld_sinderiva= number_format($ld_sinderiva,2,',','.');
						}
						else 
					{ $ld_sinderiva= $ld_sinderiva;}
					
					
					}
			
					 
					/*if($ld_sinderiva=="-0,00")
					{$ld_sinderiva="0,00";
					 }*/
			
			//print "--".$ld_sinderiva."--";
			 
			 $ld_monconiva = "-".$ld_montotdoc;
						
			
		
			  /////////  TOTALES GENERALES   //////////////
			   
			   //$totalBasimp    = $totalBasimp + $ld_baseimp;
			  
			   $ld_totalimpuesto = $ld_deduccion8s+$ld_deduccion9s;
			   
			
			  
			
			
			
		
					
			}  
			   
			
			    $t++;
			   $totalImpiva= $totalImpiva - $ld_totalimpuesto;
			// $ld_sinderiva    = number_format($ld_sinderiva,2,',','.');
			 
			   
			  
			   $ld_baseimp8       = number_format($ld_baseimp8,2,',','.');
			   $ld_baseimp9       = number_format($ld_baseimp9,2,',','.');
			   
			   $ld_deduccion8    = number_format($ld_deduccion8,2,',','.');
			 // print $ld_deduccion8."--";
			   $ld_deduccion9    = number_format($ld_deduccion9,2,',','.');
			   $ld_monconiva    = number_format($ld_monconiva,2,',','.'); 
			
			  
			   $ls_porimp9 = number_format($ls_porimp9,2,',','.');
			   $ls_porimp8 = number_format($ls_porimp8,2,',','.');
			   
			  
			  
			   $ld_totalimpuesto = number_format($ld_totalimpuesto,2,',','.'); 
			
				
			
			
			// print $ld_deduccion8s."**8S**";
			   $ld_totaldedu8    = $ld_totaldedu8 - $ld_deduccion8s ;
			   $ld_totaldedu9    = $ld_totaldedu9 - $ld_deduccion9s;
			  
			 // print "TD".$ld_totaldedu8."TD*";
			
			//print $ld_baseimp8s."**8S**";
			$ld_totbasimp9d = $ld_totbasimp9d + $ld_baseimp9s;	
			$ld_totbasimp8d = $ld_totbasimp8d + $ld_baseimp8s;
			//print "TD-".$ld_totbasimp8d."**";
			$ld_totalimp=substr($ld_totalimpuesto,0,1);
			//print $ld_totalimp."**";
			if($ld_totalimpuesto=="0,00")
			{
			$ld_totalimpuesto="0,00";
			}else
			  {$ld_totalimpuesto='-'.$ld_totalimpuesto;}
			  
			  $la_data[$t]=array('name1'=>$t,'name2'=>$ls_fecemidoc,'name3'=>$ls_rifd,'name4'=>$ls_nombred,
		         	   				'name8'=>$ls_numrecdoc,'name9'=>$ls_numcontrol,'name10'=>'',
									'name11'=>$ls_notacre,'name12'=>$ls_tiptran,'name13'=>$ls_numfacafec,'name14'=>$ld_monconiva,
									'name15'=>$ld_sinderiva,'name16'=>$ld_baseimp8,'name17'=>$ls_porimp8,'name24'=>$ld_deduccion8,
									'name22'=>$ld_baseimp9,'name23'=>$ls_porimp9,'name25'=>$ld_deduccion9,
									'name18'=>$ld_totalimpuesto,'name19'=>'0,00');			
					 
						
			//}			
			$totalVentasiva = $totalVentasiva - $ld_montotdoc ;
			 $totalVentasint = $totalVentasint - $ld_sinderivaA;
				}
		
			}
			}
			
			
			}
			
			
			}
//print $ld_totbasimp8d."*-*".$ld_totbasimp8f;

$ld_totbasimp8 = $ld_totbasimp8f - $ld_totbasimp8d;			
$ld_totbasimp9 = $ld_totbasimp9f - $ld_totbasimp9d;
	
	//print $ld_totbasimp9;
	//print "-*-".$ld_totbasimp8;	
	 	  uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		  
		 	 $totalVentasiva = number_format($totalVentasiva,2,',','.'); 
			 $totalVentasint = number_format($totalVentasint,2,',','.');
			 $totalImpiva    = number_format($totalImpiva,2,',','.'); 
			 $totalIvaper    = number_format($totalIvaper ,2,',','.'); 
			 $ld_totbasimp9 =  number_format($ld_totbasimp9,2,',','.');
			 $ld_totbasimp8 =  number_format($ld_totbasimp8,2,',','.');
			
			 $ld_totaldedu8 = number_format($ld_totaldedu8,2,',','.');
			 $ld_totaldedu9 = number_format($ld_totaldedu9,2,',','.');
			 $ld_totalmontoret = number_format($ld_totalmontoret,2,',','.');
			
			 $la_datos2[0] = array('name1'=>'TOTAL GENERAL','name2'=>$totalVentasiva,'name3'=>$totalVentasint,		
			 					   'name4'=> $ld_totbasimp8,'name5'=>'' ,'name6'=>$ld_totaldedu8,'name7'=>$ld_totbasimp9,
								   'name8'=>'' ,'name11'=>$ld_totaldedu9, 'name10'=>$totalImpiva,'name9'=>$ld_totalmontoret);
			
			 uf_print_totales($la_datos2,$io_pdf);	
			
		  
		  
		  $ld_totgenadi    = ($ld_totimp8+$ld_totimp9);//Total Compras Internas Afectadas en Alicuota Reducida. Impuestos al 14% y 25%.
		  $ld_basimpga     = ($ld_totbasimp8+$ld_totbasimp9);//Total Base Imponible Compras Internas Afectadas en Alicuota General + Adicional(14% y 25%).
		  $ld_totimpred    = ($ld_totimp8);//Total Compras Internas Afectadas en Alicuota Reducida. Impuestos al 8%.
		  $ld_totimpred    = number_format($ld_totimpred,2,',','.');
		  $ld_basimpga     = number_format($ld_basimpga,2,',','.');
		  $ld_totcomsiniva = number_format($ld_totcomsiniva,2,',','.');
		  $ld_totvenconiva = number_format($ld_totvenconiva,2,',','.');
		  $ld_totbasimp    = number_format($ld_totbasimp,2,',','.');
		  $ld_totimpuestos = number_format($ld_totimpuestos,2,',','.');
		  $ld_totgenadi    = number_format($ld_totgenadi,2,',','.');
uf_print_table_default($ld_monto,$ld_totaldedu9,$totalImpiva,$ld_totbasimp8,$totalVentasiva,$totalVentasint,$ld_totbasimp9,$ld_totaldedu8,$ld_basimpga,$ld_totgenadi,$io_pdf);
		  //uf_print_table_default($ld_monto,$ad_totbasimp,$ad_totimpuestos,$ld_sumcom,$ad_totvenconiva,$ad_totvensiniva,$ad_totbasimp9,$ad_totbasimp8,$ad_basimpga,$ad_totgenadi,&$io_pdf)
		  unset($la_data);			
		  $io_pdf->ezStopPageNumbers(1,1);
		  $io_pdf->ezStream();
		  unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
	
?> 