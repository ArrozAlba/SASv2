<?php

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Historial de Personal
//  ORGANISMO: 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//-----------------------------------------------------------------------------------------------------------------------------------
///Elaborado por: Ing. María Beatriz Unda
//-----------------------------------------------------------------------------------------------------------------------------------
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
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/09/2008		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_historialpersonal.php",$ls_descripcion);
		return $lb_valido;
	}
	
//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	    {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/09/2008		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		
		$io_pdf->addText(540,770,6,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(546,764,6,date("h:i a")); // Agregar la Hora
		
		$io_pdf->ezSetY(715);	
		$la_data=array(array('titulo1'=>'<b>'.($as_titulo).'</b>'));					
		$la_columnas=array('titulo1'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('titulo1'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->restoreState();
	    $io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina	

//-------------------------------------------------------------------------------------------------------------------------------
	
	function uf_print_datos_personal ($as_codper,$as_nomper,&$io_pdf)
	{
								  
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_datos_personal
		//		   Access: private 
		//	    Arguments: as_codper // código del personal
		//                 as_nomper // nombre del personal
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los datos del personal
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 15/10/2008
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('name'=>'<b>CÓDIGO PERSONAL</b>',
		                  'name2'=>'<b>NOMBRE Y APELLIDO</b>');	
		$la_columnas=array('name'=>'',
		                   'name2'=>'');					
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'left','width'=>150),
						               'name2'=>array('justification'=>'left','width'=>350))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('name'=>$as_codper,
		                  'name2'=>$as_nomper);	
		$la_columnas=array('name'=>'',
		                   'name2'=>'');					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'lef','width'=>150),
						               'name2'=>array('justification'=>'left','width'=>350))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
	}// end function uf_print_encabezado_pagina	

//---------------------------------------------------------------------------------------------------------------------------------
function uf_print_datos_historial($as_codnom,$as_desnom,$as_codperi,$ad_fecdesper,$ad_fechasper,$as_codcar,$as_descar,
								  $as_codasicar,$as_denasicar,$as_codgra,$as_codpas,$as_codtab,$as_grado,$as_sueper,
								  $as_coduniadm,$as_desuniadm,$as_codded,$as_desded,$as_codtipper,$as_destipper,&$io_pdf)
	{
								  
								  
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_datos_historial
		//		   Access: private 
		//	    Arguments: aa_data // Arreglo con la data del historial
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los datos del concursante
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 24/09/2008
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras						 
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas						 
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500); // Orientación de la tabla
				      	 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data=array(array('name'=>'<b>Nómina</b>','name2'=>$as_codnom.'   '.$as_desnom),
					   array('name'=>'<b>Periodo</b>','name2'=>$as_codperi.' ('.$ad_fecdesper.' - '.$ad_fechasper.')'),
					   array('name'=>'<b>Cargo</b>','name2'=>$as_codcar.'    '.$as_descar),
					   array('name'=>'<b>Asiganción de Cargo</b>','name2'=>$as_codasicar.'   '.$as_denasicar),
					   array('name'=>'<b>Grado</b>','name2'=>$as_codgra),
					   array('name'=>'<b>Paso</b>','name2'=>$as_codpas),
					   array('name'=>'<b>Tabulador</b>','name2'=>$as_codtab),
					   array('name'=>'<b>Grado Obrero</b>','name2'=>$as_grado),
					   array('name'=>'<b>Sueldo</b>','name2'=>number_format($as_sueper,2,',','.')),
					   array('name'=>'<b>Unidad Aministrativa</b>','name2'=>$as_coduniadm.'   '.$as_desuniadm),
					   array('name'=>'<b>Dedicación</b>','name2'=>$as_codded.'   '.$as_desded),
					   array('name'=>'<b>Tipo Personal</b>','name2'=>$as_codtipper.'   '.$as_destipper));
		$la_columna=array('name'=>'',
		                  'name2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras						 
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas						 
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Orientación de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'lef','width'=>120),
						               'name2'=>array('justification'=>'left','width'=>380))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		
		
 } 
//---------------------------------------------------------------------------------------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
//----------------------------------------------------  Parámetros del encabezado  -------------------------------------------
	$ls_titulo="HISTORIAL DE PERSONAL";
//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------	
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
//---------------------------------------------------------------------------------------------------------------------------------
    $lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
        $lb_valido=$io_report->uf_select_personal_historial($ls_codperdes,$ls_codperhas,$ls_orden,$rs_data);
		if (($lb_valido==false)||($rs_data->RecordCount()==0))
		{
		    print("<script language=JavaScript>");
			print(" alert('No hay nada que reportar');"); 
			print(" close();");
			print("</script>");
		}		   
		else  // Imprimimos el reporte
		{       
		    error_reporting(E_ALL);
			set_time_limit(1800);
			$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra		
			$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página	
			$io_pdf->ezSetCmMargins(4,2.5,3,3); // Configuración de los margenes en centímetros		
			uf_print_encabezado_pagina($ls_titulo,&$io_pdf);
			$ls_auxcodigo="";
			$li_i=0;	
			$li_total=$rs_data->RecordCount();	
			while ((!$rs_data->EOF)&&($lb_valido))
		    {
		   		$ls_codper=$rs_data->fields["codper"];	
				$ls_nomper=$rs_data->fields["nomper"];
				$ls_apeper=$rs_data->fields["apeper"];
				$ls_nombre=$ls_nomper.' '.$ls_apeper;
				
				$lb_valido=$io_report->uf_select_historial($ls_codper,$rs_dathis);
				
				if ($rs_dathis->RecordCount()!=0)
				{
					uf_print_datos_personal($ls_codper,$ls_nombre,$io_pdf);
					$lb_valido2=true;
				}
				else
				{
					$lb_valido2=false;
				}
				
				$ls_aux_codnom="";
				$ls_aux_codcar="";
				$ls_aux_codasicar="";
				$ls_aux_codtab="";
				$ls_aux_codgra="";
				$ls_aux_codpas="";
				$ls_aux_sueper="";
				$ls_aux_coduniadm="";
				$ls_aux_coded="";
				$ls_aux_codtipper="";
				$ls_aux_grado="";
				
				while ((!$rs_dathis->EOF)&&($lb_valido)&&($lb_valido2))
		   		{
					$ls_codnom=$rs_dathis->fields["codnom"];
					$ls_desnom=$rs_dathis->fields["desnom"];	
					$ls_codperi=$rs_dathis->fields["codperi"];
					$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($rs_dathis->fields["fecdesper"]);
					$ld_fechasper=$io_funciones->uf_convertirfecmostrar($rs_dathis->fields["fechasper"]);
					$ls_codcar=$rs_dathis->fields["codcar"];
					$ls_descar=$rs_dathis->fields["descar"];				
					$ls_codasicar=$rs_dathis->fields["codasicar"];
					$ls_denasicar=$rs_dathis->fields["denasicar"];
					$ls_codtab=$rs_dathis->fields["codtab"];
					$ls_codgra=$rs_dathis->fields["codgra"];
					$ls_codpas=$rs_dathis->fields["codpas"];
					$ls_sueper=$rs_dathis->fields["sueper"];
					$ls_minorguniadm=$rs_dathis->fields["minorguniadm"];
					$ls_ofiuniadm=$rs_dathis->fields["ofiuniadm"];
					$ls_uniuniadm=$rs_dathis->fields["uniuniadm"];					
					$ls_depuniadm=$rs_dathis->fields["depuniadm"];					
					$ls_prouniadm=$rs_dathis->fields["prouniadm"];	
					$ls_desuniadm=$rs_dathis->fields["desuniadm"];
					$ls_coduniadm=$ls_minorguniadm.$ls_ofiuniadm.$ls_uniuniadm.$ls_depuniadm.$ls_prouniadm;
					$ls_codded=$rs_dathis->fields["codded"];	
					$ls_desded=$rs_dathis->fields["desded"];									
					$ls_codtipper=$rs_dathis->fields["codtipper"];
					$ls_destipper=$rs_dathis->fields["destipper"];					
					$ls_grado=$rs_dathis->fields["grado"];
					
					if ( (trim($ls_aux_codnom)!=trim($ls_codnom)) || (trim($ls_aux_codcar)!=trim($ls_codcar)) || 
						 (trim($ls_aux_codasicar)!=trim($ls_codasicar))  || (trim($ls_aux_codtab)!=trim($ls_codtab)) ||
						 (trim($ls_aux_codgra)!=trim($ls_codgra)) || (trim($ls_aux_codpas)!=trim($ls_codpas)) || 
						 (trim($ls_aux_sueper)!=trim($ls_sueper)) || (trim($ls_aux_coduniadm)!=trim($ls_coduniadm)) ||
						 (trim($ls_aux_coded)!=trim($ls_codded)) || (trim($ls_aux_codtipper)!=trim($ls_codtipper)) ||
						 (((trim($ls_aux_grado)!=trim($ls_grado)) && (trim($ls_grado)!=""))) )
					{
					
						uf_print_datos_historial($ls_codnom,$ls_desnom,$ls_codperi,$ld_fecdesper,$ld_fechasper,$ls_codcar,
						                         $ls_descar,$ls_codasicar,$ls_denasicar,$ls_codgra,$ls_codpas,$ls_codtab,
												 $ls_grado,$ls_sueper, $ls_coduniadm,$ls_desuniadm,$ls_codded,$ls_desded,
												 $ls_codtipper,$ls_destipper,$io_pdf);
												 
							$ls_aux_codnom=$ls_codnom;
							$ls_aux_codcar=$ls_codcar;	
							$ls_aux_codasicar=$ls_codasicar;				 
							$ls_aux_codtab=$ls_codtab;
							$ls_aux_codgra=$ls_codgra;
							$ls_aux_codpas=$ls_codpas;
							$ls_aux_sueper=$ls_sueper;
							$ls_aux_coduniadm=$ls_coduniadm;
							$ls_aux_coded=$ls_codded;
							$ls_aux_codtipper=$ls_codtipper;
							$ls_aux_grado=$ls_grado;
					}
					
					$rs_dathis->MoveNext();
				}
				$rs_dathis->Close();
				$rs_data->MoveNext();
				$li_i=$li_i+1;
				if (($li_i!=$li_total)&&($lb_valido2))
				{
					$io_pdf->ezNewPage(); // Insertar una nueva página
					$io_pdf->ezSetCmMargins(4,5,3,3); // Configuración de los margenes en centímetros
				}
			}	
			$rs_data->Close();			
   }
 
   if($lb_valido) // Si no ocurrio ningún error
   {
		$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
		$io_pdf->ezStream(); // Mostramos el reporte
   }
   else // Si hubo algún error
   {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que reportar');"); 
		print(" close();");
		print("</script>");	
   }
	
		
	}	
	
?>	