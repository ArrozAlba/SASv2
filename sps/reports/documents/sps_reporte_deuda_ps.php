<?Php
session_start();

require_once("../../../sps/class_folder/dao/sps_pro_antiguedad_dao.php");
require_once("../../../sps/reports/documents/sps_reporte_base.php");
require_once("../../../sps/class_folder/utilidades/class_function.php");

$lo_antig_dao = new sps_pro_antiguedad_dao();
$lo_function  = new class_function();

$lo_reporte_base = new sps_reporte_base("Deuda de Prestaciones Sociales",'LETTER','portrait');
$lo_pdf = $lo_reporte_base->getPdf();

//Obtenemos el orden de los campos
$la_orden = explode(",",$_GET["orden"]);

//-----------------------------------------------------------------------------------------------------------------------------------
function uf_print_cabecera($as_cedper,$as_nomper,$as_apeper,$as_desnom,$i,&$io_cabecera,&$io_pdf)
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
	$io_pdf->add_texto('left',10,11,'<b>         Personal: </b>  '.$as_cedper.' - '.$as_apeper.', '.$as_nomper.'      <b>   Nómina: </b>'.$as_desnom );
	$io_pdf->closeObject();
	$io_pdf->addObject($io_cabecera,'all');
	$io_pdf->set_margenes(70+$i,15,25,15);	
	
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
	$la_columnas=array('cedper'=>'<b>Cédula</b>',
	                   'nomper'=>'<b>Nombre y Apellido</b>', 
	                   'fecha_desde'=>'<b>Fecha Desde</b>',
	                   'fecha_hasta'=>'<b>Fecha Hasta</b>',
					   'diaant'=>'<b> Días </b>',
					   'antiguedad'=>'<b> Deuda Antiguedad </b>',
					   'interes'=>'<b>  Interés  </b>',
					   'total'=>'<b> Saldo Total </b>',
					   'desnom'=>'<b> Tipo </b>');
	$la_config=array('showHeadings'=>1, // Mostrar encabezados
					 'fontSize' => 8,   // Tamaño de Letras
					 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
					 'showLines'=>1,    // Mostrar Líneas
					 'shaded'=>0,       // Sombra entre líneas
					 'width'=>530,      // Ancho de la tabla
					 'maxWidth'=>530,   // Ancho Máximo de la tabla
					 'xOrientation'=>'center', // Orientación de la tabla
			         'outerLineThickness'=>0.5,
					 'innerLineThickness' =>0.5,
					 'cols'=>array('cedper'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
					               'nomper'=>array('justification'=>'left','width'=>95), // Justificación y ancho de la columna
					               'fecha_desde'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
					               'fecha_hasta'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
								   'diaant'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
					 			   'antiguedad'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
					 			   'interes'=>array('justification'=>'right','width'=>50), // Justificación y ancho de la columna
					 			   'total'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
								   'desnom'=>array('justification'=>'center','width'=>75))); // Justificación y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	$io_pdf->set_margenes(60,15,25,15);
}// end function uf_print_detalle
//----------------------------------------------------------------------------------------------------------------------------------------------------------------//
function uf_print_total($ld_total,&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function : uf_print_total
	//		    Acess : private
	//	    Arguments : $ld_total
	//    Description : función que imprime el total de asignaciones
	//	   Creado Por : Ing. Maria Alejandra Roa
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
	$la_data=array(array('total'=>'<b>TOTAL DEUDA ANTIGUEDAD:  </b>','totaldeuda'=>'<b>'.$ld_total.'</b>'));
	$la_columna=array('total'=>'','totaldeuda'=>'');
	$la_config=array('showHeadings'=>0,      // Mostrar encabezados
					 'fontSize' => 9,        // Tamaño de Letras
					 'showLines'=>0,         // Mostrar Líneas
					 'shaded'=>0,            // Sombra entre líneas
					 'width'=>530,           // Ancho Máximo de la tabla
					 'rowGap'=>2,
					 'colGap'=>0,
					 'xOrientation'=>'center', // Orientación de la tabla
			 		 'cols'=>array('total'=>array('justification'=>'right','width'=>395), // Justificación y ancho de la columna
					 			   'totaldeuda'=>array('justification'=>'left','width'=>135))); // Justificación y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);

}// end function uf_print_total
//----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	// Datos de la cabecera del reporte
	$lb_valido=$lo_antig_dao->getCabeceraReporteDeuda("ORDER BY ".$_GET["orden"],$_GET["codper1"],$_GET["codper2"],$_GET["codnom"],$la_array);
	if ($lb_valido==false) // Existe algún error ó no hay registros
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
		$ld_totaldeuda = 0;
		$li_totrow=count($la_array["cedper"]);
		for($li_i=0;$li_i<$li_totrow;$li_i++)
		{
			// Obtenemos el detalle del reporte
			$lb_hay = $lo_antig_dao->getDetalleDeuda("ORDER BY fecant ",$la_array['codper'][$li_i],$la_detalle);									   
			if($lb_hay)
			{
			    $li_diaant =0;
				$ld_monant =0;
				$ld_monantant =0;
				$ld_monint = 0;
				$li_totrow_det=count($la_detalle["fecant"]);
				
				for($li_d=0;$li_d<$li_totrow_det;$li_d++) 
				{
                    if ($li_d==0) $ld_fecdesde=$lo_function->uf_dtoc($la_detalle["fecant"][$li_d]);				
					$li_diaant = ($li_diaant + $la_detalle["diaacu"][$li_d]);
				    $ld_monant = ($ld_monant +  $la_detalle["monant"][$li_d]);
					$ld_monantant = ($ld_monantant + $la_detalle["monantant"][$li_d]);
					$ld_monint = ($ld_monint + $la_detalle["monint"][$li_d]);
					
					if ($li_d==$li_totrow_det-1) $ld_fechasta=$lo_function->uf_dtoc($la_detalle["fecant"][$li_d]);
				    					
				}
				$ls_cedper = $la_array["cedper"][$li_i];
				$ls_nomper = $la_array["nomper"][$li_i]." ".$la_array["apeper"][$li_i];
				$ls_desnom = $la_array["desnom"][$li_i];
				$ld_parcial= ($ld_monant-$ld_monantant);
				$ld_total  = ($ld_parcial+$ld_monint);
				$ld_monant = $lo_function->uf_ntoc($ld_parcial, 2);
				$ld_monint = $lo_function->uf_ntoc($ld_monint, 2);
				$ld_totales= $lo_function->uf_ntoc($ld_total, 2);
				$la_data[0]=array('cedper'=>$ls_cedper,'nomper'=>$ls_nomper,'fecha_desde'=>$ld_fecdesde,'fecha_hasta'=>$ld_fechasta,'diaant'=>$li_diaant,'antiguedad'=>$ld_monant,'interes'=>$ld_monint,'total'=>$ld_totales,'desnom'=>$ls_desnom);
		
				uf_print_detalle($la_data,$lo_pdf); // Imprimimos el detalle 

				$ld_totaldeuda = ($ld_totaldeuda + $ld_total);
				unset($lo_cabecera);
				unset($la_data);
			}
		}
		$ld_totaldeuda = $lo_function->uf_ntoc($ld_totaldeuda, 2);
		uf_print_total($ld_totaldeuda,$lo_pdf);
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
	unset($lo_antig_dao);
?>