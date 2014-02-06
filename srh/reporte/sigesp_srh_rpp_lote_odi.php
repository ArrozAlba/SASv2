<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//  REPORTE: Formato de Revisiones de ODI
	//  ORGANISMO: Ninguno en particular
	//  MODIFICADO POR: ING.YOZELIN BARRAGAN         FECHA DE MODIFICACION : 14/08/2007
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
		global $io_fun_srh;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("SRH","sigesp_srh_r_lote_odi.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo='Titulo',$as_numsol='0001',$ad_fecregsol='20/09/2007',&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_numsol // numero de la solicitud
		//	    		   ad_fecregsol // fecha de registro de la solicitud
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		// Fecha Creación: 11/03/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();

		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(14,$as_titulo);		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_nombre,$as_cedula,$as_cargo,$as_desuniadm,$as_objetivo,
	                           $adt_revini1,$adt_revfin1,$adt_revini2,$adt_revfin2,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_numsol    // numero de la solicitud de ejecucion presupuestaria
		//	   			   as_dentipsol // Denominacion del tipo de solicitud
		//	   			   as_denuniadm // Denominacion de la Unidad Ejecutora solicitante
		//	   			   as_denfuefin // Denominacion de la fuente de financiamiento
		//	   			   as_codigo    // Codigo del Proveedor / Beneficiario
		//	   			   as_nombre    // Nombre del Proveedor / Beneficiario
		//	   			   as_consol    // Concepto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por concepto
		// Fecha Creación: 17/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(102);
		$la_data[1]=array('titulo'=>'<b> ESTABLECIMIENTO Y SEGUIMIENTO DE LOS OBJETIVOS DE DESEMPEÑO INDIVIDUAL </b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 16, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre línea
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho Máximo de la tabla
						 'xPos'=>400, // OrientaciÃ³n de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				        //'outerLineThickness'=>0.5,
						// 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>660))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		
		
		$io_pdf->ezSetDy(-15);
		$la_data[1]=array('nombre'=>$as_nombre,'cedula'=>$as_cedula,'cargo'=>$as_cargo,'departamento'=>$as_desuniadm);
		$la_columnas=array('nombre'=>'APELLIDOS Y NOMBRES',
		                  'cedula'=>'CEDULA DE IDENTIDAD',
		                  'cargo'=>'CARGO',
		                  'departamento'=>'UNIDAD ADMINISTRATIVA');
		                  
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' =>10, // TamaÃ±o de Letras
						 'titleFontSize' => 10,  // TamaÃ±o de Letras de los tÃ­tulos
						 'showLines'=>1, // Mostrar LÃ­neas
						 'shaded'=>0, // Sombra entre lÃ­neas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho MÃ¡ximo de la tabla
						 'xPos'=>400, // OrientaciÃ³n de la tabla
						 'xOrientation'=>'center', // OrientaciÃ³n de la tablA
						 'cols'=>array('nombre'=>array('justification'=>'center','width'=>220), // Justificación y ancho de la columna
						               'cedula'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
						               'cargo'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la columna
						               'departamento'=>array('justification'=>'center','width'=>220))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		
		
		
		
		//$io_pdf->addLink("http://www.ros.co.nz/pdf/",50,100,500,120);
		//$io_pdf->rectangle(63,340,660,42);
		$io_pdf->ezSetDy(-12);
		$la_data[1]=array('nombre'=>'    '.trim($as_objetivo));
		$la_columnas=array('nombre'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // TamaÃ±o de Letras
						 'titleFontSize' => 12,  // TamaÃ±o de Letras de los tÃ­tulos
						 'showLines'=>1, // Mostrar LÃ­neas
						 'shaded'=>1, // Sombra entre lÃ­neas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho MÃ¡ximo de la tabla
						 'xPos'=>400, // OrientaciÃ³n de la tabla
						 'xOrientation'=>'center', // OrientaciÃ³n de la tablA
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>660))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'OBJETIVO FUNCIONAL DE LA UNIDAD',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		
		
		$io_pdf->ezSetDy(-15);
		$la_data[1]=array('odi'=>'OBJETIVOS DE DESEMPEÑO INDIVIDUAL','peso'=>'PESO',
		                 'rev1'=>"PRIMERA REVISION ".$adt_revini1." AL ".$adt_revfin1." ",'rev2'=>"SEGUNDA REVISION ".$adt_revini2." AL ".$adt_revfin2."");
		$la_columnas=array('odi'=>'',
		                  'peso'=>'',
		                  'rev1'=>'',
		                  'rev2'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // TamaÃ±o de Letras
						 'titleFontSize' => 12,  // TamaÃ±o de Letras de los tÃ­tulos
						 'showLines'=>1, // Mostrar LÃ­neas
						 'shaded'=>0, // Sombra entre lÃ­neas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho MÃ¡ximo de la tabla
						 'xPos'=>400, // OrientaciÃ³n de la tabla
						 'xOrientation'=>'center', // OrientaciÃ³n de la tablA
						 'cols'=>array('odi'=>array('justification'=>'center','width'=>350), // Justificación y ancho de la columna
						               'peso'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						               'rev1'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
						               'rev2'=>array('justification'=>'center','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
	  
		

	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($as_odi,$as_observacion1,$as_observacion2,$adt_fecrev,$ai_peso,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 27/04/2006		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-1);
		$la_data[1]=array('odi'=>$as_odi,'peso'=>$ai_peso,'rev1'=>$as_observacion1,'rev2'=>$as_observacion2);
		$la_columnas=array('odi'=>'',
		                  'peso'=>'',
		                  'rev1'=>'',
		                  'rev2'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // TamaÃ±o de Letras
						 'titleFontSize' => 12,  // TamaÃ±o de Letras de los tÃ­tulos
						 'showLines'=>1, // Mostrar LÃ­neas
						 'shaded'=>0, // Sombra entre lÃ­neas
						 'width'=>660, // Ancho de la tabla
						 'maxWidth'=>660, // Ancho MÃ¡ximo de la tabla
						 'xPos'=>400, // OrientaciÃ³n de la tabla
						 'xOrientation'=>'center', // OrientaciÃ³n de la tablA
						 'cols'=>array('odi'=>array('justification'=>'left','width'=>350), // Justificación y ancho de la columna
						               'peso'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						               'rev1'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la columna
						               'rev2'=>array('justification'=>'left','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		

	}// end function uf_print_detalle
	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle2($as_evaluador,$as_evaluado,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$tm=250-(200/2);
		$io_pdf->addText(77,80,12,"EVALUADOR:    ".$as_evaluador); // Agregar el título
	    $tm=310-(300/2);
		$io_pdf->addText(400,80,12," EVALUADO:    ".$as_evaluado); // Agregar el título
		 $tm=250-(350/2);
		

	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha = new class_fecha();	
	require_once("class_folder/sigesp_srh_class_report.php");
	$io_report  = new sigesp_srh_class_report('../../');
	require_once("../class_folder/utilidades/class_funciones_srh.php");
	$io_fun_srh=new class_funciones_srh("../../");	
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];	
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	 $ls_titulo='<b>REVISION DE ODIS </b>';
	 //--------------variable que se toman de sigesp_srh_r_listado_evaluacioneficiencia.php------------------------------------------
	 $ls_nroreg=$_GET["nroreg"]; 
	 $ld_fecini=$_GET["fecini"]; 
	 $ld_fecfin=$_GET["fecfin"]; 
	 $ls_rev=$_GET["rev"]; 
	 
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{       
		
		$lb_valido=$io_report->uf_select_lote_odi($ls_nroreg,$ld_fecini,$ld_fecfin,$rs_data);	
		
	}
	if($lb_valido==false) // Existe algún error ó no hay registros
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
			$io_pdf = new Cezpdf("LETTER","landscape");
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');
			$io_pdf->ezSetCmMargins(7,4,3,3);
			$io_pdf->ezStartPageNumbers(406,30,10,'','',1);//Insertar el número de página.
		    $li_total=$io_report->io_sql->num_rows($rs_data); ;
		    $li_aux=0;
		    uf_print_encabezado_pagina('','','',&$io_pdf);
		    $li_i=0;	
			while($row=$io_report->io_sql->fetch_row($rs_data))  
			{
				 $li_aux++;
			    $li_i++;
				$thisPageNum=$io_pdf->ezPageCount;
				$ls_nroeval=$row["nroreg"];
				$ls_codper=$row["codper"];			
				$ls_cedper=$row["cedper"];			
				$ls_nomper=$row["nomper"];			
				$ldt_revini1=$row["fecinirev1"];	
				$ldt_revini1=$io_funciones->uf_formatovalidofecha($ldt_revini1);
				$ldt_revini1=$io_funciones->uf_convertirfecmostrar($ldt_revini1);	
				$ldt_revfin1=$row["fecfinrev1"];			
				$ldt_revfin1=$io_funciones->uf_formatovalidofecha($ldt_revfin1);
				$ldt_revfin1=$io_funciones->uf_convertirfecmostrar($ldt_revfin1);	
				$ldt_revini2=$row["fecinirev2"];
				$ldt_revini2=$io_funciones->uf_formatovalidofecha($ldt_revini2);
				$ldt_revini2=$io_funciones->uf_convertirfecmostrar($ldt_revini2);	
				$ldt_revfin2=$row["fecfinrev2"];	
				$ldt_revfin2=$io_funciones->uf_formatovalidofecha($ldt_revfin2);
				$ldt_revfin2=$io_funciones->uf_convertirfecmostrar($ldt_revfin2);	
				$ls_objetivo=trim ($row["objetivo"]);	
				$lb_valido=$io_report->uf_select_odi_persona($ls_nroeval,'E');
				$ls_evaluador=$io_report->ds_detalle2->getValue("evaluador",$li_i);
				$ls_desuniadm=trim ($row["desuniadm"]);
				$ls_descar1=$row["denasicar"];
				
				$ls_descar2=$row["descar"];
				
				 if ($ls_descar1=="Sin Asignación de Cargo")
				 { 
					$ls_descar=$ls_descar2;			
				 }
				 else
				 {
					 $ls_descar=$ls_descar1;
				 }
					
				
				uf_print_cabecera($ls_nomper,$ls_cedper,$ls_descar,$ls_desuniadm,
				                  $ls_objetivo,$ldt_revini1,$ldt_revfin1,$ldt_revini2,$ldt_revfin2,&$io_pdf);
				
				$lb_valido=$io_report->uf_select_lote_dt_odi($ls_nroreg,$ld_fecini,$ld_fecfin,$ls_rev);
				$li_total2=$io_report->ds_detalle->getRowCount("nroreg");
				
				
				uf_print_detalle2($ls_evaluador,$ls_nomper,&$io_pdf);
				
				
				
				
				if($lb_valido)
			    {
			    	
			    }
		
				$control=0;
				for($li_d=1;$li_d<=$li_total2;$li_d++)
				{
				   if ($ls_rev=="PRIMERA REVISION") {
				   
				    $ldt_fecrev=$io_report->ds_detalle->getValue("fecrev",$li_d);
					$ldt_fecrev1=$io_funciones->uf_formatovalidofecha($ldt_fecrev);
				    $ldt_fecrev1=$io_funciones->uf_convertirfecmostrar($ldt_fecrev1);	
					$ls_odi=$io_report->ds_detalle->getValue("odi",$li_d);
					$li_peso=$io_report->ds_detalle->getValue("valor",$li_d);		
					
					if (($io_fecha->uf_comparar_fecha($ldt_revini1,$ldt_fecrev1)) && ($io_fecha->uf_comparar_fecha($ldt_fecrev1,$ldt_revfin1)))
					{	
					  $ls_observacion1=$io_report->ds_detalle->getValue("observacion",$li_d);
					   switch($ls_observacion1)
					  {
						case "1":
							$ls_observacion1="En Proceso";
							break;
						case "2":
							$ls_observacion1="Alcanzado";
							break;
						case "3":
							$ls_observacion1="No Alcanzado";
							break;
					 }
					   $ls_observacion2='';
					  uf_print_detalle($ls_odi,$ls_observacion1,$ls_observacion2,$ldt_fecrev,$li_peso,&$io_pdf);
					}
				  				   
				   
				   }
				   
				   else {
				    $ldt_fecrev=$io_report->ds_detalle->getValue("fecrev",$li_d);
					$ldt_fecrev1=$io_funciones->uf_formatovalidofecha($ldt_fecrev);
				    $ldt_fecrev1=$io_funciones->uf_convertirfecmostrar($ldt_fecrev1);	
					$ls_odi=$io_report->ds_detalle->getValue("odi",$li_d);
					$li_peso=$io_report->ds_detalle->getValue("valor",$li_d);		
					
					if (($io_fecha->uf_comparar_fecha($ldt_revini1,$ldt_fecrev1)) && ($io_fecha->uf_comparar_fecha($ldt_fecrev1,$ldt_revfin1)))
					{
								
					$ls_observacion1=$io_report->ds_detalle->getValue("observacion",$li_d);	
					switch($ls_observacion1)
					  {
						case "1":
							$ls_observacion1="En Proceso";
							break;
						case "2":
							$ls_observacion1="Alcanzado";
							break;
						case "3":
							$ls_observacion1="No Alcanzado";
							break;
					 }		
					$control=$control+1;	
					}
				  else 
				  { 
						
					$ls_observacion2=$io_report->ds_detalle->getValue("observacion",$li_d);	
					switch($ls_observacion2)
					  {
						case "1":
							$ls_observacion2="En Proceso";
							break;
						case "2":
							$ls_observacion2="Alcanzado";
							break;
						case "3":
							$ls_observacion2="No Alcanzado";
							break;
					 }		
					$control=$control+1;
						}
					
					if ($control==2)
					{
					 uf_print_detalle($ls_odi,$ls_observacion1,$ls_observacion2,$ldt_fecrev,$li_peso,&$io_pdf);
					 $control=0;
					}
					
				}
				}
					
				
				if($li_aux<$li_total)
				{
					$io_pdf->ezNewPage(); // Insertar una nueva página
				}
	    	}
	    	
	    	
	    		$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
	 	    	$io_pdf->ezStream(); // Mo
		    
		    
		    
			
			//uf_print_cabecera(&$io_pdf);
		    
		
		
	 }

?>
