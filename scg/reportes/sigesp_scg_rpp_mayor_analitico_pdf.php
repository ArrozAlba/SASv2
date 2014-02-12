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
	//ini_set('memory_limit','2048M');
	//ini_set('max_execution_time','0');
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_scg;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_scg->uf_load_seguridad_reporte("SCG","sigesp_scg_r_mayor_analitico.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

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
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 21/04/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(10,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,720,10,$as_titulo); // Agregar el título

		$io_pdf->addText(500,730,7,$_SESSION["ls_database"]); // Agregar la Base de datos
		$io_pdf->addText(500,720,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(500,710,8,date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_cuenta,$as_denominacion,$ad_saldo_ant,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private
		//	    Arguments: as_cuenta // cuenta
		//	    		   as_denominacion // denominacion
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 18/05/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$la_data=array(array('name'=>'<b>Cuenta</b> '.$as_cuenta.'  -----  '.$as_denominacion.''),
		               array('name'=>'<b>Saldo Anterior '.$ls_bolivares.'</b> '.$ad_saldo_ant.' '));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'fontSize' => 7, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9),
						 'shadeCo2'=>array(0.9,0.9,0.9),
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf,$li_ocultar)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 18/05/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($li_ocultar==1)
		{
			$la_config=array('showHeadings'=>1, // Mostrar encabezados
							 'fontSize' => 6, // Tamaño de Letras
							 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'colGap'=>0, // separacion entre tablas
							 'width'=>550, // Ancho de la tabla
							 'maxWidth'=>550, // Ancho Máximo de la tabla
							 'xPos'=>299, // Orientación de la tabla
							 'cols'=>array('procede'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
										   'comprobante'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
										   'nombre'=>array('justification'=>'left','width'=>75), // Justificación y ancho de la columna
										   'documento'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
										   'fecha'=>array('justification'=>'center','width'=>40), // Justificación y ancho de la columna
										   'debe'=>array('justification'=>'right','width'=>75), // Justificación y ancho de la columna
										   'haber'=>array('justification'=>'right','width'=>75), // Justificación y ancho de la columna
										   'saldo'=>array('justification'=>'right','width'=>75))); // Justificación y ancho de la columna
	
			$la_columnas=array('procede'=>'<b>Procede</b>',
							   'comprobante'=>'<b>Comprobante</b>',
							   'nombre'=>'<b>Beneficiario</b>',
							   'documento'=>'<b>Documento</b>',
							   'fecha'=>'<b>Fecha</b>',
							   'debe'=>'<b>Debe</b>',
							   'haber'=>'<b>Haber</b>',
							   'saldo'=>'<b>Saldo Actual</b>');
		}
		else
		{
			$la_config=array('showHeadings'=>1, // Mostrar encabezados
							 'fontSize' => 6, // Tamaño de Letras
							 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'colGap'=>0, // separacion entre tablas
							 'width'=>550, // Ancho de la tabla
							 'maxWidth'=>550, // Ancho Máximo de la tabla
							 'xPos'=>299, // Orientación de la tabla
							 'cols'=>array('procede'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
										   'comprobante'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
										   'concepto'=>array('justification'=>'left','width'=>75), // Justificación y ancho de la columna
										   'nombre'=>array('justification'=>'left','width'=>75), // Justificación y ancho de la columna
										   'documento'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
										   'fecha'=>array('justification'=>'center','width'=>40), // Justificación y ancho de la columna
										   'debe'=>array('justification'=>'right','width'=>75), // Justificación y ancho de la columna
										   'haber'=>array('justification'=>'right','width'=>75), // Justificación y ancho de la columna
										   'saldo'=>array('justification'=>'right','width'=>75))); // Justificación y ancho de la columna
	
			$la_columnas=array('procede'=>'<b>Procede</b>',
							   'comprobante'=>'<b>Comprobante</b>',
							   'concepto'=>'<b>Concepto</b>',
							   'nombre'=>'<b>Beneficiario</b>',
							   'documento'=>'<b>Documento</b>',
							   'fecha'=>'<b>Fecha</b>',
							   'debe'=>'<b>Debe</b>',
							   'haber'=>'<b>Haber</b>',
							   'saldo'=>'<b>Saldo Actual</b>');
		}
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_totaldebe,$ad_totalhaber,$ad_totalsaldo,&$io_pdf,$li_ocultar)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 18/05/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		if($li_ocultar==1)
		{
			$la_data=array(array('name'=>'-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------'));
			$la_columna=array('name'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'fontSize' => 7, // Tamaño de Letras
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'xOrientation'=>'center', // Orientación de la tabla
							 'xPos'=>310, // Orientación de la tabla
							 'width'=>550); // Ancho Máximo de la tabla
			$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
			$la_data=array(array('total'=>'<b><i>Total '.$ls_bolivares.' </i></b>','debe'=>$ad_totaldebe,'haber'=>$ad_totalhaber,'saldo'=>$ad_totalsaldo));
			$la_columna=array('total'=>'','debe'=>'','haber'=>'','saldo'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'fontSize' => 7, // Tamaño de Letras
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'width'=>550, // Ancho Máximo de la tabla
							 'xOrientation'=>'center', // Orientación de la tabla
							 'xPos'=>313, // Orientación de la tabla
							 'cols'=>array('total'=>array('justification'=>'right','width'=>250), // Justificación y ancho de la columna
										   'debe'=>array('justification'=>'right','width'=>75), // Justificación y ancho de la columna
										   'haber'=>array('justification'=>'right','width'=>75), // Justificación y ancho de la columna
										   'saldo'=>array('justification'=>'right','width'=>75))); // Justificación y ancho de la columna
	
			$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
			$la_data=array(array('name'=>''));
			$la_columna=array('name'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'width'=>530, // Ancho Máximo de la tabla
							 'xOrientation'=>'center'); // Orientación de la tabla
		}
		else
		{		
			$la_data=array(array('name'=>'-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------'));
			$la_columna=array('name'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'fontSize' => 7, // Tamaño de Letras
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'xOrientation'=>'center', // Orientación de la tabla
							 'xPos'=>310, // Orientación de la tabla
							 'width'=>550); // Ancho Máximo de la tabla
			$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
			$la_data=array(array('total'=>'<b><i>Total '.$ls_bolivares.' </i></b>','debe'=>$ad_totaldebe,'haber'=>$ad_totalhaber,'saldo'=>$ad_totalsaldo));
			$la_columna=array('total'=>'','debe'=>'','haber'=>'','saldo'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'fontSize' => 7, // Tamaño de Letras
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'width'=>550, // Ancho Máximo de la tabla
							 'xOrientation'=>'center', // Orientación de la tabla
							 'xPos'=>313, // Orientación de la tabla
							 'cols'=>array('total'=>array('justification'=>'right','width'=>325), // Justificación y ancho de la columna
										   'debe'=>array('justification'=>'right','width'=>75), // Justificación y ancho de la columna
										   'haber'=>array('justification'=>'right','width'=>75), // Justificación y ancho de la columna
										   'saldo'=>array('justification'=>'right','width'=>75))); // Justificación y ancho de la columna
	
			$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
			$la_data=array(array('name'=>''));
			$la_columna=array('name'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'width'=>530, // Ancho Máximo de la tabla
							 'xOrientation'=>'center'); // Orientación de la tabla
		}
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_total_pie_cabecera($ad_montototaldebe,$ad_montototalhaber,$ad_fechasta,&$io_pdf,$li_ocultar)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 18/05/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		if($li_ocultar==1)
		{
			
			$la_data=array(array('name'=>'-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------'));
			$la_columna=array('name'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'fontSize' => 7, // Tamaño de Letras
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'xOrientation'=>'center', // Orientación de la tabla
							 'xPos'=>310, // Orientación de la tabla
							 'width'=>550); // Ancho Máximo de la tabla
			$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
				
			$la_data=array(array('total'=>'<b><i>Total General al  '.$ad_fechasta.'   '.$ls_bolivares.' </i></b>','debe'=>$ad_montototaldebe,'haber'=>$ad_montototalhaber));
			$la_columna=array('total'=>'','debe'=>'','haber'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'fontSize' => 8, // Tamaño de Letras
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'width'=>550, // Ancho Máximo de la tabla
							 'xOrientation'=>'center', // Orientación de la tabla
							 'xPos'=>374, // Orientación de la tabla
							 'cols'=>array('total'=>array('justification'=>'center','width'=>125), // Justificación y ancho de la columna
										   'debe'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la columna
										   'haber'=>array('justification'=>'left','width'=>80))); // Justificación y ancho de la columna
	
			$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
			$la_data=array(array('name'=>''));
			$la_columna=array('name'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'width'=>530, // Ancho Máximo de la tabla
							 'xOrientation'=>'center'); // Orientación de la tabla
		}
		else
		{
			 
			$la_data=array(array('name'=>'-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------'));
			$la_columna=array('name'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'fontSize' => 7, // Tamaño de Letras
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'xOrientation'=>'center', // Orientación de la tabla
							 'xPos'=>310, // Orientación de la tabla
							 'width'=>550); // Ancho Máximo de la tabla
			$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
				
			$la_data=array(array('total'=>'<b><i>Total General al  '.$ad_fechasta.'   '.$ls_bolivares.' </i></b>','debe'=>$ad_montototaldebe,'haber'=>$ad_montototalhaber));
			$la_columna=array('total'=>'','debe'=>'','haber'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'fontSize' => 8, // Tamaño de Letras
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'width'=>550, // Ancho Máximo de la tabla
							 'xOrientation'=>'center', // Orientación de la tabla
							 'xPos'=>374, // Orientación de la tabla
							 'cols'=>array('total'=>array('justification'=>'center','width'=>200), // Justificación y ancho de la columna
										   'debe'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la columna
										   'haber'=>array('justification'=>'left','width'=>80))); // Justificación y ancho de la columna
	
			$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
			$la_data=array(array('name'=>''));
			$la_columna=array('name'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'showLines'=>0, // Mostrar Líneas
							 'shaded'=>0, // Sombra entre líneas
							 'width'=>530, // Ancho Máximo de la tabla
							 'xOrientation'=>'center'); // Orientación de la tabla
		}
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_init_niveles()
	{	///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_init_niveles
		//	     Access: public
		//	    Returns: vacio	 
		//	Description: Este método realiza una consulta a los formatos de las cuentas
		//               para conocer los niveles de la escalera de las cuentas contables  
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones,$ia_niveles_scg;
		
		$ls_formato=""; $li_posicion=0; $li_indice=0;
		$dat_emp=$_SESSION["la_empresa"];
		//contable
		$ls_formato = trim($dat_emp["formcont"])."-";
		$li_posicion = 1 ;
		$li_indice   = 1 ;
		$li_posicion = $io_funciones->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		do
		{
			$ia_niveles_scg[$li_indice] = $li_posicion;
			$li_indice   = $li_indice+1;
			$li_posicion = $io_funciones->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		} while ($li_posicion>=0);
	}// end function uf_init_niveles
	//-----------------------------------------------------------------------------------------------------------------------------------

		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
		require_once("../class_funciones_scg.php");
		$io_fun_scg=new class_funciones_scg();
		$ls_tiporeporte="0";
		$ls_bolivares="";
		if (array_key_exists("tiporeporte",$_GET))
		{
			$ls_tiporeporte=$_GET["tiporeporte"];
		}
		switch($ls_tiporeporte)
		{
			case "0":
				require_once("sigesp_scg_reporte.php");
				$io_report  = new sigesp_scg_reporte();
				$ls_bolivares ="Bs.";
				break;
	
			case "1":
				require_once("sigesp_scg_reportebsf.php");
				$io_report  = new sigesp_scg_reportebsf();
				$ls_bolivares ="Bs.F.";
				break;
		}
		$ia_niveles_scg[0]="";			
		uf_init_niveles();
		$li_total=count($ia_niveles_scg)-1;
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
		$ld_fecdesde=$_GET["fecdes"];
		$ld_fechasta=$_GET["fechas"];
		$ls_cuentadesde_min=$_GET["cuentadesde"];
		$ls_cuentahasta_max=$_GET["cuentahasta"];
		if(($ls_cuentadesde_min=="")&&($ls_cuentahasta_max==""))
		{
		   if($io_report->uf_spg_reporte_select_cuenta($ls_cuentadesde_min,$ls_cuentahasta_max))
		   {
		     $ls_cuentadesde=$ls_cuentadesde_min;
		     $ls_cuentahasta=$ls_cuentahasta_max;
		   } 
		}
		else
		{
		     $ls_cuentadesde=$ls_cuentadesde_min;
		     $ls_cuentahasta=$ls_cuentahasta_max;
		}
		$ls_orden=$_GET["orden"];
		switch ($ls_orden){
			case 'COM':
				$ls_parm_orden=" scg_dt_cmp.comprobante ";
				break;
			case 'FEC':
				$ls_parm_orden=" scg_dt_cmp.fecha ";
				break;
			case 'CTA':	
				$ls_parm_orden=" scg_dt_cmp.sc_cuenta ";
				break;				
		}
		$li_ocultar=$_GET["ocultar"];

	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
		$ldt_fecha="<b> Desde   ".$ld_fecdesde."   al   ".$ld_fechasta." </b>" ;
		$ls_titulo="<b> Mayor  Analitico</b>  ".$ldt_fecha;       
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
	$lb_valido=uf_insert_seguridad("<b>Mayor Analítico en PDF</b>"); // Seguridad de Reporte
	if($lb_valido)
	{ 
		 $lb_valido=$io_report->uf_cargar_mayor_analitico($ld_fecdesde,$ld_fechasta,$ls_cuentadesde,$ls_cuentahasta,$ls_parm_orden);
    }
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
		set_time_limit(3600);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
		
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1);
		$io_report->ds_analitico->group_noorder("sc_cuenta");
		$li_tot=$io_report->ds_analitico->getRowCount("sc_cuenta");
		$ld_totaldebe=0;
		$ld_totalhaber=0;
		$ld_totalsaldo=0;
        $ld_saldo=0;
		$ldec_mondeb=0;
        $ldec_monhab=0;
		$ld_montototaldebe=0;
		$ld_montototalhaber=0;
		
		for($i=1;$i<=$li_tot;$i++)
		{
		    $li_tmp=($i+1);
		   // $io_pdf->transaction('start'); // Iniciamos la transacción
			$thisPageNum=$io_pdf->ezPageCount;
			$ls_comprobante=$io_report->ds_analitico->getValue("comprobante",$i);
			$ls_cuenta=trim($io_report->ds_analitico->getValue("sc_cuenta",$i));
		    if ($i<$li_tot)
		    {
				$ls_cuenta_next=trim($io_report->ds_analitico->getValue("sc_cuenta",$li_tmp));
		    }
		    elseif($i=$li_tot)
		    {
				$ls_cuenta_next='no_next';
		    }
			if(!empty($ls_cuenta))
			{
			  $ls_cuenta_ant=trim($io_report->ds_analitico->getValue("sc_cuenta",$i));
			}
			$ls_denominacion=$io_report->ds_analitico->getValue("denominacion",$i);

			$ls_codpro=$io_report->ds_analitico->getValue("cod_pro",$i);
			$ls_cedbene=$io_report->ds_analitico->getValue("ced_bene",$i);
			$ls_nompro=$io_report->ds_analitico->getValue("nompro",$i);
			$ls_nombene=$io_report->ds_analitico->getValue("apebene",$i).", ".$io_report->ds_analitico->getValue("nombene",$i);
			$ls_nombre="";
			if($ls_codpro!="----------")
			{
				$ls_nombre=$ls_nompro;
			}			
			if($ls_cedbene!="----------")
			{
				$ls_nombre=$ls_nombene;
			}			
			$ls_documento=$io_report->ds_analitico->getValue("documento",$i);
			$ls_procede=$io_report->ds_analitico->getValue("procede",$i);
			$ls_concepto=$io_report->ds_analitico->getValue("descripcion",$i);
			$ldec_monto=$io_report->ds_analitico->getValue("monto",$i);
			$fecmov=$io_report->ds_analitico->getValue("fecha",$i);
			$ld_fecmov=$io_funciones->uf_convertirfecmostrar($fecmov);
			$ls_debhab=$io_report->ds_analitico->getValue("debhab",$i);
			$ld_saldo_ant=$io_report->ds_analitico->getValue("saldo_ant",$i);
			if($ls_debhab=='D')
			{
				$ldec_mondeb=$ldec_monto;
				$ldec_monhab=0;		
				$ld_totaldebe=$ld_totaldebe+$ldec_mondeb;
						
			}
			elseif($ls_debhab=='H')
			{
				$ldec_monhab=$ldec_monto;		
				$ldec_mondeb=0;
				$ld_totalhaber=$ld_totalhaber+$ldec_monhab;
				
			}
			if (!empty($ls_cuenta))
			{
			  $ld_saldo=$ld_saldo_ant+$ldec_mondeb-$ldec_monhab;
			}
			else
			{
				if($ls_debhab=='D')
				{
					$ld_saldo=$ld_saldo+$ldec_monto;
				}
				elseif($ls_debhab=='H')
				{
					$ld_saldo=$ld_saldo-$ldec_monto;
				}
			}
			if (!empty($ls_cuenta))
		    {
				$ldec_mondeb=abs($ldec_mondeb);
				$ldec_monhab=abs($ldec_monhab);
				
				$ldec_mondeb=number_format($ldec_mondeb,2,",",".");
				$ldec_monhab=number_format($ldec_monhab,2,",",".");
				if($ld_saldo<0)
				{
				  $ld_saldo_aux=abs($ld_saldo); 
				  $ld_saldo_aux=number_format($ld_saldo_aux,2,",",".");
				  $ld_saldo_final="(".$ld_saldo_aux.")";
				}
				else
				{
				  $ld_saldo_aux=number_format($ld_saldo,2,",",".");
				  $ld_saldo_final=$ld_saldo_aux;
				}
				$la_data[$i]=array('procede'=>$ls_procede,'comprobante'=>$ls_comprobante,'concepto'=>$ls_concepto,
								   'nombre'=>$ls_nombre,'documento'=>$ls_documento,'fecha'=>$ld_fecmov,'debe'=>$ldec_mondeb,
								   'haber'=>$ldec_monhab,'saldo'=>$ld_saldo_final);
			   
				$ldec_mondeb=str_replace('.','',$ldec_mondeb);
				$ldec_mondeb=str_replace(',','.',$ldec_mondeb);		
				$ldec_monhab=str_replace('.','',$ldec_monhab);
				$ldec_monhab=str_replace(',','.',$ldec_monhab);		
			}
			else
			{
				$ldec_mondeb=abs($ldec_mondeb);
				$ldec_monhab=abs($ldec_monhab);
				
				$ldec_mondeb=number_format($ldec_mondeb,2,",",".");
				$ldec_monhab=number_format($ldec_monhab,2,",",".");
				
				if($ld_saldo<0)
				{
				  $ld_saldo_aux=abs($ld_saldo); 
				  $ld_saldo_aux=number_format($ld_saldo_aux,2,",",".");
				  $ld_saldo_final="(".$ld_saldo_aux.")";
				}
				else
				{
				  $ld_saldo_aux=number_format($ld_saldo,2,",",".");
				  $ld_saldo_final=$ld_saldo_aux;
				}
				$la_data[$i]=array('procede'=>$ls_procede,'comprobante'=>$ls_comprobante,'concepto'=>$ls_concepto,
								   'nombre'=>$ls_nombre,'documento'=>$ls_documento,'fecha'=>$ld_fecmov,'debe'=>$ldec_mondeb,
								   'haber'=>$ldec_monhab,'saldo'=>$ld_saldo_final);
			   
				$ldec_mondeb=str_replace('.','',$ldec_mondeb);
				$ldec_mondeb=str_replace(',','.',$ldec_mondeb);		
				$ldec_monhab=str_replace('.','',$ldec_monhab);
				$ldec_monhab=str_replace(',','.',$ldec_monhab);		
			}
			if (!empty($ls_cuenta_next))
			{
				$ldec_mondeb=abs($ldec_mondeb);
				$ldec_monhab=abs($ldec_monhab);
				$ldec_mondeb=number_format($ldec_mondeb,2,",",".");
				$ldec_monhab=number_format($ldec_monhab,2,",",".");
				if($ld_saldo<0)
				{
				  $ld_saldo_aux=abs($ld_saldo); 
				  $ld_saldo_aux=number_format($ld_saldo_aux,2,",",".");
				  $ld_saldo_final="(".$ld_saldo_aux.")";
				}
				else
				{
				  $ld_saldo_aux=number_format($ld_saldo,2,",",".");
				  $ld_saldo_final=$ld_saldo_aux;
				}
				$la_data[$i]=array('procede'=>$ls_procede,'comprobante'=>$ls_comprobante,'concepto'=>$ls_concepto,
								   'nombre'=>$ls_nombre,'documento'=>$ls_documento,'fecha'=>$ld_fecmov,'debe'=>$ldec_mondeb,
								   'haber'=>$ldec_monhab,'saldo'=>$ld_saldo_final);
				$ld_saldo_ant=number_format($ld_saldo_ant,2,",",".");
				$ld_saldo_anterior=$ld_saldo_ant;
				$li_totfil=0;
				$as_cuenta="";
				for($li=$li_total;$li>1;$li--)
				{
					$li_ant=$ia_niveles_scg[$li-1];
					$li_act=$ia_niveles_scg[$li];
					$li_fila=$li_act-$li_ant;
					$li_len=strlen($ls_cuenta_ant);
					$li_totfil=$li_totfil+$li_fila;
					$li_inicio=$li_len-$li_totfil;
					if($li==$li_total)
					{
						$as_cuenta=substr($ls_cuenta_ant,$li_inicio,$li_fila);
					}
					else
					{
						$as_cuenta=substr($ls_cuenta_ant,$li_inicio,$li_fila)."-".$as_cuenta;
					}
				}
				$li_fila=$ia_niveles_scg[1]+1;
				$as_cuenta=substr($ls_cuenta_ant,0,$li_fila)."-".$as_cuenta;


			    uf_print_cabecera($as_cuenta,$ls_denominacion,$ld_saldo_ant,$io_pdf);
				uf_print_detalle($la_data,$io_pdf,$li_ocultar); // Imprimimos el detalle 
			  	$ld_totalsaldo_final=$ld_saldo_final;
				if($ld_totaldebe<0)
				{
			       $ld_totaldebe_aux=abs($ld_totaldebe);
				   $ld_totaldebe_aux=number_format($ld_totaldebe_aux,2,",",".");
				   $ld_totaldebe="(".$ld_totaldebe_aux.")";
				}
				else
				{
				  $ld_totaldebe=number_format($ld_totaldebe,2,",",".");
				}
				if($ld_totalhaber<0)
				{
			       $ld_totalhaber_aux=abs($ld_totalhaber);
				   $ld_totalhaber_aux=number_format($ld_totalhaber_aux,2,",",".");
				   $ld_totalhaber="(".$ld_totalhaber_aux.")";
				}
				else
				{
				  $ld_totalhaber=number_format($ld_totalhaber,2,",",".");
				}
				uf_print_pie_cabecera($ld_totaldebe,$ld_totalhaber,$ld_totalsaldo_final,$io_pdf,$li_ocultar);
				
				$ld_totalde=$ld_totaldebe;
				$ld_totalha=$ld_totalhaber;
				$ld_totalsal=$ld_totalsaldo_final;
				$ld_totaldebe=str_replace('.','',$ld_totaldebe);
				$ld_totaldebe=str_replace(',','.',$ld_totaldebe);
				$ld_totalhaber=str_replace('.','',$ld_totalhaber);
				$ld_totalhaber=str_replace(',','.',$ld_totalhaber);
								
				$ld_montototaldebe=$ld_montototaldebe+$ld_totaldebe;
				$ld_montototalhaber=$ld_montototalhaber+$ld_totalhaber;
				
				$ld_totaldebe=0;
				$ld_totalhaber=0;
				
			    unset($la_data);			
			}//if
	    }//for
		$ld_montototalhaber=number_format($ld_montototalhaber,2,",",".");
		$ld_montototaldebe=number_format($ld_montototaldebe,2,",",".");
		uf_print_total_pie_cabecera($ld_montototaldebe,$ld_montototalhaber,$ld_fechasta,$io_pdf,$li_ocultar);
			
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