<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//  ESTE FORMATO SE IMPRIME EN Bs Y EN BsF. SEGUN LO SELECCIONADO POR EL USUARIO
	//  MODIFICADO POR: ING.YOZELIN BARRAGAN         FECHA DE MODIFICACION : 28/08/2007
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_titulo2,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   ad_fecha // Fecha 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 02/01/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(15,40,585,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],40,670,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,715,12,$as_titulo); // Agregar el título 1	
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo2);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,700,12,$as_titulo2); // Agregar el título	2			
		$io_pdf->addText(540,740,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(545,730,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	function uf_print_pie_de_pagina($ai_motivo,$ai_fecha,$ai_cedula1,$ai_cedula2,$ai_nombre1,$ai_nombre2,$ai_apellido1,$ai_apellido2,$ai_cargo1,$ai_cargo2,$ai_nomrec,$ai_aperec,$ai_cedrec,$ai_cargrec,&$io_pdf)
    {	
	 $io_pdf->setStrokeColor(0,0,0);    
     $io_pdf->addText(50,500,10,"<b>Motivo de Salida: ".$ai_motivo."</b>"); // Para Mostrar el motivo de la salida
	 $io_pdf->addText(50,480,10,"<b>Fecha de Entrega: ".$ai_fecha."</b>"); // Para Mostrar la fecha de salida
	 $io_pdf->addText(50,400,10,"<b>Recibe Conforme: </b>"); // Para Mostrar la fecha de salida
	 $io_pdf->addText(240,340,10,"<b>---------------------------------------------</b>");
	 $io_pdf->addText(270,330,10,"<b> C.I: ".$ai_cedrec."</b>"); // Para Mostrar la fecha de salida
	 $io_pdf->addText(260,320,10,"<b>".$ai_aperec.", ".$ai_nomrec."</b>"); // Para Mostrar la fecha de salida
	 $io_pdf->addText(260,310,10,"<b>".$ai_cargrec."</b>"); // Para Mostrar la fecha de salida
	 $io_pdf->addText(50,250,10,"<b>Entrega Conforme:</b>"); // Para Mostrar la fecha de salida
	 $io_pdf->addText(100,190,10,"<b>---------------------------------------------</b>"); // Para Mostrar la fecha de salida
	 $io_pdf->addText(350,190,10,"<b>---------------------------------------------</b>"); // Para Mostrar la fecha de salida
	 $io_pdf->addText(140,180,10,"<b> C.I: ".$ai_cedula1."</b>"); // Para Mostrar la fecha de salida
	 $io_pdf->addText(105,170,10,"<b>".$ai_apellido1.", ".$ai_nombre1."</b>"); // Para Mostrar la fecha de salida
	 $io_pdf->addText(110,160,10,"<b>".$ai_cargo1."</b>"); // Para Mostrar la fecha de salida
	 $io_pdf->addText(390,180,10,"<b> C.I: ".$ai_cedula2."</b>"); // Para Mostrar la fecha de salida
	 $io_pdf->addText(370,170,10,"<b>".$ai_apellido2.", ".$ai_nombre2."</b>"); // Para Mostrar la fecha de salida
	 $io_pdf->addText(375,160,10,"<b>".$ai_cargo2."</b>"); // Para Mostrar la fecha de salida
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    }

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing.Jennifer Rivero
		// Fecha Creación: 02/01/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-50);
		
		$as_descripcion="Descripción del Bien Muebles";
		$li_tm=$io_pdf->getTextWidth(11,$as_descripcion);		
		$io_pdf->addText(50,650,11,$as_descripcion); 
		
		global $ls_tipoformato;
		
		if($ls_tipoformato==0)
		{
		  $ls_titulo=" Bs.";
		}
		elseif($ls_tipoformato==1)
		{
		  $ls_titulo=" Bs.F.";
		}				
			
		$la_datatit[0]=array('grupo'=>'<b>Grupo</b>','codact'=>'<b>Código</b>','denact'=>'<b>Nombre</b>','maract'=>'Marca','seract'=>'Serial', 'unidad'=>'Unidad a la que Pertence' );
		$la_columna=array('grupo'=>'',
						  'codact'=>'',						  
						  'denact'=>'',
						  'maract'=>'',
						  'seract'=>'',
						  'unidad'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('grupo'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						               'codact'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'denact'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
									   'maract'=>array('justification'=>'center','width'=>45), // Justificación y ancho de la columna
									   'seract'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
									   'unidad'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
									   )); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columna,'',$la_config);
		$la_columna=array('grupo'=>'',
						  'codact'=>'',						  
						  'denact'=>'',
						  'maract'=>'',
						  'seract'=>'',
						  'unidad'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('grupo'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
						               'codact'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'denact'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
									   'maract'=>array('justification'=>'center','width'=>45), // Justificación y ancho de la columna
									   'seract'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
									   'unidad'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
									   )); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_funciones_activos.php");
	$io_fun_activos=new class_funciones_activos();
	$ls_tipoformato=$io_fun_activos->uf_obtenervalor_get("tipoformato",0);
	global $ls_tipoformato;
	$ls_tipoformato=$_GET["tipoformato"];
	if($ls_tipoformato==1)
	{
		require_once("sigesp_saf_class_reportbsf.php");
		$io_report=new sigesp_saf_class_reportbsf();
		$ls_titulo_report="Bs.F.";
	}
	else
	{
		require_once("sigesp_saf_class_report.php");
		$io_report=new sigesp_saf_class_report();
		$ls_titulo_report="Bs.";
	}	
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	    
    $ls_cmpmvo=$_GET["nromvo"];////numero del comprobante
	$ls_titulo= "DIRECCIÓN DE ADMINISTRACIÓN CONTROL DE BIENES MUEBLES ";	
	$ls_titulo2= "AUTORIZACIÓN DE SALIDA NRO.".$ls_cmpmvo;	
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_nomemp=$arre["nombre"];
	
	$li_ordenact=$_GET["ordenact"];//////ordena
	$ld_desde=$_GET["desde"];
	$ld_hasta=$_GET["hasta"];
	$ls_cmpmvo=$_GET["nromvo"];////numero del comprobante
	$ls_causa=$_GET["causa"];////numero del comprobante
	$ls_codper=$_GET["codper"];////codigo del personal que recibe conforme
	$ls_nomper=$_GET["nomper"];////nombre del personal que recibe conforme
	
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_select_comprobante_salida_activo($ld_desde,$ld_hasta,$ls_cmpmvo);	
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print("alert('No hay nada que Reportar');"); 
		print("close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		/////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////
		$ls_desc_event="Generó un reporte de Incorporaciones y Desincorporaciones de Bienes Muebles por Departamento";
		$io_fun_activos->uf_load_seguridad_reporte("SAF","sigesp_saf_r_activo.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.6,4.8,3,3); // Configuración de los margenes en centímetros
		$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página
		uf_print_encabezado_pagina($ls_titulo,$ls_titulo2,$io_pdf); // Imprimimos el encabezado de la página
		
		$lb_valido=$io_report-> uf_select_movimientos_bien($ld_desde,$ld_hasta,$ls_cmpmvo,$ls_causa,$li_ordenact);
			
		    $li_totrow=$io_report->ds_detalle->getRowCount("cmpmov");
		    $i=0;		
		    $la_data="";  
		
		    if($lb_valido)
		     {
		      for($li_i=1;$li_i<=$li_totrow;$li_i++)
		      {
				$io_pdf->transaction('start'); // Iniciamos la transacción
				$li_numpag=$io_pdf->ezPageCount; // Número de página
							
				$li_cmpmov=$io_report->ds_detalle->data["cmpmov"][$li_i];
				$li_feccmp=$io_report->ds_detalle->data["feccmp"][$li_i];
				$li_feccmp=$io_funciones->uf_convertirfecmostrar($li_feccmp);
				$li_grupo=$io_report->ds_detalle->data["grupo"][$li_i];	
				$li_codact=$io_report->ds_detalle->data["codact"][$li_i];
				$li_denact=$io_report->ds_detalle->data["denact"][$li_i];				
				$li_maract=$io_report->ds_detalle->data["maract"][$li_i];
				$li_seract=$io_report->ds_detalle->data["seract"][$li_i];	
				$li_coduniadm=$io_report->ds_detalle->data["coduniadm"][$li_i];	
				$li_denuniadm=$io_report->ds_detalle->data["denuniadm"][$li_i];	
				$unidad=$li_coduniadm."-".$li_denuniadm;						
				$li_dencau=$io_report->ds_detalle->data["dencau"][$li_i];
				$li_fecentact=$io_report->ds_detalle->data["fecentact"][$li_i];
				$li_fecentact=$io_funciones->uf_convertirfecmostrar($li_fecentact);
				
				$li_cedula1=$io_report->ds_detalle->data["cedrespri"][$li_i];
				$li_cedula2=$io_report->ds_detalle->data["cedresuso"][$li_i];
				$li_nombre1=$io_report->ds_detalle->data["nomrespri"][$li_i];
				$li_nombre2=$io_report->ds_detalle->data["nomresuso"][$li_i];
				$li_apellido1=$io_report->ds_detalle->data["aperespri"][$li_i];
				$li_apellido2=$io_report->ds_detalle->data["aperesuso"][$li_i];
				
				$li_cargo1=$io_report->ds_detalle->data["cargopri"][$li_i];
				$li_cargo2=$io_report->ds_detalle->data["cargouso"][$li_i];
			
				$la_data[$li_i]=array('cmpmov'=> $li_cmpmov,'feccmp'=> $li_feccmp,'codact'=>$li_codact,'denact'=>$li_denact,'grupo'=>$li_grupo,
			                      'maract'=>$li_maract,'seract'=>$li_seract,'unidad'=>$unidad);
		   	  }	
				if($la_data!="")
				{
					$i=$i +1;					
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					
					if ($io_pdf->ezPageCount==$li_numpag)
					{// Hacemos el commit de los registros que se desean imprimir
					    $io_pdf->transaction('commit');
					}
					else
					{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
						$io_pdf->transaction('rewind');
						$io_pdf->ezNewPage(); // Insertar una nueva página
						uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle						
					}
				}
		}
		unset($la_data);
		
		   $lb_valido=$io_report->uf_select_personal_confrome($ls_codper);////para buscar al personal que recibe conforme	
		   $li_totrow1=$io_report->ds->getRowCount("codper");
		    for($li_j=1;$li_j<=$li_totrow1;$li_j++)
		       {	
			     $li_nombre=$io_report->ds->data["nombre"][$li_j];
				 $li_apellido=$io_report->ds->data["apellido"][$li_j];
				 $li_cedula=$io_report->ds->data["cedula"][$li_j];
				 $li_cargo=$io_report->ds->data["cargo"][$li_j];
				 
			   }
	        unset($la_data);			
	    uf_print_pie_de_pagina($li_dencau,$li_fecentact,$li_cedula1,$li_cedula2,$li_nombre1,$li_nombre2,$li_apellido1,$li_apellido2,$li_cargo1,$li_cargo1,$li_nombre,$li_apellido,$li_cedula,$li_cargo,$io_pdf);
		
		if(($lb_valido)&&($i>0))
		{
			$io_pdf->ezStopPageNumbers(1,1);
			$io_pdf->ezStream();
		}
		else
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}		
		unset($io_pdf);
	}
		 
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 