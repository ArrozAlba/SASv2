<?Php
session_start();

require_once("../../../sps/class_folder/dao/sps_pro_liquidacion_dao.php");
require_once("../../../sps/class_folder/utilidades/class_function.php");
require_once("../../../shared/ezpdf/class.ezpdf.php");

$lo_liq_dao  = new sps_pro_liquidacion_dao();
$lo_function = new class_function();

function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_encabezadopagina
	//		    Acess: private
	//	    Arguments: as_titulo // Título del Reporte
	//	    		   io_pdf // Instancia de objeto pdf
	//    Description: función que imprime los encabezados por página
	//	   Creado Por: Ing. Maria Alejandra Roa
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$io_encabezado=$io_pdf->openObject();
	$io_pdf->saveState();
	$io_pdf->line(20,40,578,40);
	$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo

	$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
	$tm=330-($li_tm/2);
	$io_pdf->addText($tm,705,11,$as_titulo); // Agregar el título
	
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');
}// end function uf_print_encabezadopagina  

//-----------------------------------------------------------------------------------------------------------------------------------//
function uf_print_cabecera_1($la_data,&$io_pdf)
{
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 //       Function: uf_print_cabecera_1
 //		   Access: private
 //	    Arguments: io_pdf // Objeto PDF
 //    Description: función que imprime la cabecera de la página
 //	   Creado Por: Ing. Maria Alejandra Roa
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
         $la_config=array('showHeadings'=>1,      // Mostrar encabezados
						 'fontSize' => 6,         // Tamaño de Letras
						 'titleFontSize' => 9,    // Tamaño de Letras de los títulos
						 'showLines'=>1,          // Mostrar Líneas
						 'shaded'=>0,             // Sombra entre líneas
						 'width'=>150,            // Ancho de la tabla
						 'maxWidth'=>500,         // Ancho Máximo de la tabla
						 'xOrientation'=>'left', // Orientación de la tabla
						 'rowGap'=>2,
						 'colGap'=>0,				  
				  		 'cols'=>array('numliq'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						               'fecha'=>array('justification'=>'center','width'=>60))); // Justificación y ancho de la columna
            $la_columna= array('numliq'=>'<b>Liquidación Nº </b>',
			         	       'fecliq'=>'<b>      Fecha </b>');
              $io_pdf->ezTable($la_data,$la_columna,'',$la_config);

}// end function uf_print_cabecera_1
//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_cabecera_2($la_data,&$io_pdf)
{
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 //       Function: uf_print_cabecera_2
 //		   Access: private
 //	    Arguments: io_pdf // Objeto PDF
 //    Description: función que imprime la cabecera de la página
 //	   Creado Por: Ing. Maria Alejandra Roa
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
         $la_config=array('showHeadings'=>1,      // Mostrar encabezados
						 'fontSize' => 6,         // Tamaño de Letras
						 'titleFontSize' => 9,    // Tamaño de Letras de los títulos
						 'showLines'=>1,          // Mostrar Líneas
						 'shaded'=>0,             // Sombra entre líneas
						 'width'=>500,            // Ancho de la tabla
						 'maxWidth'=>500,         // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'rowGap'=>2,
						 'colGap'=>0,				  
				  		 'cols'=>array('nomper'=>array('justification'=>'left','width'=>400), // Justificación y ancho de la columna
						               'cedper'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
            $la_columna= array('nomper'=>'<b>  Apellidos y Nombres </b>',
			         	       'cedper'=>'<b> Cédula </b>');
              $io_pdf->ezTable($la_data,$la_columna,'',$la_config);

}// end function uf_print_cabecera_2
//-----------------------------------------------------------------------------------------------------------------------------------//
function uf_print_cabecera_3($la_data,&$io_pdf)
{
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 //       Function: uf_print_cabecera_3
 //		   Access: private
 //	    Arguments: io_pdf // Objeto PDF
 //    Description: función que imprime la cabecera de la página
 //	   Creado Por: Ing. Maria Alejandra Roa
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
         $la_config=array('showHeadings'=>1,      // Mostrar encabezados
						 'fontSize' => 6,         // Tamaño de Letras
						 'titleFontSize' => 9,    // Tamaño de Letras de los títulos
						 'showLines'=>1,          // Mostrar Líneas
						 'shaded'=>0,             // Sombra entre líneas
						 'width'=>500,            // Ancho de la tabla
						 'maxWidth'=>500,         // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'rowGap'=>2,
						 'colGap'=>0,				  
				  		 'cols'=>array('descargo'=>array('justification'=>'left','width'=>250), // Justificación y ancho de la columna
						               'desuniadm'=>array('justification'=>'left','width'=>250))); // Justificación y ancho de la columna
            $la_columna= array('descargo'=>'<b>  Cargo </b>',
			         	       'desuniadm'=>'<b>  Dependencia </b>');
              $io_pdf->ezTable($la_data,$la_columna,'',$la_config);

}// end function uf_print_cabecera_3
//-----------------------------------------------------------------------------------------------------------------------------------//
function uf_print_cabecera_4($la_data,&$io_pdf)
{
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 //       Function: uf_print_cabecera_4
 //		   Access: private
 //	    Arguments: io_pdf // Objeto PDF
 //    Description: función que imprime la cabecera de la página
 //	   Creado Por: Ing. Maria Alejandra Roa
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
         $la_config=array('showHeadings'=>1,      // Mostrar encabezados
						 'fontSize' => 6,         // Tamaño de Letras
						 'titleFontSize' => 9,    // Tamaño de Letras de los títulos
						 'showLines'=>1,          // Mostrar Líneas
						 'shaded'=>0,             // Sombra entre líneas
						 'width'=>500,            // Ancho de la tabla
						 'maxWidth'=>500,         // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'rowGap'=>2,
						 'colGap'=>0,				  
				  		 'cols'=>array('fecing'=>array('justification'=>'center','width'=>125),  // Justificación y ancho de la columna
						               'fecegr'=>array('justification'=>'center','width'=>125),  // Justificación y ancho de la columna
									   'anoser'=>array('justification'=>'center','width'=>84),   // Justificación y ancho de la columna
									   'messer'=>array('justification'=>'center','width'=>83),   // Justificación y ancho de la columna
						               'diaser'=>array('justification'=>'center','width'=>83))); // Justificación y ancho de la columna
            $la_columna= array('fecing'=>'<b> Fecha Ingreso </b>',
			         	       'fecegr'=>'<b> Fecha Egreso </b>',
							   'anoser'=>'<b> Años </b>',
							   'messer'=>'<b> Meses </b>',
							   'diaser'=>'<b> Días </b>');
              $io_pdf->ezTable($la_data,$la_columna,'',$la_config);

}// end function uf_print_cabecera_4
//-----------------------------------------------------------------------------------------------------------------------------------//
function uf_print_cabecera_5($la_data,&$io_pdf)
{
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 //       Function: uf_print_cabecera_5
 //		   Access: private
 //	    Arguments: io_pdf // Objeto PDF
 //    Description: función que imprime la cabecera de la página
 //	   Creado Por: Ing. Maria Alejandra Roa
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
         $la_config=array('showHeadings'=>1,      // Mostrar encabezados
						 'fontSize' => 6,         // Tamaño de Letras
						 'titleFontSize' => 9,    // Tamaño de Letras de los títulos
						 'showLines'=>1,          // Mostrar Líneas
						 'shaded'=>0,             // Sombra entre líneas
						 'width'=>500,            // Ancho de la tabla
						 'maxWidth'=>500,         // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'rowGap'=>2,
						 'colGap'=>0,				  
				  		 'cols'=>array('dencauret'=>array('justification'=>'center','width'=>125),   // Justificación y ancho de la columna
						               'sueproper'=>array('justification'=>'center','width'=>125),   // Justificación y ancho de la columna
									   'salint'=>array('justification'=>'center','width'=>125),     // Justificación y ancho de la columna
						               'suediaper'=>array('justification'=>'center','width'=>125))); // Justificación y ancho de la columna
            $la_columna= array('dencauret'=>'<b> Motivo de Pago </b>',
							   'sueproper'=>'<b> Sueldo Promedio </b>',
			         	       'salint'=>'<b> Sueldo Integral </b>',
							   'suediaper'=>'<b> Sueldo Diario </b>');
              $io_pdf->ezTable($la_data,$la_columna,'',$la_config);

}// end function uf_print_cabecera_5
//-----------------------------------------------------------------------------------------------------------------------------------//
function uf_print_cabecera_6(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_6
		//		   Access: private
		//	    Arguments: io_pdf // Objeto PDF
		//    Description: función que imprime parte de la cabecera
		//	   Creado Por: Ing. Maria Alejandra Roa
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'                                                                                                            <b>ESPECIFICACIONES</b> '));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1,    // Mostrar Líneas
						 'fontSize' => 7,   // Tamaño de Letras
						 'shaded'=>2,       // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500,     // Ancho de la tabla
						 'rowGap'=>2,
						 'colGap'=>0,  
						 'maxWidth'=>500); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->ezSetDy(-1);
	}// end function uf_print_cabecera_6
//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private
		//	    Arguments: la_data         // arreglo de información
		//	   			   io_pdf          // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Maria Alejandra Roa
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$la_config=array('showHeadings'=>1,             // Mostrar encabezados
						 'fontSize' => 7,               // Tamaño de Letras
						 'titleFontSize' => 10,         // Tamaño de Letras de los títulos
						 'showLines'=>1,                // Mostrar Líneas
						 'shaded'=>0,                   // Sombra entre líneas
						 'width'=>500,                  // Ancho de la tabla
						 'maxWidth'=>500,               // Ancho Máximo de la tabla
						 'xOrientation'=>'center',      // Orientación de la tabla
						 'rowGap'=>2,
						 'colGap'=>0,
						 'cols'=>array('numespliq'=>array('justification'=>'center','width'=>50),        // Justificación y ancho de la columna
						 			   'desespliq'=>array('justification'=>'left','width'=>300),   // Justificación y ancho de la columna
						 			   'diapag'=>array('justification'=>'center','width'=>60),      // Justificación y ancho de la columna
									   'subtotal'=>array('justification'=>'right','width'=>90)));       // Justificación y ancho de la columna
		$la_columnas=array('numespliq'=>'<b> Nº </b>',
						   'desespliq'=>'<b>  Descripción</b>',
						   'diapag'=>'<b>Días</b>',
						   'subtotal'=>'<b>Monto                </b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle   
//-----------------------------------------------------------------------------------------------------------------------------------//	
function uf_print_total($ld_total,&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function : uf_print_total
	//		    Acess : private
	//	    Arguments : $ld_total
	//    Description : función que imprime el total de asignaciones
	//	   Creado Por : Ing. Maria Alejandra Roa
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$la_data=array(array('total'=>'<b> TOTAL MONTO A PAGAR   </b>','totalasig'=>$ld_total));
	$la_columna=array('total'=>'','totalasig'=>'');
	$la_config=array('showHeadings'=>0,      // Mostrar encabezados
					 'fontSize' => 7,        // Tamaño de Letras
					 'showLines'=>1,         // Mostrar Líneas
					 'shaded'=>0,            // Sombra entre líneas
					 'width'=>500,           // Ancho Máximo de la tabla
					 'rowGap'=>2,
					 'colGap'=>0,
					 'xOrientation'=>'center', // Orientación de la tabla
			 		 'cols'=>array('total'=>array('justification'=>'right','width'=>410), // Justificación y ancho de la columna
					 			   'totalasig'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);

}// end function uf_print_total

function uf_print_texto(&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function : uf_print_texto
	//		    Acess : private
	//	    Arguments : 
	//    Description : función que imprime el texto del reporte
	//	   Creado Por : Ing. Maria Alejandra Roa
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
	$io_pdf->addText(60,350,8, "");
	$io_pdf->addText(60,340,8,"Yo, ________________________________ portador(a) de la cédula de identidad Nº_________________, por medio de la presente ");
    $io_pdf->addText(60,320,8,"declaro que he recibido a mi entera satisfacción de '".$_SESSION["la_empresa"]["nombre"]."' las cantidades arriba  descritas y nada tengo ");
	$io_pdf->addText(60,300,8,"que reclamar por estos o cualquier otro concepto derivado de la terminación  de la relación laboral, firmando conforme.");
	$io_pdf->addText(60,280,8,"  ");
	$io_pdf->addText(60,260,7,"                                                                 firma _________________________ ");
	$io_pdf->addText(60,150,8, "");
	$io_pdf->addText(60,130,8,"Revisado por: _________________________"."           "." Aprobado Por: _________________________");
	
}// end function uf_print_texto

//-----------------------------------------------------------------------------------------------------------------------------------//
//                                                      Datos del reporte
//-----------------------------------------------------------------------------------------------------------------------------------//
$ls_empresa = $_SESSION["la_empresa"]["nombre"];
$ls_codper  = $_GET["codper"];
$ls_nomper  = $_GET["nomper"];
$ls_codnom  = $_GET["codnom"];
$ls_numliq  = $_GET["numliq"];
$ls_titulo  = "<b> Liquidación de Prestaciones Sociales </b>";
//-----------------------------------------------------------------------------------------------------------------------------------// 

//-----------------------------------------------------------------------------------------------------------------------------------//
  error_reporting(E_ALL);
  set_time_limit(1800);
  $io_pdf = new Cezpdf('LETTER','portrait');                         // Instancia de la clase PDF
  $io_pdf->selectFont('../../../shared/ezpdf/fonts/Helvetica.afm');     // Seleccionamos el tipo de letra
  $io_pdf->ezSetCmMargins(4.5,3,3,3);                                // Configuración de los margenes en centímetros
  uf_print_encabezado_pagina($ls_titulo,$io_pdf);                    // Imprimimos el encabezado de la página
  $io_pdf->ezStartPageNumbers(550,50,10,'','',1);                    // Insertar el número de página
  
  $lb_valido=$lo_liq_dao->getCabeceraLiquidacion($ls_codper,$ls_codnom,$ls_numliq,$la_array);
 
  if ($lb_valido==false) // Existe algún error ó no hay registros
  {
		print("<script language=JavaScript>");
		print(" alert('No existen datos a Reportar.');");
		print(" close();");
		print("</script>");
  }
  else
  {
  	   $io_pdf->transaction('start'); // Iniciamos la transacción
	   $thisPageNum=$io_pdf->ezPageCount; 
	  	  
	   $ls_cedper = $la_array["cedper"][0];
	   $ls_fecliq = $lo_function->uf_dtoc($la_array["fecliq"][0]);
	   $ls_fecing = $lo_function->uf_dtoc($la_array["fecing"][0]);
	   $ls_fecegr = $lo_function->uf_dtoc($la_array["fecegr"][0]);
	   $ls_cargo  = $la_array["descargo"][0];
	   $li_anoser = $la_array["anoser"][0];
	   $li_messer = $la_array["messer"][0];
	   $li_diaser = $la_array["diaser"][0];
	   $ls_dencauret = $la_array["dencauret"][0];
	   $ld_salint    = $lo_function->uf_ntoc($la_array["salint"][0], 2);
	   $ld_sueproper = $lo_function->uf_ntoc($la_array["sueproper"][0], 2);
	   $ld_suediaper = $la_array["salint"][0]/30;
	   $ld_suediaper = $lo_function->uf_ntoc($ld_suediaper, 2);
	   $ls_desuniadm = $la_array["desuniadm"][0];
	   
	    $la_data[0]=array('numliq'=>$ls_numliq,'fecliq'=>$ls_fecliq);
		uf_print_cabecera_1($la_data,$io_pdf);
		$la_data2[0]=array('nomper'=>$ls_nomper,'cedper'=>$ls_cedper);
		uf_print_cabecera_2($la_data2,$io_pdf);
		$la_data3[0]=array('descargo'=>$ls_cargo,'desuniadm'=>$ls_desuniadm);
		uf_print_cabecera_3($la_data3,$io_pdf);
        $la_data4[0]=array('fecing'=>$ls_fecing,'fecegr'=>$ls_fecegr,'anoser'=>$li_anoser,'messer'=>$li_messer,'diaser'=>$li_diaser);
		uf_print_cabecera_4($la_data4,$io_pdf);
		$la_data5[0]=array('dencauret'=>$ls_dencauret,'sueproper'=>$ld_sueproper,'salint'=>$ld_salint,'suediaper'=>$ld_suediaper);
		uf_print_cabecera_5($la_data5,$io_pdf);
		uf_print_cabecera_6($io_pdf);
		
		$lb_valido=$lo_liq_dao->getDetalleLiquidacionReporte($ls_codper,$ls_codnom,$ls_numliq,$la_detalle);
        if ($lb_valido)
		{   $ld_total=0;
			$li_totrow_det=count($la_detalle["numespliq"]);
			for($li_d=0;$li_d<$li_totrow_det;$li_d++)
			{
			    $li_numespliq = $la_detalle["numespliq"][$li_d];
				$ls_desespliq = $la_detalle["desespliq"][$li_d];
				$ld_diapag    = $lo_function->uf_ntoc($la_detalle["diapag"][$li_d], 2);
				$ld_subtotal  = $lo_function->uf_ntoc($la_detalle["subtotal"][$li_d], 2);
				$ld_total     = $ld_total+($la_detalle["subtotal"][$li_d]);
				$la_data[$li_d]=array('numespliq'=>$li_numespliq,'desespliq'=>$ls_desespliq,'diapag'=>$ld_diapag,'subtotal'=>$ld_subtotal);
			}
			uf_print_detalle($la_data,$io_pdf);            // Imprimimos el detalle 
			$ld_total = $lo_function->uf_ntoc($ld_total, 2);
			uf_print_total($ld_total,$io_pdf);             // Imprimimos el total
		}
		else
		{
			/*$io_pdf->add_tabla('center',$la_datos,$la_opciones);
			$la_data=array(array('name'=>'<i>No existen Registros de detalle para esta Liquidación.</i>'));
		    $la_columna=array('name'=>'');
			$la_config=array('showHeadings'=>0,        // Mostrar encabezados
							 'showLines'=>1,           // Mostrar Líneas
							 'fontSize' => 8,          // Tamaño de Letras
							 'xOrientation'=>'center', // Orientación de la tabla
							 'width'=>500,             // Ancho de la tabla
							 'maxWidth'=>500);         // Ancho Máximo de la tabla
		    $io_pdf->ezTable($la_data,$la_columna,'',$la_config);*/
		}
		uf_print_texto($io_pdf);
	
  } //end else
  if ($lb_valido) // Si no ocurrio ningún error
  {
	 $io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
	 $io_pdf->ezStream();             // Mostramos el reporte
   }
   else  // Si hubo algún error
   {
		 print("<script language=JavaScript>");
		 print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
		 print(" close();");
		 print("</script>");		
	}
   unset($io_pdf);

//unset($lo_reporte_base);
unset($lo_function);			
  

?>
