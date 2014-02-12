<?php
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
	function uf_leer_archivo($as_archivo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_leer_archivo
		//		   Access: private 
		//	    Arguments: as_archivo //  ruta donde se encuentra el archivo
		//    Description: función que lee un archivo de texto y lo mete en una cadena
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_texto = file($as_archivo);
		$li_tamano = sizeof($ls_texto);
		$ls_textocompleto="";
		for($li_i=0;$li_i<$li_tamano;$li_i++)
		{
			$ls_textocompleto=$ls_textocompleto.$ls_texto[$li_i];
		}
		return $ls_textocompleto;
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../../sps/class_folder/dao/sps_def_cartaanticipo_dao.php");
	require_once("../../../sps/reports/documents/sps_reporte_base.php");
    require_once("../../../sps/class_folder/utilidades/class_function.php");
    
    $lo_anticipo_dao = new sps_def_cartaanticipo_dao();
	$lo_function     = new class_function();
	
	$lo_reporte_base = new sps_reporte_base("",'LETTER','portrait');
	$io_pdf = $lo_reporte_base->getPdf();
	
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="<i>Anticipo de Prestaciones Sociales formato Word</i>";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
		$ls_codper = $_GET["codper"];
		$ls_codcarant = $_GET["codcarant"];
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido = $lo_anticipo_dao->getCartaAnticipo($ls_codcarant,$la_array);
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
			$ls_original = $la_array["arcrtfcarant"][0];
		
			
			$lb_valido = $lo_anticipo_dao->getCartaAnticipo_personal($ls_codper,$la_personal);
			if($lb_valido)  
			{
				//---------------------------------------------------------------------------------------------------------------//
				$ls_archivo="../documentos/original/".$ls_original;
				$ls_copia=substr($ls_original,0,strrpos($ls_original,"."));
				$ls_salida="../documentos/copia/".$ls_copia."-".$_SESSION["la_logusr"].".rtf";
				$ls_contenido="";
				$ls_contenido=uf_leer_archivo($ls_archivo);
				$la_matriz=explode("sectd",$ls_contenido);
				$ls_cabecera=$la_matriz[0]."sectd";
				$li_inicio=strlen($ls_cabecera);
				$li_final=strrpos($ls_contenido,"}");
				$li_longitud=$li_final-$li_inicio;
				$ls_nuevocuerpo=substr($ls_contenido,$li_inicio,$li_longitud);
				$ls_punt=fopen($ls_salida,"w");
				fputs($ls_punt,$ls_cabecera);
				//-----------------------------------------------------------------------------------------------------------------//
				
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
				   
				   $ls_cuerpo=$ls_nuevocuerpo;
				   $ls_cuerpo=str_replace("\$ls_cedper",$ls_cedper,$ls_cuerpo);
				   $ls_cuerpo=str_replace("\$ls_nomper",$ls_nomper,$ls_cuerpo);
				   $ls_cuerpo=str_replace("\$ls_apeper",$ls_apeper,$ls_cuerpo);
				   $ls_cuerpo=str_replace("\$ldt_fecingper",$ls_fecingper,$ls_cuerpo);
				   $ls_cuerpo=str_replace("\$ls_carper",$ls_descar,$ls_cuerpo);
				   $ls_cuerpo=str_replace("\$ls_dennom",$ls_desnom,$ls_cuerpo);	
				   $ls_cuerpo=str_replace("\$ls_undadm",$ls_desuniadm,$ls_cuerpo);
				   $ls_cuerpo=str_replace("\$ld_monant",$ld_monant,$ls_cuerpo);			
				   $ls_cuerpo=str_replace("\$ldt_fecantper",$ls_fecantper,$ls_cuerpo);
				   $ls_cuerpo=str_replace("\$ls_motant",$ls_motant,$ls_cuerpo);
				   $ls_cuerpo=str_replace("\$ld_mondeulab",$ld_mondeulab,$ls_cuerpo);
				   $ls_cuerpo=str_replace("\$ld_monporant",$ld_monporant,$ls_cuerpo);
				   fputs($ls_punt,$ls_cuerpo);
					
				fputs($ls_punt,"}");
				fclose($ls_punt);
				@chmod($ls_salida,0755);
						
			}
			if($lb_valido) // Si no ocurrio ningún error
			{
				header ("Content-Disposition: attachment; filename=".$ls_copia."-".$_SESSION["la_logusr"].".rtf\n\n");
				header ("Content-Type: application/octet-stream");
				header ("Content-Length: ".filesize($ls_salida));
				readfile($ls_salida);
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