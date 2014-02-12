<?php
    session_start();
	ini_set('memory_limit','512M');
	ini_set('max_execution_time ','0');   
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
	$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],10,530,$_SESSION["ls_width"],$_SESSION["ls_height"]);    
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
	function uf_print_cabecera($io_cabecera,$as_programatica,$as_denestpro,&$io_pdf)
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
		if ($_SESSION["la_empresa"]["estmodest"] == 2)
		{
			$la_data=array(array('name'=>'<b>Programatica</b> '.$as_programatica.''),
						   array('name'=>'<b></b>'.$as_denestpro.''));
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
		}
		else
		{
			 $ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
			 $ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
			 $ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
			 
			 $la_datatit=array(array('name'=>'<b>ESTRUCTURA PRESUPUESTARIA </b>'));
			 
			 $la_columnatit=array('name'=>'');
			 
			 $la_configtit=array('showHeadings'=>0, // Mostrar encabezados
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'fontSize' => 7, // Tamaño de Letras
							 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
							 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
							 'xOrientation'=>'center', // Orientación de la tabla
							 'xPos'=>280, // Orientación de la tabla
							 'width'=>550, // Ancho de la tabla
							 'maxWidth'=>550);// Ancho Máximo de la tabla
			 
			 $io_pdf->ezTable($la_datatit,$la_columnatit,'',$la_configtit);	
			 
			 $la_data=array(array('name'=>substr($as_programatica,0,$ls_loncodestpro1).'</b>','name2'=>$as_denestpro[0]),
							array('name'=>substr($as_programatica,$ls_loncodestpro1,$ls_loncodestpro2),'name2'=>$as_denestpro[1]),
							array('name'=>substr($as_programatica,$ls_loncodestpro1+$ls_loncodestpro2,$ls_loncodestpro3),'name2'=>$as_denestpro[2]));
							
			 $la_columna=array('name'=>'','name2'=>'');
			 $la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'fontSize' => 7, // Tamaño de Letras
							 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
							 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
							 'xOrientation'=>'center', // Orientación de la tabla
							 'xPos'=>280, // Orientación de la tabla
							 'width'=>550, // Ancho de la tabla
							 'maxWidth'=>550,// Ancho Máximo de la tabla
							 'cols'=>array('name'=>array('justification'=>'right','width'=>40), // Justificación y ancho de la columna
										   'name2'=>array('justification'=>'left','width'=>510))); // Justificación y ancho de la columna
			 $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
			 $io_pdf->restoreState();
			 $io_pdf->closeObject();
			 $io_pdf->addObject($io_cabecera,'all');									   
		}			   
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_detalle($io_encabezado,&$io_pdf)
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
		$la_data=array(array('cuenta'=>'<b>Cuenta</b>','denominacion'=>'<b>Denominación</b>','trimestrei'=>'<b>I TRIMESTRE</b>',
		                     'trimestreii'=>'<b>II TRIMESTRE</b>','trimestreiii'=>'<b> III TRIMESTRE</b>','trimestreiv'=>'<b>IV TRIMESTRE</b>','total'=>'<b>TOTAL</b>',));
		$la_columnas=array('cuenta'=>'','denominacion'=>'','trimestrei'=>'','trimestreii'=>'','trimestreiii'=>'','trimestreiv'=>'',
						   'total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.4,0.7,0.1),
						 'colGap'=>0.5, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>502, // Orientación de la tabla
					     'cols'=>array('cuenta'=>array('justification'=>'center','width'=>140), // Justificación y ancho de la 
								       'denominacion'=>array('justification'=>'center','width'=>300), // Justificación y ancho 
								       'trimestrei'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
								       'trimestreii'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la 
								       'trimestreiii'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
								       'trimestreiv'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
								       'total'=>array('justification'=>'center','width'=>110))); // Justificación y ancho 
	
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_cabecera_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
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
					 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>140), // Justificación y ancho de la columna
								   'denominacion'=>array('justification'=>'left','width'=>300), // Justificación y ancho 
								   'trimestrei'=>array('justification'=>'right','width'=>110), // Justificación y ancho de la columna
								   'trimestreii'=>array('justification'=>'right','width'=>110), // Justificación y ancho de la columna
								   'trimestreiii'=>array('justification'=>'right','width'=>110), // Justificación y ancho de la columna
								   'trimestreiv'=>array('justification'=>'right','width'=>110), // Justificación y ancho de la columna
								   'total'=>array('justification'=>'right','width'=>110))); // Justificación y ancho 
	
	$la_columnas=array('cuenta'=>'<b>Cuenta</b>',
					   'denominacion'=>'<b>Denominacion</b>',
					   'trimestrei'=>'<b>I TRIMESTRE</b>',
					   'trimestreii'=>'<b>II TRIMESTRE</b>',
					   'trimestreiii'=>'<b>III TRIMESTRE</b>',
					   'trimestreiv'=>'<b>IV TRIMESTRE</b>',
					   'total'=>'<b>Totalr</b>');
	$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($la_data_tot,$io_pie_pagina,&$io_pdf)
	{
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function : uf_print_pie_cabecera
	//		    Acess : private 
	//	    Arguments : ad_total // Total General
	//    Description : función que imprime el fin de la cabecera de cada página
	//	   Creado Por:  Ing. Yozelin Barragán
	// Fecha Creación:  12/09/2006
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$io_pdf->saveState();
	$la_data=array(array('name'=>'___________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________'));
	$la_columna=array('name'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
					 'fontSize' => 7, // Tamaño de Letras
					 'showLines'=>0, // Mostrar Líneas
					 'shaded'=>0, // Sombra entre líneas
					 'xOrientation'=>'center', // Orientación de la tabla
					 'width'=>990,//Ancho de la tabla
					 'xPos'=>510, // Orientación de la tabla
					 'maxWidth'=>990); // Ancho Máximo de la tabla
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
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
				 'cols'=>array('totalgeneral'=>array('justification'=>'center','width'=>440), // Justificación y ancho de la 
							   'trimestrei'=>array('justification'=>'right','width'=>110), // Justificación y ancho de la columna
							   'trimestreii'=>array('justification'=>'right','width'=>110), // Justificación y ancho de la columna
							   'trimestreiii'=>array('justification'=>'right','width'=>110), // Justificación y ancho de la columna
							   'trimestreiv'=>array('justification'=>'right','width'=>110), // Justificación y ancho de la columna
							   'total'=>array('justification'=>'right','width'=>110))); // Justificación y ancho 
		
	$la_columnas=array('totalgeneral'=>'',
					   'trimestrei'=>'',
					   'trimestreii'=>'',
					   'trimestreiii'=>'',
					   'trimestreiv'=>'',
					   'total'=>'');
	$io_pdf->ezTable($la_data_tot,$la_columnas,'',$la_config);
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_pie_pagina,'all');
  }// end function uf_print_pie_cabecera
//-----------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("sigesp_spg_funciones_reportes.php");
		$io_function_report = new sigesp_spg_funciones_reportes();
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
//-----------------------------------------------------------------------------------------------------------------------------
		$ls_tipoformato=$_GET["tipoformato"];
		global $ls_tipoformato;
		global $la_data_tot_bsf;
		global $la_data_tot;
		if($ls_tipoformato==1)
		{
			require_once("sigesp_spg_reportes_class_bsf.php");
			$io_report = new sigesp_spg_reportes_class_bsf();
		}
		else
		{
			require_once("sigesp_spg_reportes_class.php");
			$io_report = new sigesp_spg_reportes_class();
		}	
		require_once("../../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
		$io_rcbsf= new sigesp_c_reconvertir_monedabsf();
		$li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$li_redconmon=$_SESSION["la_empresa"]["redconmon"];
//------------------------------------------------------------------------------------------------------------------------------		

//--------------------------------------------------  Parámetros para Filtar el Reporte  ------------------------------------
		$li_estmodest       = $_SESSION["la_empresa"]["estmodest"];
		$ls_codestpro1_min  = $_GET["codestpro1"];
		$ls_codestpro2_min  = $_GET["codestpro2"];
		$ls_codestpro3_min  = $_GET["codestpro3"];
		$ls_codestpro1h_max = $_GET["codestpro1h"];
		$ls_codestpro2h_max = $_GET["codestpro2h"];
		$ls_codestpro3h_max = $_GET["codestpro3h"];
	    $ls_estclades       = $_GET["estclades"];
	    $ls_estclahas       = $_GET["estclahas"];
		$ls_cuentades       = $_GET["txtcuentades"];
	    $ls_cuentahas       = $_GET["txtcuentahas"];
		if($li_estmodest==1)
		{
			$ls_codestpro4_min =  "0000000000000000000000000";
			$ls_codestpro5_min =  "0000000000000000000000000";
			$ls_codestpro4h_max = "0000000000000000000000000";
			$ls_codestpro5h_max = "0000000000000000000000000";
			if(($ls_codestpro1_min=="")&&($ls_codestpro2_min=="")&&($ls_codestpro3_min==""))
			{
			  if($io_function_report->uf_spg_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,                                                                             $ls_codestpro3_min,$ls_codestpro4_min,
			                                                                 $ls_codestpro3_min,$ls_estclades))
			  {
					$ls_codestpro1  = trim($ls_codestpro1_min);
					$ls_codestpro2  = trim($ls_codestpro2_min);
					$ls_codestpro3  = trim($ls_codestpro3_min);
					$ls_codestpro4  = trim($ls_codestpro4_min);
					$ls_codestpro5  = trim($ls_codestpro5_min);
			  }
			}
			else
			{
					$ls_codestpro1  = $ls_codestpro1_min;
					$ls_codestpro2  = $ls_codestpro2_min;
					$ls_codestpro3  = $ls_codestpro3_min;
					$ls_codestpro4  = $ls_codestpro4_min;
					$ls_codestpro5  = $ls_codestpro5_min;
			}
			if(($ls_codestpro1h_max=="")&&($ls_codestpro2h_max=="")&&($ls_codestpro3h_max==""))
			{
			  if($io_function_report->uf_spg_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
																			 $ls_codestpro3h_max,$ls_codestpro4h_max,
																			 $ls_codestpro4h_max,$ls_estclades))
			  {
				$ls_codestpro1h  = $ls_codestpro1h_max;
				$ls_codestpro2h  = $ls_codestpro2h_max;
				$ls_codestpro3h  = $ls_codestpro3h_max;
				$ls_codestpro4h  = $ls_codestpro4h_max;
				$ls_codestpro5h  = $ls_codestpro5h_max;
			  }
			}
			else
			{
				$ls_codestpro1h  = $ls_codestpro1h_max;
				$ls_codestpro2h  = $ls_codestpro2h_max;
				$ls_codestpro3h  = $ls_codestpro3h_max;
				$ls_codestpro4h  = $ls_codestpro4h_max;
				$ls_codestpro5h  = $ls_codestpro5h_max;
			}
		}
		elseif($li_estmodest==2)
		{
			$ls_codestpro4_min = $_GET["codestpro4"];
			$ls_codestpro5_min = $_GET["codestpro5"];
			$ls_codestpro4h_max = $_GET["codestpro4h"];
			$ls_codestpro5h_max = $_GET["codestpro5h"];
			if(($ls_codestpro1_min=="")&&($ls_codestpro2_min=="")&&($ls_codestpro3_min=="")&&($ls_codestpro4_min=="")&&
			   ($ls_codestpro5_min==""))
			{
			  if($io_function_report->uf_spg_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,                                                                             $ls_codestpro3_min,$ls_codestpro4_min,
			                                                                 $ls_codestpro5_min,$ls_estclahas))
			  {
					$ls_codestpro1  = $ls_codestpro1_min;
					$ls_codestpro2  = $ls_codestpro2_min;
					$ls_codestpro3  = $ls_codestpro3_min;
					$ls_codestpro4  = $ls_codestpro4_min;
					$ls_codestpro5  = $ls_codestpro5_min;
			  }
			}
			else
			{
					$ls_codestpro1  = $ls_codestpro1_min;
					$ls_codestpro2  = $ls_codestpro2_min;
					$ls_codestpro3  = $ls_codestpro3_min;
					$ls_codestpro4  = $ls_codestpro4_min;
					$ls_codestpro5  = $ls_codestpro5_min;
			}
			if(($ls_codestpro1h_max=="")&&($ls_codestpro2h_max=="")&&($ls_codestpro3h_max=="")&&($ls_codestpro4h_max=="")&&
			   ($ls_codestpro5h_max==""))
			{
			  if($io_function_report->uf_spg_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
																			 $ls_codestpro3h_max,$ls_codestpro4h_max,
																			 $ls_codestpro4h_max,$ls_estclahas))
			  {
				$ls_codestpro1h  = $ls_codestpro1h_max;
				$ls_codestpro2h  = $ls_codestpro2h_max;
				$ls_codestpro3h  = $ls_codestpro3h_max;
				$ls_codestpro4h  = $ls_codestpro4h_max;
				$ls_codestpro5h  = $ls_codestpro5h_max;
			  }
			}
			else
			{
				$ls_codestpro1h  = $ls_codestpro1h_max;
				$ls_codestpro2h  = $ls_codestpro2h_max;
				$ls_codestpro3h  = $ls_codestpro3h_max;
				$ls_codestpro4h  = $ls_codestpro4h_max;
				$ls_codestpro5h  = $ls_codestpro5h_max;
			}
		}	
	   $ls_codfuefindes=$_GET["txtcodfuefindes"];
	   $ls_codfuefinhas=$_GET["txtcodfuefinhas"];
	   if (($ls_codfuefindes=='')&&($ls_codfuefindes==''))
	   {
		  if($io_function_report->uf_spg_select_fuentefinanciamiento(&$ls_minfuefin,&$ls_maxfuefin))
		  {
		     $ls_codfuefindes=$ls_minfuefin;
		     $ls_codfuefinhas=$ls_maxfuefin;
		  } 
	   }
	   if ($ls_cuentades=='')
	   {
	    $ls_cuenta = "";
		if ($io_function_report->uf_spg_reporte_select_min_cuenta(&$ls_cuenta))
		{
		 $ls_cuentades = $ls_cuenta;
		}
	   }
	   
	   if ($ls_cuentahas=='')
	   {
	    $ls_cuenta = "";
		if ($io_function_report->uf_spg_reporte_select_max_cuenta(&$ls_cuenta))
		{
		 $ls_cuentahas = $ls_cuenta;
		}
	   }
	 /////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_programatica_desde=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5.$ls_estclades;
	 $ls_programatica_hasta=$ls_codestpro1h.$ls_codestpro2h.$ls_codestpro3h.$ls_codestpro4h.$ls_codestpro5h.$ls_estclahas;
	 
	 $ls_desc_event="Solicitud de Reporte Distribucion Trimestral del Presupuesto Desde la Programatica  ".$ls_programatica_desde." hasta ".$ls_programatica_hasta;
	 $io_function_report->uf_load_seguridad_reporte("SPG","sigesp_spg_r_distribucion_trimestral_presupuesto.php",$ls_desc_event);
	////////////////////////////////         SEGURIDAD               ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//----------------------------------------------------  Parámetros del encabezado  ----------------------------------------------
		$ls_titulo="<b> DISTRIBUCION TRIMESTRAL DEL PRESUPUESTO </b> "; 	
//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
	$ls_codestpro1  = $io_funciones->uf_cerosizquierda($ls_codestpro1_min,25);
	$ls_codestpro2  = $io_funciones->uf_cerosizquierda($ls_codestpro2_min,25);
	$ls_codestpro3  = $io_funciones->uf_cerosizquierda($ls_codestpro3_min,25);
	$ls_codestpro4  = $io_funciones->uf_cerosizquierda($ls_codestpro4_min,25);
	$ls_codestpro5  = $io_funciones->uf_cerosizquierda($ls_codestpro5_min,25);
	
	$ls_codestpro1h  = $io_funciones->uf_cerosizquierda($ls_codestpro1h_max,25);
	$ls_codestpro2h  = $io_funciones->uf_cerosizquierda($ls_codestpro2h_max,25);
	$ls_codestpro3h  = $io_funciones->uf_cerosizquierda($ls_codestpro3h_max,25);
	$ls_codestpro4h  = $io_funciones->uf_cerosizquierda($ls_codestpro4h_max,25);
	$ls_codestpro5h  = $io_funciones->uf_cerosizquierda($ls_codestpro5h_max,25);
    $lb_valido=$io_report->uf_spg_reportes_comparados_distribucion_mensual_presupuesto($ls_codestpro1,$ls_codestpro2,
	                                                                                   $ls_codestpro3,$ls_codestpro4,
	                                                                                   $ls_codestpro5,$ls_codestpro1h,
	                                                                                   $ls_codestpro2h,$ls_codestpro3h,
																                       $ls_codestpro4h,$ls_codestpro5h,
																					   $ls_codfuefindes,$ls_codfuefinhas,
																					   $ls_estclades,$ls_estclahas,
																					   $ls_cuentades, $ls_cuentahas);
	 if($lb_valido==false) // Existe algún error ó no hay registros
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	 }
	 else // Imprimimos el reporte
	 {
	    error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(5.4,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
        $io_pdf->ezStartPageNumbers(980,40,10,'','',1); // Insertar el número de página
		$io_report->dts_reporte->group_noorder("programatica");
		$li_tot=$io_report->dts_reporte->getRowCount("spg_cuenta");
		$ld_total_trimestre_i=0;
		$ld_total_trimestre_ii=0;
		$ld_total_trimestre_iii=0;
		$ld_total_trimestre_iv=0;
		$ld_total_general_cuenta=0;
		
		$ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
		$ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
		$ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
		$ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
		$ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
		for($z=1;$z<=$li_tot;$z++)
		{
		    $li_tmp=($z+1);
			$thisPageNum=$io_pdf->ezPageCount;
			$ls_programatica=$io_report->dts_reporte->data["programatica"][$z];
			$ls_spg_cuenta=trim($io_report->dts_reporte->data["spg_cuenta"][$z]);
		    if ($z<$li_tot)
		    {
				$ls_programatica_next=$io_report->dts_reporte->data["programatica"][$li_tmp]; 
		    }
		    elseif($z=$li_tot)
		    {
				$ls_programatica_next='no_next';
		    }
			if(!empty($ls_programatica))
			{
				$ls_estcla=substr($ls_programatica,-1);
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
					  $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro4($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_denestpro4,$ls_estcla);
					  $ls_denestpro4=trim($ls_denestpro4);
					}
					$ls_codestpro5=substr($ls_programatica,100,25);
					if($lb_valido)
					{
					  $ls_denestpro5="";
					  $lb_valido=$io_function_report->uf_spg_reporte_select_denestpro5($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_denestpro5,$ls_estcla);
					  $ls_denestpro5=trim($ls_denestpro5);
					}
					$ls_denestpro_ant=trim($ls_denestpro1)." , ".trim($ls_denestpro2)." , ".trim($ls_denestpro3)." , ".trim($ls_denestpro4)." , ".trim($ls_denestpro5);
					$ls_programatica_ant=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2)."-".substr($ls_codestpro3,-$ls_loncodestpro3)."-".substr($ls_codestpro4,-$ls_loncodestpro4)."-".substr($ls_codestpro5,-$ls_loncodestpro5);
				}
				else
				{
					//$ls_denestpro_ant=$ls_denestpro1." , ".$ls_denestpro2." , ".$ls_denestpro3;
					$ls_denestpro_ant = array();
					$ls_denestpro_ant[0]=$ls_denestpro1;
					$ls_denestpro_ant[1]=$ls_denestpro2;
					$ls_denestpro_ant[2]=$ls_denestpro3;
					$ls_programatica_ant=substr($ls_codestpro1,-$ls_loncodestpro1).substr($ls_codestpro2,-$ls_loncodestpro2).substr($ls_codestpro3,-$ls_loncodestpro3);
				}
			}
			
			$ls_denominacion=trim($io_report->dts_reporte->data["denominacion"][$z]);
			$li_nivel=$io_report->dts_reporte->data["nivel"][$z];
			$ld_enero=$io_report->dts_reporte->data["enero"][$z];
			$ld_febrero=$io_report->dts_reporte->data["febrero"][$z];
			$ld_marzo=$io_report->dts_reporte->data["marzo"][$z];
			$ld_abril=$io_report->dts_reporte->data["abril"][$z];
			$ld_mayo=$io_report->dts_reporte->data["mayo"][$z];
			$ld_junio=$io_report->dts_reporte->data["junio"][$z];
			$ld_julio=$io_report->dts_reporte->data["julio"][$z];
			$ld_agosto=$io_report->dts_reporte->data["agosto"][$z];
			$ld_septiembre=$io_report->dts_reporte->data["septiembre"][$z];
			$ld_octubre=$io_report->dts_reporte->data["octubre"][$z];
			$ld_noviembre=$io_report->dts_reporte->data["noviembre"][$z];
			$ld_diciembre=$io_report->dts_reporte->data["diciembre"][$z];
		    $ls_status=$io_report->dts_reporte->data["status"][$z];  
			
			$ld_trimestre_i   = $ld_enero + $ld_febrero + $ld_marzo;
			$ld_trimestre_ii  = $ld_abril + $ld_mayo + $ld_junio;
			$ld_trimestre_iii = $ld_julio + $ld_agosto + $ld_septiembre;
			$ld_trimestre_iv  = $ld_octubre + $ld_noviembre + $ld_diciembre;
			
			$ld_total_cuenta=$ld_enero+$ld_febrero+$ld_marzo+$ld_abril+$ld_mayo+$ld_junio+$ld_julio+$ld_agosto+$ld_septiembre+$ld_octubre+$ld_noviembre+$ld_diciembre; 
			
			if ($ls_status=="C")
			{
				$ld_total_general_cuenta=$ld_total_general_cuenta+$ld_total_cuenta;
				$ld_total_trimestre_i = $ld_total_trimestre_i + $ld_trimestre_i;
				$ld_total_trimestre_ii = $ld_total_trimestre_ii + $ld_trimestre_ii;
				$ld_total_trimestre_iii = $ld_total_trimestre_iii + $ld_trimestre_iii;
				$ld_total_trimestre_iv = $ld_total_trimestre_iv + $ld_trimestre_iv;
			}				
			if (!empty($ls_programatica))
		    {
				$ld_total_cuenta=number_format($ld_total_cuenta,2,",",".");
				
			    $ld_trimestre_i   =number_format($ld_trimestre_i,2,",",".");
				$ld_trimestre_ii  =number_format($ld_trimestre_ii,2,",",".");
				$ld_trimestre_iii =number_format($ld_trimestre_iii,2,",",".");
				$ld_trimestre_iv  =number_format($ld_trimestre_iv,2,",",".");	
				
				 if ($ls_status=="S")
				 {
				 	$ls_spg_cuenta='<b>'.$ls_spg_cuenta.'</b>';
					$ls_denominacion='<b>'.$ls_denominacion.'</b>';
					$ld_trimestre_i='<b>'.$ld_trimestre_i.'</b>';
					$ld_trimestre_ii='<b>'.$ld_trimestre_ii.'</b>';
					$ld_trimestre_iii='<b>'.$ld_trimestre_iii.'</b>';
					$ld_trimestre_iv='<b>'.$ld_trimestre_iv.'</b>';
					$ld_total_cuenta='<b>'.$ld_total_cuenta.'</b>';
				 }			
				$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,'denominacion'=>$ls_denominacion,'trimestrei'=>$ld_trimestre_i,
				                   'trimestreii'=>$ld_trimestre_ii,'trimestreiii'=>$ld_trimestre_iii,
								   'trimestreiv'=>$ld_trimestre_iv,
								   'total'=>$ld_total_cuenta);
				
				$ld_trimestre_i    = str_replace('.','',$ld_trimestre_i);
				$ld_trimestre_i    = str_replace(',','.',$ld_trimestre_i);
				$ld_trimestre_ii   = str_replace('.','',$ld_trimestre_ii);
				$ld_trimestre_ii   = str_replace(',','.',$ld_trimestre_ii);
				$ld_trimestre_iii  = str_replace('.','',$ld_trimestre_iii);
				$ld_trimestre_iii  = str_replace(',','.',$ld_trimestre_iii);
				$ld_trimestre_iv   = str_replace('.','',$ld_trimestre_iv);
				$ld_trimestre_iv   = str_replace(',','.',$ld_trimestre_iv);

			}
			else
			{
				$ld_total_cuenta=number_format($ld_total_cuenta,2,",",".");
				
				$ld_trimestre_i   =number_format($ld_trimestre_i,2,",",".");
				$ld_trimestre_ii  =number_format($ld_trimestre_ii,2,",",".");
				$ld_trimestre_iii =number_format($ld_trimestre_iii,2,",",".");
				$ld_trimestre_iv  =number_format($ld_trimestre_iv,2,",",".");
				
				if ($ls_status=="S")
				 {
				 	$ls_spg_cuenta='<b>'.$ls_spg_cuenta.'</b>';
					$ls_denominacion='<b>'.$ls_denominacion.'</b>';
					$ld_trimestre_i='<b>'.$ld_trimestre_i.'</b>';
					$ld_trimestre_ii='<b>'.$ld_trimestre_ii.'</b>';
					$ld_trimestre_iii='<b>'.$ld_trimestre_iii.'</b>';
					$ld_trimestre_iv='<b>'.$ld_trimestre_iv.'</b>';
					$ld_total_cuenta='<b>'.$ld_total_cuenta.'</b>';
				 }		
				
				$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,'denominacion'=>$ls_denominacion,'trimestrei'=>$ld_trimestre_i,
				                   'trimestreii'=>$ld_trimestre_ii,'trimestreiii'=>$ld_trimestre_iii,
								   'trimestreiv'=>$ld_trimestre_iv,
								   'total'=>$ld_total_cuenta);
			   
				$ld_total_cuenta=str_replace('.','',$ld_total_cuenta);
				$ld_total_cuenta=str_replace(',','.',$ld_total_cuenta);
				
				$ld_trimestre_i    = str_replace('.','',$ld_trimestre_i);
				$ld_trimestre_i    = str_replace(',','.',$ld_trimestre_i);
				$ld_trimestre_ii   = str_replace('.','',$ld_trimestre_ii);
				$ld_trimestre_ii   = str_replace(',','.',$ld_trimestre_ii);
				$ld_trimestre_iii  = str_replace('.','',$ld_trimestre_iii);
				$ld_trimestre_iii  = str_replace(',','.',$ld_trimestre_iii);
				$ld_trimestre_iv   = str_replace('.','',$ld_trimestre_iv);
				$ld_trimestre_iv   = str_replace(',','.',$ld_trimestre_iv);
			}
			if (!empty($ls_programatica_next))
			{
				
				$ld_trimestre_i   =number_format($ld_trimestre_i,2,",",".");
				$ld_trimestre_ii  =number_format($ld_trimestre_ii,2,",",".");
				$ld_trimestre_iii =number_format($ld_trimestre_iii,2,",",".");
				$ld_trimestre_iv  =number_format($ld_trimestre_iv,2,",",".");
				
				if ($ls_status=="S")
				 {
				 	$ls_spg_cuenta='<b>'.$ls_spg_cuenta.'</b>';
					$ls_denominacion='<b>'.$ls_denominacion.'</b>';
					$ld_trimestre_i='<b>'.$ld_trimestre_i.'</b>';
					$ld_trimestre_ii='<b>'.$ld_trimestre_ii.'</b>';
					$ld_trimestre_iii='<b>'.$ld_trimestre_iii.'</b>';
					$ld_trimestre_iv='<b>'.$ld_trimestre_iv.'</b>';
					$ld_total_cuenta='<b>'.$ld_total_cuenta.'</b>';
				 }						
				$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,'denominacion'=>$ls_denominacion,'trimestrei'=>$ld_trimestre_i,
				                   'trimestreii'=>$ld_trimestre_ii,'trimestreiii'=>$ld_trimestre_iii,
								   'trimestreiv'=>$ld_trimestre_iv,'total'=>$ld_total_cuenta);
		        
				if($ls_tipoformato==1)
				{
				
				  $ld_total_general_cuenta=number_format($ld_total_general_cuenta,2,",","."); 
				  $ld_total_trimestre_i   = number_format($ld_total_trimestre_i,2,",",".");
			 	  $ld_total_trimestre_ii  = number_format($ld_total_trimestre_ii,2,",",".");
				  $ld_total_trimestre_iii = number_format($ld_total_trimestre_iii,2,",",".");
				  $ld_total_trimestre_iv  = number_format($ld_total_trimestre_iv,2,",",".");
				  
				  $la_data_tot[$z]=array('totalgeneral'=>'<b>TOTAL Bs</b>','trimestrei'=>$ld_total_trimestre_i,
				                         'trimestreii'=>$ld_total_trimestre_ii,'marzo'=>$ld_total_marzo,
										 'abril'=>$ld_total_abril,'mayo'=>$ld_total_mayo,
										 'trimestreiii'=>$ld_total_trimestre_iii,'trimestreiv'=>$ld_total_trimestre_iv,
										 'total'=>$ld_total_general_cuenta);
				}
				else
				{
				  /// Bolivar				 
				  $ld_total_general_cuenta=number_format($ld_total_general_cuenta,2,",",".");
				  
				  $ld_total_trimestre_i   = number_format($ld_total_trimestre_i,2,",",".");
			 	  $ld_total_trimestre_ii  = number_format($ld_total_trimestre_ii,2,",",".");
				  $ld_total_trimestre_iii = number_format($ld_total_trimestre_iii,2,",",".");
				  $ld_total_trimestre_iv  = number_format($ld_total_trimestre_iv,2,",",".");
				  
				  
				 if ($ls_status=="S")
				 {
				 	$ls_spg_cuenta='<b>'.$ls_spg_cuenta.'</b>';
					$ls_denominacion='<b>'.$ls_denominacion.'</b>';
					$ld_trimestre_i='<b>'.$ld_trimestre_i.'</b>';
					$ld_trimestre_ii='<b>'.$ld_trimestre_ii.'</b>';
					$ld_trimestre_iii='<b>'.$ld_trimestre_iii.'</b>';
					$ld_trimestre_iv='<b>'.$ld_trimestre_iv.'</b>';
					$ld_total_cuenta='<b>'.$ld_total_cuenta.'</b>';
				 }		
				  $la_data_tot[$z]=array('totalgeneral'=>'<b>TOTAL Bs</b>','trimestrei'=>$ld_total_trimestre_i,'trimestreii'=>$ld_total_trimestre_ii,                                
										 'trimestreiii'=>$ld_total_trimestre_iii,'trimestreiv'=>$ld_total_trimestre_iv,
										 'total'=>$ld_total_general_cuenta);
				}						 
				$io_cabecera=$io_pdf->openObject();
			    uf_print_cabecera($io_cabecera,$ls_programatica_ant,$ls_denestpro_ant,$io_pdf);
				$io_encabezado=$io_pdf->openObject();
				uf_print_cabecera_detalle($io_encabezado,$io_pdf);
 				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				$io_pie_pagina=$io_pdf->openObject();
				uf_print_pie_cabecera($la_data_tot,$io_pie_pagina,$io_pdf);	
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
		else
		{
			$io_pdf->ezStream();
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_function_report);
	unset($io_fecha);
?> 