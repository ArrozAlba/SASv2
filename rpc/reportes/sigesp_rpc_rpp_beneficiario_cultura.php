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
		$io_pdf->rectangle(185,710,370,40);
		$io_pdf->line(400,750,400,710);
		$io_pdf->line(400,730,555,730);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,715,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$io_pdf->addText(225,725,11,$as_titulo); // Agregar el título
		$io_pdf->addText(430,735,10,"Fecha: ".date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(430,715,10,"Hora: ".date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------


//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_cabecera_detalle(&$io_pdf)
{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Function: uf_print_cabecera_detalle
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
	$la_data   =array(array('cedula'=>'<b>Cédula</b>','nombre'=>'<b>Nombre</b>','apellido'=>'<b>Apellido</b>','cuenta'=>'<b>Cuenta</b>'));
	$la_columna=array('cedula'=>'','nombre'=>'','apellido'=>'','cuenta'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
					 'titleFontSize' =>10,  // Tamaño de Letras de los títulos
					 'showLines'=>1, // Mostrar Líneas
					 'shaded'=>0,
					 'shadeCol2'=>array(0.86,0.86,0.86),
					 'colGap'=>1,
					 'width'=>520, // Ancho de la tabla
					 'maxWidth'=>520, // Ancho Máximo de la tabla
					 'xPos'=>296, // Orientación de la tabla
					 'cols'=>array('cedula'=>array('justification'=>'center','width'=>70),
								   'nombre'=>array('justification'=>'center','width'=>180), // Justificación y ancho de la columna
								   'apellido'=>array('justification'=>'center','width'=>180), // Justificación y ancho de la columna
								   'cuenta'=>array('justification'=>'center','width'=>90))); // Justificación y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');
}// end function uf_print_cabecera_detalle
//------------------------------------------------------------------------------------------------------------------------------

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
		$la_columna=array('cedula'=>'','nombre'=>'','apellido'=>'','cuenta'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Máximo de la tabla
						 'xPos'=>300, // Orientación de la tabla
						 'cols'=>array('cedula'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>180), // Justificación y ancho de la columna
						 			   'apellido'=>array('justification'=>'left','width'=>180), // Justificación y ancho de la columna
						 			   'cuenta'=>array('justification'=>'center','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	$io_in=new sigesp_include();
	$con=$io_in->uf_conectar();	
	require_once("sigesp_rpc_class_report.php");
	$io_report = new sigesp_rpc_class_report($con);
	require_once("../../shared/class_folder/class_sql.php");
	$io_sql = new class_sql($con);
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>Listado de Beneficiarios</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	if (array_key_exists("hidorden",$_POST))
	   {
		 $li_orden=$_POST["hidorden"];
	   }
	else
	   {
		 $li_orden=$_GET["hidorden"];
	   }
	if (array_key_exists("hidcedula1",$_POST))
	   {
		 $ls_cedula1=$_POST["hidcedula1"];
	   }
	else
	   {
		 $ls_cedula1=$_GET["hidcedula1"];
	   }
	if (array_key_exists("hidcedula2",$_POST))
	   {
		 $ls_cedula2=$_POST["hidcedula2"];
	   }
	else
	   {
		 $ls_cedula2=$_GET["hidcedula2"];
	   }
	$lb_valido=true;
	$rs_beneficiario=$io_report->uf_select_beneficiario($li_orden,$ls_cedula1,$ls_cedula2,$lb_valido);
	if ($lb_valido)
	   {
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.8,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$li_total=$io_sql->num_rows($rs_beneficiario);
		$data=$io_sql->obtener_datos($rs_beneficiario);
		for ($z=1;$z<=$li_total;$z++)
			{//1
			  $ls_cedbene =$data["ced_bene"][$z];
			  $ls_nombene =$data["nombene"][$z];
			  $ls_apebene =$data["apebene"][$z];
			  $ls_cuenta  =$data["sc_cuenta"][$z];
  			  $la_data[$z]=array('cedula'=>$ls_cedbene,'nombre'=>$ls_nombene,'apellido'=>$ls_apebene,'cuenta'=>$ls_cuenta);
			}//4
		uf_print_cabecera_detalle($io_pdf);
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		if($lb_valido) // Si no ocurrio ningún error
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
