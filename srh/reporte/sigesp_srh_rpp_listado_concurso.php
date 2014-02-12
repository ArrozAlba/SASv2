<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Reporte de Listado de Concurso
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
		// Fecha Creación: 25/02/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_srh;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("SRH","sigesp_srh_listado_concurso.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo1,as_titulo2,as_titulo3,as_titulo4 // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 11/02/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(15,40,585,40);
        
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		
		$io_pdf->addText(540,770,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(546,764,6,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');

	    $io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSetY(691.5);
	
					
		$la_columnas=array('', '');
					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('desde'=>array('justification'=>'left','width'=>270), // Justificación y ancho de la columna
						 			   'hasta'=>array('justification'=>'lef','width'=>300))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
	
	    $io_pdf->ezSetY(667);
		$la_data=array(array('titulo1'=>'<b>'.$as_titulo.'</b>'));
					
		$la_columnas=array('titulo1'=>'');
					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>12, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('titulo1'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
	    $io_pdf->ezSetY(637);
		$la_data[1]=array('codigo'=>'<b>Código del Concurso</b>',
		                     'descrip'=>'<b>Descripción del Concurso         </b>',
							 'fecaper'=>'<b>Fecha Inicio</b>',
							 'feccie'=>'<b>Fecha Culminación</b>',
							 'cargo'=>'<b>Cargo</b>',
							 'cantcar'=>'<b>Cantidad</b>',
							 'estatus'=>'<b>Estatus</b>' 
							  );
		$la_columnas=array('codigo'=>'',
						   'descrip'=>'',
						   'fecaper'=>'',
						   'feccie'=>'',
						   'cargo'=>'',
						   'cantcar'=>'',
						   'estatus'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 14,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,						 
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'descrip'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'fecaper'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'feccie'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'cargo'=>array('justification'=>'center','width'=>90),

									   'cantcar'=>array('justification'=>'center','width'=>60),

									   'estatus'=>array('justification'=>'center','width'=>70))); // Justificación y ancho de la columna
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
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el detalle del reporte.
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 11/02/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(613);
		$la_columnas=array('codigo'=>'',
						   'descrip'=>'',
						   'fecaper'=>'',
						   'feccie'=>'',
						   'cargo'=>'',
						   'cantcar'=>'',
						   'estatus'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'descrip'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'fecaper'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'feccie'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'cargo'=>array('justification'=>'center','width'=>90),

									   'cantcar'=>array('justification'=>'center','width'=>60),

									   'estatus'=>array('justification'=>'center','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
  	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha = new class_fecha();	
	require_once("class_folder/sigesp_srh_class_report.php");
	$io_report  = new sigesp_srh_class_report('../../');
	require_once("../class_folder/utilidades/class_funciones_srh.php");
	$io_fun_srh=new class_funciones_srh("../../");	
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];	
	require_once("../../shared/class_folder/sigesp_include.php");
	$io_include=new sigesp_include();
	$io_conexion=$io_include->uf_conectar();
	require_once("../../shared/class_folder/class_sql.php");
	$io_sql=new class_sql($io_conexion);	
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
       $ls_titulo="<b>LISTADO DE CONCURSOS</b>"; 
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ld_fechades=$io_fun_srh->uf_obtenervalor_get("fechades","");
	$ld_fechahas=$io_fun_srh->uf_obtenervalor_get("fechahas","");
	$ls_estatus=$io_fun_srh->uf_obtenervalor_get("estatus","");
	$ls_orden=$io_fun_srh->uf_obtenervalor_get("orden","");

	
	//-----------------------------------------------------------------------------------------------------------------------------------
	global $la_data;
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_select_concurso($ld_fechades,$ld_fechahas,$ls_estatus,$ls_orden,$rs_data); // Cargar el DS con los datos del reporte
		if($lb_valido==false) // Existe algún error ó no hay registros
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}
		else  // Imprimimos el reporte
		{
			 error_reporting(E_ALL);
			 set_time_limit(1800);
			 $io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			 $io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			 $io_pdf->ezSetCmMargins(4,3,3,3); // Configuración de los margenes en centímetros
			 $io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página
			
			$li_i=0;

			
		    while ($row=$io_sql->fetch_row($rs_data))
		   {
		   
				$ls_codigo=$row["codcon"];
				$ls_descrip=$row["descon"];
				$ls_fecaper=$row["fechaaper"];
				$ls_feccie=$row["fechacie"];
				$ls_codcar1=$row["codasicar"];
				$ls_codcar2=$row["codcar"];
				
				 if ($ls_codcar1=="")
				 {	
				 
					$ls_cargo=trim  ($row["descar"]);
				 }
				else
				 {
				   	$ls_cargo=trim ($row["denasicar"]);
					   
				 }
				
				
				$ls_cantcar=$row["cantcar"];
				$ls_estatus=$row["estatus"];
				
							
			   	
				$ls_fecaper=$io_funciones->uf_formatovalidofecha($ls_fecaper);
				$ls_fecaper=$io_funciones->uf_convertirfecmostrar($ls_fecaper);
				
				$ls_feccie=$io_funciones->uf_formatovalidofecha($ls_feccie);
				$ls_feccie=$io_funciones->uf_convertirfecmostrar($ls_feccie);
				
				$la_data[$li_i]=array('codigo'=>$ls_codigo,'descrip'=>$ls_descrip,'fecaper'=>$ls_fecaper,'feccie'=>$ls_feccie,
									  'cargo'=>$ls_cargo,'cantcar'=>$ls_cantcar,'estatus'=>$ls_estatus );
									  
				$li_i=$li_i+1;
				
				
			  
			}
			 uf_print_detalle($la_data,$io_pdf);
			uf_print_encabezado_pagina($ls_titulo,$io_pdf);
			
			if($lb_valido) // Si no ocurrio ningún error
				{
					$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
					$io_pdf->ezStream(); // Mostramos el reporte
				}
				else // Si hubo algún error
				{
					print("<script language=JavaScript>");
					print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
					print(" close();");
					print("</script>");		
				}
        }
	}
?>

