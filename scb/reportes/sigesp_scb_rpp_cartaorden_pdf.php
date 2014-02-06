<?php
	session_start();
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	header("X-LIGHTTPD-SID: ".session_id()); 
	$ls_directorio="cheque_configurable";
	$ls_archivo="cheque_configurable/medidas.txt";
	$li_medidas=16;
	//-------------------------------------------------------------------------------------------------
	
	function uf_print_encabezado_pagina($as_numdoc,$as_fecha,&$io_pdf)
	{
		/*----------------------------------------------------------------------------------------
		       Function:	uf_print_encabezado_pagina
			   Acess: 		private 
			   Arguments: 	as_fecha : "Ciudad, dia mes anio"			    		   	
						   	io_pdf   : Instancia de objeto pdf
		       Description: funcion que imprime el encabezados de la carta orden
			   Creado Por: 	Ing. Laura Cabre
		       Fecha Creacion: 26/12/2006 
		/----------------------------------------------------------------------------------------*/
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->saveState();
		$io_pdf->line(20,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,690,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->Rectangle(135,710,450,40);
		$io_pdf->line(380,730,585,730);		
		$io_pdf->line(380,750,380,710);		
		$io_pdf->addText(390,732,11,"<b>Nro.:</b>     ".$as_numdoc); // Nmero de Orden de compra
		$io_pdf->addText(220,725,13,"<b>CARTA ORDEN</b>"); // Agregar el t?ulo
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		//Fecha actual
		$io_pdf->add_texto(118,4.5,10,"<b>".$as_fecha."</b>");		
		
	}// end function uf_print_encabezadopagina
	function uf_print_detalle($la_data,&$io_pdf,$x_pos,$as_tipproben)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 24/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		//Imprimo los detalles tanto `de presupuesto como contable del movimiento
		if ($as_tipproben=='B')
		   {
			 $la_config=array('showHeadings'=>1, // Mostrar encabezados
							  'fontSize' => 9, // Tamaño de Letras
							  'titleFontSize' => 12,  // Tamaño de Letras de los títulos
							  'showLines'=>0, // Mostrar Líneas
							  'colGap'=>1, // Separacion entre las lineas de la Tabla y el Texto.							  
							  'shaded'=>0, // Sombra entre líneas
							  'width'=>580, // Ancho de la tabla
							  'maxWidth'=>580, // Ancho Máximo de la tabla
							  'xOrientation'=>'center', // Orientación de la tabla
							  'cols'=>array('cedbene'=>array('justification'=>'center','width'=>60),// Justificación y ancho de la columna
										    'nombene'=>array('justification'=>'left','width'=>240),// Justificación y ancho de la columna
										    'ctaban'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
										    'monto'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
			 $la_columnas=array('cedbene'=>'<b>Cedula</b>',
							    'nombene'=>'<b>Beneficiario</b>',
							    'ctaban'=>'<b>Cuenta</b>',
							    'monto'=>'<b>Monto</b>');
		   }
		elseif($as_tipproben=='P')
		   {
			 $la_config=array('showHeadings'=>1, // Mostrar encabezados
							  'fontSize' => 9, // Tamaño de Letras
							  'titleFontSize' => 12,  // Tamaño de Letras de los títulos
							  'showLines'=>0, // Mostrar Líneas
							  'colGap'=>1, // Separacion entre las lineas de la Tabla y el Texto.							  
							  'shaded'=>0, // Sombra entre líneas
							  'width'=>580, // Ancho de la tabla
							  'maxWidth'=>580, // Ancho Máximo de la tabla
							  'xOrientation'=>'center', // Orientación de la tabla
							  'cols'=>array('cod_pro'=>array('justification'=>'center','width'=>60),// Justificación y ancho de la columna
										    'nompro'=>array('justification'=>'left','width'=>240),// Justificación y ancho de la columna
										    'ctaban'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la columna
										    'monto'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
			 $la_columnas=array('cod_pro'=>'<b>Codigo</b>',
							    'nompro'=>'<b>Proveedor</b>',
							    'ctaban'=>'<b>Cta. Bancaria</b>',
							    'monto'=>'<b>Monto</b>');
		   }
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
	}// end function uf_print_detalle
	function uf_print_texto($as_texto,&$io_pdf,$y="")
	{
		/*---------------------------------------------------------------------------------------
		       Function: 	uf_print_texto
			   Arguments: 	as_texto: texto a imprimir
			    		   	io_pdf // total de registros que va a tener el reporte
		       Description: funcion que imprime los datos de la carta orden
			   Creado Por: 	Ing. Laura Cabre
		 	   Fecha Creacion: 26/12/2006 
		-----------------------------------------------------------------------------------------*/
		if($y!="")
			$io_pdf->y=$y;
		$la_data[0]["1"]=$as_texto;
		$la_anchos_col = array(170);
		$la_justificaciones = array("left");
		$la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 12,
							   "lineas"=>0,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>1,
							   "margen_vertical"=>1);
		$io_pdf->add_tabla(5,$la_data,$la_opciones);
		$io_pdf-> add_lineas(1);	
		
	}// 	
	function uf_reemplazar($la_data,&$la_texto,&$io_pdf)
	{
		/*---------------------------------------------------------------------------------------
		       Function: 	uf_reemplazar
			   Arguments: 	la_data: datos a reemplazar
			   				ls_texto: texto a ser reemplazado.
			    		   	io_pdf // total de registros que va a tener el reporte
		       Description: funcion que se encarga de reemplazar las palabras claves por sus valores reales
			   Creado Por: 	Ing. Laura Cabre
		 	   Fecha Creacion: 26/12/2006 
		-----------------------------------------------------------------------------------------*/
		$la_claves=array_keys($la_data);
		for($li_i=0;$li_i<count($la_claves);$li_i++)
		{
			$la_texto["encabezado"]=str_replace("@".$la_claves[$li_i]."@",$la_data[$la_claves[$li_i]],$la_texto["encabezado"]);
			$la_texto["cuerpo"]=str_replace("@".$la_claves[$li_i]."@",$la_data[$la_claves[$li_i]],$la_texto["cuerpo"]);
			$la_texto["pie"]=str_replace("@".$la_claves[$li_i]."@",$la_data[$la_claves[$li_i]],$la_texto["pie"]);
		}
	}
	
	function uf_convertir($ls_numero)
	{
		$ls_numero=str_replace(".","",$ls_numero);
		$ls_numero=str_replace(",",".",$ls_numero);
		return $ls_numero;
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------Declaraciones e Inicializaciones-----------------------//
	//uf_inicializar_variables();
	require_once('../../shared/class_folder/class_pdf.php');
	require_once("../../shared/class_folder/class_mensajes.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_datastore.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();
	$in=new sigesp_include();
	$con=$in->uf_conectar();
	$io_sql=new class_sql($con);	
	$io_funciones=new class_funciones();				
	$io_msg      = new class_mensajes();
	$io_pdf = new class_pdf("LETTER","portrait");
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');
	$io_pdf->set_margenes(20,20,20,20);
	require_once("sigesp_scb_report.php");
	$class_report=new sigesp_scb_report($con);
	$ds_voucher=new class_datastore();	
	$ds_dt_scg=new class_datastore();				
	$ds_dt_spg=new class_datastore();
	//Instancio a la clase de conversi� de numeros a letras.
	require_once("../../shared/class_folder/cnumero_letra.php");
	$numalet= new cnumero_letra();	
	//imprime numero con los cambios
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_codigo=$_GET["codigo"];	
	$ls_fecha_corta=date("d/m/Y");
	$lb_valido=$class_report->uf_formato_cartaorden($ls_codigo,$la_data);
	if(rtrim($la_data["archrtf"][1])!="")
	{
		$ls_codban	  = $_GET["codban"];
		$ls_ctaban	  = $_GET["ctaban"];
		$ls_numdoc	  = $_GET["numdoc"];	
		$ls_tipproben =	$_GET["tipproben"];
		header('Location: sigesp_scb_rpp_cartaorden_word.php?codigo='.$ls_codigo.'&codban='.$ls_codban.'&ctaban='.$ls_ctaban.'&numdoc='.$ls_numdoc.'&chevau=&codope=ND&tipproben='.$ls_tipproben);
	}

	if(!array_key_exists("opener",$_GET))
	{
		$ls_codban	  = $_GET["codban"];
		$ls_ctaban	  = $_GET["ctaban"];
		$ls_numdoc	  = $_GET["numdoc"];	
		$ls_tipproben =	$_GET["tipproben"];
		$la_cartaorden=$class_report->uf_select_cartaorden($ls_numdoc,$ls_codban,$ls_ctaban);
		if((!$lb_valido) || (count ($la_cartaorden)==0))
		{
			$io_msg->message("Error en reporte !!!");		
			print "<script>";
			print "close();";
			print "</script>";
		}
		$li_cont=count ($la_cartaorden);
		$li_totales=0;
		for ($i=1;$i<=$li_cont;$i++)
		{
			$li_acum=$la_cartaorden["monto"][$i];
			$li_totales=$li_totales+$li_acum;
		}
		$la_campo["banco"]			= $la_cartaorden["nomban"][1];
		$la_campo["ciudad"]			= $_SESSION["la_empresa"]["ciuemp"];
		$la_campo["fecha"]			= $ls_fecha_corta;
		$la_campo["gerente"]		= $la_cartaorden["gerban"][1];
		$la_campo["cartaorden"]		= $la_cartaorden["numcarord"][1];
		$la_campo["documento"]		= $la_cartaorden["numdoc"][1];
		$la_campo["cuentabancaria"] = $la_cartaorden["ctaban"][1];
		$la_campo["monto"]			= number_format($li_totales,2,",",".");
		$la_campo["montoletras"]	= $numalet->uf_convertir_letra($li_totales,'','');;
		$la_campo["tipocuenta"]		= $la_cartaorden["nomtipcta"][1];
		$la_campo["empresa"]		= $_SESSION["la_empresa"]["nombre"];
		uf_reemplazar($la_campo,$la_data,&$io_pdf);
	}
	
	$class_report->SQL->begin_transaction();		
	$ls_mes = $io_fecha->uf_load_nombre_mes(substr($ls_fecha_corta,3,2));
	$ls_fecha=$_SESSION["la_empresa"]["ciuemp"].", ".substr($ls_fecha_corta,0,2)." de ".$ls_mes." de ".substr($ls_fecha_corta,6,4)."" ;
	uf_print_encabezado_pagina($ls_numdoc,$ls_fecha,$io_pdf); // Imprimimos el encabezado de la p�ina
	uf_print_texto($la_data["encabezado"][1],$io_pdf,650);
	uf_print_texto($la_data["cuerpo"][1],$io_pdf);
	$la_data_dt=$class_report->uf_select_dt_cartaorden($ls_numdoc,$ls_codban,$ls_ctaban,$ls_tipproben);
	uf_print_detalle($la_data_dt,$io_pdf,450,$ls_tipproben); // Imprimimos el detalle 	
	uf_print_texto($la_data["pie"][1],$io_pdf);		
    $io_pdf->ezStream();	
?> 