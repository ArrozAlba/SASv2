<?Php
session_start();

require_once("../../../sps/class_folder/dao/sps_pro_sueldos_dao.php");
require_once("../../../sps/reports/documents/sps_reporte_base.php");
require_once("../../../sps/class_folder/utilidades/class_function.php");

$lo_sueldos_dao = new sps_pro_sueldos_dao();
$lo_function       = new class_function();

$lo_reporte_base = new sps_reporte_base("Detalles de Sueldos Históricos",'LETTER','portrait');
$lo_pdf = $lo_reporte_base->getPdf();

//Obtenemos el orden de los campos
$la_orden = explode(",",$_GET["orden"]);

//-----------------------------------------------------------------------------------------------------------------------------------
function uf_print_cabecera($as_codper,$as_nomper,$as_apeper,&$io_cabecera,&$io_pdf)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_cabecera
	//		   Access: private 
	//	    Arguments: $as_codper  // Código del Personal
	//	   			   $as_nomper  // Nombre del Personal 
	//	   			   $as_apeper  // apellido del Personal
	//	    		   io_cabecera // objeto cabecera
	//	    		   io_pdf      // Instancia de objeto pdf
	//    Description: función que imprime la cabecera por personal
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$io_pdf->add_texto('left',10,11,'<b>         Personal: </b>  '.$as_codper.' - '.$as_apeper.', '.$as_nomper.' ');
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
	$la_columnas=array('fecha'=>'<b>Fecha</b>',
					   'sueldo_base'=>'<b>   Sueldo Base   </b>',
					   'sueldo_integral'=>'<b>   Sueldo Integral   </b>',
					   'sueldo_diario'=>'<b>   Sueldo Diario   </b>');
	$la_config=array('showHeadings'=>1, // Mostrar encabezados
					 'fontSize' => 9,   // Tamaño de Letras
					 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
					 'showLines'=>1,    // Mostrar Líneas
					 'shaded'=>0,       // Sombra entre líneas
					 'width'=>500,      // Ancho de la tabla
					 'maxWidth'=>500,   // Ancho Máximo de la tabla
					 'xOrientation'=>'center', // Orientación de la tabla
			         'outerLineThickness'=>0.5,
					 'innerLineThickness' =>0.5,
					 'cols'=>array('fecha'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
					 			   'sueldo_base'=>array('justification'=>'right','width'=>130), // Justificación y ancho de la columna
					 			   'sueldo_integral'=>array('justification'=>'right','width'=>140), // Justificación y ancho de la columna
					 			   'sueldo_diario'=>array('justification'=>'right','width'=>130))); // Justificación y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	$io_pdf->set_margenes(30,15,25,15);
}// end function uf_print_detalle
//----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	// Datos de la cabecera del reporte
	$lb_valido=$lo_sueldos_dao->getCabeceraReporteSueldos("ORDER BY ".$_GET["orden"],$_GET["codper1"],$_GET["codper2"],$la_array);
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
		$li_totrow=count($la_array["codper"]);
		for($li_i=0;$li_i<$li_totrow;$li_i++)
		{
			$lo_cabecera= $lo_pdf->openObject();   // Creamos el objeto cabecera
		    uf_print_cabecera($la_array["codper"][$li_i],$la_array["nomper"][$li_i],$la_array["apeper"][$li_i],$lo_cabecera,$lo_pdf); // Imprimimos la cabecera del registro
			/**/
			// Obtenemos el detalle del reporte
			$lb_hay = $lo_sueldos_dao->getDetalleSueldos("ORDER BY fecincsue",$la_array['codper'][$li_i],$la_detalle);													   
			if($lb_hay)
			{
				$li_totrow_det=count($la_detalle["fecincsue"]);
				for($li_d=0;$li_d<$li_totrow_det;$li_d++)
				{
				    $ls_fecincsue   = $lo_function->uf_dtoc($la_detalle["fecincsue"][$li_d]);
					$ld_monsuebas   = $lo_function->uf_ntoc($la_detalle["monsuebas"][$li_d], 2);
					$ld_monsueint   = $lo_function->uf_ntoc($la_detalle["monsueint"][$li_d], 2);
					$ld_monsuenordia= $lo_function->uf_ntoc($la_detalle["monsuenordia"][$li_d], 2);
					$la_data[$li_d]=array('fecha'=>$ls_fecincsue,'sueldo_base'=>$ld_monsuebas,'sueldo_integral'=>$ld_monsueint,'sueldo_diario'=>$ld_monsuenordia);
				}
				uf_print_detalle($la_data,$lo_pdf); // Imprimimos el detalle 
				$lo_pdf->stopObject($lo_cabecera); // Detener el objeto cabecera
				if($li_i<$li_totrow-1)
				{
					$lo_pdf->ezNewPage(); // Insertar una nueva página
				}
				unset($lo_cabecera);
				unset($la_data);
			}
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