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
	ini_set('memory_limit','512M');
	ini_set('max_execution_time','0');

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/09/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_scf;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_scf->uf_load_seguridad_reporte("SCF","sigesp_scf_r_balance_comprobacion.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 18/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_nomemp=$_SESSION["la_empresa"]["nombre"];		
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,9,$as_titulo); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(10,$ls_nomemp);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,750,9,'<b>'.$ls_nomemp.'<b>'); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(11,$as_fecha);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,715,9,$as_fecha); // Agregar el título
		
		$io_pdf->addText(500,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(506,743,7,date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_cuenta,$as_denominacion,$ad_saldo_ant,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_cuenta // cuenta
		//	    		   as_denominacion // denominacion 
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 18/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$la_data=array(array('name'=>'<b>Cuenta</b> '.$as_cuenta.'  -----  '.$as_denominacion.''),
		               array('name'=>'<b>Saldo Anterior</b> '.$ad_saldo_ant.' '));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 7, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array(0.9,0.9,0.9),
						 'shadeCo2'=>array(0.9,0.9,0.9),
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
    
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 18/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//---------------------------------------------------------------------------------------------------------------------------
	        $la_data_t[1]=array('denominacion'=>'',						   
						        'debe'=>    '<b>  Balance de Comprobación </b>',						        
						        'egreso'=>'   <b> Ejecucuión Presupuestaria </b>',						        
						        'debe_g'=>'<b>Balance General</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 6,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>299, // Orientación de la tabla
						 'cols'=>array('denominacion'=>array('justification'=>'left','width'=>190), // Justificación y ancho de la columna
						 			   'debe'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
						 			   'egreso'=>array('justification'=>'center','width'=>130),									   
									   'debe_g'=>array('justification'=>'center','width'=>120))); // Justificación y ancho de la columna
		
		$la_columnas=array('denominacion'=>'                        <b>Denominación</b>',						   
						   'debe'=>'<b>Debe</b>              ',						   
						   'egreso'=>'<b>Egreso</b>              ',					   
						   'debe_g'=>'<b>Debe</b>              ');
		$io_pdf->ezTable($la_data_t,$la_columnas,'',$la_config);		
		
		//---------------------------------------------------------------------------------------------------------------------------
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>299, // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>130), // Justificación y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
						 			   'haber'=>array('justification'=>'right','width'=>60),
									   'egreso'=>array('justification'=>'right','width'=>60),
									   'ingreso'=>array('justification'=>'right','width'=>60),
									   'debe_g'=>array('justification'=>'right','width'=>60),
									   'haber_g'=>array('justification'=>'right','width'=>60))); // Justificación y ancho de la columna
		
		$la_columnas=array('cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'                        <b>Denominación</b>',						   
						   'debe'=>'<b>Debe</b>              ',
						   'haber'=>'<b>Haber</b>              ',
						   'egreso'=>'<b>Egreso</b>              ',
						   'ingreso'=>'<b>Ingreso</b>              ',
						   'debe_g'=>'<b>Debe</b>              ',
						   'haber_g'=>'<b>Haber</b>              ');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($adec_totaldebe,$adec_totalhaber,$adec_total_saldo,$as_total_egreso,$as_total_ingreso,
	                               $as_debe_g,$as_haber_g,$as_resul_1,$as_resul_2,$as_denomina,
								   $as_resul_Balnace_1,$as_resul_Balnace_2,$as_suma1,$as_suma2,
								   $as_suma_Balance_1,$as_suma_Balance_2,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private 
		//	    Arguments : ad_total // Total General
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 18/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		$as_total_egreso=number_format($as_total_egreso,2,",",".");
		$as_total_ingreso=number_format($as_total_ingreso,2,",",".");
		$as_debe_g=number_format($as_debe_g,2,",",".");
		$as_haber_g=number_format($as_haber_g,2,",",".");		
		$la_data[1]=array('total'=>'<b>Total '.$ls_bolivares.'</b>',
		                        'debe'=>$adec_totaldebe,
							    'haber'=>$adec_totalhaber,
							    'egreso'=>$as_total_egreso,
							    'ingreso'=>$as_total_ingreso,
							    'debe_g'=>$as_debe_g,
							    'haber_g'=>$as_haber_g);
		$la_data[2]=array('total'=>$as_denomina,
		                        'debe'=>'',
							    'haber'=>'',
							    'egreso'=>$as_resul_1,
							    'ingreso'=>$as_resul_2,
							    'debe_g'=> $as_resul_Balnace_1,
							    'haber_g'=> $as_resul_Balnace_2);
		$la_data[3]=array('total'=>'',
		                        'debe'=>'',
							    'haber'=>'',
							    'egreso'=> '---------------------',
							    'ingreso'=>'---------------------',
							    'debe_g'=> '---------------------',
							    'haber_g'=>'---------------------');
	    $la_data[4]=array('total'=>'',
		                        'debe'=>'',
							    'haber'=>'',
							    'egreso'=>$as_suma1,
							    'ingreso'=>$as_suma2,
							    'debe_g'=> $as_suma_Balance_1,
							    'haber_g'=> $as_suma_Balance_2);
		$la_columna=array('total'=>'','debe'=>'','haber'=>'','egreso'=>'','ingreso'=>'','debe_g'=>'','haber_g'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'colGap'=>1, // separacion entre tablas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>299, // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>200), // Justificación y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
						 			   'haber'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
									   'egreso'=>array('justification'=>'right','width'=>60),
									   'ingreso'=>array('justification'=>'right','width'=>60),
									   'debe_g'=>array('justification'=>'right','width'=>60),
									   'haber_g'=>array('justification'=>'right','width'=>60))); // Justificación y ancho de la columna
						 			  
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_init_niveles()
	{	///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_init_niveles
		//	     Access: public
		//	    Returns: vacio	 
		//	Description: Este método realiza una consulta a los formatos de las cuentas
		//               para conocer los niveles de la escalera de las cuentas contables  
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones,$ia_niveles_scf;
		
		$ls_formato=""; $li_posicion=0; $li_indice=0;
		$dat_emp=$_SESSION["la_empresa"];
		//contable
		$ls_formato = trim($dat_emp["formcont"])."-";
		$li_posicion = 1 ;
		$li_indice   = 1 ;
		$li_posicion = $io_funciones->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		do
		{
			$ia_niveles_scf[$li_indice] = $li_posicion;
			$li_indice   = $li_indice+1;
			$li_posicion = $io_funciones->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		} while ($li_posicion>=0);
	}// end function uf_init_niveles
	//-----------------------------------------------------------------------------------------------------------------------------------

		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();			
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
		require_once("../class_folder/class_funciones_scf.php");
		$io_fun_scf=new class_funciones_scf("../../");
		require_once("sigesp_scf_class_bal_general.php");
		$io_report_bal_g = new sigesp_scf_class_bal_general();
		$ls_tiporeporte="0";
		$ls_bolivares="";
		if (array_key_exists("tiporeporte",$_GET))
		{
			$ls_tiporeporte=$_GET["tiporeporte"];
		}
		switch($ls_tiporeporte)
		{
			case "0":
				require_once("sigesp_scf_class_report.php");
				$io_report  = new sigesp_scf_class_report();
				$ls_bolivares ="Bs.";
				break;
	
			case "1":
				require_once("sigesp_scf_class_reportbsf.php");
				$io_report  = new sigesp_scf_class_reportbsf();
				$ls_bolivares ="Bs.F.";
				break;
		}
		$ia_niveles_scf[0]="";			
		uf_init_niveles();
		$li_total=count($ia_niveles_scf)-1;
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ld_fecdes=$_GET["fecdes"];
	$ld_fechas=$_GET["fechas"];
	$ls_cuentadesde=$_GET["cuentadesde"];
	$ls_cuentahasta=$_GET["cuentahasta"];
	$li_nivel=$_GET["nivel"];
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
		$ldt_fecha=" <b>Desde  ".$ld_fecdes."  al ".$ld_fechas."</b> ";
		$ls_titulo=" <b>HOJA DE TRABAJO</b> ";       
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
	$lb_valido=uf_insert_seguridad("<b>Hoja de Trabajo en PDF</b>"); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_balancecomprobacion($ld_fecdes,$ld_fechas,$ls_cuentadesde,$ls_cuentahasta,$li_nivel);
	}
	/*	if($lb_valido)
	{
		$lb_valido=$io_report_bal_g->uf_balance_general($ld_fechas,$li_nivel,$ls_cuentadesde,$ls_cuentahasta);
	}*/
	
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
		//set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ldt_fecha,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$li_tot=$io_report->DS->getRowCount("sc_cuenta");
		$ldec_totaldebe=0;
		$ldec_totalhaber=0;
		$ldec_total_saldo=0;
        $ld_saldo=0;
		$ldec_mondeb=0;
        $ldec_monhab=0;
		$ls_total_egreso=0;
		$ls_total_ingreso=0;
		$ls_egreso=0;
		$ls_ingreso=0;
		$ls_tot_debe_g=0;
		$ls_tot_haber_g=0;
		for($i=1;$i<=$li_tot;$i++)
		{
		    $li_tmp=($i+1);
			$thisPageNum=$io_pdf->ezPageCount;
			$ls_cuenta=rtrim($io_report->DS->getValue("sc_cuenta",$i));

					$li_totfil=0;
					$as_cuenta="";
					/////////para el formato de las cuantas/////////////////////////////////////////////////
					for($li=$li_total;$li>1;$li--)
					{
						$li_ant=$ia_niveles_scf[$li-1];
						$li_act=$ia_niveles_scf[$li];
						$li_fila=$li_act-$li_ant;
						$li_len=strlen($ls_cuenta);
						$li_totfil=$li_totfil+$li_fila;
						$li_inicio=$li_len-$li_totfil;
						if($li==$li_total)
						{
							$as_cuenta=substr($ls_cuenta,$li_inicio,$li_fila);
						}
						else
						{
							$as_cuenta=substr($ls_cuenta,$li_inicio,$li_fila)."-".$as_cuenta;
						}
					}
					$li_fila=$ia_niveles_scf[1]+1;
					$as_cuenta=substr($ls_cuenta,0,$li_fila)."-".$as_cuenta;					
					//////////////////////////////////////////////////////////////////////////////////////////
			///--------------------el balance de comprobacion-------------------------------------------------
			$ls_denominacion=rtrim($io_report->DS->getValue("denominacion",$i));
            $ls_tipo_cta=substr($as_cuenta,0,2);
			$ls_tipo_cta2=substr($as_cuenta,0,1);			
			$ldec_debe=$io_report->DS->getValue("debe_mes",$i);
			$ldec_haber=$io_report->DS->getValue("haber_mes",$i);
			$ldec_saldo_ant=($io_report->DS->getValue("debe_mes_ant",$i)-$io_report->DS->getValue("haber_mes_ant",$i));
			$ldec_saldo_act=$ldec_saldo_ant+$ldec_debe-$ldec_haber;
			$ldec_BalDebe=$io_report->DS->getValue("total_debe",$i);
			$ldec_BalHABER=$io_report->DS->getValue("total_haber",$i);
			$ldec_status=$io_report->DS->getValue("status",$i);
			$ldec_nivel=$io_report->DS->getValue("nivel",$i);
			
			if ($ldec_status=='S')
			{
		     if ($ldec_nivel==1)
		     { 		
			   $ldec_totaldebe=$ldec_totaldebe+$ldec_BalDebe;
			   $ldec_totalhaber=$ldec_totalhaber+$ldec_BalHABER;
		     }
			}
		   		
			$ldec_saldo=$ldec_saldo_act;
			
			if($ldec_debe<0)
			{
			    $ldec_debe_aux=abs($ldec_debe);
				$ldec_debe_aux=number_format($ldec_debe_aux,2,",",".");
				$ldec_debe="(".$ldec_debe_aux.")";
			}
			else
			{
			     if($ls_tipo_cta=="31")
			      {
				    $ls_egreso=$ldec_debe;
				    if ($ldec_status=='S')
				    {
				       if ($ldec_nivel==1)
				       {
				    	$ls_total_egreso=$ls_total_egreso+$ls_egreso;
				       }
				    }
					  $ls_egreso=number_format($ls_egreso,2,",",".");
				  }
				  else 
				  {
				  	 $ls_egreso=0;
				  	 $ls_egreso=number_format($ls_egreso,2,",",".");
				  }
				  $ldec_debe_g=$ldec_debe;	
				  $ldec_debe=number_format($ldec_debe,2,",",".");
			}
			if($ldec_haber<0)
			{
			    $ldec_haber_aux=abs($ldec_haber);
				$ldec_haber_aux=number_format($ldec_haber_aux,2,",",".");
				$ldec_haber="(".$ldec_haber_aux.")";
			}
			else
			{
			     if($ls_tipo_cta=="32")
			      {
				     $ls_ingreso=$ldec_haber;
				     if ($ldec_status=='S')
				     {
				     	if ($ldec_nivel==1)
				     	{
				     	 $ls_total_ingreso=$ls_total_ingreso+$ls_ingreso;
				     	}				     
				     }
					 $ls_ingreso=number_format($ls_ingreso,2,",",".");					
				  }
				  else 
				  {
				  	$ls_ingreso=0;	
				  	$ls_ingreso=number_format($ls_ingreso,2,",",".");
				  }
				 $ldec_haber_g=$ldec_haber;
				 $ldec_haber=number_format($ldec_haber,2,",",".");
			}
			if($ldec_saldo<0)
			{
			    $ldec_saldo_aux=abs($ldec_saldo);
				$ldec_saldo_aux=number_format($ldec_saldo_aux,2,",",".");
				$ldec_saldo="(".$ldec_saldo_aux.")";
			}
			else
			{
			    $ldec_saldo=number_format($ldec_saldo,2,",",".");				
			}
			if($ldec_saldo_ant<0)
			{
			    $ldec_saldo_ant_aux=abs($ldec_saldo_ant);
				$ldec_saldo_ant_aux=number_format($ldec_saldo_ant_aux,2,",",".");
				$ldec_saldo_ant="(".$ldec_saldo_ant_aux.")";
			}
			else
			{
               $ldec_saldo_ant=number_format($ldec_saldo_ant,2,",",".");
			}
			///-----------------------------------termina el balance de comprobación---------------------------------------
			///-------------------------------------balance general--------------------------------------------------------
		
			
			 if($ldec_debe_g<0)
			{
			    $ldec_debe_aux_g=abs($ldec_debe_g);
				$ldec_debe_aux_g=number_format($ldec_debe_aux_g,2,",",".");
				$ldec_debe_g="(".$ldec_debe_aux_g.")";
			}
			else
			{
			    if($ls_tipo_cta2=="3")
			      {
				     $ldec_debe_g=0;
				     $ldec_debe_g=number_format($ldec_debe_g,2,",",".");
					
				  }
				else
				{
				 if ($ldec_status=='S')
				 { 
				 	if ($ldec_nivel==1)
				 	{
				 	  $ls_tot_debe_g=$ls_tot_debe_g+$ldec_debe_g;
				 	}
				 }				 	  
			      $ldec_debe_g=number_format($ldec_debe_g,2,",",".");
				}
			}
			if($ldec_haber_g<0)
			{
			    $ldec_haber_aux_g=abs($ldec_haber_g);
				$ldec_haber_aux=number_format($ldec_haber_aux_g,2,",",".");
				$ldec_haber_g="(".$ldec_haber_aux_g.")";
			}
			else
			{   
			    if ($ls_tipo_cta2=="3")
			      {
				    $ldec_haber_g=0;
				    $ldec_haber_g=number_format($ldec_haber_g,2,",",".");
				  }
				  else
				  { 
				   if ($ldec_status=='S')	
				   {
				   	if ($ldec_nivel==1)
				   	{
				   	 $ls_tot_haber_g=$ls_tot_haber_g+$ldec_haber_g;
				   	}
				   }
			        $ldec_haber_g=number_format($ldec_haber_g,2,",",".");
				  }
			}
		
			////-----------------------------------------fin de balance general------------------------------------------------
			
			//----------------------------arreglo que contiene la informaciòn completa---------------------------------------
			$la_data[$i]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'debe'=>$ldec_debe,'haber'=>$ldec_haber,
			                   'egreso'=>$ls_egreso,'ingreso'=>$ls_ingreso,'debe_g'=>$ldec_debe_g,'haber_g'=>$ldec_haber_g);
			//---------------------------------fin del arreglo----------------------------------------------------------------

		}//for

		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
		
		if($ldec_totaldebe<0)
		{
			$ldec_totaldebe_aux=abs($ldec_totaldebe);
			$ldec_totaldebe_aux=number_format($ldec_totaldebe_aux,2,",",".");
			$ldec_totaldebe="(".$ldec_totaldebe_aux.")";
		}
		else
		{
		    $ldec_totaldebe=number_format($ldec_totaldebe,2,",",".");
		}
		if($ldec_totalhaber<0)
		{
			$ldec_totalhaber_aux=abs($ldec_totalhaber);
			$ldec_totalhaber_aux=number_format($ldec_totalhaber_aux,2,",",".");
			$ldec_totalhaber="(".$ldec_totalhaber_aux.")";
		}
		else
		{
		   $ldec_totalhaber=number_format($ldec_totalhaber,2,",",".");
		}
		$ldec_total_saldo=($ldec_totaldebe-$ldec_totalhaber);
		if($ldec_total_saldo<0)
		{
			$ldec_total_saldo_aux=abs($ldec_total_saldo);
			$ldec_total_saldo_aux=number_format($ldec_total_saldo_aux,2,",",".");
			$ldec_total_saldo="(".$ldec_total_saldo_aux.")";
		}
		else
		{
		   $ldec_total_saldo=number_format($ldec_total_saldo,2,",",".");
		}
		
		$ls_resul_1=$ls_total_ingreso-$ls_total_egreso;
		$ls_resul_2="";
		if($ls_resul_1<0)
		  {
		    $ls_resul_2=(-1*$ls_resul_1);
			$ls_resul_1="";
			$ls_resul_Balnace_2="";
			$ls_resul_Balnace_1=$ls_resul_2;
			$ls_suma_Balance_1=$ls_tot_debe_g+$ls_resul_Balnace_1;
			$ls_suma_Balance_2="";
			$ls_suma2= $ls_resul_2+$ls_total_ingreso;
			$ls_suma1="";
			$ls_resul_Balnace_1=number_format($ls_resul_Balnace_1,2,",",".");
			$ls_resul_2=number_format($ls_resul_2,2,",",".");			
		    $ls_suma2=number_format($ls_suma2,2,",",".");
		    $ls_suma_Balance_1=number_format($ls_suma_Balance_1,2,",",".");
			$ls_denomina="Desahorro";
		  }
		else
		{
		   $ls_resul_2="";
		   $ls_resul_1=$ls_resul_1;
		   $ls_suma1= $ls_resul_1+$ls_total_egreso;
		   $ls_suma2="";
		   $ls_resul_Balnace_2=$ls_resul_1;
		   $ls_suma_Balance_2=$ls_tot_haber_g+$ls_resul_Balnace_2;
		   $ls_suma_Balance_1="";
		   $ls_resul_Balnace_1="";
		   $ls_resul_Balnace_2=number_format($ls_resul_Balnace_2,2,",",".");
		   $ls_resul_1=number_format($ls_resul_1,2,",",".");
		   $ls_suma1=number_format($ls_suma1,2,",",".");
		   $ls_suma_Balance_2=number_format($ls_suma_Balance_2,2,",",".");
		   $ls_denomina="Superavit Fiscal";
		}	
		
		uf_print_pie_cabecera($ldec_totaldebe,$ldec_totalhaber,$ldec_total_saldo,$ls_total_egreso,$ls_total_ingreso,
		                      $ls_tot_debe_g,$ls_tot_haber_g,$ls_resul_1,$ls_resul_2,$ls_denomina,
							  $ls_resul_Balnace_1,$ls_resul_Balnace_2,$ls_suma1,$ls_suma2,
							  $ls_suma_Balance_1,$ls_suma_Balance_2,$io_pdf);
		unset($la_data);			
			
		$io_pdf->ezStopPageNumbers(1,1);
		if (isset($d) && $d)
		{
			$ls_pdfcode = $io_pdf->ezOutput(1);
			$ls_pdfcode = str_replace("\n","\n<br>",htmlspecialchars($ls_pdfcode));
			echo '<html><body>';
			echo trim($ls_pdfcode);
			echo '</body></html>';
		}
		else
		{
			$io_pdf->ezStream();
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
?> 