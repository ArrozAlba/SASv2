<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Reporte de Movimientos de Personal
//  ORGANISMO: IPSFA
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";		
		print "</script>";		
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 06/03/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_srh;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("SRH","sigesp_srh_r_listado_movimiento_personal.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo1,$as_titulo2,$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 06/03/2008		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(15,40,950,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,510,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		
		$io_pdf->addText(906,560,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(912,553,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');

	    $io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSetY(480);
		$la_data=array(array('titulo1'=>'<b>'.$as_titulo1.'</b>'));
					
		$la_columnas=array('titulo1'=>'');
					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 14, // Tamaño de Letras
						 'titleFontSize' =>14,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('titulo1'=>array('justification'=>'center','width'=>940))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		
		$la_data2=array(array('titulo1'=>'<b>'.$as_titulo2.'</b>'));
					
		$la_columnas2=array('titulo1'=>'');
					
		$la_config2=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 14, // Tamaño de Letras
						 'titleFontSize' =>14,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('titulo1'=>array('justification'=>'center','width'=>940))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data2,$la_columnas2,'',$la_config2);
        unset($la_data2);
		unset($la_columnas2);
		unset($la_config2);
		
	    $io_pdf->ezSetY(430);
		$la_data[1]=array('nombre'=>'<b>Nombre y Apellido</b>',
						  'cedula'=>'<b>Cédula</b>',
						  'uniadm1'=>'<b>Unidad Adm. Anterior</b>',
						  'cargo1'=>'<b>Cargo Anterior</b>',
						  'motivo'=>'<b>Motivo</b>',
						  'tipomov'=>'<b>Tipo Mov.</b>',
						  'fecvig'=>'<b>Fecha Vigencia</b>',
						  'uniadm2'=>'<b>Unidad Adm. Actual</b>',
						  'cargo2'=>'<b>Cargo Actual</b>');
		$la_columnas=array('nombre'=>'',
						  'cedula'=>'',
						  'uniadm1'=>'',
						  'cargo1'=>'',
						  'motivo'=>'',
						  'tipomov'=>'',
						  'fecvig'=>'',
						  'uniadm2'=>'',
						  'cargo2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('nombre'=>array('justification'=>'center','width'=>160),
						               'cedula'=>array('justification'=>'center','width'=>70),
						  			   'uniadm1'=>array('justification'=>'center','width'=>100),
						  			   'cargo1'=>array('justification'=>'center','width'=>100),
 						  			   'motivo'=>array('justification'=>'center','width'=>100),
						  			   'tipomov'=>array('justification'=>'center','width'=>100),
						  			   'fecvig'=>array('justification'=>'center','width'=>80),
						  			   'uniadm2'=>array('justification'=>'center','width'=>100),
						  			   'cargo2'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	
		$io_pdf->restoreState();
	    $io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	
	
     }// end function uf_print_encabezado_pagina
	 //-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
 	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//				 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el detalle del reporte.
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 04/03/2008		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$la_columnas=array('nombre'=>'',
						  'cedula'=>'',
						  'uniadm1'=>'',
						  'cargo1'=>'',
						  'motivo'=>'',
						  'tipomov'=>'',
						  'fecvig'=>'',
						  'uniadm2'=>'',
						  'cargo2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>940, // Ancho de la tabla
						 'maxWidth'=>940, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('nombre'=>array('justification'=>'center','width'=>160),
						               'cedula'=>array('justification'=>'center','width'=>70),
						  			   'uniadm1'=>array('justification'=>'center','width'=>100),
						  			   'cargo1'=>array('justification'=>'center','width'=>100),
 						  			   'motivo'=>array('justification'=>'center','width'=>100),
						  			   'tipomov'=>array('justification'=>'center','width'=>100),
						  			   'fecvig'=>array('justification'=>'center','width'=>80),
						  			   'uniadm2'=>array('justification'=>'center','width'=>100),
						  			   'cargo2'=>array('justification'=>'center','width'=>100)));// Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
    require_once("../../shared/ezpdf/class.ezpdf.php");	
	require_once("class_folder/sigesp_srh_class_report.php");
	$io_report=new sigesp_srh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/utilidades/class_funciones_srh.php");
	$io_fun_srh=new class_funciones_srh('../../');
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
      
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_fechades=$io_fun_srh->uf_obtenervalor_get("fechades","");
	$ls_fechahas=$io_fun_srh->uf_obtenervalor_get("fechahas","");
	$ls_codperdes=$io_fun_srh->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_srh->uf_obtenervalor_get("codperhas","");
	$ls_orden=$io_fun_srh->uf_obtenervalor_get("orden","");
	
 $ls_titulo1="<b>LISTADO DE MOVIMIENTOS DE PERSONAL</b>"; 
 
 if ($ls_fechades!="")
 {
   $ls_titulo2="<b>DEL </b>".$ls_fechades."<b> AL </b>".$ls_fechahas; 
 }
 else
 {
   $ls_titulo2=""; 
  }
	//-----------------------------------------------------------------------------------------------------------------------------------
	global $la_data;
	$lb_valido=uf_insert_seguridad($ls_titulo1); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_select_listado_movimiento_personal($ls_fechades,$ls_fechahas,$ls_codperdes,$ls_codperhas,$ls_orden,$rs_data); // Cargar el DS con los datos del reporte
		if($lb_valido==false) // Existe algún error ó no hay registros
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			//print(" close();");
			print("</script>");
		}
		else  // Imprimimos el reporte
		{
			error_reporting(E_ALL);
		    set_time_limit(1800);
			$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(6,3,3,3);  // Configuración de los margenes en centímetros
			$io_pdf->ezStartPageNumbers(950,50,9,'','',1);; // Insertar el número de página
		  	$li_totrow=$io_report->io_sql->num_rows($rs_data); 
			$li_i=0;
			while($row=$io_report->io_sql->fetch_row($rs_data))
			{   	
				$ls_dengrumov=trim ($row["dengrumov"]);
				$ls_motivo=trim ($row["motivo"]);
				$ls_cedula=trim ($row["cedper"]);
				$ls_nombre=trim ($row["nomper"]);
				$ls_apellido=trim ($row["apeper"]);
				$ls_fecha=trim ($row["fecinimov"]);
				$ls_fecha=$io_funciones->uf_formatovalidofecha($ls_fecha);
				$ls_fecha=$io_funciones->uf_convertirfecmostrar($ls_fecha);
				$ls_cadena=$ls_nombre."  ".$ls_apellido;
				
				$ls_car1 =trim ($row["cargo1"]);
				$ls_car2 =trim ($row["cargo2"]);
				
				if ($ls_car1=="")
				{
					$ls_cargo2 = $ls_car2;	
				}
				else
				{
					$ls_cargo2 = $ls_car1;
				}
				
				$ls_desuniadm2 = trim ($row["desuniadm"]);
				$ls_codper=trim ($row["codper"]);
				$ls_nummov=trim ($row["nummov"]);
							
				 $io_report->uf_select_hmovimiento($ls_codper, $ls_nummov, $ls_uniadmant, $ls_cargoant);
				$li_i++;
				$la_data[$li_i]=array('nombre'=>$ls_cadena,'cedula'=>$ls_cedula,'uniadm1'=>$ls_uniadmant,'cargo1'=>$ls_cargoant,
				                      'motivo'=>$ls_motivo,'tipomov'=>$ls_dengrumov,'fecvig'=>$ls_fecha,
									   'uniadm2'=>$ls_desuniadm2, 'cargo2'=>$ls_cargo2);
			}
			uf_print_encabezado_pagina($ls_titulo1,$ls_titulo2,$io_pdf);
			uf_print_detalle($la_data,$io_pdf);
			if($lb_valido) // Si no ocurrio ningún error
				{
					$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
					$io_pdf->ezStream(); // Mostramos el reporte
				}
				else // Si hubo algún error
				{
					print("<script language=JavaScript>");
					print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
					//print(" close();");
					print("</script>");		
				}
		 
        }// fin del else
	}
?>


