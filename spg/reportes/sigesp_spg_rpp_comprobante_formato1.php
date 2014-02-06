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
	function uf_print_encabezado_pagina($as_titulo,$as_periodo_comp,$as_fecha_comp,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],40,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,694,11,$as_titulo); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo_comp);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,682,11,$as_periodo_comp); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(11,$as_fecha_comp);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,670,11,$as_fecha_comp); // Agregar el título
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_procede,$as_comprobante,$as_nomprobene,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_procede // procede
		//	    		   as_comprobante // comprobante
		//                 as_nomprobene   // nombre del proveedor
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 21/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>Comprobante</b>  '.$as_procede.'---'.$as_comprobante.''),
		               array('name'=>'<b>Proveedor</b>  '.$as_nomprobene.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 8, // Tamaño de Letras
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9),
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Máximo de la tabla
						 'xPos'=>299); // Orientación de la tabla 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
    
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_programatica($as_programatica,$as_denestpro,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_programatica // programatica del comprobante
		//	    		   as_denestpro5 // denominacion de la programatica del comprobante
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if ($_SESSION["la_empresa"]["estmodest"] == 2)
		{
			$la_data=array(array('name'=>'<b>Programatica</b>  '.$as_programatica.''),
		               array('name'=>'<b></b>'.$as_denestpro.''));
			$la_columna=array('name'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'showLines'=>0, // Mostrar Líneas
							 'fontSize' => 9, // Tamaño de Letras
							 'shaded'=>2, // Sombra entre líneas
							 'shadeCol'=>array(0.98,0.98,0.98), // Color de la sombra
							 'shadeCol2'=>array(0.98,0.98,0.98), // Color de la sombra
							 'xOrientation'=>'center', // Orientación de la tabla
							 'width'=>520, // Ancho de la tabla
							 'maxWidth'=>520, // Ancho Máximo de la tabla
							 'xPos'=>299); // Orientación de la tabla 
			$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
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
								 'shaded'=>2, // Sombra entre líneas
								 'fontSize' => 8, // Tamaño de Letras
								 'shadeCol'=>array(0.98,0.98,0.98), // Color de la sombra
								 'shadeCol2'=>array(0.98,0.98,0.98), // Color de la sombra
								 'xOrientation'=>'center', // Orientación de la tabla
								 'xPos'=>299, // Orientación de la tabla
								 'width'=>520, // Ancho de la tabla
								 'maxWidth'=>520);// Ancho Máximo de la tabla
	 
	 		$io_pdf->ezTable($la_datatit,$la_columnatit,'',$la_configtit);	
	 
			 $la_data=array(array('name'=>substr($as_programatica,0,$ls_loncodestpro1).'</b>','name2'=>$as_denestpro[0]),
							array('name'=>substr($as_programatica,$ls_loncodestpro1,$ls_loncodestpro2),'name2'=>$as_denestpro[1]),
							array('name'=>substr($as_programatica,$ls_loncodestpro1+$ls_loncodestpro2,$ls_loncodestpro3),'name2'=>$as_denestpro[2]));
							
			 $la_columna=array('name'=>'','name2'=>'');
			 $la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>2, // Sombra entre líneas
							 'fontSize' => 8, // Tamaño de Letras
							 'shadeCol'=>array(0.98,0.98,0.98), // Color de la sombra
							 'shadeCol2'=>array(0.98,0.98,0.98), // Color de la sombra
							 'xOrientation'=>'center', // Orientación de la tabla
							 'xPos'=>299, // Orientación de la tabla
							 'width'=>520, // Ancho de la tabla
							 'maxWidth'=>520,// Ancho Máximo de la tabla
							 'cols'=>array('name'=>array('justification'=>'right','width'=>50), // Justificación y ancho de la columna
										   'name2'=>array('justification'=>'left','width'=>480))); // Justificación y ancho de la columna
			 $io_pdf->ezTable($la_data,$la_columna,'',$la_config);		
		}		
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
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>2, // separacion entre tablas
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>299, // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'dencuenta'=>array('justification'=>'left','width'=>115), // Justificación y ancho de la columna
						 			   'descripcion'=>array('justification'=>'left','width'=>115), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'operacion'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
									   'monto'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$la_columnas=array('cuenta'=>'<b>Cuenta</b>',
						   'dencuenta'=>'<b>Denominacion Cuenta</b>',
						   'descripcion'=>'<b>Descripción Movimiento</b>',
						   'fecha'=>'<b>Fecha</b>',
						   'operacion'=>'<b>Operacion</b>',
						   'monto'=>'<b>Monto</b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_total_programatica($ad_totalprogramatica,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_total_programatica
		//		    Acess : private
		//	    Arguments : ad_totalprogramatica // Total Programatica
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por : Ing. Yesenia Moreno
		// Fecha Creación : 18/02/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if ($_SESSION["la_empresa"]["estmodest"] == 2)
		{
		 $la_data[1]=array('cuenta'=>' ','dencuenta'=>' ','descripcion'=>' ','fecha'=>' ','operacion'=>'<b>Total Programatica </b>','monto'=>$ad_totalprogramatica);
		}
		else
		{
		 $la_data[1]=array('cuenta'=>' ','dencuenta'=>' ','descripcion'=>' ','fecha'=>' ','operacion'=>'<b>Total Estructura Presupuestaria </b>','monto'=>$ad_totalprogramatica); 
		}
		$la_columnas=array('cuenta'=>'','dencuenta'=>'','descripcion'=>'','fecha'=>'','operacion'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>2, // separacion entre tablas
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>299, // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'dencuenta'=>array('justification'=>'left','width'=>70), // Justificación y ancho de la columna
						 			   'descripcion'=>array('justification'=>'left','width'=>70), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'operacion'=>array('justification'=>'right','width'=>180), // Justificación y ancho de la columna
									   'monto'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_total_programatica
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_total_comprobante($ad_totalcomprobante,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_total_programatica
		//		    Acess : private
		//	    Arguments : ad_totalcomprobante // Total Comprobante
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por : Ing. Yesenia Moreno
		// Fecha Creación : 18/02/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('cuenta'=>' ','dencuenta'=>' ','descripcion'=>' ','fecha'=>' ','operacion'=>'<b>Total Comprobante </b>','monto'=>$ad_totalcomprobante);
		$la_columnas=array('cuenta'=>'','dencuenta'=>'','descripcion'=>'','fecha'=>'','operacion'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>2, // separacion entre tablas
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>299, // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'dencuenta'=>array('justification'=>'left','width'=>115), // Justificación y ancho de la columna
						 			   'descripcion'=>array('justification'=>'left','width'=>115), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						 			   'operacion'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
									   'monto'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_total,$as_denominacion,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por : Ing. Yesenia Moreno
		// Fecha Creación : 18/02/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>550); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('total'=>'<b>Total  '.$as_denominacion.'</b>','monto'=>$ad_total));
		$la_columna=array('total'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'fontSize' => 9, // Tamaño de Letras
						 'width'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>350), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>150))); // Justificación y ancho de la columna

		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Orientación de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("sigesp_spg_funciones_reportes.php");
		$io_function_report = new sigesp_spg_funciones_reportes();
		$ls_tipoformato=$_GET["tipoformato"];
	//-----------------------------------------------------------------------------------------------------------------------------
		global $ls_tipoformato;
		global $la_data_tot_bsf;
		global $la_data_tot;
		if($ls_tipoformato==1)
		{
		    require_once("sigesp_spg_reporte_bsf.php");
			$io_report = new sigesp_spg_reporte_bsf();
		}
		else
		{
			require_once("sigesp_spg_reporte.php");
		    $io_report = new sigesp_spg_reporte();
		}	
		require_once("../../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
		$io_rcbsf= new sigesp_c_reconvertir_monedabsf();
		$li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$li_redconmon=$_SESSION["la_empresa"]["redconmon"];
		 
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	 $li_estmodest=$_SESSION["la_empresa"]["estmodest"];
	 $ls_compdes=$_GET["txtcompdes"];
	 $ls_comphas=$_GET["txtcomphas"];
	 $ls_procdes=$_GET["txtprocdes"];
	 $ls_prochas=$_GET["txtprochas"];
	 $fecdes=$_GET["txtfecdes"];
	 if (!empty($fecdes))
	 {
	     $ldt_fecdes=$io_funciones->uf_convertirdatetobd($fecdes);
	 }	else {  $ldt_fecdes=""; } 
	 $fechas=$_GET["txtfechas"];
	 if (!empty($fechas))
	 {
  	    $ldt_fechas=$io_funciones->uf_convertirdatetobd($fechas);
	 }	else {  $ldt_fechas=""; } 
	
	 $ls_orden=$_GET["rborden"];
	 /////////////////////////////////         SEGURIDAD               ///////////////////////////////////
	 $ls_desc_event="Solicitud de Reporte Comprobante Formato1 desde la fecha ".$fecdes." hasta ".$fechas." , Procede desde ".$ls_procdes." hasta ".$ls_prochas." , Comprobante desde ".$ls_compdes." hasta ".$ls_comphas;
	 $io_function_report->uf_load_seguridad_reporte("SPG","sigesp_spg_r_comprobante_formato1.php",$ls_desc_event);
	////////////////////////////////         SEGURIDAD               ///////////////////////////////////
	//----------------------------------------------------  Parámetros del encabezado  --------------------------------------------
		
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		
		$ldt_fecdes_cab=$io_funciones->uf_convertirfecmostrar(substr($ldt_fecdes,0,10));
		$ldt_fechas_cab=$io_funciones->uf_convertirfecmostrar(substr($ldt_fechas,0,10));
		
		$ls_titulo=" <b>COMPROBANTE PRESUPUESTARIO</b> ";       
		$ls_periodo_comp=" <b>Comprobante del  ".$ls_compdes."  --  ".$ls_procdes."   al  ".$ls_comphas."  --  ".$ls_prochas."  </b>  ";
		$ls_fecha_comp=" <b>Desde  ".$ldt_fecdes_cab."  al  ".$ldt_fechas_cab." </b>";
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
	 $lb_valido=$io_report->uf_spg_reporte_select_comprobante_formato1($ls_procdes,$ls_prochas,$ls_compdes,$ls_comphas,$ldt_fecdes,$ldt_fechas);
	 if($lb_valido==false) // Existe algún error ó no hay registros
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar 2');"); 
		print(" close();");
		print("</script>");
	 }
	 else // Imprimimos el reporte
	 {
	    error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(4.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_periodo_comp,$ls_fecha_comp,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$li_tot=$io_report->dts_cab->getRowCount("comprobante");
		$ld_total=0; 
	    $ld_totalcomprobante=0;
		$ld_totalprogramatica=0;
		$ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
		$ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
		$ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
		$ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
		$ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
		for($li_i=1;$li_i<=$li_tot;$li_i++)
		{
	        $io_pdf->transaction('start'); // Iniciamos la transacción
			$thisPageNum=$io_pdf->ezPageCount;
			$ls_comprobante=$io_report->dts_cab->data["comprobante"][$li_i];
			$ls_procede=$io_report->dts_cab->data["procede"][$li_i];
			$ls_ced_bene=$io_report->dts_cab->data["ced_bene"][$li_i];
			$ls_cod_pro=$io_report->dts_cab->data["cod_pro"][$li_i];
			$ls_nompro=$io_report->dts_cab->data["nompro"][$li_i];
			$ls_apebene=$io_report->dts_cab->data["apebene"][$li_i];
			$ls_nombene=$io_report->dts_cab->data["nombene"][$li_i];
			$ls_tipo_destino=$io_report->dts_cab->data["tipo_destino"][$li_i];
			$ls_codban=$io_report->dts_cab->data["codban"][$li_i];
			$ls_ctaban=$io_report->dts_cab->data["ctaban"][$li_i];
		    if($ls_tipo_destino=="P")
		    {
			    $ls_nomprobene=$ls_nompro;
		    }
			if($ls_tipo_destino=="B")
			{
				$ls_nomprobene=$ls_apebene.", ".$ls_nombene;
			}
			if($ls_tipo_destino=="-")
			{
				$ls_nomprobene="";
			}
			uf_print_cabecera($ls_procede,$ls_comprobante,$ls_nomprobene,$io_pdf); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_spg_reporte_comprobante_formato1($ls_procede,$ls_procede,$ls_comprobante,$ls_comprobante,$ldt_fecdes,
																	   $ldt_fechas,$ls_codban,$ls_ctaban);
            if($lb_valido)
			{
				$li_totrow_det=$io_report->dts_reporte->getRowCount("programatica");
				$ls_programaticaanterior='';
				$ls_denominacionanterior='';
				$li_contador=0;
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_procede=$io_report->dts_reporte->data["procede"][$li_s];
					$ls_comprobante=$io_report->dts_reporte->data["comprobante"][$li_s];
					$fecha=$io_report->dts_reporte->data["fecha"][$li_s];
					$fecha=date("Y-m-d",strtotime($fecha));
					$ldt_fecha=$io_funciones->uf_convertirfecmostrar($fecha);
					$ls_programatica=$io_report->dts_reporte->data["programatica"][$li_s];
					$ls_estcla=$io_report->dts_reporte->data["estcla"][$li_s];
		            $ls_codestpro1=substr($ls_programatica,0,25);
					$ls_codestpro2=substr($ls_programatica,25,25);
					$ls_codestpro3=substr($ls_programatica,50,25);
					$ls_denestpro1=trim($io_report->dts_reporte->data["denestpro1"][$li_s]);
					$ls_denestpro2=trim($io_report->dts_reporte->data["denestpro2"][$li_s]);
					$ls_denestpro3=trim($io_report->dts_reporte->data["denestpro3"][$li_s]);
					$ls_denestpro4=trim($io_report->dts_reporte->data["denestpro4"][$li_s]);
					$ls_denestpro5=trim($io_report->dts_reporte->data["denestpro5"][$li_s]);
					if($li_estmodest==2)
					{
						$ls_codestpro4=substr($ls_programatica,75,25);
						$ls_codestpro5=substr($ls_programatica,100,25);
						$ls_denestpro=trim($ls_denestpro1)." , ".trim($ls_denestpro2)." , ".trim($ls_denestpro3)." , ".trim($ls_denestpro4)." , ".trim($ls_denestpro5);
			            $ls_programatica=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2)."-".substr($ls_codestpro3,-$ls_loncodestpro3)."-".substr($ls_codestpro4,-$ls_loncodestpro4)."-".substr($ls_codestpro5,-$ls_loncodestpro5);
					}
					elseif($li_estmodest==1)
					{
						//$ls_denestpro=$ls_denestpro1." , ".$ls_denestpro2." , ".$ls_denestpro3;
						$ls_denestpro = array();
						$ls_denestpro[0]=$ls_denestpro1;
				        $ls_denestpro[1]=$ls_denestpro2;
				        $ls_denestpro[2]=$ls_denestpro3;
						$ls_programatica=substr($ls_codestpro1,-$ls_loncodestpro1).substr($ls_codestpro2,-$ls_loncodestpro2).substr($ls_codestpro3,-$ls_loncodestpro3);
					}
					$ls_spg_cuenta=$io_report->dts_reporte->data["spg_cuenta"][$li_s];
					$ls_documento=$io_report->dts_reporte->data["documento"][$li_s];
					$ls_operacion=$io_report->dts_reporte->data["operacion"][$li_s];
					$ls_descripcion=trim($io_report->dts_reporte->data["descripcion"][$li_s]);
					$ld_monto=$io_report->dts_reporte->data["monto"][$li_s];
					$ls_orden=$io_report->dts_reporte->data["orden"][$li_s];
					$ls_dencuenta=trim($io_report->dts_reporte->data["dencuenta"][$li_s]);
					$ls_denoperacion=$io_report->dts_reporte->data["denoperacion"][$li_s];
					$ls_tipo_destino=$io_report->dts_reporte->data["tipo_destino"][$li_s];
					$ls_cod_pro=$io_report->dts_reporte->data["cod_pro"][$li_s];
					$ls_ced_bene=$io_report->dts_reporte->data["ced_bene"][$li_s];
					$ls_nompro=$io_report->dts_reporte->data["nompro"][$li_s];
					$ls_apebene=$io_report->dts_reporte->data["apebene"][$li_s];
					$ls_nombene=$io_report->dts_reporte->data["nombene"][$li_s];

					if ($ls_programatica.$ls_estcla==$ls_programaticaanterior)
					{
						$ld_totalprogramatica=$ld_totalprogramatica+$ld_monto;
						$li_contador++;
					}
					else
					{
						if ($ls_programaticaanterior!='')
						{
							uf_print_cabecera_programatica(substr($ls_programaticaanterior,0,strlen($ls_programaticaanterior)-1),$ls_denominacionanterior,$io_pdf); // Imprimimos la cabecera del registro
							uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
							if($ld_totalprogramatica<0)
							{
							  $ld_monto_positivo=abs($ld_totalprogramatica);
							  $ld_totalprogramatica=number_format($ld_monto_positivo,2,",",".");
							  $ld_totalprogramatica="(".$ld_totalprogramatica.")";
							}
							else
							{
							   $ld_totalprogramatica=number_format($ld_totalprogramatica,2,",",".");
							}
							//$ld_totalprogram=$ld_totalprogramatica;
							uf_print_total_programatica($ld_totalprogramatica,$io_pdf); // Imprimimos el total programatica
							unset($la_data);
						}
						$ls_programaticaanterior=$ls_programatica.$ls_estcla;
						$ls_denominacionanterior=$ls_denestpro;
						$ld_totalprogramatica=$ld_monto;
						$li_contador=1;
					}
					$ld_totalcomprobante=$ld_totalcomprobante+$ld_monto;
					$ld_total=$ld_total+$ld_monto;
					
					if($ld_monto<0)
					{
					  $ld_monto_positivo=abs($ld_monto);
					  $ld_monto=number_format($ld_monto_positivo,2,",",".");
					  $ld_monto="(".$ld_monto.")";
					}
					else
					{
					  $ld_monto=number_format($ld_monto,2,",",".");
					}
					$la_data[$li_contador]=array('cuenta'=>$ls_spg_cuenta,'dencuenta'=>$ls_dencuenta,'descripcion'=>$ls_descripcion,
					                      'fecha'=>$ldt_fecha,'operacion'=>$ls_denoperacion,'monto'=>$ld_monto);
					$ld_monto=str_replace('.','',$ld_monto);
					$ld_monto=str_replace(',','.',$ld_monto);
				}
			    uf_print_cabecera_programatica($ls_programatica,$ls_denestpro,$io_pdf); // Imprimimos la cabecera del registro
			    uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
				if($ld_totalprogramatica<0)
				{
				  $ld_monto_positivo=abs($ld_totalprogramatica);
				  $ld_totalprogramatica=number_format($ld_monto_positivo,2,",",".");
				  $ld_totalprogramatica="(".$ld_totalprogramatica.")";
				}
				else
				{
			       $ld_totalprogramatica=number_format($ld_totalprogramatica,2,",",".");
				}
                $ld_totalprogram=$ld_totalprogramatica;
			    uf_print_total_programatica($ld_totalprogramatica,$io_pdf); // Imprimimos el total programatica
				if($ld_totalcomprobante<0)
				{
				  $ld_monto_positivo=abs($ld_totalcomprobante);
				  $ld_totalcomprobante=number_format($ld_monto_positivo,2,",",".");
				  $ld_totalcomprobante="(".$ld_totalcomprobante.")";
				}
				else
				{
			       $ld_totalcomprobante=number_format($ld_totalcomprobante,2,",",".");
				}
			    $ld_totalcomprob=$ld_totalcomprobante;
			    uf_print_total_comprobante($ld_totalcomprobante,$io_pdf); // Imprimimos el total comprobante
			    $ld_totalcomprobante=0;
		        $ld_totalprogramatica=0;
 			}
          	if ($io_pdf->ezPageCount==$thisPageNum)
			{// Hacemos el commit de los registros que se desean imprimir
            	$io_pdf->transaction('commit');
          	}
			elseif($thisPageNum>1)
			{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
				$io_pdf->transaction('rewind');
				$io_pdf->ezNewPage(); // Insertar una nueva página
			    uf_print_cabecera($ls_procede,$ls_comprobante,$ls_nomprobene,$io_pdf); // Imprimimos la cabecera del registro
				uf_print_cabecera_programatica($ls_programatica,$ls_denestpro,$io_pdf); // Imprimimos la cabecera del registro
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
				uf_print_total_programatica($ld_totalprogram,$io_pdf); // Imprimimos el total programatica
			    uf_print_total_comprobante($ld_totalcomprob,$io_pdf); // Imprimimos el total comprobante
			    $ld_totalcomprob=0;
		        $ld_totalprogram=0;
			}
			if($li_i==$li_tot)
			{
				  if($ls_tipoformato==1)
				  {
				  		$ld_total=number_format($ld_total,2,",",".");
			  			//uf_print_pie_cabecera($ld_total,'Bs.F.',$io_pdf); // Imprimimos pie de la cabecera
				
				  }
				  else
				  {     $ld_total_bsf=$io_rcbsf->uf_convertir_monedabsf($ld_total, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
				  		$ld_total=number_format($ld_total,2,",",".");
			  			uf_print_pie_cabecera($ld_total,'Bs.',$io_pdf); // Imprimimos pie de la cabecera
						
						/*$ld_total_bsf=number_format($ld_total_bsf,2,",",".");
			  			uf_print_pie_cabecera($ld_total_bsf,'Bs.F.',$io_pdf); // Imprimimos pie de la cabecera*/
				  }
			}
			unset($la_data);			
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
?> 