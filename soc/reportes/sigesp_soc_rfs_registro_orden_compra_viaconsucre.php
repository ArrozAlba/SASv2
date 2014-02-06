<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Formato de salida  de la Orden de Compra
//  ORGANISMO: Vialidad y Construcciones Sucre
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
	function uf_print_encabezado_pagina($as_estcondat,$as_numordcom,$ad_fecordcom,$as_coduniadm,$as_denuniadm, $as_codfuefin,
	                                   $as_denfuefin,$as_codigo,$as_nombre,$as_conordcom,$as_rifpro,$as_diaplacom,$as_dirpro,
									   $ls_forpagcom,$ld_perentdesde,$ld_perenthasta,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_estcondat  ---> tipo de la orden de compra
		//	    		   as_numordcom ---> numero de la orden de compra
		//	    		   ad_fecordcom ---> fecha de registro de la orden de compra
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 21/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_encabezado;
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->line(15,40,585,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/cintillo_vycs.jpg',30,695,565,90); // Agregar Logo
		if($as_estcondat=="B") 
        {
             $ls_titulo="Orden de Compra";	
			 $ls_titulo_grid="Bienes";
        }
        else
        {
             $ls_titulo="Orden de Servicio";
			 $ls_titulo_grid="Servicios";
        }
		$ls_rifemp = $_SESSION["la_empresa"]["rifemp"];
  		$ls_diremp = $_SESSION["la_empresa"]["direccion"];
  		$ls_nomemp = $_SESSION["la_empresa"]["nombre"];
		$li_tm=$io_pdf->getTextWidth(14,$ls_titulo);
		$tm=296-($li_tm/2);
		$io_pdf->addText(200,665,14,"<b>".$ls_titulo."</b>"); // Agregar el título

		$io_pdf->addText(485,670,8,"<b>   No.:</b>");      // Agregar texto
		$io_pdf->addText(515,670,8,$as_numordcom); // Agregar Numero de la solicitud
		$io_pdf->addText(475,660,8,"<b>  Fecha:</b>"); // Agregar texto
		$io_pdf->addText(515,660,8,$ad_fecordcom); // Agregar la Fecha
		$io_pdf->addText(550,690,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(555,680,7,date("h:i a")); // Agregar la hora
		
		$io_pdf->addText(15,642,8,$ls_nomemp); 	// Nombre de la Empresa
		$io_pdf->addText(15,632,8,$ls_rifemp); // Rif de la Empresa
		$io_pdf->addText(15,622,8,$ls_diremp); // Rif de la Empresa
		
		// cuadro inferior
        $io_pdf->Rectangle(15,40,570,100);
		$io_pdf->line(15,90,585,90); //HORIZONTAL
		$io_pdf->line(15,125,585,125); //HORIZONTAL
		$io_pdf->line(15,75,585,75); //HORIZONTAL
		$io_pdf->line(300,40,300,90);	//VERTICAL		
		$io_pdf->line(205,90,205,140);	//VERTICAL		
		$io_pdf->line(395,90,395,140);	//VERTICAL		
		$io_pdf->addText(17,115,8,"FIRMA");
		$io_pdf->addText(210,115,8,"FIRMA");
		$io_pdf->addText(400,115,8,"FIRMA");
		$io_pdf->addText(17,65,8,"FIRMA");
		$io_pdf->addText(305,65,8,"FIRMA");
		$io_pdf->addText(25,130,8,"GCIA. DE PLANIFICACIÓN Y PRESUPUESTO");
		$io_pdf->addText(235,130,8,"DEPARTAMENTO DE COMPRAS");
		$io_pdf->addText(415,130,8,"GCIA. DE ADMINISTRACIÓN Y FINANZAS");
		$io_pdf->addText(115,78,8,"PRESIDENCIA");
		$io_pdf->addText(415,78,8,"PROVEEDOR");
		$io_pdf->addText(410,30,8,"Patria, Socialismo o Muerte… Venceremos!!!");//<-
		
		$io_pdf->ezSetY(620);
		$la_data[1]=array('columna1'=>'<b>Proveedor</b>  '.$as_nombre.'<b>             Rif</b> '.$as_rifpro,
		                 'columna2'=>'<b>Direccion</b> '.$as_dirpro.'');
		$la_columna=array('columna1'=>'','columna2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>250), // Justificación y ancho de la columna
						 			   'columna2'=>array('justification'=>'left','width'=>320))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		
		$ls_uniadm=$as_coduniadm."  -  ".$as_denuniadm;
		$la_data[1]=array('columna1'=>'<b>Unidad Ejecutora</b>    '.$ls_uniadm,'columna2'=>'<b>Forma de Pago</b>    '.$ls_forpagcom);
		$la_columnas=array('columna1'=>'','columna2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>300), // Justificación y ancho de la columna
						 			   'columna2'=>array('justification'=>'left','width'=>270))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$ls_fuefin=$as_codfuefin."  -  ".$as_denfuefin;
		$la_data[1]=array('columna1'=>'<b>Fuente Financiamiento</b>   '.$ls_fuefin,'columna2'=>'<b> Plazo de Entrega</b>    '.$as_diaplacom);
		$la_columnas=array('columna1'=>'','columna2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>300), // Justificación y ancho de la columna
						 			   'columna2'=>array('justification'=>'left','width'=>270))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('columna1'=>'<b>Período de Entrega    Desde:</b> '.$ld_perentdesde.'    <b>Hasta:</b> '.$ld_perenthasta.'');
		$la_columnas=array('columna1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$la_data[1]=array('columna1'=>'<b>Concepto</b>         '.$as_conordcom);
		$la_columnas=array('columna1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data ---> arreglo de información
		//	    		   io_pdf ---> Instancia de objeto pdf
		//    Description: función que imprime el detalle 
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 21/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_estmodest, $ls_bolivares;
		if($ls_estmodest==1)
		{
			$ls_titulo_grid="Bienes";
		}
		else
		{
			$ls_titulo_grid="Servicios";
		}
		$io_pdf->ezSetDy(-10);
		$la_datatitulo[1]=array('columna1'=>'<b> Detalle de '.$ls_titulo_grid.'</b>');
		$la_columnas=array('columna1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatitulo,$la_columnas,'',$la_config);
		unset($la_datatitulo);
		unset($la_columnas);
		unset($la_config);
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('codigo'=>'<b>Código</b>',
						   'denominacion'=>'<b>Denominacion</b>',
						   'cantidad'=>'<b>Cant.</b>',
						   'unidad'=>'<b>Unidad</b>',
						   'cosuni'=>'<b>Costo '.$ls_bolivares.'</b>',
						   'baseimp'=>'<b>Sub-Total '.$ls_bolivares.'</b>',
						   'cargo'=>'<b>Cargo '.$ls_bolivares.'</b>',
						   'montot'=>'<b>Total '.$ls_bolivares.'</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>115), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>115), // Justificación y ancho de la columna
						 			   'cantidad'=>array('justification'=>'left','width'=>40), // Justificación y ancho de la columna
						 			   'unidad'=>array('justification'=>'center','width'=>45), // Justificación y ancho de la columna
						 			   'cosuni'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
						 			   'baseimp'=>array('justification'=>'right','width'=>65), // Justificación y ancho de la columna
						 			   'cargo'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
						 			   'montot'=>array('justification'=>'right','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_cuentas($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuentas
		//		   Access: private 
		//	    Arguments: la_data ---> arreglo de información
		//	    		   io_pdf ---> Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 21/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		global $ls_estmodest, $ls_bolivares;
		if($ls_estmodest==1)
		{
			$ls_titulo="Estructura Presupuestaria";
		}
		else
		{
			$ls_titulo="Estructura Programatica";
		}
		$la_datatit[1]=array('titulo'=>'<b> Detalle de Presupuesto </b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('codestpro'=>'<b>'.$ls_titulo.'</b>',
						   'cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Denominacion</b>',
						   'monto'=>'<b>Total '.$ls_bolivares.'</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('codestpro'=>array('justification'=>'center','width'=>170), // Justificación y ancho de la columna
						 			   'cuenta'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'denominacio'=>array('justification'=>'center','width'=>200), // Justificación y ancho de la columna
									   'monto'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($li_subtot,$li_totcar,$li_montot,$ls_monlet,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		    Acess: private 
		//	    Arguments: li_subtot ---> Subtotal del articulo
		//	    		   li_totcar -->  Total cargos
		//	    		   li_montot  --> Monto total
		//	    		   ls_monlet   //Monto en letras
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los totales
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 21/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$la_data[1]=array('titulo'=>'<b>Sub Total '.$ls_bolivares.'</b>','contenido'=>$li_subtot,);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificación y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('titulo'=>'<b>Cargos '.$ls_bolivares.'</b>','contenido'=>$li_totcar,);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificación y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('titulo'=>'<b>Total '.$ls_bolivares.'</b>','contenido'=>$li_montot,);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificación y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$io_pdf->ezSetDy(-5);
		$la_data[1]=array('titulo'=>'<b> Son: '.$ls_monlet.'</b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>1, // Sombra entre líneas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabeceramonto_bsf($li_montotaux,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		    Acess: private 
		//	    Arguments: li_montotaux ---> Total de la Orden Bs.F.
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: Función que imprime el total de la Orden de Compra en Bolivares Fuertes.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 25/09/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('titulo'=>'<b>Monto Bs.F.</b>','contenido'=>$li_montotaux,);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificación y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_garantiasoc(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_garantiasoc
		//		    Acess: private 
		//	    Arguments: 
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: Función que imprime el total de la Orden de Compra en Bolivares Fuertes.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 25/09/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('titulo'=>'<b>1- GARANTIA DE IDONEIDAD: </b> El Vendedor garantiza que las mercancías a que se refiere esta Orden de Compra son comerciales, de buena calidad y libres de defectos, cumpliendo las condiciones de idoneidad para su fabricación, según las exigencias de calidad, especificaciones y muestras establecidas por VYCSUCRE, S.A.');
		$la_data[2]=array('titulo'=>'<b>2- PRECIO: </b>El precio de los artículos estipulados en esta Orden de Compra será cubierto por VYCSUCRE, S.A., una vez que haya recibido la totalidad del pedido a su entera y cabal satisfacción, de conformidad con las condiciones estipuladas en esta Orden. Sin embargo, se podrán otorgar anticipos siempre y cuando el vendedor presente la fianza correspondiente.');
		$la_data[3]=array('titulo'=>'<b>3- ENTREGA: </b>Si los bienes indicados en esta Orden de Compra no son entregados en la fecha prevista, VYCSUCRE, S.A. queda en libertad de contratar con otro proveedor.');
		$la_data[4]=array('titulo'=>'<b>4- VIGENCIA: </b>La presente Orden de Compra tendrá una vigencia de 30 días continuos, contados a partir de la fecha de su emisión.');
		$la_data[5]=array('titulo'=>'<b>5- TRÁMITE DE PAGO: </b>Para la tramitación oportuna del pago, el vendedor deberá consignar en el Departamento de Compras los originales de: Factura (que cumpla con las estipulaciones del Marco Jurídico Vigente), Nota de Entrega y esta Orden de Compra.');
		$la_data[6]=array('titulo'=>'<b>6- CONDICION RESOLUTORIA: </b>VYCSUCRE, S.A.  podrá de forma unilateral y en cualquier momento, dejar sin efecto la presente Orden de Compra, sin que ello implique pago de indemnización alguna, solo deberá notificar al vendedor de la extinción de este instrumento indicando los motivos de su decisión.');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'full','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_garantiasos(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_garantiasos
		//		    Acess: private 
		//	    Arguments: 
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: Función que imprime el total de la Orden de Compra en Bolivares Fuertes.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 25/09/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('titulo'=>'<b>1- GARANTIA DE IDONEIDAD: </b>La empresa garantiza que los materiales, insumos, etc., a que se refiere esta Orden de Servicio son comerciales, de buena calidad y libres de defectos, cumpliendo las condiciones de idoneidad para su fabricación, según las exigencias de calidad, especificaciones y muestras establecidas por VYCSUCRE, S.A.');
		$la_data[2]=array('titulo'=>'<b>2- CONDICIONES DE PAGO: </b>El pago de la obra o servicios estipulados en esta Orden, se efectuará según lo establecido entre las partes. Las valuaciones o facturas presentadas, se procesarán una vez verificado que los resultados de la inspección realizada por VYCSUCRE, S.A., se adecuen a lo solicitado en esta Orden de Servicio. Se podrán otorgar anticipos siempre y cuando la empresa presente la fianza correspondiente.');
		$la_data[3]=array('titulo'=>'<b>3- ENTREGA: </b>Si la obra o servicios establecidos en esta Orden no son entregados en la fecha prevista, VYCSUCRE, S.A. queda en libertad de contratar con otro proveedor si así lo desea.');
		$la_data[4]=array('titulo'=>'<b>4- TRÁMITE DE PAGO: </b>Para la tramitación oportuna del pago, se deberá entregar en el Departamento de Compras los originales de: Factura (que cumpla con las estipulaciones del Marco Jurídico Vigente), Nota de Entrega y esta Orden de Servicio.');
		$la_data[5]=array('titulo'=>'<b>5- CONDICION RESOLUTORIA, PRORROGA Y PARALIZACIONES: </b>Las condiciones resolutorias, de prorrogas y paralizaciones se regirán de acuerdo a lo establecido en las <b> Condiciones Generales de Contratación para la Ejecución de Obras, Decreto Nº 1.417 de fecha 31 de Julio de 1996, publicado en la Gaceta Oficial de la República de Venezuela Extraordinaria Nº 5.096.</b>');
		$la_data[6]=array('titulo'=>'<b>6- CLAUSULA PENAL: </b>En caso de incumplimiento de la obligación establecida en la presente Orden de Servicio, bien sea por retardo o inejecución de la misma, la contratista pagará por concepto de Cláusula Penal el UNO POR CIENTO (1%) del monto total de la presente Orden por cada día de retraso.');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'full','width'=>570))); // Justificación y ancho de la columna
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

	//Instancio a la clase de conversión de numeros a letras.
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
		
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_numordcom=$io_fun_soc->uf_obtenervalor_get("numordcom","");
	$ls_estcondat=$io_fun_soc->uf_obtenervalor_get("tipord","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$rs_data= $io_report->uf_select_orden_imprimir($ls_numordcom,$ls_estcondat,&$lb_valido); // Cargar los datos del reporte
	if($lb_valido==false) // Existe algún error ó no hay registros
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
			$io_pdf->ezSetCmMargins(8.5,6,3,3); // Configuración de los margenes en centímetros
			$io_pdf->ezStartPageNumbers(570,20,7,'','',1); // Insertar el número de página
			if ($row=$io_sql->fetch_row($rs_data))
			{
				$ls_numordcom=$row["numordcom"];
				$ls_estcondat=$row["estcondat"];
				$ls_coduniadm=$row["coduniadm"];
				$ls_denuniadm=$row["denuniadm"];
				$ls_codfuefin=$row["codfuefin"];
				$ls_denfuefin=$row["denfuefin"];
				$ls_diaplacom=$row["diaplacom"];
				$ls_forpagcom=$row["forpagcom"];
				$ls_codpro=$row["cod_pro"];
				$ls_nompro=$row["nompro"];
				$ls_rifpro=$row["rifpro"];
				$ls_dirpro=$row["dirpro"];
				$ld_fecordcom=$row["fecordcom"];
				$ls_obscom=$row["obscom"];
				$ld_monsubtot=$row["monsubtot"];
				$ld_monimp=$row["monimp"];
				$ld_montot=$row["montot"];
				$ld_perentdesde=$row["fechentdesde"];
				$ld_perenthasta=$row["fechenthasta"];
				$ld_perentdesde=$io_funciones->uf_convertirfecmostrar($ld_perentdesde);
				$ld_perenthasta=$io_funciones->uf_convertirfecmostrar($ld_perenthasta);
				if($ls_tiporeporte==0)
				{
					$ld_montotaux=$row["montotaux"];
					$ld_montotaux=number_format($ld_montotaux,2,",",".");
				}
				$numalet->setNumero($ld_montot);
				$ls_monto= $numalet->letra();
				$ld_montot=number_format($ld_montot,2,",",".");
				$ld_monsubtot=number_format($ld_monsubtot,2,",",".");
				$ld_monimp=number_format($ld_monimp,2,",",".");
				$ld_fecordcom=$io_funciones->uf_convertirfecmostrar($ld_fecordcom);
		 
				uf_print_encabezado_pagina($ls_estcondat,$ls_numordcom,$ld_fecordcom,$ls_coduniadm,$ls_denuniadm,
				                           $ls_codfuefin,$ls_denfuefin,$ls_codpro,$ls_nompro,$ls_obscom,$ls_rifpro,
										   $ls_diaplacom,$ls_dirpro,$ls_forpagcom,$ld_perentdesde,$ld_perenthasta,&$io_pdf);
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
						$li_i=$li_i+1;
						$ls_codartser=$row["codartser"];
						$ls_denartser=$row["denartser"];
						if($ls_estcondat=="B")
						{
							$ls_unidad=$row["unidad"];
						}
						else
						{
							$ls_unidad="";
						}
						if($ls_unidad=="D")
						{
						   $ls_unidad="Detal";
						}
						elseif($ls_unidad=="M")
						{
						   $ls_unidad="Mayor";
						}
						$li_cantartser=$row["cantartser"];
						$ld_preartser=$row["preartser"];
						$ld_subtotartser=$ld_preartser*$li_cantartser;
						$ld_totartser=$row["monttotartser"];
						$ld_carartser=$ld_totartser-$ld_subtotartser;
						
						
						$li_cantartser=number_format($li_cantartser,2,",",".");
						$ld_preartser=number_format($ld_preartser,2,",",".");
						$ld_subtotartser=number_format($ld_subtotartser,2,",",".");
						$ld_totartser=number_format($ld_totartser,2,",",".");
						$ld_carartser=number_format($ld_carartser,2,",",".");
						$la_data[$li_i]=array('codigo'=>$ls_codartser,'denominacion'=>$ls_denartser,'cantidad'=>$li_cantartser,
											  'unidad'=>$ls_unidad,'cosuni'=>$ld_preartser,'baseimp'=>$ld_subtotartser,
											  'cargo'=>$ld_carartser,'montot'=>$ld_totartser);
					}
					uf_print_detalle($la_data,&$io_pdf);
					unset($la_data);
				    /////DETALLE  DE  LAS  CUENTAS DE GASTOS DE LA ORDEN DE COMPRA
					$rs_datos_cuenta=$io_report->uf_select_cuenta_gasto($ls_numordcom,$ls_estcondat,&$lb_valido); 
					if($lb_valido)
					{
						 $li_totrows = $io_sql->num_rows($rs_datos_cuenta);
						 if ($li_totrows>0)
						 {
							$li_s = 0;
							while($row=$io_sql->fetch_row($rs_datos_cuenta))
							{
								$li_s=$li_s+1;
								$ls_codestpro1=trim($row["codestpro1"]);
								$ls_codestpro2=trim($row["codestpro2"]);
								$ls_codestpro3=trim($row["codestpro3"]);
								$ls_codestpro4=trim($row["codestpro4"]);
								$ls_codestpro5=trim($row["codestpro5"]);
								$ls_codestpro1 = substr($ls_codestpro1,-$_SESSION["la_empresa"]["loncodestpro1"]);
								$ls_codestpro2 = substr($ls_codestpro2,-$_SESSION["la_empresa"]["loncodestpro2"]);
								$ls_codestpro3 = substr($ls_codestpro3,-$_SESSION["la_empresa"]["loncodestpro3"]);
								$ls_codestpro4 = substr($ls_codestpro3,-$_SESSION["la_empresa"]["loncodestpro4"]);
								$ls_codestpro5 = substr($ls_codestpro3,-$_SESSION["la_empresa"]["loncodestpro5"]);
								$ls_spg_cuenta=$row["spg_cuenta"];
								$ld_monto=$row["monto"];
								$ld_monto=number_format($ld_monto,2,",",".");
								$ls_dencuenta="";
								$lb_valido = $io_report->uf_select_denominacionspg($ls_spg_cuenta,$ls_dencuenta);																																						
								if($ls_estmodest==1)
								{
									$ls_codestpro  = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
								}
								else
								{
									$ls_codestpro = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3.'-'.$ls_codestpro4.'-'.$ls_codestpro5;
								}
								$la_data[$li_s]=array('codestpro'=>$ls_codestpro,'denominacion'=>$ls_dencuenta,
													  'cuenta'=>$ls_spg_cuenta,'monto'=>$ld_monto);
							}	
							uf_print_detalle_cuentas($la_data,&$io_pdf);
							unset($la_data);
						}
				     }
			      }
		       }
	     	}
		}
		uf_print_piecabecera($ld_monsubtot,$ld_monimp,$ld_montot,$ls_monto,&$io_pdf);
	} 	 
	if($lb_valido) // Si no ocurrio ningún error
	{
		$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
		$io_pdf->stopObject($io_encabezado);
		$io_pdf->ezNewPage(); // Insertar una nueva página
		if($ls_estcondat=="B")
		{
			uf_print_garantiasoc(&$io_pdf);
		}
		else
		{
			uf_print_garantiasos(&$io_pdf);
		}
		$io_pdf->ezStream(); // Mostramos el reporte
	}
	else // Si hubo algún error
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