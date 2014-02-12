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
		global $io_fun_scg;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_scg->uf_load_seguridad_reporte("SCG","sigesp_scg_r_balance_comprobacion.php",$ls_descripcion);
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
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,9,$as_titulo); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(11,$as_fecha);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,715,9,$as_fecha); // Agregar el título
		
		$io_pdf->addText(500,740,7,$_SESSION["ls_database"]); // Agregar la Base de datos
		$io_pdf->addText(500,730,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(500,720,8,date("h:i a")); // Agregar la hora
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
	function uf_print_titulos(&$io_pdf)
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
		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>299, // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'saldoanterior'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'haber'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'saldo'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		
		$la_data[1]=array('cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Denominación</b>',
						   'saldoanterior'=>'<b>Saldo Anterior</b>',
						   'debe'=>'<b>Debe</b>',
						   'haber'=>'<b>Haber</b>',
						   'saldo'=>'<b>Saldo Actual</b>');
		$io_pdf->ezTable($la_data,'','',$la_config);
	}// end function uf_print_detalle
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
		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>299, // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'saldoanterior'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'haber'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'saldo'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
		
		$la_columnas=array('cuenta'=>'',
						   'denominacion'=>'',
						   'saldoanterior'=>'',
						   'debe'=>'',
						   'haber'=>'',
						   'saldo'=>'');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($adec_totaldebe,$adec_totalhaber,$adec_total_saldo,&$io_pdf)
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
		$la_data=array(array('total'=>'<b><i>SALDO ACTUAL '.$ls_bolivares.'</i></b>','debe'=>'<b><i>'.$adec_totaldebe.'</i></b>','haber'=>'<b><i>'.$adec_totalhaber.'</i></b>','saldo'=>'<b><i>'.$adec_total_saldo.'</i></b>'));
		$la_columna=array('total'=>'','debe'=>'','haber'=>'','saldo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'colGap'=>1, // separacion entre tablas
						 'xOrientation'=>'center', // Orientación de la tabla
						 'xPos'=>299, // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>310), // Justificación y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
						 			   'haber'=>array('justification'=>'right','width'=>80), // Justificación y ancho de la columna
									   'saldo'=>array('justification'=>'right','width'=>80))); // Justificación y ancho de la columna
						 			  
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
		global $io_funciones,$ia_niveles_scg;
		
		$ls_formato=""; $li_posicion=0; $li_indice=0;
		$dat_emp=$_SESSION["la_empresa"];
		//contable
		$ls_formato = trim($dat_emp["formcont"])."-";
		$li_posicion = 1 ;
		$li_indice   = 1 ;
		$li_posicion = $io_funciones->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		do
		{
			$ia_niveles_scg[$li_indice] = $li_posicion;
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
		require_once("../class_funciones_scg.php");
		$io_fun_scg=new class_funciones_scg();
		$ls_tiporeporte="0";
		$ls_bolivares="";
		if (array_key_exists("tiporeporte",$_GET))
		{
			$ls_tiporeporte=$_GET["tiporeporte"];
		}
		switch($ls_tiporeporte)
		{
			case "0":
				require_once("sigesp_scg_reporte.php");
				$io_report  = new sigesp_scg_reporte();
				$ls_bolivares ="Bs.";
				break;
	
			case "1":
				require_once("sigesp_scg_reportebsf.php");
				$io_report  = new sigesp_scg_reportebsf();
				$ls_bolivares ="Bs.F.";
				break;
		}
		$ia_niveles_scg[0]="";			
		uf_init_niveles();
		$li_total=count($ia_niveles_scg)-1;
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
		$ld_fecdesde=$_GET["fecdes"];
		$ld_fechasta=$_GET["fechas"];
		$ls_cuentadesde_min=$_GET["cuentadesde"];
		$ls_cuentahasta_max=$_GET["cuentahasta"];
		if(($ls_cuentadesde_min=="")&&($ls_cuentahasta_max==""))
		{
		   if($io_report->uf_spg_reporte_select_cuenta_min_max($ls_cuentadesde_min,$ls_cuentahasta_max))
		   {
		     $ls_cuentadesde=$ls_cuentadesde_min;
		     $ls_cuentahasta=$ls_cuentahasta_max;
		   } 
		}
		else
		{
		     $ls_cuentadesde=$ls_cuentadesde_min;
		     $ls_cuentahasta=$ls_cuentahasta_max;
		}
		$li_nivel=$_GET["nivel"];
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
		$ldt_fecha=" <b>Desde  ".$ld_fecdesde."  al ".$ld_fechasta."</b> ";
		$ls_titulo=" <b>BALANCE DE COMPROBACION</b> ";       
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
	$lb_valido=uf_insert_seguridad("<b>Balance de Comprobación en PDF</b>"); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_scg_reporte_balance_comprobante($ls_cuentadesde,$ls_cuentahasta,$ld_fecdesde,$ld_fechasta,$li_nivel);
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
	    error_reporting(E_ALL);
		//set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ldt_fecha,$io_pdf); // Imprimimos el encabezado de la página
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		$li_tot=$io_report->dts_reporte->getRowCount("sc_cuenta");
		$ldec_totaldebe=0;
		$ldec_totalhaber=0;
		$ldec_total_saldo=0;
        $ld_saldo=0;
		$ldec_mondeb=0;
        $ldec_monhab=0;
		$ls_siguente_nivel=1;
		$cont=0;
		$lb_entro_nivel_1=false;
		$lb_entro_nivel_2=false;
		$lb_entro_nivel_3=false;
		$lb_entro_nivel_4=false;
		$lb_entro_nivel_5=false;
		$lb_entro_nivel_6=false;
		$lb_entro_nivel_7=false;
		for($i=1;$i<=$li_tot;$i++)
		{
		    $li_tmp=($i+1);
			$thisPageNum=$io_pdf->ezPageCount;
			$ls_cuenta=rtrim($io_report->dts_reporte->getValue("sc_cuenta",$i));

					$li_totfil=0;
					$as_cuenta="";
					for($li=$li_total;$li>1;$li--)
					{
						$li_ant=$ia_niveles_scg[$li-1];
						$li_act=$ia_niveles_scg[$li];
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
					$li_fila=$ia_niveles_scg[1]+1;
					$as_cuenta=substr($ls_cuenta,0,$li_fila)."-".$as_cuenta;

			$ls_denominacion=rtrim($io_report->dts_reporte->getValue("denominacion",$i));
			$ls_nivel=rtrim($io_report->dts_reporte->getValue("nivel",$i));
			$ls_status=rtrim($io_report->dts_reporte->getValue("status",$i));

			$ldec_debe=$io_report->dts_reporte->getValue("debe_mes",$i);
			$ldec_haber=$io_report->dts_reporte->getValue("haber_mes",$i);
			$ldec_saldo_ant=($io_report->dts_reporte->getValue("debe_mes_ant",$i)-$io_report->dts_reporte->getValue("haber_mes_ant",$i));
			$ldec_saldo_act=$ldec_saldo_ant+$ldec_debe-$ldec_haber;
			$ldec_BalDebe=$io_report->dts_reporte->getValue("total_debe",$i);
			$ldec_BalHABER=$io_report->dts_reporte->getValue("total_haber",$i);
			
			$ldec_totaldebe=$ldec_totaldebe+$ldec_BalDebe;
			$ldec_totalhaber=$ldec_totalhaber+$ldec_BalHABER;

		   		
			$ldec_saldo=$ldec_saldo_act;
			if($ldec_debe<0)
			{
			    $ldec_debe_aux=abs($ldec_debe);
				$ldec_debe_aux=number_format($ldec_debe_aux,2,",",".");
				$ldec_debe="(".$ldec_debe_aux.")";
			}
			else
			{
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
			
			if ($i<$li_tot)
			{
				$ls_siguente_nivel=rtrim($io_report->dts_reporte->getValue("nivel",$i+1));
				$auxstatus=rtrim($io_report->dts_reporte->getValue("status",$i+1));
			}
			else
			{
				$ls_siguente_nivel=0;
				$auxstatus='S';
			}
			
			
			
			switch ($li_nivel)
			{
				//////////////////////////////////////////////////////////////////////////////////////////////////////////
				// CUANDO EL USUARIO SELECCIONA GENERAR EL REPORTE CON CUENTAS DE NIVEL 1
				//////////////////////////////////////////////////////////////////////////////////////////////////////////
				case 1 : 
					$la_data[$i]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>$ldec_saldo_ant,
					  			       'debe'=>$ldec_debe,'haber'=>$ldec_haber,'saldo'=>$ldec_saldo);
					
			    break;
				//////////////////////////////////////////////////////////////////////////////////////////////////////////
				// CUANDO EL USUARIO SELECCIONA GENERAR EL REPORTE CON CUENTAS DE NIVEL 2
				//////////////////////////////////////////////////////////////////////////////////////////////////////////				
				case 2 : 
					if($ls_nivel==2)
					{
						$la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>
											$ldec_saldo_ant,'debe'=>$ldec_debe,'haber'=>$ldec_haber,'saldo'=>$ldec_saldo);
						$cont=$cont+1;									
						
					}
					if($ls_nivel==1)
					{
						 $la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>'',
									   'debe'=>'','haber'=>'','saldo'=>'');
						$cont=$cont+1;
						$ls_denominacion_nivel_1=$ls_denominacion;
						$ldec_totalsalant_nivel_1=$ldec_saldo_ant;
						$ldec_totaldebe_nivel_1=$ldec_debe;
						$ldec_totalhaber_nivel_1=$ldec_haber;
						$ldec_totalsalact_nivel_1=$ldec_saldo;
					}
										
					if (($ls_siguente_nivel < $ls_nivel)&&($auxstatus!='C'))
					{
						$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b>TOTAL CUENTA '.$ls_denominacion_nivel_1.'</b>',
											 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_1.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_1.'</b>',
											 'haber'=>'<b>'.$ldec_totalhaber_nivel_1.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_1.'</b>');
							
						$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
						uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
						uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
						unset($la_data);
						unset($la_auxdata);
						$cont=0;			   
										
					}
				
			    break;
				//////////////////////////////////////////////////////////////////////////////////////////////////////////
				// CUANDO EL USUARIO SELECCIONA GENERAR EL REPORTE CON CUENTAS DE NIVEL 3
				//////////////////////////////////////////////////////////////////////////////////////////////////////////
			    case 3: 
					if($ls_nivel==3)
					{
						$la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>
											$ldec_saldo_ant,'debe'=>$ldec_debe,'haber'=>$ldec_haber,'saldo'=>$ldec_saldo);
						$cont=$cont+1;
						
					}
					if($ls_nivel==2)
					{
						 $la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>'',
									   'debe'=>'','haber'=>'','saldo'=>'');
						$cont=$cont+1;
						$ls_denominacion_nivel_2=$ls_denominacion;
						$ldec_totalsalant_nivel_2=$ldec_saldo_ant;
						$ldec_totaldebe_nivel_2=$ldec_debe;
						$ldec_totalhaber_nivel_2=$ldec_haber;
						$ldec_totalsalact_nivel_2=$ldec_saldo;
					}
					if($ls_nivel==1)
					{
						 $la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>'',
									   'debe'=>'','haber'=>'','saldo'=>'');
						$cont=$cont+1;
						$ls_denominacion_nivel_1=$ls_denominacion;
						$ldec_totalsalant_nivel_1=$ldec_saldo_ant;
						$ldec_totaldebe_nivel_1=$ldec_debe;
						$ldec_totalhaber_nivel_1=$ldec_haber;
						$ldec_totalsalact_nivel_1=$ldec_saldo;
					}
					
					if (($ls_siguente_nivel < $ls_nivel)&&($auxstatus!='C'))
					{
																		
										
						$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b>TOTAL CUENTA '.$ls_denominacion_nivel_2.'</b>',
											 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_2.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_2.'</b>',
											 'haber'=>'<b>'.$ldec_totalhaber_nivel_2.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_2.'</b>');
							
						$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
						uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
						uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
						unset($la_data);
						unset($la_auxdata);
						$cont=0;			   
								
						
						if ($ls_siguente_nivel<=1)
						{
							
							$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_1.'</i></b>',
											 'saldoanterior'=>'<b><i>'.$ldec_totalsalant_nivel_1.'</i></b>','debe'=>'<b><i>'.$ldec_totaldebe_nivel_1.'</i></b>',
											 'haber'=>'<b><i>'.$ldec_totalhaber_nivel_1.'</i></b>','saldo'=>'<b><i>'.$ldec_totalsalact_nivel_1.'</i></b>');
							
							$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
							uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
							unset($la_auxdata);
						}		
					}
				
			    break;
				//////////////////////////////////////////////////////////////////////////////////////////////////////////
				// CUANDO EL USUARIO SELECCIONA GENERAR EL REPORTE CON CUENTAS DE NIVEL 4
				//////////////////////////////////////////////////////////////////////////////////////////////////////////				
				case 4 : 
					if($ls_nivel==4)
					{
						$la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>
											$ldec_saldo_ant,'debe'=>$ldec_debe,'haber'=>$ldec_haber,'saldo'=>$ldec_saldo);
						$cont=$cont+1;
						
					}
					if($ls_nivel==3)
					{
						 $la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>'',
									   'debe'=>'','haber'=>'','saldo'=>'');
						$cont=$cont+1;
						$ls_denominacion_nivel_3=$ls_denominacion;
						$ldec_totalsalant_nivel_3=$ldec_saldo_ant;
						$ldec_totaldebe_nivel_3=$ldec_debe;
						$ldec_totalhaber_nivel_3=$ldec_haber;
						$ldec_totalsalact_nivel_3=$ldec_saldo;
					}
					if($ls_nivel==2)
					{
						 $la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>'',
									   'debe'=>'','haber'=>'','saldo'=>'');
						$cont=$cont+1;
						$ls_denominacion_nivel_2=$ls_denominacion;
						$ldec_totalsalant_nivel_2=$ldec_saldo_ant;
						$ldec_totaldebe_nivel_2=$ldec_debe;
						$ldec_totalhaber_nivel_2=$ldec_haber;
						$ldec_totalsalact_nivel_2=$ldec_saldo;
					}
					if($ls_nivel==1)
					{
						 $la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>'',
									   'debe'=>'','haber'=>'','saldo'=>'');
						$cont=$cont+1;
						$ls_denominacion_nivel_1=$ls_denominacion;
						$ldec_totalsalant_nivel_1=$ldec_saldo_ant;
						$ldec_totaldebe_nivel_1=$ldec_debe;
						$ldec_totalhaber_nivel_1=$ldec_haber;
						$ldec_totalsalact_nivel_1=$ldec_saldo;
					}
					
					if (($ls_siguente_nivel < $ls_nivel)&&($auxstatus!='C'))
					{
						
						$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b>TOTAL CUENTA '.$ls_denominacion_nivel_3.'</b>',
											 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_3.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_3.'</b>',
											 'haber'=>'<b>'.$ldec_totalhaber_nivel_3.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_3.'</b>');
							
						$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
						uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
						uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
						unset($la_data);
						unset($la_auxdata);
						$cont=0;			   
						
						
						if ($ls_siguente_nivel==2)
						{
												
							$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_2.'</i></b>',
											 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_2.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_2.'</b>',
											 'haber'=>'<b>'.$ldec_totalhaber_nivel_2.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_2.'</b>');
							
							$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
							uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
							unset($la_auxdata);
						}
						
						if ($ls_siguente_nivel<=1)
						{
							
							
							$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_2.'</i></b>',
											 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_2.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_2.'</b>',
											 'haber'=>'<b>'.$ldec_totalhaber_nivel_2.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_2.'</b>');
							
							$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
							uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
							
							$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_1.'</i></b>',
											     'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_1.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_1.'</b>',
											     'haber'=>'<b>'.$ldec_totalhaber_nivel_1.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_1.'</b>');
							
							$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
							uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
							unset($la_auxdata);
						}		
					}
				
			    break;
				//////////////////////////////////////////////////////////////////////////////////////////////////////////
				// CUANDO EL USUARIO SELECCIONA GENERAR EL REPORTE CON CUENTAS DE NIVEL 5
				//////////////////////////////////////////////////////////////////////////////////////////////////////////		
				case 5 : 
					if($ls_nivel==5)
					{
						$la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>
											$ldec_saldo_ant,'debe'=>$ldec_debe,'haber'=>$ldec_haber,'saldo'=>$ldec_saldo);
						$cont=$cont+1;
						
					}
					if($ls_nivel==4)
					{
						 $la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>'',
									   'debe'=>'','haber'=>'','saldo'=>'');
						$cont=$cont+1;
						$ls_denominacion_nivel_4=$ls_denominacion;
						$ldec_totalsalant_nivel_4=$ldec_saldo_ant;
						$ldec_totaldebe_nivel_4=$ldec_debe;
						$ldec_totalhaber_nivel_4=$ldec_haber;
						$ldec_totalsalact_nivel_4=$ldec_saldo;
					}					
					if($ls_nivel==3)
					{
						 $la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>'',
									   'debe'=>'','haber'=>'','saldo'=>'');
						$cont=$cont+1;
						$ls_denominacion_nivel_3=$ls_denominacion;
						$ldec_totalsalant_nivel_3=$ldec_saldo_ant;
						$ldec_totaldebe_nivel_3=$ldec_debe;
						$ldec_totalhaber_nivel_3=$ldec_haber;
						$ldec_totalsalact_nivel_3=$ldec_saldo;
					}
					if($ls_nivel==2)
					{
						 $la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>'',
									   'debe'=>'','haber'=>'','saldo'=>'');
						$cont=$cont+1;
						$ls_denominacion_nivel_2=$ls_denominacion;
						$ldec_totalsalant_nivel_2=$ldec_saldo_ant;
						$ldec_totaldebe_nivel_2=$ldec_debe;
						$ldec_totalhaber_nivel_2=$ldec_haber;
						$ldec_totalsalact_nivel_2=$ldec_saldo;
					}
					if($ls_nivel==1)
					{
						 $la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>'',
									   'debe'=>'','haber'=>'','saldo'=>'');
						$cont=$cont+1;
						$ls_denominacion_nivel_1=$ls_denominacion;
						$ldec_totalsalant_nivel_1=$ldec_saldo_ant;
						$ldec_totaldebe_nivel_1=$ldec_debe;
						$ldec_totalhaber_nivel_1=$ldec_haber;
						$ldec_totalsalact_nivel_1=$ldec_saldo;
					}
					
					if (($ls_siguente_nivel < $ls_nivel)&&($auxstatus!='C'))
					{
						
					   if ($ls_nivel==5)
					   {
							$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b>TOTAL CUENTA '.$ls_denominacion_nivel_4.'</b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_4.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_4.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_4.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_4.'</b>');
								
							$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
							uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
							uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
							unset($la_data);
							unset($la_auxdata);
							$cont=0;	
					   }
					   else
					   {
					   	   
								
							$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
							uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
							uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
							unset($la_data);
							unset($la_auxdata);
							$cont=0;	
					   }
					
						  
													
						if ($ls_siguente_nivel==3)
						{
							
							$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_3.'</i></b>',
											 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_3.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_3.'</b>',
											 'haber'=>'<b>'.$ldec_totalhaber_nivel_3.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_3.'</b>');
							
							$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
							uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
							unset($la_auxdata);
						}
						
						if ($ls_siguente_nivel==2)
						{
							
							$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_3.'</i></b>',
											 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_3.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_3.'</b>',
											 'haber'=>'<b>'.$ldec_totalhaber_nivel_3.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_3.'</b>');
							
							$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
							uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
							unset($la_auxdata);
							
							$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_2.'</i></b>',
											 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_2.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_2.'</b>',
											 'haber'=>'<b>'.$ldec_totalhaber_nivel_2.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_2.'</b>');
							
							$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
							uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
							unset($la_auxdata);
						}
						
						if ($ls_siguente_nivel<=1)
						{
							$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_3.'</i></b>',
											 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_3.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_3.'</b>',
											 'haber'=>'<b>'.$ldec_totalhaber_nivel_3.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_3.'</b>');
							
							$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
							uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
							unset($la_auxdata);
							
							$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_2.'</i></b>',
											 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_2.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_2.'</b>',
											 'haber'=>'<b>'.$ldec_totalhaber_nivel_2.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_2.'</b>');
							
							$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
							uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
							
							$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_1.'</i></b>',
											     'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_1.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_1.'</b>',
											     'haber'=>'<b>'.$ldec_totalhaber_nivel_1.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_1.'</b>');
							
							$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
							uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
							unset($la_auxdata);
						}		
					}
				
			    break;
				//////////////////////////////////////////////////////////////////////////////////////////////////////////
				// CUANDO EL USUARIO SELECCIONA GENERAR EL REPORTE CON CUENTAS DE NIVEL 6
				//////////////////////////////////////////////////////////////////////////////////////////////////////////		
				
				case 6 : 
					if($ls_nivel==6)
					{
						$lb_entro_nivel_6=true;
						$la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>
											$ldec_saldo_ant,'debe'=>$ldec_debe,'haber'=>$ldec_haber,'saldo'=>$ldec_saldo);
						$cont=$cont+1;
						
					}
					if($ls_nivel==5)
					{
						$lb_entro_nivel_5=true;
						$la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>'',
								               'debe'=>'','haber'=>'','saldo'=>'');
						
						$cont=$cont+1;
						$ls_denominacion_nivel_5=$ls_denominacion;
						$ldec_totalsalant_nivel_5=$ldec_saldo_ant;
						$ldec_totaldebe_nivel_5=$ldec_debe;
						$ldec_totalhaber_nivel_5=$ldec_haber;
						$ldec_totalsalact_nivel_5=$ldec_saldo;
					}	
					if($ls_nivel==4)
					{
						$lb_entro_nivel_4=true;
						$la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>'',
									   'debe'=>'','haber'=>'','saldo'=>'');
						$cont=$cont+1;
						$ls_denominacion_nivel_4=$ls_denominacion;
						$ldec_totalsalant_nivel_4=$ldec_saldo_ant;
						$ldec_totaldebe_nivel_4=$ldec_debe;
						$ldec_totalhaber_nivel_4=$ldec_haber;
						$ldec_totalsalact_nivel_4=$ldec_saldo;
					}					
					if($ls_nivel==3)
					{
						$lb_entro_nivel_3=true;
						$la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>'',
									   'debe'=>'','haber'=>'','saldo'=>'');
						$cont=$cont+1;
						$ls_denominacion_nivel_3=$ls_denominacion;
						$ldec_totalsalant_nivel_3=$ldec_saldo_ant;
						$ldec_totaldebe_nivel_3=$ldec_debe;
						$ldec_totalhaber_nivel_3=$ldec_haber;
						$ldec_totalsalact_nivel_3=$ldec_saldo;
					}
					if($ls_nivel==2)
					{
						$lb_entro_nivel_2=true;
						$la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>'',
									   'debe'=>'','haber'=>'','saldo'=>'');
						$cont=$cont+1;
						$ls_denominacion_nivel_2=$ls_denominacion;
						$ldec_totalsalant_nivel_2=$ldec_saldo_ant;
						$ldec_totaldebe_nivel_2=$ldec_debe;
						$ldec_totalhaber_nivel_2=$ldec_haber;
						$ldec_totalsalact_nivel_2=$ldec_saldo;
					}
					if($ls_nivel==1)
					{
						$lb_entro_nivel_1=true;
						$la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>'',
									   'debe'=>'','haber'=>'','saldo'=>'');
						$cont=$cont+1;
						$ls_denominacion_nivel_1=$ls_denominacion;
						$ldec_totalsalant_nivel_1=$ldec_saldo_ant;
						$ldec_totaldebe_nivel_1=$ldec_debe;
						$ldec_totalhaber_nivel_1=$ldec_haber;
						$ldec_totalsalact_nivel_1=$ldec_saldo;
					}
										
					if (($ls_siguente_nivel < $ls_nivel)&&($auxstatus!='C'))
					{
						
						if (($ls_nivel==6)&&($lb_entro_nivel_5))
						{
							$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b>TOTAL CUENTA '.$ls_denominacion_nivel_5.'</b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_5.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_5.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_5.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_5.'</b>');
								
							$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
							uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
							uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
							unset($la_data);
							unset($la_auxdata);
							$lb_entro_nivel_5=false;
							$cont=0;	
					   }
					   else
					   {
					   	   
								
							$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
							uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
							uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
							unset($la_data);
							unset($la_auxdata);
							$cont=0;	
					   }
					 
						if  ($ls_siguente_nivel==4)
						{
							if ($lb_entro_nivel_4)	
							{
								$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_4.'</i></b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_4.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_4.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_4.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_4.'</b>');
								
								$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
								uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
								unset($la_auxdata);
								$lb_entro_nivel_4=false;
							}
							
						}
						
						if ($ls_siguente_nivel==3)
						{
							
							if ($lb_entro_nivel_4) 
							{
								$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_4.'</i></b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_4.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_4.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_4.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_4.'</b>');
								
								$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
								uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
								unset($la_auxdata);
								$lb_entro_nivel_4=false;
							}
								
							if ($lb_entro_nivel_3)	
							{	
								$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_3.'</i></b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_3.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_3.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_3.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_3.'</b>');
								$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
								uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
								unset($la_auxdata);
								$lb_entro_nivel_3=false;
							}
							
							
						}
						
						if ($ls_siguente_nivel==2)
						{
							if ($lb_entro_nivel_4) 
							{
								$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_4.'</i></b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_4.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_4.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_4.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_4.'</b>');
								
								$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
								uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
								unset($la_auxdata);
								$lb_entro_nivel_4=false;
							}
								
							if ($lb_entro_nivel_3)	
							{	
								$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_3.'</i></b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_3.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_3.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_3.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_3.'</b>');
								$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
								uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
								unset($la_auxdata);
								$lb_entro_nivel_3=false;
							}
							
							if ($lb_entro_nivel_2)
							{
								$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_2.'</i></b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_2.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_2.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_2.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_2.'</b>');
								
								$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
								uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
								unset($la_auxdata);
								$lb_entro_nivel_2=false;
							}
							
						}
						
						if ($ls_siguente_nivel<=1)
						{
							
							if ($lb_entro_nivel_4) 
							{
								$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_4.'</i></b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_4.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_4.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_4.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_4.'</b>');
								
								$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
								uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
								unset($la_auxdata);
								$lb_entro_nivel_4=false;
							}
								
							if ($lb_entro_nivel_3)	
							{	
								$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_3.'</i></b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_3.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_3.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_3.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_3.'</b>');
								$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
								uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
								unset($la_auxdata);
								$lb_entro_nivel_3=false;
							}
							
							if ($lb_entro_nivel_2)
							{
								$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_2.'</i></b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_2.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_2.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_2.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_2.'</b>');
								
								$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
								uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
								unset($la_auxdata);
								$lb_entro_nivel_2=false;
							}
							
							if ($lb_entro_nivel_1)
							{
								$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_1.'</i></b>',
													 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_1.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_1.'</b>',
													 'haber'=>'<b>'.$ldec_totalhaber_nivel_1.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_1.'</b>');
								
								$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
								uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
								unset($la_auxdata);
								$lb_entro_nivel_1=false;
							}
						}		
					}
				
			    break;
                //////////////////////////////////////////////////////////////////////////////////////////////////////////
				// CUANDO EL USUARIO SELECCIONA GENERAR EL REPORTE CON CUENTAS DE NIVEL 7
				//////////////////////////////////////////////////////////////////////////////////////////////////////////		
				case 7 : 
					if($ls_nivel==7)
					{
						
						$la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>
											$ldec_saldo_ant,'debe'=>$ldec_debe,'haber'=>$ldec_haber,'saldo'=>$ldec_saldo); 
						 
						$cont=$cont+1;
						$lb_entro_nivel_7=true;
					}
					if($ls_nivel==6)
					{
						 if ($ls_status=='C')
						 {
						 	$la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>
											$ldec_saldo_ant,'debe'=>$ldec_debe,'haber'=>$ldec_haber,'saldo'=>$ldec_saldo);
						 }
						 else
						 {
						 	 $la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>'',
									   'debe'=>'','haber'=>'','saldo'=>'');
						 }
						$lb_entro_nivel_6=true;
						$cont=$cont+1;
						$ls_denominacion_nivel_6=$ls_denominacion;
						$ldec_totalsalant_nivel_6=$ldec_saldo_ant;
						$ldec_totaldebe_nivel_6=$ldec_debe;
						$ldec_totalhaber_nivel_6=$ldec_haber;
						$ldec_totalsalact_nivel_6=$ldec_saldo;
					}	
					if($ls_nivel==5)
					{
						$lb_entro_nivel_5=true;
					    $la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>'',
									   'debe'=>'','haber'=>'','saldo'=>'');
						
						$cont=$cont+1;
						$ls_denominacion_nivel_5=$ls_denominacion;
						$ldec_totalsalant_nivel_5=$ldec_saldo_ant;
						$ldec_totaldebe_nivel_5=$ldec_debe;
						$ldec_totalhaber_nivel_5=$ldec_haber;
						$ldec_totalsalact_nivel_5=$ldec_saldo;
					}	
					if($ls_nivel==4)
					{
						$lb_entro_nivel_4=true;
						$la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>'',
									   'debe'=>'','haber'=>'','saldo'=>'');
						$cont=$cont+1;
						$ls_denominacion_nivel_4=$ls_denominacion;
						$ldec_totalsalant_nivel_4=$ldec_saldo_ant;
						$ldec_totaldebe_nivel_4=$ldec_debe;
						$ldec_totalhaber_nivel_4=$ldec_haber;
						$ldec_totalsalact_nivel_4=$ldec_saldo;
					}					
					if($ls_nivel==3)
					{
						$lb_entro_nivel_3=true;
						$la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>'',
									   'debe'=>'','haber'=>'','saldo'=>'');
						$cont=$cont+1;
						$ls_denominacion_nivel_3=$ls_denominacion;
						$ldec_totalsalant_nivel_3=$ldec_saldo_ant;
						$ldec_totaldebe_nivel_3=$ldec_debe;
						$ldec_totalhaber_nivel_3=$ldec_haber;
						$ldec_totalsalact_nivel_3=$ldec_saldo;
					}
					if($ls_nivel==2)
					{
						$lb_entro_nivel_2=true;
						$la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>'',
									   'debe'=>'','haber'=>'','saldo'=>'');
						$cont=$cont+1;
						$ls_denominacion_nivel_2=$ls_denominacion;
						$ldec_totalsalant_nivel_2=$ldec_saldo_ant;
						$ldec_totaldebe_nivel_2=$ldec_debe;
						$ldec_totalhaber_nivel_2=$ldec_haber;
						$ldec_totalsalact_nivel_2=$ldec_saldo;
					}
					if($ls_nivel==1)
					{
						$lb_entro_nivel_1=true;
						$la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>'',
									   'debe'=>'','haber'=>'','saldo'=>'');
						$cont=$cont+1;
						$ls_denominacion_nivel_1=$ls_denominacion;
						$ldec_totalsalant_nivel_1=$ldec_saldo_ant;
						$ldec_totaldebe_nivel_1=$ldec_debe;
						$ldec_totalhaber_nivel_1=$ldec_haber;
						$ldec_totalsalact_nivel_1=$ldec_saldo;
					}
					
					if (($ls_siguente_nivel < $ls_nivel)&&($auxstatus!='C'))
					{
										
						if (($ls_nivel==7)&&($lb_entro_nivel_6))
						{
							$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b>TOTAL CUENTA '.$ls_denominacion_nivel_6.'</b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_6.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_6.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_6.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_6.'</b>');
								
							$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
							uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
							uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
							unset($la_data);
							unset($la_auxdata);
							$lb_entro_nivel_6=false;
							$cont=0;	
					   }
					   else
					   {
					   	   
								
							$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
							uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
							uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
							unset($la_data);
							unset($la_auxdata);
							$cont=0;	
					   }
						
						if ($ls_siguente_nivel==5)
						{
							if ($lb_entro_nivel_5)
							{
								$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_5.'</i></b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_5.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_5.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_5.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_5.'</b>');
								
								$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
								uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
								unset($la_auxdata);
								$lb_entro_nivel_5=false;
							}
							
						}
						
						if ($ls_siguente_nivel==4)
						{
							if ($lb_entro_nivel_5)
							{
								$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_5.'</i></b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_5.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_5.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_5.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_5.'</b>');
								
								$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
								uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
								unset($la_auxdata);
								$lb_entro_nivel_5=false;
							}
							
							if ($lb_entro_nivel_4)
							{
							
								$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_4.'</i></b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_4.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_4.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_4.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_4.'</b>');
								
								$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
								uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
								unset($la_auxdata);
								$lb_entro_nivel_4=false;
							}
							
						}
												
						
						if ($ls_siguente_nivel==3)
						{
							
							if ($lb_entro_nivel_5)
							{
								$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_5.'</i></b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_5.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_5.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_5.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_5.'</b>');
								
								$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
								uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
								unset($la_auxdata);
								$lb_entro_nivel_5=false;
							}
							
							if ($lb_entro_nivel_4)
							{
							
								$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_4.'</i></b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_4.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_4.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_4.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_4.'</b>');
								
								$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
								uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
								unset($la_auxdata);
								$lb_entro_nivel_4=false;
							}
							
							if ($lb_entro_nivel_3)
							{
								$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_3.'</i></b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_3.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_3.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_3.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_3.'</b>');
								
								$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
								uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
								unset($la_auxdata);
								$lb_entro_nivel_3=false;
							}
							
						}
						
						if ($ls_siguente_nivel==2)
						{
							if ($lb_entro_nivel_5)
							{
								$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_5.'</i></b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_5.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_5.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_5.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_5.'</b>');
								
								$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
								uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
								unset($la_auxdata);
								$lb_entro_nivel_5=false;
							}
							
							if ($lb_entro_nivel_4)
							{
							
								$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_4.'</i></b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_4.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_4.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_4.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_4.'</b>');
								
								$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
								uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
								unset($la_auxdata);
								$lb_entro_nivel_4=false;
							}
							
							if ($lb_entro_nivel_3)
							{
								$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_3.'</i></b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_3.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_3.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_3.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_3.'</b>');
								
								$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
								uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
								unset($la_auxdata);
								$lb_entro_nivel_3=false;
							}
							
							if ($lb_entro_nivel_2)
							{
								$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_2.'</i></b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_2.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_2.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_2.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_2.'</b>');
								
								$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
								uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
								unset($la_auxdata);
								$lb_entro_nivel_2=false;
							}
							
						}
						
						if ($ls_siguente_nivel<=1)
						{
							
							if ($lb_entro_nivel_5)
							{
								$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_5.'</i></b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_5.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_5.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_5.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_5.'</b>');
								
								$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
								uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
								unset($la_auxdata);
								$lb_entro_nivel_5=false;
							}
							
							if ($lb_entro_nivel_4)
							{
							
								$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_4.'</i></b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_4.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_4.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_4.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_4.'</b>');
								
								$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
								uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
								unset($la_auxdata);
								$lb_entro_nivel_4=false;
							}
							
							if ($lb_entro_nivel_3)
							{
								$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_3.'</i></b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_3.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_3.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_3.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_3.'</b>');
								
								$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
								uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
								unset($la_auxdata);
								$lb_entro_nivel_3=false;
							}
							
							if ($lb_entro_nivel_2)
							{
								$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_2.'</i></b>',
												 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_2.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_2.'</b>',
												 'haber'=>'<b>'.$ldec_totalhaber_nivel_2.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_2.'</b>');
								
								$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
								uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
								unset($la_auxdata);
								$lb_entro_nivel_2=false;
							}
														
							if ($lb_entro_nivel_1)
							{
								$la_auxdata[1]=array('cuenta'=>'','denominacion'=>'<b><i>TOTAL CUENTA '.$ls_denominacion_nivel_1.'</i></b>',
													 'saldoanterior'=>'<b>'.$ldec_totalsalant_nivel_1.'</b>','debe'=>'<b>'.$ldec_totaldebe_nivel_1.'</b>',
													 'haber'=>'<b>'.$ldec_totalhaber_nivel_1.'</b>','saldo'=>'<b>'.$ldec_totalsalact_nivel_1.'</b>');
								
								$la_auxdata[2]=array('cuenta'=>'','denominacion'=>'','saldoanterior'=>'','debe'=>'','haber'=>'','saldo'=>'');
								uf_print_detalle($la_auxdata,$io_pdf); // Imprimimos el detalle
								unset($la_auxdata);
							}
						}		
					}
				
			    break;  
					
			
			}//switch
			
			
		}//for

		if ($li_nivel==1)
		{
			uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
		}
		
		
		$ldec_total_saldo=($ldec_totaldebe-$ldec_totalhaber);
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
		uf_print_pie_cabecera($ldec_totaldebe,$ldec_totalhaber,$ldec_total_saldo,$io_pdf);
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