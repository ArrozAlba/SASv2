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
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_encabezadopagina
	//		    Acess: private
	//	    Arguments: as_titulo // Título del Reporte
	//	    		   as_periodo_comp // Descripción del periodo del comprobante
	//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante
	//	    		   io_pdf // Instancia de objeto pdf
	//    Description: función que imprime los encabezados por página
	//	   Creado Por: Ing.Yozelin Barragán
	// Fecha Creación: 12/09/2006
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	$io_encabezado=$io_pdf->openObject();
	$io_pdf->saveState();
	$io_pdf->line(10,30,1000,30);
	// Agregar Logo
	$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],20,535,$_SESSION["ls_width"],$_SESSION["ls_height"]);
	$li_tm=$io_pdf->getTextWidth(12,$as_titulo);
	$tm=505-($li_tm/2);
	$io_pdf->addText($tm,550,12,$as_titulo); // Agregar el título

	$io_pdf->addText(900,550,10,date("d/m/Y")); // Agregar la Fecha
	$io_pdf->addText(900,540,10,date("h:i a")); // Agregar la hora
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($io_cabecera,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
				                  $as_codestpro5,$as_denestpro1,$as_denestpro2,$as_denestpro3,$ls_desper,$ls_lapso_meses,$ls_text_periodo,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: privates
		//	    Arguments: as_programatica // programatica del comprobante
		//	    		   as_denestpro5 // denominacion de la programatica del comprobante
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing.Yozelin Barragán
	    // Fecha Creación: 12/09/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->ezSetY(520);
		$io_pdf->addText(450,528,7,$ls_desper.': '.$ls_text_periodo);
		$ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
	    $ls_incio1=25-$ls_loncodestpro1;
	    $ls_codestpro1=substr($as_codestpro1,$ls_incio1,$ls_loncodestpro1);

		$ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
	    $ls_incio2=25-$ls_loncodestpro2;
	    $ls_codestpro2=substr($as_codestpro2,$ls_incio2,$ls_loncodestpro2);

		$ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
	    $ls_incio3=25-$ls_loncodestpro3;
	    $ls_codestpro3=substr($as_codestpro3,$ls_incio3,$ls_loncodestpro3);

		if($as_codestpro2!="")
		{
		  $ls_tituto_2=" ACCION ESPECIFICA: ";
		}
		else
		{
		  $ls_tituto_2="";
		}
		if($as_codestpro3!="")
		{
		  $ls_tituto_3=" UNIDAD: ";
		}
		else
		{
		  $ls_tituto_3="";
		}
		$la_data=array(array('name'=>'<b>PROYECTO/ACCION CENTRALIZADA: </b> '.$ls_codestpro1.'  '.$as_denestpro1),
		               array('name'=>'<b>'.$ls_tituto_2.'</b> '.$ls_codestpro2.'  '.$as_denestpro2),
					   array('name'=>'<b>'.$ls_tituto_3.'</b> '.$ls_codestpro3.'  '.$as_denestpro3));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9),
						 'shadeCo2'=>array(0.9,0.9,0.9),
						 //'textCol' =>array(0.1,0.1,0.1) , // color del texto
						 'colGap'=>0.5, // separacion entre tablas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>280, // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');
	}// end function uf_print_cabecera

	//--------------------------------------------------------------------------------------------------------------------------------

	function uf_print_pie_cabecera($ad_tipper,$as_nomper01,$as_nomper02,$as_nomper03,$ad_total_per1,$ad_total_per2,$ad_total_per3,$ad_total_disponible,$ad_total_comprometido,$ad_total_ajuste,$ad_total_modificaciones,
	                               $ad_total_precompromiso,$ad_total_libPrecompromiso,&$io_pdf,$as_titulo)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 25/09/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


		switch($ad_tipper)
		{
			case 1:
				$la_datat=array(array('name'=>'___________________________________________________________________________________________________________________________________________________________________________________________________'));
				$la_columna=array('name'=>'');
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'fontSize' => 9, // Tamaño de Letras
								 'showLines'=>0, // Mostrar Líneas
								 'shaded'=>0, // Sombra entre líneas
								 'xOrientation'=>'center', // Orientación de la tabla
								 'xPos'=>500, // Orientación de la tabla
								 'width'=>990); // Ancho Máximo de la tabla
				$io_pdf->ezTable($la_datat,$la_columna,'',$la_config);

				$la_data[]=array('codigo'=>'',
								 'descripcion'=>'<b>'.$as_titulo.'</b>',
								 'disponible'=>$ad_total_disponible,
								 'periodo01'=>'<b>'.$ad_total_per1.'</b>',
				                 'comprometido'=>$ad_total_comprometido,'ajuste'=>$ad_total_ajuste,
				                 'modificaciones'=>$ad_total_modificaciones,'precompromiso'=>$ad_total_precompromiso,'libprecompromiso'=>$ad_total_libPrecompromiso,'disponible2'=>$ad_total_disponible);

				$la_columnas=array('codigo'=>' ','descripcion'=>'',
									'disponible'=>'',
									'periodo01'=>'',
									'comprometido'=>'','ajuste'=>'',
				                    'modificaciones'=>'','precompromiso'=>'','libprecompromiso'=>'',
									'disponible2'=>'');
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'fontSize' => 8, // Tamaño de Letras
								 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
								 'showLines'=>0, // Mostrar Líneas
								 'shaded'=>0, // Sombra entre líneas
								 'colGap'=>0.5, // separacion entre tablas
								 'width'=>990, // Ancho de la tabla
								 'maxWidth'=>990, // Ancho Máximo de la tabla
								 'xOrientation'=>'center', // Orientación de la tabla
								 'xPos'=>510, // Orientación de la tabla
								 'cols'=>array('codigo'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la
								               'descripcion'=>array('justification'=>'left','width'=>150), // Justificación y ancho de la
								 			   'disponible'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la
								 			   'periodo01'=>array('justification'=>'right','width'=>150), // Justificación y ancho de la columna
								 			   'comprometido'=>array('justification'=>'right','width'=>70), // Justificación
								 			   'ajuste'=>array('justification'=>'right','width'=>70), // Justificación y ancho
								 			   'modificaciones'=>array('justification'=>'right','width'=>70), // Justificación y ancho
								 			   'precompromiso'=>array('justification'=>'right','width'=>125), // Justificación y ancho
								 			   'libprecompromiso'=>array('justification'=>'right','width'=>120), // Justificación y ancho
								 			   'disponible2'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la

/*					 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la
								   'denominacion'=>array('justification'=>'center','width'=>150), // Justificación y ancho
								   'disponact'=>array('justification'=>'center','width'=>70), // Justificación y ancho
								   'periodo01'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna								   'totcom'=>array('justification'=>'right','width'=>70),// Justificación y ancho
								   'ajucom'=>array('justification'=>'center','width'=>70),
								   'totcom'=>array('justification'=>'center','width'=>70),
								   'modpres'=>array('justification'=>'center','width'=>70),
								   'precom'=>array('justification'=>'center','width'=>125),
								   'libprecom'=>array('justification'=>'center','width'=>125),
								   'disponible'=>array('justification'=>'center','width'=>70))); // Justificación y ancho
*/


				$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
				$la_data=array(array('name'=>''));
				$la_columna=array('name'=>'');
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'showLines'=>0, // Mostrar Líneas
								 'shaded'=>0, // Sombra entre líneas
								 'width'=>990, // Ancho Máximo de la tabla
								 'xOrientation'=>'center'); // Orientación de la tabla
				$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
				break;

			case 2:
				$la_datat=array(array('name'=>'___________________________________________________________________________________________________________________________________________________________________________________________________'));
				$la_columna=array('name'=>'');
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'fontSize' => 9, // Tamaño de Letras
								 'showLines'=>0, // Mostrar Líneas
								 'shaded'=>0, // Sombra entre líneas
								 'xOrientation'=>'center', // Orientación de la tabla
								 'xPos'=>500, // Orientación de la tabla
								 'width'=>990); // Ancho Máximo de la tabla
				$io_pdf->ezTable($la_datat,$la_columna,'',$la_config);

				$la_data[]=array('codigo'=>'',
									'descripcion'=>'<b>'.$as_titulo.'</b>',
									'disponible'=>$ad_total_disponible,
									'periodo01'=>'<b>'.$ad_total_per1.'</b>',
									'periodo02'=>'<b>'.$ad_total_per2.'</b>',
				                 	'comprometido'=>$ad_total_comprometido,'ajuste'=>$ad_total_ajuste,
				                 	'modificaciones'=>$ad_total_modificaciones,'precompromiso'=>$ad_total_precompromiso,'libprecompromiso'=>$ad_total_libPrecompromiso,'disponible2'=>$ad_total_disponible);

				$la_columnas=array('codigo'=>' ','descripcion'=>'','disponible'=>'',
								   	'periodo01'=>'<b>'.$as_nomper01.'</b>',
								   	'periodo02'=>'<b>'.$as_nomper02.'</b>',
									'comprometido'=>'','ajuste'=>'',
				                   	'modificaciones'=>'','precompromiso'=>'','libprecompromiso'=>'','disponible2'=>'');

				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'fontSize' => 8, // Tamaño de Letras
								 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
								 'showLines'=>0, // Mostrar Líneas
								 'shaded'=>0, // Sombra entre líneas
								 'colGap'=>0.5, // separacion entre tablas
								 'width'=>990, // Ancho de la tabla
								 'maxWidth'=>990, // Ancho Máximo de la tabla
								 //'xOrientation'=>'center', // Orientación de la tabla
								 'xPos'=>505, // Orientación de la tabla
								 'cols'=>array('codigo'=>array('justification'=>'left','width'=>85), // Justificación y ancho de la
								               'descripcion'=>array('justification'=>'left','width'=>110), // Justificación y ancho de la
								 			   'disponible'=>array('justification'=>'right','width'=>110), // Justificación y ancho de la
											   'periodo01'=>array('justification'=>'right','width'=>105), // Justificación y ancho de la columna
											   'periodo02'=>array('justification'=>'right','width'=>105), // Justificación y ancho de la
											   'comprometido'=>array('justification'=>'right','width'=>70), // Justificación
								 			   'ajuste'=>array('justification'=>'right','width'=>70), // Justificación y ancho
								 			   'modificaciones'=>array('justification'=>'right','width'=>70), // Justificación y ancho
								 			   'precompromiso'=>array('justification'=>'right','width'=>100), // Justificación y ancho
								 			   'libprecompromiso'=>array('justification'=>'right','width'=>100), // Justificación y ancho
								 			   'disponible2'=>array('justification'=>'right','width'=>75))); // Justificación y ancho de la

				$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
				$la_data=array(array('name'=>''));
				$la_columna=array('name'=>'');
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'showLines'=>0, // Mostrar Líneas
								 'shaded'=>0, // Sombra entre líneas
								 'width'=>990, // Ancho Máximo de la tabla
								 'xOrientation'=>'center'); // Orientación de la tabla
				$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
				break;

			case 3:
				$la_datat=array(array('name'=>'___________________________________________________________________________________________________________________________________________________________________________________________________'));
				$la_columna=array('name'=>'');
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'fontSize' => 9, // Tamaño de Letras
								 'showLines'=>0, // Mostrar Líneas
								 'shaded'=>0, // Sombra entre líneas
								 'xOrientation'=>'center', // Orientación de la tabla
								 'xPos'=>500, // Orientación de la tabla
								 'width'=>990); // Ancho Máximo de la tabla
				$io_pdf->ezTable($la_datat,$la_columna,'',$la_config);

				$la_data[]=array('codigo'=>'','descripcion'=>'<b>'.$as_titulo.'</b>',
									 'disponible'=>$ad_total_disponible,
									 'periodo01'=>'<b>'.$ad_total_per1.'</b>',
									 'periodo02'=>'<b>'.$ad_total_per2.'</b>',
									 'periodo03'=>'<b>'.$ad_total_per3.'</b>',
				                 	 'comprometido'=>$ad_total_comprometido,'ajuste'=>$ad_total_ajuste,
				                 	 'modificaciones'=>$ad_total_modificaciones,'precompromiso'=>$ad_total_precompromiso,
									 'libprecompromiso'=>$ad_total_libPrecompromiso,
									 'disponible2'=>$ad_total_disponible);

				$la_columnas=array('codigo'=>' ','descripcion'=>'','disponible'=>'',
								   	'periodo01'=>'<b>'.$as_nomper01.'</b>',
								   	'periodo02'=>'<b>'.$as_nomper02.'</b>',
								   	'periodo03'=>'<b>'.$as_nomper03.'</b>',
									'comprometido'=>'','ajuste'=>'',
				                   	'modificaciones'=>'','precompromiso'=>'','libprecompromiso'=>'','disponible2'=>'');
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'fontSize' => 8, // Tamaño de Letras
								 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
								 'showLines'=>0, // Mostrar Líneas
								 'shaded'=>0, // Sombra entre líneas
								 'colGap'=>0.5, // separacion entre tablas
								 'width'=>990, // Ancho de la tabla
								 'maxWidth'=>990, // Ancho Máximo de la tabla
								 //'xOrientation'=>'center', // Orientación de la tabla
								 'xPos'=>505, // Orientación de la tabla
								 'cols'=>array('codigo'=>array('justification'=>'left','width'=>90), // Justificación y ancho de la
								               'descripcion'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la
								 			   'disponible'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la
											   'periodo01'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
											   'periodo02'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la
											   'periodo03'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
								 			   'comprometido'=>array('justification'=>'right','width'=>70), // Justificación
								 			   'ajuste'=>array('justification'=>'right','width'=>70), // Justificación y ancho
								 			   'modificaciones'=>array('justification'=>'right','width'=>70), // Justificación y ancho
								 			   'precompromiso'=>array('justification'=>'right','width'=>100), // Justificación y ancho
								 			   'libprecompromiso'=>array('justification'=>'right','width'=>100), // Justificación y ancho
								 			   'disponible2'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la

				$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
				$la_data=array(array('name'=>''));
				$la_columna=array('name'=>'');
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'showLines'=>0, // Mostrar Líneas
								 'shaded'=>0, // Sombra entre líneas
								 'width'=>990, // Ancho Máximo de la tabla
								 'xOrientation'=>'center'); // Orientación de la tabla
				$io_pdf->ezTable($la_data,$la_columna,'',$la_config);

				break;
		}

	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	function uf_print_pie_partida($ad_tipper,$as_nomper01,$as_nomper02,$as_nomper03,$ad_total_per1,$ad_total_per2,$ad_total_per3,$ad_total_disponible,$ad_total_comprometido,$ad_total_ajuste,$ad_total_modificaciones,
	                               $ad_total_precompromiso,$ad_total_libPrecompromiso,&$io_pdf,$as_titulo)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_partida
		//		    Acess : private
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el total por partidas
		//	   Creado Por: Victor Mendoza
		// Fecha Creación: 02/06/2009
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		switch($ad_tipper)
		{
			case 1:
				$la_datat=array(array('name'=>'___________________________________________________________________________________________________________________________________________________________________________________________________'));
				$la_columna=array('name'=>'');
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'fontSize' => 9, // Tamaño de Letras
								 'showLines'=>0, // Mostrar Líneas
								 'shaded'=>0, // Sombra entre líneas
								 'xOrientation'=>'center', // Orientación de la tabla
								 'xPos'=>500, // Orientación de la tabla
								 'width'=>990); // Ancho Máximo de la tabla
				$io_pdf->ezTable($la_datat,$la_columna,'',$la_config);

				$la_data[]=array('codigo'=>'',
								 'descripcion'=>'<b>'.$as_titulo.'</b>',
								 'disponible'=>$ad_total_disponible,
								 'periodo01'=>'<b>'.$ad_total_per1.'</b>',
				                 'comprometido'=>$ad_total_comprometido,'ajuste'=>$ad_total_ajuste,
				                 'modificaciones'=>$ad_total_modificaciones,'precompromiso'=>$ad_total_precompromiso,'libprecompromiso'=>$ad_total_libPrecompromiso,'disponible2'=>$ad_total_disponible);

				$la_columnas=array('codigo'=>' ','descripcion'=>'',
									'disponible'=>'',
									'periodo01'=>'',
									'comprometido'=>'','ajuste'=>'',
				                    'modificaciones'=>'','precompromiso'=>'','libprecompromiso'=>'',
									'disponible2'=>'');
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'fontSize' => 8, // Tamaño de Letras
								 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
								 'showLines'=>0, // Mostrar Líneas
								 'shaded'=>0, // Sombra entre líneas
								 'colGap'=>0.5, // separacion entre tablas
								 'width'=>990, // Ancho de la tabla
								 'maxWidth'=>990, // Ancho Máximo de la tabla
								 'xOrientation'=>'center', // Orientación de la tabla
								 'xPos'=>510, // Orientación de la tabla
								 'cols'=>array('codigo'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la
								               'descripcion'=>array('justification'=>'left','width'=>150), // Justificación y ancho de la
								 			   'disponible'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la
								 			   'periodo01'=>array('justification'=>'right','width'=>150), // Justificación y ancho de la columna
								 			   'comprometido'=>array('justification'=>'right','width'=>70), // Justificación
								 			   'ajuste'=>array('justification'=>'right','width'=>70), // Justificación y ancho
								 			   'modificaciones'=>array('justification'=>'right','width'=>70), // Justificación y ancho
								 			   'precompromiso'=>array('justification'=>'right','width'=>125), // Justificación y ancho
								 			   'libprecompromiso'=>array('justification'=>'right','width'=>120), // Justificación y ancho
								 			   'disponible2'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la

				$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
				$la_data=array(array('name'=>''));
				$la_columna=array('name'=>'');
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'showLines'=>0, // Mostrar Líneas
								 'shaded'=>0, // Sombra entre líneas
								 'width'=>990, // Ancho Máximo de la tabla
								 'xOrientation'=>'center'); // Orientación de la tabla
				$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
				break;

			case 2:
				$la_datat=array(array('name'=>'___________________________________________________________________________________________________________________________________________________________________________________________________'));
				$la_columna=array('name'=>'');
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'fontSize' => 9, // Tamaño de Letras
								 'showLines'=>0, // Mostrar Líneas
								 'shaded'=>0, // Sombra entre líneas
								 'xOrientation'=>'center', // Orientación de la tabla
								 'xPos'=>500, // Orientación de la tabla
								 'width'=>990); // Ancho Máximo de la tabla
				$io_pdf->ezTable($la_datat,$la_columna,'',$la_config);

				$la_data[]=array('codigo'=>'',
									'descripcion'=>'<b>'.$as_titulo.'</b>',
									'disponible'=>$ad_total_disponible,
									'periodo01'=>'<b>'.$ad_total_per1.'</b>',
									'periodo02'=>'<b>'.$ad_total_per2.'</b>',
				                 	'comprometido'=>$ad_total_comprometido,'ajuste'=>$ad_total_ajuste,
				                 	'modificaciones'=>$ad_total_modificaciones,'precompromiso'=>$ad_total_precompromiso,'libprecompromiso'=>$ad_total_libPrecompromiso,'disponible2'=>$ad_total_disponible);

				$la_columnas=array('codigo'=>' ','descripcion'=>'','disponible'=>'',
								   	'periodo01'=>'<b>'.$as_nomper01.'</b>',
								   	'periodo02'=>'<b>'.$as_nomper02.'</b>',
									'comprometido'=>'','ajuste'=>'',
				                   	'modificaciones'=>'','precompromiso'=>'','libprecompromiso'=>'','disponible2'=>'');

				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'fontSize' => 8, // Tamaño de Letras
								 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
								 'showLines'=>0, // Mostrar Líneas
								 'shaded'=>0, // Sombra entre líneas
								 'colGap'=>0.5, // separacion entre tablas
								 'width'=>990, // Ancho de la tabla
								 'maxWidth'=>990, // Ancho Máximo de la tabla
								 //'xOrientation'=>'center', // Orientación de la tabla
								 'xPos'=>505, // Orientación de la tabla
								 'cols'=>array('codigo'=>array('justification'=>'left','width'=>85), // Justificación y ancho de la
								               'descripcion'=>array('justification'=>'left','width'=>110), // Justificación y ancho de la
								 			   'disponible'=>array('justification'=>'right','width'=>110), // Justificación y ancho de la
											   'periodo01'=>array('justification'=>'right','width'=>105), // Justificación y ancho de la columna
											   'periodo02'=>array('justification'=>'right','width'=>105), // Justificación y ancho de la
											   'comprometido'=>array('justification'=>'right','width'=>70), // Justificación
								 			   'ajuste'=>array('justification'=>'right','width'=>70), // Justificación y ancho
								 			   'modificaciones'=>array('justification'=>'right','width'=>70), // Justificación y ancho
								 			   'precompromiso'=>array('justification'=>'right','width'=>100), // Justificación y ancho
								 			   'libprecompromiso'=>array('justification'=>'right','width'=>100), // Justificación y ancho
								 			   'disponible2'=>array('justification'=>'right','width'=>75))); // Justificación y ancho de la

				$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
				$la_data=array(array('name'=>''));
				$la_columna=array('name'=>'');
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'showLines'=>0, // Mostrar Líneas
								 'shaded'=>0, // Sombra entre líneas
								 'width'=>990, // Ancho Máximo de la tabla
								 'xOrientation'=>'center'); // Orientación de la tabla
				$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
				break;

			case 3:
				$la_datat=array(array('name'=>'___________________________________________________________________________________________________________________________________________________________________________________________________'));
				$la_columna=array('name'=>'');
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'fontSize' => 9, // Tamaño de Letras
								 'showLines'=>0, // Mostrar Líneas
								 'shaded'=>0, // Sombra entre líneas
								 'xOrientation'=>'center', // Orientación de la tabla
								 'xPos'=>500, // Orientación de la tabla
								 'width'=>990); // Ancho Máximo de la tabla
				$io_pdf->ezTable($la_datat,$la_columna,'',$la_config);

				$la_datatot[1]=array('codigo'=>'','descripcion'=>'<b>'.$as_titulo.'</b>',
									 'disponible'=>$ad_total_disponible,
									 'periodo01'=>'<b>'.$ad_total_per1.'</b>',
									 'periodo02'=>'<b>'.$ad_total_per2.'</b>',
									 'periodo03'=>'<b>'.$ad_total_per3.'</b>',
				                 	 'comprometido'=>$ad_total_comprometido,'ajuste'=>$ad_total_ajuste,
				                 	 'modificaciones'=>$ad_total_modificaciones,'precompromiso'=>$ad_total_precompromiso,
									 'libprecompromiso'=>$ad_total_libPrecompromiso,
									 'disponible2'=>$ad_total_disponible);

				$la_columnas=array('codigo'=>' ','descripcion'=>'','disponible'=>'',
								   	'periodo01'=>'',
								   	'periodo02'=>'',
								   	'periodo03'=>'',
									'comprometido'=>'','ajuste'=>'',
				                   	'modificaciones'=>'','precompromiso'=>'','libprecompromiso'=>'','disponible2'=>'');
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'fontSize' => 8, // Tamaño de Letras
								 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
								 'showLines'=>0, // Mostrar Líneas
								 'shaded'=>0, // Sombra entre líneas
								 'colGap'=>0.5, // separacion entre tablas
								 'width'=>990, // Ancho de la tabla
								 'maxWidth'=>990, // Ancho Máximo de la tabla
								 //'xOrientation'=>'center', // Orientación de la tabla
								 'xPos'=>505, // Orientación de la tabla
								 'cols'=>array('codigo'=>array('justification'=>'left','width'=>90), // Justificación y ancho de la
								               'descripcion'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la
								 			   'disponible'=>array('justification'=>'right','width'=>90), // Justificación y ancho de la
											   'periodo01'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
											   'periodo02'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la
											   'periodo03'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
								 			   'comprometido'=>array('justification'=>'right','width'=>70), // Justificación
								 			   'ajuste'=>array('justification'=>'right','width'=>70), // Justificación y ancho
								 			   'modificaciones'=>array('justification'=>'right','width'=>70), // Justificación y ancho
								 			   'precompromiso'=>array('justification'=>'right','width'=>100), // Justificación y ancho
								 			   'libprecompromiso'=>array('justification'=>'right','width'=>100), // Justificación y ancho
								 			   'disponible2'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la

				$io_pdf->ezTable($la_datatot,$la_columnas,'',$la_config);
/*				$la_data=array(array('name'=>''));
				$la_columna=array('name'=>'');
				$la_config=array('showHeadings'=>0, // Mostrar encabezados
								 'showLines'=>0, // Mostrar Líneas
								 'shaded'=>0, // Sombra entre líneas
								 'width'=>990, // Ancho Máximo de la tabla
								 'xOrientation'=>'center'); // Orientación de la tabla
				$io_pdf->ezTable($la_data,$la_columna,'',$la_config);*/
		unset($la_datatot);
		unset($la_columnas);
		unset($la_config);


		/*$io_pdf->ezTable($la_data_tot,$la_columnas,'',$la_config);
		unset($la_data_tot);
		unset($la_columnas);
		unset($la_config);*/


				break;
		}

	}// end function uf_print_pie_partida

	//--------------------------------------------------------------------------------------------------------------------------------


	function uf_print_cabecera_detalle($io_encabezado,$ai_estilo,$as_nomper01,$as_nomper02,$as_nomper03,$ad_fecha,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing.Yozelin Barragán
	    // Fecha Creación: 12/09/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();

		switch($ai_estilo)
		{
		 case 1:
		        $la_config=array('showHeadings'=>0, // Mostrar encabezados
					 'fontSize' => 7, // Tamaño de Letras
					 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
					 'showLines'=>1, // Mostrar Líneas
					 'shaded'=>0, // Sombra entre líneas
					 'colGap'=>0.5, // separacion entre tablas
					 'width'=>990, // Ancho de la tabla
					 'maxWidth'=>990, // Ancho Máximo de la tabla
					 'xOrientation'=>'center', // Orientación de la tabla
					 'xPos'=>502, // Orientación de la tabla
					 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la
								   'denominacion'=>array('justification'=>'center','width'=>150), // Justificación y ancho
								   'disponact'=>array('justification'=>'center','width'=>70), // Justificación y ancho
								   'periodo01'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna								   'totcom'=>array('justification'=>'right','width'=>70),// Justificación y ancho
								   'ajucom'=>array('justification'=>'center','width'=>70),
								   'totcom'=>array('justification'=>'center','width'=>70),
								   'modpres'=>array('justification'=>'center','width'=>70),
								   'precom'=>array('justification'=>'center','width'=>125),
								   'libprecom'=>array('justification'=>'center','width'=>125),
								   'disponible'=>array('justification'=>'center','width'=>70))); // Justificación y ancho

				$la_columnas=array('cuenta'=>'<b>CODIGO</b>',
								   'denominacion'=>'<b>DENOMINACION</b>',
								   'disponant'=>'<b>DISPONIBILIDAD</b>',
								   'periodo01'=>'<b>'.$as_nomper01.'</b>',
								   'totcom'=>'<b>TOTAL COMPROMETIDO</b>',
								   'ajucom'=>'<b>AJUSTE/COMP</b>',
								   'modpres'=>'<b>MOD. PRES</b>',
								   'precom'=>'<b>PRECOMPROMISOS</b>',
								   'libprecom'=>'<b>LIBER./PRECOMPROMISO</b>',
								   'disponible'=>'<b>DISPONIBILIDAD AL: </b>');
				$la_data=array(array('cuenta'=>'<b>CODIGO</b>',
									 'denominacion'=>'<b>DENOMINACION</b>',
									 'disponant'=>'<b>DISPONIBILIDAD</b>',
									 'periodo01'=>'<b>'.$as_nomper01.'</b>',
									 'totcom'=>'<b>TOTAL COMPROMETIDO</b>',
									 'ajucom'=>'<b>AJUSTE/COMP</b>',
									 'modpres'=>'<b>MOD. PRES</b>',
									 'precom'=>'<b>PRECOMPROMISOS</b>',
									 'libprecom'=>'<b>LIBER./PRECOMPROMISO</b>',
									 'disponible'=>'<b> DISPONIBLE AL: '.$ad_fecha.'</b>'));
		        break;

		 case 2:
		        $la_config=array('showHeadings'=>0, // Mostrar encabezados
					 'fontSize' => 7, // Tamaño de Letras
					 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
					 'showLines'=>1, // Mostrar Líneas
					 'shaded'=>0, // Sombra entre líneas
					 'colGap'=>0.5, // separacion entre tablas
					 'width'=>990, // Ancho de la tabla
					 'maxWidth'=>990, // Ancho Máximo de la tabla
					 'xOrientation'=>'center', // Orientación de la tabla
					 'xPos'=>502, // Orientación de la tabla
					 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la
								   'denominacion'=>array('justification'=>'center','width'=>150), // Justificación y ancho
								   'disponact'=>array('justification'=>'center','width'=>70), // Justificación y ancho
								   'periodo01'=>array('justification'=>'center','width'=>105), // Justificación y ancho de la columna
								   'periodo02'=>array('justification'=>'center','width'=>105), // Justificación y ancho de la
								   'totcom'=>array('justification'=>'center','width'=>70),// Justificación y ancho
								   'ajucom'=>array('justification'=>'center','width'=>70),
								   'modpres'=>array('justification'=>'center','width'=>70),
								   'precom'=>array('justification'=>'center','width'=>100),
								   'libprecom'=>array('justification'=>'center','width'=>100),
								   'disponible'=>array('justification'=>'center','width'=>70))); // Justificación y ancho

				$la_columnas=array('cuenta'=>'<b>CODIGO</b>',
								   'denominacion'=>'<b>DENOMINACION</b>',
								   'disponact'=>'<b>DISPONIBILIDAD</b>',
								   'periodo01'=>'<b>'.$as_nomper01.'</b>',
								   'periodo02'=>'<b>'.$as_nomper02.'</b>',
								   'totcom'=>'<b>TOTAL COMPROMETIDO</b>',
								   'ajucom'=>'<b>AJUSTE/COMP</b>',
								   'modpres'=>'<b>MOD. PRES</b>',
								   'precom'=>'<b>PRECOMPROMISOS</b>',
								   'libprecom'=>'<b>LIBER./PRECOMPROMISO</b>',
								   'disponible'=>'<b>DISPONIBILIDAD AL: </b>');
				$la_data=array(array('cuenta'=>'<b>CODIGO</b>',
									 'denominacion'=>'<b>DENOMINACION</b>',
									 'disponact'=>'<b>DISPONIBILIDAD</b>',
									 'periodo01'=>'<b>'.$as_nomper01.'</b>',
									 'periodo02'=>'<b>'.$as_nomper02.'</b>',
									 'totcom'=>'<b>TOTAL COMPROMETIDO</b>',
									 'ajucom'=>'<b>AJUSTE/COMP</b>',
									 'modpres'=>'<b>MOD. PRES</b>',
									 'precom'=>'<b>PRECOMPROMISOS</b>',
									 'libprecom'=>'<b>LIBER./PRECOMPROMISO</b>',
									 'disponible'=>'<b> DISPONIBLE AL: '.$ad_fecha.'</b>'));
		       	break;

		 case 3:
		        $la_config=array('showHeadings'=>0, // Mostrar encabezados
					 'fontSize' => 7, // Tamaño de Letras
					 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
					 'showLines'=>1, // Mostrar Líneas
					 'shaded'=>0, // Sombra entre líneas
					 'colGap'=>0.5, // separacion entre tablas
					 'width'=>990, // Ancho de la tabla
					 'maxWidth'=>990, // Ancho Máximo de la tabla
					 'xOrientation'=>'center', // Orientación de la tabla
					 'xPos'=>502, // Orientación de la tabla
					 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la
								   'denominacion'=>array('justification'=>'center','width'=>150), // Justificación y ancho
								   'disponact'=>array('justification'=>'center','width'=>70), // Justificación y ancho
								   'periodo01'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
								   'periodo02'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la
								   'periodo03'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
								   'totcom'=>array('justification'=>'center','width'=>70),// Justificación y ancho
								   'ajucom'=>array('justification'=>'center','width'=>70),
								   'modpres'=>array('justification'=>'center','width'=>70),
								   'precom'=>array('justification'=>'center','width'=>100),
								   'libprecom'=>array('justification'=>'center','width'=>100),
								   'disponible'=>array('justification'=>'center','width'=>70))); // Justificación y ancho

				$la_columnas=array('cuenta'=>'<b>CODIGO</b>',
								   'denominacion'=>'<b>DENOMINACION</b>',
								   'disponact'=>'<b>DISP. ANT</b>',
								   'periodo01'=>'<b>'.$as_nomper01.'</b>',
								   'periodo02'=>'<b>'.$as_nomper02.'</b>',
								   'periodo03'=>'<b>'.$as_nomper03.'</b>',
								   'totcom'=>'<b>TOTAL COMPROMETIDO</b>',
								   'ajucom'=>'<b>AJUSTE/COMP</b>',
								   'modpres'=>'<b>MOD. PRES</b>',
								   'precom'=>'<b>PRECOMPROMISOS</b>',
								   'libprecom'=>'<b>LIBER./PRECOMPROMISO</b>',
								   'disponible'=>'<b>DISPONIBILIDAD AL: </b>');

				$la_data=array(array('cuenta'=>'<b>CODIGO</b>',
									 'denominacion'=>'<b>DENOMINACION</b>',
									 'disponact'=>'<b>DISP. ANTERIOR</b>',
									 'periodo01'=>'<b>'.$as_nomper01.'</b>',
									 'periodo02'=>'<b>'.$as_nomper02.'</b>',
									 'periodo03'=>'<b>'.$as_nomper03.'</b>',
									 'totcom'=>'<b>TOTAL COMPROMETIDO</b>',
									 'ajucom'=>'<b>AJUSTE/COMP</b>',
									 'modpres'=>'<b>MOD. PRES</b>',
									 'precom'=>'<b>PRECOMPROMISOS</b>',
									 'libprecom'=>'<b>LIBER./PRECOMPROMISO</b>',
									 'disponible'=>'<b> DISPONIBLE AL: '.$ad_fecha.'</b>'));
		        break;

		}

		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_cabecera_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,$ai_estilo,$as_nomper01,$as_nomper02,$as_nomper03,&$io_pdf)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_detalle
	//		    Acess: private
	//	    Arguments: la_data // arreglo de información
	//	   			   io_pdf // Objeto PDF
	//    Description: función que imprime el detalle
	//	   Creado Por: Ing.Yozelin Barragán
	// Fecha Creación: 12/09/2006
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    switch($ai_estilo)
	{
	 case 1:
	        $la_config=array('showHeadings'=>0, // Mostrar encabezados
					 'fontSize' => 7, // Tamaño de Letras
					 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
					 'showLines'=>0, // Mostrar Líneas
					 'shaded'=>0, // Sombra entre líneas
					 'colGap'=>0.5, // separacion entre tablas
					 'width'=>990, // Ancho de la tabla
					 'maxWidth'=>990, // Ancho Máximo de la tabla
					 'xOrientation'=>'center', // Orientación de la tabla
					 'xPos'=>502, // Orientación de la tabla
					 'cols'=>array('cuenta'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la
								   'denominacion'=>array('justification'=>'left','width'=>150), // Justificación y ancho
								   'disponact'=>array('justification'=>'right','width'=>70), // Justificación y ancho
								   'periodo01'=>array('justification'=>'right','width'=>150), // Justificación y ancho de la columna
								   'totcom'=>array('justification'=>'right','width'=>70),// Justificación y ancho
								   'ajucom'=>array('justification'=>'right','width'=>70),
								   'modpres'=>array('justification'=>'right','width'=>70),
								   'precom'=>array('justification'=>'right','width'=>125),
								   'libprecom'=>array('justification'=>'right','width'=>125),
								   'disponible'=>array('justification'=>'right','width'=>70))); // Justificación y ancho

			$la_columnas=array('cuenta'=>'<b>CODIGO</b>',
							   'denominacion'=>'<b>DENOMINACION</b>',
							   'disponact'=>'<b>DISP. ANT</b>',
							   'periodo01'=>'<b>'.$as_nomper01.'</b>',
							   'totcom'=>'<b>TOTAL COMPROMETIDO</b>',
							   'ajucom'=>'<b>AJUSTE/COMP</b>',
							   'modpres'=>'<b>MOD. PRES</b>',
							   'precom'=>'<b>PRECOMPROMISOS</b>',
							   'libprecom'=>'<b>LIBER./PRECOMPROMISO</b>',
							   'disponible'=>'<b>DISPONIBILIDAD AL: </b>');
	        break;

	 case 2:
	        $la_config=array('showHeadings'=>0, // Mostrar encabezados
					 'fontSize' => 7, // Tamaño de Letras
					 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
					 'showLines'=>0, // Mostrar Líneas
					 'shaded'=>0, // Sombra entre líneas
					 'colGap'=>0.5, // separacion entre tablas
					 'width'=>990, // Ancho de la tabla
					 'maxWidth'=>990, // Ancho Máximo de la tabla
					 'xOrientation'=>'center', // Orientación de la tabla
					 'xPos'=>502, // Orientación de la tabla
					 'cols'=>array('cuenta'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la
								   'denominacion'=>array('justification'=>'left','width'=>150), // Justificación y ancho
								   'disponact'=>array('justification'=>'right','width'=>70), // Justificación y ancho
								   'periodo01'=>array('justification'=>'right','width'=>105), // Justificación y ancho de la columna
								   'periodo02'=>array('justification'=>'right','width'=>105), // Justificación y ancho de la
								   'totcom'=>array('justification'=>'right','width'=>70),// Justificación y ancho
								   'ajucom'=>array('justification'=>'right','width'=>70),
								   'modpres'=>array('justification'=>'right','width'=>70),
								   'precom'=>array('justification'=>'right','width'=>100),
								   'libprecom'=>array('justification'=>'right','width'=>100),
								   'disponible'=>array('justification'=>'right','width'=>70))); // Justificación y ancho

			$la_columnas=array('cuenta'=>'<b>CODIGO</b>',
							   'denominacion'=>'<b>DENOMINACION</b>',
							   'disponact'=>'<b>DISP. ANT</b>',
							   'periodo01'=>'<b>'.$as_nomper01.'</b>',
							   'periodo02'=>'<b>'.$as_nomper02.'</b>',
							   'totcom'=>'<b>TOTAL COMPROMETIDO</b>',
							   'ajucom'=>'<b>AJUSTE/COMP</b>',
							   'modpres'=>'<b>MOD. PRES</b>',
							   'precom'=>'<b>PRECOMPROMISOS</b>',
							   'libprecom'=>'<b>LIBER./PRECOMPROMISO</b>',
							   'disponible'=>'<b>DISPONIBILIDAD AL: </b>');
	        break;

	 case 3:
	 		$la_config=array('showHeadings'=>0, // Mostrar encabezados
					 'fontSize' => 7, // Tamaño de Letras
					 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
					 'showLines'=>0, // Mostrar Líneas
					 'shaded'=>0, // Sombra entre líneas
					 'colGap'=>0.5, // separacion entre tablas
					 'width'=>990, // Ancho de la tabla
					 'maxWidth'=>990, // Ancho Máximo de la tabla
					 'xOrientation'=>'center', // Orientación de la tabla
					 'xPos'=>502, // Orientación de la tabla
					 'cols'=>array('cuenta'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la
								   'denominacion'=>array('justification'=>'left','width'=>150), // Justificación y ancho
								   'disponact'=>array('justification'=>'right','width'=>70), // Justificación y ancho
								   'periodo01'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
								   'periodo02'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la
								   'periodo03'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
								   'totcom'=>array('justification'=>'right','width'=>70),// Justificación y ancho
								   'ajucom'=>array('justification'=>'right','width'=>70),
								   'modpres'=>array('justification'=>'right','width'=>70),
								   'precom'=>array('justification'=>'right','width'=>100),
								   'libprecom'=>array('justification'=>'right','width'=>100),
								   'disponible'=>array('justification'=>'right','width'=>70))); // Justificación y ancho

			$la_columnas=array('cuenta'=>'<b>CODIGO</b>',
							   'denominacion'=>'<b>DENOMINACION</b>',
							   'disponact'=>'<b>DISP. ANT</b>',
							   'periodo01'=>'<b>'.$as_nomper01.'</b>',
							   'periodo02'=>'<b>'.$as_nomper02.'</b>',
							   'periodo03'=>'<b>'.$as_nomper03.'</b>',
							   'totcom'=>'<b>TOTAL COMPROMETIDO</b>',
							   'ajucom'=>'<b>AJUSTE/COMP</b>',
							   'modpres'=>'<b>MOD. PRES</b>',
							   'precom'=>'<b>PRECOMPROMISOS</b>',
							   'libprecom'=>'<b>LIBER./PRECOMPROMISO</b>',
							   'disponible'=>'<b>DISPONIBILIDAD AL: </b>');
	        break;

	}

	$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("sigesp_spg_funciones_reportes.php");
		$io_function_report = new sigesp_spg_funciones_reportes();
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
//-----------------------------------------------------------------------------------------------------------------------------

		require_once("sigesp_spg_reportes_class.php");
		$io_report = new sigesp_spg_reportes_class();
		$li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$li_redconmon=$_SESSION["la_empresa"]["redconmon"];
//------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------  Parámetros para Filtar el Reporte  ------------------------------------
		$li_estmodest    = $_SESSION["la_empresa"]["estmodest"];
		$ldt_periodo     = $_SESSION["la_empresa"]["periodo"];
		$li_ano          = substr($ldt_periodo,0,4);
		$ls_codestpro1   = $_GET["codestpro1"];
		$ls_codestpro2   = $_GET["codestpro2"];
		$ls_codestpro3   = $_GET["codestpro3"];
		$ls_codestpro1h  = $_GET["codestpro1h"];
		$ls_codestpro2h  = $_GET["codestpro2h"];
		$ls_codestpro3h  = $_GET["codestpro3h"];
	    $ls_estclades    = $_GET["estclades"];
		$ls_estclahas    = $_GET["estclahas"];
		$ld_tipper       = $_GET["tipper"];
	    $ld_periodo      = $_GET["periodo"];
		$ls_cuentades    = $_GET["txtcuentades"];
		$ls_cuentahas    = $_GET["txtcuentahas"];
		$ls_text_periodo = $_GET["tperiodo"];


		switch($ld_tipper)
		{
		 case 1:
		       $ld_per01 = intval($ld_periodo);
			   $ld_per02 = "";
			   $ld_per03 = "";
			   $ls_desper = "MENSUAL";
		       $ld_fecfinrep=$io_fecha->uf_last_day($ld_periodo,$li_ano);
		       break;

		 case 2:
		      $ld_per01 = intval(substr($ld_periodo,0,2));
			  $ld_per02 = intval(substr($ld_periodo,2,2));
			  $ls_desper = "BIMENSUAL";
			  $ld_fecfinrep=$io_fecha->uf_last_day(substr($ld_periodo,2,2),$li_ano);
			  $ld_per03 = "";
		      break;

		 case 3:
		      $ld_per01 = intval(substr($ld_periodo,0,2));
			  $ld_per02 = intval(substr($ld_periodo,2,2));
			  $ld_per03 = intval(substr($ld_periodo,4,2));
			  $ls_desper = "TRIMESTRAL";
			  $ld_fecfinrep=$io_fecha->uf_last_day(substr($ld_periodo,4,2),$li_ano);
		      break;
		}
		if($li_estmodest==1)
		{
			$ls_codestpro4  =  "0000000000000000000000000";
			$ls_codestpro5  =  "0000000000000000000000000";
			$ls_codestpro4h =  "0000000000000000000000000";
			$ls_codestpro5h =  "0000000000000000000000000";
		}
		elseif($li_estmodest==2)
		{
			$ls_codestpro4  = $_GET["codestpro4"];
			$ls_codestpro5  = $_GET["codestpro5"];
			$ls_codestpro4h = $_GET["codestpro4h"];
			$ls_codestpro5h = $_GET["codestpro5h"];
	    }

	 /////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_desc_event="Solicitud de Reporte Ejecucion Financiera del Presupuesto";
	 $io_function_report->uf_load_seguridad_reporte("SPG","sigesp_spg_r_resumen_ejecucion_financiera.php",$ls_desc_event);
	////////////////////////////////         SEGURIDAD               ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//----------------------------------------------------  Parámetros del encabezado  ----------------------------------------------
		$ls_titulo="<b> EJECUCION FINANCIERA DEL PRESUPUESTO DE GASTO ".$ls_desper." AL ".$ld_fecfinrep."</b> ";
//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )
	$ls_codestpro1  = $io_funciones->uf_cerosizquierda($ls_codestpro1,25);
	$ls_codestpro2  = $io_funciones->uf_cerosizquierda($ls_codestpro2,25);
	$ls_codestpro3  = $io_funciones->uf_cerosizquierda($ls_codestpro3,25);
	$ls_codestpro4  = $io_funciones->uf_cerosizquierda($ls_codestpro4,25);
	$ls_codestpro5  = $io_funciones->uf_cerosizquierda($ls_codestpro5,25);

	$ls_codestpro1h  = $io_funciones->uf_cerosizquierda($ls_codestpro1h,25);
	$ls_codestpro2h  = $io_funciones->uf_cerosizquierda($ls_codestpro2h,25);
	$ls_codestpro3h  = $io_funciones->uf_cerosizquierda($ls_codestpro3h,25);
	$ls_codestpro4h  = $io_funciones->uf_cerosizquierda($ls_codestpro4h,25);
	$ls_codestpro5h  = $io_funciones->uf_cerosizquierda($ls_codestpro5h,25);

    $lb_valido=$io_report->uf_spg_reportes_ejecucion_financiera_presupuesto($ls_codestpro1,$ls_codestpro2,
                                                                            $ls_codestpro3,$ls_codestpro4,
                                                                            $ls_codestpro5,$ls_estclades,
																			$ls_codestpro1h,$ls_codestpro2h,
                                                                            $ls_codestpro3h,$ls_codestpro4h,
                                                                            $ls_codestpro5h,$ls_estclahas,
																			$ld_per01,$ld_per02,$ld_per03,
																			$ls_cuentades, $ls_cuentahas);

	 if($lb_valido==false) // Existe algún error ó no hay registros
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');");
		print(" close();");
		print("</script>");
	 }
	 else // Se Transfiere la data a otro arreglo para incluir subtotales por partida
	 {

	 	$io_report->dts_reporte->group_noorder("programatica");
		$li_tot=$io_report->dts_reporte->getRowCount("spg_cuenta");
		$ld_totalp_disponible		= 0;
		$ld_totalp_comprometido 	= 0;
		$ld_totalp_ajuste 			= 0;
		$ld_totalp_modificaciones 	= 0;
		$ld_totalp_precompromiso	= 0;
		$ld_totalp_libPrecompromiso	= 0;
		$ld_totalp_per1				= 0;
		$ld_totalp_per2				= 0;
		$ld_totalp_per3				= 0;

$suma = 0;

	 	$i=1;

	 		for($z=1;$z<=$li_tot;$z++)
	 		{
	 			$li_tmp						= ($z+1);
				$ls_programatica			= $io_report->dts_reporte->data["programatica"][$z];
				$ls_spg_cuenta				= trim($io_report->dts_reporte->data["spg_cuenta"][$z]);
				$io_function_report->uf_get_spg_cuenta($ls_spg_cuenta,$ls_partida,$ls_generica,$ls_especifica,$ls_subesp);
				$ls_status                 	= $io_report->dts_reporte->data["status"][$z];
				$ls_denominacion        	= trim($io_report->dts_reporte->data["denominacion"][$z]);
				$ld_dispact              	= $io_report->dts_reporte->data["dispact"][$z];
				$ld_disant              	= $io_report->dts_reporte->data["dispant"][$z];
				$ld_periodo01         		= $io_report->dts_reporte->data["periodo01"][$z];
				$ld_periodo02        		= $io_report->dts_reporte->data["periodo02"][$z];
				$ld_periodo03       		= $io_report->dts_reporte->data["periodo03"][$z];
				$ld_modpres              	= $io_report->dts_reporte->data["modpres"][$z];
				$ld_precomprometido     	= $io_report->dts_reporte->data["precomprometido"][$z];
				$ld_libprecomprometido  	= $io_report->dts_reporte->data["libprecomprometido"][$z];
				$ld_libcomprometido     	= $io_report->dts_reporte->data["libcomprometido"][$z];
				$ld_comprometido        	= $ld_periodo01 + $ld_periodo02 + $ld_periodo03;
				$ld_disponible          	= $ld_disant - $ld_comprometido + $ld_modpres - $ld_precomprometido;
				$ls_estcla					= $io_report->dts_reporte->data["estcla"][$z];

			    if ($z<$li_tot)
			    {
					$ls_programatica_next=$io_report->dts_reporte->data["programatica"][$li_tmp];
					$ls_partida_next=substr($io_report->dts_reporte->data["spg_cuenta"][$li_tmp],0,3);
			    }
			    elseif($z=$li_tot)
			    {
					$ls_programatica_next='no_next';
					$ls_partida_next='no_next';
			    }

			    if ($ls_partida!=$ls_partida_next)
			    {
			    	$lb_partida = true;
			    }
			    else
			    {
			    	$lb_partida = false;
			    }

				switch($ld_tipper)
		 		{
		  			case 1:
				         $la_data_temp[$i]=array('programatica'=>$ls_programatica,
											'dispact'=>$ld_dispact,
											'dispant'=>$ld_disant,
											'precomprometido'=>$ld_precomprometido,
											'libprecomprometido'=>$ld_libprecomprometido,
											'libcomprometido'=>$ld_libcomprometido,
											'status'=>$ls_status,
											'estcla'=>$ls_estcla,
											'cuenta'=>$ls_spg_cuenta,
										    'denominacion'=>$ls_denominacion,
										    'disponact'=>$ld_dispact,
										    'periodo01'=>$ld_periodo01,
										    'periodo02'=>$ld_periodo02,
										    'periodo03'=>$ld_periodo03,
										    'totcom'=>$ld_comprometido,
										    'ajucom'=>$ld_libcomprometido,
										    'modpres'=>$ld_modpres,
										    'precom'=>$ld_precomprometido,
										    'libprecom'=>$ld_libprecomprometido,
										    'disponible'=>$ld_disponible,
										    'lprintsub'=>false);
		         	break;

		  			case 2:
		         		$la_data_temp[$i]=array('programatica'=>$ls_programatica,
		         							'dispact'=>$ld_dispact,
											'dispant'=>$ld_disant,
											'precomprometido'=>$ld_precomprometido,
											'libprecomprometido'=>$ld_libprecomprometido,
											'libcomprometido'=>$ld_libcomprometido,
											'status'=>$ls_status,
											'estcla'=>$ls_estcla,
											'cuenta'=>$ls_spg_cuenta,
										    'denominacion'=>$ls_denominacion,
										    'disponact'=>$ld_dispact,
										    'periodo01'=>$ld_periodo01,
										    'periodo02'=>$ld_periodo02,
										    'periodo03'=>$ld_periodo03,
										    'totcom'=>$ld_comprometido,
										    'ajucom'=>$ld_libcomprometido,
										    'modpres'=>$ld_modpres,
										    'precom'=>$ld_precomprometido,
										    'libprecom'=>$ld_libprecomprometido,
										    'disponible'=>$ld_disponible,
										    'lprintsub'=>false);
		         	break;

		  			case 3:
		         		$la_data_temp[$i]=array('programatica'=>$ls_programatica,
		         							'dispact'=>$ld_dispact,
											'dispant'=>$ld_disant,
											'precomprometido'=>$ld_precomprometido,
											'libprecomprometido'=>$ld_libprecomprometido,
											'libcomprometido'=>$ld_libcomprometido,
											'status'=>$ls_status,
											'estcla'=>$ls_estcla,
											'cuenta'=>$ls_spg_cuenta,
										    'denominacion'=>$ls_denominacion,
										    'disponact'=>$ld_dispact,
										    'periodo01'=>$ld_periodo01,
										    'periodo02'=>$ld_periodo02,
										    'periodo03'=>$ld_periodo03,
										    'totcom'=>$ld_comprometido,
										    'ajucom'=>$ld_libcomprometido,
										    'modpres'=>$ld_modpres,
										    'precom'=>$ld_precomprometido,
										    'libprecom'=>$ld_libprecomprometido,
										    'disponible'=>$ld_disponible,
										    'lprintsub'=>false);
		         	break;
				}// switch

				if($ls_status=="C")
				{

					//$ls_partida_aux=$ls_partida;
					$ld_totalp_disponible		= $ld_totalp_disponible + $ld_disponible;
					$ld_totalp_comprometido 	= $ld_totalp_comprometido + $ld_comprometido;
					$ld_totalp_ajuste 			= $ld_totalp_ajuste + $ld_libcomprometido;
					$ld_totalp_modificaciones 	= $ld_totalp_modificaciones + $ld_modpres;
					$ld_totalp_precompromiso	= $ld_totalp_precompromiso + $ld_precomprometido;
					$ld_totalp_libPrecompromiso	= $ld_totalp_libPrecompromiso + $ld_libprecomprometido;
					$ld_totalp_per1				= $ld_totalp_per1 + $ld_periodo01;
					$ld_totalp_per2				= $ld_totalp_per2 + $ld_periodo02;
					$ld_totalp_per3				= $ld_totalp_per3 + $ld_periodo03;

				}



				if ($ls_partida!=$ls_partida_next)
				{

					$i++;
					switch($ld_tipper)
			 		{
			  			case 1:
					         $la_data_temp[$i]=array('programatica'=>$ls_programatica,
			         							'dispact'=>$ld_dispact,
												'dispant'=>$ld_disant,
												'precomprometido'=>$ld_precomprometido,
												'libprecomprometido'=>$ld_libprecomprometido,
												'libcomprometido'=>$ld_libcomprometido,
												'status'=>$ls_status,
												'estcla'=>$ls_estcla,
												'cuenta'=>'<b>Total Partida</b>',
											    'denominacion'=>'',
											    'disponact'=>$ld_totalp_disponible,
											    'periodo01'=>$ld_totalp_per1,
											    'periodo02'=>$ld_totalp_per2,
											    'periodo03'=>$ld_totalp_per3,
											    'totcom'=>$ld_totalp_comprometido,
											    'ajucom'=>$ld_totalp_ajuste,
											    'modpres'=>$ld_totalp_modificaciones,
											    'precom'=>$ld_totalp_precompromiso,
											    'libprecom'=>$ld_totalp_libPrecompromiso,
											    'disponible'=>$ld_totalp_disponible,
											    'lprintsub'=>true);
			         	break;

			  			case 2:
			         		$la_data_temp[$i]=array('programatica'=>$ls_programatica,
			         							'dispact'=>$ld_dispact,
												'dispant'=>$ld_disant,
												'precomprometido'=>$ld_precomprometido,
												'libprecomprometido'=>$ld_libprecomprometido,
												'libcomprometido'=>$ld_libcomprometido,
												'status'=>$ls_status,
												'estcla'=>$ls_estcla,
												'cuenta'=>'<b>Total Partida</b>',
											    'denominacion'=>'',
											    'disponact'=>$ld_totalp_disponible,
											    'periodo01'=>$ld_totalp_per1,
											    'periodo02'=>$ld_totalp_per2,
											    'periodo03'=>$ld_totalp_per3,
											    'totcom'=>$ld_totalp_comprometido,
											    'ajucom'=>$ld_totalp_ajuste,
											    'modpres'=>$ld_totalp_modificaciones,
											    'precom'=>$ld_totalp_precompromiso,
											    'libprecom'=>$ld_totalp_libPrecompromiso,
											    'disponible'=>$ld_totalp_disponible,
											    'lprintsub'=>true);
			         	break;

			  			case 3:
			         		$la_data_temp[$i]=array('programatica'=>$ls_programatica,
			         							'dispact'=>0,
												'dispant'=>$ld_disant,
												'precomprometido'=>$ld_precomprometido,
												'libprecomprometido'=>$ld_libprecomprometido,
												'libcomprometido'=>$ld_libcomprometido,
												'status'=>$ls_status,
												'estcla'=>$ls_estcla,
												'cuenta'=>'<b>Total Partida</b>',
											    'denominacion'=>'',
											    'disponact'=>$ld_totalp_disponible,
											    'periodo01'=>$ld_totalp_per1,
											    'periodo02'=>$ld_totalp_per2,
											    'periodo03'=>$ld_totalp_per3,
											    'totcom'=>$ld_totalp_comprometido,
											    'ajucom'=>$ld_totalp_ajuste,
											    'modpres'=>$ld_totalp_modificaciones,
											    'precom'=>$ld_totalp_precompromiso,
											    'libprecom'=>$ld_totalp_libPrecompromiso,
											    'disponible'=>$ld_totalp_disponible,
											    'lprintsub'=>true);

			         	break;
					}// switch

					$ld_totalp_disponible		= 0;
					$ld_totalp_comprometido 	= 0;
					$ld_totalp_ajuste 			= 0;
					$ld_totalp_modificaciones 	= 0;
					$ld_totalp_precompromiso	= 0;
					$ld_totalp_libPrecompromiso	= 0;
					$ld_totalp_per1				= 0;
					$ld_totalp_per2				= 0;
					$ld_totalp_per3				= 0;
				}
				else
				{
//					if($ls_status=="C")
//						 {
//							$ld_totalp_disponible			= $ld_totalp_disponible + $ld_disponible;
//							$ld_totalp_comprometido 		= $ld_totalp_comprometido + $ld_comprometido;
//							$ld_totalp_ajuste 				= $ld_totalp_ajuste + $ld_libcomprometido;
//							$ld_totalp_modificaciones 		= $ld_totalp_modificaciones + $ld_modpres;
//							$ld_totalp_precompromiso		= $ld_totalp_precompromiso + $ld_precomprometido;
//							$ld_totalp_libPrecompromiso		= $ld_totalp_libPrecompromiso + $ld_libprecomprometido;
//							$ld_totalp_per1					= $ld_totalp_per1 + $ld_periodo01;
//							$ld_totalp_per2					= $ld_totalp_per2 + $ld_periodo02;
//							$ld_totalp_per3					= $ld_totalp_per3 + $ld_periodo03;
//						}
				}
				$i++;
	 		}//for
			$i++;



/*		for($z=1;$z<=($i-1);$z++)
		{
			print $la_data_temp[$z]["cuenta"].', status: '.$la_data_temp[$z]["status"].'<br>';
			if($la_data_temp[$z]["status"]=='C')
			{
				print $la_data_temp[$z]["cuenta"].', status: '.$la_data_temp[$z]["status"].', acumulado:'.$la_data_temp[$z]["disponible"].'<br>';
			}
		}*/

	    error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(5.4,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
        $io_pdf->ezStartPageNumbers(980,40,10,'','',1); // Insertar el número de página
		$io_report->dts_reporte->group_noorder("programatica");
		$li_tot=$io_report->dts_reporte->getRowCount("spg_cuenta");
		$ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
		$ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
		$ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
		$ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
		$ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
		$ls_denestpro1="";
		$ls_denestpro2="";
		$ls_denestpro3="";
		$ls_denestpro4="";
		$ls_denestpro5="";
		$ls_partida="";
		$ls_partida_next="";

		$ld_total_disponible		= 0;
		$ld_total_comprometido 		= 0;
		$ld_total_ajuste 			= 0;
		$ld_total_modificaciones 	= 0;
		$ld_total_precompromiso		= 0;
		$ld_total_libPrecompromiso	= 0;
		$ld_total_per1				= 0;
		$ld_total_per2				= 0;
		$ld_total_per3				= 0;

		$ld_totalp_disponible		= 0;
		$ld_totalp_comprometido 	= 0;
		$ld_totalp_ajuste 			= 0;
		$ld_totalp_modificaciones 	= 0;
		$ld_totalp_precompromiso	= 0;
		$ld_totalp_libPrecompromiso	= 0;
		$ld_totalp_per1				= 0;
		$ld_totalp_per2				= 0;
		$ld_totalp_per3				= 0;
		$ls_partida_aux="";
		//$z=1;$z<=$li_tot;$z++

		for($z=1;$z<=($i-2);$z++)
		{
			//$la_data_temp[$z]["cuenta"]
		    $li_tmp						= ($z+1);
			$thisPageNum				= $io_pdf->ezPageCount;
			$ls_programatica			= $la_data_temp[$z]["programatica"];
			$ls_spg_cuenta				= trim($la_data_temp[$z]["cuenta"]);
			$ls_status                 	= $la_data_temp[$z]["status"];
			$lb_lprintsub				= $la_data_temp[$z]["lprintsub"];

			$io_function_report->uf_get_spg_cuenta($ls_spg_cuenta,$ls_partida,$ls_generica,$ls_especifica,$ls_subesp);

		    if ($z<($i-2))
		    {
				$ls_programatica_next=$la_data_temp[$li_tmp]["programatica"];
				$ls_partida_next=substr($la_data_temp[$li_tmp]["cuenta"],0,3);
		    }
		    elseif($z=($i-2))
		    {
				$ls_programatica_next='no_next';
				$ls_partida_next='no_next';
		    }

			if(!empty($ls_programatica))
			{
				$ls_estcla=$la_data_temp[$z]["estcla"];
				$ls_codestpro1=substr($ls_programatica,0,25);
				$ls_denestpro1="";
				$lb_valido=$io_function_report->uf_spg_reporte_select_denestpro1($ls_codestpro1,$ls_denestpro1,$ls_estcla);
				if($lb_valido)
				{
				  $ls_denestpro1=trim($ls_denestpro1);
				}
				$ls_codestpro2=substr($ls_programatica,25,25);
				if($lb_valido)
				{
				  $ls_denestpro2="";
				  $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro2($ls_codestpro1,$ls_codestpro2,$ls_denestpro2,$ls_estcla);
				  $ls_denestpro2=trim($ls_denestpro2);
				}
				$ls_codestpro3=substr($ls_programatica,50,25);
				if($lb_valido)
				{
				  $ls_denestpro3="";
				  $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro3($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_denestpro3,$ls_estcla);
				  $ls_denestpro3=trim($ls_denestpro3);
				}
				if($li_estmodest==2)
				{
					$ls_codestpro4=substr($ls_programatica,75,25);
					if($lb_valido)
					{
					  $ls_denestpro4="";
					  $lb_valido=$io_report->uf_spg_reporte_select_denestpro4($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_denestpro4,$ls_estcla);
					  $ls_denestpro4=trim($ls_denestpro4);
					}
					$ls_codestpro5=substr($ls_programatica,100,25);
					if($lb_valido)
					{
					  $ls_denestpro5="";
					  $lb_valido=$io_report->uf_spg_reporte_select_denestpro5($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_denestpro5,$ls_estcla);
					  $ls_denestpro5=trim($ls_denestpro5);
					}
					$ls_denestpro_ant=trim($ls_denestpro1)." , ".trim($ls_denestpro2)." , ".trim($ls_denestpro3)." , ".trim($ls_denestpro4)." , ".trim($ls_denestpro5);
					$ls_programatica_ant=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2)."-".substr($ls_codestpro3,-$ls_loncodestpro3)."-".substr($ls_codestpro4,-$ls_loncodestpro4)."-".substr($ls_codestpro5,-$ls_loncodestpro5);
				}
				else
				{
					$ls_denestpro_ant=$ls_denestpro1." , ".$ls_denestpro2." , ".$ls_denestpro3;
					$ls_programatica_ant=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2)."-".substr($ls_codestpro3,-$ls_loncodestpro3);
				}
			}

			$ls_denominacion        	=trim($la_data_temp[$z]["denominacion"]);
			$ld_dispact              	=$la_data_temp[$z]["dispact"];
			$ld_disant              	=$la_data_temp[$z]["dispant"];
			$ld_periodo01         		=$la_data_temp[$z]["periodo01"];
			$ld_periodo02        		=$la_data_temp[$z]["periodo02"];
			$ld_periodo03       		=$la_data_temp[$z]["periodo03"];
			$ld_modpres              	=$la_data_temp[$z]["modpres"];
			$ld_precomprometido     	=$la_data_temp[$z]["precomprometido"];
			$ld_libprecomprometido  	=$la_data_temp[$z]["libprecomprometido"];
			$ld_libcomprometido     	=$la_data_temp[$z]["libcomprometido"];
			$ld_disponact     			=$la_data_temp[$z]["disponact"];
			$ld_comprometido        	=$ld_periodo01 + $ld_periodo02 + $ld_periodo03;
			$ld_disponible          	=$ld_disant - $ld_comprometido + $ld_modpres - $ld_precomprometido;
			if(($ls_status == "C")&&(!$lb_lprintsub))
			{

				$ld_comprometido        	=$ld_periodo01 + $ld_periodo02 + $ld_periodo03;
				$ld_disponible          	=$ld_disant - $ld_comprometido + $ld_modpres - $ld_precomprometido;

				$ld_total_disponible		= $ld_total_disponible + $ld_disponible;
				$ld_total_comprometido 		= $ld_total_comprometido + $ld_comprometido;
				$ld_total_ajuste 			= $ld_total_ajuste + $ld_libcomprometido;
				$ld_total_modificaciones 	= $ld_total_modificaciones + $ld_modpres;
				$ld_total_precompromiso		= $ld_total_precompromiso + $ld_precomprometido;
				$ld_total_libPrecompromiso	= $ld_total_libPrecompromiso + $ld_libprecomprometido;
				$ld_total_per1				= $ld_total_per1 + $ld_periodo01;
				$ld_total_per2				= $ld_total_per2 + $ld_periodo02;
				$ld_total_per3				= $ld_total_per3 + $ld_periodo03;


			}
			$ld_disant             =number_format($ld_disant,2,",",".");
			$ld_dispact            =number_format($ld_dispact,2,",",".");
			$ld_periodo01          =number_format($ld_periodo01,2,",",".");
			$ld_periodo02          =number_format($ld_periodo02,2,",",".");
			$ld_periodo03          =number_format($ld_periodo03,2,",",".");
			$ld_modpres            =number_format($ld_modpres,2,",",".");
			$ld_comprometido       =number_format($ld_comprometido,2,",",".");
			$ld_precomprometido    =number_format($ld_precomprometido,2,",",".");
			$ld_disponible         =number_format($ld_disponible,2,",",".");
			$ld_libprecomprometido =number_format($ld_libprecomprometido,2,",",".");
			$ld_libcomprometido    =number_format($ld_libcomprometido,2,",",".");
			$ld_disponact		   =number_format($ld_disponact,2,",",".");

			if (!empty($ls_programatica))
		    {
				switch($ld_tipper)
		 		{
		  			case 1:
		  				if ($lb_lprintsub)
		  				{
				         $la_data[$z]=array('cuenta'=>$ls_spg_cuenta,
										    'denominacion'=>$ls_denominacion,
										    'disponact'=>$ld_disponact,
										    'periodo01'=>$ld_periodo01,
										    'totcom'=>$ld_comprometido,
										    'ajucom'=>$ld_libcomprometido,
										    'modpres'=>$ld_modpres,
										    'precom'=>$ld_precomprometido,
										    'libprecom'=>$ld_libprecomprometido,
										    'disponible'=>$ld_disponact);
		  				}
		  				else
		  				{
				         $la_data[$z]=array('cuenta'=>$ls_spg_cuenta,
										    'denominacion'=>$ls_denominacion,
										    'disponact'=>$ld_dispact,
										    'periodo01'=>$ld_periodo01,
										    'totcom'=>$ld_comprometido,
										    'ajucom'=>$ld_libcomprometido,
										    'modpres'=>$ld_modpres,
										    'precom'=>$ld_precomprometido,
										    'libprecom'=>$ld_libprecomprometido,
										    'disponible'=>$ld_disponible);
		  				}

		         	break;

		  			case 2:
		  				if ($lb_lprintsub)
		  				{
		         		$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,
										    'denominacion'=>$ls_denominacion,
										    'disponact'=>$ld_disponact,
										    'periodo01'=>$ld_periodo01,
										    'periodo02'=>$ld_periodo02,
										    'totcom'=>$ld_comprometido,
										    'ajucom'=>$ld_libcomprometido,
										    'modpres'=>$ld_modpres,
										    'precom'=>$ld_precomprometido,
										    'libprecom'=>$ld_libprecomprometido,
										    'disponible'=>$ld_disponact);
		  				}
		  				else
		  				{
		         		$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,
										    'denominacion'=>$ls_denominacion,
										    'disponact'=>$ld_dispact,
										    'periodo01'=>$ld_periodo01,
										    'periodo02'=>$ld_periodo02,
										    'totcom'=>$ld_comprometido,
										    'ajucom'=>$ld_libcomprometido,
										    'modpres'=>$ld_modpres,
										    'precom'=>$ld_precomprometido,
										    'libprecom'=>$ld_libprecomprometido,
										    'disponible'=>$ld_disponible);
		  				}

		         	break;

		  			case 3:
		  				if ($lb_lprintsub)
		  				{
		         		$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,
										    'denominacion'=>$ls_denominacion,
										    'disponact'=>$ld_disponact,
										    'periodo01'=>$ld_periodo01,
										    'periodo02'=>$ld_periodo02,
										    'periodo03'=>$ld_periodo03,
										    'totcom'=>$ld_comprometido,
										    'ajucom'=>$ld_libcomprometido,
										    'modpres'=>$ld_modpres,
										    'precom'=>$ld_precomprometido,
										    'libprecom'=>$ld_libprecomprometido,
										    'disponible'=>$ld_disponact);
		  				}
		  				else
		  				{
		         		$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,
										    'denominacion'=>$ls_denominacion,
										    'disponact'=>$ld_dispact,
										    'periodo01'=>$ld_periodo01,
										    'periodo02'=>$ld_periodo02,
										    'periodo03'=>$ld_periodo03,
										    'totcom'=>$ld_comprometido,
										    'ajucom'=>$ld_libcomprometido,
										    'modpres'=>$ld_modpres,
										    'precom'=>$ld_precomprometido,
										    'libprecom'=>$ld_libprecomprometido,
										    'disponible'=>$ld_disponible);
		  				}

		         	break;
				}// switch
			}
			else
			{
				switch($ld_tipper)
				{
					case 1:
						if ($lb_lprintsub)
						{
							$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,
												'denominacion'=>$ls_denominacion,
												'disponact'=>$ld_disponact,
												'periodo01'=>$ld_periodo01,
												'totcom'=>$ld_comprometido,
												'ajucom'=>$ld_libcomprometido,
												'modpres'=>$ld_modpres,
												'precom'=>$ld_precomprometido,
												'libprecom'=>$ld_libprecomprometido,
												'disponible'=>$ld_disponact);
						}
						else
						{
							$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,
												'denominacion'=>$ls_denominacion,
												'disponact'=>$ld_dispact,
												'periodo01'=>$ld_periodo01,
												'totcom'=>$ld_comprometido,
												'ajucom'=>$ld_libcomprometido,
												'modpres'=>$ld_modpres,
												'precom'=>$ld_precomprometido,
												'libprecom'=>$ld_libprecomprometido,
												'disponible'=>$ld_disponible);
						}

					break;

				  	case 2:
				  		if ($lb_lprintsub)
				  		{
							$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,
											'denominacion'=>$ls_denominacion,
											'disponact'=>$ld_disponact,
											'periodo01'=>$ld_periodo01,
											'periodo02'=>$ld_periodo02,
											'totcom'=>$ld_comprometido,
											'ajucom'=>$ld_libcomprometido,
											'modpres'=>$ld_modpres,
											'precom'=>$ld_precomprometido,
											'libprecom'=>$ld_libprecomprometido,
											'disponible'=>$ld_disponact);
				  		}
				  		else
				  		{
							$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,
											'denominacion'=>$ls_denominacion,
											'disponact'=>$ld_dispact,
											'periodo01'=>$ld_periodo01,
											'periodo02'=>$ld_periodo02,
											'totcom'=>$ld_comprometido,
											'ajucom'=>$ld_libcomprometido,
											'modpres'=>$ld_modpres,
											'precom'=>$ld_precomprometido,
											'libprecom'=>$ld_libprecomprometido,
											'disponible'=>$ld_disponible);
				  		}

					break;

				  	case 3:
				  		if ($lb_lprintsub)
				  		{
						$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,
											'denominacion'=>$ls_denominacion,
											'disponact'=>$ld_disponact,
											'periodo01'=>$ld_periodo01,
											'periodo02'=>$ld_periodo02,
											'periodo03'=>$ld_periodo03,
											'totcom'=>$ld_comprometido,
											'ajucom'=>$ld_libcomprometido,
											'modpres'=>$ld_modpres,
											'precom'=>$ld_precomprometido,
											'libprecom'=>$ld_libprecomprometido,
											'disponible'=>$ld_disponact);
				  		}
				  		else
				  		{
						$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,
											'denominacion'=>$ls_denominacion,
											'disponact'=>$ld_dispact,
											'periodo01'=>$ld_periodo01,
											'periodo02'=>$ld_periodo02,
											'periodo03'=>$ld_periodo03,
											'totcom'=>$ld_comprometido,
											'ajucom'=>$ld_libcomprometido,
											'modpres'=>$ld_modpres,
											'precom'=>$ld_precomprometido,
											'libprecom'=>$ld_libprecomprometido,
											'disponible'=>$ld_disponible);
				  		}

					break;
				}	// switch
		     }	//if
			if (!empty($ls_programatica_next))
			{
				switch($ld_tipper)
				{
					case 1:
						if ($lb_lprintsub)
						{
					    	$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,
											    'denominacion'=>$ls_denominacion,
											    'disponact'=>$ld_disponact,
											    'periodo01'=>$ld_periodo01,
											    'totcom'=>$ld_comprometido,
											    'ajucom'=>$ld_libcomprometido,
											    'modpres'=>$ld_modpres,
											    'precom'=>$ld_precomprometido,
											    'libprecom'=>$ld_libprecomprometido,
											    'disponible'=>$ld_disponible);
						}
						else
						{
					    	$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,
											    'denominacion'=>$ls_denominacion,
											    'disponact'=>$ld_dispact,
											    'periodo01'=>$ld_periodo01,
											    'totcom'=>$ld_comprometido,
											    'ajucom'=>$ld_libcomprometido,
											    'modpres'=>$ld_modpres,
											    'precom'=>$ld_precomprometido,
											    'libprecom'=>$ld_libprecomprometido,
											    'disponible'=>$ld_disponible);
						}

				    break;

				  	case 2:
				  		if ($lb_lprintsub)
				  		{
					    	$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,
											    'denominacion'=>$ls_denominacion,
											    'disponact'=>$ld_disponact,
											    'periodo01'=>$ld_periodo01,
											    'periodo02'=>$ld_periodo02,
											    'totcom'=>$ld_comprometido,
											    'ajucom'=>$ld_libcomprometido,
											    'modpres'=>$ld_modpres,
											    'precom'=>$ld_precomprometido,
											    'libprecom'=>$ld_libprecomprometido,
											    'disponible'=>$ld_disponible);
				  		}
				  		else
				  		{
					    	$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,
											    'denominacion'=>$ls_denominacion,
											    'disponact'=>$ld_dispact,
											    'periodo01'=>$ld_periodo01,
											    'periodo02'=>$ld_periodo02,
											    'totcom'=>$ld_comprometido,
											    'ajucom'=>$ld_libcomprometido,
											    'modpres'=>$ld_modpres,
											    'precom'=>$ld_precomprometido,
											    'libprecom'=>$ld_libprecomprometido,
											    'disponible'=>$ld_disponible);
				  		}

				  	break;

				  	case 3:
				  		if ($lb_lprintsub)
						{
					    	$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,
											    'denominacion'=>$ls_denominacion,
											    'disponact'=>$ld_disponact,
											    'periodo01'=>$ld_periodo01,
											    'periodo02'=>$ld_periodo02,
											    'periodo03'=>$ld_periodo03,
											    'totcom'=>$ld_comprometido,
											    'ajucom'=>$ld_libcomprometido,
											    'modpres'=>$ld_modpres,
											    'precom'=>$ld_precomprometido,
											    'libprecom'=>$ld_libprecomprometido,
											    'disponible'=>$ld_disponact);
						}
						else
						{
					    	$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,
											    'denominacion'=>$ls_denominacion,
											    'disponact'=>$ld_dispact,
											    'periodo01'=>$ld_periodo01,
											    'periodo02'=>$ld_periodo02,
											    'periodo03'=>$ld_periodo03,
											    'totcom'=>$ld_comprometido,
											    'ajucom'=>$ld_libcomprometido,
											    'modpres'=>$ld_modpres,
											    'precom'=>$ld_precomprometido,
											    'libprecom'=>$ld_libprecomprometido,
											    'disponible'=>$ld_disponible);
						}

				     break;
				}	// switch

				$ls_lapso_meses='';

				//------>
				switch($ld_tipper)
				{
					case 1:
						{
							$ls_lapso_meses='<b>'.$ld_periodo01.'</b>';
						}
				    break;
				  	case 2:
				  		{
				  			$ls_lapso_meses='<b>'.$ld_periodo01.'  -  '.$ld_periodo02.'</b>';
				  		}
				  	break;
				  	case 3:
						{
							$ls_lapso_meses='<b>'.$ld_periodo01.'  -  '.$ld_periodo03.'</b>';
						}
				     break;
				}	// switch
				//------->

				$io_cabecera=$io_pdf->openObject();
				uf_print_cabecera($io_cabecera,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
				                  $ls_codestpro5,$ls_denestpro1,$ls_denestpro2,$ls_denestpro3,$ls_desper,$ls_lapso_meses,$ls_text_periodo,$io_pdf);
				$io_encabezado=$io_pdf->openObject();
				$io_function_report->uf_get_nom_mes($ld_per01,$as_nomper01);
				$io_function_report->uf_get_nom_mes($ld_per02,$as_nomper02);
				$io_function_report->uf_get_nom_mes($ld_per03,$as_nomper03);

				uf_print_cabecera_detalle($io_encabezado,$ld_tipper,$as_nomper01,$as_nomper02,$as_nomper03,$ld_fecfinrep,$io_pdf);
				uf_print_detalle($la_data,$ld_tipper,$as_nomper01,$as_nomper02,$as_nomper03,$io_pdf); // Imprimimos el detalle
				$ld_total_disponible             		=number_format($ld_total_disponible,2,",",".");
				$ld_total_comprometido             		=number_format($ld_total_comprometido,2,",",".");
				$ld_total_ajuste             			=number_format($ld_total_ajuste,2,",",".");
				$ld_total_modificaciones             	=number_format($ld_total_modificaciones,2,",",".");
				$ld_total_precompromiso             	=number_format($ld_total_precompromiso,2,",",".");
				$ld_total_libPrecompromiso             	=number_format($ld_total_libPrecompromiso,2,",",".");
				$ld_total_per1             				=number_format($ld_total_per1,2,",",".");
				$ld_total_per2             				=number_format($ld_total_per2,2,",",".");
				$ld_total_per3             				=number_format($ld_total_per3,2,",",".");

				uf_print_pie_cabecera($ld_tipper,$as_nomper01,$as_nomper02,$as_nomper03,$ld_total_per1,$ld_total_per2,$ld_total_per3,$ld_total_disponible,$ld_total_comprometido,$ld_total_ajuste,
									$ld_total_modificaciones,$ld_total_precompromiso,$ld_total_libPrecompromiso,$io_pdf,'Total Bs.');
				//$io_pdf->ezNewPage(); // Insertar una nueva página

				//Reinicializa valores
				$ld_total_disponible		= 0;
				$ld_total_comprometido 		= 0;
				$ld_total_ajuste 			= 0;
				$ld_total_modificaciones 	= 0;
				$ld_total_precompromiso		= 0;
				$ld_total_libPrecompromiso	= 0;
				$ld_total_per1				= 0;
				$ld_total_per2				= 0;
				$ld_total_per3				= 0;
				//---
				$io_pie_pagina=$io_pdf->openObject();

				$io_pdf->stopObject($io_pie_pagina);
				$io_pie_pagina=$io_pdf->openObject();
				$io_pdf->stopObject($io_cabecera);
				$io_pdf->stopObject($io_encabezado);
				$io_pdf->stopObject($io_pie_pagina);

				if ((!empty($ls_programatica_next))&&($z<$li_tot))
				{
				 	$io_pdf->ezNewPage(); // Insertar una nueva página
				}
				$ld_total_general_cuenta=0;
				unset($la_data);
				unset($la_data_tot);
			}//if
		}//for
		$io_pdf->ezStopPageNumbers(1,1);
		if (isset($d) && $d)
		{
			$ls_pdfcode = $io_pdf->ezOutput(1);
		  	$ls_pdfcode = str_replace("\n","\n<br>",htmlspecialchars($ls_pdfcode));
		  	echo '<html><body>';
		  	echo trim($ls_pdfcode);
		  	echo '</body></html>';
		}
		elseif ($li_tot>0)
		{
			$io_pdf->ezStream();
		}
		else
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');");
			print(" close();");
			print("</script>");
	    }
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_function_report);
	unset($io_fecha);



?>

