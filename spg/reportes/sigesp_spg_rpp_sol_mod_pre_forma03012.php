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
	
	function uf_print_encabezado_pagina2($as_titulo,$as_procede,$ad_fecha,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		    Acess: private
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf    // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: Ing. Néstor Falcon
		// Fecha Creación: 18/05/2007.
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->rectangle(20,690,570,80);
		$io_pdf->addText(30,750,8,"ORGANO:{$_SESSION["la_empresa"]["nombre"]}");
		//$io_pdf->addText(383,750,8,"<b>PÁGINA N°.</b>");
		//$io_pdf->addText(510,745,8,"COD.");
		$io_pdf->addText(25,710,7,"FECHA:".$ad_fecha);
		//$io_pdf->addText(180,730,10,$as_titulo);
		$io_pdf->addText($tm,730,10,$as_titulo);
		$io_pdf->rectangle(20,625,570,60);
		$io_auxtexto = "Quienes abajo suscriben, acuerdan congelar los créditos presupuestarios a partir de la presente fecha, de las subpartidas que se detallan a continuación:";
		
		
		$io_pdf->addText(30,676,8,$io_auxtexto);
		/*$io_pdf->addText(130,660,7,"REDUCCIÓN");$io_pdf->rectangle(180,658,10,10);
		$io_pdf->addText(220,675,7,"<b>RECURSOS ADICIONALES</b>");
		$io_pdf->addText(223,660,7,"CRÉDITO ADICIONAL");$io_pdf->rectangle(300,658,10,10);
		$io_pdf->addText(238,638,7,"RECTIFICACIÓN");$io_pdf->rectangle(300,635,10,10);
		$io_pdf->addText(470,675,7,"<b>TRASPASO</b>");
		$io_pdf->addText(450,660,7,"GASTOS CORRIENTES");$io_pdf->rectangle(540,658,10,10);
		$io_pdf->addText(460,638,7,"GASTOS DE CAPITAL");$io_pdf->rectangle(540,635,10,10);
		
	//Impresión de las X para el Marcado de Operacion.
		
		switch ($as_procede){
		  case 'SPGINS':
		    $io_pdf->addText(97.5,660.5,7,"<b>X</b>");//Insubsistencia  
		  break;
		  case 'SPGCRA':
		    $io_pdf->addText(302.5,660.5,7,"<b>X</b>");//Crédito Adicional.
		  break;
		  case 'SPGREC':
		  	$io_pdf->addText(302.5,637.5,7,"<b>X</b>");//Rectificacion.
		  break;
		  case 'SPGTRA':
		  	$io_pdf->addText(542.5,660.5,7,"<b>X</b>");//Traspaso.
		  break;		
		}*/
		
		//Gastos Corrientes.
        /*$io_pdf->addText(542.5,660.5,7,"<b>X</b>");
		//Gastos de Capital.
		$io_pdf->addText(542.5,637.5,7,"<b>X</b>");*/

		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina2
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	
	function uf_print_detalle2($la_data,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 13/09/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $io_pdf->setStrokeColor(1,1,1);
		$io_pdf->ezSetY(615);
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580, // Ancho Máximo de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'cols'=>array('proyecto'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la 
						 			   'accion'=>array('justification'=>'center','width'=>45),
									   'ejecutora'=>array('justification'=>'center','width'=>25),
									   'partida'=>array('justification'=>'center','width'=>25),
									   'generica'=>array('justification'=>'center','width'=>25),
									   'especifica'=>array('justification'=>'center','width'=>25), 
									   'subespecifica'=>array('justification'=>'center','width'=>25),
									   'denominacion'=>array('justification'=>'left','width'=>255),
									   'monto'=>array('justification'=>'right','width'=>80))); // Justificación y ancho 

		$la_columnas = array('proyecto'=>'<b>PROYECTO O ACCION CENTRALIZADA</b>',
		                     'accion'=>'<b>ACCIÓN ESPECÍFICA</b>',
							 'ejecutora'=>'<b>UEL</b>',
							 'partida'=>'<b>PART</b>',
							 'generica'=>'<b>GEN</b>',
			                 'especifica'=>'<b>ESP</b>',
							 'subespecifica'=>'<b>SUB</b>',
							 'denominacion'=>'<b>DENOMINACIÓN</b>',
							 'monto'=>'<b>BOLÍVARES</b>');
		
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	
	function uf_print_detalle_mensual2($la_data,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_mensual
		//		    Acess: private
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing.Luis Anibal Lang
		// Fecha Creación: 19/05/2009
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $io_pdf->setStrokeColor(1,1,1);
		$data[0] = array('cuenta'=>"CUENTA",'programatica'=>"ESTRUCTURA",'operacion'=>"OPER",'enero'=>"ENE",
								'febrero'=>"FEB",'marzo'=>"MAR",'abril'=>"ABR",
								'mayo'=>"MAY",'junio'=>"JUN",'julio'=>"JUL",'agosto'=>"AGO",
								'septiembre'=>"SEP",'octubre'=>"OCT",'noviembre'=>"NOV",'diciembre'=>"DIC");
		$la_columnas = array('cuenta'=>"",'programatica'=>"",'operacion'=>"",'enero'=>"",'febrero'=>"",'marzo'=>"",'abril'=>"",
							 'mayo'=>"",'junio'=>"",'julio'=>"",'agosto'=>"",'septiembre'=>"",'octubre'=>"",'noviembre'=>"",'diciembre'=>"");
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580, // Ancho Máximo de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la 
						 			   'programatica'=>array('justification'=>'center','width'=>75),
									   'operacion'=>array('justification'=>'center','width'=>25),
									   'enero'=>array('justification'=>'center','width'=>35),
									   'febrero'=>array('justification'=>'center','width'=>35),
									   'marzo'=>array('justification'=>'center','width'=>35), 
									   'abril'=>array('justification'=>'center','width'=>35),
									   'mayo'=>array('justification'=>'center','width'=>35),
									   'junio'=>array('justification'=>'center','width'=>35),
									   'julio'=>array('justification'=>'center','width'=>35),
									   'agosto'=>array('justification'=>'center','width'=>35),
									   'septiembre'=>array('justification'=>'center','width'=>35),
									   'octubre'=>array('justification'=>'center','width'=>35),
									   'noviembre'=>array('justification'=>'center','width'=>35),
									   'diciembre'=>array('justification'=>'center','width'=>35))); // Justificación y ancho 

		$io_pdf->ezTable($data,$la_columnas,'DISTRIBUCION MENSUAL',$la_config);
		unset($la_columnas);
		unset($la_config);
		
		$la_columnas = array('cuenta'=>"",'programatica'=>"",'operacion'=>"",'enero'=>"",'febrero'=>"",'marzo'=>"",'abril'=>"",
							 'mayo'=>"",'junio'=>"",'julio'=>"",'agosto'=>"",'septiembre'=>"",'octubre'=>"",'noviembre'=>"",'diciembre'=>"");
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580, // Ancho Máximo de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la 
						 			   'programatica'=>array('justification'=>'center','width'=>75),
									   'operacion'=>array('justification'=>'center','width'=>25),
									   'enero'=>array('justification'=>'right','width'=>35),
									   'febrero'=>array('justification'=>'right','width'=>35),
									   'marzo'=>array('justification'=>'right','width'=>35), 
									   'abril'=>array('justification'=>'right','width'=>35),
									   'mayo'=>array('justification'=>'right','width'=>35),
									   'junio'=>array('justification'=>'right','width'=>35),
									   'julio'=>array('justification'=>'right','width'=>35),
									   'agosto'=>array('justification'=>'right','width'=>35),
									   'septiembre'=>array('justification'=>'right','width'=>35),
									   'octubre'=>array('justification'=>'right','width'=>35),
									   'noviembre'=>array('justification'=>'right','width'=>35),
									   'diciembre'=>array('justification'=>'right','width'=>35))); // Justificación y ancho 

		
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------------------------------------------------
	function uf_init_niveles2()
	{	///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_init_niveles
		//	     Access: public
		//	    Returns: vacio	 
		//	Description: Este método realiza una consulta a los formatos de las cuentas
		//               para conocer los niveles de la escalera de las cuentas contables  
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funcion,$ia_niveles_scg;
		
		$ls_formato  = ""; $li_posicion=0; $li_indice=0;
		$ls_formato  = trim($_SESSION["la_empresa"]["formpre"])."-";
		$li_posicion = 1 ;
		$li_indice   = 1 ;
		$li_posicion = $io_funcion->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		do
		{
			$ia_niveles_scg[$li_indice] = $li_posicion;
			$li_indice   = $li_indice+1;
			$li_posicion = $io_funcion->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		} while ($li_posicion>=0);
	}// end function uf_init_niveles
	//-----------------------------------------------------------------------------------------------------------------------------------
   
	function uf_print_pie_de_pagina2(&$io_pdf)
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////
	//	   Function: uf_print_pie_de_pagina
	//	     Access: public
	//	    Returns: vacio	 
	//	Description: Método que imprime el pie de pagina de Forma 0301 De Modificaciones Presupuestarias. 
	//////////////////////////////////////////////////////////////////////////////////////////////////////
   
        $io_pdf->Rectangle(19,80,570,30);
        $io_pdf->Rectangle(19,40,570,60);
		$io_pdf->line(70,80,590,80);		
		$io_pdf->line(400,40,400,100);		
		$io_pdf->line(205,40,205,100);	
		$io_pdf->ezStartPageNumbers(550,20,8,'','',1);	
		$io_pdf->addText(25,90,7,"ELABORADO POR:(ANALISTA DE PRESUPUESTO)"); // Agregar el título
		//$io_pdf->addText(110,90,7,"REVISADO POR:(PLANIFICACIÓN Y PRESUPUESTO"); // Agregar el título
		$io_pdf->addText(220,90,7,"REVISADO POR:(PLANIFICACIÓN Y PRESUPUESTO)"); // Agregar el título
		$io_pdf->addText(430,90,7,"APROBADO POR: (ADMINISTRADOR)"); // Agregar el título
		//$io_pdf->addText(298,90,7,"APROBADO POR GERENTE"); // Agregar el título
		//$io_pdf->addText(300,83,7,"GENERAL O PRESIDENTE"); // Agregar el título
		//$io_pdf->addText(415,90,7,"JEFE SECTOR"); // Agregar el título
		//$io_pdf->addText(490,90,7,"DIRECTOR G. SECTORIAL"); // Agregar el título
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	
	  require_once("../../shared/ezpdf/class.ezpdf.php");
	  require_once("../../shared/class_folder/class_fecha.php");
	  require_once("../../shared/class_folder/class_funciones.php");
	  require_once("../../shared/class_folder/sigesp_include.php"); 
	  require_once("../../shared/class_folder/class_datastore.php");      
	  require_once("../../shared/class_folder/class_sql.php");    
	  require_once("sigesp_spg_funciones_reportes.php");
	  require_once("sigesp_spg_reportes_class.php");
	 
	  $io_report      = new sigesp_spg_reportes_class();  
	  $io_funrep      = new sigesp_spg_funciones_reportes();
	  $io_funcion     = new class_funciones();
	  $io_fecha       = new class_fecha();
	  $io_conect      = new sigesp_include();
	  $con            = $io_conect-> uf_conectar ();
	  $io_msg         = new class_mensajes(); //Instanciando la clase mensajes 
	  $io_sql         = new class_sql($con); //Instanciando  la clase sql
	  $lb_valido      = true;
	  $io_dsreport    = new class_datastore();
	  $ls_codemp      = $_SESSION["la_empresa"]["codemp"];
	  $ls_forpre      = $_SESSION["la_empresa"]["formpre"];
	  $ls_estmodprog=	$_SESSION["la_empresa"]["estmodprog"];
	  $ls_procede     = $_GET["procede"];
	  $ls_comprobante = $_GET["comprobante"];
	  $ld_fecha       = $_GET["fecha"];
	  $io_report->uf_init_niveles(&$ia_niveles_scg,&$li_posicion);
  
	  if ($lb_valido==false)
		 {
		   print("<script language=JavaScript>");
		   print(" alert('No hay nada que Reportar');"); 
		   print(" close();");
		   print("</script>");
		 }
	   else
		 {
			error_reporting(E_ALL);
			set_time_limit(1800);
			$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(6.2,4,3,3); // Configuración de los margenes en centímetros
			$ls_titulo = "<b>ACTA DE CONGELACIÓN DE FONDOS</b>";
			uf_print_encabezado_pagina2($ls_titulo,$ls_procede,$ld_fecha,$io_pdf); // Imprimimos el encabezado de la página
			$li_total   = count($ia_niveles_scg);
			$li_numrows = 0;
			
			$lb_ok= $io_report->uf_select_dt_comprobante2($ls_codemp,$ls_procede,$ls_comprobante,$ld_fecha,&$li_numrows,$rs_dat);
			if ($li_numrows==0)
			{
				 print("<script language=JavaScript>");
				 print(" alert('No hay nada que Reportar');"); 
				 print(" close();");
				 print("</script>");
			}
			else
			{
				 $li_pos = 0;
				 $lb_impreso = false;
				 $ld_totced  = 0;
				 $ld_totrec  = 0; 
				 $li_filas   = 0;
				 $la_data2 = $io_report->uf_select_dt_comprobante_rdis($ls_codemp,$ls_procede,$ls_comprobante,$ld_fecha,$li_total,$ia_niveles_scg,$li_posicion,$li_numrows,"DI");
				 uf_print_detalle2($la_data2,&$io_pdf);
				 uf_print_pie_de_pagina2(&$io_pdf);
			}
			if($ls_estmodprog==1)
			{
				$rs_mensual=$io_report->uf_select_distmensual($ls_codemp,$ls_procede,$ls_comprobante,$ld_fecha);
				$li_i=0;
				while((!$rs_mensual->EOF))
				{
					$li_i++;
					$ls_cuenta= trim($rs_mensual->fields["spg_cuenta"]);
					$ls_codestpro1= $rs_mensual->fields["codestpro1"];
					$ls_codestpro2= $rs_mensual->fields["codestpro2"];
					$ls_codestpro3= $rs_mensual->fields["codestpro3"];
					$ls_codestpro4= $rs_mensual->fields["codestpro4"];
					$ls_codestpro5= $rs_mensual->fields["codestpro5"];
					$ls_operacion= trim($rs_mensual->fields["operacion"]);
					$li_enero= number_format($rs_mensual->fields["enero"],2,',','.');
					$li_febrero= number_format($rs_mensual->fields["febrero"],2,',','.');
					$li_marzo= number_format($rs_mensual->fields["marzo"],2,',','.');
					$li_abril= number_format($rs_mensual->fields["abril"],2,',','.');
					$li_mayo= number_format($rs_mensual->fields["mayo"],2,',','.');
					$li_junio= number_format($rs_mensual->fields["junio"],2,',','.');
					$li_julio= number_format($rs_mensual->fields["julio"],2,',','.');
					$li_agosto= number_format($rs_mensual->fields["agosto"],2,',','.');
					$li_septiembre= number_format($rs_mensual->fields["septiembre"],2,',','.');
					$li_octubre= number_format($rs_mensual->fields["octubre"],2,',','.');
					$li_noviembre= number_format($rs_mensual->fields["noviembre"],2,',','.');
					$li_diciembre= number_format($rs_mensual->fields["diciembre"],2,',','.');
					$ls_codestpro=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
					$io_report->uf_formatoprogramatica($ls_codestpro,&$ls_programatica);
					if($ls_operacion=="AU")
					{
						$ls_operacion="AUM";
					}
					else
					{
						$ls_operacion="DIS";
					}
					$la_data1[$li_i] = array('cuenta'=>$ls_cuenta,'programatica'=>$ls_programatica,'operacion'=>$ls_operacion,'enero'=>$li_enero,
											'febrero'=>$li_febrero,'marzo'=>$li_marzo,'abril'=>$li_abril,
											'mayo'=>$li_mayo,'junio'=>$li_junio,'julio'=>$li_julio,'agosto'=>$li_agosto,
											'septiembre'=>$li_septiembre,'octubre'=>$li_octubre,'noviembre'=>$li_noviembre,'diciembre'=>$li_diciembre);
					$rs_mensual->MoveNext();
				}
				if($li_i>0)
				{
					uf_print_detalle_mensual2($la_data1,&$io_pdf);
				}
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
		 }
		unset($io_pdf);
		unset($io_report);
		unset($io_funciones);
		unset($io_function_report);
		unset($io_fecha);

?>	