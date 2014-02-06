<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Reporte de Evaluacion de Desempeño
//  ORGANISMO: IPSFA
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
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_srh;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("SRH","sigesp_srh_r_listado_pago_bono_x_merito.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo1,as_titulo2,as_titulo3,as_titulo4 // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 02/08/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();        
		$io_pdf->setStrokeColor(0,0,0);		
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],80,500,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo

		$io_pdf->addText(630,560,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(630,552,8,date("h:i a")); // Agregar la Hora


		$io_pdf->ezSetDy(5);
		$la_data=array(array('titulo1'=>'<b>'.$as_titulo.'</b>'));
					
		$la_columnas=array('titulo1'=>'');
					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 14, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
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
		
		uf_print_cabecera($io_pdf);
	
		$io_pdf->restoreState();
	    $io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	
	
     }// end function uf_print_encabezado_pagina
	 //-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera(&$io_pdf)
 	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//    Arguments:   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el detalle del reporte.
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 02/08/2008 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		  $io_pdf->ezSetY(450);
		
		$la_data[1]=array('codper'=>'<b>Código</b>',
		                  'nombper'=>'<b>Nombre y Apellido</b>',
						  'tipper'=>'<b>Tipo Personal</b>',
						  'codnom'=>'<b> Código Nómina</b>',
						  'monto'=>'<b>Monto Bono Mérito Bs.</b>');
		$la_columnas=array('codper'=>'',
		                   'nombper'=>'',
						   'tipper'=>'',
						   'codnom'=>'',
						   'monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>11, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos'=>'420',
						 'cols'=>array('codper'=>array('justification'=>'center','width'=>80), 
						 			   'nombper'=>array('justification'=>'center','width'=>230), 
						 			   'tipper'=>array('justification'=>'center','width'=>150),
									   'codnom'=>array('justification'=>'ceter','width'=>100), 
						 			   'monto'=>array('justification'=>'center','width'=>120)));
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		
	}// end function uf_print_cabecera
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	function uf_print_detalle($la_data,&$io_pdf)
 	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información				 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el detalle del reporte.
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 02/08/2008 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$la_columnas=array('codper'=>'',
		                   'nombper'=>'',
						   'tipper'=>'',
						   'codnom'=>'',
						   'monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>10, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos'=>'420',
						 'cols'=>array('codper'=>array('justification'=>'center','width'=>80), 
						 			   'nombper'=>array('justification'=>'left','width'=>230), 
						 			   'tipper'=>array('justification'=>'center','width'=>150),
									   'codnom'=>array('justification'=>'rigth','width'=>100), 
						 			   'monto'=>array('justification'=>'center','width'=>120)));
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	
//-----------------------------------------------------------------------------------------------------------------------------------
	
	
function uf_print_totales($as_totalpersonal, $as_totalpago,&$io_pdf)
 	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function:  uf_print_totales
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//				   as_totalpersonal	 // total de personas que se les pagará el bono por mérito
		//				   as_totalpago // total a pagar en Bs. por bono merito
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el detalle del reporte.
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 02/08/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('total1'=>'<b>TOTAL PERSONAL:    </b>'.$as_totalpersonal);
		$la_columnas=array('total1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>12, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>400, // Ancho de la tabla
						 'maxWidth'=>400, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos'=>'710',
						 'cols'=>array('total1'=>array('justification'=>'rigth','width'=>400)));
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('total1'=>'<b>TOTAL A PAGAR Bs.:   </b>'.$as_totalpago);
		$la_columnas=array('total1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>12, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>400, // Ancho de la tabla
						 'maxWidth'=>400, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos'=>'710',
						 'cols'=>array('total1'=>array('justification'=>'rigth','width'=>400)));
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);		
		
	}// end function uf_print_detalle
	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	
    require_once("../../shared/ezpdf/class.ezpdf.php");	
	require_once("class_folder/sigesp_srh_class_report.php");
	$io_report=new sigesp_srh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/utilidades/class_funciones_srh.php");
	$io_fun_srh=new class_funciones_srh('../../');
	require_once("../../sno/class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
       $ls_titulo="<b>LISTADO DE PAGOS BONOS POR MERITOS</b>"; 
	//--------------------------------------------------  Parámetros para Filtar el Reporte  --------------------------------
	$ld_fechades=$io_fun_srh->uf_obtenervalor_get("fechades","");
	$ld_fechahas=$io_fun_srh->uf_obtenervalor_get("fechahas","");
	$ls_codperdes=$io_fun_srh->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_srh->uf_obtenervalor_get("codperhas","");
	$ls_coduniadmdes=$io_fun_srh->uf_obtenervalor_get("coduniadm1","");
	$ls_coduniadmhas=$io_fun_srh->uf_obtenervalor_get("coduniadm2","");	//-----------------------------------------------------------------------------------------------------------------------------------
	global $la_data;
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_select_bonos_x_merito($ld_fechades,$ld_fechahas,$ls_codperdes,$ls_codperhas,$ls_coduniadmdes,$ls_coduniadmhas); // Cargar el DS con los datos del reporte
		if($lb_valido==false) // Existe algún error ó no hay registros
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			//print(" close();");
			print("</script>");
		}
		else  // Imprimimos el reporte
		{
			 error_reporting(E_ALL);
			 set_time_limit(1800);
			 $io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
			 $io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			 $io_pdf->ezSetCmMargins(4,3,3,3); // Configuración de los margenes en centímetros
			 $io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página
			 
			  uf_print_encabezado_pagina($ls_titulo,$io_pdf);
			 $li_totrow=$io_report->DS->getRowCount("codper");
			 $entro=false;
			 $entrar=false;
			 $ls_totalpuntos=0;
			 $ls_promedio=0;
			 $ls_totalpagar=0;
			 $cont=0;
			 $i=0;
			 
			 for($li_i=1;$li_i<=$li_totrow;$li_i++)
			 {
			    
				if ($li_i<$li_totrow)
				{
					 $ls_codper1=$io_report->DS->data["codper"][$li_i];	
					 $ls_codper2=$io_report->DS->data["codper"][$li_i+1];
					 if ($ls_codper1 != $ls_codper2)
					 {
					   if (($li_i-1)!=0)
						{
						  	$entrar=true;
						 						
						}
						elseif ($li_i==1)
						{
						  	$entrar=true;
						}
						
												
					 }// fin if ($ls_codper1 != $ls_codper2)
					
					
				 }
				else if ($li_i==$li_totrow)
				 {
					  $ls_codper1=$io_report->DS->data["codper"][$li_i];	
					  $ls_codper2=$io_report->DS->data["codper"][$li_totrow];
				 
				 }
			   
				
																	
				if ($ls_codper1 == $ls_codper2) 
				{
								
						
					$lb_valido2=$io_report->uf_select_persona_pago_bonos_x_merito($ls_codper1);	
					
					if (($lb_valido2) &&(!$entro))
					{			
						
						$entro=true;
						$ls_codigo=$io_report->DS2->data["codper"][1];
						$ls_tipper=$io_report->DS2->data["tipper"][1];
						$ls_codnom =$io_report->DS2->data["codnom"][1];
						$ls_nombreper=$io_report->DS2->data["nomper"][1];
						$ls_apellidoper=$io_report->DS2->data["apeper"][1];
						
						$ls_cadena=$ls_nombreper."  ".$ls_apellidoper;	
					}
					
						
					$i=$i+1;	
					$ls_total=$io_report->DS->data["total"][$li_i];	
					$ls_fecha=$io_report->DS->data["fecha"][$li_i];	
					$ls_escala=$io_report->DS->data["codpun"][$li_i];
					$ls_totalpuntos=$ls_totalpuntos + $ls_total;
					
			   }
			   elseif ($entrar)
			   {
					$entrar=false;
					$entro=false;
					$lb_valido2=$io_report->uf_select_persona_pago_bonos_x_merito($ls_codper1);	
					
					if (($lb_valido2) &&(!$entro))
					{			
						
						$entro=true;
						$ls_codigo=$io_report->DS2->data["codper"][1];
						$ls_tipper=$io_report->DS2->data["tipper"][1];
						$ls_codnom =trim ($io_report->DS2->data["codnom"][1]);
						$ls_nombreper=$io_report->DS2->data["nomper"][1];
						$ls_apellidoper=$io_report->DS2->data["apeper"][1];
						
						$ls_cadena=$ls_nombreper."  ".$ls_apellidoper;	
					}
					
					$i=$i+1;	
					$ls_total=$io_report->DS->data["total"][$li_i];	
					$ls_escala=$io_report->DS->data["codpun"][$li_i];
					$ls_totalpuntos=$ls_totalpuntos + $ls_total;				   
				    $ls_promedio=round($ls_totalpuntos/12);	
				    $io_report->uf_select_monto_bono_merito($ls_escala,$ls_promedio,$ls_monto);	
					$ls_monto1=$io_fun_nomina->uf_formatonumerico($ls_monto);					   
				    $la_data[$i]=array('codper'=>$ls_codper1,'nombper'=>$ls_cadena,'tipper'=>$ls_tipper,'codnom'=>$ls_codnom,
									     'monto'=>$ls_monto1);
					$cont=$cont+1;
					$ls_totalpagar= $ls_totalpagar+$ls_monto;
					$ls_totalpuntos=0;
					$ls_promedio=0;
					
			   }
			   
			   else 
			   {
			   	   $entro=false;			  
				    $i=$i+1;	
					$ls_total=$io_report->DS->data["total"][$li_i];	
					$ls_escala=$io_report->DS2->data["codpun"][$li_i];
					$ls_totalpuntos=$ls_totalpuntos + $ls_total;				   
				    $ls_promedio=round($ls_totalpuntos/12);	
				    $io_report->uf_select_monto_bono_merito($ls_escala,$ls_promedio,$ls_monto);
					$ls_monto1=$io_fun_nomina->uf_formatonumerico($ls_monto);						   
				    $la_data[$i]=array('codper'=>$ls_codper1,'nombper'=>$ls_cadena,'tipper'=>$ls_tipper,'codnom'=>$ls_codnom,
									     'monto'=>$ls_monto1);
					$ls_totalpagar= $ls_totalpagar+$ls_monto;
					$cont=$cont+1;
				    $ls_totalpuntos=0;
					$ls_promedio=0;
			   }			    
							
			  }
			   
			    $entro=false;
				$entrar=false;
				
				
				$ls_promedio=round($ls_totalpuntos/12);	
				$io_report->uf_select_monto_bono_merito($ls_escala,$ls_promedio,$ls_monto);
				$ls_monto1=$io_fun_nomina->uf_formatonumerico($ls_monto);						   
				$la_data[$i]=array('codper'=>$ls_codper1,'nombper'=>$ls_cadena,'tipper'=>$ls_tipper,'codnom'=>$ls_codnom,
							     'monto'=>$ls_monto1);
				$ls_totalpagar= $ls_totalpagar+$ls_monto;
				 uf_print_detalle($la_data,$io_pdf);
				 $ls_totalpagar=$io_fun_nomina->uf_formatonumerico($ls_totalpagar);
				 uf_print_totales($cont,$ls_totalpagar,$io_pdf);
			  
			  if($lb_valido) // Si no ocurrio ningún error
				{
					$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
					$io_pdf->ezStream(); // Mostramos el reporte
				}
				else // Si hubo algún error
				{
					print("<script language=JavaScript>");
					print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
					//print(" close();");
					print("</script>");		
				}
        }
	}
?>