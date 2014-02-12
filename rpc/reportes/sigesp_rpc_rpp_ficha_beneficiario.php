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
	ini_set('memory_limit','512M');
	ini_set('max_execution_time ','0');

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_encabezadopagina
	//		   Access: private 
	//	    Arguments: as_titulo // Título del Reporte
	//	    		   io_pdf // Instancia de objeto pdf
	//    Description: función que imprime los encabezados por página
	//	   Creado Por: Ing. Yesenia Moreno
	// Fecha Creación: 21/04/2006 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$io_encabezado=$io_pdf->openObject();
	$io_pdf->saveState();
	$io_pdf->line(20,40,578,40);
	$io_pdf->setStrokeColor(0,0,0);
	$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],35,715,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
	$li_tm=$io_pdf->getTextWidth(14,$as_titulo);
	$io_pdf->addText(230,725,14,$as_titulo); // Agregar el título
	$io_pdf->addText(470,735,10,"Fecha: ".date("d/m/Y")); // Agregar la Fecha
	$io_pdf->addText(476,715,10,"Hora: ".date("h:i a")); // Agregar la hora
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');
}// end function uf_print_encabezadopagina
//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_constancia($as_constancia,&$io_pdf)
{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Function: uf_print_constancia
//		   Access: private 
//	    Arguments: la_data // arreglo de información
//	   			   io_pdf // Objeto PDF
//    Description: función que imprime el detalle
//	   Creado Por: Ing. Yesenia Moreno
// Fecha Creación: 21/04/2006 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$io_encabezado=$io_pdf->openObject();
	$io_pdf->saveState();
	$io_pdf->ezSetY(700);
	$la_data    = array(array('constancia'=>'<b>'.$as_constancia.'</b>'));
	$la_columna = array('constancia'=>'');
	$la_config  = array('showHeadings'=>0,
					    'titleFontSize' =>10,
					    'showLines'=>0, 
					    'shaded'=>0,
					    'shadeCol2'=>array(0.86,0.86,0.86),
					    'colGap'=>1,
					    'width'=>520, 
					    'maxWidth'=>520, 
					    'xPos'=>296,
					    'cols'=>array('constancia'=>array('justification'=>'left','width'=>520)));
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');
}// end function uf_print_cabecera_detalle
//------------------------------------------------------------------------------------------------------------------------------



	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("sigesp_rpc_class_report.php");
    require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_in      = new sigesp_include();
	$con        = $io_in->uf_conectar();	
	$io_report  = new sigesp_rpc_class_report($con);
	$io_sql     = new class_sql($con);
	$io_funcion = new class_funciones();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>Certificado de Inscripción</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	if (array_key_exists("hidorden",$_POST))
	   {
		 $li_orden=$_POST["hidorden"];
	   }
	else
	   {
		 $li_orden=$_GET["hidorden"];
	   }
	if (array_key_exists("hidcodproben1",$_POST))
	   {
		 $ls_codproben1 = $_POST["hidcodproben1"];
	   }
	else
	   {
		 $ls_codproben1=$_GET["hidcodproben1"];
	   }
	if (array_key_exists("hidcodproben2",$_POST))
	   {
		 $ls_codproben2 = $_POST["hidcodproben2"];
	   }
	else
	   {
		 $ls_codproben2 = $_GET["hidcodproben2"];
	   }
    $lb_valido  = true;
	$la_empresa = $_SESSION["la_empresa"];
	$ls_codemp  = $la_empresa["codemp"];
    $rs_data    = $io_report->uf_select_beneficiario($li_orden,$ls_codproben1,$ls_codproben2,$lb_valido);
	if ($lb_valido)
	   {
		 error_reporting(E_ALL);
		 set_time_limit(1800);
		 $li_total=$io_sql->num_rows($rs_data);
		 $data=$io_sql->obtener_datos($rs_data);
		 $io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		 $io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		 $io_pdf->ezSetCmMargins(3.8,3,3,3); // Configuración de los margenes en centímetros
		 uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		 $io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		 for ($z=1;$z<=$li_total;$z++)
			 {
			   $ls_cedbene    = $data["ced_bene"][$z];
			   $ls_nombene    = $data["nombene"][$z];
			   $ls_apebene    = $data["apebene"][$z];
			   if (!empty($ls_apebene))
			      {
				    $ls_nombene = $ls_apebene.', '.$ls_nombene;
				  }
			   $ls_rifbene    = $data["rifben"][$z];
			   $ls_telbene    = $data["telbene"][$z];
			   $ls_dirbene    = $data["dirbene"][$z];
			   $ls_fecregben  = $data["fecregben"][$z];
			   $ls_fecreg     = $io_funcion->uf_convertirfecmostrar($ls_fecregben);
			   $ls_constancia = "LA UNIDAD DE REGISTRO Y CONTROL DE CONTRATISTAS DE LA SECRETARIA DE INFRAESTRUCTURA Y SERVICIO DEL ESTADO LARA, HACE CONSTAR QUE LA EMPRESA:"; 
			   uf_print_constancia($ls_constancia,$io_pdf);
		       $io_pdf->addText(40,650,16,'<b>'.$ls_nombene.'</b>'); 
			   $io_pdf->addText(40,630,10,"Domiciliada en:");
			   $io_pdf->addText(110,630,10,'<b>'.$ls_dirbene.'</b>'); 
			   $io_pdf->addText(40,610,10,"Teléfono:"); 
		       $io_pdf->addText(90,610,10,'<b>'.$ls_telbene.'</b>'); 
			   $io_pdf->addText(40,590,10,"RIF:"); 
		       $io_pdf->addText(65,590,10,'<b>'.$ls_rifbene.'</b>');
			   $io_pdf->addText(40,570,10,"Ha sido inscrita en esta secretaría el:"); 
		       $io_pdf->addText(210,570,10,'<b>'.$ls_fecreg.'</b>');
			   $io_pdf->addText(40,550,10,"Bajo el código de registro número:");
			   $io_pdf->addText(200,550,10,'<b>'.$ls_cedbene.'</b>');  
               $io_pdf->addText(40,470,10,'<b>'."PRESENTACION, REVISION Y ADMISION CONFORME DE LOS DOCUMENTOS Y RECAUDOS EXIGIDOS".'</b>'); // Agregar el título
               $io_pdf->line(140,175,500,175);
			   $io_pdf->addText(230,160,10,'<b>'."COORDINACION DE CONTRATACION".'</b>'); // Agregar el título
			   $io_pdf->addText(190,150,10,'<b>'."SECRETARIA DE INFRAESTRUCTURA Y SERVICIOS".'</b>'); // Agregar el título
			   $io_pdf->addText(20,80,10,'<b>'."NOTA:".'</b>'." "."CERTIFICADO EXPEDIDO EN BASE A DATOS APORTADOS POR EL SOLICITANTE"); // Agregar el título
			   if ($z<$li_total)
			      {$io_pdf->ezNewPage();}
 		    }
		if ($lb_valido) // Si no ocurrio ningún error
		   {
			 $io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			 $io_pdf->ezStream(); // Mostramos el reporte
	  	   }
		else  // Si hubo algún error
		   {
			 print("<script language=JavaScript>");
			 print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			 print(" close();");
			 print("</script>");		
		   }
		unset($io_pdf);			
	}//1
	else
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	 }
?> 
