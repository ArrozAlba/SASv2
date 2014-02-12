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
	function uf_print_encabezado_pagina($as_titulo,$as_fecha,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 25/09/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=330-($li_tm/2);
		$io_pdf->addText($tm,730,10,$as_titulo); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(11,$as_fecha);
		$tm=330-($li_tm/2);
		$io_pdf->addText($tm,720,10,$as_fecha); // Agregar el título

		$io_pdf->addText(500,730,9,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(500,720,9,date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_spg_cuenta,$as_den_spg_cta,$as_programatica,$as_denestpro,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: privates
		//	    Arguments: as_programatica // programatica del comprobante
		//	    		   as_denestpro5 // denominacion de la programatica del comprobante
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 25/09/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if ($_SESSION["la_empresa"]["estmodest"] == 2)
	    {
		 $la_datacab=array(array('name'=>'<b>Cuenta</b> '.$as_spg_cuenta.''),
		                array('name'=>'<b>Denominacion</b> '.$as_den_spg_cta.'' ),
		   			    array('name'=>'<b>Programatica</b> '.$as_programatica.'' ),
					    array('name'=>'<b> </b>'.$as_denestpro.'' ));
		}
		else
		{
		 $ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
	 	 $ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
	 	 $ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
		 $la_datacab=array(array('name'=>'<b>Cuenta</b> '.$as_spg_cuenta.''),
		                array('name'=>'<b>Denominacion</b> '.$as_den_spg_cta.'' ),
		   			    array('name'=>'<b>Estructura Presupuestaria </b> '),
					    array('name'=>substr($as_programatica,0,$ls_loncodestpro1)."            ".$as_denestpro[0]),
						array('name'=>substr($as_programatica,$ls_loncodestpro1,$ls_loncodestpro2)."          ".$as_denestpro[1]),
						array('name'=>substr($as_programatica,$ls_loncodestpro1+$ls_loncodestpro2,$ls_loncodestpro3)."              ".$as_denestpro[2]));
		}				
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 7, // Tamaño de Letras
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9),
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_datacab,$la_columna,'',$la_config);
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 25/09/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'cols'=>array('documento'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la 
						               'procede'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la 
						 			   'fecha'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la 
						 			   'beneficiario'=>array('justification'=>'center','width'=>100), // Justificación 
						 			   'concepto'=>array('justification'=>'left','width'=>170), // Justificación y ancho de la 
									   'monto'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la 
		$la_columnas=array('documento'=>'<b>Documento</b>',
		                   'procede'=>'<b>Procede</b>',
						   'fecha'=>'<b>Fecha</b>',
						   'beneficiario'=>'<b>Proveedor/Beneficiario</b>',
						   'concepto'=>'<b>Concepto</b>',
						   'monto'=>'<b>Monto</b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_total_monto,&$io_pdf,$as_titulo)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 25/09/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_datat=array(array('name'=>'___________________________________________________________________________________________________________'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>560); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_datat,$la_columna,'',$la_config);
		
		$la_data[]=array('total'=>'<b>'.$as_titulo.'</b>','monto'=>$ad_total_monto);
		$la_columnas=array('total'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>2, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>450), // Justificación y ancho de la 
									   'monto'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la 
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
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
		require_once("../../shared/class_folder/class_fecha.php");
		require_once("sigesp_spg_funciones_reportes.php");
        require_once("../../shared/class_folder/class_funciones.php");
		$io_function_report = new sigesp_spg_funciones_reportes();
		$io_function		= new class_funciones() ;
		$io_fecha 			= new class_fecha();
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
		
//--------------------------------------------------  Parámetros para Filtar el Reporte  --------------------------------------
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		$ldt_fecdes = $_GET["txtfecdes"];
		$ldt_fechas = $_GET["txtfechas"];	
	    $ls_cuentades_min=$_GET["txtcuentades"];
	    $ls_cuentahas_max=$_GET["txtcuentahas"];
		if($ls_cuentades_min=="")
		{
		   if($io_function_report->uf_spg_reporte_select_min_cuenta($ls_cuentades_min))
		   {
		     $ls_cuentades=$ls_cuentades_min;
		   } 
		   else
		   {
				print("<script language=JavaScript>");
				print(" alert('No hay cuentas presupuestarias');"); 
				print(" close();");
				print("</script>");
		   }
		}
		else
		{
		    $ls_cuentades=$ls_cuentades_min;
		}
		if($ls_cuentahas_max=="")
		{
		   if($io_function_report->uf_spg_reporte_select_max_cuenta($ls_cuentahas_max))
		   {
		     $ls_cuentahas=$ls_cuentahas_max;
		   } 
		   else
		   {
				print("<script language=JavaScript>");
				print(" alert('No hay cuentas presupuestarias');"); 
				print(" close();");
				print("</script>");
		   }
		}
		else
		{
		    $ls_cuentahas=$ls_cuentahas_max;
		}
		$ls_orden=$_GET["rborden"];
		
		$ls_prvbendes  = $_GET["txtprvbendes"];
		$ls_prvbenhas  = $_GET["txtprvbenhas"];
		$ldec_montodes = $_GET["txtmondes"];
		$ldec_montohas = $_GET["txtmonhas"];
		$ls_tipoprvben = $_GET["tipoprvben"];
		$ls_concepto   = $_GET["txtconcepto"];

	    $ls_fechades=$io_function->uf_convertirfecmostrar($ldt_fecdes);
	    $ls_fechahas=$io_function->uf_convertirfecmostrar($ldt_fechas);
		
	 /////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_desc_event="Solicitud de Reporte Operacion por Especifica desde la  Fecha ".$ldt_fecdes."  hasta ".$ldt_fechas." Desde la Cuenta ".$ls_cuentades."  hasta ".$ls_cuentahas;
	 $io_function_report->uf_load_seguridad_reporte("SPG","sigesp_spg_r_operacion_por_especifica.php",$ls_desc_event);
	////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//----------------------------------------------------  Parámetros del encabezado  ---------------------------------------------------------------------------------------------------
		$ls_titulo="<b>OPERACION POR ESPECIFICA</b> "; 
		$ls_fecha="<b> DESDE  ".$ls_fechades."   HASTA LA FECHA  ".$ls_fechahas." </b>";      
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
     $lb_valido=$io_report->uf_spg_reportes_operacion_por_especifica($ldt_fecdes,$ldt_fechas,$ls_cuentades,$ls_cuentahas,$ls_orden,$ls_prvbendes,$ls_prvbenhas,$ls_tipoprvben,$ldec_montodes,$ldec_montohas,$ls_concepto);
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
		  $io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		  $io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		  $io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		  uf_print_encabezado_pagina($ls_titulo,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la página
		  $io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		  
	    $io_report->dts_reporte_final->group_noorder("spg_cuenta");
		$io_report->dts_reporte_final->group_noorder("programatica");
		$li_tot=$io_report->dts_reporte_final->getRowCount("spg_cuenta");
		$ld_total_monto_general=0;
		$ls_spg_cuenta_ant="";
		$ld_total_monto=0;
		$ls_denestpro = array();
		$ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
		$ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
		$ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
		$ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
		$ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
		for($z=1;$z<=$li_tot;$z++)
		{
			$li_tmp=($z+1);
			//$io_pdf->transaction('start'); // Iniciamos la transacción
			$thisPageNum     = $io_pdf->ezPageCount;
			$ls_spg_cuenta   = trim($io_report->dts_reporte_final->data["spg_cuenta"][$z]);
			$ls_den_spg_cta  = trim($io_report->dts_reporte_final->data["den_spg_cta"][$z]);
			$ls_programatica = $io_report->dts_reporte_final->data["programatica"][$z];
			$ls_estcla=substr($ls_programatica,-1);
			$ls_codestpro1   = substr($ls_programatica,0,25);
			$ls_denestpro1   = "";
			$ls_denestpro2   = "";
			$ls_denestpro3   = "";
			$ls_denestpro4   = "";
		    $ls_denestpro5   = "";
			$ls_denestpro[0]=$ls_denestpro1;
			$ls_denestpro[1]=$ls_denestpro2;
			$ls_denestpro[2]=$ls_denestpro3;
			$lb_valido       = $io_function_report->uf_spg_reporte_select_denestpro1($ls_codestpro1,$ls_denestpro1,$ls_estcla);
			if ($lb_valido)
		    {
				 $ls_denestpro1=trim($ls_denestpro1);
		    }
			$ls_codestpro2=substr($ls_programatica,25,25);
			if ($lb_valido)
		    {
				 $lb_valido     = $io_function_report->uf_spg_reporte_select_denestpro2($ls_codestpro1,$ls_codestpro2,$ls_denestpro2,$ls_estcla);
				 $ls_denestpro2 = trim($ls_denestpro2);
		    }
			$ls_codestpro3 = substr($ls_programatica,50,25);
			if ($lb_valido)
		    {
				 $lb_valido     = $io_function_report->uf_spg_reporte_select_denestpro3($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_denestpro3,$ls_estcla);
				 $ls_denestpro3 = trim($ls_denestpro3);
		    }

		    if ($li_estmodest==1)
		    {
				//$ls_denestpro=$ls_denestpro1." , ".$ls_denestpro2." , ".$ls_denestpro3;
				$ls_denestpro[0]=$ls_denestpro1;
				$ls_denestpro[1]=$ls_denestpro2;
				$ls_denestpro[2]=$ls_denestpro3;
				$ls_programatica=substr($ls_codestpro1,-$ls_loncodestpro1).substr($ls_codestpro2,-$ls_loncodestpro2).substr($ls_codestpro3,-$ls_loncodestpro3);
		    }
			elseif($li_estmodest==2)
		    {
			  $ls_codestpro4=substr($ls_programatica,75,25);
			  if ($lb_valido)
			  {
				  $lb_valido     = $io_function_report->uf_spg_reporte_select_denestpro4($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_denestpro4,$ls_estcla);
				  $ls_denestpro4 = trim($ls_denestpro4);
			  }
			  $ls_codestpro5 = substr($ls_programatica,100,25);
			  if ($lb_valido)
			  {
				  $lb_valido     = $io_function_report->uf_spg_reporte_select_denestpro5($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_denestpro5,$ls_estcla);
				  $ls_denestpro5 = trim($ls_denestpro5);
			  }
				$ls_denestpro=trim($ls_denestpro1)." , ".trim($ls_denestpro2)." , ".trim($ls_denestpro3)." , ".trim($ls_denestpro4)." , ".trim($ls_denestpro5);
				$ls_programatica=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2)."-".substr($ls_codestpro3,-$ls_loncodestpro3)."-".substr($ls_codestpro4,-$ls_loncodestpro4)."-".substr($ls_codestpro5,-$ls_loncodestpro5);
		   }
		   $ls_nom_benef	= $io_report->dts_reporte_final->data["nom_benef"][$z];  	  
		   $ldt_fecha		= $io_report->dts_reporte_final->data["fecha"][$z]; 
		   $ldt_fecha		= $io_function->uf_convertirfecmostrar($ldt_fecha);
		   $ls_descripcion = $io_report->dts_reporte_final->data["descripcion"][$z];
		   $ls_procede		= $io_report->dts_reporte_final->data["procede"][$z];
		   $ls_comprobante = $io_report->dts_reporte_final->data["comprobante"][$z];
		   $ld_monto		= $io_report->dts_reporte_final->data["monto"][$z];  
		   if ($z<$li_tot)
		   {
				$ls_spg_cuenta_next=$io_report->dts_reporte_final->data["spg_cuenta"][$li_tmp];
				$ls_programatica_next=$io_report->dts_reporte_final->data["programatica"][$li_tmp];
				$ls_codestpro1   = substr($ls_programatica_next,0,25);
				$ls_codestpro2   = substr($ls_programatica_next,25,25); 
				$ls_codestpro3   = substr($ls_programatica_next,50,25);  
			    if ($li_estmodest==1)
		       {
				$ls_programatica_next=substr($ls_codestpro1,-$ls_loncodestpro1).substr($ls_codestpro2,-$ls_loncodestpro2).substr($ls_codestpro3,-$ls_loncodestpro3);
		       }
			   elseif($li_estmodest==2)
		       {
			    $ls_codestpro4=substr($ls_programatica_next,75,25);
			    $ls_codestpro5 = substr($ls_programatica_next,100,25);
				$ls_programatica_next=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2)."-".substr($ls_codestpro3,-$ls_loncodestpro3)."-".substr($ls_codestpro4,-$ls_loncodestpro4)."-".substr($ls_codestpro5,-$ls_loncodestpro5);
		       }
				
		   }
		   elseif($z=$li_tot)
		   {
				$ls_spg_cuenta_next='no_next';
				$ls_programatica_next='no_next';
		   }
		   if(empty($ls_spg_cuenta_next)&&(!empty($ls_spg_cuenta)))
		   {
			   $ls_spg_cuenta_ant=$io_report->dts_reporte_final->data["spg_cuenta"][$z];
		   }
		   
		   if($li_tot==1)
		   {
			   $ls_spg_cuenta_ant=$io_report->dts_reporte_final->data["spg_cuenta"][$z];
			   $ls_programatica_ant=$io_report->dts_reporte_final->data["programatica"][$z]; 
		   }

		   $ld_total_monto=$ld_total_monto+$ld_monto;
		   $ld_total_monto_general=$ld_total_monto_general+$ld_monto;
		   $ld_monto=number_format($ld_monto,2,",",".");
		   //print "Detalle: ".$ls_programatica." - ". $ls_comprobante." - ".$ls_spg_cuenta." - ".$ls_spg_cuenta_next."-".$ld_monto."<br><br>";
		   if (!empty($ls_spg_cuenta))
		   {
				 
				  if(($ls_programatica != $ls_programatica_next)&&(($ls_programatica != "no_next")))
		          {
				   if (!empty($ls_programatica))
				   {
		            uf_print_cabecera($ls_spg_cuenta,$ls_den_spg_cta,$ls_programatica,$ls_denestpro,$io_pdf);
				   }
		          }
				  $la_data[$z]=array('documento'=>$ls_comprobante,'procede'=>$ls_procede,'fecha'=>$ldt_fecha,
				                     'beneficiario'=>$ls_nom_benef,'concepto'=>$ls_descripcion,'monto'=>$ld_monto);
		   }
		   else
		   {
				  if(($ls_programatica != $ls_programatica_next)&&(($ls_programatica != "no_next")))
		          {
				   if (!empty($ls_programatica))
				   {
		            uf_print_cabecera($ls_spg_cuenta_ant,$ls_den_spg_cta,$ls_programatica,$ls_denestpro,$io_pdf);
				   } 
		          }
				  //uf_print_cabecera($ls_spg_cuenta_next,$ls_den_spg_cta,$ls_programatica_next,$ls_denestpro,$io_pdf); 
				 
					if(!empty($ls_spg_cuenta_next)) 				 
					{
					  $la_data[$z]=array('documento'=>$ls_comprobante,'procede'=>$ls_procede,'fecha'=>$ldt_fecha,
				                        'beneficiario'=>$ls_nom_benef,'concepto'=>$ls_descripcion,'monto'=>$ld_monto);
		               uf_print_detalle($la_data,$io_pdf);		 
					}
					else
					{
					 $la_data[$z]=array('documento'=>$ls_comprobante, 'procede'=>$ls_procede,'fecha'=>$ldt_fecha,
				                        'beneficiario'=>$ls_nom_benef,'concepto'=>$ls_descripcion,'monto'=>$ld_monto);
					}
/*					print_r($la_data);
					print "<br><br>";*/
				  
		   } 

		   if (!empty($ls_spg_cuenta_next)) 
		   {     
				 if(empty($la_data[$z]))
				 { 
				  $la_data[$z]=array('documento'=>$ls_comprobante,'procede'=>$ls_procede,'fecha'=>$ldt_fecha,
				                     'beneficiario'=>$ls_nom_benef,'concepto'=>$ls_descripcion,'monto'=>$ld_monto);
				 }				 
/*				 print_r($la_data);
				 print "<br><br>";*/
				  if(($ls_programatica != $ls_programatica_next)&&(($ls_programatica != "no_next")))
		          {
				   if (!empty($ls_programatica))
				   {
		            uf_print_cabecera($ls_spg_cuenta_next,$ls_den_spg_cta,$ls_programatica,$ls_denestpro,$io_pdf);
				   }
		          }
				  //$ld_monto=str_replace('.',',',$ld_monto);
				  //$ld_monto=str_replace(',','.',$ld_monto);
			     /*uf_print_cabecera($ls_spg_cuenta_ant,$ls_den_spg_cta,$ls_programatica,$ls_denestpro,$io_pdf);*/
				
 				// uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				 $ld_totalmonto=$ld_total_monto;
				 $ld_total_monto=number_format($ld_total_monto,2,",",".");
				 if($ls_tipoformato==1)
				 {
					 uf_print_pie_cabecera($ld_total_monto,$io_pdf,'Total Bs.F.');
				 }	 	
				 else
				 {
					 uf_print_pie_cabecera($ld_total_monto,$io_pdf,'Total Bs.');
				 }	
				 $ld_total_monto=0;
				 /*if ($io_pdf->ezPageCount==$thisPageNum)
				 {// Hacemos el commit de los registros que se desean imprimir
					$io_pdf->transaction('commit');
				 }
				 elseif($thisPageNum>1)
				 {// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
					$io_pdf->transaction('rewind');
					$io_pdf->ezNewPage(); // Insertar una nueva página
			        uf_print_cabecera($ls_spg_cuenta_ant,$ls_den_spg_cta,$ls_programatica,$ls_denestpro,$io_pdf);
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					$ld_totalmonto=number_format($ld_totalmonto,2,",",".");
					if($ls_tipoformato==1)
					{
					  uf_print_pie_cabecera($ld_totalmonto,$io_pdf,'Total Bs.F.');	
					}
					else
					{
					  uf_print_pie_cabecera($ld_totalmonto,$io_pdf,'Total Bs.');	
					}
					$ld_totalmonto=0;
				 }*/
				 if($z==$li_tot)
				 {
				   // Imprimimos pie de la cabecera
					if($ls_tipoformato==1)
					{
						  $ld_total_monto_general=number_format($ld_total_monto_general,2,",",".");
						  uf_print_pie_cabecera($ld_total_monto_general,$io_pdf,'Total Bs.F.');	
					}
					else
					{
						  $ld_total_monto_general_bsf = $io_rcbsf->uf_convertir_monedabsf($ld_total_monto_general, $li_candeccon,$li_tipconmon,1000,$li_redconmon);	
						  $ld_total_monto_general_bsf=number_format($ld_total_monto_general_bsf,2,",",".");
						  //Bolivares 
						  $ld_total_monto_general=number_format($ld_total_monto_general,2,",",".");
						  uf_print_pie_cabecera($ld_total_monto_general,$io_pdf,'Total Bs.');
						  //Bolivar Fuerte
						  //uf_print_pie_cabecera($ld_total_monto_general_bsf,$io_pdf,'Total Bs.F.');
					}	  
			 	 }	
			}//if
			if(in_array($la_data[$z],$la_data)) 
			{
		     uf_print_detalle($la_data,$io_pdf);	
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
	unset($io_function_report);
?> 