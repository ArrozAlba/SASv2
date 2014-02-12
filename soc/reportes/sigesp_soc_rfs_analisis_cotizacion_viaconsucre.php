<?PHP
    session_start(); 
	ini_set('memory_limit','1024M');
 	ini_set('max_execution_time ','0');  
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");//Teleferico
	header("Cache-Control: private",false);
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
		$ls_rifemp = $_SESSION["la_empresa"]["rifemp"];
		$ls_diremp = $_SESSION["la_empresa"]["direccion"];
		$ls_nomemp = $_SESSION["la_empresa"]["nombre"];
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->addJpegFromFile('../../shared/imagebank/cintillo_vycs.jpg',35,525,745,85); // Agregar Logo
		$io_pdf->addText(40,515,7,"<b>".$ls_nomemp."</b>"); // Agregar el título
		$io_pdf->addText(40,505,7,"<b>".$ls_rifemp."</b>"); // Agregar el título
		$io_pdf->addText(40,495,7,"<b>".$ls_diremp."</b>"); // Agregar el título
		//$io_pdf->addText(720,515,8,$ad_fecha); // Agregar Numero de la solicitud
		$io_pdf->addText(720,515,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(722,505,7,date("h:i a")); // Agregar la hora
		$li_tm=$io_pdf->getTextWidth(14,"<b>Análisis de Cotizaciones</b>");
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,480,14,"<b>"."<b>Análisis de Cotizaciones</b>"."</b>"); // Agregar el título 
		$io_pdf->addText(45,465,8,'<b>Nro.:</b>'.$as_numanacot);
		$io_pdf->addText(45,455,8,'<b>Observación:</b>'.$observacion);
		/*
		$io_pdf->addText(25,550,8,$ls_rifemp); // Agregar Numero de la solicitud
		$io_pdf->addText(25,540,8,$ls_diremp); // Agregar Numero de la solicitud*/
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
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
							   "tamano_texto"  => 8,
							   "lineas"        => 1,
							   "alineacion_col"=> $la_justificaciones,
								   "color_fondo"=>array(200,200,200),
								   "grosor_lineas_externas"=>0.5,
								   "grosor_lineas_internas"=>0.5);
		$io_pdf->ezSetDy(-10);
		$io_pdf->add_tabla(5,$la_data,$la_opciones);	//primera fila del item, color gris	
		
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
							   "tamano_texto"  => 8,
							   "lineas"        => 2,
							   "alineacion_col"=> $la_justificaciones,
								   "color_fondo"=>array(232,232,232),
								   "grosor_lineas_externas"=>0.5,
								   "grosor_lineas_internas"=>0.5);
		$io_pdf->add_tabla(5,$la_data,$la_opciones);	
		
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
								   "tamano_texto"  => 8,
								   "lineas"        => 2,
								   "alineacion_col"=> $la_justificaciones,
									   "grosor_lineas_externas"=>0.5,
									   "grosor_lineas_internas"=>0.5);
			$io_pdf->add_tabla(5,$la_data,$la_opciones);	
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
							   "tamano_texto"  => 8,
							   "lineas"        => 1,
							   "alineacion_col"=> $la_justificaciones,
								   "color_fondo"=>array(200,200,200),
								   "grosor_lineas_externas"=>0.5,
								   "grosor_lineas_internas"=>0.5);
		$io_pdf->ezSetDy(-10);
		$io_pdf->add_tabla(5,$la_data,$la_opciones);	//primera fila del item, color gris	
		
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

		$la_anchos_col = array(25,33,57,17,28,25,28,47);
		$la_justificaciones = array("center","center","center","center","center","center","center","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 8,
							   "lineas"        => 2,
							   "alineacion_col"=> $la_justificaciones,
								   "color_fondo"=>array(232,232,232),
								   "grosor_lineas_externas"=>0.5,
								   "grosor_lineas_internas"=>0.5);
		$io_pdf->add_tabla(5,$la_data,$la_opciones);	
		
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
								   "tamano_texto"  => 8,
								   "lineas"        => 2,
								   "alineacion_col"=> $la_justificaciones,
									   "grosor_lineas_externas"=>0.5,
									   "grosor_lineas_internas"=>0.5);
			$io_pdf->add_tabla(5,$la_data,$la_opciones);	
		
		//imprimiendo totales
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>Totales </b>";
		$la_data[0]["2"]="<b>".number_format($li_totalprecio,2,",",".")."</b>";
		$la_data[0]["3"]="<b>".number_format($li_totaliva,2,",",".")."</b>";
		$la_data[0]["4"]="<b>".number_format($li_totalmonto,2,",",".")."</b>";		

		$la_anchos_col = array(17,28,25,28);
		$la_justificaciones = array("center","right","right","right");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 8,
							   "lineas"        => 2,
							   "alineacion_col"=> $la_justificaciones,
								   "color_fondo"=>array(232,232,232));
		$io_pdf->add_tabla(120,$la_data,$la_opciones);
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
							   "tamano_texto"  => 8,
							   "lineas"        => 1,
							   "alineacion_col"=> $la_justificaciones,
								   "color_fondo"=>array(200,200,200),
								   "grosor_lineas_externas"=>0.5,
								   "grosor_lineas_internas"=>0.5);
		$io_pdf->ezSetDy(-10);
		$io_pdf->add_tabla(5,$la_data,$la_opciones);	//primera fila del item, color gris	
		
		//Imprimiendo titulos columnas
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>Código</b>";
		$la_data[0]["2"]="<b>Nombre</b>";
		$la_data[0]["3"]="<b>Subtotal ".$ls_bolivares."</b>";
		$la_data[0]["4"]="<b>Total Cargos ".$ls_bolivares."</b>";
		$la_data[0]["5"]="<b>Monto Total ".$ls_bolivares."</b>";

		$la_anchos_col = array(25,110,42,41,42);
		$la_justificaciones = array("center","center","center","center","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 8,
							   "lineas"        => 2,
							   "alineacion_col"=> $la_justificaciones,
								   "color_fondo"=>array(232,232,232),
								   "grosor_lineas_externas"=>0.5,
								   "grosor_lineas_internas"=>0.5);
		$io_pdf->add_tabla(5,$la_data,$la_opciones);	
		
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
							   "tamano_texto"  => 8,
							   "lineas"        => 2,
							   "alineacion_col"=> $la_justificaciones,
								   "grosor_lineas_externas"=>0.5,
								   "grosor_lineas_internas"=>0.5);
		$io_pdf->add_tabla(5,$la_data,$la_opciones);	
		
		//imprimiendo totales
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>Totales </b>";
		$la_data[0]["2"]="<b>".number_format($li_totalsubtotal,2,",",".")."</b>";
		$la_data[0]["3"]="<b>".number_format($li_totaliva,2,",",".")."</b>";
		$la_data[0]["4"]="<b>".number_format($li_totalmonto,2,",",".")."</b>";		

		$la_anchos_col = array(17,42,41,42);
		$la_justificaciones = array("center","right","right","right");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 10,
							   "lineas"        => 2,
							   "alineacion_col"=> $la_justificaciones,
								   "color_fondo"=>array(232,232,232));
		$io_pdf->add_tabla(123,$la_data,$la_opciones);
	}//fin de uf_print_detalle
	//------------------------------------------------------------------------------------------------------------------------------------
	function uf_print_observaciones($aa_ganadores,$as_forpagcom,$as_diaentcom,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_observaciones
		//		    Acess: private 
		//	    Arguments: $io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los ganadores del analisis de cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 26/08/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('columna1'=>'<b>OBSERVACIONES:</b>');
		$la_data[2]=array('columna1'=>'SE SUGIERE AL PROVEEDOR <b>'.$aa_ganadores[0]["nompro"].'</b>');
		$la_data[3]=array('columna1'=>'POR PRESENTAR LOS MEJORES PRECIOS Y AJUSTARSE A LOS REQUERIMIENTOS DE VIALIDAD Y CONSTRUCCIONES SUCRE, S.A.');
		$la_data[4]=array('columna1'=>'TIEMPO DE ENTREGA: '.$as_diaentcom .' Dias');
		$la_data[5]=array('columna1'=>'FORMA DE PAGO: '.$as_forpagcom);
		$la_data[6]=array('columna1'=>'____________________________________________________________________________________________________________________________');
		$la_columnas=array('columna1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>750, // Ancho de la tabla
						 'maxWidth'=>750, // Ancho Máximo de la tabla
						 'xPos'=>450, // Orientación de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'full','width'=>750))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	
	}
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
		$io_pdf->setStrokeColor(0,0,0);
        $io_pdf->Rectangle(45,40,700,100);
		$io_pdf->line(45,90,745,90); //HORIZONTAL
		$io_pdf->line(45,125,745,125); //HORIZONTAL
		$io_pdf->line(45,75,745,75); //HORIZONTAL
		$io_pdf->line(395,40,395,90);	//VERTICAL		
		$io_pdf->line(278,90,278,140);	//VERTICAL		
		$io_pdf->line(511,90,511,140);	//VERTICAL		
		$io_pdf->addText(55,115,8,"FIRMA");
		$io_pdf->addText(288,115,8,"FIRMA");
		$io_pdf->addText(521,115,8,"FIRMA");
		$io_pdf->addText(55,65,8,"FIRMA");
		$io_pdf->addText(410,65,8,"FIRMA");
		$io_pdf->addText(55,130,8,"GCIA. DE PLANIFICACIÓN Y PRESUPUESTO");
		$io_pdf->addText(288,130,8,"DEPARTAMENTO DE COMPRAS");
		$io_pdf->addText(521,130,8,"GCIA. DE ADMINISTRACIÓN Y FINANZAS");
		$io_pdf->addText(55,78,8,"PRESIDENCIA");
		$io_pdf->addText(410,78,8,"PROVEEDOR");
		$io_pdf->addText(580,30,8,"Patria, Socialismo o Muerte… Venceremos!!!");//<-
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
	$io_pdf->ezStartPageNumbers(730,20,7,'','',1); // Insertar el número de página
	$io_pdf->ezSetCmMargins(5.4,5,1,4); // Configuración de los margenes en centímetros
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
				uf_print_items($ls_tipsolcot,$la_items,$io_pdf);
				$la_ganadores=$io_class_report->uf_select_cotizacion_analisis($ls_numanacot,$ls_tipsolcot);
				uf_print_ganadores($ls_numanacot,$ls_tipsolcot,$la_ganadores,$io_pdf);
				$lb_valido=$io_class_report->uf_select_dt_analisis($la_ganadores[0]["numcot"],$la_ganadores[0]["cod_pro"],$ls_forpagcom,$ls_diaentcom);
				$io_pdf->ezSetCmMargins(5.7,5,1,4); // Configuración de los margenes en centímetros
				uf_print_observaciones($la_ganadores,$ls_forpagcom,$ls_diaentcom,&$io_pdf);
				uf_print_pie_pagina($io_pdf);
				$io_pdf->ezStream();
				unset($io_pdf);
			}
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