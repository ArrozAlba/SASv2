<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Listado de Amonestaciones
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
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 05/03/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_srh;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("SRH","sigesp_srh_r_listado_amonestaciones.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo1,$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo// Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 05/03/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
		$io_pdf->ezSetY(520);
		$la_data=array(array('titulo1'=>'<b>'.$as_titulo1.'</b>'));
					
		$la_columnas=array('titulo1'=>'');
					

		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 12, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>730, // Ancho de la tabla
						 'maxWidth'=>730, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('titulo1'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
	    $io_pdf->ezSetY(470);
		$la_data[1]=array('codper'=>'<b>Código</b>',
		                  'nombper'=>'<b>Nombre y Apellido</b>',
						  'uniadm'=>'<b>Unidad Administrativa</b>',
						  'cargo'=>'<b>Cargo</b>',
						  'fecha'=>'<b>Fecha</b>',
						  'tipo'=>'<b>Tipo</b>',
						  'causa'=>'<b>Causas</b>');
		$la_columnas=array('codper'=>'',
		                  'nombper'=>'',
						  'uniadm'=>'',
						  'cargo'=>'',
						  'fecha'=>'',
						  'tipo'=>'',
						  'causa'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>11, // Tamaño de Letras
						 'titleFontSize' =>11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>730, // Ancho de la tabla
						 'maxWidth'=>730, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codper'=>array('justification'=>'center','width'=>66), 
						 			   'nombper'=>array('justification'=>'center','width'=>140), 
						 			   'uniadm'=>array('justification'=>'center','width'=>100),
									   'cargo'=>array('justification'=>'ceter','width'=>80), 
						 			   'fecha'=>array('justification'=>'center','width'=>60),
									   'tipo'=>array('justification'=>'center','width'=>100),
									   'causa'=>array('justification'=>'center','width'=>200)));
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
		// Fecha Creación: 05/03/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$la_columnas=array('codper'=>'',
		                  'nombper'=>'',
						  'uniadm'=>'',
						  'cargo'=>'',
						  'fecha'=>'',
						  'tipo'=>'',
						  'causa'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>730, // Ancho de la tabla
						 'maxWidth'=>730, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						'cols'=>array('codper'=>array('justification'=>'center','width'=>66), 
						 			   'nombper'=>array('justification'=>'left','width'=>140), 
						 			   'uniadm'=>array('justification'=>'left','width'=>100),
									   'cargo'=>array('justification'=>'left','width'=>80), 
						 			   'fecha'=>array('justification'=>'center','width'=>60),
									   'tipo'=>array('justification'=>'left','width'=>100),
									   'causa'=>array('justification'=>'left','width'=>200)));
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	
//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales($as_cont1,$as_cont2,$as_cont3,$as_cont4,$as_cont5,$as_cont6,&$io_pdf)
 	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información		//				 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el detalle del reporte.
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 05/03/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$la_data[1]=array('total1'=>'<b>TOTAL AMONESTACIONES: </b>'.$as_cont5);
		$la_columnas=array('total1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>250, // Ancho de la tabla
						 'maxWidth'=>250, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos'=>'650',
						 'cols'=>array('total1'=>array('justification'=>'rigth','width'=>220)));
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('total1'=>'<b>TOTAL LLAMADAS DE ATENCION: </b>'.$as_cont6);
		$la_columnas=array('total1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>250, // Ancho de la tabla
						 'maxWidth'=>250, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos'=>'650',
						 'cols'=>array('total1'=>array('justification'=>'rigth','width'=>220)));
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		
		$la_data[1]=array('total1'=>'<b>Total Amonestaciones - Verbales: </b>'.$as_cont1);
		$la_columnas=array('total1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>250, // Ancho de la tabla
						 'maxWidth'=>250, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos'=>'650',
						 'cols'=>array('total1'=>array('justification'=>'rigth','width'=>220)));
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('total1'=>'<b>Total Amonestaciones - Escritas: </b>'.$as_cont2);
		$la_columnas=array('total1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>250, // Ancho de la tabla
						 'maxWidth'=>250, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos'=>'650',
						 'cols'=>array('total1'=>array('justification'=>'rigth','width'=>220)));
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('total1'=>'<b>Total Llamadas de Atención - Verbales: </b>'.$as_cont3);
		$la_columnas=array('total1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>250, // Ancho de la tabla
						 'maxWidth'=>250, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos'=>'650',
						 'cols'=>array('total1'=>array('justification'=>'rigth','width'=>220)));
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('total1'=>'<b>Total Llamadas de Atención - Escritas: </b>'.$as_cont4);
		$la_columnas=array('total1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>250, // Ancho de la tabla
						 'maxWidth'=>250, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos'=>'650',
						 'cols'=>array('total1'=>array('justification'=>'rigth','width'=>220)));
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		
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
	require_once("../../shared/class_folder/sigesp_include.php");
	$io_include=new sigesp_include();
	$io_conexion=$io_include->uf_conectar();
	require_once("../../shared/class_folder/class_sql.php");
	$io_sql=new class_sql($io_conexion);	
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
       $ls_titulo1="<b>LISTADO DE AMONESTACIONES / LLAMADAS DE ATENCIÓN</b>"; 
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_fechainides=$io_fun_srh->uf_obtenervalor_get("fechainides","");
	$ls_fechafinhas=$io_fun_srh->uf_obtenervalor_get("fechafinhas","");
	$ls_codperdes=$io_fun_srh->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_srh->uf_obtenervalor_get("codperhas","");
	$ls_coduniadm1=$io_fun_srh->uf_obtenervalor_get("coduniadm1","");
	$ls_coduniadm2=$io_fun_srh->uf_obtenervalor_get("coduniadm2","");
	$ls_causa=$io_fun_srh->uf_obtenervalor_get("causa","");
	$ls_orden=$io_fun_srh->uf_obtenervalor_get("orden","");
	//-----------------------------------------------------------------------------------------------------------------------------------
	global $la_data;
	$lb_valido=uf_insert_seguridad($ls_titulo1); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_listado_amonestacion($ls_fechainides,$ls_fechafinhas,$ls_codperdes,$ls_codperhas,$ls_coduniadm1,$ls_coduniadm2,$ls_causa,$ls_orden, $rs_data); 
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
			$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(4,3,3,3); // Configuración de los margenes en centímetros
			$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página
		  	$li_totrow=$io_report->DS->getRowCount("codemp");
			$li_i=0;
			$ls_cont1=0;
			$ls_cont2=0;
			$ls_cont3=0;
			$ls_cont4=0;
			$ls_causas="";
			while ($row=$io_sql->fetch_row($rs_data))
		   {
		   		$ls_codper=$row["codper"];
				$ls_fecha=$row["fecllam"];
				$ls_numllam=$row["nrollam"];
				$ls_tipo= trim ($row["tipo"]);
				$ls_causa= trim ($row["causa"]);
				
				if ($ls_causa == '1')
				{
					  if ($ls_tipo == '1')
					  {					
						$ls_tipo='Amonestacion - Verbal';												
						$ls_cont1++; 
					  }
					  elseif ($ls_tipo == '2')
					  {					
						 $ls_tipo='Amonestacion - Escrita';																		
						 $ls_cont2++;
					  }
				}
				elseif ($ls_causa == '2')
				{
				  if ($ls_tipo == '1')
				  {					
					$ls_tipo= 'Llamada de Atencion - Verbal';											
					$ls_cont3++;
				  }
				  elseif ($ls_tipo == '2')
				  {					
					$ls_tipo='Llamada de Atencion - Escrita';	
					$ls_cont4++;											
				  }
				}
				
				$ls_cargo1=trim ($row["denasicar"]);
				$ls_cargo2=trim ($row["descar"]);
				
				if ($ls_cargo1!="Sin Asignación de Cargo")
				{
					 $ls_cargo=$ls_cargo1;
				}
			    if ($ls_cargo2!="Sin Cargo")
				{
				 	$ls_cargo=$ls_cargo2;
				}				
				
				$ls_motivo=trim ($row["descripcion"]);
				$ls_nombreper=$row["nomper"];
				$ls_apellidoper=$row["apeper"];
				$ls_desuniadm= trim ($row["desuniadm"]);
				$ls_fecha=$io_funciones->uf_formatovalidofecha($ls_fecha);
				$ls_fecha=$io_funciones->uf_convertirfecmostrar($ls_fecha);
				$ls_cadena=$ls_nombreper."  ".$ls_apellidoper;
				
				
				$lb_valido=$io_report->uf_select_causa_llamada_atencion($ls_numllam, $rs_data2); 
				
				if ($lb_valido)
				{
					while ($row=$io_sql->fetch_row($rs_data2))
		   			{
		   				if ($ls_causas=="")
						{
							$ls_causas = trim ($row["dencaullam_aten"]);
						}
						else
						{
							$ls_causas = $ls_causas." / ".trim ($row["dencaullam_aten"]);
						}
					}
					
					$la_data[$li_i]=array('codper'=>$ls_codper,'nombper'=>$ls_cadena,'uniadm'=>$ls_desuniadm,'cargo'=>$ls_cargo,
									  'fecha'=>$ls_fecha,'tipo'=>$ls_tipo,'causa'=>$ls_causas);
									
				    $li_i=$li_i+1;
					$ls_causas="";
				}
				
				
			}
			uf_print_encabezado_pagina($ls_titulo1,$io_pdf);
			uf_print_detalle($la_data,$io_pdf);
			$li_total_amo= $ls_cont1 + $ls_cont2;
			$li_total_llam= $ls_cont3 + $ls_cont4;			
			uf_print_totales($ls_cont1,$ls_cont2,$ls_cont3,$ls_cont4, $li_total_amo, $li_total_llam,$io_pdf);
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
        }
	}
?>

