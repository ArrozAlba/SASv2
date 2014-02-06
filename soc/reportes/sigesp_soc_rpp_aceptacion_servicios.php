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

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 24/07/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_soc;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_soc->uf_load_seguridad_reporte("SOC","sigesp_soc_r_aceptacion_servicios.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_fecha // Fecha 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],270,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],500,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,680,11,'<b>'.$as_titulo.'</b>'); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_fecha);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,780,10,$as_fecha); // Agregar el título
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_nomemp,$as_codper,$as_nompro,$as_nomrespro,$ls_nomper,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_nomemp    // nombre de la empresa
		//	    		   as_nomfisalm // nombre fiscal de la empresa
		//	    		   io_pdf       // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'Entre <b>'.$as_nomemp.'</b> representado en este acto por el ciudadano <b>'.$ls_nomper.'</b> por una parte y por la otra "LA EMPRESA" <b>'.$as_nompro.'</b> representada en este acto por el ciudadano <b>'.$as_nomrespro.'</b>, se ha procedido a efectuar la recepción de los servicios consistentes en: '));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'lineCol'=>array(0.9,0.9,0.9), // Mostrar Líneas
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'rowGap' => 10 ,
						 'justification'=>'full',
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>'centre', // Orientación de la tabla
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cuerpo($as_nomemp,$as_numordcom,$ad_fecordcom,$ai_montot,$as_totordser,$as_ciuemp,$ad_fecrecser,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_nomemp    // nombre de la empresa
		//	    		   as_nomfisalm // nombre fiscal de la empresa
		//	    		   io_pdf       // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_fun_fecha=new class_fecha();
		$io_funciones=new class_funciones();				
		$io_pdf->ezSetDy(-5);
		$ld_dia=date("d");
		$ld_mes=date("m");
		$ld_anio=date("Y");
		$ls_mes=$io_fun_fecha->uf_load_nombre_mes($ld_mes);
		$ad_fecordcom=$io_funciones->uf_convertirfecmostrar($ad_fecordcom);
		$io_pdf->ezSetDy(-15);
		$la_data1[1]=array('empresa'=>"ENTREGA CONFORME",'empresa1'=>"ENTREGA CONFORME");
		$la_data1[2]=array('empresa'=>"Firma:___________________________",'empresa1'=>"Firma:___________________________");
		$la_data1[3]=array('empresa'=>"Nombre:_________________________",'empresa1'=>"Nombre:_________________________");
		$la_data1[4]=array('empresa'=>"Cargo:__________________________",'empresa1'=>"Cargo:__________________________");
		$la_data1[5]=array('empresa'=>"C.I.:_____________________________",'empresa1'=>"C.I.:_____________________________");
		$la_columna=array('empresa'=>'<b>POR LA EMPRESA</b>',
						  'empresa1'=>'<b>POR EL '.strtoupper($as_nomemp).' </b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>490, // Ancho de la tabla
						 'maxWidth'=>490, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'rowGap' => 1.5 ,
						 'cols'=>array('empresa'=>array('justification'=>'left','width'=>245), // Justificación y ancho de la columna
						 			   'empresa1'=>array('justification'=>'left','width'=>245))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-10);
		$la_columna=array('denser'=>'<b>Denominación</b>',
						  'canser'=>'<b>Cantidad</b>',
						  'monuniser'=>'<b>Precio Unitario</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('denser'=>array('justification'=>'left','width'=>340), // Justificación y ancho de la columna
						 			   'canser'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'monuniser'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('total'=>'',
						  'sueldointegral'=>'',
						  'bonovacacional'=>'',
						  'bonofin'=>'',
						  'aporte'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>220), // Justificación y ancho de la columna
						 			   'sueldointegral'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'bonovacacional'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'bonofin'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
						 			   'aporte'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ai_totprenom,$ai_totant,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera
		//		   Access: private 
		//	    Arguments: ai_totprenom // Total Prenómina
		//	   			   ai_totant // Total Anterior
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>510); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$la_data=array(array('total'=>''));
		$la_columna=array('total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>510, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>300), // Justificación y ancho de la columna
						 			   'prenomina'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'anterior'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>510, // Ancho Máximo de la tabla
						 'xOrientation'=>'center'); // Orientación de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("sigesp_soc_class_report.php");
	require_once("../class_folder/class_funciones_soc.php");
	require_once("../../shared/ezpdf/class.ezpdf.php");
    require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/class_fecha.php");
	require_once("../../shared/class_folder/sigesp_include.php");
    require_once("../../shared/class_folder/class_funciones.php");
    require_once("../../shared/class_folder/class_numero_a_letra.php");
	$io_numero_letra= new class_numero_a_letra();
	//imprime numero con los valores por defecto
	//cambia a minusculas
	$io_numero_letra->setMayusculas(1);
	//cambia a femenino
	$io_numero_letra->setGenero(1);
	//cambia moneda
	$io_numero_letra->setMoneda("Bolivares");
	//cambia prefijo
	$io_numero_letra->setPrefijo("");
	//cambia sufijo
	$io_numero_letra->setSufijo("");
	$in           = new sigesp_include();
	$con          = $in->uf_conectar();
	$io_sql       = new class_sql($con);
	$io_report    = new sigesp_soc_class_report($con);
	$io_funciones = new class_funciones();				
	$io_fun_soc   = new class_funciones_soc();
	$io_fun_fecha = new class_fecha();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_fecrec=$io_fun_soc->uf_obtenervalor_get("fecrec","");

	$ls_titulo="ACTA DE ACEPTACION DE SERVICIOS";
	$ls_fecha="";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codemp    = $_SESSION["la_empresa"]["codemp"];
	$ls_nomemp    = $_SESSION["la_empresa"]["nombre"];
	$ls_ciuemp    = $_SESSION["la_empresa"]["ciuemp"];
	$ls_numordcom = $io_fun_soc->uf_obtenervalor_get("numordcom","");
	$ls_cedper    = $io_fun_soc->uf_obtenervalor_get("cedper","");
	$ls_nomper    = $io_fun_soc->uf_obtenervalor_get("nomper","");
	$ls_nomreppro = $io_fun_soc->uf_obtenervalor_get("nomreppro","");
	$ls_fecaceser = $io_fun_soc->uf_obtenervalor_get("fecordcom","");
	if ($ls_nomreppro=="")
	   {
		 $ls_nomreppro="_______________________________";
	   }
	if ($ls_nomper=="")
	   {
	     $ls_nomper="_______________________________";
	   }
	$lb_valido = uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if ($lb_valido)
	   {
	     $rs_data = $io_report->uf_load_orden_servicio($ls_numordcom,&$lb_valido);
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
			  $io_pdf->ezSetCmMargins(4,3,2,2.2); // Configuración de los margenes en centímetros
			  uf_print_encabezado_pagina($ls_titulo,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la página
	          $li_numrows = $io_sql->num_rows($rs_data);
			  if ($li_numrows>0)
			     {
				    $io_pdf->transaction('start'); // Iniciamos la transacción
				    $li_numpag = $io_pdf->ezPageCount; // Número de página
					$li_i = 0;
					while($row=$io_sql->fetch_row($rs_data))
					     {
						   $li_i++;
				           $ls_nompro = $row["nompro"];
				           $li_montot = $row["montot"];
				           if ($li_i==1)
						      {
							    $io_numero_letra->setNumero($li_montot);
			  	                $ls_totordser=$io_numero_letra->letra();
				                $ls_contenido="Entre <b>".$ls_nomemp."</b> representado en este acto por el ciudadano <b>".$ls_nomper."</b> por una parte y por la otra 'LA EMPRESA' <b>".$ls_nompro."</b> representada en este acto por el ciudadano <b>".$ls_nomreppro."</b>, se ha procedido a efectuar la recepción de los servicios consistentes en: ";
				                $io_pdf->ezText($ls_contenido,9,array('justification' =>'full','spacing' =>1));
							  }
						   $ls_estcom = $row["estcom"];
				           if ($ls_estcom!=7)
				              {
					            print("<script language=JavaScript>");
					            print(" alert('La Orden de Servicios no esta en estatus de Recibida');"); 
					            print(" close();");
					            print("</script>");
			  	              }
						   $ls_codser      = $row["codser"];
					 	   $ls_denser      = $row["denser"];
						   $li_canser 	   = $row["canser"];
						   $ls_fecordcom   = $row["fecordcom"];
						   $ls_fecordcom   = $io_funciones->uf_convertirfecmostrar($ls_fecordcom);
						   $li_monuniser   = $row["monuniser"];
						   $li_canser      = number_format($li_canser,2,",",".");
						   $li_monuniser   = number_format($li_monuniser,2,",",".");
						   $la_data[$li_i] = array('denser'=>$ls_denser,'canser'=>$li_canser,'monuniser'=>$li_monuniser);
						 }
				    uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				    $ls_fecaceser=$io_funciones->uf_convertirfecmostrar($ls_fecaceser);
					$io_pdf->ezSetDy(-5);
					$ls_contenido="Los cuales fueron concluidos en fecha <b>".$ls_fecaceser."</b> según Orden de Servicio Nª <b>".
											  $ls_numordcom."</b> de fecha <b>".$ls_fecordcom."</b>   Acta levantada al efecto, en la forma siguiente:";
					$io_pdf->ezText($ls_contenido,9,array('justification' =>'full','spacing' =>1));
					$io_pdf->ezSetDy(-5);
					$ls_contenido="El representante del '<b>".$ls_nomemp."</b>', luego de haber constatado que 'LA EMPRESA' ejecutó los servicios señalados en la referida Orden de Servicio, declara ACEPTADOS dichos servicios.";
					$io_pdf->ezText($ls_contenido,9,array('justification' =>'full','spacing' =>1));
					$io_pdf->ezSetDy(-5);
					$ls_contenido="El monto correspondiente a este servicio es de Bolivares:";
					$io_pdf->ezText($ls_contenido,9,array('justification' =>'full','spacing' =>1));
					$io_pdf->ezSetDy(-5);
					$ls_contenido=$ls_totordser."           Bs. (".number_format($li_montot,2,',','.').")";
					$io_pdf->ezText($ls_contenido,9,array('justification' =>'full','spacing' =>1));
					$ld_dia  = date("d");
					$ld_mes  = date("m");
					$ld_anio = date("Y");
					$ls_mes  = $io_fun_fecha->uf_load_nombre_mes($ld_mes);
					$io_pdf->ezSetDy(-5);
					$ls_contenido="En prueba de conformidad se firma, en la ciudad de ".$ls_ciuemp.", a los ".$ld_dia." dias del mes de ".$ls_mes." de ".$ld_anio."";
					$io_pdf->ezText($ls_contenido,9,array('justification' =>'full','spacing' =>1));
					uf_print_cuerpo($ls_nomemp,$ls_numordcom,$ls_fecordcom,$li_montot,$ls_totordser,$ls_ciuemp,$ls_fecaceser,&$io_pdf); // Imprimimos pie de la cabecera
					if ($io_pdf->ezPageCount==$li_numpag)
					   {// Hacemos el commit de los registros que se desean imprimir
					     $io_pdf->transaction('commit');
					   }
				    else
					   {// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
					     $io_pdf->transaction('rewind');
						 if ($li_numpag>1)
						    { 
						      $io_pdf->ezNewPage(); // Insertar una nueva página
					 	    }
						 uf_print_cabecera($ls_nomemp,$ls_nomfisalm,$io_pdf); // Imprimimos la cabecera del registro
						 uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
						 uf_print_pie_cabecera("","",$io_pdf); // Imprimimos pie de la cabecera
					   }
				 }
			  else
			     {
		           print("<script language=JavaScript>");
		           print(" alert('No hay nada que Reportar');"); 
		           print(" close();");
	      	       print("</script>");
				 }
		 if ($lb_valido)
			{
			  $io_pdf->ezStopPageNumbers(1,1);
			  $io_pdf->ezStream();
			}
			unset($io_pdf);
		  }	  
	    unset($io_report);
	    unset($io_funciones);
     }
	//--------------------------------------------------------------------------------------------------------------------------------
?> 