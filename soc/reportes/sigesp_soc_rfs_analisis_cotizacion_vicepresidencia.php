<?PHP
    session_start(); 
	ini_set('memory_limit','1024M');
 	ini_set('max_execution_time ','0');  
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");//Teleferico
	header("Cache-Control: private",false);
	//header('Content-type: application/pdf');
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "opener.document.form1.submit();"	;	
		print "close();";
		print "</script>";		
	}	
	//---------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 25/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_compra;
		$ls_descripcion="Generó el Reporte Análisis de Cotización";
		$lb_valido=$io_fun_compra->uf_load_seguridad_reporte("SOC","sigesp_soc_p_analisis_cotizacion.php",$ls_descripcion);
		return $lb_valido;
	}
	//------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_numanacot,$observacion,$ad_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		    Acess: private 
		//	    Arguments: $io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime el banner del reporte
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 17/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,535,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->add_texto(120,22,12,"<b>Análisis de Cotizaciones</b>");
		$io_pdf->add_texto(110,10,11,"VICEPRESIDENCIA DE LA REPUBLICA"); // Agregar el título
		$io_pdf->add_texto(122,13.5,11,"GESTION ADMINISTRATIVA"); // Agregar el título
    	$io_pdf->add_texto(117,17,11,"DEPARTAMENTO DE COMPRAS"); // Agregar el título
		$io_pdf->Rectangle(23,38,735,40);
		$io_pdf->line(200,38,200,78); //LINEA VERTICAL
		$io_pdf->line(500,38,500,78); //LINEA VERTICAL
		$io_pdf->line(23,65,757,65); //LINEA HORIZONTAL
		$io_pdf->add_texto(30,188,10,"ANALISTA");
		$io_pdf->add_texto(27,195,10,$_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"]);
		$io_pdf->add_texto(110,188,10,"JEFE DE COMPRAS");
		$io_pdf->add_texto(195,188,10,"COORDINADOR DE ADMINISTRACION"); 
		$io_pdf->add_texto(230,18,10,"$ad_fecha");		
		$la_data[0]["1"]="<b>Nro.:</b>";
		$la_data[1]["1"]="<b>Observación:</b>";
		$la_data[0]["2"]=$as_numanacot;
		$la_data[1]["2"]=$observacion;
		$la_anchos_col = array(27,173);
		$la_justificaciones = array("left","left");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 11,
							   "lineas"        =>0,
							   "alineacion_col"=>$la_justificaciones);
		$io_pdf->ezSetDy(-60);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);
	}// end function uf_print_encabezado_pagina
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_proveedores($la_cotizaciones,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_proveedores
		//		    Acess: private 
		//	    Arguments: $io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime el el listado de  proveedores participantes
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 18/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_class_report;
		global $io_funciones, $ls_bolivares;		
		$li_totalcotizaciones=count($la_cotizaciones);
		//Imprimiendo primer titulo
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>Cotizaciones</b>";
		$la_anchos_col = array(260);
		$la_justificaciones = array("center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 10,
							   "lineas"        => 1,
							   "alineacion_col"=> $la_justificaciones,
								   "color_fondo"=>array(200,200,200),
								   "grosor_lineas_externas"=>0.5,
								   "grosor_lineas_internas"=>0.5);
		$io_pdf->ezSetDy(-10);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);	//primera fila del item, color gris	
		
		//Imprimiendo titulos columnas
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>Nro. Solicitud</b>";
		$la_data[0]["2"]="<b>Nro. Cotización</b>";
		$la_data[0]["3"]="<b>Proveedor</b>";
		$la_data[0]["4"]="<b>Fecha</b>";
		$la_data[0]["5"]="<b>Monto Total ".$ls_bolivares."</b>";
		$la_data[0]["6"]="<b>I.V.A. ".$ls_bolivares."</b>";

		$la_anchos_col = array(33,33,98,28,43,25);
		$la_justificaciones = array("center","center","center","center","center","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 10,
							   "lineas"        => 2,
							   "alineacion_col"=> $la_justificaciones,
								   "color_fondo"=>array(232,232,232),
								   "grosor_lineas_externas"=>0.5,
								   "grosor_lineas_internas"=>0.5);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);	
		
		//Imprimiendo columnas
		$la_data=array();
		for($li_i=0;$li_i<$li_totalcotizaciones;$li_i++)
		{
			$la_data[$li_i]["1"]=$la_cotizaciones[$li_i+1]["numsolcot"];
			$la_data[$li_i]["2"]=$la_cotizaciones[$li_i+1]["numcot"];
			$la_data[$li_i]["3"]=$la_cotizaciones[$li_i+1]["nompro"];
			$la_data[$li_i]["4"]=$io_funciones->uf_convertirfecmostrar($la_cotizaciones[$li_i+1]["feccot"]);
			$la_data[$li_i]["5"]=number_format($la_cotizaciones[$li_i+1]["montotcot"],2,",",".");
			$la_data[$li_i]["6"]=number_format($la_cotizaciones[$li_i+1]["poriva"],2,",",".");
		}
	
			$la_justificaciones=array();
			$la_justificaciones = array("center","center","left","center","right","right");
			$la_opciones = array("color_texto"     => array(0,0,0),
								   "anchos_col"    => $la_anchos_col,
								   "tamano_texto"  => 10,
								   "lineas"        => 2,
								   "alineacion_col"=> $la_justificaciones,
									   "grosor_lineas_externas"=>0.5,
									   "grosor_lineas_internas"=>0.5);
			$io_pdf->add_tabla(10,$la_data,$la_opciones);	
	}//fin de uf_print_proveedores
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_items($as_tipsolcot,$la_items,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_items
		//		    Acess: private 
		//	    Arguments: $io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los items del analisis de cotizacion y su respectivo proveedor
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 17/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_class_report;
		global $io_funciones, $ls_bolivares;		
		$li_totalitems=count($la_items);
		if($as_tipsolcot=="B")
			$ls_item="Bienes";
		else
			$ls_item="Servicios";
		
		//Imprimiendo primer titulo
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>$ls_item</b>";
		$la_anchos_col = array(260);
		$la_justificaciones = array("center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 10,
							   "lineas"        => 1,
							   "alineacion_col"=> $la_justificaciones,
								   "color_fondo"=>array(200,200,200),
								   "grosor_lineas_externas"=>0.5,
								   "grosor_lineas_internas"=>0.5);
		$io_pdf->ezSetDy(-15);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);	//primera fila del item, color gris	
		
		//Imprimiendo titulos columnas
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>Código</b>";
		$la_data[0]["2"]="<b>Descripcion</b>";
		$la_data[0]["3"]="<b>Proveedor</b>";
		$la_data[0]["4"]="<b>Cant.</b>";
		$la_data[0]["5"]="<b>Precio/Unid. </b>";
		$la_data[0]["6"]="<b>I.V.A. </b>";
		$la_data[0]["7"]="<b>Monto Total </b>";
		$la_data[0]["8"]="<b>Observación</b>";

		$la_anchos_col = array(25,48,37,17,27,27,27,52);
		$la_justificaciones = array("center","center","center","center","center","center","center","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 10,
							   "lineas"        => 2,
							   "alineacion_col"=> $la_justificaciones,
								   "color_fondo"=>array(232,232,232),
								   "grosor_lineas_externas"=>0.5,
								   "grosor_lineas_internas"=>0.5);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);	
		
		//Imprimiendo columnas
		$la_data=array();
		$li_totalprecio=0;
		$li_totaliva=0;
		$li_totalmonto=0;
		for($li_i=0;$li_i<$li_totalitems;$li_i++)
		{
			$la_data[$li_i]["1"]=$la_items[$li_i+1]["codigo"];
			$la_data[$li_i]["2"]=$la_items[$li_i+1]["denominacion"];
			$la_data[$li_i]["3"]=$la_items[$li_i+1]["nompro"];
			$la_data[$li_i]["4"]=number_format($la_items[$li_i+1]["cantidad"],2,",",".");
			$la_data[$li_i]["5"]=number_format($la_items[$li_i+1]["precio"],2,",",".");
			$la_data[$li_i]["6"]=number_format($la_items[$li_i+1]["moniva"],2,",",".");
			$la_data[$li_i]["7"]=number_format($la_items[$li_i+1]["monto"],2,",",".");
			$la_data[$li_i]["8"]=$la_items[$li_i+1]["obsanacot"];
			$li_totalprecio+=$la_items[$li_i+1]["precio"];
			$li_totaliva+=$la_items[$li_i+1]["moniva"];
			$li_totalmonto+=$la_items[$li_i+1]["monto"];
		}
	
			$la_justificaciones=array();
			$la_justificaciones = array("center","left","left","right","right","right","right","left");
			$la_opciones = array("color_texto"     => array(0,0,0),
								   "anchos_col"    => $la_anchos_col,
								   "tamano_texto"  => 9,
								   "lineas"        => 2,
								   "alineacion_col"=> $la_justificaciones,
									   "grosor_lineas_externas"=>0.5,
									   "grosor_lineas_internas"=>0.5);
			$io_pdf->add_tabla(10,$la_data,$la_opciones);	
		
		//imprimiendo totales
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>Totales </b>";
		$la_data[0]["2"]="<b>".number_format($li_totalprecio,2,",",".")."</b>";
		$la_data[0]["3"]="<b>".number_format($li_totaliva,2,",",".")."</b>";
		$la_data[0]["4"]="<b>".number_format($li_totalmonto,2,",",".")."</b>";		

		$la_anchos_col = array(17,32,32,32);
		$la_justificaciones = array("center","right","right","right");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 10,
							   "lineas"        => 2,
							   "alineacion_col"=> $la_justificaciones,
								   "color_fondo"=>array(232,232,232));
		$io_pdf->add_tabla(105,$la_data,$la_opciones);
	

	}//fin de uf_print_detalle

	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_ganadores($as_numanacot,$as_tipsolcot,$aa_ganadores,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_items
		//		    Acess: private 
		//	    Arguments: $io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los ganadores del analisis de cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 26/08/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_class_report;
		global $io_funciones, $ls_bolivares;				
				
		//Imprimiendo primer titulo
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>Resumen de Proveedores Ganadores</b>";
		$la_anchos_col = array(260);
		$la_justificaciones = array("center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 10,
							   "lineas"        => 1,
							   "alineacion_col"=> $la_justificaciones,
								   "color_fondo"=>array(200,200,200),
								   "grosor_lineas_externas"=>0.5,
								   "grosor_lineas_internas"=>0.5);
		$io_pdf->ezSetDy(-15);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);	//primera fila del item, color gris	
		
		//Imprimiendo titulos columnas
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>Código</b>";
		$la_data[0]["2"]="<b>Nombre</b>";
		$la_data[0]["3"]="<b>Subtotal ".$ls_bolivares."</b>";
		$la_data[0]["4"]="<b>Total Cargos ".$ls_bolivares."</b>";
		$la_data[0]["5"]="<b>Monto Total ".$ls_bolivares."</b>";

		$la_anchos_col = array(25,80,52,51,52);
		$la_justificaciones = array("center","center","center","center","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 10,
							   "lineas"        => 2,
							   "alineacion_col"=> $la_justificaciones,
								   "color_fondo"=>array(232,232,232),
								   "grosor_lineas_externas"=>0.5,
								   "grosor_lineas_internas"=>0.5);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);	
		
		$la_data=array();
		$li_totalganadores=count($aa_ganadores);
		$li_totalsubtotal=0;
		$li_totaliva=0;
		$li_totalmonto=0;
		for($li_i=0;$li_i<$li_totalganadores;$li_i++)
		{
			$ls_proveedor		= $aa_ganadores[$li_i]["cod_pro"];
			$ls_cotizacion		= $aa_ganadores[$li_i]["numcot"];
			$ls_tipo_proveedor	= $aa_ganadores[$li_i]["tipconpro"];
			$io_class_report->uf_select_items_proveedor($ls_cotizacion,$ls_proveedor,$as_numanacot,$as_tipsolcot,$la_items,$li_totrow); 
			$io_class_report->uf_calcular_montos($li_totrow,$la_items,$la_totales,$ls_tipo_proveedor);
			$la_data[$li_i]["1"]=$ls_proveedor;
			$la_data[$li_i]["2"]=$aa_ganadores[$li_i]["nompro"];
			$la_data[$li_i]["4"]=number_format($la_totales["subtotal"],2,",",".");
			$la_data[$li_i]["5"]=number_format($la_totales["totaliva"],2,",",".");
			$la_data[$li_i]["6"]=number_format($la_totales["total"],2,",",".");
			$li_totalsubtotal+=$la_totales["subtotal"];
			$li_totaliva+=$la_totales["totaliva"];
			$li_totalmonto+=$la_totales["total"];
		}
		
		//Imprimiendo columnas
	
		$la_justificaciones=array();
		$la_justificaciones = array("center","left","right","right","right");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 9,
							   "lineas"        => 2,
							   "alineacion_col"=> $la_justificaciones,
								   "grosor_lineas_externas"=>0.5,
								   "grosor_lineas_internas"=>0.5);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);	
		
		//imprimiendo totales
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>Totales ".$ls_bolivares."</b>";
		$la_data[0]["2"]="<b>".number_format($li_totalsubtotal,2,",",".")."</b>";
		$la_data[0]["3"]="<b>".number_format($li_totaliva,2,",",".")."</b>";
		$la_data[0]["4"]="<b>".number_format($li_totalmonto,2,",",".")."</b>";		

		$la_anchos_col = array(17,52,51,52);
		$la_justificaciones = array("center","right","right","right");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 10,
							   "lineas"        => 2,
							   "alineacion_col"=> $la_justificaciones,
								   "color_fondo"=>array(232,232,232));
		$io_pdf->add_tabla(98,$la_data,$la_opciones);
	

	}//fin de uf_print_detalle
	//------------------------------------------------------------------------------------------------------------------------------------




	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_analisiscualitativo($aa_cotizaciones,$aa_precios,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_items
		//		    Acess: private 
		//	    Arguments: $io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los ganadores del analisis de cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 26/08/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_class_report;
		global $io_funciones, $ls_bolivares;				
		//Imprimiendo primer titulo
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>ANALISIS DE CUALITATIVO DE ITEMS</b>";
		$la_anchos_col = array(260);
		$la_justificaciones = array("center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 10,
							   "lineas"        => 1,
							   "alineacion_col"=> $la_justificaciones,
								   "color_fondo"=>array(200,200,200),
								   "grosor_lineas_externas"=>0.5,
								   "grosor_lineas_internas"=>0.5);
		$io_pdf->ezSetDy(-15);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);	//primera fila del item, color gris	
		unset($la_data);
		$li_cot=count($aa_cotizaciones);
		$li_ancho=735/($li_cot+1);
		$la_itemsd[1]='<b>ARTICULOS / PROVEEDORES</b>';
		$la_itemsc[1]='';
		$la_itemscols[1]=array('justification'=>'center','width'=>$li_ancho);
		for($li_i=1;$li_i<=$li_cot;$li_i++)
		{
			$la_itemsd[$li_i+1]=$aa_cotizaciones[$li_i]["nompro"];
			$la_itemsc[$li_i+1]='';
			$la_itemscols[$li_i+1]=array('justification'=>'center','width'=>$li_ancho);
		}
		$la_data[1]=$la_itemsd;
		$la_columnas=$la_itemsc;
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>$la_itemscols); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_config);
		$li_pre=count($aa_precios);
		$la_items=array_keys($aa_precios);
		$li_totalitems=count($la_items);
		for($li_j=0;$li_j<$li_totalitems;$li_j++)
		{
			$ls_item=$la_items[$li_j];//print $ls_item."<br>";
			$li_min=min($aa_precios[$ls_item]);//calculando el minimo
			$la_itemsd[1]=$ls_item;
			$la_itemsc[1]='';
			$la_itemscols[1]=array('justification'=>'left','width'=>$li_ancho);
			for($li_i=1;$li_i<=$li_cot;$li_i++)
			{
				$ls_nompro=$aa_cotizaciones[$li_i]["nompro"];
				$ls_monto=$aa_precios[$ls_item][$ls_nompro];
				if($li_min==$ls_monto)					
				{
					$la_itemsd[$li_i+1]=$ls_monto;
					$la_itemscols[$li_i+1]=array('justification'=>'center','width'=>$li_ancho);
				}
				//$ls_nompro=$aa_cotizaciones[$li_i]["nompro"];
		
			}
			$la_data[$li_j]=$la_itemsd;		
		}
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>$la_itemscols); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		
	}//fin de uf_print_detalle
	//------------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_analisisproveedores($aa_cotizaciones,$aa_proveedores,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_analisisproveedores
		//		    Acess: private 
		//	    Arguments: $io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los ganadores del analisis de cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 26/08/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_class_report;
		global $io_funciones, $ls_bolivares;				
				
		//Imprimiendo primer titulo
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>ANALISIS DE CUALITATIVO DE PROVEEDORES</b>";
		$la_anchos_col = array(260);
		$la_justificaciones = array("center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 10,
							   "lineas"        => 1,
							   "alineacion_col"=> $la_justificaciones,
								   "color_fondo"=>array(200,200,200),
								   "grosor_lineas_externas"=>0.5,
								   "grosor_lineas_internas"=>0.5);
		$io_pdf->ezSetDy(-15);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);	//primera fila del item, color gris	
		unset($la_data);
		$li_cot=count($aa_cotizaciones);
		$li_ancho=735/($li_cot+1);
		$la_itemsd[1]='<b>CALIFICADOR / PROVEEDORES</b>';
		$la_itemsc[1]='';
		$la_itemscols[1]=array('justification'=>'center','width'=>$li_ancho);
		for($li_i=1;$li_i<=$li_cot;$li_i++)
		{
			$la_itemsd[$li_i+1]=$aa_cotizaciones[$li_i]["nompro"];
			$la_itemsc[$li_i+1]='';
			$la_itemscols[$li_i+1]=array('justification'=>'center','width'=>$li_ancho);
		}
		$la_data[1]=$la_itemsd;
		$la_columnas=$la_itemsc;
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>$la_itemscols); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_config);
		unset($la_itemsd);
		unset($la_itemsc);
		unset($la_itemscols);
		$li_cot=count($aa_cotizaciones);
		$li_ancho=735/($li_cot+1);
   		$la_items=array_keys($aa_proveedores);
		$totalitems=count($la_items);
		$li_k=0;
		$ls_item1=$la_items[0]; 
		$li_totalcalificadores=count($aa_proveedores[$ls_item1]);
		for($li_i=0;$li_i<$li_totalcalificadores;$li_i++)
		{
			$la_calificador=array_keys($aa_proveedores[$ls_item1]);//Como todos tienen los mismos calificadores, tomo como referencia el primer proveedor
			$ls_calificador=$la_calificador[$li_i];
			$la_itemsd[0]=$ls_calificador;
			$la_itemsc[0]='';
			$la_itemscols[0]=array('justification'=>'center','width'=>$li_ancho);
			for($li_j=0;$li_j<$totalitems;$li_j++)
			{
			    $ls_valor=$aa_proveedores[$la_items[$li_j]][$ls_calificador];	
				$la_itemsd[$li_j+1]=$ls_valor;
				$la_itemsc[$li_j+1]='';
				$la_itemscols[$li_j+1]=array('justification'=>'center','width'=>$li_ancho);
			}
			$la_data[$li_i]=$la_itemsd;		
			$la_columnas=$la_itemsc;
		}
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>$la_itemscols); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		
	}//fin de uf_print_analisisproveedores
	//------------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_analisisprecios($aa_cotizaciones,$aa_precios,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_items
		//		    Acess: private 
		//	    Arguments: $io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los ganadores del analisis de cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 26/08/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_class_report;
		global $io_funciones, $ls_bolivares;				
				
		//Imprimiendo primer titulo
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>DEMOSTRATIVO DEL ANALISIS DE COTIZACION</b>";
		$la_data[1]["1"]="<b>ANALISIS DE PRECIOS</b>";
		$la_anchos_col = array(260);
		$la_justificaciones = array("center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 10,
							   "lineas"        => 1,
							   "alineacion_col"=> $la_justificaciones,
								   "color_fondo"=>array(200,200,200),
								   "grosor_lineas_externas"=>0.5,
								   "grosor_lineas_internas"=>0.5);
		$io_pdf->ezSetDy(-15);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);	//primera fila del item, color gris	
		unset($la_data);
		$li_cot=count($aa_cotizaciones);
		$li_ancho=735/($li_cot+1);
		$la_itemsd[1]='<b>ARTICULOS / PROVEEDORES</b>';
		$la_itemsc[1]='';
		$la_itemscols[1]=array('justification'=>'center','width'=>$li_ancho);
		for($li_i=1;$li_i<=$li_cot;$li_i++)
		{
			$la_itemsd[$li_i+1]=$aa_cotizaciones[$li_i]["nompro"];
			$la_itemsc[$li_i+1]='';
			$la_itemscols[$li_i+1]=array('justification'=>'center','width'=>$li_ancho);
		}
		$la_data[1]=$la_itemsd;
		$la_columnas=$la_itemsc;
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>$la_itemscols); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_config);
		$li_pre=count($aa_precios);
		$la_items=array_keys($aa_precios);
		$li_totalitems=count($la_items);
		for($li_j=0;$li_j<$li_totalitems;$li_j++)
		{
			$ls_item=$la_items[$li_j];//print $ls_item."<br>";
			$li_min=min($aa_precios[$ls_item]);//calculando el minimo
			$la_itemsd[1]=$ls_item;
			$la_itemsc[1]='';
			$la_itemscols[1]=array('justification'=>'left','width'=>$li_ancho);
			for($li_i=1;$li_i<=$li_cot;$li_i++)
			{
				$ls_nompro=$aa_cotizaciones[$li_i]["nompro"];
				$ls_monto=$aa_precios[$ls_item][$ls_nompro];
				if($li_min==$ls_monto)					
				{
					$la_itemsd[$li_i+1]=number_format($ls_monto,2,',','.');
					$la_itemscols[$li_i+1]=array('justification'=>'right','width'=>$li_ancho);
				}
				//$ls_nompro=$aa_cotizaciones[$li_i]["nompro"];
		
			}
			$la_data[$li_j]=$la_itemsd;		
		}
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>$la_itemscols); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		
	}//fin de uf_print_detalle
	//------------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_pagina(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_pagina
		//		    Acess: private 
		//	    Arguments: $io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime el pie del reporte
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 17/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[0]["1"]="<b>Elaborado Por:</b>";
		$la_data[1]["1"]="";
		$la_data[0]["2"]="<b>Revisado: Sub-Gerente Servicios Generales</b>";
		$la_data[1]["2"]="\n";
		$la_anchos_col = array(130,130);
		$la_justificaciones = array("center","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 9,
							   "lineas"        => 2,
							   "alineacion_col"=>$la_justificaciones,
							   "grosor_lineas_externas"=>0.5,
							   "grosor_lineas_internas"=>0.5);
		$io_pdf->y=80;
		$io_pdf->add_tabla(10,$la_data,$la_opciones);
	}// end function uf_print_encabezado_pagina
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
  	
	require_once('../../shared/class_folder/class_pdf.php');
	require_once("sigesp_soc_class_report.php");
	$io_class_report=new sigesp_soc_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();
	require_once("../class_folder/class_funciones_soc.php");
	$io_fun_compra = new class_funciones_soc();
	$ls_tiporeporte=$io_fun_compra->uf_obtenervalor_get("tiporeporte",1);
	$ls_bolivares="Bs.";
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_soc_class_reportbsf.php");
		$io_class_report=new sigesp_soc_class_reportbsf();
		$ls_bolivares="Bs.F.";
	}
	error_reporting(E_ALL);
	set_time_limit(3000);	
	$io_pdf=new class_pdf('LETTER','landscape'); // Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
	$io_pdf->numerar_paginas(7);	
	$io_pdf->set_margenes(0,20,0,0);	
	$ls_tipsolcot=$_GET["tipsolcot"];
	$ls_numanacot=$_GET["numanacot"];
	$ld_fecha=$_GET["fecha"];
	$ls_observacion=$_GET["observacion"];	
	$lb_valido=uf_insert_seguridad();
	if($lb_valido)
	{
		uf_print_encabezado_pagina($ls_numanacot,$ls_observacion,$ld_fecha,$io_pdf);
		$lb_valido=$io_class_report->uf_cargar_cotizaciones($ls_numanacot, $la_cotizaciones);
		if($lb_valido)
		{
			uf_print_proveedores($la_cotizaciones,$io_pdf);
			$lb_valido=$io_class_report->uf_select_items($ls_numanacot,$ls_tipsolcot,$la_items);
			if($lb_valido)
			{
				$io_pdf->set_margenes(20,20,0,0);
				uf_print_items($ls_tipsolcot,$la_items,$io_pdf);
				$la_ganadores=$io_class_report->uf_select_cotizacion_analisis($ls_numanacot,$ls_tipsolcot);
				uf_print_ganadores($ls_numanacot,$ls_tipsolcot,$la_ganadores,$io_pdf);
				$io_pdf->set_margenes(40,20,0,0);
				//uf_print_pie_pagina($io_pdf);
				$io_pdf->ezNewPage();
				$lb_valido=$io_class_report->uf_select_precios_cotizacion($ls_tipsolcot,$la_cotizaciones,$la_precios);//print_r($la_precios);
				if($lb_valido)
				{
					uf_print_analisisprecios($la_cotizaciones,$la_precios,$io_pdf);
				}
				$lb_valido=$io_class_report->uf_analisis_cualitativo($la_cotizaciones,&$la_cualitativo);//print_r($la_cualitativo);
				if(($lb_valido)&&(!empty($la_cualitativo)))
				{
					uf_print_analisisproveedores($la_cotizaciones,$la_cualitativo,$io_pdf);
				}
				$lb_valido=$io_class_report->uf_analisis_cualitativo_items($ls_tipsolcot,$la_cotizaciones,&$la_items);//print_r($aa_items);
				if($lb_valido)
				{
					uf_print_analisiscualitativo($la_cotizaciones,$la_items,$io_pdf);
				}

				$io_pdf->ezStream();
				unset($io_pdf);
			}
			/**/
		}
	}
	if(!$lb_valido)
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que reportar');"); 
		print(" close();");
		print("</script>");	
	}
?> 