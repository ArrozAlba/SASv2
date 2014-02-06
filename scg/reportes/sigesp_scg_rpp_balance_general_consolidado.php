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
		$lb_valido=$io_fun_scg->uf_load_seguridad_reporte("SCG","sigesp_scg_r_balance_general_consolidado.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_titulo1,$as_titulo2,$as_titulo3,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 28/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(20,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,715,11,$as_titulo); // Agregar el título		
		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo1);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,700,11,$as_titulo1); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo2);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,685,11,$as_titulo2); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo3);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,670,11,$as_titulo3); // Agregar el título

		$io_pdf->addText(510,740,7,$_SESSION["ls_database"]); // Agregar la Base de datos
		$io_pdf->addText(510,730,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(510,720,8,date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
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
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 28/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 8,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'left','width'=>115), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>305), // Justificación y ancho de la columna
									   'saldo'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$la_columnas=array('cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Denominación</b>',
						   'saldo'=>'<b>Saldo</b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ld_total,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	    Arguments : ad_totaldebe // Total debe
		//    Description : función que imprime el fin de la cabecera de cada página
		//	   Creado Por : Ing. Yozelin Barragan
		// Fecha Creación : 18/02/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
		$la_data=array(array('total'=>'<b>Total Pasivo + Capital + Resultado del Ejercicio Bs.</b>','totalgen'=>$ld_total));
		$la_columna=array('total'=>'','totalgen'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' => 9, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>520, // Ancho Máximo de la tabla
						 'colGap'=>1, // separacion entre tablas
						 'xOrientation'=>'center', // Orientación de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>420), // Justificación y ancho de la columna
						 			   'totalgen'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
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
     
	 require_once("../class_funciones_scg.php");
	 require_once("sigesp_scg_class_bal_general.php");
	 require_once("../../shared/ezpdf/class.ezpdf.php");	 
	 require_once("../../shared/class_folder/class_sql.php");
	 require_once("../../shared/class_folder/class_fecha.php");
	 require_once("../../shared/class_folder/sigesp_include.php");
	 require_once("../../shared/class_folder/class_funciones.php");
	 require_once("../../shared/class_folder/class_sigesp_int.php");
	 require_once("../../shared/class_folder/class_sigesp_int_scg.php");
	 $io_funciones = new class_funciones(); 
     $io_report    = new sigesp_scg_class_bal_general();
	 $io_fecha	   = new class_fecha();	 
	 $io_fun_scg   = new class_funciones_scg();
	 $ia_niveles_scg[0]="";			
	 uf_init_niveles();
	 $li_total=count($ia_niveles_scg)-1;
	
	  //--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	   $ls_cmbmes=$_GET["cmbmes"];
	   $ls_cmbagno=$_GET["cmbagno"];
	   $ls_last_day=$io_fecha->uf_last_day($ls_cmbmes,$ls_cmbagno);
	   $fechas=$ls_last_day;
	   if($_SESSION["ls_gestor"]=='INFORMIX')
	   {
	     $ldt_fechas=$io_funciones->uf_convertirdatetobd($ls_last_day);
	   }
	   else 
	   {
	     $ldt_fechas=$io_funciones->uf_convertirdatetobd($ls_last_day)." 00:00:00";
	   }
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
		$ldt_periodo = $_SESSION["la_empresa"]["periodo"];
		$ls_nombre   = $_SESSION["la_empresa"]["nombre"];
		$li_ano      = substr($ldt_periodo,0,4);
		$ld_fechas   = $io_funciones->uf_convertirfecmostrar($fechas);
		$ls_titulo   = "<b>BALANCE GENERAL CONSOLIDADO</b>";
		$ls_titulo1  = "<b> ".$ls_nombre." </b>"; 
		$ls_titulo2  = "<b> al ".$ld_fechas."</b>";
		$ls_titulo3  = "<b>(Expresado en Bs.)</b>";  
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar datastore con los datos del reporte
	$lb_valido=uf_insert_seguridad("<b>Balance General Consolidado en PDF</b>"); // Seguridad de Reporte
	if ($lb_valido)
	   {
	     $lb_valido=$io_report->uf_balance_general_consolidado($ldt_fechas); 
	   }
	if ($lb_valido==false) // Existe algún error ó no hay registros
	   {
		 print("<script language=JavaScript>");
		 print(" alert('No hay nada que Reportar');"); 
		 print(" close();");
	     print("</script>");
	   }	
	else// Imprimimos el reporte
	   {
	     error_reporting(E_ALL);
		 $io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		 $io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		 $io_pdf->ezSetCmMargins(4.8,3,3,3); // Configuración de los margenes en centímetros
		 uf_print_encabezado_pagina($ls_titulo,$ls_titulo1,$ls_titulo2,$ls_titulo3,$io_pdf); // Imprimimos el encabezado de la página
		 $io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		 $li_tot=$io_report->ds_reporte->getRowCount("sc_cuenta");
         $li_i = 0;	
		 for ($li_i=1;$li_i<=$li_tot;$li_i++)
			 {
			   $li_totfil = 0;
			   $io_pdf->transaction('start'); // Iniciamos la transacción
			   $thisPageNum  = $io_pdf->ezPageCount;
			   $ls_orden     = $io_report->ds_reporte->data["orden"][$li_i];
			   $li_nro_reg   = $io_report->ds_reporte->data["num_reg"][$li_i];
			   $ls_sc_cuenta = trim($io_report->ds_reporte->data["sc_cuenta"][$li_i]);
			   $as_cuenta    = "";
			   for ($li=$li_total;$li>1;$li--)
				   {
				     $li_ant    = $ia_niveles_scg[$li-1];
					 $li_act    = $ia_niveles_scg[$li];
					 $li_fila   = $li_act-$li_ant;
				 	 $li_len    = strlen($ls_sc_cuenta);
					 $li_totfil = $li_totfil+$li_fila;
					 $li_inicio = $li_len-$li_totfil;
					 if ($li==$li_total)
						{
						  $as_cuenta = substr($ls_sc_cuenta,$li_inicio,$li_fila);
						}
					 else
						{
						  $as_cuenta = substr($ls_sc_cuenta,$li_inicio,$li_fila)."-".$as_cuenta;
						}
				   }
			   $li_fila      = $ia_niveles_scg[1]+1;
			   $as_cuenta    = substr($ls_sc_cuenta,0,$li_fila)."-".$as_cuenta;
			   $ls_denscgcta = $io_report->ds_reporte->data["denominacion"][$li_i];
			   $ls_nivel     = $io_report->ds_reporte->data["nivel"][$li_i];
			   $ld_saldo     = number_format($io_report->ds_reporte->data["saldo"][$li_i],2,'.','');
			   $ld_total     = $io_report->ds_reporte->data["total"][$li_i];
			   if ($ld_saldo<0)
				  {  
				    $ld_saldo = "(".number_format(abs($ld_saldo),2,",",".").")";
				  }
			   else
				  {
				    $ld_saldo = number_format(abs($ld_saldo),2,",",".");	
				  }
			   $li_i++;
			   if ($ls_nivel==7 || $ls_nivel==6 || $ls_nivel==5 || $ls_nivel==4)
				  {
				    $la_data[$li_i] = array('cuenta'=>$as_cuenta,'denominacion'=>'            '.$ls_denscgcta,'saldo'=>$ld_saldo);					  
				  }
			   if ($ls_nivel==3)
				  {
				    $la_data[$li_i] = array('cuenta'=>'<b>'.$as_cuenta.'</b>','denominacion'=>'<b>        '.$ls_denscgcta.'</b>','saldo'=>$ld_saldo);
				  }
			   if ($ls_nivel==2)
				  {
				    $la_data[$li_i]=array('cuenta'=>'','denominacion'=>'','saldo'=>'');
					$li_i++;
					$la_data[$li_i]=array('cuenta'=>'<b>'.$as_cuenta.'</b>','denominacion'=>'    '.'<b>'.$ls_denscgcta.'</b>','saldo'=>'<b>'.$ld_saldo.'</b>');
				  }
			   if ($ls_nivel==1)
				  {
				    if ($li_i>1)
					   {
					 	 $la_data[$li_i]=array('cuenta'=>'','denominacion'=>'','saldo'=>'');
					  	 $li_i++;
					 	 $la_data[$li_i]=array('cuenta'=>'<b><i>'.$as_cuenta.'</b></i>','denominacion'=>'<b><i>'.$ls_denscgcta.'</b></i>','saldo'=>'<b><i>'.$ld_saldo.'</b></i>');
					   }
					else
					   {
					 	 $la_data[$li_i]=array('cuenta'=>'<b><i>'.$as_cuenta.'</b></i>','denominacion'=>'<b><i>'.$ls_denscgcta.'</b></i>','saldo'=>'<b><i>'.$ld_saldo.'</b></i>');
					   }
				}
			}
			uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
			$ld_total=$ld_total;
			if($ld_total<0)
			{
			   $ld_total="(".number_format(abs($ld_total),2,",",".").")";
			}
			else
			{
				$ld_total=number_format($ld_total,2,",",".");
			}
			uf_print_pie_cabecera($ld_total,$io_pdf); // Imprimimos pie de la cabecera
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
		 }//else
		unset($io_report,$io_funciones);			
?> 