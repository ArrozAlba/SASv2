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
	$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],20,530,$_SESSION["ls_width"],$_SESSION["ls_height"]);    
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
				                  $as_codestpro5,$as_denestpro1,$as_denestpro2,$as_denestpro3,&$io_pdf)
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

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_detalle($io_encabezado,$ad_fecha,&$io_pdf)
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
		$la_data=array(array('cuenta'=>'<b>CODIGO</b>','denominacion'=>'<b>DENOMINACION</b>','asignado'=>'<b>PROGRAMADO</b>','trimestrei'=>'<b>TRIMESTRE 1</b>',
		                     'trimestreii'=>'<b>TRIMESTRE 2</b>','trimestreiii'=>'<b>TRIMESTRE 3</b>','trimestreiv'=>'<b>TRIMESTRE 4</b>','causado'=>'<b>TOTAL COMPROMISO</b>',
							 'ajustes'=>'<b>AJUSTE/COMP</b>','modpres'=>'<b>MODIF. PRESUP.</b>','comprometido'=>'<b>PRECOMPROMETIDO</b>','liber'=>'<b>LIBER./PRECOMPROMISO</b>','disponible'=>'<b>DISPONIBILIDAD AL: '.$ad_fecha.'</b>'));
		$la_columnas=array('cuenta'=>'','denominacion'=>'','asignado'=>'','trimestrei'=>'',
		                     'trimestreii'=>'','trimestreiii'=>'','trimestreiv'=>'','causado'=>'',
							 'ajustes'=>'','modpres'=>'','comprometido'=>'','liber'=>'','disponible'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 6,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.4,0.7,0.1),
						 'colGap'=>0.5, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>502, // Orientación de la tabla
					     'cols'=>array('cuenta'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la 
								       'denominacion'=>array('justification'=>'center','width'=>140), // Justificación y ancho 
								       'asignado'=>array('justification'=>'center','width'=>70), // Justificación y ancho 
									   'trimestrei'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
								       'trimestreii'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la 
								       'trimestreiii'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
								       'trimestreiv'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
								       'causado'=>array('justification'=>'center','width'=>70),// Justificación y ancho 
									   'ajustes'=>array('justification'=>'center','width'=>70),
									   'modpres'=>array('justification'=>'center','width'=>70),
									   'comprometido'=>array('justification'=>'center','width'=>70),
									   'liber'=>array('justification'=>'center','width'=>70),
									   'disponible'=>array('justification'=>'center','width'=>70))); 
	
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
					 'cols'=>array('cuenta'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la 
								       'denominacion'=>array('justification'=>'left','width'=>140), // Justificación y ancho 
								       'asignado'=>array('justification'=>'right','width'=>70), // Justificación y ancho 
									   'trimestrei'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
								       'trimestreii'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la 
								       'trimestreiii'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
								       'trimestreiv'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
								       'causado'=>array('justification'=>'right','width'=>70),// Justificación y ancho 
									   'ajustes'=>array('justification'=>'right','width'=>70),
									   'modpres'=>array('justification'=>'right','width'=>70),
									   'comprometido'=>array('justification'=>'right','width'=>70),
									   'liber'=>array('justification'=>'center','right'=>70),
									   'disponible'=>array('justification'=>'right','width'=>70))); // Justificación y ancho 
	
	$la_columnas=array('cuenta'=>'<b>CODIGO</b>',
	                   'denominacion'=>'<b>DENOMINACION</b>',
					   'asignado'=>'<b>PROGRAMADO</b>',
					   'trimestrei'=>'<b>TRIMESTRE 1</b>',
		               'trimestreii'=>'<b>TRIMESTRE 2</b>',
					   'trimestreiii'=>'<b>TRIMESTRE 3</b>',
					   'trimestreiv'=>'<b>TRIMESTRE 4</b>',
					   'causado'=>'<b>TOTAL COMPROMISO</b>',
					   'ajustes'=>'<b>AJUSTE/COMP</b>',
					   'modpres'=>'<b>MODIF. PRESUP.</b>',
					   'comprometido'=>'<b>PRECOMPROMETIDO</b>',
					   'liber'=>'<b>"LIBER./PRECOMPROMISO</b>',
					   'disponible'=>'<b>DISPONIBILIDAD AL: </b>');
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
/*		$ls_tipoformato=$_GET["tipoformato"];
		global $ls_tipoformato;
		global $la_data_tot_bsf;
		global $la_data_tot;
		if($ls_tipoformato==1)
		{
			require_once("sigesp_spg_reportes_class_bsf.php");
			$io_report = new sigesp_spg_reportes_class_bsf();
		}
		else
		{*/
			require_once("sigesp_spg_reportes_class.php");
			$io_report = new sigesp_spg_reportes_class();
		/*}*/	
		require_once("../../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
		$io_rcbsf= new sigesp_c_reconvertir_monedabsf();
		$li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$li_redconmon=$_SESSION["la_empresa"]["redconmon"];
//------------------------------------------------------------------------------------------------------------------------------		

//--------------------------------------------------  Parámetros para Filtar el Reporte  ------------------------------------
		$li_estmodest   = $_SESSION["la_empresa"]["estmodest"];
		$ls_codestpro1  = $_GET["codestpro1"];
		$ls_codestpro2  = $_GET["codestpro2"];
		$ls_codestpro3  = $_GET["codestpro3"];
	    $ls_estcla      = $_GET["estcla"];
	    $ld_fecha       = $_GET["fecha"];
		$ls_cuenta      = $_GET["txtcuenta"];
		if($li_estmodest==1)
		{
			$ls_codestpro4 =  "0000000000000000000000000";
			$ls_codestpro5 =  "0000000000000000000000000";		
		}
		elseif($li_estmodest==2)
		{
			$ls_codestpro4 = $_GET["codestpro4"];
			$ls_codestpro5 = $_GET["codestpro5"];
	    }	
		
	 /////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_desc_event="Solicitud de Reporte Ejecucion Financiera del Presupuesto";
	 $io_function_report->uf_load_seguridad_reporte("SPG","sigesp_spg_r_resumen_ejecucion_financiera.php",$ls_desc_event);
	////////////////////////////////         SEGURIDAD               ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//----------------------------------------------------  Parámetros del encabezado  ----------------------------------------------
		$ls_titulo="<b> RESUMEN DE EJECUCION FINANCIERA DEL PRESUPUESTO DE GASTO AL ".$ld_fecha."</b> "; 	
//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
	$ls_codestpro1  = $io_funciones->uf_cerosizquierda($ls_codestpro1,25);
	$ls_codestpro2  = $io_funciones->uf_cerosizquierda($ls_codestpro2,25);
	$ls_codestpro3  = $io_funciones->uf_cerosizquierda($ls_codestpro3,25);
	$ls_codestpro4  = $io_funciones->uf_cerosizquierda($ls_codestpro4,25);
	$ls_codestpro5  = $io_funciones->uf_cerosizquierda($ls_codestpro5,25);
	
    $lb_valido=$io_report->uf_spg_reportes_resumen_ejecucion_financiera_presupuesto($ls_codestpro1,$ls_codestpro2,
	                                                                                   $ls_codestpro3,$ls_codestpro4,
	                                                                                   $ls_codestpro5,$ld_fecha,
																					   $ls_estcla,$ls_cuenta);
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
				$ls_estcla=$io_report->dts_reporte->data["estcla"][$z];
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
			
			$ls_denominacion=trim($io_report->dts_reporte->data["denominacion"][$z]);
			$ld_asignado        =$io_report->dts_reporte->data["asignado"][$z];
			$ld_trimestre_i     =$io_report->dts_reporte->data["trimestre_i"][$z];
			$ld_trimestre_ii    =$io_report->dts_reporte->data["trimestre_ii"][$z];
			$ld_trimestre_iii   =$io_report->dts_reporte->data["trimestre_iii"][$z];
			$ld_trimestre_iv    =$io_report->dts_reporte->data["trimestre_iv"][$z];
			$ld_causado         =$io_report->dts_reporte->data["causado"][$z];
			$ld_ajuste          =$io_report->dts_reporte->data["ajustes"][$z];
			$ld_precomprometido =$io_report->dts_reporte->data["precomprometido"][$z];
			$ld_libprecomprometido =$io_report->dts_reporte->data["libprecomprometido"][$z];
			$ld_libcomprometido =$io_report->dts_reporte->data["libcomprometido"][$z];
			$ld_modpres         = " ";
			$ld_comprometido    =$ld_trimestre_i + $ld_trimestre_ii + $ld_trimestre_iii + $ld_trimestre_iv;
			$ld_liber           = "0,00";
			$ld_disponible      =$io_report->dts_reporte->data["asignado"][$z] -($ld_trimestre_i + $ld_trimestre_ii + $ld_trimestre_iii + $ld_trimestre_iv+$ld_precomprometido)+$ld_ajuste;
			
			$ld_asignado         =number_format($ld_asignado,2,",","."); 
			$ld_trimestre_i      =number_format($ld_trimestre_i,2,",",".");
			$ld_trimestre_ii     =number_format($ld_trimestre_ii,2,",",".");
			$ld_trimestre_iii    =number_format($ld_trimestre_iii,2,",",".");
			$ld_trimestre_iv     =number_format($ld_trimestre_iv,2,",",".");
			$ld_causado          =number_format($ld_causado,2,",","."); 
			$ld_ajuste		     =number_format($ld_ajuste,2,",","."); 
			$ld_comprometido     =number_format($ld_comprometido,2,",","."); 
			$ld_precomprometido  =number_format($ld_precomprometido,2,",","."); 
			$ld_disponible       =number_format($ld_disponible,2,",","."); 
			$ld_libprecomprometido=number_format($ld_libprecomprometido,2,",","."); 
			$ld_libcomprometido=number_format($ld_libcomprometido,2,",","."); 
			
			if (!empty($ls_programatica))
		    {
				$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,
	                               'denominacion'=>$ls_denominacion,
					               'asignado'=>$ld_asignado,
					               'trimestrei'=>$ld_trimestre_i,
		                           'trimestreii'=>$ld_trimestre_ii,
					               'trimestreiii'=>$ld_trimestre_iii,
					               'trimestreiv'=>$ld_trimestre_iv,
					               'causado'=>$ld_comprometido,
					               'ajustes'=>$ld_libcomprometido,
					               'modpres'=>$ld_ajuste,
					               'comprometido'=>$ld_precomprometido,
					               'liber'=>$ld_libprecomprometido,
					               'disponible'=>$ld_disponible);

			}
			else
			{			
				$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,
	                               'denominacion'=>$ls_denominacion,
					               'asignado'=>$ld_asignado,
					               'trimestrei'=>$ld_trimestre_i,
		                           'trimestreii'=>$ld_trimestre_ii,
					               'trimestreiii'=>$ld_trimestre_iii,
					               'trimestreiv'=>$ld_trimestre_iv,
					               'causado'=>$ld_comprometido,
					               'ajustes'=>$ld_libcomprometido,
					               'modpres'=>$ld_ajuste,
					               'comprometido'=>$ld_precomprometido,
					               'liber'=>$ld_libprecomprometido,
					               'disponible'=>$ld_disponible);
								   
		     }
			if (!empty($ls_programatica_next))
			{
				
				$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,
	                               'denominacion'=>$ls_denominacion,
					               'asignado'=>$ld_asignado,
					               'trimestrei'=>$ld_trimestre_i,
		                           'trimestreii'=>$ld_trimestre_ii,
					               'trimestreiii'=>$ld_trimestre_iii,
					               'trimestreiv'=>$ld_trimestre_iv,
					               'causado'=>$ld_comprometido,
					               'ajustes'=>$ld_libcomprometido,
					               'modpres'=>$ld_ajuste,
					               'comprometido'=>$ld_precomprometido,
					               'liber'=>$ld_libprecomprometido,
					               'disponible'=>$ld_disponible);
		        						 
				$io_cabecera=$io_pdf->openObject();
			    uf_print_cabecera($io_cabecera,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
				                  $ls_codestpro5,$ls_denestpro1,$ls_denestpro2,$ls_denestpro3,$io_pdf);
				$io_encabezado=$io_pdf->openObject();
				uf_print_cabecera_detalle($io_encabezado,$ld_fecha,$io_pdf);
 				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				$io_pie_pagina=$io_pdf->openObject();
				//uf_print_pie_cabecera($la_data_tot,$io_pie_pagina,$io_pdf);	
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
		elseif($li_tot>0)
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