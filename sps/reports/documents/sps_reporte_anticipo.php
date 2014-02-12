<?Php
session_start();

require_once("../../../sps/class_folder/dao/sps_pro_anticipo_dao.php");
require_once("../../../sps/reports/documents/sps_reporte_base.php");
require_once("../../../sps/class_folder/utilidades/class_function.php");

$lo_anticipo_dao = new sps_pro_anticipo_dao();
$lo_function     = new class_function();

$lo_reporte_base = new sps_reporte_base("Anticipo de Prestaciones Sociales",'LETTER','portrait');
$lo_pdf = $lo_reporte_base->getPdf();

//---------------------------------------------------------------------------------------------------------------------------------------------------------------//
function uf_print_anticipo($as_constancia,&$io_pdf)
{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Function: uf_print_constancia
//		   Access: private 
//	    Arguments: la_data // arreglo de información
//	   			   io_pdf // Objeto PDF
//    Description: función que imprime el detalle
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
					    'width'=>300, 
					    'maxWidth'=>300, 
					    'xPos'=>296,
					    'cols'=>array('constancia'=>array('justification'=>'left','width'=>300)));
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');
}// end function uf_print_cabecera_detalle
//----------------------------------------------------------------------------------------------------------------------------------------------------------------//

	$lb_valido=$lo_anticipo_dao->getReporteAnticipo($_GET["codper"],$la_array);   
	if ($lb_valido)
	   {
		  error_reporting(E_ALL);
		  set_time_limit(1800);
//		  $lo_pdf = new Cezpdf('LETTER','portrait');                         // Instancia de la clase PDF
		  //$io_pdf->selectFont('../../../shared/ezpdf/fonts/Helvetica.afm');     // Seleccionamos el tipo de letra
//		  $io_pdf->ezSetCmMargins(4.5,3,3,3);                                // Configuración de los margenes en centímetros
//		  $io_pdf->ezStartPageNumbers(550,50,10,'','',1);                    // Insertar el número de página
		  $li_totrow=count($la_array["nomper"]);

			   $ld_monant = $lo_function->uf_ntoc($la_array["monant"][0], 2);
			   $ls_cedper = $la_array["cedper"][0];
			   $ls_nomper = $la_array["nomper"][0];
			   $ls_apeper = $la_array["apeper"][0];
			   $ls_fecingper= $lo_function->uf_dtoc($la_array["fecingper"][0]);
			   $ls_descar = $la_array["descar"][0];
			   $ls_codnom = $la_array["codnom"][0];
			   $ls_desnom = $la_array["desnom"][0];
			   $ls_desuniadm = $la_array["desuniadm"][0];
			  // $lo_pdf->add_texto('justification',30,11,'        Por solicitud de la parte interesada, se hace entrega del Anticipo de Prestaciones Sociales ');
//			   $lo_pdf->add_texto('justification',40,11,'por el monto de Bs.   <b>'.$ld_monant.'</b>         al <b>Sr.(a)      </b> <b>'.$ls_apeper.'</b>, <b>'.$ls_nomper.'</b>');
//			   $lo_pdf->add_texto('justification',50,11,'portador de la C.I. Nº  <b>'.$ls_cedper.'</b>  quien ocupa el cargo de   <b>'.$ls_descar.'</b>    en esta Institución, ');
//			   $lo_pdf->add_texto('justification',60,11,'desde la fecha  <b>'.$ls_fecingper.'</b>  en el Departamento de  <b>'.$ls_desuniadm.'</b>');
//			   $lo_pdf->add_texto('justification',70,11,'como   <b>'.$ls_desnom.'</b> ');
//			   $lo_pdf->add_texto('justification',110,11,'<b>Recibi conforme: </b> ');
//			   $lo_pdf->add_texto('justification',120,11,'<b>C.I.: </b> ');
			   

		if ($lb_valido) // Si no ocurrio ningún error
		   {
			 $lo_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			 $lo_pdf->ezStream();             // Mostramos el reporte
	  	   }
		else  // Si hubo algún error
		   {
			 print("<script language=JavaScript>");
			 print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			 print(" close();");
			 print("</script>");		
		   }
		unset($lo_pdf);			
		unset($lo_anticipo_dao);
	}
	else
	 {
		$la_opciones["anchos_col"]   = array(250);
	    $la_opciones["alineacion_col"]= array("center");  
        $la_datos = array("<i>No existen Datos del Trabajador que cumplan con los Parámetros.</i>");
        $lo_pdf->add_tabla('center',$la_datos,$la_opciones);
	 }
?>