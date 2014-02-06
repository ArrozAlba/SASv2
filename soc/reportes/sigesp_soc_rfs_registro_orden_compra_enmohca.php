<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Formato de salida  de la Orden de Compra
//  ORGANISMO: ENMOHCA
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
	function uf_print_encabezado_pagina($as_tipordcom,$as_numordcom,$ad_fecordcom,$as_codpro,$as_nompro,$as_conordcom,$as_rifpro,$as_dirpro,$as_telpro,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_estcondat  ---> tipo de la orden de compra
		//	    		   as_numordcom ---> numero de la orden de compra
		//	    		   ad_fecordcom ---> fecha de registro de la orden de compra
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Funci� que imprime los encabezados por p�ina
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creaci�: 11/02/2008.
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->addText(532,673,11,$ad_fecordcom);
		if ($as_tipordcom=='B')
		   {
		     $as_numordcom = substr($as_numordcom,-6);
		     $io_pdf->addText(550,657,11,"<b>".$as_numordcom."</b>");
		   }
		elseif($as_tipordcom=='S')
		   {
		     $io_pdf->addText(497,657,11,"<b>".$as_numordcom."</b>");
		   }
		$io_pdf->ezSetY(632);
		$la_data[1] = array('columna1'=>$as_codpro.' - '.$as_nompro.'<b> - Rif:</b> '.$as_rifpro);
		$la_data[2] = array('columna1'=>$as_dirpro.'<b> - Telefono: </b>'.$as_telpro);
		$la_data[3] = array('columna1'=>$as_conordcom);
		$la_columna = array('columna1'=>'');
		$la_config  = array('showHeadings'=>0, // Mostrar encabezados
						    'fontSize'=>9, // Tamaño de Letras
						    'titleFontSize'=>9,  // Tamaño de Letras de los t�ulos
						    'showLines'=>0, // Mostrar Líneas
						    'shaded'=>0, // Sombra entre líneas
						    'width'=>570, // Ancho de la tabla
						    'maxWidth'=>570, // Ancho M�imo de la tabla
						    'xOrientation'=>'center', // Orientaci� de la tabla
						    'cols'=>array('columna1'=>array('justification'=>'left','width'=>370))); // Justificaci� y ancho de la columna  AQUI EDITO KARLA GOMEZ!!!!!!!!!!!!!!!!!!!!!!
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		
		$io_pdf->addText(43,70,10,"T.S.U Cesar Bertoni");
		$io_pdf->addText(155,70,10,"Econ.Luis Bonito");
		$io_pdf->addText(262,70,10,"Lcda. Blanca Barrios");
		$io_pdf->addText(387,70,10,"Ing. Jorge Gonzalez");
     	$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($as_tipordcom,$la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data ---> arreglo de información
		//	    		   io_pdf ---> Instancia de objeto pdf
		//    Description: funcion que imprime el detalle 
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creaci�: 21/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$io_pdf->ezSetY(460);
		if ($as_tipordcom=='S')
		{
		$la_columnas=array('codigo'=>'','denominacion'=>'','cantidad'=>'','precio'=>'','total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>8, // Tamaño de Letras
						 'titleFontSize'=>8,  // Tamaño de Letras de los t�ulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580, // Ancho Mínimo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>100),
						 			   'denominacion'=>array('justification'=>'left','width'=>210),
						 			   'cantidad'=>array('justification'=>'left','width'=>40),
						 			   'precio'=>array('justification'=>'right','width'=>115),
						 			   'total'=>array('justification'=>'right','width'=>115)));
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		}
		elseif($as_tipordcom=='B')
		{
		  $la_columnas = array('codigo'=>'','denominacion'=>'','unidad'=>'','cantidad'=>'','precio'=>'','total'=>'');
		  $la_config   = array('showHeadings'=>0, // Mostrar encabezados
						       'fontSize'=>8, // Tamaño de Letras
						       'titleFontSize'=>8,  // Tamaño de Letras de los t�ulos
						       'showLines'=>0, // Mostrar Líneas
						       'shaded'=>0, // Sombra entre l�eas
						       'width'=>580, // Ancho de la tabla
							   'maxWidth'=>580, // Ancho Mínimo de la tabla
							   'xOrientation'=>'center', // Orientaci� de la tabla
							   'cols'=>array('codigo'=>array('justification'=>'center','width'=>100), // Justificaci� y ancho de la columna
										     'denominacion'=>array('justification'=>'left','width'=>150), // Justificaci� y ancho de la columna
										     'unidad'=>array('justification'=>'left','width'=>60), // Justificaci� y ancho de la columna
											 'cantidad'=>array('justification'=>'left','width'=>40), // Justificaci� y ancho de la columna
										     'precio'=>array('justification'=>'right','width'=>115), // Justificaci� y ancho de la columna
										     'total'=>array('justification'=>'right','width'=>115))); // Justificaci� y ancho de la columna
		  $io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		}
	    unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_cuentas($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuentas
		//		   Access: private 
		//	    Arguments: la_data ---> arreglo de informaci�
		//	    		   io_pdf ---> Instancia de objeto pdf
		//    Description: funci� que imprime el detalle por concepto
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creaci�: 21/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$io_pdf->ezSetDy(-5);
		$la_datasercon= array(array('codestpro'=>"<b>Estructura Presupuestaria</b>",
		                            'cuenta'=>"<b>Cuenta Presupuestaria</b>",
									'denominacion'=>"<b>Denominacion</b>",
									'monto'=>"<b>Monto</b>"));
		$la_columnas=array('codestpro'=>'','cuenta'=>'','denominacion'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>8, // Tama� de Letras
						 'titleFontSize'=>8,  // Tama� de Letras de los t�ulos
						 'showLines'=>1, // Mostrar L�eas
						 'shaded'=>0, // Sombra entre l�eas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�imo de la tabla
						 'xOrientation'=>'center', // Orientaci� de la tabla
						 'cols'=>array('codestpro'=>array('justification'=>'center','width'=>120), // Justificaci� y ancho de la columna
						 			   'cuenta'=>array('justification'=>'center','width'=>100), // Justificaci� y ancho de la columna
						 			   'denominacion'=>array('justification'=>'center','width'=>250), // Justificaci� y ancho de la columna
									   'monto'=>array('justification'=>'right','width'=>100))); // Justificaci� y ancho de la columna
		$io_pdf->ezTable($la_datasercon,$la_columnas,'',$la_config);
        unset($la_datasercon);
		unset($la_columnas);
		unset($la_config);

		$io_pdf->ezSetDy(-2);
		$la_columnas=array('codestpro'=>'','cuenta'=>'','denominacion'=>'','monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>8, // Tama� de Letras
						 'titleFontSize'=>8,  // Tama� de Letras de los t�ulos
						 'showLines'=>0, // Mostrar L�eas
						 'shaded'=>0, // Sombra entre l�eas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�imo de la tabla
						 'xOrientation'=>'center', // Orientaci� de la tabla
						 'cols'=>array('codestpro'=>array('justification'=>'center','width'=>120), // Justificaci� y ancho de la columna
						 			   'cuenta'=>array('justification'=>'center','width'=>100), // Justificaci� y ancho de la columna
						 			   'denominacio'=>array('justification'=>'center','width'=>250), // Justificaci� y ancho de la columna
									   'monto'=>array('justification'=>'right','width'=>100))); // Justificaci� y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales($li_subtot,$li_totcar,$li_montot,$ls_monlet,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		    Acess: private 
		//	    Arguments: li_subtot ---> Subtotal del articulo
		//	    		   li_totcar -->  Total cargos
		//	    		   li_montot  --> Monto total
		//	    		   ls_monlet   //Monto en letras
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: funci� que imprime los totales
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creaci�: 21/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_pdf->ezSetDy(-10);
		$la_data[1]=array('titulo'=>'<b>Sub Total</b>','contenido'=>$li_subtot);
		$la_columnas=array('titulo'=>'','contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama� de Letras
						 'titleFontSize' => 12,  // Tama� de Letras de los t�ulos
						 'showLines'=>0, // Mostrar L�eas
						 'shaded'=>0, // Sombra entre l�eas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�imo de la tabla
						 'xOrientation'=>'center', // Orientaci� de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificaci� y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificaci� y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('titulo'=>'<b>Cargos</b>','contenido'=>$li_totcar);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama� de Letras
						 'titleFontSize' => 12,  // Tama� de Letras de los t�ulos
						 'showLines'=>0, // Mostrar L�eas
						 'shaded'=>0, // Sombra entre l�eas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�imo de la tabla
						 'xOrientation'=>'center', // Orientaci� de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificaci� y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificaci� y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('titulo'=>'<b>Total</b>','contenido'=>$li_montot);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama� de Letras
						 'titleFontSize' => 12,  // Tama� de Letras de los t�ulos
						 'showLines'=>0, // Mostrar L�eas
						 'shaded'=>0, // Sombra entre l�eas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�imo de la tabla
						 'xOrientation'=>'center', // Orientaci� de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificaci� y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificaci� y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
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
	$ls_estmodest = $_SESSION["la_empresa"]["estmodest"];

	//Instancio a la clase de conversi� de numeros a letras.
	include("../../shared/class_folder/class_numero_a_letra.php");
	$numalet= new class_numero_a_letra();
	//imprime numero con los valore por defecto
	//cambia a minusculas
	$numalet->setMayusculas(1);
	//cambia a femenino
	$numalet->setGenero(1);
	//cambia moneda
	$numalet->setMoneda("Bolivares");
	//cambia prefijo
	$numalet->setPrefijo("***");
	//cambia sufijo
	$numalet->setSufijo("***");
	$ls_tiporeporte=$io_fun_soc->uf_obtenervalor_get("tiporeporte",1);
	$ls_bolivares="Bs.";
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_soc_class_reportbsf.php");
		$io_report=new sigesp_soc_class_reportbsf();
		$ls_bolivares="Bs.F.";
		$numalet->setMoneda("Bolivares Fuerte");
	}
		
	//--------------------------------------------------  Par�etros para Filtar el Reporte  -----------------------------------------
	$ls_numordcom=$io_fun_soc->uf_obtenervalor_get("numordcom","");
	$ls_estcondat=$io_fun_soc->uf_obtenervalor_get("tipord","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$rs_data= $io_report->uf_select_orden_imprimir($ls_numordcom,$ls_estcondat,&$lb_valido); // Cargar los datos del reporte
	if($lb_valido==false) // Existe algun error o no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else  // Imprimimos el reporte
	{
		$ls_descripcion="Generó el Reporte de Orden de Compra";
		$lb_valido=$io_fun_soc->uf_load_seguridad_reporte("SOC","sigesp_soc_p_registro_orden_compra.php",$ls_descripcion);
		if($lb_valido)	
		{
			error_reporting(E_ALL);
			set_time_limit(1800);
			$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(11.7,3.5,3,3); // Configuraci� de los margenes en cent�etros
			if ($row=$io_sql->fetch_row($rs_data))
			   {
				 $ls_numordcom	= trim($row["numordcom"]);
				 $ls_estcondat	= $row["estcondat"];
				 $ls_codpro		= trim($row["cod_pro"]);
				 $ls_nompro		= $row["nompro"];
				 $ls_rifpro	    = $row["rifpro"];
				 $ls_dirpro	    = $row["dirpro"];
				 $ls_telpro	    = $row["telpro"];
				 $ld_fecordcom  = $row["fecordcom"];
				 $ls_obscom		= $row["obscom"];
				 $ld_monsubtot	= $row["monsubtot"];
				 $ld_monimp		= $row["monimp"];
				 $ld_montot		= $row["montot"];
				 if ($ls_tiporeporte==0)
				    {
					  $ld_montotaux=$row["montotaux"];
					  $ld_montotaux=number_format($ld_montotaux,2,",",".");
				    }
				$numalet->setNumero($ld_montot);
				$ls_monto	  = $numalet->letra();
				$ld_montot	  = number_format($ld_montot,2,",",".");
				$ld_monsubtot = number_format($ld_monsubtot,2,",",".");
				$ld_monimp	  = number_format($ld_monimp,2,",",".");
				$ld_fecordcom = $io_funciones->uf_convertirfecmostrar($ld_fecordcom);
		 		uf_print_encabezado_pagina($ls_estcondat,$ls_numordcom,$ld_fecordcom,$ls_codpro,$ls_nompro,$ls_obscom,$ls_rifpro,$ls_dirpro,$ls_telpro,&$io_pdf);
				/////DETALLE  DE  LA ORDEN DE COMPRA
			   $rs_datos = $io_report->uf_select_detalle_orden_imprimir($ls_numordcom,$ls_estcondat,&$lb_valido);
			   if ($lb_valido)
			   {
		     	 $li_totrows = $io_sql->num_rows($rs_datos);
				 if ($li_totrows>0)
				 {
				    $li_i = 0;
				    while($row=$io_sql->fetch_row($rs_datos))
					     {
						   $li_i++;
						   $ls_codartser = $row["codartser"];
						   $ls_denartser = $row["denartser"];
						   if ($ls_estcondat=="B")
					 	      {
							    $ls_unidad = trim($row["unidad"]);
						      }
						   else
						      {
							    $ls_unidad="";
						      }
						   if ($ls_unidad=="D")
						      {
						        $ls_unidad="Detal";
						      }
						   elseif($ls_unidad=="M")
						      {
						        $ls_unidad="Mayor";
						      }
						   $ld_preartser   = $row["preartser"];
						   $li_cantartser  = $row["cantartser"];
						   $ld_totartser   = ($ld_preartser*$li_cantartser);
						   $ld_preartser   = number_format($ld_preartser,2,",",".");
						   $li_cantartser  = number_format($li_cantartser,2,",",".");
						   $ld_totartser   = number_format($ld_totartser,2,",",".");
						   $la_data[$li_i] = array('codigo'=>$ls_codartser,
						                           'denominacion'=>$ls_denartser,
												   'unidad'=>$ls_unidad,
												   'cantidad'=>$li_cantartser,
											       'precio'=>$ld_preartser,
												   'total'=>$ld_totartser);
					     }
					uf_print_detalle($ls_estcondat,$la_data,&$io_pdf);
					unset($la_data);
				    /////DETALLE  DE  LAS  CUENTAS DE GASTOS DE LA ORDEN DE COMPRA
					$rs_datos_cuenta=$io_report->uf_select_cuenta_gasto($ls_numordcom,$ls_estcondat,&$lb_valido); 
					if ($lb_valido)
					   {
				 	     $li_totrows = $io_sql->num_rows($rs_datos_cuenta);
						 if ($li_totrows>0)
						    {
							  $li_s = 0;
						  	  while($row=$io_sql->fetch_row($rs_datos_cuenta))
							       { 
								     $li_s++;
								     $ls_codestpro1 = substr(trim($row["codestpro1"]),-6);
								     $ls_codestpro2 = trim($row["codestpro2"]);
								     $ls_codestpro3 = trim($row["codestpro3"]);
									 $ls_codestpro4 = trim($row["codestpro4"]);
									 $ls_codestpro5 = trim($row["codestpro5"]);
								     $ls_spg_cuenta = trim($row["spg_cuenta"]);
								     $ld_monto      = $row["monto"];
								     $ld_monto      = number_format($ld_monto,2,",",".");
								     $ls_dencuenta  = "";
								     $lb_valido = $io_report->uf_select_denominacionspg($ls_spg_cuenta,$ls_dencuenta);																																						
								     if ($ls_estmodest==1)
								        {
									      $ls_codestpro=$ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
								        }
								     else
								        {
									      $ls_codestpro=substr($ls_codestpro1,-2)."-".substr($ls_codestpro2,-2)."-".substr($ls_codestpro3,-2)."-".substr($ls_codestpro4,-2)."-".substr($ls_codestpro5,-2);
								        }
								$la_data[$li_s]=array('codestpro'=>$ls_codestpro,'denominacion'=>$ls_dencuenta,
													  'cuenta'=>$ls_spg_cuenta,'monto'=>$ld_monto);
							}	
							uf_print_totales($ld_monsubtot,$ld_monimp,$ld_montot,$ls_monto,&$io_pdf);
							uf_print_detalle_cuentas($la_data,&$io_pdf);
							unset($la_data);
						}
				     }
			      }
		       }
	     	}
		}
	} 	  	 
	if($lb_valido) // Si no ocurrio ningn error
	{
		$io_pdf->ezStream(); // Mostramos el reporte
	}
	else // Si hubo algn error
	{
		print("<script language=JavaScript>");
		print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
		print(" close();");
		print("</script>");		
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_soc);
?>