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
		
		$ls_descripcion="Generó el Reporte de Formato de salida de ".$as_titulo;
		$lb_valido=$io_fun_soc->uf_load_seguridad_reporte("SOC","sigesp_soc_p_registro_cotizacion.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_numcot,$as_feccot,$as_dentipcot,$as_obscot,$as_numsolcot,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo  // Título del Reporte
		//	    		   $as_numcot // Número de la Cotizacion.
		//	    		   ls_feccot  // Número de solicitud
		//	    		   io_pdf     // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 20/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->saveState();
		$io_pdf->rectangle(140,705,450,53);
		$io_pdf->line(450,705,450,758); //LINEA VERTICAL
		$io_pdf->line(450,730,590,730); //LINEA HORIZONTAL
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],40,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(10,$as_titulo);		
		$io_pdf->addText(200,710,14,"<b>".$as_titulo."</b>"); // Agregar el título
		$io_pdf->addText(460,740,10,"<b>   No.:</b>");      // Agregar texto
		$io_pdf->addText(495,740,10,$as_numcot); // Agregar Numero de la solicitud
		$io_pdf->addText(460,710,10,"<b>  Fecha:</b>"); // Agregar texto
		$io_pdf->addText(505,710,10,$as_feccot); // Agregar la Fecha
		$io_pdf->addText(555,770,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(560,760,7,date("h:i a")); // Agregar la hora
		
		$io_pdf->addText(235,750,7,"VICEPRESIDENCIA DE LA REPUBLICA"); // Agregar el título
		$io_pdf->addText(252,740,7,"GESTION ADMINISTRATIVA"); // Agregar el título
    	$io_pdf->addText(245,730,7,"DEPARTAMENTO DE COMPRAS"); // Agregar el título
		$io_pdf->Rectangle(15,60,570,60);
		$io_pdf->line(200,60,200,120); //LINEA VERTICAL 
		$io_pdf->addText(20,110,7,"ANALISTA:"); // Agregar el título
		$io_pdf->addText(205,110,7,"FIRMA:"); // Agregar el título
		$io_pdf->addText(60,110,7,$_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"]);
		
		$io_pdf->ezSetY(695);
		$la_data=array(array('name'=>'      <b>SOLICITUD :</b>   '.$as_numsolcot.'                                                                             <b>TIPO:  </b>'.$as_dentipcot),
					   array('name'=>'<b>OBSERVACIÓN: </b> '.$as_obscot));				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'titleFontSize' => 9,
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>548, // Ancho de la tabla						 
						 'justification'=>'center', // Ancho de la tabla						 
						 'maxWidth'=>548); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);		

		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_datos_proveedor($as_codpro,$as_nompro,$as_dirpro,$as_telpro,$as_email,$as_rifpro,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_datos_proveedor
		//		   Access: private 
		//	    Arguments: as_numcot // Número
		//	    		   as_fecsolcot // Fecha
		//	    		   as_obssolcot // Observación
		//	    		   as_codpro // Código de Proveedor
		//	    		   as_nompro // Nombre de Proveedor
		//	    		   as_dirpro // Dirección de Proveedor
		//	    		   as_telpro // Teléfono de Proveedor
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 19/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_pdf->saveState();
		$la_data=array(array('name'=>'<b>Nombre o Razón Social: </b>'.$as_codpro.'  -  '.$as_nompro),
 		               array('name'=>'<b>Dirección: </b>'.$as_dirpro),
					   array('name'=>'<b>Teléfono: </b> '.$as_telpro.'  -                                                 <b>E-Mail</b>: '.$as_email.'                                                                         <b>RIF: </b>'.$as_rifpro));				
		
		$la_data1=array(array('name'=>''));				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 11, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>548, // Ancho de la tabla						 
						 'maxWidth'=>548); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);	
		
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		
		$la_columna=array('name'=>'<b>DATOS DEL PROVEEDOR</b>');		
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'titleFontSize' => 9,
						 'shaded'=>0, // Sombra entre líneas
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>548, // Ancho de la tabla						 
						 'justification'=>'center', // Ancho de la tabla						 
						 'maxWidth'=>548); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);		
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
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 17/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		global $ls_bolivares;
		$la_data1=array(array('name'=>''));				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 11, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>548, // Ancho de la tabla						 
						 'maxWidth'=>548); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);	
		
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		
		$la_columna=array('codite'=>'<b>Código</b>',
						  'denite'=>'<b>Denominación</b>',
  						  'canite'=>'<b>Cantidad</b>',
						  'preite'=>'<b>Precio '.$ls_bolivares.'</b>',
						  'subite'=>'<b>Subtotal '.$ls_bolivares.'</b>',
  						  'totite'=>'<b>Total '.$ls_bolivares.'</b>');
						  
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xPos'=>315, // Orientación de la tabla
						 'cols'=>array('codite'=>array('justification'=>'center','width'=>95),      // Justificación y ancho de la columna
						 			   'denite'=>array('justification'=>'left','width'=>163), // Justificación y ancho de la columna
						 			   'canite'=>array('justification'=>'right','width'=>50),
									   'preite'=>array('justification'=>'right','width'=>80),      // Justificación y ancho de la columna
						 			   'subite'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'totite'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'<b>DETALLE DE LOS MATERIALES, SUMINISTROS O SERVICIOS COTIZADOS</b>',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales($ad_monsubcot,$ad_totcrecot,$ad_montotcot,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime la tabla resumen de totales de la cotizacion.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 20/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		global $ls_bolivares;
		
		$la_data1=array(array('name'=>''));				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 11, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500, // Ancho de la tabla						 
						 'maxWidth'=>500); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);	
		unset($la_data1);		
		unset($la_columna);		
		unset($la_config);		
		
		$la_data[1]=array('titulo'=>'<b>Subtotal '.$ls_bolivares.':</b>','valor'=>$ad_monsubcot);		
   		$la_data[2]=array('titulo'=>'<b>Otros Créditos '.$ls_bolivares.':</b>','valor'=>$ad_totcrecot);				
   		$la_data[3]=array('titulo'=>'<b>Total Cotización '.$ls_bolivares.':</b>','valor'=>$ad_montotcot);				
		$la_columna=array('titulo'=>'','valor'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>320, // Orientación de la tabla
						 'width'=>500, // Ancho de la tabla						 
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>436), // Justificación y ancho de la columna
						 			   'valor'=>array('justification'=>'right','width'=>110))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------
	
	require_once("sigesp_soc_class_report.php");	
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../class_folder/class_funciones_soc.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_sql.php");	
	require_once("../../shared/class_folder/class_funciones.php");
	
	$in           = new sigesp_include();
	$con          = $in->uf_conectar();
	$io_sql       = new class_sql($con);	
	$io_report    = new sigesp_soc_class_report($con);
	$io_funciones = new class_funciones();
	$io_fun_soc	  = new class_funciones_soc();
	$ls_tiporeporte=$io_fun_soc->uf_obtenervalor_get("tiporeporte",0);
	$ls_bolivares="Bs.";
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_soc_class_reportbsf.php");
		$io_report=new sigesp_soc_class_reportbsf();
		$ls_bolivares="Bs.F.";
	}
	
	$ls_numcot = $_GET["numcot"];
	$ls_codpro = $_GET["cod_pro"];
	$ls_feccot = $_GET["feccot"];
	$ls_tipcot = $_GET["tipcot"];
	if ($ls_tipcot=='B')
	   {
	     $ls_tabla = "soc_dtcot_bienes";
	     $ls_campo = "codart";
	     $ls_table = "siv_articulo"; 
	     $ls_tipo  = "Bienes"; 
	   }
	elseif($ls_tipcot=='S')
	   {
	     $ls_tabla = "soc_dtcot_servicio";
	     $ls_campo = "codser";
	     $ls_table = "soc_servicios";
	     $ls_tipo  = "Servicios"; 
	   }
	$ls_codemp = $_SESSION["la_empresa"]["codemp"];
    $ls_titulo = "REGISTRO DE COTIZACIÓN";
	
	$lb_valido = uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if ($lb_valido)
	   {
		 $rs_data   = $io_report->uf_load_cabecera_formato_registro_cotizacion($ls_numcot,$ls_tipcot,$ls_feccot,$ls_codpro,$ls_tabla,&$lb_valido);
		 if (!$lb_valido)
		    {
			  print("<script language=JavaScript>");
			  print(" alert('No hay nada que Reportar');"); 
			  print(" close();");
			  print("</script>");
		    }
	 	 else
		    {
	          if ($row=$io_sql->fetch_row($rs_data))
		         {
				   error_reporting(E_ALL);
				   set_time_limit(1800);
				   $io_pdf = new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
				   $io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
				   $io_pdf->ezSetCmMargins(5,3,3,3); // Configuración de los margenes en centímetros
				   $io_pdf->ezStartPageNumbers(550,30,10,'','',1); // Insertar el número de página
				   $li_count = 0; 
				   $ls_codpro    = $row["cod_pro"];
				   $ls_nompro    = $row["nompro"];
				   $ls_dirpro    = $row["dirpro"];
				   $ls_telpro    = $row["telpro"];
				   $ls_obscot    = $row["obscot"];
				   $ls_feccot    = $row["feccot"];
				   $ls_numsolcot = $row["numsolcot"];
				   $ls_mailpro   = $row["email"];
				   $ls_rifpro    = $row["rifpro"];
				   $ls_feccot    = $io_funciones->uf_convertirfecmostrar($ls_feccot);
				   $ld_subtotcot = number_format($row["monsubtot"],2,',','.');
				   $ld_totcrecot = number_format($row["monimpcot"],2,',','.');
				   $ld_montotcot = number_format($row["montotcot"],2,',','.');
				  
				   $rs_datos     = $io_report->uf_load_dt_registro_cotizacion($ls_numcot,$ls_codpro,$ls_tabla,$ls_table,$ls_campo,&$lb_valido);
			       if ($lb_valido)
			          {
		     	        $li_totrows = $io_sql->num_rows($rs_datos);
				        if ($li_totrows>0)
					       {
						     $li_i = 0;
						     while ($row=$io_sql->fetch_row($rs_datos))
								   {
									 $li_i++;
									 $ls_codite = $row["codite"];
									 $ls_denite = $row["denite"];
									 $ld_canite = number_format($row["canite"],2,',','.');
									 $ld_preite = number_format($row["preite"],2,',','.');
									 $ld_subite = number_format($row["subite"],2,',','.');
									 $ld_totite = number_format($row["totite"],2,',','.');
								 
								 	 $la_datos[$li_i] = array('codite'=>$ls_codite,'denite'=>$ls_denite,'canite'=>$ld_canite,'preite'=>$ld_preite,'subite'=>$ld_subite,'totite'=>$ld_totite);
								   }
							 uf_print_encabezado_pagina($ls_titulo,$ls_numcot,$ls_feccot,$ls_tipo,$ls_obscot,$ls_numsolcot,$io_pdf);
							 uf_print_datos_proveedor($ls_codpro,$ls_nompro,$ls_dirpro,$ls_telpro,$ls_mailpro,$ls_rifpro,$io_pdf);
							 uf_print_detalle($la_datos,$io_pdf);
							 uf_print_totales($ld_subtotcot,$ld_totcrecot,$ld_montotcot,&$io_pdf);
							 $io_pdf->setStrokeColor(0,0,0);
							 $io_pdf->line(20,50,580,50);
							 $io_pdf->ezStopPageNumbers(1,1);
							 $io_pdf->ezStream();
					       }
			            else
					       {
					         $lb_valido = false;
					       }
				      }
				 }
			  else
				 {
				   print("<script language=JavaScript>");
				   print(" alert('No hay nada que Reportar !!!');"); 
				   print(" close();");
				   print("</script>");
				 }
	        } 
	   }			   
?>