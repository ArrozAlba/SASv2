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

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 07/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,30,1000,30);
		
		$io_pdf->rectangle(10,480,985,110);
		$io_pdf->addText(15,580,11,"<b>OFICINA NACIONAL DE PRESUPUESTO (ONAPRE)</b>"); // Agregar la Fecha
		$io_pdf->addText(15,565,11,"<b>OFICINA DE PLANIFICACIÓN DEL SECTOR UNIVERSITARIO (OPSU)</b>"); // Agregar la Fecha
		
		$li_tm=$io_pdf->getTextWidth(16,$as_titulo);
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,530,16,$as_titulo); // Agregar el título
		
		$io_pdf->addText(900,550,10,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(900,540,10,date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_titulo_reporte($ai_ano,$as_meses_trimestre,$as_etiqueta,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 07/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSetY(520);
		if($as_etiqueta=="Mensual")
		{
		   $ls_etiqueta="Mes";
		}
		if($as_etiqueta=="Bi-Mensual")
		{
		   $ls_etiqueta="Bimestre";
		}
		if($as_etiqueta=="Trimestral")
		{
		   $ls_etiqueta="Trimestre";
		}
		if($as_etiqueta=="Semestral")
		{
		   $ls_etiqueta="Semestre";
		}
		$la_data=array(array('name'=>'<b>Presupuesto   </b> '.'<b>'.$ai_ano.'</b>'),
		               array('name'=>'<b>'.$ls_etiqueta.'   </b>'.'<b>'.$as_meses_trimestre.'</b>'));
		$la_columna=array('name'=>'','name'=>'','name'=>'');
		$la_config =array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 8,       // Tamaño de Letras
						 'titleFontSize' => 8, // Tamaño de Letras de los títulos
						 'showLines'=>0,        // Mostrar Líneas
						 'shaded'=>0,           // Sombra entre líneas
						 'xPos'=>265,//65
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>900,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>500),
									   'name'=>array('justification'=>'left','width'=>500)));
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_titulo($as_etiqueta,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_titulo
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 07/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
        $io_pdf->ezSetDy(-20);
		if($as_etiqueta=="Mensual")
		{
		   $ls_etiqueta="Mes";
		}
		if($as_etiqueta=="Bi-Mensual")
		{
		   $ls_etiqueta="Bimestre";
		}
		if($as_etiqueta=="Trimestral")
		{
		   $ls_etiqueta="Trimestre";
		}
		if($as_etiqueta=="Semestral")
		{
		   $ls_etiqueta="Semestre";
		}
		$la_data   =array(array('name1'=>'<b>PARTIDAS</b>','name2'=>'<b>PROGRAMADO</b>',
		                        'name3'=>'<b>EJECUTADO</b>','name4'=>'<b>VARIACION '.strtoupper($ls_etiqueta).'</b>',
								'name5'=>'<b>VARIACION ACUMULADA</b>','name6'=>'<b>REVISION PROXIMO '.strtoupper($ls_etiqueta).'</b>'));
		$la_columna=array('name1'=>'','name2'=>'','name3'=>'','name4'=>'','name5'=>'','name6'=>'');
		$la_config =array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 7,       // Tamaño de Letras
						 'titleFontSize' => 7, // Tamaño de Letras de los títulos
						 'showLines'=>1,        // Mostrar Líneas
						 'shaded'=>0,           // Sombra entre líneas
						 'xPos'=>509,
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>900,
						 'cols'=>array('name1'=>array('justification'=>'center','width'=>220),// Justificación y ancho de la columna
						               'name2'=>array('justification'=>'center','width'=>170),// Justificación y ancho de la columna
									   'name3'=>array('justification'=>'center','width'=>170),// Justificación y ancho de la columna
									   'name4'=>array('justification'=>'center','width'=>170),// Justificación y ancho de la columna
									   'name5'=>array('justification'=>'center','width'=>170),// Justificación y ancho de la columna
									   'name6'=>array('justification'=>'center','width'=>85))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');

	}// end function uf_print_titulo
	//--------------------------------------------------------------------------------------------------------------------------------	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_etiqueta,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 07/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		if($as_etiqueta=="Mensual")
		{
		   $ls_etiqueta="Mes";
		}
		if($as_etiqueta=="Bi-Mensual")
		{
		   $ls_etiqueta="Bimestre";
		}
		if($as_etiqueta=="Trimestral")
		{
		   $ls_etiqueta="Trimestre";
		}
		if($as_etiqueta=="Semestral")
		{
		   $ls_etiqueta="Semestre";
		}
		$la_data=array(array('cuenta'=>'<b>Cuenta</b>','denominacion'=>'<b>Denominación</b>','pres_anual'=>'<b>'.strtoupper($ls_etiqueta).'</b>',
		                     'prog_acum'=>'<b>Acumulado</b>','monto_eject'=>'<b>'.strtoupper($ls_etiqueta).'</b>','acum_eject'=>'<b>Acumulado</b>',
							 'varia_abs'=>'<b>Absoluta</b>','varia_porc'=>'<b>Porcentaje (%)</b>','varia_abs_acum'=>'<b>Absoluta</b>',
							 'varia_porc_acum'=>'<b>Pocentaje (%)</b>','reprog_prox_mes'=>''));
		$la_columna=array('cuenta'=>'','denominacion'=>'','pres_anual'=>'','prog_acum'=>'','monto_eject'=>'','acum_eject'=>'',
		                  'varia_abs'=>'','varia_porc'=>'','varia_abs_acum'=>'','varia_porc_acum'=>'','reprog_prox_mes'=>'');
		$la_config=array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 7,       // Tamaño de Letras
						 'titleFontSize' => 7, // Tamaño de Letras de los títulos
						 'showLines'=>2,        // Mostrar Líneas
						 'shaded'=>0,           // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990,
						 'colGap'=>0,
						 //'xPos'=>520,
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
						 			   'pres_anual'=>array('justification'=>'center','width'=>85), // Justificación y ancho de la columna
						 			   'prog_acum'=>array('justification'=>'center','width'=>85), // Justificación y ancho de la columna
									   'monto_eject'=>array('justification'=>'center','width'=>85), // Justificación y ancho de la columna
									   'acum_eject'=>array('justification'=>'center','width'=>85), // Justificación y ancho de la columna
									   'varia_abs'=>array('justification'=>'center','width'=>85), // Justificación y ancho de la columna
									   'varia_porc'=>array('justification'=>'center','width'=>85), // Justificación y ancho de la columna
									   'varia_abs_acum'=>array('justification'=>'center','width'=>85), // Justificación y ancho de la columna
									   'varia_porc_acum'=>array('justification'=>'center','width'=>85), // Justificación y ancho de la columna
									   'reprog_prox_mes'=>array('justification'=>'center','width'=>85))); // Justificación y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');

	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 07/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 //'xPos'=>520,
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>150), // Justificación y ancho de la columna
						 			   'pres_anual'=>array('justification'=>'right','width'=>85), // Justificación y ancho de la columna
						 			   'prog_acum'=>array('justification'=>'right','width'=>85), // Justificación y ancho de la columna
									   'monto_eject'=>array('justification'=>'right','width'=>85), // Justificación y ancho de la columna
									   'acum_eject'=>array('justification'=>'right','width'=>85), // Justificación y ancho de la columna
									   'varia_abs'=>array('justification'=>'right','width'=>85), // Justificación y ancho de la columna
									   'varia_porc'=>array('justification'=>'right','width'=>85), // Justificación y ancho de la columna
									   'varia_abs_acum'=>array('justification'=>'right','width'=>85), // Justificación y ancho de la columna
									   'varia_porc_acum'=>array('justification'=>'right','width'=>85), // Justificación y ancho de la columna
									   'reprog_prox_mes'=>array('justification'=>'right','width'=>85))); // Justificación y ancho de la columna
		
		$la_columnas=array('cuenta'=>'<b></b>',
						   'denominacion'=>'<b></b>',
						   'pres_anual'=>'<b></b>',
						   'prog_acum'=>'<b></b>',
						   'monto_eject'=>'<b></b>',
						   'acum_eject'=>'<b></b>',
						   'varia_abs'=>'<b></b>',
						   'varia_porc'=>'<b></b>',
						   'varia_abs_acum'=>'<b></b>',
						   'varia_porc_acum'=>'<b></b>',
						   'reprog_prox_mes'=>'<b></b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($la_data_tot,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private 
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 07/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>220), // Justificación y ancho de la columna
									   'pres_anual'=>array('justification'=>'right','width'=>85), // Justificación y ancho de la columna
						 			   'prog_acum'=>array('justification'=>'right','width'=>85), // Justificación y ancho de la columna
									   'monto_eject'=>array('justification'=>'right','width'=>85), // Justificación y ancho de la columna
									   'acum_eject'=>array('justification'=>'right','width'=>85), // Justificación y ancho de la columna
									   'varia_abs'=>array('justification'=>'right','width'=>85), // Justificación y ancho de la columna
									   'varia_porc'=>array('justification'=>'right','width'=>85), // Justificación y ancho de la columna
									   'varia_abs_acum'=>array('justification'=>'right','width'=>85), // Justificación y ancho de la columna
									   'varia_porc_acum'=>array('justification'=>'right','width'=>85), // Justificación y ancho de la columna
									   'reprog_prox_mes'=>array('justification'=>'right','width'=>85))); // Justificación y ancho de la columna
		
		$la_columnas=array('total'=>'',
		                   'pres_anual'=>'',
						   'prog_acum'=>'',
						   'monto_eject'=>'',
						   'acum_eject'=>'',
						   'varia_abs'=>'',
						   'varia_porc'=>'',
						   'varia_abs_acum'=>'',
						   'varia_porc_acum'=>'',
						   'reprog_prox_mes'=>'');
		$io_pdf->ezTable($la_data_tot,$la_columnas,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad()
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: 
		//    Description: Funcion que inserta un registro en seguridad cuando se imprime el reporte por pantalla.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
	    $ls_evento      = "IMPRIMIR";
	    $ls_descripcion = "Imprimio Comparado Forma 0704 de Contabilidad Presupuestaria de Gasto";
	    $ls_variable    = $io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	    $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	    $aa_seguridad["ventanas"],$ls_descripcion);
		
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("../../shared/class_folder/class_funciones.php");
		require_once("../../shared/class_folder/class_fecha.php");
		

        
		$io_funciones = new class_funciones();			
		$io_fecha     = new class_fecha();
		$ls_tipoformato=$_GET["tipoformato"];
//-----------------------------------------------------------------------------------------------------------------------------
		global $ls_tipoformato;
        global $ld_total_pres_anual_bsf;
		global $la_data_tot_bsf;
		 if($ls_tipoformato==1)
		 {
			require_once("sigesp_spg_reporte_comparados07_bsf.php");
			$io_report    = new sigesp_spg_reporte_comparados07_bsf();
		 }
		 else
		 {
			require_once("sigesp_spg_reporte_comparados07.php");
			$io_report    = new sigesp_spg_reporte_comparados07();
		 }	
		 	
		 require_once("../../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
                      
		 $io_rcbsf= new sigesp_c_reconvertir_monedabsf();
		 $li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		 $li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		 $li_redconmon=$_SESSION["la_empresa"]["redconmon"];
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		
		$cmbnivel=$_GET["cmbnivel"];
		if($cmbnivel=="s1")
		{
          $li_cmbnivel="1";
		}
		else
		{
          $li_cmbnivel=$cmbnivel;
		}
		
		$ls_etiqueta=$_GET["txtetiqueta"];
		if($ls_etiqueta=="Mensual")
		{
			$ls_combo=$_GET["combo"];
			$ls_combomes=$_GET["combomes"];
			$li_mesdes=substr($ls_combo,0,2);
			$li_meshas=substr($ls_combomes,0,2); 
			$ls_cant_mes=1;
            $ls_meses=$io_report->uf_nombre_mes_desde_hasta($li_mesdes,$li_meshas);
			$ls_combo=$ls_combo.$ls_combomes;
		}
		else
		{
			$ls_combo=$_GET["combo"];
			$li_mesdes=substr($ls_combo,0,2);
			$li_meshas=substr($ls_combo,2,2); 
			if($ls_etiqueta=="Bi-Mensual")
			{
				$ls_cant_mes=2;
				$ls_meses=$io_report->uf_nombre_mes_desde_hasta($li_mesdes,$li_meshas);
			}
			if($ls_etiqueta=="Trimestral")
			{
				$ls_cant_mes=3;
				$ls_meses=$io_report->uf_nombre_mes_desde_hasta($li_mesdes,$li_meshas);
			}
			if($ls_etiqueta=="Semestral")
			{
				$ls_cant_mes=6;
				$ls_meses=$io_report->uf_nombre_mes_desde_hasta($li_mesdes,$li_meshas);
			}
		}
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
		$ls_titulo=" <b>RESUMEN DEL PRESUPUESTO DE GASTOS POR  PARTIDA (FORMA 0704)</b>";       
	//--------------------------------------------------------------------------------------------------------------------------------
    
	/*$lb_valido = uf_insert_seguridad();
	if ($lb_valido)
	   {*/
	     //Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
         $lb_valido=$io_report->uf_spg_reportes_comparados_forma0704($ls_combo,$li_cmbnivel,$ls_cant_mes);
	     if ($lb_valido==false) // Existe algún error ó no hay registros
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
			  $io_pdf->ezSetCmMargins(6,3,3,3); // Configuración de los margenes en centímetros
			  uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
			  $io_pdf->ezStartPageNumbers(980,40,10,'','',1); // Insertar el número de página
			  $ld_total_pres_anual=0;
			  $ld_total_programada_acumulado=0;
			  $ld_total_monto_ejecutado=0;
			  $ld_total_ejecutado_acumulado=0;
			  $ld_total_variacion_absoluta=0;
			  $ld_total_porcentaje_variacion=0;
			  $ld_total_variacion_abs_acum=0;
			  $ld_total_porcentaje_variacion_acum=0;
			  $ld_total_reprog_prox_mes=0;
			  $li_total=$io_report->dts_reporte->getRowCount("spg_cuenta");
			  for ($z=1;$z<=$li_total;$z++)
			      {
				    $thisPageNum=$io_pdf->ezPageCount;
				    $ls_spg_cuenta=$io_report->dts_reporte->data["spg_cuenta"][$z];
				    $ls_denominacion=$io_report->dts_reporte->data["denominacion"][$z];
					$li_nivel=$io_report->dts_reporte->data["nivel"][$z];
					$ld_asignado=$io_report->dts_reporte->data["asignado"][$z];
					$ld_monto_programado=$io_report->dts_reporte->data["monto_programado"][$z];
					$ld_monto_acumulado=$io_report->dts_reporte->data["monto_acumulado"][$z];
					$ld_aumdismes=$io_report->dts_reporte->data["aumdis_mes"][$z];
					$ld_aumdisacum=$io_report->dts_reporte->data["aumdis_acumulado"][$z];
					$ld_monto_ejecutado=$io_report->dts_reporte->data["ejecutado_mes"][$z];
					$ld_ejecutado_acumulado=$io_report->dts_reporte->data["ejecutado_acum"][$z];
					$ld_reprog_prox_mes=$io_report->dts_reporte->data["reprog_prox_mes"][$z];
					$ld_comprometer=$io_report->dts_reporte->data["compromiso"][$z];
					$ld_causado=$io_report->dts_reporte->data["causado"][$z];
					$ld_pagado=$io_report->dts_reporte->data["pagado"][$z];
					$ld_compr_t_ant=$io_report->dts_reporte->data["compr_t_ant"][$z];
					$ld_prog_t_ant=$io_report->dts_reporte->data["prog_t_ant"][$z];
				  
					$ld_pres_anual=$ld_monto_programado+$ld_aumdismes;  //programado de los meses
					$ld_programada_acumulado=$ld_monto_acumulado+$ld_aumdisacum;	//acumulado programado de los meses  
					$ld_monto_ejecutado=$ld_monto_ejecutado;   // monto ejecutado de los meses 
					$ld_ejecutado_acumulado=$ld_ejecutado_acumulado; // monto ejecutado acumulado de los meses 
				
					if($ld_pres_anual>$ld_monto_ejecutado)
					{
					   $ld_variacion_absoluta=0-($ld_pres_anual-$ld_monto_ejecutado); //variacion absoluta  del monto ejecutado
					}
					else
					{
					   if($ld_pres_anual==0){ $ld_variacion_absoluta=$ld_monto_ejecutado; } 
					   else { $ld_variacion_absoluta=$ld_pres_anual-$ld_monto_ejecutado;  }
					}
					//variacion porcentual  del monto ejecutado
					if($ld_pres_anual>0){ $ld_porcentaje_variacion=(($ld_pres_anual-$ld_monto_ejecutado)/$ld_pres_anual)*100; }
					else{ $ld_porcentaje_variacion=0; }
					if($ld_programada_acumulado==0)
					{
					   $ld_varia_acum=$ld_ejecutado_acumulado;
					}
					else
					{
					   $ld_varia_acum=$ld_programada_acumulado-$ld_ejecutado_acumulado;
					}
					//variacion absoluta  del monto acumulado
					if($ld_programada_acumulado>$ld_ejecutado_acumulado)
					{
					   $ld_variacion_abs_acum=0-($ld_varia_acum);
					}
					else
					{
					   $ld_variacion_abs_acum=$ld_varia_acum;
					}
					//variacion porcentual del monto acumulado
					if($ld_programada_acumulado>0)
					{ 
					  $ld_porcentaje_variacion_acum=(($ld_programada_acumulado-$ld_ejecutado_acumulado)/$ld_programada_acumulado)*100; 
					}
					else
					{ 
					  $ld_porcentaje_variacion_acum=0; 
					}
					$ld_reprog_prox_mes=$ld_reprog_prox_mes;
		
					if($li_nivel==1)
					{
						$ld_total_pres_anual=$ld_total_pres_anual+$ld_pres_anual;
						$ld_total_programada_acumulado=$ld_total_programada_acumulado+$ld_programada_acumulado;
						$ld_total_monto_ejecutado=$ld_total_monto_ejecutado+$ld_monto_ejecutado;
						$ld_total_ejecutado_acumulado=$ld_total_ejecutado_acumulado+$ld_ejecutado_acumulado;
						$ld_total_variacion_absoluta=$ld_total_variacion_absoluta+$ld_variacion_absoluta;
						$ld_total_porcentaje_variacion=$ld_total_porcentaje_variacion+$ld_porcentaje_variacion;
						$ld_total_variacion_abs_acum=$ld_total_variacion_abs_acum+$ld_variacion_abs_acum;
						$ld_total_porcentaje_variacion_acum=$ld_total_porcentaje_variacion_acum+$ld_porcentaje_variacion_acum;
						$ld_total_reprog_prox_mes=$ld_total_reprog_prox_mes+$ld_reprog_prox_mes;
					}	
					$ld_pres_anual=number_format($ld_pres_anual,2,",",".");
					$ld_programada_acumulado=number_format($ld_programada_acumulado,2,",",".");
					$ld_monto_ejecutado=number_format($ld_monto_ejecutado,2,",",".");
					$ld_ejecutado_acumulado=number_format($ld_ejecutado_acumulado,2,",",".");
					$ld_variacion_absoluta=number_format($ld_variacion_absoluta,2,",",".");
					$ld_porcentaje_variacion=number_format($ld_porcentaje_variacion,2,",",".");
					$ld_variacion_abs_acum=number_format($ld_variacion_abs_acum,2,",",".");
					$ld_porcentaje_variacion_acum=number_format($ld_porcentaje_variacion_acum,2,",",".");
					$ld_reprog_prox_mes=number_format($ld_reprog_prox_mes,2,",",".");
					
					$la_data[$z]=array('cuenta'=>$ls_spg_cuenta,'denominacion'=>$ls_denominacion,'pres_anual'=>$ld_pres_anual,
									   'prog_acum'=>$ld_programada_acumulado,'monto_eject'=>$ld_monto_ejecutado,
									   'acum_eject'=>$ld_ejecutado_acumulado,'varia_abs'=>$ld_variacion_absoluta,
									   'varia_porc'=>$ld_porcentaje_variacion,'varia_abs_acum'=>$ld_variacion_abs_acum,
									   'varia_porc_acum'=>$ld_porcentaje_variacion_acum,'reprog_prox_mes'=>$ld_reprog_prox_mes);
										  
					$ld_pres_anual=str_replace('.','',$ld_pres_anual);
					$ld_pres_anual=str_replace(',','.',$ld_pres_anual);		
					$ld_programada_acumulado=str_replace('.','',$ld_programada_acumulado);
					$ld_programada_acumulado=str_replace(',','.',$ld_programada_acumulado);		
					$ld_monto_ejecutado=str_replace('.','',$ld_monto_ejecutado);
					$ld_monto_ejecutado=str_replace(',','.',$ld_monto_ejecutado);		
					$ld_ejecutado_acumulado=str_replace('.','',$ld_ejecutado_acumulado);
					$ld_ejecutado_acumulado=str_replace(',','.',$ld_ejecutado_acumulado);
					$ld_variacion_absoluta=str_replace('.','',$ld_variacion_absoluta);
					$ld_variacion_absoluta=str_replace(',','.',$ld_variacion_absoluta);
					$ld_porcentaje_variacion=str_replace('.','',$ld_porcentaje_variacion);
					$ld_porcentaje_variacion=str_replace(',','.',$ld_porcentaje_variacion);		
					$ld_variacion_abs_acum=str_replace('.','',$ld_variacion_abs_acum);
					$ld_variacion_abs_acum=str_replace(',','.',$ld_variacion_abs_acum);	
					$ld_porcentaje_variacion_acum=str_replace('.','',$ld_porcentaje_variacion_acum);
					$ld_porcentaje_variacion_acum=str_replace(',','.',$ld_porcentaje_variacion_acum);		
					$ld_reprog_prox_mes=str_replace('.','',$ld_reprog_prox_mes);
					$ld_reprog_prox_mes=str_replace(',','.',$ld_reprog_prox_mes);		
					
					if($z==$li_total)
					{
						 if($ls_tipoformato==1)
						 {

							 $ld_total_pres_anual=number_format($ld_total_pres_anual,2,",",".");
							 $ld_total_programada_acumulado=number_format($ld_total_programada_acumulado,2,",",".");
							 $ld_total_monto_ejecutado=number_format($ld_total_monto_ejecutado,2,",",".");
							 $ld_total_ejecutado_acumulado=number_format($ld_total_ejecutado_acumulado,2,",",".");
							 $ld_total_variacion_absoluta=number_format($ld_total_variacion_absoluta,2,",",".");
							 $ld_total_porcentaje_variacion=number_format($ld_total_porcentaje_variacion,2,",",".");
							 $ld_total_variacion_abs_acum=number_format($ld_total_variacion_abs_acum,2,",",".");
							 $ld_total_porcentaje_variacion_acum=number_format($ld_total_porcentaje_variacion_acum,2,",",".");
							 $ld_total_reprog_prox_mes=number_format($ld_total_reprog_prox_mes,2,",",".");
						 
						 	 $la_data_tot[$z]=array('total'=>'<b>TOTAL BsF.</b>','pres_anual'=>$ld_total_pres_anual,'prog_acum'=>$ld_total_programada_acumulado,
													'monto_eject'=>$ld_total_monto_ejecutado,'acum_eject'=>$ld_total_ejecutado_acumulado,
													'varia_abs'=>$ld_total_variacion_absoluta,'varia_porc'=>$ld_total_porcentaje_variacion,
													'varia_abs_acum'=>$ld_total_variacion_abs_acum,'varia_porc_acum'=>$ld_total_porcentaje_variacion_acum,
													'reprog_prox_mes'=>$ld_total_reprog_prox_mes);
						}
						else
						{
							 $ld_total_pres_anual_bsf   = $io_rcbsf->uf_convertir_monedabsf($ld_total_pres_anual , $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
							 $ld_total_programada_acumulado_bsf   = $io_rcbsf->uf_convertir_monedabsf($ld_total_programada_acumulado , $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
							 $ld_total_monto_ejecutado_bsf   = $io_rcbsf->uf_convertir_monedabsf($ld_total_monto_ejecutado , $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
							 $ld_total_ejecutado_acumulado_bsf   = $io_rcbsf->uf_convertir_monedabsf($ld_total_ejecutado_acumulado , $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
							 $ld_total_variacion_absoluta_bsf   = $io_rcbsf->uf_convertir_monedabsf($ld_total_variacion_absoluta , $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
							 $ld_total_porcentaje_variacion_bsf   = $io_rcbsf->uf_convertir_monedabsf($ld_total_porcentaje_variacion , $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
							 $ld_total_variacion_abs_acum_bsf   = $io_rcbsf->uf_convertir_monedabsf($ld_total_variacion_abs_acum , $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
							 $ld_total_porcentaje_variacion_acum_bsf   = $io_rcbsf->uf_convertir_monedabsf($ld_total_porcentaje_variacion_acum , $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
							 $ld_total_reprog_prox_mes_bsf   = $io_rcbsf->uf_convertir_monedabsf($ld_total_reprog_prox_mes , $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
			 				
							 $ld_total_pres_anual=number_format($ld_total_pres_anual,2,",",".");
							 $ld_total_programada_acumulado=number_format($ld_total_programada_acumulado,2,",",".");
							 $ld_total_monto_ejecutado=number_format($ld_total_monto_ejecutado,2,",",".");
							 $ld_total_ejecutado_acumulado=number_format($ld_total_ejecutado_acumulado,2,",",".");
							 $ld_total_variacion_absoluta=number_format($ld_total_variacion_absoluta,2,",",".");
							 $ld_total_porcentaje_variacion=number_format($ld_total_porcentaje_variacion,2,",",".");
							 $ld_total_variacion_abs_acum=number_format($ld_total_variacion_abs_acum,2,",",".");
							 $ld_total_porcentaje_variacion_acum=number_format($ld_total_porcentaje_variacion_acum,2,",",".");
							 $ld_total_reprog_prox_mes=number_format($ld_total_reprog_prox_mes,2,",",".");
							 
							 $la_data_tot[$z]=array('total'=>'<b>TOTAL Bs.</b>','pres_anual'=>$ld_total_pres_anual,'prog_acum'=>$ld_total_programada_acumulado,
												'monto_eject'=>$ld_total_monto_ejecutado,'acum_eject'=>$ld_total_ejecutado_acumulado,
												'varia_abs'=>$ld_total_variacion_absoluta,'varia_porc'=>$ld_total_porcentaje_variacion,
												'varia_abs_acum'=>$ld_total_variacion_abs_acum,'varia_porc_acum'=>$ld_total_porcentaje_variacion_acum,
												'reprog_prox_mes'=>$ld_total_reprog_prox_mes);
							
							
							
							 $ld_total_pres_anual_bsf=number_format($ld_total_pres_anual_bsf,2,",",".");
							 $ld_total_programada_acumulado_bsf=number_format($ld_total_programada_acumulado_bsf,2,",",".");
							 $ld_total_monto_ejecutado_bsf=number_format($ld_total_monto_ejecutado_bsf,2,",",".");
							 $ld_total_ejecutado_acumulado_bsf=number_format($ld_total_ejecutado_acumulado_bsf,2,",",".");
							 $ld_total_variacion_absoluta_bsf=number_format($ld_total_variacion_absoluta_bsf,2,",",".");
							 $ld_total_porcentaje_variacion_bsf=number_format($ld_total_porcentaje_variacion_bsf,2,",",".");
							 $ld_total_variacion_abs_acum_bsf=number_format($ld_total_variacion_abs_acum_bsf,2,",",".");
							 $ld_total_porcentaje_variacion_acum_bsf=number_format($ld_total_porcentaje_variacion_acum_bsf,2,",",".");
							 $ld_total_reprog_prox_mes_bsf=number_format($ld_total_reprog_prox_mes_bsf,2,",",".");
							 					
    						 $la_data_tot_bsf[$z]=array('total'=>'<b>TOTAL BsF.</b>','pres_anual'=>$ld_total_pres_anual_bsf,'prog_acum'=>$ld_total_programada_acumulado_bsf,
												'monto_eject'=>$ld_total_monto_ejecutado_bsf,'acum_eject'=>$ld_total_ejecutado_acumulado_bsf,
												'varia_abs'=>$ld_total_variacion_absoluta_bsf,'varia_porc'=>$ld_total_porcentaje_variacion_bsf,
												'varia_abs_acum'=>$ld_total_variacion_abs_acum_bsf,'varia_porc_acum'=>$ld_total_porcentaje_variacion_acum_bsf,
												'reprog_prox_mes'=>$ld_total_reprog_prox_mes_bsf);
						}
			        }//if
			 }//for
			uf_print_titulo_reporte($li_ano,$ls_meses,$ls_etiqueta,$io_pdf);
			uf_print_titulo($ls_etiqueta,$io_pdf);
			uf_print_cabecera($ls_etiqueta,$io_pdf);
			uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
			uf_print_pie_cabecera($la_data_tot,$io_pdf);
			//uf_print_pie_cabecera($la_data_tot_bsf,$io_pdf);
			unset($la_data);
			unset($la_data_tot);
			unset($la_data_tot_bsf);
			if($z<$li_total)
			{
			 $io_pdf->ezNewPage(); // Insertar una nueva página
			} 
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
		}//else
	unset($io_report);
	unset($io_funciones);	
	/*   }
	else
	   {
	   
	   }*/

?> 