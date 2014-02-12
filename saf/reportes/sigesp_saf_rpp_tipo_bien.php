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
	function uf_print_encabezado_pagina($as_titulo,$ad_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   ad_fecha // Fecha 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 17/12/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->saveState();
		$io_pdf->line(50,40,950,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],80,520,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=504-($li_tm/2);
		$io_pdf->addText($tm,550,11,"<b>".$as_titulo."</b>"); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$ad_fecha);
		$tm=504-($li_tm/2);
		$io_pdf->addText(750,535,11,""); // Agregar la fecha
		$io_pdf->addText($tm,535,11,$ad_fecha); // Agregar la fecha
		$io_pdf->addText(750,555,11,""); // Agregar la fecha
		$io_pdf->addText(800,555,11,""); // Agregar la fecha
		$io_pdf->addText(928,570,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(934,563,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($cod_pro, $nompro,$cod_uni, $nomuni, $cantidad,$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: $cod_pro   // codigo del proveedor
		//	    		   $nompro   // nombre del proveedor	//	    		  
		//	    		   io_pdf      // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 17/12/2006 
		// Modificado el :28/12/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>Proveedor: '.$cod_pro." - ".$nompro.'</b>'),	
		               array('name'=>'<b>Unidad: '.$cod_uni." - ".$nomuni. '          Total de Bienes: '.$cantidad.'</b>'),					               
					   array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'lineCol'=>array(0.9,0.9,0.9), // Mostrar Líneas
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2	, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>840, // Ancho de la tabla
						 'maxWidth'=>350); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
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
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 17/12/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		global $ls_tipoformato;
		
		if($ls_tipoformato==0)
		{
		  $ls_titulo=" Bs.";
		}
		elseif($ls_tipoformato==1)
		{
		  $ls_titulo=" Bs.F.";
		}				
			
		$la_datatit[0]=array('grupo'=>'<b>Grupo</b>','codact'=>'<b>Código</b>','idact'=>'<b>ID</b>','denact'=>'<b>Nombre</b>','cantidad'=>'<b>Cantidad</b>', 'costo'=>'<b>Precio</b>', 'numordcom'=>'<b>Nro Doc.</b>', 'fecordcom'=>'<b>Fecha Doc.</b>','maract'=>'Marca', 'modact'=>'Modelo','seract'=>'Serial','spg_cuenta_act'=>'Cuenta Contable','sc_cuenta'=>'Código Partida');
		$la_columna=array('grupo'=>'',
						  'codact'=>'',
						  'idact'=>'',
						  'denact'=>'',
						  'costo'=>'',
						  'numordcom'=>'',
						  'fecordcom'=>'',
						  'maract'=>'',
						  'modact'=>'',
						  'seract'=>'',
						  'spg_cuenta_act'=>'',
						  'sc_cuenta'=>'');
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
						 			   'idact'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'denact'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
									   'costo'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
									   'numordcom'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'fecordcom'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
									   'maract'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
									   'modact'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
									   'seract'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
									   'spg_cuenta_act'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
									   'sc_cuenta'=>array('justification'=>'center','width'=>90) // Justificación y ancho de la columna
									    )); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columna,'',$la_config);
		$la_columna=array('grupo'=>'',
						  'codact'=>'',
						  'idact'=>'',
						  'denact'=>'',						 
						  'costo'=>'',
						  'numordcom'=>'',
						  'fecordcom'=>'',
						  'maract'=>'',
						  'modact'=>'',
						  'seract'=>'',
						  'spg_cuenta_act'=>'',
						  'sc_cuenta'=>'');
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
						 			   'idact'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'denact'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
									   'costo'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
									   'numordcom'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'fecordcom'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
									   'maract'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
									   'modact'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
									   'seract'=>array('justification'=>'center','width'=>50), // Justificación y ancho de la columna
									   'spg_cuenta_act'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
									   'sc_cuenta'=>array('justification'=>'center','width'=>90) // Justificación y ancho de la columna
									    )); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ai_montotinc, &$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera
		//		   Access: private 
		//	    Arguments: ai_montot // Total movimiento
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing.Arnaldo Suárez
		// Fecha Creación: 17/12/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('total'=>'Total x Proveedor - Unidad:','monto_inc'=>$ai_montotinc));
		$la_columna=array('total'=>'','monto_inc'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 10, 
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>555, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>200),  // Justificación y ancho de la columna
						               'monto_inc'=>array('justification'=>'left','width'=>700)// Justificación y ancho de la columna
						               )); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
	//-----------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_de_pagina($ai_cantidad,$ai_monto,&$io_pdf)
    {	
	 $io_pdf->setStrokeColor(0,0,0);    
     $io_pdf->addText(100,50,12,"<b>Total General: ".$ai_monto."</b>"); // Para Mostrar el monto global
	 $io_pdf->addText(400,50,12,"<b>Total de Bienes General: ".$ai_cantidad."</b>"); // Para Mostrar el monto global
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    }
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
	/*$ld_desde=$io_fun_activos->uf_obtenervalor_get("desde","");
	$ld_hasta=$io_fun_activos->uf_obtenervalor_get("hasta","");*/
    $ld_desde=$_GET["desde"];
	$ld_hasta=$_GET["hasta"];

	$ls_titulo= "TIPOS DE ADQUISICION DE BIENES EN ".$ls_titulo_report;	
	$ls_fecha= "Desde ".$ld_desde." Hasta ".$ld_hasta;
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_nomemp=$arre["nombre"];
	/*$li_ordenact=$io_fun_activos->uf_obtenervalor_get("ordenact","");//////ordena
	$ls_coddesde=$io_fun_activos->uf_obtenervalor_get("coddesde","");/////1er codigo del activo
	$ls_codhasta=$io_fun_activos->uf_obtenervalor_get("codhasta","");/////2do. codigo del activo
	$ls_codprodesd=$io_fun_activos->uf_obtenervalor_get("codprod","");////1er. codigo del proveedor
	$ls_codprohast=$io_fun_activos->uf_obtenervalor_get("codprohas","");//////2do. codigo del proveedor*/	
	$li_ordenact=$_GET["ordenact"];//////ordena
	$ls_coddesde=$_GET["coddesde"];/////1er codigo del activo
	$ls_codhasta=$_GET["codhasta"];/////2do. codigo del activo
	$ls_codprodesd=$_GET["codprod"];////1er. codigo del proveedor
	$ls_codprohast=$_GET["codprohas"];//////2do. codigo del proveedor
	$ls_coduni=$_GET["cod_unidad"];//////codigo de la unidad administrativa
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report-> uf_select_proveedor_tipo_bien($ls_codprodesd,$ls_codprohast,$ls_coddesde,$ls_codhasta,$ld_desde,$ld_hasta,$ls_coduni,$li_ordenact);
	
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
		$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.6,4.8,3,3); // Configuración de los margenes en centímetros
		$io_pdf->ezStartPageNumbers(940,50,10,'','',1); // Insertar el número de página
		uf_print_encabezado_pagina($ls_titulo,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la página
		
		$j=0;		
		$la_data_c="";
		$li_totrow_c=$io_report->ds->getRowCount("cod_pro");
		$li_total=0;
		$li_total_cantidad=0;
		
		for($li_j=1;$li_j<=$li_totrow_c;$li_j++)
		{
	        $io_pdf->transaction('start'); // Iniciamos la transacción
			$li_numpag=$io_pdf->ezPageCount; // Número de página
			$li_cod_pro=$io_report->ds->data["cod_pro"][$li_j];
			$li_nompro=$io_report->ds->data["nompro"][$li_j];
			$li_cantidad=$io_report->ds->data["cantidad"][$li_j];
			$li_total_cantidad=$li_total_cantidad + $li_cantidad;
			$li_codunidad=$io_report->ds->data["coduniadm"][$li_j];	
			$li_nomunidad=$io_report->ds->data["denuniadm"][$li_j];						
		
		    $lb_valido=$io_report->uf_select_tipo_bienes($li_cod_pro,$li_cod_pro,$ls_coddesde,$ls_codhasta,$ld_desde,$ld_hasta,$li_codunidad,$li_ordenact);
		    $li_totrow=$io_report->ds_detalle->getRowCount("codact");
		    $i=0;		
		    $la_data="";		    
		    $li_total_precio=0;
					
		    if($lb_valido)
		     {
		      for($li_i=1;$li_i<=$li_totrow;$li_i++)
		      {
				//$io_pdf->transaction('start'); // Iniciamos la transacción
				//$li_numpag=$io_pdf->ezPageCount; // Número de página
			
				$li_id=$io_report->ds_detalle->data["ideact"][$li_i];
				$li_codact=$io_report->ds_detalle->data["codact"][$li_i];
				$li_denact=$io_report->ds_detalle->data["denact"][$li_i];
				$li_grupo=$io_report->ds_detalle->data["grupo"][$li_i];					
				$li_costo=$io_report->ds_detalle->data["costo"][$li_i];	
				$li_total_precio= $li_total_precio + $li_costo;
				$li_total= $li_total + $li_costo;
				$li_costo=$io_fun_activos->uf_formatonumerico($li_costo);
				$li_cod_pro=$io_report->ds_detalle->data["cod_pro"][$li_i];				
				$li_numordcom=$io_report->ds_detalle->data["numordcom"][$li_i];	
				$li_fecordcom=$io_report->ds_detalle->data["fecordcom"][$li_i];					
				$li_marca=$io_report->ds_detalle->data["maract"][$li_i];	
				$li_modelo=$io_report->ds_detalle->data["modact"][$li_i];	
				$li_serial=$io_report->ds_detalle->data["seract"][$li_i];	
				$li_cuenta=$io_report->ds_detalle->data["spg_cuenta_act"][$li_i];	
				$li_partida=$io_report->ds_detalle->data["sc_cuenta"][$li_i];						
			
				$la_data[$li_i]=array('idact'=> $li_id,'codact'=>$li_codact,'denact'=>$li_denact,'grupo'=>$li_grupo,
			                      'costo'=>$li_costo,'numordcom'=>$li_numordcom,'fecordcom'=>$li_fecordcom,
								  'maract'=>$li_marca,'modact'=>$li_modelo,'seract'=>$li_serial,'spg_cuenta_act'=>$li_cuenta,'sc_cuenta'=>$li_partida);
		   	  }	
				if($la_data!="")
				{
					$i=$i +1;
					uf_print_cabecera($li_cod_pro,$li_nompro,$li_codunidad,$li_nomunidad,$li_cantidad,$io_pdf); // Imprimimos la cabecera del registro
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle					
					if ($io_pdf->ezPageCount==$li_numpag)
					{// Hacemos el commit de los registros que se desean imprimir
					    $li_total_precio  = $io_fun_activos->uf_formatonumerico($li_total_precio);
						uf_print_pie_cabecera($li_total_precio,$io_pdf);
						$io_pdf->transaction('commit');
					}
					else
					{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
						$io_pdf->transaction('rewind');
						$io_pdf->ezNewPage(); // Insertar una nueva página
						uf_print_cabecera($li_cod_pro,$li_nompro,$li_codunidad,$li_nomunidad,$li_cantidad,$io_pdf); // Imprimimos la cabecera del registro
						uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
						uf_print_pie_cabecera($li_total_precio,$io_pdf);						
					}
				}
		}		
		unset($la_data);			
	  }
	  $li_total  = $io_fun_activos->uf_formatonumerico($li_total);
	  uf_print_pie_de_pagina($li_total_cantidad,$li_total,&$io_pdf);
	  
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