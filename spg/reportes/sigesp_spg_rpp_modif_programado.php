<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Listado de Documentos
//  ORGANISMO: Ninguno en particular
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
   	function uf_obtenervalor_get($as_variable,$as_valordefecto)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_obtenertipo
		//	Description: Función que obtiene que tipo de llamada del catalogo
		//////////////////////////////////////////////////////////////////////////////
		if(array_key_exists($as_variable,$_GET))
		{
			$valor=$_GET[$as_variable];
		}
		else
		{
			$valor=$as_valordefecto;
		}
   		return $valor; 
   	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
   	function uf_loadmodalidad(&$ai_len1,&$ai_len2,&$ai_len3,&$ai_len4,&$ai_len5,&$as_titulo)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_loadmodalidad
		//		   Access: public
		//	  Description: Función que obtiene que tipo de modalidad y da las longitudes por accion
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 19/04/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		$ai_len1=$_SESSION["la_empresa"]["loncodestpro1"];
		$ai_len2=$_SESSION["la_empresa"]["loncodestpro2"];
		$ai_len3=$_SESSION["la_empresa"]["loncodestpro3"];
		$ai_len4=$_SESSION["la_empresa"]["loncodestpro4"];
		$ai_len5=$_SESSION["la_empresa"]["loncodestpro5"];
		switch($ls_modalidad)
		{
			case "1": // Modalidad por Proyecto
				$as_titulo="Estructura Presupuestaria";
				break;
				
			case "2": // Modalidad por Programatica
				$as_titulo="Estructura Programatica";
				break;
		}
   	}// end function uf_loadmodalidad
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
   	function uf_formatoprogramatica($as_codpro,&$as_programatica)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_formatoprogramatica
		//		   Access: public
		//	  Description: Función que obtiene que de acuerdo a la modalidad imprime la programatica
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 19/04/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		$li_len1=0;
		$li_len2=0;
		$li_len3=0;
		$li_len4=0;
		$li_len5=0;
		$ls_titulo="";
		uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
		$ls_codest1=substr($as_codpro,0,25);
		$ls_codest2=substr($as_codpro,25,25);
		$ls_codest3=substr($as_codpro,50,25);
		$ls_codest4=substr($as_codpro,75,25);
		$ls_codest5=substr($as_codpro,100,25);
		$ls_codest1=substr($ls_codest1,(25-$li_len1),$li_len1);
		$ls_codest2=substr($ls_codest2,(25-$li_len2),$li_len2);
		$ls_codest3=substr($ls_codest3,(25-$li_len3),$li_len3);
		$ls_codest4=substr($ls_codest4,(25-$li_len4),$li_len4);
		$ls_codest5=substr($ls_codest5,(25-$li_len5),$li_len5);		
		switch($ls_modalidad)
		{
			case "1": // Modalidad por Proyecto
				$as_programatica=$ls_codest1."-".$ls_codest2."-".$ls_codest3;
				break;

			case "2": // Modalidad por Programa
				$as_programatica=$ls_codest1."-".$ls_codest2."-".$ls_codest3."-".$ls_codest4."-".$ls_codest5;
				break;
		}
   	}// end function uf_obtenertipo
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_spg;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=uf_load_seguridad_reporte("CXP","sigesp_cxp_r_solicitudesf1.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 11/03/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(15,40,585,40);
        $io_pdf->Rectangle(15,700,570,60);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$io_pdf->addText(540,770,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(546,764,6,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
			
	function uf_print_detalle($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data      // arreglo de información
		//				   ai_i         // total de registros
		//				   li_totmonsol // total de solicitudes (Montos)
		//	    		   io_pdf       // Instancia de objeto pdf
		//    Description: Función que imprime el detalle del reporte
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 16/06/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-2);
		uf_loadmodalidad($ai_len1,$ai_len2,$ai_len3,$ai_len4,$ai_len5,$ls_titulo);
		$la_datatit[1]=array('programatica'=>'<b>'.$ls_titulo.'</b>','cuenta'=>'<b>Cuenta</b>','codusu'=>'<b>Usuario</b>','fecha'=>'<b>Fecha</b>',
							 'mesaumento'=>'<b>Mes Aumento</b>','mesdisminucion'=>'<b>Mes Disminucion</b>','monto'=>'<b>Monto</b>');
		$la_columnas=array('programatica'=>'',
						   'cuenta'=>'',
						   'codusu'=>'',
						   'fecha'=>'',
						   'mesaumento'=>'',
						   'mesdisminucion'=>'',
						   'monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('programatica'=>array('justification'=>'center','width'=>125), // Justificación y ancho de la columna
						 			   'cuenta'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'codusu'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'mesaumento'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'mesdisminucion'=>array('justification'=>'center','width'=>85),
									   'monto'=>array('justification'=>'center','width'=>65))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);

		$la_columnas=array('programatica'=>'',
						   'cuenta'=>'',
						   'codusu'=>'',
						   'fecha'=>'',
						   'mesaumento'=>'',
						   'mesdisminucion'=>'',
						   'monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('programatica'=>array('justification'=>'center','width'=>125), // Justificación y ancho de la columna
						 			   'cuenta'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'codusu'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'mesaumento'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la columna
						 			   'mesdisminucion'=>array('justification'=>'left','width'=>85),
									   'monto'=>array('justification'=>'right','width'=>65))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_spg_class_report.php");
	$io_report=new sigesp_spg_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../../shared/class_folder/class_fecha.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_fecha=new class_fecha();				
	$io_funciones=new class_funciones();				
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<b>MODIFICACIONES AL PROGRAMADO</b>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codestpro1=str_pad(uf_obtenervalor_get("codestpro1",""),25,"0",0);
	$ls_codestpro2=str_pad(uf_obtenervalor_get("codestpro2",""),25,"0",0);
	$ls_codestpro3=str_pad(uf_obtenervalor_get("codestpro3",""),25,"0",0);
	$ls_codestpro4=str_pad(uf_obtenervalor_get("codestpro4",""),25,"0",0);
	$ls_codestpro5=str_pad(uf_obtenervalor_get("codestpro5",""),25,"0",0);
	$ls_codestproh1=str_pad(uf_obtenervalor_get("codestpro1h",""),25,"0",0);
	$ls_codestproh2=str_pad(uf_obtenervalor_get("codestpro2h",""),25,"0",0);
	$ls_codestproh3=str_pad(uf_obtenervalor_get("codestpro3h",""),25,"0",0);
	$ls_codestproh4=str_pad(uf_obtenervalor_get("codestpro4h",""),25,"0",0);
	$ls_codestproh5=str_pad(uf_obtenervalor_get("codestpro5h",""),25,"0",0);
	$ls_cuentades=uf_obtenervalor_get("txtcuentades","");
	$ls_cuentahas=uf_obtenervalor_get("txtcuentahas","");
	$ls_codusu=uf_obtenervalor_get("codusu","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=true;//uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$rs_data=$io_report->uf_select_modificaciones_programado($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
																 $ls_codestpro5,$ls_codestproh1,$ls_codestproh2,$ls_codestproh3,
																 $ls_codestproh4,$ls_codestproh5,$ls_cuentades,$ls_cuentahas,$ls_codusu,
																 &$lb_valido); // Cargar el DS con los datos del reporte
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
			$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(3.6,3,3,3); // Configuración de los margenes en centímetros
			$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página
			$li_totrow=$io_report->SQL->num_rows($rs_data);
			if($li_totrow>0)
			{			
				$li_i=0;
				while((!$rs_data->EOF))
				{
					$li_i++;
					$ls_codestpro1=$rs_data->fields["codestpro1"];
					$ls_codestpro2=$rs_data->fields["codestpro2"];
					$ls_codestpro3=$rs_data->fields["codestpro3"];
					$ls_codestpro4=$rs_data->fields["codestpro4"];
					$ls_codestpro5=$rs_data->fields["codestpro5"];
					$ls_codpro=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
					$ls_cuenta=rtrim($rs_data->fields["spg_cuenta"]);
					$ls_codusu=rtrim($rs_data->fields["codusu"]);
					$ls_fecha=$io_funciones->uf_convertirfecmostrar($rs_data->fields["fecha"]);
					$ls_mesaumento=$io_fecha->uf_load_nombre_mes($rs_data->fields["mesaumento"]);
					$ls_mesdisminucion=$io_fecha->uf_load_nombre_mes($rs_data->fields["mesdisminucion"]);
					$li_monto=number_format($rs_data->fields["monto"],2,',','.');
					uf_formatoprogramatica($ls_codpro,&$ls_programatica);
					$la_data[$li_i]=array('programatica'=>$ls_programatica,'cuenta'=>$ls_cuenta,'codusu'=>$ls_codusu,'fecha'=>$ls_fecha,'mesaumento'=>$ls_mesaumento,
										  'mesdisminucion'=>$ls_mesdisminucion,'monto'=>$li_monto);
							
					$rs_data->MoveNext();
				}
				uf_print_encabezado_pagina($ls_titulo,&$io_pdf);
				uf_print_detalle($la_data,&$io_pdf);
			}
			else
			{
				print("<script language=JavaScript>");
				print(" alert('No hay nada que Reportar');"); 
				print(" close();");
				print("</script>");
			}
			
		}
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		
	}

?>
