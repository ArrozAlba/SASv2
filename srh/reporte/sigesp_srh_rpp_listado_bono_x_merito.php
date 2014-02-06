<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Reporte de Evaluacion de Desempeño
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
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_srh;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("SRH","sigesp_srh_r_listado_bono_x_merito.php",$ls_descripcion);
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
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 11/02/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();        
		$io_pdf->setStrokeColor(0,0,0);		
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],80,500,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo

		$io_pdf->addText(540,770,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(546,764,6,date("h:i a")); // Agregar la Hora


		$io_pdf->ezSetDy(20);
		$la_data=array(array('titulo1'=>'<b>'.$as_titulo.'</b>'));
					
		$la_columnas=array('titulo1'=>'');
					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 14, // Tamaño de Letras
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
	
		$io_pdf->restoreState();
	    $io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	
	
     }// end function uf_print_encabezado_pagina
	 //-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($as_codper, $as_nomper, $as_cargo, $as_uniadm,&$io_pdf)
 	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//				 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el detalle del reporte.
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 11/02/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		  $io_pdf->ezSetY(450);
		
		$la_data[1]=array('total1'=>'<b>CÓDIGO DEL PERSONAL: </b>'. $as_codper);
		$la_columnas=array('total1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>12, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos'=>'420',
						 'cols'=>array('total1'=>array('justification'=>'rigth','width'=>700)));
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('total1'=>'<b>NOMBRES Y APELLIDOS: </b>'.$as_nomper);
		$la_columnas=array('total1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>12, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos'=>'420',
						 'cols'=>array('total1'=>array('justification'=>'rigth','width'=>700)));
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		
		$la_data[1]=array('total1'=>'<b>CARGO: </b>'.$as_cargo);
		$la_columnas=array('total1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>12, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos'=>'420',
						 'cols'=>array('total1'=>array('justification'=>'rigth','width'=>700)));
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('total1'=>'<b>UNIDAD ADMINISTRATIVA: </b>'.$as_uniadm);
		$la_columnas=array('total1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>12, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos'=>'420',
						 'cols'=>array('total1'=>array('justification'=>'rigth','width'=>700)));
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}// end function uf_print_detalle
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function uf_print_detalle2(&$io_pdf)
 	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_detalle2
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//				 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el detalle del reporte.
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 11/02/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_pdf->ezSetY(350);
		$la_data[1]=array('mes1'=>'<b>MES 1</b>',
						   'mes2'=>'<b>MES 2</b>',
						   'mes3'=>'<b>MES 3</b>',
						   'mes4'=>'<b>MES 4</b>',
						   'mes5'=>'<b>MES 5</b>',
						   'mes6'=>'<b>MES 6</b>',
						   'mes7'=>'<b>MES 7</b>',
						   'mes8'=>'<b>MES 8</b>',
						   'mes9'=>'<b>MES 9</b>',
						   'mes10'=>'<b>MES 10</b>',
						   'mes11'=>'<b>MES 11</b>',
						   'mes12'=>'<b>MES 12</b>');
						   
		$la_columnas=array('mes1'=>'',
						   'mes2'=>'',
						   'mes3'=>'',
						   'mes4'=>'',
						   'mes5'=>'',
						   'mes6'=>'',
						   'mes7'=>'',
						   'mes8'=>'',
						   'mes9'=>'',
						   'mes10'=>'',
						   'mes11'=>'',
						   'mes12'=>'');
						   
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('mes1'=>array('justification'=>'center','width'=>58),
						 			   'mes2'=>array('justification'=>'center','width'=>58),
						 			   'mes3'=>array('justification'=>'center','width'=>58),
						 			   'mes4'=>array('justification'=>'center','width'=>58),
									   'mes5'=>array('justification'=>'center','width'=>58),
									   'mes6'=>array('justification'=>'center','width'=>58),
									   'mes7'=>array('justification'=>'center','width'=>58),
									   'mes8'=>array('justification'=>'center','width'=>58),
						 			   'mes9'=>array('justification'=>'center','width'=>58),
						 			   'mes10'=>array('justification'=>'center','width'=>58),
						 			   'mes11'=>array('justification'=>'center','width'=>58),
						 			   'mes12'=>array('justification'=>'center','width'=>58))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
function uf_print_detalle3($aa_fecha, &$io_pdf)
 	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_detalle2
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//				 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el detalle del reporte.
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 11/02/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
	
		$la_data[1]=array('fecha1'=>$aa_fecha[1],
						   'fecha2'=>$aa_fecha[2],
						   'fecha3'=>$aa_fecha[3],
						   'fecha4'=>$aa_fecha[4],
						   'fecha5'=>$aa_fecha[5],
						   'fecha6'=>$aa_fecha[6],
						   'fecha7'=>$aa_fecha[7],
						   'fecha8'=>$aa_fecha[8],
						   'fecha9'=>$aa_fecha[9],
						   'fecha10'=>$aa_fecha[10],
						   'fecha11'=>$aa_fecha[11],
						   'fecha12'=>$aa_fecha[12]);
						   
		$la_columnas=array('fecha1'=>'',
						   'fecha2'=>'',
						   'fecha3'=>'',
						   'fecha4'=>'',
						   'fecha5'=>'',
						   'fecha6'=>'',
						   'fecha7'=>'',
						   'fecha8'=>'',
						   'fecha9'=>'',
						   'fecha10'=>'',
						   'fecha11'=>'',
						   'fecha12'=>'');
						   
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('fecha1'=>array('justification'=>'center','width'=>58),
						 			   'fecha2'=>array('justification'=>'center','width'=>58),
						 			   'fecha3'=>array('justification'=>'center','width'=>58),
						 			   'fecha4'=>array('justification'=>'center','width'=>58),
									   'fecha5'=>array('justification'=>'center','width'=>58),
									   'fecha6'=>array('justification'=>'center','width'=>58),
									   'fecha7'=>array('justification'=>'center','width'=>58),
									   'fecha8'=>array('justification'=>'center','width'=>58),
						 			   'fecha9'=>array('justification'=>'center','width'=>58),
						 			   'fecha10'=>array('justification'=>'center','width'=>58),
						 			   'fecha11'=>array('justification'=>'center','width'=>58),
						 			   'fecha12'=>array('justification'=>'center','width'=>58))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
function uf_print_detalle4($aa_total, &$io_pdf)
 	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_detalle2
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//				 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el detalle del reporte.
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 11/02/2008 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
	
		$la_data[1]=array('total1'=>$aa_total[1],
						   'total2'=>$aa_total[2],
						   'total3'=>$aa_total[3],
						   'total4'=>$aa_total[4],
						   'total5'=>$aa_total[5],
						   'total6'=>$aa_total[6],
						   'total7'=>$aa_total[7],
						   'total8'=>$aa_total[8],
						   'total9'=>$aa_total[9],
						   'total10'=>$aa_total[10],
						   'total11'=>$aa_total[11],
						   'total12'=>$aa_total[12]);
						   
		$la_columnas=array('total1'=>'',
						   'total2'=>'',
						   'total3'=>'',
						   'total4'=>'',
						   'total5'=>'',
						   'total6'=>'',
						   'total7'=>'',
						   'total8'=>'',
						   'total9'=>'',
						   'total10'=>'',
						   'total11'=>'',
						   'total12'=>'');
						   
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total1'=>array('justification'=>'center','width'=>58),
						 			   'total2'=>array('justification'=>'center','width'=>58),
						 			   'total3'=>array('justification'=>'center','width'=>58),
						 			   'total4'=>array('justification'=>'center','width'=>58),
									   'total5'=>array('justification'=>'center','width'=>58),
									   'total6'=>array('justification'=>'center','width'=>58),
									   'total7'=>array('justification'=>'center','width'=>58),
									   'total8'=>array('justification'=>'center','width'=>58),
						 			   'total9'=>array('justification'=>'center','width'=>58),
						 			   'total10'=>array('justification'=>'center','width'=>58),
						 			   'total11'=>array('justification'=>'center','width'=>58),
						 			   'total12'=>array('justification'=>'center','width'=>58))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//---------------------------------------------------------------------------------------------------------------------------------///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
function uf_print_detalle5($as_totalpuntos, $as_promedio, $as_monto,&$io_pdf)
 	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_detalle2
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información			 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el detalle del reporte.
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 11/02/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(200);
	
		$la_data[1]=array('total1'=>'<b>TOTAL PUNTOS:    </b>'.$as_totalpuntos);
		$la_columnas=array('total1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>12, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>400, // Ancho de la tabla
						 'maxWidth'=>400, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos'=>'650',
						 'cols'=>array('total1'=>array('justification'=>'rigth','width'=>400)));
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('total1'=>'<b>PROMEDIO PUNTOS:   </b>'.$as_promedio);
		$la_columnas=array('total1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>12, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>400, // Ancho de la tabla
						 'maxWidth'=>400, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos'=>'650',
						 'cols'=>array('total1'=>array('justification'=>'rigth','width'=>400)));
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		
		$la_data[1]=array('total1'=>'<b>MONTO BONO MÉRITO Bs.: </b>'.$as_monto);
		$la_columnas=array('total1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>12, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>400, // Ancho de la tabla
						 'maxWidth'=>400, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos'=>'650',
						 'cols'=>array('total1'=>array('justification'=>'rigth','width'=>400)));
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}// end function uf_print_detalle
	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	
    require_once("../../shared/ezpdf/class.ezpdf.php");	
	require_once("class_folder/sigesp_srh_class_report.php");
	$io_report=new sigesp_srh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/utilidades/class_funciones_srh.php");
	$io_fun_srh=new class_funciones_srh('../../');
	require_once("../../sno/class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
       $ls_titulo="<b>LISTADO DE BONOS POR MERITOS</b>"; 
	//--------------------------------------------------  Parámetros para Filtar el Reporte  --------------------------------
	$ld_fechades=$io_fun_srh->uf_obtenervalor_get("fechades","");
	$ld_fechahas=$io_fun_srh->uf_obtenervalor_get("fechahas","");
	$ls_codperdes=$io_fun_srh->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_srh->uf_obtenervalor_get("codperhas","");
	$ls_coduniadmdes=$io_fun_srh->uf_obtenervalor_get("coduniadm1","");
	$ls_coduniadmhas=$io_fun_srh->uf_obtenervalor_get("coduniadm2","");	//-----------------------------------------------------------------------------------------------------------------------------------
	global $la_data;
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_select_bonos_x_merito($ld_fechades,$ld_fechahas,$ls_codperdes,$ls_codperhas,$ls_coduniadmdes,$ls_coduniadmhas); // Cargar el DS con los datos del reporte
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
			 $io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
			 $io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			 $io_pdf->ezSetCmMargins(4,3,3,3); // Configuración de los margenes en centímetros
			 $io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página
			 
			  uf_print_encabezado_pagina($ls_titulo,$io_pdf);
			 $li_totrow=$io_report->DS->getRowCount("codper");
			 $entro=false;
			 $entrar=false;
			 $ls_totalpuntos=0;
			 $ls_promedio=0;
			 $ls_monto=0;
			 $la_fecha[1]="";
			 $la_fecha[2]="";
			 $la_fecha[3]="";
			 $la_fecha[4]="";
			 $la_fecha[5]="";
			 $la_fecha[6]="";
			 $la_fecha[7]="";
			 $la_fecha[8]="";
			 $la_fecha[9]="";
			 $la_fecha[10]="";
			 $la_fecha[11]="";
			 $la_fecha[12]="";
			 $la_total[1]="";
			 $la_total[2]="";
			 $la_total[3]="";
			 $la_total[4]="";
			 $la_total[5]="";
			 $la_total[6]="";
			 $la_total[7]="";
		     $la_total[8]="";
			 $la_total[9]="";
			 $la_total[10]="";
			 $la_total[11]="";
			 $la_total[12]="";
			 $i=0;
			 
			 for($li_i=1;$li_i<=$li_totrow;$li_i++)
			 {
			    
				if ($li_i<$li_totrow)
				{
					 $ls_codper1=$io_report->DS->data["codper"][$li_i];	
					 $ls_codper2=$io_report->DS->data["codper"][$li_i+1];
					 if ($ls_codper1 != $ls_codper2)
					 {
					   if (($li_i-1)!=0)
						{
						  	$entrar=true;
						 						
						}
						elseif ($li_i==1)
						{
						  	$entrar=true;
						}
						
												
					 }// fin if ($ls_codper1 != $ls_codper2)
					
					
				 }
				else if ($li_i==$li_totrow)
				 {
					  $ls_codper1=$io_report->DS->data["codper"][$li_i];	
					  $ls_codper2=$io_report->DS->data["codper"][$li_totrow];
				 
				 }
			   
				
																	
				if ($ls_codper1 == $ls_codper2) 
				{
								
						
					$lb_valido2=$io_report->uf_select_persona_bonos_x_merito($ls_codper1);	
					
					if (($lb_valido2) &&(!$entro))
					{			
						
						$entro=true;
						$ls_codigo=$io_report->DS2->data["codper"][1];
						$ls_uniadm=$io_report->DS2->data["desuniadm"][1];
						$ls_car1 =trim ($io_report->DS2->data["cargo1"][1]);
						$ls_car2 =trim ($io_report->DS2->data["cargo2"][1]);
						
						if ($ls_car1=="Sin Asignación de Cargo")
						{
							$ls_cargo = $ls_car2;	
						}
						else
						{
							$ls_cargo = $ls_car1;
						}				
						
						$ls_nombreper=$io_report->DS2->data["nomper"][1];
						$ls_apellidoper=$io_report->DS2->data["apeper"][1];
						
						$ls_cadena=$ls_nombreper."  ".$ls_apellidoper;	
						uf_print_detalle($ls_codigo,$ls_cadena, $ls_cargo, $ls_uniadm,$io_pdf);
					    uf_print_detalle2($io_pdf);	
					}
					
						
					$i=$i+1;	
					$ls_total=$io_report->DS->data["total"][$li_i];	
					$ls_fecha=$io_report->DS->data["fecha"][$li_i];	
					$ls_escala=$io_report->DS->data["codpun"][$li_i];;
					
					$ld_mesd= substr($ls_fecha,5,2);
					$ls_fecha=$io_funciones->uf_formatovalidofecha($ls_fecha);
				    $ls_fecha=$io_funciones->uf_convertirfecmostrar($ls_fecha);
					 switch ($ld_mesd)
					 {
						case '01':
						  $la_fecha[1]=$ls_fecha;
						  $la_total[1]= $ls_total;
						  break;
						case '02':
						  $la_fecha[2]=$ls_fecha;
						  $la_total[2]= $ls_total;
						  break;
						case '03':
						  $la_fecha[3]=$ls_fecha;
						  $la_total[3]= $ls_total;
						  break;
						case '04':
						  $la_fecha[4]=$ls_fecha;
						  $la_total[4]= $ls_total;
						  break;
						case '05':
						  $la_fecha[5]=$ls_fecha;
						  $la_total[5]= $ls_total;
						  break;
						case '06':
						  $la_fecha[6]=$ls_fecha;
						  $la_total[6]= $ls_total;
						  break;
						case '07':
						  $la_fecha[7]=$ls_fecha;
						  $la_total[7]= $ls_total;
						  break;
						case '08':
						   $la_fecha[8]=$ls_fecha;
						   $la_total[8]= $ls_total;
						  break;
						case '09':
						  $la_fecha[9]=$ls_fecha;
						  $la_total[9]= $ls_total;
						  break;
						case '10':
						  $la_fecha[10]=$ls_fecha;
						  $la_total[10]= $ls_total;
						  break;
						case '11':
						  $la_fecha[11]=$ls_fecha;
						  $la_total[11]= $ls_total;
						  break;
						case '12':
						  $la_fecha[12]=$ls_fecha;
						  $la_total[12]= $ls_total;
						  break;
					 }
					
				    
					$ls_totalpuntos=$ls_totalpuntos + $ls_total;
					
					
					
					
			   }
			   elseif ($entrar)
			   {
					$entrar=false;			
						
					$lb_valido2=$io_report->uf_select_persona_bonos_x_merito($ls_codper1);	
					
					if (($lb_valido2) &&(!$entro))
					{			
						
						$entro=true;
						$ls_codigo=$io_report->DS2->data["codper"][1];
						$ls_uniadm=$io_report->DS2->data["desuniadm"][1];
						$ls_car1 =trim ($io_report->DS2->data["cargo1"][1]);
						$ls_car2 =trim ($io_report->DS2->data["cargo2"][1]);
						
						if ($ls_car1=="Sin Asignación de Cargo")
						{
							$ls_cargo = $ls_car2;	
						}
						else
						{
							$ls_cargo = $ls_car1;
						}				
						
						$ls_nombreper=$io_report->DS2->data["nomper"][1];
						$ls_apellidoper=$io_report->DS2->data["apeper"][1];
						
						$ls_cadena=$ls_nombreper."  ".$ls_apellidoper;	
						uf_print_detalle($ls_codigo,$ls_cadena, $ls_cargo, $ls_uniadm,$io_pdf);
					    uf_print_detalle2($io_pdf);	
					}
					
						
					$i=$i+1;	
					$ls_total=$io_report->DS->data["total"][$li_i];	
					$ls_fecha=$io_report->DS->data["fecha"][$li_i];	
					$ls_escala=$io_report->DS->data["codpun"][$li_i];;
					
					$ld_mesd= substr($ls_fecha,5,2);
					$ls_fecha=$io_funciones->uf_formatovalidofecha($ls_fecha);
				    $ls_fecha=$io_funciones->uf_convertirfecmostrar($ls_fecha);
					 switch ($ld_mesd)
					 {
						case '01':
						  $la_fecha[1]=$ls_fecha;
						  $la_total[1]= $ls_total;
						  break;
						case '02':
						  $la_fecha[2]=$ls_fecha;
						  $la_total[2]= $ls_total;
						  break;
						case '03':
						  $la_fecha[3]=$ls_fecha;
						  $la_total[3]= $ls_total;
						  break;
						case '04':
						  $la_fecha[4]=$ls_fecha;
						  $la_total[4]= $ls_total;
						  break;
						case '05':
						  $la_fecha[5]=$ls_fecha;
						  $la_total[5]= $ls_total;
						  break;
						case '06':
						  $la_fecha[6]=$ls_fecha;
						  $la_total[6]= $ls_total;
						  break;
						case '07':
						  $la_fecha[7]=$ls_fecha;
						  $la_total[7]= $ls_total;
						  break;
						case '08':
						   $la_fecha[8]=$ls_fecha;
						   $la_total[8]= $ls_total;
						  break;
						case '09':
						  $la_fecha[9]=$ls_fecha;
						  $la_total[9]= $ls_total;
						  break;
						case '10':
						  $la_fecha[10]=$ls_fecha;
						  $la_total[10]= $ls_total;
						  break;
						case '11':
						  $la_fecha[11]=$ls_fecha;
						  $la_total[11]= $ls_total;
						  break;
						case '12':
						  $la_fecha[12]=$ls_fecha;
						  $la_total[12]= $ls_total;
						  break;
					 }
					
				    
					$ls_totalpuntos=$ls_totalpuntos + $ls_total;
					$entro=false;				   
				   uf_print_detalle3($la_fecha,$io_pdf);						   
				   uf_print_detalle4($la_total,$io_pdf);
				   $ls_promedio=round($ls_totalpuntos/12);	
				   $io_report->uf_select_monto_bono_merito($ls_escala,$ls_promedio,$ls_monto);
				   $ls_monto=$io_fun_nomina->uf_formatonumerico($ls_monto);						   
				   uf_print_detalle5($ls_totalpuntos, $ls_promedio, $ls_monto,$io_pdf);
				   $ls_totalpuntos=0;
				   $ls_promedio=0;
				   $ls_monto=0;
				   $la_fecha[1]="";
				   $la_fecha[2]="";
				   $la_fecha[3]="";
				   $la_fecha[4]="";
				   $la_fecha[5]="";
				   $la_fecha[6]="";
				   $la_fecha[7]="";
				   $la_fecha[8]="";
				   $la_fecha[9]="";
				   $la_fecha[10]="";
				   $la_fecha[11]="";
			       $la_fecha[12]="";
				   $la_total[1]="";
				   $la_total[2]="";
				   $la_total[3]="";
				   $la_total[4]="";
				   $la_total[5]="";
				   $la_total[6]="";
				   $la_total[7]="";
				   $la_total[8]="";
				   $la_total[9]="";
				   $la_total[10]="";
				   $la_total[11]="";
				   $la_total[12]="";
				   $i=0;
				   $io_pdf->ezNewPage(); // Insertar una nueva página					
					
			   }
			   
			   else 
			   {
			   	   $entro=false;				   
				   uf_print_detalle3($la_fecha,$io_pdf);						   
				   uf_print_detalle4($la_total,$io_pdf);
				   $ls_promedio=round($ls_totalpuntos/12);	
				   $io_report->uf_select_monto_bono_merito($ls_escala,$ls_promedio,$ls_monto);						   
				   uf_print_detalle5($ls_totalpuntos, $ls_promedio, $ls_monto,$io_pdf);
				   $ls_totalpuntos=0;
				   $ls_promedio=0;
				   $ls_monto=0;
				   $la_fecha[1]="";
				   $la_fecha[2]="";
				   $la_fecha[3]="";
				   $la_fecha[4]="";
				   $la_fecha[5]="";
				   $la_fecha[6]="";
				   $la_fecha[7]="";
				   $la_fecha[8]="";
				   $la_fecha[9]="";
				   $la_fecha[10]="";
				   $la_fecha[11]="";
			       $la_fecha[12]="";
				   $la_total[1]="";
				   $la_total[2]="";
				   $la_total[3]="";
				   $la_total[4]="";
				   $la_total[5]="";
				   $la_total[6]="";
				   $la_total[7]="";
				   $la_total[8]="";
				   $la_total[9]="";
				   $la_total[10]="";
				   $la_total[11]="";
				   $la_total[12]="";
				   $i=0;
				   $io_pdf->ezNewPage(); // Insertar una nueva página
			   }			    
							
			  }
			   
			    $entro=false;
				$entrar=false;
				uf_print_detalle3($la_fecha,$io_pdf);						   
				uf_print_detalle4($la_total,$io_pdf);
				$ls_promedio=round($ls_totalpuntos/12);	
				$io_report->uf_select_monto_bono_merito($ls_escala,$ls_promedio,$ls_monto);	
				$ls_monto=$io_fun_nomina->uf_formatonumerico($ls_monto);					   
				uf_print_detalle5($ls_totalpuntos, $ls_promedio, $ls_monto,$io_pdf);
			  
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