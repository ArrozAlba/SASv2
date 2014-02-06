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
		// Fecha Creación: 21/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_listadopersonalporjubilarse.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_titulo2,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,900,40);
		$io_pdf->addText(50,30,9,'LEY DEL ESTATUTO SOBRE EL RÉGIMEN DE JUBILACIONES Y PENSIONES DE LOS FUNCIONARIOS O FUNCIONARIAS O EMPLEADOS O EMPLEADAS  DE LA ADMINISTRACIÓN PÚBLICA NACIONAL,');
		$io_pdf->addText(50,20,9,'DE LOS ESTADOS Y DE LOS MUNICIPIOS. Titulo1 DISPOSICIONES GENERALES, Artículo 3');
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=470-($li_tm/2);
		$io_pdf->addText($tm,540,18,$as_titulo); // Agregar el título
		
		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo2);
		$tm=512-($li_tm/2);
		$io_pdf->addText($tm,528,12,$as_titulo2); // Agregar el título
		
		$io_pdf->addText(912,560,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(918,553,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera(&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/07/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSety(520);
        $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(40,496,915,$io_pdf->getFontHeight(19));
        $io_pdf->setColor(0,0,0);
		$la_data[1]=array('nro'=>'<b>Nº</b>',
						  'codigo'=>'<b>Código</b>',
						  'nombre'=>'<b>Apellidos y Nombres</b>',
						  'edad'=>'<b>Edad</b>',
						  'fechanac'=>'<b>Fecha de Nacimiento</b>',
						  'fecha'=>'<b>Fecha de Ingreso a la Administración P</b>',	
						  'anoser'=>'<b>Años de Serv. Prev Adm. Pub.</b>',
						  'fechaing'=>'<b>Fecha de Ingreso a la Institución</b>',		
						  'anoserinst'=>'<b>Años de Serv. en la Inst.</b>',
						  'anoobr'=>'<b>Años de Serv. Personal Obrero</b>',
						  'fechajub'=>'<b>Fecha de Aprox. de Jub.</b>');
		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla				         
						 'cols'=>array('nro'=>array('justification'=>'center','width'=>25),
						 				'codigo'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>175), // Justificación y ancho de la columna
									   'edad'=>array('justification'=>'center','width'=>60),
						 			   'fechanac'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
									   'fecha'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
									   
									   'anoser'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna						 		
									   'fechaing'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
									   'anoserinst'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'anoobr'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'fechajub'=>array('justification'=>'center','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,'','',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla				         
						 'cols'=>array('nro'=>array('justification'=>'center','width'=>25),
						 				'codigo'=>array('justification'=>'center','width'=>55), // Justificación y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>175), // Justificación y ancho de la columna
									   'edad'=>array('justification'=>'center','width'=>60),
						 			   'fechanac'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
									   'fecha'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
									   
									   'anoser'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna						 		
									   'fechaing'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
									   'anoserinst'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'anoobr'=>array('justification'=>'center','width'=>80), // Justificación y ancho de la columna
									   'fechajub'=>array('justification'=>'center','width'=>90))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,'','',$la_config);
	}// end function uf_print_detalle
//--------------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------------	
	  function calcular_anos($fecha_1,$fecha_2)
	  {  
		  $c = date("Y",$fecha_1);	   
		  $b = date("m",$fecha_1);	  
		  $a = date("d",$fecha_1); 	  
		  $anos = date("Y",$fecha_2)-$c; 
	   
			  if(date("m",$fecha_2)-$b > 0){
		  
			  }elseif(date("m",$fecha_2)-$b == 0){
		 
			  if(date("d",$fecha_2)-$a <= 0)
			  {		  
			     $anos = $anos-1;	  
			  }
		  
			  }else{		  
			         $anos = $anos-1;		  
			       }  
		  return $anos;

		  	 
      } //FIN DE calcular_anos
//---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();	
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codnomdes=$io_fun_nomina->uf_obtenervalor_get("codnomdes","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");		
	$ls_masculino=$io_fun_nomina->uf_obtenervalor_get("masculino","");
	$ls_femenino=$io_fun_nomina->uf_obtenervalor_get("femenino","");
	$ls_fecdes=$io_fun_nomina->uf_obtenervalor_get("fecdes","");  
	$ls_fechas=$io_fun_nomina->uf_obtenervalor_get("fechas","");	//--------------------------------------------------------------------------------------------------------------------------------
	$ls_titulo="<b>Listado de Personal Por Jubilarse</b>";
	$ls_titulo2="<b>Desde </b>".$ls_fecdes."<b> Hasta </b>".$ls_fechas;
	
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{  
		$lb_valido=$io_report->uf_listado_personaljub($ls_codperdes,$ls_codperhas,$ls_codnomdes,$ls_codnomhas,$ls_femenino,$ls_masculino,$ls_orden);
	}
	if(($lb_valido==false)||($io_report->rs_data->RecordCount()==0)) // Existe algún error ó no hay registros
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
		$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(4,5,3,3); // Configuración de los margenes en centímetros		
		uf_print_encabezado_pagina($ls_titulo,$ls_titulo2,$io_pdf); // Imprimimos el encabezado de la página	  
		$io_pdf->ezStartPageNumbers(900,50,10,'','',1); // Insertar el número de página
		$li_totrow=$io_report->rs_data->RecordCount();
		uf_print_cabecera($io_pdf);
		$ls_confjub="";
		$ls_confjub=$io_report->uf_select_config('SNO', 'CONFIG','CONF JUB',$ls_confjub,'I');
		if ($ls_confjub)
		{
		   $ls_edadf="";
		   $ls_edadf=$io_report->uf_select_config('SNO', 'NOMINA', 'EDADF', $ls_edadf, 'C');
		   $ls_edadm="";
		   $ls_edadm=$io_report->uf_select_config('SNO', 'NOMINA', 'EDADM', $ls_edadm, 'C');
		}
		$ls_anomim="";
		$ls_anomim=$io_report->uf_select_config('SNO', 'NOMINA', 'ANOM', $ls_anomim, 'C');
		
		$ls_anomax="";
		$ls_anomax=$io_report->uf_select_config("SNO","NOMINA","ANOT", $ls_anomax, 'C');
		$fecha_actual=$io_fecha->uf_convert_date_to_db($ls_fechas);  
		$ls_data="";	
		$li_i=0;
		while ((!$io_report->rs_data->EOF)&&($lb_valido))	
		{
			
			$ls_codper=$io_report->rs_data->fields["codper"];
			$ls_nomber=$io_report->rs_data->fields["nomper"];
			$ls_sexo=$io_report->rs_data->fields["sexper"];
			$ls_apellido=$io_report->rs_data->fields["apeper"];
			$ls_fechaIng=$io_report->rs_data->fields["fecingper"];	
			$ls_desnom=$io_report->rs_data->fields["desnom"];
			$ls_descom=$io_report->rs_data->fields["descom"];	
			$ls_desran=$io_report->rs_data->fields["desran"];
			$ls_anoser=$io_report->rs_data->fields["anoservpreper"];
			$ls_anoserfijo=$io_report->rs_data->fields["anoservprefijo"];
			$ls_fechanac=$io_report->rs_data->fields["fecnacper"];
			$ls_edad=calcular_anos(strtotime($ls_fechanac),strtotime($fecha_actual));
			$ls_fechaadminpub=$io_report->rs_data->fields["fecingadmpubper"];
			$ls_fechajub=$io_report->rs_data->fields["fecjubper"];
			$li_anoperobr=trim($io_report->rs_data->fields["anoperobr"]);
			$li_codtippersss=$io_report->rs_data->fields["codtippersss"];
			if($li_anoperobr=="")
			{
				$li_anoperobr=0;
			}
			
			$ls_anoserinst=calcular_anos(strtotime($ls_fechaIng),strtotime($fecha_actual));
			$ls_anoseradmpub=calcular_anos(strtotime($ls_fechaadminpub),strtotime($fecha_actual));
			if ($ls_anoserfijo!=0)
			{
				$ls_anot=$ls_anoserfijo+$ls_anoserinst;
				$ls_anoser=$ls_anoserfijo;
			}
			else
			{
				if ($ls_anoseradmpub==$ls_anomim)
				{
					$ls_anot=$ls_anoseradmpub;
				}
				else
				{
					$ls_anot=$ls_anoser+$ls_anoserinst;
				}
			}	
			
			$fecingtrabant="";
			$lb_existe=$io_report->uf_buscar_fechaingtrabant($ls_codper,$fecingtrabant);
		
			if (($lb_existe)&&($fecingtrabant!=""))
			{
				$ls_ano1=substr($fecingtrabant,0,4); 
				$ls_fechaadminpub=$fecingtrabant;
			}
			else
			{
				$ls_ano1=substr($ls_fechaadminpub,0,4); 
			}
			if ($ls_confjub=="1")
			{
				$ls_ano2=$ls_ano1+$ls_anomim;
				if ($ls_anoser > 0)
				{
					$ls_ano2=$ls_ano2-$ls_anoser;
				}
			}
			else
			{
				$ls_ano2=$ls_ano1+$ls_anomax;
			}		
			
			if (($lb_existe)&&($fecingtrabant!=""))
			{
				$ls_fechaaprox=$ls_ano2.substr($fecingtrabant,4,10);
			}
			else
			{			
				$ls_fechaaprox=$ls_ano2.substr($ls_fechaadminpub,4,10);
			}
			
			if ($ls_confjub=="1")
			{
				if ($li_codtippersss!='0000010' && $li_codtippersss!='0000014')
				{
						
					$ls_edad_jub=calcular_anos(strtotime($ls_fechanac),strtotime($ls_fechaaprox));
					if ((trim($ls_sexo)=="F") && ($ls_edad_jub<$ls_edadf))
					{
						if (intval(substr($ls_fechanac,0,4))+intval($ls_edadf)==intval(substr($ls_fechaaprox,0,4)))
						{
							if ( intval(substr($ls_fechanac,5,2)) > intval(substr($ls_fechaaprox,5,2)) )
							{
								$ls_ano4=substr($ls_fechaaprox,0,4);
								$ls_fechaaprox=$ls_ano4.substr($ls_fechanac,4,10);
							}	
							elseif( intval(substr($ls_fechanac,5,2)) == intval(substr($ls_fechaaprox,5,2)) )
							{
								if( intval(substr($ls_fechanac,8,2)) >= intval(substr($ls_fechaaprox,8,2)) )
								{
									$ls_ano4=intval(substr($ls_fechanac,0,4))+$ls_edadf;
									$ls_fechaaprox=$ls_ano4.substr($ls_fechanac,4,10);
								}
								else
								{
									$ls_ano3=$ls_edadf-$ls_edad_jub;				
									$ls_ano4=$ls_ano2+$ls_ano3;
									$ls_fechaaprox=$ls_ano4.substr($ls_fechanac,4,10);
								}
							}
							else
							{
								$ls_ano3=$ls_edadf-$ls_edad_jub;				
								$ls_ano4=$ls_ano2+$ls_ano3;
								$ls_fechaaprox=$ls_ano4.substr($ls_fechanac,4,10);
							}
							
							
						}	
						else
						{	
							$ls_ano3=$ls_edadf-$ls_edad_jub;				
							$ls_ano4=$ls_ano2+$ls_ano3;
							$ls_fechaaprox=$ls_ano4.substr($ls_fechanac,4,10);
							$ls_edadprox=calcular_anos(strtotime($ls_fechanac),strtotime($ls_fechaaprox));						
							if ($ls_edadprox>=$ls_edadf)
							{
								$ls_ano5=$ls_ano4-1;					
								$ls_fechaaprox=$ls_ano5.substr($ls_fechanac,4,10);
							}
								
						}
					}
					elseif((trim($ls_sexo)=="M") && ($ls_edad_jub<$ls_edadm))
					{
						if (intval(substr($ls_fechanac,0,4))+intval($ls_edadm)==intval(substr($ls_fechaaprox,0,4)))
						{
							if ( intval(substr($ls_fechanac,5,2)) > intval(substr($ls_fechaaprox,5,2)) )
							{
								$ls_ano4=substr($ls_fechaaprox,0,4);
								$ls_fechaaprox=$ls_ano4.substr($ls_fechanac,4,10);
							}	
							elseif( intval(substr($ls_fechanac,5,2)) == intval(substr($ls_fechaaprox,5,2)) )
							{
								if( intval(substr($ls_fechanac,8,2)) >= intval(substr($ls_fechaaprox,8,2)) )
								{
									$ls_ano4=intval(substr($ls_fechanac,0,4))+$ls_edadm;
									$ls_fechaaprox=$ls_ano4.substr($ls_fechanac,4,10);	
								}
								else
								{
									$ls_ano3=$ls_edadm-$ls_edad_jub;				
									$ls_ano4=$ls_ano2+$ls_ano3;
									$ls_fechaaprox=$ls_ano4.substr($ls_fechanac,4,10);
								}
							}
						}	
						else
						{   
							$ls_ano3=$ls_edadm-$ls_edad_jub;
							$ls_ano4=$ls_ano2+$ls_ano3;					
							$ls_fechaaprox=$ls_ano4.substr($ls_fechanac,4,10);
							$ls_edadprox=calcular_anos(strtotime($ls_fechanac),strtotime($ls_fechaaprox));						
							if ($ls_edadprox>=$ls_edadm)
							{
								$ls_ano5=$ls_ano4-1;					
								$ls_fechaaprox=$ls_ano5.substr($ls_fechanac,4,10);
							}
						}
					}
					elseif ((trim($ls_sexo)=="F") && ($ls_edad_jub>=$ls_edadf) && ($ls_anot<$ls_anomim) )
					{
						$ls_ano3=$ls_anomim-$ls_anot;
						$ls_ano4=$ls_ano2+$ls_ano3;
						$ls_fechaaprox=$ls_ano4.substr($ls_fechaadminpub,4,10);
					
					}
					elseif ((trim($ls_sexo)=="M") && ($ls_edad_jub>=$ls_edadm) && ($ls_anot<$ls_anomim) )
					{
					
						$ls_ano3=$ls_anomim-$ls_anot;
						$ls_ano4=$ls_ano2+$ls_ano3;
						$ls_fechaaprox=$ls_ano4.substr($ls_fechaadminpub,4,10);
					
					}
					
				
				
					$ls_fechaaprox=$io_funciones->uf_convertirfecmostrar($ls_fechaaprox);	
					$valido1=$io_fecha->uf_comparar_fecha($ls_fecdes,$ls_fechaaprox);
					$valido2=$io_fecha->uf_comparar_fecha($ls_fechaaprox,$ls_fechas);
				
					if(trim($ls_sexo)=="F")
					{
					   if ($ls_edad>=$ls_edadf)
					   {
							if ($ls_anot>=$ls_anomim)	
							{
								if (($ls_fechajub=='1900-01-01')&&($valido1)&&($valido2))
								{ 
									$li_i++;
									$ls_anodif = intval(substr($ls_fechas,6,4)) - intval(substr($ls_fechaaprox,6,4));
									$ls_anoserinst = $ls_anoserinst - $ls_anodif;
									$ls_edad=$ls_edad- $ls_anodif;
									$ls_data[$li_i]=array('nro'=>$li_i,'codigo'=>$ls_codper,'nombre'=>$ls_apellido.", ".$ls_nomber,
														 'edad'=>$ls_edad,
														 'fechanac'=>$io_funciones->uf_convertirfecmostrar($ls_fechanac),
														 'fecha'=>$io_funciones->uf_convertirfecmostrar($ls_fechaadminpub),
														 'anoser'=>$ls_anoser,
														 'fechaing'=>$io_funciones->uf_convertirfecmostrar($ls_fechaIng),
														 'anoserinst'=>$ls_anoserinst,'anoobr'=>$li_anoperobr,
														 'fechajub'=>$io_funciones->uf_convertirfecmostrar($ls_fechaaprox));	
								}	
							}// fin de los años de servicios
					   }//fin del if de la edad
					}
					else
					{
					 
					 
					  if ($ls_edad>=$ls_edadm)
					   {
							if ($ls_anot>=$ls_anomim)	
							{   
								if (($ls_fechajub=='1900-01-01')&&($valido1)&&($valido2))
								{   
									$li_i++;
									$ls_anodif = intval(substr($ls_fechas,6,4)) - intval(substr($ls_fechaaprox,6,4));
									$ls_anoserinst = $ls_anoserinst - $ls_anodif;
									$ls_edad=$ls_edad- $ls_anodif;
									$ls_data[$li_i]=array('nro'=>$li_i,'codigo'=>$ls_codper,'nombre'=>$ls_apellido.", ".$ls_nomber,
														 'edad'=>$ls_edad,
														 'fechanac'=>$io_funciones->uf_convertirfecmostrar($ls_fechanac),
														 'fecha'=>$io_funciones->uf_convertirfecmostrar($ls_fechaadminpub),
														 'anoser'=>$ls_anoser,
														 'fechaing'=>$io_funciones->uf_convertirfecmostrar($ls_fechaIng),
														 'anoserinst'=>$ls_anoserinst,'anoobr'=>$li_anoperobr,
														 'fechajub'=>$io_funciones->uf_convertirfecmostrar($ls_fechaaprox));	
								}	
							}// fin de los años de servicios
					   }//fin del if de la edad
					}				
				
			     }//fin del $ls_confjub
			}
			else
			{
				if ($li_codtippersss!='0000010' && $li_codtippersss!='0000014')
				{
					
					$ls_fechaaprox=$io_funciones->uf_convertirfecmostrar($ls_fechaaprox);			
					$valido1=$io_fecha->uf_comparar_fecha($ls_fecdes,$ls_fechaaprox);
					$valido2=$io_fecha->uf_comparar_fecha($ls_fechaaprox,$ls_fechas);
					if ($ls_anot>=$ls_anomax)	
					{
						if (($ls_fechajub=='1900-01-01')&&($valido1)&&($valido2))
						{ 
							$li_i++;
							$ls_anodif = intval(substr($ls_fechas,6,4)) - intval(substr($ls_fechaaprox,6,4));
							$ls_anoserinst = $ls_anoserinst - $ls_anodif;
							$ls_edad=$ls_edad- $ls_anodif;
							$ls_data[$li_i]=array('nro'=>$li_i,'codigo'=>$ls_codper,'nombre'=>$ls_apellido.", ".$ls_nomber,
												'edad'=>$ls_edad,
												'fechanac'=>$io_funciones->uf_convertirfecmostrar($ls_fechanac),
												'fecha'=>$io_funciones->uf_convertirfecmostrar($ls_fechaadminpub),
												'anoser'=>$ls_anoser,
												'fechaing'=>$io_funciones->uf_convertirfecmostrar($ls_fechaIng),											
												'anoserinst'=>$ls_anoserinst,'anoobr'=>$li_anoperobr,
												'fechajub'=>$io_funciones->uf_convertirfecmostrar($ls_fechaaprox));	
						}	
					}// fin de los años de servicios
				
				}	
			}
							
			$io_report->rs_data->MoveNext();
		}
		if ($ls_data!="")
		{
			uf_print_detalle($ls_data,&$io_pdf);
			unset($la_data);			
		}
		$io_report->DS->resetds("codper");
		if(($lb_valido)&&($ls_data!="")) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que reportar');"); 
			print(" close();");
			print("</script>");		
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 