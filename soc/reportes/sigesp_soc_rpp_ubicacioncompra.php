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
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 16/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		$io_encabezado=$io_pdf->openObject();		
		$io_pdf->saveState();		
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(10,$as_titulo);
		$tm=280-($li_tm/2);
		$io_pdf->addText($tm,730,12,"<b>".$as_titulo."</b>"); // Agregar el título
		$io_pdf->addText(507,735,9,"Fecha: ".date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(514,725,9,"Hora: ".date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------	
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_listado($la_data,&$io_pdf)
	{	 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 16/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		
		$la_columna=array('codigo'=>'<b>Código</b>',
						  'nombre'=>'<b>Proveedor</b>',
						  'estcondat'=>'<b>Tipo</b>',
						  'status'=>'<b>Estaus</b>',
						  'documento'=>'<b>Documento</b>',
						  'origen'=>'<b>Destino</b>');
						  
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas						 
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xPos'=>310, // Orientación de la tabla
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>150), // Justificación y ancho de la columna
									   'estcondat'=>array('justification'=>'center','width'=>45), // Justificación y ancho de la columna
						 			   'status'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'documento'=>array('justification'=>'center','width'=>100),
   						 			   'origen'=>array('justification'=>'left','width'=>130))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_numreg,$ad_totmon,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 16/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
	    $io_pdf->ezSetDy(-10);
		$la_data=array(array('name'=>'<b>N° de Registros:</b>'.$ad_numreg));				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>310, // Orientación de la tabla
						 'width'=>750, // Ancho de la tabla						 
						 'maxWidth'=>750, // Orientaci? de la tabla
						 'cols'=>array('name'=>array('justification'=>'left','width'=>520))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_sql.php");	
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("sigesp_soc_class_report.php");	
	require_once("../class_folder/class_funciones_soc.php");
	$in           = new sigesp_include();
	$con          = $in->uf_conectar();
	$io_sql       = new class_sql($con);	
	$io_funciones = new class_funciones();	
	$io_fun_soc   = new class_funciones_soc();
	$io_report    = new sigesp_soc_class_report($con);
	$ls_bolivares="Bs.";
		
	//----------------------------------------------------  Inicializacion de variables  -----------------------------------------------
	$lb_valido=false;
	//----------------------------------------------------  Parámetros del encabezado    -----------------------------------------------
	$ls_titulo ="UBICACION DE LAS ORDENES DE COMPRAS";	
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	
	$ls_numordcomdes=$io_fun_soc->uf_obtenervalor_get("txtnumordcomdes","");
	$ls_numordcomhas=$io_fun_soc->uf_obtenervalor_get("txtnumordcomhas","");
	$ls_codprodes=$io_fun_soc->uf_obtenervalor_get("txtcodprodes","");
	$ls_codprohas=$io_fun_soc->uf_obtenervalor_get("txtcodprohas","");
	$ls_estcondat=$io_fun_soc->uf_obtenervalor_get("rdtipo","");
	 
	//--------------------------------------------------------------------------------------------------------------------------------
	$rs_data = $io_report->uf_select_oc($ls_numordcomdes,$ls_numordcomhas,$ls_codprodes,$ls_codprohas,$ls_estcondat,&$lb_valido);
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		$ls_descripcion="Generó el Reporte de Ubicacion de Orden de Compra";
		//$lb_valido=$io_fun_soc->uf_load_seguridad_reporte("SOC","sigesp_soc_r_orden_ubicacioncompra.php",$ls_descripcion);
		if($lb_valido)	
		{
			error_reporting(E_ALL);
			set_time_limit(1800);
			$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
			uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la página
			$io_pdf->ezStartPageNumbers(578,47,9,'','',1); // Insertar el número de página
			$ldec_monto=0;
			$li_i=0;
			$li_row=$io_sql->num_rows($rs_data);
			if ($li_row>0)
			{     
				while((!$rs_data->EOF))
				{
					$ls_documento  = "";
					$ls_estatus  = "";
					$ls_origen  = "";
					$ls_numord  = $rs_data->fields["numordcom"];
					$ls_estcon  = $rs_data->fields["estcondat"];
					$ls_codpro  = $rs_data->fields["cod_pro"];
					$ls_estatus = $rs_data->fields["estcom"];
					$ls_fecord  = $rs_data->fields["fecordcom"];
					$ls_nombre  = $io_report->uf_select_nombre_proveedor($ls_codpro);
					$ls_fecha   = $io_funciones->uf_convertirfecmostrar($ls_fecord);	
					if($ls_estcon=="B") 
					{  
						$ls_estcondat="Bienes";  
						$ls_procede="SOCCOC";
					}
					
					if($ls_estcon=="S")
					{
						$ls_estcondat="Servicios";
						$ls_procede="SOCCOS";
					}
					if( ($ls_estcon=="-") || ($ls_estcon=="") )  {   $ls_estcondat="";  }
					
					if($ls_estatus==0){
					$status="Registro";	}
					
					if($ls_estatus==1){
					$status="Emitida";	}
					
					if($ls_estatus==2){
					$status="Comprometida (Procesada)";	}
					
					if($ls_estatus==3){
					$status="Anulada";}
					
					if($ls_estatus==4){
					$status="Ent. Compra";}
					
					if($ls_estatus==5){
					$status="Pre-Comprometida";	}
					
					if($ls_estatus==6){
					$status="Pre-Comp.Anulada";	}
					
					if($ls_estatus==7){
					$status="Servicio Recibido";}
					$rs_data1=$io_report->uf_load_ubicacion($ls_numord,$ls_codpro,$ls_procede,&$lb_valido);
					$li_rowdet=$io_sql->num_rows($rs_data1);
					if($li_rowdet>0)
					{
						while((!$rs_data1->EOF))
						{	
							$li_i++;
							$ls_documento  = $rs_data1->fields["documento"];
							$ls_estatus  = $rs_data1->fields["estatus"];
							$ls_origen  = $rs_data1->fields["origen"];
							if($ls_origen=="RD")
							{						
								$ls_origen="Recepcion de Documentos";
								switch($ls_estatus)
								{
									case "R": 
										$ls_estatus="Recibida";
										break;
									case "E": 
										$ls_estatus="Emitida";
										break;
									case "C": 
										$ls_estatus="Contabilizada";
										break;
									case "A": 
										$ls_estatus="Anulada";
										break;
								}
							}
							else
							{
								$ls_origen="Recepcion de Suministros";
							}
							$la_data[$li_i]= array('codigo'=>$ls_numord,'nombre'=>$ls_nombre,'estcondat'=>$ls_estcondat,
											 'status'=>$status,'documento'=>$ls_documento,'origen'=>$ls_origen);
							$rs_data1->MoveNext();
						}
					}
					else
					{
						$li_i++;
						$la_data[$li_i]= array('codigo'=>$ls_numord,'nombre'=>$ls_nombre,'estcondat'=>$ls_estcondat,
										 'status'=>$status,'documento'=>$ls_documento,'origen'=>$ls_origen);
					}
					$rs_data->MoveNext();
				}
			    uf_print_listado($la_data,$io_pdf); // Imprimimos el detalle 		
				if($lb_valido) // Si no ocurrio ningún error
				{
					$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
					$io_pdf->ezStream(); // Mostramos el reporte
				}
				else  // Si hubo algún error
				{
					print("<script language=JavaScript>");
					print("alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
					print("close();");
					print("</script>");		
				}
				unset($io_pdf);
			}
			else
			{
				print("<script language=JavaScript>");
				print("alert('No hay nada que reportar');"); 
				print("close();");
				print("</script>");		
			}				
		}	
		unset($io_report);
		unset($io_funciones);
	}	
?> 