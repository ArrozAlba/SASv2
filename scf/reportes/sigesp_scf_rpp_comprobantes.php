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
	ini_set('memory_limit','512M');
	ini_set('max_execution_time','0');

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
		global $io_fun_scf;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_scf->uf_load_seguridad_reporte("SCF","sigesp_scf_r_comprobantes.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 28/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(20,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],15,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(9,$as_titulo);
		$tm=310-($li_tm/2);
		$io_pdf->addText($tm,730,9,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(9,$as_fecha);
		$tm=310-($li_tm/2);
		$io_pdf->addText($tm,715,9,$as_fecha); // Agregar el fecha
		$io_pdf->addText(500,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(506,743,7,date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_procede,$as_comprobante,$as_nomprobene,$adt_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_procede // procede
		//	    		   as_comprobante // comprobante
		//                 as_nomprobene   // nombre del proveedor
		//                 adt_fecha     // fecha del comprobante
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 28/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$r=(237)/255;
		$g=(244)/255;
		$b=(248)/255;
		$la_data=array(array('name'=>'<b>Comprobante</b>  '.$as_procede.'---'.$as_comprobante.'                '.$adt_fecha.''),
		               array('name'=>'<b>Beneficiario</b>  '.$as_nomprobene.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'shadeCol'=>array($r,$g,$b), // Color de la sombra
						 'shadeCol2'=>array($r,$g,$b), // Color de la sombra 
						 'fontSize' => 8, // Tamaño de Letras
						 'shaded'=>2, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
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
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 28/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'colGap'=>0.5, // separacion entre tablas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>300, // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'denominacion'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la columna
									   'descripcion'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la columna
						 			   'documento'=>array('justification'=>'left','width'=>100), // Justificación y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'haber'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		$la_columnas=array('cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Denominación</b>',
						   'descripcion'=>'<b>Descripción</b>',
						   'documento'=>'<b>Documento</b>',
						   'debe'=>'<b>Debe</b>',
						   'haber'=>'<b>Haber</b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_totaldebe,$ad_totalhaber,$as_tipo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private 
		//	    Arguments : ad_totaldebe // Total debe
		//	   				ad_totalhaber // Total haber
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación : 18/02/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$la_data=array(array('name'=>'--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>560); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		if($as_tipo=="1")// comprobante
		{
			$la_data=array(array('total'=>'<b>Total Comprobante '.$ls_bolivares.'</b>','debe'=>$ad_totaldebe,'haber'=>$ad_totalhaber));
		}
		elseif($as_tipo=="2")//total general
		{
			$la_data=array(array('total'=>'<b>Total '.$ls_bolivares.'</b>','debe'=>$ad_totaldebe,'haber'=>$ad_totalhaber));
		}
		$la_columna=array('total'=>'','debe'=>'','haber'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 8, // Tamaño de Letras
						 'colGap'=>0.5, // separacion entre tablas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>580, // Ancho Máximo de la tabla
						 'maxWidth'=>580, // Ancho Máximo de la tabla
						 'xPos'=>300, // Orientación de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>400), // Justificación y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'haber'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 8, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>560, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Orientación de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_init_niveles()
	{	///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_init_niveles
		//	     Access: public
		//	    Returns: vacio	 
		//	Description: Este método realiza una consulta a los formatos de las cuentas
		//               para conocer los niveles de la escalera de las cuentas contables  
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones,$ia_niveles_scf;
		
		$ls_formato=""; $li_posicion=0; $li_indice=0;
		$dat_emp=$_SESSION["la_empresa"];
		//contable
		$ls_formato = trim($dat_emp["formcont"])."-";
		$li_posicion = 1 ;
		$li_indice   = 1 ;
		$li_posicion = $io_funciones->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		do
		{
			$ia_niveles_scf[$li_indice] = $li_posicion;
			$li_indice   = $li_indice+1;
			$li_posicion = $io_funciones->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		} while ($li_posicion>=0);
	}// end function uf_init_niveles
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();	
	require_once("../class_folder/class_funciones_scf.php");
	$io_fun_scf=new class_funciones_scf("../../");
	$ls_tiporeporte="0";
	$ls_bolivares="";
	if (array_key_exists("tiporeporte",$_GET))
	{
		$ls_tiporeporte=$_GET["tiporeporte"];
	}
	switch($ls_tiporeporte)
	{
		case "0":
			require_once("sigesp_scf_class_report.php");
			$io_report  = new sigesp_scf_class_report();
			$ls_bolivares ="Bs.";
			break;

		case "1":
			require_once("sigesp_scf_class_reportbsf.php");
			$io_report  = new sigesp_scf_class_reportbsf();
			$ls_bolivares ="Bs.F.";
			break;
	}
	$ia_niveles_scf[0]="";			
	uf_init_niveles();
	$li_total=count($ia_niveles_scf)-1;
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	 $ls_comprobantedesde=$_GET["comprobantedesde"];
	 $ls_comprobantehasta=$_GET["comprobantehasta"];
	 $ls_procededesde=$_GET["procededesde"];
	 $ls_procedehasta=$_GET["procedehasta"];
	 $ld_fecdes=$_GET["fecdes"];
	 $ld_fechas=$_GET["fechas"];
	 $ls_orden=$_GET["orden"];
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
	$li_ano=substr($ldt_periodo,0,4);
	
	$ld_fecdes=substr($ld_fecdes,0,10);
	$ldt_fecdes_cab=$io_funciones->uf_convertirfecmostrar($ld_fecdes);
	$ld_fechas=substr($ld_fechas,0,10);
	$ldt_fechas_cab=$io_funciones->uf_convertirfecmostrar($ld_fechas);
	$ldt_fecha_cab=" <b>Desde  ".$ldt_fecdes_cab."  al  ".$ldt_fechas_cab." </b>"  ;
	$ls_titulo=" <b>COMPROBANTES  CONTABLE</b> ";
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el DS con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
	$lb_valido=uf_insert_seguridad("<b>Comprobantes en PDF</b>"); // Seguridad de Reporte
	if($lb_valido)
	{
		 $lb_valido=$io_report->uf_comprobante_cabecera($ld_fecdes,$ld_fechas,$ls_comprobantedesde,$ls_comprobantehasta,
		 												$ls_procededesde,$ls_procedehasta,$ls_orden);
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
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ldt_fecha_cab,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$li_tot=$io_report->DS->getRowCount("comprobante");
		$ld_totald=0;
		$ld_totalh=0;
		for($li_i=1;$li_i<=$li_tot;$li_i++)
		{
	        $io_pdf->transaction('start'); // Iniciamos la transacción
			$thisPageNum=$io_pdf->ezPageCount;
			$ld_totaldebe=0;
			$ld_totalhaber=0;
			$ls_comprobante=$io_report->DS->data["comprobante"][$li_i];
			$ldt_fecha=$io_report->DS->data["fecha"][$li_i];
			$ls_procede=$io_report->DS->data["procede"][$li_i];
			$ls_ced_bene=$io_report->DS->data["ced_bene"][$li_i];
			$ls_cod_pro=$io_report->DS->data["cod_pro"][$li_i];
			$ls_tipo_destino=$io_report->DS->data["tipo_destino"][$li_i];
			$ls_codban=$io_report->DS->data["codban"][$li_i];
			$ls_ctaban=$io_report->DS->data["ctaban"][$li_i];
			switch($ls_tipo_destino)
			{
				case "P":
					$ls_nomproben=$io_report->DS->data["nompro"][$li_i];
					break;
				case "B":
					$ls_nomproben=$io_report->DS->data["apebene"][$li_i].", ".$io_report->DS->data["nombene"][$li_i];
					break;
				default:
					$ls_nomproben="";
					break;
			}			
			$ldt_fec=$io_funciones->uf_convertirfecmostrar($ldt_fecha);
   		    uf_print_cabecera($ls_procede,$ls_comprobante,$ls_nomproben,$ldt_fec,$io_pdf); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_comprobante_detalle($ls_procede,$ls_comprobante,$ldt_fecha,$ls_codban,$ls_ctaban);
			if($lb_valido)
			{
				$li_totrow_det=$io_report->DS_detalle->getRowCount("comprobante");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_comprobante=$io_report->DS_detalle->data["comprobante"][$li_s];
					$ls_sc_cuenta=trim($io_report->DS_detalle->data["sc_cuenta"][$li_s]);
					$li_totfil=0;
					$as_cuenta="";
					for($li=$li_total;$li>1;$li--)
					{
						$li_ant=$ia_niveles_scf[$li-1];
						$li_act=$ia_niveles_scf[$li];
						$li_fila=$li_act-$li_ant;
						$li_len=strlen($ls_sc_cuenta);
						$li_totfil=$li_totfil+$li_fila;
						$li_inicio=$li_len-$li_totfil;
						if($li==$li_total)
						{
							$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila);
						}
						else
						{
							$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila)."-".$as_cuenta;
						}
					}
					$li_fila=$ia_niveles_scf[1]+1;
					$as_cuenta=substr($ls_sc_cuenta,0,$li_fila)."-".$as_cuenta;
					$ls_procede_doc=$io_report->DS_detalle->data["procede_doc"][$li_s];
					$ls_debhab=$io_report->DS_detalle->data["debhab"][$li_s];
					$ld_monto=$io_report->DS_detalle->data["monto"][$li_s];
					$ls_denominacion=$io_report->DS_detalle->data["denominacion"][$li_s];
					$ls_CMP_descripcion=$io_report->DS_detalle->data["cmp_descripcion"][$li_s];					
					if($ls_debhab=='D')
					{
					   $ld_debe=number_format($ld_monto,2,",",".");
					   $ld_totaldebe=$ld_totaldebe+$ld_monto;
					   $ld_haber=" ";
					}
					if($ls_debhab=='H')
					{
					   $ld_haber=number_format($ld_monto,2,",",".");
					   $ld_totalhaber=$ld_totalhaber+$ld_monto;
					   $ld_debe=" ";
					}
					
					$ls_documentoproc=$ls_procede_doc."-".$ls_comprobante;
					
					$la_data[$li_s]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'descripcion'=>$ls_CMP_descripcion,'documento'=>$ls_documentoproc,'debe'=>$ld_debe,'haber'=>$ld_haber);
				}
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				$ld_totald=$ld_totald+$ld_totaldebe;
				$ld_totalh=$ld_totalh+$ld_totalhaber;
				$ld_totaldebe=number_format($ld_totaldebe,2,",",".");
				$ld_totalhaber=number_format($ld_totalhaber,2,",",".");
				$ld_totalde=$ld_totaldebe;
				$ld_totalha=$ld_totalhaber;
			    uf_print_pie_cabecera($ld_totaldebe,$ld_totalhaber,1,$io_pdf); // Imprimimos pie de la cabecera
				$ld_totaldebe=str_replace('.','',$ld_totaldebe);
				$ld_totaldebe=str_replace(',','.',$ld_totaldebe);		
				$ld_totalhaber=str_replace('.','',$ld_totalhaber);
				$ld_totalhaber=str_replace(',','.',$ld_totalhaber);		
			}
          	if ($io_pdf->ezPageCount==$thisPageNum)
			{// Hacemos el commit de los registros que se desean imprimir
            	$io_pdf->transaction('commit');
          	}
			else
			{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
				if($thisPageNum==1)
				{
            		$io_pdf->transaction('commit');
				}
				else
				{
					$io_pdf->transaction('rewind');
					$io_pdf->ezNewPage(); // Insertar una nueva página
					uf_print_cabecera($ls_procede,$ls_comprobante,$ls_nomproben,$ldt_fec,$io_pdf); // Imprimimos la cabecera del registro
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					uf_print_pie_cabecera($ld_totalde,$ld_totalha,1,$io_pdf); // Imprimimos pie de la cabecera
				}
			}
			if($li_i==$li_tot)
			{
			  $ld_totald=number_format($ld_totald,2,",",".");
			  $ld_totalh=number_format($ld_totalh,2,",",".");
			  uf_print_pie_cabecera($ld_totald,$ld_totalh,2,$io_pdf); // Imprimimos pie de la cabecera
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