<?Php
session_start();

require_once("../../../sps/class_folder/dao/sps_pro_antiguedad_dao.php");
require_once("../../../sps/class_folder/utilidades/class_function.php");
require_once("../../../shared/class_folder/class_pdf.php");

$lo_antiguedad_dao = new sps_pro_antiguedad_dao();
$lo_function       = new class_function();

//Obtenemos el orden de los campos
$la_orden = explode(",",$_GET["orden"]);

//-----------------------------------------------------------------------------------------------------------------------------------
function uf_print_cabecera($as_cedper,$as_nomper,$as_apeper,&$io_cabecera,&$io_pdf)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_cabecera
	//		   Access: private 
	//	    Arguments: $as_cedper  // Cédula del Personal
	//	   			   $as_nomper  // Nombre del Personal 
	//	   			   $as_apeper  // apellido del Personal
	//	    		   io_cabecera // objeto cabecera
	//	    		   io_pdf      // Instancia de objeto pdf
	//    Description: función que imprime la cabecera por personal
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$io_pdf->add_texto('left',10,11,'<b>         Personal: </b>  '.$as_cedper.' - '.$as_apeper.', '.$as_nomper.' ');
	$io_pdf->closeObject();
	$io_pdf->addObject($io_cabecera,'all');
	$io_pdf->set_margenes(50,15,25,15);	
	
}// end function uf_print_cabecera
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_print_detalle($la_data,&$io_pdf)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_detalle
	//		   Access: private 
	//	    Arguments: la_data // arreglo de información
	//	    		   io_pdf // Instancia de objeto pdf
	//    Description: función que imprime el detalle por concepto
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$io_pdf->ezSetDy(-2);    
	$la_columnas=array('fecant'=>'<b>Fecha</b>',
					   'salbas'=>'<b>Sueldo Base  </b>',
					   'incbonvac'=>'<b>Inc. Vacacional</b>',
					   'incbonnav'=>'<b>Inc. Fin de Año</b>',
					   'salintdia'=>'<b>S. Integral</b>',
					   'diabas'=>'<b>Día Antig.</b>',
					   'diacom'=>'<b>Día Comp.</b>',
					   'monant'=>'<b>Antigüedad   </b>',
					   'monacuant'=>'<b>Antigüedad Acum.</b>',
					   'monantant'=>'<b>Anticipos</b>',
					   'salparant'=>'<b>Saldo Parcial   </b>',
					   'porint'=>'<b>% Interés</b>',
					   'diaint'=>'<b>Días Interés</b>',
					   'monint'=>'<b>Monto Interés   </b>',
					   'monacuint'=>'<b> Interés Acum.  </b>',
					   'saltotant'=>'<b>Saldo Total</b>');
	$la_config=array('showHeadings'=>1, // Mostrar encabezados
					 'fontSize' => 7,   // Tamaño de Letras
					 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
					 'showLines'=>1,    // Mostrar Líneas
					 'shaded'=>0,       // Sombra entre líneas
					 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
					 'width'=>700,      // Ancho de la tabla
					 'maxWidth'=>700,   // Ancho Máximo de la tabla
					 'xOrientation'=>'center', // Orientación de la tabla
			         'outerLineThickness'=>0.5,
					 'innerLineThickness' =>0.5,
					 'cols'=>array('fecant'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
					 			   'salbas'=>array('justification'=>'right','width'=>50), // Justificación y ancho de la columna
					 			   'incbonvac'=>array('justification'=>'right','width'=>45), // Justificación y ancho de la columna
								   'incbonnav'=>array('justification'=>'right','width'=>45), // Justificación y ancho de la columna
								   'salintdia'=>array('justification'=>'right','width'=>50), // Justificación y ancho de la columna
								   'diabas'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
								   'diacom'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
								   'monant'=>array('justification'=>'right','width'=>50), // Justificación y ancho de la columna
								   'monacuant'=>array('justification'=>'right','width'=>55), // Justificación y ancho de la columna
								   'monantant'=>array('justification'=>'right','width'=>45), // Justificación y ancho de la columna
								   'salparant'=>array('justification'=>'right','width'=>55), // Justificación y ancho de la columna
								   'porint'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
								   'diaint'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
								   'monint'=>array('justification'=>'right','width'=>50), // Justificación y ancho de la columna
								   'monacuint'=>array('justification'=>'right','width'=>50), // Justificación y ancho de la columna
					 			   'saltotant'=>array('justification'=>'right','width'=>55))); // Justificación y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	$io_pdf->set_margenes(30,15,25,15);
}// end function uf_print_detalle
//----------------------------------------------------------------------------------------------------------------------------------------------------------------//
  
	$lo_pdf  = new class_pdf('LETTER','landscape');
	
    //Configuramos la pagina
	$lo_pdf->selectFont('../../../shared/class_folder/ezpdf/fonts/Helvetica.afm');
	$lo_pdf->set_margenes(15,15,25,15);
	$lo_pdf->numerar_paginas(10);
	//Colocamos el titulo a la pagina y redimensionamos los margenes
	$ls_titulo = "Detalles de Antigüedad";
    $lo_titulo = $lo_pdf->openObject();
	$lo_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
    $lo_pdf->add_linea(0,10,$lo_pdf->get_ancho_area_trabajo(),10,2);
    $lo_pdf->add_texto('center',0,16,"<b><i>".$ls_titulo."</i></b>");
    $lo_pdf->add_texto('right',0,10,"<b><i>Fecha: ".date("d/m/Y")."</i></b>");
    $lo_pdf->add_texto('right',5,10,"<b><i>Hora: ".date("h:i a   ")."</i></b>");
    $lo_pdf->closeObject();
    $lo_pdf->addObject($lo_titulo,'all');
    $lo_pdf->set_margenes(30,15,25,15);
	
	// Datos de la cabecera del reporte
	$lb_valido=$lo_antiguedad_dao->getCabeceraReporteAntiguedad("ORDER BY ".$_GET["orden"],$_GET["codper"],$la_array);
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		$la_opciones["anchos_col"]   = array(250);
	    $la_opciones["alineacion_col"]= array("center");  
        $la_datos = array("<i>No existen Registros que cumplan con los Parámetros.</i>");
        $lo_pdf->add_tabla('center',$la_datos,$la_opciones);
	}
	else  // Imprimimos el reporte
	{
		error_reporting(E_ALL);
		set_time_limit(1800);
		$lo_cabecera= $lo_pdf->openObject();   // Creamos el objeto cabecera
		uf_print_cabecera($la_array["cedper"][0],$la_array["nomper"][0],$la_array["apeper"][0],$lo_cabecera,$lo_pdf); // Imprimimos la cabecera del registro
		
			// Obtenemos el detalle del reporte
			$lb_hay = $lo_antiguedad_dao->getDetalleAntiguedad("ORDER BY ".$_GET["orden"],$_GET["codper"],$_GET["fechainicio"],$_GET["fechafin"],$la_detalle);
			if($lb_hay)
			{
				$li_totrow_det=count($la_detalle["fecant"]);
				for($li_d=0;$li_d<$li_totrow_det;$li_d++)
				{    
				    $ls_fecant    = $lo_function->uf_dtoc($la_detalle["fecant"][$li_d]);
					$ld_salbas    = $lo_function->uf_ntoc($la_detalle["salbas"][$li_d], 2);
					$ld_incbonvac = $lo_function->uf_ntoc($la_detalle["incbonvac"][$li_d], 2);
					$ld_incbonnav = $lo_function->uf_ntoc($la_detalle["incbonnav"][$li_d], 2);
					$ld_salintdia = $lo_function->uf_ntoc($la_detalle["salintdia"][$li_d], 2);
					$li_diabas    = $la_detalle["diabas"][$li_d];
					$li_diacom    = $la_detalle["diacom"][$li_d];
					$ld_monant    = $lo_function->uf_ntoc($la_detalle["monant"][$li_d], 2);
					$ld_monacuant = $lo_function->uf_ntoc($la_detalle["monacuant"][$li_d], 2);
					$ld_monantant = $lo_function->uf_ntoc($la_detalle["monantant"][$li_d], 2);
					$ld_salparant = $lo_function->uf_ntoc($la_detalle["salparant"][$li_d], 2);
					$ld_porint    = $lo_function->uf_ntoc($la_detalle["porint"][$li_d], 2);
					$li_diaint    = $la_detalle["diaint"][$li_d];
					$ld_monint    = $lo_function->uf_ntoc($la_detalle["monint"][$li_d], 2);
					$ld_monacuint = $lo_function->uf_ntoc($la_detalle["monacuint"][$li_d], 2);
					$ld_saltotant = $lo_function->uf_ntoc($la_detalle["saltotant"][$li_d], 2);
					$la_data[$li_d]=array('fecant'=>$ls_fecant,'salbas'=>$ld_salbas,'incbonvac'=>$ld_incbonvac,'incbonnav'=>$ld_incbonnav,'salintdia'=>$ld_salintdia,'diabas'=>$li_diabas,'diacom'=>$li_diacom,'monant'=>$ld_monant,'monacuant'=>$ld_monacuant,'monantant'=>$ld_monantant,'salparant'=>$ld_salparant,'porint'=>$ld_porint,'diaint'=>$li_diaint,'monint'=>$ld_monint,'monacuint'=>$ld_monacuint,'saltotant'=>$ld_saltotant);
				}
				uf_print_detalle($la_data,$lo_pdf); // Imprimimos el detalle 
				$lo_pdf->stopObject($lo_cabecera); // Detener el objeto cabecera
				/*if($li_d<$li_totrow_det-1)
				{
					$lo_pdf->ezNewPage(); // Insertar una nueva página
				}*/
				unset($lo_cabecera);
				unset($la_data);
			}
		
		
		if($lb_valido) // Si no ocurrio ningún error
		{
			$lo_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$lo_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		unset($lo_pdf);
	}
	unset($lo_function);
	unset($lo_sueldos_dao);
?>