<?Php
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

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../../shared/class_folder/sigesp_c_seguridad.php");
	    $io_seguridad= new sigesp_c_seguridad();
		$lb_valido=true;
		$ls_evento="REPORT";
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido= $io_seguridad->uf_sss_insert_eventos_ventana($_SESSION["la_empresa"]["codemp"],"SPS",$ls_evento,$_SESSION["la_logusr"],"sps_reporte_cartaanticipo.html.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		print "encabezado";
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,680,13,$as_titulo); // Agregar el título
		if($as_fecha=="1")
		{
			$io_pdf->addText(512,750,8,date("d/m/Y")); // Agregar la Fecha
			$io_pdf->addText(518,743,7,date("h:i a")); // Agregar la Hora
		}
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	
	require_once("../../../sps/class_folder/dao/sps_def_cartaanticipo_dao.php");
	require_once("../../../sps/reports/documents/sps_reporte_base.php");
    require_once("../../../sps/class_folder/utilidades/class_function.php");
    $lo_anticipo_dao = new sps_def_cartaanticipo_dao();
   	$lo_function     = new class_function();
	$lo_reporte_base = new sps_reporte_base("",'LETTER','portrait');
	$io_pdf = $lo_reporte_base->getPdf();
	//require_once("../../../shared/ezpdf/class.ezpdf.php");
  	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<i>Anticipo de Prestaciones Sociales</i>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
		$ls_codper = $_GET["codper"];
		$ls_codcarant = $_GET["codcarant"];
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
    $lb_valido = $lo_anticipo_dao->getCartaAnticipo($ls_codcarant,$la_array);
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
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');   // Seleccionamos el tipo de letra
			$ls_codcarant    = $la_array["codcarant"][0];
			$ls_concarant    = $la_array["concarant"][0];
			$li_tamletcarant = $la_array["tamletcarant"][0];
			$li_tamletpiepag = $la_array["tamletpiepag"][0];
			if($li_tamletpiepag=="")
			{ $li_tamletpiepag=$li_tamletcarant; }
			$li_intlincarant = $la_array["intlincarant"][0];
			$li_marinfcarant = $la_array["marinfcarant"][0];
			$li_marsupcarant = $la_array["marsupcarant"][0];
			$ls_titcarant    = $la_array["titcarant"][0];
			$ls_piepagcarant = $la_array["piepagcarant"][0];
			$ls_arcrtfcarant = $la_array["arcrtfcarant"][0];
			$ls_ente=$_SESSION["la_empresa"]["nombre"];
			$ld_fecha=date("d/m/Y");
			$ls_dia_act=substr($ld_fecha,0,2);
			$ls_mes_act=substr($ld_fecha,3,2);
			$ls_ano_act=substr($ld_fecha,6,4);
			$io_pdf->ezSetCmMargins($li_marsupcarant,$li_marinfcarant,3,3);      // Configuración de los margenes en centímetros
		  	uf_print_encabezado_pagina($ls_titcarant,$ld_fecha,$io_pdf);  // Imprimimos el encabezado de la página
	
			$lb_valido = $lo_anticipo_dao->getCartaAnticipo_personal($ls_codper,$la_personal);
			
			if($lb_valido)  
			{
			       $ls_contenido="";
				   $ls_contenido=$ls_concarant;
				   
				   $ls_cedper = $la_personal["cedper"][0];
				   $ls_nomper = $la_personal["nomper"][0];
				   $ls_apeper = $la_personal["apeper"][0];
				   $ls_fecingper= $lo_function->uf_dtoc($la_personal["fecingper"][0]);
				   $ls_descar = $la_personal["descar"][0];
				   $ls_codnom = $la_personal["codnom"][0];
				   $ls_desnom = $la_personal["desnom"][0];
				   $ls_desuniadm = $la_personal["desuniadm"][0];
				   $ld_monant = $lo_function->uf_ntoc($la_personal["monant"][0], 2);
				   $ls_fecantper= $lo_function->uf_dtoc($la_personal["fecantper"][0]);
				   $ls_motant = $la_personal["motant"][0];	
				   $ld_mondeulab = $lo_function->uf_ntoc($la_personal["mondeulab"][0], 2);
				   $ld_monporant = $lo_function->uf_ntoc($la_personal["monporant"][0], 2);	
				   
				   $ls_contenido=str_replace("\$ls_cedper",$ls_cedper,$ls_contenido);
				   $ls_contenido=str_replace("\$ls_nomper",$ls_nomper,$ls_contenido);
				   $ls_contenido=str_replace("\$ls_apeper",$ls_apeper,$ls_contenido);
				   $ls_contenido=str_replace("\$ldt_fecingper",$ls_fecingper,$ls_contenido);
				   $ls_contenido=str_replace("\$ls_carper",$ls_descar,$ls_contenido);
				   $ls_contenido=str_replace("\$ls_dennom",$ls_desnom,$ls_contenido);	
				   $ls_contenido=str_replace("\$ls_undadm",$ls_desuniadm,$ls_contenido);
				   $ls_contenido=str_replace("\$ld_monant",$ld_monant,$ls_contenido);			
				   $ls_contenido=str_replace("\$ldt_fecantper",$ls_fecantper,$ls_contenido);
				   $ls_contenido=str_replace("\$ls_motant",$ls_motant,$ls_contenido);
				   $ls_contenido=str_replace("\$ld_mondeulab",$ld_mondeulab,$ls_contenido);
				   $ls_contenido=str_replace("\$ld_monporant",$ld_monporant,$ls_contenido);
					
					$io_pdf->ezText($ls_contenido,$li_tamletcarant,array('justification' =>'full','spacing' =>$li_intlincarant));
					$li_pos=($li_marinfcarant*10)*(72/25.4);
										
					$li_texto=$io_pdf->addTextWrap(50,$li_pos,500,$li_tamletpiepag,$ls_piepagcarant,'center');
					$li_pos=$li_pos-$li_tamletpiepag;
					$li_texto=$io_pdf->addTextWrap(50,$li_pos,500,$li_tamletpiepag,$li_texto,'center');
					$li_pos=$li_pos-$li_tamletpiepag;
					$io_pdf->addTextWrap(50,$li_pos,500,$li_tamletpiepag,$li_texto,'center');
						
			}
			if($lb_valido) // Si no ocurrio ningún error
			{
				$io_pdf->ezStream(); // Mostramos el reporte
			}
			else  // Si hubo algún error
			{
				print("<script language=JavaScript>");
				print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
				print(" close();");
				print("</script>");		
			}
			unset($io_pdf);
	}
	unset($lo_function);
	
?> 
