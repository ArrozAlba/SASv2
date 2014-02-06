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
	
	ini_set('memory_limit','1024M');
 	ini_set('max_execution_time ','0');  

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 24/07/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_soc;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_soc->uf_load_seguridad_reporte("SOC","sigesp_soc_r_solicitud_cotizacion.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el encabezado del Reporte de las Solicitudes de Cotización.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_encabezado=$io_pdf->openObject();		
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->saveState();
		$io_pdf->rectangle(140,705,450,40);
		$io_pdf->line(450,705,450,745);
		$io_pdf->line(450,725,590,725);

		$io_pdf->saveState();		
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],40,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=285-($li_tm/2);
		$io_pdf->addText($tm,720,12,"<b>".$as_titulo."</b>"); // Agregar el título
		$io_pdf->addText(490,730,10,"Fecha: ".date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(500,710,10,"Hora: ".date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//--------------------------------------------------------------------------------------------------------------------------------	
	
    //--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalles($la_data,&$io_pdf)
	{	 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalles
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: Funcion que imprime el las solicitudes de cotizacion encontradas.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				

				
		$la_columna=array('numsolcot'=>'<b>Nro. Solicitud</b>',						 
						  'obssolcot'=>'<b>Observacion</b>',
						  'fecsolcot'=>'<b>Fecha</b>',
						  'tipsolcot'=>'<b>Tipo</b>');
						  
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'colGap'=>1, // Mostrar Líneas						 
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xPos'=>315, // Orientación de la tabla
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'cols'=>array('numsolcot'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna						 			   
						 			   'obssolcot'=>array('justification'=>'left','width'=>320), // Justificación y ancho de la columna
						 			   'fecsolcot'=>array('justification'=>'center','width'=>70),
   						 			   'tipsolcot'=>array('justification'=>'center','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalles
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_numreg,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>N° de Registros:</b>'.$ad_numreg));				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 11, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>750, // Ancho de la tabla						 
						 'maxWidth'=>750, // Orientaci? de la tabla
						 'cols'=>array('name'=>array('justification'=>'left','width'=>560))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
    require_once("sigesp_soc_class_report.php");
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../class_folder/class_funciones_soc.php");
    require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_funciones.php");
	
	$in			  = new sigesp_include();
	$con   		  = $in->uf_conectar();
	$io_sql       = new class_sql($con);	
	$io_report	  = new sigesp_soc_class_report($con);
	$io_funciones = new class_funciones();
	$io_fun_soc   = new class_funciones_soc();		
		
	//----------------------------------------------------  Inicializacion de variables  -----------------------------------------------
	$lb_valido=false;
	//----------------------------------------------------  Parámetros del encabezado    -----------------------------------------------
	$ls_titulo ="REPORTE DE SOLICITUD DE COTIZACIONES";	
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------	

    $ls_solcotdes = $_GET["solcotdes"];
	$ls_solcothas = $_GET["solcothas"];
    $ls_codprodes = $_GET["codprodes"];
	$ls_codprohas = $_GET["codprohas"];
    $ls_numsepdes = $_GET["numsepdes"];
	$ls_numsephas = $_GET["numsephas"];
    $ls_fecsoldes = $_GET["fecsoldes"];
	$ls_fecsolhas = $_GET["fecsolhas"];
	$ls_tipsolcot = $_GET["tipsolcot"]; 
	$ls_estsolcot = $_GET["estsolcot"];

	$lb_valido = uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if ($lb_valido)
	   {
         $rs_data = $io_report->uf_load_solicitudes_cotizacion($ls_solcotdes,$ls_solcothas,$ls_codprodes,$ls_codprohas,$ls_numsepdes,$ls_numsephas,
	                                                           $ls_fecsoldes,$ls_fecsolhas,$ls_tipsolcot,$ls_estsolcot,&$lb_valido);
	     if (!$lb_valido)
	        {
		      print("<script language=JavaScript>");
		      print("alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
		      print("close();");
		      print("</script>");		
	        }
         else
	        {
	          $li_numrows = $io_sql->num_rows($rs_data);
		      if ($li_numrows>0)
		         {
				   $li_i = 0;
				   error_reporting(E_ALL);
				   set_time_limit(1800);
				   $io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
				   $io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
				   $io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
				   uf_print_encabezado_pagina($ls_titulo,$io_pdf);
				   while ($row=$io_sql->fetch_row($rs_data))
					 	 {
						   $li_i++;
						   $ls_numsolcot = $row["numsolcot"];
						   $ls_fecsolcot = $row["fecsol"];
						   $ls_fecsolcot = $io_funciones->uf_convertirfecmostrar($ls_fecsolcot);
						   $ls_obssolcot = $row["obssol"];
						   $ls_tipsolcot = $row["tipsolcot"];
						   if ($ls_tipsolcot=='B')
							  {
							    $ls_tipsolcot = "Bienes";
							  }
						   elseif($ls_tipsolcot=='S')
							  {
							    $ls_tipsolcot = "Servicios";
							  }
						   $la_data[$li_i] = array('numsolcot'=>$ls_numsolcot,'obssolcot'=>$ls_obssolcot,'fecsolcot'=>$ls_fecsolcot,'tipsolcot'=>$ls_tipsolcot);
						 }
				   uf_print_detalles($la_data,$io_pdf);//Imprimimos el detalle 										
				   uf_print_pie_cabecera($li_i,$io_pdf);
				   $io_pdf->setStrokeColor(0,0,0);
				   $io_pdf->line(20,50,580,50);
				   $io_pdf->ezStopPageNumbers(1,1);
				   $io_pdf->ezStream();
				   unset($io_pdf);
				   unset($io_report);
				   unset($io_funciones);
			     }
	          else
		         {
			       print("<script language=JavaScript>");
			       print(" alert('No hay nada que Reportar');"); 
			       print(" close();");
			       print("</script>");
			     }
	        }
	   }	
?>